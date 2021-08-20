jQuery(document).ready(function($){
	var owm_weather_id = document.getElementsByClassName('owm-weather-id');
	for(var i = 0; i < owm_weather_id.length; i++) {	
		$.ajax({
			url : owmwAjax.owmw_url,
			method : 'POST',
			data : {
				action: 'owmw_get_my_weather',
				counter: i,
				owmw_params : $(owm_weather_id[i]).data(),
				_ajax_nonce: owmwAjax.owmw_nonce,
			},
			dataType: 'json',
			success : function( data ) {
				$('#' + data.data.weather).append(data.data.html);
			},
			error : function(response) {
				var data = jQuery.parseJSON(response.responseText);
				$('#' + data.data.weather).append(data.data.html);
			},
			beforeSend: function(){
		       		$(".owmw-loading-spinner").show();
		   		$("div[id^='owm-weather-container-']").hide();
		    	},
		    	complete: function(){
		       		$(".owmw-loading-spinner").hide();
		       		$("div[id^='owm-weather-container-']").show();
		    },
		});
	}
});
