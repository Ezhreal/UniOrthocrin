#!/bin/bash

# Script simples para processar jobs OneDrive via cron
# Executa a cada minuto

cd /home/codestackrg/Projects/UniOrthocrin/uniorthocrin

# Processar até 3 jobs por execução (para não sobrecarregar o servidor)
php artisan queue:work database --timeout=600 --tries=3 --memory=256 --max-jobs=3 --quiet

# Log da execução
echo "[$(date '+%Y-%m-%d %H:%M:%S')] Cron executado" >> storage/logs/cron.log
