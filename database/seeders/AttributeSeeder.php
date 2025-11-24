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
            'name_en' => 'Color',
            'name_ar' => 'اللون',
            'name_he' => 'צבע',
            'slug' => 'color',
            'type' => 'color',
            'unit' => null,
            'is_filterable' => true,
            'order' => 1,
            'is_active' => true,
        ]);

        $colors = [
            ['value_en' => 'Black', 'value_ar' => 'أسود', 'value_he' => 'שחור', 'slug' => 'black', 'color_code' => '#000000'],
            ['value_en' => 'White', 'value_ar' => 'أبيض', 'value_he' => 'לבן', 'slug' => 'white', 'color_code' => '#FFFFFF'],
            ['value_en' => 'Silver', 'value_ar' => 'فضي', 'value_he' => 'כסף', 'slug' => 'silver', 'color_code' => '#C0C0C0'],
            ['value_en' => 'Gray', 'value_ar' => 'رمادي', 'value_he' => 'אפור', 'slug' => 'gray', 'color_code' => '#808080'],
            ['value_en' => 'Blue', 'value_ar' => 'أزرق', 'value_he' => 'כחול', 'slug' => 'blue', 'color_code' => '#0000FF'],
            ['value_en' => 'Red', 'value_ar' => 'أحمر', 'value_he' => 'אדום', 'slug' => 'red', 'color_code' => '#FF0000'],
            ['value_en' => 'Green', 'value_ar' => 'أخضر', 'value_he' => 'ירוק', 'slug' => 'green', 'color_code' => '#00FF00'],
            ['value_en' => 'Gold', 'value_ar' => 'ذهبي', 'value_he' => 'זהב', 'slug' => 'gold', 'color_code' => '#FFD700'],
        ];

        foreach ($colors as $index => $color) {
            AttributeValue::create([
                'attribute_id' => $colorAttr->id,
                'value_en' => $color['value_en'],
                'value_ar' => $color['value_ar'],
                'value_he' => $color['value_he'],
                'slug' => $color['slug'],
                'color_code' => $color['color_code'],
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // Storage Attribute
        $storageAttr = Attribute::create([
            'name_en' => 'Storage',
            'name_ar' => 'التخزين',
            'name_he' => 'אחסון',
            'slug' => 'storage',
            'type' => 'button',
            'unit' => 'GB',
            'is_filterable' => true,
            'order' => 2,
            'is_active' => true,
        ]);

        $storageOptions = [
            ['value' => '128GB', 'slug' => '128gb'],
            ['value' => '256GB', 'slug' => '256gb'],
            ['value' => '512GB', 'slug' => '512gb'],
            ['value' => '1TB', 'slug' => '1tb'],
            ['value' => '2TB', 'slug' => '2tb'],
            ['value' => '4TB', 'slug' => '4tb'],
        ];
        foreach ($storageOptions as $index => $storage) {
            AttributeValue::create([
                'attribute_id' => $storageAttr->id,
                'value_en' => $storage['value'],
                'value_ar' => $storage['value'],
                'value_he' => $storage['value'],
                'slug' => $storage['slug'],
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // RAM Attribute
        $ramAttr = Attribute::create([
            'name_en' => 'RAM',
            'name_ar' => 'الذاكرة العشوائية',
            'name_he' => 'זיכרון RAM',
            'slug' => 'ram',
            'type' => 'button',
            'unit' => 'GB',
            'is_filterable' => true,
            'order' => 3,
            'is_active' => true,
        ]);

        $ramOptions = [
            ['value' => '4GB', 'slug' => '4gb'],
            ['value' => '8GB', 'slug' => '8gb'],
            ['value' => '16GB', 'slug' => '16gb'],
            ['value' => '32GB', 'slug' => '32gb'],
            ['value' => '64GB', 'slug' => '64gb'],
            ['value' => '128GB', 'slug' => '128gb'],
        ];
        foreach ($ramOptions as $index => $ram) {
            AttributeValue::create([
                'attribute_id' => $ramAttr->id,
                'value_en' => $ram['value'],
                'value_ar' => $ram['value'],
                'value_he' => $ram['value'],
                'slug' => $ram['slug'],
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // Screen Size Attribute
        $screenAttr = Attribute::create([
            'name_en' => 'Screen Size',
            'name_ar' => 'حجم الشاشة',
            'name_he' => 'גודל מסך',
            'slug' => 'screen-size',
            'type' => 'select',
            'unit' => 'inches',
            'is_filterable' => true,
            'order' => 4,
            'is_active' => true,
        ]);

        $screenSizes = [
            ['value' => '13"', 'slug' => '13-inch'],
            ['value' => '14"', 'slug' => '14-inch'],
            ['value' => '15.6"', 'slug' => '15-6-inch'],
            ['value' => '17"', 'slug' => '17-inch'],
            ['value' => '24"', 'slug' => '24-inch'],
            ['value' => '27"', 'slug' => '27-inch'],
            ['value' => '32"', 'slug' => '32-inch'],
        ];
        foreach ($screenSizes as $index => $size) {
            AttributeValue::create([
                'attribute_id' => $screenAttr->id,
                'value_en' => $size['value'],
                'value_ar' => $size['value'],
                'value_he' => $size['value'],
                'slug' => $size['slug'],
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // Processor Attribute
        $processorAttr = Attribute::create([
            'name_en' => 'Processor',
            'name_ar' => 'المعالج',
            'name_he' => 'מעבד',
            'slug' => 'processor',
            'type' => 'select',
            'unit' => null,
            'is_filterable' => true,
            'order' => 5,
            'is_active' => true,
        ]);

        $processors = [
            ['value' => 'Intel Core i3', 'slug' => 'intel-core-i3'],
            ['value' => 'Intel Core i5', 'slug' => 'intel-core-i5'],
            ['value' => 'Intel Core i7', 'slug' => 'intel-core-i7'],
            ['value' => 'Intel Core i9', 'slug' => 'intel-core-i9'],
            ['value' => 'AMD Ryzen 3', 'slug' => 'amd-ryzen-3'],
            ['value' => 'AMD Ryzen 5', 'slug' => 'amd-ryzen-5'],
            ['value' => 'AMD Ryzen 7', 'slug' => 'amd-ryzen-7'],
            ['value' => 'AMD Ryzen 9', 'slug' => 'amd-ryzen-9'],
        ];
        foreach ($processors as $index => $processor) {
            AttributeValue::create([
                'attribute_id' => $processorAttr->id,
                'value_en' => $processor['value'],
                'value_ar' => $processor['value'],
                'value_he' => $processor['value'],
                'slug' => $processor['slug'],
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // Refresh Rate Attribute (for Monitors)
        $refreshRateAttr = Attribute::create([
            'name_en' => 'Refresh Rate',
            'name_ar' => 'معدل التحديث',
            'name_he' => 'קצב רענון',
            'slug' => 'refresh-rate',
            'type' => 'select',
            'unit' => 'Hz',
            'is_filterable' => true,
            'order' => 6,
            'is_active' => true,
        ]);

        $refreshRates = [
            ['value' => '60Hz', 'slug' => '60hz'],
            ['value' => '75Hz', 'slug' => '75hz'],
            ['value' => '120Hz', 'slug' => '120hz'],
            ['value' => '144Hz', 'slug' => '144hz'],
            ['value' => '165Hz', 'slug' => '165hz'],
            ['value' => '240Hz', 'slug' => '240hz'],
            ['value' => '360Hz', 'slug' => '360hz'],
        ];
        foreach ($refreshRates as $index => $rate) {
            AttributeValue::create([
                'attribute_id' => $refreshRateAttr->id,
                'value_en' => $rate['value'],
                'value_ar' => $rate['value'],
                'value_he' => $rate['value'],
                'slug' => $rate['slug'],
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // Panel Type Attribute (for Monitors)
        $panelTypeAttr = Attribute::create([
            'name_en' => 'Panel Type',
            'name_ar' => 'نوع اللوحة',
            'name_he' => 'סוג פאנל',
            'slug' => 'panel-type',
            'type' => 'select',
            'unit' => null,
            'is_filterable' => true,
            'order' => 7,
            'is_active' => true,
        ]);

        $panelTypes = [
            ['value_en' => 'IPS', 'value_ar' => 'IPS', 'value_he' => 'IPS', 'slug' => 'ips'],
            ['value_en' => 'TN', 'value_ar' => 'TN', 'value_he' => 'TN', 'slug' => 'tn'],
            ['value_en' => 'VA', 'value_ar' => 'VA', 'value_he' => 'VA', 'slug' => 'va'],
            ['value_en' => 'OLED', 'value_ar' => 'OLED', 'value_he' => 'OLED', 'slug' => 'oled'],
        ];
        foreach ($panelTypes as $index => $type) {
            AttributeValue::create([
                'attribute_id' => $panelTypeAttr->id,
                'value_en' => $type['value_en'],
                'value_ar' => $type['value_ar'],
                'value_he' => $type['value_he'],
                'slug' => $type['slug'],
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // Resolution Attribute (for Monitors)
        $resolutionAttr = Attribute::create([
            'name_en' => 'Resolution',
            'name_ar' => 'الدقة',
            'name_he' => 'רזולוציה',
            'slug' => 'resolution',
            'type' => 'select',
            'unit' => null,
            'is_filterable' => true,
            'order' => 8,
            'is_active' => true,
        ]);

        $resolutions = [
            ['value' => '1920x1080 (Full HD)', 'slug' => '1920x1080'],
            ['value' => '2560x1440 (QHD)', 'slug' => '2560x1440'],
            ['value' => '3840x2160 (4K)', 'slug' => '3840x2160'],
            ['value' => '5120x1440 (5K Ultrawide)', 'slug' => '5120x1440'],
        ];
        foreach ($resolutions as $index => $resolution) {
            AttributeValue::create([
                'attribute_id' => $resolutionAttr->id,
                'value_en' => $resolution['value'],
                'value_ar' => $resolution['value'],
                'value_he' => $resolution['value'],
                'slug' => $resolution['slug'],
                'order' => $index + 1,
                'is_active' => true,
            ]);
        }

        // Now assign attributes to categories
        $this->assignAttributesToCategories();
    }

    /**
     * Assign attributes to specific categories
     */
    protected function assignAttributesToCategories(): void
    {
        // Get categories
        $laptopsCategory = \App\Models\Category::where('slug', 'laptops')->first();
        $monitorsCategory = \App\Models\Category::where('slug', 'monitors')->first();
        $graphicsCardsCategory = \App\Models\Category::where('slug', 'graphics-cards')->first();
        $keyboardsCategory = \App\Models\Category::where('slug', 'keyboards')->first();
        $miceCategory = \App\Models\Category::where('slug', 'mice')->first();

        // Get attributes
        $colorAttr = Attribute::where('slug', 'color')->first();
        $storageAttr = Attribute::where('slug', 'storage')->first();
        $ramAttr = Attribute::where('slug', 'ram')->first();
        $screenSizeAttr = Attribute::where('slug', 'screen-size')->first();
        $processorAttr = Attribute::where('slug', 'processor')->first();
        $refreshRateAttr = Attribute::where('slug', 'refresh-rate')->first();
        $panelTypeAttr = Attribute::where('slug', 'panel-type')->first();
        $resolutionAttr = Attribute::where('slug', 'resolution')->first();

        // Assign to Laptops (all laptop subcategories)
        if ($laptopsCategory) {
            foreach ($laptopsCategory->children as $subcategory) {
                $subcategory->attributes()->attach([
                    $colorAttr->id,
                    $storageAttr->id,
                    $ramAttr->id,
                    $screenSizeAttr->id,
                    $processorAttr->id,
                ]);
            }
        }

        // Assign to Monitors
        if ($monitorsCategory) {
            $monitorsCategory->attributes()->attach([
                $screenSizeAttr->id,
                $refreshRateAttr->id,
                $panelTypeAttr->id,
                $resolutionAttr->id,
            ]);
        }

        // Assign to Graphics Cards
        if ($graphicsCardsCategory) {
            $graphicsCardsCategory->attributes()->attach([
                $storageAttr->id, // VRAM
            ]);
        }

        // Assign to Keyboards
        if ($keyboardsCategory) {
            $keyboardsCategory->attributes()->attach([
                $colorAttr->id,
            ]);
        }

        // Assign to Mice
        if ($miceCategory) {
            $miceCategory->attributes()->attach([
                $colorAttr->id,
            ]);
        }
    }
}
