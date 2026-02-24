<?php

namespace App\Models;

use App\Traits\HasUploadedImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes, HasUploadedImage;

    /**
     * Image columns managed by HasUploadedImage trait.
     */
    protected function imageColumns(): array
    {
        return ['image'];
    }

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
            return \App\Helpers\ImageHelper::assetUrl('images/products/default.png');
        }

        // If it's already a full URL, return it as is
        if (str_starts_with($value, 'http')) {
            return $value;
        }

        // If path starts with 'storage/', strip it (normalize)
        if (str_starts_with($value, 'storage/')) {
            $value = substr($value, 8);
        }

        // If path starts with 'images/', it's in the public folder
        if (str_starts_with($value, 'images/')) {
            return asset($value);
        }

        // All other paths are in storage/app/public, served via /media/ route
        return asset('media/' . $value);
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
     * Get the full URL path for this category based on its position in hierarchy.
     *
     * @return string
     */
    public function getUrlAttribute(): string
    {
        $ancestors = $this->ancestors();

        if ($ancestors->isEmpty()) {
            // This is a parent category
            return route('category.show', $this->slug);
        }

        // Build the slug path from ancestors
        $slugs = $ancestors->pluck('slug')->toArray();
        $slugs[] = $this->slug;

        return route('category.show', $slugs);
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
     * Get all descendant categories (children and sub-children) recursively.
     *
     * @return \Illuminate\Support\Collection<Category>
     */
    public function descendants(): \Illuminate\Support\Collection
    {
        $descendants = collect();

        foreach ($this->children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->descendants());
        }

        return $descendants;
    }

    /**
     * Get all ancestor categories (parent chain) up to root.
     * Returns collection ordered from root to immediate parent.
     *
     * @return \Illuminate\Support\Collection<Category>
     */
    public function ancestors(): \Illuminate\Support\Collection
    {
        $ancestors = collect();
        $current = $this->parent;

        while ($current !== null) {
            $ancestors->prepend($current);
            $current = $current->parent;
        }

        return $ancestors;
    }

    /**
     * Get the specification template for this category.
     */
    public function specTemplate()
    {
        // Check if table exists before trying to query
        if (!\Illuminate\Support\Facades\Schema::hasTable('spec_templates')) {
            return $this->hasOne(SpecTemplate::class)->whereRaw('1 = 0'); // Return empty relation
        }
        return $this->hasOne(SpecTemplate::class);
    }

    /**
     * Check if this category has a specification template.
     */
    public function hasSpecTemplate(): bool
    {
        return $this->specTemplate()->exists();
    }

    /**
     * Get specification fields for this category (through template).
     */
    public function getSpecFieldsAttribute()
    {
        if (!$this->specTemplate) {
            return collect();
        }
        return $this->specTemplate->activeFields;
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

    // ── Dynamic Filter Relationships ──────────────────────────

    /**
     * Get filter assignments for this category.
     */
    public function filterAssignments()
    {
        return $this->hasMany(FilterAssignment::class);
    }

    /**
     * Get filters directly assigned to this category.
     */
    public function filters()
    {
        return $this->belongsToMany(Filter::class, 'filter_assignments')
            ->withPivot('inherit_to_children')
            ->withTimestamps();
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        // Check if current route is an admin route
        $route = request()->route();
        if ($route && str_contains($route->getName() ?? '', 'admin.')) {
            return 'id';
        }
        return 'slug';
    }
}
