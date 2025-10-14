/**
 * Product List Component
 * Gerencia filtros, busca e paginação da lista de produtos
 */
class ProductList {
    constructor() {
        this.searchInput = document.getElementById('searchInput');
        this.categoryFilter = document.getElementById('categoryFilter');
        this.productsGrid = document.getElementById('productsGrid');
        this.paginationContainer = document.getElementById('paginationContainer');
        
        this.init();
    }
    
    init() {
        this.bindEvents();
    }
    
    bindEvents() {
        // Busca em tempo real
        if (this.searchInput) {
            this.searchInput.addEventListener('input', this.debounce(() => {
                this.performSearch();
            }, 300));
        }
        
        // Filtro por categoria
        if (this.categoryFilter) {
            this.categoryFilter.addEventListener('change', () => {
                this.performSearch();
            });
        }
    }
    
    performSearch() {
        const searchTerm = this.searchInput ? this.searchInput.value : '';
        const categoryId = this.categoryFilter ? this.categoryFilter.value : '';
        
        // Construir URL com parâmetros
        const url = new URL(window.location);
        if (searchTerm) {
            url.searchParams.set('search', searchTerm);
        } else {
            url.searchParams.delete('search');
        }
        
        if (categoryId) {
            url.searchParams.set('category', categoryId);
        } else {
            url.searchParams.delete('category');
        }
        
        // Resetar para primeira página
        url.searchParams.delete('page');
        
        // Redirecionar com os filtros
        window.location.href = url.toString();
    }
    
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
    
    updateProductCount(count) {
        const countElement = document.getElementById('productCount');
        if (countElement) {
            countElement.textContent = count;
        }
    }
    
    updateCategoryCount(categoryId, count) {
        const categoryElement = document.querySelector(`[data-category="${categoryId}"]`);
        if (categoryElement) {
            const countSpan = categoryElement.querySelector('.count');
            if (countSpan) {
                countSpan.textContent = count;
            }
        }
    }
}

// Exportar para uso global
window.ProductList = ProductList;
