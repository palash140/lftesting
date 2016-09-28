<?php
/*
Plugin Name: Share Buttons Widget
Plugin URI: http://seo.uk.net/share-buttons-widget/
Description: With Share Buttons Widget you can share your content at Facebook, Twitter, Google, Digg, Delicious and many more!
Author: Seo UK Team
Version: 2.0
Author URI: http://seo.uk.net
*/

function share_buttons_control() {

  $options = get_sbw_options();

  if ($_POST['wp_sbw_Submit']){

    $options['wp_sbw_WidgetTitle'] = htmlspecialchars($_POST['wp_sbw_WidgetTitle']);
    $options['wp_sbw_sctext_wlink'] = htmlspecialchars($_POST['wp_sbw_sctext_wlink']);
    update_option("widget_share_buttons", $options); 

}
?>
  <p>Do you need help with SEO?. Visit our website <a href="http://seo.uk.net" title="Link will open in a new window" target="_blank">www.seo.uk.net</a> for more information.</p>
  <p>
    <label for="wp_sbw_WidgetTitle">Text Title: </label>
    <input type="text" id="wp_sbw_WidgetTitle" name="wp_sbw_WidgetTitle" value="<?php echo ($options['wp_sbw_WidgetTitle'] =="" ? "Share this page" : $options['wp_sbw_WidgetTitle']); ?>" />
  </p>
 
 <p>
    <label for="wp_sbw_sctext_wlink">Please support our plugin by showing a small link under widget.</label><p align="right">Activate it: 
    <input type="checkbox" id="wp_sbw_sctext_wlink" name="wp_sbw_sctext_wlink" <?php echo ($options['wp_sbw_sctext_wlink'] == "on" ? "checked" : "" ); ?> /></p>
  </p>
  
  <p>
    <input type="hidden" id="wp_sbw_Submit" name="wp_sbw_Submit" value="1" />
  </p>

<?php
}
function shwinst_activate() { 
add_option('shbinstallredirect_do_activation_redirect', true); wp_redirect('../wp-admin/widgets.php');
 };

function get_sbw_options() {

  $options = get_option("widget_share_buttons");
  if (!is_array( $options )) {
    $options = array(
                     'wp_sbw_WidgetTitle' => 'Share this page',
                     'wp_sbw_sctext_wlink' => ''
                    );
  }
  return $options;
}

function get_info ($sex, $unique, $hit=false) {

  global $wpdb;
  $table_name = $wpdb->prefix . "sc_log";
  $options = get_sbw_options();
  $sql = '';
  $stime = time()-$sex;
  $sql = "SELECT COUNT(".($unique ? "DISTINCT IP" : "*").") FROM $table_name where Time > ".$stime;

  if ($hit)
   $sql .= ' AND IS_HITS = 1 ';

  if ($options['wp_sbw_sctext_bots_filter'] > 1)
      $sql .= ' AND IS_BOT <> 1';

  return number_format_i18n($wpdb->get_var($sql));
  }

function viewwidget() {

  global $wpdb;
  $options = get_sbw_options();
  $table_name = $wpdb->prefix . "sc_log";

?>

<div class="a2a_kit a2a_default_style">
<a class="a2a_dd" href="https://www.addtoany.com/share_save">Share</a>
<span class="a2a_divider"></span>
<a class="a2a_button_facebook"></a>
<a class="a2a_button_twitter"></a>
<a class="a2a_button_google_plus"></a>
<a class="a2a_button_digg"></a>
<a class="a2a_button_delicious"></a>
<a class="a2a_button_stumbleupon"></a>
<a class="a2a_button_myspace"></a>
<a class="a2a_button_email"></a>
</div>
<script type="text/javascript" src="//static.addtoany.com/menu/page.js"></script>

<?php if ($options['wp_sbw_sctext_wlink'] == "on") { ?>
<br /><div><small><a href="http://seo.uk.net/share-buttons-widget/" target="_blank">Share Buttons Widget</a> by <a href="http://seo.uk.net/" target="_blank">seo.uk.net</small>.</div>
<?php } ?>

<?php
}

function widget_share_buttons($args) {
  extract($args);

  $options = get_sbw_options();

  echo $before_widget;
  echo $before_title.$options["wp_sbw_WidgetTitle"];
  echo $after_title;
  viewwidget();
  echo $after_widget;
}


function is_hits ($ip) {

   global $wpdb;
   $table_name = $wpdb->prefix . "sc_log";

   $user_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name where ".time()." - Time <= 1000 and IP = '".$ip."'");

   return $user_count == 0;
}

function wp_sbw_install_db () {
   global $wpdb;

   $table_name = $wpdb->prefix . "sc_log";
   $gTable = $wpdb->get_var("show tables like '$table_name'");
   $gColumn = $wpdb->get_results("SHOW COLUMNS FROM ".$table_name." LIKE 'IS_BOT'");
   $hColumn = $wpdb->get_results("SHOW COLUMNS FROM ".$table_name." LIKE 'IS_HITS'");

   if($gTable != $table_name) {

      $sql = "CREATE TABLE " . $table_name . " (
           IP VARCHAR( 17 ) NOT NULL ,
           Time INT( 11 ) NOT NULL ,
           IS_BOT BOOLEAN NOT NULL,
           IS_HITS BOOLEAN NOT NULL,
           PRIMARY KEY ( IP , Time )
           );";

      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
      dbDelta($sql);

   } else {
     if (empty($gColumn)) {  //old table version update

       $sql = "ALTER TABLE ".$table_name." ADD IS_BOT BOOLEAN NOT NULL";
       $wpdb->query($sql);
     }

     if (empty($hColumn)) {  //old table version update

       $sql = "ALTER TABLE ".$table_name." ADD IS_HITS BOOLEAN NOT NULL";
       $wpdb->query($sql);
     }
   }
}

function share_buttons_init() {

  wp_sbw_install_db ();
  register_sidebar_widget(__('-- Share Buttons Widget --'), 'widget_share_buttons');
  register_widget_control(__('-- Share Buttons Widget --'), 'share_buttons_control', 300, 200 );
}

function uninstalls_sc(){

  global $wpdb;
  $table_name = $wpdb->prefix . "sc_log";
  delete_option("widget_share_buttons");
  delete_option("wp_sbw_WidgetTitle");
  delete_option("wp_sbw_sctext_wlink");

  $wpdb->query("DROP TABLE IF EXISTS $table_name");
}

function add_sbw_stylesheet() {
            wp_register_style('scStyleSheets', plugins_url('gt-styles.css',__FILE__));
            wp_enqueue_style( 'scStyleSheets');
}

add_action("plugins_loaded", "share_buttons_init");
add_action('wp_print_styles', 'add_sbw_stylesheet');

register_deactivation_hook( __FILE__, 'uninstalls_sc' );
register_activation_hook( __FILE__,'shwinst_activate');
add_action('admin_init', 'shbinstallredirect_redirect');

function shbinstallredirect_redirect() {
if (get_option('shbinstallredirect_do_activation_redirect', false)) { delete_option('shbinstallredirect_do_activation_redirect'); wp_redirect('../wp-admin/widgets.php');
}
}

?>