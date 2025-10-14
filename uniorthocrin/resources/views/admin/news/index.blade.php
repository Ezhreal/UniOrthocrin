@extends('admin.layouts.app')

@section('title', 'Radar - Admin')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-modern-title">Radar</h1>
            <p class="text-modern-subtitle">Gerencie todas as notícias do radar da plataforma</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.news.create') }}" class="btn-modern-primary">
                <i class="fas fa-plus mr-2"></i>
                Nova Notícia
            </a>
        </div>
    </div>

    <!-- Modern Filters -->
    <div class="modern-card">
        <div class="modern-card-header">
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-filter text-primary-500"></i>
                </div>
                <div>
                    <h3 class="modern-card-title">Filtros</h3>
                    <p class="modern-card-subtitle">Filtrar notícias</p>
                </div>
            </div>
        </div>
        <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
            <div>
                <label for="search" class="form-label-modern">Buscar</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Título da notícia..."
                       class="form-input-modern">
            </div>
            <div>
                <label for="category" class="form-label-modern">Categoria</label>
                <select name="category" id="category" class="form-select-modern">
                    <option value="">Todas as categorias</option>
                    @foreach(\App\Models\NewsCategory::orderBy('name')->get() as $category)
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
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Publicado</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Rascunho</option>
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

    <!-- Modern News Table -->
    <div class="modern-card">
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Notícia</th>
                        <th>Categoria</th>
                        <th>Status</th>
                        <th>Imagem</th>
                        <th>Criado em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($news as $item)
                    <tr>
                        <td>
                            <div class="flex items-center">
                                <div class="h-10 w-10 bg-error-50 rounded-xl flex items-center justify-center mr-3">
                                    <i class="fas fa-newspaper text-error-500"></i>
                                </div>
                                <div>
                                    <div class="text-modern-body font-medium">{{ $item->title }}</div>
                                    <div class="text-modern-caption">{{ Str::limit($item->content, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-modern badge-modern-primary">{{ $item->category->name ?? 'Sem categoria' }}</span>
                        </td>
                        <td>
                            <span class="badge-modern {{ $item->status === 'published' ? 'badge-modern-success' : 'badge-modern-warning' }}">
                                {{ $item->status === 'published' ? 'Publicado' : 'Rascunho' }}
                            </span>
                        </td>
                        <td>
                            @if($item->image)
                                <div class="h-10 w-10 rounded-lg overflow-hidden">
                                    <img src="{{ $item->image->thumbnail_url }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="h-10 w-10 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-image text-gray-400"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="text-modern-body">{{ $item->created_at->format('d/m/Y') }}</span>
                        </td>
                        <td>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.news.show', $item) }}" class="text-primary-500 hover:text-primary-600 transition-colors duration-200">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.news.edit', $item) }}" class="text-gray-500 hover:text-gray-600 transition-colors duration-200">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.news.destroy', $item) }}" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir esta notícia?')">
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
                            <i class="fas fa-newspaper text-gray-300 text-4xl mb-4"></i>
                            <p class="text-modern-caption">Nenhuma notícia encontrada</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Modern Pagination -->
        @if($news->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $news->links() }}
        </div>
        @endif
    </div>
</div>
@endsection