#!/bin/bash

# Script para criar estrutura de dados de teste
# UniOrthocrin - Base de Testes Completa

echo "🚀 Criando estrutura de dados de teste para UniOrthocrin..."

# Criar diretório principal
mkdir -p test-data
cd test-data

echo "📁 Criando estrutura de pastas..."

# PRODUTOS (5 produtos)
mkdir -p produtos
for i in {1..5}; do
    mkdir -p "produtos/produto-0$i"
    # 2-3 imagens por produto
    touch "produtos/produto-0$i/produto-0${i}_imagem_01.jpg"
    touch "produtos/produto-0$i/produto-0${i}_imagem_02.jpg"
    touch "produtos/produto-0$i/produto-0${i}_imagem_03.jpg"
    # 1 vídeo opcional por produto
    touch "produtos/produto-0$i/produto-0${i}_video_01.mp4"
done

# TREINAMENTOS (3 treinamentos)
mkdir -p treinamentos
for i in {1..3}; do
    mkdir -p "treinamentos/treinamento-0$i"
    # 1-2 vídeos por treinamento
    touch "treinamentos/treinamento-0$i/treinamento-0${i}_video_01.mp4"
    touch "treinamentos/treinamento-0$i/treinamento-0${i}_video_02.mp4"
    # 1-2 PDFs por treinamento
    touch "treinamentos/treinamento-0$i/treinamento-0${i}_material_01.pdf"
    touch "treinamentos/treinamento-0$i/treinamento-0${i}_material_02.pdf"
done

# BIBLIOTECA (3-4 itens)
mkdir -p biblioteca
for i in {1..4}; do
    mkdir -p "biblioteca/item-0$i"
    # 2-3 PDFs por item
    touch "biblioteca/item-0$i/item-0${i}_documento_01.pdf"
    touch "biblioteca/item-0$i/item-0${i}_documento_02.pdf"
    touch "biblioteca/item-0$i/item-0${i}_documento_03.pdf"
done

# RADAR/NOTÍCIAS (4 notícias)
mkdir -p radar
for i in {1..4}; do
    mkdir -p "radar/noticia-0$i"
    # 1-2 imagens por notícia
    touch "radar/noticia-0$i/noticia-0${i}_capa_01.jpg"
    touch "radar/noticia-0$i/noticia-0${i}_interna_02.jpg"
done

# CAMPANHAS (2 campanhas completas)
mkdir -p campanhas

# Campanha 01
mkdir -p campanhas/campanha-01/posts/tipo-a
mkdir -p campanhas/campanha-01/posts/tipo-b
mkdir -p campanhas/campanha-01/posts/tipo-c
mkdir -p campanhas/campanha-01/folder/tipo-a
mkdir -p campanhas/campanha-01/folder/tipo-b
mkdir -p campanhas/campanha-01/misc/tipo-a
mkdir -p campanhas/campanha-01/misc/tipo-b
mkdir -p campanhas/campanha-01/misc/tipo-c
mkdir -p campanhas/campanha-01/misc/tipo-d
mkdir -p campanhas/campanha-01/videos/tipo-a
mkdir -p campanhas/campanha-01/videos/tipo-b

# Posts Campanha 01 (2-3 por tipo)
touch campanhas/campanha-01/posts/tipo-a/campanha-01_posts_tipo-a_01.jpg
touch campanhas/campanha-01/posts/tipo-a/campanha-01_posts_tipo-a_02.jpg
touch campanhas/campanha-01/posts/tipo-a/campanha-01_posts_tipo-a_03.jpg

touch campanhas/campanha-01/posts/tipo-b/campanha-01_posts_tipo-b_01.jpg
touch campanhas/campanha-01/posts/tipo-b/campanha-01_posts_tipo-b_02.jpg
touch campanhas/campanha-01/posts/tipo-b/campanha-01_posts_tipo-b_03.jpg

touch campanhas/campanha-01/posts/tipo-c/campanha-01_posts_tipo-c_01.jpg
touch campanhas/campanha-01/posts/tipo-c/campanha-01_posts_tipo-c_02.jpg
touch campanhas/campanha-01/posts/tipo-c/campanha-01_posts_tipo-c_03.jpg

# Folder Campanha 01 (1-2 por tipo)
touch campanhas/campanha-01/folder/tipo-a/campanha-01_folder_tipo-a_01.jpg
touch campanhas/campanha-01/folder/tipo-a/campanha-01_folder_tipo-a_02.jpg

touch campanhas/campanha-01/folder/tipo-b/campanha-01_folder_tipo-b_01.jpg
touch campanhas/campanha-01/folder/tipo-b/campanha-01_folder_tipo-b_02.jpg

# Miscelânea Campanha 01 (1 por tipo)
touch campanhas/campanha-01/misc/tipo-a/campanha-01_misc_tipo-a_01.jpg
touch campanhas/campanha-01/misc/tipo-b/campanha-01_misc_tipo-b_01.png
touch campanhas/campanha-01/misc/tipo-c/campanha-01_misc_tipo-c_01.pdf
touch campanhas/campanha-01/misc/tipo-d/campanha-01_misc_tipo-d_01.svg

# Vídeos Campanha 01 (1 por tipo)
touch campanhas/campanha-01/videos/tipo-a/campanha-01_video_tipo-a_01.mp4
touch campanhas/campanha-01/videos/tipo-b/campanha-01_video_tipo-b_01.mp4

# Campanha 02
mkdir -p campanhas/campanha-02/posts/tipo-a
mkdir -p campanhas/campanha-02/posts/tipo-b
mkdir -p campanhas/campanha-02/posts/tipo-c
mkdir -p campanhas/campanha-02/folder/tipo-a
mkdir -p campanhas/campanha-02/folder/tipo-b
mkdir -p campanhas/campanha-02/misc/tipo-a
mkdir -p campanhas/campanha-02/misc/tipo-b
mkdir -p campanhas/campanha-02/misc/tipo-c
mkdir -p campanhas/campanha-02/misc/tipo-d
mkdir -p campanhas/campanha-02/videos/tipo-a
mkdir -p campanhas/campanha-02/videos/tipo-b

# Posts Campanha 02 (2-3 por tipo)
touch campanhas/campanha-02/posts/tipo-a/campanha-02_posts_tipo-a_01.jpg
touch campanhas/campanha-02/posts/tipo-a/campanha-02_posts_tipo-a_02.jpg
touch campanhas/campanha-02/posts/tipo-a/campanha-02_posts_tipo-a_03.jpg

touch campanhas/campanha-02/posts/tipo-b/campanha-02_posts_tipo-b_01.jpg
touch campanhas/campanha-02/posts/tipo-b/campanha-02_posts_tipo-b_02.jpg
touch campanhas/campanha-02/posts/tipo-b/campanha-02_posts_tipo-b_03.jpg

touch campanhas/campanha-02/posts/tipo-c/campanha-02_posts_tipo-c_01.jpg
touch campanhas/campanha-02/posts/tipo-c/campanha-02_posts_tipo-c_02.jpg
touch campanhas/campanha-02/posts/tipo-c/campanha-02_posts_tipo-c_03.jpg

# Folder Campanha 02 (1-2 por tipo)
touch campanhas/campanha-02/folder/tipo-a/campanha-02_folder_tipo-a_01.jpg
touch campanhas/campanha-02/folder/tipo-a/campanha-02_folder_tipo-a_02.jpg

touch campanhas/campanha-02/folder/tipo-b/campanha-02_folder_tipo-b_01.jpg
touch campanhas/campanha-02/folder/tipo-b/campanha-02_folder_tipo-b_02.jpg

# Miscelânea Campanha 02 (1 por tipo)
touch campanhas/campanha-02/misc/tipo-a/campanha-02_misc_tipo-a_01.jpg
touch campanhas/campanha-02/misc/tipo-b/campanha-02_misc_tipo-b_01.png
touch campanhas/campanha-02/misc/tipo-c/campanha-02_misc_tipo-c_01.pdf
touch campanhas/campanha-02/misc/tipo-d/campanha-02_misc_tipo-d_01.svg

# Vídeos Campanha 02 (1 por tipo)
touch campanhas/campanha-02/videos/tipo-a/campanha-02_video_tipo-a_01.mp4
touch campanhas/campanha-02/videos/tipo-b/campanha-02_video_tipo-b_01.mp4

# USUÁRIOS (estrutura para dados de teste)
mkdir -p usuarios
touch usuarios/admin_dados.txt
touch usuarios/franqueado_dados.txt
touch usuarios/lojista_dados.txt
touch usuarios/representante_dados.txt

echo "📝 Criando README com instruções..."

# Criar README
cat > README.md << 'EOF'
# 🧪 Dados de Teste - UniOrthocrin

## 📋 Estrutura Criada

### 📦 Produtos (5 produtos)
- **Localização**: `produtos/produto-01/` até `produtos/produto-05/`
- **Arquivos por produto**: 3 imagens + 1 vídeo
- **Total**: 15 imagens + 5 vídeos

### 🎓 Treinamentos (3 treinamentos)
- **Localização**: `treinamentos/treinamento-01/` até `treinamentos/treinamento-03/`
- **Arquivos por treinamento**: 2 vídeos + 2 PDFs
- **Total**: 6 vídeos + 6 PDFs

### 📚 Biblioteca (4 itens)
- **Localização**: `biblioteca/item-01/` até `biblioteca/item-04/`
- **Arquivos por item**: 3 PDFs
- **Total**: 12 PDFs

### 📰 Radar/Notícias (4 notícias)
- **Localização**: `radar/noticia-01/` até `radar/noticia-04/`
- **Arquivos por notícia**: 2 imagens
- **Total**: 8 imagens

### 📢 Campanhas (2 campanhas completas)
- **Localização**: `campanhas/campanha-01/` e `campanhas/campanha-02/`
- **Por campanha**:
  - Posts (3 tipos): 9 imagens
  - Folder (2 tipos): 4 imagens
  - Miscelânea (4 tipos): 4 arquivos
  - Vídeos (2 tipos): 2 vídeos
- **Total**: 18 imagens + 4 vídeos + 8 arquivos misc

## 🚀 Como Usar

1. **Substitua os arquivos dummy** pelos arquivos reais
2. **Mantenha os nomes** para facilitar a organização
3. **Teste cada módulo** com os arquivos correspondentes
4. **Verifique permissões** com diferentes tipos de usuário

## 📊 Totais Finais
- **Imagens**: 41 arquivos
- **Vídeos**: 15 arquivos  
- **PDFs**: 18 arquivos
- **Miscelânea**: 8 arquivos
- **Total**: 82 arquivos de teste

## 🎯 Funcionalidades para Testar
- ✅ Upload múltiplo
- ✅ Organização por categorias
- ✅ Permissões por usuário
- ✅ Visualização e downloads
- ✅ Filtros e busca
- ✅ Relatórios admin

---
*Estrutura criada automaticamente pelo script `create-test-data.sh`*
EOF

echo "✅ Estrutura criada com sucesso!"
echo ""
echo "📊 Resumo:"
echo "   - 5 produtos (15 imagens + 5 vídeos)"
echo "   - 3 treinamentos (6 vídeos + 6 PDFs)"
echo "   - 4 itens biblioteca (12 PDFs)"
echo "   - 4 notícias radar (8 imagens)"
echo "   - 2 campanhas completas (18 imagens + 4 vídeos + 8 misc)"
echo ""
echo "📁 Total: 82 arquivos de teste"
echo ""
echo "🚀 Próximos passos:"
echo "   1. Substitua os arquivos dummy pelos reais"
echo "   2. Teste cada módulo na plataforma"
echo "   3. Verifique permissões e funcionalidades"
echo ""
echo "📖 Consulte o README.md para detalhes completos"
