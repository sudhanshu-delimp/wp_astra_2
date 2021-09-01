<?php
global $wpdb;

$dates = $args['date_data'];
$user = $args['user_data'];
$activities = $args['selected_activity_data'];
$addons = $args['selected_addon_data'];
$addon_total = 0;
$activity_total = 0;
$night_price = 1250;
//echo 'night price: '.$night_price;
if($args['number_of_night'] > 11){
 $res_days = ($args['number_of_night']-11);
 $night_price = $night_price+($res_days*50);
 echo 'night price again: '.$night_price;
}
?>
<div class="booking_preview">
    <div class="booking_title">
        <h3>You are booking:</h3>
    </div>
    <div class="boooking_details_data">
          <div><h3>Nights Stay</h3></div>
          <div><h5><?php echo $args['number_of_night']; ?></h5></div>
          <div><h3>Arrival Date</h3></div>
          <div><h5><?php echo getDateTime($args['check_in_date'],'l, F d, Y'); ?></h5></div>
          <div><h3>Departure Date</h3></div>
          <div><h5><?php echo getDateTime($args['check_out_date'],'l, F d, Y'); ?></h5></div>
          <div><h3>Adults</h3></div>
          <div><h5><?php echo $args['adults_per_room']; ?></h5></div>
          <!-- <div><h3>Florida Resident Weekday Rate</h3></div>
          <div><h5>Details</h5></div>
          <div><h3>Subtotal</h3></div>
          <div><h5>$1,998.00</h5></div>
          <div><h3>Tax and Fees</h3></div>
          <div><h5>$249.76</h5></div>
          <div><h3>Deposit due at time of booking</h3></div>
          <div><h5>$5,091.52</h5></div> -->
    </div>

    <?php
      if(!empty($addons)){
        ?>
        <div class="booking_title">
        <h3>Add Ons</h3>
        </div>
        <?php
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

    <?php
      if(!empty($activities)){
        ?>
        <div class="booking_title">
        <h3>Activities</h3>
        </div>
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
          <div><h5><?php echo $user['first_name']." ".$user['last_name']; ?></h5></div>
          <div><h3>Address 1</h3></div>
          <div><h5><?php echo $user['address_one']; ?></h5></div>
          <div><h3>Address 2</h3></div>
          <div><h5><?php echo $user['address_two']; ?></h5></div>
          <div><h3>City</h3></div>
          <div><h5><?php echo $user['city']; ?></h5></div>
          <div><h3>State/Province</h3></div>
          <div><h5><?php echo $user['state']; ?></h5></div>
          <div><h3>Zip/Postal Code</h3></div>
          <div><h5><?php echo $user['zip_code']; ?> <?php echo $user['country']; ?></h5></div>
          <div><h3>E-mail Address</h3></div>
          <div><h5><?php echo $user['email']; ?></h5></div>
          <div><h3>Phone</h3></div>
          <div><h5><?php echo $user['phone']; ?></h5></div>
          <div><h3>Mobile Phone</h3></div>
          <div><h5><?php echo $user['mobile']; ?></h5></div>
          <!-- <div><h3>Credit Card</h3></div>
          <div><h5>American Express ************4242 Expiry 2/2024</h5></div>
          <div><h3>Special Requests</h3></div>
          <div><h5>Food Allergy</h5></div> -->

    </div>
    <div class="booking_policy">
        <h3>Deposit Policy:</h3>
        <p>50% of total stay is required at the time of booking. The remaining balance will be charged to the card on file thirty (30) days prior to check in. If reservation is booked within (30) days of arrival date, the total cost of reservation is due at time of booking and reservation is non-refundable. Credit card used for booking will need to be presented at the time of check in.</p>

        <h3>Cancellation Policy:</h3>
        <p>Cancellation cutoff is thirty (30) days prior to your arrival date. If a cancellation in writing is received and confirmed by the resort 31 or more days prior to the scheduled arrival date of a reservation, a $250 USD per reservation cancellation fee will be applied and the remaining deposit will be refunded. Reservations become NON-REFUNDABLE 30 days prior to the scheduled arrival date of a reservation. Cancellations received 0-30 days prior to the scheduled date of arrival date are not eligible for a refund.

              The Bungalows Key Largo reserves the right not to provide exemptions from the cancellation policy above for medical matters, inclement weather conditions, travel delays or other unforeseen circumstances. We highly recommend purchasing travel insurance prior to your stay.</p>

        <h3>Modifications:</h3>
        <p>Any modifications made to reservations may result in rate changes and additional fees. Modifications made within 30 days of arrival date will render the entire cost of reservation non-refundable.</p>

        <h3>Tipping Policy:</h3>
        <p>Gratuity is not included in pricing, but is greatly appreciated. An itemized check totaling $0.00 for all items enjoyed at our food and beverage outlets will be presented at time of service to provide guests the opportunity to add a gratuity at their discretion.</p>

        <h3>Check-In:</h3>
        <p>Check in begins strictly at 4:00 PM EST. A valid photo ID matching the name on the reservation and intended form of payment must be present at check in. Credit card must match the form of payment provided at time of booking. Early Check-in requests will be accommodated when available, starting at 11:30 AM EST, and will incur an additional cost.
          <ul>
            <li>
            For early access to all resort amenities without access to your Bungalow, there is a fee of $300.00 plus taxes. Access to your Bungalow is not guaranteed until after 4:00 PM.
            </li>
          </ul>
       </p>

       <h3>
       Check-Out:
       </h3>
       <p>
       Check-out is 11:00 AM EST. <br>
       Late check-out requests will be accommodated when available and will incur an additional cost.
       <ul>
          <li>For continued access to resort amenities until 3:00 PM, there is a fee of $300.00 plus taxes and you must check out of your Bungalow no later than 11:00 AM.</li>
          <li>For continued access to your Bungalow and resort amenities until 3:00 PM, there is a fee of $600.00 plus taxes.</li>
      </ul>
       </p>
       <h3>
       Child Policy:
       </h3>
       <p>
       Bungalows Key Largo is an adults only resort. Minimum age to check in is 21 years old.
       </p>
       <h3>
       Pet Policy:
       </h3>
       <p>
       Pets are not allowed. We can accept ADA Registered Service Animals. Emotional Support Animals are not recognized by the ADA as services animals, therefore they are not allowed on property.
       </p>
       <h3>
       Smoking Policy:
       </h3>
       <p>
       The resort is smoke-free, including e-cigarettes.
       </p>
       <div class="form-check">
        <label class="form-check-label">
          <input type="checkbox" id="confirm_by_user" class="form-check-input" value="">I have read and understood the hotel's Reservation Policies and agree with all terms and conditions outlined within.
        </label>
      </div>

    </div>
</div>
