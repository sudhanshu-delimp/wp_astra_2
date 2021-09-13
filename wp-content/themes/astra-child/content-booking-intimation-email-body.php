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
<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Template</title>
  </head>
  <body style="margin:0px; padding:30px; font-size: 16px; color: #1d2327; background: #f7f7f7;">
    <div class="email-section" style="width: 1280px; max-width: 100%; margin: 0 auto;">
      <div class="row" style="background: #fff; padding: 30px; box-shadow:0 1px 4px rgb(0 0 0 / 6%);">
        <div class="email-head" style="text-align: center;">
          <div class="email-header">
            <img src="<?php echo get_site_url("wp-content/uploads/2021/08/gold-logogold-logo.png"); ?>" width="150px">
          </div>
          <div class="header-ctnt" style="text-align: left;">
            <span><h1 style="color: #1d2327; font-size: 30px;">Thank You for Reservation</h1></span>
            <span><h2 style="text-transform: uppercase; font-size: 20px; border-left: 2px solid #e1c11e; padding-left: 9px;">Reservation ID : <?php echo $booking->booking_id; ?></h2></span>
          </div>
        </div>
        <div class="email-body">
          <span><h4  style="font-size: 20px; margin-bottom: 0;">Thomas,</h4></span>
          <span><p>Thank You for your Reservation. Lorem Ipsum is simply dummy text of the printing and typesetting industry. .</p></span>
          <div class="email-info">
            <div><span>Number of nights :</span><span><?php echo $booking->number_of_night;?></span></div>
            <div><span>Arrival Date :</span><span><?php echo getDateTime($booking->check_in_date,'l, F d, Y'); ?></span></div>
            <div><span>Departure Date :</span><span><?php echo getDateTime($booking->check_out_date,'l, F d, Y'); ?></span></div>
            <div><span>Adults :</span><span><?php echo $booking->adults_per_room; ?></span></div>
          </div>
          <div class="addon-row">
          <?php
          /*START ADDON SECTION*/
          $addons = json_decode($booking->addon_data,true);
          if(!empty($addons)){
            $addons = getAddonDataCollection($addons);
            ?>
              <div class="adon-col">
                <h3 style="font-size: 26px; border-bottom: 1px solid #eee; padding-bottom: 12px;">Add Ons</h3>
                <?php
                foreach($addons as $key=>$addon){
                  $sql_query  = "select * from wp_posts where id ='".$addon['addon_id']."'";
                  $result = $wpdb->get_row($sql_query);
                  ?>
                  <span class="adon-one">
                    <h4 style="font-size: 22px; border-left: 2px solid #e1c11e; padding-left: 9px;"><?php echo $result->post_title; ?></h4>
                    <table style="width: 100%; text-align: center;">
                      <tr>
                        <th style="padding: 10px 10px; background: #1d2327; color: #fff;">Date</th>
                        <th style="padding: 10px 10px; background: #1d2327; color: #fff;">Quantity</th>
                        <th style="padding: 10px 10px; background: #1d2327; color: #fff;">Price</th>
                      </tr>
                      <?php
                      foreach($addon['attribute'] as $key_c=>$addon_attribute){
                        $addon_total = $addon_total+$addon_attribute['total_price'];
                        ?>
                        <tr>
                          <td style="padding: 10px 10px; background: #f2f1f1;"><?php echo getDateTime($addon_attribute['date'],'d F'); ?></td>
                          <td style="padding: 10px 10px; background: #f2f1f1;"><?php echo $addon_attribute['quantity'];?></td>
                          <td style="padding: 10px 10px; background: #f2f1f1;">@ $<?php echo $addon_attribute['price'];?> each</td>
                        </tr>
                        <?php
                      }
                      ?>
                    </table>
                  </span>
                  <?php
                }
                ?>
              </div>
              <?php
            }
            /*END ADDON SECTION*/
            /*START ACTIVITY SECTION*/
            $activities = json_decode($booking->activity_data,true);
            if(!empty($activities)){
              $activities = getActivityDataCollection($activities);
            ?>
              <div class="adon-col">
                <h3 style="font-size: 26px; border-bottom: 1px solid #eee; padding-bottom: 12px;">Activities</h3>
                <?php
                foreach($activities as $key=>$activity){
                  $sql_query  = "select * from wp_posts where id ='".$activity['activity_id']."'";
                  $result = $wpdb->get_row($sql_query);
                  $activity_image = wp_get_attachment_url(get_post_thumbnail_id($result->ID));
                  ?>
                  <span class="adon-one">
                  <h4 style="font-size: 22px; border-left: 2px solid #e1c11e; padding-left: 9px;"><?php echo $result->post_title; ?></h4>
                  <table style="width: 100%; text-align: center;">
                    <tr>
                      <th style="padding: 10px 10px; background: #1d2327; color: #fff;">Date</th>
                      <th style="padding: 10px 10px; background: #1d2327; color: #fff;">Time</th>
                      <th style="padding: 10px 10px; background: #1d2327; color: #fff;">Quantity</th>
                      <th style="padding: 10px 10px; background: #1d2327; color: #fff;">Price</th>
                    </tr>
                    <?php
                    foreach($activity['attribute'] as $key_c=>$activity_attribute){
                      $activity_total = $activity_total+$activity_attribute['total_price'];
                      ?>
                      <tr>
                        <td style="padding: 10px 10px; background: #f2f1f1;"><?php echo getDateTime($activity_attribute['date'],'l, F d,Y'); ?></td>
                        <td style="padding: 10px 10px; background: #f2f1f1;"><?php echo $activity_attribute['time']; ?></td>
                        <td style="padding: 10px 10px; background: #f2f1f1;"><?php echo $activity_attribute['quantity']; ?></td>
                        <td style="padding: 10px 10px; background: #f2f1f1;">@ $<?php echo $activity_attribute['price']; ?>/each</td>
                      </tr>
                      <?php
                    }
                    ?>
                  </table>
                  </span>
                  <?php
                }
                ?>
              </div>
              <?php
            }
              /*END ACTIVITY SECTION*/
              ?>
            </div>

        </div>

        <br><br>
        <div class="email-info" style="border: 1px solid #dddd;">
          <?php
          if(isset($addon_total) && $addon_total>0){
            ?>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #ddd; background: #f2f1f1;">
              <span style="=width: 50%; padding: 10px;">Add ons Total :</span>
              <span  style="=width: 50%; padding: 10px;">$<?php echo number_format($addon_total,2); ?></span>
            </div>
            <?php
          }
          if(isset($activity_total) && $activity_total>0){
            ?>
            <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #ddd; background: #f2f1f1;">
              <span style="=width: 50%; padding: 10px;">Activity Total :</span>
              <span style="=width: 50%; padding: 10px;">$<?php echo number_format($activity_total,2); ?></span>
            </div>
            <?php
          }
          ?>
          <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #ddd; background: #f2f1f1;">
            <span style="=width: 50%; padding: 10px;">Booking Total :</span>
            <span style="=width: 50%; padding: 10px;">$<?php echo number_format($night_price,2); ?></span>
          </div>
          <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #ddd; background: #f2f1f1;">
            <span style="=width: 50%; padding: 10px;">Total :</span>
            <span style="=width: 50%; padding: 10px;">$<?php echo number_format(($night_price+$addon_total+$activity_total),2);?></span>
          </div>
          <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #ddd; background: #f2f1f1;">
            <span style="=width: 50%; padding: 10px;">Guest Name :</span>
            <span style="=width: 50%; padding: 10px;"><?php echo $user->first_name." ".$user->last_name; ?></span>
          </div>
          <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #ddd; background: #f2f1f1;">
            <span style="=width: 50%; padding: 10px;">Address 1 :</span>
            <span style="=width: 50%; padding: 10px;"><?php echo $user->address_one; ?></span>
          </div>
          <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #ddd; background: #f2f1f1;">
            <span style="=width: 50%; padding: 10px;">Address 2 :</span>
            <span style="=width: 50%; padding: 10px;"><?php echo $user->address_two; ?></span>
          </div>
          <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #ddd; background: #f2f1f1;">
            <span style="=width: 50%; padding: 10px;">City :</span>
            <span style="=width: 50%; padding: 10px;"><?php echo $user->city; ?></span>
          </div>
          <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #ddd; background: #f2f1f1;">
            <span style="=width: 50%; padding: 10px;">State/Province :</span>
            <span style="=width: 50%; padding: 10px;"><?php echo $user->state; ?></span>
          </div>
          <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #ddd; background: #f2f1f1;">
            <span style="=width: 50%; padding: 10px;">Zip/Postal Code :</span>
            <span style="=width: 50%; padding: 10px;"><?php echo $user->zip_code; ?> <?php echo $user->country; ?></span>
          </div>
          <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #ddd; background: #f2f1f1;">
            <span style="=width: 50%; padding: 10px;">E-mail Address :</span>
            <span style="=width: 50%; padding: 10px;"><?php echo $user->email; ?></span>
          </div>
          <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #ddd; background: #f2f1f1;">
            <span style="=width: 50%; padding: 10px;">Phone :</span>
            <span style="=width: 50%; padding: 10px;"><?php echo $user->phone; ?></span>
          </div>
          <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #ddd; background: #f2f1f1;">
            <span style="=width: 50%; padding: 10px;">Mobile Phone :</span>
            <span style="=width: 50%; padding: 10px;"><?php echo $user->mobile; ?></span>
          </div>
        </div>
        <br><br>
        <div class="email-footer" style="display: flex; flex-direction: column; font-size: 18px; line-height: 24px;">
          <span>Thanks</span>
          <span>The Chateau On The Ocean</span>
        </div>
      </div>
    </div>
  </body>
</html>
