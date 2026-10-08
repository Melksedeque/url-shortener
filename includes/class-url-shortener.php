<?php
namespace Melk\UrlShortenerByMelk;

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
        // Atualiza estrutura/dados quando a versão instalada for antiga
        $this->maybe_upgrade();

        // Resolve URLs curtas apenas quando a requisição seria um 404.
        // Prioridade 1: antes do redirect_canonical (que tenta "adivinhar" URLs).
        add_action('template_redirect', [$this->redirector, 'handle_redirect'], 1);

        // Gera URL curta automaticamente ao publicar
        add_action('transition_post_status', [$this, 'generate_on_publish'], 10, 3);
        add_action('created_term', [$this, 'generate_on_term_create'], 10, 3);

        // Remove registros órfãos quando o conteúdo é excluído
        add_action('before_delete_post', [$this, 'cleanup_deleted_post']);
        add_action('delete_term', [$this, 'cleanup_deleted_term'], 10, 1);

        // Inicializa componentes
        $this->admin->init();
        $this->admin_columns->init();
    }

    public function generate_on_publish($new_status, $old_status, $post) {
        if ($new_status !== 'publish' || $old_status === 'publish') {
            return;
        }

        $enabled_types = (array) get_option('urlshbym_enabled_post_types', []);
        if (!in_array($post->post_type, $enabled_types, true)) {
            return;
        }

        // Gera URL curta se ainda não existir
        $existing = get_post_meta($post->ID, '_urlshbym_short_code', true);
        if (empty($existing)) {
            $short_code = $this->generator->generate_for_post($post->ID);
            if ($short_code) {
                update_post_meta($post->ID, '_urlshbym_short_code', $short_code);
            }
        }
    }

    public function generate_on_term_create($term_id, $tt_id, $taxonomy) {
        $enabled_taxonomies = (array) get_option('urlshbym_enabled_taxonomies', []);
        if (!in_array($taxonomy, $enabled_taxonomies, true)) {
            return;
        }

        // Gera URL curta se ainda não existir
        $existing = get_term_meta($term_id, '_urlshbym_short_code', true);
        if (empty($existing)) {
            $short_code = $this->generator->generate_for_term($term_id);
            if ($short_code) {
                update_term_meta($term_id, '_urlshbym_short_code', $short_code);
            }
        }
    }

    public function cleanup_deleted_post($post_id) {
        $this->generator->delete_for_object($post_id, 'post');
    }

    public function cleanup_deleted_term($term_id) {
        $this->generator->delete_for_object($term_id, 'term');
    }

    /**
     * Ativação do plugin.
     */
    public static function activate() {
        self::install();
    }

    /**
     * Executa a instalação/atualização (idempotente).
     *
     * O hook de ativação não roda quando o plugin é atualizado, por isso esta
     * rotina também é chamada por maybe_upgrade().
     */
    public static function install() {
        global $wpdb;

        $table_name      = Shortcode_Generator::table_name();
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            short_code varchar(7) NOT NULL,
            object_id bigint(20) NOT NULL,
            object_type varchar(20) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id),
            UNIQUE KEY short_code (short_code),
            KEY object_id (object_id)
        ) $charset_collate;";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);

        // Códigos Base62 diferenciam maiúsculas de minúsculas ("aB3xY" != "ab3xy").
        // Com a collation padrão (case-insensitive) a chave UNIQUE rejeitaria
        // códigos que só diferem na caixa.
        // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.SchemaChange
        $wpdb->query("ALTER TABLE $table_name MODIFY short_code varchar(7) CHARACTER SET ascii COLLATE ascii_bin NOT NULL");

        // Reconstrói registros que existem no meta, mas não na tabela
        self::sync_table_from_meta();

        // Opções padrão (add_option não sobrescreve valores existentes)
        add_option('urlshbym_enabled_post_types', ['post', 'page']);
        add_option('urlshbym_enabled_taxonomies', ['category', 'post_tag']);
        add_option('urlshbym_delete_data_on_uninstall', 0);

        // Descarta rewrite rules antigas (versões < 1.0.1 registravam uma regra
        // na raiz que interceptava páginas com slug de 5 a 7 caracteres).
        // O WordPress as regenera automaticamente no próximo acesso.
        delete_option('rewrite_rules');

        update_option('urlshbym_db_version', URLSHBYM_DB_VERSION);
    }

    /**
     * Roda a instalação quando a versão do banco está desatualizada.
     */
    public function maybe_upgrade() {
        if (get_option('urlshbym_db_version') === URLSHBYM_DB_VERSION) {
            return;
        }

        self::install();
    }

    /**
     * Copia para a tabela os códigos guardados em post/term meta que ainda
     * não existem nela (INSERT IGNORE respeita a chave UNIQUE).
     */
    private static function sync_table_from_meta() {
        global $wpdb;

        $table = Shortcode_Generator::table_name();

        // phpcs:disable WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $wpdb->query(
            "INSERT IGNORE INTO $table (short_code, object_id, object_type)
             SELECT meta_value, post_id, 'post' FROM {$wpdb->postmeta}
             WHERE meta_key = '_urlshbym_short_code' AND meta_value <> '' AND CHAR_LENGTH(meta_value) <= 7"
        );

        $wpdb->query(
            "INSERT IGNORE INTO $table (short_code, object_id, object_type)
             SELECT meta_value, term_id, 'term' FROM {$wpdb->termmeta}
             WHERE meta_key = '_urlshbym_short_code' AND meta_value <> '' AND CHAR_LENGTH(meta_value) <= 7"
        );
        // phpcs:enable
    }
}
