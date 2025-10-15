<?php

namespace Database\Factories;

use App\Models\ProductImage;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'image_path' => 'https://picsum.photos/seed/' . \Illuminate\Support\Str::random(10) . '/800/800',
            'thumbnail_path' => null,
            'order' => $this->faker->numberBetween(0, 10),
            'is_primary' => false,
            'alt_text' => $this->faker->sentence(4),
        ];
    }
}
