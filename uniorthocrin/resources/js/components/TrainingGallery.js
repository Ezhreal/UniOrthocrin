/**
 * Training Gallery Component
 * Gerencia o carousel de imagens dos treinamentos
 */
class TrainingGallery {
    constructor(containerId, images = []) {
        this.container = document.getElementById(containerId);
        this.images = images;
        this.currentIndex = 0;
        
        this.prevBtn = document.getElementById('prevBtn');
        this.nextBtn = document.getElementById('nextBtn');
        this.leftImage = document.getElementById('leftImage');
        this.centerImage = document.getElementById('centerImage');
        this.rightImage = document.getElementById('rightImage');
        this.indicatorsContainer = document.getElementById('indicators');
        
        this.init();
    }
    
    init() {
        if (this.images.length === 0) return;
        
        this.createIndicators();
        this.updateCarousel();
        this.bindEvents();
    }
    
    createIndicators() {
        if (!this.indicatorsContainer) return;
        
        this.indicatorsContainer.innerHTML = '';
        this.images.forEach((_, index) => {
            const indicator = document.createElement('div');
            indicator.className = `w-3 h-3 rounded-full cursor-pointer transition-colors ${index === 0 ? 'bg-[#910039]' : 'bg-gray-300'}`;
            indicator.addEventListener('click', () => {
                this.currentIndex = index;
                this.updateCarousel();
                this.updateIndicators();
            });
            this.indicatorsContainer.appendChild(indicator);
        });
    }
    
    updateIndicators() {
        if (!this.indicatorsContainer) return;
        
        const indicators = this.indicatorsContainer.children;
        for (let i = 0; i < indicators.length; i++) {
            if (i === this.currentIndex) {
                indicators[i].classList.add('bg-[#910039]');
                indicators[i].classList.remove('bg-gray-300');
            } else {
                indicators[i].classList.remove('bg-[#910039]');
                indicators[i].classList.add('bg-gray-300');
            }
        }
    }
    
    updateCarousel() {
        if (!this.leftImage || !this.centerImage || !this.rightImage) return;
        
        const leftImgElement = this.leftImage.querySelector('img');
        const centerImgElement = this.centerImage.querySelector('img');
        const rightImgElement = this.rightImage.querySelector('img');
        
        if (leftImgElement && centerImgElement && rightImgElement) {
            leftImgElement.src = this.images[(this.currentIndex - 1 + this.images.length) % this.images.length].src;
            centerImgElement.src = this.images[this.currentIndex].src;
            rightImgElement.src = this.images[(this.currentIndex + 1) % this.images.length].src;
            
            // Atualizar alt text
            leftImgElement.alt = this.images[(this.currentIndex - 1 + this.images.length) % this.images.length].alt;
            centerImgElement.alt = this.images[this.currentIndex].alt;
            rightImgElement.alt = this.images[(this.currentIndex + 1) % this.images.length].alt;
            
            // Aplicar blur nas imagens laterais
            this.leftImage.style.filter = 'blur(2px)';
            this.rightImage.style.filter = 'blur(2px)';
            this.centerImage.style.filter = 'none';
        }
    }
    
    bindEvents() {
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => {
                this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                this.updateCarousel();
                this.updateIndicators();
            });
        }
        
        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => {
                this.currentIndex = (this.currentIndex + 1) % this.images.length;
                this.updateCarousel();
                this.updateIndicators();
            });
        }
    }
}

// Exportar para uso global
window.TrainingGallery = TrainingGallery;
