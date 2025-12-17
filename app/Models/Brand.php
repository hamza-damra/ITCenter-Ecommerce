<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name_en',
        'name_ar',
        'name_he',
        'slug',
        'description_en',
        'description_ar',
        'description_he',
        'logo',
        'website',
        'email',
        'phone',
        'is_active',
        'is_featured',
        'order',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'order' => 'integer',
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
     * Get the logo with fallback to default.
     */
    public function getLogoAttribute($value)
    {
        if (empty($value)) {
            return asset('images/products/default.png');
        }
        
        // If it's already a full URL, return it as is
        if (str_starts_with($value, 'http')) {
            return $value;
        }
        
        // Handle different storage path formats
        if (str_starts_with($value, 'storage/')) {
            $imagePath = public_path($value);
            if (file_exists($imagePath)) {
                return asset($value);
            }
        }
        
        if (str_starts_with($value, 'images/')) {
            $imagePath = public_path($value);
            if (file_exists($imagePath)) {
                return asset($value);
            }
        }
        
        // Try adding 'storage/' prefix
        $storagePath = public_path('storage/' . $value);
        if (file_exists($storagePath)) {
            return asset('storage/' . $value);
        }
        
        // Try the path directly in public folder
        $publicPath = public_path($value);
        if (file_exists($publicPath)) {
            return asset($value);
        }
        
        // Fallback to default image
        return asset('images/products/default.png');
    }

    /**
     * Get the description attribute based on current locale.
     */
    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"description_$locale"} ?? $this->description_en;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($brand) {
            if (empty($brand->slug)) {
                $brand->slug = Str::slug($brand->name_en);
            }
        });
    }

    /**
     * Get all products for this brand.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Scope a query to only include active brands.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured brands.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
