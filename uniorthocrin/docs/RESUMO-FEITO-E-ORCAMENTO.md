# Resumo: o que foi feito/corrigido e o que pode precisar de orçamento

Com base no que foi tratado no projeto (lista de ajustes / reviews).

---

## Feito e corrigido (sem acréscimo de valor)

### Conteúdo e textos
- **Saudação:** "Bem-vindos" (com hífen) no dashboard.
- **Rodapé:** Ano dinâmico no footer.

### Busca
- **Busca no header:** Formulário (desktop e mobile) apontando para a página de resultados.
- **Busca global (Resultados):** Uma única busca que percorre tudo o que o usuário tem permissão de ver (Marketing, Produtos, Treinamentos, Biblioteca, Radar). Página **Resultados** com cards (thumbnail, título, tipo do conteúdo). Rota `/resultados?q=palavra`.

### Marketing / Campanhas
- **Filtros regionais:** "SP" trocado por "MG"; "DF" trocado por "Outros Estados" (labels em pastas e posts).
- **Script → Materiais Internos:** Rótulo e seções ajustados; aba "Sticker" removida da interface (create, edit, show, lista e detalhe).
- **Adesivo, Banner, Faixa:** Tipos incluídos no enum de `campaign_miscellaneous` (migration), processados no admin e exibidos no front (lista e detalhe de marketing). SQL da migration disponível em `database/migrations_sql/`.

### Biblioteca
- **Ordem:** Listagem ordenada alfabeticamente (nome) e por categoria.
- **Thumbnail:** Exibição de imagem de capa quando existir, com fallback para ícone.

### Notificações
- **Front (área logada):** Botões **Ver**, **Marcar como Lida** e **Excluir** funcionando; "Ver" abre o conteúdo e marca como lida; contador do sino atualiza após marcar como lida ou excluir (fallback via API).
- **Admin:** Dropdown do header com notificações filtradas por usuário; botões/link **Ver conteúdo** e **Marcar como lida**; rota de marcar como lida implementada.

### Deploy e operação
- **Deploy manual:** Guia em `DEPLOY-MANUAL.md`.
- **Migration em SQL:** Arquivo em `database/migrations_sql/migrate_2026_02_16_adesivo_banner_faixa.sql` para rodar no servidor.
- **Arquivos para subir:** `ARQUIVOS-PARA-SUBIR.md` e `LISTA-SUBIR.md` com pastas/arquivos da raiz e o que não subir.

---

## O que pode precisar de orçamento (rocamento)

Itens que costumam envolver custo ou decisão de contratação/compra — vale fechar orçamento antes de comprometer:

### Infraestrutura e hospedagem
- Servidor (VPS, cloud ou hospedagem compartilhada).
- Domínio e renovação.
- Certificado SSL (se não for Let’s Encrypt incluso).
- Backup automático (espaço e ferramenta).
- CDN ou cache pago (se for o caso).

### Serviços e integrações
- OneDrive / Microsoft 365 (licenças, se aplicável).
- Serviço de e-mail transacional (ex.: SendGrid, Mailgun).
- Filas em produção (Redis, supervisor, fila gerenciada).
- Monitoramento e alertas (serviço pago ou tempo de alguém para configurar).

### Desenvolvimento e evolução
- Itens do documento **“Uniorthocrin - Reviews”** que tenham sido marcados como **com acréscimo de valor** (novas funcionalidades, integrações complexas, relatórios sob demanda, etc.).
- Novas features não previstas na lista original.
- Ajustes de design/UX que exijam contratação de designer ou mais horas de dev.
- Suporte técnico ou manutenção contínua (mensalidade ou por demanda).

### Recomendação
- Listar no próprio documento “Uniorthocrin - Reviews” (ou em uma planilha) os itens **com acréscimo de valor** e, para cada um, pedir **um orçamento** (tempo de dev, custo de serviço, licença) antes de aprovar.
- Manter este arquivo (`RESUMO-FEITO-E-ORCAMENTO.md`) atualizado: na seção **Feito** quando algo for entregue; na seção **Orçamento** quando surgir algo novo que precise de custo ou aprovação.

---

*Última atualização: com base no trabalho realizado no projeto até a data desta conversa.*
