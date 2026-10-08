# Changelog

Todas as mudanças notáveis neste projeto serão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/),
e este projeto adere ao [Semantic Versioning](https://semver.org/lang/pt-BR/).

## [1.0.1] - 2026-10-08

### 🐛 Correções

- **Páginas, posts e termos com slug de 5 a 7 caracteres retornavam 404** (ex.: `/sobre`, `/about`, `/contato`). A regra de rewrite na raiz tratava qualquer slug nesse formato como código curto. Agora as URLs curtas são resolvidas em `template_redirect` **apenas quando o WordPress já concluiu que seria um 404**; conteúdo real sempre tem prioridade. A regra de rewrite e a query var `urlshbym_short` foram removidas.
- **Colisão de códigos entre posts e termos** (salts 10000/20000): um código podia apontar para o objeto errado. Agora o dono do código é verificado antes de reaproveitá-lo; em colisão, o próximo código determinístico é usado. Códigos já existentes são preservados.
- **Collation da tabela**: `short_code` passou a `ascii_bin` (case-sensitive). Com a collation padrão, códigos que diferem só na caixa (`aB3xY` vs `ab3xy`) colidiam na chave UNIQUE.
- **Registros ausentes na tabela**: códigos guardados em post/term meta, mas ausentes da tabela, são restaurados automaticamente na atualização.
- Rascunhos, itens na lixeira e posts privados não redirecionam mais.
- Registros da tabela são removidos quando o post ou termo é excluído.
- A geração em massa valida o post type/taxonomia recebidos.

### ✨ Adicionado
- **Danger Zone** na tela de configurações: botão "Delete all short URLs" (com confirmação) que apaga todas as URLs curtas e os códigos guardados nos metadados, mantendo as configurações. Como os códigos derivam do ID, gerar novamente recria os mesmos códigos na grande maioria dos casos.
- `uninstall.php` e a opção **"Delete all plugin data"** na Danger Zone (desmarcada por padrão, para não quebrar links já compartilhados ao reinstalar). Só tem efeito ao excluir o plugin; remove tabela, opções e metas e é compatível com multisite.
- **Tradução pt_BR** incluída no plugin (`/languages`, com `.pot`, `.po` e `.mo`) e carregada via `load_plugin_textdomain`. A mensagem de erro que estava fixa em português no `admin.js` agora é traduzível.
- Rotina de upgrade do banco (`urlshbym_db_version`): o hook de ativação não roda em atualizações, então correções de schema agora são aplicadas automaticamente.
- Função global `urlshbym_get_short_url_for_post( $post_id )`: ponto de integração opcional para outros plugins (como o Social Kit by Melk) reaproveitarem o link curto de um post via `function_exists()`, sem dependência obrigatória entre os plugins.

### 🔧 Modificado
- O plugin volta a cuidar exclusivamente de URLs curtas. A geração de conteúdo para redes sociais, desenvolvida internamente como "Social Kit (Beta)" mas nunca publicada, tornou-se um produto próprio: **Social Kit by Melk**.
- `Requires at least` voltou de 5.3 para 5.0, já que a dependência de `wp.data` no editor era exclusiva do painel do Social Kit.
- Testado até o WordPress 7.1.
- Removido código morto (`track_click`, `clean_cache`).
- CSS e JS agora são versionados pela data de modificação do arquivo (`urlshbym_asset_version()`), evitando que o navegador sirva uma cópia antiga em cache após uma atualização.

## [1.0.0] - 2026-01-08

### 🎉 Lançamento Inicial

#### Adicionado
- **Geração Automática de URLs Curtas**
  - URLs curtas geradas automaticamente na publicação de posts
  - URLs curtas geradas automaticamente na criação de termos (categorias/tags)
  - Suporte completo para Custom Post Types públicos
  - Algoritmo Base62 para códigos de 5-7 caracteres
  - Geração baseada em ID (sempre o mesmo código para o mesmo conteúdo)

- **Interface Administrativa**
  - Página de configurações em Configurações > URL Shortener
  - Checkboxes para habilitar/desabilitar post types
  - Checkboxes para habilitar/desabilitar taxonomias
  - Botões de geração retroativa para conteúdo existente
  - Feedback visual de sucesso/erro nas ações

- **Colunas Personalizadas**
  - Coluna "URL Curta" na listagem de posts (após coluna "Data")
  - Coluna "URL Curta" na listagem de termos (após coluna "Slug")
  - Botão de copiar URL com ícone
  - Mensagem "Copiado!" com animação
  - Suporte responsivo para mobile

- **Sistema de Redirecionamento**
  - Rewrite rules otimizadas
  - Redirecionamento 301 (permanente) para SEO
  - Tratamento de erro 404 para códigos inexistentes
  - Hook `urlshbym_short_url_clicked` para extensões futuras

- **Banco de Dados**
  - Tabela `wp_urlshbym_short_urls` para armazenar URLs
  - Post meta `_urlshbym_short_code` para posts
  - Term meta `_urlshbym_short_code` para termos
  - Índices otimizados para performance

- **Assets**
  - CSS responsivo e moderno
  - JavaScript com fallback para navegadores antigos
  - Animações suaves e feedback visual
  - Compatibilidade com temas do WordPress

#### Características Técnicas
- **Código Modular**: Classes separadas por responsabilidade
- **Namespace PHP**: Evita conflitos com outros plugins
- **Hooks e Filtros**: Extensível via WordPress API
- **Autoloader**: Carregamento automático de classes
- **Internacionalização**: Pronto para tradução
- **Segurança**: Sanitização e validação de dados
- **Performance**: Queries otimizadas

#### Documentação
- README.md completo com instruções de uso

---

## [Próximas Versões Planejadas]

### [2.0.0] - Dashboard de Analytics (Planejado)
- [ ] Tracking de cliques
- [ ] Estatísticas por período
- [ ] Gráficos interativos
- [ ] Export de dados em CSV
- [ ] Top URLs mais acessadas

### [2.1.0] - Gerenciamento Avançado (Planejado)
- [ ] Página "Todas as URLs Curtas"
- [ ] Edição manual de códigos
- [ ] Exclusão de URLs
- [ ] Busca e filtros avançados
- [ ] Ações em massa

### [2.2.0] - Compatibilidade SEO (Planejado)
- [ ] Integração com Yoast SEO
- [ ] Integração com Rank Math
- [ ] Integração com All in One SEO
- [ ] Metabox personalizado no editor

### [3.0.0] - Funcionalidades Premium (Planejado)
- [ ] QR Code Generator
- [ ] Expiração de URLs
- [ ] Proteção por senha
- [ ] Domínio customizado externo
- [ ] API REST completa

---

## Legenda dos Tipos de Mudanças

- **Adicionado**: para novas funcionalidades
- **Modificado**: para mudanças em funcionalidades existentes
- **Descontinuado**: para funcionalidades que serão removidas
- **Removido**: para funcionalidades removidas
- **Corrigido**: para correção de bugs
- **Segurança**: em caso de vulnerabilidades

---

## Versionamento

Este projeto usa [Semantic Versioning](https://semver.org/):
- **MAJOR** (X.0.0): Mudanças incompatíveis com versões anteriores
- **MINOR** (0.X.0): Novas funcionalidades compatíveis
- **PATCH** (0.0.X): Correções de bugs compatíveis

---

## Links

- [Repositório no GitHub](https://github.com/Melksedeque/url-shortener)
- [Documentação e Instalação](README.md)
- [Reportar Bug](https://github.com/Melksedeque/url-shortener/issues)
