@extends('admin.layouts.app')

@section('title', 'Visualizar Usuário - Admin')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-modern-title">{{ $user->name }}</h1>
            <p class="text-modern-subtitle">Detalhes do usuário</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.users.edit', $user) }}" class="btn-modern-primary">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <div class="grid-modern grid-modern-3">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-modern">
            <!-- User Info Card -->
            <div class="modern-card hover-modern-lift">
                <div class="modern-card-header">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user text-primary-500"></i>
                        </div>
                        <div>
                            <h3 class="modern-card-title">Informações Pessoais</h3>
                            <p class="modern-card-subtitle">Dados básicos do usuário</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-modern-sm">
                    <div class="grid-modern grid-modern-2">
                        <div>
                            <label class="form-label-modern">Nome Completo</label>
                            <p class="text-modern-body font-medium">{{ $user->name }}</p>
                        </div>
                        <div>
                            <label class="form-label-modern">Email</label>
                            <p class="text-modern-body">{{ $user->email }}</p>
                        </div>
                    </div>
                    
                    <div class="grid-modern grid-modern-2">
                        <div>
                            <label class="form-label-modern">Telefone</label>
                            <p class="text-modern-body">{{ $user->phone ?: 'Não informado' }}</p>
                        </div>
                        <div>
                            <label class="form-label-modern">Empresa</label>
                            <p class="text-modern-body">{{ $user->company ?: 'Não informado' }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="form-label-modern">Observações</label>
                        <p class="text-modern-body">{{ $user->notes ?: 'Nenhuma observação' }}</p>
                    </div>
                </div>
            </div>

            <!-- Profile and Status Card -->
            <div class="modern-card hover-modern-lift">
                <div class="modern-card-header">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 bg-secondary-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-id-card text-secondary-500"></i>
                        </div>
                        <div>
                            <h3 class="modern-card-title">Perfil e Status</h3>
                            <p class="modern-card-subtitle">Informações de acesso</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-modern-sm">
                    <div class="grid-modern grid-modern-2">
                        <div>
                            <label class="form-label-modern">Perfil</label>
                            <span class="badge-modern badge-modern-primary">{{ $user->userType->name ?? 'Sem perfil' }}</span>
                        </div>
                        <div>
                            <label class="form-label-modern">Status</label>
                            <span class="badge-modern {{ $user->status === 'active' ? 'badge-modern-success' : 'badge-modern-error' }}">
                                {{ $user->status === 'active' ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="grid-modern grid-modern-2">
                        <div>
                            <label class="form-label-modern">Último Acesso</label>
                            <p class="text-modern-body">{{ $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : 'Nunca acessou' }}</p>
                        </div>
                        <div>
                            <label class="form-label-modern">IP do Último Acesso</label>
                            <p class="text-modern-body">{{ $user->last_login_ip ?: 'Não disponível' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-modern">
            <!-- Statistics Card -->
            <div class="modern-card hover-modern-lift">
                <div class="modern-card-header">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-chart-bar text-primary-500"></i>
                        </div>
                        <div>
                            <h3 class="modern-card-title">Estatísticas</h3>
                            <p class="modern-card-subtitle">Informações do usuário</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-modern-sm">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-primary-50 rounded-xl">
                            <div class="text-2xl font-bold text-primary-500">{{ $user->created_at->diffInDays(now()) }}</div>
                            <div class="text-modern-caption">Dias ativo</div>
                        </div>
                        <div class="text-center p-4 bg-secondary-50 rounded-xl">
                            <div class="text-2xl font-bold text-secondary-500">{{ $user->last_login_at ? $user->last_login_at->diffInDays(now()) : 'N/A' }}</div>
                            <div class="text-modern-caption">Dias sem login</div>
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-200 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-modern-caption">Criado em:</span>
                            <span class="text-modern-body">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-modern-caption">Atualizado em:</span>
                            <span class="text-modern-body">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="modern-card hover-modern-lift">
                <div class="modern-card-header">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 bg-warning-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-bolt text-warning-500"></i>
                        </div>
                        <div>
                            <h3 class="modern-card-title">Ações Rápidas</h3>
                            <p class="modern-card-subtitle">Operações comuns</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-modern-sm">
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="w-full btn-modern-primary text-center block">
                        <i class="fas fa-edit mr-2"></i>
                        Editar Usuário
                    </a>
                    
                    <button onclick="resetPassword()" 
                            class="w-full btn-modern-secondary text-center block">
                        <i class="fas fa-key mr-2"></i>
                        Redefinir Senha
                    </button>
                    
                    <button onclick="sendWelcomeEmail()" 
                            class="w-full btn-modern-secondary text-center block">
                        <i class="fas fa-envelope mr-2"></i>
                        Enviar Email de Boas-vindas
                    </button>
                    
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                          class="inline w-full" onsubmit="return confirm('Tem certeza que deseja excluir este usuário?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full btn-modern-danger text-center block">
                            <i class="fas fa-trash mr-2"></i>
                            Excluir Usuário
                        </button>
                    </form>
                </div>
            </div>

            <!-- User Avatar Card -->
            <div class="modern-card hover-modern-lift">
                <div class="modern-card-header">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 bg-success-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-user-circle text-success-500"></i>
                        </div>
                        <div>
                            <h3 class="modern-card-title">Avatar</h3>
                            <p class="modern-card-subtitle">Foto do usuário</p>
                        </div>
                    </div>
                </div>
                
                <div class="text-center py-6">
                    <div class="h-24 w-24 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-primary-500 font-bold text-2xl">{{ substr($user->name, 0, 1) }}</span>
                    </div>
                    <p class="text-modern-body font-medium">{{ $user->name }}</p>
                    <p class="text-modern-caption">{{ $user->email }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function resetPassword() {
    if (confirm('Deseja redefinir a senha deste usuário?')) {
        // Implementar redefinição de senha
        alert('Funcionalidade de redefinição de senha será implementada em breve');
    }
}

function sendWelcomeEmail() {
    if (confirm('Deseja enviar um email de boas-vindas para este usuário?')) {
        // Implementar envio de email
        alert('Funcionalidade de envio de email será implementada em breve');
    }
}
</script>
@endsection