jQuery(document).ready(function($){
	function usePosition(position) {
	    window.latitude = position.coords.latitude;
	    window.longitude = position.coords.longitude;
	    owmw_get_my_weather(position.coords.latitude, position.coords.longitude);
	}

	function useIP(err) {
	    console.warn('ERROR(' + err.code + '): ' + err.message);
	    owmw_get_my_weather(0, 0);
	}

	var owm_weather_id = document.getElementsByClassName('owm-weather-id');
	var use_geo_location = false;
        for(var i = 0; i < owm_weather_id.length; i++) {
		if (jQuery(owm_weather_id[i]).data("geo_location")) {
			use_geo_location = true;
		}
	}

	if (use_geo_location && navigator.geolocation) {
	    navigator.geolocation.getCurrentPosition(usePosition, useIP);
	} else {
	    owmw_get_my_weather(0, 0);
	}
});

function owmw_get_my_weather(latitude, longitude) {
	var owm_weather_id = document.getElementsByClassName('owm-weather-id');
	for(var i = 0; i < owm_weather_id.length; i++) {
		jQuery.ajax({
			url : owmwAjax.owmw_url,
			method : 'POST',
			data : {
				action: 'owmw_get_my_weather',
				counter: i,
				owmw_params : jQuery(owm_weather_id[i]).data(),
				_ajax_nonce: owmwAjax.owmw_nonce,
				latitude: latitude,
				longitude: longitude,
			},
			dataType: 'json',
			timeout: 20000,
			success : function( xhr ) {
				jQuery('#' + xhr.data.weather).html(xhr.data.html);
			},
			error: function(xhr, status, error){
				if (xhr.responseJSON !== undefined) {
					var data = xhr.responseJSON.data;
					jQuery('#' + data.weather).append(data.html);
				} else {
					var errorMessage = xhr.status + ': ' + xhr.statusText;
					console.log('OWMW Error - ' + errorMessage);
				}
			},
		});
	}
}


function owmw_refresh_weather(id) {
	var owm_weather_id = jQuery("#"+id).closest(".owm-weather-id");
	jQuery.ajax({
                url : owmwAjax.owmw_url,
                method : 'POST',
                data : {
                        action: 'owmw_get_my_weather',
                        counter: 1, /*bugbug*/
                        owmw_params : jQuery(owm_weather_id).data(),
                        _ajax_nonce: owmwAjax.owmw_nonce,
			latitude: window.latitude,
			longitude: window.longitude,
                },
                dataType: 'json',
                timeout: 20000,
                success : function( xhr ) {
                        jQuery('#' + xhr.data.weather).html(xhr.data.html);
                },
                error: function(xhr, status, error){
                        if (xhr.responseJSON !== undefined) {
				var data = xhr.responseJSON.data;
				jQuery('#' + data.weather).append(data.html);
                        } else {
                                var errorMessage = xhr.status + ': ' + xhr.statusText;
                                console.log('OWMW Error - ' + errorMessage);
                        }
                },
        });
}
