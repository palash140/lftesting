<?php

/*--------------------------------------------------------------
bbPress Forum Search Form
--------------------------------------------------------------*/

if ( ! function_exists( 'ghostpool_bbp_search' ) ) {

	function ghostpool_bbp_search( $atts, $content = null ) {
	
		extract( shortcode_atts( array(
			'title' => esc_html__( 'Search Forums', 'socialize' ),		
			'classes' => '',
			'title_format' => 'gp-standard-title',
			'title_color' => '',	
			'icon' => '',	
		), $atts ) );
		
		$gp_type = 'BBP_Search_Widget';
		
		// Title color
		if ( $title_color ) {
			$gp_title_color = ' style="background-color: ' . esc_attr( $title_color ) . '; border-color: ' . esc_attr( $title_color ) . '"';
		} else {
			$gp_title_color = '';
		}
		
		// Add icon
		if ( $icon ) {
			$gp_title_icon = '<i class="gp-element-icon fa ' . sanitize_html_class( $icon ) . '"></i>';
		} else {
			$gp_title_icon = '';
		}
				
		$gp_args = array(
			'before_title' => '<h3 class="widgettitle ' . $title_format . '"' . $gp_title_color . '>' . wp_kses_post( $gp_title_icon ) . '<span class="gp-widget-title">',
			'after_title' => '</span><div class="gp-triangle"></div></h3>',
		);

		ob_start();
		
		the_widget( $gp_type, $atts, $gp_args );

		$gp_output_string = ob_get_contents();
		ob_end_clean();
		return $gp_output_string;
	}
}
add_shortcode( 'bbp_search', 'ghostpool_bbp_search' );

/*--------------------------------------------------------------
bbPress Forums List
--------------------------------------------------------------*/

if ( ! function_exists( 'ghostpool_bbp_forums_list' ) ) {

	function ghostpool_bbp_forums_list( $atts, $content = null ) {
	
		extract( shortcode_atts( array(
			'title' => esc_html__( 'Forums List', 'socialize' ),
			'parent_forum' => '0',			
			'classes' => '',
			'title_format' => 'gp-standard-title',
			'title_color' => '',	
			'icon' => '',	
		), $atts ) );
		
		$gp_type = 'BBP_Forums_Widget';
		
		// Title color
		if ( $title_color ) {
			$gp_title_color = ' style="background-color: ' . esc_attr( $title_color ) . '; border-color: ' . esc_attr( $title_color ) . '"';
		} else {
			$gp_title_color = '';
		}
		
		// Add icon
		if ( $icon ) {
			$gp_title_icon = '<i class="gp-element-icon fa ' . sanitize_html_class( $icon ) . '"></i>';
		} else {
			$gp_title_icon = '';
		}
				
		$gp_args = array(
			'before_title' => '<h3 class="widgettitle ' . $title_format . '"' . $gp_title_color . '>' . wp_kses_post( $gp_title_icon ) . '<span class="gp-widget-title">',
			'after_title' => '</span><div class="gp-triangle"></div></h3>',
		);

		ob_start();
		
		the_widget( $gp_type, $atts, $gp_args );

		$gp_output_string = ob_get_contents();
		ob_end_clean();
		return $gp_output_string;
	}
}
add_shortcode( 'bbp_forums_list', 'ghostpool_bbp_forums_list' );

/*--------------------------------------------------------------
bbPress Recent Replies
--------------------------------------------------------------*/

if ( ! function_exists( 'ghostpool_bbp_recent_replies' ) ) {

	function ghostpool_bbp_recent_replies( $atts, $content = null ) {
	
		extract( shortcode_atts( array(
			'title' => esc_html__( 'Recent Replies', 'socialize' ),
			'max_shown' => 5,
			'show_date' => '',
			'show_user' => '',			
			'classes' => '',
			'title_format' => 'gp-standard-title',
			'title_color' => '',	
			'icon' => '',	
		), $atts ) );
		
		$gp_type = 'BBP_Replies_Widget';
		
		// Title color
		if ( $title_color ) {
			$gp_title_color = ' style="background-color: ' . esc_attr( $title_color ) . '; border-color: ' . esc_attr( $title_color ) . '"';
		} else {
			$gp_title_color = '';
		}
		
		// Add icon
		if ( $icon ) {
			$gp_title_icon = '<i class="gp-element-icon fa ' . sanitize_html_class( $icon ) . '"></i>';
		} else {
			$gp_title_icon = '';
		}
				
		$gp_args = array(
			'before_title' => '<h3 class="widgettitle ' . $title_format . '"' . $gp_title_color . '>' . wp_kses_post( $gp_title_icon ) . '<span class="gp-widget-title">',
			'after_title' => '</span><div class="gp-triangle"></div></h3>',
		);

		ob_start();
		
		the_widget( $gp_type, $atts, $gp_args );
		
		$gp_output_string = ob_get_contents();
		ob_end_clean();
		return $gp_output_string;
	}
}
add_shortcode( 'bbp_recent_replies', 'ghostpool_bbp_recent_replies' );

/*--------------------------------------------------------------
bbPress Recent Topics
--------------------------------------------------------------*/

if ( ! function_exists( 'ghostpool_bbp_recent_topics' ) ) {

	function ghostpool_bbp_recent_topics( $atts, $content = null ) {
	
		extract( shortcode_atts( array(
			'title' => esc_html__( 'Recent Topics', 'socialize' ),
			'max_shown' => 5,
			'show_date' => '',
			'show_user' => '',
			'parent_forum' => 'any',
			'order_by' => 'newness',			
			'classes' => '',
			'title_format' => 'gp-standard-title',
			'title_color' => '',	
			'icon' => '',	
		), $atts ) );
		
		$gp_type = 'BBP_Topics_Widget';
		
		// Title color
		if ( $title_color ) {
			$gp_title_color = ' style="background-color: ' . esc_attr( $title_color ) . '; border-color: ' . esc_attr( $title_color ) . '"';
		} else {
			$gp_title_color = '';
		}
		
		// Add icon
		if ( $icon ) {
			$gp_title_icon = '<i class="gp-element-icon fa ' . sanitize_html_class( $icon ) . '"></i>';
		} else {
			$gp_title_icon = '';
		}
				
		$gp_args = array(
			'before_title' => '<h3 class="widgettitle ' . $title_format . '"' . $gp_title_color . '>' . wp_kses_post( $gp_title_icon ) . '<span class="gp-widget-title">',
			'after_title' => '</span><div class="gp-triangle"></div></h3>',
		);

		ob_start();
		
		the_widget( $gp_type, $atts, $gp_args );
		
		$gp_output_string = ob_get_contents();
		ob_end_clean();
		return $gp_output_string;
	}
}
add_shortcode( 'bbp_recent_topics', 'ghostpool_bbp_recent_topics' );

/*--------------------------------------------------------------
bbPress Statistics
--------------------------------------------------------------*/

if ( ! function_exists( 'ghostpool_bbp_statistics' ) ) {

	function ghostpool_bbp_statistics( $atts, $content = null ) {
	
		extract( shortcode_atts( array(
			'title' => esc_html__( 'Forum Statistics', 'socialize' ),
			'classes' => '',
			'title_format' => 'gp-standard-title',
			'title_color' => '',	
			'icon' => '',	
		), $atts ) );
		
		$gp_type = 'BBP_Stats_Widget';
		
		// Title color
		if ( $title_color ) {
			$gp_title_color = ' style="background-color: ' . esc_attr( $title_color ) . '; border-color: ' . esc_attr( $title_color ) . '"';
		} else {
			$gp_title_color = '';
		}
		
		// Add icon
		if ( $icon ) {
			$gp_title_icon = '<i class="gp-element-icon fa ' . sanitize_html_class( $icon ) . '"></i>';
		} else {
			$gp_title_icon = '';
		}
				
		$gp_args = array(
			'before_title' => '<h3 class="widgettitle ' . $title_format . '"' . $gp_title_color . '>' . wp_kses_post( $gp_title_icon ) . '<span class="gp-widget-title">',
			'after_title' => '</span><div class="gp-triangle"></div></h3>',
		);

		ob_start();
		
		the_widget( $gp_type, $atts, $gp_args );
		
		$gp_output_string = ob_get_contents();
		ob_end_clean();
		return $gp_output_string;
	}
}
add_shortcode( 'bbp_statistics', 'ghostpool_bbp_statistics' );

?>