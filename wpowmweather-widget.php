<?php
// To prevent calling the plugin directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Please don&rsquo;t call the plugin directly. Thanks :)';
	exit;
}

/**
 * Add a widget to the dashboard.
 */
function wow_add_dashboard_widgets() {

	wp_add_dashboard_widget(
                 'wpowmweather_dashboard_widget',     // Widget slug.
                 'WP OWM Weather',    // Title.
                 'wow_dashboard_widget_function',  // Display function.
                 'wow_dashboard_widget_option'   //Options
        );	
}
add_action( 'wp_dashboard_setup', 'wow_add_dashboard_widgets' );

/**
 * Create the function to output the contents of our Dashboard Widget.
 */

function wow_dashboard_widget_function() {

	// Display selected weather.
    if ( $my_weather = get_option( 'wow_dashboard_widget_option' ) ) {
        wow_add_themes_scripts();
		wp_enqueue_style('bootstrap-css');/*bugbug*/
		wp_enqueue_script('bootstrap-js');/*bugbug*/
		wp_enqueue_script('popper-js');/*bugbug*/
		echo do_shortcode('[wow-weather id="'.$my_weather['weather_db'].'"]');
	} else {
		_e('Please select a weather via configure link','wp-owm-weather');
	}
}
/**
 * Create the function to configure our Dashboard Widget.
 */
function wow_dashboard_widget_option($widget_id) {
	
	// Get widget options
	if ( !$wow_widget_options = get_option( 'wow_dashboard_widget_option' ) )
		$wow_widget_options = array();
	
	// Update widget options
	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['wow_widget_post']) ) {
		update_option( 'wow_dashboard_widget_option', $_POST['wow_widget'] );
	}
	
	// Retrieve feed URLs
	$weather_db = $wow_widget_options['weather_db'];
	

	echo'<p>';
	echo'<label for="wow_weather_db-">';
			_e('Select the weather to display:', 'wp-owm-weather');
	echo'</label>';

	echo'<select name="wow_widget[weather_db]">';
			
			$query = new WP_Query( array( 'post_type' => array( 'wow-weather', 'wow-weather' ) ) );
	
				while ( $query->have_posts() ) : $query->the_post();
					echo '<option value="'.get_the_ID().'"';
							selected( $weather_db, get_the_ID() );
					echo '>';
							the_title();
	
					echo '</option>';
				endwhile;
			
		
			
		echo'</select>';
				
	echo'</p>';
	
	echo'<input name="wow_widget_post" type="hidden" value="1" />';
}