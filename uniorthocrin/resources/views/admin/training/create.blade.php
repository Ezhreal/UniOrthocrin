@extends('admin.layouts.app')

@section('title', 'Novo Treinamento - Admin')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-modern-title">Novo Treinamento</h1>
            <p class="text-modern-subtitle">Criar um novo treinamento na plataforma</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.training.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar
            </a>
        </div>
    </div>

    <!-- Modern Form -->
    <form method="POST" action="{{ route('admin.training.store') }}" enctype="multipart/form-data" class="space-modern">
        @csrf
        
        <div class="grid-modern grid-modern-3">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-modern">
                <!-- Basic Information Card -->
        <div class="modern-card hover-modern-lift">
            <div class="modern-card-header">
                <div class="flex items-center space-x-3">
                    <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                        <i class="fas fa-info-circle text-primary-500"></i>
                    </div>
                    <div>
                        <h3 class="modern-card-title">Informações Básicas</h3>
                        <p class="modern-card-subtitle">Dados principais do treinamento</p>
                    </div>
                </div>
            </div>
            <div class="space-modern-sm">
                <!-- Thumbnail -->
                <div>
                    <label for="thumbnail" class="form-label-modern">Thumbnail (imagem)</label>
                    <input type="file" id="thumbnail" name="thumbnail" accept=".jpg,.jpeg,.png"
                           class="form-input-modern @error('thumbnail') border-error-500 @enderror">
                    @error('thumbnail')
                        <p class="form-error-modern">{{ $message }}</p>
                    @enderror
                    <div id="thumbnail-preview" class="mt-2"></div>
                </div>
                <div class="grid-modern grid-modern-2">
                    <div>
                        <label for="name" class="form-label-modern">Nome do Treinamento *</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               class="form-input-modern @error('name') border-error-500 @enderror"
                               placeholder="Digite o nome do treinamento">
                        @error('name')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="training_category_id" class="form-label-modern">Categoria *</label>
                        <select id="training_category_id" name="training_category_id" required
                                class="form-select-modern @error('training_category_id') border-error-500 @enderror">
                            <option value="">Selecione uma categoria</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('training_category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('training_category_id')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="form-label-modern">Descrição</label>
                    <textarea id="description" name="description" rows="4"
                              class="form-textarea-modern @error('description') border-error-500 @enderror"
                              placeholder="Descreva o treinamento...">{{ old('description') }}</textarea>
                    @error('description')
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

                <!-- Conteúdos Card -->
                <div class="modern-card hover-modern-lift">
                    <div class="modern-card-header">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 bg-success-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-images text-success-500"></i>
                            </div>
                            <div>
                                <h3 class="modern-card-title">Conteúdos</h3>
                                <p class="modern-card-subtitle">Galeria de vídeos e PDFs</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-modern-sm">
                        <!-- Galeria de Vídeos -->
                        <div>
                            <label for="videos" class="form-label-modern">Galeria de Vídeos</label>
                            <div class="file-upload-area-modern border border-gray-300 rounded-lg p-6 my-4">
                                <div class="text-center">
                                    <i class="fas fa-video text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-modern-body font-medium mb-2">Arraste e solte os vídeos aqui</p>
                                    <p class="text-modern-caption mb-4">ou clique para selecionar</p>
                                    <input type="file" id="videos" name="videos[]" multiple
                                           class="hidden" accept="video/*">
                                    <label for="videos" class="btn-modern-secondary cursor-pointer">
                                        <i class="fas fa-plus mr-2"></i>
                                        Selecionar Vídeos
                                    </label>
                                </div>
                            </div>
                            <div id="videos_preview" class="mt-4"></div>
                            @error('videos')
                                <p class="form-error-modern">{{ $message }}</p>
                            @enderror
                            @error('videos.*')
                                <p class="form-error-modern">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Galeria de PDFs -->
                        <div class="mt-6">
                            <label for="files" class="form-label-modern">Galeria de PDFs</label>
                            <div class="file-upload-area-modern border border-gray-300 rounded-lg p-6 my-4">
                                <div class="text-center">
                                    <i class="fas fa-file-pdf text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-modern-body font-medium mb-2">Arraste e solte os PDFs aqui</p>
                                    <p class="text-modern-caption mb-4">ou clique para selecionar</p>
                                    <input type="file" id="files" name="files[]" multiple
                                           class="hidden" accept=".pdf">
                                    <label for="files" class="btn-modern-secondary cursor-pointer">
                                        <i class="fas fa-plus mr-2"></i>
                                        Selecionar PDFs
                                    </label>
                                </div>
                            </div>
                            <div id="files_preview" class="mt-4"></div>
                            @error('files')
                                <p class="form-error-modern">{{ $message }}</p>
                            @enderror
                            @error('files.*')
                                <p class="form-error-modern">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-modern">
                <!-- Permissions Card -->
                <div class="modern-card hover-modern-lift">
                    <div class="modern-card-header">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 bg-warning-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-shield-alt text-warning-500"></i>
                            </div>
                            <div>
                                <h3 class="modern-card-title">Permissões por Perfil</h3>
                                <p class="modern-card-subtitle">Configure quem pode visualizar e baixar este treinamento</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-modern-sm">
                        @foreach($userTypes as $userType)
                        <div class="flex items-center justify-between p-3 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors duration-200">
                            <div class="flex items-center space-x-3">
                                <div class="h-8 w-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user text-gray-600 text-sm"></i>
                                </div>
                                <div>
                                    <h4 class="text-modern-body font-medium">{{ $userType->name }}</h4>
                                </div>
                            </div>
                            <div class="flex items-center space-x-4">
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="permissions[{{ $loop->index }}][can_view]" value="1"
                                           {{ old("permissions.{$userType->id}.can_view") ? 'checked' : '' }}
                                           class="h-4 w-4 text-primary-500 focus:ring-primary-500 border-gray-300 rounded transition-colors duration-200">
                                    <span class="ml-2 text-modern-caption">Ver</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="permissions[{{ $loop->index }}][can_download]" value="1"
                                           {{ old("permissions.{$userType->id}.can_download") ? 'checked' : '' }}
                                           class="h-4 w-4 text-primary-500 focus:ring-primary-500 border-gray-300 rounded transition-colors duration-200">
                                    <span class="ml-2 text-modern-caption">Baixar</span>
                                </label>
                                <input type="hidden" name="permissions[{{ $loop->index }}][user_type_id]" value="{{ $userType->id }}">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Estatísticas Card -->
                <div class="modern-card hover-modern-lift">
                    <div class="modern-card-header">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-chart-line text-primary-500"></i>
                            </div>
                            <div>
                                <h3 class="modern-card-title">Estatísticas</h3>
                                <p class="modern-card-subtitle">Informações do treinamento</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-modern-sm">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-modern-body">Status:</span>
                                <span class="text-modern-body font-medium">Novo</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-modern-body">Criado em:</span>
                                <span class="text-modern-body font-medium">{{ now()->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ações Card -->
                <div class="modern-card hover-modern-lift">
                    <div class="modern-card-header">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 bg-warning-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-cog text-warning-500"></i>
                            </div>
                            <div>
                                <h3 class="modern-card-title">Ações</h3>
                                <p class="modern-card-subtitle">Salvar e cancelar</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-modern-sm">
                        <div class="space-y-3">
                            <label class="inline-flex items-center space-x-2">
                                <input type="checkbox" name="publish_onedrive" value="1" class="form-checkbox">
                                <span class="text-modern-body">Publicar no OneDrive</span>
                            </label>
                            <button type="submit" class="btn-modern-primary w-full">
                                <i class="fas fa-save mr-2"></i>Criar Treinamento
                            </button>
                            <a href="{{ route('admin.training.index') }}" class="btn-modern-secondary w-full text-center">
                                <i class="fas fa-times mr-2"></i>Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview do thumbnail
    const input = document.getElementById('thumbnail');
    const preview = document.getElementById('thumbnail-preview');
    if (input && preview) {
        input.addEventListener('change', function(e) {
            const file = e.target.files && e.target.files[0];
            if (!file) {
                preview.innerHTML = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(ev) {
                preview.innerHTML = `<div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden mt-2"><img src="${ev.target.result}" alt="thumb" class="w-full h-full object-cover"></div>`;
            };
            reader.readAsDataURL(file);
        });
    }

    // Preview dos vídeos
    initializeFileUpload('videos', 'videos_preview');
    
    // Preview dos PDFs
    initializeFileUpload('files', 'files_preview');
});

function initializeFileUpload(inputId, previewId) {
    const input = document.getElementById(inputId);
    if (!input) return;
    
    input.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        const previewContainer = document.getElementById(previewId);
        
        if (!previewContainer) return;
        
        // Limpar preview anterior
        previewContainer.innerHTML = '';
        
        if (files.length === 0) return;
        
        // Título
        const title = document.createElement('h5');
        title.className = 'text-modern-body font-medium mb-3';
        title.textContent = inputId === 'videos' ? 'Vídeos Selecionados:' : 'PDFs Selecionados:';
        previewContainer.appendChild(title);
        
        // Container de arquivos
        const outerDiv = document.createElement('div');
        outerDiv.className = 'bg-white border border-gray-200 rounded-lg overflow-hidden';
        
        const innerDiv = document.createElement('div');
        innerDiv.className = 'divide-y divide-gray-200';
        
        outerDiv.appendChild(innerDiv);
        previewContainer.appendChild(outerDiv);
        
        files.forEach((file, index) => {
            const fileItem = createFileItem(file, index);
            innerDiv.appendChild(fileItem);
        });
    });
}

function createFileItem(file, index) {
    const div = document.createElement('div');
    div.className = 'flex items-center justify-between p-3 hover:bg-gray-50';
    
    const icon = getFileIcon(file.type);
    
    div.innerHTML = `
        <div class="flex items-center space-x-3">
            <div class="flex-shrink-0">
                <i class="${icon} text-gray-400 text-lg"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">${file.name}</p>
            </div>
        </div>
        <button type="button" class="text-red-600 hover:text-red-800 transition-colors duration-200" onclick="removeFilePreview(this, ${index})">
            <i class="fas fa-trash text-sm"></i>
        </button>
    `;
    
    return div;
}

function getFileIcon(mimeType) {
    if (mimeType.startsWith('image/')) return 'fas fa-image';
    if (mimeType.startsWith('video/')) return 'fas fa-video';
    if (mimeType.includes('pdf')) return 'fas fa-file-pdf';
    if (mimeType.includes('audio/')) return 'fas fa-music';
    if (mimeType.includes('word')) return 'fas fa-file-word';
    if (mimeType.includes('excel')) return 'fas fa-file-excel';
    if (mimeType.includes('powerpoint')) return 'fas fa-file-powerpoint';
    return 'fas fa-file';
}

function removeFilePreview(button, index) {
    // Remover o elemento visual
    button.closest('.flex').remove();
}
</script>
@endsection