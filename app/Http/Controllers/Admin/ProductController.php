<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
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
        
        $categories = Category::active()->orderBy($nameColumn)->get();
        $brands = Brand::active()->orderBy($nameColumn)->get();

        return view('admin.products.create', compact('categories', 'brands'));
    }

    /**
     * Get category-specific attributes via AJAX
     */
    public function getCategoryAttributes($categoryId)
    {
        $category = Category::findOrFail($categoryId);
        
        $attributes = $category->attributes()
            ->where('is_filterable', true)
            ->where('is_active', true)
            ->with(['values' => function ($query) {
                $query->where('is_active', true)->orderBy('order');
            }])
            ->orderBy('order')
            ->get();

        return response()->json([
            'attributes' => $attributes->map(function ($attribute) {
                return [
                    'id' => $attribute->id,
                    'name' => $attribute->name,
                    'slug' => $attribute->slug,
                    'type' => $attribute->type,
                    'unit' => $attribute->unit,
                    'values' => $attribute->values->map(function ($value) {
                        return [
                            'id' => $value->id,
                            'value' => $value->value,
                            'slug' => $value->slug,
                            'color_code' => $value->color_code,
                        ];
                    }),
                ];
            }),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'name_he' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'main_image' => 'required|url',
            'additional_images' => 'nullable|string',
            'short_description_en' => 'nullable|string',
            'short_description_ar' => 'nullable|string',
            'short_description_he' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'description_he' => 'nullable|string',
            'search_keywords' => 'nullable|string',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'attribute_values' => 'nullable|array',
            'attribute_values.*' => 'exists:attribute_values,id',
        ]);

        // Validate that selected attribute values belong to category attributes
        if (!empty($validated['attribute_values'])) {
            $this->validateAttributeValues($validated['category_id'], $validated['attribute_values']);
        }

        // Handle checkboxes properly - convert to boolean
        $validated['is_active'] = $request->input('is_active') == '1';
        $validated['is_featured'] = $request->input('is_featured') == '1';
        $validated['is_new'] = $request->input('is_new') == '1';
        $validated['is_bestseller'] = $request->input('is_bestseller') == '1';
        $validated['is_special_offer'] = $request->input('is_special_offer') == '1';
        $validated['is_strong_offer'] = $request->input('is_strong_offer') == '1';

        DB::beginTransaction();
        try {
            $validated['slug'] = Str::slug($validated['name_en']);
            $validated['sku'] = 'SKU-' . strtoupper(Str::random(10));
            $validated['stock_status'] = $validated['stock_quantity'] > 0 ? 'in_stock' : 'out_of_stock';

            // Remove additional_images and attribute_values from validated data before creating product
            $additionalImages = $validated['additional_images'] ?? null;
            $attributeValues = $validated['attribute_values'] ?? [];
            unset($validated['additional_images'], $validated['attribute_values']);

            $product = Product::create($validated);

            // Sync attribute values
            if (!empty($attributeValues)) {
                $product->attributeValues()->sync($attributeValues);
            }

            // Create main image as first product image
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $validated['main_image'],
                'order' => 0,
                'is_primary' => true,
            ]);

            // Process additional images if provided
            if ($additionalImages) {
                $imageUrls = array_filter(array_map('trim', explode("\n", $additionalImages)));
                $order = 1;
                
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
                ->with('success', 'Product created successfully with ' . ($product->images->count()) . ' image(s)!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error creating product: ' . $e->getMessage());
        }
    }

    public function edit(Product $product)
    {
        $product->load(['images', 'attributeValues.attribute', 'category.attributes.values']);
        $locale = app()->getLocale();
        $nameColumn = "name_{$locale}";
        
        // Fallback to English if the locale column doesn't exist
        $availableColumns = ['name_en', 'name_ar'];
        if (!in_array($nameColumn, $availableColumns)) {
            $nameColumn = 'name_en';
        }
        
        $categories = Category::active()->orderBy($nameColumn)->get();
        $brands = Brand::active()->orderBy($nameColumn)->get();

        // Get category-specific attributes
        $categoryAttributes = [];
        if ($product->category) {
            $categoryAttributes = $product->category->attributes()
                ->where('is_filterable', true)
                ->where('is_active', true)
                ->with(['values' => function ($query) {
                    $query->where('is_active', true)->orderBy('order');
                }])
                ->orderBy('order')
                ->get();
        }

        // Get selected attribute value IDs
        $selectedAttributeValues = $product->attributeValues->pluck('id')->toArray();

        return view('admin.products.edit', compact('product', 'categories', 'brands', 'categoryAttributes', 'selectedAttributeValues'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'name_he' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'main_image' => 'required|url',
            'additional_images' => 'nullable|string',
            'short_description_en' => 'nullable|string',
            'short_description_ar' => 'nullable|string',
            'short_description_he' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'description_he' => 'nullable|string',
            'search_keywords' => 'nullable|string',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'attribute_values' => 'nullable|array',
            'attribute_values.*' => 'exists:attribute_values,id',
        ]);

        // Validate that selected attribute values belong to category attributes
        if (!empty($validated['attribute_values'])) {
            $this->validateAttributeValues($validated['category_id'], $validated['attribute_values']);
        }

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
                $validated['slug'] = Str::slug($validated['name_en']);
            }
            $validated['stock_status'] = $validated['stock_quantity'] > 0 ? 'in_stock' : 'out_of_stock';

            // Remove additional_images and attribute_values from validated data before updating product
            $additionalImages = $validated['additional_images'] ?? null;
            $attributeValues = $validated['attribute_values'] ?? [];
            unset($validated['additional_images'], $validated['attribute_values']);

            $product->update($validated);

            // Sync attribute values
            $product->attributeValues()->sync($attributeValues);

            // Delete existing images
            ProductImage::where('product_id', $product->id)->delete();

            // Create main image as first product image
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $validated['main_image'],
                'order' => 0,
                'is_primary' => true,
            ]);

            // Process additional images if provided
            if ($additionalImages) {
                $imageUrls = array_filter(array_map('trim', explode("\n", $additionalImages)));
                $order = 1;
                
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
                ->with('success', 'Product updated successfully with ' . ($product->images()->count()) . ' image(s)!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error updating product: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();

        // Clear home page cache to reflect changes immediately
        $this->clearHomeCache();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
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
     * Validate that selected attribute values belong to category attributes
     */
    private function validateAttributeValues($categoryId, array $attributeValueIds)
    {
        $category = Category::findOrFail($categoryId);
        
        // Get all valid attribute value IDs for this category
        $validAttributeValueIds = AttributeValue::whereHas('attribute', function ($query) use ($category) {
            $query->whereHas('categories', function ($q) use ($category) {
                $q->where('categories.id', $category->id);
            });
        })->pluck('id')->toArray();

        // Check if all selected values are valid
        $invalidValues = array_diff($attributeValueIds, $validAttributeValueIds);
        
        if (!empty($invalidValues)) {
            throw ValidationException::withMessages([
                'attribute_values' => ['Selected attribute values do not belong to the product\'s category attributes.'],
            ]);
        }
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
