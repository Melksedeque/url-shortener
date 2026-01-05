/**
 * Columns JavaScript para URL Shortener
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        
        // Handler para copiar URL
        $(document).on('click', '.wpus-copy-btn', function(e) {
            e.preventDefault();
            
            const $button = $(this);
            const url = $button.data('url');
            const $message = $button.siblings('.wpus-copied-message');
            
            // Tenta copiar usando a API moderna
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(url).then(function() {
                    showCopiedMessage($button, $message);
                }).catch(function(err) {
                    console.error('Erro ao copiar:', err);
                    fallbackCopyText(url);
                    showCopiedMessage($button, $message);
                });
            } else {
                // Fallback para navegadores antigos
                fallbackCopyText(url);
                showCopiedMessage($button, $message);
            }
        });
        
        /**
         * Mostra mensagem de copiado
         */
        function showCopiedMessage($button, $message) {
            const originalTitle = $button.attr('title');
            
            $message.fadeIn(200);
            $button.addClass('copied');
            
            setTimeout(function() {
                $message.fadeOut(200);
                $button.removeClass('copied');
            }, 2000);
        }
        
        /**
         * Fallback para copiar texto em navegadores antigos
         */
        function fallbackCopyText(text) {
            const $temp = $('<textarea>');
            $('body').append($temp);
            $temp.val(text).select();
            
            try {
                document.execCommand('copy');
            } catch (err) {
                console.error('Erro ao copiar:', err);
            }
            
            $temp.remove();
        }
        
    });

})(jQuery);