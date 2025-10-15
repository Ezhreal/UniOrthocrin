@extends('admin.layouts.app')

@section('title', 'Editar Item da Biblioteca - Admin')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-modern-title">Editar Item da Biblioteca</h1>
            <p class="text-modern-subtitle">Modifique as informações do item</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.library.show', $library) }}" class="btn-modern-secondary">
                <i class="fas fa-eye mr-2"></i>Visualizar
            </a>
            <a href="{{ route('admin.library.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <!-- Modern Form -->
    <form action="{{ route('admin.library.update', $library) }}" method="POST" enctype="multipart/form-data" class="space-modern">
        @csrf
        @method('PUT')

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
                        <p class="modern-card-subtitle">Dados principais do item</p>
                    </div>
                </div>
            </div>
            <div class="space-modern-sm">
                <!-- Thumbnail -->
                <div>
                    <label for="thumbnail" class="form-label-modern">Thumbnail (imagem)</label>
                    <input type="file" id="thumbnail" name="thumbnail" accept=".jpg,.jpeg,.png"
                           class="form-input-modern @error('thumbnail') border-error-500 @enderror">
                    @if($library->thumbnail_path)
                        <p class="text-sm text-gray-600 mt-1">Atual: <a href="/{{ $library->thumbnail_path }}" target="_blank" class="text-primary-600 underline">ver thumbnail</a></p>
                    @endif
                    @error('thumbnail')
                        <p class="form-error-modern">{{ $message }}</p>
                    @enderror
                    <div id="thumbnail-preview" class="mt-2"></div>
                </div>
                <div class="grid-modern grid-modern-2">
                    <div>
                        <label for="name" class="form-label-modern">Nome do Item *</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $library->name) }}" 
                               class="form-input-modern @error('name') border-error-500 @enderror" required
                               placeholder="Digite o nome do item">
                        @error('name')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="library_category_id" class="form-label-modern">Categoria *</label>
                        <select id="library_category_id" name="library_category_id" required
                                class="form-select-modern @error('library_category_id') border-error-500 @enderror">
                            <option value="">Selecione uma categoria</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('library_category_id', $library->library_category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('library_category_id')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="form-label-modern">Descrição</label>
                    <textarea id="description" name="description" rows="4" 
                              class="form-textarea-modern @error('description') border-error-500 @enderror"
                              placeholder="Descreva o item...">{{ old('description', $library->description) }}</textarea>
                    @error('description')
                        <p class="form-error-modern">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="form-label-modern">Status *</label>
                    <select id="status" name="status" required
                            class="form-select-modern @error('status') border-error-500 @enderror">
                        <option value="">Selecione o status</option>
                        <option value="active" {{ old('status', $library->status) == 'active' ? 'selected' : '' }}>Ativo</option>
                        <option value="inactive" {{ old('status', $library->status) == 'inactive' ? 'selected' : '' }}>Inativo</option>
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
                                <p class="modern-card-subtitle">Galeria de arquivos diversos</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-modern-sm">
                        <!-- Upload de Arquivos -->
                        <div>
                            <label for="files" class="form-label-modern">Galeria de Arquivos</label>
                            <div class="file-upload-area-modern border border-gray-300 rounded-lg p-6 my-4">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-modern-body font-medium mb-2">Arraste e solte os arquivos aqui</p>
                                    <p class="text-modern-caption mb-4">ou clique para selecionar</p>
                                    <input type="file" id="files" name="files[]" multiple
                                           class="hidden" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif,.mp4,.avi,.mov">
                                    <label for="files" class="btn-modern-secondary cursor-pointer">
                                        <i class="fas fa-plus mr-2"></i>
                                        Selecionar Arquivos
                                    </label>
                                </div>
                            </div>
                            @error('files')
                                <p class="form-error-modern">{{ $message }}</p>
                            @enderror
                            @error('files.*')
                                <p class="form-error-modern">{{ $message }}</p>
                            @enderror

                            <!-- Publicar no OneDrive -->
                            <div class="mt-4">
                                <label class="inline-flex items-center space-x-2">
                                    <input type="checkbox" name="publish_onedrive" value="1" class="form-checkbox">
                                    <span class="text-modern-body">Publicar no OneDrive</span>
                                </label>
                            </div>
                            
                            <!-- Lista dos Arquivos Existentes -->
                            @if($library->files && $library->files->count() > 0)
                            <div class="mt-4">
                                <h5 class="text-modern-body font-medium mb-3">Arquivos Existentes ({{ $library->files->count() }})</h5>
                                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                    <div class="divide-y divide-gray-200">
                                        @foreach($library->files as $file)
                                        <div class="flex items-center justify-between p-3 hover:bg-gray-50">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    @if($file->isImage())
                                                        <div class="bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                                                            <img src="{{ $file->thumbnail_url ?? $file->url }}" alt="{{ $file->name }}" class="w-full h-full object-cover">
                                                        </div>
                                                    @else
                                                        <div class="bg-gray-100 rounded-lg flex items-center justify-center">
                                                            <i class="fas fa-file text-gray-400 text-lg"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $file->name }}</p>
                                                </div>
                                            </div>
                                            <button type="button" class="text-red-600 hover:text-red-800 transition-colors duration-200"
                                                    onclick="deleteFile({{ $library->id }}, {{ $file->id }})">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
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
                        <h3 class="modern-card-title">Permissões</h3>
                        <p class="modern-card-subtitle">Controle de acesso</p>
                    </div>
                </div>
            </div>
            <div class="space-modern-sm">
                @foreach($userTypes as $userType)
                @php
                    $permission = $library->permissions->where('user_type_id', $userType->id)->first();
                @endphp
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
                                   {{ $permission && $permission->can_view ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary-500 focus:ring-primary-500 border-gray-300 rounded transition-colors duration-200">
                            <span class="ml-2 text-modern-caption">Ver</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="permissions[{{ $loop->index }}][can_download]" value="1"
                                   {{ $permission && $permission->can_download ? 'checked' : '' }}
                                   class="h-4 w-4 text-primary-500 focus:ring-primary-500 border-gray-300 rounded transition-colors duration-200">
                            <span class="ml-2 text-modern-caption">Baixar</span>
                        </label>
                        <input type="hidden" name="permissions[{{ $loop->index }}][user_type_id]" value="{{ $userType->id }}">
                    </div>
                </div>
                @endforeach
            </div>
        </div>

                <!-- Library Stats Card -->
                <div class="modern-card hover-modern-lift">
                    <div class="modern-card-header">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 bg-success-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-chart-bar text-success-500"></i>
                            </div>
                            <div>
                                <h3 class="modern-card-title">Estatísticas</h3>
                                <p class="modern-card-subtitle">Informações do item</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-modern-sm">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-primary-50 rounded-xl">
                                <div class="text-2xl font-bold text-primary-500">{{ $library->files ? $library->files->count() : 0 }}</div>
                                <div class="text-modern-caption">Arquivos</div>
                            </div>
                            <div class="text-center p-3 bg-secondary-50 rounded-xl">
                                <div class="text-2xl font-bold text-secondary-500">0</div>
                                <div class="text-modern-caption">Downloads</div>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <span class="text-modern-caption">Criado em:</span>
                                <span class="text-modern-body">{{ $library->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-modern-caption">Atualizado em:</span>
                                <span class="text-modern-body">{{ $library->updated_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="modern-card hover-modern-lift">
                    <div class="modern-card-header">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 bg-warning-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-cog text-warning-500"></i>
                            </div>
                            <div>
                                <h3 class="modern-card-title">Ações</h3>
                                <p class="modern-card-subtitle">Salvar e visualizar</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-modern-sm">
                        <div class="space-y-3">
                            <button type="submit" class="btn-modern-primary w-full">
                                <i class="fas fa-save mr-2"></i>Salvar Alterações
                            </button>
                            <a href="{{ route('admin.library.show', $library) }}" class="btn-modern-secondary w-full text-center">
                                <i class="fas fa-eye mr-2"></i>Visualizar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// File upload area interactions
const fileUploadArea = document.getElementById('file-upload-area-modern');
if (fileUploadArea) {
    fileUploadArea.addEventListener('click', () => {
        document.getElementById('files').click();
    });
}

// Drag and drop
const area = document.getElementById('file-upload-area-modern');

if (area) {
    area.addEventListener('dragover', (e) => {
        e.preventDefault();
        area.classList.add('file-upload-area-active');
    });

    area.addEventListener('dragleave', () => {
        area.classList.remove('file-upload-area-active');
    });

    area.addEventListener('drop', (e) => {
        e.preventDefault();
        area.classList.remove('file-upload-area-active');
        
        const files = e.dataTransfer.files;
        const input = document.getElementById('files');
        input.files = files;
        previewFiles(input);
    });
}

function previewFiles(input) {
    const preview = document.getElementById('file-preview');
    preview.innerHTML = '';
    
    Array.from(input.files).forEach(file => {
        const div = document.createElement('div');
        div.className = 'relative';
        
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg border border-gray-200">
                    <p class="text-xs text-gray-600 mt-1 truncate">${file.name}</p>
                `;
            };
            reader.readAsDataURL(file);
        } else {
            div.innerHTML = `
                <div class="w-full h-24 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center">
                    <i class="fas fa-file text-2xl text-gray-400"></i>
                </div>
                <p class="text-xs text-gray-600 mt-1 truncate">${file.name}</p>
            `;
        }
        
        preview.appendChild(div);
    });
}

function deleteFile(libraryId, fileId) {
    if (confirm('Tem certeza que deseja deletar este arquivo?')) {
        fetch(`/admin/library/${libraryId}/files/${fileId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao deletar arquivo: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao deletar arquivo');
        });
    }
}
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
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
                preview.innerHTML = `<div class=\"w-20 h-20 bg-gray-100 rounded-lg overflow-hidden mt-2\"><img src=\"${ev.target.result}\" alt=\"thumb\" class=\"w-full h-full object-cover\"></div>`;
            };
            reader.readAsDataURL(file);
        });
    }
});
</script>
@endsection
