<?php
 require_once('../../../wp-load.php');
 global $wpdb;
 //print_r($_POST);
 $tablename=$wpdb->prefix.'information_guest';
  

         $data=array(
           'first_name'   =>$_POST['first_name'],
           'last_name'    =>$_POST['last_name'],
           'address'      =>$_POST['address'],
           'city'         =>$_POST['city'],
           'state'        =>$_POST['State_Province'],
           'zip_code'     =>$_POST['Zip_Postal_Code'],
           'mobile_no'     =>$_POST['Mobile_Phone'],
           'email_address' =>$_POST['email_address'],
           'added_at'     => date('Y-m-d H:i:s'));         
        $wpdb->insert($tablename, $data);



    
    

        
?>

