<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Backup Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the database backup system settings including storage path,
    | retention policy, and schedule.
    |
    */

    /**
     * The directory where backups will be stored.
     * This should be outside the public directory for security.
     */
    'path' => storage_path('app/backups'),

    /**
     * Retention policy: Number of days to keep backups.
     * Backups older than this will be automatically deleted.
     */
    'retention_days' => env('BACKUP_RETENTION_DAYS', 30),

    /**
     * Schedule configuration for automatic backups.
     * Options: 'daily', 'weekly', 'monthly', 'custom'
     */
    'schedule' => env('BACKUP_SCHEDULE', 'daily'),

    /**
     * Time to run daily backup (24-hour format: HH:MM)
     */
    'daily_time' => env('BACKUP_DAILY_TIME', '02:00'),

    /**
     * Day of week for weekly backups (0 = Sunday, 6 = Saturday)
     */
    'weekly_day' => env('BACKUP_WEEKLY_DAY', 0),

    /**
     * Day of month for monthly backups (1-31)
     */
    'monthly_day' => env('BACKUP_MONTHLY_DAY', 1),

    /**
     * Backup file prefix
     */
    'prefix' => env('BACKUP_PREFIX', 'backup'),

    /**
     * Compress backups using gzip
     */
    'compress' => env('BACKUP_COMPRESS', true),

    /**
     * Maximum number of backups to keep (regardless of age)
     * Set to null for unlimited
     */
    'max_backups' => env('BACKUP_MAX_BACKUPS', null),

    /**
     * Notification settings
     */
    'notifications' => [
        'enabled' => env('BACKUP_NOTIFICATIONS_ENABLED', false),
        'email' => env('BACKUP_NOTIFICATION_EMAIL', null),
    ],

    /**
     * Backup database connection
     * Defaults to the default database connection
     */
    'connection' => env('BACKUP_DB_CONNECTION', config('database.default')),

    /**
     * Tables to exclude from backup (e.g., cache, sessions)
     */
    'exclude_tables' => [
        // 'cache',
        // 'sessions',
    ],

    /**
     * Module definitions for selective backups
     * Each module contains specific database tables
     * Note: Images are stored as URLs in the database, not as files
     */
    'modules' => [
        'products' => [
            'name' => 'Products & Inventory',
            'tables' => ['products', 'product_offers', 'product_attributes'],
        ],
        'categories' => [
            'name' => 'Categories & Brands',
            'tables' => ['categories', 'brands'],
        ],
        'users' => [
            'name' => 'Users & Authentication',
            'tables' => ['users', 'password_reset_tokens', 'sessions'],
        ],
        'orders' => [
            'name' => 'Orders & Transactions',
            'tables' => ['orders', 'order_items'],
        ],
        'cart' => [
            'name' => 'Shopping Cart',
            'tables' => ['cart_items'],
        ],
        'favorites' => [
            'name' => 'Wishlist & Favorites',
            'tables' => ['favorites'],
        ],
        'offers' => [
            'name' => 'Offers & Deals',
            'tables' => ['offers', 'product_offers'],
        ],
        'contacts' => [
            'name' => 'Contact Messages',
            'tables' => ['contacts'],
        ],
        'attributes' => [
            'name' => 'Product Attributes',
            'tables' => ['attributes', 'attribute_values'],
        ],
    ],

    /**
     * Maximum upload file size for importing backups (in MB)
     */
    'max_upload_size' => env('BACKUP_MAX_UPLOAD_SIZE', 512),

    /**
     * Allowed backup file extensions for imports
     */
    'allowed_extensions' => ['sql', 'gz', 'zip'],

];
