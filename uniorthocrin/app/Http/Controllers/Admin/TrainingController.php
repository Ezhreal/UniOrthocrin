<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileValidationRequest;
use App\Models\Training;
use App\Models\TrainingCategory;
use App\Models\UserType;
use App\Models\File;
use App\Models\TrainingPermission;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TrainingController extends Controller
{
    public function index(Request $request)
    {
        $query = Training::with(['category', 'videos', 'files']);

        if ($search = $request->get('search')) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }

        if ($category = $request->get('category')) {
            $query->where('training_category_id', $category);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $trainings = $query->orderBy('name')->paginate(10);
        $categories = TrainingCategory::orderBy('name')->get();

        return view('admin.training.index', compact('trainings', 'categories'));
    }

    public function create()
    {
        $categories = TrainingCategory::orderBy('name')->get();
        $userTypes = UserType::orderBy('name')->get();

        return view('admin.training.create', compact('categories', 'userTypes'));
    }

    public function store(Request $request)
    {
        // Validações básicas + validações específicas de arquivo
        $validationRules = array_merge([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'training_category_id' => 'required|exists:training_categories,id',
            'status' => 'required|in:active,inactive',
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:10240',
            'permissions' => 'nullable|array',
            'permissions.*.user_type_id' => 'required|exists:user_types,id',
            'permissions.*.can_view' => 'boolean',
            'permissions.*.can_download' => 'boolean',
        ], FileValidationRequest::getTrainingValidationRules());

        $request->validate($validationRules, (new FileValidationRequest())->messages());

        $training = Training::create($request->only([
            'name', 'description', 'training_category_id', 'status'
        ]));

        if ($request->hasFile('thumbnail')) {
            $thumb = $request->file('thumbnail');
            $thumbPath = $thumb->store('private/training/' . $training->id . '/thumb', 'private');
            $training->thumbnail_path = $thumbPath;
            $training->save();
        }

        $publishOneDrive = $request->boolean('publish_onedrive');
        // Handle video uploads
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $videoFile) {
                $path = $videoFile->store('private/training/' . $training->id, 'private');
                $training->videos()->create([
                    'name' => $videoFile->getClientOriginalName(),
                    'path' => $path,
                    'disk' => 'private',
                    'mime_type' => $videoFile->getMimeType(),
                    'size' => $videoFile->getSize(),
                    'file_type' => 'video',
                ]);
                // OneDrive (assíncrono)
                if ($publishOneDrive && $path) {
                    $localPath = storage_path('app/' . $path);
                    $remotePath = 'Training/' . $training->id . '/' . $videoFile->getClientOriginalName();
                    \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath)->onQueue('uploads');
                }
            }
        }

        // Handle PDF uploads
        if ($request->hasFile('pdfs')) {
            foreach ($request->file('pdfs') as $pdfFile) {
                $path = $pdfFile->store('private/training/' . $training->id, 'private');
                $training->files()->create([
                    'name' => $pdfFile->getClientOriginalName(),
                    'path' => $path,
                    'disk' => 'private',
                    'mime_type' => $pdfFile->getMimeType(),
                    'size' => $pdfFile->getSize(),
                    'file_type' => 'pdf',
                ]);
                // OneDrive (assíncrono)
                if ($publishOneDrive && $path) {
                    $localPath = storage_path('app/' . $path);
                    $remotePath = 'Training/' . $training->id . '/' . $pdfFile->getClientOriginalName();
                    \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath)->onQueue('uploads');
                }
            }
        }

        // Handle permissions
        if ($request->has('permissions')) {
            foreach ($request->permissions as $permission) {
                TrainingPermission::create([
                    'training_id' => $training->id,
                    'user_type_id' => $permission['user_type_id'],
                    'can_view' => $permission['can_view'] ?? false,
                    'can_download' => $permission['can_download'] ?? false,
                ]);
            }
        }

        // Criar notificação automática para usuários com permissão
        NotificationService::notifyNewTraining($training->id, $training->name);

        return redirect()->route('admin.training.index')->with('success', 'Treinamento criado com sucesso!');
    }

    public function show(Training $training)
    {
        $training->load(['category', 'videos', 'files', 'permissions.userType']);
        $userTypes = UserType::orderBy('name')->get();
        return view('admin.training.show', compact('training', 'userTypes'));
    }

    public function edit(Training $training)
    {
        $categories = TrainingCategory::orderBy('name')->get();
        $userTypes = UserType::orderBy('name')->get();
        $training->load(['videos', 'files', 'permissions.userType']);

        return view('admin.training.edit', compact('training', 'categories', 'userTypes'));
    }

    public function update(Request $request, Training $training)
    {
        // Validações básicas + validações específicas de arquivo
        $validationRules = array_merge([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'training_category_id' => 'required|exists:training_categories,id',
            'status' => 'required|in:active,inactive',
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:10240',
            'permissions' => 'nullable|array',
            'permissions.*.user_type_id' => 'required|exists:user_types,id',
            'permissions.*.can_view' => 'boolean',
            'permissions.*.can_download' => 'boolean',
        ], FileValidationRequest::getTrainingValidationRules());

        $request->validate($validationRules, (new FileValidationRequest())->messages());

        DB::beginTransaction();
        try {
            // Atualizar training
            $training->update($request->only([
                'name', 'description', 'training_category_id', 'status'
            ]));

        if ($request->hasFile('thumbnail')) {
            $thumb = $request->file('thumbnail');
            $thumbPath = $thumb->store('private/training/' . $training->id . '/thumb', 'private');
            $training->thumbnail_path = $thumbPath;
            $training->save();
        }

        // Handle new video uploads
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $videoFile) {
                $path = $videoFile->store('private/training/' . $training->id, 'private');
                $training->videos()->create([
                    'name' => $videoFile->getClientOriginalName(),
                    'path' => $path,
                    'disk' => 'private',
                    'mime_type' => $videoFile->getMimeType(),
                    'size' => $videoFile->getSize(),
                    'file_type' => 'video',
                ]);
            }
        }

        // Handle new PDF uploads
        if ($request->hasFile('pdfs')) {
            foreach ($request->file('pdfs') as $pdfFile) {
                $path = $pdfFile->store('private/training/' . $training->id, 'private');
                $training->files()->create([
                    'name' => $pdfFile->getClientOriginalName(),
                    'path' => $path,
                    'disk' => 'private',
                    'mime_type' => $pdfFile->getMimeType(),
                    'size' => $pdfFile->getSize(),
                    'file_type' => 'pdf',
                ]);
            }
        }

        // Update permissions (simple sync for now, can be more complex)
        $training->permissions()->delete(); // Remove existing
        
        if ($request->has('permissions')) {
            foreach ($request->permissions as $permission) {
                TrainingPermission::create([
                    'training_id' => $training->id,
                    'user_type_id' => $permission['user_type_id'],
                    'can_view' => $permission['can_view'] ?? false,
                    'can_download' => $permission['can_download'] ?? false,
                ]);
            }
        }

            DB::commit();

            return redirect()->route('admin.training.index')->with('success', 'Treinamento atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Erro ao atualizar treinamento: ' . $e->getMessage());
        }
    }

    public function destroy(Training $training)
    {
        // Delete associated files from storage
        if ($training->files) {
            foreach ($training->files as $file) {
                if ($file->path && Storage::disk('private')->exists($file->path)) {
                    Storage::disk('private')->delete($file->path);
                }
                $file->delete();
            }
        }
        $training->delete();
        return redirect()->route('admin.training.index')->with('success', 'Treinamento deletado com sucesso!');
    }

    public function uploadFiles(Request $request, Training $training)
    {
        $request->validate([
            'videos' => 'nullable|array',
            'videos.*' => 'mimetypes:video/mp4,video/avi,video/mov,video/wmv|max:102400',
            'pdfs' => 'nullable|array',
            'pdfs.*' => 'mimetypes:application/pdf|max:10240',
        ]);

        $uploadedFiles = [];

        // Handle video uploads
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $videoFile) {
                $path = $videoFile->store('private/training/' . $training->id, 'private');
                
                // Criar o arquivo
                $fileRecord = File::create([
                    'name' => $videoFile->getClientOriginalName(),
                    'path' => $path,
                    'type' => 'video',
                    'extension' => $this->getFileExtension($videoFile->getClientOriginalName()),
                    'mime_type' => $videoFile->getMimeType(),
                    'size' => $videoFile->getSize(),
                    'order' => 0,
                ]);
                
                // Associar o arquivo ao training
                $training->files()->attach($fileRecord->id, [
                    'file_type' => 'video',
                    'sort_order' => 0,
                    'is_primary' => true
                ]);
                
                $uploadedFiles[] = $fileRecord;
            }
        }

        // Handle PDF uploads
        if ($request->hasFile('pdfs')) {
            foreach ($request->file('pdfs') as $pdfFile) {
                $path = $pdfFile->store('private/training/' . $training->id, 'private');
                
                // Criar o arquivo
                $fileRecord = File::create([
                    'name' => $pdfFile->getClientOriginalName(),
                    'path' => $path,
                    'type' => 'pdf',
                    'extension' => $this->getFileExtension($pdfFile->getClientOriginalName()),
                    'mime_type' => $pdfFile->getMimeType(),
                    'size' => $pdfFile->getSize(),
                    'order' => 0,
                ]);
                
                // Associar o arquivo ao training
                $training->files()->attach($fileRecord->id, [
                    'file_type' => 'pdf',
                    'sort_order' => 0,
                    'is_primary' => true
                ]);
                
                $uploadedFiles[] = $fileRecord;
            }
        }

        return response()->json([
            'success' => true,
            'files' => $uploadedFiles
        ]);
    }

    public function deleteFile(Training $training, $fileId)
    {
        $file = $training->files()->findOrFail($fileId);
        if ($file->path && Storage::disk('private')->exists($file->path)) {
            Storage::disk('private')->delete($file->path);
        }
        $file->delete();

        return response()->json(['success' => true]);
    }

    public function deleteVideo(Training $training, $videoId)
    {
        $video = $training->videos()->findOrFail($videoId);
        if ($video->path && Storage::disk('private')->exists($video->path)) {
            Storage::disk('private')->delete($video->path);
        }
        $video->delete();

        return response()->json(['success' => true]);
    }

    public function updatePermissions(Request $request, Training $training)
    {
        $request->validate([
            'permissions' => 'required|array',
            'permissions.*.user_type_id' => 'required|exists:user_types,id',
            'permissions.*.can_view' => 'boolean',
            'permissions.*.can_download' => 'boolean',
        ]);

        $training->permissions()->delete(); // Remove existing
        foreach ($request->input('permissions') as $permissionData) {
            $training->permissions()->create([
                'user_type_id' => $permissionData['user_type_id'],
                'can_view' => $permissionData['can_view'] ?? false,
                'can_download' => $permissionData['can_download'] ?? false,
            ]);
        }

        return response()->json(['success' => true]);
    }
    
    private function getFileExtension($filename)
    {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }
}
