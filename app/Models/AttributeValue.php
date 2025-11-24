<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'attribute_id',
        'value_en',
        'value_ar',
        'value_he',
        'slug',
        'color_code',
        'order',
        'is_active',
    ];

    protected $casts = [
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the value attribute based on current locale.
     */
    public function getValueAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"value_$locale"} ?? $this->value_en;
    }

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
        return $this->belongsToMany(Product::class, 'product_attribute_values')
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
