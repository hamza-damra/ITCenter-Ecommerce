<?php

namespace App\Http\Requests;

use App\Models\SiteSetting;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Get the review ID from the route parameter
        $reviewId = $this->route('review');

        // If it's already a Review model (implicit binding), use it directly
        if ($reviewId instanceof \App\Models\Review) {
            return Auth::check() && $reviewId->user_id === Auth::id();
        }

        // Otherwise, fetch the review by ID
        $review = \App\Models\Review::find($reviewId);

        return Auth::check() && $review && $review->user_id === Auth::id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:100',
            'comment' => 'required|string|min:10|max:1000',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:' . SiteSetting::getValue('max_image_size_kb', 5120),
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
            'rating.required' => __('messages.review_rating_required'),
            'rating.integer' => __('messages.review_rating_invalid'),
            'rating.min' => __('messages.review_rating_min'),
            'rating.max' => __('messages.review_rating_max'),
            'title.max' => __('messages.review_title_max'),
            'comment.required' => __('messages.review_comment_required'),
            'comment.min' => __('messages.review_comment_min'),
            'comment.max' => __('messages.review_comment_max'),
            'images.max' => __('messages.review_images_max'),
            'images.*.image' => __('messages.review_image_invalid'),
            'images.*.mimes' => __('messages.review_image_format'),
            'images.*.max' => __('messages.review_image_size'),
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
            'rating' => __('messages.rating'),
            'title' => __('messages.review_title'),
            'comment' => __('messages.review_comment'),
            'images' => __('messages.review_images'),
        ];
    }
}

