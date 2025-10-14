/**
 * Training Video Player Component
 * Gerencia o player de vídeos dos treinamentos
 */
class TrainingVideoPlayer {
    constructor(videos = []) {
        console.log('TrainingVideoPlayer: Inicializando com', videos.length, 'vídeos');
        this.videos = videos;
        this.currentVideoIndex = 0;
        
        this.mainVideo = document.getElementById('mainVideo');
        this.videoItems = document.querySelectorAll('.video-item');
        
        console.log('TrainingVideoPlayer: Elementos encontrados:', {
            mainVideo: !!this.mainVideo,
            videoItems: this.videoItems.length
        });
        
        this.init();
    }
    
    init() {
        if (this.videos.length === 0) {
            console.log('TrainingVideoPlayer: Nenhum vídeo disponível');
            return;
        }
        
        this.setupVideoList();
        this.setActiveVideo(0);
        console.log('TrainingVideoPlayer: Inicialização concluída');
    }
    
    setupVideoList() {
        console.log('TrainingVideoPlayer: Configurando lista de vídeos');
        this.videoItems.forEach((item, index) => {
            console.log(`TrainingVideoPlayer: Adicionando listener para item ${index}`);
            item.addEventListener('click', (e) => {
                e.preventDefault();
                console.log(`TrainingVideoPlayer: Clique no item ${index}`);
                this.setActiveVideo(index);
            });
        });
    }
    
    setActiveVideo(index) {
        console.log(`TrainingVideoPlayer: Definindo vídeo ativo ${index}`);
        if (index < 0 || index >= this.videos.length) {
            console.log('TrainingVideoPlayer: Índice inválido');
            return;
        }
        
        this.currentVideoIndex = index;
        const video = this.videos[index];
        console.log('TrainingVideoPlayer: Vídeo selecionado:', video);
        
        // Atualizar vídeo principal
        if (this.mainVideo) {
            const source = this.mainVideo.querySelector('source');
            if (source) {
                source.src = video.video_url;
                this.mainVideo.load(); // Recarregar o vídeo
                console.log('TrainingVideoPlayer: Vídeo principal atualizado para:', video.video_url);
            }
        }
        
        // Atualizar estado visual dos itens
        this.videoItems.forEach((item, i) => {
            // Remover todas as classes de borda primeiro
            item.classList.remove('border-l-4', 'border-l-[#910039]');
            
            if (i === index) {
                // Item ativo
                item.classList.add('border-l-4');
                console.log(`TrainingVideoPlayer: Item ${i} marcado como ativo`);
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
window.TrainingVideoPlayer = TrainingVideoPlayer;
