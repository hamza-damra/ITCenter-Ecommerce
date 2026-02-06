<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BackupSetting;
use App\Models\Backup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class BackupSettingController extends Controller
{
    /**
     * Display backup settings page
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        try {
            $settings = BackupSetting::getAll();

            return view('admin.backup.settings', compact('settings'));

        } catch (Exception $e) {
            Log::error('Failed to load backup settings page', ['error' => $e->getMessage()]);
            
            return redirect()->route('admin.backup.index')
                ->with('error', __('messages.Failed to load backup settings'));
        }
    }

    /**
     * Update backup settings
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'auto_cleanup_enabled' => 'required|boolean',
            'default_retention_days' => 'required|integer|min:0|max:365',
            'max_backups' => 'nullable|integer|min:1|max:100',
            'auto_backup_interval' => 'required|string|in:disabled,5_minutes,15_minutes,30_minutes,hourly,6_hours,12_hours,daily,weekly,monthly',
        ]);

        try {
            BackupSetting::set('auto_cleanup_enabled', $request->auto_cleanup_enabled, 'boolean');
            BackupSetting::set('default_retention_days', $request->default_retention_days, 'integer');
            BackupSetting::set('max_backups', $request->max_backups ?? 10, 'integer');
            BackupSetting::set('auto_backup_interval', $request->auto_backup_interval, 'string');

            Log::info('Backup settings updated', [
                'admin' => auth()->user()->email ?? 'unknown',
                'settings' => $request->only(['auto_cleanup_enabled', 'default_retention_days', 'max_backups', 'auto_backup_interval'])
            ]);

            return redirect()->route('admin.backup.settings')
                ->with('success', __('messages.Backup settings updated successfully'));

        } catch (Exception $e) {
            Log::error('Failed to update backup settings', ['error' => $e->getMessage()]);
            
            return redirect()->route('admin.backup.settings')
                ->with('error', __('messages.Failed to update backup settings'));
        }
    }

    /**
     * Delete all expired backups
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cleanupExpired()
    {
        try {
            $expiredBackups = Backup::expired()->get();

            if ($expiredBackups->isEmpty()) {
                return redirect()->route('admin.backup.settings')
                    ->with('info', __('messages.No expired backups found'));
            }

            $deletedCount = 0;
            $failedCount = 0;
            $backupPath = config('backup.path', storage_path('app/backups'));

            foreach ($expiredBackups as $backup) {
                try {
                    $filepath = $backupPath . DIRECTORY_SEPARATOR . $backup->filename;

                    if (file_exists($filepath)) {
                        unlink($filepath);
                    }

                    $backup->delete();
                    $deletedCount++;
                } catch (Exception $e) {
                    $failedCount++;
                    Log::error('Failed to delete expired backup', [
                        'filename' => $backup->filename,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            Log::info('Expired backups cleanup from settings page', [
                'admin' => auth()->user()->email ?? 'unknown',
                'deleted' => $deletedCount,
                'failed' => $failedCount
            ]);

            $message = __('messages.Expired backups cleaned up successfully', ['count' => $deletedCount]);
            if ($failedCount > 0) {
                $message .= ' (' . __('messages.Failed to delete count backups', ['count' => $failedCount]) . ')';
            }

            return redirect()->route('admin.backup.settings')
                ->with('success', $message);

        } catch (Exception $e) {
            Log::error('Failed to cleanup expired backups', ['error' => $e->getMessage()]);

            return redirect()->route('admin.backup.settings')
                ->with('error', __('messages.Failed to cleanup expired backups'));
        }
    }
}
