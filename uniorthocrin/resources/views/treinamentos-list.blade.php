@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen">
    <!-- Banner com breadcrumb e título -->
    <div class="bg-[#910039] w-full py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-white">
                <div class="text-sm mb-2">Home > Treinamentos</div>
                <h1 class="text-3xl font-bold">Treinamentos</h1>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-12">
        @if(isset($trainingsByCategory))
            <!-- Listagem por categorias -->
            @forelse($trainingsByCategory as $categoryGroup)
            <div class="mb-12">
                <h2 class="text-2xl font-bold text-[#910039] mb-6">{{ $categoryGroup['category']->name ?? 'Sem Categoria' }}</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 mb-8">
                    @foreach($categoryGroup['trainings'] as $training)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm flex flex-col">
                        <img src="{{ $training->thumbnail_path ? url('/private/' . $training->thumbnail_path) : ($training->files->first() ? $training->files->first()->url : 'https://placehold.co/600x600?text=Treinamento') }}" alt="{{ $training->name }}" class="w-full h-32 object-cover rounded-t-lg">
                        <div class="p-4 flex-1 flex flex-col justify-between">
                            <div>
                                <div class="text-[#910039] font-bold text-base mb-1">{{ $training->name }}</div>
                                <div class="text-gray-500 text-sm mb-2">{{ $training->category->name ?? 'Sem categoria' }}</div>
                            </div>
                            <div class="flex justify-between items-center mt-2">
                                <a href="{{ route('treinamentos.detail', $training->id) }}" class="flex items-center gap-1 text-[#910039] text-xs">
                                    <i class="fa-regular fa-eye"></i>
                                    Detalhes
                                </a>
                                @if($training->files->count() > 0 && $training->canBeDownloadedBy(auth()->user()))
                                <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="inline-flex items-center gap-1">
                                    @csrf
                                    <input type="hidden" name="content_type" value="training">
                                    <input type="hidden" name="content_id" value="{{ $training->id }}">
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
                <p class="text-lg">Nenhum treinamento disponível</p>
            </div>
            @endforelse
        @else
            <!-- Listagem com filtros (paginação) -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center gap-4">
                    <div class="text-gray-600">
                        <p>Mostrando {{ $trainings->firstItem() ?? 0 }}-{{ $trainings->lastItem() ?? 0 }} de {{ $trainings->total() }} treinamentos</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 mb-8">
                @forelse($trainings as $training)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm flex flex-col">
                    <img src="{{ $training->thumbnail_path ? url('/private/' . $training->thumbnail_path) : ($training->files->first() ? $training->files->first()->url : 'https://placehold.co/600x600?text=Treinamento') }}" alt="{{ $training->name }}" class="w-full h-32 object-cover rounded-t-lg">
                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="text-[#910039] font-bold text-base mb-1">{{ $training->name }}</div>
                            <div class="text-gray-500 text-sm mb-2">{{ $training->category->name ?? 'Sem categoria' }}</div>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <a href="{{ route('treinamentos.detail', $training->id) }}" class="flex items-center gap-1 text-[#910039] text-xs">
                                <i class="fa-regular fa-eye"></i>
                                Detalhes
                            </a>
                            @if($training->files->count() > 0 && $training->canBeDownloadedBy(auth()->user()))
                            <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="inline-flex items-center gap-1">
                                @csrf
                                <input type="hidden" name="content_type" value="training">
                                <input type="hidden" name="content_id" value="{{ $training->id }}">
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
                        <p class="text-lg mb-2">Nenhum treinamento encontrado</p>
                        <p class="text-sm">Tente ajustar os filtros de busca</p>
                    @else
                        <p class="text-lg">Nenhum treinamento disponível</p>
                    @endif
                </div>
                @endforelse
            </div>

            <!-- Paginação -->
            @if($trainings->hasPages())
            <div class="flex justify-center mt-8">
                {{ $trainings->appends(request()->query())->links() }}
            </div>
            @endif
        @endif
    </div>
</div>
@endsection 