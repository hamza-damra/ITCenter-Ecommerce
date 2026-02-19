<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SiteSetting;
use App\Rules\ValidCategoryParent;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function __construct(
        protected ImageUploadService $imageService
    ) {}
    public function index()
    {
        $categories = Category::with('parent')
            ->latest()
            ->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $locale = app()->getLocale();
        $nameColumn = "name_{$locale}";
        
        // Fallback to English if the locale column doesn't exist
        $availableColumns = ['name_en', 'name_ar'];
        if (!in_array($nameColumn, $availableColumns)) {
            $nameColumn = 'name_en';
        }
        
        // Parent categories for carousel mode (regular parent dropdown)
        $parentCategories = Category::whereNull('parent_id')->orderBy($nameColumn)->get();
        
        // Nav parent categories for nav mode (only nav mode parents without parent)
        $navParentCategories = Category::where('display_mode', 'nav')
            ->whereNull('parent_id')
            ->orderBy('position')
            ->orderBy($nameColumn)
            ->get();

        return view('admin.categories.create', compact('parentCategories', 'navParentCategories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'name_he' => 'nullable|string|max:255',
            'parent_id' => ['nullable', 'exists:categories,id', new ValidCategoryParent()],
            'nav_type' => 'nullable|in:parent,child',
            'nav_parent_id' => ['nullable', 'exists:categories,id', new ValidCategoryParent()],
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'description_he' => 'nullable|string',
            'image' => 'nullable|url',
            'image_file' => 'nullable|file|image|mimes:jpeg,jpg,png,webp|max:2048',
            'image_source_type' => 'nullable|in:file,url',
            'icon' => 'nullable|string|max:255',
            'position' => 'nullable|integer|min:0',
            'display_mode' => 'nullable|in:carousel,nav',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        $validated['slug'] = $this->generateUniqueSlug($validated['name_en']);
        $validated['display_mode'] = $validated['display_mode'] ?? 'carousel';

        // Handle nav mode parent/child logic
        if ($validated['display_mode'] === 'nav') {
            if (isset($validated['nav_type']) && $validated['nav_type'] === 'child' && !empty($validated['nav_parent_id'])) {
                $validated['parent_id'] = $validated['nav_parent_id'];
            } else {
                $validated['parent_id'] = null; // Nav parent has no parent
            }
        }
        
        // Remove nav-specific fields before creating
        unset($validated['nav_type'], $validated['nav_parent_id'], $validated['image_file'], $validated['image_source_type']);

        // Handle image: file upload takes priority over URL
        $validated['image'] = $this->resolveImage($request, $validated);

        Category::create($validated);

        // Clear home page cache to reflect changes immediately
        $this->clearHomeCache();

        return redirect()->route('admin.categories.index')
            ->with('success', __('messages.category_created_successfully'));
    }

    public function edit(Category $category)
    {
        $locale = app()->getLocale();
        $nameColumn = "name_{$locale}";
        
        // Fallback to English if the locale column doesn't exist
        $availableColumns = ['name_en', 'name_ar'];
        if (!in_array($nameColumn, $availableColumns)) {
            $nameColumn = 'name_en';
        }
        
        // Parent categories for carousel mode (regular parent dropdown)
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy($nameColumn)
            ->get();
        
        // Nav parent categories for nav mode (only nav mode parents without parent, excluding current category)
        $navParentCategories = Category::where('display_mode', 'nav')
            ->whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('position')
            ->orderBy($nameColumn)
            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories', 'navParentCategories'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'name_he' => 'nullable|string|max:255',
            'parent_id' => ['nullable', 'exists:categories,id', new ValidCategoryParent($category->id)],
            'nav_type' => 'nullable|in:parent,child',
            'nav_parent_id' => ['nullable', 'exists:categories,id', new ValidCategoryParent($category->id)],
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'description_he' => 'nullable|string',
            'image' => 'nullable|url',
            'image_file' => 'nullable|file|image|mimes:jpeg,jpg,png,webp|max:2048',
            'image_source_type' => 'nullable|in:file,url',
            'icon' => 'nullable|string|max:255',
            'position' => 'nullable|integer|min:0',
            'display_mode' => 'nullable|in:carousel,nav',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|string|max:255',
        ]);

        $validated['slug'] = $this->generateUniqueSlug($validated['name_en'], $category->id);
        $validated['display_mode'] = $validated['display_mode'] ?? $category->display_mode;

        // Handle nav mode parent/child logic
        if ($validated['display_mode'] === 'nav') {
            if (isset($validated['nav_type']) && $validated['nav_type'] === 'child' && !empty($validated['nav_parent_id'])) {
                $validated['parent_id'] = $validated['nav_parent_id'];
            } else {
                $validated['parent_id'] = null; // Nav parent has no parent
            }
        }
        
        // Remove nav-specific fields before updating
        unset($validated['nav_type'], $validated['nav_parent_id'], $validated['image_file'], $validated['image_source_type']);

        // Handle image: file upload takes priority, then URL, then keep existing
        $validated['image'] = $this->resolveImage($request, $validated, $category);

        $category->update($validated);

        // Clear home page cache to reflect changes immediately
        $this->clearHomeCache();

        return redirect()->route('admin.categories.index')
            ->with('success', __('messages.category_updated_successfully'));
    }

    public function destroy(Category $category)
    {
        // Check if category has products assigned (directly or through sub-categories)
        $productCount = $category->allProducts()->count();
        
        if ($productCount > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', __('messages.category_has_products', ['count' => $productCount]));
        }

        $category->delete();

        // Clear home page cache to reflect changes immediately
        $this->clearHomeCache();

        return redirect()->route('admin.categories.index')
            ->with('success', __('messages.category_deleted_successfully'));
    }

    public function deleteAll(Request $request)
    {
        try {
            DB::beginTransaction();

            // Delete all categories
            $count = Category::count();
            Category::query()->delete();

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

    public function bulkDelete(Request $request)
    {
        try {
            $validated = $request->validate([
                'ids' => 'required|array|min:1',
                'ids.*' => 'required|integer|exists:categories,id'
            ]);

            DB::beginTransaction();

            // Delete selected categories
            $count = Category::whereIn('id', $validated['ids'])->delete();

            DB::commit();

            // Clear home page cache to reflect changes immediately
            $this->clearHomeCache();

            return response()->json([
                'success' => true,
                'message' => __('messages.selected_records_deleted_successfully', ['count' => $count]),
                'count' => $count
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteImage(Request $request, Category $category)
    {
        try {
            $rawImage = $category->getRawOriginal('image');
            if ($rawImage) {
                $this->imageService->delete($rawImage);
            }
            $category->update(['image' => null]);

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
     * Resolve image from file upload, URL, or keep existing.
     */
    private function resolveImage(Request $request, array $validated, ?Category $existingCategory = null): ?string
    {
        // Priority 1: File upload
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            return $this->imageService->upload($file, 'categories', $this->getImageUploadOptions());
        }

        // Priority 2: URL provided
        if (!empty($validated['image'])) {
            return $validated['image'];
        }

        // Priority 3: Keep existing image (on update)
        if ($existingCategory) {
            return $existingCategory->getRawOriginal('image');
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

    /**
     * Generate a unique slug for the category
     */
    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            // Include soft deleted categories to avoid conflicts
            $query = Category::withTrashed()->where('slug', $slug);
            
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            
            if (!$query->exists()) {
                break;
            }
            
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}

