# Estrutura do Projeto - URL Shortener

## 📁 Árvore Completa de Arquivos

```
url-shortener/
│
├── 📄 url-shortener.php          # Arquivo principal do plugin
├── 📄 README.md                      # Documentação principal
├── 📄 INSTALLATION.md                # Guia de instalação
├── 📄 EXAMPLES.md                    # Exemplos de uso
├── 📄 CHANGELOG.md                   # Histórico de versões
├── 📄 LICENSE                        # Licença GPL v2
├── 📄 .gitignore                     # Arquivos ignorados pelo Git
│
├── 📁 includes/                      # Classes principais do plugin
│   ├── 📄 class-url-shortener.php   # Classe principal (singleton)
│   ├── 📄 class-admin.php            # Interface administrativa
│   ├── 📄 class-shortcode-generator.php  # Gerador de códigos Base62
│   ├── 📄 class-redirector.php      # Sistema de redirecionamento
│   └── 📄 class-admin-columns.php   # Colunas personalizadas
│
├── 📁 admin/                         # Templates administrativos
│   └── 📄 settings-page.php         # Template da página de configurações
│
└── 📁 assets/                        # Assets estáticos (CSS/JS)
    ├── 📁 css/
    │   ├── 📄 admin.css             # Estilos da página de configurações
    │   └── 📄 columns.css           # Estilos das colunas
    └── 📁 js/
        ├── 📄 admin.js              # JavaScript da página de configurações
        └── 📄 columns.js            # JavaScript das colunas (copiar)
```

---

## 📋 Descrição Detalhada dos Arquivos

### Raiz do Plugin

#### `url-shortener.php` (Arquivo Principal)
**Responsabilidade:** Ponto de entrada do plugin
- Define constantes do plugin
- Configura autoloader de classes
- Registra hooks de ativação/desativação
- Inicializa o plugin

**Constantes Definidas:**
```php
URL_SHORTENER_VERSION       // Versão do plugin
URL_SHORTENER_PLUGIN_DIR    // Diretório do plugin
URL_SHORTENER_PLUGIN_URL    // URL do plugin
URL_SHORTENER_PLUGIN_FILE   // Caminho completo do arquivo
```

---

### Pasta `includes/`

#### `class-url-shortener.php`
**Responsabilidade:** Classe principal (padrão Singleton)
- Gerencia instância única do plugin
- Inicializa todos os componentes
- Registra hooks principais
- Gerencia ativação/desativação

**Métodos Principais:**
- `get_instance()` - Obtém instância única
- `run()` - Inicializa o plugin
- `load_textdomain()` - Carrega traduções
- `generate_on_publish()` - Gera URL ao publicar
- `generate_on_term_create()` - Gera URL ao criar termo
- `activate()` - Hook de ativação
- `deactivate()` - Hook de desativação

**Hooks:**
- `init` - Carrega textdomain e rewrite rules
- `template_redirect` - Gerencia redirecionamentos
- `transition_post_status` - Gera URL ao publicar
- `created_term` - Gera URL ao criar termo

---

#### `class-admin.php`
**Responsabilidade:** Interface administrativa
- Cria página de configurações
- Gerencia salvamento de opções
- Processa geração em massa via AJAX
- Enfileira assets do admin

**Métodos Principais:**
- `init()` - Registra hooks do admin
- `add_admin_menu()` - Adiciona menu no WordPress
- `register_settings()` - Registra configurações
- `render_settings_page()` - Renderiza página
- `enqueue_admin_assets()` - Carrega CSS/JS
- `ajax_generate_bulk()` - Handler AJAX

**Actions AJAX:**
- `wpus_generate_bulk` - Gera URLs em massa

---

#### `class-shortcode-generator.php`
**Responsabilidade:** Geração de códigos curtos
- Algoritmo Base62
- Geração baseada em ID
- Inserção no banco de dados
- Geração em massa

**Métodos Principais:**
- `base62_encode()` - Converte número para Base62
- `generate_hash()` - Gera hash com salt
- `generate_for_post()` - Gera URL para post
- `generate_for_term()` - Gera URL para termo
- `get_short_url()` - Monta URL completa
- `generate_bulk_for_posts()` - Gera em massa (posts)
- `generate_bulk_for_terms()` - Gera em massa (termos)

**Características do Algoritmo:**
- Base62: `0-9a-zA-Z` (62 caracteres)
- Salt por tipo: posts (+10000), termos (+20000)
- Comprimento: 5-7 caracteres
- Determinístico: mesmo ID = mesmo código

---

#### `class-redirector.php`
**Responsabilidade:** Sistema de redirecionamento
- Registra rewrite rules
- Captura URLs curtas
- Busca destino no banco
- Executa redirecionamento 301

**Métodos Principais:**
- `add_rewrite_rules()` - Registra regras
- `add_query_vars()` - Adiciona query var
- `handle_redirect()` - Processa redirecionamento
- `track_click()` - Prepara tracking (futuro)

**Rewrite Rule:**
```regex
^([0-9a-zA-Z]{5,7})/?$
```
Captura códigos de 5-7 caracteres na raiz

**Query Var:**
- `wpus_short` - Contém o código curto

**Hook para Extensões:**
- `wpus_short_url_clicked` - Disparado ao clicar

---

#### `class-admin-columns.php`
**Responsabilidade:** Colunas personalizadas
- Adiciona coluna "URL Curta"
- Renderiza botão de copiar
- Gerencia feedback visual
- Enfileira assets específicos

**Métodos Principais:**
- `init()` - Registra hooks de colunas
- `add_post_column()` - Adiciona coluna (posts)
- `render_post_column()` - Renderiza célula (posts)
- `add_term_column()` - Adiciona coluna (termos)
- `render_term_column()` - Renderiza célula (termos)
- `render_url_with_copy()` - HTML do botão
- `enqueue_column_assets()` - Carrega CSS/JS

**Posicionamento:**
- Posts: após coluna "date"
- Termos: após coluna "slug"

---

### Pasta `admin/`

#### `settings-page.php`
**Responsabilidade:** Template da página de configurações
- Formulário de configurações
- Checkboxes de post types
- Checkboxes de taxonomias
- Botões de geração em massa
- Card de informações

**Sections:**
1. Configurações de Geração Automática
2. Gerar URLs para Conteúdo Existente
3. Como Funciona (informativo)

---

### Pasta `assets/css/`

#### `admin.css`
**Responsabilidade:** Estilos da página de configurações
- Layout dos cards
- Estilo dos botões
- Animação de loading
- Mensagens de feedback
- Responsividade

**Classes Principais:**
- `.wpus-admin-container` - Container principal
- `.wpus-card` - Cards de seção
- `.wpus-button-group` - Grupo de botões
- `.wpus-generate-bulk` - Botão de geração
- `#wpus-bulk-result` - Mensagem de resultado

---

#### `columns.css`
**Responsabilidade:** Estilos das colunas
- Layout do wrapper
- Estilo do código curto
- Botão de copiar
- Mensagem "Copiado!"
- Animações

**Classes Principais:**
- `.wpus-url-wrapper` - Container da URL
- `.wpus-short-url` - Código curto
- `.wpus-copy-btn` - Botão de copiar
- `.wpus-copied-message` - Mensagem de feedback

---

### Pasta `assets/js/`

#### `admin.js`
**Responsabilidade:** JavaScript da página de configurações
- Handler de geração em massa
- Requisições AJAX
- Estados de loading
- Exibição de resultados
- Auto-hide de mensagens

**Funcionalidades:**
- Click handler nos botões de geração
- Prevenção de múltiplos cliques
- Animação de loading no botão
- Exibição de mensagens de sucesso/erro
- Auto-hide após 5 segundos

**Objeto Global:**
```javascript
wpusAdmin {
    ajaxurl: string,      // URL do admin-ajax.php
    nonce: string,        // Token de segurança
    strings: {            // Textos traduzíveis
        generating: string,
        success: string,
        error: string
    }
}
```

---

#### `columns.js`
**Responsabilidade:** JavaScript das colunas
- Copiar URL para clipboard
- Feedback visual
- Fallback para navegadores antigos
- Delegação de eventos

**Funcionalidades:**
- Event delegation para performance
- Clipboard API moderna
- Fallback com `document.execCommand`
- Animação de feedback "Copiado!"
- Timeout de 2 segundos

**Objeto Global:**
```javascript
wpusColumns {
    copiedText: string,   // Texto "Copiado!"
    copyText: string      // Texto "Copiar URL"
}
```

---

## 🗄️ Banco de Dados

### Tabela: `wp_url_shortener`
```sql
CREATE TABLE `wp_url_shortener` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `short_code` varchar(7) NOT NULL,
  `object_id` bigint(20) NOT NULL,
  `object_type` varchar(20) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `short_code` (`short_code`),
  KEY `object_id` (`object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**Campos:**
- `id` - ID único auto-incremento
- `short_code` - Código curto (UNIQUE)
- `object_id` - ID do post/termo
- `object_type` - 'post' ou 'term'
- `created_at` - Data de criação

**Índices:**
- PRIMARY KEY: `id`
- UNIQUE KEY: `short_code` (garante unicidade)
- INDEX: `object_id` (otimiza buscas)

---

### Post Meta: `_wpus_short_code`
Armazena o código curto diretamente no post para acesso rápido.

### Term Meta: `_wpus_short_code`
Armazena o código curto diretamente no termo para acesso rápido.

---

## ⚙️ Opções do WordPress

### `wpus_enabled_post_types`
**Tipo:** Array
**Padrão:** `['post', 'page']`
**Descrição:** Post types habilitados para geração automática

### `wpus_enabled_taxonomies`
**Tipo:** Array
**Padrão:** `['category', 'post_tag']`
**Descrição:** Taxonomias habilitadas para geração automática

---

## 🔄 Fluxo de Funcionamento

### 1. Geração Automática (Publicação)
```
Post publicado
    ↓
Hook: transition_post_status
    ↓
Verifica se post type está habilitado
    ↓
Gera código Base62 baseado no ID
    ↓
Insere na tabela wp_url_shortener
    ↓
Salva em post meta _wpus_short_code
```

### 2. Geração em Massa (Manual)
```
Usuário clica botão
    ↓
JavaScript envia requisição AJAX
    ↓
PHP: wpus_generate_bulk
    ↓
Loop por todos os posts/termos
    ↓
Gera códigos faltantes
    ↓
Retorna contagem
    ↓
JavaScript exibe mensagem
```

### 3. Redirecionamento
```
Usuário acessa seusite.com.br/abc123
    ↓
Rewrite rule captura o código
    ↓
Query var: wpus_short = 'abc123'
    ↓
Busca na tabela wp_url_shortener
    ↓
Encontra object_id e object_type
    ↓
Obtém URL de destino
    ↓
wp_redirect() com status 301
```

---

## 🎯 Pontos de Extensão

### Hooks de Action
```php
// Disparado quando URL curta é acessada
do_action('wpus_short_url_clicked', $short_code, $id);
```

### Hooks de Filter
*Nenhum filter público ainda - preparado para futuras extensões*

---

## 📊 Métricas do Código

### Estatísticas
- **Total de Arquivos PHP:** 6
- **Total de Arquivos CSS:** 2
- **Total de Arquivos JS:** 2
- **Total de Classes:** 5
- **Linhas de Código (aprox.):** ~1.500
- **Métodos Públicos:** ~25
- **Hooks WordPress:** ~15

### Padrões Utilizados
- Singleton Pattern (URL_Shortener)
- Namespace (evita conflitos)
- Autoloader (PSR-4 style)
- MVC simplificado
- Event-driven (hooks)

---

## 🔐 Segurança

### Validação e Sanitização
- `sanitize_text_field()` em inputs
- `esc_html()` em outputs
- `esc_url()` em URLs
- `esc_attr()` em atributos
- `wp_verify_nonce()` em AJAX

### Proteção
- Verificação de `ABSPATH`
- Capabilities check (`manage_options`)
- CSRF tokens (nonces)
- SQL prepared statements

---

## 🚀 Performance

### Otimizações
- Queries indexadas
- Assets carregados apenas onde necessário
- Autoloader eficiente
- Cache de rewrite rules
- Código minificado (produção)

### Carregamento Condicional
- CSS admin: apenas em `settings_page_wp-url-shortener`
- CSS columns: apenas em `edit.php` e `edit-tags.php`
- JS: enfileirado com `in_footer = true`

---

Este documento serve como referência completa da estrutura do plugin. Para informações de uso, consulte o [README.md](README.md).