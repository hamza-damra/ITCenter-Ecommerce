<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class BrandController extends Controller
{
    use ApiResponses;

    /**
     * Display a listing of brands
     */
    public function index(Request $request)
    {
        try {
            $query = Brand::query();

            // Search filter
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name_en', 'like', "%{$search}%")
                      ->orWhere('name_ar', 'like', "%{$search}%");
                });
            }

            // Status filter
            if ($request->has('is_active')) {
                $query->where('is_active', $request->is_active);
            }

            // Featured filter
            if ($request->has('is_featured')) {
                $query->where('is_featured', $request->is_featured);
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $perPage = $request->get('per_page', 20);
            $brands = $query->paginate($perPage);

            return $this->paginatedResponse($brands, 'Brands retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve brands', 500);
        }
    }

    /**
     * Store a newly created brand
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name_en' => 'required|string|max:255',
                'name_ar' => 'required|string|max:255',
                'description_en' => 'nullable|string',
                'description_ar' => 'nullable|string',
                'logo' => 'nullable|url',
                'website' => 'nullable|url',
                'is_active' => 'boolean',
                'is_featured' => 'boolean',
            ]);

            $validated['slug'] = Str::slug($validated['name_en']);
            $brand = Brand::create($validated);

            return $this->createdResponse($brand, 'Brand created successfully');
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create brand', 500);
        }
    }

    /**
     * Display the specified brand
     */
    public function show(Brand $brand)
    {
        try {
            $brand->load('products');
            return $this->successResponse($brand, 'Brand retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve brand', 500);
        }
    }

    /**
     * Update the specified brand
     */
    public function update(Request $request, Brand $brand)
    {
        try {
            $validated = $request->validate([
                'name_en' => 'sometimes|required|string|max:255',
                'name_ar' => 'sometimes|required|string|max:255',
                'description_en' => 'nullable|string',
                'description_ar' => 'nullable|string',
                'logo' => 'nullable|url',
                'website' => 'nullable|url',
                'is_active' => 'boolean',
                'is_featured' => 'boolean',
            ]);

            if (isset($validated['name_en'])) {
                $validated['slug'] = Str::slug($validated['name_en']);
            }

            $brand->update($validated);

            return $this->successResponse($brand, 'Brand updated successfully');
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update brand', 500);
        }
    }

    /**
     * Remove the specified brand
     */
    public function destroy(Brand $brand)
    {
        try {
            // Check if brand has products
            if ($brand->products()->count() > 0) {
                return $this->errorResponse('Cannot delete brand with associated products', 422);
            }

            $brand->delete();
            return $this->successResponse(null, 'Brand deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete brand', 500);
        }
    }

    /**
     * Toggle brand active status
     */
    public function toggleStatus(Brand $brand)
    {
        try {
            $brand->update(['is_active' => !$brand->is_active]);
            
            $status = $brand->is_active ? 'activated' : 'deactivated';
            return $this->successResponse($brand, "Brand {$status} successfully");
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to toggle brand status', 500);
        }
    }

    /**
     * Toggle brand featured status
     */
    public function toggleFeatured(Brand $brand)
    {
        try {
            $brand->update(['is_featured' => !$brand->is_featured]);
            
            $status = $brand->is_featured ? 'marked as featured' : 'removed from featured';
            return $this->successResponse($brand, "Brand {$status} successfully");
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to toggle brand featured status', 500);
        }
    }
}
