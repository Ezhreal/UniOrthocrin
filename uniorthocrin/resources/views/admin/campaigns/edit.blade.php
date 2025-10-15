@extends('admin.layouts.app')

@section('title', 'Editar Campanha - Admin')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-modern-title">Editar Campanha</h1>
            <p class="text-modern-subtitle">Modifique as informações da campanha</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.campaigns.show', $campaign) }}" class="btn-modern-secondary">
                <i class="fas fa-eye mr-2"></i>Visualizar
            </a>
            <a href="{{ route('admin.campaigns.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <!-- Modern Form -->
    <form action="{{ route('admin.campaigns.update', $campaign) }}" method="POST" class="space-modern">
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
                                <p class="modern-card-subtitle">Dados principais da campanha</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-modern-sm">
                        <!-- Name -->
                        <div>
                            <label for="name" class="form-label-modern">Nome da Campanha *</label>
                            <input type="text" id="name" name="name" value="{{ old('name', $campaign->name) }}" 
                                   class="form-input-modern @error('name') border-error-500 @enderror" required
                                   placeholder="Digite o nome da campanha">
                            @error('name')
                                <p class="form-error-modern">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="form-label-modern">Descrição</label>
                            <textarea id="description" name="description" rows="4" 
                                      class="form-textarea-modern @error('description') border-error-500 @enderror"
                                      placeholder="Descreva a campanha...">{{ old('description', $campaign->description) }}</textarea>
                            @error('description')
                                <p class="form-error-modern">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Thumbnail -->
                        <div>
                            <label for="thumbnail" class="form-label-modern">Thumbnail (imagem)</label>
                            <input type="file" id="thumbnail" name="thumbnail" accept=".jpg,.jpeg,.png"
                                   class="form-input-modern @error('thumbnail') border-error-500 @enderror">
                            @if($campaign->thumbnail_path)
                                <p class="text-sm text-gray-600 mt-1">Atual: <a href="/{{ $campaign->thumbnail_path }}" target="_blank" class="text-primary-600 underline">ver thumbnail</a></p>
                            @endif
                            @error('thumbnail')
                                <p class="form-error-modern">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Destaque -->
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $campaign->is_featured) ? 'checked' : '' }} class="form-checkbox-modern">
                            <label for="is_featured" class="form-label-modern">Destacar campanha (aparece no banner do dashboard)</label>
                        </div>

                        <!-- Banner (condicional) -->
                        <div id="banner-wrapper" style="display: none;">
                            <label for="banner" class="form-label-modern">Banner (imagem larga)</label>
                            <input type="file" id="banner" name="banner" accept=".jpg,.jpeg,.png"
                                   class="form-input-modern @error('banner') border-error-500 @enderror">
                            @if($campaign->banner_path)
                                <p class="text-sm text-gray-600 mt-1">Atual: <a href="/{{ $campaign->banner_path }}" target="_blank" class="text-primary-600 underline">ver banner</a></p>
                            @endif
                            @error('banner')
                                <p class="form-error-modern">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Period -->
                        <div class="grid-modern grid-modern-2">
                            <div>
                                <label for="start_date" class="form-label-modern">Data de Início</label>
                                <input type="date" id="start_date" name="start_date" 
                                       value="{{ old('start_date', $campaign->start_date ? $campaign->start_date->format('Y-m-d') : '') }}" 
                                       class="form-input-modern @error('start_date') border-error-500 @enderror">
                                @error('start_date')
                                    <p class="form-error-modern">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_date" class="form-label-modern">Data de Fim</label>
                                <input type="date" id="end_date" name="end_date" 
                                       value="{{ old('end_date', $campaign->end_date ? $campaign->end_date->format('Y-m-d') : '') }}" 
                                       class="form-input-modern @error('end_date') border-error-500 @enderror">
                                @error('end_date')
                                    <p class="form-error-modern">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Status and Visibility -->
                        <div class="grid-modern grid-modern-2">
                            <div>
                                <label for="status" class="form-label-modern">Status *</label>
                                <select id="status" name="status" required
                                        class="form-select-modern @error('status') border-error-500 @enderror">
                                    <option value="">Selecione o status</option>
                                    <option value="active" {{ old('status', $campaign->status) == 'active' ? 'selected' : '' }}>Ativo</option>
                                    <option value="inactive" {{ old('status', $campaign->status) == 'inactive' ? 'selected' : '' }}>Inativo</option>
                                </select>
                                @error('status')
                                    <p class="form-error-modern">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label-modern">Visibilidade</label>
                                <input type="hidden" name="visible_franchise_only" value="1">
                                <div class="form-input-modern bg-gray-50 text-gray-700">Apenas Franqueados</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Folhetos Card -->
                <div class="modern-card hover-modern-lift">
                    <div class="modern-card-header">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-file-pdf text-primary-500"></i>
                            </div>
                            <div>
                                <h3 class="modern-card-title">Folhetos</h3>
                                <p class="modern-card-subtitle">Upload de folhetos por estado</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-modern-sm">
                        <!-- MG/SP -->
                        <div class="mb-6">
                            <h4 class="text-modern-body font-medium mb-4">Folhetos MG/SP</h4>
                            <div class="file-upload-area-modern border border-gray-300 rounded-lg p-6 my-4">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-modern-body font-medium mb-2">Arraste e solte os folhetos aqui</p>
                                    <p class="text-modern-caption mb-4">ou clique para selecionar</p>
                                    <input type="file" id="folder_mg_sp" name="folder_mg_sp[]" multiple
                                           class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                                    <label for="folder_mg_sp" class="btn-modern-secondary cursor-pointer">
                                        <i class="fas fa-plus mr-2"></i>
                                        Selecionar Folhetos MG/SP
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Lista dos Folhetos Existentes MG/SP -->
                            @if($campaign->folders && $campaign->folders->where('state', 'MG/SP')->count() > 0)
                            <div class="mt-4">
                                <h5 class="text-modern-body font-medium mb-3">Folhetos Existentes MG/SP ({{ $campaign->folders->where('state', 'MG/SP')->count() }})</h5>
                                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                    <div class="divide-y divide-gray-200">
                                        @foreach($campaign->folders->where('state', 'MG/SP') as $folder)
                                        <div class="flex items-center justify-between p-3 hover:bg-gray-50">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-file-pdf text-gray-400 text-lg"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $folder->name }}</p>
                                                </div>
                                            </div>
                                            <button type="button" class="text-red-600 hover:text-red-800 transition-colors duration-200"
                                                    onclick="deleteFolder({{ $folder->id }})">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <!-- DF/ES -->
                        <div>
                            <h4 class="text-modern-body font-medium mb-4">Folhetos DF/ES</h4>
                            <div class="file-upload-area-modern border border-gray-300 rounded-lg p-6 my-4">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-modern-body font-medium mb-2">Arraste e solte os folhetos aqui</p>
                                    <p class="text-modern-caption mb-4">ou clique para selecionar</p>
                                    <input type="file" id="folder_df_es" name="folder_df_es[]" multiple
                                           class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                                    <label for="folder_df_es" class="btn-modern-secondary cursor-pointer">
                                        <i class="fas fa-plus mr-2"></i>
                                        Selecionar Folhetos DF/ES
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Lista dos Folhetos Existentes DF/ES -->
                            @if($campaign->folders && $campaign->folders->where('state', 'DF/ES')->count() > 0)
                            <div class="mt-4">
                                <h5 class="text-modern-body font-medium mb-3">Folhetos Existentes DF/ES ({{ $campaign->folders->where('state', 'DF/ES')->count() }})</h5>
                                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                    <div class="divide-y divide-gray-200">
                                        @foreach($campaign->folders->where('state', 'DF/ES') as $folder)
                                        <div class="flex items-center justify-between p-3 hover:bg-gray-50">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-file-pdf text-gray-400 text-lg"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $folder->name }}</p>
                                                </div>
                                            </div>
                                            <button type="button" class="text-red-600 hover:text-red-800 transition-colors duration-200"
                                                    onclick="deleteFolder({{ $folder->id }})">
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

                <!-- Posts Card -->
                <div class="modern-card hover-modern-lift">
                    <div class="modern-card-header">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 bg-success-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-image text-success-500"></i>
                            </div>
                            <div>
                                <h3 class="modern-card-title">Posts - Galeria de Imagens</h3>
                                <p class="modern-card-subtitle">Upload de imagens por tipo de post</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-modern-sm">
                        <!-- Feed -->
                        <div class="mb-6">
                            <h4 class="text-modern-body font-medium mb-4">Posts Feed</h4>
                            <div class="file-upload-area-modern border border-gray-300 rounded-lg p-6 my-4">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-modern-body font-medium mb-2">Arraste e solte as imagens aqui</p>
                                    <p class="text-modern-caption mb-4">ou clique para selecionar</p>
                                    <input type="file" id="posts_feed" name="posts_feed[]" multiple
                                           class="hidden" accept=".jpg,.jpeg,.png">
                                    <label for="posts_feed" class="btn-modern-secondary cursor-pointer">
                                        <i class="fas fa-plus mr-2"></i>
                                        Selecionar Imagens Feed
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Miniaturas dos Posts Feed Existentes -->
                            @if($campaign->posts && $campaign->posts->where('type', 'feeds')->count() > 0)
                            <div class="mt-4">
                                <h5 class="text-modern-body font-medium mb-3">Posts Feed Existentes ({{ $campaign->posts->where('type', 'feeds')->count() }})</h5>
                                 <div class="flex gap-4 mb-8">
                                     @foreach($campaign->posts->where('type', 'feeds') as $post)
                                     <div class="relative">
                                         <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden">
                                             @if($post->url)
                                                 <img src="{{ $post->url }}" alt="{{ $post->name }}" class="w-full h-full object-cover">
                                             @else
                                                 <i class="fas fa-image text-gray-400 text-lg"></i>
                                             @endif
                                         </div>
                                         <button type="button" class="absolute h-8 w-8 bg-red-600 hover:bg-red-700 text-white rounded-full p-1 text-xs"
                                                 onclick="deletePost({{ $post->id }})" style="bottom: -15px; right: -13px;">
                                             <i class="fas fa-trash"></i>
                                         </button>
                                     </div>
                                     @endforeach
                                 </div>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Stories MG/SP -->
                        <div class="mb-6">
                            <h4 class="text-modern-body font-medium mb-4">Stories MG/SP</h4>
                            <div class="file-upload-area-modern border border-gray-300 rounded-lg p-6 my-4">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-modern-body font-medium mb-2">Arraste e solte as imagens aqui</p>
                                    <p class="text-modern-caption mb-4">ou clique para selecionar</p>
                                    <input type="file" id="posts_stories_mg_sp" name="posts_stories_mg_sp[]" multiple
                                           class="hidden" accept=".jpg,.jpeg,.png">
                                    <label for="posts_stories_mg_sp" class="btn-modern-secondary cursor-pointer">
                                        <i class="fas fa-plus mr-2"></i>
                                        Selecionar Stories MG/SP
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Miniaturas dos Stories MG/SP Existentes -->
                            @if($campaign->posts && $campaign->posts->where('type', 'stories_mg_sp')->count() > 0)
                            <div class="mt-4">
                                <h5 class="text-modern-body font-medium mb-3">Stories MG/SP Existentes ({{ $campaign->posts->where('type', 'stories_mg_sp')->count() }})</h5>
                                 <div class="flex gap-4 mb-8">
                                     @foreach($campaign->posts->where('type', 'stories_mg_sp') as $post)
                                     <div class="relative">
                                         <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden">
                                             @if($post->url)
                                                 <img src="{{ $post->url }}" alt="{{ $post->name }}" class="w-full h-full object-cover">
                                             @else
                                                 <i class="fas fa-image text-gray-400 text-lg"></i>
                                             @endif
                                         </div>
                                         <button type="button" class="absolute h-8 w-8 bg-red-600 hover:bg-red-700 text-white rounded-full p-1 text-xs"
                                                 onclick="deletePost({{ $post->id }})" style="bottom: -15px; right: -13px;">
                                             <i class="fas fa-trash"></i>
                                         </button>
                                     </div>
                                     @endforeach
                                 </div>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Stories DF/ES -->
                        <div>
                            <h4 class="text-modern-body font-medium mb-4">Stories DF/ES</h4>
                            <div class="file-upload-area-modern border border-gray-300 rounded-lg p-6 my-4">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-modern-body font-medium mb-2">Arraste e solte as imagens aqui</p>
                                    <p class="text-modern-caption mb-4">ou clique para selecionar</p>
                                    <input type="file" id="posts_stories_df_es" name="posts_stories_df_es[]" multiple
                                           class="hidden" accept=".jpg,.jpeg,.png">
                                    <label for="posts_stories_df_es" class="btn-modern-secondary cursor-pointer">
                                        <i class="fas fa-plus mr-2"></i>
                                        Selecionar Stories DF/ES
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Miniaturas dos Stories DF/ES Existentes -->
                            @if($campaign->posts && $campaign->posts->where('type', 'stories_df_es')->count() > 0)
                            <div class="mt-4">
                                <h5 class="text-modern-body font-medium mb-3">Stories DF/ES Existentes ({{ $campaign->posts->where('type', 'stories_df_es')->count() }})</h5>
                                 <div class="flex gap-4 mb-8">
                                     @foreach($campaign->posts->where('type', 'stories_df_es') as $post)
                                     <div class="relative">
                                         <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden">
                                             @if($post->url)
                                                 <img src="{{ $post->url }}" alt="{{ $post->name }}" class="w-full h-full object-cover">
                                             @else
                                                 <i class="fas fa-image text-gray-400 text-lg"></i>
                                             @endif
                                         </div>
                                         <button type="button" class="absolute h-8 w-8 bg-red-600 hover:bg-red-700 text-white rounded-full p-1 text-xs"
                                                 onclick="deletePost({{ $post->id }})" style="bottom: -15px; right: -13px;">
                                             <i class="fas fa-trash"></i>
                                         </button>
                                     </div>
                                     @endforeach
                                 </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Vídeos Card -->
                <div class="modern-card hover-modern-lift">
                    <div class="modern-card-header">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 bg-warning-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-video text-warning-500"></i>
                            </div>
                            <div>
                                <h3 class="modern-card-title">Vídeos</h3>
                                <p class="modern-card-subtitle">Upload de vídeos por tipo</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-modern-sm">
                        <!-- Reels -->
                        <div class="mb-6">
                            <h4 class="text-modern-body font-medium mb-4">Reels</h4>
                            <div class="file-upload-area-modern border border-gray-300 rounded-lg p-6 my-4">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-modern-body font-medium mb-2">Arraste e solte os vídeos aqui</p>
                                    <p class="text-modern-caption mb-4">ou clique para selecionar</p>
                                    <input type="file" id="videos_reels" name="videos_reels[]" multiple
                                           class="hidden" accept=".mp4,.avi,.mov">
                                    <label for="videos_reels" class="btn-modern-secondary cursor-pointer">
                                        <i class="fas fa-plus mr-2"></i>
                                        Selecionar Reels
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Lista dos Reels Existentes -->
                            @if($campaign->videos && $campaign->videos->where('type', 'reels')->count() > 0)
                            <div class="mt-4">
                                <h5 class="text-modern-body font-medium mb-3">Reels Existentes ({{ $campaign->videos->where('type', 'reels')->count() }})</h5>
                                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                    <div class="divide-y divide-gray-200">
                                        @foreach($campaign->videos->where('type', 'reels') as $video)
                                        <div class="flex items-center justify-between p-3 hover:bg-gray-50">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-video text-gray-400 text-lg"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $video->name }}</p>
                                                </div>
                                            </div>
                                            <button type="button" class="text-red-600 hover:text-red-800 transition-colors duration-200"
                                                    onclick="deleteVideo({{ $video->id }})">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Campanhas -->
                        <div>
                            <h4 class="text-modern-body font-medium mb-4">Campanhas</h4>
                            <div class="file-upload-area-modern border border-gray-300 rounded-lg p-6 my-4">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-modern-body font-medium mb-2">Arraste e solte os vídeos aqui</p>
                                    <p class="text-modern-caption mb-4">ou clique para selecionar</p>
                                    <input type="file" id="videos_campaigns" name="videos_campaigns[]" multiple
                                           class="hidden" accept=".mp4,.avi,.mov">
                                    <label for="videos_campaigns" class="btn-modern-secondary cursor-pointer">
                                        <i class="fas fa-plus mr-2"></i>
                                        Selecionar Campanhas
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Lista das Campanhas Existentes -->
                            @if($campaign->videos && $campaign->videos->where('type', 'marketing_campaigns')->count() > 0)
                            <div class="mt-4">
                                <h5 class="text-modern-body font-medium mb-3">Campanhas Existentes ({{ $campaign->videos->where('type', 'marketing_campaigns')->count() }})</h5>
                                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                    <div class="divide-y divide-gray-200">
                                        @foreach($campaign->videos->where('type', 'marketing_campaigns') as $video)
                                        <div class="flex items-center justify-between p-3 hover:bg-gray-50">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-video text-gray-400 text-lg"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $video->name }}</p>
                                                </div>
                                            </div>
                                            <button type="button" class="text-red-600 hover:text-red-800 transition-colors duration-200"
                                                    onclick="deleteVideo({{ $video->id }})">
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

                <!-- Diversos Card -->
                <div class="modern-card hover-modern-lift">
                    <div class="modern-card-header">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 bg-secondary-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-file text-secondary-500"></i>
                            </div>
                            <div>
                                <h3 class="modern-card-title">Diversos</h3>
                                <p class="modern-card-subtitle">Spot, Tag, Sticker e Script</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-modern-sm">
                        <!-- Spot -->
                        <div class="mb-6">
                            <h4 class="text-modern-body font-medium mb-4">Spot</h4>
                            <div class="file-upload-area-modern border border-gray-300 rounded-lg p-6 my-4">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-modern-body font-medium mb-2">Arraste e solte o arquivo aqui</p>
                                    <p class="text-modern-caption mb-4">ou clique para selecionar</p>
                                    <input type="file" id="misc_spot" name="misc_spot[]" multiple
                                           class="hidden" accept=".mp3,.wav,.mp4">
                                    <label for="misc_spot" class="btn-modern-secondary cursor-pointer">
                                        <i class="fas fa-plus mr-2"></i>
                                        Selecionar Spot
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Lista dos Spots Existentes -->
                            @if($campaign->miscellaneous && $campaign->miscellaneous->where('type', 'spot')->count() > 0)
                            <div class="mt-4">
                                <h5 class="text-modern-body font-medium mb-3">Spots Existentes ({{ $campaign->miscellaneous->where('type', 'spot')->count() }})</h5>
                                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                    <div class="divide-y divide-gray-200">
                                        @foreach($campaign->miscellaneous->where('type', 'spot') as $misc)
                                        <div class="flex items-center justify-between p-3 hover:bg-gray-50">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-broadcast-tower text-gray-400 text-lg"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $misc->name }}</p>
                                                </div>
                                            </div>
                                            <button type="button" class="text-red-600 hover:text-red-800 transition-colors duration-200"
                                                    onclick="deleteMiscellaneous({{ $misc->id }})">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Tag -->
                        <div class="mb-6">
                            <h4 class="text-modern-body font-medium mb-4">Tag</h4>
                            <div class="file-upload-area-modern border border-gray-300 rounded-lg p-6 my-4">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-modern-body font-medium mb-2">Arraste e solte o arquivo aqui</p>
                                    <p class="text-modern-caption mb-4">ou clique para selecionar</p>
                                    <input type="file" id="misc_tag" name="misc_tag[]" multiple
                                           class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                                    <label for="misc_tag" class="btn-modern-secondary cursor-pointer">
                                        <i class="fas fa-plus mr-2"></i>
                                        Selecionar Tag
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Lista das Tags Existentes -->
                            @if($campaign->miscellaneous && $campaign->miscellaneous->where('type', 'tag')->count() > 0)
                            <div class="mt-4">
                                <h5 class="text-modern-body font-medium mb-3">Tags Existentes ({{ $campaign->miscellaneous->where('type', 'tag')->count() }})</h5>
                                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                    <div class="divide-y divide-gray-200">
                                        @foreach($campaign->miscellaneous->where('type', 'tag') as $misc)
                                        <div class="flex items-center justify-between p-3 hover:bg-gray-50">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-tag text-gray-400 text-lg"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $misc->name }}</p>
                                                </div>
                                            </div>
                                            <button type="button" class="text-red-600 hover:text-red-800 transition-colors duration-200"
                                                    onclick="deleteMiscellaneous({{ $misc->id }})">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Adesivo -->
                        <div class="mb-6">
                            <h4 class="text-modern-body font-medium mb-4">Adesivo</h4>
                            <div class="file-upload-area-modern border border-gray-300 rounded-lg p-6 my-4">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-modern-body font-medium mb-2">Arraste e solte o arquivo aqui</p>
                                    <p class="text-modern-caption mb-4">ou clique para selecionar</p>
                                    <input type="file" id="misc_sticker" name="misc_sticker[]" multiple
                                           class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                                    <label for="misc_sticker" class="btn-modern-secondary cursor-pointer">
                                        <i class="fas fa-plus mr-2"></i>
                                        Selecionar Adesivo
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Lista dos Adesivos Existentes -->
                            @if($campaign->miscellaneous && $campaign->miscellaneous->where('type', 'adesivo')->count() > 0)
                            <div class="mt-4">
                                <h5 class="text-modern-body font-medium mb-3">Adesivos Existentes ({{ $campaign->miscellaneous->where('type', 'adesivo')->count() }})</h5>
                                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                    <div class="divide-y divide-gray-200">
                                        @foreach($campaign->miscellaneous->where('type', 'adesivo') as $misc)
                                        <div class="flex items-center justify-between p-3 hover:bg-gray-50">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-sticky-note text-gray-400 text-lg"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $misc->name }}</p>
                                                </div>
                                            </div>
                                            <button type="button" class="text-red-600 hover:text-red-800 transition-colors duration-200"
                                                    onclick="deleteMiscellaneous({{ $misc->id }})">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Roteiro -->
                        <div>
                            <h4 class="text-modern-body font-medium mb-4">Roteiro</h4>
                            <div class="file-upload-area-modern border border-gray-300 rounded-lg p-6 my-4">
                                <div class="text-center">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-modern-body font-medium mb-2">Arraste e solte o arquivo aqui</p>
                                    <p class="text-modern-caption mb-4">ou clique para selecionar</p>
                                    <input type="file" id="misc_script" name="misc_script[]" multiple
                                           class="hidden" accept=".pdf,.doc,.docx,.txt">
                                    <label for="misc_script" class="btn-modern-secondary cursor-pointer">
                                        <i class="fas fa-plus mr-2"></i>
                                        Selecionar Roteiro
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Lista dos Roteiros Existentes -->
                            @if($campaign->miscellaneous && $campaign->miscellaneous->where('type', 'roteiro')->count() > 0)
                            <div class="mt-4">
                                <h5 class="text-modern-body font-medium mb-3">Roteiros Existentes ({{ $campaign->miscellaneous->where('type', 'roteiro')->count() }})</h5>
                                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                    <div class="divide-y divide-gray-200">
                                        @foreach($campaign->miscellaneous->where('type', 'roteiro') as $misc)
                                        <div class="flex items-center justify-between p-3 hover:bg-gray-50">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-file-alt text-gray-400 text-lg"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $misc->name }}</p>
                                                </div>
                                            </div>
                                            <button type="button" class="text-red-600 hover:text-red-800 transition-colors duration-200"
                                                    onclick="deleteMiscellaneous({{ $misc->id }})">
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
                <!-- Campaign Statistics Card -->
                <div class="modern-card hover-modern-lift">
                    <div class="modern-card-header">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-chart-line text-primary-500"></i>
                            </div>
                            <div>
                                <h3 class="modern-card-title">Estatísticas</h3>
                                <p class="modern-card-subtitle">Informações da campanha</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-modern-sm">
                        <div class="space-y-3">
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-modern-body">Total de Itens:</span>
                                <span class="text-modern-body font-medium">{{ ($campaign->posts ? $campaign->posts->count() : 0) + ($campaign->folders ? $campaign->folders->count() : 0) + ($campaign->videos ? $campaign->videos->count() : 0) + ($campaign->miscellaneous ? $campaign->miscellaneous->count() : 0) }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-modern-body">Criado em:</span>
                                <span class="text-modern-body font-medium">{{ $campaign->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-modern-body">Última atualização:</span>
                                <span class="text-modern-body font-medium">{{ $campaign->updated_at->format('d/m/Y') }}</span>
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
                        <!-- Ações -->
                        <div class="flex justify-end space-x-3">
                            <label class="inline-flex items-center space-x-2 mr-auto">
                                <input type="checkbox" name="publish_onedrive" value="1" class="form-checkbox">
                                <span class="text-modern-body">Publicar no OneDrive</span>
                            </label>
                            <a href="{{ route('admin.campaigns.index') }}" class="btn-modern-secondary">
                                Cancelar
                            </a>
                            <button type="submit" class="btn-modern-primary">
                                <i class="fas fa-save mr-2"></i>
                                Salvar Alterações
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
// Mostrar/ocultar banner conforme is_featured
document.addEventListener('DOMContentLoaded', function() {
    const featured = document.getElementById('is_featured');
    const wrapper = document.getElementById('banner-wrapper');
    const toggle = () => {
        wrapper.style.display = featured && featured.checked ? 'block' : 'none';
    };
    if (featured && wrapper) {
        toggle();
        featured.addEventListener('change', toggle);
    }
});
// Armazenar arquivos selecionados globalmente
window.selectedFiles = {
    posts_feed: [],
    posts_stories_mg_sp: [],
    posts_stories_df_es: [],
    folder_mg_sp: [],
    folder_df_es: [],
    videos_reels: [],
    videos_campaigns: [],
    misc_spot: [],
    misc_tag: [],
    misc_sticker: [],
    misc_script: []
};

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar todos os uploads
    initializeFileUploads();
    
    // Restaurar arquivos selecionados se houver erro de validação
    restoreSelectedFiles();
});

function initializeFileUploads() {
    // Upload de imagens (Posts)
    initializeImageUpload('posts_feed', 'posts_feed_preview');
    initializeImageUpload('posts_stories_mg_sp', 'posts_stories_mg_sp_preview');
    initializeImageUpload('posts_stories_df_es', 'posts_stories_df_es_preview');
    
    // Upload de folhetos
    initializeFileUpload('folder_mg_sp', 'folder_mg_sp_preview');
    initializeFileUpload('folder_df_es', 'folder_df_es_preview');
    
    // Upload de vídeos
    initializeFileUpload('videos_reels', 'videos_reels_preview');
    initializeFileUpload('videos_campaigns', 'videos_campaigns_preview');
    
    // Upload de diversos
    initializeFileUpload('misc_spot', 'misc_spot_preview');
    initializeFileUpload('misc_tag', 'misc_tag_preview');
    initializeFileUpload('misc_sticker', 'misc_sticker_preview');
    initializeFileUpload('misc_script', 'misc_script_preview');
}

function initializeImageUpload(inputId, previewId) {
    const input = document.getElementById(inputId);
    if (!input) return;
    
    input.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        const previewContainer = document.getElementById(previewId) || createPreviewContainer(inputId, previewId);
        
        // Armazenar arquivos selecionados
        window.selectedFiles[inputId] = files;
        
        // Limpar preview anterior
        previewContainer.innerHTML = '';
        
        files.forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const thumbnail = createImageThumbnail(file, e.target.result, index);
                    previewContainer.appendChild(thumbnail);
                };
                reader.readAsDataURL(file);
            }
        });
        
        // Container já está visível por padrão
    });
}

function initializeFileUpload(inputId, previewId) {
    const input = document.getElementById(inputId);
    if (!input) return;
    
    input.addEventListener('change', function(e) {
        const files = Array.from(e.target.files);
        const previewContainer = document.getElementById(previewId) || createPreviewContainer(inputId, previewId);
        
        // Armazenar arquivos selecionados
        window.selectedFiles[inputId] = files;
        
        // Limpar preview anterior
        previewContainer.innerHTML = '';
        
        files.forEach((file, index) => {
            const fileItem = createFileItem(file, index);
            previewContainer.appendChild(fileItem);
        });
        
        // Container já está visível por padrão
    });
}

function createPreviewContainer(inputId, previewId) {
    const input = document.getElementById(inputId);
    const isImage = inputId.includes('posts_');
    
    // Criar container principal
    const container = document.createElement('div');
    container.id = previewId;
    container.className = 'mt-4';
    
    // Título
    const title = document.createElement('h5');
    title.className = 'text-modern-body font-medium mb-3';
    title.textContent = 'Novos Arquivos Selecionados:';
    container.appendChild(title);
    
    // Container de conteúdo
    let contentContainer;
    if (isImage) {
        contentContainer = document.createElement('div');
        contentContainer.className = 'flex gap-4 mb-8';
    } else {
        // Estrutura EXATA como no edit
        const outerDiv = document.createElement('div');
        outerDiv.className = 'bg-white border border-gray-200 rounded-lg overflow-hidden';
        
        const innerDiv = document.createElement('div');
        innerDiv.className = 'divide-y divide-gray-200';
        
        outerDiv.appendChild(innerDiv);
        container.appendChild(outerDiv);
        contentContainer = innerDiv; // Retornar o innerDiv para adicionar os itens
    }
    
    // Só adicionar ao container se for imagem (para outros arquivos já foi adicionado acima)
    if (isImage) {
        container.appendChild(contentContainer);
    }
    
    // Inserir após a área de upload
    const uploadArea = input.closest('.file-upload-area-modern');
    if (uploadArea && uploadArea.parentNode) {
        uploadArea.parentNode.insertBefore(container, uploadArea.nextSibling);
    }
    
    return contentContainer;
}

function createImageThumbnail(file, dataUrl, index) {
    const div = document.createElement('div');
    div.className = 'relative';
    
    div.innerHTML = `
        <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden">
            <img src="${dataUrl}" alt="${file.name}" class="w-full h-full object-cover">
        </div>
        <button type="button" class="absolute h-8 w-8 bg-red-600 hover:bg-red-700 text-white rounded-full p-1 text-xs" onclick="removeFilePreview(this, ${index})" style="bottom: -15px; right: -13px;">
            <i class="fas fa-trash"></i>
        </button>
    `;
    
    return div;
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

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function removeFilePreview(button, index) {
    const item = button.closest('.relative, .flex');
    
    // Encontrar o input correspondente
    const previewContainer = item.closest('.mt-4');
    const inputId = findInputIdByPreview(previewContainer);
    
    // Remover arquivo do array global
    if (inputId && window.selectedFiles[inputId]) {
        window.selectedFiles[inputId].splice(index, 1);
        
        // Atualizar o input file
        const input = document.getElementById(inputId);
        if (input) {
            updateFileInput(input, window.selectedFiles[inputId]);
        }
    }
    
    // Verificar se ainda há arquivos no preview ANTES de remover
    const contentContainer = item.parentNode;
    
    // Remover o item
    item.remove();
    
    // Verificar se o container ficou vazio
    if (contentContainer && contentContainer.children.length === 0) {
        // Esconder o container principal
        const mainContainer = contentContainer.closest('.mt-4');
        if (mainContainer) {
            mainContainer.style.display = 'none';
        }
    }
}

// Funções auxiliares para persistência
function findInputIdByPreview(previewContainer) {
    if (!previewContainer) return null;
    
    // Mapear IDs de preview para input
    const previewToInputMap = {
        'posts_feed_preview': 'posts_feed',
        'posts_stories_mg_sp_preview': 'posts_stories_mg_sp',
        'posts_stories_df_es_preview': 'posts_stories_df_es',
        'folder_mg_sp_preview': 'folder_mg_sp',
        'folder_df_es_preview': 'folder_df_es',
        'videos_reels_preview': 'videos_reels',
        'videos_campaigns_preview': 'videos_campaigns',
        'misc_spot_preview': 'misc_spot',
        'misc_tag_preview': 'misc_tag',
        'misc_sticker_preview': 'misc_sticker',
        'misc_script_preview': 'misc_script'
    };
    
    const previewId = previewContainer.id;
    return previewToInputMap[previewId] || null;
}

function updateFileInput(input, files) {
    // Criar um novo DataTransfer para atualizar o input
    const dt = new DataTransfer();
    files.forEach(file => dt.items.add(file));
    input.files = dt.files;
}

function restoreSelectedFiles() {
    // Restaurar arquivos selecionados de cada input
    Object.keys(window.selectedFiles).forEach(inputId => {
        const files = window.selectedFiles[inputId];
        if (files && files.length > 0) {
            const input = document.getElementById(inputId);
            if (input) {
                updateFileInput(input, files);
                
                // Recriar previews
                const previewId = inputId + '_preview';
                const previewContainer = document.getElementById(previewId) || createPreviewContainer(inputId, previewId);
                previewContainer.innerHTML = '';
                
                files.forEach((file, index) => {
                    if (inputId.includes('posts_')) {
                        // Para imagens
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            const thumbnail = createImageThumbnail(file, e.target.result, index);
                            previewContainer.appendChild(thumbnail);
                        };
                        reader.readAsDataURL(file);
                    } else {
                        // Para outros arquivos
                        const fileItem = createFileItem(file, index);
                        previewContainer.appendChild(fileItem);
                    }
                });
            }
        }
    });
}

// Interceptar submit do formulário para manter arquivos
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Validar campos obrigatórios antes de salvar arquivos
            const nameField = document.getElementById('name');
            const statusField = document.getElementById('status');
            
            if (!nameField.value.trim()) {
                e.preventDefault();
                showValidationError('Nome da campanha é obrigatório');
                return;
            }
            
            if (!statusField.value) {
                e.preventDefault();
                showValidationError('Status é obrigatório');
                return;
            }
            
            // Salvar informações dos arquivos selecionados
            const filesData = {};
            Object.keys(window.selectedFiles).forEach(inputId => {
                const files = window.selectedFiles[inputId];
                if (files && files.length > 0) {
                    filesData[inputId] = files.map(file => ({
                        name: file.name,
                        size: file.size,
                        type: file.type,
                        lastModified: file.lastModified
                    }));
                }
            });
            localStorage.setItem('campaignEditFormFiles', JSON.stringify(filesData));
        });
    }
    
    // Verificar se há arquivos salvos (erro de validação)
    const savedFilesData = localStorage.getItem('campaignEditFormFiles');
    if (savedFilesData) {
        try {
            const parsedFilesData = JSON.parse(savedFilesData);
            showRestoreFilesMessage(parsedFilesData);
        } catch (e) {
            console.log('Erro ao restaurar arquivos:', e);
        }
    }
});

function showRestoreFilesMessage(filesData) {
    const hasFiles = Object.values(filesData).some(files => files.length > 0);
    if (hasFiles) {
        const message = document.createElement('div');
        message.className = 'bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6';
        message.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                <div>
                    <h4 class="text-blue-800 font-medium">Arquivos Selecionados Anteriormente</h4>
                    <p class="text-blue-700 text-sm mt-1">Detectamos que você havia selecionado arquivos antes do erro de validação. 
                    Por favor, selecione os arquivos novamente nos campos correspondentes.</p>
                </div>
            </div>
        `;
        
        const form = document.querySelector('form');
        if (form) {
            form.insertBefore(message, form.firstChild);
        }
        
        // Mostrar quais arquivos foram selecionados
        Object.keys(filesData).forEach(inputId => {
            const files = filesData[inputId];
            if (files && files.length > 0) {
                const input = document.getElementById(inputId);
                if (input) {
                    const uploadArea = input.closest('.file-upload-area-modern');
                    if (uploadArea) {
                        const hint = document.createElement('div');
                        hint.className = 'mt-2 text-sm text-gray-600';
                        hint.innerHTML = `
                            <i class="fas fa-upload mr-1"></i>
                            Arquivos selecionados anteriormente: ${files.map(f => f.name).join(', ')}
                        `;
                        uploadArea.appendChild(hint);
                    }
                }
            }
        });
        
        // Limpar dados salvos após mostrar
        setTimeout(() => {
            localStorage.removeItem('campaignEditFormFiles');
        }, 10000); // 10 segundos
    }
}

function showValidationError(message) {
    // Remover mensagens de erro anteriores
    const existingError = document.querySelector('.validation-error-message');
    if (existingError) {
        existingError.remove();
    }
    
    // Criar nova mensagem de erro
    const errorDiv = document.createElement('div');
    errorDiv.className = 'validation-error-message bg-red-50 border border-red-200 rounded-lg p-4 mb-6';
    errorDiv.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
            <div>
                <h4 class="text-red-800 font-medium">Erro de Validação</h4>
                <p class="text-red-700 text-sm mt-1">${message}</p>
            </div>
        </div>
    `;
    
    const form = document.querySelector('form');
    if (form) {
        form.insertBefore(errorDiv, form.firstChild);
        
        // Scroll para o topo do formulário
        errorDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
        
        // Remover mensagem após 5 segundos
        setTimeout(() => {
            if (errorDiv.parentNode) {
                errorDiv.remove();
            }
        }, 5000);
    }
}

// Funções para deletar conteúdo existente
function deletePost(postId) {
    if (confirm('Tem certeza que deseja excluir este post?')) {
        // Implementar AJAX para deletar
        fetch(`/admin/campaigns/posts/${postId}`, {
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
                alert('Erro ao excluir post: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao excluir post');
        });
    }
}

function deleteFolder(folderId) {
    if (confirm('Tem certeza que deseja excluir esta pasta?')) {
        // Implementar AJAX para deletar
        fetch(`/admin/campaigns/folders/${folderId}`, {
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
                alert('Erro ao excluir pasta: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao excluir pasta');
        });
    }
}

function deleteVideo(videoId) {
    if (confirm('Tem certeza que deseja excluir este vídeo?')) {
        // Implementar AJAX para deletar
        fetch(`/admin/campaigns/videos/${videoId}`, {
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
                alert('Erro ao excluir vídeo: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao excluir vídeo');
        });
    }
}

function deleteMiscellaneous(miscId) {
    if (confirm('Tem certeza que deseja excluir este item?')) {
        // Implementar AJAX para deletar
        fetch(`/admin/campaigns/miscellaneous/${miscId}`, {
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
                alert('Erro ao excluir item: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erro ao excluir item');
        });
    }
}
</script>
@endsection
