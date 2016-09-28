<?php if ( ! is_user_logged_in() ) { global $socialize; ?>

	<div id="login">
		
		<div id="gp-login-modal">
			
			<div class="gp-login-close"></div>
			
			<h3 class="gp-login-title"><?php esc_html_e( 'Login', 'socialize' ); ?></h3>
			<h3 class="gp-lost-password-title"><?php esc_html_e( 'Lost Password', 'socialize' ); ?></h3>
			<h3 class="gp-register-title"><?php esc_html_e( 'Register', 'socialize' ); ?></h3>

			<?php echo do_shortcode( '[login]' ); ?>
		
		</div>
			
	</div>
				
	<script>
	jQuery( document ).ready(function( $ ) {							

		'use strict';	
		
		// Close modal window when clicking outside of it		
		$( document ).mouseup(function(e) {
			var container = $( '#gp-login-modal' );
			if ( ! container.is( e.target ) && container.has( e.target ).length === 0) {
				$( '#login' ).hide();
			}
		});

		// Close modal window when clicking close button
		$( '.gp-login-close' ).click(function() {
			$( '#login' ).hide();
		});
		
		// Open login modal window when clicking links
		$( '.gp-switch-to-login, a[href="#login"]' ).click(function() {
			$( '#login' ).show();
			$( '.gp-login-title' ).show();
			$( '.gp-lost-password-title' ).hide();
			$( '.gp-register-title' ).hide();
		});
			
		// Open login modal window directly from URL	
		if ( /#login/.test( window.location.href ) ) {
			$( '#login' ).show();
			$( '.gp-login-title' ).show();
			$( '.gp-lost-password-title' ).hide();
			$( '.gp-register-title' ).hide();
		}
		
		// Open lost password modal window when clicking link	
		$( '.gp-switch-to-lost-password' ).click(function() {
			$( '.gp-login-title' ).hide();
			$( '.gp-lost-password-title' ).show();
			$( '.gp-register-title' ).hide();
		});
	
		// Open lost password modal window directly from URL
		if ( /#lost-password/.test( window.location.href ) ) {
			$( '#login' ).show();
			$( '.gp-login-title' ).hide();
			$( '.gp-lost-password-title' ).show();
			$( '.gp-register-title' ).hide();
		}
			
		<?php if ( get_option( 'users_can_register' ) && ! function_exists( 'bp_is_active' ) ) { ?>

			// Open registration modal window when clicking links
			$( '.gp-switch-to-register, a[href="#register"]' ).click(function() {
				$( '#login' ).show();
				$( '.gp-login-title' ).hide();
				$( '.gp-lost-password-title' ).hide();
				$( '.gp-register-title' ).show();
			});				

			// Open registration modal window directly from URL
			if ( /#register/.test( window.location.href ) ) {
				$( '#login' ).show();
				$( '.gp-login-title' ).hide();
				$( '.gp-lost-password-title' ).hide();
				$( '.gp-register-title' ).show();
			}
		
		<?php } ?>
									
	});
	</script>
	
<?php } ?>