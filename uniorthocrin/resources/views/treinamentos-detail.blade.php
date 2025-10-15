@extends('layouts.app')

@section('content')
<div class="bg-[#F9F9F9] min-h-screen">
    <!-- Banner com breadcrumb e título -->
    <div class="bg-[#910039] w-full py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-white">
                <div class="text-sm mb-2">
                    <a href="{{ route('home') }}" class="hover:underline">Home</a> > 
                    <a href="{{ route('treinamentos.list') }}" class="hover:underline">Treinamentos</a> > 
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
        <div class="bg-white p-6 rounded-lg shadow-sm mb-12">
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
        </div>

        <!-- Box 1: Galeria de Vídeos -->
        @if($training->files()->where('type', 'video')->count() > 0)
        <div class="mb-12 bg-white p-8">
            <h2 class="text-[#910039] text-2xl font-bold mb-8">Galeria de Vídeos</h2>
            
            <!-- Conteúdo dos vídeos -->
            <div class="flex gap-8">
                <!-- Player principal -->
                <div class="flex-grow max-w-[70%]">
                    <div class="bg-gray-900 rounded-lg">
                        <div class="relative">
                            <!-- Thumbnail do vídeo -->
                            <div class="bg-gray-800">
                                <video id="mainVideo" class="w-full h-96" controls>
                                    <source src="{{ $training->files()->where('type', 'video')->first()->url ?? '' }}" type="video/mp4">
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
                                        @if($training->canBeDownloadedBy(auth()->user()))
                                        <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="inline-block">
                                            @csrf
                                            <input type="hidden" name="content_type" value="training">
                                            <input type="hidden" name="content_id" value="{{ $training->id }}">
                                            <input type="hidden" name="type" value="video">
                                            <button type="submit" class="text-white hover:text-gray-300 cursor-pointer">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Lista de vídeos -->
                <div class="flex-shrink-0 max-w-[30%]">
                    <h3 class="text-[#910039] font-bold text-lg mb-4">Lista de Vídeos</h3>
                    <div class="space-y-0">
                        @forelse($training->files()->where('type', 'video')->get() as $video)
                        <div class="video-item bg-white p-4 cursor-pointer hover:bg-gray-50 transition border-t {{ $loop->last ? 'border-b' : '' }} border-gray-200" data-video="{{ $video->id }}" data-title="{{ $video->name }}">
                            <div class="flex gap-3">
                                <div class="w-20 h-12 bg-gray-300 rounded overflow-hidden flex-shrink-0">
                                    <img src="{{ $training->thumbnail_path ? url('/private/' . $training->thumbnail_path) : 'https://placehold.co/600x600?text=Vídeo' }}" alt="Thumbnail" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-[#910039] font-semibold text-sm mb-1">{{ $video->name }}</h4>
                                    <div class="flex items-center justify-between">
                                        <span class="text-gray-600 text-xs">Assistir</span>
                                        @if($training->canBeDownloadedBy(auth()->user()))
                                        <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="inline-flex items-center gap-1">
                                            @csrf
                                            <input type="hidden" name="content_type" value="training">
                                            <input type="hidden" name="content_id" value="{{ $training->id }}">
                                            <input type="hidden" name="type" value="video">
                                            <button type="submit" class="text-[#910039] text-xs hover:underline inline-flex items-center gap-1">
                                                <i class="fas fa-download mr-1"></i>Download
                                            </button>
                                        </form>
                                        @endif
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
                        @if($training->files()->where('type', 'video')->count() > 0 && $training->canBeDownloadedBy(auth()->user()))
                        <div class="mt-6">
                <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="inline-flex items-center gap-2">
                    @csrf
                    <input type="hidden" name="content_type" value="training">
                    <input type="hidden" name="content_id" value="{{ $training->id }}">
                    <input type="hidden" name="type" value="video">
                    <button type="submit" class="inline-flex items-center gap-2 text-[#910039] font-semibold hover:underline">
                        <i class="fas fa-download"></i>
                        {{ $training->files()->where('type', 'video')->count() }} Vídeo{{ $training->files()->where('type', 'video')->count() > 1 ? 's' : '' }} disponíve{{ $training->files()->where('type', 'video')->count() > 1 ? 'is' : 'l' }}
                    </button>
                </form>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Box 2: Exibição de PDF -->
        @if($training->files()->where('type', 'pdf')->count() > 0)
        <div class="mb-12 bg-white p-8">
            <h2 class="text-[#910039] text-2xl font-bold mb-8">Documentos PDF</h2>
            
            <div class="space-y-4">
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
                        <a href="{{ $pdf->url }}" 
                           download="{{ $pdf->name }}"
                           class="text-[#910039] hover:text-[#7A0030] text-sm">
                            <i class="fas fa-download mr-1"></i>Download
                        </a>
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
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm flex flex-col">
                    <img src="{{ $otherTraining->thumbnail_path ? url('/private/' . $otherTraining->thumbnail_path) : ($otherTraining->files->first() ? $otherTraining->files->first()->url : 'https://placehold.co/600x600?text=Treinamento') }}" alt="{{ $otherTraining->name }}" class="w-full h-32 object-cover rounded-t-lg">
                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="text-[#910039] font-bold text-base mb-1">{{ $otherTraining->name }}</div>
                            <div class="text-gray-500 text-sm mb-2">{{ $otherTraining->category->name ?? 'Sem categoria' }}</div>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <a href="{{ route('treinamentos.detail', $otherTraining->id) }}" class="flex items-center gap-1 text-[#910039] text-xs">
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
    
         
});
</script>
@endsection 