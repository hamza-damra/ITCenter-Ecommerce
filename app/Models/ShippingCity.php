<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingCity extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_region_id',
        'key',
        'name_en',
        'name_ar',
        'name_he',
        'governorate_en',
        'governorate_ar',
        'governorate_he',
        'postal_code_min',
        'postal_code_max',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'postal_code_min' => 'integer',
            'postal_code_max' => 'integer',
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
     * Get the localized governorate name.
     */
    public function getGovernorateAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"governorate_{$locale}"} ?? $this->governorate_en;
    }

    /**
     * Get the formatted postal code range string (e.g., "P400 – P499").
     */
    public function getPostalRangeAttribute(): string
    {
        return 'P' . str_pad($this->postal_code_min, 3, '0', STR_PAD_LEFT)
            . ' – P' . str_pad($this->postal_code_max, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Check if a given numeric postal code falls within this city's range.
     */
    public function isPostalCodeInRange(int $numericCode): bool
    {
        $prefix = (int) substr((string) $numericCode, 0, 3);
        return $prefix >= $this->postal_code_min && $prefix <= $this->postal_code_max;
    }

    /**
     * Get the region this city belongs to.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(ShippingRegion::class, 'shipping_region_id');
    }

    /**
     * Scope: active cities only.
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

    /**
     * Get all valid city keys (for validation).
     */
    public static function getValidKeys(): array
    {
        return static::active()->pluck('key')->toArray();
    }
}
