<?php
/****************************************************************************/
/* DO NOT CHANGE THIS TEMPLATE DIRECTLY. IT WILL BE OVERWRITTEN BY UPDATES. */
/****************************************************************************/
/**
 * The OWM Weather card1 template
 *
 */
?>
<!-- Start #owm-weather styles -->
<style>
#<?php echo $owmw_html["main_weather_div"] ?> {
    width: 100%;
}
#<?php echo $owmw_html["container_weather_div"] ?> {
	width: auto;
}
#<?php echo $owmw_html["main_weather_div"] ?> .owmw-current-infos {
    white-space: nowrap;
}
#<?php echo $owmw_html["main_weather_div"] ?> .owmw-infos-text {
    line-height: 1.5;
    margin-top: 20px;
    text-align: left;
}
#<?php echo $owmw_html["main_weather_div"] ?> .owmw-infos-text span.owmw-value,
#<?php echo $owmw_html["main_weather_div"] ?> .owmw-temperature {
    font-size: 125%;
    font-weight: 700;
}
#<?php echo $owmw_html["main_weather_div"] ?> .owmw-infos-text svg {
    height: 28px;
    width: 29px;
}
#<?php echo $owmw_html["main_weather_div"] ?> .owmw-now .owmw-main-temperature {
    font-size: 36px;
}
#<?php echo $owmw_html["main_weather_div"] ?> .owmw-today .owmw-sun-hours,
#<?php echo $owmw_html["main_weather_div"] ?> .owmw-today .owmw-moon-hours {
    font-size: 12px;
}
#<?php echo $owmw_html["main_weather_div"] ?> .owmw-hours .card,
#<?php echo $owmw_html["main_weather_div"] ?> .owmw-forecast .card {
    margin: 0 !important;
}
</style>
<!-- Start #owm-weather -->
<?php echo $owmw_html["container"]["start"]; ?>

<?php
    if ($owmw_opt["wind"] =='yes' || $owmw_opt["humidity"] =='yes' || $owmw_opt["dew_point"] =='yes' || $owmw_opt["pressure"] =='yes' || $owmw_opt["cloudiness"] =='yes' || $owmw_opt["precipitation"] =='yes' || $owmw_opt["visibility"] =='yes' || $owmw_opt["uv_index"] =='yes') {
        $left_classes = "col-md-6 col-5";
    } else {
        $left_classes = "col";
    }
?>

    <div class="row">
        <div class="<?php echo $left_classes; ?>">

        	<!-- Current weather -->
        	<?php echo $owmw_html["now"]["start"]; ?>
        		<?php echo $owmw_html["now"]["location_name"]; ?>
        		<?php echo $owmw_html["now"]["symbol"]; ?>
        		<?php echo $owmw_html["now"]["temperature"]; ?>
        		<?php echo $owmw_html["now"]["feels_like"]; ?>
        		<?php echo $owmw_html["now"]["weather_description"]; ?>
        	<?php echo $owmw_html["now"]["end"]; ?>

        	<!-- Alert button -->
        	<?php echo $owmw_html["alert_button"]; ?>

        </div>
	
        	<!-- Current infos: wind, humidity, pressure, cloudiness, precipitation -->
            <?php if ($owmw_opt["wind"] =='yes' || $owmw_opt["humidity"] =='yes' || $owmw_opt["dew_point"] =='yes' || $owmw_opt["pressure"] =='yes' || $owmw_opt["cloudiness"] =='yes' || $owmw_opt["precipitation"] =='yes' || $owmw_opt["visibility"] =='yes' || $owmw_opt["uv_index"] =='yes') { ?>
                <div class="owmw-current-infos col-md-6 col-7">
            	<?php echo '<p class="owmw-infos-text">'; ?>
            	<?php if ($owmw_opt["wind"] =='yes') echo $owmw_html["svg"]["wind"] . 'Wind: <span class="owmw-value">' . $owmw_data["wind_speed"] . ' ' . $owmw_data["wind_direction"] . '</span><br>'; ?>
            	<?php if ($owmw_opt["humidity"] =='yes') echo $owmw_html["svg"]["humidity"] .'Humidity: <span class="owmw-value">' . $owmw_data["humidity"] . '</span><br>'; ?>
            	<?php if ($owmw_opt["dew_point"] =='yes') echo $owmw_html["svg"]["dew_point"] .'Dew Point: <span class="owmw-value owmw-temperature">' . $owmw_data["dew_point"] . '</span><br>'; ?>
            	<?php if ($owmw_opt["pressure"] =='yes') echo $owmw_html["svg"]["pressure"] .'Pressure: <span class="owmw-value">' . $owmw_data["pressure"] . '</span><br>'; ?>
            	<?php if ($owmw_opt["cloudiness"] =='yes') echo $owmw_html["svg"]["cloudiness"] .'Cloudiness: <span class="owmw-value">' . $owmw_data["cloudiness"] . '</span><br>'; ?>
            	<?php if ($owmw_opt["precipitation"] =='yes') echo $owmw_html["svg"]["precipitation"] .'Precipitation: <span class="owmw-value">' . $owmw_data["precipitation_1h"] . '</span><br>'; ?>
            	<?php if ($owmw_opt["visibility"] =='yes') echo $owmw_html["svg"]["visibility"] . 'Visibility: <span class="owmw-value">' . $owmw_data["visibility"] . '</span><br>'; ?>
            	<?php if ($owmw_opt["uv_index"] =='yes') echo $owmw_html["svg"]["uv_index"] . 'UV Index: <span class="owmw-value">' . $owmw_data["uv_index"] . '</span><br>'; ?>
            	<?php echo "</p>"; ?>
                </div>
            <?php } ?>

    </div>

	<!-- Today -->
	<?php echo $owmw_html["today"]["start"]; ?>
		<?php echo $owmw_html["today"]["day"]; ?>
		<?php echo $owmw_html["today"]["sun"]; ?>
		<?php echo $owmw_html["today"]["moon"]; ?>
	<?php echo $owmw_html["today"]["end"]; ?>


	<?php if ($owmw_opt["hours_forecast_no"] > 0) { ?>
		<!-- Hourly Forecast -->
		<?php echo $owmw_html["hour"]["start"]; ?>
		<?php
			for ($i = 0; $i < $owmw_opt["hours_forecast_no"]; $i++) {
			    if (isset($owmw_html["hour"]["info"][$i])) {
    				echo $owmw_html["hour"]["info"][$i];
    			    }
			}
		?>
		<?php echo $owmw_html["hour"]["end"]; ?>
	<?php } ?>

	<?php if ($owmw_opt["days_forecast_no"] > 0) { ?>	
		<!-- Daily Forecast -->
		<?php echo $owmw_html["forecast"]["start_card"]; ?>
			<?php
				for ($i = 0; $i < $owmw_opt["days_forecast_no"]; $i++) {
					echo $owmw_html["forecast"]["info_card"][$i];
				};
			?>	
		<?php echo $owmw_html["forecast"]["end_card"]; ?>
	<?php } ?>

	<!-- Weather Map -->
	<?php echo $owmw_html["map"]; ?>

	<?php echo $owmw_html["owm_link_last_update_start"]; ?>
		<!-- OWM Link -->
		<?php echo $owmw_html["owm_link"]; ?>
		<!-- OWM Last Update -->
		<?php echo $owmw_html["last_update"]; ?>
	<?php echo $owmw_html["owm_link_last_update_end"]; ?>

	<!-- Alert Modals -->
	<?php echo $owmw_html["alert_modal"]; ?>

	<!-- CSS/Scripts -->
	<?php echo $owmw_html["custom_css"]; ?>
	<?php echo $owmw_html["temperature_unit"]; ?>
	<?php echo $owmw_html["gtag"]; ?>

<!-- End #owm-weather -->
<?php echo $owmw_html["container"]["end"]; ?>
