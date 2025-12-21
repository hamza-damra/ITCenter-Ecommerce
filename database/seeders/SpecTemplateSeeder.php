<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SpecTemplate;
use App\Models\SpecField;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Creates specification templates for common product categories.
     */
    public function run(): void
    {
        $templates = [
            // PC/Laptop Template
            [
                'category_slug' => 'laptops',
                'fallback_category_name' => 'Laptops',
                'name_en' => 'PC/Laptop Specifications',
                'name_ar' => 'مواصفات الحاسوب/اللابتوب',
                'fields' => [
                    ['key' => 'processor', 'label_en' => 'Processor', 'label_ar' => 'المعالج', 'type' => 'text', 'is_required' => true, 'sort_order' => 1],
                    ['key' => 'ram', 'label_en' => 'RAM', 'label_ar' => 'الذاكرة العشوائية', 'type' => 'text', 'unit' => 'GB', 'is_required' => true, 'sort_order' => 2],
                    ['key' => 'storage', 'label_en' => 'Storage', 'label_ar' => 'التخزين', 'type' => 'text', 'is_required' => true, 'sort_order' => 3],
                    ['key' => 'storage_type', 'label_en' => 'Storage Type', 'label_ar' => 'نوع التخزين', 'type' => 'select', 'options' => ['SSD', 'HDD', 'SSD + HDD'], 'sort_order' => 4],
                    ['key' => 'graphics', 'label_en' => 'Graphics Card', 'label_ar' => 'كرت الشاشة', 'type' => 'text', 'sort_order' => 5],
                    ['key' => 'screen_size', 'label_en' => 'Screen Size', 'label_ar' => 'حجم الشاشة', 'type' => 'number', 'unit' => 'inches', 'sort_order' => 6],
                    ['key' => 'screen_resolution', 'label_en' => 'Screen Resolution', 'label_ar' => 'دقة الشاشة', 'type' => 'text', 'sort_order' => 7],
                    ['key' => 'operating_system', 'label_en' => 'Operating System', 'label_ar' => 'نظام التشغيل', 'type' => 'select', 'options' => ['Windows 11', 'Windows 10', 'macOS', 'Linux', 'FreeDOS'], 'sort_order' => 8],
                    ['key' => 'battery', 'label_en' => 'Battery Life', 'label_ar' => 'عمر البطارية', 'type' => 'text', 'unit' => 'hours', 'sort_order' => 9],
                    ['key' => 'touchscreen', 'label_en' => 'Touchscreen', 'label_ar' => 'شاشة لمس', 'type' => 'boolean', 'sort_order' => 10],
                ],
            ],

            // Smartphones Template
            [
                'category_slug' => 'phones',
                'fallback_category_name' => 'Phones',
                'name_en' => 'Smartphone Specifications',
                'name_ar' => 'مواصفات الهاتف الذكي',
                'fields' => [
                    ['key' => 'screen_size', 'label_en' => 'Screen Size', 'label_ar' => 'حجم الشاشة', 'type' => 'number', 'unit' => 'inches', 'is_required' => true, 'sort_order' => 1],
                    ['key' => 'screen_resolution', 'label_en' => 'Screen Resolution', 'label_ar' => 'دقة الشاشة', 'type' => 'text', 'sort_order' => 2],
                    ['key' => 'screen_type', 'label_en' => 'Screen Type', 'label_ar' => 'نوع الشاشة', 'type' => 'select', 'options' => ['OLED', 'AMOLED', 'Super AMOLED', 'LCD', 'IPS LCD', 'Retina'], 'sort_order' => 3],
                    ['key' => 'processor', 'label_en' => 'Processor', 'label_ar' => 'المعالج', 'type' => 'text', 'is_required' => true, 'sort_order' => 4],
                    ['key' => 'ram', 'label_en' => 'RAM', 'label_ar' => 'الذاكرة العشوائية', 'type' => 'text', 'unit' => 'GB', 'is_required' => true, 'sort_order' => 5],
                    ['key' => 'storage', 'label_en' => 'Storage', 'label_ar' => 'التخزين', 'type' => 'text', 'unit' => 'GB', 'is_required' => true, 'sort_order' => 6],
                    ['key' => 'main_camera', 'label_en' => 'Main Camera', 'label_ar' => 'الكاميرا الرئيسية', 'type' => 'text', 'unit' => 'MP', 'sort_order' => 7],
                    ['key' => 'front_camera', 'label_en' => 'Front Camera', 'label_ar' => 'الكاميرا الأمامية', 'type' => 'text', 'unit' => 'MP', 'sort_order' => 8],
                    ['key' => 'battery', 'label_en' => 'Battery Capacity', 'label_ar' => 'سعة البطارية', 'type' => 'number', 'unit' => 'mAh', 'sort_order' => 9],
                    ['key' => 'operating_system', 'label_en' => 'Operating System', 'label_ar' => 'نظام التشغيل', 'type' => 'select', 'options' => ['Android 14', 'Android 13', 'iOS 17', 'iOS 16', 'HarmonyOS'], 'sort_order' => 10],
                    ['key' => 'dual_sim', 'label_en' => 'Dual SIM', 'label_ar' => 'شريحتين', 'type' => 'boolean', 'sort_order' => 11],
                    ['key' => '5g_support', 'label_en' => '5G Support', 'label_ar' => 'دعم 5G', 'type' => 'boolean', 'sort_order' => 12],
                ],
            ],

            // Monitor Template
            [
                'category_slug' => 'monitors',
                'fallback_category_name' => 'Monitors',
                'name_en' => 'Monitor Specifications',
                'name_ar' => 'مواصفات الشاشة',
                'fields' => [
                    ['key' => 'screen_size', 'label_en' => 'Screen Size', 'label_ar' => 'حجم الشاشة', 'type' => 'number', 'unit' => 'inches', 'is_required' => true, 'sort_order' => 1],
                    ['key' => 'resolution', 'label_en' => 'Resolution', 'label_ar' => 'الدقة', 'type' => 'select', 'options' => ['Full HD (1920x1080)', 'QHD (2560x1440)', '4K UHD (3840x2160)', '5K (5120x2880)', 'Ultrawide (3440x1440)'], 'is_required' => true, 'sort_order' => 2],
                    ['key' => 'panel_type', 'label_en' => 'Panel Type', 'label_ar' => 'نوع اللوحة', 'type' => 'select', 'options' => ['IPS', 'VA', 'TN', 'OLED', 'Mini LED'], 'sort_order' => 3],
                    ['key' => 'refresh_rate', 'label_en' => 'Refresh Rate', 'label_ar' => 'معدل التحديث', 'type' => 'number', 'unit' => 'Hz', 'is_required' => true, 'sort_order' => 4],
                    ['key' => 'response_time', 'label_en' => 'Response Time', 'label_ar' => 'زمن الاستجابة', 'type' => 'number', 'unit' => 'ms', 'sort_order' => 5],
                    ['key' => 'aspect_ratio', 'label_en' => 'Aspect Ratio', 'label_ar' => 'نسبة العرض', 'type' => 'select', 'options' => ['16:9', '21:9', '32:9', '16:10', '4:3'], 'sort_order' => 6],
                    ['key' => 'hdr', 'label_en' => 'HDR Support', 'label_ar' => 'دعم HDR', 'type' => 'boolean', 'sort_order' => 7],
                    ['key' => 'curved', 'label_en' => 'Curved Screen', 'label_ar' => 'شاشة منحنية', 'type' => 'boolean', 'sort_order' => 8],
                    ['key' => 'ports', 'label_en' => 'Connectivity', 'label_ar' => 'المنافذ', 'type' => 'text', 'sort_order' => 9],
                    ['key' => 'vesa_mount', 'label_en' => 'VESA Mount', 'label_ar' => 'تركيب VESA', 'type' => 'boolean', 'sort_order' => 10],
                ],
            ],

            // Accessories Template
            [
                'category_slug' => 'accessories',
                'fallback_category_name' => 'Accessories',
                'name_en' => 'Accessories Specifications',
                'name_ar' => 'مواصفات الإكسسوارات',
                'fields' => [
                    ['key' => 'compatibility', 'label_en' => 'Compatibility', 'label_ar' => 'التوافق', 'type' => 'text', 'sort_order' => 1],
                    ['key' => 'connectivity', 'label_en' => 'Connectivity', 'label_ar' => 'الاتصال', 'type' => 'select', 'options' => ['Wired', 'Wireless', 'Bluetooth', 'USB', 'USB-C', '2.4GHz'], 'sort_order' => 2],
                    ['key' => 'color', 'label_en' => 'Color', 'label_ar' => 'اللون', 'type' => 'text', 'sort_order' => 3],
                    ['key' => 'material', 'label_en' => 'Material', 'label_ar' => 'الخامة', 'type' => 'text', 'sort_order' => 4],
                    ['key' => 'battery_life', 'label_en' => 'Battery Life', 'label_ar' => 'عمر البطارية', 'type' => 'text', 'sort_order' => 5],
                    ['key' => 'waterproof', 'label_en' => 'Waterproof', 'label_ar' => 'مقاوم للماء', 'type' => 'boolean', 'sort_order' => 6],
                ],
            ],
        ];

        DB::transaction(function () use ($templates) {
            foreach ($templates as $templateData) {
                // Find category by slug or name
                $category = Category::where('slug', $templateData['category_slug'])->first();
                
                if (!$category) {
                    $category = Category::where('name_en', 'like', '%' . $templateData['fallback_category_name'] . '%')
                        ->orWhere('name_ar', 'like', '%' . $templateData['fallback_category_name'] . '%')
                        ->first();
                }
                
                if (!$category) {
                    $this->command?->warn("Category not found: {$templateData['category_slug']} / {$templateData['fallback_category_name']} - skipping template");
                    continue;
                }

                // Check if template already exists for this category
                if (SpecTemplate::where('category_id', $category->id)->exists()) {
                    $this->command?->info("Template already exists for category: {$category->name_en} - skipping");
                    continue;
                }

                // Create template
                $template = SpecTemplate::create([
                    'category_id' => $category->id,
                    'name_en' => $templateData['name_en'],
                    'name_ar' => $templateData['name_ar'],
                    'is_active' => true,
                ]);

                // Create fields
                foreach ($templateData['fields'] as $fieldData) {
                    SpecField::create([
                        'spec_template_id' => $template->id,
                        'key' => $fieldData['key'],
                        'label_en' => $fieldData['label_en'],
                        'label_ar' => $fieldData['label_ar'] ?? $fieldData['label_en'],
                        'type' => $fieldData['type'],
                        'options' => $fieldData['options'] ?? null,
                        'unit' => $fieldData['unit'] ?? null,
                        'is_required' => $fieldData['is_required'] ?? false,
                        'sort_order' => $fieldData['sort_order'],
                        'is_active' => true,
                    ]);
                }

                $this->command?->info("Created template '{$template->name_en}' with " . count($templateData['fields']) . " fields for category: {$category->name_en}");
            }
        });
    }
}


