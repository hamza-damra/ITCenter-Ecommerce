<?php

namespace App\Console\Commands;

use App\Models\Banner;
use App\Models\PromotionalAd;
use App\Helpers\ImageHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ConvertImagesToDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:convert-to-database 
                            {--dry-run : Run without making changes}
                            {--force : Force conversion even if files don\'t exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Convert file-stored images to database storage for banners and promotional ads';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $force = $this->option('force');

        if ($dryRun) {
            $this->info('🔍 DRY RUN MODE - No changes will be made');
        }

        $this->info('🔄 Converting banners...');
        $this->convertBanners($dryRun, $force);

        $this->info('🔄 Converting promotional ads...');
        $this->convertPromotionalAds($dryRun, $force);

        $this->info('✅ Conversion complete!');
    }

    /**
     * Convert banners from file storage to database storage.
     */
    private function convertBanners($dryRun, $force)
    {
        $banners = Banner::where(function ($query) {
            $query->where('image_source', Banner::SOURCE_FILE)
                  ->orWhereNull('image_source')
                  ->orWhere('image_source', '');
        })->whereNotNull('image_path')->get();

        $converted = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($banners as $banner) {
            $filePath = public_path('storage/' . $banner->image_path);
            
            if (!file_exists($filePath) && !$force) {
                $this->warn("  ⚠️  Banner #{$banner->id}: File not found: {$banner->image_path}");
                $skipped++;
                continue;
            }

            if (!file_exists($filePath)) {
                $this->warn("  ⚠️  Banner #{$banner->id}: File not found, skipping");
                $skipped++;
                continue;
            }

            try {
                if (!$dryRun) {
                    // Read and compress the image
                    $imageData = file_get_contents($filePath);
                    $mimeType = mime_content_type($filePath);
                    
                    // Create a temporary uploaded file to use ImageHelper
                    $tempFile = tmpfile();
                    $tempPath = stream_get_meta_data($tempFile)['uri'];
                    file_put_contents($tempPath, $imageData);
                    
                    $uploadedFile = new \Illuminate\Http\UploadedFile(
                        $tempPath,
                        basename($banner->image_path),
                        $mimeType,
                        null,
                        true
                    );
                    
                    $compressed = ImageHelper::compressForDatabase($uploadedFile);
                    
                    // Update banner
                    $banner->update([
                        'image_source' => Banner::SOURCE_DATABASE,
                        'image_data' => $compressed['data'],
                        'image_filename' => $compressed['original_name'],
                        'image_mime_type' => $compressed['mime_type'],
                        'image_path' => null, // Clear old path
                    ]);
                    
                    fclose($tempFile);
                }
                
                $this->info("  ✅ Banner #{$banner->id}: Converted successfully");
                $converted++;
            } catch (\Exception $e) {
                $this->error("  ❌ Banner #{$banner->id}: {$e->getMessage()}");
                $failed++;
            }
        }

        $this->info("  📊 Banners: {$converted} converted, {$skipped} skipped, {$failed} failed");
    }

    /**
     * Convert promotional ads from file storage to database storage.
     */
    private function convertPromotionalAds($dryRun, $force)
    {
        // Get promotional ads that don't have database storage
        $promotionalAds = PromotionalAd::where(function ($query) {
            $query->where('image_source', '!=', PromotionalAd::SOURCE_DATABASE)
                  ->orWhereNull('image_source');
        })->whereNotNull('image_path')->get();

        $converted = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($promotionalAds as $ad) {
            $filePath = public_path('storage/' . $ad->image_path);
            
            if (!file_exists($filePath) && !$force) {
                $this->warn("  ⚠️  Promotional Ad #{$ad->id}: File not found: {$ad->image_path}");
                $skipped++;
                continue;
            }

            if (!file_exists($filePath)) {
                $this->warn("  ⚠️  Promotional Ad #{$ad->id}: File not found, skipping");
                $skipped++;
                continue;
            }

            try {
                if (!$dryRun) {
                    // Read and compress the image
                    $imageData = file_get_contents($filePath);
                    $mimeType = mime_content_type($filePath);
                    
                    // Create a temporary uploaded file to use ImageHelper
                    $tempFile = tmpfile();
                    $tempPath = stream_get_meta_data($tempFile)['uri'];
                    file_put_contents($tempPath, $imageData);
                    
                    $uploadedFile = new \Illuminate\Http\UploadedFile(
                        $tempPath,
                        basename($ad->image_path),
                        $mimeType,
                        null,
                        true
                    );
                    
                    $compressed = ImageHelper::compressForDatabase($uploadedFile);
                    
                    // Update promotional ad
                    $ad->update([
                        'image_source' => PromotionalAd::SOURCE_DATABASE,
                        'image_data' => $compressed['data'],
                        'image_filename' => $compressed['original_name'],
                        'image_mime_type' => $compressed['mime_type'],
                        'image_path' => null, // Clear old path
                    ]);
                    
                    fclose($tempFile);
                }
                
                $this->info("  ✅ Promotional Ad #{$ad->id}: Converted successfully");
                $converted++;
            } catch (\Exception $e) {
                $this->error("  ❌ Promotional Ad #{$ad->id}: {$e->getMessage()}");
                $failed++;
            }
        }

        $this->info("  📊 Promotional Ads: {$converted} converted, {$skipped} skipped, {$failed} failed");
    }
}

