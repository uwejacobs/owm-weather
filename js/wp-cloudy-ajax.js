jQuery(document).ready(function(){
	var wpc_weather_id = document.getElementsByClassName('wpc-weather-id');
	for(var i = 0; i < wpc_weather_id.length; i++) {	
		jQuery.ajax({
			url : wpcAjax.wpc_url,
			method : 'POST',
			data : {
				action: 'wpc_get_my_weather',
				wpc_param : wpc_weather_id[i].attributes['data-id'].value,
				_ajax_nonce: wpcAjax.wpc_nonce,
			},
			success : function( data ) {
				if ( data.success ) {
					jQuery( '#wpc-weather-id-' + data.data.weather ).append( data.data.html );
				} else {
					console.log( data.data );
				}
			},
			beforeSend: function(){
		       jQuery(".wpc-loading-spinner").show();
		       jQuery("div[id^='wpc-weather-container-']").hide();
		    },
		    complete: function(){
		       jQuery(".wpc-loading-spinner").hide();
		       jQuery("div[id^='wpc-weather-container-']").show();
		    },
		});
	}
});
