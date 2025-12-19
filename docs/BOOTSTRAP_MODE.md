# Bootstrap Mode Documentation

## Overview

Bootstrap Mode is a DB-less admin access system designed to help administrators restore their database when the MySQL server is reachable but the target database schema is missing (SQLSTATE[HY000] [1049] Unknown database).

This feature allows you to:
- Access admin panel without database
- Upload and import SQL dumps
- Restore from backup files
- Create missing databases
- Validate database after restoration

## When Bootstrap Mode Activates

Bootstrap Mode activates automatically when:

1. ✅ **Frontend can reach the backend** (Laravel application is running)
2. ✅ **MySQL server credentials/host are reachable** (can connect to MySQL)
3. ❌ **Target database/schema does NOT exist** (SQLSTATE[HY000] [1049])

### State Detection

The system detects three possible states:

- **STATE_A (unreachable)**: MySQL host unreachable or credentials invalid
  - Shows standard error page
  - Bootstrap mode NOT available

- **STATE_B (missing_db)**: MySQL reachable, but database schema missing
  - Bootstrap mode ENABLED
  - Redirects `/admin/*` routes to `/admin/bootstrap/login`

- **STATE_C (available)**: Database exists and is accessible
  - Normal mode
  - Bootstrap routes are blocked (404)

## Setup

### 1. Environment Variables

Add these to your `.env` file:

```env
# Bootstrap Mode Configuration
BOOTSTRAP_MODE_ENABLED=true
BOOTSTRAP_ADMIN_EMAIL=admin@example.com
BOOTSTRAP_ADMIN_PASSWORD_HASH=$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi

# Optional: IP Allowlist (comma-separated)
BOOTSTRAP_ALLOWED_IPS=127.0.0.1,192.168.1.100

# Optional: Maximum upload size in KB (default: 512MB)
BOOTSTRAP_MAX_UPLOAD_SIZE=524288
```

### 2. Generate Password Hash

To generate a password hash for `BOOTSTRAP_ADMIN_PASSWORD_HASH`:

```bash
php artisan tinker
```

Then run:
```php
Hash::make('your-secure-password')
```

Copy the output and paste it into your `.env` file.

### 3. Security Considerations

**⚠️ IMPORTANT SECURITY NOTES:**

1. **Never commit `.env` file** - Bootstrap credentials should never be in version control
2. **Use strong passwords** - Bootstrap mode bypasses normal authentication
3. **Use IP allowlist in production** - Restrict access to trusted IPs
4. **Change credentials regularly** - Rotate bootstrap credentials periodically
5. **Monitor logs** - Check `storage/logs/bootstrap-db.log` for suspicious activity

## Usage

### Accessing Bootstrap Mode

1. When database is missing, visit any `/admin/*` route
2. You'll be automatically redirected to `/admin/bootstrap/login`
3. Enter your bootstrap admin credentials
4. You'll be taken to the Database Setup page

### Database Setup Page Features

#### Status Card
- Shows current database state
- Displays target database name
- Shows MySQL host and port

#### Import SQL File
- Upload a `.sql` file directly
- Supports large files (streaming for files >50MB)
- Progress bar and log panel show import progress
- Automatically creates database if missing

#### Restore from Backup
- Lists available backup files from `storage/app/backups/`
- Restore from existing backups
- Upload new backup files (SQL, ZIP, GZ)
- Validates backup integrity before restore

#### Validation
- After import/restore, validate database structure
- Checks for critical tables (users, products, categories, orders)
- Verifies migrations table exists
- Confirms admin user presence

### Workflow Example

1. **Database goes missing** (accidental deletion, migration failure, etc.)
2. **Visit `/admin`** → Redirected to `/admin/bootstrap/login`
3. **Login with bootstrap credentials**
4. **Upload SQL dump** or **restore from backup**
5. **Wait for import to complete**
6. **System automatically switches to normal mode**
7. **Redirected to normal admin login**

## Technical Details

### Architecture

```
Request → BootstrapModeMiddleware → DatabaseStateService
                                    ↓
                            STATE_A/B/C Detection
                                    ↓
                    ┌───────────────┴───────────────┐
                    ↓                               ↓
            STATE_B (missing_db)          STATE_C (available)
                    ↓                               ↓
        Enable Bootstrap Mode          Normal Mode
        Force file sessions            Use DB sessions
        Force file cache               Use DB cache
        Redirect admin routes          Block bootstrap routes
```

### Components

1. **DatabaseStateService** (`app/Services/DatabaseStateService.php`)
   - Detects database state early (before Laravel DB usage)
   - Caches state for performance
   - Returns STATE_A, STATE_B, or STATE_C

2. **BootstrapModeMiddleware** (`app/Http/Middleware/BootstrapModeMiddleware.php`)
   - Runs early in request lifecycle
   - Forces non-DB drivers (file sessions, file cache, sync queue)
   - Handles route redirection

3. **Bootstrap Auth System**
   - `BootstrapUserProvider` - DB-less user provider
   - `BootstrapUser` - Minimal user model
   - Uses environment variables for credentials

4. **BootstrapDatabaseService** (`app/Services/BootstrapDatabaseService.php`)
   - Creates databases
   - Imports SQL files (streaming for large files)
   - Validates database structure

5. **BootstrapController** (`app/Http/Controllers/Admin/BootstrapController.php`)
   - Handles login/logout
   - Manages database setup UI
   - Processes import/restore requests

### Forced Non-DB Drivers

When in bootstrap mode, these are automatically forced:

- **Session**: `file` (instead of `database`)
- **Cache**: `file` (instead of `database`)
- **Queue**: `sync` (instead of `database`)

This ensures no database queries are attempted during bootstrap mode.

### Logging

All bootstrap actions are logged to:
```
storage/logs/bootstrap-db.log
```

Log entries include:
- Database creation
- SQL imports
- Backup restores
- Validation results
- Login attempts

## Troubleshooting

### Bootstrap Mode Not Activating

**Problem**: Database is missing but bootstrap mode doesn't activate.

**Solutions**:
1. Check `BOOTSTRAP_MODE_ENABLED=true` in `.env`
2. Verify MySQL server is reachable (not STATE_A)
3. Clear config cache: `php artisan config:clear`
4. Check middleware is registered in `bootstrap/app.php`

### Can't Login to Bootstrap Mode

**Problem**: Bootstrap login fails even with correct credentials.

**Solutions**:
1. Verify `BOOTSTRAP_ADMIN_EMAIL` matches exactly (case-sensitive)
2. Regenerate password hash: `Hash::make('password')`
3. Check `.env` file is loaded: `php artisan config:cache`
4. Check IP allowlist if configured

### Import Fails

**Problem**: SQL import fails with errors.

**Solutions**:
1. Check file size (max: configured in `BOOTSTRAP_MAX_UPLOAD_SIZE`)
2. Verify SQL file is valid (not corrupted)
3. Check MySQL user has CREATE DATABASE permission
4. Review `storage/logs/bootstrap-db.log` for details
5. Try smaller SQL file or split into chunks

### Database Created But Still in Bootstrap Mode

**Problem**: Database exists but system still shows bootstrap mode.

**Solutions**:
1. Clear state cache: `DatabaseStateService::clearCache()`
2. Wait a few seconds (cache TTL is 5 seconds)
3. Click "Re-check Status" button
4. Verify database name matches `DB_DATABASE` in `.env`

## Testing

### Manual Testing Checklist

- [ ] **STATE_A Test**: Stop MySQL server → Should show error page (no bootstrap)
- [ ] **STATE_B Test**: Delete database → Should show bootstrap login
- [ ] **STATE_C Test**: Database exists → Bootstrap routes should be 404
- [ ] **Bootstrap Login**: Login with bootstrap credentials → Should work
- [ ] **SQL Import**: Upload SQL file → Should import successfully
- [ ] **Backup Restore**: Restore from backup → Should work
- [ ] **Auto Switch**: After restore → Should redirect to normal admin login
- [ ] **Route Protection**: Normal mode → Bootstrap routes blocked

### Automated Tests

Create tests in `tests/Feature/BootstrapModeTest.php`:

```php
public function test_bootstrap_mode_activates_when_database_missing()
{
    // Test STATE_B detection
}

public function test_bootstrap_mode_blocked_when_database_exists()
{
    // Test STATE_C blocks bootstrap routes
}

public function test_bootstrap_login_works()
{
    // Test bootstrap authentication
}
```

## Configuration Reference

See `config/bootstrap.php` for all configuration options.

## Support

For issues or questions:
1. Check `storage/logs/bootstrap-db.log`
2. Review Laravel logs: `storage/logs/laravel.log`
3. Enable debug mode: `APP_DEBUG=true` in `.env`
4. Check database state: Visit `/admin/bootstrap/status` (when in bootstrap mode)

## Security Best Practices

1. ✅ Use strong, unique passwords for bootstrap admin
2. ✅ Enable IP allowlist in production
3. ✅ Regularly rotate bootstrap credentials
4. ✅ Monitor bootstrap log file
5. ✅ Keep bootstrap mode disabled when not needed
6. ✅ Use HTTPS in production
7. ✅ Restrict file upload permissions
8. ✅ Validate uploaded SQL files before import

---

**Version**: 1.0.0  
**Last Updated**: 2025-01-19

