<!DOCTYPE html>
<?php
global $wpdb;
$booking_id = $args['booking_id'];
$addon_total = 0;
$activity_total = 0;
$night_price = 1250;
$table_name = $wpdb->prefix."reservations";
$sql_query = "SELECT * FROM {$table_name} WHERE booking_id={$booking_id}";
$booking = $wpdb->get_row($sql_query);
if($booking->number_of_night > 11){
 $res_days = ($booking->number_of_night-11);
 $night_price = $night_price+($res_days*50);
 //echo 'night price again: '.$night_price;
}
$user = json_decode($booking->user_data);
?>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <div class = "page-header">
       <h1>
          Booking Id: <?php echo $booking->booking_id; ?>
       </h1>
    </div>
    <div class="row book-sec">
      <div class="col-sm-6">
        <span>Number of nights :</span>
        <span> <?php echo $booking->number_of_night;?></span>
      </div>
      <div class="col-sm-6">
        <span>Arrival Date :</span><span><?php echo getDateTime($booking->check_in_date,'l, F d, Y'); ?></span>
      </div>
      <div class="col-sm-6">
        <span>Departure Date :</span><span> <?php echo getDateTime($booking->check_out_date,'l, F d, Y'); ?></span>
      </div>
      <div class="col-sm-6">
        <span>Adults :</span><span><?php echo $booking->adults_per_room; ?></span>
      </div>


    </div>


    <?php
      $addons = json_decode($booking->addon_data,true);
      if(!empty($addons)){
        ?>
        <div class="row">
        <h2>Add Ons</h2>
        </div>
        <div class="row">
        <?php
        $addons = getAddonDataCollection($addons);
        foreach($addons as $key=>$addon){
          $sql_query  = "select * from wp_posts where id ='".$addon['addon_id']."'";
          $result = $wpdb->get_row($sql_query);
          ?>
          <div class="booking_add_ons">
          <h3><?php echo $result->post_title; ?></h3>
          <div class="charges_entry">
          <table class="add-tab">
          <tr><th>Date</th><th>Quantity</th><th>Price</th></tr>
          <?php
            foreach($addon['attribute'] as $key_c=>$addon_attribute){
              $addon_total = $addon_total+$addon_attribute['total_price'];
              ?>
                <tr>
                  <td><?php echo getDateTime($addon_attribute['date'],'d F'); ?></td>
                  <td><?php echo $addon_attribute['quantity'];?></td>
                  <td>@ $<?php echo $addon_attribute['price'];?> each</td>
                </tr>

              <?php
            }
          ?>
          </table>
          </div>
          </div>
          <?php
        }
        ?>
        </div>
        <?php
      }
    ?>


    <?php
      $activities = json_decode($booking->activity_data,true);
      if(!empty($activities)){
        ?>
        <div class="row">
        <h2>Activities</h2>
        </div>
        <div class="row book-add">
        <?php
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
        <div class="facilities_ctnt">
        <?php echo $result->post_content; ?>

        </div>
        </div>
        <br>
          <table class="add-tab">
          <tr><th>Date</th><th>Time</th><th>Quantity</th> <th>Price</th></tr>
        <?php
        foreach($activity['attribute'] as $key_c=>$activity_attribute){
          $activity_total = $activity_total+$activity_attribute['total_price'];
          ?>

          <tr><td><?php echo getDateTime($activity_attribute['date'],'l, F d,Y'); ?></td>
          <td></span><span><?php echo $activity_attribute['time']; ?></td>
          <td><?php echo $activity_attribute['quantity']; ?></td>
          <td>@ $<?php echo $activity_attribute['price']; ?>/each</td>
        </tr>

          <?php
        }
        ?>
        </table>
        </div>
          <?php
        }
        ?>
        </div>
        <?php
      }
    ?>

    <div class="boooking_details_data data_second book-sec">
          <!-- <div><h3>Activities Tax</h3></div>
          <div><h5>$18.76</h5></div> -->
          <!-- <div><h3>Activities Total(Tax Include)</h3></div>
          <div><h5>$268.76</h5></div> -->
          <?php
          if(isset($addon_total) && $addon_total>0){
            ?>
            <div><span>Add ons Total :</span><span>$<?php echo number_format($addon_total,2); ?></span></div>
            <?php
          }
          ?>
          <?php
          if(isset($activity_total) && $activity_total>0){
            ?>
            <div><span>Activity Total :</span><span>$<?php echo number_format($activity_total,2); ?></span></div>
            <?php
          }
          ?>

          <div><span>Booking Total :</span><span>$<?php echo number_format($night_price,2); ?></span></div>
          <!-- <div><h3>Add on Tax</h3></div>
          <div><h5>$75.00</h5></div> -->
          <div><span>Total :</span><span>$<?php echo number_format(($night_price+$addon_total+$activity_total),2);?></span></div>
          <div><span>Guest Name :</span><span><?php echo $user->first_name." ".$user->last_name; ?></span></div>
          <div><span>Address 1 :</span><span><?php echo $user->address_one; ?></span></div>
          <div><span>Address 2 :</span><span><?php echo $user->address_two; ?></span></div>
          <div><span>City :</span><span><?php echo $user->city; ?></span></div>
          <div><span>State/Province :</span><span><?php echo $user->state; ?></span></div>
          <div><span>Zip/Postal Code :</span><span><?php echo $user->zip_code; ?> <?php echo $user->country; ?></span></div>
          <div><span>E-mail Address :</span><span><?php echo $user->email; ?></span></div>
          <div><span>Phone :</span><span><?php echo $user->phone; ?></span></div>
          <div><span>Mobile Phone :</span><span><?php echo $user->mobile; ?></span></div>
          <!-- <div><h3>Credit Card</h3></div>
          <div><h5>American Express ************4242 Expiry 2/2024</h5></div>
          <div><h3>Special Requests</h3></div>
          <div><h5>Food Allergy</h5></div> -->

    </div>
  </body>
</html>
