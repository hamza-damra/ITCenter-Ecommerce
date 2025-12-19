<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\BackupSetting;
use App\Models\Backup;
use App\Exceptions\BackupRestoreException;
use App\Services\DatabaseStateService;

class DatabaseBackupService
{
    /**
     * Backup directory path
     */
    protected string $backupPath;

    /**
     * Database connection name
     */
    protected ?string $connection = null;

    /**
     * Database configuration
     */
    protected ?array $dbConfig = null;

    public function __construct()
    {
        $this->backupPath = config('backup.path', storage_path('app/backups'));
        $this->connection = config('backup.connection') ?? config('database.default');
        $this->dbConfig = config("database.connections.{$this->connection}");

        // Ensure backup directory exists
        if (!file_exists($this->backupPath)) {
            mkdir($this->backupPath, 0755, true);
        }
    }

    /**
     * Check if database is available (for bootstrap mode compatibility)
     *
     * @return bool
     */
    protected function isDatabaseAvailable(): bool
    {
        try {
            return DatabaseStateService::isDatabaseAvailable();
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Create a full database backup
     *
     * @param bool $isSafetyBackup Whether this is an automatic safety backup (skips limit check)
     * @return array
     * @throws Exception
     */
    public function createBackup(bool $isSafetyBackup = false): array
    {
        // Check max backup limit BEFORE creating file (skip for safety backups)
        if (!$isSafetyBackup) {
            $this->checkMaxBackupLimit();
        }

        try {
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $filename = config('backup.prefix') . "_{$timestamp}.sql";
            $filepath = $this->backupPath . DIRECTORY_SEPARATOR . $filename;

            // Get all table names
            $tables = $this->getAllTables();
            
            if (empty($tables)) {
                throw new Exception('No tables found in database');
            }

            // Create backup file
            $handle = fopen($filepath, 'w+');
            if (!$handle) {
                throw new Exception('Could not create backup file');
            }

            // Write header
            fwrite($handle, "-- Database Backup\n");
            fwrite($handle, "-- Generated: " . Carbon::now()->toDateTimeString() . "\n");
            fwrite($handle, "-- Database: " . $this->dbConfig['database'] . "\n");
            fwrite($handle, "-- Tables: " . count($tables) . "\n\n");
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n\n");

            // Backup each table
            foreach ($tables as $table) {
                if ($this->isTableExcluded($table)) {
                    continue;
                }

                $this->backupTable($handle, $table);
            }

            fwrite($handle, "\nSET FOREIGN_KEY_CHECKS=1;\n");
            fclose($handle);

            // Compress if enabled
            if (config('backup.compress')) {
                $this->compressBackup($filepath);
                $filename .= '.gz';
                $filepath .= '.gz';
            }

            $filesize = filesize($filepath);

            // Create database record for backup (only if DB is available)
            if ($this->isDatabaseAvailable()) {
                try {
                    $backup = Backup::create([
                        'filename' => $filename,
                        'type' => 'database',
                        'size' => $filesize,
                        'expires_at' => now()->addDays(BackupSetting::get('default_retention_days', 30)),
                        'created_by' => auth()->check() ? auth()->user()->email : 'system',
                        'metadata' => [
                            'tables' => count($tables),
                            'compressed' => config('backup.compress'),
                        ],
                    ]);

                    Log::info('Database backup created successfully', [
                        'filename' => $filename,
                        'size' => $filesize,
                        'tables' => count($tables),
                        'backup_id' => $backup->id,
                    ]);
                } catch (Exception $e) {
                    // If DB record creation fails, log but don't fail the backup
                    Log::warning('Could not create backup database record', [
                        'error' => $e->getMessage(),
                        'filename' => $filename
                    ]);
                }
            } else {
                Log::info('Database backup created (bootstrap mode - no DB record)', [
                    'filename' => $filename,
                    'size' => $filesize,
                    'tables' => count($tables),
                ]);
            }

            return [
                'success' => true,
                'filename' => $filename,
                'filepath' => $filepath,
                'size' => $filesize,
                'tables' => count($tables),
                'timestamp' => $timestamp
            ];

        } catch (Exception $e) {
            Log::error('Database backup failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    /**
     * Backup a single table
     *
     * @param resource $handle
     * @param string $table
     * @return void
     */
    protected function backupTable($handle, string $table): void
    {
        // Get table structure
        $createTable = DB::select("SHOW CREATE TABLE `{$table}`");
        
        fwrite($handle, "-- Table structure for `{$table}`\n");
        fwrite($handle, "DROP TABLE IF EXISTS `{$table}`;\n");
        fwrite($handle, $createTable[0]->{'Create Table'} . ";\n\n");

        // Get table data
        $rows = DB::table($table)->get();
        
        if ($rows->count() > 0) {
            fwrite($handle, "-- Dumping data for table `{$table}`\n");
            
            // Insert in batches of 100 rows
            $chunks = $rows->chunk(100);
            foreach ($chunks as $chunk) {
                $values = [];
                foreach ($chunk as $row) {
                    $rowValues = array_map(function($value) {
                        if (is_null($value)) {
                            return 'NULL';
                        }
                        return "'" . addslashes($value) . "'";
                    }, (array) $row);
                    
                    $values[] = '(' . implode(',', $rowValues) . ')';
                }
                
                $columns = array_keys((array) $chunk->first());
                $columnList = '`' . implode('`,`', $columns) . '`';
                
                fwrite($handle, "INSERT INTO `{$table}` ({$columnList}) VALUES\n");
                fwrite($handle, implode(",\n", $values) . ";\n");
            }
            
            fwrite($handle, "\n");
        }
    }

    /**
     * Get all table names from database
     *
     * @return array
     */
    protected function getAllTables(): array
    {
        $tables = DB::select('SHOW TABLES');
        $dbName = $this->dbConfig['database'];
        $key = "Tables_in_{$dbName}";
        
        return array_map(function($table) use ($key) {
            return $table->$key;
        }, $tables);
    }

    /**
     * Check if table should be excluded
     *
     * @param string $table
     * @return bool
     */
    protected function isTableExcluded(string $table): bool
    {
        return in_array($table, config('backup.exclude_tables', []));
    }

    /**
     * Compress backup file using gzip
     *
     * @param string $filepath
     * @return void
     */
    protected function compressBackup(string $filepath): void
    {
        $gzipFile = $filepath . '.gz';
        
        $file = fopen($filepath, 'rb');
        $gz = gzopen($gzipFile, 'wb9');
        
        while (!feof($file)) {
            gzwrite($gz, fread($file, 1024 * 512));
        }
        
        fclose($file);
        gzclose($gz);
        
        // Remove uncompressed file
        unlink($filepath);
    }

    /**
     * Restore database from backup file
     *
     * @param string $filename
     * @return array
     * @throws Exception
     */
    public function restoreBackup(string $filename): array
    {
        try {
            $filepath = $this->backupPath . DIRECTORY_SEPARATOR . $filename;

            if (!file_exists($filepath)) {
                throw new Exception("Backup file not found: {$filename}");
            }

            // Check file size to determine processing method
            $fileSize = filesize($filepath);
            $maxMemorySafeSize = 50 * 1024 * 1024; // 50MB threshold

            // Decompress if needed
            $isCompressed = substr($filename, -3) === '.gz';
            
            // For large files, use streaming approach
            if ($fileSize > $maxMemorySafeSize) {
                Log::info('Using memory-safe streaming restore for large backup', [
                    'filename' => $filename,
                    'size' => $fileSize
                ]);
                return $this->restoreBackupStreaming($filepath, $filename, $isCompressed);
            }
            
            // For smaller files, use traditional method
            if ($isCompressed) {
                $sqlContent = $this->decompressBackup($filepath);
            } else {
                $sqlContent = file_get_contents($filepath);
            }

            // Validate backup file integrity
            $this->validateBackupFile($sqlContent, $filename);

            // Sanitize dump to avoid nested or conflicting transaction/locking statements
            $containsExternalTxn = $this->containsControlStatements($sqlContent);
            if ($containsExternalTxn) {
                Log::info('Sanitizing SQL dump to remove transaction/locking statements');
                $sqlContent = $this->sanitizeSqlForTransaction($sqlContent);
            }

            // Create automatic safety backup before restore (configurable)
            // Skip in bootstrap mode since database doesn't exist yet
            $safetyBackup = null;
            if (config('backup.safety_backup_on_restore', true) && $this->isDatabaseAvailable()) {
                Log::info('Creating safety backup before restore', ['target_backup' => $filename]);
                try {
                    $safetyResult = $this->createBackup(true); // Pass true to skip backup limit check
                    $safetyBackup = $safetyResult['filename'];
                    Log::info('Safety backup created', ['safety_backup' => $safetyBackup]);
                } catch (Exception $e) {
                    Log::warning('Could not create safety backup, proceeding anyway', ['error' => $e->getMessage()]);
                }
            } else {
                Log::info('Skipping safety backup (bootstrap mode or disabled)', ['target_backup' => $filename]);
            }

            // Ensure any previous transactions are resolved before starting restore
            $this->ensureNoActiveTransaction();

            // Do NOT wrap full dump restore in a Laravel transaction.
            // MySQL DDL (CREATE/ALTER) performs implicit commits, which would
            // end the transaction and cause "There is no active transaction"
            // when we try to commit. We rely on the dump's own safety.
            
            // Ensure database exists (for bootstrap mode)
            $databaseName = $this->dbConfig['database'] ?? null;
            if ($databaseName && !$this->isDatabaseAvailable()) {
                // In bootstrap mode, ensure database exists before restore
                $bootstrapDbService = app(\App\Services\BootstrapDatabaseService::class);
                $bootstrapDbService->ensureDatabaseExists($databaseName);
            }
            
            try {
                // Disable foreign key checks first
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
                
                // Execute the entire sanitized SQL content
                DB::unprepared($sqlContent);
                
                // Re-enable foreign key checks
                DB::statement('SET FOREIGN_KEY_CHECKS=1');

                Log::info('Database restored successfully', [
                    'filename' => $filename,
                    'safety_backup' => $safetyBackup,
                ]);

                // Optionally delete the safety backup after success
                if ($safetyBackup && !config('backup.keep_safety_backup_on_success', true)) {
                    try {
                        $this->deleteBackup($safetyBackup);
                        Log::info('Deleted safety backup after successful restore', ['safety_backup' => $safetyBackup]);
                        $safetyBackup = null;
                    } catch (Exception $delEx) {
                        Log::warning('Failed to delete safety backup after successful restore', ['error' => $delEx->getMessage()]);
                    }
                }

                return [
                    'success' => true,
                    'filename' => $filename,
                    'statements' => 'all',
                    'safety_backup' => $safetyBackup,
                ];

            } catch (Exception $e) {
                
                // Re-enable foreign key checks even on error
                try {
                    DB::statement('SET FOREIGN_KEY_CHECKS=1');
                } catch (Exception $fkException) {
                    Log::warning('Could not re-enable foreign key checks', ['error' => $fkException->getMessage()]);
                }
                
                Log::error('Restore failed, transaction rolled back', [
                    'filename' => $filename,
                    'error' => $e->getMessage(),
                    'safety_backup' => $safetyBackup,
                ]);
                
                // Delete safety backup if restore failed to avoid clutter
                if ($safetyBackup) {
                    try {
                        $this->deleteBackup($safetyBackup);
                        Log::info('Deleted safety backup after failed restore', ['safety_backup' => $safetyBackup]);
                    } catch (Exception $delEx) {
                        Log::warning('Failed to delete safety backup after failed restore', ['error' => $delEx->getMessage()]);
                    }
                }

                $exception = new BackupRestoreException("Restore failed: {$e->getMessage()}. Database was not modified.");
                $exception->setSafetyBackup($safetyBackup);
                throw $exception;
            }

        } catch (BackupRestoreException $e) {
            // Re-throw our custom exception
            throw $e;
        } catch (Exception $e) {
            Log::error('Database restore failed', [
                'filename' => $filename,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Restore backup using memory-safe streaming approach for large files
     *
     * @param string $filepath
     * @param string $filename
     * @param bool $isCompressed
     * @return array
     * @throws Exception
     */
    protected function restoreBackupStreaming(string $filepath, string $filename, bool $isCompressed): array
    {
        $safetyBackup = null;
        
        try {
            // Create safety backup if enabled (skip in bootstrap mode)
            if (config('backup.safety_backup_on_restore', true) && $this->isDatabaseAvailable()) {
                try {
                    $safetyResult = $this->createBackup(true); // Pass true to skip backup limit check
                    $safetyBackup = $safetyResult['filename'];
                    Log::info('Safety backup created for streaming restore', ['safety_backup' => $safetyBackup]);
                } catch (Exception $e) {
                    Log::warning('Could not create safety backup', ['error' => $e->getMessage()]);
                }
            } else {
                Log::info('Skipping safety backup for streaming restore (bootstrap mode or disabled)');
            }

            // Ensure any previous transactions are resolved before starting restore
            $this->ensureNoActiveTransaction();

            // Open file handle
            if ($isCompressed) {
                $handle = gzopen($filepath, 'rb');
            } else {
                $handle = fopen($filepath, 'rb');
            }

            if (!$handle) {
                throw new Exception("Could not open backup file for reading");
            }

            // Ensure database exists (for bootstrap mode)
            $databaseName = $this->dbConfig['database'] ?? null;
            if ($databaseName && !$this->isDatabaseAvailable()) {
                // In bootstrap mode, ensure database exists before restore
                $bootstrapDbService = app(\App\Services\BootstrapDatabaseService::class);
                $bootstrapDbService->ensureDatabaseExists($databaseName);
            }

            try {
                DB::statement('SET FOREIGN_KEY_CHECKS=0');

                $buffer = '';
                $statementCount = 0;
                
                // Read file line by line
                while (!feof($handle)) {
                    $line = $isCompressed ? gzgets($handle) : fgets($handle);
                    
                    if ($line === false) {
                        break;
                    }

                    // Skip comments and empty lines
                    $trimmedLine = trim($line);
                    if (empty($trimmedLine) || str_starts_with($trimmedLine, '--') || str_starts_with($trimmedLine, '/*')) {
                        continue;
                    }

                    // Skip transaction-control and locking statements from dumps
                    if ($this->isControlStatement($trimmedLine)) {
                        continue;
                    }

                    // Accumulate lines until we hit a semicolon
                    $buffer .= $line;

                    // Execute when we find a complete statement
                    if (str_ends_with($trimmedLine, ';')) {
                        try {
                            $bufferToExec = $this->sanitizeStatement($buffer);
                            if ($bufferToExec !== '') {
                                DB::unprepared($bufferToExec);
                                $statementCount++;
                            }
                            $buffer = '';
                        } catch (Exception $e) {
                            Log::error('Failed to execute SQL statement during streaming restore', [
                                'statement_number' => $statementCount,
                                'error' => $e->getMessage()
                            ]);
                            throw $e;
                        }
                    }
                }

                // Execute any remaining buffer
                if (!empty(trim($buffer))) {
                    $bufferToExec = $this->sanitizeStatement($buffer);
                    if ($bufferToExec !== '') {
                        DB::unprepared($bufferToExec);
                        $statementCount++;
                    }
                }

                DB::statement('SET FOREIGN_KEY_CHECKS=1');

                if ($isCompressed) {
                    gzclose($handle);
                } else {
                    fclose($handle);
                }

                Log::info('Large backup restored successfully using streaming', [
                    'filename' => $filename,
                    'statements' => $statementCount,
                    'safety_backup' => $safetyBackup,
                ]);

                // Optionally delete the safety backup after success
                if ($safetyBackup && !config('backup.keep_safety_backup_on_success', true)) {
                    try {
                        $this->deleteBackup($safetyBackup);
                        Log::info('Deleted safety backup after successful restore (streaming)', ['safety_backup' => $safetyBackup]);
                        $safetyBackup = null;
                    } catch (Exception $delEx) {
                        Log::warning('Failed to delete safety backup after successful restore (streaming)', ['error' => $delEx->getMessage()]);
                    }
                }

                return [
                    'success' => true,
                    'filename' => $filename,
                    'statements' => $statementCount,
                    'safety_backup' => $safetyBackup,
                    'method' => 'streaming',
                ];

            } catch (Exception $e) {
                
                // Re-enable foreign key checks
                try {
                    DB::statement('SET FOREIGN_KEY_CHECKS=1');
                } catch (Exception $fkException) {
                    Log::warning('Could not re-enable foreign key checks', ['error' => $fkException->getMessage()]);
                }
                
                if ($isCompressed) {
                    gzclose($handle);
                } else {
                    fclose($handle);
                }
                
                // Delete safety backup if restore failed to avoid clutter
                if ($safetyBackup) {
                    try {
                        $this->deleteBackup($safetyBackup);
                        Log::info('Deleted safety backup after failed restore', ['safety_backup' => $safetyBackup]);
                    } catch (Exception $delEx) {
                        Log::warning('Failed to delete safety backup after failed restore', ['error' => $delEx->getMessage()]);
                    }
                }

                $exception = new BackupRestoreException("Streaming restore failed: {$e->getMessage()}");
                $exception->setSafetyBackup($safetyBackup);
                throw $exception;
            }

        } catch (BackupRestoreException $e) {
            // Re-throw our custom exception
            throw $e;
        } catch (Exception $e) {
            Log::error('Streaming restore failed', [
                'filename' => $filename,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Validate backup file integrity before restore
     *
     * @param string $sqlContent
     * @param string $filename
     * @return void
     * @throws Exception
     */
    protected function validateBackupFile(string $sqlContent, string $filename): void
    {
        // Check file is not empty
        if (empty(trim($sqlContent))) {
            throw new Exception("Backup file is empty: {$filename}");
        }

        // Check for SQL keywords - must have CREATE TABLE statements
        if (!str_contains($sqlContent, 'CREATE TABLE')) {
            throw new Exception("Invalid backup: No CREATE TABLE statements found in {$filename}");
        }

        // Check file is not truncated - should end with semicolon or SQL comment
        $trimmed = trim($sqlContent);
        if (!str_ends_with($trimmed, ';') && !str_ends_with($trimmed, '*/')) {
            throw new Exception("Backup file appears truncated or corrupted: {$filename}");
        }

        // Verify it's our backup format
        if (!str_contains($sqlContent, '-- Database Backup') && !str_contains($sqlContent, '-- Advanced Database Backup')) {
            Log::warning("Backup file format not recognized, proceeding with caution", ['filename' => $filename]);
        }

        // Check for suspicious SQL injection patterns
        $dangerous = ['<?php', '<?', 'exec(', 'system(', 'shell_exec'];
        foreach ($dangerous as $pattern) {
            if (str_contains($sqlContent, $pattern)) {
                throw new Exception("Backup file contains suspicious content and has been rejected: {$filename}");
            }
        }

        Log::info('Backup file validation passed', ['filename' => $filename]);
    }

    /**
     * Decompress a gzipped backup file
     *
     * @param string $filepath
     * @return string
     */
    protected function decompressBackup(string $filepath): string
    {
        $gz = gzopen($filepath, 'rb');
        $content = '';
        
        while (!gzeof($gz)) {
            $content .= gzread($gz, 1024 * 512);
        }
        
        gzclose($gz);
        
        return $content;
    }

    /**
     * List all available backups
     *
     * @return array
     */
    public function listBackups(): array
    {
        $files = glob($this->backupPath . DIRECTORY_SEPARATOR . config('backup.prefix') . '_*.sql*');
        
        $backups = [];
        foreach ($files as $file) {
            $filename = basename($file);
            $createdAt = Carbon::createFromTimestamp(filemtime($file));
            $now = Carbon::now();
            
            // Calculate age in days (always positive)
            $ageDays = abs($createdAt->diffInDays($now, false));
            
            $backups[] = [
                'filename' => $filename,
                'filepath' => $file,
                'size' => filesize($file),
                'size_formatted' => $this->formatBytes(filesize($file)),
                'created_at' => filemtime($file),
                'created_at_formatted' => $createdAt->format('Y-m-d H:i:s'),
                'age_days' => (int) $ageDays,
                'age_human' => $this->formatTimeAgo($createdAt)
            ];
        }

        // Sort by creation time (newest first)
        usort($backups, function($a, $b) {
            return $b['created_at'] - $a['created_at'];
        });

        return $backups;
    }

    /**
     * Delete a backup file
     *
     * @param string $filename
     * @return bool
     */
    public function deleteBackup(string $filename): bool
    {
        $filepath = $this->backupPath . DIRECTORY_SEPARATOR . $filename;
        
        try {
            // Delete physical file
            if (file_exists($filepath)) {
                unlink($filepath);
            }
            
            // Delete database record (only if DB is available)
            if ($this->isDatabaseAvailable()) {
                try {
                    Backup::where('filename', $filename)->delete();
                } catch (Exception $e) {
                    Log::warning('Could not delete backup database record', [
                        'error' => $e->getMessage(),
                        'filename' => $filename
                    ]);
                }
            }
            
            Log::info('Backup deleted', [
                'filename' => $filename,
                'deleted_by' => auth()->check() ? auth()->user()->email : 'system'
            ]);
            
            return true;
            
        } catch (Exception $e) {
            Log::error('Failed to delete backup', [
                'filename' => $filename,
                'error' => $e->getMessage()
            ]);
            
            return false;
        }
    }

    /**
     * Clean up old backups based on retention policy
     * Now uses database records instead of scanning files
     *
     * @param bool $force If true, keeps only the most recent backup and deletes all others
     * @return array
     */
    public function cleanupOldBackups(bool $force = false): array
    {
        // In bootstrap mode, cleanup is not available (no DB access)
        if (!$this->isDatabaseAvailable()) {
            Log::info('Cleanup skipped - database not available (bootstrap mode)');
            return [
                'deleted' => [],
                'kept' => [],
                'deleted_count' => 0,
                'kept_count' => 0,
                'mode' => 'skipped_bootstrap'
            ];
        }

        $retentionDays = config('backup.retention_days');
        $maxBackups = BackupSetting::get('max_backups', null);
        
        $deleted = [];
        $kept = [];

        // Force mode: Keep only the most recent backup, delete all others
        if ($force) {
            Log::info('Force cleanup initiated - will keep only most recent backup');
            
            try {
                // Get all backups except the newest one
                $allBackups = Backup::orderBy('created_at', 'desc')->get();
            } catch (Exception $e) {
                Log::warning('Could not access backup records for cleanup', ['error' => $e->getMessage()]);
                return [
                    'deleted' => [],
                    'kept' => [],
                    'deleted_count' => 0,
                    'kept_count' => 0,
                    'mode' => 'error'
                ];
            }
            
            if ($allBackups->count() > 1) {
                // Keep the first (newest) one, delete all others
                $backupsToDelete = $allBackups->slice(1);
                
                foreach ($backupsToDelete as $backup) {
                    try {
                        // Delete physical file
                        $filepath = $this->backupPath . DIRECTORY_SEPARATOR . $backup->filename;
                        if (file_exists($filepath)) {
                            unlink($filepath);
                        }
                        
                        // Delete database record
                        $backup->delete();
                        $deleted[] = $backup->filename;
                        
                    } catch (Exception $e) {
                        Log::error('Failed to delete backup during force cleanup', [
                            'filename' => $backup->filename,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
                
                // Keep record of what we kept
                if ($allBackups->count() > 0) {
                    $kept[] = $allBackups->first()->filename;
                }
            }
            
            Log::info('Force cleanup completed', [
                'deleted' => count($deleted),
                'kept' => count($kept),
            ]);
            
            return [
                'deleted' => $deleted,
                'kept' => $kept,
                'deleted_count' => count($deleted),
                'kept_count' => count($kept),
                'mode' => 'force'
            ];
        }

        // Normal cleanup mode (not forced)
        
        // Method 1: Delete expired backups (based on expires_at)
        try {
            $expiredBackups = Backup::expired()->get();
        } catch (Exception $e) {
            Log::warning('Could not access expired backups', ['error' => $e->getMessage()]);
            $expiredBackups = collect([]);
        }
        
        foreach ($expiredBackups as $backup) {
            try {
                // Delete physical file
                $filepath = $this->backupPath . DIRECTORY_SEPARATOR . $backup->filename;
                if (file_exists($filepath)) {
                    unlink($filepath);
                }
                
                // Delete database record
                $backup->delete();
                $deleted[] = $backup->filename;
                
            } catch (Exception $e) {
                Log::error('Failed to delete expired backup', [
                    'filename' => $backup->filename,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Method 2: Delete backups older than retention days (based on created_at)
        if ($retentionDays && $retentionDays > 0) {
            $cutoffDate = Carbon::now()->subDays($retentionDays);
            
            try {
                $oldBackups = Backup::where('created_at', '<', $cutoffDate)->get();
            } catch (Exception $e) {
                Log::warning('Could not access old backups', ['error' => $e->getMessage()]);
                $oldBackups = collect([]);
            }
            
            foreach ($oldBackups as $backup) {
                // Skip if already deleted
                if (in_array($backup->filename, $deleted)) {
                    continue;
                }
                
                try {
                    // Delete physical file
                    $filepath = $this->backupPath . DIRECTORY_SEPARATOR . $backup->filename;
                    if (file_exists($filepath)) {
                        unlink($filepath);
                    }
                    
                    // Delete database record
                    $backup->delete();
                    $deleted[] = $backup->filename;
                    
                } catch (Exception $e) {
                    Log::error('Failed to delete old backup', [
                        'filename' => $backup->filename,
                        'age_days' => $backup->created_at->diffInDays(Carbon::now()),
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        // Method 3: Enforce max backups limit (delete oldest if exceeds)
        if ($maxBackups && $maxBackups > 0) {
            $totalBackups = Backup::count();
            
            if ($totalBackups > $maxBackups) {
                $excessCount = $totalBackups - $maxBackups;
                
                // Get oldest backups to delete
                $oldestBackups = Backup::orderBy('created_at', 'asc')
                    ->limit($excessCount)
                    ->get();
                
                foreach ($oldestBackups as $backup) {
                    // Skip if already deleted
                    if (in_array($backup->filename, $deleted)) {
                        continue;
                    }
                    
                    try {
                        // Delete physical file
                        $filepath = $this->backupPath . DIRECTORY_SEPARATOR . $backup->filename;
                        if (file_exists($filepath)) {
                            unlink($filepath);
                        }
                        
                        // Delete database record
                        $backup->delete();
                        $deleted[] = $backup->filename;
                        
                    } catch (Exception $e) {
                        Log::error('Failed to delete excess backup', [
                            'filename' => $backup->filename,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }
        }

        // Get list of kept backups
        $keptBackups = Backup::orderBy('created_at', 'desc')->get();
        foreach ($keptBackups as $backup) {
            $kept[] = $backup->filename;
        }

        Log::info('Backup cleanup completed', [
            'deleted' => count($deleted),
            'kept' => count($kept),
            'retention_days' => $retentionDays,
            'max_backups' => $maxBackups,
        ]);

        return [
            'deleted' => $deleted,
            'kept' => $kept,
            'deleted_count' => count($deleted),
            'kept_count' => count($kept)
        ];
    }

    /**
     * Get backup statistics
     *
     * @return array
     */
    public function getStatistics(): array
    {
        $backups = $this->listBackups();
        
        $totalSize = array_sum(array_column($backups, 'size'));
        
        // Get backup frequency in days based on schedule (use config if DB not available)
        try {
            $schedule = $this->isDatabaseAvailable() 
                ? BackupSetting::get('schedule', config('backup.schedule'))
                : config('backup.schedule', 'daily');
            $retentionDays = $this->isDatabaseAvailable()
                ? BackupSetting::get('default_retention_days', config('backup.retention_days'))
                : config('backup.retention_days', 30);
        } catch (Exception $e) {
            $schedule = config('backup.schedule', 'daily');
            $retentionDays = config('backup.retention_days', 30);
        }

        $backupFrequencyDays = match($schedule) {
            'daily' => 1,
            'weekly' => 7,
            'monthly' => 30,
            default => 1
        };
        
        return [
            'total_backups' => count($backups),
            'total_size' => $totalSize,
            'total_size_formatted' => $this->formatBytes($totalSize),
            'oldest_backup' => !empty($backups) ? end($backups)['created_at_formatted'] : null,
            'newest_backup' => !empty($backups) ? $backups[0]['created_at_formatted'] : null,
            'retention_days' => $retentionDays,
            'schedule' => $schedule,
            'backup_frequency_days' => $backupFrequencyDays
        ];
    }

    /**
     * Download a backup file
     *
     * @param string $filename
     * @return string|null
     */
    public function getBackupPath(string $filename): ?string
    {
        $filepath = $this->backupPath . DIRECTORY_SEPARATOR . $filename;
        
        if (file_exists($filepath)) {
            return $filepath;
        }

        return null;
    }

    /**
     * Format bytes to human readable format
     *
     * @param int $bytes
     * @return string
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Format time ago in human readable format
     *
     * @param Carbon $date
     * @return string
     */
    protected function formatTimeAgo(Carbon $date): string
    {
        $now = Carbon::now();
        
        // Get absolute differences
        $diffInSeconds = abs($now->diffInSeconds($date, false));
        $diffInMinutes = abs($now->diffInMinutes($date, false));
        $diffInHours = abs($now->diffInHours($date, false));
        $diffInDays = abs($now->diffInDays($date, false));
        
        // Get current locale
        $locale = app()->getLocale();
        
        if ($diffInSeconds < 60) {
            return $this->translateTimeUnit((int) $diffInSeconds, 'second', $locale);
        } elseif ($diffInMinutes < 60) {
            return $this->translateTimeUnit((int) $diffInMinutes, 'minute', $locale);
        } elseif ($diffInHours < 24) {
            return $this->translateTimeUnit((int) $diffInHours, 'hour', $locale);
        } elseif ($diffInDays < 30) {
            return $this->translateTimeUnit((int) $diffInDays, 'day', $locale);
        } elseif ($diffInDays < 365) {
            $months = (int) floor($diffInDays / 30);
            return $this->translateTimeUnit($months, 'month', $locale);
        } else {
            $years = (int) floor($diffInDays / 365);
            return $this->translateTimeUnit($years, 'year', $locale);
        }
    }

    /**
     * Translate time unit to current locale
     *
     * @param int $value
     * @param string $unit
     * @param string $locale
     * @return string
     */
    protected function translateTimeUnit(int $value, string $unit, string $locale): string
    {
        $translations = [
            'ar' => [
                'second' => ['ثانية واحدة', 'ثانيتان', '%d ثوانٍ', '%d ثانية'],
                'minute' => ['دقيقة واحدة', 'دقيقتان', '%d دقائق', '%d دقيقة'],
                'hour' => ['ساعة واحدة', 'ساعتان', '%d ساعات', '%d ساعة'],
                'day' => ['يوم واحد', 'يومان', '%d أيام', '%d يوم'],
                'month' => ['شهر واحد', 'شهران', '%d أشهر', '%d شهر'],
                'year' => ['سنة واحدة', 'سنتان', '%d سنوات', '%d سنة'],
                'ago' => 'منذ %s'
            ],
            'en' => [
                'second' => ['1 second', '2 seconds', '%d seconds', '%d seconds'],
                'minute' => ['1 minute', '2 minutes', '%d minutes', '%d minutes'],
                'hour' => ['1 hour', '2 hours', '%d hours', '%d hours'],
                'day' => ['1 day', '2 days', '%d days', '%d days'],
                'month' => ['1 month', '2 months', '%d months', '%d months'],
                'year' => ['1 year', '2 years', '%d years', '%d years'],
                'ago' => '%s ago'
            ],
            'he' => [
                'second' => ['שנייה אחת', 'שתי שניות', '%d שניות', '%d שניות'],
                'minute' => ['דקה אחת', 'שתי דקות', '%d דקות', '%d דקות'],
                'hour' => ['שעה אחת', 'שעתיים', '%d שעות', '%d שעות'],
                'day' => ['יום אחד', 'יומיים', '%d ימים', '%d ימים'],
                'month' => ['חודש אחד', 'חודשיים', '%d חודשים', '%d חודשים'],
                'year' => ['שנה אחת', 'שנתיים', '%d שנים', '%d שנים'],
                'ago' => 'לפני %s'
            ]
        ];
        
        // Fallback to English if locale not found
        if (!isset($translations[$locale])) {
            $locale = 'en';
        }
        
        // Get the correct plural form for Arabic
        if ($locale === 'ar') {
            if ($value == 1) {
                $format = $translations[$locale][$unit][0];
            } elseif ($value == 2) {
                $format = $translations[$locale][$unit][1];
            } elseif ($value >= 3 && $value <= 10) {
                $format = sprintf($translations[$locale][$unit][2], $value);
            } else {
                $format = sprintf($translations[$locale][$unit][3], $value);
            }
        } else {
            // English and Hebrew
            if ($value == 1) {
                $format = $translations[$locale][$unit][0];
            } elseif ($value == 2) {
                $format = $translations[$locale][$unit][1];
            } else {
                $format = sprintf($translations[$locale][$unit][2], $value);
            }
        }
        
        return sprintf($translations[$locale]['ago'], $format);
    }

    /**
     * Create a backup with custom options
     *
     * @param array $options
     * @return array
     * @throws Exception
     */
    public function createBackupWithOptions(array $options): array
    {
        // Check max backup limit BEFORE creating file
        $this->checkMaxBackupLimit();

        try {
            $type = $options['type'] ?? 'database'; // database or modules
            $modules = $options['modules'] ?? [];
            
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $typePrefix = $type === 'database' ? 'db' : 'modules';
            $filename = config('backup.prefix') . "_{$typePrefix}_{$timestamp}.sql";
            $filepath = $this->backupPath . DIRECTORY_SEPARATOR . $filename;

            // Get tables based on type
            if ($type === 'database') {
                $tables = $this->getAllTables();
            } else {
                $tables = $this->getModuleTables($modules);
            }

            if (empty($tables)) {
                throw new Exception('No tables selected for backup');
            }

            // Create backup file
            $handle = fopen($filepath, 'w+');
            if (!$handle) {
                throw new Exception('Could not create backup file');
            }

            // Write metadata header
            fwrite($handle, "-- Advanced Database Backup\n");
            fwrite($handle, "-- Type: {$type}\n");
            fwrite($handle, "-- Generated: " . Carbon::now()->toDateTimeString() . "\n");
            fwrite($handle, "-- Database: " . $this->dbConfig['database'] . "\n");
            fwrite($handle, "-- Tables: " . count($tables) . "\n");
            
            if ($type === 'modules') {
                fwrite($handle, "-- Modules: " . implode(', ', $modules) . "\n");
            }
            
            fwrite($handle, "\nSET FOREIGN_KEY_CHECKS=0;\n\n");

            // Backup tables
            foreach ($tables as $table) {
                if ($this->isTableExcluded($table)) {
                    continue;
                }
                $this->backupTable($handle, $table);
            }

            fwrite($handle, "\nSET FOREIGN_KEY_CHECKS=1;\n");
            fclose($handle);

            // Compress if enabled
            if (config('backup.compress')) {
                $this->compressBackup($filepath);
                $filename .= '.gz';
                $filepath .= '.gz';
            }

            $filesize = filesize($filepath);

            // Create database record for backup
            $backup = Backup::create([
                'filename' => $filename,
                'type' => $type,
                'size' => $filesize,
                'expires_at' => now()->addDays(BackupSetting::get('default_retention_days', 30)),
                'created_by' => auth()->check() ? auth()->user()->email : 'system',
                'metadata' => [
                    'tables' => count($tables),
                    'modules' => $modules,
                    'compressed' => config('backup.compress'),
                ],
            ]);

            Log::info('Advanced backup created successfully', [
                'filename' => $filename,
                'type' => $type,
                'size' => $filesize,
                'tables' => count($tables),
                'modules' => $modules,
                'backup_id' => $backup->id,
            ]);

            return [
                'success' => true,
                'filename' => $filename,
                'filepath' => $filepath,
                'size' => $filesize,
                'type' => $type,
                'tables' => count($tables),
                'modules' => $modules,
                'timestamp' => $timestamp
            ];

        } catch (Exception $e) {
            Log::error('Advanced backup failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Get tables for specific modules
     *
     * @param array $modules
     * @return array
     */
    protected function getModuleTables(array $modules): array
    {
        $tables = [];
        $moduleConfig = config('backup.modules', []);

        foreach ($modules as $module) {
            if (isset($moduleConfig[$module]['tables'])) {
                $tables = array_merge($tables, $moduleConfig[$module]['tables']);
            }
        }

        return array_unique($tables);
    }

    /**
     * Validate uploaded backup file
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return array
     * @throws Exception
     */
    public function validateUploadedBackup($file): array
    {
        // Check file extension
        $extension = strtolower($file->getClientOriginalExtension());
        $allowedExtensions = config('backup.allowed_extensions', ['sql', 'gz', 'zip']);
        
        if (!in_array($extension, $allowedExtensions)) {
            throw new Exception("Invalid file type. Allowed: " . implode(', ', $allowedExtensions));
        }

        // Check file size
        $maxSize = config('backup.max_upload_size', 512) * 1024 * 1024; // Convert MB to bytes
        if ($file->getSize() > $maxSize) {
            throw new Exception("File too large. Maximum size: " . config('backup.max_upload_size') . " MB");
        }

        // Try to read metadata
        $metadata = $this->extractBackupMetadata($file);

        return [
            'valid' => true,
            'filename' => $file->getClientOriginalName(),
            'size' => $file->getSize(),
            'extension' => $extension,
            'metadata' => $metadata
        ];
    }

    /**
     * Extract metadata from backup file
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return array
     */
    protected function extractBackupMetadata($file): array
    {
        $metadata = [
            'type' => 'unknown',
            'date' => null,
            'tables' => 0,
            'modules' => []
        ];

        try {
            $extension = strtolower($file->getClientOriginalExtension());
            
            if ($extension === 'gz') {
                $gz = gzopen($file->getRealPath(), 'rb');
                $header = gzread($gz, 2048);
                gzclose($gz);
            } else {
                $handle = fopen($file->getRealPath(), 'r');
                $header = fread($handle, 2048);
                fclose($handle);
            }

            // Parse metadata from header comments
            if (preg_match('/-- Type: (.+)/i', $header, $matches)) {
                $metadata['type'] = trim($matches[1]);
            }
            if (preg_match('/-- Generated: (.+)/i', $header, $matches)) {
                $metadata['date'] = trim($matches[1]);
            }
            if (preg_match('/-- Tables: (\d+)/i', $header, $matches)) {
                $metadata['tables'] = (int) $matches[1];
            }
            if (preg_match('/-- Modules: (.+)/i', $header, $matches)) {
                $metadata['modules'] = array_map('trim', explode(',', $matches[1]));
            }

        } catch (Exception $e) {
            Log::warning('Could not extract backup metadata', ['error' => $e->getMessage()]);
        }

        return $metadata;
    }

    /**
     * Import and restore from uploaded backup file
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return array
     * @throws Exception
     */
    public function importAndRestore($file): array
    {
        try {
            // Validate first
            $validation = $this->validateUploadedBackup($file);

            // Save to temporary location
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $filename = 'import_' . $timestamp . '.' . $file->getClientOriginalExtension();
            $filepath = $this->backupPath . DIRECTORY_SEPARATOR . $filename;
            
            $file->move($this->backupPath, $filename);

            // Restore from saved file
            $result = $this->restoreBackup($filename);
            
            // Create database record for imported backup (only if DB is available)
            if ($this->isDatabaseAvailable()) {
                try {
                    Backup::create([
                        'filename' => $filename,
                        'type' => $validation['metadata']['type'] ?? 'unknown',
                        'size' => $validation['size'],
                        'expires_at' => now()->addDays(BackupSetting::get('default_retention_days', 30)),
                        'created_by' => auth()->check() ? auth()->user()->email : 'import',
                        'metadata' => array_merge($validation['metadata'], [
                            'imported' => true,
                            'original_filename' => $file->getClientOriginalName(),
                        ]),
                    ]);
                } catch (Exception $e) {
                    // If DB record creation fails, log but don't fail the import
                    Log::warning('Could not create import database record', [
                        'error' => $e->getMessage(),
                        'filename' => $filename
                    ]);
                }
            }
            
            Log::info('Backup imported and restored successfully', [
                'original_filename' => $file->getClientOriginalName(),
                'saved_filename' => $filename,
                'metadata' => $validation['metadata']
            ]);

            return [
                'success' => true,
                'filename' => $filename,
                'original_filename' => $file->getClientOriginalName(),
                'metadata' => $validation['metadata'],
                'restored' => true
            ];

        } catch (Exception $e) {
            Log::error('Backup import/restore failed', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Get available modules for selective backup
     *
     * @return array
     */
    public function getAvailableModules(): array
    {
        return config('backup.modules', []);
    }

    /**
     * Ensure there are no active transactions before starting a critical operation
     * This prevents transaction nesting issues
     *
     * @return void
     */
    protected function ensureNoActiveTransaction(): void
    {
        $transactionLevel = DB::transactionLevel();
        
        if ($transactionLevel > 0) {
            Log::warning('Found active transaction(s) before restore operation, rolling back', [
                'transaction_level' => $transactionLevel
            ]);
            
            // Roll back all active transactions
            while (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            
            Log::info('All active transactions have been rolled back');
        }
    }

    /**
     * Check if creating a new backup would exceed max limit
     * Throws exception if limit reached
     * Uses database locking to prevent race conditions
     *
     * @return void
     * @throws Exception
     */
    protected function checkMaxBackupLimit(): void
    {
        // Skip limit check in bootstrap mode
        if (!$this->isDatabaseAvailable()) {
            return;
        }

        try {
            $maxBackups = BackupSetting::get('max_backups', null);
        } catch (Exception $e) {
            // If can't access settings, skip limit check
            return;
        }
        
        // If no limit is set, allow creation
        if ($maxBackups === null || $maxBackups <= 0) {
            return;
        }

        // Use database locking to prevent race condition
        DB::beginTransaction();
        
        try {
            // Lock the backup_settings table for this check
            $setting = DB::table('backup_settings')
                ->where('key', 'max_backups')
                ->lockForUpdate()
                ->first();
            
            // Count current backups
            $currentCount = Backup::count();
            
            // If current count is already at or above limit, prevent creation
            if ($currentCount >= $maxBackups) {
                throw new Exception(__('messages.Cannot create a new backup. You have reached the maximum allowed backups.') . " (Limit: {$maxBackups})");
            }
            
            DB::commit();
            
        } catch (Exception $e) {
            // Ensure transaction is rolled back on any exception
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }
            throw $e;
        }
    }

    /**
     * Detects whether the SQL dump contains transaction/locking control statements
     */
    protected function containsControlStatements(string $sql): bool
    {
        $pattern = '/\b(START\s+TRANSACTION|BEGIN\s+TRANSACTION|BEGIN\s+WORK|COMMIT|ROLLBACK|LOCK\s+TABLES|UNLOCK\s+TABLES|SET\s+AUTOCOMMIT\s*=\s*(0|1))\b/i';
        return (bool) preg_match($pattern, $sql);
    }

    /**
     * Removes transaction/locking control statements that conflict with Laravel-managed transactions
     */
    protected function sanitizeSqlForTransaction(string $sql): string
    {
        $replacements = [
            // Remove transaction and locking statements (case-insensitive)
            '/\bSTART\s+TRANSACTION\b\s*;?/i' => '',
            '/\bBEGIN\s+TRANSACTION\b\s*;?/i' => '',
            '/\bBEGIN\s+WORK\b\s*;?/i' => '',
            '/\bCOMMIT\b\s*;?/i' => '',
            '/\bROLLBACK\b\s*;?/i' => '',
            '/\bLOCK\s+TABLES\b[^;]*;?/i' => '',
            '/\bUNLOCK\s+TABLES\b\s*;?/i' => '',
            '/\bSET\s+AUTOCOMMIT\s*=\s*(0|1)\s*;?/i' => '',
        ];

        return preg_replace(array_keys($replacements), array_values($replacements), $sql);
    }

    /**
     * Returns true if the single trimmed line is a control statement that should be skipped
     */
    protected function isControlStatement(string $trimmedLine): bool
    {
        $upper = strtoupper($trimmedLine);
        return (
            str_starts_with($upper, 'START TRANSACTION') ||
            str_starts_with($upper, 'BEGIN TRANSACTION') ||
            str_starts_with($upper, 'BEGIN WORK') ||
            $upper === 'COMMIT;' || $upper === 'COMMIT' ||
            $upper === 'ROLLBACK;' || $upper === 'ROLLBACK' ||
            str_starts_with($upper, 'LOCK TABLES') ||
            str_starts_with($upper, 'UNLOCK TABLES') ||
            str_starts_with($upper, 'SET AUTOCOMMIT')
        );
    }

    /**
     * Sanitizes an individual SQL statement string
     */
    protected function sanitizeStatement(string $statement): string
    {
        $statement = $this->sanitizeSqlForTransaction($statement);
        // After sanitization, return empty string if only whitespace remains
        return trim($statement) === '' ? '' : $statement;
    }
}
