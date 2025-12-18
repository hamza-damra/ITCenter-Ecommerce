<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoryTagTestSeeder extends Seeder
{
    /**
     * Seed 10 products with 2 categories (with all category types) to test category and tag system.
     */
    public function run(): void
    {
        // Ensure we have brands
        $brand = Brand::first();
        if (!$brand) {
            $brand = Brand::create([
                'name_en' => 'Test Brand',
                'name_ar' => 'علامة تجريبية',
                'name_he' => 'מותג בדיקה',
                'slug' => 'test-brand',
                'is_active' => true,
            ]);
        }

        // Create 2 parent categories with different display modes
        $parentCategory1 = Category::firstOrCreate(
            ['slug' => 'test-electronics'],
            [
                'name_en' => 'Test Electronics',
                'name_ar' => 'إلكترونيات تجريبية',
                'name_he' => 'אלקטרוניקה לבדיקה',
                'description_en' => 'Test parent category with carousel display',
                'description_ar' => 'فئة أب تجريبية مع عرض دائري',
                'description_he' => 'קטגוריית אב לבדיקה עם תצוגת קרוסלה',
                'icon' => '🔌',
                'position' => 100,
                'is_active' => true,
                'display_mode' => 'carousel',
                'order' => 100,
            ]
        );

        $parentCategory2 = Category::firstOrCreate(
            ['slug' => 'test-accessories'],
            [
                'name_en' => 'Test Accessories',
                'name_ar' => 'إكسسوارات تجريبية',
                'name_he' => 'אביזרים לבדיקה',
                'description_en' => 'Test parent category with nav display',
                'description_ar' => 'فئة أب تجريبية مع عرض التنقل',
                'description_he' => 'קטגוריית אב לבדיקה עם תצוגת ניווט',
                'icon' => '🎧',
                'position' => 101,
                'is_active' => true,
                'display_mode' => 'nav',
                'order' => 101,
            ]
        );

        // Create child categories for parent 1 (carousel)
        $childCategory1a = Category::firstOrCreate(
            ['slug' => 'test-phones'],
            [
                'name_en' => 'Test Phones',
                'name_ar' => 'هواتف تجريبية',
                'name_he' => 'טלפונים לבדיקה',
                'description_en' => 'Test child category - phones',
                'description_ar' => 'فئة فرعية تجريبية - هواتف',
                'description_he' => 'קטגוריית משנה לבדיקה - טלפונים',
                'parent_id' => $parentCategory1->id,
                'position' => 1,
                'is_active' => true,
                'display_mode' => 'carousel',
            ]
        );

        $childCategory1b = Category::firstOrCreate(
            ['slug' => 'test-tablets'],
            [
                'name_en' => 'Test Tablets',
                'name_ar' => 'أجهزة لوحية تجريبية',
                'name_he' => 'טאבלטים לבדיקה',
                'description_en' => 'Test child category - tablets',
                'description_ar' => 'فئة فرعية تجريبية - أجهزة لوحية',
                'description_he' => 'קטגוריית משנה לבדיקה - טאבלטים',
                'parent_id' => $parentCategory1->id,
                'position' => 2,
                'is_active' => true,
                'display_mode' => 'nav',
            ]
        );

        // Create child categories for parent 2 (nav)
        $childCategory2a = Category::firstOrCreate(
            ['slug' => 'test-headphones'],
            [
                'name_en' => 'Test Headphones',
                'name_ar' => 'سماعات تجريبية',
                'name_he' => 'אוזניות לבדיקה',
                'description_en' => 'Test child category - headphones',
                'description_ar' => 'فئة فرعية تجريبية - سماعات',
                'description_he' => 'קטגוריית משנה לבדיקה - אוזניות',
                'parent_id' => $parentCategory2->id,
                'position' => 1,
                'is_active' => true,
                'display_mode' => 'carousel',
            ]
        );

        $childCategory2b = Category::firstOrCreate(
            ['slug' => 'test-cases'],
            [
                'name_en' => 'Test Cases',
                'name_ar' => 'حافظات تجريبية',
                'name_he' => 'כיסויים לבדיקה',
                'description_en' => 'Test child category - cases',
                'description_ar' => 'فئة فرعية تجريبية - حافظات',
                'description_he' => 'קטגוריית משנה לבדיקה - כיסויים',
                'parent_id' => $parentCategory2->id,
                'position' => 2,
                'is_active' => true,
                'display_mode' => 'nav',
            ]
        );

        // Ensure we have tags
        $tags = Tag::active()->get();
        if ($tags->isEmpty()) {
            $tags = collect([
                Tag::create(['name_en' => 'Gaming', 'name_ar' => 'ألعاب', 'name_he' => 'גיימינג', 'slug' => 'gaming-test', 'color' => '#ef4444', 'icon' => 'fas fa-gamepad', 'position' => 1, 'is_active' => true]),
                Tag::create(['name_en' => 'Premium', 'name_ar' => 'فاخر', 'name_he' => 'פרימיום', 'slug' => 'premium-test', 'color' => '#ec4899', 'icon' => 'fas fa-crown', 'position' => 2, 'is_active' => true]),
                Tag::create(['name_en' => 'Budget', 'name_ar' => 'اقتصادي', 'name_he' => 'תקציבי', 'slug' => 'budget-test', 'color' => '#f59e0b', 'icon' => 'fas fa-dollar-sign', 'position' => 3, 'is_active' => true]),
            ]);
        }

        // All categories to distribute products
        $allCategories = collect([
            $parentCategory1,
            $parentCategory2,
            $childCategory1a,
            $childCategory1b,
            $childCategory2a,
            $childCategory2b,
        ]);

        // Create 10 test products - distributed across all categories
        $products = [
            // Test Phones (child of Test Electronics - carousel)
            ['name' => 'Test Smartphone Pro', 'category' => $childCategory1a, 'price' => 999.99, 'is_featured' => true, 'is_new' => true],
            ['name' => 'Test Smartphone Lite', 'category' => $childCategory1a, 'price' => 499.99, 'is_bestseller' => true],
            ['name' => 'Test Smartphone Ultra', 'category' => $childCategory1a, 'price' => 1299.99, 'is_featured' => true, 'is_strong_offer' => true],
            // Test Tablets (child of Test Electronics - nav)
            ['name' => 'Test Tablet Max', 'category' => $childCategory1b, 'price' => 799.99, 'is_featured' => true, 'is_strong_offer' => true],
            ['name' => 'Test Tablet Mini', 'category' => $childCategory1b, 'price' => 399.99, 'is_new' => true],
            // Test Headphones (child of Test Accessories - carousel)
            ['name' => 'Test Wireless Headphones', 'category' => $childCategory2a, 'price' => 299.99, 'is_featured' => true, 'is_bestseller' => true],
            ['name' => 'Test Gaming Headset', 'category' => $childCategory2a, 'price' => 149.99, 'is_strong_offer' => true],
            // Test Cases (child of Test Accessories - nav)
            ['name' => 'Test Phone Case Premium', 'category' => $childCategory2b, 'price' => 49.99, 'is_new' => true],
            ['name' => 'Test Phone Case Basic', 'category' => $childCategory2b, 'price' => 19.99, 'is_bestseller' => true],
            ['name' => 'Test Phone Case Rugged', 'category' => $childCategory2b, 'price' => 39.99, 'is_featured' => true],
        ];

        foreach ($products as $index => $productData) {
            $slug = Str::slug($productData['name']);
            $product = Product::firstOrCreate(
                ['slug' => $slug],
                [
                    'name_en' => $productData['name'],
                    'name_ar' => 'منتج تجريبي ' . ($index + 1),
                    'name_he' => 'מוצר בדיקה ' . ($index + 1),
                    'sku' => 'TEST-' . strtoupper(Str::slug($productData['name'], '-')),
                    'short_description_en' => 'Short description for ' . $productData['name'],
                    'short_description_ar' => 'وصف قصير للمنتج التجريبي',
                    'short_description_he' => 'תיאור קצר למוצר הבדיקה',
                    'description_en' => 'Full description for ' . $productData['name'] . '. This is a test product to verify category and tag system functionality.',
                    'description_ar' => 'وصف كامل للمنتج التجريبي. هذا منتج تجريبي للتحقق من وظائف نظام الفئات والعلامات.',
                    'description_he' => 'תיאור מלא למוצר הבדיקה. זהו מוצר בדיקה לאימות פונקציונליות מערכת הקטגוריות והתגיות.',
                    'price' => $productData['price'],
                    'sale_price' => isset($productData['is_strong_offer']) ? $productData['price'] * 0.8 : null,
                    'stock_quantity' => rand(10, 100),
                    'category_id' => $productData['category']->id,
                    'brand_id' => $brand->id,
                    'is_active' => true,
                    'is_featured' => $productData['is_featured'] ?? false,
                    'is_new' => $productData['is_new'] ?? false,
                    'is_bestseller' => $productData['is_bestseller'] ?? false,
                    'is_special_offer' => $productData['is_special_offer'] ?? false,
                    'is_strong_offer' => $productData['is_strong_offer'] ?? false,
                    'track_stock' => true,
                    'stock_status' => 'in_stock',
                ]
            );

            // Attach random tags (1-3 tags per product) if not already attached
            if ($product->tags()->count() === 0) {
                $randomTags = $tags->random(rand(1, min(3, $tags->count())));
                $product->tags()->attach($randomTags->pluck('id'));
                $this->command->info("Created product: {$productData['name']} in category: {$productData['category']->name_en} with " . $randomTags->count() . " tags");
            } else {
                $this->command->info("Product already exists: {$productData['name']} in category: {$productData['category']->name_en}");
            }
        }

        $this->command->info('');
        $this->command->info('=== Category & Tag Test Data Summary ===');
        $this->command->info("Created 2 parent categories:");
        $this->command->info("  - {$parentCategory1->name_en} (display_mode: carousel)");
        $this->command->info("  - {$parentCategory2->name_en} (display_mode: nav)");
        $this->command->info("Created 4 child categories:");
        $this->command->info("  - {$childCategory1a->name_en} under {$parentCategory1->name_en}");
        $this->command->info("  - {$childCategory1b->name_en} under {$parentCategory1->name_en}");
        $this->command->info("  - {$childCategory2a->name_en} under {$parentCategory2->name_en}");
        $this->command->info("  - {$childCategory2b->name_en} under {$parentCategory2->name_en}");
        $this->command->info("Created 10 products with various flags (featured, new, bestseller, strong_offer)");
        $this->command->info("Each product has 1-3 random tags attached");
    }
}
