# Deploy manual – UniOrthocrin

Passo a passo para subir a aplicação no servidor de forma manual.

---

## 1. Enviar os arquivos para o servidor

### Opção A: ZIP (recomendado para primeira vez)

**No seu computador:**

```bash
cd /home/codestackrg/Projects/UniOrthocrin

# Gerar build do front (obrigatório antes de subir)
cd uniorthocrin
npm ci
npm run build
cd ..

# Criar ZIP ignorando o que não vai pro servidor (use o .deployignore)
zip -r uniorthocrin-deploy.zip uniorthocrin \
  -x "uniorthocrin/vendor/*" \
  -x "uniorthocrin/node_modules/*" \
  -x "uniorthocrin/.env" \
  -x "uniorthocrin/.env.*" \
  -x "uniorthocrin/storage/logs/*" \
  -x "uniorthocrin/storage/framework/cache/*" \
  -x "uniorthocrin/storage/framework/sessions/*" \
  -x "uniorthocrin/storage/framework/views/*" \
  -x "uniorthocrin/.git/*" \
  -x "*.DS_Store"
```

Envie o `uniorthocrin-deploy.zip` por FTP/SFTP ou SCP para o servidor e extraia na pasta do site (ex.: `/var/www/uniorthocrin`).

**Ou, se preferir enviar só a pasta `uniorthocrin`:**

- Envie tudo **exceto**: `vendor/`, `node_modules/`, `.env`, `.git/`, e o conteúdo de `storage/logs`, `storage/framework/cache`, `storage/framework/sessions`, `storage/framework/views`.

### Opção B: rsync / SCP (para atualizações)

```bash
cd /home/codestackrg/Projects/UniOrthocrin/uniorthocrin
npm run build   # sempre gerar o build antes

rsync -avz --exclude-from='.deployignore' --exclude='.git' \
  ./ usuario@SEU_SERVIDOR:/var/www/uniorthocrin/
```

Substitua `usuario@SEU_SERVIDOR` e `/var/www/uniorthocrin/` pelo seu usuário e caminho no servidor.

---

## 2. No servidor – preparar a aplicação

Entre no servidor (SSH) e vá até a pasta do projeto:

```bash
cd /var/www/uniorthocrin   # ou o caminho que você usar
```

### 2.1 Instalar dependências PHP (sem dev)

```bash
composer install --no-dev --optimize-autoloader
```

Se não tiver Composer instalado:

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
php -r "unlink('composer-setup.php');"
```

### 2.2 Arquivo .env

Crie o `.env` na raiz do projeto (copiando do exemplo e ajustando):

```bash
cp .env.example .env
nano .env   # ou vim
```

Ajuste no mínimo:

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://seudominio.com.br`
- `DB_CONNECTION=mysql`
- `DB_HOST=...`
- `DB_DATABASE=...`
- `DB_USERNAME=...`
- `DB_PASSWORD=...`

Gere a chave da aplicação:

```bash
php artisan key:generate
```

### 2.3 Banco de dados

```bash
php artisan migrate --force
```

Se precisar de dados iniciais (seed):

```bash
php artisan db:seed --force
```

### 2.4 Storage e cache

```bash
# Criar pastas e link do storage público
mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs
mkdir -p storage/app/private/campaigns storage/app/private/products storage/app/private/training storage/app/private/library storage/app/private/news storage/app/private/users

php artisan storage:link
```

### 2.5 Permissões

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

(Substitua `www-data` pelo usuário do servidor web se for outro, ex.: `nginx`, `apache`.)

### 2.6 Cache da aplicação

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## 3. Servidor web (Apache ou Nginx)

### Apache – VirtualHost exemplo

Document root deve apontar para a **pasta `public`** do projeto:

```apache
<VirtualHost *:80>
    ServerName seudominio.com.br
    DocumentRoot /var/www/uniorthocrin/public

    <Directory /var/www/uniorthocrin/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/uniorthocrin-error.log
    CustomLog ${APACHE_LOG_DIR}/uniorthocrin-access.log combined
</VirtualHost>
```

Ative o site e o módulo rewrite:

```bash
sudo a2ensite uniorthocrin
sudo a2enmod rewrite
sudo systemctl reload apache2
```

### Nginx – server block exemplo

```nginx
server {
    listen 80;
    server_name seudominio.com.br;
    root /var/www/uniorthocrin/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Ajuste o caminho do `fastcgi_pass` (PHP-FPM) e a versão do PHP se for diferente. Depois:

```bash
sudo nginx -t
sudo systemctl reload nginx
```

---

## 4. Próximos deploys (atualização)

1. **Na sua máquina:** gerar build e enviar arquivos (ZIP ou rsync), **sem** sobrescrever o `.env` do servidor.
2. **No servidor:**

```bash
cd /var/www/uniorthocrin
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
sudo chown -R www-data:www-data storage bootstrap/cache
```

Se você enviar os arquivos já com `npm run build` feito na sua máquina, **não** precisa rodar `npm` no servidor.

---

## 5. Script de pós-deploy no servidor

Você pode usar o script que já existe no projeto (após extrair os arquivos e rodar composer):

```bash
chmod +x deploy-setup.sh
./deploy-setup.sh
```

Ele configura permissões, `storage:link`, cache e sugere cron. Ajuste usuário (`www-data`) e caminhos se o seu servidor for diferente.

---

## Resumo rápido

| Onde        | O que fazer |
|------------|-------------|
| **Sua máquina** | `npm run build` → ZIP ou rsync (sem vendor, node_modules, .env, .git) |
| **Servidor**    | Extrair → `composer install --no-dev` → criar `.env` → `php artisan key:generate` → `php artisan migrate --force` → pastas storage + permissões → `php artisan storage:link` → `config/route/view cache` + `optimize` → configurar Apache/Nginx com `DocumentRoot` = `.../public` |

Se disser como você sobe hoje (FTP, SSH, painel), dá para encaixar esses passos exatamente no seu fluxo.
