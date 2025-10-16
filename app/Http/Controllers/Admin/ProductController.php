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

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'brand', 'images'])
            ->latest()
            ->paginate(20);

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $locale = app()->getLocale();
        $nameColumn = "name_{$locale}";
        
        $categories = Category::active()->orderBy($nameColumn)->get();
        $brands = Brand::active()->orderBy($nameColumn)->get();

        return view('admin.products.create', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'main_image' => 'required|url',
            'additional_images' => 'nullable|string',
            'short_description_en' => 'nullable|string',
            'short_description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
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
        
        $categories = Category::active()->orderBy($nameColumn)->get();
        $brands = Brand::active()->orderBy($nameColumn)->get();

        return view('admin.products.edit', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'main_image' => 'required|url',
            'additional_images' => 'nullable|string',
            'short_description_en' => 'nullable|string',
            'short_description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'description_ar' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'is_new' => 'boolean',
            'is_bestseller' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $validated['slug'] = Str::slug($validated['name_en']);
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

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
