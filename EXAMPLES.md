# Exemplos de Uso - URL Shortener

Este documento contém exemplos práticos de como usar e estender o plugin URL Shortener.

## 📝 Casos de Uso Comuns

### 1. Compartilhamento em Redes Sociais

**Cenário:** Você quer compartilhar um post no Twitter sem gastar muitos caracteres.

**Sem URL Shortener:**
```
Confira meu novo artigo! 
https://meusite.com.br/2026/01/05/como-criar-um-plugin-wordpress-completo-para-gerenciar-urls-curtas/
(180 caracteres)
```

**Com URL Shortener:**
```
Confira meu novo artigo!
https://meusite.com.br/abc123
(56 caracteres - economia de 124 caracteres!)
```

---

### 2. Marketing e Campanhas

**Cenário:** Você está fazendo uma campanha de email marketing e quer URLs limpas.

**Exemplo de Email:**
```html
<a href="https://meusite.com.br/xY7kL2">
  Clique aqui para ver a promoção
</a>
```

**Benefícios:**
- URL mais profissional
- Fácil de lembrar e digitar
- Pode ser usada em materiais impressos

---

### 3. Materiais Impressos

**Cenário:** Você está criando um flyer e precisa de uma URL curta e legível.

**No Flyer:**
```
Visite nosso site:
meusite.com.br/promo2026

✓ Fácil de digitar
✓ Fácil de lembrar
✓ Não quebra em múltiplas linhas
```

---

## 🛠️ Exemplos de Código para Desenvolvedores

### 1. Obter URL Curta de um Post via Código

```php
<?php
// Método 1: Via post meta
$post_id = 123;
$short_code = get_post_meta($post_id, '_wpus_short_code', true);
$short_url = home_url('/' . $short_code);

echo $short_url; // https://seusite.com.br/abc123

// Método 2: Via classe (mais seguro)
if (class_exists('WP_URL_Shortener\Shortcode_Generator')) {
    $generator = new WP_URL_Shortener\Shortcode_Generator();
    $short_code = get_post_meta($post_id, '_wpus_short_code', true);
    $short_url = $generator->get_short_url($short_code);
    echo $short_url;
}
?>
```

---

### 2. Adicionar URL Curta em um Template

**single.php ou page.php:**
```php
<?php
// Dentro do loop
$short_code = get_post_meta(get_the_ID(), '_wpus_short_code', true);

if (!empty($short_code)) {
    $short_url = home_url('/' . $short_code);
    ?>
    <div class="short-url-share">
        <label>Compartilhe:</label>
        <input type="text" value="<?php echo esc_url($short_url); ?>" readonly>
        <button onclick="copyToClipboard('<?php echo esc_js($short_url); ?>')">
            Copiar
        </button>
    </div>
    <?php
}
?>
```

---

### 3. Exibir URL Curta no Conteúdo do Post

**functions.php do tema:**
```php
<?php
// Adiciona URL curta automaticamente ao final de cada post
add_filter('the_content', 'add_short_url_to_content');

function add_short_url_to_content($content) {
    // Só para posts single
    if (!is_single()) {
        return $content;
    }
    
    $short_code = get_post_meta(get_the_ID(), '_wpus_short_code', true);
    
    if (empty($short_code)) {
        return $content;
    }
    
    $short_url = home_url('/' . $short_code);
    
    $append = '<div class="post-short-url">';
    $append .= '<p><strong>Link curto deste artigo:</strong> ';
    $append .= '<a href="' . esc_url($short_url) . '">' . esc_html($short_url) . '</a>';
    $append .= '</p></div>';
    
    return $content . $append;
}
?>
```

---

### 4. Gerar URL Curta Programaticamente

```php
<?php
// Gerar URL curta para um post específico
function generate_short_url_for_post($post_id) {
    // Verifica se já existe
    $existing = get_post_meta($post_id, '_wpus_short_code', true);
    if (!empty($existing)) {
        return home_url('/' . $existing);
    }
    
    // Gera nova URL curta
    if (class_exists('WP_URL_Shortener\Shortcode_Generator')) {
        $generator = new WP_URL_Shortener\Shortcode_Generator();
        $short_code = $generator->generate_for_post($post_id);
        update_post_meta($post_id, '_wpus_short_code', $short_code);
        return $generator->get_short_url($short_code);
    }
    
    return false;
}

// Uso
$short_url = generate_short_url_for_post(123);
echo $short_url;
?>
```

---

### 5. Hook Personalizado para Tracking

```php
<?php
// Adiciona ação customizada quando uma URL curta é clicada
add_action('wpus_short_url_clicked', 'my_custom_tracking', 10, 2);

function my_custom_tracking($short_code, $id) {
    // Exemplo: Salvar em log personalizado
    error_log("URL curta $short_code foi acessada (ID: $id)");
    
    // Exemplo: Incrementar contador customizado
    $count = get_post_meta($id, '_my_custom_click_count', true);
    $count = empty($count) ? 1 : intval($count) + 1;
    update_post_meta($id, '_my_custom_click_count', $count);
    
    // Exemplo: Enviar para analytics externo
    // my_send_to_analytics($short_code);
}
?>
```

---

### 6. Widget Personalizado com URL Curta

```php
<?php
// Criar widget que exibe posts recentes com URLs curtas
class Recent_Posts_Short_URL_Widget extends WP_Widget {
    
    function __construct() {
        parent::__construct(
            'recent_posts_short_url',
            'Posts Recentes (URL Curta)',
            array('description' => 'Exibe posts recentes com suas URLs curtas')
        );
    }
    
    public function widget($args, $instance) {
        $posts = get_posts(array(
            'posts_per_page' => 5,
            'post_status' => 'publish'
        ));
        
        echo $args['before_widget'];
        echo '<ul class="recent-posts-short-url">';
        
        foreach ($posts as $post) {
            $short_code = get_post_meta($post->ID, '_wpus_short_code', true);
            $short_url = !empty($short_code) ? home_url('/' . $short_code) : '';
            
            echo '<li>';
            echo '<a href="' . get_permalink($post->ID) . '">' . esc_html($post->post_title) . '</a>';
            
            if (!empty($short_url)) {
                echo '<br><small class="short-url">' . esc_html($short_url) . '</small>';
            }
            
            echo '</li>';
        }
        
        echo '</ul>';
        echo $args['after_widget'];
    }
}

// Registrar o widget
add_action('widgets_init', function() {
    register_widget('Recent_Posts_Short_URL_Widget');
});
?>
```

---

### 7. Shortcode para Inserir URL Curta no Conteúdo

**functions.php:**
```php
<?php
// Criar shortcode [short-url]
add_shortcode('short-url', 'wpus_short_url_shortcode');

function wpus_short_url_shortcode($atts) {
    $atts = shortcode_atts(array(
        'post_id' => get_the_ID(),
        'text' => 'Link Curto',
        'copy_button' => 'no'
    ), $atts);
    
    $post_id = intval($atts['post_id']);
    $short_code = get_post_meta($post_id, '_wpus_short_code', true);
    
    if (empty($short_code)) {
        return '';
    }
    
    $short_url = home_url('/' . $short_code);
    $output = '<a href="' . esc_url($short_url) . '" class="wpus-shortcode-link">';
    $output .= esc_html($atts['text']);
    $output .= '</a>';
    
    if ($atts['copy_button'] === 'yes') {
        $output .= ' <button onclick="navigator.clipboard.writeText(\'' . esc_js($short_url) . '\')">Copiar</button>';
    }
    
    return $output;
}
?>
```

**Uso no Editor:**
```
[short-url]
[short-url text="Clique aqui"]
[short-url post_id="123" text="Ir para o post" copy_button="yes"]
```

---

### 8. REST API Endpoint Customizado

```php
<?php
// Adicionar endpoint REST API para obter URL curta
add_action('rest_api_init', function () {
    register_rest_route('wpus/v1', '/short-url/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'wpus_get_short_url_api',
        'permission_callback' => '__return_true'
    ));
});

function wpus_get_short_url_api($request) {
    $post_id = $request['id'];
    
    if (!get_post($post_id)) {
        return new WP_Error('not_found', 'Post não encontrado', array('status' => 404));
    }
    
    $short_code = get_post_meta($post_id, '_wpus_short_code', true);
    
    if (empty($short_code)) {
        return new WP_Error('no_short_url', 'URL curta não encontrada', array('status' => 404));
    }
    
    return array(
        'post_id' => $post_id,
        'short_code' => $short_code,
        'short_url' => home_url('/' . $short_code),
        'full_url' => get_permalink($post_id)
    );
}

// Uso da API:
// GET https://seusite.com.br/wp-json/wpus/v1/short-url/123
?>
```

---

### 9. Integração com Elementor

```php
<?php
// Adicionar campo dinâmico no Elementor
add_action('elementor/dynamic_tags/register', function($dynamic_tags) {
    
    class Short_URL_Dynamic_Tag extends \Elementor\Core\DynamicTags\Tag {
        
        public function get_name() {
            return 'short-url';
        }
        
        public function get_title() {
            return 'URL Curta';
        }
        
        public function get_group() {
            return 'post';
        }
        
        public function get_categories() {
            return [\Elementor\Modules\DynamicTags\Module::URL_CATEGORY];
        }
        
        public function render() {
            $short_code = get_post_meta(get_the_ID(), '_wpus_short_code', true);
            if (!empty($short_code)) {
                echo home_url('/' . $short_code);
            }
        }
    }
    
    $dynamic_tags->register(new Short_URL_Dynamic_Tag());
});
?>
```

---

### 10. Botões de Compartilhamento Social

```php
<?php
// Adicionar botões de compartilhamento com URL curta
function wpus_social_share_buttons() {
    if (!is_single()) {
        return;
    }
    
    $short_code = get_post_meta(get_the_ID(), '_wpus_short_code', true);
    if (empty($short_code)) {
        return;
    }
    
    $short_url = home_url('/' . $short_code);
    $title = get_the_title();
    
    ?>
    <div class="wpus-social-share">
        <h4>Compartilhe:</h4>
        
        <!-- Twitter -->
        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($short_url); ?>&text=<?php echo urlencode($title); ?>" 
           target="_blank" class="share-twitter">
            Twitter
        </a>
        
        <!-- Facebook -->
        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($short_url); ?>" 
           target="_blank" class="share-facebook">
            Facebook
        </a>
        
        <!-- WhatsApp -->
        <a href="https://wa.me/?text=<?php echo urlencode($title . ' ' . $short_url); ?>" 
           target="_blank" class="share-whatsapp">
            WhatsApp
        </a>
        
        <!-- LinkedIn -->
        <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode($short_url); ?>" 
           target="_blank" class="share-linkedin">
            LinkedIn
        </a>
    </div>
    <?php
}

// Adicionar após o conteúdo
add_filter('the_content', function($content) {
    if (is_single()) {
        ob_start();
        wpus_social_share_buttons();
        $buttons = ob_get_clean();
        return $content . $buttons;
    }
    return $content;
});
?>
```

---

## 📊 Exemplos de Relatórios (Preparação Futura)

### Estrutura para Tracking de Cliques

```php
<?php
// Exemplo de estrutura para quando implementarmos analytics
function wpus_track_click_example($short_code, $id) {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'url_shortener_clicks';
    
    // Dados que serão coletados no futuro
    $data = array(
        'short_code' => $short_code,
        'object_id' => $id,
        'ip_address' => wpus_get_anonymous_ip(), // IP anonimizado (LGPD)
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
        'referrer' => $_SERVER['HTTP_REFERER'] ?? '',
        'timestamp' => current_time('mysql')
    );
    
    // Futuramente: $wpdb->insert($table_name, $data);
}

// Função para anonimizar IP (LGPD compliance)
function wpus_get_anonymous_ip() {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    // Remove últimos octetos do IP
    $ip_parts = explode('.', $ip);
    if (count($ip_parts) === 4) {
        $ip_parts[3] = '0';
        return implode('.', $ip_parts);
    }
    return $ip;
}
?>
```

---

## 🎨 Exemplos de Estilos CSS Customizados

```css
/* Estilizar URL curta no conteúdo */
.post-short-url {
    background: #f8f9fa;
    border-left: 4px solid #007bff;
    padding: 15px;
    margin: 20px 0;
}

.post-short-url a {
    font-family: 'Courier New', monospace;
    color: #007bff;
    text-decoration: none;
    font-weight: bold;
}

/* Botões de compartilhamento */
.wpus-social-share {
    margin: 30px 0;
    padding: 20px;
    background: #f5f5f5;
    border-radius: 8px;
}

.wpus-social-share a {
    display: inline-block;
    margin: 5px;
    padding: 10px 20px;
    border-radius: 5px;
    color: white;
    text-decoration: none;
    transition: opacity 0.3s;
}

.wpus-social-share a:hover {
    opacity: 0.8;
}

.share-twitter { background: #1DA1F2; }
.share-facebook { background: #4267B2; }
.share-whatsapp { background: #25D366; }
.share-linkedin { background: #0077B5; }
```

---

## 💡 Dicas de Boas Práticas

### 1. Sempre Verificar se URL Existe

```php
<?php
$short_code = get_post_meta($post_id, '_wpus_short_code', true);
if (!empty($short_code)) {
    // Usar URL curta
} else {
    // Fallback para URL normal
    $url = get_permalink($post_id);
}
?>
```

### 2. Usar URLs Curtas em Emails

```html
<!-- Melhor legibilidade em emails -->
<a href="https://seusite.com.br/abc123" style="color: #007bff;">
  Clique aqui para ler o artigo completo
</a>
```

### 3. Incluir em Feeds RSS

```php
<?php
add_filter('the_excerpt_rss', 'add_short_url_to_rss');
add_filter('the_content_feed', 'add_short_url_to_rss');

function add_short_url_to_rss($content) {
    $short_code = get_post_meta(get_the_ID(), '_wpus_short_code', true);
    if (!empty($short_code)) {
        $short_url = home_url('/' . $short_code);
        $content .= '<p><em>Link direto: ' . $short_url . '</em></p>';
    }
    return $content;
}
?>
```

---

## 🎯 Casos de Uso Avançados

### Newsletter com Tracking

```php
<?php
// Gerar URL com parâmetro de campanha
function wpus_newsletter_url($post_id, $campaign = 'newsletter') {
    $short_code = get_post_meta($post_id, '_wpus_short_code', true);
    if (empty($short_code)) {
        return get_permalink($post_id);
    }
    
    $short_url = home_url('/' . $short_code);
    return add_query_arg('utm_source', $campaign, $short_url);
}

// Uso
$url = wpus_newsletter_url(123, 'newsletter-janeiro-2026');
// Resultado: https://seusite.com.br/abc123?utm_source=newsletter-janeiro-2026
?>
```

---

**Este documento será atualizado com novos exemplos conforme o plugin evolui!** 🚀