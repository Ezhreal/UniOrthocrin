#!/bin/bash

# Script para iniciar o worker de OneDrive
# Use este script para iniciar o worker manualmente

echo "🚀 Iniciando worker de OneDrive..."

# Navegar para o diretório do projeto
cd /home/codestackrg/Projects/UniOrthocrin/uniorthocrin

# Verificar se já existe um worker rodando
if pgrep -f "queue:work database" > /dev/null; then
    echo "⚠️  Worker já está rodando!"
    echo "Para parar: pkill -f 'queue:work database'"
    exit 1
fi

# Iniciar o worker
echo "✅ Iniciando worker com configurações otimizadas..."
php artisan queue:work database \
    --timeout=600 \
    --tries=3 \
    --memory=512 \
    --sleep=3 \
    --verbose

echo "🛑 Worker parou."
