<div class="row selected-actvities" activity-id="<?php echo $args['activity_id']; ?>">
  <input type="hidden" name="activity_id" value="<?php echo $args['activity_id']; ?>">
  <input type="hidden" name="selected_date" value="<?php echo $args['selected_date']; ?>">
  <input type="hidden" name="selected_time" value="<?php echo $args['selected_time']; ?>">
  <input type="hidden" name="selected_quantity" value="<?php echo $args['selected_quantity']; ?>">
  <input type="hidden" name="selected_price" value="<?php echo $args['selected_price']; ?>">
  <div class="col-sm-4">
    <?php echo getDateTime($args['selected_date'],'l, F d,Y'); ?>
  </div>
  <div class="col-sm-2">
    <?php echo $args['selected_time']; ?>
  </div>
  <div class="col-sm-2">
    <?php echo 'Qty: '.$args['selected_quantity']; ?>
  </div>
  <div class="col-sm-2">
    <?php echo '@ $'.$args['selected_price'].'/each'; ?>
  </div>
  <div class="col-sm-2">
    <a href="#" class="btn btn-primary remove-activity">Remove</a>
  </div>
</div>
