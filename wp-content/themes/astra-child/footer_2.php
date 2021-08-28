<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<?php astra_content_bottom(); ?>
	</div> <!-- ast-container -->
	</div><!-- #content -->
<?php
	astra_content_after();

	astra_footer_before();

	astra_footer();

	astra_footer_after();
?>
	</div><!-- #page -->
<?php
	astra_body_bottom();
	wp_footer();
?>
	</body>
</html>
<sript src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.0.1/TweenMax.min.js"></sript>
<sript src="https://cdnjs.cloudflare.com/ajax/libs/gsap/2.0.1/utils/Draggable.min.js?r=clKd"></sript>
<script>https://s3-us-west-2.amazonaws.com/s.cdpn.io/16327/ThrowPropsPlugin.min.js</script>
</body>
</html>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.payment/3.0.0/jquery.payment.min.js"></script>
      <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/jquery-3.2.1.min.js"></script>
      <script src="<?php echo get_stylesheet_directory_uri(); ?>/assets/js/owl.carousel.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){

var current_fs, next_fs, previous_fs; //fieldsets
var opacity;
var current = 1;
var steps = $("fieldset").length;

setProgressBar(current);

$(".next").click(function(){
    v1 = document.getElementById("checkin");
    v2 = document.getElementById("checkout");
    var checkin = $("#checkin").val();
    var checkout = $("#checkout").val();
    var number_of_night  = $("#number_of_night").val();
    var accommodation  = $("#accommodation").val();
    var adults_per_Room  = $("#adults_per_Room").val();
    var Promo_Code  = $("#Promo_Code").val();
    var agents  = $("#agents").val();
    var firstStep  = $("#firstStep").val();


      if(v1.value == "" ) {
        v1.style.borderColor = "red";
        $("#checkin").focus();
        flag = false;
      }else{

    /*foem submit*/
        if(firstStep==1){
          $.ajax({
                type: 'post',
                url: "../wp-content/themes/rhye-child/formsubmit.php",
                dataType: 'json',
                data: 'checkin='+checkin+'&number_of_night='+number_of_night+'&accommodation='+accommodation+'&adults_per_Room='+adults_per_Room+'&Promo_Code='+Promo_Code+'&agents='+agents,

                success: function(data)
                {
                    alert(data);
                }
            });
        }

    /*end*/


      current_fs = $(this).parent();
      next_fs = $(this).parent().next();

      //Add Class Active
      $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

      //show the next fieldset
      next_fs.show();
      //hide the current fieldset with style
      current_fs.animate({opacity: 0}, {
      step: function(now) {
      // for making fielset appear animation
      opacity = 1 - now;

      current_fs.css({
      'display': 'none',
      'position': 'relative'
      });
      next_fs.css({'opacity': opacity});
      },
      duration: 500
      });
      setProgressBar(++current);

      flag = true;

      }
});

$(".step").click(function(){


  // var day_date=$(".day_date").val();
  // var day_night=$("#day_night").val();
  // var day_price=$("#day_price").val();

  // if(day_date==""){
  // $("#d_day_date").html('This filed is required');
  //     $("#d_day_date").focus();
  //         return false;
  // } else {
  //     $("#d_day_date").html('');
  // }

  // if(day_night==""){
  // $("#n_day_night").html('This filed is required');
  //     $("#n_dday_night").focus();
  //         return false;
  // } else {
  //     $("#n_day_night").html('');
  // }

  // if(day_price==""){
  // $("#p_day_price").html('This filed is required');
  //     $("#p_day_price").focus();
  //         return false;
  // } else {
  //     $("#p_day_price").html('');
  // }

        /*form submit*/

                var formData = new FormData($('form[name="form-add-new-booking-hotel"]')[0]);


                    $.ajax({
                        type: 'POST',
                        url: "../wp-content/themes/rhye-child/booking_hotel.php",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',

                        success: function(data)
                        {
                            alert(data);
                        }
                    });
        /*end*/

      current_fs = $(this).parent();
      next_fs = $(this).parent().next();

      //Add Class Active
      $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

      //show the next fieldset
      next_fs.show();
      //hide the current fieldset with style
      current_fs.animate({opacity: 0}, {
      step: function(now) {
      // for making fielset appear animation
      opacity = 1 - now;

      current_fs.css({
      'display': 'none',
      'position': 'relative'
      });
      next_fs.css({'opacity': opacity});
      },
      duration: 500
      });
      setProgressBar(++current);

      flag = true;


});

$(".step3").click(function(){

        /*form submit*/

                var formData = new FormData($('form[name="form-add-new-booking-hotel"]')[0]);


                    $.ajax({
                        type: 'POST',
                        url: "../wp-content/themes/rhye-child/actvity_booking.php",
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        dataType: 'json',

                        success: function(data)
                        {
                            alert(data);
                        }
                    });
        /*end*/

      current_fs = $(this).parent();
      next_fs = $(this).parent().next();

      //Add Class Active
      $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

      //show the next fieldset
      next_fs.show();
      //hide the current fieldset with style
      current_fs.animate({opacity: 0}, {
      step: function(now) {
      // for making fielset appear animation
      opacity = 1 - now;

      current_fs.css({
      'display': 'none',
      'position': 'relative'
      });
      next_fs.css({'opacity': opacity});
      },
      duration: 500
      });
      setProgressBar(++current);

      flag = true;


});

$(".step4").click(function(){

  first_name = document.getElementById("first_name");
  last_name = document.getElementById("last_name");

  var first_name=$("#first_name").val();
  var last_name=$("#last_name").val();
  var Mobile_Phone=$("#Mobile_Phone").val();
  var email_address=$("#email_address").val();


  if(first_name==""){
    //first_name.style.borderColor = "red";
    $("#f_first_name").html('This filed is required');
      $("#f_first_name").focus();
          return false;
  } else {
      $("#f_first_name").html('');
  }

  if(last_name==""){
    //first_name.style.borderColor = "red";
    $("#l_last_name").html('This filed is required');
      $("#l_last_name").focus();
          return false;
  } else {
      $("#l_last_name").html('');
  }



  var formData = new FormData($('form[name="form-add-new-booking-hotel"]')[0]);
  $.ajax({
      type: 'POST',
      url: "../wp-content/themes/rhye-child/guest_information.php",
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      dataType: 'json',

      success: function(data)
      {
          alert(data);
      }
  });



      current_fs = $(this).parent();
      next_fs = $(this).parent().next();

      //Add Class Active
      $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

      //show the next fieldset
      next_fs.show();
      //hide the current fieldset with style
      current_fs.animate({opacity: 0}, {
      step: function(now) {
      // for making fielset appear animation
      opacity = 1 - now;

      current_fs.css({
      'display': 'none',
      'position': 'relative'
      });

      next_fs.css({'opacity': opacity});
      },
      duration: 500
      });
      setProgressBar(++current);

      flag = true;


});


$(".previous").click(function(){

current_fs = $(this).parent();
previous_fs = $(this).parent().prev();

//Remove class active
$("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

//show the previous fieldset
previous_fs.show();

//hide the current fieldset with style
current_fs.animate({opacity: 0}, {
step: function(now) {
// for making fielset appear animation
opacity = 1 - now;

current_fs.css({
'display': 'none',
'position': 'relative'
});
previous_fs.css({'opacity': opacity});
},
duration: 500
});
setProgressBar(--current);
});

function setProgressBar(curStep){
var percent = parseFloat(100 / steps) * curStep;
percent = percent.toFixed();
$(".progress-bar")
.css("width",percent+"%")
}

$(".submit").click(function(){
return false;
})

});


    $(document).ready(function(){
    var today = new Date().toISOString().split('T')[0];
    var nextWeekDate = new Date(new Date().getTime() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];


        $('#checkin').attr('min',today);
        //$('#checkout').attr('min',today);


   });

  //  $('#checkin').on('change', function() {
  //        var startDate=this.value;
  //        var nextWeekDate = new Date(new Date(startDate).getTime() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
  //        $('#checkout').attr('min',nextWeekDate);
  //     });



</script>

<style type="text/css">
  .padding {
    padding: 5rem !important
}

.text-danger {
    color: red;
}

.form-control:focus {
    box-shadow: 10px 0px 0px 0px #ffffff !important;
    border-color: #4ca746
}
</style>
<script type="text/javascript">
// $('select[id*="day_date"]').change(function(){


// // start by setting everything to enabled
// $('select[id*="day_date"] option').attr('disabled',false);

// // loop each select and set the selected value to disabled in all other selects
// $('select[id*="day_date"]').each(function(){
//     var $this = $(this);
//     $('select[id*="day_date"]').not($this).find('option').each(function(){
//        if($(this).attr('value') == $this.val())
//            $(this).attr('disabled',true);
//     });
// });

// });
</script>
