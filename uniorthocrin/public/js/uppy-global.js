/**
 * Chunk Uploader Global
 * Intercepta automaticamente campos de upload do painel admin.
 * Se o arquivo for maior que 10MB, realiza o upload em blocos (chunked)
 * via fetch() com os headers que o ChunkUploadController espera,
 * e injeta inputs hidden no formulário com os UUIDs gerados.
 */
const uppyIndexedDB = {
    dbName: 'UppyChunkUploadDB',
    pendingStore: 'pendingUploads',
    chunksStore: 'uploadChunks',
    dbVersion: 2,

    open() {
        return new Promise((resolve, reject) => {
            const request = indexedDB.open(this.dbName, this.dbVersion);
            request.onupgradeneeded = (e) => {
                const db = e.target.result;
                if (!db.objectStoreNames.contains(this.pendingStore)) {
                    db.createObjectStore(this.pendingStore, { keyPath: 'uuid' });
                }
                if (!db.objectStoreNames.contains(this.chunksStore)) {
                    db.createObjectStore(this.chunksStore, { keyPath: 'id' });
                }
            };
            request.onsuccess = (e) => resolve(e.target.result);
            request.onerror = (e) => reject(e.target.error);
        });
    },

    async saveMetadata(uuid, name, size, type, inputName, totalChunks, modelInfo) {
        const db = await this.open();
        return new Promise((resolve, reject) => {
            const transaction = db.transaction([this.pendingStore], 'readwrite');
            const store = transaction.objectStore(this.pendingStore);
            const data = {
                uuid: uuid,
                name: name,
                size: size,
                type: type,
                inputName: inputName,
                totalChunks: totalChunks,
                modelType: modelInfo ? modelInfo.modelType : null,
                modelId: modelInfo ? modelInfo.modelId : null,
                property: modelInfo ? modelInfo.property : null,
                createdAt: Date.now()
            };
            const request = store.put(data);
            request.onsuccess = () => resolve(true);
            request.onerror = (e) => reject(e.target.error);
        });
    },

    async saveChunk(uuid, chunkIndex, blob) {
        const db = await this.open();
        return new Promise((resolve, reject) => {
            const transaction = db.transaction([this.chunksStore], 'readwrite');
            const store = transaction.objectStore(this.chunksStore);
            const data = {
                id: `${uuid}_${chunkIndex}`,
                uuid: uuid,
                chunkIndex: chunkIndex,
                blob: blob
            };
            const request = store.put(data);
            request.onsuccess = () => resolve(true);
            request.onerror = (e) => reject(e.target.error);
        });
    },

    async getMetadata(uuid) {
        const db = await this.open();
        return new Promise((resolve, reject) => {
            const transaction = db.transaction([this.pendingStore], 'readonly');
            const store = transaction.objectStore(this.pendingStore);
            const request = store.get(uuid);
            request.onsuccess = (e) => resolve(e.target.result);
            request.onerror = (e) => reject(e.target.error);
        });
    },

    async getAllMetadata() {
        const db = await this.open();
        return new Promise((resolve, reject) => {
            const transaction = db.transaction([this.pendingStore], 'readonly');
            const store = transaction.objectStore(this.pendingStore);
            const request = store.getAll();
            request.onsuccess = (e) => resolve(e.target.result);
            request.onerror = (e) => reject(e.target.error);
        });
    },

    async getChunk(uuid, chunkIndex) {
        const db = await this.open();
        return new Promise((resolve, reject) => {
            const transaction = db.transaction([this.chunksStore], 'readonly');
            const store = transaction.objectStore(this.chunksStore);
            const request = store.get(`${uuid}_${chunkIndex}`);
            request.onsuccess = (e) => resolve(e.target.result);
            request.onerror = (e) => reject(e.target.error);
        });
    },

    async deleteMetadata(uuid) {
        const db = await this.open();
        return new Promise((resolve, reject) => {
            const transaction = db.transaction([this.pendingStore], 'readwrite');
            const store = transaction.objectStore(this.pendingStore);
            const request = store.delete(uuid);
            request.onsuccess = () => resolve(true);
            request.onerror = (e) => reject(e.target.error);
        });
    },

    async deleteChunk(uuid, chunkIndex) {
        const db = await this.open();
        return new Promise((resolve, reject) => {
            const transaction = db.transaction([this.chunksStore], 'readwrite');
            const store = transaction.objectStore(this.chunksStore);
            const request = store.delete(`${uuid}_${chunkIndex}`);
            request.onsuccess = () => resolve(true);
            request.onerror = (e) => reject(e.target.error);
        });
    },

    async deleteAllChunks(uuid, totalChunks) {
        for (let i = 0; i < totalChunks; i++) {
            try {
                await this.deleteChunk(uuid, i);
            } catch (_) {}
        }
    },

    async clearAll() {
        const db = await this.open();
        return new Promise((resolve, reject) => {
            const transaction = db.transaction([this.pendingStore, this.chunksStore], 'readwrite');
            transaction.objectStore(this.pendingStore).clear();
            transaction.objectStore(this.chunksStore).clear();
            transaction.oncomplete = () => resolve(true);
            transaction.onerror = (e) => reject(e.target.error);
        });
    }
};
window.uppyIndexedDB = uppyIndexedDB;

function getModelInfoFromForm(form, inputName) {
    if (!form) return null;
    if (form.dataset.modelType && form.dataset.modelId) {
        return {
            modelType: form.dataset.modelType,
            modelId: parseInt(form.dataset.modelId, 10),
            property: inputName
        };
    }

    const action = form.getAttribute('action') || '';
    const url = window.location.pathname;

    const mappings = {
        'produtos': 'App\\Models\\Product',
        'campanhas': 'App\\Models\\Campaign',
        'radar': 'App\\Models\\News',
        'biblioteca': 'App\\Models\\Library',
        'na-midia': 'App\\Models\\Media',
        'treinamentos': 'App\\Models\\Training'
    };

    function parseUrlString(str) {
        if (!str) return null;
        const segments = str.split('/');
        for (let i = 0; i < segments.length; i++) {
            const segment = segments[i];
            if (mappings[segment]) {
                if (i + 1 < segments.length) {
                    const idVal = parseInt(segments[i + 1], 10);
                    if (!isNaN(idVal)) {
                        return {
                            modelType: mappings[segment],
                            modelId: idVal,
                            property: inputName
                        };
                    }
                }
            }
        }
        return null;
    }

    return parseUrlString(url) || parseUrlString(action);
}
window.getModelInfoFromForm = getModelInfoFromForm;

document.addEventListener('DOMContentLoaded', function () {
    // Limiar de tamanho para acionar o upload segmentado: 10MB
    const SIZE_THRESHOLD = 10 * 1024 * 1024;
    // Tamanho ideal de cada fragmento (chunk): 5MB
    const CHUNK_SIZE = 5 * 1024 * 1024;

    // Gera identificador único universal para o controle de blocos do arquivo
    function generateUUID() {
        if (typeof crypto !== 'undefined' && typeof crypto.randomUUID === 'function') {
            return crypto.randomUUID();
        }
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
            const r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    // Formata bytes para facilitar a leitura do usuário final
    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    // Verifica de forma assíncrona se a aba do gerenciador de uploads está aberta
    function checkIfManagerOpen() {
        return new Promise((resolve) => {
            let responded = false;
            const tempBc = new BroadcastChannel('uppy-upload-channel');
            tempBc.onmessage = (event) => {
                if (event.data && event.data.type === 'PONG_MANAGER') {
                    responded = true;
                    tempBc.close();
                    resolve(true);
                }
            };
            tempBc.postMessage({ type: 'PING_MANAGER' });
            setTimeout(() => {
                if (!responded) {
                    tempBc.close();
                    resolve(false);
                }
            }, 300);
        });
    }

    // Exibe diálogo interativo para arquivos pesados
    function showHeavyFilesModal(heavyFiles, onSelectBg, onSelectFg, onCancel) {
        const existing = document.getElementById('uppy-modal-dialog');
        if (existing) existing.remove();

        const modalDiv = document.createElement('div');
        modalDiv.id = 'uppy-modal-dialog';
        modalDiv.style.cssText = 'position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(15,23,42,0.85);display:flex;justify-content:center;align-items:center;z-index:100000;font-family:\'Outfit\',system-ui,-apple-system,sans-serif;backdrop-filter:blur(8px);';
        
        const filesListHtml = heavyFiles.map(f => `
            <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid rgba(255,255,255,0.05);font-size:0.85rem;">
                <span style="font-weight:500;color:#f1f5f9;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;max-width:300px;">📄 ${f.name}</span>
                <span style="color:#94a3b8;margin-left:auto;flex-shrink:0;">${formatBytes(f.size)}</span>
            </div>
        `).join('');

        const totalSize = heavyFiles.reduce((acc, f) => acc + f.size, 0);

        modalDiv.innerHTML = `
            <div style="background:#1e293b;border:1px solid rgba(255,255,255,0.1);border-radius:24px;padding:32px;max-width:550px;width:90%;box-shadow:0 25px 50px -12px rgba(0,0,0,0.5);color:#f8fafc;display:flex;flex-direction:column;gap:20px;animation:modalScale 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);">
                <div style="display:flex;align-items:flex-start;gap:16px;">
                    <div style="background:rgba(145,0,57,0.1);border-radius:16px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;color:#910039;flex-shrink:0;">
                        <svg style="width:24px;height:24px;fill:currentColor;" viewBox="0 0 24 24"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM19 18H6c-2.21 0-4-1.79-4-4 0-2.05 1.53-3.76 3.56-3.97l1.07-.11.5-.95C8.08 7.14 9.94 6 12 6c2.62 0 4.88 1.86 5.39 4.43l.3 1.5 1.53.11c1.56.1 2.78 1.41 2.78 2.96 0 1.65-1.35 3-3 3z"/></svg>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <h3 style="margin:0;font-size:1.25rem;font-weight:600;color:#f8fafc;letter-spacing:-0.02em;">Arquivo Grande Detectado</h3>
                        <p style="margin:6px 0 0 0;font-size:0.85rem;color:#94a3b8;line-height:1.5;">Você selecionou arquivos grandes (Total: <strong>${formatBytes(totalSize)}</strong>). Deseja fazer o upload em segundo plano para poder navegar livremente e salvar o formulário imediatamente?</p>
                    </div>
                </div>
                
                <div style="background:rgba(15,23,42,0.4);border-radius:16px;padding:16px;border:1px solid rgba(255,255,255,0.04);max-height:150px;overflow-y:auto;display:flex;flex-direction:column;gap:8px;margin:4px 0;">
                    ${filesListHtml}
                </div>
                
                <div style="display:flex;flex-direction:column;gap:12px;margin-top:8px;">
                    <button id="uppy-modal-btn-bg" style="background:#910039;color:white;border:none;border-radius:14px;padding:14px 20px;font-size:0.95rem;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:10px;transition:all 0.2s ease;box-shadow:0 4px 12px rgba(145,0,57,0.35);">
                        🚀 Iniciar Upload em Segundo Plano
                    </button>
                    
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:0.8rem;padding:0 4px;">
                        <button id="uppy-modal-btn-fg" style="background:none;border:none;color:#94a3b8;cursor:pointer;text-decoration:underline;padding:4px;transition:color 0.2s;">
                            Enviar nesta aba (bloqueia a tela)
                        </button>
                        <button id="uppy-modal-btn-cancel" style="background:none;border:none;color:#f87171;cursor:pointer;padding:4px;transition:color 0.2s;font-weight:500;">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
            <style>
                @keyframes modalScale { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
                #uppy-modal-btn-bg:hover { background:#7c1d3a; transform: translateY(-1px); }
                #uppy-modal-btn-bg:active { transform: translateY(0); }
                #uppy-modal-btn-fg:hover { color:#cbd5e0; }
                #uppy-modal-btn-cancel:hover { color:#ef4444; }
            </style>
        `;
        document.body.appendChild(modalDiv);

        document.getElementById('uppy-modal-btn-bg').addEventListener('click', () => {
            modalDiv.remove();
            onSelectBg();
        });

        document.getElementById('uppy-modal-btn-fg').addEventListener('click', () => {
            modalDiv.remove();
            onSelectFg();
        });

        document.getElementById('uppy-modal-btn-cancel').addEventListener('click', () => {
            modalDiv.remove();
            onCancel();
        });
    }

    // Exibe diálogo informativo/hint na página de criação
    function showCreatePageHint(form, heavyFiles, input, lightFiles) {
        const existing = document.getElementById('uppy-create-hint-modal');
        if (existing) existing.remove();

        const modalDiv = document.createElement('div');
        modalDiv.id = 'uppy-create-hint-modal';
        modalDiv.style.cssText = 'position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(15,23,42,0.85);display:flex;justify-content:center;align-items:center;z-index:100000;font-family:\'Outfit\',system-ui,-apple-system,sans-serif;backdrop-filter:blur(8px);';
        
        const filesListHtml = heavyFiles.map(f => `
            <div style="display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-bottom:1px solid rgba(255,255,255,0.05);font-size:0.85rem;">
                <span style="font-weight:500;color:#f1f5f9;text-overflow:ellipsis;overflow:hidden;white-space:nowrap;max-width:300px;">📄 ${f.name}</span>
                <span style="color:#94a3b8;margin-left:auto;flex-shrink:0;">${formatBytes(f.size)}</span>
            </div>
        `).join('');

        const totalSize = heavyFiles.reduce((acc, f) => acc + f.size, 0);

        modalDiv.innerHTML = `
            <div style="background:#1e293b;border:1px solid rgba(255,255,255,0.1);border-radius:24px;padding:32px;max-width:550px;width:90%;box-shadow:0 25px 50px -12px rgba(0,0,0,0.5);color:#f8fafc;display:flex;flex-direction:column;gap:20px;animation:modalScale 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);">
                <div style="display:flex;align-items:flex-start;gap:16px;">
                    <div style="background:rgba(254,173,0,0.1);border-radius:16px;width:48px;height:48px;display:flex;align-items:center;justify-content:center;color:#FEAD00;flex-shrink:0;">
                        <svg style="width:24px;height:24px;fill:currentColor;" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <h3 style="margin:0;font-size:1.25rem;font-weight:600;color:#f8fafc;letter-spacing:-0.02em;">Salve o registro primeiro</h3>
                        <p style="margin:6px 0 0 0;font-size:0.85rem;color:#94a3b8;line-height:1.5;">Você selecionou arquivos grandes (Total: <strong>${formatBytes(totalSize)}</strong>). Para fazer o upload de arquivos pesados, você deve primeiro salvar a criação do registro para que eles possam ser associados corretamente.</p>
                    </div>
                </div>
                
                <div style="background:rgba(15,23,42,0.4);border-radius:16px;padding:16px;border:1px solid rgba(255,255,255,0.04);max-height:150px;overflow-y:auto;display:flex;flex-direction:column;gap:8px;margin:4px 0;">
                    ${filesListHtml}
                </div>
                
                <div style="display:flex;flex-direction:column;gap:12px;margin-top:8px;">
                    <button id="uppy-hint-btn-save" style="background:#910039;color:white;border:none;border-radius:14px;padding:14px 20px;font-size:0.95rem;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:10px;transition:all 0.2s ease;box-shadow:0 4px 12px rgba(145,0,57,0.35);">
                        💾 Salvar Agora
                    </button>
                    
                    <button id="uppy-hint-btn-cancel" style="background:none;border:none;color:#94a3b8;cursor:pointer;text-decoration:underline;padding:4px;transition:color 0.2s;text-align:center;font-size:0.85rem;">
                        Cancelar e manter arquivos leves
                    </button>
                </div>
            </div>
            <style>
                @keyframes modalScale { from { transform: scale(0.9); opacity: 0; } to { transform: scale(1); opacity: 1; } }
                #uppy-hint-btn-save:hover { background:#7c1d3a; transform: translateY(-1px); }
                #uppy-hint-btn-save:active { transform: translateY(0); }
                #uppy-hint-btn-cancel:hover { color:#cbd5e0; }
            </style>
        `;
        document.body.appendChild(modalDiv);

        document.getElementById('uppy-hint-btn-save').addEventListener('click', () => {
            modalDiv.remove();
            const dt = new DataTransfer();
            lightFiles.forEach(f => dt.items.add(f));
            input.files = dt.files;
            form.submit();
        });

        document.getElementById('uppy-hint-btn-cancel').addEventListener('click', () => {
            modalDiv.remove();
            input.value = '';
            const dt = new DataTransfer();
            lightFiles.forEach(f => dt.items.add(f));
            input.files = dt.files;
            input.dispatchEvent(new Event('change', { bubbles: true }));
        });
    }

    // HTML de carregamento no primeiro plano (Foreground)
    const overlayHtml = `
        <div id="uppy-global-overlay" style="position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.75);display:none;flex-direction:column;justify-content:center;align-items:center;z-index:99999;color:white;font-family:system-ui,-apple-system,sans-serif;backdrop-filter:blur(5px);">
            <div style="background:#1e1e24;border-radius:16px;padding:32px;max-width:500px;width:90%;box-shadow:0 20px 40px rgba(0,0,0,0.5);border:1px solid #2d3748;">
                <div style="display:flex;align-items:center;margin-bottom:16px;">
                    <div style="background:#910039;border-radius:50%;width:40px;height:40px;display:flex;align-items:center;justify-content:center;margin-right:12px;flex-shrink:0;">
                        <svg style="width:20px;height:20px;fill:white;" viewBox="0 0 24 24"><path d="M19.35 10.04C18.67 6.59 15.64 4 12 4 9.11 4 6.6 5.64 5.35 8.04 2.34 8.36 0 10.91 0 14c0 3.31 2.69 6 6 6h13c2.76 0 5-2.24 5-5 0-2.64-2.05-4.78-4.65-4.96zM14 13v4h-4v-4H7l5-5 5 5h-3z"/></svg>
                    </div>
                    <div>
                        <h3 style="margin:0;font-size:1.15rem;font-weight:600;">Enviando arquivo grande...</h3>
                        <p style="margin:4px 0 0 0;font-size:0.85rem;color:#a0aec0;" id="uppy-global-filename"></p>
                    </div>
                </div>
                <div style="background:#2d3748;height:10px;border-radius:5px;overflow:hidden;width:100%;margin-bottom:12px;margin-top:20px;">
                    <div id="uppy-global-progress" style="background:#910039;height:100%;width:0%;transition:width 0.3s ease;border-radius:5px;"></div>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:0.85rem;color:#cbd5e0;">
                    <span id="uppy-global-status">Processando...</span>
                    <span id="uppy-global-percentage" style="font-weight:600;">0%</span>
                </div>
                <div id="uppy-global-error" style="display:none;margin-top:16px;padding:10px 14px;background:#742a2a;border-radius:8px;font-size:0.85rem;color:#fed7d7;"></div>
            </div>
        </div>
    `;

    const wrapper = document.createElement('div');
    wrapper.innerHTML = overlayHtml;
    document.body.appendChild(wrapper.firstElementChild);

    const overlay       = document.getElementById('uppy-global-overlay');
    const progressBar   = document.getElementById('uppy-global-progress');
    const percentageText = document.getElementById('uppy-global-percentage');
    const statusText    = document.getElementById('uppy-global-status');
    const filenameText  = document.getElementById('uppy-global-filename');
    const errorBox      = document.getElementById('uppy-global-error');

    // Token CSRF necessário para as requisições POST do Laravel
    const csrfMeta  = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';

    // Helpers da UI para controle do overlay
    function showOverlay(filename) {
        errorBox.style.display = 'none';
        errorBox.textContent = '';
        progressBar.style.width = '0%';
        percentageText.innerText = '0%';
        statusText.innerText = 'Iniciando upload...';
        filenameText.innerText = filename;
        overlay.style.display = 'flex';
    }

    function updateProgress(pct, label) {
        progressBar.style.width = pct + '%';
        percentageText.innerText = pct + '%';
        if (label) statusText.innerText = label;
    }

    function hideOverlay() {
        overlay.style.display = 'none';
    }

    let activeUploadsCount = 0;

    // ─── Upload chunked via fetch ─────────────────────────────────────────────
    async function uploadInChunks(file, uuid, onProgress, onFirstChunkSuccess, modelInfo) {
        const totalChunks = Math.ceil(file.size / CHUNK_SIZE);
        let lastResponse = null;

        for (let i = 0; i < totalChunks; i++) {
            const start = i * CHUNK_SIZE;
            const end   = Math.min(start + CHUNK_SIZE, file.size);
            const chunk = file.slice(start, end);

            const formData = new FormData();
            formData.append('file', chunk, file.name);

            const response = await fetch('/admin/upload/chunk', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-TOKEN':        csrfToken,
                    'X-Unique-Upload-Id':  uuid,
                    'X-Chunk-Index':       String(i),
                    'X-Total-Chunks':      String(totalChunks),
                    'X-File-Name':         encodeURIComponent(file.name),
                    'X-Total-Size':        String(file.size),
                    'X-File-Type':         file.type || 'application/octet-stream',
                    'X-Model-Type':        modelInfo ? modelInfo.modelType : '',
                    'X-Model-Id':          modelInfo ? String(modelInfo.modelId) : '',
                    'X-Property':          modelInfo ? modelInfo.property : '',
                },
                body: formData,
            });

            if (!response.ok) {
                let msg = `Erro HTTP ${response.status}`;
                const ct = response.headers.get('Content-Type') || '';
                if (ct.includes('application/json')) {
                    try { const b = await response.json(); if (b.message) msg = b.message; } catch (_) {}
                } else if (response.status === 419) {
                    msg = 'Sessão expirada (CSRF inválido). Recarregue a página e tente novamente.';
                } else {
                    msg = `Erro HTTP ${response.status}. O servidor retornou uma resposta inesperada.`;
                }
                throw new Error(msg);
            }

            lastResponse = await response.json();
            if (!lastResponse.success) {
                throw new Error(lastResponse.message || 'Erro desconhecido no servidor.');
            }

            // Aciona o callback do primeiro chunk logo após sua confirmação no servidor
            if (i === 0 && typeof onFirstChunkSuccess === 'function') {
                onFirstChunkSuccess(lastResponse);
            }

            const pct   = Math.round(((i + 1) / totalChunks) * 100);
            const label = `Enviando parte ${i + 1} de ${totalChunks}...`;
            onProgress(pct, label);
        }

        return lastResponse;
    }

    // Alerta de saída da página caso existam envios ativos
    window.addEventListener('beforeunload', function (e) {
        if (activeUploadsCount > 0) {
            e.preventDefault();
            e.returnValue = 'Uploads estão em andamento. Se você fechar ou sair desta página, os envios serão cancelados.';
            return e.returnValue;
        }
    });

    // ─── Interceptar todos os file inputs do formulário ────────────────────────
    const fileInputs = document.querySelectorAll('input[type="file"]');

    fileInputs.forEach(input => {
        // Ignora campos específicos ou thumbnails que não devem passar pelo fluxo pesado
        if (input.dataset.uppyIgnore === 'true' || input.id === 'thumbnail' || input.name === 'thumbnail') {
            return;
        }

        input.addEventListener('change', function (e) {
            const files = e.target.files;
            if (!files || files.length === 0) return;

            const form = input.closest('form');
            if (!form) return;

            const heavyFiles = [];
            const lightFiles = [];

            // Segrega arquivos leves dos arquivos pesados
            for (let i = 0; i < files.length; i++) {
                if (files[i].size >= SIZE_THRESHOLD) {
                    heavyFiles.push(files[i]);
                } else {
                    lightFiles.push(files[i]);
                }
            }

            if (heavyFiles.length === 0) return;

            const modelInfo = window.getModelInfoFromForm(form, input.name);
            if (!modelInfo || !modelInfo.modelId) {
                showCreatePageHint(form, heavyFiles, input, lightFiles);
                return;
            }

            window.pendingUploads = window.pendingUploads || {};
            
            const fileMapping = [];
            heavyFiles.forEach(file => {
                const uuid = generateUUID();
                fileMapping.push({ file, uuid });
            });

            // Cria contêiner para renderização dos previews/thumbnails locais
            let previewList = input.parentNode.querySelector('.uppy-file-preview-list');
            if (!previewList) {
                previewList = document.createElement('div');
                previewList.className = 'uppy-file-preview-list';
                previewList.style.cssText = 'display: flex; flex-direction: column; gap: 0px; margin-top: 12px; font-family: "Outfit", sans-serif; background: #ffffff; border: 1px solid #e4e7ec; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 3px 0 rgba(16, 24, 40, 0.1), 0 1px 2px 0 rgba(16, 24, 40, 0.06);';
                input.parentNode.appendChild(previewList);
            }

            // Gera e renderiza previews instantâneos via FileReader / ObjectURL
            fileMapping.forEach(item => {
                const itemDiv = document.createElement('div');
                itemDiv.className = `uppy-preview-item uppy-preview-item-${item.uuid}`;
                itemDiv.style.cssText = 'display: flex; align-items: center; gap: 12px; background: #ffffff; padding: 12px 16px; border-bottom: 1px solid #f2f4f7; transition: all 0.3s ease;';

                let previewSrc = '';
                const isImage = item.file.type.startsWith('image/');
                if (isImage) {
                    previewSrc = URL.createObjectURL(item.file);
                }

                let iconClass = 'fa-solid fa-file';
                if (item.file.type.startsWith('video/')) iconClass = 'fa-solid fa-file-video';
                else if (item.file.type.startsWith('audio/')) iconClass = 'fa-solid fa-file-audio';
                else if (item.file.type === 'application/pdf') iconClass = 'fa-solid fa-file-pdf';
                else if (item.file.type.includes('word') || item.file.type.includes('officedocument.wordprocessingml')) iconClass = 'fa-solid fa-file-word';
                else if (item.file.type.includes('excel') || item.file.type.includes('officedocument.spreadsheetml')) iconClass = 'fa-solid fa-file-excel';
                else if (item.file.type.includes('zip') || item.file.type.includes('rar')) iconClass = 'fa-solid fa-file-zipper';

                const mediaContainer = isImage 
                    ? `<img src="${previewSrc}" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover; border: 1px solid #e4e7ec;" />`
                    : `<div style="width: 40px; height: 40px; border-radius: 8px; background: #f2f4f7; color: #475467; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; border: 1px solid #e4e7ec;"><i class="${iconClass}"></i></div>`;

                itemDiv.innerHTML = `
                    ${mediaContainer}
                    <div style="flex: 1; min-width: 0; text-align: left;">
                        <div style="font-size: 0.85rem; font-weight: 600; color: #344054; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-family: 'Outfit', sans-serif;">${item.file.name}</div>
                        <div style="font-size: 0.75rem; color: #667085; margin-top: 2px;">${formatBytes(item.file.size)}</div>
                    </div>
                    <span class="uppy-badge-${item.uuid}" style="padding: 4px 10px; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; background: #fffaeb; color: #b54708; border: 1px solid #fedf89; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 4px;">
                        <i class="fas fa-spinner fa-spin text-[10px]"></i> Enviando...
                    </span>
                `;
                previewList.appendChild(itemDiv);

                // Polling leve a cada 5 segundos no backend para atualizar o status do badge
                const pollInterval = setInterval(async () => {
                    try {
                        const res = await fetch(`/admin/upload/status/${item.uuid}`);
                        if (res.ok) {
                            const data = await res.json();
                            const badge = document.querySelector(`.uppy-badge-${item.uuid}`);
                            if (badge) {
                                if (data.upload_status === 'completed' || data.status === 'completed' || data.status === 'ready') {
                                    badge.style.background = '#ecfdf3';
                                    badge.style.color = '#027a48';
                                    badge.style.borderColor = '#abf2c6';
                                    badge.innerHTML = '<i class="fas fa-circle-check text-xs"></i> Concluído';
                                    
                                    clearInterval(pollInterval);
                                } else if (data.upload_status === 'error' || data.status === 'failed') {
                                    badge.style.background = '#fef3f2';
                                    badge.style.color = '#b42318';
                                    badge.style.borderColor = '#fecdca';
                                    badge.innerHTML = '<i class="fas fa-circle-xmark text-xs"></i> Falhou';
                                    clearInterval(pollInterval);
                                } else if (data.upload_status === 'merging' || data.status === 'merging') {
                                    badge.style.background = '#fffaeb';
                                    badge.style.color = '#b54708';
                                    badge.style.borderColor = '#fedf89';
                                    badge.innerHTML = '<i class="fas fa-arrows-spin fa-spin text-xs"></i> Mesclando...';
                                }
                            }
                        }
                    } catch (_) {
                        // Ignora erros de conexão temporários durante o polling
                    }
                }, 5000);
            });

            // Exibe modal para seleção de segundo plano ou primeiro plano
            showHeavyFilesModal(heavyFiles,
                // Ação A: Enviar em Segundo Plano (Background)
                // ─────────────────────────────────────────────────────────────
                // Fluxo via postMessage — sem salvar blobs no IndexedDB.
                // File objects são transferíveis entre abas same-origin via
                // postMessage/BroadcastChannel sem cópia de memória.
                //
                // Sequência:
                //   1. Injeta hidden inputs no form (UUID já disponível para salvar o form)
                //   2. Salva apenas os metadados no IndexedDB (sem blobs)
                //   3. Verifica se a aba do gerenciador já está aberta
                //   4. Se sim, envia via BroadcastChannel e foca a aba existente
                //   5. Se não, abre nova aba e envia via postMessage (handshake)
                //   6. Aba do produto fica 100% livre imediatamente
                // ─────────────────────────────────────────────────────────────
                async function() {
                    input.value = '';
                    const dt = new DataTransfer();
                    lightFiles.forEach(f => dt.items.add(f));
                    input.files = dt.files;
                    input.dispatchEvent(new Event('change', { bubbles: true }));

                    const origin    = window.location.origin;

                    // Prepara payload a ser enviado à nova aba após o handshake
                    const uploadPayload = [];

                    for (let item of fileMapping) {
                        // Injeta input hidden imediatamente — garante vínculo ao salvar o form
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type  = 'hidden';
                        hiddenInput.name  = `uppy_uploads[${input.name}][]`;
                        hiddenInput.value = item.uuid;
                        hiddenInput.dataset.fileUuid = item.uuid;
                        form.appendChild(hiddenInput);

                        const totalChunks = Math.ceil(item.file.size / CHUNK_SIZE);

                        // Salva apenas metadados no IndexedDB (sem blobs — evita limite de espaço)
                        await window.uppyIndexedDB.saveMetadata(
                            item.uuid, item.file.name, item.file.size,
                            item.file.type, input.name, totalChunks, modelInfo
                        );

                        uploadPayload.push({
                            uuid:       item.uuid,
                            file:       item.file,      // File object — transferível sem cópia
                            inputName:  input.name,
                            modelInfo:  modelInfo,
                            totalChunks: totalChunks
                        });
                    }

                    const isManagerOpen = await checkIfManagerOpen();
                    if (isManagerOpen) {
                        // Se a aba já está ativa, envia os arquivos via BroadcastChannel
                        const uploadBc = new BroadcastChannel('uppy-upload-channel');
                        uploadBc.postMessage({
                            type: 'START_UPLOADS',
                            payload: uploadPayload
                        });
                        uploadBc.close();
                        
                        // NOTA: Não chamamos window.open se o gerenciador já estiver aberto.
                        // Isso previne que o foco do usuário seja roubado, permitindo que continue preenchendo a tela atual.
                    } else {
                        // Abre o gerenciador de uploads em janela popup para não atrapalhar a navegação principal
                        const managerWindow = window.open('/admin/upload-manager?popup=1', 'uppy_upload_manager', 'width=850,height=600,menubar=no,toolbar=no,location=no,status=no,scrollbars=yes,resizable=yes');

                        if (managerWindow) {
                            try {
                                managerWindow.blur();
                            } catch(e) {}
                            window.focus();
                        }

                        // Aguarda o sinal de prontidão da nova aba antes de enviar os arquivos
                        const readyHandler = function(event) {
                            if (event.source !== managerWindow) return;
                            if (!event.data || event.data.type !== 'UPLOAD_MANAGER_READY') return;

                            window.removeEventListener('message', readyHandler);

                            // Envia os File objects diretamente para a nova aba
                            managerWindow.postMessage({
                                type:    'START_UPLOADS',
                                payload: uploadPayload
                            }, origin);
                        };
                        window.addEventListener('message', readyHandler);

                        // Timeout de segurança: se a nova aba não sinalizar em 15s, remove o listener
                        setTimeout(() => window.removeEventListener('message', readyHandler), 15000);
                    }
                },
                // Ação B: Enviar nesta aba (Foreground)
                async function() {
                    const dt = new DataTransfer();
                    lightFiles.forEach(f => dt.items.add(f));
                    input.files = dt.files;
                    input.dispatchEvent(new Event('change', { bubbles: true }));

                    // Envia os arquivos de forma sequencial bloqueando a interface
                    for (let item of fileMapping) {
                        showOverlay(item.file.name);
                        activeUploadsCount++;

                        try {
                            await uploadInChunks(item.file, item.uuid, (pct, label) => {
                                updateProgress(pct, label);
                            }, () => {
                                // Injeta o input hidden logo após o primeiro chunk ser enviado com sucesso
                                const hiddenInput = document.createElement('input');
                                hiddenInput.type  = 'hidden';
                                hiddenInput.name  = `uppy_uploads[${input.name}][]`;
                                hiddenInput.value = item.uuid;
                                hiddenInput.dataset.fileUuid = item.uuid;
                                form.appendChild(hiddenInput);
                            }, modelInfo);
                            hideOverlay();
                            activeUploadsCount--;
                        } catch (err) {
                            hideOverlay();
                            activeUploadsCount--;
                            alert(`Falha ao enviar arquivo "${item.file.name}": ${err.message}`);
                            
                            const hiddenInput = form.querySelector(`input[data-file-uuid="${item.uuid}"]`);
                            if (hiddenInput) hiddenInput.remove();
                        }
                    }
                },
                // Ação C: Cancelar
                function() {
                    input.value = '';
                    const dt = new DataTransfer();
                    lightFiles.forEach(f => dt.items.add(f));
                    input.files = dt.files;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                    
                    fileMapping.forEach(item => {
                        const itemEl = previewList.querySelector(`.uppy-preview-item-${item.uuid}`);
                        if (itemEl) itemEl.remove();
                    });
                    if (previewList.children.length === 0) {
                        previewList.remove();
                    }
                }
            );
        });
    });

    // ─── BroadcastChannel e Floating Widget ──────────────────────────────────
    const bc = new BroadcastChannel('uppy-upload-channel');

    function showFloatingWidget(activeCount, overallProgress) {
        let widget = document.getElementById('uppy-floating-widget');
        if (!widget) {
            widget = document.createElement('div');
            widget.id = 'uppy-floating-widget';
            widget.style.cssText = 'position:fixed;bottom:24px;right:24px;background:#0f172a;color:#f8fafc;padding:14px 20px;border-radius:16px;box-shadow:0 10px 30px rgba(0,0,0,0.4);z-index:99999;font-family:\'Outfit\',sans-serif;border:1px solid rgba(255,255,255,0.08);display:flex;align-items:center;gap:12px;transition:all 0.3s ease;animation:slideIn 0.3s ease;cursor:pointer;';
            widget.innerHTML = `
                <div style="background:#910039;border-radius:50%;width:28px;height:28px;display:flex;align-items:center;justify-content:center;color:white;animation:spin 2s linear infinite;flex-shrink:0;">
                    <svg style="width:14px;height:14px;fill:currentColor;" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H7c0-2.76 2.24-5 5-5s5 2.24 5 5c0 1.04-.42 1.99-1.07 2.75z"/></svg>
                </div>
                <div style="display:flex;flex-direction:column;gap:2px;">
                    <span id="uppy-floating-title" style="font-weight:600;font-size:0.85rem;white-space:nowrap;">Enviando arquivos...</span>
                    <span id="uppy-floating-desc" style="font-size:0.75rem;color:#94a3b8;white-space:nowrap;">0% concluído</span>
                </div>
                <style>
                    @keyframes spin { 100% { transform: rotate(360deg); } }
                    #uppy-floating-widget:hover { transform: translateY(-2px); box-shadow: 0 12px 35px rgba(145,0,57,0.25); background:#1e293b; }
                </style>
            `;
            document.body.appendChild(widget);
            
            widget.addEventListener('click', () => {
                // Abre ou foca o gerenciador de uploads sem duplicar
                checkIfManagerOpen().then(isOpen => {
                    if (isOpen) {
                        window.open('', 'uppy_upload_manager');
                    } else {
                        window.open('/admin/upload-manager?popup=1', 'uppy_upload_manager', 'width=850,height=600,menubar=no,toolbar=no,location=no,status=no,scrollbars=yes,resizable=yes');
                    }
                });
            });
        }

        document.getElementById('uppy-floating-title').innerText = `Enviando ${activeCount} arquivo(s) em 2º plano`;
        document.getElementById('uppy-floating-desc').innerText = `${overallProgress}% concluído • Clique para abrir`;
        widget.style.display = 'flex';
    }

    function hideFloatingWidget() {
        const widget = document.getElementById('uppy-floating-widget');
        if (widget) widget.style.display = 'none';
    }

    bc.onmessage = (event) => {
        if (event.data && event.data.type === 'STATUS_UPDATE') {
            const { activeCount, overallProgress } = event.data;
            if (activeCount > 0) {
                showFloatingWidget(activeCount, overallProgress);
            } else {
                hideFloatingWidget();
            }
        }
    };

    // ─── Global Badges Polling ────────────────────────────────────────────────
    function startGlobalBadgesPolling() {
        const pendingItems = document.querySelectorAll('.uppy-file-item[data-file-uuid]');
        if (pendingItems.length === 0) return;

        const uuidsToPoll = new Set();
        pendingItems.forEach(item => {
            const uuid = item.dataset.fileUuid;
            if (uuid) {
                uuidsToPoll.add(uuid);
            }
        });

        uuidsToPoll.forEach(uuid => {
            const intervalId = setInterval(async () => {
                try {
                    const res = await fetch(`/admin/upload/status/${uuid}`);
                    if (res.ok) {
                        const data = await res.json();
                        if (data.status === 'completed' || data.upload_status === 'completed' || data.status === 'ready') {
                            clearInterval(intervalId);
                            // Quando concluído, recarrega a página para atualizar o layout naturalmente
                            window.location.reload();
                        }
                    }
                } catch (_) {
                    // Ignora erros temporários
                }
            }, 5000);
        });
    }

    startGlobalBadgesPolling();

    // Solicita status atual para as outras abas
    setTimeout(() => {
        bc.postMessage({ type: 'REQUEST_STATUS' });
    }, 500);
});


/**
 * Helper global para inicializar o painel/dashboard Uppy.
 */
window.initUppyDashboard = function(options) {
    const {
        formSelector,
        dashboardSelector,
        containerSelector,
        inputName = 'files[]',
        maxFileSize = 5368709120, // 5GB
        allowedFileTypes = null,
        note = 'Upload segmentado automático para arquivos grandes.'
    } = options;

    const form = document.querySelector(formSelector);
    const dashboardEl = document.querySelector(dashboardSelector);
    const container = document.querySelector(containerSelector);

    if (!form || !dashboardEl || !container) {
        return null;
    }

    const CHUNK_SIZE = 5 * 1024 * 1024;
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
    let activeUploadsCount = 0;

    const modelInfo = window.getModelInfoFromForm(form, inputName);

    function generateUUID() {
        if (typeof crypto !== 'undefined' && typeof crypto.randomUUID === 'function') {
            return crypto.randomUUID();
        }
        return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, c => {
            const r = Math.random() * 16 | 0, v = c === 'x' ? r : (r & 0x3 | 0x8);
            return v.toString(16);
        });
    }

    // Realiza o upload segmentado das partes de um arquivo específico do Dashboard
    async function uploadInChunks(file, uuid, onProgress) {
        const totalChunks = Math.ceil(file.size / CHUNK_SIZE);

        for (let i = 0; i < totalChunks; i++) {
            const start = i * CHUNK_SIZE;
            const end   = Math.min(start + CHUNK_SIZE, file.size);
            const chunk = file.slice(start, end);

            const formData = new FormData();
            formData.append('file', chunk, file.name);

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
                    'X-Model-Type':       modelInfo ? modelInfo.modelType : '',
                    'X-Model-Id':         modelInfo ? String(modelInfo.modelId) : '',
                    'X-Property':         modelInfo ? modelInfo.property : '',
                },
                body: formData,
            });

            if (!response.ok) {
                let msg = `Erro HTTP ${response.status}`;
                try { const b = await response.json(); if (b.message) msg = b.message; } catch (_) {}
                throw new Error(msg);
            }

            const result = await response.json();
            if (!result.success) throw new Error(result.message || 'Erro no servidor.');

            // Injeta o input hidden logo após a validação bem-sucedida do primeiro chunk no servidor
            if (i === 0) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type  = 'hidden';
                hiddenInput.name  = `uppy_uploads[${inputName}][]`;
                hiddenInput.value = uuid;
                hiddenInput.id    = `file-uuid-${uuid}`;
                container.appendChild(hiddenInput);
            }

            onProgress(Math.round(((i + 1) / totalChunks) * 100), result);

            if (result.status === 'completed') return result;
        }
    }

    const { Uppy, Dashboard } = window.Uppy;

    const uppy = new Uppy({
        id: dashboardSelector.replace('#', '') + '-uploader',
        autoProceed: false,
        restrictions: {
            maxFileSize: maxFileSize,
            allowedFileTypes: allowedFileTypes
        }
    })
    .use(Dashboard, {
        target: dashboardSelector,
        inline: true,
        showProgressDetails: true,
        height: 320,
        width: '100%',
        note: note,
        locale: {
            strings: {
                dropPasteFiles: 'Arraste e solte arquivos aqui ou %{browseFiles}',
                browseFiles: 'clique para selecionar',
                uploadXFiles: {
                    0: 'Enviar %{smart_count} arquivo',
                    1: 'Enviar %{smart_count} arquivos'
                }
            }
        }
    });

    uppy.on('file-added', (file) => {
        const uuid = generateUUID();
        uppy.setFileMeta(file.id, { uuid: uuid });
    });

    uppy.on('upload', async (data) => {
        const fileIDs = data.fileIDs;
        activeUploadsCount += fileIDs.length;

        for (const fileId of fileIDs) {
            const uppyFile = uppy.getFile(fileId);
            if (!uppyFile) continue;
            const file = uppyFile.data;
            const uuid = uppyFile.meta.uuid;

            try {
                uppy.setFileState(fileId, { progress: { uploadStarted: Date.now(), uploadComplete: false, percentage: 0, bytesUploaded: 0, bytesTotal: file.size } });

                await uploadInChunks(file, uuid, (pct) => {
                    uppy.setFileState(fileId, {
                        progress: { uploadStarted: uppyFile.progress.uploadStarted, uploadComplete: false, percentage: pct, bytesUploaded: Math.round(file.size * pct / 100), bytesTotal: file.size }
                    });
                });

                uppy.setFileState(fileId, {
                    progress: { uploadStarted: uppyFile.progress.uploadStarted, uploadComplete: true, percentage: 100, bytesUploaded: file.size, bytesTotal: file.size }
                });

                activeUploadsCount--;
            } catch (err) {
                uppy.setFileState(fileId, { error: err.message });
                activeUploadsCount--;
                alert(`Erro ao enviar "${file.name}": ${err.message}`);
            }
        }
    });

    uppy.on('file-removed', (file) => {
        const uuid = file.meta && file.meta.uuid;
        if (uuid) {
            const hiddenInput = document.getElementById(`file-uuid-${uuid}`);
            if (hiddenInput) hiddenInput.remove();
        }
    });

    window.addEventListener('beforeunload', function (e) {
        if (activeUploadsCount > 0) {
            e.preventDefault();
            e.returnValue = 'Uploads estão em andamento. Se você sair desta página, os envios serão cancelados.';
            return e.returnValue;
        }
    });

    form.addEventListener('submit', function(e) {
        const pendingFiles = uppy.getFiles().filter(f => !f.progress.uploadComplete && !f.error);
        if (pendingFiles.length > 0) {
            e.preventDefault();
            if (confirm('Você tem arquivos pendentes de upload no painel. Deseja iniciar o upload deles agora?')) {
                uppy.upload();
            }
            return;
        }

        if (activeUploadsCount > 0) {
            e.preventDefault();
            const submitBtn = form.querySelector('[type="submit"]');
            if (submitBtn) submitBtn.disabled = true;

            const formData = new FormData(form);

            const banner = document.createElement('div');
            banner.style.cssText = 'position:fixed;bottom:24px;right:24px;background:#1a202c;color:white;padding:16px 24px;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,0.3);z-index:99999;font-family:system-ui;border:1px solid #2d3748;';
            banner.innerHTML = `
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="border:3px solid #cbd5e0;border-top:3px solid #910039;border-radius:50%;width:20px;height:20px;animation:spin 1s linear infinite;"></div>
                    <span style="font-weight:600;">Salvando dados...</span>
                </div>
                <style>@keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }</style>
            `;
            document.body.appendChild(banner);

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData,
            })
            .then(response => {
                if (!response.ok) throw new Error('Falha ao salvar formulário.');
                
                banner.innerHTML = `
                    <div style="display:flex;align-items:center;gap:10px;color:#68d391;">
                        <span>✅</span>
                        <span style="font-weight:600;">Salvo com sucesso! O upload continuará em segundo plano.</span>
                    </div>
                    <p style="margin:4px 0 0 0;font-size:0.8rem;color:#a0aec0;">Por favor, não feche esta página até o fim do envio.</p>
                `;

                form.querySelectorAll('input, textarea, select, button').forEach(el => {
                    if (el.type !== 'submit') el.disabled = true;
                });

                setTimeout(() => banner.remove(), 8000);
            })
            .catch(err => {
                banner.innerHTML = `
                    <div style="display:flex;align-items:center;gap:10px;color:#fc8181;">
                        <span>❌</span>
                        <span style="font-weight:600;">Erro ao salvar.</span>
                    </div>
                `;
                if (submitBtn) submitBtn.disabled = false;
                setTimeout(() => banner.remove(), 5000);
            });
        }
    });

    return uppy;
};
