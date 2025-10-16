<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_en',
        'name_ar',
        'slug',
        'description_en',
        'description_ar',
        'discount_type',
        'discount_value',
        'min_purchase_amount',
        'max_uses',
        'uses_count',
        'start_date',
        'end_date',
        'is_active',
        'banner_image',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_purchase_amount' => 'decimal:2',
        'max_uses' => 'integer',
        'uses_count' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
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
     * Get the description attribute based on current locale.
     */
    public function getDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"description_$locale"} ?? $this->description_en;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($offer) {
            if (empty($offer->slug)) {
                $offer->slug = Str::slug($offer->name_en);
            }
        });
    }

    /**
     * Get all products associated with this offer.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_offers')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include active offers.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now());
    }

    /**
     * Scope a query to only include upcoming offers.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', Carbon::now());
    }

    /**
     * Scope a query to only include expired offers.
     */
    public function scopeExpired($query)
    {
        return $query->where('end_date', '<', Carbon::now());
    }

    /**
     * Check if the offer is valid.
     */
    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now();
        if ($now->lt($this->start_date) || $now->gt($this->end_date)) {
            return false;
        }

        if ($this->max_uses && $this->uses_count >= $this->max_uses) {
            return false;
        }

        return true;
    }

    /**
     * Calculate discount for a given amount.
     */
    public function calculateDiscount($amount)
    {
        if ($this->min_purchase_amount && $amount < $this->min_purchase_amount) {
            return 0;
        }

        if ($this->discount_type === 'percentage') {
            return ($amount * $this->discount_value) / 100;
        }

        return $this->discount_value;
    }

    /**
     * Increment uses count.
     */
    public function incrementUses()
    {
        $this->increment('uses_count');
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
