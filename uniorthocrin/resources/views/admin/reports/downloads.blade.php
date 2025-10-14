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
        <!-- Downloads by Type -->
        @if($downloadsByType->count() > 0)
        <div class="modern-card">
            <div class="modern-card-header">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-pie text-primary-500"></i>
                    </div>
                    <div>
                        <h3 class="modern-card-title">Downloads por Tipo</h3>
                        <p class="modern-card-subtitle">Distribuição total por módulo</p>
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
                        <p class="modern-card-subtitle">Downloads por módulo</p>
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
                            <div class="text-xs text-gray-500">{{ class_basename($download->resource_type) }}</div>
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
    <div class="modern-card">
        <div class="modern-card-header">
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-filter text-primary-500"></i>
                </div>
                <div>
                    <h3 class="modern-card-title">Filtros</h3>
                    <p class="modern-card-subtitle">Filtrar downloads por critérios específicos</p>
                </div>
            </div>
        </div>
        <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-5">
            <div>
                <label for="search" class="form-label-modern">Buscar</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Nome ou email do usuário..."
                       class="form-input-modern">
            </div>
            <div>
                <label for="resource_type" class="form-label-modern">Tipo de Recurso</label>
                <select name="resource_type" id="resource_type" class="form-input-modern">
                    <option value="">Todos os tipos</option>
                    <option value="App\Models\Product" {{ request('resource_type') == 'App\Models\Product' ? 'selected' : '' }}>Produtos</option>
                    <option value="App\Models\Library" {{ request('resource_type') == 'App\Models\Library' ? 'selected' : '' }}>Biblioteca</option>
                    <option value="App\Models\Training" {{ request('resource_type') == 'App\Models\Training' ? 'selected' : '' }}>Treinamentos</option>
                    <option value="App\Models\News" {{ request('resource_type') == 'App\Models\News' ? 'selected' : '' }}>Radar</option>
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
            <div class="flex items-end space-x-3">
                <button type="submit" class="btn-modern-primary">
                    <i class="fas fa-search mr-2"></i>
                    Filtrar
                </button>
                <a href="{{ route('admin.reports.downloads') }}" class="btn-modern-secondary">
                    <i class="fas fa-times mr-2"></i>
                    Limpar
                </a>
            </div>
        </form>
    </div>

    <!-- Modern Downloads Table -->
    <div class="modern-card">
        <div class="modern-card-header">
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-download text-primary-500"></i>
                </div>
                <div>
                    <h3 class="modern-card-title">Lista de Downloads</h3>
                    <p class="modern-card-subtitle">{{ $downloads->total() }} downloads encontrados</p>
                </div>
            </div>
        </div>
        
        @if($downloads->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-user mr-2"></i>
                                Usuário
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-file mr-2"></i>
                                Recurso
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-tag mr-2"></i>
                                Tipo
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-2"></i>
                                Data do Download
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                Status
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($downloads as $download)
                    <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-blue-50 transition-all duration-200 group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-200">
                                        <span class="text-white text-lg font-bold">{{ substr($download->user->name ?? 'U', 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900 group-hover:text-primary-600 transition-colors duration-200">
                                        {{ $download->user->name ?? 'Usuário não encontrado' }}
                                    </div>
                                    <div class="text-sm text-gray-500">{{ $download->user->email ?? '-' }}</div>
                                    @if($download->user->userType ?? false)
                                    <div class="text-xs text-gray-400 mt-1">
                                        <i class="fas fa-user-tag mr-1"></i>
                                        {{ $download->user->userType->name }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 mr-3">
                                    @php
                                        $resourceType = class_basename($download->resource_type);
                                        $iconClass = match($resourceType) {
                                            'Product' => 'fas fa-box text-blue-600',
                                            'Library' => 'fas fa-book text-green-600',
                                            'Training' => 'fas fa-graduation-cap text-purple-600',
                                            'News' => 'fas fa-newspaper text-orange-600',
                                            'Campaign' => 'fas fa-bullhorn text-red-600',
                                            default => 'fas fa-file text-gray-600'
                                        };
                                        $bgClass = match($resourceType) {
                                            'Product' => 'bg-blue-100',
                                            'Library' => 'bg-green-100',
                                            'Training' => 'bg-purple-100',
                                            'News' => 'bg-orange-100',
                                            'Campaign' => 'bg-red-100',
                                            default => 'bg-gray-100'
                                        };
                                    @endphp
                                    <div class="h-10 w-10 rounded-lg {{ $bgClass }} flex items-center justify-center shadow-sm">
                                        <i class="{{ $iconClass }}"></i>
                                    </div>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900 group-hover:text-primary-600 transition-colors duration-200">
                                        {{ $download->resource->name ?? 'Recurso não encontrado' }}
                                    </div>
                                    <div class="text-sm text-gray-500 truncate max-w-xs">
                                        {{ $download->resource->description ?? 'Sem descrição' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $badgeClass = match(class_basename($download->resource_type)) {
                                    'Product' => 'bg-blue-100 text-blue-800',
                                    'Library' => 'bg-green-100 text-green-800',
                                    'Training' => 'bg-purple-100 text-purple-800',
                                    'News' => 'bg-orange-100 text-orange-800',
                                    'Campaign' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $badgeClass }}">
                                <i class="fas fa-tag mr-1"></i>
                                {{ class_basename($download->resource_type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center text-sm text-gray-500">
                                <div class="flex-shrink-0 h-8 w-8 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-calendar text-gray-600 text-xs"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $download->created_at->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $download->created_at->format('H:i') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-2 w-2 bg-green-400 rounded-full mr-2"></div>
                                <span class="text-xs font-medium text-green-600">Concluído</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <div class="mx-auto h-24 w-24 text-gray-300 mb-4">
                <i class="fas fa-download text-6xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum download encontrado</h3>
            <p class="text-gray-500">Não há downloads que correspondam aos filtros selecionados.</p>
        </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($downloads->hasPages())
    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div class="flex-1 flex justify-between sm:hidden">
            {{ $downloads->links('pagination::tailwind') }}
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Mostrando
                    <span class="font-medium">{{ $downloads->firstItem() }}</span>
                    a
                    <span class="font-medium">{{ $downloads->lastItem() }}</span>
                    de
                    <span class="font-medium">{{ $downloads->total() }}</span>
                    resultados
                </p>
            </div>
            <div>
                {{ $downloads->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection