<?php
/****************************************************************************/
/* DO NOT CHANGE THIS TEMPLATE DIRECTLY. IT WILL BE OVERWRITTEN BY UPDATES. */
/****************************************************************************/
/**
 * The WP OWM Weather Slider 2 template
 *
 */
?>
<!-- Start #wow-weather -->
<style>
div[class^="wow-flexslider"] {
    background-color: inherit !important;
    border: none !important;
}
.wow-hide{ display:none;}
.wow-show{ display:block;}
</style>

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

		<!-- Current infos: wind, humidity, dew point, pressure, cloudiness, precipitation, visibility, uv index -->
		<div class="wow-infos">
			<?php echo $wow_html["today"]["day"]; ?>
			<div class="wow-flexslider flexslider carousel">
				<ul class="slides">
					<li><?php echo $wow_html["info"]["wind"]; ?></li>
					<li><?php echo $wow_html["info"]["humidity"]; ?></li>
					<li><?php echo $wow_html["info"]["dew_point"]; ?></li>
					<li><?php echo $wow_html["info"]["pressure"]; ?></li>
					<li><?php echo $wow_html["info"]["cloudiness"]; ?></li>
					<li><?php echo $wow_html["info"]["precipitation"]; ?></li>
					<li><?php echo $wow_html["info"]["visibility"]; ?></li>
					<li><?php echo $wow_html["info"]["uv_index"]; ?></li>
					<li><?php echo $wow_html["today"]["sun_hor"]; ?></li>
					<li><?php echo $wow_html["today"]["moon_hor"]; ?></li>
				</ul>
			</div>
		</div>
	
    	<?php if ($wow_opt["hours_forecast_no"] > 0) { ?>
	    	<!-- Hourly Forecast -->
		    <?php echo $wow_html["hour"]["start"]; ?>
			<div class="wow-flexslider-hours flexslider carousel">
	    		<ul class="slides">
    	    	<?php
	    	    	for ($i = 0; $i < $wow_opt["hours_forecast_no"]; $i++) {
		    	        if (isset($wow_html["hour"]["info"][$i])) {
		    	            echo "<li>";
    		    	    	echo $wow_html["hour"]["info"][$i];
		    	            echo "</li>";
        			    }
        			}
    	    	?>
		        <?php echo $wow_html["hour"]["end"]; ?>
            	<?php
            	}
            	?>
        	    </ul>
        	</div>
	
		<?php if ($wow_opt["days_forecast_no"] > 0) { ?>
			<!-- Daily Forecast -->
			<div class="wow-flexslider-forecast flexslider carousel">
				<?php echo $wow_html["forecast"]["start"]; ?>
				    <ul class="slides">
					<?php
					for ($i = 0; $i < $wow_opt["days_forecast_no"]; $i++) {
						if ($i % 3  == 0) {
							echo '<li>';
						}

						echo $wow_html["forecast"]["info"][$i];

						if ($i % 3  == 2) {
							echo '</li>';
						}
					};
					?>
					</ul>
				<?php echo $wow_html["forecast"]["end"]; ?>
			</div>
		<?php } ?>
	
		<!-- Weather Map -->
		<?php echo $wow_html["map"]; ?>

		<?php echo $wow_html["owm_link_last_update_start"]; ?>
			<!-- OWM Link -->
			<?php echo $wow_html["owm_link"]; ?>
			<!-- OWM Last Update -->
			<?php echo $wow_html["last_update"]; ?>
		<?php echo $wow_html["owm_link_last_update_end"]; ?>

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
            slider("#<?php echo $wow_opt["container_weather_div"] ?>");
            x = jQuery("#<?php echo $wow_opt["container_weather_div"] ?> .wow-btn-toggle-infos");
            x.html(x.html() == '+' ? '-' : '+');
	    });
	});

    function slider(id) {
		jQuery(id+' .wow-flexslider').flexslider({
	        controlsContainer: jQuery(id+" .custom-controls-container"),
            customDirectionNav: jQuery(id+" .custom-navigation a"),
            animation: "slide",
            animationLoop: true,
            itemWidth: 250,
            itemMargin: 5,
            maxItems: 4
		});
		jQuery(id+' .wow-flexslider-hours').flexslider({
	        controlsContainer: jQuery(id+" .custom-controls-container"),
            customDirectionNav: jQuery(id+" .custom-navigation a"),
            animation: "slide",
            animationLoop: true,
            itemWidth: 50,
            itemMargin: 5,
            maxItems: 8
    	});
		jQuery(id+' .wow-flexslider-forecast').flexslider({
	        controlsContainer: jQuery(id+" .custom-controls-container"),
            customDirectionNav: jQuery(id+" .custom-navigation a"),
		});
    }

</script>
