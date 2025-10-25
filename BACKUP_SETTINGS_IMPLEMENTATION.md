# Backup Settings System - Implementation Summary

## ✅ Completed Features

### 1. Database Structure
- **`backup_settings` table**: Stores global configuration (auto-cleanup, retention days, max backups)
- **`backups` table**: Tracks individual backup files with expiration dates, metadata, and creation info
- Migrations have been run successfully

### 2. Models Created
- **`BackupSetting` model**: Provides convenient get/set methods with caching
- **`Backup` model**: Tracks backup lifecycle with expiration logic, scopes for expired/active backups

### 3. Admin Interface
- **Settings Page** (`/admin/backup/settings`): Configure global backup policies
  - Enable/disable automatic cleanup
  - Set default retention period (1 day - 1 year)
  - Configure maximum backups to keep
- **Settings Link**: Added to main backup page for easy access

### 4. Multilingual Support
- ✅ English translations added
- ✅ Arabic translations added
- ✅ Hebrew translations added
- All UI elements support RTL for Arabic and Hebrew

### 5. Automatic Cleanup System
- **Command**: `backup:cleanup-expired` runs daily at 4:00 AM
- **Scheduled** in `routes/console.php`
- Respects the `auto_cleanup_enabled` setting
- Logs all cleanup operations

### 6. Routes Added
```php
GET  /admin/backup/settings        → View settings page
POST /admin/backup/settings        → Update settings
```

## 📋 Remaining Tasks

### 1. Update DatabaseBackupService

The service needs to be modified to integrate with the `Backup` model. Add this to `app/Services/DatabaseBackupService.php`:

```php
use App\Models\Backup;
use App\Models\BackupSetting;

// In createBackup() method, after successful backup creation, add:
Backup::create([
    'filename' => $filename,
    'type' => 'database',
    'size' => $filesize,
    'expires_at' => request()->has('expiration') ? 
        $this->calculateExpirationDate(request('expiration')) : 
        $this->getDefaultExpirationDate(),
    'created_by' => auth()->user()->email ?? 'system',
    'metadata' => [
        'tables' => count($tables),
        'compressed' => config('backup.compress'),
    ],
]);

// Add these helper methods:
protected function calculateExpirationDate($expiration)
{
    if ($expiration === 'never') {
        return null;
    }
    
    $days = match($expiration) {
        '1_day' => 1,
        '1_week' => 7,
        '1_month' => 30,
        '3_months' => 90,
        '6_months' => 180,
        '1_year' => 365,
        default => BackupSetting::get('default_retention_days', 30),
    };
    
    return now()->addDays($days);
}

protected function getDefaultExpirationDate()
{
    $days = BackupSetting::get('default_retention_days', 30);
    return now()->addDays($days);
}
```

### 2. Update Backup Creation Modal

Add expiration dropdown to the backup creation modal in `resources/views/admin/backup/index.blade.php`.

Find the export modal form and add before the submit button:

```html
<!-- Expiration Selection -->
<div class="form-group" style="margin-top: 20px;">
    <label for="expiration">
        <i class="fas fa-clock"></i>
        {{ __('messages.Expiration') }}
        <span style="color: #ef4444;">*</span>
    </label>
    <select name="expiration" id="expiration" class="form-control" required>
        <option value="">{{ __('messages.Select Expiration') }}</option>
        <option value="1_day">{{ __('messages.Keep for 1 Day') }}</option>
        <option value="1_week">{{ __('messages.Keep for 1 Week') }}</option>
        <option value="1_month" selected>{{ __('messages.Keep for 1 Month') }}</option>
        <option value="3_months">{{ __('messages.Keep for 3 Months') }}</option>
        <option value="6_months">{{ __('messages.Keep for 6 Months') }}</option>
        <option value="1_year">{{ __('messages.Keep for 1 Year') }}</option>
        <option value="never">{{ __('messages.Never Delete') }}</option>
    </select>
    <small style="color: #64748b; display: block; margin-top: 8px;">
        {{ __('messages.Select how long this backup should be kept') }}
    </small>
</div>
```

### 3. Update Backup List View

Modify the backup listing table in `resources/views/admin/backup/index.blade.php` to show expiration status.

Add a new column header:
```html
<th>{{ __('messages.Expiration') }}</th>
```

Add the expiration status in the table row:
```html
<td>
    @php
        $backupRecord = \App\Models\Backup::where('filename', $backup['filename'])->first();
    @endphp
    
    @if($backupRecord)
        @if($backupRecord->expires_at === null)
            <span class="badge badge-success">
                <i class="fas fa-infinity"></i>
                {{ __('messages.Never expires') }}
            </span>
        @elseif($backupRecord->isExpired())
            <span class="badge badge-danger">
                <i class="fas fa-exclamation-circle"></i>
                {{ __('messages.Expired') }}
            </span>
        @else
            <span class="badge badge-warning">
                <i class="fas fa-clock"></i>
                {{ __('messages.Expires in') }} {{ $backupRecord->expires_at->diffForHumans() }}
            </span>
        @endif
    @else
        <span class="badge badge-secondary">
            <i class="fas fa-question"></i>
            {{ __('messages.Unknown') }}
        </span>
    @endif
</td>
```

### 4. Sync Existing Backups

Run this command once to register existing backup files in the database:

```php
php artisan tinker

// Then run:
$backupPath = storage_path('app/backups');
$files = glob($backupPath . '/*.{sql,gz}', GLOB_BRACE);

foreach ($files as $file) {
    $filename = basename($file);
    
    if (!\App\Models\Backup::where('filename', $filename)->exists()) {
        \App\Models\Backup::create([
            'filename' => $filename,
            'type' => 'database',
            'size' => filesize($file),
            'expires_at' => now()->addDays(30), // Default 30 days
            'created_by' => 'system',
            'metadata' => [],
        ]);
    }
}

echo "Synced " . count($files) . " backups\n";
```

### 5. Update BackupController Methods

Modify the `create()` and `createWithOptions()` methods in `app/Http/Controllers/Admin/BackupController.php` to pass expiration to the service:

```php
public function create(Request $request)
{
    $request->validate([
        'expiration' => 'required|string|in:1_day,1_week,1_month,3_months,6_months,1_year,never',
    ]);

    try {
        $result = $this->backupService->createBackup();
        // Service will automatically use request('expiration')
        
        return redirect()->route('admin.backup.index')
            ->with('success', __('messages.Backup created successfully'));
    } catch (Exception $e) {
        return redirect()->route('admin.backup.index')
            ->with('error', __('messages.Failed to create backup'));
    }
}
```

## 🎯 Testing Checklist

1. **Settings Page**
   - [ ] Visit `/admin/backup/settings`
   - [ ] Change retention period and save
   - [ ] Toggle auto-cleanup and verify it's saved

2. **Backup Creation**
   - [ ] Create a backup with "Keep for 1 Day" expiration
   - [ ] Verify expiration date appears in backup list
   - [ ] Create a backup with "Never Delete"
   - [ ] Verify it shows "Never expires"

3. **Automatic Cleanup**
   - [ ] Manually run: `php artisan backup:cleanup-expired`
   - [ ] Create a test backup with "1 day" expiration
   - [ ] Wait 1 day or manually set `expires_at` in database to past
   - [ ] Run cleanup command again
   - [ ] Verify expired backup was deleted

4. **Scheduler**
   - [ ] Run: `php artisan schedule:list`
   - [ ] Verify `backup:cleanup-expired` is scheduled for 04:00 daily

## 🌐 Multilingual Features

All text is translated in:
- English (en)
- Arabic (ar) with RTL support
- Hebrew (he) with RTL support

## 📊 Database Schema

### backup_settings
- `id`, `key`, `value`, `type`, `description`, timestamps

### backups
- `id`, `filename`, `type`, `size`, `expires_at`, `created_by`, `metadata`, timestamps

## 🔧 Configuration

Settings are managed in database but defaults in `config/backup.php`:
- `retention_days`: 30 days (overridden by BackupSetting)
- `max_backups`: 10 (overridden by BackupSetting)
- Auto-cleanup: Enabled by default

## 📝 Notes

- The system is backward compatible - existing backups won't be affected
- Manual backups can have custom expiration dates
- Automatic backups use the default retention policy
- Expired backups are only deleted if auto-cleanup is enabled
- The `Never Delete` option sets `expires_at` to NULL
