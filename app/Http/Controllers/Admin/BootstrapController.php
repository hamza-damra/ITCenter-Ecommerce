<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DatabaseStateService;
use App\Services\BootstrapDatabaseService;
use App\Services\DatabaseBackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Exception;

class BootstrapController extends Controller
{
    protected BootstrapDatabaseService $dbService;
    protected DatabaseBackupService $backupService;

    public function __construct(
        BootstrapDatabaseService $dbService,
        DatabaseBackupService $backupService
    ) {
        $this->dbService = $dbService;
        $this->backupService = $backupService;
    }

    /**
     * Show bootstrap login page
     */
    public function showLogin()
    {
        // Only allow if database is missing
        if (!DatabaseStateService::shouldEnableBootstrapMode()) {
            return redirect()->route('admin.login')
                ->with('info', 'Bootstrap mode is only available when database is missing.');
        }

        // If already authenticated in bootstrap mode, redirect to setup
        if (Auth::guard('bootstrap')->check()) {
            return redirect()->route('admin.bootstrap.setup');
        }

        return view('admin.bootstrap.login');
    }

    /**
     * Handle bootstrap login
     */
    public function login(Request $request)
    {
        // Only allow if database is missing
        if (!DatabaseStateService::shouldEnableBootstrapMode()) {
            return redirect()->route('admin.login')
                ->with('error', 'Bootstrap mode is not available.');
        }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }

        $credentials = $request->only('email', 'password');

        // Use bootstrap guard
        if (Auth::guard('bootstrap')->attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();

            Log::info('Bootstrap login successful', [
                'email' => $credentials['email'],
                'ip' => $request->ip(),
            ]);

            return redirect()->intended(route('admin.bootstrap.setup'))
                ->with('success', 'Bootstrap login successful. Please restore your database.');
        }

        Log::warning('Bootstrap login failed', [
            'email' => $credentials['email'],
            'ip' => $request->ip(),
        ]);

        return redirect()->back()
            ->withErrors(['email' => 'These credentials do not match bootstrap admin credentials.'])
            ->withInput($request->only('email'));
    }

    /**
     * Handle bootstrap logout
     */
    public function logout(Request $request)
    {
        Auth::guard('bootstrap')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.bootstrap.login')
            ->with('info', 'You have been logged out.');
    }

    /**
     * Show database setup/import page
     */
    public function setup()
    {
        // Only allow if database is missing
        if (!DatabaseStateService::shouldEnableBootstrapMode()) {
            return redirect()->route('admin.dashboard')
                ->with('info', 'Database is available. Bootstrap mode is not needed.');
        }

        // Require bootstrap authentication
        if (!Auth::guard('bootstrap')->check()) {
            return redirect()->route('admin.bootstrap.login')
                ->with('error', 'Please login to access bootstrap setup.');
        }

        $stateInfo = DatabaseStateService::getStateInfo();
        
        // Get available backups (may fail in bootstrap mode, so catch exception)
        try {
            $availableBackups = $this->backupService->listBackups();
        } catch (Exception $e) {
            Log::warning('Could not list backups in bootstrap mode', ['error' => $e->getMessage()]);
            $availableBackups = [];
        }

        return view('admin.bootstrap.setup', compact('stateInfo', 'availableBackups'));
    }

    /**
     * Get current status (AJAX)
     */
    public function status()
    {
        $stateInfo = DatabaseStateService::getStateInfo();

        return response()->json([
            'success' => true,
            'data' => $stateInfo,
        ]);
    }

    /**
     * Create database
     */
    public function createDatabase(Request $request)
    {
        // Only allow if database is missing
        if (!DatabaseStateService::shouldEnableBootstrapMode()) {
            return response()->json([
                'success' => false,
                'message' => 'Bootstrap mode is not available.',
            ], 403);
        }

        // Require bootstrap authentication
        if (!Auth::guard('bootstrap')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required.',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'database_name' => 'nullable|string|max:64|regex:/^[a-zA-Z0-9_]+$/',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $databaseName = $request->input('database_name') ?? config('database.connections.mysql.database');

            $result = $this->dbService->createDatabase($databaseName);

            // Clear cache to force re-detection
            DatabaseStateService::clearCache();

            Log::info('Database created via bootstrap mode', [
                'database' => $databaseName,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => "Database '{$databaseName}' created successfully.",
                'data' => $result,
            ]);

        } catch (Exception $e) {
            Log::error('Database creation failed in bootstrap mode', [
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create database: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Import SQL file
     */
    public function importSql(Request $request)
    {
        // Only allow if database is missing or just created
        if (!DatabaseStateService::shouldEnableBootstrapMode() && !DatabaseStateService::isDatabaseAvailable()) {
            return response()->json([
                'success' => false,
                'message' => 'Bootstrap mode is not available.',
            ], 403);
        }

        // Require bootstrap authentication
        if (!Auth::guard('bootstrap')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required.',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'sql_file' => 'required|file|mimes:sql,txt|max:' . (config('backup.max_upload_size', 512) * 1024),
            'database_name' => 'nullable|string|max:64|regex:/^[a-zA-Z0-9_]+$/',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $file = $request->file('sql_file');
            $databaseName = $request->input('database_name') ?? config('database.connections.mysql.database');

            // Ensure database exists
            $this->dbService->ensureDatabaseExists($databaseName);

            // Import SQL
            $result = $this->dbService->importSqlFile($file, $databaseName);

            // Clear cache to force re-detection
            DatabaseStateService::clearCache();

            Log::info('SQL imported via bootstrap mode', [
                'database' => $databaseName,
                'filename' => $file->getClientOriginalName(),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'SQL file imported successfully.',
                'data' => $result,
            ]);

        } catch (Exception $e) {
            Log::error('SQL import failed in bootstrap mode', [
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to import SQL: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Restore from backup
     */
    public function restoreBackup(Request $request)
    {
        // Only allow if database is missing or just created
        if (!DatabaseStateService::shouldEnableBootstrapMode() && !DatabaseStateService::isDatabaseAvailable()) {
            return response()->json([
                'success' => false,
                'message' => 'Bootstrap mode is not available.',
            ], 403);
        }

        // Require bootstrap authentication
        if (!Auth::guard('bootstrap')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required.',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'backup_file' => 'required_without:backup_filename|file|mimes:sql,txt,zip,gz|max:' . (config('backup.max_upload_size', 512) * 1024),
            'backup_filename' => 'required_without:backup_file|string',
            'database_name' => 'nullable|string|max:64|regex:/^[a-zA-Z0-9_]+$/',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $databaseName = $request->input('database_name') ?? config('database.connections.mysql.database');

            // If uploading a file
            if ($request->hasFile('backup_file')) {
                $file = $request->file('backup_file');
                $result = $this->backupService->importAndRestore($file);
            } else {
                // Restore from existing backup file
                $filename = $request->input('backup_filename');
                $result = $this->backupService->restoreBackup($filename);
            }

            // Clear cache to force re-detection
            DatabaseStateService::clearCache();

            Log::info('Backup restored via bootstrap mode', [
                'database' => $databaseName,
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Backup restored successfully.',
                'data' => $result,
                'redirect' => route('admin.login'),
            ]);

        } catch (Exception $e) {
            Log::error('Backup restore failed in bootstrap mode', [
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to restore backup: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Validate database after import
     */
    public function validateDatabase(Request $request)
    {
        // Require bootstrap authentication
        if (!Auth::guard('bootstrap')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required.',
            ], 401);
        }

        try {
            $result = $this->dbService->validateDatabase();

            return response()->json([
                'success' => true,
                'message' => 'Database validation completed.',
                'data' => $result,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $e->getMessage(),
            ], 500);
        }
    }
}

