<?php
namespace Melk\UrlShortenerByMelk;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Gerador determinístico (Fase 1) do Social Kit: deriva rótulo, título e
 * texto de card, e legenda do X a partir dos dados já existentes no post.
 */
class Rule_Generator implements Generator_Interface {

    public function generate(\WP_Post $post, $short_url) {
        $limits = Social_Config::get_network_limits()['x'];

        $label = $this->build_label($post, $limits['label']);
        $card_title = $this->build_card_title($post, $limits['card_title']);
        $source_text = $this->get_source_text($post);
        $card_text = $this->build_card_text($source_text, $limits['card_text']);
        $hashtags = $this->build_hashtags($post, $limits['hashtags']);
        $caption_x = $this->build_caption($post, $short_url, $source_text, $hashtags, $limits['caption']);

        return [
            'label' => $label,
            'card_title' => $card_title,
            'card_text' => $card_text,
            'caption_x' => $caption_x,
            'hashtags' => $hashtags,
        ];
    }

    private function build_label(\WP_Post $post, $limit) {
        $category = $this->get_primary_category($post);
        if (!$category) {
            return '';
        }

        $label_map = Social_Config::get_label_map();
        $label = isset($label_map[$category->slug]) ? $label_map[$category->slug] : $category->name;

        return mb_strtoupper(Text_Utils::truncate_words($label, $limit));
    }

    private function build_card_title(\WP_Post $post, $limit) {
        $subject = trim((string) get_post_meta($post->ID, '_urlshbym_social_subject', true));
        $base = $subject !== '' ? $subject : $post->post_title;

        return Text_Utils::truncate_words($base, $limit);
    }

    private function build_card_text($source_text, $limit) {
        $trimmed = $this->trim_cta_phrases($source_text);

        return Text_Utils::truncate_sentences($trimmed, $limit);
    }

    private function build_hashtags(\WP_Post $post, $max) {
        $hashtags = [];

        $tags = get_the_tags($post->ID);
        if (!empty($tags)) {
            $pascal = Text_Utils::to_pascal_case($tags[0]->name);
            if ($pascal !== '') {
                $hashtags[] = $pascal;
            }
        }

        $category = $this->get_primary_category($post);
        if ($category) {
            $pascal = Text_Utils::to_pascal_case($category->name);
            if ($pascal !== '' && !in_array($pascal, $hashtags, true)) {
                $hashtags[] = $pascal;
            }
        }

        return array_slice($hashtags, 0, $max);
    }

    private function build_caption(\WP_Post $post, $short_url, $source_text, $hashtags, $limit) {
        $hook = trim((string) get_post_meta($post->ID, '_urlshbym_social_hook', true));
        $sentences = Text_Utils::split_sentences($source_text);

        $hook_sentence = isset($sentences[0]) ? $sentences[0] : '';
        $value_sentence = isset($sentences[1]) ? rtrim($sentences[1], '.!? ') : rtrim($post->post_title, '.!? ');

        $cta = Social_Config::get_cta_map($post);

        for ($attempt = 0; $attempt < 20; $attempt++) {
            $caption = $this->assemble_caption($hook, $hook_sentence, $value_sentence, $cta, $short_url, $hashtags);

            if (Social_Counter::count_weighted($caption) <= $limit) {
                return $caption;
            }

            if (!empty($hashtags)) {
                array_pop($hashtags);
                continue;
            }

            if ($value_sentence !== '') {
                $value_sentence = $this->shorten_by_one_word($value_sentence);
                continue;
            }

            if ($hook_sentence !== '') {
                $hook_sentence = $this->shorten_by_one_word($hook_sentence);
                continue;
            }

            break;
        }

        return $caption;
    }

    private function assemble_caption($hook, $hook_sentence, $value_sentence, $cta, $short_url, $hashtags) {
        $lines = [];

        $opening = trim($hook . ' ' . $hook_sentence);
        if ($opening !== '') {
            $lines[] = $opening;
            $lines[] = '';
        }

        if ($value_sentence !== '') {
            $lines[] = trim($value_sentence . '. ' . $cta . ' 👇');
            $lines[] = '';
        }

        $lines[] = $short_url;

        if (!empty($hashtags)) {
            $lines[] = '';
            $lines[] = implode(' ', array_map(function ($hashtag) {
                return '#' . $hashtag;
            }, $hashtags));
        }

        return implode("\n", $lines);
    }

    private function shorten_by_one_word($text) {
        $words = preg_split('/\s+/', trim($text));
        array_pop($words);

        return implode(' ', $words);
    }

    private function trim_cta_phrases($text) {
        foreach (Social_Config::get_cta_trim_list() as $phrase) {
            $pattern = '/\s*' . preg_quote($phrase, '/') . '\s*$/iu';
            $text = preg_replace($pattern, '', $text);
        }

        return trim($text);
    }

    private function get_source_text(\WP_Post $post) {
        $excerpt = trim($post->post_excerpt);
        if ($excerpt !== '') {
            return wp_strip_all_tags($excerpt);
        }

        $sentences = Text_Utils::split_sentences(wp_strip_all_tags($post->post_content));

        return implode(' ', array_slice($sentences, 0, 2));
    }

    private function get_primary_category(\WP_Post $post) {
        $categories = get_the_category($post->ID);

        return !empty($categories) ? $categories[0] : null;
    }
}
