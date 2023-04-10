<?php
/****************************************************************************/
/* DO NOT CHANGE THIS TEMPLATE DIRECTLY. IT WILL BE OVERWRITTEN BY UPDATES. */
/****************************************************************************/
/**
 * The OWM Weather Slider 2 template
 *
 */
?>
<!-- Start #owm-weather -->
<style>
div[class^="owmw-flexslider"] {
    background-color: inherit !important;
    border: none !important;
}
.owmw-hide{ display:none;}
.owmw-show{ display:block;}
</style>

<!-- Start #owm-weather -->
<?php echo wp_kses($owmw_html["container"]["start"], $owmw_opt['allowed_html']); ?>
    <!--div class="custom-navigation d-none">
        <a href="#" class="flex-prev"><?php esc_html_e('Prev', 'owm-weather') ?></a>
        <div class="custom-controls-container"></div>
        <a href="#" class="flex-next"><?php esc_html_e('Next', 'owm-weather') ?></a>
    </div-->

    <button class="owmw-btn-toggle-infos btn btn-info">+</button>

    <div class="owmw-toggle-now owmw-show">
        <!-- Current weather -->
        <?php echo wp_kses($owmw_html["now"]["start"], $owmw_opt['allowed_html']); ?>
            <?php echo wp_kses($owmw_html["now"]["location_name"], $owmw_opt['allowed_html']); ?>
            <?php echo wp_kses($owmw_html["now"]["symbol"], $owmw_opt['allowed_html']); ?>
            <?php echo wp_kses($owmw_html["now"]["temperature"], $owmw_opt['allowed_html']); ?>
            <?php echo wp_kses($owmw_html["now"]["feels_like"], $owmw_opt['allowed_html']); ?>
            <?php echo wp_kses($owmw_html["now"]["weather_description"], $owmw_opt['allowed_html']); ?>
        <?php echo wp_kses($owmw_html["now"]["end"], $owmw_opt['allowed_html']); ?>
    </div>


    <div class="owmw-toggle-infos owmw-hide">
        <!-- Alert button -->
        <?php echo wp_kses($owmw_html["alert_button"], $owmw_opt['allowed_html']); ?>
        <?php echo wp_kses($owmw_html["alert_inline"], $owmw_opt['allowed_html']); ?>

        <!-- Current infos: wind, humidity, dew point, pressure, cloudiness, precipitation, visibility, uv index -->
        <div class="owmw-infos">
            <?php echo wp_kses($owmw_html["today"]["day"], $owmw_opt['allowed_html']); ?>
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

        <!-- OWM Link -->
        <?php echo wp_kses($owmw_html["owm_link_last_update_start"], $owmw_opt['allowed_html']); ?>
        <?php echo wp_kses($owmw_html["owm_link"], $owmw_opt['allowed_html']); ?>
        <!-- OWM Last Update -->
        <?php echo wp_kses($owmw_html["last_update"], $owmw_opt['allowed_html']); ?>
        <?php echo wp_kses($owmw_html["owm_link_last_update_end"], $owmw_opt['allowed_html']); ?>

    </div><!-- End .toggle-infos -->

<!-- End #owm-weather-container -->
<?php echo wp_kses($owmw_html["container"]["end"], $owmw_opt['allowed_html']); ?>

<div id="<?php echo esc_attr($owmw_html["container_map_div"]) ?>-toggle" class="owmw-toggle-infos owmw-hide">
    <!-- Weather Map -->
    <?php echo wp_kses($owmw_html["map"], $owmw_opt['allowed_html']); ?>
    <?php echo '<script type="text/javascript">' . wp_kses($owmw_html["map_script"], $owmw_opt['allowed_html']) . '</script>'; ?>
    <?php echo wp_kses($owmw_html["owm_link_last_update_start"], $owmw_opt['allowed_html']); ?>
</div><!-- End .toggle-infos -->

<!-- Alert Modals and Scripts -->
<?php echo wp_kses($owmw_html["alert_modal"], $owmw_opt['allowed_html']); ?>
<?php echo '<script type="text/javascript">' . wp_kses($owmw_html["alert_script"], $owmw_opt['allowed_html']) . '</script>'; ?>

<!-- CSS/Scripts -->
<?php echo '<style type="text/css">' . wp_kses($owmw_html["custom_css"], $owmw_opt['allowed_html']) . '</style>'; ?>
<?php echo '<style type="text/css">' . wp_kses($owmw_html["temperature_unit"], $owmw_opt['allowed_html']) . '</style>'; ?>

<!-- Google Tag Manager -->
<?php echo '<script type="text/javascript">' . wp_kses($owmw_html["gtag"], $owmw_opt['allowed_html']) . '</script>'; ?>

<script type="text/javascript">
    jQuery(window).ready(function() {
        jQuery( "#<?php echo esc_attr($owmw_html["container_weather_div"]) ?> .owmw-btn-toggle-infos" ).click(function() {
            jQuery( "#<?php echo esc_attr($owmw_html["container_weather_div"]) ?> .owmw-toggle-infos" ).toggleClass( "owmw-show" );
            jQuery( "#<?php echo esc_attr($owmw_html["container_weather_div"]) ?> .owmw-toggle-now" ).toggleClass( "owmw-hide" );
            jQuery( "#<?php echo esc_attr($owmw_html["container_map_div"]) ?>-toggle" ).toggleClass( "owmw-show" );
            jQuery( "#<?php echo esc_attr($owmw_html["container_map_div"]) ?>-toggle" ).toggleClass( "owmw-hide" );
            jQuery( "#<?php echo esc_attr($owmw_html["container_map_div"]); ?>" ).trigger("invalidSize");
            slider("#<?php echo esc_attr($owmw_html["container_weather_div"]) ?>");
            x = jQuery("#<?php echo esc_attr($owmw_html["container_weather_div"]) ?> .owmw-btn-toggle-infos");
            x.html(x.html() == '+' ? '-' : '+');
        });
    });

    function slider(id) {
        jQuery(id+' .owmw-flexslider').flexslider({
            controlsContainer: jQuery(id+" .custom-controls-container"),
            customDirectionNav: jQuery(id+" .custom-navigation a"),
            animation: "slide",
            animationLoop: true,
            itemWidth: 250,
            itemMargin: 5,
            maxItems: 4,
            controlNav: false,
            directionNav: false
        });
        jQuery(id+' .owmw-flexslider-hours').flexslider({
            controlsContainer: jQuery(id+" .custom-controls-container"),
            customDirectionNav: jQuery(id+" .custom-navigation a"),
            animation: "slide",
            animationLoop: true,
            itemWidth: 50,
            itemMargin: 5,
            maxItems: 8,
        controlNav: false,
        directionNav: false
        });
        jQuery(id+' .owmw-flexslider-forecast').flexslider({
            controlsContainer: jQuery(id+" .custom-controls-container"),
            customDirectionNav: jQuery(id+" .custom-navigation a"),
        controlNav: false,
        directionNav: false
        });
    }

</script>
