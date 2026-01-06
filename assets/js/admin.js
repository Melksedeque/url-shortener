/**
 * Admin JavaScript para WP URL Shortener
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        
        // Handler para geração em massa
        $('.wpus-generate-bulk').on('click', function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const type = $button.data('type');
            const name = $button.data('name');
            const $result = $('#wpus-bulk-result');
            
            // Previne múltiplos cliques
            if ($button.hasClass('loading')) {
                return;
            }
            
            // Adiciona estado de loading
            $button.addClass('loading').prop('disabled', true);
            $result.hide().removeClass('success error');
            
            // Faz requisição AJAX
            $.ajax({
                url: wpusAdmin.ajaxurl,
                type: 'POST',
                data: {
                    action: 'wpus_generate_bulk',
                    nonce: wpusAdmin.nonce,
                    type: type,
                    name: name
                },
                success: function(response) {
                    if (response.success) {
                        $result
                            .addClass('success')
                            .html('<strong>' + wpusAdmin.strings.success + '</strong><br>' + response.data.message)
                            .fadeIn();
                    } else {
                        $result
                            .addClass('error')
                            .html('<strong>' + wpusAdmin.strings.error + '</strong><br>' + response.data.message)
                            .fadeIn();
                    }
                },
                error: function() {
                    $result
                        .addClass('error')
                        .html('<strong>' + wpusAdmin.strings.error + '</strong><br>Erro ao processar a requisição.')
                        .fadeIn();
                },
                complete: function() {
                    $button.removeClass('loading').prop('disabled', false);
                    
                    // Auto-hide após 5 segundos
                    setTimeout(function() {
                        $result.fadeOut();
                    }, 5000);
                }
            });
        });
        
    });

})(jQuery);