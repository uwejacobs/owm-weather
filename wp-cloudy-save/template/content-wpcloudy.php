<?php
/**
 * The WP Cloudy default template
 *
 */
?>
<div class="wpc-loading-spinner" style="display:none">
	<img src="<?php echo plugins_url( 'img/ajax-loader.gif', dirname(__FILE__)); ?>" alt="loader"/>
</div>

<!-- Start #wpc-weather -->
<?php echo $wpc_html_container_start; ?>
	<!-- Geolocation Add-on -->
	<?php echo $wpc_html_geolocation; ?>
	
	<!-- Current weather -->
	<?php echo $wpc_html_now_start; ?>
		<?php echo $wpc_html_now_location_name; ?>
		<?php echo $wpc_html_display_now_time_symbol; ?>
		<?php echo $wpc_html_display_now_time_temperature; ?>
	<?php echo $wpc_html_now_end; ?>
	
	<?php echo $wpc_html_weather; ?>

	<!-- Today -->
	<?php echo $wpc_html_today_temp_start; ?>
		<?php echo $wpc_html_today_temp_day; ?>
		<?php echo $wpc_html_today_sun; ?>
	<?php echo $wpc_html_today_temp_end; ?>
	
	<!-- Current infos: wind, humidity, pressure, cloudiness, precipitation -->
	<?php echo $wpc_html_infos_start; ?>
		<?php echo $wpc_html_infos_wind; ?>
		<?php echo $wpc_html_infos_humidity; ?>
		<?php echo $wpc_html_infos_pressure; ?>
		<?php echo $wpc_html_infos_cloudiness; ?>
		<?php echo $wpc_html_infos_precipitation; ?>
	<?php echo $wpc_html_infos_end; ?>


	<?php if ($wpc_html_hour) { ?>
		<!-- Hourly Forecast -->
		<?php echo $wpc_html_hour_start; ?>
		<?php
			if( $wpcloudy_hour_forecast && $wpcloudy_hour_forecast_nd) {
				
				echo $display_hours_0;
				
				for ($i = 0; $i < $wpcloudy_hour_forecast_nd -1; $i++) {
					echo $wpc_html_hour[$i];
				};
				
			}
		?>
		<?php echo $wpc_html_hour_end; ?>
	<?php } ?>
	<?php if ($wpc_html_forecast) { ?>	
		<!-- Daily Forecast -->
		<?php echo $wpc_html_forecast_start; ?>
			<?php
				if( $wpcloudy_forecast && $wpcloudy_forecast_nd) {
					
					for ($i = 0; $i < $wpcloudy_forecast_nd; $i++) {
						echo $wpc_html_forecast[$i];
					};
					
				}
			?>	
		<?php echo $wpc_html_forecast_end; ?>
	<?php } ?>
	
	<!-- Weather Map -->
	<?php echo $wpc_html_map; ?>
	
	<!-- OWM Link -->
	<?php echo $wpc_html_owm_link; ?>
	
	<!-- OWM Last Update -->
	<?php echo $wpc_html_last_update; ?>

	<!-- CSS -->
	<?php echo $wpc_html_custom_css; ?>
	<?php echo $wpc_html_css3_anims; ?>
	<?php echo $wpc_html_temp_unit_metric; ?>
	<?php echo $wpc_html_temp_unit_imperial; ?>

<!-- End #wpc-weather -->
<?php echo $wpc_html_container_end; ?>