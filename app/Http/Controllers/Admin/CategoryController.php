<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully!');
    }

    public function edit(Category $category)
    {
        $locale = app()->getLocale();
        $nameColumn = "name_{$locale}";
        
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

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
    }
}
