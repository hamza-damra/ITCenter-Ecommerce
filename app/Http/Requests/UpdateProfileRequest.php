<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = Auth::id();

        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'first_name.required' => __('messages.first_name_required'),
            'first_name.max' => __('messages.first_name_max'),
            'last_name.required' => __('messages.last_name_required'),
            'last_name.max' => __('messages.last_name_max'),
            'email.required' => __('messages.email_required'),
            'email.email' => __('messages.email_invalid'),
            'email.unique' => __('messages.email_taken'),
            'phone.max' => __('messages.phone_max'),
            'avatar.image' => __('messages.avatar_must_be_image'),
            'avatar.mimes' => __('messages.avatar_format'),
            'avatar.max' => __('messages.avatar_size'),
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'first_name' => __('messages.first_name'),
            'last_name' => __('messages.last_name'),
            'email' => __('messages.email'),
            'phone' => __('messages.phone'),
            'avatar' => __('messages.avatar'),
        ];
    }
}

