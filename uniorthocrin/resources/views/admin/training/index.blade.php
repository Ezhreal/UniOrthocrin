@extends('admin.layouts.app')

@section('title', 'Treinamentos - Admin')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-modern-title">Treinamentos</h1>
            <p class="text-modern-subtitle">Gerencie todos os treinamentos da plataforma</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.training.create') }}" class="btn-modern-primary">
                <i class="fas fa-plus mr-2"></i>
                Novo Treinamento
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
                    <p class="modern-card-subtitle">Filtrar treinamentos</p>
                </div>
            </div>
        </div>
        <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
            <div>
                <label for="search" class="form-label-modern">Buscar</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Nome do treinamento..."
                       class="form-input-modern">
            </div>
            <div>
                <label for="category" class="form-label-modern">Categoria</label>
                <select name="category" id="category" class="form-select-modern">
                    <option value="">Todas as categorias</option>
                    @foreach(\App\Models\TrainingCategory::orderBy('name')->get() as $category)
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

    <!-- Modern Training Table -->
    <div class="modern-card">
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Treinamento</th>
                        <th>Categoria</th>
                        <th>Status</th>
                        <th>Conteúdo</th>
                        <th>Criado em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trainings as $training)
                    <tr>
                        <td>
                            <div class="flex items-center">
                                <div class="h-10 w-10 bg-success-50 rounded-xl flex items-center justify-center mr-3">
                                    <i class="fas fa-graduation-cap text-success-500"></i>
                                </div>
                                <div>
                                    <div class="text-modern-body font-medium">{{ $training->name }}</div>
                                    <div class="text-modern-caption">{{ Str::limit($training->description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-modern badge-modern-primary">{{ $training->category->name ?? 'Sem categoria' }}</span>
                        </td>
                        <td>
                            <span class="badge-modern {{ $training->status === 'active' ? 'badge-modern-success' : 'badge-modern-error' }}">
                                {{ $training->status === 'active' ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td>
                            <div class="flex items-center space-x-2">
                                <span class="text-modern-caption">
                                    <i class="fas fa-video mr-1"></i>{{ $training->videos ? $training->videos->count() : 0 }}
                                </span>
                                <span class="text-modern-caption">
                                    <i class="fas fa-file mr-1"></i>{{ $training->files ? $training->files->count() : 0 }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <span class="text-modern-body">{{ $training->created_at->format('d/m/Y') }}</span>
                        </td>
                        <td>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.training.show', $training) }}" class="text-primary-500 hover:text-primary-600 transition-colors duration-200">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.training.edit', $training) }}" class="text-gray-500 hover:text-gray-600 transition-colors duration-200">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.training.destroy', $training) }}" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este treinamento?')">
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
                            <i class="fas fa-graduation-cap text-gray-300 text-4xl mb-4"></i>
                            <p class="text-modern-caption">Nenhum treinamento encontrado</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Modern Pagination -->
        @if($trainings->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $trainings->links() }}
        </div>
        @endif
    </div>
</div>
@endsection