<?php
/*
Template Name: Homepage2
*/
get_header(); global $socialize; ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>		
		
	<div id="gp-content-wrapper" style="width : 100%">
			<?php the_content(); ?>
	</div>
	
<?php endwhile; endif; ?>
	
<?php get_footer(); ?>