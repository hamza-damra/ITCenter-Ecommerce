<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->words(2, true);
        
        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name),
            'description' => $this->faker->sentence(15),
            'image' => 'https://picsum.photos/seed/' . Str::random(10) . '/640/480',
            'parent_id' => null,
            'is_active' => $this->faker->boolean(90),
            'order' => $this->faker->numberBetween(0, 100),
            'meta_title' => ucfirst($name) . ' - IT Center',
            'meta_description' => $this->faker->sentence(20),
            'meta_keywords' => implode(', ', $this->faker->words(5)),
        ];
    }

    /**
     * Indicate that the category is a subcategory.
     */
    public function subcategory(): static
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => Category::factory(),
        ]);
    }
}
