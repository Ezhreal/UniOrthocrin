@extends('admin.layouts.app')

@section('tour_page_key', 'admin_media_list')

@section('title', 'Na Mídia - Admin')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-modern-title">Na Mídia</h1>
            <p class="text-modern-subtitle">Gerencie todos os itens de mídia</p>
        </div>
        <div class="flex items-center space-x-3">
            <button type="button" id="btn-trigger-help-tour" class="btn-modern-secondary inline-flex items-center gap-2">
                <i class="fas fa-question-circle text-primary-500"></i>
                <span>Como usar?</span>
            </button>
            <a href="{{ route('admin.media.create') }}" id="tour-add-new-btn" class="btn-modern-primary">
                <i class="fas fa-plus mr-2"></i>
                Novo Item
            </a>
        </div>
    </div>

    <!-- Modern Filters -->
    <div id="tour-filters-card" class="modern-card">
        <div class="modern-card-header">
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-filter text-primary-500"></i>
                </div>
                <div>
                    <h3 class="modern-card-title">Filtros</h3>
                    <p class="modern-card-subtitle">Filtrar itens de mídia</p>
                </div>
            </div>
        </div>
        <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
            <div>
                <label for="search" class="form-label-modern">Buscar</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Nome do item..."
                       class="form-input-modern">
            </div>
            <div>
                <label for="category" class="form-label-modern">Categoria</label>
                <select name="category" id="category" class="form-select-modern">
                    <option value="">Todas as categorias</option>
                    @foreach(\App\Models\MediaCategory::orderBy('name')->get() as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="status" class="form-label-modern">Status</label>
                <select name="status" id="status" class="form-select-modern">
                    <option value="">Todos os status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativo</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="btn-modern-primary w-full">
                    <i class="fas fa-search mr-2"></i>
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Modern Media Table -->
    <div id="tour-items-table" class="modern-card">
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Categoria</th>
                        <th>Status</th>
                        <th>Arquivos</th>
                        <th>Criado em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($medias as $item)
                    <tr>
                        <td>
                            <div class="flex items-center">
                                <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center mr-3">
                                    <i class="fas fa-photo-video text-primary-500"></i>
                                </div>
                                <div>
                                    <div class="text-modern-body font-medium">{{ $item->name }}</div>
                                    <div class="text-modern-caption">{{ Str::limit($item->description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-modern badge-modern-primary">{{ $item->category->name ?? 'Sem categoria' }}</span>
                        </td>
                        <td>
                            <span class="badge-modern {{ $item->status === 'active' ? 'badge-modern-success' : 'badge-modern-error' }}">
                                {{ $item->status === 'active' ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center space-x-2">
                                <span class="text-modern-caption">
                                    <i class="fas fa-file mr-1"></i>{{ $item->files ? $item->files->count() : 0 }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <span class="text-modern-body">{{ $item->created_at->format('d/m/Y') }}</span>
                        </td>
                        <td>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.media.show', $item) }}" class="text-primary-500 hover:text-primary-600 transition-colors duration-200">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.media.edit', $item) }}" class="text-gray-500 hover:text-gray-600 transition-colors duration-200">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.media.destroy', $item) }}" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este item?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-error-500 hover:text-error-600 transition-colors duration-200">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8">
                            <i class="fas fa-photo-video text-gray-300 text-4xl mb-4"></i>
                            <p class="text-modern-caption">Nenhum item encontrado</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Modern Pagination -->
        @if($medias->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $medias->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
