#!/bin/bash

echo "=== CONFIGURANDO LIMITES PARA UPLOAD DE 500MB+ ==="

# Backup do arquivo original
echo "Fazendo backup do php.ini..."
sudo cp /etc/php/8.3/fpm/php.ini /etc/php/8.3/fpm/php.ini.backup.500mb

# Aplicar configurações do PHP para uploads grandes
echo "Aplicando configurações do PHP para uploads grandes..."
sudo sed -i 's/upload_max_filesize = 50M/upload_max_filesize = 200M/' /etc/php/8.3/fpm/php.ini
sudo sed -i 's/post_max_size = 100M/post_max_size = 600M/' /etc/php/8.3/fpm/php.ini
sudo sed -i 's/max_execution_time = 300/max_execution_time = 600/' /etc/php/8.3/fpm/php.ini
sudo sed -i 's/memory_limit = 256M/memory_limit = 512M/' /etc/php/8.3/fpm/php.ini

# Aplicar configuração do nginx para uploads grandes
echo "Aplicando configuração do nginx para uploads grandes..."
sudo sed -i 's/client_max_body_size 100M/client_max_body_size 600M/' /etc/nginx/sites-available/uniorthocrin.local

# Testar configuração do nginx
echo "Testando configuração do nginx..."
sudo nginx -t

if [ $? -eq 0 ]; then
    echo "Configuração do nginx OK!"
    
    # Reiniciar serviços
    echo "Reiniciando PHP-FPM..."
    sudo systemctl restart php8.3-fpm
    
    echo "Reiniciando nginx..."
    sudo systemctl restart nginx
    
    echo "=== CONFIGURAÇÕES PARA UPLOAD GRANDE APLICADAS! ==="
    echo "Limites configurados para 500MB+:"
    echo "- upload_max_filesize: 200M (por arquivo)"
    echo "- post_max_size: 600M (total)"
    echo "- max_execution_time: 600s (10 minutos)"
    echo "- memory_limit: 512M"
    echo "- client_max_body_size: 600M"
    echo ""
    echo "Agora você pode fazer upload de até 500MB+ de arquivos!"
else
    echo "ERRO: Configuração do nginx inválida!"
    exit 1
fi
