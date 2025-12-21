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
            // Check if file exists before trying to use it
            $filePath = public_path('storage/' . $this->image_path);
            if (file_exists($filePath)) {
                return asset('storage/' . $this->image_path);
            }
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
