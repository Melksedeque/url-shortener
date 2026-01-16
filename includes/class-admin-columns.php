<?php
namespace Melk\UrlShortenerByMelk;

if (!defined('ABSPATH')) {
    exit;
}

class Admin_Columns {
    
	public function init() {
		// Adiciona colunas para post types
		$enabled_post_types = get_option('urlshbym_enabled_post_types', []);
        foreach ($enabled_post_types as $post_type) {
            add_filter("manage_{$post_type}_posts_columns", [$this, 'add_post_column']);
            add_action("manage_{$post_type}_posts_custom_column", [$this, 'render_post_column'], 10, 2);
        }

		// Adiciona colunas para taxonomias
		$enabled_taxonomies = get_option('urlshbym_enabled_taxonomies', []);
        foreach ($enabled_taxonomies as $taxonomy) {
            add_filter("manage_edit-{$taxonomy}_columns", [$this, 'add_term_column']);
            add_filter("manage_{$taxonomy}_custom_column", [$this, 'render_term_column'], 10, 3);
        }

        // Enfileira scripts para copiar URL
        add_action('admin_enqueue_scripts', [$this, 'enqueue_column_assets']);
    }

    public function add_post_column($columns) {
        $new_columns = [];
        
		foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;
            
				// Adiciona a coluna após a coluna 'date'
				if ($key === 'date') {
					$new_columns['urlshbym_short_url'] = esc_html__('URL Curta', 'url-shortener-by-melk');
				}
        }
        
        return $new_columns;
    }

	public function render_post_column($column, $post_id) {
		if ($column !== 'urlshbym_short_url') {
            return;
        }

		$short_code = get_post_meta($post_id, '_urlshbym_short_code', true);
        
        if (empty($short_code)) {
            echo '<span style="color: #999;">' . esc_html__('Não gerada', 'url-shortener-by-melk') . '</span>';
            return;
        }

        $generator = new Shortcode_Generator();
        $short_url = $generator->get_short_url($short_code);
        
        $this->render_url_with_copy($short_url, $short_code);
    }

    public function add_term_column($columns) {
        $new_columns = [];
        
		foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;
            
				// Adiciona a coluna após a coluna 'slug'
				if ($key === 'slug') {
					$new_columns['urlshbym_short_url'] = esc_html__('URL Curta', 'url-shortener-by-melk');
				}
        }
        
        return $new_columns;
    }

	public function render_term_column($content, $column, $term_id) {
		if ($column !== 'urlshbym_short_url') {
            return $content;
        }

		$short_code = get_term_meta($term_id, '_urlshbym_short_code', true);
        
        if (empty($short_code)) {
            return '<span style="color: #999;">' . esc_html__('Não gerada', 'url-shortener-by-melk') . '</span>';
        }

        $generator = new Shortcode_Generator();
        $short_url = $generator->get_short_url($short_code);
        
        ob_start();
        $this->render_url_with_copy($short_url, $short_code);
        return ob_get_clean();
    }

    private function render_url_with_copy($short_url, $short_code) {
        ?>
        <div class="urlshbym-url-wrapper">
            <code class="urlshbym-short-url"><?php echo esc_html($short_code); ?></code>
            <button 
                type="button" 
                class="button button-small urlshbym-copy-btn" 
                data-url="<?php echo esc_attr($short_url); ?>"
                title="<?php esc_attr_e('Copiar URL', 'url-shortener-by-melk'); ?>"
            >
                <span class="dashicons dashicons-admin-page"></span>
            </button>
            <span class="urlshbym-copied-message" style="display: none;">
                <?php esc_html_e('Copiado!', 'url-shortener-by-melk'); ?>
            </span>
        </div>
        <?php
    }

    public function enqueue_column_assets($hook) {
        // Carrega apenas nas páginas de listagem
        if (!in_array($hook, ['edit.php', 'edit-tags.php'])) {
            return;
        }

		wp_enqueue_style(
			'urlshbym-columns-css',
			URLSHBYM_PLUGIN_URL . 'assets/css/columns.css',
			[],
			URLSHBYM_VERSION
		);

		wp_enqueue_script(
			'urlshbym-columns-js',
			URLSHBYM_PLUGIN_URL . 'assets/js/columns.js',
			['jquery'],
			URLSHBYM_VERSION,
			true
		);

		wp_localize_script('urlshbym-columns-js', 'urlshbymColumns', [
            'copiedText' => esc_html__('Copiado!', 'url-shortener-by-melk'),
            'copyText' => esc_html__('Copiar URL', 'url-shortener-by-melk'),
        ]);
    }
}
