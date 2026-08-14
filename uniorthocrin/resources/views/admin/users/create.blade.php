@extends('admin.layouts.app')

@section('tour_page_key', 'admin_users_form')

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
            <button type="button" id="btn-trigger-help-tour" class="btn-modern-secondary inline-flex items-center gap-2">
                <i class="fas fa-question-circle text-primary-500"></i>
                <span>Como usar?</span>
            </button>
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

        <!-- Multi-Profile and Status Card -->
        <div class="modern-card hover-modern-lift">
            <div class="modern-card-header">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 bg-secondary-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-id-card text-secondary-500"></i>
                    </div>
                    <div>
                        <h3 class="modern-card-title">Perfis e Status</h3>
                        <p class="modern-card-subtitle">Defina os perfis e status do usuário</p>
                    </div>
                </div>
            </div>
            <div class="space-modern-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="form-label-modern mb-3 block font-bold">Atribuir Perfis *</label>
                        <div class="space-y-3 bg-gray-50 p-4 rounded-xl border border-gray-100">
                            @foreach($userTypes as $userType)
                                <div class="flex items-center">
                                    <input type="checkbox" id="profile_{{ $userType->id }}" name="user_type_ids[]" value="{{ $userType->id }}" 
                                           class="w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 profile-checkbox"
                                           {{ is_array(old('user_type_ids')) && in_array($userType->id, old('user_type_ids')) ? 'checked' : '' }}
                                           onchange="updatePrimaryOptions()">
                                    <label for="profile_{{ $userType->id }}" class="ml-3 text-sm font-medium text-gray-700 cursor-pointer">
                                        {{ $userType->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('user_type_ids')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-6">
                        <div>
                            <label for="primary_user_type_id" class="form-label-modern block font-bold">Perfil Principal/Padrão *</label>
                            <p class="text-xs text-gray-500 mb-2">Este será o perfil carregado automaticamente após o login.</p>
                            <select id="primary_user_type_id" name="primary_user_type_id" required
                                    class="form-select-modern @error('primary_user_type_id') border-error-500 @enderror">
                                <option value="">Selecione os perfis acima primeiro</option>
                                @foreach($userTypes as $userType)
                                    <option value="{{ $userType->id }}" 
                                            class="primary-option hidden" 
                                            data-id="{{ $userType->id }}"
                                            {{ old('primary_user_type_id') == $userType->id ? 'selected' : '' }}>
                                        {{ $userType->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('primary_user_type_id')
                                <p class="form-error-modern">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="status" class="form-label-modern block font-bold">Status da Conta *</label>
                            <select id="status" name="status" required
                                    class="form-select-modern @error('status') border-error-500 @enderror">
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
        </div>

        <div id="company-info-card" class="modern-card hover-modern-lift" style="display: none;">
            <div class="modern-card-header">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 bg-success-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-building text-success-500"></i>
                    </div>
                    <div>
                        <h3 class="modern-card-title">Dados da empresa</h3>
                        <p class="modern-card-subtitle">Opcional. Vários usuários podem compartilhar o mesmo CNPJ.</p>
                    </div>
                </div>
            </div>
            <div class="space-modern-sm">
                <div class="grid-modern grid-modern-2">
                    <div>
                        <label for="razao_social" class="form-label-modern">Razão social (opcional)</label>
                        <input type="text" id="razao_social" name="razao_social" value="{{ old('razao_social') }}"
                               class="form-input-modern @error('razao_social') border-error-500 @enderror"
                               placeholder="Razão social">
                        @error('razao_social')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="nome_fantasia" class="form-label-modern">Nome fantasia (opcional)</label>
                        <input type="text" id="nome_fantasia" name="nome_fantasia" value="{{ old('nome_fantasia') }}"
                               class="form-input-modern @error('nome_fantasia') border-error-500 @enderror"
                               placeholder="Nome fantasia">
                        @error('nome_fantasia')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="cpf_cnpj" class="form-label-modern">CNPJ da empresa (opcional)</label>
                    <input type="text" id="cpf_cnpj" name="cpf_cnpj" value="{{ old('cpf_cnpj') }}"
                           class="form-input-modern @error('cpf_cnpj') border-error-500 @enderror"
                           placeholder="CNPJ">
                    @error('cpf_cnpj')
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
            <button id="tour-submit-btn" type="submit" 
                    class="btn-modern-primary">
                <i class="fas fa-save mr-2"></i>
                Criar Usuário
            </button>
        </div>
    </form>
</div>

<script>
function updatePrimaryOptions() {
    const checkboxes = document.querySelectorAll('.profile-checkbox');
    const primarySelect = document.getElementById('primary_user_type_id');
    const primaryOptions = document.querySelectorAll('.primary-option');
    const companyInfoCard = document.getElementById('company-info-card');
    const companyFields = companyInfoCard.querySelectorAll('input');

    let anyChecked = false;
    let businessChecked = false;

    primaryOptions.forEach(option => {
        const id = option.dataset.id;
        const checkbox = document.getElementById('profile_' + id);

        if (checkbox && checkbox.checked) {
            option.classList.remove('hidden');
            anyChecked = true;
            if ([2, 3, 4].includes(parseInt(id))) {
                businessChecked = true;
            }
        } else {
            option.classList.add('hidden');
            if (primarySelect.value == id) {
                primarySelect.value = '';
            }
        }
    });

    if (!anyChecked) {
        primarySelect.firstElementChild.textContent = 'Selecione os perfis acima primeiro';
    } else {
        primarySelect.firstElementChild.textContent = 'Selecione o perfil principal';
    }

    // Toggle Company Card
    if (businessChecked) {
        companyInfoCard.style.display = 'block';
        companyFields.forEach(f => f.disabled = false);
    } else {
        companyInfoCard.style.display = 'none';
        companyFields.forEach(f => {
            f.disabled = true;
            f.value = '';
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    updatePrimaryOptions();
});
</script>
@endsection