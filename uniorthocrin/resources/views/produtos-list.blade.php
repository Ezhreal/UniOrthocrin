@extends('layouts.app')

@section('content')
<!-- ARQUIVO ATUALIZADO PELO GEMINI - VERIFICAR CONTEÚDO -->
<div class="bg-white min-h-screen">
    <!-- Banner com breadcrumb e título - Baseado na estrutura da Library -->
    <div class="bg-[#910039] w-full py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-white">
                <div class="text-sm mb-2">Home > Produtos</div>
                <h1 class="text-3xl font-bold">Produtos</h1>
            </div>
        </div>
    </div>

    <!-- Conteúdo principal - Baseado na estrutura da Library, sem sidebar -->
    <div class="max-w-7xl mx-auto px-4 py-12">
        @forelse($groupedProducts as $categoryData)
            @php
                $category = $categoryData['category'];
                $products = $categoryData['products'];
                $series = $categoryData['series']; // Assumindo que o controller passa as séries
            @endphp

            <div class="mb-12">
                <h2 class="text-2xl font-bold text-[#910039] mb-6">{{ $category->name }}</h2>

                @if($series->count() > 1)
                    <!-- Tabs de Séries -->
                    <div class="flex space-x-4 mb-6" data-category-id="{{ $category->id }}">
                        <button type="button"
                                class="tab-button px-4 py-2 text-sm font-medium rounded-lg transition text-gray-700 hover:bg-gray-100" {{-- Corrigido para não ter estado ativo inicial --}}
                                data-series-id="all">
                            Todas
                        </button>
                        @foreach($series as $s)
                            <button type="button"
                                    class="tab-button px-4 py-2 text-sm font-medium rounded-lg transition text-gray-700 hover:bg-gray-100" {{-- Corrigido para não ter estado ativo inicial --}}
                                    data-series-id="{{ $s->id }}">
                                {{ $s->name }}
                            </button>
                        @endforeach
                    </div>
                @endif

                <!-- Grid de produtos - Agora com 4 colunas -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8 mb-8 product-grid" data-category-id="{{ $category->id }}">
                    @forelse($products as $product)
                        <div class="bg-white border border-gray-200 rounded-lg shadow-sm flex flex-col product-card"
                             data-series-id="{{ $product->product_series_id ?? 'none' }}"> {{-- 'none' para produtos sem série --}}
                            <img src="{{ $product->thumbnail_path ? url('/' . $product->thumbnail_path) : ($product->images->first() ? $product->images->first()->url : 'https://placehold.co/600x600?text=Produto') }}" alt="{{ $product->name }}" class="w-full h-32 object-cover rounded-t-lg">
                            <div class="p-4 flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="text-[#910039] font-bold text-base mb-1">{{ $product->name }}</div>
                                    <div class="text-gray-500 text-sm mb-2">
                                        {{ $product->category->name ?? 'Sem categoria' }}
                                        @if($product->series)
                                            ・ {{ $product->series->name }}
                                        @endif
                                    </div>
                                </div>
                                <div class="flex justify-between items-center mt-2">
                                    <a href="{{ route('produtos.detail', ['profile_slug' => session('active_profile_slug'), 'id' => $product->id]) }}" class="flex items-center gap-1 text-[#910039] text-xs">
                                        <i class="fa-regular fa-eye"></i>
                                        Detalhes
                                    </a>
                                    @if($product->canBeDownloadedBy(auth()->user()))
                                        <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="inline-flex items-center gap-1">
                                            @csrf
                                            <input type="hidden" name="content_type" value="product">
                                            <input type="hidden" name="content_id" value="{{ $product->id }}">
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
                            <p class="text-lg">Nenhum produto disponível nesta categoria.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 py-12">
                <p class="text-lg">Nenhum produto disponível.</p>
            </div>
        @endforelse
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializar DownloadManager se disponível
        if (window.DownloadManager) {
            new window.DownloadManager();
        }

        const tabButtons = document.querySelectorAll('.tab-button');

        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const categoryId = this.parentElement.dataset.categoryId; // Get category ID from parent div of tabs
                const selectedSeriesId = this.dataset.seriesId;
                const productGrid = document.querySelector(`.product-grid[data-category-id="${categoryId}"]`);
                const productCards = productGrid.querySelectorAll('.product-card');

                // Remover 'active' de todos os botões da mesma categoria
                document.querySelectorAll(`.flex.space-x-4[data-category-id="${categoryId}"] .tab-button`).forEach(btn => {
                    btn.classList.remove('bg-[#910039]', 'text-white');
                    btn.classList.add('text-gray-700', 'hover:bg-gray-100');
                });

                // Adicionar 'active' ao botão clicado
                this.classList.add('bg-[#910039]', 'text-white');
                this.classList.remove('text-gray-700', 'hover:bg-gray-100');

                productCards.forEach(card => {
                    const cardSeriesId = card.dataset.seriesId;

                    if (selectedSeriesId === 'all' || cardSeriesId === selectedSeriesId) {
                        card.style.display = 'flex'; // Mostrar o card
                    } else {
                        card.style.display = 'none'; // Esconder o card
                    }
                });
            });
        });

        // Simular clique na tab 'Todas' para todas as categorias ao carregar a página,
        // garantindo que todos os produtos sejam exibidos por padrão.
        // Also ensure that if a series is pre-selected, that tab is activated.
        document.querySelectorAll('.flex.space-x-4').forEach(tabContainer => {
            const preselectedSeries = "{{ request('series') }}"; // Check if a series is pre-selected
            let targetButton = tabContainer.querySelector(`.tab-button[data-series-id="all"]`);

            if (preselectedSeries) {
                const specificSeriesButton = tabContainer.querySelector(`.tab-button[data-series-id="${preselectedSeries}"]`);
                if (specificSeriesButton) {
                    targetButton = specificSeriesButton;
                }
            }
            if (targetButton) {
                targetButton.click();
            }
        });
    });
</script>
@endsection