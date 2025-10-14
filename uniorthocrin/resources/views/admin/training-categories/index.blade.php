@extends('admin.layouts.app')

@section('title', 'Categorias de Treinamentos - Admin')

@section('content')
<div class="space-modern">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Categorias de Treinamentos</h1>
            <p class="text-gray-600 mt-2">Gerencie as categorias dos treinamentos</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Categorias Existentes -->
        <div class="modern-card">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Categorias Existentes</h2>
                
                <div class="space-y-4">
                    @forelse($categories as $category)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-primary-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-graduation-cap text-primary-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $category->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $category->trainings_count ?? 0 }} treinamentos</p>
                                </div>
                            </div>
                            <button onclick="deleteCategory({{ $category->id }})" 
                                    class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors duration-200">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-graduation-cap text-gray-300 text-4xl mb-4"></i>
                            <p class="text-gray-500">Nenhuma categoria encontrada</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Adicionar Nova Categoria -->
        <div class="modern-card">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Adicionar Nova Categoria</h2>
                
                <form action="{{ route('admin.training-categories.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="name" class="form-label-modern">Nome da Categoria</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="form-input-modern @error('name') border-red-500 @enderror"
                               placeholder="Ex: Técnicas, Procedimentos, Protocolos..."
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
                                  placeholder="Descrição da categoria...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center space-x-4">
                        <button type="submit" class="btn-modern-primary">
                            <i class="fas fa-plus mr-2"></i>
                            Adicionar Categoria
                        </button>
                        <a href="{{ route('admin.training.index') }}" class="btn-modern-secondary">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Voltar aos Treinamentos
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteCategory(categoryId) {
    if (confirm('Tem certeza que deseja excluir esta categoria? Esta ação não pode ser desfeita.')) {
        fetch(`/admin/training-categories/${categoryId}`, {
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
                alert('Erro ao excluir categoria');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao excluir categoria');
        });
    }
}
</script>
@endsection
