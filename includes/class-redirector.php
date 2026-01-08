<?php
namespace WP_URL_Shortener;

if (!defined('ABSPATH')) {
    exit;
}

class Redirector {
    
    /**
     * Adiciona regras de rewrite para capturar URLs curtas
     */
    public function add_rewrite_rules() {
        // Captura URLs com 5-7 caracteres alfanuméricos na raiz
        add_rewrite_rule(
            '^([0-9a-zA-Z]{5,7})/?$',
            'index.php?wpus_short=$matches[1]',
            'top'
        );
        
        // Registra a query var
        add_filter('query_vars', [$this, 'add_query_vars']);
    }

    /**
     * Adiciona variável de query personalizada
     */
    public function add_query_vars($vars) {
        $vars[] = 'wpus_short';
        return $vars;
    }

    /**
     * Gerencia o redirecionamento das URLs curtas
     */
    public function handle_redirect() {
        $short_code = get_query_var('wpus_short');
        
        if (empty($short_code)) {
            return;
        }

        // Tenta buscar do cache primeiro
        $cache_key = 'wpus_short_' . $short_code;
        $result = wp_cache_get($cache_key, 'url_shortener');

        if (false === $result) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'url_shortener';
            
            // Busca o código curto na tabela
            $result = $wpdb->get_row($wpdb->prepare(
                "SELECT * FROM $table_name WHERE short_code = %s",
                $short_code
            ));

            if ($result) {
                wp_cache_set($cache_key, $result, 'url_shortener', HOUR_IN_SECONDS);
            }
        }
        
        if (!$result) {
            // Se não encontrou, retorna 404
            global $wp_query;
            $wp_query->set_404();
            status_header(404);
            return;
        }
        
        // Determina a URL de destino baseado no tipo
        $destination_url = '';
        
        if ($result->object_type === 'post') {
            $destination_url = get_permalink((int) $result->object_id);
        } elseif ($result->object_type === 'term') {
            $destination_url = get_term_link((int) $result->object_id);
        }
        
        // Se encontrou uma URL válida, redireciona
        if (!empty($destination_url) && !is_wp_error($destination_url)) {
            // Aqui virá a lógica de tracking de cliques nas versões futuras
            $this->track_click($result->id, $short_code);
            
            // Redireciona com código 301 (permanente)
            // wp_safe_redirect é preferível para segurança
            wp_safe_redirect($destination_url, 301);
            exit;
        } else {
            // Se a URL de destino não é válida, retorna 404
            global $wp_query;
            $wp_query->set_404();
            status_header(404);
        }
    }

    /**
     * Tracking de cliques (preparado para implementação futura)
     */
    private function track_click($id, $short_code) {
        // Implementação futura: salvar cliques em tabela separada
        // Por enquanto, apenas incrementa um contador no post meta
        global $wpdb;
        $table_name = $wpdb->prefix . 'url_shortener';
        
        // Futuramente, criar tabela de clicks com informações como:
        // - IP do visitante
        // - User agent
        // - Referrer
        // - Data/hora do acesso
        // - Geolocalização
        
        // Por enquanto, só registramos que houve acesso
        do_action('wpus_short_url_clicked', $short_code, $id);
    }
}
