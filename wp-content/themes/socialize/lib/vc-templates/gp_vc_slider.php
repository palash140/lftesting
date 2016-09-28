<?php 

if ( ! function_exists( 'ghostpool_slider' ) ) {

	function ghostpool_slider( $atts, $content = null ) {	
		
		extract( shortcode_atts( array(			
			'widget_title' => '',
			'cats' => '',
			'page_ids' => '',
			'post_types' => 'post',
			'format' => 'gp-slider-two-cols',	
			'orderby' => 'newest',
			'date_posted' => 'all',
			'date_modified' => 'all',
			'large_image_width' => '',
			'large_image_height' => '',
			'small_image_width' => '',
			'small_image_height' => '',
			'hard_crop' => true,
			'per_page' => '9',
			'offset' => '',
			'caption_title' => 'enabled',
			'caption_text' => 'enabled',
			'animation' => 'slide',
			'slider_speed' => '6',
			'animation_speed' => '0.6',
			'buttons' => 'enabled',
			'arrows' => 'disabled',
			'see_all' => 'disabled',
			'see_all_link' => '',
			'see_all_text' => esc_html__( 'See All Items', 'socialize' ),
			'classes' => '',
			'title_format' => 'gp-standard-title',
			'title_color' => '',
			'icon' => '',
		), $atts ) );
								
		global $post;
		
		// Detect shortcode
		$GLOBALS['ghostpool_shortcode'] = 'slider';
		
		// Load page variables
		ghostpool_shortcode_options( $atts );
		ghostpool_category_variables();

		// Load scripts		
		wp_enqueue_script( 'gp-flexslider' );

		// Unique Name	
		STATIC $gp_i = 0;
		$gp_i++;
		$gp_name = 'gp_slider_wrapper_' . $gp_i;

		// Page IDs
		if ( $page_ids ) {
			$page_ids = explode( ',', $page_ids );
		} else {
			$page_ids = '';
		}
			
		$gp_args = array(
			'post_status'         => 'publish',
			'post_type'           => explode( ',', $post_types ),
			'post__in'            => $page_ids,
			'tax_query' 	      => $GLOBALS['ghostpool_tax'],
			'orderby' 		      => $GLOBALS['ghostpool_orderby_value'],
			'order' 		      => $GLOBALS['ghostpool_order'],
			'meta_key' 		      => $GLOBALS['ghostpool_meta_key'],
			'posts_per_page'      => $per_page,
			'offset' 		      => $GLOBALS['ghostpool_offset'],  
			'paged'			      => 1,
			'date_query' 	      => array( $GLOBALS['ghostpool_date_posted_value'], $GLOBALS['ghostpool_date_modified_value'] ),
		);

		ob_start(); $gp_query = new WP_Query( $gp_args ); $gp_counter = 1; ?>

		<div id="<?php echo sanitize_html_class( $gp_name ); ?>" class="gp-slider-wrapper gp-vc-element gp-slider <?php echo sanitize_html_class( $format ); ?> <?php echo esc_attr( $classes ); ?>">

			<?php if ( $widget_title ) { ?>
				<h3 class="widgettitle <?php echo $title_format; ?>"<?php if ( $title_color ) { ?> style="background-color: <?php echo esc_attr( $title_color ); ?>; border-color: <?php echo esc_attr( $title_color ); ?>"<?php } ?>>
					<?php if ( $icon ) { ?><i class="gp-element-icon fa <?php echo sanitize_html_class( $icon ); ?>"></i><?php } ?>
					<span class="gp-widget-title"><?php echo esc_attr( $widget_title ); ?></span>
					<div class="gp-triangle"></div>
					<?php if ( $see_all == 'enabled' ) { ?>
						<div class="gp-see-all-link"><a href="<?php echo esc_url( $see_all_link ); ?>"><?php echo esc_attr( $see_all_text ); ?></a></div>
					<?php } ?>
				</h3>
			<?php } ?>
					
			<?php if ( $gp_query->have_posts() ) : ?>

				<ul class="slides">
	
					<?php while ( $gp_query->have_posts() ) : $gp_query->the_post(); ?>
										
						<?php
						
						// Slider images
						if ( ! empty( $GLOBALS['ghostpool_title_bg']['url'] ) ) {
							$gp_protocol = ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' OR $_SERVER['SERVER_PORT'] == 443 ) ? 'https:' : 'http:';
							$GLOBALS['ghostpool_image_url'] = $GLOBALS['ghostpool_title_bg']['url'];
							$GLOBALS['ghostpool_image_url'] = $gp_protocol . str_replace( array( 'http:', 'https:' ), '', $GLOBALS['ghostpool_image_url'] );
						} else {
							$GLOBALS['ghostpool_image_url'] = wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) );
						}
						
						// Large/small slider options
						if ( ( $format == 'gp-slider-two-cols' && $gp_counter % 3 == 1 ) OR $format == 'gp-slider-one-col' ) {
							if ( $format == 'gp-slider-two-cols' ) {
								if ( $large_image_width == '' ) {
									$GLOBALS['ghostpool_slide_width'] = 780;
								} else {
									$GLOBALS['ghostpool_slide_width'] = $large_image_width;
								}
								if ( $large_image_height == '' ) {
									$GLOBALS['ghostpool_slide_height'] = 500;
								} else {
									$GLOBALS['ghostpool_slide_height'] = $large_image_height;
								}
							} else {
								if ( $large_image_width == '' ) {
									$GLOBALS['ghostpool_slide_width'] = 1430;
								} else {
									$GLOBALS['ghostpool_slide_width'] = $large_image_width;
								}
								if ( $large_image_height == '' ) {
									$GLOBALS['ghostpool_slide_height'] = 500;
								} else {
									$GLOBALS['ghostpool_slide_height'] = $large_image_height;
								}
							}
							$GLOBALS['ghostpool_slide_number'] = '1';
						} else {
							if ( $small_image_width == '' ) {
								$GLOBALS['ghostpool_slide_width'] = 640;
							} else {
								$GLOBALS['ghostpool_slide_width'] = $small_image_width;
							}
							if ( $small_image_height == '' ) {
								$GLOBALS['ghostpool_slide_height'] = 250;
							} else {
								$GLOBALS['ghostpool_slide_height'] = $small_image_height;
							}
							$GLOBALS['ghostpool_slide_number'] = '';
						}
																
						?>				
						
						<?php if ( $format == 'gp-slider-two-cols' ) { ?>

							<?php if ( $gp_counter % 3 == 1 ) { ?>
								<li>
													
									<div class="gp-slider-large gp-slide-item">
										<?php get_template_part( 'lib/vc-templates/gp_vc_slide' ); ?>
									</div>	
						
								<?php } elseif ( $gp_counter % 3 == 2 ) { ?>
								
									<div class="gp-slider-right">
								
										<div class="gp-slide-2 gp-slide-small gp-slide-item">					
											<?php get_template_part( 'lib/vc-templates/gp_vc_slide' ); ?>
										</div>

								<?php } elseif ( $gp_counter % 3 == 0 ) { ?>
								
										<div class="gp-slide-3 gp-slide-small gp-slide-item">					
											<?php get_template_part( 'lib/vc-templates/gp_vc_slide' ); ?>
										</div>
								
									</div>
																								
								</li>
								
							<?php } ?>
								
						<?php } else { ?>
						
							<li>
								<div class="gp-slider-large gp-slide-item">
									<?php get_template_part( 'lib/vc-templates/gp_vc_slide' ); ?>
								</div>	
							</li>
						
						<?php } ?>	
						
					<?php $gp_counter++; endwhile; ?>	

				</ul>
															
			<?php else : ?>

				<strong class="gp-no-items-found"><?php esc_html_e( 'No items found.', 'socialize' ); ?></strong>

			<?php endif; wp_reset_postdata(); ?>

		</div>

		<script>
		jQuery( document ).ready( function( $ ) {
		jQuery( window ).load( function() {
			'use strict';
			$( '#<?php echo sanitize_html_class( $gp_name ); ?>' ).flexslider({ 
				animation: '<?php echo esc_attr( $animation ); ?>',
				slideshowSpeed: <?php if ( $slider_speed != '0' ) { echo absint( $slider_speed * 1000 ); } else { echo '9999999'; } ?>,
				animationSpeed: <?php echo absint( $animation_speed * 1000 ); ?>,
				directionNav: <?php if ( $arrows == 'enabled' ) { ?>true<?php } else { ?>false<?php } ?>,	
				controlNav: <?php if ( $buttons == 'enabled' ) { ?>true<?php } else { ?>false<?php } ?>,			
				pauseOnAction: true, 
				pauseOnHover: false,
				prevText: '',
				nextText: '',
				touch: <?php if ( ( $format == 'gp-slider-two-cols' && $per_page > 3 ) OR ( $format == 'gp-slider-one-col' && $per_page > 1 ) ) { ?>true<?php } else { ?>false<?php } ?>
			});
		});	
		});
		</script>
			 				
		<?php

		$gp_output_string = ob_get_contents();
		ob_end_clean();
		$GLOBALS['ghostpool_shortcode'] = null;
		return $gp_output_string;

	}

}

add_shortcode( 'slider', 'ghostpool_slider' );
	
?>