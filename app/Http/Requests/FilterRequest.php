<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permission handled by middleware
    }

    public function rules(): array
    {
        $filterId = $this->route('filter')?->id ?? $this->route('filter');

        $rules = [
            'title_en'       => 'required|string|max:255',
            'title_ar'       => 'nullable|string|max:255',
            'title_he'       => 'nullable|string|max:255',
            'slug'           => [
                'nullable',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('filters', 'slug')->ignore($filterId),
            ],
            'description_en' => 'nullable|string|max:1000',
            'description_ar' => 'nullable|string|max:1000',
            'description_he' => 'nullable|string|max:1000',
            'type'           => 'required|in:checkbox,radio,range,min_max,boolean',
            'sort_order'     => 'nullable|integer|min:0|max:9999',
            'is_active'      => 'nullable',

            // Category assignments
            'categories'                    => 'nullable|array',
            'categories.*.category_id'      => 'required_with:categories|exists:categories,id',
            'categories.*.inherit_to_children' => 'nullable',

            // Filter options (inline)
            'options'              => 'nullable|array',
            'options.*.label_en'   => 'required_with:options|string|max:255',
            'options.*.label_ar'   => 'nullable|string|max:255',
            'options.*.label_he'   => 'nullable|string|max:255',
            'options.*.value_slug' => 'required_with:options|string|max:255',
            'options.*.color_code' => 'nullable|string|max:30',
            'options.*.icon'       => 'nullable|string|max:100',
            'options.*.sort_order' => 'nullable|integer|min:0|max:9999',
            'options.*.is_active'  => 'nullable',
            'options.*.id'         => 'nullable|integer',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title_en.required' => __('messages.name_english') . ' ' . __('messages.is_required', ['attribute' => '']),
            'type.required'     => __('messages.filter_type') . ' is required.',
            'type.in'           => 'Invalid filter type.',
            'slug.regex'        => 'Slug must contain only lowercase letters, numbers, and hyphens.',
        ];
    }
}
