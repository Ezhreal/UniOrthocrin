@extends('admin.layouts.app')

@section('title', 'Visualizar Item - Biblioteca')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-modern-title">{{ $library->name }}</h1>
            <p class="text-modern-subtitle">Detalhes do item da biblioteca</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.library.edit', $library) }}" class="btn-modern-primary">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('admin.library.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <div class="grid-modern grid-modern-3">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-modern">
            <!-- Item Info Card -->
            <div class="modern-card hover-modern-lift">
                <div class="modern-card-header">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-info-circle text-primary-500"></i>
                        </div>
                        <div>
                            <h3 class="modern-card-title">Informações do Item</h3>
                            <p class="modern-card-subtitle">Dados principais</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-modern-sm">
                    <div class="grid-modern grid-modern-2">
                        <div>
                            <label class="form-label-modern">Nome</label>
                            <p class="text-modern-body font-medium">{{ $library->name }}</p>
                        </div>
                        <div>
                            <label class="form-label-modern">Categoria</label>
                            <span class="badge-modern badge-modern-primary">{{ $library->category->name ?? 'Sem categoria' }}</span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="form-label-modern">Descrição</label>
                        <p class="text-modern-body">{{ $library->description ?: 'Sem descrição' }}</p>
                    </div>
                    
                    <div>
                        <label class="form-label-modern">Status</label>
                        <span class="badge-modern {{ $library->status === 'active' ? 'badge-modern-success' : 'badge-modern-error' }}">
                            {{ $library->status === 'active' ? 'Ativo' : 'Inativo' }}
                        </span>
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
                    @if($library->files && $library->files->count() > 0)
                    <div>
                        <h4 class="text-modern-body font-medium mb-4">Galeria de Arquivos ({{ $library->files->count() }})</h4>
                        <div class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
                            @foreach($library->files as $file)
                            <div class="relative group">
                                @if($file->isImage())
                                    <img src="{{ $file->thumbnail_url ?? $file->url }}" alt="{{ $file->name }}" 
                                         class="w-full h-20 object-cover rounded-lg hover:scale-105 transition-transform duration-200 cursor-pointer"
                                         onclick="openFileModal('{{ $file->url }}', '{{ $file->name }}')">
                                @elseif($file->isVideo())
                                    <div class="w-full h-20 bg-gray-100 rounded-lg flex items-center justify-center relative overflow-hidden">
                                        @if($file->thumbnail_url)
                                            <img src="{{ $file->thumbnail_url }}" alt="Thumbnail do vídeo" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-video text-gray-400 text-xl"></i>
                                        @endif
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 flex items-center justify-center">
                                            <i class="fas fa-play opacity-0 group-hover:opacity-100 transition-opacity duration-200 text-white text-lg"></i>
                                        </div>
                                    </div>
                                @else
                                    <div class="w-full h-20 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-file text-gray-400 text-xl"></i>
                                    </div>
                                @endif
                                <p class="text-xs text-gray-600 mt-1 truncate">{{ $file->name }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="text-center py-12">
                        <i class="fas fa-folder-open text-gray-300 text-6xl mb-4"></i>
                        <p class="text-modern-caption">Nenhum arquivo encontrado</p>
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
                            <p class="modern-card-subtitle">Acesso por perfil</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-modern-sm">
                    @foreach($userTypes as $userType)
                    @php
                        $permission = $library->permissions->where('user_type_id', $userType->id)->first();
                    @endphp
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-xl">
                        <div class="flex items-center space-x-3">
                            <div class="h-8 w-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user text-gray-600 text-sm"></i>
                            </div>
                            <div>
                                <h4 class="text-modern-body font-medium">{{ $userType->name }}</h4>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if($permission && $permission->can_view)
                                <span class="badge-modern badge-modern-success text-xs">Ver</span>
                            @else
                                <span class="badge-modern badge-modern-error text-xs">Sem acesso</span>
                            @endif
                            
                            @if($permission && $permission->can_download)
                                <span class="badge-modern badge-modern-success text-xs">Baixar</span>
                            @else
                                <span class="badge-modern badge-modern-gray text-xs">Sem download</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Statistics Card -->
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
                        <div class="text-center p-4 bg-primary-50 rounded-xl">
                            <div class="text-2xl font-bold text-primary-500">{{ $library->files ? $library->files->count() : 0 }}</div>
                            <div class="text-modern-caption">Arquivos</div>
                        </div>
                        <div class="text-center p-4 bg-secondary-50 rounded-xl">
                            <div class="text-2xl font-bold text-secondary-500">{{ $library->permissions ? $library->permissions->count() : 0 }}</div>
                            <div class="text-modern-caption">Permissões</div>
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-200 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-modern-caption">Criado em:</span>
                            <span class="text-modern-body">{{ $library->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-modern-caption">Atualizado em:</span>
                            <span class="text-modern-body">{{ $library->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="modern-card hover-modern-lift">
                <div class="modern-card-header">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 bg-warning-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-bolt text-warning-500"></i>
                        </div>
                        <div>
                            <h3 class="modern-card-title">Ações</h3>
                            <p class="modern-card-subtitle">Operações comuns</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-modern-sm">
                    <a href="{{ route('admin.library.edit', $library) }}" 
                       class="w-full btn-modern-primary text-center block">
                        <i class="fas fa-edit mr-2"></i>
                        Editar Item
                    </a>
                    
                    <button onclick="duplicateItem()" 
                            class="w-full btn-modern-secondary text-center block">
                        <i class="fas fa-copy mr-2"></i>
                        Duplicar Item
                    </button>
                    
                    <form method="POST" action="{{ route('admin.library.destroy', $library) }}" 
                          class="inline w-full" onsubmit="return confirm('Tem certeza que deseja excluir este item?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full btn-modern-danger text-center block">
                            <i class="fas fa-trash mr-2"></i>
                            Excluir Item
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function duplicateItem() {
    if (confirm('Deseja duplicar este item?')) {
        // Implementar duplicação
        alert('Funcionalidade de duplicação será implementada em breve');
    }
}
</script>
@endsection