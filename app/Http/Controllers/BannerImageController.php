<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class BannerImageController extends Controller
{
    /**
     * Serve banner image stored in database.
     * Uses caching for better performance.
     */
    public function show(Banner $banner)
    {
        // Check if the banner has database-stored image
        if (!$banner->isImageInDatabase()) {
            abort(404, 'Image not found');
        }

        // Cache the image response for 1 hour to reduce database load
        $cacheKey = "banner_image_{$banner->id}_{$banner->updated_at->timestamp}";
        
        return Cache::remember($cacheKey, 3600, function () use ($banner) {
            // Decode the base64 image data
            $imageData = base64_decode($banner->image_data);
            
            if ($imageData === false) {
                abort(500, 'Invalid image data');
            }

            // Determine MIME type
            $mimeType = $banner->image_mime_type ?? 'image/jpeg';
            
            // Create response with proper headers including CORS
            return response($imageData, 200, [
                'Content-Type' => $mimeType,
                'Content-Length' => strlen($imageData),
                'Cache-Control' => 'public, max-age=31536000', // Cache for 1 year
                'ETag' => md5($banner->id . $banner->updated_at),
            ]);
        });
    }
}

