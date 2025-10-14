@extends('admin.layouts.app')

@section('title', 'Usuários - Admin')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-modern-title">Usuários</h1>
            <p class="text-modern-subtitle">Gerencie todos os usuários da plataforma</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.users.create') }}" class="btn-modern-primary">
                <i class="fas fa-plus mr-2"></i>
                Novo Usuário
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
                    <p class="modern-card-subtitle">Filtrar usuários</p>
                </div>
            </div>
        </div>
        <form method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
            <div>
                <label for="search" class="form-label-modern">Buscar</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="Nome ou email..."
                       class="form-input-modern">
            </div>
            <div>
                <label for="user_type" class="form-label-modern">Perfil</label>
                <select name="user_type" id="user_type" class="form-select-modern">
                    <option value="">Todos os perfis</option>
                    @foreach(\App\Models\UserType::orderBy('name')->get() as $userType)
                        <option value="{{ $userType->id }}" {{ request('user_type') == $userType->id ? 'selected' : '' }}>
                            {{ $userType->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="status" class="form-label-modern">Status</label>
                <select name="status" id="status" class="form-select-modern">
                    <option value="">Todos os status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Ativo</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inativo</option>
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

    <!-- Modern Users Table -->
    <div class="modern-card">
        <div class="overflow-x-auto">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Usuário</th>
                        <th>Perfil</th>
                        <th>Status</th>
                        <th>Último Acesso</th>
                        <th>Criado em</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="flex items-center">
                                <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center mr-3">
                                    <span class="text-primary-500 font-medium text-sm">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="text-modern-body font-medium">{{ $user->name }}</div>
                                    <div class="text-modern-caption">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge-modern badge-modern-primary">{{ $user->userType->name ?? 'Sem perfil' }}</span>
                        </td>
                        <td>
                            <span class="badge-modern {{ $user->status === 'active' ? 'badge-modern-success' : 'badge-modern-error' }}">
                                {{ $user->status === 'active' ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td>
                            <span class="text-modern-body">{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca' }}</span>
                        </td>
                        <td>
                            <span class="text-modern-body">{{ $user->created_at->format('d/m/Y') }}</span>
                        </td>
                        <td>
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.users.show', $user) }}" class="text-primary-500 hover:text-primary-600 transition-colors duration-200">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-gray-500 hover:text-gray-600 transition-colors duration-200">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')">
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
                        <td colspan="6" class="text-center py-8">
                            <i class="fas fa-users text-gray-300 text-4xl mb-4"></i>
                            <p class="text-modern-caption">Nenhum usuário encontrado</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Modern Pagination -->
        @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection