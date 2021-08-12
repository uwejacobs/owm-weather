<?php
/****************************************************************************/
/* DO NOT CHANGE THIS TEMPLATE DIRECTLY. IT WILL BE OVERWRITTEN BY UPDATES. */
/****************************************************************************/
/**
 * The OWM Weather card1 template
 *
 */
?>
<!-- Start #wow-weather styles -->
<style>
#<?php echo $wow_opt["main_weather_div"] ?> {
    width: 100%;
}
#<?php echo $wow_opt["container_weather_div"] ?> {
	width: auto;
}
#<?php echo $wow_opt["main_weather_div"] ?> .wow-current-infos {
    white-space: nowrap;
}
#<?php echo $wow_opt["main_weather_div"] ?> .wow-infos-text {
    line-height: 1.5;
    margin-top: 20px;
    text-align: left;
}
#<?php echo $wow_opt["main_weather_div"] ?> .wow-infos-text span.wow-value,
#<?php echo $wow_opt["main_weather_div"] ?> .wow-temperature {
    font-size: 125%;
    font-weight: 700;
}
#<?php echo $wow_opt["main_weather_div"] ?> .wow-infos-text svg {
    height: 28px;
    width: 29px;
}
#<?php echo $wow_opt["main_weather_div"] ?> .wow-now .wow-main-temperature {
    font-size: 36px;
}
#<?php echo $wow_opt["main_weather_div"] ?> .wow-today .wow-sun-hours,
#<?php echo $wow_opt["main_weather_div"] ?> .wow-today .wow-moon-hours {
    font-size: 12px;
}
#<?php echo $wow_opt["main_weather_div"] ?> .wow-hours .card,
#<?php echo $wow_opt["main_weather_div"] ?> .wow-forecast .card {
    margin: 0 !important;
}
</style>
<!-- Start #wow-weather -->
<?php echo $wow_html["container"]["start"]; ?>

<?php
    if ($wow_opt["wind"] =='yes' || $wow_opt["humidity"] =='yes' || $wow_opt["dew_point"] =='yes' || $wow_opt["pressure"] =='yes' || $wow_opt["cloudiness"] =='yes' || $wow_opt["precipitation"] =='yes' || $wow_opt["visibility"] =='yes' || $wow_opt["uv_index"] =='yes') {
        $left_classes = "col-md-6 col-5";
    } else {
        $left_classes = "col";
    }
?>

    <div class="row">
        <div class="<?php echo $left_classes; ?>">

        	<!-- Current weather -->
        	<?php echo $wow_html["now"]["start"]; ?>
        		<?php echo $wow_html["now"]["location_name"]; ?>
        		<?php echo $wow_html["now"]["symbol"]; ?>
        		<?php echo $wow_html["now"]["temperature"]; ?>
        		<?php echo $wow_html["now"]["feels_like"]; ?>
        		<?php echo $wow_html["now"]["weather_description"]; ?>
        	<?php echo $wow_html["now"]["end"]; ?>

        	<!-- Alert button -->
        	<?php echo $wow_html["alert_button"]; ?>

        </div>
	
        	<!-- Current infos: wind, humidity, pressure, cloudiness, precipitation -->
            <?php if ($wow_opt["wind"] =='yes' || $wow_opt["humidity"] =='yes' || $wow_opt["dew_point"] =='yes' || $wow_opt["pressure"] =='yes' || $wow_opt["cloudiness"] =='yes' || $wow_opt["precipitation"] =='yes' || $wow_opt["visibility"] =='yes' || $wow_opt["uv_index"] =='yes') { ?>
                <div class="wow-current-infos col-md-6 col-7">
            	<?php echo '<p class="wow-infos-text">'; ?>
            	<?php if ($wow_opt["wind"] =='yes') echo $wow_html["svg"]["wind"] . 'Wind: <span class="wow-value">' . $wow_data["wind_speed"] . ' ' . $wow_data["wind_direction"] . '</span><br>'; ?>
            	<?php if ($wow_opt["humidity"] =='yes') echo $wow_html["svg"]["humidity"] .'Humidity: <span class="wow-value">' . $wow_data["humidity"] . '</span><br>'; ?>
            	<?php if ($wow_opt["dew_point"] =='yes') echo $wow_html["svg"]["dew_point"] .'Dew Point: <span class="wow-value wow-temperature">' . $wow_data["dew_point"] . '</span><br>'; ?>
            	<?php if ($wow_opt["pressure"] =='yes') echo $wow_html["svg"]["pressure"] .'Pressure: <span class="wow-value">' . $wow_data["pressure"] . '</span><br>'; ?>
            	<?php if ($wow_opt["cloudiness"] =='yes') echo $wow_html["svg"]["cloudiness"] .'Cloudiness: <span class="wow-value">' . $wow_data["cloudiness"] . '</span><br>'; ?>
            	<?php if ($wow_opt["precipitation"] =='yes') echo $wow_html["svg"]["precipitation"] .'Precipitation: <span class="wow-value">' . $wow_data["precipitation_1h"] . '</span><br>'; ?>
            	<?php if ($wow_opt["visibility"] =='yes') echo $wow_html["svg"]["visibility"] . 'Visibility: <span class="wow-value">' . $wow_data["visibility"] . '</span><br>'; ?>
            	<?php if ($wow_opt["uv_index"] =='yes') echo $wow_html["svg"]["uv_index"] . 'UV Index: <span class="wow-value">' . $wow_data["uv_index"] . '</span><br>'; ?>
            	<?php echo "</p>"; ?>
                </div>
            <?php } ?>

    </div>

	<!-- Today -->
	<?php echo $wow_html["today"]["start"]; ?>
		<?php echo $wow_html["today"]["day"]; ?>
		<?php echo $wow_html["today"]["sun"]; ?>
		<?php echo $wow_html["today"]["moon"]; ?>
	<?php echo $wow_html["today"]["end"]; ?>


	<?php if ($wow_opt["hours_forecast_no"] > 0) { ?>
		<!-- Hourly Forecast -->
		<?php echo $wow_html["hour"]["start"]; ?>
		<?php
			for ($i = 0; $i < $wow_opt["hours_forecast_no"]; $i++) {
			    if (isset($wow_html["hour"]["info"][$i])) {
    				echo $wow_html["hour"]["info"][$i];
    			    }
			}
		?>
		<?php echo $wow_html["hour"]["end"]; ?>
	<?php } ?>

	<?php if ($wow_opt["days_forecast_no"] > 0) { ?>	
		<!-- Daily Forecast -->
		<?php echo $wow_html["forecast"]["start_card"]; ?>
			<?php
				for ($i = 0; $i < $wow_opt["days_forecast_no"]; $i++) {
					echo $wow_html["forecast"]["info_card"][$i];
				};
			?>	
		<?php echo $wow_html["forecast"]["end_card"]; ?>
	<?php } ?>

	<!-- Weather Map -->
	<?php echo $wow_html["map"]; ?>

	<?php echo $wow_html["owm_link_last_update_start"]; ?>
		<!-- OWM Link -->
		<?php echo $wow_html["owm_link"]; ?>
		<!-- OWM Last Update -->
		<?php echo $wow_html["last_update"]; ?>
	<?php echo $wow_html["owm_link_last_update_end"]; ?>

	<!-- Alert Modals -->
	<?php echo $wow_html["alert_modal"]; ?>

	<!-- CSS/Scripts -->
	<?php echo $wow_html["custom_css"]; ?>
	<?php echo $wow_html["temperature_unit"]; ?>
	<?php echo $wow_html["gtag"]; ?>

<!-- End #wow-weather -->
<?php echo $wow_html["container"]["end"]; ?>
