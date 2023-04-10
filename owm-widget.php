<?php
/**
 * OWM Widget
 *
 * Frontend weather widget addon for the OWM Weather plugin.
 *
 * @package     OWM_Widget
 * @link        https://github.com/ControlledChaos/owm-widget
 *
 * Original Plugin Name:  OWM Widget
 * Original Plugin URI:   https://github.com/ControlledChaos/owm-widget
 * Description:  Frontend weather widget addon for the OWM Weather plugin.
 * Version:      1.0.0
 * Author:       Controlled Chaos Design
 * Author URI:   http://ccdzine.com/
 * Original Text Domain:  owm-widget
 * Original Domain Path:  /languages
 * Requires PHP: 5.3
 * Requires at least: 4.7
 */

namespace OWM_Widget;

// Restrict direct access.
if (! defined('ABSPATH')) {
    die;
}

/**
 * Constant: Plugin base name
 *
 * @since 1.0.0
 * @var   string The base name of this plugin file.
 */
define('OWMW_BASENAME', plugin_basename(__FILE__));

/**
 * Constant: Plugin folder path
 *
 * @since 1.0.0
 * @var   string The filesystem directory path (with trailing slash)
 *               for the plugin __FILE__ passed in.
 */
if (! defined('OWMW_PATH')) {
    define('OWMW_PATH', plugin_dir_path(__FILE__));
}

/**
 * Get plugins path
 *
 * Used to check for active plugins with the `is_plugin_active` function.
 */
if (! function_exists('is_plugin_active')) {
    require(ABSPATH . 'wp-admin/includes/plugin.php');
}

// Load required files.
foreach (glob(OWMW_PATH . 'classes/*.php') as $filename) {
    require $filename;
}

// Register the weather shortcode widget.
add_action('widgets_init', function () {
    register_widget('OWM_Widget\Classes\Weather_Shortcode_Widget');
});
