<?php

namespace App\Console\Commands;

use App\Models\File;
use App\Services\ImageOptimizationService;
use Illuminate\Console\Command;

class OptimizeImagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:optimize {--force : Force optimization even if already optimized}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize all uploaded image files and generate WebP versions and thumbnails';

    /**
     * Execute the console command.
     *
     * @param ImageOptimizationService $optimizationService
     * @return int
     */
    public function handle(ImageOptimizationService $optimizationService)
    {
        $force = $this->option('force');

        $query = File::where('type', 'image');

        if (!$force) {
            $query->where('is_optimized', false);
        }

        $files = $query->get();
        $total = $files->count();

        if ($total === 0) {
            $this->info('No images found to optimize.');
            return Command::SUCCESS;
        }

        $this->info("Found {$total} image(s) to optimize.");
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $successCount = 0;
        foreach ($files as $file) {
            try {
                $success = $optimizationService->optimizeFile($file, $force);
                if ($success) {
                    $successCount++;
                }
            } catch (\Exception $e) {
                $this->error("\nError optimizing file ID {$file->id}: " . $e->getMessage());
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Completed! Optimized {$successCount} of {$total} images.");

        // 2. Optimize Model Thumbnails and Banners
        $this->newLine();
        $this->info("Optimizing model thumbnails and banners in place...");
        
        $modelsToOptimize = [
            \App\Models\Campaign::class => ['thumbnail_path', 'banner_path', 'banner_mobile_path'],
            \App\Models\Product::class => ['thumbnail_path'],
            \App\Models\Training::class => ['thumbnail_path'],
            \App\Models\News::class => ['thumbnail_path'],
            \App\Models\Media::class => ['thumbnail_path'],
            \App\Models\Library::class => ['thumbnail_path'],
        ];

        $modelOptimizedCount = 0;
        foreach ($modelsToOptimize as $modelClass => $attributes) {
            if (!class_exists($modelClass)) {
                continue;
            }
            
            $items = $modelClass::all();
            foreach ($items as $item) {
                foreach ($attributes as $attr) {
                    $path = $item->{$attr};
                    if ($path && !str_ends_with(strtolower($path), '.webp')) {
                        try {
                            $newPath = $optimizationService->optimizePathInPlace($path);
                            if ($newPath && $newPath !== $path) {
                                $item->{$attr} = $newPath;
                                $item->saveQuietly();
                                $modelOptimizedCount++;
                                $this->line("Optimized: {$modelClass} (ID {$item->id}) attribute '{$attr}' -> {$newPath}");
                            }
                        } catch (\Exception $e) {
                            $this->error("\nError optimizing {$modelClass} (ID {$item->id}) attribute '{$attr}': " . $e->getMessage());
                        }
                    }
                }
            }
        }

        $this->info("Completed! Optimized {$modelOptimizedCount} model thumbnails and banners in place.");

        return Command::SUCCESS;
    }
}
