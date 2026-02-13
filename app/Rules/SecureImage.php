<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SecureImage implements ValidationRule
{
    /**
     * Allowed MIME types.
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
     * Maximum file size in kilobytes.
     */
    protected int $maxSizeKb;

    /**
     * Dangerous content signatures to reject.
     */
    protected array $dangerousSignatures = [
        '<?php',
        '<?=',
        '<script',
        '\x4D\x5A',
    ];

    /**
     * Create a new rule instance.
     *
     * @param int $maxSizeKb Maximum file size in kilobytes (default: 2048 = 2MB)
     */
    public function __construct(int $maxSizeKb = 2048)
    {
        $this->maxSizeKb = $maxSizeKb;
    }

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Must be an uploaded file
        if (!$value instanceof \Illuminate\Http\UploadedFile) {
            $fail(__('validation.file', ['attribute' => $attribute]));
            return;
        }

        // Check file size
        if ($value->getSize() > $this->maxSizeKb * 1024) {
            $fail(__('validation.max.file', [
                'attribute' => $attribute,
                'max' => $this->maxSizeKb,
            ]));
            return;
        }

        // Validate MIME type (server-detected, not client-provided)
        $mimeType = $value->getMimeType();
        if (!in_array($mimeType, $this->allowedMimeTypes, true)) {
            $fail(__('validation.mimes', [
                'attribute' => $attribute,
                'values' => implode(', ', $this->allowedExtensions),
            ]));
            return;
        }

        // Validate file extension
        $extension = strtolower($value->getClientOriginalExtension());
        if (!in_array($extension, $this->allowedExtensions, true)) {
            $fail(__('validation.mimes', [
                'attribute' => $attribute,
                'values' => implode(', ', $this->allowedExtensions),
            ]));
            return;
        }

        // Cross-validate MIME type matches extension
        if (!$this->mimeMatchesExtension($mimeType, $extension)) {
            $fail(__('validation.mimes', [
                'attribute' => $attribute,
                'values' => implode(', ', $this->allowedExtensions),
            ]));
            return;
        }

        // Scan for dangerous content in file header
        if ($this->containsDangerousContent($value)) {
            $fail(__('validation.file', ['attribute' => $attribute]));
            return;
        }

        // Verify image integrity using GD/getimagesize
        if (!$this->isValidImage($value)) {
            $fail(__('validation.image', ['attribute' => $attribute]));
            return;
        }
    }

    /**
     * Check that the detected MIME type is consistent with the file extension.
     */
    protected function mimeMatchesExtension(string $mimeType, string $extension): bool
    {
        $map = [
            'image/jpeg' => ['jpg', 'jpeg'],
            'image/png'  => ['png'],
            'image/webp' => ['webp'],
        ];

        $validExtensions = $map[$mimeType] ?? [];

        return in_array($extension, $validExtensions, true);
    }

    /**
     * Scan the first bytes of the file for dangerous signatures.
     */
    protected function containsDangerousContent(\Illuminate\Http\UploadedFile $file): bool
    {
        $handle = @fopen($file->getRealPath(), 'rb');
        if (!$handle) {
            return true; // Fail safe: if we can't read the file, reject it
        }

        $header = fread($handle, 512);
        fclose($handle);

        if ($header === false) {
            return true;
        }

        foreach ($this->dangerousSignatures as $signature) {
            if (str_contains($header, $signature)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verify the file is a genuine image by attempting to read its metadata.
     */
    protected function isValidImage(\Illuminate\Http\UploadedFile $file): bool
    {
        $imageInfo = @getimagesize($file->getRealPath());

        if ($imageInfo === false) {
            return false;
        }

        // Verify the reported image type is one we allow
        $allowedTypes = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_WEBP];

        return in_array($imageInfo[2], $allowedTypes, true);
    }
}
