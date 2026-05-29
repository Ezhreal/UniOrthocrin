<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\ChunkUpload;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class ProcessUppyUploads
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        \Illuminate\Support\Facades\Log::info('[ProcessUppyUploads] Handle request start', [
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'has_uppy_uploads' => $request->has('uppy_uploads'),
            'uppy_uploads' => $request->input('uppy_uploads'),
        ]);

        if ($request->has('uppy_uploads')) {
            $uppyUploads = $request->input('uppy_uploads');
            
            if (is_array($uppyUploads)) {
                foreach ($uppyUploads as $inputName => $uuids) {
                    \Illuminate\Support\Facades\Log::info('[ProcessUppyUploads] Processing input field', [
                        'inputName' => $inputName,
                        'uuids' => $uuids
                    ]);
                    if (is_array($uuids)) {
                        $files = [];
                        foreach ($uuids as $uuid) {
                            $chunkUpload = ChunkUpload::where('uuid', $uuid)->first();
                            
                            // Garante estabilidade continuando o loop caso o UUID seja inválido ou inexistente no DB
                            if (!$chunkUpload) {
                                \Illuminate\Support\Facades\Log::warning('[ProcessUppyUploads] ChunkUpload record not found in DB', ['uuid' => $uuid]);
                                continue;
                            }
                            
                            \Illuminate\Support\Facades\Log::info('[ProcessUppyUploads] ChunkUpload found', [
                                'uuid' => $uuid,
                                'status' => $chunkUpload->status,
                                'local_path' => $chunkUpload->local_path,
                                'exists' => $chunkUpload->local_path ? file_exists($chunkUpload->local_path) : false
                            ]);

                            if ($chunkUpload->status === 'completed' && $chunkUpload->local_path && file_exists($chunkUpload->local_path)) {
                                // Instancia UploadedFile com test=true para poder mover/renomear sem restrição HTTP
                                $uploadedFile = new UploadedFile(
                                    $chunkUpload->local_path,
                                    $chunkUpload->filename,
                                    $chunkUpload->mime_type,
                                    0, // error code UPLOAD_ERR_OK
                                    true // modo de teste
                                );
                                $files[] = $uploadedFile;
                                
                                // Marcar como 'injected' para que o Controller não processe novamente
                                $chunkUpload->update(['status' => 'injected']);

                                \Illuminate\Support\Facades\Log::info('[ProcessUppyUploads] UploadedFile instantiated successfully and status set to injected', [
                                    'filename' => $chunkUpload->filename,
                                    'path' => $chunkUpload->local_path
                                ]);
                            } elseif (in_array($chunkUpload->status, ['uploading', 'merging'])) {
                                // Injeta flag no request indicando arquivo em andamento sem instanciar arquivo vazio que quebraria controllers
                                $request->merge([
                                    'uppy_upload_in_progress' => array_merge(
                                        $request->input('uppy_upload_in_progress', []),
                                        [$uuid => [
                                            'filename' => $chunkUpload->filename,
                                            'mime_type' => $chunkUpload->mime_type,
                                            'input_name' => $inputName,
                                            'status' => $chunkUpload->status
                                        ]]
                                    )
                                ]);

                                \Illuminate\Support\Facades\Log::info('[ProcessUppyUploads] Chunk upload flagged as in progress', [
                                    'uuid' => $uuid,
                                    'filename' => $chunkUpload->filename
                                ]);
                            }
                        }
                        
                        if (!empty($files)) {
                            // Limpa o nome do campo para remover colchetes de array se houver
                            $key = str_ends_with($inputName, '[]') ? substr($inputName, 0, -2) : $inputName;
                            
                            // Mesclar com possíveis arquivos pequenos que foram enviados de forma padrão
                            $existingFiles = $request->file($key);
                            if ($existingFiles) {
                                \Illuminate\Support\Facades\Log::info('[ProcessUppyUploads] Merging with existing files', [
                                    'key' => $key,
                                    'count' => is_array($existingFiles) ? count($existingFiles) : 1
                                ]);
                                if (is_array($existingFiles)) {
                                    $files = array_merge($existingFiles, $files);
                                } else {
                                    array_unshift($files, $existingFiles);
                                }
                            }
                            
                            // Determina se deve injetar como array ou como objeto único
                            $isMultiple = is_array($existingFiles) || str_ends_with($inputName, '[]') || count($files) > 1 || is_array($uuids);
                            
                            \Illuminate\Support\Facades\Log::info('[ProcessUppyUploads] Injecting files into request', [
                                'key' => $key,
                                'isMultiple' => $isMultiple,
                                'files_count' => count($files)
                            ]);

                            $request->files->set($key, $isMultiple ? $files : $files[0]);

                            // Limpar o cache interno de arquivos convertidos do Laravel Request
                            $reflector = new \ReflectionClass($request);
                            if ($reflector->hasProperty('convertedFiles')) {
                                $property = $reflector->getProperty('convertedFiles');
                                $property->setAccessible(true);
                                $property->setValue($request, null);
                            }
                        } else {
                            \Illuminate\Support\Facades\Log::info('[ProcessUppyUploads] No files to inject for input field', ['inputName' => $inputName]);
                        }
                    }
                }
            }
        }

        return $next($request);
    }

    /**
     * Limpa os arquivos temporários locais após a resposta ser enviada ao cliente.
     */
    public function terminate(Request $request, $response): void
    {
        if ($request->has('uppy_uploads')) {
            $uppyUploads = $request->input('uppy_uploads');
            \Illuminate\Support\Facades\Log::info('[ProcessUppyUploads] terminate() cleanup starting', [
                'uppy_uploads' => $uppyUploads
            ]);
            if (is_array($uppyUploads)) {
                foreach ($uppyUploads as $uuids) {
                    if (is_array($uuids)) {
                        foreach ($uuids as $uuid) {
                             $chunkUpload = ChunkUpload::where('uuid', $uuid)->where('status', 'injected')->first();
                             if ($chunkUpload) {
                                 \Illuminate\Support\Facades\Log::info('[ProcessUppyUploads] terminate() cleaning up uuid', [
                                     'uuid' => $uuid,
                                     'local_path' => $chunkUpload->local_path,
                                     'exists' => file_exists($chunkUpload->local_path)
                                 ]);
                                 if (file_exists($chunkUpload->local_path)) {
                                     @unlink($chunkUpload->local_path);
                                 }
                                 $chunkUpload->delete();
                             } else {
                                \Illuminate\Support\Facades\Log::warning('[ProcessUppyUploads] terminate() ChunkUpload not found', ['uuid' => $uuid]);
                            }
                        }
                    }
                }
            }
        }
    }
}
