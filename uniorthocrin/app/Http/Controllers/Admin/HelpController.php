<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    public static function topics(): array
    {
        return [
            'usuarios' => [
                'title' => 'Cadastro de usuários',
                'icon' => 'fa-users',
                'route' => 'admin.users.index',
                'permissions_note' => '**Painel admin:** apenas usuários com tipo **Admin** (tipo 1) acessam o painel. Quem cadastra usuários é sempre o Admin. No **site (área do usuário)**, o que cada um vê depende do tipo: **Admin** e **Franqueado** veem Marketing; **Lojista** e **Representante** não veem Marketing. Produtos, Biblioteca, Treinamentos e Radar respeitam as permissões por item (quem pode ver/baixar cada um).',
                'summary' => [],
                'steps' => [
                    ['Acessar usuários', 'No menu lateral, clique em **Usuários** (seção Admin).'],
                    ['Editar / inativar', 'Na listagem, use as ações de cada linha para editar ou inativar. Usuário inativo não consegue fazer login.'],
                ],
                'sections' => [
                    [
                        'title' => 'Dados do usuário',
                        'icon' => 'fa-user',
                        'fields' => ['Nome (obrigatório, até 255 caracteres)', 'E-mail (obrigatório, único no sistema)', 'Senha (mín. 8 caracteres, confirmação)', 'Status: Ativo ou Inativo'],
                        'uploads' => ['Não há upload de arquivo.'],
                        'steps' => [['Novo usuário', 'Clique em **Novo usuário**. Preencha nome, e-mail e senha. Escolha status. Salve.']],
                    ],
                    [
                        'title' => 'Tipo e campos por perfil',
                        'icon' => 'fa-user-tag',
                        'fields' => ['Tipo de usuário (obrigatório): Admin, Franqueado, Lojista ou Representante', 'Para Franqueado, Lojista e Representante (opcional): Razão social, Nome fantasia, CNPJ da empresa'],
                        'uploads' => [],
                        'steps' => [['Campos por tipo', 'Se for **Franqueado**, **Lojista** ou **Representante**, o bloco **Dados da empresa** pode ser preenchido (todos os campos são opcionais).']],
                    ],
                ],
            ],
            'campanhas' => [
                'title' => 'Campanhas (Marketing)',
                'icon' => 'fa-bullhorn',
                'route' => 'admin.campaigns.index',
                'permissions_note' => '**Quem vê no site:** apenas **Admin** e **Franqueado**. Lojista e Representante não veem Marketing. Campanhas podem ser marcadas como **apenas para franqueados** (visível só para tipo Franqueado).',
                'summary' => [],
                'steps' => [
                    ['Acessar campanhas', 'No menu lateral, clique em **Campanhas** (Marketing). Depois de criar ou abrir uma campanha, use as abas para adicionar **Posts**, **Pastas**, **Vídeos** e **Materiais diversos**.'],
                ],
                'sections' => [
                    [
                        'title' => 'Campanha (dados gerais)',
                        'icon' => 'fa-bullhorn',
                        'fields' => ['Nome (obrigatório)', 'Descrição (opcional)', 'Data início e fim (opcional)', 'Status: ativa ou inativa', 'Em destaque (sim/não)'],
                        'uploads' => ['Thumbnail: imagem (JPEG, PNG, WebP)', 'Banner: imagem (quando em destaque)'],
                        'steps' => [['Criar campanha', 'Clique em **Nova campanha**. Preencha nome, descrição, datas e status. Envie thumbnail e, se for destaque, o banner. Salve.']],
                    ],
                    [
                        'title' => 'Posts',
                        'icon' => 'fa-image',
                        'fields' => ['Título', 'Descrição (opcional)', 'Status', 'Tipo (feed, stories MG, stories Outros Estados)'],
                        'uploads' => ['1 imagem por post (formatos de imagem aceitos pelo sistema)'],
                        'steps' => [['Adicionar post', 'Dentro da campanha, aba **Posts** > **Novo**. Preencha título e descrição, escolha o tipo e envie a imagem.']],
                    ],
                    [
                        'title' => 'Pastas (folhetos)',
                        'icon' => 'fa-folder',
                        'fields' => ['Nome', 'Descrição (opcional)', 'Status', 'Estado: MG ou Outros Estados'],
                        'uploads' => ['Múltiplos arquivos por pasta (ex.: PDFs, imagens). Tamanho máximo por arquivo conforme validação.'],
                        'steps' => [['Adicionar pasta', 'Aba **Pastas** > **Nova pasta**. Preencha nome, descrição e estado. Anexe os arquivos (folhetos).']],
                    ],
                    [
                        'title' => 'Vídeos',
                        'icon' => 'fa-video',
                        'fields' => ['Título', 'Descrição (opcional)', 'Status'],
                        'uploads' => ['1 arquivo de vídeo por item (formatos aceitos: ex. MP4, etc.)'],
                        'steps' => [['Adicionar vídeo', 'Aba **Vídeos** > **Novo**. Preencha título e descrição. Envie o arquivo de vídeo.']],
                    ],
                    [
                        'title' => 'Materiais diversos',
                        'icon' => 'fa-puzzle-piece',
                        'fields' => ['Nome', 'Descrição (opcional)', 'Tipo: Script (materiais internos), Adesivo, Banner ou Faixa', 'Status'],
                        'uploads' => ['1 arquivo por item (imagem ou documento, conforme o tipo)'],
                        'steps' => [['Adicionar material', 'Aba **Materiais diversos** > **Novo**. Escolha o tipo (Script, Adesivo, Banner, Faixa), preencha nome e descrição. Anexe o arquivo.']],
                    ],
                ],
            ],
            'permissoes' => [
                'title' => 'Permissões',
                'icon' => 'fa-lock',
                'route' => 'admin.users.index',
                'permissions_note' => '**Quem acessa o admin:** só **Admin** (tipo 1). **No site:** cada tipo (Admin, Franqueado, Lojista, Representante) vê apenas o que tem permissão. Em **Produtos**, **Biblioteca**, **Treinamentos** e **Radar** as permissões são **por item**: você escolhe, por tipo de usuário, quem pode **ver** e quem pode **baixar** cada produto/item/treinamento/notícia.',
                'summary' => [
                    'Tipo de usuário define o nível geral (Admin vê tudo no admin; Franqueado vê Marketing no site; etc.).',
                    'Por item (produto, biblioteca, treinamento, notícia): tabela de permissões com tipo de usuário, "pode ver" e "pode baixar".',
                    'Se nenhuma permissão for marcada para um item, ele pode ficar invisível ou seguir regra padrão do sistema.',
                ],
                'steps' => [
                    ['Tipos de usuário', 'Cada usuário tem um **tipo**: Admin (1), Franqueado (2), Lojista (3), Representante (4). O tipo define o que ele acessa no site (ex.: Marketing só para Admin e Franqueado).'],
                    ['Permissões por conteúdo', 'Em **Produtos**, **Biblioteca**, **Treinamentos** e **Radar (Notícias)** há uma seção **Permissões** em cada item (criar/editar).'],
                    ['Definir acesso', 'Na seção Permissões, adicione linhas: escolha o **tipo de usuário** e marque **pode ver** e/ou **pode baixar**. Um mesmo item pode ter vários tipos com acesso.'],
                    ['Salvar', 'Salve o produto/item/treinamento/notícia. No site, só usuários dos tipos permitidos verão e, se marcado, poderão baixar.'],
                ],
            ],
            'categorias' => [
                'title' => 'Categorias',
                'icon' => 'fa-tags',
                'route' => 'admin.products.index',
                'permissions_note' => 'Categorias não têm permissão própria: são apenas para organização. Quem vê o conteúdo continua sendo definido pelas permissões de cada produto/item/treinamento/notícia.',
                'summary' => [
                    'Produtos: categorias em Conteúdo > Produtos > Categorias (nome, etc.).',
                    'Biblioteca: categorias em Biblioteca > Categorias.',
                    'Treinamentos: categorias em Treinamentos > Categorias.',
                    'Radar: categorias em Radar (Notícias) > Categorias.',
                    'Cada categoria costuma ter: nome; descrição opcional.',
                ],
                'steps' => [
                    ['Onde existem categorias', 'Categorias existem em **Produtos** (menu Conteúdo), **Biblioteca**, **Treinamentos** e **Radar**. Em cada módulo há um submenu ou aba **Categorias**.'],
                    ['Criar categoria', 'Abra **Categorias** do módulo desejado. Clique em **Nova categoria** (ou adicione o nome e salve). Preencha nome e, se houver, descrição.'],
                    ['Usar nas entradas', 'Ao criar ou editar um produto, item da biblioteca, treinamento ou notícia, selecione a **categoria** no formulário.'],
                    ['Organização', 'Categorias ajudam a filtrar e organizar o conteúdo para o usuário final no site.'],
                ],
            ],
            'produtos' => [
                'title' => 'Produtos',
                'icon' => 'fa-box',
                'route' => 'admin.products.index',
                'permissions_note' => 'Quem vê cada produto no site é definido pelas **Permissões** do produto (por tipo de usuário: ver e/ou baixar). Sem permissão configurada, o produto pode não aparecer para ninguém.',
                'summary' => [],
                'steps' => [
                    ['Acessar', 'No menu **Conteúdo** > **Produtos**. Configure antes **Categorias** e **Séries** no submenu.'],
                    ['Permissões e OneDrive', 'Na aba **Permissões** defina quem pode ver e baixar. Se houver OneDrive, use a opção ao salvar.'],
                ],
                'sections' => [
                    [
                        'title' => 'Cadastro do produto',
                        'icon' => 'fa-box',
                        'fields' => ['Nome (obrigatório, até 255 caracteres)', 'Descrição (opcional)', 'Categoria (obrigatório)', 'Série (opcional)', 'Status: ativo ou inativo', 'Permissões: por tipo de usuário (pode ver, pode baixar)'],
                        'uploads' => ['Thumbnail: 1 imagem (JPEG, PNG, WebP) — capa do produto', 'Galeria: múltiplas imagens ou arquivos (formatos aceitos pelo sistema)'],
                        'steps' => [['Novo produto', 'Clique em **Novo produto**. Preencha nome, descrição, categoria, série e status.'], ['Enviar arquivos', 'Envie o thumbnail e, na galeria, as imagens/arquivos que o usuário poderá ver ou baixar.'], ['Permissões', 'Na aba **Permissões**, marque para cada tipo de usuário se pode **ver** e **baixar**. Salve.']],
                    ],
                ],
            ],
            'biblioteca' => [
                'title' => 'Biblioteca',
                'icon' => 'fa-book',
                'route' => 'admin.library.index',
                'permissions_note' => 'Cada item da biblioteca tem **Permissões** por tipo de usuário (ver e baixar). Só quem tiver permissão vê o item no site.',
                'summary' => [],
                'steps' => [
                    ['Acessar', 'No menu **Conteúdo** > **Biblioteca**. Configure **Categorias** antes de criar itens.'],
                    ['OneDrive', 'Se configurado, use **Sincronizar** no item para enviar arquivos ao OneDrive.'],
                ],
                'sections' => [
                    [
                        'title' => 'Item da biblioteca',
                        'icon' => 'fa-book',
                        'fields' => ['Nome (obrigatório, até 255 caracteres)', 'Descrição (opcional, até 1000 caracteres)', 'Categoria (obrigatório)', 'Status: ativo ou inativo', 'Permissões: por tipo de usuário (ver, baixar)'],
                        'uploads' => ['Thumbnail: 1 imagem opcional (JPEG, JPG, PNG, WebP — máx. 10 MB)', 'Arquivos: múltiplos (ex.: PDFs, documentos) — até 500 MB por arquivo'],
                        'steps' => [['Novo item', 'Clique em **Novo item**. Preencha nome, descrição, categoria e status.'], ['Enviar arquivos', 'Envie a thumbnail (opcional) e os arquivos do documento.'], ['Permissões', 'Na seção **Permissões** defina quem pode ver e baixar. Salve.']],
                    ],
                ],
            ],
            'radar' => [
                'title' => 'Radar (Notícias)',
                'icon' => 'fa-newspaper',
                'route' => 'admin.news.index',
                'permissions_note' => 'Cada notícia tem **Permissões** por tipo de usuário (ver e baixar). Status **Publicado** faz a notícia aparecer no site para quem tiver permissão.',
                'summary' => [],
                'steps' => [
                    ['Acessar', 'No menu **Conteúdo** > **Radar**. Crie **Categorias** antes (ex.: Novidades, Avisos).'],
                    ['Publicação', 'Use status **Publicado** para exibir no site. **Rascunho** não aparece.'],
                ],
                'sections' => [
                    [
                        'title' => 'Notícia (Radar)',
                        'icon' => 'fa-newspaper',
                        'fields' => ['Título (obrigatório, até 255 caracteres)', 'Conteúdo (obrigatório)', 'Categoria (obrigatório)', 'Status: Publicado ou Rascunho', 'Permissões: por tipo de usuário (ver, baixar)'],
                        'uploads' => ['Imagem de destaque: 1 imagem (formatos aceitos; ex.: máx. 10 MB)'],
                        'steps' => [['Nova notícia', 'Clique em **Nova notícia**. Preencha título, conteúdo, categoria e status.'], ['Imagem', 'Envie a imagem de destaque (opcional).'], ['Permissões', 'Defina quem pode ver e baixar. Salve.']],
                    ],
                ],
            ],
            'treinamentos' => [
                'title' => 'Treinamentos',
                'icon' => 'fa-graduation-cap',
                'route' => 'admin.training.index',
                'permissions_note' => 'Cada treinamento tem **Permissões** por tipo de usuário (ver e baixar). Só quem tiver permissão vê o treinamento no site.',
                'summary' => [],
                'steps' => [
                    ['Acessar', 'No menu **Conteúdo** > **Treinamentos**. Configure **Categorias** antes.'],
                    ['OneDrive', 'Se configurado, use a opção de sincronizar com OneDrive ao salvar.'],
                ],
                'sections' => [
                    [
                        'title' => 'Treinamento',
                        'icon' => 'fa-graduation-cap',
                        'fields' => ['Nome (obrigatório, até 255 caracteres)', 'Descrição (opcional, até 1000 caracteres)', 'Categoria (obrigatório)', 'Status: ativo ou inativo', 'Permissões: por tipo de usuário (ver, baixar)'],
                        'uploads' => ['Thumbnail: 1 imagem opcional (JPEG, PNG, WebP — máx. 10 MB)', 'Vídeos: MP4, AVI, MOV, WMV (tamanho máx. conforme validação)', 'PDFs: arquivos PDF (máx. 10 MB cada)'],
                        'steps' => [['Novo treinamento', 'Clique em **Novo treinamento**. Preencha nome, descrição, categoria e status.'], ['Enviar mídia', 'Envie a thumbnail e, na área de arquivos, os vídeos e/ou PDFs do curso.'], ['Permissões', 'Defina quem pode ver e baixar. Salve.']],
                    ],
                ],
            ],
            'relatorios' => [
                'title' => 'Relatórios',
                'icon' => 'fa-chart-bar',
                'route' => 'admin.reports.index',
                'permissions_note' => 'Apenas **Admin** acessa o painel e, portanto, a tela de Relatórios. Os relatórios mostram dados gerais do sistema (usuários, downloads, arquivos, acessos).',
                'summary' => [
                    'Visão geral: totais de usuários, conteúdo, arquivos e acessos.',
                    'Relatórios detalhados: Usuários, Downloads, Arquivos, Acessos.',
                    'Exportar: CSV para usuários, downloads, arquivos ou acessos.',
                ],
                'steps' => [
                    ['Acessar', 'No menu **Admin** > **Relatórios**.'],
                    ['Visão geral', 'Na tela principal você vê totais de usuários, conteúdo, arquivos e acessos.'],
                    ['Relatórios detalhados', 'Use os links **Usuários**, **Downloads**, **Arquivos** e **Acessos** para ver relatórios específicos.'],
                    ['Exportar', 'Use o botão **Exportar** para baixar os dados em CSV (usuários, downloads, arquivos ou acessos).'],
                ],
            ],
            'notificacoes' => [
                'title' => 'Notificações',
                'icon' => 'fa-bell',
                'route' => 'admin.notifications.index',
                'permissions_note' => 'Apenas **Admin** cria e gerencia notificações no painel. No site, cada usuário vê no sino apenas as notificações **enviadas para ele** (todos, seu tipo ou usuários específicos).',
                'summary' => [],
                'steps' => [
                    ['Acessar', 'No menu **Admin** > **Notificações** ou pelo ícone de sino no topo.'],
                    ['Salvar', 'Após salvar, a notificação aparece no sino do site para os usuários escolhidos.'],
                ],
                'sections' => [
                    [
                        'title' => 'Nova notificação',
                        'icon' => 'fa-bell',
                        'fields' => ['Título (obrigatório, até 255 caracteres)', 'Mensagem (obrigatória, até 1000 caracteres)', 'Tipo: info, success, warning ou error', 'Enviar para: Todos / Por tipo de usuário / Usuários específicos', 'Se "Por tipo": marcar um ou mais (Admin, Franqueado, Lojista, Representante)', 'Se "Usuários específicos": escolher da lista'],
                        'uploads' => ['Não há upload de arquivo. Apenas texto (título e mensagem).'],
                        'steps' => [['Criar', 'Clique em **Nova notificação**. Preencha título, mensagem e tipo.'], ['Público', 'Em **Enviar para** escolha Todos, Por tipo de usuário (e marque os tipos) ou Usuários específicos (e selecione). Salve.']],
                    ],
                ],
            ],
        ];
    }

    public function index()
    {
        $topics = self::topics();
        return view('admin.help.index', compact('topics'));
    }

    public function show(string $topic)
    {
        $topics = self::topics();
        if (! isset($topics[$topic])) {
            abort(404);
        }
        $data = $topics[$topic];
        return view('admin.help.show', [
            'topic' => $topic,
            'data' => $data,
            'allTopics' => $topics,
        ]);
    }
}
