jQuery( function( $ ) {

	var rk_btn_notificacao = function() {
		$( '.rk-send-user-file-notification' ).click(function( e ) {
			e.preventDefault();
			console.log( 'click: 3' );
			var btn = $( this );
			var btn_text = btn.find( '.rk-btn-text' );
			var original_btn_text = btn_text.text();
			btn_text.text( 'Enviando...' );
			btn.addClass( 'rk-btn-loading' ).attr( 'disabled', 'disabled' );
			$( '.rk-notification-status' ).remove();

			var data = {
				'action': 'rk_send_notification'
				// 'whatever': ajax_object.we_value      // We pass php values differently!
			};
			// We can also pass the url value separately from ajaxurl for front end AJAX implementations
			$.post( ajax_object.ajax_url, data, function( response ) {
				console.log( response.status );
				btn.removeClass( 'rk-btn-loading' ).removeAttr( 'disabled' );
				btn_text.text( original_btn_text );
				if( response.status === true ) {
					btn.after( '<p class="rk-notification-status rk-notification-status-success">Notificação enviada com sucesso!</p>' );
				} else {
					btn.after( '<p class="rk-notification-status rk-notification-status-error">Ocorreu um erro ao tentar enviar a notificação. Tente novamente.</p>' );
				}
			}, 'JSON' );

		}); // $(.rk-send-user-file-notification).click
	};

	$( document ).ready( function() {
		
		rk_btn_notificacao();

	}); // $(document).ready

});