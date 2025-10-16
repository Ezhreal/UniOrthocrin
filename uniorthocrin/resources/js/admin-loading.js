// Sistema de Loading Global para Admin
class AdminLoading {
    constructor() {
        this.loadingOverlay = null;
        this.activeRequests = 0;
        this.init();
    }

    init() {
        this.createLoadingOverlay();
        this.setupAjaxInterceptors();
        this.setupFormSubmissions();
    }

    createLoadingOverlay() {
        this.loadingOverlay = document.createElement('div');
        this.loadingOverlay.id = 'admin-loading-overlay';
        this.loadingOverlay.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden';
        this.loadingOverlay.innerHTML = `
            <div class="bg-white rounded-lg p-6 flex flex-col items-center space-y-4">
                <div class="animate-spin rounded-full border-4 border-gray-200 border-t-blue-600 w-12 h-12"></div>
                <p class="text-gray-600">Processando...</p>
            </div>
        `;
        document.body.appendChild(this.loadingOverlay);
    }

    setupAjaxInterceptors() {
        // Interceptar requisições fetch
        const originalFetch = window.fetch;
        window.fetch = (...args) => {
            this.showLoading();
            return originalFetch(...args)
                .finally(() => this.hideLoading());
        };

        // Interceptar requisições XMLHttpRequest
        const originalXHR = window.XMLHttpRequest;
        window.XMLHttpRequest = function() {
            const xhr = new originalXHR();
            const originalOpen = xhr.open;
            const originalSend = xhr.send;

            xhr.open = function(method, url, ...args) {
                this._url = url;
                return originalOpen.apply(this, [method, url, ...args]);
            };

            xhr.send = function(data) {
                // Só mostrar loading para requisições que não são de upload de arquivo
                if (!this._url?.includes('upload') && !this._url?.includes('file')) {
                    AdminLoading.getInstance().showLoading();
                }
                
                this.addEventListener('loadend', () => {
                    AdminLoading.getInstance().hideLoading();
                });

                return originalSend.apply(this, [data]);
            };

            return xhr;
        };
    }

    setupFormSubmissions() {
        // Interceptar submissões de formulário
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (form.tagName === 'FORM' && !form.hasAttribute('data-no-loading')) {
                this.showLoading();
                
                // Esconder loading quando a página recarregar ou houver erro
                setTimeout(() => this.hideLoading(), 10000); // Timeout de 10s
            }
        });
    }

    showLoading() {
        this.activeRequests++;
        if (this.loadingOverlay && this.activeRequests > 0) {
            this.loadingOverlay.classList.remove('hidden');
        }
    }

    hideLoading() {
        this.activeRequests = Math.max(0, this.activeRequests - 1);
        if (this.loadingOverlay && this.activeRequests === 0) {
            this.loadingOverlay.classList.add('hidden');
        }
    }

    static getInstance() {
        if (!window.adminLoading) {
            window.adminLoading = new AdminLoading();
        }
        return window.adminLoading;
    }
}

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    AdminLoading.getInstance();
});

// Função global para mostrar loading manualmente
window.showAdminLoading = (text = 'Processando...') => {
    const overlay = document.getElementById('admin-loading-overlay');
    if (overlay) {
        overlay.querySelector('p').textContent = text;
        overlay.classList.remove('hidden');
    }
};

// Função global para esconder loading manualmente
window.hideAdminLoading = () => {
    const overlay = document.getElementById('admin-loading-overlay');
    if (overlay) {
        overlay.classList.add('hidden');
    }
};

