<?php
global $wpdb;
class SBS_Booking_Settings{

  public function viewSettingForm(){
    ob_start();
    include_once plugin_dir_path(__FILE__).'settings/sbs-setting-form.php';
    $template = ob_get_contents();
    ob_end_clean();
    echo $template;
  }

  public function upadteSettings(){
    if(isset($_POST['booking_settings'])){
      update_option('sbs_admin_name',$_POST['sbs_admin_name']);
      update_option('sbs_admin_email',$_POST['sbs_admin_email']);
      update_option('sbs_from_name',$_POST['sbs_from_name']);
      update_option('sbs_from_email',$_POST['sbs_from_email']);
      update_option('sbs_email_api_key',$_POST['sbs_email_api_key']);
      echo '<div class="notice notice-success is-dismissible"><p>Data has been Updated Successfully..</p></div>';
    }
  }
}

$sbs_settings = new SBS_Booking_Settings();
$sbs_settings->viewSettingForm();
?>
