<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * NOTE: Backup scheduling is handled in routes/console.php (Laravel 11+ style).
     * Do NOT add backup schedules here to avoid duplicate execution.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Clean expired cart items daily
        $schedule->command('cart:clean')
            ->daily()
            ->at('02:00')
            ->onSuccess(function () {
                \Log::info('Cart cleanup completed successfully');
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
