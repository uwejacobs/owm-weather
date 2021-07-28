<?php
function weatherIcon($iconpack, $id, $day_night, $description) {
    if ($iconpack == 'WeatherIcons') {
        return '<div class="wpc-symbol"><i class="wi wi-owm-' . $day_night . '-' . $id . '" title="' . $description . '"></i></div>';
	} else if ($iconpack == 'Forecast') {
        return weatherIconvault($id, $day_night, $description);
	} else if ($iconpack == 'Dripicons') {
        return weatherDripicons($id, $day_night, $description);
	} else if ($iconpack == 'Pixeden') {
        return weatherPixeden($id, $day_night, $description);
	} else if ($iconpack == 'OpenWeatherMap') {
        return weatherOpenWeatherMap($id, $day_night, $description);
	} else {
   		return '<div class="wpc-symbol climacon w' . $id . ' ' . $day_night  .'" title="' . $description . '"></div>';
	}
}

function weatherSVG($id, $dayNight) {
	switch ($id) {

		//sun
		case "800":
		  return $dayNight == "day" ? sun() : moon();
		  break;
		case "801":
		  return $dayNight == "day" ? cloudSun() : cloudMoon();
		  break;
		case "802":
		  return cloud();
		  break;
		case "803":
		  return cloudFill();
		  break;
		case "804":
		  return cloudFill();
		  break;

		//rain
		case "500":
		  return $dayNight == "day" ? cloudDrizzleSun() : cloudDrizzleMoon();
		  break;
		case "501":
		  return $dayNight == "day" ? cloudDrizzleSun() : cloudDrizzleMoon();
		  break;
		case "502":
		  return cloudDrizzle();
		  break;
		case "503":
		  return $dayNight == "day" ? cloudDrizzleSunAlt() : cloudDrizzleMoonAlt();
		  break;
		case "504":
		  return cloudDrizzleAlt();
		  break;
		case "511":
		  return $dayNight == "day" ? cloudRainSun() : cloudRainMoon();
		  break;
		case "520":
		  return cloudRain();
		  break;
		case "521":
		  return $dayNight == "day" ? cloudSunRainAlt() : cloudMoonRainAlt();
		  break;
		case "522":
		  return cloudRainAlt();
		  break;
		case "531":
		  return cloudRainAlt();
		  break;

		//drizzle
		case "300":
		  return cloudRainAlt();
		  break;
		case "301":
		  return cloudRainAlt();
		  break;
		case "302":
		  return cloudRainAlt();
		  break;
		case "310":
		  return cloudRainAlt();
		  break;
		case "311":
		  return cloudRainAlt();
		  break;
		case "312":
		  return cloudRainAlt();
		  break;
		case "313":
		  return cloudRainAlt();
		  break;
		case "314":
		  return cloudRainAlt();
		  break;
		case "321":
		  return cloudRainAlt();
		  break;

		//snow
		case "600":
		  return $dayNight == "day" ? cloudSnowSun() : cloudSnowMoon();
		  break;
		case "601":
		  return cloudSnow();
		  break;
		case "602":
		  return $dayNight == "day" ? cloudSnowSunAlt() : cloudSnowMoonAlt();
		  break;
		case "611":
		  return cloudSnow();
		  break;
		case "612":
		  return cloudSnow();
		  break;
		case "613":
		  return cloudSnow();
		  break;
		case "615":
		  return cloudSnow();
		  break;
		case "616":
		  return cloudSnow();
		  break;
		case "620":
		  return cloudSnow();
		  break;
		case "621":
		  return cloudSnowAlt();
		  break;
		case "622":
		  return cloudSnowAlt();
		  break;

		//atmosphere
		case "701":
		  return $dayNight == "day" ? cloudFogSunAlt() : cloudFogMoonAlt();
		  break;
		case "711":
		  return cloudFogAlt();
		  break;
		case "721":
		  return cloudFogAlt();
		  break;
		case "731":
		  return $dayNight == "day" ? cloudFogSun() : cloudFogMoon();
		  break;
		case "741":
		  return cloudFog();
		  break;
		case "751":
		  return $dayNight == "day" ? cloudFogSun() : cloudFogMoon();
		  break;
		case "761":
		  return $dayNight == "day" ? cloudFogSun() : cloudFogMoon();
		  break;
		case "762":
		  return $dayNight == "day" ? cloudFogSun() : cloudFogMoon();
		  break;
		case "771":
		  return tornado();
		  break;
		case "781":
		  return tornado();
		  break;

		//extreme
		case "900":
		  return tornado();
		  break;
		case "901":
		  return wind();
		  break;
		case "902":
		  return wind();
		  break;
		case "905":
		  return wind();
		  break;
		case "906":
		  return cloudHailAlt();
		  break;

		//thunderstorm
		case "200":
		  return cloudLightning();
		  break;

		case "201":
		  return cloudLightning();
		  break;

		case "202":
		  return cloudLightning();
		  break;

		case "210":
		  return cloudLightning();
		  break;

		case "211":
		  return cloudLightning();
		  break;

		case "212":
		  return cloudLightning();
		  break;

		case "221":
		  return cloudLightning();
		  break;

		case "230":
		  return cloudLightning();
		  break;

		case "231":
		  return cloudLightning();
		  break;

		case "232":
		  return cloudLightning();

	}

    return '';
}

function weatherOpenWeatherMap($id, $day_night, $description) {
    $icon = null;

	switch ($id) {
		case 200:
        case 201:
        case 202:
        case 210:
        case 211:
        case 212:
        case 221:
        case 230:
        case 231:
        case 232:
            $icon = '11';
            break;

		case 300:
		case 301:
		case 302:
		case 310:
		case 311:
		case 312:
		case 313:
		case 314:
		case 321:
            $icon = '09';
            break;

		case 500:
		case 501:
		case 502:
		case 503:
		case 504:
            $icon = '10';
            break;

		case 511:
            $icon = '13';
            break;

		case 520:
		case 521:
		case 522:
		case 531:
            $icon = '09';
            break;

		case 600:
		case 601:
		case 602:
		case 611:
		case 612:
		case 613:
		case 615:
		case 616:
		case 620:
		case 621:
		case 622:
            $icon = '13';
            break;

		case 701:
		case 711:
		case 721:
		case 731:
		case 741:
		case 751:
		case 761:
		case 762:
		case 771:
		case 781:
		case 900:
		case 901:
		case 902:
		case 903:
		case 904:
		case 905:
		case 906:
            $icon = '50';
            break;

		case 800:
            $icon = '01';
            break;

		case 801:
            $icon = '02';
            break;

		case 802:
            $icon = '03';
            break;
		case 803:
		case 804:
            $icon = '04';
            break;
    }
    
    if ($icon) {
        return '<img class="wpc-symbol owm-icon img-fluid" src="https://openweathermap.org/img/wn/' . $icon . ($day_night == 'day' ? 'd' : 'n') . '.png" title="'.$description.'" alt="'.$description.'">';
    }
    
    return '';
}

function weatherIconvault($id, $day_night, $description) {
    $str = ($day_night == "day" ? "sunny" : "night");
    
	$iconvault = array (
        //sun
        800 => '<li class="icon-' . ($day_night == "day" ? "sun" : "moon") . '"></li>',
        801 => '<li class="fullcloud"></li><li class="icon-' . $str . '"></li>',
        802 => '<li class="fullcloud"></li>',
        803 => '<li class="fullcloud icon-fill"></li>',
        804 => '<li class="fullcloud icon-fill"></li>',

        //rain
        500 => '<li class="basecloud"></li><li class="icon-drizzle icon-' . $str . '"></li>',
        501 => '<li class="basecloud"></li><li class="icon-drizzle icon-' . $str . '"></li>',
        502 => '<li class="basecloud"></li><li class="icon-drizzle"></li>',
        503 => '<li class="basecloud"></li><li class="icon-drizzle icon-' . $str . '"></li>',
        504 => '<li class="basecloud"></li><li class="icon-drizzle"></li>',
        511 => '<li class="basecloud"></li><li class="icon-rainy icon-' . $str . '"></li>',
        520 => '<li class="basecloud"></li><li class="icon-rainy"></li>',
        521 => '<li class="basecloud"></li><li class="icon-showers icon-' . $str . '"></li>',
        522 => '<li class="basecloud"></li><li class="icon-showers"></li>',
        531 => '<li class="basecloud"></li><li class="icon-showers"></li>',

        //drizzle
        300 => '<li class="basecloud"><li class="icon-drizzle"></li>',
        301 => '<li class="basecloud"><li class="icon-drizzle"></li>',
        302 => '<li class="basecloud"><li class="icon-drizzle"></li>',
        310 => '<li class="basecloud"><li class="icon-drizzle"></li>',
        311 => '<li class="basecloud"><li class="icon-drizzle"></li>',
        312 => '<li class="basecloud"><li class="icon-drizzle"></li>',
        313 => '<li class="basecloud"><li class="icon-drizzle"></li>',
        314 => '<li class="basecloud"><li class="icon-drizzle"></li>',
        321 => '<li class="basecloud"><li class="icon-drizzle"></li>',

        //snow
        600 => '<li class="basecloud"><li class="icon-snowy icon-' . $str . '"></li>',
        601 => '<li class="basecloud"><li class="icon-snowy"></li>',
        602 => '<li class="basecloud"><li class="icon-snowy icon-' . $str . '"></li>',
        611 => '<li class="basecloud"></li><li class="icon-sleet"></li>',
        612 => '<li class="basecloud"></li><li class="icon-sleet"></li>',
        613 => '<li class="basecloud"></li><li class="icon-sleet"></li>',
        615 => '<li class="basecloud"></li><li class="icon-sleet"></li>',
        616 => '<li class="basecloud"></li><li class="icon-sleet"></li>',
        620 => '<li class="basecloud"></li><li class="icon-sleet"></li>',
        621 => '<li class="basecloud"><li class="icon-snowy"></li>',
        622 => '<li class="basecloud"><li class="icon-snowy"></li>',

        //atmosphere
        701 => '<li class="icon-mist icon-' . $str . '"></li>',
        711 => '<li class="icon-mist"></li>',
        721 => '<li class="icon-mist"></li>',
        731 => '<li class="icon-mist icon-' . $str . '"></li>',
        741 => '<li class="icon-mist"></li>',
        751 => '<li class="icon-mist icon-' . $str . '"></li>',
        761 => '<li class="icon-mist icon-' . $str . '"></li>',
        762 => '<li class="icon-mist icon-' . $str . '"></li>',
        771 => '<li class="basecloud"></li><li class="icon-windy"></li>',
        781 => '<li class="basecloud"></li><li class="icon-windy"></li>',

        //extreme
        900 => '<li class="basecloud"></li><li class="icon-windy"></li>',
        901 => '<li class="basecloud"></li><li class="icon-windy"></li>',
        902 => '<li class="basecloud"></li><li class="icon-windy"></li>',
        905 => '<li class="basecloud"></li><li class="icon-windy"></li>',
        906 => '<li class="basecloud"></li><li class="icon-hail"></li>',

        //thunderstorm
        200 => '<li class="basecloud"><li class="icon-thunder"></li>',
        201 => '<li class="basecloud"><li class="icon-thunder"></li>',
        202 => '<li class="basecloud"><li class="icon-thunder"></li>',
        210 => '<li class="basecloud"><li class="icon-thunder"></li>',
        211 => '<li class="basecloud"><li class="icon-thunder"></li>',
        212 => '<li class="basecloud"><li class="icon-thunder"></li>',
        221 => '<li class="basecloud"><li class="icon-thunder"></li>',
        230 => '<li class="basecloud"><li class="icon-thunder"></li>',
        231 => '<li class="basecloud"><li class="icon-thunder"></li>',
        232 => '<li class="basecloud"><li class="icon-thunder"></li>'
	);

    return '<div class="wpc-symbol iconvault" title="' . $description . '"><ul class="list-unstyled">'. ($iconvault[$id] ?? '') .'</ul></div>';
}

function weatherDripicons($id, $day_night, $description) {
    $str = ($day_night == "day" ? "sun" : "moon");

	$dripicon = array (
        //sun
        800 => ($day_night == "day" ? "diw-sun" : "diw-moon-stars"),
        801 => 'diw-cloud-' . $str,
        802 => 'diw-clouds-' . $str,
        803 => 'diw-clouds',
        804 => 'diw-cloud',

        //rain
        500 => 'diw-cloud-rain-' . $str,
        501 => 'diw-cloud-rain-' . $str,
        502 => 'diw-cloud-rain',
        503 => 'diw-cloud-rain',
        504 => 'diw-cloud-rain',
        511 => 'diw-cloud-rain-' . $str,
        520 => 'diw-cloud-rain-' . $str,
        521 => 'diw-cloud-rain-' . $str,
        522 => 'diw-cloud-rain',
        531 => 'diw-cloud-rain',

        //drizzle
        300 => 'diw-cloud-drizzle-' . $str,
        301 => 'diw-cloud-drizzle-' . $str,
        302 => 'diw-cloud-drizzle',
        310 => 'diw-cloud-drizzle-' . $str,
        311 => 'diw-cloud-drizzle',
        312 => 'diw-cloud-drizzle',
        313 => 'diw-cloud-drizzle-' . $str,
        314 => 'diw-cloud-drizzle',
        321 => 'diw-cloud-drizzle-' . $str,

        //snow
        600 => 'diw-cloud-snow-' . $str,
        601 => 'diw-cloud-snow-' . $str,
        602 => 'diw-cloud-snow',
        611 => 'diw-cloud-snow',
        612 => 'diw-cloud-snow-' . $str,
        613 => 'diw-cloud-snow',
        615 => 'diw-cloud-snow-' . $str,
        616 => 'diw-cloud-snow',
        620 => 'diw-cloud-snow-' . $str,
        621 => 'diw-cloud-snow',
        622 => 'diw-cloud-snow',

        //atmosphere
        701 => 'diw-fog',
        711 => 'diw-fog',
        721 => 'diw-fog',
        731 => 'diw-fog',
        741 => 'diw-cloud-fog',
        751 => 'diw-fog',
        761 => 'diw-fog',
        762 => 'diw-fog',
        771 => 'diw-fog',
        781 => 'diw-tornado',

        //extreme
        900 => 'diw-tornado',
        901 => 'diw-cloud-wind-' . $str,
        902 => 'diw-cloud-wind-' . $str,
        903 => 'diw-thermometer-25',
        904 => 'diw-thermometer-100',
        905 => 'diw-cloud-wind-' . $str,
        906 => 'diw-cloud-hail-' . $str,

        //thunderstorm
        200 => 'diw-cloud-rain-lightning-' . $str,
        201 => 'diw-cloud-rain-lightning',
        202 => 'diw-cloud-rain-lightning',
        210 => 'diw-cloud-lightning-' . $str,
        211 => 'diw-cloud-lightning',
        212 => 'diw-cloud-lightning',
        221 => 'diw-cloud-lightning',
        230 => 'diw-cloud-drizzle-lightning-' . $str,
        231 => 'diw-cloud-drizzle-lightning',
        232 => 'diw-cloud-drizzle-lightning'
   
    );

    return '<div class="wpc-symbol diw '. ($dripicon[$id] ?? '') .'" title="' . $description . '"></div>';
}

function weatherPixeden($id, $day_night, $description) {
    $icon = null;

	switch ($id) {
        case 800: $icon = $day_night == "day" ? "sun-1" : "moon-1"; break;
        case 801: $icon = $day_night == "day" ? "partly-cloudy-1" : "partly-cloudy-3"; break;
        case 802: $icon = $day_night == "day" ? "partly-cloudy-1" : "partly-cloudy-3"; break;
        case 803: $icon = $day_night == "day" ? "mostly-cloudy-2" : "mostly-cloudy-2"; break;
        case 804: $icon = $day_night == "day" ? "mostly-cloudy-1" : "mostly-cloudy-1"; break;
        case 500: $icon = $day_night == "day" ? "rain-day" : "rain-night"; break;
        case 501: $icon = $day_night == "day" ? "rain-day" : "rain-night"; break;
        case 502: $icon = $day_night == "day" ? "heavy-rain-2" : "heavy-rain-2"; break;
        case 503: $icon = $day_night == "day" ? "heavy-rain-1-f" : "heavy-rain-1-f"; break;
        case 504: $icon = $day_night == "day" ? "heavy-rain-1-f" : "heavy-rain-1-f"; break;
        case 511: $icon = $day_night == "day" ? "rain-and-snow" : "rain-and-snow"; break;
        case 520: $icon = $day_night == "day" ? "rain-day" : "rain-night"; break;
        case 521: $icon = $day_night == "day" ? "rain-day" : "rain-night"; break;
        case 522: $icon = $day_night == "day" ? "heavy-rain-2" : "heavy-rain-1"; break;
        case 531: $icon = $day_night == "day" ? "heavy-rain-1-f" : "heavy-rain-1-f"; break;
        case 300: $icon = $day_night == "day" ? "drizzle" : "drizzle"; break;
        case 301: $icon = $day_night == "day" ? "drizzle" : "drizzle"; break;
        case 302: $icon = $day_night == "day" ? "drizzle-f" : "drizzle-f"; break;
        case 310: $icon = $day_night == "day" ? "rain-day" : "rain-night"; break;
        case 311: $icon = $day_night == "day" ? "rain-day" : "rain-night"; break;
        case 312: $icon = $day_night == "day" ? "heavy-rain-2-f" : "heavy-rain-2-f"; break;
        case 313: $icon = $day_night == "day" ? "drizzle" : "drizzle"; break;
        case 314: $icon = $day_night == "day" ? "drizzle-f" : "drizzle-f"; break;
        case 321: $icon = $day_night == "day" ? "drizzle" : "drizzle"; break;
        case 600: $icon = $day_night == "day" ? "snow-day-1" : "snow-night-1"; break;
        case 601: $icon = $day_night == "day" ? "snow-day-2" : "snow-night-2"; break;
        case 602: $icon = $day_night == "day" ? "snow-day-3" : "snow-night-3"; break;
        case 611: $icon = $day_night == "day" ? "snow-day-1" : "snow-night-1"; break;
        case 612: $icon = $day_night == "day" ? "snow-day-2" : "snow-night-2"; break;
        case 613: $icon = $day_night == "day" ? "snow-day-3" : "snow-night-3"; break;
        case 615: $icon = $day_night == "day" ? "rain-and-snow" : "rain-and-snow"; break;
        case 616: $icon = $day_night == "day" ? "rain-and-snow-f" : "rain-and-snow-f"; break;
        case 620: $icon = $day_night == "day" ? "snow-day-1" : "snow-night-1"; break;
        case 621: $icon = $day_night == "day" ? "snow-day-2" : "snow-night-2"; break;
        case 622: $icon = $day_night == "day" ? "snow-day-3" : "snow-night-3"; break;
        case 701: $icon = $day_night == "day" ? "mist" : "mist"; break;
        case 711: $icon = $day_night == "day" ? "fog-2" : "fog-2"; break;
        case 721: $icon = $day_night == "day" ? "fog-2" : "fog-2"; break;
        case 731: $icon = $day_night == "day" ? "fog-2" : "fog-2"; break;
        case 741: $icon = $day_night == "day" ? "fog-3" : "fog-4"; break;
        case 751: $icon = $day_night == "day" ? "fog-2" : "fog-2"; break;
        case 761: $icon = $day_night == "day" ? "fog-2" : "fog-2"; break;
        case 762: $icon = $day_night == "day" ? "fog-2" : "fog-2"; break;
        case 771: $icon = $day_night == "day" ? "fog-1" : "fog-1"; break;
        case 781: $icon = $day_night == "day" ? "tornado-1" : "tornado-1"; break;
        case 900: $icon = $day_night == "day" ? "tornado-2" : "tornado-2"; break;
        case 901: $icon = $day_night == "day" ? "wind" : "wind"; break;
        case 902: $icon = $day_night == "day" ? "wind" : "wind"; break;
        case 903: $icon = $day_night == "day" ? "thermometer-5" : "thermometer-5"; break;
        case 904: $icon = $day_night == "day" ? "thermometer-1" : "thermometer-1"; break;
        case 905: $icon = $day_night == "day" ? "wind" : "wind"; break;
        case 906: $icon = $day_night == "day" ? "heavy-hail-day" : "heavy-hail-night"; break;
        case 200: $icon = $day_night == "day" ? "mix-rainfall-1" : "mix-rainfall-1"; break;
        case 201: $icon = $day_night == "day" ? "mix-rainfall-2" : "mix-rainfall-2"; break;
        case 202: $icon = $day_night == "day" ? "mix-rainfall-2-f" : "mix-rainfall-1-f"; break;
        case 210: $icon = $day_night == "day" ? "thunderstorm-day-1" : "thunderstorm-night-1"; break;
        case 211: $icon = $day_night == "day" ? "thunderstorm" : "thunderstorm"; break;
        case 212: $icon = $day_night == "day" ? "severe-thunderstorm" : "severe-thunderstorm"; break;
        case 221: $icon = $day_night == "day" ? "severe-thunderstorm-f" : "severe-thunderstorm-f"; break;
        case 230: $icon = $day_night == "day" ? "mix-rainfall-1" : "mix-rainfall-1"; break;
        case 231: $icon = $day_night == "day" ? "mix-rainfall-2" : "mix-rainfall-2"; break;
        case 232: $icon = $day_night == "day" ? "mix-rainfall-2-f" : "mix-rainfall-1-f"; break;
    }

    return '<span class="wpc-symbol pe pe-is-w-'. ($icon ?? '') .'" title="' . $description . '"></span>';
}

function temperatureUnitSymbol($id, $display_unit, $unit, $iconpack) {
    $str = '';
    
	    if ($display_unit) {
	    	$str .=
	    		'<style>
	            	#'.$id.'.wpc-small .wpc-now .wpc-main-temperature:after,
	            	#'.$id.'.wpc-medium .wpc-now .wpc-main-temperature:after,
	            	#'.$id.'.wpc-large .wpc-now .wpc-main-temperature:after,
	              	#'.$id.' .wpc-forecast .wpc-temp-max:after,
	              	#'.$id.' .wpc-forecast .wpc-temp-min:after,
	              	#'.$id.' .wpc-hours .wpc-temperature:after,
	              	#'.$id.' .wpc-today .wpc-main-temperature_max:after,
	              	#'.$id.' .wpc-today .wpc-main-temperature_min:after,
	              	#'.$id.' .wpc-now .wpc-main-temperature:after,
	              	#'.$id.' .wpc-table .wpc-temperature:after {';

	        if ($unit == 'metric') {
	            if ($iconpack == 'WeatherIcons') {
        	    	$str .=
		                'content:"\f03c";
		                font-family: "weathericons";
                        margin-left: 2px;';
	            } else if ($iconpack == 'Forecast') {
        	    	$str .=
		                'content:"C";
		                font-family: "iconvault";';
	            } else if ($iconpack == 'Dripicons') {
        	    	$str .=
		                'content:"\0039";
		                font-family: "dripicons-weather";';
	            } else if ($iconpack == 'Pixeden') {
        	    	$str .=
		                'content:"\0039";
		                font-family: "pe-icon-set-weather";';
	            } else if ($iconpack == 'OpenWeatherMap') {
        	    	$str .=
		                'content:"°C";';
	            } else {
        	    	$str .=
		                'content:"\e03e";
		                font-family: "Climacons-Font";
                        margin-left: 2px;';
		        }
            } else {
	            if ($iconpack == 'WeatherIcons') {
        	    	$str .=
		                'content:"\f045";
		                font-family: "weathericons";
                        margin-left: 2px;';
	            } else if ($iconpack == 'Forecast') {
        	    	$str .=
		                'content:"F";
		                font-family: "iconvault";';
	            } else if ($iconpack == 'Dripicons') {
        	    	$str .=
		                'content:"\0021";
		                font-family: "dripicons-weather";';
	            } else if ($iconpack == 'Pixeden') {
        	    	$str .=
		                'content:"\E913";
		                font-family: "pe-icon-set-weather";';
	            } else if ($iconpack == 'OpenWeatherMap') {
        	    	$str .=
		                'content:"°F";';
	            } else {
        	    	$str .=
		                'content: "\e03f";
		                font-family: "Climacons-Font";
                        margin-left: 2px;';
		        }
		    }

   	    	$str .=
            '}
            </style>';
        }
        
        return $str;
}
