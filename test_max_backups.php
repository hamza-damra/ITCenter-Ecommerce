<?php
// Test max backup count enforcement
// Run: php test_max_backups.php

require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\DatabaseBackupService;
use App\Models\BackupSetting;

function line($msg='') { echo $msg."\n"; }

line("🧪 Testing Max Backup Count Enforcement");
line(str_repeat('=', 40));

// 1) Set max_backups to 3
BackupSetting::set('max_backups', 3, 'integer');
$maxBackups = BackupSetting::get('max_backups');
line("✓ Set max_backups setting to: {$maxBackups}");
line();

// 2) Create service and check current count
$service = app(DatabaseBackupService::class);
$beforeBackups = $service->listBackups();
$beforeCount = count($beforeBackups);
line("Current backup count: {$beforeCount}");
line();

// 3) Create 5 new backups
line("Creating 5 new backups...");
for ($i = 1; $i <= 5; $i++) {
    $result = $service->createBackup();
    line("  {$i}. Created: {$result['filename']}");
    sleep(1); // Small delay to ensure unique timestamps
}
line();

// 4) Check final count
$afterBackups = $service->listBackups();
$afterCount = count($afterBackups);
line("Final backup count: {$afterCount}");
line();

// 5) Verify enforcement
if ($afterCount <= $maxBackups) {
    line("✅ SUCCESS: Max backup limit enforced!");
    line("   Expected max: {$maxBackups}, Actual: {$afterCount}");
} else {
    line("❌ FAILURE: Max backup limit NOT enforced!");
    line("   Expected max: {$maxBackups}, Actual: {$afterCount}");
}
line();

// 6) Show list of remaining backups
line("Remaining backups:");
foreach ($afterBackups as $idx => $backup) {
    line("  " . ($idx+1) . ". {$backup['filename']} ({$backup['size_formatted']})");
}
line();

line(str_repeat('-', 40));
line("Test complete");
