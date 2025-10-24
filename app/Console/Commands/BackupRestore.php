<?php

namespace App\Console\Commands;

use App\Services\DatabaseBackupService;
use Illuminate\Console\Command;
use Exception;

class BackupRestore extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:restore
                            {filename? : The backup filename to restore}
                            {--latest : Restore the most recent backup}
                            {--force : Skip confirmation prompt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restore database from a backup file';

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
        $filename = $this->argument('filename');
        $backups = $this->backupService->listBackups();

        if (empty($backups)) {
            $this->error('No backup files found!');
            return self::FAILURE;
        }

        // Get filename from options
        if ($this->option('latest')) {
            $filename = $backups[0]['filename'];
            $this->info("Selected latest backup: {$filename}");
        }

        // If no filename provided, show selection menu
        if (!$filename) {
            $choices = array_map(function($backup) {
                return sprintf(
                    '%s (%s) - %s',
                    $backup['filename'],
                    $backup['size_formatted'],
                    $backup['created_at_formatted']
                );
            }, $backups);

            $selected = $this->choice(
                'Select a backup to restore',
                $choices,
                0
            );

            // Extract filename from selection
            preg_match('/^([^\s]+)/', $selected, $matches);
            $filename = $matches[1];
        }

        // Confirm restoration
        if (!$this->option('force')) {
            $this->warn('⚠ WARNING: This will replace ALL current database data!');
            $this->newLine();
            
            if (!$this->confirm("Are you sure you want to restore from '{$filename}'?", false)) {
                $this->info('Restore cancelled.');
                return self::SUCCESS;
            }
        }

        $this->info('Starting database restore...');
        $this->newLine();

        try {
            $result = $this->backupService->restoreBackup($filename);

            $this->info('✓ Database restored successfully!');
            $this->newLine();

            $this->table(
                ['Property', 'Value'],
                [
                    ['Backup File', $result['filename']],
                    ['Statements Executed', $result['statements']],
                ]
            );

            return self::SUCCESS;

        } catch (Exception $e) {
            $this->error('✗ Restore failed!');
            $this->error($e->getMessage());
            $this->newLine();

            return self::FAILURE;
        }
    }
}
