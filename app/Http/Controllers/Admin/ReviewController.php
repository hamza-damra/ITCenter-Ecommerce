<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class ReviewController extends Controller
{
    /**
     * Display a listing of the reviews with search.
     */
    public function index(Request $request)
    {
        $search = $request->input('q');

        $reviews = Review::with(['product', 'user'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('product', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('slug', 'like', "%{$search}%");
                })->orWhereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $totalReviews = Review::count();

        return view('admin.reviews.index', compact('reviews', 'search', 'totalReviews'));
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        // Delete stored images if any
        $images = $review->images;
        if (is_string($images)) {
            $decoded = json_decode($images, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $images = $decoded;
            }
        }
        if (is_array($images)) {
            foreach ($images as $path) {
                if ($path && Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }
        }

        $productId = $review->product_id;
        $review->delete();
        $this->updateProductRatingStats($productId);

        return redirect()->route('admin.reviews.index')
            ->with('success', __('messages.review_deleted_success'));
    }

    /**
     * Delete all reviews and their images; reset product stats.
     */
    public function deleteAll(Request $request)
    {
        $total = Review::count();

        if ($total === 0) {
            return redirect()->route('admin.reviews.index')
                ->with('info', __('messages.no_records_to_delete'));
        }

        // Delete images in chunks to avoid memory issues
        Review::select('id', 'images')->chunkById(500, function ($batch) {
            foreach ($batch as $review) {
                $images = $review->images;
                if (is_string($images)) {
                    $decoded = json_decode($images, true);
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $images = $decoded;
                    }
                }
                if (is_array($images)) {
                    foreach ($images as $path) {
                        if ($path && Storage::disk('public')->exists($path)) {
                            Storage::disk('public')->delete($path);
                        }
                    }
                }
            }
        });

        // Delete all reviews
        DB::table('reviews')->delete();

        // Reset rating stats for all products
        Product::query()->update([
            'avg_rating' => 0,
            'reviews_count' => 0,
        ]);

        return redirect()->route('admin.reviews.index')
            ->with('success', __('messages.all_records_deleted_successfully') . ' (' . $total . ')');
    }


    /**
     * Recalculate and persist product rating stats.
     */
    protected function updateProductRatingStats($productId): void
    {
        $product = Product::find($productId);
        if (!$product) {
            return;
        }

        $reviews = Review::where('product_id', $productId)->get();
        $count = $reviews->count();
        $avg = $count > 0 ? round($reviews->avg('rating'), 1) : 0;

        $product->reviews_count = $count;
        $product->avg_rating = $avg;
        $product->save();
    }
}

