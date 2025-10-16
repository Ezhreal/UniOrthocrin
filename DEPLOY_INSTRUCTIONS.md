# 🚀 Instruções de Deploy - UniOrthocrin

## 📋 Pré-requisitos

### 1. Configurar Secrets no GitHub
Vá em: `Settings > Secrets and variables > Actions` e adicione:

```
HOST = ftp.seudominio.com.br
USER = seu_usuario_ftp
PASS = sua_senha_ftp
```

### 2. Configurar Banco de Dados na Locaweb
- Criar banco MySQL
- Anotar: host, database, username, password

## 🔧 Configuração do Deploy

### 1. Criar arquivo .env.production
```bash
# Copie o .env.example e ajuste para produção
cp .env.example .env.production
```

Edite o `.env.production` com:
```env
APP_NAME="UniOrthocrin"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seudominio.com.br

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=seu_banco_producao
DB_USERNAME=seu_usuario_producao
DB_PASSWORD=sua_senha_producao

# OneDrive (configure com suas credenciais)
ONEDRIVE_CLIENT_ID=seu_client_id
ONEDRIVE_CLIENT_SECRET=seu_client_secret
ONEDRIVE_REDIRECT_URI=https://seudominio.com.br/onedrive/callback
```

### 2. Deploy Automático
O deploy acontece automaticamente quando você:
1. Faz push para a branch `main`
2. Ou executa manualmente em: `Actions > Deploy to Locaweb > Run workflow`

## 🗄️ Configuração do Banco de Dados

### 1. Executar Migrações
Após o deploy, acesse o servidor via SSH e execute:

```bash
cd /home/seudominio/public_html
php artisan migrate --force
```

### 2. Popular Dados Iniciais
```bash
php migrate-production.php
```

Isso criará:
- ✅ Usuário administrador: `admin@uniorthocrin.com.br` / `admin123`
- ✅ Tipos de usuário padrão
- ✅ Categorias padrão para todos os módulos

## ⏰ Configurar Crontab

### 1. Executar Script de Configuração
```bash
bash setup-crontab.sh
```

### 2. Jobs Configurados
- **Queue Worker**: A cada 5 minutos (processa OneDrive sync)
- **Limpeza de Jobs**: A cada hora (remove jobs falhados)
- **Limpeza de Cache**: A cada 6 horas
- **Backup do Banco**: Diário às 2h

### 3. Verificar Crontab
```bash
crontab -l
```

## 🔄 Processo de Deploy

### 1. Desenvolvimento
```bash
# Trabalhe na branch develop
git checkout develop
git add .
git commit -m "Nova funcionalidade"
git push origin develop
```

### 2. Deploy para Produção
```bash
# Merge para main
git checkout main
git merge develop
git push origin main
```

### 3. Deploy Automático
- ✅ GitHub Actions executa automaticamente
- ✅ Instala dependências
- ✅ Executa migrações
- ✅ Limpa e cacheia configurações
- ✅ Faz upload via FTP

## 🛠️ Comandos Úteis

### No Servidor de Produção
```bash
# Verificar logs
tail -f storage/logs/laravel.log

# Executar queue worker manualmente
php artisan queue:work --once

# Limpar cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Verificar status dos jobs
php artisan queue:failed

# Reprocessar jobs falhados
php artisan queue:retry all
```

### Backup Manual
```bash
# Backup do banco
php artisan backup:run --only-db

# Backup completo
php artisan backup:run
```

## 🔍 Troubleshooting

### 1. Deploy Falhou
- Verificar logs em: `Actions > Deploy to Locaweb`
- Verificar se os secrets estão corretos
- Verificar se o banco está acessível

### 2. OneDrive Sync Não Funciona
- Verificar credenciais no .env
- Verificar se o crontab está rodando
- Verificar logs: `tail -f storage/logs/laravel.log`

### 3. Permissões de Arquivo
```bash
# Corrigir permissões
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## 📞 Suporte

Em caso de problemas:
1. Verificar logs do Laravel
2. Verificar logs do GitHub Actions
3. Verificar status do crontab
4. Verificar conectividade com OneDrive

---

**✅ Sistema configurado e pronto para produção!**