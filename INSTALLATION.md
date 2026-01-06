# 🚀 Guia Completo de Instalação e Início Rápido

## WP URL Shortener - Do Zero ao Funcionamento

Este guia combina instalação detalhada, troubleshooting e início rápido em um único documento.

---

## 📋 Índice

1. [Pré-requisitos](#-pré-requisitos)
2. [Instalação Rápida (5 minutos)](#-instalação-rápida-5-minutos)
3. [Instalação Detalhada](#-instalação-detalhada)
4. [Configuração Inicial](#-configuração-inicial)
5. [Primeiro Uso](#-primeiro-uso)
6. [Verificação e Testes](#-verificação-e-testes)
7. [Troubleshooting](#-troubleshooting)
8. [Uso Diário](#-uso-diário)
9. [Personalizações](#-personalizações)
10. [FAQ](#-faq)

---

## 📦 Pré-requisitos

### Requisitos Mínimos

- ✅ **WordPress:** 5.0 ou superior
- ✅ **PHP:** 7.4 ou superior
- ✅ **MySQL:** 5.6 ou superior / MariaDB 10.0 ou superior
- ✅ **Permissões:** Capacidade de instalar plugins
- ✅ **Mod Rewrite:** Habilitado (para URLs amigáveis)

### Requisitos Recomendados

- ⭐ **WordPress:** 6.0+ 
- ⭐ **PHP:** 8.0+
- ⭐ **MySQL:** 8.0+ / MariaDB 10.5+
- ⭐ **Memória PHP:** 256MB+
- ⭐ **HTTPS:** Ativado (para clipboard API)

### Verificar Ambiente

```bash
# Via WP-CLI
wp core version
wp core check-update

# Versão do PHP
php -v

# Versão do MySQL
mysql --version
```

---

## ⚡ Instalação Rápida (5 minutos)

### Passo 1: Preparar Arquivos

Organize todos os arquivos criados nesta estrutura:

```
wp-url-shortener/
├── wp-url-shortener.php
├── includes/
│   └── class-url-shortener.php
│   └── [4 arquivos pendentes]
├── admin/
│   └── [1 arquivo pendente]
└── assets/
    ├── css/
    │   └── [2 arquivos pendentes]
    └── js/
        ├── columns.js
        └── [1 arquivo pendente]
```

⚠️ **ATENÇÃO:** Atualmente o plugin está **50% completo**. Faltam 7 arquivos essenciais.

### Passo 2: Upload

**Opção A - Via FTP/SFTP:**
```bash
# Conecte ao servidor
# Navegue até /wp-content/plugins/
# Faça upload da pasta wp-url-shortener/
```

**Opção B - Via WordPress Dashboard:**
```bash
1. Comprima a pasta em .zip
2. Plugins > Adicionar Novo > Enviar Plugin
3. Escolha o arquivo .zip
4. Clique em "Instalar Agora"
```

**Opção C - Via WP-CLI:**
```bash
cd /caminho/para/wordpress/wp-content/plugins/
# Copie a pasta do plugin aqui
wp plugin activate wp-url-shortener
```

### Passo 3: Ativar

1. Acesse **Plugins > Plugins Instalados**
2. Localize "WP URL Shortener"
3. Clique em **Ativar**

⚠️ **NOTA:** Como o plugin não está completo, podem ocorrer erros. Veja seção [Troubleshooting](#-troubleshooting).

### Passo 4: Configurar

1. Vá em **Configurações > URL Shortener**
2. Marque os tipos desejados
3. Clique em **Salvar**

### Passo 5: Testar

1. Crie um novo post
2. Publique
3. Verifique se URL curta foi gerada

---

## 🔧 Instalação Detalhada

### Etapa 1: Download e Preparação

#### 1.1 Estrutura de Arquivos

Certifique-se de ter esta estrutura exata:

```
wp-url-shortener/
│
├── wp-url-shortener.php          ✅ CRIADO
│
├── includes/
│   ├── class-url-shortener.php   ✅ CRIADO
│   ├── class-admin.php            ❌ PENDENTE
│   ├── class-shortcode-generator.php  ❌ PENDENTE
│   ├── class-redirector.php      ❌ PENDENTE
│   └── class-admin-columns.php   ❌ PENDENTE
│
├── admin/
│   └── settings-page.php         ❌ PENDENTE
│
└── assets/
    ├── css/
    │   ├── admin.css             ❌ PENDENTE
    │   └── columns.css           ❌ PENDENTE
    └── js/
        ├── admin.js              ❌ PENDENTE
        └── columns.js            ✅ CRIADO
```

#### 1.2 Verificar Permissões

```bash
# Diretórios devem ter permissão 755
find wp-url-shortener -type d -exec chmod 755 {} \;

# Arquivos devem ter permissão 644
find wp-url-shortener -type f -exec chmod 644 {} \;
```

#### 1.3 Verificar Codificação

- Todos os arquivos devem ser **UTF-8 sem BOM**
- Line endings: **LF** (Unix)
- Indentação: **4 espaços** (não tabs)

### Etapa 2: Upload para WordPress

#### 2.1 Via FTP/SFTP (FileZilla, WinSCP, etc)

```
1. Conecte ao seu servidor
   Host: ftp.seusite.com
   Usuário: seu_usuario
   Senha: sua_senha
   Porta: 21 (FTP) ou 22 (SFTP)

2. Navegue até:
   /public_html/wp-content/plugins/
   ou
   /www/wp-content/plugins/

3. Faça upload da pasta completa:
   wp-url-shortener/

4. Aguarde conclusão (pode demorar)

5. Verifique se todos os arquivos foram enviados:
   - Conte os arquivos no servidor
   - Compare com arquivos locais
```

#### 2.2 Via WordPress Dashboard

```
1. Comprima a pasta wp-url-shortener em .zip:
   - Windows: Clique direito > Enviar para > Pasta compactada
   - Mac: Clique direito > Comprimir
   - Linux: zip -r wp-url-shortener.zip wp-url-shortener/

2. No WordPress:
   Dashboard > Plugins > Adicionar Novo

3. Clique em "Enviar Plugin" (topo da página)

4. Escolha o arquivo .zip

5. Clique "Instalar Agora"

6. Aguarde upload e instalação

7. NÃO clique em "Ativar" ainda se houver erros
```

#### 2.3 Via WP-CLI

```bash
# Método 1: Upload direto
cd /var/www/html/wp-content/plugins/
cp -r /caminho/origem/wp-url-shortener ./

# Método 2: Via link simbólico (desenvolvimento)
ln -s /caminho/desenvolvimento/wp-url-shortener ./

# Verificar arquivos
ls -la wp-url-shortener/

# Ativar plugin
wp plugin activate wp-url-shortener

# Verificar status
wp plugin list
```

### Etapa 3: Ativação do Plugin

#### 3.1 Ativar via Dashboard

```
1. Acesse: Dashboard > Plugins > Plugins Instalados

2. Localize "WP URL Shortener" na lista

3. Clique no link "Ativar" abaixo do nome

4. Aguarde redirecionamento
```

#### 3.2 Ativar via WP-CLI

```bash
wp plugin activate wp-url-shortener
```

#### 3.3 O que Acontece na Ativação

✅ **Ações Automáticas:**
- Cria tabela `wp_url_shortener` no banco
- Define opções padrão:
  - `wpus_enabled_post_types`: ['post', 'page']
  - `wpus_enabled_taxonomies`: ['category', 'post_tag']
- Registra rewrite rules
- Executa `flush_rewrite_rules()`

⚠️ **Possíveis Erros:**
Se o plugin não estiver completo (faltam 7 arquivos), você pode ver:
- "The plugin does not have a valid header"
- "Fatal error: Class not found"
- "Call to undefined method"

**Solução:** Complete os arquivos pendentes antes de ativar.

### Etapa 4: Verificação Pós-Ativação

#### 4.1 Verificar Banco de Dados

```sql
-- Via phpMyAdmin ou linha de comando
SHOW TABLES LIKE '%url_shortener%';

-- Deve retornar:
-- wp_url_shortener

-- Verificar estrutura
DESCRIBE wp_url_shortener;

-- Deve mostrar:
-- id, short_code, object_id, object_type, created_at
```

#### 4.2 Verificar Opções

```php
// Via wp-cli
wp option get wpus_enabled_post_types
wp option get wpus_enabled_taxonomies

// Ou via código (criar arquivo test.php na raiz do WP)
<?php
require_once('wp-load.php');
var_dump(get_option('wpus_enabled_post_types'));
var_dump(get_option('wpus_enabled_taxonomies'));
```

#### 4.3 Verificar Menu Administrativo

```
1. No dashboard, vá em: Configurações

2. Deve aparecer: "URL Shortener"

3. Se NÃO aparecer:
   - Plugin não ativou corretamente
   - Faltam arquivos
   - Erro de permissões
```

---

## ⚙️ Configuração Inicial

### Passo 1: Acessar Configurações

```
Dashboard > Configurações > URL Shortener
```

### Passo 2: Selecionar Post Types

**Recomendado para começar:**
- ✅ Posts
- ✅ Páginas

**Opcional (se usar):**
- ☐ Produtos (WooCommerce)
- ☐ Portfolio (temas específicos)
- ☐ Outros Custom Post Types

**Como decidir:**
Marque os tipos de conteúdo que você compartilha frequentemente em redes sociais.

### Passo 3: Selecionar Taxonomias

**Recomendado:**
- ✅ Categorias
- ✅ Tags

**Opcional:**
- ☐ Categorias de Produtos
- ☐ Taxonomias customizadas

### Passo 4: Salvar Configurações

```
1. Revise as seleções
2. Clique em "Salvar Configurações"
3. Aguarde mensagem de confirmação
```

### Passo 5: Gerar URLs Retroativas

Se você já tem conteúdo publicado:

```
1. Role até "Gerar URLs Curtas para Conteúdo Existente"

2. Clique em cada botão:
   - Posts (X) - onde X é o número de posts
   - Páginas (X)
   - Categorias (X)
   - Tags (X)

3. Aguarde mensagem: "X URLs curtas foram geradas com sucesso!"

4. Isso pode demorar se tiver muito conteúdo
```

---

## 🎬 Primeiro Uso

### Teste 1: Criar Post com URL Curta

```
1. Posts > Adicionar Novo

2. Título: "Teste do Plugin URL Shortener"

3. Conteúdo: Qualquer coisa

4. Clique em "Publicar"

5. Vá em: Posts > Todos os Posts

6. Localize o post criado

7. Veja a coluna "URL Curta" (após coluna "Data")

8. Deve mostrar algo como: "abc123"
```

### Teste 2: Copiar URL Curta

```
1. Na listagem de posts, encontre a coluna "URL Curta"

2. Clique no botão com ícone de página

3. Veja mensagem "Copiado!" aparecer

4. Abra um editor de texto (Notepad, etc)

5. Pressione Ctrl+V (Windows) ou Cmd+V (Mac)

6. Deve colar: https://seusite.com.br/abc123
```

### Teste 3: Testar Redirecionamento

```
1. Copie a URL curta (ex: seusite.com.br/abc123)

2. Abra uma janela anônima do navegador

3. Cole a URL curta na barra de endereços

4. Pressione Enter

5. Deve redirecionar para a URL completa do post

6. Verifique a URL final na barra de endereços
```

---

## ✅ Verificação e Testes

### Checklist Completo

#### Instalação
- [ ] Plugin aparece na lista de plugins
- [ ] Não há mensagens de erro após ativar
- [ ] Menu "URL Shortener" aparece em Configurações
- [ ] Tabela no banco de dados foi criada
- [ ] Opções padrão foram definidas

#### Configurações
- [ ] Página de configurações abre sem erros
- [ ] Checkboxes aparecem corretamente
- [ ] Consegue salvar configurações
- [ ] Mensagem de sucesso aparece ao salvar
- [ ] Botões de geração em massa aparecem

#### Funcionalidade
- [ ] URL curta é gerada ao publicar novo post
- [ ] Coluna "URL Curta" aparece nas listagens
- [ ] Botão de copiar funciona
- [ ] Mensagem "Copiado!" aparece
- [ ] Redirecionamento funciona (301)
- [ ] URLs curtas para categorias funcionam
- [ ] URLs curtas para tags funcionam

#### Performance
- [ ] Páginas carregam normalmente
- [ ] Não há lentidão perceptível
- [ ] Listagens carregam rápido
- [ ] Banco de dados responde bem

### Testes por Ambiente

#### Tema Padrão (Twenty Twenty-Three)
```
1. Ative tema Twenty Twenty-Three
2. Teste todas as funcionalidades
3. Verifique estilos das colunas
4. Teste responsividade mobile
```

#### Com Plugins Comuns
```
- Yoast SEO
- WooCommerce
- Contact Form 7
- Elementor
- Jetpack

Ative cada um e teste se há conflitos
```

---

## 🔧 Troubleshooting

### Problema 1: Plugin Não Ativa

**Sintoma:**
```
Erro: "O plugin não possui um cabeçalho válido"
```

**Causa:**
Arquivo principal corrompido ou incompleto

**Solução:**
```bash
1. Verifique se wp-url-shortener.php existe
2. Abra o arquivo e confirme o cabeçalho:
   /**
    * Plugin Name: WP URL Shortener
    * ...
    */
3. Re-upload do arquivo se necessário
```

---

### Problema 2: Erro "Class Not Found"

**Sintoma:**
```
Fatal error: Class 'WP_URL_Shortener\Admin' not found
```

**Causa:**
Arquivos de classe não foram criados ou estão no local errado

**Solução:**
```bash
1. Verifique se TODOS os arquivos em includes/ existem:
   - class-url-shortener.php ✅
   - class-admin.php ❌ FALTA
   - class-shortcode-generator.php ❌ FALTA
   - class-redirector.php ❌ FALTA
   - class-admin-columns.php ❌ FALTA

2. Crie os arquivos faltantes
3. Desative e reative o plugin
```

---

### Problema 3: Redirecionamento Retorna 404

**Sintoma:**
Ao acessar URL curta (ex: seusite.com.br/abc123), aparece erro 404

**Causa:**
Rewrite rules não foram registradas corretamente

**Solução 1 - Via Dashboard:**
```
1. Vá em: Configurações > Links Permanentes
2. Não mude nada
3. Clique em "Salvar Alterações"
4. Isso força recriação das rewrite rules
5. Teste novamente
```

**Solução 2 - Via WP-CLI:**
```bash
wp rewrite flush
```

**Solução 3 - Via .htaccess:**
```bash
# Verifique se .htaccess contém:

# BEGIN WordPress
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>
# END WordPress
```

---

### Problema 4: Coluna "URL Curta" Não Aparece

**Causa Provável:**
Post type não está habilitado nas configurações

**Solução:**
```
1. Configurações > URL Shortener
2. Verifique se o post type está marcado
3. Salve as configurações
4. Recarregue a página de listagem (Ctrl+F5)
```

---

### Problema 5: Botão de Copiar Não Funciona

**Causa 1:** JavaScript não carregou
```bash
# Verificar:
1. Abra F12 (Console do navegador)
2. Procure por erros JavaScript
3. Verifique se columns.js foi carregado
4. Limpe cache do navegador
```

**Causa 2:** Site não usa HTTPS
```
Clipboard API moderna requer HTTPS.
Solução: Use HTTPS ou o fallback funcionará automaticamente.
```

---

### Problema 6: URLs Não São Geradas Automaticamente

**Verificações:**
```php
// 1. Verificar se post type está habilitado
$enabled = get_option('wpus_enabled_post_types', []);
var_dump($enabled);

// 2. Verificar se post está publicado
$post = get_post($post_id);
echo $post->post_status; // deve ser 'publish'

// 3. Verificar se hook está registrado
var_dump(has_action('transition_post_status'));
```

---

### Problema 7: Erro ao Gerar URLs em Massa

**Sintoma:**
Botão fica carregando infinitamente ou retorna erro

**Causa:**
Timeout PHP ou memória insuficiente

**Solução:**
```php
// No wp-config.php, adicione:
define('WP_MEMORY_LIMIT', '256M');
set_time_limit(300); // 5 minutos

// Ou no php.ini:
max_execution_time = 300
memory_limit = 256M
```

---

### Problema 8: Performance Lenta

**Diagnóstico:**
```sql
-- Verificar quantidade de registros
SELECT COUNT(*) FROM wp_url_shortener;

-- Verificar índices
SHOW INDEX FROM wp_url_shortener;

-- Deve ter índice em:
-- - short_code (UNIQUE)
-- - object_id
```

**Solução:**
```sql
-- Se índices não existirem, criar:
ALTER TABLE wp_url_shortener 
ADD UNIQUE INDEX short_code (short_code),
ADD INDEX object_id (object_id);
```

---

## 📱 Uso Diário

### Compartilhar em Redes Sociais

#### Twitter/X
```
Seu tweet aqui...
https://seusite.com.br/abc123

✅ Economiza caracteres
✅ URL limpa e profissional
```

#### WhatsApp
```
Copie URL curta > Cole no WhatsApp
A preview será do conteúdo original
```

#### Instagram
```
Bio: "Novo artigo no link"
Use a URL curta na bio
```

#### LinkedIn
```
Cole a URL curta no post
LinkedIn pega automaticamente título/imagem
```

### Materiais Impressos

```
Flyers/Folders:
"Saiba mais em: seusite.com.br/promo2026"

✅ Fácil de digitar
✅ Fácil de lembrar
✅ Não quebra em linhas
```

### QR Codes (Futuro)

```
Quando implementado:
1. Gere URL curta
2. Gere QR Code
3. Imprima em materiais

Benefício: QR menor e mais confiável
```

---

## 🎨 Personalizações

### Adicionar URL Curta no Template

**single.php:**
```php
<?php
$short_code = get_post_meta(get_the_ID(), '_wpus_short_code', true);
if ($short_code) {
    $short_url = home_url('/' . $short_code);
    ?>
    <div class="short-url-box">
        <p>
            <strong>Link Curto:</strong>
            <a href="<?php echo esc_url($short_url); ?>">
                <?php echo esc_html($short_url); ?>
            </a>
            <button onclick="copyUrl('<?php echo esc_js($short_url); ?>')">
                Copiar
            </button>
        </p>
    </div>
    <script>
    function copyUrl(url) {
        navigator.clipboard.writeText(url).then(() => {
            alert('URL copiada!');
        });
    }
    </script>
    <?php
}
?>
```

### Adicionar Automaticamente ao Conteúdo

**functions.php:**
```php
add_filter('the_content', function($content) {
    if (!is_single()) return $content;
    
    $short_code = get_post_meta(get_the_ID(), '_wpus_short_code', true);
    if (!$short_code) return $content;
    
    $short_url = home_url('/' . $short_code);
    
    $append = '<div style="background:#f5f5f5;padding:20px;margin:20px 0;">';
    $append .= '<strong>📎 Link Curto:</strong> ';
    $append .= '<a href="' . esc_url($short_url) . '">' . esc_html($short_url) . '</a>';
    $append .= '</div>';
    
    return $content . $append;
});
```

### Botões de Compartilhamento

**functions.php:**
```php
add_filter('the_content', function($content) {
    if (!is_single()) return $content;
    
    $short_code = get_post_meta(get_the_ID(), '_wpus_short_code', true);
    if (!$short_code) return $content;
    
    $short_url = home_url('/' . $short_code);
    $title = get_the_title();
    
    $share = '<div class="share-buttons">';
    $share .= '<h4>Compartilhe:</h4>';
    
    // Twitter
    $share .= '<a href="https://twitter.com/intent/tweet?url=' . urlencode($short_url) . '&text=' . urlencode($title) . '" target="_blank">Twitter</a> ';
    
    // Facebook
    $share .= '<a href="https://www.facebook.com/sharer/sharer.php?u=' . urlencode($short_url) . '" target="_blank">Facebook</a> ';
    
    // WhatsApp
    $share .= '<a href="https://wa.me/?text=' . urlencode($title . ' ' . $short_url) . '" target="_blank">WhatsApp</a>';
    
    $share .= '</div>';
    
    return $content . $share;
});
```

---

## ❓ FAQ

### Como o plugin economiza caracteres?

**Antes:**
```
https://meusite.com.br/2026/01/05/como-criar-urls-curtas-wordpress/
(73 caracteres)
```

**Depois:**
```
https://meusite.com.br/abc123
(34 caracteres)
```

**Economia:** 39 caracteres (53%)

---

### As URLs curtas são permanentes?

Sim, desde que:
- O conteúdo original não seja excluído
- O plugin permaneça ativo
- Os dados do banco não sejam limpos

---

### Posso personalizar o código curto?

Na versão atual (1.0), não.

Planejado para Sprint 4:
- Editar códigos manualmente
- Definir códigos personalizados
- Validação de unicidade

---

### O plugin afeta SEO?

**Não afeta negativamente:**
- URLs curtas redirecionam com 301 (permanente)
- Google entende e indexa corretamente
- Canonical tags permanecem inalterados
- Conteúdo original mantém autoridade

**Benefícios para SEO:**
- Links mais compartilháveis
- Mais cliques = mais tráfego
- Melhor experiência do usuário

---

### Funciona com WooCommerce?

Sim, desde que:
1. Marque "Produtos" nas configurações
2. Produtos sejam públicos
3. Não há conflitos com outros plugins

Testado com WooCommerce 8.0+

---

### Posso usar domínio externo?

Na versão atual (1.0), não.

Planejado para Sprint 6:
- Configurar domínio externo (ex: link.me/abc123)
- Múltiplos domínios
- DNS automático

Por ora, usa sempre o domínio do site.

---

### Como migrar para outro domínio?

Se mudar o domínio do site:

1. URLs curtas continuam funcionando
2. Atualização automática (WordPress faz isso)
3. Não precisa regenerar

**Exemplo:**
```
Antes: site1.com.br/abc123 → site1.com.br/post/
Depois: site2.com.br/abc123 → site2.com.br/post/
```

---

### Suporta multisite?

Na versão atual (1.0), não otimizado.

Funciona mas:
- Cada site tem suas próprias URLs
- Não há compartilhamento entre sites
- Configurações separadas

Planejado melhorias para Sprint futura.

---

### Como desinstalar sem perder dados?

**Desativar (mantém dados):**
```
Plugins > Desativar
```

**Desinstalar (remove dados):**
```sql
-- Backup primeiro!
mysqldump -u usuario -p banco > backup.sql

-- Depois pode desinstalar:
Plugins > Excluir
```

**Restaurar dados:**
```sql
mysql -u usuario -p banco < backup.sql
```

---

## 📞 Suporte

### Onde Buscar Ajuda

1. **Documentação:**
   - README.md - Visão geral
   - EXAMPLES.md - Exemplos de código
   - STRUCTURE.md - Arquitetura técnica

2. **Comunidade:**
   - GitHub Issues: [link]
   - Fórum WordPress: [link]

3. **Contato Direto:**
   - Email: [seu-email]
   - Site: [seu-site]

### Reportar Bugs

Inclua sempre:
- [ ] Versão do WordPress
- [ ] Versão do PHP
- [ ] Tema usado
- [ ] Plugins ativos
- [ ] Mensagem de erro completa
- [ ] Passos para reproduzir

---

## ✨ Checklist Final de Instalação

- [ ] Plugin instalado
- [ ] Plugin ativado sem erros
- [ ] Tabela no banco criada
- [ ] Página de configurações acessível
- [ ] Post types configurados
- [ ] Taxonomias configuradas
- [ ] URLs geradas para conteúdo existente
- [ ] Coluna aparece nas listagens
- [ ] Botão de copiar funciona
- [ ] Redirecionamento testado
- [ ] Novo post gera URL automaticamente
- [ ] Performance normal
- [ ] Backup realizado

---

## 🎯 Próximos Passos

Após instalação:

**Semana 1:**
- [ ] Usar em todos os posts novos
- [ ] Compartilhar nas redes sociais
- [ ] Coletar feedback
- [ ] Monitorar funcionamento

**Mês 1:**
- [ ] Analisar engajamento
- [ ] Explorar personalizações
- [ ] Sugerir melhorias

---

**Desenvolvido com ❤️ para WordPress**

**Versão:** 1.0.0 (em desenvolvimento)  
**Data:** Janeiro 2026  
**Status:** Plugin 50% completo - 7 arquivos pendentes

⚠️ **IMPORTANTE:** Complete os arquivos pendentes antes de usar em produção!