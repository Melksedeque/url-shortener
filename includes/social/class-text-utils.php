<?php
namespace Melk\UrlShortenerByMelk;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Utilitários de texto usados pelo Social Kit: corte por palavra/frase
 * e PascalCase para hashtags.
 */
class Text_Utils {

    /**
     * Corta o texto por palavra sem ultrapassar $limit caracteres,
     * nunca terminando em uma stopword.
     */
    public static function truncate_words($text, $limit) {
        $text = trim(wp_strip_all_tags($text));

        if (mb_strlen($text) <= $limit) {
            return $text;
        }

        $words = preg_split('/\s+/', $text);
        $result = '';

        foreach ($words as $word) {
            $candidate = $result === '' ? $word : $result . ' ' . $word;
            if (mb_strlen($candidate) > $limit) {
                break;
            }
            $result = $candidate;
        }

        $stopwords = Social_Config::get_stopwords();
        $pieces = explode(' ', $result);

        while (count($pieces) > 1 && in_array(mb_strtolower(end($pieces)), $stopwords, true)) {
            array_pop($pieces);
        }

        return implode(' ', $pieces);
    }

    /**
     * Divide o texto em frases usando pontuação final (. ! ?) como separador.
     */
    public static function split_sentences($text) {
        $text = trim(wp_strip_all_tags($text));

        if ($text === '') {
            return [];
        }

        $sentences = preg_split('/(?<=[.!?])\s+/u', $text);

        return array_values(array_filter(array_map('trim', $sentences)));
    }

    /**
     * Junta frases enquanto couberem em $limit. Se a primeira frase já
     * excede o limite, cai para truncate_words terminando com ponto final.
     */
    public static function truncate_sentences($text, $limit) {
        $sentences = self::split_sentences($text);

        if (empty($sentences)) {
            return '';
        }

        if (mb_strlen($sentences[0]) > $limit) {
            $truncated = self::truncate_words($sentences[0], $limit - 1);
            return rtrim($truncated, '.!?') . '.';
        }

        $result = '';
        foreach ($sentences as $sentence) {
            $candidate = $result === '' ? $sentence : $result . ' ' . $sentence;
            if (mb_strlen($candidate) > $limit) {
                break;
            }
            $result = $candidate;
        }

        return $result;
    }

    /**
     * Converte um texto livre em PascalCase para uso como hashtag
     * (ex.: "ação & aventura" -> "AcaoAventura").
     */
    public static function to_pascal_case($text) {
        $text = remove_accents($text);
        $text = preg_replace('/[^a-zA-Z0-9\s]/', ' ', $text);
        $words = array_filter(preg_split('/\s+/', trim($text)));

        $pascal = array_map(function ($word) {
            return mb_strtoupper(mb_substr($word, 0, 1)) . mb_strtolower(mb_substr($word, 1));
        }, $words);

        return implode('', $pascal);
    }
}
