@extends('layouts.app')

@section('content')
@if(!isset($campaign) || !$campaign)
    <div class="bg-[#F9F9F9] min-h-screen flex items-center justify-center">
        <div class="text-center">
            <h1 class="text-2xl font-bold text-gray-800 mb-4">Campanha não encontrada</h1>
            <p class="text-gray-600 mb-6">A campanha solicitada não existe ou você não tem permissão para acessá-la.</p>
            <a href="{{ route('campanhas.list', ['profile_slug' => session('active_profile_slug')]) }}" class="inline-flex items-center px-4 py-2 bg-[#910039] text-white rounded-lg hover:bg-[#7a0030] transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar para Marketing
            </a>
        </div>
    </div>
@else
<div class="bg-[#F9F9F9] min-h-screen">
    <!-- Banner com breadcrumb e título -->
    <div class="bg-[#910039] w-full py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-white">
                <div class="text-sm mb-2">
                    <a href="{{ route('home') }}" class="hover:underline">Home</a> > 
                    <a href="{{ route('campanhas.list', ['profile_slug' => session('active_profile_slug')]) }}" class="hover:underline">Marketing</a> > 
                    {{ $campaign->name ?? 'Campanha' }}
                </div>
                <h1 class="text-3xl font-bold">{{ $campaign->name ?? 'Campanha' }}</h1>
                @if(isset($campaign->description) && $campaign->description)
                    <p class="text-lg mt-2">{{ $campaign->description }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-12">
        <!-- 3 Boxes principais lado a lado -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <!-- Box 1: Dados Gerais da Campanha -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h2 class="text-[#910039] text-xl font-bold mb-4">Dados Gerais da Campanha</h2>
                
                <div class="space-y-3">
                    
                    <div>
                        <span class="text-gray-600 text-sm">Vigência:</span>
                        <p class="text-gray-800">
                            @if(isset($campaign->start_date) && $campaign->start_date && isset($campaign->end_date) && $campaign->end_date)
                                {{ $campaign->start_date->format('d/m/Y') }} a {{ $campaign->end_date->format('d/m/Y') }}
                            @elseif(isset($campaign->start_date) && $campaign->start_date)
                                A partir de {{ $campaign->start_date->format('d/m/Y') }}
                            @elseif(isset($campaign->end_date) && $campaign->end_date)
                                Até {{ $campaign->end_date->format('d/m/Y') }}
                            @else
                                Vigência indefinida
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <span class="text-gray-600 text-sm">Status:</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ (isset($campaign->status) && $campaign->status === 'active') ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ (isset($campaign->status) && $campaign->status === 'active') ? 'Ativa' : 'Inativa' }}
                        </span>
                    </div>
                    
                    @php
                        $totalSize = 0;
                        $totalFiles = 0;
                        
                        // Calcular tamanho total dos arquivos com verificações de segurança
                        if (method_exists($campaign, 'posts')) {
                            foreach($campaign->posts()->with('files')->get() as $post) {
                                if ($post->files) {
                                    foreach($post->files as $file) {
                                        $totalSize += $file->size ?? 0;
                                        $totalFiles++;
                                    }
                                }
                            }
                        }
                        if (method_exists($campaign, 'folders')) {
                            foreach($campaign->folders()->with('files')->get() as $folder) {
                                if ($folder->files) {
                                    foreach($folder->files as $file) {
                                        $totalSize += $file->size ?? 0;
                                        $totalFiles++;
                                    }
                                }
                            }
                        }
                        if (method_exists($campaign, 'videos')) {
                            foreach($campaign->videos()->with('files')->get() as $video) {
                                if ($video->files) {
                                    foreach($video->files as $file) {
                                        $totalSize += $file->size ?? 0;
                                        $totalFiles++;
                                    }
                                }
                            }
                        }
                        if (method_exists($campaign, 'miscellaneous')) {
                            foreach($campaign->miscellaneous()->with('files')->get() as $misc) {
                                if ($misc->files) {
                                    foreach($misc->files as $file) {
                                        $totalSize += $file->size ?? 0;
                                        $totalFiles++;
                                    }
                                }
                            }
                        }
                        
                        $totalSizeMB = round($totalSize / 1024 / 1024, 2);
                    @endphp
                    
                    <div>
                        <span class="text-gray-600 text-sm">Arquivos:</span>
                        <p class="text-[#910039] font-semibold">{{ $totalSizeMB }} GB de arquivos disponíveis</p>
                    </div>
                </div>
                
                @if(auth()->check())
                <!-- Download da Campanha -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="w-full">
                        @csrf
                        <input type="hidden" name="content_type" value="marketing">
                        <input type="hidden" name="content_id" value="{{ $campaign->id }}">
                        <input type="hidden" name="type" value="all">
                        <button type="submit" class="inline-flex items-center gap-1 text-[#910039] text-xs">
                            <i class="fa-solid fa-download"></i>
                            Download da Campanha
                        </button>
                    </form>
                </div>
                @endif
            </div>

            <!-- Box 2: Folhetos (SP / Outros Estados — um download por arquivo) -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h2 class="text-[#910039] text-xl font-bold mb-4">Folhetos</h2>
                
                @php
                    $folhetosFoldersAll = method_exists($campaign, 'folders')
                        ? $campaign->folders()->active()->with('files')->get()
                        : collect();
                    $folhetosSp = $folhetosFoldersAll->where('state', 'MG/SP')->values();
                    $folhetosOutros = $folhetosFoldersAll->where('state', 'DF/ES')->values();
                    $folhetosDemais = $folhetosFoldersAll->whereNotIn('state', ['MG/SP', 'DF/ES'])->values();
                    $folhetosSections = [
                        ['folders' => $folhetosSp, 'title' => \App\Models\CampaignFolder::getFolhetosMarketingSectionTitle('MG/SP')],
                        ['folders' => $folhetosOutros, 'title' => \App\Models\CampaignFolder::getFolhetosMarketingSectionTitle('DF/ES')],
                    ];
                    if ($folhetosDemais->isNotEmpty()) {
                        $folhetosSections[] = ['folders' => $folhetosDemais, 'title' => 'Folhetos (outras regiões)'];
                    }
                    $folhetosFileCount = $folhetosFoldersAll->sum(fn ($fo) => $fo->files->count());
                @endphp

                @if($folhetosFileCount > 0)
                    @foreach($folhetosSections as $section)
                        @php $sectionFileCount = $section['folders']->sum(fn ($fo) => $fo->files->count()); @endphp
                        @if($sectionFileCount > 0)
                        <div class="mb-6 last:mb-0">
                            <h3 class="text-gray-800 font-semibold text-sm mb-3">{{ $section['title'] }}</h3>
                            <div class="space-y-0 border-t border-gray-200">
                                @foreach($section['folders'] as $folder)
                                    @foreach($folder->files as $file)
                                    <div class="flex items-center justify-between gap-3 p-3 border-b border-gray-200">
                                        <div class="flex items-center gap-3 min-w-0">
                                            @if($file->type === 'pdf')
                                                <i class="fas fa-file-pdf text-[#910039] text-lg flex-shrink-0"></i>
                                            @elseif($file->type === 'image')
                                                <i class="fas fa-file-image text-[#910039] text-lg flex-shrink-0"></i>
                                            @else
                                                <i class="fas fa-file text-[#910039] text-lg flex-shrink-0"></i>
                                            @endif
                                            <div class="min-w-0">
                                                <p class="text-sm text-gray-800 truncate" title="{{ $file->name }}">{{ $file->name }}</p>
                                                <p class="text-xs text-gray-500">({{ round(($file->size ?? 0) / 1024 / 1024, 1) }} MB)</p>
                                            </div>
                                        </div>
                                        <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="inline-flex shrink-0">
                                            @csrf
                                            <input type="hidden" name="content_type" value="marketing">
                                            <input type="hidden" name="content_id" value="{{ $campaign->id }}">
                                            <input type="hidden" name="type" value="all">
                                            <input type="hidden" name="file_ids[]" value="{{ $file->id }}">
                                            <button type="submit" class="inline-flex items-center gap-1 text-[#910039] text-xs">
                                                <i class="fa-solid fa-download"></i>
                                                Download
                                            </button>
                                        </form>
                                    </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endforeach
                @else
                <p class="text-gray-500 text-center py-8">Nenhum folheto disponível</p>
                @endif
            </div>
        </div>

        <!-- Posts - Galeria de Imagens com Tabs -->
        @if(method_exists($campaign, 'posts') && $campaign->posts()->active()->count() > 0)
        <div class="mb-12 bg-white p-8 rounded-lg shadow-sm">
            <h2 class="text-[#910039] text-2xl font-bold mb-8">Posts - Galeria de Imagens</h2>
            
            <!-- Tabs por tipo -->
            @php
                $postTypes = $campaign->posts()->active()->pluck('type')->unique();
            @endphp
            
            @if($postTypes->count() > 1)
            <div class="mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">
                        @foreach($postTypes as $type)
                        <button class="post-tab-btn py-2 px-1 border-b-2 font-medium text-sm {{ $loop->first ? 'border-[#910039] text-[#910039]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}" data-type="{{ $type }}">
                            @switch($type)
                                @case('feeds')
                                    Feed
                                    @break
                                @case('stories_mg_sp')
                                    Stories MG
                                    @break
                                @case('stories_df_es')
                                    Stories Outros Estados
                                    @break
                                @default
                                    {{ ucfirst($type) }}
                            @endswitch
                        </button>
                        @endforeach
                    </nav>
                </div>
            </div>
            @endif
            
            <!-- Carousel de imagens -->
            <div class="relative">
                <div class="flex items-center justify-center gap-4">
                    <!-- Seta esquerda -->
                    <button id="prevBtn" class="w-[250px] h-[40px] bg-[#910039] text-white rounded-full flex items-center justify-center hover:bg-[#7A0030] transition cursor-pointer">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    
                    <!-- Container das imagens do carousel -->
                    <div id="carouselContainer" class="flex gap-6 items-center">
                        @php
                            $firstPostType = $campaign->posts()->active()->pluck('type')->first();
                            $firstTypeImages = $campaign->posts()->active()->where('type', $firstPostType)->with('files')->get()->flatMap(function($post) {
                                return $post->files->where('type', 'image');
                            });
                        @endphp
                        
                        @if($firstTypeImages->count() > 0)
                        <!-- Imagem esquerda (blur) -->
                        <div id="leftImage" class="rounded-lg overflow-hidden opacity-60 transition-all duration-300" style="flex-basis: 30%; filter: blur(2px);">
                            <img src="{{ url('/' . $firstTypeImages->first()->path) }}" alt="{{ $firstTypeImages->first()->name }}" class="w-full h-auto object-cover">
                        </div>
                        
                        <!-- Imagem central (destaque) -->
                        <div id="centerImage" class="rounded-lg overflow-hidden shadow-lg transition-all duration-300" style="flex-basis: 40%;">
                            <img src="{{ url('/' . $firstTypeImages->get(1)->path ?? $firstTypeImages->first()->path) }}" alt="{{ $firstTypeImages->get(1)->name ?? $firstTypeImages->first()->name }}" class="w-full h-auto object-cover">
                        </div>
                        
                        <!-- Imagem direita (blur) -->
                        <div id="rightImage" class="rounded-lg overflow-hidden opacity-60 transition-all duration-300" style="flex-basis: 30%; filter: blur(2px);">
                            <img src="{{ url('/' . $firstTypeImages->get(2)->path ?? $firstTypeImages->first()->path) }}" alt="{{ $firstTypeImages->get(2)->name ?? $firstTypeImages->first()->name }}" class="w-full h-auto object-cover">
                        </div>
                        @else
                        <!-- Placeholder quando não há imagens -->
                        <div id="leftImage" class="rounded-lg overflow-hidden opacity-60 transition-all duration-300" style="flex-basis: 30%; filter: blur(2px);">
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <p class="text-gray-500">Nenhuma imagem</p>
                            </div>
                        </div>
                        
                        <div id="centerImage" class="rounded-lg overflow-hidden shadow-lg transition-all duration-300" style="flex-basis: 40%;">
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <p class="text-gray-500">Nenhuma imagem</p>
                            </div>
                        </div>
                        
                        <div id="rightImage" class="rounded-lg overflow-hidden opacity-60 transition-all duration-300" style="flex-basis: 30%; filter: blur(2px);">
                            <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                <p class="text-gray-500">Nenhuma imagem</p>
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Seta direita -->
                    <button id="nextBtn" class="w-[250px] h-[40px] bg-[#910039] text-white rounded-full flex items-center justify-center hover:bg-[#7A0030] transition cursor-pointer">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                
                <!-- Indicadores -->
                <div class="flex justify-center mt-6 space-x-2">
                    <div id="indicators" class="flex space-x-2">
                        <!-- Indicadores serão gerados via JavaScript -->
                    </div>
                </div>
            </div>
            
            @if(auth()->check())
            <!-- Download da galeria (file_ids do tab ativo são atualizados via JS) -->
            <div class="mt-6 border-t border-gray-200 pt-6">
                <form id="marketing-post-gallery-form" method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="inline">
                    @csrf
                    <input type="hidden" name="content_type" value="marketing">
                    <input type="hidden" name="content_id" value="{{ $campaign->id }}">
                    <input type="hidden" name="type" value="image">
                    <span id="marketing-post-gallery-file-ids">
                        @php
                            $galleryFirstType = $campaign->posts()->active()->value('type');
                            $galleryFirstImages = $galleryFirstType
                                ? $campaign->posts()->active()->where('type', $galleryFirstType)->with('files')->get()->flatMap(fn ($p) => $p->files->where('type', 'image'))
                                : collect();
                        @endphp
                        @foreach($galleryFirstImages as $gf)
                            <input type="hidden" name="file_ids[]" value="{{ $gf->id }}">
                        @endforeach
                    </span>
                    <button type="submit" class="inline-flex items-center gap-1 text-[#910039] text-xs">
                        <i class="fa-solid fa-download"></i>
                        Baixar galeria
                        <span class="text-gray-500">(<span id="marketing-post-gallery-count">{{ $galleryFirstImages->count() }}</span> imagens neste grupo)</span>
                    </button>
                </form>
            </div>
            @endif
        </div>
        @endif

        <!-- Vídeos com Tabs -->
        @if(method_exists($campaign, 'videos') && $campaign->videos()->active()->count() > 0)
        <div class="mb-12 bg-white p-8 rounded-lg shadow-sm">
            <h2 class="text-[#910039] text-2xl font-bold mb-8">Vídeos</h2>
            
            <!-- Tabs por tipo -->
            @php
                $videoTypes = $campaign->videos()->active()->pluck('type')->unique();
            @endphp
            
            @if($videoTypes->count() > 1)
            <div class="mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">
                        @foreach($videoTypes as $type)
                        <button class="video-tab-btn py-2 px-1 border-b-2 font-medium text-sm {{ $loop->first ? 'border-[#910039] text-[#910039]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}" data-type="{{ $type }}">
                            @switch($type)
                                @case('reels')
                                    Reels
                                    @break
                                @case('marketing_campaigns')
                                    Vídeos TV - Campanha
                                    @break
                                @default
                                    {{ ucfirst($type) }}
                            @endswitch
                        </button>
                        @endforeach
                    </nav>
                </div>
            </div>
            @endif
            
            <!-- Conteúdo dos vídeos -->
            <div class="flex gap-8">
                <!-- Player principal -->
                <div class="flex-grow max-w-[70%]">
                    <div class="bg-gray-900 rounded-lg">
                        <div class="relative">
                            <!-- Thumbnail do vídeo -->
                            <div class="bg-gray-800" id="videoPlayerContainer">
                                @php
                                    $firstVideoType = $campaign->videos()->active()->pluck('type')->first();
                                    $mainVideo = $campaign->videos()->active()->where('type', $firstVideoType)->with('files')->first();
                                    $mainVideoFile = $mainVideo ? $mainVideo->files->where('type', 'video')->first() : null;
                                    $mainVideoThumb = $mainVideo ? $mainVideo->files->where('type', 'image')->first() : null;
                                @endphp
                                
                                @if($mainVideo && $mainVideo->video_source === 'url' && !empty($mainVideo->video_url))
                                    @php $embedUrl = app('App\Helpers\VideoUrlHelper')::toEmbedUrl($mainVideo->video_url); @endphp
                                    {{-- Player embed (YouTube / Vimeo) --}}
                                    <div class="relative w-full" style="padding-top: 56.25%;">
                                        <iframe id="mainVideo"
                                                class="absolute inset-0 w-full h-full"
                                                src="{{ $embedUrl }}"
                                                frameborder="0"
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                allowfullscreen>
                                        </iframe>
                                    </div>
                                @elseif($mainVideoFile)
                                <video id="mainVideo" class="w-full h-96" controls>
                                    <source src="{{ url('/' . $mainVideoFile->path) }}" type="video/mp4">
                                    Seu navegador não suporta o elemento de vídeo.
                                </video>
                                @if($mainVideoFile->name)
                                <p class="text-gray-400 text-xs mt-2 px-2 pb-2 truncate" title="{{ $mainVideoFile->name }}">Ficheiro: {{ $mainVideoFile->name }}</p>
                                @endif
                                @else
                                <div class="w-full h-96 bg-gray-800 flex items-center justify-center">
                                    <p class="text-white">Nenhum vídeo disponível</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Lista de vídeos -->
                <div class="flex-shrink-0 max-w-[30%]">
                    <h3 class="text-[#910039] font-bold text-lg mb-4">Lista de Vídeos</h3>
                    <div class="space-y-0" id="videoListContainer" data-campaign-id="{{ $campaign->id }}">
                        @php
                            $firstVideoType = $campaign->videos()->active()->pluck('type')->first();
                            $videosToShow = $campaign->videos()->active()->where('type', $firstVideoType)->with('files')->get();
                        @endphp
                        
                        @foreach($videosToShow as $video)
                        @php
                            $videoFile = $video->files->where('type', 'video')->first();
                            $videoThumb = $video->files->where('type', 'image')->first();
                            $videoSource = $video->video_source ?? 'upload';
                            
                            $thumbUrl = '';
                            if ($videoThumb && file_exists(storage_path('app/' . $videoThumb->path))) {
                                $thumbUrl = '/private/' . str_replace('private/', '', $videoThumb->path);
                            } elseif ($videoSource === 'url' && !empty($video->video_url)) {
                                $thumbUrl = app('App\Helpers\VideoUrlHelper')::getThumbnailUrl($video->video_url);
                            }
                            if (empty($thumbUrl)) {
                                $thumbUrl = $campaign->thumbnail_path 
                                    ? url('/' . $campaign->thumbnail_path) 
                                    : 'https://placehold.co/600x600?text=Vídeo';
                            }
                        @endphp
                        
                        @if(($videoSource === 'url' && !empty($video->video_url)) || $videoFile)
                        <div class="video-item bg-white p-4 cursor-pointer hover:bg-gray-50 transition border-t {{ $loop->last ? 'border-b' : '' }} border-gray-200" 
                             data-video="{{ $videoSource === 'url' ? 'ext_' . $video->id : ($videoFile ? $videoFile->id : $video->id) }}" 
                             data-title="{{ $video->name }}" 
                             data-type="{{ $video->type }}">
                            <div class="flex gap-3">
                                <div class="w-20 h-12 bg-gray-300 rounded overflow-hidden flex-shrink-0">
                                    @if(!empty($thumbUrl))
                                    <img src="{{ $thumbUrl }}" alt="Thumbnail" class="w-full h-full object-cover">
                                    @else
                                    <div class="w-full h-12 bg-gray-400 flex items-center justify-center">
                                        <i class="fas fa-video text-gray-600"></i>
                                    </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-[#910039] font-semibold text-sm mb-1">{{ $video->name }}</h4>
                                    @if($videoSource === 'url')
                                    <p class="text-gray-500 text-xs truncate mb-1" title="{{ $video->video_url }}">Link: {{ $video->video_url }}</p>
                                    @elseif($videoFile && $videoFile->name)
                                    <p class="text-gray-500 text-xs truncate mb-1" title="{{ $videoFile->name }}">{{ $videoFile->name }}</p>
                                    @endif
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-gray-600 text-xs">{{ ucfirst(str_replace('_', ' ', $video->type)) }}</span>
                                        @if($videoSource !== 'url' && $videoFile)
                                        <form method="POST" action="{{ route('download.files') }}" onclick="event.stopPropagation()" onsubmit="event.stopPropagation(); return handleDownloadSubmit(event, this);" class="inline-flex items-center gap-1 shrink-0 relative z-10">
                                            @csrf
                                            <input type="hidden" name="content_type" value="marketing">
                                            <input type="hidden" name="content_id" value="{{ $campaign->id }}">
                                            <input type="hidden" name="type" value="video">
                                            <input type="hidden" name="file_ids[]" value="{{ $videoFile->id }}">
                                            <button type="submit" class="inline-flex items-center gap-1 text-[#910039] text-xs">
                                                <i class="fa-solid fa-download"></i>
                                                Download
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach
 
                        <!-- Download dos vídeos (só deste grupo / tab) -->
                        @php
                            $withFilesCount = $videosToShow->filter(fn($v) => ($v->video_source ?? 'upload') !== 'url' && $v->files->where('type', 'video')->first())->count();
                        @endphp
                        @if($withFilesCount > 0)
                        <div class="mt-6">
                            <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="inline">
                                @csrf
                                <input type="hidden" name="content_type" value="marketing">
                                <input type="hidden" name="content_id" value="{{ $campaign->id }}">
                                <input type="hidden" name="type" value="video">
                                @foreach($videosToShow as $vBatch)
                                    @php $vf = ($vBatch->video_source ?? 'upload') !== 'url' ? $vBatch->files->where('type', 'video')->first() : null; @endphp
                                    @if($vf)
                                        <input type="hidden" name="file_ids[]" value="{{ $vf->id }}">
                                    @endif
                                @endforeach
                                <button type="submit" class="inline-flex items-center gap-1 text-[#910039] text-xs">
                                    <i class="fa-solid fa-download"></i>
                                    Baixar {{ $withFilesCount }} vídeo{{ $withFilesCount > 1 ? 's' : '' }}
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        @php
            $miscItems = method_exists($campaign, 'miscellaneous')
                ? $campaign->miscellaneous()->active()->with('files')->get()
                : collect();
            $allMiscFileIds = $miscItems->flatMap(fn ($item) => $item->files->pluck('id'))->unique()->values();
            $miscSectionsMap = [];
            foreach ($miscItems as $miscItem) {
                $meta = \App\Models\CampaignMiscellaneous::marketingSectionMeta($miscItem->type);
                $sid = $meta['id'];
                if (! isset($miscSectionsMap[$sid])) {
                    $miscSectionsMap[$sid] = [
                        'title' => $meta['title'],
                        'order' => $meta['order'],
                        'items' => collect(),
                    ];
                }
                $miscSectionsMap[$sid]['items']->push($miscItem);
            }
            $miscSectionsOrdered = collect($miscSectionsMap)->sortBy('order')->values();
            $miscTotalFiles = $miscItems->sum(fn ($item) => $item->files->count());
        @endphp
        @if($miscTotalFiles > 0)
        <div class="mb-12 bg-white p-8 rounded-lg shadow-sm">
            <h2 class="text-[#910039] text-2xl font-bold mb-8">Spots, adesivos e banners</h2>

            @foreach($miscSectionsOrdered as $section)
                @php
                    $sectionFileCount = $section['items']->sum(fn ($it) => $it->files->count());
                @endphp
                @if($sectionFileCount > 0)
                <div class="mb-8 last:mb-0">
                    <h3 class="text-gray-800 font-semibold text-sm mb-3">{{ $section['title'] }}</h3>
                    <div class="space-y-0 border-t border-gray-200">
                        @foreach($section['items'] as $item)
                            @foreach($item->files as $file)
                            <div class="flex items-center justify-between gap-3 p-3 border-b border-gray-200">
                                <div class="flex items-center gap-3 min-w-0">
                                    @if($file->type === 'pdf')
                                        <i class="fas fa-file-pdf text-[#910039] text-lg flex-shrink-0"></i>
                                    @elseif($file->type === 'image')
                                        <i class="fas fa-file-image text-[#910039] text-lg flex-shrink-0"></i>
                                    @elseif($file->type === 'video')
                                        <i class="fas fa-video text-[#910039] text-lg flex-shrink-0"></i>
                                    @elseif($file->type === 'audio')
                                        <i class="fas fa-volume-high text-[#910039] text-lg flex-shrink-0"></i>
                                    @else
                                        <i class="fas fa-file text-[#910039] text-lg flex-shrink-0"></i>
                                    @endif
                                    <div class="min-w-0">
                                        <p class="text-sm text-gray-800 truncate" title="{{ $file->name }}">{{ $file->name }}</p>
                                        <p class="text-xs text-gray-500">({{ round(($file->size ?? 0) / 1024 / 1024, 1) }} MB)</p>
                                    </div>
                                </div>
                                @if(auth()->check())
                                <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="inline-flex shrink-0">
                                    @csrf
                                    <input type="hidden" name="content_type" value="marketing">
                                    <input type="hidden" name="content_id" value="{{ $campaign->id }}">
                                    <input type="hidden" name="type" value="all">
                                    <input type="hidden" name="file_ids[]" value="{{ $file->id }}">
                                    <button type="submit" class="inline-flex items-center gap-1 text-[#910039] text-xs">
                                        <i class="fa-solid fa-download"></i>
                                        Download
                                    </button>
                                </form>
                                @endif
                            </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
                @endif
            @endforeach

            @php
                $totalMiscSize = $miscItems->sum(fn ($item) => $item->files->sum('size'));
                $totalMiscSizeMB = round($totalMiscSize / 1024 / 1024, 1);
            @endphp
            @if(auth()->check())
            <div class="pt-4 border-t border-gray-200 mt-6">
                <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="w-full">
                    @csrf
                    <input type="hidden" name="content_type" value="marketing">
                    <input type="hidden" name="content_id" value="{{ $campaign->id }}">
                    <input type="hidden" name="type" value="all">
                    @foreach($allMiscFileIds as $mid)
                        <input type="hidden" name="file_ids[]" value="{{ $mid }}">
                    @endforeach
                    <button type="submit" class="inline-flex items-center gap-1 text-[#910039] text-xs">
                        <i class="fa-solid fa-download"></i>
                        Baixar todos — {{ $totalMiscSizeMB }} MB
                    </button>
                </form>
            </div>
            @endif
        </div>
        @endif  
    </div>
    
</div>

<!-- Dados para o JavaScript -->
<script>
// Preparar dados para o componente MarketingDetail
var campaignData = {
    campaignId: {{ (int) $campaign->id }},
    downloadUrl: @json(route('download.files')),
    csrfToken: @json(csrf_token()),
    postsByType: {!! json_encode(isset($postsByType) ? $postsByType : []) !!},
    videosByType: {!! json_encode(isset($videosByType) ? $videosByType : []) !!}
};

// Inicializar o componente quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    console.log('MarketingDetail: Dados da campanha:', campaignData);
    
    if (window.MarketingDetail) {
        try {
            new window.MarketingDetail(campaignData);
            console.log('MarketingDetail: Componente inicializado com sucesso');
        } catch (error) {
            console.error('MarketingDetail: Erro ao inicializar componente:', error);
        }
    } else {
        console.error('MarketingDetail: Componente não encontrado');
    }
});
</script>
@endif
@endsection 