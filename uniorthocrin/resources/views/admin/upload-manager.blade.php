@extends('admin.layouts.app')

@section('title', 'Gerenciador de Uploads - Admin')

@section('content')
@if(request()->has('popup'))
<style>
    /* Hide layout sidebar, headers, and footer-like structures */
    aside, header, footer { display: none !important; }
    /* Override padding and margins on the main content area */
    main { padding: 24px !important; margin: 0 !important; width: 100% !important; max-width: 100% !important; height: auto !important; overflow: auto !important; }
    body { background-color: #f8fafc !important; }
    .flex-1 { margin-left: 0 !important; padding: 0 !important; width: 100% !important; }
    /* Hide scrollbar restrictions */
    .overflow-hidden { overflow: auto !important; }
    .h-screen { height: auto !important; min-height: 100vh !important; }
</style>
@endif
<div class="space-y-6">
    <!-- Modern Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Gerenciador de Uploads</h1>
            <p class="text-sm text-gray-500">Acompanhe a transmissão e integridade dos arquivos enviados por blocos (Chunks)</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg font-semibold text-sm transition-all shadow-sm flex items-center gap-2" id="btn-clear-all" onclick="clearAllUploads()">
                <i class="fa-solid fa-trash-can"></i> Limpar Tudo
            </button>
            <button class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-semibold text-sm transition-all shadow-sm flex items-center gap-2" id="btn-sync-all" onclick="syncAllUploads()">
                <i class="fa-solid fa-rotate-loop"></i> Sincronizar Todos Pendentes
            </button>
            <button class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold text-sm transition-all flex items-center gap-2" onclick="window.location.reload()">
                <i class="fa-solid fa-rotate"></i> Atualizar Status
            </button>
        </div>
    </div>

    <!-- SEÇÃO: Fila de Transmissão Ativa (Visível se houver uploads em processamento local) -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6" id="active-uploads-panel" style="display: none;">
        <div class="flex items-center justify-between border-b border-gray-100 pb-4 mb-4">
            <div class="flex items-center gap-2 font-semibold text-gray-800">
                <span class="flex h-3 w-3 relative">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-primary-500"></span>
                </span>
                Fila de Transmissão Ativa
            </div>
            <span class="bg-primary-100 text-primary-800 px-3 py-1 rounded-full text-xs font-semibold" id="active-count-badge">0 ativo(s)</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="active-uploads-container">
            <!-- Injetados dinamicamente via Javascript -->
        </div>
    </div>

    <!-- SEÇÃO: Histórico e Controle no Banco de Dados -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center gap-2">
            <i class="fa-solid fa-database text-primary-500"></i>
            <span class="font-semibold text-gray-800">Arquivos Registrados no Banco</span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nome do Arquivo</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tamanho</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Progresso</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Enviado em</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tentativas</th>
                        <th scope="col" class="px-6 py-3 class='text-right' px-6 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wider text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($uploads as $upload)
                    <tr class="hover:bg-gray-50 transition-colors" id="row-{{ $upload->uuid }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 truncate max-w-xs" title="{{ $upload->original_name ?? $upload->filename }}">
                            {{ $upload->original_name ?? $upload->filename }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ number_format($upload->file_size / 1024 / 1024, 2) }} MB
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex items-center gap-2">
                                <div class="w-24 bg-gray-100 rounded-full h-2 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-300" style="width: {{ $upload->upload_progress }}%; background-color: {{ $upload->upload_status === 'completed' ? '#22c55e' : ($upload->upload_status === 'error' ? '#ef4444' : '#910039') }};"></div>
                                </div>
                                <span class="text-xs font-bold text-gray-700">{{ $upload->upload_progress }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($upload->upload_status === 'uploading')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-primary-50 text-primary-700">
                                    <i class="fa-solid fa-spinner fa-spin"></i> Enviando
                                </span>
                            @elseif($upload->upload_status === 'merging')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700">
                                    <i class="fa-solid fa-gears fa-spin"></i> Mesclando
                                </span>
                            @elseif($upload->upload_status === 'completed')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                                    <i class="fa-solid fa-circle-check"></i> Concluído
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700">
                                    <i class="fa-solid fa-circle-xmark"></i> Falhou
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                            {{ $upload->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700">
                            {{ $upload->attempts }} / 3
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @if($upload->upload_status !== 'completed')
                            <button class="px-3 py-1.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-xs font-bold transition-all inline-flex items-center gap-1" id="btn-sync-{{ $upload->uuid }}" onclick="syncSingleUpload('{{ $upload->uuid }}')">
                                <i class="fa-solid fa-arrows-spin"></i> Sincronizar
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center">
                            <div class="text-gray-400 flex flex-col items-center gap-2">
                                <i class="fa-solid fa-folder-open text-4xl text-gray-300"></i>
                                <p class="text-sm">Nenhum upload segmentado registrado no banco.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($uploads->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $uploads->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    // Garante que a janela do gerenciador tenha o nome correto para reutilização de abas
    window.name = 'uppy_upload_manager';

    // Armazena o token CSRF para validação das requisições via fetch
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const CHUNK_SIZE = 5 * 1024 * 1024;
    const activeContainer = document.getElementById('active-uploads-container');
    const activePanel = document.getElementById('active-uploads-panel');
    const activeCountBadge = document.getElementById('active-count-badge');
    
    let activeUploadsCount = 0;
    const uploadQueue = {};

    // Configura o BroadcastChannel para avisar as abas principais sobre o progresso
    const bc = new BroadcastChannel('uppy-upload-channel');

    function broadcastStatus() {
        const list = Object.keys(uploadQueue).map(uuid => uploadQueue[uuid]);
        const activeList = list.filter(item => item.status === 'uploading' || item.status === 'merging');
        const totalProgress = list.reduce((acc, item) => acc + (item.progress || 0), 0);
        const overallProgress = list.length > 0 ? Math.round(totalProgress / list.length) : 0;

        bc.postMessage({
            type: 'STATUS_UPDATE',
            activeCount: activeList.length,
            overallProgress: overallProgress
        });
    }

    bc.onmessage = (event) => {
        if (event.data) {
            if (event.data.type === 'REQUEST_STATUS') {
                broadcastStatus();
            } else if (event.data.type === 'PING_MANAGER') {
                bc.postMessage({ type: 'PONG_MANAGER' });
            } else if (event.data.type === 'START_UPLOADS') {
                const items = event.data.payload || [];
                for (const item of items) {
                    if (activeUploads[item.uuid]) continue; // já na fila
                    activeUploads[item.uuid] = true;
                    startUploadFromFile(item);
                }
            }
        }
    };

    const activeUploads = {};

    // ─── Handshake com a aba que originou o upload ────────────────────────────
    // Sinaliza ao opener que esta aba está pronta para receber os File objects.
    if (window.opener && !window.opener.closed) {
        try {
            window.opener.postMessage({ type: 'UPLOAD_MANAGER_READY' }, window.location.origin);
        } catch (_) { /* opener de origem diferente — ignorado */ }
    }

    // Recebe os File objects diretamente via postMessage (sem IndexedDB de blobs)
    window.addEventListener('message', function(event) {
        if (event.origin !== window.location.origin) return;
        if (!event.data || event.data.type !== 'START_UPLOADS') return;

        const items = event.data.payload || [];
        for (const item of items) {
            if (activeUploads[item.uuid]) continue; // já na fila
            activeUploads[item.uuid] = true;
            startUploadFromFile(item);
        }
    });

    // ─── Fallback: polling do IndexedDB para uploads iniciados em sessões anteriores ──
    // Só processa itens que não foram recebidos via postMessage nesta sessão.
    setInterval(async () => {
        try {
            if (window.uppyIndexedDB) {
                const pending = await window.uppyIndexedDB.getAllMetadata();
                for (const item of pending) {
                    // Ignora se já está sendo processado via postMessage ou se não há blobs
                    if (uploadQueue[item.uuid] || activeUploads[item.uuid]) continue;

                    // Verifica se o chunk 0 existe (blobs no IndexedDB — fluxo legado)
                    const chunk0 = await window.uppyIndexedDB.getChunk(item.uuid, 0).catch(() => null);
                    if (!chunk0 || !chunk0.blob) {
                        // Sem blobs — upload iniciado via postMessage mas aba foi reaberta.
                        // Limpa o metadado órfão para não poluir a fila.
                        window.uppyIndexedDB.deleteMetadata(item.uuid).catch(() => {});
                        continue;
                    }

                    activeUploads[item.uuid] = true;
                    startNewUpload(item.uuid, item); // fluxo legado via IndexedDB
                }
            }
        } catch (e) {
            console.error('Erro ao ler fila do IndexedDB:', e);
        }
    }, 2000);

    // ─── Upload direto a partir de File object (via postMessage) ─────────────
    // Esta é a função principal para o fluxo de segundo plano.
    // Lê o arquivo em chunks diretamente do File object — sem IndexedDB.
    function startUploadFromFile(item) {
        const { uuid, file, inputName, modelInfo, totalChunks } = item;

        activePanel.style.display = 'block';
        activeUploadsCount++;
        activeCountBadge.textContent = `${activeUploadsCount} ativo(s)`;

        uploadQueue[uuid] = { name: file.name, size: file.size, progress: 0, status: 'uploading' };
        broadcastStatus();

        const card = document.createElement('div');
        card.className = 'bg-gray-50 border border-gray-100 rounded-xl p-4 flex flex-col gap-3 relative';
        card.id = `card-${uuid}`;
        card.innerHTML = `
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary-50 text-primary-600 rounded-lg flex items-center justify-center flex-shrink-0 text-lg"><i class="fa-solid fa-file-arrow-up"></i></div>
                <div class="flex-1 min-w-0">
                    <div class="font-semibold text-sm text-gray-900 truncate" title="${file.name}">${file.name}</div>
                    <div class="text-xs text-gray-500 mt-0.5" id="meta-${uuid}">Preparando... | ${formatBytes(file.size)}</div>
                </div>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-primary-100 text-primary-800" id="badge-${uuid}">Fila</span>
            </div>
            <div>
                <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-primary-500 h-full transition-all duration-300" id="fill-${uuid}" style="width: 0%;"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mt-2">
                    <span id="speed-${uuid}">0 KB/s</span>
                    <span class="font-bold text-gray-700" id="pct-${uuid}">0%</span>
                </div>
            </div>
        `;
        activeContainer.appendChild(card);

        uploadFileDirectly(uuid, file, totalChunks, inputName, modelInfo, card);
    }

    async function uploadFileDirectly(uuid, file, totalChunks, inputName, modelInfo, card) {
        const badge     = document.getElementById(`badge-${uuid}`);
        const fill      = document.getElementById(`fill-${uuid}`);
        const pctText   = document.getElementById(`pct-${uuid}`);
        const speedText = document.getElementById(`speed-${uuid}`);
        const metaText  = document.getElementById(`meta-${uuid}`);

        badge.textContent = 'Enviando';
        const startTime = Date.now();

        try {
            for (let i = 0; i < totalChunks; i++) {
                // Slice do File diretamente — sem IndexedDB, sem limite de espaço
                const start     = i * CHUNK_SIZE;
                const end       = Math.min(start + CHUNK_SIZE, file.size);
                const chunkBlob = file.slice(start, end);

                const formData = new FormData();
                formData.append('file', chunkBlob, file.name);

                const response = await fetch('/admin/upload/chunk', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN':       csrfToken,
                        'X-Unique-Upload-Id': uuid,
                        'X-Chunk-Index':      String(i),
                        'X-Total-Chunks':     String(totalChunks),
                        'X-File-Name':        encodeURIComponent(file.name),
                        'X-Total-Size':       String(file.size),
                        'X-File-Type':        file.type || 'application/octet-stream',
                        'X-Model-Type':       modelInfo?.modelType  || '',
                        'X-Model-Id':         modelInfo?.modelId    ? String(modelInfo.modelId) : '',
                        'X-Property':         modelInfo?.property   || inputName || ''
                    },
                    body: formData
                });

                if (!response.ok) {
                    let msg = `HTTP ${response.status}`;
                    try { const b = await response.json(); if (b.message) msg = b.message; } catch (_) {}
                    throw new Error(msg);
                }

                const result = await response.json();
                if (!result.success) throw new Error(result.message || 'Erro no servidor');

                // Atualiza progresso
                const uploadedBytes = Math.min((i + 1) * CHUNK_SIZE, file.size);
                const pct           = Math.round((uploadedBytes / file.size) * 100);
                fill.style.width    = `${pct}%`;
                pctText.textContent = `${pct}%`;

                const elapsed       = (Date.now() - startTime) / 1000;
                const speed         = uploadedBytes / elapsed;
                speedText.textContent = `${formatBytes(speed)}/s`;
                metaText.textContent  = `Parte ${i + 1} de ${totalChunks} | ${formatBytes(file.size)}`;

                uploadQueue[uuid].progress = pct;
                broadcastStatus();

                if (result.status === 'completed') {
                    // Limpa metadados do IndexedDB
                    if (window.uppyIndexedDB) {
                        window.uppyIndexedDB.deleteMetadata(uuid).catch(() => {});
                    }

                    badge.innerHTML = '<i class="fa-solid fa-gears fa-spin"></i> Mesclando';
                    badge.className = 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800';
                    uploadQueue[uuid].status   = 'merging';
                    uploadQueue[uuid].progress = 98;
                    broadcastStatus();

                    setTimeout(() => {
                        badge.innerHTML = '<i class="fa-solid fa-circle-check"></i> Concluído';
                        badge.className = 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800';
                        speedText.textContent  = '';
                        metaText.textContent   = `Finalizado com sucesso | ${formatBytes(file.size)}`;
                        uploadQueue[uuid].status   = 'completed';
                        uploadQueue[uuid].progress = 100;
                        broadcastStatus();

                        setTimeout(() => {
                            card.style.opacity = '0';
                            setTimeout(() => {
                                card.remove();
                                if (activeContainer.children.length === 0) activePanel.style.display = 'none';
                            }, 300);
                        }, 6000);
                    }, 1000);

                    break; // upload concluído — sai do loop
                }
            }
        } catch (err) {
            if (window.uppyIndexedDB) {
                window.uppyIndexedDB.deleteMetadata(uuid).catch(() => {});
            }
            badge.innerHTML = '<i class="fa-solid fa-circle-xmark"></i> Falhou';
            badge.className = 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-800';
            card.classList.add('failed');
            metaText.textContent  = `Falhou: ${err.message}`;
            speedText.textContent = '';
            uploadQueue[uuid].status = 'failed';
            broadcastStatus();
        } finally {
            activeUploadsCount--;
            activeCountBadge.textContent = `${activeUploadsCount} ativo(s)`;
        }
    }

    // ─── Upload legado via IndexedDB (blobs salvos em sessões anteriores) ─────
    function startNewUpload(uuid, item) {
        // Exibe o contêiner de uploads ativos
        activePanel.style.display = 'block';

        activeUploadsCount++;
        activeCountBadge.textContent = `${activeUploadsCount} ativo(s)`;

        uploadQueue[uuid] = {
            name: item.name,
            size: item.size,
            progress: 0,
            status: 'uploading'
        };
        broadcastStatus();

        const card = document.createElement('div');
        card.className = 'bg-gray-50 border border-gray-100 rounded-xl p-4 flex flex-col gap-3 relative';
        card.id = `card-${uuid}`;
        card.innerHTML = `
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary-50 text-primary-600 rounded-lg flex items-center justify-center flex-shrink-0 text-lg"><i class="fa-solid fa-file-arrow-up"></i></div>
                <div class="flex-1 min-w-0">
                    <div class="font-semibold text-sm text-gray-900 truncate" title="${item.name}">${item.name}</div>
                    <div class="text-xs text-gray-500 mt-0.5" id="meta-${uuid}">Preparando... | ${formatBytes(item.size)}</div>
                </div>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-primary-100 text-primary-800" id="badge-${uuid}">Fila</span>
            </div>
            <div>
                <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-primary-500 h-full transition-all duration-300" id="fill-${uuid}" style="width: 0%;"></div>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mt-2">
                    <span id="speed-${uuid}">0 KB/s</span>
                    <span class="font-bold text-gray-700" id="pct-${uuid}">0%</span>
                </div>
            </div>
        `;
        activeContainer.appendChild(card);

        // Inicia a transmissão em pedaços do arquivo usando os chunks salvos no IndexedDB
        uploadInChunks(uuid, item.totalChunks, item.size, item.name, item.type, item.inputName, card, item.modelType, item.modelId, item.property);
    }

    async function uploadInChunks(uuid, totalChunks, fileSize, fileName, fileType, inputName, card, modelType, modelId, property) {
        const badge = document.getElementById(`badge-${uuid}`);
        const fill = document.getElementById(`fill-${uuid}`);
        const pctText = document.getElementById(`pct-${uuid}`);
        const speedText = document.getElementById(`speed-${uuid}`);
        const metaText = document.getElementById(`meta-${uuid}`);

        badge.textContent = 'Enviando';

        let startTime = Date.now();

        try {
            for (let i = 0; i < totalChunks; i++) {
                // Recupera o chunk do IndexedDB
                let chunkRecord = null;
                if (window.uppyIndexedDB) {
                    chunkRecord = await window.uppyIndexedDB.getChunk(uuid, i);
                }

                if (!chunkRecord || !chunkRecord.blob) {
                    throw new Error(`Chunk ${i} não encontrado no banco de dados local (IndexedDB).`);
                }

                const chunkBlob = chunkRecord.blob;

                const formData = new FormData();
                formData.append('file', chunkBlob, fileName);

                const response = await fetch('/admin/upload/chunk', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Unique-Upload-Id': uuid,
                        'X-Chunk-Index': String(i),
                        'X-Total-Chunks': String(totalChunks),
                        'X-File-Name': encodeURIComponent(fileName),
                        'X-Total-Size': String(fileSize),
                        'X-File-Type': fileType || 'application/octet-stream',
                        'X-Model-Type': modelType || '',
                        'X-Model-Id': modelId ? String(modelId) : '',
                        'X-Property': property || inputName || ''
                    },
                    body: formData
                });

                if (!response.ok) {
                    let msg = `HTTP ${response.status}`;
                    try { const b = await response.json(); if (b.message) msg = b.message; } catch (_) {}
                    throw new Error(msg);
                }

                const result = await response.json();
                if (!result.success) throw new Error(result.message || 'Erro no servidor');

                // Deleta o chunk do IndexedDB imediatamente após sucesso para liberar memória/disco
                if (window.uppyIndexedDB) {
                    await window.uppyIndexedDB.deleteChunk(uuid, i);
                }

                // Injeta o input hidden de vinculação na janela pai após processar o primeiro bloco
                if (i === 0) {
                    if (window.opener && !window.opener.closed) {
                        try {
                            const parentForm = window.opener.document.querySelector(`input[name="${inputName}"]`)?.closest('form') || window.opener.document.querySelector('form');
                            if (parentForm) {
                                const exists = parentForm.querySelector(`input[data-file-uuid="${uuid}"]`);
                                if (!exists) {
                                    const hiddenInput = window.opener.document.createElement('input');
                                    hiddenInput.type = 'hidden';
                                    hiddenInput.name = `uppy_uploads[${inputName}][]`;
                                    hiddenInput.value = uuid;
                                    hiddenInput.dataset.fileUuid = uuid;
                                    parentForm.appendChild(hiddenInput);
                                }
                            }
                        } catch (_) {
                            // Ignora se o parent não estiver na mesma origem ou inacessível
                        }
                    }
                }

                const uploadedBytes = Math.min((i + 1) * CHUNK_SIZE, fileSize);
                const pct = Math.round((uploadedBytes / fileSize) * 100);
                fill.style.width = `${pct}%`;
                pctText.textContent = `${pct}%`;

                const elapsed = (Date.now() - startTime) / 1000;
                const speed = uploadedBytes / elapsed;
                speedText.textContent = `${formatBytes(speed)}/s`;
                metaText.textContent = `Parte ${i + 1} de ${totalChunks} | ${formatBytes(fileSize)}`;

                uploadQueue[uuid].progress = pct;
                broadcastStatus();

                if (result.status === 'completed') {
                    // Limpa do IndexedDB
                    if (window.uppyIndexedDB) {
                        await window.uppyIndexedDB.deleteMetadata(uuid);
                    }
                    badge.innerHTML = '<i class="fa-solid fa-gears fa-spin"></i> Mesclando';
                    badge.className = 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-800';
                    card.classList.add('completed');
                    uploadQueue[uuid].status = 'merging';
                    uploadQueue[uuid].progress = 98;
                    broadcastStatus();
                    
                    setTimeout(() => {
                        badge.innerHTML = '<i class="fa-solid fa-circle-check"></i> Concluído';
                        badge.className = 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800';
                        speedText.textContent = '';
                        metaText.textContent = `Finalizado com sucesso | ${formatBytes(fileSize)}`;
                        uploadQueue[uuid].status = 'completed';
                        uploadQueue[uuid].progress = 100;
                        broadcastStatus();
                        
                        // Remove o card da tela após 6 segundos para limpeza visual
                        setTimeout(() => {
                            card.style.opacity = '0';
                            setTimeout(() => {
                                card.remove();
                                if (activeContainer.children.length === 0) {
                                    activePanel.style.display = 'none';
                                }
                            }, 300);
                        }, 6000);
                    }, 1000);
                }
            }
        } catch (err) {
            // Limpa do IndexedDB para não travar na fila infinitamente
            if (window.uppyIndexedDB) {
                window.uppyIndexedDB.deleteMetadata(uuid).catch(e => console.error(e));
                window.uppyIndexedDB.deleteAllChunks(uuid, totalChunks).catch(e => console.error(e));
            }
            badge.innerHTML = '<i class="fa-solid fa-circle-xmark"></i> Falhou';
            badge.className = 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-800';
            card.classList.add('failed');
            metaText.textContent = `Falhou: ${err.message}`;
            speedText.textContent = '';
            uploadQueue[uuid].status = 'failed';
            broadcastStatus();
        } finally {
            activeUploadsCount--;
            activeCountBadge.textContent = `${activeUploadsCount} ativo(s)`;
        }
    }

    // Aciona a sincronização manual de um arquivo específico da tabela
    async function syncSingleUpload(uuid) {
        const btn = document.getElementById(`btn-sync-${uuid}`);
        const badge = document.getElementById(`db-badge-${uuid}`);
        if (btn) btn.disabled = true;

        if (badge) {
            badge.innerHTML = '<i class="fa-solid fa-arrows-spin fa-spin"></i> Sincronizando...';
            badge.className = 'status-badge merging';
        }

        try {
            const response = await fetch(`/admin/upload/sync/${uuid}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();
            alert(data.message);
            window.location.reload();
        } catch (err) {
            alert(`Erro ao sincronizar upload: ${err.message}`);
            window.location.reload();
        }
    }

    // Sincroniza em lote todos os uploads pendentes
    async function syncAllUploads() {
        const btn = document.getElementById('btn-sync-all');
        if (btn) btn.disabled = true;

        try {
            const response = await fetch('/admin/upload/sync-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();
            alert(data.message);
            window.location.reload();
        } catch (err) {
            alert(`Erro ao rodar sincronização em lote: ${err.message}`);
            window.location.reload();
        }
    }

    // Limpa todos os registros de uploads, sincronizações e arquivos temporários
    async function clearAllUploads() {
        if (!confirm('Deseja realmente limpar todos os registros do banco de dados, limpar as pastas temporárias e os arquivos locais? Esta ação não pode ser desfeita.')) {
            return;
        }

        const btn = document.getElementById('btn-clear-all');
        if (btn) btn.disabled = true;

        try {
            const response = await fetch('/admin/upload/clear-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json'
                }
            });

            const data = await response.json();
            alert(data.message);
            window.location.reload();
        } catch (err) {
            alert(`Erro ao limpar uploads: ${err.message}`);
            window.location.reload();
            if (btn) btn.disabled = false;
        }
    }

    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    window.addEventListener('beforeunload', (e) => {
        if (activeUploadsCount > 0) {
            e.preventDefault();
            e.returnValue = 'Uploads em andamento. Se fechar esta aba, os envios ativos serão interrompidos.';
            return e.returnValue;
        }
    });
</script>
@endsection
