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
                <h2><strong>Reservations</strong></h2>
                
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
                                <div class="form-card reserve-user">
                                    <h2 class="fs-title">Check Room Availability</h2>
                                    <span><b>When making room reservations, we have a two night minimum for Garden Bungalows and a four-night minimum for Waterfront Bungalows.</b><br>
                                      Please select below and then click 'Check Now'.</span>
                                    <div class="form-contain">
                                      <div class="form-group col-sm-12 bold">
                                      <label for="arrival_date">Arrival Date<sup></label>
                                      <input class="form-control" name="arrival_date" type="text" id="arrival_date" placeholder="Select Arrival Date" autocomplete="off" required>
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
                                    <div class="form-group col-sm-12 bold">
                                      <label for="accommodation">Accommodation<sup></label>
                                      <select class="form-control" name="accommodation" id="accommodation" required>
                                        <option value="0">All Room Types</option>
                                      </select>
                                    </div>
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
                                      <input class="form-control" name="promo_code" type="text" id="promo_code"  autocomplete="off" required>
                                    </div>
                                    <div class="form-group col-sm-12">
                                      <label for="agent_iata_number">Agents IATA Number<sup></label>
                                      <input class="form-control" name="agent_iata_number" type="text" id="agent_iata_number"  autocomplete="off" required>
                                    </div>
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
                                <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> <input type="button" name="next" step="step-three" class="next-step action-button" value="Next Step" />
                            </fieldset>
                            <fieldset id="step-four">
                              <div class="form-card">
                              <h2 class="fs-title">Guest Details</h2>
                              <p>Please enter Guest information then click 'Continue'. Required fields look like this</p>
                              <form class="needs-validation user-form" novalidate>
                              <div class="form-row user-row">
                              <div>
                              <label for="validationCustom01">First name</label>
                              <input type="text" class="form-control" id="validationCustom01" required>
                              </div>

                              <div>
                              <label for="validationCustom02">Last name</label>
                              <input type="text" class="form-control" id="validationCustom02" required>
                              </div>

                              <div>
                              <label for="validationCustom03">Address</label>
                              <input type="text" id="validationCustom3" class="form-control" required>
                              </div>

                              <div>
                              <label for="validationCustom04">Address</label>
                              <input type="text" id="validationCustom4" class="form-control">
                              </div>

                              <div class="thre-col">
                              <label for="validationCustom05">City</label>
                              <input type="text" class="form-control" id="validationCustom05" required>
                              </div>

                              <div class="ful-col thre-col">
                              <label for="validationCustom06">State/Province</label>
                              <select name="state" id="state">
                              <option value="AB  ">Alberta </option>
                              <option value="AK  ">Alaska </option>
                              <option value="AL  ">Alabama </option>
                              <option value="AR  ">Arkansas </option>
                              <option value="AZ  ">Arizona </option>
                              <option value="BC  ">British Columbia </option>
                              <option value="CA  ">California </option>
                              <option value="CO  ">Colorado </option>
                              <option value="CT  ">Connecticut </option>
                              <option value="DC  ">District of Columbia </option>
                              <option value="DE  ">Delaware </option>
                              <option value="FL  ">Florida </option>
                              <option value="GA  ">Georgia </option>
                              <option value="GU  ">Guam </option>
                              <option value="HI  ">Hawaii </option>
                              <option value="IA  ">Iowa </option>
                              <option value="ID  ">Idaho </option>
                              <option value="IL  ">Illinois </option>
                              <option value="IN  ">Indiana </option>
                              <option value="KS  ">Kansas </option>
                              <option value="KY  ">Kentucky </option>
                              <option value="LA  ">Louisiana </option>
                              <option value="MA  ">Massachusetts </option>
                              <option value="MB  ">Manitoba </option>
                              <option value="MD  ">Maryland </option>
                              <option value="ME  ">Maine </option>
                              <option value="MI  ">Michigan </option>
                              <option value="MN  ">Minnesota </option>
                              <option value="MO  ">Missouri </option>
                              <option value="MS  ">Mississippi </option>
                              <option value="MT  ">Montana </option>
                              <option value="NA  ">International </option>
                              <option value="NB  ">New Brunswick </option>
                              <option value="NC  ">North Carolina </option>
                              <option value="ND  ">North Dakota </option>
                              <option value="NE  ">Nebraska </option>
                              <option value="NF  ">Newfoundland </option>
                              <option value="NH  ">New Hampshire </option>
                              <option value="NJ  ">New Jersey </option>
                              <option value="NM  ">New Mexico </option>
                              <option value="NS  ">Nova Scotia </option>
                              </select>
                              </div>

                              <div class="thre-col">
                              <label for="validationCustom07">Zip/Postal Code</label>
                              <input type="text" class="form-control" id="validationCustom07" required>
                              </div>

                              <div>
                              <label for="validationCustom08">Country</label>
                              <input type="text" class="form-control" id="validationCustom08"  required>
                              </div>

                              <div>
                              <label for="validationCustom09">Phone Number</label>
                              <input type="text" name="mobileNumber" id="validationCustom09" required>
                              </div>

                              <div>
                              <label for="validationCustom10">Mobile Phone</label>
                              <input type="text" name="mobileNumber" id="validationCustom10">
                              </div>

                              <div>
                              <label for="validationCustom11">E-mail Address</label>
                              <input type="email" id="validationCustom11" class="form-control" required>
                              </div>
                              <div>
                              <button class="btn btn-primary" type="submit">Submit form</button>
                              </div>
                              </div>
                              </form>

                              </div>
                                <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> <input type="button" name="make_payment" step="step-four" class="next-step action-button" value="Confirm" />
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
