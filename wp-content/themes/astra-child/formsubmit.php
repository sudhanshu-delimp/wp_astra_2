<?php
 require_once('../../../wp-load.php');
 global $wpdb;

     

    $tablename=$wpdb->prefix.'check_room_availability';

    $data=array(
        'check_in_date' => $_POST['checkin'], 
        'check_out_date' => $_POST['checkout'],
        'number_of_night' => $_POST['number_of_night'], 
        'accommodation' => $_POST['accommodation'], 
        'adults_per_Room' => $_POST['adults_per_Room'], 
        'promo_code' => $_POST['Promo_Code'], 
        'agents_IATA_number' => $_POST['agents'], 
        'added_at'          => date('Y-m-d H:i:s'));

      
     $wpdb->insert($tablename, $data);

        
?>

