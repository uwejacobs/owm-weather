<?php
/****************************************************************************/
/* DO NOT CHANGE THIS TEMPLATE DIRECTLY. IT WILL BE OVERWRITTEN BY UPDATES. */
/****************************************************************************/
/**
 * The OWM Weather default template
 *
 */
?>
<!-- Start #owm-weather -->
<?php echo $owmw_html["container"]["start"]; ?>
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

	<!-- Today -->
	<?php echo $owmw_html["today"]["start"]; ?>
		<?php echo $owmw_html["today"]["day"]; ?>
		<?php echo $owmw_html["today"]["sun"]; ?>
		<?php echo $owmw_html["today"]["moon"]; ?>
	<?php echo $owmw_html["today"]["end"]; ?>
	
	<!-- Current infos: wind, humidity, pressure, cloudiness, precipitation -->
	<?php echo $owmw_html["info"]["start"]; ?>
		<?php echo $owmw_html["info"]["wind"]; ?>
		<?php echo $owmw_html["info"]["humidity"]; ?>
		<?php echo $owmw_html["info"]["dew_point"]; ?>
		<?php echo $owmw_html["info"]["pressure"]; ?>
		<?php echo $owmw_html["info"]["cloudiness"]; ?>
		<?php echo $owmw_html["info"]["precipitation"]; ?>
		<?php echo $owmw_html["info"]["visibility"]; ?>
		<?php echo $owmw_html["info"]["uv_index"]; ?>
	<?php echo $owmw_html["info"]["end"]; ?>


	<?php if ($owmw_opt["hours_forecast_no"] > 0) { ?>
		<!-- Hourly Forecast -->
		<?php echo $owmw_html["hour"]["start"]; ?>
		<?php
			for ($i = 0; $i < $owmw_opt["hours_forecast_no"]; $i++) {
			    if (isset($owmw_html["hour"]["info"][$i])) {
    				echo $owmw_html["hour"]["info"][$i];
    			    }
			}
		?>
		<?php echo $owmw_html["hour"]["end"]; ?>
	<?php } ?>

	<?php if ($owmw_opt["days_forecast_no"] > 0) { ?>	
		<!-- Daily Forecast -->
		<?php echo $owmw_html["forecast"]["start"]; ?>
			<?php
				for ($i = 0; $i < $owmw_opt["days_forecast_no"]; $i++) {
					echo $owmw_html["forecast"]["info"][$i];
				};
			?>	
		<?php echo $owmw_html["forecast"]["end"]; ?>
	<?php } ?>
	
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
