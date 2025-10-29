<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class ProductCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeds the 8 main product categories for the "Explore our products" homepage section.
     */
    public function run(): void
    {
        $this->command->info('🎯 Seeding product categories for homepage...');

        // Disable foreign key checks for faster insertion
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $categories = [
            [
                'name_en' => 'Mice',
                'name_ar' => 'الفئران',
                'name_he' => 'עכברים',
                'slug' => 'mice',
                'description_en' => 'Gaming and professional mice for all your needs',
                'description_ar' => 'فئران الألعاب والاحترافية لجميع احتياجاتك',
                'description_he' => 'עכברי גיימינג ומקצועיים לכל הצרכים שלך',
                'image' => 'https://redragonshop.com/cdn/shop/files/mice_7926ea20-393d-4542-bc9a-253b1fc0292f.png?v=1700132202&width=708',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'name_en' => 'Keyboards',
                'name_ar' => 'لوحات المفاتيح',
                'name_he' => 'מקלדות',
                'slug' => 'keyboards',
                'description_en' => 'Mechanical and membrane keyboards for gaming and productivity',
                'description_ar' => 'لوحات مفاتيح ميكانيكية وغشائية للألعاب والإنتاجية',
                'description_he' => 'מקלדות מכניות וממברנה למשחקים ופרודוקטיביות',
                'image' => 'https://redragonshop.com/cdn/shop/files/keyboard3.png?v=1700192620&width=708',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'name_en' => 'Headsets',
                'name_ar' => 'سماعات الرأس',
                'name_he' => 'אוזניות',
                'slug' => 'headsets',
                'description_en' => 'Premium gaming and professional headsets with superior sound quality',
                'description_ar' => 'سماعات رأس احترافية وللألعاب بجودة صوت فائقة',
                'description_he' => 'אוזניות גיימינג ומקצועיות עם איכות צליל מעולה',
                'image' => 'https://redragonshop.com/cdn/shop/files/headsets2.png?v=1700188543&width=708',
                'is_active' => true,
                'order' => 3,
            ],
            [
                'name_en' => 'Speakers',
                'name_ar' => 'مكبرات الصوت',
                'name_he' => 'רמקולים',
                'slug' => 'speakers',
                'description_en' => 'High-quality speakers for immersive audio experience',
                'description_ar' => 'مكبرات صوت عالية الجودة لتجربة صوتية غامرة',
                'description_he' => 'רמקולים באיכות גבוהה לחוויית אודיו סוחפת',
                'image' => 'https://redragonshop.com/cdn/shop/files/speaker.png?v=1700132202&width=708',
                'is_active' => true,
                'order' => 4,
            ],
            [
                'name_en' => 'Accessories',
                'name_ar' => 'الإكسسوارات',
                'name_he' => 'אביזרים',
                'slug' => 'accessories',
                'description_en' => 'Essential gaming and computer accessories',
                'description_ar' => 'إكسسوارات الألعاب والكمبيوتر الأساسية',
                'description_he' => 'אביזרי גיימינג ומחשב חיוניים',
                'image' => 'https://redragonshop.com/cdn/shop/files/acc.webp?v=1711626105&width=708',
                'is_active' => true,
                'order' => 5,
            ],
            [
                'name_en' => 'Combos',
                'name_ar' => 'المجموعات',
                'name_he' => 'חבילות',
                'slug' => 'combos',
                'description_en' => 'Complete gaming setups and combo packages',
                'description_ar' => 'إعدادات الألعاب الكاملة وحزم المجموعات',
                'description_he' => 'סטאפים מלאים למשחקים וחבילות קומבו',
                'image' => 'https://redragonshop.com/cdn/shop/files/combos.png?v=1700132202&width=708',
                'is_active' => true,
                'order' => 6,
            ],
            [
                'name_en' => 'Switches',
                'name_ar' => 'المفاتيح',
                'name_he' => 'מתגים',
                'slug' => 'switches',
                'description_en' => 'Mechanical keyboard switches for customization',
                'description_ar' => 'مفاتيح لوحة المفاتيح الميكانيكية للتخصيص',
                'description_he' => 'מתגי מקלדת מכניים להתאמה אישית',
                'image' => 'https://redragonshop.com/cdn/shop/files/switch.webp?v=1711626122&width=708',
                'is_active' => true,
                'order' => 7,
            ],
            [
                'name_en' => 'New Arrivals',
                'name_ar' => 'الوافدون الجدد',
                'name_he' => 'הגעות חדשות',
                'slug' => 'new-arrivals',
                'description_en' => 'Latest products and newest additions to our catalog',
                'description_ar' => 'أحدث المنتجات والإضافات الجديدة إلى كتالوجنا',
                'description_he' => 'המוצרים האחרונים והתוספות החדשות לקטלוג שלנו',
                'image' => 'https://redragonshop.com/cdn/shop/files/arrivals.png?v=1700132202&width=708',
                'is_active' => true,
                'order' => 8,
            ],
        ];

        foreach ($categories as $categoryData) {
            // Check if category already exists by slug
            $existingCategory = Category::where('slug', $categoryData['slug'])->first();
            
            if ($existingCategory) {
                // Update existing category
                $existingCategory->update($categoryData);
                $this->command->info("✅ Updated category: {$categoryData['name_en']}");
            } else {
                // Create new category
                Category::create($categoryData);
                $this->command->info("✅ Created category: {$categoryData['name_en']}");
            }
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('🎉 Product categories seeding completed!');
    }
}

