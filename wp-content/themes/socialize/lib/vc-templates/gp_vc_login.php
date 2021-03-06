<?php

/*--------------------------------------------------------------
Login
--------------------------------------------------------------*/

if ( ! function_exists( 'ghostpool_login' ) ) {

	function ghostpool_login( $atts, $content = null ) {
	
		extract( shortcode_atts( array(
			'widget_title' => '',
			'default_view' => 'gp-default-view-login',
			'classes' => '',
			'title_format' => 'gp-standard-title',
			'title_color' => '',	
			'icon' => '',
		), $atts ) );
		
		global $socialize;
	
		// Unique Name	
		STATIC $gp_i = 0;
		$gp_i++;
		$gp_name = 'gp_login_wrapper_' . $gp_i;
		
		ob_start(); ?>
		
		<?php if ( ! is_user_logged_in() ) { ?>		
			
			<div id="<?php echo sanitize_html_class( $gp_name ); ?>" class="gp-login-wrapper gp-vc-element <?php echo esc_attr( $default_view ); ?> <?php echo esc_attr( $classes ); ?>">
					
				<?php if ( $widget_title ) { ?>
					<h3 class="widgettitle <?php echo $title_format; ?>"<?php if ( $title_color ) { ?> style="background-color: <?php echo esc_attr( $title_color ); ?>; border-color: <?php echo esc_attr( $title_color ); ?>"<?php } ?>>
						<?php if ( $icon ) { ?><i class="gp-element-icon fa <?php echo sanitize_html_class( $icon ); ?>"></i><?php } ?>
						<span class="gp-widget-title"><?php echo esc_attr( $widget_title ); ?></span>
						<div class="gp-triangle"></div>
					</h3>
				<?php } ?>
				
				<?php if ( isset( $socialize['popup_box'] ) && $socialize['popup_box'] == 'disabled' ) { ?>
			
					<strong><?php esc_html_e( 'Please enable "Login/Register Popup Windows" from Theme Options -> General to use the Login/Register element.', 'socialize' ); ?></strong>
							
				<?php } else { ?>

					<div class="gp-login-form-wrapper">

						<form name="loginform" class="gp-login-form" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" method="post">

							<p class="username"><span class="gp-login-icon"></span><input type="text" name="log" class="user_login" value="<?php if ( ! empty( $user_login ) ) { echo esc_attr( stripslashes( $user_login ), 1 ); } ?>" size="20" placeholder="<?php esc_attr_e( 'Username', 'socialize' ); ?>" /></p>

							<p class="password"><span class="gp-password-icon"></span><input type="password" name="pwd" class="user_pass" size="20" placeholder="<?php esc_attr_e( 'Password', 'socialize' ); ?>" /></p>

							<p class="gp-lost-password-link"><a href="#" class="gp-switch-to-lost-password"><?php esc_html_e( 'Forgot your password?', 'socialize' ); ?></a></p>
		
							<?php echo apply_filters( 'cptch_display', '' ); ?>
					
							<p class="gp-login-results"></p>

							<p><input type="submit" name="wp-submit" class="wp-submit" value="<?php esc_attr_e( 'Login', 'socialize' ); ?>" /></p>
				
							<p class="rememberme"><input name="rememberme" class="rememberme" type="checkbox" checked="checked" value="forever" /> <?php esc_html_e( 'Remember Me', 'socialize' ); ?></p>
					
							<?php if ( get_option( 'users_can_register' ) ) { ?>
								<p class="gp-register-link"><?php esc_html_e( 'No account?', 'socialize' ); ?> <a href="<?php if ( function_exists( 'bp_is_active' ) ) { echo esc_url( bp_get_signup_page( false ) ); } else { echo '#'; } ?>" class="gp-switch-to-register"><?php esc_html_e( 'Sign up', 'socialize' ); ?></a></p>
							<?php } ?>
					
							<?php if ( has_action ( 'wordpress_social_login' ) ) { ?>
								<div class="gp-social-login">
									<?php do_action( 'wordpress_social_login' ); ?>
								</div>
							<?php } ?>

							<input type="hidden" name="action" value="ghostpool_login" />
	
						</form>
	
					</div>
				
				
					<div class="gp-lost-password-form-wrapper">
			
						<form name="lostpasswordform" class="gp-lost-password-form" action="#" method="post">
		
							<p><?php esc_html_e( 'Please enter your username or email address. You will receive a link to create a new password via email.', 'socialize' ); ?></p>	
			
							<p><span class="gp-login-icon"></span><input type="text" name="user_login" class="user_login" value="" size="20" placeholder="<?php esc_attr_e('Username or Email', 'socialize' ); ?>" /></p>

							<p class="gp-login-results"></p>

							<p><input type="submit" name="wp-submit" class="wp-submit" value="<?php esc_attr_e('Reset Password', 'socialize' ); ?>" /></p>
					
							<p class="gp-login-link"><?php esc_html_e( 'Already have an account?', 'socialize' ); ?> <a href="#" class="gp-switch-to-login"><?php esc_html_e( 'Login instead', 'socialize' ); ?></a></p>
	
							<input type="hidden" name="action" value="ghostpool_lost_password" />
							<input type="hidden" name="gp_pwd_nonce" value="<?php echo wp_create_nonce("gp_pwd_nonce"); ?>" />
						
						</form>

					</div>
				

					<?php if ( get_option( 'users_can_register' ) && ! function_exists( 'bp_is_active' ) ) { ?>
		
						<div class="gp-register-form-wrapper">

							<form name="registerform" class="gp-register-form" action="<?php echo esc_url( site_url( 'wp-login.php?action=register', 'login_post' ) ); ?>" method="post">

								<p class="user_login"><span class="gp-login-icon"></span><input type="text" name="user_login" class="user_login" value="<?php if ( ! empty( $user_login ) ) { echo esc_attr( stripslashes( $user_login ), 1 ); } ?>" size="20" placeholder="<?php esc_attr_e( 'Username', 'socialize' ); ?>" /></p>
	
								<p class="user_email"><span class="gp-email-icon"></span><input type="email" name="user_email" class="user_email" size="25" placeholder="<?php esc_attr_e( 'Email', 'socialize' ); ?>" /></p>
							
								<p class="user_confirm_pass"><span class="gp-password-icon"></span><input type="password" name="user_confirm_pass" class="user_confirm_pass" size="25" placeholder="<?php esc_attr_e( 'Password', 'socialize' ); ?>" /></p>
							
								<p class="user_pass"><span class="gp-password-icon"></span><input type="password" name="user_pass" class="user_pass" size="25" placeholder="<?php esc_attr_e( 'Confirm Password', 'socialize' ); ?>" /></p>
							
								<?php echo apply_filters( 'cptch_display', '' ); ?>
							
								<p class="gp-login-results"></p>
		
								<p><input type="submit" name="wp-submit" class="wp-submit" value="<?php esc_attr_e( 'Sign Up', 'socialize' ); ?>" /></p>
					
								<p class="gp-login-link"><?php esc_html_e( 'Already have an account?', 'socialize' ); ?> <a href="#" class="gp-switch-to-login"><?php esc_html_e( 'Login instead', 'socialize' ); ?></a></p>
					
								<input type="hidden" name="action" value="ghostpool_register" />
			
							</form>
			
						</div>
				
					<?php } ?>	
				
		
					<script>  	

					jQuery( document ).ready( function( $ ) {							

						'use strict';

						$( '#<?php echo sanitize_html_class( $gp_name ); ?> .gp-login-form' ).submit( function() {
							var loginform = $( this ); 
							loginform.find( '.gp-login-results' ).html('<span class="gp-verify-form"><?php esc_html_e( "Verifying...", "socialize" ); ?></span>').fadeIn();
							var input_data = loginform.serialize();
							$.ajax({
								type: "POST",
								<?php if ( is_ssl() ) { ?>
									url:  "<?php echo esc_url( 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ); ?>",
								<?php } else { ?>
									url:  "<?php echo esc_url( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ); ?>",
								<?php } ?>	
								data: input_data,
								success: function(msg) {
									loginform.find( '.gp-verify-form' ).remove();
									$( '<span>' ).html( msg ).appendTo( loginform.find( '.gp-login-results' ) ).hide().fadeIn( 'slow' );
								},
								error: function(xhr, status, error) {
									loginform.find( '.gp-verify-form' ).remove();
									$( '<span>' ).html( xhr.responseText ).appendTo( loginform.find( '.gp-login-results' ) ).hide().fadeIn( 'slow' );
								}
							});
							return false;
						});
	
						$( '#<?php echo sanitize_html_class( $gp_name ); ?> .gp-lost-password-form' ).submit( function() {
							var lostpasswordform = $( this ); 
							lostpasswordform.find( '.gp-login-results' ).html( '<span class="gp-verify-form"><?php esc_html_e( "Verifying...", "socialize" ); ?></span>' ).fadeIn();
							var input_data = lostpasswordform.serialize();
							$.ajax({
								type: "POST",
								<?php if ( is_ssl() ) { ?>
									url:  "<?php echo esc_url( 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ); ?>",
								<?php } else { ?>
									url:  "<?php echo esc_url( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ); ?>",
								<?php } ?>
								data: input_data,
								success: function(msg) {
									lostpasswordform.find( '.gp-verify-form' ).remove();
									$( '<span>' ).html( msg ).appendTo( lostpasswordform.find( '.gp-login-results' ) ).hide().fadeIn( 'slow' );
								}
							});
							return false;
						});
		
						<?php if ( get_option( 'users_can_register' ) && ! function_exists( 'bp_is_active' ) ) { ?>
							$( '#<?php echo sanitize_html_class( $gp_name ); ?> .gp-register-form' ).submit( function() {
								var registerform = $( this ); 
								registerform.find( '.gp-login-results' ).html( '<span class="gp-verify-form"><?php esc_html_e( "Verifying...", "socialize" ); ?></span>' ).fadeIn();
								var input_data = registerform.serialize();
								$.ajax({
									type: "POST",
									<?php if ( is_ssl() ) { ?>
										url:  "<?php echo esc_url( 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ); ?>",
									<?php } else { ?>
										url:  "<?php echo esc_url( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] ); ?>",
									<?php } ?>	
									data: input_data,
									success: function(msg) {
										registerform.find( '.gp-verify-form' ).remove();
										$( '<span>' ).html( msg ).appendTo( registerform.find( '.gp-login-results' ) ).hide().fadeIn( 'slow' );
										if ( ! registerform.find( '.gp-login-results > span > span' ).hasClass( 'error' ) ) {						
											registerform.find( 'input[type="text"]' ).val( '' );
											registerform.find( 'p:not(.gp-login-results)' ).hide();
										} 
									}
								});
								return false;
							});
						<?php } ?>

						// Open login modal window when clicking links
						$( '#<?php echo sanitize_html_class( $gp_name ); ?> .gp-switch-to-login, a[href="#login"]' ).click( function() {
							$( '#<?php echo sanitize_html_class( $gp_name ); ?> .gp-login-form-wrapper' ).show();
							$( '#<?php echo sanitize_html_class( $gp_name ); ?> .gp-register-form-wrapper, #<?php echo sanitize_html_class( $gp_name ); ?> .gp-lost-password-form-wrapper' ).hide();
							$( '#<?php echo sanitize_html_class( $gp_name ); ?> .gp-login-results > span' ).remove();
							return false;
						});		

						// Open login modal window directly from URL	
						if ( /#login/.test( window.location.href ) ) {
							$( '#<?php echo sanitize_html_class( $gp_name ); ?> .gp-login-form-wrapper' ).show();
							$( '#<?php echo sanitize_html_class( $gp_name ); ?> .gp-register-form-wrapper, #<?php echo sanitize_html_class( $gp_name ); ?> .gp-lost-password-form-wrapper' ).hide();
							$( '#<?php echo sanitize_html_class( $gp_name ); ?> .gp-login-results > span' ).remove();
						}
							
						// Open lost password modal window when clicking link	
						$( '#<?php echo sanitize_html_class( $gp_name ); ?> .gp-switch-to-lost-password' ).click( function() {
							$( '#<?php echo sanitize_html_class( $gp_name ); ?> .gp-lost-password-form-wrapper' ).show();
							$( '#<?php echo sanitize_html_class( $gp_name ); ?> .gp-register-form-wrapper, #<?php echo sanitize_html_class( $gp_name ); ?> .gp-login-form-wrapper' ).hide();
							$( '#<?php echo sanitize_html_class( $gp_name ); ?> .gp-login-results > span' ).remove();
							return false;
						});

						// Open lost password modal window directly from URL
						if ( /#lost-password/.test( window.location.href ) ) {
							$( '#<?php echo sanitize_html_class( $gp_name ); ?> .gp-lost-password-form-wrapper' ).show();
							$( '#<?php echo sanitize_html_class( $gp_name ); ?> .gp-register-form-wrapper, #<?php echo sanitize_html_class( $gp_name ); ?> .gp-login-form-wrapper' ).hide();
							$( '#<?php echo sanitize_html_class( $gp_name ); ?> .gp-login-results > span' ).remove();
						}
					
						<?php if ( get_option( 'users_can_register' ) && ! function_exists( 'bp_is_active' ) ) { ?>
					
							// Open registration modal window when clicking links
							$( '#<?php echo sanitize_html_class( $gp_name ); ?> .gp-switch-to-register, a[href="#register"]' ).click( function() {
								$( '#<?php echo sanitize_html_class( $gp_name ); ?> .gp-register-form-wrapper, #<?php echo sanitize_html_class( $gp_name ); ?> .gp-register-form p' ).show();
								$( '#<?php echo sanitize_html_class( $gp_name ); ?> .gp-register-form p > input[type="text"]' ).val( '' );
								$( '#<?php echo sanitize_html_class( $gp_name ); ?> .gp-login-form-wrapper, #<?php echo sanitize_html_class( $gp_name ); ?> .gp-lost-password-form-wrapper' ).hide();
								$( '#<?php echo sanitize_html_class( $gp_name ); ?> .gp-login-results > span' ).remove();
								return false;
							});

							// Open registration modal window directly from URL
							if ( /#register/.test( window.location.href ) ) {
								$( '#<?php echo sanitize_html_class( $gp_name ); ?> .gp-register-form-wrapper, #<?php echo sanitize_html_class( $gp_name ); ?> .gp-register-form p' ).show();
								$( '#<?php echo sanitize_html_class( $gp_name ); ?> .gp-register-form p > input[type="text"]' ).val( '' );
								$( '#<?php echo sanitize_html_class( $gp_name ); ?> .gp-login-form-wrapper, #<?php echo sanitize_html_class( $gp_name ); ?> .gp-lost-password-form-wrapper' ).hide();
								$( '#<?php echo sanitize_html_class( $gp_name ); ?> .gp-login-results > span' ).remove()
							}
									
						<?php } ?>

					});
	
					</script>
					
				<?php } ?>	
	
			</div>
							
		<?php } ?>	
							
		<?php

		$gp_output_string = ob_get_contents();
		ob_end_clean();
		return $gp_output_string;

	}

}
add_shortcode( 'login', 'ghostpool_login' );

?>