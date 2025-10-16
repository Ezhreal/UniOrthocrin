<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OneDriveService
{
    private const BASE_FOLDER = 'Uniorthocrin';
    private string $clientId;
    private string $clientSecret;
    private ?string $driveId;
    private string $authMode;
    private ?string $refreshToken;

    public function __construct(
        ?string $tenantId = null,
        ?string $clientId = null,
        ?string $clientSecret = null,
        ?string $driveId = null,
        ?string $authMode = null,
        ?string $refreshToken = null
    ) {
        $this->clientId = $clientId ?? config('services.onedrive.client_id');
        $this->clientSecret = $clientSecret ?? config('services.onedrive.client_secret');
        $this->driveId = $driveId ?? config('services.onedrive.drive_id');
        $this->authMode = $authMode ?? config('services.onedrive.auth_mode', 'delegated');
        $this->refreshToken = $refreshToken ?? config('services.onedrive.refresh_token');
    }

    private function getAccessToken(): string
    {
        if ($this->authMode === 'delegated' && $this->refreshToken) {
            // Modo delegado (conta pessoal ou Business com refresh_token)
            return $this->getDelegatedAccessToken();
        } else {
            // Modo app-only (Business com client_credentials)
            return $this->getAppOnlyAccessToken();
        }
    }

    private function getDelegatedAccessToken(): string
    {
        $tenant = config('services.onedrive.tenant_id') ?: 'consumers';
        
        $data = [
            'client_id' => $this->clientId,
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->refreshToken,
            'scope' => 'https://graph.microsoft.com/Files.ReadWrite https://graph.microsoft.com/User.Read offline_access',
        ];
        
        // Para contas pessoais (consumers), não enviar client_secret
        if ($tenant !== 'consumers' && $this->clientSecret) {
            $data['client_secret'] = $this->clientSecret;
        }
        
        $response = Http::asForm()->post("https://login.microsoftonline.com/{$tenant}/oauth2/v2.0/token", $data)->throw()->json();

        return $response['access_token'];
    }

    private function getAppOnlyAccessToken(): string
    {
        $tenant = config('services.onedrive.tenant_id');
        
        if (!$tenant) {
            throw new \Exception('Tenant ID é obrigatório para modo app-only');
        }

        $response = Http::asForm()->post("https://login.microsoftonline.com/{$tenant}/oauth2/v2.0/token", [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'client_credentials',
            'scope' => 'https://graph.microsoft.com/.default',
        ])->throw()->json();

        return $response['access_token'];
    }

    public function upload(string $localPath, string $remotePath): array
    {
        try {
            if (!is_file($localPath)) {
                return ['success' => false, 'message' => 'Local file not found'];
            }

            $accessToken = $this->getAccessToken();
            
            // Determinar o endpoint base
            $baseEndpoint = $this->authMode === 'delegated' 
                ? 'https://graph.microsoft.com/v1.0/me/drive'
                : "https://graph.microsoft.com/v1.0/drives/{$this->driveId}";

            // Garantir pasta raiz e prefixar caminho remoto
            $remotePath = '/' . trim(self::BASE_FOLDER, '/') . '/' . ltrim($remotePath, '/');

            // Upload em chunks para arquivos grandes
            $fileSize = filesize($localPath);
            $chunkSize = 4 * 1024 * 1024; // 4MB

            if ($fileSize <= $chunkSize) {
                // Upload direto para arquivos pequenos
                return $this->uploadDirect($accessToken, $baseEndpoint, $localPath, $remotePath);
            } else {
                // Upload em chunks para arquivos grandes
                return $this->uploadChunked($accessToken, $baseEndpoint, $localPath, $remotePath, $chunkSize);
            }
        } catch (\Exception $e) {
            \Log::error('OneDrive upload failed: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function uploadDirect(string $accessToken, string $baseEndpoint, string $localPath, string $remotePath): array
    {
        $fileName = basename($remotePath);
        $folderPath = dirname($remotePath);

        // Criar pasta se necessário
        if ($folderPath !== '.') {
            $this->createFolder($accessToken, $baseEndpoint, $folderPath);
        }

        $uploadUrl = $baseEndpoint . '/root:/' . ltrim($remotePath, '/') . ':/content';
        
        // Para arquivos binários, usar stream direto
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/octet-stream',
        ])->withBody(fopen($localPath, 'rb'), 'application/octet-stream')
          ->put($uploadUrl);

        if ($response->successful()) {
            return ['success' => true, 'url' => $response->json('webUrl')];
        }

        throw new \Exception('Upload failed: ' . $response->body());
    }

    private function uploadChunked(string $accessToken, string $baseEndpoint, string $localPath, string $remotePath, int $chunkSize): array
    {
        $fileSize = filesize($localPath);
        $fileName = basename($remotePath);
        $folderPath = dirname($remotePath);

        // Criar pasta se necessário
        if ($folderPath !== '.') {
            $this->createFolder($accessToken, $baseEndpoint, $folderPath);
        }

        // Iniciar sessão de upload
        $uploadSessionUrl = $baseEndpoint . '/root:/' . ltrim($remotePath, '/') . ':/createUploadSession';
        
        $sessionResponse = Http::withHeaders([
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ])->withBody(json_encode([
            'item' => [
                '@microsoft.graph.conflictBehavior' => 'replace',
                'name' => $fileName
            ]
        ]), 'application/json')
        ->post($uploadSessionUrl);

        if (!$sessionResponse->successful()) {
            throw new \Exception('Failed to create upload session: ' . $sessionResponse->body());
        }

        $uploadUrl = $sessionResponse->json('uploadUrl');
        $file = fopen($localPath, 'rb');
        $offset = 0;

        while ($offset < $fileSize) {
            $chunk = fread($file, $chunkSize);
            $chunkSizeActual = strlen($chunk);
            
            $response = Http::withHeaders([
                'Content-Length' => $chunkSizeActual,
                'Content-Range' => "bytes {$offset}-" . ($offset + $chunkSizeActual - 1) . "/{$fileSize}",
            ])->withBody($chunk, 'application/octet-stream')
            ->put($uploadUrl);

            if (!$response->successful()) {
                fclose($file);
                throw new \Exception('Chunk upload failed: ' . $response->body());
            }

            $offset += $chunkSizeActual;
        }

        fclose($file);
        return ['success' => true, 'url' => $response->json('webUrl')];
    }

    private function createFolder(string $accessToken, string $baseEndpoint, string $folderPath): void
    {
        $pathParts = explode('/', trim($folderPath, '/'));
        $currentPath = '';

        foreach ($pathParts as $part) {
            if (empty($part)) continue;
            
            $currentPath .= '/' . $part;
            $folderUrl = $baseEndpoint . '/root:' . $currentPath;

            // Verificar se pasta já existe
            $checkResponse = Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
            ])->get($folderUrl);

            if ($checkResponse->successful()) {
                continue; // Pasta já existe
            }

            // Criar pasta
            $parentPath = dirname($currentPath) === '.' ? '' : dirname($currentPath);
            $createUrl = $baseEndpoint . '/root:' . (empty($parentPath) ? '/' : $parentPath) . ':/children';

            Http::withHeaders([
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ])->post($createUrl, [
                'name' => $part,
                'folder' => new \stdClass(),
                '@microsoft.graph.conflictBehavior' => 'rename'
            ]);
        }
    }

    private function isBinaryFile(string $path): bool
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return in_array($extension, [
            // Documentos
            'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'rtf',
            // Imagens
            'jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'svg', 'webp',
            // Vídeos
            'mp4', 'avi', 'mov', 'wmv', 'flv', 'webm', 'mkv', 'm4v',
            // Áudios
            'mp3', 'wav', 'flac', 'aac', 'ogg', 'wma', 'm4a',
            // Arquivos compactados
            'zip', 'rar', '7z', 'tar', 'gz',
            // Outros
            'exe', 'dll', 'so', 'dmg', 'iso'
        ]);
    }
}


