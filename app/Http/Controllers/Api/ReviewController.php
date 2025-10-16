<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use App\Models\Product;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    use ApiResponses;
    /**
     * Get reviews for a product
     */
    public function index(Request $request, $productSlug)
    {
        $product = Product::where('slug', $productSlug)->firstOrFail();

        $reviews = Review::with(['user'])
            ->where('product_id', $product->id)
            ->approved()
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 10));

        return response()->json([
            'success' => true,
            'data' => [
                'reviews' => ReviewResource::collection($reviews->items()),
                'stats' => [
                    'average_rating' => $product->avg_rating,
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
    public function store(Request $request, $productSlug)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to submit a review',
            ], 401);
        }

        $product = Product::where('slug', $productSlug)->firstOrFail();

        // Check if user already reviewed this product
        $existingReview = Review::where('product_id', $product->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reviewed this product',
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $review = Review::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'is_verified_purchase' => $this->isVerifiedPurchase($product->id, Auth::id()),
            'is_approved' => false, // Requires admin approval
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully. It will be published after approval.',
            'data' => new ReviewResource($review->load('user')),
        ], 201);
    }

    /**
     * Update a review
     */
    public function update(Request $request, $reviewId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in',
            ], 401);
        }

        $review = Review::findOrFail($reviewId);

        // Check if user owns the review
        if ($review->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $review->update([
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'is_approved' => false, // Reset approval status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review updated successfully',
            'data' => new ReviewResource($review->load('user')),
        ]);
    }

    /**
     * Delete a review
     */
    public function destroy($reviewId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in',
            ], 401);
        }

        $review = Review::findOrFail($reviewId);

        // Check if user owns the review
        if ($review->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully',
        ]);
    }

    /**
     * Mark review as helpful
     */
    public function markHelpful($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $review->increment('helpful_count');

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your feedback',
            'helpful_count' => $review->helpful_count,
        ]);
    }

    /**
     * Mark review as unhelpful
     */
    public function markUnhelpful($reviewId)
    {
        $review = Review::findOrFail($reviewId);
        $review->increment('unhelpful_count');

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your feedback',
            'unhelpful_count' => $review->unhelpful_count,
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
                ->approved()
                ->count();
            $distribution[$i] = $count;
        }
        return $distribution;
    }

    /**
     * Check if purchase is verified
     */
    private function isVerifiedPurchase($productId, $userId)
    {
        // TODO: Implement logic to check if user actually purchased the product
        // This would typically check the orders table
        return false;
    }
}
