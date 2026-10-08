<?php
namespace Melk\UrlShortenerByMelk;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Contagem ponderada de caracteres seguindo as regras do X:
 * qualquer URL conta 23 caracteres fixos e emojis contam 2.
 * A mesma regra é reimplementada em JS no painel do editor.
 */
class Social_Counter {

    const URL_WEIGHT = 23;

    public static function count_weighted($text) {
        if ($text === '') {
            return 0;
        }

        $urls_removed = preg_replace('~https?://\S+~', '', $text);
        $url_matches = preg_match_all('~https?://\S+~', $text);

        $length = mb_strlen($urls_removed) + ($url_matches * self::URL_WEIGHT);
        $length += self::count_emoji_extra_weight($urls_removed);

        return $length;
    }

    /**
     * Emojis já contam 1 pelo mb_strlen; aqui soma o +1 extra para
     * fechar o peso 2 por emoji, conforme a regra do X.
     */
    private static function count_emoji_extra_weight($text) {
        $pattern = '/[\x{1F300}-\x{1FAFF}\x{2600}-\x{27BF}]/u';
        preg_match_all($pattern, $text, $matches);

        return count($matches[0]);
    }
}
