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
        } catch (\Exception $e) {
            // Silently fail if DB not available
        }
    }
}
