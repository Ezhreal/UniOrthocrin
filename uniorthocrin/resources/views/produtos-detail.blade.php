@extends('layouts.app')

@section('content')
<div class="bg-[#F9F9F9] min-h-screen">
    <!-- Banner com breadcrumb e título -->
    <div class="bg-[#910039] w-full py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-white">
                <div class="text-sm mb-2">
                    <a href="{{ route('home') }}" class="hover:underline">Home</a> > 
                    <a href="{{ route('produtos.list') }}" class="hover:underline">Produtos</a> > 
                    {{ $product->name }}
                </div>
                <h1 class="text-3xl font-bold">{{ $product->name }}</h1>
                @if($product->category)
                    <p class="text-lg mt-2">{{ $product->category->name }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto py-12">
        <!-- Card de Informações Detalhadas -->
        <div class="bg-white p-6 rounded-lg shadow-sm mb-12">
            <!-- Descrição -->
            <div class="mb-4">
                <h3 class="text-[#910039] font-bold text-lg mb-2">Descrição</h3>
                @if($product->description)
                    <p class="text-gray-700 leading-relaxed">{{ $product->description }}</p>
                @else
                    <p class="text-gray-500 italic">Nenhuma descrição disponível.</p>
                @endif
            </div>
            
            <!-- Data e Tamanho -->
            <div class="flex items-center gap-6 text-sm text-gray-600 mb-6">
                <div class="flex items-center gap-2">
                    <i class="fas fa-calendar text-[#910039]"></i>
                    <span>Publicado: <strong>{{ $product->created_at->format('d/m/Y') }}</strong></span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-hdd text-[#910039]"></i>
                    <span><strong>{{ number_format(($product->images->sum('size') + $product->videos->sum('size')) / 1024 / 1024, 1) }} MB</strong> de arquivos</span>
                </div>
            </div>
            
            <!-- Botão Download -->
            <div class="pt-4 border-t border-gray-200">
                <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="w-full">
                    @csrf
                    <input type="hidden" name="content_type" value="product">
                    <input type="hidden" name="content_id" value="{{ $product->id }}">
                    <input type="hidden" name="type" value="all">
                    <button type="submit" class="inline-flex items-center gap-1 text-[#910039] text-xs">
                        <i class="fa-solid fa-download"></i>
                        Download do Produto .zip
                    </button>
                </form>
            </div>
        </div>

        <!-- Galeria de Imagens -->
        <div class="mb-12 bg-white p-8">
            <h2 class="text-[#910039] text-2xl font-bold mb-8">Galeria de Imagens</h2>
            
            <!-- Carousel de imagens -->
            <div class="relative">
                <div class="flex items-center justify-center gap-4">
                    <!-- Seta esquerda -->
                    <button id="prevBtn" style="width: 260px; height: 40px;" class="w-[250px] h-[40px] bg-[#910039] text-white rounded-full flex items-center justify-center hover:bg-[#7A0030] transition cursor-pointer">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    
                    <!-- Container das imagens do carousel -->
                    <div id="carouselContainer" class="flex gap-6 items-center">
                        <!-- Imagem esquerda (blur) -->
                        <div id="leftImage" class="rounded-lg overflow-hidden opacity-60 transition-all duration-300" style="flex-basis: 30%; filter: blur(2px);">
                            <img src="{{ $images->first()['src'] ?? 'https://placehold.co/1080' }}" alt="{{ $images->first()['alt'] ?? 'Imagem' }}" class="w-full h-auto object-cover">
                        </div>
                        
                        <!-- Imagem central (destaque) -->
                        <div id="centerImage" class="rounded-lg overflow-hidden shadow-lg transition-all duration-300" style="flex-basis: 40%;">
                            <img src="{{ $images->get(1)['src'] ?? 'https://placehold.co/1080' }}" alt="{{ $images->get(1)['alt'] ?? 'Imagem' }}" class="w-full h-auto object-cover">
                        </div>
                        
                        <!-- Imagem direita (blur) -->
                        <div id="rightImage" class="rounded-lg overflow-hidden opacity-60 transition-all duration-300" style="flex-basis: 30%; filter: blur(2px);">
                            <img src="{{ $images->get(2)['src'] ?? 'https://placehold.co/1080' }}" alt="{{ $images->get(2)['alt'] ?? 'Imagem' }}" class="w-full h-auto object-cover">
                        </div>
                    </div>
                    
                    <!-- Seta direita -->
                    <button id="nextBtn" style="width: 260px; height: 40px;" class="w-[250px] h-[40px] bg-[#910039] text-white rounded-full flex items-center justify-center hover:bg-[#7A0030] transition cursor-pointer">
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
                <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="inline-flex items-center gap-1">
                    @csrf
                    <input type="hidden" name="content_type" value="product">
                    <input type="hidden" name="content_id" value="{{ $product->id }}">
                    <input type="hidden" name="type" value="all">
                    <button type="submit" class="inline-flex items-center gap-2 text-[#910039] font-semibold hover:underline">
                        <i class="fas fa-download"></i>
                        Baixar Galeria de Imagens.zip
                        <span class="text-gray-500 text-sm">({{ $images->count() }} arquivos)</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Vídeos do produto -->
        <div class="mb-12 bg-white p-8">
            <h2 class="text-[#910039] text-2xl font-bold mb-8">Vídeos do produto</h2>
            

            <!-- Conteúdo dos vídeos -->
            <div class="flex gap-8">
                <!-- Player principal -->
                <div class="flex-grow max-w-[70%]">
                    <div class="bg-gray-900 rounded-lg">
                        <div class="relative">
                            <!-- Thumbnail do vídeo -->
                            <div class="bg-gray-800">
                                <video id="mainVideo" class="w-full h-96" controls>
                                    <source src="{{ $videos->first()['video_url'] ?? '' }}" type="video/mp4">
                                    Seu navegador não suporta o elemento de vídeo.
                                </video>
                            </div>
                            
                            <!-- Controles do vídeo -->
                            <div class="bg-gray-800 p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4">
                                        <button class="text-white hover:text-gray-300 cursor-pointer">
                                            <i class="fas fa-play"></i>
                                        </button>
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-volume-up text-white"></i>
                                            <div class="w-20 h-1 bg-gray-600 rounded-full">
                                                <div class="w-3/4 h-full bg-white rounded-full"></div>
                                            </div>
                                        </div>
                                        <span class="text-white text-sm">0:00 / 0:30</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button class="text-white hover:text-gray-300 cursor-pointer">
                                            <i class="fas fa-expand"></i>
                                        </button>
                                        <button class="text-white hover:text-gray-300 cursor-pointer">
                                            <i class="fas fa-download"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                 
                </div>
                
                <!-- Lista de vídeos -->
                <div class="flex-shrink-0 max-w-[30%]">
                    <h3 class="text-[#910039] font-bold text-lg mb-4">Lista de Reels</h3>
                    <div class="space-y-0">
                        @forelse($videos as $video)
                        <div class="video-item bg-white p-4 cursor-pointer hover:bg-gray-50 transition border-t {{ $loop->last ? 'border-b' : '' }} border-gray-200" data-video="{{ $video['id'] }}" data-title="{{ $video['title'] }}">
                            <div class="flex gap-3">
                                <div class="w-20 h-12 bg-gray-300 rounded overflow-hidden flex-shrink-0">
                                    <img src="{{ $video['thumbnail'] }}" alt="Thumbnail" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-[#910039] font-semibold text-sm mb-1">{{ $video['title'] }}</h4>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600 text-xs">Assistir</span>
                                        <a href="javascript:void(0)" class="text-[#910039] text-xs hover:underline">
                                            <i class="fas fa-download mr-1"></i>Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-gray-500 py-4">
                            <p>Nenhum vídeo disponível</p>
                        </div>
                        @endforelse

                        <!-- Download dos vídeos -->
                        @if($videos->count() > 0)
                        <div class="mt-6">
                            <a href="javascript:void(0)" 
                               data-download="videos" 
                               data-content-id="{{ $product->id }}" 
                               data-content-type="product"
                               class="inline-flex items-center gap-2 text-[#910039] font-semibold hover:underline">
                                <i class="fas fa-download"></i>
                                {{ $videos->count() }} Vídeo{{ $videos->count() > 1 ? 's' : '' }} disponíve{{ $videos->count() > 1 ? 'is' : 'l' }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM carregado - Inicializando componentes');
    
    // Preparar dados para os componentes
    const images = {!! json_encode($images) !!};
    const videos = {!! json_encode($videos) !!};
    
    console.log('Videos data:', videos);
    console.log('Images data:', images);
    console.log('Componentes disponíveis:', {
        ProductGallery: !!window.ProductGallery,
        ProductVideoPlayer: !!window.ProductVideoPlayer
    });
    
    // Inicializar Product Gallery
    if (window.ProductGallery && images.length > 0) {
        console.log('Inicializando ProductGallery');
        new window.ProductGallery('productGallery', images);
    } else {
        console.log('ProductGallery não disponível ou sem imagens');
    }
    
    // Inicializar Product Video Player
    if (window.ProductVideoPlayer && videos.length > 0) {
        console.log('Inicializando ProductVideoPlayer');
        new window.ProductVideoPlayer(videos);
    } else {
        console.log('ProductVideoPlayer não disponível ou sem vídeos');
    }
});
</script>
@endsection 