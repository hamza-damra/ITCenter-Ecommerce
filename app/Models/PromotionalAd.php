<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionalAd extends Model
{
    use HasFactory;

    protected $fillable = [
        'image_path',
        'position',
        'link',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the full URL for the promotional ad image.
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
