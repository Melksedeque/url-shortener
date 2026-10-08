<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <div class="urlshbym-admin-container">
        
        <form method="post" action="">
	        <?php wp_nonce_field('urlshbym_settings_nonce'); ?>

        <!-- Seção de Configurações -->
        <div class="urlshbym-card">
            <h2><?php esc_html_e('Automatic Generation Settings', 'url-shortener-by-melk'); ?></h2>
            <p class="description">
                <?php esc_html_e('Select the content types that should have short URLs automatically generated when published.', 'url-shortener-by-melk'); ?>
            </p>

                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <?php esc_html_e('Post Types', 'url-shortener-by-melk'); ?>
                        </th>
                        <td>
                            <fieldset>
                                <?php foreach ($post_types as $post_type) : ?>
                                    <?php if ($post_type->name === 'attachment') continue; ?>
                                    <label>
						<input 
							type="checkbox" 
							name="urlshbym_enabled_post_types[]" 
                                            value="<?php echo esc_attr($post_type->name); ?>"
                                            <?php checked(in_array($post_type->name, $enabled_post_types)); ?>
                                        >
                                        <?php echo esc_html($post_type->label); ?>
                                        <span style="color: #666;">(<?php echo esc_html($post_type->name); ?>)</span>
                                    </label>
                                    <br>
                                <?php endforeach; ?>
                            </fieldset>
                            <p class="description">
                                <?php esc_html_e('Short URLs will be automatically created when new posts of these types are published.', 'url-shortener-by-melk'); ?>
                            </p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <?php esc_html_e('Taxonomies', 'url-shortener-by-melk'); ?>
                        </th>
                        <td>
                            <fieldset>
                                <?php foreach ($taxonomies as $taxonomy) : ?>
                                    <?php if (in_array($taxonomy->name, ['post_format', 'nav_menu'])) continue; ?>
                                    <label>
						<input 
							type="checkbox" 
							name="urlshbym_enabled_taxonomies[]" 
                                            value="<?php echo esc_attr($taxonomy->name); ?>"
                                            <?php checked(in_array($taxonomy->name, $enabled_taxonomies)); ?>
                                        >
                                        <?php echo esc_html($taxonomy->label); ?>
                                        <span style="color: #666;">(<?php echo esc_html($taxonomy->name); ?>)</span>
                                    </label>
                                    <br>
                                <?php endforeach; ?>
                            </fieldset>
                            <p class="description">
                                <?php esc_html_e('Short URLs will be automatically created when new terms of these taxonomies are created.', 'url-shortener-by-melk'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
        </div>

                <p class="submit">
			<input
				type="submit"
				name="urlshbym_save_settings"
                    class="button button-primary"
                    value="<?php esc_attr_e('Save Settings', 'url-shortener-by-melk'); ?>"
                >
            </p>
        </form>

        <!-- Seção de Geração Retroativa -->
        <div class="urlshbym-card">
            <h2><?php esc_html_e('Generate Short URLs for Existing Content', 'url-shortener-by-melk'); ?></h2>
            <p class="description">
                <?php esc_html_e('Use the buttons below to generate short URLs for all published content that does not yet have a short URL.', 'url-shortener-by-melk'); ?>
            </p>
            
            <div class="urlshbym-bulk-actions">
                <h3><?php esc_html_e('Post Types', 'url-shortener-by-melk'); ?></h3>
                <div class="urlshbym-button-group">
                    <?php foreach ($post_types as $post_type) : ?>
                        <?php if ($post_type->name === 'attachment') continue; ?>
                        <?php
                            $urlshbym_count = wp_count_posts($post_type->name);
                            $urlshbym_total = isset($urlshbym_count->publish) ? $urlshbym_count->publish : 0;
                        ?>
                        <button 
                            type="button" 
                            class="button urlshbym-generate-bulk" 
                            data-type="post_type" 
                            data-name="<?php echo esc_attr($post_type->name); ?>"
                            <?php echo $urlshbym_total === 0 ? 'disabled' : ''; ?>
                        >
                            <?php echo esc_html($post_type->label); ?>
                            <span class="count">(<?php echo esc_html($urlshbym_total); ?>)</span>
                        </button>
                    <?php endforeach; ?>
                </div>

                <h3><?php esc_html_e('Taxonomies', 'url-shortener-by-melk'); ?></h3>
                <div class="urlshbym-button-group">
                    <?php foreach ($taxonomies as $taxonomy) : ?>
                        <?php if (in_array($taxonomy->name, ['post_format', 'nav_menu'])) continue; ?>
                        <?php
                            $urlshbym_terms = get_terms(['taxonomy' => $taxonomy->name, 'hide_empty' => false]);
                            $urlshbym_total = is_array($urlshbym_terms) ? count($urlshbym_terms) : 0;
                        ?>
                        <button 
                            type="button" 
                            class="button urlshbym-generate-bulk" 
                            data-type="taxonomy" 
                            data-name="<?php echo esc_attr($taxonomy->name); ?>"
                            <?php echo $urlshbym_total === 0 ? 'disabled' : ''; ?>
                        >
                            <?php echo esc_html($taxonomy->label); ?>
                            <span class="count">(<?php echo esc_html($urlshbym_total); ?>)</span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <div id="urlshbym-bulk-result" style="display: none;"></div>
        </div>

        <!-- Seção de Informações -->
        <div class="urlshbym-card urlshbym-info-card">
            <h2><?php esc_html_e('How it Works', 'url-shortener-by-melk'); ?></h2>
            <ul>
                <li><?php esc_html_e('Short URLs are generated using Base62 (uppercase letters, lowercase letters, and numbers).', 'url-shortener-by-melk'); ?></li>
                <li><?php esc_html_e('Each short URL is 5-7 characters long and is based on the content ID, ensuring consistency.', 'url-shortener-by-melk'); ?></li>
                <li><?php esc_html_e('Short URLs appear at the root of your domain: example.com/abc123', 'url-shortener-by-melk'); ?></li>
                <li><?php esc_html_e('You can copy short URLs directly from the post and term listing tables.', 'url-shortener-by-melk'); ?></li>
                <li><?php esc_html_e('All redirects use 301 code (permanent) for SEO.', 'url-shortener-by-melk'); ?></li>
            </ul>
        </div>

        <!-- Danger Zone -->
        <div class="urlshbym-card urlshbym-danger-zone">
            <h2><?php esc_html_e('Danger Zone', 'url-shortener-by-melk'); ?></h2>

            <div class="urlshbym-danger-item">
                <div class="urlshbym-danger-text">
                    <strong><?php esc_html_e('Delete all short URLs', 'url-shortener-by-melk'); ?></strong>
                    <p class="description">
                        <?php esc_html_e('Removes every short URL created so far. Your settings are kept, and you can generate them again with the buttons above. Codes are based on the content ID, so regenerating creates the same codes in almost all cases.', 'url-shortener-by-melk'); ?>
                    </p>
                </div>
                <button type="button" id="urlshbym-delete-all" class="button urlshbym-button-danger">
                    <?php esc_html_e('Delete all short URLs', 'url-shortener-by-melk'); ?>
                </button>
            </div>

            <div id="urlshbym-delete-all-result" style="display: none;"></div>

            <form method="post" action="" class="urlshbym-danger-item">
                <?php wp_nonce_field('urlshbym_danger_nonce'); ?>
                <div class="urlshbym-danger-text">
                    <strong><?php esc_html_e('When the plugin is deleted', 'url-shortener-by-melk'); ?></strong>
                    <p>
                        <label>
                            <input
                                type="checkbox"
                                name="urlshbym_delete_data_on_uninstall"
                                value="1"
                                <?php checked(1, $delete_data_on_uninstall); ?>
                            >
                            <?php esc_html_e('Delete all plugin data (short URLs table, settings and stored codes)', 'url-shortener-by-melk'); ?>
                        </label>
                    </p>
                    <p class="description">
                        <?php esc_html_e('Only takes effect when you delete the plugin from the Plugins screen. Leave unchecked to keep your data if you reinstall the plugin.', 'url-shortener-by-melk'); ?>
                    </p>
                </div>
                <input
                    type="submit"
                    name="urlshbym_save_danger"
                    class="button"
                    value="<?php esc_attr_e('Save', 'url-shortener-by-melk'); ?>"
                >
            </form>
        </div>

    </div>
</div>
