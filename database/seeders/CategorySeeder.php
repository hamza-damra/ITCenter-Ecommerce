<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name_en' => 'Laptops',
                'name_ar' => 'أجهزة كمبيوتر محمولة',
                'slug' => 'laptops',
                'description_en' => 'High-performance laptops for work and gaming',
                'description_ar' => 'أجهزة كمبيوتر محمولة عالية الأداء للعمل والألعاب',
                'image' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=640&h=480&fit=crop',
                'is_active' => true,
                'order' => 1,
                'subcategories' => [
                    [
                        'name_en' => 'Gaming Laptops',
                        'name_ar' => 'لابتوبات الألعاب',
                        'slug' => 'gaming-laptops'
                    ],
                    [
                        'name_en' => 'Business Laptops',
                        'name_ar' => 'لابتوبات الأعمال',
                        'slug' => 'business-laptops'
                    ],
                    [
                        'name_en' => 'Ultrabooks',
                        'name_ar' => 'ألترا بوك',
                        'slug' => 'ultrabooks'
                    ],
                ]
            ],
            [
                'name_en' => 'Desktops',
                'name_ar' => 'أجهزة كمبيوتر مكتبية',
                'slug' => 'desktops',
                'description_en' => 'Powerful desktop computers',
                'description_ar' => 'أجهزة كمبيوتر مكتبية قوية',
                'image' => 'https://images.unsplash.com/photo-1587831990711-23ca6441447b?w=640&h=480&fit=crop',
                'is_active' => true,
                'order' => 2,
                'subcategories' => [
                    [
                        'name_en' => 'Gaming Desktops',
                        'name_ar' => 'أجهزة مكتبية للألعاب',
                        'slug' => 'gaming-desktops'
                    ],
                    [
                        'name_en' => 'Workstations',
                        'name_ar' => 'محطات العمل',
                        'slug' => 'workstations'
                    ],
                    [
                        'name_en' => 'All-in-One PCs',
                        'name_ar' => 'أجهزة الكل في واحد',
                        'slug' => 'all-in-one-pcs'
                    ],
                ]
            ],
            [
                'name_en' => 'Components',
                'name_ar' => 'المكونات',
                'slug' => 'components',
                'description_en' => 'Computer components and parts',
                'description_ar' => 'مكونات وقطع الكمبيوتر',
                'image' => 'https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=640&h=480&fit=crop',
                'is_active' => true,
                'order' => 3,
                'subcategories' => [
                    [
                        'name_en' => 'Processors',
                        'name_ar' => 'المعالجات',
                        'slug' => 'processors'
                    ],
                    [
                        'name_en' => 'Graphics Cards',
                        'name_ar' => 'كروت الشاشة',
                        'slug' => 'graphics-cards'
                    ],
                    [
                        'name_en' => 'Motherboards',
                        'name_ar' => 'اللوحات الأم',
                        'slug' => 'motherboards'
                    ],
                    [
                        'name_en' => 'RAM',
                        'name_ar' => 'الذاكرة العشوائية',
                        'slug' => 'ram'
                    ],
                    [
                        'name_en' => 'Storage',
                        'name_ar' => 'وحدات التخزين',
                        'slug' => 'storage'
                    ],
                    [
                        'name_en' => 'Power Supplies',
                        'name_ar' => 'مزودات الطاقة',
                        'slug' => 'power-supplies'
                    ],
                ]
            ],
            [
                'name_en' => 'Peripherals',
                'name_ar' => 'الملحقات',
                'slug' => 'peripherals',
                'description_en' => 'Computer peripherals and accessories',
                'description_ar' => 'ملحقات وإكسسوارات الكمبيوتر',
                'image' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=640&h=480&fit=crop',
                'is_active' => true,
                'order' => 4,
                'subcategories' => [
                    [
                        'name_en' => 'Keyboards',
                        'name_ar' => 'لوحات المفاتيح',
                        'slug' => 'keyboards'
                    ],
                    [
                        'name_en' => 'Mice',
                        'name_ar' => 'الفأرة',
                        'slug' => 'mice'
                    ],
                    [
                        'name_en' => 'Monitors',
                        'name_ar' => 'الشاشات',
                        'slug' => 'monitors'
                    ],
                    [
                        'name_en' => 'Headsets',
                        'name_ar' => 'سماعات الرأس',
                        'slug' => 'headsets'
                    ],
                    [
                        'name_en' => 'Webcams',
                        'name_ar' => 'كاميرات الويب',
                        'slug' => 'webcams'
                    ],
                ]
            ],
            [
                'name_en' => 'Networking',
                'name_ar' => 'الشبكات',
                'slug' => 'networking',
                'description_en' => 'Networking equipment',
                'description_ar' => 'معدات الشبكات',
                'image' => 'https://images.unsplash.com/photo-1606904825846-647eb07f5be2?w=640&h=480&fit=crop',
                'is_active' => true,
                'order' => 5,
                'subcategories' => [
                    [
                        'name_en' => 'Routers',
                        'name_ar' => 'أجهزة التوجيه',
                        'slug' => 'routers'
                    ],
                    [
                        'name_en' => 'Switches',
                        'name_ar' => 'المحولات',
                        'slug' => 'switches'
                    ],
                    [
                        'name_en' => 'Access Points',
                        'name_ar' => 'نقاط الوصول',
                        'slug' => 'access-points'
                    ],
                ]
            ],
            [
                'name_en' => 'Software',
                'name_ar' => 'البرمجيات',
                'slug' => 'software',
                'description_en' => 'Software and licenses',
                'description_ar' => 'البرمجيات والتراخيص',
                'image' => 'https://images.unsplash.com/photo-1555421689-d68471e189f2?w=640&h=480&fit=crop',
                'is_active' => true,
                'order' => 6,
                'subcategories' => [
                    [
                        'name_en' => 'Operating Systems',
                        'name_ar' => 'أنظمة التشغيل',
                        'slug' => 'operating-systems'
                    ],
                    [
                        'name_en' => 'Office Software',
                        'name_ar' => 'برامج المكتب',
                        'slug' => 'office-software'
                    ],
                    [
                        'name_en' => 'Security Software',
                        'name_ar' => 'برامج الحماية',
                        'slug' => 'security-software'
                    ],
                ]
            ],
        ];

        foreach ($categories as $categoryData) {
            $subcategories = $categoryData['subcategories'] ?? [];
            unset($categoryData['subcategories']);

            $category = Category::create($categoryData);

            foreach ($subcategories as $subcategoryData) {
                $subcategoryData['parent_id'] = $category->id;
                $subcategoryData['description_en'] = $subcategoryData['name_en'] . ' category';
                $subcategoryData['description_ar'] = 'فئة ' . $subcategoryData['name_ar'];
                $subcategoryData['is_active'] = true;
                Category::create($subcategoryData);
            }
        }
    }
}
