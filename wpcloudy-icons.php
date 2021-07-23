<?php
function weatherIcon($iconpack, $id, $day_night, $description) {
    if ($iconpack == 'WeatherIcons') {
        return '<div class="symbol"><i class="wi wi-owm-' . $day_night . '-' . $id . '" title="' . $description . '"></i></div>';
	} else if ($iconpack == 'IconVault') {
        return weatherIconvault($id, $day_night, $description);
	} else {
   		return '<div class="symbol climacon w' . $id . ' ' . $day_night  .'" title="' . $description . '"></div>';
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

function weatherIconvault($id, $day_night, $description) {
	$iconvault = array (
        //sun
        800 => '<li class="icon-' . ($day_night == "day" ? "sun" : "moon") . '"></li>',
        801 => '<li class="icon-cloud"></li><li class="icon-' . $day_night . '"></li>',
        802 => '<li class="icon-cloud"></li>',
        803 => '<li class="icon-cloud icon-fill"></li>',
        804 => '<li class="icon-cloud icon-fill"></li>',

        //rain
        500 => '<li class="icon-basecloud"></li><li class="icon-drizzle icon-' . $day_night . '"></li>',
        501 => '<li class="icon-basecloud"></li><li class="icon-drizzle icon-' . $day_night . '"></li>',
        502 => '<li class="icon-basecloud"></li><li class="icon-drizzle"></li>',
        503 => '<li class="icon-basecloud"></li><li class="icon-drizzle icon-' . $day_night . '"></li>',
        504 => '<li class="icon-basecloud"></li><li class="icon-drizzle"></li>',
        511 => '<li class="icon-basecloud"></li><li class="icon-rainy icon-' . $day_night . '"></li>',
        520 => '<li class="icon-basecloud"></li><li class="icon-rainy"></li>',
        521 => '<li class="icon-basecloud"></li><li class="icon-showers icon-' . $day_night . '"></li>',
        522 => '<li class="icon-basecloud"></li><li class="icon-showers"></li>',
        531 => '<li class="icon-basecloud"></li><li class="icon-showers"></li>',

        //drizzle
        300 => '<li class="icon-basecloud"><li class="icon-drizzle"></li>',
        301 => '<li class="icon-basecloud"><li class="icon-drizzle"></li>',
        302 => '<li class="icon-basecloud"><li class="icon-drizzle"></li>',
        310 => '<li class="icon-basecloud"><li class="icon-drizzle"></li>',
        311 => '<li class="icon-basecloud"><li class="icon-drizzle"></li>',
        312 => '<li class="icon-basecloud"><li class="icon-drizzle"></li>',
        313 => '<li class="icon-basecloud"><li class="icon-drizzle"></li>',
        314 => '<li class="icon-basecloud"><li class="icon-drizzle"></li>',
        321 => '<li class="icon-basecloud"><li class="icon-drizzle"></li>',

        //snow
        600 => '<li class="icon-basecloud"><li class="icon-snowy icon-' . $day_night . '"></li>',
        601 => '<li class="icon-basecloud"><li class="icon-snowy"></li>',
        602 => '<li class="icon-basecloud"><li class="icon-snowy icon-' . $day_night . '"></li>',
        611 => '<li class="icon-basecloud"></li><li class="icon-sleet"></li>',
        612 => '<li class="icon-basecloud"></li><li class="icon-sleet"></li>',
        613 => '<li class="icon-basecloud"></li><li class="icon-sleet"></li>',
        615 => '<li class="icon-basecloud"></li><li class="icon-sleet"></li>',
        616 => '<li class="icon-basecloud"></li><li class="icon-sleet"></li>',
        620 => '<li class="icon-basecloud"></li><li class="icon-sleet"></li>',
        621 => '<li class="icon-basecloud"><li class="icon-snowy"></li>',
        622 => '<li class="icon-basecloud"><li class="icon-snowy"></li>',

        //atmosphere
        701 => '<li class="icon-mist icon-' . $day_night . '"></li>',
        711 => '<li class="icon-mist"></li>',
        721 => '<li class="icon-mist"></li>',
        731 => '<li class="icon-mist icon-' . $day_night . '"></li>',
        741 => '<li class="icon-mist"></li>',
        751 => '<li class="icon-mist icon-' . $day_night . '"></li>',
        761 => '<li class="icon-mist icon-' . $day_night . '"></li>',
        762 => '<li class="icon-mist icon-' . $day_night . '"></li>',
        771 => '<li class="icon-basecloud"></li><li class="icon-windy"></li>',
        781 => '<li class="icon-basecloud"></li><li class="icon-windy"></li>',

        //extreme
        900 => '<li class="icon-basecloud"></li><li class="icon-windy"></li>',
        901 => '<li class="icon-basecloud"></li><li class="icon-windy"></li>',
        902 => '<li class="icon-basecloud"></li><li class="icon-windy"></li>',
        905 => '<li class="icon-basecloud"></li><li class="icon-windy"></li>',
        906 => '<li class="icon-basecloud"></li><li class="icon-hail"></li>',

        //thunderstorm
        200 => '<li class="icon-basecloud"><li class="icon-thunder"></li>',
        201 => '<li class="icon-basecloud"><li class="icon-thunder"></li>',
        202 => '<li class="icon-basecloud"><li class="icon-thunder"></li>',
        210 => '<li class="icon-basecloud"><li class="icon-thunder"></li>',
        211 => '<li class="icon-basecloud"><li class="icon-thunder"></li>',
        212 => '<li class="icon-basecloud"><li class="icon-thunder"></li>',
        221 => '<li class="icon-basecloud"><li class="icon-thunder"></li>',
        230 => '<li class="icon-basecloud"><li class="icon-thunder"></li>',
        231 => '<li class="icon-basecloud"><li class="icon-thunder"></li>',
        232 => '<li class="icon-basecloud"><li class="icon-thunder"></li>'
	);

    return '<div class="symbol" title="' . $description . '"><ul class="iconvault">'. ($iconvault[$id] ?? '') .'</ul></div>';
}

function temperatureUnitSymbol($id, $display_unit, $unit, $iconpack) {
    $str = '';
    
	    if ($display_unit) {
	    	$str .=
	    		'<style>
	            	#'.$id.'.small .now .time_temperature:after,
	              	#'.$id.' .forecast .temp_max:after,
	              	#'.$id.' .forecast .temp_min:after,
	              	#'.$id.' .hours .temperature:after,
	              	#'.$id.' .today .time_temperature_max:after,
	              	#'.$id.' .today .time_temperature_min:after,
	              	#'.$id.' .now .time_temperature:after,
	              	#'.$id.' .today .time_temperature_ave:after {';

	        if ($unit == 'metric') {
	            if ($iconpack == 'WeatherIcons') {
        	    	$str .=
		                'content:"\f03c";
		                font-family: "weathericons";';
	            } else if ($iconpack == 'IconVault') {
        	    	$str .=  //bugbug
		                'content:"C";
		                font-family: "iconvault";
		                vertical-align: super;
		                font-size: 14px !important;';
	            } else {
        	    	$str .=
		                'content:"\e03e";
		                font-family: "Climacons-Font";';
		        }
            } else {
	            if ($iconpack == 'WeatherIcons') {
        	    	$str .=
		                'content:"\f045";
		                font-family: "weathericons";';
	            } else if ($iconpack == 'IconVault') {
        	    	$str .= //bugbug
		                'content:"F";
		                font-family: "iconvault";
		                vertical-align: super;
		                font-size: 14px !important;';
	            } else {
        	    	$str .=
		                'content: "\e03f";
		                font-family: "Climacons-Font";';
		        }
		    }

   	    	$str .=
                'font-size: 24px;
                margin-left: 2px;
                vertical-align: top;
            }
            </style>';
        }
        
        return $str;
}
