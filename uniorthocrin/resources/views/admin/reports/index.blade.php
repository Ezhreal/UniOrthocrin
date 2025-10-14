@extends('admin.layouts.app')

@section('title', 'Relatórios - Admin')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-modern-title">Relatórios</h1>
            <p class="text-modern-subtitle">Visão geral e estatísticas da plataforma</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="btn-modern-secondary">
                    <i class="fas fa-download mr-2"></i>
                    Exportar
                </button>
                <div x-show="open" @click.away="open = false" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-theme-lg py-1 z-50 border border-gray-200">
                    <a href="{{ route('admin.reports.export', ['type' => 'users', 'format' => 'csv']) }}" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-users mr-2"></i>Usuários (CSV)
                    </a>
                    <a href="{{ route('admin.reports.export', ['type' => 'downloads', 'format' => 'csv']) }}" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-download mr-2"></i>Downloads (CSV)
                    </a>
                    <a href="{{ route('admin.reports.export', ['type' => 'files', 'format' => 'csv']) }}" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-file mr-2"></i>Arquivos (CSV)
                    </a>
                    <a href="{{ route('admin.reports.export', ['type' => 'access', 'format' => 'csv']) }}" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                        <i class="fas fa-eye mr-2"></i>Acessos (CSV)
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="stats-card-modern hover-modern-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-modern-caption">Total de Usuários</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                    <p class="text-modern-caption">{{ $stats['active_users'] }} ativos</p>
                </div>
                <div class="stats-card-icon-modern stats-card-icon-primary">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.reports.users') }}" class="text-sm text-primary-500 hover:text-primary-600 font-medium transition-colors duration-200">
                    Ver relatório →
                </a>
            </div>
        </div>

        <!-- Total Content -->
        <div class="stats-card-modern hover-modern-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-modern-caption">Total de Conteúdo</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total_products'] + $stats['total_library_items'] + $stats['total_trainings'] + $stats['total_news'] + $stats['total_campaigns'] }}</p>
                    <p class="text-modern-caption">{{ $stats['total_files'] }} arquivos</p>
                </div>
                <div class="stats-card-icon-modern stats-card-icon-secondary">
                    <i class="fas fa-folder text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.reports.files') }}" class="text-sm text-primary-500 hover:text-primary-600 font-medium transition-colors duration-200">
                    Ver relatório →
                </a>
            </div>
        </div>

        <!-- Downloads -->
        <div class="stats-card-modern hover-modern-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-modern-caption">Downloads</p>
                    <p class="text-2xl font-bold text-gray-900">-</p>
                    <p class="text-modern-caption">Este mês</p>
                </div>
                <div class="stats-card-icon-modern stats-card-icon-success">
                    <i class="fas fa-download text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.reports.downloads') }}" class="text-sm text-primary-500 hover:text-primary-600 font-medium transition-colors duration-200">
                    Ver relatório →
                </a>
            </div>
        </div>

        <!-- Access -->
        <div class="stats-card-modern hover-modern-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-modern-caption">Acessos</p>
                    <p class="text-2xl font-bold text-gray-900">-</p>
                    <p class="text-modern-caption">Este mês</p>
                </div>
                <div class="stats-card-icon-modern stats-card-icon-warning">
                    <i class="fas fa-eye text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.reports.access') }}" class="text-sm text-primary-500 hover:text-primary-600 font-medium transition-colors duration-200">
                    Ver relatório →
                </a>
            </div>
        </div>
    </div>

    <!-- Modern Charts Row -->
    <div class="grid-modern grid-modern-2">
        <!-- Users by Type Card -->
        <div class="modern-card hover-modern-lift">
            <div class="modern-card-header">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-users text-primary-500"></i>
                    </div>
                    <div>
                        <h3 class="modern-card-title">Usuários por Perfil</h3>
                        <p class="modern-card-subtitle">Distribuição de usuários</p>
                    </div>
                </div>
            </div>
            <div class="space-modern-sm">
                @if($usersByType->count() > 0)
                <div class="space-y-4">
                    @foreach($usersByType as $userType)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div class="flex items-center">
                            <div class="h-3 w-3 rounded-full bg-primary-500 mr-3"></div>
                            <span class="text-modern-body font-medium">{{ $userType->type_name }}</span>
                        </div>
                        <span class="text-modern-body font-bold text-primary-500">{{ $userType->count }}</span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <i class="fas fa-users text-gray-300 text-4xl mb-4"></i>
                    <p class="text-modern-caption">Nenhum usuário encontrado</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Content by Status Card -->
        <div class="modern-card hover-modern-lift">
            <div class="modern-card-header">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 bg-success-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-pie text-success-500"></i>
                    </div>
                    <div>
                        <h3 class="modern-card-title">Conteúdo por Status</h3>
                        <p class="modern-card-subtitle">Status dos conteúdos</p>
                    </div>
                </div>
            </div>
            <div class="space-modern-sm">
                @foreach($contentByStatus as $content)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                    <div class="flex items-center">
                        <div class="h-3 w-3 rounded-full bg-success-500 mr-3"></div>
                        <span class="text-modern-body font-medium">{{ $content['type'] }}</span>
                    </div>
                    <span class="text-modern-body font-bold text-success-500">{{ $content['total'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Modern Recent Activity Card -->
    <div class="modern-card hover-modern-lift">
        <div class="modern-card-header">
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 bg-warning-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-warning-500"></i>
                </div>
                <div>
                    <h3 class="modern-card-title">Atividade Recente</h3>
                    <p class="modern-card-subtitle">Últimos 30 dias</p>
                </div>
            </div>
        </div>
        <div class="grid-modern grid-modern-4">
            <div class="text-center p-4 bg-primary-50 rounded-xl">
                <div class="text-2xl font-bold text-primary-500">{{ $recentActivity['new_users'] }}</div>
                <div class="text-modern-caption">Novos Usuários</div>
            </div>
            <div class="text-center p-4 bg-secondary-50 rounded-xl">
                <div class="text-2xl font-bold text-secondary-500">{{ $recentActivity['new_products'] }}</div>
                <div class="text-modern-caption">Novos Produtos</div>
            </div>
            <div class="text-center p-4 bg-success-50 rounded-xl">
                <div class="text-2xl font-bold text-success-500">{{ $recentActivity['new_library_items'] }}</div>
                <div class="text-modern-caption">Itens Biblioteca</div>
            </div>
            <div class="text-center p-4 bg-warning-50 rounded-xl">
                <div class="text-2xl font-bold text-warning-500">{{ $recentActivity['new_trainings'] }}</div>
                <div class="text-modern-caption">Novos Treinamentos</div>
            </div>
            <div class="text-center p-4 bg-error-50 rounded-xl">
                <div class="text-2xl font-bold text-error-500">{{ $recentActivity['new_news'] }}</div>
                <div class="text-modern-caption">Novos Radar</div>
            </div>
            <div class="text-center p-4 bg-primary-50 rounded-xl">
                <div class="text-2xl font-bold text-primary-500">{{ $recentActivity['new_campaigns'] }}</div>
                <div class="text-modern-caption">Novas Campanhas</div>
            </div>
        </div>
    </div>

    <!-- Modern Quick Actions Card -->
    <div class="modern-card hover-modern-lift">
        <div class="modern-card-header">
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-bar text-primary-500"></i>
                </div>
                <div>
                    <h3 class="modern-card-title">Relatórios Detalhados</h3>
                    <p class="modern-card-subtitle">Acesse relatórios específicos para análise detalhada</p>
                </div>
            </div>
        </div>
        <div class="grid-modern grid-modern-4">
            <a href="{{ route('admin.reports.users') }}" class="flex items-center p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition-all duration-200 hover-modern-lift">
                <div class="flex-shrink-0 h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-users text-primary-500"></i>
                </div>
                <div>
                    <h4 class="text-modern-body font-medium">Usuários</h4>
                    <p class="text-modern-caption">Relatório de usuários e perfis</p>
                </div>
            </a>

            <a href="{{ route('admin.reports.downloads') }}" class="flex items-center p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition-all duration-200 hover-modern-lift">
                <div class="flex-shrink-0 h-10 w-10 bg-success-50 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-download text-success-500"></i>
                </div>
                <div>
                    <h4 class="text-modern-body font-medium">Downloads</h4>
                    <p class="text-modern-caption">Histórico de downloads</p>
                </div>
            </a>

            <a href="{{ route('admin.reports.files') }}" class="flex items-center p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition-all duration-200 hover-modern-lift">
                <div class="flex-shrink-0 h-10 w-10 bg-secondary-50 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-file text-secondary-500"></i>
                </div>
                <div>
                    <h4 class="text-modern-body font-medium">Arquivos</h4>
                    <p class="text-modern-caption">Gestão de arquivos</p>
                </div>
            </a>

            <a href="{{ route('admin.reports.access') }}" class="flex items-center p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition-all duration-200 hover-modern-lift">
                <div class="flex-shrink-0 h-10 w-10 bg-warning-50 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-eye text-warning-500"></i>
                </div>
                <div>
                    <h4 class="text-modern-body font-medium">Acessos</h4>
                    <p class="text-modern-caption">Histórico de acessos</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection