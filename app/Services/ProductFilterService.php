<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductFilterService
{
    /**
     * Apply all filters to product query
     *
     * @param Builder $query
     * @param Request $request
     * @param Category|array<int>|null $category Category object or array of category IDs
     * @return Builder
     */
    public function applyFilters(Builder $query, Request $request, Category|array|null $category = null): Builder
    {
        // Apply category filter (supports both Category object and array of IDs)
        if ($category !== null) {
            $query = $this->applyCategoryFilter($query, $category);
        }

        // Apply tag filter (single or multiple)
        if ($request->has('tag') && !empty($request->tag)) {
            $query = $this->applyTagFilter($query, $request->tag);
        }
        
        // Apply multiple tags filter (AND logic)
        if ($request->has('tags') && !empty($request->tags)) {
            $query = $this->applyMultipleTagsFilter($query, $request->tags);
        }

        // Apply strong offers filter
        if ($request->has('strong_offers') && $request->strong_offers) {
            $query = $this->applyStrongOffersFilter($query);
        }

        // Apply stock filter
        if ($request->has('stock')) {
            $query = $this->applyStockFilter($query, $request->stock);
        }

        // Apply brand filter (supports both 'brand' and 'brands' parameters)
        if ($request->has('brands') || $request->has('brand')) {
            $brands = $request->input('brands', $request->input('brand', []));
            $query = $this->applyBrandFilter($query, $brands);
        }

        // Apply price range filter
        if ($request->has('min_price') || $request->has('max_price')) {
            $query = $this->applyPriceFilter($query, $request->min_price, $request->max_price);
        }

        // Apply attribute filters
        if ($request->has('attr')) {
            $query = $this->applyAttributeFilters($query, $request->attr);
        }

        return $query;
    }
    
    /**
     * Apply single tag filter to query
     *
     * @param Builder $query
     * @param string $tagSlug
     * @return Builder
     */
    protected function applyTagFilter(Builder $query, string $tagSlug): Builder
    {
        return $query->whereHas('tags', function ($q) use ($tagSlug) {
            $q->where('slug', $tagSlug)->where('is_active', true);
        });
    }
    
    /**
     * Apply multiple tags filter with AND logic
     *
     * @param Builder $query
     * @param array|string $tags
     * @return Builder
     */
    protected function applyMultipleTagsFilter(Builder $query, $tags): Builder
    {
        $tags = is_array($tags) ? $tags : [$tags];
        $tags = array_filter($tags);
        
        foreach ($tags as $tagSlug) {
            $query->whereHas('tags', function ($q) use ($tagSlug) {
                $q->where('slug', $tagSlug)->where('is_active', true);
            });
        }
        
        return $query;
    }

    /**
     * Apply category filter to query
     * Supports both single Category object and array of category IDs for multi-category filtering
     *
     * @param Builder $query
     * @param Category|array<int> $category Category object or array of category IDs
     * @return Builder
     */
    protected function applyCategoryFilter(Builder $query, Category|array $category): Builder
    {
        if ($category instanceof Category) {
            return $query->where('category_id', $category->id);
        }
        
        // Array of category IDs - use whereIn for multi-category filtering
        return $query->whereIn('category_id', $category);
    }

    /**
     * Apply strong offers filter to query
     *
     * @param Builder $query
     * @return Builder
     */
    protected function applyStrongOffersFilter(Builder $query): Builder
    {
        return $query->strongOffers();
    }

    /**
     * Apply stock filter to query
     *
     * @param Builder $query
     * @param string $stockStatus
     * @return Builder
     */
    protected function applyStockFilter(Builder $query, string $stockStatus): Builder
    {
        if ($stockStatus === 'in') {
            return $query->where('stock_status', 'in_stock');
        } elseif ($stockStatus === 'out') {
            return $query->where('stock_status', 'out_of_stock');
        }

        return $query;
    }

    /**
     * Apply brand filter to query
     *
     * @param Builder $query
     * @param array|string $brands
     * @return Builder
     */
    protected function applyBrandFilter(Builder $query, $brands): Builder
    {
        $brands = is_array($brands) ? $brands : [$brands];
        
        if (!empty($brands)) {
            $query->whereHas('brand', function ($q) use ($brands) {
                $q->whereIn('slug', $brands);
            });
        }

        return $query;
    }

    /**
     * Apply price range filter to query
     *
     * @param Builder $query
     * @param float|null $minPrice
     * @param float|null $maxPrice
     * @return Builder
     */
    protected function applyPriceFilter(Builder $query, ?float $minPrice, ?float $maxPrice): Builder
    {
        if ($minPrice !== null) {
            $query->where('price', '>=', $minPrice);
        }

        if ($maxPrice !== null) {
            $query->where('price', '<=', $maxPrice);
        }

        return $query;
    }

    /**
     * Apply attribute filters to query with AND logic
     *
     * @param Builder $query
     * @param array $attributes
     * @return Builder
     */
    protected function applyAttributeFilters(Builder $query, array $attributes): Builder
    {
        foreach ($attributes as $attributeSlug => $valueSlugs) {
            if (empty($valueSlugs)) {
                continue;
            }

            // Ensure valueSlugs is an array
            $valueSlugs = is_array($valueSlugs) ? $valueSlugs : [$valueSlugs];

            // Apply AND logic: product must have at least one of the selected values for this attribute
            $query->whereHas('attributeValues', function ($q) use ($attributeSlug, $valueSlugs) {
                $q->whereHas('attribute', function ($attrQuery) use ($attributeSlug) {
                    $attrQuery->where('slug', $attributeSlug);
                })->whereIn('slug', $valueSlugs);
            });
        }

        return $query;
    }

    /**
     * Get available filters for a category with counts
     *
     * @param Category|array<int>|null $category Category object or array of category IDs
     * @return array
     */
    public function getAvailableFilters(Category|array|null $category = null): array
    {
        $filters = [];

        // Get categories with product counts
        $filters['categories'] = $this->getCategoryFilters();

        // Get tags with product counts
        $filters['tags'] = $this->getTagFilters($category);

        // Get brands with product counts
        $filters['brands'] = $this->getBrandFilters($category);

        // Get stock options with counts
        $filters['stock'] = $this->getStockFilters($category);

        // Get attribute filters with counts (category-specific)
        // Only available when a single Category object is provided
        if ($category instanceof Category) {
            $filters['attributes'] = $this->getAttributeFilters($category);
        }

        // Get price range
        $filters['price_range'] = $this->getPriceRange($category);

        // Add strong offers flag
        $filters['strong_offers'] = true;

        return $filters;
    }
    
    /**
     * Get tag filters with product counts
     *
     * @param Category|array<int>|null $category Category object or array of category IDs
     * @return array
     */
    protected function getTagFilters(Category|array|null $category = null): array
    {
        $query = \App\Models\Tag::active()->ordered();
        
        return $query->get()->map(function ($tag) use ($category) {
            $productQuery = Product::active()->whereHas('tags', function ($q) use ($tag) {
                $q->where('tags.id', $tag->id);
            });
            
            if ($category !== null) {
                if ($category instanceof Category) {
                    $productQuery->where('category_id', $category->id);
                } else {
                    // Array of category IDs
                    $productQuery->whereIn('category_id', $category);
                }
            }
            
            $count = $productQuery->count();
            
            return [
                'id' => $tag->id,
                'slug' => $tag->slug,
                'name' => $tag->name,
                'name_en' => $tag->name_en,
                'name_ar' => $tag->name_ar,
                'color' => $tag->color,
                'icon' => $tag->icon,
                'count' => $count,
            ];
        })->filter(function ($tag) {
            return $tag['count'] > 0;
        })->values()->all();
    }

    /**
     * Get category filters with product counts
     *
     * @return array
     */
    protected function getCategoryFilters(): array
    {
        $locale = app()->getLocale();
        $categories = Category::active()
            ->whereNull('parent_id') // Only parent categories
            ->orderBy('order')
            ->get();

        return $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'slug' => $category->slug,
                'name' => $category->name,
            ];
        })->all();
    }

    /**
     * Get brand filters with product counts
     *
     * @param Category|array<int>|null $category Category object or array of category IDs
     * @return array
     */
    protected function getBrandFilters(Category|array|null $category = null): array
    {
        $query = Product::active()->with('brand');

        if ($category !== null) {
            if ($category instanceof Category) {
                $query->where('category_id', $category->id);
            } else {
                // Array of category IDs
                $query->whereIn('category_id', $category);
            }
        }

        $brands = $query->get()
            ->groupBy('brand_id')
            ->map(function ($products, $brandId) {
                $brand = $products->first()->brand;
                if (!$brand) {
                    return null;
                }

                return [
                    'id' => $brand->id,
                    'slug' => $brand->slug,
                    'name' => $brand->name,
                    'count' => $products->count(),
                ];
            })
            ->filter()
            ->values()
            ->sortBy('name')
            ->values()
            ->all();

        return $brands;
    }

    /**
     * Get stock filters with product counts
     *
     * @param Category|array<int>|null $category Category object or array of category IDs
     * @return array
     */
    protected function getStockFilters(Category|array|null $category = null): array
    {
        $query = Product::active();

        if ($category !== null) {
            if ($category instanceof Category) {
                $query->where('category_id', $category->id);
            } else {
                // Array of category IDs
                $query->whereIn('category_id', $category);
            }
        }

        $inStockCount = (clone $query)->where('stock_status', 'in_stock')->count();
        $outOfStockCount = (clone $query)->where('stock_status', 'out_of_stock')->count();

        return [
            [
                'value' => 'in',
                'label' => __('messages.in_stock_filter'),
                'count' => $inStockCount,
            ],
            [
                'value' => 'out',
                'label' => __('messages.out_of_stock_filter'),
                'count' => $outOfStockCount,
            ],
        ];
    }

    /**
     * Get attribute filters for category with product counts
     *
     * @param Category $category
     * @return array
     */
    protected function getAttributeFilters(Category $category): array
    {
        $attributeFilters = [];

        // Get attributes assigned to this category
        $attributes = $category->attributes()
            ->where('is_filterable', true)
            ->with(['values' => function ($query) {
                $query->where('is_active', true)->orderBy('order');
            }])
            ->get();

        foreach ($attributes as $attribute) {
            $values = [];
            
            foreach ($attribute->values as $value) {
                // Count products with this attribute value in this category
                $count = Product::active()
                    ->where('category_id', $category->id)
                    ->whereHas('attributeValues', function ($q) use ($value) {
                        $q->where('attribute_value_id', $value->id);
                    })
                    ->count();

                if ($count > 0) {
                    $values[] = [
                        'id' => $value->id,
                        'slug' => $value->slug,
                        'value' => $value->value,
                        'count' => $count,
                    ];
                }
            }

            if (!empty($values)) {
                $attributeFilters[] = [
                    'id' => $attribute->id,
                    'slug' => $attribute->slug,
                    'name' => $attribute->name,
                    'type' => $attribute->type,
                    'unit' => $attribute->unit,
                    'values' => $values,
                ];
            }
        }

        return $attributeFilters;
    }

    /**
     * Get price range for products
     *
     * @param Category|array<int>|null $category Category object or array of category IDs
     * @return array
     */
    protected function getPriceRange(Category|array|null $category = null): array
    {
        $query = Product::active();

        if ($category !== null) {
            if ($category instanceof Category) {
                $query->where('category_id', $category->id);
            } else {
                // Array of category IDs
                $query->whereIn('category_id', $category);
            }
        }

        $minPrice = $query->min('price') ?? 0;
        $maxPrice = $query->max('price') ?? 0;

        // Ensure there's always a valid range for the slider
        // If min equals max, create a range around the value
        if ($minPrice == $maxPrice) {
            $minPrice = max(0, $minPrice - 1);
            $maxPrice = $maxPrice + 1;
        }

        return [
            'min' => (float) $minPrice,
            'max' => (float) $maxPrice,
        ];
    }
}
