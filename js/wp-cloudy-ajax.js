jQuery(document).ready(function($){
	var wpc_weather_id = document.getElementsByClassName('wpc-weather-id');
	for(var i = 0; i < wpc_weather_id.length; i++) {	
		$.ajax({
			url : wpcAjax.wpc_url,
			method : 'POST',
			data : {
				action: 'wpc_get_my_weather',
				counter: i,
				wpc_params : $(wpc_weather_id[i]).data(),
				_ajax_nonce: wpcAjax.wpc_nonce,
			},
			success : function( data ) {
				if ( data.success ) {
					$( '#' + data.data.weather ).append( data.data.html );
				} else {
					console.log("Error:" + data.data );
				}
			},
			beforeSend: function(){
		       $(".wpc-loading-spinner").show();
		       $("div[id^='wpc-weather-container-']").hide();
		    },
		    complete: function(){
		       $(".wpc-loading-spinner").hide();
		       $("div[id^='wpc-weather-container-']").show();
		    },
		});
	}
});
