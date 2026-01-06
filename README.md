# WP URL Shortener

> ⚠️ **STATUS DO PROJETO:** Em Desenvolvimento Ativo - 50% Completo
> 
> **Última Atualização:** 05/01/2026  
> **Versão Atual:** 0.5.0-dev  
> **Sprint Atual:** 1 de 6

---

## 🚧 Aviso Importante

Este plugin está **em desenvolvimento ativo** e **não está pronto para uso em produção**. 

### Status Atual:
- ✅ **9 arquivos criados** (50%)
- ❌ **7 arquivos pendentes** (necessários para funcionamento)
- 📝 **Documentação:** 78% completa

### Arquivos Pendentes Críticos:
1. `includes/class-admin.php` ❌
2. `includes/class-shortcode-generator.php` ❌
3. `includes/class-redirector.php` ❌
4. `includes/class-admin-columns.php` ❌
5. `admin/settings-page.php` ❌
6. `assets/css/admin.css` ❌
7. `assets/css/columns.css` ❌
8. `assets/js/admin.js` ❌

**⚠️ O plugin NÃO funcionará até que todos os arquivos sejam criados.**

---

## 📋 Índice

- [Sobre o Projeto](#-sobre-o-projeto)
- [Status de Desenvolvimento](#-status-de-desenvolvimento)
- [Funcionalidades Planejadas](#-funcionalidades-planejadas)
- [Pré-requisitos](#-pré-requisitos)
- [Instalação (Quando Pronto)](#-instalação-quando-pronto)
- [Roadmap](#-roadmap)
- [Estrutura do Código](#-estrutura-do-código)
- [Contribuindo](#-contribuindo)
- [Documentação](#-documentação)
- [Licença](#-licença)

---

## 🎯 Sobre o Projeto

Plugin WordPress para criação e gerenciamento de URLs curtas para posts, páginas, categorias, tags e custom post types.

### Conceito

Transforma URLs longas em códigos curtos para facilitar compartilhamento:

**Antes:**
```
https://meusite.com.br/2026/01/05/como-criar-um-plugin-wordpress-completo/
```

**Depois:**
```
https://meusite.com.br/abc123
```

### Por Que Este Plugin?

- ✅ **Economia de Caracteres:** Ideal para Twitter, SMS, materiais impressos
- ✅ **URLs Limpas:** Mais profissionais e fáceis de lembrar
- ✅ **SEO Friendly:** Redirecionamento 301 (permanente)
- ✅ **Privacidade:** Seus dados ficam no seu servidor
- ✅ **Gratuito:** Código aberto, GPL v2

---

## 📊 Status de Desenvolvimento

### Sprint 1: Arquivos Base (EM ANDAMENTO - 50%)

**Objetivo:** Criar todos os arquivos necessários para funcionamento básico

#### ✅ Concluído (9 arquivos)

**Estrutura e Configuração:**
- [x] `wp-url-shortener.php` - Arquivo principal
- [x] `includes/class-url-shortener.php` - Classe principal
- [x] `.gitignore` - Controle de versionamento
- [x] `LICENSE` - Licença GPL v2

**JavaScript:**
- [x] `assets/js/columns.js` - Copiar URL para clipboard

**Documentação (78% completa):**
- [x] `EXAMPLES.md` - Exemplos de uso e código
- [x] `CHANGELOG.md` - Histórico de versões
- [x] `STRUCTURE.md` - Arquitetura técnica
- [x] `DESENVOLVIMENTO.md` - Controle de sprints
- [x] `CHECKLIST-ARQUIVOS.md` - Verificação de arquivos
- [x] `INSTALLATION.md` - Guia completo de instalação
- [ ] `README.md` - Este arquivo (em atualização)

#### ❌ Pendente (8 arquivos)

**Classes PHP Críticas:**
- [ ] `includes/class-admin.php` - Interface administrativa
- [ ] `includes/class-shortcode-generator.php` - Algoritmo Base62
- [ ] `includes/class-redirector.php` - Sistema de redirecionamento
- [ ] `includes/class-admin-columns.php` - Colunas personalizadas

**Templates:**
- [ ] `admin/settings-page.php` - Página de configurações

**Estilos:**
- [ ] `assets/css/admin.css` - Estilos da página admin
- [ ] `assets/css/columns.css` - Estilos das colunas

**JavaScript:**
- [ ] `assets/js/admin.js` - Geração em massa via AJAX

---

### Próximas Sprints

#### Sprint 2: Testes e Refinamentos (PLANEJADA)
- Testes completos de funcionalidade
- Otimizações de performance
- Correção de bugs
- Melhorias de UX

#### Sprint 3: Dashboard de Analytics (PLANEJADA)
- Tracking de cliques
- Estatísticas e gráficos
- Export de dados
- Top URLs mais acessadas

#### Sprint 4: Gerenciamento de URLs (PLANEJADA)
- Página "Todas as URLs"
- Edição de códigos curtos
- Exclusão de URLs
- Busca e filtros avançados

#### Sprint 5: Compatibilidade SEO (PLANEJADA)
- Integração com Yoast SEO
- Integração com Rank Math
- Integração com All in One SEO

#### Sprint 6: Funcionalidades Premium (PLANEJADA)
- QR Code Generator
- Expiração de URLs
- Proteção por senha
- Domínio customizado externo

---

## 🎯 Funcionalidades Planejadas

### Versão 1.0 (Sprint 1-2) - EM DESENVOLVIMENTO

**Geração de URLs:**
- [ ] Geração automática ao publicar posts
- [ ] Geração automática ao criar categorias/tags
- [ ] Algoritmo Base62 (5-7 caracteres)
- [ ] Códigos baseados em ID (determinísticos)
- [ ] Suporte a Custom Post Types
- [ ] Geração retroativa em massa

**Interface Administrativa:**
- [ ] Página de configurações em Configurações > URL Shortener
- [ ] Checkboxes para habilitar post types
- [ ] Checkboxes para habilitar taxonomias
- [ ] Botões de geração em massa

**Listagens:**
- [ ] Coluna "URL Curta" em posts (após coluna "Data")
- [ ] Coluna "URL Curta" em termos (após coluna "Slug")
- [ ] Botão de copiar URL
- [ ] Feedback visual "Copiado!"

**Redirecionamento:**
- [ ] Redirecionamento 301 (permanente)
- [ ] URLs na raiz do domínio (exemplo.com.br/abc123)
- [ ] Tratamento de erro 404 para códigos inválidos

**Banco de Dados:**
- [ ] Tabela `wp_url_shortener`
- [ ] Post meta `_wpus_short_code`
- [ ] Term meta `_wpus_short_code`
- [ ] Índices otimizados

### Versão 2.0 (Sprint 3) - PLANEJADA

**Analytics:**
- [ ] Tracking de cliques
- [ ] IP anonimizado (LGPD/GDPR)
- [ ] User Agent e Referrer
- [ ] Dashboard com estatísticas
- [ ] Gráficos interativos
- [ ] Export CSV

### Versão 2.1 (Sprint 4) - PLANEJADA

**Gerenciamento:**
- [ ] Página "Todas as URLs"
- [ ] Edição de códigos curtos
- [ ] Exclusão individual/massa
- [ ] Busca e filtros
- [ ] Regeneração de URLs

### Versão 2.2 (Sprint 5) - PLANEJADA

**SEO Plugins:**
- [ ] Yoast SEO integration
- [ ] Rank Math integration
- [ ] All in One SEO integration
- [ ] Campo no editor de posts
- [ ] Preview de compartilhamento

### Versão 3.0 (Sprint 6) - PLANEJADA

**Premium Features:**
- [ ] QR Code Generator
- [ ] URLs com expiração
- [ ] Proteção por senha
- [ ] Domínio externo customizado
- [ ] API REST completa
- [ ] Webhooks

---

## 📦 Pré-requisitos

### Requisitos Mínimos

- WordPress 5.0 ou superior
- PHP 7.4 ou superior
- MySQL 5.6 ou superior / MariaDB 10.0 ou superior

### Requisitos Recomendados

- WordPress 6.0 ou superior
- PHP 8.0 ou superior
- MySQL 8.0 ou superior / MariaDB 10.5 ou superior
- HTTPS habilitado (para clipboard API)

---

## 🚀 Instalação (Quando Pronto)

> ⚠️ **ATENÇÃO:** Plugin ainda não está funcional. Aguarde a conclusão da Sprint 1.

### Quando o Plugin Estiver Completo:

1. **Download:**
   ```bash
   git clone https://github.com/seu-usuario/wp-url-shortener.git
   ```

2. **Upload:**
   - Faça upload da pasta `wp-url-shortener` para `/wp-content/plugins/`

3. **Ativação:**
   - Ative em **Plugins > Plugins Instalados**

4. **Configuração:**
   - Acesse **Configurações > URL Shortener**
   - Marque os tipos de conteúdo desejados
   - Gere URLs para conteúdo existente

5. **Uso:**
   - URLs curtas são geradas automaticamente ao publicar
   - Copie URLs nas listagens com um clique
   - Compartilhe nas redes sociais

**Documentação Completa:** Veja [INSTALLATION.md](INSTALLATION.md)

---

## 🗺️ Roadmap

### Q1 2026

**Janeiro:**
- [x] ~~Sprint 1 iniciada~~ (05/01)
- [ ] Sprint 1 concluída (meta: 15/01)
- [ ] Sprint 2 iniciada (meta: 16/01)

**Fevereiro:**
- [ ] Sprint 2 concluída
- [ ] Sprint 3 iniciada (Analytics)
- [ ] Versão 1.0 BETA lançada

**Março:**
- [ ] Sprint 3 concluída
- [ ] Sprint 4 iniciada (Gerenciamento)
- [ ] Versão 2.0 lançada

### Q2 2026

**Abril:**
- [ ] Sprint 4 concluída
- [ ] Sprint 5 iniciada (SEO)

**Maio:**
- [ ] Sprint 5 concluída
- [ ] Versão 2.2 lançada
- [ ] Testes de stress

**Junho:**
- [ ] Sprint 6 iniciada (Premium)
- [ ] Documentação em vídeo
- [ ] Submissão para WordPress.org

### Q3 2026

**Julho-Setembro:**
- [ ] Sprint 6 concluída
- [ ] Versão 3.0 lançada
- [ ] Marketing e divulgação
- [ ] Suporte à comunidade

---

## 🏗️ Estrutura do Código

### Arquitetura

```
wp-url-shortener/
│
├── wp-url-shortener.php          # Ponto de entrada
│   ├── Define constantes
│   ├── Autoloader de classes
│   └── Hooks de ativação/desativação
│
├── includes/                      # Classes principais
│   ├── class-url-shortener.php   # Singleton principal ✅
│   ├── class-admin.php            # Interface admin ❌
│   ├── class-shortcode-generator.php  # Base62 ❌
│   ├── class-redirector.php      # Redirecionamento ❌
│   └── class-admin-columns.php   # Colunas ❌
│
├── admin/                         # Templates
│   └── settings-page.php         # Configurações ❌
│
└── assets/                        # Frontend
    ├── css/
    │   ├── admin.css             # Estilos admin ❌
    │   └── columns.css           # Estilos colunas ❌
    └── js/
        ├── admin.js              # AJAX ❌
        └── columns.js            # Copiar URL ✅
```

### Padrões de Código

- **Namespace:** `WP_URL_Shortener\`
- **Autoloading:** PSR-4 style
- **Singleton:** Classe principal
- **WordPress Coding Standards (WPCS)**
- **Nomeação:** Descritiva e clara
- **Documentação:** PHPDoc em métodos

### Tecnologias

- **Backend:** PHP 7.4+
- **Frontend:** JavaScript (ES6+), CSS3
- **Database:** MySQL/MariaDB
- **Build:** Nativo WordPress (sem bundler)

---

## 🤝 Contribuindo

### Como Contribuir

O projeto está em desenvolvimento inicial. Contribuições são bem-vindas!

#### Áreas que Precisam de Ajuda:

1. **Código:**
   - Implementar classes pendentes
   - Otimizar algoritmos
   - Testes unitários

2. **Documentação:**
   - Melhorar exemplos
   - Traduzir para outros idiomas
   - Criar tutoriais em vídeo

3. **Design:**
   - Melhorar CSS
   - Criar ícones
   - UX/UI da interface admin

4. **Testes:**
   - Testar em diferentes ambientes
   - Reportar bugs
   - Sugerir melhorias

#### Processo:

```bash
1. Fork o repositório
2. Crie uma branch (git checkout -b feature/nova-funcionalidade)
3. Commit suas mudanças (git commit -am 'Adiciona nova funcionalidade')
4. Push para a branch (git push origin feature/nova-funcionalidade)
5. Abra um Pull Request
```

### Diretrizes:

- Seguir WordPress Coding Standards
- Adicionar testes para novas funcionalidades
- Atualizar documentação
- Um commit por feature/fix
- Mensagens de commit descritivas

---

## 📚 Documentação

### Documentos Disponíveis

- **[INSTALLATION.md](INSTALLATION.md)** - Guia completo de instalação e início rápido
- **[EXAMPLES.md](EXAMPLES.md)** - Exemplos de uso e snippets de código
- **[STRUCTURE.md](STRUCTURE.md)** - Arquitetura técnica detalhada
- **[CHANGELOG.md](CHANGELOG.md)** - Histórico de versões
- **[DESENVOLVIMENTO.md](DESENVOLVIMENTO.md)** - Controle de sprints e tarefas
- **[CHECKLIST-ARQUIVOS.md](CHECKLIST-ARQUIVOS.md)** - Status dos arquivos

### Documentação Futura

- [ ] API Reference
- [ ] Hooks & Filters Guide
- [ ] Developer Guide
- [ ] User Manual
- [ ] Video Tutorials

---

## 📊 Estatísticas do Projeto

### Desenvolvimento

**Progresso Geral:** 50%

**Código:**
- PHP: 1/5 classes (20%)
- CSS: 0/2 arquivos (0%)
- JavaScript: 1/2 arquivos (50%)

**Documentação:** 78%
- 7 de 9 documentos completos

**Testes:** 0%
- Nenhum teste realizado ainda

### Métricas de Código

**Linhas de Código (atual):**
- PHP: ~150 linhas
- CSS: 0 linhas
- JavaScript: ~60 linhas
- **Total:** ~210 linhas

**Linhas de Código (planejado):**
- PHP: ~1.200 linhas
- CSS: ~200 linhas
- JavaScript: ~130 linhas
- **Total:** ~1.530 linhas

**Documentação:**
- ~4.500 linhas (completo)

---

## 🐛 Issues e Bugs

### Reportar Problemas

Como o plugin está em desenvolvimento, não há bugs conhecidos ainda.

Quando o plugin estiver funcional, reporte bugs incluindo:

- [ ] Versão do WordPress
- [ ] Versão do PHP
- [ ] Tema utilizado
- [ ] Plugins ativos
- [ ] Mensagem de erro completa
- [ ] Passos para reproduzir

**Onde reportar:** [GitHub Issues](https://github.com/seu-usuario/wp-url-shortener/issues)

---

## 📄 Licença

Este projeto está licenciado sob a **GNU General Public License v2 ou posterior**.

```
Copyright (C) 2026 WP URL Shortener

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
```

Veja o arquivo [LICENSE](LICENSE) para detalhes completos.

### O que isso significa?

✅ **Você PODE:**
- Usar gratuitamente
- Modificar o código
- Distribuir (original ou modificado)
- Usar comercialmente

❌ **Você NÃO PODE:**
- Remover créditos dos autores
- Usar licença mais restritiva
- Responsabilizar autores por danos

---

## 👨‍💻 Autor

**Desenvolvido por:** [Seu Nome]  
**GitHub:** [@seu-usuario](https://github.com/seu-usuario)  
**Site:** [seusite.com](https://seusite.com)  
**Email:** [seu-email@exemplo.com](mailto:seu-email@exemplo.com)

---

## 🙏 Agradecimentos

- Comunidade WordPress
- Contribuidores do projeto
- Testadores beta (futuros)

---

## 📞 Suporte

### Canais de Suporte (Quando Disponível)

- **Documentação:** Leia primeiro os documentos acima
- **GitHub Issues:** Para bugs e feature requests
- **Email:** Para questões gerais
- **Fórum WordPress:** Suporte da comunidade

### Status de Desenvolvimento

- **Fase Atual:** Desenvolvimento Inicial (Sprint 1)
- **Status:** Em Andamento 🔄
- **Disponível para Uso:** ❌ Ainda não
- **Previsão de Lançamento BETA:** Fevereiro 2026

---

## 🌟 Dê uma Estrela!

Se você gosta da ideia deste projeto, considere dar uma ⭐ no GitHub!

---

## 📝 Notas Finais

Este é um projeto **em desenvolvimento ativo**. As informações neste README refletem o estado atual e os planos futuros.

**Última Atualização:** 05/01/2026  
**Versão do README:** 0.5.0

**Próxima Atualização:** Após conclusão da Sprint 1

---

**Desenvolvido com ❤️ para a comunidade WordPress**