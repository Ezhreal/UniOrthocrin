<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileValidationRequest;
use App\Models\News;
use App\Models\NewsCategory;
use App\Models\UserType;
use App\Models\NewsPermission;
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
            'content' => 'required|string',
            'news_category_id' => 'required|exists:news_categories,id',
            'status' => 'required|in:published,draft',
            'permissions' => 'nullable|array',
            'permissions.*.user_type_id' => 'required|exists:user_types,id',
            'permissions.*.can_view' => 'boolean',
            'permissions.*.can_download' => 'boolean',
        ], FileValidationRequest::getNewsValidationRules());

        $request->validate($validationRules, (new FileValidationRequest())->messages());

        $news = News::create($request->only([
            'title', 'content', 'news_category_id', 'status'
        ]));

        $publishOneDrive = $request->boolean('publish_onedrive');
        // Handle image upload
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $path = $imageFile->store('private/news/' . $news->id, 'private');
            $news->image()->create([
                'name' => $imageFile->getClientOriginalName(),
                'path' => $path,
                'disk' => 'private',
                'mime_type' => $imageFile->getMimeType(),
                'size' => $imageFile->getSize(),
                'file_type' => 'image',
            ]);

            if ($publishOneDrive && $path) {
                $localPath = storage_path('app/' . $path);
                $remotePath = 'News/' . $news->id . '/' . $imageFile->getClientOriginalName();
                \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath)->onQueue('uploads');
            }
        }

        // Handle permissions
        if ($request->has('permissions')) {
            foreach ($request->permissions as $permission) {
                NewsPermission::create([
                    'news_id' => $news->id,
                    'user_type_id' => $permission['user_type_id'],
                    'can_view' => $permission['can_view'] ?? false,
                ]);
            }
        }

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
            'content' => 'required|string',
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
                'title', 'content', 'news_category_id', 'status'
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
            $news->image()->create([
                'name' => $imageFile->getClientOriginalName(),
                'path' => $path,
                'disk' => 'private',
                'mime_type' => $imageFile->getMimeType(),
                'size' => $imageFile->getSize(),
                'file_type' => 'image',
            ]);

            if ($publishOneDrive && $path) {
                $localPath = storage_path('app/' . $path);
                $remotePath = 'News/' . $news->id . '/' . $imageFile->getClientOriginalName();
                \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath)->onQueue('uploads');
            }
        }

        // Update permissions (simple sync for now, can be more complex)
        $news->permissions()->delete(); // Remove existing
        if ($request->has('permissions')) {
            foreach ($request->permissions as $permission) {
                NewsPermission::create([
                    'news_id' => $news->id,
                    'user_type_id' => $permission['user_type_id'],
                    'can_view' => $permission['can_view'] ?? false,
                ]);
            }
        }

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
        $uploadedImage = $news->image()->create([
            'name' => $imageFile->getClientOriginalName(),
            'path' => $path,
            'disk' => 'private',
            'mime_type' => $imageFile->getMimeType(),
            'size' => $imageFile->getSize(),
            'file_type' => 'image',
        ]);

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
}
