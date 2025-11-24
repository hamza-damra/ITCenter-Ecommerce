<?php

namespace Database\Factories;

use App\Models\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AttributeFactory extends Factory
{
    protected $model = Attribute::class;

    public function definition(): array
    {
        $nameEn = $this->faker->words(2, true);
        
        return [
            'name_en' => ucfirst($nameEn),
            'name_ar' => 'سمة ' . $this->faker->word(),
            'name_he' => 'תכונה ' . $this->faker->word(),
            'slug' => Str::slug($nameEn) . '-' . $this->faker->unique()->numberBetween(1, 10000),
            'type' => $this->faker->randomElement(['select', 'color', 'button', 'radio']),
            'unit' => $this->faker->optional(0.3)->randomElement(['Hz', 'GB', 'inches', 'mm', 'W']),
            'is_filterable' => $this->faker->boolean(80),
            'order' => $this->faker->numberBetween(0, 100),
            'is_active' => true,
        ];
    }
}
