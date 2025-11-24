<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Attribute;
use Illuminate\Http\Request;

class CategoryAttributeController extends Controller
{
    /**
     * Show the form for editing category-attribute assignments.
     */
    public function edit(Category $category)
    {
        // Get currently assigned attribute IDs
        $assignedAttributes = $category->attributes()->pluck('attributes.id')->toArray();
        
        // Get all filterable attributes ordered by order field
        $allAttributes = Attribute::where('is_filterable', true)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('admin.categories.attributes', compact(
            'category',
            'assignedAttributes',
            'allAttributes'
        ));
    }

    /**
     * Update category-attribute assignments.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'attributes' => 'nullable|array',
            'attributes.*' => 'exists:attributes,id',
        ]);

        // Sync the attributes (this will add new ones and remove unselected ones)
        $category->attributes()->sync($validated['attributes'] ?? []);

        return redirect()->route('admin.categories.index')
            ->with('success', __('messages.category_attributes_updated_successfully'));
    }
}

