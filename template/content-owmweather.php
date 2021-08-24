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
<?php echo wp_kses_post($owmw_html["container"]["start"]); ?>
	<!-- Current weather -->
	<?php echo wp_kses_post($owmw_html["now"]["start"]); ?>
		<?php echo wp_kses_post($owmw_html["now"]["location_name"]); ?>
		<?php echo wp_kses($owmw_html["now"]["symbol"], $owmw_opt['allowed_html']); ?>
		<?php echo wp_kses_post($owmw_html["now"]["temperature"]); ?>
		<?php echo wp_kses_post($owmw_html["now"]["feels_like"]); ?>
		<?php echo wp_kses_post($owmw_html["now"]["weather_description"]); ?>
	<?php echo wp_kses_post($owmw_html["now"]["end"]); ?>

	<!-- Alert button -->
	<?php echo wp_kses_post($owmw_html["alert_button"]); ?>

	<!-- Today -->
	<?php echo wp_kses_post($owmw_html["today"]["start"]); ?>
		<?php echo wp_kses_post($owmw_html["today"]["day"]); ?>
		<?php echo wp_kses($owmw_html["today"]["sun"], $owmw_opt['allowed_html']); ?>
		<?php echo wp_kses($owmw_html["today"]["moon"], $owmw_opt['allowed_html']); ?>
	<?php echo wp_kses_post($owmw_html["today"]["end"]); ?>
	
	<!-- Current infos: wind, humidity, pressure, cloudiness, precipitation -->
	<?php echo wp_kses_post($owmw_html["info"]["start"]); ?>
		<?php echo wp_kses_post($owmw_html["info"]["wind"]); ?>
		<?php echo wp_kses_post($owmw_html["info"]["humidity"]); ?>
		<?php echo wp_kses_post($owmw_html["info"]["dew_point"]); ?>
		<?php echo wp_kses_post($owmw_html["info"]["pressure"]); ?>
		<?php echo wp_kses_post($owmw_html["info"]["cloudiness"]); ?>
		<?php echo wp_kses_post($owmw_html["info"]["precipitation"]); ?>
		<?php echo wp_kses_post($owmw_html["info"]["visibility"]); ?>
		<?php echo wp_kses_post($owmw_html["info"]["uv_index"]); ?>
	<?php echo wp_kses_post($owmw_html["info"]["end"]); ?>

	<?php if ($owmw_opt["hours_forecast_no"] > 0) { ?>
		<!-- Hourly Forecast -->
		<?php echo wp_kses_post($owmw_html["hour"]["start"]); ?>
		<?php
			for ($i = 0; $i < $owmw_opt["hours_forecast_no"]; $i++) {
			    if (isset($owmw_html["hour"]["info"][$i])) {
    				echo wp_kses_post($owmw_html["hour"]["info"][$i]);
    			    }
			}
		?>
		<?php echo wp_kses_post($owmw_html["hour"]["end"]); ?>
	<?php } ?>

	<?php if ($owmw_opt["days_forecast_no"] > 0) { ?>	
		<!-- Daily Forecast -->
		<?php echo wp_kses_post($owmw_html["forecast"]["start"]); ?>
			<?php
				for ($i = 0; $i < $owmw_opt["days_forecast_no"]; $i++) {
					echo wp_kses_post($owmw_html["forecast"]["info"][$i]);
				};
			?>	
		<?php echo wp_kses_post($owmw_html["forecast"]["end"]); ?>
	<?php } ?>
	
	<!-- Weather Map -->
	<?php echo wp_kses_post($owmw_html["map"]); ?>
	<?php echo '<script type="text/javascript">' . wp_kses_post($owmw_html["map_script"]) . '</script>'; ?>
	<?php echo wp_kses_post($owmw_html["owm_link_last_update_start"]); ?>

	<!-- OWM Link -->
	<?php echo wp_kses_post($owmw_html["owm_link"]); ?>
	<!-- OWM Last Update -->
	<?php echo wp_kses_post($owmw_html["last_update"]); ?>
	<?php echo wp_kses_post($owmw_html["owm_link_last_update_end"]); ?>

	<!-- Alert Modals -->
	<?php echo wp_kses_post($owmw_html["alert_modal"]); ?>

	<!-- CSS/Scripts -->
	<?php echo '<style type="text/css">' . wp_kses_post($owmw_html["custom_css"]) . '</style>'; ?>
	<?php echo '<style type="text/css">' . wp_kses_post($owmw_html["temperature_unit"]) . '</style>'; ?>

	<!-- Google Tag Manager -->
	<?php echo '<script type="text/javascript">' . wp_kses_post($owmw_html["gtag"]) . '</script>'; ?>

<!-- End #owm-weather -->
<?php echo wp_kses_post($owmw_html["container"]["end"]); ?>
