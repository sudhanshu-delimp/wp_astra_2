jQuery(document).ready(function(){

jQuery("#arrival_date").datepicker({
  minDate: 0,
  numberOfMonths: 2,
  showButtonPanel: true,
  dateFormat:'mm-dd-yy',
  // changeMonth: true,
  // changeYear: true,
  // yearRange: "-5:+0"
  showOn: "button",
  buttonImage: wp_base_url+"/wp-content/uploads/2021/08/datepicker.png",
  buttonImageOnly: true,
  //buttonText: "Select date"
});
jQuery('#arrival_date').datepicker('setDate', 'today');

var current_fs, next_fs, previous_fs; //fieldsets
var opacity;
var selected_activity_data = [],selected_addon_data = [];
jQuery(".next-step").click(function(){
  current_fs = jQuery(this).parent();
  next_fs = jQuery(this).parent().next();

  var step_number = jQuery(this).attr('step');
  switch(step_number){
    case 'step-one':{
      var selected_date_data = process_step_one();
    } break;
    case 'step-two':{
      selected_addon_data = process_step_two();
    } break;
    case 'step-three':{
      selected_activity_data = process_step_three();
      console.log("selected_addon_data");
      console.log(selected_addon_data);
      console.log("selected_activity_data");
      console.log(selected_activity_data);
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
  console.log("submit");
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
  jQuery.ajax({
    url:admin_ajax_url,
    type: "POST",
    data: data,
    dataType:'json',
    beforeSend: function(){
      jQuery(".available_addons").html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Loading...</span>');
    },
    success: function(response){
      jQuery(".available_addons").html(response.available_addons);
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
  get_activity_list();
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
    var selectobject = document.getElementById("time-"+activity_id);
    console.log(selectobject.length);
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
    }
  });
}

jQuery(document).on('click','.remove-activity',function(e){
  e.preventDefault();
  var element = jQuery(this);
  element.parent().parent().remove();
});
