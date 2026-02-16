<?php

namespace App\Models;

use App\Traits\HasUploadedImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory, HasUploadedImage;

    /**
     * Image source types
     */
    const SOURCE_FILE = 'file';
    const SOURCE_URL = 'url';
    const SOURCE_DATABASE = 'database'; // Legacy

    protected $fillable = [
        'image_path',
        'image_source',
        'title_en',
        'title_ar',
        'title_he',
        'title_color',
        'subtitle_en',
        'subtitle_ar',
        'subtitle_he',
        'subtitle_color',
        'link',
        'button_text_en',
        'button_text_ar',
        'button_text_he',
        'button_bg_color',
        'button_text_color',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * Get the image columns that hold uploaded file paths.
     */
    protected function imageColumns(): array
    {
        return ['image_path'];
    }

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
        if (!empty($this->image_path)) {
            // If it's already a full URL (legacy/external URL support), return as is
            if (str_starts_with($this->image_path, 'http')) {
                return $this->image_path;
            }
            // Local file storage - use the trait helper
            return $this->getImageUrl('image_path', asset('images/assets/Banner.jpg'));
        }

        // Default fallback image
        return asset('images/assets/Banner.jpg');
    }

    /**
     * Check if banner has a valid image.
     */
    public function hasImage(): bool
    {
        return !empty($this->image_path);
    }

    /**
     * Check if image is from external URL.
     */
    public function isImageFromUrl(): bool
    {
        return $this->image_source === self::SOURCE_URL;
    }

    /**
     * Check if image is stored as local file.
     */
    public function isImageInFile(): bool
    {
        return $this->image_source === self::SOURCE_FILE || ($this->image_source !== self::SOURCE_URL && !empty($this->image_path) && !str_starts_with($this->image_path, 'http'));
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
