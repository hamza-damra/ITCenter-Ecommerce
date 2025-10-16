<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::latest()->paginate(20);

        return view('admin.brands.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
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

        Brand::create($validated);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand created successfully!');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
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

        $brand->update($validated);

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand updated successfully!');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();

        return redirect()->route('admin.brands.index')
            ->with('success', 'Brand deleted successfully!');
    }
}
