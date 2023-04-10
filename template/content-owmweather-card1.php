<?php
/****************************************************************************/
/* DO NOT CHANGE THIS TEMPLATE DIRECTLY. IT WILL BE OVERWRITTEN BY UPDATES. */
/****************************************************************************/
/**
 * The OWM Weather card1 template
 *
 */
?>
<!-- Start #owm-weather styles -->
<style>
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
</style>
<!-- Start #owm-weather-container -->
<?php echo wp_kses($owmw_html["container"]["start"], $owmw_opt['allowed_html']); ?>
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
                <div class="fs-3 opacity-75 mt-3">Current</div>
                <?php echo wp_kses($owmw_html["now"]["start_card"], $owmw_opt['allowed_html']);
                      echo wp_kses($owmw_html["now"]["info_card"], $owmw_opt['allowed_html']);
                      echo wp_kses($owmw_html["now"]["end_card"], $owmw_opt['allowed_html']);
                ?>
            </div>
        <?php } ?>

    </div>

    <?php if ($owmw_opt["hours_forecast_no"] > 0) { ?>
        <!-- Hourly Forecast -->
        <?php echo '<div class="fs-3 opacity-75 mt-3">Hourly</div>'; ?>
        <?php echo wp_kses($owmw_html["hour"]["start"], $owmw_opt['allowed_html']); ?>
        <?php
        for ($i = 0; $i < $owmw_opt["hours_forecast_no"]; $i++) {
            if (isset($owmw_html["hour"]["info"][$i])) {
                echo wp_kses($owmw_html["hour"]["info"][$i], $owmw_opt['allowed_html']);
            }
        }
        ?>
        <?php echo wp_kses($owmw_html["hour"]["end"], $owmw_opt['allowed_html']); ?>
    <?php } ?>

    <?php if ($owmw_opt["days_forecast_no"] > 0) { ?>
        <!-- Daily Forecast -->
        <?php echo '<div class="fs-3 opacity-75 mt-3">Daily</div>'; ?>
        <?php echo wp_kses($owmw_html["forecast"]["start_card"], $owmw_opt['allowed_html']); ?>
            <?php
            for ($i = 0; $i < $owmw_opt["days_forecast_no"]; $i++) {
                echo wp_kses($owmw_html["forecast"]["info_card"][$i], $owmw_opt['allowed_html']);
            };
            ?>
        <?php echo wp_kses($owmw_html["forecast"]["end_card"], $owmw_opt['allowed_html']); ?>
    <?php } ?>

    <!-- OWM Link -->
    <?php echo wp_kses($owmw_html["owm_link_last_update_start"], $owmw_opt['allowed_html']); ?>
    <?php echo wp_kses($owmw_html["owm_link"], $owmw_opt['allowed_html']); ?>
    <!-- OWM Last Update -->
    <?php echo wp_kses($owmw_html["last_update"], $owmw_opt['allowed_html']); ?>
    <?php echo wp_kses($owmw_html["owm_link_last_update_end"], $owmw_opt['allowed_html']); ?>

<!-- End #owm-weather-container -->
<?php echo wp_kses($owmw_html["container"]["end"], $owmw_opt['allowed_html']); ?>

<!-- Weather Map -->
<?php echo wp_kses($owmw_html["map"], $owmw_opt['allowed_html']); ?>
<?php echo '<script type="text/javascript">' . wp_kses($owmw_html["map_script"], $owmw_opt['allowed_html']) . '</script>'; ?>
<?php echo wp_kses($owmw_html["owm_link_last_update_start"], $owmw_opt['allowed_html']); ?>

<!-- Alert Modals and Scripts -->
<?php echo wp_kses($owmw_html["alert_modal"], $owmw_opt['allowed_html']); ?>
<?php echo '<script type="text/javascript">' . wp_kses($owmw_html["alert_script"], $owmw_opt['allowed_html']) . '</script>'; ?>

<!-- CSS/Scripts -->
<?php echo '<style type="text/css">' . wp_kses($owmw_html["custom_css"], $owmw_opt['allowed_html']) . '</style>'; ?>
<?php echo '<style type="text/css">' . wp_kses($owmw_html["temperature_unit"], $owmw_opt['allowed_html']) . '</style>'; ?>

<!-- Google Tag Manager -->
<?php echo '<script type="text/javascript">' . wp_kses($owmw_html["gtag"], $owmw_opt['allowed_html']) . '</script>'; ?>
