<?php
/**
 * Script para limpar cache em hospedagem compartilhada (sem SSH).
 * Acesse: https://seusite.com.br/clear-cache.php?token=SEU_TOKEN
 *
 * No .env crie: CACHE_CLEAR_TOKEN=escolhaUmaSenhaSecreta123
 * Depois de usar, APAGUE este arquivo por segurança.
 */

// Só funciona se vier pela web (não por include direto)
if (php_sapi_name() === 'cli' && !defined('STDIN')) {
    exit;
}

$token = $_GET['token'] ?? '';
if ($token === '') {
    header('Content-Type: text/plain; charset=utf-8');
    echo "Uso: " . (isset($_SERVER['HTTP_HOST']) ? 'https://' . $_SERVER['HTTP_HOST'] : '') . "/clear-cache.php?token=SEU_TOKEN\n";
    echo "\nConfigure no .env: CACHE_CLEAR_TOKEN=umaSenhaSecreta\n";
    exit;
}

// Carrega o .env manualmente para ler o token (antes de bootar o Laravel)
$envPath = dirname(__DIR__) . '/.env';
if (!is_file($envPath)) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "Erro: arquivo .env não encontrado.";
    exit;
}
$envContent = file_get_contents($envPath);
$expectedToken = null;
if (preg_match('/CACHE_CLEAR_TOKEN=(.+)/m', $envContent, $m)) {
    $expectedToken = trim($m[1], " \t\"'");
}
if ($expectedToken === null || $expectedToken === '') {
    header('Content-Type: text/plain; charset=utf-8');
    echo "Configure no .env a linha: CACHE_CLEAR_TOKEN=umaSenhaSecreta";
    exit;
}
if (!hash_equals($expectedToken, $token)) {
    header('Content-Type: text/plain; charset=utf-8');
    echo "Token inválido.";
    exit;
}

// Bootstrap Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

header('Content-Type: text/plain; charset=utf-8');

try {
    \Illuminate\Support\Facades\Artisan::call('route:clear');
    echo "route:clear OK\n";
} catch (\Throwable $e) {
    echo "route:clear: " . $e->getMessage() . "\n";
}
try {
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    echo "config:clear OK\n";
} catch (\Throwable $e) {
    echo "config:clear: " . $e->getMessage() . "\n";
}
try {
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    echo "view:clear OK\n";
} catch (\Throwable $e) {
    echo "view:clear: " . $e->getMessage() . "\n";
}

echo "\nCache limpo. Apague este arquivo (public/clear-cache.php) por segurança.\n";
