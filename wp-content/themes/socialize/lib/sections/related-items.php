<?php global $socialize;

// Options
$gp_related_tags = wp_get_post_tags( get_the_ID() );
if ( is_singular( 'post' ) ) {
	$gp_post_type = 'post';
	$gp_per_page = $socialize['post_related_items_per_page'];
	$gp_items_in_view = $socialize['post_related_items_in_view'];
	$GLOBALS['ghostpool_image_width'] = $socialize['post_related_items_image']['width'];
	$GLOBALS['ghostpool_image_height'] = $socialize['post_related_items_image']['height'];
	$gp_related_cats = wp_get_post_terms( get_the_ID(), 'category' );
} elseif ( is_singular( 'gp_portfolio_item' ) ) {
	$gp_post_type = 'gp_portfolio_item';
	$gp_per_page = $socialize['portfolio_item_related_items_per_page'];
	$gp_items_in_view = $socialize['portfolio_item_related_items_in_view'];
	$GLOBALS['ghostpool_image_width'] = $socialize['portfolio_item_related_items_image']['width'];
	$GLOBALS['ghostpool_image_height'] = $socialize['portfolio_item_related_items_image']['height'];	
	$gp_related_cats = wp_get_post_terms( get_the_ID(), 'gp_portfolios' );
}

if ( $gp_related_tags ) {
	$gp_related_items = $gp_related_tags;
} elseif ( $gp_related_cats ) {
	$gp_related_items = $gp_related_cats;
} else {
	$gp_related_items = '';
}

$gp_temp_query = $wp_query;

if ( $gp_related_items ) {

	$gp_related_ids = array();

	foreach ( $gp_related_items as $gp_related_item ) $gp_related_ids[] = $gp_related_item->term_id;

	if ( $gp_related_tags ) {	
		$gp_related_type = 'tag__in';
		$gp_related_query = $gp_related_ids;
	} elseif ( is_singular( 'gp_portfolio_item' ) && $gp_related_cats ) {
		$gp_related_type = 'tax_query';
		$gp_related_query = array( 'relation' => 'OR', array( 'taxonomy' => 'gp_portfolios', 'field' => 'term_id', 'terms' => $gp_related_ids ) );
	} elseif ( $gp_related_cats ) {
		$gp_related_type = 'category__in';
		$gp_related_query = $gp_related_ids;
	} else {
		$gp_related_type = '';
		$gp_related_query = '';
	}
			
	$gp_args = array(
		'post_type'           => $gp_post_type,
		'orderby'             => 'rand',
		'order'               => 'asc',
		'paged'               => 1,
		'posts_per_page'      => $gp_per_page,
		'offset'              => 0,
		$gp_related_type 	  => $gp_related_query,
		'post__not_in'        => array( get_the_ID() ),
		'ignore_sticky_posts' => true,
	); 

	$gp_query = new wp_query( $gp_args ); if ( $gp_query->have_posts() ) : 
				
		wp_enqueue_script( 'gp-flexslider' );

		?>
	
		<div class="gp-related-wrapper gp-carousel-wrapper gp-slider">

			<h3><?php esc_html_e( 'Related Articles', 'socialize' ); ?></h3>
			
			<ul class="slides">
			
				<?php while ( $gp_query->have_posts() ) : $gp_query->the_post(); ?>
				
					<li>

						<section <?php post_class( 'gp-post-item' ); ?>>
						
							<?php if ( has_post_thumbnail() ) { ?>
						
								<div class="gp-post-thumbnail gp-loop-featured">
									
									 <div class="gp-image-above">

										<?php $gp_image = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), $GLOBALS['ghostpool_image_width'], $GLOBALS['ghostpool_image_height'], true, false, true ); ?>
										<?php if ( $socialize['retina'] == 'gp-retina' ) {
											$gp_retina = aq_resize( wp_get_attachment_url( get_post_thumbnail_id( get_the_ID() ) ), $GLOBALS['ghostpool_image_width'] * 2, $GLOBALS['ghostpool_image_height'] * 2, true, true, true );
										} else {
											$gp_retina = '';
										} ?>

										<a href="<?php if ( get_post_format() == 'link' ) { echo esc_url( get_post_meta( get_the_ID(), 'link', true ) ); } else { the_permalink(); } ?>" title="<?php the_title_attribute(); ?>"<?php if ( get_post_format() == 'link' ) { ?> target="<?php echo get_post_meta( get_the_ID(), 'link_target', true ); ?>"<?php } ?>>
				
											<img src="<?php echo esc_url( $gp_image[0] ); ?>" data-rel="<?php echo esc_url( $gp_retina ); ?>" width="<?php echo absint( $gp_image[1] ); ?>" height="<?php echo absint( $gp_image[2] ); ?>" alt="<?php if ( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ) { echo esc_attr( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ); } else { the_title_attribute(); } ?>" class="gp-post-image" />

										</a>
										
									</div>	
					
								</div>
						
							<?php } elseif ( get_post_format() != '0' && get_post_format() != 'gallery' ) { ?>
								
								<div class="gp-loop-featured">
									<?php get_template_part( 'lib/sections/loop', get_post_format() ); ?>
								</div>
								
							<?php } ?>
							
							<?php if ( get_post_format() != 'quote' OR has_post_thumbnail() ) { ?>

								<div class="gp-loop-content">

									<h2 class="gp-loop-title" itemprop="headline"><a href="<?php if ( get_post_format() == 'link' ) { echo esc_url( get_post_meta( get_the_ID(), 'link', true ) ); } else { the_permalink(); } ?>" title="<?php the_title_attribute(); ?>"<?php if ( get_post_format() == 'link' ) { ?> target="<?php echo get_post_meta( get_the_ID(), 'link_target', true ); ?>"<?php } ?>><?php the_title(); ?></a></h2>
																									
									<div class="gp-loop-meta">
										<time class="gp-post-meta gp-meta-date" itemprop="datePublished" datetime="<?php echo get_the_date( 'c' ); ?>"><?php the_time( get_option( 'date_format' ) ); ?></time>
									</div>	

								</div>
							
							<?php } ?>
						
						</section>
					
					</li>
				
				<?php endwhile; ?>	

			</ul>
				
		</div>
		
		<script>
		jQuery( document ).ready( function( $ ) {
			'use strict';
	
			var $window = $( window ),
				flexslider = { vars:{} };

			function getGridSize() {
				return ( $window.width() <= 567 ) ? 1 : ( $window.width() <= 1023 ) ? 2 : <?php echo absint( $gp_items_in_view ); ?>;
			}

			$window.load( function() {
				$( '.gp-related-wrapper' ).flexslider({  
					animation: 'slide',
					animationLoop: false,
					itemWidth: <?php echo absint( $gp_items_in_view ); ?>,
					itemMargin: 30,
					slideshowSpeed: 9999999,
					animationSpeed: 600,
					directionNav: true,			
					controlNav: false,			
					pauseOnAction: true, 
					pauseOnHover: false,
					prevText: '',
					nextText: '',
					minItems: getGridSize(),
					maxItems: getGridSize(),
					start: function(slider){
						flexslider = slider;
					}
				});	
			});
					
			$window.resize( function() {
				var gridSize = getGridSize();
				flexslider.vars.minItems = gridSize;
				flexslider.vars.maxItems = gridSize;
			});			

		});
		</script>

	<?php endif; wp_reset_postdata(); ?>

<?php } ?>