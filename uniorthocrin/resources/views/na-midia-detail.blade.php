@extends('layouts.app')

@section('tour_page_key', 'client_media_detail')

@section('content')
<div class="bg-[#F9F9F9] min-h-screen">
    <!-- Banner com breadcrumb e título -->
    <div class="bg-[#910039] w-full py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-white">
                <div class="text-sm mb-2">
                    <a href="{{ route('home') }}" class="hover:underline">Home</a> > 
                    <a href="{{ route('media.list', ['profile_slug' => session('active_profile_slug')]) }}" class="hover:underline">Na Mídia</a> > 
                    {{ $mediaItem->name }}
                </div>
                <h1 class="text-3xl font-bold">{{ $mediaItem->name }}</h1>
                @if($mediaItem->category)
                    <p class="text-lg mt-2">{{ $mediaItem->category->name }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-12">
        <!-- Card de Informações Detalhadas -->
        <div id="tour-item-details-card" class="bg-white p-6 rounded-lg shadow-sm mb-12">
            <!-- Descrição -->
            <div class="mb-6">
                <h3 class="text-[#910039] font-bold text-lg mb-2">Descrição</h3>
                @if($mediaItem->description)
                    <p class="text-gray-700 leading-relaxed">{{ $mediaItem->description }}</p>
                @else
                    <p class="text-gray-500 italic">Nenhuma descrição disponível.</p>
                @endif
            </div>
            
            <!-- Data e Tamanho -->
            <div class="flex flex-wrap items-center gap-6 text-sm text-gray-600 mb-6">
                <div class="flex items-center gap-2">
                    <i class="fas fa-calendar text-[#910039]"></i>
                    <span>Publicado: <strong>{{ $mediaItem->created_at->format('d/m/Y') }}</strong></span>
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-hdd text-[#910039]"></i>
                    <span><strong>{{ number_format($mediaItem->files->sum('size') / 1024 / 1024, 2) }} MB</strong> de arquivos</span>
                </div>
            </div>
            
            @if($mediaItem->files->count() > 0 && $mediaItem->canBeDownloadedBy(auth()->user()))
            <!-- Botão Download Geral -->
            <div class="pt-4 border-t border-gray-200">
                <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="w-full">
                    @csrf
                    <input type="hidden" name="content_type" value="media">
                    <input type="hidden" name="content_id" value="{{ $mediaItem->id }}">
                    <input type="hidden" name="type" value="all">
                    <button type="submit" class="btn-download-all inline-flex items-center gap-2 px-4 py-2 bg-[#910039] text-white rounded hover:bg-[#7A0030] transition text-sm font-semibold">
                        <i class="fa-solid fa-download"></i>
                        Baixar Todos os Arquivos (.zip)
                    </button>
                </form>
            </div>
            @endif
        </div>

        <!-- Lista de Arquivos -->
        <div class="bg-white p-6 rounded-lg shadow-sm">
            <h2 class="text-[#910039] text-2xl font-bold mb-6">Arquivos do Item</h2>
            
            <div class="divide-y divide-gray-100">
                @forelse($mediaItem->files as $file)
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between py-4 gap-4">
                    <div class="flex items-start gap-3">
                        @if($file->isPdf())
                            <i class="fas fa-file-pdf text-red-500 text-3xl mt-1"></i>
                        @elseif($file->isVideo())
                            <i class="fas fa-file-video text-blue-500 text-3xl mt-1"></i>
                        @elseif($file->isImage())
                            <i class="fas fa-file-image text-green-500 text-3xl mt-1"></i>
                        @elseif($file->isAudio())
                            <i class="fas fa-file-audio text-purple-500 text-3xl mt-1"></i>
                        @else
                            <i class="fas fa-file-lines text-gray-500 text-3xl mt-1"></i>
                        @endif
                        <div>
                            <h4 class="font-semibold text-gray-900 break-all">{{ $file->name }}</h4>
                            <div class="flex gap-4 text-xs text-gray-500 mt-1">
                                <span>Tipo: <strong>{{ strtoupper($file->extension ?? $file->type ?? 'Arquivo') }}</strong></span>
                                <span>Tamanho: <strong>{{ number_format($file->size / 1024 / 1024, 2) }} MB</strong></span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 self-end sm:self-auto">
                        @if($file->isPdf() || $file->isImage() || $file->isVideo())
                        <a href="{{ $file->url }}" 
                           target="_blank"
                           class="inline-flex items-center gap-1 text-[#910039] hover:underline text-sm font-semibold">
                            <i class="fas fa-eye"></i> Visualizar
                        </a>
                        @endif
                        
                        @if($mediaItem->canBeDownloadedBy(auth()->user()))
                        <a href="{{ $file->url }}" 
                           download="{{ $file->name }}"
                           class="btn-download-single inline-flex items-center gap-1 text-[#910039] hover:underline text-sm font-semibold">
                            <i class="fas fa-download"></i> Download
                        </a>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-8">
                    <p>Nenhum arquivo disponível neste item.</p>
                </div>
                @endforelse
            </div>
        </div>
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
