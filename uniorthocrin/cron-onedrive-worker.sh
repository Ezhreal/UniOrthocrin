#!/bin/bash

# Script otimizado para cron - processa jobs OneDrive
# Executa a cada minuto via crontab

# Configurações
PROJECT_PATH="/home/codestackrg/Projects/UniOrthocrin/uniorthocrin"
LOG_FILE="$PROJECT_PATH/storage/logs/cron-worker.log"
MAX_JOBS=5  # Processa no máximo 5 jobs por execução

# Função de log
log_message() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" >> "$LOG_FILE"
}

# Navegar para o diretório do projeto
cd "$PROJECT_PATH" || exit 1

# Verificar se há jobs na fila
JOBS_COUNT=$(php artisan tinker --execute="echo DB::table('jobs')->count();" 2>/dev/null | tail -1)

if [ "$JOBS_COUNT" -eq 0 ]; then
    # Sem jobs, sair silenciosamente
    exit 0
fi

log_message "Processando $JOBS_COUNT jobs na fila"

# Processar jobs (máximo 5 por execução para não sobrecarregar)
for i in $(seq 1 $MAX_JOBS); do
    # Verificar se ainda há jobs
    REMAINING_JOBS=$(php artisan tinker --execute="echo DB::table('jobs')->count();" 2>/dev/null | tail -1)
    
    if [ "$REMAINING_JOBS" -eq 0 ]; then
        log_message "Todos os jobs processados"
        break
    fi
    
    # Processar um job
    log_message "Processando job $i de $MAX_JOBS"
    php artisan queue:work database --timeout=600 --tries=3 --memory=256 --once --quiet
    
    # Pequena pausa entre jobs
    sleep 2
done

log_message "Execução do cron finalizada"
