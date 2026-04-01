@extends('admin.layouts.app')

@section('title', 'Editar Usuário - Admin')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-modern-title">Editar Usuário</h1>
            <p class="text-modern-subtitle">Editar informações do usuário: {{ $user->name }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.users.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar
            </a>
        </div>
    </div>

    <!-- Modern Form -->
    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-modern">
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
                        <p class="modern-card-subtitle">Dados básicos do usuário</p>
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
                        <label for="email" class="form-label-modern">Email *</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                               class="form-input-modern @error('email') border-error-500 @enderror"
                               placeholder="Digite o email">
                        @error('email')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid-modern grid-modern-2">
                    <div>
                        <label for="password" class="form-label-modern">Nova Senha</label>
                        <input type="password" id="password" name="password"
                               class="form-input-modern @error('password') border-error-500 @enderror"
                               placeholder="Deixe em branco para manter a senha atual">
                        @error('password')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="form-label-modern">Confirmar Nova Senha</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="form-input-modern @error('password_confirmation') border-error-500 @enderror"
                               placeholder="Confirme a nova senha">
                        @error('password_confirmation')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid-modern grid-modern-2">
                    <div>
                        <label for="user_type_id" class="form-label-modern">Perfil *</label>
                        <select id="user_type_id" name="user_type_id" required
                                class="form-select-modern @error('user_type_id') border-error-500 @enderror">
                            <option value="">Selecione um perfil</option>
                            @foreach($userTypes as $userType)
                                <option value="{{ $userType->id }}" {{ old('user_type_id', $user->user_type_id) == $userType->id ? 'selected' : '' }}>
                                    {{ $userType->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('user_type_id')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="form-label-modern">Status *</label>
                        <select id="status" name="status" required
                                class="form-select-modern @error('status') border-error-500 @enderror">
                            <option value="">Selecione o status</option>
                            <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Ativo</option>
                            <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inativo</option>
                        </select>
                        @error('status')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Dados da empresa (Franqueado, Lojista, Representante) — opcionais; CNPJ não é único -->
        <div id="company-info-card" class="modern-card hover-modern-lift" style="display: none;">
            <div class="modern-card-header">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 bg-success-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-building text-success-500"></i>
                    </div>
                    <div>
                        <h3 class="modern-card-title">Dados da empresa</h3>
                        <p class="modern-card-subtitle">Razão social, nome fantasia e CNPJ (opcionais). Vários usuários podem usar o mesmo CNPJ.</p>
                    </div>
                </div>
            </div>
            <div class="space-modern-sm">
                <div class="grid-modern grid-modern-2">
                    <div>
                        <label for="razao_social" class="form-label-modern">Razão social (opcional)</label>
                        <input type="text" id="razao_social" name="razao_social" value="{{ old('razao_social', $user->razao_social) }}"
                               class="form-input-modern @error('razao_social') border-error-500 @enderror"
                               placeholder="Razão social">
                        @error('razao_social')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nome_fantasia" class="form-label-modern">Nome fantasia (opcional)</label>
                        <input type="text" id="nome_fantasia" name="nome_fantasia" value="{{ old('nome_fantasia', $user->nome_fantasia) }}"
                               class="form-input-modern @error('nome_fantasia') border-error-500 @enderror"
                               placeholder="Nome fantasia">
                        @error('nome_fantasia')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="cnpj" class="form-label-modern">CNPJ da empresa (opcional)</label>
                    <input type="text" id="cnpj" name="cnpj" value="{{ old('cnpj', $user->cnpj ?? $user->cpf_cnpj) }}"
                           class="form-input-modern @error('cnpj') border-error-500 @enderror"
                           placeholder="Somente números ou formatado">
                    @error('cnpj')
                        <p class="form-error-modern">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Modern Actions -->
        <div class="flex items-center justify-end space-x-3 p-6 bg-gray-50 rounded-xl">
            <a href="{{ route('admin.users.index') }}" 
               class="btn-modern-secondary">
                Cancelar
            </a>
            <button type="submit" 
                    class="btn-modern-primary">
                <i class="fas fa-save mr-2"></i>
                Atualizar Usuário
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userTypeSelect = document.getElementById('user_type_id');
    const companyInfoCard = document.getElementById('company-info-card');
    
    function toggleFields() {
        const userTypeId = userTypeSelect.value;
        
        companyInfoCard.style.display = 'none';
        
        const companyFields = companyInfoCard.querySelectorAll('input');
        
        companyFields.forEach(field => {
            field.disabled = true;
        });
        
        if (userTypeId == 2 || userTypeId == 3 || userTypeId == 4) {
            companyInfoCard.style.display = 'block';
            companyFields.forEach(field => { field.disabled = false; });
        }
    }
    
    // Executar na mudança do select
    userTypeSelect.addEventListener('change', toggleFields);
    
    // Executar no carregamento da página se já houver valor selecionado
    toggleFields();
});
</script>
@endsection
