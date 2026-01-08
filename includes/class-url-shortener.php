<?php
namespace WP_URL_Shortener;

if (!defined('ABSPATH')) {
    exit;
}

class URL_Shortener {
    private static $instance = null;
    private $admin;
    private $generator;
    private $redirector;
    private $admin_columns;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->admin = new Admin();
        $this->generator = new Shortcode_Generator();
        $this->redirector = new Redirector();
        $this->admin_columns = new Admin_Columns();
    }

    public function run() {
        add_action('init', [$this, 'load_textdomain']);
        add_action('init', [$this->redirector, 'add_rewrite_rules']);
        add_action('template_redirect', [$this->redirector, 'handle_redirect']);
        
        // Gera URL curta automaticamente ao publicar
        add_action('transition_post_status', [$this, 'generate_on_publish'], 10, 3);
        add_action('created_term', [$this, 'generate_on_term_create'], 10, 3);
        
        // Inicializa componentes
        $this->admin->init();
        $this->admin_columns->init();
    }

    public function load_textdomain() {
        load_plugin_textdomain('wp-url-shortener', false, dirname(plugin_basename(URL_SHORTENER_PLUGIN_FILE)) . '/languages');
    }

    public function generate_on_publish($new_status, $old_status, $post) {
        if ($new_status !== 'publish' || $old_status === 'publish') {
            return;
        }

        $enabled_types = get_option('wpus_enabled_post_types', []);
        if (!in_array($post->post_type, $enabled_types)) {
            return;
        }

        // Gera URL curta se ainda não existir
        $existing = get_post_meta($post->ID, '_wpus_short_code', true);
        if (empty($existing)) {
            $short_code = $this->generator->generate_for_post($post->ID);
            update_post_meta($post->ID, '_wpus_short_code', $short_code);
        }
    }

    public function generate_on_term_create($term_id, $tt_id, $taxonomy) {
        $enabled_taxonomies = get_option('wpus_enabled_taxonomies', []);
        if (!in_array($taxonomy, $enabled_taxonomies)) {
            return;
        }

        // Gera URL curta se ainda não existir
        $existing = get_term_meta($term_id, '_wpus_short_code', true);
        if (empty($existing)) {
            $short_code = $this->generator->generate_for_term($term_id);
            update_term_meta($term_id, '_wpus_short_code', $short_code);
        }
    }

    public static function activate() {
        // Cria tabela para armazenar URLs curtas (futura implementação de tracking)
        global $wpdb;
        $table_name = $wpdb->prefix . 'url_shortener';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            short_code varchar(7) NOT NULL,
            object_id bigint(20) NOT NULL,
            object_type varchar(20) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id),
            UNIQUE KEY short_code (short_code),
            KEY object_id (object_id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        // Define opções padrão
        add_option('wpus_enabled_post_types', ['post', 'page']);
        add_option('wpus_enabled_taxonomies', ['category', 'post_tag']);
        
        // Força atualização das rewrite rules
        flush_rewrite_rules();
    }

    public static function deactivate() {
        // Limpa as rewrite rules
        flush_rewrite_rules();
    }
}