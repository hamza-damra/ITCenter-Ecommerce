<?php
// Quick functional test for backup expiration + cleanup
// Run: php test_backup_expire.php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\DatabaseBackupService;
use App\Models\Backup;
use App\Models\BackupSetting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Carbon;

function line($msg='') { echo $msg."\n"; }

line("🧪 Backup Expiration & Cleanup Test");
line(str_repeat('=', 36));

// 1) Ensure auto cleanup is enabled
BackupSetting::set('auto_cleanup_enabled', true, 'boolean');
line("✓ Auto cleanup enabled setting set to true");

// 2) Create a backup file
$service = app(DatabaseBackupService::class);
$result = $service->createBackup();
line("✓ Backup created: {$result['filename']} ({$result['size']} bytes)");

// 3) Create Backup DB record with 30-second expiry
$expiresAt = Carbon::now()->addSeconds(30);
$record = Backup::create([
    'filename'   => $result['filename'],
    'type'       => 'database',
    'size'       => $result['size'],
    'expires_at' => $expiresAt,
    'created_by' => 'auto-test',
    'metadata'   => ['scenario' => '30s-expire']
]);
line("✓ DB record saved with expiry: " . $expiresAt->toDateTimeString());

// 4) First cleanup attempt (should NOT delete yet)
Artisan::call('backup:cleanup-expired');
$code = Artisan::output();
line("→ First cleanup output:\n" . trim($code));
$existsBefore = Backup::where('id', $record->id)->exists();
line("Record exists after first cleanup: " . ($existsBefore ? 'YES' : 'NO'));

// 5) Wait 40 seconds, then run cleanup again
line("⏳ Waiting 40 seconds for expiry...");
sleep(40);

Artisan::call('backup:cleanup-expired');
$code2 = Artisan::output();
line("→ Second cleanup output:\n" . trim($code2));
$existsAfter = Backup::where('id', $record->id)->exists();
line("Record exists after second cleanup: " . ($existsAfter ? 'YES' : 'NO'));

// 6) File existence check
$backupPath = config('backup.path', storage_path('app/backups')) . DIRECTORY_SEPARATOR . $result['filename'];
$fileExists = file_exists($backupPath) ? 'YES' : 'NO';
line("File exists after second cleanup: {$fileExists}");

line(str_repeat('-', 36));
line("✅ Test complete");
