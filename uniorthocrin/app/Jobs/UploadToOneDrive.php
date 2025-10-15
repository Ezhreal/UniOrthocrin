<?php

namespace App\Jobs;

use App\Services\OneDriveService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UploadToOneDrive implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 0; // unlimited for large files; adjust as needed

    private string $localPath;
    private string $remotePath;

    public function __construct(string $localPath, string $remotePath)
    {
        $this->localPath = $localPath;
        $this->remotePath = $remotePath;
    }

    public function handle(OneDriveService $service): void
    {
        $result = $service->upload($this->localPath, $this->remotePath);
        if (!($result['success'] ?? false)) {
            Log::error('OneDrive upload failed', [
                'localPath' => $this->localPath,
                'remotePath' => $this->remotePath,
                'result' => $result,
            ]);
            $this->fail(new \RuntimeException($result['message'] ?? 'Unknown OneDrive upload error'));
        }
    }
}


