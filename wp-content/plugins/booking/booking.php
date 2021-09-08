<?php
/*
Plugin Name: Bookings
Description: To show all registered bookings.
Author: Sudhanshu
Version: 1.0
*/

$page = isset($_GET['page'])?trim($_GET['page']):'';
add_action('admin_menu','sbs_booking_list_table');
if($page == "sbs-booking-list"){
    add_action( 'admin_enqueue_scripts', 'sbs_booking_style_and_scripts' );
}

function sbs_booking_style_and_scripts(){
   wp_enqueue_style( 'sbs-booking-bootstrap.min-css', plugins_url('/booking/assets/css/bootstrap.min.css'),  __FILE__);

   wp_enqueue_script( 'sbs-booking-bootstrap.min-script', plugins_url('/booking/assets/js/bootstrap.min.js'), array('jquery'), rand(), true );
}

function sbs_booking_list_table(){
    add_menu_page("Bookings","Bookings","manage_options","sbs-booking-list","sbs_booking_list_fun");
}

function sbs_booking_list_fun(){
  $action = isset($_GET['action'])?trim($_GET['action']):"";
  if($action == "sgs-booking-edit"){
    $booking_id = isset($_GET['booking_id'])?intval($_GET['booking_id']):"";
    ob_start();
    include_once plugin_dir_path(__FILE__).'views/sbs-booking-edit.php';
    $template = ob_get_contents();
    ob_end_clean();
    echo $template;
  }
  else{
    ob_start();
    include_once plugin_dir_path(__FILE__).'views/sbs-booking-list.php';
    $template = ob_get_contents();
    ob_end_clean();
    echo $template;
  }
}
?>
