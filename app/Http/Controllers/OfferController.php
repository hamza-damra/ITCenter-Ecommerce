<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Offer;

/**
 * Web Controller - Returns views only
 * All business logic moved to API controllers
 */
class OfferController extends Controller
{
    public function index()
    {
        $offers = Offer::with('products')
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('offers', compact('offers'));
    }

    public function show($slug)
    {
        $offer = Offer::with('products.images')
            ->where('slug', $slug)
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->firstOrFail();
        
        return view('offer-detail', compact('slug', 'offer'));
    }
}
