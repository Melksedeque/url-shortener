# Guia de Instalação - URL Shortener

## 📦 Pré-requisitos

- WordPress 5.0 ou superior
- PHP 7.4 ou superior
- MySQL 5.6 ou superior / MariaDB 10.0 ou superior

## 🔧 Instalação Completa

### Passo 1: Preparar os Arquivos

Organize todos os arquivos criados na seguinte estrutura:

```
url-shortener/
├── url-shortener.php
├── README.md
├── INSTALLATION.md
├── includes/
│   ├── class-url-shortener.php
│   ├── class-admin.php
│   ├── class-shortcode-generator.php
│   ├── class-redirector.php
│   └── class-admin-columns.php
├── admin/
│   └── settings-page.php
└── assets/
    ├── css/
    │   ├── admin.css
    │   └── columns.css
    └── js/
        ├── admin.js
        └── columns.js
```

### Passo 2: Upload para WordPress

**Opção A - Via FTP/SFTP:**
1. Conecte-se ao seu servidor via FTP
2. Navegue até `/wp-content/plugins/`
3. Faça upload da pasta `url-shortener` completa

**Opção B - Via Painel WordPress:**
1. Comprima a pasta `url-shortener` em um arquivo `.zip`
2. Acesse **Plugins > Adicionar Novo > Enviar Plugin**
3. Escolha o arquivo `.zip` e clique em "Instalar Agora"

**Opção C - Via WP-CLI:**
```bash
# Navegue até a pasta de plugins
cd /caminho/para/wordpress/wp-content/plugins/

# Clone ou mova a pasta do plugin
mv /caminho/origem/url-shortener ./

# Ative o plugin
wp plugin activate url-shortener
```

### Passo 3: Ativar o Plugin

1. Acesse **Plugins > Plugins Instalados**
2. Localize "URL Shortener"
3. Clique em **Ativar**

### Passo 4: Verificar a Ativação

Após ativar, o plugin irá:
- ✅ Criar a tabela `url_shortener` no banco de dados
- ✅ Configurar as opções padrão (Posts e Páginas habilitados)
- ✅ Registrar as rewrite rules

**Para verificar:**
1. Acesse **Configurações > URL Shortener**
2. Você deve ver a página de configurações sem erros

### Passo 5: Configuração Inicial

1. Em **Configurações > URL Shortener**
2. Revise os **Post Types** selecionados
3. Revise as **Taxonomias** selecionadas
4. Clique em **Salvar Configurações**

### Passo 6: Gerar URLs para Conteúdo Existente

1. Na mesma página de configurações
2. Role até "Gerar URLs Curtas para Conteúdo Existente"
3. Clique nos botões para os tipos de conteúdo desejados
4. Aguarde a confirmação de geração

## ✅ Verificação da Instalação

### Teste 1: Verificar Criação de URL Curta

1. Crie um novo post
2. Publique o post
3. Acesse **Posts > Todos os Posts**
4. Verifique se a coluna "URL Curta" aparece
5. Deve haver um código de 5-7 caracteres

### Teste 2: Testar Redirecionamento

1. Copie a URL curta de um post
2. Cole no navegador (exemplo: `seusite.com.br/abc123`)
3. Deve redirecionar para o post completo

### Teste 3: Testar Botão de Copiar

1. Na listagem de posts, clique no botão de copiar
2. A mensagem "Copiado!" deve aparecer
3. Cole em algum lugar para confirmar que foi copiado

### Teste 4: Verificar Rewrite Rules

Se o redirecionamento não funcionar:

1. Acesse **Configurações > Links Permanentes**
2. Clique em **Salvar Alterações** (sem mudar nada)
3. Isso força a recriação das rewrite rules
4. Teste o redirecionamento novamente

## 🔧 Troubleshooting

### Problema: Página de Configurações não Aparece

**Solução:**
```php
// Verifique se há erros PHP
// Ative WP_DEBUG no wp-config.php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);

// Verifique o arquivo de log em wp-content/debug.log
```

### Problema: Coluna "URL Curta" não Aparece

**Causas possíveis:**
1. O post type não está habilitado nas configurações
2. Cache do navegador (Ctrl+F5 para recarregar)
3. Conflito com outro plugin

**Solução:**
1. Verifique as configurações do plugin
2. Desative outros plugins temporariamente
3. Troque de tema para o Twenty Twenty-Three

### Problema: Redirecionamento Retorna 404

**Causas possíveis:**
1. Rewrite rules não foram registradas
2. `.htaccess` com configurações conflitantes

**Solução:**
```bash
# 1. Vá em Configurações > Links Permanentes e salve
# 2. Se não funcionar, verifique o .htaccess

# Conteúdo padrão do .htaccess do WordPress:
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

### Problema: Tabela não foi Criada

**Solução Manual:**
```sql
CREATE TABLE IF NOT EXISTS `url_shortener` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `short_code` varchar(7) NOT NULL,
  `object_id` bigint(20) NOT NULL,
  `object_type` varchar(20) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `short_code` (`short_code`),
  KEY `object_id` (`object_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Problema: JavaScript não Carrega

**Verificações:**
1. Limpe o cache do navegador
2. Verifique o console do navegador (F12)
3. Confirme que os arquivos CSS/JS existem
4. Verifique permissões dos arquivos (644)

### Problema: Botão de Copiar não Funciona

**Causas:**
1. Navegador muito antigo
2. Site não está em HTTPS (clipboard API requer SSL)
3. JavaScript desabilitado

**Solução:**
- O plugin tem fallback para navegadores antigos
- Use HTTPS sempre que possível
- Verifique o console do navegador para erros

## 🔄 Atualização do Plugin

### Via Upload Manual

1. **Backup:** Faça backup do seu site antes
2. **Desative:** Desative o plugin (não delete)
3. **Substitua:** Substitua os arquivos pela nova versão
4. **Ative:** Ative o plugin novamente
5. **Teste:** Verifique se tudo funciona

### Preservar Dados

O plugin **NÃO** deleta dados ao ser desativado. Os dados são mantidos:
- Tabela `url_shortener`
- Post meta `_wpus_short_code`
- Term meta `_wpus_short_code`
- Opções `wpus_enabled_post_types` e `wpus_enabled_taxonomies`

## 🗑️ Desinstalação Completa

### Para Remover o Plugin e MANTER os Dados

1. Desative o plugin em **Plugins > Plugins Instalados**
2. Delete o plugin (os dados ficarão no banco)

### Para Remover o Plugin e TODOS os Dados

Execute este SQL no phpMyAdmin:

```sql
-- Remove tabela
DROP TABLE IF EXISTS `url_shortener`;

-- Remove post meta
DELETE FROM `wp_postmeta` WHERE `meta_key` = '_wpus_short_code';

-- Remove term meta
DELETE FROM `wp_termmeta` WHERE `meta_key` = '_wpus_short_code';

-- Remove opções
DELETE FROM `wp_options` WHERE `option_name` LIKE 'wpus_%';
```

Depois delete os arquivos do plugin.

## 📋 Checklist Pós-Instalação

- [ ] Plugin ativado com sucesso
- [ ] Página de configurações acessível
- [ ] Post types desejados habilitados
- [ ] Taxonomias desejadas habilitadas
- [ ] URLs geradas para conteúdo existente
- [ ] Coluna "URL Curta" visível nas listagens
- [ ] Botão de copiar funcionando
- [ ] Redirecionamento testado e funcionando
- [ ] Novo post gera URL curta automaticamente

## 🆘 Suporte

Se após seguir este guia você ainda tiver problemas:

1. Verifique o arquivo `debug.log` do WordPress
2. Teste com todos os plugins desativados exceto este
3. Teste com um tema padrão do WordPress
4. Verifique a versão do PHP e MySQL
5. Entre em contato compartilhando:
   - Versão do WordPress
   - Versão do PHP
   - Mensagens de erro específicas
   - Passos para reproduzir o problema

## 🔐 Permissões de Arquivos Recomendadas

```bash
# Diretórios
chmod 755 url-shortener/
chmod 755 url-shortener/includes/
chmod 755 url-shortener/admin/
chmod 755 url-shortener/assets/
chmod 755 url-shortener/assets/css/
chmod 755 url-shortener/assets/js/

# Arquivos
chmod 644 url-shortener/url-shortener.php
chmod 644 url-shortener/includes/*.php
chmod 644 url-shortener/admin/*.php
chmod 644 url-shortener/assets/css/*.css
chmod 644 url-shortener/assets/js/*.js
```

---

**Instalação concluída! 🎉**

Seu plugin está pronto para uso. Para mais informações, consulte o [README.md](README.md).