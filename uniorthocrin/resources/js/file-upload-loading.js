// Loading específico para uploads de arquivos
class FileUploadLoading {
    constructor() {
        this.init();
    }

    init() {
        this.setupFileInputs();
        this.setupFormSubmissions();
    }

    setupFileInputs() {
        // Interceptar mudanças em inputs de arquivo
        document.addEventListener('change', (e) => {
            if (e.target.type === 'file' && e.target.files.length > 0) {
                this.showFileUploadLoading(e.target);
            }
        });
    }

    setupFormSubmissions() {
        // Interceptar submissões de formulários com arquivos
        document.addEventListener('submit', (e) => {
            const form = e.target;
            const fileInputs = form.querySelectorAll('input[type="file"]');
            
            if (fileInputs.length > 0) {
                const hasFiles = Array.from(fileInputs).some(input => input.files.length > 0);
                if (hasFiles) {
                    this.showFormUploadLoading(form);
                }
            }
        });
    }

    showFileUploadLoading(input) {
        const container = input.closest('.file-upload-container') || input.parentElement;
        
        // Criar loading específico para o input
        let loadingElement = container.querySelector('.file-upload-loading');
        if (!loadingElement) {
            loadingElement = document.createElement('div');
            loadingElement.className = 'file-upload-loading absolute inset-0 bg-white bg-opacity-90 flex items-center justify-center rounded-lg';
            loadingElement.innerHTML = `
                <div class="flex flex-col items-center space-y-2">
                    <div class="animate-spin rounded-full border-2 border-gray-200 border-t-blue-600 w-6 h-6"></div>
                    <span class="text-xs text-gray-600">Processando arquivo...</span>
                </div>
            `;
            container.style.position = 'relative';
            container.appendChild(loadingElement);
        }
        
        loadingElement.classList.remove('hidden');
        
        // Esconder após 3 segundos (tempo estimado de processamento)
        setTimeout(() => {
            loadingElement.classList.add('hidden');
        }, 3000);
    }

    showFormUploadLoading(form) {
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) {
            const originalText = submitButton.textContent;
            const originalDisabled = submitButton.disabled;
            
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <div class="flex items-center space-x-2">
                    <div class="animate-spin rounded-full border-2 border-white border-t-transparent w-4 h-4"></div>
                    <span>Enviando...</span>
                </div>
            `;
            
            // Restaurar botão após 10 segundos (timeout de segurança)
            setTimeout(() => {
                submitButton.disabled = originalDisabled;
                submitButton.textContent = originalText;
            }, 10000);
        }
    }
}

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    new FileUploadLoading();
});
