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
<?php echo $owmw_html["container"]["start"]; ?>
	<div class="custom-navigation d-none">
  		<a href="#" class="flex-prev">Prev</a>
  		<div class="custom-controls-container"></div>
  		<a href="#" class="flex-next">Next</a>
	</div>

	<button class="owmw-btn-toggle-infos btn btn-info">+</button>

	<div class="owmw-toggle-now owmw-show">
		<!-- Current weather -->
		<?php echo $owmw_html["now"]["start"]; ?>
			<?php echo $owmw_html["now"]["location_name"]; ?>
			<?php echo $owmw_html["now"]["symbol"]; ?>
			<?php echo $owmw_html["now"]["temperature"]; ?>
			<?php echo $owmw_html["now"]["feels_like"]; ?>
			<?php echo $owmw_html["now"]["weather_description"]; ?>
		<?php echo $owmw_html["now"]["end"]; ?>
	</div>
	
	<div class="owmw-toggle-infos owmw-hide">
    	<!-- Alert button -->
   	    <?php echo $owmw_html["alert_button"]; ?>

	<!-- Today -->
	<?php echo $owmw_html["today"]["start"]; ?>
		<?php echo $owmw_html["today"]["day"]; ?>
		<?php echo $owmw_html["today"]["sun"]; ?>
		<?php echo $owmw_html["today"]["moon"]; ?>
	<?php echo $owmw_html["today"]["end"]; ?>
	
	<!-- Current infos: wind, humidity, dew_point, pressure, cloudiness, precipitation, visibility, uv index -->
	<?php echo $owmw_html["info"]["start"]; ?>
		<?php echo $owmw_html["info"]["wind"]; ?>
		<?php echo $owmw_html["info"]["humidity"]; ?>
		<?php echo $owmw_html["info"]["pressure"]; ?>
		<?php echo $owmw_html["info"]["cloudiness"]; ?>
		<?php echo $owmw_html["info"]["precipitation"]; ?>
		<?php echo $owmw_html["info"]["visibility"]; ?>
		<?php echo $owmw_html["info"]["uv_index"]; ?>
	<?php echo $owmw_html["info"]["end"]; ?>

	
	<?php echo $owmw_html["table"]["hourly"]; ?>
	<?php echo $owmw_html["table"]["daily"]; ?>
	
	<!-- Weather Map -->
	<?php echo $owmw_html["map"]; ?>

	<?php echo $owmw_html["owm_link_last_update_start"]; ?>
		<!-- OWM Link -->
		<?php echo $owmw_html["owm_link"]; ?>
		<!-- OWM Last Update -->
		<?php echo $owmw_html["last_update"]; ?>
	<?php echo $owmw_html["owm_link_last_update_end"]; ?>

	</div><!-- End .toggle-infos -->

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
    	jQuery( "#<?php echo $owmw_html["container_weather_div"] ?> .owmw-btn-toggle-infos" ).click(function() {
	    	jQuery( "#<?php echo $owmw_html["container_weather_div"] ?> .owmw-toggle-infos" ).toggleClass( "owmw-show" );
		    jQuery( "#<?php echo $owmw_html["container_weather_div"] ?> .owmw-toggle-now" ).toggleClass( "owmw-hide" );
		    jQuery( "#<?php echo $owmw_html["container_map_div"]; ?>" ).trigger("invalidSize");
            x = jQuery("#<?php echo $owmw_html["container_weather_div"] ?> .owmw-btn-toggle-infos");
            x.html(x.html() == '+' ? '-' : '+');
	    });
	});

</script>
