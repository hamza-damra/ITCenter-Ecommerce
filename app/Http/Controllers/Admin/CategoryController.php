<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
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
        
        $parentCategories = Category::whereNull('parent_id')->orderBy($nameColumn)->get();

        return view('admin.categories.create', compact('parentCategories'));
    }

    public function store(Request $request)
    {
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

        Category::create($validated);

        // Clear home page cache to reflect changes immediately
        $this->clearHomeCache();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully!');
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
        
        $parentCategories = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy($nameColumn)
            ->get();

        return view('admin.categories.edit', compact('category', 'parentCategories'));
    }

    public function update(Request $request, Category $category)
    {
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

        $category->update($validated);

        // Clear home page cache to reflect changes immediately
        $this->clearHomeCache();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        // Clear home page cache to reflect changes immediately
        $this->clearHomeCache();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
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

