/**
 * Product Video Player Component
 * Gerencia o player de vídeos dos produtos
 */
class ProductVideoPlayer {
    constructor(videos = []) {
        console.log('ProductVideoPlayer: Inicializando com', videos.length, 'vídeos');
        this.videos = videos;
        this.currentVideoIndex = 0;
        
        this.mainVideo = document.getElementById('mainVideo');
        this.videoContainer = this.mainVideo ? (this.mainVideo.closest('.bg-gray-800') || this.mainVideo.parentElement) : null;
        this.videoItems = document.querySelectorAll('.video-item');
        
        console.log('ProductVideoPlayer: Elementos encontrados:', {
            mainVideo: !!this.mainVideo,
            videoContainer: !!this.videoContainer,
            videoItems: this.videoItems.length
        });
        
        this.init();
    }
    
    init() {
        if (this.videos.length === 0) {
            console.log('ProductVideoPlayer: Nenhum vídeo disponível');
            return;
        }
        
        this.setupVideoList();
        this.setActiveVideo(0);
        console.log('ProductVideoPlayer: Inicialização concluída');
    }
    
    setupVideoList() {
        console.log('ProductVideoPlayer: Configurando lista de vídeos');
        this.videoItems.forEach((item, index) => {
            console.log(`ProductVideoPlayer: Adicionando listener para item ${index}`);
            item.addEventListener('click', (e) => {
                // Não interceptar download (form) nem links — senão o submit/default é bloco
                if (e.target.closest('form') || e.target.closest('a[href]')) {
                    return;
                }
                e.preventDefault();
                console.log(`ProductVideoPlayer: Clique no item ${index}`);
                this.setActiveVideo(index);
            });
        });
    }
    
    setActiveVideo(index) {
        console.log(`ProductVideoPlayer: Definindo vídeo ativo ${index}`);
        if (index < 0 || index >= this.videos.length) {
            console.log('ProductVideoPlayer: Índice inválido');
            return;
        }
        
        this.currentVideoIndex = index;
        const video = this.videos[index];
        console.log('ProductVideoPlayer: Vídeo selecionado:', video);
        
        // Atualizar vídeo principal
        if (this.videoContainer) {
            if (video.video_source === 'url' && video.embed_url) {
                this.videoContainer.innerHTML = `
                    <div class="relative w-full" style="padding-top: 56.25%;" id="mainVideoWrapper">
                        <iframe id="mainVideo"
                                class="absolute inset-0 w-full h-full"
                                src="${video.embed_url}"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                        </iframe>
                    </div>
                `;
                console.log('ProductVideoPlayer: Vídeo principal atualizado para embed:', video.embed_url);
            } else if (video.video_url) {
                const cleanFileName = video.file_name ? decodeURIComponent(decodeURIComponent(video.file_name)) : '';
                this.videoContainer.innerHTML = `
                    <video id="mainVideo" class="w-full h-96" controls>
                        <source src="${video.video_url}" type="video/mp4">
                        Seu navegador não suporta o elemento de vídeo.
                    </video>
                    ${cleanFileName ? `<p class="text-gray-400 text-xs mt-2 px-2 pb-2 truncate" title="${cleanFileName}">Ficheiro: ${cleanFileName}</p>` : ''}
                `;
                console.log('ProductVideoPlayer: Vídeo principal atualizado para HTML5:', video.video_url);
            } else {
                this.videoContainer.innerHTML = `
                    <div class="w-full h-96 bg-gray-800 flex items-center justify-center">
                        <p class="text-white">Nenhum vídeo disponível</p>
                    </div>
                `;
            }
            this.mainVideo = document.getElementById('mainVideo');
        }
        
        // Atualizar estado visual dos itens
        this.videoItems.forEach((item, i) => {
            // Remover todas as classes de borda primeiro
            item.classList.remove('border-l-4', 'border-l-[#910039]');
            
            if (i === index) {
                // Item ativo
                item.classList.add('border-l-4', 'border-l-[#910039]');
                console.log(`ProductVideoPlayer: Item ${i} marcado como ativo`);
            } else {
                // Item inativo
                item.classList.add('border-gray-200');
            }
        });
    }
    
    getCurrentVideo() {
        return this.videos[this.currentVideoIndex];
    }
}

// Exportar para uso global
window.ProductVideoPlayer = ProductVideoPlayer;
