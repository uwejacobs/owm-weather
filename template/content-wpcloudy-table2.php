<?php
/****************************************************************************/
/* DO NOT CHANGE THIS TEMPLATE DIRECTLY. IT WILL BE OVERWRITTEN BY UPDATES. */
/****************************************************************************/
/**
 * The WP Cloudy table2 template
 *
 */
?>
<!-- Start #wpc-weather -->
<?php echo $wpc_html["container"]["start"]; ?>
	<div class="custom-navigation d-none">
  		<a href="#" class="flex-prev">Prev</a>
  		<div class="custom-controls-container"></div>
  		<a href="#" class="flex-next">Next</a>
	</div>

	<button class="wpc-btn-toggle-infos btn btn-info">+</button>

	<div class="wpc-toggle-now wpc-show">
		<!-- Current weather -->
		<?php echo $wpc_html["now"]["start"]; ?>
			<?php echo $wpc_html["now"]["location_name"]; ?>
			<?php echo $wpc_html["now"]["symbol"]; ?>
			<?php echo $wpc_html["now"]["temperature"]; ?>
			<?php echo $wpc_html["now"]["weather_description"]; ?>
		<?php echo $wpc_html["now"]["end"]; ?>
	</div>
	
	<div class="wpc-toggle-infos wpc-hide">
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
	<?php echo $wpc_html["info"]["end"]; ?>

	
	<?php echo $wpc_html["table"]["hourly"]; ?>
	<?php echo $wpc_html["table"]["daily"]; ?>
	
	<!-- Weather Map -->
	<?php echo $wpc_html["map"]; ?>

    	<!-- OWM Link -->
	<?php echo $wpc_html["owm_link"]; ?>
	
    	<!-- OWM Last Update -->
	<?php echo $wpc_html["last_update"]; ?>

	</div><!-- End .toggle-infos -->

	<!-- Alert Modals -->
	<?php echo $wpc_html["alert_modal"]; ?>

	<!-- CSS/Scripts -->
	<?php echo $wpc_html["custom_css"]; ?>
	<?php echo $wpc_html["temperature_unit"]; ?>
	<?php echo $wpc_html["gtag"]; ?>

<!-- End #wpc-weather -->
<?php echo $wpc_html["container"]["end"]; ?>

<script type="text/javascript" charset="utf-8">
	jQuery(window).ready(function() {
    	jQuery( "#<?php echo $wpc_html["weather_id"] ?> .wpc-btn-toggle-infos" ).click(function() {
	    	jQuery( "#<?php echo $wpc_html["weather_id"] ?> .wpc-toggle-infos" ).toggleClass( "wpc-show" );
		    jQuery( "#<?php echo $wpc_html["weather_id"] ?> .wpc-toggle-now" ).toggleClass( "wpc-hide" );
		    jQuery( "#<?php echo $wpc_html["map_container_id"]; ?>" ).trigger("invalidSize");
            x = jQuery("#<?php echo $wpc_html["weather_id"] ?> .wpc-btn-toggle-infos");
            x.html(x.html() == '+' ? '-' : '+');
	    });
	});

</script>
