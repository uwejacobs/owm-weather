<?php
/****************************************************************************/
/* DO NOT CHANGE THIS TEMPLATE DIRECTLY. IT WILL BE OVERWRITTEN BY UPDATES. */
/****************************************************************************/
/**
 * The OWM Weather Slider 2 template
 *
 */
?>
<!-- Start #owm-weather -->
<style>
div[class^="owmw-flexslider"] {
    background-color: inherit !important;
    border: none !important;
}
.owmw-hide{ display:none;}
.owmw-show{ display:block;}
</style>

<!-- Start #owm-weather -->
<?php echo wp_kses_post($owmw_html["container"]["start"]); ?>
	<div class="custom-navigation d-none">
  		<a href="#" class="flex-prev">Prev</a>
  		<div class="custom-controls-container"></div>
  		<a href="#" class="flex-next">Next</a>
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
   	    <?php echo wp_kses_post($owmw_html["alert_button"]); ?>

		<!-- Current infos: wind, humidity, dew point, pressure, cloudiness, precipitation, visibility, uv index -->
		<div class="owmw-infos">
			<?php echo wp_kses_post($owmw_html["today"]["day"]); ?>
			<div class="owmw-flexslider flexslider carousel">
				<ul class="slides">
					<li><?php echo wp_kses_post($owmw_html["info"]["wind"]); ?></li>
					<li><?php echo wp_kses_post($owmw_html["info"]["humidity"]); ?></li>
					<li><?php echo wp_kses_post($owmw_html["info"]["dew_point"]); ?></li>
					<li><?php echo wp_kses_post($owmw_html["info"]["pressure"]); ?></li>
					<li><?php echo wp_kses_post($owmw_html["info"]["cloudiness"]); ?></li>
					<li><?php echo wp_kses_post($owmw_html["info"]["precipitation"]); ?></li>
					<li><?php echo wp_kses_post($owmw_html["info"]["visibility"]); ?></li>
					<li><?php echo wp_kses_post($owmw_html["info"]["uv_index"]); ?></li>
					<li><?php echo wp_kses($owmw_html["today"]["sun_hor"], $owmw_opt['allowed_html']); ?></li>
					<li><?php echo wp_kses($owmw_html["today"]["moon_hor"], $owmw_opt['allowed_html']); ?></li>
				</ul>
			</div>
		</div>
	
    	<?php if ($owmw_opt["hours_forecast_no"] > 0) { ?>
	    	<!-- Hourly Forecast -->
		    <?php echo wp_kses_post($owmw_html["hour"]["start"]); ?>
			<div class="owmw-flexslider-hours flexslider carousel">
	    		<ul class="slides">
    	    	<?php
	    	    	for ($i = 0; $i < $owmw_opt["hours_forecast_no"]; $i++) {
		    	        if (isset($owmw_html["hour"]["info"][$i])) {
		    	            echo "<li>";
    		    	    	echo wp_kses_post($owmw_html["hour"]["info"][$i]);
		    	            echo "</li>";
        			    }
        			}
    	    	?>
		        <?php echo wp_kses_post($owmw_html["hour"]["end"]); ?>
            	<?php
            	}
            	?>
        	    </ul>
        	</div>
	
		<?php if ($owmw_opt["days_forecast_no"] > 0) { ?>
			<!-- Daily Forecast -->
			<div class="owmw-flexslider-forecast flexslider carousel">
				<?php echo wp_kses_post($owmw_html["forecast"]["start"]); ?>
				    <ul class="slides">
					<?php
					for ($i = 0; $i < $owmw_opt["days_forecast_no"]; $i++) {
						if ($i % 3  == 0) {
							echo '<li>';
						}

						echo wp_kses_post($owmw_html["forecast"]["info"][$i]);

						if ($i % 3  == 2) {
							echo '</li>';
						}
					};
					?>
					</ul>
				<?php echo wp_kses_post($owmw_html["forecast"]["end"]); ?>
			</div>
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

	</div><!-- End .toggle-infos -->

	<!-- Alert Modals -->
	<?php echo wp_kses_post($owmw_html["alert_modal"]); ?>

	<!-- CSS/Scripts -->
	<?php echo '<style type="text/css">' . wp_kses_post($owmw_html["custom_css"]) . '</style>'; ?>
	<?php echo '<style type="text/css">' . wp_kses_post($owmw_html["temperature_unit"]) . '</style>'; ?>

	<!-- Google Tag Manager -->
	<?php echo '<script type="text/javascript">' . wp_kses_post($owmw_html["gtag"]) . '</script>'; ?>

<!-- End #owm-weather -->
<?php echo wp_kses_post($owmw_html["container"]["end"]); ?>

<script type="text/javascript">
	jQuery(window).ready(function() {
    	jQuery( "#<?php echo esc_attr($owmw_html["container_weather_div"]) ?> .owmw-btn-toggle-infos" ).click(function() {
	    	jQuery( "#<?php echo esc_attr($owmw_html["container_weather_div"]) ?> .owmw-toggle-infos" ).toggleClass( "owmw-show" );
		    jQuery( "#<?php echo esc_attr($owmw_html["container_weather_div"]) ?> .owmw-toggle-now" ).toggleClass( "owmw-hide" );
		    jQuery( "#<?php echo esc_attr($owmw_html["container_map_div"]); ?>" ).trigger("invalidSize");
            slider("#<?php echo esc_attr($owmw_html["container_weather_div"]) ?>");
            x = jQuery("#<?php echo esc_attr($owmw_html["container_weather_div"]) ?> .owmw-btn-toggle-infos");
            x.html(x.html() == '+' ? '-' : '+');
	    });
	});

    function slider(id) {
		jQuery(id+' .owmw-flexslider').flexslider({
	        controlsContainer: jQuery(id+" .custom-controls-container"),
            customDirectionNav: jQuery(id+" .custom-navigation a"),
            animation: "slide",
            animationLoop: true,
            itemWidth: 250,
            itemMargin: 5,
            maxItems: 4
		});
		jQuery(id+' .owmw-flexslider-hours').flexslider({
	        controlsContainer: jQuery(id+" .custom-controls-container"),
            customDirectionNav: jQuery(id+" .custom-navigation a"),
            animation: "slide",
            animationLoop: true,
            itemWidth: 50,
            itemMargin: 5,
            maxItems: 8
    	});
		jQuery(id+' .owmw-flexslider-forecast').flexslider({
	        controlsContainer: jQuery(id+" .custom-controls-container"),
            customDirectionNav: jQuery(id+" .custom-navigation a"),
		});
    }

</script>
