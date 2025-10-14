# 📁 Configuração de Storage para Produção

## 🎯 **Estratégias Recomendadas**

### **1. Storage Local (Recomendado para início)**
```bash
# No servidor, configure:
sudo chown -R www-data:www-data storage/
sudo chmod -R 775 storage/
```

### **2. Storage em NFS (Para múltiplos servidores)**
```php
// config/filesystems.php
'private' => [
    'driver' => 'local',
    'root' => '/mnt/nfs/storage/app',
    'url' => env('APP_URL').'/storage',
    'visibility' => 'private',
],
```

### **3. Storage em S3 (Recomendado para produção)**
```php
// config/filesystems.php
'private' => [
    'driver' => 's3',
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION'),
    'bucket' => env('AWS_BUCKET'),
    'url' => env('AWS_URL'),
    'endpoint' => env('AWS_ENDPOINT'),
    'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
    'throw' => false,
],
```

## 🔧 **Comandos para Deploy**

### **Upload via SCP/RSYNC**
```bash
# Upload do projeto
rsync -avz --exclude 'node_modules' --exclude '.git' \
  /caminho/local/ uniorthocrin@servidor:/var/www/uniorthocrin/

# Upload apenas dos arquivos de storage (se necessário)
rsync -avz storage/app/private/ uniorthocrin@servidor:/var/www/uniorthocrin/storage/app/private/
```

### **Backup de Storage**
```bash
# Backup completo
tar -czf backup-storage-$(date +%Y%m%d).tar.gz storage/app/private/

# Backup incremental
rsync -avz --link-dest=/backup/previous/ storage/app/private/ /backup/current/
```

## 🚀 **Deploy Automatizado**

### **Script de Deploy**
```bash
#!/bin/bash
# deploy.sh

echo "🚀 Iniciando deploy..."

# 1. Backup atual
echo "📦 Fazendo backup..."
tar -czf backup-$(date +%Y%m%d-%H%M).tar.gz storage/app/private/

# 2. Upload do código
echo "📤 Enviando código..."
rsync -avz --exclude 'node_modules' --exclude '.git' \
  --exclude 'storage/app/private' \
  . uniorthocrin@servidor:/var/www/uniorthocrin/

# 3. Upload dos arquivos (se necessário)
echo "📁 Enviando arquivos..."
rsync -avz storage/app/private/ uniorthocrin@servidor:/var/www/uniorthocrin/storage/app/private/

# 4. Configurar permissões
echo "🔧 Configurando permissões..."
ssh uniorthocrin@servidor "cd /var/www/uniorthocrin && sudo chown -R www-data:www-data storage/ && sudo chmod -R 775 storage/"

# 5. Otimizar aplicação
echo "⚡ Otimizando..."
ssh uniorthocrin@servidor "cd /var/www/uniorthocrin && php artisan optimize"

echo "✅ Deploy concluído!"
```

## 🔒 **Segurança**

### **Permissões Recomendadas**
```bash
# Arquivos de aplicação
chmod 644 *.php
chmod 755 public/
chmod 644 public/*.php

# Storage
chmod 775 storage/
chmod 775 storage/app/private/
chmod 644 storage/app/private/*

# Logs
chmod 664 storage/logs/*.log
```

### **Configuração do Servidor Web**
```apache
# Apache .htaccess
<Directory "/var/www/uniorthocrin/storage">
    Require all denied
</Directory>

<Directory "/var/www/uniorthocrin/bootstrap/cache">
    Require all denied
</Directory>
```

## 📊 **Monitoramento**

### **Script de Monitoramento**
```bash
#!/bin/bash
# monitor-storage.sh

echo "📊 Monitoramento de Storage"
echo "=========================="

# Tamanho do storage
echo "📁 Tamanho do storage:"
du -sh storage/app/private/

# Arquivos por módulo
echo "📂 Arquivos por módulo:"
for dir in campaigns products training library news; do
    count=$(find storage/app/private/$dir -type f 2>/dev/null | wc -l)
    size=$(du -sh storage/app/private/$dir 2>/dev/null | cut -f1)
    echo "  $dir: $count arquivos ($size)"
done

# Espaço em disco
echo "💾 Espaço em disco:"
df -h | grep -E "(Filesystem|/dev/)"
```

## 🔄 **Migração de Storage**

### **Para S3**
```php
// Artisan command para migrar arquivos
php artisan storage:migrate-to-s3
```

### **Para outro servidor**
```bash
# Migração completa
rsync -avz --progress storage/app/private/ novo-servidor:/var/www/uniorthocrin/storage/app/private/
```
