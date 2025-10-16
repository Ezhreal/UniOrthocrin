<?php

namespace App\Jobs;

use App\Services\OneDriveService;
use App\Models\OneDriveSync;
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
    public int $timeout = 600; // 10 minutos para uploads grandes

    public function __construct(
        private string $localPath,
        private string $remotePath,
        private ?int $syncId = null
    ) {
        // Sanitizar o caminho remoto para evitar problemas de UTF-8
        $this->remotePath = $this->sanitizeRemotePath($remotePath);
    }

    private function sanitizeRemotePath(string $path): string
    {
        // Remover sequências UTF-8 inválidas
        $path = mb_convert_encoding($path, 'UTF-8', 'UTF-8');
        
        // Usar iconv para limpeza mais agressiva
        $path = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $path);
        
        // Remover caracteres problemáticos restantes
        $path = preg_replace('/[^\w\-_\.\/]/', '_', $path);
        
        return $path;
    }

    public function handle(OneDriveService $service): void
    {
        $sync = null;
        
        try {
            // Verificar se o arquivo existe
            if (!file_exists($this->localPath)) {
                // Tentar caminho alternativo se o arquivo não existe
                $alternativePath = str_replace('storage/app/private/private/', 'storage/app/private/', $this->localPath);
                if (file_exists($alternativePath)) {
                    $this->localPath = $alternativePath;
                }
            }
            
            if (!file_exists($this->localPath)) {
                $error = "Local file not found: {$this->localPath}";
                
                // Marcar como falhado se temos syncId
                if ($this->syncId) {
                    $sync = OneDriveSync::find($this->syncId);
                    if ($sync) {
                        $sync->markAsFailed($error);
                        // Deletar o registro de sync se o arquivo não existe
                        $sync->delete();
                    }
                }
                
                Log::warning('OneDrive upload skipped: Local file not found (sync record deleted)', [
                    'local' => $this->localPath,
                    'remote' => $this->remotePath,
                    'sync_id' => $this->syncId
                ]);
                
                // Não falhar o job, apenas pular
                return;
            }

            // Marcar como processando se temos syncId
            if ($this->syncId) {
                $sync = OneDriveSync::find($this->syncId);
                if ($sync) {
                    $sync->markAsProcessing();
                }
            }

            $result = $service->upload($this->localPath, $this->remotePath);
            
            if ($result['success'] ?? false) {
                // Marcar como sincronizado
                if ($sync) {
                    $sync->markAsSynced($result['url'] ?? null);
                }
                
                Log::info('OneDrive upload success', [
                    'local' => $this->localPath,
                    'remote' => $this->remotePath,
                    'url' => $result['url'] ?? 'N/A'
                ]);
            } else {
                $error = $result['error'] ?? $result['message'] ?? 'Unknown error';
                
                // Marcar como falhado
                if ($sync) {
                    $sync->markAsFailed($error);
                }
                
                Log::error('OneDrive upload failed', [
                    'local' => $this->localPath,
                    'remote' => $this->remotePath,
                    'error' => $error
                ]);
                $this->fail(new \RuntimeException($error));
            }
        } catch (\Exception $e) {
            // Marcar como falhado
            if ($sync) {
                $sync->markAsFailed($e->getMessage());
            }
            
            Log::error('OneDrive upload exception', [
                'local' => $this->localPath,
                'remote' => $this->remotePath,
                'exception' => $e->getMessage()
            ]);
            $this->fail($e);
        }
    }
}