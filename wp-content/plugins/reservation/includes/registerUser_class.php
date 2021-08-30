<?php
//Add custom field in frontend registration field
global $cusd_user;
class registerUser{
	public function __construct() {
		add_action( 'register_form', [$this,'crf_registration_form']);
		add_filter( 'registration_errors', [$this,'crf_registration_errors'], 10, 3 );
		add_action( 'user_register', [$this,'crf_user_register'] );
		add_filter( 'login_redirect', [$this,'my_login_redirect'], 10, 3 );
		add_action( 'admin_post_process_pacakge_payment', [$this,'process_pacakge_payment'] );
	}

	public function crf_registration_form() {
		$first_name = ! empty( $_POST['first_name'] ) ? intval( $_POST['first_name'] ) : '';
		$last_name = ! empty( $_POST['last_name'] ) ? intval( $_POST['last_name'] ) : '';
		$phone = ! empty( $_POST['phone'] ) ? intval( $_POST['phone'] ) : '';
		?>
		<p>
			<label for="first_name"><?php esc_html_e( 'First Name', 'crf' ) ?><br/>
				<input type="text" id="first_name" name="first_name" value="<?php echo esc_attr( $first_name ); ?>" class="input"/>
			</label>
		</p>
		<p>
			<label for="last_name"><?php esc_html_e( 'Last Name', 'crf' ) ?><br/>
				<input type="text" id="last_name" name="last_name" value="<?php echo esc_attr( $last_name ); ?>" class="input"/>
			</label>
		</p>
		<p>
			<label for="phone"><?php esc_html_e( 'Phone', 'crf' ) ?><br/>
				<input type="text" id="phone" name="phone" value="<?php echo esc_attr( $phone ); ?>" class="input"/>
			</label>
		</p>
		<p>
			<div class="form-check">
			<input type="checkbox" class="form-check-input" id="exampleCheck1Broker" value="1" name="is_broker">
			<label class="form-check-label" for="exampleCheck1Broker">Are You a Broker</label>
			</div>
		</p>
		<p>
			<label for="phone"><?php esc_html_e( 'Choose Package', 'crf' ) ?><br/>
				<?php
				$terms = get_terms('types');
				foreach($terms as $key=>$term){
					?>
					<div class="form-check">
					<input type="checkbox" class="form-check-input" id="exampleCheck1<?php echo $key;?>" name="package[]" value="<?php echo $term->slug; ?>">
					<label class="form-check-label" for="exampleCheck1<?php echo $key;?>"><?php echo $term->name; ?></label>
					</div>
					<?php
				}?>
			</label>
		</p>
		<?php
	}

	public function crf_registration_errors( $errors, $sanitized_user_login, $user_email ) {
		if ( empty( $_POST['first_name'] ) ) {
			$errors->add( 'first_name_error', __( '<strong>ERROR</strong>: Please enter your first name.', 'crf' ) );
		}
		if ( empty( $_POST['last_name'] ) ) {
			$errors->add( 'last_name_error', __( '<strong>ERROR</strong>: Please enter your last name.', 'crf' ) );
		}
		if ( empty( $_POST['phone'] ) ) {
			$errors->add( 'phone_error', __( '<strong>ERROR</strong>: Please enter your phone number.', 'crf' ) );
		}
		return $errors;
	}

	public function crf_user_register( $user_id ) {
		if ( ! empty( $_POST['first_name'] ) ) {
			update_user_meta( $user_id, 'first_name', trim($_POST['first_name']) );
		}
		if ( ! empty( $_POST['last_name'] ) ) {
			update_user_meta( $user_id, 'last_name', trim($_POST['last_name']) );
		}
		if ( ! empty( $_POST['phone'] ) ) {
			update_user_meta( $user_id, 'phone', trim($_POST['phone']) );
		}

		$amount = 0;
		$is_broker = 0;
		if(count($_POST['package'])>0){
			$amount = (count($_POST['package']) == 2)?1500:1000;
		}
		if(!empty($_POST['is_broker'])){
			$amount = $amount-250;
			$is_broker = 1;
		}
		update_user_meta( $user_id, 'package', implode(',',$_POST['package']));
		update_user_meta( $user_id, 'is_broker', $is_broker);
		update_user_meta( $user_id, 'paid_package_amount', number_format($amount, 2));
	}

	public function my_login_redirect( $redirect_to, $request, $user ) {
	    //is there a user to check?
	    global $user;
	    if ( isset( $user->roles ) && is_array( $user->roles ) ) {
	        if(in_array('subscriber',$user->roles)){
	          return home_url('archives/videos');
	        }
	        else{
	          return $redirect_to;
	        }
	    } else {
	        return $redirect_to;
	    }
	}

	public function process_pacakge_payment() {
	global $wpdb;
	$amount = 0;
	$is_broker = 0;
	if(count($_POST['package'])>0){
		$amount = (count($_POST['package']) == 2)?1500:1000;
	}
	if(!empty($_POST['is_broker'])){
		$amount = $amount-250;
		$is_broker = 1;
	}
	update_user_meta( $_POST['user_id'], 'package', implode(',',$_POST['package']));
	update_user_meta( $_POST['user_id'], 'is_broker', $is_broker);
	update_user_meta( $_POST['user_id'], 'paid_package_amount', number_format($amount, 2));
	echo  $amount;
	print_this($_POST);
	}

	public static function init() {
			static $instance = false;

			if ( ! $instance ) {
					$instance = new registerUser();
			}

			return $instance;
	}
}

function register_User() {
    return registerUser::init();
}

register_User();
