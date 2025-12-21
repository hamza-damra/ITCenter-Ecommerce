<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromotionalAd;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class PromotionalAdController extends Controller
{
    /**
     * Display a listing of promotional ads.
     */
    public function index()
    {
        $promotionalAds = PromotionalAd::orderBy('position', 'asc')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return view('admin.promotional-ads.index', compact('promotionalAds'));
    }

    /**
     * Show the form for creating a new promotional ad.
     */
    public function create()
    {
        return view('admin.promotional-ads.create');
    }

    /**
     * Store a newly created promotional ad in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'position' => 'required|in:left,right',
            'link' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Store image directly in database as compressed base64
        $image = $request->file('image');
        $compressed = ImageHelper::compressForDatabase($image);

        // Create promotional ad record
        PromotionalAd::create([
            'image_source' => PromotionalAd::SOURCE_DATABASE,
            'image_path' => null,
            'image_data' => $compressed['data'],
            'image_filename' => $compressed['original_name'],
            'image_mime_type' => $compressed['mime_type'],
            'position' => $validated['position'],
            'link' => $validated['link'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->clearHomeCache();

        return redirect()->route('admin.promotional-ads.index')
            ->with('success', __('messages.promotional_ad_created_successfully'));
    }

    /**
     * Show the form for editing the specified promotional ad.
     */
    public function edit(PromotionalAd $promotionalAd)
    {
        return view('admin.promotional-ads.edit', compact('promotionalAd'));
    }

    /**
     * Update the specified promotional ad in storage.
     */
    public function update(Request $request, PromotionalAd $promotionalAd)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'position' => 'required|in:left,right',
            'link' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Prepare update data
        $updateData = [
            'position' => $validated['position'],
            'link' => $validated['link'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ];

        // Handle image update if new file provided
        if ($request->hasFile('image')) {
            // Store new image in database
            $image = $request->file('image');
            $compressed = ImageHelper::compressForDatabase($image);
            
            $updateData['image_source'] = PromotionalAd::SOURCE_DATABASE;
            $updateData['image_path'] = null;
            $updateData['image_data'] = $compressed['data'];
            $updateData['image_filename'] = $compressed['original_name'];
            $updateData['image_mime_type'] = $compressed['mime_type'];
            
            // Clear image cache
            Cache::forget("promotional_ad_image_{$promotionalAd->id}_{$promotionalAd->updated_at->timestamp}");
        }

        // Update promotional ad record
        $promotionalAd->update($updateData);

        $this->clearHomeCache();

        return redirect()->route('admin.promotional-ads.index')
            ->with('success', __('messages.promotional_ad_updated_successfully'));
    }

    /**
     * Remove the specified promotional ad from storage.
     */
    public function destroy(PromotionalAd $promotionalAd)
    {
        // Clear image cache if stored in database
        if ($promotionalAd->isImageInDatabase()) {
            Cache::forget("promotional_ad_image_{$promotionalAd->id}_{$promotionalAd->updated_at->timestamp}");
        }

        $promotionalAd->delete();

        $this->clearHomeCache();

        return redirect()->route('admin.promotional-ads.index')
            ->with('success', __('messages.promotional_ad_deleted_successfully'));
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
