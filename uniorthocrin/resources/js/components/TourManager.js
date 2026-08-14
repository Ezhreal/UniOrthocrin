import { driver } from 'driver.js';
import 'driver.js/dist/driver.css';

/**
 * TourManager Component
 * Gerencia os tours guiados interativos (onboarding) em todo o sistema.
 */
class TourManager {
    constructor() {
        this.driverObj = null;
        this.tours = {
            // === CLIENT SIDE TOURS ===
            'client_dashboard': {
                storageKey: 'uniorthocrin_tour_client_dash',
                steps: [
                    {
                        popover: {
                            title: '👋 Bem-vindo à UniOrthocrin!',
                            description: 'Este é o seu portal de suporte. Preparamos este tour rápido para te mostrar onde encontrar e baixar tudo o que precisa.',
                            position: 'center'
                        }
                    },
                    {
                        element: '#tour-notifications',
                        popover: {
                            title: '🔔 Alertas e Notificações',
                            description: 'Fique atento ao sino! Avisos importantes e comunicados da fábrica para sua loja aparecem aqui.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-navigation',
                        popover: {
                            title: '📂 Seções de Materiais',
                            description: 'Navegue pelos módulos: Marketing (peças prontas), Produtos (fotos de alta definição) ou Biblioteca (manuais).',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-profile-menu',
                        popover: {
                            title: '👤 Seu Perfil & Lojas',
                            description: 'Gerencia mais de uma filial ou marca? Clique aqui para alternar de perfil ou editar sua conta.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#btn-trigger-help-tour',
                        popover: {
                            title: '❓ Como Usar esta tela',
                            description: 'Ficou com alguma dúvida no futuro? Basta clicar neste botão "Como Usar?" para ver este tutorial novamente.',
                            position: 'bottom'
                        }
                    }
                ]
            },
            'client_product_detail': {
                storageKey: 'uniorthocrin_tour_client_prod_detail',
                steps: [
                    {
                        element: '#tour-item-details-card',
                        popover: {
                            title: '📋 Detalhes do Produto',
                            description: 'Aqui você encontra a descrição técnica, data de publicação, quantidade de mídias e o tamanho total dos arquivos para download.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#carouselContainer',
                        popover: {
                            title: '📸 Galeria de Imagens',
                            description: 'Navegue pelo carrossel para ver imagens de alta definição do produto. Use as setas laterais para trocar as fotos.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '.btn-download-zip',
                        popover: {
                            title: '📦 Baixar Fotos do Produto',
                            description: 'Clique aqui para fazer o download de todas as imagens da galeria de uma vez, compactadas em um arquivo ZIP.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-video-player',
                        popover: {
                            title: '🎥 Vídeos do Produto',
                            description: 'Assista aos vídeos e reels de divulgação deste produto pelo reprodutor integrado.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-video-playlist',
                        popover: {
                            title: '📑 Lista de Vídeos e Reels',
                            description: 'Use este menu lateral para selecionar e alternar entre os diferentes vídeos e reels disponíveis para este produto.',
                            position: 'left'
                        }
                    },
                    {
                        element: '.btn-video-download-single',
                        popover: {
                            title: '⬇️ Download do Vídeo',
                            description: 'Baixe um vídeo ou reel individualmente clicando no botão Download na linha correspondente da playlist.',
                            position: 'top'
                        }
                    },
                    {
                        element: '#tour-video-download-all',
                        popover: {
                            title: '📦 Baixar Todos os Vídeos (.zip)',
                            description: 'Baixe todos os vídeos e reels deste produto em um único pacote compactado.',
                            position: 'top'
                        }
                    }
                ]
            },
            'client_training_detail': {
                storageKey: 'uniorthocrin_tour_client_train_detail',
                steps: [
                    {
                        element: '#tour-item-details-card',
                        popover: {
                            title: '📋 Detalhes do Treinamento',
                            description: 'Consulte os objetivos do curso, data de publicação, quantidade de materiais e tamanho total dos arquivos para download.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#videoPlayerContainer',
                        popover: {
                            title: '🎥 Videoaula Online',
                            description: 'Assista às aulas diretamente pelo portal. Os vídeos dos cursos são exclusivos para visualização online.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-video-playlist',
                        popover: {
                            title: '📑 Playlist de Aulas',
                            description: 'Selecione a próxima aula na lista lateral para avançar nos módulos do treinamento.',
                            position: 'left'
                        }
                    },
                    {
                        element: '#tour-training-attachments',
                        popover: {
                            title: '📘 Material de Apoio (PDFs)',
                            description: 'Baixe as apostilas, apresentações e documentos complementares em PDF para acompanhar o seu aprendizado.',
                            position: 'top'
                        }
                    }
                ]
            },
            'client_marketing_detail': {
                storageKey: 'uniorthocrin_tour_client_mkt_detail',
                steps: [
                    {
                        element: '#tour-item-details-card',
                        popover: {
                            title: '📋 Dados Gerais da Campanha',
                            description: 'Aqui você confere o período de vigência da campanha, status, quantidade total de peças publicitárias e tamanho dos downloads.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#regional-brochures-box',
                        popover: {
                            title: '🗺️ Folhetos Regionais',
                            description: 'Atenção: Os folhetos estão divididos por região geográfica (MG/SP ou DF/ES). Certifique-se de baixar a versão correta da sua praça.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#marketing-campaign-download-btn',
                        popover: {
                            title: '📦 Baixar Campanha Inteira',
                            description: 'Use este botão para baixar o pacote de mídia completo da campanha (feed, stories e vídeos promocionais) em um único arquivo .zip.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '.marketing-posts-grid',
                        popover: {
                            title: '📱 Posts Individuais',
                            description: 'Selecione imagens individuais para suas redes sociais. Cada imagem vem com sugestões de textos prontos para copiar.',
                            position: 'top'
                        }
                    },
                    {
                        element: '.btn-video-download-single',
                        popover: {
                            title: '⬇️ Download do Vídeo da Campanha',
                            description: 'Baixe um vídeo ou comercial específico da campanha diretamente pelo botão de Download.',
                            position: 'top'
                        }
                    },
                    {
                        element: '#tour-video-download-all',
                        popover: {
                            title: '📦 Baixar Todos os Vídeos (.zip)',
                            description: 'Baixe todos os vídeos promocionais desta categoria da campanha em um arquivo .zip.',
                            position: 'top'
                        }
                    }
                ]
            },
            'client_library_detail': {
                storageKey: 'uniorthocrin_tour_client_lib_detail',
                steps: [
                    {
                        element: '#tour-item-details-card',
                        popover: {
                            title: '📋 Detalhes do Documento',
                            description: 'Aqui você confere a descrição, data de publicação, quantidade de arquivos e o tamanho total para download.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '.btn-download-all',
                        popover: {
                            title: '📂 Baixar Todos os Documentos (.zip)',
                            description: 'Gere um arquivo .zip com todas as circulares ou manuais corporativos deste item de uma só vez.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '.btn-download-single',
                        popover: {
                            title: '⬇️ Download Individual',
                            description: 'Se preferir, faça o download de um arquivo específico clicando no botão Download na linha correspondente.',
                            position: 'top'
                        }
                    }
                ]
            },
            'client_media_detail': {
                storageKey: 'uniorthocrin_tour_client_media_detail',
                steps: [
                    {
                        element: '#tour-item-details-card',
                        popover: {
                            title: '📋 Detalhes da Mídia',
                            description: 'Confira a descrição da matéria ou campanha, data de publicação e o tamanho total dos arquivos disponibilizados.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '.btn-download-all',
                        popover: {
                            title: '📂 Baixar Todos os Arquivos (.zip)',
                            description: 'Faça o download de todos os anexos e vídeos desta matéria de uma só vez.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '.btn-download-single',
                        popover: {
                            title: '⬇️ Download Individual',
                            description: 'Selecione e baixe individualmente qualquer foto, vídeo ou PDF listado.',
                            position: 'top'
                        }
                    }
                ]
            },

            // === ADMIN SIDE TOURS ===
            'admin_dashboard': {
                storageKey: 'uniorthocrin_tour_admin_dash',
                steps: [
                    {
                        popover: {
                            title: '👑 Painel do Administrador',
                            description: 'Este é o centro de controle da Universidade Orthocrin. Vamos conhecê-lo rapidamente?',
                            position: 'center'
                        }
                    },
                    {
                        element: '#admin-sidebar',
                        popover: {
                            title: '🛠️ Menu Administrativo',
                            description: 'Gerencie usuários, crie campanhas, produtos, notícias e visualize métricas por meio deste menu.',
                            position: 'right'
                        }
                    },
                    {
                        element: '#admin-quick-stats',
                        popover: {
                            title: '⚡ Indicadores Principais',
                            description: 'Acompanhe de forma consolidada o volume total de Campanhas ativas, Produtos cadastrados e Treinamentos publicados.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#admin-secondary-stats',
                        popover: {
                            title: '📊 Outras Métricas',
                            description: 'Veja um resumo rápido da quantidade de arquivos na Biblioteca, notícias do Radar, inserções Na Mídia e usuários cadastrados.',
                            position: 'top'
                        }
                    },
                    {
                        element: '#admin-recent-activity',
                        popover: {
                            title: '🕒 Atividades Recentes',
                            description: 'Acompanhe os novos usuários registrados recentemente e a lista com o horário dos últimos acessos à plataforma.',
                            position: 'top'
                        }
                    },
                    {
                        element: '#admin-upload-manager-btn',
                        popover: {
                            title: '🔄 Monitor de Uploads',
                            description: 'Acompanhe a integridade e andamento de todas as transmissões de arquivos em blocos executadas em tempo real.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#admin-bell-notifications',
                        popover: {
                            title: '📢 Central de Alertas',
                            description: 'Precisa enviar um comunicado importante? Clique aqui para criar e gerenciar notificações enviadas para os usuários.',
                            position: 'bottom'
                        }
                    }
                ]
            },
            'admin_users_form': {
                storageKey: 'uniorthocrin_tour_admin_users',
                steps: [
                    {
                        element: '#primary_user_type_id',
                        popover: {
                            title: '👤 Tipo de Usuário *',
                            description: 'Defina primeiro o nível do usuário. Admins acessam o painel; Franqueados veem Marketing; Lojistas/Representantes têm acessos restritos.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#name',
                        popover: {
                            title: '📝 Nome Completo *',
                            description: 'Informe o nome do usuário que será exibido no topo da página e relatórios.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#email',
                        popover: {
                            title: '📧 E-mail de Login *',
                            description: 'Preencha o e-mail que servirá como chave de acesso único do usuário.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#company-info-card',
                        popover: {
                            title: '🏢 Dados da Empresa',
                            description: 'Para Lojistas, Franqueados e Representantes, este bloco opcional serve para registrar o CNPJ e Razão Social correspondente.',
                            position: 'top'
                        }
                    },
                    {
                        element: '#status',
                        popover: {
                            title: '🟢 Status do Acesso *',
                            description: 'Mantenha como "Ativo" para permitir o login, ou inative o cadastro para bloquear a conta.',
                            position: 'top'
                        }
                    },
                    {
                        element: '#tour-submit-btn',
                        popover: {
                            title: '💾 Salvar Cadastro',
                            description: 'Clique aqui para concluir o cadastro ou atualizar as credenciais do usuário.',
                            position: 'top'
                        }
                    }
                ]
            },
            'admin_product_form': {
                storageKey: 'uniorthocrin_tour_admin_product',
                steps: [
                    {
                        element: '#thumbnail',
                        popover: {
                            title: '🖼️ Capa do Produto',
                            description: 'Selecione uma imagem de capa retangular (proporção 16:9) que identifique o produto.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#name',
                        popover: {
                            title: '📋 Nome do Produto *',
                            description: 'Preencha o nome comercial completo do produto.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#description',
                        popover: {
                            title: '📝 Descrição',
                            description: 'Informe os detalhes técnicos, dimensões e características do produto.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#product_category_id',
                        popover: {
                            title: '📂 Categoria *',
                            description: 'Selecione a categoria correspondente para ajudar nos filtros da busca.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#product_series_id',
                        popover: {
                            title: '🏷️ Série / Coleção',
                            description: 'Vincule o produto a uma série específica se fizer parte de uma linha de produtos.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#status',
                        popover: {
                            title: '🟢 Status da Publicação *',
                            description: 'Defina se o produto ficará ativo imediatamente (Publicado) ou oculto para ajustes (Rascunho).',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-gallery-upload-area',
                        popover: {
                            title: '📤 Galeria de Imagens',
                            description: 'Arraste ou selecione imagens adicionais do produto. Suporta uploads múltiplos de arquivos de alta definição.',
                            position: 'top'
                        }
                    },
                    {
                        element: '#tour-videos-upload-area',
                        popover: {
                            title: '🎥 Galeria de Vídeos',
                            description: 'Aqui você pode adicionar múltiplos vídeos do produto. Temos suporte para duas formas de anexo.',
                            position: 'top'
                        }
                    },
                    {
                        element: 'label.vss-radio-label[data-mode="upload"][data-target^="vss_gallery_videos_"]',
                        popover: {
                            title: '📤 Opção 1: Enviar Arquivo',
                            description: 'Selecione esta opção para arrastar e fazer o upload do arquivo de vídeo local direto para nosso servidor.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: 'label.vss-radio-label[data-mode="url"][data-target^="vss_gallery_videos_"]',
                        popover: {
                            title: '🔗 Opção 2: Link de Vídeo Externo',
                            description: 'Se preferir, selecione esta opção para colar uma URL do YouTube ou Vimeo. O sistema criará o embed automaticamente.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-permissions-card',
                        popover: {
                            title: '🔒 Permissões de Acesso *',
                            description: 'Defina quais perfis (Franqueados, Lojistas, Representantes) podem ver e quais podem baixar este produto.',
                            position: 'top'
                        }
                    },
                    {
                        element: '#tour-submit-btn',
                        popover: {
                            title: '💾 Salvar Produto',
                            description: 'Tudo pronto? Clique aqui para salvar e publicar este produto no portal.',
                            position: 'top'
                        }
                    }
                ]
            },
            'admin_library_form': {
                storageKey: 'uniorthocrin_tour_admin_library',
                steps: [
                    {
                        element: '#thumbnail',
                        popover: {
                            title: '🖼️ Capa do Item',
                            description: 'Selecione uma imagem de destaque para representar este documento na listagem.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#name',
                        popover: {
                            title: '📋 Nome do Item *',
                            description: 'Informe o título do manual, circular ou documento.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#library_category_id',
                        popover: {
                            title: '📂 Categoria *',
                            description: 'Vincule a uma categoria para organização interna da biblioteca.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#description',
                        popover: {
                            title: '📝 Descrição',
                            description: 'Uma breve descrição sobre o conteúdo ou finalidade deste item.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#status',
                        popover: {
                            title: '🟢 Status *',
                            description: 'Selecione se o item está ativo para visualização (Publicado) ou se é um rascunho.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-files-upload-area',
                        popover: {
                            title: '📤 Galeria de Arquivos',
                            description: 'Arraste ou selecione os arquivos/PDFs correspondentes para download.',
                            position: 'top'
                        }
                    },
                    {
                        element: '#tour-permissions-card',
                        popover: {
                            title: '🔒 Controle de Acesso *',
                            description: 'Marque quem tem permissão para visualizar e realizar downloads destes documentos.',
                            position: 'top'
                        }
                    },
                    {
                        element: '#tour-submit-btn',
                        popover: {
                            title: '💾 Salvar Item',
                            description: 'Tudo preenchido? Clique aqui para salvar e armazenar o documento na biblioteca.',
                            position: 'top'
                        }
                    }
                ]
            },
            'admin_media_form': {
                storageKey: 'uniorthocrin_tour_admin_media',
                steps: [
                    {
                        element: '#thumbnail',
                        popover: {
                            title: '🖼️ Capa / Miniatura',
                            description: 'Selecione uma imagem de capa para identificar o item na seção Na Mídia.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#name',
                        popover: {
                            title: '📋 Nome do Item *',
                            description: 'Escreva o título da matéria, vídeo de propaganda ou menção na mídia.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#media_category_id',
                        popover: {
                            title: '📂 Categoria *',
                            description: 'Selecione o tipo de mídia para fins de classificação.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#description',
                        popover: {
                            title: '📝 Descrição',
                            description: 'Detalhes adicionais sobre a inserção da marca na mídia.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#status',
                        popover: {
                            title: '🟢 Status *',
                            description: 'Defina se a mídia está visível (Publicado) ou oculta.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-files-upload-area',
                        popover: {
                            title: '📤 Galeria de Arquivos (Múltiplos Formatos)',
                            description: 'Esta área aceita diversos tipos de arquivos: vídeos (.mp4, .mov), imagens (.png, .jpg), documentos (.pdf, .txt, .docx) ou arquivos compactados (.zip).',
                            position: 'top'
                        }
                    },
                    {
                        element: '#tour-permissions-card',
                        popover: {
                            title: '🔒 Permissões *',
                            description: 'Controle de visualização e download do material da mídia por perfil.',
                            position: 'top'
                        }
                    },
                    {
                        element: '#tour-submit-btn',
                        popover: {
                            title: '💾 Salvar Mídia',
                            description: 'Tudo preenchido? Clique aqui para salvar as alterações.',
                            position: 'top'
                        }
                    }
                ]
            },
            'admin_training_form': {
                storageKey: 'uniorthocrin_tour_admin_training',
                steps: [
                    {
                        element: '#thumbnail',
                        popover: {
                            title: '🖼️ Capa do Treinamento',
                            description: 'Selecione uma capa ilustrativa para o treinamento.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#name',
                        popover: {
                            title: '📋 Nome do Treinamento *',
                            description: 'Preencha o título do treinamento corporativo.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#training_category_id',
                        popover: {
                            title: '📂 Categoria *',
                            description: 'Escolha a categoria (ex: Vendas, Técnico) correspondente.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#description',
                        popover: {
                            title: '📝 Descrição',
                            description: 'Descreva a ementa, os objetivos e o público-alvo do treinamento.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#status',
                        popover: {
                            title: '🟢 Status *',
                            description: 'Defina a visibilidade do treinamento (Publicado ou Rascunho).',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-videos-upload-area',
                        popover: {
                            title: '🎥 Galeria de Vídeos',
                            description: 'Adicione arquivos de vídeo ou links externos (YouTube/Vimeo) com suporte a exibição em player local.',
                            position: 'top'
                        }
                    },
                    {
                        element: 'label.vss-radio-label[data-mode="upload"][data-target^="vss_videos_"]',
                        popover: {
                            title: '📤 Opção 1: Enviar Arquivo de Treinamento',
                            description: 'Selecione esta opção para fazer o upload de um arquivo de vídeo corporativo direto para o servidor.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: 'label.vss-radio-label[data-mode="url"][data-target^="vss_videos_"]',
                        popover: {
                            title: '🔗 Opção 2: Vídeo Externo',
                            description: 'Selecione esta opção para colar o endereço de um vídeo no YouTube ou Vimeo que os usuários assistirão via player integrado.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-files-upload-area',
                        popover: {
                            title: '📤 PDFs Complementares',
                            description: 'Suba guias, manuais e materiais de apoio em PDF para download.',
                            position: 'top'
                        }
                    },
                    {
                        element: '#tour-permissions-card',
                        popover: {
                            title: '🔒 Restrição de Acesso *',
                            description: 'Escolha quais perfis de usuários poderão assistir e baixar os materiais deste treinamento.',
                            position: 'top'
                        }
                    },
                    {
                        element: '#tour-submit-btn',
                        popover: {
                            title: '💾 Salvar Treinamento',
                            description: 'Clique aqui para registrar e publicar este treinamento no sistema.',
                            position: 'top'
                        }
                    }
                ]
            },
            'admin_news_form': {
                storageKey: 'uniorthocrin_tour_admin_news',
                steps: [
                    {
                        element: '#title',
                        popover: {
                            title: '📋 Título da Notícia *',
                            description: 'Defina o título principal do informativo ou notícia no Radar.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#news_category_id',
                        popover: {
                            title: '📂 Categoria *',
                            description: 'Indique a categoria da notícia para classificação no mural.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#status',
                        popover: {
                            title: '🟢 Status *',
                            description: 'Selecione se a notícia deve ser publicada imediatamente ou guardada em rascunho.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-image-upload-area',
                        popover: {
                            title: '🖼️ Imagem de Destaque',
                            description: 'Faça upload da imagem retangular que ilustrará o corpo da notícia.',
                            position: 'top'
                        }
                    },
                    {
                        element: '#tour-permissions-card',
                        popover: {
                            title: '🔒 Nível de Permissões *',
                            description: 'Defina as regras de leitura da notícia de acordo com o perfil logado.',
                            position: 'top'
                        }
                    },
                    {
                        element: '#tour-submit-btn',
                        popover: {
                            title: '💾 Salvar Notícia',
                            description: 'Tudo pronto? Clique aqui para salvar a notícia no mural do Radar.',
                            position: 'top'
                        }
                    }
                ]
            },
            'admin_upload_manager': {
                storageKey: 'uniorthocrin_tour_admin_uploads',
                steps: [
                    {
                        element: '#active-uploads-panel',
                        popover: {
                            title: '⚡ Transmissões Ativas',
                            description: 'Monitore os arquivos que estão subindo por fatias neste exato momento de forma dinâmica.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#table-db-uploads',
                        popover: {
                            title: '💾 Arquivos Registrados no Banco',
                            description: 'Acompanhe o progresso de junção de blocos ("merging") e o histórico consolidado de envios.',
                            position: 'top'
                        }
                    },
                    {
                        element: '.badge-status-upload',
                        popover: {
                            title: '🟢 Status do Upload',
                            description: 'Indica se a transmissão está em processamento, pendente, concluída ou falhou.',
                            position: 'top'
                        }
                    },
                    {
                        element: '.btn-retry-upload',
                        popover: {
                            title: '🔄 Retentar Envios',
                            description: 'Caso alguma transmissão falhe por perda de conexão, use este botão para reiniciar o envio a partir da última fatia concluída.',
                            position: 'top'
                        }
                    }
                ]
            },
            'admin_notifications_form': {
                storageKey: 'uniorthocrin_tour_admin_notifications',
                steps: [
                    {
                        element: '#title',
                        popover: {
                            title: '✉️ Título do Alerta *',
                            description: 'Um resumo curto do aviso que aparecerá em destaque no sino.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#message',
                        popover: {
                            title: '📝 Mensagem Completa *',
                            description: 'Descreva detalhadamente o comunicado que o usuário receberá.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#type',
                        popover: {
                            title: '🎨 Tipo e Cor do Ícone *',
                            description: 'Selecione Info, Success, Warning ou Error para ajustar o tom visual da mensagem.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#audience_selection',
                        popover: {
                            title: '🎯 Segmentar Público *',
                            description: 'Escolha enviar para todos, filtrar por tipos de perfis específicos ou selecionar usuários manualmente.',
                            position: 'top'
                        }
                    },
                    {
                        element: '#tour-submit-btn',
                        popover: {
                            title: '💾 Enviar Comunicado',
                            description: 'Clique aqui para despachar o aviso para todos os usuários selecionados.',
                            position: 'top'
                        }
                    }
                ]
            },
            'admin_campaigns_form': {
                storageKey: 'uniorthocrin_tour_admin_campaigns',
                steps: [
                    {
                        element: '#name',
                        popover: {
                            title: '📋 Nome da Campanha *',
                            description: 'Dê um nome representativo para a campanha de Marketing (ex: Black Friday, Liquidação de Inverno).',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#description',
                        popover: {
                            title: '📝 Descrição da Campanha',
                            description: 'Explique a mecânica promocional, metas ou orientações para a equipe de vendas.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#thumbnail',
                        popover: {
                            title: '🖼️ Capa da Campanha',
                            description: 'Selecione uma imagem representativa para ilustrar o card da campanha na listagem.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#is_featured',
                        popover: {
                            title: '⭐ Destacar na Home',
                            description: 'Marque esta opção para exibir a campanha no banner rotativo principal da home dos franqueados.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#banner_desktop',
                        popover: {
                            title: '🖥️ Banner Desktop (Destaque)',
                            description: 'Envie a arte horizontal larga otimizada para visualização em computadores.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#banner_mobile',
                        popover: {
                            title: '📱 Banner Mobile (Destaque)',
                            description: 'Envie a imagem quadrada/vertical ideal para smartphones e tablets.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#start_date',
                        popover: {
                            title: '📅 Início de Vigência',
                            description: 'Data de início da publicação automática do banner destacado na home.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#end_date',
                        popover: {
                            title: '📅 Fim de Vigência',
                            description: 'Data de encerramento da exibição do banner na página inicial.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-folhetos-card',
                        popover: {
                            title: '📄 Encartes e Folhetos',
                            description: 'Anexe folhetos específicos por região (ex: Minas Gerais/São Paulo ou Distrito Federal/Espírito Santo) para os lojistas baixarem.',
                            position: 'top'
                        }
                    },
                    {
                        element: '#tour-posts-card',
                        popover: {
                            title: '📸 Posts e Stories para Redes Sociais',
                            description: 'Seção dedicada para subir mídias de divulgação prontas para Feed e Stories das lojas.',
                            position: 'top'
                        }
                    },
                    {
                        element: '#tour-videos-card',
                        popover: {
                            title: '🎥 Reels e Vídeos de Campanha',
                            description: 'Adicione clipes de divulgação (upload direto ou links como YouTube/Vimeo) para os franqueados usarem.',
                            position: 'top'
                        }
                    },
                    {
                        element: 'label.vss-radio-label[data-mode="upload"][data-target^="vss_videos_reels_"]',
                        popover: {
                            title: '📤 Reels/Stories: Enviar Arquivo',
                            description: 'Marque esta opção para enviar vídeos verticais nativos gravados para redes sociais.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: 'label.vss-radio-label[data-mode="url"][data-target^="vss_videos_reels_"]',
                        popover: {
                            title: '🔗 Reels/Stories: Link Externo',
                            description: 'Marque esta opção para informar o link de Reels/Stories publicados externamente.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: 'label.vss-radio-label[data-mode="upload"][data-target^="vss_videos_campaigns_"]',
                        popover: {
                            title: '📤 Vídeos Horizontais: Enviar Arquivo',
                            description: 'Selecione para fazer o upload da peça ou comercial completo widescreen da campanha.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: 'label.vss-radio-label[data-mode="url"][data-target^="vss_videos_campaigns_"]',
                        popover: {
                            title: '🔗 Vídeos Horizontais: Link Externo',
                            description: 'Selecione para colar a URL do comercial de campanha hospedado no YouTube ou Vimeo.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-diversos-card',
                        popover: {
                            title: '📦 Outros Materiais (PDV)',
                            description: 'Área para subir spots de rádio, tags de preço, adesivos de vitrine, banners, faixas e roteiros de vendas.',
                            position: 'top'
                        }
                    },
                    {
                        element: '#status',
                        popover: {
                            title: '🟢 Status da Campanha',
                            description: 'Defina como "Publicado" para tornar a campanha visível imediatamente aos lojistas ou "Rascunho" para guardar sem publicar.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-submit-btn',
                        popover: {
                            title: '💾 Salvar Campanha',
                            description: 'Clique aqui para registrar a campanha e disponibilizar todos os materiais de marketing associados de uma só vez.',
                            position: 'top'
                        }
                    }
                ]
            },
            'admin_products_list': {
                storageKey: 'uniorthocrin_tour_admin_products_list',
                steps: [
                    {
                        element: '#tour-add-new-btn',
                        popover: {
                            title: '➕ Cadastrar Novo Produto',
                            description: 'Clique aqui para abrir o formulário e cadastrar um novo produto na plataforma.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-filters-card',
                        popover: {
                            title: '🔍 Filtros de Busca',
                            description: 'Filtre os produtos por nome, categoria ou status para localizá-los rapidamente.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-items-table',
                        popover: {
                            title: '📋 Tabela de Itens',
                            description: 'Exibe a listagem completa dos produtos cadastrados, incluindo informações rápidas como categorias, número de arquivos anexados e status.',
                            position: 'top'
                        }
                    },
                    {
                        element: '.table-modern tbody tr:first-child td:last-child',
                        popover: {
                            title: '⚡ Ações Rápidas',
                            description: 'Use estes botões para visualizar os detalhes, editar ou excluir este produto do sistema.',
                            position: 'left'
                        }
                    }
                ]
            },
            'admin_library_list': {
                storageKey: 'uniorthocrin_tour_admin_library_list',
                steps: [
                    {
                        element: '#tour-add-new-btn',
                        popover: {
                            title: '➕ Cadastrar Item',
                            description: 'Clique aqui para cadastrar um novo manual, circular ou documento na biblioteca.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-filters-card',
                        popover: {
                            title: '🔍 Filtros de Busca',
                            description: 'Filtre os arquivos por nome, categoria ou status.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-items-table',
                        popover: {
                            title: '📋 Tabela de Documentos',
                            description: 'Lista de arquivos da biblioteca corporativa com status e data de criação.',
                            position: 'top'
                        }
                    },
                    {
                        element: '.table-modern tbody tr:first-child td:last-child',
                        popover: {
                            title: '⚡ Ações Rápidas',
                            description: 'Visualize, edite ou exclua o item correspondente.',
                            position: 'left'
                        }
                    }
                ]
            },
            'admin_media_list': {
                storageKey: 'uniorthocrin_tour_admin_media_list',
                steps: [
                    {
                        element: '#tour-add-new-btn',
                        popover: {
                            title: '➕ Cadastrar Novo Item',
                            description: 'Clique aqui para adicionar um clipe de na mídia.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-filters-card',
                        popover: {
                            title: '🔍 Filtros',
                            description: 'Filtre por termo de busca ou categorias de mídia.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-items-table',
                        popover: {
                            title: '📋 Tabela de Mídias',
                            description: 'Lista de inserções da marca na mídia.',
                            position: 'top'
                        }
                    },
                    {
                        element: '.table-modern tbody tr:first-child td:last-child',
                        popover: {
                            title: '⚡ Ações Rápidas',
                            description: 'Visualize, edite ou remova o item.',
                            position: 'left'
                        }
                    }
                ]
            },
            'admin_training_list': {
                storageKey: 'uniorthocrin_tour_admin_training_list',
                steps: [
                    {
                        element: '#tour-add-new-btn',
                        popover: {
                            title: '➕ Criar Novo Treinamento',
                            description: 'Abra a tela de criação de novos cursos ou treinamentos.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-filters-card',
                        popover: {
                            title: '🔍 Filtros',
                            description: 'Localize treinamentos por categoria ou status.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-items-table',
                        popover: {
                            title: '📋 Tabela de Treinamentos',
                            description: 'Lista de treinamentos com contagem de vídeos e anexos em PDF.',
                            position: 'top'
                        }
                    },
                    {
                        element: '.table-modern tbody tr:first-child td:last-child',
                        popover: {
                            title: '⚡ Ações Rápidas',
                            description: 'Gerencie detalhes, assista aos vídeos associados ou edite as informações.',
                            position: 'left'
                        }
                    }
                ]
            },
            'admin_news_list': {
                storageKey: 'uniorthocrin_tour_admin_news_list',
                steps: [
                    {
                        element: '#tour-add-new-btn',
                        popover: {
                            title: '➕ Nova Notícia',
                            description: 'Cadastre um novo comunicado ou notícia no painel Radar.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-filters-card',
                        popover: {
                            title: '🔍 Filtros',
                            description: 'Filtre as notícias cadastradas.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-items-table',
                        popover: {
                            title: '📋 Tabela de Notícias',
                            description: 'Lista de avisos e posts do Radar com indicação de status e data.',
                            position: 'top'
                        }
                    },
                    {
                        element: '.table-modern tbody tr:first-child td:last-child',
                        popover: {
                            title: '⚡ Ações Rápidas',
                            description: 'Abra os detalhes da notícia, edite o texto ou remova.',
                            position: 'left'
                        }
                    }
                ]
            },
            'admin_campaigns_list': {
                storageKey: 'uniorthocrin_tour_admin_campaigns_list',
                steps: [
                    {
                        element: '#tour-add-new-btn',
                        popover: {
                            title: '➕ Criar Nova Campanha',
                            description: 'Abra o formulário para criar uma campanha de Marketing com banners em destaque.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-filters-card',
                        popover: {
                            title: '🔍 Filtros',
                            description: 'Busque campanhas pelo nome.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-items-table',
                        popover: {
                            title: '📋 Tabela de Campanhas',
                            description: 'Lista de campanhas, indicando se estão em destaque e quais pastas internas possuem.',
                            position: 'top'
                        }
                    },
                    {
                        element: '.table-modern tbody tr:first-child td:last-child',
                        popover: {
                            title: '⚡ Ações Rápidas',
                            description: 'Visualize as subpastas da campanha, edite ou exclua.',
                            position: 'left'
                        }
                    }
                ]
            },
            'admin_users_list': {
                storageKey: 'uniorthocrin_tour_admin_users_list',
                steps: [
                    {
                        element: '#tour-add-new-btn',
                        popover: {
                            title: '➕ Cadastrar Usuário',
                            description: 'Clique aqui para cadastrar um novo administrador, lojista, franqueado ou representante.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-filters-card',
                        popover: {
                            title: '🔍 Filtros de Busca',
                            description: 'Busque usuários por nome, e-mail, perfil ou status da conta.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-items-table',
                        popover: {
                            title: '📋 Tabela de Usuários',
                            description: 'Lista de cadastros ativos e inativos com data do último acesso à Universidade Orthocrin.',
                            position: 'top'
                        }
                    },
                    {
                        element: '.table-modern tbody tr:first-child td:last-child',
                        popover: {
                            title: '⚡ Ações Rápidas',
                            description: 'Edite o perfil do usuário ou desative o acesso.',
                            position: 'left'
                        }
                    }
                ]
            },
            'admin_notifications_list': {
                storageKey: 'uniorthocrin_tour_admin_notifications_list',
                steps: [
                    {
                        element: '#tour-add-new-btn',
                        popover: {
                            title: '➕ Nova Notificação',
                            description: 'Clique aqui para criar e disparar um novo alerta para a base de usuários.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-filters-card',
                        popover: {
                            title: '🔍 Filtros',
                            description: 'Busque notificações passadas pelo título.',
                            position: 'bottom'
                        }
                    },
                    {
                        element: '#tour-items-table',
                        popover: {
                            title: '📋 Tabela de Envios',
                            description: 'Veja os comunicados disparados, tipos de alertas e o número de leituras consolidadas.',
                            position: 'top'
                        }
                    },
                    {
                        element: '.table-modern tbody tr:first-child td:last-child',
                        popover: {
                            title: '⚡ Ações Rápidas',
                            description: 'Visualize os detalhes do aviso ou remova-o do histórico.',
                            position: 'left'
                        }
                    }
                ]
            }
        };

        this.init();
    }

    getCurrentPageKey() {
        return document.body.getAttribute('data-tour-page');
    }

    showOnboardingModal(onConfirm, onCancel) {
        const modal = document.createElement('div');
        modal.id = 'tour-onboarding-modal';
        modal.className = 'fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-opacity duration-300 opacity-0';
        
        modal.innerHTML = `
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden transform scale-95 transition-transform duration-300 p-6 flex flex-col items-center text-center">
                <div class="h-16 w-16 bg-[#910039]/10 text-[#910039] rounded-2xl flex items-center justify-center mb-4 text-2xl">
                    <i class="fas fa-compass animate-bounce"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2 font-outfit">Guia de Uso Rápido</h3>
                <p class="text-gray-600 mb-6 text-sm font-outfit">Gostaria de fazer um tour interativo rápido para conhecer os principais recursos e campos desta tela?</p>
                <div class="flex gap-3 w-full">
                    <button id="btn-tour-no" class="flex-1 px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl text-sm transition duration-200 font-outfit">
                        Não, obrigado
                    </button>
                    <button id="btn-tour-yes" class="flex-1 px-4 py-2.5 bg-[#910039] hover:bg-[#7A0030] text-white font-semibold rounded-xl text-sm transition duration-200 font-outfit">
                        Sim, iniciar!
                    </button>
                </div>
            </div>
        `;
        
        document.body.appendChild(modal);
        
        // Trigger animations
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.firstElementChild.classList.remove('scale-95');
        }, 50);
        
        const close = () => {
            modal.classList.add('opacity-0');
            modal.firstElementChild.classList.add('scale-95');
            setTimeout(() => modal.remove(), 300);
        };
        
        modal.querySelector('#btn-tour-yes').addEventListener('click', () => {
            close();
            onConfirm();
        });
        
        modal.querySelector('#btn-tour-no').addEventListener('click', () => {
            close();
            onCancel();
        });
    }

    startTour(config) {
        const filteredSteps = config.steps
            .map(step => {
                const stepCopy = { ...step };
                if (stepCopy.element === '#tour-submit-btn') {
                    const submitEl = document.querySelector('#tour-submit-btn');
                    if (submitEl) {
                        const text = submitEl.textContent.trim();
                        stepCopy.popover = {
                            ...stepCopy.popover,
                            title: `💾 ${text}`,
                            description: `Tudo pronto? Clique aqui para concluir: "${text}".`
                        };
                    }
                }
                return stepCopy;
            })
            .filter(step => {
                if (!step.element) return true;
                const el = document.querySelector(step.element);
                if (!el) return false;
                // Verificar se o elemento está visível na tela
                return !!(el.offsetWidth || el.offsetHeight || el.getClientRects().length);
            });

        if (filteredSteps.length === 0) return;

        const d = driver({
            showProgress: true,
            nextBtnText: 'Próximo →',
            prevBtnText: '← Voltar',
            doneBtnText: 'Entendi!',
            allowClose: true,
            steps: filteredSteps,
            onDestroyed: () => {
                localStorage.setItem(config.storageKey, 'true');
            }
        });
        d.drive();
    }

    init() {
        const pageKey = this.getCurrentPageKey();
        if (!pageKey || !this.tours[pageKey]) return;

        const config = this.tours[pageKey];

        // Disparar automaticamente se for a primeira vez
        if (!localStorage.getItem(config.storageKey)) {
            setTimeout(() => {
                this.showOnboardingModal(
                    () => {
                        this.startTour(config);
                    },
                    () => {
                        localStorage.setItem(config.storageKey, 'true');
                    }
                );
            }, 1000);
        }

        // Adicionar suporte para os botões de reativação "Como Usar"
        document.addEventListener('click', (e) => {
            const helpBtn = e.target.closest('#btn-trigger-help-tour');
            if (helpBtn) {
                e.preventDefault();
                this.startTour(config);
            }
        });
    }
}

// Exportar para uso global
window.TourManager = TourManager;
export default TourManager;
