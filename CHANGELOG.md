# Changelog

Todas as mudanças notáveis neste projeto serão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/),
e este projeto adere ao [Semantic Versioning](https://semver.org/lang/pt-BR/).

## [1.0.1] - 2026-10-08

### 🎯 Reforço de escopo

#### Modificado
- O plugin volta a cuidar exclusivamente de URLs curtas. A geração de conteúdo para redes sociais, desenvolvida internamente como "Social Kit (Beta)" nesta mesma janela de trabalho mas nunca publicada, saiu deste plugin e se tornou um produto próprio: **Social Kit by Melk**.
- `Requires at least` voltou de 5.3 para 5.0, já que a dependência de `wp.data` no editor era exclusiva do painel do Social Kit.

#### Adicionado
- Função global `urlshbym_get_short_url_for_post( $post_id )`: ponto de integração opcional para outros plugins (como o Social Kit by Melk) reaproveitarem o link curto de um post via `function_exists()`, sem criar dependência obrigatória entre os plugins.
- Seção "Other Plugins by Melk" na tela de configurações, indicando o Social Kit by Melk como complemento opcional.

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
