<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    /**
     * Image source types
     * Note: SOURCE_FILE is kept for legacy data compatibility but not used for new banners
     */
    const SOURCE_FILE = 'file'; // Legacy - not used for new banners
    const SOURCE_DATABASE = 'database';
    const SOURCE_URL = 'url';

    protected $fillable = [
        'image_path',
        'image_source',
        'image_data',
        'image_filename',
        'image_mime_type',
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
     * The attributes that should be hidden for serialization.
     * Hide the large image_data from JSON responses by default.
     */
    protected $hidden = [
        'image_data',
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
     * Get the full URL for the banner image based on image source type.
     */
    public function getImageUrlAttribute(): string
    {
        // Default to database storage if image_source is null (for existing records)
        $imageSource = $this->image_source ?? self::SOURCE_DATABASE;
        
        // Handle based on image source type
        switch ($imageSource) {
            case self::SOURCE_DATABASE:
                // For database-stored images, return a route that serves the image
                if (!empty($this->image_data)) {
                    return route('banner.image', ['banner' => $this->id]);
                }
                // If no database data but has image_path, try to use it as fallback
                if (!empty($this->image_path)) {
                    // If it's already a full URL, return as is
                    if (str_starts_with($this->image_path, 'http')) {
                        return $this->image_path;
                    }
                    // Check if file exists before trying to use it
                    $filePath = public_path('storage/' . $this->image_path);
                    if (file_exists($filePath)) {
                        return asset('storage/' . $this->image_path);
                    }
                }
                break;

            case self::SOURCE_URL:
                // For external URLs, return the URL directly
                if (!empty($this->image_path)) {
                    return $this->image_path;
                }
                break;

            case self::SOURCE_FILE:
                // For file storage, return the asset URL only if file exists
                if (!empty($this->image_path)) {
                    // If it's already a full URL (legacy support), return as is
                    if (str_starts_with($this->image_path, 'http')) {
                        return $this->image_path;
                    }
                    // Check if file exists before trying to use it
                    $filePath = public_path('storage/' . $this->image_path);
                    if (file_exists($filePath)) {
                        return asset('storage/' . $this->image_path);
                    }
                }
                break;
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
        return $this->image_source === self::SOURCE_DATABASE && !empty($this->image_data);
    }

    /**
     * Check if image is from external URL.
     */
    public function isImageFromUrl(): bool
    {
        return $this->image_source === self::SOURCE_URL && !empty($this->image_path);
    }

    /**
     * Check if image is stored as file.
     */
    public function isImageInFile(): bool
    {
        return $this->image_source === self::SOURCE_FILE && !empty($this->image_path);
    }

    /**
     * Get human-readable image source label.
     */
    public function getImageSourceLabelAttribute(): string
    {
        return match($this->image_source) {
            self::SOURCE_DATABASE => __('messages.stored_in_database'),
            self::SOURCE_URL => __('messages.external_url'),
            self::SOURCE_FILE => __('messages.legacy_storage'),
            default => __('messages.unknown'),
        };
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
