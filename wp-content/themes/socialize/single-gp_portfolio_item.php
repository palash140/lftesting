<?php get_header(); global $socialize;

// Load page variables
ghostpool_loop_variables();

// Load FlexSlider
if ( $GLOBALS['ghostpool_type'] == 'gp-left-slider' OR $GLOBALS['ghostpool_type'] == 'gp-fullwidth-slider' ) {
	wp_enqueue_script( 'gp-flexslider' );
}

?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); 

	// Portfolio Column Classes			
	if ( $GLOBALS['ghostpool_type'] == 'gp-left-image' OR $GLOBALS['ghostpool_type'] == 'gp-left-slider' ) {
		$gp_portfolio_class_1 = 'gp-portfolio-left-col';
		$gp_portfolio_class_2 = 'gp-portfolio-right-col';
	} else {
		$gp_portfolio_class_1 = 'gp-portfolio-full-col';
		$gp_portfolio_class_2 = '';			
	} 

	?>		
	
	<?php if ( $GLOBALS['ghostpool_page_header'] == 'gp-fullwidth-page-header' OR $GLOBALS['ghostpool_page_header'] == 'gp-full-page-page-header' ) { ghostpool_page_header( get_the_ID() ); } ?>

	<div id="gp-content-wrapper" class="gp-container">

		<?php if ( $GLOBALS['ghostpool_page_header'] == 'gp-large-page-header' ) { ghostpool_page_header( get_the_ID() ); } ?>

		<div id="gp-inner-container">

			<div id="gp-left-column">	

				<div id="gp-content">	

					<article <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">

						<?php echo ghostpool_meta_data( get_the_ID() ); ?>

						<div id="gp-post-navigation">
				
							<?php ghostpool_breadcrumbs(); ?>
					
							<?php if ( $socialize['portfolio_item_meta']['post_nav'] == '1' OR $socialize['portfolio_item_meta']['top_share_icons'] == '1' ) { ?>
					
								<div id="gp-post-links">
									<?php if ( $socialize['portfolio_item_meta']['post_nav'] == '1' ) { ?>
										<?php previous_post_link( '%link', '', false ); ?>
										<?php next_post_link( '%link', '', false ); ?>
									<?php } ?>
									<?php if ( $socialize['portfolio_item_meta']['top_share_icons'] == '1' ) { ?>
									<a href="#" class="gp-share-button"></a><?php } ?>
								</div>
					
							<?php } ?>
							
							<?php if ( $socialize['portfolio_item_meta']['top_share_icons'] == '1' ) { ?>
								<?php get_template_part( 'lib/sections/share', 'icons' ); ?>
							<?php } ?>
					
							<div class="gp-clear"></div>
				
						</div>	

						<header class="gp-entry-header">	

							<h1 class="gp-entry-title">
								<?php if ( ! empty( $GLOBALS['ghostpool_custom_title'] ) ) { echo esc_attr( $GLOBALS['ghostpool_custom_title'] ); } else { the_title(); } ?>
							</h1>

							<?php if ( ! empty( $GLOBALS['ghostpool_subtitle'] ) ) { ?>
								<h3 class="gp-subtitle"><?php echo esc_attr( $GLOBALS['ghostpool_subtitle'] ); ?></h3>
							<?php } ?>

							<?php if ( get_post_meta( get_the_ID(), 'portfolio_item_link', true ) ) { ?>
								<a href="<?php echo get_post_meta( get_the_ID(), 'portfolio_item_link', true ); ?>" class="button gp-portfolio-link" target="<?php echo esc_attr( $GLOBALS['ghostpool_link_target'] ); ?>"><?php echo esc_attr( $GLOBALS['ghostpool_link_text'] ); ?></a>
							<?php } ?>
								
						</header>
				
						<div class="gp-entry-content gp-portfolio-row">
			
							<?php if ( $GLOBALS['ghostpool_type'] != 'none' ) { ?>

								<?php $gp_image = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), $GLOBALS['ghostpool_image_width'], $GLOBALS['ghostpool_image_height'], $GLOBALS['ghostpool_hard_crop'], false, true ); ?>
								<?php if ( $socialize['retina'] == 'gp-retina' ) {
									$gp_retina = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ),  $GLOBALS['ghostpool_image_width'] * 2, $GLOBALS['ghostpool_image_height'] * 2, $GLOBALS['ghostpool_hard_crop'], true, true );
								} else {
									$gp_retina = '';
								} ?>

								<?php if ( $GLOBALS['ghostpool_type'] == 'gp-left-slider' OR $GLOBALS['ghostpool_type'] == 'gp-fullwidth-slider' ) {

									// Gallery Image IDs
									$gp_image_ids = array_filter( explode( ',', $socialize['portfolio_item_gallery_slider'] ) );

									?>

									<div class="<?php echo sanitize_html_class( $gp_portfolio_class_1 ); ?>">

										<div class="gp-portfolio-slider gp-slider <?php echo sanitize_html_class( $GLOBALS['ghostpool_type'] ); ?>" style="width: <?php echo absint( $gp_image[1] ); ?>px;"> 
											 <ul class="slides">
												<?php foreach ( $gp_image_ids as $gp_image_id ) { ?>
													<li>
														<?php $gp_image = aq_resize( wp_get_attachment_url( $gp_image_id ), $GLOBALS['ghostpool_image_width'], $GLOBALS['ghostpool_image_height'], $GLOBALS['ghostpool_hard_crop'], false, true ); ?>
														<?php if ( $socialize['retina'] == 'gp-retina' ) {
															$gp_retina = aq_resize(wp_get_attachment_url( $gp_image_id ),  $GLOBALS['ghostpool_image_width'] * 2, $GLOBALS['ghostpool_image_height'] * 2, $GLOBALS['ghostpool_hard_crop'], true, true );
														} else {
															$gp_retina = '';
														} ?>
														<img src="<?php echo esc_url( $gp_image[0] ); ?>" data-rel="<?php echo esc_url( $gp_retina ); ?>" width="<?php echo absint( $gp_image[1] ); ?>" height="<?php echo absint( $gp_image[2] ); ?>" alt="<?php if ( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true) ) { echo esc_attr( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ); } else { the_title_attribute(); } ?>" class="gp-post-image" />			
													</li>
												<?php } ?>
											</ul>
										 </div>
		 
									 </div>

									<script>
									jQuery( window ).load( function() {
										'use strict';
										jQuery( '.gp-portfolio-slider' ).flexslider({ 
											animation: 'fade',
											slideshowSpeed: 9999999,
											animationSpeed: 600,
											directionNav: true,			
											controlNav: false,			
											pauseOnAction: true, 
											pauseOnHover: false,
											prevText: '',
											nextText: '',
											smoothHeight: true
										});

									});
									</script>

								<?php } else { ?>

									<div class="<?php echo sanitize_html_class( $gp_portfolio_class_1 ); ?>">
							
										<img src="<?php echo esc_url( $gp_image[0] ); ?>" data-rel="<?php echo esc_url( $gp_retina ); ?>" width="<?php echo absint( $gp_image[1] ); ?>" height="<?php echo absint( $gp_image[2] ); ?>" alt="<?php if ( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ) { echo esc_attr( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ); } else { the_title_attribute(); } ?>" class="gp-post-image" />

									</div>
							
								<?php } ?>
				
							<?php } ?>
	
							<?php if ( $post->post_content ) { ?>
								<div class="<?php echo sanitize_html_class( $gp_portfolio_class_2 ); ?>">
									<?php the_content(); ?>
									<?php wp_link_pages( 'before=<div class="gp-pagination gp-pagination-numbers gp-standard-pagination gp-entry-nav"><ul class="page-numbers">&pagelink=<span class="page-numbers">%</span>&after=</ul></div>' ); ?>
								</div>
							<?php } ?>
											
						</div>

						<?php if ( $GLOBALS['ghostpool_meta_tags'] == '1' ) { ?>
							<?php the_tags( '<div class="gp-entry-tags">', ' ', '</div>' ); ?>
						<?php } ?>

						<?php if ( $socialize['portfolio_item_meta']['bottom_share_icons'] == '1' ) { ?>
							<?php get_template_part( 'lib/sections/share', 'icons' ); ?>
						<?php } ?>

						<?php if ( $socialize['portfolio_item_author_info'] == 'enabled' ) { ?>
							<?php get_template_part( 'lib/sections/author', 'info' ); ?>
						<?php } ?>
														
						<?php if ( $socialize['portfolio_item_related_items'] == 'enabled' ) { ?>
							<?php get_template_part( 'lib/sections/related', 'items' ); ?>
						<?php } ?>
					
						<?php comments_template(); ?>

					</article>		

				</div>

				<?php get_sidebar( 'left' ); ?>
		
			</div>
		
			<?php get_sidebar( 'right' ); ?>
	
		</div>
				
		<div class="gp-clear"></div>
	
	</div>

<?php endwhile; endif; ?>
		
<?php get_footer(); ?>