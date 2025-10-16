// Loading sutil para botões de ação
class ButtonLoading {
    constructor() {
        this.init();
    }

    init() {
        this.setupFormButtons();
    }

    setupFormButtons() {
        // Interceptar apenas submissões de formulário (criar/editar)
        document.addEventListener('submit', (e) => {
            const form = e.target;
            const submitButton = form.querySelector('button[type="submit"]');
            
            // Só aplicar loading em formulários de criação/edição
            if (submitButton && !submitButton.hasAttribute('data-no-loading') && this.isCreateEditForm(form)) {
                this.showButtonLoading(submitButton);
            }
        });
    }

    isCreateEditForm(form) {
        // Verificar se é um formulário de criação/edição
        const action = form.action || '';
        const method = form.method || 'GET';
        
        return (
            method.toLowerCase() === 'post' && 
            (action.includes('/store') || action.includes('/update') || action.includes('/create') || action.includes('/edit'))
        );
    }


    showButtonLoading(button) {
        // Salvar estado original
        if (!button.dataset.originalContent) {
            button.dataset.originalContent = button.innerHTML;
        }
        if (!button.dataset.originalDisabled) {
            button.dataset.originalDisabled = button.disabled;
        }

        // Aplicar loading sutil - só spinner pequeno
        button.disabled = true;
        button.innerHTML = `
            <div class="flex items-center space-x-2">
                <div class="animate-spin rounded-full border-2 border-current border-t-transparent w-4 h-4"></div>
                <span>Processando...</span>
            </div>
        `;

        // Restaurar após timeout (segurança)
        setTimeout(() => {
            this.hideButtonLoading(button);
        }, 10000);
    }

    hideButtonLoading(button) {
        if (button.dataset.originalContent) {
            button.innerHTML = button.dataset.originalContent;
        }
        if (button.dataset.originalDisabled !== undefined) {
            button.disabled = button.dataset.originalDisabled === 'true';
        }
    }

    // Método público para mostrar loading manualmente
    static showLoading(buttonSelector, loadingText = 'Processando...') {
        const button = document.querySelector(buttonSelector);
        if (button) {
            const instance = new ButtonLoading();
            instance.showButtonLoading(button, loadingText);
        }
    }

    // Método público para esconder loading manualmente
    static hideLoading(buttonSelector) {
        const button = document.querySelector(buttonSelector);
        if (button) {
            const instance = new ButtonLoading();
            instance.hideButtonLoading(button);
        }
    }
}

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    new ButtonLoading();
});

// Funções globais para uso manual
window.showButtonLoading = ButtonLoading.showLoading;
window.hideButtonLoading = ButtonLoading.hideLoading;
