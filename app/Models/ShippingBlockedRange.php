<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingBlockedRange extends Model
{
    use HasFactory;

    protected $fillable = [
        'postal_code_min',
        'postal_code_max',
        'label_en',
        'label_ar',
        'label_he',
        'reason_en',
        'reason_ar',
        'reason_he',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'postal_code_min' => 'integer',
            'postal_code_max' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the localized label.
     */
    public function getLabelAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"label_{$locale}"} ?? $this->label_en;
    }

    /**
     * Get the localized reason.
     */
    public function getReasonAttribute(): ?string
    {
        $locale = app()->getLocale();
        return $this->{"reason_{$locale}"} ?? $this->reason_en;
    }

    /**
     * Get formatted range string.
     */
    public function getRangeAttribute(): string
    {
        return 'P' . str_pad($this->postal_code_min, 3, '0', STR_PAD_LEFT)
            . ' – P' . str_pad($this->postal_code_max, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Check if a numeric postal prefix falls in this blocked range.
     */
    public function isBlocked(int $numericPrefix): bool
    {
        return $numericPrefix >= $this->postal_code_min && $numericPrefix <= $this->postal_code_max;
    }

    /**
     * Scope: active blocked ranges only.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
