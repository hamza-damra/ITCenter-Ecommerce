<?php

namespace App\Console\Commands;

use App\Services\DatabaseBackupService;
use Illuminate\Console\Command;
use Exception;

class BackupCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:create
                            {--force : Force backup creation even if one was recently created}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a full database backup';

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
        $this->info('Starting database backup...');
        $this->newLine();

        try {
            $result = $this->backupService->createBackup();

            $this->info('✓ Backup created successfully!');
            $this->newLine();

            $this->table(
                ['Property', 'Value'],
                [
                    ['Filename', $result['filename']],
                    ['Size', $this->formatBytes($result['size'])],
                    ['Tables', $result['tables']],
                    ['Location', $result['filepath']],
                ]
            );

            return self::SUCCESS;

        } catch (Exception $e) {
            $this->error('✗ Backup failed!');
            $this->error($e->getMessage());
            $this->newLine();

            return self::FAILURE;
        }
    }

    /**
     * Format bytes to human readable format
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
}
