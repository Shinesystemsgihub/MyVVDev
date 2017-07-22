


//jQuery time
var current_fs, next_fs, previous_fs; //fieldsets
var left, opacity, scale; //fieldset properties which we will animate
var animating; //flag to prevent quick multi-click glitches

jQuery(document).ready(function($){

	$(function(){

		jQuery("select#vacation-status").change(function(){

			if (jQuery("select#vacation-status").val() == "yet") {
				jQuery("label[for=datepicker-1]").html("Arrival Date");
				jQuery("label[for=arrival-time-slot]").html("Delivery Time Slot");
				//jQuery("#pre-arrival").val("");
			}

			if (jQuery("select#vacation-status").val() == "already") {
				jQuery("label[for=datepicker-1]").html("Delivery Date");
				jQuery("label[for=arrival-time-slot]").html("Delivery Time Slot");
				//jQuery("#pre-arrival").val("NA-pre-arrival");
			}
			jQuery("#arrival-time-slot").val("");
			jQuery("#datepicker-1").val("");
		});

		jQuery("#datepicker-1").change(function(){
			jQuery("#arrival-time-slot").val("");
		});

		jQuery("#pre-arrival").change(function(){
			jQuery("#datepicker-1").val(""); 
			jQuery("#arrival-time-slot").val("");
		});

	});
	
});


jQuery(document).ready(function($){


	jQuery(".next").click(function(){
	if(animating) return false;
	animating = true;
	
	current_fs = jQuery(this).parent();
	next_fs = jQuery(this).parent().next();
	/*
	if(jQuery("#pre-arrival").val() === "NA-pre-arrival"){

		if(jQuery(this).parent().is(":first-child") ) {
			next_fs = jQuery(this).parent().next().next();
		}

	}
	*/

	
	//show the next fieldset
	next_fs.show(); 
	//hide the current fieldset with style
	current_fs.animate({opacity: 0}, 
	{
		step: function(now, mx) {
			//as the opacity of current_fs reduces to 0 - stored in "now"
			//1. scale current_fs down to 80%
			scale = 1 - (1 - now) * 0.2;
			//2. bring next_fs from the right(50%)
			left = (now * 50)+"%";
			//3. increase opacity of next_fs to 1 as it moves in
			opacity = 1 - now;
			current_fs.css({
        'transform': 'scale('+scale+')',
        'position': 'absolute'
      });
			next_fs.css({'left': left, 'opacity': opacity});
		}, 
		duration: 400, 
		complete: function(){
			current_fs.hide();
			animating = false;
		},
		easing: 'easeInOutBack'
	});
});

$(".previous").click(function(){
	if(animating) return false;
	animating = true;
	
	current_fs = $(this).parent();
	previous_fs = $(this).parent().prev();
	
	/*
	if(jQuery("#pre-arrival").val() === "NA-pre-arrival"){

		if(jQuery(this).parent().prev().prev().is(":first-child") ) {
			previous_fs = jQuery(this).parent().prev().prev();
		}

	}
	*/
	//show the previous fieldset
	previous_fs.show(); 
	//hide the current fieldset with style
	current_fs.animate({opacity: 0}, {
		step: function(now, mx) {
			//as the opacity of current_fs reduces to 0 - stored in "now"
			//1. scale previous_fs from 80% to 100%
			scale = 0.8 + (1 - now) * 0.2;
			//2. take current_fs to the right(50%) - from 0%
			left = ((1-now) * 50)+"%";
			//3. increase opacity of previous_fs to 1 as it moves in
			opacity = 1 - now;
			current_fs.css({'left': left});
			previous_fs.css({'transform': 'scale('+scale+')', 'opacity': opacity});
		}, 
		duration: 800, 
		complete: function(){
			current_fs.hide();
			animating = false;
		}
	});
});


$(".submit").click(function(){
	return false;
});

	var min_date = new Date();
	$("#datepicker-1").datepicker({
		minDate: min_date,
		onSelect: function(date, event){
			jQuery('form.cart').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});
			
				get_available_timeslots_arrival(date);
			
			
		}
	});
	$("#datepicker-2").datepicker({
		minDate: min_date,
		onSelect: function(date, event){
			jQuery('form.cart').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});

			get_available_timeslots_departure(date);
		}
	});

	var xhr;

	function get_available_timeslots_arrival(date, event) {

		var $form = $('form.cart');
		var test_div = $(".test_div");

		if (xhr) xhr.abort();

		xhr = $.ajax({
			type: 		'POST',
			url: 		mvv_tsr_params.ajax_url,
			data: 		{
				action: 'mvv_available_timeslots_arrival',
				form:   $form.serialize()
			},
			success: function( code ) {
				$("select#arrival-time-slot").children().show();
				$.each(code, function(k,v){

					$("select#arrival-time-slot option[value='" + v + "']").hide();
				});
				
				$('form.cart').unblock();
			},
			dataType: 	"json"
		});
	}
	function get_available_timeslots_departure(date, event) {

		var $form = $('form.cart');
		var test_div = $(".test_div");

		if (xhr) xhr.abort();

		xhr = $.ajax({
			type: 		'POST',
			url: 		mvv_tsr_params.ajax_url,
			data: 		{
				action: 'mvv_available_timeslots_departure',
				form:   $form.serialize()
			},
			success: function( code ) {
				$("select#departure-time-slot").children().show();
				$.each(code, function(k,v){
					$("select#departure-time-slot option[value='" + v + "']").remove();
				});
				$('form.cart').unblock();
			},
			dataType: 	"json"
		});
	}

});