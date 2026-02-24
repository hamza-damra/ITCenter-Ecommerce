<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class FilterSectionSetting extends Model
{
    protected $fillable = ['section_key', 'is_enabled', 'sort_order'];
    protected $casts = ['is_enabled' => 'boolean', 'sort_order' => 'integer'];

    const CACHE_KEY = 'filter_section_settings';
    const CACHE_TTL = 3600;

    /**
     * Get all section settings, ordered by sort_order.
     */
    public static function getOrderedSettings(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return static::orderBy('sort_order')->get()->keyBy('section_key')->toArray();
        });
    }

    /**
     * Check if a section is enabled.
     */
    public static function isEnabled(string $sectionKey): bool
    {
        $settings = static::getOrderedSettings();
        $s = $settings[$sectionKey] ?? null;
        return $s ? (bool) ($s['is_enabled'] ?? true) : true;
    }

    /**
     * Invalidate cache (call after updating settings).
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::clearCache());
        static::deleted(fn () => static::clearCache());
    }
}
