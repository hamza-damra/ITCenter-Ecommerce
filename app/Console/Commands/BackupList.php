<?php

namespace App\Console\Commands;

use App\Services\DatabaseBackupService;
use Illuminate\Console\Command;

class BackupList extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:list
                            {--stats : Show backup statistics}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all available database backups';

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
        $backups = $this->backupService->listBackups();

        if (empty($backups)) {
            $this->warn('No backup files found.');
            return self::SUCCESS;
        }

        $this->info('Available Backups:');
        $this->newLine();

        $tableData = array_map(function($backup) {
            return [
                $backup['filename'],
                $backup['size_formatted'],
                $backup['created_at_formatted'],
                $backup['age_days'] . ' days ago',
            ];
        }, $backups);

        $this->table(
            ['Filename', 'Size', 'Created At', 'Age'],
            $tableData
        );

        if ($this->option('stats')) {
            $this->newLine();
            $stats = $this->backupService->getStatistics();

            $this->info('Backup Statistics:');
            $this->table(
                ['Property', 'Value'],
                [
                    ['Total Backups', $stats['total_backups']],
                    ['Total Size', $stats['total_size_formatted']],
                    ['Oldest Backup', $stats['oldest_backup'] ?? 'N/A'],
                    ['Newest Backup', $stats['newest_backup'] ?? 'N/A'],
                    ['Retention Policy', $stats['retention_days'] . ' days'],
                    ['Schedule', ucfirst($stats['schedule'])],
                ]
            );
        }

        return self::SUCCESS;
    }
}
