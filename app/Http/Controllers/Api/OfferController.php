<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Models\Offer;
use App\Traits\ApiResponses;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    use ApiResponses;
    /**
     * Get all active offers
     */
    public function index()
    {
        $activeOffers = Offer::with(['products'])
            ->active()
            ->get();

        $upcomingOffers = Offer::upcoming()
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'active_offers' => OfferResource::collection($activeOffers),
                'upcoming_offers' => OfferResource::collection($upcomingOffers),
            ]
        ]);
    }

    /**
     * Get a specific offer
     */
    public function show($slug)
    {
        $offer = Offer::with(['products.brand', 'products.category', 'products.images'])
            ->where('slug', $slug)
            ->firstOrFail();

        if (!$offer->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'This offer is no longer available.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'offer' => new OfferResource($offer),
            ]
        ]);
    }
}
