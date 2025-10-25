<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Backup;
use App\Models\BackupSetting;
use Illuminate\Support\Facades\Log;

class CleanupExpiredBackups extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:cleanup-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired backups based on expiration dates';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if auto cleanup is enabled
        $autoCleanupEnabled = BackupSetting::get('auto_cleanup_enabled', true);
        
        if (!$autoCleanupEnabled) {
            $this->info('Automatic cleanup is disabled in settings');
            return Command::SUCCESS;
        }

        $this->info('Starting expired backup cleanup...');

        // Get expired backups
        $expiredBackups = Backup::expired()->get();

        if ($expiredBackups->isEmpty()) {
            $this->info('No expired backups found');
            return Command::SUCCESS;
        }

        $deletedCount = 0;
        $failedCount = 0;
        $backupPath = config('backup.path', storage_path('app/backups'));

        foreach ($expiredBackups as $backup) {
            try {
                // Delete physical file
                $filepath = $backupPath . DIRECTORY_SEPARATOR . $backup->filename;
                
                if (file_exists($filepath)) {
                    unlink($filepath);
                }

                // Delete database record
                $backup->delete();

                $deletedCount++;
                $this->line("✓ Deleted: {$backup->filename}");

            } catch (\Exception $e) {
                $failedCount++;
                $this->error("✗ Failed to delete {$backup->filename}: {$e->getMessage()}");
                Log::error('Failed to delete expired backup', [
                    'filename' => $backup->filename,
                    'error' => $e->getMessage()
                ]);
            }
        }

        $this->newLine();
        $this->info("Cleanup completed!");
        $this->info("Deleted: {$deletedCount}");
        
        if ($failedCount > 0) {
            $this->warn("Failed: {$failedCount}");
        }

        Log::info('Automatic backup cleanup completed', [
            'deleted' => $deletedCount,
            'failed' => $failedCount
        ]);

        return Command::SUCCESS;
    }
}
