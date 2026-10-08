<?php
namespace Melk\UrlShortenerByMelk;

if (!defined('ABSPATH')) {
    exit;
}

class Shortcode_Generator {

    /**
     * Máximo de tentativas ao resolver colisões de código.
     */
    const MAX_ATTEMPTS = 20;

    /**
     * Caracteres Base62: 0-9, a-z, A-Z
     */
    private $base62_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Nome da tabela de URLs curtas.
     */
    public static function table_name() {
        global $wpdb;
        return $wpdb->prefix . 'urlshbym_short_urls';
    }

    /**
     * Codifica um inteiro em Base62.
     */
    private function base62_encode($num) {
        $num = (int) $num;

        if ($num <= 0) {
            return $this->base62_chars[0];
        }

        $base    = strlen($this->base62_chars);
        $encoded = '';

        while ($num > 0) {
            $encoded = $this->base62_chars[$num % $base] . $encoded;
            $num     = intdiv($num, $base);
        }

        return $encoded;
    }

    /**
     * Gera um código determinístico a partir do ID.
     * O salt evita códigos muito curtos; $attempt só é usado quando há colisão.
     */
    private function generate_hash($id, $type = 'post', $attempt = 0) {
        $salt = [
            'post' => 10000,
            'term' => 20000,
        ];

        $salted_id = (int) $id + ($salt[$type] ?? 0) + ($attempt * 1000003);
        $encoded   = $this->base62_encode($salted_id);

        // Garante pelo menos 5 caracteres, máximo 7
        $encoded = str_pad($encoded, 5, '0', STR_PAD_LEFT);

        return substr($encoded, 0, 7);
    }

    /**
     * Reivindica um código para o objeto: reaproveita o que já existe
     * ou grava um novo, resolvendo colisões com outros objetos.
     *
     * @return string|false Código curto ou false se não foi possível gerar.
     */
    private function claim_code($object_id, $type) {
        global $wpdb;

        $object_id = (int) $object_id;
        $table     = self::table_name();

        // phpcs:disable WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared

        // Já existe um código para este objeto?
        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT short_code FROM {$table} WHERE object_id = %d AND object_type = %s LIMIT 1",
            $object_id,
            $type
        ));

        if ($existing) {
            // phpcs:enable
            return $existing;
        }

        for ($attempt = 0; $attempt < self::MAX_ATTEMPTS; $attempt++) {
            $code = $this->generate_hash($object_id, $type, $attempt);

            $owner = $wpdb->get_var($wpdb->prepare(
                "SELECT id FROM {$table} WHERE short_code = %s",
                $code
            ));

            if ($owner) {
                // Código pertence a outro objeto: tenta o próximo
                continue;
            }

            $inserted = $wpdb->insert(
                $table,
                [
                    'short_code'  => $code,
                    'object_id'   => $object_id,
                    'object_type' => $type,
                ],
                ['%s', '%d', '%s']
            );

            if ($inserted) {
                // phpcs:enable
                return $code;
            }
        }

        // phpcs:enable
        return false;
    }

    /**
     * Gera URL curta para um post
     *
     * @return string|false
     */
    public function generate_for_post($post_id) {
        return $this->claim_code($post_id, 'post');
    }

    /**
     * Gera URL curta para um termo (categoria/tag)
     *
     * @return string|false
     */
    public function generate_for_term($term_id) {
        return $this->claim_code($term_id, 'term');
    }

    /**
     * Remove o registro de um objeto (post ou term) da tabela.
     */
    public function delete_for_object($object_id, $type) {
        global $wpdb;

        $table = self::table_name();

        // phpcs:disable WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $code = $wpdb->get_var($wpdb->prepare(
            "SELECT short_code FROM {$table} WHERE object_id = %d AND object_type = %s LIMIT 1",
            (int) $object_id,
            $type
        ));

        if ($code) {
            $wpdb->delete($table, ['object_id' => (int) $object_id, 'object_type' => $type], ['%d', '%s']);
            wp_cache_delete('urlshbym_short_' . $code, 'urlshbym_cache');
        }
        // phpcs:enable
    }

    /**
     * Apaga TODAS as URLs curtas (tabela + códigos guardados em post/term meta).
     * As configurações do plugin são mantidas.
     *
     * @return int Quantidade de URLs curtas removidas.
     */
    public function delete_all() {
        global $wpdb;

        $table = self::table_name();

        // phpcs:disable WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $codes = $wpdb->get_col("SELECT short_code FROM $table");

        $wpdb->query("DELETE FROM $table");
        // phpcs:enable

        // Invalida o cache dos redirecionamentos
        foreach ($codes as $code) {
            wp_cache_delete('urlshbym_short_' . $code, 'urlshbym_cache');
        }

        // Remove os códigos guardados nos metadados (atualiza também o cache de meta)
        delete_metadata('post', 0, '_urlshbym_short_code', '', true);
        delete_metadata('term', 0, '_urlshbym_short_code', '', true);

        return count($codes);
    }

    /**
     * Obtém a URL curta completa
     */
    public function get_short_url($short_code) {
        return home_url('/' . $short_code);
    }

    /**
     * Gera URLs curtas para todos os posts existentes de um tipo específico
     */
    public function generate_bulk_for_posts($post_type) {
        $posts = get_posts([
            'post_type'      => $post_type,
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'no_found_rows'  => true,
        ]);

        $generated = 0;

        foreach ($posts as $post_id) {
            $existing = get_post_meta($post_id, '_urlshbym_short_code', true);
            if (!empty($existing)) {
                continue;
            }

            $short_code = $this->generate_for_post($post_id);
            if ($short_code) {
                update_post_meta($post_id, '_urlshbym_short_code', $short_code);
                $generated++;
            }
        }

        return $generated;
    }

    /**
     * Gera URLs curtas para todos os termos de uma taxonomia
     */
    public function generate_bulk_for_terms($taxonomy) {
        $terms = get_terms([
            'taxonomy'   => $taxonomy,
            'hide_empty' => false,
        ]);

        if (is_wp_error($terms)) {
            return 0;
        }

        $generated = 0;

        foreach ($terms as $term) {
            $existing = get_term_meta($term->term_id, '_urlshbym_short_code', true);
            if (!empty($existing)) {
                continue;
            }

            $short_code = $this->generate_for_term($term->term_id);
            if ($short_code) {
                update_term_meta($term->term_id, '_urlshbym_short_code', $short_code);
                $generated++;
            }
        }

        return $generated;
    }
}
