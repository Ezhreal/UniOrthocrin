@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen">
    <!-- Banner com breadcrumb e título -->
    <div class="bg-[#910039] w-full py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-white">
                <div class="text-sm mb-2">Home > Produtos</div>
                <h1 class="text-3xl font-bold">Produtos</h1>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="flex gap-8">
            <!-- Sidebar - Categorias -->
            <div class="w-64 flex-shrink-0">
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                    <h2 class="text-[#910039] font-bold text-lg mb-4">Categorias</h2>
                    <ul class="space-y-3">
                        <!-- Categoria "Todas" -->
                        <li>
                            <a href="{{ route('produtos.list') }}" class="flex items-center justify-between text-gray-700 hover:text-[#910039] transition {{ !$categoryId ? 'text-[#910039] font-semibold' : '' }}">
                                <span>Todas</span>
                                <span class="text-sm text-gray-500">({{ $totalProducts }})</span>
                            </a>
                        </li>
                        
                        <!-- Categorias dinâmicas -->
                        @forelse($categories as $category)
                        <li>
                            <a href="{{ route('produtos.list', ['category' => $category->id]) }}" class="flex items-center justify-between text-gray-700 hover:text-[#910039] transition {{ $categoryId == $category->id ? 'text-[#910039] font-semibold' : '' }}">
                                <span>{{ $category->name }}</span>
                                <span class="text-sm text-gray-500">({{ $category->products_count }})</span>
                            </a>
                        </li>
                        @empty
                        <li class="text-gray-500 text-sm">Nenhuma categoria encontrada</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <!-- Conteúdo principal -->
            <div class="flex-1">
                <!-- Contador -->
                <div class="flex justify-between items-center mb-6">
                    <div class="flex items-center gap-4">
                        <div class="text-gray-600">
                            @if($categoryId || $search)
                                <p>Mostrando {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} de {{ $filteredCount }} produtos</p>
                            @else
                                <p>Mostrando {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} de {{ $totalProducts }} produtos</p>
                            @endif
                        </div>
                        
                        <!-- Botão de download de todos os produtos -->
                        @if($products->count() > 0)
                        <a href="#" 
                           data-download="all_products" 
                           data-content-type="product"
                           data-product-ids="{{ $products->pluck('id')->implode(',') }}"
                           class="inline-flex items-center gap-2 bg-[#910039] text-white px-4 py-2 rounded-lg hover:bg-[#7a0030] transition text-sm">
                            <i class="fas fa-download"></i>
                            Baixar Todos os Produtos
                        </a>
                        @endif
                    </div>
                </div>

                <!-- Grid de produtos -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 mb-8">
                    @forelse($products as $product)
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm flex flex-col">
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
                                <a href="{{ route('produtos.detail', $product->id) }}" class="flex items-center gap-1 text-[#910039] text-xs">
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
                        @if($search || $categoryId)
                            <p class="text-lg mb-2">Nenhum produto encontrado</p>
                            <p class="text-sm">Tente ajustar os filtros de busca</p>
                        @else
                            <p class="text-lg">Nenhum produto disponível</p>
                        @endif
                    </div>
                    @endforelse
                </div>

                <!-- Paginação -->
                @if($products->hasPages())
                <div class="flex justify-center mt-8">
                    {{ $products->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 