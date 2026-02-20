# Publicar tudo do "Como usar" (help)

Guia para subir **tudo** relacionado ao "Como usar": rotas, controller, layout (menu), listagem e página de cada tópico.

---

## Lista completa – o que subir

Subir estes **5 arquivos** (mesma estrutura de pastas no servidor):

| # | Caminho no projeto (pasta `uniorthocrin/`) |
|---|--------------------------------------------|
| 1 | `routes/admin.php` |
| 2 | `app/Http/Controllers/Admin/HelpController.php` |
| 3 | `resources/views/admin/layouts/app.blade.php` |
| 4 | `resources/views/admin/help/index.blade.php` |
| 5 | `resources/views/admin/help/show.blade.php` |

**Resumo:** rotas do admin (onde está o “como-usar”), controller, layout do admin (menu lateral com o link “Como usar”), página que lista os tópicos e página de cada tópico (cards e “o que pode subir”).

---

## Onde subir no servidor

- **Locaweb / FTP:** pasta do projeto (ex.: `public_html/uniorthocrin` ou `www/uniorthocrin`).
- **VPS:** ex.: `/var/www/uniorthocrin`.

Envie cada arquivo **no mesmo caminho relativo à raiz do projeto**:

| Arquivo no seu PC | No servidor (substituir no mesmo caminho) |
|-------------------|------------------------------------------|
| `uniorthocrin/routes/admin.php` | `.../routes/admin.php` |
| `uniorthocrin/app/Http/Controllers/Admin/HelpController.php` | `.../app/Http/Controllers/Admin/HelpController.php` |
| `uniorthocrin/resources/views/admin/layouts/app.blade.php` | `.../resources/views/admin/layouts/app.blade.php` |
| `uniorthocrin/resources/views/admin/help/index.blade.php` | `.../resources/views/admin/help/index.blade.php` |
| `uniorthocrin/resources/views/admin/help/show.blade.php` | `.../resources/views/admin/help/show.blade.php` |

Se a pasta `resources/views/admin/help/` não existir no servidor, crie e envie `index.blade.php` e `show.blade.php` dentro dela.

---

## Lembrando o problema que tivemos (Locaweb sem SSH)

- Não dá para rodar `php artisan route:clear` nem `view:clear` pelo terminal.
- Por isso existe o **`public/clear-cache.php`**: você chama pelo navegador e ele limpa cache de rotas, config e **views** (assim a nova tela de ajuda aparece).

### Passos na Locaweb (após subir os arquivos)

1. **Se ainda não fez:** no `.env` do servidor, adicione:
   ```env
   CACHE_CLEAR_TOKEN=escolhaUmaSenhaSecreta123
   ```
   (troque por uma senha só sua.)

2. **Se ainda não estiver no servidor:** suba o arquivo `public/clear-cache.php` para a pasta **public** do site (a mesma onde está o `index.php` do Laravel).

3. No navegador, acesse:
   ```
   https://seudominio.com.br/clear-cache.php?token=escolhaUmaSenhaSecreta123
   ```
   (use o mesmo valor que colocou em `CACHE_CLEAR_TOKEN`).

4. Confirme a mensagem de “Cache limpo” e **apague** o `clear-cache.php` da pasta public no servidor.

Assim as views são atualizadas e a nova tela “Como usar” (com os cards por tipo e “o que pode subir”) passa a aparecer.

---

## Se você sobe o projeto inteiro (não só o “Como usar”)

- Use o que está em **ARQUIVOS-PARA-SUBIR.md** e **DEPLOY-MANUAL.md**.
- Resumo: rodar `npm run build` em `uniorthocrin`, subir toda a pasta **uniorthocrin** exceto `vendor/`, `node_modules/`, `.env` e (conforme `.deployignore`) logs/cache de storage. Incluir sempre **`public/build/`**.
- No servidor: se tiver SSH, depois de subir rode `php artisan view:clear` (e se quiser `route:clear`, `config:clear`). Se for Locaweb sem SSH, use o `clear-cache.php` como acima.

---

## Resumo rápido

1. Subir os **5 arquivos** listados acima nos mesmos caminhos no servidor (e criar a pasta `resources/views/admin/help/` se não existir).
2. Limpar cache: no navegador, acessar `https://seudominio.com.br/clear-cache.php?token=...` (e depois apagar o `clear-cache.php`).
3. Pronto: o menu “Como usar” aparece no admin, a listagem e as páginas com o visual por tipo e “o que pode subir” funcionam.
