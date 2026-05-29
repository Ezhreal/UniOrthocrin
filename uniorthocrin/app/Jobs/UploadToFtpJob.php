<?php

namespace App\Jobs;

use App\Models\FtpSync;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UploadToFtpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 1800; // 30 minutos para uploads grandes

    public function __construct(
        private int $ftpSyncId
    ) {}

    public function handle(): void
    {
        $sync = FtpSync::find($this->ftpSyncId);
        
        if (!$sync) {
            Log::warning("UploadToFtpJob: FtpSync record not found. ID: {$this->ftpSyncId}");
            return;
        }

        $sync->markAsProcessing();

        try {
            if (!file_exists($sync->local_path)) {
                $error = "Arquivo local para upload não encontrado: {$sync->local_path}";
                $sync->markAsFailed($error);
                Log::error("UploadToFtpJob failed: {$error}");
                return;
            }

            // Garante que o diretório remoto exista se necessário
            $remoteDir = dirname($sync->remote_path);
            if ($remoteDir && $remoteDir !== '.' && $remoteDir !== '/') {
                // Flysystem FTP tenta criar os diretórios automaticamente ao salvar o arquivo,
                // mas podemos garantir chamando makeDirectory se necessário.
            }

            // Abre o arquivo local para streaming de leitura
            $localStream = fopen($sync->local_path, 'r');
            if (!$localStream) {
                throw new \RuntimeException("Não foi possível abrir o arquivo local para leitura: {$sync->local_path}");
            }

            // Envia para o FTP utilizando streams
            // Flysystem gerencia a leitura em pedaços e o envio de forma eficiente
            $uploaded = Storage::disk('ftp')->writeStream(
                $sync->remote_path,
                $localStream
            );

            if (is_resource($localStream)) {
                fclose($localStream);
            }

            if (!$uploaded) {
                throw new \RuntimeException("Falha na gravação do arquivo via FTP (Storage::writeStream retornou falso).");
            }

            // Sincronizado com sucesso!
            $sync->markAsSynced();

            Log::info("FTP upload success", [
                'local' => $sync->local_path,
                'remote' => $sync->remote_path,
                'sync_id' => $sync->id
            ]);

            // Deletar o arquivo temporário local para liberar espaço (manter thumbnails locais)
            if ($sync->file_id && file_exists($sync->local_path)) {
                unlink($sync->local_path);
            }

        } catch (\Exception $e) {
            $sync->markAsFailed($e->getMessage());

            Log::error("FTP upload failed", [
                'local' => $sync->local_path,
                'remote' => $sync->remote_path,
                'error' => $e->getMessage()
            ]);

            // Lançar a exceção para que o Queue Worker trate a retentativa automática (retry)
            throw $e;
        }
    }
}
