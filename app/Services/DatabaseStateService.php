<?php

namespace App\Services;

use Exception;
use PDO;
use PDOException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class DatabaseStateService
{
    /**
     * State constants
     */
    const STATE_A = 'unreachable'; // MySQL host unreachable / credentials invalid
    const STATE_B = 'missing_db';  // MySQL reachable, but database schema missing (1049)
    const STATE_C = 'available';   // Database exists and is accessible

    /**
     * Cache the state to avoid repeated checks
     */
    protected static ?string $cachedState = null;

    /**
     * Cache timestamp
     */
    protected static ?int $cacheTimestamp = null;

    /**
     * Cache TTL in seconds (5 seconds)
     */
    protected const CACHE_TTL = 5;

    /**
     * Detect database state without triggering Laravel's DB connection
     *
     * @return string One of STATE_A, STATE_B, or STATE_C
     */
    public static function detectState(): string
    {
        // Return cached state if still valid
        if (self::$cachedState !== null && self::$cacheTimestamp !== null) {
            $age = time() - self::$cacheTimestamp;
            if ($age < self::CACHE_TTL) {
                return self::$cachedState;
            }
        }

        try {
            $state = self::performDetection();
            self::$cachedState = $state;
            self::$cacheTimestamp = time();
            return $state;
        } catch (Exception $e) {
            Log::error('Database state detection failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Default to unreachable on error
            return self::STATE_A;
        }
    }

    /**
     * Perform the actual detection
     *
     * @return string
     */
    protected static function performDetection(): string
    {
        $connection = Config::get('database.default', 'mysql');
        $config = Config::get("database.connections.{$connection}");

        if (!$config) {
            Log::warning('Database connection config not found', ['connection' => $connection]);
            return self::STATE_A;
        }

        $host = $config['host'] ?? '127.0.0.1';
        $port = $config['port'] ?? 3306;
        $database = $config['database'] ?? null;
        $username = $config['username'] ?? 'root';
        $password = $config['password'] ?? '';
        $charset = $config['charset'] ?? 'utf8mb4';

        if (empty($database)) {
            Log::warning('Database name not configured');
            return self::STATE_A;
        }

        // Try to connect without selecting a database first
        try {
            $dsn = "mysql:host={$host};port={$port};charset={$charset}";
            $pdo = new PDO($dsn, $username, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 5,
            ]);

            // Check if database exists
            $stmt = $pdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = " . $pdo->quote($database));
            $exists = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($exists) {
                // Database exists, try to use it
                try {
                    $pdo->exec("USE `{$database}`");
                    // Try a simple query to ensure it's accessible
                    $pdo->query("SELECT 1");
                    return self::STATE_C;
                } catch (PDOException $e) {
                    // Database exists but can't access it
                    Log::warning('Database exists but not accessible', [
                        'database' => $database,
                        'error' => $e->getMessage()
                    ]);
                    return self::STATE_A;
                }
            } else {
                // Database doesn't exist - this is STATE_B
                return self::STATE_B;
            }

        } catch (PDOException $e) {
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();

            // Check for specific error codes
            // 1049 = Unknown database (STATE_B)
            if (str_contains($errorMessage, '1049') || str_contains($errorMessage, 'Unknown database')) {
                return self::STATE_B;
            }

            // Connection errors (STATE_A)
            if (
                str_contains($errorMessage, '2002') || // Connection refused
                str_contains($errorMessage, '2003') || // Can't connect to MySQL server
                str_contains($errorMessage, '1045') || // Access denied
                str_contains($errorMessage, 'Connection refused') ||
                str_contains($errorMessage, "Can't connect to") ||
                str_contains($errorMessage, 'Access denied')
            ) {
                return self::STATE_A;
            }

            // Default to unreachable for unknown errors
            Log::warning('Unknown database error during detection', [
                'code' => $errorCode,
                'message' => $errorMessage
            ]);
            return self::STATE_A;
        }
    }

    /**
     * Check if bootstrap mode should be enabled
     *
     * @return bool
     */
    public static function shouldEnableBootstrapMode(): bool
    {
        return self::detectState() === self::STATE_B;
    }

    /**
     * Check if database is available
     *
     * @return bool
     */
    public static function isDatabaseAvailable(): bool
    {
        return self::detectState() === self::STATE_C;
    }

    /**
     * Clear the cached state (useful after DB operations)
     */
    public static function clearCache(): void
    {
        self::$cachedState = null;
        self::$cacheTimestamp = null;
    }

    /**
     * Get detailed state information
     *
     * @return array
     */
    public static function getStateInfo(): array
    {
        $state = self::detectState();
        $config = Config::get('database.connections.' . Config::get('database.default'));

        return [
            'state' => $state,
            'state_label' => self::getStateLabel($state),
            'database' => $config['database'] ?? null,
            'host' => $config['host'] ?? null,
            'port' => $config['port'] ?? null,
            'bootstrap_enabled' => $state === self::STATE_B,
            'database_available' => $state === self::STATE_C,
        ];
    }

    /**
     * Get human-readable state label
     *
     * @param string $state
     * @return string
     */
    protected static function getStateLabel(string $state): string
    {
        return match ($state) {
            self::STATE_A => 'MySQL Server Unreachable',
            self::STATE_B => 'Database Missing',
            self::STATE_C => 'Database Available',
            default => 'Unknown',
        };
    }
}

