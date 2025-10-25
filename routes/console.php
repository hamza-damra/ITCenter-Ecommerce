<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Database Backup Scheduling
$schedule = config('backup.schedule');
$backupCommand = Schedule::command('backup:create');

switch ($schedule) {
    case 'daily':
        $time = config('backup.daily_time', '02:00');
        $backupCommand->dailyAt($time);
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
