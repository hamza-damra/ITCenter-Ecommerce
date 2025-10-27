<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Schedule automatic backups based on config
        $scheduleType = config('backup.schedule', 'daily');
        
        switch ($scheduleType) {
            case 'testing':
                // TEST MODE: Backup every 30 seconds
                $schedule->command('backup:create')
                    ->everyThirtySeconds()
                    ->onSuccess(function () {
                        \Log::info('[TEST MODE] Scheduled backup completed successfully at ' . now());
                    })
                    ->onFailure(function () {
                        \Log::error('[TEST MODE] Scheduled backup failed at ' . now());
                    });
                break;
                
            case 'daily':
                $time = config('backup.daily_time', '02:00');
                $schedule->command('backup:create')
                    ->dailyAt($time)
                    ->onSuccess(function () {
                        \Log::info('Scheduled backup completed successfully');
                    })
                    ->onFailure(function () {
                        \Log::error('Scheduled backup failed');
                    });
                break;
                
            case 'weekly':
                $day = config('backup.weekly_day', 0); // 0 = Sunday
                $time = config('backup.daily_time', '02:00');
                $schedule->command('backup:create')
                    ->weeklyOn($day, $time)
                    ->onSuccess(function () {
                        \Log::info('Weekly scheduled backup completed successfully');
                    });
                break;
                
            case 'monthly':
                $day = config('backup.monthly_day', 1);
                $time = config('backup.daily_time', '02:00');
                $schedule->command('backup:create')
                    ->monthlyOn($day, $time)
                    ->onSuccess(function () {
                        \Log::info('Monthly scheduled backup completed successfully');
                    });
                break;
        }

        // Run cleanup of expired backups daily (if auto cleanup is enabled)
        $schedule->command('backup:cleanup-expired')
            ->daily()
            ->at('03:00')
            ->when(function () {
                return \App\Models\BackupSetting::get('auto_cleanup_enabled', true);
            })
            ->onSuccess(function () {
                \Log::info('Automatic backup cleanup completed');
            });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
