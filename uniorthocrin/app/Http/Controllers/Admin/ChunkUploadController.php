<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class ChunkUploadController extends Controller
{
    /**
     * Recebe um chunk e salva em storage/app/chunks/{uuid}/chunk_{index}.
     */
    public function uploadChunk(Request $request)
    {
        $request->validate([
            'uuid'        => 'required|string',
            'chunkIndex'  => 'required|integer|min:1',
            'totalChunks' => 'required|integer|min:1',
            'file'        => 'required|file',
        ]);

        $uuid = $request->input('uuid');
        $chunkIndex = (int) $request->input('chunkIndex');

        $chunkDir = storage_path('app/chunks/' . $uuid);

        if (!is_dir($chunkDir)) {
            mkdir($chunkDir, 0775, true);
        }

        $uploadedChunk = $request->file('file');
        $chunkPath = $chunkDir . DIRECTORY_SEPARATOR . 'chunk_' . $chunkIndex;

        if (!move_uploaded_file($uploadedChunk->getRealPath(), $chunkPath)) {
            return response()->json([
                'received'   => false,
                'chunkIndex' => $chunkIndex,
                'message'    => 'Falha ao salvar chunk no servidor.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([
            'received'   => true,
            'chunkIndex' => $chunkIndex,
        ]);
    }

    /**
     * Remonta os chunks na ordem e grava o arquivo final em storage/app/uploads/{module}/UUID.ext.
     */
    public function assemble(Request $request)
    {
        $request->validate([
            'uuid'         => 'required|string',
            'originalName' => 'required|string',
            'mimeType'     => 'required|string',
            'totalChunks'  => 'required|integer|min:1',
            'module'       => 'required|string|in:campaign,product,training,library,news',
        ]);

        $uuid = $request->input('uuid');
        $originalName = $request->input('originalName');
        $mimeType = $request->input('mimeType');
        $totalChunks = (int) $request->input('totalChunks');
        $module = $request->input('module');

        $allowedMimeTypes = [
            'video/mp4',
            'video/quicktime',
            'video/x-msvideo',
            'image/jpeg',
            'image/png',
            'image/gif',
            'application/pdf',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowedExtensions = ['mp4', 'mov', 'avi', 'jpg', 'jpeg', 'png', 'gif', 'pdf', 'docx', 'xlsx'];

        if (!in_array($mimeType, $allowedMimeTypes, true) || !in_array($extension, $allowedExtensions, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Tipo de arquivo não permitido.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $chunkDir = storage_path('app/chunks/' . $uuid);

        if (!is_dir($chunkDir)) {
            return response()->json([
                'success' => false,
                'message' => 'Chunks não encontrados para o UUID informado.',
            ], Response::HTTP_BAD_REQUEST);
        }

        $finalUuid = Str::uuid()->toString();
        $finalName = $finalUuid . '.' . $extension;
        $relativeUploadPath = 'uploads/' . $module . '/' . $finalName;
        $finalPath = storage_path('app/' . $relativeUploadPath);

        if (!is_dir(dirname($finalPath))) {
            mkdir(dirname($finalPath), 0775, true);
        }

        $output = fopen($finalPath, 'wb');

        if ($output === false) {
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível criar o arquivo final no servidor.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        try {
            for ($i = 1; $i <= $totalChunks; $i++) {
                $chunkPath = $chunkDir . DIRECTORY_SEPARATOR . 'chunk_' . $i;

                if (!file_exists($chunkPath)) {
                    fclose($output);

                    return response()->json([
                        'success' => false,
                        'message' => "Chunk {$i} não encontrado.",
                    ], Response::HTTP_BAD_REQUEST);
                }

                $chunk = fopen($chunkPath, 'rb');
                if ($chunk === false) {
                    fclose($output);

                    return response()->json([
                        'success' => false,
                        'message' => "Não foi possível ler o chunk {$i}.",
                    ], Response::HTTP_INTERNAL_SERVER_ERROR);
                }

                while (!feof($chunk)) {
                    $buffer = fread($chunk, 1024 * 1024);
                    if ($buffer === false) {
                        fclose($chunk);
                        fclose($output);

                        return response()->json([
                            'success' => false,
                            'message' => "Erro ao montar o arquivo no chunk {$i}.",
                        ], Response::HTTP_INTERNAL_SERVER_ERROR);
                    }

                    fwrite($output, $buffer);
                }

                fclose($chunk);
            }

            fclose($output);

            $this->deleteDirectory($chunkDir);

            $size = @filesize($finalPath) ?: 0;

            return response()->json([
                'success'      => true,
                'path'         => $relativeUploadPath,
                'uuid'         => $finalUuid,
                'originalName' => $originalName,
                'mimeType'     => $mimeType,
                'size'         => $size,
            ]);
        } catch (\Throwable $e) {
            if (is_resource($output)) {
                fclose($output);
            }

            if (file_exists($finalPath)) {
                @unlink($finalPath);
            }

            return response()->json([
                'success' => false,
                'message' => 'Erro ao montar arquivo: ' . $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    protected function deleteDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            return;
        }

        $items = scandir($dir);

        foreach ($items as $item) {
            if ($item === '.' || $item === '..') {
                continue;
            }

            $path = $dir . DIRECTORY_SEPARATOR . $item;

            if (is_dir($path)) {
                $this->deleteDirectory($path);
            } else {
                @unlink($path);
            }
        }

        @rmdir($dir);
    }
}

