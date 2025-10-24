<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
     * Create a full database backup
     *
     * @return array
     * @throws Exception
     */
    public function createBackup(): array
    {
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

            Log::info('Database backup created successfully', [
                'filename' => $filename,
                'size' => $filesize,
                'tables' => count($tables)
            ]);

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

            // Decompress if needed
            $isCompressed = substr($filename, -3) === '.gz';
            if ($isCompressed) {
                $sqlContent = $this->decompressBackup($filepath);
            } else {
                $sqlContent = file_get_contents($filepath);
            }

            // Disable foreign key checks first
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // Execute the entire SQL content
            try {
                // Use DB::unprepared to execute multiple statements
                DB::unprepared($sqlContent);
                
                // Re-enable foreign key checks
                DB::statement('SET FOREIGN_KEY_CHECKS=1');

                Log::info('Database restored successfully', [
                    'filename' => $filename
                ]);

                return [
                    'success' => true,
                    'filename' => $filename,
                    'statements' => 'all'
                ];

            } catch (Exception $e) {
                // Re-enable foreign key checks even on error
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
                throw $e;
            }

        } catch (Exception $e) {
            Log::error('Database restore failed', [
                'filename' => $filename,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
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
            $backups[] = [
                'filename' => $filename,
                'filepath' => $file,
                'size' => filesize($file),
                'size_formatted' => $this->formatBytes(filesize($file)),
                'created_at' => filemtime($file),
                'created_at_formatted' => Carbon::createFromTimestamp(filemtime($file))->format('Y-m-d H:i:s'),
                'age_days' => Carbon::createFromTimestamp(filemtime($file))->diffInDays(Carbon::now())
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
        
        if (file_exists($filepath)) {
            unlink($filepath);
            
            Log::info('Backup deleted', ['filename' => $filename]);
            
            return true;
        }

        return false;
    }

    /**
     * Clean up old backups based on retention policy
     *
     * @return array
     */
    public function cleanupOldBackups(): array
    {
        $backups = $this->listBackups();
        $retentionDays = config('backup.retention_days');
        $maxBackups = config('backup.max_backups');
        
        $deleted = [];
        $kept = [];

        foreach ($backups as $index => $backup) {
            $shouldDelete = false;

            // Check age-based retention
            if ($backup['age_days'] > $retentionDays) {
                $shouldDelete = true;
            }

            // Check max backups limit
            if ($maxBackups && $index >= $maxBackups) {
                $shouldDelete = true;
            }

            if ($shouldDelete) {
                if ($this->deleteBackup($backup['filename'])) {
                    $deleted[] = $backup['filename'];
                }
            } else {
                $kept[] = $backup['filename'];
            }
        }

        Log::info('Backup cleanup completed', [
            'deleted' => count($deleted),
            'kept' => count($kept)
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
        
        return [
            'total_backups' => count($backups),
            'total_size' => $totalSize,
            'total_size_formatted' => $this->formatBytes($totalSize),
            'oldest_backup' => !empty($backups) ? end($backups)['created_at_formatted'] : null,
            'newest_backup' => !empty($backups) ? $backups[0]['created_at_formatted'] : null,
            'retention_days' => config('backup.retention_days'),
            'schedule' => config('backup.schedule')
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
     * Create a backup with custom options
     *
     * @param array $options
     * @return array
     * @throws Exception
     */
    public function createBackupWithOptions(array $options): array
    {
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

            Log::info('Advanced backup created successfully', [
                'filename' => $filename,
                'type' => $type,
                'size' => $filesize,
                'tables' => count($tables),
                'modules' => $modules
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
}
