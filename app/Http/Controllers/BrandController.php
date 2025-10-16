<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;

/**
 * Web Controller - Returns views only
 * All business logic moved to API controllers
 */
class BrandController extends Controller
{
    public function index()
    {
        $locale = app()->getLocale();
        $nameColumn = "name_{$locale}";
        
        $brands = Brand::withCount('products')
            ->orderBy($nameColumn)
            ->get();
            
        return view('brands', compact('brands'));
    }

    public function show($slug)
    {
        $brand = Brand::where('slug', $slug)->firstOrFail();
        
        return view('brand-products', compact('slug', 'brand'));
    }
}
