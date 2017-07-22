jQuery(document).ready(function($){

	$("#datepicker-1").datepicker({
		onSelect: function(date, event){
			get_available_timeslots(date);
		}
	});

	var xhr;

	function get_available_timeslots(date, event) {

		var $form = $('form.cart');
		var test_div = $(".test_div");

		if (xhr) xhr.abort();

		xhr = $.ajax({
			type: 		'POST',
			url: 		mvv_tsr_params.ajax_url,
			data: 		{
				action: 'mvv_available_timeslots',
				form:   $form.serialize()
			},
			success: function( code ) {
				test_div.html( code );
			},
			dataType: 	"html"
		});
	}

});