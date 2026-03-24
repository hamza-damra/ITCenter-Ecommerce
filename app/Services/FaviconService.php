<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FaviconService
{
    protected const DIRECTORY = 'favicons';
    protected const SETTING_KEY = 'site_favicon';
    protected const SETTING_GROUP = 'branding';

    protected const ICO_MIME_TYPES = [
        'image/x-icon',
        'image/vnd.microsoft.icon',
    ];

    protected ImageUploadService $imageUploadService;

    public function __construct(ImageUploadService $imageUploadService)
    {
        $this->imageUploadService = $imageUploadService;
    }

    /**
     * Upload a new favicon, replacing any existing one.
     */
    public function upload(UploadedFile $file): string
    {
        $this->delete();

        $mimeType = $file->getMimeType();

        if (in_array($mimeType, self::ICO_MIME_TYPES, true)) {
            $relativePath = $this->uploadIco($file);
        } else {
            $relativePath = $this->uploadRaster($file);
        }

        SiteSetting::setValue(self::SETTING_KEY, $relativePath, 'string', self::SETTING_GROUP);
        SiteSetting::setValue('site_favicon_version', (string) time(), 'string', self::SETTING_GROUP);

        // Sync the processed favicon to public/favicon.ico so that nginx/Apache
        // static file serving also delivers the new icon (browsers auto-request /favicon.ico)
        $this->syncPublicFavicon($relativePath);

        return $relativePath;
    }

    /**
     * Delete the current favicon and clear the setting.
     */
    public function delete(): bool
    {
        $currentPath = SiteSetting::getValue(self::SETTING_KEY);

        if (!empty($currentPath)) {
            $this->imageUploadService->delete($currentPath);
        }

        SiteSetting::setValue(self::SETTING_KEY, '', 'string', self::SETTING_GROUP);

        // Restore the original default favicon.ico in the public directory
        $this->restoreDefaultPublicFavicon();

        return true;
    }

    /**
     * Get the public URL for the current favicon, with fallback.
     */
    public static function getUrl(): string
    {
        return SiteSetting::getFaviconUrl();
    }

    /**
     * Get the current stored path (raw value), or null if not set.
     */
    public static function getCurrentPath(): ?string
    {
        $path = SiteSetting::getValue(self::SETTING_KEY);

        return !empty($path) ? $path : null;
    }

    /**
     * Upload an ICO file directly — GD cannot process ICO files,
     * so we store as-is after scanning for dangerous content.
     */
    protected function uploadIco(UploadedFile $file): string
    {
        $this->scanForDangerousContent($file);

        $filename = Str::uuid()->toString() . '.ico';

        $stored = $file->storeAs(self::DIRECTORY, $filename, [
            'disk' => 'public',
            'visibility' => 'public',
        ]);

        if (!$stored) {
            throw new \RuntimeException(__('messages.favicon_upload_failed'));
        }

        return self::DIRECTORY . '/' . $filename;
    }

    /**
     * Upload a raster image (jpg, jpeg, png, webp) via ImageUploadService,
     * optimized to favicon-friendly dimensions.
     */
    protected function uploadRaster(UploadedFile $file): string
    {
        return $this->imageUploadService->upload($file, self::DIRECTORY, [
            'organize_by_date' => false,
            'optimize' => true,
            'max_width' => 180,
            'max_height' => 180,
            'quality' => 80,
            'convert_to_webp' => false,
        ]);
    }

    /**
     * Sync the uploaded favicon to public/favicon.ico so that web servers
     * (nginx/Apache) serving static files directly will deliver the new icon.
     * Browsers auto-request /favicon.ico regardless of <link rel="icon"> tags.
     */
    protected function syncPublicFavicon(string $storagePath): void
    {
        try {
            $fullPath = storage_path('app/public/' . $storagePath);

            if (!file_exists($fullPath) || !is_file($fullPath)) {
                return;
            }

            $ext = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

            // ICO files: copy directly to public/favicon.ico
            if ($ext === 'ico') {
                copy($fullPath, public_path('favicon.ico'));
                return;
            }

            // Raster images: process to 48x48 PNG and save as public/favicon.ico
            // (modern browsers handle PNG data in .ico files perfectly)
            $image = @imagecreatefromstring(file_get_contents($fullPath));
            if (!$image) {
                return;
            }

            $faviconSize = 48;
            $srcW = imagesx($image);
            $srcH = imagesy($image);
            $squareSize = min($srcW, $srcH);
            $cropX = 0;
            $cropY = (int) round(($srcH - $squareSize) / 2);

            $favicon = imagecreatetruecolor($faviconSize, $faviconSize);
            imagealphablending($favicon, false);
            imagesavealpha($favicon, true);
            $transparent = imagecolorallocatealpha($favicon, 0, 0, 0, 127);
            imagefill($favicon, 0, 0, $transparent);

            imagecopyresampled($favicon, $image, 0, 0, $cropX, $cropY, $faviconSize, $faviconSize, $squareSize, $squareSize);
            imagedestroy($image);

            imagepng($favicon, public_path('favicon.ico'), 9);
            imagedestroy($favicon);
        } catch (\Throwable $e) {
            Log::warning('Failed to sync public favicon: ' . $e->getMessage());
        }
    }

    /**
     * Restore the default public/favicon.ico placeholder when the custom favicon is deleted.
     */
    protected function restoreDefaultPublicFavicon(): void
    {
        try {
            // Create a minimal 1x1 transparent PNG as placeholder
            $img = imagecreatetruecolor(48, 48);
            imagealphablending($img, false);
            imagesavealpha($img, true);
            $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
            imagefill($img, 0, 0, $transparent);
            imagepng($img, public_path('favicon.ico'), 9);
            imagedestroy($img);
        } catch (\Throwable $e) {
            Log::warning('Failed to restore default public favicon: ' . $e->getMessage());
        }
    }

    /**
     * Scan file header for dangerous content signatures.
     */
    protected function scanForDangerousContent(UploadedFile $file): void
    {
        $dangerousSignatures = [
            "\x4D\x5A",         // EXE
            "\x7F\x45\x4C\x46", // ELF
            "<?php",
            "<?=",
            "<script",
        ];

        $handle = fopen($file->getRealPath(), 'rb');
        if (!$handle) {
            throw new \InvalidArgumentException(__('messages.favicon_upload_failed'));
        }

        $header = fread($handle, 256);
        fclose($handle);

        foreach ($dangerousSignatures as $signature) {
            if (str_contains($header, $signature)) {
                throw new \InvalidArgumentException(__('messages.favicon_dangerous_content'));
            }
        }
    }
}
