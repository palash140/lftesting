<?php

/*
* Add your own functions here. You can also copy some of the theme functions into this file and WordPress will use these functions instead of the original functions.
*/

/////////////////////////////////////// Load parent style.css ///////////////////////////////////////

if ( ! function_exists( 'ghostpool_enqueue_child_styles' ) ) {
	function ghostpool_enqueue_child_styles() { 
		wp_enqueue_style( 'gp-parent-style', get_template_directory_uri() . '/style.css' );
	}
}
add_action( 'wp_enqueue_scripts', 'ghostpool_enqueue_child_styles' );

 function change_media_gallery_title( $title ){
		global $rtmedia_query;
		global $pagenow;
 		$rtmedia_query->is_gallery_shortcode = false;
 		$title;
 		
 		//change rtmedia's default title 
		if(($pagenow == "index.php") && ($title == "All Photos"))
	        $title = "Pictures";
	    else if(($pagenow == "index.php") && ($title == "All Videos"))
	        $title = 'Videos';
 		
        return $title;
    }
add_filter('rtmedia_gallery_title', 'change_media_gallery_title', 10, 1);

function my_wp_get_nav_menu_items( $items, $menu, $args ){

	if ( is_user_logged_in() && class_exists( 'RTMedia' ) && $menu->slug == 'life-menu' && count($items) > 2) {
	
	$more_menu_item_id = $items[2]->ID;
	
	// get current logged in user id
	$user_id = get_current_user_id();
	$url = trailingslashit ( get_rtmedia_user_link ( get_current_user_id () ) ) . RTMEDIA_MEDIA_SLUG . '/';     // get user's media link

	// add new menu item to nav menu
	$new_item = new stdClass;
	$new_item->menu_item_parent = $more_menu_item_id;
	$new_item->url = $url;
	$new_item->title = 'Member Gallery';
	$new_item->menu_order = count( $items ) + 1;
	$items[] = $new_item;
	}
	return $items;
}
add_filter( 'wp_get_nav_menu_items', 'my_wp_get_nav_menu_items', 99, 3 );

?>
