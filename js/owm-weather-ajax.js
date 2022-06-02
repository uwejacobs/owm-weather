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
			timeout: 7000,
			success : function( xhr ) {
				$('#' + xhr.data.weather).html(xhr.data.html);
			},
			error: function(xhr, status, error){
				if (xhr.responseJSON !== undefined) {
					var data = JSON.parse(xhr.responseJSON);
					$('#' + data.data.weather).append(data.data.html);
				} else {
					var errorMessage = xhr.status + ': ' + xhr.statusText;
					console.log('OWMW Error - ' + errorMessage);
				}
			},
		});
	}
});

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
                },
                dataType: 'json',
                timeout: 7000,
                success : function( xhr ) {
                        jQuery('#' + xhr.data.weather).html(xhr.data.html);
                },
                error: function(xhr, status, error){
                        if (xhr.responseJSON !== undefined) {
                                var data = JSON.parse(xhr.responseJSON);
                                jQuery('#' + data.data.weather).append(data.data.html);
                        } else {
                                var errorMessage = xhr.status + ': ' + xhr.statusText;
                                console.log('OWMW Error - ' + errorMessage);
                        }
                },
        });
}
