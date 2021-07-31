<?php
/****************************************************************************/
/* DO NOT CHANGE THIS TEMPLATE DIRECTLY. IT WILL BE OVERWRITTEN BY UPDATES. */
/****************************************************************************/
/**
 * The WP Cloudy Table1 template
 *
 */
?>
<!-- Start #wpc-weather -->
<?php echo $wpc_html["container"]["start"]; ?>
	<!-- Current weather -->
	<?php echo $wpc_html["now"]["start"]; ?>
		<?php echo $wpc_html["now"]["location_name"]; ?>
		<?php echo $wpc_html["now"]["symbol"]; ?>
		<?php echo $wpc_html["now"]["temperature"]; ?>
		<?php echo $wpc_html["now"]["feels_like"]; ?>
		<?php echo $wpc_html["now"]["weather_description"]; ?>
	<?php echo $wpc_html["now"]["end"]; ?>

	<!-- Alert button -->
	<?php echo $wpc_html["alert_button"]; ?>

	<!-- Today -->
	<?php echo $wpc_html["today"]["start"]; ?>
		<?php echo $wpc_html["today"]["day"]; ?>
		<?php echo $wpc_html["today"]["sun"]; ?>
		<?php echo $wpc_html["today"]["moon"]; ?>
	<?php echo $wpc_html["today"]["end"]; ?>
	
	<!-- Current infos: wind, humidity, pressure, cloudiness, precipitation -->
	<?php echo $wpc_html["info"]["start"]; ?>
		<?php echo $wpc_html["info"]["wind"]; ?>
		<?php echo $wpc_html["info"]["humidity"]; ?>
		<?php echo $wpc_html["info"]["pressure"]; ?>
		<?php echo $wpc_html["info"]["cloudiness"]; ?>
		<?php echo $wpc_html["info"]["precipitation"]; ?>
		<?php echo $wpc_html["info"]["visibility"]; ?>
	<?php echo $wpc_html["info"]["end"]; ?>

	<?php echo $wpc_html["table"]["hourly"]; ?>
	<?php echo $wpc_html["table"]["daily"]; ?>

	<!-- Weather Map -->
	<?php echo $wpc_html["map"]; ?>
	
	<!-- OWM Link -->
	<?php echo $wpc_html["owm_link"]; ?>
	
	<!-- OWM Last Update -->
	<?php echo $wpc_html["last_update"]; ?>

	<!-- Alert Modals -->
	<?php echo $wpc_html["alert_modal"]; ?>

	<!-- CSS/Scripts -->
	<?php echo $wpc_html["custom_css"]; ?>
	<?php echo $wpc_html["temperature_unit"]; ?>
	<?php echo $wpc_html["gtag"]; ?>

<!-- End #wpc-weather -->
<?php echo $wpc_html["container"]["end"]; ?>
