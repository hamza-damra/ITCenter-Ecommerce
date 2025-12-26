<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SpecFieldRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $fieldId = $this->route('field')?->id;
        $templateId = $this->route('template')?->id ?? $this->input('spec_template_id');

        return [
            'spec_template_id' => 'required|exists:spec_templates,id',
            'key' => [
                'nullable',
                'string',
                'max:50',
                'regex:/^[a-z][a-z0-9_]*$/',
                Rule::unique('spec_fields', 'key')
                    ->where('spec_template_id', $templateId)
                    ->ignore($fieldId),
            ],
            'label_en' => 'required|string|max:100',
            'label_ar' => 'nullable|string|max:100',
            'label_he' => 'nullable|string|max:100',
            'type' => 'required|in:text,number,boolean,select',
            'options' => 'nullable|array',
            'options.*' => 'string|max:100',
            'unit' => 'nullable|string|max:30',
            'is_required' => 'boolean',
            'sort_order' => 'integer|min:0',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'key.regex' => __('messages.key_must_be_lowercase'),
            'key.unique' => __('messages.key_already_exists_in_template'),
            'label_en.required' => __('messages.field_label_required'),
            'type.required' => __('messages.field_type_required'),
            'options.required_if' => __('messages.options_required_for_select'),
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure options are provided for select type
        if ($this->input('type') === 'select' && !$this->has('options')) {
            $this->merge(['options' => []]);
        }

        // Parse options from string if provided as newline-separated
        if (is_string($this->input('options'))) {
            $options = array_filter(
                array_map('trim', explode("\n", $this->input('options')))
            );
            $this->merge(['options' => $options]);
        }
    }
}






