# URL Shortener

Plugin WordPress para criação e gerenciamento de URLs curtas para posts, páginas, categorias, tags e custom post types.

## 📋 Características Atuais (v1.0.0)

### ✅ Funcionalidades Implementadas

- **Geração Automática de URLs Curtas**
  - URLs curtas geradas automaticamente na publicação de conteúdo
  - Suporte para Posts, Páginas, Custom Post Types, Categorias e Tags
  - Configuração flexível via checkboxes no painel administrativo

- **Formato de URL**
  - URLs curtas na raiz do domínio: `exemplo.com.br/abc123`
  - Códigos de 5-7 caracteres usando Base62 (a-z, A-Z, 0-9)
  - Geração baseada no ID do conteúdo (sempre o mesmo código para o mesmo item)

- **Interface Administrativa**
  - Página de configurações em Configurações > URL Shortener
  - Seleção de post types e taxonomias para geração automática
  - Detecção automática de Custom Post Types públicos
  - Botões de geração retroativa por tipo de conteúdo

- **Colunas nas Listagens**
  - Coluna "URL Curta" nas tabelas de posts (após a coluna "Data")
  - Coluna "URL Curta" nas tabelas de termos (após a coluna "Slug")
  - Botão de copiar com feedback visual "Copiado!"
  - Compatível com telas responsivas

- **Redirecionamento**
  - Redirecionamento 301 (permanente) para SEO
  - Sistema de rewrite rules otimizado
  - Tratamento de erro 404 para códigos inexistentes

- **Arquitetura**
  - Código modular e orientado a objetos
  - Namespace PHP para evitar conflitos
  - Estrutura organizada em classes separadas
  - Hooks e filtros para extensibilidade

## 📁 Estrutura de Arquivos

```
url-shortener/
├── url-shortener.php          # Arquivo principal do plugin
├── README.md                      # Este arquivo
├── includes/                      # Classes principais
│   ├── class-url-shortener.php   # Classe principal
│   ├── class-admin.php            # Interface administrativa
│   ├── class-shortcode-generator.php  # Gerador de códigos
│   ├── class-redirector.php      # Sistema de redirecionamento
│   └── class-admin-columns.php   # Colunas personalizadas
├── admin/                         # Templates administrativos
│   └── settings-page.php         # Página de configurações
└── assets/                        # Assets estáticos
    ├── css/
    │   ├── admin.css             # Estilos da página de configurações
    │   └── columns.css           # Estilos das colunas
    └── js/
        ├── admin.js              # JavaScript da página de configurações
        └── columns.js            # JavaScript das colunas (copiar URL)
```

## 🚀 Instalação

1. Faça upload da pasta `url-shortener` para `/wp-content/plugins/`
2. Ative o plugin através do menu 'Plugins' no WordPress
3. Acesse Configurações > URL Shortener para configurar
4. Selecione os tipos de conteúdo que devem ter URLs curtas
5. Use os botões de geração em massa para conteúdo existente

## ⚙️ Configuração

### Configurações Básicas

1. Acesse **Configurações > URL Shortener**
2. Marque os **Post Types** que devem ter URLs curtas geradas automaticamente
3. Marque as **Taxonomias** que devem ter URLs curtas geradas automaticamente
4. Clique em **Salvar Configurações**

### Geração Retroativa

Na mesma página de configurações:
1. Role até a seção "Gerar URLs Curtas para Conteúdo Existente"
2. Clique nos botões correspondentes aos tipos de conteúdo
3. O número entre parênteses indica quantos itens existem
4. Uma mensagem de sucesso mostrará quantas URLs foram geradas

### Usando as URLs Curtas

As URLs curtas aparecem automaticamente nas listagens:
- **Posts/Páginas**: coluna "URL Curta" após a coluna "Data"
- **Categorias/Tags**: coluna "URL Curta" após a coluna "Slug"

Para copiar uma URL:
1. Clique no botão com ícone de página
2. A mensagem "Copiado!" confirmará a ação
3. A URL completa estará na área de transferência

## 🔧 Detalhes Técnicos

### Algoritmo de Geração

- **Base62**: Usa caracteres a-z, A-Z, 0-9
- **Baseado em ID**: Cada ID gera sempre o mesmo código
- **Salt por tipo**: Posts e termos usam salts diferentes para evitar colisões
- **Comprimento**: 5-7 caracteres (padding para garantir mínimo de 5)

### Banco de Dados

O plugin cria uma tabela `url_shortener`:
- `id`: ID único auto-incremento
- `short_code`: Código curto (único)
- `object_id`: ID do objeto (post/term)
- `object_type`: Tipo do objeto ('post' ou 'term')
- `created_at`: Data de criação

### Metadados

- Posts: `_wpus_short_code` (post_meta)
- Termos: `_wpus_short_code` (term_meta)

### Hooks Disponíveis

#### Actions
- `wpus_short_url_clicked`: Disparado quando uma URL curta é acessada
  - Parâmetros: `$short_code`, `$id`

#### Filters
*Nenhum filter público ainda - preparado para extensões futuras*

## 📈 Próximos Passos e Funcionalidades Futuras

### 1. Dashboard de Analytics (Alta Prioridade)

**Objetivo**: Rastreamento completo de cliques nas URLs curtas

**Funcionalidades**:
- Contador de cliques por URL
- Data e hora de cada clique
- IP do visitante (com anonimização LGPD)
- User Agent (navegador/dispositivo)
- Referrer (de onde veio o visitante)
- Geolocalização básica (país/cidade)
- Gráficos de visualização:
  - Cliques ao longo do tempo
  - Top URLs mais clicadas
  - Origem do tráfego
  - Dispositivos mais usados

**Implementação Técnica**:
- Nova tabela `url_shortener_clicks`
- Registro assíncrono para não afetar performance
- Agregação de dados para otimizar consultas
- Export de dados em CSV

**Interface**:
- Menu "Analytics" no painel do WordPress
- Filtros por data, tipo de conteúdo, URL específica
- Widgets no dashboard principal

---

### 2. Gerenciamento Avançado de URLs (Média Prioridade)

**Objetivo**: Controle total sobre as URLs curtas criadas

**Funcionalidades**:
- Página "Todas as URLs Curtas"
  - Listagem completa com busca e filtros
  - Informações: código, destino, data de criação, cliques
  - Ações em massa: excluir, regenerar
- Edição manual de códigos
  - Permitir customizar o código curto
  - Validação de unicidade
  - Prevenção de conflitos
- Exclusão de URLs
  - Opção de excluir URLs não utilizadas
  - Confirmação antes de excluir
  - Limpeza automática de URLs órfãs
- Estatísticas gerais
  - Total de URLs ativas
  - URLs criadas hoje/semana/mês
  - Taxa de utilização

**Interface**:
- Menu "URL Shortener" > "Todas as URLs"
- Integração com WP_List_Table
- Bulk actions nativas do WordPress

---

### 3. Compatibilidade com Plugins de SEO (Média Prioridade)

**Objetivo**: Integração nativa com principais plugins de SEO

**Yoast SEO**:
- Adicionar campo "URL Curta" no metabox do Yoast
- Copiar URL curta diretamente do editor de posts
- Sugestão automática de URL curta para compartilhamento
- Integração com análise de compartilhamento social

**Rank Math**:
- Campo "URL Curta" no painel Rank Math
- Shortcode para inserir URL curta no conteúdo
- Suporte a Schema.org com URLs curtas
- Integração com módulo de compartilhamento

**All in One SEO**:
- Metabox customizado com URL curta
- Preview de compartilhamento com URL curta
- Sugestões de otimização considerando URL curta

**Implementação Técnica**:
- Hooks nos metaboxes dos plugins
- JavaScript para integração de UI
- API de compartilhamento dos plugins
- Testes de compatibilidade por versão

---

### 4. Funcionalidades Adicionais

#### 4.1 QR Code Generator
- Geração automática de QR Code para cada URL curta
- Download em PNG, SVG
- Customização de cores e tamanho
- Útil para materiais impressos

#### 4.2 Expiração de URLs
- Opção de definir data de expiração
- Redirecionamento customizado após expiração
- Notificação antes de expirar
- Útil para campanhas temporárias

#### 4.3 Proteção por Senha
- URLs curtas protegidas por senha
- Útil para conteúdo exclusivo/premium
- Integração com membership plugins

#### 4.4 Domínio Customizado Externo
- Suporte a domínios curtos personalizados (ex: `exem.plo/abc123`)
- Configuração de DNS
- Múltiplos domínios
- Útil para branding

#### 4.5 Integração com Redes Sociais
- Botões de compartilhamento com URL curta
- Meta tags Open Graph automáticas
- Twitter Cards otimizados
- Preview de compartilhamento

#### 4.6 API REST
- Endpoints para criar URLs curtas programaticamente
- Obter estatísticas via API
- Integração com ferramentas externas
- Documentação completa

#### 4.7 Importação/Exportação
- Importar URLs de outros serviços (bit.ly, TinyURL)
- Exportar todas as URLs em CSV/JSON
- Backup e restauração de dados
- Migração facilitada

#### 4.8 Multisite Support
- URLs curtas compartilhadas na rede
- Configurações por site
- Estatísticas consolidadas
- Administração centralizada

---

### 5. Melhorias de Performance

#### 5.1 Cache
- Cache de redirecionamentos
- Integração com plugins de cache
- Object cache para consultas frequentes
- Redução de queries ao banco

#### 5.2 CDN
- Suporte a CDN para assets
- Otimização de carregamento
- Performance global

#### 5.3 Lazy Loading
- Carregamento sob demanda de estatísticas
- Paginação otimizada
- Requisições assíncronas

---

### 6. Segurança e Privacidade

#### 6.1 LGPD/GDPR Compliance
- Anonimização de IPs
- Opt-in para tracking
- Direito ao esquecimento
- Exportação de dados pessoais

#### 6.2 Rate Limiting
- Proteção contra abuso
- Limite de criação de URLs por tempo
- Blacklist de IPs suspeitos

#### 6.3 Spam Prevention
- Validação de destinos
- Prevenção de phishing
- Lista de domínios bloqueados

---

## 🗓️ Roadmap Sugerido

### Fase 1 (Curto Prazo - 1-2 meses)
1. Dashboard de Analytics básico
2. Página de gerenciamento de URLs
3. Compatibilidade com Yoast SEO

### Fase 2 (Médio Prazo - 3-4 meses)
1. Compatibilidade com Rank Math e AIOSEO
2. QR Code Generator
3. API REST básica

### Fase 3 (Longo Prazo - 5-6 meses)
1. Domínio customizado externo
2. Expiração de URLs
3. Multisite Support
4. Melhorias de performance avançadas

---

## 🤝 Contribuindo

Este é um projeto pessoal, mas sugestões e melhorias são bem-vindas!

## 📝 Licença

GPL v2 ou posterior

## 👨‍💻 Autor

Desenvolvido com ❤️ para a comunidade WordPress

---

## 🐛 Bugs Conhecidos

*Nenhum bug conhecido na versão atual.*

## ❓ FAQ

**P: As URLs curtas são permanentes?**
R: Sim, desde que o conteúdo original não seja excluído.

**P: Posso customizar o código curto?**
R: Na versão atual não, mas está planejado para versões futuras.

**P: O plugin afeta a performance do site?**
R: Não, o sistema de rewrite é otimizado e não adiciona queries desnecessárias.

**P: Funciona com Custom Post Types?**
R: Sim, todos os CPTs públicos são detectados automaticamente.

**P: É compatível com WPML/Polylang?**
R: Na versão atual não há suporte específico para multilíngue, mas está nos planos futuros.

---

## 📞 Suporte

Para dúvidas ou problemas, abra uma issue no repositório do projeto.