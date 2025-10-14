#!/bin/bash

echo "=== CORRIGINDO LIMITES DE UPLOAD ==="

# Backup do arquivo original
echo "Fazendo backup do php.ini..."
sudo cp /etc/php/8.3/fpm/php.ini /etc/php/8.3/fpm/php.ini.backup

# Aplicar configurações do PHP
echo "Aplicando configurações do PHP..."
sudo sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 50M/' /etc/php/8.3/fpm/php.ini
sudo sed -i 's/post_max_size = 8M/post_max_size = 100M/' /etc/php/8.3/fpm/php.ini
sudo sed -i 's/max_execution_time = 30/max_execution_time = 300/' /etc/php/8.3/fpm/php.ini
sudo sed -i 's/memory_limit = 128M/memory_limit = 256M/' /etc/php/8.3/fpm/php.ini

# Aplicar configuração do nginx
echo "Aplicando configuração do nginx..."
sudo cp /home/codestackrg/Projects/UniOrthocrin/nginx_config_temp.conf /etc/nginx/sites-available/uniorthocrin.local

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
    
    echo "=== CONFIGURAÇÕES APLICADAS COM SUCESSO! ==="
    echo "Limites configurados:"
    echo "- upload_max_filesize: 50M"
    echo "- post_max_size: 100M"
    echo "- max_execution_time: 300s"
    echo "- memory_limit: 256M"
    echo "- client_max_body_size: 100M"
else
    echo "ERRO: Configuração do nginx inválida!"
    exit 1
fi
