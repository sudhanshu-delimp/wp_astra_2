-<?php /*Template Name:Reservation*/ ?>
<?php
  get_header();
  global $wpdb;
  $today = getDateTime();
  $current_date = explode("-",$today);
  $current_year = $current_date[0];
  $current_day = $current_date[2];
  $current_month = $current_date[1];
  $months = monthList();
?>
<div class="reserve-ban"><img src="http://localhost/wp_astra/wp-content/uploads/2021/09/fun-two.jpg"></div>
<!-- MultiStep Form -->
<div class="container-fluid" id="grad1">
  <div class="row justify-content-center mt-0">
    <div class="col-11 col-sm-12 col-md-12 col-lg-12 text-center p-0 mt-3 mb-2">
      <div class="card px-0 pt-4 pb-0 mt-3 mb-3">
        <h2 class="main-head">Reservations</h2>
        <div class="row">
          <div class="col-md-12 mx-0">
            <div id="msform">
              <!-- progressbar -->
              <ul id="progressbar">
                <li class="active" id="account"><strong>Dates</strong></li>
                <li id="personal"><strong>Add Ons</strong></li>
                <li id="payment"><strong>Activities</strong></li>
                <li id="confirm"><strong>Guest Details</strong></li>
                <li id="preview"><strong>preview</strong></li>
                <li id="success"><strong>Confirmation</strong></li>
              </ul> <!-- fieldsets -->
              <div>
                <fieldset id="step-one">
                  <div class="form-card reserve-user">
                    <h2 class="fs-title">Check Room Availability</h2>
                    <span><b>When making room reservations, we have a two night minimum for Garden Bungalows and a
                        four-night minimum for Waterfront Bungalows.</b><br>
                      <i>Please select below and then click</i> 'Check Now'.</span>
                    <div class="form-contain"  id="step-one-contain">
                      <div class="form-group col-sm-12 bold date-fin">
                        <label for="arrival_date">Arrival Date<sup></label>
                          <select class="form-control" name="selected_month" onchange="updateCal()" id="selected_month" required>
                            <?php
                            foreach($months as $key=>$month){
                              $selected = ($key==$current_month)?'selected':'';
                              echo '<option value="'.$key.'" '.$selected.'>'.$month.'</option>';
                            }
                            ?>
                          </select>
                          <select class="form-control pad" name="selected_day" onchange="updateCal()" id="selected_day" required>
                            <?php
                            for($i=1;$i<=31;$i++){
                              $value = (strlen($i) == 1)?'0'.$i:$i;
                              $selected = (intval($value)==$current_day)?'selected':'';
                              echo '<option value="'.$value.'" '.$selected.'>'.$i.'</option>';
                            }
                            ?>
                          </select>
                          <select class="form-control pad" name="selected_year" onchange="updateCal()" id="selected_year" required>
                            <?php
                            for($i=$current_year;$i<=($current_year+4);$i++){
                              $selected = ($i==$current_year)?'selected':'';
                              echo '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
                            }
                            ?>
                          </select>
                          <input class="form-control" name="arrival_date" type="hidden" id="arrival_date"
                            placeholder="Select Arrival Date" autocomplete="off" required>

                      </div>
                      <div class="calendar-container">

                          </div>
                      <div class="form-group col-sm-12 bold">
                        <label for="number_of_night">Number of Nights<sup></label>
                        <select class="form-control" name="number_of_night" id="number_of_night" required>
                          <?php
                                          for($i=1;$i<=11;$i++){
                                            echo '<option value="'.$i.'">'.$i.'</option>';
                                          }
                                          ?>
                        </select>

                      </div>
                      <!-- <div class="form-group col-sm-12 bold long-box">
                        <label for="accommodation">Accommodation<sup></label>
                        <select class="form-control" name="accommodation" id="accommodation" required>
                          <option value="0">All Room Types</option>
                        </select>
                      </div> -->
                      <div class="form-group col-sm-12 bold">
                        <label for="adults_per_room">Adults per Room<sup></label>
                        <select class="form-control" name="adults_per_room" id="adults_per_room" required>
                          <?php
                                          for($i=2;$i<=24;$i++){
                                            echo '<option value="'.$i.'">'.$i.'</option>';
                                          }
                                          ?>
                        </select>
                      </div>
                      <div class="form-group col-sm-12">
                        <label for="promo_code">Promo Code<sup></label>
                        <input class="form-control" name="promo_code" type="text" id="promo_code" autocomplete="off"
                          required>
                      </div>
                      <div class="form-group col-sm-12">
                        <label for="agent_iata_number">Agents IATA Number<sup></label>
                        <input class="form-control" name="agent_iata_number" type="text" id="agent_iata_number"
                          autocomplete="off" required>
                      </div>
                    </div>
                  </div>
                  <input type="button" name="next" step="step-one" class="next-step action-button" value="Next Step" />
                </fieldset>
                <fieldset id="step-two">
                  <div class="form-card">
                    <h2 class="fs-title">Upgrade Your Stay with these Add Ons</h2>
                    <span class="card-sub-head">Choose the quantity of each item for each day of your stay.
                    </span>
                    <div class="row available_addons load">

                    </div>
                  </div>
                  <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                  <input type="button" name="next" step="step-two" class="next-step action-button" value="Next Step" />
                </fieldset>
                <fieldset id="step-three">
                  <div class="form-card">
                    <h2 class="fs-title">Check Activities Availability</h2>

                    <div class="row available_activities load" activity_check_in_date = "" activity_number_of_night = "">

                    </div>
                  </div>
                  <input type="button" name="previous" step="step-three" class="previous action-button-previous" value="Previous" />
                  <input type="button" name="next" step="step-three" class="next-step action-button"
                    value="Next Step" />
                </fieldset>
                <fieldset id="step-four">
                  <div class="form-card">
                    <h2 class="fs-title">Guest Details</h2>

                    <span class="card-sub-head">Please enter Guest information then click 'Continue'. Required fields
                      look
                      like this
                    </span>
                    <form class="user-form" id="user_detail" method="post">
                      <div class="form-row user-row">
                      <div class="req">
                      <span><label for="first_name">First name</label></span>
                      <div class="input-wrap"><input type="text" name="first_name" class="form-control" id="first_name" required></div>
                      </div>

                      <div class="req">
                      <span><label for="last_name">Last name</label></span>
                      <div class="input-wrap"><input type="text" name="last_name" class="form-control" id="last_name" required></div>
                      </div>

                      <div class="req">
                      <span><label for="address_one">Address</label></span>
                      <div class="input-wrap"><input type="text" name="address_one" id="address_one" class="form-control" required></div>
                      </div>

                      <div>
                      <span><label for="address_two">Address</label></span>
                      <div class="input-wrap"><input type="text" name="address_two" id="address_two" class="form-control"></div>
                      </div>

                      <div class="thre-col req">
                      <span><label for="city">City</label></span>
                      <div class="input-wrap"><input type="text" name="city" id="city" class="form-control" required></div>
                      </div>

                      <div class="thre-col cont">
                      <span><label for="country">Country</label></span>
                       <select name="country" id="country" class="form-control booking-country" required>
                         <option value="">Select Country</option>
                         <?php
                         $table_name_178 = $wpdb->prefix.'countries';
                         $sql_query = "SELECT * FROM {$table_name_178}";
                         $countries = $wpdb->get_results($sql_query);
                         if(!empty($countries)){
                           foreach($countries as $country){
                               echo '<option value="'.$country->name.'" country-id="'.$country->id.'">'.$country->name.'</option>';
                           }
                         }
                        ?>
                       </select>
                      </div>


                      <div class="thre-col req">
                      <span><label for="zip_code">Zip/Postal Code</label></span>
                      <div class="input-wrap"><input type="text" name="zip_code" class="form-control" id="zip_code" required></div>
                      </div>

                      <div class="ful-col thre-col req cont">
                      <span><label for="state">State/Province</label></span>
                      <div class="input-wrap">
                        <select name="state" id="state" class="form-control available_states" required>
                          <option value="">Select Country First</option>
                        </select>
                      </div>
                      </div>

                      <div class="req">
                      <span><label for="phone">Phone Number</label></span>
                      <div class="input-wrap"><input type="text" name="phone" id="phone" required></div>
                      </div>

                      <div>
                      <span><label for="mobile">Mobile Phone</label></span>
                      <div class="input-wrap"><input type="text" name="mobile" id="mobile"></div>
                      </div>

                      <div class="req">
                      <span><label for="email">E-mail Address</label></span>
                      <div class="input-wrap"><input type="email" name="email" id="email" class="form-control" required></div>
                      </div>

                      </div>
                      </form>

                  </div>
                  <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                  <input type="button" name="next" step="step-four" class="next-step action-button" value="Next Step" />
                </fieldset>
                <fieldset id="step-five">
                <div class="form-card">
                    <h2 class="fs-title">Booking Preview</h2>

                    <!-- <span class="card-sub-head">Please enter Guest information then click 'Continue'. Required fields
                      look
                      like this
                    </span> -->
                    <div class="available_preview load" id="step-five-contain">

                    </div>
                  </div>
                  <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                  <input type="button" name="next" step="step-five" id="step-five-contain-btn" disabled class="next-step action-button confirm_top" value="Confirm" />

                </fieldset>
                <fieldset id="step-six">
                <div class="form-card">
                    <h2 class="fs-title">Finish</h2>

                    <span class="card-sub-head">Your reservation has been booked. reservation ID : <span id="reservation_id">123456</span>
                    </span>
                    <div class=" load" id="step-six-contain">
                         <div class="msg_succsess">
                             <div class="icon_succsess"><i class="fas fa-check"></i></div>
                             <div class="data_succsess"><h3>Your reservation has been done successfully.</h3></div>
                         </div>
                    </div>
                  </div>
                  <a href="<?php echo get_site_url(); ?>" class="back_home" > Back Home</a>
                </fieldset>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<style media="screen">


</style>
<?php get_footer();?>
