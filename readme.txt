=== URL Shortener by Melk ===
Contributors: Melksedeque
Tags: url shortener, shortlink, redirection, permalink, custom post type, seo
Requires at least: 5.0
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Crie URLs curtas para posts, páginas, categorias, tags e custom post types do seu WordPress automaticamente.

== Description ==

O **URL Shortener by Melk** é um plugin leve e eficiente para WordPress que permite gerar URLs curtas automaticamente para seus posts, páginas, categorias, tags e Custom Post Types. Ideal para compartilhamento em redes sociais e materiais de marketing.

**Funcionalidades:**

*   **Geração Automática:** Cria URLs curtas automaticamente ao publicar novos posts.
*   **Suporte Abrangente:** Funciona com Posts, Páginas, Categorias, Tags e Custom Post Types.
*   **Cópia Rápida:** Botão de "Copiar" direto na listagem de posts/termos no painel admin.
*   **Geração em Massa:** Ferramenta para gerar URLs curtas para conteúdo antigo com um clique.
*   **Performance:** Redirecionamento rápido usando regras de rewrite nativas do WordPress (sem queries pesadas).
*   **Seguro:** Código validado e seguro, seguindo as melhores práticas do WordPress.

== Installation ==

1. Faça o upload da pasta `url-shortener` para o diretório `/wp-content/plugins/`.
2. Ative o plugin através do menu 'Plugins' no WordPress.
3. Vá em **Configurações > URL Shortener** para ajustar as preferências.

== Frequently Asked Questions ==

= O plugin funciona com Custom Post Types? =
Sim! Você pode habilitar quais tipos de post deseja gerar URLs curtas nas configurações do plugin.

= Como as URLs são geradas? =
Utilizamos um algoritmo Base62 seguro para criar strings curtas e únicas (ex: `abc12`) baseadas no ID do post.

== Changelog ==

= 1.0.0 =
* Lançamento inicial.
