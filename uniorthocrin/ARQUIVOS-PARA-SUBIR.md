# Arquivos para subir no servidor (após o build)

Depois de rodar **`npm run build`** na pasta `uniorthocrin`, suba o projeto **inteiro** (exceto o que está no `.deployignore`), **incluindo** a pasta gerada pelo build.

---

## O que o build gera (obrigatório subir)

A pasta **`public/build/`** é criada pelo `npm run build`. Ela contém o CSS e o JS compilados que o site usa em produção.

```
uniorthocrin/
  public/
    build/              ← OBRIGATÓRIO subir esta pasta
      manifest.json
      assets/
        app-XXXXXXXX.js
        app-XXXXXXXX.css
        admin-modern-XXXXXXXX.css  (se existir)
```

**Suba sempre a pasta `public/build/` inteira** (com `manifest.json` e a pasta `assets/`).

---

## Resumo: o que enviar

| Enviar | Não enviar |
|--------|------------|
| Toda a pasta **uniorthocrin** (código da aplicação) | **vendor/** |
| Incluindo **public/build/** (resultado do build) | **node_modules/** |
| Incluindo **public/** (imagens, etc.) | **.env** (crie outro no servidor) |
| Incluindo **app/**, **config/**, **database/**, **resources/**, **routes/**, etc. | **storage/logs/***, **storage/framework/cache/***, etc. (conforme .deployignore) |
| | **.git/** (opcional; pode não subir para economizar espaço) |

---

## Checklist rápido

1. Rodar **`npm run build`** dentro de `uniorthocrin`.
2. Subir todos os arquivos da pasta **uniorthocrin**, **exceto**:
   - `vendor/`
   - `node_modules/`
   - `.env`
   - Conteúdo de `storage/logs`, `storage/framework/cache`, `storage/framework/sessions`, `storage/framework/views`
3. Garantir que a pasta **`public/build/`** foi enviada (com `manifest.json` e `assets/`).
4. No servidor: criar o `.env`, rodar `composer install --no-dev`, configurar permissões e cache (ver DEPLOY-MANUAL.md).

---

## Se você sobe só o que mudou (atualização)

Além dos arquivos que você alterou, **sempre** inclua na subida:

- **`public/build/`** (toda a pasta), se você rodou `npm run build` de novo.

Sem a pasta `public/build/` atualizada, o site pode continuar usando JS/CSS antigos ou dar erro 404 nos arquivos de asset.
