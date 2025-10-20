<?php

// Test script to check home sections
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Product;

echo "=== Testing Home Sections ===\n\n";

// Test New Products
$newProducts = Product::with(['brand:id,name_en,name_ar,name_he,slug', 'category:id,name_en,name_ar,name_he,slug'])
    ->select('id', 'name_en', 'name_ar', 'name_he', 'slug', 'price', 'sale_price', 'main_image', 'short_description_en', 'short_description_ar', 'short_description_he', 'is_new', 'is_featured', 'brand_id', 'category_id')
    ->active()
    ->new()
    ->limit(8)
    ->get();

echo "New Products Count: " . $newProducts->count() . "\n";
echo "Products:\n";
foreach ($newProducts as $product) {
    echo "  - ID: {$product->id}, Name: {$product->name_ar}, is_new: {$product->is_new}\n";
}

echo "\n";

// Test Featured Products
$featuredProducts = Product::active()->featured()->limit(8)->get();
echo "Featured Products Count: " . $featuredProducts->count() . "\n";

// Test Bestsellers
$bestsellerProducts = Product::active()->bestseller()->limit(8)->get();
echo "Bestseller Products Count: " . $bestsellerProducts->count() . "\n";

// Test On Sale
$onSaleProducts = Product::active()
    ->whereNotNull('sale_price')
    ->where('sale_price', '<', \DB::raw('price'))
    ->limit(8)
    ->get();
echo "On Sale Products Count: " . $onSaleProducts->count() . "\n";
