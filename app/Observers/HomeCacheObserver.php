<?php

namespace App\Observers;

use App\Services\HomeCacheService;

class HomeCacheObserver
{
    /**
     * Handle the "created" event.
     */
    public function created($model): void
    {
        HomeCacheService::clearAll();
    }

    /**
     * Handle the "updated" event.
     */
    public function updated($model): void
    {
        HomeCacheService::clearAll();
    }

    /**
     * Handle the "deleted" event.
     */
    public function deleted($model): void
    {
        HomeCacheService::clearAll();
    }

    /**
     * Handle the "restored" event.
     */
    public function restored($model): void
    {
        HomeCacheService::clearAll();
    }
}
