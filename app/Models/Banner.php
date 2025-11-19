<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_en',
        'title_ar',
        'title_he',
        'description_en',
        'description_ar',
        'description_he',
        'image_url',
        'button_text_en',
        'button_text_ar',
        'button_text_he',
        'link_type',
        'link_url',
        'category_id',
        'filter_options',
        'display_order',
        'is_active',
        'section',
    ];

    protected $casts = [
        'filter_options' => 'array',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSection($query, $section)
    {
        return $query->where('section', $section);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc');
    }

    // Accessors
    public function getTitleAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"title_{$locale}"} ?? $this->title_en;
    }

    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"description_{$locale}"} ?? $this->description_en;
    }

    public function getButtonTextAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"button_text_{$locale}"} ?? $this->button_text_en;
    }

    // Generate final link URL based on link_type
    public function getFinalLinkAttribute()
    {
        switch ($this->link_type) {
            case 'external':
                return $this->link_url;
            
            case 'products':
                $url = route('products');
                $params = [];
                
                if ($this->category_id) {
                    $params['category'] = $this->category_id;
                }
                
                if ($this->filter_options) {
                    $params = array_merge($params, $this->filter_options);
                }
                
                return $url . ($params ? '?' . http_build_query($params) : '');
            
            case 'category':
                if ($this->category) {
                    return route('categories.show', $this->category->slug);
                }
                return route('categories');
            
            case 'categories':
                return route('categories');
            
            default:
                return route('products');
        }
    }
}
