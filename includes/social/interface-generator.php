<?php
namespace Melk\UrlShortenerByMelk;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Contrato que qualquer gerador de conteúdo do Social Kit precisa seguir.
 * Permite trocar as regras determinísticas (Fase 1) por um gerador via
 * IA no futuro sem alterar o restante do plugin.
 */
interface Generator_Interface {

    /**
     * @param \WP_Post $post      Post publicado.
     * @param string   $short_url URL curta já gerada pelo plugin.
     *
     * @return array{label:string, card_title:string, card_text:string, caption_x:string, hashtags:array}
     */
    public function generate(\WP_Post $post, $short_url);
}
