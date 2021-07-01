<?php
// To prevent calling the plugin directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Please don&rsquo;t call the plugin directly. Thanks :)';
	exit;
}

/**
 * Add a widget to the dashboard.
 */
function wpc_add_dashboard_widgets() {

	wp_add_dashboard_widget(
                 'wpcloudy_dashboard_widget',     // Widget slug.
                 'WP Cloudy',    // Title.
                 'wpc_dashboard_widget_function',  // Display function.
                 'wpc_dashboard_widget_option'   //Options
        );	
}
add_action( 'wp_dashboard_setup', 'wpc_add_dashboard_widgets' );

/**
 * Create the function to output the contents of our Dashboard Widget.
 */

function wpc_dashboard_widget_function() {

	// Display selected weather.
    if ( $my_weather = get_option( 'wpc_dashboard_widget_option' ) ) {
		echo do_shortcode('[wpc-weather id="'.$my_weather['weather_db'].'"]');
	} else {
		_e('Please select a weather via configure link','wp-cloudy');
	}
}
/**
 * Create the function to configure our Dashboard Widget.
 */
function wpc_dashboard_widget_option($widget_id) {
	
	// Get widget options
	if ( !$wpc_widget_options = get_option( 'wpc_dashboard_widget_option' ) )
		$wpc_widget_options = array();
	
	// Update widget options
	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['wpc_widget_post']) ) {
		update_option( 'wpc_dashboard_widget_option', $_POST['wpc_widget'] );
	}
	
	// Retrieve feed URLs
	$weather_db = $wpc_widget_options['weather_db'];
	

	echo'<p>';
	echo'<label for="wpc_weather_db-">';
			_e('Select the weather to display:', 'wp-cloudy');
	echo'</label>';

	echo'<select name="wpc_widget[weather_db]">';
			
			$query = new WP_Query( array( 'post_type' => array( 'wpc-weather' ) ) );
	
				while ( $query->have_posts() ) : $query->the_post();
					echo '<option value="'.get_the_ID().'"';
							selected( $weather_db, get_the_ID() );
					echo '>';
							the_title();
	
					echo '</option>';
				endwhile;
			
		
			
		echo'</select>';
				
	echo'</p>';
	
	echo'<input name="wpc_widget_post" type="hidden" value="1" />';

}
