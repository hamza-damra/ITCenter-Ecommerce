<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HomeCacheService
{
    /**
     * Cache key prefix for home page data
     */
    const CACHE_PREFIX = 'home_page_data_';

    /**
     * Cache duration in seconds (30 minutes)
     */
    const CACHE_DURATION = 1800;

    /**
     * Available locales
     */
    const LOCALES = ['ar', 'en', 'he'];

    /**
     * Clear all home page cache
     */
    public static function clearAll(): void
    {
        try {
            // Clear cache for all locales
            foreach (self::LOCALES as $locale) {
                Cache::forget(self::CACHE_PREFIX . $locale);
            }

            // If using database cache driver, also truncate the table for certainty
            if (config('cache.default') === 'database') {
                DB::table('cache')
                    ->where('key', 'like', '%' . self::CACHE_PREFIX . '%')
                    ->delete();
            }

            Log::info('Home page cache cleared successfully');
        } catch (\Exception $e) {
            Log::error('Failed to clear home page cache: ' . $e->getMessage());
        }
    }

    /**
     * Clear cache for specific locale
     */
    public static function clearForLocale(string $locale): void
    {
        Cache::forget(self::CACHE_PREFIX . $locale);
    }

    /**
     * Get cache key for current locale
     */
    public static function getCacheKey(): string
    {
        return self::CACHE_PREFIX . app()->getLocale();
    }

    /**
     * Force refresh - clear all and return fresh data indicator
     */
    public static function forceRefresh(): bool
    {
        self::clearAll();
        return true;
    }
}
