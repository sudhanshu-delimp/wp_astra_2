var selected_date_data = [], selected_activity_data = [], selected_addon_data = [] , user_data = {}, selected_date_data = {};
var activity_flag = 1;
var current_fs, next_fs, previous_fs; //fieldsets
var opacity;

var updateCal = function(){
  var selected_month = jQuery("#selected_month").val();
  var selected_day = jQuery("#selected_day").val();
  var selected_year = jQuery("#selected_year").val();
  var time_string = selected_month+'-'+selected_day+'-'+parseInt(selected_year);
  console.log(time_string);
  jQuery('#arrival_date').datepicker().datepicker('setDate', time_string);
  jQuery(".ui-datepicker-trigger").click();
  //jQuery('#arrival_date').datepicker('setDate', '05-15-2021');
}

jQuery(document).ready(function(){

jQuery("#arrival_date").datepicker({
  minDate: 0,
  numberOfMonths: 2,
  showButtonPanel: true,
  dateFormat:'mm-dd-yy',
  showOn: "button",
  buttonImage: wp_base_url+"/wp-content/uploads/2021/08/datepicker.png",
  buttonImageOnly: true,
  //buttonText: "Select date"
  onSelect: function(dateText, inst) {
     var date_string = dateText.split("-");
     jQuery("#selected_month").val(date_string[0]);
     jQuery("#selected_day").val(date_string[1]);
     jQuery("#selected_year").val(date_string[2]);
  }
});
jQuery('#arrival_date').datepicker('setDate', 'today');
jQuery(".ui-datepicker-trigger").click();


jQuery(".next-step").click(function(){
  current_fs = jQuery(this).parent();
  next_fs = jQuery(this).parent().next();

  var step_number = jQuery(this).attr('step');
  console.log(step_number);
  switch(step_number){
    case 'step-one':{
      var check_in_date = getDateFormat(jQuery("#arrival_date").val());
      var number_of_night = jQuery("#number_of_night").val();
      if(selected_date_data.check_in_date===undefined || selected_date_data.check_in_date!==check_in_date || selected_date_data.number_of_night!=number_of_night){
        console.log("get data");
        activity_flag = 1;
        process_step_one();
        return false;
      }
      else{
        activity_flag = 0;
        console.log("put old data");
      }

    } break;
    case 'step-two':{
      selected_addon_data = process_step_two();
    } break;
    case 'step-three':{
      selected_activity_data = process_step_three();
    } break;
    case 'step-four':{
      user_data = process_step_four();
      if(Object.keys(user_data).length == 0){
        return false;
      }
      else{
        get_preview_data();
      }
    } break;
    case 'step-five':{
      make_booking();
      return false;
    } break;
  }
  //Add Class Active
  jQuery("#progressbar li").eq(jQuery("fieldset").index(next_fs)).addClass("active");

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
  duration: 600
  });
});

jQuery(".previous").click(function(){

current_fs = jQuery(this).parent();
previous_fs = jQuery(this).parent().prev();

//Remove class active
jQuery("#progressbar li").eq(jQuery("fieldset").index(current_fs)).removeClass("active");

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
duration: 600
});
});


jQuery(".submit").click(function(){
  //console.log("submit");
  return false;
})

});



jQuery(document).on('change','.all_day',function(){
  var addon_id = jQuery(this).attr('addon-id');
  var selected_value = jQuery(this).val();
  jQuery(".quantity-"+addon_id).val(selected_value);
})

var getDateFormat = function(date){
  date = date.split("-");
    return date[2]+'-'+date[0]+'-'+date[1]
  }

var process_step_one = function(){
  var data = {};
  data['action'] = 'process_step_one';
  data['arrival_date'] = getDateFormat(jQuery("#arrival_date").val());
  data['number_of_night'] = jQuery("#number_of_night").val();
  data['adults_per_room'] = jQuery("#adults_per_room").val();
  jQuery.ajax({
    url:admin_ajax_url,
    type: "POST",
    data: data,
    dataType:'json',
    beforeSend: function(){
      console.log(data);
      general_Attr("#step-one-contain", 4, 'Processing...');
      jQuery(".available_addons").html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Loading...</span>');
    },
    success: function(response){
      if(response.status === '1'){
        removeAlert();
        jQuery(".available_addons").html(response.available_addons);
        selected_date_data = response.selected_date_data;


        //Add Class Active
        jQuery("#progressbar li").eq(jQuery("fieldset").index(next_fs)).addClass("active");

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
        duration: 600
        });
      }
      else{
        general_Attr("#step-one-contain", 2, response.message);
        return false;
      }
    }
  });
 }

var process_step_two = function(){
  var addons = [];
  jQuery('.selected-addons').each(function(index, ele){
    var element = jQuery(ele).find('option:selected');
    if(element.val() > 0){
      var addon_id = element.attr("addon-id");
      var date = element.attr("date");
      var price = element.attr("price");
      var addon_data = {};
      addon_data['addon_id'] = addon_id;
      addon_data['date'] = date;
      addon_data['quantity'] = parseInt(element.val());
      addon_data['price'] = parseInt(price);
      addon_data['total_price'] = parseInt(price)*parseInt(element.val());
      addons.push(addon_data);
    }
  });
  console.log("selected_activity_data: "+selected_activity_data);
  if(activity_flag == 1){
    get_activity_list();
  }
  return addons;
}

var process_step_three = function(){
  var activities = [];
  if(jQuery(".selected-actvities").length > 0){
    jQuery('.selected-actvities').each(function(index, ele){
      var element = jQuery(ele);
      var activity_data = {};
      activity_data['activity_id'] = element.find("input[name=activity_id]").val();
      activity_data['selected_date'] = element.find("input[name=selected_date]").val();
      activity_data['selected_time'] = element.find("input[name=selected_time]").val();
      activity_data['selected_quantity'] = element.find("input[name=selected_quantity]").val();
      activity_data['selected_price'] = element.find("input[name=selected_price]").val();
      activity_data['total_price'] = parseInt(activity_data['selected_price'])*parseInt(activity_data['selected_quantity']);
      activities.push(activity_data);
  });
  }
  return activities;
}

var process_step_four = function(){
  var form = jQuery("#user_detail");
  form.validate({
    errorClass: "text-danger",
    errorElement: "p",
    errorPlacement: function(error, element) {
      var par = jQuery(element).parent();
      error.appendTo(par);
    },
    rules: {
      email: {
        required: true,
      },
      phone: {
        required: true,
        number: true
      }
    },
    messages: {
      // email: {
      //   remote: "The email is already taken"
      // }
    }
  });
  var user = {};
  if (form.valid() === true) {
    console.log("++++++++++++++++++++++++++++++++++++++");
      var x = jQuery('form').serializeArray();
      if(Array.isArray(x)){
        jQuery.each(x, function(index, field){
          var user_data = {};
          user[field.name] = field.value;
        });
      }
  }
  return user;
}

var get_preview_data = function(){
  var data = {};
  data['action'] = 'show_booking_preview';
  data['arrival_date'] = getDateFormat(jQuery("#arrival_date").val());
  data['number_of_night'] = jQuery("#number_of_night").val();
  data['adults_per_room'] = jQuery("#adults_per_room").val();
  data['user_data'] = user_data;
  data['selected_addon_data'] = selected_addon_data;
  data['selected_activity_data'] = selected_activity_data;
  jQuery.ajax({
    url:admin_ajax_url,
    type: "POST",
    data: data,
    dataType:'json',
    beforeSend: function(){
      jQuery(".available_preview").html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Loading...</span>');
      console.log(data);
    },
    success: function(response){
      console.log(response);
       jQuery(".available_preview").html(response.available_preview);
       jQuery("#step-five-contain-btn").attr("disabled","disabled");
    }
  });
}

var make_booking = function(){
  var data = {};
  data['action'] = 'make_booking';
  data['arrival_date'] = getDateFormat(jQuery("#arrival_date").val());
  data['number_of_night'] = jQuery("#number_of_night").val();
  data['adults_per_room'] = jQuery("#adults_per_room").val();
  data['user_data'] = user_data;
  data['selected_addon_data'] = selected_addon_data;
  data['selected_activity_data'] = selected_activity_data;

  jQuery.ajax({
    url:admin_ajax_url,
    type: "POST",
    data: data,
    dataType:'json',
    beforeSend: function(){
      jQuery(".available_activities").html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Loading...</span>');
      general_Attr("#step-five-contain", 4, 'Processing...');
    },
    success: function(response){
      if(response.status === '1'){
        general_Attr("#step-five-contain", 1, response.data.message);
        jQuery("#reservation_id").html(response.data.reservation_id);
        jQuery("html, body").animate({ scrollTop: 0 }, "slow");
        //Add Class Active
        jQuery("#progressbar li").eq(jQuery("fieldset").index(next_fs)).addClass("active");

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
        duration: 600
        });
      }
      else{
        general_Attr("#step-five-contain", 2, response.data.message);
        jQuery("html, body").animate({ scrollTop: 0 }, "slow");
      }
      console.log(response);
    }
  });
}

var get_activity_list = function(){

  var data = {};
  data['action'] = 'get_activity_list';
  data['arrival_date'] = getDateFormat(jQuery("#arrival_date").val());
  data['number_of_night'] = jQuery("#number_of_night").val();
  jQuery.ajax({
    url:admin_ajax_url,
    type: "POST",
    data: data,
    dataType:'json',
    beforeSend: function(){
      jQuery(".available_activities").html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Loading...</span>');
    },
    success: function(response){
      jQuery(".available_activities").html(response.available_activities);
      jQuery(".all-time").attr("disabled","disabled");
    }
  });
}

jQuery(document).on('change','.choose-date',function(){
  var element = jQuery(this).find('option:selected');
  var activity_id = element.attr("activity-id");
  if(jQuery.trim(element.val()) !== ""){
    var init_options = jQuery("#time-hide-"+activity_id).html();
    jQuery("#time-"+activity_id).html(init_options);
    /*code for remove already slected time for the same day */
    if(jQuery("#activity-box-"+activity_id+" .selected-actvities").length > 0){
      jQuery("#activity-box-"+activity_id+" .selected-actvities").each(function(index, ele){
        var child_element = jQuery(ele);
        var selected_date = jQuery.trim(child_element.find("input[name=selected_date]").val());
        var selected_time = jQuery.trim(child_element.find("input[name=selected_time]").val());
        var selectobject = document.getElementById("time-"+activity_id);
        for (var i=0; i<selectobject.length; i++) {
          if(jQuery.trim(selectobject.options[i].value) == selected_time && selected_date == jQuery.trim(element.val())){
            selectobject.remove(i);
          }
        }
      });
    }else{
      console.log("not exist");
    }
    /*end code */
    jQuery(".time-"+activity_id).removeAttr('disabled');
  }
  else{
    /*Reset and disable time select field */
    jQuery(".time-"+activity_id).attr('disabled','disabled');
    jQuery(".time-"+activity_id).val("");
    /*Reset, hide and disable quantity select field */
    jQuery(".div-"+activity_id).addClass('invisible');
    jQuery(".quantity-"+activity_id).val("");
  }
});

jQuery(document).on('change','.all-time',function(){
  var element = jQuery(this).find('option:selected');
  var activity_id = element.attr("activity-id");
  if(jQuery.trim(element.val()) !== ""){
    jQuery(".div-"+activity_id).removeClass('invisible');
  }
  else{
    /*Reset, hide and disable quantity select field */
    jQuery(".div-"+activity_id).addClass('invisible');
    jQuery(".quantity-"+activity_id).val("");
  }
});

jQuery(document).on('change','.all-quantity',function(){
  var element = jQuery(this).find('option:selected');
  var activity_id = element.attr("activity-id");
  var selected_date = jQuery("#date-"+activity_id).val();
  var selected_time = jQuery("#time-"+activity_id).val();
  var selected_price = element.attr("price");
  var selected_quantity = element.val();
  addActivityInQueue(activity_id, selected_date, selected_time, selected_quantity, selected_price);
});

var addActivityInQueue = function(activity_id, selected_date, selected_time, selected_quantity, selected_price){
  var data = {};
  data['action'] = 'add_activity_in_queue';
  data['activity_id'] = activity_id;
  data['selected_date'] = selected_date;
  data['selected_time'] = selected_time;
  data['selected_quantity'] = selected_quantity;
  data['selected_price'] = selected_price;
  jQuery.ajax({
    url:admin_ajax_url,
    type: "POST",
    data: data,
    dataType:'json',
    beforeSend: function(){
      console.log(data);
    },
    success: function(response){
      jQuery("#activity-box-"+activity_id).append(response.activity_content);
      /*Reset date select field */
      jQuery(".date-"+activity_id).val("");
      /*Reset and disable time select field */
      jQuery(".time-"+activity_id).attr('disabled','disabled');
      jQuery(".time-"+activity_id).val("");
      /*Reset and disable quantity select field */
      jQuery(".div-"+activity_id).addClass('invisible');
      jQuery(".quantity-"+activity_id).val("");

      var init_options = jQuery("#time-hide-"+activity_id).html();
      jQuery("#time-"+activity_id).html(init_options);
    }
  });
}

jQuery(document).on('click','.remove-activity',function(e){
  e.preventDefault();
  var element = jQuery(this);
  var activity_id = element.parent().parent().find("input[name=activity_id]").val();
  element.parent().parent().remove();

  var init_options = jQuery("#time-hide-"+activity_id).html();
  jQuery("#time-"+activity_id).html(init_options);
  jQuery("#date-"+activity_id).val("");
});

jQuery(document).on('click','#confirm_by_user',function(){
  if(!this.checked){
    jQuery("#step-five-contain-btn").attr("disabled","disabled");
  }
  else{
    jQuery("#step-five-contain-btn").removeAttr("disabled");
  }
});


// top
