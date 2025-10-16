<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Review;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuickSeeder extends Seeder
{
    /**
     * Run the database seeds - Optimized for remote databases.
     */
    public function run(): void
    {
        $this->command->info('Starting quick seeding...');
        
        // Disable foreign key checks for faster insertion
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Categories
        $this->command->info('Seeding categories...');
        $this->seedCategories();
        
        // Brands
        $this->command->info('Seeding brands...');
        $this->seedBrands();
        
        // Attributes
        $this->command->info('Seeding attributes...');
        $this->seedAttributes();
        
        // Products (reduced to 20 for faster seeding)
        $this->command->info('Seeding products...');
        $this->seedProducts();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->command->info('Quick seeding completed!');
    }
    
    private function seedCategories(): void
    {
        $categories = [
            ['name_en' => 'Laptops', 'name_ar' => 'أجهزة كمبيوتر محمولة', 'slug' => 'laptops', 'is_active' => true, 'order' => 1],
            ['name_en' => 'Desktops', 'name_ar' => 'أجهزة كمبيوتر مكتبية', 'slug' => 'desktops', 'is_active' => true, 'order' => 2],
            ['name_en' => 'Components', 'name_ar' => 'مكونات', 'slug' => 'components', 'is_active' => true, 'order' => 3],
            ['name_en' => 'Peripherals', 'name_ar' => 'ملحقات', 'slug' => 'peripherals', 'is_active' => true, 'order' => 4],
            ['name_en' => 'Networking', 'name_ar' => 'الشبكات', 'slug' => 'networking', 'is_active' => true, 'order' => 5],
        ];
        
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
    
    private function seedBrands(): void
    {
        $brands = [
            ['name' => 'Dell', 'slug' => 'dell', 'logo' => 'https://logo.clearbit.com/dell.com', 'is_active' => true],
            ['name' => 'HP', 'slug' => 'hp', 'logo' => 'https://logo.clearbit.com/hp.com', 'is_active' => true],
            ['name' => 'Lenovo', 'slug' => 'lenovo', 'logo' => 'https://logo.clearbit.com/lenovo.com', 'is_active' => true],
            ['name' => 'ASUS', 'slug' => 'asus', 'logo' => 'https://logo.clearbit.com/asus.com', 'is_active' => true],
            ['name' => 'Apple', 'slug' => 'apple', 'logo' => 'https://logo.clearbit.com/apple.com', 'is_active' => true],
            ['name' => 'Samsung', 'slug' => 'samsung', 'logo' => 'https://logo.clearbit.com/samsung.com', 'is_active' => true],
            ['name' => 'Microsoft', 'slug' => 'microsoft', 'logo' => 'https://logo.clearbit.com/microsoft.com', 'is_active' => true],
            ['name' => 'Acer', 'slug' => 'acer', 'logo' => 'https://logo.clearbit.com/acer.com', 'is_active' => true],
        ];
        
        foreach ($brands as $brand) {
            Brand::create($brand);
        }
    }
    
    private function seedAttributes(): void
    {
        $attributes = [
            ['name_en' => 'RAM', 'name_ar' => 'الذاكرة العشوائية', 'slug' => 'ram', 'type' => 'select'],
            ['name_en' => 'Storage', 'name_ar' => 'التخزين', 'slug' => 'storage', 'type' => 'select'],
            ['name_en' => 'Processor', 'name_ar' => 'المعالج', 'slug' => 'processor', 'type' => 'select'],
            ['name_en' => 'Color', 'name_ar' => 'اللون', 'slug' => 'color', 'type' => 'select'],
        ];
        
        foreach ($attributes as $attrData) {
            $attribute = Attribute::create($attrData);
            
            // Add attribute values
            if ($attribute->slug === 'ram') {
                AttributeValue::create(['attribute_id' => $attribute->id, 'value_en' => '8GB', 'value_ar' => '8 جيجابايت']);
                AttributeValue::create(['attribute_id' => $attribute->id, 'value_en' => '16GB', 'value_ar' => '16 جيجابايت']);
                AttributeValue::create(['attribute_id' => $attribute->id, 'value_en' => '32GB', 'value_ar' => '32 جيجابايت']);
            } elseif ($attribute->slug === 'storage') {
                AttributeValue::create(['attribute_id' => $attribute->id, 'value_en' => '256GB SSD', 'value_ar' => '256 جيجابايت SSD']);
                AttributeValue::create(['attribute_id' => $attribute->id, 'value_en' => '512GB SSD', 'value_ar' => '512 جيجابايت SSD']);
                AttributeValue::create(['attribute_id' => $attribute->id, 'value_en' => '1TB SSD', 'value_ar' => '1 تيرابايت SSD']);
            } elseif ($attribute->slug === 'color') {
                AttributeValue::create(['attribute_id' => $attribute->id, 'value_en' => 'Black', 'value_ar' => 'أسود']);
                AttributeValue::create(['attribute_id' => $attribute->id, 'value_en' => 'Silver', 'value_ar' => 'فضي']);
                AttributeValue::create(['attribute_id' => $attribute->id, 'value_en' => 'Gray', 'value_ar' => 'رمادي']);
            }
        }
    }
    
    private function seedProducts(): void
    {
        $categories = Category::all();
        $brands = Brand::all();
        
        // Create only 20 products for faster seeding
        for ($i = 1; $i <= 20; $i++) {
            $product = Product::create([
                'name_en' => "Product $i",
                'name_ar' => "منتج $i",
                'slug' => "product-$i",
                'description_en' => "Description for product $i",
                'description_ar' => "وصف المنتج $i",
                'price' => rand(500, 5000),
                'compare_price' => rand(5001, 6000),
                'cost' => rand(300, 800),
                'sku' => 'SKU' . str_pad($i, 5, '0', STR_PAD_LEFT),
                'quantity' => rand(10, 100),
                'is_active' => true,
                'is_featured' => rand(0, 1),
                'category_id' => $categories->random()->id,
                'brand_id' => $brands->random()->id,
            ]);
            
            // Create one main image
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'https://via.placeholder.com/640x480?text=Product+' . $i,
                'order' => 0,
                'is_primary' => true,
                'alt_text' => $product->name_en,
            ]);
        }
    }
}
