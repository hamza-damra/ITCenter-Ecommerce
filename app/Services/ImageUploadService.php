<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Jobs\OptimizeImageJob;

class ImageUploadService
{
    /**
     * The storage disk to use.
     */
    protected string $disk = 'public';

    /**
     * Allowed MIME types for image uploads.
     */
    protected array $allowedMimeTypes = [
        'image/jpeg',
        'image/png',
        'image/webp',
    ];

    /**
     * Allowed file extensions.
     */
    protected array $allowedExtensions = [
        'jpg', 'jpeg', 'png', 'webp',
    ];

    /**
     * Dangerous file signatures (magic bytes) to reject.
     */
    protected array $dangerousSignatures = [
        "\x4D\x5A",         // EXE
        "\x7F\x45\x4C\x46", // ELF
        "<?php",             // PHP
        "<?=",               // PHP short tag
        "<script",           // JavaScript
    ];

    /**
     * Maximum file size in bytes (dynamically loaded from site settings).
     */
    protected ?int $maxFileSize = null;

    /**
     * Default image quality (0-100).
     */
    protected int $defaultQuality = 80;

    /**
     * Default max width in pixels.
     */
    protected int $defaultMaxWidth = 1920;

    /**
     * Default max height in pixels.
     */
    protected int $defaultMaxHeight = 1080;

    /**
     * Upload an image file to storage.
     *
     * @param UploadedFile $file The uploaded file
     * @param string $directory Feature directory (e.g., 'products', 'categories', 'banners')
     * @param array $options Optional settings: optimize, max_width, max_height, quality, organize_by_date
     * @return string Relative path suitable for database storage (e.g., 'products/2026/02/uuid.webp')
     *
     * @throws \InvalidArgumentException If file fails security validation
     * @throws \RuntimeException If file storage fails
     */
    public function upload(UploadedFile $file, string $directory, array $options = []): string
    {
        // 1. Validate the file is safe
        $this->validateFile($file);

        // 2. Build the storage path
        $organizeByDate = $options['organize_by_date'] ?? true;
        $storagePath = $this->buildStoragePath($directory, $organizeByDate);

        // 3. Generate a unique, collision-safe filename
        $filename = $this->generateFilename($file);

        // 4. Store the file
        $relativePath = $this->storeFile($file, $storagePath, $filename);

        // 5. Dispatch optimization job if requested (default: true)
        $shouldOptimize = $options['optimize'] ?? true;
        if ($shouldOptimize) {
            $this->dispatchOptimization($relativePath, $options);
        }

        return $relativePath;
    }

    /**
     * Replace an existing image with a new one.
     * Deletes the old file (if it exists and is a local path) before storing the new one.
     *
     * @param string|null $oldPath The existing relative path (or null if no previous image)
     * @param UploadedFile $file The new uploaded file
     * @param string $directory Feature directory
     * @param array $options Optional settings
     * @return string New relative path
     */
    public function replace(?string $oldPath, UploadedFile $file, string $directory, array $options = []): string
    {
        // Delete old file if it's a local storage path (not a URL)
        if ($oldPath) {
            $this->delete($oldPath);
        }

        return $this->upload($file, $directory, $options);
    }

    /**
     * Delete an image from storage.
     *
     * @param string $path Relative path (e.g., 'products/2026/02/uuid.webp')
     * @return bool True if deleted, false if file didn't exist or is not a local path
     */
    public function delete(string $path): bool
    {
        // Skip URLs and empty paths
        if (empty($path) || $this->isUrl($path)) {
            return false;
        }

        // Strip 'storage/' prefix if present (DB might store with or without it)
        $path = $this->normalizePathForStorage($path);

        try {
            if (Storage::disk($this->disk)->exists($path)) {
                return Storage::disk($this->disk)->delete($path);
            }
        } catch (\Exception $e) {
            Log::warning('ImageUploadService: Failed to delete image', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);
        }

        return false;
    }

    /**
     * Delete multiple images from storage.
     *
     * @param array $paths Array of relative paths
     * @return int Number of files successfully deleted
     */
    public function deleteMany(array $paths): int
    {
        $deleted = 0;
        foreach ($paths as $path) {
            if ($this->delete($path)) {
                $deleted++;
            }
        }
        return $deleted;
    }

    /**
     * Check if an image exists in storage.
     *
     * @param string $path Relative path
     * @return bool
     */
    public function exists(string $path): bool
    {
        if (empty($path) || $this->isUrl($path)) {
            return false;
        }

        $path = $this->normalizePathForStorage($path);

        return Storage::disk($this->disk)->exists($path);
    }

    /**
     * Get the public URL for an image path.
     *
     * @param string $path Relative path
     * @param string|null $fallback Fallback URL if image doesn't exist
     * @return string
     */
    public function url(string $path, ?string $fallback = null): string
    {
        if (empty($path)) {
            return $fallback ?? asset('images/products/default.png');
        }

        // If it's already a URL, return as-is
        if ($this->isUrl($path)) {
            return $path;
        }

        $path = $this->normalizePathForStorage($path);

        return asset('media/' . $path);
    }

    /**
     * Get the full filesystem path for an image.
     *
     * @param string $path Relative path
     * @return string
     */
    public function fullPath(string $path): string
    {
        $path = $this->normalizePathForStorage($path);

        return Storage::disk($this->disk)->path($path);
    }

    /**
     * Validate the uploaded file for security and type.
     *
     * @param UploadedFile $file
     * @throws \InvalidArgumentException
     */
    protected function validateFile(UploadedFile $file): void
    {
        // Resolve max file size from site settings if not explicitly set
        $maxFileSize = $this->maxFileSize ?? ((int) SiteSetting::getValue('max_image_size_kb', 5120) * 1024);

        // Check file size
        if ($file->getSize() > $maxFileSize) {
            throw new \InvalidArgumentException(
                "File size exceeds maximum allowed size of " . round($maxFileSize / 1048576, 1) . "MB."
            );
        }

        // Validate MIME type (server-side, not trusting client)
        $mimeType = $file->getMimeType();
        if (!in_array($mimeType, $this->allowedMimeTypes, true)) {
            throw new \InvalidArgumentException(
                "Invalid file type: {$mimeType}. Allowed types: " . implode(', ', $this->allowedMimeTypes)
            );
        }

        // Validate file extension
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $this->allowedExtensions, true)) {
            throw new \InvalidArgumentException(
                "Invalid file extension: {$extension}. Allowed: " . implode(', ', $this->allowedExtensions)
            );
        }

        // Cross-check MIME type matches extension
        $this->validateMimeMatchesExtension($mimeType, $extension);

        // Check for dangerous content in file header
        $this->scanForDangerousContent($file);

        // Verify the file is actually a valid image using GD
        $this->validateImageIntegrity($file);
    }

    /**
     * Ensure MIME type and extension are consistent.
     *
     * @param string $mimeType
     * @param string $extension
     * @throws \InvalidArgumentException
     */
    protected function validateMimeMatchesExtension(string $mimeType, string $extension): void
    {
        $mimeToExtension = [
            'image/jpeg' => ['jpg', 'jpeg'],
            'image/png'  => ['png'],
            'image/webp' => ['webp'],
        ];

        $validExtensions = $mimeToExtension[$mimeType] ?? [];

        if (!in_array($extension, $validExtensions, true)) {
            throw new \InvalidArgumentException(
                "MIME type ({$mimeType}) does not match file extension ({$extension})."
            );
        }
    }

    /**
     * Scan first bytes of file for dangerous signatures.
     *
     * @param UploadedFile $file
     * @throws \InvalidArgumentException
     */
    protected function scanForDangerousContent(UploadedFile $file): void
    {
        $handle = fopen($file->getRealPath(), 'rb');
        if (!$handle) {
            throw new \InvalidArgumentException("Unable to read uploaded file.");
        }

        $header = fread($handle, 256);
        fclose($handle);

        foreach ($this->dangerousSignatures as $signature) {
            if (str_contains($header, $signature)) {
                throw new \InvalidArgumentException(
                    "File contains potentially dangerous content and was rejected."
                );
            }
        }
    }

    /**
     * Verify the file is a genuine image by attempting to read it with GD.
     *
     * @param UploadedFile $file
     * @throws \InvalidArgumentException
     */
    protected function validateImageIntegrity(UploadedFile $file): void
    {
        if (!extension_loaded('gd')) {
            // GD not available; skip this check
            return;
        }

        $imageInfo = @getimagesize($file->getRealPath());

        if ($imageInfo === false) {
            throw new \InvalidArgumentException(
                "File is not a valid image or is corrupted."
            );
        }

        // Verify the reported image type matches allowed types
        $allowedImageTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP];
        if (!in_array($imageInfo[2], $allowedImageTypes, true)) {
            throw new \InvalidArgumentException(
                "Image type detected by GD does not match allowed types."
            );
        }
    }

    /**
     * Build the directory path for storing the file.
     *
     * @param string $directory Base directory (feature name)
     * @param bool $organizeByDate Whether to add year/month subdirectories
     * @return string
     */
    protected function buildStoragePath(string $directory, bool $organizeByDate = true): string
    {
        // Sanitize directory name to prevent traversal
        $directory = preg_replace('/[^a-zA-Z0-9_\-\/]/', '', $directory);
        $directory = trim($directory, '/');

        if ($organizeByDate) {
            $year = now()->format('Y');
            $month = now()->format('m');
            return "{$directory}/{$year}/{$month}";
        }

        return $directory;
    }

    /**
     * Generate a unique, collision-safe filename.
     * Uses UUID to prevent collisions and directory traversal.
     *
     * @param UploadedFile $file
     * @return string
     */
    protected function generateFilename(UploadedFile $file): string
    {
        $extension = strtolower($file->getClientOriginalExtension());

        return Str::uuid()->toString() . '.' . $extension;
    }

    /**
     * Store the file to disk.
     *
     * @param UploadedFile $file
     * @param string $storagePath Directory path within the disk
     * @param string $filename Generated filename
     * @return string Relative path (directory + filename)
     *
     * @throws \RuntimeException
     */
    protected function storeFile(UploadedFile $file, string $storagePath, string $filename): string
    {
        $relativePath = $storagePath . '/' . $filename;

        // Ensure no overwrite (extremely unlikely with UUID, but defense in depth)
        if (Storage::disk($this->disk)->exists($relativePath)) {
            $filename = Str::uuid()->toString() . '_' . time() . '.' . $file->getClientOriginalExtension();
            $relativePath = $storagePath . '/' . $filename;
        }

        // Store the file with public visibility
        $stored = $file->storeAs($storagePath, $filename, [
            'disk' => $this->disk,
            'visibility' => 'public',
        ]);

        if (!$stored) {
            throw new \RuntimeException(
                "Failed to store image file. Check disk permissions and available space."
            );
        }

        return $relativePath;
    }

    /**
     * Dispatch the image optimization job to the queue.
     *
     * @param string $relativePath
     * @param array $options
     */
    protected function dispatchOptimization(string $relativePath, array $options = []): void
    {
        $maxWidth = $options['max_width'] ?? $this->defaultMaxWidth;
        $maxHeight = $options['max_height'] ?? $this->defaultMaxHeight;
        $quality = $options['quality'] ?? $this->defaultQuality;
        $convertToWebp = $options['convert_to_webp'] ?? true;

        try {
            OptimizeImageJob::dispatch(
                $relativePath,
                $this->disk,
                $maxWidth,
                $maxHeight,
                $quality,
                $convertToWebp
            );
        } catch (\Exception $e) {
            // If queue dispatch fails, log but don't fail the upload
            Log::warning('ImageUploadService: Failed to dispatch optimization job', [
                'path' => $relativePath,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Check if a path is a URL.
     *
     * @param string $path
     * @return bool
     */
    protected function isUrl(string $path): bool
    {
        return str_starts_with($path, 'http://') || str_starts_with($path, 'https://');
    }

    /**
     * Normalize a path for storage operations by stripping common prefixes.
     *
     * @param string $path
     * @return string
     */
    protected function normalizePathForStorage(string $path): string
    {
        // Strip 'storage/' prefix if present
        if (str_starts_with($path, 'storage/')) {
            $path = substr($path, 8);
        }

        // Prevent directory traversal
        $path = str_replace(['../', '..\\'], '', $path);

        return ltrim($path, '/\\');
    }

    /**
     * Set a custom disk for this service instance.
     *
     * @param string $disk
     * @return static
     */
    public function disk(string $disk): static
    {
        $this->disk = $disk;
        return $this;
    }

    /**
     * Set maximum file size.
     *
     * @param int $bytes
     * @return static
     */
    public function maxSize(int $bytes): static
    {
        $this->maxFileSize = $bytes;
        return $this;
    }

    /**
     * Ensure the storage symlink exists.
     * Useful for deployment scripts on shared hosting.
     *
     * @return bool True if symlink exists or was created
     */
    public static function ensureStorageLink(): bool
    {
        $link = public_path('storage');
        $target = storage_path('app/public');

        $linkExists = is_link($link) || is_dir($link);

        if (!$linkExists) {
            try {
                if (function_exists('symlink')) {
                    $linkExists = symlink($target, $link);
                }
            } catch (\Exception $e) {
                Log::error('ImageUploadService: Failed to create storage symlink', [
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Create .htaccess in storage/app/public to prevent PHP execution
        static::ensureSecurityFiles();

        return $linkExists;
    }

    /**
     * Create security files in the storage public directory.
     * Prevents PHP execution and directory listing inside uploaded file directories.
     */
    public static function ensureSecurityFiles(): void
    {
        $htaccessPath = storage_path('app/public/.htaccess');

        // Security .htaccess for storage directory
        // NOTE: Do NOT use RewriteEngine Off here - it disables inherited rewrite rules
        // which prevents the parent .htaccess from routing /storage/ through Laravel
        $htaccessContent = <<<'HTACCESS'
# Prevent PHP execution in upload directories
<FilesMatch "\.(?:php|phtml|php3|php4|php5|php7|php8|phar|phps)$">
    <IfModule mod_authz_core.c>
        Require all denied
    </IfModule>
    <IfModule !mod_authz_core.c>
        Order Deny,Allow
        Deny from all
    </IfModule>
</FilesMatch>

# Prevent directory listing
Options -Indexes

# Allow all access to public files in this directory
<IfModule mod_authz_core.c>
    Require all granted
</IfModule>
<IfModule !mod_authz_core.c>
    Order Allow,Deny
    Allow from all
</IfModule>
HTACCESS;

        try {
            file_put_contents($htaccessPath, $htaccessContent);
        } catch (\Exception $e) {
            Log::warning('ImageUploadService: Failed to create .htaccess', [
                'path' => $htaccessPath,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
