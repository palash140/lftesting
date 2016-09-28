<?php
namespace ERROPiX\VCSE;

class Widget {
	private $post;
	private $widget;
	
	public $id;
	public $id_base = 'epx-vcse-widget';
	public $name = 'Visual Sidebar Editor Widget';
	
	public function __construct( $post ) {
		$this->post = $post;
		
		$this->id = "{$this->id_base}-{$post->ID}";
		
		$this->widget = array(
			'id' => $this->id,
			'name' => $this->name,
			'params' => array(),
			'classname' => __CLASS__,
			'callback' => array($this, 'display'),
		);
	}
	
	public function display($args) {
		global $post, $VSE;
		
		$this->setup_postdata();
		extract( $args );
		
		if( empty( $VSE->styles[$id] ) ) {
			$custom_css = get_post_meta( $post->ID, '_wpb_post_custom_css', true );
			$custom_css.= get_post_meta( $post->ID, '_wpb_shortcodes_custom_css', true );
			$VSE->inline_css( $custom_css, $id );
		}

		$output = '';
		if( function_exists('bbp_restore_all_filters') ) {
			bbp_restore_all_filters('the_content', 0);
		}
		$content = apply_filters( 'the_content', $post->post_content );
		
		if( $post->settings->container == 'default' ) {
			$output.= $before_widget;
			$title = apply_filters( 'widget_title', $post->post_title );
			if( !empty($title) ) {
				$output.= $before_title . $title . $after_title;
			}
			$output.= $content;
			$output.= $after_widget;
		} else {
			$output.= $content;
		}
		echo $output;
		
		$this->reset_postdata();
	}
	
	public function get_widget() {
		return $this->widget;
	}
	
	public function get_settings() {
		return array();
	}
	
	private function setup_postdata() {
		global $wp_query;
		if( is_object($wp_query->post) ) {
			$wp_query->_post = clone $wp_query->post;
		} {
			$wp_query->_post = $wp_query->post;
		}
		$wp_query->post = $this->post;
		$wp_query->reset_postdata();
	}
	private function reset_postdata() {
		global $wp_query;
		if( is_object($wp_query->_post) ) {
			$wp_query->post = clone $wp_query->_post;
		} {
			$wp_query->post = $wp_query->_post;
		}
		$wp_query->reset_postdata();
	}
}