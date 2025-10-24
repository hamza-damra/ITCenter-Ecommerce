# Database Backup System Documentation

## Overview

A comprehensive, production-ready database backup system for the ITCenter E-commerce Laravel application. This system provides automated backups, manual backup/restore capabilities, and a full admin interface for backup management.

## Features

✅ **Complete Database Backups**
- Backs up ALL tables in the database
- Includes all data (structure + rows)
- No data loss or selective table exclusion (unless configured)

✅ **Automated Scheduling**
- Configurable schedule (daily, weekly, monthly)
- Automatic cleanup of old backups
- Runs in the background without affecting site performance

✅ **Manual Control**
- Create backups on-demand via admin panel or CLI
- Restore from any backup snapshot
- Download backup files
- Delete individual backups

✅ **Security**
- Backups stored outside public directory
- Admin-only access
- Secure file handling

✅ **Production-Safe**
- Non-blocking operations
- Transaction-based restore
- Comprehensive error logging
- User-friendly error messages

## Installation & Setup

### 1. Configuration

The backup system is configured via `config/backup.php`. You can also use environment variables:

```env
# Backup Configuration
BACKUP_RETENTION_DAYS=30
BACKUP_SCHEDULE=daily
BACKUP_DAILY_TIME=02:00
BACKUP_WEEKLY_DAY=0
BACKUP_MONTHLY_DAY=1
BACKUP_COMPRESS=true
BACKUP_PREFIX=backup
```

### 2. Enable Scheduled Backups

To enable automatic backups, you need to set up Laravel's task scheduler. Add this to your server's cron:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

For Windows servers, use Task Scheduler to run:
```powershell
php artisan schedule:run
```

### 3. Ensure Storage Directory Exists

The backup system automatically creates the storage directory, but you can manually create it:

```bash
mkdir -p storage/app/backups
chmod 755 storage/app/backups
```

## Usage

### Admin Panel Interface

Access the backup management interface at:
```
https://yoursite.com/admin/backup
```

**Features:**
- View all available backups with size and date
- Create new backups instantly
- Restore from any backup
- Download backup files
- Delete old backups
- View backup statistics
- Run cleanup manually

### Command Line Interface

#### Create a Backup

```bash
php artisan backup:create
```

**Output:**
```
Starting database backup...

✓ Backup created successfully!

+----------+---------------------------+
| Property | Value                     |
+----------+---------------------------+
| Filename | backup_2025-10-24_14-30-15.sql.gz |
| Size     | 2.45 MB                   |
| Tables   | 15                        |
| Location | /path/to/storage/app/backups/... |
+----------+---------------------------+
```

#### List All Backups

```bash
php artisan backup:list
```

**With statistics:**
```bash
php artisan backup:list --stats
```

#### Restore from Backup

**Interactive mode (shows list to choose from):**
```bash
php artisan backup:restore
```

**Restore specific file:**
```bash
php artisan backup:restore backup_2025-10-24_14-30-15.sql.gz
```

**Restore latest backup:**
```bash
php artisan backup:restore --latest
```

**Force restore (skip confirmation):**
```bash
php artisan backup:restore --latest --force
```

#### Cleanup Old Backups

```bash
php artisan backup:cleanup
```

**Force cleanup (skip confirmation):**
```bash
php artisan backup:cleanup --force
```

## File Structure

```
ITCenter-Ecommerce/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       ├── BackupCreate.php      # Create backup command
│   │       ├── BackupRestore.php     # Restore backup command
│   │       ├── BackupCleanup.php     # Cleanup old backups command
│   │       └── BackupList.php        # List backups command
│   ├── Http/
│   │   └── Controllers/
│   │       └── Admin/
│   │           └── BackupController.php  # Admin panel controller
│   └── Services/
│       └── DatabaseBackupService.php  # Core backup logic
├── config/
│   └── backup.php                     # Backup configuration
├── resources/
│   └── views/
│       └── admin/
│           └── backup/
│               └── index.blade.php    # Admin backup interface
├── routes/
│   ├── console.php                    # Scheduled tasks
│   └── web.php                        # Backup routes
└── storage/
    └── app/
        └── backups/                   # Backup storage (auto-created)
```

## Configuration Options

### `config/backup.php`

| Option | Default | Description |
|--------|---------|-------------|
| `path` | `storage/app/backups` | Backup storage directory |
| `retention_days` | `30` | Days to keep backups |
| `schedule` | `daily` | Backup frequency (daily/weekly/monthly) |
| `daily_time` | `02:00` | Time for daily backups (24h format) |
| `weekly_day` | `0` | Day of week for weekly backups (0=Sunday) |
| `monthly_day` | `1` | Day of month for monthly backups |
| `prefix` | `backup` | Backup filename prefix |
| `compress` | `true` | Enable gzip compression |
| `max_backups` | `null` | Maximum number of backups to keep |
| `exclude_tables` | `[]` | Tables to exclude from backup |

## Backup File Format

Backups are stored as SQL dump files with the following naming convention:

```
{prefix}_{timestamp}.sql[.gz]
```

Examples:
- `backup_2025-10-24_14-30-15.sql.gz` (compressed)
- `backup_2025-10-24_14-30-15.sql` (uncompressed)

## How It Works

### Backup Process

1. **Table Discovery**: Retrieves all table names from the database
2. **Structure Dump**: Exports `CREATE TABLE` statements for each table
3. **Data Export**: Exports all rows in batches (100 rows per INSERT)
4. **Compression**: Optionally compresses the SQL file using gzip
5. **Storage**: Saves to `storage/app/backups/`

### Restore Process

1. **Validation**: Checks if backup file exists
2. **Decompression**: Decompresses if it's a `.gz` file
3. **Transaction**: Wraps restore in database transaction
4. **Execution**: Runs SQL statements sequentially
5. **Commit/Rollback**: Commits on success, rolls back on error

### Cleanup Process

1. **Age Check**: Identifies backups older than retention period
2. **Count Check**: Identifies excess backups beyond max limit
3. **Deletion**: Removes old/excess backup files
4. **Logging**: Records all cleanup actions

## Scheduled Tasks

The system automatically schedules:

1. **Backup Creation**
   - Schedule: Based on `backup.schedule` config
   - Time: Based on `backup.daily_time` config

2. **Automatic Cleanup**
   - Schedule: Daily at 03:00
   - Removes backups older than retention period

## Security Considerations

✅ **Secure Storage**
- Backups stored in `storage/app/backups/` (outside public directory)
- Not accessible via web browser

✅ **Access Control**
- Admin panel requires admin authentication
- All routes protected by admin middleware

✅ **Safe Operations**
- Restore uses database transactions
- Comprehensive error handling
- All actions logged

## Performance

### Non-Blocking Operations

- Backups run in background (via CLI or scheduled tasks)
- No impact on customer-facing pages
- Batched INSERT statements for efficiency

### Production Safety

- Uses Laravel's query builder (sanitized)
- Transaction-based restore (atomic)
- Proper error handling and rollback

## Monitoring & Logging

All backup operations are logged to `storage/logs/laravel.log`:

```php
[2025-10-24 14:30:15] local.INFO: Database backup created successfully {"filename":"backup_2025-10-24_14-30-15.sql.gz","size":2564123,"tables":15}
[2025-10-24 14:35:20] local.INFO: Database restored successfully {"filename":"backup_2025-10-24_14-30-15.sql.gz","statements":450}
[2025-10-24 03:00:00] local.INFO: Backup cleanup completed {"deleted":5,"kept":25}
```

## Troubleshooting

### Backup Creation Fails

**Issue:** "Could not create backup file"
- **Solution:** Check write permissions on `storage/app/backups/`
  ```bash
  chmod 755 storage/app/backups/
  ```

**Issue:** "No tables found in database"
- **Solution:** Verify database connection in `.env`

### Restore Fails

**Issue:** "Backup file not found"
- **Solution:** Verify file exists in `storage/app/backups/`

**Issue:** SQL syntax errors during restore
- **Solution:** Ensure backup was created from the same database version

### Scheduled Backups Not Running

**Issue:** Backups not being created automatically
- **Solution:** Ensure cron job is set up correctly
  ```bash
  crontab -e
  # Add: * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
  ```

### Permission Errors

**Issue:** "Permission denied" when creating backups
- **Solution:** Set proper ownership and permissions
  ```bash
  chown -R www-data:www-data storage/
  chmod -R 755 storage/
  ```

## Best Practices

1. **Regular Testing**
   - Test restore process periodically
   - Verify backup integrity

2. **Off-Site Backups**
   - Download important backups
   - Store copies off-server

3. **Monitoring**
   - Check backup logs regularly
   - Monitor backup file sizes

4. **Retention Policy**
   - Set appropriate retention based on needs
   - Balance storage space vs. recovery options

5. **Before Major Changes**
   - Always create manual backup
   - Test changes in development first

## API Endpoints (Admin Panel)

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/admin/backup` | View backup management page |
| POST | `/admin/backup/create` | Create new backup |
| POST | `/admin/backup/restore` | Restore from backup |
| GET | `/admin/backup/download/{filename}` | Download backup file |
| DELETE | `/admin/backup/delete/{filename}` | Delete backup file |
| POST | `/admin/backup/cleanup` | Run cleanup process |

## Support

For issues or questions:
1. Check the logs: `storage/logs/laravel.log`
2. Review this documentation
3. Contact the development team

## Version History

- **v1.0.0** (2025-10-24): Initial release
  - Full database backup/restore
  - Automated scheduling
  - Admin panel interface
  - CLI commands
  - Retention policy
  - Compression support
