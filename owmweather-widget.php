<?php
// To prevent calling the plugin directly
if (!function_exists('add_action')) {
    echo 'Please don&rsquo;t call the plugin directly. Thanks :)';
    exit;
}

/**
 * Add a widget to the dashboard.
 */
function owmw_add_dashboard_widgets()
{

    wp_add_dashboard_widget(
        'owmweather_dashboard_widget',     // Widget slug.
        'OWM Weather',                     // Title.
        'owmw_dashboard_widget_function',  // Display function.
        'owmw_dashboard_widget_option'     //Options
    );
}
add_action('wp_dashboard_setup', 'owmw_add_dashboard_widgets');

/**
 * Create the function to output the contents of our Dashboard Widget.
 */

function owmw_dashboard_widget_function()
{
    // Display selected weather.
    if ($my_weather = get_option('owmw_dashboard_widget_option')) {
        echo do_shortcode('[owm-weather id="'.esc_attr($my_weather['weather_db']).'"]');
    } else {
        esc_html_e('Please select a weather via the configure link', 'owm-weather');
    }
}
/**
 * Create the function to configure our Dashboard Widget.
 */
function owmw_dashboard_widget_option($widget_id)
{
    // Update widget options
    if ('POST' == $_SERVER['REQUEST_METHOD'] && isset($_POST['owmw_widget_post'])) {
        if (! wp_verify_nonce(sanitize_text_field($_POST['dashboard-widget-nonce']), 'edit-dashboard-widget_owmweather_dashboard_widget')) {
            return;
        }
    
        $owmw_widget_options = $_POST['owmw_widget'];
        $owmw_widget_options["weather_db"] = sanitize_text_field($owmw_widget_options["weather_db"]);
        update_option('owmw_dashboard_widget_option', $owmw_widget_options);
    }
    
    // Get widget options
    if (!$owmw_widget_options = get_option('owmw_dashboard_widget_option')) {
        $owmw_widget_options = [];
    }

    // Retrieve feed URLs
    $weather_db = $owmw_widget_options['weather_db'] ?? null;

    echo'<p>';
    echo'<label for="owm_weather_db">';
            esc_html_e('Select the weather to display:', 'owm-weather');
    echo'</label>';

    echo'<select id="owm_weather_db" name="owmw_widget[weather_db]">';
            
        $options = [];
        
        $query = new WP_Query(array( 'post_type' => array( 'owm-weather' ) ));
    
    while ($query->have_posts()) :
        $query->the_post();
        $options[get_the_ID()] = get_the_title();
    endwhile;

    if (owmw_is_global_multisite() && !is_main_site()) {
        switch_to_blog(get_main_site_id());

        $query = new WP_Query(array( 'post_type' => array( 'owm-weather' ) ));
        
        while ($query->have_posts()) :
            $query->the_post();
            if (get_post_meta(get_the_ID(), '_owmweather_network_share', true) == "yes") {
                $options["m" . get_the_ID()] = get_the_title() . " (shared)";
            }
        endwhile;

        restore_current_blog();
    }
        
        asort($options);
    foreach ($options as $k => $v) {
        echo '<option value="'.esc_attr($k).'" ';
                selected($weather_db, $k);
        echo '>' . $v . '</option>';
    }
            
        echo'</select>';
                
    echo'</p>';
    
    echo'<input name="owmw_widget_post" type="hidden" value="1" />';
}
