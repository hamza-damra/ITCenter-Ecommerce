<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Bootstrap Mode Configuration
    |--------------------------------------------------------------------------
    |
    | Bootstrap Mode is a DB-less admin access system that activates when
    | the MySQL server is reachable but the target database schema is missing.
    | This allows administrators to restore the database without manual
    | database server access.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Bootstrap Mode Enabled
    |--------------------------------------------------------------------------
    |
    | Set to false to completely disable bootstrap mode. When disabled,
    | the system will show a standard error page when database is missing.
    |
    */

    'enabled' => env('BOOTSTRAP_MODE_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Bootstrap Admin Credentials
    |--------------------------------------------------------------------------
    |
    | These credentials are stored in environment variables for security.
    | Never commit these values to version control.
    |
    | BOOTSTRAP_ADMIN_EMAIL - Email address for bootstrap admin login
    | BOOTSTRAP_ADMIN_PASSWORD_HASH - Bcrypt hash of the password
    |
    | To generate a password hash, use:
    | php artisan tinker
    | Hash::make('your-password')
    |
    */

    'admin' => [
        'email' => env('BOOTSTRAP_ADMIN_EMAIL'),
        'password_hash' => env('BOOTSTRAP_ADMIN_PASSWORD_HASH'),
    ],

    /*
    |--------------------------------------------------------------------------
    | IP Allowlist (Optional)
    |--------------------------------------------------------------------------
    |
    | If set, only these IP addresses can access bootstrap mode routes.
    | Leave empty to allow from any IP (not recommended for production).
    |
    | Example: ['127.0.0.1', '192.168.1.100']
    |
    */

    'allowed_ips' => env('BOOTSTRAP_ALLOWED_IPS') ? explode(',', env('BOOTSTRAP_ALLOWED_IPS')) : [],

    /*
    |--------------------------------------------------------------------------
    | Session Configuration
    |--------------------------------------------------------------------------
    |
    | Bootstrap mode uses file-based sessions to avoid database dependencies.
    | These settings override the default session configuration when in
    | bootstrap mode.
    |
    */

    'session' => [
        'driver' => 'file',
        'lifetime' => 120, // 2 hours
        'secure' => env('SESSION_SECURE_COOKIE', false),
        'http_only' => true,
        'same_site' => 'lax',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Bootstrap mode uses file-based cache to avoid database dependencies.
    |
    */

    'cache' => [
        'driver' => 'file',
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue Configuration
    |--------------------------------------------------------------------------
    |
    | Bootstrap mode uses sync queue to avoid database dependencies.
    |
    */

    'queue' => [
        'connection' => 'sync',
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | All bootstrap mode actions are logged to this file for audit purposes.
    |
    */

    'log_file' => storage_path('logs/bootstrap-db.log'),

    /*
    |--------------------------------------------------------------------------
    | Maximum Upload Size
    |--------------------------------------------------------------------------
    |
    | Maximum size for SQL/backup file uploads in kilobytes.
    | Default: 512MB (524288 KB)
    |
    */

    'max_upload_size' => env('BOOTSTRAP_MAX_UPLOAD_SIZE', 524288), // 512MB in KB

    /*
    |--------------------------------------------------------------------------
    | Database State Cache TTL
    |--------------------------------------------------------------------------
    |
    | How long (in seconds) to cache the database state detection result.
    | Lower values provide more real-time detection but increase overhead.
    |
    */

    'state_cache_ttl' => env('BOOTSTRAP_STATE_CACHE_TTL', 5),

];

