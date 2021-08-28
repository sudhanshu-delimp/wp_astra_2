<?php
/*Template Name:Reservation*/
get_header();
global $wpdb;

$data = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix."check_room_availability where status='1' ORDER BY id DESC");
$parent_id = $data->id;
$addon = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."addon where parent_id='".$parent_id."'");
$sum= 0;

$origin = new DateTime($data->check_in_date);
$nextdate=  date('Y-m-d', strtotime($data->check_in_date. '+'.$data->number_of_night.' days'));

$target = new DateTime($nextdate);
$interval = date_diff($origin, $target);
$count= $interval->format('%a');

function getDatesFromRange($start, $end, $format = 'l F j Y') {
  // Declare an empty array
  $array = array();
  // Variable that store the date interval
  // of period 1 day
  $interval = new DateInterval('P1D');
  $realEnd = new DateTime($end);
  $realEnd->add($interval);
  $period = new DatePeriod(new DateTime($start), $interval, $realEnd);
  // Use loop to store date into array
  foreach($period as $date) {
  $array[] = $date->format($format);
  }
  // Return the array elements
  return $array;
}

$Date = getDatesFromRange($data->check_in_date, $nextdate);

for($i=1;$i<$count;$i++){
$num=$i;
}
?>
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-11 col-sm-9 col-md-7 col-lg-6 col-xl-6 text-center p-0 mt-3 mb-2">
<div class="card px-0 pt-4 pb-0 mt-3 mb-3">
    <h2 id="heading">Reservations</h2>
    <!-- <p>Fill all form field to go to next step</p> -->
    <form id="msform" role="form" action="" method="post" class="f1 m_bt0" name="form-add-new-booking-hotel" enctype="multipart/form-data" >
        <input type="hidden" name="patient_id" id="patient_id" value="<?php echo $parent_id;?>">

        <!-- progressbar -->
        <ul id="progressbar">
            <li class="active" id="account"><strong>DATES</strong></li>
            <li id="payment"><strong>ADD ONS</strong></li>
            <li id="payment2"><strong>ACTIVITY</strong></li>
            <li id="confirm"><strong>GUEST DETAILS</strong></li>
            <li id="review"><strong>REVIEW</strong></li>
            <li id="confirmation"><strong>CONFIRMATION</strong></li>
        </ul>
        <div class="progress">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
        </div> <br> <!-- fieldsets -->

        <fieldset>

            <div class="form-card">
                <div class="row">
                    <div class="col-10">
                        <h2 class="fs-title">Check Room Availability</h2>
                        <p>When making room reservations, we have a two night minimum for Garden Bungalows and a four-night minimum for Waterfront Bungalows.
                            Please select below and then click 'Check Now'.</p>
                    </div>
                    <div class="col-2">
                        <h2 class="steps">1 - 6</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                <label class="fieldlabels">Arrival Date: *</label>
                <input type="date" name="checkin" id="checkin" class="form-control" placeholder="Arrival Date" required="" />
                </div>

            </div>
                <label class="fieldlabels">Number of Nights: *</label>
                    <select class="form-control form-control-lg" name="number_of_night" id="number_of_night" required="">

                        <?php for($i=8;$i<12; $i++){ ?>
                        <option value="<?php echo $i;?>"><?php echo $i;?></option>
                        <?php } ?>
                    </select>
                    <br>
                    <br>
                    <label class="fieldlabels">Accommodation: *</label>
                <select name="buildingCodeRoomType" id="accommodation" class="form-control">
                    <option value="any|any" selected="selected">All Room Types</option>
                    <option value="b1" selected="selected">Bungalow Room Types</option>
                </select>
                    <br>
                    <br>
                    <label class="fieldlabels">Adults per Room: *</label>
                <select name="Adults_per_Room" id="adults_per_Room" class="form-control form-control-lg">
                    <?php for($i=17;$i<25; $i++){ ?>
                        <option value="<?php echo $i;?>"><?php echo $i;?></option>
                        <?php } ?>
                </select><br>
                <label class="fieldlabels">Promo Code *</label>
                <input type="text" name="Promo_Code" id="Promo_Code" placeholder="" class="form-control"/>
                <label class="fieldlabels">Agents IATA Number: *</label>
                <input type="text" name="Agents" id="agents" placeholder="" class="form-control"/>
            </div>
            <input type="hidden" name="firstStep" id="firstStep" value="1" />
                <input type="button" name="next" id="next" class="next action-button" value="Check Now" />
        </fieldset>


        <fieldset>
                <div class="form-card">
                <div class="row">
                    <div class="col-7">
                        <h2 class="fs-title">Room Availability:</h2>

                        <p>(<?php echo $room;?>) room for a <?php echo $data->number_of_night;?> night stay, arriving on , <?php echo $data->check_in_date;?> departing on <?php echo $data->check_out_date;?> to accommodate <?php echo $data->adults_per_room;?> adults per room.</p>

                        <p>Upgrade Your Stay with these Add Ons Choose the quantity of each item for each day of your stay.</p>
                    </div>
                    <div class="col-5">
                        <h2 class="steps">Step 2 - 6</h2>
                    </div>
                </div>
                <?php

                $args2 = array( 'post_type' => 'product', 'stock' => 1, 'posts_per_page' => 10,'product_category' =>'Addon','order' => 'ASC' );
                $loop2 = new WP_Query( $args2 );
                while ( $loop2->have_posts() ) : $loop2->the_post();
                global $product;
                    ?>
                <?php  $feat_image_url2 = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );?>
                <?php  $key_1_values2 = get_post_meta( get_the_ID(), 'AvgNightlyRate', true ); ?>
                <div class="well">
<div class="media">

<img class="media-object" src="<?php echo $feat_image_url2;?>" style="width: 23%;">

<div class="media-body">
<h4 class="media-heading"><?php the_title();?></h4>
<p><?php the_content();?></p>
<div>
<!--  <h4 class="media-heading">Receta 1</h4> -->
<p class="text-right"><?php the_excerpt();?></p>
<p>All Days</p>
<?php $i=1; for($i=1;$i<$count;$i++){ ?>
<input type="hidden" name="question_id[<?php echo get_the_ID();?>][answerid][title]" id="title_name_<?php echo get_the_ID();?>" value="<?php the_title();?>">
<div class="row">
<div class="col-4">

<select class="form-control form-control-lg day_date" name="question_id[<?php echo get_the_ID();?>][answerid][day_date][]" id="day_date">
<option value="">Select Day</option>
<?php foreach($Date as $key=>$value){ ?>
<option value="<?php echo $value;?>"><?php echo $value;?></option>
<?php } ?>
</select>
<span id="d_day_date" class="text-danger"></span>

</div>
<div class="col-4">
<select class="form-control form-control-lg day_night" id="day_night" name="question_id[<?php echo get_the_ID();?>][answerid][day_night][]" >
<option value="">Select Night</option>
<?php $i2=1; for($i2=1;$i2<$count;$i2++){  ?>
<option value="<?php echo $i2;?>"><?php echo $i2;?></option>
<?php } ?>

</select>
<span id="n_day_night" class="text-danger"></span>
</div>
<div class="col-4">
<select class="form-control form-control-lg day_night" id="day_price" name="question_id[<?php echo get_the_ID();?>][answerid][day_price][]" >
<option value="">Select Price</option>
<?php for($i2=1;$i2<$count;$i2++){  ?>
<option value="<?php echo $key_1_values2;?>"><?php echo $key_1_values2;?></option>
<?php } ?>

</select>
<span id="p_day_price" class="text-danger"></span>

</div>
</div>
<?php }?>
</div>
</div>
</div>
</div>
                <hr>
                <?php endwhile; wp_reset_query();  ?>
            </div>
                <input type="hidden" name="socoundNdstep" id="socoundNdstep" value="2">
                <input type="button" name="next" class="step action-button" value="Next" />
                <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
        </fieldset>
            <fieldset>
            <div class="form-card">
                <div class="row">
                    <div class="col-7">
                        <h2 class="fs-title">Check Activities Availability</h2>

                        <p>Date: <?php echo $data->check_in_date;?> <br>
                        Number Days: <?php echo $data->number_of_night;?></p>
                    </div>
                    <div class="col-5">
                        <h2 class="steps">Step 2 - 6</h2>
                    </div>
                </div>
                <?php

                $args = array( 'post_type' => 'product', 'stock' => 1, 'posts_per_page' => 10,'product_category' =>'Activities','order' => 'ASC' );
                        $loop = new WP_Query( $args );
                        while ( $loop->have_posts() ) : $loop->the_post();
                    global $product;

                    ?>

                <?php  $feat_image_url = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );?>

                <?php
                    $price = get_post_meta( get_the_ID(), 'price', true );
                    $timeSlot = get_post_meta(get_the_ID(),'select_time', true );
                    $timeSlots = explode('|', $timeSlot);
                    $timeCount= count($timeSlots);


                    $qtySlot  = get_post_meta(get_the_ID(),'qty', true );
                    $qtySlots = explode('|', $qtySlot);
                    $qtyCount = count($qtySlots);
                    ?>

                <div class="well">
                    <div class="media">

                            <img class="media-object" src="<?php echo $feat_image_url;?>" style="width: 23%;">

                        <div class="media-body">
                            <h4 class="media-heading"><?php the_title();?></h4>
                            <p><?php the_content();?></p>
                            <div>
                                <p class="text-right"><?php the_excerpt();?></p>
                                <div class="input_fields_wrap_<?php echo get_the_ID();?>">

                                <div class="row">
                                    <div class="col-3">
                                    <input type="hidden" name="3rd_step" id="3rd_step" value="3rd_step">
                                    <input type="hidden" name="activity[<?php echo get_the_ID();?>][answerid][title]" id="title_name" value="<?php the_title();?>">
                                        <select class="form-control form-control-lg" name="activity[<?php echo get_the_ID();?>][answerid][Day][]">
                                        <option value="">Day</option>
                                            <?php for($i3=1;$i3<$count;$i3++){  ?>
                                                <option value="<?php echo $Date[$i3];?>"><?php echo $Date[$i3];?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-3">
                                    <select class="form-control form-control-lg" name="activity[<?php echo get_the_ID();?>][answerid][timeslot][]" id="timeslot">
                                        <option value="">Time</option>
                                            <?php for($i4=1;$i4<$timeCount;$i4++){  ?>
                                            <option value="<?php echo $timeSlots[$i4];?>"><?php echo $timeSlots[$i4];?></option>
                                        <?php } ?>

                                        </select>
                                    </div>
                                    <div class="col-2">
                                    <select class="form-control form-control-lg" name="activity[<?php echo get_the_ID();?>][answerid][Qty][]" id="qtyslot">
                                        <option value="">Qty</option>
                                            <?php for($i5=1;$i5<$qtyCount;$i5++){  ?>
                                            <option value="<?php echo $qtySlots[$i5];?>"><?php echo $qtySlots[$i5];?></option>
                                        <?php } ?>
                                    </select>
                                    </div>
                                <div class="col-2">
                                    <select class="form-control form-control-lg" name="activity[<?php echo get_the_ID();?>][answerid][price]" id="price">
                                        <option value="">price</option>
                                            <option value="<?php echo $price;?>"><?php echo $price;?></option>

                                    </select>

                                </div>
                                <div class="col-2">
                                    <input type="hidden"  id="total_count" name="total_count[]" value="1">
                                <button style="background-color:green;" class="add_field_button_<?php echo get_the_ID();?> btn btn-info active">Add More</button>
                                </div>
                                </div>

                            </div>


                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <script>
                jQuery(document).ready(function() {
                today = new Date().toISOString().split('T')[0];
                var max_fields = 15; //maximum input boxes allowed
                var wrapper = $(".input_fields_wrap_<?php echo get_the_ID();?>"); //Fields wrapper
                var add_button = $(".add_field_button_<?php echo get_the_ID();?>"); //Add button ID
                var x = 1; //initlal text box count
                $(add_button).click(function(e){ //on add input button click
                e.preventDefault();
                if(x < max_fields){ //max input box allowed
                    $('#total_count').val(x);
                x++; //text box increment

                $(wrapper).append('<div><div style="background: #fafafa; padding: 1.5rem 0rem 0rem 0rem;"> <div class="row"><div class="col-3"><select class="form-control form-control-lg" name="activity[<?php echo get_the_ID();?>][answerid][Day][]"><option value="">Day</option><?php for($i3=1;$i3<$count;$i3++){  ?><option value="<?php echo $Date[$i3];?>"><?php echo $Date[$i3];?></option><?php } ?></select></div><div class="col-3"><select class="form-control form-control-lg" name="activity[<?php echo get_the_ID();?>][answerid][timeslot][]" id="timeslot"><option value="">Select Time</option><?php for($i4=0;$i4<$timeCount;$i4++){  ?><option value="<?php echo $timeSlots[$i4];?>"><?php echo $timeSlots[$i4];?></option><?php } ?></select></div><div class="col-2"><select class="form-control form-control-lg" name="activity[<?php echo get_the_ID();?>][answerid][Qty][]" id="qtyslot"><option value="">Qty</option><?php for($i5=0;$i5<$qtyCount;$i5++){  ?><option value="<?php echo $qtySlots[$i5];?>"><?php echo $qtySlots[$i5];?></option><?php } ?></select></div><div class="col-2"><select class="form-control form-control-lg" name="activity[<?php echo get_the_ID();?>][answerid][price]" id="price"><option value="">price</option><option value="<?php echo $price;?>"><?php echo $price;?></option></select></div></div><a class="remove_field_<?php echo get_the_ID();?>" style="cursor:pointer; text-align: right; color: red; position: absolute; right: 57px;">Delete</a></div></div>');
                $('#total_count').val(x);

                }
                });
                $(wrapper).on("click",".remove_field_<?php echo get_the_ID();?>", function(e){ //user click on remove text
                e.preventDefault(); $(this).parent('div').remove(); x--;
                })
                });
                </script>
                <?php endwhile; wp_reset_query();  ?>
            </div>
            <input type="button" name="next" class="step3 action-button" value="Next" />
                <input type="button" name="previous" class="previous action-button-previous" value="Previous" />

        </fieldset>
        <fieldset>
            <div class="form-card">
                <div class="row">
                    <div class="col-7">
                        <h2 class="fs-title">Guest Information</h2>
                        <p>Please enter Guest information then click 'Continue'. Required fields look like this</p>
                    </div>
                    <div class="col-5">
                        <h2 class="steps">Step 3 - 6</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                    <label class="fieldlabels">First Name: *</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name"  />
                    <span id="f_first_name" class="text-danger"></span>
                    </div>
                    <div class="col-6">
                        <label class="fieldlabels">Last Name: *</label>
                        <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name"  />
                        <span id="l_last_name" class="text-danger"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                    <label class="fieldlabels">Mobile Phone:</label>
                    <input type="text" name="Mobile_Phone" class="form-control" id="Mobile_Phone">
                    <span id="Mobile_Phone" class="text-danger"></span>
                    </div>
                    <div class="col-6">
                        <label class="fieldlabels">E-mail_Address*</label>
                        <input type="text" name="email_address" class="form-control" id="email_address">
                        <span id="email_address" class="text-danger"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                    <label class="fieldlabels">Address:</label>
                    <input type="text" name="address" class="form-control" id="address">
                    </div>
                    <div class="col-6">
                        <label class="fieldlabels">City: *</label>
                        <input type="text" name="city" class="form-control" id="city">
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                    <label class="fieldlabels">State/Province:</label>
                    <input type="text" name="State_Province" class="form-control" id="State_Province">
                    </div>
                    <div class="col-6">
                        <label class="fieldlabels">Zip/Postal Code: *</label>
                        <input type="text" name="Zip_Postal_Code" class="form-control" id="Zip_Postal_Code">
                    </div>
                </div>


            </div>
            <input type="button" name="next" class="step4 action-button" value="Submit" />
            <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
        </fieldset>
        <fieldset>
            <div class="form-card">
                <div class="row">
                    <div class="col-7">
                        <h2 class="fs-title">Image Upload:</h2>
                    </div>
                    <div class="col-5">
                        <h2 class="steps">Step 3 - 4</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                    <h4>Addon</h4>
                    <?php echo $parent_id = $data->id;
                     $addon=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."addon where parent_id='".$parent_id."'");
                     ?>
                    <?php $sum= 0; foreach($addon as $values){
                            $array= explode('|',$values->day_night);
                            $day_price= explode('|',$values->day_price);
                            $day= explode('$',$day_price[0]);
                            //print_r($day[1]);
                            foreach($array as $key1=>$num){
                                @$sum+= $num;
                            }?>

                            <h4 class="media-heading"><?php echo $values->title;?></h4>
                            <p>Date:<?php echo  $values->day_date;?></p>
                            <p>Night:<?php echo $values->day_night;?></p>
                            <p>Price:<?php echo $totalAddon=$sum*$day[1];;?></p>
                        <?php  } ?>
                    </div>
                    <div class="col-6">
                        <h4>Activity</h4>
                        <?php $activitiy=$wpdb->get_results("SELECT * FROM ".$wpdb->prefix."activitiy where parent_id='".$parent_id."'");?>
                        <?php $qyt= 0;
                        print_r($activitiy);
                        foreach($activitiy as $valuesActivity){
                            $QtyActivity= explode('|',$valuesActivity->Qty);
                            $priceactivity= explode('$',$valuesActivity->price);
                                    foreach($QtyActivity as $key2=>$num2){
                                    @$qyt+=$num2;
                                    }?>

                            <h4 class="media-heading"><?php echo $valuesActivity->title;?></h4>
                            <p>Date:<?php echo  $valuesActivity->day_date;?></p>
                            <p>Time Slot:<?php echo $valuesActivity->timeslot;?></p>
                            <p>Qty:<?php echo $valuesActivity->Qty;?></p>
                            <p>Price:<?php echo $totalActivity=$qyt*$priceactivity[1];?></p>
                            <?php //echo $qyt;//$totalActivity=$qyt*$priceactivity[1];
                                }
                            ?>
                    </div>
                </div>



            </div>
            <input type="button" name="next" class="step5 action-button" value="Submit" />
            <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
        </fieldset>
        <fieldset>
            <div class="form-card">
                <div class="row">
                    <div class="col-7">
                        <h2 class="fs-title">Finish:</h2>
                    </div>
                    <div class="col-5">
                        <h2 class="steps">Step 4 - 4</h2>
                    </div>
                </div> <br><br>
                <h2 class="purple-text text-center"><strong>SUCCESS !</strong></h2> <br>
                <div class="row justify-content-center">
                    <div class="col-3"> <img src="https://i.imgur.com/GwStPmg.png" class="fit-image"> </div>
                </div> <br><br>
                <div class="row justify-content-center">
                    <div class="col-7 text-center">
                        <h5 class="purple-text text-center">You Have Successfully Signed Up</h5>
                        <input type="button" name="previous" class="previous action-button-previous" value="Previous" />
                    </div>
                </div>
            </div>
        </fieldset>
    </form>
</div>
</div>
</div>
</div>
<?php get_footer();?>
