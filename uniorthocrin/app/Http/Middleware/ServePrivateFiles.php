<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ServePrivateFiles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar se o usuário está autenticado
        if (!Auth::check()) {
            abort(403, 'Acesso negado');
        }

        // Extrair o caminho do arquivo da URL
        $path = $request->segment(2); // 'private' é o primeiro segmento
        
        if (!$path) {
            abort(404, 'Arquivo não encontrado');
        }

        // Construir o caminho completo
        $fullPath = 'private/' . $path;
        
        // Verificar se o arquivo existe
        $filePath = storage_path('app/' . $fullPath);
        
        if (!file_exists($filePath)) {
            abort(404, 'Arquivo não encontrado');
        }

        // Obter informações do arquivo
        $file = file_get_contents($filePath);
        $mimeType = mime_content_type($filePath);
        $fileName = basename($path);

        // Retornar o arquivo
        return response($file, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . $fileName . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
    }
}
