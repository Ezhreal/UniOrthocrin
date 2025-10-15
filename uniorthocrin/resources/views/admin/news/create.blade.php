@extends('admin.layouts.app')

@section('title', 'Nova Notícia - Admin')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-modern-title">Nova Notícia</h1>
            <p class="text-modern-subtitle">Criar uma nova notícia na plataforma</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.news.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left mr-2"></i>
                Voltar
            </a>
        </div>
    </div>

    <!-- Modern Form -->
    <form method="POST" action="{{ route('admin.news.store') }}" enctype="multipart/form-data" class="space-modern">
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
                                <p class="modern-card-subtitle">Dados principais da notícia</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-modern-sm">
                        <div>
                            <label for="title" class="form-label-modern">Título da Notícia *</label>
                            <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                   class="form-input-modern @error('title') border-error-500 @enderror"
                                   placeholder="Digite o título da notícia">
                            @error('title')
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
                                        <option value="{{ $category->id }}" {{ old('news_category_id') == $category->id ? 'selected' : '' }}>
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
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Publicado</option>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Rascunho</option>
                                </select>
                                @error('status')
                                    <p class="form-error-modern">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="content" class="form-label-modern">Conteúdo *</label>
                            <textarea id="content" name="content" rows="8" required
                                      class="form-textarea-modern @error('content') border-error-500 @enderror"
                                      placeholder="Digite o conteúdo da notícia...">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="form-error-modern">{{ $message }}</p>
                            @enderror
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
                                <p class="modern-card-subtitle">Faça upload da imagem principal</p>
                            </div>
                        </div>
                    </div>
                    <div class="space-modern-sm">
                        <div class="file-upload-area-modern border border-gray-300 rounded-lg p-6 my-4">
                            <div class="text-center">
                                <i class="fas fa-image text-4xl text-gray-400 mb-4"></i>
                                <p class="text-modern-body font-medium mb-2">Arraste e solte a imagem aqui</p>
                                <p class="text-modern-caption mb-4">ou clique para selecionar</p>
                                <input type="file" id="image" name="image"
                                       class="hidden" accept="image/*">
                                <label for="image" class="btn-modern-secondary cursor-pointer">
                                    <i class="fas fa-plus mr-2"></i>
                                    Selecionar Imagem
                                </label>
                            </div>
                        </div>
                        
                        <!-- Preview da imagem -->
                        <div id="image_preview"></div>
                        @error('image')
                            <p class="form-error-modern">{{ $message }}</p>
                        @enderror
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
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-modern-caption">Status:</span>
                                <span class="text-modern-body font-medium">Nova</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-modern-caption">Criado em:</span>
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
                            <button type="submit" class="btn-modern-primary w-full">
                                <i class="fas fa-save mr-2"></i>Criar Notícia
                            </button>
                            <a href="{{ route('admin.news.index') }}" class="btn-modern-secondary w-full text-center">
                                <i class="fas fa-times mr-2"></i>Cancelar
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
document.addEventListener('DOMContentLoaded', function() {
    // Preview da imagem
    const input = document.getElementById('image');
    const preview = document.getElementById('image_preview');
    
    if (input && preview) {
        input.addEventListener('change', function(e) {
            const file = e.target.files && e.target.files[0];
            if (!file) {
                preview.innerHTML = '';
                return;
            }
            
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    preview.innerHTML = `
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                            <h5 class="text-modern-body font-medium mb-3">Imagem Selecionada:</h5>
                            <div class="relative inline-block">
                                <div class="w-32 h-32 bg-gray-100 rounded-lg overflow-hidden">
                                    <img src="${ev.target.result}" alt="${file.name}" class="w-full h-full object-cover">
                                </div>
                                <button type="button" class="absolute h-8 w-8 bg-red-600 hover:bg-red-700 text-white rounded-full p-1 text-xs" onclick="removeImagePreview()" style="bottom: -15px; right: -13px;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">${file.name}</p>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});

function removeImagePreview() {
    // Limpar o preview
    const preview = document.getElementById('image_preview');
    if (preview) {
        preview.innerHTML = '';
    }
    
    // Limpar o input
    const input = document.getElementById('image');
    if (input) {
        input.value = '';
    }
}
</script>
@endsection