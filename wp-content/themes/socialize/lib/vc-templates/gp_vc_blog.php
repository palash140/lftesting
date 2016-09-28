<?php 

if ( ! function_exists( 'ghostpool_blog' ) ) {

	function ghostpool_blog( $atts, $content = null ) {	
		
		extract( shortcode_atts( array(
			'widget_title' => '',			
			'cats' => '',
			'page_ids' => '',
			'post_types' => 'post',
			'format' => 'gp-blog-standard',
			'orderby' => 'newest',
			'date_posted' => 'all',
			'date_modified' => 'all',
			'filter' => 'disabled',
			'filter_cats' => '',
			'filter_date' => '',
			'filter_title' => '',					
			'filter_comment_count' => '',
			'filter_views' => '',
			'filter_date_posted' => '',
			'filter_date_modified' => '',
			'filter_cats_id' => '',
			'per_page' => '12',
			'offset' => '',
			'featured_image' => 'enabled',
			'image_width' => '200',
			'image_height' => '200',
			'hard_crop' => true,
			'image_alignment' => 'gp-image-align-left',
			'title_position' => 'title-next-to-thumbnail',
			'content_display' => 'excerpt',
			'excerpt_length' => '160',
			'meta_author' => '',
			'meta_date' => '',
			'meta_views' => '',
			'meta_comment_count' => '',
			'meta_cats' => '',
			'meta_tags' => '',	
			'read_more_link' => 'disabled',
			'page_arrows' => 'disabled',
			'page_numbers' => 'disabled',
			'see_all' => 'disabled',
			'see_all_link' => '',
			'see_all_text' => esc_html__( 'See All Items', 'socialize' ),
			'classes' => '',
			'title_format' => 'gp-standard-title',
			'title_color' => '',	
			'icon' => '',		
		), $atts ) );
							
		global $socialize;

		// Detect shortcode
		$GLOBALS['ghostpool_shortcode'] = 'blog';
		
		// Load page variables
		ghostpool_shortcode_options( $atts );
		ghostpool_category_variables();

		// Load scripts
		if ( $GLOBALS['ghostpool_format'] == 'gp-blog-masonry' ) {
			wp_enqueue_script( 'gp-isotope' );
			wp_enqueue_script( 'gp-images-loaded' );
		}
						
		// Unique Name	
		STATIC $gp_i = 0;
		$gp_i++;
		$gp_name = 'gp_blog_wrapper_' . $gp_i;

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
			'tax_query'           => $GLOBALS['ghostpool_tax'],
			'orderby' 		      => $GLOBALS['ghostpool_orderby_value'],
			'order' 		      => $GLOBALS['ghostpool_order'],	
			'meta_key' 		      => $GLOBALS['ghostpool_meta_key'],
			'posts_per_page'      => $GLOBALS['ghostpool_per_page'],
			'offset' 		      => $GLOBALS['ghostpool_offset'],	
			'paged'          	  => $GLOBALS['ghostpool_paged'],
			'date_query' 	      => array( $GLOBALS['ghostpool_date_posted_value'], $GLOBALS['ghostpool_date_modified_value'] ),
		);
		
		ob_start(); $gp_query = new WP_Query( $gp_args ); ?>		

		<div id="<?php echo sanitize_html_class( $gp_name ); ?>" class="gp-blog-wrapper gp-vc-element <?php echo sanitize_html_class( $GLOBALS['ghostpool_format'] ); ?> <?php echo esc_attr( $classes ); ?>"<?php if ( function_exists( 'ghostpool_data_properties' ) ) { echo ghostpool_data_properties( 'blog' ); } ?>>

			<?php if ( $widget_title ) { ?>
				<h3 class="widgettitle <?php echo $title_format; ?>"<?php if ( $title_color ) { ?> style="background-color: <?php echo esc_attr( $title_color ); ?>; border-color: <?php echo esc_attr( $title_color ); ?>"<?php } ?>>
					<?php if ( $icon ) { ?><i class="gp-element-icon fa <?php echo sanitize_html_class( $icon ); ?>"></i><?php } ?>
					<span class="gp-widget-title"><?php echo esc_attr( $widget_title ); ?></span>
					<div class="gp-triangle"></div>
					<?php if ( $see_all == 'enabled' ) { ?>
						<span class="gp-see-all-link"><a href="<?php echo esc_url( $see_all_link ); ?>"><?php echo esc_attr( $see_all_text ); ?></a></span>
					<?php } ?>
				</h3>
			<?php } elseif ( $filter == 'disabled' ) { ?>
				<div class="gp-empty-widget-title"></div>
			<?php } ?>
			
			<?php if ( $gp_query->have_posts() ) : ?>
			
				<?php if ( $page_arrows == 'enabled' ) { ?>
					<div class="gp-pagination gp-standard-pagination gp-pagination-arrows">
						<?php echo ghostpool_get_previous_posts_page_link( $gp_query->max_num_pages ); ?>
						<?php echo ghostpool_get_next_posts_page_link( $gp_query->max_num_pages ); ?>	
					</div>
				<?php } ?>
											
				<?php get_template_part( 'lib/sections/filter' ); ?>
				
				<div class="gp-inner-loop <?php echo sanitize_html_class( $socialize['ajax'] ); ?>">
							
					<?php if ( $GLOBALS['ghostpool_format'] == 'gp-blog-masonry' ) { ?><div class="gp-gutter-size"></div><?php } ?>
			
					<?php while ( $gp_query->have_posts() ) : $gp_query->the_post(); ?>

						<?php get_template_part( 'post', 'loop' ); ?>

					<?php endwhile; ?>
		
				</div>

				<?php if ( $page_numbers == 'enabled' ) { ?>
					<?php echo ghostpool_pagination( $gp_query->max_num_pages ); ?>
				<?php } ?>

			<?php else : ?>

				<strong class="gp-no-items-found"><?php esc_html_e( 'No items found.', 'socialize' ); ?></strong>

			<?php endif; wp_reset_postdata(); ?>
							
		</div>
					
		<?php

		$gp_output_string = ob_get_contents();
		ob_end_clean();  
		$GLOBALS['ghostpool_shortcode'] = null;
		return $gp_output_string;

	}

}

add_shortcode( 'blog', 'ghostpool_blog' );
	
?>