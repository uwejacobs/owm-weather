<?php
/****************************************************************************/
/* DO NOT CHANGE THIS TEMPLATE DIRECTLY. IT WILL BE OVERWRITTEN BY UPDATES. */
/****************************************************************************/
/**
 * The OWM Weather default template
 *
 */
?>
<!-- Start #owm-weather-container -->
<?php echo wp_kses($owmw_html["container"]["start"], $owmw_opt['allowed_html']); ?>
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
        <?php echo wp_kses($owmw_html["today"]["sun"], $owmw_opt['allowed_html']); ?>
        <?php echo wp_kses($owmw_html["today"]["moon"], $owmw_opt['allowed_html']); ?>
    <?php echo wp_kses($owmw_html["today"]["end"], $owmw_opt['allowed_html']); ?>

    <!-- Current infos: wind, humidity, pressure, cloudiness, precipitation -->
    <?php echo wp_kses($owmw_html["info"]["start"], $owmw_opt['allowed_html']); ?>
        <?php echo wp_kses($owmw_html["info"]["wind"], $owmw_opt['allowed_html']); ?>
        <?php echo wp_kses($owmw_html["info"]["humidity"], $owmw_opt['allowed_html']); ?>
        <?php echo wp_kses($owmw_html["info"]["dew_point"], $owmw_opt['allowed_html']); ?>
        <?php echo wp_kses($owmw_html["info"]["pressure"], $owmw_opt['allowed_html']); ?>
        <?php echo wp_kses($owmw_html["info"]["cloudiness"], $owmw_opt['allowed_html']); ?>
        <?php echo wp_kses($owmw_html["info"]["precipitation"], $owmw_opt['allowed_html']); ?>
        <?php echo wp_kses($owmw_html["info"]["visibility"], $owmw_opt['allowed_html']); ?>
        <?php echo wp_kses($owmw_html["info"]["uv_index"], $owmw_opt['allowed_html']); ?>
    <?php echo wp_kses($owmw_html["info"]["end"], $owmw_opt['allowed_html']); ?>

    <?php if ($owmw_opt["hours_forecast_no"] > 0) { ?>
        <!-- Hourly Forecast -->
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
        <?php echo wp_kses($owmw_html["forecast"]["start"], $owmw_opt['allowed_html']); ?>
            <?php
            for ($i = 0; $i < $owmw_opt["days_forecast_no"]; $i++) {
                echo wp_kses($owmw_html["forecast"]["info"][$i], $owmw_opt['allowed_html']);
            };
            ?>
        <?php echo wp_kses($owmw_html["forecast"]["end"], $owmw_opt['allowed_html']); ?>
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
