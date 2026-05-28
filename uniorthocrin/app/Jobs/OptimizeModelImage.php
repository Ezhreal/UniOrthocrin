<?php

namespace App\Jobs;

use App\Services\ImageOptimizationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OptimizeModelImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $model;
    protected $attribute;

    /**
     * Create a new job instance.
     *
     * @param mixed $model
     * @param string $attribute
     */
    public function __construct($model, string $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }

    /**
     * Execute the job.
     *
     * @param ImageOptimizationService $optimizationService
     * @return void
     */
    public function handle(ImageOptimizationService $optimizationService)
    {
        $path = $this->model->{$this->attribute};
        
        if (!$path) {
            return;
        }

        Log::info("Optimizing model image in place: Model: " . get_class($this->model) . ", ID: " . $this->model->id . ", Attribute: {$this->attribute}");

        $newPath = $optimizationService->optimizePathInPlace($path);

        if ($newPath && $newPath !== $path) {
            $this->model->{$this->attribute} = $newPath;
            $this->model->saveQuietly();
            Log::info("Model image optimized successfully. New path: {$newPath}");
        }
    }
}
