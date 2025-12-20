<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    use ApiResponses;
    /**
     * Get reviews for a product
     */
    public function index(Request $request, Product $product)
    {

        $query = Review::with(['user'])
            ->where('product_id', $product->id);

        // Apply sorting
        $sortBy = $request->get('sort_by', 'recent');
        switch ($sortBy) {
            case 'helpful':
                $query->mostHelpful();
                break;
            case 'highest':
                $query->highestRating();
                break;
            case 'lowest':
                $query->lowestRating();
                break;
            case 'recent':
            default:
                $query->mostRecent();
                break;
        }

        // Apply rating filter
        if ($request->has('rating') && $request->rating >= 1 && $request->rating <= 5) {
            $query->rating($request->rating);
        }

        // Apply verified purchase filter
        if ($request->get('verified_only') === 'true') {
            $query->verifiedPurchase();
        }

        $perPage = min($request->get('per_page', 15), 50); // Max 50 per page
        $reviews = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'reviews' => ReviewResource::collection($reviews->items()),
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
    public function store(StoreReviewRequest $request, Product $product)
    {

        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.please_login'),
            ], 401);
        }

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
                'rating' => $request->rating,
                'title' => $request->title,
                'comment' => $request->comment,
                'images' => $imagePaths,
                'is_verified_purchase' => $this->isVerifiedPurchase($product->id, Auth::id()),
                'is_approved' => true,
                'status' => 'approved',
            ]);

            // Update product average rating and review count
            $this->updateProductRatingStats($product->id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('messages.review_submitted_success'),
                'data' => [
                    'review' => new ReviewResource($review->load('user')),
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
    public function update(UpdateReviewRequest $request, $reviewId)
    {
        $review = Review::findOrFail($reviewId);

        DB::beginTransaction();
        try {
            $data = [
                'rating' => $request->rating,
                'title' => $request->title,
                'comment' => $request->comment,
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

            return response()->json([
                'success' => true,
                'message' => __('messages.review_updated_success'),
                'data' => [
                    'review' => new ReviewResource($review->load('user')),
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
        $review = Review::findOrFail($reviewId);

        // Prevent users from voting on their own reviews
        if (Auth::check() && $review->user_id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.cannot_vote_own_review'),
            ], 403);
        }

        $review->increment('helpful_count');

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
        $review = Review::findOrFail($reviewId);

        // Prevent users from voting on their own reviews
        if (Auth::check() && $review->user_id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => __('messages.cannot_vote_own_review'),
            ], 403);
        }

        $review->increment('unhelpful_count');

        return response()->json([
            'success' => true,
            'message' => __('messages.review_marked_unhelpful'),
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
        // Check if user has shipped or delivered order with this product
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
        // Check if user has shipped or delivered order with this product
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

        $reviews = Review::where('product_id', $productId)
            ->get();

        $product->reviews_count = $reviews->count();
        $product->avg_rating = $reviews->count() > 0
            ? round($reviews->avg('rating'), 1)
            : 0;

        $product->save();
    }
}
