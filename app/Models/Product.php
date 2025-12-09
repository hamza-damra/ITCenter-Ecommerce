<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name_en',
        'name_ar',
        'name_he',
        'slug',
        'sku',
        'short_description_en',
        'short_description_ar',
        'short_description_he',
        'description_en',
        'description_ar',
        'description_he',
        'price',
        'sale_price',
        'cost_price',
        'stock_quantity',
        'min_stock_quantity',
        'main_image',
        'category_id',
        'brand_id',
        'is_active',
        'is_featured',
        'is_new',
        'is_bestseller',
        'is_special_offer',
        'is_strong_offer',
        'discount_percentage',
        'track_stock',
        'stock_status',
        'weight',
        'length',
        'width',
        'height',
        'warranty',
        'views_count',
        'sales_count',
        'avg_rating',
        'reviews_count',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'search_keywords',
        'specifications',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'min_stock_quantity' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_bestseller' => 'boolean',
        'is_strong_offer' => 'boolean',
        'discount_percentage' => 'decimal:2',
        'track_stock' => 'boolean',
        'weight' => 'decimal:2',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
        'views_count' => 'integer',
        'sales_count' => 'integer',
        'avg_rating' => 'decimal:1',
        'reviews_count' => 'integer',
        'specifications' => 'array',
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
     * Get the main image with fallback to default.
     */
    public function getMainImageAttribute($value)
    {
        if (empty($value)) {
            return asset('images/products/default.png');
        }
        
        if (str_starts_with($value, 'http')) {
            // If it's an external URL, return it as is
            return $value;
        }
        
        // If it's a local path, check if file exists
        $imagePath = public_path($value);
        if (file_exists($imagePath)) {
            return asset($value);
        }
        
        // Fallback to default image
        return asset('images/products/default.png');
    }

    /**
     * Get the short description attribute based on current locale.
     */
    public function getShortDescriptionAttribute()
    {
        $locale = app()->getLocale();
        return $this->{"short_description_$locale"} ?? $this->short_description_en;
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

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name_en);
            }
            if (empty($product->sku)) {
                $product->sku = 'SKU-' . strtoupper(Str::random(10));
            }
        });

        // CRITICAL FIX: Auto-update stock status when stock quantity changes
        static::updating(function ($product) {
            if (!$product->isDirty('stock_quantity')) {
                return;
            }

            if ($product->track_stock) {
                $oldStatus = $product->getOriginal('stock_status');
                
                if ($product->stock_quantity <= 0 && $oldStatus !== 'out_of_stock') {
                    $product->stock_status = 'out_of_stock';
                } elseif ($product->stock_quantity > 0 && $oldStatus === 'out_of_stock') {
                    $product->stock_status = 'in_stock';
                }
            }
        });
    }

    /**
     * Get the category that owns the product.
     * Explicitly excludes soft-deleted categories.
     */
    public function category()
    {
        return $this->belongsTo(Category::class)->withoutTrashed();
    }

    /**
     * Get the brand that owns the product.
     * Explicitly excludes soft-deleted brands.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class)->withoutTrashed();
    }

    /**
     * Get all images for the product.
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Get all reviews for the product.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get all attributes for the product.
     */
    public function attributes()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_attributes')
            ->withPivot('price_adjustment', 'stock_quantity')
            ->withTimestamps();
    }

    /**
     * Get all attribute values for the product (for filtering).
     */
    public function attributeValues()
    {
        return $this->belongsToMany(AttributeValue::class, 'product_attribute_values')
            ->withTimestamps();
    }

    /**
     * Get the offers associated with the product.
     */
    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'product_offers')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include featured products.
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include new products.
     */
    public function scopeNew($query)
    {
        return $query->where('is_new', true);
    }

    /**
     * Scope a query to only include bestseller products.
     */
    public function scopeBestseller($query)
    {
        return $query->where('is_bestseller', true);
    }

    /**
     * Scope a query to only include strong offers products.
     */
    public function scopeStrongOffers($query)
    {
        return $query->where('is_strong_offer', true);
    }

    /**
     * Scope a query to only include products in stock.
     */
    public function scopeInStock($query)
    {
        return $query->where('stock_status', 'in_stock');
    }

    /**
     * Scope a query to filter by price range.
     */
    public function scopePriceRange($query, $min, $max)
    {
        return $query->whereBetween('price', [$min, $max]);
    }

    /**
     * Get the final price (considering sale price).
     */
    public function getFinalPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    /**
     * Get the discount percentage.
     */
    public function getDiscountPercentageAttribute()
    {
        if ($this->sale_price && $this->price > 0) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }

    /**
     * Check if product is on sale.
     */
    public function getIsOnSaleAttribute()
    {
        return $this->sale_price && $this->sale_price < $this->price;
    }

    /**
     * Check if product is low on stock.
     */
    public function getIsLowStockAttribute()
    {
        return $this->track_stock && $this->stock_quantity <= $this->min_stock_quantity;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Increment product views.
     */
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    /**
     * Increment product sales.
     */
    public function incrementSales($quantity = 1)
    {
        $this->increment('sales_count', $quantity);
        if ($this->track_stock) {
            $this->decrement('stock_quantity', $quantity);
            $this->updateStockStatus();
        }
    }

    /**
     * Update stock status based on quantity.
     */
    public function updateStockStatus()
    {
        if (!$this->track_stock) {
            $this->stock_status = 'in_stock';
        } elseif ($this->stock_quantity <= 0) {
            $this->stock_status = 'out_of_stock';
        } else {
            $this->stock_status = 'in_stock';
        }
        $this->save();
    }

    /**
     * Update average rating.
     */
    public function updateRating(): void
    {
        /** @var float|string|null $avg */
        $avg = $this->reviews()->avg('rating');
        $count = $this->reviews()->count();

        // Convert to string format that matches decimal(2,1) column type
        $avgRating = $avg !== null ? number_format((float)$avg, 1, '.', '') : '0.0';

        $this->attributes['avg_rating'] = $avgRating;
        $this->reviews_count = $count;
        $this->save();
    }

    /**
     * Get rating distribution for the product.
     */
    public function getRatingDistributionAttribute()
    {
        $distribution = [];
        $totalReviews = $this->reviews_count;

        for ($i = 5; $i >= 1; $i--) {
            $count = $this->reviews()
                ->where('rating', $i)
                ->count();

            $percentage = $totalReviews > 0 ? round(($count / $totalReviews) * 100, 1) : 0;

            $distribution[$i] = [
                'count' => $count,
                'percentage' => $percentage,
            ];
        }

        return $distribution;
    }

    /**
     * Get approved reviews for the product.
     */
    public function getApprovedReviewsAttribute()
    {
        return $this->reviews()->get();
    }

    /**
     * Check if user has reviewed this product.
     */
    public function hasUserReviewed($userId)
    {
        return $this->reviews()
            ->where('user_id', $userId)
            ->exists();
    }

    /**
     * Get users who favorited this product.
     */
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    /**
     * Get all favorites for this product.
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * Get all tags for this product.
     */
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag')
            ->withTimestamps();
    }

    /**
     * Scope a query to filter by tag.
     */
    public function scopeWithTag($query, $tagSlug)
    {
        return $query->whereHas('tags', function ($q) use ($tagSlug) {
            $q->where('slug', $tagSlug)->where('is_active', true);
        });
    }
}
