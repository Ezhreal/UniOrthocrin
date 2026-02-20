@extends('admin.layouts.app')

@section('title', $data['title'] . ' - Como usar')

@section('content')
<div class="space-modern">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.help.index') }}"
               class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div>
                <h1 class="text-modern-title">{{ $data['title'] }}</h1>
                <p class="text-modern-subtitle">Como cadastrar e o que pode subir em cada tipo</p>
            </div>
        </div>
        @if(!empty($data['route']))
            <a href="{{ route($data['route']) }}" class="btn-modern-primary">
                <i class="fas fa-external-link-alt mr-2"></i>
                Ir para a tela
            </a>
        @endif
    </div>

    @if(!empty($data['permissions_note']))
        <div class="mt-6 p-4 rounded-lg bg-amber-50 border border-amber-200">
            <h3 class="text-sm font-semibold text-amber-800 mb-2"><i class="fas fa-lock mr-1"></i> Quem pode / Permissões</h3>
            <div class="text-sm text-amber-900 prose prose-sm max-w-none">
                {!! \Illuminate\Support\Str::markdown($data['permissions_note']) !!}
            </div>
        </div>
    @endif

    @if(!empty($data['summary']) && is_array($data['summary']))
        <div class="mt-6 p-4 rounded-lg bg-gray-50 border border-gray-200">
            <h3 class="text-sm font-semibold text-gray-800 mb-2"><i class="fas fa-list mr-1"></i> Resumo do que a tela aceita</h3>
            <ul class="text-sm text-gray-700 space-y-1 list-disc list-inside">
                @foreach($data['summary'] as $item)
                    <li>{{ $item }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(!empty($data['sections']) && is_array($data['sections']))
        <div class="mt-8">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Como cadastrar cada tipo</h2>
            <div class="grid gap-6 sm:grid-cols-1 lg:grid-cols-1">
                @foreach($data['sections'] as $section)
                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/80">
                            <h3 class="font-semibold text-gray-900 flex items-center gap-2">
                                @if(!empty($section['icon']))
                                    <i class="fas {{ $section['icon'] }} text-primary-500"></i>
                                @endif
                                {{ $section['title'] ?? 'Seção' }}
                            </h3>
                        </div>
                        <div class="p-5 flex flex-col md:flex-row gap-6">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Como cadastrar</h4>
                                @if(!empty($section['fields']) && is_array($section['fields']))
                                    <ul class="text-sm text-gray-600 space-y-1 list-disc list-inside mb-3">
                                        @foreach($section['fields'] as $f)
                                            <li>{{ $f }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                                @if(!empty($section['steps']) && is_array($section['steps']))
                                    <div class="space-y-2 mt-2">
                                        @foreach($section['steps'] as $s)
                                            @php
                                                $st = is_array($s) ? $s[0] : 'Passo';
                                                $sb = is_array($s) ? $s[1] : $s;
                                            @endphp
                                            <div>
                                                <span class="font-medium text-gray-800">{{ $st }}</span>
                                                <div class="text-gray-600 prose prose-sm max-w-none mt-0.5">
                                                    {!! \Illuminate\Support\Str::markdown($sb) !!}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <div class="md:w-72 flex-shrink-0">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">O que pode subir</h4>
                                @if(!empty($section['uploads']) && is_array($section['uploads']))
                                    <ul class="space-y-2">
                                        @foreach($section['uploads'] as $up)
                                            <li class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-primary-50 text-primary-800 text-sm border border-primary-100 w-full">
                                                <i class="fas fa-upload text-primary-500 flex-shrink-0"></i>
                                                <span>{{ $up }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="text-sm text-gray-500 italic">Nenhum arquivo para este tipo.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="mt-8 max-w-3xl">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Passo a passo</h2>
        <ol class="space-y-6">
            @foreach($data['steps'] as $i => $step)
                @php
                    $stepTitle = is_array($step) ? $step[0] : 'Passo ' . ($i + 1);
                    $stepBody = is_array($step) ? $step[1] : $step;
                @endphp
                <li class="flex gap-4">
                    <span class="flex-shrink-0 w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center font-semibold">
                        {{ $i + 1 }}
                    </span>
                    <div class="flex-1 pb-6 border-b border-gray-100 last:border-0">
                        <h3 class="font-semibold text-gray-900">{{ $stepTitle }}</h3>
                        <div class="mt-2 text-gray-600 prose prose-sm max-w-none">
                            {!! \Illuminate\Support\Str::markdown($stepBody) !!}
                        </div>
                    </div>
                </li>
            @endforeach
        </ol>
    </div>

    <div class="mt-10 pt-6 border-t border-gray-200">
        <p class="text-modern-caption mb-3">Outros tópicos:</p>
        <div class="flex flex-wrap gap-2">
            @foreach($allTopics as $slug => $t)
                @if($slug !== $topic)
                    <a href="{{ route('admin.help.show', $slug) }}"
                       class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gray-100 text-gray-700 hover:bg-primary-50 hover:text-primary-600 text-sm transition-colors">
                        <i class="fas {{ $t['icon'] }} mr-2 text-gray-400"></i>
                        {{ $t['title'] }}
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</div>
@endsection
