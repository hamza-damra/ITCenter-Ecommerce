<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Brand;
use App\Models\HomeSection;
use App\Models\SiteSetting;
use App\Services\ImageUploadService;
use App\Services\FilterResolutionService;
use App\Models\Filter;
use App\Models\ProductFilterNumericValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function __construct(
        protected ImageUploadService $imageService
    ) {}
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images']);

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name_en', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('name_ar', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('name_he', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('sku', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('search_keywords', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Status filter
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status === 'active');
        }

        // Stock filter
        if ($request->has('stock') && $request->stock != '') {
            if ($request->stock === 'low') {
                $query->where('stock_quantity', '>', 0)
                      ->where('stock_quantity', '<=', 5);
            } elseif ($request->stock === 'out') {
                $query->where('stock_quantity', 0);
            }
        }

        // Featured filter
        if ($request->has('featured') && $request->featured != '') {
            $query->where('is_featured', $request->featured === '1');
        }

        // New Product filter
        if ($request->has('new') && $request->new != '') {
            $query->where('is_new', $request->new === '1');
        }

        // Bestseller filter
        if ($request->has('bestseller') && $request->bestseller != '') {
            $query->where('is_bestseller', $request->bestseller === '1');
        }

        // Special Offer filter
        if ($request->has('special_offer') && $request->special_offer != '') {
            $query->where('is_special_offer', $request->special_offer === '1');
        }

        // Apply filter based on request parameter
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'recent':
                    // Recent products (latest)
                    $query->latest();
                    break;

                case 'top_rated':
                    // Top rated products
                    $query->withAvg('reviews', 'rating')
                          ->where('reviews_count', '>', 0)
                          ->orderByDesc('reviews_avg_rating');
                    break;

                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $products = $query->paginate(20);

        // Preserve all parameters in pagination
        $products->appends($request->except('page'));

        return view('admin.products.index', compact('products'));
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

        $categories = Category::active()->with('specTemplate.activeFields')->orderBy($nameColumn)->get();
        $brands = Brand::active()->orderBy($nameColumn)->get();
        $tags = \App\Models\Tag::active()->ordered()->get();

        // Input limits for frontend validation
        $inputLimits = [
            'name' => ProductRequest::NAME_MAX_LENGTH,
            'short_description' => ProductRequest::SHORT_DESCRIPTION_MAX_LENGTH,
            'description' => ProductRequest::DESCRIPTION_MAX_LENGTH,
            'search_keywords' => ProductRequest::SEARCH_KEYWORDS_MAX_LENGTH,
        ];

        $customSections = HomeSection::customProductSections()->active()->ordered()->get();

        return view('admin.products.create', compact('categories', 'brands', 'tags', 'inputLimits', 'customSections'));
    }

    public function store(ProductRequest $request)
    {
        $validated = $request->validated();

        // Handle checkboxes properly - convert to boolean
        $validated['is_active'] = $request->input('is_active') == '1';
        $validated['is_featured'] = $request->input('is_featured') == '1';
        $validated['is_new'] = $request->input('is_new') == '1';
        $validated['is_bestseller'] = $request->input('is_bestseller') == '1';
        $validated['is_special_offer'] = $request->input('is_special_offer') == '1';
        $validated['is_strong_offer'] = $request->input('is_strong_offer') == '1';

        DB::beginTransaction();
        try {
            $validated['slug'] = $this->generateUniqueSlug($validated['name_en']);
            $validated['sku'] = 'SKU-' . strtoupper(Str::random(10));
            $validated['stock_status'] = $validated['stock_quantity'] > 0 ? 'in_stock' : 'out_of_stock';

            // Remove additional_images and spec_values from validated data before creating product
            $additionalImages = $validated['additional_images'] ?? null;
            $specValues = $validated['spec_values'] ?? [];
            $filterOptions = $request->input('filter_options', []);
            $filterNumericValues = $request->input('filter_numeric_values', []);
            unset($validated['additional_images'], $validated['spec_values']);

            $product = Product::create($validated);

            // Sync specification values
            if (!empty($specValues)) {
                $product->syncSpecValues($specValues);
            }

            // Sync filter option values
            $this->syncProductFilterValues($product, $filterOptions, $filterNumericValues);

            // Handle tags - both existing and new
            $tagIds = $request->input('tags', []);

            // Create new tags if provided (from comma-separated string)
            if ($request->filled('new_tags')) {
                $newTagIds = $this->createNewTags($request->input('new_tags'));
                $tagIds = array_merge($tagIds, $newTagIds);
            }

            // Create new tags from array (from tag input component)
            if ($request->has('new_tags_array')) {
                $newTagIds = $this->createNewTagsFromArray($request->input('new_tags_array', []));
                $tagIds = array_merge($tagIds, $newTagIds);
            }

            // Sync all tags
            $product->tags()->sync($tagIds);

            // Sync home sections
            $homeSectionIds = $request->input('home_sections', []);
            $product->homeSections()->sync($homeSectionIds);

            // Determine main image path (file upload or URL)
            $mainImagePath = $this->resolveMainImage($request, $validated);
            if ($mainImagePath) {
                $product->update(['main_image' => $mainImagePath]);
            }

            // Create main image as first product image
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $mainImagePath ?? $validated['main_image'] ?? '',
                'order' => 0,
                'is_primary' => true,
            ]);

            // Process additional images (file uploads)
            $order = 1;
            if ($request->hasFile('additional_images_files')) {
                $uploadOptions = $this->getImageUploadOptions();
                foreach ($request->file('additional_images_files') as $file) {
                    $path = $this->imageService->upload($file, 'products', $uploadOptions);
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'order' => $order++,
                        'is_primary' => false,
                    ]);
                }
            }

            // Process additional images (URLs - legacy support)
            if ($additionalImages) {
                $imageUrls = array_filter(array_map('trim', explode("\n", $additionalImages)));

                foreach ($imageUrls as $imageUrl) {
                    if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $imageUrl,
                            'order' => $order++,
                            'is_primary' => false,
                        ]);
                    }
                }
            }

            DB::commit();

            // Clear home page cache to reflect changes immediately
            $this->clearHomeCache();

            return redirect()->route('admin.products.index')
                ->with('success', __('messages.product_created_successfully', ['count' => $product->images->count()]));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', __('messages.error_creating_product', ['error' => $e->getMessage()]));
        }
    }

    public function edit(Product $product)
    {
        $product->load(['images', 'category.specTemplate.activeFields', 'tags', 'specValues.field', 'homeSections', 'filterOptions', 'filterNumericValues']);
        $locale = app()->getLocale();
        $nameColumn = "name_{$locale}";

        // Fallback to English if the locale column doesn't exist
        $availableColumns = ['name_en', 'name_ar'];
        if (!in_array($nameColumn, $availableColumns)) {
            $nameColumn = 'name_en';
        }

        $categories = Category::active()->with('specTemplate.activeFields')->orderBy($nameColumn)->get();
        $brands = Brand::active()->orderBy($nameColumn)->get();
        $tags = \App\Models\Tag::active()->ordered()->get();

        // Get spec values as [field_id => value]
        $specValues = $product->specValues->pluck('value', 'spec_field_id')->toArray();

        // Get spec fields for current category
        $specFields = $product->category?->specTemplate?->activeFields ?? collect();

        // Input limits for frontend validation
        $inputLimits = [
            'name' => ProductRequest::NAME_MAX_LENGTH,
            'short_description' => ProductRequest::SHORT_DESCRIPTION_MAX_LENGTH,
            'description' => ProductRequest::DESCRIPTION_MAX_LENGTH,
            'search_keywords' => ProductRequest::SEARCH_KEYWORDS_MAX_LENGTH,
        ];

        $customSections = HomeSection::customProductSections()->active()->ordered()->get();
        $selectedHomeSections = $product->homeSections->pluck('id')->toArray();

        // Get category-specific filters
        $categoryFilters = [];
        $selectedFilterOptions = [];
        $selectedFilterNumericValues = [];
        if ($product->category) {
            $filterResolutionService = app(FilterResolutionService::class);
            $resolvedFilters = $filterResolutionService->getFiltersForCategory($product->category);
            $categoryFilters = $resolvedFilters->map(function ($filter) {
                return [
                    'id' => $filter->id,
                    'title' => $filter->title,
                    'type' => $filter->type,
                    'options' => $filter->activeOptions->map(function ($opt) {
                        return ['id' => $opt->id, 'label' => $opt->label, 'color_code' => $opt->color_code];
                    })->toArray(),
                ];
            })->toArray();
            $selectedFilterOptions = $product->filterOptions->pluck('id')->toArray();
            $selectedFilterNumericValues = $product->filterNumericValues->pluck('numeric_value', 'filter_id')->toArray();
        }

        return view('admin.products.edit', compact('product', 'categories', 'brands', 'tags', 'specValues', 'specFields', 'inputLimits', 'customSections', 'selectedHomeSections', 'categoryFilters', 'selectedFilterOptions', 'selectedFilterNumericValues'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        $validated = $request->validated();

        // Handle checkboxes properly - convert to boolean
        $validated['is_active'] = $request->input('is_active') == '1';
        $validated['is_featured'] = $request->input('is_featured') == '1';
        $validated['is_new'] = $request->input('is_new') == '1';
        $validated['is_bestseller'] = $request->input('is_bestseller') == '1';
        $validated['is_special_offer'] = $request->input('is_special_offer') == '1';
        $validated['is_strong_offer'] = $request->input('is_strong_offer') == '1';

        DB::beginTransaction();
        try {
            // Only regenerate slug if name_en has changed
            if ($product->name_en !== $validated['name_en']) {
                $validated['slug'] = $this->generateUniqueSlug($validated['name_en'], $product->id);
            }
            $validated['stock_status'] = $validated['stock_quantity'] > 0 ? 'in_stock' : 'out_of_stock';

            // Remove additional_images and spec_values from validated data before updating product
            $additionalImages = $validated['additional_images'] ?? null;
            $specValues = $validated['spec_values'] ?? [];
            $filterOptions = $request->input('filter_options', []);
            $filterNumericValues = $request->input('filter_numeric_values', []);
            unset($validated['additional_images'], $validated['spec_values'], $validated['main_image']);

            $product->update($validated);

            // Sync specification values
            $product->syncSpecValues($specValues);

            // Sync filter option values
            $this->syncProductFilterValues($product, $filterOptions, $filterNumericValues);

            // Handle tags - both existing and new
            $tagIds = $request->input('tags', []);

            // Create new tags if provided (from comma-separated string)
            if ($request->filled('new_tags')) {
                $newTagIds = $this->createNewTags($request->input('new_tags'));
                $tagIds = array_merge($tagIds, $newTagIds);
            }

            // Create new tags from array (from tag input component)
            if ($request->has('new_tags_array')) {
                $newTagIds = $this->createNewTagsFromArray($request->input('new_tags_array', []));
                $tagIds = array_merge($tagIds, $newTagIds);
            }

            // Sync all tags
            $product->tags()->sync($tagIds);

            // Sync home sections
            $homeSectionIds = $request->input('home_sections', []);
            $product->homeSections()->sync($homeSectionIds);

            // Resolve main image (file upload, URL, or keep existing)
            $mainImagePath = $this->resolveMainImage($request, $validated, $product);
            if ($mainImagePath && $mainImagePath !== $product->getRawOriginal('main_image')) {
                $product->update(['main_image' => $mainImagePath]);
            } else {
                $mainImagePath = $mainImagePath ?? $product->getRawOriginal('main_image');
            }

            // Delete existing product images (the HasUploadedImage trait handles file cleanup)
            ProductImage::where('product_id', $product->id)->each(function ($img) {
                $img->delete();
            });

            // Create main image as first product image
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $mainImagePath ?? '',
                'order' => 0,
                'is_primary' => true,
            ]);

            // Process additional images (file uploads)
            $order = 1;
            if ($request->hasFile('additional_images_files')) {
                $uploadOptions = $this->getImageUploadOptions();
                foreach ($request->file('additional_images_files') as $file) {
                    $path = $this->imageService->upload($file, 'products', $uploadOptions);
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'order' => $order++,
                        'is_primary' => false,
                    ]);
                }
            }

            // Process additional images (URLs - legacy support)
            if ($additionalImages) {
                $imageUrls = array_filter(array_map('trim', explode("\n", $additionalImages)));

                foreach ($imageUrls as $imageUrl) {
                    if (filter_var($imageUrl, FILTER_VALIDATE_URL)) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $imageUrl,
                            'order' => $order++,
                            'is_primary' => false,
                        ]);
                    }
                }
            }

            DB::commit();

            // Clear home page cache to reflect changes immediately
            $this->clearHomeCache();

            return redirect()->route('admin.products.index')
                ->with('success', __('messages.product_updated_successfully', ['count' => $product->images()->count()]));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', __('messages.error_updating_product', ['error' => $e->getMessage()]));
        }
    }

    public function deleteProductImage(Request $request, Product $product)
    {
        $request->validate([
            'image_id' => 'required|integer',
            'type' => 'required|in:main,additional',
        ]);

        $imageId = $request->input('image_id');
        $type = $request->input('type');

        try {
            if ($type === 'main') {
                $image = ProductImage::where('product_id', $product->id)
                    ->where('id', $imageId)
                    ->where('is_primary', true)
                    ->firstOrFail();

                $image->delete();

                $product->update(['main_image' => null]);

                return response()->json([
                    'success' => true,
                    'message' => __('messages.main_image_deleted_successfully') ?? 'Main image deleted successfully.',
                ]);
            } else {
                $image = ProductImage::where('product_id', $product->id)
                    ->where('id', $imageId)
                    ->where('is_primary', false)
                    ->firstOrFail();

                $image->delete();

                return response()->json([
                    'success' => true,
                    'message' => __('messages.image_deleted_successfully') ?? 'Image deleted successfully.',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.error_deleting_image') ?? 'Error deleting image.',
            ], 500);
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();

        // Clear home page cache to reflect changes immediately
        $this->clearHomeCache();

        return redirect()->route('admin.products.index')
            ->with('success', __('messages.product_deleted_successfully'));
    }

    public function deleteAll(Request $request)
    {
        try {
            DB::beginTransaction();

            // Delete all products (this will also soft delete them if using SoftDeletes)
            $count = Product::count();
            Product::query()->delete();

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
                'ids.*' => 'required|integer|exists:products,id'
            ]);

            DB::beginTransaction();

            // Delete selected products (this will also soft delete them if using SoftDeletes)
            $count = Product::whereIn('id', $validated['ids'])->delete();

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

    /**
     * Generate a unique slug, appending -2, -3, etc. if the base slug already exists.
     *
     * @param string $name
     * @param int|null $excludeId Product ID to exclude (for updates)
     * @return string
     */
    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 2;

        while (true) {
            $query = Product::withTrashed()->where('slug', $slug);
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

    private function resolveMainImage(ProductRequest $request, array $validated, ?Product $existingProduct = null): ?string
    {
        // Priority 1: File upload
        if ($request->hasFile('main_image_file')) {
            $file = $request->file('main_image_file');
            return $this->imageService->upload($file, 'products', $this->getImageUploadOptions());
        }

        // Priority 2: URL provided
        if (!empty($validated['main_image'])) {
            return $validated['main_image'];
        }

        // Priority 3: Keep existing image (on update)
        if ($existingProduct) {
            return $existingProduct->getRawOriginal('main_image');
        }

        return null;
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
     * Create new tags from array (from tag input component)
     * Returns array of created tag IDs
     */
    private function createNewTagsFromArray(array $tagNames): array
    {
        $tagIds = [];

        foreach ($tagNames as $tagName) {
            $tagName = trim($tagName);
            if (empty($tagName)) continue;

            // Check if tag already exists
            $existingTag = \App\Models\Tag::where('name_en', $tagName)
                ->orWhere('name_ar', $tagName)
                ->first();

            if ($existingTag) {
                $tagIds[] = $existingTag->id;
            } else {
                // Create new tag
                $newTag = \App\Models\Tag::create([
                    'name_en' => $tagName,
                    'name_ar' => $tagName,
                    'slug' => \Illuminate\Support\Str::slug($tagName),
                    'color' => $this->generateRandomColor(),
                    'is_active' => true,
                ]);
                $tagIds[] = $newTag->id;
            }
        }

        return $tagIds;
    }

    /**
     * Create new tags from comma-separated string
     * Returns array of created tag IDs
     */
    private function createNewTags(string $tagsString): array
    {
        $tagIds = [];
        $tagNames = array_filter(array_map('trim', explode(',', $tagsString)));

        foreach ($tagNames as $tagName) {
            if (empty($tagName)) continue;

            // Check if tag already exists
            $existingTag = \App\Models\Tag::where('name_en', $tagName)
                ->orWhere('name_ar', $tagName)
                ->first();

            if ($existingTag) {
                $tagIds[] = $existingTag->id;
            } else {
                // Create new tag
                $newTag = \App\Models\Tag::create([
                    'name_en' => $tagName,
                    'name_ar' => $tagName,
                    'slug' => \Illuminate\Support\Str::slug($tagName),
                    'color' => $this->generateRandomColor(),
                    'is_active' => true,
                ]);
                $tagIds[] = $newTag->id;
            }
        }

        return $tagIds;
    }

    /**
     * Generate a random color for new tags
     */
    private function generateRandomColor(): string
    {
        $colors = [
            '#ef4444', '#f97316', '#f59e0b', '#eab308', '#84cc16',
            '#22c55e', '#10b981', '#14b8a6', '#06b6d4', '#0ea5e9',
            '#3b82f6', '#6366f1', '#8b5cf6', '#a855f7', '#d946ef',
            '#ec4899', '#f43f5e'
        ];
        return $colors[array_rand($colors)];
    }

    /**
     * Sync product filter option values and numeric filter values.
     */
    private function syncProductFilterValues(Product $product, array $filterOptions, array $filterNumericValues): void
    {
        // Flatten all option IDs from filter_options[filterId][] groups
        $allOptionIds = [];
        foreach ($filterOptions as $filterId => $optionIds) {
            if (is_array($optionIds)) {
                $allOptionIds = array_merge($allOptionIds, $optionIds);
            }
        }
        $product->filterOptions()->sync(array_filter(array_unique($allOptionIds)));

        // Delete existing numeric values and re-insert
        $product->filterNumericValues()->delete();
        foreach ($filterNumericValues as $filterId => $value) {
            if ($value !== null && $value !== '') {
                ProductFilterNumericValue::create([
                    'product_id' => $product->id,
                    'filter_id' => (int) $filterId,
                    'numeric_value' => (float) $value,
                ]);
            }
        }
    }
}
