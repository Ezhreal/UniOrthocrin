/**
 * Download Manager Component
 * Gerencia downloads de arquivos baseado no contexto
 */
class DownloadManager {
    constructor() {
        this.init();
    }
    
    init() {
        this.setupDownloadButtons();
    }
    
    setupDownloadButtons() {
        // Botões de download por contexto
        const downloadButtons = document.querySelectorAll('[data-download]');
        
        downloadButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                this.handleDownload(button);
            });
        });
    }
    
    handleDownload(button) {
        const downloadType = button.getAttribute('data-download');
        const contentId = button.getAttribute('data-content-id');
        const contentType = button.getAttribute('data-content-type');
        const context = button.getAttribute('data-context');
        const productIds = button.getAttribute('data-product-ids');
        
        console.log('Download solicitado:', { downloadType, contentId, contentType, context, productIds });
        
        // Mostrar loading
        this.showLoading(button);
        
        // Fazer requisição para download
        this.requestDownload(downloadType, contentId, contentType, context, productIds)
            .then(response => {
                this.hideLoading(button);
                if (response.success) {
                    // Para todos os tipos, download direto
                    this.downloadFile(response.downloadUrl, response.filename);
                } else {
                    this.showError('Erro ao gerar download');
                }
            })
            .catch(error => {
                this.hideLoading(button);
                this.showError('Erro ao processar download');
                console.error('Download error:', error);
            });
    }
    
    async requestDownload(type, contentId, contentType, context, productIds) {
        // Buscar o token CSRF de forma segura
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         document.querySelector('input[name="_token"]')?.value ||
                         '';
        
        console.log('Fazendo requisição de download:', {
            type, contentId, contentType, context, productIds, csrfToken: !!csrfToken
        });
        
        const response = await fetch('/download', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                type: type,
                content_id: contentId,
                content_type: contentType,
                context: context,
                product_ids: productIds
            })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        return await response.json();
    }
    
    downloadFile(url, filename) {
        const link = document.createElement('a');
        link.href = url;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
    
    showLoading(button) {
        const originalText = button.innerHTML;
        button.setAttribute('data-original-text', originalText);
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Gerando...';
        button.disabled = true;
    }
    
    hideLoading(button) {
        const originalText = button.getAttribute('data-original-text');
        button.innerHTML = originalText;
        button.disabled = false;
    }
    
    showError(message) {
        // Implementar notificação de erro
        alert(message);
    }
    

    

}

// Exportar para uso global
window.DownloadManager = DownloadManager;
