# Sistema de Loading - Admin UniOrthocrin

## 🚀 Funcionalidades Implementadas

### 1. **Loading Global Automático**
- Intercepta todas as requisições AJAX/fetch
- Mostra overlay de loading durante requisições
- Timeout automático de 10 segundos

### 2. **Loading para Uploads de Arquivos**
- Loading específico para inputs de arquivo
- Loading para formulários com uploads
- Timeout de 3 segundos para processamento

### 3. **Loading para Botões de Ação**
- Intercepta cliques em botões importantes
- Substitui texto por spinner animado
- Restaura estado original automaticamente

### 4. **Componente de Botão com Loading**
- Componente Blade reutilizável
- Suporte a diferentes variantes e tamanhos
- Loading state controlável

## 📁 Arquivos Criados

```
resources/
├── views/components/
│   ├── loading.blade.php          # Componente de loading básico
│   └── loading-button.blade.php   # Botão com loading
├── js/
│   ├── admin-loading.js           # Loading global
│   ├── file-upload-loading.js     # Loading para uploads
│   └── button-loading.js          # Loading para botões
└── views/admin/layouts/
    └── app.blade.php              # Layout com scripts incluídos

public/js/                         # Scripts compilados
├── admin-loading.js
├── file-upload-loading.js
└── button-loading.js
```

## 🎯 Como Usar

### 1. **Loading Automático (Já Funcionando)**
```javascript
// Não precisa fazer nada - funciona automaticamente!
// Todas as requisições AJAX mostram loading
```

### 2. **Componente de Botão com Loading**
```blade
<!-- Botão simples -->
<x-loading-button type="submit" variant="primary">
    Salvar
</x-loading-button>

<!-- Botão com loading customizado -->
<x-loading-button 
    type="submit" 
    variant="primary" 
    size="lg"
    loading-text="Salvando Produto..."
>
    <i class="fas fa-save mr-2"></i>Salvar Produto
</x-loading-button>

<!-- Variantes disponíveis -->
<x-loading-button variant="primary">Primário</x-loading-button>
<x-loading-button variant="secondary">Secundário</x-loading-button>
<x-loading-button variant="danger">Perigo</x-loading-button>
<x-loading-button variant="success">Sucesso</x-loading-button>

<!-- Tamanhos disponíveis -->
<x-loading-button size="sm">Pequeno</x-loading-button>
<x-loading-button size="md">Médio</x-loading-button>
<x-loading-button size="lg">Grande</x-loading-button>
<x-loading-button size="xl">Extra Grande</x-loading-button>
```

### 3. **Loading Manual (JavaScript)**
```javascript
// Mostrar loading global
showAdminLoading('Processando dados...');
hideAdminLoading();

// Mostrar loading em botão específico
showButtonLoading('#meu-botao', 'Salvando...');
hideButtonLoading('#meu-botao');
```

### 4. **Desabilitar Loading em Formulário**
```blade
<form data-no-loading>
    <!-- Este formulário não mostrará loading automático -->
</form>
```

## 🎨 Estilos CSS

O sistema usa classes Tailwind CSS já existentes:
- `animate-spin` para rotação
- `bg-black bg-opacity-50` para overlay
- `z-50` para z-index alto
- Cores do tema (primary, secondary, etc.)

## 🔧 Configurações

### Timeouts
- **Loading Global**: 10 segundos
- **Upload de Arquivos**: 3 segundos
- **Botões de Ação**: 10 segundos

### Z-Index
- **Overlay Global**: `z-50`
- **Loading de Arquivo**: `relative` no container

## 📝 Exemplo de Implementação

```blade
<!-- Formulário com loading automático -->
<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf
    
    <!-- Input de arquivo com loading automático -->
    <div class="file-upload-container">
        <input type="file" name="image" accept="image/*">
    </div>
    
    <!-- Botão com componente de loading -->
    <div class="flex justify-end space-x-3">
        <a href="{{ route('admin.products.index') }}" class="btn-modern-secondary">
            Cancelar
        </a>
        <x-loading-button type="submit" variant="primary" loading-text="Criando Produto...">
            <i class="fas fa-plus mr-2"></i>Criar Produto
        </x-loading-button>
    </div>
</form>
```

## ✅ Status

- ✅ Loading Global Automático
- ✅ Loading para Uploads
- ✅ Loading para Botões
- ✅ Componente de Botão
- ✅ Scripts Incluídos no Layout
- ✅ Exemplo Implementado (Products)

## 🧪 Teste

1. Acesse qualquer formulário de criação/edição
2. Faça upload de arquivo - verá loading específico
3. Clique em "Salvar" - verá loading no botão
4. Requisições AJAX - verá overlay global

**Pronto para usar!** 🎉
