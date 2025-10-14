@extends('admin.layouts.app')

@section('title', 'Notificações - Admin')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-modern-title">Notificações</h1>
            <p class="text-modern-subtitle">Gerencie todas as notificações da plataforma</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.notifications.create') }}" class="btn-modern-primary">
                <i class="fas fa-plus mr-2"></i>
                Nova Notificação
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
                    <p class="modern-card-subtitle">Filtrar notificações</p>
                </div>
            </div>
        </div>
        <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
            <div>
                <label for="search" class="form-label-modern">Buscar</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Título ou conteúdo..."
                       class="form-input-modern">
            </div>
            <div>
                <label for="type" class="form-label-modern">Tipo</label>
                <select name="type" id="type" class="form-select-modern">
                    <option value="">Todos os tipos</option>
                    <option value="info" {{ request('type') == 'info' ? 'selected' : '' }}>Informação</option>
                    <option value="warning" {{ request('type') == 'warning' ? 'selected' : '' }}>Aviso</option>
                    <option value="error" {{ request('type') == 'error' ? 'selected' : '' }}>Erro</option>
                    <option value="success" {{ request('type') == 'success' ? 'selected' : '' }}>Sucesso</option>
                </select>
            </div>
            <div>
                <label for="target_type" class="form-label-modern">Destinatários</label>
                <select name="target_type" id="target_type" class="form-select-modern">
                    <option value="">Todos os tipos</option>
                    <option value="all" {{ request('target_type') == 'all' ? 'selected' : '' }}>Todos os usuários</option>
                    <option value="user_types" {{ request('target_type') == 'user_types' ? 'selected' : '' }}>Por perfil</option>
                    <option value="specific_users" {{ request('target_type') == 'specific_users' ? 'selected' : '' }}>Usuários específicos</option>
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

    <!-- Modern Notifications Table -->
    <div class="modern-card">
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Notificação</th>
                        <th>Tipo</th>
                        <th>Leitura</th>
                        <th>Destinatários</th>
                        <th>Criado em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notifications as $notification)
                    <tr>
                        <td>
                            <div class="flex items-center">
                                <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center mr-3">
                                    @switch($notification->type)
                                        @case('info')
                                            <i class="fas fa-info text-primary-500"></i>
                                            @break
                                        @case('warning')
                                            <i class="fas fa-exclamation-triangle text-warning-500"></i>
                                            @break
                                        @case('error')
                                            <i class="fas fa-times-circle text-error-500"></i>
                                            @break
                                        @case('success')
                                            <i class="fas fa-check-circle text-success-500"></i>
                                            @break
                                        @default
                                            <i class="fas fa-bell text-gray-500"></i>
                                    @endswitch
                                </div>
                                <div>
                                    <div class="text-modern-body font-medium">{{ $notification->title }}</div>
                                    <div class="text-modern-caption">{{ Str::limit($notification->message, 50) }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-modern 
                                @switch($notification->type)
                                    @case('info') badge-modern-primary @break
                                    @case('warning') badge-modern-warning @break
                                    @case('error') badge-modern-error @break
                                    @case('success') badge-modern-success @break
                                    @default badge-modern-gray
                                @endswitch">
                                {{ ucfirst($notification->type) }}
                            </span>
                        </td>
                        <td>
                            <div class="flex flex-col space-y-1">
                                <span class="badge-modern badge-modern-info">
                                    {{ $notification->read_count }} lida(s)
                                </span>
                                <span class="text-xs text-gray-500">
                                    de {{ $notification->total_target_count }} total
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="flex items-center">
                                <div class="h-8 w-8 bg-gray-100 rounded-lg flex items-center justify-center mr-2">
                                    <span class="text-gray-600 font-medium text-xs">
                                        @switch($notification->target_type)
                                            @case('all')
                                                T
                                                @break
                                            @case('user_types')
                                                P
                                                @break
                                            @case('specific_users')
                                                U
                                                @break
                                            @default
                                                ?
                                        @endswitch
                                    </span>
                                </div>
                                <div>
                                    <div class="text-modern-body font-medium">
                                        @switch($notification->target_type)
                                            @case('all')
                                                Todos os Usuários
                                                @break
                                            @case('user_types')
                                                Por Perfil
                                                @break
                                            @case('specific_users')
                                                Usuários Específicos
                                                @break
                                            @default
                                                Desconhecido
                                        @endswitch
                                    </div>
                                    <div class="text-modern-caption">
                                        @if($notification->target_type === 'user_types' && $notification->target_ids)
                                            {{ count($notification->target_ids) }} perfil(is)
                                        @elseif($notification->target_type === 'specific_users' && $notification->target_ids)
                                            {{ count($notification->target_ids) }} usuário(s)
                                        @else
                                            {{ $notification->total_target_count }} usuário(s)
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="text-modern-body">{{ $notification->created_at->format('d/m/Y H:i') }}</span>
                        </td>
                        <td>
                            <div class="flex items-center space-x-2">
                                <form method="POST" action="{{ route('admin.notifications.destroy', $notification) }}" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir esta notificação?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-error-500 hover:text-error-600 transition-colors duration-200" title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8">
                            <i class="fas fa-bell text-gray-300 text-4xl mb-4"></i>
                            <p class="text-modern-caption">Nenhuma notificação encontrada</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Modern Pagination -->
        @if($notifications->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>

    <!-- Statistics -->
    <div class="modern-card">
        <div class="modern-card-header">
            <div class="flex items-center space-x-3">
                <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-bar text-primary-500"></i>
                </div>
                <div>
                    <h3 class="modern-card-title">Estatísticas</h3>
                    <p class="modern-card-subtitle">Informações sobre as notificações</p>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.notifications.create') }}" class="btn-modern-primary">
                <i class="fas fa-plus mr-2"></i>
                Nova Notificação
            </a>
            
            <div class="text-sm text-gray-500">
                <i class="fas fa-info-circle mr-1"></i>
                Total: {{ $notifications->total() }} notificação(ões)
            </div>
        </div>
    </div>
</div>
@endsection