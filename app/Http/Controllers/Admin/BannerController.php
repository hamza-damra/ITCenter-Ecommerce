<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

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
        // Base validation rules
        $rules = [
            'image_source' => 'required|in:file,database,url',
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
            // Both 'file' and 'database' sources require file upload
            $rules['image'] = 'required|image|mimes:jpg,jpeg,png,gif,webp|max:10240'; // Max 10MB for database storage
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
        switch ($imageSource) {
            case Banner::SOURCE_URL:
                // Store the external URL
                $bannerData['image_path'] = $validated['image_url'];
                $bannerData['image_data'] = null;
                $bannerData['image_filename'] = null;
                $bannerData['image_mime_type'] = null;
                break;

            case Banner::SOURCE_DATABASE:
                // Store image directly in database as base64
                $image = $request->file('image');
                $imageContent = file_get_contents($image->getRealPath());
                
                $bannerData['image_path'] = null;
                $bannerData['image_data'] = base64_encode($imageContent);
                $bannerData['image_filename'] = $image->getClientOriginalName();
                $bannerData['image_mime_type'] = $image->getMimeType();
                break;

            case Banner::SOURCE_FILE:
            default:
                // Traditional file storage
                $image = $request->file('image');
                $filename = $this->generateUniqueFilename($image);
                $path = $image->storeAs('banners', $filename, 'public');
                
                $bannerData['image_path'] = $path;
                $bannerData['image_data'] = null;
                $bannerData['image_filename'] = $image->getClientOriginalName();
                $bannerData['image_mime_type'] = $image->getMimeType();
                break;
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
        // Base validation rules
        $rules = [
            'image_source' => 'required|in:file,database,url',
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

        // Add conditional validation for new images
        if ($imageSource === 'url') {
            // URL is required if changing to URL source or if current is URL and changing it
            if ($hasNewUrl || $banner->image_source !== 'url') {
                $rules['image_url'] = $hasNewUrl ? 'required|url|max:2048' : 'nullable|url|max:2048';
            }
        } else {
            // File upload is optional for updates (keep existing if not provided)
            $rules['image'] = 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:10240';
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

        // Track if we need to clean up old file
        $oldFilePath = null;
        if ($banner->image_source === Banner::SOURCE_FILE && $banner->image_path) {
            $oldFilePath = $banner->image_path;
        }

        // Handle image based on source type
        $sourceChanged = $imageSource !== $banner->image_source;

        switch ($imageSource) {
            case Banner::SOURCE_URL:
                if ($hasNewUrl || $sourceChanged) {
                    $bannerData['image_path'] = $validated['image_url'] ?? $banner->image_path;
                    $bannerData['image_data'] = null;
                    $bannerData['image_filename'] = null;
                    $bannerData['image_mime_type'] = null;
                    
                    // Clean up old file if source changed from file
                    if ($oldFilePath && $sourceChanged) {
                        $this->deleteOldImage($oldFilePath);
                    }
                }
                break;

            case Banner::SOURCE_DATABASE:
                if ($hasNewImage) {
                    $image = $request->file('image');
                    $imageContent = file_get_contents($image->getRealPath());
                    
                    $bannerData['image_path'] = null;
                    $bannerData['image_data'] = base64_encode($imageContent);
                    $bannerData['image_filename'] = $image->getClientOriginalName();
                    $bannerData['image_mime_type'] = $image->getMimeType();
                    
                    // Clean up old file if source changed from file
                    if ($oldFilePath) {
                        $this->deleteOldImage($oldFilePath);
                    }
                    
                    // Clear image cache
                    Cache::forget("banner_image_{$banner->id}_{$banner->updated_at->timestamp}");
                } elseif ($sourceChanged && !$hasNewImage) {
                    // Switching to database but no new image - require image
                    return back()->withInput()->withErrors([
                        'image' => __('messages.image_required_for_database_storage'),
                    ]);
                }
                break;

            case Banner::SOURCE_FILE:
            default:
                if ($hasNewImage) {
                    // Delete old image if exists
                    if ($oldFilePath) {
                        $this->deleteOldImage($oldFilePath);
                    }
                    
                    // Store new image
                    $image = $request->file('image');
                    $filename = $this->generateUniqueFilename($image);
                    $path = $image->storeAs('banners', $filename, 'public');
                    
                    $bannerData['image_path'] = $path;
                    $bannerData['image_data'] = null;
                    $bannerData['image_filename'] = $image->getClientOriginalName();
                    $bannerData['image_mime_type'] = $image->getMimeType();
                } elseif ($sourceChanged && !$hasNewImage) {
                    // Switching to file but no new image - require image
                    return back()->withInput()->withErrors([
                        'image' => __('messages.image_required_for_file_storage'),
                    ]);
                }
                break;
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
        // Delete associated image file if stored as file
        if ($banner->image_source === Banner::SOURCE_FILE && $banner->image_path) {
            $this->deleteOldImage($banner->image_path);
        }

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
     * Generate a unique filename for uploaded images.
     */
    private function generateUniqueFilename($file): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('Y-m-d_H-i-s');
        $random = Str::random(8);
        
        return "banner_{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Delete old image from storage.
     */
    private function deleteOldImage(string $path): void
    {
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
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
