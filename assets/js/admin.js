/**
 * Admin JavaScript para URL Shortener By Melk
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        
		// Handler para geração em massa
		$('.urlshbym-generate-bulk').on('click', function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const type = $button.data('type');
            const name = $button.data('name');
            const $result = $('#urlshbym-bulk-result');
            
            // Previne múltiplos cliques
            if ($button.hasClass('loading')) {
                return;
            }
            
            // Adiciona estado de loading
            $button.addClass('loading').prop('disabled', true);
            $result.hide().removeClass('success error');
            
				// Faz requisição AJAX
				$.ajax({
					url: urlshbymAdmin.ajaxurl,
                type: 'POST',
					data: {
						action: 'urlshbym_generate_bulk',
						nonce: urlshbymAdmin.nonce,
                    type: type,
                    name: name
                },
					success: function(response) {
						if (response.success) {
							$result
								.addClass('success')
								.html('<strong>' + urlshbymAdmin.strings.success + '</strong><br>' + response.data.message)
                            .fadeIn();
						} else {
							$result
								.addClass('error')
								.html('<strong>' + urlshbymAdmin.strings.error + '</strong><br>' + response.data.message)
                            .fadeIn();
                    }
                },
					error: function() {
						$result
							.addClass('error')
							.html('<strong>' + urlshbymAdmin.strings.error + '</strong><br>' + urlshbymAdmin.strings.requestError)
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
        
        // Danger Zone: apagar todas as URLs curtas
        $('#urlshbym-delete-all').on('click', function(e) {
            e.preventDefault();

            const $button = $(this);
            const $result = $('#urlshbym-delete-all-result');

            if ($button.prop('disabled') || !window.confirm(urlshbymAdmin.strings.confirmDelete)) {
                return;
            }

            $button.prop('disabled', true);
            $result.hide().removeClass('success error');

            $.ajax({
                url: urlshbymAdmin.ajaxurl,
                type: 'POST',
                data: {
                    action: 'urlshbym_delete_all',
                    nonce: urlshbymAdmin.deleteNonce
                },
                success: function(response) {
                    if (response.success) {
                        $result
                            .addClass('success')
                            .html('<strong>' + urlshbymAdmin.strings.deleted + '</strong><br>' + response.data.message)
                            .fadeIn();
                    } else {
                        $result
                            .addClass('error')
                            .html('<strong>' + urlshbymAdmin.strings.deleteError + '</strong><br>' + response.data.message)
                            .fadeIn();
                    }
                },
                error: function() {
                    $result
                        .addClass('error')
                        .html('<strong>' + urlshbymAdmin.strings.deleteError + '</strong><br>' + urlshbymAdmin.strings.requestError)
                        .fadeIn();
                },
                complete: function() {
                    $button.prop('disabled', false);
                }
            });
        });

    });

})(jQuery);
