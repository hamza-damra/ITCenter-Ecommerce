# Backup System - Quick Reference Guide

## 🚀 Quick Start

### Create a Backup
```bash
# Via command line
php artisan backup:create

# Via admin panel
Login → Admin → Backup Management → "Create Backup Now"
```

### Restore a Backup
```bash
# Via command line
php artisan backup:restore backup_db_2025-10-27_14-30-45.sql.gz

# Via admin panel
Login → Admin → Backup Management → Click "Restore" → Confirm
```

### List Backups
```bash
php artisan backup:list
```

### Cleanup Old Backups
```bash
# Manual cleanup
php artisan backup:cleanup

# Cleanup expired only
php artisan backup:cleanup-expired
```

---

## 🔧 Configuration

### Environment Variables (.env)
```env
# Schedule (daily, weekly, monthly)
BACKUP_SCHEDULE=daily
BACKUP_DAILY_TIME=02:00

# Retention
BACKUP_RETENTION_DAYS=30
BACKUP_MAX_BACKUPS=10

# Features
BACKUP_COMPRESS=true
BACKUP_MAX_UPLOAD_SIZE=512
```

### Database Settings
Access via Admin Panel → Backup Settings:
- **Auto Cleanup Enabled**: Automatically delete expired backups
- **Default Retention Days**: How long to keep backups (default: 30)
- **Max Backups**: Maximum number of backups to store (default: 10)

---

## 🛡️ Security Features

### ✅ What's Protected:
- Path traversal attacks blocked
- Malicious SQL injection detected
- Authentication required for all operations
- File integrity validation before restore
- Automatic safety backups before restore

### ✅ Audit Trail:
All operations logged to `storage/logs/laravel.log`:
- Who created/deleted/restored backups
- Success/failure status
- Security violation attempts

---

## 💾 Backup Types

### 1. Full Database Backup
Includes all tables (default option)

### 2. Selective Module Backup
Choose specific modules:
- Products & Inventory
- Categories & Brands
- Users & Authentication
- Orders & Transactions
- Shopping Cart
- Favorites
- Offers & Deals
- Contact Messages
- Product Attributes

---

## ⚡ Important Features

### 🔄 Automatic Safety Backup
Before every restore, system automatically creates a safety backup:
- Protects against restore failures
- Filename included in error messages
- Can be used to roll back manually

### 🧠 Smart Memory Management
- Files <50MB: Load into memory (fast)
- Files >50MB: Stream line-by-line (safe)
- No more memory exhaustion errors

### 🔒 Transaction Rollback
If restore fails:
- Database changes are rolled back
- Data remains intact
- Safety backup available

### 📊 Database Tracking
All backups tracked in database:
- Filename, size, type
- Creation date and expiration
- Creator information
- Metadata (tables, modules)

---

## 🔄 Automated Scheduling

### Enable Cron (Required for Automation)

**Linux/Mac:**
```bash
crontab -e
# Add this line:
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

**Windows Task Scheduler:**
- Program: `php`
- Arguments: `artisan schedule:run`
- Start in: `C:\path\to\project`
- Trigger: Every 1 minute

### What Runs Automatically:
- **Backups**: Based on schedule (daily/weekly/monthly)
- **Cleanup**: Daily at 3:00 AM (if enabled)

---

## 📋 Common Tasks

### Change Backup Schedule
```env
# Edit .env file
BACKUP_SCHEDULE=weekly  # or daily, monthly
BACKUP_WEEKLY_DAY=0     # 0=Sunday, 1=Monday, etc.
```

### Import External Backup
1. Admin Panel → Backup Management
2. Click "Import Backup"
3. Upload .sql or .sql.gz file
4. Review metadata
5. Confirm import and restore

### Download Backup
1. Admin Panel → Backup Management
2. Find backup in list
3. Click download icon
4. File downloads to your computer

### Delete Old Backups
**Automatic:** Set max_backups and retention_days in settings

**Manual:** 
- Admin Panel → Click trash icon next to backup
- Or: `php artisan backup:cleanup`

---

## ⚠️ Troubleshooting

### Backup Creation Fails
```bash
# Check disk space
df -h

# Check permissions
ls -la storage/app/backups/

# Check logs
tail -f storage/logs/laravel.log
```

### Restore Fails
- Check log file for specific error
- Verify backup file is not corrupted
- Safety backup is automatically created - use it if needed
- Database remains unchanged due to rollback

### Max Backups Reached
- Increase limit in Backup Settings
- Or manually delete old backups
- Or run cleanup command

### Memory Errors (Old Issue - Now Fixed!)
- System now automatically uses streaming for files >50MB
- No configuration needed
- Should not occur anymore

---

## 📊 Monitoring

### Check Backup Status
```bash
# List all backups
php artisan backup:list

# Check database records
php artisan tinker
>>> App\Models\Backup::all();
```

### View Logs
```bash
# Real-time log monitoring
tail -f storage/logs/laravel.log | grep -i backup

# Last 50 backup-related logs
grep -i backup storage/logs/laravel.log | tail -50
```

### Database Query
```sql
-- See all backups
SELECT filename, type, size, expires_at, created_at 
FROM backups 
ORDER BY created_at DESC;

-- See expired backups
SELECT * FROM backups 
WHERE expires_at < NOW() 
ORDER BY expires_at;

-- Check settings
SELECT * FROM backup_settings;
```

---

## 🔐 Best Practices

### DO:
- ✅ Test backups regularly by restoring to dev environment
- ✅ Keep backups in multiple locations (download important ones)
- ✅ Monitor disk space regularly
- ✅ Review logs periodically
- ✅ Set reasonable retention policies
- ✅ Enable automatic cleanup
- ✅ Verify cron is running for scheduled backups

### DON'T:
- ❌ Restore backups without testing first
- ❌ Ignore disk space warnings
- ❌ Disable authentication checks
- ❌ Store backups in publicly accessible directories
- ❌ Ignore failed backup notifications
- ❌ Set retention too low (<7 days)

---

## 🆘 Emergency Recovery

### If Database is Corrupted:
1. Check latest backup: `php artisan backup:list`
2. Restore: `php artisan backup:restore [filename]`
3. System creates safety backup automatically
4. If restore fails, database is not modified (rollback)

### If Backup System Fails:
1. Check `storage/logs/laravel.log`
2. Verify `storage/app/backups/` directory exists and is writable
3. Check database connectivity
4. Verify `backups` and `backup_settings` tables exist
5. Contact support with log files

### Manual Database Backup (Bypass System):
```bash
# MySQL
mysqldump -u username -p database_name > manual_backup.sql

# Compress
gzip manual_backup.sql
```

---

## 📚 Additional Resources

- **Full Issue Report**: `BACKUP_SYSTEM_ISSUES.md`
- **Fix Summary**: `BACKUP_FIXES_SUMMARY.md`
- **System Documentation**: `ADVANCED_BACKUP_SYSTEM.md`
- **Laravel Logs**: `storage/logs/laravel.log`

---

## ✅ System Health Checklist

Run these periodically:

```bash
# 1. Verify backups exist
php artisan backup:list

# 2. Check database records match files
php artisan tinker
>>> count(glob(storage_path('app/backups/*'))) === App\Models\Backup::count()

# 3. Test backup creation
php artisan backup:create

# 4. Verify scheduler is running
php artisan schedule:list

# 5. Check disk space
df -h storage/app/backups/

# 6. Review recent logs
grep -i "backup" storage/logs/laravel.log | tail -20
```

All checks should pass ✅

---

**Last Updated:** October 27, 2025  
**System Version:** Laravel 12 with Enhanced Backup System  
**Status:** Production Ready ✅
