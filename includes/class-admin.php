<?php
namespace WP_URL_Shortener;

if (!defined('ABSPATH')) {
    exit;
}

class Admin {
    
    public function init() {
        add_action('admin_menu', [$this, 'add_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
        add_action('wp_ajax_wpus_generate_bulk', [$this, 'ajax_generate_bulk']);
    }

    public function add_admin_menu() {
        add_options_page(
            __('URL Shortener', 'url-shortener-by-melk'),
            __('URL Shortener', 'url-shortener-by-melk'),
            'manage_options',
            'url-shortener-by-melk',
            [$this, 'render_settings_page']
        );
    }

    public function register_settings() {
        register_setting('wpus_settings', 'wpus_enabled_post_types');
        register_setting('wpus_settings', 'wpus_enabled_taxonomies');
    }

    public function enqueue_admin_assets($hook) {
        if ($hook !== 'settings_page_wp-url-shortener') {
            return;
        }

        wp_enqueue_style(
            'wpus-admin-css',
            WP_URL_SHORTENER_PLUGIN_URL . 'assets/css/admin.css',
            [],
            WP_URL_SHORTENER_VERSION
        );

        wp_enqueue_script(
            'wpus-admin-js',
            WP_URL_SHORTENER_PLUGIN_URL . 'assets/js/admin.js',
            ['jquery'],
            WP_URL_SHORTENER_VERSION,
            true
        );

        wp_localize_script('wpus-admin-js', 'wpusAdmin', [
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wpus_generate_bulk'),
            'strings' => [
                'generating' => __('Gerando URLs curtas...', 'url-shortener-by-melk'),
                'success' => __('URLs curtas geradas com sucesso!', 'url-shortener-by-melk'),
                'error' => __('Erro ao gerar URLs curtas.', 'url-shortener-by-melk'),
            ]
        ]);
    }

    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        // Salva configurações se o formulário foi enviado
        if (isset($_POST['wpus_save_settings']) && check_admin_referer('wpus_settings_nonce')) {
            $post_types = isset($_POST['wpus_enabled_post_types']) ? array_map('sanitize_text_field', $_POST['wpus_enabled_post_types']) : [];
            $taxonomies = isset($_POST['wpus_enabled_taxonomies']) ? array_map('sanitize_text_field', $_POST['wpus_enabled_taxonomies']) : [];
            
            update_option('wpus_enabled_post_types', $post_types);
            update_option('wpus_enabled_taxonomies', $taxonomies);
            
            echo '<div class="notice notice-success is-dismissible"><p>' . __('Configurações salvas com sucesso!', 'url-shortener-by-melk') . '</p></div>';
        }

        $enabled_post_types = get_option('wpus_enabled_post_types', ['post', 'page']);
        $enabled_taxonomies = get_option('wpus_enabled_taxonomies', ['category', 'post_tag']);
        
        // Obtém todos os post types públicos
        $post_types = get_post_types(['public' => true], 'objects');
        
        // Obtém todas as taxonomias públicas
        $taxonomies = get_taxonomies(['public' => true], 'objects');
        
        include WP_URL_SHORTENER_PLUGIN_DIR . 'admin/settings-page.php';
    }

    public function ajax_generate_bulk() {
        check_ajax_referer('wpus_generate_bulk', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Permissão negada.', 'url-shortener-by-melk')]);
        }

        $type = isset($_POST['type']) ? sanitize_text_field($_POST['type']) : '';
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';

        if (empty($type) || empty($name)) {
            wp_send_json_error(['message' => __('Parâmetros inválidos.', 'wp-url-shortener')]);
        }

        $generator = new Shortcode_Generator();
        $generated = 0;

        if ($type === 'post_type') {
            $generated = $generator->generate_bulk_for_posts($name);
        } elseif ($type === 'taxonomy') {
            $generated = $generator->generate_bulk_for_terms($name);
        }

        wp_send_json_success([
            'message' => sprintf(
                __('%d URLs curtas foram geradas com sucesso!', 'wp-url-shortener'),
                $generated
            ),
            'count' => $generated
        ]);
    }
}