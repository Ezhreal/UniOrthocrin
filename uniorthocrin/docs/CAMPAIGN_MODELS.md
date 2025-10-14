# 📋 Models de Campanha - Documentação

## 🎯 Visão Geral

Este documento descreve a estrutura completa dos models relacionados às campanhas de marketing, baseada no diagrama ERD fornecido. A arquitetura segue o padrão Laravel Eloquent com relacionamentos bem definidos e funcionalidades reutilizáveis.

## 🏗️ Estrutura dos Models

### 1. **Campaign** (Model Principal)
**Arquivo:** `app/Models/Campaign.php`

**Descrição:** Model central que representa uma campanha de marketing.

**Atributos:**
- `id` - Chave primária
- `name` - Nome da campanha
- `description` - Descrição da campanha
- `start_date` - Data de início
- `end_date` - Data de término
- `visible_franchise_only` - Visível apenas para franquias
- `status` - Status da campanha (active/inactive)
- `is_active` - Se está ativa
- `created_at` / `updated_at` - Timestamps

**Relacionamentos:**
- `posts()` - HasMany → CampaignPost
- `folders()` - HasMany → CampaignFolder
- `videos()` - HasMany → CampaignVideo
- `miscellaneous()` - HasMany → CampaignMiscellaneous
- `userViews()` - HasMany → UserView
- `downloadOptions()` - HasMany → DownloadOption

**Métodos Principais:**
- `getAllContent()` - Retorna todo o conteúdo da campanha
- `getActiveContent()` - Retorna conteúdo ativo
- `getContentCount()` - Conta total de itens de conteúdo
- `getMainThumbnailAttribute()` - Thumbnail principal

**Scopes:**
- `scopeActive()` - Apenas campanhas ativas
- `scopeCurrent()` - Campanhas atuais (dentro do período)
- `scopeFranchiseOnly()` - Apenas para franquias

### 2. **CampaignPost** (Posts da Campanha)
**Arquivo:** `app/Models/CampaignPost.php`

**Descrição:** Representa posts de redes sociais da campanha.

**Atributos:**
- `campaign_id` - FK para Campaign
- `name` - Nome do post
- `description` - Descrição
- `type` - Tipo (feeds, stories_mg_sp, stories_df_es)
- `status` - Status (active/inactive)
- `thumbnail_path` - Caminho do thumbnail

**Relacionamentos:**
- `campaign()` - BelongsTo → Campaign
- `files()` - BelongsToMany → File (via campaign_post_files)

**Métodos Específicos:**
- `getTypeLabelAttribute()` - Label do tipo
- `scopeOfType()` - Filtrar por tipo

### 3. **CampaignFolder** (Pastas da Campanha)
**Arquivo:** `app/Models/CampaignFolder.php`

**Descrição:** Representa pastas organizacionais por estado.

**Atributos:**
- `campaign_id` - FK para Campaign
- `name` - Nome da pasta
- `description` - Descrição
- `state` - Estado (MG/SP, DF/ES, RJ, etc.)
- `status` - Status (active/inactive)
- `thumbnail_path` - Caminho do thumbnail

**Relacionamentos:**
- `campaign()` - BelongsTo → Campaign
- `files()` - BelongsToMany → File (via campaign_folder_files)

**Métodos Específicos:**
- `getStateLabelAttribute()` - Label do estado
- `scopeOfState()` - Filtrar por estado
- `getAvailableStates()` - Lista de estados disponíveis

### 4. **CampaignVideo** (Vídeos da Campanha)
**Arquivo:** `app/Models/CampaignVideo.php`

**Descrição:** Representa vídeos da campanha.

**Atributos:**
- `campaign_id` - FK para Campaign
- `name` - Nome do vídeo
- `description` - Descrição
- `type` - Tipo (reels, marketing_campaigns)
- `status` - Status (active/inactive)
- `thumbnail_path` - Caminho do thumbnail

**Relacionamentos:**
- `campaign()` - BelongsTo → Campaign
- `files()` - BelongsToMany → File (via campaign_video_files)

**Métodos Específicos:**
- `getTypeLabelAttribute()` - Label do tipo
- `mainVideo()` - Vídeo principal
- `getMainVideoUrlAttribute()` - URL do vídeo principal
- `hasVideo()` - Verifica se tem vídeo
- `getDurationAttribute()` - Duração do vídeo

### 5. **CampaignMiscellaneous** (Diversos da Campanha)
**Arquivo:** `app/Models/CampaignMiscellaneous.php`

**Descrição:** Representa itens diversos (spots, tags, stickers, scripts).

**Atributos:**
- `campaign_id` - FK para Campaign
- `name` - Nome do item
- `description` - Descrição
- `type` - Tipo (spot, tag, sticker, script)
- `status` - Status (active/inactive)
- `thumbnail_path` - Caminho do thumbnail

**Relacionamentos:**
- `campaign()` - BelongsTo → Campaign
- `files()` - BelongsToMany → File (via campaign_miscellaneous_files)

**Métodos Específicos:**
- `getTypeLabelAttribute()` - Label do tipo
- `getIconClassAttribute()` - Classe do ícone
- `getColorClassAttribute()` - Classe da cor

## 🔧 Trait: HasCampaignContent

**Arquivo:** `app/Models/Traits/HasCampaignContent.php`

**Descrição:** Trait compartilhado entre todos os models de conteúdo de campanha.

**Funcionalidades Compartilhadas:**

### Relacionamentos com Arquivos:
- `primaryFile()` - Arquivo principal
- `mainImage()` - Imagem principal
- `images()` - Todas as imagens
- `videos()` - Todos os vídeos
- `documents()` - Todos os documentos
- `audios()` - Todos os áudios

### Scopes:
- `scopeActive()` - Apenas itens ativos

### Métodos de Verificação:
- `isActive()` - Verifica se está ativo
- `hasImage()` - Verifica se tem imagem
- `hasVideo()` - Verifica se tem vídeo
- `hasAudio()` - Verifica se tem áudio
- `hasDocuments()` - Verifica se tem documentos

### Accessors:
- `thumbnail_url` - URL do thumbnail
- `main_file_url` - URL do arquivo principal
- `file_count` - Contagem de arquivos
- `image_count` - Contagem de imagens
- `video_count` - Contagem de vídeos
- `document_count` - Contagem de documentos
- `audio_count` - Contagem de áudios
- `content_type` - Tipo de conteúdo
- `content_type_label` - Label do tipo
- `content_icon_class` - Classe do ícone
- `content_color_class` - Classe da cor
- `content_bg_color_class` - Classe do background

## 📊 Tabelas Pivot

### 1. **campaign_post_files**
- `campaign_post_id` - FK para CampaignPost
- `file_id` - FK para File
- `file_type` - Tipo do arquivo (image, video, document, audio)
- `sort_order` - Ordem de exibição
- `is_primary` - Se é arquivo principal
- `created_at` / `updated_at` - Timestamps

### 2. **campaign_folder_files**
- `campaign_folder_id` - FK para CampaignFolder
- `file_id` - FK para File
- `file_type` - Tipo do arquivo
- `sort_order` - Ordem de exibição
- `is_primary` - Se é arquivo principal
- `created_at` / `updated_at` - Timestamps

### 3. **campaign_video_files**
- `campaign_video_id` - FK para CampaignVideo
- `file_id` - FK para File
- `file_type` - Tipo do arquivo
- `sort_order` - Ordem de exibição
- `is_primary` - Se é arquivo principal
- `created_at` / `updated_at` - Timestamps

### 4. **campaign_miscellaneous_files**
- `campaign_miscellaneous_id` - FK para CampaignMiscellaneous
- `file_id` - FK para File
- `file_type` - Tipo do arquivo
- `sort_order` - Ordem de exibição
- `is_primary` - Se é arquivo principal
- `created_at` / `updated_at` - Timestamps

## 🚀 Service: CampaignService

**Arquivo:** `app/Services/CampaignService.php`

**Funcionalidades Principais:**

### Consultas Básicas:
- `getAllCampaigns()` - Todas as campanhas
- `getActiveCampaigns()` - Campanhas ativas
- `getFranchiseOnlyCampaigns()` - Apenas para franquias
- `getCampaignById()` - Campanha por ID

### Consultas Avançadas:
- `getCampaignsByDateRange()` - Por período
- `searchCampaigns()` - Busca por nome/descrição
- `getRecentCampaigns()` - Campanhas recentes
- `getUpcomingCampaigns()` - Campanhas futuras
- `getEndingCampaigns()` - Campanhas terminando

### Estatísticas:
- `getCampaignStats()` - Estatísticas da campanha
- `getCampaignsWithContentCount()` - Com contagem de conteúdo

### Filtros por Tipo:
- `getCampaignContentByType()` - Por tipo de conteúdo
- `getCampaignFoldersByState()` - Pastas por estado
- `getCampaignPostsByType()` - Posts por tipo
- `getCampaignVideosByType()` - Vídeos por tipo
- `getCampaignMiscellaneousByType()` - Diversos por tipo

### Arquivos:
- `getCampaignContentWithFiles()` - Conteúdo com arquivos
- `getCampaignContentByFileType()` - Por tipo de arquivo

## 💡 Exemplos de Uso

### 1. Buscar Campanha com Todo Conteúdo:
```php
$campaign = Campaign::with(['posts', 'folders', 'videos', 'miscellaneous'])->find(1);
$allContent = $campaign->getAllContent();
```

### 2. Filtrar Posts por Tipo:
```php
$feeds = $campaign->posts()->ofType('feeds')->active()->get();
```

### 3. Buscar Arquivos de um Post:
```php
$post = CampaignPost::with('files')->find(1);
$images = $post->images();
$videos = $post->videos();
```

### 4. Usar Service para Estatísticas:
```php
$service = new CampaignService();
$stats = $service->getCampaignStats($campaign);
```

### 5. Buscar Conteúdo por Tipo de Arquivo:
```php
$imageContent = $service->getCampaignContentByFileType($campaign, 'image');
```

## 🔄 Relacionamentos no ERD

```
Campaign (1) → (N) CampaignPost
Campaign (1) → (N) CampaignFolder  
Campaign (1) → (N) CampaignVideo
Campaign (1) → (N) CampaignMiscellaneous

CampaignPost (N) → (N) File (via campaign_post_files)
CampaignFolder (N) → (N) File (via campaign_folder_files)
CampaignVideo (N) → (N) File (via campaign_video_files)
CampaignMiscellaneous (N) → (N) File (via campaign_miscellaneous_files)
```

## ✅ Benefícios da Arquitetura

1. **Reutilização de Código** - Trait compartilhado
2. **Flexibilidade** - Muitos para muitos com arquivos
3. **Organização** - Separação clara por tipo de conteúdo
4. **Performance** - Eager loading e índices
5. **Manutenibilidade** - Código limpo e documentado
6. **Escalabilidade** - Fácil adição de novos tipos
7. **Consistência** - Padrões uniformes em todos os models
