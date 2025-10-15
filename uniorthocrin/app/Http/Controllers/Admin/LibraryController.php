<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileValidationRequest;
use App\Models\Library;
use App\Models\LibraryCategory;
use App\Models\UserType;
use App\Models\File;
use App\Models\LibraryPermission;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LibraryController extends Controller
{
    public function index(Request $request)
    {
        $query = Library::with(['category', 'files']);

        if ($search = $request->get('search')) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }

        if ($category = $request->get('category')) {
            $query->where('library_category_id', $category);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $libraries = $query->orderBy('name')->paginate(10);
        $categories = LibraryCategory::orderBy('name')->get();

        return view('admin.library.index', compact('libraries', 'categories'));
    }

    public function create()
    {
        $categories = LibraryCategory::orderBy('name')->get();
        $userTypes = UserType::orderBy('name')->get();

        return view('admin.library.create', compact('categories', 'userTypes'));
    }

    public function store(Request $request)
    {
        // Validações básicas + validações específicas de arquivo
        $validationRules = array_merge([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'library_category_id' => 'required|exists:library_categories,id',
            'status' => 'required|in:active,inactive',
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:10240',
            'permissions' => 'nullable|array',
            'permissions.*.user_type_id' => 'required|exists:user_types,id',
            'permissions.*.can_view' => 'boolean',
            'permissions.*.can_download' => 'boolean',
        ], FileValidationRequest::getLibraryValidationRules());

        $request->validate($validationRules, (new FileValidationRequest())->messages());

        $library = Library::create($request->only([
            'name', 'description', 'library_category_id', 'status'
        ]));

        if ($request->hasFile('thumbnail')) {
            $thumb = $request->file('thumbnail');
            $thumbPath = $thumb->store('private/library/' . $library->id . '/thumb', 'private');
            $library->thumbnail_path = $thumbPath;
            $library->save();
        }

        // Handle file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('private/library/' . $library->id, 'private');
                
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
                
                // Associar o arquivo à library
                $library->files()->attach($fileRecord->id, [
                    'file_type' => $this->getFileType($file->getMimeType()),
                    'sort_order' => 0,
                    'is_primary' => true
                ]);
            }
        }

        // Handle permissions
        if ($request->has('permissions')) {
            foreach ($request->permissions as $permission) {
                LibraryPermission::create([
                    'library_id' => $library->id,
                    'user_type_id' => $permission['user_type_id'],
                    'can_view' => $permission['can_view'] ?? false,
                    'can_download' => $permission['can_download'] ?? false,
                ]);
            }
        }

        // Criar notificação automática para usuários com permissão
        NotificationService::notifyNewLibrary($library->id, $library->name);

        return redirect()->route('admin.library.index')->with('success', 'Item da biblioteca criado com sucesso!');
    }

    public function show(Library $library)
    {
        $library->load(['category', 'files', 'permissions.userType']);
        $userTypes = UserType::orderBy('name')->get();
        return view('admin.library.show', compact('library', 'userTypes'));
    }

    public function edit(Library $library)
    {
        $categories = LibraryCategory::orderBy('name')->get();
        $userTypes = UserType::orderBy('name')->get();
        $library->load(['files', 'permissions.userType']);

        return view('admin.library.edit', compact('library', 'categories', 'userTypes'));
    }

    public function update(Request $request, Library $library)
    {
        // Validações básicas + validações específicas de arquivo
        $validationRules = array_merge([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'library_category_id' => 'required|exists:library_categories,id',
            'status' => 'required|in:active,inactive',
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:10240',
            'permissions' => 'nullable|array',
            'permissions.*.user_type_id' => 'required|exists:user_types,id',
            'permissions.*.can_view' => 'boolean',
            'permissions.*.can_download' => 'boolean',
        ], FileValidationRequest::getLibraryValidationRules());

        $request->validate($validationRules, (new FileValidationRequest())->messages());

        DB::beginTransaction();
        try {
            // Atualizar library
            $library->update($request->only([
                'name', 'description', 'library_category_id', 'status'
            ]));

        if ($request->hasFile('thumbnail')) {
            $thumb = $request->file('thumbnail');
            $thumbPath = $thumb->store('private/library/' . $library->id . '/thumb', 'private');
            $library->thumbnail_path = $thumbPath;
            $library->save();
        }

        // Handle new file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('private/library/' . $library->id, 'private');
                
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
                
                // Associar o arquivo à library
                $library->files()->attach($fileRecord->id, [
                    'file_type' => $this->getFileType($file->getMimeType()),
                    'sort_order' => 0,
                    'is_primary' => true
                ]);
            }
        }

        // Update permissions (simple sync for now, can be more complex)
        $library->permissions()->delete(); // Remove existing
        
        if ($request->has('permissions')) {
            foreach ($request->permissions as $permission) {
                LibraryPermission::create([
                    'library_id' => $library->id,
                    'user_type_id' => $permission['user_type_id'],
                    'can_view' => $permission['can_view'] ?? false,
                    'can_download' => $permission['can_download'] ?? false,
                ]);
            }
        }

            DB::commit();

            return redirect()->route('admin.library.index')->with('success', 'Item da biblioteca atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Erro ao atualizar item da biblioteca: ' . $e->getMessage());
        }
    }

    public function destroy(Library $library)
    {
        // Delete associated files from storage
        if ($library->files) {
            foreach ($library->files as $file) {
                if ($file->path && Storage::disk('private')->exists($file->path)) {
                    Storage::disk('private')->delete($file->path);
                }
                $file->delete();
            }
        }
        $library->delete();
        return redirect()->route('admin.library.index')->with('success', 'Item da biblioteca deletado com sucesso!');
    }

    public function uploadFiles(Request $request, Library $library)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'file|max:102400', // 100MB
        ]);

        $uploadedFiles = [];
        $publishOneDrive = $request->boolean('publish_onedrive');
        foreach ($request->file('files') as $file) {
            $path = $file->store('private/library/' . $library->id, 'private');
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
            
            // Associar o arquivo à library
            $library->files()->attach($fileRecord->id, [
                'file_type' => $this->getFileType($file->getMimeType()),
                'sort_order' => 0,
                'is_primary' => true
            ]);
            
            $uploadedFile = $fileRecord;
            // Disparar envio ao OneDrive (assíncrono) se marcado
            if ($publishOneDrive && $path) {
                $localPath = storage_path('app/' . $path);
                $remotePath = 'Library/' . $library->id . '/' . $file->getClientOriginalName();
                \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath)->onQueue('uploads');
            }
            $uploadedFiles[] = $uploadedFile;
        }

        return response()->json([
            'success' => true,
            'files' => $uploadedFiles
        ]);
    }

    public function deleteFile(Library $library, $fileId)
    {
        $file = $library->files()->findOrFail($fileId);
        if ($file->path && Storage::disk('private')->exists($file->path)) {
            Storage::disk('private')->delete($file->path);
        }
        $file->delete();

        return response()->json(['success' => true]);
    }

    public function updatePermissions(Request $request, Library $library)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*.user_type_id' => 'required|exists:user_types,id',
            'permissions.*.can_view' => 'boolean',
            'permissions.*.can_download' => 'boolean',
        ]);

        $library->permissions()->delete(); // Remove existing
        foreach ($request->input('permissions') as $permissionData) {
            $library->permissions()->create([
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
        } elseif (in_array($mimeType, ['application/pdf'])) {
            return 'pdf';
        } elseif (in_array($mimeType, [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ])) {
            return 'document';
        } elseif (in_array($mimeType, [
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ])) {
            return 'spreadsheet';
        } elseif (in_array($mimeType, [
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation'
        ])) {
            return 'presentation';
        } else {
            return 'pdf'; // Default para outros tipos de arquivo
        }
    }
    
    private function getFileExtension($filename)
    {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }
}
