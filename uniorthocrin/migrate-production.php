<?php

/**
 * Script para migrar dados para produção
 * Execute: php migrate-production.php
 */

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// Configurações do banco de produção
$config = [
    'host' => 'localhost',
    'database' => 'seu_banco_producao',
    'username' => 'seu_usuario_producao',
    'password' => 'sua_senha_producao',
];

try {
    // Conectar ao banco
    $pdo = new PDO(
        "mysql:host={$config['host']};dbname={$config['database']};charset=utf8mb4",
        $config['username'],
        $config['password'],
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    echo "✅ Conectado ao banco de dados de produção\n";

    // 1. Criar usuário administrador padrão
    echo "📝 Criando usuário administrador...\n";
    
    $adminExists = $pdo->query("SELECT id FROM users WHERE email = 'admin@uniorthocrin.com.br'")->fetch();
    
    if (!$adminExists) {
        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, password, user_type_id, email_verified_at, created_at, updated_at) 
            VALUES (?, ?, ?, ?, NOW(), NOW(), NOW())
        ");
        
        $stmt->execute([
            'Administrador',
            'admin@uniorthocrin.com.br',
            Hash::make('admin123'),
            1 // ID do tipo Administrador
        ]);
        
        echo "✅ Usuário administrador criado: admin@uniorthocrin.com.br / admin123\n";
    } else {
        echo "ℹ️  Usuário administrador já existe\n";
    }

    // 2. Criar tipos de usuário padrão
    echo "📝 Criando tipos de usuário...\n";
    
    $userTypes = [
        ['name' => 'Administrador', 'description' => 'Acesso total ao sistema'],
        ['name' => 'Franqueado', 'description' => 'Acesso a conteúdo exclusivo'],
        ['name' => 'Cliente', 'description' => 'Acesso básico ao sistema'],
    ];
    
    foreach ($userTypes as $type) {
        $exists = $pdo->query("SELECT id FROM user_types WHERE name = '{$type['name']}'")->fetch();
        
        if (!$exists) {
            $stmt = $pdo->prepare("
                INSERT INTO user_types (name, description, created_at, updated_at) 
                VALUES (?, ?, NOW(), NOW())
            ");
            $stmt->execute([$type['name'], $type['description']]);
            echo "✅ Tipo de usuário criado: {$type['name']}\n";
        }
    }

    // 3. Criar categorias padrão
    echo "📝 Criando categorias padrão...\n";
    
    // Categorias de produtos
    $productCategories = [
        ['name' => 'Produtos Principais', 'description' => 'Produtos principais da linha'],
        ['name' => 'Acessórios', 'description' => 'Acessórios e complementos'],
    ];
    
    foreach ($productCategories as $category) {
        $exists = $pdo->query("SELECT id FROM product_categories WHERE name = '{$category['name']}'")->fetch();
        
        if (!$exists) {
            $stmt = $pdo->prepare("
                INSERT INTO product_categories (name, description, created_at, updated_at) 
                VALUES (?, ?, NOW(), NOW())
            ");
            $stmt->execute([$category['name'], $category['description']]);
            echo "✅ Categoria de produto criada: {$category['name']}\n";
        }
    }

    // Categorias de treinamento
    $trainingCategories = [
        ['name' => 'Treinamentos Básicos', 'description' => 'Treinamentos introdutórios'],
        ['name' => 'Treinamentos Avançados', 'description' => 'Treinamentos especializados'],
    ];
    
    foreach ($trainingCategories as $category) {
        $exists = $pdo->query("SELECT id FROM training_categories WHERE name = '{$category['name']}'")->fetch();
        
        if (!$exists) {
            $stmt = $pdo->prepare("
                INSERT INTO training_categories (name, description, created_at, updated_at) 
                VALUES (?, ?, NOW(), NOW())
            ");
            $stmt->execute([$category['name'], $category['description']]);
            echo "✅ Categoria de treinamento criada: {$category['name']}\n";
        }
    }

    // Categorias de biblioteca
    $libraryCategories = [
        ['name' => 'Manuais', 'description' => 'Manuais e documentação'],
        ['name' => 'Catálogos', 'description' => 'Catálogos de produtos'],
    ];
    
    foreach ($libraryCategories as $category) {
        $exists = $pdo->query("SELECT id FROM library_categories WHERE name = '{$category['name']}'")->fetch();
        
        if (!$exists) {
            $stmt = $pdo->prepare("
                INSERT INTO library_categories (name, description, created_at, updated_at) 
                VALUES (?, ?, NOW(), NOW())
            ");
            $stmt->execute([$category['name'], $category['description']]);
            echo "✅ Categoria de biblioteca criada: {$category['name']}\n";
        }
    }

    // Categorias de notícias
    $newsCategories = [
        ['name' => 'Notícias Gerais', 'description' => 'Notícias gerais da empresa'],
        ['name' => 'Lançamentos', 'description' => 'Lançamentos de produtos'],
    ];
    
    foreach ($newsCategories as $category) {
        $exists = $pdo->query("SELECT id FROM news_categories WHERE name = '{$category['name']}'")->fetch();
        
        if (!$exists) {
            $stmt = $pdo->prepare("
                INSERT INTO news_categories (name, description, created_at, updated_at) 
                VALUES (?, ?, NOW(), NOW())
            ");
            $stmt->execute([$category['name'], $category['description']]);
            echo "✅ Categoria de notícia criada: {$category['name']}\n";
        }
    }

    echo "\n🎉 Migração concluída com sucesso!\n";
    echo "\n📋 Próximos passos:\n";
    echo "1. Acesse o sistema com: admin@uniorthocrin.com.br / admin123\n";
    echo "2. Configure as permissões do OneDrive\n";
    echo "3. Execute: php artisan queue:work para processar jobs\n";
    echo "4. Configure o crontab com: bash setup-crontab.sh\n";

} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
    exit(1);
}
