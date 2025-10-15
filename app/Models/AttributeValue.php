<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribute_id',
        'value',
        'color_code',
        'order',
        'is_active',
    ];

    protected $casts = [
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the attribute that owns this value.
     */
    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    /**
     * Get all products with this attribute value.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_attributes')
            ->withPivot('price_adjustment', 'stock_quantity')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include active attribute values.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
