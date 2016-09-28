<?php

/////////////////////////////////////// File directories ///////////////////////////////////////

$gp_template_dir = get_template_directory();
$gp_template_dir_uri = get_template_directory_uri();
define( 'ghostpool', $gp_template_dir . '/' );
define( 'ghostpool_uri', $gp_template_dir_uri . '/' );
define( 'ghostpool_css_uri', $gp_template_dir_uri . '/lib/css/' );
define( 'ghostpool_images', $gp_template_dir_uri . '/lib/images/' );
define( 'ghostpool_inc', $gp_template_dir . '/lib/inc/' );
define( 'ghostpool_plugins', $gp_template_dir . '/lib/plugins/' );
define( 'ghostpool_scripts', $gp_template_dir . '/lib/scripts/' );
define( 'ghostpool_scripts_uri', $gp_template_dir_uri . '/lib/scripts/' );
define( 'ghostpool_widgets', $gp_template_dir . '/lib/widgets/' );
define( 'ghostpool_vc', $gp_template_dir . '/lib/vc-templates/' );


/////////////////////////////////////// Localisation ///////////////////////////////////////

load_theme_textdomain( 'socialize', trailingslashit( WP_LANG_DIR ) . 'themes/' );
load_theme_textdomain( 'socialize', get_stylesheet_directory() . '/languages' );
load_theme_textdomain( 'socialize', ghostpool . 'languages' );
		
		
/////////////////////////////////////// Theme setup ///////////////////////////////////////

if ( ! function_exists( 'ghostpool_theme_setup' ) ) {
	function ghostpool_theme_setup() {

		global $content_width;
		
		// Set the content width based on the theme's design and stylesheet
		if ( ! isset( $content_width ) ) {
			$content_width = 730;
		}
		
		// Featured images
		add_theme_support( 'post-thumbnails' );
		set_post_thumbnail_size( 150, 150, true );

		// Background customizer
		add_theme_support( 'custom-background' );

		// Add shortcode support to Text widget
		add_filter( 'widget_text', 'do_shortcode' );

		// This theme styles the visual editor with editor-style.css to match the theme style
		add_editor_style( 'lib/css/editor-style.css' );

		// Add default posts and comments RSS feed links to <head>
		add_theme_support( 'automatic-feed-links' );

		// WooCommerce Support
		add_theme_support( 'woocommerce' );
		
		// Post formats
		add_theme_support( 'post-formats', array( 'quote', 'video', 'audio', 'gallery', 'link' ) );

		// Title support
		add_theme_support( 'title-tag' );

	}
}
add_action( 'after_setup_theme', 'ghostpool_theme_setup' );


/////////////////////////////////////// Additional functions ///////////////////////////////////////

// Redux Ad Remover
require_once( ghostpool . 'lib/redux-extensions/extensions/ad_remove/extension_ad_remove.php' );

// Metaboxes
require_once( ghostpool . 'lib/redux-extensions/config.php' );

// Framework
if ( ! class_exists( 'ReduxFramework' ) && file_exists( ghostpool . 'lib/ReduxCore/framework.php' ) ) {
    require_once( ghostpool . 'lib/ReduxCore/framework.php' );
}
if ( ! isset( $socialize ) && file_exists( ghostpool_inc. 'theme-config.php' ) ) {
    require_once( ghostpool_inc . 'theme-config.php' );
}

global $socialize;
$socialize = get_option( 'socialize' );

// Category options
require_once( ghostpool_inc . 'category-config.php' );

// Init variables
require_once( ghostpool_inc . 'init-variables.php' );

// Loop variables
require_once( ghostpool_inc . 'loop-variables.php' );

// Category variables
require_once( ghostpool_inc . 'category-variables.php' );

// Page headers
require_once( ghostpool_inc . 'page-headers.php' );

// One click demo installer
require( ghostpool . 'lib/demo-installer/init.php' );

// Image resizer
require_once( ghostpool_inc . 'aq_resizer.php' );

// Custom menu walker
require_once( ghostpool . 'lib/menus/custom-menu-walker.php' );

// Custom menu fields
require_once( ghostpool . 'lib/menus/menu-item-custom-fields.php' );

// Shortcodes
if ( function_exists( 'vc_set_as_theme' ) ) {
	require_once( ghostpool_inc . 'theme-shortcodes.php' );
	require_once( ghostpool_inc . 'default-vc-templates.php' );
}

// Ajax loop
if ( isset( $socialize['ajax'] ) && $socialize['ajax'] == 'gp-ajax-loop' ) {
	require_once( ghostpool_inc . 'ajax.php' );
}

// BuddyPress functions
if ( function_exists( 'bp_is_active' ) ) {
	require_once( ghostpool_inc . 'bp-functions.php' );
}

// bbPress functions
if ( function_exists( 'is_bbpress' ) ) {
	require_once( ghostpool_inc . 'bbpress-functions.php' );
}

// Woocommerce functions
if ( function_exists( 'is_woocommerce' ) ) {
	require_once( ghostpool_inc . 'wc-functions.php' );
}


/////////////////////////////////////// Enqueue Styles ///////////////////////////////////////

if ( ! function_exists( 'ghostpool_enqueue_styles' ) ) {

	function ghostpool_enqueue_styles() { 

		global $socialize;
		
		wp_enqueue_style( 'gp-style', get_stylesheet_uri() );
		
		wp_enqueue_style( 'gp-font-awesome', ghostpool_uri . 'lib/fonts/font-awesome/css/font-awesome.min.css' );
					
		if ( isset( $socialize['lightbox'] ) && $socialize['lightbox'] != 'disabled' ) {
			wp_enqueue_style( 'gp-prettyphoto', ghostpool_scripts_uri . 'prettyPhoto/css/prettyPhoto.css' );
		}
		
		if ( ! empty( $socialize['custom_stylesheet'] ) ) {
			wp_enqueue_style( 'gp-custom-style', ghostpool_uri . $socialize['custom_stylesheet'] );
		}

	}
}

add_action( 'wp_enqueue_scripts', 'ghostpool_enqueue_styles' );
 
			
/////////////////////////////////////// Enqueue Scripts ///////////////////////////////////////
		
if ( ! function_exists( 'ghostpool_enqueue_scripts' ) ) {

	function ghostpool_enqueue_scripts() {

		global $socialize, $post;
		
		wp_enqueue_script( 'gp-modernizr', ghostpool_scripts_uri . 'modernizr.js', false, '', true );
				
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) { 
			wp_enqueue_script( 'comment-reply' );
		}

		if ( $socialize['smooth_scrolling'] == 'gp-smooth-scrolling' ) { 
			wp_enqueue_script( 'gp-nicescroll', ghostpool_scripts_uri . 'nicescroll.min.js', false, '', true );
		}
		
		wp_enqueue_script( 'gp-selectivizr', ghostpool_scripts_uri . 'selectivizr.min.js', false, '', true );

		wp_enqueue_script( 'gp-placeholder', ghostpool_scripts_uri . 'placeholders.min.js', false, '', true );
									
		if ( isset( $socialize['lightbox'] ) && $socialize['lightbox'] != 'disabled' ) {							
			wp_enqueue_script( 'gp-prettyphoto', ghostpool_scripts_uri . 'prettyPhoto/js/jquery.prettyPhoto.js', array( 'jquery' ), '', true );
		}

		if ( $socialize['back_to_top'] == 'gp-back-to-top' ) { 
			wp_enqueue_script( 'gp-back-to-top', ghostpool_scripts_uri . 'jquery.ui.totop.min.js', array( 'jquery' ), '', true );
		}
							
		wp_enqueue_script( 'gp-custom-js', ghostpool_scripts_uri . 'custom.js', array( 'jquery' ), '', true );

		wp_localize_script( 'gp-custom-js', 'ghostpool_script', array(
			'lightbox' => $socialize['lightbox'],
		) );	

		wp_register_script( 'gp-flexslider', ghostpool_scripts_uri . 'jquery.flexslider-min.js', array( 'jquery' ), '', true );
		
		wp_register_script( 'gp-isotope', ghostpool_scripts_uri . 'isotope.pkgd.min.js', false, '', true );
				
		wp_register_script( 'gp-images-loaded', ghostpool_scripts_uri . 'imagesLoaded.min.js', false, '', true );

		wp_register_script( 'gp-stellar', ghostpool_scripts_uri . 'jquery.stellar.min.js', array( 'jquery' ), '', true );

		wp_register_script( 'gp-video-header', ghostpool_scripts_uri . 'jquery.video-header.js', array( 'jquery' ), '', true );
						
	}
}

add_action( 'wp_enqueue_scripts', 'ghostpool_enqueue_scripts' );


/////////////////////////////////////// Enqueue Admin Styles ///////////////////////////////////////

if ( ! function_exists( 'ghostpool_enqueue_admin_styles' ) ) {
	function ghostpool_enqueue_admin_styles( $gp_hook ) {
			wp_enqueue_style( 'gp-admin ', ghostpool_css_uri . 'admin.css' );
	}
}
add_action( 'admin_enqueue_scripts', 'ghostpool_enqueue_admin_styles' );	
	

/////////////////////////////////////// Enqueue Admin Scripts ///////////////////////////////////////

if ( ! function_exists( 'ghostpool_enqueue_admin_scripts' ) ) {
	function ghostpool_enqueue_admin_scripts( $gp_hook ) {
		if ( 'post.php' == $gp_hook OR 'post-new.php' == $gp_hook ) {
			wp_enqueue_script( 'gp-admin ', ghostpool_scripts_uri . 'admin.js', '', '', true );
		}
	}
}
add_action( 'admin_enqueue_scripts', 'ghostpool_enqueue_admin_scripts' );	


/////////////////////////////////////// WP Header Hooks ///////////////////////////////////////

if ( ! function_exists( 'ghostpool_wp_header' ) ) {

	function ghostpool_wp_header() {
	
		global $socialize;

		// Title fallback for versions earlier than WordPress 4.1
		if ( ! function_exists( '_wp_render_title_tag' ) && ! function_exists( 'ghostpool_render_title' ) ) {
			function ghostpool_render_title() { ?>
				<title><?php wp_title( '|', true, 'right' ); ?></title>
			<?php }
		}

		// Initial variables - variables loaded only once at the top of the page
		ghostpool_init_variables();
				
		// Style settings
		require_once( ghostpool_inc . 'style-settings.php' );

		echo '<!--[if gte IE 9]><style>.gp-slider-wrapper .gp-slide-caption + .gp-post-thumbnail:before,body:not(.gp-full-page-page-header) .gp-page-header.gp-has-text:before,body:not(.gp-full-page-page-header) .gp-page-header.gp-has-teaser-video.gp-has-text .gp-video-header:before{filter: none;}</style><![endif]-->';

		// Javascript code
		if ( isset( $socialize['js_code'] ) && ( ! ctype_space( $socialize['js_code'] ) && ! empty( $socialize['js_code'] ) ) ) {
			$socialize['js_code'] = str_replace( array( '<script>', '</script>' ), '', $socialize['js_code'] );
			echo '<script>' . $socialize['js_code'] . '</script>';
		}	
		
	}
	
}

add_action( 'wp_head', 'ghostpool_wp_header' );


/////////////////////////////////////// Navigation Menus ///////////////////////////////////////

if ( ! function_exists( 'ghostpool_register_menus' ) ) {
	function ghostpool_register_menus() {
		register_nav_menus(array(
			'gp-primary-main-header-nav' => esc_html__( 'Primary Main Header Navigation', 'socialize' ),
			'gp-secondary-main-header-nav' => esc_html__( 'Secondary Main Header Navigation', 'socialize' ),
			'gp-left-small-header-nav'    => esc_html__( 'Left Small Header Navigation', 'socialize' ),
			'gp-right-small-header-nav' => esc_html__( 'Right Small Header Navigation', 'socialize' ),
			'gp-footer-nav' => esc_html__( 'Footer Navigation', 'socialize' ),
		) );
	}
}
add_action( 'init', 'ghostpool_register_menus' );


/////////////////////////////////////// Navigation User Meta ///////////////////////////////////////

if ( ! function_exists( 'ghostpool_nav_user_meta' ) ) {
	function ghostpool_nav_user_meta( $gp_user_id = NULL ) {

		// These are the metakeys we will need to update
		$GLOBALS['ghostpool_meta_key']['menus'] = 'metaboxhidden_nav-menus';
		$GLOBALS['ghostpool_meta_key']['properties'] = 'managenav-menuscolumnshidden';

		// So this can be used without hooking into user_register
		if ( ! $gp_user_id ) {
			$gp_user_id = get_current_user_id(); 
		}
	
		// Set the default hiddens if it has not been set yet
		if ( ! get_user_meta( $gp_user_id, $GLOBALS['ghostpool_meta_key']['menus'], true ) ) {
			$gp_meta_value = array( 'add-gp_slides', 'add-gp_slide' );
			update_user_meta( $gp_user_id, $GLOBALS['ghostpool_meta_key']['menus'], $gp_meta_value );
		}

		// Set the default properties if it has not been set yet
		if ( ! get_user_meta( $gp_user_id, $GLOBALS['ghostpool_meta_key']['properties'], true) ) {
			$gp_meta_value = array( 'link-target', 'xfn', 'description' );
			update_user_meta( $gp_user_id, $GLOBALS['ghostpool_meta_key']['properties'], $gp_meta_value );
		}
	
	}	
}
add_action( 'admin_init', 'ghostpool_nav_user_meta' );


/////////////////////////////////////// Image sizes ///////////////////////////////////////

/*if ( ! function_exists( 'ghostpool_image_sizes' ) ) {
	function ghostpool_image_sizes() {
	
		global $socialize;
			
		add_image_size( 'gp-post-image', $socialize['post_image']['width'], $socialize['post_image']['height'], $socialize['post_hard_crop'] );
		add_image_size( 'gp-post-image-retina', $socialize['post_image']['width']*2, $socialize['post_image']['height']*2, $socialize['post_hard_crop'] );
		
		add_image_size( 'gp-menu-image', 270, 140, true );		
		add_image_size( 'gp-menu-image-retina', 270*2, 140*2, true );
		
		
		
	}
}	
add_action( 'after_setup_theme', 'ghostpool_image_sizes' );*/


/////////////////////////////////////// Featured images ///////////////////////////////////////

/*if ( ! function_exists( 'ghostpool_featured_image' ) ) {
	function ghostpool_featured_image( $gp_name, $gp_width, $gp_height, $gp_crop ) {

		global $socialize;
		
		add_image_size( $gp_name, $gp_width, $gp_height, $gp_crop );

		if ( $socialize['retina'] == 'gp-retina' ) {
			$gp_retina = wp_get_attachment_image_src( get_post_thumbnail_id(), $gp_name . '-retina' );
			$gp_retina = $gp_retina[0];
		} else {
			$gp_retina = '';
		}
								
		the_post_thumbnail( $gp_name, array( 
			'class'    => 'gp-post-image', 
			'data-rel' => $gp_retina,
			'alt'      => get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ? esc_attr__( get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ) : the_title_attribute( 'echo=0' ),
		) );	
		
	}										
}*/

	
/////////////////////////////////////// Insert schema meta data ///////////////////////////////////////

if ( ! function_exists( 'ghostpool_meta_data' ) ) {
	function ghostpool_meta_data( $gp_post_id ) {
	
		global $socialize, $post;
		
		$gp_global = get_option( 'socialize' );

		// Get title
		if ( ! empty( $GLOBALS['ghostpool_custom_title'] ) ) { 
			$gp_title = esc_attr( $GLOBALS['ghostpool_custom_title'] );
		} else {
			$gp_title = get_the_title( $gp_post_id );
		}
		
		// Logo URL protocol
		$gp_protocol = ( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' OR $_SERVER['SERVER_PORT'] == 443 ) ? 'https:' : 'http:';
		$gp_global['desktop_logo']['url'] = $gp_protocol . str_replace( array( 'http:', 'https:' ), '', $gp_global['desktop_logo']['url'] );

		// Meta data
		return '<meta itemprop="mainEntityOfPage" itemType="https://schema.org/WebPage" content="' . esc_url( get_permalink( $gp_post_id ) ) . '">
		<meta itemprop="headline" content="' . esc_attr( $gp_title ) . '">			
		<div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
			<meta itemprop="url" content="' . esc_url( wp_get_attachment_url( get_post_thumbnail_id( $gp_post_id ) ) ) . '">
			<meta itemprop="width" content="' .  absint( $gp_global['post_image']['width'] ) . '">	
			<meta itemprop="height" content="' . absint( $gp_global['post_image']['height'] ) . '">		
		</div>
		<meta itemprop="author" content="' . get_the_author_meta( 'display_name', $post->post_author ) . '">			
		<meta itemprop="datePublished" content="' . get_the_time( 'Y-m-d' ) . '">
		<meta itemprop="dateModified" content="' . get_the_modified_date( 'Y-m-d' ) . '">
		<div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
			<div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
				<meta itemprop="url" content="' . esc_url( $gp_global['desktop_logo']['url'] ) . '">
				<meta itemprop="width" content="' . absint( $gp_global['desktop_logo_dimensions']['width'] ) . '">
				<meta itemprop="height" content="' . absint( $gp_global['desktop_logo_dimensions']['height'] ) . '">
			</div>
			<meta itemprop="name" content="' . get_bloginfo( 'name' ) . '">
		</div>';

	}
}


/////////////////////////////////////// Insert breadcrumbs ///////////////////////////////////////

if ( ! function_exists( 'ghostpool_breadcrumbs' ) ) {
	function ghostpool_breadcrumbs() {

		if ( function_exists( 'yoast_breadcrumb' ) && ! is_front_page() ) { 
			$gp_breadcrumbs = yoast_breadcrumb( '<div id="gp-breadcrumbs">', '</div>' );
		} else {
			$gp_breadcrumbs = '';
		}

	}
}


/////////////////////////////////////// Sidebars/Widgets ///////////////////////////////////////

// Categories Widget
require_once( ghostpool_widgets . 'categories.php' );
	
// Recent Comments Widget
require_once( ghostpool_widgets . 'recent-comments.php' );

// Recent Posts Widget
require_once( ghostpool_widgets . 'recent-posts.php' );

if ( ! function_exists( 'ghostpool_widgets_init' ) ) {
	function ghostpool_widgets_init() {

		// Sidebars
		register_sidebar( array( 
			'name'          => esc_html__( 'Left Sidebar', 'socialize' ),
			'id'            => 'gp-left-sidebar',
			'description'   => esc_html__( 'Displayed on posts, pages and post categories.', 'socialize' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		) );

		register_sidebar( array( 
			'name'          => esc_html__( 'Right Sidebar', 'socialize' ),
			'id'            => 'gp-right-sidebar',
			'description'   => esc_html__( 'Displayed on posts, pages and post categories.', 'socialize' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		) );

		register_sidebar( array( 
			'name'          => esc_html__( 'Homepage Left Sidebar', 'socialize' ),
			'id'            => 'gp-homepage-left-sidebar',
			'description'   => esc_html__( 'Displayed on the homepage.', 'socialize' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		) );

		register_sidebar( array( 
			'name'          => esc_html__( 'Homepage Right Sidebar', 'socialize' ),
			'id'            => 'gp-homepage-right-sidebar',
			'description'   => esc_html__( 'Displayed on the homepage.', 'socialize' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		) );
				
		register_sidebar( array(
			'name'          => esc_html__( 'Footer 1', 'socialize' ),
			'id'            => 'gp-footer-1',
			'description'   => esc_html__( 'Displayed as the first column in the footer.', 'socialize' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		) );        

		register_sidebar( array(
			'name'          => esc_html__( 'Footer 2', 'socialize' ),
			'id'            => 'gp-footer-2',
			'description'   => esc_html__( 'Displayed as the second column in the footer.', 'socialize' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		) );        
	
		register_sidebar( array(
			'name'          => esc_html__( 'Footer 3', 'socialize' ),
			'id'            => 'gp-footer-3',
			'description'   => esc_html__( 'Displayed as the third column in the footer.', 'socialize' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		) );        
	
		register_sidebar( array(
			'name'          => esc_html__( 'Footer 4', 'socialize' ),
			'id'            => 'gp-footer-4',
			'description'   => esc_html__( 'Displayed as the fourth column in the footer.', 'socialize' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		) );      

		register_sidebar( array(
			'name'          => esc_html__( 'Footer 5', 'socialize' ),
			'id'            => 'gp-footer-5',
			'description'   => esc_html__( 'Displayed as the fifth column in the footer.', 'socialize' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		) );

		// Deprecated since v1.1
		register_sidebar( array( 
			'name'          => esc_html__( 'Standard Sidebar (Deprecated)', 'socialize' ),
			'id'            => 'gp-standard-sidebar',
			'description'   => esc_html__( 'Displayed on posts, pages and post categories.', 'socialize' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widgettitle">',
			'after_title'   => '</h3>',
		) );
			
	}
}
add_action( 'widgets_init', 'ghostpool_widgets_init' );


/////////////////////////////////////// Excerpts ///////////////////////////////////////

// Character Length
if ( ! function_exists( 'ghostpool_excerpt_length' ) ) {
	function ghostpool_excerpt_length() {
		if ( function_exists( 'buddyboss_global_search_init' ) && is_search() ) {
			return 50;
		} else {
			return 10000;
		}	
	}
}
add_filter( 'excerpt_length', 'ghostpool_excerpt_length' );

// Excerpt Output
if ( ! function_exists( 'ghostpool_excerpt' ) ) {
	function ghostpool_excerpt( $gp_length ) {
		global $socialize;
		if ( isset( $GLOBALS['ghostpool_read_more_link'] ) && $GLOBALS['ghostpool_read_more_link'] == 'enabled' ) {
			$gp_more_text = '...<a href="' . esc_url( get_permalink( get_the_ID() ) ) . '" class="gp-read-more" title="' . the_title_attribute( 'echo=0' ) . '">' . esc_html__( 'Read More', 'socialize' ) . '</a>';
		} else {
			$gp_more_text = '...';
		}	
		
		if ( get_post_meta( get_the_ID(), 'video_description', true ) ) {
			$gp_excerpt = get_post_meta( get_the_ID(), 'video_description', true );
		} else {
			$gp_excerpt = get_the_excerpt();
		}
					
		$gp_excerpt = strip_tags( $gp_excerpt );
		if ( function_exists( 'mb_strlen' ) && function_exists( 'mb_substr' ) ) { 
			if ( mb_strlen( $gp_excerpt ) > $gp_length ) {
				$gp_excerpt = mb_substr( $gp_excerpt, 0, $gp_length ) . $gp_more_text;
			}
		} else {
			if ( strlen( $gp_excerpt ) > $gp_length ) {
				$gp_excerpt = substr( $gp_excerpt, 0, $gp_length ) . $gp_more_text;
			}	
		}
		return $gp_excerpt;
	}
}


/////////////////////////////////////// Add Excerpt Support To Pages ///////////////////////////////////////

if ( ! function_exists( 'ghostpool_add_excerpts_to_pages' ) ) {
	function ghostpool_add_excerpts_to_pages() {
		 add_post_type_support( 'page', 'excerpt' );
	}
}
add_action( 'init', 'ghostpool_add_excerpts_to_pages' );


/////////////////////////////////////// Add post tags to pages ///////////////////////////////////////	

if ( ! function_exists( 'ghostpool_page_tags_support' ) ) {
	function ghostpool_page_tags_support() {
		register_taxonomy_for_object_type( 'post_tag', 'page' );
	}
}
add_action( 'init', 'ghostpool_page_tags_support' );

if ( ! function_exists( 'ghostpool_page_tags_support_query' ) ) {
	function ghostpool_page_tags_support_query( $wp_query ) {
		if ( $wp_query->get( 'tag' ) ) {
			$wp_query->set( 'post_type', 'any' );
		}	
	}
}
add_action( 'pre_get_posts', 'ghostpool_page_tags_support_query' );


/////////////////////////////////////// Prefix portfolio categories ///////////////////////////////////////	

if ( ! function_exists( 'ghostpool_add_prefix_to_terms' ) ) {
	function ghostpool_add_prefix_to_terms( $gp_term_id, $gp_tt_id, $gp_taxonomy ) {
		global $socialize;
		if ( $gp_taxonomy == 'gp_portfolios' && ! empty( $socialize['portfolio_cat_prefix_slug'] ) ) {
			$gp_term = get_term( $gp_term_id, $gp_taxonomy );
			$gp_args = array( 'slug' => $socialize['portfolio_cat_prefix_slug'] . '-' . $gp_term->slug );
			wp_update_term( $gp_term_id, $gp_taxonomy, $gp_args );
		} 
	}
}
add_action( 'created_term', 'ghostpool_add_prefix_to_terms', 10, 3 );


/////////////////////////////////////// Change Password Protect Post Text ///////////////////////////////////////	

if ( ! function_exists( 'ghostpool_password_form' ) ) {
	function ghostpool_password_form() {
		global $post;
		$gp_label = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
		$gp_o = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">
		<p>' . esc_html__( 'To view this protected post, enter the password below:', 'socialize' ) . '</p>
		<label for="' . $gp_label . '"><input name="post_password" id="' . $gp_label . '" type="password" size="20" maxlength="20" /></label> <input type="submit" class="pwsubmit" name="Submit" value="' .  esc_attr__( 'Submit', 'socialize' ) . '" />
		</form>
		';
		return $gp_o;
	}
}
add_filter( 'the_password_form', 'ghostpool_password_form' );


/////////////////////////////////////// Redirect Empty Search To Search Page ///////////////////////////////////////	

if ( ! function_exists( 'ghostpool_empty_search' ) ) {
	function ghostpool_empty_search( $gp_query ) {
		global $wp_query;
		if ( isset( $_GET['s'] ) && ( $_GET['s'] == '' ) ) {
			$wp_query->set( 's', ' ' );
			$wp_query->is_search = true;
		}
		return $gp_query;
	}
}
add_action( 'pre_get_posts', 'ghostpool_empty_search' );


/////////////////////////////////////// Alter category queries ///////////////////////////////////////	

if ( ! function_exists( 'ghostpool_category_queries' ) ) {
	function ghostpool_category_queries( $gp_query ) {
		global $socialize;			
		if ( is_admin() OR ! $gp_query->is_main_query() ) { 
			return;
		} else {
			if ( is_post_type_archive( 'gp_portfolio_item' ) OR is_tax( 'gp_portfolios' ) )  {
				$GLOBALS['ghostpool_orderby'] = $socialize['portfolio_cat_orderby'];
				$GLOBALS['ghostpool_per_page'] = $socialize['portfolio_cat_per_page'];
				$GLOBALS['ghostpool_date_posted'] = $socialize['portfolio_cat_date_posted'];
				$GLOBALS['ghostpool_date_modified'] = $socialize['portfolio_cat_date_modified'];
			} elseif ( is_author() ) {
				$GLOBALS['ghostpool_orderby'] = $socialize['search_orderby'];
				$GLOBALS['ghostpool_per_page'] = $socialize['search_per_page'];
				$GLOBALS['ghostpool_date_posted'] = $socialize['search_date_posted'];
				$GLOBALS['ghostpool_date_modified'] = $socialize['search_date_modified'];
			} elseif ( is_search() OR is_author() ) {
				$GLOBALS['ghostpool_orderby'] = $socialize['search_orderby'];
				$GLOBALS['ghostpool_per_page'] = $socialize['search_per_page'];
				$GLOBALS['ghostpool_date_posted'] = $socialize['search_date_posted'];
				$GLOBALS['ghostpool_date_modified'] = $socialize['search_date_modified'];				
			} elseif ( is_home() OR is_archive() ) {
				$GLOBALS['ghostpool_orderby'] = $socialize['cat_orderby'];
				$GLOBALS['ghostpool_per_page'] = $socialize['cat_per_page'];
				$GLOBALS['ghostpool_date_posted'] = $socialize['cat_date_posted'];
				$GLOBALS['ghostpool_date_modified'] = $socialize['cat_date_modified'];
			}
			if ( isset( $GLOBALS['ghostpool_per_page'] ) ) {
				ghostpool_loop_variables();
				ghostpool_category_variables();
				$gp_query->set( 'posts_per_page', $GLOBALS['ghostpool_per_page'] );
				if ( ! is_search() ) {
					$gp_query->set( 'orderby', $GLOBALS['ghostpool_orderby_value'] );	
					$gp_query->set( 'order', $GLOBALS['ghostpool_order'] );
					$gp_query->set( 'meta_key', $GLOBALS['ghostpool_meta_key'] );
				}
				$gp_query->set( 'date_query', array( $GLOBALS['ghostpool_date_posted_value'], $GLOBALS['ghostpool_date_modified_value'] ) );
				return;
			}	
		}
	}
}	
add_action( 'pre_get_posts', 'ghostpool_category_queries', 1 );


/////////////////////////////////////// Pagination ///////////////////////////////////////	

if ( ! function_exists( 'ghostpool_pagination' ) ) {
	function ghostpool_pagination( $gp_query ) {
		$gp_big = 999999999;
		if ( get_query_var( 'paged' ) ) {
			$gp_paged = get_query_var( 'paged' );
		} elseif ( get_query_var( 'page' ) ) {
			$gp_paged = get_query_var( 'page' );
		} else {
			$gp_paged = 1;
		}
		if ( $gp_query >  1 ) {
			return '<div class="gp-pagination gp-pagination-numbers gp-standard-pagination">' . paginate_links( array(
				'base'      => str_replace( $gp_big, '%#%', esc_url( get_pagenum_link( $gp_big ) ) ),
				'format'    => '?paged=%#%',
				'current'   => max( 1, $gp_paged ),
				'total'     => $gp_query,
				'type'      => 'list',
				'prev_text' => '',
				'next_text' => '',
				'end_size'  => 1,
				'mid_size'  => 1, 
			) ) . '</div>';
		}
	}
}

if ( ! function_exists( 'ghostpool_get_previous_posts_page_link' ) ) {
	function ghostpool_get_previous_posts_page_link() {
		global $paged;
		$gp_nextpage = intval( $paged ) - 1;
		if ( $gp_nextpage < 1 ) {
			$gp_nextpage = 1;
		}	
		if ( $paged > 1 ) {
			return '<a href="#" data-pagelink="' . esc_attr( $gp_nextpage ) . '" class="prev"></a>';
		} else {
			return '<span class="prev gp-disabled"></span>';
		}
	}
}		

if ( ! function_exists( 'ghostpool_get_next_posts_page_link' ) ) {
	function ghostpool_get_next_posts_page_link( $gp_max_page = 0 ) {
		global $paged;
		if ( ! $paged ) {
			$paged = 1;
		}	
		$gp_nextpage = intval( $paged ) + 1;
		if ( ! $gp_max_page || $gp_max_page >= $gp_nextpage ) {
			return '<a href="#" data-pagelink="' . esc_attr( $gp_nextpage ) . '" class="next"></a>';
		} else {
			return '<span class="next gp-disabled"></span>';
		}
	}
}


/////////////////////////////////////// Canonical, next and prev rel links on page templates ///////////////////////////////////////	

if ( ! function_exists( 'ghostpool_rel_prev_next' ) && function_exists( 'wpseo_auto_load' ) ) {
	function ghostpool_rel_prev_next() {
		if ( is_page_template( 'blog-template.php' ) OR is_page_template( 'portfolio-template.php' ) ) {
		
			global $paged;
		
			// Load page variables
			ghostpool_loop_variables();
			ghostpool_category_variables();
			
			if ( is_page_template( 'blog-template.php' ) ) {

				$gp_args = array(
					'post_status' 	      => 'publish',
					'post_type'           => explode( ',', $GLOBALS['ghostpool_post_types'] ),
					'tax_query'           => $GLOBALS['ghostpool_tax'],
					'orderby'             => $GLOBALS['ghostpool_orderby_value'],
					'order'               => $GLOBALS['ghostpool_order'],
					'meta_key'            => $GLOBALS['ghostpool_meta_key'],
					'posts_per_page'      => $GLOBALS['ghostpool_per_page'],
					'paged'               => $GLOBALS['ghostpool_paged'],
					'date_query'          => array( $GLOBALS['ghostpool_date_posted_value'], $GLOBALS['ghostpool_date_modified_value'] ),	
				);

			} else {

				$gp_args = array(
					'post_status'         => 'publish',
					'post_type'           => 'gp_portfolio_item',
					'tax_query'           => $GLOBALS['ghostpool_tax'],
					'posts_per_page'      => $GLOBALS['ghostpool_per_page'],
					'orderby'             => $GLOBALS['ghostpool_orderby_value'],
					'order'               => $GLOBALS['ghostpool_order'],
					'paged'               => $GLOBALS['ghostpool_paged'],
					'date_query'          => array( $GLOBALS['ghostpool_date_posted_value'], $GLOBALS['ghostpool_date_modified_value'] ),	
				);
					
			}	

			// Contains query data
			$gp_query = new wp_query( $gp_args );
			
			// Get maximum pages from query
			$gp_max_page = $gp_query->max_num_pages;
			
			if ( ! $paged ) {
				$paged = 1;
			}
		
			// Prev rel link
			$gp_prevpage = intval( $paged ) - 1;
			if ( $gp_prevpage < 1 ) {
				$gp_prevpage = 1;
			}	
			if ( $paged > 1 ) {
				echo '<link rel="prev" href="' . get_pagenum_link( $gp_prevpage ) . '">';
			}
		
			// Next rel link
			$gp_nextpage = intval( $paged ) + 1;	
			if ( ! $gp_max_page OR $gp_max_page >= $gp_nextpage ) {
				echo '<link rel="next" href="' . get_pagenum_link( $gp_nextpage ) . '">';
			}

			// Meta noindex,follow on paginated page templates
			if ( ( is_page_template( 'blog-template.php' ) OR is_page_template( 'portfolio-template.php' ) ) && $paged > 1 ) {
				echo '<meta name="robots" content="noindex,follow">';
			}
					
		}
	}
	add_action( 'wp_head', 'ghostpool_rel_prev_next' );
}
	
if ( ! function_exists( 'ghostpool_canonical_link' ) && function_exists( 'wpseo_auto_load' ) ) {	
	function ghostpool_canonical_link( $gp_canonical ) {
		if ( is_page_template( 'blog-template.php' ) OR is_page_template( 'portfolio-template.php' ) ) {
			global $paged;		
			if ( ! $paged ) {
				$paged = 1;
			}
			return get_pagenum_link( $paged );
		} else {
			return $gp_canonical;
		}
	}
	add_filter( 'wpseo_canonical', 'ghostpool_canonical_link' );
}


/////////////////////////////////////// Exclude categories ///////////////////////////////////////	

if ( ! function_exists( 'ghostpool_exclude_cats' ) ) {
	function ghostpool_exclude_cats( $gp_post_id, $gp_no_link = false, $gp_loop ) {
	
		global $socialize;
					
		// Get categories for post
		$gp_cats = wp_get_object_terms( $gp_post_id, 'category', array( 'fields' => 'ids' ) );
		
		// Remove categories that are excluded
		if ( isset( $socialize['cat_exclude_cats'] ) && ! empty( $socialize['cat_exclude_cats'] ) ) { 
			$gp_excluded_cats = array_diff( $gp_cats, $socialize['cat_exclude_cats'] );
		} else {
			$gp_excluded_cats = $gp_cats;
		}
		
		// Construct new categories loop
		if ( ! empty( $gp_excluded_cats ) && ! is_wp_error( $gp_excluded_cats ) ) { 		
			$gp_cat_link = '';
			foreach( $gp_excluded_cats as $gp_excluded_cat ) {
				if ( has_term( $gp_excluded_cat, 'category', $gp_post_id ) ) {
					$gp_term = get_term( $gp_excluded_cat, 'category' );
					$gp_term_link = get_term_link( $gp_term, 'category' );
					if ( ! $gp_term_link OR is_wp_error( $gp_term_link ) ) {
						continue;
					}
					if ( $gp_no_link == true ) {
						$gp_cat_link .= esc_attr( $gp_term->name ) . ' / ';
					} else {
						$gp_cat_link .= '<a href="' . esc_url( $gp_term_link ) . '">' . esc_attr( $gp_term->name ) . '</a> / ';
					}
				}
			}
			if ( $gp_loop == true ) {
				return '<div class="gp-loop-cats">' . rtrim( $gp_cat_link, ' / ' ) . '</div>';
			} else {			
				return '<div class="gp-entry-cats">' . rtrim( $gp_cat_link, ' / ' ) . '</div>';
			}
		}

	}
}
		

/////////////////////////////////////// Redirect wp-login.php to popup login form ///////////////////////////////////////	

if ( ! function_exists( 'ghostpool_login_redirect' ) ) {
	function ghostpool_login_redirect() {
		global $socialize, $pagenow;
		if ( 'wp-login.php' == $pagenow && ( isset ( $socialize['popup_box'] ) && $socialize['popup_box'] == 'enabled' ) ) {
		
			if ( isset( $_GET['action'] ) && $_GET['action'] == 'register' ) {
				wp_redirect( esc_url( home_url( '#register/' ) ) ); // Open registration modal window
			} elseif ( isset( $_GET['action'] ) && $_GET['action'] == 'lostpassword' ) {
				wp_redirect( esc_url( home_url( '#lost-password/' ) ) ); // Open lost password modal window
			} elseif ( isset( $_GET['action'] ) && $_GET['action'] == 'bpnoaccess' ) {
 				wp_redirect( esc_url( home_url( '#login/' ) ) ); // If there are specific actions open login modal window
			} elseif ( ! isset( $_POST['wp-submit'] ) && ! isset( $_GET['checkemail'] ) && ! isset( $_GET['action'] ) && ! isset( $_GET['reauth'] ) && ! isset( $_GET['interim-login'] ) ) {
				wp_redirect( esc_url( home_url( '#login/' ) ) ); // If there are no actions open login modal window
			} else {
				return;
			}

			exit();
		}
	}
}
add_action( 'init', 'ghostpool_login_redirect' );


/////////////////////////////////////// Add reset password success message to home page ///////////////////////////////////////	

if ( isset( $_GET['action'] ) && $_GET['action'] == 'reset_success' ) {
	if ( ! function_exists( 'ghostpool_reset_password_success' ) ) {
		function ghostpool_reset_password_success() {
			echo '<div id="gp-reset-message"><p>' . esc_html__( "You will receive an email with your new password.", "socialize" ) . '<span id="gp-close-reset-message"></span></p></div>';
		}
	}
	add_action( 'wp_footer', 'ghostpool_reset_password_success' );
}	


/////////////////////////////////////// Remove hentry tag ///////////////////////////////////////	

if ( ! function_exists( 'ghostpool_remove_hentry' ) ) {
	function ghostpool_remove_hentry( $gp_classes ) {
		$gp_classes = array_diff( $gp_classes, array( 'hentry' ) );
		return $gp_classes;
	}
}
add_filter( 'post_class', 'ghostpool_remove_hentry' );


/////////////////////////////////////// Add user profile fields ///////////////////////////////////////	

if ( ! function_exists( 'ghostpool_custom_profile_methods' ) ) {
	function ghostpool_custom_profile_methods( $gp_profile_fields ) {
		$gp_profile_fields['twitter'] = esc_html__( 'Twitter URL', 'socialize' );
		$gp_profile_fields['facebook'] = esc_html__( 'Facebook URL', 'socialize' );
		$gp_profile_fields['googleplus'] = esc_html__( 'Google+ URL', 'socialize' );
		$gp_profile_fields['pinterest'] = esc_html__( 'Pinterest URL', 'socialize' );
		$gp_profile_fields['youtube'] = esc_html__( 'YouTube URL', 'socialize' );
		$gp_profile_fields['vimeo'] = esc_html__( 'Vimeo URL', 'socialize' );
		$gp_profile_fields['flickr'] = esc_html__( 'Flickr URL', 'socialize' );
		$gp_profile_fields['linkedin'] = esc_html__( 'LinkedIn URL', 'socialize' );
		$gp_profile_fields['instagram'] = esc_html__( 'Instagram URL', 'socialize' );
		return $gp_profile_fields;
	}
}
add_filter( 'user_contactmethods', 'ghostpool_custom_profile_methods' );


/////////////////////////////////////// Add lightbox class to image links ///////////////////////////////////////	

if ( ! function_exists( 'ghostpool_lightbox_image_link' ) ) {
	function ghostpool_lightbox_image_link( $gp_content ) {	
		global $socialize, $post;
		if ( isset( $socialize['lightbox'] ) && $socialize['lightbox'] != 'disabled' ) {
			if ( $socialize['lightbox'] == 'group_images' ) {
				$gp_group = '[image-' . $post->ID . ']';
			} else {
				$gp_group = '';
			}
			$gp_pattern = "/<a(.*?)href=('|\")(.*?).(jpg|jpeg|png|gif|bmp|ico)('|\")(.*?)>/i";
			preg_match_all( $gp_pattern, $gp_content, $gp_matches, PREG_SET_ORDER );
			foreach ( $gp_matches as $gp_val ) {
				$gp_pattern = '<a' . $gp_val[1] . 'href=' . $gp_val[2] . $gp_val[3] . '.' . $gp_val[4] . $gp_val[5] . $gp_val[6] . '>';
				$gp_replacement = '<a' . $gp_val[1] . 'href=' . $gp_val[2] . $gp_val[3] . '.' . $gp_val[4] . $gp_val[5] . ' data-rel="prettyPhoto' . $gp_group . '"' . $gp_val[6] . '>';
				$gp_content = str_replace( $gp_pattern, $gp_replacement, $gp_content );			
			}
			return $gp_content;
		} else {
			return $gp_content;
		}
	}	
}
add_filter( 'the_content', 'ghostpool_lightbox_image_link' );	
add_filter( 'wp_get_attachment_link', 'ghostpool_lightbox_image_link' );
add_filter( 'bbp_get_reply_content', 'ghostpool_lightbox_image_link' );

		
/////////////////////////////////////// Post Views Counter plugin defaults ///////////////////////////////////////	

if ( class_exists( 'Post_Views_Counter_Settings' ) && get_option( 'ghostpool_pvc_defaults' ) !== '1' ) {
	function ghostpool_pvc_defaults() {	
		update_option( 'post_views_counter_settings_general', array( 'post_types_count' => array( 'page', 'post' ) ) );
		update_option( 'post_views_counter_settings_display', array( 'position' => 'manual' ) );
	}	
	add_action( 'init', 'ghostpool_pvc_defaults', 1 );	
	update_option( 'ghostpool_pvc_defaults', '1' );		
}

		
/////////////////////////////////////// Visual Sidebars Editor defaults ///////////////////////////////////////	

if ( ! function_exists( 'ghostpool_default_vc_widget_container' ) ) {
	function ghostpool_default_vc_widget_container( $gp_container, $gp_current_sidebar ) {
		$gp_container = 'none';
		return $gp_container;
	}
}
add_filter( 'vcse_default_container', 'ghostpool_default_vc_widget_container', 10, 2 );

if ( ! function_exists( 'ghostpool_default_vc_widget_behavior' ) ) {
	function ghostpool_default_vc_widget_behavior( $gp_behavior, $gp_current_sidebar ) {
		$gp_behavior = 'replace';
		return $gp_behavior;
	}
}
add_filter( 'vcse_default_behavior', 'ghostpool_default_vc_widget_behavior', 10, 2 );

	
/////////////////////////////////////// Visual Composer ///////////////////////////////////////	

if ( function_exists( 'vc_set_as_theme' ) ) {
	
	function ghostpool_vc_functions() {
		vc_set_as_theme(); // Disable design options
		vc_set_shortcodes_templates_dir( ghostpool_vc ); // Set templates directory
		vc_set_default_editor_post_types( array( 'page', 'gp_portfolio_item', 'epx_vcsb', 'vc-element' ) ); // Check VC post type checkboxes by default
		//vc_editor_set_post_types( array( 'page', 'gp_portfolio_item', 'epx_vcsb', 'vc-element' ) ); // Enable VC for these post types by default
		//Vc_Manager::getInstance()->disableUpdater(true); // Disable automatic updates
	}
	add_action( 'vc_before_init', 'ghostpool_vc_functions', 9 );
	
}

					
/////////////////////////////////////// TMG Plugin Activation ///////////////////////////////////////	

if ( version_compare( phpversion(), '5.2.4', '>=' ) ) {
	require_once( ghostpool_inc . 'class-tgm-plugin-activation.php' );
}

if ( ! function_exists( 'ghostpool_register_required_plugins' ) ) {
	
	function ghostpool_register_required_plugins() {

		$gp_plugins = array(

			array(
				'name'               => esc_html__( 'Socialize Plugin', 'socialize' ),
				'slug'               => 'socialize-plugin',
				'source'             => ghostpool_plugins . 'socialize-plugin.zip',
				'required'           => true,
				'version'            => '2.1',
				'force_activation'   => true,
				'force_deactivation' => false,
			),

			array(
				'name'               => esc_html__( 'WPBakery Visual Composer', 'socialize' ),
				'slug'               => 'js_composer',
				'source'             => ghostpool_plugins . 'js_composer.zip',
				'required'           => true,
				'version'            => '4.12',
				'force_activation'	 => true,
				'force_deactivation' => false,
			),

			array(
				'name'               => esc_html__( 'Visual Sidebars Editor', 'socialize' ),
				'slug'               => 'visual-sidebars-editor',
				'source'             => ghostpool_plugins . 'visual-sidebars-editor.zip',
				'required'           => true,
				'version'            => '1.0.9',
				'force_activation'	 => false,
				'force_deactivation' => false,
			),
			
			array(
				'name'   		     => esc_html__( 'Theia Sticky Sidebar', 'socialize' ),
				'slug'   		     => 'theia-sticky-sidebar',
				'source'   		     => ghostpool_plugins . 'theia-sticky-sidebar.zip',
				'required'   		 => true,
				'version'   		 => '1.6.3',
				'force_activation'	 => true,
				'force_deactivation' => false,
			),

			array(
				'name'      => esc_html__( 'BuddyPress', 'socialize' ),
				'slug'      => 'buddypress',
				'required' 	=> false,
			),
			
			array(
				'name'      => esc_html__( 'bbPress', 'socialize' ),
				'slug'      => 'bbpress',
				'required' 	=> false,
			),
						
			array(
				'name'      => esc_html__( 'The Events Calendar', 'socialize' ),
				'slug'      => 'the-events-calendar	',
				'required' 	=> false,
			),
												
			array(
				'name'      => esc_html__( 'Contact Form 7', 'socialize' ),
				'slug'      => 'contact-form-7',
				'required' 	=> false,
			),

			array(
				'name'      => esc_html__( 'WordPress Social Login', 'socialize' ),
				'slug'      => 'wordpress-social-login',
				'required' 	=> false,
			),

			array(
				'name'      => esc_html__( 'Captcha', 'socialize' ),
				'slug'      => 'captcha',
				'required' 	=> false,
			),
			
			array(
				'name'      => esc_html__( 'Post Views Counters', 'socialize' ),
				'slug'      => 'post-views-counter',
				'required' 	=> false,
			),
			
			array(
				'name'      => esc_html__( 'Yoast SEO', 'socialize' ),
				'slug'      => 'wordpress-seo',
				'required' 	=> false,
				'is_callable' => 'wpseo_init',
			),
																							
		);

		$gp_config = array(
			'id'           => 'socialize',            // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => '',                     // Default absolute path to pre-packaged plugins.
			'menu'         => 'tgmpa-install-plugins', // Menu slug.
			'has_notices'  => true,                   // Show admin notices or not.
			'dismissable'  => true,                   // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                     // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => true,                  // Automatically activate plugins after installation or not.
			'message'      => '',                     // Message to output right before the plugins table.
		);
 
		tgmpa( $gp_plugins, $gp_config );

	}
	
}

add_action( 'tgmpa_register', 'ghostpool_register_required_plugins' );

?>