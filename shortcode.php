<?php

add_shortcode( 'rk_arquivos', 'rk_user_arquivos' );

function rk_user_arquivos() {

	$user_id = get_current_user_id();
	
	if( !$user_id )
		return __( 'Necessário estar logado para visualizar o conteúdo desta página.', 'rk' );

	$output = '';
	$user_files = get_user_meta( $user_id, 'rk_arquivos', false );

	if( !$user_files )
		return __( 'Nenhum arquivo disponível para download no momento.', 'rk' );

		$output .= '<ul>';

		foreach( $user_files as $file ) :
			foreach( $file as $file_id => $file_url ) :
				$file_name = get_the_title( $file_id );
				$output .= '<li><a href="' . $file_url . '" target="_BLANK">' . $file_name . '</a></li>';
			endforeach;
		endforeach;
		$output .= '</ul>';

	return $output;
}