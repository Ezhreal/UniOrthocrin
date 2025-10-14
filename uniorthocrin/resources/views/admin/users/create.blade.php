@extends('admin.layouts.app')

@section('title', 'Novo Usuário - Admin')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-modern-title">Novo Usuário</h1>
            <p class="text-modern-subtitle">Criar um novo usuário na plataforma</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.users.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar
            </a>
        </div>
    </div>

    <!-- Modern Form -->
    <form method="POST" action="{{ route('admin.users.store') }}" class="space-modern">
        @csrf
        
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
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               class="form-input-modern @error('name') border-error-500 @enderror"
                               placeholder="Digite o nome completo">
                        @error('name')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="form-label-modern">Email *</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               class="form-input-modern @error('email') border-error-500 @enderror"
                               placeholder="Digite o email">
                        @error('email')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid-modern grid-modern-2">
                    <div>
                        <label for="password" class="form-label-modern">Senha *</label>
                        <input type="password" id="password" name="password" required
                               class="form-input-modern @error('password') border-error-500 @enderror"
                               placeholder="Digite a senha">
                        @error('password')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="form-label-modern">Confirmar Senha *</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="form-input-modern @error('password_confirmation') border-error-500 @enderror"
                               placeholder="Confirme a senha">
                        @error('password_confirmation')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>
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
                        <p class="modern-card-subtitle">Defina o perfil e status do usuário</p>
                    </div>
                </div>
            </div>
            <div class="space-modern-sm">
                <div class="grid-modern grid-modern-2">
                    <div>
                        <label for="user_type_id" class="form-label-modern">Perfil *</label>
                        <select id="user_type_id" name="user_type_id" required
                                class="form-select-modern @error('user_type_id') border-error-500 @enderror">
                            <option value="">Selecione um perfil</option>
                            @foreach($userTypes as $userType)
                                <option value="{{ $userType->id }}" {{ old('user_type_id') == $userType->id ? 'selected' : '' }}>
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
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Ativo</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inativo</option>
                        </select>
                        @error('status')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information Card - Franqueado/Lojista -->
        <div id="company-info-card" class="modern-card hover-modern-lift" style="display: none;">
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
                        <input type="text" id="razao_social" name="razao_social" value="{{ old('razao_social') }}"
                               class="form-input-modern @error('razao_social') border-error-500 @enderror"
                               placeholder="Digite a razão social">
                        @error('razao_social')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nome_fantasia" class="form-label-modern">Nome Fantasia *</label>
                        <input type="text" id="nome_fantasia" name="nome_fantasia" value="{{ old('nome_fantasia') }}"
                               class="form-input-modern @error('nome_fantasia') border-error-500 @enderror"
                               placeholder="Digite o nome fantasia">
                        @error('nome_fantasia')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="cpf_cnpj_company" class="form-label-modern">CNPJ *</label>
                    <input type="text" id="cpf_cnpj_company" name="cpf_cnpj" value="{{ old('cpf_cnpj') }}"
                           class="form-input-modern @error('cpf_cnpj') border-error-500 @enderror"
                           placeholder="Digite o CNPJ">
                    @error('cpf_cnpj')
                        <p class="form-error-modern">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Additional Information Card - Representante -->
        <div id="representative-info-card" class="modern-card hover-modern-lift" style="display: none;">
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
                        <input type="text" id="representante_nome" name="representante_nome" value="{{ old('representante_nome') }}"
                               class="form-input-modern @error('representante_nome') border-error-500 @enderror"
                               placeholder="Digite o nome do representante">
                        @error('representante_nome')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="cpf_cnpj_representative" class="form-label-modern">CPF *</label>
                        <input type="text" id="cpf_cnpj_representative" name="cpf_cnpj" value="{{ old('cpf_cnpj') }}"
                               class="form-input-modern @error('cpf_cnpj') border-error-500 @enderror"
                               placeholder="Digite o CPF">
                        @error('cpf_cnpj')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>
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
                Criar Usuário
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userTypeSelect = document.getElementById('user_type_id');
    const companyInfoCard = document.getElementById('company-info-card');
    const representativeInfoCard = document.getElementById('representative-info-card');
    
    function toggleFields() {
        const userTypeId = userTypeSelect.value;
        
        // Esconder todos os cards primeiro
        companyInfoCard.style.display = 'none';
        representativeInfoCard.style.display = 'none';
        
        // Desabilitar todos os campos condicionais
        const companyFields = companyInfoCard.querySelectorAll('input');
        const representativeFields = representativeInfoCard.querySelectorAll('input');
        
        companyFields.forEach(field => {
            field.disabled = true;
            field.value = '';
        });
        
        representativeFields.forEach(field => {
            field.disabled = true;
            field.value = '';
        });
        
        // Mostrar card apropriado baseado no tipo de usuário
        if (userTypeId == 2 || userTypeId == 3) { // Franqueado ou Lojista
            companyInfoCard.style.display = 'block';
            companyFields.forEach(field => {
                field.disabled = false;
            });
        } else if (userTypeId == 4) { // Representante
            representativeInfoCard.style.display = 'block';
            representativeFields.forEach(field => {
                field.disabled = false;
            });
        }
    }
    
    // Executar na mudança do select
    userTypeSelect.addEventListener('change', toggleFields);
    
    // Executar no carregamento da página se já houver valor selecionado
    toggleFields();
});
</script>
@endsection