<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Pre-populate home_sections with the 9 existing hardcoded sections
     * in their current display order so the home page looks identical after migration.
     */
    public function up(): void
    {
        $sections = [
            [
                'type' => 'hero_banner',
                'title_en' => 'Hero Banner Slider',
                'title_ar' => 'سلايدر البانر الرئيسي',
                'title_he' => 'סליידר באנר ראשי',
                'display_order' => 0,
                'is_active' => true,
                'settings' => json_encode(['auto_scroll_interval' => 5000]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'category_carousel',
                'title_en' => 'Category Carousel',
                'title_ar' => 'عرض الفئات',
                'title_he' => 'קרוסלת קטגוריות',
                'display_order' => 1,
                'is_active' => true,
                'settings' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'featured_products',
                'title_en' => 'Featured Products',
                'title_ar' => 'المنتجات المميزة',
                'title_he' => 'מוצרים מומלצים',
                'display_order' => 2,
                'is_active' => true,
                'settings' => json_encode(['max_products' => 8]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'special_offers_banner',
                'title_en' => 'Special Offers Banner',
                'title_ar' => 'بانر العروض الخاصة',
                'title_he' => 'באנר מבצעים מיוחדים',
                'display_order' => 3,
                'is_active' => true,
                'settings' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'special_discounts',
                'title_en' => 'Special Discounts',
                'title_ar' => 'الخصومات الخاصة',
                'title_he' => 'הנחות מיוחדות',
                'display_order' => 4,
                'is_active' => true,
                'settings' => json_encode(['auto_scroll_interval' => 4500, 'cards_to_scroll' => 1]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'new_arrivals',
                'title_en' => 'New Arrivals',
                'title_ar' => 'وصل حديثاً',
                'title_he' => 'מוצרים חדשים',
                'display_order' => 5,
                'is_active' => true,
                'settings' => json_encode(['auto_scroll_interval' => 5000, 'cards_to_scroll' => 2]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'bestsellers',
                'title_en' => 'Bestsellers',
                'title_ar' => 'الأكثر مبيعاً',
                'title_he' => 'רבי מכר',
                'display_order' => 6,
                'is_active' => true,
                'settings' => json_encode(['auto_scroll_interval' => 6000]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'gift_ideas_banner',
                'title_en' => 'Gift Ideas',
                'title_ar' => 'أفكار هدايا',
                'title_he' => 'רעיונות למתנות',
                'display_order' => 7,
                'is_active' => true,
                'settings' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'type' => 'on_sale',
                'title_en' => 'On Sale',
                'title_ar' => 'تخفيضات',
                'title_he' => 'מבצעים',
                'display_order' => 8,
                'is_active' => true,
                'settings' => json_encode(['auto_scroll_interval' => 5000, 'cards_to_scroll' => 1]),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('home_sections')->insert($sections);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('home_sections')->whereIn('type', [
            'hero_banner', 'category_carousel', 'featured_products',
            'special_offers_banner', 'special_discounts', 'new_arrivals',
            'bestsellers', 'gift_ideas_banner', 'on_sale',
        ])->delete();
    }
};
