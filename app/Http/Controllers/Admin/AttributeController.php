<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AttributeController extends Controller
{
    public function index()
    {
        $attributes = Attribute::with('values')
            ->orderBy('order')
            ->paginate(20);

        return view('admin.attributes.index', compact('attributes'));
    }

    public function create()
    {
        return view('admin.attributes.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'name_he' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:attributes,slug',
            'type' => 'required|in:select,multi_select,range,color',
            'unit' => 'nullable|string|max:50',
            'is_filterable' => 'boolean',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name_en']);
        }

        // Set defaults
        $validated['is_filterable'] = $request->has('is_filterable') ? true : false;
        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['order'] = $validated['order'] ?? 0;

        Attribute::create($validated);

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Attribute created successfully!');
    }

    public function edit(Attribute $attribute)
    {
        return view('admin.attributes.edit', compact('attribute'));
    }

    public function update(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'name_he' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:attributes,slug,' . $attribute->id,
            'type' => 'required|in:select,multi_select,range,color',
            'unit' => 'nullable|string|max:50',
            'is_filterable' => 'boolean',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name_en']);
        }

        // Set defaults
        $validated['is_filterable'] = $request->has('is_filterable') ? true : false;
        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['order'] = $validated['order'] ?? $attribute->order;

        $attribute->update($validated);

        return redirect()->route('admin.attributes.index')
            ->with('success', 'Attribute updated successfully!');
    }

    public function destroy(Attribute $attribute)
    {
        try {
            DB::beginTransaction();

            // Cascade deletion is handled by database foreign key constraints
            // This will automatically delete:
            // - attribute_values (via ON DELETE CASCADE)
            // - attribute_category records (via ON DELETE CASCADE)
            // - product_attribute_values records (via ON DELETE CASCADE through attribute_values)
            $attribute->delete();

            DB::commit();

            return redirect()->route('admin.attributes.index')
                ->with('success', 'Attribute deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.attributes.index')
                ->with('error', 'Error deleting attribute: ' . $e->getMessage());
        }
    }

    public function deleteAll(Request $request)
    {
        try {
            DB::beginTransaction();

            // Delete all attributes (cascade will handle related records)
            $count = Attribute::count();
            Attribute::query()->delete();

            DB::commit();

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
}
