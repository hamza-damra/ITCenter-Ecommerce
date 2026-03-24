<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates favicon upload requests.
 *
 * SVG (image/svg+xml) is intentionally excluded. SVG files can contain embedded
 * JavaScript and XML entities that enable XSS attacks. Supporting SVG safely
 * requires an SVG sanitizer library (e.g., enshrined/svg-sanitize), which this
 * project does not currently include.
 */
class UpdateFaviconRequest extends FormRequest
{
    public const MAX_SIZE_KB = 512;

    protected const ALLOWED_MIME_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/x-icon',
        'image/vnd.microsoft.icon',
    ];

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'favicon' => [
                'required',
                'file',
                'max:' . self::MAX_SIZE_KB,
                'mimes:jpg,jpeg,png,webp,ico',
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->hasFile('favicon') && !$validator->errors()->has('favicon')) {
                $this->validateMimeType($validator);
                $this->validateDangerousContent($validator);
                $this->validateImageIntegrity($validator);
            }
        });
    }

    protected function validateMimeType($validator): void
    {
        $file = $this->file('favicon');
        $mimeType = $file->getMimeType();

        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES, true)) {
            $validator->errors()->add('favicon', __('messages.favicon_invalid_type'));
        }
    }

    protected function validateDangerousContent($validator): void
    {
        $file = $this->file('favicon');
        $dangerousSignatures = [
            "<?php", "<?=", "<script",
            "\x4D\x5A",          // EXE
            "\x7F\x45\x4C\x46", // ELF
        ];

        $handle = @fopen($file->getRealPath(), 'rb');
        if (!$handle) {
            $validator->errors()->add('favicon', __('messages.favicon_upload_failed'));
            return;
        }

        $header = fread($handle, 512);
        fclose($handle);

        foreach ($dangerousSignatures as $sig) {
            if (str_contains($header, $sig)) {
                $validator->errors()->add('favicon', __('messages.favicon_dangerous_content'));
                return;
            }
        }
    }

    /**
     * For raster images, verify GD can read the file. ICO files skip this check
     * because GD does not support the ICO format.
     */
    protected function validateImageIntegrity($validator): void
    {
        $file = $this->file('favicon');
        $mimeType = $file->getMimeType();

        $icoMimes = ['image/x-icon', 'image/vnd.microsoft.icon'];
        if (in_array($mimeType, $icoMimes, true)) {
            return;
        }

        if (!extension_loaded('gd')) {
            return;
        }

        $imageInfo = @getimagesize($file->getRealPath());
        if ($imageInfo === false) {
            $validator->errors()->add('favicon', __('messages.favicon_invalid_image'));
        }
    }

    public function messages(): array
    {
        return [
            'favicon.required' => __('messages.favicon_required'),
            'favicon.file'     => __('messages.favicon_must_be_file'),
            'favicon.max'      => __('messages.favicon_max_size', ['size' => self::MAX_SIZE_KB]),
            'favicon.mimes'    => __('messages.favicon_invalid_type'),
        ];
    }
}
