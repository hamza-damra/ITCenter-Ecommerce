<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name_en',
        'name_ar',
        'name_he',
        'slug',
        'color',
        'icon',
        'position',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'position' => 'integer',
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

        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name_en);
            }
        });
    }

    /**
     * Get all products with this tag.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_tag')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include active tags.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order by position.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('position')->orderBy('name_en');
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

    /**
     * Get badge HTML for display.
     */
    public function getBadgeHtmlAttribute()
    {
        $icon = $this->icon ? "<i class=\"{$this->icon}\"></i> " : '';
        return "<span class=\"tag-badge\" style=\"background-color: {$this->color}; color: white; padding: 2px 8px; border-radius: 4px; font-size: 12px;\">{$icon}{$this->name}</span>";
    }
}
