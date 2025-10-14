/**
 * Marketing Detail Component
 * Gerencia a tela de detalhes de marketing com tabs e componentes
 */
class MarketingDetail {
    constructor(campaignData) {
        this.campaignData = campaignData;
        this.currentPostGallery = null;
        this.currentVideoPlayer = null;
        
        this.init();
    }
    
    init() {
        console.log('MarketingDetail: Inicializando com dados:', this.campaignData);
        
        // Inicializar componentes
        this.initPostGallery();
        this.initVideoPlayer();
        this.initTabs();
    }
    
    initPostGallery() {
        if (!this.campaignData.postsByType || Object.keys(this.campaignData.postsByType).length === 0) {
            console.log('MarketingDetail: Nenhum post disponível para galeria');
            return;
        }
        
        const firstType = Object.keys(this.campaignData.postsByType)[0];
        const firstTypeImages = this.campaignData.postsByType[firstType] || [];
        
        if (firstTypeImages.length > 0 && window.ProductGallery) {
            console.log('MarketingDetail: Inicializando ProductGallery para posts do tipo:', firstType);
            this.currentPostGallery = new window.ProductGallery('productGallery', firstTypeImages);
        }
    }
    
    initVideoPlayer() {
        if (!this.campaignData.videosByType || Object.keys(this.campaignData.videosByType).length === 0) {
            console.log('MarketingDetail: Nenhum vídeo disponível para player');
            return;
        }
        
        const firstType = Object.keys(this.campaignData.videosByType)[0];
        const firstTypeVideos = this.campaignData.videosByType[firstType] || [];
        
        if (firstTypeVideos.length > 0 && window.ProductVideoPlayer) {
            console.log('MarketingDetail: Inicializando ProductVideoPlayer para vídeos do tipo:', firstType);
            this.currentVideoPlayer = new window.ProductVideoPlayer(firstTypeVideos);
        }
    }
    
    initTabs() {
        this.initPostTabs();
        this.initVideoTabs();
    }
    
    initPostTabs() {
        const postTabBtns = document.querySelectorAll('.post-tab-btn');
        if (postTabBtns.length === 0) return;
        
        postTabBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.switchPostTab(btn.dataset.type);
            });
        });
    }
    
    initVideoTabs() {
        const videoTabBtns = document.querySelectorAll('.video-tab-btn');
        if (videoTabBtns.length === 0) return;
        
        videoTabBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                this.switchVideoTab(btn.dataset.type);
            });
        });
    }
    
    switchPostTab(type) {
        console.log('MarketingDetail: Trocando tab de posts para:', type);
        
        // Atualizar estado das tabs
        const postTabBtns = document.querySelectorAll('.post-tab-btn');
        postTabBtns.forEach(btn => {
            if (btn.dataset.type === type) {
                btn.classList.add('border-[#910039]', 'text-[#910039]');
                btn.classList.remove('border-transparent', 'text-gray-500');
            } else {
                btn.classList.remove('border-[#910039]', 'text-[#910039]');
                btn.classList.add('border-transparent', 'text-gray-500');
            }
        });
        
        // Filtrar imagens pelo tipo selecionado
        const images = this.campaignData.postsByType[type] || [];
        console.log('MarketingDetail: Imagens filtradas para tipo', type, ':', images);
        
        if (images.length > 0) {
            // Atualizar carousel com novas imagens
            this.updatePostCarousel(images);
            
            // Recriar galeria se o componente estiver disponível
            if (window.ProductGallery) {
                // Limpar indicadores anteriores
                const indicators = document.getElementById('indicators');
                if (indicators) {
                    indicators.innerHTML = '';
                }
                
                // Criar nova galeria apenas com as imagens do tipo selecionado
                this.currentPostGallery = new window.ProductGallery('productGallery', images);
            }
        } else {
            console.log('MarketingDetail: Nenhuma imagem encontrada para o tipo:', type);
            // Limpar carousel se não houver imagens
            this.clearPostCarousel();
            
            // Limpar indicadores
            const indicators = document.getElementById('indicators');
            if (indicators) {
                indicators.innerHTML = '';
            }
        }
    }
    
    updatePostCarousel(images) {
        const leftImage = document.getElementById('leftImage');
        const centerImage = document.getElementById('centerImage');
        const rightImage = document.getElementById('rightImage');
        
        if (leftImage && centerImage && rightImage && images.length > 0) {
            // Atualizar imagem esquerda
            leftImage.innerHTML = `<img src="${images[0].src}" alt="${images[0].alt}" class="w-full h-auto object-cover">`;
            
            // Atualizar imagem central
            if (images.length > 1) {
                centerImage.innerHTML = `<img src="${images[1].src}" alt="${images[1].alt}" class="w-full h-auto object-cover">`;
            } else {
                centerImage.innerHTML = `<img src="${images[0].src}" alt="${images[0].alt}" class="w-full h-auto object-cover">`;
            }
            
            // Atualizar imagem direita
            if (images.length > 2) {
                rightImage.innerHTML = `<img src="${images[2].src}" alt="${images[2].alt}" class="w-full h-auto object-cover">`;
            } else if (images.length > 1) {
                rightImage.innerHTML = `<img src="${images[1].src}" alt="${images[1].alt}" class="w-full h-auto object-cover">`;
            } else {
                rightImage.innerHTML = `<img src="${images[0].src}" alt="${images[0].alt}" class="w-full h-auto object-cover">`;
            }
        }
    }
    
    clearPostCarousel() {
        const leftImage = document.getElementById('leftImage');
        const centerImage = document.getElementById('centerImage');
        const rightImage = document.getElementById('rightImage');
        
        if (leftImage && centerImage && rightImage) {
            leftImage.innerHTML = '<div class="w-full h-48 bg-gray-200 flex items-center justify-center"><p class="text-gray-500">Nenhuma imagem</p></div>';
            centerImage.innerHTML = '<div class="w-full h-48 bg-gray-200 flex items-center justify-center"><p class="text-gray-500">Nenhuma imagem</p></div>';
            rightImage.innerHTML = '<div class="w-full h-48 bg-gray-200 flex items-center justify-center"><p class="text-gray-500">Nenhuma imagem</p></div>';
        }
    }
    
    switchVideoTab(type) {
        console.log('MarketingDetail: Trocando tab de vídeos para:', type);
        
        // Atualizar estado das tabs
        const videoTabBtns = document.querySelectorAll('.video-tab-btn');
        videoTabBtns.forEach(btn => {
            if (btn.dataset.type === type) {
                btn.classList.add('border-[#910039]', 'text-[#910039]');
                btn.classList.remove('border-transparent', 'text-gray-500');
            } else {
                btn.classList.remove('border-[#910039]', 'text-[#910039]');
                btn.classList.add('border-transparent', 'text-gray-500');
            }
        });
        
        // Filtrar vídeos pelo tipo selecionado
        const videos = this.campaignData.videosByType[type] || [];
        console.log('MarketingDetail: Vídeos filtrados para tipo', type, ':', videos);
        
        if (videos.length > 0) {
            // Atualizar player principal
            this.updateMainVideo(videos[0]);
            
            // Atualizar lista de vídeos
            this.updateVideoList(videos);
            
            // Recriar player se o componente estiver disponível
            if (window.ProductVideoPlayer) {
                // Limpar estado visual anterior
                const videoItems = document.querySelectorAll('.video-item');
                videoItems.forEach(item => {
                    item.classList.remove('border-l-4', 'border-l-[#910039]');
                    item.classList.add('border-gray-200');
                });
                
                // Criar novo player apenas com os vídeos do tipo selecionado
                this.currentVideoPlayer = new window.ProductVideoPlayer(videos);
            }
        } else {
            console.log('MarketingDetail: Nenhum vídeo encontrado para o tipo:', type);
            // Limpar player se não houver vídeos
            this.clearMainVideo();
            
            // Limpar lista de vídeos
            this.clearVideoList();
        }
    }
    
    updateMainVideo(video) {
        const mainVideo = document.getElementById('mainVideo');
        if (mainVideo && video.video_url) {
            const source = mainVideo.querySelector('source');
            if (source) {
                source.src = video.video_url;
                mainVideo.load(); // Recarregar o vídeo
            }
        }
    }
    
    updateVideoList(videos) {
        const videoListContainer = document.getElementById('videoListContainer');
        if (videoListContainer) {
            // Limpar lista atual
            videoListContainer.innerHTML = '';
            
            // Adicionar novos vídeos
            videos.forEach((video, index) => {
                const videoItem = document.createElement('div');
                videoItem.className = `video-item bg-white p-4 cursor-pointer hover:bg-gray-50 transition border-t ${index === videos.length - 1 ? 'border-b' : ''} border-gray-200`;
                videoItem.setAttribute('data-video', video.id);
                videoItem.setAttribute('data-title', video.name);
                videoItem.setAttribute('data-type', video.type);
                
                videoItem.innerHTML = `
                    <div class="flex gap-3">
                        <div class="w-20 h-12 bg-gray-300 rounded overflow-hidden flex-shrink-0">
                            ${video.thumbnail ? 
                                `<img src="${video.thumbnail}" alt="Thumbnail" class="w-full h-full object-cover">` :
                                `<div class="w-full h-12 bg-gray-400 flex items-center justify-center"><i class="fas fa-video text-gray-600"></i></div>`
                            }
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-[#910039] font-semibold text-sm mb-1">${video.name}</h4>
                            <div class="flex items-center justify-between">
                                <span class="text-gray-600 text-xs">${video.type.charAt(0).toUpperCase() + video.type.slice(1)}</span>
                                <button class="text-[#910039] text-xs hover:underline">
                                    <i class="fas fa-download mr-1"></i>Download
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                
                videoListContainer.appendChild(videoItem);
            });
            
            // Adicionar contador de vídeos
            const videoCount = document.createElement('div');
            videoCount.className = 'mt-6';
            videoCount.innerHTML = `
                <p class="text-[#910039] text-sm font-medium text-center">
                    ${videos.length} Vídeo${videos.length > 1 ? 's' : ''} disponíve${videos.length > 1 ? 'is' : 'l'}
                </p>
            `;
            videoListContainer.appendChild(videoCount);
        }
    }
    
    clearMainVideo() {
        const mainVideo = document.getElementById('mainVideo');
        if (mainVideo) {
            mainVideo.innerHTML = '<div class="w-full h-96 bg-gray-800 flex items-center justify-center"><p class="text-white">Nenhum vídeo disponível para este tipo</p></div>';
        }
    }
    
    clearVideoList() {
        const videoListContainer = document.getElementById('videoListContainer');
        if (videoListContainer) {
            videoListContainer.innerHTML = '<p class="text-gray-500 text-center py-8">Nenhum vídeo disponível para este tipo</p>';
        }
    }
}

// Exportar para uso global
window.MarketingDetail = MarketingDetail;
