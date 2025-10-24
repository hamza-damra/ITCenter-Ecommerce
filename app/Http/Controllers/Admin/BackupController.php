<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DatabaseBackupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
                ->with('error', 'Failed to load backup management page: ' . $e->getMessage());
        }
    }

    /**
     * Create a new backup
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        try {
            $result = $this->backupService->createBackup();

            return redirect()->route('admin.backup.index')
                ->with('success', "Backup created successfully! File: {$result['filename']} ({$this->formatBytes($result['size'])})");

        } catch (Exception $e) {
            Log::error('Backup creation failed', ['error' => $e->getMessage()]);
            
            return redirect()->route('admin.backup.index')
                ->with('error', 'Failed to create backup: ' . $e->getMessage());
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
        $request->validate([
            'filename' => 'required|string',
            'confirm' => 'required|accepted',
        ], [
            'confirm.accepted' => 'You must confirm the restoration to proceed.',
        ]);

        try {
            $result = $this->backupService->restoreBackup($request->filename);

            Log::warning('Database restored from backup', [
                'filename' => $request->filename,
                'admin' => auth()->user()->email ?? 'unknown'
            ]);

            return redirect()->route('admin.backup.index')
                ->with('success', "Database restored successfully from {$result['filename']}! {$result['statements']} statements executed.");

        } catch (Exception $e) {
            Log::error('Backup restoration failed', [
                'filename' => $request->filename,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.backup.index')
                ->with('error', 'Failed to restore backup: ' . $e->getMessage());
        }
    }

    /**
     * Delete a backup
     *
     * @param string $filename
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete($filename)
    {
        try {
            $deleted = $this->backupService->deleteBackup($filename);

            if ($deleted) {
                return redirect()->route('admin.backup.index')
                    ->with('success', "Backup '{$filename}' deleted successfully!");
            }

            return redirect()->route('admin.backup.index')
                ->with('error', "Backup file not found: {$filename}");

        } catch (Exception $e) {
            Log::error('Backup deletion failed', [
                'filename' => $filename,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.backup.index')
                ->with('error', 'Failed to delete backup: ' . $e->getMessage());
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
            $filepath = $this->backupService->getBackupPath($filename);

            if (!$filepath) {
                return redirect()->route('admin.backup.index')
                    ->with('error', "Backup file not found: {$filename}");
            }

            return response()->download($filepath, $filename, [
                'Content-Type' => 'application/octet-stream',
            ]);

        } catch (Exception $e) {
            Log::error('Backup download failed', [
                'filename' => $filename,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.backup.index')
                ->with('error', 'Failed to download backup: ' . $e->getMessage());
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
            $result = $this->backupService->cleanupOldBackups();

            return redirect()->route('admin.backup.index')
                ->with('success', "Cleanup completed! Deleted {$result['deleted_count']} old backups, kept {$result['kept_count']} backups.");

        } catch (Exception $e) {
            Log::error('Backup cleanup failed', ['error' => $e->getMessage()]);
            
            return redirect()->route('admin.backup.index')
                ->with('error', 'Failed to cleanup backups: ' . $e->getMessage());
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
                ->with('success', "Backup created successfully! Type: {$typeLabel}, File: {$result['filename']} ({$this->formatBytes($result['size'])})");

        } catch (Exception $e) {
            Log::error('Advanced backup creation failed', [
                'type' => $request->type,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.backup.index')
                ->with('error', 'Failed to create backup: ' . $e->getMessage());
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

            Log::warning('Database imported and restored from upload', [
                'filename' => $result['filename'],
                'original' => $result['original_filename'],
                'admin' => auth()->user()->email ?? 'unknown'
            ]);

            return redirect()->route('admin.backup.index')
                ->with('success', "Backup imported and restored successfully! Original file: {$result['original_filename']}");

        } catch (Exception $e) {
            Log::error('Backup import/restore failed', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.backup.index')
                ->with('error', 'Failed to import/restore backup: ' . $e->getMessage());
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
}
