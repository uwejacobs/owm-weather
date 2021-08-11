<?php
/****************************************************************************/
/* DO NOT CHANGE THIS TEMPLATE DIRECTLY. IT WILL BE OVERWRITTEN BY UPDATES. */
/****************************************************************************/
/**
 * The WP OWM Weather default template
 *
 */
?>
<!-- Start #wow-weather -->
<?php echo $wow_html["container"]["start"]; ?>
	<!-- Current weather -->
	<?php echo $wow_html["now"]["start"]; ?>
		<?php echo $wow_html["now"]["location_name"]; ?>
		<?php echo $wow_html["now"]["symbol"]; ?>
		<?php echo $wow_html["now"]["temperature"]; ?>
		<?php echo $wow_html["now"]["feels_like"]; ?>
		<?php echo $wow_html["now"]["weather_description"]; ?>
	<?php echo $wow_html["now"]["end"]; ?>

	<!-- Alert button -->
	<?php echo $wow_html["alert_button"]; ?>

	<!-- Today -->
	<?php echo $wow_html["today"]["start"]; ?>
		<?php echo $wow_html["today"]["day"]; ?>
		<?php echo $wow_html["today"]["sun"]; ?>
		<?php echo $wow_html["today"]["moon"]; ?>
	<?php echo $wow_html["today"]["end"]; ?>
	
	<!-- Current infos: wind, humidity, pressure, cloudiness, precipitation -->
	<?php echo $wow_html["info"]["start"]; ?>
		<?php echo $wow_html["info"]["wind"]; ?>
		<?php echo $wow_html["info"]["humidity"]; ?>
		<?php echo $wow_html["info"]["dew_point"]; ?>
		<?php echo $wow_html["info"]["pressure"]; ?>
		<?php echo $wow_html["info"]["cloudiness"]; ?>
		<?php echo $wow_html["info"]["precipitation"]; ?>
		<?php echo $wow_html["info"]["visibility"]; ?>
		<?php echo $wow_html["info"]["uv_index"]; ?>
	<?php echo $wow_html["info"]["end"]; ?>


	<?php if ($wow_opt["hours_forecast_no"] > 0) { ?>
		<!-- Hourly Forecast -->
		<?php echo $wow_html["hour"]["start"]; ?>
		<?php
			for ($i = 0; $i < $wow_opt["hours_forecast_no"]; $i++) {
			    if (isset($wow_html["hour"]["info"][$i])) {
    				echo $wow_html["hour"]["info"][$i];
    			    }
			}
		?>
		<?php echo $wow_html["hour"]["end"]; ?>
	<?php } ?>

	<?php if ($wow_opt["days_forecast_no"] > 0) { ?>	
		<!-- Daily Forecast -->
		<?php echo $wow_html["forecast"]["start"]; ?>
			<?php
				for ($i = 0; $i < $wow_opt["days_forecast_no"]; $i++) {
					echo $wow_html["forecast"]["info"][$i];
				};
			?>	
		<?php echo $wow_html["forecast"]["end"]; ?>
	<?php } ?>
	
	<!-- Weather Map -->
	<?php echo $wow_html["map"]; ?>
	
	<?php echo $wow_html["owm_link_last_update_start"]; ?>
		<!-- OWM Link -->
		<?php echo $wow_html["owm_link"]; ?>
		<!-- OWM Last Update -->
		<?php echo $wow_html["last_update"]; ?>
	<?php echo $wow_html["owm_link_last_update_end"]; ?>

	<!-- Alert Modals -->
	<?php echo $wow_html["alert_modal"]; ?>

	<!-- CSS/Scripts -->
	<?php echo $wow_html["custom_css"]; ?>
	<?php echo $wow_html["temperature_unit"]; ?>
	<?php echo $wow_html["gtag"]; ?>

<!-- End #wow-weather -->
<?php echo $wow_html["container"]["end"]; ?>
