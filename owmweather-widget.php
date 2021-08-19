<?php
// To prevent calling the plugin directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Please don&rsquo;t call the plugin directly. Thanks :)';
	exit;
}

/**
 * Add a widget to the dashboard.
 */
function owmw_add_dashboard_widgets() {

	wp_add_dashboard_widget(
                 'owmweather_dashboard_widget',     // Widget slug.
                 'OWM Weather',    // Title.
                 'owmw_dashboard_widget_function',  // Display function.
                 'owmw_dashboard_widget_option'   //Options
        );	
}
add_action( 'wp_dashboard_setup', 'owmw_add_dashboard_widgets' );

/**
 * Create the function to output the contents of our Dashboard Widget.
 */

function owmw_dashboard_widget_function() {

	// Display selected weather.
    if ( $my_weather = get_option( 'owmw_dashboard_widget_option' ) ) {
		echo do_shortcode('[owm-weather id="'.esc_attr($my_weather['weather_db']).'"]');
	} else {
		esc_html_e('Please select a weather via configure link','owm-weather');
	}
}
/**
 * Create the function to configure our Dashboard Widget.
 */
function owmw_dashboard_widget_option($widget_id) {
	
	// Get widget options
	if ( !$owmw_widget_options = get_option( 'owmw_dashboard_widget_option' ) )
		$owmw_widget_options = [];

	// Update widget options
	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['owmw_widget_post']) ) {
        update_option( 'owmw_dashboard_widget_option', $_POST['owmw_widget'] );
	}
	
	// Retrieve feed URLs
	$weather_db = $owmw_widget_options['weather_db'] ?? null;

	echo'<p>';
	echo'<label for="owm_weather_db">';
			esc_html_e('Select the weather to display:', 'owm-weather');
	echo'</label>';

	echo'<select id="owm_weather_db" name="owmw_widget[weather_db]">';
			
			$query = new WP_Query( array( 'post_type' => array( 'owm-weather' ) ) );
	
				while ( $query->have_posts() ) : $query->the_post();
					echo '<option value="'.esc_attr(get_the_ID()).'"';
							selected( $weather_db, get_the_ID() );
					echo '>';
							the_title();
	
					echo '</option>';
				endwhile;
			
		echo'</select>';
				
	echo'</p>';
	
	echo'<input name="owmw_widget_post" type="hidden" value="1" />';
}
