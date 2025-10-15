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
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $userTypes = UserType::orderBy('name')->get();
        return view('admin.campaigns.create', compact('userTypes'));
    }

    public function store(Request $request)
    {
        // Validações básicas + validações específicas de arquivo
        $validationRules = array_merge([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'visible_franchise_only' => 'boolean',
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
            'videos_campaigns' => 'nullable|array',
            'misc_spot' => 'nullable|array',
            'misc_tag' => 'nullable|array',
            'misc_sticker' => 'nullable|array',
            'misc_script' => 'nullable|array',
        ], $this->getCampaignFileValidationRules());

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
            $thumbPath = $thumb->store('private/campaigns/' . $campaign->id . '/thumb', 'private');
            $campaign->thumbnail_path = $thumbPath;
        }

        // Upload banner se marcado como destaque
        if ($request->boolean('is_featured') && $request->hasFile('banner')) {
            $banner = $request->file('banner');
            $bannerPath = $banner->store('private/campaigns/' . $campaign->id . '/banner', 'private');
            $campaign->banner_path = $bannerPath;
        }

        $campaign->save();

        // Processar uploads de arquivos
        $this->processFileUploads($request, $campaign);

        // Criar notificação automática para usuários com permissão
        NotificationService::notifyNewCampaign($campaign->id, $campaign->name);

        return redirect()->route('admin.campaigns.show', $campaign)->with('success', 'Campanha criada com sucesso!');
    }

    public function show(Campaign $campaign)
    {
        $campaign->load(['posts', 'folders', 'videos', 'miscellaneous']);
        return view('admin.campaigns.show', compact('campaign'));
    }

    public function edit(Campaign $campaign)
    {
        return view('admin.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'visible_franchise_only' => 'boolean',
            'status' => 'required|in:active,inactive',
            'thumbnail' => 'nullable|image|max:10240',
            'is_featured' => 'nullable|boolean',
            'banner' => 'nullable|image|max:20480',
            'folder_mg_sp' => 'nullable|string|max:255',
            'folder_df_es' => 'nullable|string|max:255',
            'posts_feed' => 'nullable|array',
            'posts_stories_mg_sp' => 'nullable|array',
            'posts_stories_df_es' => 'nullable|array',
            'spot_audio' => 'nullable|string|max:255',
            'tag_pdf' => 'nullable|string|max:255',
            'adesivo_pdf' => 'nullable|string|max:255',
            'roteiros_pdf' => 'nullable|string|max:255',
        ]);

        $campaign->update(array_merge(
            $request->only([
                'name', 'description', 'start_date', 'end_date', 'status', 'is_featured',
                'folder_mg_sp', 'folder_df_es', 'posts_feed', 'posts_stories_mg_sp', 'posts_stories_df_es',
                'spot_audio', 'tag_pdf', 'adesivo_pdf', 'roteiros_pdf'
            ]),
            ['visible_franchise_only' => true]
        ));

        if ($request->hasFile('thumbnail')) {
            $thumb = $request->file('thumbnail');
            $thumbPath = $thumb->store('private/campaigns/' . $campaign->id . '/thumb', 'private');
            $campaign->thumbnail_path = $thumbPath;
        }

        if ($request->boolean('is_featured') && $request->hasFile('banner')) {
            $banner = $request->file('banner');
            $bannerPath = $banner->store('private/campaigns/' . $campaign->id . '/banner', 'private');
            $campaign->banner_path = $bannerPath;
        } else if (!$request->boolean('is_featured')) {
            // Se desmarcar destaque, zera o banner
            $campaign->banner_path = null;
        }

        $campaign->save();

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
                    'disk' => 'private',
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'file_type' => $this->getFileType($file->getMimeType()),
                ]);
                
                // Associar o arquivo à pasta
                $folder->files()->attach($fileRecord->id, [
                    'file_type' => $this->getFileType($file->getMimeType()),
                    'sort_order' => 0,
                    'is_primary' => true
                ]);
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
                'disk' => 'private',
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'file_type' => $this->getFileType($file->getMimeType()),
            ]);
            
            // Associar o arquivo ao miscellaneous
            $misc->files()->attach($fileRecord->id, [
                'file_type' => $this->getFileType($file->getMimeType()),
                'sort_order' => 0,
                'is_primary' => true
            ]);
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
            return 'pdf'; // Default para outros tipos de arquivo
        }
    }
    
    private function getFileExtension($filename)
    {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }

    /**
     * Obter validações específicas de arquivo para campanhas
     */
    private function getCampaignFileValidationRules()
    {
        return [
            // Folhetos (PDFs)
            'folder_mg_sp.*' => 'file|mimes:pdf|max:102400',
            'folder_df_es.*' => 'file|mimes:pdf|max:102400',
            
            // Posts (Imagens)
            'posts_feed.*' => 'image|mimes:jpeg,jpg,png,webp|max:10240',
            'posts_stories_mg_sp.*' => 'image|mimes:jpeg,jpg,png,webp|max:10240',
            'posts_stories_df_es.*' => 'image|mimes:jpeg,jpg,png,webp|max:10240',
            
            // Vídeos
            'videos_reels.*' => 'file|mimes:mp4,mov,ogg,avi|max:102400',
            'videos_campaigns.*' => 'file|mimes:mp4,mov,ogg,avi|max:102400',
            
            // Miscellaneous
            'misc_spot.*' => 'file|mimes:mp3,wav,ogg,aac,m4a|max:102400', // Spot (áudio)
            'misc_tag.*' => 'file|mimes:pdf|max:102400', // Tag (PDF)
            'misc_sticker.*' => 'file|mimes:pdf|max:102400', // Adesivo (PDF)
            'misc_script.*' => 'file|mimes:pdf|max:102400', // Roteiro (PDF)
        ];
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
                        $remotePath = 'Campaigns/' . $campaign->id . '/posts/' . $file->getClientOriginalName();
                        \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath)->onQueue('uploads');
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
                        $remotePath = 'Campaigns/' . $campaign->id . '/folders/' . $file->getClientOriginalName();
                        \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath)->onQueue('uploads');
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
                        'disk' => 'private',
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'file_type' => 'video',
                    ]);
                    
                    // Associar o arquivo ao vídeo
                    $video->files()->attach($fileRecord->id, [
                        'file_type' => 'video',
                        'sort_order' => 0,
                        'is_primary' => true
                    ]);

                    if ($publishOneDrive && $path) {
                        $localPath = storage_path('app/' . $path);
                        $remotePath = 'Campaigns/' . $campaign->id . '/videos/' . $file->getClientOriginalName();
                        \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath)->onQueue('uploads');
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
            'misc_sticker' => 'adesivo',
            'misc_script' => 'roteiro'
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
                            'disk' => 'private',
                            'mime_type' => $file->getMimeType(),
                            'size' => $file->getSize(),
                            'file_type' => $this->getFileType($file->getMimeType()),
                        ]);
                        
                        // Associar o arquivo ao miscellaneous
                        $misc->files()->attach($fileRecord->id, [
                            'file_type' => $this->getFileType($file->getMimeType()),
                            'sort_order' => 0,
                            'is_primary' => true
                        ]);

                        if ($publishOneDrive && $path) {
                            $localPath = storage_path('app/' . $path);
                            $remotePath = 'Campaigns/' . $campaign->id . '/miscellaneous/' . $file->getClientOriginalName();
                            \App\Jobs\UploadToOneDrive::dispatch($localPath, $remotePath)->onQueue('uploads');
                        }
                }
            }
        }
    }
}
