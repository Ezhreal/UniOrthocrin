# Erro: Route [admin.xxx] not defined

Este doc vale para qualquer rota do admin que apareça como "not defined", por exemplo:

- `Route [admin.notifications.mark-read] not defined`
- `Route [admin.help.index] not defined`
- `Route [admin.help.show] not defined`

## Causa

A rota existe em `routes/admin.php`. O erro costuma aparecer no servidor quando:

1. **Cache de rotas antigo** — foi rodado `php artisan route:cache` antes de subir o código novo, e o cache não foi atualizado.
2. **Arquivo `routes/admin.php` desatualizado** — a versão no servidor não tem a rota (ex.: help.index, notifications.mark-read).

---

## Solução em hospedagem compartilhada (Locaweb, sem SSH)

Se você **não tem SSH** (como em muitos planos Locaweb compartilhados), use o script que limpa o cache pelo navegador:

### 1. No servidor, edite o `.env` e adicione (troque pela sua senha secreta):

```env
CACHE_CLEAR_TOKEN=escolhaUmaSenhaSecreta123
```

### 2. Suba o arquivo `public/clear-cache.php` para a pasta **public** do seu site (via FTP ou gerenciador de arquivos da Locaweb).

### 3. No navegador, acesse (troque pelo seu domínio e pelo token que você colocou no .env):

```
https://seudominio.com.br/clear-cache.php?token=escolhaUmaSenhaSecreta123
```

### 4. Deve aparecer algo como:

```
route:clear OK
config:clear OK
view:clear OK

Cache limpo. Apague este arquivo (public/clear-cache.php) por segurança.
```

### 5. **Apague** o arquivo `clear-cache.php` da pasta public no servidor depois de usar (segurança).

---

## Solução no servidor (com SSH)

Entre no servidor (SSH) e, na pasta do projeto (ex.: `/var/www/uniorthocrin`), rode:

```bash
php artisan route:clear
php artisan config:clear
php artisan view:clear
```

Depois, se quiser gerar de novo o cache (opcional):

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

O importante é o **`route:clear`**: ele apaga o cache de rotas e o Laravel volta a ler os arquivos `routes/*.php`.

---

## Conferir se a rota está no código

No servidor (FTP ou gerenciador de arquivos), abra `routes/admin.php` e confira:

- **Para help:** devem existir as linhas (por volta da 118–119):
  ```php
  Route::get('como-usar', [HelpController::class, 'index'])->name('help.index');
  Route::get('como-usar/{topic}', [HelpController::class, 'show'])->name('help.show')->where('topic', '[a-z0-9_-]+');
  ```
- **Para notificações:** a linha com `notifications.mark-read` (por volta da 70).

Todas ficam **dentro** do grupo com `->name('admin.')`, então a rota final é `admin.help.index`, `admin.notifications.mark-read`, etc.

Se alguma linha não estiver no arquivo no servidor, suba de novo o `routes/admin.php` do seu projeto local e depois **limpe o cache** (clear-cache.php ou `php artisan route:clear`).
