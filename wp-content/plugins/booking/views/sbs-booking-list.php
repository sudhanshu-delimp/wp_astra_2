<?php
require_once(ABSPATH.'wp-admin/includes/class-wp-list-table.php');

class SBS_Booking_List extends WP_List_Table{

  public function prepare_items(){
    $per_page = 10;
    $current_page = $this->get_pagenum();

    $start = (($current_page-1)*$per_page);
    $limit = $per_page;
    $this->items = $this->get_booking_data(['start'=>$start,'limit'=>$limit]);
    $total_items = count($this->get_booking_data());

    $this->set_pagination_args(["total_items"=>$total_items,"per_page"=>$per_page]);
    $columns = $this->get_columns();
    $hidden = $this->get_hidden_columns();
    $shortable = $this->get_sortable_columns();
    $this->_column_headers = [$columns,$hidden,$shortable];
  }

  public function get_booking_data($params = []){
    global $wpdb;
    $order_by = isset($_GET['orderby'])?trim($_GET['orderby']):"created_at";

    $order = isset($_GET['order'])?trim($_GET['order']):"desc";

    $search_term = isset($_POST['s'])?trim($_POST['s']):"";

    $table_name = $wpdb->prefix."reservations";
    $sql_query = "";
    $sql_query .= "SELECT id,booking_id,user_name,check_in_date,check_out_date,created_at FROM {$table_name} WHERE 1=1";
    if(!empty($search_term)){
      $sql_query .= " AND booking_id LIKE '%{$search_term}%' OR user_name LIKE '%{$search_term}%'";
    }
    if(!empty($order_by) && !empty($order)){
      $sql_query .= " ORDER BY {$order_by} {$order}";
    }
    if(count($params) > 0 && isset($params['start']) && isset($params['limit'])){
      $sql_query .= " LIMIT {$params['start']},{$params['limit']}";
    }
    //echo '<h3>'.$sql_query.'</h3>';
    $bookings = $wpdb->get_results($sql_query);
    return $bookings;
  }

  public function get_hidden_columns(){
    return [];
  }

  public function get_sortable_columns(){
    return [
      "user_name"=>["user_name", true],
      "booking_id"=>["booking_id", true],
      "created_at"=>["created_at", true],
      "check_in_date"=>["check_in_date", true],
    ];
  }

  public function get_columns(){
    $columns = [
      "booking_id"=>"Booking Id",
      "user_name"=>"Customer Name",
      "check_in_date"=>"Checkin Date",
      "check_out_date"=>"Checkout Date",
      "created_at"=>"Booking Date"
    ];
    return $columns;
  }

  public function column_default($item, $column_name){
    switch($column_name){
      case 'booking_id':{
        return $item->$column_name;
      }break;
      case 'user_name': {
        return $item->$column_name;
      } break;
      case 'check_in_date':{
        return $item->$column_name;
      }break;
      case 'check_out_date':{
        return $item->$column_name;
      }break;
      case 'created_at':{
        return getDateTime($item->$column_name, 'Y-m-d h:i A');
      }break;
      default:
        return "no value";
    }
  }

  public function column_booking_id($item){
    $action = [
      "edit"=>sprintf('<a href="?page=%s&action=%s&booking_id=%s">Edit</a>',$_GET['page'],'sgs-booking-edit',$item->id),
      "delete"=>sprintf('<a href="?page=%s&action=%s&booking_id=%s">Delete</a>',$_GET['page'],'sgs-booking-delete',$item->id)
    ];
    return sprintf('%1s %2s',$item->booking_id,$this->row_actions($action));
  }
}

function sbs_show_booking_list(){
  $booking_list = new SBS_Booking_List();
  $booking_list->prepare_items();
  echo '<h3>Booking List</h3>';
  echo '<form method="POST" name="frn_search_booking" action="'.$_SERVER['PHP_SELF'].'?page=sbs-booking-list">';
  $booking_list->search_box("Search","search-booking");
  echo '</form>';
  $booking_list->display();
}

sbs_show_booking_list();
?>
