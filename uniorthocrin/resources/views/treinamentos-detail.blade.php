@extends('layouts.app')

@section('tour_page_key', 'client_training_detail')

@section('content')
<div class="bg-[#F9F9F9] min-h-screen">
    <!-- Banner com breadcrumb e título -->
    <div class="bg-[#910039] w-full py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-white">
                <div class="text-sm mb-2">
                    <a href="{{ route('home') }}" class="hover:underline">Home</a> > 
                    <a href="{{ route('treinamentos.list', ['profile_slug' => session('active_profile_slug')]) }}" class="hover:underline">Treinamentos</a> > 
                    {{ $training->name }}
                </div>
                <h1 class="text-3xl font-bold">{{ $training->name }}</h1>
                @if($training->category)
                    <p class="text-lg mt-2">{{ $training->category->name }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-12">
        <!-- Card de Informações Detalhadas -->
        <div id="tour-item-details-card" class="bg-white p-6 rounded-lg shadow-sm mb-12">
            <!-- Descrição -->
            <div class="mb-4">
                <h3 class="text-[#910039] font-bold text-lg mb-2">Descrição</h3>
                @if($training->description)
                    <p class="text-gray-700 leading-relaxed">{{ $training->description }}</p>
                @else
                    <p class="text-gray-500 italic">Nenhuma descrição disponível.</p>
                @endif
            </div>
            
            <!-- Data e Tamanho -->
            <div class="flex items-center gap-6 text-sm text-gray-600 mb-6">
                <div class="flex items-center gap-2">
                    <i class="fas fa-calendar text-[#910039]"></i>
                    <span>Publicado: <strong>{{ $training->created_at->format('d/m/Y') }}</strong></span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-hdd text-[#910039]"></i>
                    <span><strong>{{ number_format($training->files->sum('size') / 1024 / 1024, 1) }} MB</strong> de arquivos</span>
                </div>
            </div>
            
            @if($training->canBeDownloadedBy(auth()->user()))
            <!-- Botão Download -->
            <div class="pt-4 border-t border-gray-200">
                <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="w-full">
                    @csrf
                    <input type="hidden" name="content_type" value="training">
                    <input type="hidden" name="content_id" value="{{ $training->id }}">
                    <input type="hidden" name="type" value="all">
                    <button type="submit" class="inline-flex items-center gap-1 text-[#910039] text-xs">
                        <i class="fa-solid fa-download"></i>
                        Download do Treinamento .zip
                    </button>
                </form>
            </div>
            @endif
             <!-- Box 1: Galeria de Vídeos -->
        @if($videos && $videos->count() > 0)
        <div class="mb-12 bg-white p-8">
            <h2 class="text-[#910039] text-2xl font-bold mb-8">Galeria de Vídeos</h2>
            
            <!-- Conteúdo dos vídeos -->
            <div class="flex gap-8">
                <!-- Player principal -->
                <div class="flex-grow max-w-[70%]">
                    <div class="bg-gray-900 rounded-lg">
                        <div class="relative">
                            <!-- Thumbnail do vídeo -->
                            <div class="bg-gray-800" id="videoPlayerContainer">
                                @php $mainV = $videos->first(); @endphp
                                @if(!empty($mainV['video_source']) && $mainV['video_source'] === 'url' && !empty($mainV['embed_url']))
                                {{-- Player embed (YouTube / Vimeo) --}}
                                <div class="relative w-full" style="padding-top: 56.25%;">
                                    <iframe id="mainVideo"
                                            class="absolute inset-0 w-full h-full"
                                            src="{{ $mainV['embed_url'] }}"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen>
                                    </iframe>
                                </div>
                                @elseif(!empty($mainV['video_url']))
                                {{-- Player HTML5 (upload) --}}
                                <video id="mainVideo" class="w-full h-96" controls>
                                    <source src="{{ $mainV['video_url'] ?? '' }}" type="video/mp4">
                                    Seu navegador não suporta o elemento de vídeo.
                                </video>
                                @if(!empty($mainV['file_name']))
                                <p class="text-gray-400 text-xs mt-2 px-2 pb-2 truncate" title="{{ $mainV['file_name'] }}">Ficheiro: {{ $mainV['file_name'] }}</p>
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
                <div id="tour-video-playlist" class="flex-shrink-0 max-w-[30%]">
                    <h3 class="text-[#910039] font-bold text-lg mb-4">Lista de Aulas</h3>
                    <div class="video-playlist space-y-0">
                        @foreach($videos as $video)
                        @php
                            $videoSource = $video['video_source'] ?? 'upload';
                            $cleanTitle = rawurldecode(urldecode($video['title'] ?? $training->name));
                            $cleanFileName = !empty($video['file_name']) ? rawurldecode(urldecode($video['file_name'])) : null;
                            $thumbSrc = $video['thumbnail'] ?? ($training->thumbnail_path ? url('/' . ltrim($training->thumbnail_path, '/')) : 'https://placehold.co/600x600?text=Vídeo');
                        @endphp
                        <div class="video-item bg-white p-4 cursor-pointer hover:bg-gray-50 transition border-t {{ $loop->last ? 'border-b' : '' }} border-gray-200" 
                             data-video="{{ $video['id'] }}" 
                             data-title="{{ $cleanTitle }}">
                            <div class="flex gap-3">
                                <div class="w-20 h-12 bg-gray-300 rounded overflow-hidden flex-shrink-0">
                                    <img src="{{ $thumbSrc }}" alt="Thumbnail" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-[#910039] font-semibold text-sm mb-1 break-words line-clamp-2" title="{{ $cleanTitle }}">{{ $cleanTitle }}</h4>
                                    @if($cleanFileName && $cleanFileName !== $cleanTitle)
                                    <p class="text-gray-500 text-xs truncate mb-1" title="{{ $cleanFileName }}">{{ $cleanFileName }}</p>
                                    @endif
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600 text-xs">{{ $videoSource === 'url' ? 'Link Externo' : 'Assistir Aula' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Box 2: Exibição de PDF -->
        @if($training->files()->where('type', 'pdf')->count() > 0)
        <div id="tour-training-attachments" class="mb-12 bg-white p-8">
            <h2 class="text-[#910039] text-2xl font-bold mb-8">Documentos PDF</h2>
            
            <div class="training-attachments space-y-4">
                @foreach($training->files()->where('type', 'pdf')->get() as $pdf)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $pdf->name }}</h4>
                            <p class="text-gray-500 text-sm">{{ $pdf->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ $pdf->url }}" 
                           target="_blank"
                           class="text-[#910039] hover:text-[#7A0030] text-sm">
                            <i class="fas fa-eye mr-1"></i>Visualizar
                        </a>
                        @if($training->canBeDownloadedBy(auth()->user()))
                        <a href="{{ $pdf->url }}" 
                           download="{{ $pdf->name }}"
                           class="text-[#910039] hover:text-[#7A0030] text-sm">
                            <i class="fas fa-download mr-1"></i>Download
                        </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Download de todos os PDFs -->
            @if($training->files()->where('type', 'pdf')->count() > 1 && $training->canBeDownloadedBy(auth()->user()))
            <div class="mt-6 pt-6 border-t border-gray-200">
                <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="inline-flex items-center gap-2">
                    @csrf
                    <input type="hidden" name="content_type" value="training">
                    <input type="hidden" name="content_id" value="{{ $training->id }}">
                    <input type="hidden" name="type" value="pdf">
                    <button type="submit" class="inline-flex items-center gap-2 text-[#910039] font-semibold hover:underline">
                        <i class="fas fa-download"></i>
                        Baixar Todos os PDFs ({{ $training->files()->where('type', 'pdf')->count() }} arquivos)
                    </button>
                </form>
            </div>
            @endif
        </div>
        @endif

        <!-- Box 3: Outros Treinamentos da Mesma Categoria -->
        @if($training->category)
        <div class="mb-12 bg-white p-8">
            <h2 class="text-[#910039] text-2xl font-bold mb-8">Outros Treinamentos da Categoria</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                @forelse(App\Models\Training::where('training_category_id', $training->training_category_id)->where('id', '!=', $training->id)->where('status', 'active')->take(3)->get() as $otherTraining)
                @php
                    $imageFile = $otherTraining->files->firstWhere('type', 'image');
                    $otherThumbSrc = $otherTraining->thumbnail_path
                        ? url('/' . ltrim($otherTraining->thumbnail_path, '/'))
                        : ($imageFile ? $imageFile->url : 'https://placehold.co/600x600?text=Treinamento');
                @endphp
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm flex flex-col">
                    <img src="{{ $otherThumbSrc }}" alt="{{ $otherTraining->name }}" class="w-full h-32 object-cover rounded-t-lg">
                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="text-[#910039] font-bold text-base mb-1">{{ $otherTraining->name }}</div>
                            <div class="text-gray-500 text-sm mb-2">{{ $otherTraining->category->name ?? 'Sem categoria' }}</div>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <a href="{{ route('treinamentos.detail', ['profile_slug' => session('active_profile_slug'), 'id' => $otherTraining->id]) }}" class="flex items-center gap-1 text-[#910039] text-xs">
                                <i class="fa-regular fa-eye"></i>
                                Detalhes
                            </a>
                            @if($otherTraining->files->count() > 0 && $otherTraining->canBeDownloadedBy(auth()->user()))
                            <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="flex items-center gap-1 text-[#910039] text-xs">
                                @csrf
                                <input type="hidden" name="content_type" value="training">
                                <input type="hidden" name="content_id" value="{{ $otherTraining->id }}">
                                <input type="hidden" name="type" value="all">
                                <button type="submit" class="inline-flex items-center gap-1">
                                    <i class="fa-solid fa-download"></i>
                                    Download .zip
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center text-gray-500 py-8">
                    <p>Nenhum outro treinamento disponível nesta categoria</p>
                </div>
                @endforelse
            </div>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM carregado - Inicializando componentes de treinamento');
    
    // Inicializar DownloadManager se disponível
    if (window.DownloadManager) {
        new window.DownloadManager();
    }
    
    // Inicializar Product Video Player
    const videos = {!! json_encode($videos ?? []) !!};
    if (window.ProductVideoPlayer && videos.length > 0) {
        console.log('Inicializando ProductVideoPlayer para treinamento');
        new window.ProductVideoPlayer(videos);
    }
});
</script>
@endsection 