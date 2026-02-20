@extends('admin.layouts.app')

@section('title', 'Como usar - Admin')

@section('content')
<div class="space-modern">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-modern-title">Como usar</h1>
            <p class="text-modern-subtitle">Fluxos e orientações para cada área do painel</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
        @foreach($topics as $slug => $t)
            <a href="{{ route('admin.help.show', $slug) }}"
               class="stats-card-modern hover-modern-lift block group">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">{{ $t['title'] }}</p>
                        <p class="text-sm text-gray-500 mt-1">Ver fluxo de uso</p>
                    </div>
                    <div class="stats-card-icon-modern stats-card-icon-primary flex-shrink-0">
                        <i class="fas {{ $t['icon'] }} text-xl"></i>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm text-primary-500 group-hover:text-primary-600">
                    <span>Ver passo a passo</span>
                    <i class="fas fa-arrow-right ml-2"></i>
                </div>
            </a>
        @endforeach
    </div>
</div>
@endsection
