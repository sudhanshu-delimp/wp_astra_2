<?php
$states = $args['states'];
if(!empty($states)){
  ?>
  <option value="">Select State</option>
  <?php
  foreach($states as $key=>$state){
    ?>
    <option value="<?php echo $state->id; ?>"><?php echo $state->name; ?></option>
    <?php
  }
}
?>
