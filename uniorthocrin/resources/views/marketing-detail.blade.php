@extends('layouts.app')

@section('content')
<div class="bg-[#F9F9F9] min-h-screen">
    <!-- Banner com breadcrumb e título -->
    <div class="bg-[#910039] w-full py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-white">
                <div class="text-sm mb-2">
                    <a href="{{ route('home') }}" class="hover:underline">Home</a> > 
                    <a href="{{ route('marketing.list') }}" class="hover:underline">Marketing</a> > 
                    {{ $campaign->name }}
                </div>
                <h1 class="text-3xl font-bold">{{ $campaign->name }}</h1>
                @if($campaign->description)
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
                            @if($campaign->start_date && $campaign->end_date)
                                {{ $campaign->start_date->format('d/m/Y') }} a {{ $campaign->end_date->format('d/m/Y') }}
                            @elseif($campaign->start_date)
                                A partir de {{ $campaign->start_date->format('d/m/Y') }}
                            @elseif($campaign->end_date)
                                Até {{ $campaign->end_date->format('d/m/Y') }}
                            @else
                                Vigência indefinida
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <span class="text-gray-600 text-sm">Status:</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $campaign->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $campaign->status === 'active' ? 'Ativa' : 'Inativa' }}
                        </span>
                    </div>
                    
                    @php
                        $totalSize = 0;
                        $totalFiles = 0;
                        
                        // Calcular tamanho total dos arquivos
                        foreach($campaign->posts()->with('files')->get() as $post) {
                            foreach($post->files as $file) {
                                $totalSize += $file->size;
                                $totalFiles++;
                            }
                        }
                        foreach($campaign->folders()->with('files')->get() as $folder) {
                            foreach($folder->files as $file) {
                                $totalSize += $file->size;
                                $totalFiles++;
                            }
                        }
                        foreach($campaign->videos()->with('files')->get() as $video) {
                            foreach($video->files as $file) {
                                $totalSize += $file->size;
                                $totalFiles++;
                            }
                        }
                        foreach($campaign->miscellaneous()->with('files')->get() as $misc) {
                            foreach($misc->files as $file) {
                                $totalSize += $file->size;
                                $totalFiles++;
                            }
                        }
                        
                        $totalSizeMB = round($totalSize / 1024 / 1024, 2);
                    @endphp
                    
                    <div>
                        <span class="text-gray-600 text-sm">Arquivos:</span>
                        <p class="text-[#910039] font-semibold">{{ $totalSizeMB }} GB de arquivos disponíveis</p>
                    </div>
                </div>
                
                <!-- Download da Campanha -->
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="w-full">
                        @csrf
                        <input type="hidden" name="content_type" value="marketing">
                        <input type="hidden" name="content_id" value="{{ $campaign->id }}">
                        <input type="hidden" name="type" value="all">
                        <button type="submit" class="inline-flex items-center gap-1 text-[#910039] text-xs">
                            <i class="fa-solid fa-download"></i>
                            Download da Campanha .zip
                        </button>
                    </form>
                </div>
            </div>

            <!-- Box 2: Folhetos -->
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h2 class="text-[#910039] text-xl font-bold mb-4">Folhetos</h2>
                
                @if($campaign->folders()->active()->count() > 0)
                <div class="space-y-3 mb-4">
                    @foreach($campaign->folders()->active()->with('files')->get() as $folder)
                    <div class="flex items-center justify-between p-3 border-t border-gray-200 {{ $loop->last ? 'border-b' : '' }}">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-file-pdf text-[#910039] text-lg"></i>
                            <div>
                                <p class="font-medium text-gray-800">{{ strtoupper($folder->state) }}</p>
                                <p class="text-sm text-gray-600">Arquivo .pdf ({{ round($folder->files->sum('size') / 1024 / 1024, 1) }}mb)</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="inline-flex items-center gap-1">
                            @csrf
                            <input type="hidden" name="content_type" value="marketing">
                            <input type="hidden" name="content_id" value="{{ $campaign->id }}">
                            <input type="hidden" name="type" value="image">
                            <button type="submit" class="inline-flex items-center gap-1 text-[#910039] text-xs">
                                <i class="fa-solid fa-download"></i>
                                Download .zip
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
                
                <!-- Download todos os folhetos -->
                @php
                    $totalFolderSize = $campaign->folders()->active()->with('files')->get()->sum(function($folder) {
                        return $folder->files->sum('size');
                    });
                    $totalFolderSizeMB = round($totalFolderSize / 1024 / 1024, 1);
                @endphp
                <div class="pt-4">
                    <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="w-full">
                        @csrf
                        <input type="hidden" name="content_type" value="marketing">
                        <input type="hidden" name="content_id" value="{{ $campaign->id }}">
                        <input type="hidden" name="type" value="pdf">
                        <button type="submit" class="inline-flex items-center gap-1 text-[#910039] text-xs">
                            <i class="fa-solid fa-download"></i>
                            Baixar todos folhetos .zip {{ $totalFolderSizeMB }} MB
                        </button>
                    </form>
                  
                </div>
                @else
                <p class="text-gray-500 text-center py-8">Nenhum folheto disponível</p>
                @endif
            </div>
        </div>

        <!-- Posts - Galeria de Imagens com Tabs -->
        @if($campaign->posts()->active()->count() > 0)
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
                                    Stories MG/SP
                                    @break
                                @case('stories_df_es')
                                    Stories DF/ES
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
            
            <!-- Download da galeria -->
            <div class="mt-6 border-t border-gray-200 pt-6">
                <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="inline">
                    @csrf
                    <input type="hidden" name="content_type" value="marketing">
                    <input type="hidden" name="content_id" value="{{ $campaign->id }}">
                    <input type="hidden" name="type" value="image">
                        <button type="submit" class="inline-flex items-center gap-1 text-[#910039] text-xs">
                            <i class="fa-solid fa-download"></i>
                            Baixar Galeria de Posts.zip
                            <span class="text-gray-500 text-sm">({{ $campaign->posts()->active()->count() }} posts)</span>
                        </button>
                </form>
            </div>
        </div>
        @endif

        <!-- Vídeos com Tabs -->
        @if($campaign->videos()->active()->count() > 0)
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
                                    Campanhas
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
                            <div class="bg-gray-800">
                                @php
                                    $firstVideoType = $campaign->videos()->active()->pluck('type')->first();
                                    $mainVideo = $campaign->videos()->active()->where('type', $firstVideoType)->with('files')->first();
                                    $mainVideoFile = $mainVideo ? $mainVideo->files->where('type', 'video')->first() : null;
                                    $mainVideoThumb = $mainVideo ? $mainVideo->files->where('type', 'image')->first() : null;
                                @endphp
                                
                                @if($mainVideoFile)
                                <video id="mainVideo" class="w-full h-96" controls>
                                    <source src="{{ url('/' . $mainVideoFile->path) }}" type="video/mp4">
                                    Seu navegador não suporta o elemento de vídeo.
                                </video>
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
                    <div class="space-y-0" id="videoListContainer">
                        @php
                            $firstVideoType = $campaign->videos()->active()->pluck('type')->first();
                            $videosToShow = $campaign->videos()->active()->where('type', $firstVideoType)->with('files')->get();
                        @endphp
                        
                        @foreach($videosToShow as $video)
                        @php
                            $videoFile = $video->files->where('type', 'video')->first();
                            $videoThumb = $video->files->where('type', 'image')->first();
                        @endphp
                        
                        @if($videoFile)
                        <div class="video-item bg-white p-4 cursor-pointer hover:bg-gray-50 transition border-t {{ $loop->last ? 'border-b' : '' }} border-gray-200" data-video="{{ $videoFile->id }}" data-title="{{ $video->name }}" data-type="{{ $video->type }}">
                            <div class="flex gap-3">
                                <div class="w-20 h-12 bg-gray-300 rounded overflow-hidden flex-shrink-0">
                                    @if($videoThumb && file_exists(storage_path('app/' . $videoThumb->path)))
                                    <img src="/private/{{ str_replace('private/', '', $videoThumb->path) }}" alt="Thumbnail" class="w-full h-auto object-cover">
                                    @else
                                    <div class="w-full h-12 bg-gray-400 flex items-center justify-center">
                                        <i class="fas fa-video text-gray-600"></i>
                                    </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-[#910039] font-semibold text-sm mb-1">{{ $video->name }}</h4>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600 text-xs">{{ ucfirst($video->type) }}</span>
                                        <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="inline-flex items-center gap-1">
                                            @csrf
                                            <input type="hidden" name="content_type" value="marketing">
                                            <input type="hidden" name="content_id" value="{{ $campaign->id }}">
                                            <input type="hidden" name="type" value="video">
                                            <button type="submit" class="inline-flex items-center gap-1">
                                                <i class="fa-solid fa-download"></i>
                                                Download
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                        @endforeach

                        <!-- Download dos vídeos -->
                        @if($videosToShow->count() > 0)
                        <div class="mt-6">
                            <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="inline">
                                @csrf
                                <input type="hidden" name="content_type" value="marketing">
                                <input type="hidden" name="content_id" value="{{ $campaign->id }}">
                                <input type="hidden" name="type" value="video">
                                <button type="submit" class="inline-flex items-center gap-1">
                                    <i class="fa-solid fa-download"></i>
                                    {{ $videosToShow->count() }} Vídeo{{ $videosToShow->count() > 1 ? 's' : '' }} disponíve{{ $videosToShow->count() > 1 ? 'is' : 'l' }}
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        @if($campaign->miscellaneous()->active()->count() > 0)
        <div class="mb-12 bg-white p-8 rounded-lg shadow-sm">
        <div class="space-y-3 mb-4">
                    @foreach($campaign->miscellaneous()->active()->with('files')->get() as $item)
                    <div class="flex items-center justify-between p-3 border-t border-gray-200 {{ $loop->last ? 'border-b' : '' }}">
                        <div class="flex items-center gap-3">
                            @if($item->type === 'audio')
                                <i class="fas fa-play text-[#910039] text-lg"></i>
                            @elseif($item->type === 'pdf')
                                <i class="fas fa-file-pdf text-[#910039] text-lg"></i>
                            @elseif($item->type === 'video')
                                <i class="fas fa-video text-[#910039] text-lg"></i>
                            @else
                                <i class="fas fa-file text-[#910039] text-lg"></i>
                            @endif
                            <div>
                                <p class="font-medium text-gray-800">{{ strtoupper($item->type) }}</p>
                                <p class="text-sm text-gray-600">Arquivo.{{ $item->files->first() ? $item->files->first()->extension : 'pdf' }} ({{ round($item->files->sum('size') / 1024 / 1024, 1) }}mb)</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="inline-flex items-center gap-1">
                            @csrf
                            <input type="hidden" name="content_type" value="marketing">
                            <input type="hidden" name="content_id" value="{{ $campaign->id }}">
                            <input type="hidden" name="type" value="all">
                            <button type="submit" class="inline-flex items-center gap-1 text-[#910039] text-xs">
                                <i class="fa-solid fa-download"></i>
                                Download .zip
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
                
                <!-- Download todos os diversos -->
                @php
                    $totalMiscSize = $campaign->miscellaneous()->active()->with('files')->get()->sum(function($item) {
                        return $item->files->sum('size');
                    });
                    $totalMiscSizeMB = round($totalMiscSize / 1024 / 1024, 1);
                    $totalMiscFiles = $campaign->miscellaneous()->active()->with('files')->get()->sum(function($item) {
                        return $item->files->count();
                    });
                @endphp
                <div class="pt-4">
                    <p class="text-[#910039] text-sm font-medium text-center mb-2"></p>
                    <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="w-full">
                        @csrf
                        <input type="hidden" name="content_type" value="marketing">
                        <input type="hidden" name="content_id" value="{{ $campaign->id }}">
                        <input type="hidden" name="type" value="all">
                        <button type="submit" class="inline-flex items-center gap-1 text-[#910039] text-xs">
                            <i class="fa-solid fa-download"></i>
                            Download .zip {{ $totalMiscSizeMB }} MB
                        </button>
                    </form>
                   
                </div>
        </div>


        @endif  
    </div>
    
</div>

<!-- Dados para o JavaScript -->
<script>
// Preparar dados para o componente MarketingDetail
const campaignData = {
    postsByType: @json($postsByType),
    videosByType: @json($videosByType)
};

// Inicializar o componente quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    if (window.MarketingDetail) {
        new window.MarketingDetail(campaignData);
    } else {
        console.error('MarketingDetail component not found');
    }
});
</script>
@endsection 