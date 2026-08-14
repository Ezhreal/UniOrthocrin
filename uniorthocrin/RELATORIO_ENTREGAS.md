# 📋 Relatório Técnico de Entregas & Discriminação de Horas

**Projeto:** UniOrthocrin  
**Escopo:** Implementação de Novas Features (Onboarding Interativo & Seleção de Perfis), Diagnóstico e Otimização de Uploads (>500MB), Otimização de Comandos de Imagens/Sync, Refatorações de UX/UI e Correções Estruturais  
**Carga Horária Total:** 40 Horas (Sprint Dedicada)  
**Valor / Hora:** R$ 32,00  
**Investimento Total:** **R$ 1.280,00**  

---

## 🚀 1. Novas Features Desenvolvidas (New Features)

### 🔹 [FEATURE] Engine Central de Onboarding & Tours Guiados Interativos (*Driver.js*)
* **Integração Arquitetural:** Instalação e configuração da biblioteca `driver.js` integrada ao bundle de produção do Vite.
* **Componente Singleton `TourManager.js`:** Desenvolvimento de um gerenciador centralizado com persistência de estado em `localStorage` para exibição inteligente de tours (única vez automática e repetição sob demanda através do botão *"Como usar?"*).
* **Detecção Inteligente do DOM:** Algoritmo defensivo que identifica elementos condicionais presentes na tela (ex.: botões de download, reprodutor de vídeo, carrossel de fotos) para evitar travamentos ou passos em branco durante o tour.

### 🔹 [FEATURE] Mapeamento e Criação de Tours Guiados em Toda a Plataforma
* **Painel Administrativo (Gestão Completa):**
  * **Dashboard:** Destaque dos cards métricos e resumo geral da plataforma.
  * **Campanhas / Marketing:** Passo a passo detalhado de cadastro, vigência, upload de folhetos regionais, posts e comerciais de vídeo.
  * **Produtos:** Apresentação de carrossel de fotos, especificações, galerias de vídeos e anexos técnicos.
  * **Biblioteca de Arquivos:** Mapeamento de upload de documentos, categorização e download.
  * **Na Mídia / Imprensa:** Orientação sobre inclusão de releases e matérias multi-formato.
  * **Treinamentos:** Fluxo de criação de videoaulas, módulos e manuais de apoio em PDF.
  * **Usuários & Notícias:** Criação de usuários, atribuição de perfis e disparos de avisos.
* **Portal do Cliente (Experiência do Franqueado / Lojista / Representante):**
  * **Produtos:** Tours detalhando carrossel, download de imagens em pacote `.zip`, player integrado e opções de download individual ou lote de vídeos.
  * **Campanhas:** Orientação sobre vigência, folhetos segmentados por praça geográfica, posts prontos para redes sociais e pacote de mídia completo.
  * **Treinamentos:** Orientação de visualização exclusiva online de videoaulas e download de apostilas em PDF.
  * **Biblioteca e Na Mídia:** Orientações de consulta e download seguro.

### 🔹 [FEATURE] Sistema de Pré-Seleção e Modal de Entrada no Cadastro de Usuários
* **Modal Interativo de Primeiro Passo:** Bloqueio e exigência de seleção de perfil antes do preenchimento do formulário para impedir cadastros em categorias indevidas.
* **Segmentação Visual Clara:**
  * 🏪 **Franqueado:** Para franqueados e lojas exclusivas.
  * 🏬 **Lojista Multimarca:** Para revendedores multimarcas autorizados.
  * 👔 **Representante:** Para representantes comerciais e equipe de vendas.

---

## 🔧 2. Refatorações Estruturais, Otimização de Comandos & Engenharia

### 🔹 [ENGENHARIA] Otimização do Comando de Imagens & Sync de Uploads Pesados
* **Comando Artisan de Otimização:** Refinamento e otimização do comando CLI de processamento e compressão de imagens, reduzindo o consumo de memória do servidor durante execuções pesadas.
* **Sincronização de Uploads Pesados:** Ajuste no mecanismo de sincronização e processamento em segundo plano de mídias volumosas, garantindo integridade dos arquivos transferidos sem travamento das requisições HTTP.

### 🔹 [DIAGNÓSTICO & LOGS] Otimização de Uploads (>500MB) & Persistência Atômica no Banco
* **Investigação Profunda via Logs:** Leitura, análise e interpretação de logs de aplicação e servidor para identificação de falhas silenciosas de envio e timeouts em ambiente de produção.
* **Suporte a Uploads > 500MB:** Diagnóstico e mitigação de gargalos de memória e concorrência, avaliando comportamento de uploads fracionados (*chunked uploads*) vs. envio síncrono.
* **Melhoria de Logging:** Implementação de rastreabilidade para auditoria completa do ciclo de envio dos arquivos.
* **Persistência Atômica no Banco:** Correção do fluxo de inserção de registros de arquivos para evitar registros órfãos e garantir integridade referencial.
* **Upload Manager Multi-formato:** Suporte ampliado e validações para `.mp4`, `.png`, `.jpg`, `.pdf`, `.txt` e `.zip`.

### 🔹 [REFATORAÇÃO] Redesenho e Fluxo da Tela de Cadastro (`/cadastro`)
* **Layout Fluido em 2 Colunas:** Eliminação do formulário centralizado estreito e reestruturação em grid responsivo com proporção equilibrada e visual premium.
* **Card de Perfil Selecionado Fixo:** Substituição do dropdown propenso a falhas por um card de confirmação com identificador visual do perfil escolhido e botão dinâmico *"Escolher outro perfil"*.
* **Backend Sanitizado:** Adequação no `RegisterController.php` para vinculação correta do `UserType` e permissões de acesso.

### 🔹 [CORREÇÃO] Sanitização e Decodificação de Nomes de Mídias (Encoding)
* **Backend PHP (Repositories/Controllers):** Tratamento em `ProductRepository`, `TrainingRepository` e `MarketingController` com decodificação RFC 3986 (`rawurldecode`) para remover artefatos de URLs (`%20`, `%C3%8D`, caracteres acentuados).
* **Frontend & UX:** Inclusão de `break-words line-clamp-2` para evitar quebra do layout em arquivos com nomes longos.

### 🔹 [CORREÇÃO] Mecanismo de Busca Global (Search) & Filtros
* **Refinamento de Queries:** Ajustes na busca textual e filtros nos módulos administrativos e do portal para suporte a termos com acentuação e buscas parciais.

---

## ⏱️ 3. Tabela Discriminada de Horas Técnicas

| Item | Atividade / Módulo | Tipo | Horas |
|:---:|---|:---:|:---:|
| **01** | **Diagnóstico Avançado de Uploads (>500MB), Logs & Persistência no Banco** | Engenharia / Backend | **7.0h** |
| **02** | **Otimização do Comando de Imagens & Sync de Uploads Pesados** | Engenharia / CLI | **4.5h** |
| **03** | **Engine de Onboarding & Tours Guiados Interativos (Driver.js)** | **Feature Nova** | **5.5h** |
| **04** | **Mapeamento de Tours nos Módulos do Painel Administrativo** | **Feature Nova** | **6.0h** |
| **05** | **Mapeamento de Tours nas Views do Portal do Cliente** | **Feature Nova** | **4.0h** |
| **06** | **Modal de Entrada de Perfis & Refatoração Completa do Cadastro** | **Feature Nova / UX** | **6.0h** |
| **07** | **Sanitização de Encoding de Arquivos & Nomes de Mídias** | Correção / Backend | **2.5h** |
| **08** | **Otimização do Mecanismo de Busca (Search) & Filtros** | Correção / Queries | **2.0h** |
| **09** | **Build Vite, Testes Cross-Browser, Homologação e Deploy** | QA / DevOps | **2.5h** |
| **TOTAL** | **Carga Horária Total Consolidada** | **Sprint Fechada** | **40.0h** |

---

## 💰 4. Resumo Financeiro

* **Carga Horária:** 40 horas técnicas (1 semana cheia de sprint dedicada)
* **Taxa Horária:** R$ 32,00 / hora
* **Valor Total a Faturar:** **R$ 1.280,00**
* **Forma de Pagamento:** Conforme alinhado (À vista / Faturamento).
