<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class ChunkMergeService
{
    /**
     * Mescla os chunks de um upload temporário em um arquivo final unificado.
     *
     * @param string $uuid
     * @param string $filename
     * @param int $totalChunks
     * @return string Caminho do arquivo final mesclado
     */
    public function mergeChunks(string $uuid, string $filename, int $totalChunks): string
    {
        // Armazena chunks em diretório estruturado por UUID para evitar colisões
        $chunksDir = storage_path('app/chunks/' . $uuid);
        // Consolida arquivos finalizados em diretório compartilhado de temporários
        $tempDir = storage_path('app/temp');
        
        // Garante a existência do diretório temporário final para evitar falhas de escrita
        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        // Sanitiza o nome do arquivo para mitigar ataques de Path Traversal
        $safeFilename = preg_replace('/[^a-zA-Z0-9\._-]/', '_', $filename);
        $outputPath = $tempDir . '/' . $uuid . '_' . $safeFilename;

        // Abre fluxo de escrita no modo append para juntar os pedaços sequencialmente
        $outStream = fopen($outputPath, 'ab');
        if (!$outStream) {
            throw new \RuntimeException("Não foi possível criar o arquivo final em: {$outputPath}");
        }

        try {
            // Lê cada chunk por ordem numérica exata para garantir integridade estrutural
            for ($i = 0; $i < $totalChunks; $i++) {
                $chunkFile = $chunksDir . '/' . $i;

                // Evita remontagem corrompida interrompendo o processo caso falte algum pedaço
                if (!File::exists($chunkFile)) {
                    throw new \RuntimeException("Chunk de índice {$i} ausente durante a remontagem.");
                }

                // Abre o pedaço em modo binário de leitura para segurança multiplataforma
                $inStream = fopen($chunkFile, 'rb');
                if (!$inStream) {
                    throw new \RuntimeException("Não foi possível abrir o chunk {$i} para leitura.");
                }

                // Copia streams diretamente para evitar gargalos de uso de memória RAM no PHP
                stream_copy_to_stream($inStream, $outStream);
                fclose($inStream);
            }
        } finally {
            fclose($outStream);
        }

        // Remove pasta de chunks originais para economizar espaço de armazenamento
        File::deleteDirectory($chunksDir);

        return $outputPath;
    }
}
