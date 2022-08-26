jQuery(document).ready(function($){
    function usePosition(position) {
        window.latitude = position.coords.latitude;
        window.longitude = position.coords.longitude;
        const now = new Date();
        const geo_location = {
            latitude: position.coords.latitude,
            longitude: position.coords.longitude,
            accuracy: position.coords.accuracy,
            timestamp: position.timestamp
        };
        sessionStorage.setItem("owm_weather_geo_location", JSON.stringify(geo_location));
        owmw_get_my_weather(position.coords.latitude, position.coords.longitude);
    }

    function useIP(err) {
        const geo_location_no_permission = {
            permission: "no"
	}
        console.warn('ERROR(' + err.code + '): ' + err.message);
        if (err.code == 1) {
            sessionStorage.setItem("owm_weather_geo_location_no_permission", JSON.stringify(geo_location_no_permission));
        };
        owmw_get_my_weather(0, 0);
    }

    var owm_weather_id = document.getElementsByClassName('owm-weather-id');
    var use_geo_location = false;
    for (var i = 0; i < owm_weather_id.length; i++) {
        if (jQuery(owm_weather_id[i]).data("geo_location")) {
            use_geo_location = true;
        }
    }

    const geo_location_no_permission_str = sessionStorage.getItem("owm_weather_geo_location_no_permission");
    if (!geo_location_no_permission_str && use_geo_location && navigator.geolocation) {
        const geo_location_str = sessionStorage.getItem("owm_weather_geo_location");
        if (!geo_location_str) {
            navigator.geolocation.getCurrentPosition(usePosition, useIP);
        } else {
            const geo_location = JSON.parse(geo_location_str);
            window.latitude = geo_location.latitude;
            window.longitude = geo_location.longitude;
            owmw_get_my_weather(geo_location.latitude, geo_location.longitude);
        }
    } else {
        owmw_get_my_weather(0, 0);
    }
});

function owmw_get_my_weather(latitude, longitude) {
    var owm_weather_id = document.getElementsByClassName('owm-weather-id');
    for(var i = 0; i < owm_weather_id.length; i++) {
        if (latitude == 0 && longitude == 0) {
            lat = jQuery(owm_weather_id[i]).data("lat");
            lon = jQuery(owm_weather_id[i]).data("lon");
            if (lat) {
                latitude = lat;
                jQuery(owm_weather_id[i]).removeData("lat");
            }
            if (lon) {
                longitude = lon;
                jQuery(owm_weather_id[i]).removeData("lon");
            }
        }
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
            counter: 1001, /*bugbug*/
            owmw_params : jQuery(owm_weather_id).data(),
            _ajax_nonce: owmwAjax.owmw_nonce,
            latitude: window.latitude,
            longitude: window.longitude,
            },
        dataType: 'json',
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
