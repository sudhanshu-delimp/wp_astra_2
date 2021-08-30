<?php
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class Customers_List extends WP_List_Table {
	/** Class constructor */
	public function __construct() {
		parent::__construct( [
			'singular' => __( 'User', 'sp' ), //singular name of the listed records
			'plural'   => __( 'Users', 'sp' ), //plural name of the listed records
			'ajax'     => false //should this table support ajax?
		] );
	}

	public static function get_customers( $per_page = 5, $page_number = 1 ) {
		global $wpdb;
		$sql = "SELECT * FROM {$wpdb->prefix}users INNER JOIN {$wpdb->prefix}usermeta ON wp_users.ID = wp_usermeta.user_id WHERE wp_usermeta.meta_key = 'wp_capabilities' AND wp_usermeta.meta_value LIKE '%subscriber%'";
		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}
		else{
			$sql .= ' ORDER BY ID';
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' DESC';
		}
		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;
		$rusers= $wpdb->get_results( $sql, 'ARRAY_A' );
		$users = array();
		foreach($rusers as $user) {
			$new_user = array();
			$new_user['ID'] = $user['ID'];
			$new_user['user_login'] = $user['user_login'];
			$new_user['user_email'] = $user['user_email'];
			$new_user['user_phone'] = get_user_meta($user['ID'], 'phone', true);
			$new_user['user_registered'] = $user['user_registered'];
			$new_user['user_package'] = self::getUserSelectedPackages($user['ID']);
			$is_broker = get_user_meta($user['ID'], 'is_broker', true);
			$new_user['is_broker'] = ($is_broker == 1)?'Yes':'No';
			$new_user['stripe_trans_id'] = get_user_meta($user['ID'], 'stripe_trans_id', true);
			$new_user['paid_package_amount'] = get_user_meta($user['ID'], 'paid_package_amount', true);
			array_push($users, $new_user);
		}
		return $users;
	}

	public static function getUserSelectedPackages($userId){
		$packages = [];
		$ids =  get_user_meta($userId, 'package', true);
		if(!empty($ids)){
			$ids = explode(',', $ids);
			foreach($ids as $id){
				$packages[] = get_term($id)->name;
			}
		}
		return implode('<br>',$packages);
	}

	public static function getUserSelectedPackagesTwo($userId){
		$packages = [];
		$ids =  get_user_meta($userId, 'package', true);
		if(!empty($ids)){
			$ids = explode(',', $ids);
			foreach($ids as $id){
				$packages[] = get_term($id)->name;
			}
		}
		return implode(',',$packages);
	}

	public static function delete_customer( $id ) {
		global $wpdb;
		$wpdb->delete(
		  "{$wpdb->prefix}users",
		  [ 'ID' => $id ],
		  [ '%d' ]
		);
		$wpdb->delete(
		  "{$wpdb->prefix}usermeta",
		  [ 'user_id' => $id ],
		  [ '%d' ]
		);
	}

	public static function record_count() {
		// global $wpdb;
		// $author_search = new WP_User_Query( array( 'role' => 'subscriber' ) );
		// $subscriber_count  = $wpdb->num_rows;
		// return $subscriber_count;
		return count( get_users( array( 'role' => 'subscriber' ) ) );
	}

	public function no_items() {
		_e( 'No users avaliable.', 'sp' );
	}

	public function column_name( $item ) {
		// create a nonce
		$delete_nonce = wp_create_nonce('sp_delete_user');
		$title = '<strong>' . $item['user_login'] . '</strong>';
		$actions = [
			'view' => sprintf('<a href="?post_type=%s&page=%s&user=%s">View</a>','videos','registered-user-profile', absint($item['ID'])),
		  'delete' => sprintf('<a href="?post_type=%s&page=%s&action=%s&user=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['post_type'] ),esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['ID'] ), $delete_nonce )
		];
		return $title . $this->row_actions( $actions );
	}

	public function column_default( $item, $column_name ) {
		switch ($column_name) {
		  case 'user_login':
			  return self::column_name($item);
		  case 'user_email':
		    return $item[$column_name];
			case 'user_phone':
		    return $item[$column_name];
			case 'user_registered':
		    return $item[$column_name];
			case 'user_package':
				return $item[$column_name];
			case 'is_broker':
				return $item[$column_name];
			case 'paid_package_amount':
				return $item[$column_name];
			case 'stripe_trans_id':
				return $item[$column_name];
		  default:
		    return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	function column_cb($item) {
		return sprintf(
		  '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']
		);
	}

	function get_columns() {
		$columns = [
		  'cb'      => '<input type="checkbox" />',
		  'user_login'    => __( 'User Name', 'sp' ),
		  'user_email' => __( 'User Email', 'sp' ),
			'user_phone' => __( 'User Phone', 'sp' ),
			'user_package' => __( 'User Package', 'sp' ),
			'is_broker' => __( 'Professional', 'sp' ),
		  'paid_package_amount'    => __( 'Paid Amount', 'sp' ),
			'stripe_trans_id'    => __( 'Transaction Id', 'sp' ),
			'user_registered' => __( 'Registered', 'sp' ),
		];
		return $columns;
	}

	public function get_sortable_columns() {
		$sortable_columns = array(
		  'user_email' => array( 'user_email', true ),
		  'user_registered' => array( 'user_registered', false )
		);
		return $sortable_columns;
	}

	public function get_bulk_actions() {
		$actions = [
		  'bulk-delete' => 'Delete'
		];
		return $actions;
	}

	public function prepare_items() {
		//$this->_column_headers = $this->get_column_info();
		$this->_column_headers = [
			$this->get_columns(),
			[], // hidden columns
			$this->get_sortable_columns(),
			$this->get_primary_column_name(),
		];
		/** Process bulk action */
		$this->process_bulk_action();
		$per_page     = $this->get_items_per_page( 'users_per_page', 10 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();
		$this->set_pagination_args( [
		  'total_items' => $total_items, //WE have to calculate the total number of items
		  'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );
		$this->items = self::get_customers( $per_page, $current_page );
	}

	public function process_bulk_action() {
	//Detect when a bulk action is being triggered...
	if ( 'delete' === $this->current_action() ) {
	  // In our file that handles the request, verify the nonce.
	  $nonce = esc_attr( $_REQUEST['_wpnonce'] );
	  if ( ! wp_verify_nonce( $nonce, 'sp_delete_user' ) ) {
	    die( 'Go get a life script kiddies' );
	  }
	  else {
	    self::delete_customer( absint( $_GET['user'] ) );
	    // wp_redirect( esc_url( add_query_arg() ) );
	    // exit;
			echo '<div class="notice notice-success is-dismissible"><p>Deleted Successfully..</p></div>';
	  }
	}
	// If the delete bulk action is triggered
	if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
	     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
	) {
	  $delete_ids = esc_sql($_POST['bulk-delete']);
	  // loop over the array of record IDs and delete them
	  foreach ( $delete_ids as $id ) {
	    self::delete_customer( $id );
	  }
	  // wp_redirect(esc_url(add_query_arg()));
	  // exit;
		echo '<div class="notice notice-success is-dismissible"><p>Deleted Successfully..</p></div>';
	}
	}
}


add_action('admin_menu','my_admin_menu');
function my_admin_menu(){
	//add_menu_page('Registered Users','Registered Users','manage_options','package-users','my_admin_page_contents');
	add_submenu_page(
		'edit.php?post_type=videos',
		__( 'Registered Users', 'my-textdomain' ),
		__( 'Registered Users', 'my-textdomain' ),
		'manage_options',
		'registered-users',
		'my_admin_page_contents'
	);

	add_submenu_page(
		'',
		__( 'Registered Users Profile', 'my-textdomain' ),
		__( 'Registered Users Profile', 'my-textdomain' ),
		'manage_options',
		'registered-user-profile',
		'registered_user_profile'
	);
}

 function my_admin_page_contents() {
	 $customers_obj = new Customers_List();
	?>
	<div class="wrap">
		<h2>Registered Users</h2>

		<form method="post">
			<?php
			$customers_obj->prepare_items();
			$customers_obj->display();
			?>
		</form>
	</div>
<?php
}

function registered_user_profile() {
	$customers_obj = new Customers_List();
 ?>
 <div class="wrap">
	 <h2>Register User Detail</h2>
	 <?php
	 $user_id = $_GET['user'];
	 $user = get_userdata($user_id);
	 ?>
	<div class="update-nag notice notice-warning inline"><a href="edit.php?post_type=videos&page=registered-users">Back To Registered Users</a></div>
	<table class="wp-list-table widefat fixed striped table-view-list users">
	<tbody>
	<tr>
		<th><strong>Username</strong></th>
		<td><?php echo $user->data->user_login; ?></td>
	</tr>
	<tr>
		<th><strong>Full Name</strong></th>
		<td><?php echo get_user_meta($user->data->ID, 'first_name', true).' '.get_user_meta($user->data->ID, 'last_name', true); ?></td>
	</tr>
	<tr>
		<th><strong>Email</strong></th>
		<td><?php echo $user->data->user_email; ?></td>
	</tr>
	<tr>
		<th><strong>Phone</strong></th>
		<td><?php echo get_user_meta($user->data->ID, 'phone', true); ?></td>
	</tr>
	<tr>
		<th><strong>Address</strong></th>
		<td><?php echo get_user_meta($user->data->ID, 'address', true); ?></td>
	</tr>
	<tr>
		<th><strong>City</strong></th>
		<td><?php echo get_user_meta($user->data->ID, 'city', true); ?></td>
	</tr>
	<tr>
		<th><strong>State</strong></th>
		<td><?php echo get_user_meta($user->data->ID, 'state', true); ?></td>
	</tr>
	<tr>
		<th><strong>Zip Code</strong></th>
		<td><?php echo get_user_meta($user->data->ID, 'zip_code', true); ?></td>
	</tr>
	<tr>
		<th><strong>Professional with a license</strong></th>
		<td><?php echo (get_user_meta($user->data->ID, 'is_broker', true) == 1)?'Yes':'No';?></td>
	</tr>
	<?php
	if(get_user_meta($user->data->ID, 'is_broker', true) == 1){
		?>
		<tr>
			<th><strong>Proof of License</strong></th>
			<td><a target="_blank" href="<?php echo get_user_meta($user->data->ID, 'upload_file_url', true);?>"><strong>View Proof of License</strong></a></td>
		</tr>
		<?php
	}
	?>
	<tr>
		<th><strong>Selected Package</strong></th>
		<td><?php echo Customers_List::getUserSelectedPackages($user->data->ID); ?></td>
	</tr>
	<tr>
		<th><strong>Paid Amount</strong></th>
		<td><?php echo '$'.get_user_meta($user->data->ID, 'paid_package_amount', true);?></td>
	</tr>
	<tr>
		<th><strong>Transaction Id</strong></th>
		<td><?php echo get_user_meta($user->data->ID, 'stripe_trans_id', true);?></td>
	</tr>
	<tr>
		<th><strong>Registered At</strong></th>
		<td><?php echo date_format(date_create($user->data->user_registered),"m/d/Y H:i A"); ?></td>
	</tr>
	</tbody>
	</table>
 </div>
<?php
}
?>
