<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Http;

class CheckBrokenImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:check {--fix : Attempt to fix URLs by adding format parameters}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for broken external product image URLs and optionally fix them';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking product images...');
        
        $images = ProductImage::whereNotNull('image_path')
            ->where(function($query) {
                $query->where('image_path', 'like', 'http://%')
                      ->orWhere('image_path', 'like', 'https://%');
            })
            ->get();
        
        $this->info("Found {$images->count()} external image URLs to check");
        
        $broken = [];
        $bar = $this->output->createProgressBar($images->count());
        $bar->start();
        
        foreach ($images as $image) {
            try {
                $response = Http::timeout(5)->head($image->image_path);
                
                if ($response->status() >= 400) {
                    $broken[] = [
                        'id' => $image->id,
                        'product_id' => $image->product_id,
                        'url' => $image->image_path,
                        'status' => $response->status()
                    ];
                }
            } catch (\Exception $e) {
                $broken[] = [
                    'id' => $image->id,
                    'product_id' => $image->product_id,
                    'url' => $image->image_path,
                    'status' => 'Error: ' . $e->getMessage()
                ];
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);
        
        if (empty($broken)) {
            $this->info('✅ All images are accessible!');
            return 0;
        }
        
        $count = count($broken);
        $this->warn("Found {$count} broken image URLs:");
        $this->table(
            ['ID', 'Product ID', 'Status', 'URL'],
            array_map(fn($item) => [
                $item['id'],
                $item['product_id'],
                $item['status'],
                substr($item['url'], 0, 80) . '...'
            ], $broken)
        );
        
        if ($this->option('fix')) {
            $this->info('Attempting to fix URLs...');
            // You can add logic here to update URLs or mark them for manual review
            $this->warn('Manual review recommended for external CDN URLs');
        }
        
        return 0;
    }
}

