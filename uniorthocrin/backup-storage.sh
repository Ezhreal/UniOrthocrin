#!/bin/bash

# Script de backup automático do storage
# Execute via cron: 0 2 * * * /path/to/backup-storage.sh

BACKUP_DIR="/backups/uniorthocrin"
PROJECT_DIR="/var/www/uniorthocrin"
DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_FILE="storage_backup_$DATE.tar.gz"

echo "🔄 Iniciando backup do storage..."

# Criar diretório de backup se não existir
mkdir -p $BACKUP_DIR

# Fazer backup do storage
echo "📦 Criando backup: $BACKUP_FILE"
cd $PROJECT_DIR
tar -czf "$BACKUP_DIR/$BACKUP_FILE" storage/app/private/

# Verificar se o backup foi criado com sucesso
if [ $? -eq 0 ]; then
    echo "✅ Backup criado com sucesso: $BACKUP_FILE"
    
    # Calcular tamanho do backup
    BACKUP_SIZE=$(du -h "$BACKUP_DIR/$BACKUP_FILE" | cut -f1)
    echo "📊 Tamanho do backup: $BACKUP_SIZE"
    
    # Manter apenas os últimos 7 backups
    echo "🧹 Removendo backups antigos..."
    cd $BACKUP_DIR
    ls -t storage_backup_*.tar.gz | tail -n +8 | xargs -r rm
    
    echo "✅ Backup concluído!"
else
    echo "❌ Erro ao criar backup!"
    exit 1
fi

# Log do backup
echo "$(date): Backup $BACKUP_FILE criado ($BACKUP_SIZE)" >> $BACKUP_DIR/backup.log
