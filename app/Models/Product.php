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
        'slug',
        'sku',
        'short_description_en',
        'short_description_ar',
        'description_en',
        'description_ar',
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
    }

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the brand that owns the product.
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class);
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
    public function updateRating()
    {
        $this->avg_rating = $this->reviews()->avg('rating') ?? 0;
        $this->reviews_count = $this->reviews()->count();
        $this->save();
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
}
