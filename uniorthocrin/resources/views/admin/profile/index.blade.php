@extends('admin.layouts.app')

@section('title', 'Meu Perfil - Admin')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-modern-title">Meu Perfil</h1>
            <p class="text-modern-subtitle">Gerencie suas informações pessoais</p>
        </div>
    </div>

    <!-- Formulário de Perfil -->
    <form method="POST" action="{{ route('admin.profile.update') }}" class="space-modern">
        @csrf
        @method('PUT')
        
        <!-- Basic Information Card -->
        <div class="modern-card hover-modern-lift">
            <div class="modern-card-header">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user text-primary-500"></i>
                    </div>
                    <div>
                        <h3 class="modern-card-title">Informações Pessoais</h3>
                        <p class="modern-card-subtitle">Dados básicos do perfil</p>
                    </div>
                </div>
            </div>
            <div class="space-modern-sm">
                <div class="grid-modern grid-modern-2">
                    <div>
                        <label for="name" class="form-label-modern">Nome Completo *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                               class="form-input-modern @error('name') border-error-500 @enderror"
                               placeholder="Digite o nome completo">
                        @error('name')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="form-label-modern">Email</label>
                        <input type="email" id="email" value="{{ $user->email }}" 
                               class="form-input-modern bg-gray-50 text-gray-500" readonly>
                        <p class="text-xs text-gray-500 mt-1">O email não pode ser alterado</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information Card - Franqueado/Lojista -->
        @if($user->user_type_id == 2 || $user->user_type_id == 3)
        <div class="modern-card hover-modern-lift">
            <div class="modern-card-header">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 bg-success-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-building text-success-500"></i>
                    </div>
                    <div>
                        <h3 class="modern-card-title">Informações da Empresa</h3>
                        <p class="modern-card-subtitle">Dados da franquia/loja</p>
                    </div>
                </div>
            </div>
            <div class="space-modern-sm">
                <div class="grid-modern grid-modern-2">
                    <div>
                        <label for="razao_social" class="form-label-modern">Razão Social *</label>
                        <input type="text" id="razao_social" name="razao_social" value="{{ old('razao_social', $user->razao_social) }}"
                               class="form-input-modern @error('razao_social') border-error-500 @enderror"
                               placeholder="Digite a razão social" required>
                        @error('razao_social')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nome_fantasia" class="form-label-modern">Nome Fantasia *</label>
                        <input type="text" id="nome_fantasia" name="nome_fantasia" value="{{ old('nome_fantasia', $user->nome_fantasia) }}"
                               class="form-input-modern @error('nome_fantasia') border-error-500 @enderror"
                               placeholder="Digite o nome fantasia" required>
                        @error('nome_fantasia')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="cpf_cnpj" class="form-label-modern">CNPJ *</label>
                    <input type="text" id="cpf_cnpj" name="cpf_cnpj" value="{{ old('cpf_cnpj', $user->cpf_cnpj) }}"
                           class="form-input-modern @error('cpf_cnpj') border-error-500 @enderror"
                           placeholder="Digite o CNPJ" required>
                    @error('cpf_cnpj')
                        <p class="form-error-modern">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        @endif

        <!-- Additional Information Card - Representante -->
        @if($user->user_type_id == 4)
        <div class="modern-card hover-modern-lift">
            <div class="modern-card-header">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 bg-warning-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-tie text-warning-500"></i>
                    </div>
                    <div>
                        <h3 class="modern-card-title">Informações do Representante</h3>
                        <p class="modern-card-subtitle">Dados do representante</p>
                    </div>
                </div>
            </div>
            <div class="space-modern-sm">
                <div class="grid-modern grid-modern-2">
                    <div>
                        <label for="representante_nome" class="form-label-modern">Nome do Representante *</label>
                        <input type="text" id="representante_nome" name="representante_nome" value="{{ old('representante_nome', $user->representante_nome) }}"
                               class="form-input-modern @error('representante_nome') border-error-500 @enderror"
                               placeholder="Digite o nome do representante" required>
                        @error('representante_nome')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cpf_cnpj" class="form-label-modern">CPF *</label>
                        <input type="text" id="cpf_cnpj" name="cpf_cnpj" value="{{ old('cpf_cnpj', $user->cpf_cnpj) }}"
                               class="form-input-modern @error('cpf_cnpj') border-error-500 @enderror"
                               placeholder="Digite o CPF" required>
                        @error('cpf_cnpj')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Modern Actions -->
        <div class="flex items-center justify-end space-x-3 p-6 bg-gray-50 rounded-xl">
            <button type="submit" 
                    class="btn-modern-primary">
                <i class="fas fa-save mr-2"></i>
                Salvar Alterações
            </button>
        </div>
    </form>

    <!-- Formulário de Alteração de Senha -->
    <form method="POST" action="{{ route('admin.profile.password.update') }}" class="space-modern">
        @csrf
        @method('PUT')
        
        <div class="modern-card hover-modern-lift">
            <div class="modern-card-header">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 bg-warning-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-key text-warning-500"></i>
                    </div>
                    <div>
                        <h3 class="modern-card-title">Alterar Senha</h3>
                        <p class="modern-card-subtitle">Atualize sua senha de acesso</p>
                    </div>
                </div>
            </div>
            <div class="space-modern-sm">
                <div class="grid-modern grid-modern-2">
                    <div>
                        <label for="current_password" class="form-label-modern">Senha Atual *</label>
                        <input type="password" id="current_password" name="current_password" required
                               class="form-input-modern @error('current_password') border-error-500 @enderror"
                               placeholder="Digite a senha atual">
                        @error('current_password')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="form-label-modern">Nova Senha *</label>
                        <input type="password" id="password" name="password" required
                               class="form-input-modern @error('password') border-error-500 @enderror"
                               placeholder="Digite a nova senha" minlength="8">
                        @error('password')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="form-label-modern">Confirmar Nova Senha *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required
                           class="form-input-modern @error('password_confirmation') border-error-500 @enderror"
                           placeholder="Confirme a nova senha">
                    @error('password_confirmation')
                        <p class="form-error-modern">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Modern Actions -->
            <div class="flex items-center justify-end space-x-3 p-6 bg-gray-50 rounded-xl">
                <button type="submit" 
                        class="btn-modern-primary">
                    <i class="fas fa-key mr-2"></i>
                    Alterar Senha
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
