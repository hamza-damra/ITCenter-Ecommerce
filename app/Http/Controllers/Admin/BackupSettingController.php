<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BackupSetting;
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
        ]);

        try {
            BackupSetting::set('auto_cleanup_enabled', $request->auto_cleanup_enabled, 'boolean');
            BackupSetting::set('default_retention_days', $request->default_retention_days, 'integer');
            BackupSetting::set('max_backups', $request->max_backups ?? 10, 'integer');

            Log::info('Backup settings updated', [
                'admin' => auth()->user()->email ?? 'unknown',
                'settings' => $request->only(['auto_cleanup_enabled', 'default_retention_days', 'max_backups'])
            ]);

            return redirect()->route('admin.backup.settings')
                ->with('success', __('messages.Backup settings updated successfully'));

        } catch (Exception $e) {
            Log::error('Failed to update backup settings', ['error' => $e->getMessage()]);
            
            return redirect()->route('admin.backup.settings')
                ->with('error', __('messages.Failed to update backup settings'));
        }
    }
}
