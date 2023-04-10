<?php
function owmw_weatherIcon($iconpack, $id, $day_night, $description)
{
    if ($iconpack == 'WeatherIcons') {
        return '<div class="owmw-symbol"><i class="wi wi-owm-' . esc_attr($day_night) . '-' . esc_attr($id) . '" title="' . esc_attr($description) . '"></i></div>';
    } elseif ($iconpack == 'Forecast') {
        return owmw_weatherIconvault($id, $day_night, $description);
    } elseif ($iconpack == 'Dripicons') {
        return owmw_weatherDripicons($id, $day_night, $description);
    } elseif ($iconpack == 'Pixeden') {
        return owmw_weatherPixeden($id, $day_night, $description);
    } elseif ($iconpack == 'OpenWeatherMap') {
        return owmw_weatherOpenWeatherMap($id, $day_night, $description);
    } elseif ($iconpack == 'ColorAnimated') {
        return owmw_ColorAnimated($id, $day_night, $description);
    } else {
        return '<div class="owmw-symbol climacon w' . esc_attr($id) . ' ' . esc_attr($day_night)  .'" title="' . esc_attr($description) . '"></div>';
    }
}

function owmw_weatherSVG($iconpack, $id, $dayNight, $description)
{
    if ($iconpack == 'ColorAnimated') {
        $str = owmw_colorAnimatedConfig();
        $str .= owmw_ColorAnimated($id, $dayNight, $description);
        return $str;
    } else {
        return  owmw_weatherSVGclimacons($id, $dayNight, $description);
    }
}

function owmw_weatherSVGclimacons($id, $dayNight)
{
    switch ($id) {
        //sun
        case "800":
            return $dayNight == "day" ? owmw_sun() : owmw_moon();
          break;
        case "801":
            return $dayNight == "day" ? owmw_cloudSun() : owmw_cloudMoon();
          break;
        case "802":
            return owmw_cloud();
          break;
        case "803":
            return owmw_cloudFill();
          break;
        case "804":
            return owmw_cloudFill();
          break;

        //rain
        case "500":
            return $dayNight == "day" ? owmw_cloudDrizzleSun() : owmw_cloudDrizzleMoon();
          break;
        case "501":
            return $dayNight == "day" ? owmw_cloudDrizzleSun() : owmw_cloudDrizzleMoon();
          break;
        case "502":
            return owmw_cloudDrizzle();
          break;
        case "503":
            return $dayNight == "day" ? owmw_cloudDrizzleSunAlt() : owmw_cloudDrizzleMoonAlt();
          break;
        case "504":
            return owmw_cloudDrizzleAlt();
          break;
        case "511":
            return $dayNight == "day" ? owmw_cloudRainSun() : owmw_cloudRainMoon();
          break;
        case "520":
            return owmw_cloudRain();
          break;
        case "521":
            return $dayNight == "day" ? owmw_cloudSunRainAlt() : owmw_cloudMoonRainAlt();
          break;
        case "522":
            return owmw_cloudRainAlt();
          break;
        case "531":
            return owmw_cloudRainAlt();
          break;

        //drizzle
        case "300":
            return owmw_cloudRainAlt();
          break;
        case "301":
            return owmw_cloudRainAlt();
          break;
        case "302":
            return owmw_cloudRainAlt();
          break;
        case "310":
            return owmw_cloudRainAlt();
          break;
        case "311":
            return owmw_cloudRainAlt();
          break;
        case "312":
            return owmw_cloudRainAlt();
          break;
        case "313":
            return owmw_cloudRainAlt();
          break;
        case "314":
            return owmw_cloudRainAlt();
          break;
        case "321":
            return owmw_cloudRainAlt();
          break;

        //snow
        case "600":
            return $dayNight == "day" ? owmw_cloudSnowSun() : owmw_cloudSnowMoon();
          break;
        case "601":
            return owmw_cloudSnow();
          break;
        case "602":
            return $dayNight == "day" ? owmw_cloudSnowSunAlt() : owmw_cloudSnowMoonAlt();
          break;
        case "611":
            return owmw_cloudSnow();
          break;
        case "612":
            return owmw_cloudSnow();
          break;
        case "613":
            return owmw_cloudSnow();
          break;
        case "615":
            return owmw_cloudSnow();
          break;
        case "616":
            return owmw_cloudSnow();
          break;
        case "620":
            return owmw_cloudSnow();
          break;
        case "621":
            return owmw_cloudSnowAlt();
          break;
        case "622":
            return owmw_cloudSnowAlt();
          break;

        //atmosphere
        case "701":
            return $dayNight == "day" ? owmw_cloudFogSunAlt() : owmw_cloudFogMoonAlt();
          break;
        case "711":
            return owmw_cloudFogAlt();
          break;
        case "721":
            return owmw_cloudFogAlt();
          break;
        case "731":
            return $dayNight == "day" ? owmw_cloudFogSun() : owmw_cloudFogMoon();
          break;
        case "741":
            return owmw_cloudFog();
          break;
        case "751":
            return $dayNight == "day" ? owmw_cloudFogSun() : owmw_cloudFogMoon();
          break;
        case "761":
            return $dayNight == "day" ? owmw_cloudFogSun() : owmw_cloudFogMoon();
          break;
        case "762":
            return $dayNight == "day" ? owmw_cloudFogSun() : owmw_cloudFogMoon();
          break;
        case "771":
            return owmw_tornado();
          break;
        case "781":
            return owmw_tornado();
          break;

        //extreme
        case "900":
            return owmw_tornado();
          break;
        case "901":
            return owmw_wind();
          break;
        case "902":
            return owmw_wind();
          break;
        case "905":
            return owmw_wind();
          break;
        case "906":
            return owmw_cloudHailAlt();
          break;

        //thunderstorm
        case "200":
            return owmw_cloudLightning();
          break;

        case "201":
            return owmw_cloudLightning();
          break;

        case "202":
            return owmw_cloudLightning();
          break;

        case "210":
            return owmw_cloudLightning();
          break;

        case "211":
            return owmw_cloudLightning();
          break;

        case "212":
            return owmw_cloudLightning();
          break;

        case "221":
            return owmw_cloudLightning();
          break;

        case "230":
            return owmw_cloudLightning();
          break;

        case "231":
            return owmw_cloudLightning();
          break;

        case "232":
            return owmw_cloudLightning();
    }

    return '';
}

function owmw_weatherOpenWeatherMap($id, $day_night, $description)
{
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
        return '<img class="owmw-symbol owm-icon img-fluid" src="https://openweathermap.org/img/wn/' . esc_attr($icon) . esc_attr($day_night == 'day' ? 'd' : 'n') . '.png" title="'.esc_attr($description).'" alt="'.esc_attr($description).'">';
    }
    
    return '';
}

function owmw_weatherIconvault($id, $day_night, $description)
{
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

    return '<div class="owmw-symbol iconvault" title="' . esc_attr($description) . '"><ul class="list-unstyled">'. ($iconvault[$id] ?? '') .'</ul></div>';
}

function owmw_weatherDripicons($id, $day_night, $description)
{
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

    return '<div class="owmw-symbol diw '. esc_attr($dripicon[$id] ?? '') .'" title="' . esc_attr($description) . '"></div>';
}

function owmw_weatherPixeden($id, $day_night, $description)
{
    $icon = null;

    switch ($id) {
        case 800:
            $icon = $day_night == "day" ? "sun-1" : "moon-1";
            break;
        case 801:
            $icon = $day_night == "day" ? "partly-cloudy-1" : "partly-cloudy-3";
            break;
        case 802:
            $icon = $day_night == "day" ? "partly-cloudy-1" : "partly-cloudy-3";
            break;
        case 803:
            $icon = $day_night == "day" ? "mostly-cloudy-2" : "mostly-cloudy-2";
            break;
        case 804:
            $icon = $day_night == "day" ? "mostly-cloudy-1" : "mostly-cloudy-1";
            break;
        case 500:
            $icon = $day_night == "day" ? "rain-day" : "rain-night";
            break;
        case 501:
            $icon = $day_night == "day" ? "rain-day" : "rain-night";
            break;
        case 502:
            $icon = $day_night == "day" ? "heavy-rain-2" : "heavy-rain-2";
            break;
        case 503:
            $icon = $day_night == "day" ? "heavy-rain-1-f" : "heavy-rain-1-f";
            break;
        case 504:
            $icon = $day_night == "day" ? "heavy-rain-1-f" : "heavy-rain-1-f";
            break;
        case 511:
            $icon = $day_night == "day" ? "rain-and-snow" : "rain-and-snow";
            break;
        case 520:
            $icon = $day_night == "day" ? "rain-day" : "rain-night";
            break;
        case 521:
            $icon = $day_night == "day" ? "rain-day" : "rain-night";
            break;
        case 522:
            $icon = $day_night == "day" ? "heavy-rain-2" : "heavy-rain-1";
            break;
        case 531:
            $icon = $day_night == "day" ? "heavy-rain-1-f" : "heavy-rain-1-f";
            break;
        case 300:
            $icon = $day_night == "day" ? "drizzle" : "drizzle";
            break;
        case 301:
            $icon = $day_night == "day" ? "drizzle" : "drizzle";
            break;
        case 302:
            $icon = $day_night == "day" ? "drizzle-f" : "drizzle-f";
            break;
        case 310:
            $icon = $day_night == "day" ? "rain-day" : "rain-night";
            break;
        case 311:
            $icon = $day_night == "day" ? "rain-day" : "rain-night";
            break;
        case 312:
            $icon = $day_night == "day" ? "heavy-rain-2-f" : "heavy-rain-2-f";
            break;
        case 313:
            $icon = $day_night == "day" ? "drizzle" : "drizzle";
            break;
        case 314:
            $icon = $day_night == "day" ? "drizzle-f" : "drizzle-f";
            break;
        case 321:
            $icon = $day_night == "day" ? "drizzle" : "drizzle";
            break;
        case 600:
            $icon = $day_night == "day" ? "snow-day-1" : "snow-night-1";
            break;
        case 601:
            $icon = $day_night == "day" ? "snow-day-2" : "snow-night-2";
            break;
        case 602:
            $icon = $day_night == "day" ? "snow-day-3" : "snow-night-3";
            break;
        case 611:
            $icon = $day_night == "day" ? "snow-day-1" : "snow-night-1";
            break;
        case 612:
            $icon = $day_night == "day" ? "snow-day-2" : "snow-night-2";
            break;
        case 613:
            $icon = $day_night == "day" ? "snow-day-3" : "snow-night-3";
            break;
        case 615:
            $icon = $day_night == "day" ? "rain-and-snow" : "rain-and-snow";
            break;
        case 616:
            $icon = $day_night == "day" ? "rain-and-snow-f" : "rain-and-snow-f";
            break;
        case 620:
            $icon = $day_night == "day" ? "snow-day-1" : "snow-night-1";
            break;
        case 621:
            $icon = $day_night == "day" ? "snow-day-2" : "snow-night-2";
            break;
        case 622:
            $icon = $day_night == "day" ? "snow-day-3" : "snow-night-3";
            break;
        case 701:
            $icon = $day_night == "day" ? "mist" : "mist";
            break;
        case 711:
            $icon = $day_night == "day" ? "fog-2" : "fog-2";
            break;
        case 721:
            $icon = $day_night == "day" ? "fog-2" : "fog-2";
            break;
        case 731:
            $icon = $day_night == "day" ? "fog-2" : "fog-2";
            break;
        case 741:
            $icon = $day_night == "day" ? "fog-3" : "fog-4";
            break;
        case 751:
            $icon = $day_night == "day" ? "fog-2" : "fog-2";
            break;
        case 761:
            $icon = $day_night == "day" ? "fog-2" : "fog-2";
            break;
        case 762:
            $icon = $day_night == "day" ? "fog-2" : "fog-2";
            break;
        case 771:
            $icon = $day_night == "day" ? "fog-1" : "fog-1";
            break;
        case 781:
            $icon = $day_night == "day" ? "tornado-1" : "tornado-1";
            break;
        case 900:
            $icon = $day_night == "day" ? "tornado-2" : "tornado-2";
            break;
        case 901:
            $icon = $day_night == "day" ? "wind" : "wind";
            break;
        case 902:
            $icon = $day_night == "day" ? "wind" : "wind";
            break;
        case 903:
            $icon = $day_night == "day" ? "thermometer-5" : "thermometer-5";
            break;
        case 904:
            $icon = $day_night == "day" ? "thermometer-1" : "thermometer-1";
            break;
        case 905:
            $icon = $day_night == "day" ? "wind" : "wind";
            break;
        case 906:
            $icon = $day_night == "day" ? "heavy-hail-day" : "heavy-hail-night";
            break;
        case 200:
            $icon = $day_night == "day" ? "mix-rainfall-1" : "mix-rainfall-1";
            break;
        case 201:
            $icon = $day_night == "day" ? "mix-rainfall-2" : "mix-rainfall-2";
            break;
        case 202:
            $icon = $day_night == "day" ? "mix-rainfall-2-f" : "mix-rainfall-1-f";
            break;
        case 210:
            $icon = $day_night == "day" ? "thunderstorm-day-1" : "thunderstorm-night-1";
            break;
        case 211:
            $icon = $day_night == "day" ? "thunderstorm" : "thunderstorm";
            break;
        case 212:
            $icon = $day_night == "day" ? "severe-thunderstorm" : "severe-thunderstorm";
            break;
        case 221:
            $icon = $day_night == "day" ? "severe-thunderstorm-f" : "severe-thunderstorm-f";
            break;
        case 230:
            $icon = $day_night == "day" ? "mix-rainfall-1" : "mix-rainfall-1";
            break;
        case 231:
            $icon = $day_night == "day" ? "mix-rainfall-2" : "mix-rainfall-2";
            break;
        case 232:
            $icon = $day_night == "day" ? "mix-rainfall-2-f" : "mix-rainfall-1-f";
            break;
    }

    return '<span class="owmw-symbol pe pe-is-w-'. esc_attr($icon ?? '') .'" title="' . esc_attr($description) . '"></span>';
}

function owmw_ColorAnimated($id, $dayNight, $description)
{
    $icon = null;
    switch ($id) {
        case "200": /* Thunderstorm thunderstorm with light rain */
        case "201": /* Thunderstorm thunderstorm with rain */
        case "202": /* Thunderstorm thunderstorm with heavy rain */
        case "210": /* Thunderstorm light thunderstorm */
        case "211": /* Thunderstorm thunderstorm */
        case "212": /* Thunderstorm heavy thunderstorm */
        case "221": /* Thunderstorm ragged thunderstorm */
        case "230": /* Thunderstorm thunderstorm with light drizzle */
        case "231": /* Thunderstorm thunderstorm with drizzle */
        case "232": /* Thunderstorm thunderstorm with heavy drizzle */
            $icon = owmw_colorAnimated_Thunderstorm();
            break;
        case "300": /* Drizzle light intensity drizzle */
        case "301": /* Drizzle drizzle */
        case "310": /* Drizzle light intensity drizzle rain */
            $icon = $dayNight == "day" ? owmw_colorAnimated_Patchy_Drizzle_Day() : owmw_colorAnimated_Patchy_Drizzle_Night();
            break;
        case "302": /* Drizzle heavy intensity drizzle */
        case "311": /* Drizzle drizzle rain */
        case "312": /* Drizzle heavy intensity drizzle rain */
        case "313": /* Drizzle shower rain and drizzle */
        case "314": /* Drizzle heavy shower rain and drizzle */
        case "321": /* Drizzle shower drizzle */
            $icon = owmw_colorAnimated_Drizzle();
            break;
        case "500": /* Rain light rain */
        case "520": /* Rain light intensity shower rain */
            $icon = $dayNight == "day" ? owmw_colorAnimated_Patchy_Rain_Day() : owmw_colorAnimated_Patchy_Rain_Night();
            break;
        case "501": /* Rain moderate rain */
        case "502": /* Rain heavy intensity rain */
        case "503": /* Rain very heavy rain */
        case "504": /* Rain extreme rain */
        case "521": /* Rain shower rain */
        case "522": /* Rain heavy intensity shower rain */
        case "531": /* Rain ragged shower rain */
            $icon = owmw_colorAnimated_Rain();
            break;
        case "511": /* Rain freezing rain */
            $icon = owmw_colorAnimated_Hail();
            break;
        case "600": /* Snow light snow */
        case "615": /* Snow Light rain and snow */
        case "620": /* Snow Light shower snow */
            $icon = $dayNight == "day" ? owmw_colorAnimated_Patchy_Snow_Day() : owmw_colorAnimated_Patchy_Snow_Night();
            break;
        case "601": /* Snow Snow */
        case "602": /* Snow Heavy snow */
        case "616": /* Snow Rain and snow */
        case "621": /* Snow Shower snow */
        case "622": /* Snow Heavy shower snow */
            $icon = owmw_colorAnimated_Snow();
            break;
        case "612": /* Snow Light shower sleet */
            $icon = $dayNight == "day" ? owmw_colorAnimated_Patchy_Sleet_Day() : owmw_colorAnimated_Patchy_Sleet_Night();
            break;
        case "611": /* Snow Sleet */
        case "613": /* Snow Shower sleet */
            $icon = owmw_colorAnimated_Sleet();
            break;
        case "701": /* Mist mist */
        case "711": /* Smoke Smoke */
        case "721": /* Haze Haze */
        case "731": /* Dust sand/ dust whirls */
        case "741": /* Fog fog */
        case "751": /* Sand sand */
        case "761": /* Dust dust */
        case "762": /* Ash volcanic ash */
        case "771": /* Squall squalls */
            $icon = owmw_colorAnimated_Mist_Cloud();
            break;
        case "781": /* Tornado tornado */
            $icon = owmw_colorAnimated_Tornado();
            break;
        case "800": /* Clear clear sky */
            $icon = $dayNight == "day" ? owmw_colorAnimated_Sun() : owmw_colorAnimated_Moon();
            break;
        case "801": /* Clouds few clouds: 11-25% */
        case "802": /* Clouds scattered clouds: 25-50% */
            $icon = $dayNight == "day" ? owmw_colorAnimated_Partly_Cloudy_Day() : owmw_colorAnimated_Partly_Cloudy_Night();
            break;
        case "803": /* Clouds broken clouds: 51-84% */
            $icon = owmw_colorAnimated_Few_Clouds();
            break;
        case "804": /* Clouds overcast clouds: 85-100% */
            $icon = owmw_colorAnimated_Dark_Clouds();
            break;
    }
    
    return '<span class="owmw-symbol color-animated-icon" title="' . esc_attr($description) . '">'.$icon.'</span>';
}

function owmw_temperatureUnitSymbol($id, $display_unit, $unit, $iconpack, $extension = '')
{
    $id = sanitize_html_class($id);
    
    $str = '';
    if ($display_unit == 'yes') {
        $str .=
               '#'.esc_attr($id).' .owmw-now .owmw-main-temperature'.esc_attr($extension).':after,
	            	#'.esc_attr($id).' .owmw-now .owmw-main-feels-like'.esc_attr($extension).':after,
	            	#'.esc_attr($id).' .owmw-infos .owmw-temperature'.esc_attr($extension).':after,
	            	#'.esc_attr($id).' .owmw-infos-text .owmw-temperature'.esc_attr($extension).':after,
	              	#'.esc_attr($id).' .owmw-forecast .owmw-temp-max'.esc_attr($extension).':after,
	              	#'.esc_attr($id).' .owmw-forecast .owmw-temp-min'.esc_attr($extension).':after,
	              	#'.esc_attr($id).' .owmw-hours .owmw-temperature'.esc_attr($extension).':after,
	              	#'.esc_attr($id).' .owmw-today .owmw-main-temperature-max'.esc_attr($extension).':after,
	              	#'.esc_attr($id).' .owmw-today .owmw-main-temperature-min'.esc_attr($extension).':after,
	              	#'.esc_attr($id).' .owmw-table .owmw-table-temperature'.esc_attr($extension).':after {';

        if ($unit == 'metric') {
            if ($iconpack == 'WeatherIcons') {
                $str .=
                    'content:"\f03c";
		                font-family: "WeatherIcons";
                        margin-left: 2px;';
            } elseif ($iconpack == 'Forecast') {
                $str .=
                    'content:"C";
		                font-family: "iconvault";';
            } elseif ($iconpack == 'Dripicons') {
                $str .=
                    'content:"\0039";
		                font-family: "dripicons-weather";';
            } elseif ($iconpack == 'Pixeden') {
                $str .=
                    'content:"\0039";
		                font-family: "pe-icon-set-weather";';
            } elseif ($iconpack == 'OpenWeatherMap') {
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
		                font-family: "WeatherIcons";
                        margin-left: 2px;';
            } elseif ($iconpack == 'Forecast') {
                $str .=
                    'content:"F";
		                font-family: "iconvault";';
            } elseif ($iconpack == 'Dripicons') {
                $str .=
                    'content:"\0021";
		                font-family: "dripicons-weather";';
            } elseif ($iconpack == 'Pixeden') {
                $str .=
                    'content:"\E913";
		                font-family: "pe-icon-set-weather";';
            } elseif ($iconpack == 'OpenWeatherMap') {
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
        '}';
    }
        
        return $str;
}

function owmw_wind_direction_icon($degrees, $color, $direction)
{
    if (!empty($direction) && $direction == "from") {
        $rotation = 0;
    } else {
        $rotation = 180;
    }
    return
    '<svg viewBox="0 0 30 30" fill="'.(!empty($color) ? esc_attr($color) : 'currentColor').'">
<title>'.esc_attr__('Wind', 'owm-weather').'</title>
<path transform="rotate('.esc_attr(($degrees ?? 0) - $rotation).')" transform-origin="center" d="M3.74,14.5c0-2.04,0.51-3.93,1.52-5.66s2.38-3.1,4.11-4.11s3.61-1.51,5.64-1.51c1.52,0,2.98,0.3,4.37,0.89
        s2.58,1.4,3.59,2.4s1.81,2.2,2.4,3.6s0.89,2.85,0.89,4.39c0,1.52-0.3,2.98-0.89,4.37s-1.4,2.59-2.4,3.59s-2.2,1.8-3.59,2.39
        s-2.84,0.89-4.37,0.89c-1.53,0-3-0.3-4.39-0.89s-2.59-1.4-3.6-2.4s-1.8-2.2-2.4-3.58S3.74,16.03,3.74,14.5z M6.22,14.5
        c0,2.37,0.86,4.43,2.59,6.18c1.73,1.73,3.79,2.59,6.2,2.59c1.58,0,3.05-0.39,4.39-1.18s2.42-1.85,3.21-3.2s1.19-2.81,1.19-4.39
        s-0.4-3.05-1.19-4.4s-1.86-2.42-3.21-3.21s-2.81-1.18-4.39-1.18s-3.05,0.39-4.39,1.18S8.2,8.75,7.4,10.1S6.22,12.92,6.22,14.5z
         M11.11,20.35l3.75-13.11c0.01-0.1,0.06-0.15,0.15-0.15s0.14,0.05,0.15,0.15l3.74,13.11c0.04,0.11,0.03,0.19-0.02,0.25
        s-0.13,0.06-0.24,0l-3.47-1.3c-0.1-0.04-0.2-0.04-0.29,0l-3.5,1.3c-0.1,0.06-0.17,0.06-0.21,0S11.09,20.45,11.11,20.35z"/>
</svg>';
}

function owmw_chart_wind_direction_icon($degrees, $color, $direction)
{
    if (!empty($direction) && $direction == "from") {
            $rotation = 0;
    } else {
            $rotation = 180;
    }
    return
    "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30' fill='".(!empty($color) ? esc_attr($color) : "currentColor")."' width='30' height='30'>
<title>".esc_attr__("Wind", "owm-weather")."</title>
<path transform='rotate(".esc_attr(($degrees ?? 0) - $rotation).")' transform-origin='center' d='M11.11,20.35l3.75-13.11c0.01-0.1,0.06-0.15,0.15-0.15s0.14,0.05,0.15,0.15l3.74,13.11c0.04,0.11,0.03,0.19-0.02,0.25
        s-0.13,0.06-0.24,0l-3.47-1.3c-0.1-0.04-0.2-0.04-0.29,0l-3.5,1.3c-0.1,0.06-0.17,0.06-0.21,0S11.09,20.45,11.11,20.35z'/>
</svg>";
}

function owmw_humidity_icon($color)
{
    return
    '<svg
	 viewBox="0 0 30 30" fill="'.(!empty($color) ? esc_attr($color) : 'currentColor').'">
<title>'.esc_attr__('Humidity', 'owm-weather').'</title>
<path d="M7.56,17.19c0-0.88,0.24-1.89,0.72-3.03s1.1-2.25,1.86-3.31c1.56-2.06,2.92-3.62,4.06-4.67l0.75-0.72
	c0.25,0.26,0.53,0.5,0.83,0.72c0.41,0.42,1.04,1.11,1.88,2.09s1.57,1.85,2.17,2.65c0.71,1.01,1.32,2.1,1.81,3.25
	s0.74,2.16,0.74,3.03c0,1-0.19,1.95-0.58,2.86c-0.39,0.91-0.91,1.7-1.57,2.36c-0.66,0.66-1.45,1.19-2.37,1.58
	c-0.92,0.39-1.89,0.59-2.91,0.59c-1,0-1.95-0.19-2.86-0.57c-0.91-0.38-1.7-0.89-2.36-1.55c-0.66-0.65-1.19-1.44-1.58-2.35
	S7.56,18.23,7.56,17.19z M9.82,14.26c0,0.83,0.17,1.49,0.52,1.99c0.35,0.49,0.88,0.74,1.59,0.74c0.72,0,1.25-0.25,1.61-0.74
	c0.35-0.49,0.53-1.15,0.54-1.99c-0.01-0.84-0.19-1.5-0.54-2c-0.35-0.49-0.89-0.74-1.61-0.74c-0.71,0-1.24,0.25-1.59,0.74
	C9.99,12.76,9.82,13.42,9.82,14.26z M11.39,14.26c0-0.15,0-0.27,0-0.35s0.01-0.19,0.02-0.33c0.01-0.14,0.02-0.25,0.05-0.32
	s0.05-0.16,0.09-0.24c0.04-0.08,0.09-0.15,0.15-0.18c0.07-0.04,0.14-0.06,0.23-0.06c0.14,0,0.25,0.04,0.33,0.12s0.14,0.21,0.17,0.38
	c0.03,0.18,0.05,0.32,0.06,0.45s0.01,0.3,0.01,0.52c0,0.23,0,0.4-0.01,0.52c-0.01,0.12-0.03,0.27-0.06,0.45
	c-0.03,0.17-0.09,0.3-0.17,0.38s-0.19,0.12-0.33,0.12c-0.09,0-0.16-0.02-0.23-0.06c-0.07-0.04-0.12-0.1-0.15-0.18
	c-0.04-0.08-0.07-0.17-0.09-0.24c-0.02-0.08-0.04-0.19-0.05-0.32c-0.01-0.14-0.02-0.25-0.02-0.32S11.39,14.41,11.39,14.26z
	 M11.98,22.01h1.32l4.99-10.74h-1.35L11.98,22.01z M16.28,19.02c0.01,0.84,0.2,1.5,0.55,2c0.35,0.49,0.89,0.74,1.6,0.74
	c0.72,0,1.25-0.25,1.6-0.74c0.35-0.49,0.52-1.16,0.53-2c-0.01-0.84-0.18-1.5-0.53-1.99c-0.35-0.49-0.88-0.74-1.6-0.74
	c-0.71,0-1.25,0.25-1.6,0.74C16.47,17.52,16.29,18.18,16.28,19.02z M17.85,19.02c0-0.23,0-0.4,0.01-0.52
	c0.01-0.12,0.03-0.27,0.06-0.45s0.09-0.3,0.17-0.38s0.19-0.12,0.33-0.12c0.09,0,0.17,0.02,0.24,0.06c0.07,0.04,0.12,0.1,0.16,0.19
	c0.04,0.09,0.07,0.17,0.1,0.24s0.04,0.18,0.05,0.32l0.01,0.32l0,0.34c0,0.16,0,0.28,0,0.35l-0.01,0.32l-0.05,0.32l-0.1,0.24
	l-0.16,0.19l-0.24,0.06c-0.14,0-0.25-0.04-0.33-0.12s-0.14-0.21-0.17-0.38c-0.03-0.18-0.05-0.33-0.06-0.45S17.85,19.25,17.85,19.02z
	"/>
</svg>';
}

function owmw_pressure_icon($color)
{
    return
    '<svg
	 viewBox="0 0 30 30" fill="'.(!empty($color) ? esc_attr($color) : 'currentColor').'">
<title>'.esc_attr__('Pressure', 'owm-weather').'</title>
<path d="M7.69,13.2c0-0.99,0.19-1.94,0.58-2.85c0.39-0.91,0.91-1.68,1.57-2.33s1.44-1.17,2.34-1.56c0.9-0.39,1.85-0.58,2.84-0.58
	c0.99,0,1.94,0.19,2.85,0.58c0.9,0.39,1.68,0.91,2.33,1.56c0.65,0.65,1.17,1.43,1.56,2.33s0.58,1.85,0.58,2.85
	c0,1.62-0.48,3.06-1.44,4.34c-0.96,1.27-2.2,2.14-3.71,2.61v3.29h-4.24v-3.25c-1.54-0.45-2.81-1.32-3.79-2.61S7.69,14.83,7.69,13.2z
	 M9.3,13.2c0,1.55,0.56,2.88,1.69,3.99c1.11,1.12,2.45,1.68,4.02,1.68c1.03,0,1.99-0.25,2.86-0.76c0.88-0.51,1.57-1.2,2.09-2.07
	c0.51-0.87,0.77-1.82,0.77-2.85c0-0.77-0.15-1.5-0.45-2.21s-0.71-1.31-1.22-1.82c-0.51-0.51-1.12-0.92-1.83-1.22
	c-0.71-0.3-1.44-0.45-2.21-0.45c-0.77,0-1.5,0.15-2.21,0.45s-1.31,0.71-1.82,1.22c-0.51,0.51-0.92,1.12-1.22,1.82
	C9.45,11.7,9.3,12.43,9.3,13.2z M9.88,13.56v-0.72h2.17v0.72H9.88z M10.97,10.02l0.52-0.52l1.52,1.52l-0.52,0.53L10.97,10.02z
	 M13.5,14.95c0-0.42,0.15-0.78,0.44-1.09c0.29-0.31,0.65-0.47,1.06-0.48l2.73-4.49l0.66,0.35l-2.02,4.83
	c0.18,0.25,0.26,0.54,0.26,0.88c0,0.44-0.15,0.81-0.46,1.11c-0.31,0.3-0.68,0.45-1.12,0.45c-0.43,0-0.8-0.15-1.1-0.45
	C13.65,15.76,13.5,15.39,13.5,14.95z M14.81,10.28V8.12h0.69v2.17H14.81z M17.75,13.55v-0.74h2.17v0.74H17.75z"/>
</svg>';
}

function owmw_cloudiness_icon($color)
{
    return
    '<svg
	 viewBox="0 0 30 30" fill="'.(!empty($color) ? esc_attr($color) : 'currentColor').'">
<title>'.esc_attr__('Cloudiness', 'owm-weather').'</title>
<path d="M3.89,17.6c0-0.99,0.31-1.88,0.93-2.65s1.41-1.27,2.38-1.49c0.26-1.17,0.85-2.14,1.78-2.88c0.93-0.75,2-1.12,3.22-1.12
	c1.18,0,2.24,0.36,3.16,1.09c0.93,0.73,1.53,1.66,1.8,2.8h0.27c1.18,0,2.18,0.41,3.01,1.24s1.25,1.83,1.25,3
	c0,1.18-0.42,2.18-1.25,3.01s-1.83,1.25-3.01,1.25H8.16c-0.58,0-1.13-0.11-1.65-0.34S5.52,21,5.14,20.62
	c-0.38-0.38-0.68-0.84-0.91-1.36S3.89,18.17,3.89,17.6z M5.34,17.6c0,0.76,0.28,1.42,0.82,1.96s1.21,0.82,1.99,0.82h9.28
	c0.77,0,1.44-0.27,1.99-0.82c0.55-0.55,0.83-1.2,0.83-1.96c0-0.76-0.27-1.42-0.83-1.96c-0.55-0.54-1.21-0.82-1.99-0.82h-1.39
	c-0.1,0-0.15-0.05-0.15-0.15l-0.07-0.49c-0.1-0.94-0.5-1.73-1.19-2.35s-1.51-0.93-2.45-0.93c-0.94,0-1.76,0.31-2.46,0.94
	c-0.7,0.62-1.09,1.41-1.18,2.34l-0.07,0.42c0,0.1-0.05,0.15-0.16,0.15l-0.45,0.07c-0.72,0.06-1.32,0.36-1.81,0.89
	C5.59,16.24,5.34,16.87,5.34,17.6z M14.19,8.88c-0.1,0.09-0.08,0.16,0.07,0.21c0.43,0.19,0.79,0.37,1.08,0.55
	c0.11,0.03,0.19,0.02,0.22-0.03c0.61-0.57,1.31-0.86,2.12-0.86c0.81,0,1.5,0.27,2.1,0.81c0.59,0.54,0.92,1.21,0.99,2l0.09,0.64h1.42
	c0.65,0,1.21,0.23,1.68,0.7c0.47,0.47,0.7,1.02,0.7,1.66c0,0.6-0.21,1.12-0.62,1.57s-0.92,0.7-1.53,0.77c-0.1,0-0.15,0.05-0.15,0.16
	v1.13c0,0.11,0.05,0.16,0.15,0.16c1.01-0.06,1.86-0.46,2.55-1.19s1.04-1.6,1.04-2.6c0-1.06-0.37-1.96-1.12-2.7
	c-0.75-0.75-1.65-1.12-2.7-1.12h-0.15c-0.26-1-0.81-1.82-1.65-2.47c-0.83-0.65-1.77-0.97-2.8-0.97C16.28,7.29,15.11,7.82,14.19,8.88
	z"/>
</svg>';
}

function owmw_precipitation_icon($color)
{
    return
    '<svg
	 viewBox="0 0 30 30" fill="'.(!empty($color) ? esc_attr($color) : 'currentColor').'">
<title>'.esc_attr__('Precipitation', 'owm-weather').'</title>
<path d="M11.01,12.23c0-0.26,0.13-0.59,0.38-1.01c0.25-0.42,0.5-0.77,0.73-1.04c0.06-0.07,0.14-0.17,0.23-0.28s0.15-0.17,0.16-0.18
	l0.37,0.43c0.28,0.31,0.53,0.66,0.76,1.07c0.23,0.41,0.35,0.74,0.35,1.01c0,0.41-0.14,0.77-0.43,1.06
	c-0.28,0.29-0.63,0.44-1.05,0.44c-0.41,0-0.77-0.15-1.06-0.44C11.16,12.99,11.01,12.64,11.01,12.23z M14.13,16.38
	c0-0.29,0.08-0.62,0.24-1.01c0.16-0.38,0.36-0.74,0.6-1.06c0.46-0.61,0.89-1.12,1.31-1.53c0.04-0.03,0.13-0.11,0.26-0.24l0.25,0.24
	c0.39,0.37,0.83,0.88,1.32,1.52c0.26,0.34,0.46,0.7,0.62,1.08s0.24,0.71,0.24,1c0,0.69-0.23,1.26-0.7,1.73
	c-0.47,0.47-1.05,0.7-1.73,0.7c-0.68,0-1.25-0.24-1.72-0.71S14.13,17.05,14.13,16.38z M15.65,9.48c0-0.43,0.33-1,1-1.7l0.25,0.28
	c0.19,0.22,0.36,0.46,0.51,0.74c0.15,0.27,0.23,0.5,0.23,0.68c0,0.28-0.1,0.5-0.29,0.69c-0.19,0.18-0.42,0.28-0.7,0.28
	c-0.29,0-0.53-0.09-0.72-0.28C15.75,9.98,15.65,9.75,15.65,9.48z"/>
</svg>';
}

function owmw_visibility_icon($color)
{
    return
    '<svg
	 viewBox="0 0 30 30" fill="'.(!empty($color) ? esc_attr($color) : 'currentColor').'">
<title>'.esc_attr__('Visibility', 'owm-weather').'</title>
<path d="M4.93,20.97c0-0.26,0.09-0.47,0.28-0.62c0.14-0.16,0.35-0.23,0.63-0.23h18.34c0.25,0,0.46,0.08,0.64,0.24
	c0.18,0.16,0.26,0.37,0.26,0.61c0,0.24-0.09,0.45-0.27,0.62s-0.39,0.27-0.63,0.27H5.84c-0.25,0-0.46-0.09-0.64-0.27
	C5.02,21.42,4.93,21.21,4.93,20.97z M6.9,12.68c0-0.26,0.08-0.47,0.23-0.63c0.17-0.18,0.38-0.26,0.65-0.26
	c0.23,0,0.43,0.09,0.6,0.26l1.5,1.5c0.18,0.18,0.27,0.39,0.27,0.63c0,0.23-0.09,0.44-0.27,0.62c-0.15,0.18-0.35,0.27-0.6,0.27
	s-0.47-0.09-0.64-0.27l-1.5-1.5C6.98,13.15,6.9,12.95,6.9,12.68z M9.83,18.27c-0.04,0.16,0.01,0.23,0.15,0.23h1.49
	c0.07,0,0.14-0.06,0.22-0.17c0.3-0.64,0.74-1.14,1.33-1.52s1.24-0.56,1.96-0.56c0.73,0,1.39,0.19,1.99,0.56s1.05,0.88,1.35,1.52
	c0.08,0.11,0.16,0.17,0.23,0.17h1.48c0.13,0,0.18-0.08,0.15-0.23c-0.34-1.13-0.99-2.05-1.95-2.76c-0.96-0.71-2.04-1.06-3.25-1.06
	c-1.2,0-2.28,0.35-3.23,1.06C10.82,16.22,10.17,17.14,9.83,18.27z M14.14,11.81V9.68c0-0.25,0.08-0.46,0.24-0.64
	c0.16-0.18,0.37-0.26,0.61-0.26c0.25,0,0.46,0.09,0.63,0.26c0.17,0.17,0.25,0.39,0.25,0.64v2.14c0,0.26-0.08,0.47-0.25,0.64
	c-0.17,0.17-0.38,0.25-0.63,0.25c-0.24,0-0.45-0.09-0.61-0.26S14.14,12.06,14.14,11.81z M19.86,14.18c0-0.24,0.08-0.45,0.25-0.63
	l1.54-1.5c0.16-0.18,0.36-0.26,0.62-0.26c0.24,0,0.44,0.08,0.6,0.25s0.23,0.38,0.23,0.64c0,0.26-0.08,0.47-0.23,0.62l-1.48,1.5
	c-0.17,0.17-0.36,0.26-0.56,0.28c-0.23,0.02-0.44-0.06-0.65-0.24S19.86,14.43,19.86,14.18z"/>
</svg>';
}

function owmw_dew_point_icon($color)
{
    return
    '<svg
	 viewBox="0 0 30 30" fill="'.(!empty($color) ? esc_attr($color) : 'currentColor').'">
<title>'.esc_attr__('Dew Point', 'owm-weather').'</title>
<path d="M9.81,15.25c0,0.92,0.23,1.78,0.7,2.57s1.1,1.43,1.9,1.9c0.8,0.47,1.66,0.71,2.59,0.71c0.93,0,1.8-0.24,2.61-0.71
	c0.81-0.47,1.45-1.11,1.92-1.9c0.47-0.8,0.71-1.65,0.71-2.57c0-0.6-0.17-1.31-0.52-2.14c-0.35-0.83-0.77-1.6-1.26-2.3
	c-0.44-0.57-0.96-1.2-1.56-1.88c-0.6-0.68-1.65-1.73-1.89-1.97l-1.28,1.29c-0.62,0.6-1.22,1.29-1.79,2.08
	c-0.57,0.79-1.07,1.64-1.49,2.55C10.01,13.79,9.81,14.58,9.81,15.25z"/>
</svg>';
}

function owmw_uv_index_icon($color)
{
    return
    '<svg
	 viewBox="0 0 30 30" fill="'.(!empty($color) ? esc_attr($color) : 'currentColor').'">
<title>'.esc_attr__('UV Index', 'owm-weather').'</title>
<path d="M4.37,14.62c0-0.24,0.08-0.45,0.25-0.62c0.17-0.16,0.38-0.24,0.6-0.24h2.04c0.23,0,0.42,0.08,0.58,0.25
	c0.15,0.17,0.23,0.37,0.23,0.61S8,15.06,7.85,15.23c-0.15,0.17-0.35,0.25-0.58,0.25H5.23c-0.23,0-0.43-0.08-0.6-0.25
	C4.46,15.06,4.37,14.86,4.37,14.62z M7.23,21.55c0-0.23,0.08-0.43,0.23-0.61l1.47-1.43c0.15-0.16,0.35-0.23,0.59-0.23
	c0.24,0,0.44,0.08,0.6,0.23s0.24,0.34,0.24,0.57c0,0.24-0.08,0.46-0.24,0.64L8.7,22.14c-0.41,0.32-0.82,0.32-1.23,0
	C7.31,21.98,7.23,21.78,7.23,21.55z M7.23,7.71c0-0.23,0.08-0.43,0.23-0.61C7.66,6.93,7.87,6.85,8.1,6.85
	c0.22,0,0.42,0.08,0.59,0.24l1.43,1.47c0.16,0.15,0.24,0.35,0.24,0.59c0,0.24-0.08,0.44-0.24,0.6s-0.36,0.24-0.6,0.24
	c-0.24,0-0.44-0.08-0.59-0.24L7.47,8.32C7.31,8.16,7.23,7.95,7.23,7.71z M9.78,14.62c0-0.93,0.23-1.8,0.7-2.6s1.1-1.44,1.91-1.91
	s1.67-0.7,2.6-0.7c0.7,0,1.37,0.14,2.02,0.42c0.64,0.28,1.2,0.65,1.66,1.12c0.47,0.47,0.84,1.02,1.11,1.66
	c0.27,0.64,0.41,1.32,0.41,2.02c0,0.94-0.23,1.81-0.7,2.61c-0.47,0.8-1.1,1.43-1.9,1.9c-0.8,0.47-1.67,0.7-2.61,0.7
	s-1.81-0.23-2.61-0.7c-0.8-0.47-1.43-1.1-1.9-1.9C10.02,16.43,9.78,15.56,9.78,14.62z M11.48,14.62c0,0.98,0.34,1.81,1.03,2.5
	c0.68,0.69,1.51,1.04,2.49,1.04s1.81-0.35,2.5-1.04s1.04-1.52,1.04-2.5c0-0.96-0.35-1.78-1.04-2.47c-0.69-0.68-1.52-1.02-2.5-1.02
	c-0.97,0-1.8,0.34-2.48,1.02C11.82,12.84,11.48,13.66,11.48,14.62z M14.14,22.4c0-0.24,0.08-0.44,0.25-0.6s0.37-0.24,0.6-0.24
	c0.24,0,0.45,0.08,0.61,0.24s0.24,0.36,0.24,0.6v1.99c0,0.24-0.08,0.45-0.25,0.62c-0.17,0.17-0.37,0.25-0.6,0.25
	s-0.44-0.08-0.6-0.25c-0.17-0.17-0.25-0.38-0.25-0.62V22.4z M14.14,6.9V4.86c0-0.23,0.08-0.43,0.25-0.6C14.56,4.09,14.76,4,15,4
	s0.43,0.08,0.6,0.25c0.17,0.17,0.25,0.37,0.25,0.6V6.9c0,0.23-0.08,0.42-0.25,0.58S15.23,7.71,15,7.71s-0.44-0.08-0.6-0.23
	S14.14,7.13,14.14,6.9z M19.66,20.08c0-0.23,0.08-0.42,0.23-0.56c0.15-0.16,0.34-0.23,0.56-0.23c0.24,0,0.44,0.08,0.6,0.23
	l1.46,1.43c0.16,0.17,0.24,0.38,0.24,0.61c0,0.23-0.08,0.43-0.24,0.59c-0.4,0.31-0.8,0.31-1.2,0l-1.42-1.42
	C19.74,20.55,19.66,20.34,19.66,20.08z M19.66,9.16c0-0.25,0.08-0.45,0.23-0.59l1.42-1.47c0.17-0.16,0.37-0.24,0.59-0.24
	c0.24,0,0.44,0.08,0.6,0.25c0.17,0.17,0.25,0.37,0.25,0.6c0,0.25-0.08,0.46-0.24,0.62l-1.46,1.43c-0.18,0.16-0.38,0.24-0.6,0.24
	c-0.23,0-0.41-0.08-0.56-0.24S19.66,9.4,19.66,9.16z M21.92,14.62c0-0.24,0.08-0.44,0.24-0.62c0.16-0.16,0.35-0.24,0.57-0.24h2.02
	c0.23,0,0.43,0.09,0.6,0.26c0.17,0.17,0.26,0.37,0.26,0.6s-0.09,0.43-0.26,0.6c-0.17,0.17-0.37,0.25-0.6,0.25h-2.02
	c-0.23,0-0.43-0.08-0.58-0.25S21.92,14.86,21.92,14.62z"/>
</svg>';
}

function owmw_rain_chance_icon($color)
{
    return
    '<svg
	 viewBox="0 0 30 30" fill="'.(!empty($color) ? esc_attr($color) : 'currentColor').'">
<title>'.esc_attr__('Rain Chance', 'owm-weather').'</title>
<path d="M4.64,16.91c0-1.15,0.36-2.17,1.08-3.07c0.72-0.9,1.63-1.47,2.73-1.73c0.31-1.36,1.02-2.48,2.11-3.36s2.34-1.31,3.75-1.31
	c1.38,0,2.6,0.43,3.68,1.28c1.08,0.85,1.78,1.95,2.1,3.29h0.32c0.89,0,1.72,0.22,2.48,0.65s1.37,1.03,1.81,1.78
	c0.44,0.75,0.67,1.58,0.67,2.47c0,0.88-0.21,1.69-0.63,2.44c-0.42,0.75-1,1.35-1.73,1.8c-0.73,0.45-1.53,0.69-2.4,0.71
	c-0.13,0-0.2-0.06-0.2-0.17v-1.33c0-0.12,0.07-0.18,0.2-0.18c0.85-0.04,1.58-0.38,2.18-1.02s0.9-1.39,0.9-2.26s-0.33-1.62-0.98-2.26
	s-1.42-0.96-2.31-0.96h-1.61c-0.12,0-0.18-0.06-0.18-0.17l-0.08-0.58c-0.11-1.08-0.58-1.99-1.39-2.71
	c-0.82-0.73-1.76-1.09-2.85-1.09c-1.09,0-2.05,0.36-2.85,1.09c-0.81,0.73-1.26,1.63-1.36,2.71l-0.07,0.53c0,0.12-0.07,0.19-0.2,0.19
	l-0.53,0.03c-0.83,0.1-1.53,0.46-2.1,1.07s-0.85,1.33-0.85,2.16c0,0.87,0.3,1.62,0.9,2.26s1.33,0.98,2.18,1.02
	c0.11,0,0.17,0.06,0.17,0.18v1.33c0,0.11-0.06,0.17-0.17,0.17c-1.34-0.06-2.47-0.57-3.4-1.53S4.64,18.24,4.64,16.91z M9.99,23.6
	c0-0.04,0.01-0.11,0.04-0.2l1.63-5.77c0.06-0.19,0.17-0.34,0.32-0.44c0.15-0.1,0.31-0.15,0.46-0.15c0.07,0,0.15,0.01,0.24,0.03
	c0.24,0.04,0.42,0.17,0.54,0.37c0.12,0.2,0.15,0.42,0.08,0.67l-1.63,5.73c-0.12,0.43-0.4,0.64-0.82,0.64
	c-0.04,0-0.07-0.01-0.11-0.02c-0.06-0.02-0.09-0.03-0.1-0.03c-0.22-0.06-0.38-0.17-0.49-0.33C10.04,23.93,9.99,23.77,9.99,23.6z
	 M12.61,26.41l2.44-8.77c0.04-0.19,0.14-0.34,0.3-0.44c0.16-0.1,0.32-0.15,0.49-0.15c0.09,0,0.18,0.01,0.27,0.03
	c0.22,0.06,0.38,0.19,0.49,0.39c0.11,0.2,0.13,0.41,0.07,0.64l-2.43,8.78c-0.04,0.17-0.13,0.31-0.29,0.43
	c-0.16,0.12-0.32,0.18-0.51,0.18c-0.09,0-0.18-0.02-0.25-0.05c-0.2-0.05-0.37-0.18-0.52-0.39C12.56,26.88,12.54,26.67,12.61,26.41z
	 M16.74,23.62c0-0.04,0.01-0.11,0.04-0.23l1.63-5.77c0.06-0.19,0.16-0.34,0.3-0.44c0.15-0.1,0.3-0.15,0.46-0.15
	c0.08,0,0.17,0.01,0.26,0.03c0.21,0.06,0.36,0.16,0.46,0.31c0.1,0.15,0.15,0.31,0.15,0.47c0,0.03-0.01,0.08-0.02,0.14
	s-0.02,0.1-0.02,0.12l-1.63,5.73c-0.04,0.19-0.13,0.35-0.28,0.46s-0.32,0.17-0.51,0.17l-0.24-0.05c-0.2-0.06-0.35-0.16-0.46-0.32
	C16.79,23.94,16.74,23.78,16.74,23.62z"/>
</svg>';
}

function owmw_temperature_icon($color)
{
    return
    '<svg 
	 viewBox="0 0 30 30" fill="'.(!empty($color) ? esc_attr($color) : 'currentColor').'">
<title>'.esc_attr__('Temperature', 'owm-weather').'</title>
<path d="M9.91,19.56c0-0.85,0.2-1.64,0.59-2.38s0.94-1.35,1.65-1.84V5.42c0-0.8,0.27-1.48,0.82-2.03S14.2,2.55,15,2.55
	c0.81,0,1.49,0.28,2.04,0.83c0.55,0.56,0.83,1.23,0.83,2.03v9.92c0.71,0.49,1.25,1.11,1.64,1.84s0.58,1.53,0.58,2.38
	c0,0.92-0.23,1.78-0.68,2.56s-1.07,1.4-1.85,1.85s-1.63,0.68-2.56,0.68c-0.92,0-1.77-0.23-2.55-0.68s-1.4-1.07-1.86-1.85
	S9.91,20.48,9.91,19.56z M11.67,19.56c0,0.93,0.33,1.73,0.98,2.39c0.65,0.66,1.44,0.99,2.36,0.99c0.93,0,1.73-0.33,2.4-1
	s1.01-1.46,1.01-2.37c0-0.62-0.16-1.2-0.48-1.73c-0.32-0.53-0.76-0.94-1.32-1.23l-0.28-0.14c-0.1-0.04-0.15-0.14-0.15-0.29V5.42
	c0-0.32-0.11-0.59-0.34-0.81C15.62,4.4,15.34,4.29,15,4.29c-0.32,0-0.6,0.11-0.83,0.32c-0.23,0.21-0.34,0.48-0.34,0.81v10.74
	c0,0.15-0.05,0.25-0.14,0.29l-0.27,0.14c-0.55,0.29-0.98,0.7-1.29,1.23C11.82,18.35,11.67,18.92,11.67,19.56z M12.45,19.56
	c0,0.71,0.24,1.32,0.73,1.82s1.07,0.75,1.76,0.75s1.28-0.25,1.79-0.75c0.51-0.5,0.76-1.11,0.76-1.81c0-0.63-0.22-1.19-0.65-1.67
	c-0.43-0.48-0.96-0.77-1.58-0.85V9.69c0-0.06-0.03-0.13-0.1-0.19c-0.07-0.07-0.14-0.1-0.22-0.1c-0.09,0-0.16,0.03-0.21,0.08
	c-0.05,0.06-0.08,0.12-0.08,0.21v7.34c-0.61,0.09-1.13,0.37-1.56,0.85C12.66,18.37,12.45,18.92,12.45,19.56z"/>
</svg>';
}

function owmw_moonrise($color)
{
    return
    '<svg class="climacon climacon-moonrise" viewBox="0 0 1000 1000" fill="' . (!empty($color) ? esc_attr($color) : 'currentColor') . '">
<title>'.esc_attr__('Moonrise', 'owm-weather').'</title>
<metadata> Svg Vector Icons : http://www.onlinewebfonts.com/icon </metadata>
<g><path d="M316.3,683.7l40.8-35.8c-71.5-40.8-117.4-112.3-117.4-199c0-101.9,66.4-183.8,153.1-214.2c0,5.1,0,10.1,0,10.1c0,153.1,127.5,280.7,280.7,280.7c5.1,0,10.1,0,10.1,0c-15.2,40.8-40.8,76.6-71.5,101.9l40.8,35.8c45.9-35.8,76.6-86.7,91.8-148.1c5.1-20.5,5.1-35.8,10.1-56c-30.7,10.1-56,15.2-81.6,15.2c-127.5,0-229.7-101.9-229.7-229.7c0-25.6,5.1-51,15.2-76.6c-20.3,0-35.8,5.1-56,10.1c-122.5,30.7-214.3,142.7-214.3,270.5C188.8,546,239.7,632.7,316.3,683.7z"/><path d="M954.2,780.8H673.6L495.1,627.7L316.6,780.8h-281C20.4,780.8,10,791,10,806.4c0,15.5,10.1,25.6,25.6,25.6H347l61.1-51l91.8-76.6l91.8,76.6l61.4,51h311.4c15.2,0,25.6-10.1,25.6-25.6C979.6,791,969.5,780.8,954.2,780.8z"/></g>
</svg>';
}

function owmw_moonset($color)
{
    return
    '<svg class="climacon climacon-moonset" viewBox="0 0 1000 1000" fill="' . (!empty($color) ? esc_attr($color) : 'currentColor') . '">
<title>'.esc_attr__('Moonset', 'owm-weather').'</title>
<metadata> Svg Vector Icons : http://www.onlinewebfonts.com/icon </metadata>
<g><path d="M964.4,731.8H649.7l-62,51.5l-92.8,77.4l-82.5-77.1l-61.8-51.5H35.9c-15.4,0-25.9,10.2-25.9,25.9c0,15.6,10.2,25.9,25.9,25.9h283.7L500,938.6l180.4-154.8h283.7c15.4,0,25.9-10.3,25.9-25.9C990,742.3,979.8,731.8,964.4,731.8z"/><path d="M474.4,628.8c134,0,242.4-92.8,273.4-216.6c5.1-20.5,5.1-36.1,10.3-56.6c-25.9,10.2-51.5,15.4-77.4,15.4c-128.9,0-232.2-103-232.2-232.2c0-25.9,5.1-51.5,15.4-77.4c-20.5,0-36.1,5.1-56.6,10.2c-123.8,31-216.5,144.3-216.5,273.4C190.7,499.9,319.6,628.8,474.4,628.8z M397,123.4c0,5.1,0,10.2,0,10.2c0,154.8,128.9,283.7,283.7,283.7c5.1,0,10.2,0,10.2,0c-31,92.8-118.7,154.8-216.5,154.8c-128.9,0-232.2-103-232.2-232.2C242.2,241.8,309.3,159.3,397,123.4z"/></g>
</svg>';
}

function owmw_sunrise($color)
{
    return
    '<svg class="climacon climacon-sunrise" viewBox="0 0 512 512" fill="' . (!empty($color) ? esc_attr($color) : 'currentColor') . '">
<title>'.esc_attr__('Sunrise', 'owm-weather').'</title>
<g>
	<path d="M0,316c0-5.2,2.1-10,6.3-14.4c4.6-4,9.6-6.1,14.6-6.1h48.7c5.6,0,10.2,2.1,14,6.1s5.6,9,5.6,14.4c0,6.1-1.9,11.1-5.6,15
		s-8.6,6.1-14,6.1H20.9c-5.6,0-10.4-2.1-14.6-6.3C2.1,326.4,0,321.4,0,316z M69.6,149.2c0-5.6,1.9-10.4,5.4-14.2
		c4.8-4.2,9.6-6.3,14.8-6.3c5.4,0,10.2,2.1,14.2,6.1l34.3,34.5c4,4.6,5.8,9.4,5.8,14.4c0,5.8-1.9,10.9-5.6,14.6
		c-3.8,3.8-8.4,5.8-13.8,5.8c-5,0-10-2.1-14.6-6.1L75,164.5C71.5,160.1,69.6,154.9,69.6,149.2z M111.4,431.3
		c0-5.9,2.1-10.7,6.1-14.2c3.8-3.6,8.4-5.4,14.2-5.4h55l65-61c2.1-1.7,4.4-1.7,7.1,0l66,61h57.9c5.6,0,10.4,1.9,14.4,5.8
		c4,3.8,6.1,8.6,6.1,14c0,5.6-2.1,10.4-6.1,14.4c-4,4-8.8,6.1-14.4,6.1H312c-2.1,0-4.2-0.4-6.1-1.5l-50.4-47.4l-49.9,47.4
		c-1.7,1-3.6,1.5-5.8,1.5h-68.1c-5.6,0-10.4-2.1-14.4-6.1C113.3,441.8,111.4,437,111.4,431.3z M130.6,316c0,20.3,4.4,38.7,13,55.2
		c0.4,2.5,2.3,3.8,5.2,3.8h39.3l3.1-1.7l-0.4-4c-13.4-16.1-20.1-33.9-20.1-53.3c0-23.4,8.4-43.5,25.1-60
		c16.7-16.5,36.8-24.7,60.4-24.7c23.4,0,43.3,8.2,59.8,24.7c16.5,16.5,24.9,36.4,24.9,60c0,19.6-6.7,37.4-19.9,53.3l-0.6,4l3.1,1.7
		h39.7c2.7,0,4.4-1.3,4.8-3.8c9.2-16.1,13.4-34.5,13.4-55.2c0-16.9-3.3-33.2-10-48.5c-6.7-15.5-15.7-28.6-26.7-39.9
		c-11.1-11.1-24.5-20.1-39.9-26.8c-15.5-6.7-31.6-9.8-48.5-9.8c-16.9,0-33.2,3.3-48.7,9.8c-15.5,6.7-28.8,15.5-40.1,26.8
		c-11.3,11.1-20.3,24.4-26.9,39.9C134,282.7,130.6,298.8,130.6,316z M235.7,128.7V80.7c0-6.1,1.9-10.9,5.9-14.8s9-5.8,14.8-5.8
		c5.8,0,10.7,1.9,14.6,5.8c4,4,5.8,9.2,5.8,15.1v48.1c0,6.1-1.9,10.9-5.8,14.8c-3.8,3.8-8.8,5.9-14.6,5.9c-6.1,0-10.9-1.9-14.8-5.9
		S235.7,134.8,235.7,128.7z M368.4,183.9c0-5.2,1.9-10,5.6-14.4l33.9-34.5c4-4,9-6.1,14.6-6.1s10.7,2.1,14.4,6.1
		c4,4,5.8,8.8,5.8,14.4c0,6.1-1.9,11.1-5.4,15.3l-35.1,33.7c-4.4,4-9.4,6.1-14.6,6.1c-5.6,0-10.2-1.9-13.8-5.8
		S368.4,189.8,368.4,183.9z M423,316c0-5.6,1.9-10.4,5.6-14.4c3.8-4,8.4-6.1,13.8-6.1h49.1c5.6,0,10.4,2.1,14.4,6.1s6.1,9,6.1,14.4
		c0,5.8-2.1,10.7-6.1,14.8c-4,4.2-8.8,6.3-14.4,6.3h-49.1c-5.6,0-10.2-2.1-14-6.1C424.9,326.8,423,321.8,423,316z"/>
</g>
</svg>';
}

function owmw_sunset($color)
{
    return
    '<svg class="climacon climacon-sunset" viewBox="0 0 512 512" color="#fff" fill="' . (!empty($color) ? esc_attr($color) : 'currentColor') . '">
<title>'.esc_attr__('Sunset', 'owm-weather').'</title>
<g>
	<path d="M0,285.8c0-5.9,2.1-10.6,6.3-14.4c3.6-3.8,8.5-5.5,14.4-5.5h48.8c5.7,0,10.3,1.9,14.1,5.7c3.6,3.8,5.5,8.4,5.5,14.1
		c0,5.9-1.9,11-5.7,15c-3.8,4-8.5,6.1-13.9,6.1H20.9c-5.7,0-10.6-2.1-14.6-6.3C2.1,296.4,0,291.5,0,285.8z M69.5,119.8
		c0-5.9,1.7-10.8,5.3-14.4c4.2-4.2,9.1-6.3,14.8-6.3c6.1,0,10.8,2.1,14.4,6.3l34.2,34.4c9.7,9.3,9.7,18.8,0,28.5
		c-4,4-8.4,5.9-13.7,5.9c-4.6,0-9.3-1.9-14.4-5.9l-35.1-34.2C71.4,130.4,69.5,125.5,69.5,119.8z M111.1,400.2
		c0-5.5,2.1-10.3,6.3-14.6c3.8-3.8,8.7-5.7,14.4-5.7h68c2.3,0,4.2,0.4,5.9,1.7l49.6,46.9l50.5-47.1c1.5-1.1,3.6-1.7,6.1-1.7h69.7
		c5.7,0,10.6,1.9,14.6,5.9c4,4,6.1,8.9,6.1,14.4c0,5.7-2.1,10.6-6.1,14.6c-4,4-8.9,6.1-14.6,6.1h-56.6l-66.3,60
		c-2.5,1.9-4.9,1.9-7,0l-65-60h-54.9c-5.7,0-10.6-2.1-14.6-6.1C113.2,410.8,111.1,405.9,111.1,400.2z M130.9,285.8
		c0,20.9,4,39.5,12.2,55.3c1.3,2.3,3.2,3.4,5.7,3.4h39.5l3.2-1.5l-1.5-3.8c-12.5-15.6-18.8-33.6-18.8-53.4
		c0-23.2,8.2-43.1,24.9-59.3s36.7-24.5,60.2-24.5c23.2,0,43.1,8.2,59.8,24.5c16.5,16.5,24.9,36.1,24.9,59.1
		c0,19.8-6.3,37.8-18.8,53.4l-1.5,3.8l3.2,1.5h39.7c2.7,0,4.4-1.1,5.1-3.4c8.7-16.5,13.1-35,13.1-55.3c0-16.7-3.4-32.9-9.9-48.4
		c-6.5-15.4-15.6-28.9-26.8-40.1c-11.2-11.2-24.5-20.1-39.9-26.8c-15.4-6.8-31.7-9.9-48.6-9.9c-16.9,0-33.1,3.4-48.6,9.9
		c-15.4,6.8-28.7,15.6-39.9,26.8c-11.2,11.2-20.1,24.5-26.8,40.1C134.5,252.9,130.9,269.1,130.9,285.8z M235.6,99.8V50.6
		c0-5.7,2.1-10.6,6.1-14.6c4-4,8.9-6.1,14.6-6.1c5.7,0,10.6,2.1,14.6,6.1s6.1,8.9,6.1,14.6v49c0,5.7-2.1,10.6-6.1,14.6
		c-4,4-8.9,6.1-14.6,6.1c-5.7,0-10.6-2.1-14.6-6.1C237.5,110.1,235.6,105.5,235.6,99.8z M368,154.3c0-5.9,1.9-10.8,5.5-14.1
		l34.4-34.4c3.4-4.2,8.2-6.3,14.4-6.3c5.7,0,10.6,2.1,14.4,6.1c3.8,4,5.7,8.9,5.7,14.6c0,5.9-1.7,10.8-5.3,14.4L402,168.8
		c-4.9,4-9.7,5.9-14.6,5.9c-5.5,0-10.1-1.9-13.9-5.9C369.9,164.8,368,160,368,154.3z M422.7,285.8c0-5.7,1.9-10.3,5.5-14.1
		c3.6-3.8,8.5-5.7,14.2-5.7h49c5.7,0,10.6,1.9,14.6,5.7c4,3.8,6.1,8.4,6.1,14.1s-2.1,10.6-6.1,14.8c-4,4.2-8.9,6.3-14.6,6.3h-49
		c-5.5,0-10.1-2.1-13.9-6.1C424.6,296.8,422.7,291.7,422.7,285.8z"/>
</g>
</svg>';
}

function owmw_hour_icon($value, $color)
{
    $hour = intval($value);
    if ($hour > 12) {
        $hour %= 12;
    }
    $span_start = '<span title="' . esc_attr($value) . '">';
    $span_end = '</span>';
    
    if ($hour < 0 || $hour > 12) {
        return $span_start . esc_html($value) . $span_end;
    }

    $func = 'owmw_hour_' . $hour . '_icon';
    return $span_start . $func($color) . $span_end;
}

function owmw_hour_0_icon($color)
{
    return hour_12_icon($color);
}

function owmw_hour_1_icon($color)
{
    return
    '<svg
	 viewBox="0 0 30 30" fill="'.(!empty($color) ? esc_attr($color) : 'currentColor').'">
<path d="M3.74,14.47c0-2.04,0.51-3.93,1.52-5.66s2.38-3.1,4.11-4.11s3.61-1.51,5.64-1.51c1.52,0,2.98,0.3,4.37,0.89
	s2.58,1.4,3.59,2.4s1.81,2.2,2.4,3.6s0.89,2.85,0.89,4.39c0,1.52-0.3,2.98-0.89,4.37s-1.4,2.59-2.4,3.59s-2.2,1.8-3.59,2.39
	s-2.84,0.89-4.37,0.89c-1.53,0-3-0.3-4.39-0.89s-2.59-1.4-3.6-2.4s-1.8-2.2-2.4-3.58S3.74,16,3.74,14.47z M6.22,14.47
	c0,2.37,0.86,4.43,2.59,6.18c1.73,1.73,3.79,2.59,6.2,2.59c1.58,0,3.05-0.39,4.39-1.18s2.42-1.85,3.21-3.2s1.19-2.81,1.19-4.39
	s-0.4-3.05-1.19-4.4s-1.86-2.42-3.21-3.21s-2.81-1.18-4.39-1.18s-3.05,0.39-4.39,1.18S8.2,8.72,7.4,10.07S6.22,12.89,6.22,14.47z
	 M14.14,14.47V7.81c0-0.23,0.08-0.43,0.24-0.59s0.36-0.24,0.59-0.24c0.22,0,0.42,0.08,0.59,0.24s0.25,0.36,0.25,0.59v3.53l0.75-1.3
	c0.12-0.2,0.29-0.32,0.52-0.38s0.44-0.03,0.64,0.09c0.2,0.11,0.32,0.27,0.39,0.5s0.04,0.43-0.08,0.63l-2.29,3.91
	c-0.13,0.35-0.38,0.53-0.76,0.53c-0.23,0-0.43-0.08-0.59-0.24S14.14,14.71,14.14,14.47z"/>
</svg>';
}

function owmw_hour_2_icon($color)
{
    return
    '<svg
	 viewBox="0 0 30 30" fill="'.(!empty($color) ? esc_attr($color) : 'currentColor').'">
<path d="M3.74,14.47c0-2.04,0.51-3.93,1.52-5.66s2.38-3.1,4.11-4.11s3.61-1.51,5.64-1.51c1.52,0,2.98,0.3,4.37,0.89
	s2.58,1.4,3.59,2.4s1.81,2.2,2.4,3.6s0.89,2.85,0.89,4.39c0,1.52-0.3,2.98-0.89,4.37s-1.4,2.59-2.4,3.59s-2.2,1.8-3.59,2.39
	s-2.84,0.89-4.37,0.89c-1.53,0-3-0.3-4.39-0.89s-2.59-1.4-3.6-2.4s-1.8-2.2-2.4-3.58S3.74,16,3.74,14.47z M6.22,14.47
	c0,2.37,0.86,4.43,2.59,6.18c1.73,1.73,3.79,2.59,6.2,2.59c1.58,0,3.05-0.39,4.39-1.18s2.42-1.85,3.21-3.2s1.19-2.81,1.19-4.39
	s-0.4-3.05-1.19-4.4s-1.86-2.42-3.21-3.21s-2.81-1.18-4.39-1.18s-3.05,0.39-4.39,1.18S8.2,8.72,7.4,10.07S6.22,12.89,6.22,14.47z
	 M14.14,14.47V7.81c0-0.23,0.08-0.43,0.24-0.59s0.36-0.24,0.59-0.24s0.43,0.08,0.59,0.24s0.24,0.36,0.24,0.59v5.21l2.78-1.57
	c0.2-0.12,0.41-0.15,0.63-0.09s0.39,0.2,0.5,0.41c0.12,0.2,0.14,0.41,0.08,0.63s-0.19,0.4-0.39,0.51l-3.88,2.17
	c-0.17,0.15-0.35,0.22-0.56,0.22c-0.23,0-0.43-0.08-0.59-0.24S14.14,14.71,14.14,14.47z"/>
</svg>';
}

function owmw_hour_3_icon($color)
{
    return
    '<svg
	 viewBox="0 0 30 30" fill="'.(!empty($color) ? esc_attr($color) : 'currentColor').'">
<path d="M3.74,14.47c0-2.04,0.51-3.93,1.52-5.66s2.38-3.1,4.11-4.11s3.61-1.51,5.64-1.51c1.52,0,2.98,0.3,4.37,0.89
	s2.58,1.4,3.59,2.4s1.81,2.2,2.4,3.6s0.89,2.85,0.89,4.39c0,1.52-0.3,2.98-0.89,4.37s-1.4,2.59-2.4,3.59s-2.2,1.8-3.59,2.39
	s-2.84,0.89-4.37,0.89c-1.53,0-3-0.3-4.39-0.89s-2.59-1.4-3.6-2.4s-1.8-2.2-2.4-3.58S3.74,16,3.74,14.47z M6.22,14.47
	c0,2.37,0.86,4.43,2.59,6.18c1.73,1.73,3.79,2.59,6.2,2.59c1.58,0,3.05-0.39,4.39-1.18s2.42-1.85,3.21-3.2s1.19-2.81,1.19-4.39
	s-0.4-3.05-1.19-4.4s-1.86-2.42-3.21-3.21s-2.81-1.18-4.39-1.18s-3.05,0.39-4.39,1.18S8.2,8.72,7.4,10.07S6.22,12.89,6.22,14.47z
	 M14.14,14.47V7.81c0-0.23,0.08-0.43,0.24-0.59s0.36-0.24,0.59-0.24s0.43,0.08,0.59,0.24s0.24,0.36,0.24,0.59v5.82h3.78
	c0.23,0,0.43,0.08,0.59,0.24s0.24,0.36,0.24,0.59c0,0.22-0.08,0.42-0.24,0.59c-0.16,0.17-0.36,0.25-0.59,0.25h-4.44
	c-0.03,0.01-0.09,0.01-0.18,0.01c-0.23,0-0.43-0.08-0.59-0.24S14.14,14.71,14.14,14.47z"/>
</svg>';
}

function owmw_hour_4_icon($color)
{
    return
    '<svg
	 viewBox="0 0 30 30" fill="'.(!empty($color) ? esc_attr($color) : 'currentColor').'">
<path d="M3.74,14.47c0-2.04,0.51-3.93,1.52-5.66s2.38-3.1,4.11-4.11s3.61-1.51,5.64-1.51c1.52,0,2.98,0.3,4.37,0.89
	s2.58,1.4,3.59,2.4s1.81,2.2,2.4,3.6s0.89,2.85,0.89,4.39c0,1.52-0.3,2.98-0.89,4.37s-1.4,2.59-2.4,3.59s-2.2,1.8-3.59,2.39
	s-2.84,0.89-4.37,0.89c-1.53,0-3-0.3-4.39-0.89s-2.59-1.4-3.6-2.4s-1.8-2.2-2.4-3.58S3.74,16,3.74,14.47z M6.22,14.47
	c0,2.37,0.86,4.43,2.59,6.18c1.73,1.73,3.79,2.59,6.2,2.59c1.58,0,3.05-0.39,4.39-1.18s2.42-1.85,3.21-3.2s1.19-2.81,1.19-4.39
	s-0.4-3.05-1.19-4.4s-1.86-2.42-3.21-3.21s-2.81-1.18-4.39-1.18s-3.05,0.39-4.39,1.18S8.2,8.72,7.4,10.07S6.22,12.89,6.22,14.47z
	 M14.14,14.47V7.81c0-0.23,0.08-0.43,0.24-0.59s0.36-0.24,0.59-0.24s0.43,0.08,0.59,0.24s0.24,0.36,0.24,0.59v6.15l3.59,2.09
	c0.2,0.12,0.32,0.29,0.38,0.51s0.03,0.43-0.09,0.62c-0.16,0.28-0.4,0.42-0.72,0.42c-0.17,0-0.31-0.04-0.42-0.12l-3.82-2.23
	c-0.17-0.05-0.31-0.15-0.42-0.29S14.14,14.66,14.14,14.47z"/>
</svg>';
}

function owmw_hour_5_icon($color)
{
    return
    '<svg
	 viewBox="0 0 30 30" fill="'.(!empty($color) ? esc_attr($color) : 'currentColor').'">
<path d="M3.74,14.47c0-2.04,0.51-3.93,1.52-5.66s2.38-3.1,4.11-4.11s3.61-1.51,5.64-1.51c1.52,0,2.98,0.3,4.37,0.89
	s2.58,1.4,3.59,2.4s1.81,2.2,2.4,3.6s0.89,2.85,0.89,4.39c0,1.52-0.3,2.98-0.89,4.37s-1.4,2.59-2.4,3.59s-2.2,1.8-3.59,2.39
	s-2.84,0.89-4.37,0.89c-1.53,0-3-0.3-4.39-0.89s-2.59-1.4-3.6-2.4s-1.8-2.2-2.4-3.58S3.74,16,3.74,14.47z M6.22,14.47
	c0,2.37,0.86,4.43,2.59,6.18c1.73,1.73,3.79,2.59,6.2,2.59c1.58,0,3.05-0.39,4.39-1.18s2.42-1.85,3.21-3.2s1.19-2.81,1.19-4.39
	s-0.4-3.05-1.19-4.4s-1.86-2.42-3.21-3.21s-2.81-1.18-4.39-1.18s-3.05,0.39-4.39,1.18S8.2,8.72,7.4,10.07S6.22,12.89,6.22,14.47z
	 M14.14,14.47V7.81c0-0.23,0.08-0.43,0.24-0.59s0.36-0.24,0.59-0.24s0.43,0.08,0.59,0.24s0.24,0.36,0.24,0.59v6.42l2.15,3.84
	c0.12,0.21,0.14,0.43,0.08,0.65s-0.19,0.39-0.39,0.51c-0.11,0.06-0.24,0.09-0.41,0.09c-0.33,0-0.58-0.14-0.73-0.41l-2.2-3.9
	C14.2,14.85,14.14,14.68,14.14,14.47z"/>
</svg>';
}

function owmw_hour_6_icon($color)
{
    return
    '<svg
	 viewBox="0 0 30 30" fill="'.(!empty($color) ? esc_attr($color) : 'currentColor').'">
<path d="M3.74,14.47c0-2.04,0.51-3.93,1.52-5.66s2.38-3.1,4.11-4.11s3.61-1.51,5.64-1.51c1.52,0,2.98,0.3,4.37,0.89
	s2.58,1.4,3.59,2.4s1.81,2.2,2.4,3.6s0.89,2.85,0.89,4.39c0,1.52-0.3,2.98-0.89,4.37s-1.4,2.59-2.4,3.59s-2.2,1.8-3.59,2.39
	s-2.84,0.89-4.37,0.89c-1.53,0-3-0.3-4.39-0.89s-2.59-1.4-3.6-2.4s-1.8-2.2-2.4-3.58S3.74,16,3.74,14.47z M6.22,14.47
	c0,2.37,0.86,4.43,2.59,6.18c1.73,1.73,3.79,2.59,6.2,2.59c1.58,0,3.05-0.39,4.39-1.18s2.42-1.85,3.21-3.2s1.19-2.81,1.19-4.39
	s-0.4-3.05-1.19-4.4s-1.86-2.42-3.21-3.21s-2.81-1.18-4.39-1.18s-3.05,0.39-4.39,1.18S8.2,8.72,7.4,10.07S6.22,12.89,6.22,14.47z
	 M14.14,19.07V7.81c0-0.23,0.08-0.43,0.24-0.59s0.36-0.24,0.59-0.24s0.43,0.08,0.59,0.24s0.24,0.36,0.24,0.59v11.26
	c0,0.23-0.08,0.43-0.24,0.6s-0.36,0.25-0.59,0.25s-0.43-0.08-0.59-0.25S14.14,19.31,14.14,19.07z"/>
</svg>';
}

function owmw_hour_7_icon($color)
{
    return
    '<svg
	 viewBox="0 0 30 30" fill="'.(!empty($color) ? esc_attr($color) : 'currentColor').'">
<path d="M3.74,14.47c0-2.04,0.51-3.93,1.52-5.66s2.38-3.1,4.11-4.11s3.61-1.51,5.64-1.51c1.52,0,2.98,0.3,4.37,0.89
	s2.58,1.4,3.59,2.4s1.81,2.2,2.4,3.6s0.89,2.85,0.89,4.39c0,1.52-0.3,2.98-0.89,4.37s-1.4,2.59-2.4,3.59s-2.2,1.8-3.59,2.39
	s-2.84,0.89-4.37,0.89c-1.53,0-3-0.3-4.39-0.89s-2.59-1.4-3.6-2.4s-1.8-2.2-2.4-3.58S3.74,16,3.74,14.47z M6.22,14.47
	c0,2.37,0.86,4.43,2.59,6.18c1.73,1.73,3.79,2.59,6.2,2.59c1.58,0,3.05-0.39,4.39-1.18s2.42-1.85,3.21-3.2s1.19-2.81,1.19-4.39
	s-0.4-3.05-1.19-4.4s-1.86-2.42-3.21-3.21s-2.81-1.18-4.39-1.18s-3.05,0.39-4.39,1.18S8.2,8.72,7.4,10.07S6.22,12.89,6.22,14.47z
	 M11.89,18.71c-0.06-0.22-0.04-0.44,0.08-0.65l2.17-3.84V7.81c0-0.23,0.08-0.43,0.24-0.59s0.36-0.24,0.59-0.24s0.43,0.08,0.59,0.24
	s0.24,0.36,0.24,0.59v6.67c0,0.2-0.06,0.37-0.19,0.53l-2.18,3.9c-0.16,0.27-0.41,0.41-0.75,0.41c-0.16,0-0.29-0.03-0.4-0.09
	C12.09,19.1,11.96,18.93,11.89,18.71z"/>
</svg>';
}

function owmw_hour_8_icon($color)
{
    return
    '<svg
	 viewBox="0 0 30 30" fill="'.(!empty($color) ? esc_attr($color) : 'currentColor').'">
<path d="M3.74,14.47c0-2.04,0.51-3.93,1.52-5.66s2.38-3.1,4.11-4.11s3.61-1.51,5.64-1.51c1.52,0,2.98,0.3,4.37,0.89
	s2.58,1.4,3.59,2.4s1.81,2.2,2.4,3.6s0.89,2.85,0.89,4.39c0,1.52-0.3,2.98-0.89,4.37s-1.4,2.59-2.4,3.59s-2.2,1.8-3.59,2.39
	s-2.84,0.89-4.37,0.89c-1.53,0-3-0.3-4.39-0.89s-2.59-1.4-3.6-2.4s-1.8-2.2-2.4-3.58S3.74,16,3.74,14.47z M6.22,14.47
	c0,2.37,0.86,4.43,2.59,6.18c1.73,1.73,3.79,2.59,6.2,2.59c1.58,0,3.05-0.39,4.39-1.18s2.42-1.85,3.21-3.2s1.19-2.81,1.19-4.39
	s-0.4-3.05-1.19-4.4s-1.86-2.42-3.21-3.21s-2.81-1.18-4.39-1.18s-3.05,0.39-4.39,1.18S8.2,8.72,7.4,10.07S6.22,12.89,6.22,14.47z
	 M10.17,16.56c0.06-0.22,0.19-0.39,0.38-0.51l3.59-2.09V7.81c0-0.23,0.08-0.43,0.24-0.59s0.36-0.24,0.59-0.24s0.43,0.08,0.59,0.24
	s0.24,0.36,0.24,0.59v6.67c0,0.19-0.06,0.35-0.17,0.5s-0.25,0.24-0.42,0.29l-3.84,2.23c-0.12,0.08-0.25,0.12-0.41,0.12
	c-0.32,0-0.56-0.14-0.72-0.42C10.14,16.99,10.11,16.78,10.17,16.56z"/>
</svg>';
}

function owmw_hour_9_icon($color)
{
    return
    '<svg
	 viewBox="0 0 30 30" fill="'.(!empty($color) ? esc_attr($color) : 'currentColor').'">
<path d="M3.74,14.47c0-2.04,0.51-3.93,1.52-5.66s2.38-3.1,4.11-4.11s3.61-1.51,5.64-1.51c1.52,0,2.98,0.3,4.37,0.89
	s2.58,1.4,3.59,2.4s1.81,2.2,2.4,3.6s0.89,2.85,0.89,4.39c0,1.52-0.3,2.98-0.89,4.37s-1.4,2.59-2.4,3.59s-2.2,1.8-3.59,2.39
	s-2.84,0.89-4.37,0.89c-1.53,0-3-0.3-4.39-0.89s-2.59-1.4-3.6-2.4s-1.8-2.2-2.4-3.58S3.74,16,3.74,14.47z M6.22,14.47
	c0,2.37,0.86,4.43,2.59,6.18c1.73,1.73,3.79,2.59,6.2,2.59c1.58,0,3.05-0.39,4.39-1.18s2.42-1.85,3.21-3.2s1.19-2.81,1.19-4.39
	s-0.4-3.05-1.19-4.4s-1.86-2.42-3.21-3.21s-2.81-1.18-4.39-1.18s-3.05,0.39-4.39,1.18S8.2,8.72,7.4,10.07S6.22,12.89,6.22,14.47z
	 M9.51,14.46c0-0.23,0.08-0.43,0.24-0.59s0.36-0.24,0.59-0.24h3.79V7.81c0-0.23,0.08-0.43,0.24-0.59s0.36-0.24,0.59-0.24
	s0.43,0.08,0.59,0.24s0.24,0.36,0.24,0.59v6.67c0,0.23-0.08,0.43-0.24,0.59s-0.36,0.24-0.59,0.24c-0.1,0-0.16,0-0.19-0.01h-4.44
	c-0.23,0-0.43-0.08-0.59-0.25C9.59,14.88,9.51,14.68,9.51,14.46z"/>
</svg>';
}

function owmw_hour_10_icon($color)
{
    return
    '<svg
	 viewBox="0 0 30 30" fill="'.(!empty($color) ? esc_attr($color) : 'currentColor').'">
<path d="M3.74,14.47c0-2.04,0.51-3.93,1.52-5.66s2.38-3.1,4.11-4.11s3.61-1.51,5.64-1.51c1.52,0,2.98,0.3,4.37,0.89
	s2.58,1.4,3.59,2.4s1.81,2.2,2.4,3.6s0.89,2.85,0.89,4.39c0,1.52-0.3,2.98-0.89,4.37s-1.4,2.59-2.4,3.59s-2.2,1.8-3.59,2.39
	s-2.84,0.89-4.37,0.89c-1.53,0-3-0.3-4.39-0.89s-2.59-1.4-3.6-2.4s-1.8-2.2-2.4-3.58S3.74,16,3.74,14.47z M6.22,14.47
	c0,2.37,0.86,4.43,2.59,6.18c1.73,1.73,3.79,2.59,6.2,2.59c1.58,0,3.05-0.39,4.39-1.18s2.42-1.85,3.21-3.2s1.19-2.81,1.19-4.39
	s-0.4-3.05-1.19-4.4s-1.86-2.42-3.21-3.21s-2.81-1.18-4.39-1.18s-3.05,0.39-4.39,1.18S8.2,8.72,7.4,10.07S6.22,12.89,6.22,14.47z
	 M10.14,12.41c-0.07-0.22-0.04-0.43,0.07-0.63c0.11-0.2,0.28-0.33,0.51-0.4s0.44-0.04,0.64,0.07l2.78,1.57V7.81
	c0-0.23,0.08-0.43,0.24-0.59s0.36-0.24,0.59-0.24s0.43,0.08,0.59,0.24s0.24,0.36,0.24,0.59v6.67c0,0.23-0.08,0.43-0.24,0.59
	s-0.36,0.24-0.59,0.24c-0.21,0-0.39-0.07-0.56-0.22l-3.88-2.17C10.34,12.8,10.21,12.63,10.14,12.41z"/>
</svg>';
}

function owmw_hour_11_icon($color)
{
    return
    '<svg
	 viewBox="0 0 30 30" fill="'.(!empty($color) ? esc_attr($color) : 'currentColor').'">
<path d="M3.74,14.47c0-2.04,0.51-3.93,1.52-5.66s2.38-3.1,4.11-4.11s3.61-1.51,5.64-1.51c1.52,0,2.98,0.3,4.37,0.89
	s2.58,1.4,3.59,2.4s1.81,2.2,2.4,3.6s0.89,2.85,0.89,4.39c0,1.52-0.3,2.98-0.89,4.37s-1.4,2.59-2.4,3.59s-2.2,1.8-3.59,2.39
	s-2.84,0.89-4.37,0.89c-1.53,0-3-0.3-4.39-0.89s-2.59-1.4-3.6-2.4s-1.8-2.2-2.4-3.58S3.74,16,3.74,14.47z M6.22,14.47
	c0,2.37,0.86,4.43,2.59,6.18c1.73,1.73,3.79,2.59,6.2,2.59c1.58,0,3.05-0.39,4.39-1.18s2.42-1.85,3.21-3.2s1.19-2.81,1.19-4.39
	s-0.4-3.05-1.19-4.4s-1.86-2.42-3.21-3.21c-1.35-0.79-2.81-1.18-4.39-1.18s-3.05,0.39-4.39,1.18C9.27,7.65,8.2,8.72,7.4,10.07
	S6.22,12.89,6.22,14.47z M11.84,10.24c0.06-0.22,0.19-0.39,0.38-0.5c0.2-0.12,0.41-0.15,0.64-0.09s0.4,0.19,0.51,0.38l0.78,1.3V7.81
	c0-0.23,0.08-0.43,0.24-0.59s0.36-0.24,0.59-0.24s0.43,0.08,0.59,0.24s0.24,0.36,0.24,0.59v6.67c0,0.23-0.08,0.43-0.24,0.59
	s-0.36,0.24-0.59,0.24c-0.4,0-0.66-0.18-0.79-0.53l-2.26-3.91C11.81,10.67,11.78,10.46,11.84,10.24z"/>
</svg>';
}

function owmw_hour_12_icon($color)
{
    return
    '<svg
	 viewBox="0 0 30 30" fill="'.(!empty($color) ? esc_attr($color) : 'currentColor').'">
<path d="M3.74,14.47c0-2.04,0.51-3.93,1.52-5.66s2.38-3.1,4.11-4.11s3.61-1.51,5.64-1.51c1.52,0,2.98,0.3,4.37,0.89
	s2.58,1.4,3.59,2.4s1.81,2.2,2.4,3.6s0.89,2.85,0.89,4.39c0,1.52-0.3,2.98-0.89,4.37s-1.4,2.59-2.4,3.59s-2.2,1.8-3.59,2.39
	s-2.84,0.89-4.37,0.89c-1.53,0-3-0.3-4.39-0.89s-2.59-1.4-3.6-2.4s-1.8-2.2-2.4-3.58S3.74,16,3.74,14.47z M6.22,14.47
	c0,2.37,0.86,4.43,2.59,6.18c1.73,1.73,3.79,2.59,6.2,2.59c1.58,0,3.05-0.39,4.39-1.18s2.42-1.85,3.21-3.2s1.19-2.81,1.19-4.39
	s-0.4-3.05-1.19-4.4s-1.86-2.42-3.21-3.21s-2.81-1.18-4.39-1.18s-3.05,0.39-4.39,1.18S8.2,8.72,7.4,10.07S6.22,12.89,6.22,14.47z
	 M14.14,14.47c0,0.22,0.08,0.42,0.24,0.59c0.16,0.17,0.36,0.25,0.59,0.25c0.22,0,0.42-0.08,0.59-0.25c0.17-0.17,0.25-0.36,0.25-0.59
	V7.81c0-0.23-0.08-0.43-0.25-0.59s-0.36-0.24-0.59-0.24c-0.23,0-0.43,0.08-0.59,0.24s-0.24,0.36-0.24,0.59V14.47z"/>
</svg>';
}
