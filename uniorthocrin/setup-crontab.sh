#!/bin/bash

# Script para configurar crontab na Locaweb
# Execute este script no servidor de produção

echo "Configurando crontab para UniOrthocrin..."

# Criar o arquivo de crontab
cat > /tmp/uniorthocrin-crontab << EOF
# UniOrthocrin - Jobs do OneDrive
# Executar a cada 5 minutos
*/5 * * * * cd /home/seudominio/public_html && php artisan queue:work --once --timeout=300 >> /dev/null 2>&1

# Limpar jobs falhados a cada hora
0 * * * * cd /home/seudominio/public_html && php artisan queue:prune-failed --hours=24 >> /dev/null 2>&1

# Limpar cache a cada 6 horas
0 */6 * * * cd /home/seudominio/public_html && php artisan cache:clear >> /dev/null 2>&1

# Backup do banco de dados diário às 2h da manhã
0 2 * * * cd /home/seudominio/public_html && php artisan backup:run --only-db >> /dev/null 2>&1
EOF

# Instalar o crontab
crontab /tmp/uniorthocrin-crontab

# Verificar se foi instalado
echo "Crontab configurado:"
crontab -l

# Limpar arquivo temporário
rm /tmp/uniorthocrin-crontab

echo "✅ Crontab configurado com sucesso!"
echo ""
echo "📋 Jobs configurados:"
echo "  - Queue worker: A cada 5 minutos"
echo "  - Limpeza de jobs falhados: A cada hora"
echo "  - Limpeza de cache: A cada 6 horas"
echo "  - Backup do banco: Diário às 2h"
