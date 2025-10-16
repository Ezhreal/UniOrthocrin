#!/bin/bash

# Script para iniciar worker de filas
# Execute este script no servidor para processar uploads do OneDrive

echo "Iniciando worker de filas para OneDrive uploads..."

# Navegar para o diretório do projeto
cd /path/to/your/project/uniorthocrin

# Criar tabela de jobs se não existir
php artisan queue:table
php artisan migrate --force

# Iniciar worker de filas
# --timeout=600: timeout de 10 minutos por job
# --tries=3: tentar 3 vezes se falhar
# --memory=512: limite de memória de 512MB
php artisan queue:work database --timeout=600 --tries=3 --memory=512 --sleep=3 --tries=3

echo "Worker de filas iniciado!"
