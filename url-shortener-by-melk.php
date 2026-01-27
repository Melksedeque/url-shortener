<?php
/**
 * Plugin Name: URL Shortener by Melk
 * Plugin URI: https://github.com/Melksedeque/plugin-url-shortener-wordpress
 * Description: Create short URLs for posts, pages, categories, tags, and custom post types in your WordPress.
 * Version: 1.0.0
 * Author: Melksedeque Silva
 * Author URI: https://github.com/Melksedeque
 * License: GPL v3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: url-shortener-by-melk
 */

// Evita acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Define constantes do plugin
define('URLSHBYM_VERSION', '1.0.0');
define('URLSHBYM_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('URLSHBYM_PLUGIN_URL', plugin_dir_url(__FILE__));
define('URLSHBYM_PLUGIN_FILE', __FILE__);

// Autoloader para carregar classes automaticamente
spl_autoload_register(function ($class) {
	$prefix = 'Melk\\UrlShortenerByMelk\\';
	$base_dir = URLSHBYM_PLUGIN_DIR . 'includes/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . 'class-' . strtolower(str_replace('_', '-', $relative_class)) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Carrega arquivos necessários
require_once URLSHBYM_PLUGIN_DIR . 'includes/class-url-shortener.php';
require_once URLSHBYM_PLUGIN_DIR . 'includes/class-admin.php';
require_once URLSHBYM_PLUGIN_DIR . 'includes/class-shortcode-generator.php';
require_once URLSHBYM_PLUGIN_DIR . 'includes/class-redirector.php';
require_once URLSHBYM_PLUGIN_DIR . 'includes/class-admin-columns.php';

// Hook de ativação
register_activation_hook(__FILE__, ['Melk\\UrlShortenerByMelk\\URL_Shortener', 'activate']);

// Hook de desativação
register_deactivation_hook(__FILE__, ['Melk\\UrlShortenerByMelk\\URL_Shortener', 'deactivate']);

// Inicializa o plugin
function urlshbym_init() {
	$plugin = Melk\UrlShortenerByMelk\URL_Shortener::get_instance();
	$plugin->run();
}
add_action('plugins_loaded', 'urlshbym_init');

// Adiciona link de documentação na listagem de plugins
function urlshbym_add_action_links($links) {
    $plugin_data = get_plugin_data(__FILE__);
    $plugin_uri = $plugin_data['PluginURI'];
    
    $docs_link = '<a href="' . esc_url($plugin_uri) . '#readme" target="_blank" aria-label="' . esc_attr__('View plugin documentation', 'url-shortener-by-melk') . '">' . __('Documentation', 'url-shortener-by-melk') . '</a>';
    
	array_push($links, $docs_link);
	return $links;
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'urlshbym_add_action_links');
