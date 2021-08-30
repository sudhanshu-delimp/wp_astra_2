jQuery(document).ready(function(){

jQuery("#arrival_date").datepicker({
  numberOfMonths: 2,
  showButtonPanel: true,
  dateFormat:'mm-dd-yy',
  changeMonth: true,
  changeYear: true,
  yearRange: "-5:+0",
});
jQuery('#arrival_date').datepicker('setDate', 'today');

var current_fs, next_fs, previous_fs; //fieldsets
var opacity;

jQuery(".next-step").click(function(){
  current_fs = jQuery(this).parent();
  next_fs = jQuery(this).parent().next();

  var step_number = jQuery(this).attr('step');
  switch(step_number){
    case 'step-one':{
      var selected_date_data = process_step_one();
    } break;
    case 'step-two':{
      var selected_addon_data = process_step_two();
      console.log(selected_addon_data);
    } break;
    case 'step-three':{
      var selected_activity_data = process_step_three();
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
  return JSON.stringify(addons);
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

    },
    success: function(response){
      jQuery(".available_activities").html(response.available_activities);
      jQuery(".all-time").attr("disabled","disabled");
    }
  });
}

