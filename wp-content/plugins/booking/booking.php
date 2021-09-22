<?php
/*
Plugin Name: Bookings
Description: To show all registered bookings.
Author: Sudhanshu
Version: 1.0
*/

$page = isset($_GET['page'])?trim($_GET['page']):'';
if(in_array($page,['sbs-booking-list','sbs-booking-setting'])){
    add_action( 'admin_enqueue_scripts', 'sbs_booking_style_and_scripts' );
}

function sbs_booking_style_and_scripts(){
   //wp_enqueue_style( 'sbs-booking-bootstrap.min-css', plugins_url('/booking/assets/css/bootstrap.min.css'),  __FILE__);
   wp_enqueue_style( 'sbs-booking-css', plugins_url('/booking/assets/css/booking.css'),  __FILE__);

   wp_enqueue_script( 'sbs-booking-bootstrap.min-script', plugins_url('/booking/assets/js/bootstrap.min.js'), array('jquery'), rand(), true );
}

function sbs_booking_list_table(){
     add_menu_page("Bookings","Bookings","manage_options","sbs-booking-list","sbs_booking_list_fun");
     add_submenu_page('sbs-booking-list', 'Addons', 'Addons', 'manage_options', 'edit.php?post_type=addons');
     add_submenu_page('sbs-booking-list', 'Add New Addon', 'Add New Addon', 'manage_options', 'post-new.php?post_type=addons');
     add_submenu_page('sbs-booking-list', 'Activities', 'Activities', 'manage_options', 'edit.php?post_type=activities');
     add_submenu_page('sbs-booking-list', 'Add New Activity', 'Add New Activity', 'manage_options', 'post-new.php?post_type=activities');
     add_submenu_page('sbs-booking-list', 'Settings', 'Settings', 'manage_options', 'sbs-booking-setting', 'sbs_booking_setting_fun');
}

add_action('admin_menu','sbs_booking_list_table');

function sbs_booking_setting_fun(){
  ob_start();
  include_once plugin_dir_path(__FILE__).'views/sbs-settings.php';
  $template = ob_get_contents();
  ob_end_clean();
  echo "<div class='booking-container'>".$template."<div>";
}

function sbs_booking_list_fun(){
  $action = isset($_GET['action'])?trim($_GET['action']):"";
  if($action == "sgs-booking-view"){
    $booking_id = isset($_GET['booking_id'])?intval($_GET['booking_id']):"";
    ob_start();
    include_once plugin_dir_path(__FILE__).'views/sbs-booking-edit.php';
    $template = ob_get_contents();
    ob_end_clean();
    echo "<div class='booking-container'>".$template."<div>";
  }
  else{
    ob_start();
    include_once plugin_dir_path(__FILE__).'views/sbs-booking-list.php';
    $template = ob_get_contents();
    ob_end_clean();
    echo "<div class='booking-container'>".$template."<div>";
  }
}

add_action( 'init', 'crunchify_addons_custom_post_type', 0 );
add_action( 'init', 'crunchify_activity_custom_post_type', 0 );
function crunchify_addons_custom_post_type() {
	$labels = array(
		'name'                => __( 'Addons' ),
		'singular_name'       => __( 'Addon'),
		'menu_name'           => __( 'Addons'),
		'parent_item_colon'   => __( 'Parent Addon'),
		'all_items'           => __( 'All Addons'),
		'view_item'           => __( 'View Addon'),
		'add_new_item'        => __( 'Add New Addon'),
		'add_new'             => __( 'Add New'),
		'edit_item'           => __( 'Edit Addon'),
		'update_item'         => __( 'Update Addon'),
		'search_items'        => __( 'Search Addon'),
		'not_found'           => __( 'Not Found'),
		'not_found_in_trash'  => __( 'Not found in Trash')
	);
	$args = array(
		'label'               => __( 'Addons'),
		'description'         => __( 'Addons'),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor','author', 'thumbnail'),
		'public'              => true,
		'hierarchical'        => false,
		'show_ui'             => true,
		'show_in_menu'        => false,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'has_archive'         => true,
		'can_export'          => true,
		'exclude_from_search' => false,
	    'yarpp_support'       => true,
		'menu_icon' => 'dashicons-randomize',
		//'taxonomies' 	      => array('post_tag'),
		'publicly_queryable'  => true,
		'capability_type'     => 'page'
);
	register_post_type( 'Addons', $args );
}

function crunchify_activity_custom_post_type() {
	$labels = array(
		'name'                => __( 'Activities' ),
		'singular_name'       => __( 'Activity'),
		'menu_name'           => __( 'Activities'),
		'parent_item_colon'   => __( 'Parent Activity'),
		'all_items'           => __( 'All Activities'),
		'view_item'           => __( 'View Activity'),
		'add_new_item'        => __( 'Add New Activity'),
		'add_new'             => __( 'Add New'),
		'edit_item'           => __( 'Edit Activity'),
		'update_item'         => __( 'Update Activity'),
		'search_items'        => __( 'Search Activity'),
		'not_found'           => __( 'Not Found'),
		'not_found_in_trash'  => __( 'Not found in Trash')
	);
	$args = array(
		'label'               => __( 'Activities'),
		'description'         => __( 'Activities'),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor','author', 'thumbnail'),
		'public'              => true,
		'hierarchical'        => false,
		'show_ui'             => true,
		'show_in_menu'        => false,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'has_archive'         => true,
		'can_export'          => true,
		'exclude_from_search' => false,
	  'yarpp_support'       => true,
	  'menu_icon' => 'dashicons-randomize',
		//'taxonomies' 	      => array('post_tag'),
		'publicly_queryable'  => true,
		'capability_type'     => 'page'
);
	register_post_type( 'Activities', $args );
}

function sbs_booking_plugin_activation(){
  do_action('create_plugin_database_table');
  do_action('sbs_init_booking_settings');
}

function sbs_create_plugin_database_table(){
    global $table_prefix, $wpdb;
    $tblname = 'reservations';
    $wp_track_table = $table_prefix . "$tblname ";
    if($wpdb->get_var( "show tables like '$wp_track_table'" ) != $wp_track_table){
        $sql = "CREATE TABLE `".$wp_track_table."` (";
        $sql .= " `id` int(11) NOT NULL, ";
        $sql .=   " `booking_id` varchar(255) NOT NULL DEFAULT '', ";
        $sql .=   " `user_name` varchar(255) NOT NULL DEFAULT '', ";
        $sql .=   " `check_in_date` date NOT NULL, ";
        $sql .=   " `check_out_date` date NOT NULL, ";
        $sql .=   " `number_of_night` int(11) NOT NULL DEFAULT 0,";
        $sql .=   " `adults_per_room` int(11) NOT NULL DEFAULT 0,";
        $sql .=   " `user_data` mediumtext NOT NULL DEFAULT '',";
        $sql .=   " `addon_data` mediumtext NOT NULL DEFAULT '',";
        $sql .=   " `activity_data` mediumtext NOT NULL DEFAULT '',";
        $sql .=   " `status` enum('0','1','2') NOT NULL DEFAULT '1',";
        $sql .=   " `created_at` timestamp NOT NULL DEFAULT current_timestamp() ";
        $sql .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }
}

function sbs_init_booking_settings(){
  add_option('sbs_admin_name', 'info one');
  add_option('sbs_admin_email', 'info@example.com');
  add_option('sbs_from_name', 'info two');
  add_option('sbs_from_email', 'info2@example.com');
  add_option('sbs_email_api_key', '*****************');
}

register_activation_hook( __FILE__, 'sbs_booking_plugin_activation' );
add_action('sbs_create_plugin_database_table','sbs_create_plugin_database_table');
add_action('sbs_init_booking_settings','sbs_init_booking_settings');
?>
