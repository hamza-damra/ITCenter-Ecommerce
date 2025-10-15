<?php

namespace Database\Seeders;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create active offers
        $offers = Offer::factory()
            ->count(5)
            ->active()
            ->create();

        // Create some upcoming offers
        Offer::factory()
            ->count(3)
            ->create();

        // Attach products to offers
        $products = Product::all();
        
        foreach ($offers as $offer) {
            // Attach 5-15 random products to each offer
            $offer->products()->attach(
                $products->random(rand(5, 15))->pluck('id')->toArray()
            );
        }
    }
}
