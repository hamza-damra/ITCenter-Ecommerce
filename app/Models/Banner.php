<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_path',
        'title_en',
        'title_ar',
        'title_he',
        'subtitle_en',
        'subtitle_ar',
        'subtitle_he',
        'link',
        'button_text_en',
        'button_text_ar',
        'button_text_he',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get the title attribute based on current locale with fallback to English.
     */
    public function getTitleAttribute(): ?string
    {
        $locale = app()->getLocale();
        $localizedTitle = $this->{"title_$locale"};
        
        // Return localized title if not empty, otherwise fallback to English
        if (!empty($localizedTitle)) {
            return $localizedTitle;
        }
        
        return $this->title_en;
    }

    /**
     * Get the subtitle attribute based on current locale with fallback to English.
     */
    public function getSubtitleAttribute(): ?string
    {
        $locale = app()->getLocale();
        $localizedSubtitle = $this->{"subtitle_$locale"};
        
        // Return localized subtitle if not empty, otherwise fallback to English
        if (!empty($localizedSubtitle)) {
            return $localizedSubtitle;
        }
        
        return $this->subtitle_en;
    }

    /**
     * Get the button text attribute based on current locale with fallback to English.
     */
    public function getButtonTextAttribute(): ?string
    {
        $locale = app()->getLocale();
        $localizedButtonText = $this->{"button_text_$locale"};
        
        // Return localized button text if not empty, otherwise fallback to English
        if (!empty($localizedButtonText)) {
            return $localizedButtonText;
        }
        
        return $this->button_text_en;
    }

    /**
     * Get the full URL for the banner image.
     */
    public function getImageUrlAttribute(): string
    {
        if (empty($this->image_path)) {
            return asset('images/assets/Banner.jpg');
        }

        // If it's already a full URL, return as is
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }

        // Return the asset URL for the stored path
        return asset('storage/' . $this->image_path);
    }

    /**
     * Scope a query to only include active banners.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order banners by display_order and created_at.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc')
                     ->orderBy('created_at', 'asc');
    }
}
