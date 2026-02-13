<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class OptimizeImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     * Shared hosting may have strict limits; keep this reasonable.
     */
    public int $timeout = 60;

    /**
     * Delete the job if its models no longer exist.
     */
    public bool $deleteWhenMissingModels = true;

    /**
     * Create a new job instance.
     *
     * @param string $relativePath Relative path within the disk (e.g., 'products/2026/02/uuid.jpg')
     * @param string $disk Storage disk name
     * @param int $maxWidth Maximum width in pixels
     * @param int $maxHeight Maximum height in pixels
     * @param int $quality Compression quality (0-100)
     * @param bool $convertToWebp Whether to convert to WebP format
     */
    public function __construct(
        protected string $relativePath,
        protected string $disk = 'public',
        protected int $maxWidth = 1920,
        protected int $maxHeight = 1080,
        protected int $quality = 80,
        protected bool $convertToWebp = true,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!extension_loaded('gd')) {
            Log::info('OptimizeImageJob: GD extension not available, skipping optimization', [
                'path' => $this->relativePath,
            ]);
            return;
        }

        $storage = Storage::disk($this->disk);

        if (!$storage->exists($this->relativePath)) {
            Log::warning('OptimizeImageJob: File not found, skipping', [
                'path' => $this->relativePath,
            ]);
            return;
        }

        $fullPath = $storage->path($this->relativePath);

        try {
            $this->optimizeImage($fullPath, $storage);
        } catch (\Exception $e) {
            Log::error('OptimizeImageJob: Optimization failed', [
                'path' => $this->relativePath,
                'error' => $e->getMessage(),
            ]);

            // Don't re-throw; the original unoptimized image is still valid
        }
    }

    /**
     * Optimize a single image: resize, compress, strip EXIF, and optionally convert to WebP.
     *
     * @param string $fullPath Absolute filesystem path
     * @param \Illuminate\Contracts\Filesystem\Filesystem $storage
     */
    protected function optimizeImage(string $fullPath, $storage): void
    {
        $imageInfo = @getimagesize($fullPath);
        if ($imageInfo === false) {
            Log::warning('OptimizeImageJob: Not a valid image file', ['path' => $fullPath]);
            return;
        }

        [$originalWidth, $originalHeight, $imageType] = $imageInfo;

        // Create GD image resource from the source file
        $sourceImage = $this->createImageResource($fullPath, $imageType);
        if ($sourceImage === false) {
            return;
        }

        // Calculate new dimensions (preserve aspect ratio)
        [$newWidth, $newHeight] = $this->calculateDimensions(
            $originalWidth,
            $originalHeight,
            $this->maxWidth,
            $this->maxHeight
        );

        // Resize if needed
        $needsResize = ($newWidth !== $originalWidth || $newHeight !== $originalHeight);

        if ($needsResize) {
            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

            // Preserve transparency for PNG
            if ($imageType === IMAGETYPE_PNG) {
                imagealphablending($resizedImage, false);
                imagesavealpha($resizedImage, true);
                $transparent = imagecolorallocatealpha($resizedImage, 0, 0, 0, 127);
                imagefilledrectangle($resizedImage, 0, 0, $newWidth, $newHeight, $transparent);
            }

            imagecopyresampled(
                $resizedImage, $sourceImage,
                0, 0, 0, 0,
                $newWidth, $newHeight,
                $originalWidth, $originalHeight
            );

            imagedestroy($sourceImage);
            $sourceImage = $resizedImage;
        }

        // Determine output format and path
        $canWebp = $this->convertToWebp && function_exists('imagewebp');
        $hasPngTransparency = ($imageType === IMAGETYPE_PNG) && $this->hasTransparency($sourceImage);

        // Don't convert transparent PNGs to WebP (WebP supports transparency but quality can suffer)
        $outputAsWebp = $canWebp && !$hasPngTransparency;

        if ($outputAsWebp) {
            // Save as WebP
            $webpPath = $this->changeExtension($this->relativePath, 'webp');
            $webpFullPath = $storage->path($webpPath);

            // Ensure directory exists
            $dir = dirname($webpFullPath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            imagewebp($sourceImage, $webpFullPath, $this->quality);

            // Verify WebP was created and is smaller (or at least valid)
            if (file_exists($webpFullPath) && filesize($webpFullPath) > 0) {
                // Delete original file if WebP has a different path
                if ($webpPath !== $this->relativePath) {
                    $storage->delete($this->relativePath);
                }

                Log::info('OptimizeImageJob: Converted to WebP', [
                    'original' => $this->relativePath,
                    'webp' => $webpPath,
                    'original_size' => filesize($fullPath),
                    'webp_size' => filesize($webpFullPath),
                ]);

                // Update the database path if a callback model is provided
                // This is handled by the model's observer or the caller
            } else {
                // WebP creation failed; keep original and optimize in-place
                $this->optimizeInPlace($sourceImage, $fullPath, $imageType);
            }
        } else {
            // Optimize in original format
            $this->optimizeInPlace($sourceImage, $fullPath, $imageType);
        }

        imagedestroy($sourceImage);
    }

    /**
     * Optimize an image in place (same format, compressed).
     * GD re-encoding strips EXIF data automatically.
     *
     * @param \GdImage $image
     * @param string $fullPath
     * @param int $imageType
     */
    protected function optimizeInPlace(\GdImage $image, string $fullPath, int $imageType): void
    {
        switch ($imageType) {
            case IMAGETYPE_JPEG:
                imagejpeg($image, $fullPath, $this->quality);
                break;

            case IMAGETYPE_PNG:
                // PNG compression level: 0 (none) to 9 (max)
                // Map quality (0-100) to compression (9-0)
                $compression = (int) round((100 - $this->quality) * 9 / 100);
                imagepng($image, $fullPath, $compression);
                break;

            case IMAGETYPE_WEBP:
                if (function_exists('imagewebp')) {
                    imagewebp($image, $fullPath, $this->quality);
                }
                break;
        }
    }

    /**
     * Create a GD image resource from a file.
     *
     * @param string $path
     * @param int $imageType
     * @return \GdImage|false
     */
    protected function createImageResource(string $path, int $imageType): \GdImage|false
    {
        return match ($imageType) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($path),
            IMAGETYPE_PNG  => @imagecreatefrompng($path),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            default        => false,
        };
    }

    /**
     * Calculate new dimensions while maintaining aspect ratio.
     *
     * @param int $width
     * @param int $height
     * @param int $maxWidth
     * @param int $maxHeight
     * @return array{0: int, 1: int}
     */
    protected function calculateDimensions(int $width, int $height, int $maxWidth, int $maxHeight): array
    {
        if ($width <= $maxWidth && $height <= $maxHeight) {
            return [$width, $height];
        }

        $ratio = min($maxWidth / $width, $maxHeight / $height);

        return [
            (int) round($width * $ratio),
            (int) round($height * $ratio),
        ];
    }

    /**
     * Check if a GD image has transparent pixels.
     *
     * @param \GdImage $image
     * @return bool
     */
    protected function hasTransparency(\GdImage $image): bool
    {
        $width = imagesx($image);
        $height = imagesy($image);

        // Sample corners and center
        $points = [
            [0, 0],
            [$width - 1, 0],
            [0, $height - 1],
            [$width - 1, $height - 1],
            [(int) ($width / 2), (int) ($height / 2)],
        ];

        foreach ($points as [$x, $y]) {
            $rgba = imagecolorat($image, $x, $y);
            $alpha = ($rgba >> 24) & 0x7F;
            if ($alpha > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Change the file extension of a path.
     *
     * @param string $path
     * @param string $newExtension
     * @return string
     */
    protected function changeExtension(string $path, string $newExtension): string
    {
        $info = pathinfo($path);
        $dir = $info['dirname'] ?? '';
        $filename = $info['filename'] ?? '';

        return ($dir ? $dir . '/' : '') . $filename . '.' . $newExtension;
    }

    /**
     * Get the new relative path if the image was converted to WebP.
     * Useful for callers who need to update the DB after the job runs.
     *
     * @return string
     */
    public function getWebpPath(): string
    {
        return $this->changeExtension($this->relativePath, 'webp');
    }
}
