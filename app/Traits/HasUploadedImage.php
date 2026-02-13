<?php

namespace App\Traits;

use App\Services\ImageUploadService;
use Illuminate\Support\Facades\Log;

/**
 * Trait for Eloquent models that have uploaded images stored via ImageUploadService.
 *
 * Usage:
 *   use HasUploadedImage;
 *
 *   // Override in your model to specify which columns hold image paths
 *   protected function imageColumns(): array
 *   {
 *       return ['main_image']; // or ['image'], ['logo'], etc.
 *   }
 *
 * The trait automatically deletes associated image files from storage when:
 *   - A model is force-deleted (for SoftDeletes models)
 *   - A model is deleted (for non-SoftDeletes models)
 *   - An image column is updated with a new value (old file is cleaned up)
 */
trait HasUploadedImage
{
    /**
     * Boot the trait.
     */
    public static function bootHasUploadedImage(): void
    {
        // Clean up images when model is deleted
        // For SoftDeletes models, this fires on soft delete.
        // Override with forceDeleted event if you only want cleanup on force delete.
        static::deleting(function ($model) {
            // If model uses SoftDeletes and this is a soft delete, skip cleanup
            if (method_exists($model, 'isForceDeleting') && !$model->isForceDeleting()) {
                return;
            }

            $model->deleteUploadedImages();
        });

        // Clean up old image when a column value changes
        static::updating(function ($model) {
            $model->cleanupReplacedImages();
        });
    }

    /**
     * Get the image columns that hold uploaded file paths.
     * Override this in your model to specify which columns to manage.
     *
     * @return array
     */
    protected function imageColumns(): array
    {
        return [];
    }

    /**
     * Delete all uploaded images associated with this model.
     */
    public function deleteUploadedImages(): void
    {
        $service = app(ImageUploadService::class);

        foreach ($this->imageColumns() as $column) {
            $path = $this->getRawOriginal($column) ?? $this->getOriginal($column);

            if (!empty($path) && !$this->isExternalUrl($path)) {
                $service->delete($path);
            }
        }
    }

    /**
     * When an image column is updated, delete the old file.
     */
    protected function cleanupReplacedImages(): void
    {
        $service = app(ImageUploadService::class);

        foreach ($this->imageColumns() as $column) {
            if (!$this->isDirty($column)) {
                continue;
            }

            $oldPath = $this->getOriginal($column);
            $newPath = $this->getAttribute($column);

            // Only clean up if old path exists, is different, and is a local file
            if (!empty($oldPath) && $oldPath !== $newPath && !$this->isExternalUrl($oldPath)) {
                $service->delete($oldPath);
            }
        }
    }

    /**
     * Get the public URL for an image column.
     *
     * @param string $column
     * @param string|null $fallback Fallback URL if image doesn't exist
     * @return string
     */
    public function getImageUrl(string $column, ?string $fallback = null): string
    {
        $path = $this->getRawOriginal($column) ?? $this->getOriginal($column);

        if (empty($path)) {
            return $fallback ?? asset('images/products/default.png');
        }

        if ($this->isExternalUrl($path)) {
            return $path;
        }

        return app(ImageUploadService::class)->url($path, $fallback);
    }

    /**
     * Check if a path is an external URL (not a local storage path).
     *
     * @param string $path
     * @return bool
     */
    protected function isExternalUrl(string $path): bool
    {
        return str_starts_with($path, 'http://') || str_starts_with($path, 'https://');
    }
}
