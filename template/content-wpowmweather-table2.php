<?php
/****************************************************************************/
/* DO NOT CHANGE THIS TEMPLATE DIRECTLY. IT WILL BE OVERWRITTEN BY UPDATES. */
/****************************************************************************/
/**
 * The WP OWM Weather Table 2 template
 *
 */
?>
<!-- Start #wow-weather -->
<?php echo $wow_html["container"]["start"]; ?>
	<div class="custom-navigation d-none">
  		<a href="#" class="flex-prev">Prev</a>
  		<div class="custom-controls-container"></div>
  		<a href="#" class="flex-next">Next</a>
	</div>

	<button class="wow-btn-toggle-infos btn btn-info">+</button>

	<div class="wow-toggle-now wow-show">
		<!-- Current weather -->
		<?php echo $wow_html["now"]["start"]; ?>
			<?php echo $wow_html["now"]["location_name"]; ?>
			<?php echo $wow_html["now"]["symbol"]; ?>
			<?php echo $wow_html["now"]["temperature"]; ?>
			<?php echo $wow_html["now"]["feels_like"]; ?>
			<?php echo $wow_html["now"]["weather_description"]; ?>
		<?php echo $wow_html["now"]["end"]; ?>
	</div>
	
	<div class="wow-toggle-infos wow-hide">
    	<!-- Alert button -->
   	    <?php echo $wow_html["alert_button"]; ?>

	<!-- Today -->
	<?php echo $wow_html["today"]["start"]; ?>
		<?php echo $wow_html["today"]["day"]; ?>
		<?php echo $wow_html["today"]["sun"]; ?>
		<?php echo $wow_html["today"]["moon"]; ?>
	<?php echo $wow_html["today"]["end"]; ?>
	
	<!-- Current infos: wind, humidity, dew_point, pressure, cloudiness, precipitation, visibility, uv index -->
	<?php echo $wow_html["info"]["start"]; ?>
		<?php echo $wow_html["info"]["wind"]; ?>
		<?php echo $wow_html["info"]["humidity"]; ?>
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

    	<!-- OWM Link -->
	<?php echo $wow_html["owm_link"]; ?>
	
    	<!-- OWM Last Update -->
	<?php echo $wow_html["last_update"]; ?>

	</div><!-- End .toggle-infos -->

	<!-- Alert Modals -->
	<?php echo $wow_html["alert_modal"]; ?>

	<!-- CSS/Scripts -->
	<?php echo $wow_html["custom_css"]; ?>
	<?php echo $wow_html["temperature_unit"]; ?>
	<?php echo $wow_html["gtag"]; ?>

<!-- End #wow-weather -->
<?php echo $wow_html["container"]["end"]; ?>

<script type="text/javascript" charset="utf-8">
	jQuery(window).ready(function() {
    	jQuery( "#<?php echo $wow_opt["container_weather_div"] ?> .wow-btn-toggle-infos" ).click(function() {
	    	jQuery( "#<?php echo $wow_opt["container_weather_div"] ?> .wow-toggle-infos" ).toggleClass( "wow-show" );
		    jQuery( "#<?php echo $wow_opt["container_weather_div"] ?> .wow-toggle-now" ).toggleClass( "wow-hide" );
		    jQuery( "#<?php echo $wow_opt["container_map_div"]; ?>" ).trigger("invalidSize");
            x = jQuery("#<?php echo $wow_opt["container_weather_div"] ?> .wow-btn-toggle-infos");
            x.html(x.html() == '+' ? '-' : '+');
	    });
	});

</script>
