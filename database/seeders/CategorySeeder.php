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
                'name_he' => 'מחשבים ניידים',
                'slug' => 'laptops',
                'description_en' => 'High-performance laptops for work and gaming',
                'description_ar' => 'أجهزة كمبيوتر محمولة عالية الأداء للعمل والألعاب',
                'description_he' => 'מחשבים ניידים בעלי ביצועים גבוהים לעבודה ומשחקים',
                'image' => 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=640&h=480&fit=crop',
                'icon' => '💻',
                'position' => 1,
                'is_active' => true,
                'order' => 1,
                'subcategories' => [
                    [
                        'name_en' => 'Gaming Laptops',
                        'name_ar' => 'لابتوبات الألعاب',
                        'name_he' => 'מחשבים ניידים למשחקים',
                        'slug' => 'gaming-laptops',
                        'position' => 1,
                    ],
                    [
                        'name_en' => 'Business Laptops',
                        'name_ar' => 'لابتوبات الأعمال',
                        'name_he' => 'מחשבים ניידים לעסקים',
                        'slug' => 'business-laptops',
                        'position' => 2,
                    ],
                    [
                        'name_en' => 'Ultrabooks',
                        'name_ar' => 'ألترا بوك',
                        'name_he' => 'אולטרה-בוקים',
                        'slug' => 'ultrabooks',
                        'position' => 3,
                    ],
                ]
            ],
            [
                'name_en' => 'Desktops',
                'name_ar' => 'أجهزة كمبيوتر مكتبية',
                'name_he' => 'מחשבים שולחניים',
                'slug' => 'desktops',
                'description_en' => 'Powerful desktop computers',
                'description_ar' => 'أجهزة كمبيوتر مكتبية قوية',
                'description_he' => 'מחשבים שולחניים עוצמתיים',
                'image' => 'https://images.unsplash.com/photo-1587831990711-23ca6441447b?w=640&h=480&fit=crop',
                'icon' => '🖥️',
                'position' => 2,
                'is_active' => true,
                'order' => 2,
                'subcategories' => [
                    [
                        'name_en' => 'Gaming Desktops',
                        'name_ar' => 'أجهزة مكتبية للألعاب',
                        'name_he' => 'מחשבים שולחניים למשחקים',
                        'slug' => 'gaming-desktops',
                        'position' => 1,
                    ],
                    [
                        'name_en' => 'Workstations',
                        'name_ar' => 'محطات العمل',
                        'name_he' => 'תחנות עבודה',
                        'slug' => 'workstations',
                        'position' => 2,
                    ],
                    [
                        'name_en' => 'All-in-One PCs',
                        'name_ar' => 'أجهزة الكل في واحد',
                        'name_he' => 'מחשבים הכל-באחד',
                        'slug' => 'all-in-one-pcs',
                        'position' => 3,
                    ],
                ]
            ],
            [
                'name_en' => 'Components',
                'name_ar' => 'المكونات',
                'name_he' => 'רכיבים',
                'slug' => 'components',
                'description_en' => 'Computer components and parts',
                'description_ar' => 'مكونات وقطع الكمبيوتر',
                'description_he' => 'רכיבי מחשב וחלקים',
                'image' => 'https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=640&h=480&fit=crop',
                'icon' => '⚙️',
                'position' => 3,
                'is_active' => true,
                'order' => 3,
                'subcategories' => [
                    [
                        'name_en' => 'Processors',
                        'name_ar' => 'المعالجات',
                        'name_he' => 'מעבדים',
                        'slug' => 'processors',
                        'position' => 1,
                    ],
                    [
                        'name_en' => 'Graphics Cards',
                        'name_ar' => 'كروت الشاشة',
                        'name_he' => 'כרטיסי מסך',
                        'slug' => 'graphics-cards',
                        'position' => 2,
                    ],
                    [
                        'name_en' => 'Motherboards',
                        'name_ar' => 'اللوحات الأم',
                        'name_he' => 'לוחות אם',
                        'slug' => 'motherboards',
                        'position' => 3,
                    ],
                    [
                        'name_en' => 'RAM',
                        'name_ar' => 'الذاكرة العشوائية',
                        'name_he' => 'זיכרון RAM',
                        'slug' => 'ram',
                        'position' => 4,
                    ],
                    [
                        'name_en' => 'Storage',
                        'name_ar' => 'وحدات التخزين',
                        'name_he' => 'אחסון',
                        'slug' => 'storage',
                        'position' => 5,
                    ],
                    [
                        'name_en' => 'Power Supplies',
                        'name_ar' => 'مزودات الطاقة',
                        'name_he' => 'ספקי כוח',
                        'slug' => 'power-supplies',
                        'position' => 6,
                    ],
                ]
            ],
            [
                'name_en' => 'Peripherals',
                'name_ar' => 'الملحقات',
                'name_he' => 'ציוד היקפי',
                'slug' => 'peripherals',
                'description_en' => 'Computer peripherals and accessories',
                'description_ar' => 'ملحقات وإكسسوارات الكمبيوتر',
                'description_he' => 'ציוד היקפי ואביזרים למחשב',
                'image' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=640&h=480&fit=crop',
                'icon' => '🖱️',
                'position' => 4,
                'is_active' => true,
                'order' => 4,
                'subcategories' => [
                    [
                        'name_en' => 'Keyboards',
                        'name_ar' => 'لوحات المفاتيح',
                        'name_he' => 'מקלדות',
                        'slug' => 'keyboards',
                        'position' => 1,
                    ],
                    [
                        'name_en' => 'Mice',
                        'name_ar' => 'الفأرة',
                        'name_he' => 'עכברים',
                        'slug' => 'mice',
                        'position' => 2,
                    ],
                    [
                        'name_en' => 'Monitors',
                        'name_ar' => 'الشاشات',
                        'name_he' => 'מסכים',
                        'slug' => 'monitors',
                        'position' => 3,
                    ],
                    [
                        'name_en' => 'Headsets',
                        'name_ar' => 'سماعات الرأس',
                        'name_he' => 'אוזניות',
                        'slug' => 'headsets',
                        'position' => 4,
                    ],
                    [
                        'name_en' => 'Webcams',
                        'name_ar' => 'كاميرات الويب',
                        'name_he' => 'מצלמות אינטרנט',
                        'slug' => 'webcams',
                        'position' => 5,
                    ],
                ]
            ],
            [
                'name_en' => 'Networking',
                'name_ar' => 'الشبكات',
                'name_he' => 'רשתות',
                'slug' => 'networking',
                'description_en' => 'Networking equipment',
                'description_ar' => 'معدات الشبكات',
                'description_he' => 'ציוד רשתות',
                'image' => 'https://images.unsplash.com/photo-1606904825846-647eb07f5be2?w=640&h=480&fit=crop',
                'icon' => '🌐',
                'position' => 5,
                'is_active' => true,
                'order' => 5,
                'subcategories' => [
                    [
                        'name_en' => 'Routers',
                        'name_ar' => 'أجهزة التوجيه',
                        'name_he' => 'נתבים',
                        'slug' => 'routers',
                        'position' => 1,
                    ],
                    [
                        'name_en' => 'Switches',
                        'name_ar' => 'المحولات',
                        'name_he' => 'מתגים',
                        'slug' => 'switches',
                        'position' => 2,
                    ],
                    [
                        'name_en' => 'Access Points',
                        'name_ar' => 'نقاط الوصول',
                        'name_he' => 'נקודות גישה',
                        'slug' => 'access-points',
                        'position' => 3,
                    ],
                ]
            ],
            [
                'name_en' => 'Software',
                'name_ar' => 'البرمجيات',
                'name_he' => 'תוכנה',
                'slug' => 'software',
                'description_en' => 'Software and licenses',
                'description_ar' => 'البرمجيات والتراخيص',
                'description_he' => 'תוכנה ורישיונות',
                'image' => 'https://images.unsplash.com/photo-1555421689-d68471e189f2?w=640&h=480&fit=crop',
                'icon' => '📀',
                'position' => 6,
                'is_active' => true,
                'order' => 6,
                'subcategories' => [
                    [
                        'name_en' => 'Operating Systems',
                        'name_ar' => 'أنظمة التشغيل',
                        'name_he' => 'מערכות הפעלה',
                        'slug' => 'operating-systems',
                        'position' => 1,
                    ],
                    [
                        'name_en' => 'Office Software',
                        'name_ar' => 'برامج المكتب',
                        'name_he' => 'תוכנות משרד',
                        'slug' => 'office-software',
                        'position' => 2,
                    ],
                    [
                        'name_en' => 'Security Software',
                        'name_ar' => 'برامج الحماية',
                        'name_he' => 'תוכנות אבטחה',
                        'slug' => 'security-software',
                        'position' => 3,
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
                $subcategoryData['description_he'] = 'קטגוריית ' . $subcategoryData['name_he'];
                $subcategoryData['is_active'] = true;
                Category::create($subcategoryData);
            }
        }
    }
}
