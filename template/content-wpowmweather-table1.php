<?php
/****************************************************************************/
/* DO NOT CHANGE THIS TEMPLATE DIRECTLY. IT WILL BE OVERWRITTEN BY UPDATES. */
/****************************************************************************/
/**
 * The WP OWM Weather Table1 template
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
	
	<!-- Current infos: wind, humidity, dew point, pressure, cloudiness, precipitation, uv index -->
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

	<?php echo $wow_html["table"]["hourly"]; ?>
	<?php echo $wow_html["table"]["daily"]; ?>

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
