@extends('admin.layouts.app')

@section('title', 'Relatório de Usuários - Admin')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Relatório de Usuários</h1>
            <p class="text-gray-600 mt-2">Análise detalhada dos usuários da plataforma</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.reports.export', ['type' => 'users', 'format' => 'csv']) }}" class="btn-modern-secondary">
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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
        <div class="stats-card-modern hover-modern-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-modern-caption">Total de Usuários</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $userStats['total'] }}</p>
                </div>
                <div class="stats-card-icon-modern stats-card-icon-primary">
                    <i class="fas fa-users text-xl"></i>
                </div>
            </div>
        </div>

        <div class="stats-card-modern hover-modern-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-modern-caption">Usuários Ativos</p>
                    <p class="text-2xl font-bold text-green-600">{{ $userStats['active'] }}</p>
                </div>
                <div class="stats-card-icon-modern stats-card-icon-success">
                    <i class="fas fa-user-check text-xl"></i>
                </div>
            </div>
        </div>

        <div class="stats-card-modern hover-modern-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-modern-caption">Usuários Inativos</p>
                    <p class="text-2xl font-bold text-red-600">{{ $userStats['inactive'] }}</p>
                </div>
                <div class="stats-card-icon-modern stats-card-icon-error">
                    <i class="fas fa-user-times text-xl"></i>
                </div>
            </div>
        </div>

        <div class="stats-card-modern hover-modern-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-modern-caption">Novos Este Mês</p>
                    <p class="text-2xl font-bold text-blue-600">{{ $userStats['new_this_month'] }}</p>
                </div>
                <div class="stats-card-icon-modern stats-card-icon-warning">
                    <i class="fas fa-calendar text-xl"></i>
                </div>
            </div>
        </div>

        <div class="stats-card-modern hover-modern-lift">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-modern-caption">Novos Esta Semana</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $userStats['new_this_week'] }}</p>
                </div>
                <div class="stats-card-icon-modern stats-card-icon-gray">
                    <i class="fas fa-calendar-week text-xl"></i>
                </div>
            </div>
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
                    <p class="modern-card-subtitle">Filtrar usuários por critérios específicos</p>
                </div>
            </div>
        </div>
        <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-6">
            <div>
                <label for="search" class="form-label-modern">Buscar</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Nome ou email..."
                       class="form-input-modern">
            </div>
            <div>
                <label for="user_type" class="form-label-modern">Perfil</label>
                <select name="user_type" id="user_type" class="form-input-modern">
                    <option value="">Todos os perfis</option>
                    @foreach($userTypes as $userType)
                        <option value="{{ $userType->id }}" {{ request('user_type') == $userType->id ? 'selected' : '' }}>
                            {{ $userType->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="status" class="form-label-modern">Status</label>
                <select name="status" id="status" class="form-input-modern">
                    <option value="">Todos os status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativo</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inativo</option>
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
                <a href="{{ route('admin.reports.users') }}" class="btn-modern-secondary">
                    <i class="fas fa-times mr-2"></i>
                    Limpar
                </a>
            </div>
        </form>
    </div>

    <!-- Modern Users Table -->
    <div class="modern-card">
        <div class="modern-card-header">
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-primary-500"></i>
                </div>
                <div>
                    <h3 class="modern-card-title">Lista de Usuários</h3>
                    <p class="modern-card-subtitle">{{ $users->total() }} usuários encontrados</p>
                </div>
            </div>
        </div>
        
        @if($users->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Usuário
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Perfil
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Empresa
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Último Acesso
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Cadastrado em
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-primary-500 to-primary-600 flex items-center justify-center shadow-sm">
                                        <span class="text-white text-sm font-semibold">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-primary-100 text-primary-800">
                                {{ $user->userType->name ?? 'Sem perfil' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $user->nome_fantasia ?? $user->razao_social ?? '-' }}</div>
                            @if($user->representante_nome)
                                <div class="text-sm text-gray-500">{{ $user->representante_nome }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <div class="w-1.5 h-1.5 rounded-full {{ $user->status === 'active' ? 'bg-green-400' : 'bg-red-400' }} mr-1.5"></div>
                                {{ $user->status === 'active' ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-clock mr-2 text-gray-400"></i>
                                {{ $user->last_access ? $user->last_access->format('d/m/Y H:i') : 'Nunca' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex items-center">
                                <i class="fas fa-calendar mr-2 text-gray-400"></i>
                                {{ $user->created_at->format('d/m/Y H:i') }}
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
                <i class="fas fa-users text-6xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum usuário encontrado</h3>
            <p class="text-gray-500">Não há usuários que correspondam aos filtros selecionados.</p>
        </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div class="flex-1 flex justify-between sm:hidden">
            {{ $users->links('pagination::tailwind') }}
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Mostrando
                    <span class="font-medium">{{ $users->firstItem() }}</span>
                    a
                    <span class="font-medium">{{ $users->lastItem() }}</span>
                    de
                    <span class="font-medium">{{ $users->total() }}</span>
                    resultados
                </p>
            </div>
            <div>
                {{ $users->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
