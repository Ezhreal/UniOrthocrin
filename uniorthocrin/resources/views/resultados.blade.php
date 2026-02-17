@extends('layouts.app')

@section('content')
<div class="bg-[#F9F9F9] min-h-screen">
    <!-- Banner com título Resultados -->
    <div class="bg-[#910039] w-full py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-white">
                <div class="text-sm mb-2">Home > Busca</div>
                <h1 class="text-3xl font-bold">Resultados</h1>
                @if($query)
                    <p class="text-white/90 mt-1">para "{{ $query }}"</p>
                @endif
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-12">
        @if(empty($results))
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-12 text-center">
                <i class="fas fa-search text-4xl text-gray-300 mb-4"></i>
                <p class="text-gray-600 text-lg">Nenhum resultado encontrado.</p>
                @if($query)
                    <p class="text-gray-500 text-sm mt-1">Tente outra palavra ou verifique as permissões do seu usuário.</p>
                @else
                    <p class="text-gray-500 text-sm mt-1">Digite uma palavra na busca do topo da página.</p>
                @endif
            </div>
        @else
            <p class="text-gray-600 mb-6">{{ count($results) }} resultado(s) encontrado(s)</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 mb-8">
                @foreach($results as $item)
                <a href="{{ $item['url'] }}" class="bg-white border border-gray-200 rounded-lg shadow-sm flex flex-col hover:shadow-md transition-shadow duration-200 group">
                    <div class="w-full h-32 rounded-t-lg flex items-center justify-center overflow-hidden bg-gray-100">
                        @if(!empty($item['thumbnail_url']))
                            <img src="{{ $item['thumbnail_url'] }}" alt="{{ $item['title'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200" loading="lazy">
                        @else
                            <i class="fas fa-file-alt text-4xl text-gray-400"></i>
                        @endif
                    </div>
                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="text-[#910039] font-bold text-base mb-1 line-clamp-2">{{ $item['title'] }}</div>
                            <span class="inline-block text-xs font-medium text-gray-500 bg-gray-100 px-2 py-0.5 rounded">{{ $item['type_label'] }}</span>
                        </div>
                        <div class="mt-3">
                            <span class="text-[#910039] text-xs font-medium group-hover:underline">Ver detalhes <i class="fas fa-arrow-right text-[10px] ml-0.5"></i></span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
