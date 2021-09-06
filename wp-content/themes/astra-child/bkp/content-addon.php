<?php
$add_ons_args = array('post_type'=>'addons','order'=>'ASC');
$add_ons = new WP_Query($add_ons_args);
while($add_ons->have_posts()):$add_ons->the_post();
    $add_on_image = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
    $quantity = get_post_meta( get_the_ID(), 'quantity',true);
    $quantity = explode("|",$quantity);
    $price = get_post_meta( get_the_ID(), 'price_',true);
?>
<div class="card">
<div class="card-wrap">
<h5 class="card-title"><?php the_title();?></h5>
    <div class="card-pic"><?php if(!empty($add_on_image)){ ?> <img src="<?php echo $add_on_image;?>" class="card-img-top" alt="..."> <?php } ?>
    <p class="card-text"><?php the_content();?></p></div></div>
    <div class="card-body">
    <div class="row">
      <div class="col-sm-4">
        All Days
      </div>
      <div class="form-group col-sm-4">
        <select name="<?php echo get_the_ID(); ?>" id="<?php echo get_the_ID(); ?>" addon-id = "<?php echo get_the_ID(); ?>" class="all_day form-control">
        <?php
        foreach($quantity as $qyt){
          echo '<option value="'.$qyt.'">'.$qyt.'</option>';
        }
        ?>
        </select>
      </div>
    </div>
    <?php
        foreach($args['date_range'] as $key=>$date){
            ?>
          <div class="row">
          <div class="col-sm-4">
            <?php echo getDateTime($date,'d F'); ?>
          </div>
          <div class="form-group col-sm-4">
          <select name="quantity-<?php echo get_the_ID(); ?>-<?php echo $date; ?>" id="quantity-<?php echo get_the_ID(); ?>-<?php echo $date; ?>" class="form-control quantity-<?php echo get_the_ID(); ?> selected-addons">
          <?php
          foreach($quantity as $qyt){
            echo '<option value="'.$qyt.'" date="'.$date.'"  price="'.$price.'" addon-id="'.get_the_ID().'">'.$qyt.'</option>';
          }
          ?>
          </select>
          </div>
          <div class="col-sm-4">
          @ $<?php echo $price; ?> each
          </div>
          </div>
            <?php
        }
    ?>
    </div>
</div>
<?php endwhile; wp_reset_query();  ?>
