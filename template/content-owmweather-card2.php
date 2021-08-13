<?php
/****************************************************************************/
/* DO NOT CHANGE THIS TEMPLATE DIRECTLY. IT WILL BE OVERWRITTEN BY UPDATES. */
/****************************************************************************/
/**
 * The OWM Weather card2 template
 *
 */
?>
<!-- Start #owm-weather styles -->
<style>
#<?php echo $owmw_opt["main_weather_div"] ?> {
    width: 100%;
}
#<?php echo $owmw_opt["container_weather_div"] ?> {
	width: auto;
}
#<?php echo $owmw_opt["main_weather_div"] ?> .owmw-infos-text {
    line-height: 1.5;
    margin-top: 20px;
    text-align: left;
}
#<?php echo $owmw_opt["main_weather_div"] ?> .owmw-infos-text span.owmw-value,
#<?php echo $owmw_opt["main_weather_div"] ?> .owmw-temperature {
    font-size: 125%;
    font-weight: 700;
}
#<?php echo $owmw_opt["main_weather_div"] ?> .owmw-infos-text svg {
    height: 28px;
    width: 29px;
}
#<?php echo $owmw_opt["main_weather_div"] ?> .owmw-now .owmw-main-temperature {
    font-size: 36px;
}
#<?php echo $owmw_opt["main_weather_div"] ?> .owmw-today .owmw-sun-hours,
#<?php echo $owmw_opt["main_weather_div"] ?> .owmw-today .owmw-moon-hours {
    font-size: 12px;
}
#<?php echo $owmw_opt["main_weather_div"] ?> .owmw-hours .card,
#<?php echo $owmw_opt["main_weather_div"] ?> .owmw-forecast .card {
    margin: 0 !important;
}
#<?php echo $owmw_opt["main_weather_div"] ?> .owmw-current {
    border-right: <?php echo "1px " . $owmw_opt["border_style"] ?? "solid " . $owmw_opt["border_color"] ?? "inherit" . ";" ; ?>;
}

@media only screen and (max-width: 558px) {
    #<?php echo $owmw_opt["main_weather_div"] ?> .owmw-current {
        border-right: none;
    }
}

@media only screen and (max-width: 767px) {
    #<?php echo $owmw_opt["main_weather_div"] ?> .owmw-infos-text {
        text-align: center;
    }
}
</style>

<?php
    $info_col = ($owmw_opt["wind"] =='yes' || $owmw_opt["humidity"] =='yes' || $owmw_opt["dew_point"] =='yes' || $owmw_opt["pressure"] =='yes' || $owmw_opt["cloudiness"] =='yes' || $owmw_opt["precipitation"] =='yes' || $owmw_opt["visibility"] =='yes' || $owmw_opt["uv_index"] =='yes');
    $hour_col = ($owmw_opt["hours_forecast_no"] > 0);
    $day_col  = ($owmw_opt["days_forecast_no"] > 0);
    
    if ($info_col && ($hour_col || $day_col)) {
        $main_class = "col-md-3 col-sm-4 col-12";
        $info_class = "";
        $hour_day_class = "col-md-9 col-sm-8 col-12";
    } else if (!$info_col && ($hour_col || $day_col)) {
        $main_class = "col-md-3 col-5";
        $info_class = "";
        $hour_day_class = "col-md-9 col-7";
    } else if ($info_col && !$hour_col && !$day_col) {
        $main_class = "col-md-5 col-12";
        $info_class = "col-md-7 col-12";
        $hour_day_class = "";
    }
?>

<!-- Start #owm-weather -->
<?php echo $owmw_html["container"]["start"]; ?>

    <div class="row">
        <div class="owmw-current <?php echo $main_class ?>">

        	<!-- Current weather -->
        	<?php echo $owmw_html["now"]["start"]; ?>
        		<?php echo $owmw_html["now"]["location_name"]; ?>
        		<?php echo $owmw_html["now"]["symbol"]; ?>
        		<?php echo $owmw_html["now"]["temperature"]; ?>
        		<?php echo $owmw_html["now"]["feels_like"]; ?>
        		<?php echo $owmw_html["now"]["weather_description"]; ?>
        	<?php echo $owmw_html["now"]["end"]; ?>

        	<!-- Today -->
        	<?php echo str_replace("row", "", $owmw_html["today"]["start"]); ?>
        		<?php echo str_replace("col", "", $owmw_html["today"]["day"]); ?>
        		<?php echo str_replace("col", "", $owmw_html["today"]["sun"]); ?>
        		<?php echo str_replace("col", "", $owmw_html["today"]["moon"]); ?>
        	<?php echo $owmw_html["today"]["end"]; ?>

        	<!-- Alert button -->
        	<?php echo $owmw_html["alert_button"]; ?>

        	<!-- Current infos: wind, humidity, pressure, cloudiness, precipitation -->
            <?php if ($owmw_opt["wind"] =='yes' || $owmw_opt["humidity"] =='yes' || $owmw_opt["dew_point"] =='yes' || $owmw_opt["pressure"] =='yes' || $owmw_opt["cloudiness"] =='yes' || $owmw_opt["precipitation"] =='yes' || $owmw_opt["visibility"] =='yes' || $owmw_opt["uv_index"] =='yes') { ?>
                <?php if (!empty($info_class)) { ?>
                    </div>
                    <div class="<?php echo $info_class ?>">
                <?php } ?>
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
            <?php } ?>

        </div>
	
    	<?php if ($owmw_opt["hours_forecast_no"] > 0 || $owmw_opt["days_forecast_no"] > 0) { ?>
            <div class="<?php echo $hour_day_class ?>">
    	<?php } ?>

    	<?php if ($owmw_opt["hours_forecast_no"] > 0) { ?>
            <div class="row">
                <div class="col">
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
        		</div>
    		</div>
    	<?php } ?>

    	<?php if ($owmw_opt["days_forecast_no"] > 0) { ?>	
            <div class="row">
                <div class="col">
        		<!-- Daily Forecast -->
        		<?php echo $owmw_html["forecast"]["start_card"]; ?>
        			<?php
        				for ($i = 0; $i < $owmw_opt["days_forecast_no"]; $i++) {
        					echo $owmw_html["forecast"]["info_card"][$i];
        				}
        			?>	
        		<?php echo $owmw_html["forecast"]["end_card"]; ?>
        		</div>
    		</div>
    	<?php } ?>

    	<?php if ($owmw_opt["hours_forecast_no"] > 0 || $owmw_opt["days_forecast_no"] > 0) { ?>
            </div>
    	<?php } ?>


    </div>

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
