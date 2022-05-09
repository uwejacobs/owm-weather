<?php
/****************************************************************************/
/* DO NOT CHANGE THIS TEMPLATE DIRECTLY. IT WILL BE OVERWRITTEN BY UPDATES. */
/****************************************************************************/
/**
 * The OWM Weather Table 2 template
 *
 */
?>
<!-- Start #owm-weather -->
<?php echo wp_kses_post($owmw_html["container"]["start"]); ?>
	<div class="custom-navigation d-none">
  		<a href="#" class="flex-prev"><?php esc_html_e('Prev', 'owm-weather') ?></a>
  		<div class="custom-controls-container"></div>
  		<a href="#" class="flex-next"><?php esc_html_e('Next', 'owm-weather') ?></a>
	</div>

	<button class="owmw-btn-toggle-infos btn btn-info">+</button>

	<div class="owmw-toggle-now owmw-show">
		<!-- Current weather -->
		<?php echo wp_kses_post($owmw_html["now"]["start"]); ?>
			<?php echo wp_kses_post($owmw_html["now"]["location_name"]); ?>
			<?php echo wp_kses($owmw_html["now"]["symbol"], $owmw_opt['allowed_html']); ?>
			<?php echo wp_kses_post($owmw_html["now"]["temperature"]); ?>
			<?php echo wp_kses_post($owmw_html["now"]["feels_like"]); ?>
			<?php echo wp_kses_post($owmw_html["now"]["weather_description"]); ?>
		<?php echo wp_kses_post($owmw_html["now"]["end"]); ?>
	</div>
	
	<div class="owmw-toggle-infos owmw-hide">
    	<!-- Alert button -->
   	    <?php echo wp_kses($owmw_html["alert_button"], $owmw_opt['allowed_html']); ?>
   	    <?php echo wp_kses($owmw_html["alert_inline"], $owmw_opt['allowed_html']); ?>

	<!-- Today -->
	<?php echo wp_kses_post($owmw_html["today"]["start"]); ?>
		<?php echo wp_kses_post($owmw_html["today"]["day"]); ?>
		<?php echo wp_kses($owmw_html["today"]["sun"], $owmw_opt['allowed_html']); ?>
		<?php echo wp_kses($owmw_html["today"]["moon"], $owmw_opt['allowed_html']); ?>
	<?php echo wp_kses_post($owmw_html["today"]["end"]); ?>
	
	<!-- Current infos: wind, humidity, dew_point, pressure, cloudiness, precipitation, visibility, uv index -->
	<?php echo wp_kses_post($owmw_html["info"]["start"]); ?>
		<?php echo wp_kses_post($owmw_html["info"]["wind"]); ?>
		<?php echo wp_kses_post($owmw_html["info"]["humidity"]); ?>
		<?php echo wp_kses_post($owmw_html["info"]["pressure"]); ?>
		<?php echo wp_kses_post($owmw_html["info"]["cloudiness"]); ?>
		<?php echo wp_kses_post($owmw_html["info"]["precipitation"]); ?>
		<?php echo wp_kses_post($owmw_html["info"]["visibility"]); ?>
		<?php echo wp_kses_post($owmw_html["info"]["uv_index"]); ?>
	<?php echo wp_kses_post($owmw_html["info"]["end"]); ?>

	<!-- Hourly Table -->
	<?php echo wp_kses_post($owmw_html["table"]["hourly"]); ?>
	<!-- Daily Table -->
	<?php echo wp_kses_post($owmw_html["table"]["daily"]); ?>
	
	<!-- Weather Map -->
	<?php echo wp_kses_post($owmw_html["map"]); ?>
	<?php echo '<script type="text/javascript">' . wp_kses_post($owmw_html["map_script"]) . '</script>'; ?>

	<!-- OWM Link -->
	<?php echo wp_kses_post($owmw_html["owm_link_last_update_start"]); ?>
	<?php echo wp_kses_post($owmw_html["owm_link"]); ?>
	<!-- OWM Last Update -->
	<?php echo wp_kses_post($owmw_html["last_update"]); ?>
	<?php echo wp_kses_post($owmw_html["owm_link_last_update_end"]); ?>

	<!-- Alert Modals and Scripts -->
	<?php echo wp_kses($owmw_html["alert_modal"], $owmw_opt['allowed_html']); ?>
	<?php echo '<script type="text/javascript">' . wp_kses_post($owmw_html["alert_script"]) . '</script>'; ?>

	<!-- CSS/Scripts -->
	<?php echo '<style type="text/css">' . wp_kses_post($owmw_html["custom_css"]) . '</style>'; ?>
	<?php echo '<style type="text/css">' . wp_kses_post($owmw_html["temperature_unit"]) . '</style>'; ?>

	<!-- Google Tag Manager -->
	<?php echo '<script type="text/javascript">' . wp_kses_post($owmw_html["gtag"]) . '</script>'; ?>

<!-- End #owm-weather -->
<?php echo wp_kses_post($owmw_html["container"]["end"]); ?>

<script type="text/javascript">
	jQuery(window).ready(function() {
    	jQuery( "#<?php echo esc_attr($owmw_html["container_weather_div"]); ?> .owmw-btn-toggle-infos" ).click(function() {
	    	jQuery( "#<?php echo esc_attr($owmw_html["container_weather_div"]); ?> .owmw-toggle-infos" ).toggleClass( "owmw-show" );
		    jQuery( "#<?php echo esc_attr($owmw_html["container_weather_div"]); ?> .owmw-toggle-now" ).toggleClass( "owmw-hide" );
		    jQuery( "#<?php echo esc_attr($owmw_html["container_map_div"]); ?>" ).trigger("invalidSize");
            x = jQuery("#<?php echo esc_attr($owmw_html["container_weather_div"]); ?> .owmw-btn-toggle-infos");
            x.html(x.html() == '+' ? '-' : '+');
	    });
	});

</script>
