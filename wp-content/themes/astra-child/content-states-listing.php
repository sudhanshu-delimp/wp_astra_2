<?php
$states = $args['states'];
if(!empty($states)){
  ?>
  <option value="">Select State</option>
  <?php
  foreach($states as $key=>$state){
    ?>
    <option value="<?php echo $state->name; ?>"><?php echo $state->name; ?></option>
    <?php
  }
}
?>
