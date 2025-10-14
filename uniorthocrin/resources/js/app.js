import './bootstrap';

// Importar componentes
import './components/Common';
import './components/Dashboard';
import './components/ProductGallery';
import './components/ProductVideoPlayer';
import './components/ProductList';
import './components/DownloadManager';
import './components/MarketingDetail';

// Inicializar componentes comuns em todas as páginas
document.addEventListener('DOMContentLoaded', function() {
    // Sempre inicializar Common
    if (window.Common) {
        new window.Common();
    }
    
    // Sempre inicializar DownloadManager
    if (window.DownloadManager) {
        new window.DownloadManager();
    }
    
    // Inicializar Dashboard se estivermos na home (raiz)
    if (window.location.pathname === '/') {
        if (window.Dashboard) {
            new window.Dashboard();
        }
    }
    
    // Inicializar ProductList se estivermos na página de lista de produtos
    if (window.location.pathname === '/produtos-list') {
        if (window.ProductList) {
            new window.ProductList();
        }
    }
    
    // Inicializar componentes de produto se estivermos na página de detalhes
    if (window.location.pathname.match(/\/produtos\/\d+/)) {
        console.log('Página de detalhes do produto detectada');
        // Os componentes ProductGallery e ProductVideoPlayer serão inicializados
        // pelo script inline na página, pois precisam dos dados do PHP
    }
    
    // Inicializar MarketingDetail se estivermos na página de detalhes de marketing
    if (window.location.pathname.match(/\/marketing\/\d+/)) {
        console.log('Página de detalhes de marketing detectada');
        // O componente MarketingDetail será inicializado pelo script inline na página
    }
});
