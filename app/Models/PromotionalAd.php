<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionalAd extends Model
{
    use HasFactory;

    /**
     * Image source types
     */
    const SOURCE_DATABASE = 'database';
    const SOURCE_URL = 'url';

    protected $fillable = [
        'image_path',
        'image_source',
        'image_data',
        'image_filename',
        'image_mime_type',
        'position',
        'link',
        'is_active',
        // Title fields
        'title_en',
        'title_ar',
        'title_he',
        'title_color',
        'title_font_size',
        // Subtitle fields
        'subtitle_en',
        'subtitle_ar',
        'subtitle_he',
        'subtitle_color',
        'subtitle_font_size',
        // Button fields
        'button_text_en',
        'button_text_ar',
        'button_text_he',
        'button_bg_color',
        'button_text_color',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * The attributes that should be hidden for serialization.
     * Hide the large image_data from JSON responses by default.
     */
    protected $hidden = [
        'image_data',
    ];

    /**
     * Get the full URL for the promotional ad image.
     */
    public function getImageUrlAttribute(): string
    {
        // Handle based on image source type
        $imageSource = $this->image_source ?? self::SOURCE_DATABASE;
        
        switch ($imageSource) {
            case self::SOURCE_DATABASE:
                // For database-stored images, return a route that serves the image
                if (!empty($this->image_data)) {
                    return route('promotional-ad.image', ['promotionalAd' => $this->id]);
                }
                // Fall through to legacy file handling if no database data
                break;

            case self::SOURCE_URL:
                // For external URLs, return the URL directly
                if (!empty($this->image_path)) {
                    return $this->image_path;
                }
                break;
        }

        // Legacy file storage support (for existing records before migration)
        if (!empty($this->image_path)) {
            // If it's already a full URL, return as is
            if (str_starts_with($this->image_path, 'http')) {
                return $this->image_path;
            }
            // Serve via /media/ route (no file_exists check - let the route handle 404)
            return asset('media/' . $this->image_path);
        }

        // Default fallback image
        return asset('images/assets/Banner.jpg');
    }

    /**
     * Get the image as a base64 data URI for inline embedding.
     * Useful for displaying database-stored images directly in HTML.
     */
    public function getImageDataUriAttribute(): ?string
    {
        if ($this->image_source === self::SOURCE_DATABASE && !empty($this->image_data)) {
            $mimeType = $this->image_mime_type ?? 'image/jpeg';
            return "data:{$mimeType};base64,{$this->image_data}";
        }
        
        return null;
    }

    /**
     * Check if image is stored in database.
     */
    public function isImageInDatabase(): bool
    {
        return ($this->image_source ?? self::SOURCE_DATABASE) === self::SOURCE_DATABASE && !empty($this->image_data);
    }

    /**
     * Check if image is from external URL.
     */
    public function isImageFromUrl(): bool
    {
        return $this->image_source === self::SOURCE_URL && !empty($this->image_path);
    }

    /**
     * Get the title attribute based on current locale with fallback to English.
     */
    public function getTitleAttribute(): ?string
    {
        $locale = app()->getLocale();
        $localizedTitle = $this->{"title_$locale"};
        
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
        
        if (!empty($localizedButtonText)) {
            return $localizedButtonText;
        }
        
        return $this->button_text_en;
    }

    /**
     * Check if the promotional ad has any title content.
     */
    public function hasTitle(): bool
    {
        return !empty($this->title_en) || !empty($this->title_ar) || !empty($this->title_he);
    }

    /**
     * Check if the promotional ad has any subtitle content.
     */
    public function hasSubtitle(): bool
    {
        return !empty($this->subtitle_en) || !empty($this->subtitle_ar) || !empty($this->subtitle_he);
    }

    /**
     * Check if the promotional ad has any button text.
     */
    public function hasButton(): bool
    {
        return !empty($this->button_text_en) || !empty($this->button_text_ar) || !empty($this->button_text_he);
    }

    /**
     * Scope a query to only include active promotional ads.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by position.
     */
    public function scopeForPosition($query, string $position)
    {
        return $query->where('position', $position);
    }
}
