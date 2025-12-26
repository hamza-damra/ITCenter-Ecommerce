<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SpecTemplateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $templateId = $this->route('template')?->id;

        return [
            'category_id' => [
                'required',
                'exists:categories,id',
                Rule::unique('spec_templates', 'category_id')->ignore($templateId),
            ],
            'name_en' => 'required|string|max:100',
            'name_ar' => 'nullable|string|max:100',
            'name_he' => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'category_id.unique' => __('messages.category_already_has_template'),
            'category_id.required' => __('messages.category_required'),
            'name_en.required' => __('messages.template_name_required'),
        ];
    }
}






