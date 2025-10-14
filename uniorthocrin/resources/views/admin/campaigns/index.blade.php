@extends('admin.layouts.app')

@section('title', 'Campanhas - Admin')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-modern-title">Campanhas</h1>
            <p class="text-modern-subtitle">Gerencie todas as campanhas da plataforma</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.campaigns.create') }}" class="btn-modern-primary">
                <i class="fas fa-plus mr-2"></i>
                Nova Campanha
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
                    <p class="modern-card-subtitle">Filtrar campanhas</p>
                </div>
            </div>
        </div>
        <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
            <div>
                <label for="search" class="form-label-modern">Buscar</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Nome da campanha..."
                       class="form-input-modern">
            </div>
            <div>
                <label for="status" class="form-label-modern">Status</label>
                <select name="status" id="status" class="form-select-modern">
                    <option value="">Todos os status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativo</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inativo</option>
                </select>
            </div>
            <div>
                <label for="franchise_only" class="form-label-modern">Apenas Franqueados</label>
                <select name="franchise_only" id="franchise_only" class="form-select-modern">
                    <option value="">Todos</option>
                    <option value="yes" {{ request('franchise_only') == 'yes' ? 'selected' : '' }}>Sim</option>
                    <option value="no" {{ request('franchise_only') == 'no' ? 'selected' : '' }}>Não</option>
                </select>
            </div>
            <div class="flex items-end space-x-3">
                <button type="submit" class="btn-modern-primary w-full">
                    <i class="fas fa-search mr-2"></i>
                    Filtrar
                </button>
            </div>
        </form>
    </div>

    <!-- Modern Campaigns Table -->
    <div class="modern-card">
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Campanha</th>
                        <th>Status</th>
                        <th>Franqueados</th>
                        <th>Período</th>
                        <th>Conteúdo</th>
                        <th>Criado em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($campaigns as $campaign)
                    <tr>
                        <td>
                            <div class="flex items-center">
                                <div class="h-10 w-10 bg-warning-50 rounded-xl flex items-center justify-center mr-3">
                                    <i class="fas fa-bullhorn text-warning-500"></i>
                                </div>
                                <div>
                                    <div class="text-modern-body font-medium">{{ $campaign->name }}</div>
                                    <div class="text-modern-caption">{{ Str::limit($campaign->description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-modern {{ $campaign->status === 'active' ? 'badge-modern-success' : 'badge-modern-error' }}">
                                {{ $campaign->status === 'active' ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge-modern {{ $campaign->franchise_only ? 'badge-modern-warning' : 'badge-modern-gray' }}">
                                {{ $campaign->franchise_only ? 'Apenas Franqueados' : 'Todos' }}
                            </span>
                        </td>
                        <td>
                            <div class="text-modern-body">
                                <div>{{ $campaign->start_date ? $campaign->start_date->format('d/m/Y') : '-' }}</div>
                                <div class="text-modern-caption">até {{ $campaign->end_date ? $campaign->end_date->format('d/m/Y') : '-' }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center space-x-2">
                                <span class="text-modern-caption">
                                    <i class="fas fa-file-alt mr-1"></i>{{ $campaign->posts ? $campaign->posts->count() : 0 }}
                                </span>
                                <span class="text-modern-caption">
                                    <i class="fas fa-folder mr-1"></i>{{ $campaign->folders ? $campaign->folders->count() : 0 }}
                                </span>
                                <span class="text-modern-caption">
                                    <i class="fas fa-video mr-1"></i>{{ $campaign->videos ? $campaign->videos->count() : 0 }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <span class="text-modern-body">{{ $campaign->created_at->format('d/m/Y') }}</span>
                        </td>
                        <td>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.campaigns.show', $campaign) }}" class="text-primary-500 hover:text-primary-600 transition-colors duration-200">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="text-gray-500 hover:text-gray-600 transition-colors duration-200">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.campaigns.destroy', $campaign) }}" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir esta campanha?')">
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
                        <td colspan="7" class="text-center py-8">
                            <i class="fas fa-bullhorn text-gray-300 text-4xl mb-4"></i>
                            <p class="text-modern-caption">Nenhuma campanha encontrada</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Modern Pagination -->
        @if($campaigns->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $campaigns->links() }}
        </div>
        @endif
    </div>
</div>
@endsection