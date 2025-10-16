<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    use ApiResponses;

    /**
     * Display a listing of categories
     */
    public function index(Request $request)
    {
        try {
            $query = Category::with('parent');

            // Search filter
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name_en', 'like', "%{$search}%")
                      ->orWhere('name_ar', 'like', "%{$search}%");
                });
            }

            // Parent filter
            if ($request->has('parent_id')) {
                if ($request->parent_id === 'null') {
                    $query->whereNull('parent_id');
                } else {
                    $query->where('parent_id', $request->parent_id);
                }
            }

            // Status filter
            if ($request->has('is_active')) {
                $query->where('is_active', $request->is_active);
            }

            // Sorting
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $perPage = $request->get('per_page', 20);
            $categories = $query->paginate($perPage);

            return $this->paginatedResponse($categories, 'Categories retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve categories', 500);
        }
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name_en' => 'required|string|max:255',
                'name_ar' => 'required|string|max:255',
                'parent_id' => 'nullable|exists:categories,id',
                'description_en' => 'nullable|string',
                'description_ar' => 'nullable|string',
                'image' => 'nullable|url',
                'is_active' => 'boolean',
            ]);

            $validated['slug'] = Str::slug($validated['name_en']);
            $category = Category::create($validated);
            $category->load('parent');

            return $this->createdResponse($category, 'Category created successfully');
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to create category', 500);
        }
    }

    /**
     * Display the specified category
     */
    public function show(Category $category)
    {
        try {
            $category->load(['parent', 'children', 'products']);
            return $this->successResponse($category, 'Category retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve category', 500);
        }
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, Category $category)
    {
        try {
            $validated = $request->validate([
                'name_en' => 'sometimes|required|string|max:255',
                'name_ar' => 'sometimes|required|string|max:255',
                'parent_id' => 'nullable|exists:categories,id',
                'description_en' => 'nullable|string',
                'description_ar' => 'nullable|string',
                'image' => 'nullable|url',
                'is_active' => 'boolean',
            ]);

            // Prevent setting itself as parent
            if (isset($validated['parent_id']) && $validated['parent_id'] == $category->id) {
                return $this->errorResponse('Category cannot be its own parent', 422);
            }

            if (isset($validated['name_en'])) {
                $validated['slug'] = Str::slug($validated['name_en']);
            }

            $category->update($validated);
            $category->load('parent');

            return $this->successResponse($category, 'Category updated successfully');
        } catch (ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update category', 500);
        }
    }

    /**
     * Remove the specified category
     */
    public function destroy(Category $category)
    {
        try {
            // Check if category has products
            if ($category->products()->count() > 0) {
                return $this->errorResponse('Cannot delete category with associated products', 422);
            }

            // Check if category has children
            if ($category->children()->count() > 0) {
                return $this->errorResponse('Cannot delete category with subcategories', 422);
            }

            $category->delete();
            return $this->successResponse(null, 'Category deleted successfully');
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to delete category', 500);
        }
    }

    /**
     * Toggle category active status
     */
    public function toggleStatus(Category $category)
    {
        try {
            $category->update(['is_active' => !$category->is_active]);
            $category->load('parent');
            
            $status = $category->is_active ? 'activated' : 'deactivated';
            return $this->successResponse($category, "Category {$status} successfully");
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to toggle category status', 500);
        }
    }
}
