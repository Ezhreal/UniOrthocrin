<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OneDriveService
{
    private string $tenantId;
    private string $clientId;
    private string $clientSecret;
    private ?string $driveId;

    public function __construct(?string $tenantId = null, ?string $clientId = null, ?string $clientSecret = null, ?string $driveId = null)
    {
        $this->tenantId = $tenantId ?? config('services.onedrive.tenant_id');
        $this->clientId = $clientId ?? config('services.onedrive.client_id');
        $this->clientSecret = $clientSecret ?? config('services.onedrive.client_secret');
        $this->driveId = $driveId ?? config('services.onedrive.drive_id');
    }

    private function getAccessToken(): string
    {
        $response = Http::asForm()->post("https://login.microsoftonline.com/{$this->tenantId}/oauth2/v2.0/token", [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'client_credentials',
            'scope' => 'https://graph.microsoft.com/.default',
        ])->throw()->json();

        return $response['access_token'];
    }

    public function upload(string $localPath, string $remotePath): array
    {
        if (!is_file($localPath)) {
            return ['success' => false, 'message' => 'Local file not found'];
        }

        $accessToken = $this->getAccessToken();
        $driveTarget = $this->driveId ? "/drives/{$this->driveId}" : "/me/drive";

        $createSessionUrl = "https://graph.microsoft.com/v1.0{$driveTarget}/root:/" . ltrim($remotePath, '/') . ":/createUploadSession";

        $session = Http::withToken($accessToken)
            ->post($createSessionUrl, [
                'item' => [
                    '@microsoft.graph.conflictBehavior' => 'replace',
                ],
            ])->throw()->json();

        $uploadUrl = $session['uploadUrl'] ?? null;
        if (!$uploadUrl) {
            return ['success' => false, 'message' => 'Failed to create upload session'];
        }

        $fileSize = filesize($localPath);
        $chunkSize = 3276800; // 3.125 MB
        $handle = fopen($localPath, 'rb');
        $start = 0;

        try {
            while ($start < $fileSize) {
                $end = min($start + $chunkSize - 1, $fileSize - 1);
                $length = $end - $start + 1;

                $data = fread($handle, $length);

                $response = Http::withBody($data, 'application/octet-stream')
                    ->withHeaders([
                        'Content-Range' => "bytes {$start}-{$end}/{$fileSize}",
                    ])->put($uploadUrl);

                $response->throw();
                $start += $length;
            }
        } finally {
            if (is_resource($handle)) {
                fclose($handle);
            }
        }

        return ['success' => true];
    }
}


