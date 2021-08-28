<?php
 require_once('../../../wp-load.php');
 global $wpdb;
 $tablename=$wpdb->prefix.'addon';
 //print_r($_POST['question_id']);
  foreach($_POST['question_id'] as $key=> $value){
   // echo "<pre>";
   //  print_r($value['answerid']['title']);

    //foreach($value['answerid'] as $key1=>$valueData){
     
      
        
       $day_date= implode('|',$value['answerid']['day_date']);
       $day_night= implode('|',$value['answerid']['day_night']);
       $day_price= implode('|',$value['answerid']['day_price']);
     //print_r($day_date);

       if($day_date!='' || $day_night!='' || $day_price!=''){
       //print_r($day_date);
         $data=array(
           'addon_id'   => $key,
           'parent_id'    =>$_POST['patient_id'],
           //'answer'     => $valueData,
           'title'     => $value['answerid']['title'], 
           'day_date'=> $day_date,
           'day_night'=> $day_night,
           'day_price'=> $day_price,
           'added_at'   => date('Y-m-d H:i:s'));
         
        $wpdb->insert($tablename, $data);
      }

    //}
 }

    
    

        
?>

