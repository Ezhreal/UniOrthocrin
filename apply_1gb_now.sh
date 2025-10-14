#!/bin/bash

echo "=== APLICANDO CONFIGURAÇÕES PARA 1GB AGORA ==="

# Aplicar configurações do PHP
echo "Configurando PHP para 1GB..."
sudo sed -i 's/upload_max_filesize = 50M/upload_max_filesize = 500M/' /etc/php/8.3/fpm/php.ini
sudo sed -i 's/post_max_size = 100M/post_max_size = 1200M/' /etc/php/8.3/fpm/php.ini
sudo sed -i 's/max_execution_time = 300/max_execution_time = 1200/' /etc/php/8.3/fpm/php.ini
sudo sed -i 's/memory_limit = 256M/memory_limit = 1024M/' /etc/php/8.3/fpm/php.ini

# Aplicar configuração do nginx
echo "Configurando nginx para 1GB..."
sudo sed -i 's/client_max_body_size 100M/client_max_body_size 1200M/' /etc/nginx/sites-available/uniorthocrin.local

# Verificar configurações
echo "Verificando configurações aplicadas..."
echo "PHP upload_max_filesize:"
grep "upload_max_filesize" /etc/php/8.3/fpm/php.ini
echo "PHP post_max_size:"
grep "post_max_size" /etc/php/8.3/fpm/php.ini
echo "Nginx client_max_body_size:"
grep "client_max_body_size" /etc/nginx/sites-available/uniorthocrin.local

# Testar e reiniciar
echo "Testando nginx..."
sudo nginx -t

if [ $? -eq 0 ]; then
    echo "Reiniciando serviços..."
    sudo systemctl restart php8.3-fpm
    sudo systemctl restart nginx
    echo "=== CONFIGURAÇÕES APLICADAS! TESTE AGORA! ==="
else
    echo "ERRO na configuração do nginx!"
fi
