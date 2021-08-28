<?php
 require_once('../../../wp-load.php');
 global $wpdb;
  //print_r($_POST['activity']);
 $tablename=$wpdb->prefix.'activitiy';
  foreach($_POST['activity'] as $key=> $value){

    $Day= implode('|',$value['answerid']['Day']);
    $timeslot= implode('|',$value['answerid']['timeslot']);
    $Qty= implode('|',$value['answerid']['Qty']);

         $data=array(
           'activity_id'  => $key,
           'parent_id'    =>$_POST['patient_id'],
           //'answer'       => $valueData,
           'title'        => $value['answerid']['title'],
           'day_date'      => $Day,
           'timeslot'      => $timeslot,
           'Qty'          =>  $Qty,
           'price'        => $value['answerid']['price'],
           'added_at'     => date('Y-m-d H:i:s'));         
        $wpdb->insert($tablename, $data);

 }



    
    

        
?>

