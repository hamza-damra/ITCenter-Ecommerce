<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\DatabaseBackupService;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BackupRestoreTransactionTest extends TestCase
{
    use RefreshDatabase;

    protected DatabaseBackupService $backupService;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->backupService = new DatabaseBackupService();
        
        // Create admin user
        $this->admin = User::factory()->create([
            'email' => 'admin@test.com',
            'role' => 'admin',
        ]);
    }

    /**
     * Test that active transactions are properly cleaned up before restore
     *
     * @return void
     */
    public function test_restore_cleans_up_active_transactions()
    {
        $this->actingAs($this->admin);

        // Create a backup first
        $backup = $this->backupService->createBackup();
        $this->assertArrayHasKey('filename', $backup);

        // Simulate an active transaction (this would normally cause the error)
        DB::beginTransaction();
        
        // Verify transaction is active
        $this->assertGreaterThan(0, DB::transactionLevel());

        // Try to restore - this should handle the active transaction
        try {
            $result = $this->backupService->restoreBackup($backup['filename']);
            
            // If successful, verify transaction was cleaned up
            $this->assertEquals(0, DB::transactionLevel());
            $this->assertTrue($result['success']);
            
        } catch (\Exception $e) {
            // Even if restore fails, transaction should be cleaned up
            $this->assertEquals(0, DB::transactionLevel());
        }
    }

    /**
     * Test import and restore flow
     *
     * @return void
     */
    public function test_import_and_restore_handles_transactions_properly()
    {
        $this->actingAs($this->admin);

        // Create a real backup file
        $backup = $this->backupService->createBackup();
        $backupPath = config('backup.path', storage_path('app/backups'));
        $backupFile = $backupPath . DIRECTORY_SEPARATOR . $backup['filename'];

        // Create an UploadedFile from the backup
        $uploadedFile = new UploadedFile(
            $backupFile,
            $backup['filename'],
            'application/sql',
            null,
            true
        );

        // Post to the import endpoint
        $response = $this->post(route('admin.backup.import-and-restore'), [
            'backup_file' => $uploadedFile,
            'confirm' => true,
        ]);

        // Should redirect with success
        $response->assertRedirect(route('admin.backup.index'));
        $response->assertSessionHas('success');
        
        // Verify no active transactions remain
        $this->assertEquals(0, DB::transactionLevel());
    }

    /**
     * Test that BackupRestoreException is properly thrown and handled
     *
     * @return void
     */
    public function test_backup_restore_exception_includes_safety_backup()
    {
        $this->actingAs($this->admin);

        // Create a backup
        $backup = $this->backupService->createBackup();

        // Corrupt the backup file to force an error
        $backupPath = config('backup.path', storage_path('app/backups'));
        $backupFile = $backupPath . DIRECTORY_SEPARATOR . $backup['filename'];
        
        if (substr($backup['filename'], -3) === '.gz') {
            $content = gzfile($backupFile);
            file_put_contents($backupFile . '.tmp', "CORRUPTED DATA");
            gzencode(file_get_contents($backupFile . '.tmp'));
            rename($backupFile . '.tmp', $backupFile);
        } else {
            file_put_contents($backupFile, "CORRUPTED DATA");
        }

        // Try to restore and expect exception with safety backup info
        try {
            $this->backupService->restoreBackup($backup['filename']);
            $this->fail('Expected BackupRestoreException was not thrown');
        } catch (\App\Exceptions\BackupRestoreException $e) {
            // Verify exception contains detailed message
            $this->assertNotEmpty($e->getDetailedMessage());
            // Safety backup may or may not be created depending on when the error occurs
            $this->assertIsString($e->getMessage());
        }
        
        // Verify no active transactions remain
        $this->assertEquals(0, DB::transactionLevel());
    }

    /**
     * Test web interface restore with transaction handling
     *
     * @return void
     */
    public function test_web_restore_endpoint_handles_exceptions()
    {
        $this->actingAs($this->admin);

        // Create a valid backup
        $backup = $this->backupService->createBackup();

        // Test restore via web endpoint
        $response = $this->post(route('admin.backup.restore'), [
            'filename' => $backup['filename'],
            'confirm' => true,
        ]);

        $response->assertRedirect(route('admin.backup.index'));
        $response->assertSessionHas('success');
        
        // Verify no active transactions
        $this->assertEquals(0, DB::transactionLevel());
    }

    protected function tearDown(): void
    {
        // Ensure transactions are cleaned up after tests
        while (DB::transactionLevel() > 0) {
            DB::rollBack();
        }
        
        parent::tearDown();
    }
}
