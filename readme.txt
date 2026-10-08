=== URL Shortener by Melk ===
Contributors: Melksedeque
Tags: url shortener, shortlink, redirection, permalink, seo
Requires at least: 5.3
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.1.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Create short URLs for your WordPress posts, pages, categories, tags, and custom post types automatically.

== Description ==

**URL Shortener by Melk** is a lightweight and efficient WordPress plugin that allows you to automatically generate short URLs for your posts, pages, categories, tags, and Custom Post Types. Ideal for sharing on social media and marketing materials.

**Features:**

*   **Automatic Generation:** Automatically creates short URLs when publishing new posts.
*   **Comprehensive Support:** Works with Posts, Pages, Categories, Tags, and Custom Post Types.
*   **Quick Copy:** "Copy" button directly in the post/term listing in the admin panel.
*   **Bulk Generation:** Tool to generate short URLs for old content with one click.
*   **Performance:** Fast redirection using native WordPress rewrite rules (no heavy queries).
*   **Secure:** Validated and secure code, following WordPress best practices.
*   **Social Kit (Beta):** Automatically generates a card label, card title, card text, and a character-counted X (Twitter) caption for each post, ready to copy or post with one click.

== Installation ==

1. Upload the `url-shortener` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to **Settings > URL Shortener** to adjust preferences.

== Frequently Asked Questions ==

= Does the plugin work with Custom Post Types? =
Yes! You can enable which post types you want to generate short URLs for in the plugin settings.

= How are URLs generated? =
We use a secure Base62 algorithm to create short and unique strings (e.g., `abc12`) based on the post ID.

= What is the Social Kit? =
Social Kit (Beta) automatically derives a card label, card title, card text, and an X (Twitter) caption from each post's title, excerpt, category, and short URL. Enable it for the content types you want in **Settings > URL Shortener**, then use the "Social Kit" panel in the block editor to review, edit, lock, or copy the generated text. Any field you edit by hand is automatically locked so future saves won't overwrite it.

= Does the Social Kit publish to X automatically? =
No. It only prepares the caption and gives you an "Open on X" button that opens a pre-filled post composer — you still click to publish, with no API keys or costs involved.

== Developer Notes ==

* Namespace: `Melk\\UrlShortenerByMelk`.
* Unique prefix: all functions, options, meta keys and hooks use the `urlshbym_` prefix, following the WordPress Plugin Handbook recommendations to avoid naming collisions.
* Options stored in the database:
  * `urlshbym_enabled_post_types`
  * `urlshbym_enabled_taxonomies`
  * `urlshbym_social_enabled_post_types` — post types with the Social Kit panel enabled (empty by default).
* Meta keys:
  * `_urlshbym_short_code` on posts
  * `_urlshbym_short_code` on terms (taxonomies)
  * `_urlshbym_social_label`, `_urlshbym_social_card_title`, `_urlshbym_social_card_text`, `_urlshbym_social_caption_x`, `_urlshbym_social_hashtags`, `_urlshbym_social_locked`, `_urlshbym_social_source_hash`, `_urlshbym_social_version` on posts (Social Kit, all `show_in_rest`).
  * `_urlshbym_social_subject` and `_urlshbym_social_hook` — optional post meta you can set yourself to override the card title base and the caption's opening line.
* Database table: `{$wpdb->prefix}urlshbym_short_urls` is created on activation to store the mapping between short codes and objects.
* Main hook:
  * `urlshbym_short_url_clicked` — fired whenever a short URL is accessed, receiving the short code and the internal record ID.
* Social Kit filters (all optional, no code required for basic use):
  * `urlshbym_social_stopwords` — words that should never end a truncated title (defaults cover PT-BR and EN).
  * `urlshbym_social_cta_trim_list` — trailing CTA phrases removed from the card text.
  * `urlshbym_social_cta_map` — category slug to call-to-action phrase used in the X caption.
  * `urlshbym_social_label_map` — category slug to custom card label.
  * `urlshbym_social_networks` — per-network character limits, ready for future networks beyond X.
* Rewrite rules: short URLs are handled through a rewrite rule that maps patterns like `/abc12` to `index.php?urlshbym_short=abc12`.

== Screenshots ==

1. Admin settings page where you can choose post types and taxonomies.
2. Quick copy button in the posts list table.
3. Bulk generation tool for existing content.

== Changelog ==

= 1.1.0 =
* Added the Social Kit (Beta): automatically generates a card label, card title, card text, and an X (Twitter) caption for each post, with a review/edit/copy panel in the block editor.
* New setting to choose which content types get the Social Kit panel.

= 1.0.0 =
* Initial release.
