<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AttributeValueController extends Controller
{
    /**
     * Display a listing of attribute values for a specific attribute.
     */
    public function index(Attribute $attribute)
    {
        $values = $attribute->values()
            ->orderBy('order')
            ->paginate(20);

        return view('admin.attribute-values.index', compact('attribute', 'values'));
    }

    /**
     * Show the form for creating a new attribute value.
     */
    public function create(Attribute $attribute)
    {
        return view('admin.attribute-values.create', compact('attribute'));
    }

    /**
     * Store a newly created attribute value in storage.
     */
    public function store(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
            'value_en' => 'required|string|max:255',
            'value_ar' => 'required|string|max:255',
            'value_he' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:attribute_values,slug',
            'color_code' => 'nullable|string|max:7',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['value_en']);
        }

        // Set defaults
        $validated['attribute_id'] = $attribute->id;
        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['order'] = $validated['order'] ?? 0;

        AttributeValue::create($validated);

        return redirect()->route('admin.attribute-values.index', $attribute)
            ->with('success', __('messages.attribute_value_created_successfully'));
    }

    /**
     * Show the form for editing the specified attribute value.
     */
    public function edit(Attribute $attribute, AttributeValue $attributeValue)
    {
        // Ensure the attribute value belongs to the attribute
        if ($attributeValue->attribute_id !== $attribute->id) {
            abort(404);
        }

        return view('admin.attribute-values.edit', compact('attribute', 'attributeValue'));
    }

    /**
     * Update the specified attribute value in storage.
     */
    public function update(Request $request, Attribute $attribute, AttributeValue $attributeValue)
    {
        // Ensure the attribute value belongs to the attribute
        if ($attributeValue->attribute_id !== $attribute->id) {
            abort(404);
        }

        $validated = $request->validate([
            'value_en' => 'required|string|max:255',
            'value_ar' => 'required|string|max:255',
            'value_he' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:attribute_values,slug,' . $attributeValue->id,
            'color_code' => 'nullable|string|max:7',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['value_en']);
        }

        // Set defaults
        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['order'] = $validated['order'] ?? $attributeValue->order;

        $attributeValue->update($validated);

        return redirect()->route('admin.attribute-values.index', $attribute)
            ->with('success', __('messages.attribute_value_updated_successfully'));
    }

    /**
     * Remove the specified attribute value from storage.
     */
    public function destroy(Attribute $attribute, AttributeValue $attributeValue)
    {
        // Ensure the attribute value belongs to the attribute
        if ($attributeValue->attribute_id !== $attribute->id) {
            abort(404);
        }

        try {
            DB::beginTransaction();

            // Cascade deletion is handled by database foreign key constraints
            // This will automatically delete product_attribute_values records
            $attributeValue->delete();

            DB::commit();

            return redirect()->route('admin.attribute-values.index', $attribute)
                ->with('success', __('messages.attribute_value_deleted_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.attribute-values.index', $attribute)
                ->with('error', __('messages.error_deleting_attribute_value', ['error' => $e->getMessage()]));
        }
    }
}
