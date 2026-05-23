@extends('admin.layouts.app')

@section('title', 'Relatório de Acessos - Admin')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Relatório de Acessos</h1>
            <p class="text-gray-600 mt-2">Análise detalhada das visualizações de conteúdo</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.reports.export', ['type' => 'access', 'format' => 'csv']) }}" class="btn-modern-secondary">
                <i class="fas fa-download mr-2"></i>
                Exportar CSV
            </a>
            <a href="{{ route('admin.reports.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar
            </a>
        </div>
    </div>

    <!-- Modern Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="stats-card-modern hover-modern-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-modern-caption">Total de Acessos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($accessStats['total']) }}</p>
                </div>
                <div class="stats-card-icon-modern stats-card-icon-primary">
                    <i class="fas fa-eye text-xl"></i>
                </div>
            </div>
        </div>

        <div class="stats-card-modern hover-modern-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-modern-caption">Este Mês</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($accessStats['this_month']) }}</p>
                </div>
                <div class="stats-card-icon-modern stats-card-icon-warning">
                    <i class="fas fa-calendar text-xl"></i>
                </div>
            </div>
        </div>

        <div class="stats-card-modern hover-modern-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-modern-caption">Esta Semana</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($accessStats['this_week']) }}</p>
                </div>
                <div class="stats-card-icon-modern stats-card-icon-success">
                    <i class="fas fa-calendar-week text-xl"></i>
                </div>
            </div>
        </div>

        <div class="stats-card-modern hover-modern-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-modern-caption">Hoje</p>
                    <p class="text-2xl font-bold text-purple-600">{{ number_format($accessStats['today']) }}</p>
                </div>
                <div class="stats-card-icon-modern stats-card-icon-gray">
                    <i class="fas fa-calendar-day text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Access by Module -->
        @if($accessByType->count() > 0)
        <div class="modern-card">
            <div class="modern-card-header">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-pie text-primary-500"></i>
                    </div>
                    <div>
                        <h3 class="modern-card-title">Acessos por Módulo</h3>
                        <p class="modern-card-subtitle">Distribuição total de visualizações</p>
                    </div>
                </div>
            </div>
            <div class="space-modern-sm">
                <div class="space-y-4">
                    @foreach($accessByType as $type => $count)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div class="flex items-center">
                            <div class="h-3 w-3 rounded-full bg-primary-500 mr-3"></div>
                            <span class="text-modern-body font-medium">{{ $type }}</span>
                        </div>
                        <span class="text-modern-body font-bold text-primary-500">{{ number_format($count) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Access by Module (Last 30 days) -->
        @if($accessByModuleRecent->count() > 0)
        <div class="modern-card">
            <div class="modern-card-header">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 bg-green-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-green-500"></i>
                    </div>
                    <div>
                        <h3 class="modern-card-title">Últimos 30 Dias</h3>
                        <p class="modern-card-subtitle">Acessos por módulo no período</p>
                    </div>
                </div>
            </div>
            <div class="space-modern-sm">
                <div class="space-y-4">
                    @foreach($accessByModuleRecent as $type => $count)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div class="flex items-center">
                            <div class="h-3 w-3 rounded-full bg-green-500 mr-3"></div>
                            <span class="text-modern-body font-medium">{{ $type }}</span>
                        </div>
                        <span class="text-modern-body font-bold text-green-500">{{ number_format($count) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Top Users and Resources -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Top Users -->
        @if($topUsers->count() > 0)
        <div class="modern-card">
            <div class="modern-card-header">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 bg-blue-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-blue-500"></i>
                    </div>
                    <div>
                        <h3 class="modern-card-title">Usuários Mais Ativos</h3>
                        <p class="modern-card-subtitle">Top usuários (visualizações)</p>
                    </div>
                </div>
            </div>
            <div class="space-modern-sm">
                <div class="space-y-3">
                    @foreach($topUsers as $index => $userAccess)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-xs mr-3">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">{{ Str::limit($userAccess->user->name ?? 'Usuário removido', 15) }}</div>
                                <div class="text-xs text-gray-500">{{ $userAccess->user->userType->name ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <span class="text-xs font-bold text-blue-600">
                            {{ number_format($userAccess->count) }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Recent Logins (Quem Entrou) -->
        <div class="modern-card">
            <div class="modern-card-header">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 bg-green-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-sign-in-alt text-green-500"></i>
                    </div>
                    <div>
                        <h3 class="modern-card-title">Quem Entrou</h3>
                        <p class="modern-card-subtitle">Últimos acessos ao sistema</p>
                    </div>
                </div>
            </div>
            <div class="space-modern-sm">
                <div class="space-y-3">
                    @forelse($recentLogins as $login)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 mr-3">
                                <i class="fas fa-user text-xs"></i>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">{{ Str::limit($login->name, 15) }}</div>
                                <div class="text-xs text-gray-500">{{ $login->last_access->diffForHumans() }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs font-medium text-gray-900">{{ $login->last_access->format('d/m') }}</div>
                            <div class="text-xs text-gray-500">{{ $login->last_access->format('H:i') }}</div>
                        </div>
                    </div>
                    @empty
                    <p class="text-modern-caption text-center py-4">Nenhum login registrado</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Top Resources -->
        @if($topResources->count() > 0)
        <div class="modern-card">
            <div class="modern-card-header">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 bg-yellow-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-trophy text-yellow-500"></i>
                    </div>
                    <div>
                        <h3 class="modern-card-title">Conteúdos Populares</h3>
                        <p class="modern-card-subtitle">Top recursos acessados</p>
                    </div>
                </div>
            </div>
            <div class="space-modern-sm">
                <div class="space-y-3">
                    @foreach($topResources as $index => $access)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8 bg-yellow-500 rounded-full flex items-center justify-center text-white font-bold text-xs mr-3">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900 truncate max-w-[120px]">{{ $access->viewable->name ?? 'Recurso removido' }}</div>
                                <div class="text-xs text-gray-500">
                                    {{ $access->module_display_name }}
                                </div>
                            </div>                        </div>
                        <span class="text-xs font-bold text-yellow-600">
                            {{ number_format($access->access_count) }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Modern Filters -->
    <div class="modern-card mb-8">
        <div class="modern-card-header">
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-filter text-primary-500"></i>
                </div>
                <div>
                    <h3 class="modern-card-title">Filtros</h3>
                    <p class="modern-card-subtitle">Análise detalhada de acessos</p>
                </div>
            </div>
        </div>
        <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-5 p-6 pt-0">
            <div>
                <label for="search" class="form-label-modern">Buscar Usuário</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Nome ou email..."
                       class="form-input-modern">
            </div>
            <div>
                <label for="resource_type" class="form-label-modern">Módulo</label>
                <select name="resource_type" id="resource_type" class="form-select-modern">
                    <option value="">Todos os módulos</option>
                    <option value="App\Models\Product" {{ request('resource_type') == 'App\Models\Product' ? 'selected' : '' }}>Produtos</option>
                    <option value="App\Models\Library" {{ request('resource_type') == 'App\Models\Library' ? 'selected' : '' }}>Biblioteca</option>
                    <option value="App\Models\Training" {{ request('resource_type') == 'App\Models\Training' ? 'selected' : '' }}>Treinamentos</option>
                    <option value="App\Models\News" {{ request('resource_type') == 'App\Models\News' ? 'selected' : '' }}>Radar</option>
                    <option value="App\Models\Media" {{ request('resource_type') == 'App\Models\Media' ? 'selected' : '' }}>Na Mídia</option>
                    <option value="App\Models\Campaign" {{ request('resource_type') == 'App\Models\Campaign' ? 'selected' : '' }}>Campanhas</option>
                </select>
            </div>
            <div>
                <label for="date_from" class="form-label-modern">Data Inicial</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                       class="form-input-modern">
            </div>
            <div>
                <label for="date_to" class="form-label-modern">Data Final</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                       class="form-input-modern">
            </div>
            <div class="flex items-end space-x-2">
                <button type="submit" class="btn-modern-primary flex-1">
                    <i class="fas fa-search mr-2"></i>
                    Filtrar
                </button>
                <a href="{{ route('admin.reports.access') }}" class="btn-modern-secondary" title="Limpar Filtros">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Modern Access Table -->
    <div class="modern-card">
        <div class="modern-card-header">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="modern-card-title">Histórico de Acessos</h3>
                    <p class="modern-card-subtitle">{{ $accesses->total() }} registros encontrados</p>
                </div>
            </div>
        </div>
        
        @if($accesses->count() > 0)
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Usuário</th>
                        <th>Recurso</th>
                        <th>Módulo</th>
                        <th>Qtd. Acessos</th>
                        <th>Último Acesso</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accesses as $access)
                    <tr>
                        <td>
                            <div class="flex items-center">
                                <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-primary-500"></i>
                                </div>
                                <div>
                                    <div class="text-modern-body font-medium">{{ $access->user->name ?? 'Usuário removido' }}</div>
                                    <div class="text-modern-caption">{{ $access->user->email ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="max-w-xs">
                                <div class="text-modern-body font-medium truncate" title="{{ $access->viewable->name ?? 'Recurso removido' }}">
                                    {{ $access->viewable->name ?? 'Recurso removido' }}
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $moduleName = class_basename($access->viewable_type);
                                $moduleDisplayName = match($moduleName) {
                                    'Product' => 'Produtos',
                                    'Library' => 'Biblioteca',
                                    'Training' => 'Treinamentos',
                                    'News' => 'Radar',
                                    'Media' => 'Na Mídia',
                                    'Campaign' => 'Campanhas',
                                    default => $moduleName
                                };
                            @endphp
                            <span class="badge-modern badge-modern-primary">
                                {{ $moduleDisplayName }}
                            </span>
                        </td>
                        <td>
                            <div class="text-modern-body font-bold text-primary-500">{{ $access->view_count }}</div>
                        </td>
                        <td>
                            <div class="text-modern-body">{{ $access->last_viewed_at->format('d/m/Y') }}</div>
                            <div class="text-modern-caption">{{ $access->last_viewed_at->format('H:i') }}</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modern Pagination -->
        @if($accesses->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $accesses->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-12">
            <i class="fas fa-eye-slash text-gray-300 text-6xl mb-4"></i>
            <p class="text-modern-caption">Nenhum acesso encontrado</p>
        </div>
        @endif
    </div>
</div>
@endsection
