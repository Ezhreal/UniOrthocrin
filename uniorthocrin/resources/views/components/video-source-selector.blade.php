{{--
  Componente reutilizável: seletor de tipo de vídeo (upload de arquivo vs. URL externa)

  Parâmetros:
    $inputId       — ID base do campo (ex: 'gallery_videos', 'videos', 'videos_reels')
    $inputName     — name do file input array (ex: 'gallery_videos[]')
    $urlFieldName  — name do campo de URL (ex: 'video_url', 'video_url_reels')
    $label         — Rótulo visível da seção (ex: 'Galeria de Vídeos')
    $description   — Texto auxiliar opcional
    $currentUrl    — URL atualmente salva (para formulários de edição)
    $currentSource — 'upload' | 'url' atualmente salvo (para formulários de edição)
    $multiple      — Se o file input aceita múltiplos arquivos (default: true)
    $showPreview   — Se deve mostrar div de preview (default: true)
--}}

@props([
    'inputId'       => 'gallery_videos',
    'inputName'     => 'gallery_videos[]',
    'urlFieldName'  => 'video_url',
    'label'         => 'Vídeo',
    'description'   => null,
    'currentUrl'    => null,
    'currentSource' => null,
    'multiple'      => true,
    'showPreview'   => true,
])

@php
    $defaultMode = ($currentSource === 'url') ? 'url' : 'upload';
    $uniqueKey   = $inputId . '_' . uniqid();
@endphp

<div class="video-source-selector" id="vss_{{ $uniqueKey }}">
    <label class="form-label-modern">{{ $label }}</label>

    @if($description)
        <p class="text-xs text-gray-500 mb-3">{{ $description }}</p>
    @endif

    {{-- Toggle de seleção de modo --}}
    <div class="flex gap-4 mb-4">
        <label class="flex items-center gap-2 cursor-pointer vss-radio-label
                      {{ $defaultMode === 'upload' ? 'text-primary-700 font-semibold' : 'text-gray-600' }}"
               data-target="vss_{{ $uniqueKey }}" data-mode="upload">
            <input type="radio"
                   name="video_source_{{ $inputId }}"
                   value="upload"
                   class="accent-primary-600 vss-radio"
                   {{ $defaultMode === 'upload' ? 'checked' : '' }}>
            <i class="fas fa-upload text-sm"></i>
            Upload de arquivo
        </label>

        <label class="flex items-center gap-2 cursor-pointer vss-radio-label
                      {{ $defaultMode === 'url' ? 'text-primary-700 font-semibold' : 'text-gray-600' }}"
               data-target="vss_{{ $uniqueKey }}" data-mode="url">
            <input type="radio"
                   name="video_source_{{ $inputId }}"
                   value="url"
                   class="accent-primary-600 vss-radio"
                   {{ $defaultMode === 'url' ? 'checked' : '' }}>
            <i class="fab fa-youtube text-sm"></i>
            Link de vídeo externo
        </label>
    </div>

    {{-- Painel: Upload de arquivo --}}
    <div class="vss-panel-upload {{ $defaultMode === 'url' ? 'hidden' : '' }}" data-panel="upload" data-owner="vss_{{ $uniqueKey }}">
        <div class="file-upload-area-modern border border-gray-300 rounded-lg p-6 my-4">
            <div class="text-center">
                <i class="fas fa-video text-4xl text-gray-400 mb-4"></i>
                <p class="text-modern-body font-medium mb-2">Arraste e solte o vídeo aqui</p>
                <p class="text-modern-caption mb-4">ou clique para selecionar</p>
                <input type="file"
                       id="{{ $inputId }}"
                       name="{{ $inputName }}"
                       class="hidden"
                       accept="video/*"
                       @if($multiple) multiple @endif>
                <label for="{{ $inputId }}" class="btn-modern-secondary cursor-pointer">
                    <i class="fas fa-plus mr-2"></i>
                    Selecionar {{ $multiple ? 'Vídeos' : 'Vídeo' }}
                </label>
            </div>
        </div>
        @if($showPreview)
            <div id="{{ $inputId }}_preview" class="mt-4"></div>
        @endif
        @error($inputId)
            <p class="form-error-modern">{{ $message }}</p>
        @enderror
        @error($inputName)
            <p class="form-error-modern">{{ $message }}</p>
        @enderror
    </div>

    {{-- Painel: URL externa --}}
    <div class="vss-panel-url {{ $defaultMode !== 'url' ? 'hidden' : '' }}" data-panel="url" data-owner="vss_{{ $uniqueKey }}">
        <div class="mt-3">
            <label for="{{ $urlFieldName }}_{{ $uniqueKey }}" class="block text-sm font-medium text-gray-700 mb-1">
                URL do vídeo (YouTube ou Vimeo)
            </label>
            <input type="url"
                   id="{{ $urlFieldName }}_{{ $uniqueKey }}"
                   name="{{ $urlFieldName }}"
                   value="{{ old($urlFieldName, $currentUrl) }}"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"
                   placeholder="https://www.youtube.com/watch?v=... ou https://vimeo.com/...">

            {{-- Preview do embed --}}
            <div id="{{ $urlFieldName }}_preview_{{ $uniqueKey }}" class="mt-4 hidden vss-preview-container">
                <p class="text-xs text-gray-500 mb-2">Pré-visualização:</p>
                <div class="relative w-full" style="padding-top: 56.25%;">
                    <iframe id="{{ $urlFieldName }}_iframe_{{ $uniqueKey }}"
                            class="absolute inset-0 w-full h-full rounded-lg border border-gray-200"
                            frameborder="0"
                            allowfullscreen
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            src="">
                    </iframe>
                </div>
            </div>
        </div>
        @error($urlFieldName)
            <p class="form-error-modern mt-2">{{ $message }}</p>
        @enderror

        @if($currentSource === 'url' && $currentUrl)
            {{-- Mostra preview ao carregar no edit --}}
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    var embedUrl = videoUrlToEmbed('{{ $currentUrl }}');
                    if (embedUrl) {
                        var iframe = document.getElementById('{{ $urlFieldName }}_iframe_{{ $uniqueKey }}');
                        var preview = document.getElementById('{{ $urlFieldName }}_preview_{{ $uniqueKey }}');
                        if (iframe && preview) {
                            iframe.src = embedUrl;
                            preview.classList.remove('hidden');
                        }
                    }
                });
            </script>
        @endif
    </div>

    {{-- Campo oculto video_source (submetido com o form) --}}
    <input type="hidden" name="video_source" value="{{ $defaultMode }}" id="vss_source_{{ $uniqueKey }}">
</div>

{{-- Script do componente (emite apenas uma vez via @once) --}}
@once
@push('scripts')
<script>
/**
 * Converte URL pública (YouTube/Vimeo) para URL de embed.
 * Disponível globalmente para uso em outros componentes.
 */
function videoUrlToEmbed(url) {
    if (!url) return null;

    // youtu.be/<id>
    var m = url.match(/youtu\.be\/([a-zA-Z0-9_\-]{11})/);
    if (m) return 'https://www.youtube.com/embed/' + m[1];

    // youtube.com/watch?v=<id>  ou /embed/<id> ou /shorts/<id>
    m = url.match(/youtube\.com\/(?:watch\?(?:.*&)?v=|embed\/|shorts\/)([a-zA-Z0-9_\-]{11})/);
    if (m) return 'https://www.youtube.com/embed/' + m[1];

    // vimeo.com/<id>
    m = url.match(/vimeo\.com\/(?:video\/)?(\d+)/);
    if (m) return 'https://player.vimeo.com/video/' + m[1];

    return null;
}

document.addEventListener('DOMContentLoaded', function () {
    // Inicializa todos os seletores de vídeo na página
    document.querySelectorAll('.vss-radio').forEach(function (radio) {
        radio.addEventListener('change', function () {
            var targetId = this.closest('.vss-radio-label')?.dataset?.target;
            var mode     = this.value;
            if (!targetId) return;

            var wrapper = document.getElementById(targetId);
            if (!wrapper) return;

            // Alterna painéis
            wrapper.querySelectorAll('[data-panel]').forEach(function (panel) {
                panel.classList.toggle('hidden', panel.dataset.panel !== mode);
            });

            // Atualiza estilo dos rótulos
            wrapper.querySelectorAll('.vss-radio-label').forEach(function (lbl) {
                var active = lbl.dataset.mode === mode;
                lbl.classList.toggle('text-primary-700', active);
                lbl.classList.toggle('font-semibold', active);
                lbl.classList.toggle('text-gray-600', !active);
            });

            // Atualiza campo oculto video_source dentro do wrapper
            var sourceInput = wrapper.querySelector('[id^="vss_source_"]');
            if (sourceInput) sourceInput.value = mode;
        });
    });

    // Preview em tempo real de URL externa
    document.querySelectorAll('[id^="video_url_"]').forEach(function (input) {
        if (!input.name) return;
        input.addEventListener('input', function () {
            // Busca elementos via container (mais robusto que ID composto)
            var owner = this.closest('.vss-panel-url');
            if (!owner) return;
            var iframe  = owner.querySelector('iframe');
            var preview = owner.querySelector('.vss-preview-container');

            var embedUrl = videoUrlToEmbed(this.value.trim());
            if (iframe && preview) {
                if (embedUrl) {
                    iframe.src = embedUrl;
                    preview.classList.remove('hidden');
                } else {
                    iframe.src = '';
                    preview.classList.add('hidden');
                }
            }
        });

        // Dispara para campos já preenchidos (old() em caso de erro de validação)
        if (input.value) {
            input.dispatchEvent(new Event('input'));
        }
    });
});
</script>
@endpush
@endonce
