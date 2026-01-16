# URL Shortener by Melk

> **Plugin WordPress para criação de URLs curtas personalizadas.**

[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![WordPress](https://img.shields.io/badge/WordPress-Tested-blue.svg)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)](https://www.php.net/)
[![Status](https://img.shields.io/badge/Status-Under%20Review-yellow.svg)](https://wordpress.org/plugins/)

O **URL Shortener by Melk** é um plugin leve, eficiente e seguro para WordPress que permite gerar URLs curtas automaticamente para seus posts, páginas, categorias, tags e Custom Post Types. Ideal para compartilhamento em redes sociais e materiais de marketing.

🚀 **Submetido e em análise pela equipe de Plugins do WordPress.org.**

---

## ✨ Funcionalidades

- 🔗 **Geração Automática:** Cria URLs curtas automaticamente ao publicar novos posts.
- 🎯 **Suporte Abrangente:** Funciona com Posts, Páginas, Categorias, Tags e Custom Post Types.
- 📋 **Cópia Rápida:** Botão de "Copiar" direto na listagem de posts/termos no painel admin.
- ⚡ **Geração em Massa:** Ferramenta para gerar URLs curtas para conteúdo antigo com um clique.
- 🚀 **Performance:** Redirecionamento rápido usando regras de rewrite nativas do WordPress.
- 🔒 **Seguro:** Código validado, sanitizado e escapado seguindo rigorosamente os padrões do WordPress.

---

## 🛡️ Qualidade e Segurança (Compliance)

Este plugin foi desenvolvido seguindo as melhores práticas de desenvolvimento WordPress e aprovado em testes rigorosos de qualidade (Plugin Check):

- **Segurança de Banco de Dados:** Todas as consultas utilizam `wpdb->prepare()` para prevenir SQL Injection.
- **Sanitização e Escape:** Todos os dados de entrada são sanitizados e todas as saídas são escapadas (`esc_html`, `esc_attr`, etc.) para prevenir XSS.
- **Performance Otimizada:** Implementação de **Object Caching** (`wp_cache_get`/`wp_cache_set`) para reduzir consultas ao banco de dados em ambientes de alta tráfego.
- **Padrões de Código:** Compatível com os padrões de codificação do WordPress (WPCS).

---

## 🚀 Instalação

### Via Upload (ZIP)

1. Faça o download do arquivo `.zip` deste repositório.
2. No painel do WordPress, vá em **Plugins > Adicionar Novo**.
3. Clique em **Enviar Plugin** e selecione o arquivo baixado.
4. Clique em **Instalar Agora** e depois em **Ativar**.

### Via Git (Para Desenvolvedores)

1. Navegue até a pasta de plugins do seu WordPress:
   ```bash
   cd wp-content/plugins
   ```
2. Clone o repositório:
   ```bash
   git clone https://github.com/Melksedeque/url-shortener.git
   ```
3. Ative o plugin no painel do WordPress.

---

## 📖 Como Usar

### Configuração Inicial

1. Após ativar, vá em **Configurações > URL Shortener**.
2. Selecione quais **Tipos de Post** (Posts, Páginas, etc.) devem ter URLs curtas.
3. Selecione quais **Taxonomias** (Categorias, Tags, etc.) devem ter URLs curtas.
4. Clique em **Salvar Configurações**.

### Gerando URLs para Conteúdo Antigo

Na mesma página de configurações:
1. Localize a seção **Gerar URLs em Massa**.
2. Clique no botão **Gerar URLs** para os tipos de conteúdo desejados.
3. Aguarde a barra de progresso ou mensagem de conclusão.

### Copiando URLs Curtas

1. Vá para a listagem de posts (**Posts > Todos os Posts**) ou categorias.
2. Localize a coluna **URL Curta**.
3. Clique no botão de **Copiar** ao lado do código da URL.
4. A URL curta (ex: `seusite.com/a1b2c`) será copiada para sua área de transferência.

---

## 🧑‍💻 Para desenvolvedores

- **Namespace principal:** `Melk\\UrlShortenerByMelk`.
- **Prefixo único:** todas as funções, options, metas e hooks utilizam o prefixo `urlshbym_`, conforme as diretrizes oficiais do WordPress para evitar _naming collisions_.
- **Options no banco:**
  - `urlshbym_enabled_post_types`
  - `urlshbym_enabled_taxonomies`
- **Meta keys:**
  - `_urlshbym_short_code` em posts
  - `_urlshbym_short_code` em termos (taxonomias)
- **Tabela de banco de dados:** `{$wpdb->prefix}urlshbym_short_urls` (criada na ativação para armazenar mapeamentos `short_code -> objeto`).
- **Hooks principais:**
  - `urlshbym_short_url_clicked` — action disparada sempre que uma URL curta é acessada, recebendo o `$short_code` e o ID interno do registro.
- **Regras de rewrite:** as URLs curtas são resolvidas via rewrite rule para `index.php?urlshbym_short={codigo}`, permitindo estruturas como `seusite.com/abc12`.

Esses detalhes garantem que o plugin seja seguro para ser estendido em ambientes complexos, evitando conflitos com outros plugins e temas.

## 🤝 Contribuindo

Contribuições são bem-vindas! Se você tiver sugestões, correções de bugs ou novas funcionalidades:

1. Faça um Fork do projeto.
2. Crie uma Branch para sua Feature (`git checkout -b feature/NovaFuncionalidade`).
3. Faça o Commit de suas mudanças (`git commit -m 'Adiciona Nova Funcionalidade'`).
4. Faça o Push para a Branch (`git push origin feature/NovaFuncionalidade`).
5. Abra um Pull Request.

---

## 📝 Licença

Este projeto está licenciado sob a Licença GPL v3 - veja o arquivo [LICENSE](LICENSE) para mais detalhes.

---

Desenvolvido com ❤️ por [Melksedeque Silva](https://github.com/Melksedeque).
