<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    /**
     * Get reviews for a product (JSON response for AJAX)
     */
    public function index(Request $request, Product $product)
    {
        $query = Review::with(['user'])
            ->where('product_id', $product->id);

        // Apply sorting
        $sortBy = $request->get('sort_by', 'recent');
        switch ($sortBy) {
            case 'helpful':
                $query->orderBy('helpful_count', 'desc');
                break;
            case 'highest':
                $query->orderBy('rating', 'desc');
                break;
            case 'lowest':
                $query->orderBy('rating', 'asc');
                break;
            case 'recent':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Apply rating filter
        if ($request->has('rating') && $request->rating >= 1 && $request->rating <= 5) {
            $query->where('rating', $request->rating);
        }

        // Apply verified purchase filter
        if ($request->get('verified_only') === 'true') {
            $query->where('is_verified_purchase', true);
        }

        $perPage = min($request->get('per_page', 15), 50);
        $reviews = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'reviews' => $reviews->items(),
                'stats' => [
                    'average_rating' => $product->avg_rating !== null ? round((float)$product->avg_rating, 1) : 0.0,
                    'total_reviews' => $product->reviews_count,
                    'rating_distribution' => $this->getRatingDistribution($product->id),
                ],
            ],
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ]
        ]);
    }

    /**
     * Store a new review
     */
    public function store(Request $request, Product $product)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.please_login'),
            ], 401);
        }

        // Validate request
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:100',
            'comment' => 'required|string|min:10|max:1000',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:' . SiteSetting::getValue('max_image_size_kb', 5120),
        ], [
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
        ]);

        // Check if user has purchased and shipped this product
        if (!$this->hasPurchasedAndShipped($product->id, Auth::id())) {
            return response()->json([
                'success' => false,
                'message' => __('messages.review_requires_purchase_shipped'),
            ], 403);
        }

        // Check if user already reviewed this product
        $existingReview = Review::where('product_id', $product->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => __('messages.review_already_exists'),
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Handle image uploads
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('reviews', 'public');
                    $imagePaths[] = $path;
                }
            }

            $review = Review::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'rating' => $validated['rating'],
                'title' => isset($validated['title']) ? strip_tags($validated['title']) : null,
                'comment' => strip_tags($validated['comment']),
                'images' => $imagePaths,
                'is_verified_purchase' => $this->isVerifiedPurchase($product->id, Auth::id()),
                'is_approved' => true,
                'status' => 'approved',
            ]);

            // Update product average rating and review count
            $this->updateProductRatingStats($product->id);

            DB::commit();

            // Load user relationship for response
            $review->load('user');

            return response()->json([
                'success' => true,
                'message' => __('messages.review_submitted_success'),
                'data' => [
                    'review' => $review,
                ],
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            // Clean up uploaded images on error
            foreach ($imagePaths as $path) {
                Storage::disk('public')->delete($path);
            }

            return response()->json([
                'success' => false,
                'message' => __('messages.review_submit_failed'),
            ], 500);
        }
    }

    /**
     * Update a review
     */
    public function update(Request $request, $reviewId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.unauthorized'),
            ], 401);
        }

        $review = Review::findOrFail($reviewId);

        // Check if user owns the review
        if ($review->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.unauthorized'),
            ], 403);
        }

        // Validate request
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:100',
            'comment' => 'required|string|min:10|max:1000',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:' . SiteSetting::getValue('max_image_size_kb', 5120),
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'rating' => $validated['rating'],
                'title' => isset($validated['title']) ? strip_tags($validated['title']) : null,
                'comment' => strip_tags($validated['comment']),
            ];

            // Handle new image uploads
            if ($request->hasFile('images')) {
                // Delete old images
                if ($review->images) {
                    foreach ($review->images as $oldImage) {
                        Storage::disk('public')->delete($oldImage);
                    }
                }

                // Upload new images
                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('reviews', 'public');
                    $imagePaths[] = $path;
                }
                $data['images'] = $imagePaths;
            }

            $review->update($data);

            // Update product rating stats
            $this->updateProductRatingStats($review->product_id);

            DB::commit();

            // Load user relationship for response
            $review->load('user');

            return response()->json([
                'success' => true,
                'message' => __('messages.review_updated_success'),
                'data' => [
                    'review' => $review,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => __('messages.review_update_failed'),
            ], 500);
        }
    }

    /**
     * Delete a review
     */
    public function destroy($reviewId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.unauthorized'),
            ], 401);
        }

        $review = Review::findOrFail($reviewId);

        // Check if user owns the review
        if ($review->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.unauthorized'),
            ], 403);
        }

        DB::beginTransaction();
        try {
            $productId = $review->product_id;

            // Delete review images
            if ($review->images) {
                foreach ($review->images as $image) {
                    Storage::disk('public')->delete($image);
                }
            }

            $review->delete();

            // Update product rating stats
            $this->updateProductRatingStats($productId);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('messages.review_deleted_success'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => __('messages.review_delete_failed'),
            ], 500);
        }
    }

    /**
     * Mark review as helpful
     */
    public function markHelpful($reviewId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.please_login'),
            ], 401);
        }

        $review = Review::findOrFail($reviewId);

        // Prevent users from voting on their own reviews
        if ($review->user_id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.cannot_vote_own_review'),
            ], 403);
        }

        // Check for existing vote
        $existingVote = \App\Models\ReviewVote::where('review_id', $review->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingVote) {
            if ($existingVote->vote_type === 'helpful') {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.already_voted'),
                ], 422);
            }
            // Change vote from unhelpful to helpful
            $existingVote->update(['vote_type' => 'helpful']);
            $review->increment('helpful_count');
            $review->decrement('unhelpful_count');
        } else {
            \App\Models\ReviewVote::create([
                'review_id' => $review->id,
                'user_id' => Auth::id(),
                'vote_type' => 'helpful',
            ]);
            $review->increment('helpful_count');
        }

        return response()->json([
            'success' => true,
            'message' => __('messages.review_marked_helpful'),
            'data' => [
                'helpful_count' => $review->helpful_count,
            ],
        ]);
    }

    /**
     * Mark review as unhelpful
     */
    public function markUnhelpful($reviewId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.please_login'),
            ], 401);
        }

        $review = Review::findOrFail($reviewId);

        // Prevent users from voting on their own reviews
        if ($review->user_id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.cannot_vote_own_review'),
            ], 403);
        }

        // Check for existing vote
        $existingVote = \App\Models\ReviewVote::where('review_id', $review->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingVote) {
            if ($existingVote->vote_type === 'unhelpful') {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.already_voted'),
                ], 422);
            }
            // Change vote from helpful to unhelpful
            $existingVote->update(['vote_type' => 'unhelpful']);
            $review->decrement('helpful_count');
            $review->increment('unhelpful_count');
        } else {
            \App\Models\ReviewVote::create([
                'review_id' => $review->id,
                'user_id' => Auth::id(),
                'vote_type' => 'unhelpful',
            ]);
            $review->increment('unhelpful_count');
        }

        return response()->json([
            'success' => true,
            'message' => __('messages.review_marked_unhelpful'),
            'data' => [
                'unhelpful_count' => $review->unhelpful_count,
            ],
        ]);
    }

    /**
     * Get rating distribution for a product
     */
    private function getRatingDistribution($productId)
    {
        $distribution = [];
        for ($i = 5; $i >= 1; $i--) {
            $count = Review::where('product_id', $productId)
                ->where('rating', $i)
                ->count();
            $distribution[$i] = $count;
        }
        return $distribution;
    }

    /**
     * Check if purchase is verified (user has purchased and shipped the product)
     */
    private function isVerifiedPurchase($productId, $userId)
    {
        return Order::where('user_id', $userId)
            ->whereIn('status', ['shipped', 'delivered'])
            ->whereHas('items', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->exists();
    }

    /**
     * Check if user has purchased and shipped the product (required for review)
     */
    private function hasPurchasedAndShipped($productId, $userId)
    {
        return Order::where('user_id', $userId)
            ->whereIn('status', ['shipped', 'delivered'])
            ->whereHas('items', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->exists();
    }

    /**
     * Update product rating statistics
     */
    private function updateProductRatingStats($productId)
    {
        $product = Product::find($productId);
        if (!$product) {
            return;
        }

        $reviews = Review::where('product_id', $productId)->get();

        $product->reviews_count = $reviews->count();
        $product->avg_rating = $reviews->count() > 0
            ? round($reviews->avg('rating'), 1)
            : 0;

        $product->save();
    }
}
