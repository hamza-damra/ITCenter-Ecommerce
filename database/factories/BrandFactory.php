<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BrandFactory extends Factory
{
    protected $model = Brand::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->company();
        
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(3),
            'logo' => 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&size=300&background=random',
            'website' => $this->faker->url(),
            'email' => $this->faker->companyEmail(),
            'phone' => $this->faker->phoneNumber(),
            'is_active' => $this->faker->boolean(95),
            'is_featured' => $this->faker->boolean(30),
            'order' => $this->faker->numberBetween(0, 100),
            'meta_title' => $name . ' - IT Center',
            'meta_description' => $this->faker->sentence(20),
            'meta_keywords' => implode(', ', $this->faker->words(5)),
        ];
    }
}
