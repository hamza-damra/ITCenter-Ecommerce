<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SpecTemplateRequest;
use App\Http\Requests\SpecFieldRequest;
use App\Models\Category;
use App\Models\SpecTemplate;
use App\Models\SpecField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SpecTemplateController extends Controller
{
    /**
     * Display a listing of the spec templates.
     */
    public function index()
    {
        $templates = SpecTemplate::with(['category', 'fields'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.spec-templates.index', compact('templates'));
    }

    /**
     * Show the form for creating a new spec template.
     */
    public function create()
    {
        // Get categories that don't have a template yet
        $usedCategoryIds = SpecTemplate::pluck('category_id')->toArray();
        $categories = Category::active()
            ->whereNotIn('id', $usedCategoryIds)
            ->orderBy('name_en')
            ->get();

        return view('admin.spec-templates.create', compact('categories'));
    }

    /**
     * Store a newly created spec template.
     */
    public function store(SpecTemplateRequest $request)
    {
        DB::beginTransaction();
        try {
            $template = SpecTemplate::create($request->validated());

            DB::commit();

            return redirect()
                ->route('admin.spec-templates.edit', $template)
                ->with('success', __('messages.template_created_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', __('messages.error_creating_template', ['error' => $e->getMessage()]));
        }
    }

    /**
     * Show the form for editing the specified spec template.
     */
    public function edit(SpecTemplate $template)
    {
        $template->load(['category', 'fields' => function ($query) {
            $query->orderBy('sort_order');
        }]);

        return view('admin.spec-templates.edit', compact('template'));
    }

    /**
     * Update the specified spec template.
     */
    public function update(SpecTemplateRequest $request, SpecTemplate $template)
    {
        DB::beginTransaction();
        try {
            $template->update($request->validated());

            DB::commit();

            return redirect()
                ->route('admin.spec-templates.edit', $template)
                ->with('success', __('messages.template_updated_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', __('messages.error_updating_template', ['error' => $e->getMessage()]));
        }
    }

    /**
     * Remove the specified spec template.
     */
    public function destroy(SpecTemplate $template)
    {
        DB::beginTransaction();
        try {
            // All related fields and values will be cascade deleted
            $template->delete();

            DB::commit();

            return redirect()
                ->route('admin.spec-templates.index')
                ->with('success', __('messages.template_deleted_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', __('messages.error_deleting_template', ['error' => $e->getMessage()]));
        }
    }

    /**
     * Store a new field for the template.
     */
    public function storeField(SpecFieldRequest $request, SpecTemplate $template)
    {
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $data['spec_template_id'] = $template->id;
            
            // Generate key from label if not provided
            if (empty($data['key'])) {
                $data['key'] = Str::slug($data['label_en'], '_');
            }
            
            // Set sort order to last
            $data['sort_order'] = $template->fields()->max('sort_order') + 1;

            SpecField::create($data);

            DB::commit();

            return redirect()
                ->route('admin.spec-templates.edit', $template)
                ->with('success', __('messages.field_added_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', __('messages.error_adding_field', ['error' => $e->getMessage()]));
        }
    }

    /**
     * Update a field for the template.
     */
    public function updateField(SpecFieldRequest $request, SpecTemplate $template, SpecField $field)
    {
        if ($field->spec_template_id !== $template->id) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            $field->update($request->validated());

            DB::commit();

            return redirect()
                ->route('admin.spec-templates.edit', $template)
                ->with('success', __('messages.field_updated_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', __('messages.error_updating_field', ['error' => $e->getMessage()]));
        }
    }

    /**
     * Delete a field from the template.
     */
    public function destroyField(SpecTemplate $template, SpecField $field)
    {
        if ($field->spec_template_id !== $template->id) {
            abort(404);
        }

        DB::beginTransaction();
        try {
            // This will also delete all product_spec_values for this field
            $field->delete();

            DB::commit();

            return redirect()
                ->route('admin.spec-templates.edit', $template)
                ->with('success', __('messages.field_deleted_successfully'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', __('messages.error_deleting_field', ['error' => $e->getMessage()]));
        }
    }

    /**
     * Reorder fields via AJAX.
     */
    public function reorderFields(Request $request, SpecTemplate $template)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:spec_fields,id',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->order as $position => $fieldId) {
                SpecField::where('id', $fieldId)
                    ->where('spec_template_id', $template->id)
                    ->update(['sort_order' => $position]);
            }

            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Get template fields for a category (AJAX).
     */
    public function getCategorySpecFields($categoryId)
    {
        $category = Category::with(['specTemplate.activeFields'])->findOrFail($categoryId);

        if (!$category->specTemplate) {
            return response()->json([
                'hasTemplate' => false,
                'fields' => [],
            ]);
        }

        $fields = $category->specTemplate->activeFields->map(function ($field) {
            return [
                'id' => $field->id,
                'key' => $field->key,
                'label' => $field->label,
                'label_en' => $field->label_en,
                'label_ar' => $field->label_ar,
                'type' => $field->type,
                'options' => $field->options,
                'unit' => $field->unit,
                'is_required' => $field->is_required,
            ];
        });

        return response()->json([
            'hasTemplate' => true,
            'templateName' => $category->specTemplate->name,
            'fields' => $fields,
        ]);
    }
}






