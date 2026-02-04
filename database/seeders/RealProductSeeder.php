<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Database\Seeders\ProductData\LaptopData;
use Database\Seeders\ProductData\ComponentData;
use Database\Seeders\ProductData\PeripheralData;
use Database\Seeders\ProductData\NetworkingData;

class RealProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = array_merge(
            LaptopData::getData(),
            ComponentData::getData(),
            PeripheralData::getData(),
            NetworkingData::getData()
        );

        $created = 0;

        foreach ($products as $data) {
            $category = Category::where('slug', $data['category_slug'])->first();
            $brand = Brand::where('slug', $data['brand_slug'])->first();

            if (!$category) {
                $this->command->warn("Category not found: {$data['category_slug']}");
                continue;
            }

            if (!$brand) {
                $this->command->warn("Brand not found: {$data['brand_slug']}");
                continue;
            }

            $product = Product::create([
                'name_en' => $data['name_en'],
                'name_ar' => $data['name_ar'],
                'name_he' => $data['name_he'],
                'slug' => Str::slug($data['name_en']),
                'sku' => $data['sku'],
                'short_description_en' => $data['short_en'],
                'short_description_ar' => $data['short_ar'],
                'short_description_he' => $data['short_he'],
                'description_en' => $data['desc_en'],
                'description_ar' => $data['desc_ar'],
                'description_he' => $data['desc_he'],
                'price' => $data['price'],
                'sale_price' => $data['sale_price'] ?? null,
                'cost_price' => $data['cost'],
                'stock_quantity' => $data['stock'],
                'min_stock_quantity' => 5,
                'main_image' => $data['image'],
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'is_active' => true,
                'is_featured' => $data['featured'] ?? false,
                'is_new' => $data['new'] ?? false,
                'is_bestseller' => $data['bestseller'] ?? false,
                'is_strong_offer' => $data['strong_offer'] ?? false,
                'discount_percentage' => $data['discount'] ?? 0,
                'track_stock' => true,
                'stock_status' => 'in_stock',
                'warranty' => $data['warranty'] ?? '1 Year',
            ]);

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $data['image'],
                'order' => 0,
                'is_primary' => true,
                'alt_text' => $data['name_en'],
            ]);

            foreach ($data['gallery'] ?? [] as $i => $img) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $img,
                    'order' => $i + 1,
                    'is_primary' => false,
                    'alt_text' => $data['name_en'] . ' - ' . ($i + 1),
                ]);
            }

            $created++;
        }

        $this->command->info("Created {$created} products with images!");
    }
}
