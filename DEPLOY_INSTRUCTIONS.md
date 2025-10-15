# Instruções de Deploy Automatizado - Locaweb

## 📋 O que foi configurado

Criado o arquivo `.github/workflows/deploy.yml` que automatiza o deploy do projeto Laravel para a Locaweb via FTP.

## 🔧 Configuração necessária no GitHub

### 1. Configurar Secrets no GitHub

Acesse seu repositório no GitHub e vá em:
**Settings** → **Secrets and variables** → **Actions** → **New repository secret**

Crie os seguintes secrets:

| Secret Name | Descrição | Exemplo |
|-------------|-----------|---------|
| `HOST` | Endereço do servidor FTP | `ftp.seusite.com.br` |
| `USER` | Usuário do FTP | `seu_usuario` |
| `PASS` | Senha do FTP | `sua_senha` |

### 2. Informações da Locaweb

Para encontrar essas informações:
1. Acesse o painel da Locaweb
2. Vá em **Hospedagem** → **Gerenciar**
3. Procure por **FTP** ou **Acesso FTP**
4. Use as credenciais fornecidas

## 🚀 Como funciona o deploy

### Triggers (quando o deploy acontece):
- **Push na branch main/master**: Deploy automático
- **Manual**: Vá em Actions → Deploy via FTP → Run workflow

### Processo do deploy:
1. ✅ Baixa o código do repositório
2. ✅ Configura PHP 8.1
3. ✅ Instala dependências do Composer (produção)
4. ✅ Instala e builda assets (npm)
5. ✅ Prepara arquivos para produção
6. ✅ Remove arquivos desnecessários
7. ✅ Cria .htaccess se necessário
8. ✅ Faz upload via FTP para `public_html`

## 📁 Estrutura de arquivos no servidor

O deploy copia todo o conteúdo da pasta `uniorthocrin/` para `public_html/` no servidor, exceto:
- `node_modules/`
- `.git/`
- `tests/`
- `.env.example`
- `README.md`
- `package*.json`
- `webpack.mix.js`
- `vite.config.js`

## ⚙️ Configurações específicas do projeto

### Se precisar ajustar o remoteDir:
Edite o arquivo `.github/workflows/deploy.yml` na linha:
```yaml
remoteDir: "public_html"  # Mude para "web" se for Windows
```

### Se precisar ajustar o localDir:
O workflow já está configurado para usar a pasta `uniorthocrin/` como base.

## 🔍 Verificação do deploy

1. Acesse a aba **Actions** no GitHub
2. Clique no workflow "Deploy via FTP"
3. Verifique se todos os steps passaram (✅)
4. Teste o site no navegador

## 🛠️ Troubleshooting

### Erro de permissão FTP:
- Verifique se as credenciais estão corretas
- Confirme se o usuário tem permissão de escrita

### Erro de arquivo não encontrado:
- Verifique se o `remoteDir` está correto
- Confirme se a estrutura de pastas existe no servidor

### Erro de PHP:
- Verifique se o servidor suporta PHP 8.1
- Confirme se as extensões necessárias estão instaladas

## 📞 Suporte

Se tiver problemas:
1. Verifique os logs na aba Actions do GitHub
2. Teste as credenciais FTP manualmente
3. Entre em contato com o suporte da Locaweb se necessário
