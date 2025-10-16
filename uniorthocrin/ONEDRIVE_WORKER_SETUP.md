# 🚀 Configuração do Worker OneDrive

## 📋 Opções para rodar o worker:

### 1. **Manual (Desenvolvimento/Teste)**
```bash
# Rodar uma vez
php artisan queue:work database --timeout=600 --tries=3 --memory=512 --once

# Rodar continuamente
php artisan queue:work database --timeout=600 --tries=3 --memory=512 --verbose

# Usar comando customizado
php artisan onedrive:worker
php artisan onedrive:worker --daemon
```

### 2. **Script Shell**
```bash
# Tornar executável
chmod +x start-onedrive-worker.sh

# Executar
./start-onedrive-worker.sh
```

### 3. **Supervisor (Produção)**
```bash
# Copiar configuração
sudo cp supervisor-onedrive-worker.conf /etc/supervisor/conf.d/

# Recarregar supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start uniorthocrin-onedrive-worker:*

# Verificar status
sudo supervisorctl status uniorthocrin-onedrive-worker:*
```

### 4. **Crontab (Alternativa)**
```bash
# Editar crontab
crontab -e

# Adicionar linha (executa a cada minuto)
* * * * * cd /home/codestackrg/Projects/UniOrthocrin/uniorthocrin && php artisan queue:work database --timeout=600 --tries=3 --memory=512 --once
```

## 🔧 Configurações do Worker:

- **Timeout**: 600 segundos (10 minutos) para uploads grandes
- **Tentativas**: 3 tentativas antes de falhar
- **Memória**: 512MB para processar arquivos grandes
- **Sleep**: 3 segundos entre ciclos (quando não há jobs)

## 📊 Monitoramento:

### Ver jobs na fila:
```bash
php artisan queue:monitor database
```

### Ver jobs falhados:
```bash
php artisan queue:failed
```

### Reprocessar jobs falhados:
```bash
php artisan queue:retry all
```

### Limpar jobs falhados:
```bash
php artisan queue:flush
```

## 🚨 Troubleshooting:

### Worker não está processando:
1. Verificar se está rodando: `ps aux | grep queue:work`
2. Verificar logs: `tail -f storage/logs/laravel.log`
3. Verificar jobs na fila: `php artisan tinker` → `DB::table('jobs')->count()`

### Jobs falhando:
1. Verificar permissões dos arquivos
2. Verificar configuração do OneDrive no `.env`
3. Verificar logs de erro

### Performance:
- Para muitos uploads simultâneos, considere aumentar `numprocs` no Supervisor
- Monitore uso de memória e CPU
- Ajuste `--memory` conforme necessário

## 📝 Logs:

- **Laravel**: `storage/logs/laravel.log`
- **Worker**: `storage/logs/worker.log` (se usando Supervisor)
- **OneDrive**: Logs específicos no `OneDriveService`
