<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromotionalAd;
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
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'position' => 'required|in:left,right',
            'link' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Handle file upload with unique filename
        $image = $request->file('image');
        $filename = $this->generateUniqueFilename($image);
        $path = $image->storeAs('promotional-ads', $filename, 'public');

        // Create promotional ad record
        PromotionalAd::create([
            'image_path' => $path,
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
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:5120',
            'position' => 'required|in:left,right',
            'link' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Handle image update if new file provided
        $imagePath = $promotionalAd->image_path;
        if ($request->hasFile('image')) {
            // Delete old image
            if ($promotionalAd->image_path && Storage::disk('public')->exists($promotionalAd->image_path)) {
                Storage::disk('public')->delete($promotionalAd->image_path);
            }
            
            // Store new image
            $image = $request->file('image');
            $filename = $this->generateUniqueFilename($image);
            $imagePath = $image->storeAs('promotional-ads', $filename, 'public');
        }

        // Update promotional ad record
        $promotionalAd->update([
            'image_path' => $imagePath,
            'position' => $validated['position'],
            'link' => $validated['link'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        $this->clearHomeCache();

        return redirect()->route('admin.promotional-ads.index')
            ->with('success', __('messages.promotional_ad_updated_successfully'));
    }

    /**
     * Remove the specified promotional ad from storage.
     */
    public function destroy(PromotionalAd $promotionalAd)
    {
        // Delete associated image file
        if ($promotionalAd->image_path && Storage::disk('public')->exists($promotionalAd->image_path)) {
            Storage::disk('public')->delete($promotionalAd->image_path);
        }

        $promotionalAd->delete();

        $this->clearHomeCache();

        return redirect()->route('admin.promotional-ads.index')
            ->with('success', __('messages.promotional_ad_deleted_successfully'));
    }

    /**
     * Generate a unique filename for uploaded images.
     */
    private function generateUniqueFilename($file): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('Y-m-d_H-i-s');
        $random = Str::random(8);
        
        return "promotional_ad_{$timestamp}_{$random}.{$extension}";
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
