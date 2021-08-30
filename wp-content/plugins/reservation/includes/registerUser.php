<?php
//Add custom field in frontend registration field
global $user;
add_action( 'register_form', 'crf_registration_form' );
add_filter( 'registration_errors', 'crf_registration_errors', 10, 3 );
add_action( 'user_register', 'crf_user_register' );

function getCurlResponse($url){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  $response = curl_exec($ch);
  curl_close($ch);
  return json_decode($response,true);
}

 function getCaptcha($secretKey){
  $response = getCurlResponse("https://www.google.com/recaptcha/api/siteverify?secret=".SECRET_KEY."&response={$secretKey}");
  //$return = json_decode($response);
  return $response;
}

function crf_registration_form() {
	$first_name = ! empty( $_POST['first_name'] ) ? intval( $_POST['first_name'] ) : '';
	$last_name = ! empty( $_POST['last_name'] ) ? intval( $_POST['last_name'] ) : '';
	$phone = ! empty( $_POST['phone'] ) ? intval( $_POST['phone'] ) : '';
	$password = ! empty( $_POST['password'] ) ? intval( $_POST['password'] ) : '';
	?>
	<p>
		<label for="first_name"><?php esc_html_e( 'First Name', 'crf' ) ?></label>
		<input type="text" id="first_name" name="first_name" value="<?php echo esc_attr( $first_name ); ?>" class="input"/>
	</p>
	<p>
		<label for="last_name"><?php esc_html_e( 'Last Name', 'crf' ) ?></label>
		<input type="text" id="last_name" name="last_name" value="<?php echo esc_attr( $last_name ); ?>" class="input"/>
	</p>
	<p>
		<label for="phone"><?php esc_html_e( 'Phone', 'crf' ) ?></label>
		<input type="text" id="phone" name="phone" value="<?php echo esc_attr( $phone ); ?>" class="input"/>
	</p>
	<p>
		<label for="password"><?php esc_html_e( 'Password', 'crf' ) ?></label>
		<input type="text" id="password" name="password" value="<?php echo esc_attr( $phone ); ?>" class="input"/>
	</p>
	<?php
}

function crf_registration_errors( $errors, $sanitized_user_login, $user_email ) {
	if ( empty( $_POST['first_name'] ) ) {
		$errors->add( 'first_name_error', __( '<strong>ERROR</strong>: Please enter your first name.', 'crf' ) );
	}
	if ( empty( $_POST['last_name'] ) ) {
		$errors->add( 'last_name_error', __( '<strong>ERROR</strong>: Please enter your last name.', 'crf' ) );
	}
	if ( empty( $_POST['password'] ) ) {
		$errors->add( 'last_name_error', __( '<strong>ERROR</strong>: Please enter your password.', 'crf' ) );
	}
	if ( empty( $_POST['phone'] ) ) {
		$errors->add( 'phone_error', __( '<strong>ERROR</strong>: Please enter your phone number.', 'crf' ) );
	}
	else{
		if(!preg_match('/^[0-9]{10}+$/', $_POST['phone'])){
			$errors->add( 'phone_error', __( '<strong>ERROR</strong>: Please enter a valid phone number.', 'crf' ) );
		}
	}
	return $errors;
}

function crf_user_register( $user_id ) {
	if ( ! empty( $_POST['first_name'] ) ) {
		update_user_meta( $user_id, 'first_name', trim($_POST['first_name']) );
	}
	if ( ! empty( $_POST['last_name'] ) ) {
		update_user_meta( $user_id, 'last_name', trim($_POST['last_name']) );
	}
	if ( ! empty( $_POST['phone'] ) ) {
		update_user_meta( $user_id, 'phone', trim($_POST['phone']) );
	}
	if ( ! empty( $_POST['password'] ) ) {
		wp_set_password($_POST['password'], $user_id);
	}
	$_SESSION['register_user_id'] = $user_id;
}

function register_session(){
    if( !session_id() )
        session_start();
}
add_action('init','register_session');

function wpse_19692_registration_redirect() {
    return home_url('package-payment');
}

 add_filter( 'registration_redirect', 'wpse_19692_registration_redirect' );

function my_login_redirect( $redirect_to, $request, $user ) {
    //is there a user to check?
    global $user;
    if ( isset( $user->roles ) && is_array( $user->roles ) ) {
        if(in_array('subscriber',$user->roles)){
          return home_url('videos');
        }
        else{
          return $redirect_to;
        }
    } else {
        return $redirect_to;
    }
}
add_filter( 'login_redirect', 'my_login_redirect', 10, 3 );

function process_pacakge_payment(){
	global $wpdb,$wp_filesystem;
	WP_Filesystem();
	$amount = 0;
	$is_broker = 0;
/**/
if(!empty($_POST['is_broker']) && empty($_FILES['fileToUpload']['name'])){
	$_SESSION['package_message'] = ['danger'=>'Please upload your proof of a license.'];
}

if(!empty($_FILES['fileToUpload']['name'])) {
		$supported_types = array('application/pdf');
		$arr_file_type = wp_check_filetype(basename($_FILES['fileToUpload']['name']));
		$uploaded_type = $arr_file_type['type'];
		// Check if the type is supported. If not, throw an error.
		if(in_array($uploaded_type, $supported_types)) {
			$upload = wp_upload_bits(time().'_'.$_FILES['fileToUpload']['name'], null, file_get_contents($_FILES['fileToUpload']['tmp_name']));
			if(isset($upload['error']) && $upload['error'] != 0) {
				$_SESSION['package_message'] = ['danger'=>'There was an error uploading your file. The error is: '.$upload['error']];
			}
      if($upload['url']){
        update_user_meta( $_POST['user_id'], 'upload_file_url', $upload['url']);
      }
      else{
        update_user_meta( $_POST['user_id'], 'upload_file_url', '');
      }
		}
		else{
			$_SESSION['package_message'] = ['danger'=>'The file type that you\'ve uploaded is not PDF.'];
		}
}
/**/
	if(count($_POST['package']) == 0){
		$_SESSION['package_message'] = ['danger'=>'Check at least one package.'];
	}
	if(!empty($_SESSION['package_message'])){
		wp_redirect(wp_get_referer());
		exit;
	}
  $amount = 0;
	if(count($_POST['package'])>0){
		$amount = (count($_POST['package'])*1000);
	}
  if(count($_POST['package'])==2){
    $amount = ($amount-750);
  }
  if(!empty($_POST['is_broker']) && !empty($upload['url'])){
    $is_broker = 1;
  }
	if(!empty($upload['url']) && count($_POST['package'])==1){
		$amount = $amount-250;
	}
	update_user_meta( $_POST['user_id'], 'package', implode(',',$_POST['package']));
	update_user_meta( $_POST['user_id'], 'is_broker', $is_broker);
  //$amount = 0.50;
	update_user_meta( $_POST['user_id'], 'paid_package_amount', number_format($amount, 2));
	//return home_url('processpackagepayment');
	//wp_redirect( home_url('process-package-payment') );
  wp_redirect( home_url('membership-agreement') );
  exit;
}
add_action('wp_ajax_process_pacakge_payment', 'process_pacakge_payment');
add_action('wp_ajax_nopriv_process_pacakge_payment', 'process_pacakge_payment');

function membership_agreement(){
  wp_redirect(home_url('process-package-payment'));
  exit;
}

add_action('wp_ajax_membership_agreement', 'membership_agreement');
add_action('wp_ajax_nopriv_membership_agreement', 'membership_agreement');
function process_stripe_payment(){
	$userData = get_userdata($_SESSION['register_user_id']);
	//$userData->data->user_email
	$selected_package = get_user_meta($_SESSION['register_user_id'],'package',true);
	require_once('../vendor/autoload.php');
	$stripe = new \Stripe\StripeClient('sk_test_51Igv07KIiEnuerpov7JyrqtJnZsnDyVpRIg8eASNOAUiQ5JFaPI6TjqmfTj59d2tJ4HXYSViri11V95aLnEaI6Ma00Yts1uNJg');
	try{
		$selected_package = get_user_meta($_SESSION['register_user_id'],'package',true);
		$paid_package_amount = get_user_meta($_SESSION['register_user_id'],'paid_package_amount',true);
		$paid_package_amount = filter_var($paid_package_amount, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
		$description = Customers_List::getUserSelectedPackagesTwo($_SESSION['register_user_id']). " package buy by ".$_POST['fullname'];
		$charge = $stripe->charges->create([
			"amount" => $paid_package_amount * 100,
			"currency" => "usd",
			"source" => $_POST['stripeToken'],
			"description" => ucwords($description),
			"receipt_email" => 'xyz@package.com',
		]);
		if(!empty($charge->id)){
			update_user_meta( $_SESSION['register_user_id'], 'stripe_trans_id', $charge->id);
			update_user_meta( $_SESSION['register_user_id'], 'payment_method', $charge->payment_method);
			send_payment_confirmation_email($_SESSION['register_user_id']);
			unset($_SESSION['register_user_id']);
      unset($_SESSION['register_user_name']);
			$_SESSION['payment_message'] = ['success'=>'Payment complete.'];
		}
		else{
			$_SESSION['payment_message'] = ['danger'=>'Payment not complete.'];
		}
	}
	catch(\Stripe\Exception\CardException $e) {
		$_SESSION['payment_message'] = ['danger'=>$e->getError()->message];
	} catch (\Stripe\Exception\RateLimitException $e) {
  		$_SESSION['payment_message'] = ['danger'=>$e->getError()->message];
	} catch (\Stripe\Exception\InvalidRequestException $e) {
		$_SESSION['payment_message'] = ['danger'=>$e->getError()->message];
	} catch (\Stripe\Exception\AuthenticationException $e) {
		$_SESSION['payment_message'] = ['danger'=>$e->getError()->message];
	} catch (\Stripe\Exception\ApiConnectionException $e) {
		$_SESSION['payment_message'] = ['danger'=>$e->getError()->message];
	} catch (\Stripe\Exception\ApiErrorException $e) {
		$_SESSION['payment_message'] = ['danger'=>$e->getError()->message];
	} catch (\Exception $e) {
		$_SESSION['payment_message'] = ['danger'=>$e->getMessage()];
	}
	wp_redirect(wp_get_referer());
	exit;
}
add_action('wp_ajax_process_stripe_payment', 'process_stripe_payment');
add_action('wp_ajax_nopriv_process_stripe_payment', 'process_stripe_payment');

function front_login(){
	require_once( explode( "wp-content" , __FILE__ )[0] . "wp-load.php");
	global $wpdb;
	if(isset($_POST)){
		$username = $wpdb->escape($_REQUEST['username']);
		$password = $wpdb->escape($_REQUEST['password']);

		$login_data = array();
		$login_data['user_login'] = $username;
		$login_data['user_password'] = $password;
		$login_data['remember'] = false;

		$user_verify = wp_signon( $login_data, false );
		if(is_wp_error($user_verify)){
			$_SESSION['login_message'] = (!empty($user_verify->errors['invalid_email']))?$user_verify->errors['invalid_email']:$user_verify->errors['incorrect_password'];
			wp_redirect(home_url('login'));
			exit;
		}
		else{
			wp_redirect(home_url('videos'));
			exit;
		}
	}
}
add_action('wp_ajax_front_login', 'front_login');
add_action('wp_ajax_nopriv_front_login', 'front_login');

function front_register(){
	require_once( explode( "wp-content" , __FILE__ )[0] . "wp-load.php");
	global $wpdb, $user_ID;
	$errors = array();
	if(isset($_POST)){
		$username = $wpdb->escape($_REQUEST['username']);
		if ( strpos($username, ' ') !== false ){
			$errors[] = "Sorry, no spaces allowed in usernames";
		}
		if(empty($username)){
			$errors[] = "Please enter a username";
		}
		elseif( username_exists( $username ) ){
			$errors[] = __('<strong>ERROR</strong>: Username already exists, please try another.','crf');
		}
		$email = $wpdb->escape($_REQUEST['email']);
		if( !is_email( $email ) ){
			$errors[] = __('<strong>ERROR</strong>: Please enter a valid email.','crf');
		}
		else if( email_exists( $email ) ){
			$errors[] = __('<strong>ERROR</strong>: This email address is already in use.','crf');
		}
		if(0 === preg_match("/.{6,}/", $_POST['password'])){
			$errors[] = __('<strong>ERROR</strong>: Password must be at least six characters.','crf');
		}
		if(0 !== strcmp($_POST['password'], $_POST['password_confirmation'])){
			$errors[] = __('<strong>ERROR</strong>: Passwords do not match.','crf');
		}
		if(empty( $_POST['first_name'])){
			$errors[] = __('<strong>ERROR</strong>: Please enter your first name.', 'crf');
		}
		if (empty($_POST['last_name'])){
			$errors[] = __('<strong>ERROR</strong>: Please enter your last name.', 'crf');
		}
		if ( empty( $_POST['phone'] ) ) {
	 		$errors[] = __('<strong>ERROR</strong>: Please enter your phone number.', 'crf');
		}
		else if(!preg_match('/^[0-9]{10}+$/', $_POST['phone'])){
			$errors[] =  __('<strong>ERROR</strong>: Please enter a valid phone number.','crf');
		}
		if(empty( $_POST['address'])){
			$errors[] = __('<strong>ERROR</strong>: Please enter your address.', 'crf');
		}
		if(empty( $_POST['city'])){
			$errors[] = __('<strong>ERROR</strong>: Please enter your city name.', 'crf');
		}
		if(empty( $_POST['state'])){
			$errors[] = __('<strong>ERROR</strong>: Please enter your state name.', 'crf');
		}
		if(empty( $_POST['zip_code'])){
			$errors[] = __('<strong>ERROR</strong>: Please enter your zip code.', 'crf');
		}

		$return = getCaptcha($_POST['g-recaptcha-response']);
    //echo SECRET_KEY.'<br>';
    //echo $_POST['g-recaptcha-response'].'<br>';
    //print_r($return);die;
		if(empty($return['success']) || $return['score'] < 0.5){
			$errors[] = __( '<strong>ERROR</strong>: You are a Robot!!.', 'crf');
		}
		if(0 === count($errors)){
			$password = $_POST['password'];
			$new_user_id = wp_create_user( $username, $password, $email );
			update_user_meta( $new_user_id, 'phone', trim($_POST['phone']) );
			update_user_meta( $new_user_id, 'last_name', trim($_POST['last_name']) );
			update_user_meta( $new_user_id, 'first_name', trim($_POST['first_name']) );
			update_user_meta( $new_user_id, 'address', trim($_POST['address']) );
			update_user_meta( $new_user_id, 'city', trim($_POST['city']) );
			update_user_meta( $new_user_id, 'state', trim($_POST['state']) );
			update_user_meta( $new_user_id, 'zip_code', trim($_POST['zip_code']) );
			send_welcome_email_to_new_user($new_user_id);
			$_SESSION['register_user_id'] = $new_user_id;
      $_SESSION['register_user_name'] = trim($_POST['first_name']).' '.trim($_POST['last_name']);
			wp_redirect(home_url('package-payment'));
			exit;
		}
		else{
			$_SESSION['login_message'] = $errors;
			wp_redirect(home_url('register'));
			exit;
		}
	}
}
add_action('wp_ajax_front_register', 'front_register');
add_action('wp_ajax_nopriv_front_register', 'front_register');

function send_welcome_email_to_new_user($user_id) {
    $user = get_userdata($user_id);
    $user_email = $user->data->user_email;
    $user_full_name = get_user_meta($user_id,'first_name',true)." ".get_user_meta($user_id,'last_name',true);
    $to = $user_email;
    $subject = "Hi " .$user_full_name. ", welcome to financeamerica!";
    $body = '
              <h1>Dear ' . $user_full_name . ',</h1></br>
              <p>Thank you for joining our site. Your account is now active.</p>
              <p>Please go ahead and navigate around your account. <a href="'.site_url('login').'">Login</a></p>
              <p>Let me know if you have further questions, I am here to help.</p>
              <p>Enjoy the rest of your day!</p>
              <p>Kind Regards,</p>
              <p>Financeamerica</p>
    ';
		$headers[] = 'Content-Type: text/html; charset=UTF-8';
		$headers[] = 'MIME-Version: 1.0\r\n';
		$headers[] = 'X-Priority: 3\r\n';
		$headers[] = 'X-Mailer: PHP'.phpversion().'\r\n';
    if (wp_mail($to, $subject, $body, $headers)) {
      error_log("email has been successfully sent to user whose email is " . $user_email);
    }else{
      error_log("email failed to sent to user whose email is " . $user_email);
    }
  }

	function send_payment_confirmation_email($user_id) {
	    $user = get_userdata($user_id);
	    $user_email = $user->data->user_email;
	    $user_full_name = get_user_meta($user_id,'first_name',true)." ".get_user_meta($user_id,'last_name',true);
	    $to = $user_email;
	    $subject = "Financeamerica Payment Confirmation";
	    $body = '
	              <h1>Dear ' . $user_full_name . ',</h1></br>
	              <p>Thank you for joining our site. Your account is now active.</p>
	              <p>We have received an amount of <strong>USD '.get_user_meta($user_id,'paid_package_amount',true).'</strong></p>
								<p><strong>Transaction Id: </strong>'.get_user_meta($user_id,'stripe_trans_id',true).'</p>
								<p><strong>Payment Method: </strong>'.get_user_meta($user_id,'payment_method',true).'</p>
								<p><strong>Paid Amount: </strong>USD'.get_user_meta($user_id,'paid_package_amount',true).'</p>
								<p><strong>Package: </strong>'.Customers_List::getUserSelectedPackages($user_id).'</p>
	              <p>Let me know if you have further questions, I am here to help.</p>
	              <p>Enjoy the rest of your day!</p>
	              <p>Kind Regards,</p>
	              <p>Financeamerica</p>
	    ';
			$headers[] = 'Content-Type: text/html; charset=UTF-8';
			$headers[] = 'MIME-Version: 1.0\r\n';
			$headers[] = 'X-Priority: 4\r\n';
			$headers[] = 'X-Mailer: PHP'.phpversion().'\r\n';
	    if (wp_mail($to, $subject, $body, $headers)) {
	      error_log("email has been successfully sent to user whose email is " . $user_email);
	    }else{
	      error_log("email failed to sent to user whose email is " . $user_email);
	    }
	  }
