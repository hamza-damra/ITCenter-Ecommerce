<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Database Backup Scheduling - reads interval from backup_settings table
try {
    $interval = \App\Models\BackupSetting::get('auto_backup_interval', 'daily');
} catch (\Exception $e) {
    $interval = config('backup.schedule', 'daily');
}

if ($interval !== 'disabled') {
    $backupCommand = Schedule::command('backup:create')->withoutOverlapping();

    switch ($interval) {
        case '5_minutes':
            $backupCommand->everyFiveMinutes();
            break;

        case '15_minutes':
            $backupCommand->everyFifteenMinutes();
            break;

        case '30_minutes':
            $backupCommand->everyThirtyMinutes();
            break;

        case 'hourly':
            $backupCommand->hourly();
            break;

        case '6_hours':
            $backupCommand->everySixHours();
            break;

        case '12_hours':
            $backupCommand->twiceDaily(2, 14);
            break;

        case 'weekly':
            $day = config('backup.weekly_day', 0);
            $time = config('backup.daily_time', '02:00');
            $backupCommand->weeklyOn($day, $time);
            break;

        case 'monthly':
            $day = config('backup.monthly_day', 1);
            $time = config('backup.daily_time', '02:00');
            $backupCommand->monthlyOn($day, $time);
            break;

        case 'daily':
        default:
            $time = config('backup.daily_time', '02:00');
            $backupCommand->dailyAt($time);
            break;
    }
}

// Run cleanup after backup
Schedule::command('backup:cleanup --force')
    ->dailyAt('03:00')
    ->name('backup-cleanup')
    ->withoutOverlapping();

// Clean up expired backups daily
Schedule::command('backup:cleanup-expired')
    ->daily()
    ->at('04:00')
    ->name('backup-cleanup-expired')
    ->withoutOverlapping();
