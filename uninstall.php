<?php
/**
 * Executado quando o plugin é excluído pelo painel do WordPress.
 *
 * Os dados só são removidos se o administrador marcou a opção
 * "Delete all plugin data when the plugin is deleted" nas configurações.
 */

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

/**
 * Remove os dados do plugin do site atual.
 */
function urlshbym_uninstall_site() {
    global $wpdb;

    if (!(int) get_option('urlshbym_delete_data_on_uninstall', 0)) {
        return;
    }

    // phpcs:disable WordPress.DB.DirectDatabaseQuery, WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.DirectDatabaseQuery.SchemaChange
    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}urlshbym_short_urls");
    $wpdb->delete($wpdb->postmeta, ['meta_key' => '_urlshbym_short_code'], ['%s']);
    $wpdb->delete($wpdb->termmeta, ['meta_key' => '_urlshbym_short_code'], ['%s']);
    // phpcs:enable

    delete_option('urlshbym_enabled_post_types');
    delete_option('urlshbym_enabled_taxonomies');
    delete_option('urlshbym_delete_data_on_uninstall');
    delete_option('urlshbym_db_version');
    delete_option('rewrite_rules');
}

if (is_multisite()) {
    foreach (get_sites(['fields' => 'ids', 'number' => 0]) as $urlshbym_blog_id) {
        switch_to_blog($urlshbym_blog_id);
        urlshbym_uninstall_site();
        restore_current_blog();
    }
} else {
    urlshbym_uninstall_site();
}
