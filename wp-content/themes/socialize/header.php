<?php include_once( ghostpool_inc . 'login-settings.php' ); ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<?php global $socialize; ?>
<?php if ( $socialize['responsive'] == 'gp-responsive' ) { ?>
	<meta name="viewport" content="width=device-width, initial-scale=1">
<?php } else { ?>
	<meta name="viewport" content="width=1460">
<?php } ?>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<?php if ( is_singular() && pings_open( get_queried_object() ) ) { ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php } ?>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php if ( ! is_page_template( 'blank-page-template.php' ) ) { ?>
	
	<div id="gp-site-wrapper">
			
		<?php if ( has_nav_menu( 'gp-primary-main-header-nav' ) OR has_nav_menu( 'gp-secondary-main-header-nav' ) ) { ?>		
			<nav id="gp-mobile-nav">
				<div id="gp-mobile-nav-close-button"></div>
				<?php wp_nav_menu( array( 'theme_location' => 'gp-primary-main-header-nav', 'sort_column' => 'menu_order', 'container' => '', 'items_wrap' => '<ul class="menu">%3$s</ul>', 'fallback_cb' => 'null', 'walker' => new ghostpool_custom_menu ) ); ?>
				<?php wp_nav_menu( array( 'theme_location' => 'gp-secondary-main-header-nav', 'sort_column' => 'menu_order', 'container' => 'ul', 'fallback_cb' => 'null', 'walker' => new ghostpool_custom_menu ) ); ?>
			</nav>
			<div id="gp-mobile-nav-bg"></div>
		<?php } ?>
			
		<div id="gp-page-wrapper">

			<header id="gp-main-header">

				<div class="gp-container">
				
					<div id="gp-logo">
						<?php if ( $socialize['desktop_logo']['url'] OR $socialize['mobile_logo']['url'] ) {
						
							// Logo URL protocol
							$gp_protocol = ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' OR $_SERVER['SERVER_PORT'] == 443 ) ? 'https:' : 'http:';
						
							$socialize['desktop_logo']['url'] = $gp_protocol . str_replace( array( 'http:', 'https:' ), '', $socialize['desktop_logo']['url'] );
						
							$socialize['desktop_scrolling_logo']['url'] = $gp_protocol . str_replace( array( 'http:', 'https:' ), '', $socialize['desktop_scrolling_logo']['url'] );
						
							$socialize['mobile_logo']['url'] = $gp_protocol . str_replace( array( 'http:', 'https:' ), '', $socialize['mobile_logo']['url'] );

							?>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php bloginfo( 'name' ); ?>">
								<img src="<?php echo esc_url( $socialize['desktop_logo']['url'] ); ?>" alt="<?php bloginfo( 'name' ); ?>" width="<?php echo absint( $socialize['desktop_logo_dimensions']['width'] ); ?>" height="<?php echo absint( $socialize['desktop_logo_dimensions']['height'] ); ?>" class="gp-desktop-logo" />
								<img src="<?php echo esc_url( $socialize['desktop_scrolling_logo']['url'] ); ?>" alt="<?php bloginfo( 'name' ); ?>" width="<?php echo absint( $socialize['desktop_scrolling_logo_dimensions']['width'] ); ?>" height="<?php echo absint( $socialize['desktop_scrolling_logo_dimensions']['height'] ); ?>" class="gp-scrolling-logo" />
								<img src="<?php echo esc_url( $socialize['mobile_logo']['url'] ); ?>" alt="<?php bloginfo( 'name' ); ?>" width="<?php echo absint( $socialize['mobile_logo_dimensions']['width'] ); ?>" height="<?php echo absint( $socialize['mobile_logo_dimensions']['height'] ); ?>" class="gp-mobile-logo" />
							</a>
						<?php } ?>
					</div>
					
					<a id="gp-mobile-nav-button"></a>
				
					<?php if ( has_nav_menu( 'gp-primary-main-header-nav' ) OR has_nav_menu( 'gp-secondary-main-header-nav' ) OR $socialize['search_button'] != 'gp-search-disabled' ) { ?>

						<nav id="gp-main-nav" class="gp-nav">
						
							<nav id="gp-primary-main-nav">
								<?php wp_nav_menu( array( 'theme_location' => 'gp-primary-main-header-nav', 'sort_column' => 'menu_order', 'container' => 'ul', 'fallback_cb' => 'null', 'walker' => new ghostpool_custom_menu ) ); ?>
							</nav>
						
							<?php if ( function_exists( 'is_woocommerce' ) && $socialize['cart_button'] != 'gp-cart-disabled' ) { echo ghostpool_dropdown_cart(); } ?>
						
							<?php if ( $socialize['search_button'] != 'gp-search-disabled' ) { ?>
								<div id="gp-search">
									<div id="gp-search-button"></div>
									<div id="gp-search-box"><?php get_search_form(); ?></div>
								</div>
							<?php } ?>
							
							<?php if ( $socialize['profile_button'] != 'gp-profile-disabled' && is_user_logged_in() ) { ?>
								<a href="<?php if ( function_exists( 'bp_is_active' ) ) { global $bp; echo $bp->loggedin_user->domain; } else { $gp_current_user = wp_get_current_user(); echo get_author_posts_url( $gp_current_user->ID ); } ?>" id="gp-profile-button"></a>
								<?php if ( function_exists( 'bp_notifications_get_notifications_for_user' ) ) { 
									$gp_notifications = bp_notifications_get_notifications_for_user( bp_loggedin_user_id(), 'object' );
									$gp_count = ! empty( $gp_notifications ) ? count( $gp_notifications ) : 0;
									echo '<a href="' . $bp->loggedin_user->domain . '/notifications" class="gp-notification-counter">' . $gp_count . '</a>';
								} ?>
							<?php } ?>	
																		
							<nav id="gp-secondary-main-nav">
								<?php wp_nav_menu( array( 'theme_location' => 'gp-secondary-main-header-nav', 'sort_column' => 'menu_order', 'container' => 'ul', 'fallback_cb' => 'null', 'walker' => new ghostpool_custom_menu ) ); ?>
							</nav>
												
						</nav>
													
					<?php } ?>
						
				</div>
			
			</header>

			<?php if ( $socialize['small_header'] != 'gp-no-small-header' ) { ?>
	
				<header id="gp-small-header">
	
					<div class="gp-container">

						<div class="gp-left-triangle"></div>
						<div class="gp-right-triangle"></div>
					
						<nav id="gp-top-nav" class="gp-nav">		
							
							<div id="gp-left-top-nav">	
								<?php wp_nav_menu( array( 'theme_location' => 'gp-left-small-header-nav', 'sort_column' => 'menu_order', 'container' => 'ul', 'fallback_cb' => 'null', 'walker' => new ghostpool_custom_menu ) ); ?>
							</div>	
						
							<div id="gp-right-top-nav">	
								<?php wp_nav_menu( array( 'theme_location' => 'gp-right-small-header-nav', 'sort_column' => 'menu_order', 'container' => 'ul', 'fallback_cb' => 'null', 'walker' => new ghostpool_custom_menu ) ); ?>
							</div>	
										
						</nav>
					
					</div>
		
				</header>
	
			<?php } ?>
		
			<div id="gp-fixed-padding"></div>
		
			<?php if ( $socialize['header_ad'] ) { ?>
				<div id="gp-header-area">
					<div class="gp-container">
						<?php echo do_shortcode( $socialize['header_ad'] ); ?>
					</div>
				</div>
			<?php } ?>
			
			<div class="gp-clear"></div>
				
<?php } ?>