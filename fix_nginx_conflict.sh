#!/bin/bash

echo "=== CORRIGINDO CONFLITO DO NGINX ==="

# Remover o arquivo antigo que não tem client_max_body_size
echo "Removendo configuração antiga..."
sudo rm /etc/nginx/sites-enabled/uniorthocrin.conf

# Testar configuração
echo "Testando configuração do nginx..."
sudo nginx -t

if [ $? -eq 0 ]; then
    echo "Configuração OK! Reiniciando nginx..."
    sudo systemctl restart nginx
    echo "=== CONFLITO RESOLVIDO! ==="
    echo "Agora o nginx está usando apenas a configuração com client_max_body_size = 100M"
else
    echo "ERRO: Configuração inválida!"
    exit 1
fi
