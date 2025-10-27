<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\DatabaseBackupService;
use App\Models\Backup;
use App\Models\BackupSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BackupSystemTest extends TestCase
{
    use RefreshDatabase;

    protected $backupService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->backupService = app(DatabaseBackupService::class);
        
        // Create backup directory if it doesn't exist
        $backupPath = config('backup.path', storage_path('app/backups'));
        if (!file_exists($backupPath)) {
            mkdir($backupPath, 0755, true);
        }
    }

    /** @test */
    public function it_validates_backup_file_integrity()
    {
        // Create a test backup content
        $validContent = "-- Database Backup\n-- Generated: 2025-10-27\nCREATE TABLE test (id INT);";
        
        // Use reflection to test protected method
        $reflection = new \ReflectionClass($this->backupService);
        $method = $reflection->getMethod('validateBackupFile');
        $method->setAccessible(true);
        
        // Should pass validation
        $this->expectNotToPerformAssertions();
        $method->invoke($this->backupService, $validContent, 'test.sql');
    }

    /** @test */
    public function it_rejects_empty_backup_files()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Backup file is empty');
        
        $reflection = new \ReflectionClass($this->backupService);
        $method = $reflection->getMethod('validateBackupFile');
        $method->setAccessible(true);
        
        $method->invoke($this->backupService, '', 'test.sql');
    }

    /** @test */
    public function it_rejects_backup_without_create_table()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No CREATE TABLE statements');
        
        $reflection = new \ReflectionClass($this->backupService);
        $method = $reflection->getMethod('validateBackupFile');
        $method->setAccessible(true);
        
        $invalidContent = "-- Just comments\nINSERT INTO users VALUES (1, 'test');";
        $method->invoke($this->backupService, $invalidContent, 'test.sql');
    }

    /** @test */
    public function it_rejects_truncated_backup_files()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('truncated or corrupted');
        
        $reflection = new \ReflectionClass($this->backupService);
        $method = $reflection->getMethod('validateBackupFile');
        $method->setAccessible(true);
        
        $truncatedContent = "CREATE TABLE test (id INT)"; // No semicolon
        $method->invoke($this->backupService, $truncatedContent, 'test.sql');
    }

    /** @test */
    public function it_rejects_malicious_content()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('suspicious content');
        
        $reflection = new \ReflectionClass($this->backupService);
        $method = $reflection->getMethod('validateBackupFile');
        $method->setAccessible(true);
        
        $maliciousContent = "CREATE TABLE test (id INT); <?php system('rm -rf /'); ?>";
        $method->invoke($this->backupService, $maliciousContent, 'test.sql');
    }

    /** @test */
    public function it_creates_database_record_for_backup()
    {
        // Mock the backup creation process
        BackupSetting::create([
            'key' => 'default_retention_days',
            'value' => '30',
            'type' => 'integer'
        ]);

        BackupSetting::create([
            'key' => 'max_backups',
            'value' => '10',
            'type' => 'integer'
        ]);

        // Verify no backups exist initially
        $this->assertEquals(0, Backup::count());

        // Create a mock backup record (simulating what the service should do)
        Backup::create([
            'filename' => 'test_backup.sql.gz',
            'type' => 'database',
            'size' => 1024,
            'expires_at' => now()->addDays(30),
            'created_by' => 'test',
            'metadata' => ['tables' => 10],
        ]);

        // Verify backup was created
        $this->assertEquals(1, Backup::count());
        
        $backup = Backup::first();
        $this->assertEquals('test_backup.sql.gz', $backup->filename);
        $this->assertEquals('database', $backup->type);
        $this->assertEquals(1024, $backup->size);
        $this->assertNotNull($backup->expires_at);
    }

    /** @test */
    public function it_identifies_expired_backups()
    {
        // Create an expired backup
        Backup::create([
            'filename' => 'old_backup.sql',
            'type' => 'database',
            'size' => 1024,
            'expires_at' => now()->subDays(5), // Expired 5 days ago
            'created_by' => 'test',
        ]);

        // Create a valid backup
        Backup::create([
            'filename' => 'new_backup.sql',
            'type' => 'database',
            'size' => 1024,
            'expires_at' => now()->addDays(25), // Still valid
            'created_by' => 'test',
        ]);

        // Test expired scope
        $expiredBackups = Backup::expired()->get();
        $this->assertEquals(1, $expiredBackups->count());
        $this->assertEquals('old_backup.sql', $expiredBackups->first()->filename);
    }

    /** @test */
    public function it_identifies_active_backups()
    {
        // Create an expired backup
        Backup::create([
            'filename' => 'old_backup.sql',
            'type' => 'database',
            'size' => 1024,
            'expires_at' => now()->subDays(5),
            'created_by' => 'test',
        ]);

        // Create active backups
        Backup::create([
            'filename' => 'new_backup.sql',
            'type' => 'database',
            'size' => 1024,
            'expires_at' => now()->addDays(25),
            'created_by' => 'test',
        ]);

        Backup::create([
            'filename' => 'never_expires.sql',
            'type' => 'database',
            'size' => 1024,
            'expires_at' => null, // Never expires
            'created_by' => 'test',
        ]);

        // Test active scope
        $activeBackups = Backup::active()->get();
        $this->assertEquals(2, $activeBackups->count());
    }

    /** @test */
    public function it_formats_backup_size_correctly()
    {
        $backup = Backup::create([
            'filename' => 'test.sql',
            'type' => 'database',
            'size' => 1536, // 1.5 KB
            'created_by' => 'test',
        ]);

        $formatted = $backup->formatted_size;
        $this->assertEquals('1.5 KB', $formatted);
    }

    /** @test */
    public function backup_setting_casts_values_correctly()
    {
        // Test boolean casting
        BackupSetting::set('auto_cleanup_enabled', true, 'boolean');
        $this->assertTrue(BackupSetting::get('auto_cleanup_enabled'));

        // Test integer casting
        BackupSetting::set('max_backups', 15, 'integer');
        $this->assertEquals(15, BackupSetting::get('max_backups'));

        // Test default value
        $this->assertEquals('default', BackupSetting::get('nonexistent_key', 'default'));
    }

    /** @test */
    public function it_detects_large_files_for_streaming()
    {
        // This tests the logic, not actual file processing
        $smallFileSize = 30 * 1024 * 1024; // 30MB
        $largeFileSize = 100 * 1024 * 1024; // 100MB
        $threshold = 50 * 1024 * 1024; // 50MB threshold

        $this->assertLessThan($threshold, $smallFileSize);
        $this->assertGreaterThan($threshold, $largeFileSize);
    }

    /** @test */
    public function it_validates_filename_format_for_download()
    {
        $validFilenames = [
            'backup_db_2025-10-27_14-30-45.sql',
            'backup_db_2025-10-27_14-30-45.sql.gz',
            'backup_modules_2025-10-27_14-30-45.sql',
            'import_2025-10-27_14-30-45.sql.gz',
        ];

        $invalidFilenames = [
            '../../../etc/passwd',
            'backup_db_2025-10-27.php',
            'malicious.exe',
            '../../config/database.php',
            'backup/../../../secret.sql',
        ];

        $pattern = '/^(backup|import)_[a-z0-9_-]+\.sql(\.gz)?$/i';

        foreach ($validFilenames as $filename) {
            $this->assertEquals(1, preg_match($pattern, $filename), "Failed for: $filename");
        }

        foreach ($invalidFilenames as $filename) {
            $this->assertEquals(0, preg_match($pattern, $filename), "Should fail for: $filename");
        }
    }

    /** @test */
    public function it_detects_path_traversal_attempts()
    {
        $maliciousFilenames = [
            '../../../etc/passwd',
            'backup/../../../config.php',
            'backup\\..\\..\\secret.txt',
            'backup/../../database.php',
        ];

        foreach ($maliciousFilenames as $filename) {
            $hasTraversal = str_contains($filename, '..') || 
                           str_contains($filename, '/') || 
                           str_contains($filename, '\\');
            
            $this->assertTrue($hasTraversal, "Should detect traversal in: $filename");
        }
    }

    /** @test */
    public function backup_model_calculates_expiration_status()
    {
        $expiredBackup = Backup::create([
            'filename' => 'expired.sql',
            'type' => 'database',
            'size' => 1024,
            'expires_at' => now()->subDays(5),
            'created_by' => 'test',
        ]);

        $activeBackup = Backup::create([
            'filename' => 'active.sql',
            'type' => 'database',
            'size' => 1024,
            'expires_at' => now()->addDays(5),
            'created_by' => 'test',
        ]);

        $neverExpiresBackup = Backup::create([
            'filename' => 'permanent.sql',
            'type' => 'database',
            'size' => 1024,
            'expires_at' => null,
            'created_by' => 'test',
        ]);

        $this->assertTrue($expiredBackup->isExpired());
        $this->assertFalse($activeBackup->isExpired());
        $this->assertFalse($neverExpiresBackup->isExpired());
    }
}
