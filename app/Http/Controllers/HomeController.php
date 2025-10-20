<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Offer;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function index()
    {
        // Optimize queries with eager loading
        $featuredProducts = Product::with(['brand:id,name_en,name_ar,name_he,slug', 'category:id,name_en,name_ar,name_he,slug'])
            ->select('id', 'name_en', 'name_ar', 'name_he', 'slug', 'price', 'sale_price', 'main_image', 'short_description_en', 'short_description_ar', 'short_description_he', 'is_new', 'is_featured', 'brand_id', 'category_id')
            ->active()
            ->featured()
            ->limit(8)
            ->get();

        $newProducts = Product::with(['brand:id,name_en,name_ar,name_he,slug', 'category:id,name_en,name_ar,name_he,slug'])
            ->select('id', 'name_en', 'name_ar', 'name_he', 'slug', 'price', 'sale_price', 'main_image', 'short_description_en', 'short_description_ar', 'short_description_he', 'is_new', 'is_featured', 'brand_id', 'category_id')
            ->active()
            ->new()
            ->limit(8)
            ->get();

        $bestsellerProducts = Product::with(['brand:id,name_en,name_ar,name_he,slug', 'category:id,name_en,name_ar,name_he,slug'])
            ->select('id', 'name_en', 'name_ar', 'name_he', 'slug', 'price', 'sale_price', 'main_image', 'short_description_en', 'short_description_ar', 'short_description_he', 'is_new', 'is_featured', 'brand_id', 'category_id')
            ->active()
            ->bestseller()
            ->limit(8)
            ->get();

        $onSaleProducts = Product::with(['brand:id,name_en,name_ar,name_he,slug', 'category:id,name_en,name_ar,name_he,slug'])
            ->select('id', 'name_en', 'name_ar', 'name_he', 'slug', 'price', 'sale_price', 'main_image', 'short_description_en', 'short_description_ar', 'short_description_he', 'is_new', 'is_featured', 'brand_id', 'category_id')
            ->active()
            ->whereNotNull('sale_price')
            ->where('sale_price', '<', \DB::raw('price'))
            ->limit(8)
            ->get();

        $categories = Category::select('id', 'name_en', 'name_ar', 'name_he', 'slug', 'image', 'order')
            ->active()
            ->parent()
            ->withCount('products')
            ->orderBy('order')
            ->limit(20)
            ->get();

        $featuredBrands = Brand::select('id', 'name_en', 'name_ar', 'name_he', 'slug', 'logo', 'order')
            ->active()
            ->featured()
            ->orderBy('order')
            ->limit(12)
            ->get();

        $activeOffers = Offer::select('id', 'name_en', 'name_ar', 'slug', 'description_en', 'description_ar', 'discount_type', 'discount_value', 'start_date', 'end_date', 'banner_image')
            ->active()
            ->limit(3)
            ->get();

        // Get cart product IDs for current user/session
        $cartProductIds = $this->getCartProductIds();

        return view('home', compact(
            'featuredProducts',
            'newProducts',
            'bestsellerProducts',
            'onSaleProducts',
            'categories',
            'featuredBrands',
            'activeOffers',
            'cartProductIds'
        ));
    }

    /**
     * Get cart product IDs for current user/session
     */
    private function getCartProductIds()
    {
        $identifier = $this->getCartIdentifier();

        return CartItem::where(function($query) use ($identifier) {
            if (isset($identifier['user_id'])) {
                $query->where('user_id', $identifier['user_id']);
            } else {
                $query->where('session_id', $identifier['session_id']);
            }
        })->pluck('product_id')->toArray();
    }

    /**
     * Get cart identifier (user_id or session_id)
     */
    private function getCartIdentifier()
    {
        if (Auth::check()) {
            return ['user_id' => Auth::id()];
        }

        // Ensure session is started
        if (!Session::isStarted()) {
            Session::start();
        }

        return ['session_id' => Session::getId()];
    }
}
