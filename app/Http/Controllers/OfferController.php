<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function index()
    {
        $activeOffers = Offer::with(['products'])
            ->active()
            ->get();

        $upcomingOffers = Offer::upcoming()
            ->orderBy('start_date')
            ->limit(5)
            ->get();

        return view('offers', compact('activeOffers', 'upcomingOffers'));
    }

    public function show($slug)
    {
        $offer = Offer::with(['products.brand', 'products.category'])
            ->where('slug', $slug)
            ->firstOrFail();

        if (!$offer->isValid()) {
            abort(404, 'This offer is no longer available.');
        }

        return view('offer-detail', compact('offer'));
    }
}
