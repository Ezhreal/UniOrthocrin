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
                            @elseif($file->file_type === 'document')
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

    <!-- Filters -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title">Filtros</h3>
        </div>
        <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-5">
            <div>
                <label for="search" class="form-label">Buscar</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Nome do arquivo..."
                       class="form-input">
            </div>
            <div>
                <label for="file_type" class="form-label">Tipo de Arquivo</label>
                <select name="file_type" id="file_type" class="form-select">
                    <option value="">Todos os tipos</option>
                    <option value="image" {{ request('file_type') == 'image' ? 'selected' : '' }}>Imagem</option>
                    <option value="video" {{ request('file_type') == 'video' ? 'selected' : '' }}>Vídeo</option>
                    <option value="audio" {{ request('file_type') == 'audio' ? 'selected' : '' }}>Áudio</option>
                    <option value="pdf" {{ request('file_type') == 'pdf' ? 'selected' : '' }}>PDF</option>
                    <option value="document" {{ request('file_type') == 'document' ? 'selected' : '' }}>Documento</option>
                    <option value="spreadsheet" {{ request('file_type') == 'spreadsheet' ? 'selected' : '' }}>Planilha</option>
                    <option value="presentation" {{ request('file_type') == 'presentation' ? 'selected' : '' }}>Apresentação</option>
                    <option value="other" {{ request('file_type') == 'other' ? 'selected' : '' }}>Outros</option>
                </select>
            </div>
            <div>
                <label for="resource_type" class="form-label">Módulo</label>
                <select name="resource_type" id="resource_type" class="form-select">
                    <option value="">Todos os módulos</option>
                    <option value="App\Models\Product" {{ request('resource_type') == 'App\Models\Product' ? 'selected' : '' }}>Produtos</option>
                    <option value="App\Models\Library" {{ request('resource_type') == 'App\Models\Library' ? 'selected' : '' }}>Biblioteca</option>
                    <option value="App\Models\Training" {{ request('resource_type') == 'App\Models\Training' ? 'selected' : '' }}>Treinamentos</option>
                    <option value="App\Models\News" {{ request('resource_type') == 'App\Models\News' ? 'selected' : '' }}>Radar</option>
                    <option value="App\Models\Campaign" {{ request('resource_type') == 'App\Models\Campaign' ? 'selected' : '' }}>Campanhas</option>
                </select>
            </div>
            <div>
                <label for="date_from" class="form-label">Data Inicial</label>
                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" 
                       class="form-input">
            </div>
            <div>
                <label for="date_to" class="form-label">Data Final</label>
                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" 
                       class="form-input">
            </div>
            <div class="flex items-end space-x-3">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-search mr-2"></i>
                    Filtrar
                </button>
                <a href="{{ route('admin.reports.files') }}" class="btn-secondary">
                    <i class="fas fa-times mr-2"></i>
                    Limpar
                </a>
            </div>
        </form>
    </div>

    <!-- Files Table -->
    <div class="admin-card">
        <div class="admin-card-header">
            <h3 class="admin-card-title">Lista de Arquivos</h3>
            <p class="admin-card-subtitle">{{ $files->total() }} arquivos encontrados</p>
        </div>
        
        @if($files->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-file mr-2"></i>
                                Arquivo
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
                                <i class="fas fa-folder mr-2"></i>
                                Módulo
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-weight-hanging mr-2"></i>
                                Tamanho
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-2"></i>
                                Upload
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($files as $file)
                    <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-blue-50 transition-all duration-200 group">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-12 w-12">
                                    @if($file->file_type === 'image')
                                        <img class="h-12 w-12 rounded-lg object-cover shadow-lg group-hover:shadow-xl transition-shadow duration-200" src="{{ $file->url }}" alt="{{ $file->name }}">
                                    @elseif($file->file_type === 'video')
                                        <div class="h-12 w-12 rounded-lg bg-gradient-to-r from-red-100 to-red-200 flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-200">
                                            <i class="fas fa-video text-red-600 text-lg"></i>
                                        </div>
                                    @elseif($file->file_type === 'audio')
                                        <div class="h-12 w-12 rounded-lg bg-gradient-to-r from-blue-100 to-blue-200 flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-200">
                                            <i class="fas fa-music text-blue-600 text-lg"></i>
                                        </div>
                                    @elseif($file->file_type === 'pdf')
                                        <div class="h-12 w-12 rounded-lg bg-gradient-to-r from-red-100 to-red-200 flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-200">
                                            <i class="fas fa-file-pdf text-red-600 text-lg"></i>
                                        </div>
                                    @elseif($file->file_type === 'document')
                                        <div class="h-12 w-12 rounded-lg bg-gradient-to-r from-blue-100 to-blue-200 flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-200">
                                            <i class="fas fa-file-word text-blue-600 text-lg"></i>
                                        </div>
                                    @elseif($file->file_type === 'spreadsheet')
                                        <div class="h-12 w-12 rounded-lg bg-gradient-to-r from-green-100 to-green-200 flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-200">
                                            <i class="fas fa-file-excel text-green-600 text-lg"></i>
                                        </div>
                                    @elseif($file->file_type === 'presentation')
                                        <div class="h-12 w-12 rounded-lg bg-gradient-to-r from-orange-100 to-orange-200 flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-200">
                                            <i class="fas fa-file-powerpoint text-orange-600 text-lg"></i>
                                        </div>
                                    @else
                                        <div class="h-12 w-12 rounded-lg bg-gradient-to-r from-gray-100 to-gray-200 flex items-center justify-center shadow-lg group-hover:shadow-xl transition-shadow duration-200">
                                            <i class="fas fa-file text-gray-600 text-lg"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900 group-hover:text-primary-600 transition-colors duration-200 truncate max-w-xs">{{ $file->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $file->mime_type }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $badgeClass = match($file->file_type) {
                                    'image' => 'bg-green-100 text-green-800',
                                    'video' => 'bg-red-100 text-red-800',
                                    'audio' => 'bg-blue-100 text-blue-800',
                                    'pdf' => 'bg-red-100 text-red-800',
                                    'document' => 'bg-blue-100 text-blue-800',
                                    'spreadsheet' => 'bg-green-100 text-green-800',
                                    'presentation' => 'bg-orange-100 text-orange-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $badgeClass }}">
                                <i class="fas fa-tag mr-1"></i>
                                {{ ucfirst($file->file_type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $moduleBadgeClass = match(class_basename($file->fileable_type)) {
                                    'Product' => 'bg-blue-100 text-blue-800',
                                    'Library' => 'bg-green-100 text-green-800',
                                    'Training' => 'bg-purple-100 text-purple-800',
                                    'News' => 'bg-orange-100 text-orange-800',
                                    'Campaign' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $moduleBadgeClass }}">
                                <i class="fas fa-folder mr-1"></i>
                                {{ class_basename($file->fileable_type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center text-sm text-gray-500">
                                <div class="flex-shrink-0 h-8 w-8 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-weight-hanging text-gray-600 text-xs"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ number_format($file->size / 1024 / 1024, 2) }} MB</div>
                                    <div class="text-xs text-gray-500">{{ number_format($file->size) }} bytes</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center text-sm text-gray-500">
                                <div class="flex-shrink-0 h-8 w-8 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                    <i class="fas fa-calendar text-gray-600 text-xs"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-gray-900">{{ $file->created_at->format('d/m/Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $file->created_at->format('H:i') }}</div>
                                </div>
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
                <i class="fas fa-file text-6xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum arquivo encontrado</h3>
            <p class="text-gray-500">Não há arquivos que correspondam aos filtros selecionados.</p>
        </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($files->hasPages())
    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div class="flex-1 flex justify-between sm:hidden">
            {{ $files->links('pagination::tailwind') }}
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Mostrando
                    <span class="font-medium">{{ $files->firstItem() }}</span>
                    a
                    <span class="font-medium">{{ $files->lastItem() }}</span>
                    de
                    <span class="font-medium">{{ $files->total() }}</span>
                    resultados
                </p>
            </div>
            <div>
                {{ $files->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
