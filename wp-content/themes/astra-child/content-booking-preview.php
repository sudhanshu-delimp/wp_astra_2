<?php 
  $dates = $args['date_data'];
  $user = $args['user_data'];
  $activities = $args['selected_activity_data'];
  $addons = $args['selected_addon_data'];
  
  echo '<h1>check_in_date : '.$args['check_in_date'].'</h1><br>'; 
  echo '<h1>check_out_date : '.$args['check_out_date'].'</h1><br>'; 
  echo '<h1>number_of_night : '.$args['number_of_night'].'</h1><br>'; 
  echo '<h1>adults_per_room : '.$args['adults_per_room'].'</h1><br>'; 
  if(!empty($user)){
    echo '<h1>User Data</h1>';
    foreach($user as $values){
      foreach($values as $key=>$value){
        echo $key.": ".$value."<br>";
      }
    }
  }

  
  if(!empty($activities)){
    echo '<h1>Activities Data</h1>';
    foreach($activities as $values){
      echo '<hr>';
      foreach($values as $key=>$value){
        echo $key.": ".$value."<br>";
      }
    }
  }

  
  if(!empty($addons)){
    echo '<h1>Addons Data</h1>';
    foreach($addons as $values){
      echo '<hr>';
      foreach($values as $key=>$value){
        echo $key.": ".$value."<br>";
      }
    }
  }
?>