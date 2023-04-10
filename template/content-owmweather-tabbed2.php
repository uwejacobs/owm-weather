<?php
/****************************************************************************/
/* DO NOT CHANGE THIS TEMPLATE DIRECTLY. IT WILL BE OVERWRITTEN BY UPDATES. */
/****************************************************************************/
/**
 * The OWM Weather tabbed2 template
 *
 */
?>
<!-- Start #owm-weather styles -->
<style>
#<?php echo esc_attr($owmw_html["main_weather_div"]) ?> .tab {
  overflow: hidden;
  color: <?php echo $owmw_opt["tabbed_btn_text_color"]; ?>;
  border: 1px solid <?php echo $owmw_opt["tabbed_btn_active_color"]; ?>;
  background-color: <?php echo $owmw_opt["tabbed_btn_background_color"]; ?>;
}

#<?php echo esc_attr($owmw_html["main_weather_div"]) ?> .tab button {
  background-color: inherit;
  float: left;
  border: none;
  outline: none;
  cursor: pointer;
  padding: 14px 16px;
  transition: 0.3s;
  font-size: 125%;
}

#<?php echo esc_attr($owmw_html["main_weather_div"]) ?> .tab button:hover {
  background-color: <?php echo $owmw_opt["tabbed_btn_hover_color"]; ?>;
}

#<?php echo esc_attr($owmw_html["main_weather_div"]) ?> .tab button.active {
  background-color: <?php echo $owmw_opt["tabbed_btn_active_color"]; ?>;
}

#<?php echo esc_attr($owmw_html["main_weather_div"]) ?> .tabcontent {
  display: none;
  padding: 6px 12px;
  -webkit-animation: fadeEffect 1s;
  animation: fadeEffect 1s;
}

@-webkit-keyframes fadeEffect {
  from {opacity: 0;}
  to {opacity: 1;}
}

@keyframes fadeEffect {
  from {opacity: 0;}
  to {opacity: 1;}
}

#<?php echo esc_attr($owmw_html["main_weather_div"]) ?> {
    width: 100%;
    box-sizing: border-box;
}
#<?php echo esc_attr($owmw_html["main_weather_div"]) ?> .owmw-current-infos.card {
    margin-right: auto !important;
    margin-left: auto !important;
    width: -moz-fit-content;
    width: fit-content;
    border: 0;
    background-color: inherit;
}
#<?php echo esc_attr($owmw_html["main_weather_div"]) ?> .owmw-current-infos {
    white-space: nowrap;
}
#<?php echo esc_attr($owmw_html["main_weather_div"]) ?> .owmw-infos-text {
    line-height: 1.5;
    margin-top: 20px;
    text-align: left;
}
#<?php echo esc_attr($owmw_html["main_weather_div"]) ?> .owmw-infos-text td.owmw-value {
    text-align: right !important;
}
#<?php echo esc_attr($owmw_html["main_weather_div"]) ?> .owmw-infos-text td.owmw-value,
#<?php echo esc_attr($owmw_html["main_weather_div"]) ?> .owmw-temperature {
    font-size: 125%;
    font-weight: 700;
}
#<?php echo esc_attr($owmw_html["main_weather_div"]) ?> .owmw-infos-text svg {
    height: 28px;
    width: 29px;
}
#<?php echo esc_attr($owmw_html["main_weather_div"]) ?> .owmw-now .owmw-main-temperature {
    font-size: 36px;
}
#<?php echo esc_attr($owmw_html["main_weather_div"]) ?> .owmw-today .owmw-sun-hours,
#<?php echo esc_attr($owmw_html["main_weather_div"]) ?> .owmw-today .owmw-moon-hours {
    font-size: 12px;
}
#<?php echo esc_attr($owmw_html["main_weather_div"]) ?> .owmw-hours {
        border-bottom-style: none!important;
        border-top-style: none!important;
        border-width: 0!important;
}
#<?php echo esc_attr($owmw_html["main_weather_div"]) ?> div.tab {        
    width: -moz-fit-content;
    width: fit-content;
    margin-right: auto !important;
    margin-left: auto !important;
}
</style>

<div class="tab">
    <button class="<?php echo esc_attr($owmw_html["main_weather_div"]) ?>-tablinks" onclick="<?php echo esc_attr(str_replace("-", "_", $owmw_html["main_weather_div"])) ?>_openWeather(event, 'current')" id="<?php echo esc_attr($owmw_html["main_weather_div"]) ?>-defaultOpen">Current</button>
    <?php if ($owmw_opt["hours_forecast_no"] > 0) { ?>
        <button class="<?php echo esc_attr($owmw_html["main_weather_div"]) ?>-tablinks" onclick="<?php echo esc_attr(str_replace("-", "_", $owmw_html["main_weather_div"])) ?>_openWeather(event, 'hourly')">Hourly</button>
    <?php } ?>
    <?php if ($owmw_opt["days_forecast_no"] > 0) { ?>
        <button class="<?php echo esc_attr($owmw_html["main_weather_div"]) ?>-tablinks" onclick="<?php echo esc_attr(str_replace("-", "_", $owmw_html["main_weather_div"])) ?>_openWeather(event, 'daily')">Daily</button>
    <?php } ?>
    <?php if ($owmw_opt["map"] == 'yes') { ?>
          <button class="<?php echo esc_attr($owmw_html["main_weather_div"]) ?>-tablinks" onclick="<?php echo esc_attr(str_replace("-", "_", $owmw_html["main_weather_div"])) ?>_openWeather(event, 'map')">Map</button>
    <?php } ?>
</div>

<!-- Start #owm-weather-container -->
<?php echo wp_kses($owmw_html["container"]["start"], $owmw_opt['allowed_html']); ?>

    <div id="<?php echo esc_attr($owmw_html["main_weather_div"]) ?>-current" class="<?php echo esc_attr($owmw_html["main_weather_div"]) ?>-tabcontent">
        <div class="row">
            <div class="col">
                <!-- Current weather -->
                <?php echo wp_kses($owmw_html["now"]["start"], $owmw_opt['allowed_html']); ?>
                    <?php echo wp_kses($owmw_html["now"]["location_name"], $owmw_opt['allowed_html']); ?>
                    <?php echo wp_kses($owmw_html["now"]["symbol"], $owmw_opt['allowed_html']); ?>
                    <?php echo wp_kses($owmw_html["now"]["temperature"], $owmw_opt['allowed_html']); ?>
                    <?php echo wp_kses($owmw_html["now"]["feels_like"], $owmw_opt['allowed_html']); ?>
                    <?php echo wp_kses($owmw_html["now"]["weather_description"], $owmw_opt['allowed_html']); ?>
                <?php echo wp_kses($owmw_html["now"]["end"], $owmw_opt['allowed_html']); ?>

                <!-- Alert button -->
                <?php echo wp_kses($owmw_html["alert_button"], $owmw_opt['allowed_html']); ?>
                <?php echo wp_kses($owmw_html["alert_inline"], $owmw_opt['allowed_html']); ?>

                <!-- Today -->
                <?php echo wp_kses($owmw_html["today"]["start"], $owmw_opt['allowed_html']); ?>
                    <?php echo wp_kses($owmw_html["today"]["day"], $owmw_opt['allowed_html']); ?>
                <?php echo wp_kses($owmw_html["today"]["end"], $owmw_opt['allowed_html']); ?>
            </div>

            <!-- Current infos: wind, humidity, pressure, cloudiness, precipitation -->
            <?php if ($owmw_opt["wind"] =='yes' || $owmw_opt["humidity"] =='yes' || $owmw_opt["dew_point"] =='yes' || $owmw_opt["pressure"] =='yes' || $owmw_opt["cloudiness"] =='yes' || $owmw_opt["precipitation"] =='yes' || $owmw_opt["visibility"] =='yes' || $owmw_opt["uv_index"] =='yes') { ?>
                <div class="col">
                    <?php echo wp_kses($owmw_html["now"]["start_card"], $owmw_opt['allowed_html']);
                          echo wp_kses($owmw_html["now"]["info_card"], $owmw_opt['allowed_html']);
                          echo wp_kses($owmw_html["now"]["end_card"], $owmw_opt['allowed_html']);
                    ?>
                </div>
            <?php } ?>
        </div>

        <!-- OWM Link -->
        <?php echo wp_kses($owmw_html["owm_link_last_update_start"], $owmw_opt['allowed_html']); ?>
        <?php echo wp_kses($owmw_html["owm_link"], $owmw_opt['allowed_html']); ?>
        <!-- OWM Last Update -->
        <?php echo wp_kses($owmw_html["last_update"], $owmw_opt['allowed_html']); ?>
        <?php echo wp_kses($owmw_html["owm_link_last_update_end"], $owmw_opt['allowed_html']); ?>
    </div>

    <?php if ($owmw_opt["hours_forecast_no"] > 0) { ?>
    <div id="<?php echo esc_attr($owmw_html["main_weather_div"]) ?>-hourly" class="<?php echo esc_attr($owmw_html["main_weather_div"]) ?>-tabcontent">
        <!-- Hourly Forecast -->
        <?php echo wp_kses($owmw_html["chart"]["hourly"]["temperatures"]["container"], $owmw_opt['allowed_html']); ?>
        <script type="text/javascript"><?php echo wp_kses($owmw_html["chart"]["hourly"]["temperatures"]["script"], $owmw_opt['allowed_html']); ?></script>
        <?php echo wp_kses($owmw_html["chart"]["hourly"]["precipitation"]["container"], $owmw_opt['allowed_html']); ?>
        <script type="text/javascript"><?php echo wp_kses($owmw_html["chart"]["hourly"]["precipitation"]["script"], $owmw_opt['allowed_html']); ?></script>
        <?php echo wp_kses($owmw_html["chart"]["hourly"]["precip_amt"]["container"], $owmw_opt['allowed_html']); ?>
        <script type="text/javascript"><?php echo wp_kses($owmw_html["chart"]["hourly"]["precip_amt"]["script"], $owmw_opt['allowed_html']); ?></script>
        <?php echo wp_kses($owmw_html["chart"]["hourly"]["wind"]["container"], $owmw_opt['allowed_html']); ?>
        <script type="text/javascript"><?php echo wp_kses($owmw_html["chart"]["hourly"]["wind"]["script"], $owmw_opt['allowed_html']); ?></script>
        <!-- OWM Link -->
        <?php echo wp_kses($owmw_html["owm_link_last_update_start"], $owmw_opt['allowed_html']); ?>
        <?php echo wp_kses($owmw_html["owm_link"], $owmw_opt['allowed_html']); ?>
        <!-- OWM Last Update -->
        <?php echo wp_kses($owmw_html["last_update"], $owmw_opt['allowed_html']); ?>
        <?php echo wp_kses($owmw_html["owm_link_last_update_end"], $owmw_opt['allowed_html']); ?>
    </div>
    <?php } ?>

    <?php if ($owmw_opt["days_forecast_no"] > 0) { ?>
    <div id="<?php echo esc_attr($owmw_html["main_weather_div"]) ?>-daily" class="<?php echo esc_attr($owmw_html["main_weather_div"]) ?>-tabcontent">
        <!-- Daily Forecast -->
        <?php echo wp_kses($owmw_html["chart"]["daily"]["temperatures"]["container"], $owmw_opt['allowed_html']); ?>
        <script type="text/javascript"><?php echo wp_kses($owmw_html["chart"]["daily"]["temperatures"]["script"], $owmw_opt['allowed_html']); ?></script>
        <?php echo wp_kses($owmw_html["chart"]["daily"]["precipitation"]["container"], $owmw_opt['allowed_html']); ?>
        <script type="text/javascript"><?php echo wp_kses($owmw_html["chart"]["daily"]["precipitation"]["script"], $owmw_opt['allowed_html']); ?></script>
        <?php echo wp_kses($owmw_html["chart"]["daily"]["precip_amt"]["container"], $owmw_opt['allowed_html']); ?>
        <script type="text/javascript"><?php echo wp_kses($owmw_html["chart"]["daily"]["precip_amt"]["script"], $owmw_opt['allowed_html']); ?></script>
        <?php echo wp_kses($owmw_html["chart"]["daily"]["wind"]["container"], $owmw_opt['allowed_html']); ?>
        <script type="text/javascript"><?php echo wp_kses($owmw_html["chart"]["daily"]["wind"]["script"], $owmw_opt['allowed_html']); ?></script>
        <!-- OWM Link -->
        <?php echo wp_kses($owmw_html["owm_link_last_update_start"], $owmw_opt['allowed_html']); ?>
        <?php echo wp_kses($owmw_html["owm_link"], $owmw_opt['allowed_html']); ?>
        <!-- OWM Last Update -->
        <?php echo wp_kses($owmw_html["last_update"], $owmw_opt['allowed_html']); ?>
        <?php echo wp_kses($owmw_html["owm_link_last_update_end"], $owmw_opt['allowed_html']); ?>
    </div>
    <?php } ?>

    <?php if ($owmw_opt["map"] == 'yes') { ?>
    <div id="<?php echo esc_attr($owmw_html["main_weather_div"]) ?>-map" class="<?php echo esc_attr($owmw_html["main_weather_div"]) ?>-tabcontent">
        <!-- Weather Map -->
        <?php echo wp_kses($owmw_html["map"], $owmw_opt['allowed_html']); ?>
        <?php echo '<script type="text/javascript">' . wp_kses($owmw_html["map_script"], $owmw_opt['allowed_html']) . '</script>'; ?>
        <?php echo wp_kses($owmw_html["owm_link_last_update_start"], $owmw_opt['allowed_html']); ?>
        <!-- OWM Link -->
        <?php echo wp_kses($owmw_html["owm_link_last_update_start"], $owmw_opt['allowed_html']); ?>
        <?php echo wp_kses($owmw_html["owm_link"], $owmw_opt['allowed_html']); ?>
        <!-- OWM Last Update -->
        <?php echo wp_kses($owmw_html["last_update"], $owmw_opt['allowed_html']); ?>
        <?php echo wp_kses($owmw_html["owm_link_last_update_end"], $owmw_opt['allowed_html']); ?>
    </div>
    <?php } ?>

<!-- End #owm-weather-container -->
<?php echo wp_kses($owmw_html["container"]["end"], $owmw_opt['allowed_html']); ?>

<!-- Alert Modals and Scripts -->
<?php echo wp_kses($owmw_html["alert_modal"], $owmw_opt['allowed_html']); ?>
<?php echo '<script type="text/javascript">' . wp_kses($owmw_html["alert_script"], $owmw_opt['allowed_html']) . '</script>'; ?>

<!-- CSS/Scripts -->
<?php echo '<style type="text/css">' . wp_kses($owmw_html["custom_css"], $owmw_opt['allowed_html']) . '</style>'; ?>
<?php echo '<style type="text/css">' . wp_kses($owmw_html["temperature_unit"], $owmw_opt['allowed_html']) . '</style>'; ?>

<!-- Google Tag Manager -->
<?php echo '<script type="text/javascript">' . wp_kses($owmw_html["gtag"], $owmw_opt['allowed_html']) . '</script>'; ?>


<script>
function <?php echo esc_attr(str_replace("-", "_", $owmw_html["main_weather_div"])) ?>_openWeather(evt, tabName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("<?php echo esc_attr($owmw_html["main_weather_div"]) ?>-tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("<?php echo esc_attr($owmw_html["main_weather_div"]) ?>-tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById("<?php echo esc_attr($owmw_html["main_weather_div"]) ?>-" + tabName).style.display = "block";
  evt.currentTarget.className += " active";
  jQuery( "#<?php echo esc_attr($owmw_html["container_map_div"]); ?>" ).trigger("invalidSize");
}

document.getElementById("<?php echo esc_attr($owmw_html["main_weather_div"]) ?>-defaultOpen").click();
</script>
