<?php
/*
Plugin Name: OWM Weather
Plugin URI: https://github.com/uwejacobs/owm-weather
Description: Powerful weather plugin for WordPress, based on the OpenWeather API, using custom post types and shortcodes, bundled with a ton of features.
Version: 5.7.2
Author: Uwe Jacobs
Author URI: https://ujsoftware.com/owm-weather-blog/
Original Author: Benjamin DENIS
Original Author URI: https://wpcloudy.com/
License: GPLv2
Text Domain: owm-weather
Network: true
Domain Path: /lang
*/

/*  Copyright 2013 - 2018  Benjamin DENIS  (email : contact@wpcloudy.com)
    Copyright 2021 - 2022  Uwe Jacobs

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define('OWM_WEATHER_VERSION', '5.7.2');

// To prevent calling the plugin directly
if (!function_exists('add_action')) {
    echo 'Please don&rsquo;t call the plugin directly. Thanks :)';
    exit;
}

$GLOBALS['owmw_params'] = [];

function owmw_activation()
{
    global $wpdb;

    if (!current_user_can('activate_plugins')) {
        return;
    }

    $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_owmw%' ");
    $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_owmw%' ");
    if (is_multisite() && is_network_admin() && current_user_can("manage_network_options")) {
        $wpdb->query("DELETE FROM $wpdb->sitemeta WHERE meta_key LIKE '_site_transient_owmw%' ");
        $wpdb->query("DELETE FROM $wpdb->sitemeta WHERE meta_key LIKE 'site__transient_timeout_owmw%' ");
    }

    $post_slug = 'owm-weather-geo-location';
    if (!get_page_by_path($post_slug, OBJECT, 'owm-weather')) {
        $new_post = array(
            'post_type'             => 'owm-weather',
            'post_title'            => 'GeoLocation',
            'post_content'          => '',
            'post_content_filtered' => '',
            'post_status'           => 'publish',
            'post_author'           => 1,
            'post_name'             => $post_slug,
            'ping_status'           => 'closed',
            'comment_status'        => 'closed',
        );
        $new_post_id = wp_insert_post($new_post);

        $post_meta = array(
            "_owmweather_version"                       => OWM_WEATHER_VERSION,
            "_owmweather_unit"                          => "imperial",
            "_owmweather_time_format"                   => "12",
            "_owmweather_custom_timezone"               => "Default",
            "_owmweather_today_date_format"             => "none",
            "_owmweather_size"                          => "medium",
            "_owmweather_font"                          => "Default",
            "_owmweather_template"                      => "card1",
            "_owmweather_iconpack"                      => "ColorAnimated",
            "_owmweather_owm_language"                  => "Default",
            "_owmweather_current_city_name"             => "yes",
            "_owmweather_current_weather_symbol"        => "yes",
            "_owmweather_current_weather_description"   => "yes",
            "_owmweather_display_temperature_unit"      => "yes",
            "_owmweather_current_temperature"           => "yes"
        );

        foreach ($post_meta as $k => $v) {
            update_post_meta($new_post_id, $k, $v);
        }
    }
}
register_activation_hook(__FILE__, 'owmw_activation');

function owmw_deactivation()
{
}
register_deactivation_hook(__FILE__, 'owmw_deactivation');

add_filter('plugin_row_meta', 'plugin_row_meta', 10, 2);

function plugin_row_meta($links, $file)
{
    if ($file == plugin_basename(dirname(__FILE__) . '/owmweather.php')) {
        $links[] = '<a target="_blank" href="https://ujsoftware.com/owm-weather-blog/">' . esc_html__('Blog', 'owm-weather') . '</a>';
    }

    return $links;
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Shortcut settings page
///////////////////////////////////////////////////////////////////////////////////////////////////

add_filter('plugin_action_links', 'owmw_plugin_action_links', 10, 2);

function owmw_plugin_action_links($links, $file)
{
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        if (!is_multisite() || (is_multisite() && !owmw_is_global_multisite() && !is_network_admin())) {
            array_unshift($links, '<a href="' . admin_url('admin.php?page=owmw-settings-admin') . '">' . esc_html__('Settings', 'owm-weather') . '</a>');
        }
    }

    return $links;
}

add_filter('network_admin_plugin_action_links', 'owmw_network_plugin_action_links', 10, 2);

function owmw_network_plugin_action_links($links, $file)
{
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        array_unshift($links, '<a href="' . network_admin_url('admin.php?page=owmw-settings-admin') . '">' . esc_html__('Settings', 'owm-weather') . '</a>');
    }

    return $links;
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Translation
///////////////////////////////////////////////////////////////////////////////////////////////////
function owmw_textdomain()
{
    load_plugin_textdomain('owm-weather', false, dirname(plugin_basename(__FILE__)) . '/lang/');
}

add_action('init', 'owmw_textdomain', 10);

$pressureLabel = [];
$pressureLabel["inHg"] = __('inHg', 'owm-weather');
$pressureLabel["mmHg"] = __('mmHg', 'owm-weather');
$pressureLabel["mb"] = __('mb', 'owm-weather');
$pressureLabel["hPa"] = __('hPa', 'owm-weather');

$windspeedLabel = [];
$windspeedLabel["mi/h"] = __('mi/h', 'owm-weather');
$windspeedLabel["m/s"] = __('m/s', 'owm-weather');
$windspeedLabel["km/h"] = __('km/h', 'owm-weather');
$windspeedLabel["kt"] = __('kt', 'owm-weather');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Admin panel + Dashboard widget
///////////////////////////////////////////////////////////////////////////////////////////////////
function owmw_init()
{
    if (is_admin()) {
        require_once dirname(__FILE__) . '/owmweather-admin.php';
        require_once dirname(__FILE__) . '/owmweather-export.php';
        require_once dirname(__FILE__) . '/owmweather-widget.php';
        require_once dirname(__FILE__) . '/owmweather-pointers.php';
        require_once dirname(__FILE__) . '/owmweather-block.php';
    }

    require_once dirname(__FILE__) . '/owm-widget.php';
}
add_action('plugins_loaded', 'owmw_init');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Enqueue styles Front-end
///////////////////////////////////////////////////////////////////////////////////////////////////
function owmw_async_js($url)
{
    if (strpos($url, '#async') === false) {
        return $url;
    } elseif (is_admin()) {
        return str_replace('#async', '', $url);
    } else {
        return str_replace('#async', '', $url) . "' async='async";
    }
}
add_filter('clean_url', 'owmw_async_js', 11, 1);

function owmw_styles()
{
    wp_enqueue_script('owmw-ajax-js', plugins_url('js/owm-weather-ajax.js', __FILE__), array('jquery'), '', true);
    $owmwAjax = array(
        'owmw_nonce' => wp_create_nonce('owmw_get_weather_nonce'),
        'owmw_url' => admin_url('admin-ajax.php') . "?lang=" . substr(get_locale(), 0, 2),
    );
    wp_add_inline_script('owmw-ajax-js', 'const owmwAjax = ' . json_encode($owmwAjax), 'before');

    wp_register_style('owmweather-css', plugins_url('css/owmweather.min.css', __FILE__));
    wp_enqueue_style('owmweather-css');

    wp_register_style('owmweather-anim-css', plugins_url('css/owmweather-anim.min.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'owmw_styles');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Loads the JS/CSS for Slider1 - Slider2
///////////////////////////////////////////////////////////////////////////////////////////////////

function owmw_add_themes_scripts()
{
    wp_register_style('owmw-flexslider-css', plugins_url('css/flexslider.css', __FILE__));
    wp_register_script('owmw-flexslider-js', plugins_url('js/jquery.flexslider-min.js#async', __FILE__));
    wp_register_style('owmw-bootstrap5-css', plugins_url('css/bootstrap5.stripped.min.css', __FILE__));
    wp_register_script('owmw-bootstrap5-js', plugins_url('js/bootstrap5.bundle.min.js#async', __FILE__));
    wp_register_script('owmw-custom-chart-js', plugins_url('js/custom-chart.min.js#async', __FILE__));
}
add_action('wp_enqueue_scripts', 'owmw_add_themes_scripts', 10, 1);
add_action('admin_enqueue_scripts', 'owmw_add_themes_scripts', 10, 1);

///////////////////////////////////////////////////////////////////////////////////////////////////
//Loads the JS/CSS in admin
///////////////////////////////////////////////////////////////////////////////////////////////////

//Dashboard
function owmw_add_dashboard_scripts()
{
    wp_enqueue_script('owmw-ajax-js', plugins_url('js/owm-weather-ajax.js', __FILE__), array('jquery'), '', true);

    $owmwAjax = array(
        'owmw_nonce' => wp_create_nonce('owmw_get_weather_nonce'),
        'owmw_url' => admin_url('admin-ajax.php') . "?lang=" . substr(get_locale(), 0, 2),
    );
    wp_add_inline_script('owmw-ajax-js', 'const owmwAjax = ' . json_encode($owmwAjax), 'before');

    wp_register_style('owmweather-css', plugins_url('css/owmweather.min.css', __FILE__));
    wp_enqueue_style('owmweather-css');

    wp_register_style('owmweather-anim-css', plugins_url('css/owmweather-anim.min.css', __FILE__));

    if (is_multisite() && owmw_is_global_multisite()) {
        $opts = get_site_option("owmw_option_name");
    } else {
        $opts = get_option("owmw_option_name");
    }
    if (isset($opts["owmw_advanced_disable_modal_js"]) && $opts["owmw_advanced_disable_modal_js"] != 'yes') {
        wp_enqueue_style('owmw-bootstrap5-css');
        // bugbug wp_enqueue_script('owmw-bootstrap5-js');
    }
}
add_action('admin_head', 'owmw_add_dashboard_scripts');

//Admin + Custom Post Type (new, listing)
function owmw_add_admin_scripts($hook)
{

    global $post;

    if ($hook == 'post-new.php' || $hook == 'post.php') {
        if ('owm-weather' === $post->post_type) {
            wp_register_style('owmweather-admin-css', plugins_url('css/owmweather-admin.min.css', __FILE__));
            wp_enqueue_style('owmweather-admin-css');
            wp_register_style('owmweather-toggle-switchy-css', plugins_url('css/toggle-switchy.min.css', __FILE__));
            wp_enqueue_style('owmweather-toggle-switchy-css');

            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('color-picker-js', plugins_url('js/color-picker.js', __FILE__), array( 'wp-color-picker' ));

            wp_enqueue_script('tabs-js', plugins_url('js/tabs.js', __FILE__), array( 'jquery-ui-tabs' ));

            wp_enqueue_script('handlebars-js', plugins_url('js/handlebars-v1.3.0.js', __FILE__), array('typeahead-bundle-js'));
            wp_enqueue_script('typeahead-bundle-js', plugins_url('js/typeahead.bundle.min.js', __FILE__), array('jquery'), '2.0');
            wp_enqueue_script('autocomplete-js', plugins_url('js/owmw-autocomplete.js', __FILE__), '', '2.0', true);
        }
    }
}
add_action('admin_enqueue_scripts', 'owmw_add_admin_scripts', 10, 1);

//OWM Weather Options page
function owmw_add_admin_options_scripts()
{
            wp_register_style('owmweather-admin-css', plugins_url('css/owmweather-admin.min.css', __FILE__));
            wp_enqueue_style('owmweather-admin-css');
            wp_register_style('owmweather-toggle-switchy-css', plugins_url('css/toggle-switchy.min.css', __FILE__));
            wp_enqueue_style('owmweather-toggle-switchy-css');

            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('color-picker-js', plugins_url('js/color-picker.js', __FILE__), array( 'wp-color-picker' ));
            wp_enqueue_script('tabs-js', plugins_url('js/tabs.js', __FILE__), array( 'jquery-ui-tabs' ));
}

if (isset($_GET['page']) && (sanitize_text_field($_GET['page']) == 'owmw-settings-admin')) {
    add_action('admin_enqueue_scripts', 'owmw_add_admin_options_scripts', 10, 1);
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Get all registered post types
///////////////////////////////////////////////////////////////////////////////////////////////////

function owmw_get_post_types()
{
    global $wp_post_types;

    $args = array(
        'show_ui' => true,
    );

    $output = 'names'; // names or objects, note names is the default
    $operator = 'and'; // 'and' or 'or'

    $post_types = get_post_types($args, $output, $operator);
    unset($post_types['attachment']);
    return $post_types;
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Add weather button in tinymce editor
///////////////////////////////////////////////////////////////////////////////////////////////////

//TinyMCE v4.x--------------------------------------------------------------------------------------
add_action('admin_head', 'owmw_add_button_v4');

function owmw_add_button_v4()
{
    global $typenow;

    if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
        return;
    }

    if (! in_array($typenow, owmw_get_post_types())) {
        return;
    }

    if (get_user_option('rich_editing') == 'true') {
        add_filter("mce_external_plugins", "owmw_add_button_v4_plugin");
        add_filter('mce_buttons', 'owmw_add_button_v4_register');
    }
}

function owmw_add_button_v4_plugin($plugin_array)
{
    $plugin_array['owmw_button_v4'] = plugins_url('js/owmw-tinymce.js', __FILE__);
    return $plugin_array;
}

function owmw_add_button_v4_register($buttons)
{
    array_push($buttons, "owmw_button_v4");
    return $buttons;
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Add duplicate link in OWM WEATHER List view
///////////////////////////////////////////////////////////////////////////////////////////////////

function owmw_duplicate_post_as_draft()
{
    global $wpdb;

    if (! ( isset($_GET['post']) || isset($_POST['post'])  || ( isset($_REQUEST['action']) && 'owmw_duplicate_post_as_draft' == sanitize_text_field($_REQUEST['action']) ) )) {
        wp_die('No weather to duplicate has been supplied!');
    }

    if (!isset($_GET['duplicate_nonce']) || !wp_verify_nonce($_GET['duplicate_nonce'], basename(__FILE__))) {
        return;
    }

    $post_id = intval(isset($_GET['post']) ? $_GET['post'] : $_POST['post']);

    $post = get_post($post_id);

    $current_user = wp_get_current_user();
    $new_post_author = $current_user->ID;

    if (isset($post) && $post != null) {
        $args = array(
            'comment_status' => $post->comment_status,
            'ping_status'    => $post->ping_status,
            'post_author'    => $new_post_author,
            'post_content'   => $post->post_content,
            'post_excerpt'   => $post->post_excerpt,
            'post_name'      => $post->post_name,
            'post_parent'    => $post->post_parent,
            'post_password'  => $post->post_password,
            'post_status'    => 'draft',
            'post_title'     => $post->post_title,
            'post_type'      => $post->post_type,
            'to_ping'        => $post->to_ping,
            'menu_order'     => $post->menu_order
        );

        $new_post_id = wp_insert_post($args);

        $taxonomies = get_object_taxonomies($post->post_type);
        foreach ($taxonomies as $taxonomy) {
            $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
            wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
        }

        $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
        if (count($post_meta_infos) != 0) {
            $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
            foreach ($post_meta_infos as $meta_info) {
                $meta_key = $meta_info->meta_key;
                $meta_value = addslashes($meta_info->meta_value);
                $sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
            }
            $sql_query .= implode(" UNION ALL ", $sql_query_sel);
            $wpdb->query($sql_query);
        }

        wp_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
        exit;
    } else {
        wp_die('Weather creation failed, could not find original weather: ' . $post_id);
    }
}
add_action('admin_action_owmw_duplicate_post_as_draft', 'owmw_duplicate_post_as_draft', 999);

function owmw_duplicate_post_link($actions, $post)
{
    if ($post->post_type === 'owm-weather' && current_user_can('edit_posts')) {
        $actions['duplicate'] = '<a title="' . esc_html__('Duplicate this item', 'owm-weather') . '" href="' . wp_nonce_url('admin.php?action=owmw_duplicate_post_as_draft&amp;post=' . $post->ID, basename(__FILE__), 'duplicate_nonce') . '" rel="permalink">' . esc_html__('Duplicate', 'owm-weather') . '</a>';
    }
    return $actions;
}
add_filter('post_row_actions', 'owmw_duplicate_post_link', 999, 2);

///////////////////////////////////////////////////////////////////////////////////////////////////
//Display metabox in Weather Custom Post Type
///////////////////////////////////////////////////////////////////////////////////////////////////

function owmw_init_metabox()
{
    add_meta_box('owmweather_basic', __('OWM Weather Settings', 'owm-weather') . ' - <a href="' . admin_url("options-general.php?page=owmw-settings-admin") . '">' . __('OWM Weather global settings', 'owm-weather') . '</a>', 'owmw_basic', 'owm-weather', 'advanced');
    add_meta_box('owmweather_shortcode', 'OWM Weather Shortcode', 'owmw_shortcode', 'owm-weather', 'side');
}
add_action('add_meta_boxes', 'owmw_init_metabox');

function owmw_shortcode()
{
    echo esc_html__('Copy and paste this shortcode anywhere in posts, pages, text widgets: ', 'owm-weather');
    echo "<div class='shortcode'>";
    echo "<span class='owmw-highlight'>[owm-weather id=\"";
    echo get_the_ID();
    echo "\"/]</span>";
    echo "</div>";

    echo '<div class="shortcode-php">';
    echo esc_html__('If you need to display this weather anywhere in your theme, simply copy and paste this code snippet in your PHP file like sidebar.php: ', 'owm-weather');
    echo "<span class='owmw-highlight'>echo do_shortcode('[owm-weather id=\"" . get_the_ID() . "\"]');</span>";
    echo "</div>";
}

function owmw_basic($post)
{
    $id = $post->ID;

    wp_enqueue_media();
    owmw_media_selector_print_scripts($id);

    $owmw_opt = [];
    $owmw_opt["city"]                       = get_post_meta($id, '_owmweather_city', true);
    $owmw_opt["custom_city_name"]           = get_post_meta($id, '_owmweather_custom_city_name', true);
    $owmw_opt["id_owm"]                     = get_post_meta($id, '_owmweather_id_owm', true);
    $owmw_opt["longitude"]                  = get_post_meta($id, '_owmweather_longitude', true);
    $owmw_opt["latitude"]                   = get_post_meta($id, '_owmweather_latitude', true);
    $owmw_opt["zip"]                        = get_post_meta($id, '_owmweather_zip', true);
    $owmw_opt["country_code"]               = get_post_meta($id, '_owmweather_country_code', true);
    $owmw_opt["zip_country_code"]           = get_post_meta($id, '_owmweather_zip_country_code', true);
    $owmw_opt["temperature_unit"]           = get_post_meta($id, '_owmweather_unit', true);
    $owmw_opt["time_format"]                = get_post_meta($id, '_owmweather_time_format', true);
    $owmw_opt["custom_timezone"]            = get_post_meta($id, '_owmweather_custom_timezone', true);
    $owmw_opt["owm_language"]               = get_post_meta($id, '_owmweather_owm_language', true);
    $owmw_opt["gtag"]                       = get_post_meta($id, '_owmweather_gtag', true);
    $owmw_opt["timemachine"]                = get_post_meta($id, '_owmweather_timemachine', true);
    $owmw_opt["timemachine_date"]             = get_post_meta($id, '_owmweather_timemachine_date', true);
    $owmw_opt["timemachine_time"]             = get_post_meta($id, '_owmweather_timemachine_time', true);
    $owmw_opt["network_share"]              = get_post_meta($id, '_owmweather_network_share', true);
    $owmw_opt["bypass_exclude"]             = get_post_meta($id, '_owmweather_bypass_exclude', true);
    $owmw_opt["current_weather_symbol"]     = get_post_meta($id, '_owmweather_current_weather_symbol', true);
    $owmw_opt["current_city_name"]          = get_post_meta($id, '_owmweather_current_city_name', true);
    $owmw_opt["today_date_format"]          = owmw_getDefault($id, '_owmweather_today_date_format', 'none');
    $owmw_opt["current_weather_description"] = get_post_meta($id, '_owmweather_current_weather_description', true);
    $owmw_opt["sunrise_sunset"]             = get_post_meta($id, '_owmweather_sunrise_sunset', true);
    $owmw_opt["moonrise_moonset"]           = get_post_meta($id, '_owmweather_moonrise_moonset', true);
    $owmw_opt["wind"]                       = get_post_meta($id, '_owmweather_wind', true);
    $owmw_opt["wind_unit"]                  = get_post_meta($id, '_owmweather_wind_unit', true);
    $owmw_opt["wind_icon_direction"]        = owmw_getDefault($id, '_owmweather_wind_icon_direction', 'to');
    $owmw_opt["humidity"]                   = get_post_meta($id, '_owmweather_humidity', true);
    $owmw_opt["dew_point"]                  = get_post_meta($id, '_owmweather_dew_point', true);
    $owmw_opt["pressure"]                   = get_post_meta($id, '_owmweather_pressure', true);
    $owmw_opt["pressure_unit"]              = get_post_meta($id, '_owmweather_pressure_unit', true);
    $owmw_opt["cloudiness"]                 = get_post_meta($id, '_owmweather_cloudiness', true);
    $owmw_opt["precipitation"]              = get_post_meta($id, '_owmweather_precipitation', true);
    $owmw_opt["visibility"]                 = get_post_meta($id, '_owmweather_visibility', true);
    $owmw_opt["uv_index"]                   = get_post_meta($id, '_owmweather_uv_index', true);
    $owmw_opt["text_labels"]                = get_post_meta($id, '_owmweather_text_labels', true);
    $owmw_opt["alerts"]                     = get_post_meta($id, '_owmweather_alerts', true);
    $owmw_opt["alerts_popup"]               = owmw_getDefault($id, '_owmweather_alerts_popup', 'modal');
    $owmw_opt["hours_forecast_no"]          = get_post_meta($id, '_owmweather_hours_forecast_no', true);
    $owmw_opt["hours_time_icons"]           = get_post_meta($id, '_owmweather_hours_time_icons', true);
    $owmw_opt["current_temperature"]        = get_post_meta($id, '_owmweather_current_temperature', true);
    $owmw_opt["current_feels_like"]         = get_post_meta($id, '_owmweather_current_feels_like', true);
    $owmw_opt["display_temperature_unit"]   = get_post_meta($id, '_owmweather_display_temperature_unit', true);
    $owmw_opt["days_forecast_no"]           = get_post_meta($id, '_owmweather_forecast_no', true);
    $owmw_opt["display_length_days_names"]  = owmw_getDefault($id, '_owmweather_display_length_days_names', 'short');
    $owmw_opt["disable_spinner"]            = get_post_meta($id, '_owmweather_disable_spinner', true);
    $owmw_opt["disable_anims"]              = get_post_meta($id, '_owmweather_disable_anims', true);
    $owmw_opt["background_color"]           = get_post_meta($id, '_owmweather_background_color', true);
    $owmw_opt["background_image"]           = get_post_meta($id, '_owmweather_background_image', true);
    $owmw_opt["background_yt_video"]        = get_post_meta($id, '_owmweather_background_yt_video', true);
    $owmw_opt["sunny_background_yt_video"]  = get_post_meta($id, '_owmweather_sunny_background_yt_video', true);
    $owmw_opt["cloudy_background_yt_video"] = get_post_meta($id, '_owmweather_cloudy_background_yt_video', true);
    $owmw_opt["drizzly_background_yt_video"] = get_post_meta($id, '_owmweather_drizzly_background_yt_video', true);
    $owmw_opt["rainy_background_yt_video"]  = get_post_meta($id, '_owmweather_rainy_background_yt_video', true);
    $owmw_opt["snowy_background_yt_video"]  = get_post_meta($id, '_owmweather_snowy_background_yt_video', true);
    $owmw_opt["stormy_background_yt_video"] = get_post_meta($id, '_owmweather_stormy_background_yt_video', true);
    $owmw_opt["foggy_background_yt_video"]  = get_post_meta($id, '_owmweather_foggy_background_yt_video', true);
    $owmw_opt["background_opacity"]         = owmw_getDefault($id, '_owmweather_background_opacity', "0.8");
    $owmw_opt["text_color"]                 = get_post_meta($id, '_owmweather_text_color', true);
    $owmw_opt["border_color"]               = get_post_meta($id, '_owmweather_border_color', true);
    $owmw_opt["border_width"]               = owmw_getDefault($id, '_owmweather_border_width', $owmw_opt["border_color"] == '' ? '0' : '1');
    $owmw_opt["border_style"]               = get_post_meta($id, '_owmweather_border_style', true);
    $owmw_opt["border_radius"]              = owmw_getDefault($id, '_owmweather_border_radius', '0');
    $owmw_opt["custom_css"]                 = get_post_meta($id, '_owmweather_custom_css', true);
    $owmw_opt["size"]                       = get_post_meta($id, '_owmweather_size', true);
    $owmw_opt["owm_link"]                   = get_post_meta($id, '_owmweather_owm_link', true);
    $owmw_opt["last_update"]                = get_post_meta($id, '_owmweather_last_update', true);
    $owmw_opt["font"]                       = get_post_meta($id, '_owmweather_font', true);
    $owmw_opt["template"]                   = get_post_meta($id, '_owmweather_template', true);
    $owmw_opt["iconpack"]                   = get_post_meta($id, '_owmweather_iconpack', true);
    $owmw_opt["map"]                        = get_post_meta($id, '_owmweather_map', true);
    $owmw_opt["map_height"]                 = get_post_meta($id, '_owmweather_map_height', true);
    $owmw_opt["map_opacity"]                = owmw_getDefault($id, '_owmweather_map_opacity', "0.8");
    $owmw_opt["map_zoom"]                   = owmw_getDefault($id, '_owmweather_map_zoom', '9');
    $owmw_opt["map_disable_zoom_wheel"]     = get_post_meta($id, '_owmweather_map_disable_zoom_wheel', true);
    $owmw_opt["map_cities"]                 = get_post_meta($id, '_owmweather_map_cities', true);
    $owmw_opt["map_cities_legend"]          = get_post_meta($id, '_owmweather_map_cities_legend', true);
    $owmw_opt["map_cities_on"]              = get_post_meta($id, '_owmweather_map_cities_on', true);
    $owmw_opt["map_clouds"]                 = get_post_meta($id, '_owmweather_map_clouds', true);
    $owmw_opt["map_clouds_legend"]          = get_post_meta($id, '_owmweather_map_clouds_legend', true);
    $owmw_opt["map_clouds_on"]              = get_post_meta($id, '_owmweather_map_clouds_on', true);
    $owmw_opt["map_precipitation"]          = get_post_meta($id, '_owmweather_map_precipitation', true);
    $owmw_opt["map_precipitation_legend"]   = get_post_meta($id, '_owmweather_map_precipitation_legend', true);
    $owmw_opt["map_precipitation_on"]       = get_post_meta($id, '_owmweather_map_precipitation_on', true);
    $owmw_opt["map_rain"]                   = get_post_meta($id, '_owmweather_map_rain', true);
    $owmw_opt["map_rain_legend"]            = get_post_meta($id, '_owmweather_map_rain_legend', true);
    $owmw_opt["map_rain_on"]                = get_post_meta($id, '_owmweather_map_rain_on', true);
    $owmw_opt["map_snow"]                   = get_post_meta($id, '_owmweather_map_snow', true);
    $owmw_opt["map_snow_legend"]            = get_post_meta($id, '_owmweather_map_snow_legend', true);
    $owmw_opt["map_snow_on"]                = get_post_meta($id, '_owmweather_map_snow_on', true);
    $owmw_opt["map_wind"]                   = get_post_meta($id, '_owmweather_map_wind', true);
    $owmw_opt["map_wind_legend"]            = get_post_meta($id, '_owmweather_map_wind_legend', true);
    $owmw_opt["map_wind_on"]                = get_post_meta($id, '_owmweather_map_wind_on', true);
    $owmw_opt["map_temperature"]            = get_post_meta($id, '_owmweather_map_temperature', true);
    $owmw_opt["map_temperature_legend"]     = get_post_meta($id, '_owmweather_map_temperature_legend', true);
    $owmw_opt["map_temperature_on"]         = get_post_meta($id, '_owmweather_map_temperature_on', true);
    $owmw_opt["map_pressure"]               = get_post_meta($id, '_owmweather_map_pressure', true);
    $owmw_opt["map_pressure_legend"]        = get_post_meta($id, '_owmweather_map_pressure_legend', true);
    $owmw_opt["map_pressure_on"]            = get_post_meta($id, '_owmweather_map_pressure_on', true);
    $owmw_opt["map_windrose"]               = get_post_meta($id, '_owmweather_map_windrose', true);
    $owmw_opt["map_windrose_legend"]        = get_post_meta($id, '_owmweather_map_windrose_legend', true);
    $owmw_opt["map_windrose_on"]            = get_post_meta($id, '_owmweather_map_windrose_on', true);

    $owmw_opt["chart_height"]               = owmw_getDefault($id, '_owmweather_chart_height', '400');
    $owmw_opt["chart_text_color"]           = owmw_getDefault($id, '_owmweather_chart_text_color', '#111');
    $owmw_opt["chart_night_color"]          = owmw_getDefault($id, '_owmweather_chart_night_color', '#c8c8c8');
    $owmw_opt["chart_background_color"]     = owmw_getDefault($id, '_owmweather_chart_background_color', '#fff');
    $owmw_opt["chart_border_color"]         = owmw_getDefault($id, '_owmweather_chart_border_color', '');
    $owmw_opt["chart_border_width"]         = owmw_getDefault($id, '_owmweather_chart_border_width', $owmw_opt["chart_border_color"] == '' ? '0' : '1');
    $owmw_opt["chart_border_style"]         = get_post_meta($id, '_owmweather_chart_border_style', true);
    $owmw_opt["chart_border_radius"]        = owmw_getDefault($id, '_owmweather_chart_border_radius', '0');
    $owmw_opt["chart_temperature_color"]    = owmw_getDefault($id, '_owmweather_chart_temperature_color', '#d5202a');
    $owmw_opt["chart_feels_like_color"]     = owmw_getDefault($id, '_owmweather_chart_feels_like_color', '#f83');
    $owmw_opt["chart_dew_point_color"]      = owmw_getDefault($id, '_owmweather_chart_dew_point_color', '#5b9f49');
    $owmw_opt["chart_cloudiness_color"]     = owmw_getDefault($id, '_owmweather_chart_cloudiness_color', '#a3a3a3');
    $owmw_opt["chart_rain_chance_color"]    = owmw_getDefault($id, '_owmweather_chart_rain_chance_color', '#15aadc');
    $owmw_opt["chart_humidity_color"]       = owmw_getDefault($id, '_owmweather_chart_humidity_color', '#87c404');
    $owmw_opt["chart_pressure_color"]       = owmw_getDefault($id, '_owmweather_chart_pressure_color', '#1e2023');
    $owmw_opt["chart_rain_amt_color"]       = owmw_getDefault($id, '_owmweather_chart_rain_amt_color', '#a3a3a3');
    $owmw_opt["chart_snow_amt_color"]       = owmw_getDefault($id, '_owmweather_chart_snow_amt_color', '#15aadc');
    $owmw_opt["chart_wind_speed_color"]     = owmw_getDefault($id, '_owmweather_chart_wind_speed_color', '#a3a3a3');
    $owmw_opt["chart_wind_gust_color"]      = owmw_getDefault($id, '_owmweather_chart_wind_gust_color', '#15aadc');

    $owmw_opt["table_background_color"]     = owmw_getDefault($id, '_owmweather_table_background_color', '');
    $owmw_opt["table_border_color"]         = owmw_getDefault($id, '_owmweather_table_border_color', '');
    $owmw_opt["table_border_width"]         = owmw_getDefault($id, '_owmweather_table_border_width', $owmw_opt["table_border_color"] == '' ? '0' : '1');
    $owmw_opt["table_border_style"]         = get_post_meta($id, '_owmweather_table_border_style', true);
    $owmw_opt["table_border_radius"]            = owmw_getDefault($id, '_owmweather_table_border_radius', '0');
    $owmw_opt["table_text_color"]           = owmw_getDefault($id, '_owmweather_table_text_color', '');

    $owmw_opt["tabbed_btn_text_color"]      = owmw_getDefault($id, '_owmweather_tabbed_btn_text_color', '#212529');
    $owmw_opt["tabbed_btn_background_color"] = owmw_getDefault($id, '_owmweather_tabbed_btn_background_color', '#f1f1f1');
    $owmw_opt["tabbed_btn_active_color"]    = owmw_getDefault($id, '_owmweather_tabbed_btn_active_color', '#ccc');
    $owmw_opt["tabbed_btn_hover_color"]     = owmw_getDefault($id, '_owmweather_tabbed_btn_hover_color', '#ddd');

    $owmw_opt["sunny_text_color"]           = get_post_meta($id, '_owmweather_sunny_text_color', true);
    $owmw_opt["sunny_background_color"]     = get_post_meta($id, '_owmweather_sunny_background_color', true);
    $owmw_opt["sunny_background_image"]     = get_post_meta($id, '_owmweather_sunny_background_image', true);
    $owmw_opt["cloudy_text_color"]          = get_post_meta($id, '_owmweather_cloudy_text_color', true);
    $owmw_opt["cloudy_background_color"]    = get_post_meta($id, '_owmweather_cloudy_background_color', true);
    $owmw_opt["cloudy_background_image"]    = get_post_meta($id, '_owmweather_cloudy_background_image', true);
    $owmw_opt["drizzly_text_color"]         = get_post_meta($id, '_owmweather_drizzly_text_color', true);
    $owmw_opt["drizzly_background_color"]   = get_post_meta($id, '_owmweather_drizzly_background_color', true);
    $owmw_opt["drizzly_background_image"]   = get_post_meta($id, '_owmweather_drizzly_background_image', true);
    $owmw_opt["rainy_text_color"]           = get_post_meta($id, '_owmweather_rainy_text_color', true);
    $owmw_opt["rainy_background_color"]     = get_post_meta($id, '_owmweather_rainy_background_color', true);
    $owmw_opt["rainy_background_image"]     = get_post_meta($id, '_owmweather_rainy_background_image', true);
    $owmw_opt["snowy_text_color"]           = get_post_meta($id, '_owmweather_snowy_text_color', true);
    $owmw_opt["snowy_background_color"]     = get_post_meta($id, '_owmweather_snowy_background_color', true);
    $owmw_opt["snowy_background_image"]     = get_post_meta($id, '_owmweather_snowy_background_image', true);
    $owmw_opt["stormy_text_color"]          = get_post_meta($id, '_owmweather_stormy_text_color', true);
    $owmw_opt["stormy_background_color"]    = get_post_meta($id, '_owmweather_stormy_background_color', true);
    $owmw_opt["stormy_background_image"]    = get_post_meta($id, '_owmweather_stormy_background_image', true);
    $owmw_opt["foggy_text_color"]           = get_post_meta($id, '_owmweather_foggy_text_color', true);
    $owmw_opt["foggy_background_color"]     = get_post_meta($id, '_owmweather_foggy_background_color', true);
    $owmw_opt["foggy_background_image"]     = get_post_meta($id, '_owmweather_foggy_background_image', true);

    function owmw_get_admin_api_key2()
    {
        if (is_multisite() && owmw_is_global_multisite()) {
            $opts = get_site_option("owmw_option_name");
        } else {
            $opts = get_option("owmw_option_name");
        }

        if (!empty($opts["owmw_advanced_api_key"])) {
            return $opts["owmw_advanced_api_key"];
        } else {
            return '46c433f6ba7dd4d29d5718dac3d7f035';//bugbug
        }
    }

    ob_start();
    ?>
<div id="owmweather-tabs">
        <ul>
            <?php if (is_main_site() && owmw_is_global_multisite()) { ?>
            <li><a href="#tabs-0"><?php esc_html_e('Network', 'owm-weather') ?></a></li>
            <?php } ?>
            <li><a href="#tabs-1"><?php esc_html_e('Basic', 'owm-weather') ?></a></li>
            <li><a href="#tabs-2"><?php esc_html_e('Display', 'owm-weather') ?></a></li>
            <li><a href="#tabs-3"><?php esc_html_e('Layout', 'owm-weather') ?></a></li>
            <li><a href="#tabs-4"><?php esc_html_e('Weather-Based', 'owm-weather') ?></a></li>
            <li><a href="#tabs-5"><?php esc_html_e('Map', 'owm-weather') ?></a></li>
        </ul>
        <?php if (is_main_site() && owmw_is_global_multisite()) { ?>
        <div id="tabs-0">
            <p class=" subsection-title">
                <?php esc_html_e('Multisite', 'owm-weather') ?>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_network_share_meta" data-size="sm" data-text="false" data-color="green">
                  <input <?php echo checked($owmw_opt["network_share"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_network_share_meta" name="owmweather_network_share"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php esc_html_e('Network Shared', 'owm-weather') ?></span>
                </label>
            </p>
        </div>
        <?php } ?>
        <div id="tabs-1">
            <p class=" subsection-title">
                <?php esc_html_e('Get Weather By ...', 'owm-weather') ?>
            </p>
    <?php
    if (!empty($owmw_opt["id_owm"])) {
        $fragment_active = 0;
    } elseif (!empty($owmw_opt["latitude"]) || !empty($owmw_opt["longitude"])) {
        $fragment_active = 1;
    } elseif (!empty($owmw_opt["zip"]) || !empty($owmw_opt["zip_country_code"])) {
        $fragment_active = 2;
    } elseif (!empty($owmw_opt["city"]) || !empty($owmw_opt["country_code"])) {
        $fragment_active = 3;
    } else {
        $fragment_active = 4;
    }
    ?>
            <div id="owmweather-owm-param" data-active-tab="<?php echo $fragment_active ?>">
                <ul>
                    <li class="<?php echo $fragment_active == 0 ? "fw-bold" : ""; ?>"><a href="#fragment-1"><?php esc_html_e('City Id', 'owm-weather') ?></a></li>
                    <li class="<?php echo $fragment_active == 1 ? "fw-bold" : ""; ?>"><a href="#fragment-2"><?php esc_html_e('Longitude/Latitude', 'owm-weather') ?></a></li>
                    <li class="<?php echo $fragment_active == 2 ? "fw-bold" : ""; ?>"><a href="#fragment-3"><?php esc_html_e('Zip/Country', 'owm-weather') ?></a></li>
                    <li class="<?php echo $fragment_active == 3 ? "fw-bold" : ""; ?>"><a href="#fragment-4"><?php esc_html_e('City/Country', 'owm-weather') ?></a></li>
                    <li class="<?php echo $fragment_active == 4 ? "fw-bold" : ""; ?>"><a href="#fragment-5"><?php esc_html_e('Visitor\'s Location', 'owm-weather') ?></a></li>
                </ul>
                  <div id="fragment-1">
                        <p>
                        <label for="owmweather_id_owm_meta"><?php esc_html_e('OpenWeatherMap City Id', 'owm-weather') ?><span class="mandatory">*</span> <a href="https://openweathermap.org/find?q=" target="_blank"> <?php esc_html_e('Find my City Id', 'owm-weather') ?></a><span class="dashicons dashicons-external"></span></label>
                        <p></p>
                        <input id="owmweather_id_owm" type="number" name="owmweather_id_owm" value="<?php echo esc_attr($owmw_opt["id_owm"]) ?>" />
                    </p>
                  </div>
                  <div id="fragment-2">
                    <p>
                        <label for="owmweather_latitude_meta"><?php esc_html_e('Latitude', 'owm-weather') ?><span class="mandatory">*</span></label>
                        <input id="owmweather_latitude_meta" type="number" min="-90" max="90" step="0.0000001" name="owmweather_latitude" value="<?php echo esc_attr($owmw_opt["latitude"]) ?>" />
                    </p>
                    <p>
                        <label for="owmweather_longitude_meta"><?php esc_html_e('Longitude', 'owm-weather') ?><span class="mandatory">*</span></label>
                        <input id="owmweather_longitude_meta" type="number" min="-180" max="180" step="0.000001" name="owmweather_longitude" value="<?php echo esc_attr($owmw_opt["longitude"]) ?>" />
                    </p>
                  </div>
                  <div id="fragment-3">
                    <p>
                        <label for="owmweather_zip_meta"><?php esc_html_e('Zip code', 'owm-weather') ?><span class="mandatory">*</span></label>
                        <input id="owmweather_zip_meta" name="owmweather_zip" value="<?php echo esc_attr($owmw_opt["zip"]) ?>" />
                    </p>
                    <p>
                        <label for="owmweather_zip_country_meta"><?php esc_html_e('2-letter country code', 'owm-weather') ?>(<?php esc_html_e("Default: US", 'owm-weather') ?>)</label>
                        <input id="owmweather_zip_country_meta" class="countrycodes typeahead" type="text" name="owmweather_zip_country_code" maxlength="2" value="<?php echo esc_attr($owmw_opt["zip_country_code"]) ?>" />
                    </p>
                  </div>
                  <div id="fragment-4">
                    <p>
                        <label for="owmweather_city_meta"><?php esc_html_e('City', 'owm-weather') ?><span class="mandatory">*</span></label>
                        <input id="owmweather_city_meta" data_appid="<?php echo esc_attr_e(owmw_get_admin_api_key2()) ?>" class="cities typeahead" type="text" name="owmweather_city" placeholder="<?php esc_attr_e('Enter your city', 'owm-weather') ?>" value="<?php echo esc_attr($owmw_opt["city"]) ?>" />
                    </p>
                    <p>
                        <label for="owmweather_country_meta"><?php esc_html_e('Country', 'owm-weather') ?><span class="mandatory">*</span></label>
                        <input id="owmweather_country_meta" class="countries typeahead" type="text" name="owmweather_country_code" value="<?php echo esc_attr($owmw_opt["country_code"]) ?>" />
                    </p>
                  </div>
                  <div id="fragment-5">
                    <p><em><?php esc_html_e('Leave City Id, Longitude/Latitude, Zip/Country and City/Country empty to use the visitor\'s location.', 'owm-weather') ?></em></p>
                  </div>
              </div>
            <p class=" subsection-title">
                <?php esc_html_e('Historical Data', 'owm-weather') ?>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_timemachine_meta" data-size="sm" data-text="false" data-color="green">
                  <input <?php echo checked($owmw_opt["timemachine"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_timemachine_meta" name="owmweather_timemachine"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php echo esc_html_e('Time Machine', 'owm-weather') . " (OneCall Subscription)" ?></span>
                </label>
            </p>
            </p>
            <p>
                <label for="owmweather_timemachine_date_meta"><?php esc_html_e('Date', 'owm-weather') ?></label>
                <input id="owmweather_timemachine_date_meta" type="date" name="owmweather_timemachine_date" value="<?php echo esc_attr($owmw_opt["timemachine_date"]) ?>" />
            </p>
            <p>
                <label for="owmweather_timemachine_time_meta"><?php esc_html_e('Time', 'owm-weather') ?></label>
                <input id="owmweather_timemachine_time_meta" type="time" name="owmweather_timemachine_time" value="<?php echo esc_attr($owmw_opt["timemachine_time"]) ?>" />
            </p>
            <p class=" subsection-title">
                <?php esc_html_e('Basic', 'owm-weather') ?>
            </p>
            <p>
                <label for="owmweather_custom_city_name_meta"><?php esc_html_e('Custom City Title', 'owm-weather') ?></label>
                <input id="owmweather_custom_city_name_meta" type="text" name="owmweather_custom_city_name" value="<?php echo esc_attr($owmw_opt["custom_city_name"]) ?>" />
            </p>
            <p>
                <label for="unit_meta"><?php esc_html_e('Measurement System', 'owm-weather') ?></label>
                <select name="owmweather_unit">
                    <option <?php echo selected('imperial', $owmw_opt["temperature_unit"], false) ?>value="imperial"><?php esc_html_e('Imperial', 'owm-weather') ?></option>
                    <option <?php echo selected('metric', $owmw_opt["temperature_unit"], false) ?>value="metric"><?php esc_html_e('Metric', 'owm-weather') ?></option>
                </select>
            </p>
            <p>
                <label for="owmweather_time_format_meta"><?php esc_html_e('Time Format', 'owm-weather') ?></label>
                <select name="owmweather_time_format">
                    <option <?php echo selected('12', $owmw_opt["time_format"], false) ?>value="12"><?php esc_html_e('12 h', 'owm-weather') ?></option>
                    <option <?php echo selected('24', $owmw_opt["time_format"], false) ?>value="24"><?php esc_html_e('24 h', 'owm-weather') ?></option>
                </select>
            </p>
            <p>
                <label for="owmweather_custom_timezone_meta"><?php esc_html_e('Timezone (Default: WordPress General Settings)', 'owm-weather') ?></label>
                <select name="owmweather_custom_timezone" id="owmweather_custom_timezone_meta">
                    <option <?php echo selected('Default', $owmw_opt["custom_timezone"], false) ?>value="Default"><?php esc_html_e('WordPress Timezone', 'owm-weather') ?></option>
                    <option <?php echo selected('local', $owmw_opt["custom_timezone"], false) ?>value="local"><?php esc_html_e('Local Timezone', 'owm-weather') ?></option>
                    <option <?php echo selected('-12', $owmw_opt["custom_timezone"], false) ?>value="-12"><?php esc_html_e('UTC -12', 'owm-weather') ?></option>
                    <option <?php echo selected('-11', $owmw_opt["custom_timezone"], false) ?>value="-11"><?php esc_html_e('UTC -11', 'owm-weather') ?></option>
                    <option <?php echo selected('-10', $owmw_opt["custom_timezone"], false) ?>value="-10"><?php esc_html_e('UTC -10', 'owm-weather') ?></option>
                    <option <?php echo selected('-9', $owmw_opt["custom_timezone"], false) ?>value="-9"><?php esc_html_e('UTC -9', 'owm-weather') ?></option>
                    <option <?php echo selected('-8', $owmw_opt["custom_timezone"], false) ?>value="-8"><?php esc_html_e('UTC -8', 'owm-weather') ?></option>
                    <option <?php echo selected('-7', $owmw_opt["custom_timezone"], false) ?>value="-7"><?php esc_html_e('UTC -7', 'owm-weather') ?></option>
                    <option <?php echo selected('-6', $owmw_opt["custom_timezone"], false) ?>value="-6"><?php esc_html_e('UTC -6', 'owm-weather') ?></option>
                    <option <?php echo selected('-5', $owmw_opt["custom_timezone"], false) ?>value="-5"><?php esc_html_e('UTC -5', 'owm-weather') ?></option>
                    <option <?php echo selected('-4', $owmw_opt["custom_timezone"], false) ?>value="-4"><?php esc_html_e('UTC -4', 'owm-weather') ?></option>
                    <option <?php echo selected('-3', $owmw_opt["custom_timezone"], false) ?>value="-3"><?php esc_html_e('UTC -3', 'owm-weather') ?></option>
                    <option <?php echo selected('-2', $owmw_opt["custom_timezone"], false) ?>value="-2"><?php esc_html_e('UTC -2', 'owm-weather') ?></option>
                    <option <?php echo selected('-1', $owmw_opt["custom_timezone"], false) ?>value="-1"><?php esc_html_e('UTC -1', 'owm-weather') ?></option>
                    <option <?php echo selected('0', $owmw_opt["custom_timezone"], false) ?>value="0"><?php esc_html_e('UTC 0', 'owm-weather') ?></option>
                    <option <?php echo selected('1', $owmw_opt["custom_timezone"], false) ?>value="1"><?php esc_html_e('UTC +1', 'owm-weather') ?></option>
                    <option <?php echo selected('2', $owmw_opt["custom_timezone"], false) ?>value="2"><?php esc_html_e('UTC +2', 'owm-weather') ?></option>
                    <option <?php echo selected('3', $owmw_opt["custom_timezone"], false) ?>value="3"><?php esc_html_e('UTC +3', 'owm-weather') ?></option>
                    <option <?php echo selected('4', $owmw_opt["custom_timezone"], false) ?>value="4"><?php esc_html_e('UTC +4', 'owm-weather') ?></option>
                    <option <?php echo selected('5', $owmw_opt["custom_timezone"], false) ?>value="5"><?php esc_html_e('UTC +5', 'owm-weather') ?></option>
                    <option <?php echo selected('6', $owmw_opt["custom_timezone"], false) ?>value="6"><?php esc_html_e('UTC +6', 'owm-weather') ?></option>
                    <option <?php echo selected('7', $owmw_opt["custom_timezone"], false) ?>value="7"><?php esc_html_e('UTC +7', 'owm-weather') ?></option>
                    <option <?php echo selected('8', $owmw_opt["custom_timezone"], false) ?>value="8"><?php esc_html_e('UTC +8', 'owm-weather') ?></option>
                    <option <?php echo selected('9', $owmw_opt["custom_timezone"], false) ?>value="9"><?php esc_html_e('UTC +9', 'owm-weather') ?></option>
                    <option <?php echo selected('10', $owmw_opt["custom_timezone"], false) ?>value="10"><?php esc_html_e('UTC +10', 'owm-weather') ?></option>
                    <option <?php echo selected('11', $owmw_opt["custom_timezone"], false) ?>value="11"><?php esc_html_e('UTC +11', 'owm-weather') ?></option>
                    <option <?php echo selected('12', $owmw_opt["custom_timezone"], false) ?>value="12"><?php esc_html_e('UTC +12', 'owm-weather') ?></option>
                </select>
            </p>
            <p>
                <label for="owmweather_owm_language_meta"><?php esc_html_e('OpenWeatherMap Language', 'owm-weather') ?></label>
                <p><i><?php esc_html_e('This is the language for the data from OpenWeatherMap, not for this plugin. If set to Default, it will try to use the WordPress site language first with fallback to English.', 'owm-weather') ?></i></p>
                <select name="owmweather_owm_language" id="owmweather_owm_language_meta">
                    <option <?php echo selected('Default', $owmw_opt["owm_language"], false) ?>value="Default"><?php esc_html_e('Default', 'owm-weather') ?></option>
                    <option <?php echo selected('af', $owmw_opt["owm_language"], false) ?>value="af"><?php esc_html_e('Afrikaans', 'owm-weather') ?></option>
                    <option <?php echo selected('al', $owmw_opt["owm_language"], false) ?>value="al"><?php esc_html_e('Albanian', 'owm-weather') ?></option>
                    <option <?php echo selected('ar', $owmw_opt["owm_language"], false) ?>value="ar"><?php esc_html_e('Arabic', 'owm-weather') ?></option>
                    <option <?php echo selected('az', $owmw_opt["owm_language"], false) ?>value="az"><?php esc_html_e('Azerbaijani', 'owm-weather') ?></option>
                    <option <?php echo selected('eu', $owmw_opt["owm_language"], false) ?>value="eu"><?php esc_html_e('Basque', 'owm-weather') ?></option>
                    <option <?php echo selected('bg', $owmw_opt["owm_language"], false) ?>value="bg"><?php esc_html_e('Bulgarian', 'owm-weather') ?></option>
                    <option <?php echo selected('ca', $owmw_opt["owm_language"], false) ?>value="ca"><?php esc_html_e('Catalan', 'owm-weather') ?></option>
                    <option <?php echo selected('zh_cn', $owmw_opt["owm_language"], false) ?>value="zh_cn"><?php esc_html_e('Chinese Simplified', 'owm-weather') ?></option>
                    <option <?php echo selected('zh_tw', $owmw_opt["owm_language"], false) ?>value="zh_tw"><?php esc_html_e('Chinese Traditional', 'owm-weather') ?></option>
                    <option <?php echo selected('hr', $owmw_opt["owm_language"], false) ?>value="hr"><?php esc_html_e('Croatian', 'owm-weather') ?></option>
                    <option <?php echo selected('cz', $owmw_opt["owm_language"], false) ?>value="cz"><?php esc_html_e('Czech', 'owm-weather') ?></option>
                    <option <?php echo selected('da', $owmw_opt["owm_language"], false) ?>value="da"><?php esc_html_e('Danish', 'owm-weather') ?></option>
                    <option <?php echo selected('nl', $owmw_opt["owm_language"], false) ?>value="nl"><?php esc_html_e('Dutch', 'owm-weather') ?></option>
                    <option <?php echo selected('en', $owmw_opt["owm_language"], false) ?>value="en"><?php esc_html_e('English', 'owm-weather') ?></option>
                    <option <?php echo selected('fi', $owmw_opt["owm_language"], false) ?>value="fi"><?php esc_html_e('Finnish', 'owm-weather') ?></option>
                    <option <?php echo selected('fr', $owmw_opt["owm_language"], false) ?>value="fr"><?php esc_html_e('French', 'owm-weather') ?></option>
                    <option <?php echo selected('gl', $owmw_opt["owm_language"], false) ?>value="gl"><?php esc_html_e('Galician', 'owm-weather') ?></option>
                    <option <?php echo selected('de', $owmw_opt["owm_language"], false) ?>value="de"><?php esc_html_e('German', 'owm-weather') ?></option>
                    <option <?php echo selected('el', $owmw_opt["owm_language"], false) ?>value="el"><?php esc_html_e('Greek', 'owm-weather') ?></option>
                    <option <?php echo selected('he', $owmw_opt["owm_language"], false) ?>value="he"><?php esc_html_e('Hebrew', 'owm-weather') ?></option>
                    <option <?php echo selected('hi', $owmw_opt["owm_language"], false) ?>value="hi"><?php esc_html_e('Hindi', 'owm-weather') ?></option>
                    <option <?php echo selected('hu', $owmw_opt["owm_language"], false) ?>value="hu"><?php esc_html_e('Hungarian', 'owm-weather') ?></option>
                    <option <?php echo selected('id', $owmw_opt["owm_language"], false) ?>value="id"><?php esc_html_e('Indonesian', 'owm-weather') ?></option>
                    <option <?php echo selected('it', $owmw_opt["owm_language"], false) ?>value="it"><?php esc_html_e('Italian', 'owm-weather') ?></option>
                    <option <?php echo selected('ja', $owmw_opt["owm_language"], false) ?>value="ja"><?php esc_html_e('Japanese', 'owm-weather') ?></option>
                    <option <?php echo selected('kr', $owmw_opt["owm_language"], false) ?>value="kr"><?php esc_html_e('Korean', 'owm-weather') ?></option>
                    <option <?php echo selected('la', $owmw_opt["owm_language"], false) ?>value="la"><?php esc_html_e('Latvian', 'owm-weather') ?></option>
                    <option <?php echo selected('lt', $owmw_opt["owm_language"], false) ?>value="lt"><?php esc_html_e('Lithuanian', 'owm-weather') ?></option>
                    <option <?php echo selected('mk', $owmw_opt["owm_language"], false) ?>value="mk"><?php esc_html_e('Macedonian', 'owm-weather') ?></option>
                    <option <?php echo selected('no', $owmw_opt["owm_language"], false) ?>value="no"><?php esc_html_e('Norwegian', 'owm-weather') ?></option>
                    <option <?php echo selected('fa', $owmw_opt["owm_language"], false) ?>value="fa"><?php esc_html_e('Persian (Farsi)', 'owm-weather') ?></option>
                    <option <?php echo selected('pl', $owmw_opt["owm_language"], false) ?>value="pl"><?php esc_html_e('Polish', 'owm-weather') ?></option>
                    <option <?php echo selected('pt', $owmw_opt["owm_language"], false) ?>value="pt"><?php esc_html_e('Portuguese', 'owm-weather') ?></option>
                    <option <?php echo selected('pt', $owmw_opt["owm_language"], false) ?>value="pt"><?php esc_html_e('Português Brasil', 'owm-weather') ?></option>
                    <option <?php echo selected('ro', $owmw_opt["owm_language"], false) ?>value="ro"><?php esc_html_e('Romanian', 'owm-weather') ?></option>
                    <option <?php echo selected('ru', $owmw_opt["owm_language"], false) ?>value="ru"><?php esc_html_e('Russian', 'owm-weather') ?></option>
                    <option <?php echo selected('sr', $owmw_opt["owm_language"], false) ?>value="sr"><?php esc_html_e('Serbian', 'owm-weather') ?></option>
                    <option <?php echo selected('sv', $owmw_opt["owm_language"], false) ?>value="se"><?php esc_html_e('Swedish', 'owm-weather') ?></option>
                    <option <?php echo selected('sk', $owmw_opt["owm_language"], false) ?>value="sk"><?php esc_html_e('Slovak', 'owm-weather') ?></option>
                    <option <?php echo selected('sl', $owmw_opt["owm_language"], false) ?>value="sl"><?php esc_html_e('Slovenian', 'owm-weather') ?></option>
                    <option <?php echo selected('sp', $owmw_opt["owm_language"], false) ?>value="es"><?php esc_html_e('Spanish', 'owm-weather') ?></option>
                    <option <?php echo selected('th', $owmw_opt["owm_language"], false) ?>value="th"><?php esc_html_e('Thai', 'owm-weather') ?></option>
                    <option <?php echo selected('tr', $owmw_opt["owm_language"], false) ?>value="tr"><?php esc_html_e('Turkish', 'owm-weather') ?></option>
                    <option <?php echo selected('ua', $owmw_opt["owm_language"], false) ?>value="uk"><?php esc_html_e('Ukrainian', 'owm-weather') ?></option>
                    <option <?php echo selected('vi', $owmw_opt["owm_language"], false) ?>value="vi"><?php esc_html_e('Vietnamese', 'owm-weather') ?></option>
                    <option <?php echo selected('zu', $owmw_opt["owm_language"], false) ?>value="zu"><?php esc_html_e('Zulu', 'owm-weather') ?></option>
                </select>
            </p>
            <p class="misc subsection-title">
                <?php esc_html_e('Miscellaneous', 'owm-weather') ?>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_gtag_meta" data-size="sm" data-text="false" data-color="green">
                  <input <?php echo checked($owmw_opt["gtag"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_gtag_meta" name="owmweather_gtag"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php echo esc_html_e('Google Tag Manager DataLayer', 'owm-weather') . " (OneCall Subscription)" ?></span>
                </label>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_bypass_exclude_meta" data-size="sm" data-text="false" data-color="green">
                  <input <?php echo checked($owmw_opt["bypass_exclude"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_bypass_exclude_meta" name="owmweather_bypass_exclude"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php esc_html_e('Exclude from System Settings and Parameter Bypass', 'owm-weather') ?></span>
                </label>
            </p>
        </div>
        <div id="tabs-2">
            <p style="border: 2px solid;padding: 5px;">
                <?php esc_html_e('Select the information you would like to show on your weather shortcode.', 'owm-weather') ?>
            </p>
            <p class="owmw-dates subsection-title">
                <?php esc_html_e('Current Weather', 'owm-weather') ?>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_current_city_name_meta" data-size="sm" data-text="false" data-color="green">
                  <input <?php echo checked($owmw_opt["current_city_name"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_current_city_name_meta" name="owmweather_current_city_name"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php esc_html_e('Current Weather City Name', 'owm-weather') ?></span>
                </label>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_current_weather_symbol_meta" data-size="sm" data-text="false" data-color="green">
                  <input <?php echo checked($owmw_opt["current_weather_symbol"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_current_weather_symbol_meta" name="owmweather_current_weather_symbol"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php esc_html_e('Current Weather Symbol', 'owm-weather') ?></span>
                </label>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_current_temperature_meta" data-size="sm" data-text="false" data-color="green">
                  <input <?php echo checked($owmw_opt["current_temperature"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_current_temperature_meta" name="owmweather_current_temperature"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php esc_html_e('Current Temperature', 'owm-weather') ?></span>
                </label>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_current_feels_like_meta" data-size="sm" data-text="false" data-color="green">
                  <input <?php echo checked($owmw_opt["current_feels_like"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_current_feels_like_meta" name="owmweather_current_feels_like"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php esc_html_e('Current Feels-Like Temperature', 'owm-weather') ?></span>
                </label>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_current_weather_description_meta" data-size="sm" data-text="false" data-color="green">
                  <input <?php echo checked($owmw_opt["current_weather_description"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_current_weather_description_meta" name="owmweather_current_weather_description"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php esc_html_e('Current Weather Short Condition', 'owm-weather') ?></span>
                </label>
            </p>
            <p class="temperatures subsection-title">
                <?php esc_html_e('Temperatures', 'owm-weather') ?>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_display_temperature_unit_meta" data-size="sm" data-text="false" data-color="green">
                  <input <?php echo checked($owmw_opt["display_temperature_unit"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_display_temperature_unit_meta" name="owmweather_display_temperature_unit"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php esc_html_e('Temperature Unit (C / F)', 'owm-weather') ?></span>
                </label>
            </p>
            <p class="owmw-dates subsection-title">
                <?php esc_html_e('Date', 'owm-weather') ?>
            </p>
            <p>
                <label for="owmweather_today_date_format_none_meta">
                    <input type="radio" name="owmweather_today_date_format" id="owmweather_today_date_format_none_meta" value="none" <?php echo checked($owmw_opt["today_date_format"], 'none', false) ?>/>
                        <?php esc_html_e('No date (default)', 'owm-weather') ?>
                </label>
            </p>
            <p>
                <label for="owmweather_today_date_format_week_meta">
                    <input type="radio" name="owmweather_today_date_format" id="owmweather_today_date_format_week_meta" value="day" <?php echo checked($owmw_opt["today_date_format"], 'day', false) ?>/>
                        <?php esc_html_e('Day of the week (eg: Sunday)', 'owm-weather') ?>
                </label>
            </p>
            <p>
                <label for="owmweather_today_date_format_calendar_meta">
                    <input type="radio" name="owmweather_today_date_format" id="owmweather_today_date_format_calendar_meta" value="date" <?php echo checked($owmw_opt["today_date_format"], 'date', false) ?>/>
                        <?php esc_html_e('Today\'s date (based on your WordPress General Settings)', 'owm-weather') ?>
                </label>
            </p>
            <p>
                <label for="owmweather_today_date_time_format_calendar_meta">
                    <input type="radio" name="owmweather_today_date_format" id="owmweather_today_date_time_format_calendar_meta" value="datetime" <?php echo checked($owmw_opt["today_date_format"], 'datetime', false) ?>/>
                        <?php esc_html_e('Today\'s date and time (based on your WordPress General Settings)', 'owm-weather') ?>
                </label>
            </p>
            <p class="owmw-dates subsection-title">
                <?php echo esc_html_e('Sunrise/Sunset and Moonrise/Moonset', 'owm-weather') . " (OneCall Subscription)" ?>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_sunrise_sunset_meta" data-size="sm" data-text="false" data-color="green">
                  <input <?php echo checked($owmw_opt["sunrise_sunset"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_sunrise_sunset_meta" name="owmweather_sunrise_sunset"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php esc_html_e('Sunrise + Sunset', 'owm-weather') ?></span>
                </label>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_moonrise_moonset_meta" data-size="sm" data-text="false" data-color="green">
                  <input <?php echo checked($owmw_opt["moonrise_moonset"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_moonrise_moonset_meta" name="owmweather_moonrise_moonset"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php echo esc_html_e('Moonrise + Moonset', 'owm-weather') . " (OneCall Subscription)" ?></span>
                </label>
            </p>
            <p class="owmw-misc subsection-title">
                <?php esc_html_e('Additional Weather Data', 'owm-weather') ?>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_wind_meta" data-size="sm" data-text="false" data-color="green">
                  <input <?php echo checked($owmw_opt["wind"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_wind_meta" name="owmweather_wind"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php esc_html_e('Wind', 'owm-weather') ?></span>
                </label>            </p>
            <p>
                <label for="owmweather_wind_unit_meta"><?php esc_html_e('Wind Unit: ', 'owm-weather') ?></label>
                <select name="owmweather_wind_unit">
                    <option <?php echo selected('mi/h', $owmw_opt["wind_unit"], false) ?>value="mi/h"><?php esc_html_e('mi/h', 'owm-weather') ?></option>
                    <option <?php echo selected('m/s', $owmw_opt["wind_unit"], false) ?>value="m/s"><?php esc_html_e('m/s', 'owm-weather') ?></option>
                    <option <?php echo selected('km/h', $owmw_opt["wind_unit"], false) ?>value="km/h"><?php esc_html_e('km/h', 'owm-weather') ?></option>
                    <option <?php echo selected('kt', $owmw_opt["wind_unit"], false) ?>value="kt"><?php esc_html_e('kt', 'owm-weather') ?></option>
                </select>
            </p>
            <p>
                <span><?php esc_html_e('Wind icons show ', 'owm-weather') ?></span>
                <label for="owmweather_wind_icon_to_meta">
                    <input type="radio" name="owmweather_wind_icon_direction" id="owmweather_wind_icon_to_meta" value="to" <?php echo checked($owmw_opt["wind_icon_direction"], 'to', false) ?>/>
                        <?php esc_html_e(' direction of the wind', 'owm-weather') ?>
                </label>
                <label for="owmweather_wind_icon_from_meta">
                    <input type="radio" name="owmweather_wind_icon_direction" id="owmweather_wind_icon_from_meta" value="to" <?php echo checked($owmw_opt["wind_icon_direction"], 'from', false) ?>/>
                        <?php esc_html_e('source of wind flow', 'owm-weather') ?>
                </label>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_humidity_meta" data-size="sm" data-text="false" data-color="green">
                  <input <?php echo checked($owmw_opt["humidity"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_humidity_meta" name="owmweather_humidity"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php esc_html_e('Humidity', 'owm-weather') ?></span>
                </label>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_dew_point_meta" data-size="sm" data-text="false" data-color="green">
                  <input <?php echo checked($owmw_opt["dew_point"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_dew_point_meta" name="owmweather_dew_point"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php echo esc_html_e('Dew Point', 'owm-weather') . " (OneCall Subscription)" ?></span>
                </label>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_pressure_meta" data-size="sm" data-text="false" data-color="green">
                  <input <?php echo checked($owmw_opt["pressure"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_pressure_meta" name="owmweather_pressure"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php esc_html_e('Pressure', 'owm-weather') ?></span>
                </label>
            </p>
            <p>
                <label for="owmweather_pressure_unit_meta"><?php esc_html_e('Pressure Unit: ', 'owm-weather') ?></label>
                <select name="owmweather_pressure_unit">
                    <option <?php echo selected('inHg', $owmw_opt["pressure_unit"], false) ?>value="inHg"><?php esc_html_e('Inches of Mercury', 'owm-weather') ?></option>
                    <option <?php echo selected('mmHg', $owmw_opt["pressure_unit"], false) ?>value="mmHg"><?php esc_html_e('Millimeter of Mercury', 'owm-weather') ?></option>
                    <option <?php echo selected('hPa', $owmw_opt["pressure_unit"], false) ?>value="hPa"><?php esc_html_e('Hectopascal', 'owm-weather') ?></option>
                    <option <?php echo selected('mb', $owmw_opt["pressure_unit"], false) ?>value="mb"><?php esc_html_e('Millibar', 'owm-weather') ?></option>
                </select>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_cloudiness_meta" data-size="sm" data-text="false" data-color="green">
                  <input <?php echo checked($owmw_opt["cloudiness"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_cloudiness_meta" name="owmweather_cloudiness"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php esc_html_e('Cloudiness', 'owm-weather') ?></span>
                </label>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_precipitation_meta" data-size="sm" data-text="false" data-color="green">
                  <input <?php echo checked($owmw_opt["precipitation"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_precipitation_meta" name="owmweather_precipitation"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php esc_html_e('Precipitation', 'owm-weather') ?></span>
                </label>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_visibility_meta" data-size="sm" data-text="false" data-color="green">
                  <input <?php echo checked($owmw_opt["visibility"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_visibility_meta" name="owmweather_visibility"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php esc_html_e('Visibility', 'owm-weather') ?></span>
                </label>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_uv_index_meta" data-size="sm" data-text="false" data-color="green">
                  <input <?php echo checked($owmw_opt["uv_index"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_uv_index_meta" name="owmweather_uv_index"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php echo esc_html_e('UV Index', 'owm-weather') . " (OneCall Subscription)" ?></span>
                </label>
            </p>
            <p>
                <span>
                    <label class="toggle-switchy" for="owmweather_alerts_meta" data-size="sm" data-text="false" data-color="green">
                      <input <?php echo checked($owmw_opt["alerts"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_alerts_meta" name="owmweather_alerts"/>
                      <span class="toggle">
                        <span class="switch"></span>
                      </span>
                      <span class="label"><?php echo esc_html_e('Alerts', 'owm-weather') . " (OneCall Subscription)" ?></span>
                    </label>
                </span>
                <span>&nbsp;</span>
                <span>
                <label for="owmweather_alerts_popup_modal_meta">
                    <input type="radio" name="owmweather_alerts_popup" id="owmweather_alerts_popup_modal_meta" value="modal" <?php echo checked($owmw_opt["alerts_popup"], 'modal', false) ?>/>
                        <?php esc_html_e('Modal', 'owm-weather') ?>
                </label>
                </span>
                <span>&nbsp;</span>
                <span>
                <label for="owmweather_display_alert_inline_meta">
                    <input type="radio" name="owmweather_alerts_popup" id="owmweather_display_alert_inline_meta" value="inline" <?php echo checked($owmw_opt["alerts_popup"], 'inline', false) ?>/>
                        <?php esc_html_e('Inline', 'owm-weather') ?>
                </label>
                </span>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_text_labels_meta" data-size="sm" data-text="false" data-color="green">
                  <input <?php echo checked($owmw_opt["text_labels"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_text_labels_meta" name="owmweather_text_labels"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php esc_html_e('Show only Icon Labels', 'owm-weather') ?></span>
                </label>
            </p>
            <p class="hour subsection-title">
                <?php echo esc_html_e('Hourly Forecast', 'owm-weather') . " (OneCall Subscription)" ?>
            </p>
            <p>
                <label for="owmweather_hours_forecast_no_meta"><?php esc_html_e('Number of Hours', 'owm-weather') ?></label>
                <select name="owmweather_hours_forecast_no"><?php echo owmw_generate_hour_options($owmw_opt["hours_forecast_no"]) ?></select>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_hours_time_icons_meta" data-size="sm" data-text="false" data-color="green">
                  <input <?php echo checked($owmw_opt["hours_time_icons"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_hours_time_icons_meta" name="owmweather_hours_time_icons"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php esc_html_e('Display Time Icons', 'owm-weather') ?></span>
                </label>
            </p>
            <p class="forecast subsection-title">
                <?php echo esc_html_e('Daily Forecast', 'owm-weather') . " (OneCall Subscription)" ?>
            </p>
            <p>
                <label for="owmweather_forecast_no_meta"><?php esc_html_e('Number of Days', 'owm-weather') ?></label>
                <select name="owmweather_forecast_no">
                    <option <?php echo selected('0', $owmw_opt["days_forecast_no"], false) ?>value="0"><?php esc_html_e('None', 'owm-weather') ?></option>
                    <option <?php echo selected('1', $owmw_opt["days_forecast_no"], false) ?>value="1"><?php esc_html_e('Today', 'owm-weather') ?></option>
                    <option <?php echo selected('2', $owmw_opt["days_forecast_no"], false) ?>value="2"><?php esc_html_e('Today + 1 day', 'owm-weather') ?></option>
                    <option <?php echo selected('3', $owmw_opt["days_forecast_no"], false) ?>value="3"><?php esc_html_e('Today + 2 days', 'owm-weather') ?></option>
                    <option <?php echo selected('4', $owmw_opt["days_forecast_no"], false) ?>value="4"><?php esc_html_e('Today + 3 days', 'owm-weather') ?></option>
                    <option <?php echo selected('5', $owmw_opt["days_forecast_no"], false) ?>value="5"><?php esc_html_e('Today + 4 days', 'owm-weather') ?></option>
                    <option <?php echo selected('6', $owmw_opt["days_forecast_no"], false) ?>value="6"><?php esc_html_e('Today + 5 days', 'owm-weather') ?></option>
                    <option <?php echo selected('7', $owmw_opt["days_forecast_no"], false) ?>value="7"><?php esc_html_e('Today + 6 days', 'owm-weather') ?></option>
                    <option <?php echo selected('8', $owmw_opt["days_forecast_no"], false) ?>value="8"><?php esc_html_e('Today + 7 days', 'owm-weather') ?></option>
                </select>
            </p>
            <p>
                <label for="owmweather_display_length_days_names_short_meta">
                    <input type="radio" name="owmweather_display_length_days_names" id="owmweather_display_length_days_names_short_meta" value="short" <?php echo checked($owmw_opt["display_length_days_names"], 'short', false) ?>/>
                        <?php esc_html_e('Short day names', 'owm-weather') ?>
                </label>
            </p>
            <p>
                <label for="owmweather_display_length_days_names_normal_meta">
                    <input type="radio" name="owmweather_display_length_days_names" id="owmweather_display_length_days_names_normal_meta" value="normal" <?php echo checked($owmw_opt["display_length_days_names"], 'normal', false) ?>/>
                        <?php esc_html_e('Normal day names', 'owm-weather') ?>
                </label>
            </p>
            <p class="footer subsection-title">
                <?php esc_html_e('Footer', 'owm-weather') ?>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_owm_link_meta" data-size="sm" data-text="false" data-color="green">
                  <input <?php echo checked($owmw_opt["owm_link"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_owm_link_meta" name="owmweather_owm_link"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php esc_html_e('Link to OpenWeatherMap', 'owm-weather') ?></span>
                </label>
            </p>

            <p>
                <label class="toggle-switchy" for="owmweather_last_update_meta" data-size="sm" data-text="false" data-color="green">
                  <input <?php echo checked($owmw_opt["last_update"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_last_update_meta" name="owmweather_last_update"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php esc_html_e('Data Update Time', 'owm-weather') ?></span>
                </label>
            </p>
        </div>
        <div id="tabs-3">
            <p style="border: 2px solid;padding: 5px;">
                <?php esc_html_e('Select the layout styling for your weather shortcode.', 'owm-weather') ?>
            </p>
            <p>
                <label for="template_meta"><?php esc_html_e('Template', 'owm-weather') ?></label>
                <select name="owmweather_template">
                    <option <?php echo selected('Default', $owmw_opt["template"], false) ?>value="Default"><?php esc_html_e('Default', 'owm-weather') ?></option>
                    <option <?php echo selected('card1', $owmw_opt["template"], false) ?>value="card1"><?php esc_html_e('Card 1', 'owm-weather') ?></option>
                    <option <?php echo selected('card2', $owmw_opt["template"], false) ?>value="card2"><?php esc_html_e('Card 2', 'owm-weather') ?></option>
                    <option <?php echo selected('tabbed1', $owmw_opt["template"], false) ?>value="tabbed1"><?php esc_html_e('Tabbed 1', 'owm-weather') ?></option>
                    <option <?php echo selected('tabbed2', $owmw_opt["template"], false) ?>value="tabbed2"><?php esc_html_e('Tabbed 2', 'owm-weather') ?></option>
                    <option <?php echo selected('chart1', $owmw_opt["template"], false) ?>value="chart1"><?php esc_html_e('Chart 1', 'owm-weather') ?></option>
                    <option <?php echo selected('chart2', $owmw_opt["template"], false) ?>value="chart2"><?php esc_html_e('Chart 2', 'owm-weather') ?></option>
                    <option <?php echo selected('table1', $owmw_opt["template"], false) ?>value="table1"><?php esc_html_e('Table 1', 'owm-weather') ?></option>
                    <option <?php echo selected('table2', $owmw_opt["template"], false) ?>value="table2"><?php esc_html_e('Table 2', 'owm-weather') ?></option>
                    <option <?php echo selected('table3', $owmw_opt["template"], false) ?>value="table3"><?php esc_html_e('Table 3', 'owm-weather') ?></option>
                    <option <?php echo selected('slider1', $owmw_opt["template"], false) ?>value="slider1"><?php esc_html_e('Slider 1', 'owm-weather') ?></option>
                    <option <?php echo selected('slider2', $owmw_opt["template"], false) ?>value="slider2"><?php esc_html_e('Slider 2', 'owm-weather') ?></option>
                    <option <?php echo selected('custom1', $owmw_opt["template"], false) ?>value="custom1"><?php esc_html_e('Custom 1', 'owm-weather') ?></option>
                    <option <?php echo selected('custom2', $owmw_opt["template"], false) ?>value="custom2"><?php esc_html_e('Custom 2', 'owm-weather') ?></option>
                    <option <?php echo selected('debug', $owmw_opt["template"], false) ?>value="debug"><?php esc_html_e('Debug', 'owm-weather') ?></option>
                </select>
            </p>
            <p>
                <label for="font_meta"><?php esc_html_e('Font', 'owm-weather') ?></label>
                <select name="owmweather_font">
                    <option <?php echo selected('Default', $owmw_opt["font"], false) ?>value="Default"><?php esc_html_e('Default', 'owm-weather') ?></option>
                    <option <?php echo selected('Arvo', $owmw_opt["font"], false) ?>value="Arvo"><?php esc_html_e('Arvo', 'owm-weather') ?></option>
                    <option <?php echo selected('Asap', $owmw_opt["font"], false) ?>value="Asap"><?php esc_html_e('Asap', 'owm-weather') ?></option>
                    <option <?php echo selected('Bitter', $owmw_opt["font"], false) ?>value="Bitter"><?php esc_html_e('Bitter', 'owm-weather') ?></option>
                    <option <?php echo selected('Droid Serif', $owmw_opt["font"], false) ?>value="Droid Serif"><?php esc_html_e('Droid Serif', 'owm-weather') ?></option>
                    <option <?php echo selected('Exo 2', $owmw_opt["font"], false) ?>value="Exo 2"><?php esc_html_e('Exo 2', 'owm-weather') ?></option>
                    <option <?php echo selected('Francois One', $owmw_opt["font"], false) ?>value="Francois One"><?php esc_html_e('Francois One', 'owm-weather') ?></option>
                    <option <?php echo selected('Inconsolata', $owmw_opt["font"], false) ?>value="Inconsolata"><?php esc_html_e('Inconsolata', 'owm-weather') ?></option>
                    <option <?php echo selected('Josefin Sans', $owmw_opt["font"], false) ?>value="Josefin Sans"><?php esc_html_e('Josefin Sans', 'owm-weather') ?></option>
                    <option <?php echo selected('Lato', $owmw_opt["font"], false) ?>value="Lato"><?php esc_html_e('Lato', 'owm-weather') ?></option>
                    <option <?php echo selected('Merriweather Sans', $owmw_opt["font"], false) ?>value="Merriweather Sans"><?php esc_html_e('Merriweather Sans', 'owm-weather') ?></option>
                    <option <?php echo selected('Nunito', $owmw_opt["font"], false) ?>value="Nunito"><?php esc_html_e('Nunito', 'owm-weather') ?></option>
                    <option <?php echo selected('Open Sans', $owmw_opt["font"], false) ?>value="Open Sans"><?php esc_html_e('Open Sans', 'owm-weather') ?></option>
                    <option <?php echo selected('Oswald', $owmw_opt["font"], false) ?>value="Oswald"><?php esc_html_e('Oswald', 'owm-weather') ?></option>
                    <option <?php echo selected('Pacifico', $owmw_opt["font"], false) ?>value="Pacifico"><?php esc_html_e('Pacifico', 'owm-weather') ?></option>
                    <option <?php echo selected('Roboto', $owmw_opt["font"], false) ?>value="Roboto"><?php esc_html_e('Roboto', 'owm-weather') ?></option>
                    <option <?php echo selected('Signika', $owmw_opt["font"], false) ?>value="Signika"><?php esc_html_e('Signika', 'owm-weather') ?></option>
                    <option <?php echo selected('Source Sans Pro', $owmw_opt["font"], false) ?>value="Source Sans Pro"><?php esc_html_e('Source Sans Pro', 'owm-weather') ?></option>
                    <option <?php echo selected('Tangerine', $owmw_opt["font"], false) ?>value="Tangerine"><?php esc_html_e('Tangerine', 'owm-weather') ?></option>
                    <option <?php echo selected('Ubuntu', $owmw_opt["font"], false) ?>value="Ubuntu"><?php esc_html_e('Ubuntu', 'owm-weather') ?></option>
                </select>
            </p>
            <p>
                <label for="iconpack_meta"><?php esc_html_e('Icon Pack', 'owm-weather') ?></label>
                <p><i><?php esc_html_e('The main icon is always an animated Climacons SVG. If you want to display the icon from the selected icon pack instead, you need to select "Disable Animations for Main Icon" below.', 'owm-weather') ?></i></p>
                <select name="owmweather_iconpack">
                    <option <?php echo selected('Climacons', $owmw_opt["iconpack"], false) ?>value="Climacons"><?php esc_html_e('Climacons', 'owm-weather') ?></option>
                    <option <?php echo selected('OpenWeatherMap', $owmw_opt["iconpack"], false) ?>value="OpenWeatherMap"><?php esc_html_e('Open Weather Map', 'owm-weather') ?></option>
                    <option <?php echo selected('WeatherIcons', $owmw_opt["iconpack"], false) ?>value="WeatherIcons"><?php esc_html_e('Weather Icons', 'owm-weather') ?></option>
                    <option <?php echo selected('Forecast', $owmw_opt["iconpack"], false) ?>value="Forecast"><?php esc_html_e('Forecast', 'owm-weather') ?></option>
                    <option <?php echo selected('Dripicons', $owmw_opt["iconpack"], false) ?>value="Dripicons"><?php esc_html_e('Dripicons', 'owm-weather') ?></option>
                    <option <?php echo selected('Pixeden', $owmw_opt["iconpack"], false) ?>value="Pixeden"><?php esc_html_e('Pixeden', 'owm-weather') ?></option>
                    <option <?php echo selected('ColorAnimated', $owmw_opt["iconpack"], false) ?>value="ColorAnimated"><?php esc_html_e('Color Animated', 'owm-weather') ?></option>
                </select>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_disable_anims_meta" data-size="sm" data-text="false" data-color="red">
                  <input <?php echo checked($owmw_opt["disable_anims"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_disable_anims_meta" name="owmweather_disable_anims"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php esc_html_e('Disable Animations for Main Icon', 'owm-weather') ?></span>
                </label>
            </p>
            <p class="misc subsection-title">
                <?php esc_html_e('Text', 'owm-weather') ?>
            </p>
            <p>
                <label for="owmweather_text_color"><?php esc_html_e('Text Color', 'owm-weather') ?></label>
                <input name="owmweather_text_color" type="text" value="<?php echo esc_attr($owmw_opt["text_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p class="misc subsection-title">
                <?php esc_html_e('Background', 'owm-weather') ?>
            </p>
            <p>
                <label for="owmweather_background_color"><?php esc_html_e('Background Color', 'owm-weather') ?></label>
                <input name="owmweather_background_color" type="text" value="<?php echo esc_attr($owmw_opt["background_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label><?php esc_html_e('Background Image', 'owm-weather') ?></label>
                <div class="background_image_preview_wrapper">
                    <img id="background_image_preview" src="<?php echo wp_get_attachment_url(($owmw_opt["background_image"] ?? '' )) ?>" height="100px"<?php echo (!empty($owmw_opt["background_image"]) ? '' : ' style="display: none;"') ?>>
                </div>
                <input id="select_background_image_button" type="button" class="button select_background_image_button" value="<?php esc_html_e('Select image', 'owm-weather') ?>" data-name="background" />
                <input type="hidden" name="owmweather_background_image" id="background_image_attachment_id" value="<?php echo esc_attr($owmw_opt["background_image"] ?? '') ?>">
                <input id="clear_background_image_button" type="button" class="button clear_background_image" value="Clear" data-name="background" />
            </p>
            <p>
                <label for="owmweather_background_yt_video_meta"><?php esc_html_e('YouTube Background Video', 'owm-weather') ?></label>
                <input id="owmweather_background_yt_video_meta" type="text" name="owmweather_background_yt_video" value="<?php echo esc_attr($owmw_opt["background_yt_video"]) ?>" />
            </p>
            <p>
                <img id="owmweather_background_yt_video_tn" src="https://img.youtube.com/vi/<?php echo esc_attr($owmw_opt["background_yt_video"]) ?>/default.jpg"<?php echo (empty($owmw_opt["background_yt_video"]) ? ' style="display: none;"' : '') ?>>
            </p>
            <?php echo printYTvideoTN("background"); ?>
            <p>
                <label for="owmweather_background_opacity_meta"><?php esc_html_e('Background Opacity', 'owm-weather') ?></label>
                <select name="owmweather_background_opacity">
                    <option <?php echo selected('0', $owmw_opt["background_opacity"], false) ?>value="0">0%</option>
                    <option <?php echo selected('0.1', $owmw_opt["background_opacity"], false) ?>value="0.1">10%</option>
                    <option <?php echo selected('0.2', $owmw_opt["background_opacity"], false) ?>value="0.2">20%</option>
                    <option <?php echo selected('0.3', $owmw_opt["background_opacity"], false) ?>value="0.3">30%</option>
                    <option <?php echo selected('0.4', $owmw_opt["background_opacity"], false) ?>value="0.4">40%</option>
                    <option <?php echo selected('0.5', $owmw_opt["background_opacity"], false) ?>value="0.5">50%</option>
                    <option <?php echo selected('0.6', $owmw_opt["background_opacity"], false) ?>value="0.6">60%</option>
                    <option <?php echo selected('0.7', $owmw_opt["background_opacity"], false) ?>value="0.7">70%</option>
                    <option <?php echo selected('0.8', $owmw_opt["background_opacity"], false) ?>value="0.8">80%</option>
                    <option <?php echo selected('0.9', $owmw_opt["background_opacity"], false) ?>value="0.9">90%</option>
                    <option <?php echo selected('1', $owmw_opt["background_opacity"], false) ?>value="1">100%</option>
                </select>
            </p>
            <p class="misc subsection-title">
                <?php esc_html_e('Borders', 'owm-weather') ?>
            </p>
            <p>
                <label for="owmweather_border_color"><?php esc_html_e('Border Color', 'owm-weather') ?></label>
                <input name="owmweather_border_color" type="text" value="<?php echo esc_attr($owmw_opt["border_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label for="owmweather_border_width"><?php esc_html_e('Border Width (px)', 'owm-weather') ?></label>
                <input name="owmweather_border_width" type="number" min="0" value="<?php echo esc_attr($owmw_opt["border_width"]) ?>" />
            </p>
            <p>
                <label for="owmweather_border_style"><?php esc_html_e('Border Style', 'owm-weather') ?></label>
                <select name="owmweather_border_style">
                    <option <?php echo selected('solid', $owmw_opt["border_style"], false) ?>value="solid"><?php esc_html_e('Solid', 'owm-weather') ?></option>
                    <option <?php echo selected('dotted', $owmw_opt["border_style"], false) ?>value="dotted"><?php esc_html_e('Dotted', 'owm-weather') ?></option>
                    <option <?php echo selected('dashed', $owmw_opt["border_style"], false) ?>value="dashed"><?php esc_html_e('Dashed', 'owm-weather') ?></option>
                    <option <?php echo selected('double', $owmw_opt["border_style"], false) ?>value="double"><?php esc_html_e('Double', 'owm-weather') ?></option>
                    <option <?php echo selected('groove', $owmw_opt["border_style"], false) ?>value="groove"><?php esc_html_e('Groove', 'owm-weather') ?></option>
                    <option <?php echo selected('inset', $owmw_opt["border_style"], false) ?>value="inset"><?php esc_html_e('Inset', 'owm-weather') ?></option>
                    <option <?php echo selected('outset', $owmw_opt["border_style"], false) ?>value="outset"><?php esc_html_e('Outset', 'owm-weather') ?></option>
                    <option <?php echo selected('ridge', $owmw_opt["border_style"], false) ?>value="ridge"><?php esc_html_e('Ridge', 'owm-weather') ?></option>
                </select>
            </p>
            <p>
                <label for="owmweather_border_radius"><?php esc_html_e('Border Radius (px)', 'owm-weather') ?></label>
                <input name="owmweather_border_radius" type="number" min="0" value="<?php echo esc_attr($owmw_opt["border_radius"]) ?>" />
            </p>
            <p class="misc subsection-title">
                <?php esc_html_e('Miscellaneous', 'owm-weather') ?>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_disable_spinner_meta" data-size="sm" data-text="false" data-color="red">
                  <input <?php echo checked($owmw_opt["disable_spinner"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_disable_spinner_meta" name="owmweather_disable_spinner"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php esc_html_e('Disable Loading Spinner', 'owm-weather') ?></span>
                </label>
            </p>
            <p>
                <label for="size_meta"><?php esc_html_e('Weather Size', 'owm-weather') ?></label>
                <select name="owmweather_size">
                    <option <?php echo selected('small', $owmw_opt["size"], false) ?>value="small"><?php esc_html_e('Small', 'owm-weather') ?></option>
                    <option <?php echo selected('medium', $owmw_opt["size"], false) ?>value="medium"><?php esc_html_e('Medium', 'owm-weather') ?></option>
                    <option <?php echo selected('large', $owmw_opt["size"], false) ?>value="large"><?php esc_html_e('Large', 'owm-weather') ?></option>
                </select>
            </p>
            <p>
                <label for="owmweather_custom_css_meta"><?php esc_html_e('Custom CSS', 'owm-weather') ?></label>
                <textarea id="owmweather_custom_css_meta" name="owmweather_custom_css"><?php echo esc_textarea($owmw_opt["custom_css"]) ?></textarea>
                <p>Preceed all CSS rules with .owmw-<?php echo esc_html($id) ?> if you are planning to use more than one weather shortcode on a page.</p>
            </p>
            <p class="subsection-title">
                <?php esc_html_e('Tabbed Templates', 'owm-weather') ?>
            </p>
            <p>
                <label for="owmweather_tabbed_btn_text_color"><?php esc_html_e('Text Color', 'owm-weather') ?></label>
                <input name="owmweather_tabbed_btn_text_color" type="text" value="<?php echo esc_attr($owmw_opt["tabbed_btn_text_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label for="owmweather_tabbed_btn_background_color"><?php esc_html_e('Background Color', 'owm-weather') ?></label>
                <input name="owmweather_tabbed_btn_background_color" type="text" value="<?php echo esc_attr($owmw_opt["tabbed_btn_background_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label for="owmweather_tabbed_btn_active_color"><?php esc_html_e('Active Button Color', 'owm-weather') ?></label>
                <input name="owmweather_tabbed_btn_active_color" type="text" value="<?php echo esc_attr($owmw_opt["tabbed_btn_active_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label for="owmweather_tabbed_btn_hover_color"><?php esc_html_e('Hover Button Color', 'owm-weather') ?></label>
                <input name="owmweather_tabbed_btn_hover_color" type="text" value="<?php echo esc_attr($owmw_opt["tabbed_btn_hover_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p class="subsection-title">
                <?php esc_html_e('Tables', 'owm-weather') ?>
            </p>
            <p>
                <label for="owmweather_table_background_color"><?php esc_html_e('Background Color', 'owm-weather') ?></label>
                <input name="owmweather_table_background_color" type="text" value="<?php echo esc_attr($owmw_opt["table_background_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label for="owmweather_table_text_color"><?php esc_html_e('Text Color', 'owm-weather') ?></label>
                <input name="owmweather_table_text_color" type="text" value="<?php echo esc_attr($owmw_opt["table_text_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label for="owmweather_table_border_color"><?php esc_html_e('Border Color', 'owm-weather') ?></label>
                <input name="owmweather_table_border_color" type="text" value="<?php echo esc_attr($owmw_opt["table_border_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label for="owmweather_table_border_width"><?php esc_html_e('Border Width (px)', 'owm-weather') ?></label>
                <input name="owmweather_table_border_width" type="number" min="0" value="<?php echo esc_attr($owmw_opt["table_border_width"]) ?>" />
            </p>
            <p>
                <label for="owmweather_table_border_style"><?php esc_html_e('Border Style', 'owm-weather') ?></label>
                <select name="owmweather_table_border_style">
                    <option <?php echo selected('solid', $owmw_opt["table_border_style"], false) ?>value="solid"><?php esc_html_e('Solid', 'owm-weather') ?></option>
                    <option <?php echo selected('dotted', $owmw_opt["table_border_style"], false) ?>value="dotted"><?php esc_html_e('Dotted', 'owm-weather') ?></option>
                    <option <?php echo selected('dashed', $owmw_opt["table_border_style"], false) ?>value="dashed"><?php esc_html_e('Dashed', 'owm-weather') ?></option>
                    <option <?php echo selected('double', $owmw_opt["table_border_style"], false) ?>value="double"><?php esc_html_e('Double', 'owm-weather') ?></option>
                    <option <?php echo selected('groove', $owmw_opt["table_border_style"], false) ?>value="groove"><?php esc_html_e('Groove', 'owm-weather') ?></option>
                    <option <?php echo selected('inset', $owmw_opt["table_border_style"], false) ?>value="inset"><?php esc_html_e('Inset', 'owm-weather') ?></option>
                    <option <?php echo selected('outset', $owmw_opt["table_border_style"], false) ?>value="outset"><?php esc_html_e('Outset', 'owm-weather') ?></option>
                    <option <?php echo selected('ridge', $owmw_opt["table_border_style"], false) ?>value="ridge"><?php esc_html_e('Ridge', 'owm-weather') ?></option>
                </select>
            </p>
            <p>
                <label for="owmweather_table_border_radius"><?php esc_html_e('Border Radius (px)', 'owm-weather') ?></label>
                <input name="owmweather_table_border_radius" type="number" min="0" value="<?php echo esc_attr($owmw_opt["table_border_radius"]) ?>" />
            </p>
            <p class="subsection-title">
                <?php esc_html_e('Charts', 'owm-weather') ?>
            </p>
            <p>
                <label for="owmweather_chart_height_meta"><?php esc_html_e('Height (in px)', 'owm-weather') ?></label>
                <input id="owmweather_charet_height_meta" type="text" name="owmweather_chart_height" value="<?php echo esc_attr($owmw_opt["chart_height"]) ?>" />
            </p>
            <p>
                <label for="owmweather_chart_text_color"><?php esc_html_e('Text Color', 'owm-weather') ?></label>
                <input name="owmweather_chart_text_color" type="text" value="<?php echo esc_attr($owmw_opt["chart_text_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label for="owmweather_chart_night_color"><?php esc_html_e('Night Highlight Color', 'owm-weather') ?></label>
                <input name="owmweather_chart_night_color" type="text" value="<?php echo esc_attr($owmw_opt["chart_night_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label for="owmweather_chart_background_color"><?php esc_html_e('Background Color', 'owm-weather') ?></label>
                <input name="owmweather_chart_background_color" type="text" value="<?php echo esc_attr($owmw_opt["chart_background_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label for="owmweather_chart_border_color"><?php esc_html_e('Border Color', 'owm-weather') ?></label>
                <input name="owmweather_chart_border_color" type="text" value="<?php echo esc_attr($owmw_opt["chart_border_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label for="owmweather_chart_border_width"><?php esc_html_e('Border Width (px)', 'owm-weather') ?></label>
                <input name="owmweather_chart_border_width" type="number" min="0" value="<?php echo esc_attr($owmw_opt["chart_border_width"]) ?>" />
            </p>
            <p>
                <label for="owmweather_chart_border_style"><?php esc_html_e('Border Style', 'owm-weather') ?></label>
                <select name="owmweather_chart_border_style">
                    <option <?php echo selected('solid', $owmw_opt["chart_border_style"], false) ?>value="solid"><?php esc_html_e('Solid', 'owm-weather') ?></option>
                    <option <?php echo selected('dotted', $owmw_opt["chart_border_style"], false) ?>value="dotted"><?php esc_html_e('Dotted', 'owm-weather') ?></option>
                    <option <?php echo selected('dashed', $owmw_opt["chart_border_style"], false) ?>value="dashed"><?php esc_html_e('Dashed', 'owm-weather') ?></option>
                    <option <?php echo selected('double', $owmw_opt["chart_border_style"], false) ?>value="double"><?php esc_html_e('Double', 'owm-weather') ?></option>
                    <option <?php echo selected('groove', $owmw_opt["chart_border_style"], false) ?>value="groove"><?php esc_html_e('Groove', 'owm-weather') ?></option>
                    <option <?php echo selected('inset', $owmw_opt["chart_border_style"], false) ?>value="inset"><?php esc_html_e('Inset', 'owm-weather') ?></option>
                    <option <?php echo selected('outset', $owmw_opt["chart_border_style"], false) ?>value="outset"><?php esc_html_e('Outset', 'owm-weather') ?></option>
                    <option <?php echo selected('ridge', $owmw_opt["chart_border_style"], false) ?>value="ridge"><?php esc_html_e('Ridge', 'owm-weather') ?></option>
                </select>
            </p>
            <p>
                <label for="owmweather_chart_border_radius"><?php esc_html_e('Border Radius (px)', 'owm-weather') ?></label>
                <input name="owmweather_chart_border_radius" type="number" min="0" value="<?php echo esc_attr($owmw_opt["chart_border_radius"]) ?>" />
            </p>
            <p>
                <label for="owmweather_chart_temperature_color"><?php esc_html_e('Temperature Color', 'owm-weather') ?></label>
                <input name="owmweather_chart_temperature_color" type="text" value="<?php echo esc_attr($owmw_opt["chart_temperature_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label for="owmweather_chart_feels_like_color"><?php esc_html_e('Feels-Like Color', 'owm-weather') ?></label>
                <input name="owmweather_chart_feels_like_color" type="text" value="<?php echo esc_attr($owmw_opt["chart_feels_like_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label for="owmweather_chart_dew_point_color"><?php esc_html_e('Dew Point Color', 'owm-weather') ?></label>
                <input name="owmweather_chart_dew_point_color" type="text" value="<?php echo esc_attr($owmw_opt["chart_dew_point_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label for="owmweather_chart_cloudiness_color"><?php esc_html_e('Cloudiness Color', 'owm-weather') ?></label>
                <input name="owmweather_chart_cloudiness_color" type="text" value="<?php echo esc_attr($owmw_opt["chart_cloudiness_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label for="owmweather_chart_rain_chance_color"><?php esc_html_e('Rain Chance Color', 'owm-weather') ?></label>
                <input name="owmweather_chart_rain_chance_color" type="text" value="<?php echo esc_attr($owmw_opt["chart_rain_chance_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label for="owmweather_chart_humidity_color"><?php esc_html_e('Humidity Color', 'owm-weather') ?></label>
                <input name="owmweather_chart_humidity_color" type="text" value="<?php echo esc_attr($owmw_opt["chart_humidity_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label for="owmweather_chart_pressure_color"><?php esc_html_e('Pressure Color', 'owm-weather') ?></label>
                <input name="owmweather_chart_pressure_color" type="text" value="<?php echo esc_attr($owmw_opt["chart_pressure_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label for="owmweather_chart_rain_amt_color"><?php esc_html_e('Rain Amount Color', 'owm-weather') ?></label>
                <input name="owmweather_chart_rain_amt_color" type="text" value="<?php echo esc_attr($owmw_opt["chart_rain_amt_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label for="owmweather_chart_snow_amt_color"><?php esc_html_e('Snow Amount Color', 'owm-weather') ?></label>
                <input name="owmweather_chart_snow_amt_color" type="text" value="<?php echo esc_attr($owmw_opt["chart_snow_amt_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label for="owmweather_chart_wind_speed_color"><?php esc_html_e('Wind Speed Color', 'owm-weather') ?></label>
                <input name="owmweather_chart_wind_speed_color" type="text" value="<?php echo esc_attr($owmw_opt["chart_wind_speed_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label for="owmweather_chart_wind_gust_color"><?php esc_html_e('Wind Gust Color', 'owm-weather') ?></label>
                <input name="owmweather_chart_wind_gust_color" type="text" value="<?php echo esc_attr($owmw_opt["chart_wind_gust_color"]) ?>" class="owmweather_color_picker" />
            </p>
        </div>
        <div id="tabs-4">
            <p style="border: 2px solid;padding: 5px;">
                <?php esc_html_e('Select the text colors and backgrounds for each weather type.', 'owm-weather') ?>
            </p>
            <p class="misc subsection-title">
                <?php esc_html_e('Sunny', 'owm-weather') ?>
            </p>
            <p>
                <label for="owmweather_sunny_text_color"><?php esc_html_e('Sunny Text Color', 'owm-weather') ?></label>
                <input name="owmweather_sunny_text_color" type="text" value="<?php echo esc_attr($owmw_opt["sunny_text_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label><?php esc_html_e('Sunny Background Color', 'owm-weather') ?></label>
                <input name="owmweather_sunny_background_color" type="text" value="<?php echo esc_attr($owmw_opt["sunny_background_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label><?php esc_html_e('Sunny Background Image', 'owm-weather') ?></label>
                  <div class="background_image_preview_wrapper">
                    <img id="sunny_background_image_preview" src="<?php echo wp_get_attachment_url(($owmw_opt["sunny_background_image"] ?? '' )) ?>" height="100px"<?php echo (!empty($owmw_opt["sunny_background_image"]) ? '' : ' style="display: none;"') ?>>
                  </div>
                  <input id="select_sunny_background_image_button" type="button" class="button select_background_image_button" value="<?php esc_html_e('Select image', 'owm-weather') ?>" data-name="sunny_background" />
                  <input type="hidden" name="owmweather_sunny_background_image" id="sunny_background_image_attachment_id" value="<?php echo esc_attr($owmw_opt["sunny_background_image"] ?? '') ?>">
                  <input id="clear_sunny_background_image_button" type="button" class="button clear_background_image" value="Clear" data-name="sunny_background" />
            </p>
            <p>
                <label for="owmweather_sunny_background_yt_video_meta"><?php esc_html_e('YouTube Sunny Background Video', 'owm-weather') ?></label>
                <input id="owmweather_sunny_background_yt_video_meta" type="text" name="owmweather_sunny_background_yt_video" value="<?php echo esc_attr($owmw_opt["sunny_background_yt_video"]) ?>" />
            </p>
            <p>
                <img id="owmweather_sunny_background_yt_video_tn" src="https://img.youtube.com/vi/<?php echo esc_attr($owmw_opt["sunny_background_yt_video"]) ?>/default.jpg"<?php echo (empty($owmw_opt["sunny_background_yt_video"]) ? ' style="display: none;"' : '') ?>>
            </p>
            <?php echo printYTvideoTN("sunny_background"); ?>
            <p class="misc subsection-title">
                <?php esc_html_e('Cloudy', 'owm-weather') ?>
            </p>
            <p>
                <label for="owmweather_cloudy_text_color"><?php esc_html_e('Cloudy Text Color', 'owm-weather') ?></label>
                <input name="owmweather_cloudy_text_color" type="text" value="<?php echo esc_attr($owmw_opt["cloudy_text_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label><?php esc_html_e('Cloudy Background Color', 'owm-weather') ?></label>
                <input name="owmweather_cloudy_background_color" type="text" value="<?php echo esc_attr($owmw_opt["cloudy_background_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label><?php esc_html_e('Cloudy Background Image', 'owm-weather') ?></label>
                  <div class="background_image_preview_wrapper">
                    <img id="cloudy_background_image_preview" src="<?php echo wp_get_attachment_url(($owmw_opt["cloudy_background_image"] ?? '' )) ?>" height="100px"<?php echo (!empty($owmw_opt["cloudy_background_image"]) ? '' : ' style="display: none;"') ?>>
                  </div>
                  <input id="select_cloudy_background_image_button" type="button" class="button select_background_image_button" value="<?php esc_html_e('Select image', 'owm-weather') ?>" data-name="cloudy_background" />
                  <input type="hidden" name="owmweather_cloudy_background_image" id="cloudy_background_image_attachment_id" value="<?php echo esc_attr($owmw_opt["cloudy_background_image"] ?? '') ?>">
                  <input id="clear_cloudy_background_image_button" type="button" class="button clear_background_image" value="Clear" data-name="cloudy_background" />
            </p>
            <p>
                <label for="owmweather_cloudy_background_yt_video_meta"><?php esc_html_e('YouTube Cloudy Background Video', 'owm-weather') ?></label>
                <input id="owmweather_cloudy_background_yt_video_meta" type="text" name="owmweather_cloudy_background_yt_video" value="<?php echo esc_attr($owmw_opt["cloudy_background_yt_video"]) ?>" />
            </p>
            <p>
                <img id="owmweather_cloudy_background_yt_video_tn" src="https://img.youtube.com/vi/<?php echo esc_attr($owmw_opt["cloudy_background_yt_video"]) ?>/default.jpg"<?php echo (empty($owmw_opt["cloudy_background_yt_video"]) ? ' style="display: none;"' : '') ?>>
            </p>
            <?php echo printYTvideoTN("cloudy_background"); ?>
            <p class="misc subsection-title">
                <?php esc_html_e('Drizzly', 'owm-weather') ?>
            </p>
            <p>
                <label for="owmweather_drizzly_text_color"><?php esc_html_e('Drizzly Text Color', 'owm-weather') ?></label>
                <input name="owmweather_drizzly_text_color" type="text" value="<?php echo esc_attr($owmw_opt["drizzly_text_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label><?php esc_html_e('Drizzly Background Color', 'owm-weather') ?></label>
                <input name="owmweather_drizzly_background_color" type="text" value="<?php echo esc_attr($owmw_opt["drizzly_background_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label><?php esc_html_e('Drizzly Background Image', 'owm-weather') ?></label>
                  <div class="background_image_preview_wrapper">
                    <img id="drizzly_background_image_preview" src="<?php echo wp_get_attachment_url(($owmw_opt["drizzly_background_image"] ?? '' )) ?>" height="100px"<?php echo (!empty($owmw_opt["drizzly_background_image"]) ? '' : ' style="display: none;"') ?>>
                  </div>
                  <input id="select_drizzly_background_image_button" type="button" class="button select_background_image_button" value="<?php esc_html_e('Select image', 'owm-weather') ?>" data-name="drizzly_background" />
                  <input type="hidden" name="owmweather_drizzly_background_image" id="drizzly_background_image_attachment_id" value="<?php echo esc_attr($owmw_opt["drizzly_background_image"] ?? '') ?>">
                  <input id="clear_drizzly_background_image_button" type="button" class="button clear_background_image" value="Clear" data-name="drizzly_background" />
            </p>
            <p>
                <label for="owmweather_drizzly_background_yt_video_meta"><?php esc_html_e('YouTube Drizzly Background Video', 'owm-weather') ?></label>
                <input id="owmweather_drizzly_background_yt_video_meta" type="text" name="owmweather_drizzly_background_yt_video" value="<?php echo esc_attr($owmw_opt["drizzly_background_yt_video"]) ?>" />
            </p>
            <p>
                <img id="owmweather_drizzly_background_yt_video_tn" src="https://img.youtube.com/vi/<?php echo esc_attr($owmw_opt["drizzly_background_yt_video"]) ?>/default.jpg"<?php echo (empty($owmw_opt["drizzly_background_yt_video"]) ? ' style="display: none;"' : '') ?>>
            </p>
            <?php echo printYTvideoTN("drizzly_background"); ?>
            <p class="misc subsection-title">
                <?php esc_html_e('Rainy', 'owm-weather') ?>
            </p>
            <p>
                <label for="owmweather_rainy_text_color"><?php esc_html_e('Rainy Text Color', 'owm-weather') ?></label>
                <input name="owmweather_rainy_text_color" type="text" value="<?php echo esc_attr($owmw_opt["rainy_text_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label><?php esc_html_e('Rainy Background Color', 'owm-weather') ?></label>
                <input name="owmweather_rainy_background_color" type="text" value="<?php echo esc_attr($owmw_opt["rainy_background_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label><?php esc_html_e('Rainy Background Image', 'owm-weather') ?></label>
                  <div class="background_image_preview_wrapper">
                    <img id="rainy_background_image_preview" src="<?php echo wp_get_attachment_url(($owmw_opt["rainy_background_image"] ?? '' )) ?>" height="100px"<?php echo (!empty($owmw_opt["rainy_background_image"]) ? '' : ' style="display: none;"') ?>>
                  </div>
                  <input id="select_rainy_background_image_button" type="button" class="button select_background_image_button" value="<?php esc_html_e('Select image', 'owm-weather') ?>" data-name="rainy_background" />
                  <input type="hidden" name="owmweather_rainy_background_image" id="rainy_background_image_attachment_id" value="<?php echo esc_attr($owmw_opt["rainy_background_image"] ?? '') ?>">
                  <input id="clear_rainy_background_image_button" type="button" class="button clear_background_image" value="Clear" data-name="rainy_background" />
            </p>
            <p>
                <label for="owmweather_rainy_background_yt_video_meta"><?php esc_html_e('YouTube Rainy Background Video', 'owm-weather') ?></label>
                <input id="owmweather_rainy_background_yt_video_meta" type="text" name="owmweather_rainy_background_yt_video" value="<?php echo esc_attr($owmw_opt["rainy_background_yt_video"]) ?>" />
            </p>
            <p>
                <img id="owmweather_rainy_background_yt_video_tn" src="https://img.youtube.com/vi/<?php echo esc_attr($owmw_opt["rainy_background_yt_video"]) ?>/default.jpg"<?php echo (empty($owmw_opt["rainy_background_yt_video"]) ? ' style="display: none;"' : '') ?>>
            </p>
            <?php echo printYTvideoTN("rainy_background"); ?>
            <p class="misc subsection-title">
                <?php esc_html_e('Snowy', 'owm-weather') ?>
            </p>
            <p>
                <label for="owmweather_snowy_text_color"><?php esc_html_e('Snowy Text Color', 'owm-weather') ?></label>
                <input name="owmweather_snowy_text_color" type="text" value="<?php echo esc_attr($owmw_opt["snowy_text_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label><?php esc_html_e('Snowy Background Color', 'owm-weather') ?></label>
                <input name="owmweather_snowy_background_color" type="text" value="<?php echo esc_attr($owmw_opt["snowy_background_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label><?php esc_html_e('Snowy Background Image', 'owm-weather') ?></label>
                  <div class="background_image_preview_wrapper">
                    <img id="snowy_background_image_preview" src="<?php echo wp_get_attachment_url(($owmw_opt["snowy_background_image"] ?? '' )) ?>" height="100px"<?php echo (!empty($owmw_opt["snowy_background_image"]) ? '' : ' style="display: none;"') ?>>
                  </div>
                  <input id="select_snowy_background_image_button" type="button" class="button select_background_image_button" value="<?php esc_html_e('Select image', 'owm-weather') ?>" data-name="snowy_background" />
                  <input type="hidden" name="owmweather_snowy_background_image" id="snowy_background_image_attachment_id" value="<?php echo esc_attr($owmw_opt["snowy_background_image"] ?? '') ?>">
                  <input id="clear_snowy_background_image_button" type="button" class="button clear_background_image" value="Clear" data-name="snowy_background" />
            </p>
            <p>
                <label for="owmweather_snowy_background_yt_video_meta"><?php esc_html_e('YouTube Snowy Background Video', 'owm-weather') ?></label>
                <input id="owmweather_snowy_background_yt_video_meta" type="text" name="owmweather_snowy_background_yt_video" value="<?php echo esc_attr($owmw_opt["snowy_background_yt_video"]) ?>" />
            </p>
            <p>
                <img id="owmweather_snowy_background_yt_video_tn" src="https://img.youtube.com/vi/<?php echo esc_attr($owmw_opt["snowy_background_yt_video"]) ?>/default.jpg"<?php echo (empty($owmw_opt["snowy_background_yt_video"]) ? ' style="display: none;"' : '') ?>>
            </p>
            <?php echo printYTvideoTN("snowy_background"); ?>
            <p class="misc subsection-title">
                <?php esc_html_e('Stormy', 'owm-weather') ?>
            </p>
            <p>
                <label for="owmweather_stormy_text_color"><?php esc_html_e('Stormy Text Color', 'owm-weather') ?></label>
                <input name="owmweather_stormy_text_color" type="text" value="<?php echo esc_attr($owmw_opt["stormy_text_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label><?php esc_html_e('Stormy Background Color', 'owm-weather') ?></label>
                <input name="owmweather_stormy_background_color" type="text" value="<?php echo esc_attr($owmw_opt["stormy_background_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label><?php esc_html_e('Stormy Background Image', 'owm-weather') ?></label>
                  <div class="background_image_preview_wrapper">
                    <img id="stormy_background_image_preview" src="<?php echo wp_get_attachment_url(($owmw_opt["stormy_background_image"] ?? '' )) ?>" height="100px"<?php echo (!empty($owmw_opt["stormy_background_image"]) ? '' : ' style="display: none;"') ?>>
                  </div>
                  <input id="select_stormy_background_image_button" type="button" class="button select_background_image_button" value="<?php esc_html_e('Select image', 'owm-weather') ?>" data-name="stormy_background" />
                  <input type="hidden" name="owmweather_stormy_background_image" id="stormy_background_image_attachment_id" value="<?php echo esc_attr($owmw_opt["stormy_background_image"] ?? '') ?>">
                  <input id="clear_stormy_background_image_button" type="button" class="button clear_background_image" value="Clear" data-name="stormy_background" />
            </p>
            <p>
                <label for="owmweather_stormy_background_yt_video_meta"><?php esc_html_e('YouTube Stormy Background Video', 'owm-weather') ?></label>
                <input id="owmweather_stormy_background_yt_video_meta" type="text" name="owmweather_stormy_background_yt_video" value="<?php echo esc_attr($owmw_opt["stormy_background_yt_video"]) ?>" />
            </p>
            <p>
                <img id="owmweather_stormy_background_yt_video_tn" src="https://img.youtube.com/vi/<?php echo esc_attr($owmw_opt["stormy_background_yt_video"]) ?>/default.jpg"<?php echo (empty($owmw_opt["stormy_background_yt_video"]) ? ' style="display: none;"' : '') ?>>
            </p>
            <?php echo printYTvideoTN("stormy_background"); ?>

            <p class="misc subsection-title">
                <?php esc_html_e('Foggy', 'owm-weather') ?>
            </p>
            <p>
                <label for="owmweather_foggy_text_color"><?php esc_html_e('Foggy Text Color', 'owm-weather') ?></label>
                <input name="owmweather_foggy_text_color" type="text" value="<?php echo esc_attr($owmw_opt["foggy_text_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label><?php esc_html_e('Foggy Background Color', 'owm-weather') ?></label>
                <input name="owmweather_foggy_background_color" type="text" value="<?php echo esc_attr($owmw_opt["foggy_background_color"]) ?>" class="owmweather_color_picker" />
            </p>
            <p>
                <label><?php esc_html_e('Foggy Background Image', 'owm-weather') ?></label>
                  <div class="background_image_preview_wrapper">
                    <img id="foggy_background_image_preview" src="<?php echo wp_get_attachment_url(($owmw_opt["foggy_background_image"] ?? '' )) ?>" height="100px"<?php echo (!empty($owmw_opt["foggy_background_image"]) ? '' : ' style="display: none;"') ?>>
                  </div>
                  <input id="select_foggy_background_image_button" type="button" class="button select_background_image_button" value="<?php esc_html_e('Select image', 'owm-weather') ?>" data-name="foggy_background" />
                  <input type="hidden" name="owmweather_foggy_background_image" id="foggy_background_image_attachment_id" value="<?php echo esc_attr($owmw_opt["foggy_background_image"] ?? '') ?>">
                  <input id="clear_foggy_background_image_button" type="button" class="button clear_background_image" value="Clear" data-name="foggy_background" />
            </p>
            <p>
                <label for="owmweather_foggy_background_yt_video_meta"><?php esc_html_e('YouTube Foggy Background Video', 'owm-weather') ?></label>
                <input id="owmweather_foggy_background_yt_video_meta" type="text" name="owmweather_foggy_background_yt_video" value="<?php echo esc_attr($owmw_opt["foggy_background_yt_video"]) ?>" />
            </p>
            <p>
                <img id="owmweather_foggy_background_yt_video_tn" src="https://img.youtube.com/vi/<?php echo esc_attr($owmw_opt["foggy_background_yt_video"]) ?>/default.jpg"<?php echo (empty($owmw_opt["foggy_background_yt_video"]) ? ' style="display: none;"' : '') ?>>
            </p>
            <?php echo printYTvideoTN("foggy_background"); ?>
        </div>
        <div id="tabs-5">
            <p style="border: 2px solid;padding: 5px;">
                <?php esc_html_e('Select the information and layout styling for the optional map on your weather shortcode.', 'owm-weather') ?>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_map_meta" data-size="sm" data-text="false" data-color="green">
                  <input <?php echo checked($owmw_opt["map"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_meta" name="owmweather_map"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php esc_html_e('Display Map', 'owm-weather') ?></span>
                </label>
            </p>
            <p>
                <label for="owmweather_map_height_meta"><?php esc_html_e('Map Height (in px)', 'owm-weather') ?></label>
                <input id="owmweather_map_height_meta" type="number" min="300" name="owmweather_map_height" value="<?php echo esc_attr($owmw_opt["map_height"]) ?>" />
            </p>
            <p>
                <label for="owmweather_map_opacity_meta"><?php esc_html_e('Layers Opacity', 'owm-weather') ?></label>
                <select name="owmweather_map_opacity">
                    <option <?php echo selected('0', $owmw_opt["map_opacity"], false) ?>value="0">0%</option>
                    <option <?php echo selected('0.1', $owmw_opt["map_opacity"], false) ?>value="0.1">10%</option>
                    <option <?php echo selected('0.2', $owmw_opt["map_opacity"], false) ?>value="0.2">20%</option>
                    <option <?php echo selected('0.3', $owmw_opt["map_opacity"], false) ?>value="0.3">30%</option>
                    <option <?php echo selected('0.4', $owmw_opt["map_opacity"], false) ?>value="0.4">40%</option>
                    <option <?php echo selected('0.5', $owmw_opt["map_opacity"], false) ?>value="0.5">50%</option>
                    <option <?php echo selected('0.6', $owmw_opt["map_opacity"], false) ?>value="0.6">60%</option>
                    <option <?php echo selected('0.7', $owmw_opt["map_opacity"], false) ?>value="0.7">70%</option>
                    <option <?php echo selected('0.8', $owmw_opt["map_opacity"], false) ?>value="0.8">80%</option>
                    <option <?php echo selected('0.9', $owmw_opt["map_opacity"], false) ?>value="0.9">90%</option>
                    <option <?php echo selected('1', $owmw_opt["map_opacity"], false) ?>value="1">100%</option>
                </select>
            </p>
            <p>
                <label for="owmweather_map_zoom_meta"><?php esc_html_e('Zoom', 'owm-weather') ?></label>
                <select name="owmweather_map_zoom">
                    <option <?php echo selected('1', $owmw_opt["map_zoom"], false) ?>value="1">1</option>
                    <option <?php echo selected('2', $owmw_opt["map_zoom"], false) ?>value="2">2</option>
                    <option <?php echo selected('3', $owmw_opt["map_zoom"], false) ?>value="3">3</option>
                    <option <?php echo selected('4', $owmw_opt["map_zoom"], false) ?>value="4">4</option>
                    <option <?php echo selected('5', $owmw_opt["map_zoom"], false) ?>value="5">5</option>
                    <option <?php echo selected('6', $owmw_opt["map_zoom"], false) ?>value="6">6</option>
                    <option <?php echo selected('7', $owmw_opt["map_zoom"], false) ?>value="7">7</option>
                    <option <?php echo selected('8', $owmw_opt["map_zoom"], false) ?>value="8">8</option>
                    <option <?php echo selected('9', $owmw_opt["map_zoom"], false) ?>value="9">9</option>
                    <option <?php echo selected('10', $owmw_opt["map_zoom"], false) ?>value="10">10</option>
                    <option <?php echo selected('11', $owmw_opt["map_zoom"], false) ?>value="11">11</option>
                    <option <?php echo selected('12', $owmw_opt["map_zoom"], false) ?>value="12">12</option>
                    <option <?php echo selected('13', $owmw_opt["map_zoom"], false) ?>value="13">13</option>
                    <option <?php echo selected('14', $owmw_opt["map_zoom"], false) ?>value="14">14</option>
                    <option <?php echo selected('15', $owmw_opt["map_zoom"], false) ?>value="15">15</option>
                    <option <?php echo selected('16', $owmw_opt["map_zoom"], false) ?>value="16">16</option>
                    <option <?php echo selected('17', $owmw_opt["map_zoom"], false) ?>value="17">17</option>
                    <option <?php echo selected('18', $owmw_opt["map_zoom"], false) ?>value="18">18</option>
                </select>
            </p>
            <p>
                <label class="toggle-switchy" for="owmweather_map_disable_zoom_wheel_meta" data-size="sm" data-text="false" data-color="red">
                  <input <?php echo checked($owmw_opt["map_disable_zoom_wheel"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_disable_zoom_wheel_meta" name="owmweather_map_disable_zoom_wheel"/>
                  <span class="toggle">
                    <span class="switch"></span>
                  </span>
                  <span class="label"><?php esc_html_e('Disable Zoom Wheel on Map', 'owm-weather') ?></span>
                </label>
            </p>
            <p class="subsection-title">
                <?php esc_html_e('Layers', 'owm-weather') ?>
            </p>
            <style>#map-layers th, #map-layers td { padding: 5px 15px; } #map-layers td + td {text-align:center;}</style>
            <table id="map-layers">
            <thead><tr>
                <th style="text-align:left;"><?php esc_html_e('Layer', 'owm-weather') ?></th>
                <th><?php esc_html_e('Display?', 'owm-weather') ?></th>
                <th><?php esc_html_e('Legend On?', 'owm-weather') ?></th>
                <th><?php esc_html_e('Turned On?', 'owm-weather') ?></th>
            </tr></thead>
            <tbody>
            <tr>
                <td><?php esc_html_e('Cities', 'owm-weather') ?></td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_cities"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_cities_meta" name="owmweather_map_cities"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_cities_legend"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_cities_legend_meta" name="owmweather_map_cities_legend"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_cities_on"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_cities_on_meta" name="owmweather_map_cities_on"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
            </tr>
            <tr>
                <td><?php esc_html_e('Clouds', 'owm-weather') ?></td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_clouds"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_clouds_meta" name="owmweather_map_clouds"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_clouds_legend"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_clouds_legend_meta" name="owmweather_map_clouds_legend"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_clouds_on"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_clouds_on_meta" name="owmweather_map_clouds_on"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
            </tr>
            <tr>
                <td><?php esc_html_e('Precipitation', 'owm-weather') ?></td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_precipitation"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_precipitation_meta" name="owmweather_map_precipitation"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_precipitation_legend"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_precipitation_legend_meta" name="owmweather_map_precipitation_legend"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_precipitation_on"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_precipitation_on_meta" name="owmweather_map_precipitation_on"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
            </tr>
            <tr>
                <td><?php esc_html_e('Rain', 'owm-weather') ?></td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_rain"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_rain_meta" name="owmweather_map_rain"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_rain_legend"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_rain_legend_meta" name="owmweather_map_rain_legend"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_rain_on"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_rain_on_meta" name="owmweather_map_rain_on"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
            </tr>
            <tr>
                <td><?php esc_html_e('Snow', 'owm-weather') ?></td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_snow"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_snow_meta" name="owmweather_map_snow"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_snow_legend"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_snow_legend_meta" name="owmweather_map_snow_legend"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_snow_on"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_snow_on_meta" name="owmweather_map_snow_on"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
            </tr>
            <tr>
                <td><?php esc_html_e('Wind', 'owm-weather') ?></td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_wind"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_wind_meta" name="owmweather_map_wind"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_wind_legend"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_wind_legend_meta" name="owmweather_map_wind_legend"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_wind_on"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_wind_on_meta" name="owmweather_map_wind_on"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
            </tr>
            <tr>
                <td><?php esc_html_e('Temperature', 'owm-weather') ?></td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_temperature"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_temperature_meta" name="owmweather_map_temperature"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_temperature_legend"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_temperature_legend_meta" name="owmweather_map_temperature_legend"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_temperature_on"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_temperature_on_meta" name="owmweather_map_temperature_on"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
            </tr>
            <tr>
                <td><?php esc_html_e('Pressure', 'owm-weather') ?></td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_pressure"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_pressure_meta" name="owmweather_map_pressure"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_pressure_legend"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_pressure_legend_meta" name="owmweather_map_pressure_legend"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_pressure_on"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_pressure_on_meta" name="owmweather_map_pressure_on"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
            </tr>
            <tr>
                <td><?php esc_html_e('Wind Rose', 'owm-weather') ?></td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_windrose"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_windrose_meta" name="owmweather_map_windrose"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_windrose_legend"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_windrose_legend_meta" name="owmweather_map_windrose_legend"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
                <td>
                  <label class="toggle-switchy" data-size="sm" data-text="false" data-color="green">
                    <input <?php echo checked($owmw_opt["map_windrose_on"], 'yes', false) ?> value="yes" type="checkbox" id="owmweather_map_windrose_on_meta" name="owmweather_map_windrose_on"/>
                    <span class="toggle">
                      <span class="switch"></span>
                    </span>
                  </label>
                </td>
            </tr>
            </tbody>
            </table>
        </div>
</div>
    <?php
    echo ob_get_clean();
}

function printYTvideoTN($video)
{
    ?>
    <script type="text/javascript">
    var <?php echo $video ?>_yt_video = document.getElementById("owmweather_<?php echo $video ?>_yt_video_meta");
    <?php echo $video ?>_yt_video.addEventListener("change", function(){
        var <?php echo $video ?>_yt_video_tn = document.getElementById("owmweather_<?php echo $video ?>_yt_video_tn");
        <?php echo $video ?>_yt_video_tn.src = "";
        if (<?php echo $video ?>_yt_video.value.length > 0) {
            <?php echo $video ?>_yt_video_tn.src = "https://img.youtube.com/vi/" + document.getElementById("owmweather_<?php echo $video ?>_yt_video_meta").value + "/default.jpg";
            <?php echo $video ?>_yt_video_tn.style.display = "block";
        } else {
            <?php echo $video ?>_yt_video_tn.style.display = "none";
        }
    });
    </script>
    <?php
}

function owmw_save_metabox($post_id)
{
    if (!empty($_POST['action']) && sanitize_text_field($_POST['action']) == 'inline-save') {
        return;
    }

    if ('owm-weather' === get_post_type($post_id)) {
        update_post_meta($post_id, '_owmweather_version', OWM_WEATHER_VERSION);

        owmw_save_metabox_field('city', $post_id);
        owmw_save_metabox_field('custom_city_name', $post_id);
        owmw_save_metabox_field('id_owm', $post_id);
        owmw_save_metabox_field('longitude', $post_id);
        owmw_save_metabox_field('latitude', $post_id);
        owmw_save_metabox_field('zip', $post_id);
        owmw_save_metabox_field('country_code', $post_id);
        owmw_save_metabox_field('zip_country_code', $post_id);
        owmw_save_metabox_field('unit', $post_id);
        owmw_save_metabox_field('time_format', $post_id);
        owmw_save_metabox_field('custom_timezone', $post_id);
        owmw_save_metabox_field('today_date_format', $post_id);
        owmw_save_metabox_field('wind_unit', $post_id);
        owmw_save_metabox_field('wind_icon_direction', $post_id);
        owmw_save_metabox_field('pressure_unit', $post_id);
        owmw_save_metabox_field('hours_forecast_no', $post_id);
        owmw_save_metabox_field('alerts_popup', $post_id);
        owmw_save_metabox_field('display_length_days_names', $post_id);
        owmw_save_metabox_field('forecast_no', $post_id);
        owmw_save_metabox_field('background_color', $post_id);
        owmw_save_metabox_field('background_opacity', $post_id);
        owmw_save_metabox_field('sunny_background_color', $post_id);
        owmw_save_metabox_field('cloudy_background_color', $post_id);
        owmw_save_metabox_field('drizzly_background_color', $post_id);
        owmw_save_metabox_field('rainy_background_color', $post_id);
        owmw_save_metabox_field('snowy_background_color', $post_id);
        owmw_save_metabox_field('stormy_background_color', $post_id);
        owmw_save_metabox_field('foggy_background_color', $post_id);
        owmw_save_metabox_field('background_image', $post_id);
        owmw_save_metabox_field('background_yt_video', $post_id);
        owmw_save_metabox_field('sunny_background_yt_video', $post_id);
        owmw_save_metabox_field('cloudy_background_yt_video', $post_id);
        owmw_save_metabox_field('drizzly_background_yt_video', $post_id);
        owmw_save_metabox_field('rainy_background_yt_video', $post_id);
        owmw_save_metabox_field('snowy_background_yt_video', $post_id);
        owmw_save_metabox_field('stormy_background_yt_video', $post_id);
        owmw_save_metabox_field('foggy_background_yt_video', $post_id);
        owmw_save_metabox_field('sunny_background_image', $post_id);
        owmw_save_metabox_field('cloudy_background_image', $post_id);
        owmw_save_metabox_field('drizzly_background_image', $post_id);
        owmw_save_metabox_field('rainy_background_image', $post_id);
        owmw_save_metabox_field('snowy_background_image', $post_id);
        owmw_save_metabox_field('stormy_background_image', $post_id);
        owmw_save_metabox_field('foggy_background_image', $post_id);
        owmw_save_metabox_field('text_color', $post_id);
        owmw_save_metabox_field('sunny_text_color', $post_id);
        owmw_save_metabox_field('cloudy_text_color', $post_id);
        owmw_save_metabox_field('drizzly_text_color', $post_id);
        owmw_save_metabox_field('rainy_text_color', $post_id);
        owmw_save_metabox_field('snowy_text_color', $post_id);
        owmw_save_metabox_field('stormy_text_color', $post_id);
        owmw_save_metabox_field('foggy_text_color', $post_id);
        owmw_save_metabox_field('border_color', $post_id);
        owmw_save_metabox_field('border_width', $post_id);
        owmw_save_metabox_field('border_style', $post_id);
        owmw_save_metabox_field('border_radius', $post_id);
        owmw_save_metabox_field('custom_css', $post_id);
        owmw_save_metabox_field('size', $post_id);
        owmw_save_metabox_field('font', $post_id);
        owmw_save_metabox_field('template', $post_id);
        owmw_save_metabox_field('iconpack', $post_id);
        owmw_save_metabox_field('map_height', $post_id);
        owmw_save_metabox_field('map_opacity', $post_id);
        owmw_save_metabox_field('map_zoom', $post_id);
        owmw_save_metabox_field('owm_language', $post_id);
        owmw_save_metabox_field('chart_height', $post_id);
        owmw_save_metabox_field('chart_text_color', $post_id);
        owmw_save_metabox_field('chart_night_color', $post_id);
        owmw_save_metabox_field('chart_background_color', $post_id);
        owmw_save_metabox_field('chart_border_color', $post_id);
        owmw_save_metabox_field('chart_border_width', $post_id);
        owmw_save_metabox_field('chart_border_style', $post_id);
        owmw_save_metabox_field('chart_border_radius', $post_id);
        owmw_save_metabox_field('chart_temperature_color', $post_id);
        owmw_save_metabox_field('chart_feels_like_color', $post_id);
        owmw_save_metabox_field('chart_dew_point_color', $post_id);
        owmw_save_metabox_field('chart_cloudiness_color', $post_id);
        owmw_save_metabox_field('chart_rain_chance_color', $post_id);
        owmw_save_metabox_field('chart_humidity_color', $post_id);
        owmw_save_metabox_field('chart_pressure_color', $post_id);
        owmw_save_metabox_field('chart_rain_amt_color', $post_id);
        owmw_save_metabox_field('chart_snow_amt_color', $post_id);
        owmw_save_metabox_field('chart_wind_speed_color', $post_id);
        owmw_save_metabox_field('chart_wind_gust_color', $post_id);
        owmw_save_metabox_field('table_background_color', $post_id);
        owmw_save_metabox_field('table_text_color', $post_id);
        owmw_save_metabox_field('table_border_color', $post_id);
        owmw_save_metabox_field('table_border_width', $post_id);
        owmw_save_metabox_field('table_border_style', $post_id);
        owmw_save_metabox_field('table_border_radius', $post_id);
        owmw_save_metabox_field('tabbed_btn_text_color', $post_id);
        owmw_save_metabox_field('tabbed_btn_background_color', $post_id);
        owmw_save_metabox_field('tabbed_btn_active_color', $post_id);
        owmw_save_metabox_field('tabbed_btn_hover_color', $post_id);
        owmw_save_metabox_field('timemachine_date', $post_id);
        owmw_save_metabox_field('timemachine_time', $post_id);

        owmw_save_metabox_field_yn('current_city_name', $post_id);
        owmw_save_metabox_field_yn('current_weather_symbol', $post_id);
        owmw_save_metabox_field_yn('current_weather_description', $post_id);
        owmw_save_metabox_field_yn('display_temperature_unit', $post_id);
        owmw_save_metabox_field_yn('sunrise_sunset', $post_id);
        owmw_save_metabox_field_yn('moonrise_moonset', $post_id);
        owmw_save_metabox_field_yn('wind', $post_id);
        owmw_save_metabox_field_yn('humidity', $post_id);
        owmw_save_metabox_field_yn('dew_point', $post_id);
        owmw_save_metabox_field_yn('pressure', $post_id);
        owmw_save_metabox_field_yn('cloudiness', $post_id);
        owmw_save_metabox_field_yn('precipitation', $post_id);
        owmw_save_metabox_field_yn('visibility', $post_id);
        owmw_save_metabox_field_yn('uv_index', $post_id);
        owmw_save_metabox_field_yn('text_labels', $post_id);
        owmw_save_metabox_field_yn('current_temperature', $post_id);
        owmw_save_metabox_field_yn('current_feels_like', $post_id);
        owmw_save_metabox_field_yn('disable_spinner', $post_id);
        owmw_save_metabox_field_yn('disable_anims', $post_id);
        owmw_save_metabox_field_yn('owm_link', $post_id);
        owmw_save_metabox_field_yn('last_update', $post_id);
        owmw_save_metabox_field_yn('map_disable_zoom_wheel', $post_id);
        owmw_save_metabox_field_yn('map_cities', $post_id);
        owmw_save_metabox_field_yn('map_cities_legend', $post_id);
        owmw_save_metabox_field_yn('map_cities_on', $post_id);
        owmw_save_metabox_field_yn('map_clouds', $post_id);
        owmw_save_metabox_field_yn('map_clouds_legend', $post_id);
        owmw_save_metabox_field_yn('map_clouds_on', $post_id);
        owmw_save_metabox_field_yn('map_precipitation', $post_id);
        owmw_save_metabox_field_yn('map_precipitation_legend', $post_id);
        owmw_save_metabox_field_yn('map_precipitation_on', $post_id);
        owmw_save_metabox_field_yn('map_rain', $post_id);
        owmw_save_metabox_field_yn('map_rain_legend', $post_id);
        owmw_save_metabox_field_yn('map_rain_on', $post_id);
        owmw_save_metabox_field_yn('map_snow', $post_id);
        owmw_save_metabox_field_yn('map_snow_legend', $post_id);
        owmw_save_metabox_field_yn('map_snow_on', $post_id);
        owmw_save_metabox_field_yn('map_wind', $post_id);
        owmw_save_metabox_field_yn('map_wind_legend', $post_id);
        owmw_save_metabox_field_yn('map_wind_on', $post_id);
        owmw_save_metabox_field_yn('map_temperature', $post_id);
        owmw_save_metabox_field_yn('map_temperature_legend', $post_id);
        owmw_save_metabox_field_yn('map_temperature_on', $post_id);
        owmw_save_metabox_field_yn('map_pressure', $post_id);
        owmw_save_metabox_field_yn('map_pressure_legend', $post_id);
        owmw_save_metabox_field_yn('map_pressure_on', $post_id);
        owmw_save_metabox_field_yn('map_windrose', $post_id);
        owmw_save_metabox_field_yn('map_windrose_legend', $post_id);
        owmw_save_metabox_field_yn('map_windrose_on', $post_id);
        owmw_save_metabox_field_yn('gtag', $post_id);
        owmw_save_metabox_field_yn('timemachine', $post_id);
        owmw_save_metabox_field_yn('network_share', $post_id);
        owmw_save_metabox_field_yn('bypass_exclude', $post_id);
        owmw_save_metabox_field_yn('map', $post_id);
        owmw_save_metabox_field_yn('alerts', $post_id);
        owmw_save_metabox_field_yn('hours_time_icons', $post_id);
    }
}
add_action('save_post', 'owmw_save_metabox');

function owmw_save_metabox_field($field, $post_id)
{
    if (!empty($_POST['owmweather_' . $field])) {
        update_post_meta($post_id, '_owmweather_' . $field, owmw_sanitize_validate_field($field, sanitize_text_field($_POST['owmweather_' . $field])));
    } else {
        delete_post_meta($post_id, '_owmweather_' . $field);
    }
}

function owmw_save_metabox_field_yn($field, $post_id)
{
    if (isset($_POST['owmweather_' . $field]) && sanitize_text_field($_POST['owmweather_' . $field]) == 'yes') {
        update_post_meta($post_id, '_owmweather_' . $field, 'yes');
    } else {
        delete_post_meta($post_id, '_owmweather_' . $field);
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Function CSS/Display/Misc
///////////////////////////////////////////////////////////////////////////////////////////////////

function owmw_css_color($attribute, $color)
{
    if (!empty($color)) {
        return esc_attr($attribute) . ': ' . esc_attr($color) . ';';
    } elseif ($attribute == 'fill') {
        return esc_attr($attribute) . ': currentColor;';
    }

    return '';
}

function owmw_css_border($color, $width = '1', $style = 'solid', $radius_val = null)
{
    $str = '';

    if ($color) {
            $str .= 'border: ' . esc_attr($width) . 'px ' . esc_attr($style) . ' ' . esc_attr($color) . ';';
    }

    if ($radius_val) {
            $str .= 'border-radius: ' . esc_attr($radius_val) . 'px;';
    }

    return $str;
}

function owmw_weather_based_text_color($owmw_opt, $condition_id)
{
    if ($condition_id == 800 && !empty($owmw_opt["sunny_text_color"])) {
        return $owmw_opt["sunny_text_color"];
    } elseif (($condition_id > 800 && $condition_id < 900) && !empty($owmw_opt["cloudy_text_color"])) {
        return $owmw_opt["cloudy_text_color"];
    } elseif (($condition_id >= 700 && $condition_id < 800) && !empty($owmw_opt["foggy_text_color"])) {
        return $owmw_opt["foggy_text_color"];
    } elseif (($condition_id >= 600 && $condition_id < 700) && !empty($owmw_opt["snowy_text_color"])) {
        return $owmw_opt["snowy_text_color"];
    } elseif (($condition_id >= 500 && $condition_id < 600) && !empty($owmw_opt["rainy_text_color"])) {
        return $owmw_opt["rainy_text_color"];
    } elseif (($condition_id >= 300 && $condition_id < 400) && !empty($owmw_opt["drizzly_text_color"])) {
        return $owmw_opt["drizzly_text_color"];
    } elseif (($condition_id >= 200 && $condition_id < 300) && !empty($owmw_opt["stormy_text_color"])) {
        return $owmw_opt["stormy_text_color"];
    } else {
        return $owmw_opt["text_color"];
    }
}

function owmw_css_weather_based_text_color($owmw_opt, $condition_id)
{
    $color = owmw_weather_based_text_color($owmw_opt, $condition_id);

    if (!empty($color)) {
        return 'color: ' . esc_attr($color) . ';';
    }

    return '';
}

function owmw_css_background_color($owmw_opt, $condition_id)
{
    if ($condition_id == 800 && !empty($owmw_opt["sunny_background_color"])) {
        return 'background-color: ' . esc_attr($owmw_opt["sunny_background_color"]) . ';';
    } elseif (($condition_id > 800 && $condition_id < 900) && !empty($owmw_opt["cloudy_background_color"])) {
        return 'background-color: ' . esc_attr($owmw_opt["cloudy_background_color"]) . ';';
    } elseif (($condition_id >= 700 && $condition_id < 800) && !empty($owmw_opt["foggy_background_color"])) {
        return 'background-color: ' . esc_attr($owmw_opt["foggy_background_color"]) . ';';
    } elseif (($condition_id >= 600 && $condition_id < 700) && !empty($owmw_opt["snowy_background_color"])) {
        return 'background-color: ' . esc_attr($owmw_opt["snowy_background_color"]) . ';';
    } elseif (($condition_id >= 500 && $condition_id < 600) && !empty($owmw_opt["rainy_background_color"])) {
        return 'background-color: ' . esc_attr($owmw_opt["rainy_background_color"]) . ';';
    } elseif (($condition_id >= 300 && $condition_id < 400) && !empty($owmw_opt["drizzly_background_color"])) {
        return 'background-color: ' . esc_attr($owmw_opt["drizzly_background_color"]) . ';';
    } elseif (($condition_id >= 200 && $condition_id < 300) && !empty($owmw_opt["stormy_background_color"])) {
        return 'background-color: ' . esc_attr($owmw_opt["stormy_background_color"]) . ';';
    } elseif (!empty($owmw_opt["background_color"])) {
        return 'background-color: ' . esc_attr($owmw_opt["background_color"]) . ';';
    }
    return '';
}

function owmw_css_background_image($owmw_opt, $condition_id)
{
    $css = 'background-color: rgba(255,255,255,' . (string)(1.0 - floatval($owmw_opt["background_opacity"])) . ')!important; background-blend-mode: lighten;';
    if ($condition_id == 800 && !empty($owmw_opt["sunny_background_image"])) {
        return $css . 'background-image: url(\'' . wp_get_attachment_url($owmw_opt["sunny_background_image"]) . '\');';
    } elseif (($condition_id > 800 && $condition_id < 900) && !empty($owmw_opt["cloudy_background_image"])) {
        return $css . 'background-image: url(\'' . wp_get_attachment_url($owmw_opt["cloudy_background_image"]) . '\');';
    } elseif (($condition_id >= 700 && $condition_id < 800) && !empty($owmw_opt["foggy_background_image"])) {
        return $css . 'background-image: url(\'' . wp_get_attachment_url($owmw_opt["foggy_background_image"]) . '\');';
    } elseif (($condition_id >= 600 && $condition_id < 700) && !empty($owmw_opt["snowy_background_image"])) {
        return $css . 'background-image: url(\'' . wp_get_attachment_url($owmw_opt["snowy_background_image"]) . '\');';
    } elseif (($condition_id >= 500 && $condition_id < 600) && !empty($owmw_opt["rainy_background_image"])) {
        return $css . 'background-image: url(\'' . wp_get_attachment_url($owmw_opt["rainy_background_image"]) . '\');';
    } elseif (($condition_id >= 300 && $condition_id < 400) && !empty($owmw_opt["drizzly_background_image"])) {
        return $css . 'background-image: url(\'' . wp_get_attachment_url($owmw_opt["drizzly_background_image"]) . '\');';
    } elseif (($condition_id >= 200 && $condition_id < 300) && !empty($owmw_opt["stormy_background_image"])) {
        return $css . 'background-image: url(\'' . wp_get_attachment_url($owmw_opt["stormy_background_image"]) . '\');';
    } elseif (!empty($owmw_opt["background_image"])) {
        return $css . 'background-image: url(\'' . wp_get_attachment_url($owmw_opt["background_image"]) . '\');';
    }
    return '';
}

function owmw_background_yt_video($owmw_opt, $condition_id)
{
    if ($condition_id == 800 && !empty($owmw_opt["sunny_background_yt_video"])) {
        return $owmw_opt["sunny_background_yt_video"];
    } elseif (($condition_id > 800 && $condition_id < 900) && !empty($owmw_opt["cloudy_background_yt_video"])) {
        return $owmw_opt["cloudy_background_yt_video"];
    } elseif (($condition_id >= 700 && $condition_id < 800) && !empty($owmw_opt["foggy_background_yt_video"])) {
        return $owmw_opt["foggy_background_yt_video"];
    } elseif (($condition_id >= 600 && $condition_id < 700) && !empty($owmw_opt["snowy_background_yt_video"])) {
        return $owmw_opt["snowy_background_yt_video"];
    } elseif (($condition_id >= 500 && $condition_id < 600) && !empty($owmw_opt["rainy_background_yt_video"])) {
        return $owmw_opt["rainy_background_yt_video"];
    } elseif (($condition_id >= 300 && $condition_id < 400) && !empty($owmw_opt["drizzly_background_yt_video"])) {
        return $owmw_opt["drizzly_background_yt_video"];
    } elseif (($condition_id >= 200 && $condition_id < 300) && !empty($owmw_opt["stormy_background_yt_video"])) {
        return $owmw_opt["stormy_background_yt_video"];
    } elseif (!empty($owmw_opt["background_yt_video"])) {
        return $owmw_opt["background_yt_video"];
    }
    return '';
}

function owmw_css_background_size($size)
{
    if ($size) {
            return 'background-size: ' . esc_attr($size) . ';';
    }
    return '';
}

/* bugbug
function owmw_css_background_position($horizontal, $vertical) {
    if( $horizontal && $vertical ) {
            return 'background-position: '.esc_attr($horizontal).'% '.esc_attr($vertical).'%;';
    }
    return "";
}
*/

function owmw_css_font_family($font)
{
    if ($font != 'Default') {
            return 'font-family: \'' . esc_attr($font) . '\';';
    }
    return '';
}

function owmw_css_height($height)
{
    if ($height) {
            return 'height: ' . esc_attr($height) . 'px;';
    }
    return '';
}


function owmw_city_name($custom_city_name, $owm_city_name)
{
    $str = '';
    if (!empty($custom_city_name)) {
        $str = $custom_city_name;
    } elseif (!empty($owm_city_name)) {
        $str = $owm_city_name;
    }

    return esc_html($str);
}

function owmw_display_today_sunrise_sunset($owmweather_sunrise_sunset, $sun_rise, $sun_set, $color, $elem)
{
    if ($owmweather_sunrise_sunset == 'yes') {
        return '<div class="owmw-sun-hours col">
					<' . esc_attr($elem) . ' class="owmw-sunrise" title="' . esc_attr__('Sunrise', 'owm-weather') . '">' . owmw_sunrise($color) . '<span class="font-weight-bold">' . esc_html($sun_rise) . '</span></' . esc_attr($elem) . '><' . esc_attr($elem) . ' class="owmw-sunset" title="' . esc_attr__('Sunset', 'owm-weather') . '">' . owmw_sunset($color) . '<span class="font-weight-bold">' . esc_html($sun_set) . '</span></' . esc_attr($elem) . '>
				</div>';
    }

    return '';
}

function owmw_display_today_moonrise_moonset($owmweather_moonrise_moonset, $moon_rise, $moon_set, $color, $elem)
{
    if ($owmweather_moonrise_moonset == 'yes') {
        return '<div class="owmw-moon-hours col">
					<' . esc_attr($elem) . ' class="owmw-moonrise" title="' . esc_attr__('Moonrise', 'owm-weather') . '">' . owmw_moonrise($color) . '<span class="font-weight-bold">' . esc_html($moon_rise) . '</span></' . esc_attr($elem) . '><' . esc_attr($elem) . ' class="owmw-moonset" title="' . esc_attr__('Moonset', 'owm-weather') . '">' . owmw_moonset($color) . '<span class="font-weight-bold">' . esc_html($moon_set) . '</span></' . esc_attr($elem) . '>
				</div>';
    }

    return '';
}

function owmw_webfont($bypass, $id)
{
    $owmw_webfont_value = owmw_get_bypass($bypass, "font", $id);

    if ($owmw_webfont_value != 'Default') {
        wp_register_style($owmw_webfont_value, '//fonts.googleapis.com/css?family=' . str_replace(' ', '+', esc_attr($owmw_webfont_value)) . ':400&display=swap');
        wp_enqueue_style($owmw_webfont_value);
    }

    return $owmw_webfont_value;
}

function owmw_icons_pack($bypass, $id)
{
    $iconpack = owmw_get_bypass($bypass, "iconpack", $id);

    if ($iconpack == 'WeatherIcons') {
        wp_register_style("weathericons-css", plugins_url('css/weather-icons.min.css', __FILE__));
        wp_enqueue_style("weathericons-css");
    } elseif ($iconpack == 'Forecast') {
        wp_register_style("iconvault-css", plugins_url('css/iconvault.min.css', __FILE__));
        wp_enqueue_style("iconvault-css");
    } elseif ($iconpack == 'Dripicons') {
        wp_register_style("dripicons-css", plugins_url('css/dripicons.min.css', __FILE__));
        wp_enqueue_style("dripicons-css");
    } elseif ($iconpack == 'Pixeden') {
        wp_register_style("pe-icon-set-weather-css", plugins_url('css/pe-icon-set-weather.min.css', __FILE__));
        wp_enqueue_style("pe-icon-set-weather-css");
    } elseif ($iconpack == 'ColorAnimated') {
        wp_register_style("ca-animate-min-css", plugins_url('css/animate.min.css', __FILE__));
        wp_enqueue_style("ca-animate-min-css");
        wp_register_style("ca-colorAnimated-min-css", plugins_url('css/colorAnimated.min.css', __FILE__));
        wp_enqueue_style("ca-colorAnimated-min-css");
        wp_register_style("climacons-css", plugins_url('css/climacons-font.min.css', __FILE__));
        wp_enqueue_style("climacons-css");
    } else {
        wp_register_style("climacons-css", plugins_url('css/climacons-font.min.css', __FILE__));
        wp_enqueue_style("climacons-css");
    }

    return $iconpack;
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Add shortcode Weather
///////////////////////////////////////////////////////////////////////////////////////////////////

function owmw_get_my_weather_id($atts)
{
    global $owmw_params;
    global $post;
    $need_restore_blog = false;

    require_once dirname(__FILE__) . '/owmweather-options.php';

    $atts = array_change_key_case((array)$atts, CASE_LOWER);
    $owmw_params = shortcode_atts(array(
        "id"                            => false,
        "id_owm"                        => false,
        "longitude"                     => false,
        "latitude"                      => false,
        "zip"                           => false,
        "zip_country_code"              => false,
        "city"                          => false,
        "country_code"                  => false,
        "custom_city_name"              => false,
        "custom_timezone"               => false,
        "owm_language"                  => false,
        "font"                          => false,
        "iconpack"                      => false,
        "template"                      => false,
        "size"                          => false,
        "disable_spinner"               => false,
        "disable_anims"                 => false,
        "background_image"              => false,
        "background_yt_video"           => false,
        "sunny_background_yt_video"     => false,
        "cloudy_background_yt_video"    => false,
        "drizzly_background_yt_video"   => false,
        "rainy_background_yt_video"     => false,
        "snowy_background_yt_video"     => false,
        "stormy_background_yt_video"    => false,
        "foggy_background_yt_video"     => false,
        "sunny_background_image"        => false,
        "cloudy_background_image"       => false,
        "drizzly_background_image"      => false,
        "rainy_background_image"        => false,
        "snowy_background_image"        => false,
        "stormy_background_image"       => false,
        "foggy_background_image"        => false,
        "forecast_no"                   => false,
        "hours_forecast_no"             => false,
        "unit"                          => false,
        "time_format"                   => false,
        "today_date_format"             => false,
        "wind_unit"                     => false,
        "wind_icon_direction"           => false,
        "pressure_unit"                 => false,
        "alerts_popup"                  => false,
        "display_length_days_names"     => false,
        "background_color"              => false,
        "background_opacity"            => false,
        "sunny_background_color"        => false,
        "cloudy_background_color"       => false,
        "drizzly_background_color"      => false,
        "rainy_background_color"        => false,
        "snowy_background_color"        => false,
        "stormy_background_color"       => false,
        "foggy_background_color"        => false,
        "text_color"                    => false,
        "sunny_text_color"              => false,
        "cloudy_text_color"             => false,
        "drizzly_text_color"            => false,
        "rainy_text_color"              => false,
        "snowy_text_color"              => false,
        "stormy_text_color"             => false,
        "foggy_text_color"              => false,
        "border_color"                  => false,
        "border_width"                  => false,
        "border_style"                  => false,
        "border_radius"                 => false,
        "custom_css"                    => false,
        "map_height"                    => false,
        "map_opacity"                   => false,
        "map_zoom"                      => false,
        "chart_height"                  => false,
        "chart_txt_color"               => false,
        "chart_background_color"        => false,
        "chart_border_color"            => false,
        "chart_border_width"            => false,
        "chart_border_style"            => false,
        "chart_border_radius"           => false,
        "chart_temperature_color"       => false,
        "chart_feels_like_color"        => false,
        "chart_dew_point_color"         => false,
        "chart_cloudiness_color"        => false,
        "chart_rain_chance_color"       => false,
        "chart_humidity_color"          => false,
        "chart_pressure_color"          => false,
        "chart_rain_amt_color"          => false,
        "chart_snow_amt_color"          => false,
        "chart_wind_speed_color"        => false,
        "chart_wind_gust_color"         => false,
        "table_background_color"        => false,
        "table_text_color"              => false,
        "table_border_color"            => false,
        "table_border_width"            => false,
        "table_border_style"            => false,
        "table_border_radius"           => false,
        "tabbed_btn_text_color"         => false,
        "tabbed_btn_background_color"   => false,
        "tabbed_btn_active_color"       => false,
        "tabbed_btn_hover_color"        => false,
        "current_city_name"             => false,
        "current_weather_symbol"        => false,
        "current_weather_description"   => false,
        "current_temperature"           => false,
        "current_feels_like"            => false,
        "display_temperature_unit"      => false,
        "sunrise_sunset"                => false,
        "moonrise_moonset"              => false,
        "wind"                          => false,
        "humidity"                      => false,
        "dew_point"                     => false,
        "pressure"                      => false,
        "cloudiness"                    => false,
        "precipitation"                 => false,
        "visibility"                    => false,
        "uv_index"                      => false,
        "text_labels"                   => false,
        "owm_link"                      => false,
        "last_update"                   => false,
        "map"                           => false,
        "map_disable_zoom_wheel"        => false,
        "map_cities"                    => false,
        "map_cities_legend"             => false,
        "map_cities_on"                 => false,
        "map_clouds"                    => false,
        "map_clouds_legend"             => false,
        "map_clouds_on"                 => false,
        "map_precipitation"             => false,
        "map_precipitation_legend"      => false,
        "map_precipitation_on"          => false,
        "map_rain"                      => false,
        "map_rain_legend"               => false,
        "map_rain_on"                   => false,
        "map_snow"                      => false,
        "map_snow_legend"               => false,
        "map_snow_on"                   => false,
        "map_wind"                      => false,
        "map_wind_legend"               => false,
        "map_wind_on"                   => false,
        "map_temperature"               => false,
        "map_temperature_legend"        => false,
        "map_temperature_on"            => false,
        "map_pressure"                  => false,
        "map_pressure_legend"           => false,
        "map_pressure_on"               => false,
        "map_windrose"                  => false,
        "map_windrose_legend"           => false,
        "map_windrose_on"               => false,
        "gtag"                          => false,
        "timemachine"                   => false,
        "timemachine_date"                => false,
        "timemachine_time"                => false,
        "network_share"                 => false,
        "bypass_exclude"                => false,
        "alerts"                        => false,
        "hours_time_icons"              => false,
    ), $atts);

    owmw_sanitize_atts($owmw_params);

    foreach ($owmw_params as $key => $value) {
        if (empty($value)) {
            unset($owmw_params[$key]);
        }
    }

    if (empty($owmw_params)) {
        return;
    }

    if (empty($owmw_params["id"])) {
        echo "<p>OWM Weather Error: owm-weather shortcode without 'id' parameter</p>";
        return;
    }

    if (owmw_is_global_multisite() && $owmw_params["id"][0] === "m") {
        if (!is_main_site()) {
            switch_to_blog(get_main_site_id());
            $need_restore_blog = true;
        }

        $id = intval(substr($owmw_params["id"], 1));
        if (get_post_meta($id, '_owmweather_network_share', true) != "yes" && $need_restore_blog) {
            echo "<p>OWM Weather Error: network id '" . esc_html($owmw_params["id"]) . "' is not shared</p>";
            if ($need_restore_blog) {
                restore_current_blog();
            }
            return;
        }

        $owmw_params["id"] = $id;
        $owmw_params["network_share"] = true;
    }

    if (get_post_type($owmw_params["id"]) != 'owm-weather') {
        echo "<p>OWM Weather Error: id '" . esc_html($owmw_params["id"]) . "' is not type 'weather'</p>";
        return;
    }

    if (get_post_status($owmw_params["id"]) != 'publish') {
        echo "<p>OWM Weather Error: id '" . esc_html($owmw_params["id"]) . "' is not published</p>";
        return;
    }

    $owmw_opt                    = [];
    $owmw_opt["id"]              = $owmw_params["id"];
    $owmw_opt["bypass_exclude"]  = get_post_meta($owmw_params["id"], '_owmweather_bypass_exclude', true);
    $bypass                      = $owmw_opt["bypass_exclude"] != 'yes';
    $owmw_opt["disable_anims"]   = $owmw_params["disable_anims"] ?? owmw_get_bypass_yn($bypass, "disable_anims", $owmw_opt["id"]);
    $owmw_opt["map"]             = $owmw_params["map"] ?? owmw_get_bypass_yn($bypass, "map", $owmw_opt["id"]);
    $owmw_opt["template"]        = $owmw_params["template"] ?? owmw_get_bypass($bypass, "template", $owmw_opt["id"]);
    $owmw_opt["disable_spinner"] = $owmw_params["disable_spinner"] ?? owmw_get_bypass_yn($bypass, "disable_spinner", $owmw_opt["id"]);
    $owmw_opt["id_owm"]          = $owmw_params["id_owm"] ?? owmw_get_bypass($bypass, "id_owm", $owmw_opt["id"]);
    $owmw_opt["longitude"]       = $owmw_params["longitude"] ?? owmw_get_bypass($bypass, "longitude", $owmw_opt["id"]);
    $owmw_opt["latitude"]        = $owmw_params["latitude"] ?? owmw_get_bypass($bypass, "latitude", $owmw_opt["id"]);
    $owmw_opt["zip"]             = $owmw_params["zip"] ?? owmw_get_bypass($bypass, "zip", $owmw_opt["id"]);
    $owmw_opt["city"]            = $owmw_params["city"] ?? owmw_get_bypass($bypass, "city", $owmw_opt["id"]);
    if (str_starts_with($owmw_opt["city"], "@")) {
        $owmw_opt["city"] = sanitize_text_field(get_post_meta($post->ID, substr($owmw_opt["city"], 1), true));
        $owmw_params["post_id"] = $post->ID;
    }

    owmw_webfont($bypass, $owmw_params["id"]);
    owmw_icons_pack($bypass, $owmw_params["id"]);


    if ($need_restore_blog) {
        restore_current_blog();
    }

    if ($owmw_opt["template"] == 'slider1' || $owmw_opt["template"] == 'slider2') {
        wp_enqueue_script('jquery');
        wp_enqueue_script('owmw-flexslider-js');
        wp_enqueue_style('owmw-flexsl1ider-css');
    } elseif (in_array($owmw_opt["template"], array('custom1', 'custom2','chart1', 'chart2', 'tabbed2', 'debug'))) {
        wp_enqueue_script('owmw-custom-chart-js');
    }

    if (owmw_get_admin_bypass('owmw_advanced_disable_modal_js') != 'yes') {
            wp_enqueue_style('owmw-bootstrap5-css');
            wp_enqueue_script('owmw-bootstrap5-js');
    }

    if ($owmw_opt["disable_anims"] != 'yes') {
        wp_enqueue_style('owmweather-anim-css');
    }

    //Map
    if ($owmw_opt["map"] == 'yes') {
        wp_register_script("leaflet-js", plugins_url('js/leaflet.js', __FILE__), "1.0", false);

        wp_register_script("leaflet-openweathermap-js", plugins_url('js/leaflet-openweathermap.js', __FILE__), "1.0", false);

        wp_register_style("leaflet-openweathermap-css", plugins_url('css/leaflet-openweathermap.css', __FILE__));
        wp_register_style("leaflet-css", plugins_url('css/leaflet.css', __FILE__));

        wp_enqueue_script("leaflet-js");

        wp_enqueue_script("leaflet-openweathermap-js");

        wp_enqueue_style("leaflet-openweathermap-css");
        wp_enqueue_style("leaflet-css");
    }

    $data_attributes_esc = [];
    foreach ($owmw_params as $key => $value) {
        if ($value !== false) {
            $data_attributes_esc[] = 'data-' . esc_attr($key) . '="' . esc_attr($value) . '"';
        }
    }

    if (empty($owmw_opt["id_owm"]) && empty($owmw_opt["longitude"]) && empty($owmw_opt["latitude"]) && empty($owmw_opt["zip"]) && empty($owmw_opt["city"])) {
        $get_transient = is_multisite() ? "get_site_transient" : "get_transient";
        $transient_key = 'owmw_geolocation_' . owmw_get_ip_from_server() . '_' . hash("md5", $_SERVER["HTTP_USER_AGENT"] ?? '');
        if (false === ($latlon = $get_transient($transient_key))) {
            $data_attributes_esc[] = 'data-geo_location="true"';
        } else {
            $data_attributes_esc[] = 'data-lat="' . esc_attr($latlon["lat"]) . '"';
            $data_attributes_esc[] = 'data-lon="' . esc_attr($latlon["lon"]) . '"';
        }
    }

    $div_id_esc = owmw_unique_id_esc("owm-weather-id-" . $owmw_opt["id"]);
    $data_attributes_esc[] = "data-weather_id=" . $div_id_esc;

    $ret = '<div id="' . $div_id_esc . '" class="owm-weather-id" ' . join(' ', $data_attributes_esc) . '>';
    if ($owmw_opt["disable_spinner"] != 'yes') {
        $ret .= '<div class="owmw-loading-spinner"><button id="button-' . $div_id_esc . '" onclick="owmw_refresh_weather(this.id)"><img src="' . plugins_url('img/owmloading.gif', __FILE__) . '" alt="loader"/></button></div>';
    }
    $ret .= '</div>';

    return $ret;
}
add_shortcode("owm-weather", 'owmw_get_my_weather_id');

function owmw_get_my_weather($attr)
{
    global $owmw_params;
    global $pressureLabel;
    global $windspeedLabel;
    global $post;

    $owmw_params = [];
    if (isset($_POST['owmw_params'])) {
        foreach ($_POST['owmw_params'] as $k => $v) {
            $owmw_params[sanitize_key($k)] = sanitize_text_field($v);
        }
    }

    check_ajax_referer('owmw_get_weather_nonce', sanitize_text_field($_POST['_ajax_nonce']), true);

    if (isset($owmw_params['id'])) {
        $id = intval($owmw_params["id"]);
        $need_restore_blog = false;
        if (owmw_is_global_multisite() && !empty($owmw_params["network_share"])) {
            switch_to_blog(get_main_site_id());
            $need_restore_blog = true;
        }

        require_once dirname(__FILE__) . '/owmweather-options.php';
        require_once dirname(__FILE__) . '/owmweather-anim.php';
        require_once dirname(__FILE__) . '/owmweather-icons.php';

        $owmw_opt                                   = [];
        $owmw_opt["id"]                             = $id;
        $owmw_opt["network_share"]                  = $owmw_params["network_share"] ?? null;
        $owmw_opt["bypass_exclude"]                 = get_post_meta($id, '_owmweather_bypass_exclude', true);
        $bypass                                     = ($owmw_opt["bypass_exclude"] != 'yes');
        $owmw_opt["id_owm"]                         = owmw_get_bypass($bypass, "id_owm");
        $owmw_opt["longitude"]                      = owmw_get_bypass($bypass, "longitude");
        $owmw_opt["latitude"]                       = owmw_get_bypass($bypass, "latitude");
        $owmw_opt["zip"]                            = str_replace(' ', '+', owmw_get_bypass($bypass, "zip"));
        $owmw_opt["zip_country_code"]               = str_replace(' ', '+', owmw_get_bypass($bypass, "zip_country_code"));
        $owmw_opt["city"]                           = strtolower(owmw_get_bypass($bypass, "city"));
        if (str_starts_with($owmw_opt["city"], "@")) {
            $owmw_opt["city"] = sanitize_text_field(get_post_meta($owmw_params["post_id"], substr($owmw_opt["city"], 1), true));
        }
        $owmw_opt["city"]                           = str_replace(' ', '+', $owmw_opt["city"]);
        $owmw_opt["country_code"]                   = str_replace(' ', '+', owmw_get_bypass($bypass, "country_code"));
        $owmw_opt["custom_city_name"]               = owmw_get_bypass($bypass, "custom_city_name");
        $owmw_opt["temperature_unit"]               = owmw_get_bypass($bypass, "unit");
        $owmw_opt["map"]                            = owmw_get_bypass_yn($bypass, "map");
        $owmw_opt["map_height"]                     = owmw_get_bypass($bypass, "map_height");
        $owmw_opt["map_opacity"]                    = owmw_get_bypass($bypass, "map_opacity");
        $owmw_opt["map_zoom"]                       = owmw_get_bypass($bypass, "map_zoom");
        $owmw_opt["map_disable_zoom_wheel"]         = owmw_get_bypass_yn($bypass, "map_disable_zoom_wheel");
        $owmw_opt["map_cities"]                     = owmw_get_bypass_yn($bypass, "map_cities");
        $owmw_opt["map_cities_legend"]              = owmw_get_bypass_yn($bypass, "map_cities_legend");
        $owmw_opt["map_cities_on"]                  = owmw_get_bypass_yn($bypass, "map_cities_on");
        $owmw_opt["map_clouds"]                     = owmw_get_bypass_yn($bypass, "map_clouds");
        $owmw_opt["map_clouds_legend"]              = owmw_get_bypass_yn($bypass, "map_clouds_legend");
        $owmw_opt["map_clouds_on"]                  = owmw_get_bypass_yn($bypass, "map_clouds_on");
        $owmw_opt["map_precipitation"]              = owmw_get_bypass_yn($bypass, "map_precipitation");
        $owmw_opt["map_precipitation_legend"]       = owmw_get_bypass_yn($bypass, "map_precipitation_legend");
        $owmw_opt["map_precipitation_on"]           = owmw_get_bypass_yn($bypass, "map_precipitation_on");
        $owmw_opt["map_rain"]                       = owmw_get_bypass_yn($bypass, "map_rain");
        $owmw_opt["map_rain_legend"]                = owmw_get_bypass_yn($bypass, "map_rain_legend");
        $owmw_opt["map_rain_on"]                    = owmw_get_bypass_yn($bypass, "map_rain_on");
        $owmw_opt["map_snow"]                       = owmw_get_bypass_yn($bypass, "map_snow");
        $owmw_opt["map_snow_legend"]                = owmw_get_bypass_yn($bypass, "map_snow_legend");
        $owmw_opt["map_snow_on"]                    = owmw_get_bypass_yn($bypass, "map_snow_on");
        $owmw_opt["map_wind"]                       = owmw_get_bypass_yn($bypass, "map_wind");
        $owmw_opt["map_wind_legend"]                = owmw_get_bypass_yn($bypass, "map_wind_legend");
        $owmw_opt["map_wind_on"]                    = owmw_get_bypass_yn($bypass, "map_wind_on");
        $owmw_opt["map_temperature"]                = owmw_get_bypass_yn($bypass, "map_temperature");
        $owmw_opt["map_temperature_legend"]         = owmw_get_bypass_yn($bypass, "map_temperature_legend");
        $owmw_opt["map_temperature_on"]             = owmw_get_bypass_yn($bypass, "map_temperature_on");
        $owmw_opt["map_pressure"]                   = owmw_get_bypass_yn($bypass, "map_pressure");
        $owmw_opt["map_pressure_legend"]            = owmw_get_bypass_yn($bypass, "map_pressure_legend");
        $owmw_opt["map_pressure_on"]                = owmw_get_bypass_yn($bypass, "map_pressure_on");
        $owmw_opt["map_windrose"]                   = owmw_get_bypass_yn($bypass, "map_windrose");
        $owmw_opt["map_windrose_legend"]            = owmw_get_bypass_yn($bypass, "map_windrose_legend");
        $owmw_opt["map_windrose_on"]                = owmw_get_bypass_yn($bypass, "map_windrose_on");
        $owmw_opt["border_color"]                   = owmw_get_bypass($bypass, "border_color");
        $owmw_opt["border_width"]                   = owmw_getBypassDefault($bypass, 'border_width', $owmw_opt["border_color"] == '' ? '0' : '1');
        $owmw_opt["border_style"]                   = owmw_get_bypass($bypass, "border_style");
        $owmw_opt["border_radius"]                  = owmw_get_bypass($bypass, "border_radius");
        $owmw_opt["background_color"]               = owmw_get_bypass($bypass, "background_color");
        $owmw_opt["background_opacity"]             = owmw_get_bypass($bypass, "background_opacity");
        $owmw_opt["sunny_background_color"]         = owmw_get_bypass($bypass, "sunny_background_color");
        $owmw_opt["cloudy_background_color"]        = owmw_get_bypass($bypass, "cloudy_background_color");
        $owmw_opt["drizzly_background_color"]       = owmw_get_bypass($bypass, "drizzly_background_color");
        $owmw_opt["rainy_background_color"]         = owmw_get_bypass($bypass, "rainy_background_color");
        $owmw_opt["snowy_background_color"]         = owmw_get_bypass($bypass, "snowy_background_color");
        $owmw_opt["stormy_background_color"]        = owmw_get_bypass($bypass, "stormy_background_color");
        $owmw_opt["foggy_background_color"]         = owmw_get_bypass($bypass, "foggy_background_color");
        $owmw_opt["background_image"]               = owmw_get_bypass($bypass, "background_image");
        $owmw_opt["background_yt_video"]            = owmw_get_bypass($bypass, "background_yt_video");
        $owmw_opt["sunny_background_yt_video"]      = owmw_get_bypass($bypass, "sunny_background_yt_video");
        $owmw_opt["cloudy_background_yt_video"]     = owmw_get_bypass($bypass, "cloudy_background_yt_video");
        $owmw_opt["drizzly_background_yt_video"]    = owmw_get_bypass($bypass, "drizzly_background_yt_video");
        $owmw_opt["rainy_background_yt_video"]      = owmw_get_bypass($bypass, "rainy_background_yt_video");
        $owmw_opt["snowy_background_yt_video"]      = owmw_get_bypass($bypass, "snowybackground_yt_video");
        $owmw_opt["stormy_background_yt_video"]     = owmw_get_bypass($bypass, "stormy_background_yt_video");
        $owmw_opt["foggy_background_yt_video"]      = owmw_get_bypass($bypass, "foggy_background_yt_video");
        $owmw_opt["sunny_background_image"]         = owmw_get_bypass($bypass, "sunny_background_image");
        $owmw_opt["cloudy_background_image"]        = owmw_get_bypass($bypass, "cloudy_background_image");
        $owmw_opt["drizzly_background_image"]       = owmw_get_bypass($bypass, "drizzly_background_image");
        $owmw_opt["rainy_background_image"]         = owmw_get_bypass($bypass, "rainy_background_image");
        $owmw_opt["snowy_background_image"]         = owmw_get_bypass($bypass, "snowy_background_image");
        $owmw_opt["stormy_background_image"]        = owmw_get_bypass($bypass, "stormy_background_image");
        $owmw_opt["foggy_background_image"]         = owmw_get_bypass($bypass, "foggy_background_image");
        $owmw_opt["text_color"]                     = owmw_get_bypass($bypass, "text_color");
        $owmw_opt["sunny_text_color"]               = owmw_get_bypass($bypass, "sunny_text_color");
        $owmw_opt["cloudy_text_color"]              = owmw_get_bypass($bypass, "cloudy_text_color");
        $owmw_opt["drizzly_text_color"]             = owmw_get_bypass($bypass, "drizzly_text_color");
        $owmw_opt["rainy_text_color"]               = owmw_get_bypass($bypass, "rainy_text_color");
        $owmw_opt["snowy_text_color"]               = owmw_get_bypass($bypass, "snowy_text_color");
        $owmw_opt["stormy_text_color"]              = owmw_get_bypass($bypass, "stormy_text_color");
        $owmw_opt["foggy_text_color"]               = owmw_get_bypass($bypass, "foggy_text_color");
        $owmw_opt["time_format"]                    = owmw_get_bypass($bypass, "time_format");
        $owmw_opt["sunrise_sunset"]                 = owmw_get_bypass_yn($bypass, "sunrise_sunset");
        $owmw_opt["moonrise_moonset"]               = owmw_get_bypass_yn($bypass, "moonrise_moonset");
        $owmw_opt["display_temperature_unit"]       = owmw_get_bypass_yn($bypass, "display_temperature_unit");
        $owmw_opt["display_length_days_names"]      = owmw_get_bypass($bypass, "display_length_days_names");
        $owmw_opt["cache_time"]                     = owmw_get_admin_cache_time();
        $owmw_opt["disable_cache"]                  = owmw_get_admin_disable_cache();
        $owmw_opt["api_key"]                        = owmw_get_admin_api_key();
        $owmw_opt["owm_link"]                       = owmw_get_bypass_yn($bypass, "owm_link");
        $owmw_opt["last_update"]                    = owmw_get_bypass_yn($bypass, "last_update");
        $owmw_opt["hours_forecast_no"]              = owmw_get_bypass($bypass, "hours_forecast_no");
        $owmw_opt["hours_time_icons"]               = owmw_get_bypass($bypass, "hours_time_icons");
        $owmw_opt["days_forecast_no"]               = owmw_get_bypass($bypass, "forecast_no");
        $owmw_opt["custom_timezone"]                = owmw_get_bypass($bypass, "custom_timezone");
        $owmw_opt["today_date_format"]              = owmw_get_bypass($bypass, "today_date_format");
        $owmw_opt["alerts"]                         = owmw_get_bypass_yn($bypass, "alerts");
        $owmw_opt["alerts_popup"]                   = owmw_get_bypass($bypass, "alerts_popup");
        $owmw_opt["owm_language"]                   = owmw_get_bypass($bypass, "owm_language");
        if ($owmw_opt["owm_language"] == 'Default') {
            $localeLang = substr(get_locale(), 0, 2);
            if (owmw_checkLanguage($localeLang)) {
                $owmw_opt["owm_language"] = $localeLang;
            } else {
                $owmw_opt["owm_language"] = 'en';
            }
        }
        $owmw_opt["font"]                           = owmw_get_bypass($bypass, "font");
        $owmw_opt["iconpack"]                       = owmw_get_bypass($bypass, "iconpack");
        $owmw_opt["template"]                       = owmw_get_bypass($bypass, "template");
        $owmw_opt["gtag"]                           = owmw_get_bypass($bypass, "gtag");
        $owmw_opt["timemachine"]                    = owmw_get_bypass($bypass, "timemachine");
        $owmw_opt["timemachine_date"]                 = owmw_get_bypass($bypass, "timemachine_date");
        $owmw_opt["timemachine_time"]                 = owmw_get_bypass($bypass, "timemachine_time");
        $owmw_opt["network_share"]                  = owmw_get_bypass($bypass, "network_share");
        $owmw_opt["custom_css"]                     = owmw_get_bypass($bypass, 'custom_css');
        $owmw_opt["current_weather_symbol"]         = owmw_get_bypass_yn($bypass, "current_weather_symbol");
        $owmw_opt["current_city_name"]              = owmw_get_bypass_yn($bypass, "current_city_name");
        $owmw_opt["current_weather_description"]    = owmw_get_bypass_yn($bypass, "current_weather_description");
        $owmw_opt["wind"]                           = owmw_get_bypass_yn($bypass, "wind");
        $owmw_opt["wind_unit"]                      = owmw_get_bypass($bypass, "wind_unit");
        $owmw_opt["wind_icon_direction"]            = owmw_get_bypass($bypass, "wind_icon_direction");
        $owmw_opt["humidity"]                       = owmw_get_bypass_yn($bypass, "humidity");
        $owmw_opt["dew_point"]                      = owmw_get_bypass_yn($bypass, "dew_point");
        $owmw_opt["pressure"]                       = owmw_get_bypass_yn($bypass, "pressure");
        $owmw_opt["pressure_unit"]                  = owmw_get_bypass($bypass, "pressure_unit");
        $owmw_opt["cloudiness"]                     = owmw_get_bypass_yn($bypass, "cloudiness");
        $owmw_opt["precipitation"]                  = owmw_get_bypass_yn($bypass, "precipitation");
        $owmw_opt["visibility"]                     = owmw_get_bypass_yn($bypass, "visibility");
        $owmw_opt["uv_index"]                       = owmw_get_bypass_yn($bypass, "uv_index");
        $owmw_opt["text_labels"]                    = owmw_get_bypass_yn($bypass, "text_labels");
        $owmw_opt["current_temperature"]            = owmw_get_bypass_yn($bypass, "current_temperature");
        $owmw_opt["current_feels_like"]             = owmw_get_bypass_yn($bypass, "current_feels_like");
        $owmw_opt["size"]                           = owmw_get_bypass($bypass, "size");
        $owmw_opt["disable_spinner"]                = owmw_get_bypass_yn($bypass, "disable_spinner");
        $owmw_opt["disable_anims"]                  = owmw_get_bypass_yn($bypass, "disable_anims");
        $owmw_opt["chart_height"]                   = owmw_getBypassDefault($bypass, 'chart_height', '400');
        $owmw_opt["chart_text_color"]               = owmw_get_bypass($bypass, 'chart_text_color');
        $owmw_opt["chart_night_color"]              = owmw_get_bypass($bypass, 'chart_night_color');
        $owmw_opt["chart_background_color"]         = owmw_get_bypass($bypass, 'chart_background_color');
        $owmw_opt["chart_border_color"]             = owmw_get_bypass($bypass, 'chart_border_color');
        $owmw_opt["chart_border_width"]             = owmw_getBypassDefault($bypass, 'chart_border_width', $owmw_opt["chart_border_color"] == '' ? '0' : '1');
        $owmw_opt["chart_border_style"]             = owmw_getBypassDefault($bypass, 'chart_border_style', "solid");
        $owmw_opt["chart_border_radius"]            = owmw_getBypassDefault($bypass, 'chart_border_radius', "0");
        $owmw_opt["chart_temperature_color"]        = owmw_get_bypass($bypass, 'chart_temperature_color');
        $owmw_opt["chart_feels_like_color"]         = owmw_get_bypass($bypass, 'chart_feels_like_color');
        $owmw_opt["chart_dew_point_color"]          = owmw_get_bypass($bypass, 'chart_dew_point_color');
        $owmw_opt["chart_cloudiness_color"]         = owmw_get_bypass($bypass, "chart_cloudiness_color");
        $owmw_opt["chart_rain_chance_color"]        = owmw_get_bypass($bypass, "chart_rain_chance_color");
        $owmw_opt["chart_humidity_color"]           = owmw_get_bypass($bypass, "chart_humidity_color");
        $owmw_opt["chart_pressure_color"]           = owmw_get_bypass($bypass, "chart_pressure_color");
        $owmw_opt["chart_rain_amt_color"]           = owmw_get_bypass($bypass, "chart_rain_amt_color");
        $owmw_opt["chart_snow_amt_color"]           = owmw_get_bypass($bypass, "chart_snow_amt_color");
        $owmw_opt["chart_wind_speed_color"]         = owmw_get_bypass($bypass, "chart_wind_speed_color");
        $owmw_opt["chart_wind_gust_color"]          = owmw_get_bypass($bypass, "chart_wind_gust_color");
        $owmw_opt["table_background_color"]         = owmw_get_bypass($bypass, 'table_background_color');
        $owmw_opt["table_text_color"]               = owmw_get_bypass($bypass, 'table_text_color');
        $owmw_opt["table_border_color"]             = owmw_get_bypass($bypass, 'table_border_color');
        $owmw_opt["table_border_width"]             = owmw_getBypassDefault($bypass, 'table_border_width', $owmw_opt["table_border_color"] == '' ? '0' : '1');
        $owmw_opt["table_border_style"]             = owmw_getBypassDefault($bypass, 'table_border_style', "solid");
        $owmw_opt["table_border_radius"]            = owmw_getBypassDefault($bypass, 'table_border_radius', "0");
        $owmw_opt["tabbed_btn_text_color"]          = owmw_get_bypass($bypass, 'tabbed_btn_text_color');
        $owmw_opt["tabbed_btn_background_color"]    = owmw_get_bypass($bypass, 'tabbed_btn_background_color');
        $owmw_opt["tabbed_btn_active_color"]        = owmw_get_bypass($bypass, 'tabbed_btn_active_color');
	$owmw_opt["tabbed_btn_hover_color"]         = owmw_get_bypass($bypass, 'tabbed_btn_hover_color');

	if ($owmw_opt["timemachine"]) {
		$owmw_opt["hours_forecast_no"] = 0;
		$owmw_opt["days_forecast_no"] = 0;
		$owmw_opt["alerts"] = "No";
		$owmw_opt["map"] = "No";
		$owmw_opt["moonrise_moonset"] = "No";
		$owmw_opt["sunrise_sunset"] = "No";
		$owmw_opt["uv_index"] = "No";
		$owmw_opt["gtag"] = "No";
        $owmw_opt["timestamp"] = strtotime($owmw_opt["timemachine_date"] . " " . $owmw_opt["timemachine_time"]);
	}

        /* Defaults */
        if (empty($owmw_opt["today_date_format"])) {
            $owmw_opt["today_date_format"] = 'none';
        }
        if (empty($owmw_opt["wind_icon_direction"])) {
            $owmw_opt["wind_icon_direction"] = 'to';
        }
        if (empty($owmw_opt["alerts_popup"])) {
            $owmw_opt["alerts_popup"] = 'modal';
        }
        if (empty($owmw_opt["display_length_days_names"])) {
            $owmw_opt["display_length_days_names"] = 'short';
        }
        if (empty($owmw_opt["border_width"])) {
            $owmw_opt["border_width"] =  $owmw_opt["border_color"] == '' ? '0' : '1';
        }
        if (empty($owmw_opt["border_radius"])) {
            $owmw_opt["border_radius"] = '0';
        }
        if (empty($owmw_opt["background_opacity"])) {
            $owmw_opt["background_opacity"] = "0.8";
        }
        if (empty($owmw_opt["map_opacity"])) {
            $owmw_opt["map_opacity"] = "0.8";
        }
        if (empty($owmw_opt["map_zoom"])) {
            $owmw_opt["map_zoom"] = '9';
        }
        if (empty($owmw_opt["chart_height"])) {
            $owmw_opt["chart_height"] = '400';
        }
        if (empty($owmw_opt["chart_text_color"])) {
            $owmw_opt["chart_text_color"] = '#111';
        }
        if (empty($owmw_opt["chart_night_color"])) {
            $owmw_opt["chart_night_color"] = '#c8c8c8';
        }
        if (empty($owmw_opt["chart_background_color"])) {
            $owmw_opt["chart_background_color"] = '#fff';
        }
        if (empty($owmw_opt["chart_border_color"])) {
            $owmw_opt["chart_border_color"] = '';
        }
        if (empty($owmw_opt["chart_border_width"])) {
            $owmw_opt["chart_border_width"] = $owmw_opt["chart_border_color"] == '' ? '0' : '1';
        }
        if (empty($owmw_opt["chart_border_radius"])) {
            $owmw_opt["chart_border_radius"] = '0';
        }
        if (empty($owmw_opt["chart_temperature_color"])) {
            $owmw_opt["chart_temperature_color"] = '#d5202a';
        }
        if (empty($owmw_opt["chart_feels_like_color"])) {
            $owmw_opt["chart_feels_like_color"] = '#f83';
        }
        if (empty($owmw_opt["chart_dew_point_color"])) {
            $owmw_opt["chart_dew_point_color"] = '#5b9f49';
        }
        if (empty($owmw_opt["chart_cloudiness_color"])) {
            $owmw_opt["chart_cloudiness_color"]  = '#a3a3a3';
        }
        if (empty($owmw_opt["chart_rain_chance_color"])) {
            $owmw_opt["chart_rain_chance_color"] = '#15aadc';
        }
        if (empty($owmw_opt["chart_humidity_color"])) {
            $owmw_opt["chart_humidity_color"]    = '#87c404';
        }
        if (empty($owmw_opt["chart_pressure_color"])) {
            $owmw_opt["chart_pressure_color"]    = '#1e2023';
        }
        if (empty($owmw_opt["chart_rain_amt_color"])) {
            $owmw_opt["chart_rain_amt_color"]    = '#a3a3a3';
        }
        if (empty($owmw_opt["chart_snow_amt_color"])) {
            $owmw_opt["chart_snow_amt_color"]    = '#15aadc';
        }
        if (empty($owmw_opt["chart_wind_speed_color"])) {
            $owmw_opt["chart_wind_speed_color"]  = '#a3a3a3';
        }
        if (empty($owmw_opt["chart_wind_gust_color"])) {
            $owmw_opt["chart_wind_gust_color"]   = '#15aadc';
        }
        if (empty($owmw_opt["table_background_color"])) {
            $owmw_opt["table_background_color"] = '';
        }
        if (empty($owmw_opt["table_border_color"])) {
            $owmw_opt["table_border_color"] = '';
        }
        if (empty($owmw_opt["table_border_width"])) {
            $owmw_opt["table_border_width"] = $owmw_opt["table_border_color"] == '' ? '0' : '1';
        }
        if (empty($owmw_opt["table_border_radius"])) {
            $owmw_opt["table_border_radius"] = '0';
        }
        if (empty($owmw_opt["table_text_color"])) {
            $owmw_opt["table_text_color"] = '';
        }
        if (empty($owmw_opt["tabbed_btn_text_color"])) {
            $owmw_opt["tabbed_btn_text_color"] = '#212529';
        }
        if (empty($owmw_opt["tabbed_btn_background_color"])) {
            $owmw_opt["tabbed_btn_background_color"] = '#f1f1f1';
        }
        if (empty($owmw_opt["tabbed_btn_active_color"])) {
            $owmw_opt["tabbed_btn_active_color"] = '#ccc';
        }
        if (empty($owmw_opt["tabbed_btn_hover_color"])) {
            $owmw_opt["tabbed_btn_hover_color"] = '#ddd';
        }

        $set_transient = is_multisite() ? "set_site_transient" : "set_transient";
        $get_transient = is_multisite() ? "get_site_transient" : "get_transient";


        if (owmw_get_admin_bypass('owmw_advanced_disable_modal_js') != 'yes') {
            $owmw_opt["bootstrap_version"] = owmw_get_admin_bypass('owmw_advanced_bootstrap_version') ?? '4';
        } else {
            $owmw_opt["bootstrap_version"] = '5';
        }
        $owmw_opt["bootstrap_data"]              = $owmw_opt["bootstrap_version"] == '5' ? 'bs-' : '';
        $owmw_opt["bootstrap_modal_close"]              = $owmw_opt["bootstrap_version"] == '5' ? '<button type="button" class="btn-close" data-bs-dismiss="modal"></button>' : '<button type="button" class="close" data-dismiss="modal">&times;</button>';

        if ($need_restore_blog) {
            restore_current_blog();
        }


        //JSON : Current weather
        if ($owmw_opt["id_owm"] != '') {
            $query = "id=" . $owmw_opt["id_owm"];
            $queryT = $owmw_opt["id_owm"];
        } elseif ($owmw_opt["longitude"] != '' && $owmw_opt["latitude"] != '') {
            $query = "lat=" . $owmw_opt["latitude"] . "&lon=" . $owmw_opt["longitude"];
            $queryT = $owmw_opt["latitude"] . $owmw_opt["longitude"];
        } elseif ($owmw_opt["zip"] != '') {
            $query = "zip=" . $owmw_opt["zip"];
            $queryT = $owmw_opt["zip"];
            if (!empty($owmw_opt["zip_country_code"])) {
                $query .= "," . $owmw_opt["zip_country_code"];
                $queryT .= $owmw_opt["zip_country_code"];
            }
        } elseif ($owmw_opt["city"] != '') {
            $query = "q=" . $owmw_opt["city"];
            $queryT = $owmw_opt["city"];
            if (!empty($owmw_opt["country_code"])) {
                $query .= "," . $owmw_opt["country_code"];
                $queryT .= $owmw_opt["country_code"];
            }
        } elseif ($_POST["longitude"] != '' && $_POST["latitude"] != '') {
            $query = "lat=" . floatval($_POST["latitude"]) . "&lon=" . floatval($_POST["longitude"]);
            $queryT = floatval($_POST["latitude"]) . floatval($_POST["longitude"]);

            if (floatval($_POST["latitude"]) != 0.00 && floatval($_POST["longitude"]) != 0.00) {
                $latlon = array("lat" => floatval($_POST["latitude"]), "lon" => floatval($_POST["longitude"]));
                $set_transient = is_multisite() ? "set_site_transient" : "set_transient";
                $transient_key = 'owmw_geolocation_' . owmw_get_ip_from_server() . '_' . hash("md5", $_SERVER["HTTP_USER_AGENT"] ?? '');
                $set_transient($transient_key, $latlon, MONTH_IN_SECONDS);
            }
        } elseif (($ipData = owmw_IPtoLocation())) {
            $owmw_opt["latitude"] = floatval($ipData->data->geo->latitude);
            $owmw_opt["longitude"] = floatval($ipData->data->geo->longitude);
            $query = "lat=" . $owmw_opt["latitude"] . "&lon=" . $owmw_opt["longitude"];
            $queryT = $owmw_opt["latitude"] . $owmw_opt["longitude"];
        } else {
            return;
        }


        $url = 'https://api.openweathermap.org/data/2.5/weather?' . $query . '&mode=json&lang=' . $owmw_opt["owm_language"] . '&units=' . $owmw_opt["temperature_unit"] . '&APPID=' . $owmw_opt["api_key"];
        if ($owmw_opt["disable_cache"] == 'yes') {
            $response = wp_remote_get(esc_url_raw($url), array( 'timeout' => 30));
            if (!is_wp_error($response)) {
                $owmweather_current = json_decode(wp_remote_retrieve_body($response));
            } else {
                $errorMsgs = $response->get_error_messages();
                $response = array();
                $response['weather'] = $owmw_params["weather_id"];
                $response['html'] = "<p>" . esc_html__("OWM Weather id", 'owm-weather') . " '" . esc_attr($owmw_opt["id"]) . "': " . esc_html__("OWM Error", 'owm-weather') . " " . esc_html__('Unable to retrieve weather data', 'owm-weather') . "</p>";
                foreach ($errorMsgs as $errorMsg) {
                    $response['html'] .= "<p>" . esc_html__($errorMsg) . "</p>";
                }
                wp_send_json_error($response, 400);
                return;
            }
        } else {
            $transient_key = 'owmw_cur_' . $queryT . $owmw_opt["owm_language"] . $owmw_opt["temperature_unit"][0];
            if (false === ( $owmweather_current = $get_transient($transient_key) )) {
                $response = wp_remote_get(esc_url_raw($url), array( 'timeout' => 30));
                if (!is_wp_error($response)) {
                    $owmweather_current = json_decode(wp_remote_retrieve_body($response));
                    $set_transient($transient_key, $owmweather_current, $owmw_opt["cache_time"] * MINUTE_IN_SECONDS);
                } else {
                    $errorMsgs = $response->get_error_messages();
                    $response = array();
                    $response['weather'] = $owmw_params["weather_id"];
                    $response['html'] = "<p>" . esc_html__("OWM Weather id", 'owm-weather') . " '" . esc_attr($owmw_opt["id"]) . "': " . esc_html__("OWM Error", 'owm-weather') . " " . esc_html__('Unable to retrieve weather data', 'owm-weather') . "</p>";
                    foreach ($errorMsgs as $errorMsg) {
                        $response['html'] .= "<p>" . esc_html__($errorMsg) . "</p>";
                    }
                    wp_send_json_error($response, 400);
                    return;
                }
            }
        }

        if (!empty($owmweather_current->cod) && $owmweather_current->cod != "200") {
            $response = array();
            $response['weather'] = $owmw_params["weather_id"];
            $response['html'] = "<p>" . esc_html__("OWM Weather id", 'owm-weather') . " '" . esc_attr($owmw_opt["id"]) . "': " . esc_html__("OWM Error", 'owm-weather') . " " . esc_html($owmweather_current->cod . (!empty($owmweather_current->message) ? " (" . $owmweather_current->message . ")" : "")) . "</p>";
            $response['html'] .= "<pre>" . $url . "</pre>";
            wp_send_json_error($response, $owmweather_current->cod);
            return;
        }

        owmw_sanitize_api_response($owmweather_current);


        $owmw_data = [];
        $owmw_data["name"] = $owmweather_current->name ?? null;
        $owmw_data["id"] = $owmweather_current->id ?? null;
        $owmw_data["timezone"] = $owmweather_current->timezone ?? null;

        $owmweather_hours_php = $owmw_opt["time_format"] == '12' ? 'h A' : 'H';
        if ($owmw_opt["custom_timezone"] == 'Default') {
            $utc_time_wp = get_option('gmt_offset') * 60;
        } elseif ($owmw_opt["custom_timezone"] == 'local') {
            $utc_time_wp = intval($owmw_data["timezone"]) * 60;
        } else {
            $utc_time_wp = intval($owmw_opt["custom_timezone"]) * 60;
        }

        $owmw_data["timestamp"] = $owmweather_current->dt ? $owmweather_current->dt + (60 * $utc_time_wp) : null;
        $owmw_data["last_update"] = esc_html__('Last updated: ', 'owm-weather') . date_i18n(get_option('time_format'), $owmw_data["timestamp"]);
        $owmw_data["latitude"] = $owmweather_current->coord->lat ?? null;
        $owmw_data["longitude"] = $owmweather_current->coord->lon ?? null;
        $owmw_data["condition_id"] = $owmweather_current->weather[0]->id ?? null;
        $owmw_opt["text_color"] = owmw_weather_based_text_color($owmw_opt, $owmw_data["condition_id"]);
        $owmw_data["category"] = $owmweather_current->weather[0]->main ?? null;
        $owmw_data["description"] = owmw_getConditionText($owmw_data["condition_id"]);
        $owmw_data["wind_speed_unit"] = owmw_getWindSpeedUnit($owmw_opt["temperature_unit"], $owmw_opt["wind_unit"]);
        $owmw_data["wind_speed"] = owmw_getConvertedWindSpeed($owmweather_current->wind->speed, $owmw_opt["temperature_unit"], $owmw_opt["wind_unit"]) . ' ' . $windspeedLabel[$owmw_data["wind_speed_unit"]];
        $owmw_data["wind_speed_description"] = owmw_getWindspeedText(owmw_getConvertedWindSpeed($owmweather_current->wind->speed, $owmw_opt["temperature_unit"], "mi/h"));
        $owmw_data["wind_degrees"] = $owmweather_current->wind->deg ?? null;
        $owmw_data["wind_direction"] = owmw_getWindDirection($owmweather_current->wind->deg);
        $owmw_data["wind_gust"] = isset($owmweather_current->wind->gust) ? owmw_getConvertedWindSpeed($owmweather_current->wind->gust, $owmw_opt["temperature_unit"], $owmw_opt["wind_unit"])  . ' ' . $windspeedLabel[$owmw_data["wind_speed_unit"]] : null;
        $owmw_data["temperature"] = $owmweather_current->main->temp ? ceil($owmweather_current->main->temp) : null;
        $owmw_data["feels_like"] = $owmweather_current->main->feels_like ? ceil($owmweather_current->main->feels_like) : null;
        if ($owmw_opt["temperature_unit"] == 'metric') {
            $owmw_data["temperature_unit_character"] = __("C", 'owm-weather');
            $owmw_data["temperature_unit_text"] = __('Celsius', 'owm-weather');
            $owmw_data["temperature_celsius"] = $owmw_data["temperature"];
            $owmw_data["temperature_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["temperature"]);
            $owmw_data["feels_like_celsius"] = $owmw_data["feels_like"];
            $owmw_data["feels_like_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["feels_like"]);
        } else {
            $owmw_data["temperature_unit_character"] = __("F", 'owm-weather');
            $owmw_data["temperature_unit_text"] = __('Fahrenheit', 'owm-weather');
            $owmw_data["temperature_fahrenheit"] = $owmw_data["temperature"];
            $owmw_data["temperature_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["temperature"]);
            $owmw_data["feels_like_fahrenheit"] = $owmw_data["feels_like"];
            $owmw_data["feels_like_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["feels_like"]);
        }
        $owmw_data["humidity"] = $owmweather_current->main->humidity ? $owmweather_current->main->humidity . '%' : null;
        $owmw_data["pressure_unit"] = owmw_getPressureUnit($owmw_opt["temperature_unit"], $owmw_opt["pressure_unit"]);
        $owmw_data["pressure"] = owmw_converthPa($owmw_opt["temperature_unit"], $owmweather_current->main->pressure, $pressureLabel[$owmw_data["pressure_unit"]]);
        $owmw_data["cloudiness"] = $owmweather_current->clouds->all ? $owmweather_current->clouds->all . '%' : "0%";
        $owmw_data["precipitation_unit"] = $owmw_opt["temperature_unit"] == 'imperial' ? esc_html__('in', 'owm-weather') : esc_html__('mm', 'owm-weather');
        $owmw_data["rain_1h"] = owmw_getConvertedPrecipitation($owmw_opt["temperature_unit"], $owmweather_current->rain->{"1h"} ?? '0.00') . ' ' . $owmw_data["precipitation_unit"];
        $owmw_data["rain_3h"] = owmw_getConvertedPrecipitation($owmw_opt["temperature_unit"], $owmweather_current->rain->{"3h"} ?? '0.00') . ' ' . $owmw_data["precipitation_unit"];
        $owmw_data["snow_1h"] = owmw_getConvertedPrecipitation($owmw_opt["temperature_unit"], $owmweather_current->snow->{"1h"} ?? '0.00') . ' ' . $owmw_data["precipitation_unit"];
        $owmw_data["snow_3h"] = owmw_getConvertedPrecipitation($owmw_opt["temperature_unit"], $owmweather_current->snow->{"3h"} ?? '0.00') . ' ' . $owmw_data["precipitation_unit"];
        $owmw_data["precipitation_1h"] = number_format(floatval($owmw_data["rain_1h"] ?? 0.00) + floatval($owmw_data["snow_1h"] ?? 0.00), 2) . ' ' . $owmw_data["precipitation_unit"];
        $owmw_data["precipitation_3h"] = number_format(floatval($owmw_data["rain_3h"] ?? 0.00) + floatval($owmw_data["snow_3h"] ?? 0.00), 2) . ' ' . $owmw_data["precipitation_unit"];
        ;
        $owmw_data["visibility"] = owmw_getConvertedDistance($owmw_opt["temperature_unit"], $owmweather_current->visibility);
        $owmw_data["owm_link"] = 'https://openweathermap.org/city/' . ($owmweather_current->id ?? "");
        $owmw_data["timestamp_sunrise"] = $owmweather_current->sys->sunrise ? $owmweather_current->sys->sunrise + (60 * $utc_time_wp) : null;
        $owmw_data["timestamp_sunset"] = $owmweather_current->sys->sunset ? $owmweather_current->sys->sunset + (60 * $utc_time_wp) : null;
        $owmw_data["sunrise"] = (string)date_i18n(get_option("time_format"), $owmw_data["timestamp_sunrise"]);
        $owmw_data["sunset"] = (string)date_i18n(get_option("time_format"), $owmw_data["timestamp_sunset"]);

        if ($owmw_opt["today_date_format"] == 'date') {
            $owmw_data["today_day"] =  date_i18n(get_option('date_format'));
        } elseif ($owmw_opt["today_date_format"] == 'datetime') {
            $owmw_data["today_day"] =  date_i18n(get_option('date_format') . ' ' . get_option('time_format'));
        } elseif ($owmw_opt["today_date_format"] == 'day') {
            switch (strftime("%w", $owmw_data["timestamp"])) {
                case "0":
                    $owmw_data["today_day"]      = esc_html__('Sunday', 'owm-weather');
                    break;
                case "1":
                    $owmw_data["today_day"]      = esc_html__('Monday', 'owm-weather');
                    break;
                case "2":
                    $owmw_data["today_day"]      = esc_html__('Tuesday', 'owm-weather');
                    break;
                case "3":
                    $owmw_data["today_day"]      = esc_html__('Wednesday', 'owm-weather');
                    break;
                case "4":
                    $owmw_data["today_day"]      = esc_html__('Thursday', 'owm-weather');
                    break;
                case "5":
                    $owmw_data["today_day"]      = esc_html__('Friday', 'owm-weather');
                    break;
                case "6":
                    $owmw_data["today_day"]      = esc_html__('Saturday', 'owm-weather');
                    break;
            }
        } else {
            $owmw_data["today_day"] = '';
	}

    //JSON : Onecall historical weather (relies on lat and lon from current weather call)
	if ($owmw_opt["timemachine"] == "yes") {
        $url = 'https://api.openweathermap.org/data/3.0/onecall/timemachine?lon=' . $owmw_data["longitude"] . "&lat=" . $owmw_data["latitude"] . '&dt=' . $owmw_opt["timestamp"] . '&lang=' . $owmw_opt["owm_language"] . '&units=' . $owmw_opt["temperature_unit"] . '&APPID=' . $owmw_opt["api_key"];
        if ($owmw_opt["disable_cache"] == 'yes') {
            $response = wp_remote_get(esc_url_raw($url), array( 'timeout' => 30));
            if (!is_wp_error($response)) {
                $owmweather_timemachine = json_decode(wp_remote_retrieve_body($response));
            } else {
                $errorMsgs = $response->get_error_messages();
                $response = array();
                $response['weather'] = $owmw_params["weather_id"];
                $response['html'] = "<p>" . esc_html__("OWM Weather id", 'owm-weather') . " '" . esc_attr($owmw_opt["id"]) . "': " . esc_html__("OWM Error", 'owm-weather') . " " . esc_html__('Unable to retrieve weather data', 'owm-weather') . "</p>";
                foreach ($errorMsgs as $errorMsg) {
                    $response['html'] .= "<p>" . esc_html__($errorMsg) . "</p>";
                }
                wp_send_json_error($response, 400);
                return;
            }
        } else {
            $transient_key = 'owmw_tm_' . $queryT . $owmw_opt["owm_language"] . $owmw_opt["temperature_unit"][0] . $owmw_opt["timestamp"];
            if (false === ( $owmweather_timemachine = $get_transient($transient_key) )) {
                $response = wp_remote_get(esc_url_raw($url), array( 'timeout' => 30));
                if (!is_wp_error($response)) {
                    $owmweather_timemachine = json_decode(wp_remote_retrieve_body($response));
                    $set_transient($transient_key, $owmweather_timemachine, 1 * MONTH_IN_SECONDS);
                } else {
                    $errorMsgs = $response->get_error_messages();
                    $response = array();
                    $response['weather'] = $owmw_params["weather_id"];
                    $response['html'] = "<p>" . esc_html__("OWM Weather id", 'owm-weather') . " '" . esc_attr($owmw_opt["id"]) . "': " . esc_html__("OWM Error", 'owm-weather') . " " . esc_html__('Unable to retrieve weather data', 'owm-weather') . "</p>";
                    foreach ($errorMsgs as $errorMsg) {
                        $response['html'] .= "<p>" . esc_html__($errorMsg) . "</p>";
                    }
                    wp_send_json_error($response, 400);
                    return;
                }
            }
        }

        if (!empty($owmweather_timemachine->cod) && $owmweather_timemachine->cod != "200") {
            $response = array();
            $response['weather'] = $owmw_params["weather_id"];
            $response['html'] = "<p>" . esc_html__("OWM Weather id", 'owm-weather') . " '" . esc_attr($owmw_opt["id"]) . "': " . esc_html__("OWM Error", 'owm-weather') . " " . esc_html($owmweather_timemachine->cod . (!empty($owmweather_timemachine->message) ? " (" . $owmweather_timemachine->message . ")" : "")) . "</p>";
            $response['html'] .= "<pre>" . $url . "</pre>";
            wp_send_json_error($response, $owmweather_timemachine->cod);
            return;
        }

	    owmw_sanitize_api_response($owmweather_timemachine);

        $owmw_data["timezone_offset"] = $owmweather_timemachine->timezone_offset ? $owmweather_timemachine->timezone_offset : 0;
        $owmw_data["timezone"] = $owmweather_timemachine->timezone ? $owmweather_timemachine->timezone : 0;
        $owmw_data["timestamp"] = $owmweather_timemachine->data[0]->dt ? $owmweather_timemachine->data[0]->dt + $owmw_data["timezone_offset"] : null;
        if ($owmw_opt["custom_timezone"] == 'Default') {
            $utc_time_wp = get_option('gmt_offset') * 60;
        } elseif ($owmw_opt["custom_timezone"] == 'local') {
            $utc_time_wp = intval($owmw_data["timezone"]) * 60;
        } else {
            $utc_time_wp = intval($owmw_opt["custom_timezone"]) * 60;
        }

        $owmw_data["condition_id"] = $owmweather_timemachine->data[0]->weather[0]->id ?? null;
        $owmw_opt["text_color"] = owmw_weather_based_text_color($owmw_opt, $owmw_data["condition_id"]);
        $owmw_data["category"] = $owmweather_timemachine->data[0]->weather[0]->main ?? null;
        $owmw_data["description"] = owmw_getConditionText($owmw_data["condition_id"]);
        $owmw_data["wind_speed_unit"] = owmw_getWindSpeedUnit($owmw_opt["temperature_unit"], $owmw_opt["wind_unit"]);
        $owmw_data["wind_speed"] = owmw_getConvertedWindSpeed($owmweather_timemachine->data[0]->wind_speed, $owmw_opt["temperature_unit"], $owmw_opt["wind_unit"]) . ' ' . $windspeedLabel[$owmw_data["wind_speed_unit"]];
        $owmw_data["wind_speed_description"] = owmw_getWindspeedText(owmw_getConvertedWindSpeed($owmweather_timemachine->data[0]->wind_speed, $owmw_opt["temperature_unit"], "mi/h"));
        $owmw_data["wind_degrees"] = $owmweather_timemachine->data[0]->wind_deg ?? null;
        $owmw_data["wind_direction"] = owmw_getWindDirection($owmweather_timemachine->data[0]->wind_deg);
        $owmw_data["wind_gust"] = isset($owmweather_timemachine->data[0]->wind_gust) ? owmw_getConvertedWindSpeed($owmweather_timemachine->data[0]->wind_gust, $owmw_opt["temperature_unit"], $owmw_opt["wind_unit"])  . ' ' . $windspeedLabel[$owmw_data["wind_speed_unit"]] : null;
        $owmw_data["temperature"] = $owmweather_timemachine->data[0]->temp ? ceil($owmweather_timemachine->data[0]->temp) : null;
	    $owmw_data["feels_like"] = $owmweather_timemachine->data[0]->feels_like ? ceil($owmweather_timemachine->data[0]->feels_like) : null;
	    $owmw_data["dew_point"] = $owmweather_timemachine->data[0]->dew_point ? ceil($owmweather_timemachine->data[0]->dew_point) : null;
        if ($owmw_opt["temperature_unit"] == 'metric') {
            $owmw_data["temperature_unit_character"] = __("C", 'owm-weather');
            $owmw_data["temperature_unit_text"] = __('Celsius', 'owm-weather');
            $owmw_data["temperature_celsius"] = $owmw_data["temperature"];
            $owmw_data["temperature_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["temperature"]);
            $owmw_data["feels_like_celsius"] = $owmw_data["feels_like"];
            $owmw_data["feels_like_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["feels_like"]);
            $owmw_data["dew_point_celsius"] = $owmw_data["dew_point"];
            $owmw_data["dew_point_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["dew_point"]);
        } else {
            $owmw_data["temperature_unit_character"] = __("F", 'owm-weather');
            $owmw_data["temperature_unit_text"] = __('Fahrenheit', 'owm-weather');
            $owmw_data["temperature_fahrenheit"] = $owmw_data["temperature"];
            $owmw_data["temperature_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["temperature"]);
            $owmw_data["feels_like_fahrenheit"] = $owmw_data["feels_like"];
            $owmw_data["feels_like_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["feels_like"]);
            $owmw_data["dew_point_fahrenheit"] = $owmw_data["dew_point"];
            $owmw_data["dew_point_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["dew_point"]);
        }
        $owmw_data["humidity"] = $owmweather_timemachine->data[0]->humidity ? $owmweather_timemachine->data[0]->humidity . '%' : null;
        $owmw_data["pressure_unit"] = owmw_getPressureUnit($owmw_opt["temperature_unit"], $owmw_opt["pressure_unit"]);
        $owmw_data["pressure"] = owmw_converthPa($owmw_opt["temperature_unit"], $owmweather_timemachine->data[0]->pressure, $pressureLabel[$owmw_data["pressure_unit"]]);
        $owmw_data["cloudiness"] = $owmweather_timemachine->data[0]->clouds ? $owmweather_timemachine->data[0]->clouds . '%' : "0%";
        ;
        $owmw_data["visibility"] = !empty($owmweather_timemachine->data[0]->visibility) ? owmw_getConvertedDistance($owmw_opt["temperature_unit"], $owmweather_timemachine->data[0]->visibility) : 0;
        $owmw_data["timestamp_sunrise"] = $owmweather_timemachine->data[0]->sunrise ? $owmweather_timemachine->data[0]->sunrise + (60 * $utc_time_wp) : null;
        $owmw_data["timestamp_sunset"] = $owmweather_timemachine->data[0]->sunset ? $owmweather_timemachine->data[0]->sunset + (60 * $utc_time_wp) : null;
        $owmw_data["sunrise"] = (string)date_i18n(get_option("time_format"), $owmw_data["timestamp_sunrise"]);
        $owmw_data["sunset"] = (string)date_i18n(get_option("time_format"), $owmw_data["timestamp_sunset"]);

        if ($owmw_opt["today_date_format"] == 'date') {
            $owmw_data["today_day"] =  date_i18n(get_option('date_format'), $owmw_data["timestamp"]);
        } elseif ($owmw_opt["today_date_format"] == 'datetime') {
            $owmw_data["today_day"] =  date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $owmw_data["timestamp"]);
        } elseif ($owmw_opt["today_date_format"] == 'day') {
            switch (strftime("%w", $owmw_data["timestamp"])) {
                case "0":
                    $owmw_data["today_day"]      = esc_html__('Sunday', 'owm-weather');
                    break;
                case "1":
                    $owmw_data["today_day"]      = esc_html__('Monday', 'owm-weather');
                    break;
                case "2":
                    $owmw_data["today_day"]      = esc_html__('Tuesday', 'owm-weather');
                    break;
                case "3":
                    $owmw_data["today_day"]      = esc_html__('Wednesday', 'owm-weather');
                    break;
                case "4":
                    $owmw_data["today_day"]      = esc_html__('Thursday', 'owm-weather');
                    break;
                case "5":
                    $owmw_data["today_day"]      = esc_html__('Friday', 'owm-weather');
                    break;
                case "6":
                    $owmw_data["today_day"]      = esc_html__('Saturday', 'owm-weather');
                    break;
            }
        } else {
            $owmw_data["today_day"] = '';
	    }
	}

    //JSON : Onecall forecast weather (relies on lat and lon from current weather call)
    $url = "https://api.openweathermap.org/data/2.5/onecall?lon=" . $owmw_data["longitude"] . "&lat=" . $owmw_data["latitude"] . "&mode=json&exclude=minutely&lang=" . $owmw_opt["owm_language"] . "&units=" . $owmw_opt["temperature_unit"] . "&APPID=" . $owmw_opt["api_key"];
    if ($owmw_opt["timemachine"] != "yes" && ($owmw_opt["hours_forecast_no"] > 0 || $owmw_opt["days_forecast_no"] > 0 || $owmw_opt["alerts"] == 'yes' || $owmw_opt["moonrise_moonset"] == "yes" || $owmw_opt["dew_point"] == "yes" || $owmw_opt["uv_index"] == "yes" || $owmw_opt["gtag"] == "yes")) {
            if ($owmw_opt["disable_cache"] == 'yes') {
                $response = wp_remote_get(esc_url_raw($url), array( 'timeout' => 30));
                if (!is_wp_error($response)) {
                    $owmweather = json_decode(wp_remote_retrieve_body($response));
                } else {
                    $errorMsgs = $response->get_error_messages();
                    $response = array();
                    $response['weather'] = $owmw_params["weather_id"];
                    $response['html'] = "<p>" . esc_html__("OWM Weather id", 'owm-weather') . " '" . esc_attr($owmw_opt["id"]) . "': " . esc_html__("OWM Error", 'owm-weather') . " " . esc_html__('Unable to retrieve weather data', 'owm-weather') . "</p>";
                    foreach ($errorMsgs as $errorMsg) {
                        $response['html'] .= "<p>" . esc_html__($errorMsg) . "</p>";
                    }
                    wp_send_json_error($response, 400);
                    return;
                }
            } else {
                $transient_key = 'owmw_' . $owmw_data["longitude"] . $owmw_data["latitude"] . $owmw_opt["temperature_unit"][0] . $owmw_opt["owm_language"];
                if (false === ( $owmweather = $get_transient($transient_key))) {
                    $response = wp_remote_get(esc_url_raw($url), array( 'timeout' => 30));
                    if (!is_wp_error($response)) {
                        $owmweather = json_decode(wp_remote_retrieve_body($response));
                        $set_transient($transient_key, $owmweather, $owmw_opt["cache_time"] * MINUTE_IN_SECONDS);
                    } else {
                        $errorMsgs = $response->get_error_messages();
                        $response = array();
                        $response['weather'] = $owmw_params["weather_id"];
                        $response['html'] = "<p>" . esc_html__("OWM Weather id", 'owm-weather') . " '" . esc_attr($owmw_opt["id"]) . "': " . esc_html__("OWM Error", 'owm-weather') . " " . esc_html__('Unable to retrieve weather data', 'owm-weather') . "</p>";
                        foreach ($errorMsgs as $errorMsg) {
                            $response['html'] .= "<p>" . esc_html__($errorMsg) . "</p>";
                        }
                        wp_send_json_error($response, 400);
                        return;
                    }
                }
            }
        }

        if (!empty($owmweather->cod) && $owmweather->cod != "200") {
            $response = array();
            $response['weather'] = $owmw_params["weather_id"];
            $response['html'] = "<p>" . esc_html__("OWM Weather id", 'owm-weather') . " '" . $owmw_opt["id"] . "': " . esc_html__("OWM Error", 'owm-weather') . " " . $owmweather->cod . (!empty($owmweather->message) ? " (" . $owmweather->message . ")" : "") . "</p>";
            $response['html'] .= "<pre>" . $url . "</pre>";
            wp_send_json_success($response);
            return;
        }

        owmw_sanitize_api_response($owmweather, array("description"));

        if ($owmw_opt["timemachine"] != "yes" && ($owmw_opt["dew_point"] == "yes" || $owmw_opt["uv_index"] == "yes" || $owmw_opt["gtag"] == "yes")) {
            $owmw_data["uv_index"] = $owmweather->current->uvi ?? null;
            $owmw_data["dew_point"] = $owmweather->current->dew_point ? ceil($owmweather->current->dew_point) : null;
            if ($owmw_opt["temperature_unit"] == 'metric') {
                $owmw_data["dew_point_celsius"] = $owmw_data["dew_point"];
                $owmw_data["dew_point_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["dew_point"]);
            } else {
                $owmw_data["dew_point_fahrenheit"] = $owmw_data["dew_point"];
                $owmw_data["dew_point_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["dew_point"]);
            }
        }

        //Days loop
        if ($owmw_opt["days_forecast_no"] > 0 || $owmw_opt["hours_forecast_no"] > 0 || $owmw_opt["moonrise_moonset"] == "yes") {
            foreach ($owmweather->daily as $i => $value) {
                switch (strftime('%w', $owmweather->daily[$i]->dt + (60 * $utc_time_wp))) {
                    case "0":
                        $owmw_data["daily"][$i]["day"] = $owmw_opt["display_length_days_names"] == 'short' ? esc_html__('Sun', 'owm-weather') : esc_html__('Sunday', 'owm-weather');
                        break;
                    case "1":
                        $owmw_data["daily"][$i]["day"] = $owmw_opt["display_length_days_names"] == 'short' ? esc_html__('Mon', 'owm-weather') : esc_html__('Monday', 'owm-weather');
                        break;
                    case "2":
                        $owmw_data["daily"][$i]["day"] = $owmw_opt["display_length_days_names"] == 'short' ? esc_html__('Tue', 'owm-weather') : esc_html__('Tuesday', 'owm-weather');
                        break;
                    case "3":
                        $owmw_data["daily"][$i]["day"] = $owmw_opt["display_length_days_names"] == 'short' ? esc_html__('Wed', 'owm-weather') : esc_html__('Wednesday', 'owm-weather');
                        break;
                    case "4":
                        $owmw_data["daily"][$i]["day"] = $owmw_opt["display_length_days_names"] == 'short' ? esc_html__('Thu', 'owm-weather') : esc_html__('Thursday', 'owm-weather');
                        break;
                    case "5":
                        $owmw_data["daily"][$i]["day"] = $owmw_opt["display_length_days_names"] == 'short' ? esc_html__('Fri', 'owm-weather') : esc_html__('Friday', 'owm-weather');
                        break;
                    case "6":
                        $owmw_data["daily"][$i]["day"] = $owmw_opt["display_length_days_names"] == 'short' ? esc_html__('Sat', 'owm-weather') : esc_html__('Saturday', 'owm-weather');
                        break;
                }

                $owmw_data["daily"][$i]["timestamp"] = $value->dt ? $value->dt + (60 * $utc_time_wp) : null;
                $owmw_data["daily"][$i]["timestamp_sunrise"] = $value->sunrise ? $value->sunrise + (60 * $utc_time_wp) : null;
                $owmw_data["daily"][$i]["timestamp_sunset"] = $value->sunset ? $value->sunset + (60 * $utc_time_wp) : null;
                $owmw_data["daily"][$i]["sunrise"] = (string)date_i18n(get_option("time_format"), $owmw_data["daily"][$i]["timestamp_sunrise"]);
                $owmw_data["daily"][$i]["sunset"] = (string)date_i18n(get_option("time_format"), $owmw_data["daily"][$i]["timestamp_sunset"]);
                $owmw_data["daily"][$i]["timestamp_moonrise"] = $value->moonrise ? $value->moonrise + (60 * $utc_time_wp) : null;
                $owmw_data["daily"][$i]["timestamp_moonset"] = $value->moonset ? $value->moonset + (60 * $utc_time_wp) : null;
                $owmw_data["daily"][$i]["moonrise"] = (string)date_i18n(get_option("time_format"), $owmw_data["daily"][$i]["timestamp_moonrise"]);
                $owmw_data["daily"][$i]["moonset"] = (string)date_i18n(get_option("time_format"), $owmw_data["daily"][$i]["timestamp_moonset"]);
                $owmw_data["daily"][$i]["moon_phase"] = $value->moon_phase ?? null;
                $owmw_data["daily"][$i]["condition_id"] = $value->weather[0]->id ?? null;
                $owmw_data["daily"][$i]["category"] = $value->weather[0]->main ?? null;
                $owmw_data["daily"][$i]["description"] = owmw_getConditionText($owmw_data["daily"][$i]["condition_id"]);
                $owmw_data["daily"][$i]["wind_speed"] = owmw_getConvertedWindSpeed($value->wind_speed, $owmw_opt["temperature_unit"], $owmw_opt["wind_unit"]) . ' ' . $windspeedLabel[$owmw_data["wind_speed_unit"]];
                $owmw_data["daily"][$i]["wind_speed_description"] = owmw_getWindspeedText(owmw_getConvertedWindSpeed($value->wind_speed, $owmw_opt["temperature_unit"], "mi/h"));
                $owmw_data["daily"][$i]["wind_degrees"] = $value->wind_deg ?? null;
                $owmw_data["daily"][$i]["wind_direction"] = owmw_getWindDirection($value->wind_deg);
                $owmw_data["daily"][$i]["wind_gust"] = isset($value->wind_gust) ? owmw_getConvertedWindSpeed($value->wind_gust, $owmw_opt["temperature_unit"], $owmw_opt["wind_unit"])  . ' ' . $windspeedLabel[$owmw_data["wind_speed_unit"]] : null;
                $owmw_data["daily"][$i]["temperature_morning"] = $value->temp->morn ? ceil($value->temp->morn) : null;
                $owmw_data["daily"][$i]["temperature_day"] = $value->temp->day ? ceil($value->temp->day) : null;
                $owmw_data["daily"][$i]["temperature_evening"] = $value->temp->eve ? ceil($value->temp->eve) : null;
                $owmw_data["daily"][$i]["temperature_night"] = $value->temp->eve ? ceil($value->temp->night) : null;
                $owmw_data["daily"][$i]["temperature_minimum"] = $value->temp->min ? ceil($value->temp->min) : null;
                $owmw_data["daily"][$i]["temperature_maximum"] = $value->temp->max ? ceil($value->temp->max) : null;
                $owmw_data["daily"][$i]["feels_like_morning"] = $value->feels_like->morn ? ceil($value->feels_like->morn) : null;
                $owmw_data["daily"][$i]["feels_like_day"] = $value->feels_like->day ? ceil($value->feels_like->day) : null;
                $owmw_data["daily"][$i]["feels_like_evening"] = $value->feels_like->eve ? ceil($value->feels_like->eve) : null;
                $owmw_data["daily"][$i]["feels_like_night"] = $value->feels_like->night ? ceil($value->feels_like->night) : null;
                $owmw_data["daily"][$i]["dew_point"] = $value->dew_point ? ceil($value->dew_point) : null;
                if ($owmw_opt["temperature_unit"] == 'metric') {
                    $owmw_data["daily"][$i]["temperature_morning_celsius"] = $owmw_data["daily"][$i]["temperature_morning"];
                    $owmw_data["daily"][$i]["temperature_morning_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["daily"][$i]["temperature_morning"]);
                    $owmw_data["daily"][$i]["temperature_day_celsius"] = $owmw_data["daily"][$i]["temperature_day"];
                    $owmw_data["daily"][$i]["temperature_day_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["daily"][$i]["temperature_day"]);
                    $owmw_data["daily"][$i]["temperature_evening_celsius"] = $owmw_data["daily"][$i]["temperature_evening"];
                    $owmw_data["daily"][$i]["temperature_evening_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["daily"][$i]["temperature_evening"]);
                    $owmw_data["daily"][$i]["temperature_evening_celsius"] = $owmw_data["daily"][$i]["temperature_evening"];
                    $owmw_data["daily"][$i]["temperature_evening_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["daily"][$i]["temperature_evening"]);
                    $owmw_data["daily"][$i]["temperature_night_celsius"] = $owmw_data["daily"][$i]["temperature_night"];
                    $owmw_data["daily"][$i]["temperature_night_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["daily"][$i]["temperature_night"]);
                    $owmw_data["daily"][$i]["temperature_minimum_celsius"] = $owmw_data["daily"][$i]["temperature_minimum"];
                    $owmw_data["daily"][$i]["temperature_minimum_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["daily"][$i]["temperature_minimum"]);
                    $owmw_data["daily"][$i]["temperature_maximum_celsius"] = $owmw_data["daily"][$i]["temperature_maximum"];
                    $owmw_data["daily"][$i]["temperature_maximum_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["daily"][$i]["temperature_maximum"]);
                    $owmw_data["daily"][$i]["feels_like_morning_celsius"] = $owmw_data["daily"][$i]["feels_like_morning"];
                    $owmw_data["daily"][$i]["feels_like_morning_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["daily"][$i]["feels_like_morning"]);
                    $owmw_data["daily"][$i]["feels_like_day_celsius"] = $owmw_data["daily"][$i]["feels_like_day"];
                    $owmw_data["daily"][$i]["feels_like_day_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["daily"][$i]["feels_like_day"]);
                    $owmw_data["daily"][$i]["feels_like_evening_celsius"] = $owmw_data["daily"][$i]["feels_like_evening"];
                    $owmw_data["daily"][$i]["feels_like_evening_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["daily"][$i]["feels_like_evening"]);
                    $owmw_data["daily"][$i]["feels_like_night_celsius"] = $owmw_data["daily"][$i]["feels_like_night"];
                    $owmw_data["daily"][$i]["feels_like_night_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["daily"][$i]["feels_like_night"]);
                    $owmw_data["daily"][$i]["dew_point_celsius"] = $owmw_data["daily"][$i]["dew_point"];
                    $owmw_data["daily"][$i]["dew_point_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["daily"][$i]["dew_point_celsius"]);
                } else {
                    $owmw_data["daily"][$i]["temperature_morning_fahrenheit"] = $owmw_data["daily"][$i]["temperature_morning"];
                    $owmw_data["daily"][$i]["temperature_morning_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["daily"][$i]["temperature_morning"]);
                    $owmw_data["daily"][$i]["temperature_day_fahrenheit"] = $owmw_data["daily"][$i]["temperature_day"];
                    $owmw_data["daily"][$i]["temperature_day_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["daily"][$i]["temperature_day"]);
                    $owmw_data["daily"][$i]["temperature_evening_fahrenheit"] = $owmw_data["daily"][$i]["temperature_evening"];
                    $owmw_data["daily"][$i]["temperature_evening_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["daily"][$i]["temperature_evening"]);
                    $owmw_data["daily"][$i]["temperature_night_fahrenheit"] = $owmw_data["daily"][$i]["temperature_night"];
                    $owmw_data["daily"][$i]["temperature_night_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["daily"][$i]["temperature_night"]);
                    $owmw_data["daily"][$i]["temperature_minimum_fahrenheit"] = $owmw_data["daily"][$i]["temperature_minimum"];
                    $owmw_data["daily"][$i]["temperature_minimum_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["daily"][$i]["temperature_minimum"]);
                    $owmw_data["daily"][$i]["temperature_maximum_fahrenheit"] = $owmw_data["daily"][$i]["temperature_maximum"];
                    $owmw_data["daily"][$i]["temperature_maximum_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["daily"][$i]["temperature_maximum"]);
                    $owmw_data["daily"][$i]["feels_like_morning_fahrenheit"] = $owmw_data["daily"][$i]["feels_like_morning"];
                    $owmw_data["daily"][$i]["feels_like_morning_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["daily"][$i]["feels_like_morning"]);
                    $owmw_data["daily"][$i]["feels_like_day_fahrenheit"] = $owmw_data["daily"][$i]["feels_like_day"];
                    $owmw_data["daily"][$i]["feels_like_day_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["daily"][$i]["feels_like_day"]);
                    $owmw_data["daily"][$i]["feels_like_evening_fahrenheit"] = $owmw_data["daily"][$i]["feels_like_evening"];
                    $owmw_data["daily"][$i]["feels_like_evening_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["daily"][$i]["feels_like_evening"]);
                    $owmw_data["daily"][$i]["feels_like_night_fahrenheit"] = $owmw_data["daily"][$i]["feels_like_night"];
                    $owmw_data["daily"][$i]["feels_like_night_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["daily"][$i]["feels_like_night"]);
                    $owmw_data["daily"][$i]["dew_point_fahrenheit"] = $owmw_data["daily"][$i]["dew_point"];
                    $owmw_data["daily"][$i]["dew_point_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["daily"][$i]["dew_point"]);
                }
                $owmw_data["daily"][$i]["humidity"] = $value->humidity ? $value->humidity . '%' : null;
                $owmw_data["daily"][$i]["pressure"] = owmw_converthPa($owmw_opt["temperature_unit"], $value->pressure, $pressureLabel[$owmw_data["pressure_unit"]]);
                $owmw_data["daily"][$i]["cloudiness"] = $value->clouds ? $value->clouds . '%' : '0%';
                $owmw_data["daily"][$i]["uv_index"] = $value->uvi ?? null;
                $owmw_data["daily"][$i]["rain_chance"] = $value->pop ? number_format($value->pop * 100, 0) . '%' : '0%';
                $owmw_data["daily"][$i]["rain"] = owmw_getConvertedPrecipitation($owmw_opt["temperature_unit"], $value->rain ?? '0.00') . ' ' . $owmw_data["precipitation_unit"];
                $owmw_data["daily"][$i]["snow"] = owmw_getConvertedPrecipitation($owmw_opt["temperature_unit"], $value->snow ?? '0.00') . ' ' . $owmw_data["precipitation_unit"];
                $owmw_data["daily"][$i]["precipitation"] = number_format(floatval($owmw_data["daily"][$i]["rain"] ?? 0.00) + floatval($owmw_data["daily"][$i]["snow"] ?? 0.00), 2)  . ' ' . $owmw_data["precipitation_unit"];

                $date_index = date('Ymd', $owmw_data["daily"][$i]["timestamp"]);
                $owmw_data[$date_index]["sunrise"] = $owmw_data["daily"][$i]["timestamp_sunrise"];
                $owmw_data[$date_index]["sunset"] = $owmw_data["daily"][$i]["timestamp_sunset"];
            }
        }//End days loop

        //Hours loop (must be after days loop)
        if ($owmw_opt["hours_forecast_no"] > 0) {
            $cnt = 0;
            foreach ($owmweather->hourly as $i => $value) {
                if ($value->dt > (time() - 3600)) {
                    $owmw_data["hourly"][$cnt]["timestamp"] = $value->dt ? $value->dt + (60 * $utc_time_wp) : null;
                    $owmw_data["hourly"][$cnt]["time"] = (string)date($owmweather_hours_php, $value->dt + (60 * $utc_time_wp));
                    $owmw_data["hourly"][$cnt]["condition_id"] = $value->weather[0]->id ?? null;
                    $owmw_data["hourly"][$cnt]["category"] = $value->weather[0]->main ?? null;
                    $owmw_data["hourly"][$cnt]["description"] = owmw_getConditionText($owmw_data["hourly"][$cnt]["condition_id"]);
                    $owmw_data["hourly"][$cnt]["wind_speed"] = owmw_getConvertedWindSpeed($value->wind_speed, $owmw_opt["temperature_unit"], $owmw_opt["wind_unit"]) . ' ' . $windspeedLabel[$owmw_data["wind_speed_unit"]];
                    $owmw_data["hourly"][$cnt]["wind_speed_description"] = owmw_getWindspeedText(owmw_getConvertedWindSpeed($value->wind_speed, $owmw_opt["temperature_unit"], "mi/h"));
                    $owmw_data["hourly"][$cnt]["wind_degrees"] = $value->wind_deg ?? null;
                    $owmw_data["hourly"][$cnt]["wind_direction"] = owmw_getWindDirection($value->wind_deg);
                    $owmw_data["hourly"][$cnt]["wind_gust"] = isset($value->wind_gust) ? owmw_getConvertedWindSpeed($value->wind_gust, $owmw_opt["temperature_unit"], $owmw_opt["wind_unit"]) . ' ' . $windspeedLabel[$owmw_data["wind_speed_unit"]] : null;
                    $owmw_data["hourly"][$cnt]["temperature"] = $value->temp ? ceil($value->temp) : null;
                    $owmw_data["hourly"][$cnt]["feels_like"] = $value->feels_like ? ceil($value->feels_like) : null;
                    if ($owmw_opt["temperature_unit"] == 'metric') {
                        $owmw_data["hourly"][$cnt]["temperature_celsius"] = $owmw_data["hourly"][$cnt]["temperature"];
                        $owmw_data["hourly"][$cnt]["temperature_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["hourly"][$cnt]["temperature"]);
                        $owmw_data["hourly"][$cnt]["feels_like_celsius"] = $owmw_data["hourly"][$cnt]["feels_like"];
                        $owmw_data["hourly"][$cnt]["feels_like_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["hourly"][$cnt]["feels_like"]);
                    } else {
                        $owmw_data["hourly"][$cnt]["temperature_fahrenheit"] = $owmw_data["hourly"][$cnt]["temperature"];
                        $owmw_data["hourly"][$cnt]["temperature_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["hourly"][$cnt]["temperature"]);
                        $owmw_data["hourly"][$cnt]["feels_like_fahrenheit"] = $owmw_data["hourly"][$cnt]["feels_like"];
                        $owmw_data["hourly"][$cnt]["feels_like_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["hourly"][$cnt]["feels_like"]);
                    }
                    $owmw_data["hourly"][$cnt]["humidity"] = $value->humidity ? $value->humidity . '%' : null;
                    $owmw_data["hourly"][$cnt]["pressure"] = owmw_converthPa($owmw_opt["temperature_unit"], $value->pressure, $pressureLabel[$owmw_data["pressure_unit"]]);
                    $owmw_data["hourly"][$cnt]["dew_point"] = $value->dew_point ? ceil($value->dew_point) : null;
                    $owmw_data["hourly"][$cnt]["cloudiness"] = $value->clouds ? $value->clouds . '%' : "0%";
                    $owmw_data["hourly"][$cnt]["uv_index"] = $value->uvi ?? null;
                    $owmw_data["hourly"][$cnt]["rain_chance"] = $value->pop ? number_format($value->pop * 100, 0) . '%' : '0%';
                    $owmw_data["hourly"][$cnt]["visibility"] = owmw_getConvertedDistance($owmw_opt["temperature_unit"], $value->visibility);
                    $owmw_data["hourly"][$cnt]["rain"] = owmw_getConvertedPrecipitation($owmw_opt["temperature_unit"], $value->rain->{"1h"} ?? '0.00') . ' ' . $owmw_data["precipitation_unit"];
                    $owmw_data["hourly"][$cnt]["snow"] = owmw_getConvertedPrecipitation($owmw_opt["temperature_unit"], $value->snow->{"1h"} ?? '0.00') . ' ' . $owmw_data["precipitation_unit"];
                    $owmw_data["hourly"][$cnt]["precipitation"] = number_format(floatval($owmw_data["hourly"][$cnt]["rain"] ?? 0.00) + floatval($owmw_data["hourly"][$cnt]["snow"] ?? 0.00), 2) . ' ' . $owmw_data["precipitation_unit"];
                    $date = date('Ymd', $owmw_data["hourly"][$cnt]["timestamp"]);
                    if (isset($owmw_data[$date])) {
                        $owmw_data["hourly"][$cnt]["day_night"] = ($owmw_data["hourly"][$cnt]["timestamp"] > $owmw_data[$date]["sunrise"] && $owmw_data["hourly"][$cnt]["timestamp"] < $owmw_data[$date]["sunset"]) ? 'day' : 'night';
                    } else {
                        $owmw_data["hourly"][$cnt]["day_night"] = 'day';
                    }
                    ++$cnt;
                }
            }
        }

        //Moon rise and set
        if (!empty($owmw_data["daily"])) {
            $owmw_data["timestamp_moonrise"] = $owmw_data["daily"][0]["timestamp_moonrise"];
            $owmw_data["timestamp_moonset"] = $owmw_data["daily"][0]["timestamp_moonset"];
            $owmw_data["moonrise"] = (string)date_i18n(get_option("time_format"), $owmw_data["timestamp_moonrise"]);
            $owmw_data["moonset"] = (string)date_i18n(get_option("time_format"), $owmw_data["timestamp_moonset"]);
        }

        //Alerts loop
        if (isset($owmweather->alerts)) {
            foreach ($owmweather->alerts as $i => $value) {
                $owmw_data["alerts"][$i]["sender"] = $value->sender_name;
                $owmw_data["alerts"][$i]["event"] = $value->event;
                $owmw_data["alerts"][$i]["start"] = date_i18n(__('M j, Y @ G:i'), $value->start);
                $owmw_data["alerts"][$i]["end"] = date_i18n(__('M j, Y @ G:i'), $value->end);
                $owmw_data["alerts"][$i]["text"] = $value->description;
            }
        }


        //JSON : 5 day forecast weather (relies on lat and lon from current weather call)
        $url = "https://api.openweathermap.org/data/2.5/forecast?lon=" . $owmw_data["longitude"] . "&lat=" . $owmw_data["latitude"] . "&mode=xml&exclude=minutely&lang=" . $owmw_opt["owm_language"] . "&units=" . $owmw_opt["temperature_unit"] . "&APPID=" . $owmw_opt["api_key"];
        if ($owmw_opt["timemachine"] != "yes" && in_array($owmw_opt["template"], array("debug", "custom1", "custom2", "chart1", "chart2", "tabbed2", "table3"))) {
            if ($owmw_opt["disable_cache"] == 'yes') {
                $response = wp_remote_get(esc_url_raw($url), array( 'timeout' => 30));
                if (!is_wp_error($response)) {
                    $owmforecast = json_decode(wp_remote_retrieve_body($response));
                } else {
                    $errorMsgs = $response->get_error_messages();
                    $response = array();
                    $response['weather'] = $owmw_params["weather_id"];
                    $response['html'] = "<p>" . esc_html__("OWM Weather id", 'owm-weather') . " '" . esc_attr($owmw_opt["id"]) . "': " . esc_html__("OWM Error", 'owm-weather') . " " . esc_html__('Unable to retrieve weather data', 'owm-weather') . "</p>";
                    foreach ($errorMsgs as $errorMsg) {
                        $response['html'] .= "<p>" . esc_html__($errorMsg) . "</p>";
                    }
                    wp_send_json_error($response, 400);
                    return;
                }
            } else {
                $transient_key = 'owmw_5d_' . $owmw_data["longitude"] . $owmw_data["latitude"] . $owmw_opt["temperature_unit"][0] . $owmw_opt["owm_language"];
                if (false === ( $owmforecastXML = $get_transient($transient_key))) {
                    $response = wp_remote_get(esc_url_raw($url), array( 'timeout' => 30));
                    if (!is_wp_error($response)) {
                        $owmforecastXML = wp_remote_retrieve_body($response);
                        $set_transient($transient_key, $owmforecastXML, $owmw_opt["cache_time"] * MINUTE_IN_SECONDS);
                    } else {
                        $errorMsgs = $response->get_error_messages();
                        $response = array();
                        $response['weather'] = $owmw_params["weather_id"];
                        $response['html'] = "<p>" . esc_html__("OWM Weather id", 'owm-weather') . " '" . esc_attr($owmw_opt["id"]) . "': " . esc_html__("OWM Error", 'owm-weather') . " " . esc_html__('Unable to retrieve weather data', 'owm-weather') . "</p>";
                        $response['html'] .= "<pre>" . $url . "</pre>";
                        foreach ($errorMsgs as $errorMsg) {
                            $response['html'] .= "<p>" . esc_html__($errorMsg) . "</p>";
                        }
                        wp_send_json_error($response, 400);
                        return;
                    }
                }
            }
        }

        //Forecast loop
        if (!empty($owmforecastXML)) {
            $cnt = 0;
            $owmforecast = simplexml_load_string($owmforecastXML);
            foreach ($owmforecast->forecast->time as $i => $value) {
                $timestamp = strtotime($value->attributes()->from);
                if ($timestamp > (time() - (3 * 3600))) {
                    $owmw_data["forecast"][$cnt]["timestamp"] = $timestamp + (60 * $utc_time_wp);
                    $owmw_data["forecast"][$cnt]["time"] = (string)date($owmweather_hours_php, $timestamp + (60 * $utc_time_wp));
                    $owmw_data["forecast"][$cnt]["day"] = (string)date_i18n('D', $timestamp + (60 * $utc_time_wp));
                    $owmw_data["forecast"][$cnt]["condition_id"] = (int)$value->symbol->attributes()->number ?? 0;
                    $owmw_data["forecast"][$cnt]["description"] = owmw_getConditionText($owmw_data["forecast"][$cnt]["condition_id"]);
                    $owmw_data["forecast"][$cnt]["wind_speed"] = owmw_getConvertedWindSpeed((int)$value->windSpeed->attributes()->mps ?? 0, $owmw_opt["temperature_unit"], $owmw_opt["wind_unit"]) . ' ' . $windspeedLabel[$owmw_data["wind_speed_unit"]];
                    $owmw_data["forecast"][$cnt]["wind_speed_description"] = owmw_getWindspeedText(owmw_getConvertedWindSpeed((int)$value->windSpeed->attributes()->mps ?? 0, $owmw_opt["temperature_unit"], "mi/h"));
                    $owmw_data["forecast"][$cnt]["wind_degrees"] = (int)$value->windDirection->attributes()->deg ?? 0;
                    $owmw_data["forecast"][$cnt]["wind_direction"] = owmw_getWindDirection($owmw_data["forecast"][$cnt]["wind_degrees"]);
                    $owmw_data["forecast"][$cnt]["wind_gust"] = owmw_getConvertedWindSpeed((int)$value->windGust->attributes()->gust ?? 0, $owmw_opt["temperature_unit"], $owmw_opt["wind_unit"]) . ' ' . $windspeedLabel[$owmw_data["wind_speed_unit"]];
                    $owmw_data["forecast"][$cnt]["temperature"] = ceil((float)($value->temperature->attributes()->value ?? 0));
                    $temp_min = ceil((float)($value->temperature->attributes()->min ?? 0));
                    if (empty($owmw_data["forecast"]["temperature_minimum"][$owmw_data["forecast"][$cnt]["day"]]) || $owmw_data["forecast"]["temperature_minimum"][$owmw_data["forecast"][$cnt]["day"]] > $temp_min) {
                        $owmw_data["forecast"]["temperature_minimum"][$owmw_data["forecast"][$cnt]["day"]] = $temp_min;
                    }
                    $temp_max = ceil((float)($value->temperature->attributes()->max ?? 0));
                    if (empty($owmw_data["forecast"]["temperature_maximum"][$owmw_data["forecast"][$cnt]["day"]]) || $owmw_data["forecast"]["temperature_maximum"][$owmw_data["forecast"][$cnt]["day"]] < $temp_max) {
                        $owmw_data["forecast"]["temperature_maximum"][$owmw_data["forecast"][$cnt]["day"]] = $temp_max;
                    }
                    $owmw_data["forecast"][$cnt]["feels_like"] = ceil((float)($value->feels_like->attributes()->value ?? 0));
                    if ($owmw_opt["temperature_unit"] == 'metric') {
                        $owmw_data["forecast"][$cnt]["temperature_celsius"] = $owmw_data["forecast"][$cnt]["temperature"];
                        $owmw_data["forecast"][$cnt]["temperature_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["forecast"][$cnt]["temperature"]);
                        $owmw_data["forecast"]["temperature_minimum_celsius"] = $owmw_data["forecast"]["temperature_minimum"][$owmw_data["forecast"][$cnt]["day"]];
                        $owmw_data["forecast"]["temperature_minimum_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["forecast"]["temperature_minimum_celsius"]);
                        $owmw_data["forecast"]["temperature_maximum_celsius"] = $owmw_data["forecast"]["temperature_maximum"][$owmw_data["forecast"][$cnt]["day"]];
                        $owmw_data["forecast"]["temperature_maximum_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["forecast"]["temperature_maximum_celsius"]);
                        $owmw_data["forecast"][$cnt]["feels_like_celsius"] = $owmw_data["forecast"][$cnt]["feels_like"];
                        $owmw_data["forecast"][$cnt]["feels_like_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["forecast"][$cnt]["feels_like"]);
                    } else {
                        $owmw_data["forecast"][$cnt]["temperature_fahrenheit"] = $owmw_data["forecast"][$cnt]["temperature"];
                        $owmw_data["forecast"][$cnt]["temperature_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["forecast"][$cnt]["temperature"]);
                        $owmw_data["forecast"]["temperature_minimum_fahrenheit"] = $owmw_data["forecast"]["temperature_minimum"][$owmw_data["forecast"][$cnt]["day"]];
                        $owmw_data["forecast"]["temperature_minimum_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["forecast"]["temperature_minimum_fahrenheit"]);
                        $owmw_data["forecast"]["temperature_maximum_fahrenheit"] = $owmw_data["forecast"]["temperature_maximum"][$owmw_data["forecast"][$cnt]["day"]];
                        $owmw_data["forecast"]["temperature_maximum_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["forecast"]["temperature_maximum_fahrenheit"]);
                        $owmw_data["forecast"][$cnt]["feels_like_fahrenheit"] = $owmw_data["forecast"][$cnt]["feels_like"];
                        $owmw_data["forecast"][$cnt]["feels_like_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["forecast"][$cnt]["feels_like"]);
                    }
                    $owmw_data["forecast"][$cnt]["humidity"] = ((int)$value->humidity->attributes()->value ?? 0) . '%';
                    $owmw_data["forecast"][$cnt]["pressure"] = owmw_converthPa($owmw_opt["temperature_unit"], $value->pressure->attributes()->value, $pressureLabel[$owmw_data["pressure_unit"]]);
                    $owmw_data["forecast"][$cnt]["cloudiness"] = ((int)$value->clouds->attributes()->all ?? 0) . '%';
                    $owmw_data["forecast"][$cnt]["rain_chance"] = number_format(((float)$value->precipitation->attributes()->probability ?? 0) * 100, 0) . '%';
                    $owmw_data["forecast"][$cnt]["precipitation_unit"] = (string)$value->precipitation->attributes()->unit ?? null;
                    $owmw_data["forecast"][$cnt]["rain"] = '0.00 ' . $owmw_data["precipitation_unit"];
                    $owmw_data["forecast"][$cnt]["snow"] = '0.00 ' . $owmw_data["precipitation_unit"];
                    if ($owmw_data["forecast"][$cnt]["precipitation_unit"]) {
                        $owmw_data["forecast"][$cnt][(string)$value->precipitation->attributes()->type] = owmw_getConvertedPrecipitation($owmw_opt["temperature_unit"], (float)$value->precipitation->attributes()->value ?? '0.00') . ' ' . $owmw_data["precipitation_unit"];
                    }
                    $owmw_data["forecast"][$cnt]["precipitation"] = (floatval($owmw_data["forecast"][$cnt]["rain"]) + floatval($owmw_data["forecast"][$cnt]["snow"])) . ' ' . $owmw_data["precipitation_unit"];
                    $owmw_data["forecast"][$cnt]["visibility"] = owmw_getConvertedDistance($owmw_opt["temperature_unit"], (int)$value->visibility->attributes()->value ?? 0);
                    $date = date('Ymd', $owmw_data["forecast"][$cnt]["timestamp"]);
                    if (isset($owmw_data[$date])) {
                        $owmw_data["forecast"][$cnt]["day_night"] = ($owmw_data["forecast"][$cnt]["timestamp"] > $owmw_data[$date]["sunrise"] && $owmw_data["forecast"][$cnt]["timestamp"] < $owmw_data[$date]["sunset"]) ? 'day' : 'night';
                    } else {
                        $owmw_data["forecast"][$cnt]["day_night"] = 'day';
                    }
                    ++$cnt;
                }
            }
        }

        // escape all data fields for use as html blocks
        owmw_esc_html_all($owmw_data);

        //variable declarations
        $owmw_html = [];
        $owmw_html["now"]["start"]              = '';
        $owmw_html["now"]["location_name"]       = '';
        $owmw_html["now"]["symbol"]             = '';
        $owmw_html["now"]["temperature"]         = '';
        $owmw_html["now"]["temperature_celsius"]  = '';
        $owmw_html["now"]["temperature_fahrenheit"]   = '';
        $owmw_html["now"]["feels_like"]          = '';
        $owmw_html["now"]["feels_like_celsius"]          = '';
        $owmw_html["now"]["feels_like_fahrenheit"]          = '';
        $owmw_html["now"]["weather_description"] = '';
        $owmw_html["now"]["end"]                = '';
        $owmw_html["now"]["start_card"]     = '';
        $owmw_html["now"]["info_card"]      = '';
        $owmw_html["now"]["end_card"]       = '';
        $owmw_html["custom_css"] = $owmw_opt["custom_css"] ?? '';
        $owmw_html["today"]["start"]            = '';
        $owmw_html["today"]["day"]                  = '';
        $owmw_html["today"]["sun"]              = '';
        $owmw_html["today"]["moon"]                 = '';
        $owmw_html["info"]["start"]                 = '';
        $owmw_html["info"]["wind"]              = '';
        $owmw_html["info"]["humidity"]              = '';
        $owmw_html["info"]["dew_point"]             = '';
        $owmw_html["info"]["dew_point_celsius"]     = '';
        $owmw_html["info"]["dew_point_fahrenheit"]  = '';
        $owmw_html["info"]["pressure"]           = '';
        $owmw_html["info"]["cloudiness"]         = '';
        $owmw_html["info"]["precipitation"]      = '';
        $owmw_html["info"]["visibility"]         = '';
        $owmw_html["info"]["uv_index"]           = '';
        $owmw_html["svg"]["wind"]               = '';
        $owmw_html["svg"]["humidity"]           = '';
        $owmw_html["svg"]["dew_point"]              = '';
        $owmw_html["svg"]["pressure"]            = '';
        $owmw_html["svg"]["cloudiness"]          = '';
        $owmw_html["svg"]["precipitation"]       = '';
        $owmw_html["svg"]["visibility"]          = '';
        $owmw_html["svg"]["uv_index"]            = '';
        $owmw_html["info"]["end"]               = '';
        $owmw_html["hour"]["info"]               = '';
        $owmw_html["hour"]["start"]             = '';
        $owmw_html["hour"]["end"]               = '';
        $owmw_html["map"]                       = '';
        $owmw_html["map_script"]                = '';
        $owmw_html["today"]["end"]                  = '';
        $owmw_html["forecast"]["start"]          = '';
        $owmw_html["forecast"]["info"]           = '';
        $owmw_html["forecast"]["end"]            = '';
        $owmw_html["forecast"]["start_card"]     = '';
        $owmw_html["forecast"]["info_card"]      = '';
        $owmw_html["forecast"]["end_card"]       = '';
        $owmw_html["container"]["start"]         = '';
        $owmw_html["container"]["end"]           = '';
        $owmw_html["owm_link"]                  = '';
        $owmw_html["last_update"]               = '';
        $owmw_html["owm_link_last_update_start"] = '';
        $owmw_html["owm_link_last_update_end"]   = '';
        $owmw_html["gtag"]                       = '';
        $owmw_html["alert_button"]               = '';
        $owmw_html["alert_script"]               = '';
        $owmw_html["alert_modal"]                = '';
        $owmw_html["alert_inline"]               = '';
        $owmw_html["table"]["hourly"]            = '';
        $owmw_html["table"]["daily"]             = '';
        $owmw_html["table"]["forecast"]          = '';

        $owmw_html["main_weather_div"]       = esc_attr($owmw_params["weather_id"]);
        $owmw_html["container_weather_div"]  = owmw_unique_id_esc('owm-weather-container-' . $owmw_opt["id"]);
        $owmw_html["main_map_div"]           = owmw_unique_id_esc('owmw-map-id-' . $owmw_opt["id"]);
        $owmw_html["container_map_div"]      = owmw_unique_id_esc('owmw-map-container-' . $owmw_opt["id"]);

        $owmw_html["svg"]["wind"]          = owmw_wind_direction_icon($owmw_data["wind_degrees"], $owmw_opt["text_color"], $owmw_opt["wind_icon_direction"]);
        $owmw_html["svg"]["temperature"]   = owmw_temperature_icon($owmw_opt["text_color"]);
        $owmw_html["svg"]["humidity"]      = owmw_humidity_icon($owmw_opt["text_color"]);
        $owmw_html["svg"]["dew_point"]     = owmw_dew_point_icon($owmw_opt["text_color"]);
        $owmw_html["svg"]["pressure"]      = owmw_pressure_icon($owmw_opt["text_color"]);
        $owmw_html["svg"]["cloudiness"]    = owmw_cloudiness_icon($owmw_opt["text_color"]);
        $owmw_html["svg"]["rain_chance"]   = owmw_rain_chance_icon($owmw_opt["text_color"]);
        $owmw_html["svg"]["precipitation"] = owmw_precipitation_icon($owmw_opt["text_color"]);
        $owmw_html["svg"]["visibility"]    = owmw_visibility_icon($owmw_opt["text_color"]);
        $owmw_html["svg"]["uv_index"]      = owmw_uv_index_icon($owmw_opt["text_color"]);

        $owmw_html["current"]["day_night"] = ($owmw_data["timestamp"] > $owmw_data["timestamp_sunrise"] && $owmw_data["timestamp"] < $owmw_data["timestamp_sunset"]) ? 'day' : 'night';
        $owmw_html["current"]["symbol_svg"] = owmw_weatherSVG($owmw_opt["iconpack"], $owmw_data["condition_id"], $owmw_html["current"]["day_night"], $owmw_data["description"]);
        $owmw_html["current"]["symbol_alt"] = owmw_weatherIcon($owmw_opt["iconpack"], $owmw_data["condition_id"], $owmw_html["current"]["day_night"], $owmw_data["description"]);

        $display_now_start = '<div class="owmw-now">';
        $display_now_location_name = '<div class="owmw-location-name">' . owmw_city_name($owmw_opt["custom_city_name"], $owmw_data["name"])  . '</div>';
        if ($owmw_opt["disable_anims"] != 'yes') {
            $display_now_symbol = '<div class="owmw-main-symbol owmw-symbol-svg climacon" style="' . esc_attr(owmw_css_color("fill", $owmw_opt["text_color"])) . '"><span title="' . esc_attr($owmw_data["description"]) . '">' . $owmw_html["current"]["symbol_svg"] . '</span></div>';
        } else {
            $display_now_symbol = '<div class="owmw-main-symbol owmw-symbol-alt" style="' . esc_attr(owmw_css_color("fill", $owmw_opt["text_color"])) . '"><span title="' . esc_attr($owmw_data["description"]) . '">' . $owmw_html["current"]["symbol_alt"] . '</span></div>';
        }
        $display_now_temperature = '<div class="owmw-main-temperature">' . esc_html($owmw_data["temperature"]) . '</div>';
        $display_now_temperature_celsius = '<div class="owmw-main-temperature-celsius">' . esc_html($owmw_data["temperature_celsius"]) . '</div>';
        $display_now_temperature_fahrenheit = '<div class="owmw-main-temperature-fahrenheit">' . esc_html($owmw_data["temperature_fahrenheit"]) . '</div>';
        $display_now_feels_like = '<div class="owmw-main-feels-like">' . esc_html__('Feels Like', 'owm-weather') . ' ' . esc_html($owmw_data["feels_like"]) . '</div>';
        $display_now_feels_like_celsius = '<div class="owmw-main-feels-like-celsius">' . esc_html__('Feels Like', 'owm-weather') . ' ' . esc_html($owmw_data["feels_like_celsius"]) . '</div>';
        $display_now_feels_like_fahrenheit = '<div class="owmw-main-feels-like-fahrenheit">' . esc_html__('Feels Like', 'owm-weather') . ' ' . esc_html($owmw_data["feels_like_fahrenheit"]) . '</div>';
        $display_now_end = '</div>';

        //Hours loop
        if ($owmw_opt["hours_forecast_no"] > 0) {
            $owmweather_class_hours = array( 0 => "first", 1 => "second", 2 => "third", 3 => "fourth", 4 => "fifth", 5 => "sixth", 6 => "seventh", 7 => "eighth", 8 => "ninth", 9 => "tenth", 10 => "eleventh", 11 => "twelfth", 12 => "thirteenth", 13 => "fourteenth", 14 => "fifteenth", 15 => "sixteenth", 16 => "seventeenth", 17 => "eighteenth", 18 => "nineteenth", 19 => "twentieth", 20 => "twentyfirst", 21 => "twentysecond", 22 => "twentythird", 23 => "twentyfourth", 24 => "twentyfifth", 25 => "twentysixth", 26 => "twentyseventh", 27 => "twentyeighth", 28 => "twentyninth", 29 => "thirtieth", 30 => "thirtyfirst", 31 => "thirtysecond", 32 => "thirtythird", 33 => "thirtyfourth", 34 => "thirtyfifth", 35 => "thirtysixth", 36 => "thirtyseventh", 37 => "thirtyeighth", 38 => "thirtyninth", 39 => "fortieth", 40 => "fortyfirst", 41 => "fortysecond", 42 => "fortythird", 43 => "fortyfourth", 44 => "fortyfifth", 45 => "fortysixth", 46 => "fortyseventh", 47 => "fortyeighth" );

            foreach ($owmw_data["hourly"] as $i => $value) {
                $display_hours_icon[$i] = owmw_weatherIcon($owmw_opt["iconpack"], $value["condition_id"], $value["day_night"], $value["description"]);
                $display_hours[$i] =
                    '<div class="owmw-' . $owmweather_class_hours[$i] . ' card">
   						<div class="card-body">
   						    <div class="owmw-hour">' . date_i18n('D', $value["timestamp"]) . '<br>' .
                                ($owmw_opt["hours_time_icons"] == 'yes' ? owmw_hour_icon($value["time"], $owmw_opt["text_color"]) : esc_html($value["time"])) .
                            '</div>' .
                            $display_hours_icon[$i] .
                            '<div class="owmw-temperature">' .
                                esc_html($value["temperature"]) .
                            '</div>
    					</div>
   					</div>';
            }
        }

        //Daily loop
        if ($owmw_opt["days_forecast_no"] > 0) {
            $owmweather_class_days = array(0 => "first", 1 => "second", 2 => "third", 3 => "fourth", 4 => "fifth", 5 => "sixth", 6 => "seventh", 7 => "eighth");

            foreach ($owmw_data["daily"] as $i => $value) {
                $esc_display_forecasticon = owmw_weatherIcon($owmw_opt["iconpack"], $value["condition_id"], "day", $value["description"]);
                $display_forecast[$i] =
                    '<div class="owmw-' . esc_attr($owmweather_class_days[$i]) . ' row">
			      		<div class="owmw-day col">' . ($i == 0 ? esc_html__('Today', 'owm-weather') : esc_html($value["day"])) . '</div>' . '<div class="col">' . $esc_display_forecasticon . '</div>';
                        $display_forecast[$i] .= '<div class="owmw-rain col">' . esc_html($value["precipitation"]) . '</div>';
                        $display_forecast[$i] .=
                        '<div class="owmw-temp-min col">' . esc_html($value["temperature_minimum"]) . '</div>
			      		<div class="owmw-temp-max col"><span class="font-weight-bold">' . esc_html($value["temperature_maximum"]) . '</span></div>
			    	</div>';
                $display_forecast_card[$i] =
                    '<div class="owmw-' . esc_attr($owmweather_class_days[$i]) . ' card">
   						<div class="card-body">
					        <div class="owmw-day">' . ($i == 0 ? esc_html__('Today', 'owm-weather') : esc_html($value["day"])) . '</div>' . $esc_display_forecasticon .
                            '<div class="owmw-temperature">
       				            <span class="owmw-temp-min">' . esc_html($value["temperature_minimum"]) . '</span> - <span class="owmw-temp-max">' . esc_html($value["temperature_maximum"]) . '</span>
       				        </div>
       				        <div class="owmw-infos-text">
                            <table>
                            <tbody>';
                if ($owmw_opt["wind"] == 'yes') {
                    $display_forecast_card[$i] .= '<tr><td>' . owmw_wind_direction_icon($value["wind_degrees"], $owmw_opt["text_color"], $owmw_opt["wind_icon_direction"]) . '<span class="card-text-lable">' . esc_html__('Wind', 'owm-weather') . ':</span></td><td class="owmw-value">' . esc_html($value["wind_speed"] . ' ' . $value["wind_direction"]) . '</td></tr>';
                }
                if ($owmw_opt["humidity"] == 'yes') {
                    $display_forecast_card[$i] .= '<tr><td>' . $owmw_html["svg"]["humidity"] . '<span class="card-text-lable">' . esc_html__('Humidity', 'owm-weather') . ':</span></td><td class="owmw-value">' . esc_html($value["humidity"]) . '</td></tr>';
                }
                if ($owmw_opt["dew_point"] == 'yes') {
                    $display_forecast_card[$i] .= '<tr><td>' . $owmw_html["svg"]["dew_point"] . '<span class="card-text-lable">' . esc_html__('Dew Point', 'owm-weather') . ':</span></td><td class="owmw-value owmw-temperature">' . esc_html($value["dew_point"]) . '</td></tr>';
                }
                if ($owmw_opt["pressure"] == 'yes') {
                    $display_forecast_card[$i] .= '<tr><td>' . $owmw_html["svg"]["pressure"] . '<span class="card-text-lable">' . esc_html__('Pressure', 'owm-weather') . ':</span></td><td class="owmw-value">' . esc_html($value["pressure"]) . '</td></tr>';
                }
                if ($owmw_opt["cloudiness"] == 'yes') {
                    $display_forecast_card[$i] .= '<tr><td>' . $owmw_html["svg"]["cloudiness"] . '<span class="card-text-lable">' . esc_html__('Clouds', 'owm-weather') . ':</span></td><td class="owmw-value">' . esc_html($value["cloudiness"]) . '</td></tr>';
                }
                if ($owmw_opt["precipitation"] == 'yes') {
                    $display_forecast_card[$i] .= '<tr><td>' . $owmw_html["svg"]["rain_chance"] . '<span class="card-text-lable">' . esc_html__('Rain Chance', 'owm-weather') . ':</span></td><td class="owmw-value">' . esc_html($value["rain_chance"]) . '</td></tr>';
                }
                if ($owmw_opt["precipitation"] == 'yes') {
                    $display_forecast_card[$i] .= '<tr><td>' . $owmw_html["svg"]["precipitation"] . '<span class="card-text-lable">' . esc_html__('Precipitation', 'owm-weather') . ':</span></td><td class="owmw-value">' . esc_html($value["precipitation"]) . '</td></tr>';
                }
                if ($owmw_opt["uv_index"] == 'yes') {
                    $display_forecast_card[$i] .= '<tr><td>' . $owmw_html["svg"]["uv_index"] . '<span class="card-text-lable">' . esc_html__('UV Index', 'owm-weather') . ':</span></td><td class="owmw-value">' . esc_html($value["uv_index"]) . '</td></tr>';
                }
                $display_forecast_card[$i] .=
                            '</tbody>
                            </table>
                            </div>
    					</div>
   					</div>';
            }
        }

        //Map

        if ($owmw_opt['map'] == 'yes') {
            //Layers opacity
            $display_map_layers_opacity = floatval($owmw_opt["map_opacity"]);

            $display_map_layers  = '';
            $display_map_options = '';

            //Clouds
            if ($owmw_opt["map_clouds"] == 'yes') {
                $display_map_options            .= 'var clouds = L.OWM.clouds({showLegend: ' . ($owmw_opt["map_clouds_legend"] == 'yes' ? "true" : "false") . ', opacity: ' . esc_attr($display_map_layers_opacity) . ', appId: "' . esc_attr($owmw_opt["api_key"]) . '"});';
                $display_map_layers             .= '"' . esc_attr__("Clouds", "owm-weather") . '": clouds,';
                $display_map_options            .= 'var cloudscls = L.OWM.cloudsClassic({showLegend: ' . ($owmw_opt["map_clouds_legend"] == 'yes' ? "true" : "false") . ', opacity: ' . esc_attr($display_map_layers_opacity) . ', appId: "' . esc_attr($owmw_opt["api_key"]) . '"});';
                $display_map_layers             .= '"' . esc_attr__("Clouds Classic", "owm-weather") . '": cloudscls,';
            }

            //Precipitation
            if ($owmw_opt["map_precipitation"] == 'yes') {
                $display_map_options            .= 'var precipitation = L.OWM.precipitation({showLegend: ' . ($owmw_opt["map_precipitation_legend"] == 'yes' ? "true" : "false") . ', opacity: ' . esc_attr($display_map_layers_opacity) . ', appId: "' . esc_attr($owmw_opt["api_key"]) . '"});';
                $display_map_layers             .= '"' . esc_attr__("Precipitation", "owm-weather") . '": precipitation,';
                $display_map_options            .= 'var precipitationcls = L.OWM.precipitationClassic({showLegend: ' . ($owmw_opt["map_precipitation_legend"] == 'yes' ? "true" : "false") . ', opacity: ' . esc_attr($display_map_layers_opacity) . ', appId: "' . esc_attr($owmw_opt["api_key"]) . '"});';
                $display_map_layers             .= '"' . esc_attr__("Precipitation Classic", "owm-weather") . '": precipitationcls,';
            }

            //Rain
            if ($owmw_opt["map_rain"] == 'yes') {
                $display_map_options            .= 'var rain = L.OWM.rain({showLegend: ' . ($owmw_opt["map_rain_legend"] == 'yes' ? "true" : "false") . ', opacity: ' . esc_attr($display_map_layers_opacity) . ', appId: "' . esc_attr($owmw_opt["api_key"]) . '"});';
                $display_map_layers             .= '"' . esc_attr__("Rain", "owm-weather") . '": rain,';
                $display_map_options            .= 'var raincls = L.OWM.rainClassic({showLegend: ' . ($owmw_opt["map_rain_legend"] == 'yes' ? "true" : "false") . ', opacity: ' . esc_attr($display_map_layers_opacity) . ', appId: "' . esc_attr($owmw_opt["api_key"]) . '"});';
                $display_map_layers             .= '"' . esc_attr__("Rain Classic", "owm-weather") . '": raincls,';
            }

            //Snow
            if ($owmw_opt["map_snow"] == 'yes') {
                $display_map_options            .= 'var snow = L.OWM.snow({showLegend: ' . ($owmw_opt["map_snow_legend"] == 'yes' ? "true" : "false") . ', opacity: ' . esc_attr($display_map_layers_opacity) . ', appId: "' . esc_attr($owmw_opt["api_key"]) . '"});';
                $display_map_layers             .= '"' . esc_attr__("Snow", "owm-weather") . '": snow,';
            }

            //Wind
            if ($owmw_opt["map_wind"] == 'yes') {
                $display_map_options            .= 'var wind = L.OWM.wind({showLegend: ' . ($owmw_opt["map_wind_legend"] == 'yes' ? "true" : "false") . ', opacity: ' . esc_attr($display_map_layers_opacity) . ', appId: "' . esc_attr($owmw_opt["api_key"]) . '"});';
                $display_map_layers             .= '"' . esc_attr__("Wind", "owm-weather") . '": wind,';
            }

            //Temperature
            if ($owmw_opt["map_temperature"] == 'yes') {
                $display_map_options            .= 'var temp = L.OWM.temperature({' . ($owmw_opt["temperature_unit"] == 'imperial' ? 'legendImagePath: "' . esc_url(plugins_url('img/Legend_Fahrenheit.png', __FILE__)) . '",' : '') . 'showLegend: ' . ($owmw_opt["map_temperature_legend"] == 'yes' ? "true" : "false") . ', opacity: ' . esc_attr($display_map_layers_opacity) . ', appId: "' . esc_attr($owmw_opt["api_key"]) . '",temperatureUnit:"F"});';
                $display_map_layers             .= '"' . esc_attr__("Temperature", "owm-weather") . '": temp,';
            }

            //Pressure
            if ($owmw_opt["map_pressure"] == 'yes') {
                $display_map_options            .= 'var pressure = L.OWM.pressure({showLegend: ' . ($owmw_opt["map_pressure_legend"] == 'yes' ? "true" : "false") . ', opacity: ' . esc_attr($display_map_layers_opacity) . ', appId: "' . esc_attr($owmw_opt["api_key"]) . '"});';
                $display_map_layers             .= '"' . esc_attr__("Pressure", "owm-weather") . '": pressure,';
                $display_map_options            .= 'var pressurecntr = L.OWM.pressureContour({showLegend: ' . ($owmw_opt["map_pressure_legend"] == 'yes' ? "true" : "false") . ', opacity: ' . esc_attr($display_map_layers_opacity) . ', appId: "' . esc_attr($owmw_opt["api_key"]) . '"});';
                $display_map_layers             .= '"' . esc_attr__("Pressure Contour", "owm-weather") . '": pressurecntr,';
            }

            //Wind Rose
            if ($owmw_opt["map_windrose"] == 'yes') {
                $display_map_options .= 'var windrose = L.OWM.current({showLegend: ' . ($owmw_opt["map_windrose_legend"] == 'yes' ? "true" : "false") . ', intervall: 15, lang: "en", minZoom: 4, appId: "' . esc_attr($owmw_opt["api_key"]) . '", markerFunction: myWindroseMarker, popup: false, clusterSize: 50, imageLoadingBgUrl: "https://openweathermap.org/img/w0/iwind.png" });	windrose.on("owmlayeradd", windroseAdded, windrose);';
                $display_map_layers             .= '"' . esc_attr__("Wind Rose", "owm-weather") . '": windrose,';
            }

            //Cities
            if ($owmw_opt["map_windrose"] == 'yes') {
                if ($owmw_opt["wind_unit"] == "m/s") {
                    $map_speed = 'ms';
                } elseif ($owmw_opt["wind_unit"] == "km/h") {
                    $map_speed = 'kmh';
                } else {
                    $map_speed = 'mph';
                }
                $display_map_options .= 'var city = L.OWM.current({showLegend: ' . ($owmw_opt["map_cities_legend"] == 'yes' ? "true" : "false") . ', intervall: ' . esc_attr($owmw_opt["cache_time"] ?? 30) . ', lang: "en", minZoom: 5, appId: "' . esc_attr($owmw_opt["api_key"]) . '",temperatureDigits:0,temperatureUnit:"' . esc_attr($owmw_data["temperature_unit_character"]) . '",speedUnit:"' . esc_attr($map_speed) . '"});';
                $display_map_layers             .= '"' . esc_attr__("Cities", "owm-weather") . '": city,';
            }

            //Scroll wheel
            $display_map_scroll_wheel = ($owmw_opt["map_disable_zoom_wheel"] == 'yes') ? "false" : "true";

            $owmw_html["map"] =
                '<div id="' . $owmw_html["main_map_div"] . '" class="owmw-map">
			        	<div id="' . esc_attr($owmw_html["container_map_div"]) . '" style="' . owmw_css_height($owmw_opt["map_height"]) . '"></div>
			        </div>';
            $owmw_html["map_script"] =
                'jQuery(document).ready( function() {

				        	var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
							maxZoom: 18, attribution: \'&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank">OpenStreetMap</a> contributors</a>\' });'

                        . $display_map_options .

                        'var map = L.map("' . esc_attr($owmw_html["container_map_div"]) . '", { center: new L.LatLng(' . esc_attr($owmw_data["latitude"]) . ', ' . esc_attr($owmw_data["longitude"]) . '), zoom: ' . esc_attr($owmw_opt["map_zoom"]) . ', layers: [osm], scrollWheelZoom: ' . esc_attr($display_map_scroll_wheel) . ' });

							var baseMaps = { "OSM Standard": osm };
							var overlayMaps = {' . $display_map_layers . '};
							var layerControl = L.control.layers(baseMaps, overlayMaps).addTo(map);

							' . ($owmw_opt["map_clouds"] == 'yes' && $owmw_opt["map_clouds_on"] == 'yes' ? "clouds.addTo(map);cloudscls.addTo(map);" : "") . '
							' . ($owmw_opt["map_precipitation"] == 'yes' && $owmw_opt["map_precipitation_on"] == 'yes' ? "precipitation.addTo(map);precipitationcls.addTo(map);" : "") . '
							' . ($owmw_opt["map_rain"] == 'yes' && $owmw_opt["map_rain_on"] == 'yes' ? "rain.addTo(map);raincls.addTo(map);" : "") . '
							' . ($owmw_opt["map_snow"] == 'yes' && $owmw_opt["map_snow_on"] == 'yes' ? "snow.addTo(map);" : "") . '
							' . ($owmw_opt["map_wind"] == 'yes' && $owmw_opt["map_wind_on"] == 'yes' ? "wind.addTo(map);" : "") . '
							' . ($owmw_opt["map_temperature"] == 'yes' && $owmw_opt["map_temperature_on"] == 'yes' ? "temp.addTo(map);" : "") . '
							' . ($owmw_opt["map_pressure"] == 'yes' && $owmw_opt["map_pressure_on"] == 'yes' ? "pressure.addTo(map);pressurecntr.addTo(map);" : "") . '
							' . ($owmw_opt["map_windrose"] == 'yes' && $owmw_opt["map_windrose_on"] == 'yes' ? "windrose.addTo(map);" : "") . '
							' . ($owmw_opt["map_cities"] == 'yes' && $owmw_opt["map_cities_on"] == 'yes' ? "city.addTo(map);" : "") . '

                            map.whenReady(function() {
                               	jQuery( "#' . esc_attr($owmw_html["container_map_div"]) . '").on("invalidSize", function() {
                                    map.invalidateSize();
                            	});
                            });
			        	});';
        }

        $owmw_html["container"]["start"] = '<!-- OWM Weather : WordPress weather plugin v' . OWM_WEATHER_VERSION . ' - https://github.com/uwejacobs/owm-weather -->';
        $owmw_html["container"]["start"] .= '<div id="' . $owmw_html["container_weather_div"] . '" class="container owmw-' . esc_attr($owmw_opt["id"]) . ' owm-weather-' . esc_attr($owmw_data["condition_id"]) . ' owmw-' . $owmw_opt["size"] . ' owmw-template-' . $owmw_opt["template"] . '"';
        $video_id = owmw_background_yt_video($owmw_opt, $owmw_data["condition_id"]);
        $owmw_html["container"]["start"] .= ' style="';
        $owmw_html["container"]["start"] .= owmw_css_weather_based_text_color($owmw_opt, $owmw_data["condition_id"]) .
                                            owmw_css_border($owmw_opt["border_color"], $owmw_opt["border_width"], $owmw_opt["border_style"], $owmw_opt["border_radius"]) .
                                            owmw_css_font_family($owmw_opt["font"]);
        if (empty($video_id)) {
            $owmw_html["container"]["start"] .= owmw_css_background_color($owmw_opt, $owmw_data["condition_id"]) .
                                                owmw_css_background_image($owmw_opt, $owmw_data["condition_id"]) .
                                                owmw_css_background_size("cover");
        }
        $owmw_html["container"]["start"] .= '"';

        $owmw_html["container"]["start"] .= '>';

        if (!empty($video_id)) {
            $owmw_html["container"]["start"] .= owmw_printYTvideo($video_id, $owmw_opt["background_opacity"], owmw_unique_id_esc($owmw_opt["id"], ''));
        }

        // Now
        if ($owmw_opt["current_city_name"] == 'yes' || $owmw_opt["current_weather_symbol"] == 'yes' || $owmw_opt["current_temperature"] == 'yes' || $owmw_opt["current_feels_like"] == 'yes' || $owmw_opt["current_weather_description"] == 'yes') {
            $owmw_html["now"]["start"]              = $display_now_start;
            if ($owmw_opt["current_city_name"] == 'yes') {
                $owmw_html["now"]["location_name"]       = $display_now_location_name;
            }
            if ($owmw_opt["current_weather_symbol"] == 'yes') {
                $owmw_html["now"]["symbol"]     = $display_now_symbol;
            }
            if ($owmw_opt["current_temperature"] == 'yes') {
                $owmw_html["now"]["temperature"]    = $display_now_temperature;
                $owmw_html["now"]["temperature_celsius"]    = $display_now_temperature_celsius;
                $owmw_html["now"]["temperature_fahrenheit"]    = $display_now_temperature_fahrenheit;
            }
            if ($owmw_opt["current_feels_like"] == 'yes') {
                $owmw_html["now"]["feels_like"]    = $display_now_feels_like;
                $owmw_html["now"]["feels_like_celsius"]    = $display_now_feels_like_celsius;
                $owmw_html["now"]["feels_like_fahrenheit"]    = $display_now_feels_like_fahrenheit;
            }
            if ($owmw_opt["current_weather_description"] == 'yes') {
                $owmw_html["now"]["weather_description"] = '<div class="owmw-short-condition">' . esc_html($owmw_data["description"]) . ' | ' . $owmw_data["wind_speed_description"] . '</div>';
            }
            $owmw_html["now"]["end"]                = $display_now_end;
        }

        $owmw_html["today"]["start"]     = '<div class="owmw-today row">';
        if ($owmw_opt["today_date_format"] != "none") {
            $owmw_html["today"]["day"]       = '<div class="owmw-day col"><span class="owmw-highlight">' . esc_html($owmw_data["today_day"]) . '</span></div>';
        }
        $owmw_html["today"]["sun"]       = owmw_display_today_sunrise_sunset($owmw_opt["sunrise_sunset"], $owmw_data["sunrise"], $owmw_data["sunset"], $owmw_opt["text_color"], 'span');
        $owmw_html["today"]["sun_hor"]   = owmw_display_today_sunrise_sunset($owmw_opt["sunrise_sunset"], $owmw_data["sunrise"], $owmw_data["sunset"], $owmw_opt["text_color"], 'div');
        $owmw_html["today"]["moon"]      = owmw_display_today_moonrise_moonset($owmw_opt["moonrise_moonset"], $owmw_data["moonrise"], $owmw_data["moonset"], $owmw_opt["text_color"], 'span');
        $owmw_html["today"]["moon_hor"]  = owmw_display_today_moonrise_moonset($owmw_opt["moonrise_moonset"], $owmw_data["moonrise"], $owmw_data["moonset"], $owmw_opt["text_color"], 'div');
        $owmw_html["today"]["end"]       = '</div>';

        if ($owmw_opt["wind"] == 'yes' || $owmw_opt["humidity"] == 'yes' || $owmw_opt["dew_point"] == 'yes' || $owmw_opt["pressure"] == 'yes' || $owmw_opt["cloudiness"] == 'yes' || $owmw_opt["precipitation"] == 'yes' || $owmw_opt["visibility"] == 'yes' || $owmw_opt["uv_index"] == 'yes') {
            $owmw_html["info"]["start"]     .= '<div class="owmw-infos row">';
            $owmw_html["now"]["start_card"] .= '<div class="owmw-current-infos card"><div class="card-body"><table class="owmw-infos-text"><tbody>';

            if ($owmw_opt["wind"] == 'yes') {
                $owmw_html["info"]["wind"]            = '<div class="owmw-wind col">' . $owmw_html["svg"]["wind"] . '<span class="card-text-lable">' . esc_html__('Wind', 'owm-weather') . '</span><span class="owmw-highlight">' . esc_html($owmw_data["wind_speed"] . ' - ' . $owmw_data["wind_direction"]) . '</span></div>';
                $owmw_html["now"]["info_card"] .= "<tr><td>" . $owmw_html["svg"]["wind"] . '<span class="card-text-lable">' . esc_html__('Wind', 'owm-weather') . ':</span></td><td class="owmw-value">' . esc_html($owmw_data["wind_speed"]) . ' ' . esc_html($owmw_data["wind_direction"]) . '</td></tr>';
            }

            if ($owmw_opt["humidity"] == 'yes') {
                $owmw_html["info"]["humidity"]        = '<div class="owmw-humidity col">' . $owmw_html["svg"]["humidity"] . '<span class="card-text-lable">' . esc_html__('Humidity', 'owm-weather') . '</span><span class="owmw-highlight">' . esc_html($owmw_data["humidity"]) . '</span></div>';
                $owmw_html["now"]["info_card"] .= "<tr><td>" . $owmw_html["svg"]["humidity"] . '<span class="card-text-lable">' . esc_html__('Humidity', 'owm-weather') . ':</span></td><td class="owmw-value">' . esc_html($owmw_data["humidity"]) . '</td></tr>';
            }

            if ($owmw_opt["dew_point"] == 'yes') {
                $owmw_html["info"]["dew_point"]       = '<div class="owmw-dew-point col">' . $owmw_html["svg"]["dew_point"] . '<span class="card-text-lable">' .  esc_html__('Dew Point', 'owm-weather') . '</span><span class="owmw-highlight owmw-temperature">' . esc_html($owmw_data["dew_point"]) . '</span></div>';
                $owmw_html["info"]["dew_point_celsius"]       = '<div class="owmw-dew-point col">' . $owmw_html["svg"]["dew_point"] . '<span class="card-text-lable">' .  esc_html__('Dew Point', 'owm-weather') . '</span><span class="owmw-highlight owmw-temperature">' . esc_html($owmw_data["dew_point_celsius"]) . '</span></div>';
                $owmw_html["info"]["dew_point_fahrenheit"]       = '<div class="owmw-dew-point col">' . $owmw_html["svg"]["dew_point"] . '<span class="card-text-lable">' .  esc_html__('Dew Point', 'owm-weather') . '</span><span class="owmw-highlight owmw-temperature">' . esc_html($owmw_data["dew_point_fahrenheit"]) . '</span></div>';
                $owmw_html["now"]["info_card"] .= "<tr><td>" . $owmw_html["svg"]["dew_point"] . '<span class="card-text-lable">' . esc_html__('Dew Point', 'owm-weather') . ':</span></td><td class="owmw-value owmw-temperature">' . esc_html($owmw_data["dew_point"]) . '</td></tr>';
            }

            if ($owmw_opt["pressure"]  == 'yes') {
                $owmw_html["info"]["pressure"]        = '<div class="owmw-pressure col">' . $owmw_html["svg"]["pressure"] . '<span class="card-text-lable">' .  esc_html__('Pressure', 'owm-weather') . '</span><span class="owmw-highlight">' . esc_html($owmw_data["pressure"]) . '</span></div>';
                $owmw_html["now"]["info_card"] .= "<tr><td>" . $owmw_html["svg"]["pressure"] . '<span class="card-text-lable">' . esc_html__('Pressure', 'owm-weather') . ':</span></td><td class="owmw-value">' . esc_html($owmw_data["pressure"]) . '</td></tr>';
            }

            if ($owmw_opt["cloudiness"] == 'yes') {
                $owmw_html["info"]["cloudiness"]      = '<div class="owmw-cloudiness col">' . $owmw_html["svg"]["cloudiness"] . '<span class="card-text-lable">' .  esc_html__('Cloudiness', 'owm-weather') . '</span><span class="owmw-highlight">' . esc_html($owmw_data["cloudiness"]) . '</span></div>';
                $owmw_html["now"]["info_card"] .= "<tr><td>" . $owmw_html["svg"]["cloudiness"] . '<span class="card-text-lable">' . esc_html__('Cloudiness', 'owm-weather') . ':</span></td><td class="owmw-value">' . esc_html($owmw_data["cloudiness"]) . '</td></tr>';
            }

            if ($owmw_opt["precipitation"] == 'yes') {
                $owmw_html["info"]["precipitation"]   = '<div class="owmw-precipitation col">' . $owmw_html["svg"]["precipitation"] . '<span class="card-text-lable">' .  esc_html__('Precipitation', 'owm-weather') . '</span><span class="owmw-highlight">' . esc_html($owmw_data["precipitation_3h"]) . '</span></div>';
                $owmw_html["now"]["info_card"] .= "<tr><td>" . $owmw_html["svg"]["precipitation"] . '<span class="card-text-lable">' . esc_html__('Precipitation', 'owm-weather') . ':</span></td><td class="owmw-value">' . esc_html($owmw_data["precipitation_1h"]) . '</td></tr>';
            }

            if ($owmw_opt["visibility"] == 'yes') {
                $owmw_html["info"]["visibility"]     = '<div class="owmw-visibility col">' . $owmw_html["svg"]["visibility"] . '<span class="card-text-lable">' .  esc_html__('Visibility', 'owm-weather') . '</span><span class="owmw-highlight">' . esc_html($owmw_data["visibility"]) . '</span></div>';
                $owmw_html["now"]["info_card"] .= "<tr><td>" . $owmw_html["svg"]["visibility"] . '<span class="card-text-lable">' . esc_html__('Visibility', 'owm-weather') . ':</span></td><td class="owmw-value">' . esc_html($owmw_data["visibility"]) . '</td></tr>';
            }

            if ($owmw_opt["uv_index"] == 'yes') {
                $owmw_html["info"]["uv_index"]       = '<div class="owmw-uv-index col">' . $owmw_html["svg"]["uv_index"] . '<span class="card-text-lable">' .  esc_html__('UV Index', 'owm-weather') . '</span><span class="owmw-highlight">' . esc_html($owmw_data["uv_index"]) . '</span></div>';
                $owmw_html["now"]["info_card"] .= "<tr><td>" . $owmw_html["svg"]["uv_index"] . '<span class="card-text-lable">' . esc_html__('UV Index', 'owm-weather') . ':</span></td><td class="owmw-value">' . esc_html($owmw_data["uv_index"]) . '</td></tr>';
            }

            if ($owmw_opt["sunrise_sunset"] == 'yes') {
                $owmw_html["now"]["info_card"] .= '<tr><td><span class="owmw-sunrise" title="' . esc_attr__('Sunrise', 'owm-weather') . '">' . owmw_sunrise($owmw_opt["text_color"]) . '<span class="font-weight-bold">' . esc_html($owmw_data["sunrise"]) . '</span></td>';
                $owmw_html["now"]["info_card"] .= '<td><span class="owmw-sunset" title="' . esc_attr__('Sunset', 'owm-weather') . '">' . owmw_sunset($owmw_opt["text_color"]) . '<span class="font-weight-bold">' . esc_html($owmw_data["sunset"]) . '</span></td></tr>';
            }

            if ($owmw_opt["moonrise_moonset"] == 'yes') {
                $owmw_html["now"]["info_card"] .= '<tr><td><span class="owmw-moonrise" title="' . esc_attr__('moonrise', 'owm-weather') . '">' . owmw_moonrise($owmw_opt["text_color"]) . '<span class="font-weight-bold">' . esc_html($owmw_data["moonrise"]) . '</span></td>';
                $owmw_html["now"]["info_card"] .= '<td><span class="owmw-moonset" title="' . esc_attr__('moonset', 'owm-weather') . '">' . owmw_moonset($owmw_opt["text_color"]) . '<span class="font-weight-bold">' . esc_html($owmw_data["moonset"]) . '</span></td></tr>';
            }

            $owmw_html["now"]["end_card"]   .= '</tbody></table></div></div>';
            $owmw_html["info"]["end"] .= '</div>';
        };

        if ($owmw_opt["hours_forecast_no"] > 0) {
            $owmw_html["hour"]["start"] = '<div class="owmw-hours card-column" style="' . owmw_css_color('border-color', $owmw_opt["border_color"]) . '">';
            $owmw_html["hour"]["info"]  = $display_hours;
            $owmw_html["hour"]["end"]   = '</div>';

            for ($i = 1; $i <= 12; $i++) {
                $owmw_html["hour"]["icon" . $i] = owmw_hour_icon($i, $owmw_opt["text_color"]);
            }
        }

        if ($owmw_opt["days_forecast_no"] > 0) {
            $owmw_html["forecast"]["start"] = '<div class="owmw-forecast d-flex flex-column justify-content-center">';
            $owmw_html["forecast"]["info"]  = $display_forecast;
            $owmw_html["forecast"]["end"]   = '</div>';
            $owmw_html["forecast"]["start_card"] = '<div class="owmw-forecast d-flex justify-content-center flex-wrap">';
            $owmw_html["forecast"]["info_card"]  = $display_forecast_card;
            $owmw_html["forecast"]["end_card"]   = '</div>';
        }

        //Google Tag Manager dataLayer
        if ($owmw_opt["gtag"] == 'yes') {
            $owmw_html["gtag"] =
                'var dataLayer = window.dataLayer = window.dataLayer || [];
	        	jQuery(document).ready(function() {
                    dataLayer.push({
                      "weatherTemperature": "' . esc_attr($owmw_data["temperature"]) . '",
                      "weatherFeelsLike": "' . esc_attr($owmw_data["feels_like"]) . '",
                      "weatherCloudiness": "' . intval($owmw_data["cloudiness"]) . '",
                      "weatherDescription": "' . esc_attr($owmw_data["description"]) . '",
                      "weatherCategory": "' . esc_attr($owmw_data["category"]) . '",
                      "weatherWindSpeed": "' . intval($owmw_data["wind_speed"]) . '",
                      "weatherWindDirection": "' . esc_attr($owmw_data["wind_direction"]) . '",
                      "weatherHumidity": "' . intval($owmw_data["humidity"]) . '",
                      "weatherPressure": "' . floatval($owmw_data["pressure"]) . '",
                      "weatherPrecipitation": "' . floatval($owmw_data["precipitation_3h"]) . '",
                      "weatherUVIndex": "' . floatval($owmw_data["uv_index"]) . '",
                      "weatherDewPoint": "' . intval($owmw_data["dew_point"]) . '",
                  });
                });';
        }

        if ($owmw_opt["alerts"] == 'yes' && !empty($owmw_data["alerts"])) {
            require_once dirname(__FILE__) . '/owmweather-color-css.php';

            $owmw_html["custom_css"] .= owmw_generateColorCSS($owmw_opt["text_color"] ?? 'inherit', "owmw-alert-" . esc_attr($owmw_opt["id"]));
            $owmw_html["alert_button"] .= '<div class="owmw-alert-buttons text-center">';

            foreach ($owmw_data["alerts"] as $key => $value) {
                if (empty($owmw_opt["alerts_popup"]) || $owmw_opt["alerts_popup"] === "modal") {
                    $modal = owmw_unique_id_esc('owmw-modal-' . esc_attr($owmw_opt["id"]));
                    $owmw_html["alert_button"] .= '<button class="owmw-alert-button btn btn-outline-owmw-alert-' . esc_attr($owmw_opt["id"]) . '" style="margin: 0.25rem !important;"  data-' . $owmw_opt["bootstrap_data"] . 'toggle="modal" data-' . $owmw_opt["bootstrap_data"] . 'target="#' . esc_attr($modal) . '">' . esc_html($value["event"]) . '</button>';
                    $owmw_html["alert_modal"] .=
                        '<div class="modal fade" id="' . esc_attr($modal) . '" tabindex="-1" role="dialog" aria-labelledby="' . esc_attr($modal) . '-label" aria-hidden="true">
						  <div class="modal-dialog" role="document">
							<div class="modal-content" style="' .
                                    owmw_css_color('background-color', "#fff") . owmw_css_color("color", "#000") . '">
							  <div class="modal-header">
								<h5 class="modal-title" id="' . esc_attr($modal) . '-label">' . esc_html($value["event"]) . '</h5>
								' . $owmw_opt["bootstrap_modal_close"] . '
							  </div>
							  <div class="modal-body">
								<p>' . esc_html($value["sender"]) . '<br>' . esc_html($value["start"]) . ' until ' . esc_html($value["end"]) . '</p>
								<p>' . nl2br($value["text"]) . '</p></div>
							  <div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-' . $owmw_opt["bootstrap_data"] . 'dismiss="modal">Close</button>
							  </div>
							</div>
						  </div>
						</div>';
                } else {
                    $card = str_replace('-', '_', owmw_unique_id_esc('owmw_card_' . esc_attr($owmw_opt["id"])));
                    $owmw_html["alert_button"] .= '<button class="owmw-alert-button btn btn-outline-owmw-alert-' . esc_attr($owmw_opt["id"]) . ' m-1" onclick="toggleAlert_' . esc_attr($card) . '();">' . esc_html($value["event"]) . '</button>';
                    $owmw_html["alert_script"] .= 'function toggleAlert_' . esc_attr($card) . '() { var div = document.getElementById("' . esc_html($card) . '"); div.style.display = div.style.display == "none" ? "block" : "none"; }';
                    $owmw_html["alert_inline"] .= '<div id="' . esc_attr($card) . '" class="card" style="display: none;' .
                                    owmw_css_color('background-color', $owmw_opt["background_color"]) . owmw_css_color("color", $owmw_opt["text_color"]) . '">';
                    $owmw_html["alert_inline"] .= '<div class="card-header">';
                    $owmw_html["alert_inline"] .= '<h5>' . esc_html($value["event"]) . '</h5>';
                    $owmw_html["alert_inline"] .= '<div class="card-header">';
                    $owmw_html["alert_inline"] .= '<p>' . esc_html($value["sender"]) . '<br>' . esc_html($value["start"]) . ' until ' . esc_html($value["end"]) . '</p>';
                    $owmw_html["alert_inline"] .= '<p>' . nl2br($value["text"]) . '</p>';
                    $owmw_html["alert_inline"] .= '</div>';
                    $owmw_html["alert_inline"] .= '</div>';
                    $owmw_html["alert_inline"] .= '</div>';
                }
            }
            $owmw_html["alert_button"] .= '</div>';
        }

        $owmw_html["temperature_unit"] = owmw_temperatureUnitSymbol($owmw_html["container_weather_div"], $owmw_opt["display_temperature_unit"], $owmw_opt["temperature_unit"], $owmw_opt["iconpack"]);
        $owmw_html["temperature_unit"] .= owmw_temperatureUnitSymbol($owmw_html["container_weather_div"], $owmw_opt["display_temperature_unit"], "metric", $owmw_opt["iconpack"], "-celsius");
        $owmw_html["temperature_unit"] .= owmw_temperatureUnitSymbol($owmw_html["container_weather_div"], $owmw_opt["display_temperature_unit"], "imperial", $owmw_opt["iconpack"], "-fahrenheit");

        if ($owmw_opt["owm_link"] == 'yes' || $owmw_opt["last_update"] == 'yes') {
            $owmw_html["owm_link_last_update_start"] .= '<div class="row owmw-owm-link-last-update">';
            $owmw_html["owm_link_last_update_end"] .= '</div>';
        }

        if ($owmw_opt["owm_link"] == 'yes') {
            $owmw_html["owm_link"] = '<div class="col owmw-link-owm"><a rel="noopener" href="' . esc_url($owmw_data["owm_link"]) . '" target="_blank" title="' . esc_attr__('Full weather on OpenWeatherMap', 'owm-weather') . '">' . esc_html__('Full weather', 'owm-weather') . '</a></div>';
        }
        if ($owmw_opt["last_update"] == 'yes') {
            $owmw_html["last_update"] = '<div class="col owmw-last-update">' . esc_html($owmw_data["last_update"]) . '</div>';
        }

        //charts
        $owmw_html["chart"]["hourly"] = [];
        $owmw_html["chart"]["hourly"]["temperatures"] = [];
        $owmw_html["chart"]["hourly"]["precipitation"] = [];
        $owmw_html["chart"]["hourly"]["precip_amt"] = [];
        $owmw_html["chart"]["hourly"]["wind"] = [];
        $owmw_html["chart"]["hourly"]["night"] = [];
        $owmw_html["chart"]["hourly"]["night_boxes"] = '';
        $owmw_html["chart"]["daily"] = [];
        $owmw_html["chart"]["daily"]["temperatures"] = [];
        $owmw_html["chart"]["daily"]["precipitation"] = [];
        $owmw_html["chart"]["daily"]["precip_amt"] = [];
        $owmw_html["chart"]["daily"]["wind"] = [];
        $owmw_html["chart"]["daily"]["night"] = [];
        $owmw_html["chart"]["daily"]["night_boxes"] = '';

        if (in_array($owmw_opt["template"], array("debug", "custom1", "custom2", "chart1", "chart2", "tabbed2", "table3"))) {
            require_once dirname(__FILE__) . '/owmweather-color-css.php';

            //hourly temperatures
            $chart_id = owmw_unique_id_esc($owmw_opt["id"], '_');
            $owmw_html["container_chart_hourly_temp_div"] = 'owmw-hourly-temp-chart-canvas-' . esc_attr($chart_id);
            $owmw_html["chart"]["hourly"]["temperatures"]["labels"] = '';
            $owmw_html["chart"]["hourly"]["temperatures"]["dataset_temperature"] = '';
            $owmw_html["chart"]["hourly"]["temperatures"]["dataset_feels_like"] = '';
            $owmw_html["chart"]["hourly"]["temperatures"]["dataset_dew_point"] = '';
            $owmw_html["chart"]["hourly"]["temperatures"]["config"] = '';
            $owmw_html["chart"]["hourly"]["temperatures"]["data"] = '';
            $owmw_html["chart"]["hourly"]["temperatures"]["options"] = '';
            $owmw_html["chart"]["hourly"]["temperatures"]["chart"] = '';
            $owmw_html["chart"]["hourly"]["temperatures"]["cmd"] = '';
            $owmw_html["chart"]["hourly"]["temperatures"]["container"] = '';
            $owmw_html["chart"]["hourly"]["temperatures"]["script"] = '';

            if ($owmw_opt["hours_forecast_no"] > 0) {
                $owmw_html["chart"]["hourly"]["temperatures"]["labels"] .= 'const hourly_temp_labels_' . esc_attr($chart_id) . ' = [';
                $owmw_html["chart"]["hourly"]["temperatures"]["dataset_temperature"] .= 'const hourly_temp_temperature_dataset_' . esc_attr($chart_id) . ' = [';
                $owmw_html["chart"]["hourly"]["temperatures"]["dataset_feels_like"] .= 'const hourly_temp_feels_like_dataset_' . esc_attr($chart_id) . ' = [';
                $owmw_html["chart"]["hourly"]["temperatures"]["dataset_dew_point"] .= 'const hourly_temp_dew_point_dataset_' . esc_attr($chart_id) . ' = [';
                $cnt = 0;
                foreach ($owmw_data["hourly"] as $i => $value) {
                    if ($cnt < $owmw_opt["hours_forecast_no"]) {
                        $owmw_html["chart"]["hourly"]["temperatures"]["labels"] .= '"' . esc_attr($value["time"] != 'Now' ? date_i18n('D', $value["timestamp"]) . ' ' : '') . $value["time"] . '",';
                        $owmw_html["chart"]["hourly"]["temperatures"]["dataset_temperature"] .= '"' . esc_html($value["temperature"]) . '",';
                        $owmw_html["chart"]["hourly"]["temperatures"]["dataset_feels_like"] .= '"' . esc_html($value["feels_like"]) . '",';
                        $owmw_html["chart"]["hourly"]["temperatures"]["dataset_dew_point"] .= '"' . esc_html($value["dew_point"]) . '",';
                        if ($value["day_night"] == 'night') {
                            $owmw_html["chart"]["hourly"]["night"][] = $cnt;
                        }
                        ++$cnt;
                    }
                }
                $owmw_html["chart"]["hourly"]["temperatures"]["labels"] .= '];';
                $owmw_html["chart"]["hourly"]["temperatures"]["dataset_temperature"] .= '];';
                $owmw_html["chart"]["hourly"]["temperatures"]["dataset_feels_like"] .= '];';
                $owmw_html["chart"]["hourly"]["temperatures"]["dataset_dew_point"] .= '];';

                $rgb = owmw_hex2rgb($owmw_opt["chart_night_color"]);
                $owmw_opt["chart_night_bg_color"] = 'rgba(' . $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ', 0.25)';

                foreach ($owmw_html["chart"]["hourly"]["night"] as $i => $value) {
                    $owmw_html["chart"]["hourly"]["night_boxes"] .= ($i ? ',' : '');
                    $owmw_html["chart"]["hourly"]["night_boxes"] .= '
                    box' . $i . ': {
                      type: "box",
                      xMin: ' . $value . ',
                      xMax: ' . ($value + 1) . ',
                      backgroundColor: "' . $owmw_opt["chart_night_bg_color"] . '",
                      borderWidth: 0
                    }';
                }

                $owmw_html["chart"]["hourly"]["temperatures"]["config"] .= 'const hourly_temp_config_' . esc_attr($chart_id) . ' = { type: "line", options: hourly_temp_options_' . esc_attr($chart_id) . ', data: hourly_temp_data_' . esc_attr($chart_id) . ',};';
                $owmw_html["chart"]["hourly"]["temperatures"]["options"] .= 'const hourly_temp_options_' . esc_attr($chart_id) . ' = {
                color: "' . $owmw_opt["chart_text_color"] . '",
                borderWidth: 1,
                tension: 0.3,
                responsive: true,
                pointBackgroundColor: "rgba(0,0,0,0)",
                pointBorderWidth: 0,
                pointRadius: 10,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: "index" },
                plugins: {
                    annotation: { annotations: { ' . $owmw_html["chart"]["hourly"]["night_boxes"] . ' } },
                    tooltip: { position: "bottom", callbacks: { label: function(context) { var label = context.dataset.label || ""; if (label) { label += ": "; }
                    if (context.parsed.y !== null) { label += context.parsed.y + " ' . esc_html($owmw_data["temperature_unit_character"]) . '"; }
                    return label; } } } },
                scales: { x: { ticks: { color: "' . $owmw_opt["chart_text_color"] . '" }, grid: { display: false, } },
                          y: { ticks: { color: "' . $owmw_opt["chart_text_color"] . '" }, title: { color: "' . $owmw_opt["chart_text_color"] . '", display: true, text: "' . esc_html($owmw_data["temperature_unit_text"]) . '" } } }
                };';
                $owmw_html["chart"]["hourly"]["temperatures"]["data"] .= 'const hourly_temp_data_' . esc_attr($chart_id) . ' = {
                                                                      labels: hourly_temp_labels_' . esc_attr($chart_id) . ',
                                                                      datasets: [{
                                                                        label: "' . esc_attr__('Temperature', 'owm-weather') . '",
                                                                        data: hourly_temp_temperature_dataset_' . esc_attr($chart_id) . ',
                                                                        fill: false,
                                                                        borderColor: "' . esc_attr($owmw_opt["chart_temperature_color"]) . '",
                                                                        },{
                                                                        label: "' . esc_attr__('Feels Like', 'owm-weather') . '",
                                                                        data: hourly_temp_feels_like_dataset_' . esc_attr($chart_id) . ',
                                                                        fill: false,
                                                                        borderColor: "' . esc_attr($owmw_opt["chart_feels_like_color"]) . '",
                                                                        },{
                                                                        label: "' . esc_attr__('Dew Point', 'owm-weather') . '",
                                                                        data: hourly_temp_dew_point_dataset_' . esc_attr($chart_id) . ',
                                                                        fill: false,
                                                                        borderColor: "' . esc_attr($owmw_opt["chart_dew_point_color"]) . '",
                                                                      }]
                                                                    };';
                $owmw_html["chart"]["hourly"]["temperatures"]["chart"] .= 'jQuery(document).ready(function(){
                                                        var ctx = jQuery("#' . esc_attr($owmw_html["container_chart_hourly_temp_div"]) . '");
                                                        var hourlyTemperatureChart = new Chart(ctx, hourly_temp_config_' . esc_attr($chart_id) . ');
                                                        });';

                $owmw_html["chart"]["hourly"]["temperatures"]["container"] =
                '<div class="owmw-chart-container" style="position: relative; height:' . esc_attr($owmw_opt["chart_height"]) . 'px; width:100%">
            <canvas id="' . $owmw_html["container_chart_hourly_temp_div"] . '" style="' . owmw_css_color('background-color', $owmw_opt["chart_background_color"]) . owmw_css_border($owmw_opt["chart_border_color"], $owmw_opt["chart_border_width"], $owmw_opt["chart_border_style"], $owmw_opt["chart_border_radius"]) . '" aria-label="' . esc_attr__('Hourly Temperatures', 'owm-weather') . '" role="img"></canvas></div>';
                $owmw_html["chart"]["hourly"]["temperatures"]["script"] =
                $owmw_html["chart"]["hourly"]["temperatures"]["labels"] .
                $owmw_html["chart"]["hourly"]["temperatures"]["dataset_temperature"] .
                $owmw_html["chart"]["hourly"]["temperatures"]["dataset_feels_like"] .
                $owmw_html["chart"]["hourly"]["temperatures"]["dataset_dew_point"] .
                $owmw_html["chart"]["hourly"]["temperatures"]["data"] .
                $owmw_html["chart"]["hourly"]["temperatures"]["options"] .
                $owmw_html["chart"]["hourly"]["temperatures"]["config"] .
                $owmw_html["chart"]["hourly"]["temperatures"]["chart"];
            }

            //hourly precipitation
            $chart_id = owmw_unique_id_esc($owmw_opt["id"], '_');
            $owmw_html["container_chart_hourly_precip_div"] = 'owmw-hourly-precip-chart-canvas-' . esc_attr($chart_id);
            $owmw_html["chart"]["hourly"]["precipitation"]["labels"] = '';
            $owmw_html["chart"]["hourly"]["precipitation"]["dataset_cloudiness"] = '';
            $owmw_html["chart"]["hourly"]["precipitation"]["dataset_rain_chance"] = '';
            $owmw_html["chart"]["hourly"]["precipitation"]["dataset_humidity"] = '';
            $owmw_html["chart"]["hourly"]["precipitation"]["dataset_pressure"] = '';
            $owmw_html["chart"]["hourly"]["precipitation"]["config"] = '';
            $owmw_html["chart"]["hourly"]["precipitation"]["data"] = '';
            $owmw_html["chart"]["hourly"]["precipitation"]["options"] = '';
            $owmw_html["chart"]["hourly"]["precipitation"]["chart"] = '';
            $owmw_html["chart"]["hourly"]["precipitation"]["cmd"] = '';
            $owmw_html["chart"]["hourly"]["precipitation"]["container"] = '';
            $owmw_html["chart"]["hourly"]["precipitation"]["script"] = '';

            $rgb = owmw_hex2rgb($owmw_opt["chart_rain_chance_color"]);
            $owmw_opt["chart_rain_chance_bg_color"] = 'rgba(' . $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ', 0.3)';

            if ($owmw_opt["hours_forecast_no"] > 0) {
                $owmw_html["chart"]["hourly"]["precipitation"]["labels"] .= 'const hourly_precip_labels_' . esc_attr($chart_id) . ' = [';
                $owmw_html["chart"]["hourly"]["precipitation"]["dataset_cloudiness"] .= 'const hourly_precip_cloudiness_dataset_' . esc_attr($chart_id) . ' = [';
                $owmw_html["chart"]["hourly"]["precipitation"]["dataset_rain_chance"] .= 'const hourly_precip_rain_chance_dataset_' . esc_attr($chart_id) . ' = [';
                $owmw_html["chart"]["hourly"]["precipitation"]["dataset_humidity"] .= 'const hourly_precip_humidity_dataset_' . esc_attr($chart_id) . ' = [';
                $owmw_html["chart"]["hourly"]["precipitation"]["dataset_pressure"] .= 'const hourly_precip_pressure_dataset_' . esc_attr($chart_id) . ' = [';
                $cnt = 0;
                foreach ($owmw_data["hourly"] as $i => $value) {
                    if ($cnt < $owmw_opt["hours_forecast_no"]) {
                        $owmw_html["chart"]["hourly"]["precipitation"]["labels"] .= '"' . esc_attr($value["time"] != 'Now' ? date_i18n('D', $value["timestamp"]) . ' ' : '') . $value["time"] . '",';
                        $owmw_html["chart"]["hourly"]["precipitation"]["dataset_cloudiness"] .= '"' . esc_html(intval($value["cloudiness"])) . '",';
                        $owmw_html["chart"]["hourly"]["precipitation"]["dataset_rain_chance"] .= '"' . esc_html(intval($value["rain_chance"])) . '",';
                        $owmw_html["chart"]["hourly"]["precipitation"]["dataset_humidity"] .= '"' . esc_html(intval($value["humidity"])) . '",';
                        $owmw_html["chart"]["hourly"]["precipitation"]["dataset_pressure"] .= '"' . esc_html(floatval($value["pressure"])) . '",';
                        ++$cnt;
                    }
                }
                $owmw_html["chart"]["hourly"]["precipitation"]["labels"] .= '];';
                $owmw_html["chart"]["hourly"]["precipitation"]["dataset_cloudiness"] .= '];';
                $owmw_html["chart"]["hourly"]["precipitation"]["dataset_rain_chance"] .= '];';
                $owmw_html["chart"]["hourly"]["precipitation"]["dataset_humidity"] .= '];';
                $owmw_html["chart"]["hourly"]["precipitation"]["dataset_pressure"] .= '];';

                $owmw_html["chart"]["hourly"]["precipitation"]["config"] .= 'const hourly_precip_config_' . esc_attr($chart_id) . ' = { type: "line", options: hourly_precip_options_' . esc_attr($chart_id) . ', data: hourly_precip_data_' . esc_attr($chart_id) . ',};';
                $owmw_html["chart"]["hourly"]["precipitation"]["options"] .= 'const hourly_precip_options_' . esc_attr($chart_id) . ' = {
                color: "' . $owmw_opt["chart_text_color"] . '",
                borderWidth: 1,
                tension: 0.3,
                responsive: true,
                pointBackgroundColor: "rgba(0,0,0,0)",
                pointBorderWidth: 0,
                pointRadius: 10,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: "index" },
                plugins: {
                    annotation: { annotations: { ' . $owmw_html["chart"]["hourly"]["night_boxes"] . ' } },
                    tooltip: { position: "bottom", callbacks: { label: function(context) { var label = context.dataset.label || ""; if (label) { label += ": "; } if (context.parsed.y !== null) { label += context.parsed.y; } if (label.startsWith("' . __('Pressure', 'owm-weather') . '")) { label += " ' . $owmw_data["pressure_unit"] . '"; } else { label += "%"; } return label; } } } },
                scales: { x:  { ticks: { color: "' . $owmw_opt["chart_text_color"] . '" }, grid: { display: false, } },
                          yl: { ticks: { color: "' . $owmw_opt["chart_text_color"] . '", callback: function(value, index, ticks) { return value + "%"; } }, position: "left" },
                          yr: { ticks: { color: "' . $owmw_opt["chart_text_color"] . '" }, title: { color: "' . $owmw_opt["chart_text_color"] . '", display: true, text: "' . __('Pressure', 'owm-weather') . ' (' . $owmw_data["pressure_unit"] . ')" }, position: "right", grid: { display: false, } }
                        }
                };';
                $owmw_html["chart"]["hourly"]["precipitation"]["data"] .= 'const hourly_precip_data_' . esc_attr($chart_id) . ' = {
                                                                      labels: hourly_precip_labels_' . esc_attr($chart_id) . ',
                                                                      datasets: [{
                                                                        label: "' . esc_attr__('Cloudiness', 'owm-weather') . '",
                                                                        data: hourly_precip_cloudiness_dataset_' . esc_attr($chart_id) . ',
                                                                        fill: true,
                                                                        borderColor: "' . esc_attr($owmw_opt["chart_cloudiness_color"]) . '",
                                                                        yAxisID: "yl",
                                                                        },{
                                                                        label: "' . esc_attr__('Rain Chance', 'owm-weather') . '",
                                                                        data: hourly_precip_rain_chance_dataset_' . esc_attr($chart_id) . ',
                                                                        fill: true,
                                                                        backgroundColor: "' . esc_attr($owmw_opt["chart_rain_chance_bg_color"]) . '",
                                                                        borderColor: "' . esc_attr($owmw_opt["chart_rain_chance_color"]) . '",
                                                                        yAxisID: "yl",
                                                                        },{
                                                                        label: "' . esc_attr__('Humidity', 'owm-weather') . '",
                                                                        data: hourly_precip_humidity_dataset_' . esc_attr($chart_id) . ',
                                                                        fill: false,
                                                                        borderColor: "' . esc_attr($owmw_opt["chart_humidity_color"]) . '",
                                                                        yAxisID: "yl",
                                                                        },{
                                                                        label: "' . esc_attr__('Pressure', 'owm-weather') . '",
                                                                        data: hourly_precip_pressure_dataset_' . esc_attr($chart_id) . ',
                                                                        fill: false,
                                                                        borderColor: "' . esc_attr($owmw_opt["chart_pressure_color"]) . '",
                                                                        yAxisID: "yr",
                                                                        }]
                                                                    };';
                $owmw_html["chart"]["hourly"]["precipitation"]["chart"] .= 'jQuery(document).ready(function(){
                                                        var ctx = jQuery("#' . esc_attr($owmw_html["container_chart_hourly_precip_div"]) . '");
                                                        var hourlyPrecipitationChart = new Chart(ctx, hourly_precip_config_' . esc_attr($chart_id) . ');
                                                        });';

                $owmw_html["chart"]["hourly"]["precipitation"]["container"] =
                '<div class="owmw-chart-container" style="position: relative; height:' . esc_attr($owmw_opt["chart_height"]) . 'px; width:100%">
                <canvas id="' . $owmw_html["container_chart_hourly_precip_div"] . '" style="' . owmw_css_color('background-color', $owmw_opt["chart_background_color"]) . owmw_css_border($owmw_opt["chart_border_color"], $owmw_opt["chart_border_width"], $owmw_opt["chart_border_style"], $owmw_opt["chart_border_radius"]) . '" aria-label="' . esc_attr__('Hourly precipitation', 'owm-weather') . '" role="img"></canvas></div>';
                $owmw_html["chart"]["hourly"]["precipitation"]["script"] =
                $owmw_html["chart"]["hourly"]["precipitation"]["labels"] .
                $owmw_html["chart"]["hourly"]["precipitation"]["dataset_cloudiness"] .
                $owmw_html["chart"]["hourly"]["precipitation"]["dataset_rain_chance"] .
                $owmw_html["chart"]["hourly"]["precipitation"]["dataset_humidity"] .
                $owmw_html["chart"]["hourly"]["precipitation"]["dataset_pressure"] .
                $owmw_html["chart"]["hourly"]["precipitation"]["data"] .
                $owmw_html["chart"]["hourly"]["precipitation"]["options"] .
                $owmw_html["chart"]["hourly"]["precipitation"]["config"] .
                $owmw_html["chart"]["hourly"]["precipitation"]["chart"];
            }

            //hourly precipitation amount
            $chart_id = owmw_unique_id_esc($owmw_opt["id"], '_');
            $owmw_html["container_chart_hourly_precip_amt_div"] = 'owmw-hourly-precip-amt-chart-canvas-' . esc_attr($chart_id);
            $owmw_html["chart"]["hourly"]["precip_amt"]["labels"] = '';
            $owmw_html["chart"]["hourly"]["precip_amt"]["dataset_rain"] = '';
            $owmw_html["chart"]["hourly"]["precip_amt"]["dataset_snow"] = '';
            $owmw_html["chart"]["hourly"]["precip_amt"]["config"] = '';
            $owmw_html["chart"]["hourly"]["precip_amt"]["data"] = '';
            $owmw_html["chart"]["hourly"]["precip_amt"]["options"] = '';
            $owmw_html["chart"]["hourly"]["precip_amt"]["chart"] = '';
            $owmw_html["chart"]["hourly"]["precip_amt"]["cmd"] = '';
            $owmw_html["chart"]["hourly"]["precip_amt"]["container"] = '';
            $owmw_html["chart"]["hourly"]["precip_amt"]["script"] = '';

            $rgb = owmw_hex2rgb($owmw_opt["chart_rain_amt_color"]);
            $owmw_opt["chart_rain_amt_bg_color"] = 'rgba(' . $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ', 0.3)';
            $rgb = owmw_hex2rgb($owmw_opt["chart_snow_amt_color"]);
            $owmw_opt["chart_snow_amt_bg_color"] = 'rgba(' . $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ', 0.3)';

            if ($owmw_opt["hours_forecast_no"] > 0) {
                $owmw_html["chart"]["hourly"]["precip_amt"]["labels"] .= 'const hourly_precip_amt_labels_' . esc_attr($chart_id) . ' = [';
                $owmw_html["chart"]["hourly"]["precip_amt"]["dataset_rain"] .= 'const hourly_precip_amt_rain_dataset_' . esc_attr($chart_id) . ' = [';
                $owmw_html["chart"]["hourly"]["precip_amt"]["dataset_snow"] .= 'const hourly_precip_amt_snow_dataset_' . esc_attr($chart_id) . ' = [';
                $cnt = 0;
                foreach ($owmw_data["hourly"] as $i => $value) {
                    if ($cnt < $owmw_opt["hours_forecast_no"]) {
                        $owmw_html["chart"]["hourly"]["precip_amt"]["labels"] .= '"' . esc_attr($value["time"] != 'Now' ? date_i18n('D', $value["timestamp"]) . ' ' : '') . $value["time"] . '",';
                        $owmw_html["chart"]["hourly"]["precip_amt"]["dataset_rain"] .= '"' . esc_html(floatval($value["rain"])) . '",';
                        $owmw_html["chart"]["hourly"]["precip_amt"]["dataset_snow"] .= '"' . esc_html(floatval($value["snow"])) . '",';
                        ++$cnt;
                    }
                }
                $owmw_html["chart"]["hourly"]["precip_amt"]["labels"] .= '];';
                $owmw_html["chart"]["hourly"]["precip_amt"]["dataset_rain"] .= '];';
                $owmw_html["chart"]["hourly"]["precip_amt"]["dataset_snow"] .= '];';

                $owmw_html["chart"]["hourly"]["precip_amt"]["config"] .= 'const hourly_precip_amt_config_' . esc_attr($chart_id) . ' = { type: "line", options: hourly_precip_amt_options_' . esc_attr($chart_id) . ', data: hourly_precip_amt_data_' . esc_attr($chart_id) . ',};';
                $owmw_html["chart"]["hourly"]["precip_amt"]["options"] .= 'const hourly_precip_amt_options_' . esc_attr($chart_id) . ' = {
                color: "' . $owmw_opt["chart_text_color"] . '",
                borderWidth: 1,
                tension: 0.3,
                responsive: true,
                pointBackgroundColor: "rgba(0,0,0,0)",
                pointBorderWidth: 0,
                pointRadius: 10,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: "index" },
                plugins: {
                    annotation: { annotations: { ' . $owmw_html["chart"]["hourly"]["night_boxes"] . ' } },
                    tooltip: { position: "bottom", callbacks: { label: function(context) { var label = context.dataset.label || ""; if (label) { label += ": "; }
                    if (context.parsed.y !== null) { label += context.parsed.y + " ' . $owmw_data["precipitation_unit"] . '"; }
                    return label; } } } },
                scales: { x: { ticks: { color: "' . $owmw_opt["chart_text_color"] . '" }, grid: { display: false, } },
                          y: { beginAtZero: true, ticks: { color: "' . $owmw_opt["chart_text_color"] . '", callback: function(value, index, ticks) { return value + " ' . $owmw_data["precipitation_unit"] . '"; } }, position: "left" } }
                };';
                $owmw_html["chart"]["hourly"]["precip_amt"]["data"] .= 'const hourly_precip_amt_data_' . esc_attr($chart_id) . ' = {
                                                                      labels: hourly_precip_amt_labels_' . esc_attr($chart_id) . ',
                                                                      datasets: [{
                                                                        label: "' . esc_attr__('Rain', 'owm-weather') . '",
                                                                        data: hourly_precip_amt_rain_dataset_' . esc_attr($chart_id) . ',
                                                                        fill: true,
                                                                        borderColor: "' . esc_attr($owmw_opt["chart_rain_amt_color"]) . '",
                                                                        backgroundColor: "' . esc_attr($owmw_opt["chart_rain_amt_bg_color"]) . '",
                                                                        yAxisID: "y",
                                                                        },{
                                                                        label: "' . esc_attr__('Snow', 'owm-weather') . '",
                                                                        data: hourly_precip_amt_snow_dataset_' . esc_attr($chart_id) . ',
                                                                        fill: true,
                                                                        borderColor: "' . esc_attr($owmw_opt["chart_snow_amt_color"]) . '",
                                                                        backgroundColor: "' . esc_attr($owmw_opt["chart_snow_amt_bg_color"]) . '",
                                                                        yAxisID: "y",
                                                                        }]
                                                                    };';
                $owmw_html["chart"]["hourly"]["precip_amt"]["chart"] .= 'jQuery(document).ready(function(){
                                                        var ctx = jQuery("#' . esc_attr($owmw_html["container_chart_hourly_precip_amt_div"]) . '");
                                                        var hourlyPrecipitationAmountChart = new Chart(ctx, hourly_precip_amt_config_' . esc_attr($chart_id) . ');
                                                        });';

                $owmw_html["chart"]["hourly"]["precip_amt"]["container"] =
                '<div class="owmw-chart-container" style="position: relative; height:' . esc_attr($owmw_opt["chart_height"]) . 'px; width:100%">
                <canvas id="' . $owmw_html["container_chart_hourly_precip_amt_div"] . '" style="' . owmw_css_color('background-color', $owmw_opt["chart_background_color"]) . owmw_css_border($owmw_opt["chart_border_color"], $owmw_opt["chart_border_width"], $owmw_opt["chart_border_style"], $owmw_opt["chart_border_radius"]) . '" aria-label="' . esc_attr__('Hourly liquid precipitation amount', 'owm-weather') . '" role="img"></canvas></div>';
                $owmw_html["chart"]["hourly"]["precip_amt"]["script"] =
                $owmw_html["chart"]["hourly"]["precip_amt"]["labels"] .
                $owmw_html["chart"]["hourly"]["precip_amt"]["dataset_rain"] .
                $owmw_html["chart"]["hourly"]["precip_amt"]["dataset_snow"] .
                $owmw_html["chart"]["hourly"]["precip_amt"]["data"] .
                $owmw_html["chart"]["hourly"]["precip_amt"]["options"] .
                $owmw_html["chart"]["hourly"]["precip_amt"]["config"] .
                $owmw_html["chart"]["hourly"]["precip_amt"]["chart"];
            }

            //hourly wind speed
            $chart_id = owmw_unique_id_esc($owmw_opt["id"], '_');
            $owmw_html["container_chart_hourly_wind_div"] = 'owmw-hourly-wind-chart-canvas-' . esc_attr($chart_id);
            $owmw_html["chart"]["hourly"]["wind"]["labels"] = '';
            $owmw_html["chart"]["hourly"]["wind"]["dataset_wind"] = '';
            $owmw_html["chart"]["hourly"]["wind"]["dataset_wind_gust"] = '';
            $owmw_html["chart"]["hourly"]["wind"]["loadImages"] = '';
            $owmw_html["chart"]["hourly"]["wind"]["loadImagePoints"] = '';
            $owmw_html["chart"]["hourly"]["wind"]["config"] = '';
            $owmw_html["chart"]["hourly"]["wind"]["data"] = '';
            $owmw_html["chart"]["hourly"]["wind"]["options"] = '';
            $owmw_html["chart"]["hourly"]["wind"]["chart"] = '';
            $owmw_html["chart"]["hourly"]["wind"]["cmd"] = '';
            $owmw_html["chart"]["hourly"]["wind"]["container"] = '';
            $owmw_html["chart"]["hourly"]["wind"]["script"] = '';

            if ($owmw_opt["hours_forecast_no"] > 0) {
                $owmw_html["chart"]["hourly"]["wind"]["labels"] .= 'const hourly_wind_labels_' . esc_attr($chart_id) . ' = [';
                $owmw_html["chart"]["hourly"]["wind"]["dataset_wind"] .= 'const hourly_wind_speed_dataset_' . esc_attr($chart_id) . ' = [';
                $owmw_html["chart"]["hourly"]["wind"]["dataset_wind_gust"] .= 'const hourly_wind_gust_dataset_' . esc_attr($chart_id) . ' = [';
                $cnt = 0;
                foreach ($owmw_data["hourly"] as $i => $value) {
                    if ($cnt < $owmw_opt["hours_forecast_no"]) {
                        $owmw_html["chart"]["hourly"]["wind"]["labels"] .= '"' . esc_attr($value["time"] != 'Now' ? date_i18n('D', $value["timestamp"]) . ' ' : '') . $value["time"] . '",';
                        $owmw_html["chart"]["hourly"]["wind"]["dataset_wind"] .= '"' . esc_html(intval($value["wind_speed"])) . '",';
                        $owmw_html["chart"]["hourly"]["wind"]["dataset_wind_gust"] .= '"' . esc_html(intval($value["wind_gust"])) . '",';
                        $varName = "owmw_wind_direction_svg_" . esc_attr($chart_id) . "_" . $cnt;
                        $owmw_html["chart"]["hourly"]["wind"]["loadImages"] .= ($cnt > 0 ? ',' : '') . 'loadImage("' . owmw_chart_wind_direction_icon($value["wind_degrees"], null, $owmw_opt["wind_icon_direction"], true) . '")';
                        $owmw_html["chart"]["hourly"]["wind"]["loadImagePoints"] .= ($cnt ? "," : "") . 'images[' . $cnt . ']';
                        ++$cnt;
                    }
                }
                $owmw_html["chart"]["hourly"]["wind"]["labels"] .= '];';
                $owmw_html["chart"]["hourly"]["wind"]["dataset_wind"] .= '];';
                $owmw_html["chart"]["hourly"]["wind"]["dataset_wind_gust"] .= '];';

                $owmw_html["chart"]["hourly"]["wind"]["config"] .= 'const hourly_wind_config_' . esc_attr($chart_id) . ' = { type: "line", options: hourly_wind_options_' . esc_attr($chart_id) . ', data: hourly_wind_data_' . esc_attr($chart_id) . ',};';
                $owmw_html["chart"]["hourly"]["wind"]["options"] .= 'const hourly_wind_options_' . esc_attr($chart_id) . ' = {
                color: "' . $owmw_opt["chart_text_color"] . '",
                borderWidth: 1,
                tension: 0.3,
                responsive: true,
                pointBackgroundColor: "rgba(0,0,0,0)",
                pointBorderWidth: 0,
                pointRadius: 10,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: "index" },
                plugins: {
                    annotation: { annotations: { ' . $owmw_html["chart"]["hourly"]["night_boxes"] . ' } },
                    tooltip: { position: "bottom", callbacks: { label: function(context) { var label = context.dataset.label || ""; if (label) { label += ": "; }
                    if (context.parsed.y !== null) { label += context.parsed.y + " ' . $owmw_data["wind_speed_unit"] . '"; }
                    return label; } } } },
                scales: { x: { ticks: { color: "' . $owmw_opt["chart_text_color"] . '" }, grid: { display: false, } },
                          y: { beginAtZero: true, ticks: { color: "' . $owmw_opt["chart_text_color"] . '", callback: function(value, index, ticks) { return value + " ' . $owmw_data["wind_speed_unit"] . '"; } }, position: "left" } }
                };';
                $owmw_html["chart"]["hourly"]["wind"]["data"] .= 'const hourly_wind_data_' . esc_attr($chart_id) . ' = {
                                                                      labels: hourly_wind_labels_' . esc_attr($chart_id) . ',
                                                                      datasets: [{
                                                                        label: "' . esc_attr__('Wind Speed', 'owm-weather') . '",
                                                                        data: hourly_wind_speed_dataset_' . esc_attr($chart_id) . ',
                                                                        fill: false,
                                                                        borderColor: "' . esc_attr($owmw_opt["chart_wind_speed_color"]) . '",
                                                                        pointStyle: [' . $owmw_html["chart"]["hourly"]["wind"]["loadImagePoints"] . '],
                                                                        yAxisID: "y",
                                                                        },{
                                                                        label: "' . esc_attr__('Wind Gust', 'owm-weather') . '",
                                                                        data: hourly_wind_gust_dataset_' . esc_attr($chart_id) . ',
                                                                        fill: false,
                                                                        borderColor: "' . esc_attr($owmw_opt["chart_wind_gust_color"]) . '",
                                                                        yAxisID: "y",
                                                                        }]
                                                                    };';
                $owmw_html["chart"]["hourly"]["wind"]["chart"] .= 'jQuery(document).ready(function(){
                                        Promise.all([
                                        ' . $owmw_html["chart"]["hourly"]["wind"]["loadImages"] .
                                        '
                                          ])
                                          .then(function(images){
                                        ' . $owmw_html["chart"]["hourly"]["wind"]["labels"] .
                                          $owmw_html["chart"]["hourly"]["wind"]["dataset_wind"] .
                                          $owmw_html["chart"]["hourly"]["wind"]["dataset_wind_gust"] .
                                          $owmw_html["chart"]["hourly"]["wind"]["data"] .
                                          $owmw_html["chart"]["hourly"]["wind"]["options"] .
                                          $owmw_html["chart"]["hourly"]["wind"]["config"] . '
                                            var ctx = jQuery("#' . esc_attr($owmw_html["container_chart_hourly_wind_div"]) . '");
                                            var hourlyWindSpeedChart = new Chart(ctx, hourly_wind_config_' . esc_attr($chart_id) . ');
                                          })
                                          .catch( function(e) { console.error(e); });
                                        });';

                $owmw_html["chart"]["hourly"]["wind"]["container"] =
                '<div class="owmw-chart-container" style="position: relative; height:' . esc_attr($owmw_opt["chart_height"]) . 'px; width:100%">
                <canvas id="' . $owmw_html["container_chart_hourly_wind_div"] . '" style="' . owmw_css_color('background-color', $owmw_opt["chart_background_color"]) . owmw_css_border($owmw_opt["chart_border_color"], $owmw_opt["chart_border_width"], $owmw_opt["chart_border_style"], $owmw_opt["chart_border_radius"]) . '" aria-label="' . esc_attr__('Hourly wind speed', 'owm-weather') . '" role="img"></canvas></div>';
                $owmw_html["chart"]["hourly"]["wind"]["script"] = $owmw_html["chart"]["hourly"]["wind"]["chart"];
            }

            //daily temperatures
            $chart_id = owmw_unique_id_esc($owmw_opt["id"], '_');
            $owmw_html["container_chart_daily_temp_div"] = 'owmw-daily-temp-chart-canvas-' . esc_attr($chart_id);
            $owmw_html["chart"]["daily"]["temperatures"]["labels"] = '';
            $owmw_html["chart"]["daily"]["temperatures"]["dataset_temperature"] = '';
            $owmw_html["chart"]["daily"]["temperatures"]["dataset_feels_like"] = '';
            $owmw_html["chart"]["daily"]["temperatures"]["config"] = '';
            $owmw_html["chart"]["daily"]["temperatures"]["data"] = '';
            $owmw_html["chart"]["daily"]["temperatures"]["options"] = '';
            $owmw_html["chart"]["daily"]["temperatures"]["chart"] = '';
            $owmw_html["chart"]["daily"]["temperatures"]["cmd"] = '';
            $owmw_html["chart"]["daily"]["temperatures"]["container"] = '';
            $owmw_html["chart"]["daily"]["temperatures"]["script"] = '';

            if ($owmw_opt["days_forecast_no"] > 0) {
                $owmw_html["chart"]["daily"]["temperatures"]["labels"] .= 'const daily_temp_labels_' . esc_attr($chart_id) . ' = [';
                $owmw_html["chart"]["daily"]["temperatures"]["dataset_temperature"] .= 'const daily_temp_temperature_dataset_' . esc_attr($chart_id) . ' = [';
                $owmw_html["chart"]["daily"]["temperatures"]["dataset_feels_like"] .= 'const daily_temp_feels_like_dataset_' . esc_attr($chart_id) . ' = [';
                $cnt = 0;
                foreach ($owmw_data["forecast"] as $i => $value) {
                    if (is_numeric($i)) {
                        $owmw_html["chart"]["daily"]["temperatures"]["labels"] .= '"' . esc_attr($value["time"] != 'Now' ? date_i18n('D', $value["timestamp"]) . ' ' : '') . $value["time"] . '",';
                        $owmw_html["chart"]["daily"]["temperatures"]["dataset_temperature"] .= '"' . esc_html($value["temperature"]) . '",';
                        $owmw_html["chart"]["daily"]["temperatures"]["dataset_feels_like"] .= '"' . esc_html($value["feels_like"]) . '",';
                        if ($value["day_night"] == 'night') {
                            $owmw_html["chart"]["daily"]["night"][] = $cnt;
                        }
                        ++$cnt;
                    }
                }
                $owmw_html["chart"]["daily"]["temperatures"]["labels"] .= '];';
                $owmw_html["chart"]["daily"]["temperatures"]["dataset_temperature"] .= '];';
                $owmw_html["chart"]["daily"]["temperatures"]["dataset_feels_like"] .= '];';

                $rgb = owmw_hex2rgb($owmw_opt["chart_night_color"]);
                $owmw_opt["chart_night_bg_color"] = 'rgba(' . $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ', 0.25)';

                foreach ($owmw_html["chart"]["daily"]["night"] as $i => $value) {
                    $owmw_html["chart"]["daily"]["night_boxes"] .= ($i ? ',' : '');
                    $owmw_html["chart"]["daily"]["night_boxes"] .= '
                    box' . $i . ': {
                      type: "box",
                      xMin: ' . $value . ',
                      xMax: ' . ($value + 1) . ',
                      backgroundColor: "' . $owmw_opt["chart_night_bg_color"] . '",
                      borderWidth: 0
                    }';
                }

                $owmw_html["chart"]["daily"]["temperatures"]["config"] .= 'const daily_temp_config_' . esc_attr($chart_id) . ' = { type: "line", options: daily_temp_options_' . esc_attr($chart_id) . ', data: daily_temp_data_' . esc_attr($chart_id) . ',};';
                $owmw_html["chart"]["daily"]["temperatures"]["options"] .= 'const daily_temp_options_' . esc_attr($chart_id) . ' = {
                color: "' . $owmw_opt["chart_text_color"] . '",
                borderWidth: 1,
                tension: 0.3,
                responsive: true,
                pointBackgroundColor: "rgba(0,0,0,0)",
                pointBorderWidth: 0,
                pointRadius: 10,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: "index" },
                plugins: {
                    annotation: { annotations: { ' . $owmw_html["chart"]["daily"]["night_boxes"] . ' } },
                    tooltip: { position: "bottom", callbacks: { label: function(context) { var label = context.dataset.label || ""; if (label) { label += ": "; }
                    if (context.parsed.y !== null) { label += context.parsed.y + " ' . esc_html($owmw_data["temperature_unit_character"]) . '"; }
                    return label; } } } },
                scales: { x: { ticks: { color: "' . $owmw_opt["chart_text_color"] . '" }, grid: { display: false, } },
                          y: { ticks: { color: "' . $owmw_opt["chart_text_color"] . '" }, title: { color: "' . $owmw_opt["chart_text_color"] . '", display: true, text: "' . esc_html($owmw_data["temperature_unit_text"]) . '" } } }
                };';
                $owmw_html["chart"]["daily"]["temperatures"]["data"] .= 'const daily_temp_data_' . esc_attr($chart_id) . ' = {
                                                                      labels: daily_temp_labels_' . esc_attr($chart_id) . ',
                                                                      datasets: [{
                                                                        label: "' . esc_attr__('Temperature', 'owm-weather') . '",
                                                                        data: daily_temp_temperature_dataset_' . esc_attr($chart_id) . ',
                                                                        fill: false,
                                                                        borderColor: "' . esc_attr($owmw_opt["chart_temperature_color"]) . '",
                                                                        },{
                                                                        label: "' . esc_attr__('Feels Like', 'owm-weather') . '",
                                                                        data: daily_temp_feels_like_dataset_' . esc_attr($chart_id) . ',
                                                                        fill: false,
                                                                        borderColor: "' . esc_attr($owmw_opt["chart_feels_like_color"]) . '",
                                                                      }]
                                                                    };';
                $owmw_html["chart"]["daily"]["temperatures"]["chart"] .= 'jQuery(document).ready(function(){
                                                        var ctx = jQuery("#' . esc_attr($owmw_html["container_chart_daily_temp_div"]) . '");
                                                        var dailyTemperatureChart = new Chart(ctx, daily_temp_config_' . esc_attr($chart_id) . ');
                                                        });';

                $owmw_html["chart"]["daily"]["temperatures"]["container"] =
                '<div class="owmw-chart-container" style="position: relative; height:' . esc_attr($owmw_opt["chart_height"]) . 'px; width:100%">
                <canvas id="' . $owmw_html["container_chart_daily_temp_div"] . '" style="' . owmw_css_color('background-color', $owmw_opt["chart_background_color"]) . owmw_css_border($owmw_opt["chart_border_color"], $owmw_opt["chart_border_width"], $owmw_opt["chart_border_style"], $owmw_opt["chart_border_radius"]) . '" aria-label="' . esc_attr__('daily Temperatures', 'owm-weather') . '" role="img"></canvas></div>';
                $owmw_html["chart"]["daily"]["temperatures"]["script"] =
                $owmw_html["chart"]["daily"]["temperatures"]["labels"] .
                $owmw_html["chart"]["daily"]["temperatures"]["dataset_temperature"] .
                $owmw_html["chart"]["daily"]["temperatures"]["dataset_feels_like"] .
                $owmw_html["chart"]["daily"]["temperatures"]["data"] .
                $owmw_html["chart"]["daily"]["temperatures"]["options"] .
                $owmw_html["chart"]["daily"]["temperatures"]["config"] .
                $owmw_html["chart"]["daily"]["temperatures"]["chart"];
            }

            //daily precipitation
            $chart_id = owmw_unique_id_esc($owmw_opt["id"], '_');
            $owmw_html["container_chart_daily_precip_div"] = 'owmw-daily-precip-chart-canvas-' . esc_attr($chart_id);
            $owmw_html["chart"]["daily"]["precipitation"]["labels"] = '';
            $owmw_html["chart"]["daily"]["precipitation"]["dataset_cloudiness"] = '';
            $owmw_html["chart"]["daily"]["precipitation"]["dataset_rain_chance"] = '';
            $owmw_html["chart"]["daily"]["precipitation"]["dataset_humidity"] = '';
            $owmw_html["chart"]["daily"]["precipitation"]["dataset_pressure"] = '';
            $owmw_html["chart"]["daily"]["precipitation"]["config"] = '';
            $owmw_html["chart"]["daily"]["precipitation"]["data"] = '';
            $owmw_html["chart"]["daily"]["precipitation"]["options"] = '';
            $owmw_html["chart"]["daily"]["precipitation"]["chart"] = '';
            $owmw_html["chart"]["daily"]["precipitation"]["cmd"] = '';
            $owmw_html["chart"]["daily"]["precipitation"]["container"] = '';
            $owmw_html["chart"]["daily"]["precipitation"]["script"] = '';

            $rgb = owmw_hex2rgb($owmw_opt["chart_rain_chance_color"]);
            $owmw_opt["chart_rain_chance_bg_color"] = 'rgba(' . $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ', 0.3)';

            if ($owmw_opt["days_forecast_no"] > 0) {
                $owmw_html["chart"]["daily"]["precipitation"]["labels"] .= 'const daily_precip_labels_' . esc_attr($chart_id) . ' = [';
                $owmw_html["chart"]["daily"]["precipitation"]["dataset_cloudiness"] .= 'const daily_precip_cloudiness_dataset_' . esc_attr($chart_id) . ' = [';
                $owmw_html["chart"]["daily"]["precipitation"]["dataset_rain_chance"] .= 'const daily_precip_rain_chance_dataset_' . esc_attr($chart_id) . ' = [';
                $owmw_html["chart"]["daily"]["precipitation"]["dataset_humidity"] .= 'const daily_precip_humidity_dataset_' . esc_attr($chart_id) . ' = [';
                $owmw_html["chart"]["daily"]["precipitation"]["dataset_pressure"] .= 'const daily_precip_pressure_dataset_' . esc_attr($chart_id) . ' = [';
                $cnt = 0;
                foreach ($owmw_data["forecast"] as $i => $value) {
                    if (is_numeric($i)) {
                        $owmw_html["chart"]["daily"]["precipitation"]["labels"] .= '"' . esc_attr($value["time"] != 'Now' ? date_i18n('D', $value["timestamp"]) . ' ' : '') . $value["time"] . '",';
                        $owmw_html["chart"]["daily"]["precipitation"]["dataset_cloudiness"] .= '"' . esc_html(intval($value["cloudiness"])) . '",';
                        $owmw_html["chart"]["daily"]["precipitation"]["dataset_rain_chance"] .= '"' . esc_html(intval($value["rain_chance"])) . '",';
                        $owmw_html["chart"]["daily"]["precipitation"]["dataset_humidity"] .= '"' . esc_html(intval($value["humidity"])) . '",';
                        $owmw_html["chart"]["daily"]["precipitation"]["dataset_pressure"] .= '"' . esc_html(floatval($value["pressure"])) . '",';
                        ++$cnt;
                    }
                }
                $owmw_html["chart"]["daily"]["precipitation"]["labels"] .= '];';
                $owmw_html["chart"]["daily"]["precipitation"]["dataset_cloudiness"] .= '];';
                $owmw_html["chart"]["daily"]["precipitation"]["dataset_rain_chance"] .= '];';
                $owmw_html["chart"]["daily"]["precipitation"]["dataset_humidity"] .= '];';
                $owmw_html["chart"]["daily"]["precipitation"]["dataset_pressure"] .= '];';

                $owmw_html["chart"]["daily"]["precipitation"]["config"] .= 'const daily_precip_config_' . esc_attr($chart_id) . ' = { type: "line", options: daily_precip_options_' . esc_attr($chart_id) . ', data: daily_precip_data_' . esc_attr($chart_id) . ',};';
                $owmw_html["chart"]["daily"]["precipitation"]["options"] .= 'const daily_precip_options_' . esc_attr($chart_id) . ' = {
                color: "' . $owmw_opt["chart_text_color"] . '",
                borderWidth: 1,
                tension: 0.3,
                responsive: true,
                pointBackgroundColor: "rgba(0,0,0,0)",
                pointBorderWidth: 0,
                pointRadius: 10,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: "index" },
                plugins: {
                    annotation: { annotations: { ' . $owmw_html["chart"]["daily"]["night_boxes"] . ' } },
                    tooltip: { position: "bottom", callbacks: { label: function(context) { var label = context.dataset.label || ""; if (label) { label += ": "; } if (context.parsed.y !== null) { label += context.parsed.y; } if (label.startsWith("' . __('Pressure', 'owm-weather') . '")) { label += " ' . $owmw_data["pressure_unit"] . '"; } else { label += "%"; } return label; } } } },
                scales: { x:  { ticks: { color: "' . $owmw_opt["chart_text_color"] . '" }, grid: { display: false, } },
                          yl: { ticks: { color: "' . $owmw_opt["chart_text_color"] . '", callback: function(value, index, ticks) { return value + "%"; } }, position: "left" },
                          yr: { ticks: { color: "' . $owmw_opt["chart_text_color"] . '" }, title: { color: "' . $owmw_opt["chart_text_color"] . '", display: true, text: "' . __('Pressure', 'owm-weather') . ' (' . $owmw_data["pressure_unit"] . ')" }, position: "right", grid: { display: false, } }
                        }
                };';
                $owmw_html["chart"]["daily"]["precipitation"]["data"] .= 'const daily_precip_data_' . esc_attr($chart_id) . ' = {
                                                                      labels: daily_precip_labels_' . esc_attr($chart_id) . ',
                                                                      datasets: [{
                                                                        label: "' . esc_attr__('Cloudiness', 'owm-weather') . '",
                                                                        data: daily_precip_cloudiness_dataset_' . esc_attr($chart_id) . ',
                                                                        fill: true,
                                                                        borderColor: "' . esc_attr($owmw_opt["chart_cloudiness_color"]) . '",
                                                                        yAxisID: "yl",
                                                                        },{
                                                                        label: "' . esc_attr__('Rain Chance', 'owm-weather') . '",
                                                                        data: daily_precip_rain_chance_dataset_' . esc_attr($chart_id) . ',
                                                                        fill: true,
                                                                        backgroundColor: "' . esc_attr($owmw_opt["chart_rain_chance_bg_color"]) . '",
                                                                        borderColor: "' . esc_attr($owmw_opt["chart_rain_chance_color"]) . '",
                                                                        yAxisID: "yl",
                                                                        },{
                                                                        label: "' . esc_attr__('Humidity', 'owm-weather') . '",
                                                                        data: daily_precip_humidity_dataset_' . esc_attr($chart_id) . ',
                                                                        fill: false,
                                                                        borderColor: "' . esc_attr($owmw_opt["chart_humidity_color"]) . '",
                                                                        yAxisID: "yl",
                                                                        },{
                                                                        label: "' . esc_attr__('Pressure', 'owm-weather') . '",
                                                                        data: daily_precip_pressure_dataset_' . esc_attr($chart_id) . ',
                                                                        fill: false,
                                                                        borderColor: "' . esc_attr($owmw_opt["chart_pressure_color"]) . '",
                                                                        yAxisID: "yr",
                                                                        }]
                                                                    };';
                $owmw_html["chart"]["daily"]["precipitation"]["chart"] .= 'jQuery(document).ready(function(){
                                                        var ctx = jQuery("#' . esc_attr($owmw_html["container_chart_daily_precip_div"]) . '");
                                                        var dailyPrecipitationChart = new Chart(ctx, daily_precip_config_' . esc_attr($chart_id) . ');
                                                        });';

                $owmw_html["chart"]["daily"]["precipitation"]["container"] =
                '<div class="owmw-chart-container" style="position: relative; height:' . esc_attr($owmw_opt["chart_height"]) . 'px; width:100%">
                <canvas id="' . $owmw_html["container_chart_daily_precip_div"] . '" style="' . owmw_css_color('background-color', $owmw_opt["chart_background_color"]) . owmw_css_border($owmw_opt["chart_border_color"], $owmw_opt["chart_border_width"], $owmw_opt["chart_border_style"], $owmw_opt["chart_border_radius"]) . '" aria-label="' . esc_attr__('daily precipitation', 'owm-weather') . '" role="img"></canvas></div>';
                $owmw_html["chart"]["daily"]["precipitation"]["script"] =
                $owmw_html["chart"]["daily"]["precipitation"]["labels"] .
                $owmw_html["chart"]["daily"]["precipitation"]["dataset_cloudiness"] .
                $owmw_html["chart"]["daily"]["precipitation"]["dataset_rain_chance"] .
                $owmw_html["chart"]["daily"]["precipitation"]["dataset_humidity"] .
                $owmw_html["chart"]["daily"]["precipitation"]["dataset_pressure"] .
                $owmw_html["chart"]["daily"]["precipitation"]["data"] .
                $owmw_html["chart"]["daily"]["precipitation"]["options"] .
                $owmw_html["chart"]["daily"]["precipitation"]["config"] .
                $owmw_html["chart"]["daily"]["precipitation"]["chart"];
            }

            //daily precipitation amount
            $chart_id = owmw_unique_id_esc($owmw_opt["id"], '_');
            $owmw_html["container_chart_daily_precip_amt_div"] = 'owmw-daily-precip-amt-chart-canvas-' . esc_attr($chart_id);
            $owmw_html["chart"]["daily"]["precip_amt"]["labels"] = '';
            $owmw_html["chart"]["daily"]["precip_amt"]["dataset_rain"] = '';
            $owmw_html["chart"]["daily"]["precip_amt"]["dataset_snow"] = '';
            $owmw_html["chart"]["daily"]["precip_amt"]["config"] = '';
            $owmw_html["chart"]["daily"]["precip_amt"]["data"] = '';
            $owmw_html["chart"]["daily"]["precip_amt"]["options"] = '';
            $owmw_html["chart"]["daily"]["precip_amt"]["chart"] = '';
            $owmw_html["chart"]["daily"]["precip_amt"]["cmd"] = '';
            $owmw_html["chart"]["daily"]["precip_amt"]["container"] = '';
            $owmw_html["chart"]["daily"]["precip_amt"]["script"] = '';

            $rgb = owmw_hex2rgb($owmw_opt["chart_rain_amt_color"]);
            $owmw_opt["chart_rain_amt_bg_color"] = 'rgba(' . $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ', 0.3)';
            $rgb = owmw_hex2rgb($owmw_opt["chart_snow_amt_color"]);
            $owmw_opt["chart_snow_amt_bg_color"] = 'rgba(' . $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ', 0.3)';

            if ($owmw_opt["days_forecast_no"] > 0) {
                $owmw_html["chart"]["daily"]["precip_amt"]["labels"] .= 'const daily_precip_amt_labels_' . esc_attr($chart_id) . ' = [';
                $owmw_html["chart"]["daily"]["precip_amt"]["dataset_rain"] .= 'const daily_precip_amt_rain_dataset_' . esc_attr($chart_id) . ' = [';
                $owmw_html["chart"]["daily"]["precip_amt"]["dataset_snow"] .= 'const daily_precip_amt_snow_dataset_' . esc_attr($chart_id) . ' = [';
                $cnt = 0;
                foreach ($owmw_data["forecast"] as $i => $value) {
                    if (is_numeric($i)) {
                        $owmw_html["chart"]["daily"]["precip_amt"]["labels"] .= '"' . esc_attr($value["time"] != 'Now' ? date_i18n('D', $value["timestamp"]) . ' ' : '') . $value["time"] . '",';
                        $owmw_html["chart"]["daily"]["precip_amt"]["dataset_rain"] .= '"' . esc_html(floatval($value["rain"])) . '",';
                        $owmw_html["chart"]["daily"]["precip_amt"]["dataset_snow"] .= '"' . esc_html(floatval($value["snow"])) . '",';
                        ++$cnt;
                    }
                }
                $owmw_html["chart"]["daily"]["precip_amt"]["labels"] .= '];';
                $owmw_html["chart"]["daily"]["precip_amt"]["dataset_rain"] .= '];';
                $owmw_html["chart"]["daily"]["precip_amt"]["dataset_snow"] .= '];';

                $owmw_html["chart"]["daily"]["precip_amt"]["config"] .= 'const daily_precip_amt_config_' . esc_attr($chart_id) . ' = { type: "line", options: daily_precip_amt_options_' . esc_attr($chart_id) . ', data: daily_precip_amt_data_' . esc_attr($chart_id) . ',};';
                $owmw_html["chart"]["daily"]["precip_amt"]["options"] .= 'const daily_precip_amt_options_' . esc_attr($chart_id) . ' = {
                color: "' . $owmw_opt["chart_text_color"] . '",
                borderWidth: 1,
                tension: 0.3,
                responsive: true,
                pointBackgroundColor: "rgba(0,0,0,0)",
                pointBorderWidth: 0,
                pointRadius: 10,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: "index" },
                plugins: {
                    annotation: { annotations: { ' . $owmw_html["chart"]["daily"]["night_boxes"] . ' } },
                    tooltip: { position: "bottom", callbacks: { label: function(context) { var label = context.dataset.label || ""; if (label) { label += ": "; }
                    if (context.parsed.y !== null) { label += context.parsed.y + " ' . $owmw_data["precipitation_unit"] . '"; }
                    return label; } } } },
                scales: { x: { ticks: { color: "' . $owmw_opt["chart_text_color"] . '" }, grid: { display: false, } },
                          y: { beginAtZero: true, ticks: { color: "' . $owmw_opt["chart_text_color"] . '", callback: function(value, index, ticks) { return value + " ' . $owmw_data["precipitation_unit"] . '"; } }, position: "left" } }
                };';
                $owmw_html["chart"]["daily"]["precip_amt"]["data"] .= 'const daily_precip_amt_data_' . esc_attr($chart_id) . ' = {
                                                                      labels: daily_precip_amt_labels_' . esc_attr($chart_id) . ',
                                                                      datasets: [{
                                                                        label: "' . esc_attr__('Rain', 'owm-weather') . '",
                                                                        data: daily_precip_amt_rain_dataset_' . esc_attr($chart_id) . ',
                                                                        fill: true,
                                                                        borderColor: "' . esc_attr($owmw_opt["chart_rain_amt_color"]) . '",
                                                                        backgroundColor: "' . esc_attr($owmw_opt["chart_rain_amt_bg_color"]) . '",
                                                                        yAxisID: "y",
                                                                        },{
                                                                        label: "' . esc_attr__('Snow', 'owm-weather') . '",
                                                                        data: daily_precip_amt_snow_dataset_' . esc_attr($chart_id) . ',
                                                                        fill: true,
                                                                        borderColor: "' . esc_attr($owmw_opt["chart_snow_amt_color"]) . '",
                                                                        backgroundColor: "' . esc_attr($owmw_opt["chart_snow_amt_bg_color"]) . '",
                                                                        yAxisID: "y",
                                                                        }]
                                                                    };';
                $owmw_html["chart"]["daily"]["precip_amt"]["chart"] .= 'jQuery(document).ready(function(){
                                                        var ctx = jQuery("#' . esc_attr($owmw_html["container_chart_daily_precip_amt_div"]) . '");
                                                        var dailyPrecipitationAmountChart = new Chart(ctx, daily_precip_amt_config_' . esc_attr($chart_id) . ');
                                                        });';

                $owmw_html["chart"]["daily"]["precip_amt"]["container"] =
                '<div class="owmw-chart-container" style="position: relative; height:' . esc_attr($owmw_opt["chart_height"]) . 'px; width:100%">
                <canvas id="' . $owmw_html["container_chart_daily_precip_amt_div"] . '" style="' . owmw_css_color('background-color', $owmw_opt["chart_background_color"]) . owmw_css_border($owmw_opt["chart_border_color"], $owmw_opt["chart_border_width"], $owmw_opt["chart_border_style"], $owmw_opt["chart_border_radius"]) . '" aria-label="' . esc_attr__('daily liquid precipitation amount', 'owm-weather') . '" role="img"></canvas></div>';
                $owmw_html["chart"]["daily"]["precip_amt"]["script"] =
                $owmw_html["chart"]["daily"]["precip_amt"]["labels"] .
                $owmw_html["chart"]["daily"]["precip_amt"]["dataset_rain"] .
                $owmw_html["chart"]["daily"]["precip_amt"]["dataset_snow"] .
                $owmw_html["chart"]["daily"]["precip_amt"]["data"] .
                $owmw_html["chart"]["daily"]["precip_amt"]["options"] .
                $owmw_html["chart"]["daily"]["precip_amt"]["config"] .
                $owmw_html["chart"]["daily"]["precip_amt"]["chart"];
            }

            //daily wind speed
            $chart_id = owmw_unique_id_esc($owmw_opt["id"], '_');
            $owmw_html["container_chart_daily_wind_div"] = 'owmw-daily-wind-chart-canvas-' . esc_attr($chart_id);
            $owmw_html["chart"]["daily"]["wind"]["labels"] = '';
            $owmw_html["chart"]["daily"]["wind"]["dataset_wind"] = '';
            $owmw_html["chart"]["daily"]["wind"]["dataset_wind_gust"] = '';
            $owmw_html["chart"]["daily"]["wind"]["loadImages"] = '';
            $owmw_html["chart"]["daily"]["wind"]["loadImagePoints"] = '';
            $owmw_html["chart"]["daily"]["wind"]["config"] = '';
            $owmw_html["chart"]["daily"]["wind"]["data"] = '';
            $owmw_html["chart"]["daily"]["wind"]["options"] = '';
            $owmw_html["chart"]["daily"]["wind"]["chart"] = '';
            $owmw_html["chart"]["daily"]["wind"]["cmd"] = '';
            $owmw_html["chart"]["daily"]["wind"]["container"] = '';
            $owmw_html["chart"]["daily"]["wind"]["script"] = '';

            if ($owmw_opt["hours_forecast_no"] > 0) {
                $owmw_html["chart"]["daily"]["wind"]["labels"] .= 'const daily_wind_labels_' . esc_attr($chart_id) . ' = [';
                $owmw_html["chart"]["daily"]["wind"]["dataset_wind"] .= 'const daily_wind_speed_dataset_' . esc_attr($chart_id) . ' = [';
                $owmw_html["chart"]["daily"]["wind"]["dataset_wind_gust"] .= 'const daily_wind_gust_dataset_' . esc_attr($chart_id) . ' = [';
                $cnt = 0;
                foreach ($owmw_data["forecast"] as $i => $value) {
                    if (is_numeric($i)) {
                        $owmw_html["chart"]["daily"]["wind"]["labels"] .= '"' . esc_attr($value["time"] != 'Now' ? date_i18n('D', $value["timestamp"]) . ' ' : '') . $value["time"] . '",';
                        $owmw_html["chart"]["daily"]["wind"]["dataset_wind"] .= '"' . esc_html(intval($value["wind_speed"])) . '",';
                        $owmw_html["chart"]["daily"]["wind"]["dataset_wind_gust"] .= '"' . esc_html(intval($value["wind_gust"])) . '",';
                        $varName = "owmw_wind_direction_svg_" . esc_attr($chart_id) . "_" . $cnt;
                        $owmw_html["chart"]["daily"]["wind"]["loadImages"] .= ($cnt > 0 ? ',' : '') . 'loadImage("' . owmw_chart_wind_direction_icon($value["wind_degrees"], null, $owmw_opt["wind_icon_direction"], true) . '")';
                        $owmw_html["chart"]["daily"]["wind"]["loadImagePoints"] .= ($cnt ? "," : "") . 'images[' . $cnt . ']';
                        ++$cnt;
                    }
                }
                $owmw_html["chart"]["daily"]["wind"]["labels"] .= '];';
                $owmw_html["chart"]["daily"]["wind"]["dataset_wind"] .= '];';
                $owmw_html["chart"]["daily"]["wind"]["dataset_wind_gust"] .= '];';

                $owmw_html["chart"]["daily"]["wind"]["config"] .= 'const daily_wind_config_' . esc_attr($chart_id) . ' = { type: "line", options: daily_wind_options_' . esc_attr($chart_id) . ', data: daily_wind_data_' . esc_attr($chart_id) . ',};';
                $owmw_html["chart"]["daily"]["wind"]["options"] .= 'const daily_wind_options_' . esc_attr($chart_id) . ' = {
                color: "' . $owmw_opt["chart_text_color"] . '",
                borderWidth: 1,
                tension: 0.3,
                responsive: true,
                pointBackgroundColor: "rgba(0,0,0,0)",
                pointBorderWidth: 0,
                pointRadius: 10,
                maintainAspectRatio: false,
                interaction: { intersect: false, mode: "index" },
                plugins: {
                    annotation: { annotations: { ' . $owmw_html["chart"]["daily"]["night_boxes"] . ' } },
                    tooltip: { position: "bottom", callbacks: { label: function(context) { var label = context.dataset.label || ""; if (label) { label += ": "; }
                    if (context.parsed.y !== null) { label += context.parsed.y + " ' . $owmw_data["wind_speed_unit"] . '"; }
                    return label; } } } },
                scales: { x: { ticks: { color: "' . $owmw_opt["chart_text_color"] . '" }, grid: { display: false, } },
                          y: { beginAtZero: true, ticks: { color: "' . $owmw_opt["chart_text_color"] . '", callback: function(value, index, ticks) { return value + " ' . $owmw_data["wind_speed_unit"] . '"; } }, position: "left" } }
                };';
                $owmw_html["chart"]["daily"]["wind"]["data"] .= 'const daily_wind_data_' . esc_attr($chart_id) . ' = {
                                                                      labels: daily_wind_labels_' . esc_attr($chart_id) . ',
                                                                      datasets: [{
                                                                        label: "' . esc_attr__('Wind Speed', 'owm-weather') . '",
                                                                        data: daily_wind_speed_dataset_' . esc_attr($chart_id) . ',
                                                                        fill: false,
                                                                        borderColor: "' . esc_attr($owmw_opt["chart_wind_speed_color"]) . '",
                                                                        pointStyle: [' . $owmw_html["chart"]["daily"]["wind"]["loadImagePoints"] . '],
                                                                        yAxisID: "y",
                                                                        },{
                                                                        label: "' . esc_attr__('Wind Gust', 'owm-weather') . '",
                                                                        data: daily_wind_gust_dataset_' . esc_attr($chart_id) . ',
                                                                        fill: false,
                                                                        borderColor: "' . esc_attr($owmw_opt["chart_wind_gust_color"]) . '",
                                                                        yAxisID: "y",
                                                                        }]
                                                                    };';
                $owmw_html["chart"]["daily"]["wind"]["chart"] .= 'jQuery(document).ready(function(){
                                        Promise.all([
                                        ' . $owmw_html["chart"]["daily"]["wind"]["loadImages"] .
                                        '
                                          ])
                                          .then(function(images){
                                        ' . $owmw_html["chart"]["daily"]["wind"]["labels"] .
                                          $owmw_html["chart"]["daily"]["wind"]["dataset_wind"] .
                                          $owmw_html["chart"]["daily"]["wind"]["dataset_wind_gust"] .
                                          $owmw_html["chart"]["daily"]["wind"]["data"] .
                                          $owmw_html["chart"]["daily"]["wind"]["options"] .
                                          $owmw_html["chart"]["daily"]["wind"]["config"] . '
                                            var ctx = jQuery("#' . esc_attr($owmw_html["container_chart_daily_wind_div"]) . '");
                                            var dailyWindSpeedChart = new Chart(ctx, daily_wind_config_' . esc_attr($chart_id) . ');
                                          })
                                          .catch( function(e) { console.error(e); });
                                        });';

                $owmw_html["chart"]["daily"]["wind"]["container"] =
                '<div class="owmw-chart-container" style="position: relative; height:' . esc_attr($owmw_opt["chart_height"]) . 'px; width:100%">
                <canvas id="' . $owmw_html["container_chart_daily_wind_div"] . '" style="' . owmw_css_color('background-color', $owmw_opt["chart_background_color"]) . owmw_css_border($owmw_opt["chart_border_color"], $owmw_opt["chart_border_width"], $owmw_opt["chart_border_style"], $owmw_opt["chart_border_radius"]) . '" aria-label="' . esc_attr__('daily wind speed', 'owm-weather') . '" role="img"></canvas></div>';
                $owmw_html["chart"]["daily"]["wind"]["script"] = $owmw_html["chart"]["daily"]["wind"]["chart"];
            }
        }

        //Table
        if (in_array($owmw_opt["template"], array("debug", "custom1", "custom2", "table1", "table2", "table3", "tabbed1"))) {
            if (!empty($owmw_opt["table_border_color"])) {
                $owmw_html["custom_css"] .= '.owmw-table.table-bordered > tbody > tr > td, .owmw-table .table-bordered > tbody > tr > th, .owmw-table.table-bordered > tfoot > tr > td, .owmw-table.table-bordered > tfoot > tr > th, .owmw-table.table-bordered > thead > tr > td, .owmw-table.table-bordered > thead > tr > th { ' . owmw_css_border($owmw_opt["table_border_color"], $owmw_opt["table_border_width"], $owmw_opt["table_border_style"], $owmw_opt["table_border_radius"]) . '}';
            }
            //Hourly
            if ($owmw_opt["hours_forecast_no"] > 0) {
                $owmw_html["container_table_hourly_div"] = owmw_unique_id_esc('owmw-table-hourly-container-' . esc_attr($owmw_opt["id"]));
                $owmw_html["table"]["hourly"] = '<div class="table-responsive owmw-table owmw-table-hours"><table class="table table-sm table-bordered" style="' . owmw_css_color('background-color', $owmw_opt["table_background_color"]) . owmw_css_color("color", $owmw_opt["table_text_color"]) .
                                            owmw_css_border($owmw_opt["table_border_color"], $owmw_opt["table_border_width"], $owmw_opt["table_border_style"], $owmw_opt["table_border_radius"]) . '">';
                $owmw_html["table"]["hourly"] .= '<thead><tr>';
                $owmw_html["table"]["hourly"] .= '<th scope="col">' . esc_html__('Time', 'owm-weather') . '</th>';
                $owmw_html["table"]["hourly"] .= '<th scope="col" colspan="2">' . esc_html__('Conditions', 'owm-weather') . '</th>';
                $owmw_html["table"]["hourly"] .= '<th scope="col" colspan="2">' . owmw_temperature_icon($owmw_opt["table_text_color"]) . '<br>' . esc_html__('Temp.', 'owm-weather') . ' / ' . esc_html__('Feels Like', 'owm-weather') . '</th>';
                if ($owmw_opt["precipitation"] == 'yes') {
                    $owmw_html["table"]["hourly"] .= '<th scope="col" colspan="2">' . owmw_precipitation_icon($owmw_opt["table_text_color"]) . '<br>' . esc_html__('Precipitation', 'owm-weather') . '</th>';
                }
                if ($owmw_opt["cloudiness"] == 'yes') {
                    $owmw_html["table"]["hourly"] .= '<th scope="col">' . owmw_cloudiness_icon($owmw_opt["table_text_color"]) . '<br>' . esc_html__('Cloud Cover', 'owm-weather') . '</th>';
                }
                if ($owmw_opt["dew_point"] == 'yes') {
                    $owmw_html["table"]["hourly"] .= '<th scope="col">' . owmw_dew_point_icon($owmw_opt["table_text_color"]) . '<br>' . esc_html__('Dew Point', 'owm-weather') . '</th>';
                }
                if ($owmw_opt["humidity"] == 'yes') {
                    $owmw_html["table"]["hourly"] .= '<th scope="col">' . owmw_humidity_icon($owmw_opt["table_text_color"]) . '<br>' . esc_html__('Humidity', 'owm-weather') . '</th>';
                }
                if ($owmw_opt["wind"] == 'yes') {
                    $owmw_html["table"]["hourly"] .= '<th scope="col" colspan="2">' . owmw_wind_direction_icon($owmw_data["wind_degrees"], $owmw_opt["table_text_color"], $owmw_opt["wind_icon_direction"]) . '<br>' . esc_html__('Wind', 'owm-weather') . ' / ' . esc_html__('Gust', 'owm-weather') . '</th>';
                }
                if ($owmw_opt["pressure"] == 'yes') {
                    $owmw_html["table"]["hourly"] .= '<th scope="col">' . owmw_pressure_icon($owmw_opt["table_text_color"]) . '<br>' . esc_html__('Pressure', 'owm-weather') . '</th>';
                }
                if ($owmw_opt["visibility"] == 'yes') {
                    $owmw_html["table"]["hourly"] .= '<th scope="col">' . owmw_visibility_icon($owmw_opt["table_text_color"]) . '<br>' . esc_html__('Visibility', 'owm-weather') . '</th>';
                }
                if ($owmw_opt["uv_index"] == 'yes') {
                    $owmw_html["table"]["hourly"] .= '<th scope="col">' . owmw_uv_index_icon($owmw_opt["table_text_color"]) . '<br>' . esc_html__('UV Index', 'owm-weather') . '</th>';
                }
                $owmw_html["table"]["hourly"] .= '</tr></thead>';
                $owmw_html["table"]["hourly"] .= '<tbody>';
                $cnt = 0;
                foreach ($owmw_data["hourly"] as $i => $value) {
                    if ($cnt < $owmw_opt["hours_forecast_no"]) {
                        $owmw_html["table"]["hourly"] .= '<tr>';
                        $owmw_html["table"]["hourly"] .= '<th scope="row">' . date_i18n('D', $value["timestamp"]) . ($owmw_opt["hours_time_icons"] == 'yes' ? owmw_hour_icon($value["time"], $owmw_opt["table_text_color"]) : '<br>' . esc_html($value["time"])) . '</th>';
                        $owmw_html["table"]["hourly"] .= '<td style="border-right: 0 !important;">' . owmw_weatherIcon($owmw_opt["iconpack"], $value["condition_id"], $value["day_night"], $value["description"]) . '</td><td class="small" style="border-left: 0 !important">' . esc_html($value["description"]) . '</td>';
                        $owmw_html["table"]["hourly"] .= '<td class="owmw-table-temperature">' . esc_html($value["temperature"]) . '</td>';
                        $owmw_html["table"]["hourly"] .= '<td class="owmw-table-temperature">' . esc_html($value["feels_like"]) . '</td>';
                        if ($owmw_opt["precipitation"] == 'yes') {
                            $owmw_html["table"]["hourly"] .= '<td>' . esc_html($value["rain_chance"]) . '</td>';
                            $owmw_html["table"]["hourly"] .= '<td>' . esc_html($value["precipitation"]) . '</td>';
                        }
                        if ($owmw_opt["cloudiness"] == 'yes') {
                            $owmw_html["table"]["hourly"] .= '<td>' . esc_html($value["cloudiness"]) . '</td>';
                        }
                        if ($owmw_opt["dew_point"] == 'yes') {
                            $owmw_html["table"]["hourly"] .= '<td class="owmw-table-temperature">' . esc_html($value["dew_point"]) . '</td>';
                        }
                        if ($owmw_opt["humidity"] == 'yes') {
                            $owmw_html["table"]["hourly"] .= '<td>' . esc_html($value["humidity"]) . '</td>';
                        }
                        if ($owmw_opt["wind"] == 'yes') {
                            $owmw_html["table"]["hourly"] .= '<td>' . esc_html($value["wind_speed"] . ' ' . $value["wind_direction"]) . '</td>';
                            $owmw_html["table"]["hourly"] .= '<td>' . esc_html($value["wind_gust"]) . '</td>';
                        }
                        if ($owmw_opt["pressure"] == 'yes') {
                            $owmw_html["table"]["hourly"] .= '<td>' . esc_html($value["pressure"]) . '</td>';
                        }
                        if ($owmw_opt["visibility"] == 'yes') {
                            $owmw_html["table"]["hourly"] .= '<td>' . esc_html($value["visibility"]) . '</td>';
                        }
                        if ($owmw_opt["uv_index"] == 'yes') {
                            $owmw_html["table"]["hourly"] .= '<td>' . esc_html($value["uv_index"]) . '</td>';
                        }
                        $owmw_html["table"]["hourly"] .= '</tr>';
                        ++$cnt;
                    }
                }
                $owmw_html["table"]["hourly"] .= '</tbody>';
                $owmw_html["table"]["hourly"] .= '</table>';
                $owmw_html["table"]["hourly"] .= '</div>';
            }

            //Daily
            if ($owmw_opt["days_forecast_no"] > 0) {
                $owmw_html["container_table_daily_div"] = owmw_unique_id_esc('owmw-table-daily-container-' . esc_attr($owmw_opt["id"]));
                $owmw_html["table"]["daily"] = '<div class="table-responsive owmw-table owmw-table-daily"><table class="table table-sm table-bordered" style="' . owmw_css_color('background-color', $owmw_opt["table_background_color"]) . owmw_css_color("color", $owmw_opt["table_text_color"]) .
                                            owmw_css_border($owmw_opt["table_border_color"], $owmw_opt["table_border_width"], $owmw_opt["table_border_style"], $owmw_opt["table_border_radius"]) . '">';
                $owmw_html["table"]["daily"] .= '<thead><tr>';
                $owmw_html["table"]["daily"] .= '<th scope="col">' . esc_html__('Day', 'owm-weather') . '</th>';
                $owmw_html["table"]["daily"] .= '<th scope="col" colspan="2">' . esc_html__('Conditions', 'owm-weather') . '</th>';
                $owmw_html["table"]["daily"] .= '<th scope="col" colspan="2">' . owmw_temperature_icon($owmw_opt["table_text_color"]) . '<br>' . esc_html__('Low / High Temperature', 'owm-weather') . '</th>';
                if ($owmw_opt["precipitation"] == 'yes') {
                    $owmw_html["table"]["daily"] .= '<th scope="col" colspan="2">' . owmw_precipitation_icon($owmw_opt["table_text_color"]) . '<br>' . esc_html__('Precipitation', 'owm-weather') . '</th>';
                }
                if ($owmw_opt["cloudiness"] == 'yes') {
                    $owmw_html["table"]["daily"] .= '<th scope="col">' . owmw_cloudiness_icon($owmw_opt["table_text_color"]) . '<br>' . esc_html__('Cloud Cover', 'owm-weather') . '</th>';
                }
                if ($owmw_opt["dew_point"] == 'yes') {
                    $owmw_html["table"]["daily"] .= '<th scope="col">' . owmw_dew_point_icon($owmw_opt["table_text_color"]) . '<br>' . esc_html__('Dew Point', 'owm-weather') . '</th>';
                }
                if ($owmw_opt["humidity"] == 'yes') {
                    $owmw_html["table"]["daily"] .= '<th scope="col">' . owmw_humidity_icon($owmw_opt["table_text_color"]) . '<br>' . esc_html__('Humidity', 'owm-weather') . '</th>';
                }
                if ($owmw_opt["wind"] == 'yes') {
                    $owmw_html["table"]["daily"] .= '<th scope="col" colspan="2">' . owmw_wind_direction_icon($owmw_data["wind_degrees"], $owmw_opt["table_text_color"], $owmw_opt["wind_icon_direction"]) . '<br>' . esc_html__('Wind', 'owm-weather') . ' / ' . esc_html__('Gust', 'owm-weather') . '</th>';
                }
                if ($owmw_opt["pressure"] == 'yes') {
                    $owmw_html["table"]["daily"] .= '<th scope="col">' . owmw_pressure_icon($owmw_opt["table_text_color"]) . '<br>' . esc_html__('Pressure', 'owm-weather') . '</th>';
                }
                if ($owmw_opt["uv_index"] == 'yes') {
                    $owmw_html["table"]["daily"] .= '<th scope="col">' . owmw_uv_index_icon($owmw_opt["table_text_color"]) . '<br>' . esc_html__('UV Index', 'owm-weather') . '</th>';
                }
                $owmw_html["table"]["daily"] .= '</tr></thead>';
                $owmw_html["table"]["daily"] .= '<tbody>';
                $cnt = 0;
                foreach ($owmw_data["daily"] as $i => $value) {
                    if ($cnt < $owmw_opt["days_forecast_no"]) {
                        $owmw_html["table"]["daily"] .= '<tr>';
                        $owmw_html["table"]["daily"] .= '<th scope="row">' . esc_html($value["day"]) . '</th>';
                        $owmw_html["table"]["daily"] .= '<td style="border-right: 0 !important;">' . owmw_weatherIcon($owmw_opt["iconpack"], $value["condition_id"], "day", $value["description"]) . '</td><td class="small" style="border-left: 0 !important">' . esc_html($value["description"]) . '</td>';
                        $owmw_html["table"]["daily"] .= '<td class="owmw-table-temperature">' . esc_html($value["temperature_minimum"]) . '</td>';
                        $owmw_html["table"]["daily"] .= '<td class="owmw-table-temperature">' . esc_html($value["temperature_maximum"]) . '</td>';
                        if ($owmw_opt["precipitation"] == 'yes') {
                            $owmw_html["table"]["daily"] .= '<td>' . esc_html($value["rain_chance"]) . '</td>';
                            $owmw_html["table"]["daily"] .= '<td>' . esc_html($value["precipitation"]) . '</td>';
                        }
                        if ($owmw_opt["cloudiness"] == 'yes') {
                            $owmw_html["table"]["daily"] .= '<td>' . esc_html($value["cloudiness"]) . '</td>';
                        }
                        if ($owmw_opt["dew_point"] == 'yes') {
                            $owmw_html["table"]["daily"] .= '<td class="owmw-table-temperature">' . esc_html($value["dew_point"]) . '</td>';
                        }
                        if ($owmw_opt["humidity"] == 'yes') {
                            $owmw_html["table"]["daily"] .= '<td>' . esc_html($value["humidity"]) . '</td>';
                        }
                        if ($owmw_opt["wind"] == 'yes') {
                            $owmw_html["table"]["daily"] .= '<td>' . esc_html($value["wind_speed"]) . ' ' . $value["wind_direction"] . '</td>';
                            $owmw_html["table"]["daily"] .= '<td>' . esc_html($value["wind_gust"]) . '</td>';
                        }
                        if ($owmw_opt["pressure"] == 'yes') {
                            $owmw_html["table"]["daily"] .= '<td>' . esc_html($value["pressure"]) . '</td>';
                        }
                        if ($owmw_opt["uv_index"] == 'yes') {
                            $owmw_html["table"]["daily"] .= '<td>' . esc_html($value["uv_index"]) . '</td>';
                        }
                        $owmw_html["table"]["daily"] .= '</tr>';
                        ++$cnt;
                    }
                }
                $owmw_html["table"]["daily"] .= '</tbody>';
                $owmw_html["table"]["daily"] .= '</table>';
                $owmw_html["table"]["daily"] .= '</div>';
            }

            //5 days / 3 hours
            if (in_array($owmw_opt["template"], array("debug", "custom1", "custom2", "chart1", "chart2", "tabbed2", "table3"))) {
                if ($owmw_opt["hours_forecast_no"] > 0) {
                    $owmw_html["container_table_forecast_div"] = owmw_unique_id_esc('owmw-table-forecast-container-' . esc_attr($owmw_opt["id"]));
                    $owmw_html["table"]["forecast"] .= '<div class="table-responsive owmw-table owmw-table-hours"><table class="table table-sm table-bordered" style="' . owmw_css_color('background-color', $owmw_opt["table_background_color"]) . owmw_css_color("color", $owmw_opt["table_text_color"]) .
                                                    owmw_css_border($owmw_opt["table_border_color"], $owmw_opt["table_border_width"], $owmw_opt["table_border_style"], $owmw_opt["table_border_radius"]) . '">';
                    $owmw_html["table"]["forecast"] .= '<thead><tr>';
                    $owmw_html["table"]["forecast"] .= '<th scope="col">' . esc_html__('Time', 'owm-weather') . '</th>';
                    $owmw_html["table"]["forecast"] .= '<th scope="col" colspan="2">' . esc_html__('Conditions', 'owm-weather') . '</th>';
                    $owmw_html["table"]["forecast"] .= '<th scope="col" colspan="2">' . owmw_temperature_icon($owmw_opt["table_text_color"]) . '<br>' . esc_html__('Temperature', 'owm-weather') . ' / ' . esc_html__('Feels Like', 'owm-weather') . '</th>';
                    if ($owmw_opt["precipitation"] == 'yes') {
                        $owmw_html["table"]["forecast"] .= '<th scope="col" colspan="2">' . owmw_precipitation_icon($owmw_opt["table_text_color"]) . '<br>' . esc_html__('Precipitation', 'owm-weather') . '</th>';
                        $owmw_html["table"]["forecast"] .= '<th scope="col">' . owmw_cloudiness_icon($owmw_opt["table_text_color"]) . '<br>' . esc_html__('Cloud Cover', 'owm-weather') . '</th>';
                    }
                    if ($owmw_opt["humidity"] == 'yes') {
                        $owmw_html["table"]["forecast"] .= '<th scope="col">' . owmw_humidity_icon($owmw_opt["table_text_color"]) . '<br>' . esc_html__('Humidity', 'owm-weather') . '</th>';
                    }
                    if ($owmw_opt["wind"] == 'yes') {
                        $owmw_html["table"]["forecast"] .= '<th scope="col" colspan="2">' . owmw_wind_direction_icon($owmw_data["wind_degrees"], $owmw_opt["table_text_color"], $owmw_opt["wind_icon_direction"]) . '<br>' . esc_html__('Wind', 'owm-weather') . ' / ' . esc_html__('Gust', 'owm-weather') . '</th>';
                    }
                    if ($owmw_opt["pressure"] == 'yes') {
                        $owmw_html["table"]["forecast"] .= '<th scope="col">' . owmw_pressure_icon($owmw_opt["table_text_color"]) . '<br>' . esc_html__('Pressure', 'owm-weather') . '</th>';
                    }
                    if ($owmw_opt["visibility"] == 'yes') {
                        $owmw_html["table"]["forecast"] .= '<th scope="col">' . owmw_visibility_icon($owmw_opt["table_text_color"]) . '<br>' . esc_html__('Visibility', 'owm-weather') . '</th>';
                    }
                    $owmw_html["table"]["forecast"] .= '</tr></thead>';
                    $owmw_html["table"]["forecast"] .= '<tbody>';
                    $cnt = 0;
                    foreach ($owmw_data["forecast"] as $i => $value) {
                        if ($cnt < (3 * $owmw_opt["hours_forecast_no"])) {
                            if (!in_array($i, array("temperature_minimum", "temperature_minimum_celsius", "temperature_minimum_fahrenheit", "temperature_maximum", "temperature_maximum_celsius", "temperature_maximum_fahrenheit"))) {
                                $owmw_html["table"]["forecast"] .= '<tr>';
                                $owmw_html["table"]["forecast"] .= '<th scope="row">' . date_i18n('D', $value["timestamp"]) . ($owmw_opt["hours_time_icons"] == 'yes' ? owmw_hour_icon($value["time"], $owmw_opt["table_text_color"]) : '<br>' . esc_html($value["time"])) . '</th>';
                                $owmw_html["table"]["forecast"] .= '<td style="border-right: 0 !important;">' . owmw_weatherIcon($owmw_opt["iconpack"], $value["condition_id"], $value["day_night"], $value["description"]) . '</td><td class="small" style="border-left: 0 !important">' . esc_html($value["description"]) . '</td>';
                                $owmw_html["table"]["forecast"] .= '<td class="owmw-table-temperature">' . esc_html($value["temperature"]) . '</td>';
                                $owmw_html["table"]["forecast"] .= '<td class="owmw-table-temperature">' . esc_html($value["feels_like"]) . '</td>';
                                if ($owmw_opt["precipitation"] == 'yes') {
                                    $owmw_html["table"]["forecast"] .= '<td>' . esc_html($value["rain_chance"]) . '</td>';
                                    $owmw_html["table"]["forecast"] .= '<td>' . esc_html($value["rain"] ? $value["rain"] : ($value["snow"] ? $value["snow"] : '0 ' . $owmw_data["precipitation_unit"])) . '</td>';
                                    $owmw_html["table"]["forecast"] .= '<td>' . esc_html($value["cloudiness"]) . '</td>';
                                }
                                if ($owmw_opt["humidity"] == 'yes') {
                                    $owmw_html["table"]["forecast"] .= '<td>' . esc_html($value["humidity"]) . '</td>';
                                }
                                if ($owmw_opt["wind"] == 'yes') {
                                    $owmw_html["table"]["forecast"] .= '<td>' . esc_html($value["wind_speed"] . ' ' . $value["wind_direction"]) . '</td>';
                                    $owmw_html["table"]["forecast"] .= '<td>' . esc_html($value["wind_gust"]) . '</td>';
                                }
                                if ($owmw_opt["pressure"] == 'yes') {
                                    $owmw_html["table"]["forecast"] .= '<td>' . esc_html($value["pressure"]) . '</td>';
                                }
                                if ($owmw_opt["visibility"] == 'yes') {
                                    $owmw_html["table"]["forecast"] .= '<td>' . esc_html($value["visibility"]) . '</td>';
                                }
                                $owmw_html["table"]["forecast"] .= '</tr>';
                                ++$cnt;
                            }
                        }
                    }
                    $owmw_html["table"]["forecast"] .= '</tbody>';
                    $owmw_html["table"]["forecast"] .= '</table>';
                    $owmw_html["table"]["forecast"] .= '</div>';
                }
            }
        }

        $owmw_html["container"]["end"] .= '</div>';
        owmw_deleteWhitespaces($owmw_html);

        if ($owmw_opt["template"] == "debug") {
            if (is_multisite() && owmw_is_global_multisite()) {
                $owmw_sys_opt = get_site_option("owmw_option_name");
            } else {
                $owmw_sys_opt = get_option("owmw_option_name");
            }
        }

        owmw_sanitize_api_response($owmw_data);

        $owmw_opt['allowed_html'] = array_merge(
            wp_kses_allowed_html('post'),
            array('svg' => array('class' => true, 'style' => true, 'viewbox' => true, 'fill' => true, 'width' => true, 'height' => true, 'title' => true, 'xmlns' => true, 'viewbox' => true),
                                               'defs' => array(),
                                               'clippath' => array('id' => true, 'd' => true, 'class' => true),
                                               'path' => array('d' => true, 'class' => true, 'transform' => true, 'transform-origin' => true, 'fill' => true),
                                               'g' => array('class' => true, 'clip-path' => true),
                                               'circle' => array('class' => true, 'fill' => true, 'cx' => true, 'cy' => true, 'r' => true),
                                               'rect' => array('class' => true, 'x' => true, 'y' => true, 'width' => true, 'height' => true),
                                               'polygon' => array('class' => true, 'points' => true, 'style' => true),
                                               'metadata' => array(),
                                               'canvas' => array('id' => true, 'style' => true, 'aria-label' => true, 'role' => true),
                                               'radialgradient' => array('id' => true, 'cx' => true, 'cy' => true, 'r' => true, 'fx' => true, 'fy' => true),
                                               'lineargradient' => array('id' => true, 'x1' => true, 'y1' => true, 'x2' => true, 'y2' => true),
                                               'stop' => array('offset' => true, 'style' => true, 'class' => true),
                                               'symbol' => array('id' => true),
                                               'line' => array('x1' => true, 'y1' => true, 'x2' => true, 'y2' => true, 'class' => true),
                                               'use' => array('xlink:href' => true, 'class' => true, 'fill' => true, 'x' => true, 'y' => true),
                                               'text' => array ('x' => true, 'y' => true, 'fill' => true ),
                                               'script' => array ('type' => true )
            )
        );

        $owmw_opt['allowed_html']['button']['data-' . $owmw_opt["bootstrap_data"] . 'target'] = 1;
        $owmw_opt['allowed_html']['button']['data-' . $owmw_opt["bootstrap_data"] . 'toggle'] = 1;
        $owmw_opt['allowed_html']['button']['data-' . $owmw_opt["bootstrap_data"] . 'dismiss'] = 1;
        $owmw_opt['allowed_html']['button']['onclick'] = 1;

        add_filter('safe_style_css', function ($styles) {
            $styles[] = 'fill';
            $styles[] = 'background-blend-mode';
            $styles[] = 'position';
            return $styles;
        }, 10, 1);

        add_filter('safecss_filter_attr_allow_css', function ($allow_css, $css_test_string) {
            $pattern = '((color)*(rgb[a]))';
            if (preg_match($pattern, $css_test_string)) {
                $allow_css = true;
            }

            return $allow_css;
        }, 10, 2);

        if ($owmw_opt["text_labels"] == "yes") {
            $owmw_html["custom_css"] .= '#' . $owmw_html["main_weather_div"] . ' .card-text-lable { display: none; }';
        }

        ob_start();
        if (locate_template('owm-weather/content-owmweather.php', false) != '' && $owmw_opt["template"] == 'Default') {
            include get_stylesheet_directory() . '/owm-weather/content-owmweather.php';
        } elseif ($owmw_opt["template"] != 'Default') {
            if (locate_template('owm-weather/content-owmweather-' . $owmw_opt["template"] . '.php', false) != '') {
                include get_stylesheet_directory() . '/owm-weather/content-owmweather-' . $owmw_opt["template"] . '.php';
            } else {
                include dirname(__FILE__) . '/template/content-owmweather-' . $owmw_opt["template"] . '.php';
            }
        } else { //Default
            include(dirname(__FILE__) . '/template/content-owmweather.php');
        }
        $owmw_html["html"] = ob_get_clean();

        $response = array();
        $response['weather'] = $owmw_params["weather_id"];
        $response['html'] = $owmw_html["html"];

        wp_send_json_success($response);
    }
}
add_action('wp_ajax_owmw_get_my_weather', 'owmw_get_my_weather');
add_action('wp_ajax_nopriv_owmw_get_my_weather', 'owmw_get_my_weather');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Fix shortcode bug in widget text
///////////////////////////////////////////////////////////////////////////////////////////////////
add_filter('widget_text', 'shortcode_unautop');
add_filter('widget_text', 'do_shortcode', 11);

///////////////////////////////////////////////////////////////////////////////////////////////////
//Display shortcode in listing view
///////////////////////////////////////////////////////////////////////////////////////////////////
add_filter('manage_edit-owm-weather_columns', 'owmw_set_custom_edit_owm_weather_columns');
add_action('manage_owm-weather_posts_custom_column', 'owmw_custom_owm_weather_column', 10, 2);

function owmw_set_custom_edit_owm_weather_columns($columns)
{
    $columns['owmw-shortcode'] = esc_html__('Shortcode', 'owm-weather');
    if (is_main_site() && owmw_is_global_multisite()) {
        $columns['owmw-multisite'] = esc_html__('Multisite', 'owm-weather');
    }
    return $columns;
}

function owmw_custom_owm_weather_column($column, $post_id)
{
    if ($column == 'owmw-shortcode') {
        echo '<b>[owm-weather id="' . esc_attr($post_id) . '" /]</b>';
    }
    if ($column == 'owmw-multisite') {
        echo get_post_meta($post_id, "_owmweather_network_share", true) ? '<b>[owm-weather id="' . (is_main_site() && owmw_is_global_multisite() ? "m" : "") . esc_attr($post_id) . '" /]</b>' : "";
    }
}

function owmw_printYTvideo($video_id, $video_opacity, $id)
{
    ob_start(); ?>
<div class="owmw-feature owmw-video">
  <div id="owmw-video-bg-<?php echo esc_attr($id) ?>" class="owmw-video-bg">
    <div id="owmw-video-<?php echo esc_attr($id) ?>" class="owmw-video-wrapper">
      <div id="owmw-player-<?php echo esc_attr($id) ?>"></div>
        <script type="text/javascript">
            if (typeof window.enqueueOnYoutubeIframeAPIReady != 'function') {
              (function () {
                var isReady = false;
                var callbacks = [];
                var tag = document.createElement('script');
                tag.src = "https://www.youtube.com/iframe_api";
                var firstScriptTag = document.getElementsByTagName('script')[0];
                firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
              
                window.enqueueOnYoutubeIframeAPIReady = function (callback) {
                  if (isReady) {
                    callback();
                  } else {
                    callbacks.push(callback);
                  }
                };
              
                window.onYouTubeIframeAPIReady = function () {
                  isReady = true;
                  callbacks.forEach(function (callback) {
                    callback();
                  });
                  callbacks.splice(0);
                };
              })();
            }

            var player_<?php echo esc_attr($id) ?>;
            var playerWrapper_<?php echo esc_attr($id) ?> = document.querySelector('#owmw-video-<?php echo esc_attr($id) ?>');
            enqueueOnYoutubeIframeAPIReady(function () {
              player_<?php echo esc_attr($id) ?> = new YT.Player('owmw-player-<?php echo esc_attr($id) ?>', {
                videoId: '<?php echo $video_id; ?>',
                allowfullscreen: 1,
                playerVars: {
                  'enablejsapi': 1,
                  'autoplay': 1,
                  'rel': 0,
                  'iv_load_policy': 3,
                  'modestbranding': 1,
                  'playsinline': 1,
                  'controls': 0,
                  'loop': 1,
                  'disablekb': 1,
                  'color': 'white',
                  'origin': window.location.origin,
                  'mute': 1
                },
                events: {
                  'onReady': onPlayerReady_<?php echo esc_attr($id) ?>,
                  'onStateChange': onPlayerStateChange_<?php echo esc_attr($id) ?>
                }
                });
            });

            <?php if ($video_opacity < 0.1 || $video_opacity > 1.0) {
                $video_opacity = 0.8;
            } ?>
            document.getElementById("owmw-video-<?php echo esc_attr($id) ?>").style.opacity = <?php echo $video_opacity; ?>;
  
            function onPlayerReady_<?php echo esc_attr($id) ?>() {
              player_<?php echo esc_attr($id) ?>.playVideo();
              player_<?php echo esc_attr($id) ?>.mute();
            }
            function onPlayerStateChange_<?php echo esc_attr($id) ?>(el) {
              if(el.data === 1) {
                playerWrapper_<?php echo esc_attr($id) ?>.classList.add('fadein')
              } else if(el.data === 0) {
                player_<?php echo esc_attr($id) ?>.playVideo();
                player_<?php echo esc_attr($id) ?>.mute();
              }
            }
        </script>
    </div>
  </div>
</div>
<script type="text/javascript">
function owmw_video_<?php echo esc_attr($id) ?>() {
    var video = document.querySelector('#owmw-video-<?php echo esc_attr($id) ?>');

    if (video !== null) {
      var wrapperWidth = window.outerWidth,
          videoWidth = video.offsetWidth,
          videoHeight = video.offsetHeight; 
          
      if (wrapperWidth < 1024) {
        var wrapperHeight = window.innerHeight + 200;
      } else {
        var wrapperHeight = window.innerHeight;
      }

      var scale = Math.max(wrapperWidth / videoWidth, wrapperHeight / videoHeight);
      video.style.transform = "translate(-50%, -50%) " + "scale(" + scale + ")";
    }
}

owmw_video_<?php echo esc_attr($id) ?>();

window.onresize = function (event) {
    owmw_video_<?php echo esc_attr($id) ?>();
};
</script>
    <?php
    return(ob_get_clean());
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Weather Custom Post Type
///////////////////////////////////////////////////////////////////////////////////////////////////

// Register Custom Post Type
function owmw_posttype_weather()
{
    $labels = array(
        'name'                => _x('Weather', 'Post Type General Name', 'owm-weather'),
        'singular_name'       => _x('Weather', 'Post Type Singular Name', 'owm-weather'),
        'menu_name'           => _x('Weather', 'Post Type Menu Name', 'owm-weather'),
        'parent_item_colon'   => __('Parent Weather:', 'owm-weather'),
        'all_items'           => __('All Weather', 'owm-weather'),
        'view_item'           => __('View Weather', 'owm-weather'),
        'add_new_item'        => __('Add New Weather', 'owm-weather'),
        'add_new'             => __('New Weather', 'owm-weather'),
        'edit_item'           => __('Edit Weather', 'owm-weather'),
        'update_item'         => __('Update Weather', 'owm-weather'),
        'search_items'        => __('Search Weather', 'owm-weather'),
        'not_found'           => __('No weather found', 'owm-weather'),
        'not_found_in_trash'  => __('No weather found in Trash', 'owm-weather'),
    );

    $args = array(
        'label'               => __('weather', 'owm-weather'),
        'description'         => __('Listing weather', 'owm-weather'),
        'labels'              => $labels,
        'supports'            => array( 'title' ),
        'hierarchical'        => false,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => false,
        'show_in_admin_bar'   => true,
        'menu_position'       => 20,
        'menu_icon'           => 'dashicons-cloud',
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
        'capability_type'     => 'post',
    );

    register_post_type('owm-weather', $args);
}

// Hook into the 'init' action, must be after loading textdomain
add_action('init', 'owmw_posttype_weather', 11);

///////////////////////////////////////////////////////////////////////////////////////////////////
//Weather Custom Post Type Messages
///////////////////////////////////////////////////////////////////////////////////////////////////

function owmw_set_messages($messages)
{
    global $post, $post_ID;
    $post_type = 'owm-weather';

    $obj = get_post_type_object($post_type);
    $singular = $obj->labels->singular_name;

    $messages[$post_type] = array(
        0 => '', // Unused. Messages start at index 1.
        1 => sprintf($singular . " " . __('updated') . '.', esc_url(get_permalink($post_ID))),
        2 => __('Custom field updated') . '.',
        3 => __('Custom field deleted') . '.',
        4 => $singular . " " . __('updated') . '.',
        5 => isset($_GET['revision']) ? sprintf($singular . " " . __('restored to revision from') . " %s", wp_post_revision_title((int) $_GET['revision'], false)) : false,
        6 => $singular . " " . sprintf(__('published') . '.', esc_url(get_permalink($post_ID))),
        7 => __('Page saved') . '.',
        8 => sprintf($singular . " " . __('submitted') . '.', esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
        9 => sprintf($singular . " " . __('scheduled for') . ': <strong>%1$s</strong>.', date_i18n(__('M j, Y @ G:i'), strtotime($post->post_date)), esc_url(get_permalink($post_ID))),
        10 => sprintf($singular . " " . __('draft updated') . '.', esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
    );
    return $messages;
}
add_filter('post_updated_messages', 'owmw_set_messages');

///////////////////////////////////////////////////////////////////////////////////////////////////
//OWM WEATHER Notices
///////////////////////////////////////////////////////////////////////////////////////////////////
function owmw_notice()
{
    if (is_multisite() && owmw_is_global_multisite()) {
        $opts = get_site_option('owmw_option_name');
    } else {
        $opts = get_option('owmw_option_name');
    }

    if (is_plugin_active('owm-weather/owmweather.php') && empty($opts["owmw_advanced_api_key"])) {
        ?>
        <div class="error notice">
            <p><a href="<?php echo admin_url('admin.php?page=owmw-settings-admin#tab_advanced'); ?>"><?php esc_html_e('OWM Weather: Please enter your own OpenWeatherMap API key to avoid exceeding daily API call limits.', 'owm-weather'); ?></a></p>
        </div>
        <div class="notice">
            <p><a href="<?php echo admin_url('admin.php?page=owmw-settings-admin#tab_support'); ?>"><?php esc_html_e('OWM Weather: Create my first weather.', 'owm-weather'); ?></a></p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'owmw_notice');
add_action('network_admin_notices', 'owmw_notice');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Utility functions
///////////////////////////////////////////////////////////////////////////////////////////////////
function owmw_generate_hour_options($current)
{
    $str = '<option ' . selected(0, intval($current), false) . ' value="0">' . esc_html__("None", 'owm-weather') . '</option>';

    for ($i = 1; $i <= 48; $i++) {
        if ($i == 1) {
            $h = __('Now', 'owm-weather');
        } elseif ($i == 2) {
            $h = __('Now + 1 hour', 'owm-weather');
        } else {
            $h = __('Now + ', 'owm-weather') . ($i - 1) . __(' hours', 'owm-weather');
        }
        $str .= '<option ' . selected($i, intval($current), false) . ' value="' . esc_attr($i) . '">' . esc_html($h) . '</option>';
    }

    return $str;
}

function owmw_getWindDirection($deg)
{
    $dirs = array(
        __('N', 'owm-weather'),
        __('NE', 'owm-weather'),
        __('E', 'owm-weather'),
        __('SE', 'owm-weather'),
        __('S', 'owm-weather'),
        __('SW', 'owm-weather'),
        __('W', 'owm-weather'),
        __('NW', 'owm-weather'),
        __('N', 'owm-weather'));

    return $dirs[round($deg / 45)];
}

function owmw_getConvertedWindSpeed($speed, $unit, $bypass_unit)
{
    switch ($bypass_unit) {
        case "mi/h": //MI/H
            if ($unit == 'metric') {
                return number_format($speed * 2.24, 0);
            } else {
                return number_format($speed, 0);
            }
            break;
        case "m/s": //M/S
            if ($unit == 'metric') {
                return number_format($speed, 1);
            } else {
                return number_format($speed / 2.24, 1);
            }
            break;
        case "km/h": //KM/H
            if ($unit == 'metric') {
                return number_format($speed * 3.6, 0);
            } else {
                return number_format($speed * 1.61, 0);
            }
            break;
        case "kt": //KNOTS
            if ($unit == 'metric') {
                return number_format($speed * 1.94, 0);
            } else {
                return number_format($speed * 0.87, 0);
            }
            break;
    }

    return number_format($speed, 0);
}

function owmw_getWindSpeedUnit($unit, $bypass_unit)
{
    switch ($bypass_unit) {
        case "mi/h": //MI/H
            return 'mi/h';
            break;
        case "m/s": //M/S
            return 'm/s';
            break;
        case "km/h": //KM/H
            return 'km/h';
            break;
        case "kt": //KNOTS
            return 'kt';
            break;
        default:
            if ($unit == 'metric') {
                return 'm/s';
            } else {
                return 'mi/h';
            }
            break;
    }
}

function owmw_getPressureUnit($unit, $bypass_unit)
{
    switch ($bypass_unit) {
        case "inHg": //Inches of Mercury
        case "mmHg": //Millimeter of Mercury
        case "mb": //millibar
        case "hPa": //Hectopascals
            return $bypass_unit;
        default:
            if (in_array($unit, array("inHg", "mmHg", "mb", "hPa"))) {
                return $unit;
            } else {
                return "inHg";
            }
            break;
    }
}

function owmw_getConvertedPrecipitation($unit, $p)
{
    if ($unit == 'imperial') {
        return number_format($p / 25.4, 2);
    }

    return number_format($p, 2);
}

function owmw_getConvertedDistance($unit, $p)
{
    if ($unit == 'imperial') {
        return ($p ? number_format($p / 1609.344, 1) . " " . __("mi", 'owm-weather') : "");
    }

    return ($p ? number_format($p / 1000, 1) . " " . __("km", 'owm-weather') : "");
}

function owmw_converthPa($unit, $p, $punit)
{
    $precision = 2;
    switch ($punit) {
        case "inHg": //Inches of Mercury
            $p *= 0.029529983071445;
            break;
        case "mmHg": //Millimeter of Mercury
            $precision = 1;
            $p *= 0.75006157584566;
            break;
        case "mb": //millibar
            $precision = 0;
            break;
        case "hPa": //Hectopascals
            $precision = 0;
            break;
        default:
            if ($unit == 'metric') {
                $precision = 1;
                $p /= 0.75006157584566;//mmHg
            } else {
                $p /= 0.029529983071445;//inHg
            }
            break;
    }

    return number_format($p, $precision) . " " . $punit;
}

function owmw_getWindspeedText($speed)
{
    // Beaufort Wind Scale mph
    $windDescriptions = [
        73 => __('Hurricane', 'owm-weather'),
        64 => __('Violent Storm', 'owm-weather'),
        55 => __('Whole Gale/Storm', 'owm-weather'),
        47 => __('Strong Gale', 'owm-weather'),
        39 => __('Gale', 'owm-weather'),
        32 => __('Near Gale', 'owm-weather'),
        25 => __('Strong Breeze', 'owm-weather'),
        19 => __('Fresh Breeze', 'owm-weather'),
        13 => __('Moderate Breeze', 'owm-weather'),
        8 => __('Gentle Breeze', 'owm-weather'),
        4 => __('Light Breeze', 'owm-weather'),
        1 => __('Light Air', 'owm-weather'),
        0 => __('Calm', 'owm-weather')
    ];

    foreach ($windDescriptions as $windSpeed => $description) {
        if ($speed >= $windSpeed) {
            return $description;
        }
    }
}

function owmw_getDefault($id, $field, $default)
{
    $val = get_post_meta($id, $field, true);
    return !empty($val) ? $val : $default;
}

function owmw_getBypassDefault($bypass, $field, $default)
{
    $val = owmw_get_bypass($bypass, $field);
    return !empty($val) ? $val : $default;
}

function owmw_unique_id_esc($prefix = '', $delim = '-')
{
    static $id_counter = 0;
    return esc_attr($prefix . (isset($_POST['counter']) ? $delim . intval($_POST['counter']) : '') . $delim . (string) ++$id_counter);
}

function owmw_deleteWhitespaces(&$arr)
{
    if ($arr) {
        foreach ($arr as &$value) {
            if (is_array($value)) {
                owmw_deleteWhitespaces($value);
            } else {
                $value = preg_replace('/\s+/', ' ', $value);
            }
        }
    }
}

function owmw_esc_html_all(&$arr)
{
    if ($arr) {
        foreach ($arr as &$value) {
            if (is_array($value)) {
                owmw_esc_html_all($value);
            } else {
                $value = esc_html($value);
            }
        }
    }
}

function owmw_sanitize_atts(&$arr)
{
    if ($arr) {
        foreach ($arr as $key => &$value) {
            if ($value) {
                $value = owmw_sanitize_validate_field($key, $value);
            }
        }
    }
}

function owmw_sanitize_validate_field($key, $value)
{
    if (!empty($value)) {
        switch ($key) {
            case "id":
                $value = sanitize_text_field($value);
                if ($value[0] == "m") {
                    $value = "m" . (string)intval(substr($value, 1));
                } else {
                    $value = (string)intval($value);
                }
                break;

            case "id_owm":
            case "background_image":
            case "sunny_background_image":
            case "cloudy_background_image":
            case "drizzly_background_image":
            case "rainy_background_image":
            case "snowy_background_image":
            case "stormy_background_image":
            case "foggy_background_image":
                $value = intval(sanitize_text_field($value));
                break;

            case "zip":
            case "zip_country_code":
            case "city":
            case "country_code":
            case "custom_city_name":
                $value = sanitize_text_field($value);
                break;

            case "owm_language":
                $value = sanitize_text_field($value);
                if (!owmw_checkLanguage($value)) {
                    $value = "Default";
                }
                break;

            case "today_date_format":
                $value = sanitize_text_field($value);
                if (!in_array($value, array("none", "day", "date", "datetime"))) {
                    $value = "none";
                }
                break;

            case "wind_unit":
                $value = sanitize_text_field($value);
                if (!in_array($value, array("mi/h", "m/s", "km/h", "kt"))) {
                    $value = "mi/h";
                }
                break;

            case "wind_icon_direction":
                $value = sanitize_text_field($value);
                if (!in_array($value, array("to", "from"))) {
                    $value = "to";
                }
                break;

            case "pressure_unit":
                $value = sanitize_text_field($value);
                if (!in_array($value, array("inHg", "mmHg", "mb", "hPa"))) {
                    $value = "inHg";
                }
                break;

            case "alerts_popup":
                $value = sanitize_text_field($value);
                if (!in_array($value, array("modal", "inline"))) {
                    $value = "modal";
                }
                break;

            case "display_length_days_names":
                $value = sanitize_text_field($value);
                if (!in_array($value, array("short", "normal"))) {
                    $value = "short";
                }
                break;

            case "font":
                $value = sanitize_text_field($value);
                if (!in_array($value, array("Default", "Arvo", "Asap", "Bitter", "Droid Serif", "Exo 2", "Francois One", "Inconsolata", "Josefin Sans", "Lato", "Merriweather Sans", "Nunito", "Open Sans", "Oswald", "Pacifico", "Roboto", "Signika", "Source Sans Pro", "Tangerine", "Ubuntu"))) {
                    $value = "Default";
                }
                break;

            case "border_style":
            case "chart_border_style":
            case "table_border_style":
                $value = sanitize_text_field($value);
                if (!in_array($value, array("solid", "dotted", "dashed", "double", "groove", "inset", "outset", "ridge"))) {
                    $value = "Climacons";
                }
                break;

            case "iconpack":
                $value = sanitize_text_field($value);
                if (!in_array($value, array("Climacons", "OpenWeatherMap", "WeatherIcons", "Forecast", "Dripicons", "Pixeden", "ColorAnimated"))) {
                    $value = "Climacons";
                }
                break;

            case "size":
                $value = sanitize_text_field($value);
                if (!in_array($value, array("small", "medium", "large"))) {
                    $value = "small";
                }
                break;

            case "custom_timezone":
                $value = sanitize_text_field($value);
                if (!in_array($value, array("Default", "local", "-12", "-11", "-10", "-9", "-8", "-7", "-6", "-5", "-4", "-3", "-2", "-1", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"))) {
                    $value = "Default";
                }
                break;

            case "time_format":
                $value = sanitize_text_field($value);
                if (!in_array($value, array("12", "24"))) {
                    $value = "12";
                }
                break;

            case "unit":
                $value = sanitize_text_field($value);
                if (!in_array($value, array("imperial", "metric"))) {
                    $value = "imperial";
                }
                break;

            case "template":
                $value = sanitize_text_field($value);
                if (!in_array($value, array("Default", "card1", "card2", "tabbed1", "tabbed2", "chart1", "chart2", "table1", "table2", "table3", "slider1", "slider2", "custom1", "custom2", "debug"))) {
                    $value = "Default";
                }
                break;

            case "border_width":
            case "chart_border_width":
            case "table_border_width":
                $value = intval(sanitize_text_field($value));
                if ($value < 0) {
                    $value = "";
                }
                break;

            case "border_radius":
            case "chart_border_radius":
            case "table_border_radius":
                $value = intval(sanitize_text_field($value));
                if ($value < 0) {
                    $value = "";
                }
                break;


            case "forecast_no":
                $value = intval(sanitize_text_field($value));
                if ($value < 0 || $value > 8) {
                    $value = 0;
                }
                break;

            case "hours_forecast_no":
                $value = intval(sanitize_text_field($value));
                if ($value < 0 || $value > 48) {
                    $value = 0;
                }
                break;

            case "chart_height":
            case "map_height":
                $value = intval(sanitize_text_field($value));
                if ($value === 0) {
                    $value = "";
                } elseif ($value < 300) {
                    $value = 300;
                }
                break;

            case "map_zoom":
                $value = intval(sanitize_text_field($value));
                if ($value < 1 || $value > 18) {
                    $value = 9;
                }
                break;

            case "map_opacity":
            case "background_opacity":
                $value = floatval(sanitize_text_field($value));
                if ($value < 0.0 || $value > 1.0) {
                    $value = 0.8;
                }
                break;

            case "latitude":
                $value = floatval(sanitize_text_field($value));
                if ($value < -90.0 || $value > 90.0 || $value === 0.0) {
                    $value = "";
                }
                break;

            case "longitude":
                $value = floatval(sanitize_text_field($value));
                if ($value < -180.0 || $value > 180.0 || $value === 0.0) {
                    $value = "";
                }
                break;

            case "disable_spinner":
            case "disable_anims":
            case "current_city_name":
            case "current_weather_symbol":
            case "current_weather_description":
            case "current_temperature":
            case "current_feels_like":
            case "display_temperature_unit":
            case "sunrise_sunset":
            case "moonrise_moonset":
            case "wind":
            case "humidity":
            case "dew_point":
            case "pressure":
            case "cloudiness":
            case "precipitation":
            case "visibility":
            case "uv_index":
            case "text_labels":
            case "owm_link":
            case "last_update":
            case "map":
            case "map_disable_zoom_wheel":
            case "map_cities":
            case "map_cities_legend":
            case "map_cities_on":
            case "map_clouds":
            case "map_clouds_legend":
            case "map_clouds_on":
            case "map_precipitation":
            case "map_precipitation_legend":
            case "map_precipitation_on":
            case "map_rain":
            case "map_rain_legend":
            case "map_rain_on":
            case "map_snow":
            case "map_snow_legend":
            case "map_snow_on":
            case "map_wind":
            case "map_wind_legend":
            case "map_wind_on":
            case "map_temperature":
            case "map_temperature_legend":
            case "map_temperature_on":
            case "map_pressure":
            case "map_pressure_legend":
            case "map_pressure_on":
            case "map_windrose":
            case "map_windrose_legend":
            case "map_windrose_on":
            case "gtag":
            case "timemachine":
            case "network_share":
            case "bypass_exclude":
            case "alerts":
            case "hours_time_icons":
            case "advanced_disable_cache":
            case "advanced_disable_modal_js":
                $value = sanitize_text_field($value);
                if (!in_array($value, array('yes', 'no'))) {
                    $value = false;
                }
                break;

            case "text_color":
            case "sunny_text_color":
            case "cloudy_text_color":
            case "drizzly_text_color":
            case "rainy_text_color":
            case "snowy_text_color":
            case "stormy_text_color":
            case "foggy_text_color":
            case "border_color":
            case "background_color":
            case "sunny_background_color":
            case "cloudy_background_color":
            case "drizzly_background_color":
            case "rainy_background_color":
            case "snowy_background_color":
            case "stormy_background_color":
            case "foggy_background_color":
            case "chart_text_color":
            case "chart_night_color":
            case "chart_background_color":
            case "chart_border_color":
            case "chart_temperature_color":
            case "chart_feels_like_color":
            case "chart_dew_point_color":
            case "chart_cloudiness_color":
            case "chart_rain_chance_color":
            case "chart_humidity_color":
            case "chart_pressure_color":
            case "chart_rain_amt_color":
            case "chart_snow_amt_color":
            case "chart_wind_speed_color":
            case "chart_wind_gust_color":
            case "table_text_color":
            case "table_border_color":
            case "table_background_color":
            case "tabbed_btn_text_color":
            case "tabbed_btn_background_color":
            case "tabbed_btn_active_color":
            case "tabbed_btn_hover_color":
                $value = sanitize_hex_color($value);
                break;

            case "custom_css":
                $value = sanitize_textarea_field($value);
                break;

            case "advanced_api_key":
                $value = sanitize_text_field($value);
                break;

            case "advanced_cache_time":
                $value = intval(sanitize_text_field($value));
                if ($value <= 0) {
                    $value = "";
                } elseif ($value < 10) {
                    $value = 10;
                }
		        break;
	        case "timemachine_date":
	        case "timemachine_time":
    		    $value = sanitize_text_field($value);
		        break;
            default:
                $value = sanitize_text_field($value);
                break;
        }
    }

    return $value;
}

function owmw_sanitize_api_response(&$arr, $ta = [])
{
    if ($arr) {
        array_walk_recursive($arr, 'owmw_sanitize_api_response_item', $ta);
    }
}

function owmw_sanitize_api_response_item(&$item, $key, $ta = [])
{
    if (!is_object($item)) {
        if (in_array($key, $ta)) {
            $item = sanitize_textarea_field($item);
        } else {
            $item = sanitize_text_field($item);
        }
    } else {
        array_walk_recursive($item, 'owmw_sanitize_api_response_item', $ta);
    }
}

function owmw_IPtoLocation()
{
    global $wp;

    $set_transient = is_multisite() ? "set_site_transient" : "set_transient";
    $get_transient = is_multisite() ? "get_site_transient" : "get_transient";

    $transient_key = 'owmw_iplocation_' . owmw_get_ip_from_server();

    if (false === ($ipData = $get_transient($transient_key))) {
        $apiURL = 'https://tools.keycdn.com/geo.json?host=' . $ip;
        $request_headers = [];
        $request_headers[] = 'User-Agent: keycdn-tools:' . home_url($wp->request);

        $response = wp_remote_get(
            $apiURL,
            array( 'timeout' => 10,
            'headers' => array( 'User-Agent' => 'keycdn-tools:' . home_url($wp->request))
            )
        );

        if (is_wp_error($response)) {
            return false;
        }

        $ipData = json_decode(wp_remote_retrieve_body($response));
        $set_transient($transient_key, $ipData, MONTH_IN_SECONDS);
    }

    owmw_sanitize_api_response($ipData);

    return !empty($ipData) ? $ipData : false;
}

function owmw_get_ip_from_server()
{
    $client     = $_SERVER["HTTP_CF_CONNECTING_IP"] ?? "";
    $forward    = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? "";
    $xforward   = $_SERVER['HTTP_X_FORWARDED'] ?? "";
    $forwardfor = $_SERVER['HTTP_FORWARDED_FOR'] ?? "";
    $forwarded  = $_SERVER['HTTP_FORWARDED'] ?? "";
    $clientip   = $_SERVER['HTTP_CLIENT_IP'] ?? "";
    $remote     = $_SERVER['REMOTE_ADDR'] ?? "";

    if (filter_var($client, FILTER_VALIDATE_IP)) {
        return $client;
    } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
        return $forward;
    } elseif (filter_var($xforward, FILTER_VALIDATE_IP)) {
        return $xforward;
    } elseif (filter_var($forwardfor, FILTER_VALIDATE_IP)) {
        return $forwardfor;
    } elseif (filter_var($forwarded, FILTER_VALIDATE_IP)) {
        return $forwarded;
    } elseif (filter_var($clientip, FILTER_VALIDATE_IP)) {
        return $clientip;
    } elseif (filter_var($remote, FILTER_VALIDATE_IP)) {
        return $remote;
    }

    return false;
}

function owmw_celsius_to_fahrenheit($t)
{
    if ($t !== null) {
        return ceil(($t * 9 / 5) + 32);
    }

    return null;
}

function owmw_fahrenheit_to_celsius($t)
{
    if ($t !== null) {
        return ceil(($t - 32) * 5 / 9);
    }

    return null;
}

function owmw_checkLanguage($lang)
{
    return (in_array($lang, array("Default", "af", "al", "ar", "az", "eu", "bg", "ca", "zh_cn", "zh_tw", "hr", "cz", "da", "nl", "en", "fi", "fr", "gl", "de", "el", "he", "hi", "hu", "id", "it", "ja", "kr", "la", "lt", "mk", "no", "fa", "pl", "pt", "ro", "ru", "sr", "se", "sk", "sl", "es", "th", "tr", "uk", "vi", "zh", "zu")));
}

function owmw_getConditionText($condition)
{
    $conditionText = array(
        200 => __("Thunderstorm with Light Rain", 'owm-weather'),
        201 => __("Thunderstorm with Rain", 'owm-weather'),
        202 => __("Thunderstorm with Heavy Rain", 'owm-weather'),
        210 => __("Light Thunderstorm", 'owm-weather'),
        211 => __("Thunderstorm", 'owm-weather'),
        212 => __("Heavy Thunderstorm", 'owm-weather'),
        221 => __("Ragged Thunderstorm", 'owm-weather'),
        230 => __("Thunderstorm with Light Drizzle", 'owm-weather'),
        231 => __("Thunderstorm with Drizzle", 'owm-weather'),
        232 => __("Thunderstorm with Heavy Drizzle", 'owm-weather'),
        300 => __("Light Intensity Drizzle", 'owm-weather'),
        301 => __("Drizzle", 'owm-weather'),
        302 => __("Heavy Intensity Drizzle", 'owm-weather'),
        310 => __("Light Intensity Drizzle Rain", 'owm-weather'),
        311 => __("Drizzle Rain", 'owm-weather'),
        312 => __("Heavy Intensity Drizzle Rain", 'owm-weather'),
        313 => __("Shower Rain and Drizzle", 'owm-weather'),
        314 => __("Heavy Shower Rain and Drizzle", 'owm-weather'),
        321 => __("Shower Drizzle", 'owm-weather'),
        500 => __("Light Rain", 'owm-weather'),
        501 => __("Moderate Rain", 'owm-weather'),
        502 => __("Heavy Intensity Rain", 'owm-weather'),
        503 => __("Very Heavy Rain", 'owm-weather'),
        504 => __("Extreme Rain", 'owm-weather'),
        511 => __("Freezing Rain", 'owm-weather'),
        520 => __("Light Intensity Shower Rain", 'owm-weather'),
        521 => __("Shower Rain", 'owm-weather'),
        522 => __("Heavy Intensity Shower Rain", 'owm-weather'),
        531 => __("Ragged Shower Rain", 'owm-weather'),
        600 => __("Light Snow", 'owm-weather'),
        601 => __("Snow", 'owm-weather'),
        602 => __("Heavy Snow", 'owm-weather'),
        611 => __("Sleet", 'owm-weather'),
        612 => __("Light Shower Sleet", 'owm-weather'),
        613 => __("Shower Sleet", 'owm-weather'),
        615 => __("Light Rain and Snow", 'owm-weather'),
        616 => __("Rain and Snow", 'owm-weather'),
        620 => __("Light Shower Snow", 'owm-weather'),
        621 => __("Shower Snow", 'owm-weather'),
        622 => __("Heavy Shower Snow", 'owm-weather'),
        701 => __("Mist", 'owm-weather'),
        711 => __("Smoke", 'owm-weather'),
        721 => __("Haze", 'owm-weather'),
        731 => __("Sand / Dust Whirls", 'owm-weather'),
        741 => __("Fog", 'owm-weather'),
        751 => __("Sand", 'owm-weather'),
        761 => __("Dust", 'owm-weather'),
        762 => __("Volcanic Ash", 'owm-weather'),
        771 => __("Squalls", 'owm-weather'),
        781 => __("Tornado", 'owm-weather'),
        800 => __("Clear Sky", 'owm-weather'),
        801 => __("Few Clouds", 'owm-weather'),
        802 => __("Scattered Clouds", 'owm-weather'),
        803 => __("Broken Clouds", 'owm-weather'),
        804 => __("Overcast Clouds", 'owm-weather')
    );

    if (!empty($conditionText[$condition])) {
        return $conditionText[$condition];
    }

    return "";
}

function owmw_is_global_multisite()
{
    if (is_multisite()) {
        $opts = get_site_option('owmw_option_name');
        if (isset($opts["owmw_network_multisite"]) && $opts["owmw_network_multisite"] == "yes") {
            return true;
        }
    }

    return false;
}

if (!function_exists('str_starts_with')) {
    function str_starts_with($haystack, $needle) {
        return substr($haystack, 0, strlen($needle)) === $needle;
    }
}
