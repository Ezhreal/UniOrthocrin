@extends('layouts.app')

@section('content')
<div class="bg-[#F9F9F9] min-h-screen">
    <!-- Banner com breadcrumb e título -->
    <div class="bg-[#910039] w-full py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-white">
                <div class="text-sm mb-2">Home > Marketing</div>
                <h1 class="text-3xl font-bold">Marketing</h1>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 py-12">
        @if(isset($campaigns) && ($campaigns['featured'] || $campaigns['others']->count() > 0))
        
        <!-- Campanha em Destaque -->
        @if($campaigns['featured'])
        <div class="mb-12">
            <h2 class="text-2xl font-bold text-[#910039] mb-6">Campanha em Destaque</h2>
            
            <div class="bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden">
                <div class="flex flex-col lg:flex-row">
                    <!-- Coluna da Esquerda - Imagem -->
                    <div class="lg:w-1/2 p-8 bg-gradient-to-br from-pink-50 to-pink-100">
                        <div class="text-center">
                            @if($campaigns['featured']->getMainThumbnailAttribute())
                                <img src="{{ $campaigns['featured']->getMainThumbnailAttribute() }}" 
                                     alt="{{ $campaigns['featured']->name }}" 
                                     class="w-full max-w-md mx-auto rounded-lg shadow-md">
                            @else
                                <div class="w-full h-64 bg-gradient-to-br from-pink-200 to-pink-300 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-bullhorn text-6xl text-pink-400"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Coluna da Direita - Informações -->
                    <div class="lg:w-1/2 p-8">
                        <h3 class="text-2xl font-bold text-[#910039]">{{ $campaigns['featured']->name }}</h3>
                        
                        <!-- Período de Vigência -->
                        <div class="mb-4">
                            <span class="text-sm text-gray-500">Vigência: 
                            @if($campaigns['featured']->start_date && $campaigns['featured']->end_date)
                                    {{ $campaigns['featured']->start_date->format('d/m/Y') }} a {{ $campaigns['featured']->end_date->format('d/m/Y') }}
                                @elseif($campaigns['featured']->start_date)
                                    A partir de {{ $campaigns['featured']->start_date->format('d/m/Y') }}
                                @elseif($campaigns['featured']->end_date)
                                    Até {{ $campaigns['featured']->end_date->format('d/m/Y') }}
                                @else
                                    Vigência indefinida
                                @endif
                            </span>
                        </div>
                        
                        <!-- Lista de Assets -->
                        <div class="mb-6">
                            @if($campaigns['featured']->posts()->active()->count() > 0)
                            <div class="flex items-center justify-between py-1 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <span class="text-gray-500 text-sm">Posts</span>
                                    <span class="text-gray-500 text-sm">
                                        @php
                                            $postTypes = $campaigns['featured']->posts()->active()->pluck('type')->unique();
                                            $typeLabels = $postTypes->map(function($type) {
                                                return match($type) {
                                                    'feeds' => 'Feed',
                                                    'stories_mg_sp' => 'Stories',
                                                    'stories_df_es' => 'Stories',
                                                    default => ucfirst($type)
                                                };
                                            })->implode(' • ');
                                        @endphp
                                        {{ $typeLabels }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('marketing.detail', $campaigns['featured']->id) }}" 
                                       class="text-[#910039] hover:text-[#7a0030] p-1">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="inline-flex items-center gap-1">
                                        @csrf
                                        <input type="hidden" name="content_type" value="marketing">
                                        <input type="hidden" name="content_id" value="{{ $campaigns['featured']->id }}">
                                        <input type="hidden" name="type" value="image">
                                        <button type="submit" class="inline-flex items-center gap-1 text-[#910039] text-xs">
                                            <i class="fa-solid fa-download"></i>
                                            Download .zip
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endif
                            
                            @if($campaigns['featured']->folders()->active()->count() > 0)
                            <div class="flex items-center justify-between py-1 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <span class="text-gray-500 text-sm">Folhetos</span>
                                    <span class="text-gray-500 text-sm">
                                        @php
                                            $folderStates = $campaigns['featured']->folders()->active()->pluck('state')->unique();
                                            $stateLabels = $folderStates->map(function($state) {
                                                return $state;
                                            })->implode(' • ');
                                        @endphp
                                        {{ $stateLabels }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('marketing.detail', $campaigns['featured']->id) }}" 
                                       class="text-[#910039] hover:text-[#7a0030] p-1">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="inline-flex items-center gap-1">
                                        @csrf
                                        <input type="hidden" name="content_type" value="marketing">
                                        <input type="hidden" name="content_id" value="{{ $campaigns['featured']->id }}">
                                        <input type="hidden" name="type" value="pdf">
                                        <button type="submit" class="inline-flex items-center gap-1 text-[#910039] text-xs">
                                            <i class="fa-solid fa-download"></i>
                                            Download .zip
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endif
                            
                            @if($campaigns['featured']->videos()->active()->count() > 0)
                            <div class="flex items-center justify-between py-1 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <span class="text-gray-500 text-sm">Vídeos</span>
                                    <span class="text-gray-500 text-sm">
                                        @php
                                            $videoTypes = $campaigns['featured']->videos()->active()->pluck('type')->unique();
                                            $videoTypeLabels = $videoTypes->map(function($type) {
                                                return match($type) {
                                                    'reels' => 'Reels',
                                                    'marketing_campaigns' => 'Campanha',
                                                    default => ucfirst($type)
                                                };
                                            })->implode(' • ');
                                        @endphp
                                        {{ $videoTypeLabels }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('marketing.detail', $campaigns['featured']->id) }}" 
                                       class="text-[#910039] hover:text-[#7a0030] p-1">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="inline-flex items-center gap-1">
                                        @csrf
                                        <input type="hidden" name="content_type" value="marketing">
                                        <input type="hidden" name="content_id" value="{{ $campaigns['featured']->id }}">
                                        <input type="hidden" name="type" value="video">
                                        <button type="submit" class="inline-flex items-center gap-1 text-[#910039] text-xs">
                                            <i class="fa-solid fa-download"></i>
                                            Download .zip
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endif
                            
                            @if($campaigns['featured']->miscellaneous()->active()->count() > 0)
                            <div class="flex items-center justify-between py-1 border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <span class="text-gray-500 text-sm">Outros</span>
                                    <span class="text-gray-500 text-sm">
                                        @php
                                            $miscTypes = $campaigns['featured']->miscellaneous()->active()->pluck('type')->unique();
                                            $miscTypeLabels = $miscTypes->map(function($type) {
                                                return match($type) {
                                                    'spot' => 'Spot',
                                                    'tag' => 'Tag',
                                                    'sticker' => 'Adesivo',
                                                    'script' => 'Roteiro',
                                                    default => ucfirst($type)
                                                };
                                            })->implode(' • ');
                                        @endphp
                                        {{ $miscTypeLabels }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('marketing.detail', $campaigns['featured']->id) }}" 
                                       class="text-[#910039] hover:text-[#7a0030] p-1">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="inline-flex items-center gap-1">
                                        @csrf
                                        <input type="hidden" name="content_type" value="marketing">
                                        <input type="hidden" name="content_id" value="{{ $campaigns['featured']->id }}">
                                        <input type="hidden" name="type" value="all">
                                        <button type="submit" class="inline-flex items-center gap-1 text-[#910039] text-xs">
                                            <i class="fa-solid fa-download"></i>
                                            Download .zip
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @endif
                        </div>
                   
                   

                        <div class="flex items-center gap-2 text-gray-600 justify-between">
                               
                        <!-- Botão Detalhes -->
                        <a href="{{ route('marketing.detail', $campaigns['featured']->id) }}" 
                               class="bg-[#910039] text-white px-3 py-2 rounded-lg font-semibold hover:bg-[#7a0030] transition-colors duration-200 inline-block">
                                
                                Detalhes
                            </a>
                          <!-- Tamanho Total de Arquivos -->
                   @php
                       $totalFiles = $campaigns['featured']->posts()->with('files')->get()->sum(function($post) {
                           return $post->files->count();
                       }) + $campaigns['featured']->folders()->with('files')->get()->sum(function($folder) {
                           return $folder->files->count();
                       }) + $campaigns['featured']->videos()->with('files')->get()->sum(function($video) {
                           return $video->files->count();
                       }) + $campaigns['featured']->miscellaneous()->with('files')->get()->sum(function($misc) {
                           return $misc->files->count();
                       });
                   @endphp
                   
                   @if($totalFiles > 0)
                   <div class="flex items-center gap-2 text-gray-600">
                       <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="inline-flex items-center gap-1">
                           @csrf
                           <input type="hidden" name="content_type" value="marketing">
                           <input type="hidden" name="content_id" value="{{ $campaigns['featured']->id }}">
                           <input type="hidden" name="type" value="all">
                           <button type="submit" class="inline-flex items-center gap-1 text-[#910039] text-xs">
                               <i class="fa-solid fa-download"></i>
                               Download .zip
                           </button>
                       </form>
                       <span>{{ $totalFiles }} arquivos disponíveis</span>
                   </div>
                   @endif
                   </div>
                        
                   
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Outras Campanhas -->
        @if($campaigns['others']->count() > 0)
        <div>
            <h2 class="text-2xl font-bold text-[#910039] mb-6">Outras Campanhas</h2>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 mb-8">
                @foreach($campaigns['others'] as $campaign)
                <div class="bg-white border border-gray-200 rounded-lg shadow-sm flex flex-col">
                    <!-- Imagem da Campanha -->
                    <div class="w-full h-32 bg-gray-100 rounded-t-lg overflow-hidden">
                        @if($campaign->getMainThumbnailAttribute())
                            <img src="{{ $campaign->getMainThumbnailAttribute() }}" 
                                 alt="{{ $campaign->name }}" 
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-bullhorn text-4xl text-gray-400"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="text-[#910039] font-bold text-base mb-1">{{ $campaign->name }}</div>
                            <div class="text-gray-500 text-sm mb-2">
                                @if($campaign->start_date && $campaign->end_date)
                                    {{ $campaign->start_date->format('d/m/Y') }} - {{ $campaign->end_date->format('d/m/Y') }}
                                @elseif($campaign->start_date)
                                    A partir de {{ $campaign->start_date->format('d/m/Y') }}
                                @elseif($campaign->end_date)
                                    Até {{ $campaign->end_date->format('d/m/Y') }}
                                @else
                                    Vigência indefinida
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center mt-2">
                            <a href="{{ route('marketing.detail', $campaign->id) }}" class="flex items-center gap-1 text-[#910039] text-xs">
                                <i class="fa-regular fa-eye"></i>
                                Detalhes
                            </a>
                            
                            @php
                                $totalFiles = $campaign->posts()->with('files')->get()->sum(function($post) {
                                    return $post->files->count();
                                }) + $campaign->folders()->with('files')->get()->sum(function($folder) {
                                    return $folder->files->count();
                                }) + $campaign->videos()->with('files')->get()->sum(function($video) {
                                    return $video->files->count();
                                }) + $campaign->miscellaneous()->with('files')->get()->sum(function($misc) {
                                    return $misc->files->count();
                                });
                            @endphp
                            
                            @if($totalFiles > 0)
                            <form method="POST" action="{{ route('download.files') }}" onsubmit="return handleDownloadSubmit(event, this);" class="inline">
                                @csrf
                                <input type="hidden" name="content_type" value="marketing">
                                <input type="hidden" name="content_id" value="{{ $campaign->id }}">
                                <input type="hidden" name="type" value="all">
                                <button type="submit" class="inline-flex items-center gap-1">
                                    <i class="fa-solid fa-download"></i>
                                    Download .zip
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
        @else
        <!-- Estado vazio -->
        <div class="text-center text-gray-500 py-12">
            <i class="fas fa-bullhorn text-6xl mb-4 text-gray-300"></i>
            <p class="text-xl mb-2">Nenhuma campanha disponível</p>
            <p class="text-sm">Aguarde novas campanhas de marketing serem publicadas.</p>
        </div>
        @endif
    </div>
</div>
@endsection 