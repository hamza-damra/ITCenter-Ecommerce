<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Brand;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    use ApiResponses;

    /**
     * Display a listing of products
     */
    public function index(Request $request)
    {
        try {
            $query = Product::with(['category', 'brand', 'images']);

            // Search filter
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name_en', 'like', "%{$search}%")
                      ->orWhere('name_ar', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
                });
            }

            // Category filter
            if ($request->has('category_id')) {
                $query->where('category_id', $request->category_id);
            }

            // Brand filter
            if ($request->has('brand_id')) {
                $query->where('brand_id', $request->brand_id);
            }

            // Status filter
            if ($request->has('is_active')) {
                $query->where('is_active', $request->is_active);
            }

            // Stock status filter
            if ($request->has('stock_status')) {
                $query->where('stock_status', $request->stock_status);
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $perPage = $request->get('per_page', 20);
            $products = $query->paginate($perPage);

            return $this->paginatedResponse($products, 'Products retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve products', 500);
        }
    }

    /**
     * Store a newly created product
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name_en' => 'required|string|max:255',
                'name_ar' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'brand_id' => 'nullable|exists:brands,id',
                'price' => 'required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0|lt:price',
                'stock_quantity' => 'required|integer|min:0',
                'main_image' => 'required|url',
                'additional_images' => 'nullable|array',
                'additional_images.*' => 'url',
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

                // Remove additional_images from validated data
                $additionalImages = $validated['additional_images'] ?? [];
                unset($validated['additional_images']);

                $product = Product::create($validated);

                // Create main image
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $validated['main_image'],
                    'order' => 0,
                    'is_primary' => true,
                ]);

                // Create additional images
                if (!empty($additionalImages)) {
                    $order = 1;
                    foreach ($additionalImages as $imageUrl) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $imageUrl,
                            'order' => $order++,
                            'is_primary' => false,
                        ]);
                    }
                }

                DB::commit();

                $product->load(['category', 'brand', 'images']);
                return $this->createdResponse($product, 'Product created successfully');
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->errorResponse('Failed to create product: ' . $e->getMessage(), 500);
            }
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        }
    }

    /**
     * Display the specified product
     */
    public function show(Product $product)
    {
        try {
            $product->load(['category', 'brand', 'images', 'attributes', 'reviews']);
            return $this->successResponse($product, 'Product retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve product', 500);
        }
    }

    /**
     * Update the specified product
     */
    public function update(Request $request, Product $product)
    {
        try {
            $validated = $request->validate([
                'name_en' => 'sometimes|required|string|max:255',
                'name_ar' => 'sometimes|required|string|max:255',
                'category_id' => 'sometimes|required|exists:categories,id',
                'brand_id' => 'nullable|exists:brands,id',
                'price' => 'sometimes|required|numeric|min:0',
                'sale_price' => 'nullable|numeric|min:0',
                'stock_quantity' => 'sometimes|required|integer|min:0',
                'main_image' => 'sometimes|required|url',
                'additional_images' => 'nullable|array',
                'additional_images.*' => 'url',
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
                if (isset($validated['name_en'])) {
                    $validated['slug'] = Str::slug($validated['name_en']);
                }

                if (isset($validated['stock_quantity'])) {
                    $validated['stock_status'] = $validated['stock_quantity'] > 0 ? 'in_stock' : 'out_of_stock';
                }

                // Handle images separately
                $mainImage = $validated['main_image'] ?? null;
                $additionalImages = $validated['additional_images'] ?? null;
                unset($validated['main_image'], $validated['additional_images']);

                $product->update($validated);

                // Update images if provided
                if ($mainImage || $additionalImages) {
                    // Delete existing images
                    $product->images()->delete();

                    // Create new main image
                    if ($mainImage) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => $mainImage,
                            'order' => 0,
                            'is_primary' => true,
                        ]);
                    }

                    // Create new additional images
                    if ($additionalImages) {
                        $order = 1;
                        foreach ($additionalImages as $imageUrl) {
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

                $product->load(['category', 'brand', 'images']);
                return $this->successResponse($product, 'Product updated successfully');
            } catch (\Exception $e) {
                DB::rollBack();
                return $this->errorResponse('Failed to update product: ' . $e->getMessage(), 500);
            }
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        }
    }

    /**
     * Remove the specified product
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return $this->successResponse(null, 'Product deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete product', 500);
        }
    }

    /**
     * Toggle product active status
     */
    public function toggleStatus(Product $product)
    {
        try {
            $product->update(['is_active' => !$product->is_active]);
            $product->load(['category', 'brand', 'images']);
            
            $status = $product->is_active ? 'activated' : 'deactivated';
            return $this->successResponse($product, "Product {$status} successfully");
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to toggle product status', 500);
        }
    }

    /**
     * Toggle product featured status
     */
    public function toggleFeatured(Product $product)
    {
        try {
            $product->update(['is_featured' => !$product->is_featured]);
            $product->load(['category', 'brand', 'images']);
            
            $status = $product->is_featured ? 'marked as featured' : 'removed from featured';
            return $this->successResponse($product, "Product {$status} successfully");
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to toggle product featured status', 500);
        }
    }
}
