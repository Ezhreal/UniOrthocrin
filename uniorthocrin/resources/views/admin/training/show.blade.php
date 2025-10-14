@extends('admin.layouts.app')

@section('title', 'Visualizar Treinamento - Admin')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-modern-title">{{ $training->name }}</h1>
            <p class="text-modern-subtitle">Detalhes do treinamento</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.training.edit', $training) }}" class="btn-modern-primary">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('admin.training.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <div class="grid-modern grid-modern-3">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-modern">
            <!-- Training Info Card -->
            <div class="modern-card hover-modern-lift">
                <div class="modern-card-header">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-info-circle text-primary-500"></i>
                        </div>
                        <div>
                            <h3 class="modern-card-title">Informações do Treinamento</h3>
                            <p class="modern-card-subtitle">Dados principais</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-modern-sm">
                    <div class="grid-modern grid-modern-2">
                        <div>
                            <label class="form-label-modern">Nome</label>
                            <p class="text-modern-body font-medium">{{ $training->name }}</p>
                        </div>
                        <div>
                            <label class="form-label-modern">Categoria</label>
                            <span class="badge-modern badge-modern-primary">{{ $training->category->name ?? 'Sem categoria' }}</span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="form-label-modern">Descrição</label>
                        <p class="text-modern-body">{{ $training->description ?: 'Sem descrição' }}</p>
                    </div>
                    
                    <div>
                        <label class="form-label-modern">Status</label>
                        <span class="badge-modern {{ $training->status === 'active' ? 'badge-modern-success' : 'badge-modern-error' }}">
                            {{ $training->status === 'active' ? 'Ativo' : 'Inativo' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Videos Card -->
            <div class="modern-card hover-modern-lift">
                <div class="modern-card-header">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 bg-success-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-video text-success-500"></i>
                        </div>
                        <div>
                            <h3 class="modern-card-title">Vídeos ({{ $training->videos ? $training->videos->count() : 0 }})</h3>
                            <p class="modern-card-subtitle">Vídeos do treinamento</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-modern-sm">
                    @if($training->videos && $training->videos->count() > 0)
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="divide-y divide-gray-200">
                            @foreach($training->videos as $video)
                            <div class="flex items-center justify-between p-3 hover:bg-gray-50">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-video text-gray-400 text-lg"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $video->name }}</p>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('admin.training.videos.delete', [$training, $video]) }}" 
                                      onsubmit="return confirm('Tem certeza que deseja excluir este vídeo?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition-colors duration-200">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="text-center py-12">
                        <i class="fas fa-video text-gray-300 text-6xl mb-4"></i>
                        <p class="text-modern-caption">Nenhum vídeo encontrado</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- PDFs Card -->
            <div class="modern-card hover-modern-lift">
                <div class="modern-card-header">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 bg-secondary-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-file-pdf text-secondary-500"></i>
                        </div>
                        <div>
                            <h3 class="modern-card-title">PDFs ({{ $training->files ? $training->files->count() : 0 }})</h3>
                            <p class="modern-card-subtitle">Materiais em PDF</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-modern-sm">
                    @if($training->files && $training->files->count() > 0)
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="divide-y divide-gray-200">
                            @foreach($training->files as $file)
                            <div class="flex items-center justify-between p-3 hover:bg-gray-50">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-file-pdf text-gray-400 text-lg"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $file->name }}</p>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('admin.training.files.delete', [$training, $file]) }}" 
                                      onsubmit="return confirm('Tem certeza que deseja excluir este arquivo?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition-colors duration-200">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @else
                    <div class="text-center py-12">
                        <i class="fas fa-file-pdf text-gray-300 text-6xl mb-4"></i>
                        <p class="text-modern-caption">Nenhum PDF encontrado</p>
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
                        $permission = $training->permissions->where('user_type_id', $userType->id)->first();
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
                            <p class="modern-card-subtitle">Informações do treinamento</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-modern-sm">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-success-50 rounded-xl">
                            <div class="text-2xl font-bold text-success-500">{{ $training->videos ? $training->videos->count() : 0 }}</div>
                            <div class="text-modern-caption">Vídeos</div>
                        </div>
                        <div class="text-center p-4 bg-secondary-50 rounded-xl">
                            <div class="text-2xl font-bold text-secondary-500">{{ $training->files ? $training->files->count() : 0 }}</div>
                            <div class="text-modern-caption">PDFs</div>
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-200 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-modern-caption">Criado em:</span>
                            <span class="text-modern-body">{{ $training->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-modern-caption">Atualizado em:</span>
                            <span class="text-modern-body">{{ $training->updated_at->format('d/m/Y H:i') }}</span>
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
                    <a href="{{ route('admin.training.edit', $training) }}" 
                       class="w-full btn-modern-primary text-center block">
                        <i class="fas fa-edit mr-2"></i>
                        Editar Treinamento
                    </a>
                    
                    <button onclick="duplicateTraining()" 
                            class="w-full btn-modern-secondary text-center block">
                        <i class="fas fa-copy mr-2"></i>
                        Duplicar Treinamento
                    </button>
                    
                    <form method="POST" action="{{ route('admin.training.destroy', $training) }}" 
                          class="inline w-full" onsubmit="return confirm('Tem certeza que deseja excluir este treinamento?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full btn-modern-danger text-center block">
                            <i class="fas fa-trash mr-2"></i>
                            Excluir Treinamento
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function duplicateTraining() {
    if (confirm('Deseja duplicar este treinamento?')) {
        // Implementar duplicação
        alert('Funcionalidade de duplicação será implementada em breve');
    }
}
</script>
@endsection