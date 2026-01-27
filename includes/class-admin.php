<?php
namespace Melk\UrlShortenerByMelk;

if (!defined('ABSPATH')) {
    exit;
}

class Admin {
    
    public function init() {
		add_action('admin_menu', [$this, 'add_admin_menu']);
		add_action('admin_init', [$this, 'register_settings']);
		add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_assets']);
		add_action('wp_ajax_urlshbym_generate_bulk', [$this, 'ajax_generate_bulk']);
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
		register_setting('urlshbym_settings', 'urlshbym_enabled_post_types', [
            'sanitize_callback' => [$this, 'sanitize_array']
        ]);
		register_setting('urlshbym_settings', 'urlshbym_enabled_taxonomies', [
            'sanitize_callback' => [$this, 'sanitize_array']
        ]);
    }

    public function sanitize_array($input) {
        return (is_array($input)) ? array_map('sanitize_text_field', $input) : sanitize_text_field($input);
    }

    public function enqueue_admin_assets($hook) {
		if ($hook !== 'settings_page_url-shortener-by-melk') {
            return;
        }

		wp_enqueue_style(
			'urlshbym-admin-css',
			URLSHBYM_PLUGIN_URL . 'assets/css/admin.css',
			[],
			URLSHBYM_VERSION
		);

		wp_enqueue_script(
			'urlshbym-admin-js',
			URLSHBYM_PLUGIN_URL . 'assets/js/admin.js',
			['jquery'],
			URLSHBYM_VERSION,
			true
		);

		wp_localize_script('urlshbym-admin-js', 'urlshbymAdmin', [
            'ajaxurl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('urlshbym_generate_bulk'),
            'strings' => [
                'generating' => __('Generating short URLs...', 'url-shortener-by-melk'),
                'success' => __('Short URLs generated successfully!', 'url-shortener-by-melk'),
                'error' => __('Error generating short URLs.', 'url-shortener-by-melk'),
            ]
        ]);
    }

    public function render_settings_page() {
        if (!current_user_can('manage_options')) {
            return;
        }

        // Salva configurações se o formulário foi enviado
		if (isset($_POST['urlshbym_save_settings']) && check_admin_referer('urlshbym_settings_nonce')) {
			$post_types = isset($_POST['urlshbym_enabled_post_types']) ? array_map('sanitize_text_field', wp_unslash($_POST['urlshbym_enabled_post_types'])) : [];
			$taxonomies = isset($_POST['urlshbym_enabled_taxonomies']) ? array_map('sanitize_text_field', wp_unslash($_POST['urlshbym_enabled_taxonomies'])) : [];
            
			update_option('urlshbym_enabled_post_types', $post_types);
			update_option('urlshbym_enabled_taxonomies', $taxonomies);
            
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Settings saved successfully!', 'url-shortener-by-melk') . '</p></div>';
        }

		$enabled_post_types = get_option('urlshbym_enabled_post_types', ['post', 'page']);
		$enabled_taxonomies = get_option('urlshbym_enabled_taxonomies', ['category', 'post_tag']);
        
        // Obtém todos os post types públicos
        $post_types = get_post_types(['public' => true], 'objects');
        
        // Obtém todas as taxonomias públicas
        $taxonomies = get_taxonomies(['public' => true], 'objects');
        
        include URLSHBYM_PLUGIN_DIR . 'admin/settings-page.php';
    }

	public function ajax_generate_bulk() {
		check_ajax_referer('urlshbym_generate_bulk', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Permission denied.', 'url-shortener-by-melk')]);
        }

        $type = isset($_POST['type']) ? sanitize_text_field(wp_unslash($_POST['type'])) : '';
        $name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';

        if (empty($type) || empty($name)) {
            wp_send_json_error(['message' => __('Invalid parameters.', 'url-shortener-by-melk')]);
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
                /* translators: %d: Number of generated URLs */
                __('%d short URLs were generated successfully!', 'url-shortener-by-melk'),
                $generated
            ),
            'count' => $generated
        ]);
    }
}
