<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Category;

class ProductRequest extends FormRequest
{
    /**
     * Input limits for overflow protection.
     */
    public const NAME_MAX_LENGTH = 120;
    public const SHORT_DESCRIPTION_MAX_LENGTH = 500;
    public const DESCRIPTION_MAX_LENGTH = 3000;
    public const SEARCH_KEYWORDS_MAX_LENGTH = 500;

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
        $rules = [
            // Basic Information with input limits
            'name_en' => 'required|string|max:' . self::NAME_MAX_LENGTH,
            'name_ar' => 'required|string|max:' . self::NAME_MAX_LENGTH,
            'name_he' => 'nullable|string|max:' . self::NAME_MAX_LENGTH,
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            
            // Pricing
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            
            // Images
            'main_image' => 'required|url',
            'additional_images' => 'nullable|string',
            
            // Descriptions with limits
            'short_description_en' => 'nullable|string|max:' . self::SHORT_DESCRIPTION_MAX_LENGTH,
            'short_description_ar' => 'nullable|string|max:' . self::SHORT_DESCRIPTION_MAX_LENGTH,
            'short_description_he' => 'nullable|string|max:' . self::SHORT_DESCRIPTION_MAX_LENGTH,
            'description_en' => 'nullable|string|max:' . self::DESCRIPTION_MAX_LENGTH,
            'description_ar' => 'nullable|string|max:' . self::DESCRIPTION_MAX_LENGTH,
            'description_he' => 'nullable|string|max:' . self::DESCRIPTION_MAX_LENGTH,
            
            // SEO
            'search_keywords' => 'nullable|string|max:' . self::SEARCH_KEYWORDS_MAX_LENGTH,
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            
            // Attributes
            'attribute_values' => 'nullable|array',
            'attribute_values.*' => 'exists:attribute_values,id',
            
            // Specification values (dynamic, validated separately)
            'spec_values' => 'nullable|array',
            'spec_values.*' => 'nullable|string|max:500',
        ];

        return $rules;
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name_en.max' => __('messages.product_name_too_long', ['max' => self::NAME_MAX_LENGTH]),
            'name_ar.max' => __('messages.product_name_too_long', ['max' => self::NAME_MAX_LENGTH]),
            'description_en.max' => __('messages.description_too_long', ['max' => self::DESCRIPTION_MAX_LENGTH]),
            'description_ar.max' => __('messages.description_too_long', ['max' => self::DESCRIPTION_MAX_LENGTH]),
            'sale_price.lt' => __('messages.sale_price_must_be_less_than_price'),
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $this->validateSpecValues($validator);
        });
    }

    /**
     * Validate specification values against the category's template.
     */
    protected function validateSpecValues($validator): void
    {
        $categoryId = $this->input('category_id');
        $specValues = $this->input('spec_values', []);

        if (empty($categoryId) || empty($specValues)) {
            return;
        }

        $category = Category::with('specTemplate.activeFields')->find($categoryId);
        
        if (!$category?->specTemplate) {
            return; // No template, no validation needed
        }

        $validFieldIds = $category->specTemplate->activeFields->pluck('id')->toArray();

        foreach ($specValues as $fieldId => $value) {
            // Check if field belongs to this category's template
            if (!in_array((int) $fieldId, $validFieldIds)) {
                $validator->errors()->add(
                    "spec_values.$fieldId",
                    __('messages.invalid_spec_field_for_category')
                );
                continue;
            }

            // Validate required fields
            $field = $category->specTemplate->activeFields->find($fieldId);
            if ($field && $field->is_required && empty($value) && $value !== '0') {
                $validator->errors()->add(
                    "spec_values.$fieldId",
                    __('messages.spec_field_required', ['field' => $field->label])
                );
            }

            // Validate field type
            if ($field && !empty($value) && !$field->validateValue($value)) {
                $validator->errors()->add(
                    "spec_values.$fieldId",
                    __('messages.invalid_spec_value_type', ['field' => $field->label])
                );
            }
        }

        // Check for missing required fields
        foreach ($category->specTemplate->requiredFields as $field) {
            if (!isset($specValues[$field->id]) || 
                ($specValues[$field->id] === '' && $specValues[$field->id] !== '0')) {
                $validator->errors()->add(
                    "spec_values.{$field->id}",
                    __('messages.spec_field_required', ['field' => $field->label])
                );
            }
        }
    }
}


