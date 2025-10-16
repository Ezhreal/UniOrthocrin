# Configuração de Filas para OneDrive Uploads

## 🚀 **Problema Resolvido**

O timeout 504 acontecia porque os uploads do OneDrive estavam rodando **sincronamente** (`sync`), bloqueando a requisição HTTP. Agora configuramos para usar **filas assíncronas**.

## ⚙️ **Configurações Aplicadas**

### 1. **Job Otimizado**
- **Timeout**: `300s` → `600s` (10 minutos)
- **Tentativas**: 3 tentativas automáticas
- **Fila**: Removido `onQueue('uploads')` - usa fila padrão

### 2. **Controllers Atualizados**
- **LibraryController** ✅
- **ProductController** ✅  
- **TrainingController** ✅
- **CampaignController** ✅
- **NewsController** ✅

### 3. **Comando Personalizado**
```bash
php artisan onedrive:process --timeout=600 --tries=3
```

## 🔧 **Como Configurar no Servidor**

### **Opção 1: Comando Personalizado (Recomendado)**
```bash
# No servidor, execute:
php artisan onedrive:process
```

### **Opção 2: Comando Padrão**
```bash
# Worker básico
php artisan queue:work database --timeout=600 --tries=3 --memory=512
```

### **Opção 3: Script Automatizado**
```bash
# Tornar executável
chmod +x start-queue-worker.sh

# Editar o caminho no script
nano start-queue-worker.sh

# Executar
./start-queue-worker.sh
```

## 📋 **Configuração no .env**

```env
# Alterar de:
QUEUE_CONNECTION=sync

# Para:
QUEUE_CONNECTION=database
```

## 🎯 **Como Funciona Agora**

1. **Usuário faz upload** → Formulário é processado rapidamente
2. **Job é adicionado à fila** → Upload vai para background
3. **Worker processa** → Upload acontece assincronamente
4. **Usuário não espera** → Pode continuar navegando

## 🧪 **Teste**

1. **Faça upload de arquivo grande** (vídeo, PDF)
2. **Marque "Publicar no OneDrive"**
3. **Salve** → Deve processar rapidamente (sem timeout)
4. **Verifique logs** → Upload acontece em background

## 📊 **Monitoramento**

### **Ver filas pendentes:**
```bash
php artisan queue:monitor
```

### **Ver jobs falhados:**
```bash
php artisan queue:failed
```

### **Reprocessar jobs falhados:**
```bash
php artisan queue:retry all
```

## ⚠️ **Importante**

- **Worker deve estar rodando** no servidor
- **Configurar cron job** para manter worker ativo
- **Monitorar logs** para verificar uploads
- **Timeout de 10 minutos** para arquivos grandes

## 🎉 **Resultado**

- ✅ **Sem timeout 504**
- ✅ **Uploads em background**
- ✅ **UX melhorada**
- ✅ **Suporte a arquivos grandes**

**Agora os uploads do OneDrive funcionam perfeitamente!** 🚀
