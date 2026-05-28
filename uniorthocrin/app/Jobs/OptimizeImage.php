<?php

namespace App\Jobs;

use App\Models\File;
use App\Services\ImageOptimizationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class OptimizeImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $file;
    protected $force;

    /**
     * Create a new job instance.
     *
     * @param File $file
     * @param bool $force
     */
    public function __construct(File $file, bool $force = false)
    {
        $this->file = $file;
        $this->force = $force;
    }

    /**
     * Execute the job.
     *
     * @param ImageOptimizationService $optimizationService
     * @return void
     */
    public function handle(ImageOptimizationService $optimizationService)
    {
        Log::info("Starting optimization job for file ID: {$this->file->id}");
        
        $success = $optimizationService->optimizeFile($this->file, $this->force);
        
        if ($success) {
            Log::info("Optimization job successfully completed for file ID: {$this->file->id}");
        } else {
            Log::info("Optimization job skipped or failed for file ID: {$this->file->id}");
        }
    }
}
