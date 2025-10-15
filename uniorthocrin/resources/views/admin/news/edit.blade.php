@extends('admin.layouts.app')

@section('title', 'Editar Notícia - Admin')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-modern-title">Editar Notícia</h1>
            <p class="text-modern-subtitle">Modifique as informações da notícia</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.news.show', $news) }}" class="btn-modern-secondary">
                <i class="fas fa-eye mr-2"></i>Visualizar
            </a>
            <a href="{{ route('admin.news.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <!-- Modern Form -->
    <form action="{{ route('admin.news.update', $news) }}" method="POST" enctype="multipart/form-data" class="space-modern">
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
                                <p class="modern-card-subtitle">Dados principais da notícia</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-modern-sm">
                        <div>
                            <label for="title" class="form-label-modern">Título da Notícia *</label>
                            <input type="text" id="title" name="title" value="{{ old('title', $news->title) }}" 
                                   class="form-input-modern @error('title') border-error-500 @enderror" required
                                   placeholder="Digite o título da notícia">
                            @error('title')
                                <p class="form-error-modern">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="content" class="form-label-modern">Conteúdo *</label>
                            <textarea id="content" name="content" rows="8" 
                                      class="form-textarea-modern @error('content') border-error-500 @enderror" required
                                      placeholder="Digite o conteúdo da notícia...">{{ old('content', $news->content) }}</textarea>
                            @error('content')
                                <p class="form-error-modern">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid-modern grid-modern-2">
                            <div>
                                <label for="news_category_id" class="form-label-modern">Categoria *</label>
                                <select id="news_category_id" name="news_category_id" required
                                        class="form-select-modern @error('news_category_id') border-error-500 @enderror">
                                    <option value="">Selecione uma categoria</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('news_category_id', $news->news_category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('news_category_id')
                                    <p class="form-error-modern">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="form-label-modern">Status *</label>
                                <select id="status" name="status" required
                                        class="form-select-modern @error('status') border-error-500 @enderror">
                                    <option value="">Selecione o status</option>
                                    <option value="published" {{ old('status', $news->status) == 'published' ? 'selected' : '' }}>Publicado</option>
                                    <option value="draft" {{ old('status', $news->status) == 'draft' ? 'selected' : '' }}>Rascunho</option>
                                </select>
                                @error('status')
                                    <p class="form-error-modern">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Image Upload Card -->
                <div class="modern-card hover-modern-lift">
                    <div class="modern-card-header">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 bg-secondary-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-image text-secondary-500"></i>
                            </div>
                            <div>
                                <h3 class="modern-card-title">Imagem da Notícia</h3>
                                <p class="modern-card-subtitle">Upload de imagem única</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-modern-sm">
                        <!-- New Image -->
                        <div>
                            <label for="image" class="form-label-modern">Nova Imagem</label>
                            <div class="file-upload-area-modern border border-gray-300 rounded-lg p-6 my-4" id="image-upload-area">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-modern-body font-medium mb-2">Clique para selecionar uma imagem ou arraste aqui</p>
                                    <p class="text-modern-caption mb-4">PNG, JPG, JPEG até 10MB</p>
                                    <input type="file" id="image" name="image" accept="image/*" 
                                           class="hidden" onchange="previewImage(this)">
                                    <label for="image" class="btn-modern-secondary cursor-pointer">
                                        <i class="fas fa-plus mr-2"></i>
                                        Selecionar Imagem
                                    </label>
                                </div>
                            </div>
                            <div id="image-preview" class="mt-4"></div>
                        </div>

                        <!-- Existing Image -->
                        @if($news->image)
                        <div class="mt-6">
                            <h5 class="text-modern-body font-medium mb-3">Imagem Atual</h5>
                            <div class="relative group">
                                <img src="{{ $news->image->url }}" alt="{{ $news->title }}" 
                                     class="w-full h-64 object-cover rounded-lg border border-gray-200">
                                <div class="absolute top-2 right-2 bg-black bg-opacity-50 rounded-full p-2">
                                    <button type="button" class="text-white hover:text-red-400 transition-colors duration-200"
                                            onclick="deleteImage({{ $news->id }})">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endif
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
                            $permission = $news->permissions->where('user_type_id', $userType->id)->first();
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
                                           {{ ($permission && $permission->can_view) ? 'checked' : '' }}
                                           class="h-4 w-4 text-primary-500 focus:ring-primary-500 border-gray-300 rounded transition-colors duration-200">
                                    <span class="ml-2 text-modern-caption">Ver</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="permissions[{{ $loop->index }}][can_download]" value="1"
                                           {{ ($permission && $permission->can_download) ? 'checked' : '' }}
                                           class="h-4 w-4 text-primary-500 focus:ring-primary-500 border-gray-300 rounded transition-colors duration-200">
                                    <span class="ml-2 text-modern-caption">Baixar</span>
                                </label>
                                <input type="hidden" name="permissions[{{ $loop->index }}][user_type_id]" value="{{ $userType->id }}">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- News Stats Card -->
                <div class="modern-card hover-modern-lift">
                    <div class="modern-card-header">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 bg-success-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-chart-bar text-success-500"></i>
                            </div>
                            <div>
                                <h3 class="modern-card-title">Estatísticas</h3>
                                <p class="modern-card-subtitle">Informações da notícia</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-modern-sm">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="text-center p-3 bg-primary-50 rounded-xl">
                                <div class="text-2xl font-bold text-primary-500">{{ $news->image ? '1' : '0' }}</div>
                                <div class="text-modern-caption">Imagem</div>
                            </div>
                            <div class="text-center p-3 bg-secondary-50 rounded-xl">
                                <div class="text-2xl font-bold text-secondary-500">0</div>
                                <div class="text-modern-caption">Visualizações</div>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex justify-between items-center">
                                <span class="text-modern-caption">Criado em:</span>
                                <span class="text-modern-body">{{ $news->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-modern-caption">Atualizado em:</span>
                                <span class="text-modern-body">{{ $news->updated_at->format('d/m/Y') }}</span>
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
                            <a href="{{ route('admin.news.show', $news) }}" class="btn-modern-secondary w-full text-center">
                                <i class="fas fa-eye mr-2"></i>Visualizar
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <label class="inline-flex items-center space-x-2 mr-auto">
                <input type="checkbox" name="publish_onedrive" value="1" class="form-checkbox">
                <span class="text-modern-body">Publicar no OneDrive</span>
            </label>
            <a href="{{ route('admin.news.index') }}" class="btn-modern-secondary">Cancelar</a>
            <button type="submit" class="btn-modern-primary"><i class="fas fa-save mr-2"></i>Salvar Notícia</button>
        </div>
    </form>
</div>

<script>
// File upload area interactions
const imageUploadArea = document.getElementById('image-upload-area');
if (imageUploadArea) {
    imageUploadArea.addEventListener('click', () => {
        document.getElementById('image').click();
    });
}

// Drag and drop
const area = document.getElementById('image-upload-area');

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
        if (files.length > 0) {
            const input = document.getElementById('image');
            input.files = files;
            previewImage(input);
        }
    });
}

function previewImage(input) {
    const preview = document.getElementById('image-preview');
    preview.innerHTML = '';
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
            preview.innerHTML = `
                <div class="relative">
                    <img src="${e.target.result}" class="w-full h-64 object-cover rounded-lg border border-gray-200">
                    <p class="text-sm text-gray-600 mt-2">${input.files[0].name}</p>
                </div>
            `;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function deleteImage(newsId) {
    if (confirm('Tem certeza que deseja deletar esta imagem?')) {
        fetch(`/admin/news/${newsId}/image`, {
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
                alert('Erro ao deletar imagem: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao deletar imagem');
        });
    }
}
</script>
@endsection
