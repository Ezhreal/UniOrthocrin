<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use ZipArchive;
use App\Models\Product;
use App\Models\File;
use App\Models\Campaign;
use App\Models\Training;
use App\Models\Library;
use App\Models\News;
use App\Models\Media;
use App\Models\UserView;
use App\Services\ProfileService;

class DownloadController extends Controller
{
    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function download(Request $request)
    {
        Log::info('=== INÍCIO DO DOWNLOAD ===');
        
        // Verificar se já foi enviado algum output
        if (headers_sent()) {
            Log::error('Headers já foram enviados antes do download');
            return response()->json(['success' => false, 'message' => 'Erro interno do servidor']);
        }

        Log::info('Download request iniciado', [
            'all_inputs' => $request->all(),
            'method' => $request->method(),
            'url' => $request->url(),
            'user_agent' => $request->header('User-Agent'),
            'content_type' => $request->header('Content-Type')
        ]);
        
        $type = $request->input('type');
        $contentId = $request->input('content_id');
        $contentType = $request->input('content_type'); // product, training, library, marketing, news
        $context = $request->input('context');
        $productIds = $request->input('product_ids');
        
        $restrictFileIds = null;
        if ($request->has('file_ids')) {
            $rawIds = $request->input('file_ids');
            if (! is_array($rawIds)) {
                $rawIds = $rawIds !== null && $rawIds !== '' ? [$rawIds] : [];
            }
            $restrictFileIds = array_values(array_filter(array_map('intval', $rawIds)));
            if ($restrictFileIds === []) {
                return response()->json(['success' => false, 'message' => 'Nenhum arquivo selecionado para download']);
            }
        }

        Log::info('Parâmetros extraídos', [
            'type' => $type,
            'contentId' => $contentId,
            'contentType' => $contentType,
            'context' => $context,
            'productIds' => $productIds,
            'file_ids' => $restrictFileIds,
        ]);
        
        // Verificar se o usuário está autenticado
        if (!auth()->check()) {
            Log::error('Usuário não autenticado');
            return response()->json(['success' => false, 'message' => 'Usuário não autenticado']);
        }
        
        // Verificar permissões de download
        if (!$this->checkDownloadPermissions($contentId, $contentType)) {
            Log::error('Usuário sem permissão para download', [
                'user_id' => auth()->id(),
                'content_id' => $contentId,
                'content_type' => $contentType
            ]);
            return response()->json(['success' => false, 'message' => 'Você não tem permissão para baixar este conteúdo']);
        }
        
        Log::info('Usuário autenticado', [
            'user_id' => auth()->id(),
            'user_email' => auth()->user()->email
        ]);

        // Registrar download nas estatísticas
        try {
            $modelMap = [
                'product' => \App\Models\Product::class,
                'training' => \App\Models\Training::class,
                'library' => \App\Models\Library::class,
                'marketing' => \App\Models\Campaign::class,
                'news' => \App\Models\News::class,
                'media' => \App\Models\Media::class,
            ];

            if (isset($modelMap[$contentType]) && $contentId) {
                $userView = UserView::recordView(auth()->id(), $modelMap[$contentType], $contentId);
                $userView->recordDownload();
                Log::info('Download registrado em UserView', [
                    'user_id' => auth()->id(),
                    'content_type' => $contentType,
                    'content_id' => $contentId
                ]);
            }
        } catch (\Exception $e) {
            Log::warning('Falha ao registrar estatística de download: ' . $e->getMessage());
        }

        if ($restrictFileIds !== null && count($restrictFileIds) === 1) {
            return $this->downloadSingleAuthorizedFile($contentId, $contentType, $restrictFileIds[0], (string) $type);
        }
        
        try {
            Log::info('Entrando no switch', ['type' => $type]);
            
            switch ($type) {
                case 'images':
                case 'image':
                    Log::info('Executando downloadImages');
                    return $this->downloadImages($contentId, $contentType, $restrictFileIds);
                case 'videos':
                case 'video':
                    Log::info('Executando downloadVideos');
                    return $this->downloadVideos($contentId, $contentType, $restrictFileIds);
                case 'pdfs':
                case 'pdf':
                    Log::info('Executando downloadPdfs');
                    return $this->downloadPdfs($contentId, $contentType, $restrictFileIds);
                case 'all':
                    Log::info('Executando downloadAll');
                    return $this->downloadAll($contentId, $contentType, $restrictFileIds);
                case 'gallery_images':
                    Log::info('Executando downloadGalleryImages');
                    return $this->downloadGalleryImages($context);
                case 'gallery_videos':
                    Log::info('Executando downloadGalleryVideos');
                    return $this->downloadGalleryVideos($context);
                case 'all_products':
                    Log::info('Executando downloadAllProducts');
                    return $this->downloadAllProducts($request);

                default:
                    Log::warning('Tipo não suportado', ['type' => $type]);
                    return response()->json(['success' => false, 'message' => 'Tipo de download não suportado']);
            }
        } catch (\Exception $e) {
            Log::error('Download error: ' . $e->getMessage(), [
                'type' => $type,
                'contentId' => $contentId,
                'contentType' => $contentType,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Erro ao gerar download: ' . $e->getMessage()]);
        }
    }
    
    private function downloadImages($contentId, $contentType, ?array $restrictFileIds = null)
    {
        Log::info('downloadImages iniciado', ['contentId' => $contentId, 'contentType' => $contentType]);
        
        try {
            $content = $this->getContent($contentId, $contentType);
            Log::info('Content encontrado', ['content' => $content->name]);
            
            $files = $this->getFilesByType($contentId, $contentType, 'image');
            if ($restrictFileIds !== null) {
                $files = $files->whereIn('id', $restrictFileIds)->values();
            }
            Log::info('Arquivos encontrados', ['count' => $files->count()]);
                
            if ($files->isEmpty()) {
                Log::warning('Nenhuma imagem encontrada');
                return response()->json(['success' => false, 'message' => 'Nenhuma imagem encontrada']);
            }
            
            // Criar ZIP simples
            Log::info('Criando ZIP simples');
            $zipPath = $this->createSimpleZip($files, $contentType . '_' . $contentId . '_imagens');
            Log::info('ZIP criado', ['path' => $zipPath]);
            
                $slug = session('active_profile_slug');
                $prefix = $slug ? '/' . $slug : '';
                $response = response()->json([
                   'success' => true,
                   'downloadUrl' => url($prefix . '/private/' . str_replace('private/', '', $zipPath)),
                   'filename' => $contentType . '_' . $content->name . '_imagens.zip'
               ]);
            
            Log::info('Resposta criada', ['response' => $response]);
            return $response;
            
        } catch (\Exception $e) {
            Log::error('Erro em downloadImages', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }
    
    private function downloadVideos($contentId, $contentType, ?array $restrictFileIds = null)
    {
        Log::info('downloadVideos iniciado', ['contentId' => $contentId, 'contentType' => $contentType]);
        
        try {
            $content = $this->getContent($contentId, $contentType);
            Log::info('Content encontrado', ['content' => $content->name]);
            
            $files = $this->getFilesByType($contentId, $contentType, 'video');
            if ($restrictFileIds !== null) {
                $files = $files->whereIn('id', $restrictFileIds)->values();
            }
            Log::info('Arquivos encontrados', ['count' => $files->count()]);
                
            if ($files->isEmpty()) {
                Log::warning('Nenhum vídeo encontrado');
                return response()->json(['success' => false, 'message' => 'Nenhum vídeo encontrado']);
            }
            
            // Criar ZIP simples
            Log::info('Criando ZIP simples');
            $zipPath = $this->createSimpleZip($files, $contentType . '_' . $contentId . '_videos');
            Log::info('ZIP criado', ['path' => $zipPath]);
            
            $slug = session('active_profile_slug');
            $prefix = $slug ? '/' . $slug : '';
            $response = response()->json([
                'success' => true,
                'downloadUrl' => url($prefix . '/private/' . str_replace('private/', '', $zipPath)),
                'filename' => $contentType . '_' . $content->name . '_videos.zip'
            ]);
            
            Log::info('Resposta criada', ['response' => $response]);
            return $response;
            
        } catch (\Exception $e) {
            Log::error('Erro em downloadVideos', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }
    
    private function downloadPdfs($contentId, $contentType, ?array $restrictFileIds = null)
    {
        try {
            $content = $this->getContent($contentId, $contentType);
            Log::info('Content encontrado', ['content' => $content->name]);
            
            $files = $this->getFilesByType($contentId, $contentType, 'pdf');
            if ($restrictFileIds !== null) {
                $files = $files->whereIn('id', $restrictFileIds)->values();
            }
            Log::info('Arquivos encontrados', ['count' => $files->count()]);
                
            if ($files->isEmpty()) {
                Log::warning('Nenhum PDF encontrado');
                return response()->json(['success' => false, 'message' => 'Nenhum PDF encontrado']);
            }
            
            // Criar ZIP simples
            Log::info('Criando ZIP simples');
            $zipPath = $this->createSimpleZip($files, $contentType . '_' . $contentId . '_pdfs');
            Log::info('ZIP criado', ['path' => $zipPath]);
            
            $slug = session('active_profile_slug');
            $prefix = $slug ? '/' . $slug : '';
            $response = response()->json([
                'success' => true,
                'downloadUrl' => url($prefix . '/private/' . str_replace('private/', '', $zipPath)),
                'filename' => $contentType . '_' . $content->name . '_pdfs.zip'
            ]);
            
            Log::info('Resposta criada', ['response' => $response]);
            return $response;
            
        } catch (\Exception $e) {
            Log::error('Erro em downloadPdfs', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            throw $e;
        }
    }
    
    private function downloadAll($contentId, $contentType, ?array $restrictFileIds = null)
    {
        $content = $this->getContent($contentId, $contentType);
        $files = $this->getFilesByType($contentId, $contentType, 'all');
        if ($restrictFileIds !== null) {
            $files = $files->whereIn('id', $restrictFileIds)->values();
        }
        
        if ($files->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Nenhum arquivo encontrado']);
        }
        
        $zipPath = $this->createZipFromFiles($files, $contentType . '_' . $contentId . '_completo');
        
        $slug = session('active_profile_slug');
        $prefix = $slug ? '/' . $slug : '';
        return response()->json([
            'success' => true,
            'downloadUrl' => url($prefix . "/private/" . str_replace('private/', '', $zipPath)),
            'filename' => $contentType . '_' . $content->name . '_completo.zip'
        ]);
    }
    
    private function getFilesByType($contentId, $contentType, $fileType)
    {
        switch ($contentType) {
            case 'product':
                return $this->getProductFiles($contentId, $fileType);
            case 'training':
                return $this->getTrainingFiles($contentId, $fileType);
            case 'library':
                return $this->getLibraryFiles($contentId, $fileType);
            case 'marketing':
                return $this->getMarketingFiles($contentId, $fileType);
            case 'news':
                return $this->getNewsFiles($contentId, $fileType);
            case 'media':
                return $this->getMediaFiles($contentId, $fileType);
            default:
                throw new \Exception('Tipo de conteúdo não suportado: ' . $contentType);
        }
    }
    
    private function getProductFiles($productId, $fileType)
    {
        Log::info('getProductFiles iniciado', ['productId' => $productId, 'fileType' => $fileType]);
        
        $query = File::where('path', 'like', 'private/products/' . $productId . '/%')
            ->where(function($q) {
                // Excluir thumbnails - arquivos que não são para download
                $q->where('name', 'not like', '%thumb%')
                  ->where('path', 'not like', '%thumb%')
                  ->where('type', '!=', 'thumbnail');
            });
        Log::info('Query base criada (sem thumbnails)', ['path_pattern' => 'private/products/' . $productId . '/%']);
        
        if ($fileType !== 'all') {
            $query->where('type', $fileType);
            Log::info('Filtro de tipo aplicado', ['type' => $fileType]);
        }
        
        $files = $query->get();
        Log::info('Arquivos encontrados (sem thumbnails)', ['count' => $files->count(), 'files' => $files->pluck('path', 'id')->toArray()]);
        
        return $files;
    }
    
    private function getTrainingFiles($trainingId, $fileType)
    {
        Log::info('getTrainingFiles iniciado', ['trainingId' => $trainingId, 'fileType' => $fileType]);
        
        // Buscar arquivos através da tabela pivot
        $query = File::join('training_files', 'files.id', '=', 'training_files.file_id')
                     ->where('training_files.training_id', $trainingId)
                     ->where(function($q) {
                         // Excluir thumbnails - arquivos que não são para download
                         $q->where('files.name', 'not like', '%thumb%')
                           ->where('files.path', 'not like', '%thumb%')
                           ->where('files.type', '!=', 'thumbnail');
                     });
        
        if ($fileType !== 'all') {
            $query->where('files.type', $fileType);
        }
        
        $files = $query->select('files.*')->get();
        Log::info('Arquivos de treinamento encontrados (sem thumbnails)', ['count' => $files->count(), 'files' => $files->pluck('path', 'id')->toArray()]);
        
        return $files;
    }
    
    private function getLibraryFiles($libraryId, $fileType)
    {
        Log::info('getLibraryFiles iniciado', ['libraryId' => $libraryId, 'fileType' => $fileType]);
        
        // Buscar arquivos através da tabela pivot
        $query = File::join('library_files', 'files.id', '=', 'library_files.file_id')
                     ->where('library_files.library_id', $libraryId)
                     ->where(function($q) {
                         // Excluir thumbnails - arquivos que não são para download
                         $q->where('files.name', 'not like', '%thumb%')
                           ->where('files.path', 'not like', '%thumb%')
                           ->where('files.type', '!=', 'thumbnail');
                     });
        
        if ($fileType !== 'all') {
            $query->where('files.type', $fileType);
        }
        
        $files = $query->select('files.*')->get();
        Log::info('Arquivos de biblioteca encontrados (sem thumbnails)', ['count' => $files->count(), 'files' => $files->pluck('path', 'id')->toArray()]);
        
        return $files;
    }
    
    private function getMarketingFiles($campaignId, $fileType)
    {
        Log::info('getMarketingFiles iniciado', ['campaignId' => $campaignId, 'fileType' => $fileType]);
        
        // Buscar arquivos através das tabelas pivot
        $fileIds = collect();
        
        // Posts
        $postFileIds = DB::table('campaign_post_files')
            ->join('campaign_posts', 'campaign_post_files.campaign_post_id', '=', 'campaign_posts.id')
            ->where('campaign_posts.campaign_id', $campaignId)
            ->pluck('campaign_post_files.file_id');
        $fileIds = $fileIds->merge($postFileIds);
        
        // Folders
        $folderFileIds = DB::table('campaign_folder_files')
            ->join('campaign_folders', 'campaign_folder_files.campaign_folder_id', '=', 'campaign_folders.id')
            ->where('campaign_folders.campaign_id', $campaignId)
            ->pluck('campaign_folder_files.file_id');
        $fileIds = $fileIds->merge($folderFileIds);
        
        // Videos
        $videoFileIds = DB::table('campaign_video_files')
            ->join('campaign_videos', 'campaign_video_files.campaign_video_id', '=', 'campaign_videos.id')
            ->where('campaign_videos.campaign_id', $campaignId)
            ->pluck('campaign_video_files.file_id');
        $fileIds = $fileIds->merge($videoFileIds);
        
        // Miscellaneous
        $miscFileIds = DB::table('campaign_miscellaneous_files')
            ->join('campaign_miscellaneous', 'campaign_miscellaneous_files.campaign_miscellaneous_id', '=', 'campaign_miscellaneous.id')
            ->where('campaign_miscellaneous.campaign_id', $campaignId)
            ->pluck('campaign_miscellaneous_files.file_id');
        $fileIds = $fileIds->merge($miscFileIds);
        
        $query = File::whereIn('id', $fileIds)
            ->where(function($q) {
                // Excluir thumbnails - arquivos que não são para download
                $q->where('name', 'not like', '%thumb%')
                  ->where('path', 'not like', '%thumb%')
                  ->where('type', '!=', 'thumbnail'); // Se houver um tipo específico para thumbnail
            });
        
        if ($fileType !== 'all') {
            $query->where('type', $fileType);
        }
        
        $files = $query->get();
        Log::info('Arquivos de marketing encontrados (sem thumbnails)', ['count' => $files->count(), 'files' => $files->pluck('path', 'id')->toArray()]);
        
        return $files;
    }
    
    private function getNewsFiles($newsId, $fileType)
    {
        Log::info('getNewsFiles iniciado', ['newsId' => $newsId, 'fileType' => $fileType]);
        
        // Buscar arquivo principal da notícia
        $news = News::find($newsId);
        if (!$news || !$news->news_file_id) {
            return collect();
        }
        
        $query = File::where('id', $news->news_file_id);
        
        if ($fileType !== 'all') {
            $query->where('type', $fileType);
        }
        
        $files = $query->get();
        Log::info('Arquivos de news encontrados', ['count' => $files->count(), 'files' => $files->pluck('path', 'id')->toArray()]);
        
        return $files;
    }

    private function getMediaFiles($mediaId, $fileType)
    {
        Log::info('getMediaFiles iniciado', ['mediaId' => $mediaId, 'fileType' => $fileType]);
        
        // Buscar arquivos através da tabela pivot
        $query = File::join('media_files', 'files.id', '=', 'media_files.file_id')
                     ->where('media_files.media_id', $mediaId)
                     ->where(function($q) {
                         // Excluir thumbnails - arquivos que não são para download
                         $q->where('files.name', 'not like', '%thumb%')
                           ->where('files.path', 'not like', '%thumb%')
                           ->where('files.type', '!=', 'thumbnail');
                     });
        
        if ($fileType !== 'all') {
            $query->where('files.type', $fileType);
        }
        
        $files = $query->select('files.*')->get();
        Log::info('Arquivos de mídia encontrados (sem thumbnails)', ['count' => $files->count(), 'files' => $files->pluck('path', 'id')->toArray()]);
        
        return $files;
    }
    
    private function downloadAllProducts(Request $request)
    {
        $user = $request->user();
        $categoryId = $request->input('category_id');
        $search = $request->input('search');
        
        $activeProfileId = $this->profileService->getActiveProfile()->id;

        // Buscar produtos com os mesmos filtros da lista
        $query = Product::active()
            ->with(['category', 'series', 'mainFile'])
            ->whereHas('permissions', function($q) use ($activeProfileId) {
                $q->where('user_type_id', $activeProfileId)
                  ->where('can_view', true);
            });

        // Aplicar filtros
        if ($categoryId) {
            $query->where('product_category_id', $categoryId);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->get();
        
        if ($products->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Nenhum produto encontrado']);
        }
        
        // Buscar todos os arquivos dos produtos
        $allFiles = collect();
        foreach ($products as $product) {
            $files = File::where('path', 'like', 'private/products/' . $product->id . '/%')->get();
            $allFiles = $allFiles->merge($files);
        }
        
        if ($allFiles->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Nenhum arquivo encontrado']);
        }
        
        $zipPath = $this->createZipFromFiles($allFiles, 'todos_produtos_' . time());
        
        $slug = session('active_profile_slug');
        $prefix = $slug ? '/' . $slug : '';
        return response()->json([
            'success' => true,
            'downloadUrl' => url($prefix . "/private/" . str_replace('private/', '', $zipPath)),
            'filename' => 'todos_produtos.zip'
        ]);
    }
    

    
    private function getContent($contentId, $contentType)
    {
        // Mapear tipos de conteúdo para modelos corretos
        $modelMap = [
            'product' => Product::class,
            'training' => Training::class,
            'library' => Library::class,
            'marketing' => Campaign::class,
            'news' => News::class,
            'media' => Media::class,
        ];
        
        if (!isset($modelMap[$contentType])) {
            throw new \Exception('Tipo de conteúdo não suportado: ' . $contentType);
        }
        
        $modelClass = $modelMap[$contentType];
        return $modelClass::findOrFail($contentId);
    }
    
    private function downloadGalleryImages($context)
    {
        // Implementar para galerias gerais
        $files = File::where('type', 'image')->take(20)->get();
        
        if ($files->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Nenhuma imagem encontrada']);
        }
        
        $zipPath = $this->createZipFromFiles($files, 'galeria_imagens');
        
        $slug = session('active_profile_slug');
        $prefix = $slug ? '/' . $slug : '';
        return response()->json([
            'success' => true,
            'downloadUrl' => url($prefix . "/private/" . str_replace('private/', '', $zipPath)),
            'filename' => 'galeria_imagens.zip'
        ]);
    }
    
    private function downloadGalleryVideos($context)
    {
        // Implementar para galerias de vídeos
        $files = File::where('type', 'video')->take(10)->get();
        
        if ($files->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Nenhum vídeo encontrado']);
        }
        
        $zipPath = $this->createZipFromFiles($files, 'galeria_videos');
        
        $slug = session('active_profile_slug');
        $prefix = $slug ? '/' . $slug : '';
        return response()->json([
            'success' => true,
            'downloadUrl' => url($prefix . "/private/" . str_replace('private/', '', $zipPath)),
            'filename' => 'galeria_videos.zip'
        ]);
    }
    
        private function createZipFromFiles($files, $zipName)
    {
        // Criar diretório private/downloads se não existir
        $downloadsPath = storage_path('app/private/downloads');
        if (!file_exists($downloadsPath)) {
            mkdir($downloadsPath, 0755, true);
        }
        
        // Garantir permissões corretas
        if (file_exists($downloadsPath)) {
            chmod($downloadsPath, 0755);
        }

        // Limpar ZIPs antigos (mais de 1 hora)
        $this->cleanOldZips($downloadsPath);

        $zip = new ZipArchive();
        $zipPath = 'private/downloads/' . $zipName . '_' . time() . '.zip';
        $fullZipPath = storage_path('app/' . $zipPath);

        Log::info('Criando ZIP', ['path' => $fullZipPath, 'files_count' => $files->count()]);

        if ($zip->open($fullZipPath, ZipArchive::CREATE) !== TRUE) {
            throw new \Exception('Não foi possível criar o arquivo ZIP em: ' . $fullZipPath);
        }

        $addedFiles = 0;
        foreach ($files as $file) {
            // Usar o caminho correto para arquivos privados
            $filePath = storage_path('app/' . $file->path);
            Log::info('Verificando arquivo', ['file_path' => $filePath, 'exists' => file_exists($filePath), 'file_name' => $file->name]);

            if (file_exists($filePath)) {
                // Verificar se o arquivo não é um thumbnail (que não deve ser incluído no download)
                $isThumbnail = strpos($file->name, 'thumb') !== false || strpos($file->path, 'thumb') !== false;
                
                if (!$isThumbnail) {
                    $result = $zip->addFile($filePath, $file->name);
                    if ($result) {
                        $addedFiles++;
                        Log::info('Arquivo adicionado ao ZIP', ['file' => $file->name]);
                    } else {
                        Log::error('Erro ao adicionar arquivo ao ZIP', ['file' => $file->name]);
                    }
                } else {
                    Log::info('Thumbnail ignorado no download', ['file' => $file->name]);
                }
            } else {
                Log::error('Arquivo não encontrado', ['file_path' => $filePath]);
            }
        }

        $result = $zip->close();
        if (!$result) {
            throw new \Exception('Erro ao fechar o arquivo ZIP');
        }

        Log::info('ZIP criado com sucesso', ['path' => $zipPath, 'added_files' => $addedFiles]);

        return $zipPath;
    }
    
    private function createStreamingZip($files, $zipName)
    {
        // Criar diretório private/downloads se não existir
        $downloadsPath = storage_path('app/private/downloads');
        if (!file_exists($downloadsPath)) {
            mkdir($downloadsPath, 0755, true);
        }
        
        // Garantir permissões corretas
        if (file_exists($downloadsPath)) {
            chmod($downloadsPath, 0755);
        }

        // Limpar ZIPs antigos (mais de 1 hora)
        $this->cleanOldZips($downloadsPath);

        $zip = new ZipArchive();
        $zipPath = 'private/downloads/' . $zipName . '_' . time() . '.zip';
        $fullZipPath = storage_path('app/' . $zipPath);

        Log::info('Criando ZIP com streaming', ['path' => $fullZipPath, 'files_count' => $files->count()]);

        if ($zip->open($fullZipPath, ZipArchive::CREATE) !== TRUE) {
            throw new \Exception('Não foi possível criar o arquivo ZIP em: ' . $fullZipPath);
        }

        $addedFiles = 0;
        foreach ($files as $file) {
            // Usar o caminho correto para arquivos privados
            $filePath = storage_path('app/' . $file->path);
            Log::info('Verificando arquivo', ['file_path' => $filePath, 'exists' => file_exists($filePath), 'file_name' => $file->name]);

            if (file_exists($filePath)) {
                // Usar addFile que não carrega o arquivo na memória
                $result = $zip->addFile($filePath, $file->name);
                if ($result) {
                    $addedFiles++;
                    Log::info('Arquivo adicionado ao ZIP', ['file' => $file->name]);
                } else {
                    Log::error('Erro ao adicionar arquivo ao ZIP', ['file' => $file->name]);
                }
            } else {
                Log::error('Arquivo não encontrado', ['file_path' => $filePath]);
            }
        }

        $result = $zip->close();
        if (!$result) {
            throw new \Exception('Erro ao fechar o arquivo ZIP');
        }

        Log::info('ZIP criado com sucesso usando streaming', ['path' => $zipPath, 'added_files' => $addedFiles]);

        return $zipPath;
    }
    
    private function createSimpleZip($files, $zipName)
    {
        // Criar diretório private/downloads se não existir
        $downloadsPath = storage_path('app/private/downloads');
        if (!file_exists($downloadsPath)) {
            mkdir($downloadsPath, 0755, true);
        }
        
        // Garantir permissões corretas
        if (file_exists($downloadsPath)) {
            chmod($downloadsPath, 0755);
        }

        // Limpar ZIPs antigos (mais de 1 hora)
        $this->cleanOldZips($downloadsPath);

        $zip = new ZipArchive();
        $zipPath = 'private/downloads/' . $zipName . '_' . time() . '.zip';
        $fullZipPath = storage_path('app/' . $zipPath);

        Log::info('Criando ZIP simples', ['path' => $fullZipPath, 'files_count' => $files->count()]);

        if ($zip->open($fullZipPath, ZipArchive::CREATE) !== TRUE) {
            throw new \Exception('Não foi possível criar o arquivo ZIP em: ' . $fullZipPath);
        }

        $addedFiles = 0;
        foreach ($files as $file) {
            $filePath = storage_path('app/' . $file->path);
            
            if (file_exists($filePath)) {
                // Usar addFile que não carrega na memória
                $result = $zip->addFile($filePath, $file->name);
                if ($result) {
                    $addedFiles++;
                    Log::info('Arquivo adicionado', ['file' => $file->name]);
                }
            }
        }

        $zip->close();
        Log::info('ZIP simples criado', ['path' => $zipPath, 'added_files' => $addedFiles]);

        return $zipPath;
    }
    
    /**
     * Limpar arquivos ZIP antigos (mais de 1 hora)
     */
    private function cleanOldZips($downloadsPath)
    {
        try {
            $files = glob($downloadsPath . '/*.zip');
            $oneHourAgo = time() - 3600; // 1 hora atrás
            
            foreach ($files as $file) {
                if (filemtime($file) < $oneHourAgo) {
                    unlink($file);
                    Log::info('ZIP antigo removido', ['file' => basename($file)]);
                }
            }
        } catch (\Exception $e) {
            Log::warning('Erro ao limpar ZIPs antigos', ['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Download direto de um único arquivo (sem ZIP), após validar pertença ao conteúdo.
     */
    private function downloadSingleAuthorizedFile($contentId, $contentType, int $fileId, string $requestType)
    {
        $allFiles = $this->getFilesByType($contentId, $contentType, 'all');
        $file = $allFiles->firstWhere('id', $fileId);
        if (!$file) {
            return response()->json(['success' => false, 'message' => 'Arquivo não encontrado ou sem permissão']);
        }

        $allowedTypes = match ($requestType) {
            'pdf', 'pdfs' => ['pdf'],
            'image', 'images' => ['image'],
            'video', 'videos' => ['video'],
            'all' => null,
            default => null,
        };
        if ($allowedTypes !== null && !in_array($file->type, $allowedTypes, true)) {
            return response()->json(['success' => false, 'message' => 'Tipo de download não corresponde ao arquivo']);
        }

        $path = storage_path('app/' . $file->path);
        if (!is_file($path)) {
            // Verificar se o arquivo existe no FTP
            try {
                if (Storage::disk('ftp')->exists($file->path)) {
                    $fileName = $file->name;
                    $mimeType = $file->mime_type ?? 'application/octet-stream';

                    return response()->streamDownload(function() use ($file) {
                        $stream = Storage::disk('ftp')->readStream($file->path);
                        if ($stream) {
                            fpassthru($stream);
                            if (is_resource($stream)) {
                                fclose($stream);
                            }
                        }
                    }, $fileName, [
                        'Content-Type' => $mimeType,
                    ]);
                }
            } catch (\Throwable $e) {
                Log::error("Erro ao baixar arquivo do FTP no DownloadController: " . $e->getMessage());
            }

            return response()->json(['success' => false, 'message' => 'Arquivo não encontrado no servidor']);
        }

        return response()->download($path, $file->name);
    }

    private function checkDownloadPermissions($contentId, $contentType)
    {
        $user = auth()->user();
        $activeProfile = $this->profileService->getActiveProfile();

        if (!$activeProfile) {
            return false;
        }
        
        $activeProfileId = $activeProfile->id;

        switch ($contentType) {
            case 'product':
                $product = Product::find($contentId);
                return $product && $product->canBeDownloadedBy($user, $activeProfileId);
                
            case 'training':
                $training = Training::find($contentId);
                return $training && $training->canBeDownloadedBy($user, $activeProfileId);
                
            case 'library':
                $library = Library::find($contentId);
                return $library && $library->canBeDownloadedBy($user, $activeProfileId);
                
            case 'marketing':
                $campaign = Campaign::find($contentId);
                if (!$campaign) return false;

                // Admin (ID 1) and Franqueado (ID 2) can download marketing
                if (!in_array($activeProfileId, [1, 2])) return false;
                
                // Admin can download everything
                if ($activeProfileId == 1) return true;
                
                // Franqueado can download campaigns not exclusive to representatives
                if ($activeProfileId == 2) {
                    return true;
                }
                
                return false;
                
            case 'news':
                $news = News::find($contentId);
                return $news && $news->canBeDownloadedBy($user, $activeProfileId);
                
            case 'media':
                $media = Media::find($contentId);
                return $media && $media->canBeDownloadedBy($user, $activeProfileId);
                
            default:
                return false;
        }
    }

}
