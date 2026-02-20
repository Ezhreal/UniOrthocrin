# Lista: pastas e arquivos da raiz para subir

Raiz = pasta **uniorthocrin** (onde ficam `artisan`, `composer.json`, etc.).

---

## Pastas da raiz — SUBIR

| Pasta        | Subir? | Observação |
|-------------|--------|------------|
| **app/**    | Sim    | Código da aplicação |
| **bootstrap/** | Sim | Mantenha a pasta; no servidor pode limpar `bootstrap/cache/*` depois e dar permissões |
| **config/** | Sim    | Configurações Laravel |
| **database/** | Sim  | Migrations, seeders, factories |
| **public/** | Sim    | **Inclui `public/build/`** (resultado do `npm run build`) |
| **resources/** | Sim | Views, JS/CSS fonte, etc. |
| **routes/** | Sim    | Rotas web e admin |
| **storage/** | Sim   | Subir a **estrutura de pastas** (app, framework, logs); não precisa subir o conteúdo de logs/cache/sessions/views |
| **tests/**  | Sim    | Opcional (pode não subir se quiser) |
| **docs/**   | Sim    | Opcional |

---

## Arquivos da raiz — SUBIR

| Arquivo | Subir? |
|---------|--------|
| **artisan** | Sim |
| **composer.json** | Sim |
| **composer.lock** | Sim |
| **package.json** | Sim |
| **package-lock.json** | Sim |
| **vite.config.js** | Sim |
| **tailwind.config.js** | Sim |
| **postcss.config.js** | Sim |
| **phpunit.xml** | Sim (opcional) |
| **.editorconfig** | Sim |
| **.gitattributes** | Sim |
| **.gitignore** | Sim |
| **.deployignore** | Sim |
| **.env.example** | Sim (só como referência; no servidor use um novo .env) |
| **deploy-setup.sh** | Sim |
| **DEPLOY-MANUAL.md** | Sim (opcional) |
| **ARQUIVOS-PARA-SUBIR.md** | Sim (opcional) |
| **LISTA-SUBIR.md** | Sim (opcional) |
| **README.md** | Sim (opcional) |
| **backup-storage.sh** | Sim (opcional) |
| **create-test-data.sh** | Não (só desenvolvimento) |
| **cron-onedrive-worker.sh** | Sim (se usar Onedrive/workers) |
| **process-onedrive-jobs.sh** | Sim (se usar) |
| **setup-crontab.sh** | Sim (opcional) |
| **start-onedrive-worker.sh** | Sim (se usar) |
| **start-queue-worker.sh** | Sim (se usar filas) |
| **supervisor-onedrive-worker.conf** | Sim (se usar) |
| **migrate-production.php** | Sim (opcional) |
| **CRONTAB_SETUP.md**, **LOADING_SYSTEM.md**, **ONEDRIVE_*.md**, **QUEUE_SETUP.md**, **storage-config.md** | Opcional |

---

## NÃO subir (raiz)

| Item | Motivo |
|------|--------|
| **vendor/** | Instalar no servidor com `composer install --no-dev` |
| **node_modules/** | Não é usado em produção; o que importa é o `public/build/` |
| **.env** | Criar um .env novo no servidor (copiar de .env.example e ajustar) |
| **.env.production** | Não subir (dados sensíveis); configurar no servidor se precisar |
| **.git/** | Opcional não subir (economiza espaço) |
| **test-data/** | Só para desenvolvimento |

---

## Resumo em uma frase

Subir **tudo** que está na raiz de **uniorthocrin**, **exceto**: `vendor/`, `node_modules/`, `.env`, `.env.production` e (opcional) `.git/` e `test-data/`.  
Garantir que **public/build/** (com `manifest.json` e `assets/`) foi gerado pelo `npm run build` e está incluído.
