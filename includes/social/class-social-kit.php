<?php
namespace Melk\UrlShortenerByMelk;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Orquestra o Social Kit: registra os post meta usados pelo painel do
 * editor, gera os campos por regras ao salvar um post publicado e
 * enfileira os assets do painel Gutenberg.
 */
class Social_Kit {

    const VERSION = '1.0';

    public function init() {
        add_action('init', [$this, 'register_meta']);
        add_action('save_post', [$this, 'maybe_generate'], 20, 3);
        add_action('enqueue_block_editor_assets', [$this, 'enqueue_editor_assets']);
    }

    public function register_meta() {
        $string_fields = [
            '_urlshbym_social_label',
            '_urlshbym_social_card_title',
            '_urlshbym_social_card_text',
            '_urlshbym_social_caption_x',
            '_urlshbym_social_source_hash',
            '_urlshbym_social_version',
        ];

        foreach ($string_fields as $meta_key) {
            register_post_meta('', $meta_key, [
                'show_in_rest' => true,
                'single' => true,
                'type' => 'string',
                'sanitize_callback' => 'sanitize_textarea_field',
                'auth_callback' => [$this, 'can_edit_social_kit'],
            ]);
        }

        register_post_meta('', '_urlshbym_social_hashtags', [
            'show_in_rest' => [
                'schema' => [
                    'type' => 'array',
                    'items' => ['type' => 'string'],
                ],
            ],
            'single' => true,
            'type' => 'array',
            'sanitize_callback' => [$this, 'sanitize_hashtags'],
            'auth_callback' => [$this, 'can_edit_social_kit'],
        ]);

        register_post_meta('', '_urlshbym_social_locked', [
            'show_in_rest' => true,
            'single' => true,
            'type' => 'boolean',
            'sanitize_callback' => 'rest_sanitize_boolean',
            'auth_callback' => [$this, 'can_edit_social_kit'],
        ]);
    }

    public function sanitize_hashtags($value) {
        return is_array($value) ? array_map('sanitize_text_field', $value) : [];
    }

    public function can_edit_social_kit() {
        return current_user_can('edit_posts');
    }

    public function maybe_generate($post_id, $post, $update) {
        if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
            return;
        }

        if ($post->post_status !== 'publish') {
            return;
        }

        $enabled_types = get_option('urlshbym_social_enabled_post_types', []);
        if (!in_array($post->post_type, $enabled_types, true)) {
            return;
        }

        if (get_post_meta($post_id, '_urlshbym_social_locked', true)) {
            return;
        }

        $source_hash = $this->build_source_hash($post);
        if ($source_hash === get_post_meta($post_id, '_urlshbym_social_source_hash', true)) {
            return;
        }

        $short_code = get_post_meta($post_id, '_urlshbym_short_code', true);
        if (empty($short_code)) {
            return;
        }

        $generator = new Shortcode_Generator();
        $short_url = $generator->get_short_url($short_code);

        $fields = (new Rule_Generator())->generate($post, $short_url);

        update_post_meta($post_id, '_urlshbym_social_label', $fields['label']);
        update_post_meta($post_id, '_urlshbym_social_card_title', $fields['card_title']);
        update_post_meta($post_id, '_urlshbym_social_card_text', $fields['card_text']);
        update_post_meta($post_id, '_urlshbym_social_caption_x', $fields['caption_x']);
        update_post_meta($post_id, '_urlshbym_social_hashtags', $fields['hashtags']);
        update_post_meta($post_id, '_urlshbym_social_source_hash', $source_hash);
        update_post_meta($post_id, '_urlshbym_social_version', self::VERSION);
    }

    private function build_source_hash(\WP_Post $post) {
        $category = get_the_category($post->ID);
        $category_id = !empty($category) ? $category[0]->term_id : 0;

        return md5($post->post_title . '|' . $post->post_excerpt . '|' . $category_id);
    }

    public function enqueue_editor_assets() {
        $screen = get_current_screen();
        if (!$screen || !$screen->is_block_editor()) {
            return;
        }

        $enabled_types = get_option('urlshbym_social_enabled_post_types', []);
        if (!in_array($screen->post_type, $enabled_types, true)) {
            return;
        }

        wp_enqueue_style(
            'urlshbym-social-panel-css',
            URLSHBYM_PLUGIN_URL . 'assets/css/social-panel.css',
            [],
            URLSHBYM_VERSION
        );

        wp_enqueue_script(
            'urlshbym-social-panel-js',
            URLSHBYM_PLUGIN_URL . 'assets/js/social-panel.js',
            ['wp-plugins', 'wp-edit-post', 'wp-element', 'wp-components', 'wp-data', 'wp-i18n'],
            URLSHBYM_VERSION,
            true
        );

        wp_localize_script('urlshbym-social-panel-js', 'urlshbymSocialKit', [
            'limits' => Social_Config::get_network_limits()['x'],
            'strings' => [
                'panelTitle' => __('Social Kit', 'url-shortener-by-melk'),
                'label' => __('Label', 'url-shortener-by-melk'),
                'cardTitle' => __('Card title', 'url-shortener-by-melk'),
                'cardText' => __('Card text', 'url-shortener-by-melk'),
                'captionX' => __('X caption', 'url-shortener-by-melk'),
                'locked' => __('Lock editing', 'url-shortener-by-melk'),
                'regenerate' => __('Regenerate', 'url-shortener-by-melk'),
                'copy' => __('Copy', 'url-shortener-by-melk'),
                'copied' => __('Copied!', 'url-shortener-by-melk'),
                'copyAll' => __('Copy all', 'url-shortener-by-melk'),
                'openOnX' => __('Open on X', 'url-shortener-by-melk'),
                'noShortUrlYet' => __('Publish the post to generate a short URL first.', 'url-shortener-by-melk'),
            ],
        ]);
    }
}
