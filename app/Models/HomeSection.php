<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeSection extends Model
{
    use HasFactory;

    /**
     * Section type constants
     */
    const TYPE_HERO_BANNER = 'hero_banner';
    const TYPE_CATEGORY_CAROUSEL = 'category_carousel';
    const TYPE_FEATURED_PRODUCTS = 'featured_products';
    const TYPE_SPECIAL_OFFERS_BANNER = 'special_offers_banner';
    const TYPE_SPECIAL_DISCOUNTS = 'special_discounts';
    const TYPE_NEW_ARRIVALS = 'new_arrivals';
    const TYPE_BESTSELLERS = 'bestsellers';
    const TYPE_GIFT_IDEAS_BANNER = 'gift_ideas_banner';
    const TYPE_ON_SALE = 'on_sale';
    const TYPE_CUSTOM_CONTENT = 'custom_content';
    const TYPE_CUSTOM_PRODUCT_SECTION = 'custom_product_section';

    /**
     * All available section types
     */
    const TYPES = [
        self::TYPE_HERO_BANNER,
        self::TYPE_CATEGORY_CAROUSEL,
        self::TYPE_FEATURED_PRODUCTS,
        self::TYPE_SPECIAL_OFFERS_BANNER,
        self::TYPE_SPECIAL_DISCOUNTS,
        self::TYPE_NEW_ARRIVALS,
        self::TYPE_BESTSELLERS,
        self::TYPE_GIFT_IDEAS_BANNER,
        self::TYPE_ON_SALE,
        self::TYPE_CUSTOM_CONTENT,
        self::TYPE_CUSTOM_PRODUCT_SECTION,
    ];

    protected $fillable = [
        'type',
        'title_en',
        'title_ar',
        'title_he',
        'subtitle_en',
        'subtitle_ar',
        'subtitle_he',
        'display_order',
        'is_active',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
        'settings' => 'array',
    ];

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
     * Get a specific setting value with default.
     */
    public function getSetting(string $key, $default = null)
    {
        return data_get($this->settings, $key, $default);
    }

    /**
     * Get the Blade partial view name for this section type.
     */
    public function getViewName(): string
    {
        $map = [
            self::TYPE_HERO_BANNER => 'home.sections.hero-banner',
            self::TYPE_CATEGORY_CAROUSEL => 'home.sections.category-carousel',
            self::TYPE_FEATURED_PRODUCTS => 'home.sections.featured-products',
            self::TYPE_SPECIAL_OFFERS_BANNER => 'home.sections.special-offers-banner',
            self::TYPE_SPECIAL_DISCOUNTS => 'home.sections.special-discounts',
            self::TYPE_NEW_ARRIVALS => 'home.sections.new-arrivals',
            self::TYPE_BESTSELLERS => 'home.sections.bestsellers',
            self::TYPE_GIFT_IDEAS_BANNER => 'home.sections.gift-ideas-banner',
            self::TYPE_ON_SALE => 'home.sections.on-sale',
            self::TYPE_CUSTOM_CONTENT => 'home.sections.custom-content',
            self::TYPE_CUSTOM_PRODUCT_SECTION => 'home.sections.custom-product-section',
        ];

        return $map[$this->type] ?? 'home.sections.custom-content';
    }

    /**
     * Get human-readable type label translation key.
     */
    public static function getTypeLabel(string $type): string
    {
        $labels = [
            self::TYPE_HERO_BANNER => 'messages.section_type_hero_banner',
            self::TYPE_CATEGORY_CAROUSEL => 'messages.section_type_category_carousel',
            self::TYPE_FEATURED_PRODUCTS => 'messages.section_type_featured_products',
            self::TYPE_SPECIAL_OFFERS_BANNER => 'messages.section_type_special_offers_banner',
            self::TYPE_SPECIAL_DISCOUNTS => 'messages.section_type_special_discounts',
            self::TYPE_NEW_ARRIVALS => 'messages.section_type_new_arrivals',
            self::TYPE_BESTSELLERS => 'messages.section_type_bestsellers',
            self::TYPE_GIFT_IDEAS_BANNER => 'messages.section_type_gift_ideas_banner',
            self::TYPE_ON_SALE => 'messages.section_type_on_sale',
            self::TYPE_CUSTOM_CONTENT => 'messages.section_type_custom_content',
            self::TYPE_CUSTOM_PRODUCT_SECTION => 'messages.section_type_custom_product_section',
        ];

        return __($labels[$type] ?? 'messages.unknown');
    }

    /**
     * Get icon class for a section type.
     */
    public static function getTypeIcon(string $type): string
    {
        $icons = [
            self::TYPE_HERO_BANNER => 'fas fa-images',
            self::TYPE_CATEGORY_CAROUSEL => 'fas fa-th-large',
            self::TYPE_FEATURED_PRODUCTS => 'fas fa-star',
            self::TYPE_SPECIAL_OFFERS_BANNER => 'fas fa-bullhorn',
            self::TYPE_SPECIAL_DISCOUNTS => 'fas fa-percent',
            self::TYPE_NEW_ARRIVALS => 'fas fa-clock',
            self::TYPE_BESTSELLERS => 'fas fa-fire',
            self::TYPE_GIFT_IDEAS_BANNER => 'fas fa-gift',
            self::TYPE_ON_SALE => 'fas fa-tags',
            self::TYPE_CUSTOM_CONTENT => 'fas fa-puzzle-piece',
            self::TYPE_CUSTOM_PRODUCT_SECTION => 'fas fa-th-list',
        ];

        return $icons[$type] ?? 'fas fa-puzzle-piece';
    }

    /**
     * Scope a query to only include active sections.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order by display_order.
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order', 'asc');
    }

    /**
     * Scope a query to only include custom product sections.
     */
    public function scopeCustomProductSections($query)
    {
        return $query->where('type', self::TYPE_CUSTOM_PRODUCT_SECTION);
    }

    /**
     * Get the products assigned to this section.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'home_section_product')
            ->withPivot('display_order')
            ->withTimestamps()
            ->orderBy('home_section_product.display_order');
    }
}
