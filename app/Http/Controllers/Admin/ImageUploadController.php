<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Rules\SecureImage;
use App\Services\ImageUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Example controller demonstrating ImageUploadService usage.
 *
 * This controller provides AJAX endpoints for image uploads used by
 * admin forms (products, categories, brands, banners, etc.).
 *
 * Usage in Blade forms:
 *   - Upload via AJAX, receive the relative path back
 *   - Store the returned path in a hidden input
 *   - Submit the form with the path as a regular string field
 *
 * This decouples file upload from form submission, making forms faster
 * and allowing image preview before saving.
 */
class ImageUploadController extends Controller
{
    public function __construct(
        protected ImageUploadService $imageService
    ) {}

    /**
     * Get the dynamic image settings from site settings.
     */
    protected function getImageSettings(): array
    {
        return [
            'max_size_kb' => (int) SiteSetting::getValue('max_image_size_kb', 5120),
            'max_width' => (int) SiteSetting::getValue('max_image_width', 1920),
            'max_height' => (int) SiteSetting::getValue('max_image_height', 1080),
            'quality' => (int) SiteSetting::getValue('image_quality', 80),
            'convert_to_webp' => (bool) SiteSetting::getValue('convert_to_webp', true),
        ];
    }

    /**
     * Upload a product image.
     *
     * POST /admin/upload/product-image
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadProductImage(Request $request): JsonResponse
    {
        $settings = $this->getImageSettings();

        $request->validate([
            'image' => ['required', new SecureImage($settings['max_size_kb'])],
        ]);

        try {
            $path = $this->imageService->upload(
                $request->file('image'),
                'products',
                [
                    'optimize' => true,
                    'max_width' => $settings['max_width'],
                    'max_height' => $settings['max_height'],
                    'quality' => $settings['quality'],
                    'convert_to_webp' => $settings['convert_to_webp'],
                ]
            );

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => $this->imageService->url($path),
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.image_upload_failed'),
            ], 500);
        }
    }

    /**
     * Upload a category image.
     *
     * POST /admin/upload/category-image
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadCategoryImage(Request $request): JsonResponse
    {
        $settings = $this->getImageSettings();

        $request->validate([
            'image' => ['required', new SecureImage($settings['max_size_kb'])],
        ]);

        try {
            $path = $this->imageService->upload(
                $request->file('image'),
                'categories',
                [
                    'optimize' => true,
                    'max_width' => 800,
                    'max_height' => 800,
                    'quality' => $settings['quality'],
                    'organize_by_date' => false,
                ]
            );

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => $this->imageService->url($path),
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.image_upload_failed'),
            ], 500);
        }
    }

    /**
     * Upload a brand logo.
     *
     * POST /admin/upload/brand-logo
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadBrandLogo(Request $request): JsonResponse
    {
        $settings = $this->getImageSettings();

        $request->validate([
            'image' => ['required', new SecureImage($settings['max_size_kb'])],
        ]);

        try {
            $path = $this->imageService->upload(
                $request->file('image'),
                'brands',
                [
                    'optimize' => true,
                    'max_width' => 500,
                    'max_height' => 500,
                    'quality' => $settings['quality'],
                    'organize_by_date' => false,
                ]
            );

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => $this->imageService->url($path),
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.image_upload_failed'),
            ], 500);
        }
    }

    /**
     * Upload a banner image.
     *
     * POST /admin/upload/banner-image
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadBannerImage(Request $request): JsonResponse
    {
        $settings = $this->getImageSettings();

        $request->validate([
            'image' => ['required', new SecureImage($settings['max_size_kb'])],
        ]);

        try {
            $path = $this->imageService->upload(
                $request->file('image'),
                'banners',
                [
                    'optimize' => true,
                    'max_width' => $settings['max_width'],
                    'max_height' => 600,
                    'quality' => $settings['quality'],
                    'convert_to_webp' => $settings['convert_to_webp'],
                ]
            );

            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => $this->imageService->url($path),
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.image_upload_failed'),
            ], 500);
        }
    }

    /**
     * Delete an uploaded image by path.
     *
     * DELETE /admin/upload/delete-image
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteImage(Request $request): JsonResponse
    {
        $request->validate([
            'path' => 'required|string|max:500',
        ]);

        $path = $request->input('path');

        // Security: prevent deleting files outside expected directories
        $allowedPrefixes = ['products/', 'categories/', 'brands/', 'banners/'];
        $isAllowed = false;
        foreach ($allowedPrefixes as $prefix) {
            if (str_starts_with($path, $prefix)) {
                $isAllowed = true;
                break;
            }
        }

        if (!$isAllowed) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid image path.',
            ], 403);
        }

        $deleted = $this->imageService->delete($path);

        return response()->json([
            'success' => $deleted,
            'message' => $deleted ? 'Image deleted.' : 'Image not found.',
        ]);
    }

    /**
     * Check and ensure the storage symlink exists.
     * Useful as a health-check endpoint for deployment.
     *
     * GET /admin/upload/check-storage
     *
     * @return JsonResponse
     */
    public function checkStorage(): JsonResponse
    {
        $linkExists = is_link(public_path('storage')) || is_dir(public_path('storage'));
        $diskWritable = is_writable(storage_path('app/public'));

        return response()->json([
            'storage_link_exists' => $linkExists,
            'disk_writable' => $diskWritable,
            'storage_path' => storage_path('app/public'),
            'public_path' => public_path('storage'),
        ]);
    }
}
