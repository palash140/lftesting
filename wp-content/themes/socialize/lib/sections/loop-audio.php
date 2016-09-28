<div class="gp-post-format-audio-content gp-image-above">

	<?php 
	
	$gp_protocol = ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' OR $_SERVER['SERVER_PORT'] == 443 ) ? 'https:' : 'http:';
	
	$gp_mp3 = '';
	$gp_ogg = '';

	if ( get_post_meta( get_the_ID(), 'audio_mp3_url', true ) ) {
		$gp_mp3 = get_post_meta( get_the_ID(), 'audio_mp3_url', true );
		$gp_mp3 = $gp_mp3['url'];
		$gp_mp3 = $gp_protocol . str_replace( array( 'http:', 'https:' ), '', $gp_mp3 );
	}

	if ( get_post_meta( get_the_ID(), 'audio_ogg_url', true ) ) {
		$gp_ogg = get_post_meta( get_the_ID(), 'audio_ogg_url', true );
		$gp_ogg = $gp_ogg['url'];
		$gp_ogg = $gp_protocol . str_replace( array( 'http:', 'https:' ), '', $gp_ogg );
	}
			
	echo do_shortcode( '[audio mp3="' . esc_url( $gp_mp3 ) . '" ogg="' . esc_url( $gp_ogg ) . '"][/audio]' ); ?>
	
</div>