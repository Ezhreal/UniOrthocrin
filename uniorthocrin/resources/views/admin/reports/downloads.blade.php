@extends('admin.layouts.app')

@section('title', 'Relatório de Downloads - Admin')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Relatório de Downloads</h1>
            <p class="text-gray-600 mt-2">Análise detalhada dos downloads da plataforma</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.reports.export', ['type' => 'downloads', 'format' => 'csv']) }}" class="btn-modern-secondary">
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="stats-card-modern hover-modern-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-modern-caption">Total de Downloads</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $downloadStats['total'] }}</p>
                </div>
                <div class="stats-card-icon-modern stats-card-icon-primary">
                    <i class="fas fa-download text-xl"></i>
                </div>
            </div>
        </div>

        <div class="stats-card-modern hover-modern-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-modern-caption">Este Mês</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $downloadStats['this_month'] }}</p>
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
                    <p class="text-2xl font-bold text-green-600">{{ $downloadStats['this_week'] }}</p>
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
                    <p class="text-2xl font-bold text-purple-600">{{ $downloadStats['today'] }}</p>
                </div>
                <div class="stats-card-icon-modern stats-card-icon-gray">
                    <i class="fas fa-calendar-day text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Downloads by Module -->
        @if($downloadsByType->count() > 0)
        <div class="modern-card">
            <div class="modern-card-header">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-pie text-primary-500"></i>
                    </div>
                    <div>
                        <h3 class="modern-card-title">Downloads por Módulo</h3>
                        <p class="modern-card-subtitle">Distribuição total acumulada</p>
                    </div>
                </div>
            </div>
            <div class="space-modern-sm">
                <div class="space-y-4">
                    @foreach($downloadsByType as $type => $count)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div class="flex items-center">
                            <div class="h-3 w-3 rounded-full bg-primary-500 mr-3"></div>
                            <span class="text-modern-body font-medium">{{ $type }}</span>
                        </div>
                        <span class="text-modern-body font-bold text-primary-500">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Downloads by Module (Last 30 days) -->
        @if($downloadsByModule->count() > 0)
        <div class="modern-card">
            <div class="modern-card-header">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 bg-green-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-green-500"></i>
                    </div>
                    <div>
                        <h3 class="modern-card-title">Últimos 30 Dias</h3>
                        <p class="modern-card-subtitle">Downloads por módulo no período</p>
                    </div>
                </div>
            </div>
            <div class="space-modern-sm">
                <div class="space-y-4">
                    @foreach($downloadsByModule as $type => $count)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div class="flex items-center">
                            <div class="h-3 w-3 rounded-full bg-green-500 mr-3"></div>
                            <span class="text-modern-body font-medium">{{ $type }}</span>
                        </div>
                        <span class="text-modern-body font-bold text-green-500">{{ $count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Top Downloads -->
    @if($topDownloads->count() > 0)
    <div class="modern-card">
        <div class="modern-card-header">
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 bg-yellow-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-trophy text-yellow-500"></i>
                </div>
                <div>
                    <h3 class="modern-card-title">Top 10 Recursos Mais Baixados</h3>
                    <p class="modern-card-subtitle">Os recursos mais populares da plataforma</p>
                </div>
            </div>
        </div>
        <div class="space-modern-sm">
            <div class="space-y-3">
                @foreach($topDownloads as $index => $download)
                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl border border-yellow-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8 bg-yellow-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-4">
                            {{ $index + 1 }}
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-gray-900">{{ $download->resource->name ?? 'Recurso não encontrado' }}</div>
                            <div class="text-xs text-gray-500">
                                @php
                                    $resourceName = class_basename($download->resource_type);
                                    echo match($resourceName) {
                                        'Product' => 'Produtos',
                                        'Library' => 'Biblioteca',
                                        'Training' => 'Treinamentos',
                                        'News' => 'Radar',
                                        'Media' => 'Na Mídia',
                                        'Campaign' => 'Campanhas',
                                        default => $resourceName
                                    };
                                @endphp
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-download mr-1"></i>
                            {{ $download->download_count }} downloads
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Modern Filters -->
    <div class="modern-card mb-8">
        <div class="modern-card-header">
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-filter text-primary-500"></i>
                </div>
                <div>
                    <h3 class="modern-card-title">Filtros</h3>
                    <p class="modern-card-subtitle">Análise detalhada de downloads</p>
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
                <label for="resource_type" class="form-label-modern">Tipo de Recurso</label>
                <select name="resource_type" id="resource_type" class="form-select-modern">
                    <option value="">Todos os tipos</option>
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
                <a href="{{ route('admin.reports.downloads') }}" class="btn-modern-secondary" title="Limpar Filtros">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Modern Downloads Table -->
    <div class="modern-card">
        <div class="modern-card-header">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="modern-card-title">Histórico de Downloads</h3>
                    <p class="modern-card-subtitle">{{ $downloads->total() }} registros encontrados</p>
                </div>
            </div>
        </div>
        
        @if($downloads->count() > 0)
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Usuário</th>
                        <th>Recurso</th>
                        <th>Módulo</th>
                        <th>Qtd. Downloads</th>
                        <th>Último Download</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($downloads as $download)
                    <tr>
                        <td>
                            <div class="flex items-center">
                                <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center mr-3">
                                    <i class="fas fa-user text-primary-500"></i>
                                </div>
                                <div>
                                    <div class="text-modern-body font-medium">{{ $download->user->name ?? 'Usuário não encontrado' }}</div>
                                    <div class="text-modern-caption">{{ $download->user->email ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="text-modern-body font-medium">{{ $download->viewable->name ?? 'Recurso não encontrado' }}</div>
                        </td>
                        <td>
                            @php
                                $moduleName = class_basename($download->viewable_type);
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
                            <div class="text-modern-body font-bold text-primary-500">{{ $download->download_count }}</div>
                        </td>
                        <td>
                            <div class="text-modern-body">{{ $download->last_viewed_at->format('d/m/Y') }}</div>
                            <div class="text-modern-caption">{{ $download->last_viewed_at->format('H:i') }}</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modern Pagination -->
        @if($downloads->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $downloads->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-12">
            <i class="fas fa-download text-gray-300 text-6xl mb-4"></i>
            <p class="text-modern-caption">Nenhum download encontrado</p>
        </div>
        @endif
    </div>
</div>
@endsection
