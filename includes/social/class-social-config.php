<?php
namespace Melk\UrlShortenerByMelk;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Centraliza os valores padrão (rótulos, CTAs, stopwords, limites por rede)
 * do Social Kit. Cada site pode sobrescrever qualquer valor via filtro,
 * sem precisar editar código.
 */
class Social_Config {

    /**
     * Palavras que não devem ficar no final de um título cortado.
     * Cobre PT-BR e EN por padrão; outros idiomas entram via filtro.
     */
    public static function get_stopwords() {
        $stopwords = [
            // PT-BR
            'a', 'o', 'as', 'os', 'de', 'da', 'do', 'das', 'dos', 'e', 'é',
            'em', 'um', 'uma', 'uns', 'umas', 'para', 'por', 'com', 'sem',
            'que', 'no', 'na', 'nos', 'nas', 'ao', 'aos', 'à', 'às',
            // EN
            'a', 'an', 'the', 'of', 'to', 'in', 'on', 'for', 'and', 'or',
            'with', 'at', 'by', 'from',
        ];

        return apply_filters('urlshbym_social_stopwords', array_unique($stopwords));
    }

    /**
     * Frases de chamada para ação que competem com o botão do card
     * e por isso são removidas do final do texto de apoio.
     */
    public static function get_cta_trim_list() {
        $list = [
            'vale a pena?',
            'confira!',
            'confira.',
            'leia mais',
            'saiba mais',
            'worth it?',
            'check it out!',
            'read more',
            'find out more',
        ];

        return apply_filters('urlshbym_social_cta_trim_list', $list);
    }

    /**
     * Mapa de categoria (slug) para frase de CTA usada na legenda.
     * 'default' é usado quando a categoria do post não está no mapa.
     */
    public static function get_cta_map(\WP_Post $post) {
        $map = apply_filters('urlshbym_social_cta_map', [
            'default' => __('Read more', 'url-shortener-by-melk'),
        ], $post);

        $categories = get_the_category($post->ID);
        if (!empty($categories)) {
            foreach ($categories as $category) {
                if (isset($map[$category->slug])) {
                    return $map[$category->slug];
                }
            }
        }

        return $map['default'];
    }

    /**
     * Mapa de categoria (slug) para rótulo customizado do card.
     * Vazio por padrão: o rótulo cai no nome da categoria em caixa alta.
     */
    public static function get_label_map() {
        return apply_filters('urlshbym_social_label_map', []);
    }

    /**
     * Limites e configuração por rede social. Só "x" é usado na Fase 1,
     * mas a estrutura já é pensada para novas redes entrarem via filtro.
     */
    public static function get_network_limits() {
        $networks = [
            'x' => [
                'caption'    => 280,
                'card_title' => 30,
                'card_text'  => 170,
                'label'      => 20,
                'hashtags'   => 3,
            ],
        ];

        return apply_filters('urlshbym_social_networks', $networks);
    }
}
