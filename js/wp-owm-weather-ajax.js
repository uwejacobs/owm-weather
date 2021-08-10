jQuery(document).ready(function($){
	var wow_weather_id = document.getElementsByClassName('wow-weather-id');
	for(var i = 0; i < wow_weather_id.length; i++) {	
		$.ajax({
			url : wowAjax.wow_url,
			method : 'POST',
			data : {
				action: 'wow_get_my_weather',
				counter: i,
				wow_params : $(wow_weather_id[i]).data(),
				_ajax_nonce: wowAjax.wow_nonce,
			},
			success : function( data ) {
				if ( data.success ) {
					$( '#' + data.data.weather ).append( data.data.html );
				} else {
					console.log("Error:" + data.data );
				}
			},
			beforeSend: function(){
		       $(".wow-loading-spinner").show();
		       $("div[id^='wow-weather-container-']").hide();
		    },
		    complete: function(){
		       $(".wow-loading-spinner").hide();
		       $("div[id^='wow-weather-container-']").show();
		    },
		});
	}
});
