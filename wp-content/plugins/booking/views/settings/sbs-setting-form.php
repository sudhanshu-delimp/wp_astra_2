<h1>Settings</h1>
<?php
$this->upadteSettings();
?>
<form class="book-form" action="<?php echo $_SERVER['PHP_SELF'].'?page=sbs-booking-setting'; ?>" method="post">
  <div class="">
    <label for="">Admin Name</label>
    <input type="text" name="sbs_admin_name" value="<?php echo get_option('sbs_admin_name');?>">
  </div>
  <div class="">
    <label for="">Admin Email</label>
    <input type="email" name="sbs_admin_email" value="<?php echo get_option('sbs_admin_email');?>">
  </div>
  <div class="">
    <label for="">From Name</label>
    <input type="text" name="sbs_from_name" value="<?php echo get_option('sbs_from_name');?>">
  </div>
  <div class="">
    <label for="">From Email</label>
    <input type="email" name="sbs_from_email" value="<?php echo get_option('sbs_from_email');?>">
  </div>
  <div class="">
    <label for="">Email api key</label>
    <input type="text" name="sbs_email_api_key" value="<?php echo get_option('sbs_email_api_key');?>">
  </div>
  <div class="">
    <label for="">Twilio Account SID</label>
    <input type="text" name="sbs_twillio_account_sid" value="<?php echo get_option('sbs_twillio_account_sid');?>">
  </div>
  <div class="">
    <label for="">Twilio Auth Token</label>
    <input type="text" name="sbs_twillio_auth_token" value="<?php echo get_option('sbs_twillio_auth_token');?>">
  </div>
  <div class="">
    <label for="">Twilio Phone Number</label>
    <input type="text" name="sbs_twillio_phone_number" value="<?php echo get_option('sbs_twillio_phone_number');?>">
  </div>
  <div class="sub-btn">
    <input type="submit" name="booking_settings" value="Save">
  </div>
</form>
