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
        
        // Fallback to English if the locale column doesn't exist
        $availableColumns = ['name_en', 'name_ar'];
        if (!in_array($nameColumn, $availableColumns)) {
            $nameColumn = 'name_en';
        }
        
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
