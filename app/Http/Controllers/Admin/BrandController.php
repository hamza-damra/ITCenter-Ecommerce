<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\SiteSetting;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class BrandController extends Controller
{
    public function __construct(
        protected ImageUploadService $imageService
    ) {}
    public function index()
    {
        $brands = Brand::latest()->paginate(20);

        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'logo' => 'nullable|url',
            'logo_file' => 'nullable|file|image|mimes:jpeg,jpg,png,webp|max:' . SiteSetting::getValue('max_image_size_kb', 5120),
            'image_source_type' => 'nullable|in:file,url',
            'website' => 'nullable|url',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name_en']);

        // Handle logo: file upload takes priority over URL
        $validated['logo'] = $this->resolveLogo($request, $validated);
        unset($validated['logo_file'], $validated['image_source_type']);

        Brand::create($validated);

        // Clear home page cache to reflect changes immediately
        $this->clearHomeCache();

        return redirect()->route('admin.brands.index')
            ->with('success', __('messages.brand_created_successfully'));
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'logo' => 'nullable|url',
            'logo_file' => 'nullable|file|image|mimes:jpeg,jpg,png,webp|max:' . SiteSetting::getValue('max_image_size_kb', 5120),
            'image_source_type' => 'nullable|in:file,url',
            'website' => 'nullable|url',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name_en']);

        // Handle logo: file upload takes priority, then URL, then keep existing
        $validated['logo'] = $this->resolveLogo($request, $validated, $brand);
        unset($validated['logo_file'], $validated['image_source_type']);

        $brand->update($validated);

        // Clear home page cache to reflect changes immediately
        $this->clearHomeCache();

        return redirect()->route('admin.brands.index')
            ->with('success', __('messages.brand_updated_successfully'));
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();

        // Clear home page cache to reflect changes immediately
        $this->clearHomeCache();

        return redirect()->route('admin.brands.index')
            ->with('success', __('messages.brand_deleted_successfully'));
    }

    public function deleteAll(Request $request)
    {
        try {
            DB::beginTransaction();

            // Delete all brands
            $count = Brand::count();
            Brand::query()->delete();

            DB::commit();

            // Clear home page cache to reflect changes immediately
            $this->clearHomeCache();

            return response()->json([
                'success' => true,
                'message' => __('messages.all_records_deleted_successfully'),
                'count' => $count
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteImage(Request $request, Brand $brand)
    {
        try {
            $rawLogo = $brand->getRawOriginal('logo');
            if ($rawLogo) {
                $this->imageService->delete($rawLogo);
            }
            $brand->update(['logo' => null]);

            return response()->json([
                'success' => true,
                'message' => __('messages.image_deleted_successfully') ?? 'Image deleted successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.error_deleting_image') ?? 'Error deleting image.',
            ], 500);
        }
    }

    /**
     * Resolve logo from file upload, URL, or keep existing.
     */
    private function resolveLogo(Request $request, array $validated, ?Brand $existingBrand = null): ?string
    {
        // Priority 1: File upload
        if ($request->hasFile('logo_file')) {
            $file = $request->file('logo_file');
            return $this->imageService->upload($file, 'brands', $this->getImageUploadOptions());
        }

        // Priority 2: URL provided
        if (!empty($validated['logo'])) {
            return $validated['logo'];
        }

        // Priority 3: Keep existing logo (on update)
        if ($existingBrand) {
            return $existingBrand->getRawOriginal('logo');
        }

        return null;
    }

    /**
     * Get image upload options from site settings.
     */
    private function getImageUploadOptions(): array
    {
        return [
            'optimize' => true,
            'max_width' => SiteSetting::getValue('max_image_width', 1920),
            'max_height' => SiteSetting::getValue('max_image_height', 1080),
            'quality' => SiteSetting::getValue('image_quality', 80),
            'convert_to_webp' => (bool) SiteSetting::getValue('convert_to_webp', true),
        ];
    }

    /**
     * Clear home page cache for all locales
     */
    private function clearHomeCache()
    {
        Cache::forget('home_page_data_ar');
        Cache::forget('home_page_data_en');
        Cache::forget('home_page_data_he');
    }
}

