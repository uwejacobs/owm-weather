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
<!-- Start #owm-weather -->
<?php echo $owmw_html["container"]["start"]; ?>
	<div class="custom-navigation d-none">
  	<a href="#" class="flex-prev">Prev</a>
  	<div class="custom-controls-container"></div>
  	<a href="#" class="flex-next">Next</a>
	</div>

	<!-- Current weather -->
	<?php echo $owmw_html["now"]["start"]; ?>
		<?php echo $owmw_html["now"]["location_name"]; ?>
		<?php echo $owmw_html["now"]["symbol"]; ?>
		<?php echo $owmw_html["now"]["temperature"]; ?>
		<?php echo $owmw_html["now"]["feels_like"]; ?>
		<?php echo $owmw_html["now"]["weather_description"]; ?>
	<?php echo $owmw_html["now"]["end"]; ?>

	<!-- Alert button -->
   	<?php echo $owmw_html["alert_button"]; ?>

	<div class="owmw-toggle-infos">

		<!-- Current infos: wind, humidity, dew_point, pressure, cloudiness, precipitation, visibility, uv index -->
		<div class="owmw-infos">
			<div class="owmw-flexslider flexslider carousel">
				<ul class="slides">
					<li><?php echo $owmw_html["info"]["wind"]; ?></li>
					<li><?php echo $owmw_html["info"]["humidity"]; ?></li>
					<li><?php echo $owmw_html["info"]["dew_point"]; ?></li>
					<li><?php echo $owmw_html["info"]["pressure"]; ?></li>
					<li><?php echo $owmw_html["info"]["cloudiness"]; ?></li>
					<li><?php echo $owmw_html["info"]["precipitation"]; ?></li>
					<li><?php echo $owmw_html["info"]["visibility"]; ?></li>
					<li><?php echo $owmw_html["info"]["uv_index"]; ?></li>
					<li><?php echo $owmw_html["today"]["sun_hor"]; ?></li>
					<li><?php echo $owmw_html["today"]["moon_hor"]; ?></li>
				</ul>
			</div>
		</div>

    	<?php if ($owmw_opt["hours_forecast_no"] > 0) { ?>
	    	<!-- Hourly Forecast -->
		    <?php echo $owmw_html["hour"]["start"]; ?>
			<div class="owmw-flexslider-hours flexslider carousel">
	    		<ul class="slides">
    	    	<?php
	    	    	for ($i = 0; $i < $owmw_opt["hours_forecast_no"]; $i++) {
		    	        if (isset($owmw_html["hour"]["info"][$i])) {
		    	            echo "<li>";
    		    	    	echo $owmw_html["hour"]["info"][$i];
		    	            echo "</li>";
        			    }
        			}
    	    	?>
		        <?php echo $owmw_html["hour"]["end"]; ?>
            	<?php
            	}
            	?>
        	    </ul>
        	</div>

		<?php if ($owmw_opt["days_forecast_no"] > 0) { ?>
			<!-- Daily Forecast -->
			<div class="owmw-flexslider-forecast flexslider carousel">
				<?php echo $owmw_html["forecast"]["start"]; ?>
				    <ul class="slides">
					<?php
					for ($i = 0; $i < $owmw_opt["days_forecast_no"]; $i++) {
						if ($i % 3  == 0) {
							echo '<li>';
						}

						echo $owmw_html["forecast"]["info"][$i];

						if ($i % 3  == 2) {
							echo '</li>';
						}
					};
					?>
					</ul>
				<?php echo $owmw_html["forecast"]["end"]; ?>
			</div>
		<?php } ?>
	</div><!-- End .toggle-infos -->

	<!-- Weather Map -->
	<?php echo $owmw_html["map"]; ?>

	<?php echo $owmw_html["owm_link_last_update_start"]; ?>
		<!-- OWM Link -->
		<?php echo $owmw_html["owm_link"]; ?>
		<!-- OWM Last Update -->
		<?php echo $owmw_html["last_update"]; ?>
	<?php echo $owmw_html["owm_link_last_update_end"]; ?>

	<!-- Alert Modals -->
	<?php echo $owmw_html["alert_modal"]; ?>

	<!-- CSS/Scripts -->
	<?php echo $owmw_html["custom_css"]; ?>
	<?php echo $owmw_html["temperature_unit"]; ?>
	<?php echo $owmw_html["gtag"]; ?>

<!-- End #owm-weather -->
<?php echo $owmw_html["container"]["end"]; ?>

<script type="text/javascript" charset="utf-8">
	jQuery(window).ready(function() {
		jQuery('#<?php echo $owmw_html["container_weather_div"] ?> .owmw-flexslider').flexslider({
	        controlsContainer: jQuery("#<?php echo $owmw_html["container_weather_div"] ?> .custom-controls-container"),
            customDirectionNav: jQuery("#<?php echo $owmw_html["container_weather_div"] ?> .custom-navigation a"),
            animation: "slide",
            animationLoop: true,
            itemWidth: 250,
            itemMargin: 5,
            maxItems: 4
		});
		jQuery('#<?php echo $owmw_html["container_weather_div"] ?> .owmw-flexslider-hours').flexslider({
	        controlsContainer: jQuery("#<?php echo $owmw_html["container_weather_div"] ?> .custom-controls-container"),
            customDirectionNav: jQuery("#<?php echo $owmw_html["container_weather_div"] ?> .custom-navigation a"),
            animation: "slide",
            animationLoop: true,
            itemWidth: 50,
            itemMargin: 5,
            maxItems: 8
    	});
		jQuery('#<?php echo $owmw_html["container_weather_div"] ?> .owmw-flexslider-forecast').flexslider({
	        controlsContainer: jQuery("#<?php echo $owmw_html["container_weather_div"] ?> .custom-controls-container"),
            customDirectionNav: jQuery("#<?php echo $owmw_html["container_weather_div"] ?> .custom-navigation a"),
		});
	});
</script>
