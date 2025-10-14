@extends('admin.layouts.app')

@section('title', 'Nova Notificação - Admin')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-modern-title">Nova Notificação</h1>
            <p class="text-modern-subtitle">Criar uma nova notificação para usuários</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.notifications.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar
            </a>
        </div>
    </div>

    <!-- Modern Form -->
    <form method="POST" action="{{ route('admin.notifications.store') }}" class="space-modern">
        @csrf
        
        <!-- Basic Information Card -->
        <div class="modern-card hover-modern-lift">
            <div class="modern-card-header">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-bell text-primary-500"></i>
                    </div>
                    <div>
                        <h3 class="modern-card-title">Informações da Notificação</h3>
                        <p class="modern-card-subtitle">Dados básicos da notificação</p>
                    </div>
                </div>
            </div>
            <div class="space-modern-sm">
                <div>
                    <label class="form-label-modern">Destinatários *</label>
                    <div class="mt-2 space-y-3">
                        <div class="flex items-center">
                            <input type="radio" id="target_all" name="target_type" value="all" 
                                   {{ old('target_type', 'all') == 'all' ? 'checked' : '' }}
                                   class="form-radio-modern @error('target_type') border-error-500 @enderror">
                            <label for="target_all" class="ml-2 text-modern-body">Todos os usuários</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="target_user_types" name="target_type" value="user_types"
                                   {{ old('target_type') == 'user_types' ? 'checked' : '' }}
                                   class="form-radio-modern @error('target_type') border-error-500 @enderror">
                            <label for="target_user_types" class="ml-2 text-modern-body">Por perfil de usuário</label>
                        </div>
                    </div>
                    @error('target_type')
                        <p class="form-error-modern">{{ $message }}</p>
                    @enderror
                </div>

                <div id="user_types_selection" class="hidden">
                    <label class="form-label-modern">Selecione os perfis *</label>
                    <div class="mt-2 space-y-2">
                        @foreach($userTypes as $userType)
                            <div class="flex items-center">
                                <input type="checkbox" id="user_type_{{ $userType->id }}" name="user_types[]" value="{{ $userType->id }}"
                                       {{ in_array($userType->id, old('user_types', [])) ? 'checked' : '' }}
                                       class="form-checkbox-modern @error('user_types') border-error-500 @enderror">
                                <label for="user_type_{{ $userType->id }}" class="ml-2 text-modern-body">{{ $userType->name }}</label>
                            </div>
                        @endforeach
                    </div>
                    @error('user_types')
                        <p class="form-error-modern">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid-modern grid-modern-2">
                    <div>
                        <label for="type" class="form-label-modern">Tipo *</label>
                        <select id="type" name="type" required
                                class="form-select-modern @error('type') border-error-500 @enderror">
                            <option value="">Selecione o tipo</option>
                            <option value="info" {{ old('type') == 'info' ? 'selected' : '' }}>Informação</option>
                            <option value="success" {{ old('type') == 'success' ? 'selected' : '' }}>Sucesso</option>
                            <option value="warning" {{ old('type') == 'warning' ? 'selected' : '' }}>Aviso</option>
                            <option value="error" {{ old('type') == 'error' ? 'selected' : '' }}>Erro</option>
                        </select>
                        @error('type')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="title" class="form-label-modern">Título *</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                           class="form-input-modern @error('title') border-error-500 @enderror"
                           placeholder="Digite o título da notificação">
                    @error('title')
                        <p class="form-error-modern">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="message" class="form-label-modern">Mensagem *</label>
                    <textarea id="message" name="message" rows="4" required
                              class="form-input-modern @error('message') border-error-500 @enderror"
                              placeholder="Digite a mensagem da notificação">{{ old('message') }}</textarea>
                    @error('message')
                        <p class="form-error-modern">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Modern Actions -->
        <div class="flex items-center justify-end space-x-3 p-6 bg-gray-50 rounded-xl">
            <a href="{{ route('admin.notifications.index') }}" 
               class="btn-modern-secondary">
                Cancelar
            </a>
            <button type="submit" 
                    class="btn-modern-primary">
                <i class="fas fa-save mr-2"></i>
                Criar Notificação
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const targetAll = document.getElementById('target_all');
    const targetUserTypes = document.getElementById('target_user_types');
    const userTypesSelection = document.getElementById('user_types_selection');
    
    function toggleUserTypesSelection() {
        if (targetUserTypes.checked) {
            userTypesSelection.classList.remove('hidden');
        } else {
            userTypesSelection.classList.add('hidden');
        }
    }
    
    targetAll.addEventListener('change', toggleUserTypesSelection);
    targetUserTypes.addEventListener('change', toggleUserTypesSelection);
    
    // Executar na inicialização
    toggleUserTypesSelection();
});
</script>
@endsection
