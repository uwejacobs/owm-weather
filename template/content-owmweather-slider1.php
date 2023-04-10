<?php
/****************************************************************************/
/* DO NOT CHANGE THIS TEMPLATE DIRECTLY. IT WILL BE OVERWRITTEN BY UPDATES. */
/****************************************************************************/
/**
 * The OWM Weather Slider 1 template
 *
 */
?>
<style>
    div[class^="owmw-flexslider"] {
        background-color: inherit !important;
        border: none !important;
    }
.owmw-infos .owmw-wind,
.owmw-infos .owmw-humidity,
.owmw-infos .owmw-dew-point,
.owmw-infos .owmw-pressure,
.owmw-infos .owmw-cloudiness,
.owmw-infos .owmw-precipitation,
.owmw-infos .owmw-visibility,
.owmw-infos .owmw-uv_index {
    width: 100%;
}
</style>
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

    <div class="owmw-toggle-infos">

        <!-- Current infos: wind, humidity, dew_point, pressure, cloudiness, precipitation, visibility, uv index -->
        <div class="owmw-infos">
            <div class="owmw-flexslider flexslider carousel">
                <ul class="slides">
                    <li><?php echo wp_kses($owmw_html["info"]["wind"], $owmw_opt['allowed_html']); ?></li>
                    <li><?php echo wp_kses($owmw_html["info"]["humidity"], $owmw_opt['allowed_html']); ?></li>
                    <li><?php echo wp_kses($owmw_html["info"]["dew_point"], $owmw_opt['allowed_html']); ?></li>
                    <li><?php echo wp_kses($owmw_html["info"]["pressure"], $owmw_opt['allowed_html']); ?></li>
                    <li><?php echo wp_kses($owmw_html["info"]["cloudiness"], $owmw_opt['allowed_html']); ?></li>
                    <li><?php echo wp_kses($owmw_html["info"]["precipitation"], $owmw_opt['allowed_html']); ?></li>
                    <li><?php echo wp_kses($owmw_html["info"]["visibility"], $owmw_opt['allowed_html']); ?></li>
                    <li><?php echo wp_kses($owmw_html["info"]["uv_index"], $owmw_opt['allowed_html']); ?></li>
                    <li><?php echo wp_kses($owmw_html["today"]["sun_hor"], $owmw_opt['allowed_html']); ?></li>
                    <li><?php echo wp_kses($owmw_html["today"]["moon_hor"], $owmw_opt['allowed_html']); ?></li>
                </ul>
            </div>
        </div>

        <?php if ($owmw_opt["hours_forecast_no"] > 0) { ?>
            <!-- Hourly Forecast -->
            <?php echo wp_kses($owmw_html["hour"]["start"], $owmw_opt['allowed_html']); ?>
            <div class="owmw-flexslider-hours flexslider carousel">
                <ul class="slides">
                <?php
                for ($i = 0; $i < $owmw_opt["hours_forecast_no"]; $i++) {
                    if (isset($owmw_html["hour"]["info"][$i])) {
                        echo "<li>";
                        echo wp_kses($owmw_html["hour"]["info"][$i], $owmw_opt['allowed_html']);
                        echo "</li>";
                    }
                }
                ?>
                <?php echo wp_kses($owmw_html["hour"]["end"], $owmw_opt['allowed_html']); ?>
                <?php
        }
        ?>
                </ul>
            </div>

        <?php if ($owmw_opt["days_forecast_no"] > 0) { ?>
            <!-- Daily Forecast -->
            <div class="owmw-flexslider-forecast flexslider carousel">
                <?php echo wp_kses($owmw_html["forecast"]["start"], $owmw_opt['allowed_html']); ?>
                    <ul class="slides">
                    <?php
                    for ($i = 0; $i < $owmw_opt["days_forecast_no"]; $i++) {
                        if ($i % 3  == 0) {
                            echo '<li>';
                        }

                        echo wp_kses($owmw_html["forecast"]["info"][$i], $owmw_opt['allowed_html']);

                        if ($i % 3  == 2) {
                            echo '</li>';
                        }
                    };
                    ?>
                    </ul>
                <?php echo wp_kses($owmw_html["forecast"]["end"], $owmw_opt['allowed_html']); ?>
            </div>
        <?php } ?>
    </div><!-- End .toggle-infos -->

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

<!-- End #owm-weather -->
<?php echo wp_kses($owmw_html["container"]["end"], $owmw_opt['allowed_html']); ?>

<script type="text/javascript">
    jQuery(window).ready(function() {
        jQuery('#<?php echo esc_attr($owmw_html["container_weather_div"]) ?> .owmw-flexslider').flexslider({
            controlsContainer: jQuery("#<?php echo esc_attr($owmw_html["container_weather_div"]) ?> .custom-controls-container"),
            customDirectionNav: jQuery("#<?php echo esc_attr($owmw_html["container_weather_div"]) ?> .custom-navigation a"),
            animation: "slide",
            animationLoop: true,
            itemWidth: 250,
            itemMargin: 5,
        maxItems: 4,
        controlNav: false,
        directionNav: false
        });
        jQuery('#<?php echo esc_attr($owmw_html["container_weather_div"]) ?> .owmw-flexslider-hours').flexslider({
            controlsContainer: jQuery("#<?php echo esc_attr($owmw_html["container_weather_div"]) ?> .custom-controls-container"),
            customDirectionNav: jQuery("#<?php echo esc_attr($owmw_html["container_weather_div"]) ?> .custom-navigation a"),
            animation: "slide",
            animationLoop: true,
            itemWidth: 50,
            itemMargin: 5,
            maxItems: 8,
        controlNav: false,
        directionNav: false
        });
        jQuery('#<?php echo esc_attr($owmw_html["container_weather_div"]) ?> .owmw-flexslider-forecast').flexslider({
            controlsContainer: jQuery("#<?php echo esc_attr($owmw_html["container_weather_div"]) ?> .custom-controls-container"),
            customDirectionNav: jQuery("#<?php echo esc_attr($owmw_html["container_weather_div"]) ?> .custom-navigation a"),
        controlNav: false,
        directionNav: false
        });
    });
</script>
