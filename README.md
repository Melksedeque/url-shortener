# URL Shortener by Melk

> **WordPress plugin for creating custom short URLs.**

[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](https://www.gnu.org/licenses/gpl-3.0)
[![WordPress](https://img.shields.io/badge/WordPress-Tested-blue.svg)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-7.4%2B-purple.svg)](https://www.php.net/)
[![Status](https://img.shields.io/badge/Status-Under%20Review-yellow.svg)](https://wordpress.org/plugins/)

**URL Shortener by Melk** is a lightweight, efficient, and secure WordPress plugin that allows you to automatically generate short URLs for your posts, pages, categories, tags, and Custom Post Types. Ideal for sharing on social media and marketing materials.

🚀 **Submitted and under review by the WordPress.org Plugin Team.**

---

## ✨ Features

- 🔗 **Automatic Generation:** Automatically creates short URLs when publishing new posts.
- 🎯 **Comprehensive Support:** Works with Posts, Pages, Categories, Tags, and Custom Post Types.
- 📋 **Quick Copy:** "Copy" button directly in the post/term listing in the admin panel.
- ⚡ **Bulk Generation:** Tool to generate short URLs for old content with one click.
- 🚀 **Performance:** Fast redirection using native WordPress rewrite rules.
- 🔒 **Secure:** Validated, sanitized, and escaped code strictly following WordPress standards.

---

## 🛡️ Quality and Security (Compliance)

This plugin was developed following WordPress development best practices and approved in rigorous quality tests (Plugin Check):

- **Database Security:** All queries use `wpdb->prepare()` to prevent SQL Injection.
- **Sanitization and Escaping:** All input data is sanitized and all outputs are escaped (`esc_html`, `esc_attr`, etc.) to prevent XSS.
- **Optimized Performance:** Implementation of **Object Caching** (`wp_cache_get`/`wp_cache_set`) to reduce database queries in high-traffic environments.
- **Code Standards:** Compatible with WordPress Coding Standards (WPCS).

---

## 🚀 Installation

### Via Upload (ZIP)

1. Download the `.zip` file from this repository.
2. In the WordPress dashboard, go to **Plugins > Add New**.
3. Click **Upload Plugin** and select the downloaded file.
4. Click **Install Now** and then **Activate**.

### Via Git (For Developers)

1. Navigate to your WordPress plugins folder:
   ```bash
   cd wp-content/plugins
   ```
2. Clone the repository:
   ```bash
   git clone https://github.com/Melksedeque/url-shortener.git
   ```
3. Activate the plugin in the WordPress dashboard.

---

## 📖 How to Use

### Initial Configuration

1. After activation, go to **Settings > URL Shortener**.
2. Select which **Post Types** (Posts, Pages, etc.) should have short URLs.
3. Select which **Taxonomies** (Categories, Tags, etc.) should have short URLs.
4. Click **Save Settings**.

### Generating URLs for Old Content

On the same settings page:
1. Locate the **Generate Short URLs for Existing Content** section.
2. Click the **Generate URLs** button for the desired content types.
3. Wait for the progress bar or completion message.

### Copying Short URLs

1. Go to the post list (**Posts > All Posts**) or categories.
2. Locate the **Short URL** column.
3. Click the **Copy** button next to the URL code.
4. The short URL (e.g., `yoursite.com/a1b2c`) will be copied to your clipboard.

---

## 🧑‍💻 For Developers

- **Main Namespace:** `Melk\\UrlShortenerByMelk`.
- **Unique Prefix:** All functions, options, metas, and hooks use the `urlshbym_` prefix, following official WordPress guidelines to avoid _naming collisions_.
- **Database Options:**
  - `urlshbym_enabled_post_types`
  - `urlshbym_enabled_taxonomies`
- **Meta Keys:**
  - `_urlshbym_short_code` on posts
  - `_urlshbym_short_code` on terms (taxonomies)
- **Database Table:** `{$wpdb->prefix}urlshbym_short_urls` (created on activation to store `short_code -> object` mappings).
- **Main Hooks:**
  - `urlshbym_short_url_clicked` — action fired whenever a short URL is accessed, receiving the `$short_code` and the internal record ID.
- **Rewrite Rules:** Short URLs are resolved via rewrite rule to `index.php?urlshbym_short={code}`, allowing structures like `yoursite.com/abc12`.

These details ensure the plugin is safe to extend in complex environments, avoiding conflicts with other plugins and themes.

---

## 🤝 Contributing

Contributions are welcome! If you have suggestions, bug fixes, or new features:

1. Fork the project.
2. Create a Branch for your Feature (`git checkout -b feature/NewFeature`).
3. Commit your changes (`git commit -m 'Add New Feature'`).
4. Push to the Branch (`git push origin feature/NewFeature`).
5. Open a Pull Request.

---

## 📝 License

This project is licensed under the GPL v3 - see the [LICENSE](LICENSE) file for more details.

---

Developed with ❤️ by [Melksedeque Silva](https://github.com/Melksedeque).
