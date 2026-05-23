<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileValidationRequest;
use App\Models\Media;
use App\Models\MediaCategory;
use App\Models\UserType;
use App\Models\File;
use App\Models\MediaPermission;
use App\Models\OneDriveSync;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::with(['category', 'files']);

        if ($search = $request->get('search')) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }

        if ($category = $request->get('category')) {
            $query->where('media_category_id', $category);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $medias = $query->orderBy('name')->paginate(10);
        $categories = MediaCategory::orderBy('name')->get();

        return view('admin.media.index', compact('medias', 'categories'));
    }

    public function create()
    {
        $categories = MediaCategory::orderBy('name')->get();
        $userTypes = UserType::orderBy('name')->get();

        return view('admin.media.create', compact('categories', 'userTypes'));
    }

    public function store(Request $request)
    {
        // Validações básicas + validações específicas de arquivo
        $validationRules = array_merge([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'media_category_id' => 'required|exists:media_categories,id',
            'status' => 'required|in:active,inactive',
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:10240',
            'permissions' => 'nullable|array',
            'permissions.*.user_type_id' => 'required|exists:user_types,id',
            'permissions.*.can_view' => 'boolean',
            'permissions.*.can_download' => 'boolean',
        ], FileValidationRequest::getMediaValidationRules());

        $request->validate($validationRules, (new FileValidationRequest())->messages());

        $media = Media::create($request->only([
            'name', 'description', 'media_category_id', 'status'
        ]));

        if ($request->hasFile('thumbnail')) {
            $thumb = $request->file('thumbnail');
            $thumbPath = $thumb->store('private/media/' . $media->id . '/thumb', 'private');
            $media->thumbnail_path = $thumbPath;
            $media->save();
        }

        // Handle file uploads
        $publishOneDrive = $request->boolean('publish_onedrive');
        
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('private/media/' . $media->id, 'private');
                
                // Criar o arquivo na tabela files
                $fileRecord = File::create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $this->getFileType($file->getMimeType()),
                    'extension' => $this->getFileExtension($file->getClientOriginalName()),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'order' => 0,
                ]);
                
                // Associar o arquivo à media
                $media->files()->attach($fileRecord->id, [
                    'file_type' => $this->getFileType($file->getMimeType()),
                    'sort_order' => 0,
                    'is_primary' => true
                ]);
                
                // Disparar envio ao OneDrive (síncrono) se marcado
                if ($publishOneDrive && $path) {
                    $localPath = storage_path('app/' . $path);
                    $remotePath = 'Media/' . $media->id . '/' . $fileRecord->id . '-' . $this->getFileType($file->getMimeType()) . '.' . $this->getFileExtension($file->getClientOriginalName());
                    $sync = $this->createOneDriveSync($media, $path, $remotePath);
                    \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath, $sync->id);
                }
            }
        }

        // Handle permissions
        if ($request->has('permissions')) {
            foreach ($request->permissions as $permission) {
                MediaPermission::create([
                    'media_id' => $media->id,
                    'user_type_id' => $permission['user_type_id'],
                    'can_view' => isset($permission['can_view']) ? (bool)$permission['can_view'] : false,
                    'can_download' => isset($permission['can_download']) ? (bool)$permission['can_download'] : false,
                ]);
            }
        }

        // Administrador sempre tem permissão total
        MediaPermission::updateOrCreate([
            'media_id' => $media->id,
            'user_type_id' => 1, // ID do Administrador
        ], [
            'can_view' => true,
            'can_download' => true,
        ]);

        // Criar notificação automática para usuários com permissão
        NotificationService::notifyNewMedia($media->id, $media->name);

        return redirect()->route('admin.media.index')->with('success', 'Item de mídia criado com sucesso!');
    }

    public function show(Media $media)
    {
        $media->load(['category', 'files', 'permissions.userType']);
        $userTypes = UserType::orderBy('name')->get();
        return view('admin.media.show', compact('media', 'userTypes'));
    }

    public function edit(Media $media)
    {
        $categories = MediaCategory::orderBy('name')->get();
        $userTypes = UserType::orderBy('name')->get();
        $media->load(['files', 'permissions.userType']);

        return view('admin.media.edit', compact('media', 'categories', 'userTypes'));
    }

    public function update(Request $request, Media $media)
    {
        // Validações básicas + validações específicas de arquivo
        $validationRules = array_merge([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'media_category_id' => 'required|exists:media_categories,id',
            'status' => 'required|in:active,inactive',
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:10240',
            'permissions' => 'nullable|array',
            'permissions.*.user_type_id' => 'required|exists:user_types,id',
            'permissions.*.can_view' => 'boolean',
            'permissions.*.can_download' => 'boolean',
        ], FileValidationRequest::getMediaValidationRules());

        $request->validate($validationRules, (new FileValidationRequest())->messages());

        DB::beginTransaction();
        try {
            // Atualizar media
            $media->update($request->only([
                'name', 'description', 'media_category_id', 'status'
            ]));

        $publishOneDrive = $request->boolean('publish_onedrive');

        if ($request->hasFile('thumbnail')) {
            $thumb = $request->file('thumbnail');
            $thumbPath = $thumb->store('private/media/' . $media->id . '/thumb', 'private');
            $media->thumbnail_path = $thumbPath;
            $media->save();
            
            // OneDrive (assíncrono)
            if ($publishOneDrive && $thumbPath) {
                $localPath = storage_path('app/' . $thumbPath);
                $remotePath = 'Media/' . $media->id . '/thumb-' . $media->id . '.' . $this->getFileExtension($thumb->getClientOriginalName());
                $sync = $this->createOneDriveSync($media, $thumbPath, $remotePath);
                \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath, $sync->id);
            }
        }

        // Handle new file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('private/media/' . $media->id, 'private');
                
                // Criar o arquivo na tabela files
                $fileRecord = File::create([
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $this->getFileType($file->getMimeType()),
                    'extension' => $this->getFileExtension($file->getClientOriginalName()),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'order' => 0,
                ]);
                
                // Associar o arquivo à media
                $media->files()->attach($fileRecord->id, [
                    'file_type' => $this->getFileType($file->getMimeType()),
                    'sort_order' => 0,
                    'is_primary' => true
                ]);
                
                // OneDrive (assíncrono)
                if ($publishOneDrive && $path) {
                    $localPath = storage_path('app/' . $path);
                    $remotePath = 'Media/' . $media->id . '/' . $fileRecord->id . '-' . $this->getFileType($file->getMimeType()) . '.' . $this->getFileExtension($file->getClientOriginalName());
                    $sync = $this->createOneDriveSync($media, $path, $remotePath);
                    \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath, $sync->id);
                }
            }
        }

        // Update permissions (simple sync for now, can be more complex)
        $media->permissions()->where('user_type_id', '!=', 1)->delete(); // Remove existing (exceto admin)
        
        if ($request->has('permissions')) {
            foreach ($request->permissions as $permission) {
                try {
                    MediaPermission::create([
                        'media_id' => $media->id,
                        'user_type_id' => $permission['user_type_id'],
                        'can_view' => isset($permission['can_view']) ? (bool)$permission['can_view'] : false,
                        'can_download' => isset($permission['can_download']) ? (bool)$permission['can_download'] : false,
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
        MediaPermission::updateOrCreate([
            'media_id' => $media->id,
            'user_type_id' => 1, // ID do Administrador
        ], [
            'can_view' => true,
            'can_download' => true,
        ]);

            DB::commit();

            return redirect()->route('admin.media.index')->with('success', 'Item de mídia atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Erro ao atualizar item de mídia: ' . $e->getMessage());
        }
    }

    public function destroy(Media $media)
    {
        // Delete associated files from storage
        if ($media->files) {
            foreach ($media->files as $file) {
                if ($file->path && Storage::disk('private')->exists($file->path)) {
                    Storage::disk('private')->delete($file->path);
                }
                $file->delete();
            }
        }
        $media->delete();
        return redirect()->route('admin.media.index')->with('success', 'Item de mídia deletado com sucesso!');
    }

    public function uploadFiles(Request $request, Media $media)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|max:512000', // 500MB
        ]);

        $uploadedFiles = [];
        $publishOneDrive = $request->boolean('publish_onedrive');
        
        foreach ($request->file('files') as $file) {
            $path = $file->store('private/media/' . $media->id, 'private');
            // Criar o arquivo
            $fileRecord = File::create([
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'type' => $this->getFileType($file->getMimeType()),
                'extension' => $this->getFileExtension($file->getClientOriginalName()),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'order' => 0,
            ]);
            
            // Associar o arquivo à media
            $media->files()->attach($fileRecord->id, [
                'file_type' => $this->getFileType($file->getMimeType()),
                'sort_order' => 0,
                'is_primary' => true
            ]);
            
            $uploadedFile = $fileRecord;
            // Disparar envio ao OneDrive (assíncrono) se marcado
            if ($publishOneDrive && $path) {
                $localPath = storage_path('app/' . $path);
                $remotePath = 'Media/' . $media->id . '/' . $file->getClientOriginalName();
                $sync = $this->createOneDriveSync($media, $path, $remotePath);
                \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath, $sync->id);
            }
            $uploadedFiles[] = $uploadedFile;
        }

        return response()->json([
            'success' => true,
            'files' => $uploadedFiles
        ]);
    }

    public function deleteFile(Media $media, $fileId)
    {
        $file = $media->files()->findOrFail($fileId);
        if ($file->path && Storage::disk('private')->exists($file->path)) {
            Storage::disk('private')->delete($file->path);
        }
        $file->delete();

        return response()->json(['success' => true]);
    }

    public function updatePermissions(Request $request, Media $media)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*.user_type_id' => 'required|exists:user_types,id',
            'permissions.*.can_view' => 'boolean',
            'permissions.*.can_download' => 'boolean',
        ]);

        $media->permissions()->delete(); // Remove existing
        foreach ($request->input('permissions') as $permissionData) {
            $media->permissions()->create([
                'user_type_id' => $permissionData['user_type_id'],
                'can_view' => $permissionData['can_view'] ?? false,
                'can_download' => $permissionData['can_download'] ?? false,
            ]);
        }

        return response()->json(['success' => true]);
    }

    private function getFileType($mimeType)
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        } elseif (str_starts_with($mimeType, 'video/')) {
            return 'video';
        } elseif (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        } elseif ($mimeType === 'application/pdf') {
            return 'pdf';
        } else {
            return 'pdf';
        }
    }
    
    private function getFileExtension($filename)
    {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }

    private function createOneDriveSync($media, $filePath, $remotePath)
    {
        $sync = OneDriveSync::create([
            'syncable_type' => get_class($media),
            'syncable_id' => $media->id,
            'file_path' => $filePath,
            'remote_path' => $remotePath,
            'status' => 'pending'
        ]);

        return $sync;
    }

    public function syncToOneDrive(Media $media)
    {
        try {
            $syncedCount = 0;
            
            // Sincronizar arquivos que ainda não foram enviados
            foreach ($media->files as $file) {
                // Verificar se já existe sync para este arquivo
                $existingSync = OneDriveSync::where('syncable_type', get_class($media))
                    ->where('syncable_id', $media->id)
                    ->where('file_path', $file->path)
                    ->first();
                
                if (!$existingSync) {
                    // Criar novo sync
                    $localPath = storage_path('app/' . $file->path);
                    $remotePath = 'Media/' . $media->id . '/' . $file->name;
                    
                    $sync = $this->createOneDriveSync($media, $file->path, $remotePath);
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

    public function retryOneDriveSync(Media $media)
    {
        try {
            $retryCount = 0;
            
            // Reenviar arquivos que falharam
            $failedSyncs = OneDriveSync::where('syncable_type', get_class($media))
                ->where('syncable_id', $media->id)
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
}
