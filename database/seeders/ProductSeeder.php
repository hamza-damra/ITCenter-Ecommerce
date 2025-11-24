<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Review;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use App\Models\Attribute;
use App\Models\AttributeValue;
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
        for ($i = 0; $i < 50; $i++) {
            $category = $categories->random();
            $product = Product::factory()->create([
                'category_id' => $category->id,
                'brand_id' => $brands->random()->id,
            ]);

            // Assign attributes based on category
            $this->assignAttributesToProduct($product, $category);

            // Create primary image
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $product->main_image,
                'order' => 0,
                'is_primary' => true,
                'alt_text' => $product->name . ' - Main Image',
            ]);

            // Create 2-4 additional images for each product
            for ($j = 1; $j <= rand(2, 4); $j++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'https://picsum.photos/seed/' . \Illuminate\Support\Str::random(10) . '/800/800',
                    'order' => $j,
                    'is_primary' => false,
                    'alt_text' => $product->name . ' - Image ' . $j,
                ]);
            }
        }

        // Create some featured products
        for ($i = 0; $i < 10; $i++) {
            $category = $categories->random();
            $product = Product::factory()->featured()->create([
                'category_id' => $category->id,
                'brand_id' => $brands->random()->id,
            ]);

            // Assign attributes based on category
            $this->assignAttributesToProduct($product, $category);

            // Create primary image
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $product->main_image,
                'order' => 0,
                'is_primary' => true,
                'alt_text' => $product->name . ' - Main Image',
            ]);

            // Create additional images
            for ($j = 1; $j <= rand(2, 4); $j++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'https://picsum.photos/seed/' . \Illuminate\Support\Str::random(10) . '/800/800',
                    'order' => $j,
                    'is_primary' => false,
                    'alt_text' => $product->name . ' - Image ' . $j,
                ]);
            }
        }

        // Create some products on sale
        for ($i = 0; $i < 15; $i++) {
            $category = $categories->random();
            $product = Product::factory()->onSale()->create([
                'category_id' => $category->id,
                'brand_id' => $brands->random()->id,
            ]);

            // Assign attributes based on category
            $this->assignAttributesToProduct($product, $category);

            // Create primary image
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $product->main_image,
                'order' => 0,
                'is_primary' => true,
                'alt_text' => $product->name . ' - Main Image',
            ]);

            // Create additional images
            for ($j = 1; $j <= rand(2, 4); $j++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'https://picsum.photos/seed/' . \Illuminate\Support\Str::random(10) . '/800/800',
                    'order' => $j,
                    'is_primary' => false,
                    'alt_text' => $product->name . ' - Image ' . $j,
                ]);
            }
        }

        // Create some bestseller products
        for ($i = 0; $i < 10; $i++) {
            $category = $categories->random();
            $product = Product::factory()->bestseller()->create([
                'category_id' => $category->id,
                'brand_id' => $brands->random()->id,
            ]);

            // Assign attributes based on category
            $this->assignAttributesToProduct($product, $category);

            // Create primary image
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $product->main_image,
                'order' => 0,
                'is_primary' => true,
                'alt_text' => $product->name . ' - Main Image',
            ]);

            // Create additional images
            for ($j = 1; $j <= rand(2, 4); $j++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'https://picsum.photos/seed/' . \Illuminate\Support\Str::random(10) . '/800/800',
                    'order' => $j,
                    'is_primary' => false,
                    'alt_text' => $product->name . ' - Image ' . $j,
                ]);
            }
        }

        // Create some strong offers products
        for ($i = 0; $i < 20; $i++) {
            $category = $categories->random();
            $product = Product::factory()->create([
                'category_id' => $category->id,
                'brand_id' => $brands->random()->id,
                'is_strong_offer' => true,
                'discount_percentage' => rand(10, 50),
            ]);

            // Assign attributes based on category
            $this->assignAttributesToProduct($product, $category);

            // Create primary image
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $product->main_image,
                'order' => 0,
                'is_primary' => true,
                'alt_text' => $product->name . ' - Main Image',
            ]);

            // Create additional images
            for ($j = 1; $j <= rand(2, 4); $j++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'https://picsum.photos/seed/' . \Illuminate\Support\Str::random(10) . '/800/800',
                    'order' => $j,
                    'is_primary' => false,
                    'alt_text' => $product->name . ' - Image ' . $j,
                ]);
            }
        }

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

    /**
     * Assign attributes to a product based on its category
     */
    protected function assignAttributesToProduct(Product $product, Category $category): void
    {
        // Get attributes assigned to this category
        $attributes = $category->attributes;

        if ($attributes->isEmpty()) {
            return;
        }

        // For each attribute, randomly select 1-2 values
        foreach ($attributes as $attribute) {
            $values = $attribute->values()->active()->get();
            
            if ($values->isEmpty()) {
                continue;
            }

            // Randomly select 1-2 values for this attribute
            $selectedValues = $values->random(min(rand(1, 2), $values->count()));
            
            // Attach the selected values to the product
            foreach ($selectedValues as $value) {
                $product->attributeValues()->attach($value->id);
            }
        }
    }
}
