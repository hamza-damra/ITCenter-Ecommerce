<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name_en',
        'product_name_ar',
        'product_name_he',
        'product_slug',
        'product_image',
        'product_sku',
        'price',
        'original_price',
        'quantity',
        'subtotal',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'quantity' => 'integer',
    ];

    // Relationships
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Accessors
    public function getProductNameAttribute(): string
    {
        $locale = app()->getLocale();
        return $this->{"product_name_$locale"} ?? $this->product_name_en;
    }

    public function getHasDiscountAttribute(): bool
    {
        return $this->original_price && $this->original_price > $this->price;
    }

    public function getDiscountPercentageAttribute(): float
    {
        if (!$this->has_discount) {
            return 0;
        }
        return round((($this->original_price - $this->price) / $this->original_price) * 100);
    }
}
