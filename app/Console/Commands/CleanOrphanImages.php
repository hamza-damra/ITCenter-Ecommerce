<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CleanOrphanImages extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'images:clean-orphans
                            {--disk=public : Storage disk to scan}
                            {--dry-run : List orphan files without deleting them}
                            {--directories=products,categories,brands,banners : Comma-separated directories to scan}';

    /**
     * The console command description.
     */
    protected $description = 'Find and delete orphan image files that are no longer referenced in the database';

    /**
     * Database tables and columns that store image paths.
     * Maps table => [columns that hold image paths].
     */
    protected array $imageReferences = [
        'products'       => ['main_image'],
        'product_images' => ['image_path', 'thumbnail_path'],
        'categories'     => ['image'],
        'brands'         => ['logo'],
    ];

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $disk = $this->option('disk');
        $dryRun = $this->option('dry-run');
        $directories = array_map('trim', explode(',', $this->option('directories')));

        $this->info('Scanning for orphan images...');
        $this->info('Disk: ' . $disk);
        $this->info('Directories: ' . implode(', ', $directories));

        if ($dryRun) {
            $this->warn('DRY RUN MODE - no files will be deleted');
        }

        // 1. Collect all known image paths from the database
        $knownPaths = $this->collectKnownPaths();
        $this->info('Found ' . $knownPaths->count() . ' image references in database');

        // 2. Scan storage directories for actual files
        $storage = Storage::disk($disk);
        $orphanFiles = [];
        $totalFiles = 0;

        foreach ($directories as $directory) {
            if (!$storage->exists($directory)) {
                $this->line("  Directory '{$directory}' does not exist, skipping.");
                continue;
            }

            $files = $storage->allFiles($directory);
            $totalFiles += count($files);

            foreach ($files as $file) {
                // Skip non-image files (e.g., .gitignore, .htaccess)
                $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                    continue;
                }

                // Check if this file is referenced in any DB column
                if (!$this->isFileReferenced($file, $knownPaths)) {
                    $orphanFiles[] = $file;
                }
            }
        }

        $this->info("Scanned {$totalFiles} total files across " . count($directories) . " directories");
        $this->info('Found ' . count($orphanFiles) . ' orphan files');

        if (empty($orphanFiles)) {
            $this->info('No orphan files found. Storage is clean.');
            return Command::SUCCESS;
        }

        // 3. Display orphan files
        if ($this->output->isVerbose() || $dryRun) {
            $this->table(
                ['#', 'File Path', 'Size'],
                collect($orphanFiles)->map(function ($file, $index) use ($storage) {
                    $size = $storage->size($file);
                    return [
                        $index + 1,
                        $file,
                        $this->formatBytes($size),
                    ];
                })->toArray()
            );
        }

        $totalSize = collect($orphanFiles)->sum(fn ($file) => $storage->size($file));
        $this->info('Total orphan size: ' . $this->formatBytes($totalSize));

        // 4. Delete orphan files (unless dry run)
        if ($dryRun) {
            $this->warn('Dry run complete. Use without --dry-run to delete these files.');
            return Command::SUCCESS;
        }

        if (!$this->confirm('Delete ' . count($orphanFiles) . ' orphan files?')) {
            $this->info('Aborted.');
            return Command::SUCCESS;
        }

        $deleted = 0;
        $failed = 0;

        foreach ($orphanFiles as $file) {
            try {
                if ($storage->delete($file)) {
                    $deleted++;
                } else {
                    $failed++;
                    $this->error("  Failed to delete: {$file}");
                }
            } catch (\Exception $e) {
                $failed++;
                $this->error("  Error deleting {$file}: {$e->getMessage()}");
            }
        }

        $this->info("Deleted: {$deleted} files");
        if ($failed > 0) {
            $this->error("Failed: {$failed} files");
        }

        Log::info('CleanOrphanImages: Cleanup completed', [
            'deleted' => $deleted,
            'failed' => $failed,
            'freed_bytes' => $totalSize,
        ]);

        return $failed > 0 ? Command::FAILURE : Command::SUCCESS;
    }

    /**
     * Collect all known image paths from the database.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function collectKnownPaths(): \Illuminate\Support\Collection
    {
        $paths = collect();

        foreach ($this->imageReferences as $table => $columns) {
            // Check if table exists
            if (!DB::getSchemaBuilder()->hasTable($table)) {
                continue;
            }

            foreach ($columns as $column) {
                // Check if column exists
                if (!DB::getSchemaBuilder()->hasColumn($table, $column)) {
                    continue;
                }

                $values = DB::table($table)
                    ->whereNotNull($column)
                    ->where($column, '!=', '')
                    ->pluck($column);

                // Normalize paths
                $normalized = $values->map(function ($path) {
                    // Skip URLs
                    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
                        return null;
                    }
                    // Strip 'storage/' prefix if present
                    if (str_starts_with($path, 'storage/')) {
                        $path = substr($path, 8);
                    }
                    return ltrim($path, '/\\');
                })->filter();

                $paths = $paths->merge($normalized);
            }
        }

        return $paths->unique();
    }

    /**
     * Check if a file is referenced in the database.
     *
     * @param string $file The file path relative to disk root
     * @param \Illuminate\Support\Collection $knownPaths
     * @return bool
     */
    protected function isFileReferenced(string $file, \Illuminate\Support\Collection $knownPaths): bool
    {
        $normalized = ltrim($file, '/\\');

        // Check exact match
        if ($knownPaths->contains($normalized)) {
            return true;
        }

        // Check with 'storage/' prefix (in case DB stores it that way)
        if ($knownPaths->contains('storage/' . $normalized)) {
            return true;
        }

        // Check the filename only (for WebP conversions where extension changed)
        $filenameWithoutExt = pathinfo($normalized, PATHINFO_FILENAME);
        $directory = pathinfo($normalized, PATHINFO_DIRNAME);

        return $knownPaths->contains(function ($knownPath) use ($filenameWithoutExt, $directory) {
            $knownDir = pathinfo($knownPath, PATHINFO_DIRNAME);
            $knownFilename = pathinfo($knownPath, PATHINFO_FILENAME);
            return $knownDir === $directory && $knownFilename === $filenameWithoutExt;
        });
    }

    /**
     * Format bytes to human readable string.
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $power = $bytes > 0 ? floor(log($bytes, 1024)) : 0;
        $power = min($power, count($units) - 1);

        return round($bytes / pow(1024, $power), 2) . ' ' . $units[$power];
    }
}
