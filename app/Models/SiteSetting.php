<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'group'];

    /**
     * Get a setting value by key with optional default.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue(string $key, mixed $default = null): mixed
    {
        try {
            $setting = Cache::remember("site_setting.{$key}", 3600, function () use ($key) {
                return static::where('key', $key)->first();
            });

            if (!$setting) {
                return $default;
            }

            return match ($setting->type) {
                'integer' => (int) $setting->value,
                'boolean' => (bool) $setting->value,
                'json' => json_decode($setting->value, true),
                default => $setting->value,
            };
        } catch (\Exception $e) {
            return $default;
        }
    }

    /**
     * Set a setting value by key.
     *
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @param string $group
     * @return static
     */
    public static function setValue(string $key, mixed $value, string $type = 'string', string $group = 'general'): static
    {
        if ($type === 'boolean') {
            $value = $value ? '1' : '0';
        } elseif ($type === 'json') {
            $value = json_encode($value);
        }

        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => (string) $value, 'type' => $type, 'group' => $group]
        );

        Cache::forget("site_setting.{$key}");
        Cache::forget('site_settings.images');
        Cache::forget('site_settings.branding');

        return $setting;
    }

    /**
     * Get all settings for a group.
     *
     * @param string $group
     * @return \Illuminate\Support\Collection
     */
    public static function getGroup(string $group): \Illuminate\Support\Collection
    {
        return Cache::remember("site_settings.{$group}", 3600, function () use ($group) {
            try {
                return static::where('group', $group)->get()->pluck('value', 'key');
            } catch (\Exception $e) {
                return collect();
            }
        });
    }

    /**
     * Clear all cached settings.
     */
    public static function clearCache(): void
    {
        try {
            $keys = static::pluck('key');
            foreach ($keys as $key) {
                Cache::forget("site_setting.{$key}");
            }
            Cache::forget('site_settings.images');
            Cache::forget('site_settings.general');
            Cache::forget('site_settings.security');
            Cache::forget('site_settings.policies');
            Cache::forget('site_settings.social');
            Cache::forget('site_settings.branding');
        } catch (\Exception $e) {
            // Silently fail if DB not available
        }
    }

    /**
     * Get the current site logo URL with fallback to default.
     */
    public static function getSiteLogoUrl(): string
    {
        $path = static::getValue('site_logo');

        if (!empty($path)) {
            $version = static::getValue('site_logo_version') ?: '1';
            return asset('media/' . $path) . '?v=' . $version;
        }

        return asset('images/assets/logo.png');
    }

    /**
     * Get the raw media URL for logo preview (admin panel).
     */
    public static function getSiteLogoPreviewUrl(): string
    {
        $path = static::getValue('site_logo');

        if (!empty($path)) {
            return asset('media/' . $path);
        }

        return asset('images/assets/logo.png');
    }

    /**
     * Get the current favicon URL with fallback to default.
     */
    public static function getFaviconUrl(): string
    {
        $path = static::getValue('site_favicon');

        if (!empty($path)) {
            $version = static::getValue('site_favicon_version') ?: '1';
            return url('/site-favicon') . '?v=' . $version;
        }

        return asset('favicon.ico');
    }

    /**
     * Get the raw media URL for favicon preview (admin panel).
     */
    public static function getFaviconPreviewUrl(): string
    {
        $path = static::getValue('site_favicon');

        if (!empty($path)) {
            return asset('media/' . $path);
        }

        return asset('favicon.ico');
    }

    /**
     * Get the MIME type for the current favicon based on its file extension.
     */
    public static function getFaviconMime(): string
    {
        $path = static::getValue('site_favicon');

        if (!empty($path)) {
            $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
            // ICO files are served as-is; all other formats are converted to PNG
            // by the /site-favicon route, so report image/png for those.
            return $ext === 'ico' ? 'image/x-icon' : 'image/png';
        }

        return 'image/x-icon';
    }
}
