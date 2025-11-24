<?php

namespace Database\Factories;

use App\Models\AttributeValue;
use App\Models\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AttributeValueFactory extends Factory
{
    protected $model = AttributeValue::class;

    public function definition(): array
    {
        $valueEn = $this->faker->words(2, true);
        
        return [
            'attribute_id' => Attribute::factory(),
            'value_en' => ucfirst($valueEn),
            'value_ar' => 'قيمة ' . $this->faker->word(),
            'value_he' => 'ערך ' . $this->faker->word(),
            'slug' => Str::slug($valueEn) . '-' . $this->faker->unique()->numberBetween(1, 10000),
            'color_code' => $this->faker->optional(0.2)->hexColor(),
            'order' => $this->faker->numberBetween(0, 100),
            'is_active' => true,
        ];
    }
}
