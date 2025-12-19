<?php

namespace App\Services;

use Exception;
use PDO;
use PDOException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class BootstrapDatabaseService
{
    /**
     * Get database connection config without database name
     */
    protected function getConnectionConfig(): array
    {
        $connection = Config::get('database.default', 'mysql');
        $config = Config::get("database.connections.{$connection}");

        return [
            'host' => $config['host'] ?? '127.0.0.1',
            'port' => $config['port'] ?? 3306,
            'username' => $config['username'] ?? 'root',
            'password' => $config['password'] ?? '',
            'charset' => $config['charset'] ?? 'utf8mb4',
            'collation' => $config['collation'] ?? 'utf8mb4_unicode_ci',
        ];
    }

    /**
     * Create a new database
     *
     * @param string $databaseName
     * @return array
     * @throws Exception
     */
    public function createDatabase(string $databaseName): array
    {
        $this->logAction('create_database', ['database' => $databaseName]);

        try {
            $config = $this->getConnectionConfig();
            
            // Connect without selecting a database
            $dsn = "mysql:host={$config['host']};port={$config['port']};charset={$config['charset']}";
            $pdo = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);

            // Check if database already exists
            $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = " . $pdo->quote($databaseName));
            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                throw new Exception("Database '{$databaseName}' already exists.");
            }

            // Create database
            $pdo->exec("CREATE DATABASE `{$databaseName}` CHARACTER SET {$config['charset']} COLLATE {$config['collation']}");

            $this->logAction('create_database_success', ['database' => $databaseName]);

            return [
                'database' => $databaseName,
                'created' => true,
            ];

        } catch (PDOException $e) {
            $this->logAction('create_database_error', [
                'database' => $databaseName,
                'error' => $e->getMessage(),
            ]);
            throw new Exception("Failed to create database: " . $e->getMessage());
        }
    }

    /**
     * Ensure database exists, create if it doesn't
     *
     * @param string $databaseName
     * @return array
     * @throws Exception
     */
    public function ensureDatabaseExists(string $databaseName): array
    {
        try {
            $config = $this->getConnectionConfig();
            
            // Connect without selecting a database
            $dsn = "mysql:host={$config['host']};port={$config['port']};charset={$config['charset']}";
            $pdo = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);

            // Check if database exists
            $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = " . $pdo->quote($databaseName));
            if ($stmt->fetch(PDO::FETCH_ASSOC)) {
                return [
                    'database' => $databaseName,
                    'created' => false,
                    'exists' => true,
                ];
            }

            // Create database
            $pdo->exec("CREATE DATABASE `{$databaseName}` CHARACTER SET {$config['charset']} COLLATE {$config['collation']}");

            $this->logAction('ensure_database_created', ['database' => $databaseName]);

            return [
                'database' => $databaseName,
                'created' => true,
                'exists' => true,
            ];

        } catch (PDOException $e) {
            $this->logAction('ensure_database_error', [
                'database' => $databaseName,
                'error' => $e->getMessage(),
            ]);
            throw new Exception("Failed to ensure database exists: " . $e->getMessage());
        }
    }

    /**
     * Import SQL file into database
     *
     * @param \Illuminate\Http\UploadedFile|string $file File path or uploaded file
     * @param string $databaseName
     * @return array
     * @throws Exception
     */
    public function importSqlFile($file, string $databaseName): array
    {
        $this->logAction('import_sql_start', ['database' => $databaseName]);

        try {
            // Get file path
            if (is_string($file)) {
                $filePath = $file;
            } else {
                // Save uploaded file temporarily
                $tempPath = storage_path('app/temp/bootstrap_import_' . time() . '.sql');
                $file->move(dirname($tempPath), basename($tempPath));
                $filePath = $tempPath;
            }

            if (!file_exists($filePath)) {
                throw new Exception("SQL file not found: {$filePath}");
            }

            // Ensure database exists
            $this->ensureDatabaseExists($databaseName);

            // Update Laravel config to use the database
            Config::set("database.connections.mysql.database", $databaseName);

            // Get file size to determine processing method
            $fileSize = filesize($filePath);
            $maxMemorySafeSize = 50 * 1024 * 1024; // 50MB

            $statements = 0;
            $errors = [];

            if ($fileSize > $maxMemorySafeSize) {
                // Use streaming for large files
                $result = $this->importSqlStreaming($filePath, $databaseName);
                $statements = $result['statements'];
                $errors = $result['errors'];
            } else {
                // Load entire file for smaller files
                $sqlContent = file_get_contents($filePath);
                
                // Handle compressed files
                if (substr($filePath, -3) === '.gz') {
                    $sqlContent = gzdecode($sqlContent);
                    if ($sqlContent === false) {
                        throw new Exception("Failed to decompress SQL file.");
                    }
                }

                $result = $this->executeSqlContent($sqlContent, $databaseName);
                $statements = $result['statements'];
                $errors = $result['errors'];
            }

            // Clean up temp file if it was uploaded
            if (!is_string($file) && isset($tempPath) && file_exists($tempPath)) {
                @unlink($tempPath);
            }

            $this->logAction('import_sql_complete', [
                'database' => $databaseName,
                'statements' => $statements,
                'errors' => count($errors),
            ]);

            return [
                'database' => $databaseName,
                'statements' => $statements,
                'errors' => $errors,
                'success' => empty($errors),
            ];

        } catch (Exception $e) {
            $this->logAction('import_sql_error', [
                'database' => $databaseName,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Execute SQL content
     *
     * @param string $sqlContent
     * @param string $databaseName
     * @return array
     */
    protected function executeSqlContent(string $sqlContent, string $databaseName): array
    {
        $config = $this->getConnectionConfig();
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$databaseName};charset={$config['charset']}";
        
        $pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_LOCAL_INFILE => true,
        ]);

        // Remove BOM if present
        $sqlContent = preg_replace('/^\xEF\xBB\xBF/', '', $sqlContent);

        // Split into individual statements
        $statements = $this->splitSqlStatements($sqlContent);
        
        $executed = 0;
        $errors = [];

        // Disable foreign key checks
        $pdo->exec('SET FOREIGN_KEY_CHECKS=0');
        $pdo->exec('SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO"');

        foreach ($statements as $statement) {
            $statement = trim($statement);
            
            if (empty($statement) || $this->isComment($statement)) {
                continue;
            }

            try {
                $pdo->exec($statement);
                $executed++;
            } catch (PDOException $e) {
                $errors[] = [
                    'statement' => substr($statement, 0, 100),
                    'error' => $e->getMessage(),
                ];
            }
        }

        // Re-enable foreign key checks
        $pdo->exec('SET FOREIGN_KEY_CHECKS=1');

        return [
            'statements' => $executed,
            'errors' => $errors,
        ];
    }

    /**
     * Import SQL using streaming (for large files)
     *
     * @param string $filePath
     * @param string $databaseName
     * @return array
     */
    protected function importSqlStreaming(string $filePath, string $databaseName): array
    {
        $config = $this->getConnectionConfig();
        $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$databaseName};charset={$config['charset']}";
        
        $pdo = new PDO($dsn, $config['username'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_LOCAL_INFILE => true,
        ]);

        $handle = fopen($filePath, 'r');
        if (!$handle) {
            throw new Exception("Could not open SQL file for reading.");
        }

        $currentStatement = '';
        $executed = 0;
        $errors = [];

        // Disable foreign key checks
        $pdo->exec('SET FOREIGN_KEY_CHECKS=0');
        $pdo->exec('SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO"');

        while (($line = fgets($handle)) !== false) {
            $line = trim($line);
            
            // Skip comments and empty lines
            if (empty($line) || $this->isComment($line)) {
                continue;
            }

            $currentStatement .= $line . "\n";

            // Execute when we find a semicolon
            if (substr($line, -1) === ';') {
                $statement = trim($currentStatement);
                $currentStatement = '';

                if (empty($statement)) {
                    continue;
                }

                try {
                    $pdo->exec($statement);
                    $executed++;
                } catch (PDOException $e) {
                    $errors[] = [
                        'statement' => substr($statement, 0, 100),
                        'error' => $e->getMessage(),
                    ];
                }
            }
        }

        // Execute any remaining statement
        if (!empty(trim($currentStatement))) {
            try {
                $pdo->exec(trim($currentStatement));
                $executed++;
            } catch (PDOException $e) {
                $errors[] = [
                    'statement' => substr($currentStatement, 0, 100),
                    'error' => $e->getMessage(),
                ];
            }
        }

        fclose($handle);

        // Re-enable foreign key checks
        $pdo->exec('SET FOREIGN_KEY_CHECKS=1');

        return [
            'statements' => $executed,
            'errors' => $errors,
        ];
    }

    /**
     * Split SQL content into individual statements
     *
     * @param string $sql
     * @return array
     */
    protected function splitSqlStatements(string $sql): array
    {
        // Remove comments
        $sql = preg_replace('/--.*$/m', '', $sql);
        $sql = preg_replace('/\/\*.*?\*\//s', '', $sql);

        // Split by semicolon, but preserve semicolons inside strings
        $statements = [];
        $current = '';
        $inString = false;
        $stringChar = '';

        for ($i = 0; $i < strlen($sql); $i++) {
            $char = $sql[$i];
            $nextChar = $i + 1 < strlen($sql) ? $sql[$i + 1] : '';

            if (!$inString && ($char === '"' || $char === "'" || $char === '`')) {
                $inString = true;
                $stringChar = $char;
            } elseif ($inString && $char === $stringChar && $nextChar !== $stringChar) {
                $inString = false;
            } elseif (!$inString && $char === ';') {
                $statements[] = trim($current);
                $current = '';
                continue;
            }

            $current .= $char;
        }

        if (!empty(trim($current))) {
            $statements[] = trim($current);
        }

        return array_filter($statements, function($stmt) {
            return !empty(trim($stmt));
        });
    }

    /**
     * Check if line is a comment
     *
     * @param string $line
     * @return bool
     */
    protected function isComment(string $line): bool
    {
        $line = trim($line);
        return str_starts_with($line, '--') || 
               str_starts_with($line, '/*') || 
               str_starts_with($line, '*');
    }

    /**
     * Validate database after import
     *
     * @return array
     */
    public function validateDatabase(): array
    {
        $this->logAction('validate_database_start');

        $results = [
            'tables' => [],
            'migrations' => false,
            'admin_user' => false,
            'critical_tables' => [],
        ];

        try {
            // Check if we can connect
            DB::connection()->getPdo();

            // Get all tables
            $tables = DB::select('SHOW TABLES');
            $databaseName = DB::getDatabaseName();
            $tableKey = "Tables_in_{$databaseName}";

            foreach ($tables as $table) {
                $tableName = $table->$tableKey;
                $results['tables'][] = $tableName;
            }

            // Check migrations table
            if (Schema::hasTable('migrations')) {
                $results['migrations'] = true;
                $results['migration_count'] = DB::table('migrations')->count();
            }

            // Check users table and admin user
            if (Schema::hasTable('users')) {
                $adminUser = DB::table('users')
                    ->where('role', 'admin')
                    ->orWhere('email', env('BOOTSTRAP_ADMIN_EMAIL'))
                    ->first();
                
                $results['admin_user'] = $adminUser !== null;
            }

            // Check critical tables
            $criticalTables = ['users', 'products', 'categories', 'orders'];
            foreach ($criticalTables as $table) {
                $results['critical_tables'][$table] = Schema::hasTable($table);
            }

            $this->logAction('validate_database_complete', $results);

        } catch (Exception $e) {
            $this->logAction('validate_database_error', ['error' => $e->getMessage()]);
            throw $e;
        }

        return $results;
    }

    /**
     * Log action to bootstrap log file
     *
     * @param string $action
     * @param array $data
     */
    protected function logAction(string $action, array $data = []): void
    {
        $logPath = storage_path('logs/bootstrap-db.log');
        $logDir = dirname($logPath);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $message = sprintf(
            "[%s] %s: %s\n",
            now()->toDateTimeString(),
            strtoupper($action),
            json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        );

        file_put_contents($logPath, $message, FILE_APPEND | LOCK_EX);
    }
}

