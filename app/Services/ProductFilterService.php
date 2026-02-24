<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Filter;
use App\Models\FilterSectionSetting;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // Apply categories filter from request (supports both 'category' and 'categories[]' parameters)
        $categoryFilters = [];
        if ($request->has('categories') && !empty($request->categories)) {
            $categoryFilters = is_array($request->categories) ? $request->categories : [$request->categories];
        }
        if ($request->has('category') && !empty($request->category) && empty($categoryFilters)) {
            $categoryFilters = [$request->category];
        }
        if (!empty($categoryFilters)) {
            $categoryFilters = array_filter($categoryFilters);
            if (!empty($categoryFilters)) {
                $query->whereHas('category', function ($q) use ($categoryFilters) {
                    $q->whereIn('slug', $categoryFilters);
                });
            }
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
        if ($request->has('stock') && !empty($request->stock)) {
            $query = $this->applyStockFilter($query, $request->stock);
        }

        // Apply brand filter (supports both 'brand' and 'brands' parameters)
        if ($request->has('brands') || $request->has('brand')) {
            $brands = $request->input('brands', $request->input('brand', []));
            if (!empty($brands)) {
                $query = $this->applyBrandFilter($query, $brands);
            }
        }

        // Apply price range filter
        if ($request->has('min_price') || $request->has('max_price')) {
            $query = $this->applyPriceFilter($query, $request->min_price, $request->max_price);
        }

        // Apply dynamic filters (new system: f[slug][]=value)
        if ($request->has('f')) {
            $query = $this->applyDynamicFilters($query, $request->input('f', []));
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
     * Apply dynamic filters from the new filter system.
     * Expects format: f[filter_slug][] = option_value_slug (checkbox/radio/boolean)
     *                  f[filter_slug][min] = X, f[filter_slug][max] = Y (range/min_max)
     *
     * AND logic across different filters, OR logic within the same filter.
     *
     * @param Builder $query
     * @param array $filterParams
     * @return Builder
     */
    protected function applyDynamicFilters(Builder $query, array $filterParams): Builder
    {
        if (empty($filterParams)) {
            return $query;
        }

        // Preload all referenced filter slugs in one query
        $filterSlugs = array_keys($filterParams);
        $filters = Filter::active()
            ->whereIn('slug', $filterSlugs)
            ->with('activeOptions')
            ->get()
            ->keyBy('slug');

        foreach ($filterParams as $filterSlug => $values) {
            $filter = $filters->get($filterSlug);
            if (!$filter) {
                continue; // Skip invalid/unknown filter slugs
            }

            if ($filter->isOptionBased()) {
                // checkbox / radio / boolean
                $optionSlugs = is_array($values) ? array_values(array_filter($values)) : [$values];
                if (empty($optionSlugs)) {
                    continue;
                }

                // Validate option slugs against active options
                $validSlugs = $filter->activeOptions->pluck('value_slug')->toArray();
                $optionSlugs = array_intersect($optionSlugs, $validSlugs);
                if (empty($optionSlugs)) {
                    continue;
                }

                // Get option IDs for these slugs
                $optionIds = $filter->activeOptions
                    ->whereIn('value_slug', $optionSlugs)
                    ->pluck('id')
                    ->toArray();

                // OR within same filter: product must have at least one of the selected options
                $query->whereHas('filterOptions', function ($q) use ($optionIds) {
                    $q->whereIn('filter_options.id', $optionIds);
                });
            } elseif ($filter->isNumeric()) {
                // range / min_max — expects ['min' => X, 'max' => Y]
                if (!is_array($values)) {
                    continue;
                }
                $min = isset($values['min']) && is_numeric($values['min']) ? (float) $values['min'] : null;
                $max = isset($values['max']) && is_numeric($values['max']) ? (float) $values['max'] : null;

                if ($min === null && $max === null) {
                    continue;
                }

                $filterId = $filter->id;
                $query->whereHas('filterNumericValues', function ($q) use ($filterId, $min, $max) {
                    $q->where('filter_id', $filterId);
                    if ($min !== null) {
                        $q->where('numeric_value', '>=', $min);
                    }
                    if ($max !== null) {
                        $q->where('numeric_value', '<=', $max);
                    }
                });
            }
        }

        return $query;
    }

    /**
     * Get available filters for a category with counts
     *
     * @param Category|array<int>|null $category Category object or array of category IDs (for filter counts)
     * @param Category|null $currentCategory The actual current category for tree highlighting
     * @return array
     */
    public function getAvailableFilters(Category|array|null $category = null, ?Category $currentCategory = null): array
    {
        $filters = [];

        // Get hierarchical category tree for sidebar navigation
        // Use the specific Category object for highlighting if provided, otherwise fall back to $category
        $treeContext = $currentCategory ?? $category;
        $filters['category_tree'] = $this->getHierarchicalCategories($treeContext);

        // Get tags with product counts
        $filters['tags'] = $this->getTagFilters($category);

        // Get brands with product counts
        $filters['brands'] = $this->getBrandFilters($category);

        // Get stock options with counts
        $filters['stock'] = $this->getStockFilters($category);

        // Get dynamic filters with counts (unified system)
        $filterResolution = app(FilterResolutionService::class);
        $filters['dynamic_filters'] = $filterResolution->getFiltersWithCounts($category);

        // Get price range from actual DB values
        $filters['price_range'] = $this->getPriceRange($category);

        // Add strong offers flag (respects admin setting)
        $filters['strong_offers'] = FilterSectionSetting::isEnabled('strong_offers');

        // Section display settings (admin-controllable visibility & order)
        $filters['section_settings'] = $this->getSectionSettings();

        return $filters;
    }

    /**
     * Get hierarchical category tree for sidebar navigation.
     * Returns parent categories with nested children/grandchildren,
     * each with product counts (including descendant products).
     *
     * @param Category|array<int>|null $currentCategory Current category context for highlighting
     * @return array
     */
    public function getHierarchicalCategories(Category|array|null $currentCategory = null): array
    {
        // Resolve current category IDs for highlighting
        $currentCategoryIds = [];
        if ($currentCategory instanceof Category) {
            $currentCategoryIds = [$currentCategory->id];
            // Also include ancestor IDs so parent gets highlighted/expanded
            foreach ($currentCategory->ancestors() as $ancestor) {
                $currentCategoryIds[] = $ancestor->id;
            }
        } elseif (is_array($currentCategory)) {
            $currentCategoryIds = $currentCategory;
        }

        // Load all active categories with counts in a single query
        $allCategories = Category::active()
            ->withCount(['products' => function ($query) {
                $query->active();
            }])
            ->orderBy('position')
            ->orderBy('order')
            ->orderBy('name_en')
            ->get();

        // Build lookup maps
        $byParent = $allCategories->groupBy('parent_id');
        $parents = $byParent->get('', collect())->merge($byParent->get(null, collect()));

        $tree = [];
        foreach ($parents as $parent) {
            $children = $byParent->get($parent->id, collect());
            $childrenData = [];
            $parentTotalCount = $parent->products_count;

            foreach ($children as $child) {
                $grandChildren = $byParent->get($child->id, collect());
                $grandChildrenData = [];
                $childTotalCount = $child->products_count;

                foreach ($grandChildren as $grandChild) {
                    $childTotalCount += $grandChild->products_count;
                    $parentTotalCount += $grandChild->products_count;
                    $grandChildrenData[] = [
                        'id' => $grandChild->id,
                        'slug' => $grandChild->slug,
                        'name' => $grandChild->name,
                        'icon' => $grandChild->icon,
                        'url' => $grandChild->url,
                        'count' => $grandChild->products_count,
                        'is_current' => in_array($grandChild->id, $currentCategoryIds),
                    ];
                }

                $parentTotalCount += $child->products_count;
                $childrenData[] = [
                    'id' => $child->id,
                    'slug' => $child->slug,
                    'name' => $child->name,
                    'icon' => $child->icon,
                    'url' => $child->url,
                    'count' => $child->products_count,
                    'total_count' => $childTotalCount,
                    'is_current' => in_array($child->id, $currentCategoryIds),
                    'children' => $grandChildrenData,
                ];
            }

            $tree[] = [
                'id' => $parent->id,
                'slug' => $parent->slug,
                'name' => $parent->name,
                'icon' => $parent->icon,
                'url' => $parent->url,
                'count' => $parent->products_count,
                'total_count' => $parentTotalCount,
                'is_current' => in_array($parent->id, $currentCategoryIds),
                'children' => $childrenData,
            ];
        }

        return $tree;
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
     * Sorted by: categories with products first (by count desc), then categories without products (alphabetically)
     *
     * @return array
     */
    protected function getCategoryFilters(): array
    {
        $locale = app()->getLocale();

        // Get ALL active categories (both parent and child) with product counts
        $categories = Category::active()
            ->withCount(['products' => function ($query) {
                $query->active();
            }])
            ->get();

        $categoriesWithCounts = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'slug' => $category->slug,
                'name' => $category->name,
                'parent_id' => $category->parent_id,
                'count' => $category->products_count,
            ];
        });

        // Sort: categories with products first (by count desc), then categories without products (alphabetically)
        return $categoriesWithCounts->sortBy([
            ['count', 'desc'],  // First by count descending (categories with products first)
            ['name', 'asc'],    // Then alphabetically
        ])->values()->all();
    }

    /**
     * Get brand filters with product counts
     * Returns ALL active brands with their product counts
     * Sorted by: brands with products first (by count desc), then brands without products (alphabetically)
     *
     * @param Category|array<int>|null $category Category object or array of category IDs
     * @return array
     */
    protected function getBrandFilters(Category|array|null $category = null): array
    {
        $locale = app()->getLocale();

        // Get ALL active brands
        $brands = \App\Models\Brand::active()
            ->orderBy('name_' . $locale)
            ->get();

        $brandsWithCounts = $brands->map(function ($brand) use ($category) {
            // Count products for this brand
            $productQuery = Product::active()->where('brand_id', $brand->id);

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
                'id' => $brand->id,
                'slug' => $brand->slug,
                'name' => $brand->name,
                'count' => $count,
            ];
        });

        // Sort: brands with products first (by count desc), then brands without products (alphabetically)
        return $brandsWithCounts->sortBy([
            ['count', 'desc'],  // First by count descending (brands with products first)
            ['name', 'asc'],    // Then alphabetically
        ])->values()->all();
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
     * Get section settings for filter sidebar (admin-controllable).
     *
     * @return array [['key' => 'status', 'enabled' => true, 'sort_order' => 0], ...]
     */
    protected function getSectionSettings(): array
    {
        $raw = FilterSectionSetting::getOrderedSettings();
        $result = [];
        foreach ($raw as $key => $data) {
            $result[] = [
                'key' => $key,
                'enabled' => (bool) ($data['is_enabled'] ?? true),
                'sort_order' => (int) ($data['sort_order'] ?? 0),
            ];
        }
        usort($result, fn ($a, $b) => $a['sort_order'] <=> $b['sort_order']);
        return $result;
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
