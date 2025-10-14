@extends('admin.layouts.app')

@section('title', 'Visualizar Produto - Admin')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-modern-title">{{ $product->name }}</h1>
            <p class="text-modern-subtitle">Detalhes do produto</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.products.edit', $product) }}" class="btn-modern-primary">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <div class="grid-modern grid-modern-3">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-modern">
            <!-- Product Info Card -->
            <div class="modern-card hover-modern-lift">
                <div class="modern-card-header">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-info-circle text-primary-500"></i>
                        </div>
                        <div>
                            <h3 class="modern-card-title">Informações do Produto</h3>
                            <p class="modern-card-subtitle">Dados principais</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-modern-sm">
                    <div class="grid-modern grid-modern-2">
                        <div>
                            <label class="form-label-modern">Nome</label>
                            <p class="text-modern-body font-medium">{{ $product->name }}</p>
                        </div>
                        <div>
                            <label class="form-label-modern">Status</label>
                            <span class="badge-modern {{ $product->status === 'active' ? 'badge-modern-success' : 'badge-modern-error' }}">
                                {{ $product->status === 'active' ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="form-label-modern">Descrição</label>
                        <p class="text-modern-body">{{ $product->description ?: 'Sem descrição' }}</p>
                    </div>
                    
                    <div class="grid-modern grid-modern-2">
                        <div>
                            <label class="form-label-modern">Categoria</label>
                            <span class="badge-modern badge-modern-primary">{{ $product->category->name ?? 'Sem categoria' }}</span>
                        </div>
                        <div>
                            <label class="form-label-modern">Série</label>
                            <span class="text-modern-body">{{ $product->series->name ?? 'Sem série' }}</span>
                        </div>
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
                            <p class="modern-card-subtitle">Galeria de imagens e vídeos</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-modern-sm">
                    <!-- Galeria de Imagens -->
                    @if($product->images && $product->images->count() > 0)
                    <div>
                        <h4 class="text-modern-body font-medium mb-4">Galeria de Imagens ({{ $product->images->count() }})</h4>
                        <div class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
                            @foreach($product->images as $image)
                            <div class="w-full h-20 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                                @if($image->thumbnail_url || $image->url)
                                    <img src="{{ $image->thumbnail_url ?? $image->url }}" alt="Imagem do produto" class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-image text-gray-400 text-lg"></i>
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Lista de Vídeos -->
                    @if($product->videos && $product->videos->count() > 0)
                    <div>
                        <h4 class="text-modern-body font-medium mb-4">Galeria de Vídeos ({{ $product->videos->count() }})</h4>
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="divide-y divide-gray-200">
                                @foreach($product->videos as $video)
                                <div class="flex items-center justify-between p-3 hover:bg-gray-50">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <i class="fas fa-video text-gray-400 text-lg"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $video->original_name }}</p>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($product->images->count() == 0 && $product->videos->count() == 0)
                    <div class="text-center py-12">
                        <i class="fas fa-images text-gray-300 text-6xl mb-4"></i>
                        <p class="text-modern-caption">Nenhuma mídia encontrada</p>
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
                        $permission = $product->permissions->where('user_type_id', $userType->id)->first();
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
                            <p class="modern-card-subtitle">Informações do produto</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-modern-sm">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-primary-50 rounded-xl">
                            <div class="text-2xl font-bold text-primary-500">{{ $product->images->count() }}</div>
                            <div class="text-modern-caption">Imagens</div>
                        </div>
                        <div class="text-center p-4 bg-secondary-50 rounded-xl">
                            <div class="text-2xl font-bold text-secondary-500">{{ $product->videos->count() }}</div>
                            <div class="text-modern-caption">Vídeos</div>
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-200 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-modern-caption">Criado em:</span>
                            <span class="text-modern-body">{{ $product->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-modern-caption">Atualizado em:</span>
                            <span class="text-modern-body">{{ $product->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-modern-caption">Permissões:</span>
                            <span class="text-modern-body">{{ $product->permissions->count() }}</span>
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
                    <a href="{{ route('admin.products.edit', $product) }}" 
                       class="w-full btn-modern-primary text-center block">
                        <i class="fas fa-edit mr-2"></i>
                        Editar Produto
                    </a>
                    
                    <button onclick="duplicateProduct()" 
                            class="w-full btn-modern-secondary text-center block">
                        <i class="fas fa-copy mr-2"></i>
                        Duplicar Produto
                    </button>
                    
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" 
                          class="inline w-full" onsubmit="return confirm('Tem certeza que deseja excluir este produto?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full btn-modern-danger text-center block">
                            <i class="fas fa-trash mr-2"></i>
                            Excluir Produto
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function duplicateProduct() {
    if (confirm('Deseja duplicar este produto?')) {
        // Implementar duplicação
        alert('Funcionalidade de duplicação será implementada em breve');
    }
}
</script>
@endsection