<?php
namespace Melk\UrlShortenerByMelk;

if (!defined('ABSPATH')) {
    exit;
}

class Redirector {

    /**
     * Resolve URLs curtas sem rewrite rules.
     *
     * Só entra em ação quando o WordPress já concluiu que a requisição seria
     * um 404. Assim, páginas, posts e termos reais (ex.: /sobre) nunca são
     * interceptados por um código curto com o mesmo formato.
     */
    public function handle_redirect() {
        if (!is_404()) {
            return;
        }

        global $wp;

        $path = isset($wp->request) ? trim($wp->request, '/') : '';

        // Códigos curtos têm 5-7 caracteres alfanuméricos, na raiz do site
        if (!preg_match('/^[0-9a-zA-Z]{5,7}$/', $path)) {
            return;
        }

        $result = $this->get_record($path);
        if (!$result) {
            return;
        }

        $destination_url = $this->get_destination_url($result);
        if (empty($destination_url) || is_wp_error($destination_url)) {
            return;
        }

        /**
         * Disparado quando uma URL curta é acessada.
         *
         * @param string $short_code Código curto.
         * @param int    $id         ID do registro na tabela do plugin.
         */
        do_action('urlshbym_short_url_clicked', $path, (int) $result->id);

        // 301 (permanente) para SEO; wp_safe_redirect por segurança
        wp_safe_redirect($destination_url, 301);
        exit;
    }

    /**
     * Busca o registro do código curto (com cache).
     */
    private function get_record($short_code) {
        global $wpdb;

        $cache_key = 'urlshbym_short_' . $short_code;
        $result    = wp_cache_get($cache_key, 'urlshbym_cache');

        if (false !== $result) {
            return $result;
        }

        $table = Shortcode_Generator::table_name();

        // phpcs:ignore WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $result = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$table} WHERE short_code = %s",
            $short_code
        ));

        if ($result) {
            wp_cache_set($cache_key, $result, 'urlshbym_cache', HOUR_IN_SECONDS);
        }

        return $result;
    }

    /**
     * Determina a URL de destino conforme o tipo do objeto.
     */
    private function get_destination_url($record) {
        $object_id = (int) $record->object_id;

        if ($record->object_type === 'post') {
            // Rascunhos, lixeira e posts privados não devem ser expostos
            if (get_post_status($object_id) !== 'publish') {
                return '';
            }
            return get_permalink($object_id);
        }

        if ($record->object_type === 'term') {
            return get_term_link($object_id);
        }

        return '';
    }
}
