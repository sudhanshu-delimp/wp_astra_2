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
function check_reservation_availability($check_in_date, $check_out_date){
	global $wpdb;

	// $sql_query = "SELECT * FROM `wp_reservations` WHERE";
	// $sql_query .= " ($check_in_date <= check_in_date AND $check_in_date <= check_out_date AND";
	// $sql_query .= " (($check_out_date >= check_in_date AND $check_out_date >= check_out_date) OR ($check_out_date >=";
	// $sql_query .= " check_in_date AND $check_out_date <= check_out_date))) OR";
	// $sql_query .= " ($check_in_date >= check_in_date AND $check_in_date <= check_out_date AND";
	// $sql_query .= " (($check_out_date >= check_in_date AND $check_out_date >= check_out_date) OR ($check_out_date >= ";
	// $sql_query .= " check_in_date AND $check_out_date <= check_out_date)))";

	$sql_query  = "select * from wp_reservations where check_in_date >=date '".$check_in_date."' AND check_in_date<=date'".$check_out_date."'";
	$sql_query  .= " OR check_out_date<=date '".$check_out_date."' AND check_out_date>=date'".$check_in_date."'";
	//$sql_query = "SELECT * FROM `wp_reservations`";

	// $sql_query = "select count(*) from wp_reservations";
	// $sql_query .= " where daterange(check_in_date, check_out_date, '[]') && daterange('".$check_in_dat."', '".$check_out_date."', '[]')";
	
	$result = $wpdb->get_results($sql_query);
	$row_count = count($result);
	return ($row_count == 0)?true:false;
}

function process_step_one(){
	$data = [];
	$data['check_in_date'] = getDateTime($_POST['arrival_date'], 'Y-m-d');
	$data['check_out_date'] = getNextDate($_POST['arrival_date'], intval($_POST['number_of_night']));

	$is_reservation_available = check_reservation_availability($data['check_in_date'], $data['check_out_date']);
	if($is_reservation_available){
		$data['status'] = '1';
		$data['date_range'] = getDatesFromRange($data['check_in_date'],$data['check_out_date']);
		$number_of_night = intval($_POST['number_of_night']);
		$adults_per_room = intval($_POST['adults_per_room']);
		ob_start();
		get_template_part('content-addon',null,['date_range'=>$data['date_range']]);
		$available_addons = ob_get_clean();
		$data['available_addons'] = $available_addons;
		$data['selected_date_data'] = [
		'check_in_date'=>$data['check_in_date'],
		'check_out_date'=>$data['check_out_date'],
		'number_of_night'=>$data['number_of_night'],
		'adults_per_room'=>$data['adults_per_room']
		];
	}
	else{
		$data['status'] = '0';
		$data['message'] = 'Reservation is not available for selected date and nights';
	}
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
	$check_in_date= getDateTime($_POST['arrival_date'], 'Y-m-d');
	$check_out_date = getNextDate($_POST['arrival_date'], intval($_POST['number_of_night']));
	$number_of_night = intval($_POST['number_of_night']);
	$adults_per_room = intval($_POST['adults_per_room']);
	$user_data = $_POST['user_data'];
	$selected_activity_data = $_POST['selected_activity_data'];
	$selected_addon_data = $_POST['selected_addon_data'];
	ob_start();
	get_template_part('content-booking-preview',null,['check_in_date'=>$check_in_date,'check_out_date'=>$check_out_date,'number_of_night'=>$number_of_night,'adults_per_room'=>$adults_per_room,'user_data'=>$user_data,'selected_activity_data'=>$selected_activity_data ,'selected_addon_data'=>$selected_addon_data]);
	$available_preview = ob_get_clean();
	$data['available_preview'] = $available_preview;
	sendResponse($data);
}

function make_booking(){
	global $wpdb;
	$insert = [];
	$data = [];
	$check_in_date = getDateTime($_POST['arrival_date'], 'Y-m-d');
	$check_out_date = getNextDate($_POST['arrival_date'], intval($_POST['number_of_night']));
	$number_of_night = intval($_POST['number_of_night']);
	$adults_per_room = intval($_POST['adults_per_room']);
	$user_data = $_POST['user_data'];
	$selected_activity_data = $_POST['selected_activity_data'];
	$selected_addon_data = $_POST['selected_addon_data'];
	$insert['check_in_date'] = $check_in_date;
	$insert['check_out_date'] = $check_out_date;
	$insert['number_of_night'] = $number_of_night;
	$insert['adults_per_room'] = $adults_per_room;
	$insert['user_data'] = json_encode($user_data);
	$insert['addon_data'] = json_encode($selected_addon_data);
	$insert['addon_data'] = json_encode($selected_addon_data);
	$insert['activity_data'] = json_encode($selected_activity_data);
	$insert['status'] = '1';
	$response = $wpdb->insert('wp_reservations', $insert);
	if($response){
		$data['status'] = '1';
		$data['data']['reservation_id'] = $wpdb->insert_id;
		$data['data']['message'] = 'Your reservation has done successfully.';
	}
	else{
		$data['status'] = '0';
		$data['data']['message'] = 'Unable to book your reservation.';
	}
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
add_action('wp_ajax_make_booking', 'make_booking');
add_action('wp_ajax_nopriv_make_booking', 'make_booking');

/*end reservation process functions*/

//dd_filter('acf/settings/remove_wp_meta_box', '__return_false');
