<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Attribute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ar',
        'name_he',
        'slug',
        'type',
        'unit',
        'is_filterable',
        'order',
        'is_active',
    ];

    protected $casts = [
        'order' => 'integer',
        'is_active' => 'boolean',
        'is_filterable' => 'boolean',
    ];

    /**
     * Get the name attribute based on current locale.
     */
    public function getNameAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"name_$locale"} ?? $this->name_en;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($attribute) {
            if (empty($attribute->slug)) {
                $attribute->slug = Str::slug($attribute->name_en);
            }
        });
    }

    /**
     * Get all values for this attribute.
     */
    public function values()
    {
        return $this->hasMany(AttributeValue::class);
    }

    /**
     * Get the categories this attribute is assigned to.
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'attribute_category')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include active attributes.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
