<?php
/*
Plugin Name: OWM Weather
Plugin URI: https://github.com/uwejacobs/owm-weather
Description: OWM Weather is a powerful weather plugin for WordPress, based on Open Weather Map API, using Custom Post Types and shortcodes, bundled with a ton of features.
Version: 5.0.0
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

global $wow_params;

function wp_owm_weather_activation() {
    global $wpdb;
	$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_myweather%' ");
	$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_myweather%' ");
}
register_activation_hook(__FILE__, 'wp_owm_weather_activation');
function wp_owm_weather_deactivation() {
}
register_deactivation_hook(__FILE__, 'wp_owm_weather_deactivation');

define( 'WP_OWM_WEATHER_VERSION', '5.0.0' );

///////////////////////////////////////////////////////////////////////////////////////////////////
//Shortcut settings page
///////////////////////////////////////////////////////////////////////////////////////////////////

add_filter('plugin_action_links', 'wow_plugin_action_links', 10, 2);

function wow_plugin_action_links($links, $file) {
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        $settings_link = '<a href="' . admin_url('admin.php?page=wow-settings-admin').'">'.__('Settings', 'owm-weather').'</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Translation
///////////////////////////////////////////////////////////////////////////////////////////////////
function wpowmweather_init() {
	load_plugin_textdomain( 'owm-weather', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

	//Admin panel + Dashboard widget
	if ( is_admin() ) {
		require_once dirname( __FILE__ ) . '/owmweather-admin.php';
		require_once dirname( __FILE__ ) . '/owmweather-export.php';
	    require_once dirname( __FILE__ ) . '/owmweather-widget.php';
	    require_once dirname( __FILE__ ) . '/owmweather-pointers.php';
	}
}
add_action('plugins_loaded', 'wpowmweather_init');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Enqueue styles Front-end
///////////////////////////////////////////////////////////////////////////////////////////////////
function wpowmweather_async_js($url) {
    if (strpos($url, '#async')===false)
        return $url;
    else if (is_admin())
        return str_replace('#async', '', $url);
    else
        return str_replace('#async', '', $url)."' async='async";
}
add_filter('clean_url', 'wpowmweather_async_js', 11, 1);

function wpowmweather_styles() {
	global $post;

	wp_enqueue_script( 'wow-ajax-js', plugins_url('js/owm-weather-ajax.js', __FILE__), array('jquery'), '', true );
	$wowAjax = array(
        'wow_nonce' => wp_create_nonce('wow_get_weather_nonce'),
        'wow_url' => admin_url( 'admin-ajax.php' ) . "?lang=" . substr(get_locale(),0,2),
    );
	wp_localize_script( 'wow-ajax-js', 'wowAjax', $wowAjax);

	wp_register_style('owmweather-css', plugins_url('css/owmweather.min.css', __FILE__));
	wp_enqueue_style('owmweather-css');

	wp_register_style('owmweather-anim-css', plugins_url('css/owmweather-anim.min.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'wpowmweather_styles');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Loads the JS/CSS for Slider1 - Slider2
///////////////////////////////////////////////////////////////////////////////////////////////////

function wow_add_themes_scripts() {
	wp_register_style( 'wow-flexslider-css', plugins_url( 'css/flexslider.css', __FILE__ ));
	wp_register_script( 'wow-flexslider-js', plugins_url( 'js/jquery.flexslider-min.js#async', __FILE__ ));
	wp_register_style( 'bootstrap-css', plugins_url( 'css/bootstrap.min.css', __FILE__ ));
	wp_register_script( 'bootstrap-js', plugins_url( 'js/bootstrap.bundle.min.js#async', __FILE__ ));
	wp_register_script( 'chart-js', plugins_url( 'js/chart.min.js#async', __FILE__ ));
}
add_action( 'wp_enqueue_scripts', 'wow_add_themes_scripts', 10, 1 );

///////////////////////////////////////////////////////////////////////////////////////////////////
//Loads the JS/CSS in admin
///////////////////////////////////////////////////////////////////////////////////////////////////

//Dashboard
function wow_add_dashboard_scripts() {
	wp_enqueue_script( 'wow-ajax-js', plugins_url('js/owm-weather-ajax.js', __FILE__), array('jquery'), '', true );

	$wowAjax = array(
        'wow_nonce' => wp_create_nonce('wow_get_weather_nonce'),
        'wow_url' => admin_url( 'admin-ajax.php' ) . "?lang=" . substr(get_locale(),0,2),
    );
	wp_localize_script( 'wow-ajax-js', 'wowAjax', $wowAjax);

	wp_register_style('owmweather-css', plugins_url('css/owmweather.min.css', __FILE__));
	wp_enqueue_style('owmweather-css');

	wp_register_style('owmweather-anim-css', plugins_url('css/owmweather-anim.min.css', __FILE__));

}
add_action('admin_head-index.php', 'wow_add_dashboard_scripts');

//Admin + Custom Post Type (new, listing)
function wow_add_admin_scripts( $hook ) {

global $post;

	if ( $hook == 'post-new.php' || $hook == 'post.php') {

        if ( 'wow-weather' === $post->post_type ) {
			wp_register_style( 'owmweather-admin-css', plugins_url('css/owmweather-admin.min.css', __FILE__));
			wp_enqueue_style( 'owmweather-admin-css' );

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'color-picker-js', plugins_url('js/color-picker.js', __FILE__), array( 'wp-color-picker' ) );

			wp_enqueue_script( 'tabs-js', plugins_url( 'js/tabs.js', __FILE__ ), array( 'jquery-ui-tabs' ) );

			wp_enqueue_script( 'handlebars-js', plugins_url( 'js/handlebars-v1.3.0.js', __FILE__ ), array('typeahead-bundle-js') );
			wp_enqueue_script( 'typeahead-bundle-js', plugins_url( 'js/typeahead.bundle.min.js', __FILE__ ), array('jquery') , '2.0');
			wp_enqueue_script( 'autocomplete-js', plugins_url( 'js/wow-autocomplete.js', __FILE__ ), '', '2.0', true );
		}
	}
}
add_action( 'admin_enqueue_scripts', 'wow_add_admin_scripts', 10, 1 );

//OWM Weather Options page
function wow_add_admin_options_scripts() {
			wp_register_style( 'owmweather-admin-css', plugins_url('css/owmweather-admin.min.css', __FILE__));
			wp_enqueue_style( 'owmweather-admin-css' );

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'color-picker-js', plugins_url('js/color-picker.js', __FILE__), array( 'wp-color-picker' ) );
			wp_enqueue_script( 'tabs-js', plugins_url( 'js/tabs.js', __FILE__ ), array( 'jquery-ui-tabs' ) );
}

if (isset($_GET['page']) && ($_GET['page'] == 'wow-settings-admin')) {

	add_action('admin_enqueue_scripts', 'wow_add_admin_options_scripts', 10, 1);
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
// function wow_gutenberg_boilerplate_block() {
//     wp_register_script('gutenberg-wpowmweather-js', plugins_url( 'js/blocks.build.js', __FILE__ ), array( 'wp-blocks', 'wp-element' ));

//     register_block_type( 'gutenberg-wpowmweather/wpowmweather', array('editor_script' => 'gutenberg-wpowmweather'));

//     wp_enqueue_script('gutenberg-wpowmweather');
// }
// add_action( 'enqueue_block_editor_assets', 'wow_gutenberg_boilerplate_block' );

///////////////////////////////////////////////////////////////////////////////////////////////////
//Get all registered post types
///////////////////////////////////////////////////////////////////////////////////////////////////

function wow_get_post_types() {
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
add_action('admin_head', 'wow_add_button_v4');

function wow_add_button_v4() {
    global $typenow;

    if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
    	return;
    }

    if( ! in_array( $typenow, wow_get_post_types() ) )
        return;

	if ( get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "wow_add_button_v4_plugin");
		add_filter('mce_buttons', 'wow_add_button_v4_register');
	}
}
function wow_add_button_v4_plugin($plugin_array) {
    $plugin_array['wow_button_v4'] = plugins_url( 'js/wow-tinymce.js', __FILE__ );
    return $plugin_array;
}
function wow_add_button_v4_register($buttons) {
   array_push($buttons, "wow_button_v4");
   return $buttons;
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Add duplicate link in OWM WEATHER List view
///////////////////////////////////////////////////////////////////////////////////////////////////

function wow_duplicate_post_as_draft(){
	global $wpdb;
	if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'wow_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
		wp_die('No weather to duplicate has been supplied!');
	}

	$post_id = (isset($_GET['post']) ? $_GET['post'] : $_POST['post']);

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
add_action( 'admin_action_wow_duplicate_post_as_draft', 'wow_duplicate_post_as_draft', 999 );

function wow_duplicate_post_link( $actions, $post ) {
	if ($post->post_type === 'wow-weather' && current_user_can('edit_posts')) {
		$actions['duplicate'] = '<a href="admin.php?action=wow_duplicate_post_as_draft&amp;post=' . $post->ID . '" title="'.__('Duplicate this item','owm-weather').'" rel="permalink">'.__('Duplicate','owm-weather').'</a>';
	}
	return $actions;
}

add_filter( 'post_row_actions', 'wow_duplicate_post_link', 999, 2 );

///////////////////////////////////////////////////////////////////////////////////////////////////
//Display metabox in Weather Custom Post Type
///////////////////////////////////////////////////////////////////////////////////////////////////

add_action('add_meta_boxes','wp_owm_weather_init_metabox');
function wp_owm_weather_init_metabox(){
	add_meta_box('wpowmweather_basic', __('OWM Weather Settings','owm-weather') .' - <a href="'.admin_url("options-general.php?page=wow-settings-admin").'">'.__('OWM Weather global settings','owm-weather').'</a>', 'wpowmweather_basic', 'wow-weather', 'advanced');
	add_meta_box('wpowmweather_shortcode', 'OWM Weather Shortcode', 'wpowmweather_shortcode', 'wow-weather', 'side');
}

function wpowmweather_shortcode($post){
	_e( 'Copy and paste this shortcode anywhere in posts, pages, text widgets: ', 'owm-weather' );
	echo "<div class='shortcode'>";
	echo "<span class='wow-highlight'>[wow-weather id=\"";
	echo get_the_ID();
	echo "\"/]</span>";
	echo "</div>";

	echo '<div class="shortcode-php">';
	_e( 'If you need to display this weather anywhere in your theme, simply copy and paste this code snippet in your PHP file like sidebar.php: ', 'owm-weather' );
	echo "<span class='wow-highlight'>echo do_shortcode('[wow-weather id=\"".get_the_ID()."\"]');</span>";
	echo "</div>";
}

function wpowmweather_basic($post){
    $id = $post->ID;

    wp_enqueue_media();
    media_selector_print_scripts($id);

    $wow_opt = [];
	$wow_opt["city"] 					    = get_post_meta($id,'_wpowmweather_city',true);
	$wow_opt["custom_city_name"]		    = get_post_meta($id,'_wpowmweather_city_name',true);
	$wow_opt["id_owm"]					    = get_post_meta($id,'_wpowmweather_id_owm',true);
	$wow_opt["longitude"] 			        = get_post_meta($id,'_wpowmweather_longitude',true);
	$wow_opt["latitude"] 		    	    = get_post_meta($id,'_wpowmweather_latitude',true);
	$wow_opt["zip"] 		    	        = get_post_meta($id,'_wpowmweather_zip',true);
	$wow_opt["country_code"] 		        = get_post_meta($id,'_wpowmweather_country_code',true);
	$wow_opt["temperature_unit"] 			= get_post_meta($id,'_wpowmweather_unit',true);
	$wow_opt["time_format"]				    = get_post_meta($id,'_wpowmweather_time_format',true);
	$wow_opt["custom_timezone"]	    		= get_post_meta($id,'_wpowmweather_custom_timezone',true);
	$wow_opt["owm_language"] 		    	= get_post_meta($id,'_wpowmweather_owm_language',true);
	$wow_opt["gtag"]              		    = get_post_meta($id,'_wpowmweather_gtag',true);
	$wow_opt["bypass_exclude"]     		    = get_post_meta($id,'_wpowmweather_bypass_exclude',true);
	$wow_opt["current_weather_symbol"]		= get_post_meta($id,'_wpowmweather_current_weather_symbol',true);
	$wow_opt["current_city_name"]	    	= get_post_meta($id,'_wpowmweather_current_city_name',true);
	$wow_opt["today_date_format"]	    	= getDefault($id,'_wpowmweather_today_date_format', 'none');
	$wow_opt["current_weather_description"]	= get_post_meta($id,'_wpowmweather_current_weather_description',true);
	$wow_opt["sunrise_sunset"] 			    = get_post_meta($id,'_wpowmweather_sunrise_sunset',true);
	$wow_opt["moonrise_moonset"] 	    	= get_post_meta($id,'_wpowmweather_moonrise_moonset',true);
	$wow_opt["wind"] 				    	= get_post_meta($id,'_wpowmweather_wind',true);
	$wow_opt["wind_unit"] 				    = get_post_meta($id,'_wpowmweather_wind_unit',true);
	$wow_opt["humidity"] 				    = get_post_meta($id,'_wpowmweather_humidity',true);
	$wow_opt["dew_point"] 				    = get_post_meta($id,'_wpowmweather_dew_point',true);
	$wow_opt["pressure"]				    = get_post_meta($id,'_wpowmweather_pressure',true);
	$wow_opt["cloudiness"]				    = get_post_meta($id,'_wpowmweather_cloudiness',true);
	$wow_opt["precipitation"]			    = get_post_meta($id,'_wpowmweather_precipitation',true);
	$wow_opt["visibility"]			        = get_post_meta($id,'_wpowmweather_visibility',true);
	$wow_opt["uv_index"]			        = get_post_meta($id,'_wpowmweather_uv_index',true);
	$wow_opt["alerts"]    				    = get_post_meta($id,'_wpowmweather_alerts',true);
	$wow_opt["alerts_button_color"]         = get_post_meta($id,'_wpowmweather_alerts_button_color',true);
	$wow_opt["hours_forecast_no"]		    = get_post_meta($id,'_wpowmweather_hours_forecast_no',true);
	$wow_opt["hours_time_icons"]		    = get_post_meta($id,'_wpowmweather_hours_time_icons',true);
	$wow_opt["current_temperature"]		    = get_post_meta($id,'_wpowmweather_current_temperature',true);
	$wow_opt["current_feels_like"]		    = get_post_meta($id,'_wpowmweather_current_feels_like',true);
	$wow_opt["display_temperature_unit"]	= get_post_meta($id,'_wpowmweather_display_temperature_unit',true);
	$wow_opt["days_forecast_no"]		    = get_post_meta($id,'_wpowmweather_forecast_no',true);
	$wow_opt["forecast_precipitations"]     = get_post_meta($id,'_wpowmweather_forecast_precipitations',true);
	$wow_opt["display_length_days_names"]	= getDefault($id,'_wpowmweather_display_length_days_names', 'short');
 	$wow_opt["disable_spinner"]   			= get_post_meta($id,'_wpowmweather_disable_spinner',true);
 	$wow_opt["disable_anims"]   			= get_post_meta($id,'_wpowmweather_disable_anims',true);
	$wow_opt["background_color"]	   		= get_post_meta($id,'_wpowmweather_background_color',true);
	$wow_opt["background_image"]	   		= get_post_meta($id,'_wpowmweather_background_image',true);
	$wow_opt["text_color"]		        	= get_post_meta($id,'_wpowmweather_text_color',true);
	$wow_opt["border_color"]		        = get_post_meta($id,'_wpowmweather_border_color',true);
	$wow_opt["border_width"]		        = getDefault($id, '_wpowmweather_border_width', $wow_opt["border_color"] == '' ? '0' : '1');
	$wow_opt["border_style"]		        = get_post_meta($id,'_wpowmweather_border_style',true);
	$wow_opt["border_radius"]		        = getDefault($id, '_wpowmweather_border_radius', '0');
	$wow_opt["custom_css"]	    			= get_post_meta($id,'_wpowmweather_custom_css',true);
	$wow_opt["size"] 			    		= get_post_meta($id,'_wpowmweather_size',true);
	$wow_opt["owm_link"]			    	= get_post_meta($id,'_wpowmweather_owm_link',true);
	$wow_opt["last_update"]				    = get_post_meta($id,'_wpowmweather_last_update',true);
	$wow_opt["font"]      		    		= get_post_meta($id,'_wpowmweather_font',true);
	$wow_opt["template"]     		    	= get_post_meta($id,'_wpowmweather_template',true);
	$wow_opt["iconpack"]     			    = get_post_meta($id,'_wpowmweather_iconpack',true);
	$wow_opt["map"] 	    				= get_post_meta($id,'_wpowmweather_map',true);
	$wow_opt["map_height"]	    			= get_post_meta($id,'_wpowmweather_map_height',true);
	$wow_opt["map_opacity"]		    		= getDefault($id,'_wpowmweather_map_opacity', "0.5");
	$wow_opt["map_zoom"]			    	= getDefault($id,'_wpowmweather_map_zoom', '9');
	$wow_opt["map_disable_zoom_wheel"]		= get_post_meta($id,'_wpowmweather_map_disable_zoom_wheel',true);
	$wow_opt["map_stations"]			    = get_post_meta($id,'_wpowmweather_map_stations',true);
	$wow_opt["map_clouds"]				    = get_post_meta($id,'_wpowmweather_map_clouds',true);
	$wow_opt["map_precipitation"]		    = get_post_meta($id,'_wpowmweather_map_precipitation',true);
	$wow_opt["map_snow"]    				= get_post_meta($id,'_wpowmweather_map_snow',true);
	$wow_opt["map_wind"]	    			= get_post_meta($id,'_wpowmweather_map_wind',true);
	$wow_opt["map_temperature"]	    		= get_post_meta($id,'_wpowmweather_map_temperature',true);
	$wow_opt["map_pressure"]		    	= get_post_meta($id,'_wpowmweather_map_pressure',true);

	$wow_opt["chart_height"]	    		= getDefault($id,'_wpowmweather_chart_height', '400');
	$wow_opt["chart_background_color"]		= getDefault($id, '_wpowmweather_chart_background_color', '#fff');
	$wow_opt["chart_border_color"]		    = getDefault($id, '_wpowmweather_chart_border_color', '');
	$wow_opt["chart_border_width"]		    = getDefault($id, '_wpowmweather_chart_border_width', $wow_opt["chart_border_color"] == '' ? '0' : '1');
	$wow_opt["chart_border_style"]		    = get_post_meta($id,'_wpowmweather_chart_border_style',true);
	$wow_opt["chart_border_radius"]		    = getDefault($id, '_wpowmweather_chart_border_radius', '0');
	$wow_opt["chart_temperature_color"]	    = getDefault($id,'_wpowmweather_chart_temperature_color', '#d5202a');
	$wow_opt["chart_feels_like_color"]	    = getDefault($id,'_wpowmweather_chart_feels_like_color', '#f83');
	$wow_opt["chart_dew_point_color"]	    = getDefault($id,'_wpowmweather_chart_dew_point_color', '#ac54a0');

	$wow_opt["table_background_color"]		= getDefault($id, '_wpowmweather_table_background_color', '');
	$wow_opt["table_border_color"]		    = getDefault($id, '_wpowmweather_table_border_color', '');
	$wow_opt["table_border_width"]		    = getDefault($id, '_wpowmweather_table_border_width', $wow_opt["table_border_color"] == '' ? '0' : '1');
	$wow_opt["table_border_style"]		    = get_post_meta($id,'_wpowmweather_table_border_style',true);
	$wow_opt["table_border_radius"]		    = getDefault($id, '_wpowmweather_table_border_radius', '0');
	$wow_opt["table_text_color"]		    = getDefault($id, '_wpowmweather_table_text_color', '');


	function wow_get_admin_api_key2() {
		$options = get_option("wow_option_name");
		if ( ! empty ( $options["wow_advanced_api_key"] ) ) {
			return $options["wow_advanced_api_key"];
		} else {
			return '46c433f6ba7dd4d29d5718dac3d7f035';//bugbug
		}
	};

	echo '<div id="wpowmweather-tabs">
			<ul>
				<li><a href="#tabs-1">'. __( 'Basic', 'owm-weather' ) .'</a></li>
				<li><a href="#tabs-2">'. __( 'Display', 'owm-weather' ) .'</a></li>
				<li><a href="#tabs-3">'. __( 'Layout', 'owm-weather' ) .'</a></li>
				<li><a href="#tabs-4">'. __( 'Map', 'owm-weather' ) .'</a></li>
			</ul>

			<div id="tabs-1">
			    <p class=" subsection-title">
    			    Get weather by ...
			    </p>
                <div id="wpowmweather-owm-param">
        			<ul>
    	    			<li><a href="#fragment-1">'. __( 'City Id', 'owm-weather' ) .'</a></li>
    		    		<li><a href="#fragment-2">'. __( 'Longitude/Latitude', 'owm-weather' ) .'</a></li>
    				    <li><a href="#fragment-3">'. __( 'Zip', 'owm-weather' ) .'</a></li>
    			    	<li><a href="#fragment-4">'. __( 'City/Country', 'owm-weather' ) .'</a></li>
    				    <li><a href="#fragment-5">'. __( 'Visitor\'s Location', 'owm-weather' ) .'</a></li>
        			</ul>
                    <div id="fragment-1">
          				<p>
        					<label for="wpowmweather_id_owm_meta">'. __( 'OpenWeatherMap City Id', 'owm-weather' ) .'<span class="mandatory">*</span> <a href="https://openweathermap.org/find?q=" target="_blank"> '.__('Find my City Id','owm-weather').'</a><span class="dashicons dashicons-external"></span></label>
        					<input id="wpowmweather_id_owm" type="number" name="wpowmweather_id_owm" value="'.$wow_opt["id_owm"].'" />
        				</p>
                    </div>
                    <div id="fragment-2">
        				<p>
        					<label for="wpowmweather_latitude_meta">'. __( 'Latitude?', 'owm-weather' ) .' <span class="mandatory">*</span></label>
        					<input id="wpowmweather_latitude_meta" type="number" min="-90" max="90" step="0.000001" name="wpowmweather_latitude" value="'.$wow_opt["latitude"].'" />
        				</p>
        				<p>
        					<label for="wpowmweather_longitude_meta">'. __( 'Longitude?', 'owm-weather' ) .' <span class="mandatory">*</span></label>
        					<input id="wpowmweather_longitude_meta" type="number" min="-180" max="180" step="0.000001" name="wpowmweather_longitude" value="'.$wow_opt["longitude"].'" />
        				</p>
        				<p><em>'.__('If you enter an OpenWeatherMap City Id, it will automatically bypass the  Latitude/Longitude fields.','owm-weather').'</em></p>
                    </div>
                    <div id="fragment-3">
        				<p>
        					<label for="wpowmweather_zip_meta">'. __( 'Zip code?', 'owm-weather' ) .' <span class="mandatory">*</span></label>
        					<input id="wpowmweather_zip_meta" type="number" min="1" max="99999" name="wpowmweather_zip" value="'.$wow_opt["zip"].'" />
        				</p>
        				<p><em>'.__('If you enter an OpenWeatherMap City Id or Latitude/Longitude, it will automatically bypass the City and Country fields.','owm-weather').'</em></p>
                    </div>
                    <div id="fragment-4">
        				<p>
        					<label for="wpowmweather_city_meta">'. __( 'City', 'owm-weather' ) .' <span class="mandatory">*</span></label>
        					<input id="wpowmweather_city_meta" data_appid="'.wow_get_admin_api_key2().'" class="cities typeahead" type="text" name="wpowmweather_city" placeholder="'.__('Enter your city','owm-weather').'" value="'.$wow_opt["city"].'" />
        				</p>
        				<p>
        					<label for="wpowmweather_country_meta">'. __( 'Country?', 'owm-weather' ) .' <span class="mandatory">*</span></label>
        					<input id="wpowmweather_country_meta" class="countries typeahead" type="text" name="wpowmweather_country_code" value="'.$wow_opt["country_code"].'" />
        				</p>
        				<p><em>'.__('If you enter an OpenWeatherMap City Id, Latitude/Longitude or Zip, it will automatically bypass the City and Country fields.','owm-weather').'</em></p>
                    </div>
                    <div id="fragment-5">
        				<p><em>'.__('Leave City Id, Longitude/Latitude, Zip and City/Country empty to use the visitor\'s location.','owm-weather').'</em></p>
                    </div>
                </div>
			    <p class=" subsection-title">
    			    Basic
			    </p>
				<p>
					<label for="wpowmweather_city_name_meta">'. __( 'Custom city title', 'owm-weather' ) .'</label>
					<input id="wpowmweather_city_name_meta" type="text" name="wpowmweather_city_name" value="'.$wow_opt["custom_city_name"].'" />
				</p>
				<p>
					<label for="unit_meta">'. __( 'Measurement system?', 'owm-weather' ) .'</label>
					<select name="wpowmweather_unit">
						<option ' . selected( 'imperial', $wow_opt["temperature_unit"], false ) . ' value="imperial">'. __( 'Imperial', 'owm-weather' ) .'</option>
						<option ' . selected( 'metric', $wow_opt["temperature_unit"], false ) . ' value="metric">'. __( 'Metric', 'owm-weather' ) .'</option>
					</select>
				</p>
				<p>
					<label for="wpowmweather_time_format_meta">'. __( '12h / 24h time format?', 'owm-weather' ) .'</label>
					<select name="wpowmweather_time_format">
						<option ' . selected( '12', $wow_opt["time_format"], false ) . ' value="12">'. __( '12 h', 'owm-weather' ) .'</option>
						<option ' . selected( '24', $wow_opt["time_format"], false ) . ' value="24">'. __( '24 h', 'owm-weather' ) .'</option>
					</select>
				</p>
				<p>
					<label for="wpowmweather_custom_timezone_meta">'. __( 'Custom timezone? (default: WordPress general settings)', 'owm-weather' ) .'</label>
					<select name="wpowmweather_custom_timezone" id="wpowmweather_custom_timezone_meta">
						<option ' . selected( 'Default', $wow_opt["custom_timezone"], false ) . ' value="Default">'. __( 'WordPress timezone', 'owm-weather' ) .'</option>
						<option ' . selected( '-12', $wow_opt["custom_timezone"], false ) . ' value="-12">'. __( 'UTC -12', 'owm-weather' ) .'</option>
						<option ' . selected( '-11', $wow_opt["custom_timezone"], false ) . ' value="-11">'. __( 'UTC -11', 'owm-weather' ) .'</option>
						<option ' . selected( '-10', $wow_opt["custom_timezone"], false ) . ' value="-10">'. __( 'UTC -10', 'owm-weather' ) .'</option>
						<option ' . selected( '-9', $wow_opt["custom_timezone"], false ) . ' value="-9">'. __( 'UTC -9', 'owm-weather' ) .'</option>
						<option ' . selected( '-8', $wow_opt["custom_timezone"], false ) . ' value="-8">'. __( 'UTC -8', 'owm-weather' ) .'</option>
						<option ' . selected( '-7', $wow_opt["custom_timezone"], false ) . ' value="-7">'. __( 'UTC -7', 'owm-weather' ) .'</option>
						<option ' . selected( '-6', $wow_opt["custom_timezone"], false ) . ' value="-6">'. __( 'UTC -6', 'owm-weather' ) .'</option>
						<option ' . selected( '-5', $wow_opt["custom_timezone"], false ) . ' value="-5">'. __( 'UTC -5', 'owm-weather' ) .'</option>
						<option ' . selected( '-4', $wow_opt["custom_timezone"], false ) . ' value="-4">'. __( 'UTC -4', 'owm-weather' ) .'</option>
						<option ' . selected( '-3', $wow_opt["custom_timezone"], false ) . ' value="-3">'. __( 'UTC -3', 'owm-weather' ) .'</option>
						<option ' . selected( '-2', $wow_opt["custom_timezone"], false ) . ' value="-2">'. __( 'UTC -2', 'owm-weather' ) .'</option>
						<option ' . selected( '-1', $wow_opt["custom_timezone"], false ) . ' value="-1">'. __( 'UTC -1', 'owm-weather' ) .'</option>
						<option ' . selected( '0', $wow_opt["custom_timezone"], false ) . ' value="0">'. __( 'UTC 0', 'owm-weather' ) .'</option>
						<option ' . selected( '1', $wow_opt["custom_timezone"], false ) . ' value="1">'. __( 'UTC +1', 'owm-weather' ) .'</option>
						<option ' . selected( '2', $wow_opt["custom_timezone"], false ) . ' value="2">'. __( 'UTC +2', 'owm-weather' ) .'</option>
						<option ' . selected( '3', $wow_opt["custom_timezone"], false ) . ' value="3">'. __( 'UTC +3', 'owm-weather' ) .'</option>
						<option ' . selected( '4', $wow_opt["custom_timezone"], false ) . ' value="4">'. __( 'UTC +4', 'owm-weather' ) .'</option>
						<option ' . selected( '5', $wow_opt["custom_timezone"], false ) . ' value="5">'. __( 'UTC +5', 'owm-weather' ) .'</option>
						<option ' . selected( '6', $wow_opt["custom_timezone"], false ) . ' value="6">'. __( 'UTC +6', 'owm-weather' ) .'</option>
						<option ' . selected( '7', $wow_opt["custom_timezone"], false ) . ' value="7">'. __( 'UTC +7', 'owm-weather' ) .'</option>
						<option ' . selected( '8', $wow_opt["custom_timezone"], false ) . ' value="8">'. __( 'UTC +8', 'owm-weather' ) .'</option>
						<option ' . selected( '9', $wow_opt["custom_timezone"], false ) . ' value="9">'. __( 'UTC +9', 'owm-weather' ) .'</option>
						<option ' . selected( '10', $wow_opt["custom_timezone"], false ) . ' value="10">'. __( 'UTC +10', 'owm-weather' ) .'</option>
						<option ' . selected( '11', $wow_opt["custom_timezone"], false ) . ' value="11">'. __( 'UTC +11', 'owm-weather' ) .'</option>
						<option ' . selected( '12', $wow_opt["custom_timezone"], false ) . ' value="12">'. __( 'UTC +12', 'owm-weather' ) .'</option>
					</select>
				</p>
				<p>
					<label for="wpowmweather_owm_language_meta">'. __( 'OpenWeatherMap language?', 'owm-weather' ) .'</label>
					<select name="wpowmweather_owm_language" id="wpowmweather_owm_language_meta">
						<option ' . selected( 'Default', $wow_opt["owm_language"], false ) . ' value="Default">'. __( 'Default', 'owm-weather' ) .'</option>
						<option ' . selected( 'af', $wow_opt["owm_language"], false ) . ' value="af">'. __( 'Afrikaans', 'owm-weather' ) .'</option>
						<option ' . selected( 'al', $wow_opt["owm_language"], false ) . ' value="al">'. __( 'Albanian', 'owm-weather' ) .'</option>
						<option ' . selected( 'ar', $wow_opt["owm_language"], false ) . ' value="ar">'. __( 'Arabic', 'owm-weather' ) .'</option>
						<option ' . selected( 'az', $wow_opt["owm_language"], false ) . ' value="az">'. __( 'Azerbaijani', 'owm-weather' ) .'</option>
						<option ' . selected( 'eu', $wow_opt["owm_language"], false ) . ' value="eu">'. __( 'Basque', 'owm-weather' ) .'</option>
						<option ' . selected( 'bg', $wow_opt["owm_language"], false ) . ' value="bg">'. __( 'Bulgarian', 'owm-weather' ) .'</option>
						<option ' . selected( 'ca', $wow_opt["owm_language"], false ) . ' value="ca">'. __( 'Catalan', 'owm-weather' ) .'</option>
						<option ' . selected( 'zh_cn', $wow_opt["owm_language"], false ) . ' value="zh_cn">'. __( 'Chinese Simplified', 'owm-weather' ) .'</option>
						<option ' . selected( 'zh_tw', $wow_opt["owm_language"], false ) . ' value="zh_tw">'. __( 'Chinese Traditional', 'owm-weather' ) .'</option>
						<option ' . selected( 'hr', $wow_opt["owm_language"], false ) . ' value="hr">'. __( 'Croatian', 'owm-weather' ) .'</option>
						<option ' . selected( 'cz', $wow_opt["owm_language"], false ) . ' value="cz">'. __( 'Czech', 'owm-weather' ) .'</option>
						<option ' . selected( 'da', $wow_opt["owm_language"], false ) . ' value="da">'. __( 'Danish', 'owm-weather' ) .'</option>
						<option ' . selected( 'nl', $wow_opt["owm_language"], false ) . ' value="nl">'. __( 'Dutch', 'owm-weather' ) .'</option>
						<option ' . selected( 'en', $wow_opt["owm_language"], false ) . ' value="en">'. __( 'English', 'owm-weather' ) .'</option>
						<option ' . selected( 'fi', $wow_opt["owm_language"], false ) . ' value="fi">'. __( 'Finnish', 'owm-weather' ) .'</option>
						<option ' . selected( 'fr', $wow_opt["owm_language"], false ) . ' value="fr">'. __( 'French', 'owm-weather' ) .'</option>
						<option ' . selected( 'gl', $wow_opt["owm_language"], false ) . ' value="gl">'. __( 'Galician', 'owm-weather' ) .'</option>
						<option ' . selected( 'de', $wow_opt["owm_language"], false ) . ' value="de">'. __( 'German', 'owm-weather' ) .'</option>
						<option ' . selected( 'el', $wow_opt["owm_language"], false ) . ' value="el">'. __( 'Greek', 'owm-weather' ) .'</option>
						<option ' . selected( 'he', $wow_opt["owm_language"], false ) . ' value="he">'. __( 'Hebrew', 'owm-weather' ) .'</option>
						<option ' . selected( 'hi', $wow_opt["owm_language"], false ) . ' value="hi">'. __( 'Hindi', 'owm-weather' ) .'</option>
						<option ' . selected( 'hu', $wow_opt["owm_language"], false ) . ' value="hu">'. __( 'Hungarian', 'owm-weather' ) .'</option>
						<option ' . selected( 'id', $wow_opt["owm_language"], false ) . ' value="id">'. __( 'Indonesian', 'owm-weather' ) .'</option>
						<option ' . selected( 'it', $wow_opt["owm_language"], false ) . ' value="it">'. __( 'Italian', 'owm-weather' ) .'</option>
						<option ' . selected( 'ja', $wow_opt["owm_language"], false ) . ' value="ja">'. __( 'Japanese', 'owm-weather' ) .'</option>
						<option ' . selected( 'kr', $wow_opt["owm_language"], false ) . ' value="kr">'. __( 'Korean', 'owm-weather' ) .'</option>
						<option ' . selected( 'la', $wow_opt["owm_language"], false ) . ' value="la">'. __( 'Latvian', 'owm-weather' ) .'</option>
						<option ' . selected( 'lt', $wow_opt["owm_language"], false ) . ' value="lt">'. __( 'Lithuanian', 'owm-weather' ) .'</option>
						<option ' . selected( 'mk', $wow_opt["owm_language"], false ) . ' value="mk">'. __( 'Macedonian', 'owm-weather' ) .'</option>
						<option ' . selected( 'no', $wow_opt["owm_language"], false ) . ' value="no">'. __( 'Norwegian', 'owm-weather' ) .'</option>
						<option ' . selected( 'fa', $wow_opt["owm_language"], false ) . ' value="fa">'. __( 'Persian (Farsi)', 'owm-weather' ) .'</option>
						<option ' . selected( 'pl', $wow_opt["owm_language"], false ) . ' value="pl">'. __( 'Polish', 'owm-weather' ) .'</option>
						<option ' . selected( 'pt', $wow_opt["owm_language"], false ) . ' value="pt">'. __( 'Portuguese', 'owm-weather' ) .'</option>
						<option ' . selected( 'pt', $wow_opt["owm_language"], false ) . ' value="pt">'. __( 'Português Brasil', 'owm-weather' ) .'</option>
						<option ' . selected( 'ro', $wow_opt["owm_language"], false ) . ' value="ro">'. __( 'Romanian', 'owm-weather' ) .'</option>
						<option ' . selected( 'ru', $wow_opt["owm_language"], false ) . ' value="ru">'. __( 'Russian', 'owm-weather' ) .'</option>
						<option ' . selected( 'sr', $wow_opt["owm_language"], false ) . ' value="sr">'. __( 'Serbian', 'owm-weather' ) .'</option>
						<option ' . selected( 'sv', $wow_opt["owm_language"], false ) . ' value="sv">'. __( 'Swedish', 'owm-weather' ) .'</option>
						<option ' . selected( 'sk', $wow_opt["owm_language"], false ) . ' value="sk">'. __( 'Slovak', 'owm-weather' ) .'</option>
						<option ' . selected( 'sl', $wow_opt["owm_language"], false ) . ' value="sl">'. __( 'Slovenian', 'owm-weather' ) .'</option>
						<option ' . selected( 'sp', $wow_opt["owm_language"], false ) . ' value="sp">'. __( 'Spanish', 'owm-weather' ) .'</option>
						<option ' . selected( 'th', $wow_opt["owm_language"], false ) . ' value="th">'. __( 'Thai', 'owm-weather' ) .'</option>
						<option ' . selected( 'tr', $wow_opt["owm_language"], false ) . ' value="tr">'. __( 'Turkish', 'owm-weather' ) .'</option>
						<option ' . selected( 'ua', $wow_opt["owm_language"], false ) . ' value="ua">'. __( 'Ukrainian', 'owm-weather' ) .'</option>
						<option ' . selected( 'vi', $wow_opt["owm_language"], false ) . ' value="vi">'. __( 'Vietnamese', 'owm-weather' ) .'</option>
						<option ' . selected( 'zu', $wow_opt["owm_language"], false ) . ' value="zu">'. __( 'Zulu', 'owm-weather' ) .'</option>
					</select>
				</p>
				<p class="misc subsection-title">
					'. __( 'Misc', 'owm-weather' ) .'
				</p>
				<p>
					<label for="wpowmweather_gtag_meta">
						<input type="checkbox" name="wpowmweather_gtag" id="wpowmweather_gtag_meta" value="yes" '. checked( $wow_opt["gtag"], 'yes', false ) .' />
							'. __( 'Google Tag Manager dataLayer?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_bypass_exclude_meta">
						<input type="checkbox" name="wpowmweather_bypass_exclude" id="wpowmweather_bypass_exclude_meta" value="yes" '. checked( $wow_opt["bypass_exclude"], 'yes', false ) .' />
							'. __( 'Exclude from System Settings and Parameter Bypass?', 'owm-weather' ) .'
					</label>
				</p>
			</div>
			<div id="tabs-2">
			    <p style="border: 2px solid;padding: 5px;">
    			    Select the information you would like to show on your weather shortcode.
			    </p>
				<p class="wow-dates subsection-title">
					'. __( 'Current weather', 'owm-weather' ) .'
				</p>
				<p>
					<label for="wpowmweather_current_city_name_meta">
						<input type="checkbox" name="wpowmweather_current_city_name" id="wpowmweather_current_city_name_meta" value="yes" '. checked( $wow_opt["current_city_name"], 'yes', false ) .' />
							'. __( 'Current weather city name?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_current_weather_symbol_meta">
						<input type="checkbox" name="wpowmweather_current_weather_symbol" id="wpowmweather_current_weather_symbol_meta" value="yes" '. checked( $wow_opt["current_weather_symbol"], 'yes', false ) .' />
							'. __( 'Current weather symbol?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_current_temperature_meta">
						<input type="checkbox" name="wpowmweather_current_temperature" id="wpowmweather_current_temperature_meta" value="yes" '. checked( $wow_opt["current_temperature"], 'yes', false ) .' />
							'. __( 'Current temperature?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_current_feels_like_meta">
						<input type="checkbox" name="wpowmweather_current_feels_like" id="wpowmweather_current_feels_like_meta" value="yes" '. checked( $wow_opt["current_feels_like"], 'yes', false ) .' />
							'. __( 'Current feels like temperature?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_current_weather_description_meta">
						<input type="checkbox" name="wpowmweather_current_weather_description" id="wpowmweather_current_weather_description_meta" value="yes" '. checked( $wow_opt["current_weather_description"], 'yes', false ) .' />
							'. __( 'Current weather short condition?', 'owm-weather' ) .'
					</label>
				</p>
				<p class="temperatures subsection-title">
					'. __( 'Temperatures', 'owm-weather' ) .'
				</p>
				<p>
					<label for="wpowmweather_display_temperature_unit_meta">
						<input type="checkbox" name="wpowmweather_display_temperature_unit" id="wpowmweather_display_temperature_unit_meta" value="yes" '. checked( $wow_opt["display_temperature_unit"], 'yes', false ) .' />
							'. __( 'Temperatures unit (C / F)?', 'owm-weather' ) .'
					</label>
				</p>
				<p class="wow-dates subsection-title">
					'. __( 'Date, Sunrise/Sunset and Moonrise/Moonset', 'owm-weather' ) .'
				</p>
				<p>
					<label for="wpowmweather_today_date_format_none_meta">
						<input type="radio" name="wpowmweather_today_date_format" id="wpowmweather_today_date_format_none_meta" value="none" '. checked( $wow_opt["today_date_format"], 'none', false ) .' />
							'. __( 'No date (default)?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_today_date_format_week_meta">
						<input type="radio" name="wpowmweather_today_date_format" id="wpowmweather_today_date_format_week_meta" value="day" '. checked( $wow_opt["today_date_format"], 'day', false ) .' />
							'. __( 'Day of the week (eg: Sunday)?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_today_date_format_calendar_meta">
						<input type="radio" name="wpowmweather_today_date_format" id="wpowmweather_today_date_format_calendar_meta" value="date" '. checked( $wow_opt["today_date_format"], 'date', false ) .' />
							'. __( 'Today\'s date (based on your WordPress General Settings)?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_sunrise_sunset_meta">
						<input type="checkbox" name="wpowmweather_sunrise_sunset" id="wpowmweather_sunrise_sunset_meta" value="yes" '. checked( $wow_opt["sunrise_sunset"], 'yes', false ) .' />
							'. __( 'Sunrise + sunset?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_moonrise_moonset_meta">
						<input type="checkbox" name="wpowmweather_moonrise_moonset" id="wpowmweather_moonrise_moonset_meta" value="yes" '. checked( $wow_opt["moonrise_moonset"], 'yes', false ) .' />
							'. __( 'Moonrise + moonset?', 'owm-weather' ) .'
					</label>
				</p>
				<p class="wow-misc subsection-title">
					'. __( 'Misc', 'owm-weather' ) .'
				</p>
				<p>
					<label for="wpowmweather_wind_meta">
						<input type="checkbox" name="wpowmweather_wind" id="wpowmweather_wind_meta" value="yes" '. checked( $wow_opt["wind"], 'yes', false ) .' />
							'. __( 'Wind?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_wind_unit_meta">'. __( 'Wind unit: ', 'owm-weather' ) .'</label>
					<select name="wpowmweather_wind_unit">
						<option ' . selected( '1', $wow_opt["wind_unit"], false ) . ' value="1">'. __( 'mi/h', 'owm-weather' ) .'</option>
						<option ' . selected( '2', $wow_opt["wind_unit"], false ) . ' value="2">'. __( 'm/s', 'owm-weather' ) .'</option>
						<option ' . selected( '3', $wow_opt["wind_unit"], false ) . ' value="3">'. __( 'km/h', 'owm-weather' ) .'</option>
						<option ' . selected( '4', $wow_opt["wind_unit"], false ) . ' value="4">'. __( 'kt', 'owm-weather' ) .'</option>
					</select>
				</p>
				<p>
					<label for="wpowmweather_humidity_meta">
						<input type="checkbox" name="wpowmweather_humidity" id="wpowmweather_humidity_meta" value="yes" '. checked( $wow_opt["humidity"], 'yes', false ) .' />
							'. __( 'Humidity?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_dew_point_meta">
						<input type="checkbox" name="wpowmweather_dew_point" id="wpowmweather_dew_point_meta" value="yes" '. checked( $wow_opt["dew_point"], 'yes', false ) .' />
							'. __( 'Dew Point?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_pressure_meta">
						<input type="checkbox" name="wpowmweather_pressure" id="wpowmweather_pressure_meta" value="yes" '. checked( $wow_opt["pressure"], 'yes', false ) .' />
							'. __( 'Pressure?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_cloudiness_meta">
						<input type="checkbox" name="wpowmweather_cloudiness" id="wpowmweather_cloudiness_meta" value="yes" '. checked( $wow_opt["cloudiness"], 'yes', false ) .' />
							'. __( 'Cloudiness?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_precipitation_meta">
						<input type="checkbox" name="wpowmweather_precipitation" id="wpowmweather_precipitation_meta" value="yes" '. checked( $wow_opt["precipitation"], 'yes', false ) .' />
							'. __( 'Precipitation?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_visibility_meta">
						<input type="checkbox" name="wpowmweather_visibility" id="wpowmweather_visibility_meta" value="yes" '. checked( $wow_opt["visibility"], 'yes', false ) .' />
							'. __( 'Visibility?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_uv_index_meta">
						<input type="checkbox" name="wpowmweather_uv_index" id="wpowmweather_uv_index_meta" value="yes" '. checked( $wow_opt["uv_index"], 'yes', false ) .' />
							'. __( 'UV Index?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_alerts_meta">
						<input type="checkbox" name="wpowmweather_alerts" id="wpowmweather_alerts_meta" value="yes" '. checked( $wow_opt["alerts"], 'yes', false ) .' />
							'. __( 'Alerts?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_alerts_button_color">'. __( 'Alert Button color', 'owm-weather' ) .'</label>
					<input name="wpowmweather_alerts_button_color" type="text" value="'. $wow_opt["alerts_button_color"] .'" class="wpowmweather_color_picker" />
				</p>
				<p class="hour subsection-title">
					'. __( 'Hourly Forecast', 'owm-weather' ) .'
				</p>
				<p>
					<label for="wpowmweather_hours_forecast_no_meta">'. __( 'How many hours?', 'owm-weather' ) .'</label>
					<select name="wpowmweather_hours_forecast_no">' . generate_hour_options($wow_opt["hours_forecast_no"]) . '</select>
					<br />
					<span class="dashicons dashicons-editor-help"></span><a href="'.admin_url('options-general.php').'" target="_blank">'.__('Make sure you have properly set the date of your site in WordPress settings.','owm-weather').'</a> or set a Custom timezone under Basic.
				</p>
				<p>
					<label for="wpowmweather_hours_time_icons_meta">
						<input type="checkbox" name="wpowmweather_hours_time_icons" id="wpowmweather_hours_time_icons_meta" value="yes" '. checked( $wow_opt["hours_time_icons"], 'yes', false ) .' />
							'. __( 'Display time icons?', 'owm-weather' ) .'
					</label>
				</p>
				<p class="forecast subsection-title">
					'. __( 'Daily Forecast', 'owm-weather' ) .'
				</p>
				<p>
					<label for="wpowmweather_forecast_no_meta">'. __( 'How many days?', 'owm-weather' ) .'</label>
					<select name="wpowmweather_forecast_no">
						<option ' . selected( '0', $wow_opt["days_forecast_no"], false ) . ' value="0">'. __( 'None', 'owm-weather' ) .'</option>
						<option ' . selected( '1', $wow_opt["days_forecast_no"], false ) . ' value="1">'. __( 'Today', 'owm-weather' ) .'</option>
						<option ' . selected( '2', $wow_opt["days_forecast_no"], false ) . ' value="2">'. __( 'Today + 1 day', 'owm-weather' ) .'</option>
						<option ' . selected( '3', $wow_opt["days_forecast_no"], false ) . ' value="3">'. __( 'Today + 2 days', 'owm-weather' ) .'</option>
						<option ' . selected( '4', $wow_opt["days_forecast_no"], false ) . ' value="4">'. __( 'Today + 3 days', 'owm-weather' ) .'</option>
						<option ' . selected( '5', $wow_opt["days_forecast_no"], false ) . ' value="5">'. __( 'Today + 4 days', 'owm-weather' ) .'</option>
						<option ' . selected( '6', $wow_opt["days_forecast_no"], false ) . ' value="6">'. __( 'Today + 5 days', 'owm-weather' ) .'</option>
						<option ' . selected( '7', $wow_opt["days_forecast_no"], false ) . ' value="7">'. __( 'Today + 6 days', 'owm-weather' ) .'</option>
						<option ' . selected( '8', $wow_opt["days_forecast_no"], false ) . ' value="8">'. __( 'Today + 7 days', 'owm-weather' ) .'</option>
					</select>
				</p>
				<p>
					<label for="wpowmweather_forecast_precipitations_meta">
						<input type="checkbox" name="wpowmweather_forecast_precipitations" id="wpowmweather_forecast_precipitations_meta" value="yes" '. checked( $wow_opt["forecast_precipitations"], 'yes', false ) .' />
							'. __( 'Forecast Precipitations?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_display_length_days_names_short_meta">
						<input type="radio" name="wpowmweather_display_length_days_names" id="wpowmweather_display_length_days_names_short_meta" value="short" '. checked( $wow_opt["display_length_days_names"], 'short', false ) .' />
							'. __( 'Short days names?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_display_length_days_names_normal_meta">
						<input type="radio" name="wpowmweather_display_length_days_names" id="wpowmweather_display_length_days_names_normal_meta" value="normal" '. checked( $wow_opt["display_length_days_names"], 'normal', false ) .' />
							'. __( 'Normal days names?', 'owm-weather' ) .'
					</label>
				</p>
				<p class="footer subsection-title">
					'. __( 'Footer', 'owm-weather' ) .'
				</p>
				<p>
					<label for="wpowmweather_owm_link_meta">
						<input type="checkbox" name="wpowmweather_owm_link" id="wpowmweather_owm_link_meta" value="yes" '. checked( $wow_opt["owm_link"], 'yes', false ) .' />
						'. __( 'Link to OpenWeatherMap?', 'owm-weather' ) .'
					</label>
				</p>

				<p>
					<label for="wpowmweather_last_update_meta">
						<input type="checkbox" name="wpowmweather_last_update" id="wpowmweather_last_update_meta" value="yes" '. checked( $wow_opt["last_update"], 'yes', false ) .' />

						'. __( 'Update date?', 'owm-weather' ) .'
					</label>
				</p>
			</div>
			<div id="tabs-3">
			    <p style="border: 2px solid;padding: 5px;">
    			    Select the layout styling for your weather shortcode.
			    </p>
				<p>
					<label for="template_meta">'. __( 'Template', 'owm-weather' ) .'</label>
					<select name="wpowmweather_template">
						<option ' . selected( 'Default', $wow_opt["template"], false ) . ' value="Default">'. __( 'Default', 'owm-weather' ) .'</option>
						<option ' . selected( 'card1', $wow_opt["template"], false ) . ' value="card1">'. __( 'Card 1', 'owm-weather' ) .'</option>
						<option ' . selected( 'card2', $wow_opt["template"], false ) . ' value="card2">'. __( 'Card 2', 'owm-weather' ) .'</option>
						<option ' . selected( 'chart1', $wow_opt["template"], false ) . ' value="chart1">'. __( 'Chart 1', 'owm-weather' ) .'</option>
						<option ' . selected( 'chart2', $wow_opt["template"], false ) . ' value="chart2">'. __( 'Chart 2', 'owm-weather' ) .'</option>
						<option ' . selected( 'table1', $wow_opt["template"], false ) . ' value="table1">'. __( 'Table 1', 'owm-weather' ) .'</option>
						<option ' . selected( 'table2', $wow_opt["template"], false ) . ' value="table2">'. __( 'Table 2', 'owm-weather' ) .'</option>
						<option ' . selected( 'slider1', $wow_opt["template"], false ) . ' value="slider1">'. __( 'Slider 1', 'owm-weather' ) .'</option>
						<option ' . selected( 'slider2', $wow_opt["template"], false ) . ' value="slider2">'. __( 'Slider 2', 'owm-weather' ) .'</option>
						<option ' . selected( 'custom1', $wow_opt["template"], false ) . ' value="custom1">'. __( 'Custom 1', 'owm-weather' ) .'</option>
						<option ' . selected( 'custom2', $wow_opt["template"], false ) . ' value="custom2">'. __( 'Custom 2', 'owm-weather' ) .'</option>
						<option ' . selected( 'debug', $wow_opt["template"], false ) . ' value="debug">'. __( 'Debug', 'owm-weather' ) .'</option>
					</select>
				</p>
				<p>
					<label for="font_meta">'. __( 'Font', 'owm-weather' ) .'</label>
					<select name="wpowmweather_font">
						<option ' . selected( 'Default', $wow_opt["font"], false ) . ' value="Default">'. __( 'Default', 'owm-weather' ) .'</option>
						<option ' . selected( 'Arvo', $wow_opt["font"], false ) . ' value="Arvo">'. __( 'Arvo', 'owm-weather' ) .'</option>
						<option ' . selected( 'Asap', $wow_opt["font"], false ) . ' value="Asap">'. __( 'Asap', 'owm-weather' ) .'</option>
						<option ' . selected( 'Bitter', $wow_opt["font"], false ) . ' value="Bitter">'. __( 'Bitter', 'owm-weather' ) .'</option>
						<option ' . selected( 'Droid Serif', $wow_opt["font"], false ) . ' value="Droid Serif">'. __( 'Droid Serif', 'owm-weather' ) .'</option>
						<option ' . selected( 'Exo 2', $wow_opt["font"], false ) . ' value="Exo 2">'. __( 'Exo 2', 'owm-weather' ) .'</option>
						<option ' . selected( 'Francois One', $wow_opt["font"], false ) . ' value="Francois One">'. __( 'Francois One', 'owm-weather' ) .'</option>
						<option ' . selected( 'Inconsolata', $wow_opt["font"], false ) . ' value="Inconsolata">'. __( 'Inconsolata', 'owm-weather' ) .'</option>
						<option ' . selected( 'Josefin Sans', $wow_opt["font"], false ) . ' value="Josefin Sans">'. __( 'Josefin Sans', 'owm-weather' ) .'</option>
						<option ' . selected( 'Lato', $wow_opt["font"], false ) . ' value="Lato">'. __( 'Lato', 'owm-weather' ) .'</option>
						<option ' . selected( 'Merriweather Sans', $wow_opt["font"], false ) . ' value="Merriweather Sans">'. __( 'Merriweather Sans', 'owm-weather' ) .'</option>
						<option ' . selected( 'Nunito', $wow_opt["font"], false ) . ' value="Nunito">'. __( 'Nunito', 'owm-weather' ) .'</option>
						<option ' . selected( 'Open Sans', $wow_opt["font"], false ) . ' value="Open Sans">'. __( 'Open Sans', 'owm-weather' ) .'</option>
						<option ' . selected( 'Oswald', $wow_opt["font"], false ) . ' value="Oswald">'. __( 'Oswald', 'owm-weather' ) .'</option>
						<option ' . selected( 'Pacifico', $wow_opt["font"], false ) . ' value="Pacifico">'. __( 'Pacifico', 'owm-weather' ) .'</option>
						<option ' . selected( 'Roboto', $wow_opt["font"], false ) . ' value="Roboto">'. __( 'Roboto', 'owm-weather' ) .'</option>
						<option ' . selected( 'Signika', $wow_opt["font"], false ) . ' value="Signika">'. __( 'Signika', 'owm-weather' ) .'</option>
						<option ' . selected( 'Source Sans Pro', $wow_opt["font"], false ) . ' value="Source Sans Pro">'. __( 'Source Sans Pro', 'owm-weather' ) .'</option>
						<option ' . selected( 'Tangerine', $wow_opt["font"], false ) . ' value="Tangerine">'. __( 'Tangerine', 'owm-weather' ) .'</option>
						<option ' . selected( 'Ubuntu', $wow_opt["font"], false ) . ' value="Ubuntu">'. __( 'Ubuntu', 'owm-weather' ) .'</option>
					</select>
				</p>
				<p>
					<label for="iconpack_meta">'. __( 'Icon Pack', 'owm-weather' ) .'</label>
					<select name="wpowmweather_iconpack">
						<option ' . selected( 'Climacons', $wow_opt["iconpack"], false ) . ' value="Climacons">'. __( 'Climacons', 'owm-weather' ) .'</option>
						<option ' . selected( 'OpenWeatherMap', $wow_opt["iconpack"], false ) . ' value="OpenWeatherMap">'. __( 'Open Weather Map', 'owm-weather' ) .'</option>
						<option ' . selected( 'WeatherIcons', $wow_opt["iconpack"], false ) . ' value="WeatherIcons">'. __( 'Weather Icons', 'owm-weather' ) .'</option>
						<option ' . selected( 'Forecast', $wow_opt["iconpack"], false ) . ' value="Forecast">'. __( 'Forecast', 'owm-weather' ) .'</option>
						<option ' . selected( 'Dripicons', $wow_opt["iconpack"], false ) . ' value="Dripicons">'. __( 'Dripicons', 'owm-weather' ) .'</option>
						<option ' . selected( 'Pixeden', $wow_opt["iconpack"], false ) . ' value="Pixeden">'. __( 'Pixeden', 'owm-weather' ) .'</option>
					</select>
				</p>
				<p class="misc subsection-title">
					'. __( 'Colors and Borders', 'owm-weather' ) .'
				</p>
				<p>
					<label for="wpowmweather_background_color">'. __( 'Background color', 'owm-weather' ) .'</label>
					<input name="wpowmweather_background_color" type="text" value="'. $wow_opt["background_color"] .'" class="wpowmweather_color_picker" />
				</p>
				<p>
					<label>'. __( 'Background image', 'owm-weather' ) .'</label>
                    <div class="background_image_preview_wrapper">
                    	<img id="background_image_preview" src="' . wp_get_attachment_url( ($wow_opt["background_image"] ?? '' ) ) . '" height="100px"' . (!empty($wow_opt["background_image"]) ? '' : ' style="display: none;"') . '>
                    </div>
                    <input id="select_background_image_button" type="button" class="button" value="' . __( 'Select image', 'owm-weather' ) . '" />
                    <input type="hidden" name="wpowmweather_background_image" id="background_image_attachment_id" value="' . ($wow_opt["background_image"] ?? '') . '">
                    <input id="clear_background_image_button" type="button" class="button" value="Clear" />
                </p>
				<p>
					<label for="wpowmweather_text_color">'. __( 'Text color', 'owm-weather' ) .'</label>
					<input name="wpowmweather_text_color" type="text" value="'. $wow_opt["text_color"] .'" class="wpowmweather_color_picker" />
				</p>
				<p>
					<label for="wpowmweather_border_color">'. __( 'Border color', 'owm-weather' ) .'</label>
					<input name="wpowmweather_border_color" type="text" value="'. $wow_opt["border_color"] .'" class="wpowmweather_color_picker" />
				</p>
				<p>
					<label for="wpowmweather_border_width">'. __( 'Border width (px)', 'owm-weather' ) .'</label>
					<input name="wpowmweather_border_width" type="number" min="0" value="'. $wow_opt["border_width"] .'" />
				</p>
				<p>
					<label for="wpowmweather_border_style">'. __( 'Border style', 'owm-weather' ) .'</label>
					<select name="wpowmweather_border_style">
						<option ' . selected( 'solid', $wow_opt["border_style"], false ) . ' value="solid">'. __( 'Solid', 'owm-weather' ) .'</option>
						<option ' . selected( 'dotted', $wow_opt["border_style"], false ) . ' value="dotted">'. __( 'Dotted', 'owm-weather' ) .'</option>
						<option ' . selected( 'dashed', $wow_opt["border_style"], false ) . ' value="dashed">'. __( 'Dashed', 'owm-weather' ) .'</option>
						<option ' . selected( 'double', $wow_opt["border_style"], false ) . ' value="double">'. __( 'Double', 'owm-weather' ) .'</option>
						<option ' . selected( 'groove', $wow_opt["border_style"], false ) . ' value="groove">'. __( 'Groove', 'owm-weather' ) .'</option>
						<option ' . selected( 'inset', $wow_opt["border_style"], false ) . ' value="inset">'. __( 'Inset', 'owm-weather' ) .'</option>
						<option ' . selected( 'outset', $wow_opt["border_style"], false ) . ' value="outset">'. __( 'Outset', 'owm-weather' ) .'</option>
						<option ' . selected( 'ridge', $wow_opt["border_style"], false ) . ' value="ridge">'. __( 'Ridge', 'owm-weather' ) .'</option>
					</select>
				</p>
				<p>
					<label for="wpowmweather_border_radius">'. __( 'Border radius (px)', 'owm-weather' ) .'</label>
					<input name="wpowmweather_border_radius" type="number" min="0" value="'. $wow_opt["border_radius"] .'" />
				</p>
				<p class="misc subsection-title">
					'. __( 'Misc', 'owm-weather' ) .'
				</p>
				<p>
					<label for="wpowmweather_disable_spinner_meta">
						<input type="checkbox" name="wpowmweather_disable_spinner" id="wpowmweather_disable_spinner_meta" value="yes" '. checked( $wow_opt["disable_spinner"], 'yes', false ) .' />
							'. __( 'Disable loading spinner?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_disable_anims_meta">
						<input type="checkbox" name="wpowmweather_disable_anims" id="wpowmweather_disable_anims_meta" value="yes" '. checked( $wow_opt["disable_anims"], 'yes', false ) .' />
							'. __( 'Disable animations for main icon?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="size_meta">'. __( 'Weather size?', 'owm-weather' ) .'</label>
					<select name="wpowmweather_size">
						<option ' . selected( 'small', $wow_opt["size"], false ) . ' value="small">'. __( 'Small', 'owm-weather' ) .'</option>
						<option ' . selected( 'medium', $wow_opt["size"], false ) . ' value="medium">'. __( 'Medium', 'owm-weather' ) .'</option>
						<option ' . selected( 'large', $wow_opt["size"], false ) . ' value="large">'. __( 'Large', 'owm-weather' ) .'</option>
					</select>
				</p>
				<p>
					<label for="wpowmweather_custom_css_meta">'. __( 'Custom CSS', 'owm-weather' ) .'</label>
					<textarea id="wpowmweather_custom_css_meta" name="wpowmweather_custom_css">'.$wow_opt["custom_css"].'</textarea>
				    <p>Preceed all CSS rules with .wow-' . $id . ' if you are planning to use more than one weather shortcode on a page.</p>
				</p>
				<p class="subsection-title">
					'. __( 'Tables', 'owm-weather' ) .'
				</p>
				<p>
					<label for="wpowmweather_table_background_color">'. __( 'Background color', 'owm-weather' ) .'</label>
					<input name="wpowmweather_table_background_color" type="text" value="'. $wow_opt["table_background_color"] .'" class="wpowmweather_color_picker" />
				</p>
				<p>
					<label for="wpowmweather_table_text_color">'. __( 'Text color', 'owm-weather' ) .'</label>
					<input name="wpowmweather_table_text_color" type="text" value="'. $wow_opt["table_text_color"] .'" class="wpowmweather_color_picker" />
				</p>
				<p>
					<label for="wpowmweather_table_border_color">'. __( 'Border color', 'owm-weather' ) .'</label>
					<input name="wpowmweather_table_border_color" type="text" value="'. $wow_opt["table_border_color"] .'" class="wpowmweather_color_picker" />
				</p>
				<p>
					<label for="wpowmweather_table_border_width">'. __( 'Border width (px)', 'owm-weather' ) .'</label>
					<input name="wpowmweather_table_border_width" type="number" min="0" value="'. $wow_opt["table_border_width"] .'" />
				</p>
				<p>
					<label for="wpowmweather_table_border_style">'. __( 'Border style', 'owm-weather' ) .'</label>
					<select name="wpowmweather_table_border_style">
						<option ' . selected( 'solid', $wow_opt["table_border_style"], false ) . ' value="solid">'. __( 'Solid', 'owm-weather' ) .'</option>
						<option ' . selected( 'dotted', $wow_opt["table_border_style"], false ) . ' value="dotted">'. __( 'Dotted', 'owm-weather' ) .'</option>
						<option ' . selected( 'dashed', $wow_opt["table_border_style"], false ) . ' value="dashed">'. __( 'Dashed', 'owm-weather' ) .'</option>
						<option ' . selected( 'double', $wow_opt["table_border_style"], false ) . ' value="double">'. __( 'Double', 'owm-weather' ) .'</option>
						<option ' . selected( 'groove', $wow_opt["table_border_style"], false ) . ' value="groove">'. __( 'Groove', 'owm-weather' ) .'</option>
						<option ' . selected( 'inset', $wow_opt["table_border_style"], false ) . ' value="inset">'. __( 'Inset', 'owm-weather' ) .'</option>
						<option ' . selected( 'outset', $wow_opt["table_border_style"], false ) . ' value="outset">'. __( 'Outset', 'owm-weather' ) .'</option>
						<option ' . selected( 'ridge', $wow_opt["table_border_style"], false ) . ' value="ridge">'. __( 'Ridge', 'owm-weather' ) .'</option>
					</select>
				</p>
				<p>
					<label for="wpowmweather_table_border_radius">'. __( 'Border radius (px)', 'owm-weather' ) .'</label>
					<input name="wpowmweather_table_border_radius" type="number" min="0" value="'. $wow_opt["table_border_radius"] .'" />
				</p>
				<p class="subsection-title">
					'. __( 'Charts', 'owm-weather' ) .'
				</p>
				<p>
					<label for="wpowmweather_chart_height_meta">'. __( 'Height (in px)', 'owm-weather' ) .'</label>
					<input id="wpowmweather_charet_height_meta" type="text" name="wpowmweather_chart_height" value="'.$wow_opt["chart_height"].'" />
				</p>
				<p>
					<label for="wpowmweather_chart_background_color">'. __( 'Background color', 'owm-weather' ) .'</label>
					<input name="wpowmweather_chart_background_color" type="text" value="'. $wow_opt["chart_background_color"] .'" class="wpowmweather_color_picker" />
				</p>
				<p>
					<label for="wpowmweather_chart_border_color">'. __( 'Border color', 'owm-weather' ) .'</label>
					<input name="wpowmweather_chart_border_color" type="text" value="'. $wow_opt["chart_border_color"] .'" class="wpowmweather_color_picker" />
				</p>
				<p>
					<label for="wpowmweather_chart_border_width">'. __( 'Border width (px)', 'owm-weather' ) .'</label>
					<input name="wpowmweather_chart_border_width" type="number" min="0" value="'. $wow_opt["chart_border_width"] .'" />
				</p>
				<p>
					<label for="wpowmweather_chart_border_style">'. __( 'Border style', 'owm-weather' ) .'</label>
					<select name="wpowmweather_chart_border_style">
						<option ' . selected( 'solid', $wow_opt["chart_border_style"], false ) . ' value="solid">'. __( 'Solid', 'owm-weather' ) .'</option>
						<option ' . selected( 'dotted', $wow_opt["chart_border_style"], false ) . ' value="dotted">'. __( 'Dotted', 'owm-weather' ) .'</option>
						<option ' . selected( 'dashed', $wow_opt["chart_border_style"], false ) . ' value="dashed">'. __( 'Dashed', 'owm-weather' ) .'</option>
						<option ' . selected( 'double', $wow_opt["chart_border_style"], false ) . ' value="double">'. __( 'Double', 'owm-weather' ) .'</option>
						<option ' . selected( 'groove', $wow_opt["chart_border_style"], false ) . ' value="groove">'. __( 'Groove', 'owm-weather' ) .'</option>
						<option ' . selected( 'inset', $wow_opt["chart_border_style"], false ) . ' value="inset">'. __( 'Inset', 'owm-weather' ) .'</option>
						<option ' . selected( 'outset', $wow_opt["chart_border_style"], false ) . ' value="outset">'. __( 'Outset', 'owm-weather' ) .'</option>
						<option ' . selected( 'ridge', $wow_opt["chart_border_style"], false ) . ' value="ridge">'. __( 'Ridge', 'owm-weather' ) .'</option>
					</select>
				</p>
				<p>
					<label for="wpowmweather_chart_border_radius">'. __( 'Border radius (px)', 'owm-weather' ) .'</label>
					<input name="wpowmweather_chart_border_radius" type="number" min="0" value="'. $wow_opt["chart_border_radius"] .'" />
				</p>
				<p>
					<label for="wpowmweather_chart_temperature_color">'. __( 'Temperature color', 'owm-weather' ) .'</label>
					<input name="wpowmweather_chart_temperature_color" type="text" value="'. $wow_opt["chart_temperature_color"] .'" class="wpowmweather_color_picker" />
				</p>
				<p>
					<label for="wpowmweather_chart_feels_like_color">'. __( 'Feels like color', 'owm-weather' ) .'</label>
					<input name="wpowmweather_chart_feels_like_color" type="text" value="'. $wow_opt["chart_feels_like_color"] .'" class="wpowmweather_color_picker" />
				</p>
				<p>
					<label for="wpowmweather_chart_dew_point_color">'. __( 'Dew point color', 'owm-weather' ) .'</label>
					<input name="wpowmweather_chart_dew_point_color" type="text" value="'. $wow_opt["chart_dew_point_color"] .'" class="wpowmweather_color_picker" />
				</p>
			</div>
			<div id="tabs-4">
			    <p style="border: 2px solid;padding: 5px;">
    			    Select the information and layout styling for the optional map on your weather shortcode.
			    </p>
				<p>
					<label for="wpowmweather_map_meta">
						<input type="checkbox" name="wpowmweather_map" id="wpowmweather_map_meta" value="yes" '. checked( $wow_opt["map"], 'yes', false ) .' />
							'. __( 'Display map?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_map_height_meta">'. __( 'Map height (in px)', 'owm-weather' ) .'</label>
					<input id="wpowmweather_map_height_meta" type="number" min="300" name="wpowmweather_map_height" value="'.$wow_opt["map_height"].'" />
				</p>
				<p>
					<label for="wpowmweather_map_opacity_meta">'. __( 'Layers opacity', 'owm-weather' ) .'</label>
					<select name="wpowmweather_map_opacity">
						<option ' . selected( '0', $wow_opt["map_opacity"], false ) . ' value="0">0%</option>
						<option ' . selected( '0.1', $wow_opt["map_opacity"], false ) . ' value="0.1">10%</option>
						<option ' . selected( '0.2', $wow_opt["map_opacity"], false ) . ' value="0.2">20%</option>
						<option ' . selected( '0.3', $wow_opt["map_opacity"], false ) . ' value="0.3">30%</option>
						<option ' . selected( '0.4', $wow_opt["map_opacity"], false ) . ' value="0.4">40%</option>
						<option ' . selected( '0.5', $wow_opt["map_opacity"], false ) . ' value="0.5">50%</option>
						<option ' . selected( '0.6', $wow_opt["map_opacity"], false ) . ' value="0.6">60%</option>
						<option ' . selected( '0.7', $wow_opt["map_opacity"], false ) . ' value="0.7">70%</option>
						<option ' . selected( '0.8', $wow_opt["map_opacity"], false ) . ' value="0.8">80%</option>
						<option ' . selected( '0.9', $wow_opt["map_opacity"], false ) . ' value="0.9">90%</option>
						<option ' . selected( '1', $wow_opt["map_opacity"], false ) . ' value="1">100%</option>
					</select>
				</p>
				<p>
					<label for="wpowmweather_map_zoom_meta">'. __( 'Zoom', 'owm-weather' ) .'</label>
					<select name="wpowmweather_map_zoom">
						<option ' . selected( '1', $wow_opt["map_zoom"], false ) . ' value="1">1</option>
						<option ' . selected( '2', $wow_opt["map_zoom"], false ) . ' value="2">2</option>
						<option ' . selected( '3', $wow_opt["map_zoom"], false ) . ' value="3">3</option>
						<option ' . selected( '4', $wow_opt["map_zoom"], false ) . ' value="4">4</option>
						<option ' . selected( '5', $wow_opt["map_zoom"], false ) . ' value="5">5</option>
						<option ' . selected( '6', $wow_opt["map_zoom"], false ) . ' value="6">6</option>
						<option ' . selected( '7', $wow_opt["map_zoom"], false ) . ' value="7">7</option>
						<option ' . selected( '8', $wow_opt["map_zoom"], false ) . ' value="8">8</option>
						<option ' . selected( '9', $wow_opt["map_zoom"], false ) . ' value="9">9</option>
						<option ' . selected( '10', $wow_opt["map_zoom"], false ) . ' value="10">10</option>
						<option ' . selected( '11', $wow_opt["map_zoom"], false ) . ' value="11">11</option>
						<option ' . selected( '12', $wow_opt["map_zoom"], false ) . ' value="12">12</option>
						<option ' . selected( '13', $wow_opt["map_zoom"], false ) . ' value="13">13</option>
						<option ' . selected( '14', $wow_opt["map_zoom"], false ) . ' value="14">14</option>
						<option ' . selected( '15', $wow_opt["map_zoom"], false ) . ' value="15">15</option>
						<option ' . selected( '16', $wow_opt["map_zoom"], false ) . ' value="16">16</option>
						<option ' . selected( '17', $wow_opt["map_zoom"], false ) . ' value="17">17</option>
						<option ' . selected( '18', $wow_opt["map_zoom"], false ) . ' value="18">18</option>
					</select>
				</p>
				<p>
					<label for="wpowmweather_map_disable_zoom_wheel_meta">
						<input type="checkbox" name="wpowmweather_map_disable_zoom_wheel" id="wpowmweather_map_disable_zoom_wheel_meta" value="yes" '. checked( $wow_opt["map_disable_zoom_wheel"], 'yes', false ) .' />
							'. __( 'Disable zoom wheel on map?', 'owm-weather' ) .'
					</label>
				</p>
				<p class="subsection-title">
					'. __( 'Layers', 'owm-weather' ) .'
				</p>
				<p>
					<label for="wpowmweather_map_stations_meta">
						<input type="checkbox" name="wpowmweather_map_stations" id="wpowmweather_map_stations_meta" value="yes" '. checked( $wow_opt["map_stations"], 'yes', false ) .' />
							'. __( 'Display stations?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_map_clouds_meta">
						<input type="checkbox" name="wpowmweather_map_clouds" id="wpowmweather_map_clouds_meta" value="yes" '. checked( $wow_opt["map_clouds"], 'yes', false ) .' />
							'. __( 'Display clouds?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_map_precipitation_meta">
						<input type="checkbox" name="wpowmweather_map_precipitation" id="wpowmweather_map_precipitation_meta" value="yes" '. checked( $wow_opt["map_precipitation"], 'yes', false ) .' />
							'. __( 'Display precipitation?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_map_snow_meta">
						<input type="checkbox" name="wpowmweather_map_snow" id="wpowmweather_map_snow_meta" value="yes" '. checked( $wow_opt["map_snow"], 'yes', false ) .' />
							'. __( 'Display snow?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_map_wind_meta">
						<input type="checkbox" name="wpowmweather_map_wind" id="wpowmweather_map_wind_meta" value="yes" '. checked( $wow_opt["map_wind"], 'yes', false ) .' />
							'. __( 'Display wind?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_map_temperature_meta">
						<input type="checkbox" name="wpowmweather_map_temperature" id="wpowmweather_map_temperature_meta" value="yes" '. checked( $wow_opt["map_temperature"], 'yes', false ) .' />
							'. __( 'Display temperature?', 'owm-weather' ) .'
					</label>
				</p>
				<p>
					<label for="wpowmweather_map_pressure_meta">
						<input type="checkbox" name="wpowmweather_map_pressure" id="wpowmweather_map_pressure_meta" value="yes" '. checked( $wow_opt["map_pressure"], 'yes', false ) .' />
							'. __( 'Display pressure?', 'owm-weather' ) .'
					</label>
				</p>
			</div>
	</div>';
}


add_action('save_post','wow_save_metabox');
function wow_save_metabox($post_id){
	if ( 'wow-weather' === get_post_type($post_id) || 'wow-weather' === get_post_type($post_id) ) {
        update_post_meta($post_id, '_wpowmweather_version', WP_OWM_WEATHER_VERSION);

		wow_save_metabox_field('city', $post_id);
		wow_save_metabox_field('city_name', $post_id);
		wow_save_metabox_field('id_owm', $post_id);
		wow_save_metabox_field('longitude', $post_id);
		wow_save_metabox_field('latitude', $post_id);
		wow_save_metabox_field('zip', $post_id);
		wow_save_metabox_field('country_code', $post_id);
		wow_save_metabox_field('unit', $post_id);
		wow_save_metabox_field('time_format', $post_id);
		wow_save_metabox_field('custom_timezone', $post_id);
		wow_save_metabox_field('today_date_format', $post_id);
		wow_save_metabox_field('wind_unit', $post_id);
		wow_save_metabox_field('hours_forecast_no', $post_id);
		wow_save_metabox_field('display_length_days_names', $post_id);
		wow_save_metabox_field('forecast_no', $post_id);
		wow_save_metabox_field('background_color', $post_id);
		wow_save_metabox_field('background_image', $post_id);
		wow_save_metabox_field('text_color', $post_id);
		wow_save_metabox_field('border_color', $post_id);
		wow_save_metabox_field('border_width', $post_id);
		wow_save_metabox_field('border_style', $post_id);
		wow_save_metabox_field('border_radius', $post_id);
		wow_save_metabox_field('custom_css', $post_id);
		wow_save_metabox_field('size', $post_id);
		wow_save_metabox_field('font', $post_id);
		wow_save_metabox_field('template', $post_id);
		wow_save_metabox_field('iconpack', $post_id);
		wow_save_metabox_field('map_height', $post_id);
		wow_save_metabox_field('map_opacity', $post_id);
		wow_save_metabox_field('map_zoom', $post_id);
		wow_save_metabox_field('owm_language', $post_id);
		wow_save_metabox_field('alerts_button_color', $post_id);
		wow_save_metabox_field('chart_height', $post_id);
		wow_save_metabox_field('chart_background_color', $post_id);
		wow_save_metabox_field('chart_border_color', $post_id);
		wow_save_metabox_field('chart_border_width', $post_id);
		wow_save_metabox_field('chart_border_style', $post_id);
		wow_save_metabox_field('chart_border_radius', $post_id);
		wow_save_metabox_field('chart_temperature_color', $post_id);
		wow_save_metabox_field('chart_feels_like_color', $post_id);
		wow_save_metabox_field('chart_dew_point_color', $post_id);
		wow_save_metabox_field('table_background_color', $post_id);
		wow_save_metabox_field('table_text_color', $post_id);
		wow_save_metabox_field('table_border_color', $post_id);
		wow_save_metabox_field('table_border_width', $post_id);
		wow_save_metabox_field('table_border_style', $post_id);
		wow_save_metabox_field('table_border_radius', $post_id);

		wow_save_metabox_field_yn('current_city_name', $post_id);
		wow_save_metabox_field_yn('current_weather_symbol', $post_id);
		wow_save_metabox_field_yn('current_weather_description', $post_id);
		wow_save_metabox_field_yn('display_temperature_unit', $post_id);
		wow_save_metabox_field_yn('sunrise_sunset', $post_id);
		wow_save_metabox_field_yn('moonrise_moonset', $post_id);
		wow_save_metabox_field_yn('wind', $post_id);
		wow_save_metabox_field_yn('humidity', $post_id);
		wow_save_metabox_field_yn('dew_point', $post_id);
		wow_save_metabox_field_yn('pressure', $post_id);
		wow_save_metabox_field_yn('cloudiness', $post_id);
		wow_save_metabox_field_yn('precipitation', $post_id);
		wow_save_metabox_field_yn('visibility', $post_id);
		wow_save_metabox_field_yn('uv_index', $post_id);
		wow_save_metabox_field_yn('current_temperature', $post_id);
		wow_save_metabox_field_yn('current_feels_like', $post_id);
		wow_save_metabox_field_yn('forecast_precipitations', $post_id);
		wow_save_metabox_field_yn('disable_spinner', $post_id);
		wow_save_metabox_field_yn('disable_anims', $post_id);
		wow_save_metabox_field_yn('owm_link', $post_id);
		wow_save_metabox_field_yn('last_update', $post_id);
		wow_save_metabox_field_yn('map_disable_zoom_wheel', $post_id);
		wow_save_metabox_field_yn('map_stations', $post_id);
		wow_save_metabox_field_yn('map_clouds', $post_id);
		wow_save_metabox_field_yn('map_precipitation', $post_id);
		wow_save_metabox_field_yn('map_snow', $post_id);
		wow_save_metabox_field_yn('map_wind', $post_id);
		wow_save_metabox_field_yn('map_temperature', $post_id);
		wow_save_metabox_field_yn('map_pressure', $post_id);
		wow_save_metabox_field_yn('gtag', $post_id);
		wow_save_metabox_field_yn('bypass_exclude', $post_id);
		wow_save_metabox_field_yn('map', $post_id);
		wow_save_metabox_field_yn('alerts', $post_id);
		wow_save_metabox_field_yn('hours_time_icons', $post_id);
	}
}

function wow_save_metabox_field($field, $post_id) {
	if (isset($_POST['wpowmweather_' . $field])){
        update_post_meta($post_id, '_wpowmweather_' . $field, esc_html(trim($_POST['wpowmweather_' . $field])));
	} else {
	    delete_post_meta($post_id, '_wpowmweather_' . $field);
	}
}

function wow_save_metabox_field_yn($field, $post_id) {
	if (isset($_POST['wpowmweather_' . $field])){
        update_post_meta($post_id, '_wpowmweather_' . $field, 'yes');
	} else {
	    delete_post_meta($post_id, '_wpowmweather_' . $field);
	}
}

add_action('save_post','wow_clear_cache_current');
function wow_clear_cache_current() {
	if ( 'wow-weather' === get_post_type() ) {
        global $wpdb;
		$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_myweather%' ");
		$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_myweather%' ");
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Function CSS/Display/Misc
///////////////////////////////////////////////////////////////////////////////////////////////////

function wow_css_color($attribute, $color) {
	if( !empty($color) ) {
		return $attribute.': '.$color.';';
	} else if ($attribute == 'fill') {
	    return $attribute.': currentColor;';
	}

	return '';
}
function wow_css_border($color, $width = '1', $style = 'solid', $radius_val = null) {
    $str = '';

	if( $color ) {
			$str .= 'border: '.$width.'px '.$style.' '. $color.';';
	}

	if( $radius_val ) {
			$str .= 'border-radius: '.$radius_val.'px;';
	}

	return $str;
}
function wow_css_background_image($id) {
	if( $id ) {
			return 'background-image: url(\''. wp_get_attachment_url($id) . '\');';
	}
	return '';
}
function wow_css_background_size($size) {
	if( $size ) {
			return 'background-size: '. $size.';';
	}
	return '';
}
function wow_css_background_position($horizontal, $vertical) {
	if( $horizontal && $vertical ) {
			return 'background-position: '.$horizontal.'% '.$vertical.'%;';
	}
	return "";
}
function wow_css_font_family($font) {
	if( $font != 'Default' ) {
			return 'font-family: \'' . $font . '\';';
	}
	return '';
}
function wow_css_height($height) {
	if( $height ) {
			return 'height: '. $height .'px;';
	}
	return '';
}


function wpowmweather_city_name($custom_city_name, $owm_city_name) {

	if (!empty($custom_city_name)) {
		return $custom_city_name;
	} else if(!empty($owm_city_name)) {
		return $owm_city_name;
	}

	return '';
}

function wow_display_today_sunrise_sunset($wpowmweather_sunrise_sunset, $sun_rise, $sun_set, $color, $elem) {
	if( $wpowmweather_sunrise_sunset == 'yes' ) {
		return '<div class="wow-sun-hours col">
					<' . $elem . ' class="wow-sunrise" title="'.__('Sunrise','owm-weather').'">'. sunrise($color) . '<span class="font-weight-bold">' . $sun_rise .'</span></' . $elem . '><' . $elem . ' class="wow-sunset" title="'.__('Sunset','owm-weather').'">'. sunset($color) . '<span class="font-weight-bold">' . $sun_set .'</span></' . $elem . '>
				</div>';
	}

	return '';
}

function wow_display_today_moonrise_moonset($wpowmweather_moonrise_moonset, $moon_rise, $moon_set, $color, $elem) {
	if( $wpowmweather_moonrise_moonset == 'yes' ) {
		return '<div class="wow-moon-hours col">
					<' . $elem . ' class="wow-moonrise" title="'.__('Moonrise','owm-weather').'">'. moonrise($color) . '<span class="font-weight-bold">' . $moon_rise .'</span></' . $elem . '><' . $elem . ' class="wow-moonset" title="'.__('Moonset','owm-weather').'">'. moonset($color) . '<span class="font-weight-bold">' . $moon_set .'</span></' . $elem . '>
				</div>';
	}

	return '';
}

function wow_webfont($bypass, $id) {
	$wow_webfont_value = wow_get_bypass($bypass, "font", $id);

    if ($wow_webfont_value != 'Default') {
        wp_register_style($wow_webfont_value, '//fonts.googleapis.com/css?family=' . str_replace(' ', '+', $wow_webfont_value) . ':400&display=swap' );
       	wp_enqueue_style($wow_webfont_value);
    }

	return $wow_webfont_value;
}

function wow_icons_pack($bypass, $id) {
	$iconpack = wow_get_bypass($bypass, "iconpack", $id);

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


add_shortcode("wow-weather", 'wow_get_my_weather_id');

function wow_get_my_weather_id($atts) {
    global $wow_params;
    
	require_once dirname( __FILE__ ) . '/owmweather-options.php';

    $atts = array_change_key_case((array)$atts, CASE_LOWER);
    $wow_params = shortcode_atts(array(
        "id"                            => false,
        "id_owm"                        => false,
        "longitude"                     => false,
        "latitude"                      => false,
        "zip"                           => false,
        "city"                          => false,
        "country_code"                  => false,
        "city_name"                     => false,
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
        "custom_timezone"               => false,
        "today_date_format"             => false,
        "wind_unit"                     => false,
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

	if (empty($wow_params["id"])) {
	    echo "<p>OWM Weather Error: wow-weather shortcode without 'id' parameter</p>";
	    return;
	}
    if (get_post_type($wow_params["id"]) != 'wow-weather') {
	    echo "<p>OWM Weather Error: id '".$wow_params["id"]."' is not type 'weather'</p>";
	    return;
    }

    if (get_post_status($wow_params["id"]) != 'publish') {
	    echo "<p>OWM Weather Error: id '".$wow_params["id"]."' is not published</p>";
	    return;
    }

    $wow_opt = [];
	$wow_opt["id"] = $wow_params["id"];
    $wow_opt["bypass_exclude"]      = get_post_meta($wow_opt["id"],'_wpowmweather_bypass_exclude',true);
    $bypass = $wow_opt["bypass_exclude"] != 'yes';
	$wow_opt["disable_anims"]	    = wow_get_bypass_yn($bypass, "disable_anims", $wow_opt["id"]);
	$wow_opt["map"]           		= wow_get_bypass_yn($bypass, "map", $wow_opt["id"]);
	$wow_opt["template"]  			= wow_get_bypass($bypass, "template", $wow_opt["id"]);
	$wow_opt["disable_spinner"] 	= wow_get_bypass_yn($bypass, "disable_spinner", $wow_opt["id"]);

	wow_webfont($bypass, $wow_opt["id"]);
	wow_icons_pack($bypass, $wow_opt["id"]);

	if ($wow_opt["template"] == 'slider1' || $wow_opt["template"] == 'slider2' ) {
		wp_enqueue_script('jquery');
		wp_enqueue_script('wow-flexslider-js');
		wp_enqueue_style('wow-flexslider-css');
	} else if ($wow_opt["template"] == 'chart1' || $wow_opt["template"] == 'chart2' || $wow_opt["template"] == 'debug') {
		wp_enqueue_script('chart-js');
	}

    if (wow_get_admin_bypass('wow_advanced_disable_modal_js') != 'yes') {
		wp_enqueue_script('jquery');
		wp_enqueue_style('bootstrap-css');
		wp_enqueue_script('bootstrap-js');
		wp_enqueue_script('popper-js');
    }

	if ($wow_opt["disable_anims"] != 'yes') {
    	wp_enqueue_style('owmweather-anim-css');
    }

    //Map
      if ($wow_opt["map"] == 'yes') {
    	wp_register_script("leaflet-js", plugins_url('js/leaflet.js', __FILE__), "1.0", false);

    	wp_register_script("leaflet-openweathermap-js", plugins_url('js/leaflet-openweathermap.js', __FILE__), "1.0", false);

      	wp_register_style("leaflet-openweathermap-css", plugins_url('css/leaflet-openweathermap.css', __FILE__));
      	wp_register_style("leaflet-css", plugins_url('css/leaflet.css', __FILE__));

      	wp_enqueue_script("leaflet-js");

      	wp_enqueue_script("leaflet-openweathermap-js");

      	wp_enqueue_style("leaflet-openweathermap-css");
      	wp_enqueue_style("leaflet-css");
    }

    $data_attributes = [];
    foreach($wow_params as $key => $value) {
        if ($value !== false) {
            $data_attributes[] = "data-" . $key . '="' . $value . '"';
        }
    }

    $div_id = wow_unique_id("wow-weather-id-".$wow_opt["id"]);
    $data_attributes[] = "data-weather_id=".$div_id;

	$ret = '<div id="'.$div_id.'" class="wow-weather-id" ' . join(' ', $data_attributes) .'>';
	if ($wow_opt["disable_spinner"] != 'yes') {
	    $ret .= '<div class="wow-loading-spinner"><img src="'. plugins_url( 'img/owmloading.gif', __FILE__) . '" alt="loader"/></div>';
	}
	$ret .= '</div>';

	return $ret;
}

add_action( 'wp_ajax_wow_get_my_weather', 'wow_get_my_weather' );
add_action( 'wp_ajax_nopriv_wow_get_my_weather', 'wow_get_my_weather' );

function wow_get_my_weather($attr) {
    global $wow_params;
    $wow_params = $_POST['wow_params'];

	check_ajax_referer( 'wow_get_weather_nonce', $_POST['_ajax_nonce'], true );

	if ( isset( $wow_params['id'] ) ) {
		$id = $wow_params['id'];

		require_once dirname( __FILE__ ) . '/owmweather-options.php';
		require_once dirname( __FILE__ ) . '/owmweather-anim.php';
		require_once dirname( __FILE__ ) . '/owmweather-icons.php';

        $wow_opt                                    = [];
	  	$wow_opt["id"] 								= $id;
	  	$wow_opt["main_weather_div"]				= $wow_params["weather_id"];
        $wow_opt["container_weather_div"]           = wow_unique_id('wow-weather-container-'.$wow_opt["id"]);
        $wow_opt["main_map_div"]                    = wow_unique_id('wow-map-id-'.$wow_opt["id"]);
        $wow_opt["container_map_div"]               = wow_unique_id('wow-map-container-'.$wow_opt["id"]);
	  	$wow_opt["counter"] 						= $_POST['counter'];
        $wow_opt["bypass_exclude"]                  = get_post_meta($wow_opt["id"],'_wpowmweather_bypass_exclude',true);
        $bypass                                     = ($wow_opt["bypass_exclude"] != 'yes');
	  	$wow_opt["id_owm"]          				= wow_get_bypass($bypass, "id_owm");
	  	$wow_opt["longitude"]          				= wow_get_bypass($bypass, "longitude");
	  	$wow_opt["latitude"]          				= wow_get_bypass($bypass, "latitude");
	  	$wow_opt["zip"]          				    = wow_get_bypass($bypass, "zip");
	  	$wow_opt["city"]                			= str_replace(' ', '+', strtolower(wow_get_bypass($bypass, "city")));
		$wow_opt["country_code"]            		= str_replace(' ', '+', wow_get_bypass($bypass, "country_code"));
		$wow_opt["custom_city_name"]       			= wow_get_bypass($bypass, "city_name");
		$wow_opt["temperature_unit"]       			= wow_get_bypass($bypass, "unit");
    	$wow_opt["map"]           		            = wow_get_bypass_yn($bypass, "map");
		$wow_opt["map_height"]            			= wow_get_bypass($bypass, "map_height");
		$wow_opt["map_opacity"]          			= wow_get_bypass($bypass, "map_opacity");
		$wow_opt["map_zoom"]              			= wow_get_bypass($bypass, "map_zoom");
		$wow_opt["map_disable_zoom_wheel"]     		= wow_get_bypass_yn($bypass, "map_disable_zoom_wheel");
		$wow_opt["map_stations"]            		= wow_get_bypass_yn($bypass, "map_stations");
		$wow_opt["map_clouds"]            			= wow_get_bypass_yn($bypass, "map_clouds");
		$wow_opt["map_precipitation"]         		= wow_get_bypass_yn($bypass, "map_precipitation");
		$wow_opt["map_snow"]              			= wow_get_bypass_yn($bypass, "map_snow");
		$wow_opt["map_wind"]              			= wow_get_bypass_yn($bypass, "map_wind");
		$wow_opt["map_temperature"]         		= wow_get_bypass_yn($bypass, "map_temperature");
		$wow_opt["map_pressure"]            		= wow_get_bypass_yn($bypass, "map_pressure");
		$wow_opt["border_color"]             		= wow_get_bypass($bypass, "border_color");
		$wow_opt["border_width"]             		= getBypassDefault($bypass, 'border_width', $wow_opt["border_color"] == '' ? '0' : '1');
		$wow_opt["border_style"]             		= wow_get_bypass($bypass, "border_style");
		$wow_opt["border_radius"]             		= wow_get_bypass($bypass, "border_radius");
		$wow_opt["background_color"]   	          	= wow_get_bypass($bypass, "background_color");
		$wow_opt["background_image"]   	          	= wow_get_bypass($bypass, "background_image");
		$wow_opt["text_color"]         		        = wow_get_bypass($bypass, "text_color");
		$wow_opt["time_format"]          			= wow_get_bypass($bypass, "time_format");
		$wow_opt["sunrise_sunset"]          		= wow_get_bypass_yn($bypass, "sunrise_sunset");
		$wow_opt["moonrise_moonset"]         		= wow_get_bypass_yn($bypass, "moonrise_moonset");
		$wow_opt["display_temperature_unit"]   		= wow_get_bypass_yn($bypass, "display_temperature_unit");
		$wow_opt["display_length_days_names"]     	= wow_get_bypass($bypass, "display_length_days_names");
		$wow_opt["cache_time"]      	            = wow_get_admin_cache_time();
		$wow_opt["disable_cache"]       	    	= wow_get_admin_disable_cache();
		$wow_opt["api_key"]           			    = wow_get_admin_api_key();
		$wow_opt["owm_link"]          	    	    = wow_get_bypass_yn($bypass, "owm_link");
		$wow_opt["last_update"]       		        = wow_get_bypass_yn($bypass, "last_update");
		$wow_opt["hours_forecast_no"]  				= wow_get_bypass($bypass, "hours_forecast_no");
		$wow_opt["hours_time_icons"]  				= wow_get_bypass($bypass, "hours_time_icons");
		$wow_opt["days_forecast_no"]     			= wow_get_bypass($bypass, "forecast_no");
		$wow_opt["forecast_precipitations"]			= wow_get_bypass_yn($bypass, "forecast_precipitations");
		$wow_opt["custom_timezone"]			    	= wow_get_bypass($bypass, "custom_timezone");
		$wow_opt["today_date_format"]      			= wow_get_bypass($bypass, "today_date_format");
		$wow_opt["alerts"]                          = wow_get_bypass_yn($bypass, "alerts");
		$wow_opt["alerts_button_color"]             = wow_get_bypass($bypass, "alerts_button_color");
		$wow_opt["owm_language"]                    = wow_get_bypass($bypass, "owm_language");
    	$wow_opt["font"]    			            = wow_get_bypass($bypass, "font");
    	$wow_opt["iconpack"]  			            = wow_get_bypass($bypass, "iconpack");
	    $wow_opt["template"]  			            = wow_get_bypass($bypass, "template");
        $wow_opt["gtag"]                            = get_post_meta($wow_opt["id"],'_wpowmweather_gtag',true);
		$wow_opt["custom_css"]                      = wow_get_bypass($bypass, 'custom_css');
		$wow_opt["current_weather_symbol"]  		= wow_get_bypass_yn($bypass, "current_weather_symbol");
		$wow_opt["current_city_name"]        		= wow_get_bypass_yn($bypass, "current_city_name");
		$wow_opt["current_weather_description"]     = wow_get_bypass_yn($bypass, "current_weather_description");
		$wow_opt["wind"]          					= wow_get_bypass_yn($bypass, "wind");
        $wow_opt["wind_unit"]                       = wow_get_bypass($bypass, "wind_unit");
        $wow_opt["humidity"]        				= wow_get_bypass_yn($bypass, "humidity");
        $wow_opt["dew_point"]        				= wow_get_bypass_yn($bypass, "dew_point");
		$wow_opt["pressure"]        				= wow_get_bypass_yn($bypass, "pressure");
		$wow_opt["cloudiness"]      				= wow_get_bypass_yn($bypass, "cloudiness");
		$wow_opt["precipitation"]     				= wow_get_bypass_yn($bypass, "precipitation");
		$wow_opt["visibility"]     				    = wow_get_bypass_yn($bypass, "visibility");
		$wow_opt["uv_index"]     				    = wow_get_bypass_yn($bypass, "uv_index");
		$wow_opt["current_temperature"] 			= wow_get_bypass_yn($bypass, "current_temperature");
		$wow_opt["current_feels_like"] 			    = wow_get_bypass_yn($bypass, "current_feels_like");
		$wow_opt["size"]          					= wow_get_bypass($bypass, "size");
        $wow_opt["disable_spinner"]                 = wow_get_bypass_yn($bypass, "disable_spinner");
        $wow_opt["disable_anims"]                   = wow_get_bypass_yn($bypass, "disable_anims");

		//$wow_opt["image_bg_cover"]					= get_post_meta($wow_opt["id"],'_wpowmweather_image_bg_cover',true); // bugbug
		//$wow_opt["image_bg_position_horizontal_e"]	= null; // bugbug
		//$wow_opt["image_bg_position_vertical_e"]	= null; // bugbug

    	$wow_opt["chart_height"]	    		    = getBypassDefault($bypass, 'chart_height', '400');
    	$wow_opt["chart_background_color"]		    = wow_get_bypass($bypass, 'chart_background_color');
    	$wow_opt["chart_border_color"]	            = wow_get_bypass($bypass, 'chart_border_color');
    	$wow_opt["chart_border_width"]	            = getBypassDefault($bypass, 'chart_border_width', $wow_opt["chart_border_color"] == '' ? '0' : '1');
    	$wow_opt["chart_border_style"]	            = getBypassDefault($bypass, 'chart_border_style', "solid");
    	$wow_opt["chart_border_radius"]	            = getBypassDefault($bypass, 'chart_border_radius', "0");
    	$wow_opt["chart_temperature_color"]	        = wow_get_bypass($bypass, 'chart_temperature_color');
    	$wow_opt["chart_feels_like_color"]	        = wow_get_bypass($bypass, 'chart_feels_like_color');
    	$wow_opt["chart_dew_point_color"]	        = wow_get_bypass($bypass, 'chart_dew_point_color');
    	$wow_opt["table_background_color"]	        = wow_get_bypass($bypass, 'table_background_color');
    	$wow_opt["table_text_color"]	            = wow_get_bypass($bypass, 'table_text_color');
    	$wow_opt["table_border_color"]	            = wow_get_bypass($bypass, 'table_border_color');
    	$wow_opt["table_border_width"]	            = getBypassDefault($bypass, 'table_border_width', $wow_opt["table_border_color"] == '' ? '0' : '1');
    	$wow_opt["table_border_style"]	            = getBypassDefault($bypass, 'table_border_style', "solid");
    	$wow_opt["table_border_radius"]	            = getBypassDefault($bypass, 'table_border_radius', "0");

        esc_html_all($wow_opt);

        //JSON : Current weather
    	//No CACHE
		if ($wow_opt["owm_language"] == 'Default') {
		    $wow_opt["owm_language"] = 'en';
		}

       	if ($wow_opt["id_owm"] !='') {
       	    $query = "id=".$wow_opt["id_owm"];
       	} else if ($wow_opt["longitude"] != '' && $wow_opt["latitude"] != '') {
       	    $query = "lat=".$wow_opt["latitude"]."&lon=".$wow_opt["longitude"];
       	} else if ($wow_opt["zip"] != '') {
       	    $query = "zip=".$wow_opt["zip"];
       	} else  if ($wow_opt["city"] != '' && $wow_opt["country_code"] != '') {
       	    $query = "q=".$wow_opt["city"].",".$wow_opt["country_code"];
       	} else if (($ipData = IPtoLocation())){
            $wow_opt["latitude"] = esc_html($ipData["data"]["geo"]["latitude"]);
            $wow_opt["longitude"] =  esc_html($ipData["data"]["geo"]["longitude"]);
       	    $query = "lat=".$wow_opt["latitude"]."&lon=".$wow_opt["longitude"];
       	} else {
       	    return;
       	}

        if ($wow_opt["disable_cache"] == 'yes') {
			$myweather_current_url = wp_remote_retrieve_body(wp_remote_get("https://api.openweathermap.org/data/2.5/weather?".$query."&mode=json&lang=".$wow_opt["owm_language"]."&units=".$wow_opt["temperature_unit"]."&APPID=".$wow_opt["api_key"], array( 'timeout' => 10)));

          	$myweather_current = json_decode($myweather_current_url);
          	if (!$myweather_current) {
          		_e('Unable to retrieve weather data','owm-weather');
			}
        } else {
            $transient_key = 'myweather_current_' . $query . $wow_opt["owm_language"] . $wow_opt["temperature_unit"];
           	if (false === ( $myweather_current = get_transient( $transient_key ) ) ) {
               	$myweather_current_body = wp_remote_retrieve_body(wp_remote_get("https://api.openweathermap.org/data/2.5/weather?".$query."&mode=json&lang=".$wow_opt["owm_language"]."&units=".$wow_opt["temperature_unit"]."&APPID=".$wow_opt["api_key"], array( 'timeout' => 10)));
            	$myweather_current = json_decode($myweather_current_body);
                if ($myweather_current) {
			    	$rc = set_transient( $transient_key, $myweather_current, $wow_opt["cache_time"] * MINUTE_IN_SECONDS );
    			}
            }
        }

        if (!empty($myweather_current->cod) && $myweather_current->cod != "200") {
    	  	$response = array();
    	  	$response['weather'] = $wow_params["weather_id"];
    	  	$response['html'] = "<p>OWM Weather id '" . $wow_opt["id"] . "': OWM Error " . $myweather_current->cod . (!empty($myweather_current->message) ? " (" . $myweather_current->message . ")" : "") . "</p>";
    		wp_send_json_success($response);
    		return;
        }

		$wpowmweather_time_php = $wow_opt["time_format"] =='12' ? 'h:i A' : 'H:i';
		$wpowmweather_hours_php = $wow_opt["time_format"] =='12' ? 'h A' : 'H';
        $utc_time_wp = $wow_opt["custom_timezone"] != 'Default' ? intval($wow_opt["custom_timezone"]) * 60 : get_option('gmt_offset') * 60;

        $wow_data = [];
        $wow_data["name"] = $myweather_current->name ?? null;
        $wow_data["id"] = $myweather_current->id ?? null;
        $wow_data["timezone"] = $myweather_current->timezone ?? null;
        $wow_data["timestamp"] = $myweather_current->dt ? $myweather_current->dt + (60 * $utc_time_wp) : null;
        $wow_data["last_update"] = __('Last updated: ','owm-weather').date($wpowmweather_time_php, $wow_data["timestamp"]);
        $wow_data["latitude"] = $myweather_current->coord->lat ?? null;
        $wow_data["longitude"] = $myweather_current->coord->lon ?? null;
        $wow_data["condition_id"] = $myweather_current->weather[0]->id ?? null;
        $wow_data["category"] = $myweather_current->weather[0]->main ?? null;
        $wow_data["description"] = $myweather_current->weather[0]->description ?? null;
        $wow_data["wind_speed_unit"] = getWindSpeedUnit($wow_opt["temperature_unit"], $wow_opt["wind_unit"]);
        $wow_data["wind_speed"] = getConvertedWindSpeed($myweather_current->wind->speed, $wow_opt["temperature_unit"], $wow_opt["wind_unit"]) . ' ' . $wow_data["wind_speed_unit"];
        $wow_data["wind_degrees"] = $myweather_current->wind->deg ?? null;
        $wow_data["wind_direction"] = getWindDirection($myweather_current->wind->deg);
        $wow_data["wind_gust"] = isset($myweather_current->wind->gust) ? getConvertedWindSpeed($myweather_current->wind->gust, $wow_opt["temperature_unit"], $wow_opt["wind_unit"])  . ' ' . $wow_data["wind_speed_unit"] : null;
        $wow_data["temperature"] = $myweather_current->main->temp ? ceil($myweather_current->main->temp) : null;
        $wow_data["temperature_unit_character"] = $wow_opt["temperature_unit"] == 'metric' ? 'C' : 'F';
        $wow_data["feels_like"] = $myweather_current->main->feels_like ? ceil($myweather_current->main->feels_like) : null;
        $wow_data["humidity"] = $myweather_current->main->humidity ? $myweather_current->main->humidity . '%' : null;
        $wow_data["pressure_unit"] = $wow_opt["temperature_unit"] == 'imperial' ? __('in','owm-weather') : __('hPa','owm-weather');
        $wow_data["pressure"] = converthp2iom($wow_opt["temperature_unit"], $myweather_current->main->pressure) . ' ' . $wow_data["pressure_unit"];
        $wow_data["cloudiness"] = $myweather_current->clouds->all ? $myweather_current->clouds->all . '%' : null;
        $wow_data["precipitation_unit"] = $wow_opt["temperature_unit"] == 'imperial' ? __('in','owm-weather') : __('mm','owm-weather');
        $wow_data["rain_1h"] = getConvertedPrecipitation($wow_opt["temperature_unit"], $myweather_current->rain->{"1h"} ?? 0) . ' ' . $wow_data["precipitation_unit"];
        $wow_data["rain_3h"] = getConvertedPrecipitation($wow_opt["temperature_unit"], $myweather_current->rain->{"3h"} ?? 0) . ' ' . $wow_data["precipitation_unit"];
        $wow_data["snow_1h"] = getConvertedPrecipitation($wow_opt["temperature_unit"], $myweather_current->snow->{"1h"} ?? 0) . ' ' . $wow_data["precipitation_unit"];
        $wow_data["snow_3h"] = getConvertedPrecipitation($wow_opt["temperature_unit"], $myweather_current->snow->{"3h"} ?? 0) . ' ' . $wow_data["precipitation_unit"];
        $wow_data["precipitation_1h"] = $wow_data["rain_1h"] ?? 0 + $wow_data["snow_1h"] ?? 0 . ' ' . $wow_data["precipitation_unit"];
        $wow_data["precipitation_3h"] = $wow_data["rain_3h"] ?? 0 + $wow_data["snow_3h"] ?? 0 . ' ' . $wow_data["precipitation_unit"];;
        $wow_data["visibility"] = getConvertedDistance($wow_opt["temperature_unit"], $myweather_current->visibility);
        $wow_data["owm_link"] = '<a href="https://openweathermap.org/city/'.($myweather_current->id ?? "").'" target="_blank" title="'.__('Full weather on OpenWeatherMap','owm-weather').'">'.__('Full weather','owm-weather').'</a>';
        $wow_data["timestamp_sunrise"] = $myweather_current->sys->sunrise ? $myweather_current->sys->sunrise + (60 * $utc_time_wp) : null;
        $wow_data["timestamp_sunset"] = $myweather_current->sys->sunset ? $myweather_current->sys->sunset + (60 * $utc_time_wp) : null;
        $wow_data["sunrise"] = (string)date($wpowmweather_time_php, $wow_data["timestamp_sunrise"]);
        $wow_data["sunset"] = (string)date($wpowmweather_time_php, $wow_data["timestamp_sunset"]);
        $wow_data["moonrise"] = '';
        $wow_data["moonset"] = '';

		if ($wow_opt["today_date_format"] == 'date') {
			$today_day =  date_i18n( get_option( 'date_format' ) );
		} else if ($wow_opt["today_date_format"] == 'day') {
			switch (strftime("%w", $wow_data["timestamp"])) {
		        case "0":
		          	$today_day      = __('Sunday','owm-weather');
		          	break;
		        case "1":
		          	$today_day      = __('Monday','owm-weather');
		          	break;
		        case "2":
		        	$today_day      = __('Tuesday','owm-weather');
		          	break;
		        case "3":
		        	$today_day      = __('Wednesday','owm-weather');
		          	break;
		        case "4":
		        	$today_day      = __('Thursday','owm-weather');
		          	break;
		        case "5":
		        	$today_day      = __('Friday','owm-weather');
		          	break;
		        case "6":
		        	$today_day      = __('Saturday','owm-weather');
		          	break;
		  		}
		} else {
			$today_day ='';
		}

        //JSON : Onecall forecast weather (relies on lat and lon from current weather call)
        // bugbug check uvi and dew_point flag
        if($wow_opt["hours_forecast_no"] > 0 || $wow_opt["days_forecast_no"] > 0 || $wow_opt["alerts"] == 'yes' || $wow_opt["moonrise_moonset"] == "yes" || $wow_opt["dew_point"] == "yes" || $wow_opt["uv_index"] == "yes") {
    		if ($wow_opt["disable_cache"] == 'yes') {
   				$myweather_url = wp_remote_retrieve_body(wp_remote_get("https://api.openweathermap.org/data/2.5/onecall?lon=".$wow_data["longitude"]."&lat=".$wow_data["latitude"]."&mode=json&exclude=minutely&units=".$wow_opt["temperature_unit"]."&APPID=".$wow_opt["api_key"], array( 'timeout' => 10)));
          		$myweather = json_decode($myweather_url);
          		if (!$myweather) {
          			_e('Unable to retrieve weather data','owm-weather');
				}
        	} else {
        	    $transient_key = 'myweather_' . $wow_data["longitude"] . $wow_data["latitude"] . $wow_opt["temperature_unit"];
              	if (false === ( $myweather = get_transient( $transient_key ) ) ) {
                	$myweather_body = wp_remote_retrieve_body(wp_remote_get("https://api.openweathermap.org/data/2.5/onecall?lon=".$wow_data["longitude"]."&lat=".$wow_data["latitude"]."&mode=json&exclude=minutely&units=".$wow_opt["temperature_unit"]."&APPID=".$wow_opt["api_key"], array( 'timeout' => 10)));
                	$myweather = json_decode($myweather_body);
	                if ($myweather) {
	                	 $rc = set_transient( $transient_key, $myweather, $wow_opt["cache_time"] * MINUTE_IN_SECONDS );
                	}
	            }
		    }
		}

        if (!empty($myweather->cod) && $myweather->cod != "200") {
    	  	$response = array();
    	  	$response['weather'] = $wow_params["weather_id"];
    	  	$response['html'] = "<p>OWM Weather id '" . $wow_opt["id"] . "': OWM Error " . $myweather->cod . (!empty($myweather->message) ? " (" . $myweather->message . ")" : "") . "</p>";
    		wp_send_json_success($response);
    		return;
        }

        if ($wow_opt["dew_point"] == "yes" || $wow_opt["uv_index"] == "yes") {
            $wow_data["uv_index"] = $myweather->current->uvi ?? null;
            $wow_data["dew_point"] = $myweather->current->dew_point ? ceil($myweather->current->dew_point) : null;
        }

		//Days loop
		if ($wow_opt["days_forecast_no"] > 0 || $wow_opt["hours_forecast_no"] > 0 || $wow_opt["moonrise_moonset"] == "yes") {
			foreach ($myweather->daily as $i => $value) {
		    	switch (strftime('%w', $myweather->daily[$i]->dt + (60 * $utc_time_wp))) {
			    	case "0":
			      		$wow_data["daily"][$i]["day"] = $wow_opt["display_length_days_names"] == 'short' ? __('Sun','owm-weather') : __('Sunday','owm-weather');
			      		break;
			    	case "1":
			      		$wow_data["daily"][$i]["day"] = $wow_opt["display_length_days_names"] == 'short' ? __('Mon','owm-weather') : __('Monday','owm-weather');
			      		break;
			    	case "2":
			    		$wow_data["daily"][$i]["day"] = $wow_opt["display_length_days_names"] == 'short' ? __('Tue','owm-weather') : __('Tuesday','owm-weather');
			      		break;
			    	case "3":
			    		$wow_data["daily"][$i]["day"] = $wow_opt["display_length_days_names"] == 'short' ? __('Wed','owm-weather') : __('Wednesday','owm-weather');
			      		break;
			    	case "4":
			    		$wow_data["daily"][$i]["day"] = $wow_opt["display_length_days_names"] == 'short' ? __('Thu','owm-weather') : __('Thursday','owm-weather');
			      		break;
			    	case "5":
			    		$wow_data["daily"][$i]["day"] = $wow_opt["display_length_days_names"] == 'short' ? __('Fri','owm-weather') : __('Friday','owm-weather');
			      		break;
			    	case "6":
			    		$wow_data["daily"][$i]["day"] = $wow_opt["display_length_days_names"] == 'short' ? __('Sat','owm-weather') : __('Saturday','owm-weather');
				      	break;
			    }

                $wow_data["daily"][$i]["timestamp"] = $value->dt ? $value->dt + (60 * $utc_time_wp) : null;
                $wow_data["daily"][$i]["timestamp_sunrise"] = $value->sunrise ? $value->sunrise + (60 * $utc_time_wp) : null;
                $wow_data["daily"][$i]["timestamp_sunset"] = $value->sunset ? $value->sunset + (60 * $utc_time_wp) : null;
                $wow_data["daily"][$i]["sunrise"] = (string)date($wpowmweather_time_php, $wow_data["daily"][$i]["timestamp_sunrise"]);
                $wow_data["daily"][$i]["sunset"] = (string)date($wpowmweather_time_php, $wow_data["daily"][$i]["timestamp_sunset"]);
                $wow_data["daily"][$i]["timestamp_moonrise"] = $value->moonrise ? $value->moonrise + (60 * $utc_time_wp) : null;
                $wow_data["daily"][$i]["timestamp_moonset"] = $value->moonset ? $value->moonset + (60 * $utc_time_wp) : null;
                $wow_data["daily"][$i]["moonrise"] = (string)date($wpowmweather_time_php, $wow_data["daily"][$i]["timestamp_moonrise"]);
                $wow_data["daily"][$i]["moonset"] = (string)date($wpowmweather_time_php, $wow_data["daily"][$i]["timestamp_moonset"]);
                $wow_data["daily"][$i]["moon_phase"] = $value->moon_phase ?? null;
                $wow_data["daily"][$i]["condition_id"] = $value->weather[0]->id ?? null;
                $wow_data["daily"][$i]["category"] = $value->weather[0]->main ?? null;
                $wow_data["daily"][$i]["description"] = $value->weather[0]->description ?? null;
                $wow_data["daily"][$i]["wind_speed"] = getConvertedWindSpeed($value->wind_speed, $wow_opt["temperature_unit"], $wow_opt["wind_unit"]) . ' ' . $wow_data["wind_speed_unit"];
                $wow_data["daily"][$i]["wind_degrees"] = $value->wind_deg ?? null;
                $wow_data["daily"][$i]["wind_direction"] = getWindDirection($value->wind_deg);
                $wow_data["daily"][$i]["wind_gust"] = isset($value->wind_gust) ? getConvertedWindSpeed($value->wind_gust, $wow_opt["temperature_unit"], $wow_opt["wind_unit"])  . ' ' . $wow_data["wind_speed_unit"] : null;
                $wow_data["daily"][$i]["temperature_morning"] = $value->temp->morn ? ceil($value->temp->morn) : null;
                $wow_data["daily"][$i]["temperature_day"] = $value->temp->day ? ceil($value->temp->day) : null;
                $wow_data["daily"][$i]["temperature_evening"] = $value->temp->eve ? ceil($value->temp->eve) : null;
                $wow_data["daily"][$i]["temperature_night"] = $value->temp->eve ? ceil($value->temp->night) : null;
                $wow_data["daily"][$i]["temperature_minimum"] = $value->temp->min ? ceil($value->temp->min) : null;
                $wow_data["daily"][$i]["temperature_maximum"] = $value->temp->max ? ceil($value->temp->max) : null;
                $wow_data["daily"][$i]["feels_like_morning"] = $value->feels_like->morn ? ceil($value->feels_like->morn) : null;
                $wow_data["daily"][$i]["feels_like_day"] = $value->feels_like->day ? ceil($value->feels_like->day) : null;
                $wow_data["daily"][$i]["feels_like_evening"] = $value->feels_like->eve ? ceil($value->feels_like->eve) : null;
                $wow_data["daily"][$i]["feels_like_night"] = $value->feels_like->night ? ceil($value->feels_like->night) : null;
                $wow_data["daily"][$i]["humidity"] = $value->humidity ? $value->humidity . '%' : null;
                $wow_data["daily"][$i]["pressure"] = converthp2iom($wow_opt["temperature_unit"], $value->pressure) . ' ' . $wow_data["pressure_unit"];
                $wow_data["daily"][$i]["dew_point"] = $value->dew_point ? ceil($value->dew_point) : null;
                $wow_data["daily"][$i]["cloudiness"] = $value->clouds ? $value->clouds . '%' : '0%';
                $wow_data["daily"][$i]["uv_index"] = $value->uvi ?? null;
                $wow_data["daily"][$i]["rain_chance"] = $value->pop ? number_format($value->pop * 100, 0) . '%' : '0%';
                $wow_data["daily"][$i]["rain"] = getConvertedPrecipitation($wow_opt["temperature_unit"], $value->rain ?? 0) . ' ' . $wow_data["precipitation_unit"];
                $wow_data["daily"][$i]["snow"] = getConvertedPrecipitation($wow_opt["temperature_unit"], $value->snow ?? 0) . ' ' . $wow_data["precipitation_unit"];
                $wow_data["daily"][$i]["precipitation"] = $wow_data["daily"][$i]["rain"] ?? 0 + $wow_data["daily"][$i]["snow"] ?? 0  . ' ' . $wow_data["precipitation_unit"];

			    $date_index = date('Ymd', $wow_data["daily"][$i]["timestamp"]);
			    $wow_data[$date_index]["sunrise"] = $wow_data["daily"][$i]["timestamp_sunrise"];
			    $wow_data[$date_index]["sunset"] = $wow_data["daily"][$i]["timestamp_sunset"];
			}
		}//End days loop

		//Hours loop (must be after days loop)
		if($wow_opt["hours_forecast_no"] > 0) {
            $cnt = 0;
            foreach ($myweather->hourly as $i => $value) {
                if ($value->dt > (time()-3600)) {
                    $wow_data["hourly"][$cnt]["timestamp"] = $value->dt ? $value->dt + (60 * $utc_time_wp) : null;
                    $wow_data["hourly"][$cnt]["time"] = (string)date($wpowmweather_hours_php, $value->dt + (60*$utc_time_wp));
                    $wow_data["hourly"][$cnt]["condition_id"] = $value->weather[0]->id ?? null;
                    $wow_data["hourly"][$cnt]["category"] = $value->weather[0]->main ?? null;
                    $wow_data["hourly"][$cnt]["description"] = $value->weather[0]->description ?? null;
                    $wow_data["hourly"][$cnt]["wind_speed"] = getConvertedWindSpeed($value->wind_speed, $wow_opt["temperature_unit"], $wow_opt["wind_unit"]) . ' ' . $wow_data["wind_speed_unit"];
                    $wow_data["hourly"][$cnt]["wind_degrees"] = $value->wind_deg ?? null;
                    $wow_data["hourly"][$cnt]["wind_direction"] = getWindDirection($value->wind_deg);
                    $wow_data["hourly"][$cnt]["wind_gust"] = isset($value->wind_gust) ? getConvertedWindSpeed($value->wind_gust, $wow_opt["temperature_unit"], $wow_opt["wind_unit"]) . ' ' . $wow_data["wind_speed_unit"] : null;
                    $wow_data["hourly"][$cnt]["temperature"] = $value->temp ? ceil($value->temp) : null;
                    $wow_data["hourly"][$cnt]["feels_like"] = $value->feels_like ? ceil($value->feels_like) : null;
                    $wow_data["hourly"][$cnt]["humidity"] = $value->humidity ? $value->humidity . '%' : null;
                    $wow_data["hourly"][$cnt]["pressure"] = converthp2iom($wow_opt["temperature_unit"], $value->pressure) . ' ' . $wow_data["pressure_unit"];
                    $wow_data["hourly"][$cnt]["dew_point"] = $value->dew_point ? ceil($value->dew_point) : null;
                    $wow_data["hourly"][$cnt]["cloudiness"] = $value->clouds ? $value->clouds . '%' : "0%";
                    $wow_data["hourly"][$cnt]["uv_index"] = $value->uvi ?? null;
                    $wow_data["hourly"][$cnt]["rain_chance"] = $value->pop ? number_format($value->pop * 100, 0) .'%': '0%';
                    $wow_data["hourly"][$cnt]["visibility"] = getConvertedDistance($wow_opt["temperature_unit"], $value->visibility);
                    $wow_data["hourly"][$cnt]["rain"] = getConvertedPrecipitation($wow_opt["temperature_unit"], $value->rain->{"1h"} ?? 0) . ' ' . $wow_data["precipitation_unit"];
                    $wow_data["hourly"][$cnt]["snow"] = getConvertedPrecipitation($wow_opt["temperature_unit"], $value->snow->{"1h"} ?? 0) . ' ' . $wow_data["precipitation_unit"];
                    $wow_data["hourly"][$cnt]["precipitation"] = $wow_data["hourly"][$cnt]["rain"] ?? 0 + $wow_data["hourly"][$cnt]["snow"] ?? 0 . ' ' . $wow_data["precipitation_unit"];
       			    $date = date('Ymd', $wow_data["hourly"][$cnt]["timestamp"]);
       			    if (isset($wow_data[$date])) {
           			    $wow_data["hourly"][$cnt]["day_night"] = ($wow_data["hourly"][$cnt]["timestamp"] > $wow_data[$date]["sunrise"] && $wow_data["hourly"][$cnt]["timestamp"] < $wow_data[$date]["sunset"]) ? 'day' : 'night';
           			} else {
                        $wow_data["hourly"][$cnt]["day_night"] = 'night';
           			}
       			    ++$cnt;
                }
		  	}
		}

        //Moon rise and set
        if (!empty($wow_data["daily"])) {
            $wow_data["timestamp_moonrise"] = $wow_data["daily"][0]["timestamp_moonrise"] + (60 * $utc_time_wp);
            $wow_data["timestamp_moonset"] = $wow_data["daily"][0]["timestamp_moonset"] + (60 * $utc_time_wp);
            $wow_data["moonrise"] = (string)date($wpowmweather_time_php, $wow_data["timestamp_moonrise"]);
            $wow_data["moonset"] = (string)date($wpowmweather_time_php, $wow_data["timestamp_moonset"]);
        }

		//Alerts loop
		if (isset($myweather->alerts)) {
			foreach ($myweather->alerts as $i => $value) {
			    $wow_data["alerts"][$i]["sender"] = $value->sender_name;
			    $wow_data["alerts"][$i]["event"] = $value->event;
			    $wow_data["alerts"][$i]["start"] = date_i18n( __( 'M j, Y @ G:i' ), $value->start );
			    $wow_data["alerts"][$i]["end"] = date_i18n( __( 'M j, Y @ G:i' ), $value->end );
			    $wow_data["alerts"][$i]["text"] = $value->description;
			}
		}

        esc_html_all($wow_data);

		//variable declarations
        $wow_html = [];
		$wow_html["now"]["start"]             	= '';
		$wow_html["now"]["location_name"]       = '';
		$wow_html["now"]["symbol"]       	    = '';
		$wow_html["now"]["temperature"]         = '';
		$wow_html["now"]["feels_like"]          = '';
		$wow_html["now"]["weather_description"] = '';
		$wow_html["now"]["end"]               	= '';
		$wow_html["custom_css"]            		= '';
		$wow_html["today"]["start"]          	= '';
		$wow_html["today"]["day"]          		= '';
		$wow_html["today"]["sun"]             	= '';
		$wow_html["today"]["moon"]             	= '';
		$wow_html["info"]["start"]             	= '';
		$wow_html["info"]["wind"]            	= '';
		$wow_html["info"]["humidity"]          	= '';
		$wow_html["info"]["dew_point"]         	= '';
		$wow_html["info"]["pressure"]           = '';
		$wow_html["info"]["cloudiness"]         = '';
		$wow_html["info"]["precipitation"]      = '';
		$wow_html["info"]["visibility"]         = '';
		$wow_html["info"]["uv_index"]           = '';
		$wow_html["svg"]["wind"]            	= '';
		$wow_html["svg"]["humidity"]          	= '';
		$wow_html["svg"]["dew_point"]          	= '';
		$wow_html["svg"]["pressure"]            = '';
		$wow_html["svg"]["cloudiness"]          = '';
		$wow_html["svg"]["precipitation"]       = '';
		$wow_html["svg"]["visibility"]          = '';
		$wow_html["svg"]["uv_index"]            = '';
		$wow_html["info"]["end"]             	= '';
		$wow_html["hour"]["info"]               = '';
		$wow_html["hour"]["start"]            	= '';
		$wow_html["hour"]["end"]              	= '';
		$wow_html["map"]                 		= '';
		$wow_html["today"]["end"]          		= '';
		$wow_html["forecast"]["start"]          = '';
		$wow_html["forecast"]["info"]           = '';
		$wow_html["forecast"]["end"]            = '';
		$wow_html["forecast"]["start_card"]     = '';
		$wow_html["forecast"]["info_card"]      = '';
		$wow_html["forecast"]["end_card"]       = '';
		$wow_html["container"]["start"]         = '';
		$wow_html["container"]["end"]           = '';
		$wow_html["owm_link"]            		= '';
		$wow_html["last_update"]         		= '';
    	$wow_html["owm_link_last_update_start"] = '';
    	$wow_html["owm_link_last_update_end"]   = '';
        $wow_html["gtag"]                       = '';
        $wow_html["alert_button"]               = '';
        $wow_html["alert_modal"]                = '';
        $wow_html["table"]["hourly"]            = '';
        $wow_html["table"]["daily"]             = '';

       	$wow_html["svg"]["wind"]          = wind_direction_icon($wow_data["wind_degrees"], $wow_opt["text_color"]);
       	$wow_html["svg"]["humidity"]      = humidity_icon($wow_opt["text_color"]);
      	$wow_html["svg"]["dew_point"]     = dew_point_icon($wow_opt["text_color"]);
       	$wow_html["svg"]["pressure"]      = pressure_icon($wow_opt["text_color"]);
       	$wow_html["svg"]["cloudiness"]    = cloudiness_icon($wow_opt["text_color"]);
       	$wow_html["svg"]["rain_chance"]   = rain_chance_icon($wow_opt["text_color"]);
       	$wow_html["svg"]["precipitation"] = precipitation_icon($wow_opt["text_color"]);
       	$wow_html["svg"]["visibility"]    = visibility_icon($wow_opt["text_color"]);
       	$wow_html["svg"]["uv_index"]      = uv_index_icon($wow_opt["text_color"]);

	    $wow_html["current"]["day_night"] = ($wow_data["timestamp"] > $wow_data["timestamp_sunrise"] && $wow_data["timestamp"] < $wow_data["timestamp_sunset"]) ? 'day' : 'night';
        $wow_html["current"]["symbol_svg"] = weatherSVG($wow_data["condition_id"], $wow_html["current"]["day_night"]);
        $wow_html["current"]["symbol_alt"] = weatherIcon($wow_opt["iconpack"], $wow_data["condition_id"], $wow_html["current"]["day_night"], $wow_data["description"]);

		$display_now_start = '<div class="wow-now">';
		$display_now_location_name = '<div class="wow-location-name">'. wpowmweather_city_name($wow_opt["custom_city_name"], $wow_data["name"])  .'</div>';
        if ($wow_opt["disable_anims"] != 'yes') {
    		$display_now_symbol = '<div class="wow-main-symbol wow-symbol-svg climacon" style="'. wow_css_color("fill", $wow_opt["text_color"]) .'"><span title="'.$wow_data["description"].'">'. $wow_html["current"]["symbol_svg"] .'</span></div>';
    	} else {
    		$display_now_symbol = '<div class="wow-main-symbol wow-symbol-alt" style="'. wow_css_color("fill", $wow_opt["text_color"]) .'"><span title="'.$wow_data["description"].'">'. $wow_html["current"]["symbol_alt"] .'</span></div>';
    	}
		$display_now_temperature = '<div class="wow-main-temperature">'. $wow_data["temperature"] .'</div>';
		$display_now_feels_like = '<div class="wow-main-feels-like">Feels like '. $wow_data["feels_like"] .'</div>';
		$display_now_end = '</div>';

		//Hours loop
	    if ($wow_opt["hours_forecast_no"] > 0) {
	    	$wpowmweather_class_hours = array( 0 => "first", 1 => "second", 2 => "third", 3 => "fourth", 4 => "fifth", 5 => "sixth", 6 => "seventh", 7 => "eighth", 8 => "ninth", 9 => "tenth", 10 => "eleventh", 11 => "twelfth", 12 => "thirteenth", 13 => "fourteenth", 14 => "fifteenth", 15 => "sixteenth", 16 => "seventeenth", 17 => "eighteenth", 18 => "nineteenth", 19 => "twentieth", 20 => "twentyfirst", 21 => "twentysecond", 22 => "twentythird", 23 => "twentyfourth", 24 => "twentyfifth", 25 => "twentysixth", 26 => "twentyseventh", 27 => "twentyeighth", 28 => "twentyninth", 29 => "thirtieth", 30 => "thirtyfirst", 31 => "thirtysecond", 32 => "thirtythird", 33 => "thirtyfourth", 34 => "thirtyfifth", 35 => "thirtysixth", 36 => "thirtyseventh", 37 => "thirtyeighth", 38 => "thirtyninth", 39 => "fortieth", 40 => "fortyfirst", 41 => "fortysecond", 42 => "fortythird", 43 => "fortyfourth", 44 => "fortyfifth", 45 => "fortysixth", 46 => "fortyseventh", 47 => "fortyeighth" );

			foreach ($wow_data["hourly"] as $i => $value) {
				$display_hours_icon[$i] = weatherIcon($wow_opt["iconpack"], $value["condition_id"], $value["day_night"], $value["description"]);
				$display_hours[$i] =
   					'<div class="wow-'. $wpowmweather_class_hours[$i].' card">
   						<div class="card-body">
   						    <div class="wow-hour">'. date('D', $value["timestamp"]) . '<br>' .
       						    ($wow_opt["hours_time_icons"] == 'yes' ? hour_icon($value["time"], $wow_opt["text_color"]) : $value["time"]) .
       				        '</div>' .
       				        $display_hours_icon[$i] .
       				        '<div class="wow-temperature">'.
       				            $value["temperature"] .
       				        '</div>
    					</div>
   					</div>';
			}
		}

		//Daily loop
		if ($wow_opt["days_forecast_no"] > 0) {
			$wpowmweather_class_days = array(0 => "first", 1 => "second", 2 => "third", 3 => "fourth", 4 => "fifth", 5 => "sixth", 6 => "seventh", 7 => "eighth");

			foreach ($wow_data["daily"] as $i => $value) {
				$display_forecasticon[$i] = weatherIcon($wow_opt["iconpack"], $value["condition_id"], "day", $value["description"]);
				$display_forecast[$i] =
			   		'<div class="wow-'. $wpowmweather_class_days[$i].' row">
			      		<div class="wow-day col">'. ($i == 0 ? "Today" : $value["day"]) .'</div>' . '<div class="col">' . $display_forecasticon[$i] . '</div>';

			      		if ($wow_opt["forecast_precipitations"] == 'yes') {
		      				$display_forecast[$i] .= '<div class="wow-rain col">'. $value["precipitation"] . '</div>';
			      		}

			      		$display_forecast[$i] .=
			      		'<div class="wow-temp-min col">'. $value["temperature_minimum"] .'</div>
			      		<div class="wow-temp-max col"><span class="font-weight-bold">'. $value["temperature_maximum"] .'</span></div>
			    	</div>';
        		$display_forecast_card[$i] =
   					'<div class="wow-'. $wpowmweather_class_days[$i].' card">
   						<div class="card-body">
					        <div class="wow-day">'. ($i == 0 ? "Today" : $value["day"]) .'</div>' . $display_forecasticon[$i] .
       				        '<div class="wow-temperature">
       				            <span class="wow-temp-min">' . $value["temperature_minimum"] . '</span> - <span class="wow-temp-max">' . $value["temperature_maximum"] . '</span>
       				        </div>
       				        <div class="wow-infos-text">';
       				            if ($wow_opt["wind"] == 'yes') {
               				        $display_forecast_card[$i] .= '<div>'.wind_direction_icon($value["wind_degrees"], $wow_opt["text_color"]).'Wind: <span class="wow-value">' .$value["wind_speed"].' '.$value["wind_direction"].'</span></div>';
           				        }
       				            if ($wow_opt["humidity"] == 'yes') {
               				        $display_forecast_card[$i] .= '<div>'.$wow_html["svg"]["humidity"].'Humidity: <span class="wow-value">' .$value["humidity"]. '</span></div>';
               				    }
       				            if ($wow_opt["dew_point"] == 'yes') {
               				        $display_forecast_card[$i] .= '<div>'.$wow_html["svg"]["dew_point"].'Dew point: <span class="wow-value wow-temperature">' .$value["dew_point"]. '</span></div>';
               				    }
       				            if ($wow_opt["pressure"] == 'yes') {
               				        $display_forecast_card[$i] .= '<div>'.$wow_html["svg"]["pressure"].'Pressure: <span class="wow-value">' .$value["pressure"]. '</span></div>';
               				    }
       				            if ($wow_opt["cloudiness"] == 'yes') {
               				        $display_forecast_card[$i] .= '<div>'.$wow_html["svg"]["cloudiness"].'Clouds: <span class="wow-value">' .$value["cloudiness"]. '</span></div>';
               				    }
       				            if ($wow_opt["precipitation"] == 'yes') {
               				        $display_forecast_card[$i] .= '<div>'.$wow_html["svg"]["rain_chance"].'Rain Chance: <span class="wow-value">' .$value["rain_chance"]. '</span></div>';
               				    }
       				            if ($wow_opt["precipitation"] == 'yes') {
               				        $display_forecast_card[$i] .= '<div>'.$wow_html["svg"]["precipitation"].'Precipitation: <span class="wow-value">' .$value["precipitation"]. '</span></div>';
               				    }
       				            if ($wow_opt["uv_index"] == 'yes') {
               				        $display_forecast_card[$i] .= '<div>'.$wow_html["svg"]["uv_index"].'UV index: <span class="wow-value">' .$value["uv_index"]. '</span></div>';
               				    }
        		$display_forecast_card[$i] .=
           				    '</div>
    					</div>
   					</div>';
			}
		}

      	$temperature_unit_character = $wow_opt["temperature_unit"] == "metric" ? 'C' : 'F';
      	$temperature_unit_text = $wow_opt["temperature_unit"] == "metric" ? 'Celsius' : 'Fahrenheit';

	    //Map

	      	if ($wow_opt['map'] == 'yes') {

		    	//Layers opacity
	    		$display_map_layers_opacity = $wow_opt["map_opacity"];

                $display_map_layers  = '';
                $display_map_options = '';

		    	//Stations
		    	if ( $wow_opt["map_stations"] == 'yes' ) {
		        	$display_map_options         	.= 'var station = L.OWM.current({type: "station", appId: "'.$wow_opt["api_key"].'"});';
		        	$display_map_layers             .= '"Stations": station,';
		      	}

		      	//Clouds
		      	if ( $wow_opt["map_clouds"] == 'yes' ) {
		        	$display_map_options         	.= 'var clouds = L.OWM.clouds({showLegend: false, opacity: '.$display_map_layers_opacity.', appId: "'.$wow_opt["api_key"].'"});';
		        	$display_map_layers             .= '"Clouds": clouds,';
		        	$display_map_options         	.= 'var cloudscls = L.OWM.cloudsClassic({showLegend: false, opacity: '.$display_map_layers_opacity.', appId: "'.$wow_opt["api_key"].'"});';
		        	$display_map_layers             .= '"Clouds Classic": cloudscls,';
		      	}

		      	//Precipitations and Rain
		      	if ( $wow_opt["map_precipitation"] == 'yes' ) {
		        	$display_map_options         	.= 'var rain = L.OWM.rain({opacity: '.$display_map_layers_opacity.', appId: "'.$wow_opt["api_key"].'"});';
		        	$display_map_layers             .= '"Rain": rain,';
		        	$display_map_options         	.= 'var raincls = L.OWM.rainClassic({opacity: '.$display_map_layers_opacity.', appId: "'.$wow_opt["api_key"].'"});';
		        	$display_map_layers             .= '"Rain Classic": raincls,';
		        	$display_map_options         	.= 'var precipitation = L.OWM.precipitation({opacity: '.$display_map_layers_opacity.', appId: "'.$wow_opt["api_key"].'"});';
		        	$display_map_layers             .= '"Precipitation": precipitation,';
		        	$display_map_options         	.= 'var precipitationcls = L.OWM.precipitationClassic({opacity: '.$display_map_layers_opacity.', appId: "'.$wow_opt["api_key"].'"});';
		        	$display_map_layers             .= '"Precipitation Classic": precipitationcls,';
		      	}

		      	//Snow
		      	if ( $wow_opt["map_snow"] == 'yes' ) {
		        	$display_map_options         	.= 'var snow = L.OWM.snow({opacity: '.$display_map_layers_opacity.', appId: "'.$wow_opt["api_key"].'"});';
		        	$display_map_layers             .= '"Snow": snow,';
		      	}

		      	//Wind
		      	if ( $wow_opt["map_wind"] == 'yes' ) {
		        	$display_map_options         	.= 'var wind = L.OWM.wind({opacity: '.$display_map_layers_opacity.', appId: "'.$wow_opt["api_key"].'"});';
		        	$display_map_layers             .= '"Wind": wind,';
		      	}

		      	//Temperature
		      	if ( $wow_opt["map_temperature"] == 'yes' ) {
		        	$display_map_options         	.= 'var temp = L.OWM.temperature({opacity: '.$display_map_layers_opacity.', appId: "'.$wow_opt["api_key"].'"});';
		        	$display_map_layers             .= '"Temperature": temp,';
		      	}

		      	//Pressure
		      	if ( $wow_opt["map_pressure"] == 'yes' ) {
		        	$display_map_options         	.= 'var pressure = L.OWM.pressure({opacity: '.$display_map_layers_opacity.', appId: "'.$wow_opt["api_key"].'"});';
		        	$display_map_layers             .= '"Pressure": pressure,';
		        	$display_map_options         	.= 'var pressurecntr = L.OWM.pressureContour({opacity: '.$display_map_layers_opacity.', appId: "'.$wow_opt["api_key"].'"});';
		        	$display_map_layers             .= '"Pressure Contour": pressurecntr,';
		      	}

		      	//Scroll wheel
		      	$display_map_scroll_wheel = ($wow_opt["map_disable_zoom_wheel"] == 'yes') ? "false" : "true";

                if ($wow_opt["wind_unit"] == "2") {
    		      	$map_speed = 'ms';
				} else if ($wow_opt["wind_unit"] == "3") {
    		      	$map_speed = 'kmh';
                } else {
    		      	$map_speed = 'mph';
                }

		      	$wow_html["map"] =
			        '<div id="' . $wow_opt["main_map_div"] . '" class="wow-map">
			        	<div id="' . $wow_opt["container_map_div"] . '" style="'.wow_css_height($wow_opt["map_height"]) .'"></div>
			        </div>
			        <script type="text/javascript">
			        	jQuery(document).ready( function() {

				        	var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
							maxZoom: 18, attribution: "OWM Weather" });

							var city = L.OWM.current({intervall: '.($wow_opt["cache_time"]??30).', lang: "en", appId: "'.$wow_opt["api_key"] . '",temperatureDigits:0,temperatureUnit:"' . $temperature_unit_character . '",speedUnit:"' . $map_speed . '"});'.

							$display_map_options .

							'var map = L.map("' . $wow_opt["container_map_div"] . '", { center: new L.LatLng('. $wow_data["latitude"] .', '. $wow_data["longitude"] .'), zoom: '. $wow_opt["map_zoom"] .', layers: [osm], scrollWheelZoom: '.$display_map_scroll_wheel.' });

							var baseMaps = { "OSM Standard": osm };

							var overlayMaps = {'.$display_map_layers.'"Cities": city};

							var layerControl = L.control.layers(baseMaps, overlayMaps).addTo(map);

                            map.whenReady(() => {
                               	jQuery( "#' . $wow_opt["container_map_div"] . '").on("invalidSize", function() {
                                    map.invalidateSize();
                            	});
                            });

			        	});
			        </script>';
			}

/* bugbug
		if ($wow_opt["image_bg_cover"] == 'yes') {
			$wow_opt["image_bg_cover_e"] = 'cover';
		}
		$wpowmweather_image_bg_position_horizontal				= 	get_post_meta($wow_opt["id"],'_wpowmweather_image_bg_position_horizontal',true);
		if ($wpowmweather_image_bg_position_horizontal != 'Default') {
			$wow_opt["image_bg_position_horizontal_e"]		= 	$wpowmweather_image_bg_position_horizontal;
		}
		$wpowmweather_image_bg_position_vertical				= 	get_post_meta($wow_opt["id"],'_wpowmweather_image_bg_position_vertical',true);
		if ($wpowmweather_image_bg_position_vertical != 'Default') {
			$wow_opt["image_bg_position_vertical_e"]		= 	$wpowmweather_image_bg_position_vertical;
		}
*/
		$wow_html["container"]["start"] = '<!-- OWM Weather : WordPress weather plugin v'.WP_OWM_WEATHER_VERSION.' - https://github.com/uwejacobs/owm-weather -->';
		$wow_html["container"]["start"] .= '<div id="' . $wow_opt["container_weather_div"] . '" class="wow-'.$wow_opt["id"].' wow-weather-'.$wow_data["condition_id"].' wow-'. $wow_opt["size"] .' wow-template-'. $wow_opt["template"] .'"';
		$wow_html["container"]["start"] .= ' style="';
		$wow_html["container"]["start"] .= wow_css_color('background-color', $wow_opt["background_color"]) .
                                            wow_css_background_image($wow_opt["background_image"]) .
		                                    wow_css_background_size("cover").
//		                                    wow_css_background_size($wow_opt["image_bg_cover_e"]).
//		                                    wow_css_background_position($wow_opt["image_bg_position_horizontal_e"], $wow_opt["image_bg_position_vertical_e"]).
		                                    wow_css_color("color",$wow_opt["text_color"]).
		                                    wow_css_border($wow_opt["border_color"], $wow_opt["border_width"],$wow_opt["border_style"],$wow_opt["border_radius"]).
		                                    wow_css_font_family($wow_opt["font"]);
		$wow_html["container"]["start"] .= '">';

        // Now
        if ($wow_opt["current_city_name"] == 'yes' || $wow_opt["current_weather_symbol"] =='yes' || $wow_opt["current_temperature"] =='yes' || $wow_opt["current_feels_like"] =='yes' || $wow_opt["current_weather_description"] == 'yes')  {
            $wow_html["now"]["start"]           	= $display_now_start;
            if ($wow_opt["current_city_name"] == 'yes') {
                $wow_html["now"]["location_name"]       = $display_now_location_name;
            }
            if ($wow_opt["current_weather_symbol"] =='yes') {
            	$wow_html["now"]["symbol"]     = $display_now_symbol;
        	}
            if ($wow_opt["current_temperature"] =='yes') {
       	        $wow_html["now"]["temperature"]    = $display_now_temperature;
        	}
            if ($wow_opt["current_feels_like"] =='yes') {
       	        $wow_html["now"]["feels_like"]    = $display_now_feels_like;
        	}
    	    if( $wow_opt["current_weather_description"] == 'yes' ) {
	        	$wow_html["now"]["weather_description"] = '<div class="wow-short-condition">'. $wow_data["description"] .'</div>';
	        }
            $wow_html["now"]["end"]             	= $display_now_end;
        }

	   	$wow_html["today"]["start"]     = '<div class="wow-today row">';
        if( $wow_opt["today_date_format"] != "none") {
	        $wow_html["today"]["day"]       = '<div class="wow-day col"><span class="wow-highlight">'. $today_day .'</span></div>';
	    }
        $wow_html["today"]["sun"]       = wow_display_today_sunrise_sunset($wow_opt["sunrise_sunset"], $wow_data["sunrise"], $wow_data["sunset"], $wow_opt["text_color"], 'span');
        $wow_html["today"]["sun_hor"]   = wow_display_today_sunrise_sunset($wow_opt["sunrise_sunset"], $wow_data["sunrise"], $wow_data["sunset"], $wow_opt["text_color"], 'div');
        $wow_html["today"]["moon"]      = wow_display_today_moonrise_moonset($wow_opt["moonrise_moonset"], $wow_data["moonrise"], $wow_data["moonset"], $wow_opt["text_color"], 'span');
        $wow_html["today"]["moon_hor"]  = wow_display_today_moonrise_moonset($wow_opt["moonrise_moonset"], $wow_data["moonrise"], $wow_data["moonset"], $wow_opt["text_color"],'div');
        $wow_html["today"]["end"]       = '</div>';

	    if( $wow_opt["wind"] == 'yes' || $wow_opt["humidity"] == 'yes' || $wow_opt["dew_point"] == 'yes' || $wow_opt["pressure"] == 'yes' || $wow_opt["cloudiness"] == 'yes' || $wow_opt["precipitation"] == 'yes' || $wow_opt["visibility"] == 'yes' || $wow_opt["uv_index"] == 'yes') {
	    	$wow_html["info"]["start"] .= '<div class="wow-infos row">';

	        if( $wow_opt["wind"] == 'yes' ) {
	        	$wow_html["info"]["wind"]            = '<div class="wow-wind col">'.$wow_html["svg"]["wind"]. __( 'Wind', 'owm-weather' ) .'<span class="wow-highlight">'. $wow_data["wind_speed"] .' - '.$wow_data["wind_direction"].'</span></div>';
	        }

	        if( $wow_opt["humidity"] == 'yes' ) {
	        	$wow_html["info"]["humidity"]        = '<div class="wow-humidity col">'.$wow_html["svg"]["humidity"]. __( 'Humidity', 'owm-weather' ) .'<span class="wow-highlight">'. $wow_data["humidity"] .'</span></div>';
	        }

	        if( $wow_opt["dew_point"] == 'yes' ) {
	        	$wow_html["info"]["dew_point"]       = '<div class="wow-dew-point col">'.$wow_html["svg"]["dew_point"]. __( 'Dew Point', 'owm-weather' ) .'<span class="wow-highlight wow-temperature">'. $wow_data["dew_point"] .'</span></div>';
	        }

	        if( $wow_opt["pressure"]  == 'yes') {
	        	$wow_html["info"]["pressure"]        = '<div class="wow-pressure col">'.$wow_html["svg"]["pressure"]. __( 'Pressure', 'owm-weather' ) .'<span class="wow-highlight">'. $wow_data["pressure"] .'</span></div>';
	        }

	        if( $wow_opt["cloudiness"] == 'yes' ) {
	        	$wow_html["info"]["cloudiness"]      = '<div class="wow-cloudiness col">'.$wow_html["svg"]["cloudiness"]. __( 'Cloudiness', 'owm-weather' ) .'<span class="wow-highlight">'. $wow_data["cloudiness"] .'</span></div>';
	        }

	        if( $wow_opt["precipitation"] == 'yes' ) {
	        	$wow_html["info"]["precipitation"]   = '<div class="wow-precipitation col">'.$wow_html["svg"]["precipitation"]. __( 'Precipitation', 'owm-weather' ) .'<span class="wow-highlight">'. $wow_data["precipitation_3h"] .'</span></div>';
	        }

	        if( $wow_opt["visibility"] == 'yes' ) {
	        	$wow_html["info"]["visibility"]     = '<div class="wow-visibility col">'.$wow_html["svg"]["visibility"]. __( 'Visibility', 'owm-weather' ) .'<span class="wow-highlight">'. $wow_data["visibility"] .'</span></div>';
	        }

	        if( $wow_opt["uv_index"] == 'yes' ) {
	        	$wow_html["info"]["uv_index"]       = '<div class="wow-uv-index col">'.$wow_html["svg"]["uv_index"]. __( 'UV Index', 'owm-weather' ) .'<span class="wow-highlight">'. $wow_data["uv_index"] .'</span></div>';
	        }

	        $wow_html["info"]["end"] .= '</div>';
	    };

	    if($wow_opt["hours_forecast_no"] > 0) {
	    	$wow_html["hour"]["start"] = '<div class="wow-hours card-column" style="'.wow_css_color('border-color', $wow_opt["border_color"]).'">';
	        $wow_html["hour"]["info"]  = $display_hours;
	        $wow_html["hour"]["end"]   = '</div>';

            for ($i=1; $i<=12; $i++) {
                $wow_html["hour"]["icon".$i] = hour_icon($i, $wow_opt["text_color"]);
            }
	    }

	    if ($wow_opt["days_forecast_no"] > 0) {
	    	$wow_html["forecast"]["start"] = '<div class="wow-forecast d-flex flex-column justify-content-center">';
	        $wow_html["forecast"]["info"]  = $display_forecast;
	        $wow_html["forecast"]["end"]   = '</div>';
	    	$wow_html["forecast"]["start_card"] = '<div class="wow-forecast card-column">';
	        $wow_html["forecast"]["info_card"]  = $display_forecast_card;
	        $wow_html["forecast"]["end_card"]   = '</div>';
	    }

        //Google Tag Manager dataLayer
        if ($wow_opt["gtag"] == 'yes') {
            $wow_html["gtag"] = '<script type="text/javascript">
                var dataLayer = window.dataLayer = window.dataLayer || [];
	        	jQuery(document).ready(function() {
                    dataLayer.push({
                      "weatherTemperature": ' . $wow_data["temperature"] . ',
                      "weatherCloudiness": ' . $wow_data["cloudiness"] . ',
                      "weatherDescription": "' . $wow_data["description"] . '",
                      "weatherCategory": "' . $wow_data["category"] . '"
                  });
                });
            </script>';
        }

	    if ($wow_opt["alerts"] == 'yes' && !empty($wow_data["alerts"])) {
    	    require_once dirname( __FILE__ ) . '/owmweather-color-css.php';

            if (empty($wow_opt["alerts_button_color"])) {
                $wow_opt["alerts_button_color"] = '#000';
    	    }
   	        $wow_opt["custom_css"] .= generateColorCSS($wow_opt["alerts_button_color"], "wow-alert-" . $wow_opt["id"]);
            $wow_html["alert_button"] .= '<div class="wow-alert-buttons text-center">';
            foreach($wow_data["alerts"] as $key => $value) {
                $modal = wow_unique_id('wow-modal-'.$wow_opt["id"]);
                $wow_html["alert_button"] .= '<button class="wow-alert-button btn btn-outline-wow-alert-' . $wow_opt["id"] . ' m-1" data-toggle="modal" data-target="#' . $modal . '">' . $value["event"] . '</button>';
                $wow_html["alert_modal"] .=
                    '<div class="modal fade" id="' . $modal . '" tabindex="-1" role="dialog" aria-labelledby="' . $modal . '-label" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content" style="' .
                        		wow_css_color('background-color', $wow_opt["background_color"]) . wow_css_color("color",$wow_opt["text_color"]) . '">
                          <div class="modal-header">
                            <h5 class="modal-title" id="' . $modal . '-label">' . $value["event"] . '</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <div class="modal-body">
                            <p>' . $value["sender"] . '<br>' . $value["start"] . ' until ' . $value["end"] .'</p>
                            <p>' . nl2br($value["text"]) . '</p></div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div>';
            }
            $wow_html["alert_button"] .= '</div>';
	    }

    	$wow_html["temperature_unit"] = temperatureUnitSymbol($wow_opt["container_weather_div"], $wow_opt["display_temperature_unit"], $wow_opt["temperature_unit"], $wow_opt["iconpack"]);

        if ($wow_opt["owm_link"] == 'yes' || $wow_opt["last_update"] == 'yes') {
	    	$wow_html["owm_link_last_update_start"] .= '<div class="wow-owm-link-last-update clearfix">';
	    	$wow_html["owm_link_last_update_end"] .= '</div>';
        }

	    if ($wow_opt["owm_link"] == 'yes') {
	    	$wow_html["owm_link"] .= '<div class="wow-link-owm">'.$wow_data["owm_link"].'</div>';
	    }
	    if ($wow_opt["last_update"] == 'yes') {
	    	$wow_html["last_update"] .= '<div class="wow-last-update">'.$wow_data["last_update"].'</div>';
	    }

        //charts
        //hourly
        $chart_id = wow_unique_id($wow_opt["id"], '_');
        $wow_html["chart"]["hourly"] = [];
        $wow_opt["container_chart_hourly_div"] = 'wow-hourly-chart-canvas-'.$chart_id;
        $wow_html["chart"]["hourly"]["labels"] = '';
        $wow_html["chart"]["hourly"]["dataset_temperature"] = '';
        $wow_html["chart"]["hourly"]["dataset_feels_like"] = '';
        $wow_html["chart"]["hourly"]["dataset_dew_point"] = '';
        $wow_html["chart"]["hourly"]["config"] = '';
        $wow_html["chart"]["hourly"]["data"] = '';
        $wow_html["chart"]["hourly"]["options"] = '';
        $wow_html["chart"]["hourly"]["chart"] = '';
        $wow_html["chart"]["hourly"]["cmd"] = '';

	    if ($wow_opt["hours_forecast_no"] > 0) {
            $wow_html["chart"]["hourly"]["labels"] .= 'const hourly_labels_'.$chart_id.' = [';
            $wow_html["chart"]["hourly"]["dataset_temperature"] .= 'const hourly_temperature_dataset_'.$chart_id.' = [';
            $wow_html["chart"]["hourly"]["dataset_feels_like"] .= 'const hourly_feels_like_dataset_'.$chart_id.' = [';
            $wow_html["chart"]["hourly"]["dataset_dew_point"] .= 'const hourly_dew_point_dataset_'.$chart_id.' = [';
			foreach ($wow_data["hourly"] as $i => $value) {
                $wow_html["chart"]["hourly"]["labels"] .= '"' . ($value["time"] != 'Now' ? date('D', $value["timestamp"]) . ' ' : '') . $value["time"] . '",';
                $wow_html["chart"]["hourly"]["dataset_temperature"] .= '"' . $value["temperature"] . '",';
                $wow_html["chart"]["hourly"]["dataset_feels_like"] .= '"' . $value["feels_like"] . '",';
                $wow_html["chart"]["hourly"]["dataset_dew_point"] .= '"' . $value["dew_point"] . '",';
			}
            $wow_html["chart"]["hourly"]["labels"] .= '];';
            $wow_html["chart"]["hourly"]["dataset_temperature"] .= '];';
            $wow_html["chart"]["hourly"]["dataset_feels_like"] .= '];';
            $wow_html["chart"]["hourly"]["dataset_dew_point"] .= '];';

            $wow_html["chart"]["hourly"]["config"] .= 'const hourly_config_'.$chart_id.' = { type: "line", options: hourly_options_'.$chart_id.', data: hourly_data_'.$chart_id.',};';
            $wow_html["chart"]["hourly"]["options"] .= 'const hourly_options_'.$chart_id.' = {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { intersect: false, mode: "index" },
                    plugins: {title: {display: true, text: "Hourly Temperatures" },
                        tooltip: { callbacks: { label: function(context) { var label = context.dataset.label || ""; if (label) { label += ": "; }
                        if (context.parsed.y !== null) { label += context.parsed.y + " '.$temperature_unit_character.'"; }
                        return label; } } } },
                    scales: { y: { title: { display: true, text: "'.$temperature_unit_text.'" } } }
                    };';
            $wow_html["chart"]["hourly"]["data"] .= 'const hourly_data_'.$chart_id.' = {
                                                                      labels: hourly_labels_'.$chart_id.',
                                                                      datasets: [{
                                                                        label: "Temperature",
                                                                        data: hourly_temperature_dataset_'.$chart_id.',
                                                                        fill: false,
                                                                        borderColor: "'.$wow_opt["chart_temperature_color"].'",
                                                                        borderWidth: 1,
                                                                        tension: 0.3,
                                                                        pointBorderWidth: 0,
                                                                        },{
                                                                        label: "Feels Like",
                                                                        data: hourly_feels_like_dataset_'.$chart_id.',
                                                                        fill: false,
                                                                        borderColor: "'.$wow_opt["chart_feels_like_color"].'",
                                                                        borderWidth: 1,
                                                                        tension: 0.3,
                                                                        pointBorderWidth: 0,
                                                                        },{
                                                                        label: "Dew Point",
                                                                        data: hourly_dew_point_dataset_'.$chart_id.',
                                                                        fill: false,
                                                                        borderColor: "'.$wow_opt["chart_dew_point_color"].'",
                                                                        borderWidth: 1,
                                                                        tension: 0.3,
                                                                        pointBorderWidth: 0,
                                                                      }]
                                                                    };';
            $wow_html["chart"]["hourly"]["chart"] .= 'jQuery(document).ready(function(){
                                                        var ctx = jQuery("#'.$wow_opt["container_chart_hourly_div"].'");
                                                        var hourlyChart = new Chart(ctx, hourly_config_'.$chart_id.');
                                                        });';

$wow_html["chart"]["hourly"]["cmd"] =
        '<div class="wow-chart-container" style="position: relative; height:'.$wow_opt["chart_height"].'px; width:100%">
        <canvas id="'.$wow_opt["container_chart_hourly_div"].'" style="'.wow_css_color('background-color', $wow_opt["chart_background_color"]).wow_css_border($wow_opt["chart_border_color"],$wow_opt["chart_border_width"],$wow_opt["chart_border_style"],$wow_opt["chart_border_radius"]).'" aria-label="Hourly Temperatures" role="img"></canvas></div><script>' .
        $wow_html["chart"]["hourly"]["labels"] .
        $wow_html["chart"]["hourly"]["dataset_temperature"] .
        $wow_html["chart"]["hourly"]["dataset_feels_like"] .
        $wow_html["chart"]["hourly"]["dataset_dew_point"] .
        $wow_html["chart"]["hourly"]["data"] .
        $wow_html["chart"]["hourly"]["options"] .
        $wow_html["chart"]["hourly"]["config"] .
        $wow_html["chart"]["hourly"]["chart"] .
        '</script>';
		}
        //daily
        $chart_id = wow_unique_id($wow_opt["id"], '_');
        $wow_html["chart"]["daily"] = [];
        $wow_opt["container_chart_daily_div"] = 'wow-daily-chart-canvas-'.$chart_id;
        $wow_html["chart"]["daily"]["labels"] = '';
        $wow_html["chart"]["daily"]["dataset_temperature"] = '';
        $wow_html["chart"]["daily"]["dataset_feels_like"] = '';
        $wow_html["chart"]["daily"]["config"] = '';
        $wow_html["chart"]["daily"]["data"] = '';
        $wow_html["chart"]["daily"]["options"] = '';
        $wow_html["chart"]["daily"]["chart"] = '';
        $wow_html["chart"]["daily"]["cmd"] = '';

	    if ($wow_opt["hours_forecast_no"] > 0) {
            $wow_html["chart"]["daily"]["labels"] .= 'const daily_labels_'.$chart_id.' = [';
            $wow_html["chart"]["daily"]["dataset_temperature"] .= 'const daily_temperature_dataset_'.$chart_id.' = [';
            $wow_html["chart"]["daily"]["dataset_feels_like"] .= 'const daily_feels_like_dataset_'.$chart_id.' = [';
			foreach ($wow_data["daily"] as $i => $value) {
                $wow_html["chart"]["daily"]["labels"] .= '"","' . $value["day"] . '","","",';
                $wow_html["chart"]["daily"]["dataset_temperature"] .= '"' . $value["temperature_morning"] . '","' . $value["temperature_day"] . '","' . $value["temperature_evening"] . '","' . $value["temperature_night"] . '",';
                $wow_html["chart"]["daily"]["dataset_feels_like"] .= '"' . $value["feels_like_morning"] . '","' . $value["feels_like_day"] . '","' . $value["feels_like_evening"] . '","' . $value["feels_like_night"] . '",';
			}
            $wow_html["chart"]["daily"]["labels"] .= '];';
            $wow_html["chart"]["daily"]["dataset_temperature"] .= '];';
            $wow_html["chart"]["daily"]["dataset_feels_like"] .= '];';

            $wow_html["chart"]["daily"]["config"] .= 'const daily_config_'.$chart_id.' = { type: "line", options: daily_options_'.$chart_id.', data: daily_data_'.$chart_id.',};';
            $wow_html["chart"]["daily"]["options"] .= 'const daily_options_'.$chart_id.' = {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { intersect: false, mode: "index" },
                    plugins: {title: {display: true, text: "Daily Temperatures" },
                        tooltip: { callbacks: { label: function(context) { var label = context.dataset.label || ""; if (label) { label += ": "; }
                        if (context.parsed.y !== null) { label += context.parsed.y + " '.$temperature_unit_character.'"; }
                        return label; } } } },
                    scales: { y: { title: { display: true, text: "'.$temperature_unit_text.'" } } }
                    };';
            $wow_html["chart"]["daily"]["data"] .= 'const daily_data_'.$chart_id.' = {
                                                                      labels: daily_labels_'.$chart_id.',
                                                                      datasets: [{
                                                                        label: "Temperature",
                                                                        data: daily_temperature_dataset_'.$chart_id.',
                                                                        fill: false,
                                                                        borderColor: "'.$wow_opt["chart_temperature_color"].'",
                                                                        borderWidth: 1,
                                                                        tension: 0.3,
                                                                        pointBorderWidth: 0,
                                                                        },{
                                                                        label: "Feels Like",
                                                                        data: daily_feels_like_dataset_'.$chart_id.',
                                                                        fill: false,
                                                                        borderColor: "'.$wow_opt["chart_feels_like_color"].'",
                                                                        borderWidth: 1,
                                                                        tension: 0.3,
                                                                        pointBorderWidth: 0,
                                                                      }]
                                                                    };';
            $wow_html["chart"]["daily"]["chart"] .= 'jQuery(document).ready(function(){
                                                        var ctx = jQuery("#'.$wow_opt["container_chart_daily_div"].'");
                                                        var dailyChart = new Chart(ctx, daily_config_'.$chart_id.');
                                                        });';

$wow_html["chart"]["daily"]["cmd"] =
        '<div class="wow-chart-container" style="position: relative; height:'.$wow_opt["chart_height"].'px; width:100%">
        <canvas id="'.$wow_opt["container_chart_daily_div"].'" style="'.wow_css_color('background-color', $wow_opt["chart_background_color"]).wow_css_border($wow_opt["chart_border_color"],$wow_opt["chart_border_width"],$wow_opt["chart_border_style"],$wow_opt["chart_border_radius"]).'" aria-label="Daily Temperatures" role="img"></canvas></div><script>' .
        $wow_html["chart"]["daily"]["labels"] .
        $wow_html["chart"]["daily"]["dataset_temperature"] .
        $wow_html["chart"]["daily"]["dataset_feels_like"] .
        $wow_html["chart"]["daily"]["data"] .
        $wow_html["chart"]["daily"]["options"] .
        $wow_html["chart"]["daily"]["config"] .
        $wow_html["chart"]["daily"]["chart"] .
        '</script>';
		}


        //Table
        if (!empty($wow_opt["table_border_color"])) {
            $wow_opt["custom_css"] .= '.wow-table.table-bordered > tbody > tr > td, .wow-table .table-bordered > tbody > tr > th, .wow-table.table-bordered > tfoot > tr > td, .wow-table.table-bordered > tfoot > tr > th, .wow-table.table-bordered > thead > tr > td, .wow-table.table-bordered > thead > tr > th { ' . wow_css_border($wow_opt["table_border_color"], $wow_opt["table_border_width"],$wow_opt["table_border_style"],$wow_opt["table_border_radius"]) .'}';
        }
        //Hourly
	    if ($wow_opt["hours_forecast_no"] > 0) {
	        $wow_opt["container_table_hourly_div"] = wow_unique_id('wow-table_hourly-container-'.$wow_opt["id"]);
            $wow_html["table"]["hourly"] = '<div class="table-responsive wow-table wow-table-hours"><table class="table table-sm table-bordered" style="'.wow_css_color('background-color', $wow_opt["table_background_color"]).wow_css_color("color",$wow_opt["table_text_color"]).
		                                    wow_css_border($wow_opt["table_border_color"],$wow_opt["table_border_width"],$wow_opt["table_border_style"], $wow_opt["table_border_radius"]).'">';
            $wow_html["table"]["hourly"] .= '<thead><tr>';
            $wow_html["table"]["hourly"] .= '<th>Time</th>';
            $wow_html["table"]["hourly"] .= '<th colspan="2">Conditions</th>';
            $wow_html["table"]["hourly"] .= '<th>Temp.</th>';
            $wow_html["table"]["hourly"] .= '<th>Feels Like</th>';
            $wow_html["table"]["hourly"] .= '<th>Precip</th>';
            $wow_html["table"]["hourly"] .= '<th>Amount</th>';
            $wow_html["table"]["hourly"] .= '<th>Cloud Cover</th>';
            $wow_html["table"]["hourly"] .= '<th>Dew Point</th>';
            $wow_html["table"]["hourly"] .= '<th>Humidity</th>';
            $wow_html["table"]["hourly"] .= '<th>Wind</th>';
            $wow_html["table"]["hourly"] .= '<th>Gust</th>';
            $wow_html["table"]["hourly"] .= '<th>Pressure</th>';
            $wow_html["table"]["hourly"] .= '<th>Visibility</th>';
            $wow_html["table"]["hourly"] .= '<th>UV Index</th>';
            $wow_html["table"]["hourly"] .= '</tr></thead>';
            $wow_html["table"]["hourly"] .= '<tbody>';
			foreach ($wow_data["hourly"] as $i => $value) {
                $wow_html["table"]["hourly"] .= '<tr>';
                $wow_html["table"]["hourly"] .= '<td>' . date('D', $value["timestamp"]) . ($wow_opt["hours_time_icons"] == 'yes' ? hour_icon($value["time"], $wow_opt["table_text_color"]) : '<br>' . $value["time"]) . '</td>';
                $wow_html["table"]["hourly"] .= '<td class="border-right-0">' . weatherIcon($wow_opt["iconpack"], $value["condition_id"], $value["day_night"], $value["description"]) . '</td><td class="border-left-0 small">' . $value["description"] . '</td>';
                $wow_html["table"]["hourly"] .= '<td class="wow-temperature">' . $value["temperature"] . '</td>';
                $wow_html["table"]["hourly"] .= '<td class="wow-temperature">' . $value["feels_like"] . '</td>';
                $wow_html["table"]["hourly"] .= '<td>' . $value["rain_chance"] . '</td>';
                $wow_html["table"]["hourly"] .= '<td>' . $value["precipitation"] . '</td>';
                $wow_html["table"]["hourly"] .= '<td>' . $value["cloudiness"] . '</td>';
                $wow_html["table"]["hourly"] .= '<td class="wow-temperature">' . $value["dew_point"] . '</td>';
                $wow_html["table"]["hourly"] .= '<td>' . $value["humidity"] . '</td>';
                $wow_html["table"]["hourly"] .= '<td>' . $value["wind_speed"] .' '. $value["wind_direction"] . '</td>';
                $wow_html["table"]["hourly"] .= '<td>' . $value["wind_gust"] . '</td>';
                $wow_html["table"]["hourly"] .= '<td>' . $value["pressure"] . '</td>';
                $wow_html["table"]["hourly"] .= '<td>' . $value["visibility"] . '</td>';
                $wow_html["table"]["hourly"] .= '<td>' . $value["uv_index"] . '</td>';
                $wow_html["table"]["hourly"] .= '</tr>';
			}
            $wow_html["table"]["hourly"] .= '</tbody>';
            $wow_html["table"]["hourly"] .= '</table>';
            $wow_html["table"]["hourly"] .= '</div>';
		}

        //daily
	    if ($wow_opt["days_forecast_no"] > 0) {
	        $wow_opt["container_table_daily_div"] = wow_unique_id('wow-table_daily-container-'.$wow_opt["id"]);
            $wow_html["table"]["daily"] = '<div class="table-responsive wow-table wow-table-hours"><table class="table table-sm table-bordered" style="'.wow_css_color('background-color', $wow_opt["table_background_color"]).wow_css_color("color",$wow_opt["table_text_color"]).
		                                    wow_css_border($wow_opt["table_border_color"],$wow_opt["table_border_width"],$wow_opt["table_border_style"], $wow_opt["table_border_radius"]).'">';
            $wow_html["table"]["daily"] .= '<thead><tr>';
            $wow_html["table"]["daily"] .= '<th>Day</th>';
            $wow_html["table"]["daily"] .= '<th colspan="2">Conditions</th>';
            $wow_html["table"]["daily"] .= '<th>Min Temp.</th>';
            $wow_html["table"]["daily"] .= '<th>Max Temp.</th>';
            $wow_html["table"]["daily"] .= '<th>Precip</th>';
            $wow_html["table"]["daily"] .= '<th>Amount</th>';
            $wow_html["table"]["daily"] .= '<th>Cloud Cover</th>';
            $wow_html["table"]["daily"] .= '<th>Dew Point</th>';
            $wow_html["table"]["daily"] .= '<th>Humidity</th>';
            $wow_html["table"]["daily"] .= '<th>Wind</th>';
            $wow_html["table"]["daily"] .= '<th>Gust</th>';
            $wow_html["table"]["daily"] .= '<th>Pressure</th>';
            $wow_html["table"]["daily"] .= '<th>UV Index</th>';
            $wow_html["table"]["daily"] .= '</tr></thead>';
            $wow_html["table"]["daily"] .= '<tbody>';
			foreach ($wow_data["daily"] as $i => $value) {
                $wow_html["table"]["daily"] .= '<tr>';
                $wow_html["table"]["daily"] .= '<td>' . $value["day"] . '</td>';
                $wow_html["table"]["daily"] .= '<td class="border-right-0">' . weatherIcon($wow_opt["iconpack"], $value["condition_id"], "day", $value["description"]) . '</td><td class="border-left-0 small">' . $value["description"] . '</td>';
                $wow_html["table"]["daily"] .= '<td class="wow-temperature">' . $value["temperature_minimum"] . '</td>';
                $wow_html["table"]["daily"] .= '<td class="wow-temperature">' . $value["temperature_maximum"] . '</td>';
                $wow_html["table"]["daily"] .= '<td>' . $value["rain_chance"] . '</td>';
                $wow_html["table"]["daily"] .= '<td>' . $value["precipitation"] . '</td>';
                $wow_html["table"]["daily"] .= '<td>' . $value["cloudiness"] . '</td>';
                $wow_html["table"]["daily"] .= '<td class="wow-temperature">' . $value["dew_point"] . '</td>';
                $wow_html["table"]["daily"] .= '<td>' . $value["humidity"] . '</td>';
                $wow_html["table"]["daily"] .= '<td>' . $value["wind_speed"] .' '. $value["wind_direction"] . '</td>';
                $wow_html["table"]["daily"] .= '<td>' . $value["wind_gust"] . '</td>';
                $wow_html["table"]["daily"] .= '<td>' . $value["pressure"] . '</td>';
                $wow_html["table"]["daily"] .= '<td>' . $value["uv_index"] . '</td>';
                $wow_html["table"]["daily"] .= '</tr>';
			}
            $wow_html["table"]["daily"] .= '</tbody>';
            $wow_html["table"]["daily"] .= '</table>';
            $wow_html["table"]["daily"] .= '</div>';
		}

		if ($wow_opt["custom_css"]) {
	    	$wow_html["custom_css"] = '<style>'. $wow_opt["custom_css"] . '</style>';
		}

	    $wow_html["container"]["end"] .= '</div>';
	    deleteWhitespaces($wow_html);

        if ($wow_opt["template"] == "debug") {
            $wow_sys_opt = get_option('wow_option_name');
        }

    	ob_start();
	    if ( locate_template('owm-weather/content-owmweather.php', false) != '' && $wow_opt["template"] == 'Default' ) {
	    	include get_stylesheet_directory() . '/owm-weather/content-owmweather.php';
	    } elseif ( $wow_opt["template"] != 'Default' ) {
	    	if ( locate_template('owm-weather/content-owmweather-' . $wow_opt["template"] . '.php', false) != '' ) {
		    	include get_stylesheet_directory() . '/owm-weather/content-owmweather-' . $wow_opt["template"] . '.php';
	    	} else {
	    		include dirname( __FILE__ ) . '/template/content-owmweather-' . $wow_opt["template"] . '.php';
	    	}
	    } else { //Default
	    	include ( dirname( __FILE__ ) . '/template/content-owmweather.php');
	    }
    	$wow_html["html"] = ob_get_clean();

	  	$response = array();
	  	$response['weather'] = $wow_params["weather_id"];
	  	$response['html'] = $wow_html["html"];
		wp_send_json_success($response);
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Fix shortcode bug in widget text
///////////////////////////////////////////////////////////////////////////////////////////////////
add_filter( 'widget_text', 'shortcode_unautop');
add_filter( 'widget_text', 'do_shortcode', 11);

///////////////////////////////////////////////////////////////////////////////////////////////////
//Display shortcode in listing view
///////////////////////////////////////////////////////////////////////////////////////////////////

add_filter('manage_edit-wow-weather_columns', 'wow_set_custom_edit_wow_weather_columns');
add_action('manage_wow-weather_posts_custom_column', 'wow_custom_wow_weather_column', 10, 2);

function wow_set_custom_edit_wow_weather_columns($columns) {
    $columns['wow-weather'] = __('Shortcode');
    return $columns;
}

function wow_custom_wow_weather_column($column, $post_id) {

    $wow_weather_meta = get_post_meta($post_id, "_wow-weather_meta", true);
    $wow_weather_meta = ($wow_weather_meta != '') ? json_decode($wow_weather_meta) : array();

    switch ($column) {
        case 'wow-weather':
            echo "[wow-weather id=\"$post_id\" /]";
            break;
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Weather Custom Post Type
///////////////////////////////////////////////////////////////////////////////////////////////////

// Register Custom Post Type
function wpowmweather_weather() {
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

	register_post_type( 'wow-weather', $args );
}

// Hook into the 'init' action
add_action( 'init', 'wpowmweather_weather', 0 );

///////////////////////////////////////////////////////////////////////////////////////////////////
//Weather Custom Post Type Messages
///////////////////////////////////////////////////////////////////////////////////////////////////

function wow_set_messages($messages) {
	global $post, $post_ID;
	$post_type = 'wow-weather';

	$obj = get_post_type_object($post_type);
	$singular = $obj->labels->singular_name;

	$messages[$post_type] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => sprintf( __($singular.' updated.'), esc_url( get_permalink($post_ID) ) ),
		2 => __('Custom field updated.'),
		3 => __('Custom field deleted.'),
		4 => __($singular.' updated.'),
		5 => isset($_GET['revision']) ? sprintf( __($singular.' restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6 => sprintf( __($singular.' published.'), esc_url( get_permalink($post_ID) ) ),
		7 => __('Page saved.'),
		8 => sprintf( __($singular.' submitted.'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
		9 => sprintf( __($singular.' scheduled for: ') . '<strong>%1$s</strong>.', date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __($singular.' draft updated.'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);
	return $messages;
}

add_filter('post_updated_messages', 'wow_set_messages' );

///////////////////////////////////////////////////////////////////////////////////////////////////
//OWM WEATHER Notices
///////////////////////////////////////////////////////////////////////////////////////////////////
function wpowmweather_notice() {
	$wow_advanced_api_key = get_option('wow_option_name');
	if ( is_plugin_active( 'owm-weather/owmweather.php' ) && !isset($wow_advanced_api_key['wow_advanced_api_key'])) {
	    ?>
	    <div class="error notice">
	        <p><a href="<?php echo admin_url('admin.php?page=wow-settings-admin#tab_advanced'); ?>"><?php _e( 'OWM Weather: Please enter your own OpenWeatherMap API key to avoid limits requests.', 'owm-weather' ); ?></a></p>
	    </div>
	    <?php
	}
}
add_action( 'admin_notices', 'wpowmweather_notice' );

///////////////////////////////////////////////////////////////////////////////////////////////////
//Utility functions
///////////////////////////////////////////////////////////////////////////////////////////////////
function generate_hour_options($current) {
	$str = '<option ' . selected( 0, intval($current), false ) . ' value="0">' . __( "None", 'owm-weather' ) . '</option>';

    for ($i=1; $i<=48; $i++) {
        if ($i == 1) {
            $h = 'Now';
        } else if ($i == 2) {
            $h = 'Now + 1 hour';
        } else {
            $h = 'Now + ' . ($i-1) . ' hours';
        }
		$str .= '<option ' . selected( $i, intval($current), false ) . ' value="' . $i . '">' . __( $h, 'owm-weather' ) . '</option>';
    }

    return $str;
}

function getWindDirection($deg) {
    $dirs = array('N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW', 'N');
    return $dirs[round($deg/45)];
}

function getConvertedWindSpeed($speed, $unit, $bypass_unit) {
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

function getWindSpeedUnit($unit, $bypass_unit) {
	switch ($bypass_unit) {
        case "1": //MI/H
        	return 'mi/h';
          	break;
        case "2": //M/S
			return 'm/s';
          	break;
        case "3": //KM/H
			return 'km/h';
          	break;
        case "4": //KNOTS
        	return 'kt';
          	break;
        default: //KNOTS
        	if ($unit =='metric') {
				return 'm/s';
			} else {
				return 'mi/h';
			}
			break;
	}
}

function getConvertedPrecipitation($unit, $p) {
    if ($unit == 'imperial') {
        return number_format($p / 25.4, 1);
    }

    return number_format($p,0);
}

function getConvertedDistance($unit, $p) {
    if ($unit == 'imperial') {
        return ($p ? number_format($p / 1609.344, 1)." mi" : "");
    }

    return ($p ? number_format($p / 1000, 1)." km" : "");
}

function converthp2iom($unit, $p) {
    if ($unit == 'imperial') {
        return number_format($p / 33.86389, 2);
    }

    return number_format($p,0);
}

function getDefault($id, $field, $default) {
	$val = get_post_meta($id, $field, true);
	return !empty($val) ? $val : $default;
}

function getBypassDefault($bypass, $field, $default) {
	$val = wow_get_bypass($bypass, $field);
	return !empty($val) ? $val : $default;
}

function wow_unique_id( $prefix = '', $delim = '-' ) {
    static $id_counter = 0;
    return $prefix . (isset($_POST['counter']) ? $delim . $_POST['counter'] : '') . $delim . (string) ++$id_counter;
}

function deleteWhitespaces(&$arr) {
    if ($arr) {
        foreach ($arr as &$value) {
            if (is_array($value)) {
                deleteWhitespaces($value);
            } else {
                $value = preg_replace('/\s+/', ' ', $value);
            }
        }
    }
}

function esc_html_all(&$arr) {
    if ($arr) {
        foreach ($arr as $key => &$value) {
            if (is_array($value)) {
                esc_html_all($value);
            } else {
                if ($key != 'owm_link') {
                    $value = esc_html($value);
                }
            }
        }
    }
}

function IPtoLocation() {
    global $wp;

    if (!empty($_SERVER['HTTP_CLIENT_IP'])){
        //ip from share internet
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
        //ip pass from proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    $transient_key = 'myweather_iplocation_' . $ip;

    if (false === ($ipData = get_transient($transient_key))) {
    	$apiURL = 'https://tools.keycdn.com/geo.json?host='.$ip;
        $request_headers = [];
        $request_headers[] = 'User-Agent: keycdn-tools:' . home_url($wp->request);

    	$ch = curl_init($apiURL);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    	$apiResponse = curl_exec($ch);
    	if($apiResponse === false) {
            $msg = curl_error($ch);
            curl_close($ch);
            return false;
        }
    	curl_close($ch);

    	$ipData = json_decode($apiResponse, true);
    	set_transient($transient_key, $ipData);
    }

	return !empty($ipData) ? $ipData : false;
}
