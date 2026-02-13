<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingRegion extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name_en',
        'name_ar',
        'name_he',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Get the localized name based on current locale.
     */
    public function getNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"name_{$locale}"} ?? $this->name_en;
    }

    /**
     * Get cities belonging to this region.
     */
    public function cities(): HasMany
    {
        return $this->hasMany(ShippingCity::class)->orderBy('sort_order');
    }

    /**
     * Get only active cities.
     */
    public function activeCities(): HasMany
    {
        return $this->hasMany(ShippingCity::class)->where('is_active', true)->orderBy('sort_order');
    }

    /**
     * Scope: active regions only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: ordered by sort_order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
