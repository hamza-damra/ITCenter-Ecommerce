<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Request;

class ImageHelper
{
    /**
     * Maximum width for database-stored images
     */
    const MAX_WIDTH = 1920;

    /**
     * Maximum height for database-stored images
     */
    const MAX_HEIGHT = 1080;

    /**
     * JPEG quality (0-100)
     */
    const JPEG_QUALITY = 75;

    /**
     * PNG compression level (0-9)
     */
    const PNG_COMPRESSION = 6;

    /**
     * Maximum file size in bytes for database storage (1MB)
     */
    const MAX_DB_SIZE = 1048576;

    /**
     * Check if GD extension is available
     */
    public static function isGdAvailable(): bool
    {
        return extension_loaded('gd') && function_exists('imagecreatefromjpeg');
    }

    /**
     * Compress and resize an image for database storage.
     * Falls back to storing original if GD is not available.
     *
     * @param UploadedFile $file The uploaded image file
     * @param int $maxWidth Maximum width (default: 1920)
     * @param int $maxHeight Maximum height (default: 1080)
     * @param int $quality JPEG quality (default: 75)
     * @return array{data: string, mime_type: string, original_name: string}
     */
    public static function compressForDatabase(
        UploadedFile $file,
        int $maxWidth = self::MAX_WIDTH,
        int $maxHeight = self::MAX_HEIGHT,
        int $quality = self::JPEG_QUALITY
    ): array {
        $mimeType = $file->getMimeType();
        $originalName = $file->getClientOriginalName();
        $path = $file->getRealPath();

        // If GD is not available, just return the original image
        if (!self::isGdAvailable()) {
            return [
                'data' => base64_encode(file_get_contents($path)),
                'mime_type' => $mimeType,
                'original_name' => $originalName,
            ];
        }

        // Create image resource based on type
        $sourceImage = self::createImageFromFile($path, $mimeType);

        if ($sourceImage === false) {
            // If we can't process the image, just return the original
            return [
                'data' => base64_encode(file_get_contents($path)),
                'mime_type' => $mimeType,
                'original_name' => $originalName,
            ];
        }

        // Get original dimensions
        $originalWidth = imagesx($sourceImage);
        $originalHeight = imagesy($sourceImage);

        // Calculate new dimensions while maintaining aspect ratio
        [$newWidth, $newHeight] = self::calculateDimensions(
            $originalWidth,
            $originalHeight,
            $maxWidth,
            $maxHeight
        );

        // Create new image with calculated dimensions
        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG images
        if ($mimeType === 'image/png') {
            imagealphablending($newImage, false);
            imagesavealpha($newImage, true);
            $transparent = imagecolorallocatealpha($newImage, 0, 0, 0, 127);
            imagefilledrectangle($newImage, 0, 0, $newWidth, $newHeight, $transparent);
        }

        // Resize the image
        imagecopyresampled(
            $newImage,
            $sourceImage,
            0, 0, 0, 0,
            $newWidth, $newHeight,
            $originalWidth, $originalHeight
        );

        // Output to buffer
        ob_start();

        // Save based on mime type - always convert to JPEG for smaller size (except PNG with transparency)
        if ($mimeType === 'image/png') {
            // Check if PNG has transparency
            if (self::hasTransparency($sourceImage)) {
                imagepng($newImage, null, self::PNG_COMPRESSION);
                $outputMime = 'image/png';
            } else {
                // Convert to JPEG for smaller size
                imagejpeg($newImage, null, $quality);
                $outputMime = 'image/jpeg';
                $originalName = pathinfo($originalName, PATHINFO_FILENAME) . '.jpg';
            }
        } else {
            // Convert all other formats to JPEG
            imagejpeg($newImage, null, $quality);
            $outputMime = 'image/jpeg';
            if (!str_ends_with(strtolower($originalName), '.jpg') && !str_ends_with(strtolower($originalName), '.jpeg')) {
                $originalName = pathinfo($originalName, PATHINFO_FILENAME) . '.jpg';
            }
        }

        $imageData = ob_get_clean();

        // If still too large, reduce quality further
        $iteration = 0;
        while (strlen($imageData) > self::MAX_DB_SIZE && $quality > 20 && $iteration < 5) {
            $quality -= 15;
            $iteration++;
            
            ob_start();
            if ($outputMime === 'image/png') {
                imagepng($newImage, null, min(9, self::PNG_COMPRESSION + $iteration));
            } else {
                imagejpeg($newImage, null, $quality);
            }
            $imageData = ob_get_clean();
        }

        // Free memory
        imagedestroy($sourceImage);
        imagedestroy($newImage);

        return [
            'data' => base64_encode($imageData),
            'mime_type' => $outputMime,
            'original_name' => $originalName,
        ];
    }

    /**
     * Create image resource from file based on mime type.
     *
     * @param string $path File path
     * @param string $mimeType MIME type
     * @return \GdImage|false
     */
    private static function createImageFromFile(string $path, string $mimeType): \GdImage|false
    {
        if (!self::isGdAvailable()) {
            return false;
        }

        return match ($mimeType) {
            'image/jpeg', 'image/jpg' => imagecreatefromjpeg($path),
            'image/png' => imagecreatefrompng($path),
            'image/gif' => imagecreatefromgif($path),
            'image/webp' => function_exists('imagecreatefromwebp') ? imagecreatefromwebp($path) : false,
            default => false,
        };
    }

    /**
     * Calculate new dimensions while maintaining aspect ratio.
     *
     * @param int $originalWidth
     * @param int $originalHeight
     * @param int $maxWidth
     * @param int $maxHeight
     * @return array{0: int, 1: int}
     */
    private static function calculateDimensions(
        int $originalWidth,
        int $originalHeight,
        int $maxWidth,
        int $maxHeight
    ): array {
        // If image is smaller than max dimensions, keep original size
        if ($originalWidth <= $maxWidth && $originalHeight <= $maxHeight) {
            return [$originalWidth, $originalHeight];
        }

        $ratio = min($maxWidth / $originalWidth, $maxHeight / $originalHeight);

        return [
            (int) round($originalWidth * $ratio),
            (int) round($originalHeight * $ratio),
        ];
    }

    /**
     * Check if a PNG image has transparency.
     *
     * @param \GdImage $image
     * @return bool
     */
    private static function hasTransparency(\GdImage $image): bool
    {
        $width = imagesx($image);
        $height = imagesy($image);

        // Sample some pixels to check for transparency
        $samplePoints = [
            [0, 0],
            [$width - 1, 0],
            [0, $height - 1],
            [$width - 1, $height - 1],
            [(int) ($width / 2), (int) ($height / 2)],
        ];

        foreach ($samplePoints as [$x, $y]) {
            $rgba = imagecolorat($image, $x, $y);
            $alpha = ($rgba >> 24) & 0x7F;
            if ($alpha > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the approximate size of base64 encoded data in bytes.
     *
     * @param string $base64Data
     * @return int
     */
    public static function getBase64Size(string $base64Data): int
    {
        return (int) (strlen($base64Data) * 0.75);
    }

    /**
     * Get asset URL using current request host instead of configured APP_URL.
     * This fixes CORS issues when accessing via different domains/IPs.
     *
     * @param string $path
     * @return string
     */
    public static function assetUrl(string $path): string
    {
        // Remove leading slash if present
        $path = ltrim($path, '/');
        
        // Use current request to build URL dynamically
        $request = request();
        if ($request) {
            $scheme = $request->getScheme();
            $host = $request->getHost();
            $port = $request->getPort();
            
            $baseUrl = $scheme . '://' . $host;
            if (($scheme === 'http' && $port !== 80) || ($scheme === 'https' && $port !== 443)) {
                $baseUrl .= ':' . $port;
            }
            
            return $baseUrl . '/' . $path;
        }
        
        // Fallback to regular asset() if no request
        return asset($path);
    }
}

