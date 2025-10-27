<?php

namespace App\Console\Commands;

use App\Services\DatabaseBackupService;
use Illuminate\Console\Command;

class BackupCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:cleanup
                            {--force : Force cleanup - keeps only the most recent backup}
                            {--yes : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old backups based on retention policy';

    /**
     * Database backup service
     */
    protected DatabaseBackupService $backupService;

    /**
     * Create a new command instance.
     */
    public function __construct(DatabaseBackupService $backupService)
    {
        parent::__construct();
        $this->backupService = $backupService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $forceMode = $this->option('force');
        
        $this->info('Starting backup cleanup...');
        $this->newLine();

        if ($forceMode) {
            $this->warn('⚠️  FORCE MODE: Will delete ALL backups except the most recent one!');
        } else {
            $retentionDays = config('backup.retention_days');
            $this->info("Retention policy: Keep backups for {$retentionDays} days");
        }
        $this->newLine();

        if (!$this->option('yes')) {
            $confirmMessage = $forceMode 
                ? 'This will delete ALL old backups! Are you sure?' 
                : 'Do you want to proceed with cleanup?';
                
            if (!$this->confirm($confirmMessage, true)) {
                $this->info('Cleanup cancelled.');
                return self::SUCCESS;
            }
        }

        $result = $this->backupService->cleanupOldBackups($forceMode);

        $this->info('✓ Cleanup completed!');
        $this->newLine();

        $this->table(
            ['Status', 'Count'],
            [
                ['Deleted', $result['deleted_count']],
                ['Kept', $result['kept_count']],
            ]
        );

        if (!empty($result['deleted'])) {
            $this->info('Deleted backups:');
            foreach ($result['deleted'] as $filename) {
                $this->line("  - {$filename}");
            }
        }

        return self::SUCCESS;
    }
}
