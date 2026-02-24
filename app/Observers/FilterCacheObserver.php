<?php

namespace App\Observers;

use App\Models\Filter;
use App\Models\FilterOption;
use App\Models\FilterAssignment;
use App\Services\FilterResolutionService;

/**
 * Observer to invalidate filter-related cache when filters, options, or assignments change.
 */
class FilterCacheObserver
{
    protected FilterResolutionService $filterResolution;

    public function __construct(FilterResolutionService $filterResolution)
    {
        $this->filterResolution = $filterResolution;
    }

    /**
     * Handle the Filter "created" event.
     */
    public function created(Filter|FilterOption|FilterAssignment $model): void
    {
        $this->invalidate($model);
    }

    /**
     * Handle the model "updated" event.
     */
    public function updated(Filter|FilterOption|FilterAssignment $model): void
    {
        $this->invalidate($model);
    }

    /**
     * Handle the model "deleted" event.
     */
    public function deleted(Filter|FilterOption|FilterAssignment $model): void
    {
        $this->invalidate($model);
    }

    /**
     * Determine category ID(s) to invalidate based on model type.
     */
    protected function invalidate(Filter|FilterOption|FilterAssignment $model): void
    {
        if ($model instanceof FilterAssignment) {
            // Assignment changed — invalidate the specific category
            $this->filterResolution->invalidateCache($model->category_id);
        } elseif ($model instanceof FilterOption) {
            // Option changed — invalidate all categories that use this filter's assignments
            $filterId = $model->filter_id;
            $categoryIds = FilterAssignment::where('filter_id', $filterId)->pluck('category_id');
            foreach ($categoryIds as $catId) {
                $this->filterResolution->invalidateCache($catId);
            }
        } elseif ($model instanceof Filter) {
            // Filter changed — invalidate all assigned categories
            $categoryIds = FilterAssignment::where('filter_id', $model->id)->pluck('category_id');
            foreach ($categoryIds as $catId) {
                $this->filterResolution->invalidateCache($catId);
            }
        }
    }
}
