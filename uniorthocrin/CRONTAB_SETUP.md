# ⏰ Configuração Crontab para OneDrive Worker

## 🚀 Para Servidor Compartilhado (Locaweb)

### 1. **Acessar o cPanel**
- Faça login no cPanel da Locaweb
- Procure por "Cron Jobs" ou "Tarefas Agendadas"

### 2. **Configurar o Cron Job**

**Frequência**: A cada minuto
**Comando**:
```bash
/home/codestackrg/Projects/UniOrthocrin/uniorthocrin/process-onedrive-jobs.sh
```

**Ou se preferir comando direto**:
```bash
cd /home/codestackrg/Projects/UniOrthocrin/uniorthocrin && php artisan queue:work database --timeout=600 --tries=3 --memory=256 --max-jobs=3 --quiet
```

### 3. **Configuração no cPanel**

| Campo | Valor |
|-------|-------|
| **Minute** | * |
| **Hour** | * |
| **Day** | * |
| **Month** | * |
| **Weekday** | * |
| **Command** | `/home/codestackrg/Projects/UniOrthocrin/uniorthocrin/process-onedrive-jobs.sh` |

### 4. **Alternativa: Via SSH (se disponível)**

```bash
# Editar crontab
crontab -e

# Adicionar linha
* * * * * /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/process-onedrive-jobs.sh

# Verificar crontab
crontab -l
```

## 📊 Monitoramento

### Ver logs do cron:
```bash
tail -f /home/codestackrg/Projects/UniOrthocrin/uniorthocrin/storage/logs/cron.log
```

### Ver jobs na fila:
```bash
cd /home/codestackrg/Projects/UniOrthocrin/uniorthocrin
php artisan tinker --execute="echo 'Jobs: ' . DB::table('jobs')->count();"
```

### Ver jobs falhados:
```bash
php artisan queue:failed
```

## ⚙️ Configurações Otimizadas

### Para servidor compartilhado:
- **--max-jobs=3**: Processa no máximo 3 jobs por execução
- **--memory=256**: Limita uso de memória
- **--timeout=600**: 10 minutos para uploads grandes
- **--tries=3**: 3 tentativas antes de falhar
- **--quiet**: Reduz logs para não sobrecarregar

### Frequências alternativas:
- **A cada 2 minutos**: `*/2 * * * *`
- **A cada 5 minutos**: `*/5 * * * *`
- **Apenas em horário comercial**: `* 9-18 * * 1-5`

## 🚨 Troubleshooting

### Cron não está executando:
1. Verificar se o arquivo tem permissão de execução: `chmod +x process-onedrive-jobs.sh`
2. Verificar logs do cron no cPanel
3. Testar o script manualmente: `./process-onedrive-jobs.sh`

### Jobs não estão sendo processados:
1. Verificar se há jobs na fila
2. Verificar logs do Laravel: `tail -f storage/logs/laravel.log`
3. Verificar configuração do OneDrive no `.env`

### Servidor sobrecarregado:
1. Reduzir `--max-jobs` para 1 ou 2
2. Aumentar intervalo do cron para 2-5 minutos
3. Reduzir `--memory` para 128

## 📝 Logs Importantes

- **Cron**: `storage/logs/cron.log`
- **Laravel**: `storage/logs/laravel.log`
- **OneDrive**: Logs específicos no `OneDriveService`

## 🔧 Comandos Úteis

```bash
# Processar jobs manualmente
php artisan queue:work database --once

# Limpar jobs falhados
php artisan queue:flush

# Reprocessar jobs falhados
php artisan queue:retry all

# Ver status da fila
php artisan queue:monitor database
```
