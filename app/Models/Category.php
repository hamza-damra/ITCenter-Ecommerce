<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
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
        'image',
        'icon',
        'position',
        'parent_id',
        'is_active',
        'display_mode',
        'order',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected $attributes = [
        'display_mode' => 'carousel',
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
     * Get the image with fallback to default.
     */
    public function getImageAttribute($value)
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

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name_en);
            }
        });
    }

    /**
     * Get the parent category.
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Get the child categories.
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Get all products in this category.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get all products including sub-category products.
     */
    public function allProducts()
    {
        $categoryIds = $this->children()->pluck('id')->push($this->id);
        return Product::whereIn('category_id', $categoryIds);
    }

    /**
     * Get the attributes assigned to this category (for filtering).
     */
    public function attributes()
    {
        return $this->belongsToMany(Attribute::class, 'attribute_category')
            ->withTimestamps()
            ->orderBy('order');
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include parent categories.
     */
    public function scopeParent($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope a query to only include carousel display mode categories.
     */
    public function scopeCarousel($query)
    {
        return $query->where('display_mode', 'carousel');
    }

    /**
     * Scope a query to only include nav display mode categories.
     */
    public function scopeNav($query)
    {
        return $query->where('display_mode', 'nav');
    }

    /**
     * Get the display mode badge HTML.
     */
    public function getDisplayModeBadgeAttribute()
    {
        if ($this->display_mode === 'nav') {
            return '<span class="status-badge" style="background: #dbeafe; color: #1e40af;"><i class="fas fa-bars"></i> ' . __('messages.nav_bar') . '</span>';
        }
        return '<span class="status-badge" style="background: #fef3c7; color: #92400e;"><i class="fas fa-images"></i> ' . __('messages.carousel') . '</span>';
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
