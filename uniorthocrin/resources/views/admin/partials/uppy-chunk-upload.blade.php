{{--
  Uppy chunk upload: arquivos grandes (até 500MB) em create e edit.
  Variáveis: containerId, module, title (opcional), accept, note (opcional),
  uploadUrl (edit), uploadPayloadKey (edit), inputName (create), pathsContainerId (create).
--}}
@php
  $isEdit = !empty($uploadUrl);
  $title = $title ?? 'Upload em partes (arquivos grandes)';
  $note = $note ?? 'Arquivos grandes são enviados em partes de 5 MB. Até 500 MB.';
@endphp
<div class="uppy-chunk-upload mb-6">
  @if(!empty($title))
    <h4 class="text-modern-body font-medium mb-2">{{ $title }}</h4>
  @endif
  <div id="{{ $containerId }}" class="uppy-dashboard-container border border-gray-300 rounded-lg overflow-hidden" style="min-height: 200px;"></div>
  @if(!$isEdit && !empty($pathsContainerId))
    <div id="{{ $pathsContainerId }}" class="hidden resolved-paths-container"></div>
  @endif
  <p class="text-xs text-gray-500 mt-1">{{ $note }}</p>
</div>

@push('scripts')
<script src="https://releases.transloadit.com/uppy/v3.25.0/uppy.min.js" crossorigin></script>
<link href="https://releases.transloadit.com/uppy/v3.25.0/uppy.min.css" rel="stylesheet">
<script>
(function() {
  const CONFIG = {
    containerId: @json($containerId),
    module: @json($module),
    accept: @json($accept ?? '.mp4,.avi,.mov'),
    chunkSize: 5 * 1024 * 1024,
    chunkUrl: @json(route('admin.upload.chunk')),
    assembleUrl: @json(route('admin.upload.assemble')),
    isEdit: @json($isEdit),
    uploadUrl: @json($uploadUrl ?? ''),
    uploadPayloadKey: @json($uploadPayloadKey ?? 'resolved_paths'),
    inputName: @json($inputName ?? ''),
    pathsContainerId: @json($pathsContainerId ?? ''),
    csrfToken: document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '',
  };

  function uuid() {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/x/g, function() {
      return (Math.random() * 16 | 0).toString(16);
    });
  }

  async function uploadFileInChunks(file, onProgress) {
    const totalChunks = Math.ceil(file.size / CONFIG.chunkSize);
    const fileUuid = uuid();

    for (let i = 1; i <= totalChunks; i++) {
      const start = (i - 1) * CONFIG.chunkSize;
      const end = Math.min(i * CONFIG.chunkSize, file.size);
      const chunk = file.slice(start, end);

      const formData = new FormData();
      formData.append('uuid', fileUuid);
      formData.append('chunkIndex', i);
      formData.append('totalChunks', totalChunks);
      formData.append('file', chunk);
      formData.append('_token', CONFIG.csrfToken);

      const res = await fetch(CONFIG.chunkUrl, {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
      });

      const data = await res.json();
      if (!data.received) {
        throw new Error(data.message || 'Falha no chunk ' + i);
      }
      if (onProgress) {
        onProgress(Math.round((i / totalChunks) * 100));
      }
    }

    const assembleRes = await fetch(CONFIG.assembleUrl, {
      method: 'POST',
      body: JSON.stringify({
        uuid: fileUuid,
        originalName: file.name,
        mimeType: file.type,
        totalChunks: totalChunks,
        module: CONFIG.module,
        _token: CONFIG.csrfToken,
      }),
      headers: {
        'Content-Type': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': CONFIG.csrfToken,
      },
    });

    const assembleData = await assembleRes.json();
    if (!assembleData.success) {
      throw new Error(assembleData.message || 'Falha ao montar arquivo');
    }
    return assembleData.path;
  }

  function initUppy() {
    const container = document.getElementById(CONFIG.containerId);
    if (!container) return;

    const uppy = new Uppy.Core({
      id: CONFIG.containerId + '-uppy',
      autoProceed: false,
      allowMultiple: true,
      restrictions: {
        maxFileSize: 500 * 1024 * 1024,
        allowedFileTypes: CONFIG.accept.split(',').map(function(s) { return s.trim().replace(/^\./, ''); }),
      },
    });

    uppy.use(Uppy.Dashboard, {
      target: '#' + CONFIG.containerId,
      inline: true,
      height: 220,
      note: 'Até 500 MB. Envio em partes de 5 MB.',
      proudlyDisplayPoweredByUppy: false,
    });

    uppy.on('upload', async function() {
      const files = uppy.getFiles();
      if (files.length === 0) return;

      const errors = [];

      for (let idx = 0; idx < files.length; idx++) {
        const f = files[idx];
        if (f.data && typeof f.data.size !== 'undefined') {
          try {
            const path = await uploadFileInChunks(f.data, function(pct) {
              uppy.setFileState(f.id, { progress: { percentage: pct } });
            });

            if (CONFIG.isEdit && CONFIG.uploadUrl) {
              const formData = new FormData();
              formData.append(CONFIG.uploadPayloadKey + '[]', path);
              formData.append('_token', CONFIG.csrfToken);

              const r = await fetch(CONFIG.uploadUrl, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
              });
              const json = await r.json();
              if (!json.success) {
                errors.push(f.name + ': ' + (json.message || 'Erro ao anexar'));
              }
            } else if (CONFIG.pathsContainerId && CONFIG.inputName) {
              const wrap = document.getElementById(CONFIG.pathsContainerId);
              if (wrap) {
                wrap.classList.remove('hidden');
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = CONFIG.inputName;
                input.value = path;
                wrap.appendChild(input);
              }
            }

            uppy.removeFile(f.id);
          } catch (err) {
            errors.push(f.name + ': ' + err.message);
          }
        }
      }

      if (errors.length) {
        alert('Erros:\n' + errors.join('\n'));
      }
      if (CONFIG.isEdit && files.length > 0) {
        window.location.reload();
      }
    });

    window['uppy_' + CONFIG.containerId] = uppy;
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initUppy);
  } else {
    initUppy();
  }
})();
</script>
@endpush
