@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen">
    <!-- Banner com breadcrumb e título -->
    <div class="bg-[#910039] w-full py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-white">
                <div class="text-sm mb-2">Home > Radar</div>
                <h1 class="text-3xl font-bold">Radar</h1>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-12">
        @if(isset($news))
        <!-- Contador -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center gap-4">
                <div class="text-gray-600">
                    <p>Mostrando {{ $news->firstItem() ?? 0 }}-{{ $news->lastItem() ?? 0 }} de {{ $news->total() }} notícias</p>
                </div>
            </div>
        </div>

        <!-- Grid de notícias -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 mb-8">
            @forelse($news as $newsItem)
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm flex flex-col">
                <div class="w-full h-full bg-gray-100 rounded-t-lg overflow-hidden news-image-container" data-title="{{ $newsItem->title }}">
                    @if($newsItem->mainFile)
                        <a href="{{ $newsItem->mainFile->url }}" 
                           data-title="{{ $newsItem->title }}"
                           class="block w-full h-full news-image-link">
                            <img src="{{ $newsItem->mainFile->url }}" 
                                 alt="{{ $newsItem->title }}" 
                                 class="w-full h-full object-cover news-image cursor-pointer hover:scale-105 transition-transform duration-200"
                                 loading="lazy">
                        </a>
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-newspaper text-4xl text-gray-400"></i>
                        </div>
                    @endif
                </div>
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="text-[#910039] font-bold text-base mb-1 line-clamp-2">{{ $newsItem->title }}</div>
                        <div class="text-gray-500 text-sm mb-2">{{ $newsItem->published_at ? $newsItem->published_at->format('d/m/Y') : 'Data não informada' }}</div>
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        @if($newsItem->mainFile)
                        <a href="{{ $newsItem->mainFile->url }}" 
                           class="flex items-center gap-1 text-[#910039] text-xs news-view-btn hover:bg-[#910039] hover:text-white px-2 py-1 rounded transition-colors duration-200"
                           data-title="{{ $newsItem->title }}">
                            <i class="fa-regular fa-eye"></i>
                            Visualizar
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center text-gray-500 py-12">
                @if(request('search'))
                    <p class="text-lg mb-2">Nenhuma notícia encontrada</p>
                    <p class="text-sm">Tente ajustar os filtros de busca</p>
                @else
                    <p class="text-lg">Nenhuma notícia disponível</p>
                @endif
            </div>
            @endforelse
        </div>

        <!-- Paginação -->
        @if($news->hasPages())
        <div class="flex justify-center mt-8">
            {{ $news->appends(request()->query())->links() }}
        </div>
        @endif
        @else
        <div class="text-center text-gray-500 py-12">
            <p class="text-lg">Nenhuma notícia disponível</p>
        </div>
        @endif
    </div>
</div>

<!-- Modal para visualização de imagens -->
<div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <!-- Botão fechar -->
        <button id="closeModal" class="absolute -top-10 right-0 text-white hover:text-gray-300 text-2xl font-bold z-10">
            <i class="fas fa-times"></i>
        </button>
        
        <!-- Imagem -->
        <img id="modalImage" src="" alt="" class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl">
        
        <!-- Título -->
        <div id="modalTitle" class="text-white text-center mt-4 text-lg font-semibold"></div>
        
        <!-- Navegação -->
        <button id="prevImage" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 text-3xl">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button id="nextImage" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 text-3xl">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');
    const closeModal = document.getElementById('closeModal');
    const prevImage = document.getElementById('prevImage');
    const nextImage = document.getElementById('nextImage');
    
    let currentImages = [];
    let currentIndex = 0;
    
    // Função para abrir modal
    function openModal(imageUrl, title, images = [], index = 0) {
        modalImage.src = imageUrl;
        modalTitle.textContent = title;
        currentImages = images;
        currentIndex = index;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        
        // Atualizar navegação
        updateNavigation();
    }
    
    // Função para fechar modal
    function closeModalFunc() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
    
    // Função para navegar
    function navigateImage(direction) {
        if (currentImages.length === 0) return;
        
        if (direction === 'next') {
            currentIndex = (currentIndex + 1) % currentImages.length;
        } else {
            currentIndex = currentIndex === 0 ? currentImages.length - 1 : currentIndex - 1;
        }
        
        const image = currentImages[currentIndex];
        modalImage.src = image.url;
        modalTitle.textContent = image.title;
        updateNavigation();
    }
    
    // Atualizar navegação
    function updateNavigation() {
        if (currentImages.length <= 1) {
            prevImage.style.display = 'none';
            nextImage.style.display = 'none';
        } else {
            prevImage.style.display = 'block';
            nextImage.style.display = 'block';
        }
    }
    
    // Event listeners
    closeModal.addEventListener('click', closeModalFunc);
    prevImage.addEventListener('click', () => navigateImage('prev'));
    nextImage.addEventListener('click', () => navigateImage('next'));
    
    // Fechar com ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModalFunc();
        }
    });
    
    // Fechar clicando fora da imagem
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModalFunc();
        }
    });
    
    // Coletar todas as imagens da página
    const newsImages = [];
    document.querySelectorAll('.news-image-container').forEach((container, index) => {
        const img = container.querySelector('img');
        const title = container.getAttribute('data-title');
        if (img && img.src) {
            newsImages.push({
                url: img.src,
                title: title || 'Notícia'
            });
        }
    });
    
    // Adicionar eventos aos botões de visualizar
    document.querySelectorAll('.news-view-btn').forEach((btn, index) => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const imageUrl = this.getAttribute('href');
            const title = this.getAttribute('data-title') || 'Notícia';
            openModal(imageUrl, title, newsImages, index);
        });
    });
    
    // Adicionar eventos às imagens
    document.querySelectorAll('.news-image-link').forEach((link, index) => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const imageUrl = this.getAttribute('href');
            const title = this.getAttribute('data-title') || 'Notícia';
            openModal(imageUrl, title, newsImages, index);
        });
    });
});
</script>
@endsection 