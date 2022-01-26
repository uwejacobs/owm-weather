<?php
/*
Plugin Name: OWM Weather
Plugin URI: https://github.com/uwejacobs/owm-weather
Description: OWM Weather is a powerful weather plugin for WordPress, based on Open Weather Map API, using Custom Post Types and shortcodes, bundled with a ton of features.
Version: 5.0.8
Author: Uwe Jacobs
Author URI: https://github.com/uwejacobs
Original Author: Benjamin DENIS
Original Author URI: https://wpcloudy.com/
License: GPLv2
Text Domain: owm-weather
Domain Path: /lang
*/

/*  Copyright 2013 - 2018  Benjamin DENIS  (email : contact@wpcloudy.com)
    Copyright 2021 Uwe Jacobs

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

// To prevent calling the plugin directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Please don&rsquo;t call the plugin directly. Thanks :)';
	exit;
}

$GLOBALS['owmw_params'] = [];

function owmw_activation() {
    global $wpdb;
	$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_owmweather%' ");
	$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_owmweather%' ");
}
register_activation_hook(__FILE__, 'owmw_activation');

function owmw_deactivation() {
}
register_deactivation_hook(__FILE__, 'owmw_deactivation');

define( 'OWM_WEATHER_VERSION', '5.0.8' );

///////////////////////////////////////////////////////////////////////////////////////////////////
//Shortcut settings page
///////////////////////////////////////////////////////////////////////////////////////////////////

add_filter('plugin_action_links', 'owmw_plugin_action_links', 10, 2);

function owmw_plugin_action_links($links, $file) {
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        $settings_link = '<a href="' . admin_url('admin.php?page=owmw-settings-admin').'">'.esc_html__('Settings', 'owm-weather').'</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Translation
///////////////////////////////////////////////////////////////////////////////////////////////////
function owmw_init() {
	load_plugin_textdomain( 'owm-weather', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

	//Admin panel + Dashboard widget
	if ( is_admin() ) {
		require_once dirname( __FILE__ ) . '/owmweather-admin.php';
		require_once dirname( __FILE__ ) . '/owmweather-export.php';
	    require_once dirname( __FILE__ ) . '/owmweather-widget.php';
	    require_once dirname( __FILE__ ) . '/owmweather-pointers.php';
	}
}
add_action('plugins_loaded', 'owmw_init');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Enqueue styles Front-end
///////////////////////////////////////////////////////////////////////////////////////////////////
function owmw_async_js($url) {
    if (strpos($url, '#async')===false)
        return $url;
    else if (is_admin())
        return str_replace('#async', '', $url);
    else
        return str_replace('#async', '', $url)."' async='async";
}
add_filter('clean_url', 'owmw_async_js', 11, 1);

function owmw_styles() {
	wp_enqueue_script( 'owmw-ajax-js', plugins_url('js/owm-weather-ajax.js', __FILE__), array('jquery'), '', true );
	$owmwAjax = array(
        'owmw_nonce' => wp_create_nonce('owmw_get_weather_nonce'),
        'owmw_url' => admin_url( 'admin-ajax.php' ) . "?lang=" . substr(get_locale(),0,2),
    );
	wp_localize_script( 'owmw-ajax-js', 'owmwAjax', $owmwAjax);

	wp_register_style('owmweather-css', plugins_url('css/owmweather.min.css', __FILE__));
	wp_enqueue_style('owmweather-css');

	wp_register_style('owmweather-anim-css', plugins_url('css/owmweather-anim.min.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'owmw_styles');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Loads the JS/CSS for Slider1 - Slider2
///////////////////////////////////////////////////////////////////////////////////////////////////

function owmw_add_themes_scripts() {
	wp_register_style( 'owmw-flexslider-css', plugins_url( 'css/flexslider.css', __FILE__ ));
	wp_register_script( 'owmw-flexslider-js', plugins_url( 'js/jquery.flexslider-min.js#async', __FILE__ ));
	wp_register_style( 'bootstrap-css', plugins_url( 'css/bootstrap.min.css', __FILE__ ));
	wp_register_script( 'bootstrap-js', plugins_url( 'js/bootstrap.bundle.min.js#async', __FILE__ ));
	wp_register_script( 'chart-js', plugins_url( 'js/chart.min.js#async', __FILE__ ));
}
add_action( 'wp_enqueue_scripts', 'owmw_add_themes_scripts', 10, 1 );

///////////////////////////////////////////////////////////////////////////////////////////////////
//Loads the JS/CSS in admin
///////////////////////////////////////////////////////////////////////////////////////////////////

//Dashboard
function owmw_add_dashboard_scripts() {
	wp_enqueue_script( 'owmw-ajax-js', plugins_url('js/owm-weather-ajax.js', __FILE__), array('jquery'), '', true );

	$owmwAjax = array(
        'owmw_nonce' => wp_create_nonce('owmw_get_weather_nonce'),
        'owmw_url' => admin_url( 'admin-ajax.php' ) . "?lang=" . substr(get_locale(),0,2),
    );
	wp_localize_script( 'owmw-ajax-js', 'owmwAjax', $owmwAjax);

	wp_register_style('owmweather-css', plugins_url('css/owmweather.min.css', __FILE__));
	wp_enqueue_style('owmweather-css');

	wp_register_style('owmweather-anim-css', plugins_url('css/owmweather-anim.min.css', __FILE__));

	wp_register_style( 'bootstrap-css', plugins_url( 'css/bootstrap.min.css', __FILE__ ));
	wp_enqueue_style('bootstrap-css');
	wp_register_script( 'bootstrap-js', plugins_url( 'js/bootstrap.bundle.min.js#async', __FILE__ ));
	wp_enqueue_script('bootstrap-js');
}
add_action('admin_head-index.php', 'owmw_add_dashboard_scripts');

//Admin + Custom Post Type (new, listing)
function owmw_add_admin_scripts( $hook ) {

global $post;

	if ( $hook == 'post-new.php' || $hook == 'post.php') {

        if ( 'owm-weather' === $post->post_type ) {
			wp_register_style( 'owmweather-admin-css', plugins_url('css/owmweather-admin.min.css', __FILE__));
			wp_enqueue_style( 'owmweather-admin-css' );

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'color-picker-js', plugins_url('js/color-picker.js', __FILE__), array( 'wp-color-picker' ) );

			wp_enqueue_script( 'tabs-js', plugins_url( 'js/tabs.js', __FILE__ ), array( 'jquery-ui-tabs' ) );

			wp_enqueue_script( 'handlebars-js', plugins_url( 'js/handlebars-v1.3.0.js', __FILE__ ), array('typeahead-bundle-js') );
			wp_enqueue_script( 'typeahead-bundle-js', plugins_url( 'js/typeahead.bundle.min.js', __FILE__ ), array('jquery') , '2.0');
			wp_enqueue_script( 'autocomplete-js', plugins_url( 'js/owmw-autocomplete.js', __FILE__ ), '', '2.0', true );
		}
	}
}
add_action( 'admin_enqueue_scripts', 'owmw_add_admin_scripts', 10, 1 );

//OWM Weather Options page
function owmw_add_admin_options_scripts() {
			wp_register_style( 'owmweather-admin-css', plugins_url('css/owmweather-admin.min.css', __FILE__));
			wp_enqueue_style( 'owmweather-admin-css' );

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'color-picker-js', plugins_url('js/color-picker.js', __FILE__), array( 'wp-color-picker' ) );
			wp_enqueue_script( 'tabs-js', plugins_url( 'js/tabs.js', __FILE__ ), array( 'jquery-ui-tabs' ) );
}

if (isset($_GET['page']) && ($_GET['page'] == 'owmw-settings-admin')) {

	add_action('admin_enqueue_scripts', 'owmw_add_admin_options_scripts', 10, 1);
}

//Gutenberg
/* BE + FE */
// function capitainewp_block_assets() {

// 	// CSS des blocs
// 	wp_enqueue_style(
// 		'capitainewp-blocks',
// 		plugins_url( 'dist/blocks.style.build.css',  __FILE__ ),
// 		array( 'wp-blocks' )  // Dépendances
// 	);

// 	// Possibilité de charger un JS supplémentaire pour le front si besoin
// }
// add_action( 'enqueue_block_assets', 'capitainewp_block_assets' );

/*BE*/
// function owmw_gutenberg_boilerplate_block() {
//     wp_register_script('gutenberg-owmweather-js', plugins_url( 'js/blocks.build.js', __FILE__ ), array( 'wp-blocks', 'wp-element' ));

//     register_block_type( 'gutenberg-owmweather/owmweather', array('editor_script' => 'gutenberg-owmweather'));

//     wp_enqueue_script('gutenberg-owmweather');
// }
// add_action( 'enqueue_block_editor_assets', 'owmw_gutenberg_boilerplate_block' );

///////////////////////////////////////////////////////////////////////////////////////////////////
//Get all registered post types
///////////////////////////////////////////////////////////////////////////////////////////////////

function owmw_get_post_types() {
    global $wp_post_types;

    $args = array(
        'show_ui' => true,
    );

    $output = 'names'; // names or objects, note names is the default
    $operator = 'and'; // 'and' or 'or'

    $post_types = get_post_types( $args, $output, $operator );
    unset($post_types['attachment']);
    return $post_types;
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Add weather button in tinymce editor
///////////////////////////////////////////////////////////////////////////////////////////////////

//TinyMCE v4.x--------------------------------------------------------------------------------------
add_action('admin_head', 'owmw_add_button_v4');

function owmw_add_button_v4() {
    global $typenow;

    if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
    	return;
    }

    if( ! in_array( $typenow, owmw_get_post_types() ) )
        return;

	if ( get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "owmw_add_button_v4_plugin");
		add_filter('mce_buttons', 'owmw_add_button_v4_register');
	}
}

function owmw_add_button_v4_plugin($plugin_array) {
    $plugin_array['owmw_button_v4'] = plugins_url( 'js/owmw-tinymce.js', __FILE__ );
    return $plugin_array;
}

function owmw_add_button_v4_register($buttons) {
   array_push($buttons, "owmw_button_v4");
   return $buttons;
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Add duplicate link in OWM WEATHER List view
///////////////////////////////////////////////////////////////////////////////////////////////////

function owmw_duplicate_post_as_draft(){
	global $wpdb;
	if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'owmw_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
		wp_die('No weather to duplicate has been supplied!');
	}

	$post_id = sanitize_text_field(isset($_GET['post']) ? $_GET['post'] : $_POST['post']);

	$post = get_post( $post_id );

	$current_user = wp_get_current_user();
	$new_post_author = $current_user->ID;

	if (isset( $post ) && $post != null) {

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

		$new_post_id = wp_insert_post( $args );

 		$taxonomies = get_object_taxonomies($post->post_type);
		foreach ($taxonomies as $taxonomy) {
			$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
			wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
		}

 		$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
		if (count($post_meta_infos)!=0) {
			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
			foreach ($post_meta_infos as $meta_info) {
				$meta_key = $meta_info->meta_key;
				$meta_value = addslashes($meta_info->meta_value);
				$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
			}
			$sql_query.= implode(" UNION ALL ", $sql_query_sel);
			$wpdb->query($sql_query);
		}

 		wp_redirect( admin_url( 'post.php?action=edit&post=' . $new_post_id ) );
		exit;
	} else {
		wp_die('Weather creation failed, could not find original weather: ' . $post_id);
	}
}
add_action( 'admin_action_owmw_duplicate_post_as_draft', 'owmw_duplicate_post_as_draft', 999 );

function owmw_duplicate_post_link( $actions, $post ) {
	if ($post->post_type === 'owm-weather' && current_user_can('edit_posts')) {
		$actions['duplicate'] = '<a href="'.admin_url("admin.php?action=owmw_duplicate_post_as_draft&amp;post=" . $post->ID) . '" title="'.esc_html__('Duplicate this item','owm-weather').'" rel="permalink">'.esc_html__('Duplicate','owm-weather').'</a>';
	}
	return $actions;
}
add_filter( 'post_row_actions', 'owmw_duplicate_post_link', 999, 2 );

///////////////////////////////////////////////////////////////////////////////////////////////////
//Display metabox in Weather Custom Post Type
///////////////////////////////////////////////////////////////////////////////////////////////////

function owmw_init_metabox(){
	add_meta_box('owmweather_basic', __('OWM Weather Settings','owm-weather') .' - <a href="'.admin_url("options-general.php?page=owmw-settings-admin").'">'.__('OWM Weather global settings','owm-weather').'</a>', 'owmw_basic', 'owm-weather', 'advanced');
	add_meta_box('owmweather_shortcode', 'OWM Weather Shortcode', 'owmw_shortcode', 'owm-weather', 'side');
}
add_action('add_meta_boxes','owmw_init_metabox');

function owmw_shortcode($post){
	echo esc_html__( 'Copy and paste this shortcode anywhere in posts, pages, text widgets: ', 'owm-weather' );
	echo "<div class='shortcode'>";
	echo "<span class='owmw-highlight'>[owm-weather id=\"";
	echo get_the_ID();
	echo "\"/]</span>";
	echo "</div>";

	echo '<div class="shortcode-php">';
	echo esc_html__( 'If you need to display this weather anywhere in your theme, simply copy and paste this code snippet in your PHP file like sidebar.php: ', 'owm-weather' );
	echo "<span class='owmw-highlight'>echo do_shortcode('[owm-weather id=\"".get_the_ID()."\"]');</span>";
	echo "</div>";
}

function owmw_basic($post){
    $id = $post->ID;

    wp_enqueue_media();
    owmw_media_selector_print_scripts($id);

    $owmw_opt = [];
	$owmw_opt["city"] 					    = get_post_meta($id,'_owmweather_city',true);
	$owmw_opt["custom_city_name"]		    = get_post_meta($id,'_owmweather_custom_city_name',true);
	$owmw_opt["id_owm"]					    = get_post_meta($id,'_owmweather_id_owm',true);
	$owmw_opt["longitude"] 			        = get_post_meta($id,'_owmweather_longitude',true);
	$owmw_opt["latitude"] 		    	    = get_post_meta($id,'_owmweather_latitude',true);
	$owmw_opt["zip"] 		    	        = get_post_meta($id,'_owmweather_zip',true);
	$owmw_opt["country_code"] 		        = get_post_meta($id,'_owmweather_country_code',true);
	$owmw_opt["zip_country_code"] 		        = get_post_meta($id,'_owmweather_zip_country_code',true);
	$owmw_opt["temperature_unit"] 			= get_post_meta($id,'_owmweather_unit',true);
	$owmw_opt["time_format"]				    = get_post_meta($id,'_owmweather_time_format',true);
	$owmw_opt["custom_timezone"]	    		= get_post_meta($id,'_owmweather_custom_timezone',true);
	$owmw_opt["owm_language"] 		    	= get_post_meta($id,'_owmweather_owm_language',true);
	$owmw_opt["gtag"]              		    = get_post_meta($id,'_owmweather_gtag',true);
	$owmw_opt["bypass_exclude"]     		    = get_post_meta($id,'_owmweather_bypass_exclude',true);
	$owmw_opt["current_weather_symbol"]		= get_post_meta($id,'_owmweather_current_weather_symbol',true);
	$owmw_opt["current_city_name"]	    	= get_post_meta($id,'_owmweather_current_city_name',true);
	$owmw_opt["today_date_format"]	    	= owmw_getDefault($id,'_owmweather_today_date_format', 'none');
	$owmw_opt["current_weather_description"]	= get_post_meta($id,'_owmweather_current_weather_description',true);
	$owmw_opt["sunrise_sunset"] 			    = get_post_meta($id,'_owmweather_sunrise_sunset',true);
	$owmw_opt["moonrise_moonset"] 	    	= get_post_meta($id,'_owmweather_moonrise_moonset',true);
	$owmw_opt["wind"] 				    	= get_post_meta($id,'_owmweather_wind',true);
	$owmw_opt["wind_unit"] 				    = get_post_meta($id,'_owmweather_wind_unit',true);
	$owmw_opt["wind_icon_direction"] 	    = get_post_meta($id,'_owmweather_wind_icon_direction',true);
	$owmw_opt["humidity"] 				    = get_post_meta($id,'_owmweather_humidity',true);
	$owmw_opt["dew_point"] 				    = get_post_meta($id,'_owmweather_dew_point',true);
	$owmw_opt["pressure"]				    = get_post_meta($id,'_owmweather_pressure',true);
	$owmw_opt["cloudiness"]				    = get_post_meta($id,'_owmweather_cloudiness',true);
	$owmw_opt["precipitation"]			    = get_post_meta($id,'_owmweather_precipitation',true);
	$owmw_opt["visibility"]			        = get_post_meta($id,'_owmweather_visibility',true);
	$owmw_opt["uv_index"]			        = get_post_meta($id,'_owmweather_uv_index',true);
	$owmw_opt["alerts"]    				    = get_post_meta($id,'_owmweather_alerts',true);
	$owmw_opt["alerts_button_color"]         = get_post_meta($id,'_owmweather_alerts_button_color',true);
	$owmw_opt["hours_forecast_no"]		    = get_post_meta($id,'_owmweather_hours_forecast_no',true);
	$owmw_opt["hours_time_icons"]		    = get_post_meta($id,'_owmweather_hours_time_icons',true);
	$owmw_opt["current_temperature"]		    = get_post_meta($id,'_owmweather_current_temperature',true);
	$owmw_opt["current_feels_like"]		    = get_post_meta($id,'_owmweather_current_feels_like',true);
	$owmw_opt["display_temperature_unit"]	= get_post_meta($id,'_owmweather_display_temperature_unit',true);
	$owmw_opt["days_forecast_no"]		    = get_post_meta($id,'_owmweather_forecast_no',true);
	$owmw_opt["forecast_precipitations"]     = get_post_meta($id,'_owmweather_forecast_precipitations',true);
	$owmw_opt["display_length_days_names"]	= owmw_getDefault($id,'_owmweather_display_length_days_names', 'short');
 	$owmw_opt["disable_spinner"]   			= get_post_meta($id,'_owmweather_disable_spinner',true);
 	$owmw_opt["disable_anims"]   			= get_post_meta($id,'_owmweather_disable_anims',true);
	$owmw_opt["background_color"]	   		= get_post_meta($id,'_owmweather_background_color',true);
	$owmw_opt["background_image"]	   		= get_post_meta($id,'_owmweather_background_image',true);
	$owmw_opt["text_color"]		        	= get_post_meta($id,'_owmweather_text_color',true);
	$owmw_opt["border_color"]		        = get_post_meta($id,'_owmweather_border_color',true);
	$owmw_opt["border_width"]		        = owmw_getDefault($id, '_owmweather_border_width', $owmw_opt["border_color"] == '' ? '0' : '1');
	$owmw_opt["border_style"]		        = get_post_meta($id,'_owmweather_border_style',true);
	$owmw_opt["border_radius"]		        = owmw_getDefault($id, '_owmweather_border_radius', '0');
	$owmw_opt["custom_css"]	    			= get_post_meta($id,'_owmweather_custom_css',true);
	$owmw_opt["size"] 			    		= get_post_meta($id,'_owmweather_size',true);
	$owmw_opt["owm_link"]			    	= get_post_meta($id,'_owmweather_owm_link',true);
	$owmw_opt["last_update"]				    = get_post_meta($id,'_owmweather_last_update',true);
	$owmw_opt["font"]      		    		= get_post_meta($id,'_owmweather_font',true);
	$owmw_opt["template"]     		    	= get_post_meta($id,'_owmweather_template',true);
	$owmw_opt["iconpack"]     			    = get_post_meta($id,'_owmweather_iconpack',true);
	$owmw_opt["map"] 	    				= get_post_meta($id,'_owmweather_map',true);
	$owmw_opt["map_height"]	    			= get_post_meta($id,'_owmweather_map_height',true);
	$owmw_opt["map_opacity"]		    		= owmw_getDefault($id,'_owmweather_map_opacity', "0.5");
	$owmw_opt["map_zoom"]			    	= owmw_getDefault($id,'_owmweather_map_zoom', '9');
	$owmw_opt["map_disable_zoom_wheel"]		= get_post_meta($id,'_owmweather_map_disable_zoom_wheel',true);
	$owmw_opt["map_stations"]			    = get_post_meta($id,'_owmweather_map_stations',true);
	$owmw_opt["map_clouds"]				    = get_post_meta($id,'_owmweather_map_clouds',true);
	$owmw_opt["map_precipitation"]		    = get_post_meta($id,'_owmweather_map_precipitation',true);
	$owmw_opt["map_snow"]    				= get_post_meta($id,'_owmweather_map_snow',true);
	$owmw_opt["map_wind"]	    			= get_post_meta($id,'_owmweather_map_wind',true);
	$owmw_opt["map_temperature"]	    		= get_post_meta($id,'_owmweather_map_temperature',true);
	$owmw_opt["map_pressure"]		    	= get_post_meta($id,'_owmweather_map_pressure',true);

	$owmw_opt["chart_height"]	    		= owmw_getDefault($id,'_owmweather_chart_height', '400');
	$owmw_opt["chart_background_color"]		= owmw_getDefault($id, '_owmweather_chart_background_color', '#fff');
	$owmw_opt["chart_border_color"]		    = owmw_getDefault($id, '_owmweather_chart_border_color', '');
	$owmw_opt["chart_border_width"]		    = owmw_getDefault($id, '_owmweather_chart_border_width', $owmw_opt["chart_border_color"] == '' ? '0' : '1');
	$owmw_opt["chart_border_style"]		    = get_post_meta($id,'_owmweather_chart_border_style',true);
	$owmw_opt["chart_border_radius"]		    = owmw_getDefault($id, '_owmweather_chart_border_radius', '0');
	$owmw_opt["chart_temperature_color"]	    = owmw_getDefault($id,'_owmweather_chart_temperature_color', '#d5202a');
	$owmw_opt["chart_feels_like_color"]	    = owmw_getDefault($id,'_owmweather_chart_feels_like_color', '#f83');
	$owmw_opt["chart_dew_point_color"]	    = owmw_getDefault($id,'_owmweather_chart_dew_point_color', '#ac54a0');

	$owmw_opt["table_background_color"]		= owmw_getDefault($id, '_owmweather_table_background_color', '');
	$owmw_opt["table_border_color"]		    = owmw_getDefault($id, '_owmweather_table_border_color', '');
	$owmw_opt["table_border_width"]		    = owmw_getDefault($id, '_owmweather_table_border_width', $owmw_opt["table_border_color"] == '' ? '0' : '1');
	$owmw_opt["table_border_style"]		    = get_post_meta($id,'_owmweather_table_border_style',true);
	$owmw_opt["table_border_radius"]		    = owmw_getDefault($id, '_owmweather_table_border_radius', '0');
	$owmw_opt["table_text_color"]		    = owmw_getDefault($id, '_owmweather_table_text_color', '');


	function owmw_get_admin_api_key2() {
		$options = get_option("owmw_option_name");
		if ( ! empty ( $options["owmw_advanced_api_key"] ) ) {
			return $options["owmw_advanced_api_key"];
		} else {
			return '46c433f6ba7dd4d29d5718dac3d7f035';//bugbug
		}
	};

  ob_start();
?>
<div id="owmweather-tabs">
		<ul>
			<li><a href="#tabs-1"><?php esc_html_e( 'Basic', 'owm-weather' ) ?></a></li>
			<li><a href="#tabs-2"><?php esc_html_e( 'Display', 'owm-weather' ) ?></a></li>
			<li><a href="#tabs-3"><?php esc_html_e( 'Layout', 'owm-weather' ) ?></a></li>
			<li><a href="#tabs-4"><?php esc_html_e( 'Map', 'owm-weather' ) ?></a></li>
		</ul>

		<div id="tabs-1">
		    <p class=" subsection-title">
  			    Get weather by ...
		    </p>
              <div id="owmweather-owm-param">
      			<ul>
  	    			<li><a href="#fragment-1"><?php esc_html_e( 'City Id', 'owm-weather' ) ?></a></li>
  		    		<li><a href="#fragment-2"><?php esc_html_e( 'Longitude/Latitude', 'owm-weather' ) ?></a></li>
  				    <li><a href="#fragment-3"><?php esc_html_e( 'Zip/Country', 'owm-weather' ) ?></a></li>
  			    	<li><a href="#fragment-4"><?php esc_html_e( 'City/Country', 'owm-weather' ) ?></a></li>
  				    <li><a href="#fragment-5"><?php esc_html_e( 'Visitor\'s Location', 'owm-weather' ) ?></a></li>
      			</ul>
                  <div id="fragment-1">
        				<p>
      					<label for="owmweather_id_owm_meta"><?php esc_html_e( 'OpenWeatherMap City Id', 'owm-weather' ) ?><span class="mandatory">*</span> <a href="https://openweathermap.org/find?q=" target="_blank"> <?php esc_html_e('Find my City Id','owm-weather') ?></a><span class="dashicons dashicons-external"></span></label>
      					<input id="owmweather_id_owm" type="number" name="owmweather_id_owm" value="<?php esc_attr_e($owmw_opt["id_owm"]) ?>" />
      				</p>
                  </div>
                  <div id="fragment-2">
      				<p>
      					<label for="owmweather_latitude_meta"><?php esc_html_e( 'Latitude?', 'owm-weather' ) ?><span class="mandatory">*</span></label>
      					<input id="owmweather_latitude_meta" type="number" min="-90" max="90" step="0.0000001" name="owmweather_latitude" value="<?php esc_attr_e($owmw_opt["latitude"]) ?>" />
      				</p>
      				<p>
      					<label for="owmweather_longitude_meta"><?php esc_html_e( 'Longitude?', 'owm-weather' ) ?><span class="mandatory">*</span></label>
      					<input id="owmweather_longitude_meta" type="number" min="-180" max="180" step="0.000001" name="owmweather_longitude" value="<?php esc_attr_e($owmw_opt["longitude"]) ?>" />
      				</p>
      				<p><em><?php esc_html_e('If you enter an OpenWeatherMap City Id, it will automatically bypass the  Latitude/Longitude fields.','owm-weather') ?></em></p>
                  </div>
                  <div id="fragment-3">
      				<p>
      					<label for="owmweather_zip_meta"><?php esc_html_e( 'Zip code?', 'owm-weather' ) ?><span class="mandatory">*</span></label>
      					<input id="owmweather_zip_meta" name="owmweather_zip" value="<?php esc_attr_e($owmw_opt["zip"]) ?>" />
      				</p>
      				<p>
      					<label for="owmweather_zip_country_meta"><?php esc_html_e( '2-letter country code?', 'owm-weather' ) ?>(<?php esc_html_e("Default: US", 'owm-weather') ?>)</label>
      					<input id="owmweather_zip_country_meta" class="countrycodes typeahead" type="text" name="owmweather_zip_country_code" maxlength="2" value="<?php esc_attr_e($owmw_opt["zip_country_code"]) ?>" />
      				</p>
      				<p><em><?php esc_html_e('If you enter an OpenWeatherMap City Id or Latitude/Longitude, it will automatically bypass the Zip and Country fields.','owm-weather') ?></em></p>
                  </div>
                  <div id="fragment-4">
      				<p>
      					<label for="owmweather_city_meta"><?php esc_html_e( 'City', 'owm-weather' ) ?><span class="mandatory">*</span></label>
      					<input id="owmweather_city_meta" data_appid="<?php esc_attr_e(owmw_get_admin_api_key2()) ?>" class="cities typeahead" type="text" name="owmweather_city" placeholder="<?php esc_attr_e('Enter your city','owm-weather') ?>" value="<?php esc_attr_e($owmw_opt["city"]) ?>" />
      				</p>
      				<p>
      					<label for="owmweather_country_meta"><?php esc_html_e( 'Country?', 'owm-weather' ) ?><span class="mandatory">*</span></label>
      					<input id="owmweather_country_meta" class="countries typeahead" type="text" name="owmweather_country_code" value="<?php esc_attr_e($owmw_opt["country_code"]) ?>" />
      				</p>
      				<p><em><?php esc_html_e('If you enter an OpenWeatherMap City Id, Latitude/Longitude or Zip/Country, it will automatically bypass the City and Country fields.','owm-weather') ?></em></p>
                  </div>
                  <div id="fragment-5">
      				<p><em><?php esc_html_e('Leave City Id, Longitude/Latitude, Zip/Country and City/Country empty to use the visitor\'s location.','owm-weather') ?></em></p>
                  </div>
              </div>
		    <p class=" subsection-title">
  			    Basic
		    </p>
			<p>
				<label for="owmweather_custom_city_name_meta"><?php esc_html_e( 'Custom city title', 'owm-weather' ) ?></label>
				<input id="owmweather_custom_city_name_meta" type="text" name="owmweather_custom_city_name" value="<?php esc_attr_e($owmw_opt["custom_city_name"]) ?>" />
			</p>
			<p>
				<label for="unit_meta"><?php esc_html_e( 'Measurement system?', 'owm-weather' ) ?></label>
				<select name="owmweather_unit">
					<option <?php echo selected( 'imperial', $owmw_opt["temperature_unit"], false ) ?>value="imperial"><?php esc_html_e( 'Imperial', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'metric', $owmw_opt["temperature_unit"], false ) ?>value="metric"><?php esc_html_e( 'Metric', 'owm-weather' ) ?></option>
				</select>
			</p>
			<p>
				<label for="owmweather_time_format_meta"><?php esc_html_e( 'Time format?', 'owm-weather' ) ?></label>
				<select name="owmweather_time_format">
					<option <?php echo selected( '12', $owmw_opt["time_format"], false ) ?>value="12"><?php esc_html_e( '12 h', 'owm-weather' ) ?></option>
					<option <?php echo selected( '24', $owmw_opt["time_format"], false ) ?>value="24"><?php esc_html_e( '24 h', 'owm-weather' ) ?></option>
				</select>
			</p>
			<p>
				<label for="owmweather_custom_timezone_meta"><?php esc_html_e( 'Timezone? (default: WordPress general settings)', 'owm-weather' ) ?></label>
				<select name="owmweather_custom_timezone" id="owmweather_custom_timezone_meta">
					<option <?php echo selected( 'Default', $owmw_opt["custom_timezone"], false ) ?>value="Default"><?php esc_html_e( 'WordPress timezone', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'local', $owmw_opt["custom_timezone"], false ) ?>value="local"><?php esc_html_e( 'Local timezone', 'owm-weather' ) ?></option>
					<option <?php echo selected( '-12', $owmw_opt["custom_timezone"], false ) ?>value="-12"><?php esc_html_e( 'UTC -12', 'owm-weather' ) ?></option>
					<option <?php echo selected( '-11', $owmw_opt["custom_timezone"], false ) ?>value="-11"><?php esc_html_e( 'UTC -11', 'owm-weather' ) ?></option>
					<option <?php echo selected( '-10', $owmw_opt["custom_timezone"], false ) ?>value="-10"><?php esc_html_e( 'UTC -10', 'owm-weather' ) ?></option>
					<option <?php echo selected( '-9', $owmw_opt["custom_timezone"], false ) ?>value="-9"><?php esc_html_e( 'UTC -9', 'owm-weather' ) ?></option>
					<option <?php echo selected( '-8', $owmw_opt["custom_timezone"], false ) ?>value="-8"><?php esc_html_e( 'UTC -8', 'owm-weather' ) ?></option>
					<option <?php echo selected( '-7', $owmw_opt["custom_timezone"], false ) ?>value="-7"><?php esc_html_e( 'UTC -7', 'owm-weather' ) ?></option>
					<option <?php echo selected( '-6', $owmw_opt["custom_timezone"], false ) ?>value="-6"><?php esc_html_e( 'UTC -6', 'owm-weather' ) ?></option>
					<option <?php echo selected( '-5', $owmw_opt["custom_timezone"], false ) ?>value="-5"><?php esc_html_e( 'UTC -5', 'owm-weather' ) ?></option>
					<option <?php echo selected( '-4', $owmw_opt["custom_timezone"], false ) ?>value="-4"><?php esc_html_e( 'UTC -4', 'owm-weather' ) ?></option>
					<option <?php echo selected( '-3', $owmw_opt["custom_timezone"], false ) ?>value="-3"><?php esc_html_e( 'UTC -3', 'owm-weather' ) ?></option>
					<option <?php echo selected( '-2', $owmw_opt["custom_timezone"], false ) ?>value="-2"><?php esc_html_e( 'UTC -2', 'owm-weather' ) ?></option>
					<option <?php echo selected( '-1', $owmw_opt["custom_timezone"], false ) ?>value="-1"><?php esc_html_e( 'UTC -1', 'owm-weather' ) ?></option>
					<option <?php echo selected( '0', $owmw_opt["custom_timezone"], false ) ?>value="0"><?php esc_html_e( 'UTC 0', 'owm-weather' ) ?></option>
					<option <?php echo selected( '1', $owmw_opt["custom_timezone"], false ) ?>value="1"><?php esc_html_e( 'UTC +1', 'owm-weather' ) ?></option>
					<option <?php echo selected( '2', $owmw_opt["custom_timezone"], false ) ?>value="2"><?php esc_html_e( 'UTC +2', 'owm-weather' ) ?></option>
					<option <?php echo selected( '3', $owmw_opt["custom_timezone"], false ) ?>value="3"><?php esc_html_e( 'UTC +3', 'owm-weather' ) ?></option>
					<option <?php echo selected( '4', $owmw_opt["custom_timezone"], false ) ?>value="4"><?php esc_html_e( 'UTC +4', 'owm-weather' ) ?></option>
					<option <?php echo selected( '5', $owmw_opt["custom_timezone"], false ) ?>value="5"><?php esc_html_e( 'UTC +5', 'owm-weather' ) ?></option>
					<option <?php echo selected( '6', $owmw_opt["custom_timezone"], false ) ?>value="6"><?php esc_html_e( 'UTC +6', 'owm-weather' ) ?></option>
					<option <?php echo selected( '7', $owmw_opt["custom_timezone"], false ) ?>value="7"><?php esc_html_e( 'UTC +7', 'owm-weather' ) ?></option>
					<option <?php echo selected( '8', $owmw_opt["custom_timezone"], false ) ?>value="8"><?php esc_html_e( 'UTC +8', 'owm-weather' ) ?></option>
					<option <?php echo selected( '9', $owmw_opt["custom_timezone"], false ) ?>value="9"><?php esc_html_e( 'UTC +9', 'owm-weather' ) ?></option>
					<option <?php echo selected( '10', $owmw_opt["custom_timezone"], false ) ?>value="10"><?php esc_html_e( 'UTC +10', 'owm-weather' ) ?></option>
					<option <?php echo selected( '11', $owmw_opt["custom_timezone"], false ) ?>value="11"><?php esc_html_e( 'UTC +11', 'owm-weather' ) ?></option>
					<option <?php echo selected( '12', $owmw_opt["custom_timezone"], false ) ?>value="12"><?php esc_html_e( 'UTC +12', 'owm-weather' ) ?></option>
				</select>
			</p>
			<p>
				<label for="owmweather_owm_language_meta"><?php esc_html_e( 'OpenWeatherMap language?', 'owm-weather' ) ?></label>
				<select name="owmweather_owm_language" id="owmweather_owm_language_meta">
					<option <?php echo selected( 'Default', $owmw_opt["owm_language"], false ) ?>value="Default"><?php esc_html_e( 'Default', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'af', $owmw_opt["owm_language"], false ) ?>value="af"><?php esc_html_e( 'Afrikaans', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'al', $owmw_opt["owm_language"], false ) ?>value="al"><?php esc_html_e( 'Albanian', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'ar', $owmw_opt["owm_language"], false ) ?>value="ar"><?php esc_html_e( 'Arabic', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'az', $owmw_opt["owm_language"], false ) ?>value="az"><?php esc_html_e( 'Azerbaijani', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'eu', $owmw_opt["owm_language"], false ) ?>value="eu"><?php esc_html_e( 'Basque', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'bg', $owmw_opt["owm_language"], false ) ?>value="bg"><?php esc_html_e( 'Bulgarian', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'ca', $owmw_opt["owm_language"], false ) ?>value="ca"><?php esc_html_e( 'Catalan', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'zh_cn', $owmw_opt["owm_language"], false ) ?>value="zh_cn"><?php esc_html_e( 'Chinese Simplified', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'zh_tw', $owmw_opt["owm_language"], false ) ?>value="zh_tw"><?php esc_html_e( 'Chinese Traditional', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'hr', $owmw_opt["owm_language"], false ) ?>value="hr"><?php esc_html_e( 'Croatian', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'cz', $owmw_opt["owm_language"], false ) ?>value="cz"><?php esc_html_e( 'Czech', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'da', $owmw_opt["owm_language"], false ) ?>value="da"><?php esc_html_e( 'Danish', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'nl', $owmw_opt["owm_language"], false ) ?>value="nl"><?php esc_html_e( 'Dutch', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'en', $owmw_opt["owm_language"], false ) ?>value="en"><?php esc_html_e( 'English', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'fi', $owmw_opt["owm_language"], false ) ?>value="fi"><?php esc_html_e( 'Finnish', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'fr', $owmw_opt["owm_language"], false ) ?>value="fr"><?php esc_html_e( 'French', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'gl', $owmw_opt["owm_language"], false ) ?>value="gl"><?php esc_html_e( 'Galician', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'de', $owmw_opt["owm_language"], false ) ?>value="de"><?php esc_html_e( 'German', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'el', $owmw_opt["owm_language"], false ) ?>value="el"><?php esc_html_e( 'Greek', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'he', $owmw_opt["owm_language"], false ) ?>value="he"><?php esc_html_e( 'Hebrew', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'hi', $owmw_opt["owm_language"], false ) ?>value="hi"><?php esc_html_e( 'Hindi', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'hu', $owmw_opt["owm_language"], false ) ?>value="hu"><?php esc_html_e( 'Hungarian', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'id', $owmw_opt["owm_language"], false ) ?>value="id"><?php esc_html_e( 'Indonesian', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'it', $owmw_opt["owm_language"], false ) ?>value="it"><?php esc_html_e( 'Italian', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'ja', $owmw_opt["owm_language"], false ) ?>value="ja"><?php esc_html_e( 'Japanese', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'kr', $owmw_opt["owm_language"], false ) ?>value="kr"><?php esc_html_e( 'Korean', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'la', $owmw_opt["owm_language"], false ) ?>value="la"><?php esc_html_e( 'Latvian', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'lt', $owmw_opt["owm_language"], false ) ?>value="lt"><?php esc_html_e( 'Lithuanian', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'mk', $owmw_opt["owm_language"], false ) ?>value="mk"><?php esc_html_e( 'Macedonian', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'no', $owmw_opt["owm_language"], false ) ?>value="no"><?php esc_html_e( 'Norwegian', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'fa', $owmw_opt["owm_language"], false ) ?>value="fa"><?php esc_html_e( 'Persian (Farsi)', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'pl', $owmw_opt["owm_language"], false ) ?>value="pl"><?php esc_html_e( 'Polish', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'pt', $owmw_opt["owm_language"], false ) ?>value="pt"><?php esc_html_e( 'Portuguese', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'pt', $owmw_opt["owm_language"], false ) ?>value="pt"><?php esc_html_e( 'Português Brasil', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'ro', $owmw_opt["owm_language"], false ) ?>value="ro"><?php esc_html_e( 'Romanian', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'ru', $owmw_opt["owm_language"], false ) ?>value="ru"><?php esc_html_e( 'Russian', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'sr', $owmw_opt["owm_language"], false ) ?>value="sr"><?php esc_html_e( 'Serbian', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'sv', $owmw_opt["owm_language"], false ) ?>value="sv"><?php esc_html_e( 'Swedish', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'sk', $owmw_opt["owm_language"], false ) ?>value="sk"><?php esc_html_e( 'Slovak', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'sl', $owmw_opt["owm_language"], false ) ?>value="sl"><?php esc_html_e( 'Slovenian', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'sp', $owmw_opt["owm_language"], false ) ?>value="sp"><?php esc_html_e( 'Spanish', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'th', $owmw_opt["owm_language"], false ) ?>value="th"><?php esc_html_e( 'Thai', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'tr', $owmw_opt["owm_language"], false ) ?>value="tr"><?php esc_html_e( 'Turkish', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'ua', $owmw_opt["owm_language"], false ) ?>value="ua"><?php esc_html_e( 'Ukrainian', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'vi', $owmw_opt["owm_language"], false ) ?>value="vi"><?php esc_html_e( 'Vietnamese', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'zu', $owmw_opt["owm_language"], false ) ?>value="zu"><?php esc_html_e( 'Zulu', 'owm-weather' ) ?></option>
				</select>
			</p>
			<p class="misc subsection-title">
				<?php esc_html_e( 'Misc', 'owm-weather' ) ?>
			</p>
			<p>
				<label for="owmweather_gtag_meta">
					<input type="checkbox" name="owmweather_gtag" id="owmweather_gtag_meta" value="yes" <?php echo checked( $owmw_opt["gtag"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Google Tag Manager dataLayer?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_bypass_exclude_meta">
					<input type="checkbox" name="owmweather_bypass_exclude" id="owmweather_bypass_exclude_meta" value="yes" <?php echo checked( $owmw_opt["bypass_exclude"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Exclude from System Settings and Parameter Bypass?', 'owm-weather' ) ?>
				</label>
			</p>
		</div>
		<div id="tabs-2">
		    <p style="border: 2px solid;padding: 5px;">
  			    Select the information you would like to show on your weather shortcode.
		    </p>
			<p class="owmw-dates subsection-title">
				<?php esc_html_e( 'Current weather', 'owm-weather' ) ?>
			</p>
			<p>
				<label for="owmweather_current_city_name_meta">
					<input type="checkbox" name="owmweather_current_city_name" id="owmweather_current_city_name_meta" value="yes" <?php echo checked( $owmw_opt["current_city_name"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Current weather city name?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_current_weather_symbol_meta">
					<input type="checkbox" name="owmweather_current_weather_symbol" id="owmweather_current_weather_symbol_meta" value="yes" <?php echo checked( $owmw_opt["current_weather_symbol"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Current weather symbol?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_current_temperature_meta">
					<input type="checkbox" name="owmweather_current_temperature" id="owmweather_current_temperature_meta" value="yes" <?php echo checked( $owmw_opt["current_temperature"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Current temperature?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_current_feels_like_meta">
					<input type="checkbox" name="owmweather_current_feels_like" id="owmweather_current_feels_like_meta" value="yes" <?php echo checked( $owmw_opt["current_feels_like"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Current feels like temperature?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_current_weather_description_meta">
					<input type="checkbox" name="owmweather_current_weather_description" id="owmweather_current_weather_description_meta" value="yes" <?php echo checked( $owmw_opt["current_weather_description"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Current weather short condition?', 'owm-weather' ) ?>
				</label>
			</p>
			<p class="temperatures subsection-title">
				<?php esc_html_e( 'Temperatures', 'owm-weather' ) ?>
			</p>
			<p>
				<label for="owmweather_display_temperature_unit_meta">
					<input type="checkbox" name="owmweather_display_temperature_unit" id="owmweather_display_temperature_unit_meta" value="yes" <?php echo checked( $owmw_opt["display_temperature_unit"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Temperatures unit (C / F)?', 'owm-weather' ) ?>
				</label>
			</p>
			<p class="owmw-dates subsection-title">
				<?php esc_html_e( 'Date, Sunrise/Sunset and Moonrise/Moonset', 'owm-weather' ) ?>
			</p>
			<p>
				<label for="owmweather_today_date_format_none_meta">
					<input type="radio" name="owmweather_today_date_format" id="owmweather_today_date_format_none_meta" value="none" <?php echo checked( $owmw_opt["today_date_format"], 'none', false ) ?>/>
						<?php esc_html_e( 'No date (default)?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_today_date_format_week_meta">
					<input type="radio" name="owmweather_today_date_format" id="owmweather_today_date_format_week_meta" value="day" <?php echo checked( $owmw_opt["today_date_format"], 'day', false ) ?>/>
						<?php esc_html_e( 'Day of the week (eg: Sunday)?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_today_date_format_calendar_meta">
					<input type="radio" name="owmweather_today_date_format" id="owmweather_today_date_format_calendar_meta" value="date" <?php echo checked( $owmw_opt["today_date_format"], 'date', false ) ?>/>
						<?php esc_html_e( 'Today\'s date (based on your WordPress General Settings)?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_sunrise_sunset_meta">
					<input type="checkbox" name="owmweather_sunrise_sunset" id="owmweather_sunrise_sunset_meta" value="yes" <?php echo checked( $owmw_opt["sunrise_sunset"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Sunrise + sunset?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_moonrise_moonset_meta">
					<input type="checkbox" name="owmweather_moonrise_moonset" id="owmweather_moonrise_moonset_meta" value="yes" <?php echo checked( $owmw_opt["moonrise_moonset"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Moonrise + moonset?', 'owm-weather' ) ?>
				</label>
			</p>
			<p class="owmw-misc subsection-title">
				<?php esc_html_e( 'Misc', 'owm-weather' ) ?>
			</p>
			<p>
				<label for="owmweather_wind_meta">
					<input type="checkbox" name="owmweather_wind" id="owmweather_wind_meta" value="yes" <?php echo checked( $owmw_opt["wind"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Wind?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_wind_unit_meta"><?php esc_html_e( 'Wind unit: ', 'owm-weather' ) ?></label>
				<select name="owmweather_wind_unit">
					<option <?php echo selected( 'mi/h', $owmw_opt["wind_unit"], false ) ?>value="mi/h"><?php esc_html_e( 'mi/h', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'm/s', $owmw_opt["wind_unit"], false ) ?>value="m/s"><?php esc_html_e( 'm/s', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'km/h', $owmw_opt["wind_unit"], false ) ?>value="km/h"><?php esc_html_e( 'km/h', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'kt', $owmw_opt["wind_unit"], false ) ?>value="kt"><?php esc_html_e( 'kt', 'owm-weather' ) ?></option>
				</select>
			</p>
			<p>
				<label for="owmweather_wind_icon_meta"><?php esc_html_e( 'Wind icons show ', 'owm-weather' ) ?></label>
				<label for="owmweather_wind_icon_to_meta">
					<input type="radio" name="owmweather_wind_icon_direction" id="owmweather_wind_icon_to_meta" value="to" <?php echo checked( $owmw_opt["wind_icon_direction"], 'to', false ) ?>/>
						<?php esc_html_e( ' direction of the wind', 'owm-weather' ) ?>
				</label>
				<label for="owmweather_wind_icon_from_meta">
					<input type="radio" name="owmweather_wind_icon_direction" id="owmweather_wind_icon_from_meta" value="to" <?php echo checked( $owmw_opt["wind_icon_direction"], 'from', false ) ?>/>
						<?php esc_html_e( 'source of wind flow', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_humidity_meta">
					<input type="checkbox" name="owmweather_humidity" id="owmweather_humidity_meta" value="yes" <?php echo checked( $owmw_opt["humidity"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Humidity?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_dew_point_meta">
					<input type="checkbox" name="owmweather_dew_point" id="owmweather_dew_point_meta" value="yes" <?php echo checked( $owmw_opt["dew_point"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Dew Point?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_pressure_meta">
					<input type="checkbox" name="owmweather_pressure" id="owmweather_pressure_meta" value="yes" <?php echo checked( $owmw_opt["pressure"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Pressure?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_cloudiness_meta">
					<input type="checkbox" name="owmweather_cloudiness" id="owmweather_cloudiness_meta" value="yes" <?php echo checked( $owmw_opt["cloudiness"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Cloudiness?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_precipitation_meta">
					<input type="checkbox" name="owmweather_precipitation" id="owmweather_precipitation_meta" value="yes" <?php echo checked( $owmw_opt["precipitation"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Precipitation?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_visibility_meta">
					<input type="checkbox" name="owmweather_visibility" id="owmweather_visibility_meta" value="yes" <?php echo checked( $owmw_opt["visibility"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Visibility?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_uv_index_meta">
					<input type="checkbox" name="owmweather_uv_index" id="owmweather_uv_index_meta" value="yes" <?php echo checked( $owmw_opt["uv_index"], 'yes', false ) ?>/>
						<?php esc_html_e( 'UV Index?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_alerts_meta">
					<input type="checkbox" name="owmweather_alerts" id="owmweather_alerts_meta" value="yes" <?php echo checked( $owmw_opt["alerts"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Alerts?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_alerts_button_color"><?php esc_html_e( 'Alert Button color', 'owm-weather' ) ?></label>
				<input name="owmweather_alerts_button_color" type="text" value="<?php esc_attr_e($owmw_opt["alerts_button_color"]) ?>" class="owmweather_color_picker" />
			</p>
			<p class="hour subsection-title">
				<?php esc_html_e( 'Hourly Forecast', 'owm-weather' ) ?>
			</p>
			<p>
				<label for="owmweather_hours_forecast_no_meta"><?php esc_html_e( 'How many hours?', 'owm-weather' ) ?></label>
				<select name="owmweather_hours_forecast_no"><?php echo owmw_generate_hour_options($owmw_opt["hours_forecast_no"]) ?></select>
			</p>
			<p>
				<label for="owmweather_hours_time_icons_meta">
					<input type="checkbox" name="owmweather_hours_time_icons" id="owmweather_hours_time_icons_meta" value="yes" <?php echo checked( $owmw_opt["hours_time_icons"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Display time icons?', 'owm-weather' ) ?>
				</label>
			</p>
			<p class="forecast subsection-title">
				<?php esc_html_e( 'Daily Forecast', 'owm-weather' ) ?>
			</p>
			<p>
				<label for="owmweather_forecast_no_meta"><?php esc_html_e( 'How many days?', 'owm-weather' ) ?></label>
				<select name="owmweather_forecast_no">
					<option <?php echo selected( '0', $owmw_opt["days_forecast_no"], false ) ?>value="0"><?php esc_html_e( 'None', 'owm-weather' ) ?></option>
					<option <?php echo selected( '1', $owmw_opt["days_forecast_no"], false ) ?>value="1"><?php esc_html_e( 'Today', 'owm-weather' ) ?></option>
					<option <?php echo selected( '2', $owmw_opt["days_forecast_no"], false ) ?>value="2"><?php esc_html_e( 'Today + 1 day', 'owm-weather' ) ?></option>
					<option <?php echo selected( '3', $owmw_opt["days_forecast_no"], false ) ?>value="3"><?php esc_html_e( 'Today + 2 days', 'owm-weather' ) ?></option>
					<option <?php echo selected( '4', $owmw_opt["days_forecast_no"], false ) ?>value="4"><?php esc_html_e( 'Today + 3 days', 'owm-weather' ) ?></option>
					<option <?php echo selected( '5', $owmw_opt["days_forecast_no"], false ) ?>value="5"><?php esc_html_e( 'Today + 4 days', 'owm-weather' ) ?></option>
					<option <?php echo selected( '6', $owmw_opt["days_forecast_no"], false ) ?>value="6"><?php esc_html_e( 'Today + 5 days', 'owm-weather' ) ?></option>
					<option <?php echo selected( '7', $owmw_opt["days_forecast_no"], false ) ?>value="7"><?php esc_html_e( 'Today + 6 days', 'owm-weather' ) ?></option>
					<option <?php echo selected( '8', $owmw_opt["days_forecast_no"], false ) ?>value="8"><?php esc_html_e( 'Today + 7 days', 'owm-weather' ) ?></option>
				</select>
			</p>
			<p>
				<label for="owmweather_forecast_precipitations_meta">
					<input type="checkbox" name="owmweather_forecast_precipitations" id="owmweather_forecast_precipitations_meta" value="yes" <?php echo checked( $owmw_opt["forecast_precipitations"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Forecast Precipitations?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_display_length_days_names_short_meta">
					<input type="radio" name="owmweather_display_length_days_names" id="owmweather_display_length_days_names_short_meta" value="short" <?php echo checked( $owmw_opt["display_length_days_names"], 'short', false ) ?>/>
						<?php esc_html_e( 'Short days names?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_display_length_days_names_normal_meta">
					<input type="radio" name="owmweather_display_length_days_names" id="owmweather_display_length_days_names_normal_meta" value="normal" <?php echo checked( $owmw_opt["display_length_days_names"], 'normal', false ) ?>/>
						<?php esc_html_e( 'Normal days names?', 'owm-weather' ) ?>
				</label>
			</p>
			<p class="footer subsection-title">
				<?php esc_html_e( 'Footer', 'owm-weather' ) ?>
			</p>
			<p>
				<label for="owmweather_owm_link_meta">
					<input type="checkbox" name="owmweather_owm_link" id="owmweather_owm_link_meta" value="yes" <?php echo checked( $owmw_opt["owm_link"], 'yes', false ) ?>/>
					<?php esc_html_e( 'Link to OpenWeatherMap?', 'owm-weather' ) ?>
				</label>
			</p>

			<p>
				<label for="owmweather_last_update_meta">
					<input type="checkbox" name="owmweather_last_update" id="owmweather_last_update_meta" value="yes" <?php echo checked( $owmw_opt["last_update"], 'yes', false ) ?>/>

					<?php esc_html_e( 'Update date?', 'owm-weather' ) ?>
				</label>
			</p>
		</div>
		<div id="tabs-3">
		    <p style="border: 2px solid;padding: 5px;">
  			    Select the layout styling for your weather shortcode.
		    </p>
			<p>
				<label for="template_meta"><?php esc_html_e( 'Template', 'owm-weather' ) ?></label>
				<select name="owmweather_template">
					<option <?php echo selected( 'Default', $owmw_opt["template"], false ) ?>value="Default"><?php esc_html_e( 'Default', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'card1', $owmw_opt["template"], false ) ?>value="card1"><?php esc_html_e( 'Card 1', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'card2', $owmw_opt["template"], false ) ?>value="card2"><?php esc_html_e( 'Card 2', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'chart1', $owmw_opt["template"], false ) ?>value="chart1"><?php esc_html_e( 'Chart 1', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'chart2', $owmw_opt["template"], false ) ?>value="chart2"><?php esc_html_e( 'Chart 2', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'table1', $owmw_opt["template"], false ) ?>value="table1"><?php esc_html_e( 'Table 1', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'table2', $owmw_opt["template"], false ) ?>value="table2"><?php esc_html_e( 'Table 2', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'table3', $owmw_opt["template"], false ) ?>value="table3"><?php esc_html_e( 'Table 3', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'slider1', $owmw_opt["template"], false ) ?>value="slider1"><?php esc_html_e( 'Slider 1', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'slider2', $owmw_opt["template"], false ) ?>value="slider2"><?php esc_html_e( 'Slider 2', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'custom1', $owmw_opt["template"], false ) ?>value="custom1"><?php esc_html_e( 'Custom 1', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'custom2', $owmw_opt["template"], false ) ?>value="custom2"><?php esc_html_e( 'Custom 2', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'debug', $owmw_opt["template"], false ) ?>value="debug"><?php esc_html_e( 'Debug', 'owm-weather' ) ?></option>
				</select>
			</p>
			<p>
				<label for="font_meta"><?php esc_html_e( 'Font', 'owm-weather' ) ?></label>
				<select name="owmweather_font">
					<option <?php echo selected( 'Default', $owmw_opt["font"], false ) ?>value="Default"><?php esc_html_e( 'Default', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'Arvo', $owmw_opt["font"], false ) ?>value="Arvo"><?php esc_html_e( 'Arvo', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'Asap', $owmw_opt["font"], false ) ?>value="Asap"><?php esc_html_e( 'Asap', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'Bitter', $owmw_opt["font"], false ) ?>value="Bitter"><?php esc_html_e( 'Bitter', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'Droid Serif', $owmw_opt["font"], false ) ?>value="Droid Serif"><?php esc_html_e( 'Droid Serif', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'Exo 2', $owmw_opt["font"], false ) ?>value="Exo 2"><?php esc_html_e( 'Exo 2', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'Francois One', $owmw_opt["font"], false ) ?>value="Francois One"><?php esc_html_e( 'Francois One', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'Inconsolata', $owmw_opt["font"], false ) ?>value="Inconsolata"><?php esc_html_e( 'Inconsolata', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'Josefin Sans', $owmw_opt["font"], false ) ?>value="Josefin Sans"><?php esc_html_e( 'Josefin Sans', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'Lato', $owmw_opt["font"], false ) ?>value="Lato"><?php esc_html_e( 'Lato', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'Merriweather Sans', $owmw_opt["font"], false ) ?>value="Merriweather Sans"><?php esc_html_e( 'Merriweather Sans', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'Nunito', $owmw_opt["font"], false ) ?>value="Nunito"><?php esc_html_e( 'Nunito', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'Open Sans', $owmw_opt["font"], false ) ?>value="Open Sans"><?php esc_html_e( 'Open Sans', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'Oswald', $owmw_opt["font"], false ) ?>value="Oswald"><?php esc_html_e( 'Oswald', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'Pacifico', $owmw_opt["font"], false ) ?>value="Pacifico"><?php esc_html_e( 'Pacifico', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'Roboto', $owmw_opt["font"], false ) ?>value="Roboto"><?php esc_html_e( 'Roboto', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'Signika', $owmw_opt["font"], false ) ?>value="Signika"><?php esc_html_e( 'Signika', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'Source Sans Pro', $owmw_opt["font"], false ) ?>value="Source Sans Pro"><?php esc_html_e( 'Source Sans Pro', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'Tangerine', $owmw_opt["font"], false ) ?>value="Tangerine"><?php esc_html_e( 'Tangerine', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'Ubuntu', $owmw_opt["font"], false ) ?>value="Ubuntu"><?php esc_html_e( 'Ubuntu', 'owm-weather' ) ?></option>
				</select>
			</p>
			<p>
				<label for="iconpack_meta"><?php esc_html_e( 'Icon Pack', 'owm-weather' ) ?></label>
				<select name="owmweather_iconpack">
					<option <?php echo selected( 'Climacons', $owmw_opt["iconpack"], false ) ?>value="Climacons"><?php esc_html_e( 'Climacons', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'OpenWeatherMap', $owmw_opt["iconpack"], false ) ?>value="OpenWeatherMap"><?php esc_html_e( 'Open Weather Map', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'WeatherIcons', $owmw_opt["iconpack"], false ) ?>value="WeatherIcons"><?php esc_html_e( 'Weather Icons', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'Forecast', $owmw_opt["iconpack"], false ) ?>value="Forecast"><?php esc_html_e( 'Forecast', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'Dripicons', $owmw_opt["iconpack"], false ) ?>value="Dripicons"><?php esc_html_e( 'Dripicons', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'Pixeden', $owmw_opt["iconpack"], false ) ?>value="Pixeden"><?php esc_html_e( 'Pixeden', 'owm-weather' ) ?></option>
				</select>
			</p>
			<p class="misc subsection-title">
				<?php esc_html_e( 'Colors and Borders', 'owm-weather' ) ?>
			</p>
			<p>
				<label for="owmweather_background_color"><?php esc_html_e( 'Background color', 'owm-weather' ) ?></label>
				<input name="owmweather_background_color" type="text" value="<?php esc_attr_e($owmw_opt["background_color"]) ?>" class="owmweather_color_picker" />
			</p>
			<p>
				<label><?php esc_html_e( 'Background image', 'owm-weather' ) ?></label>
                  <div class="background_image_preview_wrapper">
                  	<img id="background_image_preview" src="<?php echo wp_get_attachment_url( ($owmw_opt["background_image"] ?? '' ) ) ?>" height="100px"<?php echo (!empty($owmw_opt["background_image"]) ? '' : ' style="display: none;"') ?>>
                  </div>
                  <input id="select_background_image_button" type="button" class="button" value="<?php esc_html_e( 'Select image', 'owm-weather' ) ?>" />
                  <input type="hidden" name="owmweather_background_image" id="background_image_attachment_id" value="<?php esc_attr_e($owmw_opt["background_image"] ?? '') ?>">
                  <input id="clear_background_image_button" type="button" class="button" value="Clear" />
              </p>
			<p>
				<label for="owmweather_text_color"><?php esc_html_e( 'Text color', 'owm-weather' ) ?></label>
				<input name="owmweather_text_color" type="text" value="<?php esc_attr_e($owmw_opt["text_color"]) ?>" class="owmweather_color_picker" />
			</p>
			<p>
				<label for="owmweather_border_color"><?php esc_html_e( 'Border color', 'owm-weather' ) ?></label>
				<input name="owmweather_border_color" type="text" value="<?php esc_attr_e($owmw_opt["border_color"]) ?>" class="owmweather_color_picker" />
			</p>
			<p>
				<label for="owmweather_border_width"><?php esc_html_e( 'Border width (px)', 'owm-weather' ) ?></label>
				<input name="owmweather_border_width" type="number" min="0" value="<?php esc_attr_e($owmw_opt["border_width"]) ?>" />
			</p>
			<p>
				<label for="owmweather_border_style"><?php esc_html_e( 'Border style', 'owm-weather' ) ?></label>
				<select name="owmweather_border_style">
					<option <?php echo selected( 'solid', $owmw_opt["border_style"], false ) ?>value="solid"><?php esc_html_e( 'Solid', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'dotted', $owmw_opt["border_style"], false ) ?>value="dotted"><?php esc_html_e( 'Dotted', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'dashed', $owmw_opt["border_style"], false ) ?>value="dashed"><?php esc_html_e( 'Dashed', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'double', $owmw_opt["border_style"], false ) ?>value="double"><?php esc_html_e( 'Double', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'groove', $owmw_opt["border_style"], false ) ?>value="groove"><?php esc_html_e( 'Groove', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'inset', $owmw_opt["border_style"], false ) ?>value="inset"><?php esc_html_e( 'Inset', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'outset', $owmw_opt["border_style"], false ) ?>value="outset"><?php esc_html_e( 'Outset', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'ridge', $owmw_opt["border_style"], false ) ?>value="ridge"><?php esc_html_e( 'Ridge', 'owm-weather' ) ?></option>
				</select>
			</p>
			<p>
				<label for="owmweather_border_radius"><?php esc_html_e( 'Border radius (px)', 'owm-weather' ) ?></label>
				<input name="owmweather_border_radius" type="number" min="0" value="<?php esc_attr_e($owmw_opt["border_radius"]) ?>" />
			</p>
			<p class="misc subsection-title">
				<?php esc_html_e( 'Misc', 'owm-weather' ) ?>
			</p>
			<p>
				<label for="owmweather_disable_spinner_meta">
					<input type="checkbox" name="owmweather_disable_spinner" id="owmweather_disable_spinner_meta" value="yes" <?php echo checked( $owmw_opt["disable_spinner"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Disable loading spinner?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_disable_anims_meta">
					<input type="checkbox" name="owmweather_disable_anims" id="owmweather_disable_anims_meta" value="yes" <?php echo checked( $owmw_opt["disable_anims"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Disable animations for main icon?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="size_meta"><?php esc_html_e( 'Weather size?', 'owm-weather' ) ?></label>
				<select name="owmweather_size">
					<option <?php echo selected( 'small', $owmw_opt["size"], false ) ?>value="small"><?php esc_html_e( 'Small', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'medium', $owmw_opt["size"], false ) ?>value="medium"><?php esc_html_e( 'Medium', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'large', $owmw_opt["size"], false ) ?>value="large"><?php esc_html_e( 'Large', 'owm-weather' ) ?></option>
				</select>
			</p>
			<p>
				<label for="owmweather_custom_css_meta"><?php esc_html_e( 'Custom CSS', 'owm-weather' ) ?></label>
				<textarea id="owmweather_custom_css_meta" name="owmweather_custom_css"><?php echo esc_textarea($owmw_opt["custom_css"]) ?></textarea>
			    <p>Preceed all CSS rules with .owmw-<?php esc_html_e($id) ?> if you are planning to use more than one weather shortcode on a page.</p>
			</p>
			<p class="subsection-title">
				<?php esc_html_e( 'Tables', 'owm-weather' ) ?>
			</p>
			<p>
				<label for="owmweather_table_background_color"><?php esc_html_e( 'Background color', 'owm-weather' ) ?></label>
				<input name="owmweather_table_background_color" type="text" value="<?php esc_attr_e($owmw_opt["table_background_color"]) ?>" class="owmweather_color_picker" />
			</p>
			<p>
				<label for="owmweather_table_text_color"><?php esc_html_e( 'Text color', 'owm-weather' ) ?></label>
				<input name="owmweather_table_text_color" type="text" value="<?php esc_attr_e($owmw_opt["table_text_color"]) ?>" class="owmweather_color_picker" />
			</p>
			<p>
				<label for="owmweather_table_border_color"><?php esc_html_e( 'Border color', 'owm-weather' ) ?></label>
				<input name="owmweather_table_border_color" type="text" value="<?php esc_attr_e($owmw_opt["table_border_color"]) ?>" class="owmweather_color_picker" />
			</p>
			<p>
				<label for="owmweather_table_border_width"><?php esc_html_e( 'Border width (px)', 'owm-weather' ) ?></label>
				<input name="owmweather_table_border_width" type="number" min="0" value="<?php esc_attr_e($owmw_opt["table_border_width"]) ?>" />
			</p>
			<p>
				<label for="owmweather_table_border_style"><?php esc_html_e( 'Border style', 'owm-weather' ) ?></label>
				<select name="owmweather_table_border_style">
					<option <?php echo selected( 'solid', $owmw_opt["table_border_style"], false ) ?>value="solid"><?php esc_html_e( 'Solid', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'dotted', $owmw_opt["table_border_style"], false ) ?>value="dotted"><?php esc_html_e( 'Dotted', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'dashed', $owmw_opt["table_border_style"], false ) ?>value="dashed"><?php esc_html_e( 'Dashed', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'double', $owmw_opt["table_border_style"], false ) ?>value="double"><?php esc_html_e( 'Double', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'groove', $owmw_opt["table_border_style"], false ) ?>value="groove"><?php esc_html_e( 'Groove', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'inset', $owmw_opt["table_border_style"], false ) ?>value="inset"><?php esc_html_e( 'Inset', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'outset', $owmw_opt["table_border_style"], false ) ?>value="outset"><?php esc_html_e( 'Outset', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'ridge', $owmw_opt["table_border_style"], false ) ?>value="ridge"><?php esc_html_e( 'Ridge', 'owm-weather' ) ?></option>
				</select>
			</p>
			<p>
				<label for="owmweather_table_border_radius"><?php esc_html_e( 'Border radius (px)', 'owm-weather' ) ?></label>
				<input name="owmweather_table_border_radius" type="number" min="0" value="<?php esc_attr_e($owmw_opt["table_border_radius"]) ?>" />
			</p>
			<p class="subsection-title">
				<?php esc_html_e( 'Charts', 'owm-weather' ) ?>
			</p>
			<p>
				<label for="owmweather_chart_height_meta"><?php esc_html_e( 'Height (in px)', 'owm-weather' ) ?></label>
				<input id="owmweather_charet_height_meta" type="text" name="owmweather_chart_height" value="<?php esc_attr_e($owmw_opt["chart_height"]) ?>" />
			</p>
			<p>
				<label for="owmweather_chart_background_color"><?php esc_html_e( 'Background color', 'owm-weather' ) ?></label>
				<input name="owmweather_chart_background_color" type="text" value="<?php esc_attr_e($owmw_opt["chart_background_color"]) ?>" class="owmweather_color_picker" />
			</p>
			<p>
				<label for="owmweather_chart_border_color"><?php esc_html_e( 'Border color', 'owm-weather' ) ?></label>
				<input name="owmweather_chart_border_color" type="text" value="<?php esc_attr_e($owmw_opt["chart_border_color"]) ?>" class="owmweather_color_picker" />
			</p>
			<p>
				<label for="owmweather_chart_border_width"><?php esc_html_e( 'Border width (px)', 'owm-weather' ) ?></label>
				<input name="owmweather_chart_border_width" type="number" min="0" value="<?php esc_attr_e($owmw_opt["chart_border_width"]) ?>" />
			</p>
			<p>
				<label for="owmweather_chart_border_style"><?php esc_html_e( 'Border style', 'owm-weather' ) ?></label>
				<select name="owmweather_chart_border_style">
					<option <?php echo selected( 'solid', $owmw_opt["chart_border_style"], false ) ?>value="solid"><?php esc_html_e( 'Solid', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'dotted', $owmw_opt["chart_border_style"], false ) ?>value="dotted"><?php esc_html_e( 'Dotted', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'dashed', $owmw_opt["chart_border_style"], false ) ?>value="dashed"><?php esc_html_e( 'Dashed', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'double', $owmw_opt["chart_border_style"], false ) ?>value="double"><?php esc_html_e( 'Double', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'groove', $owmw_opt["chart_border_style"], false ) ?>value="groove"><?php esc_html_e( 'Groove', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'inset', $owmw_opt["chart_border_style"], false ) ?>value="inset"><?php esc_html_e( 'Inset', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'outset', $owmw_opt["chart_border_style"], false ) ?>value="outset"><?php esc_html_e( 'Outset', 'owm-weather' ) ?></option>
					<option <?php echo selected( 'ridge', $owmw_opt["chart_border_style"], false ) ?>value="ridge"><?php esc_html_e( 'Ridge', 'owm-weather' ) ?></option>
				</select>
			</p>
			<p>
				<label for="owmweather_chart_border_radius"><?php esc_html_e( 'Border radius (px)', 'owm-weather' ) ?></label>
				<input name="owmweather_chart_border_radius" type="number" min="0" value="<?php esc_attr_e($owmw_opt["chart_border_radius"]) ?>" />
			</p>
			<p>
				<label for="owmweather_chart_temperature_color"><?php esc_html_e( 'Temperature color', 'owm-weather' ) ?></label>
				<input name="owmweather_chart_temperature_color" type="text" value="<?php esc_attr_e($owmw_opt["chart_temperature_color"]) ?>" class="owmweather_color_picker" />
			</p>
			<p>
				<label for="owmweather_chart_feels_like_color"><?php esc_html_e( 'Feels like color', 'owm-weather' ) ?></label>
				<input name="owmweather_chart_feels_like_color" type="text" value="<?php esc_attr_e($owmw_opt["chart_feels_like_color"]) ?>" class="owmweather_color_picker" />
			</p>
			<p>
				<label for="owmweather_chart_dew_point_color"><?php esc_html_e( 'Dew point color', 'owm-weather' ) ?></label>
				<input name="owmweather_chart_dew_point_color" type="text" value="<?php esc_attr_e($owmw_opt["chart_dew_point_color"]) ?>" class="owmweather_color_picker" />
			</p>
		</div>
		<div id="tabs-4">
		    <p style="border: 2px solid;padding: 5px;">
  			    Select the information and layout styling for the optional map on your weather shortcode.
		    </p>
			<p>
				<label for="owmweather_map_meta">
					<input type="checkbox" name="owmweather_map" id="owmweather_map_meta" value="yes" <?php echo checked( $owmw_opt["map"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Display map?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_map_height_meta"><?php esc_html_e( 'Map height (in px)', 'owm-weather' ) ?></label>
				<input id="owmweather_map_height_meta" type="number" min="300" name="owmweather_map_height" value="<?php esc_attr_e($owmw_opt["map_height"]) ?>" />
			</p>
			<p>
				<label for="owmweather_map_opacity_meta"><?php esc_html_e( 'Layers opacity', 'owm-weather' ) ?></label>
				<select name="owmweather_map_opacity">
					<option <?php echo selected( '0', $owmw_opt["map_opacity"], false ) ?>value="0">0%</option>
					<option <?php echo selected( '0.1', $owmw_opt["map_opacity"], false ) ?>value="0.1">10%</option>
					<option <?php echo selected( '0.2', $owmw_opt["map_opacity"], false ) ?>value="0.2">20%</option>
					<option <?php echo selected( '0.3', $owmw_opt["map_opacity"], false ) ?>value="0.3">30%</option>
					<option <?php echo selected( '0.4', $owmw_opt["map_opacity"], false ) ?>value="0.4">40%</option>
					<option <?php echo selected( '0.5', $owmw_opt["map_opacity"], false ) ?>value="0.5">50%</option>
					<option <?php echo selected( '0.6', $owmw_opt["map_opacity"], false ) ?>value="0.6">60%</option>
					<option <?php echo selected( '0.7', $owmw_opt["map_opacity"], false ) ?>value="0.7">70%</option>
					<option <?php echo selected( '0.8', $owmw_opt["map_opacity"], false ) ?>value="0.8">80%</option>
					<option <?php echo selected( '0.9', $owmw_opt["map_opacity"], false ) ?>value="0.9">90%</option>
					<option <?php echo selected( '1', $owmw_opt["map_opacity"], false ) ?>value="1">100%</option>
				</select>
			</p>
			<p>
				<label for="owmweather_map_zoom_meta"><?php esc_html_e( 'Zoom', 'owm-weather' ) ?></label>
				<select name="owmweather_map_zoom">
					<option <?php echo selected( '1', $owmw_opt["map_zoom"], false ) ?>value="1">1</option>
					<option <?php echo selected( '2', $owmw_opt["map_zoom"], false ) ?>value="2">2</option>
					<option <?php echo selected( '3', $owmw_opt["map_zoom"], false ) ?>value="3">3</option>
					<option <?php echo selected( '4', $owmw_opt["map_zoom"], false ) ?>value="4">4</option>
					<option <?php echo selected( '5', $owmw_opt["map_zoom"], false ) ?>value="5">5</option>
					<option <?php echo selected( '6', $owmw_opt["map_zoom"], false ) ?>value="6">6</option>
					<option <?php echo selected( '7', $owmw_opt["map_zoom"], false ) ?>value="7">7</option>
					<option <?php echo selected( '8', $owmw_opt["map_zoom"], false ) ?>value="8">8</option>
					<option <?php echo selected( '9', $owmw_opt["map_zoom"], false ) ?>value="9">9</option>
					<option <?php echo selected( '10', $owmw_opt["map_zoom"], false ) ?>value="10">10</option>
					<option <?php echo selected( '11', $owmw_opt["map_zoom"], false ) ?>value="11">11</option>
					<option <?php echo selected( '12', $owmw_opt["map_zoom"], false ) ?>value="12">12</option>
					<option <?php echo selected( '13', $owmw_opt["map_zoom"], false ) ?>value="13">13</option>
					<option <?php echo selected( '14', $owmw_opt["map_zoom"], false ) ?>value="14">14</option>
					<option <?php echo selected( '15', $owmw_opt["map_zoom"], false ) ?>value="15">15</option>
					<option <?php echo selected( '16', $owmw_opt["map_zoom"], false ) ?>value="16">16</option>
					<option <?php echo selected( '17', $owmw_opt["map_zoom"], false ) ?>value="17">17</option>
					<option <?php echo selected( '18', $owmw_opt["map_zoom"], false ) ?>value="18">18</option>
				</select>
			</p>
			<p>
				<label for="owmweather_map_disable_zoom_wheel_meta">
					<input type="checkbox" name="owmweather_map_disable_zoom_wheel" id="owmweather_map_disable_zoom_wheel_meta" value="yes" <?php echo checked( $owmw_opt["map_disable_zoom_wheel"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Disable zoom wheel on map?', 'owm-weather' ) ?>
				</label>
			</p>
			<p class="subsection-title">
				<?php esc_html_e( 'Layers', 'owm-weather' ) ?>
			</p>
			<p>
				<label for="owmweather_map_stations_meta">
					<input type="checkbox" name="owmweather_map_stations" id="owmweather_map_stations_meta" value="yes" <?php echo checked( $owmw_opt["map_stations"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Display stations?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_map_clouds_meta">
					<input type="checkbox" name="owmweather_map_clouds" id="owmweather_map_clouds_meta" value="yes" <?php echo checked( $owmw_opt["map_clouds"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Display clouds?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_map_precipitation_meta">
					<input type="checkbox" name="owmweather_map_precipitation" id="owmweather_map_precipitation_meta" value="yes" <?php echo checked( $owmw_opt["map_precipitation"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Display precipitation?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_map_snow_meta">
					<input type="checkbox" name="owmweather_map_snow" id="owmweather_map_snow_meta" value="yes" <?php echo checked( $owmw_opt["map_snow"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Display snow?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_map_wind_meta">
					<input type="checkbox" name="owmweather_map_wind" id="owmweather_map_wind_meta" value="yes" <?php echo checked( $owmw_opt["map_wind"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Display wind?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_map_temperature_meta">
					<input type="checkbox" name="owmweather_map_temperature" id="owmweather_map_temperature_meta" value="yes" <?php echo checked( $owmw_opt["map_temperature"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Display temperature?', 'owm-weather' ) ?>
				</label>
			</p>
			<p>
				<label for="owmweather_map_pressure_meta">
					<input type="checkbox" name="owmweather_map_pressure" id="owmweather_map_pressure_meta" value="yes" <?php echo checked( $owmw_opt["map_pressure"], 'yes', false ) ?>/>
						<?php esc_html_e( 'Display pressure?', 'owm-weather' ) ?>
				</label>
			</p>
		</div>
</div>
<?php
  echo ob_get_clean();
}

function owmw_save_metabox($post_id){
	if ( 'owm-weather' === get_post_type($post_id) ) {
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
		owmw_save_metabox_field('hours_forecast_no', $post_id);
		owmw_save_metabox_field('display_length_days_names', $post_id);
		owmw_save_metabox_field('forecast_no', $post_id);
		owmw_save_metabox_field('background_color', $post_id);
		owmw_save_metabox_field('background_image', $post_id);
		owmw_save_metabox_field('text_color', $post_id);
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
		owmw_save_metabox_field('alerts_button_color', $post_id);
		owmw_save_metabox_field('chart_height', $post_id);
		owmw_save_metabox_field('chart_background_color', $post_id);
		owmw_save_metabox_field('chart_border_color', $post_id);
		owmw_save_metabox_field('chart_border_width', $post_id);
		owmw_save_metabox_field('chart_border_style', $post_id);
		owmw_save_metabox_field('chart_border_radius', $post_id);
		owmw_save_metabox_field('chart_temperature_color', $post_id);
		owmw_save_metabox_field('chart_feels_like_color', $post_id);
		owmw_save_metabox_field('chart_dew_point_color', $post_id);
		owmw_save_metabox_field('table_background_color', $post_id);
		owmw_save_metabox_field('table_text_color', $post_id);
		owmw_save_metabox_field('table_border_color', $post_id);
		owmw_save_metabox_field('table_border_width', $post_id);
		owmw_save_metabox_field('table_border_style', $post_id);
		owmw_save_metabox_field('table_border_radius', $post_id);

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
		owmw_save_metabox_field_yn('current_temperature', $post_id);
		owmw_save_metabox_field_yn('current_feels_like', $post_id);
		owmw_save_metabox_field_yn('forecast_precipitations', $post_id);
		owmw_save_metabox_field_yn('disable_spinner', $post_id);
		owmw_save_metabox_field_yn('disable_anims', $post_id);
		owmw_save_metabox_field_yn('owm_link', $post_id);
		owmw_save_metabox_field_yn('last_update', $post_id);
		owmw_save_metabox_field_yn('map_disable_zoom_wheel', $post_id);
		owmw_save_metabox_field_yn('map_stations', $post_id);
		owmw_save_metabox_field_yn('map_clouds', $post_id);
		owmw_save_metabox_field_yn('map_precipitation', $post_id);
		owmw_save_metabox_field_yn('map_snow', $post_id);
		owmw_save_metabox_field_yn('map_wind', $post_id);
		owmw_save_metabox_field_yn('map_temperature', $post_id);
		owmw_save_metabox_field_yn('map_pressure', $post_id);
		owmw_save_metabox_field_yn('gtag', $post_id);
		owmw_save_metabox_field_yn('bypass_exclude', $post_id);
		owmw_save_metabox_field_yn('map', $post_id);
		owmw_save_metabox_field_yn('alerts', $post_id);
		owmw_save_metabox_field_yn('hours_time_icons', $post_id);
	}
}
add_action('save_post','owmw_save_metabox');

function owmw_save_metabox_field($field, $post_id) {
	if (!empty($_POST['owmweather_' . $field])){
        update_post_meta($post_id, '_owmweather_' . $field, owmw_sanitize_validate_field($field, $_POST['owmweather_' . $field]));
	} else {
	    delete_post_meta($post_id, '_owmweather_' . $field);
	}
}

function owmw_save_metabox_field_yn($field, $post_id) {
	if (isset($_POST['owmweather_' . $field]) && $_POST['owmweather_' . $field] == 'yes'){
        update_post_meta($post_id, '_owmweather_' . $field, 'yes');
	} else {
	    delete_post_meta($post_id, '_owmweather_' . $field);
	}
}

function owmw_clear_cache_current() {
	if ( 'owm-weather' === get_post_type() ) {
        global $wpdb;
		$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_owmweather%' ");
		$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_owmweather%' ");
    }
}
add_action('save_post','owmw_clear_cache_current');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Function CSS/Display/Misc
///////////////////////////////////////////////////////////////////////////////////////////////////

function owmw_css_color($attribute, $color) {
	if( !empty($color) ) {
		return esc_attr($attribute).': '.esc_attr($color).';';
	} else if ($attribute == 'fill') {
	    return esc_attr($attribute).': currentColor;';
	}

	return '';
}

function owmw_css_border($color, $width = '1', $style = 'solid', $radius_val = null) {
    $str = '';

	if( $color ) {
			$str .= 'border: '.esc_attr($width).'px '.esc_attr($style).' '. esc_attr($color).';';
	}

	if( $radius_val ) {
			$str .= 'border-radius: '.esc_attr($radius_val).'px;';
	}

	return $str;
}

function owmw_css_background_image($id) {
	if( $id ) {
			return 'background-image: url(\''. wp_get_attachment_url($id) . '\');';
	}
	return '';
}

function owmw_css_background_size($size) {
	if( $size ) {
			return 'background-size: '. esc_attr($size).';';
	}
	return '';
}

function owmw_css_background_position($horizontal, $vertical) {
	if( $horizontal && $vertical ) {
			return 'background-position: '.esc_attr($horizontal).'% '.esc_attr($vertical).'%;';
	}
	return "";
}

function owmw_css_font_family($font) {
	if( $font != 'Default' ) {
			return 'font-family: \'' . esc_attr($font) . '\';';
	}
	return '';
}

function owmw_css_height($height) {
	if( $height ) {
			return 'height: '. esc_attr($height) .'px;';
	}
	return '';
}


function owmw_city_name($custom_city_name, $owm_city_name) {
    $str = '';
	if (!empty($custom_city_name)) {
		$str = $custom_city_name;
	} else if(!empty($owm_city_name)) {
		$str = $owm_city_name;
	}

	return esc_html($str);
}

function owmw_display_today_sunrise_sunset($owmweather_sunrise_sunset, $sun_rise, $sun_set, $color, $elem) {
	if( $owmweather_sunrise_sunset == 'yes' ) {
		return '<div class="owmw-sun-hours col">
					<' . esc_attr($elem) . ' class="owmw-sunrise" title="'.esc_attr__('Sunrise','owm-weather').'">'. owmw_sunrise($color) . '<span class="font-weight-bold">' . esc_html($sun_rise) .'</span></' . esc_attr($elem) . '><' . esc_attr($elem) . ' class="owmw-sunset" title="'.esc_attr__('Sunset','owm-weather').'">'. owmw_sunset($color) . '<span class="font-weight-bold">' . esc_html($sun_set) .'</span></' . esc_attr($elem) . '>
				</div>';
	}

	return '';
}

function owmw_display_today_moonrise_moonset($owmweather_moonrise_moonset, $moon_rise, $moon_set, $color, $elem) {
	if( $owmweather_moonrise_moonset == 'yes' ) {
		return '<div class="owmw-moon-hours col">
					<' . esc_attr($elem) . ' class="owmw-moonrise" title="'.esc_attr__('Moonrise','owm-weather').'">'. owmw_moonrise($color) . '<span class="font-weight-bold">' . esc_html($moon_rise) .'</span></' . esc_attr($elem) . '><' . esc_attr($elem) . ' class="owmw-moonset" title="'.esc_attr__('Moonset','owm-weather').'">'. owmw_moonset($color) . '<span class="font-weight-bold">' . esc_html($moon_set) .'</span></' . esc_attr($elem) . '>
				</div>';
	}

	return '';
}

function owmw_webfont($bypass, $id) {
	$owmw_webfont_value = owmw_get_bypass($bypass, "font", $id);

    if ($owmw_webfont_value != 'Default') {
        wp_register_style($owmw_webfont_value, '//fonts.googleapis.com/css?family=' . str_replace(' ', '+', esc_attr($owmw_webfont_value)) . ':400&display=swap' );
       	wp_enqueue_style($owmw_webfont_value);
    }

	return $owmw_webfont_value;
}

function owmw_icons_pack($bypass, $id) {
	$iconpack = owmw_get_bypass($bypass, "iconpack", $id);

    if ($iconpack == 'WeatherIcons') {
      	wp_register_style("weathericons-css", plugins_url('css/weather-icons.min.css', __FILE__));
      	wp_enqueue_style("weathericons-css");
    } else if ($iconpack == 'Forecast') {
      	wp_register_style("iconvault-css", plugins_url('css/iconvault.min.css', __FILE__));
      	wp_enqueue_style("iconvault-css");
    } else if ($iconpack == 'Dripicons') {
      	wp_register_style("dripicons-css", plugins_url('css/dripicons.min.css', __FILE__));
      	wp_enqueue_style("dripicons-css");
    } else if ($iconpack == 'Pixeden') {
      	wp_register_style("pe-icon-set-weather-css", plugins_url('css/pe-icon-set-weather.min.css', __FILE__));
      	wp_enqueue_style("pe-icon-set-weather-css");
    } else {
      	wp_register_style("climacons-css", plugins_url('css/climacons-font.min.css', __FILE__));
      	wp_enqueue_style("climacons-css");
    }

    return $iconpack;
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Add shortcode Weather
///////////////////////////////////////////////////////////////////////////////////////////////////

function owmw_get_my_weather_id($atts) {
    global $owmw_params;
    
	require_once dirname( __FILE__ ) . '/owmweather-options.php';

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
        "forecast_no"                   => false,
        "hours_forecast_no"             => false,
        "unit"                          => false,
        "time_format"                   => false,
        "today_date_format"             => false,
        "wind_unit"                     => false,
        "wind_icon_direction"           => false,
        "display_length_days_names"     => false,
        "background_color"              => false,
        "text_color"                    => false,
        "border_color"                  => false,
        "border_width"                  => false,
        "border_style"                  => false,
        "border_radius"                 => false,
        "custom_css"                    => false,
        "map_height"                    => false,
        "map_opacity"                   => false,
        "map_zoom"                      => false,
        "alerts_button_color"           => false,
        "chart_height"                  => false,
        "chart_background_color"        => false,
        "chart_border_color"            => false,
        "chart_border_width"            => false,
        "chart_border_style"            => false,
        "chart_border_radius"           => false,
        "chart_temperature_color"       => false,
        "chart_feels_like_color"        => false,
        "chart_dew_point_color"         => false,
        "table_background_color"        => false,
        "table_text_color"              => false,
        "table_border_color"            => false,
        "table_border_width"            => false,
        "table_border_style"            => false,
        "table_border_radius"           => false,
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
        "forecast_precipitations"       => false,
        "owm_link"                      => false,
        "last_update"                   => false,
        "map"                           => false,
        "map_disable_zoom_wheel"        => false,
        "map_stations"                  => false,
        "map_clouds"                    => false,
        "map_precipitation"             => false,
        "map_snow"                      => false,
        "map_wind"                      => false,
        "map_temperature"               => false,
        "map_pressure"                  => false,
        "gtag"                          => false,
        "bypass_exclude"                => false,
        "alerts"                        => false,
        "hours_time_icons"              => false,
    ), $atts);

    owmw_sanitize_atts($owmw_params);

	if (empty($owmw_params["id"])) {
	    echo "<p>OWM Weather Error: owm-weather shortcode without 'id' parameter</p>";
	    return;
	}
    if (get_post_type($owmw_params["id"]) != 'owm-weather') {
	    echo "<p>OWM Weather Error: id '".esc_html($owmw_params["id"])."' is not type 'weather'</p>";
	    return;
    }

    if (get_post_status($owmw_params["id"]) != 'publish') {
	    echo "<p>OWM Weather Error: id '".esc_html($owmw_params["id"])."' is not published</p>";
	    return;
    }

    $owmw_opt = [];
	$owmw_opt["id"] = $owmw_params["id"];
    $owmw_opt["bypass_exclude"]      = get_post_meta($owmw_opt["id"],'_owmweather_bypass_exclude',true);
    $bypass = $owmw_opt["bypass_exclude"] != 'yes';
	$owmw_opt["disable_anims"]	    = owmw_get_bypass_yn($bypass, "disable_anims", $owmw_opt["id"]);
	$owmw_opt["map"]           		= owmw_get_bypass_yn($bypass, "map", $owmw_opt["id"]);
	$owmw_opt["template"]  			= owmw_get_bypass($bypass, "template", $owmw_opt["id"]);
	$owmw_opt["disable_spinner"] 	= owmw_get_bypass_yn($bypass, "disable_spinner", $owmw_opt["id"]);

	owmw_webfont($bypass, $owmw_opt["id"]);
	owmw_icons_pack($bypass, $owmw_opt["id"]);

	if ($owmw_opt["template"] == 'slider1' || $owmw_opt["template"] == 'slider2' ) {
		wp_enqueue_script('jquery');
		wp_enqueue_script('owmw-flexslider-js');
		wp_enqueue_style('owmw-flexslider-css');
	} else if ($owmw_opt["template"] == 'chart1' || $owmw_opt["template"] == 'chart2' || $owmw_opt["template"] == 'debug') {
		wp_enqueue_script('chart-js');
	}

    if (owmw_get_admin_bypass('owmw_advanced_disable_modal_js') != 'yes') {
		wp_enqueue_script('jquery');
		wp_enqueue_style('bootstrap-css');
		wp_enqueue_script('bootstrap-js');
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
    foreach($owmw_params as $key => $value) {
        if ($value !== false) {
            $data_attributes_esc[] = 'data-' . esc_attr($key) . '="' . esc_attr($value) . '"';
        }
    }

    $div_id_esc = owmw_unique_id_esc("owm-weather-id-".$owmw_opt["id"]);
    $data_attributes_esc[] = "data-weather_id=".$div_id_esc;

	$ret = '<div id="'.$div_id_esc.'" class="owm-weather-id" ' . join(' ', $data_attributes_esc) .'>';
	if ($owmw_opt["disable_spinner"] != 'yes') {
	    $ret .= '<div class="owmw-loading-spinner"><img src="'. plugins_url( 'img/owmloading.gif', __FILE__) . '" alt="loader"/></div>';
	}
	$ret .= '</div>';

	return $ret;
}
add_shortcode("owm-weather", 'owmw_get_my_weather_id');

function owmw_get_my_weather($attr) {
    global $owmw_params;

    $owmw_params = [];
    if (isset($_POST['owmw_params'])) {
        foreach ($_POST['owmw_params'] as $k => $v) {
            $owmw_params[$k] = sanitize_text_field($v);
        }
    }

	check_ajax_referer( 'owmw_get_weather_nonce', $_POST['_ajax_nonce'], true );

	if ( isset( $owmw_params['id'] ) ) {
		$id = intval($owmw_params['id']);

		require_once dirname( __FILE__ ) . '/owmweather-options.php';
		require_once dirname( __FILE__ ) . '/owmweather-anim.php';
		require_once dirname( __FILE__ ) . '/owmweather-icons.php';

        $owmw_opt                                    = [];
	  	$owmw_opt["id"] 								= $id;
        $owmw_opt["bypass_exclude"]                  = get_post_meta($owmw_opt["id"],'_owmweather_bypass_exclude',true);
        $bypass                                     = ($owmw_opt["bypass_exclude"] != 'yes');
	  	$owmw_opt["id_owm"]          				= owmw_get_bypass($bypass, "id_owm");
	  	$owmw_opt["longitude"]          				= owmw_get_bypass($bypass, "longitude");
	  	$owmw_opt["latitude"]          				= owmw_get_bypass($bypass, "latitude");
	  	$owmw_opt["zip"]          				    = str_replace(' ', '+', owmw_get_bypass($bypass, "zip"));
		$owmw_opt["zip_country_code"]          		= str_replace(' ', '+', owmw_get_bypass($bypass, "zip_country_code"));
	  	$owmw_opt["city"]                			= str_replace(' ', '+', strtolower(owmw_get_bypass($bypass, "city")));
		$owmw_opt["country_code"]            		= str_replace(' ', '+', owmw_get_bypass($bypass, "country_code"));
		$owmw_opt["custom_city_name"]       			= owmw_get_bypass($bypass, "custom_city_name");
		$owmw_opt["temperature_unit"]       			= owmw_get_bypass($bypass, "unit");
    	$owmw_opt["map"]           		            = owmw_get_bypass_yn($bypass, "map");
		$owmw_opt["map_height"]            			= owmw_get_bypass($bypass, "map_height");
		$owmw_opt["map_opacity"]          			= owmw_get_bypass($bypass, "map_opacity");
		$owmw_opt["map_zoom"]              			= owmw_get_bypass($bypass, "map_zoom");
		$owmw_opt["map_disable_zoom_wheel"]     		= owmw_get_bypass_yn($bypass, "map_disable_zoom_wheel");
		$owmw_opt["map_stations"]            		= owmw_get_bypass_yn($bypass, "map_stations");
		$owmw_opt["map_clouds"]            			= owmw_get_bypass_yn($bypass, "map_clouds");
		$owmw_opt["map_precipitation"]         		= owmw_get_bypass_yn($bypass, "map_precipitation");
		$owmw_opt["map_snow"]              			= owmw_get_bypass_yn($bypass, "map_snow");
		$owmw_opt["map_wind"]              			= owmw_get_bypass_yn($bypass, "map_wind");
		$owmw_opt["map_temperature"]         		= owmw_get_bypass_yn($bypass, "map_temperature");
		$owmw_opt["map_pressure"]            		= owmw_get_bypass_yn($bypass, "map_pressure");
		$owmw_opt["border_color"]             		= owmw_get_bypass($bypass, "border_color");
		$owmw_opt["border_width"]             		= owmw_getBypassDefault($bypass, 'border_width', $owmw_opt["border_color"] == '' ? '0' : '1');
		$owmw_opt["border_style"]             		= owmw_get_bypass($bypass, "border_style");
		$owmw_opt["border_radius"]             		= owmw_get_bypass($bypass, "border_radius");
		$owmw_opt["background_color"]   	          	= owmw_get_bypass($bypass, "background_color");
		$owmw_opt["background_image"]   	          	= owmw_get_bypass($bypass, "background_image");
		$owmw_opt["text_color"]         		        = owmw_get_bypass($bypass, "text_color");
		$owmw_opt["time_format"]          			= owmw_get_bypass($bypass, "time_format");
		$owmw_opt["sunrise_sunset"]          		= owmw_get_bypass_yn($bypass, "sunrise_sunset");
		$owmw_opt["moonrise_moonset"]         		= owmw_get_bypass_yn($bypass, "moonrise_moonset");
		$owmw_opt["display_temperature_unit"]   		= owmw_get_bypass_yn($bypass, "display_temperature_unit");
		$owmw_opt["display_length_days_names"]     	= owmw_get_bypass($bypass, "display_length_days_names");
		$owmw_opt["cache_time"]      	            = owmw_get_admin_cache_time();
		$owmw_opt["disable_cache"]       	    	= owmw_get_admin_disable_cache();
		$owmw_opt["api_key"]           			    = owmw_get_admin_api_key();
		$owmw_opt["owm_link"]          	    	    = owmw_get_bypass_yn($bypass, "owm_link");
		$owmw_opt["last_update"]       		        = owmw_get_bypass_yn($bypass, "last_update");
		$owmw_opt["hours_forecast_no"]  				= owmw_get_bypass($bypass, "hours_forecast_no");
		$owmw_opt["hours_time_icons"]  				= owmw_get_bypass($bypass, "hours_time_icons");
		$owmw_opt["days_forecast_no"]     			= owmw_get_bypass($bypass, "forecast_no");
		$owmw_opt["forecast_precipitations"]			= owmw_get_bypass_yn($bypass, "forecast_precipitations");
		$owmw_opt["custom_timezone"]			    	= owmw_get_bypass($bypass, "custom_timezone");
		$owmw_opt["today_date_format"]      			= owmw_get_bypass($bypass, "today_date_format");
		$owmw_opt["alerts"]                          = owmw_get_bypass_yn($bypass, "alerts");
		$owmw_opt["alerts_button_color"]             = owmw_get_bypass($bypass, "alerts_button_color");
		$owmw_opt["owm_language"]                    = owmw_get_bypass($bypass, "owm_language");
		if ($owmw_opt["owm_language"] == 'Default') {
		    $owmw_opt["owm_language"] = 'en';
		}
    	$owmw_opt["font"]    			            = owmw_get_bypass($bypass, "font");
    	$owmw_opt["iconpack"]  			            = owmw_get_bypass($bypass, "iconpack");
	    $owmw_opt["template"]  			            = owmw_get_bypass($bypass, "template");
        $owmw_opt["gtag"]                            = owmw_get_bypass($bypass, "gtag");
		$owmw_opt["custom_css"]                      = owmw_get_bypass($bypass, 'custom_css');
		$owmw_opt["current_weather_symbol"]  		= owmw_get_bypass_yn($bypass, "current_weather_symbol");
		$owmw_opt["current_city_name"]        		= owmw_get_bypass_yn($bypass, "current_city_name");
		$owmw_opt["current_weather_description"]     = owmw_get_bypass_yn($bypass, "current_weather_description");
		$owmw_opt["wind"]          					= owmw_get_bypass_yn($bypass, "wind");
        $owmw_opt["wind_unit"]                       = owmw_get_bypass($bypass, "wind_unit");
        $owmw_opt["wind_icon_direction"]            = owmw_get_bypass($bypass, "wind_icon_direction");
        $owmw_opt["humidity"]        				= owmw_get_bypass_yn($bypass, "humidity");
        $owmw_opt["dew_point"]        				= owmw_get_bypass_yn($bypass, "dew_point");
		$owmw_opt["pressure"]        				= owmw_get_bypass_yn($bypass, "pressure");
		$owmw_opt["cloudiness"]      				= owmw_get_bypass_yn($bypass, "cloudiness");
		$owmw_opt["precipitation"]     				= owmw_get_bypass_yn($bypass, "precipitation");
		$owmw_opt["visibility"]     				    = owmw_get_bypass_yn($bypass, "visibility");
		$owmw_opt["uv_index"]     				    = owmw_get_bypass_yn($bypass, "uv_index");
		$owmw_opt["current_temperature"] 			= owmw_get_bypass_yn($bypass, "current_temperature");
		$owmw_opt["current_feels_like"] 			    = owmw_get_bypass_yn($bypass, "current_feels_like");
		$owmw_opt["size"]          					= owmw_get_bypass($bypass, "size");
        $owmw_opt["disable_spinner"]                 = owmw_get_bypass_yn($bypass, "disable_spinner");
        $owmw_opt["disable_anims"]                   = owmw_get_bypass_yn($bypass, "disable_anims");

		//$owmw_opt["image_bg_cover"]					= get_post_meta($owmw_opt["id"],'_owmweather_image_bg_cover',true); // bugbug
		//$owmw_opt["image_bg_position_horizontal_e"]	= null; // bugbug
		//$owmw_opt["image_bg_position_vertical_e"]	= null; // bugbug

    	$owmw_opt["chart_height"]	    		    = owmw_getBypassDefault($bypass, 'chart_height', '400');
    	$owmw_opt["chart_background_color"]		    = owmw_get_bypass($bypass, 'chart_background_color');
    	$owmw_opt["chart_border_color"]	            = owmw_get_bypass($bypass, 'chart_border_color');
    	$owmw_opt["chart_border_width"]	            = owmw_getBypassDefault($bypass, 'chart_border_width', $owmw_opt["chart_border_color"] == '' ? '0' : '1');
    	$owmw_opt["chart_border_style"]	            = owmw_getBypassDefault($bypass, 'chart_border_style', "solid");
    	$owmw_opt["chart_border_radius"]	            = owmw_getBypassDefault($bypass, 'chart_border_radius', "0");
    	$owmw_opt["chart_temperature_color"]	        = owmw_get_bypass($bypass, 'chart_temperature_color');
    	$owmw_opt["chart_feels_like_color"]	        = owmw_get_bypass($bypass, 'chart_feels_like_color');
    	$owmw_opt["chart_dew_point_color"]	        = owmw_get_bypass($bypass, 'chart_dew_point_color');
    	$owmw_opt["table_background_color"]	        = owmw_get_bypass($bypass, 'table_background_color');
    	$owmw_opt["table_text_color"]	            = owmw_get_bypass($bypass, 'table_text_color');
    	$owmw_opt["table_border_color"]	            = owmw_get_bypass($bypass, 'table_border_color');
    	$owmw_opt["table_border_width"]	            = owmw_getBypassDefault($bypass, 'table_border_width', $owmw_opt["table_border_color"] == '' ? '0' : '1');
    	$owmw_opt["table_border_style"]	            = owmw_getBypassDefault($bypass, 'table_border_style', "solid");
    	$owmw_opt["table_border_radius"]	            = owmw_getBypassDefault($bypass, 'table_border_radius', "0");
/* bugbug
		if ($owmw_opt["image_bg_cover"] == 'yes') {
			$owmw_opt["image_bg_cover_e"] = 'cover';
		}
		$owmweather_image_bg_position_horizontal				= 	get_post_meta($owmw_opt["id"],'_owmweather_image_bg_position_horizontal',true);
		if ($owmweather_image_bg_position_horizontal != 'Default') {
			$owmw_opt["image_bg_position_horizontal_e"]		= 	$owmweather_image_bg_position_horizontal;
		}
		$owmweather_image_bg_position_vertical				= 	get_post_meta($owmw_opt["id"],'_owmweather_image_bg_position_vertical',true);
		if ($owmweather_image_bg_position_vertical != 'Default') {
			$owmw_opt["image_bg_position_vertical_e"]		= 	$owmweather_image_bg_position_vertical;
		}
*/

        //JSON : Current weather
       	if ($owmw_opt["id_owm"] !='') {
       	    $query = "id=".$owmw_opt["id_owm"];
       	} else if ($owmw_opt["longitude"] != '' && $owmw_opt["latitude"] != '') {
       	    $query = "lat=".$owmw_opt["latitude"]."&lon=".$owmw_opt["longitude"];
       	} else if ($owmw_opt["zip"] != '') {
       	    $query = "zip=".$owmw_opt["zip"];
       	    if (!empty($owmw_opt["zip_country_code"])) {
       	        $query .= "," . $owmw_opt["zip_country_code"];
       	    }
       	} else if ($owmw_opt["city"] != '') {
       	    $query = "q=".$owmw_opt["city"];
       	    if (!empty($owmw_opt["country_code"])) {
           	    $query .= ",".$owmw_opt["country_code"];
       	    }
       	} else if (($ipData = owmw_IPtoLocation())) {
            $owmw_opt["latitude"] = $ipData->data->geo->latitude;
            $owmw_opt["longitude"] =  $ipData->data->geo->longitude;
       	    $query = "lat=".$owmw_opt["latitude"]."&lon=".$owmw_opt["longitude"];
       	} else {
       	    return;
       	}

        $url = 'https://api.openweathermap.org/data/2.5/weather?'.$query.'&mode=json&lang='.$owmw_opt["owm_language"].'&units='.$owmw_opt["temperature_unit"].'&APPID='.$owmw_opt["api_key"];
        if ($owmw_opt["disable_cache"] == 'yes') {
			$response = wp_remote_get(esc_url_raw($url), array( 'timeout' => 10));
            if (!is_wp_error($response)) {
    			$owmweather_current = json_decode(wp_remote_retrieve_body($response));
    		} else {
           	  	$response = array();
   	          	$response['weather'] = $owmw_params["weather_id"];
           	  	$response['html'] = "<p>OWM Weather id '" . esc_attr($owmw_opt["id"]) . "': OWM Error " . esc_htlm__('Unable to retrieve weather data','owm-weather') . "</p>";
   	        	wp_send_json_error($response, 400);
                return;
    		}
        } else {
            $transient_key = 'owmweather_current_' . $query . $owmw_opt["owm_language"] . $owmw_opt["temperature_unit"];
           	if (false === ( $owmweather_current = get_transient( $transient_key ) ) ) {
    			$response = wp_remote_get(esc_url_raw($url), array( 'timeout' => 10));
                if (!is_wp_error($response)) {
        			$owmweather_current = json_decode(wp_remote_retrieve_body($response));
        			set_transient( $transient_key, $owmweather_current, $owmw_opt["cache_time"] * MINUTE_IN_SECONDS );
        		} else {
            	  	$response = array();
    	          	$response['weather'] = $owmw_params["weather_id"];
            	  	$response['html'] = "<p>OWM Weather id '" . esc_attr($owmw_opt["id"]) . "': OWM Error " . esc_htlm__('Unable to retrieve weather data','owm-weather') . "</p>";
    	        	wp_send_json_error($response, 400);
                    return;
        		}
            }
        }

        if (!empty($owmweather_current->cod) && $owmweather_current->cod != "200") {
    	  	$response = array();
    	  	$response['weather'] = $owmw_params["weather_id"];
    	  	$response['html'] = "<p>OWM Weather id '" . esc_attr($owmw_opt["id"]) . "': OWM Error " . esc_html($owmweather_current->cod . (!empty($owmweather_current->message) ? " (" . $owmweather_current->message . ")" : "")) . "</p>";
    		wp_send_json_error($response, $owmweather_current->cod);
    		return;
        }

        owmw_sanitize_api_response($owmweather_current);


        $owmw_data = [];
        $owmw_data["name"] = $owmweather_current->name ?? null;
        $owmw_data["id"] = $owmweather_current->id ?? null;
        $owmw_data["timezone"] = $owmweather_current->timezone ?? null;

		$owmweather_time_php = $owmw_opt["time_format"] == '12' ? 'h:i A' : 'H:i';
		$owmweather_hours_php = $owmw_opt["time_format"] == '12' ? 'h A' : 'H';
        if ($owmw_opt["custom_timezone"] == 'Default') {
            $utc_time_wp = get_option('gmt_offset') * 60;
        } else if ($owmw_opt["custom_timezone"] == 'local') {
            $utc_time_wp = intval($owmw_data["timezone"]) * 60;
        } else {
            $utc_time_wp = intval($owmw_opt["custom_timezone"]) * 60;
        }

        $owmw_data["timestamp"] = $owmweather_current->dt ? $owmweather_current->dt + (60 * $utc_time_wp) : null;
        $owmw_data["last_update"] = esc_html__('Last updated: ','owm-weather').date($owmweather_time_php, $owmw_data["timestamp"]);
        $owmw_data["latitude"] = $owmweather_current->coord->lat ?? null;
        $owmw_data["longitude"] = $owmweather_current->coord->lon ?? null;
        $owmw_data["condition_id"] = $owmweather_current->weather[0]->id ?? null;
        $owmw_data["category"] = $owmweather_current->weather[0]->main ?? null;
        $owmw_data["description"] = $owmweather_current->weather[0]->description ?? null;
        $owmw_data["wind_speed_unit"] = owmw_getWindSpeedUnit($owmw_opt["temperature_unit"], $owmw_opt["wind_unit"]);
        $owmw_data["wind_speed"] = owmw_getConvertedWindSpeed($owmweather_current->wind->speed, $owmw_opt["temperature_unit"], $owmw_opt["wind_unit"]) . ' ' . $owmw_data["wind_speed_unit"];
        $owmw_data["wind_degrees"] = $owmweather_current->wind->deg ?? null;
        $owmw_data["wind_direction"] = owmw_getWindDirection($owmweather_current->wind->deg);
        $owmw_data["wind_gust"] = isset($owmweather_current->wind->gust) ? owmw_getConvertedWindSpeed($owmweather_current->wind->gust, $owmw_opt["temperature_unit"], $owmw_opt["wind_unit"])  . ' ' . $owmw_data["wind_speed_unit"] : null;
        $owmw_data["temperature"] = $owmweather_current->main->temp ? ceil($owmweather_current->main->temp) : null;
        $owmw_data["feels_like"] = $owmweather_current->main->feels_like ? ceil($owmweather_current->main->feels_like) : null;
		if ($owmw_opt["temperature_unit"] == 'metric') {
			$owmw_data["temperature_unit_character"] = "C";
			$owmw_data["temperature_unit_text"] = 'Celsius';
			$owmw_data["temperature_celsius"] = $owmw_data["temperature"];
			$owmw_data["temperature_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["temperature"]);
			$owmw_data["feels_like_celsius"] = $owmw_data["feels_like"];
			$owmw_data["feels_like_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["feels_like"]);
		} else {
			$owmw_data["temperature_unit_character"] = "F";
			$owmw_data["temperature_unit_text"] = 'Fahrenheit';
			$owmw_data["temperature_fahrenheit"] = $owmw_data["temperature"];
			$owmw_data["temperature_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["temperature"]);
			$owmw_data["feels_like_fahrenheit"] = $owmw_data["feels_like"];
			$owmw_data["feels_like_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["feels_like"]);
		}
        $owmw_data["humidity"] = $owmweather_current->main->humidity ? $owmweather_current->main->humidity . '%' : null;
        $owmw_data["pressure_unit"] = $owmw_opt["temperature_unit"] == 'imperial' ? esc_html__('in','owm-weather') : esc_html__('hPa','owm-weather');
        $owmw_data["pressure"] = owmw_converthp2iom($owmw_opt["temperature_unit"], $owmweather_current->main->pressure) . ' ' . $owmw_data["pressure_unit"];
        $owmw_data["cloudiness"] = $owmweather_current->clouds->all ? $owmweather_current->clouds->all . '%' : null;
        $owmw_data["precipitation_unit"] = $owmw_opt["temperature_unit"] == 'imperial' ? esc_html__('in','owm-weather') : esc_html__('mm','owm-weather');
        $owmw_data["rain_1h"] = owmw_getConvertedPrecipitation($owmw_opt["temperature_unit"], $owmweather_current->rain->{"1h"} ?? 0) . ' ' . $owmw_data["precipitation_unit"];
        $owmw_data["rain_3h"] = owmw_getConvertedPrecipitation($owmw_opt["temperature_unit"], $owmweather_current->rain->{"3h"} ?? 0) . ' ' . $owmw_data["precipitation_unit"];
        $owmw_data["snow_1h"] = owmw_getConvertedPrecipitation($owmw_opt["temperature_unit"], $owmweather_current->snow->{"1h"} ?? 0) . ' ' . $owmw_data["precipitation_unit"];
        $owmw_data["snow_3h"] = owmw_getConvertedPrecipitation($owmw_opt["temperature_unit"], $owmweather_current->snow->{"3h"} ?? 0) . ' ' . $owmw_data["precipitation_unit"];
        $owmw_data["precipitation_1h"] = $owmw_data["rain_1h"] ?? 0 + $owmw_data["snow_1h"] ?? 0 . ' ' . $owmw_data["precipitation_unit"];
        $owmw_data["precipitation_3h"] = $owmw_data["rain_3h"] ?? 0 + $owmw_data["snow_3h"] ?? 0 . ' ' . $owmw_data["precipitation_unit"];;
        $owmw_data["visibility"] = owmw_getConvertedDistance($owmw_opt["temperature_unit"], $owmweather_current->visibility);
        $owmw_data["owm_link"] = 'https://openweathermap.org/city/'.($owmweather_current->id ?? "");
        $owmw_data["timestamp_sunrise"] = $owmweather_current->sys->sunrise ? $owmweather_current->sys->sunrise + (60 * $utc_time_wp) : null;
        $owmw_data["timestamp_sunset"] = $owmweather_current->sys->sunset ? $owmweather_current->sys->sunset + (60 * $utc_time_wp) : null;
        $owmw_data["sunrise"] = (string)date($owmweather_time_php, $owmw_data["timestamp_sunrise"]);
        $owmw_data["sunset"] = (string)date($owmweather_time_php, $owmw_data["timestamp_sunset"]);
        $owmw_data["moonrise"] = '';
        $owmw_data["moonset"] = '';

		if ($owmw_opt["today_date_format"] == 'date') {
			$today_day =  date_i18n( get_option( 'date_format' ) );
		} else if ($owmw_opt["today_date_format"] == 'day') {
			switch (strftime("%w", $owmw_data["timestamp"])) {
		        case "0":
		          	$today_day      = esc_html__('Sunday','owm-weather');
		          	break;
		        case "1":
		          	$today_day      = esc_html__('Monday','owm-weather');
		          	break;
		        case "2":
		        	$today_day      = esc_html__('Tuesday','owm-weather');
		          	break;
		        case "3":
		        	$today_day      = esc_html__('Wednesday','owm-weather');
		          	break;
		        case "4":
		        	$today_day      = esc_html__('Thursday','owm-weather');
		          	break;
		        case "5":
		        	$today_day      = esc_html__('Friday','owm-weather');
		          	break;
		        case "6":
		        	$today_day      = esc_html__('Saturday','owm-weather');
		          	break;
		  		}
		} else {
			$today_day ='';
		}

        //JSON : Onecall forecast weather (relies on lat and lon from current weather call)
        $url = "https://api.openweathermap.org/data/2.5/onecall?lon=".$owmw_data["longitude"]."&lat=".$owmw_data["latitude"]."&mode=json&exclude=minutely&lang=".$owmw_opt["owm_language"]."&units=".$owmw_opt["temperature_unit"]."&APPID=".$owmw_opt["api_key"];
        if($owmw_opt["hours_forecast_no"] > 0 || $owmw_opt["days_forecast_no"] > 0 || $owmw_opt["alerts"] == 'yes' || $owmw_opt["moonrise_moonset"] == "yes" || $owmw_opt["dew_point"] == "yes" || $owmw_opt["uv_index"] == "yes" || $owmw_opt["gtag"] == "yes") {
    		if ($owmw_opt["disable_cache"] == 'yes') {
    			$response = wp_remote_get(esc_url_raw($url), array( 'timeout' => 10));
                if (!is_wp_error($response)) {
        			$owmweather = json_decode(wp_remote_retrieve_body($response));
        		} else {
               	  	$response = array();
       	          	$response['weather'] = $owmw_params["weather_id"];
               	  	$response['html'] = "<p>OWM Weather id '" . esc_attr($owmw_opt["id"]) . "': OWM Error " . esc_htlm__('Unable to retrieve weather data','owm-weather') . "</p>";
       	        	wp_send_json_error($response, 400);
                    return;
        		}
        	} else {
        	    $transient_key = 'owmweather_' . $owmw_data["longitude"] . $owmw_data["latitude"] . $owmw_opt["temperature_unit"] . $owmw_opt["owm_language"];
              	if (false === ( $owmweather = get_transient( $transient_key))) {
    			    $response = wp_remote_get(esc_url_raw($url), array( 'timeout' => 10));
                    if (!is_wp_error($response)) {
            			$owmweather = json_decode(wp_remote_retrieve_body($response));
            			set_transient($transient_key, $owmweather, $owmw_opt["cache_time"] * MINUTE_IN_SECONDS );
            		} else {
                	  	$response = array();
        	          	$response['weather'] = $owmw_params["weather_id"];
                	  	$response['html'] = "<p>OWM Weather id '" . esc_attr($owmw_opt["id"]) . "': OWM Error " . esc_htlm__('Unable to retrieve weather data','owm-weather') . "</p>";
        	        	wp_send_json_error($response, 400);
                        return;
            		}
	            }
		    }
		}

        if (!empty($owmweather->cod) && $owmweather->cod != "200") {
    	  	$response = array();
    	  	$response['weather'] = $owmw_params["weather_id"];
    	  	$response['html'] = "<p>OWM Weather id '" . $owmw_opt["id"] . "': OWM Error " . $owmweather->cod . (!empty($owmweather->message) ? " (" . $owmweather->message . ")" : "") . "</p>";
    		wp_send_json_success($response);
    		return;
        }

        owmw_sanitize_api_response($owmweather, array("description"));

        if ($owmw_opt["dew_point"] == "yes" || $owmw_opt["uv_index"] == "yes" || $owmw_opt["gtag"] == "yes") {
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
			      		$owmw_data["daily"][$i]["day"] = $owmw_opt["display_length_days_names"] == 'short' ? esc_html__('Sun','owm-weather') : esc_html__('Sunday','owm-weather');
			      		break;
			    	case "1":
			      		$owmw_data["daily"][$i]["day"] = $owmw_opt["display_length_days_names"] == 'short' ? esc_html__('Mon','owm-weather') : esc_html__('Monday','owm-weather');
			      		break;
			    	case "2":
			    		$owmw_data["daily"][$i]["day"] = $owmw_opt["display_length_days_names"] == 'short' ? esc_html__('Tue','owm-weather') : esc_html__('Tuesday','owm-weather');
			      		break;
			    	case "3":
			    		$owmw_data["daily"][$i]["day"] = $owmw_opt["display_length_days_names"] == 'short' ? esc_html__('Wed','owm-weather') : esc_html__('Wednesday','owm-weather');
			      		break;
			    	case "4":
			    		$owmw_data["daily"][$i]["day"] = $owmw_opt["display_length_days_names"] == 'short' ? esc_html__('Thu','owm-weather') : esc_html__('Thursday','owm-weather');
			      		break;
			    	case "5":
			    		$owmw_data["daily"][$i]["day"] = $owmw_opt["display_length_days_names"] == 'short' ? esc_html__('Fri','owm-weather') : esc_html__('Friday','owm-weather');
			      		break;
			    	case "6":
			    		$owmw_data["daily"][$i]["day"] = $owmw_opt["display_length_days_names"] == 'short' ? esc_html__('Sat','owm-weather') : esc_html__('Saturday','owm-weather');
				      	break;
			    }

                $owmw_data["daily"][$i]["timestamp"] = $value->dt ? $value->dt + (60 * $utc_time_wp) : null;
                $owmw_data["daily"][$i]["timestamp_sunrise"] = $value->sunrise ? $value->sunrise + (60 * $utc_time_wp) : null;
                $owmw_data["daily"][$i]["timestamp_sunset"] = $value->sunset ? $value->sunset + (60 * $utc_time_wp) : null;
                $owmw_data["daily"][$i]["sunrise"] = (string)date($owmweather_time_php, $owmw_data["daily"][$i]["timestamp_sunrise"]);
                $owmw_data["daily"][$i]["sunset"] = (string)date($owmweather_time_php, $owmw_data["daily"][$i]["timestamp_sunset"]);
                $owmw_data["daily"][$i]["timestamp_moonrise"] = $value->moonrise ? $value->moonrise + (60 * $utc_time_wp) : null;
                $owmw_data["daily"][$i]["timestamp_moonset"] = $value->moonset ? $value->moonset + (60 * $utc_time_wp) : null;
                $owmw_data["daily"][$i]["moonrise"] = (string)date($owmweather_time_php, $owmw_data["daily"][$i]["timestamp_moonrise"]);
                $owmw_data["daily"][$i]["moonset"] = (string)date($owmweather_time_php, $owmw_data["daily"][$i]["timestamp_moonset"]);
                $owmw_data["daily"][$i]["moon_phase"] = $value->moon_phase ?? null;
                $owmw_data["daily"][$i]["condition_id"] = $value->weather[0]->id ?? null;
                $owmw_data["daily"][$i]["category"] = $value->weather[0]->main ?? null;
                $owmw_data["daily"][$i]["description"] = $value->weather[0]->description ?? null;
                $owmw_data["daily"][$i]["wind_speed"] = owmw_getConvertedWindSpeed($value->wind_speed, $owmw_opt["temperature_unit"], $owmw_opt["wind_unit"]) . ' ' . $owmw_data["wind_speed_unit"];
                $owmw_data["daily"][$i]["wind_degrees"] = $value->wind_deg ?? null;
                $owmw_data["daily"][$i]["wind_direction"] = owmw_getWindDirection($value->wind_deg);
                $owmw_data["daily"][$i]["wind_gust"] = isset($value->wind_gust) ? owmw_getConvertedWindSpeed($value->wind_gust, $owmw_opt["temperature_unit"], $owmw_opt["wind_unit"])  . ' ' . $owmw_data["wind_speed_unit"] : null;
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
                $owmw_data["daily"][$i]["pressure"] = owmw_converthp2iom($owmw_opt["temperature_unit"], $value->pressure) . ' ' . $owmw_data["pressure_unit"];
                $owmw_data["daily"][$i]["cloudiness"] = $value->clouds ? $value->clouds . '%' : '0%';
                $owmw_data["daily"][$i]["uv_index"] = $value->uvi ?? null;
                $owmw_data["daily"][$i]["rain_chance"] = $value->pop ? number_format($value->pop * 100, 0) . '%' : '0%';
                $owmw_data["daily"][$i]["rain"] = owmw_getConvertedPrecipitation($owmw_opt["temperature_unit"], $value->rain ?? 0) . ' ' . $owmw_data["precipitation_unit"];
                $owmw_data["daily"][$i]["snow"] = owmw_getConvertedPrecipitation($owmw_opt["temperature_unit"], $value->snow ?? 0) . ' ' . $owmw_data["precipitation_unit"];
                $owmw_data["daily"][$i]["precipitation"] = $owmw_data["daily"][$i]["rain"] ?? 0 + $owmw_data["daily"][$i]["snow"] ?? 0  . ' ' . $owmw_data["precipitation_unit"];

			    $date_index = date('Ymd', $owmw_data["daily"][$i]["timestamp"]);
			    $owmw_data[$date_index]["sunrise"] = $owmw_data["daily"][$i]["timestamp_sunrise"];
			    $owmw_data[$date_index]["sunset"] = $owmw_data["daily"][$i]["timestamp_sunset"];
			}
		}//End days loop

		//Hours loop (must be after days loop)
		if($owmw_opt["hours_forecast_no"] > 0) {
            $cnt = 0;
            foreach ($owmweather->hourly as $i => $value) {
                if ($value->dt > (time()-3600)) {
                    $owmw_data["hourly"][$cnt]["timestamp"] = $value->dt ? $value->dt + (60 * $utc_time_wp) : null;
                    $owmw_data["hourly"][$cnt]["time"] = (string)date($owmweather_hours_php, $value->dt + (60*$utc_time_wp));
                    $owmw_data["hourly"][$cnt]["condition_id"] = $value->weather[0]->id ?? null;
                    $owmw_data["hourly"][$cnt]["category"] = $value->weather[0]->main ?? null;
                    $owmw_data["hourly"][$cnt]["description"] = $value->weather[0]->description ?? null;
                    $owmw_data["hourly"][$cnt]["wind_speed"] = owmw_getConvertedWindSpeed($value->wind_speed, $owmw_opt["temperature_unit"], $owmw_opt["wind_unit"]) . ' ' . $owmw_data["wind_speed_unit"];
                    $owmw_data["hourly"][$cnt]["wind_degrees"] = $value->wind_deg ?? null;
                    $owmw_data["hourly"][$cnt]["wind_direction"] = owmw_getWindDirection($value->wind_deg);
                    $owmw_data["hourly"][$cnt]["wind_gust"] = isset($value->wind_gust) ? owmw_getConvertedWindSpeed($value->wind_gust, $owmw_opt["temperature_unit"], $owmw_opt["wind_unit"]) . ' ' . $owmw_data["wind_speed_unit"] : null;
                    $owmw_data["hourly"][$cnt]["temperature"] = $value->temp ? ceil($value->temp) : null;
                    $owmw_data["hourly"][$cnt]["feels_like"] = $value->feels_like ? ceil($value->feels_like) : null;
					if ($owmw_opt["temperature_unit"] == 'metric') {
						$owmw_data["hourly"][$cnt]["temperature_celsius"] = $owmw_data["hourly"][$cnt]["temperature"];
						$owmw_data["hourly"][$cnt]["temperature_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["hourly"][$cnt]["temperature"]);
						$owmw_data["hourly"][$cnt]["feels_like_celsius"] = $owmw_data["hourly"][$cnt]["feels_like"];
						$owmw_data["hourly"][$cnt]["feels_like_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["hourly"][$cnt]["feels_like"]);
					}else {
						$owmw_data["hourly"][$cnt]["temperature_fahrenheit"] = $owmw_data["hourly"][$cnt]["temperature"];
						$owmw_data["hourly"][$cnt]["temperature_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["hourly"][$cnt]["temperature"]);
						$owmw_data["hourly"][$cnt]["feels_like_fahrenheit"] = $owmw_data["hourly"][$cnt]["feels_like"];
						$owmw_data["hourly"][$cnt]["feels_like_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["hourly"][$cnt]["feels_like"]);
					}
                    $owmw_data["hourly"][$cnt]["humidity"] = $value->humidity ? $value->humidity . '%' : null;
                    $owmw_data["hourly"][$cnt]["pressure"] = owmw_converthp2iom($owmw_opt["temperature_unit"], $value->pressure) . ' ' . $owmw_data["pressure_unit"];
                    $owmw_data["hourly"][$cnt]["dew_point"] = $value->dew_point ? ceil($value->dew_point) : null;
                    $owmw_data["hourly"][$cnt]["cloudiness"] = $value->clouds ? $value->clouds . '%' : "0%";
                    $owmw_data["hourly"][$cnt]["uv_index"] = $value->uvi ?? null;
                    $owmw_data["hourly"][$cnt]["rain_chance"] = $value->pop ? number_format($value->pop * 100, 0) .'%': '0%';
                    $owmw_data["hourly"][$cnt]["visibility"] = owmw_getConvertedDistance($owmw_opt["temperature_unit"], $value->visibility);
                    $owmw_data["hourly"][$cnt]["rain"] = owmw_getConvertedPrecipitation($owmw_opt["temperature_unit"], $value->rain->{"1h"} ?? 0) . ' ' . $owmw_data["precipitation_unit"];
                    $owmw_data["hourly"][$cnt]["snow"] = owmw_getConvertedPrecipitation($owmw_opt["temperature_unit"], $value->snow->{"1h"} ?? 0) . ' ' . $owmw_data["precipitation_unit"];
                    $owmw_data["hourly"][$cnt]["precipitation"] = $owmw_data["hourly"][$cnt]["rain"] ?? 0 + $owmw_data["hourly"][$cnt]["snow"] ?? 0 . ' ' . $owmw_data["precipitation_unit"];
       			    $date = date('Ymd', $owmw_data["hourly"][$cnt]["timestamp"]);
       			    if (isset($owmw_data[$date])) {
           			    $owmw_data["hourly"][$cnt]["day_night"] = ($owmw_data["hourly"][$cnt]["timestamp"] > $owmw_data[$date]["sunrise"] && $owmw_data["hourly"][$cnt]["timestamp"] < $owmw_data[$date]["sunset"]) ? 'day' : 'night';
           			} else {
                        $owmw_data["hourly"][$cnt]["day_night"] = 'night';
           			}
       			    ++$cnt;
                }
		  	}
		}

        //Moon rise and set
        if (!empty($owmw_data["daily"])) {
            $owmw_data["timestamp_moonrise"] = $owmw_data["daily"][0]["timestamp_moonrise"];
            $owmw_data["timestamp_moonset"] = $owmw_data["daily"][0]["timestamp_moonset"];
            $owmw_data["moonrise"] = (string)date($owmweather_time_php, $owmw_data["timestamp_moonrise"]);
            $owmw_data["moonset"] = (string)date($owmweather_time_php, $owmw_data["timestamp_moonset"]);
        }

		//Alerts loop
		if (isset($owmweather->alerts)) {
			foreach ($owmweather->alerts as $i => $value) {
			    $owmw_data["alerts"][$i]["sender"] = $value->sender_name;
			    $owmw_data["alerts"][$i]["event"] = $value->event;
			    $owmw_data["alerts"][$i]["start"] = date_i18n( __( 'M j, Y @ G:i' ), $value->start );
			    $owmw_data["alerts"][$i]["end"] = date_i18n( __( 'M j, Y @ G:i' ), $value->end );
			    $owmw_data["alerts"][$i]["text"] = $value->description;
			}
		}


        //JSON : 5 day forecast weather (relies on lat and lon from current weather call)
        $url = "https://api.openweathermap.org/data/2.5/forecast?lon=".$owmw_data["longitude"]."&lat=".$owmw_data["latitude"]."&mode=json&exclude=minutely&lang=".$owmw_opt["owm_language"]."&units=".$owmw_opt["temperature_unit"]."&APPID=".$owmw_opt["api_key"];
/* bugbug condition ? */
        if($owmw_opt["hours_forecast_no"] > 0 || $owmw_opt["days_forecast_no"] > 0 || $owmw_opt["alerts"] == 'yes' || $owmw_opt["moonrise_moonset"] == "yes" || $owmw_opt["dew_point"] == "yes" || $owmw_opt["uv_index"] == "yes" || $owmw_opt["gtag"] == "yes") {
    		if ($owmw_opt["disable_cache"] == 'yes') {
    			$response = wp_remote_get(esc_url_raw($url), array( 'timeout' => 10));
                if (!is_wp_error($response)) {
        			$owmforecast = json_decode(wp_remote_retrieve_body($response));
        		} else {
               	  	$response = array();
       	          	$response['weather'] = $owmw_params["weather_id"];
               	  	$response['html'] = "<p>OWM Weather id '" . esc_attr($owmw_opt["id"]) . "': OWM Error " . esc_htlm__('Unable to retrieve weather data','owm-weather') . "</p>";
       	        	wp_send_json_error($response, 400);
                    return;
        		}
        	} else {
        	    $transient_key = 'owmweather_5day_' . $owmw_data["longitude"] . $owmw_data["latitude"] . $owmw_opt["temperature_unit"] . $owmw_opt["owm_language"];
              	if (false === ( $owmforecast = get_transient( $transient_key))) {
    			    $response = wp_remote_get(esc_url_raw($url), array( 'timeout' => 10));
                    if (!is_wp_error($response)) {
            			$owmforecast = json_decode(wp_remote_retrieve_body($response));
            			set_transient($transient_key, $owmforecast, $owmw_opt["cache_time"] * MINUTE_IN_SECONDS );
            		} else {
                	  	$response = array();
        	          	$response['weather'] = $owmw_params["weather_id"];
                	  	$response['html'] = "<p>OWM Weather id '" . esc_attr($owmw_opt["id"]) . "': OWM Error " . esc_htlm__('Unable to retrieve weather data','owm-weather') . "</p>";
        	        	wp_send_json_error($response, 400);
                        return;
            		}
	            }
		    }
		}

		//Forecast loop
		if (!empty($owmforecast)) {
            $cnt = 0;
            foreach ($owmforecast->list as $i => $value) {
                if ($value->dt > (time()-3600)) {
                    $owmw_data["forecast"][$cnt]["timestamp"] = $value->dt ? $value->dt + (60 * $utc_time_wp) : null;
                    $owmw_data["forecast"][$cnt]["time"] = (string)date($owmweather_hours_php, $value->dt + (60*$utc_time_wp));
                    $owmw_data["forecast"][$cnt]["day"] = (string)date('D', $value->dt + (60*$utc_time_wp));
                    $owmw_data["forecast"][$cnt]["condition_id"] = $value->weather[0]->id ?? null;
                    $owmw_data["forecast"][$cnt]["category"] = $value->weather[0]->main ?? null;
                    $owmw_data["forecast"][$cnt]["description"] = $value->weather[0]->description ?? null;
                    $owmw_data["forecast"][$cnt]["wind_speed"] = owmw_getConvertedWindSpeed($value->wind->speed, $owmw_opt["temperature_unit"], $owmw_opt["wind_unit"]) . ' ' . $owmw_data["wind_speed_unit"];
                    $owmw_data["forecast"][$cnt]["wind_degrees"] = $value->wind->deg ?? null;
                    $owmw_data["forecast"][$cnt]["wind_direction"] = owmw_getWindDirection($value->wind->deg);
                    $owmw_data["forecast"][$cnt]["wind_gust"] = isset($value->wind->gust) ? owmw_getConvertedWindSpeed($value->wind->gust, $owmw_opt["temperature_unit"], $owmw_opt["wind_unit"]) . ' ' . $owmw_data["wind_speed_unit"] : null;
                    $owmw_data["forecast"][$cnt]["temperature"] = $value->main->temp ? ceil($value->main->temp) : null;
					$temp_min = $value->main->temp_min ? ceil($value->main->temp_min) : null;
					if (empty($owmw_data["forecast"]["temperature_minimum"][$owmw_data["forecast"][$cnt]["day"]]) || $owmw_data["forecast"]["temperature_minimum"][$owmw_data["forecast"][$cnt]["day"]] > $temp_min) {
						$owmw_data["forecast"]["temperature_minimum"][$owmw_data["forecast"][$cnt]["day"]] = $temp_min;
					}
					$temp_max = $value->main->temp_max ? ceil($value->main->temp_max) : null;
					if (empty($owmw_data["forecast"]["temperature_maximum"][$owmw_data["forecast"][$cnt]["day"]]) || $owmw_data["forecast"]["temperature_maximum"][$owmw_data["forecast"][$cnt]["day"]] < $temp_max) {
						$owmw_data["forecast"]["temperature_maximum"][$owmw_data["forecast"][$cnt]["day"]] = $temp_max;
					}
                    $owmw_data["forecast"][$cnt]["feels_like"] = $value->main->feels_like ? ceil($value->main->feels_like) : null;
					if ($owmw_opt["temperature_unit"] == 'metric') {
						$owmw_data["forecast"][$cnt]["temperature_celsius"] = $owmw_data["forecast"][$cnt]["temperature"];
						$owmw_data["forecast"][$cnt]["temperature_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["forecast"][$cnt]["temperature"]);
						$owmw_data["forecast"]["temperature_minimum_celsius"] = $owmw_data["forecast"]["temperature_minimum"][$owmw_data["forecast"][$cnt]["day"]];
						$owmw_data["forecast"]["temperature_minimum_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["forecast"]["temperature_minimum_celsius"]);
						$owmw_data["forecast"]["temperature_maximum_celsius"] = $owmw_data["forecast"]["temperature_maximum"][$owmw_data["forecast"][$cnt]["day"]];
						$owmw_data["forecast"]["temperature_maximum_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["forecast"]["temperature_maximum_celsius"]);
						$owmw_data["forecast"][$cnt]["feels_like_celsius"] = $owmw_data["forecast"][$cnt]["feels_like"];
						$owmw_data["forecast"][$cnt]["feels_like_fahrenheit"] = owmw_celsius_to_fahrenheit($owmw_data["forecast"][$cnt]["feels_like"]);
					}else {
						$owmw_data["forecast"][$cnt]["temperature_fahrenheit"] = $owmw_data["forecast"][$cnt]["temperature"];
						$owmw_data["forecast"][$cnt]["temperature_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["forecast"][$cnt]["temperature"]);
						$owmw_data["forecast"]["temperature_minimum_fahrenheit"] = $owmw_data["forecast"]["temperature_minimum"][$owmw_data["forecast"][$cnt]["day"]];
						$owmw_data["forecast"]["temperature_minimum_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["forecast"]["temperature_minimum_fahrenheit"]);
						$owmw_data["forecast"]["temperature_maximum_fahrenheit"] = $owmw_data["forecast"]["temperature_maximum"][$owmw_data["forecast"][$cnt]["day"]];
						$owmw_data["forecast"]["temperature_maximum_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["forecast"]["temperature_maximum_fahrenheit"]);
						$owmw_data["forecast"][$cnt]["feels_like_fahrenheit"] = $owmw_data["forecast"][$cnt]["feels_like"];
						$owmw_data["forecast"][$cnt]["feels_like_celsius"] = owmw_fahrenheit_to_celsius($owmw_data["forecast"][$cnt]["feels_like"]);
					}
                    $owmw_data["forecast"][$cnt]["humidity"] = $value->main->humidity ? $value->main->humidity . '%' : null;
                    $owmw_data["forecast"][$cnt]["pressure"] = owmw_converthp2iom($owmw_opt["temperature_unit"], $value->main->pressure) . ' ' . $owmw_data["pressure_unit"];
                    $owmw_data["forecast"][$cnt]["cloudiness"] = $value->clouds->all ? $value->clouds->all . '%' : "0%";
                    $owmw_data["forecast"][$cnt]["rain_chance"] = $value->pop ? number_format($value->pop * 100, 0) .'%': '0%';
                    $owmw_data["forecast"][$cnt]["visibility"] = owmw_getConvertedDistance($owmw_opt["temperature_unit"], $value->visibility);
       			    $owmw_data["forecast"][$cnt]["day_night"] = $value->sys->pod == 'd' ? 'day' : 'night';
       			    ++$cnt;
                }
		  	}
		}

        // escape all data fields for use as html blocks
        owmw_esc_html_all($owmw_data);

		//variable declarations
        $owmw_html = [];
		$owmw_html["now"]["start"]             	= '';
		$owmw_html["now"]["location_name"]       = '';
		$owmw_html["now"]["symbol"]       	    = '';
		$owmw_html["now"]["temperature"]         = '';
		$owmw_html["now"]["temperature_celsius"]  = '';
		$owmw_html["now"]["temperature_fahrenheit"]   = '';
		$owmw_html["now"]["feels_like"]          = '';
		$owmw_html["now"]["feels_like_celsius"]          = '';
		$owmw_html["now"]["feels_like_fahrenheit"]          = '';
		$owmw_html["now"]["weather_description"] = '';
		$owmw_html["now"]["end"]               	= '';
		$owmw_html["custom_css"] = $owmw_opt["custom_css"] ?? '';
		$owmw_html["today"]["start"]          	= '';
		$owmw_html["today"]["day"]          		= '';
		$owmw_html["today"]["sun"]             	= '';
		$owmw_html["today"]["moon"]             	= '';
		$owmw_html["info"]["start"]             	= '';
		$owmw_html["info"]["wind"]            	= '';
		$owmw_html["info"]["humidity"]          	= '';
		$owmw_html["info"]["dew_point"]         	= '';
		$owmw_html["info"]["dew_point_celsius"]    	= '';
		$owmw_html["info"]["dew_point_fahrenheit"] 	= '';
		$owmw_html["info"]["pressure"]           = '';
		$owmw_html["info"]["cloudiness"]         = '';
		$owmw_html["info"]["precipitation"]      = '';
		$owmw_html["info"]["visibility"]         = '';
		$owmw_html["info"]["uv_index"]           = '';
		$owmw_html["svg"]["wind"]            	= '';
		$owmw_html["svg"]["humidity"]          	= '';
		$owmw_html["svg"]["dew_point"]          	= '';
		$owmw_html["svg"]["pressure"]            = '';
		$owmw_html["svg"]["cloudiness"]          = '';
		$owmw_html["svg"]["precipitation"]       = '';
		$owmw_html["svg"]["visibility"]          = '';
		$owmw_html["svg"]["uv_index"]            = '';
		$owmw_html["info"]["end"]             	= '';
		$owmw_html["hour"]["info"]               = '';
		$owmw_html["hour"]["start"]            	= '';
		$owmw_html["hour"]["end"]              	= '';
		$owmw_html["map"]                 		= '';
		$owmw_html["map_script"]           		= '';
		$owmw_html["today"]["end"]          		= '';
		$owmw_html["forecast"]["start"]          = '';
		$owmw_html["forecast"]["info"]           = '';
		$owmw_html["forecast"]["end"]            = '';
		$owmw_html["forecast"]["start_card"]     = '';
		$owmw_html["forecast"]["info_card"]      = '';
		$owmw_html["forecast"]["end_card"]       = '';
		$owmw_html["container"]["start"]         = '';
		$owmw_html["container"]["end"]           = '';
		$owmw_html["owm_link"]            		= '';
		$owmw_html["last_update"]         		= '';
    	$owmw_html["owm_link_last_update_start"] = '';
    	$owmw_html["owm_link_last_update_end"]   = '';
        $owmw_html["gtag"]                       = '';
        $owmw_html["alert_button"]               = '';
        $owmw_html["alert_modal"]                = '';
        $owmw_html["table"]["hourly"]            = '';
        $owmw_html["table"]["daily"]             = '';
        $owmw_html["table"]["forecast"]          = '';

		$owmw_html["main_weather_div"]      = esc_attr($owmw_params["weather_id"]);
        $owmw_html["container_weather_div"] = owmw_unique_id_esc('owm-weather-container-'.$owmw_opt["id"]);
        $owmw_html["main_map_div"]          = owmw_unique_id_esc('owmw-map-id-'.$owmw_opt["id"]);
        $owmw_html["container_map_div"]     = owmw_unique_id_esc('owmw-map-container-'.$owmw_opt["id"]);

       	$owmw_html["svg"]["wind"]          = owmw_wind_direction_icon($owmw_data["wind_degrees"], $owmw_opt["text_color"], $owmw_opt["wind_icon_direction"]);
       	$owmw_html["svg"]["humidity"]      = owmw_humidity_icon($owmw_opt["text_color"]);
      	$owmw_html["svg"]["dew_point"]     = owmw_dew_point_icon($owmw_opt["text_color"]);
       	$owmw_html["svg"]["pressure"]      = owmw_pressure_icon($owmw_opt["text_color"]);
       	$owmw_html["svg"]["cloudiness"]    = owmw_cloudiness_icon($owmw_opt["text_color"]);
       	$owmw_html["svg"]["rain_chance"]   = owmw_rain_chance_icon($owmw_opt["text_color"]);
       	$owmw_html["svg"]["precipitation"] = owmw_precipitation_icon($owmw_opt["text_color"]);
       	$owmw_html["svg"]["visibility"]    = owmw_visibility_icon($owmw_opt["text_color"]);
       	$owmw_html["svg"]["uv_index"]      = owmw_uv_index_icon($owmw_opt["text_color"]);

	    $owmw_html["current"]["day_night"] = ($owmw_data["timestamp"] > $owmw_data["timestamp_sunrise"] && $owmw_data["timestamp"] < $owmw_data["timestamp_sunset"]) ? 'day' : 'night';
        $owmw_html["current"]["symbol_svg"] = owmw_weatherSVG($owmw_data["condition_id"], $owmw_html["current"]["day_night"]);
        $owmw_html["current"]["symbol_alt"] = owmw_weatherIcon($owmw_opt["iconpack"], $owmw_data["condition_id"], $owmw_html["current"]["day_night"], $owmw_data["description"]);

		$display_now_start = '<div class="owmw-now">';
		$display_now_location_name = '<div class="owmw-location-name">'. owmw_city_name($owmw_opt["custom_city_name"], $owmw_data["name"])  .'</div>';
        if ($owmw_opt["disable_anims"] != 'yes') {
    		$display_now_symbol = '<div class="owmw-main-symbol owmw-symbol-svg climacon" style="'. esc_attr(owmw_css_color("fill", $owmw_opt["text_color"])) .'"><span title="'.esc_attr($owmw_data["description"]).'">'. $owmw_html["current"]["symbol_svg"] .'</span></div>';
    	} else {
    		$display_now_symbol = '<div class="owmw-main-symbol owmw-symbol-alt" style="'. esc_attr(owmw_css_color("fill", $owmw_opt["text_color"])) .'"><span title="'.esc_attr($owmw_data["description"]).'">'. $owmw_html["current"]["symbol_alt"] .'</span></div>';
    	}
		$display_now_temperature = '<div class="owmw-main-temperature">'. esc_html($owmw_data["temperature"]) .'</div>';
		$display_now_temperature_celsius = '<div class="owmw-main-temperature-celsius">'. esc_html($owmw_data["temperature_celsius"]) .'</div>';
		$display_now_temperature_fahrenheit = '<div class="owmw-main-temperature-fahrenheit">'. esc_html($owmw_data["temperature_fahrenheit"]) .'</div>';
		$display_now_feels_like = '<div class="owmw-main-feels-like">Feels like '. esc_html($owmw_data["feels_like"]) .'</div>';
		$display_now_feels_like_celsius = '<div class="owmw-main-feels-like-celsius">Feels like '. esc_html($owmw_data["feels_like_celsius"]) .'</div>';
		$display_now_feels_like_fahrenheit = '<div class="owmw-main-feels-like-fahrenheit">Feels like '. esc_html($owmw_data["feels_like_fahrenheit"]) .'</div>';
		$display_now_end = '</div>';

		//Hours loop
	    if ($owmw_opt["hours_forecast_no"] > 0) {
	    	$owmweather_class_hours = array( 0 => "first", 1 => "second", 2 => "third", 3 => "fourth", 4 => "fifth", 5 => "sixth", 6 => "seventh", 7 => "eighth", 8 => "ninth", 9 => "tenth", 10 => "eleventh", 11 => "twelfth", 12 => "thirteenth", 13 => "fourteenth", 14 => "fifteenth", 15 => "sixteenth", 16 => "seventeenth", 17 => "eighteenth", 18 => "nineteenth", 19 => "twentieth", 20 => "twentyfirst", 21 => "twentysecond", 22 => "twentythird", 23 => "twentyfourth", 24 => "twentyfifth", 25 => "twentysixth", 26 => "twentyseventh", 27 => "twentyeighth", 28 => "twentyninth", 29 => "thirtieth", 30 => "thirtyfirst", 31 => "thirtysecond", 32 => "thirtythird", 33 => "thirtyfourth", 34 => "thirtyfifth", 35 => "thirtysixth", 36 => "thirtyseventh", 37 => "thirtyeighth", 38 => "thirtyninth", 39 => "fortieth", 40 => "fortyfirst", 41 => "fortysecond", 42 => "fortythird", 43 => "fortyfourth", 44 => "fortyfifth", 45 => "fortysixth", 46 => "fortyseventh", 47 => "fortyeighth" );

			foreach ($owmw_data["hourly"] as $i => $value) {
				$display_hours_icon[$i] = owmw_weatherIcon($owmw_opt["iconpack"], $value["condition_id"], $value["day_night"], $value["description"]);
				$display_hours[$i] =
   					'<div class="owmw-'. $owmweather_class_hours[$i].' card">
   						<div class="card-body">
   						    <div class="owmw-hour">'. date('D', $value["timestamp"]) . '<br>' .
       						    ($owmw_opt["hours_time_icons"] == 'yes' ? owmw_hour_icon($value["time"], $owmw_opt["text_color"]) : esc_html($value["time"])) .
       				        '</div>' .
       				        $display_hours_icon[$i] .
       				        '<div class="owmw-temperature">'.
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
			   		'<div class="owmw-'. esc_attr($owmweather_class_days[$i]).' row">
			      		<div class="owmw-day col">'. ($i == 0 ? "Today" : esc_html($value["day"])) .'</div>' . '<div class="col">' . $esc_display_forecasticon . '</div>';

			      		if ($owmw_opt["forecast_precipitations"] == 'yes') {
		      				$display_forecast[$i] .= '<div class="owmw-rain col">'. esc_html($value["precipitation"]) . '</div>';
			      		}

			      		$display_forecast[$i] .=
			      		'<div class="owmw-temp-min col">'. esc_html($value["temperature_minimum"]) .'</div>
			      		<div class="owmw-temp-max col"><span class="font-weight-bold">'. esc_html($value["temperature_maximum"]) .'</span></div>
			    	</div>';
        		$display_forecast_card[$i] =
   					'<div class="owmw-'. esc_attr($owmweather_class_days[$i]).' card">
   						<div class="card-body">
					        <div class="owmw-day">'. ($i == 0 ? "Today" : esc_html($value["day"])) .'</div>' . $esc_display_forecasticon .
       				        '<div class="owmw-temperature">
       				            <span class="owmw-temp-min">' . esc_html($value["temperature_minimum"]) . '</span> - <span class="owmw-temp-max">' . esc_html($value["temperature_maximum"]) . '</span>
       				        </div>
       				        <div class="owmw-infos-text">';
       				            if ($owmw_opt["wind"] == 'yes') {
               				        $display_forecast_card[$i] .= '<div>'.owmw_wind_direction_icon($value["wind_degrees"], $owmw_opt["text_color"], $owmw_opt["wind_icon_direction"]).'Wind: <span class="owmw-value">' .esc_html($value["wind_speed"].' '.$value["wind_direction"]).'</span></div>';
           				        }
       				            if ($owmw_opt["humidity"] == 'yes') {
               				        $display_forecast_card[$i] .= '<div>'.$owmw_html["svg"]["humidity"].'Humidity: <span class="owmw-value">' .esc_html($value["humidity"]). '</span></div>';
               				    }
       				            if ($owmw_opt["dew_point"] == 'yes') {
               				        $display_forecast_card[$i] .= '<div>'.$owmw_html["svg"]["dew_point"].'Dew point: <span class="owmw-value owmw-temperature">' .esc_html($value["dew_point"]). '</span></div>';
               				    }
       				            if ($owmw_opt["pressure"] == 'yes') {
               				        $display_forecast_card[$i] .= '<div>'.$owmw_html["svg"]["pressure"].'Pressure: <span class="owmw-value">' .esc_html($value["pressure"]). '</span></div>';
               				    }
       				            if ($owmw_opt["cloudiness"] == 'yes') {
               				        $display_forecast_card[$i] .= '<div>'.$owmw_html["svg"]["cloudiness"].'Clouds: <span class="owmw-value">' .esc_html($value["cloudiness"]). '</span></div>';
               				    }
       				            if ($owmw_opt["precipitation"] == 'yes') {
               				        $display_forecast_card[$i] .= '<div>'.$owmw_html["svg"]["rain_chance"].'Rain Chance: <span class="owmw-value">' .esc_html($value["rain_chance"]). '</span></div>';
               				    }
       				            if ($owmw_opt["precipitation"] == 'yes') {
               				        $display_forecast_card[$i] .= '<div>'.$owmw_html["svg"]["precipitation"].'Precipitation: <span class="owmw-value">' .esc_html($value["precipitation"]). '</span></div>';
               				    }
       				            if ($owmw_opt["uv_index"] == 'yes') {
               				        $display_forecast_card[$i] .= '<div>'.$owmw_html["svg"]["uv_index"].'UV index: <span class="owmw-value">' .esc_html($value["uv_index"]). '</span></div>';
               				    }
        		$display_forecast_card[$i] .=
           				    '</div>
    					</div>
   					</div>';
			}
		}

	    //Map

	      	if ($owmw_opt['map'] == 'yes') {

		    	//Layers opacity
	    		$display_map_layers_opacity = intval($owmw_opt["map_opacity"]);

                $display_map_layers  = '';
                $display_map_options = '';

		    	//Stations
		    	if ( $owmw_opt["map_stations"] == 'yes' ) {
		        	$display_map_options         	.= 'var station = L.OWM.current({type: "station", appId: "'.esc_attr($owmw_opt["api_key"]).'"});';
		        	$display_map_layers             .= '"Stations": station,';
		      	}

		      	//Clouds
		      	if ( $owmw_opt["map_clouds"] == 'yes' ) {
		        	$display_map_options         	.= 'var clouds = L.OWM.clouds({showLegend: false, opacity: '.esc_attr($display_map_layers_opacity).', appId: "'.esc_attr($owmw_opt["api_key"]).'"});';
		        	$display_map_layers             .= '"Clouds": clouds,';
		        	$display_map_options         	.= 'var cloudscls = L.OWM.cloudsClassic({showLegend: false, opacity: '.esc_attr($display_map_layers_opacity).', appId: "'.esc_attr($owmw_opt["api_key"]).'"});';
		        	$display_map_layers             .= '"Clouds Classic": cloudscls,';
		      	}

		      	//Precipitations and Rain
		      	if ( $owmw_opt["map_precipitation"] == 'yes' ) {
		        	$display_map_options         	.= 'var rain = L.OWM.rain({opacity: '.esc_attr($display_map_layers_opacity).', appId: "'.esc_attr($owmw_opt["api_key"]).'"});';
		        	$display_map_layers             .= '"Rain": rain,';
		        	$display_map_options         	.= 'var raincls = L.OWM.rainClassic({opacity: '.esc_attr($display_map_layers_opacity).', appId: "'.esc_attr($owmw_opt["api_key"]).'"});';
		        	$display_map_layers             .= '"Rain Classic": raincls,';
		        	$display_map_options         	.= 'var precipitation = L.OWM.precipitation({opacity: '.esc_attr($display_map_layers_opacity).', appId: "'.esc_attr($owmw_opt["api_key"]).'"});';
		        	$display_map_layers             .= '"Precipitation": precipitation,';
		        	$display_map_options         	.= 'var precipitationcls = L.OWM.precipitationClassic({opacity: '.esc_attr($display_map_layers_opacity).', appId: "'.esc_attr($owmw_opt["api_key"]).'"});';
		        	$display_map_layers             .= '"Precipitation Classic": precipitationcls,';
		      	}

		      	//Snow
		      	if ( $owmw_opt["map_snow"] == 'yes' ) {
		        	$display_map_options         	.= 'var snow = L.OWM.snow({opacity: '.esc_attr($display_map_layers_opacity).', appId: "'.esc_attr($owmw_opt["api_key"]).'"});';
		        	$display_map_layers             .= '"Snow": snow,';
		      	}

		      	//Wind
		      	if ( $owmw_opt["map_wind"] == 'yes' ) {
		        	$display_map_options         	.= 'var wind = L.OWM.wind({opacity: '.esc_attr($display_map_layers_opacity).', appId: "'.esc_attr($owmw_opt["api_key"]).'"});';
		        	$display_map_layers             .= '"Wind": wind,';
		      	}

		      	//Temperature
		      	if ( $owmw_opt["map_temperature"] == 'yes' ) {
		        	$display_map_options         	.= 'var temp = L.OWM.temperature({opacity: '.esc_attr($display_map_layers_opacity).', appId: "'.esc_attr($owmw_opt["api_key"]).'"});';
		        	$display_map_layers             .= '"Temperature": temp,';
		      	}

		      	//Pressure
		      	if ( $owmw_opt["map_pressure"] == 'yes' ) {
		        	$display_map_options         	.= 'var pressure = L.OWM.pressure({opacity: '.esc_attr($display_map_layers_opacity).', appId: "'.esc_attr($owmw_opt["api_key"]).'"});';
		        	$display_map_layers             .= '"Pressure": pressure,';
		        	$display_map_options         	.= 'var pressurecntr = L.OWM.pressureContour({opacity: '.esc_attr($display_map_layers_opacity).', appId: "'.esc_attr($owmw_opt["api_key"]).'"});';
		        	$display_map_layers             .= '"Pressure Contour": pressurecntr,';
		      	}

		      	//Scroll wheel
		      	$display_map_scroll_wheel = ($owmw_opt["map_disable_zoom_wheel"] == 'yes') ? "false" : "true";

                if ($owmw_opt["wind_unit"] == "m/s") {
    		      	$map_speed = 'ms';
				} else if ($owmw_opt["wind_unit"] == "km/h") {
    		      	$map_speed = 'kmh';
                } else {
    		      	$map_speed = 'mph';
                }

		      	$owmw_html["map"] =
			        '<div id="' . $owmw_html["main_map_div"] . '" class="owmw-map">
			        	<div id="' . esc_attr($owmw_html["container_map_div"]) . '" style="'.owmw_css_height($owmw_opt["map_height"]) .'"></div>
			        </div>';
		      	$owmw_html["map_script"] =
			        'jQuery(document).ready( function() {

				        	var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
							maxZoom: 18, attribution: "OWM Weather" });

							var city = L.OWM.current({intervall: '.esc_attr($owmw_opt["cache_time"]??30).', lang: "en", appId: "'.esc_attr($owmw_opt["api_key"]) . '",temperatureDigits:0,temperatureUnit:"' . esc_attr($owmw_data["temperature_unit_character"]) . '",speedUnit:"' . esc_attr($map_speed) . '"});'.

							$display_map_options .

							'var map = L.map("' . esc_attr($owmw_html["container_map_div"]) . '", { center: new L.LatLng('. esc_attr($owmw_data["latitude"]) .', '. esc_attr($owmw_data["longitude"]) .'), zoom: '. esc_attr($owmw_opt["map_zoom"]) .', layers: [osm], scrollWheelZoom: '.esc_attr($display_map_scroll_wheel).' });

							var baseMaps = { "OSM Standard": osm };

							var overlayMaps = {'.$display_map_layers.'"Cities": city};

							var layerControl = L.control.layers(baseMaps, overlayMaps).addTo(map);

                            map.whenReady(function() {
                               	jQuery( "#' . esc_attr($owmw_html["container_map_div"]) . '").on("invalidSize", function() {
                                    map.invalidateSize();
                            	});
                            });
			        	});';
			}

		$owmw_html["container"]["start"] = '<!-- OWM Weather : WordPress weather plugin v'.OWM_WEATHER_VERSION.' - https://github.com/uwejacobs/owm-weather -->';
		$owmw_html["container"]["start"] .= '<div id="' . $owmw_html["container_weather_div"] . '" class="owmw-'.esc_attr($owmw_opt["id"]).' owm-weather-'.esc_attr($owmw_data["condition_id"]).' owmw-'. $owmw_opt["size"] .' owmw-template-'. $owmw_opt["template"] .'"';
		$owmw_html["container"]["start"] .= ' style="';
		$owmw_html["container"]["start"] .= owmw_css_color('background-color', $owmw_opt["background_color"]) .
                                            owmw_css_background_image($owmw_opt["background_image"]) .
		                                    owmw_css_background_size("cover").
//		                                    owmw_css_background_size($owmw_opt["image_bg_cover_e"]).
//		                                    owmw_css_background_position($owmw_opt["image_bg_position_horizontal_e"], $owmw_opt["image_bg_position_vertical_e"]).
		                                    owmw_css_color("color",$owmw_opt["text_color"]).
		                                    owmw_css_border($owmw_opt["border_color"], $owmw_opt["border_width"],$owmw_opt["border_style"],$owmw_opt["border_radius"]).
		                                    owmw_css_font_family($owmw_opt["font"]);
		$owmw_html["container"]["start"] .= '">';

        // Now
        if ($owmw_opt["current_city_name"] == 'yes' || $owmw_opt["current_weather_symbol"] =='yes' || $owmw_opt["current_temperature"] =='yes' || $owmw_opt["current_feels_like"] =='yes' || $owmw_opt["current_weather_description"] == 'yes')  {
            $owmw_html["now"]["start"]           	= $display_now_start;
            if ($owmw_opt["current_city_name"] == 'yes') {
                $owmw_html["now"]["location_name"]       = $display_now_location_name;
            }
            if ($owmw_opt["current_weather_symbol"] =='yes') {
            	$owmw_html["now"]["symbol"]     = $display_now_symbol;
        	}
            if ($owmw_opt["current_temperature"] =='yes') {
       	        $owmw_html["now"]["temperature"]    = $display_now_temperature;
       	        $owmw_html["now"]["temperature_celsius"]    = $display_now_temperature_celsius;
       	        $owmw_html["now"]["temperature_fahrenheit"]    = $display_now_temperature_fahrenheit;
        	}
            if ($owmw_opt["current_feels_like"] =='yes') {
       	        $owmw_html["now"]["feels_like"]    = $display_now_feels_like;
       	        $owmw_html["now"]["feels_like_celsius"]    = $display_now_feels_like_celsius;
       	        $owmw_html["now"]["feels_like_fahrenheit"]    = $display_now_feels_like_fahrenheit;
        	}
    	    if( $owmw_opt["current_weather_description"] == 'yes' ) {
	        	$owmw_html["now"]["weather_description"] = '<div class="owmw-short-condition">'. esc_html($owmw_data["description"]) .'</div>';
	        }
            $owmw_html["now"]["end"]             	= $display_now_end;
        }

	   	$owmw_html["today"]["start"]     = '<div class="owmw-today row">';
        if( $owmw_opt["today_date_format"] != "none") {
	        $owmw_html["today"]["day"]       = '<div class="owmw-day col"><span class="owmw-highlight">'. esc_html($today_day) .'</span></div>';
	    }
        $owmw_html["today"]["sun"]       = owmw_display_today_sunrise_sunset($owmw_opt["sunrise_sunset"], $owmw_data["sunrise"], $owmw_data["sunset"], $owmw_opt["text_color"], 'span');
        $owmw_html["today"]["sun_hor"]   = owmw_display_today_sunrise_sunset($owmw_opt["sunrise_sunset"], $owmw_data["sunrise"], $owmw_data["sunset"], $owmw_opt["text_color"], 'div');
        $owmw_html["today"]["moon"]      = owmw_display_today_moonrise_moonset($owmw_opt["moonrise_moonset"], $owmw_data["moonrise"], $owmw_data["moonset"], $owmw_opt["text_color"], 'span');
        $owmw_html["today"]["moon_hor"]  = owmw_display_today_moonrise_moonset($owmw_opt["moonrise_moonset"], $owmw_data["moonrise"], $owmw_data["moonset"], $owmw_opt["text_color"],'div');
        $owmw_html["today"]["end"]       = '</div>';

	    if( $owmw_opt["wind"] == 'yes' || $owmw_opt["humidity"] == 'yes' || $owmw_opt["dew_point"] == 'yes' || $owmw_opt["pressure"] == 'yes' || $owmw_opt["cloudiness"] == 'yes' || $owmw_opt["precipitation"] == 'yes' || $owmw_opt["visibility"] == 'yes' || $owmw_opt["uv_index"] == 'yes') {
	    	$owmw_html["info"]["start"] .= '<div class="owmw-infos row">';

	        if( $owmw_opt["wind"] == 'yes' ) {
	        	$owmw_html["info"]["wind"]            = '<div class="owmw-wind col">'.$owmw_html["svg"]["wind"]. esc_html__( 'Wind', 'owm-weather' ) .'<span class="owmw-highlight">'. esc_html($owmw_data["wind_speed"] .' - '.$owmw_data["wind_direction"]).'</span></div>';
	        }

	        if( $owmw_opt["humidity"] == 'yes' ) {
	        	$owmw_html["info"]["humidity"]        = '<div class="owmw-humidity col">'.$owmw_html["svg"]["humidity"]. esc_html__( 'Humidity', 'owm-weather' ) .'<span class="owmw-highlight">'. esc_html($owmw_data["humidity"]) .'</span></div>';
	        }

	        if( $owmw_opt["dew_point"] == 'yes' ) {
	        	$owmw_html["info"]["dew_point"]       = '<div class="owmw-dew-point col">'.$owmw_html["svg"]["dew_point"]. esc_html__( 'Dew Point', 'owm-weather' ) .'<span class="owmw-highlight owmw-temperature">'. esc_html($owmw_data["dew_point"]) .'</span></div>';
	        	$owmw_html["info"]["dew_point_celsius"]       = '<div class="owmw-dew-point col">'.$owmw_html["svg"]["dew_point"]. esc_html__( 'Dew Point', 'owm-weather' ) .'<span class="owmw-highlight owmw-temperature">'. esc_html($owmw_data["dew_point_celsius"]) .'</span></div>';
	        	$owmw_html["info"]["dew_point_fahrenheit"]       = '<div class="owmw-dew-point col">'.$owmw_html["svg"]["dew_point"]. esc_html__( 'Dew Point', 'owm-weather' ) .'<span class="owmw-highlight owmw-temperature">'. esc_html($owmw_data["dew_point_fahrenheit"]) .'</span></div>';
	        }

	        if( $owmw_opt["pressure"]  == 'yes') {
	        	$owmw_html["info"]["pressure"]        = '<div class="owmw-pressure col">'.$owmw_html["svg"]["pressure"]. esc_html__( 'Pressure', 'owm-weather' ) .'<span class="owmw-highlight">'. esc_html($owmw_data["pressure"]) .'</span></div>';
	        }

	        if( $owmw_opt["cloudiness"] == 'yes' ) {
	        	$owmw_html["info"]["cloudiness"]      = '<div class="owmw-cloudiness col">'.$owmw_html["svg"]["cloudiness"]. esc_html__( 'Cloudiness', 'owm-weather' ) .'<span class="owmw-highlight">'. esc_html($owmw_data["cloudiness"]) .'</span></div>';
	        }

	        if( $owmw_opt["precipitation"] == 'yes' ) {
	        	$owmw_html["info"]["precipitation"]   = '<div class="owmw-precipitation col">'.$owmw_html["svg"]["precipitation"]. esc_html__( 'Precipitation', 'owm-weather' ) .'<span class="owmw-highlight">'. esc_html($owmw_data["precipitation_3h"]) .'</span></div>';
	        }

	        if( $owmw_opt["visibility"] == 'yes' ) {
	        	$owmw_html["info"]["visibility"]     = '<div class="owmw-visibility col">'.$owmw_html["svg"]["visibility"]. esc_html__( 'Visibility', 'owm-weather' ) .'<span class="owmw-highlight">'. esc_html($owmw_data["visibility"]) .'</span></div>';
	        }

	        if( $owmw_opt["uv_index"] == 'yes' ) {
	        	$owmw_html["info"]["uv_index"]       = '<div class="owmw-uv-index col">'.$owmw_html["svg"]["uv_index"]. esc_html__( 'UV Index', 'owm-weather' ) .'<span class="owmw-highlight">'. esc_html($owmw_data["uv_index"]) .'</span></div>';
	        }

	        $owmw_html["info"]["end"] .= '</div>';
	    };

	    if($owmw_opt["hours_forecast_no"] > 0) {
	    	$owmw_html["hour"]["start"] = '<div class="owmw-hours card-column" style="'.owmw_css_color('border-color', $owmw_opt["border_color"]).'">';
	        $owmw_html["hour"]["info"]  = $display_hours;
	        $owmw_html["hour"]["end"]   = '</div>';

            for ($i=1; $i<=12; $i++) {
                $owmw_html["hour"]["icon".$i] = owmw_hour_icon($i, $owmw_opt["text_color"]);
            }
	    }

	    if ($owmw_opt["days_forecast_no"] > 0) {
	    	$owmw_html["forecast"]["start"] = '<div class="owmw-forecast d-flex flex-column justify-content-center">';
	        $owmw_html["forecast"]["info"]  = $display_forecast;
	        $owmw_html["forecast"]["end"]   = '</div>';
	    	$owmw_html["forecast"]["start_card"] = '<div class="owmw-forecast card-column">';
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
    	    require_once dirname( __FILE__ ) . '/owmweather-color-css.php';

   	        $owmw_html["custom_css"] .= owmw_generateColorCSS($owmw_opt["alerts_button_color"] ?? '#000', "owmw-alert-" . esc_attr($owmw_opt["id"]));
            $owmw_html["alert_button"] .= '<div class="owmw-alert-buttons text-center">';
            foreach($owmw_data["alerts"] as $key => $value) {
                $modal = owmw_unique_id_esc('owmw-modal-'.esc_attr($owmw_opt["id"]));
                $owmw_html["alert_button"] .= '<button class="owmw-alert-button btn btn-outline-owmw-alert-' . esc_attr($owmw_opt["id"]) . ' m-1" data-toggle="modal" data-target="#' . esc_attr($modal) . '">' . esc_html($value["event"]) . '</button>';
                $owmw_html["alert_modal"] .=
                    '<div class="modal fade" id="' . esc_attr($modal) . '" tabindex="-1" role="dialog" aria-labelledby="' . esc_attr($modal) . '-label" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content" style="' .
                        		owmw_css_color('background-color', $owmw_opt["background_color"]) . owmw_css_color("color",$owmw_opt["text_color"]) . '">
                          <div class="modal-header">
                            <h5 class="modal-title" id="' . esc_attr($modal) . '-label">' . esc_html($value["event"]) . '</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p>' . esc_html($value["sender"]) . '<br>' . esc_html($value["start"]) . ' until ' . esc_html($value["end"]) .'</p>
                            <p>' . nl2br($value["text"]) . '</p></div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>';
            }
            $owmw_html["alert_button"] .= '</div>';
	    }

    	$owmw_html["temperature_unit"] = owmw_temperatureUnitSymbol($owmw_html["container_weather_div"], $owmw_opt["display_temperature_unit"], $owmw_opt["temperature_unit"], $owmw_opt["iconpack"]);
    	$owmw_html["temperature_unit"] .= owmw_temperatureUnitSymbol($owmw_html["container_weather_div"], $owmw_opt["display_temperature_unit"], "metric", $owmw_opt["iconpack"], "-celsius");
    	$owmw_html["temperature_unit"] .= owmw_temperatureUnitSymbol($owmw_html["container_weather_div"], $owmw_opt["display_temperature_unit"], "imperial", $owmw_opt["iconpack"], "-fahrenheit");

        if ($owmw_opt["owm_link"] == 'yes' || $owmw_opt["last_update"] == 'yes') {
	    	$owmw_html["owm_link_last_update_start"] .= '<div class="owmw-owm-link-last-update clearfix">';
	    	$owmw_html["owm_link_last_update_end"] .= '</div>';
        }

	    if ($owmw_opt["owm_link"] == 'yes') {
	    	$owmw_html["owm_link"] = '<div class="owmw-link-owm"><a rel="noopener" href="' . esc_url($owmw_data["owm_link"]) . '" target="_blank" title="'.esc_attr__('Full weather on OpenWeatherMap','owm-weather').'">'.esc_html__('Full weather','owm-weather').'</a></div>';
	    }
	    if ($owmw_opt["last_update"] == 'yes') {
	    	$owmw_html["last_update"] = '<div class="owmw-last-update">'.esc_html($owmw_data["last_update"]).'</div>';
	    }

        //charts
        //hourly
        $chart_id = owmw_unique_id_esc($owmw_opt["id"], '_');
        $owmw_html["chart"]["hourly"] = [];
        $owmw_html["container_chart_hourly_div"] = 'owmw-hourly-chart-canvas-'.esc_attr($chart_id);
        $owmw_html["chart"]["hourly"]["labels"] = '';
        $owmw_html["chart"]["hourly"]["dataset_temperature"] = '';
        $owmw_html["chart"]["hourly"]["dataset_feels_like"] = '';
        $owmw_html["chart"]["hourly"]["dataset_dew_point"] = '';
        $owmw_html["chart"]["hourly"]["config"] = '';
        $owmw_html["chart"]["hourly"]["data"] = '';
        $owmw_html["chart"]["hourly"]["options"] = '';
        $owmw_html["chart"]["hourly"]["chart"] = '';
        $owmw_html["chart"]["hourly"]["cmd"] = '';
        $owmw_html["chart"]["hourly"]["container"] = '';
        $owmw_html["chart"]["hourly"]["script"] = '';

	    if ($owmw_opt["hours_forecast_no"] > 0) {
            $owmw_html["chart"]["hourly"]["labels"] .= 'const hourly_labels_'.esc_attr($chart_id).' = [';
            $owmw_html["chart"]["hourly"]["dataset_temperature"] .= 'const hourly_temperature_dataset_'.esc_attr($chart_id).' = [';
            $owmw_html["chart"]["hourly"]["dataset_feels_like"] .= 'const hourly_feels_like_dataset_'.esc_attr($chart_id).' = [';
            $owmw_html["chart"]["hourly"]["dataset_dew_point"] .= 'const hourly_dew_point_dataset_'.esc_attr($chart_id).' = [';
			$cnt = 0;
			foreach ($owmw_data["hourly"] as $i => $value) {
				if ($cnt < $owmw_opt["hours_forecast_no"]) {
					$owmw_html["chart"]["hourly"]["labels"] .= '"' . esc_attr($value["time"] != 'Now' ? date('D', $value["timestamp"]) . ' ' : '') . $value["time"] . '",';
					$owmw_html["chart"]["hourly"]["dataset_temperature"] .= '"' . esc_html($value["temperature"]) . '",';
					$owmw_html["chart"]["hourly"]["dataset_feels_like"] .= '"' . esc_html($value["feels_like"]) . '",';
					$owmw_html["chart"]["hourly"]["dataset_dew_point"] .= '"' . esc_html($value["dew_point"]) . '",';
					++$cnt;
				}
			}
            $owmw_html["chart"]["hourly"]["labels"] .= '];';
            $owmw_html["chart"]["hourly"]["dataset_temperature"] .= '];';
            $owmw_html["chart"]["hourly"]["dataset_feels_like"] .= '];';
            $owmw_html["chart"]["hourly"]["dataset_dew_point"] .= '];';

            $owmw_html["chart"]["hourly"]["config"] .= 'const hourly_config_'.esc_attr($chart_id).' = { type: "line", options: hourly_options_'.esc_attr($chart_id).', data: hourly_data_'.esc_attr($chart_id).',};';
            $owmw_html["chart"]["hourly"]["options"] .= 'const hourly_options_'.esc_attr($chart_id).' = {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { intersect: false, mode: "index" },
                    plugins: {title: {display: true, text: "Hourly Temperatures" },
                        tooltip: { callbacks: { label: function(context) { var label = context.dataset.label || ""; if (label) { label += ": "; }
                        if (context.parsed.y !== null) { label += context.parsed.y + " '.esc_html($owmw_data["temperature_unit_character"]).'"; }
                        return label; } } } },
                    scales: { y: { title: { display: true, text: "'.esc_html($owmw_data["temperature_unit_text"]).'" } } }
                    };';
            $owmw_html["chart"]["hourly"]["data"] .= 'const hourly_data_'.esc_attr($chart_id).' = {
                                                                      labels: hourly_labels_'.esc_attr($chart_id).',
                                                                      datasets: [{
                                                                        label: "Temperature",
                                                                        data: hourly_temperature_dataset_'.esc_attr($chart_id).',
                                                                        fill: false,
                                                                        borderColor: "'.esc_attr($owmw_opt["chart_temperature_color"]).'",
                                                                        borderWidth: 1,
                                                                        tension: 0.3,
                                                                        pointBorderWidth: 0,
                                                                        },{
                                                                        label: "Feels Like",
                                                                        data: hourly_feels_like_dataset_'.esc_attr($chart_id).',
                                                                        fill: false,
                                                                        borderColor: "'.esc_attr($owmw_opt["chart_feels_like_color"]).'",
                                                                        borderWidth: 1,
                                                                        tension: 0.3,
                                                                        pointBorderWidth: 0,
                                                                        },{
                                                                        label: "Dew Point",
                                                                        data: hourly_dew_point_dataset_'.esc_attr($chart_id).',
                                                                        fill: false,
                                                                        borderColor: "'.esc_attr($owmw_opt["chart_dew_point_color"]).'",
                                                                        borderWidth: 1,
                                                                        tension: 0.3,
                                                                        pointBorderWidth: 0,
                                                                      }]
                                                                    };';
            $owmw_html["chart"]["hourly"]["chart"] .= 'jQuery(document).ready(function(){
                                                        var ctx = jQuery("#'.esc_attr($owmw_html["container_chart_hourly_div"]).'");
                                                        var hourlyChart = new Chart(ctx, hourly_config_'.esc_attr($chart_id).');
                                                        });';

$owmw_html["chart"]["hourly"]["container"] =
        '<div class="owmw-chart-container" style="position: relative; height:'.esc_attr($owmw_opt["chart_height"]).'px; width:100%">
        <canvas id="'.$owmw_html["container_chart_hourly_div"].'" style="'.owmw_css_color('background-color', $owmw_opt["chart_background_color"]).owmw_css_border($owmw_opt["chart_border_color"],$owmw_opt["chart_border_width"],$owmw_opt["chart_border_style"],$owmw_opt["chart_border_radius"]).'" aria-label="Hourly Temperatures" role="img"></canvas></div>';
$owmw_html["chart"]["hourly"]["script"] = 
        $owmw_html["chart"]["hourly"]["labels"] .
        $owmw_html["chart"]["hourly"]["dataset_temperature"] .
        $owmw_html["chart"]["hourly"]["dataset_feels_like"] .
        $owmw_html["chart"]["hourly"]["dataset_dew_point"] .
        $owmw_html["chart"]["hourly"]["data"] .
        $owmw_html["chart"]["hourly"]["options"] .
        $owmw_html["chart"]["hourly"]["config"] .
        $owmw_html["chart"]["hourly"]["chart"];
		}
        
		//daily
        $chart_id = owmw_unique_id_esc($owmw_opt["id"], '_');
        $owmw_html["chart"]["daily"] = [];
        $owmw_html["container_chart_daily_div"] = 'owmw-daily-chart-canvas-'.esc_attr($chart_id);
        $owmw_html["chart"]["daily"]["labels"] = '';
        $owmw_html["chart"]["daily"]["dataset_temperature"] = '';
        $owmw_html["chart"]["daily"]["dataset_feels_like"] = '';
        $owmw_html["chart"]["daily"]["config"] = '';
        $owmw_html["chart"]["daily"]["data"] = '';
        $owmw_html["chart"]["daily"]["options"] = '';
        $owmw_html["chart"]["daily"]["chart"] = '';
        $owmw_html["chart"]["daily"]["cmd"] = '';
        $owmw_html["chart"]["daily"]["container"] = '';
        $owmw_html["chart"]["daily"]["script"] = '';

	    if ($owmw_opt["days_forecast_no"] > 0) {
            $owmw_html["chart"]["daily"]["labels"] .= 'const daily_labels_'.esc_attr($chart_id).' = [';
            $owmw_html["chart"]["daily"]["dataset_temperature"] .= 'const daily_temperature_dataset_'.esc_attr($chart_id).' = [';
            $owmw_html["chart"]["daily"]["dataset_feels_like"] .= 'const daily_feels_like_dataset_'.esc_attr($chart_id).' = [';
			$cnt = 0;
			foreach ($owmw_data["daily"] as $i => $value) {
				if ($cnt < $owmw_opt["days_forecast_no"]) {
					$owmw_html["chart"]["daily"]["labels"] .= '"","' . esc_attr($value["day"]) . '","","",';
					$owmw_html["chart"]["daily"]["dataset_temperature"] .= '"' . esc_attr($value["temperature_morning"]) . '","' . esc_attr($value["temperature_day"]) . '","' . esc_attr($value["temperature_evening"]) . '","' . esc_attr($value["temperature_night"]) . '",';
					$owmw_html["chart"]["daily"]["dataset_feels_like"] .= '"' . esc_attr($value["feels_like_morning"]) . '","' . esc_attr($value["feels_like_day"]) . '","' . esc_attr($value["feels_like_evening"]) . '","' . esc_attr($value["feels_like_night"]) . '",';
					++$cnt;
				}
			}
            $owmw_html["chart"]["daily"]["labels"] .= '];';
            $owmw_html["chart"]["daily"]["dataset_temperature"] .= '];';
            $owmw_html["chart"]["daily"]["dataset_feels_like"] .= '];';

            $owmw_html["chart"]["daily"]["config"] .= 'const daily_config_'.esc_attr($chart_id).' = { type: "line", options: daily_options_'.esc_attr($chart_id).', data: daily_data_'.esc_attr($chart_id).',};';
            $owmw_html["chart"]["daily"]["options"] .= 'const daily_options_'.esc_attr($chart_id).' = {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { intersect: false, mode: "index" },
                    plugins: {title: {display: true, text: "Daily Temperatures" },
                        tooltip: { callbacks: { label: function(context) { var label = context.dataset.label || ""; if (label) { label += ": "; }
                        if (context.parsed.y !== null) { label += context.parsed.y + " '.esc_attr($owmw_data["temperature_unit_character"]).'"; }
                        return label; } } } },
                    scales: { y: { title: { display: true, text: "'.esc_attr($owmw_data["temperature_unit_text"]).'" } } }
                    };';
            $owmw_html["chart"]["daily"]["data"] .= 'const daily_data_'.esc_attr($chart_id).' = {
                                                                      labels: daily_labels_'.esc_attr($chart_id).',
                                                                      datasets: [{
                                                                        label: "Temperature",
                                                                        data: daily_temperature_dataset_'.esc_attr($chart_id).',
                                                                        fill: false,
                                                                        borderColor: "'.esc_attr($owmw_opt["chart_temperature_color"]).'",
                                                                        borderWidth: 1,
                                                                        tension: 0.3,
                                                                        pointBorderWidth: 0,
                                                                        },{
                                                                        label: "Feels Like",
                                                                        data: daily_feels_like_dataset_'.esc_attr($chart_id).',
                                                                        fill: false,
                                                                        borderColor: "'.esc_attr($owmw_opt["chart_feels_like_color"]).'",
                                                                        borderWidth: 1,
                                                                        tension: 0.3,
                                                                        pointBorderWidth: 0,
                                                                      }]
                                                                    };';
            $owmw_html["chart"]["daily"]["chart"] .= 'jQuery(document).ready(function(){
                                                        var ctx = jQuery("#'.$owmw_html["container_chart_daily_div"].'");
                                                        var dailyChart = new Chart(ctx, daily_config_'.esc_attr($chart_id).');
                                                        });';

$owmw_html["chart"]["daily"]["container"] =
        '<div class="owmw-chart-container" style="position: relative; height:'.esc_attr($owmw_opt["chart_height"]).'px; width:100%">
        <canvas id="'.$owmw_html["container_chart_daily_div"].'" style="'.owmw_css_color('background-color', $owmw_opt["chart_background_color"]).owmw_css_border($owmw_opt["chart_border_color"],$owmw_opt["chart_border_width"],$owmw_opt["chart_border_style"],$owmw_opt["chart_border_radius"]).'" aria-label="Daily Temperatures" role="img"></canvas></div>';
$owmw_html["chart"]["daily"]["script"] =
        $owmw_html["chart"]["daily"]["labels"] .
        $owmw_html["chart"]["daily"]["dataset_temperature"] .
        $owmw_html["chart"]["daily"]["dataset_feels_like"] .
        $owmw_html["chart"]["daily"]["data"] .
        $owmw_html["chart"]["daily"]["options"] .
        $owmw_html["chart"]["daily"]["config"] .
        $owmw_html["chart"]["daily"]["chart"];
		}


        //Table
        if (!empty($owmw_opt["table_border_color"])) {
            $owmw_html["custom_css"] .= '.owmw-table.table-bordered > tbody > tr > td, .owmw-table .table-bordered > tbody > tr > th, .owmw-table.table-bordered > tfoot > tr > td, .owmw-table.table-bordered > tfoot > tr > th, .owmw-table.table-bordered > thead > tr > td, .owmw-table.table-bordered > thead > tr > th { ' . owmw_css_border($owmw_opt["table_border_color"], $owmw_opt["table_border_width"],$owmw_opt["table_border_style"],$owmw_opt["table_border_radius"]) .'}';
        }
        //Hourly
	    if ($owmw_opt["hours_forecast_no"] > 0) {
	        $owmw_html["container_table_hourly_div"] = owmw_unique_id_esc('owmw-table_hourly-container-'.esc_attr($owmw_opt["id"]));
            $owmw_html["table"]["hourly"] = '<div class="table-responsive owmw-table owmw-table-hours"><table class="table table-sm table-bordered" style="'.owmw_css_color('background-color', $owmw_opt["table_background_color"]).owmw_css_color("color",$owmw_opt["table_text_color"]).
		                                    owmw_css_border($owmw_opt["table_border_color"],$owmw_opt["table_border_width"],$owmw_opt["table_border_style"], $owmw_opt["table_border_radius"]).'">';
            $owmw_html["table"]["hourly"] .= '<thead><tr>';
            $owmw_html["table"]["hourly"] .= '<th>'.esc_html__('Time', 'owm-weather').'</th>';
            $owmw_html["table"]["hourly"] .= '<th colspan="2">'.esc_html__('Conditions', 'owm-weather').'</th>';
            $owmw_html["table"]["hourly"] .= '<th>'.esc_html__('Temperature', 'owm-weather').'</th>';
            $owmw_html["table"]["hourly"] .= '<th>'.esc_html__('Feels Like', 'owm-weather').'</th>';
			if ($owmw_opt["precipitation"] == 'yes') {
				$owmw_html["table"]["hourly"] .= '<th>'.esc_html__('Precipitation', 'owm-weather').'</th>';
				$owmw_html["table"]["hourly"] .= '<th>'.esc_html__('Amount', 'owm-weather').'</th>';
			}
			if ($owmw_opt["cloudiness"] == 'yes') {
				$owmw_html["table"]["hourly"] .= '<th>'.esc_html__('Cloud Cover', 'owm-weather').'</th>';
			}
			if ($owmw_opt["dew_point"] == 'yes') {
				$owmw_html["table"]["hourly"] .= '<th>'.esc_html__('Dew Point', 'owm-weather').'</th>';
			}
			if ($owmw_opt["humidity"] == 'yes') {
				$owmw_html["table"]["hourly"] .= '<th>'.esc_html__('Humidity', 'owm-weather').'</th>';
			}
			if ($owmw_opt["wind"] == 'yes') {
				$owmw_html["table"]["hourly"] .= '<th>'.esc_html__('Wind', 'owm-weather').'</th>';
				$owmw_html["table"]["hourly"] .= '<th>'.esc_html__('Gust', 'owm-weather').'</th>';
			}
			if ($owmw_opt["pressure"] == 'yes') {
				$owmw_html["table"]["hourly"] .= '<th>'.esc_html__('Pressure', 'owm-weather').'</th>';
			}
			if ($owmw_opt["visibility"] == 'yes') {
				$owmw_html["table"]["hourly"] .= '<th>'.esc_html__('Visibility', 'owm-weather').'</th>';
			}
			if ($owmw_opt["uv_index"] == 'yes') {
				$owmw_html["table"]["hourly"] .= '<th>'.esc_html__('UV Index', 'owm-weather').'</th>';
			}
            $owmw_html["table"]["hourly"] .= '</tr></thead>';
            $owmw_html["table"]["hourly"] .= '<tbody>';
			$cnt = 0;
			foreach ($owmw_data["hourly"] as $i => $value) {
				if ($cnt < $owmw_opt["hours_forecast_no"]) {
					$owmw_html["table"]["hourly"] .= '<tr>';
					$owmw_html["table"]["hourly"] .= '<td>' . date('D', $value["timestamp"]) . ($owmw_opt["hours_time_icons"] == 'yes' ? owmw_hour_icon($value["time"], $owmw_opt["table_text_color"]) : '<br>' . esc_html($value["time"])) . '</td>';
					$owmw_html["table"]["hourly"] .= '<td class="border-right-0">' . owmw_weatherIcon($owmw_opt["iconpack"], $value["condition_id"], $value["day_night"], $value["description"]) . '</td><td class="border-left-0 small">' . esc_html($value["description"]) . '</td>';
					$owmw_html["table"]["hourly"] .= '<td class="owmw-temperature">' . esc_html($value["temperature"]) . '</td>';
					$owmw_html["table"]["hourly"] .= '<td class="owmw-temperature">' . esc_html($value["feels_like"]) . '</td>';
					if ($owmw_opt["precipitation"] == 'yes') {
						$owmw_html["table"]["hourly"] .= '<td>' . esc_html($value["rain_chance"]) . '</td>';
						$owmw_html["table"]["hourly"] .= '<td>' . esc_html($value["precipitation"]) . '</td>';
					}
					if ($owmw_opt["cloudiness"] == 'yes') {
						$owmw_html["table"]["hourly"] .= '<td>' . esc_html($value["cloudiness"]) . '</td>';
					}
					if ($owmw_opt["dew_point"] == 'yes') {
						$owmw_html["table"]["hourly"] .= '<td class="owmw-temperature">' . esc_html($value["dew_point"]) . '</td>';
					}
					if ($owmw_opt["humidity"] == 'yes') {
						$owmw_html["table"]["hourly"] .= '<td>' . esc_html($value["humidity"]) . '</td>';
					}
					if ($owmw_opt["wind"] == 'yes') {
						$owmw_html["table"]["hourly"] .= '<td>' . esc_html($value["wind_speed"] .' '. $value["wind_direction"]) . '</td>';
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

        //daily
	    if ($owmw_opt["days_forecast_no"] > 0) {
	        $owmw_html["container_table_daily_div"] = owmw_unique_id_esc('owmw-table_daily-container-'.esc_attr($owmw_opt["id"]));
            $owmw_html["table"]["daily"] = '<div class="table-responsive owmw-table owmw-table-hours"><table class="table table-sm table-bordered" style="'.owmw_css_color('background-color', $owmw_opt["table_background_color"]).owmw_css_color("color",$owmw_opt["table_text_color"]).
		                                    owmw_css_border($owmw_opt["table_border_color"],$owmw_opt["table_border_width"],$owmw_opt["table_border_style"], $owmw_opt["table_border_radius"]).'">';
            $owmw_html["table"]["daily"] .= '<thead><tr>';
            $owmw_html["table"]["daily"] .= '<th>'.esc_html__('Day', 'owm-weather').'</th>';
            $owmw_html["table"]["daily"] .= '<th colspan="2">'.esc_html__('Conditions', 'owm-weather').'</th>';
            $owmw_html["table"]["daily"] .= '<th>'.esc_html__('Min Temperature', 'owm-weather').'</th>';
			$owmw_html["table"]["daily"] .= '<th>'.esc_html__('Max Temperature', 'owm-weather').'</th>';
			if ($owmw_opt["precipitation"] == 'yes') {
				$owmw_html["table"]["daily"] .= '<th>'.esc_html__('Rain Chance', 'owm-weather').'</th>';
				$owmw_html["table"]["daily"] .= '<th>'.esc_html__('Precipitation', 'owm-weather').'</th>';
			}
			if ($owmw_opt["cloudiness"] == 'yes') {
				$owmw_html["table"]["daily"] .= '<th>'.esc_html__('Cloud Cover', 'owm-weather').'</th>';
			}
			if ($owmw_opt["dew_point"] == 'yes') {
				$owmw_html["table"]["daily"] .= '<th>'.esc_html__('Dew Point', 'owm-weather').'</th>';
			}
			if ($owmw_opt["humidity"] == 'yes') {
				$owmw_html["table"]["daily"] .= '<th>'.esc_html__('Humidity', 'owm-weather').'</th>';
			}
			if ($owmw_opt["wind"] == 'yes') {
				$owmw_html["table"]["daily"] .= '<th>'.esc_html__('Wind', 'owm-weather').'</th>';
				$owmw_html["table"]["daily"] .= '<th>'.esc_html__('Gust', 'owm-weather').'</th>';
			}
			if ($owmw_opt["pressure"] == 'yes') {
				$owmw_html["table"]["daily"] .= '<th>'.esc_html__('Pressure', 'owm-weather').'</th>';
			}
			if ($owmw_opt["uv_index"] == 'yes') {
				$owmw_html["table"]["daily"] .= '<th>'.esc_html__('UV Index', 'owm-weather').'</th>';
			}
            $owmw_html["table"]["daily"] .= '</tr></thead>';
            $owmw_html["table"]["daily"] .= '<tbody>';
			$cnt = 0;
			foreach ($owmw_data["daily"] as $i => $value) {
				if ($cnt < $owmw_opt["days_forecast_no"]) {
					$owmw_html["table"]["daily"] .= '<tr>';
					$owmw_html["table"]["daily"] .= '<td>' . esc_html($value["day"]) . '</td>';
					$owmw_html["table"]["daily"] .= '<td class="border-right-0">' . owmw_weatherIcon($owmw_opt["iconpack"], $value["condition_id"], "day", $value["description"]) . '</td><td class="border-left-0 small">' . esc_html($value["description"]) . '</td>';
					$owmw_html["table"]["daily"] .= '<td class="owmw-temperature">' . esc_html($value["temperature_minimum"]) . '</td>';
					$owmw_html["table"]["daily"] .= '<td class="owmw-temperature">' . esc_html($value["temperature_maximum"]) . '</td>';
					if ($owmw_opt["precipitation"] == 'yes') {
						$owmw_html["table"]["daily"] .= '<td>' . esc_html($value["rain_chance"]) . '</td>';
						$owmw_html["table"]["daily"] .= '<td>' . esc_html($value["precipitation"]) . '</td>';
					}
					if ($owmw_opt["cloudiness"] == 'yes') {
						$owmw_html["table"]["daily"] .= '<td>' . esc_html($value["cloudiness"]) . '</td>';
					}
					if ($owmw_opt["dew_point"] == 'yes') {
						$owmw_html["table"]["daily"] .= '<td class="owmw-temperature">' . esc_html($value["dew_point"]) . '</td>';
					}
					if ($owmw_opt["humidity"] == 'yes') {
						$owmw_html["table"]["daily"] .= '<td>' . esc_html($value["humidity"]) . '</td>';
					}
					if ($owmw_opt["wind"] == 'yes') {
						$owmw_html["table"]["daily"] .= '<td>' . esc_html($value["wind_speed"]) .' '. $value["wind_direction"] . '</td>';
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
//	    if ($owmw_opt["hours_forecast_no"] > 0) {
	        $owmw_html["container_table_forecast_div"] = owmw_unique_id_esc('owmw-table_forecast-container-'.esc_attr($owmw_opt["id"]));
            $owmw_html["table"]["forecast"] = '<div class="table-responsive owmw-table owmw-table-hours"><table class="table table-sm table-bordered" style="'.owmw_css_color('background-color', $owmw_opt["table_background_color"]).owmw_css_color("color",$owmw_opt["table_text_color"]).
		                                    owmw_css_border($owmw_opt["table_border_color"],$owmw_opt["table_border_width"],$owmw_opt["table_border_style"], $owmw_opt["table_border_radius"]).'">';
            $owmw_html["table"]["forecast"] .= '<thead><tr>';
            $owmw_html["table"]["forecast"] .= '<th>'.esc_html__('Time', 'owm-weather').'</th>';
            $owmw_html["table"]["forecast"] .= '<th colspan="2">'.esc_html__('Conditions', 'owm-weather').'</th>';
            $owmw_html["table"]["forecast"] .= '<th>'.esc_html__('Temperature', 'owm-weather').'</th>';
            $owmw_html["table"]["forecast"] .= '<th>'.esc_html__('Feels Like', 'owm-weather').'</th>';
			if ($owmw_opt["precipitation"] == 'yes') {
				$owmw_html["table"]["forecast"] .= '<th>'.esc_html__('Precipitation', 'owm-weather').'</th>';
				$owmw_html["table"]["forecast"] .= '<th>'.esc_html__('Cloud Cover', 'owm-weather').'</th>';
			}
			if ($owmw_opt["humidity"] == 'yes') {
				$owmw_html["table"]["forecast"] .= '<th>'.esc_html__('Humidity', 'owm-weather').'</th>';
			}
			if ($owmw_opt["wind"] == 'yes') {
				$owmw_html["table"]["forecast"] .= '<th>'.esc_html__('Wind', 'owm-weather').'</th>';
				$owmw_html["table"]["forecast"] .= '<th>'.esc_html__('Gust', 'owm-weather').'</th>';
			}
			if ($owmw_opt["pressure"] == 'yes') {
				$owmw_html["table"]["forecast"] .= '<th>'.esc_html__('Pressure', 'owm-weather').'</th>';
			}
			if ($owmw_opt["visibility"] == 'yes') {
				$owmw_html["table"]["forecast"] .= '<th>'.esc_html__('Visibility', 'owm-weather').'</th>';
			}
            $owmw_html["table"]["forecast"] .= '</tr></thead>';
            $owmw_html["table"]["forecast"] .= '<tbody>';
			$cnt = 0;
			foreach ($owmw_data["forecast"] as $i => $value) {
//				if ($cnt < $owmw_opt["hours_forecast_no"]) {
				if (!in_array($i, array("temperature_minimum", "temperature_minimum_celsius", "temperature_minimum_fahrenheit", "temperature_maximum", "temperature_maximum_celsius", "temperature_maximum_fahrenheit"))) {
					$owmw_html["table"]["forecast"] .= '<tr>';
					$owmw_html["table"]["forecast"] .= '<td>' . date('D', $value["timestamp"]) . ($owmw_opt["hours_time_icons"] == 'yes' ? owmw_hour_icon($value["time"], $owmw_opt["table_text_color"]) : '<br>' . esc_html($value["time"])) . '</td>';
					$owmw_html["table"]["forecast"] .= '<td class="border-right-0">' . owmw_weatherIcon($owmw_opt["iconpack"], $value["condition_id"], $value["day_night"], $value["description"]) . '</td><td class="border-left-0 small">' . esc_html($value["description"]) . '</td>';
					$owmw_html["table"]["forecast"] .= '<td class="owmw-temperature">' . esc_html($value["temperature"]) . '</td>';
					$owmw_html["table"]["forecast"] .= '<td class="owmw-temperature">' . esc_html($value["feels_like"]) . '</td>';
					if ($owmw_opt["precipitation"] == 'yes') {
						$owmw_html["table"]["forecast"] .= '<td>' . esc_html($value["rain_chance"]) . '</td>';
						$owmw_html["table"]["forecast"] .= '<td>' . esc_html($value["cloudiness"]) . '</td>';
					}
					if ($owmw_opt["humidity"] == 'yes') {
						$owmw_html["table"]["forecast"] .= '<td>' . esc_html($value["humidity"]) . '</td>';
					}
					if ($owmw_opt["wind"] == 'yes') {
						$owmw_html["table"]["forecast"] .= '<td>' . esc_html($value["wind_speed"] .' '. $value["wind_direction"]) . '</td>';
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
            $owmw_html["table"]["forecast"] .= '</tbody>';
            $owmw_html["table"]["forecast"] .= '</table>';
            $owmw_html["table"]["hourly"] .= '</div>';
//		}

	    $owmw_html["container"]["end"] .= '</div>';
	    owmw_deleteWhitespaces($owmw_html);

        if ($owmw_opt["template"] == "debug") {
            $owmw_sys_opt = get_option('owmw_option_name');
        }

        owmw_sanitize_api_response($owmw_data);

        $owmw_opt['allowed_html'] = array_merge(wp_kses_allowed_html('post'),
                                               array('svg' => array('class' => true, 'style' => true, 'viewbox' => true, 'transform' => true),
                                               'clippath' => array('id' => true, 'd' => true, 'class' => true),
                                               'path' => array('d'=>true, 'class' => true),
                                               'g' => array('class' => true, 'clip-path' => true),
                                               'circle' => array('class' => true, 'fill' => true, 'cx' => true, 'cy' => true, 'r' => true),
                                               'rect' => array('class' => true, 'x' => true, 'y' => true, 'width' => true, 'height' => true),
                                               'polygon' => array('class' => true, 'points' => true),
                                               'metadata' => array(),
                                               'canvas' => array('id' => true, 'style' => true, 'aria-label' => true, 'role' => true)
				       ));

        add_filter( 'safe_style_css', function( $styles ) {
            $styles[] = 'fill';
            return $styles;
        } );


    	ob_start();
	    if ( locate_template('owm-weather/content-owmweather.php', false) != '' && $owmw_opt["template"] == 'Default' ) {
	    	include get_stylesheet_directory() . '/owm-weather/content-owmweather.php';
	    } elseif ( $owmw_opt["template"] != 'Default' ) {
	    	if ( locate_template('owm-weather/content-owmweather-' . $owmw_opt["template"] . '.php', false) != '' ) {
		    	include get_stylesheet_directory() . '/owm-weather/content-owmweather-' . $owmw_opt["template"] . '.php';
	    	} else {
	    		include dirname( __FILE__ ) . '/template/content-owmweather-' . $owmw_opt["template"] . '.php';
	    	}
	    } else { //Default
	    	include ( dirname( __FILE__ ) . '/template/content-owmweather.php');
	    }
    	$owmw_html["html"] = ob_get_clean();

	  	$response = array();
	  	$response['weather'] = $owmw_params["weather_id"];
	  	$response['html'] = $owmw_html["html"];
		wp_send_json_success($response);
	}
}
add_action( 'wp_ajax_owmw_get_my_weather', 'owmw_get_my_weather' );
add_action( 'wp_ajax_nopriv_owmw_get_my_weather', 'owmw_get_my_weather' );

///////////////////////////////////////////////////////////////////////////////////////////////////
//Fix shortcode bug in widget text
///////////////////////////////////////////////////////////////////////////////////////////////////
add_filter( 'widget_text', 'shortcode_unautop');
add_filter( 'widget_text', 'do_shortcode', 11);

///////////////////////////////////////////////////////////////////////////////////////////////////
//Display shortcode in listing view
///////////////////////////////////////////////////////////////////////////////////////////////////
add_filter('manage_edit-owm-weather_columns', 'owmw_set_custom_edit_owm_weather_columns');
add_action('manage_owm-weather_posts_custom_column', 'owmw_custom_owm_weather_column', 10, 2);

function owmw_set_custom_edit_owm_weather_columns($columns) {
    $columns['owm-weather'] = esc_html__('Shortcode', 'owm-weather');
    return $columns;
}

function owmw_custom_owm_weather_column($column, $post_id) {
    if ($column == 'owm-weather') {
        echo '<b>[owm-weather id="' . esc_html($post_id) . '" /]</b>';
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Weather Custom Post Type
///////////////////////////////////////////////////////////////////////////////////////////////////

// Register Custom Post Type
function owmw_posttype_weather() {
	$labels = array(
		'name'                => _x( 'Weather', 'Post Type General Name', 'owm-weather' ),
		'singular_name'       => _x( 'Weather', 'Post Type Singular Name', 'owm-weather' ),
		'menu_name'           => __( 'Weather', 'owm-weather' ),
		'parent_item_colon'   => __( 'Parent Weather:', 'owm-weather' ),
		'all_items'           => __( 'All Weather', 'owm-weather' ),
		'view_item'           => __( 'View Weather', 'owm-weather' ),
		'add_new_item'        => __( 'Add New Weather', 'owm-weather' ),
		'add_new'             => __( 'New Weather', 'owm-weather' ),
		'edit_item'           => __( 'Edit Weather', 'owm-weather' ),
		'update_item'         => __( 'Update Weather', 'owm-weather' ),
		'search_items'        => __( 'Search Weather', 'owm-weather' ),
		'not_found'           => __( 'No weather found', 'owm-weather' ),
		'not_found_in_trash'  => __( 'No weather found in Trash', 'owm-weather' ),
	);

	$args = array(
		'label'               => __( 'weather', 'owm-weather' ),
		'description'         => __( 'Listing weather', 'owm-weather' ),
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

	register_post_type( 'owm-weather', $args );
}

// Hook into the 'init' action
add_action( 'init', 'owmw_posttype_weather', 0 );

///////////////////////////////////////////////////////////////////////////////////////////////////
//Weather Custom Post Type Messages
///////////////////////////////////////////////////////////////////////////////////////////////////

function owmw_set_messages($messages) {
	global $post, $post_ID;
	$post_type = 'owm-weather';

	$obj = get_post_type_object($post_type);
	$singular = $obj->labels->singular_name;

	$messages[$post_type] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( $singular." ".__('updated').'.', esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated').'.',
		3 => __('Custom field deleted').'.',
		4 => $singular." ".__('updated').'.',
		5 => isset($_GET['revision']) ? sprintf( $singular." ".__('restored to revision from')." %s", wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => $singular." ".sprintf( __('published').'.', esc_url( get_permalink($post_ID) ) ),
		7 => __('Page saved').'.',
		8 => sprintf( $singular." ".__('submitted').'.', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( $singular." ".__('scheduled for') . ': <strong>%1$s</strong>.', date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( $singular." ".__('draft updated').'.', esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);
	return $messages;
}
add_filter('post_updated_messages', 'owmw_set_messages' );

///////////////////////////////////////////////////////////////////////////////////////////////////
//OWM WEATHER Notices
///////////////////////////////////////////////////////////////////////////////////////////////////
function owmw_notice() {
	$owmw_advanced_api_key = get_option('owmw_option_name');
	if ( is_plugin_active( 'owm-weather/owmweather.php' ) && !isset($owmw_advanced_api_key['owmw_advanced_api_key'])) {
	    ?>
	    <div class="error notice">
	        <p><a href="<?php echo admin_url('admin.php?page=owmw-settings-admin#tab_advanced'); ?>"><?php esc_html_e( 'OWM Weather: Please enter your own OpenWeatherMap API key to avoid limits requests.', 'owm-weather' ); ?></a></p>
	    </div>
	    <?php
	}
}
add_action( 'admin_notices', 'owmw_notice' );

///////////////////////////////////////////////////////////////////////////////////////////////////
//Utility functions
///////////////////////////////////////////////////////////////////////////////////////////////////
function owmw_generate_hour_options($current) {
	$str = '<option ' . selected( 0, intval($current), false ) . ' value="0">' . esc_html__( "None", 'owm-weather' ) . '</option>';

    for ($i=1; $i<=48; $i++) {
        if ($i == 1) {
            $h = 'Now';
        } else if ($i == 2) {
            $h = 'Now + 1 hour';
        } else {
            $h = 'Now + ' . ($i-1) . ' hours';
        }
		$str .= '<option ' . selected( $i, intval($current), false ) . ' value="' . esc_attr($i) . '">' . esc_html__( $h, 'owm-weather' ) . '</option>';
    }

    return $str;
}

function owmw_getWindDirection($deg) {
    $dirs = array('N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW', 'N');
    return $dirs[round($deg/45)];
}

function owmw_getConvertedWindSpeed($speed, $unit, $bypass_unit) {
	switch ($bypass_unit) {
        case "1": //MI/H
        	if ($unit =='metric') {
		        return number_format($speed*2.24,0);
			} else {
				return number_format($speed,0);
			}
          	break;
        case "2": //M/S
        	if ($unit =='metric') {
	        	return number_format($speed,0);
			} else {
				return number_format($speed/2.24,0);
			}
          	break;
        case "3": //KM/H
        	if ($unit =='metric') {
				return number_format($speed*3.6,0);
			} else {
				return number_format($speed*1.61,0);
			}
          	break;
        case "4": //KNOTS
        	if ($unit =='metric') {
		        return number_format($speed*1.94,0);
			} else {
				return number_format($speed*0.87,0);
			}
          	break;
  		}

    return number_format($speed,0);
}

function owmw_getWindSpeedUnit($unit, $bypass_unit) {
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
        	if ($unit =='metric') {
				return 'm/s';
			} else {
				return 'mi/h';
			}
			break;
	}
}

function owmw_getConvertedPrecipitation($unit, $p) {
    if ($unit == 'imperial') {
        return number_format($p / 25.4, 1);
    }

    return number_format($p,0);
}

function owmw_getConvertedDistance($unit, $p) {
    if ($unit == 'imperial') {
        return ($p ? number_format($p / 1609.344, 1)." mi" : "");
    }

    return ($p ? number_format($p / 1000, 1)." km" : "");
}

function owmw_converthp2iom($unit, $p) {
    if ($unit == 'imperial') {
        return number_format($p / 33.86389, 2);
    }

    return number_format($p,0);
}

function owmw_getDefault($id, $field, $default) {
	$val = get_post_meta($id, $field, true);
	return !empty($val) ? $val : $default;
}

function owmw_getBypassDefault($bypass, $field, $default) {
	$val = owmw_get_bypass($bypass, $field);
	return !empty($val) ? $val : $default;
}

function owmw_unique_id_esc( $prefix = '', $delim = '-' ) {
    static $id_counter = 0;
    return esc_attr($prefix . (isset($_POST['counter']) ? $delim . intval($_POST['counter']) : '') . $delim . (string) ++$id_counter);
}

function owmw_deleteWhitespaces(&$arr) {
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

function owmw_esc_html_all(&$arr) {
    if ($arr) {
        foreach ($arr as $key => &$value) {
            if (is_array($value)) {
                owmw_esc_html_all($value);
            } else {
                $value = esc_html($value);
            }
        }
    }
}

function owmw_sanitize_atts(&$arr) {
    if ($arr) {
        foreach ($arr as $key => &$value) {
            if ($value) {
                $value = owmw_sanitize_validate_field($key, $value);
            }
        }
    }
}

function owmw_sanitize_validate_field($key, $value) {
    if (!empty($value)) {
        switch($key) {
            case "id":
            case "id_owm":
            case "background_image":
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
                if (!in_array($value, array("Default", "af", "al", "ar", "az", "eu", "bg", "ca", "zh_cn", "zh_tw", "hr", "cz", "da", "nl", "en", "fi", "fr", "gl", "de", "el", "he", "hi", "hu", "id", "it", "ja", "kr", "la", "lt", "mk", "no", "fa", "pl", "pt", "pt", "ro", "ru", "sr", "sv", "sk", "sl", "sp", "th", "tr", "ua", "vi", "zu"))) {
                    $value = "Default";
                }
                break;

            case "today_date_format":
                $value = sanitize_text_field($value);
                if (!in_array($value, array("none", "day", "date"))) {
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
                if (!in_array($value, array("Climacons", "OpenWeatherMap", "WeatherIcons", "Forecast", "Dripicons", "Pixeden"))) {
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
                if (!in_array($value, array("Default", "card1", "card2", "chart1", "chart2", "table1", "table2", "table3", "slider1", "slider2", "custom1", "custom2", "debug"))) {
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
                } else if ($value < 300) {
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
                $value = floatval(sanitize_text_field($value));
                if ($value < 0.0 || $value > 1.0) {
                    $value = 0.5;
                }
                break;

            case "longitude":
            case "latitude":
                $value = floatval(sanitize_text_field($value));
                if ($value === "0") {
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
            case "forecast_precipitations":
            case "owm_link":
            case "last_update":
            case "map":
            case "map_disable_zoom_wheel":
            case "map_stations":
            case "map_clouds":
            case "map_precipitation":
            case "map_snow":
            case "map_wind":
            case "map_temperature":
            case "map_pressure":
            case "gtag":
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
            case "border_color":
            case "background_color":
            case "alerts_button_color":
            case "chart_background_color":
            case "chart_border_color":
            case "chart_temperature_color":
            case "chart_feels_like_color":
            case "chart_dew_point_color":
            case "table_text_color":
            case "table_border_color":
            case "table_background_color":
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
                } else if ($value < 10) {
                    $value = 10;
                }
                break;

            default:
                $value = sanitize_text_field($value);
                break;
        }
    }
    
    return $value;
}

function owmw_sanitize_api_response(&$arr, $ta = []) {
    if ($arr) {
        array_walk_recursive($arr, 'owmw_sanitize_api_response_item', $ta);
    }
}

function owmw_sanitize_api_response_item(&$item, $key, $ta = []) {
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

function owmw_IPtoLocation() {
    global $wp;

    $client  = @$_SERVER["HTTP_CF_CONNECTING_IP"];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $xforward = @$_SERVER['HTTP_X_FORWARDED'];
    $forwardfor = @$_SERVER['HTTP_FORWARDED_FOR'];
    $forwarded = @$_SERVER['HTTP_FORWARDED'];
    $clientip = @$_SERVER['HTTP_CLIENT_IP'];
    $remote  = @$_SERVER['REMOTE_ADDR'];

    if (filter_var($client, FILTER_VALIDATE_IP)){
       $ip = $client;
    } else if(filter_var($forward, FILTER_VALIDATE_IP)){
       $ip = $forward;
    } else if(filter_var($xforward, FILTER_VALIDATE_IP)){
       $ip = $xforward;
    } else if(filter_var($forwardfor, FILTER_VALIDATE_IP)){
       $ip = $forwardfor;
    } else if(filter_var($forwarded, FILTER_VALIDATE_IP)){
       $ip = $forwarded;
    } else if(filter_var($clientip, FILTER_VALIDATE_IP)){
       $ip = $clientip;
    } else if(filter_var($remote, FILTER_VALIDATE_IP)){
       $ip = $remote;
    } else {
        return false;
    }

    $transient_key = 'owmweather_iplocation_' . $ip;

    if (false === ($ipData = get_transient($transient_key))) {
    	$apiURL = 'https://tools.keycdn.com/geo.json?host='.$ip;
        $request_headers = [];
        $request_headers[] = 'User-Agent: keycdn-tools:' . home_url($wp->request);

        $response = wp_remote_get( $apiURL,
             array( 'timeout' => 10,
            'headers' => array( 'User-Agent' => 'keycdn-tools:' .home_url($wp->request)) 
             ));

        if (is_wp_error($response)) {
			return false;
   		}

        $ipData = json_decode(wp_remote_retrieve_body($response));
    	set_transient($transient_key, $ipData);
    }

    owmw_sanitize_api_response($ipData);

	return !empty($ipData) ? $ipData : false;
}

function owmw_celsius_to_fahrenheit($t) {
	if ($t !== null) {
		return ceil(($t * 9/5) + 32);
	}
		
	return null;
}

function owmw_fahrenheit_to_celsius($t) {
	if ($t !== null) {
		return ceil(($t - 32) * 5/9);
	}
		
	return null;
}
