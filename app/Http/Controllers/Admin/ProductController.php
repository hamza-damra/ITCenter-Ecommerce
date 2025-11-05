<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'brand', 'images']);

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
        
        // Preserve filter parameter in pagination
        if ($request->has('filter')) {
            $products->appends(['filter' => $request->filter]);
        }

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
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_bestseller' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $validated['slug'] = Str::slug($validated['name_en']);
            $validated['sku'] = 'SKU-' . strtoupper(Str::random(10));
            $validated['stock_status'] = $validated['stock_quantity'] > 0 ? 'in_stock' : 'out_of_stock';

            // Remove additional_images from validated data before creating product
            $additionalImages = $validated['additional_images'] ?? null;
            unset($validated['additional_images']);

            $product = Product::create($validated);

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
        $product->load('images');
        $locale = app()->getLocale();
        $nameColumn = "name_{$locale}";
        
        // Fallback to English if the locale column doesn't exist
        $availableColumns = ['name_en', 'name_ar'];
        if (!in_array($nameColumn, $availableColumns)) {
            $nameColumn = 'name_en';
        }
        
        $categories = Category::active()->orderBy($nameColumn)->get();
        $brands = Brand::active()->orderBy($nameColumn)->get();

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
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
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_bestseller' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            // Only regenerate slug if name_en has changed
            if ($product->name_en !== $validated['name_en']) {
                $validated['slug'] = Str::slug($validated['name_en']);
            }
            $validated['stock_status'] = $validated['stock_quantity'] > 0 ? 'in_stock' : 'out_of_stock';

            // Remove additional_images from validated data before updating product
            $additionalImages = $validated['additional_images'] ?? null;
            unset($validated['additional_images']);

            $product->update($validated);

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
     * Clear home page cache for all locales
     */
    private function clearHomeCache()
    {
        Cache::forget('home_page_data_ar');
        Cache::forget('home_page_data_en');
        Cache::forget('home_page_data_he');
    }
}
