# Backup Restore Transaction Fix

## Problem
When importing/restoring a backup via the admin panel at `/admin/backup`, the following error occurred:

```
Failed to import/restore backup: Restore failed: There is no active transaction. 
Database was not modified. Safety backup available: backup_2025-11-05_20-45-33.sql.gz
```

## Root Cause
The error was caused by **nested transaction conflicts**:

1. `importAndRestore()` calls `restoreBackup()`
2. `restoreBackup()` creates a safety backup via `createBackup()`
3. `createBackup()` calls `checkMaxBackupLimit()` which starts a transaction
4. After the safety backup completes, that transaction is committed
5. `restoreBackup()` then tries to start its own transaction with `DB::beginTransaction()`
6. When the restore tries to commit/rollback, the transaction state is inconsistent, causing the error

## Solution Implemented

### 1. **Transaction Cleanup Helper**
Added `ensureNoActiveTransaction()` method that checks for and rolls back any active transactions before critical operations:

```php
protected function ensureNoActiveTransaction(): void
{
    $transactionLevel = DB::transactionLevel();
    
    if ($transactionLevel > 0) {
        Log::warning('Found active transaction(s) before restore operation, rolling back', [
            'transaction_level' => $transactionLevel
        ]);
        
        while (DB::transactionLevel() > 0) {
            DB::rollBack();
        }
        
        Log::info('All active transactions have been rolled back');
    }
}
```

### 2. **Improved Transaction Handling in Restore Methods**
Both `restoreBackup()` and `restoreBackupStreaming()` now:
- Call `ensureNoActiveTransaction()` before starting their transaction
- Check `DB::transactionLevel() > 0` before rolling back
- Wrap foreign key re-enablement in try-catch blocks

### 3. **Custom Exception for Better Error Handling**
Created `BackupRestoreException` class with:
- Safety backup filename tracking
- Detailed error messages
- Proper exception hierarchy

```php
class BackupRestoreException extends Exception
{
    protected ?string $safetyBackup = null;
    
    public function setSafetyBackup(?string $filename): self
    public function getSafetyBackup(): ?string
    public function getDetailedMessage(): string
}
```

### 4. **Controller Exception Handling**
Updated `BackupController` to catch and handle `BackupRestoreException` separately:

```php
catch (BackupRestoreException $e) {
    Log::error('Backup restoration failed', [
        'filename' => $request->filename,
        'error' => $e->getMessage(),
        'safety_backup' => $e->getSafetyBackup()
    ]);
    
    return redirect()->route('admin.backup.index')
        ->with('error', $e->getDetailedMessage());
}
```

### 5. **Global Exception Handler**
Added handling in `app/Exceptions/Handler.php` to properly render `BackupRestoreException`:
- JSON responses for API requests
- Redirects with detailed error messages for web requests

## Files Changed

1. **app/Services/DatabaseBackupService.php**
   - Added `ensureNoActiveTransaction()` method
   - Improved `restoreBackup()` transaction handling
   - Improved `restoreBackupStreaming()` transaction handling
   - Updated exception handling to use `BackupRestoreException`

2. **app/Exceptions/BackupRestoreException.php** (NEW)
   - Custom exception for backup restore failures
   - Tracks safety backup filename
   - Provides detailed error messages

3. **app/Http/Controllers/Admin/BackupController.php**
   - Added `BackupRestoreException` import
   - Updated `restore()` method exception handling
   - Updated `importAndRestore()` method exception handling

4. **app/Exceptions/Handler.php**
   - Added custom rendering for `BackupRestoreException`
   - Supports both web and API responses

5. **tests/Feature/BackupRestoreTransactionTest.php** (NEW)
   - Comprehensive test suite for transaction handling
   - Tests active transaction cleanup
   - Tests import and restore flow
   - Tests exception handling

## Testing

Run the test suite to verify the fix:

```bash
php artisan test --filter BackupRestoreTransactionTest
```

Or test manually:
1. Go to `/admin/backup`
2. Click "Import Backup"
3. Upload a valid backup file
4. Confirm the import/restore
5. Should complete successfully without transaction errors

## Benefits

- **Prevents transaction conflicts**: Ensures clean state before restore operations
- **Better error messages**: Users see detailed information including safety backup filename
- **Safer operations**: Always creates safety backup before restore
- **Proper cleanup**: Foreign key checks and transactions are properly managed even on failure
- **Logging**: Comprehensive logging of transaction states and errors
- **Testable**: Full test coverage for transaction handling edge cases

## Prevention

The fix prevents future issues by:
- Always checking for active transactions before critical operations
- Using `DB::transactionLevel()` checks before committing/rolling back
- Wrapping cleanup operations in try-catch blocks
- Providing clear error messages for debugging

## Backward Compatibility

This fix is fully backward compatible:
- Existing backup files work without modification
- API responses maintain the same structure
- All existing functionality is preserved
- Only internal transaction handling is improved
