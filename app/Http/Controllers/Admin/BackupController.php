<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DatabaseBackupService;
use App\Exceptions\BackupRestoreException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Exception;

class BackupController extends Controller
{
    /**
     * Database backup service
     */
    protected DatabaseBackupService $backupService;

    /**
     * Create a new controller instance.
     */
    public function __construct(DatabaseBackupService $backupService)
    {
        $this->backupService = $backupService;
    }

    /**
     * Display backup management page
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        try {
            $backups = $this->backupService->listBackups();
            $statistics = $this->backupService->getStatistics();

            return view('admin.backup.index', compact('backups', 'statistics'));

        } catch (Exception $e) {
            Log::error('Failed to load backup page', ['error' => $e->getMessage()]);
            
            return redirect()->route('admin.dashboard')
                ->with('error', __('messages.error_loading_backups', ['error' => $e->getMessage()]));
        }
    }

    /**
     * Create a new backup
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        // Ensure user is authenticated
        if (!auth()->check()) {
            abort(403, 'Unauthorized: Authentication required for backup operations');
        }

        try {
            $result = $this->backupService->createBackup();

            return redirect()->route('admin.backup.index')
                ->with('success', __('messages.backup_created', ['filename' => $result['filename'], 'size' => $this->formatBytes($result['size'])]));

        } catch (Exception $e) {
            Log::error('Backup creation failed', ['error' => $e->getMessage()]);
            
            return redirect()->route('admin.backup.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Restore from a backup
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(Request $request)
    {
        // Ensure user is authenticated
        if (!auth()->check()) {
            abort(403, 'Unauthorized: Authentication required for restore operations');
        }

        $request->validate([
            'filename' => 'required|string',
            'confirm' => 'required|accepted',
        ], [
            'confirm.accepted' => 'You must confirm the restoration to proceed.',
        ]);

        try {
            $result = $this->backupService->restoreBackup($request->filename);

            // CRITICAL FIX: Clear frontend caches after restore
            $this->clearFrontendCaches();

            // Validate that restored data is visible on frontend
            $validation = $this->validateFrontendDataVisibility();
            $warningMessage = '';
            if (!$validation['visible']) {
                $warningMessage = " Warning: {$validation['message']}";
                Log::warning('Frontend data visibility issue after restore', $validation);
            }

            Log::warning('Database restored from backup', [
                'filename' => $request->filename,
                'admin' => auth()->user()->email ?? 'unknown',
                'frontend_validation' => $validation
            ]);

            return redirect()->route('admin.backup.index')
                ->with('success', __('messages.backup_restored', ['filename' => $result['filename'], 'statements' => $result['statements']]))
                ->with('warning', $validation['visible'] ? null : $validation['message']);

        } catch (BackupRestoreException $e) {
            Log::error('Backup restoration failed', [
                'filename' => $request->filename,
                'error' => $e->getMessage(),
                'safety_backup' => $e->getSafetyBackup()
            ]);
            
            return redirect()->route('admin.backup.index')
                ->with('error', $e->getDetailedMessage());
        } catch (Exception $e) {
            Log::error('Backup restoration failed', [
                'filename' => $request->filename,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.backup.index')
                ->with('error', __('messages.error_restoring_backup', ['error' => $e->getMessage()]));
        }
    }

    /**
     * Delete a backup
     *
     * @param string $filename
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function delete(Request $request, $filename)
    {
        try {
            $deleted = $this->backupService->deleteBackup($filename);

            if ($deleted) {
                $message = __('messages.backup_deleted', ['filename' => $filename]);
                
                Log::info('Backup deleted', [
                    'filename' => $filename,
                    'admin' => auth()->user()->email ?? 'unknown'
                ]);
                
                // Return JSON for AJAX requests
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => $message
                    ]);
                }
                
                return redirect()->route('admin.backup.index')
                    ->with('success', $message);
            }

            $errorMessage = __('messages.backup_not_found', ['filename' => $filename]);
            
            // Return JSON for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 404);
            }
            
            return redirect()->route('admin.backup.index')
                ->with('error', $errorMessage);

        } catch (Exception $e) {
            Log::error('Backup deletion failed', [
                'filename' => $filename,
                'error' => $e->getMessage()
            ]);
            
            $errorMessage = __('messages.error_deleting_backup', ['error' => $e->getMessage()]);
            
            // Return JSON for AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.backup.index')
                ->with('error', $errorMessage);
        }
    }

    /**
     * Download a backup file
     *
     * @param string $filename
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function download($filename)
    {
        try {
            // Validate filename format to prevent path traversal attacks
            if (!preg_match('/^(backup|import)_[a-z0-9_-]+\.sql(\.gz)?$/i', $filename)) {
                Log::warning('Invalid backup filename format attempted', [
                    'filename' => $filename,
                    'ip' => request()->ip(),
                    'user' => auth()->user()->email ?? 'unknown'
                ]);
                
                return redirect()->route('admin.backup.index')
                    ->with('error', 'Invalid backup filename format.');
            }

            // Prevent directory traversal
            if (str_contains($filename, '..') || str_contains($filename, '/') || str_contains($filename, '\\')) {
                Log::warning('Path traversal attempt detected', [
                    'filename' => $filename,
                    'ip' => request()->ip(),
                    'user' => auth()->user()->email ?? 'unknown'
                ]);
                
                abort(403, 'Forbidden: Invalid filename');
            }

            $filepath = $this->backupService->getBackupPath($filename);

            if (!$filepath) {
                return redirect()->route('admin.backup.index')
                    ->with('error', "Backup file not found: {$filename}");
            }

            // Additional security check: ensure file is within backup directory
            $backupPath = realpath(config('backup.path', storage_path('app/backups')));
            $realFilePath = realpath($filepath);
            
            if (!$realFilePath || strpos($realFilePath, $backupPath) !== 0) {
                Log::warning('Attempted access to file outside backup directory', [
                    'filename' => $filename,
                    'ip' => request()->ip(),
                ]);
                abort(403, 'Forbidden');
            }

            Log::info('Backup file downloaded', [
                'filename' => $filename,
                'user' => auth()->user()->email ?? 'unknown'
            ]);

            return response()->download($filepath, $filename, [
                'Content-Type' => 'application/octet-stream',
            ]);

        } catch (Exception $e) {
            Log::error('Backup download failed', [
                'filename' => $filename,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.backup.index')
                ->with('error', __('messages.error_downloading_backup', ['error' => $e->getMessage()]));
        }
    }

    /**
     * Clean up old backups
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cleanup()
    {
        try {
            // Use force mode to ensure old backups are actually deleted
            $force = request()->has('force') ? request()->boolean('force') : true;
            $result = $this->backupService->cleanupOldBackups($force);

            $message = $force 
                ? __('messages.backup_cleanup_force', ['deleted' => $result['deleted_count'], 'kept' => $result['kept_count']])
                : __('messages.backup_cleanup_done', ['deleted' => $result['deleted_count'], 'kept' => $result['kept_count']]);

            return redirect()->route('admin.backup.index')
                ->with('success', $message);

        } catch (Exception $e) {
            Log::error('Backup cleanup failed', ['error' => $e->getMessage()]);
            
            return redirect()->route('admin.backup.index')
                ->with('error', __('messages.error_cleanup_backups', ['error' => $e->getMessage()]));
        }
    }

    /**
     * Clean up old backups (AJAX version)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function cleanupAjax()
    {
        try {
            // Use force mode to ensure old backups are actually deleted
            $force = request()->has('force') ? request()->boolean('force') : true;
            $result = $this->backupService->cleanupOldBackups($force);

            Log::info('Backup cleanup completed via AJAX', [
                'deleted' => $result['deleted_count'],
                'kept' => $result['kept_count'],
                'force_mode' => $force,
                'admin' => auth()->user()->email ?? 'unknown'
            ]);

            $message = isset($result['mode']) && $result['mode'] === 'force'
                ? __('messages.backup_cleanup_force', ['deleted' => $result['deleted_count'], 'kept' => $result['kept_count']])
                : __('messages.backup_cleanup_done', ['deleted' => $result['deleted_count'], 'kept' => $result['kept_count']]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => [
                    'deleted_count' => $result['deleted_count'],
                    'kept_count' => $result['kept_count'],
                    'deleted_files' => $result['deleted'],
                    'kept_files' => $result['kept'],
                    'mode' => $result['mode'] ?? 'normal'
                ]
            ]);

        } catch (Exception $e) {
            Log::error('Backup cleanup failed', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => __('messages.error_cleanup_backups', ['error' => $e->getMessage()]),
                'error' => $e->getMessage()
            ], 500);
        }
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
     * Create a backup with custom options (wizard)
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createWithOptions(Request $request)
    {
        $request->validate([
            'type' => 'required|in:database,modules',
            'modules' => 'required_if:type,modules|array',
            'modules.*' => 'string',
        ]);

        try {
            $options = [
                'type' => $request->type,
                'modules' => $request->modules ?? []
            ];

            $result = $this->backupService->createBackupWithOptions($options);

            $typeLabel = $this->getBackupTypeLabel($result['type']);
            
            return redirect()->route('admin.backup.index')
                ->with('success', __('messages.backup_created_typed', ['type' => $typeLabel, 'filename' => $result['filename'], 'size' => $this->formatBytes($result['size'])]));

        } catch (Exception $e) {
            Log::error('Advanced backup creation failed', [
                'type' => $request->type,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.backup.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Validate uploaded backup file
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateUpload(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|max:' . (config('backup.max_upload_size', 512) * 1024),
        ]);

        try {
            $file = $request->file('backup_file');
            $validation = $this->backupService->validateUploadedBackup($file);

            return response()->json([
                'success' => true,
                'data' => $validation
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Import and restore from uploaded backup
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function importAndRestore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|max:' . (config('backup.max_upload_size', 512) * 1024),
            'confirm' => 'required|accepted',
        ], [
            'confirm.accepted' => 'You must confirm the import and restoration to proceed.',
        ]);

        try {
            $file = $request->file('backup_file');
            $result = $this->backupService->importAndRestore($file);

            // CRITICAL FIX: Clear frontend caches after import
            $this->clearFrontendCaches();

            // Validate that imported data is visible on frontend
            $validation = $this->validateFrontendDataVisibility();
            $warningMessage = '';
            if (!$validation['visible']) {
                $warningMessage = " Warning: {$validation['message']}";
                Log::warning('Frontend data visibility issue after import', $validation);
            }

            Log::warning('Database imported and restored from upload', [
                'filename' => $result['filename'],
                'original' => $result['original_filename'],
                'admin' => auth()->user()->email ?? 'unknown',
                'frontend_validation' => $validation
            ]);

            return redirect()->route('admin.backup.index')
                ->with('success', __('messages.backup_imported', ['filename' => $result['original_filename']]))
                ->with('warning', $validation['visible'] ? null : $validation['message']);

        } catch (BackupRestoreException $e) {
            Log::error('Backup import/restore failed', [
                'error' => $e->getMessage(),
                'safety_backup' => $e->getSafetyBackup()
            ]);
            
            return redirect()->route('admin.backup.index')
                ->with('error', $e->getDetailedMessage());
        } catch (Exception $e) {
            Log::error('Backup import/restore failed', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.backup.index')
                ->with('error', __('messages.error_importing_backup', ['error' => $e->getMessage()]));
        }
    }

    /**
     * Get available modules for backup
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getModules()
    {
        try {
            $modules = $this->backupService->getAvailableModules();
            
            return response()->json([
                'success' => true,
                'modules' => $modules
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear frontend cache manually
     * Useful after import/restore if data doesn't appear
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function clearFrontendCache()
    {
        try {
            $this->clearFrontendCaches();
            
            // Also clear Laravel caches
            Cache::flush();
            Artisan::call('cache:clear');
            Artisan::call('view:clear');
            
            // Validate data visibility
            $validation = $this->validateFrontendDataVisibility();
            
            Log::info('Frontend cache cleared manually', [
                'admin' => auth()->user()->email ?? 'unknown',
                'validation' => $validation
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Frontend cache cleared successfully. ' . $validation['message'],
                'validation' => $validation
            ]);
        } catch (Exception $e) {
            Log::error('Failed to clear frontend cache', ['error' => $e->getMessage()]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get human-readable backup type label
     *
     * @param string $type
     * @return string
     */
    protected function getBackupTypeLabel(string $type): string
    {
        $labels = [
            'database' => 'Full Database',
            'modules' => 'Selected Modules'
        ];

        return $labels[$type] ?? $type;
    }

    /**
     * Purge all data from database except admin account
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function purgeAllData(Request $request)
    {
        // Ensure user is authenticated and is admin
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.Unauthorized access')
            ], 403);
        }

        $request->validate([
            'password' => 'required|string',
            'confirm_text' => 'required|string|in:DELETE ALL DATA',
        ]);

        // Verify admin password
        if (!Hash::check($request->password, auth()->user()->password)) {
            return response()->json([
                'success' => false,
                'message' => __('messages.Invalid password')
            ], 401);
        }

        try {
            // Get current admin user data to preserve
            $adminUser = auth()->user();
            $adminId = $adminUser->id;

            // Tables to preserve (system tables)
            $preserveTables = [
                'migrations',
                'personal_access_tokens',
                'password_reset_tokens',
                'password_reset_codes',
                'sessions',
                'cache',
                'cache_locks',
                'jobs',
                'job_batches',
                'failed_jobs',
                'backup_settings',
            ];

            // Get all tables
            $tables = DB::select('SHOW TABLES');
            $databaseName = DB::getDatabaseName();
            $tableKey = "Tables_in_{$databaseName}";

            $deletedTables = [];
            $skippedTables = [];

            // Disable foreign key checks (this doesn't require transaction)
            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            foreach ($tables as $table) {
                $tableName = $table->$tableKey;

                // Skip preserved tables
                if (in_array($tableName, $preserveTables)) {
                    $skippedTables[] = $tableName;
                    continue;
                }

                // Special handling for users table - keep only admin
                if ($tableName === 'users') {
                    DB::table('users')->where('id', '!=', $adminId)->delete();
                    $deletedTables[] = $tableName . ' (kept admin only)';
                    continue;
                }

                // Use DELETE instead of TRUNCATE to avoid transaction issues
                // Then reset auto-increment
                try {
                    DB::table($tableName)->delete();
                    DB::statement("ALTER TABLE `{$tableName}` AUTO_INCREMENT = 1");
                    $deletedTables[] = $tableName;
                } catch (Exception $tableError) {
                    // If delete fails, try truncate as fallback
                    try {
                        DB::statement("TRUNCATE TABLE `{$tableName}`");
                        $deletedTables[] = $tableName;
                    } catch (Exception $truncateError) {
                        Log::warning("Could not clear table {$tableName}", [
                            'delete_error' => $tableError->getMessage(),
                            'truncate_error' => $truncateError->getMessage()
                        ]);
                        $skippedTables[] = $tableName . ' (error)';
                    }
                }
            }

            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            // Clear all Laravel caches to ensure fresh data is loaded
            try {
                Cache::flush();
                Artisan::call('cache:clear');
                Artisan::call('view:clear');
                Artisan::call('route:clear');
                Artisan::call('config:clear');
                
                // CRITICAL FIX: Clear home page cache to prevent stale empty data
                $this->clearFrontendCaches();
            } catch (Exception $cacheError) {
                Log::warning('Cache clear failed during purge', ['error' => $cacheError->getMessage()]);
            }

            Log::warning('DATABASE PURGE: All data deleted except admin account', [
                'admin_id' => $adminUser->id,
                'admin_email' => $adminUser->email,
                'deleted_tables' => $deletedTables,
                'skipped_tables' => $skippedTables,
                'ip' => $request->ip(),
                'timestamp' => now()->toIso8601String()
            ]);

            return response()->json([
                'success' => true,
                'message' => __('messages.All data has been deleted successfully'),
                'data' => [
                    'deleted_tables' => count($deletedTables),
                    'skipped_tables' => count($skippedTables),
                ],
                'clear_browser_cache' => true
            ]);

        } catch (Exception $e) {
            // Re-enable foreign key checks in case of error
            try {
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            } catch (Exception $fkError) {
                // Ignore
            }
            
            Log::error('Database purge failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'admin' => auth()->user()->email ?? 'unknown'
            ]);

            return response()->json([
                'success' => false,
                'message' => __('messages.Failed to purge data') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear all frontend-related caches
     * This ensures frontend pages show fresh data after data operations
     *
     * @return void
     */
    protected function clearFrontendCaches(): void
    {
        try {
            // Clear home page cache for all locales
            $locales = ['ar', 'en', 'he'];
            foreach ($locales as $locale) {
                Cache::forget("home_page_data_{$locale}");
            }
            
            Log::info('Frontend caches cleared', ['caches' => array_map(fn($l) => "home_page_data_{$l}", $locales)]);
        } catch (Exception $e) {
            Log::warning('Failed to clear frontend caches', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Validate that imported/restored data is visible on frontend
     * Checks for active products and categories that should appear on home page
     *
     * @return array
     */
    protected function validateFrontendDataVisibility(): array
    {
        try {
            $issues = [];
            
            // Check for active products
            $activeProductsCount = \App\Models\Product::where('is_active', true)->count();
            if ($activeProductsCount === 0) {
                $issues[] = 'No active products found. Products need is_active=1 to appear on frontend.';
            }
            
            // Check for featured products (required for home page)
            $featuredProductsCount = \App\Models\Product::where('is_active', true)
                ->where('is_featured', true)
                ->count();
            if ($featuredProductsCount === 0) {
                $issues[] = 'No featured products found. At least some products should have is_featured=1.';
            }
            
            // Check for active categories
            $activeCategoriesCount = \App\Models\Category::where('is_active', true)->count();
            if ($activeCategoriesCount === 0) {
                $issues[] = 'No active categories found. Categories need is_active=1 to appear on frontend.';
            }
            
            // Check for carousel categories (required for home page)
            $carouselCategoriesCount = \App\Models\Category::where('is_active', true)
                ->whereNull('parent_id')
                ->where('display_mode', 'carousel')
                ->count();
            if ($carouselCategoriesCount === 0) {
                $issues[] = 'No carousel categories found. At least one parent category should have display_mode="carousel".';
            }
            
            $isVisible = empty($issues);
            $message = $isVisible 
                ? 'Frontend data is visible and ready.'
                : implode(' ', $issues);
            
            return [
                'visible' => $isVisible,
                'message' => $message,
                'details' => [
                    'active_products' => $activeProductsCount,
                    'featured_products' => $featuredProductsCount,
                    'active_categories' => $activeCategoriesCount,
                    'carousel_categories' => $carouselCategoriesCount,
                ],
                'issues' => $issues
            ];
        } catch (Exception $e) {
            Log::warning('Failed to validate frontend data visibility', ['error' => $e->getMessage()]);
            return [
                'visible' => true, // Assume visible if validation fails
                'message' => 'Could not validate frontend data visibility.',
                'error' => $e->getMessage()
            ];
        }
    }
}
