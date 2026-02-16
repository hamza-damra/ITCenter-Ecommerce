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

// NOTE: We use Schedule::call() + Artisan::call() instead of Schedule::command()
// because shell_exec/proc_open are DISABLED on CPanel shared hosting.
// Schedule::command() spawns a subprocess (needs shell_exec), Schedule::call() runs in-process.

if ($interval !== 'disabled') {
    $backupSchedule = Schedule::call(function () {
        Artisan::call('backup:create');
        \Log::info('Scheduled backup completed', ['output' => Artisan::output()]);
    })->name('auto-backup')->withoutOverlapping(10);

    switch ($interval) {
        case '5_minutes':
            $backupSchedule->everyFiveMinutes();
            break;

        case '15_minutes':
            $backupSchedule->everyFifteenMinutes();
            break;

        case '30_minutes':
            $backupSchedule->everyThirtyMinutes();
            break;

        case 'hourly':
            $backupSchedule->hourly();
            break;

        case '6_hours':
            $backupSchedule->everySixHours();
            break;

        case '12_hours':
            $backupSchedule->twiceDaily(2, 14);
            break;

        case 'weekly':
            $day = config('backup.weekly_day', 0);
            $time = config('backup.daily_time', '02:00');
            $backupSchedule->weeklyOn($day, $time);
            break;

        case 'monthly':
            $day = config('backup.monthly_day', 1);
            $time = config('backup.daily_time', '02:00');
            $backupSchedule->monthlyOn($day, $time);
            break;

        case 'daily':
        default:
            $time = config('backup.daily_time', '02:00');
            $backupSchedule->dailyAt($time);
            break;
    }
}

// Run cleanup after backup (uses retention policy, NOT force mode)
Schedule::call(function () {
    Artisan::call('backup:cleanup', ['--yes' => true]);
    \Log::info('Scheduled backup cleanup completed', ['output' => Artisan::output()]);
})->dailyAt('03:00')
  ->name('backup-cleanup')
  ->withoutOverlapping(30);

// Cron heartbeat — writes timestamp every minute to verify cron is running
Schedule::call(function () {
    file_put_contents(storage_path('app/cron-heartbeat.txt'), now()->toDateTimeString());
})->everyMinute()->name('cron-heartbeat');

// Clean up expired backups daily
Schedule::call(function () {
    Artisan::call('backup:cleanup-expired');
    \Log::info('Scheduled expired backup cleanup completed', ['output' => Artisan::output()]);
})->daily()
  ->at('04:00')
  ->name('backup-cleanup-expired')
  ->withoutOverlapping(30);
