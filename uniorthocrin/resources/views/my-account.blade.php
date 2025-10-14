@extends('layouts.app')

@section('content')
<div class="bg-white min-h-screen">
    <!-- Banner com breadcrumb e título -->
    <div class="bg-[#910039] w-full py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-white">
                <div class="text-sm mb-2">Home > Minha Conta</div>
                <h1 class="text-3xl font-bold">Minha Conta</h1>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 py-12">
        <!-- Formulário de Perfil -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-8 mb-8">
            <h2 class="text-[#910039] font-bold text-xl mb-6">Informações do Perfil</h2>
            
            <form id="profileForm" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nome -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nome *</label>
                        <input type="text" 
                               id="name" 
                               name="name" 
                               value="{{ $user->name }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#910039] focus:border-transparent transition"
                               required>
                    </div>

                    <!-- Email (somente leitura) -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" 
                               id="email" 
                               value="{{ $user->email }}" 
                               class="w-full px-4 py-3 border border-gray-200 rounded-lg bg-gray-50 text-gray-500"
                               readonly>
                        <p class="text-xs text-gray-500 mt-1">O email não pode ser alterado</p>
                    </div>
                </div>

                <!-- Campos condicionais para Franqueado/Lojista -->
                @if($user->user_type_id == 2 || $user->user_type_id == 3)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Razão Social -->
                    <div>
                        <label for="razao_social" class="block text-sm font-medium text-gray-700 mb-2">Razão Social *</label>
                        <input type="text" 
                               id="razao_social" 
                               name="razao_social" 
                               value="{{ $user->razao_social }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#910039] focus:border-transparent transition"
                               required>
                    </div>

                    <!-- Nome Fantasia -->
                    <div>
                        <label for="nome_fantasia" class="block text-sm font-medium text-gray-700 mb-2">Nome Fantasia *</label>
                        <input type="text" 
                               id="nome_fantasia" 
                               name="nome_fantasia" 
                               value="{{ $user->nome_fantasia }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#910039] focus:border-transparent transition"
                               required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- CNPJ -->
                    <div>
                        <label for="cpf_cnpj" class="block text-sm font-medium text-gray-700 mb-2">CNPJ *</label>
                        <input type="text" 
                               id="cpf_cnpj" 
                               name="cpf_cnpj" 
                               value="{{ $user->cpf_cnpj }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#910039] focus:border-transparent transition"
                               placeholder="00.000.000/0000-00"
                               required>
                    </div>
                </div>
                @endif

                <!-- Campos condicionais para Representante -->
                @if($user->user_type_id == 4)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nome do Representante -->
                    <div>
                        <label for="representante_nome" class="block text-sm font-medium text-gray-700 mb-2">Nome do Representante *</label>
                        <input type="text" 
                               id="representante_nome" 
                               name="representante_nome" 
                               value="{{ $user->representante_nome }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#910039] focus:border-transparent transition"
                               required>
                    </div>

                    <!-- CPF -->
                    <div>
                        <label for="cpf_cnpj" class="block text-sm font-medium text-gray-700 mb-2">CPF *</label>
                        <input type="text" 
                               id="cpf_cnpj" 
                               name="cpf_cnpj" 
                               value="{{ $user->cpf_cnpj }}" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#910039] focus:border-transparent transition"
                               placeholder="000.000.000-00"
                               required>
                    </div>
                </div>
                @endif

                <div class="flex justify-end">
                    <button type="submit" 
                            class="bg-[#910039] text-white px-6 py-3 rounded-lg hover:bg-[#7a0030] transition font-medium">
                        <i class="fas fa-save mr-2"></i>Salvar Alterações
                    </button>
                </div>
            </form>
        </div>

        <!-- Formulário de Alteração de Senha -->
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-8">
            <h2 class="text-[#910039] font-bold text-xl mb-6">Alterar Senha</h2>
            
            <form id="passwordForm" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Senha Atual -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Senha Atual *</label>
                        <input type="password" 
                               id="current_password" 
                               name="current_password" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#910039] focus:border-transparent transition"
                               required>
                    </div>

                    <!-- Nova Senha -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Nova Senha *</label>
                        <input type="password" 
                               id="password" 
                               name="password" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#910039] focus:border-transparent transition"
                               required
                               minlength="8">
                        <p class="text-xs text-gray-500 mt-1">Mínimo de 8 caracteres</p>
                    </div>

                    <!-- Confirmar Nova Senha -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirmar Nova Senha *</label>
                        <input type="password" 
                               id="password_confirmation" 
                               name="password_confirmation" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#910039] focus:border-transparent transition"
                               required>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" 
                            class="bg-[#910039] text-white px-6 py-3 rounded-lg hover:bg-[#7a0030] transition font-medium">
                        <i class="fas fa-key mr-2"></i>Alterar Senha
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Toast de Notificação -->
<div id="toast" class="fixed top-4 right-4 bg-white border border-gray-200 rounded-lg shadow-lg p-4 transform translate-x-full transition-transform duration-300 z-50">
    <div class="flex items-center">
        <div id="toastIcon" class="mr-3"></div>
        <div id="toastMessage" class="text-sm text-gray-700"></div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Formulário de Perfil
    const profileForm = document.getElementById('profileForm');
    profileForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("my.account.profile") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(async response => {
            if (response.ok) {
                const data = await response.json();
                clearErrors();
                showToast(data.message || 'Perfil atualizado com sucesso!', true);
                return;
            }
            // Tratamento de validação 422
            if (response.status === 422) {
                const data = await response.json();
                showValidationErrors(data.errors || {});
                showToast('Corrija os campos destacados.', false);
                return;
            }
            // Outros erros
            showToast('Erro ao processar requisição', false);
        })
        .catch(() => {
            showToast('Erro ao processar requisição', false);
        });
    });

    // Formulário de Senha
    const passwordForm = document.getElementById('passwordForm');
    passwordForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('{{ route("my.account.password") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(async response => {
            if (response.ok) {
                const data = await response.json();
                clearErrors();
                showToast(data.message || 'Senha alterada com sucesso!', true);
                // Limpar formulário
                passwordForm.reset();
                return;
            }
            if (response.status === 422) {
                const data = await response.json();
                showValidationErrors(data.errors || {});
                showToast('Corrija os campos destacados.', false);
                return;
            }
            showToast('Erro ao processar requisição', false);
        })
        .catch(() => {
            showToast('Erro ao processar requisição', false);
        });
    });

    // Função para mostrar toast
    function showToast(message, isSuccess) {
        const toast = document.getElementById('toast');
        const toastIcon = document.getElementById('toastIcon');
        const toastMessage = document.getElementById('toastMessage');
        
        // Configurar ícone e cor baseado no sucesso
        if (isSuccess) {
            toastIcon.innerHTML = '<i class="fas fa-check-circle text-green-500 text-lg"></i>';
            toast.classList.add('border-green-200');
        } else {
            toastIcon.innerHTML = '<i class="fas fa-exclamation-circle text-red-500 text-lg"></i>';
            toast.classList.add('border-red-200');
        }
        
        toastMessage.textContent = message;
        
        // Mostrar toast
        toast.classList.remove('translate-x-full');
        
        // Esconder após 5 segundos
        setTimeout(() => {
            toast.classList.add('translate-x-full');
        }, 5000);
    }

    // Função para limpar erros
    function clearErrors() {
        const errorElements = document.querySelectorAll('.error-message');
        errorElements.forEach(element => element.remove());
        const erroredInputs = document.querySelectorAll('.input-error');
        erroredInputs.forEach(input => input.classList.remove('input-error', 'border-red-500'));
    }

    function showValidationErrors(errors) {
        clearErrors();
        Object.keys(errors).forEach((field) => {
            const messages = errors[field];
            const input = document.querySelector(`[name="${field}"]`);
            if (!input) return;
            input.classList.add('input-error', 'border-red-500');
            const error = document.createElement('p');
            error.className = 'error-message text-sm text-red-600 mt-1';
            error.textContent = Array.isArray(messages) ? messages[0] : messages;
            input.insertAdjacentElement('afterend', error);
        });
    }
});
</script>
@endsection
