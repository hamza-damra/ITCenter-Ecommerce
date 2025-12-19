# Bootstrap Mode Troubleshooting

## Issue: System Not Applied / Still Showing Error Page

If you're still seeing the old "Database Connection Lost" error page instead of being redirected to Bootstrap Mode, follow these steps:

### Step 1: Verify Configuration

1. **Check .env file** has bootstrap credentials:
   ```env
   BOOTSTRAP_MODE_ENABLED=true
   BOOTSTRAP_ADMIN_EMAIL=admin@example.com
   BOOTSTRAP_ADMIN_PASSWORD_HASH=$2y$10$...
   ```

2. **Clear all caches**:
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   php artisan view:clear
   ```

### Step 2: Check Database State

Visit this URL to check database state:
```
http://127.0.0.1:8000/admin/bootstrap/status
```

Or run in tinker:
```bash
php artisan tinker
```
```php
\App\Services\DatabaseStateService::detectState()
```

Expected results:
- `missing_db` = STATE_B (should enable bootstrap mode)
- `unreachable` = STATE_A (MySQL server down, bootstrap won't work)
- `available` = STATE_C (database exists, normal mode)

### Step 3: Verify Middleware is Running

The middleware should run BEFORE any database queries. Check:

1. **Middleware order** in `bootstrap/app.php`:
   ```php
   $middleware->web(prepend: [
       \App\Http\Middleware\BootstrapModeMiddleware::class,  // Must be first!
       ...
   ]);
   ```

2. **Routes** should have bootstrap.mode middleware:
   ```php
   Route::prefix('admin')->middleware('bootstrap.mode')->group(...)
   ```

### Step 4: Test Direct Access

Try accessing bootstrap login directly:
```
http://127.0.0.1:8000/admin/bootstrap/login
```

If this works, the issue is with the redirect logic.

### Step 5: Check Logs

Check these log files for errors:
- `storage/logs/laravel.log` - General errors
- `storage/logs/bootstrap-db.log` - Bootstrap-specific logs

Look for:
- "Database state detection failed"
- "Bootstrap mode state detection failed"
- Any PDO exceptions

### Step 6: Common Issues

#### Issue: Exception Handler Not Catching Errors

**Symptom**: Still seeing old error page

**Solution**: The exception handler in `bootstrap/app.php` should catch PDOException with code 1049. Verify:
```php
if (str_contains($message, '1049') || str_contains($message, 'Unknown database')) {
    $isDbMissing = true;
}
```

#### Issue: View Composer Accessing Database

**Symptom**: Error happens before middleware can redirect

**Solution**: Updated `AppServiceProvider` to check database state before querying. Verify it's updated.

#### Issue: Session Driver Still Using Database

**Symptom**: Cascading failures

**Solution**: Middleware should force `file` driver. Check:
```php
Config::set('session.driver', 'file');
```

### Step 7: Manual Test

1. **Stop MySQL server** (or delete database)
2. **Visit** `http://127.0.0.1:8000/admin`
3. **Expected**: Redirect to `/admin/bootstrap/login`
4. **If not**: Check middleware is registered and running

### Step 8: Debug Mode

Enable debug mode temporarily:
```env
APP_DEBUG=true
```

This will show more detailed error messages.

### Step 9: Verify Files Exist

Ensure these files exist:
- `app/Http/Middleware/BootstrapModeMiddleware.php`
- `app/Services/DatabaseStateService.php`
- `app/Http/Controllers/Admin/BootstrapController.php`
- `resources/views/admin/bootstrap/login.blade.php`

### Step 10: Restart Server

After making changes, restart your development server:
```bash
php artisan serve
```

## Still Not Working?

1. Check if you're actually in STATE_B (missing database) vs STATE_A (unreachable)
2. Verify bootstrap routes are accessible: `/admin/bootstrap/login`
3. Check if there are any syntax errors in the code
4. Review `storage/logs/laravel.log` for specific error messages
5. Try accessing bootstrap login directly to bypass redirect logic

## Quick Fix Command

Run this to reset everything:
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

Then restart your server and try again.

