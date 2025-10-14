#!/bin/bash

echo "=== CONFIGURANDO LIMITES PARA UPLOAD DE 1GB+ (GESTÃO DE MÍDIA) ==="

# Backup do arquivo original
echo "Fazendo backup do php.ini..."
sudo cp /etc/php/8.3/fpm/php.ini /etc/php/8.3/fpm/php.ini.backup.1gb

# Aplicar configurações do PHP para uploads MASSIVOS
echo "Aplicando configurações do PHP para uploads de 1GB+..."
sudo sed -i 's/upload_max_filesize = 200M/upload_max_filesize = 500M/' /etc/php/8.3/fpm/php.ini
sudo sed -i 's/post_max_size = 600M/post_max_size = 1200M/' /etc/php/8.3/fpm/php.ini
sudo sed -i 's/max_execution_time = 600/max_execution_time = 1200/' /etc/php/8.3/fpm/php.ini
sudo sed -i 's/memory_limit = 512M/memory_limit = 1024M/' /etc/php/8.3/fpm/php.ini

# Aplicar configuração do nginx para uploads MASSIVOS
echo "Aplicando configuração do nginx para uploads de 1GB+..."
sudo sed -i 's/client_max_body_size 600M/client_max_body_size 1200M/' /etc/nginx/sites-available/uniorthocrin.local

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
    
    echo "=== CONFIGURAÇÕES PARA GESTÃO DE MÍDIA APLICADAS! ==="
    echo "Limites configurados para 1GB+:"
    echo "- upload_max_filesize: 500M (por arquivo)"
    echo "- post_max_size: 1200M (1.2GB total)"
    echo "- max_execution_time: 1200s (20 minutos)"
    echo "- memory_limit: 1024M (1GB)"
    echo "- client_max_body_size: 1200M (1.2GB)"
    echo ""
    echo "Agora você pode fazer upload de TUDO que quiser! 🚀"
    echo "Gestão de mídia sem limites! 💪"
else
    echo "ERRO: Configuração do nginx inválida!"
    exit 1
fi
