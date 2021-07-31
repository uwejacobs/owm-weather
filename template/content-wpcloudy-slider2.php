<?php
/**
 * The WP Cloudy Theme 2 template
 *
 */
?>
<!-- Start #wpc-weather -->
<style>
div[class^="wpc-flexslider"] {
    background-color: inherit !important;
    border: none !important;
}
.wpc-hide{ display:none;}
.wpc-show{ display:block;}
</style>

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
			<?php echo $wpc_html["now"]["feels_like"]; ?>
			<?php echo $wpc_html["now"]["weather_description"]; ?>
		<?php echo $wpc_html["now"]["end"]; ?>
	</div>
	
	
	<div class="wpc-toggle-infos wpc-hide">
    	<!-- Alert button -->
   	    <?php echo $wpc_html["alert_button"]; ?>

		<!-- Current infos: wind, humidity, pressure, cloudiness, precipitation, visibility -->
		<div class="wpc-infos">
			<?php echo $wpc_html["today"]["day"]; ?>
			<div class="wpc-flexslider flexslider carousel">
				<ul class="slides">
					<li><?php echo $wpc_html["info"]["wind"]; ?></li>
					<li><?php echo $wpc_html["info"]["humidity"]; ?></li>
					<li><?php echo $wpc_html["info"]["pressure"]; ?></li>
					<li><?php echo $wpc_html["info"]["cloudiness"]; ?></li>
					<li><?php echo $wpc_html["info"]["precipitation"]; ?></li>
					<li><?php echo $wpc_html["info"]["visibility"]; ?></li>
					<li><?php echo $wpc_html["today"]["sun_hor"]; ?></li>
					<li><?php echo $wpc_html["today"]["moon_hor"]; ?></li>
				</ul>
			</div>
		</div>
	
    	<?php if ($wpc_opt["hours_forecast_no"] > 0) { ?>
	    	<!-- Hourly Forecast -->
		    <?php echo $wpc_html["hour"]["start"]; ?>
			<div class="wpc-flexslider-hours flexslider carousel">
	    		<ul class="slides">
    	    	<?php
	    	    	for ($i = 0; $i < $wpc_opt["hours_forecast_no"]; $i++) {
		    	        if (isset($wpc_html["hour"]["info"][$i])) {
		    	            echo "<li>";
    		    	    	echo $wpc_html["hour"]["info"][$i];
		    	            echo "</li>";
        			    }
        			}
    	    	?>
		        <?php echo $wpc_html["hour"]["end"]; ?>
            	<?php
            	}
            	?>
        	    </ul>
        	</div>
	
		<?php if ($wpc_opt["days_forecast_no"] > 0) { ?>
			<!-- Daily Forecast -->
			<div class="wpc-flexslider-forecast flexslider carousel">
				<?php echo $wpc_html["forecast"]["start"]; ?>
				    <ul class="slides">
					<?php
					for ($i = 0; $i < $wpc_opt["days_forecast_no"]; $i++) {
						if ($i % 3  == 0) {
							echo '<li>';
						}

						echo $wpc_html["forecast"]["info"][$i];

						if ($i % 3  == 2) {
							echo '</li>';
						}
					};
					?>
					</ul>
				<?php echo $wpc_html["forecast"]["end"]; ?>
			</div>
		<?php } ?>
	
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
            slider("#<?php echo $wpc_html["weather_id"] ?>");
            x = jQuery("#<?php echo $wpc_html["weather_id"] ?> .wpc-btn-toggle-infos");
            x.html(x.html() == '+' ? '-' : '+');
	    });
	});

    function slider(id) {
		jQuery(id+' .wpc-flexslider').flexslider({
	        controlsContainer: jQuery(id+" .custom-controls-container"),
            customDirectionNav: jQuery(id+" .custom-navigation a"),
            animation: "slide",
            animationLoop: true,
            itemWidth: 250,
            itemMargin: 5,
            maxItems: 4
		});
		jQuery(id+' .wpc-flexslider-hours').flexslider({
	        controlsContainer: jQuery(id+" .custom-controls-container"),
            customDirectionNav: jQuery(id+" .custom-navigation a"),
            animation: "slide",
            animationLoop: true,
            itemWidth: 50,
            itemMargin: 5,
            maxItems: 8
    	});
		jQuery(id+' .wpc-flexslider-forecast').flexslider({
	        controlsContainer: jQuery(id+" .custom-controls-container"),
            customDirectionNav: jQuery(id+" .custom-navigation a"),
		});
    }

</script>
