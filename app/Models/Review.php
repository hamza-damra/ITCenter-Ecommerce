<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'title',
        'comment',
        'images',
        'is_verified_purchase',
        'is_approved',
        'status',
        'helpful_count',
        'unhelpful_count',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'product_id' => 'integer',
        'rating' => 'integer',
        'is_verified_purchase' => 'boolean',
        'is_approved' => 'boolean',
        'helpful_count' => 'integer',
        'unhelpful_count' => 'integer',
        'images' => 'array',
    ];

    /**
     * Get the product that owns the review.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user that wrote the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }





    /**
     * Scope a query to only include verified purchase reviews.
     */
    public function scopeVerifiedPurchase($query)
    {
        return $query->where('is_verified_purchase', true);
    }

    /**
     * Scope a query to filter by rating.
     */
    public function scopeRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope a query to sort by most helpful.
     */
    public function scopeMostHelpful($query)
    {
        return $query->orderBy('helpful_count', 'desc');
    }

    /**
     * Scope a query to sort by highest rating.
     */
    public function scopeHighestRating($query)
    {
        return $query->orderBy('rating', 'desc');
    }

    /**
     * Scope a query to sort by lowest rating.
     */
    public function scopeLowestRating($query)
    {
        return $query->orderBy('rating', 'asc');
    }

    /**
     * Scope a query to sort by most recent.
     */
    public function scopeMostRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Mark the review as helpful.
     */
    public function markAsHelpful()
    {
        $this->increment('helpful_count');
    }

    /**
     * Mark the review as unhelpful.
     */
    public function markAsUnhelpful()
    {
        $this->increment('unhelpful_count');
    }







    /**
     * Get formatted created date.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }
}
