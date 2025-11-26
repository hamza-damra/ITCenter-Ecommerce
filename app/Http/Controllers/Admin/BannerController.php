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
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
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
        ]);

        // Validate at least one title is provided
        if (empty($validated['title_en']) && empty($validated['title_ar']) && empty($validated['title_he'])) {
            return back()->withInput()->withErrors([
                'title_en' => __('messages.at_least_one_title_required'),
            ]);
        }

        // Handle file upload with unique filename
        $image = $request->file('image');
        $filename = $this->generateUniqueFilename($image);
        $path = $image->storeAs('banners', $filename, 'public');

        // Create banner record
        Banner::create([
            'image_path' => $path,
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
        ]);

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
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
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
        ]);

        // Validate at least one title is provided
        if (empty($validated['title_en']) && empty($validated['title_ar']) && empty($validated['title_he'])) {
            return back()->withInput()->withErrors([
                'title_en' => __('messages.at_least_one_title_required'),
            ]);
        }

        // Handle image update if new file provided
        $imagePath = $banner->image_path;
        if ($request->hasFile('image')) {
            // Delete old image
            if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
                Storage::disk('public')->delete($banner->image_path);
            }
            
            // Store new image
            $image = $request->file('image');
            $filename = $this->generateUniqueFilename($image);
            $imagePath = $image->storeAs('banners', $filename, 'public');
        }

        // Update banner record
        $banner->update([
            'image_path' => $imagePath,
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
        ]);

        $this->clearHomeCache();

        return redirect()->route('admin.banners.index')
            ->with('success', __('messages.banner_updated_successfully'));
    }

    /**
     * Remove the specified banner from storage.
     */
    public function destroy(Banner $banner)
    {
        // Delete associated image file
        if ($banner->image_path && Storage::disk('public')->exists($banner->image_path)) {
            Storage::disk('public')->delete($banner->image_path);
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
     * Clear home page cache for all locales.
     */
    private function clearHomeCache(): void
    {
        Cache::forget('home_page_data_ar');
        Cache::forget('home_page_data_en');
        Cache::forget('home_page_data_he');
    }
}
