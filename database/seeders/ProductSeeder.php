<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Review;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing categories and brands
        $categories = Category::whereNotNull('parent_id')->get(); // Get subcategories
        $brands = Brand::all();
        
        if ($categories->isEmpty()) {
            $categories = Category::all();
        }

        // Create 50 products
        $products = Product::factory()
            ->count(50)
            ->create()
            ->each(function ($product) use ($categories, $brands) {
                // Assign random category and brand
                $product->update([
                    'category_id' => $categories->random()->id,
                    'brand_id' => $brands->random()->id,
                ]);

                // Create primary image
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $product->main_image,
                    'order' => 0,
                    'is_primary' => true,
                    'alt_text' => $product->name . ' - Main Image',
                ]);

                // Create 2-4 additional images for each product
                for ($i = 1; $i <= rand(2, 4); $i++) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => 'https://picsum.photos/seed/' . \Illuminate\Support\Str::random(10) . '/800/800',
                        'order' => $i,
                        'is_primary' => false,
                        'alt_text' => $product->name . ' - Image ' . $i,
                    ]);
                }
            });

        // Create some featured products
        Product::factory()
            ->count(10)
            ->featured()
            ->create()
            ->each(function ($product) use ($categories, $brands) {
                $product->update([
                    'category_id' => $categories->random()->id,
                    'brand_id' => $brands->random()->id,
                ]);

                // Create primary image
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $product->main_image,
                    'order' => 0,
                    'is_primary' => true,
                    'alt_text' => $product->name . ' - Main Image',
                ]);

                // Create additional images
                for ($i = 1; $i <= rand(2, 4); $i++) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => 'https://picsum.photos/seed/' . \Illuminate\Support\Str::random(10) . '/800/800',
                        'order' => $i,
                        'is_primary' => false,
                        'alt_text' => $product->name . ' - Image ' . $i,
                    ]);
                }
            });

        // Create some products on sale
        Product::factory()
            ->count(15)
            ->onSale()
            ->create()
            ->each(function ($product) use ($categories, $brands) {
                $product->update([
                    'category_id' => $categories->random()->id,
                    'brand_id' => $brands->random()->id,
                ]);

                // Create primary image
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $product->main_image,
                    'order' => 0,
                    'is_primary' => true,
                    'alt_text' => $product->name . ' - Main Image',
                ]);

                // Create additional images
                for ($i = 1; $i <= rand(2, 4); $i++) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => 'https://picsum.photos/seed/' . \Illuminate\Support\Str::random(10) . '/800/800',
                        'order' => $i,
                        'is_primary' => false,
                        'alt_text' => $product->name . ' - Image ' . $i,
                    ]);
                }
            });

        // Create some bestseller products
        Product::factory()
            ->count(10)
            ->bestseller()
            ->create()
            ->each(function ($product) use ($categories, $brands) {
                $product->update([
                    'category_id' => $categories->random()->id,
                    'brand_id' => $brands->random()->id,
                ]);

                // Create primary image
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $product->main_image,
                    'order' => 0,
                    'is_primary' => true,
                    'alt_text' => $product->name . ' - Main Image',
                ]);

                // Create additional images
                for ($i = 1; $i <= rand(2, 4); $i++) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => 'https://picsum.photos/seed/' . \Illuminate\Support\Str::random(10) . '/800/800',
                        'order' => $i,
                        'is_primary' => false,
                        'alt_text' => $product->name . ' - Image ' . $i,
                    ]);
                }
            });

        // Create reviews for some products
        $allProducts = Product::all();
        $users = User::factory()->count(20)->create();

        foreach ($allProducts->random(30) as $product) {
            Review::factory()
                ->count(rand(2, 10))
                ->create([
                    'product_id' => $product->id,
                    'user_id' => $users->random()->id,
                ])
                ->each(function ($review) use ($product) {
                    // Update product rating after each review
                    $product->updateRating();
                });
        }
    }
}
