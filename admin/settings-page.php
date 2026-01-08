<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    
    <div class="wpus-admin-container">
        
        <!-- Seção de Configurações -->
        <div class="wpus-card">
            <h2><?php esc_html_e('Configurações de Geração Automática', 'url-shortener-by-melk'); ?></h2>
            <p class="description">
                <?php esc_html_e('Selecione os tipos de conteúdo que devem ter URLs curtas geradas automaticamente ao serem publicados.', 'url-shortener-by-melk'); ?>
            </p>
            
            <form method="post" action="">
                <?php wp_nonce_field('wpus_settings_nonce'); ?>
                
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
                                            name="wpus_enabled_post_types[]" 
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
                                <?php esc_html_e('URLs curtas serão criadas automaticamente quando novos posts desses tipos forem publicados.', 'url-shortener-by-melk'); ?>
                            </p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <?php esc_html_e('Taxonomias', 'url-shortener-by-melk'); ?>
                        </th>
                        <td>
                            <fieldset>
                                <?php foreach ($taxonomies as $taxonomy) : ?>
                                    <?php if (in_array($taxonomy->name, ['post_format', 'nav_menu'])) continue; ?>
                                    <label>
                                        <input 
                                            type="checkbox" 
                                            name="wpus_enabled_taxonomies[]" 
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
                                <?php esc_html_e('URLs curtas serão criadas automaticamente quando novos termos dessas taxonomias forem criados.', 'url-shortener-by-melk'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <input 
                        type="submit" 
                        name="wpus_save_settings" 
                        class="button button-primary" 
                        value="<?php esc_attr_e('Salvar Configurações', 'url-shortener-by-melk'); ?>"
                    >
                </p>
            </form>
        </div>

        <!-- Seção de Geração Retroativa -->
        <div class="wpus-card">
            <h2><?php esc_html_e('Gerar URLs Curtas para Conteúdo Existente', 'url-shortener-by-melk'); ?></h2>
            <p class="description">
                <?php esc_html_e('Use os botões abaixo para gerar URLs curtas para todo o conteúdo já publicado que ainda não possui uma URL curta.', 'url-shortener-by-melk'); ?>
            </p>
            
            <div class="wpus-bulk-actions">
                <h3><?php esc_html_e('Post Types', 'url-shortener-by-melk'); ?></h3>
                <div class="wpus-button-group">
                    <?php foreach ($post_types as $post_type) : ?>
                        <?php if ($post_type->name === 'attachment') continue; ?>
                        <?php
                            $wpus_count = wp_count_posts($post_type->name);
                            $wpus_total = isset($wpus_count->publish) ? $wpus_count->publish : 0;
                        ?>
                        <button 
                            type="button" 
                            class="button wpus-generate-bulk" 
                            data-type="post_type" 
                            data-name="<?php echo esc_attr($post_type->name); ?>"
                            <?php echo $wpus_total === 0 ? 'disabled' : ''; ?>
                        >
                            <?php echo esc_html($post_type->label); ?>
                            <span class="count">(<?php echo esc_html($wpus_total); ?>)</span>
                        </button>
                    <?php endforeach; ?>
                </div>

                <h3><?php esc_html_e('Taxonomias', 'url-shortener-by-melk'); ?></h3>
                <div class="wpus-button-group">
                    <?php foreach ($taxonomies as $taxonomy) : ?>
                        <?php if (in_array($taxonomy->name, ['post_format', 'nav_menu'])) continue; ?>
                        <?php
                            $wpus_terms = get_terms(['taxonomy' => $taxonomy->name, 'hide_empty' => false]);
                            $wpus_total = is_array($wpus_terms) ? count($wpus_terms) : 0;
                        ?>
                        <button 
                            type="button" 
                            class="button wpus-generate-bulk" 
                            data-type="taxonomy" 
                            data-name="<?php echo esc_attr($taxonomy->name); ?>"
                            <?php echo $wpus_total === 0 ? 'disabled' : ''; ?>
                        >
                            <?php echo esc_html($taxonomy->label); ?>
                            <span class="count">(<?php echo esc_html($wpus_total); ?>)</span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <div id="wpus-bulk-result" style="display: none;"></div>
        </div>

        <!-- Seção de Informações -->
        <div class="wpus-card wpus-info-card">
            <h2><?php esc_html_e('Como Funciona', 'url-shortener-by-melk'); ?></h2>
            <ul>
                <li><?php esc_html_e('URLs curtas são geradas usando Base62 (letras maiúsculas, minúsculas e números).', 'url-shortener-by-melk'); ?></li>
                <li><?php esc_html_e('Cada URL curta tem entre 5-7 caracteres e é baseada no ID do conteúdo, garantindo consistência.', 'url-shortener-by-melk'); ?></li>
                <li><?php esc_html_e('As URLs curtas aparecem na raiz do seu domínio: exemplo.com.br/abc123', 'url-shortener-by-melk'); ?></li>
                <li><?php esc_html_e('Você pode copiar URLs curtas diretamente das tabelas de listagem de posts e termos.', 'url-shortener-by-melk'); ?></li>
                <li><?php esc_html_e('Todos os redirecionamentos usam código 301 (permanente) para SEO.', 'url-shortener-by-melk'); ?></li>
            </ul>
        </div>

    </div>
</div>