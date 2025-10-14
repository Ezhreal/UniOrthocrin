#!/bin/bash

echo "=== FORÇANDO RELOAD DO NGINX ==="

# Testar configuração
echo "Testando configuração do nginx..."
sudo nginx -t

if [ $? -eq 0 ]; then
    echo "Configuração OK! Forçando reload..."
    
    # Parar nginx
    sudo systemctl stop nginx
    sleep 2
    
    # Iniciar nginx
    sudo systemctl start nginx
    
    # Verificar status
    sudo systemctl status nginx --no-pager -l
    
    echo "=== NGINX RELOADED! ==="
    echo "Agora teste o upload novamente!"
else
    echo "ERRO: Configuração inválida!"
    exit 1
fi
