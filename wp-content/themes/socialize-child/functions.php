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

///////////////////////////////////////// CUSTOM FUNCTION GOES HERE ///////////////////////////////////
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

	global $pagenow;

	if (class_exists( 'RTMedia' ) && ($menu->slug == 'main-header-life-menu') && (count($items) > 2) && ($pagenow != "nav-menus.php") && ($pagenow != "customize.php") && !is_admin()) {
	
		$more_menu_item_id = $items[2]->ID;
		
		// get current logged in user id
		$user_id = get_current_user_id();
		$url = trailingslashit ( get_rtmedia_user_link ( get_current_user_id () ) ) . RTMEDIA_MEDIA_SLUG . '/';     // get user's media link

		// add new menu item to nav menu
		$new_item = new stdClass;
		$new_item->menu_item_parent = $more_menu_item_id;
		$new_item->url = $url;
		$new_item->title = 'Member Gallery';
		$new_item->menu_order = count( $items );
		$items[count( $items ) -1]->menu_order = count( $items )+1;

		$tmp_item = $items[count( $items ) -1];
		$items[count( $items ) -1] = $new_item;
		$items[] = $tmp_item;
	}
	return $items;
}
add_filter( 'wp_get_nav_menu_items', 'my_wp_get_nav_menu_items', 99, 3 );


class ReportPageSetting
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
    	global $pagenow;
    	$template_path = get_stylesheet_directory_uri()."/assets/";

        if(isset($_GET['page']) && "report-problem-setting" == trim($_GET['page']))
        {
			wp_enqueue_script ( 'wp_bootstrap_js', $template_path . 'js/bootstrap.min.js', array ('jquery'), '', true );
			wp_enqueue_script ( 'wp_setting_script', $template_path . 'js/script.js', array ('wp_bootstrap_js'), '', true );
			wp_enqueue_style ( 'wp_bootstrap_css', $template_path."css/bootstrap.min.css" );		
        }

        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Report Page Setting', 
            'manage_options', 
            'report-problem-setting', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'gb_report_problem_name' );
        ?>

        <div class="wrap">
            <h1>My Settings</h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'gb_report_problem_group' );
                do_settings_sections( 'report-problem-setting' );
                submit_button();
            ?>
            </form>
        </div>
		<div id="blank_entry" style="display:none">
	         <div class="entry input-group col-md-3" style="margin-bottom:5px;">
	            <input class="form-control" name="select_fields[]" type="text" placeholder="New Item" />
	        	<span class="input-group-btn">
	                <button class="btn btn-success btn-add-more" type="button">
	                    <span class="glyphicon glyphicon-plus"></span>
	                </button>
	            </span>
	        </div>
        </div>

        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'gb_report_problem_group', // Option group
            'gb_report_problem_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'selection_fields', // ID
            'Reason of the problems', // Title
            array( $this, "print_section_info" ), // Callback
            'report-problem-setting' // Page
        );  

        add_settings_field(
            'selection_field', // ID
            'Selection Box items', // Title 
            array( $this, 'selection_field_callback' ), // Callback
            'report-problem-setting', // Page
            'selection_fields' // Section           
        );      
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        //print 'Manage Report Problem Page <b>Reason of the problem</b> Field Items:';
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if(isset($_POST['select_fields']))
        {
        	$new_input['selection_field'] = json_encode(array_filter($_POST['select_fields']));
        }
        return $new_input;
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function selection_field_callback()
    {
    	$values = array();
    	if(isset( $this->options['selection_field'])) 
    	{
    		$values = json_decode( $this->options['selection_field']);
    	}
    	?>

		<div class="container">
			<div class="row">
		        <div class="control-group" id="fields">
		            <div class="controls" id="control_form">
		            	<?php foreach ($values as $key => $value) { ?>
	                    <div class="entry input-group col-md-3" style="margin-bottom:5px;">
	                        <input class="form-control" name="select_fields[]" value="<?php echo esc_attr($value); ?>" type="text" placeholder="New Item" />
	                    	<span class="input-group-btn">
	                            <button class="btn btn-danger btn-remove" type="button">
	                                <span class="glyphicon glyphicon-minus"></span>
	                            </button>
	                        </span>
	                    </div>
	                    <?php } ?>
	                    <div class="entry input-group col-md-3" style="margin-bottom:5px;">
	                        <input class="form-control" name="select_fields[]" type="text" placeholder="New Item" />
	                    	<span class="input-group-btn">
	                            <button class="btn btn-success btn-add-more" type="button">
	                                <span class="glyphicon glyphicon-plus"></span>
	                            </button>
	                        </span>
	                    </div>
		            </div>
		        </div>
			</div>
		</div>
    <?php    
    }
}

if( is_admin() )
    $report_page_setting = new ReportPageSetting();

function report_selection_items_to_contact_form ( $tag, $unused ) 
{ 
    if ( $tag['name'] != 'report-selection-items' )  
        return $tag;  
  	
  	$selection_field = get_option( 'gb_report_problem_name' );
  	$options = array();

  	if(count($selection_field) > 0)
		$options = json_decode($selection_field['selection_field']);  		

    foreach ( $options as $option ) 
    {
        $tag['raw_values'][] = $option;  
        $tag['values'][] = $option;
        $tag['labels'][] = $option;
    }  
  
    return $tag;  
}  
add_filter( 'wpcf7_form_tag', 'report_selection_items_to_contact_form', 10, 2)

?>
