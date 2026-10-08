=== URL Shortener by Melk ===
Contributors: Melksedeque
Tags: url shortener, shortlink, redirection, permalink, seo
Requires at least: 5.0
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.1
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
*   **Performance:** Fast redirection resolved only on would-be 404s (no heavy queries), so your pages and posts are never affected.
*   **Secure:** Validated and secure code, following WordPress best practices.

== Installation ==

1. Upload the `url-shortener-by-melk` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Go to **Settings > URL Shortener** to adjust preferences.

== Frequently Asked Questions ==

= Does the plugin work with Custom Post Types? =
Yes! You can enable which post types you want to generate short URLs for in the plugin settings.

= How are URLs generated? =
We use a secure Base62 algorithm to create short and unique strings (e.g., `abc12`) based on the post ID.

= What if a page has the same address as a short code? =
The page wins. Short URLs are only resolved when WordPress would otherwise return a "page not found" error, so your pages, posts and categories are never affected.

= What happens to my data if I delete the plugin? =
By default nothing is removed, so your short links keep working if you reinstall. To remove the table, settings and stored short codes on deletion, enable "Delete all plugin data" in the Danger Zone (Settings > URL Shortener) before deleting the plugin. The same area also has a button to delete all short URLs without removing the plugin.

= Does this plugin post to social media? =
No. URL Shortener by Melk focuses only on generating and redirecting short URLs. For automatic social media content (card text, captions, and more), check out our companion plugin, **Social Kit by Melk**.

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
* Integration helper: `urlshbym_get_short_url_for_post( $post_id )` — a global function other plugins (such as Social Kit by Melk) can call via `function_exists()` to reuse the short URL, with no hard dependency in either direction.
* Resolution: on `template_redirect` (priority 1), only when the main query is a 404, a root-level path of 5-7 alphanumeric characters is looked up in the table and redirected with a 301. No rewrite rules are registered.
* Options also stored: `urlshbym_delete_data_on_uninstall`, `urlshbym_db_version`.

== Screenshots ==

1. Admin settings page where you can choose post types and taxonomies.
2. Quick copy button in the posts list table.
3. Bulk generation tool for existing content.

== Changelog ==

= 1.0.1 =
* Fixed: pages, posts and terms with 5-7 character slugs (e.g. `/about`, `/sobre`) returned a 404 because they were mistaken for short codes. Short URLs are now resolved only when WordPress would otherwise show a 404, so real content always takes priority.
* Fixed: two different items (e.g. a post and a category) could end up with the same short code. Codes are now checked for ownership before being assigned; existing codes are preserved.
* Fixed: the short codes table is now case-sensitive, so codes that differ only by letter case no longer collide.
* Fixed: short codes stored on posts and terms but missing from the table are restored automatically on update.
* Fixed: drafts, trashed and private posts no longer redirect. Short URL records are removed when the post or term is deleted.
* Added: "Danger Zone" in the settings page with a button to delete all short URLs (your settings are kept and the URLs can be generated again).
* Added: `uninstall.php` and an option, in the Danger Zone, to delete all plugin data when the plugin is deleted (off by default).
* Added: Brazilian Portuguese (pt_BR) translation bundled with the plugin; a hard-coded error message in the admin script is now translatable.
* Added: automatic database upgrade routine, so updates apply schema fixes without reactivating the plugin.
* Added: `urlshbym_get_short_url_for_post()`, an optional integration helper other plugins can use to fetch a post's short URL.
* Changed: the plugin stays focused solely on short URLs. Social content generation is now a separate plugin: Social Kit by Melk.
* Changed: removed the rewrite rule and the `urlshbym_short` query var.

= 1.0.0 =
* Initial release.
