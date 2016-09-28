<?php
/*
Plugin Name: Socialize Plugin
Plugin URI: 
Description: A required plugin for Socialize theme you purchased from ThemeForest. It includes a number of features that you can still use if you switch to another theme.
Version: 2.1
Author: GhostPool
Author URI: http://themeforest.net/user/GhostPool/portfolio?ref=GhostPool
License: You should have purchased a license from ThemeForest.net
Text Domain: socialize
*/

if ( ! class_exists( 'GhostPool_Socialize' ) ) {

	class GhostPool_Socialize {

		public function __construct() {

			// Load plugin translations
			add_action( 'plugins_loaded', array( &$this, 'ghostpool_plugin_load_textdomain' ) );

			if ( ! class_exists( 'GhostPool_Custom_Sidebars' ) ) {
				require_once( sprintf( "%s/custom-sidebars/custom-sidebars.php", dirname( __FILE__ ) ) );
			}

			if ( ! post_type_exists( 'gp_portfolio' ) && ! class_exists( 'GhostPool_Portfolios' ) ) {
				require_once( sprintf( "%s/post-types/portfolio-tax.php", dirname( __FILE__ ) ) );
				$GhostPool_Portfolios = new GhostPool_Portfolios();
			}

			if ( ! post_type_exists( 'gp_slide' ) && ! class_exists( 'GhostPool_Slides' ) ) {
				require_once( sprintf( "%s/post-types/slide-tax.php", dirname( __FILE__ ) ) );
				$GhostPool_Slides = new Ghostpool_Slides();
			}

			/*if ( function_exists( 'vc_set_as_theme' ) && ! class_exists( 'Socialize_Shortcodes' ) ) {
				require_once( sprintf( "%s/theme-shortcodes.php", dirname( __FILE__ ) ) );
				$Socialize_Shortcodes = new Socialize_Shortcodes();
			}*/
																								
		} 
		
		public static function ghostpool_activate() {} 		
		
		public static function ghostpool_deactivate() {}

		public function ghostpool_plugin_load_textdomain() {
			load_plugin_textdomain( 'socialize-plugin', false, trailingslashit( WP_LANG_DIR ) . 'plugins/' );
			load_plugin_textdomain( 'socialize-plugin', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
		}
			
	}
	
}

// User registration emails
$gp_theme_variable = get_option( 'socialize-plugin' );
if ( ! function_exists( 'wp_new_user_notification' ) && ! function_exists( 'bp_is_active' ) && ( isset ( $gp_theme_variable['popup_box'] ) && $gp_theme_variable['popup_box'] == 'enabled' ) ) {
	function wp_new_user_notification( $gp_user_id, $gp_deprecated = null, $gp_notify = 'both' ) {

		if ( $gp_deprecated !== null ) {
			_deprecated_argument( __FUNCTION__, '4.3.1' );
		}
	
		global $wpdb;
		$gp_user = get_userdata( $gp_user_id );
		
		$gp_user_login = stripslashes( $gp_user->user_login );
		$gp_user_email = stripslashes( $gp_user->user_email );

		// The blogname option is escaped with esc_html on the way into the database in sanitize_option
		// we want to reverse this for the plain text arena of emails.
		$gp_blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
		
		// Admin email
		$gp_message  = sprintf( esc_html__( 'New user registration on your blog %s:', 'socialize-plugin' ), $gp_blogname ) . "\r\n\r\n";
		$gp_message .= sprintf( esc_html__( 'Username: %s', 'socialize-plugin' ), $gp_user_login ) . "\r\n\r\n";
		$gp_message .= sprintf( esc_html__( 'Email: %s', 'socialize-plugin' ), $gp_user_email ) . "\r\n";
		$gp_message = apply_filters( 'gp_registration_notice_message', $gp_message, $gp_blogname, $gp_user_login, $gp_user_email );
		@wp_mail( get_option( 'admin_email' ), sprintf( apply_filters( 'gp_registration_notice_subject', esc_html__( '[%s] New User Registration', 'socialize-plugin' ), $gp_blogname ), $gp_blogname ), $gp_message );

		if ( 'admin' === $gp_notify || empty( $gp_notify ) ) {
			return;
		}
		
		// User email
		$gp_message  = esc_html__( 'Hi there,', 'socialize-plugin' ) . "\r\n\r\n";
		$gp_message .= sprintf( esc_html__( 'Welcome to %s.', 'socialize-plugin' ), $gp_blogname ) . "\r\n\r\n";
		$gp_message .= sprintf( esc_html__( 'Username: %s', 'socialize-plugin' ), $gp_user_login ) . "\r\n";
		$gp_message .= esc_html__( 'Password: [use the password you entered when signing up]', 'socialize-plugin' ) . "\r\n\r\n";
		$gp_message .= 'Please login at ' . home_url( '/#login' ) . "\r\n\r\n";	
		$gp_message = apply_filters( 'gp_registered_user_message', $gp_message, $gp_blogname, $gp_user_login, $gp_user_email );
		wp_mail( $gp_user_email, sprintf( apply_filters( 'gp_registered_user_subject', esc_html__( '[%s] Your username and password', 'socialize-plugin' ), $gp_blogname ), $gp_blogname ), $gp_message );

	}
}

// Active/deactivate plugin
if ( class_exists( 'GhostPool_Socialize' ) ) {

	register_activation_hook( __FILE__, array( 'GhostPool_Socialize', 'ghostpool_activate' ) );
	register_deactivation_hook( __FILE__, array( 'GhostPool_Socialize', 'ghostpool_deactivate' ) );

	$ghostpool_plugin = new GhostPool_Socialize();

}

?>