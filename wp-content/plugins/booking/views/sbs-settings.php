<?php
global $wpdb;
if(isset($_POST['booking_settings'])){
  update_option('sbs_admin_name',$_POST['sbs_admin_name']);
  update_option('sbs_admin_email',$_POST['sbs_admin_email']);
  update_option('sbs_from_name',$_POST['sbs_from_name']);
  update_option('sbs_from_email',$_POST['sbs_from_email']);
  update_option('sbs_sendinblue_api_key',$_POST['sbs_sendinblue_api_key']);
  echo '<div class="notice notice-success is-dismissible"><p>Data has been Updated Successfully..</p></div>';
}
?>
<form class="" action="<?php echo $_SERVER['PHP_SELF'].'?page=sbs-booking-setting'; ?>" method="post">
  <div class="">
    <label for="">Admin Name</label>
    <input type="text" name="sbs_admin_name" value="<?php echo get_option('sbs_admin_name');?>">
  </div>
  <div class="">
    <label for="">Admin Email</label>
    <input type="text" name="sbs_admin_email" value="<?php echo get_option('sbs_admin_email');?>">
  </div>
  <div class="">
    <label for="">From Name</label>
    <input type="text" name="sbs_from_name" value="<?php echo get_option('sbs_from_name');?>">
  </div>
  <div class="">
    <label for="">From Email</label>
    <input type="text" name="sbs_from_email" value="<?php echo get_option('sbs_from_email');?>">
  </div>
  <div class="">
    <label for="">Sendinblue api key</label>
    <input type="text" name="sbs_sendinblue_api_key" value="<?php echo get_option('sbs_sendinblue_api_key');?>">
  </div>
  <div class="">
    <input type="submit" name="booking_settings" value="Save">
  </div>
</form>
