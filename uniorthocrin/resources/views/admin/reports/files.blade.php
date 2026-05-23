@extends('admin.layouts.app')

@section('title', 'Relatório de Arquivos - Admin')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Relatório de Arquivos</h1>
            <p class="text-gray-600 mt-2">Gestão e análise de arquivos da plataforma</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.reports.export', ['type' => 'files', 'format' => 'csv']) }}" class="btn-modern-secondary">
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
                    <p class="text-modern-caption">Total de Arquivos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $fileStats['total'] }}</p>
                </div>
                <div class="stats-card-icon-modern stats-card-icon-primary">
                    <i class="fas fa-file text-xl"></i>
                </div>
            </div>
        </div>

        <div class="stats-card-modern hover-modern-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-modern-caption">Tamanho Total</p>
                    <p class="text-2xl font-bold text-blue-600">{{ number_format($fileStats['total_size'] / 1024 / 1024 / 1024, 2) }} GB</p>
                </div>
                <div class="stats-card-icon-modern stats-card-icon-warning">
                    <i class="fas fa-hdd text-xl"></i>
                </div>
            </div>
        </div>

        <div class="stats-card-modern hover-modern-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-modern-caption">Novos Este Mês</p>
                    <p class="text-2xl font-bold text-green-600">{{ $fileStats['this_month'] }}</p>
                </div>
                <div class="stats-card-icon-modern stats-card-icon-success">
                    <i class="fas fa-calendar text-xl"></i>
                </div>
            </div>
        </div>

        <div class="stats-card-modern hover-modern-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-modern-caption">Novos Esta Semana</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $fileStats['this_week'] }}</p>
                </div>
                <div class="stats-card-icon-modern stats-card-icon-gray">
                    <i class="fas fa-calendar-week text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Files by Type -->
        @if($filesByType->count() > 0)
        <div class="modern-card">
            <div class="modern-card-header">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-pie text-primary-500"></i>
                    </div>
                    <div>
                        <h3 class="modern-card-title">Arquivos por Tipo</h3>
                        <p class="modern-card-subtitle">Distribuição total por tipo de arquivo</p>
                    </div>
                </div>
            </div>
            <div class="space-modern-sm">
                <div class="space-y-4">
                    @foreach($filesByType as $fileType)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div class="flex items-center">
                            <div class="h-3 w-3 rounded-full bg-primary-500 mr-3"></div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ ucfirst($fileType->file_type) }}</div>
                                <div class="text-xs text-gray-500">{{ number_format($fileType->total_size / 1024 / 1024, 2) }} MB</div>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-primary-500">{{ $fileType->count }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Files by Module (Last 30 days) -->
        @if($filesByModuleRecent->count() > 0)
        <div class="modern-card">
            <div class="modern-card-header">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 bg-green-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-green-500"></i>
                    </div>
                    <div>
                        <h3 class="modern-card-title">Últimos 30 Dias</h3>
                        <p class="modern-card-subtitle">Arquivos por módulo</p>
                    </div>
                </div>
            </div>
            <div class="space-modern-sm">
                <div class="space-y-4">
                    @foreach($filesByModuleRecent as $module => $data)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div class="flex items-center">
                            <div class="h-3 w-3 rounded-full bg-green-500 mr-3"></div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $module }}</div>
                                <div class="text-xs text-gray-500">{{ number_format($data['size'] / 1024 / 1024, 2) }} MB</div>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-green-500">{{ $data['count'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Top Files -->
    @if($topFiles->count() > 0)
    <div class="modern-card">
        <div class="modern-card-header">
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 bg-yellow-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-weight-hanging text-yellow-500"></i>
                </div>
                <div>
                    <h3 class="modern-card-title">Top 10 Arquivos Maiores</h3>
                    <p class="modern-card-subtitle">Os arquivos que ocupam mais espaço</p>
                </div>
            </div>
        </div>
        <div class="space-modern-sm">
            <div class="space-y-3">
                @foreach($topFiles as $index => $file)
                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl border border-yellow-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8 bg-yellow-500 rounded-full flex items-center justify-center text-white font-bold text-sm mr-4">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-shrink-0 h-10 w-10 mr-3">
                            @if($file->file_type === 'image')
                                <img class="h-10 w-10 rounded-lg object-cover shadow-sm" src="{{ $file->url }}" alt="{{ $file->name }}">
                            @elseif($file->file_type === 'video')
                                <div class="h-10 w-10 rounded-lg bg-gradient-to-r from-red-100 to-red-200 flex items-center justify-center shadow-sm">
                                    <i class="fas fa-video text-red-600"></i>
                                </div>
                            @elseif($file->file_type === 'pdf')
                                <div class="h-10 w-10 rounded-lg bg-gradient-to-r from-red-100 to-red-200 flex items-center justify-center shadow-sm">
                                    <i class="fas fa-file-pdf text-red-600"></i>
                                </div>
                            @elseif($file->file_type === 'pdf')
                                <div class="h-10 w-10 rounded-lg bg-gradient-to-r from-blue-100 to-blue-200 flex items-center justify-center shadow-sm">
                                    <i class="fas fa-file-word text-blue-600"></i>
                                </div>
                            @else
                                <div class="h-10 w-10 rounded-lg bg-gradient-to-r from-gray-100 to-gray-200 flex items-center justify-center shadow-sm">
                                    <i class="fas fa-file text-gray-600"></i>
                                </div>
                            @endif
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-gray-900">{{ $file->name }}</div>
                            <div class="text-xs text-gray-500">{{ class_basename($file->fileable_type) }}</div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <i class="fas fa-weight-hanging mr-1"></i>
                            {{ number_format($file->size / 1024 / 1024, 2) }} MB
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
                    <p class="modern-card-subtitle">Análise detalhada de arquivos</p>
                </div>
            </div>
        </div>
        <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-5 p-6 pt-0">
            <div>
                <label for="search" class="form-label-modern">Buscar</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Nome do arquivo..."
                       class="form-input-modern">
            </div>
            <div>
                <label for="file_type" class="form-label-modern">Tipo de Arquivo</label>
                <select name="file_type" id="file_type" class="form-select-modern">
                    <option value="">Todos os tipos</option>
                    <option value="image" {{ request('file_type') == 'image' ? 'selected' : '' }}>Imagem</option>
                    <option value="video" {{ request('file_type') == 'video' ? 'selected' : '' }}>Vídeo</option>
                    <option value="audio" {{ request('file_type') == 'audio' ? 'selected' : '' }}>Áudio</option>
                    <option value="pdf" {{ request('file_type') == 'pdf' ? 'selected' : '' }}>PDF</option>
                    <option value="spreadsheet" {{ request('file_type') == 'spreadsheet' ? 'selected' : '' }}>Planilha</option>
                    <option value="presentation" {{ request('file_type') == 'presentation' ? 'selected' : '' }}>Apresentação</option>
                </select>
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
            <div class="flex items-end space-x-2">
                <button type="submit" class="btn-modern-primary flex-1">
                    <i class="fas fa-search mr-2"></i>
                    Filtrar
                </button>
                <a href="{{ route('admin.reports.files') }}" class="btn-modern-secondary" title="Limpar Filtros">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Modern Files Table -->
    <div class="modern-card">
        <div class="modern-card-header">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="modern-card-title">Lista de Arquivos</h3>
                    <p class="modern-card-subtitle">{{ $files->total() }} arquivos encontrados</p>
                </div>
            </div>
        </div>
        
        @if($files->count() > 0)
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Arquivo</th>
                        <th>Tipo</th>
                        <th>Módulo</th>
                        <th>Tamanho</th>
                        <th>Upload</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($files as $file)
                    <tr>
                        <td>
                            <div class="flex items-center">
                                <div class="h-10 w-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3 overflow-hidden">
                                    @if($file->file_type === 'image')
                                        <img class="h-full w-full object-cover" src="{{ $file->url }}" alt="{{ $file->name }}">
                                    @elseif($file->file_type === 'video')
                                        <i class="fas fa-video text-red-500"></i>
                                    @elseif($file->file_type === 'pdf')
                                        <i class="fas fa-file-pdf text-red-500"></i>
                                    @else
                                        <i class="fas fa-file text-gray-400"></i>
                                    @endif
                                </div>
                                <div class="max-w-xs">
                                    <div class="text-modern-body font-medium truncate" title="{{ $file->name }}">{{ $file->name }}</div>
                                    <div class="text-modern-caption">{{ $file->mime_type }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            @php
                                $badgeClass = match($file->file_type) {
                                    'image' => 'badge-modern-success',
                                    'video' => 'badge-modern-error',
                                    'pdf' => 'badge-modern-error',
                                    'spreadsheet' => 'badge-modern-success',
                                    default => 'badge-modern-primary'
                                };
                            @endphp
                            <span class="badge-modern {{ $badgeClass }}">
                                {{ ucfirst($file->file_type) }}
                            </span>
                        </td>
                        <td>
                            @php
                                $moduleName = match(class_basename($file->fileable_type)) {
                                    'Product' => 'Produtos',
                                    'Library' => 'Biblioteca',
                                    'Training' => 'Treinamentos',
                                    'News' => 'Radar',
                                    'Media' => 'Na Mídia',
                                    'Campaign' => 'Campanhas',
                                    default => class_basename($file->fileable_type)
                                };
                            @endphp
                            <span class="text-modern-body">{{ $moduleName }}</span>
                        </td>
                        <td>
                            <div class="text-modern-body font-medium">{{ number_format($file->size / 1024 / 1024, 2) }} MB</div>
                        </td>
                        <td>
                            <div class="text-modern-body">{{ $file->created_at->format('d/m/Y') }}</div>
                            <div class="text-modern-caption">{{ $file->created_at->format('H:i') }}</div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Modern Pagination -->
        @if($files->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $files->links() }}
        </div>
        @endif
        @else
        <div class="text-center py-12">
            <i class="fas fa-file text-gray-300 text-6xl mb-4"></i>
            <p class="text-modern-caption">Nenhum arquivo encontrado</p>
        </div>
        @endif
    </div>
</div>
@endsection
