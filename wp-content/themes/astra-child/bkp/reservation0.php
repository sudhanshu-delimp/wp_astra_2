<?php /*Template Name:Reservation*/ ?>
<?php
  get_header();
  global $wpdb;
?>
<!-- MultiStep Form -->
<div class="container-fluid" id="grad1">
    <div class="row justify-content-center mt-0">
        <div class="col-11 col-sm-12 col-md-12 col-lg-12 text-center p-0 mt-3 mb-2">
            <div class="card px-0 pt-4 pb-0 mt-3 mb-3">
                <h2><strong>Sign Up Your User Account</strong></h2>
                <p>Fill all form field to go to next step</p>
                <div class="row">
                    <div class="col-md-12 mx-0">
                        <form id="msform">
                            <!-- progressbar -->
                            <ul id="progressbar">
                                <li class="active" id="account"><strong>Dates</strong></li>
                                <li id="personal"><strong>Add Ons</strong></li>
                                <li id="payment"><strong>Activities</strong></li>
                                <li id="confirm"><strong>Guest Details</strong></li>
                            </ul> <!-- fieldsets -->
                            <fieldset id="step-one">
                                <div class="form-card">
                                    <h2 class="fs-title">Dates</h2>
                                    <div class="form-group col-sm-12">
                                      <label for="arrival_date">Arrival Date<sup></label>
                                      <input class="form-control" name="arrival_date" type="text" id="arrival_date" placeholder="Select Arrival Date" autocomplete="off" required>
                                    </div>
                                    <div class="form-group col-sm-12">
                                      <label for="number_of_night">Number of Nights<sup></label>
                                      <select class="form-control" name="number_of_night" id="number_of_night" required>
                                        <?php
                                        for($i=1;$i<=11;$i++){
                                          echo '<option value="'.$i.'">'.$i.'</option>';
                                        }
                                        ?>
                                      </select>
                                    </div>
                                    <div class="form-group col-sm-12">
                                      <label for="accommodation">Accommodation<sup></label>
                                      <select class="form-control" name="accommodation" id="accommodation" required>
                                        <option value="0">All Room Types</option>
                                      </select>
                                    </div>
                                    <div class="form-group col-sm-12">
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
                                      <input class="form-control" name="promo_code" type="text" id="promo_code" placeholder="Enter Promo Code" autocomplete="off" required>
                                    </div>
                                    <div class="form-group col-sm-12">
                                      <label for="agent_iata_number">Agents IATA Number<sup></label>
                                      <input class="form-control" name="agent_iata_number" type="text" id="agent_iata_number" placeholder="Enter Agents IATA Number" autocomplete="off" required>
                                    </div>
                                </div>
                                <input type="button" name="next" step="step-one" class="next-step action-button" value="Next Step" />
                            </fieldset>
                            <fieldset id="step-two">
                                <div class="form-card">
                                    <h2 class="fs-title">Add Ons</h2>
                                    <div class="row available_addons">

                                    </div>
                                </div>
                                <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> <input type="button" name="next" step="step-two" class="next-step action-button" value="Next Step" />
                            </fieldset>
                            <fieldset id="step-three">
                                <div class="form-card">
                                    <h2 class="fs-title">Activities</h2>
                                    <div class="row available_activities">

                                    </div>
                                </div>
                                <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> <input type="button" name="make_payment" step="step-three" class="next-step action-button" value="Confirm" />
                            </fieldset>
                            <fieldset id="step-three">
                                <div class="form-card">
                                    <h2 class="fs-title">Guest Details</h2>

                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style media="screen">


</style>
<?php get_footer();?>
