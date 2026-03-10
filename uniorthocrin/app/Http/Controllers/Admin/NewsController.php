<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileValidationRequest;
use App\Models\File;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\UserType;
use App\Models\NewsPermission;
use App\Models\OneDriveSync;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $query = News::with(['category', 'image']);

        if ($search = $request->get('search')) {
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%');
        }

        if ($category = $request->get('category')) {
            $query->where('news_category_id', $category);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $news = $query->orderBy('created_at', 'desc')->paginate(10);
        $categories = NewsCategory::orderBy('name')->get();

        return view('admin.news.index', compact('news', 'categories'));
    }

    public function create()
    {
        $categories = NewsCategory::orderBy('name')->get();
        $userTypes = UserType::orderBy('name')->get();

        return view('admin.news.create', compact('categories', 'userTypes'));
    }

    public function store(Request $request)
    {
        // Validações básicas + validações específicas de arquivo
        $validationRules = array_merge([
            'title' => 'required|string|max:255',
            'news_category_id' => 'required|exists:news_categories,id',
            'status' => 'required|in:published,draft',
            'permissions' => 'nullable|array',
            'permissions.*.user_type_id' => 'required|exists:user_types,id',
            'permissions.*.can_view' => 'boolean',
            'permissions.*.can_download' => 'boolean',
        ], FileValidationRequest::getNewsValidationRules());

        $request->validate($validationRules, (new FileValidationRequest())->messages());

        $news = News::create([
            'author_id' => auth()->id(),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'news_category_id' => $request->input('news_category_id'),
            'status' => $request->input('status'),
        ]);

        $publishOneDrive = $request->boolean('publish_onedrive');
        // Handle image upload
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $path = $imageFile->store('private/news/' . $news->id, 'private');

            // Criar o arquivo na tabela files
            $fileRecord = File::create([
                'name' => $imageFile->getClientOriginalName(),
                'path' => $path,
                'type' => 'image',
                'extension' => $this->getFileExtension($imageFile->getClientOriginalName()),
                'mime_type' => $imageFile->getMimeType(),
                'size' => $imageFile->getSize(),
                'order' => 0,
            ]);

            // Associar o arquivo principal à notícia
            $news->news_file_id = $fileRecord->id;
            $news->save();

            if ($publishOneDrive && $path) {
                $localPath = storage_path('app/' . $path);
                $remotePath = 'News/' . $news->id . '/image-' . $fileRecord->id . '.' . $this->getFileExtension($imageFile->getClientOriginalName());
                $sync = $this->createOneDriveSync($news, $path, $remotePath);
                \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath, $sync->id);
            }
        }

        // Handle permissions
        if ($request->has('permissions')) {
            foreach ($request->permissions as $permission) {
                NewsPermission::create([
                    'news_id' => $news->id,
                    'user_type_id' => $permission['user_type_id'],
                    'can_view' => isset($permission['can_view']) ? (bool)$permission['can_view'] : false,
                ]);
            }
        }

        // Administrador sempre tem permissão total
        NewsPermission::updateOrCreate([
            'news_id' => $news->id,
            'user_type_id' => 1, // ID do Administrador
        ], [
            'can_view' => true,
        ]);

        // Criar notificação automática para usuários com permissão
        NotificationService::notifyNewNews($news->id, $news->title);

        return redirect()->route('admin.news.index')->with('success', 'Notícia criada com sucesso!');
    }

    public function show(News $news)
    {
        $news->load(['category', 'image', 'permissions.userType']);
        $userTypes = UserType::orderBy('name')->get();
        return view('admin.news.show', compact('news', 'userTypes'));
    }

    public function edit(News $news)
    {
        $categories = NewsCategory::orderBy('name')->get();
        $userTypes = UserType::orderBy('name')->get();
        $news->load(['image', 'permissions.userType']);

        return view('admin.news.edit', compact('news', 'categories', 'userTypes'));
    }

    public function update(Request $request, News $news)
    {
        // Validações básicas + validações específicas de arquivo
        $validationRules = array_merge([
            'title' => 'required|string|max:255',
            'news_category_id' => 'required|exists:news_categories,id',
            'status' => 'required|in:published,draft',
            'permissions' => 'nullable|array',
            'permissions.*.user_type_id' => 'required|exists:user_types,id',
            'permissions.*.can_view' => 'boolean',
            'permissions.*.can_download' => 'boolean',
        ], FileValidationRequest::getNewsValidationRules());

        $request->validate($validationRules, (new FileValidationRequest())->messages());

        DB::beginTransaction();
        try {
            // Atualizar news
            $news->update($request->only([
                'title', 'news_category_id', 'status'
            ]));

        $publishOneDrive = $request->boolean('publish_onedrive');
        // Handle new image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($news->image && $news->image->path && Storage::disk('private')->exists($news->image->path)) {
                Storage::disk('private')->delete($news->image->path);
                $news->image->delete();
            }

            $imageFile = $request->file('image');
            $path = $imageFile->store('private/news/' . $news->id, 'private');

            // Criar o arquivo na tabela files e associar como principal
            $fileRecord = File::create([
                'name' => $imageFile->getClientOriginalName(),
                'path' => $path,
                'type' => 'image',
                'extension' => $this->getFileExtension($imageFile->getClientOriginalName()),
                'mime_type' => $imageFile->getMimeType(),
                'size' => $imageFile->getSize(),
                'order' => 0,
            ]);

            $news->news_file_id = $fileRecord->id;
            $news->save();

            if ($publishOneDrive && $path) {
                $localPath = storage_path('app/' . $path);
                $remotePath = 'News/' . $news->id . '/image-' . $news->id . '.' . $this->getFileExtension($imageFile->getClientOriginalName());
                $sync = $this->createOneDriveSync($news, $path, $remotePath);
                \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath, $sync->id);
            }
        }

        // Update permissions (simple sync for now, can be more complex)
        $news->permissions()->where('user_type_id', '!=', 1)->delete(); // Remove existing (exceto admin)
        if ($request->has('permissions')) {
            foreach ($request->permissions as $permission) {
                try {
                    NewsPermission::create([
                        'news_id' => $news->id,
                        'user_type_id' => $permission['user_type_id'],
                        'can_view' => isset($permission['can_view']) ? (bool)$permission['can_view'] : false,
                    ]);
                } catch (\Exception $e) {
                    // Ignorar erro de duplicata (admin já existe)
                    if (!str_contains($e->getMessage(), 'Duplicate entry')) {
                        throw $e;
                    }
                }
            }
        }

        // Administrador sempre tem permissão total
        NewsPermission::updateOrCreate([
            'news_id' => $news->id,
            'user_type_id' => 1, // ID do Administrador
        ], [
            'can_view' => true,
        ]);

            DB::commit();

            return redirect()->route('admin.news.index')->with('success', 'Notícia atualizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Erro ao atualizar notícia: ' . $e->getMessage());
        }
    }

    public function destroy(News $news)
    {
        // Delete associated image from storage
        if ($news->image && $news->image->path && Storage::disk('private')->exists($news->image->path)) {
            Storage::disk('private')->delete($news->image->path);
            $news->image->delete();
        }
        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'Notícia deletada com sucesso!');
    }

    public function uploadImage(Request $request, News $news)
    {
        $request->validate([
            'image' => 'required|image|max:10240', // 10MB
        ]);

        // Delete old image if exists
        if ($news->image && $news->image->path && Storage::disk('private')->exists($news->image->path)) {
            Storage::disk('private')->delete($news->image->path);
            $news->image->delete();
        }

        $imageFile = $request->file('image');
        $path = $imageFile->store('private/news/' . $news->id, 'private');
        $uploadedImage = File::create([
            'name' => $imageFile->getClientOriginalName(),
            'path' => $path,
            'type' => 'image',
            'extension' => $this->getFileExtension($imageFile->getClientOriginalName()),
            'mime_type' => $imageFile->getMimeType(),
            'size' => $imageFile->getSize(),
            'order' => 0,
        ]);

        $news->news_file_id = $uploadedImage->id;
        $news->save();

        return response()->json([
            'success' => true,
            'image' => $uploadedImage
        ]);
    }

    public function deleteImage(News $news)
    {
        if ($news->image && $news->image->path && Storage::disk('private')->exists($news->image->path)) {
            Storage::disk('private')->delete($news->image->path);
            $news->image->delete();
        }

        return response()->json(['success' => true]);
    }

    public function updatePermissions(Request $request, News $news)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*.user_type_id' => 'required|exists:user_types,id',
            'permissions.*.can_view' => 'boolean',
            'permissions.*.can_download' => 'boolean',
        ]);

        $news->permissions()->delete(); // Remove existing
        foreach ($request->input('permissions') as $permissionData) {
            $news->permissions()->create([
                'user_type_id' => $permissionData['user_type_id'],
                'can_view' => $permissionData['can_view'] ?? false,
                'can_download' => $permissionData['can_download'] ?? false,
            ]);
        }

        return response()->json(['success' => true]);
    }

    private function createOneDriveSync($news, $filePath, $remotePath)
    {
        $sync = OneDriveSync::create([
            'syncable_type' => get_class($news),
            'syncable_id' => $news->id,
            'file_path' => $filePath,
            'remote_path' => $remotePath,
            'status' => 'pending'
        ]);

        return $sync;
    }

    public function syncToOneDrive(News $news)
    {
        try {
            $syncedCount = 0;
            
            // Sincronizar imagem da notícia
            if ($news->image && $news->image->path) {
                // Verificar se já existe sync para este arquivo
                $existingSync = OneDriveSync::where('syncable_type', get_class($news))
                    ->where('syncable_id', $news->id)
                    ->where('file_path', $news->image->path)
                    ->first();
                
                if (!$existingSync) {
                    // Criar novo sync
                    $localPath = storage_path('app/' . $news->image->path);
                    $remotePath = 'News/' . $news->id . '/' . $news->image->name;
                    
                    $sync = $this->createOneDriveSync($news, $news->image->path, $remotePath);
                    \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath, $sync->id);
                    $syncedCount++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "Sincronização iniciada para {$syncedCount} arquivo(s).",
                'synced_count' => $syncedCount
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao iniciar sincronização: ' . $e->getMessage()
            ], 500);
        }
    }

    public function retryOneDriveSync(News $news)
    {
        try {
            $retryCount = 0;
            
            // Reenviar arquivos que falharam
            $failedSyncs = OneDriveSync::where('syncable_type', get_class($news))
                ->where('syncable_id', $news->id)
                ->where('status', 'failed')
                ->get();
            
            foreach ($failedSyncs as $sync) {
                $localPath = storage_path('app/' . $sync->file_path);
                
                if (file_exists($localPath)) {
                    $sync->update(['status' => 'pending']);
                    \App\Jobs\UploadToOneDrive::dispatch($localPath, $sync->remote_path, $sync->id);
                    $retryCount++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "Tentativa de reenvio iniciada para {$retryCount} arquivo(s).",
                'retry_count' => $retryCount
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao tentar reenviar: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getFileType($mimeType)
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        } elseif (str_starts_with($mimeType, 'video/')) {
            return 'video';
        } elseif (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        } elseif (in_array($mimeType, ['application/pdf'])) {
            return 'pdf';
        } else {
            return 'pdf'; // Default para outros tipos de arquivo
        }
    }
    
    private function getFileExtension($filename)
    {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }
}
