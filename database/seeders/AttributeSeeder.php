<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Seeder;

class AttributeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Color Attribute
        $colorAttr = Attribute::create([
            'name' => 'Color',
            'slug' => 'color',
            'type' => 'color',
            'order' => 1,
            'is_active' => true,
        ]);

        $colors = [
            ['value' => 'Black', 'color_code' => '#000000'],
            ['value' => 'White', 'color_code' => '#FFFFFF'],
            ['value' => 'Silver', 'color_code' => '#C0C0C0'],
            ['value' => 'Gray', 'color_code' => '#808080'],
            ['value' => 'Blue', 'color_code' => '#0000FF'],
            ['value' => 'Red', 'color_code' => '#FF0000'],
            ['value' => 'Green', 'color_code' => '#00FF00'],
            ['value' => 'Gold', 'color_code' => '#FFD700'],
        ];

        foreach ($colors as $index => $color) {
            AttributeValue::create([
                'attribute_id' => $colorAttr->id,
                'value' => $color['value'],
                'color_code' => $color['color_code'],
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // Storage Attribute
        $storageAttr = Attribute::create([
            'name' => 'Storage',
            'slug' => 'storage',
            'type' => 'button',
            'order' => 2,
            'is_active' => true,
        ]);

        $storageOptions = ['128GB', '256GB', '512GB', '1TB', '2TB', '4TB'];
        foreach ($storageOptions as $index => $storage) {
            AttributeValue::create([
                'attribute_id' => $storageAttr->id,
                'value' => $storage,
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // RAM Attribute
        $ramAttr = Attribute::create([
            'name' => 'RAM',
            'slug' => 'ram',
            'type' => 'button',
            'order' => 3,
            'is_active' => true,
        ]);

        $ramOptions = ['4GB', '8GB', '16GB', '32GB', '64GB', '128GB'];
        foreach ($ramOptions as $index => $ram) {
            AttributeValue::create([
                'attribute_id' => $ramAttr->id,
                'value' => $ram,
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // Screen Size Attribute
        $screenAttr = Attribute::create([
            'name' => 'Screen Size',
            'slug' => 'screen-size',
            'type' => 'select',
            'order' => 4,
            'is_active' => true,
        ]);

        $screenSizes = ['13"', '14"', '15.6"', '17"', '24"', '27"', '32"'];
        foreach ($screenSizes as $index => $size) {
            AttributeValue::create([
                'attribute_id' => $screenAttr->id,
                'value' => $size,
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // Processor Attribute
        $processorAttr = Attribute::create([
            'name' => 'Processor',
            'slug' => 'processor',
            'type' => 'select',
            'order' => 5,
            'is_active' => true,
        ]);

        $processors = [
            'Intel Core i3',
            'Intel Core i5',
            'Intel Core i7',
            'Intel Core i9',
            'AMD Ryzen 3',
            'AMD Ryzen 5',
            'AMD Ryzen 7',
            'AMD Ryzen 9',
        ];
        foreach ($processors as $index => $processor) {
            AttributeValue::create([
                'attribute_id' => $processorAttr->id,
                'value' => $processor,
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }
    }
}
