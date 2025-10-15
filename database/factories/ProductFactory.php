<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(3, true);
        $price = $this->faker->randomFloat(2, 50, 5000);
        $hasSale = $this->faker->boolean(30);
        $salePrice = $hasSale ? $this->faker->randomFloat(2, $price * 0.5, $price * 0.9) : null;
        
        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'sku' => 'SKU-' . strtoupper(Str::random(10)),
            'short_description' => $this->faker->sentence(10),
            'description' => $this->faker->paragraphs(5, true),
            'price' => $price,
            'sale_price' => $salePrice,
            'cost_price' => $this->faker->randomFloat(2, $price * 0.3, $price * 0.6),
            'stock_quantity' => $this->faker->numberBetween(0, 500),
            'min_stock_quantity' => $this->faker->numberBetween(5, 20),
            'main_image' => 'https://picsum.photos/seed/' . Str::random(10) . '/800/800',
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'is_active' => $this->faker->boolean(95),
            'is_featured' => $this->faker->boolean(20),
            'is_new' => $this->faker->boolean(25),
            'is_bestseller' => $this->faker->boolean(15),
            'track_stock' => $this->faker->boolean(85),
            'stock_status' => $this->faker->randomElement(['in_stock', 'out_of_stock', 'on_backorder']),
            'weight' => $this->faker->randomFloat(2, 0.1, 50),
            'length' => $this->faker->randomFloat(2, 5, 200),
            'width' => $this->faker->randomFloat(2, 5, 200),
            'height' => $this->faker->randomFloat(2, 5, 200),
            'warranty' => $this->faker->randomElement(['1 Year', '2 Years', '3 Years', 'Lifetime', null]),
            'views_count' => $this->faker->numberBetween(0, 10000),
            'sales_count' => $this->faker->numberBetween(0, 500),
            'avg_rating' => $this->faker->randomFloat(1, 0, 5),
            'reviews_count' => $this->faker->numberBetween(0, 200),
            'meta_title' => ucfirst($name) . ' - IT Center',
            'meta_description' => $this->faker->sentence(20),
            'meta_keywords' => implode(', ', $this->faker->words(8)),
            'specifications' => [
                'Processor' => $this->faker->randomElement(['Intel Core i5', 'Intel Core i7', 'AMD Ryzen 5', 'AMD Ryzen 7']),
                'RAM' => $this->faker->randomElement(['8GB', '16GB', '32GB', '64GB']),
                'Storage' => $this->faker->randomElement(['256GB SSD', '512GB SSD', '1TB SSD', '2TB HDD']),
                'Display' => $this->faker->randomElement(['15.6" FHD', '17.3" FHD', '14" QHD', '15.6" 4K']),
            ],
        ];
    }

    /**
     * Indicate that the product is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * Indicate that the product is new.
     */
    public function newProduct(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_new' => true,
        ]);
    }

    /**
     * Indicate that the product is a bestseller.
     */
    public function bestseller(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_bestseller' => true,
        ]);
    }

    /**
     * Indicate that the product is on sale.
     */
    public function onSale(): static
    {
        return $this->state(function (array $attributes) {
            $price = $attributes['price'];
            return [
                'sale_price' => $price * 0.8,
            ];
        });
    }
}
