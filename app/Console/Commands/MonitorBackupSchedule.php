<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Backup;
use Carbon\Carbon;

class MonitorBackupSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:monitor-schedule
                            {--duration=120 : How many seconds to monitor (default: 2 minutes)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor backup schedule execution in real-time';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $duration = (int) $this->option('duration');
        $startTime = now();
        $endTime = $startTime->copy()->addSeconds($duration);
        
        $this->info('╔══════════════════════════════════════════════════════════╗');
        $this->info('║        BACKUP SCHEDULE MONITORING - TEST MODE           ║');
        $this->info('╚══════════════════════════════════════════════════════════╝');
        $this->newLine();
        
        $this->info("📊 Monitoring Configuration:");
        $this->info("   • Schedule Type: " . config('backup.schedule'));
        $this->info("   • Start Time: " . $startTime->format('H:i:s'));
        $this->info("   • End Time: " . $endTime->format('H:i:s'));
        $this->info("   • Duration: {$duration} seconds");
        $this->info("   • Expected Backups: " . ($duration >= 30 ? floor($duration / 30) : 0));
        $this->newLine();

        // Record initial backup count
        $initialCount = Backup::count();
        $this->info("📦 Initial State:");
        $this->info("   • Existing Backups: {$initialCount}");
        
        if ($initialCount > 0) {
            $latest = Backup::latest()->first();
            $this->info("   • Latest Backup: {$latest->filename}");
            $this->info("   • Created At: {$latest->created_at->format('H:i:s')}");
        }
        
        $this->newLine();
        $this->info("⏳ Monitoring for {$duration} seconds... Press Ctrl+C to stop");
        $this->info("   (Scheduler should create backup every 30 seconds)");
        $this->newLine();

        $lastCount = $initialCount;
        $backupsCreated = 0;
        $checkInterval = 5; // Check every 5 seconds
        
        $this->output->write("🔄 Checking");
        
        while (now()->lt($endTime)) {
            sleep($checkInterval);
            
            $currentCount = Backup::count();
            
            if ($currentCount > $lastCount) {
                $newBackups = $currentCount - $lastCount;
                $backupsCreated += $newBackups;
                
                $this->newLine();
                $this->info("✅ NEW BACKUP DETECTED!");
                
                $latest = Backup::latest()->first();
                $this->table(
                    ['Property', 'Value'],
                    [
                        ['Time', now()->format('H:i:s')],
                        ['Filename', $latest->filename],
                        ['Size', $this->formatBytes($latest->size)],
                        ['Type', $latest->type],
                        ['Total Backups', $currentCount],
                    ]
                );
                
                $lastCount = $currentCount;
            } else {
                $this->output->write(".");
            }
            
            // Show progress
            if (now()->diffInSeconds($startTime) % 15 == 0) {
                $elapsed = now()->diffInSeconds($startTime);
                $remaining = $endTime->diffInSeconds(now());
                $this->output->write(" [{$elapsed}s elapsed, {$remaining}s remaining]");
            }
        }
        
        $this->newLine(2);
        $this->info('╔══════════════════════════════════════════════════════════╗');
        $this->info('║                   MONITORING COMPLETE                    ║');
        $this->info('╚══════════════════════════════════════════════════════════╝');
        $this->newLine();
        
        $finalCount = Backup::count();
        $actualCreated = $finalCount - $initialCount;
        $expectedCreated = floor($duration / 30);
        
        $this->info("📊 Results:");
        $this->info("   • Initial Backups: {$initialCount}");
        $this->info("   • Final Backups: {$finalCount}");
        $this->info("   • Backups Created: {$actualCreated}");
        $this->info("   • Expected: {$expectedCreated}");
        
        if ($actualCreated >= $expectedCreated) {
            $this->info("   • Status: ✅ PASSED - Schedule working correctly!");
        } elseif ($actualCreated > 0) {
            $this->warn("   • Status: ⚠️  PARTIAL - Some backups created but less than expected");
        } else {
            $this->error("   • Status: ❌ FAILED - No backups created");
        }
        
        $this->newLine();
        
        if ($finalCount > $initialCount) {
            $this->info("📁 Newly Created Backups:");
            $newBackups = Backup::where('created_at', '>=', $startTime)->get();
            
            foreach ($newBackups as $backup) {
                $this->line("   • {$backup->filename} ({$this->formatBytes($backup->size)}) at {$backup->created_at->format('H:i:s')}");
            }
        }
        
        $this->newLine();
        $this->info("💡 Tip: Check logs for more details:");
        $this->comment("   tail -f storage/logs/laravel.log | grep -i backup");
        
        return self::SUCCESS;
    }

    /**
     * Format bytes to human readable format
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
