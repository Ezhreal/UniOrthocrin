/**
 * Dashboard Component
 * Gerencia interações e animações do dashboard
 */
class Dashboard {
    constructor() {
        this.init();
    }
    
    init() {
        this.bindEvents();
        this.initializeCounters();
    }
    
    bindEvents() {
        // Animações de entrada dos cards
        this.animateCards();
        
        // Hover effects nos cards
        this.setupCardHovers();
        
        // Contadores animados
        this.setupCounters();
    }
    
    animateCards() {
        const cards = document.querySelectorAll('.dashboard-card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    }
    
    setupCardHovers() {
        const cards = document.querySelectorAll('.dashboard-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-5px)';
                card.style.boxShadow = '0 10px 25px rgba(0,0,0,0.1)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
                card.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
            });
        });
    }
    
    setupCounters() {
        const counters = document.querySelectorAll('.counter');
        counters.forEach(counter => {
            const target = parseInt(counter.getAttribute('data-target'));
            const duration = 2000; // 2 segundos
            const increment = target / (duration / 16); // 60fps
            let current = 0;
            
            const updateCounter = () => {
                current += increment;
                if (current < target) {
                    counter.textContent = Math.floor(current);
                    requestAnimationFrame(updateCounter);
                } else {
                    counter.textContent = target;
                }
            };
            
            // Iniciar quando o elemento estiver visível
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        updateCounter();
                        observer.unobserve(entry.target);
                    }
                });
            });
            
            observer.observe(counter);
        });
    }
    
    initializeCounters() {
        // Contadores para estatísticas do dashboard
        const stats = {
            'total-products': document.querySelectorAll('.product-card').length,
            'total-trainings': document.querySelectorAll('.training-card').length,
            'total-news': document.querySelectorAll('.news-card').length
        };
        
        Object.entries(stats).forEach(([key, value]) => {
            const element = document.getElementById(key);
            if (element) {
                element.textContent = value;
            }
        });
    }
    
    refreshDashboard() {
        // Método para atualizar dados do dashboard via AJAX
        fetch('/api/dashboard/stats')
            .then(response => response.json())
            .then(data => {
                this.updateStats(data);
            })
            .catch(error => {
                console.error('Erro ao atualizar dashboard:', error);
            });
    }
    
    updateStats(data) {
        // Atualizar estatísticas com novos dados
        if (data.products) {
            const element = document.getElementById('total-products');
            if (element) element.textContent = data.products;
        }
        
        if (data.trainings) {
            const element = document.getElementById('total-trainings');
            if (element) element.textContent = data.trainings;
        }
        
        if (data.news) {
            const element = document.getElementById('total-news');
            if (element) element.textContent = data.news;
        }
    }
}

// Exportar para uso global
window.Dashboard = Dashboard;
