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
            <h2><?php _e('Configurações de Geração Automática', 'wp-url-shortener'); ?></h2>
            <p class="description">
                <?php _e('Selecione os tipos de conteúdo que devem ter URLs curtas geradas automaticamente ao serem publicados.', 'wp-url-shortener'); ?>
            </p>
            
            <form method="post" action="">
                <?php wp_nonce_field('wpus_settings_nonce'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <?php _e('Post Types', 'wp-url-shortener'); ?>
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
                                <?php _e('URLs curtas serão criadas automaticamente quando novos posts desses tipos forem publicados.', 'wp-url-shortener'); ?>
                            </p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <?php _e('Taxonomias', 'wp-url-shortener'); ?>
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
                                <?php _e('URLs curtas serão criadas automaticamente quando novos termos dessas taxonomias forem criados.', 'wp-url-shortener'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <input 
                        type="submit" 
                        name="wpus_save_settings" 
                        class="button button-primary" 
                        value="<?php esc_attr_e('Salvar Configurações', 'wp-url-shortener'); ?>"
                    >
                </p>
            </form>
        </div>

        <!-- Seção de Geração Retroativa -->
        <div class="wpus-card">
            <h2><?php _e('Gerar URLs Curtas para Conteúdo Existente', 'wp-url-shortener'); ?></h2>
            <p class="description">
                <?php _e('Use os botões abaixo para gerar URLs curtas para todo o conteúdo já publicado que ainda não possui uma URL curta.', 'wp-url-shortener'); ?>
            </p>
            
            <div class="wpus-bulk-actions">
                <h3><?php _e('Post Types', 'wp-url-shortener'); ?></h3>
                <div class="wpus-button-group">
                    <?php foreach ($post_types as $post_type) : ?>
                        <?php if ($post_type->name === 'attachment') continue; ?>
                        <?php
                            $count = wp_count_posts($post_type->name);
                            $total = isset($count->publish) ? $count->publish : 0;
                        ?>
                        <button 
                            type="button" 
                            class="button wpus-generate-bulk" 
                            data-type="post_type" 
                            data-name="<?php echo esc_attr($post_type->name); ?>"
                            <?php echo $total === 0 ? 'disabled' : ''; ?>
                        >
                            <?php echo esc_html($post_type->label); ?>
                            <span class="count">(<?php echo esc_html($total); ?>)</span>
                        </button>
                    <?php endforeach; ?>
                </div>

                <h3><?php _e('Taxonomias', 'wp-url-shortener'); ?></h3>
                <div class="wpus-button-group">
                    <?php foreach ($taxonomies as $taxonomy) : ?>
                        <?php if (in_array($taxonomy->name, ['post_format', 'nav_menu'])) continue; ?>
                        <?php
                            $terms = get_terms(['taxonomy' => $taxonomy->name, 'hide_empty' => false]);
                            $total = is_array($terms) ? count($terms) : 0;
                        ?>
                        <button 
                            type="button" 
                            class="button wpus-generate-bulk" 
                            data-type="taxonomy" 
                            data-name="<?php echo esc_attr($taxonomy->name); ?>"
                            <?php echo $total === 0 ? 'disabled' : ''; ?>
                        >
                            <?php echo esc_html($taxonomy->label); ?>
                            <span class="count">(<?php echo esc_html($total); ?>)</span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <div id="wpus-bulk-result" style="display: none;"></div>
        </div>

        <!-- Seção de Informações -->
        <div class="wpus-card wpus-info-card">
            <h2><?php _e('Como Funciona', 'wp-url-shortener'); ?></h2>
            <ul>
                <li><?php _e('URLs curtas são geradas usando Base62 (letras maiúsculas, minúsculas e números).', 'wp-url-shortener'); ?></li>
                <li><?php _e('Cada URL curta tem entre 5-7 caracteres e é baseada no ID do conteúdo, garantindo consistência.', 'wp-url-shortener'); ?></li>
                <li><?php _e('As URLs curtas aparecem na raiz do seu domínio: exemplo.com.br/abc123', 'wp-url-shortener'); ?></li>
                <li><?php _e('Você pode copiar URLs curtas diretamente das tabelas de listagem de posts e termos.', 'wp-url-shortener'); ?></li>
                <li><?php _e('Todos os redirecionamentos usam código 301 (permanente) para SEO.', 'wp-url-shortener'); ?></li>
            </ul>
        </div>

    </div>
</div>