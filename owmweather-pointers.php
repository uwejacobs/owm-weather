<?php
defined('ABSPATH') or die('Please don&rsquo;t call the plugin directly. Thanks :)');

function owmw_enqueue_pointer_script_style($hook_suffix)
{
    
    // Assume pointer shouldn't be shown
    $enqueue_pointer_script_style = false;

    // Get array list of dismissed pointers for current user and convert it to array
    $dismissed_pointers = explode(',', get_user_meta(get_current_user_id(), 'dismissed_wp_pointers', true));

    // Check if our pointer is not among dismissed ones
    if (!in_array('owmw_settings_pointer', $dismissed_pointers)) {
        $enqueue_pointer_script_style = true;
        
        // Add footer scripts using callback function
        add_action('admin_print_footer_scripts', 'owmw_pointer_print_scripts');
    }

    // Enqueue pointer CSS and JS files, if needed
    if ($enqueue_pointer_script_style) {
        wp_enqueue_style('wp-pointer');
        wp_enqueue_script('wp-pointer');
    }
}
add_action('admin_enqueue_scripts', 'owmw_enqueue_pointer_script_style');

function owmw_pointer_print_scripts()
{

    $pointer_content  = "<h3>OWM Weather</h3>";
    $pointer_content .= '<p><strong>'.esc_html__('Before starting:', 'owm-weather').'</strong> '.esc_html__('Enter your own OpenWeatherMap API key in', 'owm-weather').' <a href="'.admin_url('options-general.php?page=owmw-settings-admin#tab_advanced').'">'. esc_html__('Advanced Settings!', 'owm-weather').'</a></p>';
    ?>
    
    <script type="text/javascript">
    //<![CDATA[
    jQuery(document).ready( function($) {
        $('#menu-settings').pointer({
            content:        '<?php echo wp_kses_post($pointer_content); ?>',
            position:       {
                                edge:   'left', // arrow direction
                                align:  'center' // vertical alignment
                            },
            pointerWidth:   350,
            close:          function() {
                                $.post( ajaxurl, {
                                        pointer: 'owmw_settings_pointer', // pointer ID
                                        action: 'dismiss-wp-pointer'
                                });
                            }
        }).pointer('open');
    });
    //]]>
    </script>

    <?php
}
