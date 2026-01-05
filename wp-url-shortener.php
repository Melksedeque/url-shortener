<?php
/**
 * Plugin Name: WP URL Shortener
 * Plugin URI: https://github.com/Melksedeque/plugin-url-shortener-wordpress
 * Description: Crie URLs curtas para posts, páginas, categorias, tags e custom post types do seu WordPress.
 * Version: 1.0.0
 * Author: Melksedeque Silva
 * Author URI: https://github.com/Melksedeque
 * License: GPL v3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: url-shortener
 */

// Evita acesso direto
if (!defined('ABSPATH')) {
    exit;
}

// Define constantes do plugin
define('WP_URL_SHORTENER_VERSION', '1.0.0');
define('WP_URL_SHORTENER_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WP_URL_SHORTENER_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WP_URL_SHORTENER_PLUGIN_FILE', __FILE__);

// Autoloader para carregar classes automaticamente
spl_autoload_register(function ($class) {
    $prefix = 'WP_URL_Shortener\\';
    $base_dir = WP_URL_SHORTENER_PLUGIN_DIR . 'includes/';

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
require_once WP_URL_SHORTENER_PLUGIN_DIR . 'includes/class-url-shortener.php';
require_once WP_URL_SHORTENER_PLUGIN_DIR . 'includes/class-admin.php';
require_once WP_URL_SHORTENER_PLUGIN_DIR . 'includes/class-shortcode-generator.php';
require_once WP_URL_SHORTENER_PLUGIN_DIR . 'includes/class-redirector.php';
require_once WP_URL_SHORTENER_PLUGIN_DIR . 'includes/class-admin-columns.php';

// Hook de ativação
register_activation_hook(__FILE__, ['WP_URL_Shortener\URL_Shortener', 'activate']);

// Hook de desativação
register_deactivation_hook(__FILE__, ['WP_URL_Shortener\URL_Shortener', 'deactivate']);

// Inicializa o plugin
function wp_url_shortener_init() {
    $plugin = WP_URL_Shortener\URL_Shortener::get_instance();
    $plugin->run();
}
add_action('plugins_loaded', 'wp_url_shortener_init');