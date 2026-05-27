@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen">
    <!-- Banner com breadcrumb e título -->
    <div class="bg-[#910039] w-full py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-white">
                <div class="text-sm mb-2">Home > Na Mídia</div>
                <h1 class="text-3xl font-bold">Na Mídia</h1>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-12">
        @if(isset($mediaByCategory))
            <!-- Listagem por categorias -->
            @forelse($mediaByCategory as $categoryGroup)
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-[#910039] mb-6">{{ $categoryGroup['category']->name }}</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 mb-8">
                    @foreach($categoryGroup['media'] as $mediaItem)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm flex flex-col">
                        <div class="w-full h-32 rounded-t-lg flex items-center justify-center overflow-hidden bg-gray-100 relative">
                            @if(!empty($mediaItem->thumbnail_path))
                                <img src="{{ url('/' . ltrim($mediaItem->thumbnail_path, '/')) }}" alt="{{ $mediaItem->name }}" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                                <span class="hidden absolute inset-0 flex items-center justify-center"><i class="fas fa-photo-video text-4xl text-gray-400"></i></span>
                            @else
                                <i class="fas fa-photo-video text-4xl text-gray-400"></i>
                            @endif
                        </div>
                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="text-[#910039] font-bold text-base mb-1">{{ $mediaItem->name }}</div>
                                <div class="text-gray-500 text-sm mb-2">{{ $mediaItem->category->name ?? 'Sem categoria' }}</div>
                            </div>
                            <div class="flex justify-between items-center mt-2">
                                <a href="{{ route('media.detail', ['profile_slug' => session('active_profile_slug'), 'id' => $mediaItem->id]) }}" class="flex items-center gap-1 text-[#910039] text-xs">
                                    <i class="fa-regular fa-eye"></i>
                                    Detalhes
                                </a>
                                @if($mediaItem->files->count() > 0 && $mediaItem->canBeDownloadedBy(auth()->user()))
                                <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="inline-flex items-center gap-1">
                                    @csrf
                                    <input type="hidden" name="content_type" value="media">
                                    <input type="hidden" name="content_id" value="{{ $mediaItem->id }}">
                                    <input type="hidden" name="type" value="all">
                                    <button type="submit" class="inline-flex items-center gap-1 text-[#910039] text-xs">
                                        <i class="fa-solid fa-download"></i>
                                        Download .zip
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="text-center text-gray-500 py-12">
                <p class="text-lg">Nenhum item disponível em Na Mídia</p>
            </div>
            @endforelse
        @elseif(isset($mediaItems))
            <!-- Listagem com filtros (paginação) -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-4">
                    <div class="text-gray-600">
                        <p>Mostrando {{ $mediaItems->firstItem() ?? 0 }}-{{ $mediaItems->lastItem() ?? 0 }} de {{ $mediaItems->total() }} itens</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 mb-8">
                @forelse($mediaItems as $mediaItem)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm flex flex-col">
                    <div class="w-full h-32 rounded-t-lg flex items-center justify-center overflow-hidden bg-gray-100 relative">
                        @if(!empty($mediaItem->thumbnail_path))
                            <img src="{{ url('/' . ltrim($mediaItem->thumbnail_path, '/')) }}" alt="{{ $mediaItem->name }}" class="w-full h-full object-cover" onerror="this.style.display='none'; this.nextElementSibling.classList.remove('hidden');">
                            <span class="hidden absolute inset-0 flex items-center justify-center"><i class="fas fa-photo-video text-4xl text-gray-400"></i></span>
                        @else
                            <i class="fas fa-photo-video text-4xl text-gray-400"></i>
                        @endif
                    </div>
                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="text-[#910039] font-bold text-base mb-1">{{ $mediaItem->name }}</div>
                            <div class="text-gray-500 text-sm mb-2">{{ $mediaItem->category->name ?? 'Sem categoria' }}</div>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <a href="{{ route('media.detail', ['profile_slug' => session('active_profile_slug'), 'id' => $mediaItem->id]) }}" class="flex items-center gap-1 text-[#910039] text-xs">
                                <i class="fa-regular fa-eye"></i>
                                Detalhes
                            </a>
                            @if($mediaItem->files->count() > 0 && $mediaItem->canBeDownloadedBy(auth()->user()))
                            <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="inline-flex items-center gap-1">
                                @csrf
                                <input type="hidden" name="content_type" value="media">
                                <input type="hidden" name="content_id" value="{{ $mediaItem->id }}">
                                <input type="hidden" name="type" value="all">
                                <button type="submit" class="inline-flex items-center gap-1 text-[#910039] text-xs">
                                    <i class="fa-solid fa-download"></i>
                                    Download .zip
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center text-gray-500 py-12">
                    @if(request('search'))
                        <p class="text-lg mb-2">Nenhum item encontrado</p>
                        <p class="text-sm">Tente ajustar os filtros de busca</p>
                    @else
                        <p class="text-lg">Nenhum item disponível</p>
                    @endif
                </div>
                @endforelse
            </div>

            <!-- Paginação -->
            @if($mediaItems->hasPages())
            <div class="flex justify-center mt-8">
                {{ $mediaItems->appends(request()->query())->links() }}
            </div>
            @endif
        @else
        <div class="text-center text-gray-500 py-12">
            <p class="text-lg">Nenhum item disponível</p>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar DownloadManager se disponível
    if (window.DownloadManager) {
        new window.DownloadManager();
    }
});
</script>
@endsection
