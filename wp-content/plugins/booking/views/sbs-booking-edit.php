<?php
  global $wpdb;
  $addon_total = 0;
  $activity_total = 0;
  $night_price = 1250;
  $table_name = $wpdb->prefix."reservations";
  $sql_query = "SELECT * FROM {$table_name} WHERE id={$booking_id}";
  $booking = $wpdb->get_row($sql_query);
  if($booking->number_of_night > 11){
   $res_days = ($booking->number_of_night-11);
   $night_price = $night_price+($res_days*50);
   echo 'night price again: '.$night_price;
  }
  $user = json_decode($booking->user_data);
  //print_this($booking);
?>
<div class = "page-header">
   <h1>
      Booking Id: <?php echo $booking->booking_id; ?>
   </h1>
</div>
<div class="row">
  <div class="col-sm-6">
    Number of nights
  </div>
  <div class="col-sm-6">
    <?php echo $booking->number_of_night;?>
  </div>
  <div class="col-sm-6">
    Arrival Date
  </div>
  <div class="col-sm-6">
    <?php echo getDateTime($booking->check_in_date,'l, F d, Y'); ?>
  </div>
  <div class="col-sm-6">
    Departure Date
  </div>
  <div class="col-sm-6">
    <?php echo getDateTime($booking->check_out_date,'l, F d, Y'); ?>
  </div>
  <div class="col-sm-6">
    Adults
  </div>
  <div class="col-sm-6">
    <?php echo $booking->adults_per_room; ?>
  </div>

</div>

<div class="row">
<h2>Add Ons</h2>
</div>
<div class="row">
<?php
  $addons = json_decode($booking->addon_data,true);
  if(!empty($addons)){
    $addons = getAddonDataCollection($addons);
    foreach($addons as $key=>$addon){
      $sql_query  = "select * from wp_posts where id ='".$addon['addon_id']."'";
      $result = $wpdb->get_row($sql_query);
      ?>
      <div class="booking_add_ons">
      <h3><?php echo $result->post_title; ?></h3>
      <div class="charges_entry">
      <?php
        foreach($addon['attribute'] as $key_c=>$addon_attribute){
          $addon_total = $addon_total+$addon_attribute['total_price'];
          ?>
          <span><?php echo getDateTime($addon_attribute['date'],'d F'); ?></span><span><?php echo $addon_attribute['quantity'];?> @ $<?php echo $addon_attribute['price'];?> each</span>
          <?php
        }
      ?>
      </div>
      </div>
      <?php
    }
  }
?>
</div>
<div class="row">
<h2>Activities</h2>
</div>
<div class="row">
<?php
  $activities = json_decode($booking->activity_data,true);
  if(!empty($activities)){
    $activities = getActivityDataCollection($activities);
    foreach($activities as $key=>$activity){
      $sql_query  = "select * from wp_posts where id ='".$activity['activity_id']."'";
      $result = $wpdb->get_row($sql_query);
      $activity_image = wp_get_attachment_url(get_post_thumbnail_id($result->ID));
      ?>
    <div class="booking_add_ons">
    <h3><?php echo $result->post_title; ?></h3>
    <div class="facilities_ct">
    <?php
    if(!empty($activity_image)){
      ?>
      <div class="facilities_img">
       <img src="<?php echo $activity_image;?>">
      </div>
      <?php
    }
    ?>
    <div class="facilities_data">
    <p>
    <?php echo $result->post_content; ?>
    </p>
    </div>
    </div>
    <?php
    foreach($activity['attribute'] as $key_c=>$activity_attribute){
      $activity_total = $activity_total+$activity_attribute['total_price'];
      ?>
      <div class="facilities_details_date">
      <div>
      <span><?php echo getDateTime($activity_attribute['date'],'l, F d,Y'); ?></span>
      </div>
      <div>
      <span><?php echo $activity_attribute['time']; ?></span>
      </div>
      <div>
      <span><?php echo $activity_attribute['quantity']; ?></span>
      </div>
      <div>
      <span>@ $<?php echo $activity_attribute['price']; ?>/each</span>
      </div>
      </div>
      <?php
    }
    ?>
    </div>
      <?php
    }
  }
?>
</div>
<div class="boooking_details_data data_second">
      <!-- <div><h3>Activities Tax</h3></div>
      <div><h5>$18.76</h5></div> -->
      <!-- <div><h3>Activities Total(Tax Include)</h3></div>
      <div><h5>$268.76</h5></div> -->
      <?php
      if(isset($addon_total) && $addon_total>0){
        ?>
        <div><h3>Add ons Total</h3></div>
        <div><h5>$<?php echo number_format($addon_total,2); ?></h5></div>
        <?php
      }
      ?>
      <?php
      if(isset($activity_total) && $activity_total>0){
        ?>
        <div><h3>Activity Total</h3></div>
        <div><h5>$<?php echo number_format($activity_total,2); ?></h5></div>
        <?php
      }
      ?>

      <div><h3>Booking Total</h3></div>
      <div><h5>$<?php echo number_format($night_price,2); ?></h5></div>
      <!-- <div><h3>Add on Tax</h3></div>
      <div><h5>$75.00</h5></div> -->
      <div><h3>Total</h3></div>
      <div><h5>$<?php echo number_format(($night_price+$addon_total+$activity_total),2);?></h5></div>
      <div><h3>Guest Name</h3></div>
      <div><h5><?php echo $user->first_name." ".$user->last_name; ?></h5></div>
      <div><h3>Address 1</h3></div>
      <div><h5><?php echo $user->address_one; ?></h5></div>
      <div><h3>Address 2</h3></div>
      <div><h5><?php echo $user->address_two; ?></h5></div>
      <div><h3>City</h3></div>
      <div><h5><?php echo $user->city; ?></h5></div>
      <div><h3>State/Province</h3></div>
      <div><h5><?php echo $user->state; ?></h5></div>
      <div><h3>Zip/Postal Code</h3></div>
      <div><h5><?php echo $user->zip_code; ?> <?php echo $user->country; ?></h5></div>
      <div><h3>E-mail Address</h3></div>
      <div><h5><?php echo $user->email; ?></h5></div>
      <div><h3>Phone</h3></div>
      <div><h5><?php echo $user->phone; ?></h5></div>
      <div><h3>Mobile Phone</h3></div>
      <div><h5><?php echo $user->mobile; ?></h5></div>
      <!-- <div><h3>Credit Card</h3></div>
      <div><h5>American Express ************4242 Expiry 2/2024</h5></div>
      <div><h3>Special Requests</h3></div>
      <div><h5>Food Allergy</h5></div> -->

</div>
