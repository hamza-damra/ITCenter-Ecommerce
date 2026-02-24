<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Filter;
use App\Models\FilterAssignment;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class FilterResolutionService
{
    /**
     * Cache TTL in seconds (1 hour).
     */
    protected const CACHE_TTL = 3600;

    /**
     * Resolve all applicable filters for a single category,
     * including inherited filters from ancestors.
     *
     * @param Category $category
     * @return Collection<Filter>
     */
    public function getFiltersForCategory(Category $category): Collection
    {
        $cacheKey = "category_filters_{$category->id}";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($category) {
            return $this->resolveFiltersForCategory($category);
        });
    }

    /**
     * Resolve applicable filters for an array of category IDs
     * (e.g. a category + all its descendants). Deduplicates by filter id.
     *
     * @param array<int> $categoryIds
     * @return Collection<Filter>
     */
    public function getFiltersForCategories(array $categoryIds): Collection
    {
        if (empty($categoryIds)) {
            return collect();
        }

        // If single ID, delegate to the simpler method
        if (count($categoryIds) === 1) {
            $category = Category::find($categoryIds[0]);
            return $category ? $this->getFiltersForCategory($category) : collect();
        }

        $cacheKey = 'category_filters_multi_' . md5(implode(',', $categoryIds));

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($categoryIds) {
            $allFilterIds = collect();

            foreach ($categoryIds as $categoryId) {
                $category = Category::find($categoryId);
                if (!$category) continue;

                $filters = $this->resolveFiltersForCategory($category);
                $allFilterIds = $allFilterIds->merge($filters->pluck('id'));
            }

            $uniqueFilterIds = $allFilterIds->unique()->values();

            if ($uniqueFilterIds->isEmpty()) {
                return collect();
            }

            return Filter::active()
                ->whereIn('id', $uniqueFilterIds)
                ->ordered()
                ->with(['activeOptions'])
                ->get();
        });
    }

    /**
     * Get filters with product counts for the frontend sidebar.
     *
     * @param Category|array<int>|null $category
     * @return array
     */
    public function getFiltersWithCounts(Category|array|null $category = null): array
    {
        $categoryIds = $this->normalizeCategoryIds($category);

        // When no category is specified, show ALL active filters that have at least one category assignment
        if (empty($categoryIds)) {
            $filters = $this->getAllAssignedFilters();
        } else {
            $filters = $this->getFiltersForCategories($categoryIds);
        }

        if ($filters->isEmpty()) {
            return [];
        }

        $result = [];

        foreach ($filters as $filter) {
            $filterData = [
                'id'          => $filter->id,
                'slug'        => $filter->slug,
                'title'       => $filter->title,
                'title_en'    => $filter->title_en,
                'title_ar'    => $filter->title_ar,
                'title_he'    => $filter->title_he,
                'description' => $filter->description,
                'type'        => $filter->type,
            ];

            if ($filter->isOptionBased()) {
                $filterData['options'] = $this->getOptionCounts($filter, $categoryIds);
                // Hide filter if no options have products
                if (empty($filterData['options'])) {
                    continue;
                }
            } elseif ($filter->isNumeric()) {
                $filterData['range'] = $this->getNumericRange($filter, $categoryIds);
                // Hide filter if no products have numeric values
                if ($filterData['range']['min'] === null) {
                    continue;
                }
            }

            $result[] = $filterData;
        }

        return $result;
    }

    /**
     * Invalidate cache for a category and its descendants.
     */
    public function invalidateCache(?int $categoryId = null): void
    {
        if ($categoryId) {
            Cache::forget("category_filters_{$categoryId}");

            // Also invalidate descendants
            $category = Category::find($categoryId);
            if ($category) {
                foreach ($category->descendants() as $descendant) {
                    Cache::forget("category_filters_{$descendant->id}");
                }
                // And ancestors (they might have multi-category cache keys)
                foreach ($category->ancestors() as $ancestor) {
                    Cache::forget("category_filters_{$ancestor->id}");
                }
            }
        }

        // Clear all multi-key caches (pattern-based clearing)
        // For simplicity, we use a version tag
        Cache::forget('category_filters_version');
        Cache::forget('all_assigned_filters');
    }

    /**
     * Invalidate all filter caches
     */
    public function invalidateAllCaches(): void
    {
        // Get all category IDs and clear each
        $categoryIds = Category::pluck('id');
        foreach ($categoryIds as $id) {
            Cache::forget("category_filters_{$id}");
        }
        Cache::forget('all_assigned_filters');
    }

    // ── Private helpers ──────────────────────────────────────

    /**
     * Get ALL active filters that have at least one category assignment.
     * Used when showing filters on the main /products page without category filter.
     */
    protected function getAllAssignedFilters(): Collection
    {
        $cacheKey = 'all_assigned_filters';

        return Cache::remember($cacheKey, self::CACHE_TTL, function () {
            // Get all filter IDs that have at least one category assignment
            $assignedFilterIds = FilterAssignment::distinct()->pluck('filter_id');

            if ($assignedFilterIds->isEmpty()) {
                return collect();
            }

            return Filter::active()
                ->whereIn('id', $assignedFilterIds)
                ->ordered()
                ->with(['activeOptions'])
                ->get();
        });
    }

    /**
     * Core resolution logic: direct assignments + inherited from ancestors.
     */
    protected function resolveFiltersForCategory(Category $category): Collection
    {
        $filterIds = collect();

        // 1. Direct assignments on this category
        $directAssignments = FilterAssignment::where('category_id', $category->id)
            ->pluck('filter_id');
        $filterIds = $filterIds->merge($directAssignments);

        // 2. Walk up ancestors, collect filters where inherit_to_children = true
        $ancestors = $category->ancestors();
        foreach ($ancestors as $ancestor) {
            $inheritedFilterIds = FilterAssignment::where('category_id', $ancestor->id)
                ->where('inherit_to_children', true)
                ->pluck('filter_id');
            $filterIds = $filterIds->merge($inheritedFilterIds);
        }

        $uniqueFilterIds = $filterIds->unique()->values();

        if ($uniqueFilterIds->isEmpty()) {
            return collect();
        }

        return Filter::active()
            ->whereIn('id', $uniqueFilterIds)
            ->ordered()
            ->with(['activeOptions'])
            ->get();
    }

    /**
     * Count products per option for a filter within the given category IDs.
     */
    protected function getOptionCounts(Filter $filter, array $categoryIds): array
    {
        $options = $filter->activeOptions;

        if ($options->isEmpty()) {
            return [];
        }

        // Single query to get counts for all options
        $counts = DB::table('product_filter_option')
            ->join('products', 'products.id', '=', 'product_filter_option.product_id')
            ->where('products.is_active', true)
            ->when(!empty($categoryIds), function ($q) use ($categoryIds) {
                $q->whereIn('products.category_id', $categoryIds);
            })
            ->whereIn('product_filter_option.filter_option_id', $options->pluck('id'))
            ->groupBy('product_filter_option.filter_option_id')
            ->select('product_filter_option.filter_option_id', DB::raw('COUNT(DISTINCT product_filter_option.product_id) as count'))
            ->pluck('count', 'filter_option_id');

        $result = [];
        foreach ($options as $option) {
            $count = $counts->get($option->id, 0);
            $result[] = [
                'id'         => $option->id,
                'slug'       => $option->value_slug,
                'label'      => $option->label,
                'label_en'   => $option->label_en,
                'color_code' => $option->color_code,
                'icon'       => $option->icon,
                'count'      => $count,
            ];
        }

        return $result;
    }

    /**
     * Get min/max numeric values for a range filter within given categories.
     */
    protected function getNumericRange(Filter $filter, array $categoryIds): array
    {
        $query = DB::table('product_filter_numeric_values')
            ->join('products', 'products.id', '=', 'product_filter_numeric_values.product_id')
            ->where('products.is_active', true)
            ->where('product_filter_numeric_values.filter_id', $filter->id);

        if (!empty($categoryIds)) {
            $query->whereIn('products.category_id', $categoryIds);
        }

        $result = $query->selectRaw('MIN(numeric_value) as min_val, MAX(numeric_value) as max_val')->first();

        return [
            'min' => $result->min_val !== null ? (float) $result->min_val : null,
            'max' => $result->max_val !== null ? (float) $result->max_val : null,
        ];
    }

    /**
     * Normalize category parameter into array of IDs.
     */
    protected function normalizeCategoryIds(Category|array|null $category): array
    {
        if ($category === null) {
            return [];
        }
        if ($category instanceof Category) {
            return [$category->id];
        }
        return $category;
    }
}
