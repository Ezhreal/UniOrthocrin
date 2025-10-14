#!/bin/bash

# Script de configuração para deploy em servidor
# Execute como root ou com sudo

echo "🚀 Configurando UniOrthocrin para produção..."

# 1. Configurar permissões do storage
echo "📁 Configurando permissões do storage..."
sudo chown -R www-data:www-data storage/
sudo chown -R www-data:www-data bootstrap/cache/
sudo chmod -R 775 storage/
sudo chmod -R 775 bootstrap/cache/

# 2. Criar diretórios necessários se não existirem
echo "📂 Criando diretórios necessários..."
sudo mkdir -p storage/app/private/{campaigns,products,training,library,news,users}
sudo chown -R www-data:www-data storage/app/private/
sudo chmod -R 775 storage/app/private/

# 3. Configurar link simbólico para storage público
echo "🔗 Configurando link simbólico..."
sudo php artisan storage:link

# 4. Configurar permissões do usuário web
echo "👤 Configurando usuário web..."
sudo usermod -a -G www-data $USER
sudo chown -R $USER:www-data .
sudo chmod -R 775 .

# 5. Configurar cache e otimizações
echo "⚡ Otimizando aplicação..."
sudo php artisan config:cache
sudo php artisan route:cache
sudo php artisan view:cache
sudo php artisan optimize

# 6. Configurar cron jobs (se necessário)
echo "⏰ Configurando cron jobs..."
(crontab -l 2>/dev/null; echo "* * * * * cd $(pwd) && php artisan schedule:run >> /dev/null 2>&1") | crontab -

echo "✅ Configuração concluída!"
echo ""
echo "📋 Próximos passos:"
echo "1. Configure seu servidor web (Apache/Nginx)"
echo "2. Configure SSL/HTTPS"
echo "3. Configure backup automático"
echo "4. Configure monitoramento"
echo ""
echo "🔧 Comandos úteis:"
echo "sudo systemctl restart apache2  # ou nginx"
echo "sudo php artisan queue:work     # para processar filas"
echo "sudo php artisan horizon        # para Laravel Horizon (se usar)"
