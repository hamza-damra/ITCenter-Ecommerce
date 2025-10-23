<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromotionalOffer extends Model
{
    protected $fillable = [
        'product_id',
        'title_en',
        'title_ar',
        'title_he',
        'original_price',
        'sale_price',
        'discount_amount',
        'discount_percentage',
        'features_en',
        'features_ar',
        'features_he',
        'start_date',
        'end_date',
        'is_active',
        'display_order',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
        'original_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getTitleAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"title_$locale"} ?? $this->title_en;
    }

    public function getFeaturesAttribute()
    {
        $locale = app()->getLocale();
        $features = $this->{"features_$locale"} ?? $this->features_en;
        return $features ? json_decode($features, true) : [];
    }

    public function getDiscountAmountAttribute()
    {
        if (isset($this->attributes['discount_amount'])) {
            return $this->attributes['discount_amount'];
        }
        return $this->original_price - $this->sale_price;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }
}
