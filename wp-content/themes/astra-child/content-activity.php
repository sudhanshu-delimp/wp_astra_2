<?php
$add_ons_args = array('post_type'=>'activities','order'=>'ASC');
$add_ons = new WP_Query($add_ons_args);
while($add_ons->have_posts()):$add_ons->the_post();
    $add_on_image = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
    $quantity = get_post_meta( get_the_ID(), 'quantity',true);
    $quantity = explode("|",$quantity);
    $price = get_post_meta( get_the_ID(), 'price',true);
    $time = get_post_meta( get_the_ID(), 'time',true);
    $time = explode("|",$time);
?>
<div class="card">
    <img src="<?php echo $add_on_image;?>" class="card-img-top" alt="...">
    <div class="card-body">
    <h5 class="card-title"><?php the_title();?></h5>
    <p class="card-text"><?php the_content();?></p>
          <div class="row selected-actvities" id="activity-box-<?php echo get_the_ID(); ?>">

          </div>
          <div class="row">
          <div class="col-sm-3">
              <select name="date-<?php echo get_the_ID(); ?>" id="date-<?php echo get_the_ID(); ?>" class="form-control date-<?php echo get_the_ID(); ?> choose-date">
              <option value=""  activity-id="<?php echo get_the_ID(); ?>">Select Date</option>
              <?php
              foreach($args['date_range'] as $key=>$date){
                echo '<option value="'.$date.'"  price="'.$price.'" activity-id="'.get_the_ID().'">'.getDateTime($date,'l, F d,Y').'</option>';
              }
              ?>
              </select>
          </div>
          <div class="form-group col-sm-3">
          <select name="time-<?php echo get_the_ID(); ?>-<?php echo $date; ?>" id="time-<?php echo get_the_ID(); ?>" class="form-control time-<?php echo get_the_ID(); ?> disabled all-time">
          <option value="" activity-id="<?php echo get_the_ID(); ?>">Select Time</option>
          <?php
          foreach($time as $tym){
            echo '<option value="'.$tym.'" activity-id="'.get_the_ID().'">'.$tym.'</option>';
          }
          ?>
          </select>
          </div>
          <div class="form-group col-sm-3 invisible div-<?php echo get_the_ID(); ?>">
          <select name="quantity-<?php echo get_the_ID(); ?>" id="quantity-<?php echo get_the_ID(); ?>" class="form-control quantity-<?php echo get_the_ID(); ?> all-quantity">
          <option value="" activity-id="<?php echo get_the_ID(); ?>">Select Quantity</option>
          <?php
          foreach($quantity as $qyt){
            echo '<option value="'.$qyt.'" price="'.$price.'" activity-id="'.get_the_ID().'">'.$qyt.'</option>';
          }
          ?>
          </select>
          </div>
          <div class="form-group col-sm-3 invisible div-<?php echo get_the_ID(); ?>">
          @ $<?php echo $price; ?> each
          </div>
          </div>
    </div>
</div>
<?php endwhile; wp_reset_query();  ?>
