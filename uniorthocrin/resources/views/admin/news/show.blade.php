@extends('admin.layouts.app')

@section('title', 'Visualizar Notícia - Admin')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-modern-title">{{ $news->title }}</h1>
            <p class="text-modern-subtitle">Detalhes da notícia</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.news.edit', $news) }}" class="btn-modern-primary">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('admin.news.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <div class="grid-modern grid-modern-3">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-modern">
            <!-- News Info Card -->
            <div class="modern-card hover-modern-lift">
                <div class="modern-card-header">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-info-circle text-primary-500"></i>
                        </div>
                        <div>
                            <h3 class="modern-card-title">Informações da Notícia</h3>
                            <p class="modern-card-subtitle">Dados principais</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-modern-sm">
                    <div class="grid-modern grid-modern-2">
                        <div>
                            <label class="form-label-modern">Título</label>
                            <p class="text-modern-body font-medium">{{ $news->title }}</p>
                        </div>
                        <div>
                            <label class="form-label-modern">Categoria</label>
                            <span class="badge-modern badge-modern-primary">{{ $news->category->name ?? 'Sem categoria' }}</span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="form-label-modern">Conteúdo</label>
                        <div class="prose max-w-none">
                            <p class="text-modern-body whitespace-pre-wrap">{{ $news->content }}</p>
                        </div>
                    </div>
                    
                    <div>
                        <label class="form-label-modern">Status</label>
                        <span class="badge-modern {{ $news->status === 'published' ? 'badge-modern-success' : 'badge-modern-warning' }}">
                            {{ $news->status === 'published' ? 'Publicado' : 'Rascunho' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Image Card -->
            <div class="modern-card hover-modern-lift">
                <div class="modern-card-header">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 bg-secondary-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-image text-secondary-500"></i>
                        </div>
                        <div>
                            <h3 class="modern-card-title">Imagem da Notícia</h3>
                            <p class="modern-card-subtitle">Imagem principal</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-modern-sm">
                    @if($news->image)
                    <div class="relative">
                        <img src="{{ $news->image->url }}" alt="{{ $news->title }}" 
                             class="w-full h-64 object-cover rounded-xl">
                        <div class="absolute top-4 right-4">
                            <form method="POST" action="{{ route('admin.news.image.delete', $news) }}" 
                                  onsubmit="return confirm('Tem certeza que deseja excluir esta imagem?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-error-500 text-white p-2 rounded-lg hover:bg-error-600 transition-colors duration-200">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-12">
                        <i class="fas fa-image text-gray-300 text-6xl mb-4"></i>
                        <p class="text-modern-caption">Nenhuma imagem encontrada</p>
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
                        $permission = $news->permissions->where('user_type_id', $userType->id)->first();
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
                            <p class="modern-card-subtitle">Informações da notícia</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-modern-sm">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-primary-50 rounded-xl">
                            <div class="text-2xl font-bold text-primary-500">{{ $news->image ? '1' : '0' }}</div>
                            <div class="text-modern-caption">Imagens</div>
                        </div>
                        <div class="text-center p-4 bg-secondary-50 rounded-xl">
                            <div class="text-2xl font-bold text-secondary-500">{{ $news->permissions->count() }}</div>
                            <div class="text-modern-caption">Permissões</div>
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-200 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-modern-caption">Criado em:</span>
                            <span class="text-modern-body">{{ $news->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-modern-caption">Atualizado em:</span>
                            <span class="text-modern-body">{{ $news->updated_at->format('d/m/Y H:i') }}</span>
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
                    <a href="{{ route('admin.news.edit', $news) }}" 
                       class="w-full btn-modern-primary text-center block">
                        <i class="fas fa-edit mr-2"></i>
                        Editar Notícia
                    </a>
                    
                    <button onclick="duplicateNews()" 
                            class="w-full btn-modern-secondary text-center block">
                        <i class="fas fa-copy mr-2"></i>
                        Duplicar Notícia
                    </button>
                    
                    <form method="POST" action="{{ route('admin.news.destroy', $news) }}" 
                          class="inline w-full" onsubmit="return confirm('Tem certeza que deseja excluir esta notícia?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full btn-modern-danger text-center block">
                            <i class="fas fa-trash mr-2"></i>
                            Excluir Notícia
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function duplicateNews() {
    if (confirm('Deseja duplicar esta notícia?')) {
        // Implementar duplicação
        alert('Funcionalidade de duplicação será implementada em breve');
    }
}
</script>
@endsection