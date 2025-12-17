<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Helpers\ImageHelper;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BannerController extends Controller
{
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
        // Base validation rules - only database and url options
        $rules = [
            'image_source' => 'required|in:database,url',
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
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ];

        // Add conditional validation based on image source
        $imageSource = $request->input('image_source', 'database');
        
        if ($imageSource === 'url') {
            $rules['image_url'] = 'required|url|max:2048';
        } else {
            // Database storage requires file upload
            // Max 2MB to prevent MySQL packet errors (without GD compression)
            $rules['image'] = 'required|image|mimes:jpg,jpeg,png,gif,webp|max:2048';
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
            'image_source' => $imageSource,
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
            'display_order' => $validated['display_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ];

        // Handle image based on source type
        if ($imageSource === Banner::SOURCE_URL) {
            // Store the external URL
            $bannerData['image_path'] = $validated['image_url'];
            $bannerData['image_data'] = null;
            $bannerData['image_filename'] = null;
            $bannerData['image_mime_type'] = null;
        } else {
            // Store image directly in database as compressed base64
            $image = $request->file('image');
            $compressed = ImageHelper::compressForDatabase($image);
            
            $bannerData['image_path'] = null;
            $bannerData['image_data'] = $compressed['data'];
            $bannerData['image_filename'] = $compressed['original_name'];
            $bannerData['image_mime_type'] = $compressed['mime_type'];
        }

        // Create banner record
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
        // Base validation rules - only database and url options
        $rules = [
            'image_source' => 'required|in:database,url',
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
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ];

        $imageSource = $request->input('image_source', $banner->image_source);
        $hasNewImage = $request->hasFile('image');
        $hasNewUrl = $request->filled('image_url');
        $sourceChanged = $imageSource !== $banner->image_source;

        // Add conditional validation for new images
        if ($imageSource === 'url') {
            // URL is required if changing to URL source
            if ($sourceChanged || $hasNewUrl) {
                $rules['image_url'] = 'required|url|max:2048';
            }
        } else {
            // Database storage - image optional for updates unless source changed
            // Max 2MB to prevent MySQL packet errors (without GD compression)
            $rules['image'] = 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048';
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
            'image_source' => $imageSource,
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
            'display_order' => $validated['display_order'] ?? 0,
            'is_active' => $request->boolean('is_active', true),
        ];

        // Handle image based on source type
        if ($imageSource === Banner::SOURCE_URL) {
            if ($hasNewUrl || $sourceChanged) {
                $bannerData['image_path'] = $validated['image_url'] ?? $banner->image_path;
                $bannerData['image_data'] = null;
                $bannerData['image_filename'] = null;
                $bannerData['image_mime_type'] = null;
            }
        } else {
            // Database storage
            if ($hasNewImage) {
                $image = $request->file('image');
                $compressed = ImageHelper::compressForDatabase($image);
                
                $bannerData['image_path'] = null;
                $bannerData['image_data'] = $compressed['data'];
                $bannerData['image_filename'] = $compressed['original_name'];
                $bannerData['image_mime_type'] = $compressed['mime_type'];
                
                // Clear image cache
                Cache::forget("banner_image_{$banner->id}_{$banner->updated_at->timestamp}");
            } elseif ($sourceChanged && !$hasNewImage) {
                // Switching to database but no new image - require image
                return back()->withInput()->withErrors([
                    'image' => __('messages.image_required_for_database_storage'),
                ]);
            }
        }

        // Update banner record
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
        // Clear image cache if stored in database
        if ($banner->image_source === Banner::SOURCE_DATABASE) {
            Cache::forget("banner_image_{$banner->id}_{$banner->updated_at->timestamp}");
        }

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
}
