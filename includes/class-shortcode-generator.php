<?php
namespace WP_URL_Shortener;

if (!defined('ABSPATH')) {
    exit;
}

class Shortcode_Generator {
    
    /**
     * Caracteres Base62: a-z, A-Z, 0-9
     */
    private $base62_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Gera código curto baseado em Base62 a partir de um número
     */
    private function base62_encode($num) {
        if ($num === 0) {
            return $this->base62_chars[0];
        }

        $base = strlen($this->base62_chars);
        $encoded = '';

        while ($num > 0) {
            $remainder = $num % $base;
            $encoded = $this->base62_chars[$remainder] . $encoded;
            $num = floor($num / $base);
        }

        return $encoded;
    }

    /**
     * Gera hash consistente baseado no ID
     * Adiciona um salt para evitar que IDs baixos gerem códigos muito curtos
     */
    private function generate_hash($id, $type = 'post') {
        // Adiciona um salt diferente por tipo para evitar colisões
        $salt = [
            'post' => 10000,
            'term' => 20000,
        ];
        
        $salted_id = $id + ($salt[$type] ?? 0);
        $encoded = $this->base62_encode($salted_id);
        
        // Garante pelo menos 5 caracteres, máximo 7
        $encoded = str_pad($encoded, 5, '0', STR_PAD_LEFT);
        $encoded = substr($encoded, 0, 7);
        
        return $encoded;
    }

    /**
     * Gera URL curta para um post
     */
    public function generate_for_post($post_id) {
        global $wpdb;
        
        $short_code = $this->generate_hash($post_id, 'post');
        
        // Verifica se já existe na tabela usando cache primeiro
        $cache_key = 'wpus_check_' . $short_code;
        $existing = wp_cache_get($cache_key, 'url_shortener');

        if (false === $existing) {
            $table_name = $wpdb->prefix . 'url_shortener';
            // Query direta no prepare para conformidade
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
            $existing = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}url_shortener WHERE short_code = %s",
                $short_code
            ));

            if ($existing) {
                wp_cache_set($cache_key, $existing, 'url_shortener', HOUR_IN_SECONDS);
            }
        }
        
        // Se não existe, insere na tabela
        if (!$existing) {
            $table_name = $wpdb->prefix . 'url_shortener';
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
            $wpdb->insert(
                $table_name,
                [
                    'short_code' => $short_code,
                    'object_id' => $post_id,
                    'object_type' => 'post',
                ],
                ['%s', '%d', '%s']
            );
        }
        
        return $short_code;
    }

    /**
     * Gera URL curta para um termo (categoria/tag)
     */
    public function generate_for_term($term_id) {
        global $wpdb;
        
        $short_code = $this->generate_hash($term_id, 'term');
        
        // 1. Defina uma chave única para o cache baseada no short_code
        $cache_key = 'short_url_' . md5($short_code);
        $group     = 'url_shortener_queries';

        // 2. Tenta recuperar o resultado do cache primeiro
        $existing = wp_cache_get($cache_key, $group);

        if (false === $existing) {
            // 3. Se não estiver no cache, faz a consulta ao banco
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
            $existing = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}url_shortener WHERE short_code = %s",
                $short_code
            ));

            // 4. Salva o resultado no cache por 1 hora (3600 segundos) para evitar consultas repetitivas
            if ($existing) {
                wp_cache_set($cache_key, $existing, $group, HOUR_IN_SECONDS);
            }
        }
        
        // Se não existe, insere na tabela
        if (!$existing) {
            $table_name = $wpdb->prefix . 'url_shortener';
            // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery
            $wpdb->insert(
                $table_name,
                [
                    'short_code' => $short_code,
                    'object_id' => $term_id,
                    'object_type' => 'term',
                ],
                ['%s', '%d', '%s']
            );
        }
        
        return $short_code;
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
        $args = [
            'post_type' => $post_type,
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
        ];
        
        $posts = get_posts($args);
        $generated = 0;
        
        foreach ($posts as $post_id) {
            $existing = get_post_meta($post_id, '_wpus_short_code', true);
            if (empty($existing)) {
                $short_code = $this->generate_for_post($post_id);
                update_post_meta($post_id, '_wpus_short_code', $short_code);
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
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ]);
        
        $generated = 0;
        
        foreach ($terms as $term) {
            $existing = get_term_meta($term->term_id, '_wpus_short_code', true);
            if (empty($existing)) {
                $short_code = $this->generate_for_term($term->term_id);
                update_term_meta($term->term_id, '_wpus_short_code', $short_code);
                $generated++;
            }
        }
        
        return $generated;
    }
}
