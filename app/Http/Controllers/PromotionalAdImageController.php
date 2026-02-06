<?php

namespace App\Http\Controllers;

use App\Models\PromotionalAd;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class PromotionalAdImageController extends Controller
{
    /**
     * Serve promotional ad image stored in database.
     * Uses caching for better performance.
     */
    public function show(PromotionalAd $promotionalAd)
    {
        // Check if the promotional ad has database-stored image
        if (!$promotionalAd->isImageInDatabase()) {
            abort(404, 'Image not found');
        }

        // Cache the image response for 1 hour to reduce database load
        $cacheKey = "promotional_ad_image_{$promotionalAd->id}_{$promotionalAd->updated_at->timestamp}";
        
        return Cache::remember($cacheKey, 3600, function () use ($promotionalAd) {
            // Decode the base64 image data
            $imageData = base64_decode($promotionalAd->image_data);
            
            if ($imageData === false) {
                abort(500, 'Invalid image data');
            }

            // Determine MIME type
            $mimeType = $promotionalAd->image_mime_type ?? 'image/jpeg';
            
            // Create response with proper headers including CORS
            return response($imageData, 200, [
                'Content-Type' => $mimeType,
                'Content-Length' => strlen($imageData),
                'Cache-Control' => 'public, max-age=31536000', // Cache for 1 year
                'ETag' => md5($promotionalAd->id . $promotionalAd->updated_at),
            ]);
        });
    }
}

