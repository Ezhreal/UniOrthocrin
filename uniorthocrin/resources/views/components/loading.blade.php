@props(['size' => 'md', 'text' => 'Carregando...', 'overlay' => false])

@php
$sizeClasses = [
    'sm' => 'w-4 h-4',
    'md' => 'w-8 h-8', 
    'lg' => 'w-12 h-12',
    'xl' => 'w-16 h-16'
];
@endphp

<div @if($overlay) class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" @else class="flex items-center justify-center" @endif>
    <div class="flex flex-col items-center space-y-2">
        <div class="animate-spin rounded-full border-4 border-gray-200 border-t-blue-600 {{ $sizeClasses[$size] }}"></div>
        @if($text)
            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $text }}</p>
        @endif
    </div>
</div>

