# UniOrthocrin - Guia de Setup e Desenvolvimento

## STATUS ATUAL DO SISTEMA (LADO DO CLIENTE)

### O que já foi feito:
- Layout da dashboard pronto, responsivo e aprovado visualmente.
- Controllers, Services e Repositories criados para: Produtos, Notícias, Treinamentos, Notificações, Marketing (Campanhas) e Biblioteca (Library), todos seguindo padrão SOLID e Repository Pattern.
- Models Eloquent para todas as entidades principais, com traits de permissões e scopes de status/visibilidade.
- Permissões e visibilidade implementadas e seeders ajustados para cada tipo de usuário.
- Layouts e componentes principais prontos (dashboard, blocos, acervo digital, etc), com Font Awesome para ícones.

### Roteiro de próximos passos (lado do cliente)
1. Implementar as views das demais páginas (produtos, treinamentos, biblioteca, campanhas, etc) — pode começar com layouts mockados e depois integrar com dados reais.
2. Integrar controllers/services/repos nas views para exibir dados reais em todas as páginas.
3. Ajustar rotas web para garantir navegação entre todas as páginas.
4. Testar permissões e visibilidade em diferentes tipos de usuário.
5. Aprimorar detalhes de UX/UI conforme feedback.

## Stack Tecnológica
- **Backend**: Laravel 10+ + MySQL
- **Frontend**: Blade + Alpine.js + Tailwind CSS + Livewire
- **Servidor**: Ubuntu + Nginx + PHP-FPM

---

## FASE 1: SETUP INICIAL

### 1.1 Criação do Projeto e Dependências

```bash
# Criar projeto Laravel
composer create-project laravel/laravel uniorthocrin
cd uniorthocrin

# Instalar dependências PHP
composer require spatie/laravel-permission
composer require intervention/image
composer require maatwebsite/excel
composer require livewire/livewire

# Instalar dependências Frontend
npm install -D tailwindcss postcss autoprefixer @tailwindcss/forms @tailwindcss/typography
npm install alpinejs sweetalert2 sortablejs axios

# Configurar Tailwind
npx tailwindcss init -p

# Publicar configs necessários
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

### 1.2 Configuração do Banco de Dados

```bash
# Criar banco MySQL
mysql -u root -p
CREATE DATABASE uniorthocrin;
CREATE USER 'uniorthocrin_user'@'localhost' IDENTIFIED BY 'sua_senha_segura';
GRANT ALL PRIVILEGES ON uniorthocrin.* TO 'uniorthocrin_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

**Arquivo .env**
```env
APP_NAME="UniOrthocrin"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=uniorthocrin
DB_USERNAME=root
DB_PASSWORD=Fl03

FILESYSTEM_DISK=public
```

---

## FASE 2: ESTRUTURA DE MIGRATIONS

### 2.1 Comando para Criar Todas as Migrations

```bash
# 1. Tabelas Base (sem dependências)
php artisan make:migration create_user_types_table
php artisan make:migration create_product_categories_table
php artisan make:migration create_training_categories_table
php artisan make:migration create_library_categories_table

# 2. Modificar tabela users existente
php artisan make:migration add_fields_to_users_table --table=users

# 3. Tabelas Principais
php artisan make:migration create_products_table
php artisan make:migration create_campaigns_table
php artisan make:migration create_trainings_table
php artisan make:migration create_library_table
php artisan make:migration create_news_table

# 4. Tabela Polimórfica de Arquivos
php artisan make:migration create_files_table

# 5. Tabelas de Permissões
php artisan make:migration create_product_permissions_table
php artisan make:migration create_training_permissions_table
php artisan make:migration create_library_permissions_table
php artisan make:migration create_news_permissions_table

# 6. Tabelas de Monitoramento
php artisan make:migration create_user_views_table
php artisan make:migration create_access_history_table
php artisan make:migration create_user_notifications_table

# 7. Tabelas Auxiliares
php artisan make:migration create_download_options_table
```

### 2.2 Schema das Migrations

#### **Migration: create_user_types_table**
```php
Schema::create('user_types', function (Blueprint $table) {
    $table->id();
    $table->string('name')->unique(); // admin, franquia, representante, lojista
    $table->string('description');
    $table->integer('level')->default(1); // nível hierárquico
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->timestamps();
});
```

#### **Migration: create_product_categories_table**
```php
Schema::create('product_categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->timestamps();
    
    $table->index('status');
});
```

#### **Migration: create_training_categories_table**
```php
Schema::create('training_categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->timestamps();
    
    $table->index('status');
});
```

#### **Migration: create_library_categories_table**
```php
Schema::create('library_categories', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->timestamps();
    
    $table->index('status');
});
```

#### **Migration: add_fields_to_users_table**
```php
Schema::table('users', function (Blueprint $table) {
    $table->foreignId('user_type_id')->nullable()->constrained()->nullOnDelete();
    $table->timestamp('last_access')->nullable();
    $table->enum('status', ['active', 'inactive'])->default('active');
    
    $table->index(['user_type_id', 'status']);
    $table->index('last_access');
});
```

#### **Migration: create_products_table**
```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('serie')->nullable();
    $table->foreignId('category_id')->constrained('product_categories');
    $table->text('description')->nullable();
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->timestamps();
    
    $table->index(['category_id', 'status']);
    $table->index('updated_at');
});
```

#### **Migration: create_campaigns_table**
```php
Schema::create('campaigns', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description')->nullable();
    $table->date('start_date')->nullable();
    $table->date('end_date')->nullable();
    $table->boolean('visible_franchise_only')->default(true);
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->timestamps();
    
    $table->index(['status', 'visible_franchise_only']);
    $table->index(['start_date', 'end_date']);
    $table->index('updated_at');
});
```

#### **Migration: create_trainings_table**
```php
Schema::create('trainings', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->foreignId('category_id')->constrained('training_categories');
    $table->text('description')->nullable();
    $table->enum('content_type', ['pdf', 'video'])->default('pdf');
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->timestamps();
    
    $table->index(['category_id', 'status']);
    $table->index(['content_type', 'status']);
    $table->index('updated_at');
});
```

#### **Migration: create_library_table**
```php
Schema::create('library', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->foreignId('category_id')->constrained('library_categories');
    $table->text('description')->nullable();
    $table->enum('status', ['active', 'inactive'])->default('active');
    $table->timestamps();
    
    $table->index(['category_id', 'status']);
    $table->index('updated_at');
});
```

#### **Migration: create_news_table**
```php
Schema::create('news', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('content');
    $table->text('excerpt')->nullable();
    $table->foreignId('author_id')->constrained('users');
    $table->timestamp('published_at')->nullable();
    $table->enum('status', ['draft', 'published'])->default('draft');
    $table->timestamps();
    
    $table->index(['status', 'published_at']);
    $table->index('author_id');
    $table->index('updated_at');
});
```

#### **Migration: create_files_table**
```php
Schema::create('files', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->enum('type', ['image', 'video', 'pdf', 'audio']);
    $table->string('path');
    $table->string('thumbnail_path')->nullable();
    $table->unsignedBigInteger('size'); // bytes
    $table->string('extension', 10);
    $table->string('mime_type')->nullable();
    $table->integer('order')->default(0);
    
    // Relacionamento polimórfico
    $table->morphs('fileable');
    
    $table->timestamps();
    
    $table->index(['fileable_type', 'fileable_id']);
    $table->index(['type', 'fileable_type']);
    $table->index('order');
});
```

#### **Migration: create_product_permissions_table**
```php
Schema::create('product_permissions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_type_id')->constrained()->cascadeOnDelete();
    $table->boolean('can_view')->default(true);
    $table->boolean('can_download')->default(true);
    $table->timestamps();
    
    $table->unique(['product_id', 'user_type_id']);
    $table->index(['user_type_id', 'can_view']);
});
```

#### **Migration: create_training_permissions_table**
```php
Schema::create('training_permissions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('training_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_type_id')->constrained()->cascadeOnDelete();
    $table->boolean('can_view')->default(true);
    $table->boolean('can_download')->default(true);
    $table->timestamps();
    
    $table->unique(['training_id', 'user_type_id']);
    $table->index(['user_type_id', 'can_view']);
});
```

#### **Migration: create_library_permissions_table**
```php
Schema::create('library_permissions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('library_id')->constrained('library')->cascadeOnDelete();
    $table->foreignId('user_type_id')->constrained()->cascadeOnDelete();
    $table->boolean('can_view')->default(true);
    $table->boolean('can_download')->default(true);
    $table->timestamps();
    
    $table->unique(['library_id', 'user_type_id']);
    $table->index(['user_type_id', 'can_view']);
});
```

#### **Migration: create_news_permissions_table**
```php
Schema::create('news_permissions', function (Blueprint $table) {
    $table->id();
    $table->foreignId('news_id')->constrained()->cascadeOnDelete();
    $table->foreignId('user_type_id')->constrained()->cascadeOnDelete();
    $table->boolean('can_view')->default(true);
    $table->timestamps();
    
    $table->unique(['news_id', 'user_type_id']);
    $table->index(['user_type_id', 'can_view']);
});
```

#### **Migration: create_user_views_table**
```php
Schema::create('user_views', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('viewable_type'); // Product, Campaign, Training, etc.
    $table->unsignedBigInteger('viewable_id');
    $table->timestamp('first_viewed_at');
    $table->timestamp('last_viewed_at');
    $table->integer('view_count')->default(1);
    $table->integer('download_count')->default(0);
    $table->timestamps();
    
    $table->index(['viewable_type', 'viewable_id']);
    $table->unique(['user_id', 'viewable_type', 'viewable_id']);
    $table->index(['user_id', 'last_viewed_at']);
});
```

#### **Migration: create_access_history_table**
```php
Schema::create('access_history', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('action'); // view, download
    $table->string('resource_type'); // Product, Campaign, etc.
    $table->unsignedBigInteger('resource_id');
    $table->string('ip_address', 45)->nullable();
    $table->text('user_agent')->nullable();
    $table->json('metadata')->nullable(); // dados extras
    $table->timestamp('created_at');
    
    $table->index(['user_id', 'created_at']);
    $table->index(['resource_type', 'resource_id']);
    $table->index(['action', 'created_at']);
});
```

#### **Migration: create_user_notifications_table**
```php
Schema::create('user_notifications', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('title');
    $table->text('message');
    $table->string('type')->default('info'); // info, success, warning, error
    $table->string('related_type')->nullable(); // Product, Campaign, etc.
    $table->unsignedBigInteger('related_id')->nullable();
    $table->timestamp('read_at')->nullable();
    $table->timestamps();
    
    $table->index(['user_id', 'read_at']);
    $table->index(['related_type', 'related_id']);
    $table->index('created_at');
});
```

#### **Migration: create_download_options_table**
```php
Schema::create('download_options', function (Blueprint $table) {
    $table->id();
    $table->string('resource_type'); // Product, Campaign, etc.
    $table->unsignedBigInteger('resource_id');
    $table->string('option_name'); // 'all_images', 'all_videos', 'complete'
    $table->text('description')->nullable();
    $table->unsignedBigInteger('estimated_size')->nullable(); // bytes
    $table->timestamps();
    
    $table->index(['resource_type', 'resource_id']);
    $table->unique(['resource_type', 'resource_id', 'option_name']);
});
```

---

## FASE 3: SEEDERS

### 3.1 Criar Seeders

```bash
php artisan make:seeder UserTypeSeeder
php artisan make:seeder ProductCategorySeeder
php artisan make:seeder TrainingCategorySeeder
php artisan make:seeder LibraryCategorySeeder
php artisan make:seeder AdminUserSeeder
```

### 3.2 Executar Migrations e Seeders

```bash
# Executar migrations
php artisan migrate

# Executar seeders
php artisan db:seed --class=UserTypeSeeder
php artisan db:seed --class=ProductCategorySeeder
php artisan db:seed --class=TrainingCategorySeeder
php artisan db:seed --class=LibraryCategorySeeder
php artisan db:seed --class=AdminUserSeeder

# Ou executar todos
php artisan db:seed
```

---

## FASE 4: MODELS

### 4.1 Criar Models

```bash
php artisan make:model UserType
php artisan make:model Product
php artisan make:model ProductCategory
php artisan make:model Campaign
php artisan make:model Training
php artisan make:model TrainingCategory
php artisan make:model Library
php artisan make:model LibraryCategory
php artisan make:model News
php artisan make:model File
php artisan make:model UserView
php artisan make:model AccessHistory
php artisan make:model UserNotification
php artisan make:model DownloadOption
```

---

## PRÓXIMOS PASSOS

1. ✅ **Executar FASE 1**: Setup inicial
2. ✅ **Executar FASE 2**: Criar e executar migrations
3. ✅ **Executar FASE 3**: Criar e executar seeders
4. ✅ **FASE 4**: Implementar Models com relacionamentos
5. ⏳ **FASE 5**: Controllers e Routes
6. ⏳ **FASE 6**: Views e Layouts
7. ⏳ **FASE 7**: Alpine.js e Interatividade

---

## COMANDOS ÚTEIS

```bash
# Reset completo do banco
php artisan migrate:fresh --seed

# Verificar status das migrations
php artisan migrate:status

# Rollback última migration
php artisan migrate:rollback

# Criar link simbólico para storage
php artisan storage:link

# Limpar caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```