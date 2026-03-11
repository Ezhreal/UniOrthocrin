<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileValidationRequest;
use App\Models\Campaign;
use App\Models\CampaignPost;
use App\Models\CampaignFolder;
use App\Models\CampaignVideo;
use App\Models\CampaignMiscellaneous;
use App\Models\File;
use App\Models\UserType;
use App\Models\OneDriveSync;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $query = Campaign::with(['posts', 'folders', 'videos', 'miscellaneous']);

        if ($search = $request->get('search')) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($franchise_only = $request->get('franchise_only')) {
            $query->where('visible_franchise_only', $franchise_only === 'yes');
        }

        $campaigns = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('admin.campaigns.create');
    }

    public function store(Request $request)
    {
        // Validações básicas + validações específicas de arquivo
        $validationRules = array_merge([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:10240',
            'is_featured' => 'nullable|boolean',
            'banner' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:20480',
            'folder_mg_sp' => 'nullable|array',
            'folder_df_es' => 'nullable|array',
            'posts_feed' => 'nullable|array',
            'posts_stories_mg_sp' => 'nullable|array',
            'posts_stories_df_es' => 'nullable|array',
            'videos_reels' => 'nullable|array',
            'videos_reels.*' => 'nullable|file|mimes:mp4,avi,mov|max:' . config('upload.max_video_size'),
            'videos_campaigns' => 'nullable|array',
            'videos_campaigns.*' => 'nullable|file|mimes:mp4,avi,mov|max:' . config('upload.max_video_size'),
            'misc_spot' => 'nullable|array',
            'misc_tag' => 'nullable|array',
            'misc_sticker' => 'nullable|array',
            'misc_script' => 'nullable|array',
            'misc_adesivo' => 'nullable|array',
            'misc_banner' => 'nullable|array',
            'misc_faixa' => 'nullable|array',
        ], FileValidationRequest::getProductValidationRules());

        $request->validate($validationRules, (new FileValidationRequest())->messages());

        $campaign = Campaign::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'visible_franchise_only' => true,
            'status' => $request->input('status'),
            'is_featured' => (bool) $request->input('is_featured'),
        ]);

        // Upload thumbnail
        if ($request->hasFile('thumbnail')) {
            $thumb = $request->file('thumbnail');
            $thumbPath = $thumb->store("private/campaigns/{$campaign->id}/thumb", 'private');
            $campaign->thumbnail_path = $thumbPath;
        }

        // Upload banner se marcado como destaque
        if ($request->boolean('is_featured') && $request->hasFile('banner')) {
            $banner = $request->file('banner');
            $bannerPath = $banner->store("private/campaigns/{$campaign->id}/banner", 'private');
            $campaign->banner_path = $bannerPath;
        }

        $campaign->save();

        // Processar uploads de arquivos
        $this->processFileUploads($request, $campaign);

        // Processar vídeos enviados via chunk (create): mover de uploads/campaign/ e criar registros
        $this->processResolvedPathsVideos($request, $campaign);

        // Criar notificação automática para usuários com permissão
        NotificationService::notifyNewCampaign($campaign->id, $campaign->name);

        return redirect()->route('admin.campaigns.show', $campaign)->with('success', 'Campanha criada com sucesso!');
    }

    public function show(Campaign $campaign)
    {
        $campaign->load([
            'posts.files',
            'folders.files', 
            'videos.files',
            'miscellaneous.files'
        ]);
        return view('admin.campaigns.show', compact('campaign'));
    }

    public function edit(Campaign $campaign)
    {
        $campaign->load([
            'posts.files',
            'folders.files', 
            'videos.files',
            'miscellaneous.files'
        ]);
        return view('admin.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign)
    {

        $validationRules = array_merge([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|in:active,inactive',
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:10240',
            'is_featured' => 'nullable|boolean',
            'banner' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:20480',
            'folder_mg_sp' => 'nullable|array',
            'folder_df_es' => 'nullable|array',
            'posts_feed' => 'nullable|array',
            'posts_stories_mg_sp' => 'nullable|array',
            'posts_stories_df_es' => 'nullable|array',
            'videos_reels' => 'nullable|array',
            'videos_reels.*' => 'nullable|file|mimes:mp4,avi,mov|max:' . config('upload.max_video_size'),
            'videos_campaigns' => 'nullable|array',
            'videos_campaigns.*' => 'nullable|file|mimes:mp4,avi,mov|max:' . config('upload.max_video_size'),
            'misc_spot' => 'nullable|array',
            'misc_tag' => 'nullable|array',
            'misc_sticker' => 'nullable|array',
            'misc_script' => 'nullable|array',
            'misc_adesivo' => 'nullable|array',
            'misc_banner' => 'nullable|array',
            'misc_faixa' => 'nullable|array',
        ], FileValidationRequest::getProductValidationRules());

        $request->validate($validationRules, (new FileValidationRequest())->messages());

        $campaign->update(array_merge(
            $request->only([
                'name', 'description', 'start_date', 'end_date', 'status', 'is_featured'
            ]),
            ['visible_franchise_only' => true]
        ));

        if ($request->hasFile('thumbnail')) {
            $thumb = $request->file('thumbnail');
            $thumbPath = $thumb->store("private/campaigns/{$campaign->id}/thumb", 'private');
            $campaign->thumbnail_path = $thumbPath;
        }

        if ($request->boolean('is_featured') && $request->hasFile('banner')) {
            $banner = $request->file('banner');
            $bannerPath = $banner->store("private/campaigns/{$campaign->id}/banner", 'private');
            $campaign->banner_path = $bannerPath;
        } else if (!$request->boolean('is_featured')) {
            // Se desmarcar destaque, zera o banner
            $campaign->banner_path = null;
        }

        $campaign->save();

        // Processar uploads de arquivos (posts, vídeos, folders, etc.)
        $this->processFileUploads($request, $campaign);

        return redirect()->route('admin.campaigns.show', $campaign)->with('success', 'Campanha atualizada com sucesso!');
    }

    public function destroy(Campaign $campaign)
    {
        // Delete all associated content
        if ($campaign->posts) {
            foreach ($campaign->posts as $post) {
                // Deletar arquivos associados aos posts
                if ($post->files) {
                    foreach ($post->files as $file) {
                        if ($file->path && Storage::disk('private')->exists($file->path)) {
                            Storage::disk('private')->delete($file->path);
                        }
                        $file->delete();
                    }
                }
                $post->delete();
            }
        }

        if ($campaign->folders) {
            foreach ($campaign->folders as $folder) {
                // Deletar arquivos associados às pastas
                if ($folder->files) {
                    foreach ($folder->files as $file) {
                        if ($file->path && Storage::disk('private')->exists($file->path)) {
                            Storage::disk('private')->delete($file->path);
                        }
                        $file->delete();
                    }
                }
                $folder->delete();
            }
        }

        if ($campaign->videos) {
            foreach ($campaign->videos as $video) {
                // Deletar arquivos associados aos vídeos
                if ($video->files) {
                    foreach ($video->files as $file) {
                        if ($file->path && Storage::disk('private')->exists($file->path)) {
                            Storage::disk('private')->delete($file->path);
                        }
                        $file->delete();
                    }
                }
                $video->delete();
            }
        }

        if ($campaign->miscellaneous) {
            foreach ($campaign->miscellaneous as $misc) {
                // Deletar arquivos associados aos miscellaneous
                if ($misc->files) {
                    foreach ($misc->files as $file) {
                        if ($file->path && Storage::disk('private')->exists($file->path)) {
                            Storage::disk('private')->delete($file->path);
                        }
                        $file->delete();
                    }
                }
                $misc->delete();
            }
        }

        $campaign->delete();
        return redirect()->route('admin.campaigns.index')->with('success', 'Campanha deletada com sucesso!');
    }

    // Posts Management
    public function createPost(Campaign $campaign)
    {
        return view('admin.campaigns.posts.create', compact('campaign'));
    }

    public function storePost(Request $request, Campaign $campaign)
    {
        $validationRules = array_merge([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
        ], FileValidationRequest::getCampaignPostsValidationRules());

        $request->validate($validationRules, (new FileValidationRequest())->messages());

        $post = $campaign->posts()->create($request->only([
            'title', 'description', 'status'
        ]));

        // Handle image upload
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $path = $imageFile->store('private/campaigns/' . $campaign->id . '/posts', 'private');
            
            // Criar o arquivo
            $fileRecord = File::create([
                'name' => $imageFile->getClientOriginalName(),
                'path' => $path,
                'type' => 'image',
                'extension' => $this->getFileExtension($imageFile->getClientOriginalName()),
                'mime_type' => $imageFile->getMimeType(),
                'size' => $imageFile->getSize(),
                'order' => 0,
            ]);
            
            // Associar o arquivo ao post
            $post->files()->attach($fileRecord->id, [
                'file_type' => 'image',
                'sort_order' => 0,
                'is_primary' => true
            ]);

            // OneDrive sync se habilitado
            $publishOneDrive = $request->boolean('publish_onedrive');
            if ($publishOneDrive && $path) {
                $localPath = storage_path('app/' . $path);
                $remotePath = 'Campaigns/' . $campaign->id . '/posts/' . $post->id . '-' . $fileRecord->id . '.' . $this->getFileExtension($imageFile->getClientOriginalName());
                $sync = $this->createOneDriveSync($campaign, $path, $remotePath);
                \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath, $sync->id);
            }
        }

        return redirect()->route('admin.campaigns.show', $campaign)->with('success', 'Post criado com sucesso!');
    }

    // Folders Management
    public function createFolder(Campaign $campaign)
    {
        return view('admin.campaigns.folders.create', compact('campaign'));
    }

    public function storeFolder(Request $request, Campaign $campaign)
    {
        $validationRules = array_merge([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
        ], FileValidationRequest::getCampaignFolhetosValidationRules());

        $request->validate($validationRules, (new FileValidationRequest())->messages());

        $folder = $campaign->folders()->create($request->only([
            'name', 'description', 'status'
        ]));

        // Handle file uploads
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('private/campaigns/' . $campaign->id . '/folders/' . $folder->id, 'private');
                
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
                
                // Associar o arquivo à pasta
                $folder->files()->attach($fileRecord->id, [
                    'file_type' => $this->getFileType($file->getMimeType()),
                    'sort_order' => 0,
                    'is_primary' => true
                ]);

                // OneDrive sync se habilitado
                $publishOneDrive = $request->boolean('publish_onedrive');
                if ($publishOneDrive && $path) {
                    $localPath = storage_path('app/' . $path);
                    $remotePath = 'Campaigns/' . $campaign->id . '/folders/' . $folder->id . '-' . $fileRecord->id . '.' . $this->getFileExtension($file->getClientOriginalName());
                    $sync = $this->createOneDriveSync($campaign, $path, $remotePath);
                    \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath, $sync->id);
                }
            }
        }

        return redirect()->route('admin.campaigns.show', $campaign)->with('success', 'Pasta criada com sucesso!');
    }

    // Videos Management
    public function createVideo(Campaign $campaign)
    {
        return view('admin.campaigns.videos.create', compact('campaign'));
    }

    public function storeVideo(Request $request, Campaign $campaign)
    {
        $validationRules = array_merge([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
        ], FileValidationRequest::getCampaignVideosValidationRules());

        $request->validate($validationRules, (new FileValidationRequest())->messages());

        $video = $campaign->videos()->create($request->only([
            'title', 'description', 'status'
        ]));

        // Handle video upload
        if ($request->hasFile('video_file')) {
            $videoFile = $request->file('video_file');
            $path = $videoFile->store('private/campaigns/' . $campaign->id . '/videos', 'private');
            
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
            
            // Associar o arquivo ao vídeo
            $video->files()->attach($fileRecord->id, [
                'file_type' => 'video',
                'sort_order' => 0,
                'is_primary' => true
            ]);

            // OneDrive sync se habilitado
            $publishOneDrive = $request->boolean('publish_onedrive');
            if ($publishOneDrive && $path) {
                $localPath = storage_path('app/' . $path);
                $remotePath = 'Campaigns/' . $campaign->id . '/videos/' . $video->id . '-' . $fileRecord->id . '.' . $this->getFileExtension($videoFile->getClientOriginalName());
                $sync = $this->createOneDriveSync($campaign, $path, $remotePath);
                \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath, $sync->id);
            }
        }

        return redirect()->route('admin.campaigns.show', $campaign)->with('success', 'Vídeo criado com sucesso!');
    }

    // Miscellaneous Management
    public function createMiscellaneous(Campaign $campaign)
    {
        return view('admin.campaigns.miscellaneous.create', compact('campaign'));
    }

    public function storeMiscellaneous(Request $request, Campaign $campaign)
    {
        // Determinar tipo de validação baseado no tipo do miscellaneous
        $type = $request->input('type', 'spot');
        $validationRules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
        ];

        if ($type === 'spot') {
            $validationRules = array_merge($validationRules, FileValidationRequest::getMiscellaneousSpotValidationRules());
        } else {
            $validationRules = array_merge($validationRules, FileValidationRequest::getMiscellaneousDocumentValidationRules());
        }

        $request->validate($validationRules, (new FileValidationRequest())->messages());

        $misc = $campaign->miscellaneous()->create($request->only([
            'name', 'description', 'status'
        ]));

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $path = $file->store('private/campaigns/' . $campaign->id . '/miscellaneous', 'private');
            
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
            
            // Associar o arquivo ao miscellaneous
            $misc->files()->attach($fileRecord->id, [
                'file_type' => $this->getFileType($file->getMimeType()),
                'sort_order' => 0,
                'is_primary' => true
            ]);

            // OneDrive sync se habilitado
            $publishOneDrive = $request->boolean('publish_onedrive');
            if ($publishOneDrive && $path) {
                $localPath = storage_path('app/' . $path);
                $remotePath = 'Campaigns/' . $campaign->id . '/miscellaneous/' . $type . '-' . $misc->id . '-' . $fileRecord->id . '.' . $this->getFileExtension($file->getClientOriginalName());
                $sync = $this->createOneDriveSync($campaign, $path, $remotePath);
                \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath, $sync->id);
            }
        }

        return redirect()->route('admin.campaigns.show', $campaign)->with('success', 'Item criado com sucesso!');
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
            return 'pdf'; // Default para outros tipos de arquivo (PDF é o mais comum)
        }
    }
    
    private function getFileExtension($filename)
    {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }


    private function processFileUploads(Request $request, Campaign $campaign)
    {
        // Processar Posts (Imagens)
        $this->processPosts($request, $campaign);
        
        // Processar Folders (Folhetos)
        $this->processFolders($request, $campaign);
        
        // Processar Videos
        $this->processVideos($request, $campaign);
        
        // Processar Miscellaneous
        $this->processMiscellaneous($request, $campaign);
    }

    private function processPosts(Request $request, Campaign $campaign)
    {
        $publishOneDrive = $request->boolean('publish_onedrive');
        $postTypes = [
            'posts_feed' => 'feeds',
            'posts_stories_mg_sp' => 'stories_mg_sp',
            'posts_stories_df_es' => 'stories_df_es'
        ];

        foreach ($postTypes as $inputName => $type) {
            if ($request->hasFile($inputName)) {
                foreach ($request->file($inputName) as $file) {
                    $path = $file->store('private/campaigns/' . $campaign->id . '/posts', 'private');
                    
                    $post = $campaign->posts()->create([
                        'name' => $file->getClientOriginalName(),
                        'type' => $type,
                        'status' => 'active'
                    ]);
                    
                    // Criar o arquivo
                    $fileRecord = File::create([
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'type' => 'image',
                        'extension' => $this->getFileExtension($file->getClientOriginalName()),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'order' => 0,
                    ]);
                    
                    // Associar o arquivo ao post
                    $post->files()->attach($fileRecord->id, [
                        'file_type' => 'image',
                        'sort_order' => 0,
                        'is_primary' => true
                    ]);

                    if ($publishOneDrive && $path) {
                        $localPath = storage_path('app/' . $path);
                        $remotePath = 'Campaigns/' . $campaign->id . '/posts/' . $type . '-' . $fileRecord->id . '.' . $this->getFileExtension($file->getClientOriginalName());
                        $sync = $this->createOneDriveSync($campaign, $path, $remotePath);
                        \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath, $sync->id);
                    }
                }
            }
        }
    }

    private function processFolders(Request $request, Campaign $campaign)
    {
        $publishOneDrive = $request->boolean('publish_onedrive');
        $folderTypes = [
            'folder_mg_sp' => 'MG/SP',
            'folder_df_es' => 'DF/ES'
        ];

        foreach ($folderTypes as $inputName => $state) {
            if ($request->hasFile($inputName)) {
                foreach ($request->file($inputName) as $file) {
                    $path = $file->store('private/campaigns/' . $campaign->id . '/folders', 'private');
                    
                    $folder = $campaign->folders()->create([
                        'name' => $file->getClientOriginalName(),
                        'state' => $state,
                        'status' => 'active'
                    ]);
                    
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
                    
                    // Associar o arquivo à pasta
                    $folder->files()->attach($fileRecord->id, [
                        'file_type' => $this->getFileType($file->getMimeType()),
                        'sort_order' => 0,
                        'is_primary' => true
                    ]);

                    if ($publishOneDrive && $path) {
                        $localPath = storage_path('app/' . $path);
                        $remotePath = 'Campaigns/' . $campaign->id . '/folders/' . $state . '-' . $fileRecord->id . '.' . $this->getFileExtension($file->getClientOriginalName());
                        $sync = $this->createOneDriveSync($campaign, $path, $remotePath);
                        \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath, $sync->id);
                    }
                }
            }
        }
    }

    private function processVideos(Request $request, Campaign $campaign)
    {
        $publishOneDrive = $request->boolean('publish_onedrive');
        $videoTypes = [
            'videos_reels' => 'reels',
            'videos_campaigns' => 'marketing_campaigns'
        ];

        foreach ($videoTypes as $inputName => $type) {
            if ($request->hasFile($inputName)) {
                foreach ($request->file($inputName) as $file) {
                    $path = $file->store('private/campaigns/' . $campaign->id . '/videos', 'private');
                    
                    $video = $campaign->videos()->create([
                        'name' => $file->getClientOriginalName(),
                        'type' => $type,
                        'status' => 'active'
                    ]);
                    
                    // Criar o arquivo
                    $fileRecord = File::create([
                        'name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'type' => 'video',
                        'extension' => $this->getFileExtension($file->getClientOriginalName()),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'order' => 0,
                    ]);
                    
                    // Associar o arquivo ao vídeo
                    $video->files()->attach($fileRecord->id, [
                        'file_type' => 'video',
                        'sort_order' => 0,
                        'is_primary' => true
                    ]);

                    if ($publishOneDrive && $path) {
                        $localPath = storage_path('app/' . $path);
                        $remotePath = 'Campaigns/' . $campaign->id . '/videos/' . $type . '-' . $fileRecord->id . '.' . $this->getFileExtension($file->getClientOriginalName());
                        $sync = $this->createOneDriveSync($campaign, $path, $remotePath);
                        \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath, $sync->id);
                    }
                }
            }
        }
    }

    private function processMiscellaneous(Request $request, Campaign $campaign)
    {
        $publishOneDrive = $request->boolean('publish_onedrive');
        $miscTypes = [
            'misc_spot' => 'spot',
            'misc_tag' => 'tag',
            'misc_sticker' => 'sticker',
            'misc_script' => 'script',
            'misc_adesivo' => 'adesivo',
            'misc_banner' => 'banner',
            'misc_faixa' => 'faixa',
        ];

        foreach ($miscTypes as $inputName => $type) {
            if ($request->hasFile($inputName)) {
                foreach ($request->file($inputName) as $file) {
                    $path = $file->store('private/campaigns/' . $campaign->id . '/miscellaneous', 'private');
                    
                    $misc = $campaign->miscellaneous()->create([
                        'name' => $file->getClientOriginalName(),
                        'type' => $type,
                        'status' => 'active'
                    ]);
                    
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
                    
                    // Associar o arquivo ao miscellaneous
                    $misc->files()->attach($fileRecord->id, [
                        'file_type' => $this->getFileType($file->getMimeType()),
                        'sort_order' => 0,
                        'is_primary' => true
                    ]);

                    if ($publishOneDrive && $path) {
                        $localPath = storage_path('app/' . $path);
                        $remotePath = 'Campaigns/' . $campaign->id . '/miscellaneous/' . $type . '-' . $fileRecord->id . '.' . $this->getFileExtension($file->getClientOriginalName());
                        $sync = $this->createOneDriveSync($campaign, $path, $remotePath);
                        \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath, $sync->id);
                    }
                }
            }
        }
    }

    /**
     * No create: processa resolved_paths_reels e resolved_paths_campaigns vindos do chunk upload.
     * Move arquivos de uploads/campaign/ para private/campaigns/{id}/videos/ e cria File + CampaignVideo.
     */
    private function processResolvedPathsVideos(Request $request, Campaign $campaign): void
    {
        $types = [
            'resolved_paths_reels'      => 'reels',
            'resolved_paths_campaigns' => 'marketing_campaigns',
        ];

        foreach ($types as $inputKey => $videoType) {
            $paths = (array) $request->input($inputKey, []);
            foreach ($paths as $path) {
                if (!is_string($path) || $path === '') {
                    continue;
                }
                $absolutePath = storage_path('app/' . $path);
                if (!file_exists($absolutePath)) {
                    continue;
                }

                $content = file_get_contents($absolutePath);
                $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                $filename = Str::uuid() . '.' . $extension;
                $newPath = 'private/campaigns/' . $campaign->id . '/videos/' . $filename;

                if (!Storage::disk('private')->put($newPath, $content)) {
                    continue;
                }
                @unlink($absolutePath);

                $mimeType = mime_content_type(storage_path('app/' . $newPath)) ?: 'application/octet-stream';
                $size = @filesize(storage_path('app/' . $newPath)) ?: 0;

                $fileRecord = File::create([
                    'name'      => $filename,
                    'path'      => $newPath,
                    'type'      => 'video',
                    'extension' => $extension,
                    'mime_type' => $mimeType,
                    'size'      => $size,
                    'order'     => 0,
                ]);

                $video = $campaign->videos()->create([
                    'name'   => $filename,
                    'type'   => $videoType,
                    'status' => 'active',
                ]);

                $video->files()->attach($fileRecord->id, [
                    'file_type'  => 'video',
                    'sort_order' => 0,
                    'is_primary' => true,
                ]);
            }
        }
    }

    /**
     * Upload incremental de arquivos via AJAX
     * Reutiliza exatamente a mesma lógica de processFileUploads.
     */
    public function uploadFiles(Request $request, Campaign $campaign)
    {
        // Suporte a caminhos já resolvidos por tipo (upload em chunks - edit)
        if ($request->filled('resolved_paths_reels') || $request->filled('resolved_paths_campaigns')) {
            $uploaded = [];
            foreach (['resolved_paths_reels' => 'reels', 'resolved_paths_campaigns' => 'marketing_campaigns'] as $key => $type) {
                $paths = (array) $request->input($key, []);
                foreach ($paths as $path) {
                    if (!is_string($path) || $path === '') {
                        continue;
                    }
                    $absolutePath = storage_path('app/' . $path);
                    if (!file_exists($absolutePath)) {
                        continue;
                    }

                    $mimeType = mime_content_type($absolutePath) ?: 'application/octet-stream';
                    $size = @filesize($absolutePath) ?: 0;
                    $extension = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));
                    $name = basename($path);

                    $fileType = $this->getFileType($mimeType);

                    $fileRecord = File::create([
                        'name'      => $name,
                        'path'      => $path,
                        'type'      => $fileType,
                        'extension' => $extension,
                        'mime_type' => $mimeType,
                        'size'      => $size,
                        'order'     => 0,
                    ]);

                    $video = $campaign->videos()->create([
                        'name'   => $name,
                        'type'   => $type,
                        'status' => 'active',
                    ]);

                    $video->files()->attach($fileRecord->id, [
                        'file_type'  => $fileType,
                        'sort_order' => 0,
                        'is_primary' => true,
                    ]);

                    $uploaded[] = $fileRecord;
                }
            }

            return response()->json([
                'success' => true,
                'files'   => $uploaded,
            ]);
        }

        // Suporte a caminhos já resolvidos (upload em chunks - legado, trata como reels)
        if ($request->filled('resolved_paths')) {
            $paths = (array) $request->input('resolved_paths');
            $uploaded = [];

            foreach ($paths as $path) {
                $absolutePath = storage_path('app/' . $path);
                if (!file_exists($absolutePath)) {
                    continue;
                }

                $mimeType = mime_content_type($absolutePath) ?: 'application/octet-stream';
                $size = @filesize($absolutePath) ?: 0;
                $extension = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));
                $name = basename($absolutePath);

                $fileType = $this->getFileType($mimeType);

                $fileRecord = File::create([
                    'name'      => $name,
                    'path'      => $path,
                    'type'      => $fileType,
                    'extension' => $extension,
                    'mime_type' => $mimeType,
                    'size'      => $size,
                    'order'     => 0,
                ]);

                $video = $campaign->videos()->create([
                    'name'   => $name,
                    'type'   => 'reels',
                    'status' => 'active',
                ]);

                $video->files()->attach($fileRecord->id, [
                    'file_type'  => $fileType,
                    'sort_order' => 0,
                    'is_primary' => true,
                ]);

                $uploaded[] = $fileRecord;
            }

            return response()->json([
                'success' => true,
                'files'   => $uploaded,
            ]);
        }

        // Reaproveita as mesmas regras básicas usadas em store/update
        $validationRules = [
            'folder_mg_sp' => 'nullable|array',
            'folder_mg_sp.*' => 'file|mimes:pdf,jpeg,jpg,png,webp|max:' . config('upload.max_document_size'),
            'folder_df_es' => 'nullable|array',
            'folder_df_es.*' => 'file|mimes:pdf,jpeg,jpg,png,webp|max:' . config('upload.max_document_size'),

            'posts_feed' => 'nullable|array',
            'posts_feed.*' => 'image|mimes:jpeg,jpg,png,webp|max:' . config('upload.max_image_size'),
            'posts_stories_mg_sp' => 'nullable|array',
            'posts_stories_mg_sp.*' => 'image|mimes:jpeg,jpg,png,webp|max:' . config('upload.max_image_size'),
            'posts_stories_df_es' => 'nullable|array',
            'posts_stories_df_es.*' => 'image|mimes:jpeg,jpg,png,webp|max:' . config('upload.max_image_size'),

            'videos_reels' => 'nullable|array',
            'videos_reels.*' => 'file|mimes:mp4,avi,mov|max:' . config('upload.max_video_size'),
            'videos_campaigns' => 'nullable|array',
            'videos_campaigns.*' => 'file|mimes:mp4,avi,mov|max:' . config('upload.max_video_size'),

            'misc_spot' => 'nullable|array',
            'misc_spot.*' => 'file|mimes:mp3,wav,ogg,aac,m4a|max:' . config('upload.max_audio_size'),
            'misc_tag' => 'nullable|array',
            'misc_tag.*' => 'file|mimes:pdf,jpeg,jpg,png,webp|max:' . config('upload.max_document_size'),
            'misc_sticker' => 'nullable|array',
            'misc_sticker.*' => 'file|mimes:pdf,jpeg,jpg,png,webp|max:' . config('upload.max_document_size'),
            'misc_script' => 'nullable|array',
            'misc_script.*' => 'file|mimes:pdf,doc,docx,txt|max:' . config('upload.max_document_size'),
            'misc_adesivo' => 'nullable|array',
            'misc_adesivo.*' => 'file|mimes:pdf,jpeg,jpg,png,webp|max:' . config('upload.max_document_size'),
            'misc_banner' => 'nullable|array',
            'misc_banner.*' => 'file|mimes:pdf,jpeg,jpg,png,webp|max:' . config('upload.max_document_size'),
            'misc_faixa' => 'nullable|array',
            'misc_faixa.*' => 'file|mimes:pdf,jpeg,jpg,png,webp|max:' . config('upload.max_document_size'),
        ];

        $request->validate($validationRules, (new FileValidationRequest())->messages());

        // Processa somente os tipos que vieram neste request
        $this->processFileUploads($request, $campaign);

        return response()->json([
            'success' => true,
            'message' => 'Arquivos enviados com sucesso.',
        ]);
    }

    private function createOneDriveSync($campaign, $filePath, $remotePath)
    {
        $sync = OneDriveSync::create([
            'syncable_type' => get_class($campaign),
            'syncable_id' => $campaign->id,
            'file_path' => $filePath,
            'remote_path' => $remotePath,
            'status' => 'pending'
        ]);

        return $sync;
    }

    public function syncToOneDrive(Campaign $campaign)
    {
        try {
            $syncedCount = 0;
            
            // Sincronizar todos os arquivos da campanha (posts, folders, videos, miscellaneous)
            $allFiles = collect();
            
            // Posts
            foreach ($campaign->posts as $post) {
                $allFiles = $allFiles->merge($post->files);
            }
            
            // Folders
            foreach ($campaign->folders as $folder) {
                $allFiles = $allFiles->merge($folder->files);
            }
            
            // Videos
            foreach ($campaign->videos as $video) {
                $allFiles = $allFiles->merge($video->files);
            }
            
            // Miscellaneous
            foreach ($campaign->miscellaneous as $misc) {
                $allFiles = $allFiles->merge($misc->files);
            }
            
            // Sincronizar arquivos únicos
            foreach ($allFiles->unique('id') as $file) {
                // Verificar se já existe sync para este arquivo
                $existingSync = OneDriveSync::where('syncable_type', get_class($campaign))
                    ->where('syncable_id', $campaign->id)
                    ->where('file_path', $file->path)
                    ->first();
                
                if (!$existingSync) {
                    // Criar novo sync
                    $localPath = storage_path('app/' . $file->path);
                    $remotePath = 'Campaigns/' . $campaign->id . '/' . $file->name;
                    
                    $sync = $this->createOneDriveSync($campaign, $file->path, $remotePath);
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

    public function retryOneDriveSync(Campaign $campaign)
    {
        try {
            $retryCount = 0;
            
            // Reenviar arquivos que falharam
            $failedSyncs = OneDriveSync::where('syncable_type', get_class($campaign))
                ->where('syncable_id', $campaign->id)
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
