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
     * @param Category|null $category
     * @return Builder
     */
    public function applyFilters(Builder $query, Request $request, ?Category $category = null): Builder
    {
        // Apply category filter
        if ($category) {
            $query = $this->applyCategoryFilter($query, $category);
        }

        // Apply strong offers filter
        if ($request->has('strong_offers') && $request->strong_offers) {
            $query = $this->applyStrongOffersFilter($query);
        }

        // Apply stock filter
        if ($request->has('stock')) {
            $query = $this->applyStockFilter($query, $request->stock);
        }

        // Apply brand filter
        if ($request->has('brand')) {
            $query = $this->applyBrandFilter($query, $request->brand);
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
     * Apply category filter to query
     *
     * @param Builder $query
     * @param Category $category
     * @return Builder
     */
    protected function applyCategoryFilter(Builder $query, Category $category): Builder
    {
        return $query->where('category_id', $category->id);
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
     * @param Category|null $category
     * @return array
     */
    public function getAvailableFilters(?Category $category = null): array
    {
        $filters = [];

        // Get categories with product counts
        $filters['categories'] = $this->getCategoryFilters();

        // Get brands with product counts
        $filters['brands'] = $this->getBrandFilters($category);

        // Get stock options with counts
        $filters['stock'] = $this->getStockFilters($category);

        // Get attribute filters with counts (category-specific)
        if ($category) {
            $filters['attributes'] = $this->getAttributeFilters($category);
        }

        // Get price range
        $filters['price_range'] = $this->getPriceRange($category);

        // Add strong offers flag
        $filters['strong_offers'] = true;

        return $filters;
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
     * @param Category|null $category
     * @return array
     */
    protected function getBrandFilters(?Category $category = null): array
    {
        $query = Product::active()->with('brand');

        if ($category) {
            $query->where('category_id', $category->id);
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
     * @param Category|null $category
     * @return array
     */
    protected function getStockFilters(?Category $category = null): array
    {
        $query = Product::active();

        if ($category) {
            $query->where('category_id', $category->id);
        }

        $inStockCount = (clone $query)->where('stock_status', 'in_stock')->count();
        $outOfStockCount = (clone $query)->where('stock_status', 'out_of_stock')->count();

        return [
            [
                'value' => 'in',
                'label' => 'In Stock',
                'count' => $inStockCount,
            ],
            [
                'value' => 'out',
                'label' => 'Out of Stock',
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
     * @param Category|null $category
     * @return array
     */
    protected function getPriceRange(?Category $category = null): array
    {
        $query = Product::active();

        if ($category) {
            $query->where('category_id', $category->id);
        }

        $minPrice = $query->min('price') ?? 0;
        $maxPrice = $query->max('price') ?? 0;

        return [
            'min' => (float) $minPrice,
            'max' => (float) $maxPrice,
        ];
    }
}
