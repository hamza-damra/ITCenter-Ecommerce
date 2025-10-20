<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateHomeSections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'home:populate-sections 
                            {--reset : Reset all product flags before populating}
                            {--featured=10 : Number of featured products}
                            {--new=10 : Number of new products}
                            {--bestseller=10 : Number of bestseller products}
                            {--sale=10 : Number of products on sale}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate home page sections with products (Featured, New Arrivals, Bestsellers, On Sale)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting to populate home page sections...');
        $this->newLine();

        // Reset flags if requested
        if ($this->option('reset')) {
            $this->info('🔄 Resetting all product flags...');
            Product::query()->update([
                'is_featured' => false,
                'is_new' => false,
                'is_bestseller' => false,
            ]);
            $this->info('✅ Flags reset complete');
            $this->newLine();
        }

        // 1. Populate Featured Products
        $this->populateFeatured();

        // 2. Populate New Arrivals
        $this->populateNewArrivals();

        // 3. Populate Bestsellers
        $this->populateBestsellers();

        // 4. Set Sale Prices
        $this->populateOnSale();

        $this->newLine();
        $this->info('✨ Home page sections populated successfully!');
        $this->newLine();

        // Show summary
        $this->showSummary();
    }

    /**
     * Populate featured products section
     */
    private function populateFeatured()
    {
        $limit = $this->option('featured');
        $this->info("📌 Setting {$limit} featured products...");

        $count = Product::active()
            ->where('is_featured', false)
            ->limit($limit)
            ->update(['is_featured' => true]);

        $this->line("   ✓ {$count} products marked as featured");
    }

    /**
     * Populate new arrivals section
     */
    private function populateNewArrivals()
    {
        $limit = $this->option('new');
        $this->info("🆕 Setting {$limit} new arrival products...");

        $count = Product::active()
            ->where('is_new', false)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->update(['is_new' => true]);

        $this->line("   ✓ {$count} products marked as new arrivals");
    }

    /**
     * Populate bestsellers section
     */
    private function populateBestsellers()
    {
        $limit = $this->option('bestseller');
        $this->info("🏆 Setting {$limit} bestseller products...");

        // Get products with most sales
        $topProducts = DB::table('order_items')
            ->select('product_id', DB::raw('SUM(quantity) as total_sales'))
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', 'completed')
            ->groupBy('product_id')
            ->orderBy('total_sales', 'desc')
            ->limit($limit)
            ->pluck('product_id');

        if ($topProducts->isEmpty()) {
            // If no sales data, just pick random active products
            $this->line("   ⚠ No sales data found, selecting random products");
            $count = Product::active()
                ->where('is_bestseller', false)
                ->limit($limit)
                ->update(['is_bestseller' => true]);
        } else {
            $count = Product::whereIn('id', $topProducts)
                ->where('is_active', true)
                ->update(['is_bestseller' => true]);
        }

        $this->line("   ✓ {$count} products marked as bestsellers");
    }

    /**
     * Populate on sale section
     */
    private function populateOnSale()
    {
        $limit = $this->option('sale');
        $this->info("🏷️  Setting {$limit} products on sale...");

        // Get products without sale price
        $products = Product::active()
            ->whereNull('sale_price')
            ->limit($limit)
            ->get();

        $count = 0;
        foreach ($products as $product) {
            // Apply 20% discount
            $salePrice = round($product->price * 0.8, 2);
            $product->update(['sale_price' => $salePrice]);
            $count++;
        }

        $this->line("   ✓ {$count} products set on sale (20% discount)");
    }

    /**
     * Show summary of all sections
     */
    private function showSummary()
    {
        $this->info('📊 Summary:');
        $this->newLine();

        $sections = [
            ['Section' => '📌 Featured Products', 'Count' => Product::active()->featured()->count()],
            ['Section' => '🆕 New Arrivals', 'Count' => Product::active()->new()->count()],
            ['Section' => '🏆 Bestsellers', 'Count' => Product::active()->bestseller()->count()],
            ['Section' => '🏷️  On Sale', 'Count' => Product::active()->whereNotNull('sale_price')->where('sale_price', '<', DB::raw('price'))->count()],
        ];

        $this->table(['Section', 'Product Count'], $sections);

        $this->newLine();
        $this->info('💡 Tip: Visit the home page to see the changes!');
    }
}
