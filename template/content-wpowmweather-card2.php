<?php
/****************************************************************************/
/* DO NOT CHANGE THIS TEMPLATE DIRECTLY. IT WILL BE OVERWRITTEN BY UPDATES. */
/****************************************************************************/
/**
 * The WP OWM Weather card2 template
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
#<?php echo $wow_opt["main_weather_div"] ?> .wow-current {
    border-right: <?php echo "1px " . $wow_opt["border_style"] ?? "solid " . $wow_opt["border_color"] ?? "inherit" . ";" ; ?>;
}

@media only screen and (max-width: 558px) {
    #<?php echo $wow_opt["main_weather_div"] ?> .wow-current {
        border-right: none;
    }
}

@media only screen and (max-width: 767px) {
    #<?php echo $wow_opt["main_weather_div"] ?> .wow-infos-text {
        text-align: center;
    }
}
</style>

<?php
    $info_col = ($wow_opt["wind"] =='yes' || $wow_opt["humidity"] =='yes' || $wow_opt["dew_point"] =='yes' || $wow_opt["pressure"] =='yes' || $wow_opt["cloudiness"] =='yes' || $wow_opt["precipitation"] =='yes' || $wow_opt["visibility"] =='yes' || $wow_opt["uv_index"] =='yes');
    $hour_col = ($wow_opt["hours_forecast_no"] > 0);
    $day_col  = ($wow_opt["days_forecast_no"] > 0);
    
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

<!-- Start #wow-weather -->
<?php echo $wow_html["container"]["start"]; ?>

    <div class="row">
        <div class="wow-current <?php echo $main_class ?>">

        	<!-- Current weather -->
        	<?php echo $wow_html["now"]["start"]; ?>
        		<?php echo $wow_html["now"]["location_name"]; ?>
        		<?php echo $wow_html["now"]["symbol"]; ?>
        		<?php echo $wow_html["now"]["temperature"]; ?>
        		<?php echo $wow_html["now"]["feels_like"]; ?>
        		<?php echo $wow_html["now"]["weather_description"]; ?>
        	<?php echo $wow_html["now"]["end"]; ?>

        	<!-- Today -->
        	<?php echo str_replace("row", "", $wow_html["today"]["start"]); ?>
        		<?php echo str_replace("col", "", $wow_html["today"]["day"]); ?>
        		<?php echo str_replace("col", "", $wow_html["today"]["sun"]); ?>
        		<?php echo str_replace("col", "", $wow_html["today"]["moon"]); ?>
        	<?php echo $wow_html["today"]["end"]; ?>

        	<!-- Alert button -->
        	<?php echo $wow_html["alert_button"]; ?>

        	<!-- Current infos: wind, humidity, pressure, cloudiness, precipitation -->
            <?php if ($wow_opt["wind"] =='yes' || $wow_opt["humidity"] =='yes' || $wow_opt["dew_point"] =='yes' || $wow_opt["pressure"] =='yes' || $wow_opt["cloudiness"] =='yes' || $wow_opt["precipitation"] =='yes' || $wow_opt["visibility"] =='yes' || $wow_opt["uv_index"] =='yes') { ?>
                <?php if (!empty($info_class)) { ?>
                    </div>
                    <div class="<?php echo $info_class ?>">
                <?php } ?>
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
            <?php } ?>

        </div>
	
    	<?php if ($wow_opt["hours_forecast_no"] > 0 || $wow_opt["days_forecast_no"] > 0) { ?>
            <div class="<?php echo $hour_day_class ?>">
    	<?php } ?>

    	<?php if ($wow_opt["hours_forecast_no"] > 0) { ?>
            <div class="row">
                <div class="col">
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
        		</div>
    		</div>
    	<?php } ?>

    	<?php if ($wow_opt["days_forecast_no"] > 0) { ?>	
            <div class="row">
                <div class="col">
        		<!-- Daily Forecast -->
        		<?php echo $wow_html["forecast"]["start_card"]; ?>
        			<?php
        				for ($i = 0; $i < $wow_opt["days_forecast_no"]; $i++) {
        					echo $wow_html["forecast"]["info_card"][$i];
        				}
        			?>	
        		<?php echo $wow_html["forecast"]["end_card"]; ?>
        		</div>
    		</div>
    	<?php } ?>

    	<?php if ($wow_opt["hours_forecast_no"] > 0 || $wow_opt["days_forecast_no"] > 0) { ?>
            </div>
    	<?php } ?>


    </div>

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
