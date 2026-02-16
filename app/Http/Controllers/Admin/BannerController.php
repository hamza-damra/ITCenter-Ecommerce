<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\SiteSetting;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function __construct(
        protected ImageUploadService $imageService
    ) {}

    /**
     * Display a listing of banners.
     */
    public function index()
    {
        $banners = Banner::orderBy('display_order', 'asc')
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new banner.
     */
    public function create()
    {
        return view('admin.banners.create');
    }

    /**
     * Store a newly created banner in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'image_source' => 'required|in:file,url,download_url',
            'title_en' => 'nullable|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'title_he' => 'nullable|string|max:255',
            'subtitle_en' => 'nullable|string|max:500',
            'subtitle_ar' => 'nullable|string|max:500',
            'subtitle_he' => 'nullable|string|max:500',
            'link' => 'nullable|url|max:255',
            'button_text_en' => 'nullable|string|max:100',
            'button_text_ar' => 'nullable|string|max:100',
            'button_text_he' => 'nullable|string|max:100',
            'title_color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'subtitle_color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'button_bg_color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'button_text_color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ];

        $imageSource = $request->input('image_source', 'file');

        if ($imageSource === 'file') {
            $rules['image'] = 'required|image|mimes:jpg,jpeg,png,webp|max:2048';
        } else {
            // Both 'url' and 'download_url' require a URL
            $rules['image_url'] = 'required|url|max:2048';
        }

        $validated = $request->validate($rules);

        // Validate at least one title is provided
        if (empty($validated['title_en']) && empty($validated['title_ar']) && empty($validated['title_he'])) {
            return back()->withInput()->withErrors([
                'title_en' => __('messages.at_least_one_title_required'),
            ]);
        }

        // Resolve image path based on source
        $resolvedImage = $this->resolveImage($request, $validated);
        if ($resolvedImage === false) {
            return back()->withInput()->withErrors([
                'image_url' => __('messages.failed_to_download_image') ?? 'Failed to download image from the provided URL.',
            ]);
        }

        // Prepare banner data
        $bannerData = [
            'image_path' => $resolvedImage['path'],
            'image_source' => $resolvedImage['source'],
            'title_en' => $validated['title_en'] ?? null,
            'title_ar' => $validated['title_ar'] ?? null,
            'title_he' => $validated['title_he'] ?? null,
            'subtitle_en' => $validated['subtitle_en'] ?? null,
            'subtitle_ar' => $validated['subtitle_ar'] ?? null,
            'subtitle_he' => $validated['subtitle_he'] ?? null,
            'link' => $validated['link'] ?? null,
            'button_text_en' => $validated['button_text_en'] ?? null,
            'button_text_ar' => $validated['button_text_ar'] ?? null,
            'button_text_he' => $validated['button_text_he'] ?? null,
            'title_color' => $validated['title_color'] ?? null,
            'subtitle_color' => $validated['subtitle_color'] ?? null,
            'button_bg_color' => $validated['button_bg_color'] ?? null,
            'button_text_color' => $validated['button_text_color'] ?? null,
            'display_order' => $validated['display_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ];

        Banner::create($bannerData);

        $this->clearHomeCache();

        return redirect()->route('admin.banners.index')
            ->with('success', __('messages.banner_created_successfully'));
    }

    /**
     * Show the form for editing the specified banner.
     */
    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    /**
     * Update the specified banner in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        $rules = [
            'image_source' => 'required|in:file,url,download_url,keep',
            'title_en' => 'nullable|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'title_he' => 'nullable|string|max:255',
            'subtitle_en' => 'nullable|string|max:500',
            'subtitle_ar' => 'nullable|string|max:500',
            'subtitle_he' => 'nullable|string|max:500',
            'link' => 'nullable|url|max:255',
            'button_text_en' => 'nullable|string|max:100',
            'button_text_ar' => 'nullable|string|max:100',
            'button_text_he' => 'nullable|string|max:100',
            'title_color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'subtitle_color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'button_bg_color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'button_text_color' => 'nullable|string|max:7|regex:/^#[0-9A-Fa-f]{6}$/',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ];

        $imageSource = $request->input('image_source', 'keep');

        if ($imageSource === 'file') {
            $rules['image'] = 'required|image|mimes:jpg,jpeg,png,webp|max:2048';
        } elseif ($imageSource === 'url' || $imageSource === 'download_url') {
            $rules['image_url'] = 'required|url|max:2048';
        }

        $validated = $request->validate($rules);

        // Validate at least one title is provided
        if (empty($validated['title_en']) && empty($validated['title_ar']) && empty($validated['title_he'])) {
            return back()->withInput()->withErrors([
                'title_en' => __('messages.at_least_one_title_required'),
            ]);
        }

        // Prepare banner data
        $bannerData = [
            'title_en' => $validated['title_en'] ?? null,
            'title_ar' => $validated['title_ar'] ?? null,
            'title_he' => $validated['title_he'] ?? null,
            'subtitle_en' => $validated['subtitle_en'] ?? null,
            'subtitle_ar' => $validated['subtitle_ar'] ?? null,
            'subtitle_he' => $validated['subtitle_he'] ?? null,
            'link' => $validated['link'] ?? null,
            'button_text_en' => $validated['button_text_en'] ?? null,
            'button_text_ar' => $validated['button_text_ar'] ?? null,
            'button_text_he' => $validated['button_text_he'] ?? null,
            'title_color' => $validated['title_color'] ?? null,
            'subtitle_color' => $validated['subtitle_color'] ?? null,
            'button_bg_color' => $validated['button_bg_color'] ?? null,
            'button_text_color' => $validated['button_text_color'] ?? null,
            'display_order' => $validated['display_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ];

        // Handle image change if not keeping current
        if ($imageSource !== 'keep') {
            $resolvedImage = $this->resolveImage($request, $validated, $banner);
            if ($resolvedImage === false) {
                return back()->withInput()->withErrors([
                    'image_url' => __('messages.failed_to_download_image') ?? 'Failed to download image from the provided URL.',
                ]);
            }

            // Delete old local file if switching to a new image
            if ($banner->image_source === Banner::SOURCE_FILE && !empty($banner->image_path)) {
                $this->imageService->delete($banner->image_path);
            }

            $bannerData['image_path'] = $resolvedImage['path'];
            $bannerData['image_source'] = $resolvedImage['source'];
        }

        $banner->update($bannerData);

        $this->clearHomeCache();

        return redirect()->route('admin.banners.index')
            ->with('success', __('messages.banner_updated_successfully'));
    }

    /**
     * Remove the specified banner from storage.
     */
    public function destroy(Banner $banner)
    {
        // The HasUploadedImage trait handles file cleanup on delete
        $banner->delete();

        $this->clearHomeCache();

        return redirect()->route('admin.banners.index')
            ->with('success', __('messages.banner_deleted_successfully'));
    }

    /**
     * Clear home page cache for all locales.
     */
    private function clearHomeCache(): void
    {
        Cache::forget('home_page_data_ar');
        Cache::forget('home_page_data_en');
        Cache::forget('home_page_data_he');
    }

    /**
     * Resolve the image path and source based on the selected image_source option.
     *
     * @return array{path: string, source: string}|false
     */
    private function resolveImage(Request $request, array $validated, ?Banner $existingBanner = null): array|false
    {
        $imageSource = $request->input('image_source', 'file');

        if ($imageSource === 'file' && $request->hasFile('image')) {
            // Upload from device to local storage
            $path = $existingBanner
                ? $this->imageService->replace($existingBanner->image_path, $request->file('image'), 'banners', $this->getImageUploadOptions())
                : $this->imageService->upload($request->file('image'), 'banners', $this->getImageUploadOptions());

            return ['path' => $path, 'source' => Banner::SOURCE_FILE];
        }

        if ($imageSource === 'url' && !empty($validated['image_url'])) {
            // Use external URL directly (no download)
            return ['path' => $validated['image_url'], 'source' => Banner::SOURCE_URL];
        }

        if ($imageSource === 'download_url' && !empty($validated['image_url'])) {
            // Download from URL and store in local storage
            $path = $this->downloadImageFromUrl($validated['image_url']);
            if ($path === false) {
                return false;
            }
            return ['path' => $path, 'source' => Banner::SOURCE_FILE];
        }

        // Fallback: keep existing
        if ($existingBanner) {
            return ['path' => $existingBanner->image_path, 'source' => $existingBanner->image_source];
        }

        return ['path' => null, 'source' => Banner::SOURCE_FILE];
    }

    /**
     * Download an image from a URL and store it in local storage.
     *
     * @return string|false Relative path on success, false on failure
     */
    private function downloadImageFromUrl(string $url): string|false
    {
        try {
            $tempPath = tempnam(sys_get_temp_dir(), 'banner_');

            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 5,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; BannerDownloader/1.0)',
            ]);

            $imageData = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            $error = curl_error($ch);
            curl_close($ch);

            if ($imageData === false || $httpCode !== 200) {
                Log::warning('Banner: Failed to download image from URL', ['url' => $url, 'http_code' => $httpCode, 'error' => $error]);
                @unlink($tempPath);
                return false;
            }

            // Validate content type
            $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
            $mimeBase = explode(';', $contentType)[0] ?? '';
            if (!in_array(trim($mimeBase), $allowedMimes, true)) {
                Log::warning('Banner: Invalid content type from URL', ['url' => $url, 'content_type' => $contentType]);
                @unlink($tempPath);
                return false;
            }

            // Write to temp file
            file_put_contents($tempPath, $imageData);

            // Determine extension from MIME
            $extension = match (trim($mimeBase)) {
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
                default => 'jpg',
            };

            // Rename temp file with proper extension
            $tempWithExt = $tempPath . '.' . $extension;
            rename($tempPath, $tempWithExt);

            // Create an UploadedFile instance from the temp file
            $uploadedFile = new UploadedFile(
                $tempWithExt,
                'banner_' . Str::random(8) . '.' . $extension,
                trim($mimeBase),
                null,
                true // test mode: skip is_uploaded_file check
            );

            // Upload via ImageUploadService
            $path = $this->imageService->upload($uploadedFile, 'banners', $this->getImageUploadOptions());

            // Cleanup temp file
            @unlink($tempWithExt);

            return $path;
        } catch (\Exception $e) {
            Log::error('Banner: Exception downloading image from URL', ['url' => $url, 'error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Get image upload options for banners.
     */
    private function getImageUploadOptions(): array
    {
        return [
            'optimize' => true,
            'max_width' => 1920,
            'max_height' => 600,
            'quality' => (int) SiteSetting::getValue('image_quality', 85),
            'convert_to_webp' => (bool) SiteSetting::getValue('convert_to_webp', true),
        ];
    }
}
