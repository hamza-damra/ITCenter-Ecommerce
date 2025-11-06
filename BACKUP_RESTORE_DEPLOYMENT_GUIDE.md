# Backup Restore Transaction Fix - Deployment Guide

## Pre-Deployment Checklist

Before deploying this fix to production:

- [ ] Review all changed files
- [ ] Run tests locally: `php artisan test --filter BackupRestoreTransactionTest`
- [ ] Create a database backup of production (just in case!)
- [ ] Verify Laravel version compatibility (Laravel 12+)
- [ ] Check that all syntax is valid: `composer validate`

## Deployment Steps

### 1. Backup Current State

```bash
# Create a backup of your current database
php artisan backup:create

# Backup your current codebase
git commit -am "Backup before transaction fix deployment"
git tag -a "pre-transaction-fix" -m "Before backup restore transaction fix"
```

### 2. Deploy Code Changes

```bash
# Pull the latest changes
git pull origin main

# Or if applying manually, copy these files:
# - app/Services/DatabaseBackupService.php
# - app/Exceptions/BackupRestoreException.php
# - app/Http/Controllers/Admin/BackupController.php
# - app/Exceptions/Handler.php
# - tests/Feature/BackupRestoreTransactionTest.php
```

### 3. Clear Caches

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# If using OPcache, restart PHP-FPM
sudo systemctl restart php8.2-fpm  # Adjust version as needed
```

### 4. Verify Deployment

```bash
# Check for syntax errors
php artisan about

# Run the tests
php artisan test --filter BackupRestoreTransactionTest

# Check routes are registered
php artisan route:list --path=backup
```

### 5. Test in Production

1. **Low-Risk Test**:
   - Navigate to `/admin/backup`
   - Create a new backup (this tests the createBackup flow)
   - Verify backup appears in the list

2. **Medium-Risk Test**:
   - Download a recent backup
   - Restore it via the web interface
   - Verify success message appears

3. **Monitor Logs**:
   ```bash
   tail -f storage/logs/laravel.log
   ```

## Rollback Plan

If issues occur, rollback using:

```bash
# Revert to previous version
git checkout pre-transaction-fix

# Clear caches again
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Restart services
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx  # or apache2
```

## Post-Deployment Monitoring

### What to Monitor

1. **Error Logs** (`storage/logs/laravel.log`):
   - Look for `BackupRestoreException` entries
   - Check for "active transaction" warnings
   - Monitor any restore failures

2. **Database Transactions**:
   - Watch for any hung transactions
   - Monitor transaction counts

3. **Backup Operations**:
   - Verify backups complete successfully
   - Check restore operations work as expected

### Expected Log Entries

**Normal Operation:**
```
[YYYY-MM-DD HH:MM:SS] local.INFO: Creating safety backup before restore {"target_backup":"backup_xxx.sql.gz"}
[YYYY-MM-DD HH:MM:SS] local.INFO: Safety backup created {"safety_backup":"backup_xxx.sql.gz"}
[YYYY-MM-DD HH:MM:SS] local.INFO: Database restored successfully {"filename":"backup_xxx.sql.gz","safety_backup":"backup_xxx.sql.gz"}
```

**If Active Transaction Found (Now Handled):**
```
[YYYY-MM-DD HH:MM:SS] local.WARNING: Found active transaction(s) before restore operation, rolling back {"transaction_level":1}
[YYYY-MM-DD HH:MM:SS] local.INFO: All active transactions have been rolled back
```

## Environment-Specific Notes

### Development
- Run full test suite: `php artisan test`
- Test with both small and large backup files
- Test with compressed (.gz) and uncompressed backups

### Staging
- Use production-like data volumes
- Test import/restore flows extensively
- Verify error messages are user-friendly

### Production
- Deploy during low-traffic period
- Keep monitoring dashboard open
- Have rollback plan ready
- Communicate with team about deployment window

## Common Issues & Solutions

### Issue: "Class BackupRestoreException not found"

**Solution:**
```bash
composer dump-autoload
php artisan config:clear
```

### Issue: Old backups don't restore

**Solution:**  
Old backups are fully compatible. If issues occur:
1. Check backup file integrity
2. Verify backup is not corrupted
3. Try restoring a different backup
4. Check logs for specific error

### Issue: Tests fail

**Solution:**
```bash
# Ensure test database is configured
php artisan config:clear --env=testing
php artisan test --filter BackupRestoreTransactionTest --verbose
```

## Performance Impact

**Expected Impact**: Minimal to none

- Transaction cleanup adds < 1ms overhead
- Memory usage unchanged
- Database load unchanged
- File I/O patterns unchanged

## Security Considerations

✅ **No new security concerns** - The fix:
- Maintains existing authentication/authorization
- Doesn't expose new endpoints
- Doesn't change data access patterns
- Improves error handling (doesn't leak sensitive data)

## Support & Troubleshooting

### Debug Mode

To enable detailed logging for backup operations:

```php
// In config/logging.php, ensure 'daily' channel is configured
// Check storage/logs/laravel.log for entries like:

// Transaction state before restore
// Safety backup creation status
// Transaction cleanup actions
// Restore operation success/failure
```

### Testing Recovery

To test the safety backup feature:

1. Create a test backup
2. Attempt to restore it (should create safety backup)
3. If restore fails, verify safety backup exists
4. Restore from safety backup to recover

## Documentation Updates

After successful deployment, update:

- [ ] Internal wiki/docs with new error handling
- [ ] Team members about the fix
- [ ] Runbook with new troubleshooting steps
- [ ] Incident response procedures

## Success Criteria

Deployment is successful when:

✅ All backups create successfully  
✅ All restores complete without transaction errors  
✅ Safety backups are created before restores  
✅ Error messages are clear and informative  
✅ No new errors in logs  
✅ Tests pass: `php artisan test --filter BackupRestoreTransactionTest`  

## Contact & Support

If you encounter issues during deployment:

1. Check this guide's troubleshooting section
2. Review `BACKUP_RESTORE_TRANSACTION_FIX.md` for technical details
3. Check `BACKUP_RESTORE_QUICK_GUIDE.md` for usage examples
4. Review Laravel logs: `storage/logs/laravel.log`

---

**Version**: 1.0  
**Last Updated**: November 5, 2025  
**Laravel Version**: 12+  
**PHP Version**: 8.2+
