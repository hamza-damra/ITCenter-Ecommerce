<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PromotionalOffer;
use App\Models\Product;
use Carbon\Carbon;

class PromotionalOfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get a random product to use for the promotional offer
        $product = Product::where('is_active', true)->first();
        
        if (!$product) {
            $this->command->warn('No active products found. Please seed products first.');
            return;
        }

        // Create a promotional offer
        PromotionalOffer::create([
            'product_id' => $product->id,
            'title_en' => 'Special Offer',
            'title_ar' => 'عرض خاص',
            'title_he' => 'הצעה מיוחדת',
            'original_price' => $product->price ?? 100.00,
            'sale_price' => ($product->sale_price ?? $product->price ?? 100.00) * 0.8, // 20% discount
            'discount_amount' => (($product->price ?? 100.00) - (($product->sale_price ?? $product->price ?? 100.00) * 0.8)),
            'discount_percentage' => 20,
            'features_en' => "• High Quality\n• Fast Shipping\n• 1 Year Warranty",
            'features_ar' => "• جودة عالية\n• شحن سريع\n• ضمان سنة واحدة",
            'features_he' => "• איכות גבוהה\n• משלוח מהיר\n• אחריות שנה",
            'start_date' => Carbon::now(),
            'end_date' => Carbon::now()->addDays(7), // Offer valid for 7 days
            'is_active' => true,
            'display_order' => 1,
        ]);

        $this->command->info('Promotional offer created successfully!');
    }
}
