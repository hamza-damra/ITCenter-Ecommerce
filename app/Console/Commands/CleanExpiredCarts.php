<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CartItem;

class CleanExpiredCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:clean {--days=7 : Number of days after which cart items are considered expired}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired cart items to prevent database bloat';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        
        $this->info("Cleaning up cart items older than {$days} days...");
        
        $deleted = CartItem::where('updated_at', '<', now()->subDays($days))
            ->delete();

        $this->info("✓ Cleaned up {$deleted} expired cart items.");
        
        return 0;
    }
}
