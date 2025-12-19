# Bootstrap Mode - Quick Start Guide

## What is Bootstrap Mode?

Bootstrap Mode is a DB-less admin access system that activates when your MySQL server is reachable but the target database schema is missing. It allows you to restore your database without manual database server access.

## Quick Setup

### 1. Generate Bootstrap Credentials

Run the setup command:

```bash
php artisan bootstrap:setup
```

This will:
- Prompt for email and password
- Generate a password hash
- Optionally update your `.env` file automatically

### 2. Manual Setup (Alternative)

If you prefer manual setup, add to your `.env`:

```env
BOOTSTRAP_MODE_ENABLED=true
BOOTSTRAP_ADMIN_EMAIL=admin@example.com
BOOTSTRAP_ADMIN_PASSWORD_HASH=$2y$10$...
```

Generate password hash:
```bash
php artisan tinker
Hash::make('your-secure-password')
```

### 3. Clear Config Cache

```bash
php artisan config:clear
```

## Usage

### When Database is Missing

1. Visit `/admin` → Automatically redirected to `/admin/bootstrap/login`
2. Login with bootstrap credentials
3. Upload SQL file or restore from backup
4. System automatically switches to normal mode after restore

### Testing

To test bootstrap mode:

1. **Delete your database** (or rename it)
2. Visit `/admin` → Should redirect to bootstrap login
3. Login → Should show database setup page
4. Restore database → Should redirect to normal admin login

## Security

- ✅ Use strong passwords
- ✅ Enable IP allowlist in production: `BOOTSTRAP_ALLOWED_IPS=127.0.0.1,192.168.1.100`
- ✅ Never commit `.env` file
- ✅ Monitor `storage/logs/bootstrap-db.log`

## Documentation

Full documentation: `docs/BOOTSTRAP_MODE.md`

## Troubleshooting

**Bootstrap mode not activating?**
- Check `BOOTSTRAP_MODE_ENABLED=true` in `.env`
- Clear config cache: `php artisan config:clear`
- Verify MySQL server is reachable

**Can't login?**
- Verify email matches exactly (case-sensitive)
- Regenerate password hash
- Check IP allowlist if configured

**Import fails?**
- Check file size (max: 512MB default)
- Verify SQL file is valid
- Check MySQL user has CREATE DATABASE permission
- Review `storage/logs/bootstrap-db.log`

