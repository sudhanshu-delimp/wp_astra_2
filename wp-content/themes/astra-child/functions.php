<?php
/**
 * Astra Child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra Child
 * @since 1.0.0
 */

/**
 * Define Constants
 */
define( 'CHILD_THEME_ASTRA_CHILD_VERSION', '1.0.0' );

/**
 * Enqueue styles
 */
 //echo get_stylesheet_directory_uri() . '/style.css';
function child_enqueue_styles() {
	wp_enqueue_style( 'astra-child-bootstrap.min-css', 'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css', array('astra-theme-css'), CHILD_THEME_ASTRA_CHILD_VERSION, 'all' );
	wp_enqueue_style( 'astra-child-font-awesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css', array('astra-theme-css'), CHILD_THEME_ASTRA_CHILD_VERSION, 'all' );
	wp_enqueue_style( 'astra-child-jquery-ui-css','//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css', array('astra-theme-css'));
	wp_enqueue_style( 'astra-child-theme-css', get_stylesheet_directory_uri() . '/style.css', array('astra-theme-css'));

	wp_enqueue_script( 'astra-child-bootstrap.min-script', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js', array('jquery'), CHILD_THEME_ASTRA_CHILD_VERSION, true );
	wp_enqueue_script( 'astra-child-jquery.validate-script', get_stylesheet_directory_uri().'/assets/custom/jquery.validate.js', array('jquery'), CHILD_THEME_ASTRA_CHILD_VERSION, true );
	wp_enqueue_script( 'astra-child-jquery.form-script', get_stylesheet_directory_uri().'/assets/custom/jquery.form.js', array('jquery'), CHILD_THEME_ASTRA_CHILD_VERSION, true );
	wp_enqueue_script( 'astra-child-custom-main-script', get_stylesheet_directory_uri().'/assets/custom/custom-main.js', array('jquery'), CHILD_THEME_ASTRA_CHILD_VERSION, true );
	wp_enqueue_script( 'astra-child-jquery-ui-script', 'https://code.jquery.com/ui/1.12.1/jquery-ui.js', array('jquery'), CHILD_THEME_ASTRA_CHILD_VERSION, true );

	wp_enqueue_script( 'astra-child-reservation-script', get_stylesheet_directory_uri().'/assets/custom/reservation.js', array('jquery'), CHILD_THEME_ASTRA_CHILD_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'child_enqueue_styles', 100 );

/*start custom functions*/
function getDateTime($datetime='',$format='Y-m-d H:i:s') {
	$format = trim($format)=='' ? 'Y-m-d H:i:s' : $format;
	$datetime = (trim($datetime)=='') ? date($format) : $datetime;
	return date($format,strtotime($datetime));
}

function dateDiffInDays($date1, $date2){
  $diff = strtotime($date2) - strtotime($date1);
  return abs(round($diff / 86400));
}

function getNextDate($current_date,$day){
  return date('Y-m-d', strtotime('+ '.($day-1).' day'.$current_date));
}

function getDatesFromRange($start, $end, $format = 'Y-m-d') {
  $array = array();
  $interval = new DateInterval('P1D');
  $realEnd = new DateTime($end);
  $realEnd->add($interval);
  $period = new DatePeriod(new DateTime($start), $interval, $realEnd);
  foreach($period as $date) {
    $array[] = $date->format($format);
  }
  return $array;
}

function sendResponse($data=array()){
  header('Content-Type: application/json');
  echo json_encode($data);
  exit();
}
/* end custom functions*/

/*start reservation process functions*/
function process_step_one(){
	$data = [];
	$data['check_in_date'] = getDateTime($_POST['arrival_date'], 'Y-m-d');
	$data['check_out_date'] = getNextDate($_POST['arrival_date'], intval($_POST['number_of_night']));
	$data['date_range'] = getDatesFromRange($data['check_in_date'],$data['check_out_date']);
  ob_start();
  get_template_part('content-addon',null,['date_range'=>$data['date_range']]);
  $available_addons = ob_get_clean();
  $data['available_addons'] = $available_addons;
	sendResponse($data);
}

function get_activity_list(){
	$data = [];
	$data['check_in_date'] = getDateTime($_POST['arrival_date'], 'Y-m-d');
	$data['check_out_date'] = getNextDate($_POST['arrival_date'], intval($_POST['number_of_night']));
	$data['date_range'] = getDatesFromRange($data['check_in_date'],$data['check_out_date']);
  ob_start();
  get_template_part('content-activity',null,['date_range'=>$data['date_range']]);
  $available_activities = ob_get_clean();
  $data['available_activities'] = $available_activities;
	sendResponse($data);
}

function add_activity_in_queue(){
	$data = [];
	$activity_id = $_POST['activity_id'];
	$selected_date = $_POST['selected_date'];
	$selected_time = $_POST['selected_time'];
	$selected_quantity = $_POST['selected_quantity'];
	$selected_price = $_POST['selected_price'];
	ob_start();
  get_template_part('content-activity-queue',null,['activity_id'=>$activity_id,'selected_date'=>$selected_date,'selected_time'=>$selected_time,'selected_quantity'=>$selected_quantity,'selected_price'=>$selected_price]);
  $activity_content = ob_get_clean();
  $data['activity_content'] = $activity_content;
  sendResponse($data);
}

function show_booking_preview(){
	$data['message'] = "Hello";
	sendResponse($data);
}

add_action('wp_ajax_process_step_one', 'process_step_one');
add_action('wp_ajax_nopriv_process_step_one', 'process_step_one');
add_action('wp_ajax_get_activity_list', 'get_activity_list');
add_action('wp_ajax_nopriv_get_activity_list', 'get_activity_list');
add_action('wp_ajax_add_activity_in_queue', 'add_activity_in_queue');
add_action('wp_ajax_nopriv_add_activity_in_queue', 'add_activity_in_queue');
add_action('wp_ajax_show_booking_preview', 'show_booking_preview');
add_action('wp_ajax_nopriv_show_booking_preview', 'show_booking_preview');


/*end reservation process functions*/

//dd_filter('acf/settings/remove_wp_meta_box', '__return_false');
