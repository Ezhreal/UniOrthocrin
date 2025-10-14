@extends('admin.layouts.app')

@section('title', 'Séries de Produtos - Admin')

@section('content')
<div class="space-modern">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Séries de Produtos</h1>
            <p class="text-gray-600 mt-2">Gerencie as séries dos produtos por categoria</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Séries Existentes -->
        <div class="modern-card">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Séries Existentes</h2>
                
                <div class="space-y-4">
                    @forelse($series as $serie)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-layer-group text-primary-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $serie->name }}</h3>
                                    <p class="text-sm text-gray-500">
                                        @if($serie->category)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-primary-100 text-primary-800">
                                                {{ $serie->category->name }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-gray-100 text-gray-600">
                                                Sem categoria
                                            </span>
                                        @endif
                                    </p>
                                    <p class="text-sm text-gray-500">{{ $serie->products_count ?? 0 }} produtos</p>
                                </div>
                            </div>
                            <button onclick="deleteSerie({{ $serie->id }})" 
                                    class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors duration-200">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-layer-group text-gray-300 text-4xl mb-4"></i>
                            <p class="text-gray-500">Nenhuma série encontrada</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Adicionar Nova Série -->
        <div class="modern-card">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Adicionar Nova Série</h2>
                
                <form action="{{ route('admin.product-series.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="category_id" class="form-label-modern">Categoria</label>
                        <select id="category_id" 
                                name="category_id" 
                                class="form-input-modern @error('category_id') border-red-500 @enderror"
                                required>
                            <option value="">Selecione uma categoria</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="name" class="form-label-modern">Nome da Série</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="form-input-modern @error('name') border-red-500 @enderror"
                               placeholder="Ex: Damon, Invisalign, Clear Aligner..."
                               required>
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="form-label-modern">Descrição (Opcional)</label>
                        <textarea id="description" 
                                  name="description" 
                                  rows="3"
                                  class="form-input-modern @error('description') border-red-500 @enderror"
                                  placeholder="Descrição da série...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center space-x-4">
                        <button type="submit" class="btn-modern-primary">
                            <i class="fas fa-plus mr-2"></i>
                            Adicionar Série
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn-modern-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Voltar aos Produtos
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteSerie(serieId) {
    if (confirm('Tem certeza que deseja excluir esta série? Esta ação não pode ser desfeita.')) {
        fetch(`/admin/product-series/${serieId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Erro ao excluir série');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao excluir série');
        });
    }
}
</script>
@endsection
