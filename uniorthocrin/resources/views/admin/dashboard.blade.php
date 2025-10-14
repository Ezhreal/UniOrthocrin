@extends('admin.layouts.app')

@section('title', 'Dashboard - Admin')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-modern-title">Dashboard</h1>
            <p class="text-modern-subtitle">Visão geral da plataforma UniOrthocrin</p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="text-modern-caption">Última atualização: {{ now()->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    <!-- Main Stats Cards (2 principais) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Campaigns - Card Principal -->
        <div class="stats-card-modern hover-modern-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-modern-caption">Campanhas</p>
                    <p class="text-4xl font-bold text-gray-900">{{ $stats['campaigns'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">Total de campanhas ativas</p>
                </div>
                <div class="stats-card-icon-modern stats-card-icon-warning" style="width: 80px; height: 80px;">
                    <i class="fas fa-bullhorn text-3xl"></i>
                </div>
            </div>
            <div class="mt-6">
                <a href="{{ route('admin.campaigns.index') }}" class="text-sm text-primary-500 hover:text-primary-600 font-medium transition-colors duration-200">
                    Ver todas →
                </a>
            </div>
        </div>

        <!-- Products - Card Principal -->
        <div class="stats-card-modern hover-modern-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-modern-caption">Produtos</p>
                    <p class="text-4xl font-bold text-gray-900">{{ $stats['products'] }}</p>
                    <p class="text-sm text-gray-500 mt-1">Total de produtos cadastrados</p>
                </div>
                <div class="stats-card-icon-modern stats-card-icon-primary" style="width: 80px; height: 80px;">
                    <i class="fas fa-box text-3xl"></i>
                </div>
            </div>
            <div class="mt-6">
                <a href="{{ route('admin.products.index') }}" class="text-sm text-primary-500 hover:text-primary-600 font-medium transition-colors duration-200">
                    Ver todos →
                </a>
            </div>
        </div>
    </div>

    <!-- Secondary Stats Cards (4 menores) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Library -->
        <div class="stats-card-modern hover-modern-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-modern-caption">Biblioteca</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['library'] }}</p>
                </div>
                <div class="stats-card-icon-modern stats-card-icon-secondary">
                    <i class="fas fa-book text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.library.index') }}" class="text-sm text-primary-500 hover:text-primary-600 font-medium transition-colors duration-200">
                    Ver todos →
                </a>
            </div>
        </div>

        <!-- Training -->
        <div class="stats-card-modern hover-modern-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-modern-caption">Treinamentos</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['training'] }}</p>
                </div>
                <div class="stats-card-icon-modern stats-card-icon-success">
                    <i class="fas fa-graduation-cap text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.training.index') }}" class="text-sm text-primary-500 hover:text-primary-600 font-medium transition-colors duration-200">
                    Ver todos →
                </a>
            </div>
        </div>

        <!-- Radar -->
        <div class="stats-card-modern hover-modern-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-modern-caption">Radar</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['news'] }}</p>
                </div>
                <div class="stats-card-icon-modern stats-card-icon-error">
                    <i class="fas fa-newspaper text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.news.index') }}" class="text-sm text-primary-500 hover:text-primary-600 font-medium transition-colors duration-200">
                    Ver todas →
                </a>
            </div>
        </div>

        <!-- Users -->
        <div class="stats-card-modern hover-modern-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-modern-caption">Usuários</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['users'] }}</p>
                </div>
                <div class="stats-card-icon-modern stats-card-icon-primary">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('admin.users.index') }}" class="text-sm text-primary-500 hover:text-primary-600 font-medium transition-colors duration-200">
                    Ver todos →
                </a>
            </div>
        </div>
    </div>

    <!-- Modern Recent Activity -->
    <div class="grid-modern grid-modern-2">
        <!-- Recent Users -->
        <div class="modern-card hover-modern-lift">
            <div class="modern-card-header">
                <div class="flex items-center justify-between">
                    <h3 class="modern-card-title">Usuários Recentes</h3>
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-primary-500 hover:text-primary-600 font-medium transition-colors duration-200">
                        Ver todos
                    </a>
                </div>
            </div>
            <div class="space-modern-sm">
                @forelse($recentUsers as $user)
                <div class="flex items-center space-x-4 p-3 rounded-xl hover:bg-gray-50 transition-all duration-200">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user text-primary-500"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-modern-body font-medium truncate">{{ $user->name }}</p>
                        <p class="text-modern-caption">{{ $user->userType->name ?? 'Sem tipo' }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="badge-modern {{ $user->status === 'active' ? 'badge-modern-success' : 'badge-modern-error' }}">
                            {{ $user->status === 'active' ? 'Ativo' : 'Inativo' }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-user text-gray-300 text-4xl mb-4"></i>
                    <p class="text-modern-caption">Nenhum usuário encontrado</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Access -->
        <div class="modern-card hover-modern-lift">
            <div class="modern-card-header">
                <div class="flex items-center justify-between">
                    <h3 class="modern-card-title">Últimos Acessos</h3>
                    <a href="{{ route('admin.users.index') }}" class="text-sm text-primary-500 hover:text-primary-600 font-medium transition-colors duration-200">
                        Ver relatórios
                    </a>
                </div>
            </div>
            <div class="space-modern-sm">
                @forelse($recentAccesses as $user)
                <div class="flex items-center space-x-4 p-3 rounded-xl hover:bg-gray-50 transition-all duration-200">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 bg-success-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-sign-in-alt text-success-500"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-modern-body font-medium truncate">{{ $user->name }}</p>
                        <p class="text-modern-caption">{{ $user->last_access ? $user->last_access->format('d/m/Y H:i') : 'Nunca acessou' }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <span class="badge-modern badge-modern-primary">
                            {{ $user->userType->name ?? 'Sem tipo' }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <i class="fas fa-sign-in-alt text-gray-300 text-4xl mb-4"></i>
                    <p class="text-modern-caption">Nenhum acesso recente</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection