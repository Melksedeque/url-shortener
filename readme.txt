=== URL Shortener by Melk ===
Contributors: Melksedeque
Tags: url shortener, shortlink, redirection, permalink, seo
Requires at least: 5.0
Tested up to: 6.9
Requires PHP: 7.4
Stable tag: 1.0.0
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

== Installation ==

1. Upload the `url-shortener` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to **Settings > URL Shortener** to adjust preferences.

== Frequently Asked Questions ==

= Does the plugin work with Custom Post Types? =
Yes! You can enable which post types you want to generate short URLs for in the plugin settings.

= How are URLs generated? =
We use a secure Base62 algorithm to create short and unique strings (e.g., `abc12`) based on the post ID.

== Developer Notes ==

* Namespace: `Melk\\UrlShortenerByMelk`.
* Unique prefix: all functions, options, meta keys and hooks use the `urlshbym_` prefix, following the WordPress Plugin Handbook recommendations to avoid naming collisions.
* Options stored in the database:
  * `urlshbym_enabled_post_types`
  * `urlshbym_enabled_taxonomies`
* Meta keys:
  * `_urlshbym_short_code` on posts
  * `_urlshbym_short_code` on terms (taxonomies)
* Database table: `{$wpdb->prefix}urlshbym_short_urls` is created on activation to store the mapping between short codes and objects.
* Main hook:
  * `urlshbym_short_url_clicked` — fired whenever a short URL is accessed, receiving the short code and the internal record ID.
* Rewrite rules: short URLs are handled through a rewrite rule that maps patterns like `/abc12` to `index.php?urlshbym_short=abc12`.

== Screenshots ==

1. Admin settings page where you can choose post types and taxonomies.
2. Quick copy button in the posts list table.
3. Bulk generation tool for existing content.

== Changelog ==

= 1.0.0 =
* Initial release.
