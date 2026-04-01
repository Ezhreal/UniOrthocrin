@extends('admin.layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', 'Visualizar Campanha - Admin')

@section('content')
<div class="space-modern">
    <!-- Modern Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-modern-title">{{ $campaign->name }}</h1>
            <p class="text-modern-subtitle">Detalhes da campanha</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.campaigns.edit', $campaign) }}" class="btn-modern-primary">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="{{ route('admin.campaigns.index') }}" class="btn-modern-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </div>

    <div class="grid-modern grid-modern-3">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-modern">
            <!-- Campaign Info Card -->
            <div class="modern-card hover-modern-lift">
                <div class="modern-card-header">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-info-circle text-primary-500"></i>
                        </div>
                        <div>
                            <h3 class="modern-card-title">Informações da Campanha</h3>
                            <p class="modern-card-subtitle">Dados principais</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-modern-sm">
                    <!-- Preview da Imagem (Thumbnail) -->
                    @if($campaign->thumbnail_path)
                    <div class="mb-6">
                        <label class="form-label-modern">Preview da Campanha</label>
                        <div class="w-full max-w-md">
                            <img src="{{ url('/private/' . str_replace('private/', '', $campaign->thumbnail_path)) }}" 
                                 alt="Preview da campanha {{ $campaign->name }}" 
                                 class="w-full h-auto rounded-lg shadow-lg object-cover">
                        </div>
                    </div>
                    @endif
                    
                    <div class="grid-modern grid-modern-2">
                        <div>
                            <label class="form-label-modern">Nome</label>
                            <p class="text-modern-body font-medium">{{ $campaign->name }}</p>
                        </div>
                        <div>
                            <label class="form-label-modern">Status</label>
                            <span class="badge-modern {{ $campaign->status === 'active' ? 'badge-modern-success' : 'badge-modern-error' }}">
                                {{ $campaign->status === 'active' ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="form-label-modern">Descrição</label>
                        <p class="text-modern-body">{{ $campaign->description ?: 'Sem descrição' }}</p>
                    </div>
                    
                    <div class="grid-modern grid-modern-2">
                        <div>
                            <label class="form-label-modern">Data de Início</label>
                            <p class="text-modern-body">{{ $campaign->start_date ? $campaign->start_date->format('d/m/Y') : 'Não definida' }}</p>
                        </div>
                        <div>
                            <label class="form-label-modern">Data de Fim</label>
                            <p class="text-modern-body">{{ $campaign->end_date ? $campaign->end_date->format('d/m/Y') : 'Não definida' }}</p>
                        </div>
                    </div>
                    
                    <div class="grid-modern grid-modern-2">
                        <div>
                            <label class="form-label-modern">Visibilidade</label>
                            <span class="badge-modern {{ $campaign->visible_franchise_only ? 'badge-modern-warning' : 'badge-modern-gray' }}">
                                {{ $campaign->visible_franchise_only ? 'Apenas Franqueados' : 'Todos os Usuários' }}
                            </span>
                        </div>
                        <div>
                            <label class="form-label-modern">Campanha Destaque</label>
                            <span class="badge-modern {{ $campaign->is_featured ? 'badge-modern-warning' : 'badge-modern-gray' }}">
                                {{ $campaign->is_featured ? 'Sim' : 'Não' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Banners em destaque -->
            @if($campaign->is_featured && ($campaign->banner_path || $campaign->banner_mobile_path))
            <div class="modern-card hover-modern-lift">
                <div class="modern-card-header">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 bg-warning-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-star text-warning-500"></i>
                        </div>
                        <div>
                            <h3 class="modern-card-title">Banners (destaque no dashboard)</h3>
                            <p class="modern-card-subtitle">Desktop e mobile</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-modern-sm grid grid-cols-1 md:grid-cols-2 gap-4">
                    @if($campaign->banner_path)
                    <div>
                        <p class="text-xs text-gray-500 mb-2">Desktop</p>
                        <img src="{{ $campaign->banner_desktop_url }}" alt="Banner desktop" class="w-full h-auto rounded-lg shadow object-cover">
                    </div>
                    @endif
                    @if($campaign->banner_mobile_path)
                    <div>
                        <p class="text-xs text-gray-500 mb-2">Mobile (1080×1080)</p>
                        <img src="{{ $campaign->banner_mobile_url }}" alt="Banner mobile" class="w-full max-w-sm mx-auto rounded-lg shadow object-cover aspect-square">
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Folhetos Card -->
            <div class="modern-card hover-modern-lift">
                <div class="modern-card-header">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-file-pdf text-primary-500"></i>
                        </div>
                        <div>
                            <h3 class="modern-card-title">Folhetos</h3>
                            <p class="modern-card-subtitle">Folhetos por estado</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-modern-sm">
                    @if($campaign->folders && $campaign->folders->count() > 0)
                    <div class="space-y-6">
                        <!-- MG -->
                        @if($campaign->folders->where('state', 'MG/SP')->count() > 0)
                        <div>
                            <h4 class="text-modern-body font-medium mb-4">Folhetos MG ({{ $campaign->folders->where('state', 'MG/SP')->count() }})</h4>
                            <div class="space-y-3">
                                @foreach($campaign->folders->where('state', 'MG/SP') as $folder)
                                <div class="flex items-center space-x-3 p-3 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <div class="flex-shrink-0">
                                        <div class="bg-gray-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-file-pdf text-gray-400 text-lg"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $folder->name }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <!-- Outros Estados -->
                        @if($campaign->folders->where('state', 'DF/ES')->count() > 0)
                        <div>
                            <h4 class="text-modern-body font-medium mb-4">Folhetos Outros Estados ({{ $campaign->folders->where('state', 'DF/ES')->count() }})</h4>
                            <div class="space-y-3">
                                @foreach($campaign->folders->where('state', 'DF/ES') as $folder)
                                <div class="flex items-center space-x-3 p-3 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <div class="flex-shrink-0">
                                        <div class="bg-gray-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-file-pdf text-gray-400 text-lg"></i>
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $folder->name }}</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="text-center py-12">
                        <i class="fas fa-file-pdf text-gray-300 text-6xl mb-4"></i>
                        <p class="text-modern-caption">Nenhum folheto encontrado</p>
                    </div>
                    @endif
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
                            <p class="modern-card-subtitle">Imagens por tipo de post</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-modern-sm">
                    @if($campaign->posts && $campaign->posts->count() > 0)
                    <div class="space-y-8">
                        <!-- Feed -->
                        @if($campaign->posts->where('type', 'feeds')->count() > 0)
                        <div>
                            <h4 class="text-modern-body font-medium mb-4">Posts Feed ({{ $campaign->posts->where('type', 'feeds')->count() }})</h4>
                            <div class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
                                @foreach($campaign->posts->where('type', 'feeds') as $post)
                                <div class="relative group">
                                    <div class="w-full h-20 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden border border-gray-200">
                                        @if($post->mainImage())
                                            <img src="{{ url('/private/' . str_replace('private/', '', $post->mainImage()->path)) }}" alt="{{ $post->name }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-image text-gray-400 text-lg"></i>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <!-- Stories MG -->
                        @if($campaign->posts->where('type', 'stories_mg_sp')->count() > 0)
                        <div>
                            <h4 class="text-modern-body font-medium mb-4">Stories MG ({{ $campaign->posts->where('type', 'stories_mg_sp')->count() }})</h4>
                            <div class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
                                @foreach($campaign->posts->where('type', 'stories_mg_sp') as $post)
                                <div class="relative group">
                                    <div class="w-full h-20 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden border border-gray-200">
                                        @if($post->mainImage())
                                            <img src="{{ url('/private/' . str_replace('private/', '', $post->mainImage()->path)) }}" alt="{{ $post->name }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-image text-gray-400 text-lg"></i>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <!-- Stories Outros Estados -->
                        @if($campaign->posts->where('type', 'stories_df_es')->count() > 0)
                        <div>
                            <h4 class="text-modern-body font-medium mb-4">Stories Outros Estados ({{ $campaign->posts->where('type', 'stories_df_es')->count() }})</h4>
                            <div class="grid grid-cols-4 md:grid-cols-6 lg:grid-cols-8 gap-3">
                                @foreach($campaign->posts->where('type', 'stories_df_es') as $post)
                                <div class="relative group">
                                    <div class="w-full h-20 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden border border-gray-200">
                                        @if($post->mainImage())
                                            <img src="{{ url('/private/' . str_replace('private/', '', $post->mainImage()->path)) }}" alt="{{ $post->name }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-image text-gray-400 text-lg"></i>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="text-center py-12">
                        <i class="fas fa-image text-gray-300 text-6xl mb-4"></i>
                        <p class="text-modern-caption">Nenhum post encontrado</p>
                    </div>
                    @endif
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
                            <p class="modern-card-subtitle">Lista de vídeos por tipo</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-modern-sm">
                    @if($campaign->videos && $campaign->videos->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Arquivo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($campaign->videos as $video)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $video->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            {{ $video->type === 'reels' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $video->type === 'reels' ? 'Reels' : 'Vídeos TV - Campanha' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <i class="fas fa-video text-gray-400 mr-2"></i>
                                            <span class="text-sm text-gray-500">
                                                @if($video->primaryFile())
                                                    <a href="{{ url('/private/' . str_replace('private/', '', $video->primaryFile()->path)) }}" target="_blank" class="text-primary-600 hover:text-primary-800">
                                                        Ver vídeo
                                                    </a>
                                                @else
                                                    Sem arquivo
                                                @endif
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <span class="text-gray-400">
                                            <i class="fas fa-eye"></i>
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-12">
                        <i class="fas fa-video text-gray-300 text-6xl mb-4"></i>
                        <p class="text-modern-caption">Nenhum vídeo encontrado</p>
                    </div>
                    @endif
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
                            <p class="modern-card-subtitle">Spot, Tag, Adesivo, Banner, Faixa e Materiais Internos</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-modern-sm">
                    @if($campaign->miscellaneous && $campaign->miscellaneous->count() > 0)
                    <div class="space-y-6">
                        <!-- Spot -->
                        @if($campaign->miscellaneous->where('type', 'spot')->count() > 0)
                        <div>
                            <h4 class="text-modern-body font-medium mb-4">Spot ({{ $campaign->miscellaneous->where('type', 'spot')->count() }})</h4>
                            <div class="space-y-3">
                                @foreach($campaign->miscellaneous->where('type', 'spot') as $misc)
                                <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="bg-gray-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-broadcast-tower text-gray-400 text-lg"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $misc->name }}</p>
                                        </div>
                                    </div>
                                    <span class="text-gray-400">
                                        <i class="fas fa-eye text-sm"></i>
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <!-- Tag -->
                        @if($campaign->miscellaneous->where('type', 'tag')->count() > 0)
                        <div>
                            <h4 class="text-modern-body font-medium mb-4">Tag ({{ $campaign->miscellaneous->where('type', 'tag')->count() }})</h4>
                            <div class="space-y-3">
                                @foreach($campaign->miscellaneous->where('type', 'tag') as $misc)
                                <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="bg-gray-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-tag text-gray-400 text-lg"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $misc->name }}</p>
                                        </div>
                                    </div>
                                    <span class="text-gray-400">
                                        <i class="fas fa-eye text-sm"></i>
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <!-- Adesivo -->
                        @if($campaign->miscellaneous->where('type', 'adesivo')->count() > 0)
                        <div>
                            <h4 class="text-modern-body font-medium mb-4">Adesivo ({{ $campaign->miscellaneous->where('type', 'adesivo')->count() }})</h4>
                            <div class="space-y-3">
                                @foreach($campaign->miscellaneous->where('type', 'adesivo') as $misc)
                                <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0"><div class="bg-gray-100 rounded-lg flex items-center justify-center"><i class="fas fa-sticky-note text-gray-400 text-lg"></i></div></div>
                                        <div class="flex-1 min-w-0"><p class="text-sm font-medium text-gray-900 truncate">{{ $misc->name }}</p></div>
                                    </div>
                                    <span class="text-gray-400"><i class="fas fa-eye text-sm"></i></span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <!-- Banner -->
                        @if($campaign->miscellaneous->where('type', 'banner')->count() > 0)
                        <div>
                            <h4 class="text-modern-body font-medium mb-4">Banner ({{ $campaign->miscellaneous->where('type', 'banner')->count() }})</h4>
                            <div class="space-y-3">
                                @foreach($campaign->miscellaneous->where('type', 'banner') as $misc)
                                <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0"><div class="bg-gray-100 rounded-lg flex items-center justify-center"><i class="fas fa-image text-gray-400 text-lg"></i></div></div>
                                        <div class="flex-1 min-w-0"><p class="text-sm font-medium text-gray-900 truncate">{{ $misc->name }}</p></div>
                                    </div>
                                    <span class="text-gray-400"><i class="fas fa-eye text-sm"></i></span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <!-- Faixa -->
                        @if($campaign->miscellaneous->where('type', 'faixa')->count() > 0)
                        <div>
                            <h4 class="text-modern-body font-medium mb-4">Faixa ({{ $campaign->miscellaneous->where('type', 'faixa')->count() }})</h4>
                            <div class="space-y-3">
                                @foreach($campaign->miscellaneous->where('type', 'faixa') as $misc)
                                <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0"><div class="bg-gray-100 rounded-lg flex items-center justify-center"><i class="fas fa-align-center text-gray-400 text-lg"></i></div></div>
                                        <div class="flex-1 min-w-0"><p class="text-sm font-medium text-gray-900 truncate">{{ $misc->name }}</p></div>
                                    </div>
                                    <span class="text-gray-400"><i class="fas fa-eye text-sm"></i></span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <!-- Materiais Internos -->
                        @if($campaign->miscellaneous->where('type', 'script')->count() > 0)
                        <div>
                            <h4 class="text-modern-body font-medium mb-4">Materiais Internos ({{ $campaign->miscellaneous->where('type', 'script')->count() }})</h4>
                            <div class="space-y-3">
                                @foreach($campaign->miscellaneous->where('type', 'script') as $misc)
                                <div class="flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-shrink-0">
                                            <div class="bg-gray-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-file-alt text-gray-400 text-lg"></i>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $misc->name }}</p>
                                        </div>
                                    </div>
                                    <span class="text-gray-400">
                                        <i class="fas fa-eye text-sm"></i>
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="text-center py-12">
                        <i class="fas fa-file text-gray-300 text-6xl mb-4"></i>
                        <p class="text-modern-caption">Nenhum arquivo diverso encontrado</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-modern">
            <!-- Statistics Card -->
            <div class="modern-card hover-modern-lift">
                <div class="modern-card-header">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 bg-primary-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-chart-bar text-primary-500"></i>
                        </div>
                        <div>
                            <h3 class="modern-card-title">Estatísticas</h3>
                            <p class="modern-card-subtitle">Informações da campanha</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-modern-sm">
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-primary-50 rounded-xl">
                            <div class="text-2xl font-bold text-primary-500">{{ $campaign->posts ? $campaign->posts->count() : 0 }}</div>
                            <div class="text-modern-caption">Posts</div>
                        </div>
                        <div class="text-center p-4 bg-secondary-50 rounded-xl">
                            <div class="text-2xl font-bold text-secondary-500">{{ $campaign->folders ? $campaign->folders->count() : 0 }}</div>
                            <div class="text-modern-caption">Pastas</div>
                        </div>
                        <div class="text-center p-4 bg-success-50 rounded-xl">
                            <div class="text-2xl font-bold text-success-500">{{ $campaign->videos ? $campaign->videos->count() : 0 }}</div>
                            <div class="text-modern-caption">Vídeos</div>
                        </div>
                        <div class="text-center p-4 bg-warning-50 rounded-xl">
                            <div class="text-2xl font-bold text-warning-500">{{ $campaign->miscellaneous ? $campaign->miscellaneous->count() : 0 }}</div>
                            <div class="text-modern-caption">Arquivos</div>
                        </div>
                    </div>
                    
                    <div class="pt-4 border-t border-gray-200 space-y-3">
                        <div class="flex justify-between items-center">
                            <span class="text-modern-caption">Criado em:</span>
                            <span class="text-modern-body">{{ $campaign->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-modern-caption">Atualizado em:</span>
                            <span class="text-modern-body">{{ $campaign->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="modern-card hover-modern-lift">
                <div class="modern-card-header">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 bg-success-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-bolt text-success-500"></i>
                        </div>
                        <div>
                            <h3 class="modern-card-title">Ações Rápidas</h3>
                            <p class="modern-card-subtitle">Operações comuns</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-modern-sm">
                    <a href="{{ route('admin.campaigns.edit', $campaign) }}" 
                       class="w-full btn-modern-primary text-center block">
                        <i class="fas fa-edit mr-2"></i>
                        Editar Campanha
                    </a>
                    
                    <button onclick="duplicateCampaign()" 
                            class="w-full btn-modern-secondary text-center block">
                        <i class="fas fa-copy mr-2"></i>
                        Duplicar Campanha
                    </button>
                    
                    <form method="POST" action="{{ route('admin.campaigns.destroy', $campaign) }}" 
                          class="inline w-full" onsubmit="return confirm('Tem certeza que deseja excluir esta campanha?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full btn-modern-danger text-center block">
                            <i class="fas fa-trash mr-2"></i>
                            Excluir Campanha
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function duplicateCampaign() {
    if (confirm('Deseja duplicar esta campanha?')) {
        // Implementar duplicação
        alert('Funcionalidade de duplicação será implementada em breve');
    }
}
</script>
@endsection