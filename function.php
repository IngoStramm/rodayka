<?php

function rk_debug( $debug ) {
	echo '<pre>';
	var_dump( $debug );
	echo '</pre>';
}

add_action( 'wp_mail_failed', 'rk_mail_error', 10, 1 );

function rk_mail_error( $wp_error ) {
	rk_debug( $wp_error );
}

add_action( 'wp_ajax_rk_send_notification', 'rk_send_notification' );

function rk_send_notification() {

	$user = wp_get_current_user();;

	$to = $user->user_email;
	$subject = 'Arquivos disponíveis para download';
	$body = '
		<h3>Olá, ' . $user->first_name . '!</h3>
		<p>Existem arquivos disponíveis para download. Acesse <a href="https://rodaykasantana.com.br/arquivos-para-download/" target="_BLANK">este link</a> para visualizá-los.</p>
	';
	$headers = array( 'Content-Type: text/html; charset=UTF-8' );
	$headers[] = 'From: Rodayka <contato@rodaykasantana.com.br>';
	$headers[] .= 'Bcc: ingo@agencialaf.com';
	 
	$send_email = wp_mail( $to, $subject, $body, $headers );

	echo json_encode( array( 'status' => $send_email ) );

	wp_die();
}

add_action( 'woocommerce_order_details_after_order_table', 'rk_order', 10 );

function rk_order( $order ) {
	// rk_debug( rk_return_product_quiz_options() );
	// rk_debug( $item );
	// rk_debug( $order->get_order_item_totals() );
	$products = $order->get_items();

	$output = '';
	$output .= '<ul class="rk-quiz-list">';
	$check_page_id = [];
	foreach( $products as $product ) :
		$product_id = $product->get_product_id();
		$pages = get_post_meta( $product_id, 'rk_quizzes', true );
		// rk_debug( $forms );
		if( $pages ) :

			foreach( $pages as $page_id ) :

				if( !in_array( $page_id, $check_page_id ) ) :

					$output .= '<li><a class="btn button" target="_blank" href="' . get_page_link( $page_id ) . '?order=' . $order->get_id() . '">' . get_the_title( $page_id ) . '</a></li>';
					$check_page_id[] = $page_id;
				endif;

			endforeach;

		endif;

	endforeach;
	$output .= '</ul>';

	echo $output;
}

function rk_return_pages() {
	$pages = get_pages( array( '' ) );
	$output = [];

	foreach( $pages as $page ) :
		$output[ $page->ID ] = $page->post_title;
	endforeach;

	return $output;
}

function rk_return_product_quiz_options() {
	$output	= [];

	if( rk_get_option( 'rk_book_estilos_page' ) )
		$output[ rk_get_option( 'rk_book_estilos_page' ) ] = esc_html__( 'Book de Estilos', 'rk' );
	
	if( rk_get_option( 'rk_quiz_estilos_page' ) )
		$output[ rk_get_option( 'rk_quiz_estilos_page' ) ] = esc_html__( 'Quiz de Estilos', 'rk' );
	
	if( rk_get_option( 'rk_envio_fotos_page' ) )
		$output[ rk_get_option( 'rk_envio_fotos_page' ) ] = esc_html__( 'Envio de fotos', 'rk' );
	
	if( rk_get_option( 'rk_envio_medidas_page' ) )
		$output[ rk_get_option( 'rk_envio_medidas_page' ) ] = esc_html__( 'Envio de medidas', 'rk' );

	return $output;

}