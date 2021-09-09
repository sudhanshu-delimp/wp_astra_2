<?php
/*
Plugin Name: Bookings
Description: To show all registered bookings.
Author: Sudhanshu
Version: 1.0
*/

$page = isset($_GET['page'])?trim($_GET['page']):'';
if($page == "sbs-booking-list"){
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
}

add_action('admin_menu','sbs_booking_list_table');
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
?>
