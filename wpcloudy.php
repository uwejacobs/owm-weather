<?php
/*
Plugin Name: WP Cloudy 2
Plugin URI: https://github.com/uwejacobs/wp-cloudy-2
Description: WP Cloudy 2 is a powerful weather plugin for WordPress, based on Open Weather Map API, using Custom Post Types and shortcodes, bundled with a ton of features.
Version: 4.5.1
Author: Uwe Jacobs
Author URI: https://github.com/uwejacobs
Original Author: Benjamin DENIS
Original Author URI: https://wpcloudy.com/
License: GPLv2
Text Domain: wp-cloudy
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

function weather_activation() {
    global $wpdb;
	$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_myweather%' ");
	$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_myweather%' ");
}
register_activation_hook(__FILE__, 'weather_activation');
function weather_deactivation() {
}
register_deactivation_hook(__FILE__, 'weather_deactivation');

define( 'WPCLOUDY_VERSION', '4.5.1' );

///////////////////////////////////////////////////////////////////////////////////////////////////
//Shortcut settings page
///////////////////////////////////////////////////////////////////////////////////////////////////

add_filter('plugin_action_links', 'wpc_plugin_action_links', 10, 2);

function wpc_plugin_action_links($links, $file) {
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        $settings_link = '<a href="' . admin_url('admin.php?page=wpc-settings-admin').'">'.__('Settings', 'wp-cloudy').'</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Translation
///////////////////////////////////////////////////////////////////////////////////////////////////
function wpcloudy_init() {
	load_plugin_textdomain( 'wp-cloudy', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

	//Admin panel + Dashboard widget
	if ( is_admin() ) {
		require_once dirname( __FILE__ ) . '/wpcloudy-admin.php';
		require_once dirname( __FILE__ ) . '/wpcloudy-export.php';
	    require_once dirname( __FILE__ ) . '/wpcloudy-widget.php';
	    require_once dirname( __FILE__ ) . '/wpcloudy-pointers.php';
	}
}
add_action('plugins_loaded', 'wpcloudy_init');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Enqueue styles Front-end
///////////////////////////////////////////////////////////////////////////////////////////////////
function wpcloudy_async_js($url) {
    if (strpos($url, '#async')===false)
        return $url;
    else if (is_admin())
        return str_replace('#async', '', $url);
    else
        return str_replace('#async', '', $url)."' async='async";
}
add_filter('clean_url', 'wpcloudy_async_js', 11, 1);

function wpcloudy_styles() {
	global $post;

	wp_enqueue_script( 'wpc-ajax', plugins_url('js/wp-cloudy-ajax.js', __FILE__), array('jquery'), '', true );
	$wpcAjax = array(
        'wpc_nonce' => wp_create_nonce('wpc_get_weather_nonce'),
        'wpc_url' => admin_url( 'admin-ajax.php' ) . "?lang=" . substr(get_locale(),0,2),
    );
	wp_localize_script( 'wpc-ajax', 'wpcAjax', $wpcAjax);

	wp_register_style('wpcloudy', plugins_url('css/wpcloudy.min.css', __FILE__));
	wp_enqueue_style('wpcloudy');

	wp_register_style('wpcloudy-anim', plugins_url('css/wpcloudy-anim.min.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'wpcloudy_styles');

///////////////////////////////////////////////////////////////////////////////////////////////////
//Loads the JS/CSS for Theme1 - Theme2
///////////////////////////////////////////////////////////////////////////////////////////////////

function wpc_add_themes_scripts() {
	wp_register_style( 'wpc-flexslider-css', plugins_url( 'css/flexslider.css', __FILE__ ));
	wp_register_script( 'wpc-flexslider-js', plugins_url( 'js/jquery.flexslider-min.js#async', __FILE__ ));
	wp_register_style( 'bootstrap-css', plugins_url( 'css/bootstrap.min.css', __FILE__ ));
	wp_register_script( 'bootstrap-js', plugins_url( 'js/bootstrap.min.js#async', __FILE__ ));
	wp_register_script( 'popper-js', plugins_url( 'js/popper.min.js#async', __FILE__ ));
	wp_register_script( 'chart-js', plugins_url( 'js/chart.min.js#async', __FILE__ ));
}
add_action( 'wp_enqueue_scripts', 'wpc_add_themes_scripts', 10, 1 );

///////////////////////////////////////////////////////////////////////////////////////////////////
//Loads the JS/CSS in admin
///////////////////////////////////////////////////////////////////////////////////////////////////

//Dashboard
function wpc_add_dashboard_scripts() {
	wp_enqueue_script( 'wpc-ajax', plugins_url('js/wp-cloudy-ajax.js', __FILE__), array('jquery'), '', true );

	$wpcAjax = array(
        'wpc_nonce' => wp_create_nonce('wpc_get_weather_nonce'),
        'wpc_url' => admin_url( 'admin-ajax.php' ) . "?lang=" . substr(get_locale(),0,2),
    );
	wp_localize_script( 'wpc-ajax', 'wpcAjax', $wpcAjax);

	wp_register_style('wpcloudy', plugins_url('css/wpcloudy.min.css', __FILE__));
	wp_enqueue_style('wpcloudy');

	wp_register_style('wpcloudy-anim', plugins_url('css/wpcloudy-anim.min.css', __FILE__));

}
add_action('admin_head-index.php', 'wpc_add_dashboard_scripts');

//Admin + Custom Post Type (new, listing)
function wpc_add_admin_scripts( $hook ) {

global $post;

	if ( $hook == 'post-new.php' || $hook == 'post.php') {


        if ( 'wpc-weather' === $post->post_type) {
			wp_register_style( 'wpcloudy-admin', plugins_url('css/wpcloudy-admin.min.css', __FILE__));
			wp_enqueue_style( 'wpcloudy-admin' );

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'color-picker-js', plugins_url('js/color-picker.js', __FILE__), array( 'wp-color-picker' ) );

			wp_enqueue_script( 'tabs-js', plugins_url( 'js/tabs.js', __FILE__ ), array( 'jquery-ui-tabs' ) );

			wp_enqueue_script( 'handlebars-js', plugins_url( 'js/handlebars-v1.3.0.js', __FILE__ ), array('typeahead-bundle-js') );
			wp_enqueue_script( 'typeahead-bundle-js', plugins_url( 'js/typeahead.bundle.min.js', __FILE__ ), array('jquery') , '2.0');
			wp_enqueue_script( 'autocomplete-js', plugins_url( 'js/wpc-autocomplete.js', __FILE__ ), '', '2.0', true );

		}
	}
}
add_action( 'admin_enqueue_scripts', 'wpc_add_admin_scripts', 10, 1 );

//WP Cloudy Options page
function wpc_add_admin_options_scripts() {
			wp_register_style( 'wpcloudy-admin', plugins_url('css/wpcloudy-admin.min.css', __FILE__));
			wp_enqueue_style( 'wpcloudy-admin' );

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'color-picker-js', plugins_url('js/color-picker.js', __FILE__), array( 'wp-color-picker' ) );
			wp_enqueue_script( 'tabs-js', plugins_url( 'js/tabs.js', __FILE__ ), array( 'jquery-ui-tabs' ) );
}

if (isset($_GET['page']) && ($_GET['page'] == 'wpc-settings-admin')) {

	add_action('admin_enqueue_scripts', 'wpc_add_admin_options_scripts', 10, 1);
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
// function wpc_gutenberg_boilerplate_block() {
//     wp_register_script('gutenberg-wpcloudy', plugins_url( 'js/blocks.build.js', __FILE__ ), array( 'wp-blocks', 'wp-element' ));

//     register_block_type( 'gutenberg-wpcloudy/wpcloudy', array('editor_script' => 'gutenberg-wpcloudy'));

//     wp_enqueue_script('gutenberg-wpcloudy');
// }
// add_action( 'enqueue_block_editor_assets', 'wpc_gutenberg_boilerplate_block' );

///////////////////////////////////////////////////////////////////////////////////////////////////
//Get all registered post types
///////////////////////////////////////////////////////////////////////////////////////////////////

function wpc_get_post_types() {
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
add_action('admin_head', 'wpc_add_button_v4');

function wpc_add_button_v4() {
    global $typenow;

    if ( !current_user_can('edit_posts') && !current_user_can('edit_pages') ) {
    	return;
    }

    if( ! in_array( $typenow, wpc_get_post_types() ) )
        return;

	if ( get_user_option('rich_editing') == 'true') {
		add_filter("mce_external_plugins", "wpc_add_button_v4_plugin");
		add_filter('mce_buttons', 'wpc_add_button_v4_register');
	}
}
function wpc_add_button_v4_plugin($plugin_array) {
    $plugin_array['wpc_button_v4'] = plugins_url( 'js/wpc-tinymce.js', __FILE__ );
    return $plugin_array;
}
function wpc_add_button_v4_register($buttons) {
   array_push($buttons, "wpc_button_v4");
   return $buttons;
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Add duplicate link in WPC List view
///////////////////////////////////////////////////////////////////////////////////////////////////

function wpc_duplicate_post_as_draft(){
	global $wpdb;
	if (! ( isset( $_GET['post']) || isset( $_POST['post'])  || ( isset($_REQUEST['action']) && 'wpc_duplicate_post_as_draft' == $_REQUEST['action'] ) ) ) {
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
add_action( 'admin_action_wpc_duplicate_post_as_draft', 'wpc_duplicate_post_as_draft', 999 );

function wpc_duplicate_post_link( $actions, $post ) {
	if ($post->post_type=='wpc-weather' && current_user_can('edit_posts')) {
		$actions['duplicate'] = '<a href="admin.php?action=wpc_duplicate_post_as_draft&amp;post=' . $post->ID . '" title="'.__('Duplicate this item','wp-cloudy').'" rel="permalink">'.__('Duplicate','wp-cloudy').'</a>';
	}
	return $actions;
}

add_filter( 'post_row_actions', 'wpc_duplicate_post_link', 999, 2 );

///////////////////////////////////////////////////////////////////////////////////////////////////
//Display metabox in Weather Custom Post Type
///////////////////////////////////////////////////////////////////////////////////////////////////

add_action('add_meta_boxes','init_metabox');
function init_metabox(){
	add_meta_box('wpcloudy_basic', __('WP Cloudy Settings','wp-cloudy') .' - <a href="'.admin_url("options-general.php?page=wpc-settings-admin").'">'.__('WP Cloudy global settings','wp-cloudy').'</a>', 'wpcloudy_basic', 'wpc-weather', 'advanced');
	add_meta_box('wpcloudy_shortcode', 'WP Cloudy Shortcode', 'wpcloudy_shortcode', 'wpc-weather', 'side');
}

function wpcloudy_shortcode($post){
	_e( 'Copy and paste this shortcode anywhere in posts, pages, text widgets: ', 'wp-cloudy' );
	echo "<div class='shortcode'>";
	echo "<span class='wpc-highlight'>[wpc-weather id=\"";
	echo get_the_ID();
	echo "\"/]</span>";
	echo "</div>";

	echo '<div class="shortcode-php">';
	_e( 'If you need to display this weather anywhere in your theme, simply copy and paste this code snippet in your PHP file like sidebar.php: ', 'wp-cloudy' );
	echo "<span class='wpc-highlight'>echo do_shortcode('[wpc-weather id=\"".get_the_ID()."\"]');</span>";
	echo "</div>";
}

function wpcloudy_basic($post){
    $id = $post->ID;

    convertSettings($post->ID);

    $wpc_opt = [];
	$wpc_opt["city"] 					    = get_post_meta($id,'_wpcloudy_city',true);
	$wpc_opt["custom_city_name"]		    = get_post_meta($id,'_wpcloudy_city_name',true);
	$wpc_opt["id_owm"]					    = get_post_meta($id,'_wpcloudy_id_owm',true);
	$wpc_opt["longitude"] 			        = get_post_meta($id,'_wpcloudy_longitude',true);
	$wpc_opt["latitude"] 		    	    = get_post_meta($id,'_wpcloudy_latitude',true);
	$wpc_opt["country_code"] 		        = get_post_meta($id,'_wpcloudy_country_code',true);
	$wpc_opt["temperature_unit"] 			= get_post_meta($id,'_wpcloudy_unit',true);
	$wpc_opt["time_format"]				    = get_post_meta($id,'_wpcloudy_time_format',true);
	$wpc_opt["custom_timezone"]	    		= get_post_meta($id,'_wpcloudy_custom_timezone',true);
	$wpc_opt["owm_language"] 		    	= get_post_meta($id,'_wpcloudy_owm_language',true);
	$wpc_opt["gtag"]              		    = get_post_meta($id,'_wpcloudy_gtag',true);
	$wpc_opt["bypass_exclude"]     		    = get_post_meta($id,'_wpcloudy_bypass_exclude',true);
	$wpc_opt["current_weather_symbol"]		= get_post_meta($id,'_wpcloudy_current_weather_symbol',true);
	$wpc_opt["current_city_name"]	    	= get_post_meta($id,'_wpcloudy_current_city_name',true);
	$wpc_opt["today_date_format"]	    	= get_post_meta($id,'_wpcloudy_today_date_format',true);
	if (empty($wpc_opt["today_date_format"])) {
	    $wpc_opt["today_date_format"] = 'none';
	}
	$wpc_opt["current_weather_description"]	= get_post_meta($id,'_wpcloudy_current_weather_description',true);
	$wpc_opt["sunrise_sunset"] 			    = get_post_meta($id,'_wpcloudy_sunrise_sunset',true);
	$wpc_opt["moonrise_moonset"] 	    	= get_post_meta($id,'_wpcloudy_moonrise_moonset',true);
	$wpc_opt["wind"] 				    	= get_post_meta($id,'_wpcloudy_wind',true);
	$wpc_opt["wind_unit"] 				    = get_post_meta($id,'_wpcloudy_wind_unit',true);
	$wpc_opt["humidity"] 				    = get_post_meta($id,'_wpcloudy_humidity',true);
	$wpc_opt["pressure"]				    = get_post_meta($id,'_wpcloudy_pressure',true);
	$wpc_opt["cloudiness"]				    = get_post_meta($id,'_wpcloudy_cloudiness',true);
	$wpc_opt["precipitation"]			    = get_post_meta($id,'_wpcloudy_precipitation',true);
	$wpc_opt["alerts"]    				    = get_post_meta($id,'_wpcloudy_alerts',true);
	$wpc_opt["alerts_button_color"]         = get_post_meta($id,'_wpcloudy_alerts_button_color',true);
	$wpc_opt["hours_forecast_no"]		    = get_post_meta($id,'_wpcloudy_hours_forecast_no',true);
	$wpc_opt["current_temperature"]		    = get_post_meta($id,'_wpcloudy_current_temperature',true);
	$wpc_opt["display_temperature_unit"]	= get_post_meta($id,'_wpcloudy_display_temperature_unit',true);
	$wpc_opt["days_forecast_no"]		    = get_post_meta($id,'_wpcloudy_forecast_no',true);
	$wpc_opt["forecast_precipitations"]     = get_post_meta($id,'_wpcloudy_forecast_precipitations',true);
	$wpc_opt["display_length_days_names"]	= get_post_meta($id,'_wpcloudy_display_length_days_names',true);
    if (empty($wpc_opt["display_length_days_names"])) {
        $wpc_opt["display_length_days_names"] = "short";
    }
 	$wpc_opt["disable_spinner"]   			= get_post_meta($id,'_wpcloudy_disable_spinner',true);
 	$wpc_opt["disable_anims"]   			= get_post_meta($id,'_wpcloudy_disable_anims',true);
	$wpc_opt["background_color"]	   		= get_post_meta($id,'_wpcloudy_background_color',true);
	$wpc_opt["text_color"]		        	= get_post_meta($id,'_wpcloudy_text_color',true);
	$wpc_opt["border_color"]		        = get_post_meta($id,'_wpcloudy_border_color',true);
	$wpc_opt["custom_css"]	    			= get_post_meta($id,'_wpcloudy_custom_css',true);
	$wpc_opt["size"] 			    		= get_post_meta($id,'_wpcloudy_size',true);
	$wpc_opt["owm_link"]			    	= get_post_meta($id,'_wpcloudy_owm_link',true);
	$wpc_opt["last_update"]				    = get_post_meta($id,'_wpcloudy_last_update',true);
	$wpc_opt["font"]      		    		= get_post_meta($id,'_wpcloudy_font',true);
	$wpc_opt["template"]     		    	= get_post_meta($id,'_wpcloudy_template',true);
	$wpc_opt["iconpack"]     			    = get_post_meta($id,'_wpcloudy_iconpack',true);
	$wpc_opt["map"] 	    				= get_post_meta($id,'_wpcloudy_map',true);
	$wpc_opt["map_height"]	    			= get_post_meta($id,'_wpcloudy_map_height',true);
	$wpc_opt["map_opacity"]		    		= get_post_meta($id,'_wpcloudy_map_opacity',true);
   	if ($wpc_opt["map_opacity"] =='') {
   		$wpc_opt["map_opacity"] = "0.5";
   	}
	$wpc_opt["map_zoom"]			    	= get_post_meta($id,'_wpcloudy_map_zoom',true);
   	if ($wpc_opt["map_zoom"] == '') {
   		$wpc_opt["map_zoom"] = "9";
   	}
	$wpc_opt["map_disable_zoom_wheel"]		= get_post_meta($id,'_wpcloudy_map_disable_zoom_wheel',true);
	$wpc_opt["map_stations"]			    = get_post_meta($id,'_wpcloudy_map_stations',true);
	$wpc_opt["map_clouds"]				    = get_post_meta($id,'_wpcloudy_map_clouds',true);
	$wpc_opt["map_precipitation"]		    = get_post_meta($id,'_wpcloudy_map_precipitation',true);
	$wpc_opt["map_snow"]    				= get_post_meta($id,'_wpcloudy_map_snow',true);
	$wpc_opt["map_wind"]	    			= get_post_meta($id,'_wpcloudy_map_wind',true);
	$wpc_opt["map_temperature"]	    		= get_post_meta($id,'_wpcloudy_map_temperature',true);
	$wpc_opt["map_pressure"]		    	= get_post_meta($id,'_wpcloudy_map_pressure',true);

	$wpc_opt["chart_height"]	    		= get_post_meta($id,'_wpcloudy_chart_height',true);
	$wpc_opt["chart_background_color"]		= getColor($id, '_wpcloudy_chart_background_color', '#fff');
	$wpc_opt["chart_temperature_color"]	    = getColor($id,'_wpcloudy_chart_temperature_color', '#d5202a');
	$wpc_opt["chart_feels_like_color"]	    = getColor($id,'_wpcloudy_chart_feels_like_color', '#f83');
	$wpc_opt["chart_dew_point_color"]	    = getColor($id,'_wpcloudy_chart_dew_point_color', '#ac54a0');

	function wpc_get_admin_api_key2() {
		$options = get_option("wpc_option_name");
		if ( ! empty ( $options["wpc_advanced_api_key"] ) ) {
			return $options["wpc_advanced_api_key"];
		} else {
			return '46c433f6ba7dd4d29d5718dac3d7f035';//bugbug
		}
	};

	echo '<div id="wpcloudy-tabs">
			<ul>
				<li><a href="#tabs-1">'. __( 'Basic', 'wp-cloudy' ) .'</a></li>
				<li><a href="#tabs-2">'. __( 'Display', 'wp-cloudy' ) .'</a></li>
				<li><a href="#tabs-3">'. __( 'Layout', 'wp-cloudy' ) .'</a></li>
				<li><a href="#tabs-4">'. __( 'Map', 'wp-cloudy' ) .'</a></li>
				<li><a href="#tabs-5">'. __( 'Chart', 'wp-cloudy' ) .'</a></li>
			</ul>

			<div id="tabs-1">
				<p>
					<label for="wpcloudy_id_owm_meta">'. __( 'OpenWeatherMap city ID', 'wp-cloudy' ) .'<span class="mandatory">*</span> <a href="https://openweathermap.org/find?q=" target="_blank"> '.__('Find my city ID','wp-cloudy').'</a><span class="dashicons dashicons-external"></span></label>
					<input id="wpcloudy_id_owm" type="text" name="wpcloudy_id_owm" value="'.$wpc_opt["id_owm"].'" />
				</p>
				~<strong>'.__('OR','wp-cloudy').'~</strong>
				<p>
					<label for="wpcloudy_latitude_meta">'. __( 'Latitude?', 'wp-cloudy' ) .' <span class="mandatory">*</span></label>
					<input id="wpcloudy_latitude_meta" type="text" name="wpcloudy_latitude" value="'.$wpc_opt["latitude"].'" />
				</p>
				<p>
					<label for="wpcloudy_longitude_meta">'. __( 'Longitude?', 'wp-cloudy' ) .' <span class="mandatory">*</span></label>
					<input id="wpcloudy_longitude_meta" type="text" name="wpcloudy_longitude" value="'.$wpc_opt["longitude"].'" />
				</p>
				<p><em>'.__('If you enter an OpenWeatherMap city ID, it will automatically bypass the  Latitude/Longitude fields.','wp-cloudy').'</em></p>
				~<strong>'.__('OR','wp-cloudy').'~</strong>
				<p>
					<label for="wpcloudy_city_meta">'. __( 'City', 'wp-cloudy' ) .' <span class="mandatory">*</span></label>
					<input id="wpcloudy_city_meta" data_appid="'.wpc_get_admin_api_key2().'" class="cities typeahead" type="text" name="wpcloudy_city" placeholder="'.__('Enter your city','wp-cloudy').'" value="'.$wpc_opt["city"].'" />
				</p>
				<p>
					<label for="wpcloudy_country_meta">'. __( 'Country?', 'wp-cloudy' ) .' <span class="mandatory">*</span></label>
					<input id="wpcloudy_country_meta" class="countries typeahead" type="text" name="wpcloudy_country_code" value="'.$wpc_opt["country_code"].'" />
				</p>
				<p><em>'.__('If you enter an OpenWeatherMap city ID or Latitude/Longitude, it will automatically bypass the City and Country fields.','wp-cloudy').'</em></p>
				<hr>
				<p>
					<label for="wpcloudy_city_name_meta">'. __( 'Custom city title', 'wp-cloudy' ) .'</label>
					<input id="wpcloudy_city_name_meta" type="text" name="wpcloudy_city_name" value="'.$wpc_opt["custom_city_name"].'" />
				</p>
				<p>
					<label for="unit_meta">'. __( 'Measurement system?', 'wp-cloudy' ) .'</label>
					<select name="wpcloudy_unit">
						<option ' . selected( 'imperial', $wpc_opt["temperature_unit"], false ) . ' value="imperial">'. __( 'Imperial', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'metric', $wpc_opt["temperature_unit"], false ) . ' value="metric">'. __( 'Metric', 'wp-cloudy' ) .'</option>
					</select>
				</p>
				<p>
					<label for="wpcloudy_time_format_meta">'. __( '12h / 24h time format?', 'wp-cloudy' ) .'</label>
					<select name="wpcloudy_time_format">
						<option ' . selected( '12', $wpc_opt["time_format"], false ) . ' value="12">'. __( '12 h', 'wp-cloudy' ) .'</option>
						<option ' . selected( '24', $wpc_opt["time_format"], false ) . ' value="24">'. __( '24 h', 'wp-cloudy' ) .'</option>
					</select>
				</p>
				<p>
					<label for="wpcloudy_custom_timezone_meta">'. __( 'Custom timezone? (default: WordPress general settings)', 'wp-cloudy' ) .'</label>
					<select name="wpcloudy_custom_timezone" id="wpcloudy_custom_timezone_meta">
						<option ' . selected( 'Default', $wpc_opt["custom_timezone"], false ) . ' value="Default">'. __( 'WordPress timezone', 'wp-cloudy' ) .'</option>
						<option ' . selected( '-12', $wpc_opt["custom_timezone"], false ) . ' value="-12">'. __( 'UTC -12', 'wp-cloudy' ) .'</option>
						<option ' . selected( '-11', $wpc_opt["custom_timezone"], false ) . ' value="-11">'. __( 'UTC -11', 'wp-cloudy' ) .'</option>
						<option ' . selected( '-10', $wpc_opt["custom_timezone"], false ) . ' value="-10">'. __( 'UTC -10', 'wp-cloudy' ) .'</option>
						<option ' . selected( '-9', $wpc_opt["custom_timezone"], false ) . ' value="-9">'. __( 'UTC -9', 'wp-cloudy' ) .'</option>
						<option ' . selected( '-8', $wpc_opt["custom_timezone"], false ) . ' value="-8">'. __( 'UTC -8', 'wp-cloudy' ) .'</option>
						<option ' . selected( '-7', $wpc_opt["custom_timezone"], false ) . ' value="-7">'. __( 'UTC -7', 'wp-cloudy' ) .'</option>
						<option ' . selected( '-6', $wpc_opt["custom_timezone"], false ) . ' value="-6">'. __( 'UTC -6', 'wp-cloudy' ) .'</option>
						<option ' . selected( '-5', $wpc_opt["custom_timezone"], false ) . ' value="-5">'. __( 'UTC -5', 'wp-cloudy' ) .'</option>
						<option ' . selected( '-4', $wpc_opt["custom_timezone"], false ) . ' value="-4">'. __( 'UTC -4', 'wp-cloudy' ) .'</option>
						<option ' . selected( '-3', $wpc_opt["custom_timezone"], false ) . ' value="-3">'. __( 'UTC -3', 'wp-cloudy' ) .'</option>
						<option ' . selected( '-2', $wpc_opt["custom_timezone"], false ) . ' value="-2">'. __( 'UTC -2', 'wp-cloudy' ) .'</option>
						<option ' . selected( '-1', $wpc_opt["custom_timezone"], false ) . ' value="-1">'. __( 'UTC -1', 'wp-cloudy' ) .'</option>
						<option ' . selected( '0', $wpc_opt["custom_timezone"], false ) . ' value="0">'. __( 'UTC 0', 'wp-cloudy' ) .'</option>
						<option ' . selected( '1', $wpc_opt["custom_timezone"], false ) . ' value="1">'. __( 'UTC +1', 'wp-cloudy' ) .'</option>
						<option ' . selected( '2', $wpc_opt["custom_timezone"], false ) . ' value="2">'. __( 'UTC +2', 'wp-cloudy' ) .'</option>
						<option ' . selected( '3', $wpc_opt["custom_timezone"], false ) . ' value="3">'. __( 'UTC +3', 'wp-cloudy' ) .'</option>
						<option ' . selected( '4', $wpc_opt["custom_timezone"], false ) . ' value="4">'. __( 'UTC +4', 'wp-cloudy' ) .'</option>
						<option ' . selected( '5', $wpc_opt["custom_timezone"], false ) . ' value="5">'. __( 'UTC +5', 'wp-cloudy' ) .'</option>
						<option ' . selected( '6', $wpc_opt["custom_timezone"], false ) . ' value="6">'. __( 'UTC +6', 'wp-cloudy' ) .'</option>
						<option ' . selected( '7', $wpc_opt["custom_timezone"], false ) . ' value="7">'. __( 'UTC +7', 'wp-cloudy' ) .'</option>
						<option ' . selected( '8', $wpc_opt["custom_timezone"], false ) . ' value="8">'. __( 'UTC +8', 'wp-cloudy' ) .'</option>
						<option ' . selected( '9', $wpc_opt["custom_timezone"], false ) . ' value="9">'. __( 'UTC +9', 'wp-cloudy' ) .'</option>
						<option ' . selected( '10', $wpc_opt["custom_timezone"], false ) . ' value="10">'. __( 'UTC +10', 'wp-cloudy' ) .'</option>
						<option ' . selected( '11', $wpc_opt["custom_timezone"], false ) . ' value="11">'. __( 'UTC +11', 'wp-cloudy' ) .'</option>
						<option ' . selected( '12', $wpc_opt["custom_timezone"], false ) . ' value="12">'. __( 'UTC +12', 'wp-cloudy' ) .'</option>
					</select>
				</p>
				<p>
					<label for="wpcloudy_owm_language_meta">'. __( 'OpenWeatherMap language?', 'wp-cloudy' ) .'</label>
					<select name="wpcloudy_owm_language" id="wpcloudy_owm_language_meta">
						<option ' . selected( 'Default', $wpc_opt["owm_language"], false ) . ' value="Default">'. __( 'Default', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'af', $wpc_opt["owm_language"], false ) . ' value="af">'. __( 'Afrikaans', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'al', $wpc_opt["owm_language"], false ) . ' value="al">'. __( 'Albanian', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'ar', $wpc_opt["owm_language"], false ) . ' value="ar">'. __( 'Arabic', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'az', $wpc_opt["owm_language"], false ) . ' value="az">'. __( 'Azerbaijani', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'eu', $wpc_opt["owm_language"], false ) . ' value="eu">'. __( 'Basque', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'bg', $wpc_opt["owm_language"], false ) . ' value="bg">'. __( 'Bulgarian', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'ca', $wpc_opt["owm_language"], false ) . ' value="ca">'. __( 'Catalan', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'zh_cn', $wpc_opt["owm_language"], false ) . ' value="zh_cn">'. __( 'Chinese Simplified', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'zh_tw', $wpc_opt["owm_language"], false ) . ' value="zh_tw">'. __( 'Chinese Traditional', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'hr', $wpc_opt["owm_language"], false ) . ' value="hr">'. __( 'Croatian', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'cz', $wpc_opt["owm_language"], false ) . ' value="cz">'. __( 'Czech', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'da', $wpc_opt["owm_language"], false ) . ' value="da">'. __( 'Danish', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'nl', $wpc_opt["owm_language"], false ) . ' value="nl">'. __( 'Dutch', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'en', $wpc_opt["owm_language"], false ) . ' value="en">'. __( 'English', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'fi', $wpc_opt["owm_language"], false ) . ' value="fi">'. __( 'Finnish', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'fr', $wpc_opt["owm_language"], false ) . ' value="fr">'. __( 'French', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'gl', $wpc_opt["owm_language"], false ) . ' value="gl">'. __( 'Galician', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'de', $wpc_opt["owm_language"], false ) . ' value="de">'. __( 'German', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'el', $wpc_opt["owm_language"], false ) . ' value="el">'. __( 'Greek', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'he', $wpc_opt["owm_language"], false ) . ' value="he">'. __( 'Hebrew', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'hi', $wpc_opt["owm_language"], false ) . ' value="hi">'. __( 'Hindi', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'hu', $wpc_opt["owm_language"], false ) . ' value="hu">'. __( 'Hungarian', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'id', $wpc_opt["owm_language"], false ) . ' value="id">'. __( 'Indonesian', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'it', $wpc_opt["owm_language"], false ) . ' value="it">'. __( 'Italian', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'ja', $wpc_opt["owm_language"], false ) . ' value="ja">'. __( 'Japanese', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'kr', $wpc_opt["owm_language"], false ) . ' value="kr">'. __( 'Korean', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'la', $wpc_opt["owm_language"], false ) . ' value="la">'. __( 'Latvian', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'lt', $wpc_opt["owm_language"], false ) . ' value="lt">'. __( 'Lithuanian', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'mk', $wpc_opt["owm_language"], false ) . ' value="mk">'. __( 'Macedonian', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'no', $wpc_opt["owm_language"], false ) . ' value="no">'. __( 'Norwegian', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'fa', $wpc_opt["owm_language"], false ) . ' value="fa">'. __( 'Persian (Farsi)', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'pl', $wpc_opt["owm_language"], false ) . ' value="pl">'. __( 'Polish', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'pt', $wpc_opt["owm_language"], false ) . ' value="pt">'. __( 'Portuguese', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'pt', $wpc_opt["owm_language"], false ) . ' value="pt">'. __( 'Português Brasil', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'ro', $wpc_opt["owm_language"], false ) . ' value="ro">'. __( 'Romanian', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'ru', $wpc_opt["owm_language"], false ) . ' value="ru">'. __( 'Russian', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'sr', $wpc_opt["owm_language"], false ) . ' value="sr">'. __( 'Serbian', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'sv', $wpc_opt["owm_language"], false ) . ' value="sv">'. __( 'Swedish', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'sk', $wpc_opt["owm_language"], false ) . ' value="sk">'. __( 'Slovak', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'sl', $wpc_opt["owm_language"], false ) . ' value="sl">'. __( 'Slovenian', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'sp', $wpc_opt["owm_language"], false ) . ' value="sp">'. __( 'Spanish', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'th', $wpc_opt["owm_language"], false ) . ' value="th">'. __( 'Thai', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'tr', $wpc_opt["owm_language"], false ) . ' value="tr">'. __( 'Turkish', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'ua', $wpc_opt["owm_language"], false ) . ' value="ua">'. __( 'Ukrainian', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'vi', $wpc_opt["owm_language"], false ) . ' value="vi">'. __( 'Vietnamese', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'zu', $wpc_opt["owm_language"], false ) . ' value="zu">'. __( 'Zulu', 'wp-cloudy' ) .'</option>
					</select>
				</p>
				<p class="misc">
					'. __( 'Misc', 'wp-cloudy' ) .'
				</p>
				<p>
					<label for="wpcloudy_gtag_meta">
						<input type="checkbox" name="wpcloudy_gtag" id="wpcloudy_gtag_meta" value="yes" '. checked( $wpc_opt["gtag"], 'yes', false ) .' />
							'. __( 'Google Tag Manager dataLayer?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_bypass_exclude_meta">
						<input type="checkbox" name="wpcloudy_bypass_exclude" id="wpcloudy_bypass_exclude_meta" value="yes" '. checked( $wpc_opt["bypass_exclude"], 'yes', false ) .' />
							'. __( 'Exclude from System Settings Bypass?', 'wp-cloudy' ) .'
					</label>
				</p>
			</div>
			<div id="tabs-2">
				<p class="wpc-dates">
					'. __( 'Current weather', 'wp-cloudy' ) .'
				</p>
				<p>
					<label for="wpcloudy_current_city_name_meta">
						<input type="checkbox" name="wpcloudy_current_city_name" id="wpcloudy_current_city_name_meta" value="yes" '. checked( $wpc_opt["current_city_name"], 'yes', false ) .' />
							'. __( 'Current weather city name?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_current_weather_symbol_meta">
						<input type="checkbox" name="wpcloudy_current_weather_symbol" id="wpcloudy_current_weather_symbol_meta" value="yes" '. checked( $wpc_opt["current_weather_symbol"], 'yes', false ) .' />
							'. __( 'Current weather symbol?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_current_temperature_meta">
						<input type="checkbox" name="wpcloudy_current_temperature" id="wpcloudy_current_temperature_meta" value="yes" '. checked( $wpc_opt["current_temperature"], 'yes', false ) .' />
							'. __( 'Current temperature?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_current_weather_description_meta">
						<input type="checkbox" name="wpcloudy_current_weather_description" id="wpcloudy_current_weather_description_meta" value="yes" '. checked( $wpc_opt["current_weather_description"], 'yes', false ) .' />
							'. __( 'Current weather short condition?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p class="temperatures">
					'. __( 'Temperatures', 'wp-cloudy' ) .'
				</p>
				<p>
					<label for="wpcloudy_display_temperature_unit_meta">
						<input type="checkbox" name="wpcloudy_display_temperature_unit" id="wpcloudy_display_temperature_unit_meta" value="yes" '. checked( $wpc_opt["display_temperature_unit"], 'yes', false ) .' />
							'. __( 'Temperatures unit (C / F)?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p class="wpc-dates">
					'. __( 'Date, Sunrise/Sunset and Moonrise/Moonset', 'wp-cloudy' ) .'
				</p>
				<p>
					<label for="wpcloudy_today_date_format_none_meta">
						<input type="radio" name="wpcloudy_today_date_format" id="wpcloudy_today_date_format_none_meta" value="none" '. checked( $wpc_opt["today_date_format"], 'none', false ) .' />
							'. __( 'No date (default)?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_today_date_format_week_meta">
						<input type="radio" name="wpcloudy_today_date_format" id="wpcloudy_today_date_format_week_meta" value="day" '. checked( $wpc_opt["today_date_format"], 'day', false ) .' />
							'. __( 'Day of the week (eg: Sunday)?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_today_date_format_calendar_meta">
						<input type="radio" name="wpcloudy_today_date_format" id="wpcloudy_today_date_format_calendar_meta" value="date" '. checked( $wpc_opt["today_date_format"], 'date', false ) .' />
							'. __( 'Today\'s date (based on your WordPress General Settings)?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_sunrise_sunset_meta">
						<input type="checkbox" name="wpcloudy_sunrise_sunset" id="wpcloudy_sunrise_sunset_meta" value="yes" '. checked( $wpc_opt["sunrise_sunset"], 'yes', false ) .' />
							'. __( 'Sunrise + sunset?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_moonrise_moonset_meta">
						<input type="checkbox" name="wpcloudy_moonrise_moonset" id="wpcloudy_moonrise_moonset_meta" value="yes" '. checked( $wpc_opt["moonrise_moonset"], 'yes', false ) .' />
							'. __( 'Moonrise + moonset?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p class="wpc-misc">
					'. __( 'Misc', 'wp-cloudy' ) .'
				</p>
				<p>
					<label for="wpcloudy_wind_meta">
						<input type="checkbox" name="wpcloudy_wind" id="wpcloudy_wind_meta" value="yes" '. checked( $wpc_opt["wind"], 'yes', false ) .' />
							'. __( 'Wind?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_wind_unit_meta">'. __( 'Wind unit: ', 'wp-cloudy' ) .'</label>
					<select name="wpcloudy_wind_unit">
						<option ' . selected( '1', $wpc_opt["wind_unit"], false ) . ' value="1">'. __( 'mi/h', 'wp-cloudy' ) .'</option>
						<option ' . selected( '2', $wpc_opt["wind_unit"], false ) . ' value="2">'. __( 'm/s', 'wp-cloudy' ) .'</option>
						<option ' . selected( '3', $wpc_opt["wind_unit"], false ) . ' value="3">'. __( 'km/h', 'wp-cloudy' ) .'</option>
						<option ' . selected( '4', $wpc_opt["wind_unit"], false ) . ' value="4">'. __( 'kt', 'wp-cloudy' ) .'</option>
					</select>
				</p>
				<p>
					<label for="wpcloudy_humidity_meta">
						<input type="checkbox" name="wpcloudy_humidity" id="wpcloudy_humidity_meta" value="yes" '. checked( $wpc_opt["humidity"], 'yes', false ) .' />
							'. __( 'Humidity?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_pressure_meta">
						<input type="checkbox" name="wpcloudy_pressure" id="wpcloudy_pressure_meta" value="yes" '. checked( $wpc_opt["pressure"], 'yes', false ) .' />
							'. __( 'Pressure?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_cloudiness_meta">
						<input type="checkbox" name="wpcloudy_cloudiness" id="wpcloudy_cloudiness_meta" value="yes" '. checked( $wpc_opt["cloudiness"], 'yes', false ) .' />
							'. __( 'Cloudiness?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_precipitation_meta">
						<input type="checkbox" name="wpcloudy_precipitation" id="wpcloudy_precipitation_meta" value="yes" '. checked( $wpc_opt["precipitation"], 'yes', false ) .' />
							'. __( 'Precipitation?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_alerts_meta">
						<input type="checkbox" name="wpcloudy_alerts" id="wpcloudy_alerts_meta" value="yes" '. checked( $wpc_opt["alerts"], 'yes', false ) .' />
							'. __( 'Alerts?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_alerts_button_color">'. __( 'Alert Button color', 'wp-cloudy' ) .'</label>
					<input name="wpcloudy_alerts_button_color" type="text" value="'. $wpc_opt["alerts_button_color"] .'" class="wpcloudy_color_picker" />
				</p>
				<p class="hour">
					'. __( 'Hourly Forecast', 'wp-cloudy' ) .'
				</p>
				<p>
					<label for="wpcloudy_hours_forecast_no_meta">'. __( 'How many hours?', 'wp-cloudy' ) .'</label>
					<select name="wpcloudy_hours_forecast_no">' . generate_hour_options($wpc_opt["hours_forecast_no"]) . '</select>
					<br />
					<span class="dashicons dashicons-editor-help"></span><a href="'.admin_url('options-general.php').'" target="_blank">'.__('Make sure you have properly set the date of your site in WordPress settings.','wp-cloudy').'</a> or set a Custom timezone under Basic.
				</p>
				<p class="forecast">
					'. __( 'Daily Forecast', 'wp-cloudy' ) .'
				</p>
				<p>
					<label for="wpcloudy_forecast_no_meta">'. __( 'How many days?', 'wp-cloudy' ) .'</label>
					<select name="wpcloudy_forecast_no">
						<option ' . selected( '0', $wpc_opt["days_forecast_no"], false ) . ' value="0">'. __( 'None', 'wp-cloudy' ) .'</option>
						<option ' . selected( '1', $wpc_opt["days_forecast_no"], false ) . ' value="1">'. __( 'Today', 'wp-cloudy' ) .'</option>
						<option ' . selected( '2', $wpc_opt["days_forecast_no"], false ) . ' value="2">'. __( 'Today + 1 day', 'wp-cloudy' ) .'</option>
						<option ' . selected( '3', $wpc_opt["days_forecast_no"], false ) . ' value="3">'. __( 'Today + 2 days', 'wp-cloudy' ) .'</option>
						<option ' . selected( '4', $wpc_opt["days_forecast_no"], false ) . ' value="4">'. __( 'Today + 3 days', 'wp-cloudy' ) .'</option>
						<option ' . selected( '5', $wpc_opt["days_forecast_no"], false ) . ' value="5">'. __( 'Today + 4 days', 'wp-cloudy' ) .'</option>
						<option ' . selected( '6', $wpc_opt["days_forecast_no"], false ) . ' value="6">'. __( 'Today + 5 days', 'wp-cloudy' ) .'</option>
						<option ' . selected( '7', $wpc_opt["days_forecast_no"], false ) . ' value="7">'. __( 'Today + 6 days', 'wp-cloudy' ) .'</option>
						<option ' . selected( '8', $wpc_opt["days_forecast_no"], false ) . ' value="8">'. __( 'Today + 7 days', 'wp-cloudy' ) .'</option>
					</select>
				</p>
				<p>
					<label for="wpcloudy_forecast_precipitations_meta">
						<input type="checkbox" name="wpcloudy_forecast_precipitations" id="wpcloudy_forecast_precipitations_meta" value="yes" '. checked( $wpc_opt["forecast_precipitations"], 'yes', false ) .' />
							'. __( 'Forecast Precipitations?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_display_length_days_names_short_meta">
						<input type="radio" name="wpcloudy_display_length_days_names" id="wpcloudy_display_length_days_names_short_meta" value="short" '. checked( $wpc_opt["display_length_days_names"], 'short', false ) .' />
							'. __( 'Short days names?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_display_length_days_names_normal_meta">
						<input type="radio" name="wpcloudy_display_length_days_names" id="wpcloudy_display_length_days_names_normal_meta" value="normal" '. checked( $wpc_opt["display_length_days_names"], 'normal', false ) .' />
							'. __( 'Normal days names?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p class="footer">
					'. __( 'Footer', 'wp-cloudy' ) .'
				</p>
				<p>
					<label for="wpcloudy_owm_link_meta">
						<input type="checkbox" name="wpcloudy_owm_link" id="wpcloudy_owm_link_meta" value="yes" '. checked( $wpc_opt["owm_link"], 'yes', false ) .' />
						'. __( 'Link to OpenWeatherMap?', 'wp-cloudy' ) .'
					</label>
				</p>

				<p>
					<label for="wpcloudy_last_update_meta">
						<input type="checkbox" name="wpcloudy_last_update" id="wpcloudy_last_update_meta" value="yes" '. checked( $wpc_opt["last_update"], 'yes', false ) .' />

						'. __( 'Update date?', 'wp-cloudy' ) .'
					</label>
				</p>
			</div>
			<div id="tabs-3">
				<p>
					<label for="template_meta">'. __( 'Template', 'wp-cloudy' ) .'</label>
					<select name="wpcloudy_template">
						<option ' . selected( 'Default', $wpc_opt["template"], false ) . ' value="Default">'. __( 'Default', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'theme1', $wpc_opt["template"], false ) . ' value="theme1">'. __( 'Theme 1 (with slider)', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'theme2', $wpc_opt["template"], false ) . ' value="theme2">'. __( 'Theme 2 (with slider)', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'chart1', $wpc_opt["template"], false ) . ' value="chart1" disabled>'. __( 'Chart 1', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'chart2', $wpc_opt["template"], false ) . ' value="chart2" disabled>'. __( 'Chart 2', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'custom1', $wpc_opt["template"], false ) . ' value="custom1">'. __( 'Custom 1', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'custom2', $wpc_opt["template"], false ) . ' value="custom2">'. __( 'Custom 2', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'debug', $wpc_opt["template"], false ) . ' value="debug">'. __( 'Debug', 'wp-cloudy' ) .'</option>
					</select>
				</p>
				<p>
					<label for="font_meta">'. __( 'Font', 'wp-cloudy' ) .'</label>
					<select name="wpcloudy_font">
						<option ' . selected( 'Default', $wpc_opt["font"], false ) . ' value="Default">'. __( 'Default', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'Arvo', $wpc_opt["font"], false ) . ' value="Arvo">'. __( 'Arvo', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'Asap', $wpc_opt["font"], false ) . ' value="Asap">'. __( 'Asap', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'Bitter', $wpc_opt["font"], false ) . ' value="Bitter">'. __( 'Bitter', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'Droid Serif', $wpc_opt["font"], false ) . ' value="Droid Serif">'. __( 'Droid Serif', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'Exo 2', $wpc_opt["font"], false ) . ' value="Exo 2">'. __( 'Exo 2', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'Francois One', $wpc_opt["font"], false ) . ' value="Francois One">'. __( 'Francois One', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'Inconsolata', $wpc_opt["font"], false ) . ' value="Inconsolata">'. __( 'Inconsolata', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'Josefin Sans', $wpc_opt["font"], false ) . ' value="Josefin Sans">'. __( 'Josefin Sans', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'Lato', $wpc_opt["font"], false ) . ' value="Lato">'. __( 'Lato', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'Merriweather Sans', $wpc_opt["font"], false ) . ' value="Merriweather Sans">'. __( 'Merriweather Sans', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'Nunito', $wpc_opt["font"], false ) . ' value="Nunito">'. __( 'Nunito', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'Open Sans', $wpc_opt["font"], false ) . ' value="Open Sans">'. __( 'Open Sans', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'Oswald', $wpc_opt["font"], false ) . ' value="Oswald">'. __( 'Oswald', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'Pacifico', $wpc_opt["font"], false ) . ' value="Pacifico">'. __( 'Pacifico', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'Roboto', $wpc_opt["font"], false ) . ' value="Roboto">'. __( 'Roboto', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'Signika', $wpc_opt["font"], false ) . ' value="Signika">'. __( 'Signika', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'Source Sans Pro', $wpc_opt["font"], false ) . ' value="Source Sans Pro">'. __( 'Source Sans Pro', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'Tangerine', $wpc_opt["font"], false ) . ' value="Tangerine">'. __( 'Tangerine', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'Ubuntu', $wpc_opt["font"], false ) . ' value="Ubuntu">'. __( 'Ubuntu', 'wp-cloudy' ) .'</option>
					</select>
				</p>
				<p>
					<label for="iconpack_meta">'. __( 'Icon Pack', 'wp-cloudy' ) .'</label>
					<select name="wpcloudy_iconpack">
						<option ' . selected( 'Default', $wpc_opt["iconpack"], false ) . ' value="Default">'. __( 'Climacons', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'WeatherIcons', $wpc_opt["iconpack"], false ) . ' value="WeatherIcons">'. __( 'Weather Icons', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'IconVault', $wpc_opt["iconpack"], false ) . ' value="IconVault">'. __( 'Forecast', 'wp-cloudy' ) .'</option>
						<option disabled ' . selected( 'Dripicons', $wpc_opt["iconpack"], false ) . ' value="Dripicons">'. __( 'Dripicons', 'wp-cloudy' ) .'</option>
						<option disabled ' . selected( 'Pixeden', $wpc_opt["iconpack"], false ) . ' value="Pixeden">'. __( 'Pixeden', 'wp-cloudy' ) .'</option>
					</select>
				</p>
				<p>
					<label for="wpcloudy_disable_spinner_meta">
						<input type="checkbox" name="wpcloudy_disable_spinner" id="wpcloudy_disable_spinner_meta" value="yes" '. checked( $wpc_opt["disable_spinner"], 'yes', false ) .' />
							'. __( 'Disable loading spinner?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_disable_anims_meta">
						<input type="checkbox" name="wpcloudy_disable_anims" id="wpcloudy_disable_anims_meta" value="yes" '. checked( $wpc_opt["disable_anims"], 'yes', false ) .' />
							'. __( 'Disable CSS3 animations?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_background_color">'. __( 'Background color', 'wp-cloudy' ) .'</label>
					<input name="wpcloudy_background_color" type="text" value="'. $wpc_opt["background_color"] .'" class="wpcloudy_color_picker" />
				</p>
				<p>
					<label for="wpcloudy_text_color">'. __( 'Text color', 'wp-cloudy' ) .'</label>
					<input name="wpcloudy_text_color" type="text" value="'. $wpc_opt["text_color"] .'" class="wpcloudy_color_picker" />
				</p>
				<p>
					<label for="wpcloudy_border_color">'. __( 'Border color', 'wp-cloudy' ) .'</label>
					<input name="wpcloudy_border_color" type="text" value="'. $wpc_opt["border_color"] .'" class="wpcloudy_color_picker" />
				</p>
				<p>
					<label for="size_meta">'. __( 'Weather size?', 'wp-cloudy' ) .'</label>
					<select name="wpcloudy_size">
						<option ' . selected( 'small', $wpc_opt["size"], false ) . ' value="small">'. __( 'Small', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'medium', $wpc_opt["size"], false ) . ' value="medium">'. __( 'Medium', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'large', $wpc_opt["size"], false ) . ' value="large">'. __( 'Large', 'wp-cloudy' ) .'</option>
					</select>
				</p>
				<p>
					<label for="wpcloudy_custom_css_meta">'. __( 'Custom CSS', 'wp-cloudy' ) .'</label>
					<textarea id="wpcloudy_custom_css_meta" name="wpcloudy_custom_css">'.$wpc_opt["custom_css"].'</textarea>
				    <p>Preceed all CSS rules with #wpc-weather-container-' . $id . ' if you are planning to use more than one weather shortcode on a page.</p>
				</p>
			</div>
			<div id="tabs-4">
				<p>
					<label for="wpcloudy_map_meta">
						<input type="checkbox" name="wpcloudy_map" id="wpcloudy_map_meta" value="yes" '. checked( $wpc_opt["map"], 'yes', false ) .' />
							'. __( 'Display map?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_map_height_meta">'. __( 'Map height (in px)', 'wp-cloudy' ) .'</label>
					<input id="wpcloudy_map_height_meta" type="text" name="wpcloudy_map_height" value="'.$wpc_opt["map_height"].'" />
				</p>
				<p>
					<label for="wpcloudy_map_opacity_meta">'. __( 'Layers opacity', 'wp-cloudy' ) .'</label>
					<select name="wpcloudy_map_opacity">
						<option ' . selected( '0', $wpc_opt["map_opacity"], false ) . ' value="0">0%</option>
						<option ' . selected( '0.1', $wpc_opt["map_opacity"], false ) . ' value="0.1">10%</option>
						<option ' . selected( '0.2', $wpc_opt["map_opacity"], false ) . ' value="0.2">20%</option>
						<option ' . selected( '0.3', $wpc_opt["map_opacity"], false ) . ' value="0.3">30%</option>
						<option ' . selected( '0.4', $wpc_opt["map_opacity"], false ) . ' value="0.4">40%</option>
						<option ' . selected( '0.5', $wpc_opt["map_opacity"], false ) . ' value="0.5">50%</option>
						<option ' . selected( '0.6', $wpc_opt["map_opacity"], false ) . ' value="0.6">60%</option>
						<option ' . selected( '0.7', $wpc_opt["map_opacity"], false ) . ' value="0.7">70%</option>
						<option ' . selected( '0.8', $wpc_opt["map_opacity"], false ) . ' value="0.8">80%</option>
						<option ' . selected( '0.9', $wpc_opt["map_opacity"], false ) . ' value="0.9">90%</option>
						<option ' . selected( '1', $wpc_opt["map_opacity"], false ) . ' value="1">100%</option>
					</select>
				</p>
				<p>
					<label for="wpcloudy_map_zoom_meta">'. __( 'Zoom', 'wp-cloudy' ) .'</label>
					<select name="wpcloudy_map_zoom">
						<option ' . selected( '1', $wpc_opt["map_zoom"], false ) . ' value="1">1</option>
						<option ' . selected( '2', $wpc_opt["map_zoom"], false ) . ' value="2">2</option>
						<option ' . selected( '3', $wpc_opt["map_zoom"], false ) . ' value="3">3</option>
						<option ' . selected( '4', $wpc_opt["map_zoom"], false ) . ' value="4">4</option>
						<option ' . selected( '5', $wpc_opt["map_zoom"], false ) . ' value="5">5</option>
						<option ' . selected( '6', $wpc_opt["map_zoom"], false ) . ' value="6">6</option>
						<option ' . selected( '7', $wpc_opt["map_zoom"], false ) . ' value="7">7</option>
						<option ' . selected( '8', $wpc_opt["map_zoom"], false ) . ' value="8">8</option>
						<option ' . selected( '9', $wpc_opt["map_zoom"], false ) . ' value="9">9</option>
						<option ' . selected( '10', $wpc_opt["map_zoom"], false ) . ' value="10">10</option>
						<option ' . selected( '11', $wpc_opt["map_zoom"], false ) . ' value="11">11</option>
						<option ' . selected( '12', $wpc_opt["map_zoom"], false ) . ' value="12">12</option>
						<option ' . selected( '13', $wpc_opt["map_zoom"], false ) . ' value="13">13</option>
						<option ' . selected( '14', $wpc_opt["map_zoom"], false ) . ' value="14">14</option>
						<option ' . selected( '15', $wpc_opt["map_zoom"], false ) . ' value="15">15</option>
						<option ' . selected( '16', $wpc_opt["map_zoom"], false ) . ' value="16">16</option>
						<option ' . selected( '17', $wpc_opt["map_zoom"], false ) . ' value="17">17</option>
						<option ' . selected( '18', $wpc_opt["map_zoom"], false ) . ' value="18">18</option>
					</select>
				</p>
				<p>
					<label for="wpcloudy_map_disable_zoom_wheel_meta">
						<input type="checkbox" name="wpcloudy_map_disable_zoom_wheel" id="wpcloudy_map_disable_zoom_wheel_meta" value="yes" '. checked( $wpc_opt["map_disable_zoom_wheel"], 'yes', false ) .' />
							'. __( 'Disable zoom wheel on map?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p class="subsection-title">
					'. __( 'Layers', 'wp-cloudy' ) .'
				</p>
				<p>
					<label for="wpcloudy_map_stations_meta">
						<input type="checkbox" name="wpcloudy_map_stations" id="wpcloudy_map_stations_meta" value="yes" '. checked( $wpc_opt["map_stations"], 'yes', false ) .' />
							'. __( 'Display stations?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_map_clouds_meta">
						<input type="checkbox" name="wpcloudy_map_clouds" id="wpcloudy_map_clouds_meta" value="yes" '. checked( $wpc_opt["map_clouds"], 'yes', false ) .' />
							'. __( 'Display clouds?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_map_precipitation_meta">
						<input type="checkbox" name="wpcloudy_map_precipitation" id="wpcloudy_map_precipitation_meta" value="yes" '. checked( $wpc_opt["map_precipitation"], 'yes', false ) .' />
							'. __( 'Display precipitation?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_map_snow_meta">
						<input type="checkbox" name="wpcloudy_map_snow" id="wpcloudy_map_snow_meta" value="yes" '. checked( $wpc_opt["map_snow"], 'yes', false ) .' />
							'. __( 'Display snow?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_map_wind_meta">
						<input type="checkbox" name="wpcloudy_map_wind" id="wpcloudy_map_wind_meta" value="yes" '. checked( $wpc_opt["map_wind"], 'yes', false ) .' />
							'. __( 'Display wind?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_map_temperature_meta">
						<input type="checkbox" name="wpcloudy_map_temperature" id="wpcloudy_map_temperature_meta" value="yes" '. checked( $wpc_opt["map_temperature"], 'yes', false ) .' />
							'. __( 'Display temperature?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_map_pressure_meta">
						<input type="checkbox" name="wpcloudy_map_pressure" id="wpcloudy_map_pressure_meta" value="yes" '. checked( $wpc_opt["map_pressure"], 'yes', false ) .' />
							'. __( 'Display pressure?', 'wp-cloudy' ) .'
					</label>
				</p>
			</div>
			<div id="tabs-5">
				<p>
					<label for="wpcloudy_chart_height_meta">'. __( 'Chart height (in px)', 'wp-cloudy' ) .'</label>
					<input id="wpcloudy_charet_height_meta" type="text" name="wpcloudy_chart_height" value="'.$wpc_opt["chart_height"].'" />
				</p>
				<p>
					<label for="wpcloudy_chart_background_color">'. __( 'Background color', 'wp-cloudy' ) .'</label>
					<input name="wpcloudy_chart_background_color" type="text" value="'. $wpc_opt["chart_background_color"] .'" class="wpcloudy_color_picker" />
				</p>
				<p>
					<label for="wpcloudy_chart_temperature_color">'. __( 'Temperature color', 'wp-cloudy' ) .'</label>
					<input name="wpcloudy_chart_temperature_color" type="text" value="'. $wpc_opt["chart_temperature_color"] .'" class="wpcloudy_color_picker" />
				</p>
				<p>
					<label for="wpcloudy_chart_feels_like_color">'. __( 'Feels like color', 'wp-cloudy' ) .'</label>
					<input name="wpcloudy_chart_feels_like_color" type="text" value="'. $wpc_opt["chart_feels_like_color"] .'" class="wpcloudy_color_picker" />
				</p>
				<p>
					<label for="wpcloudy_chart_dew_point_color">'. __( 'Dew point color', 'wp-cloudy' ) .'</label>
					<input name="wpcloudy_chart_dew_point_color" type="text" value="'. $wpc_opt["chart_dew_point_color"] .'" class="wpcloudy_color_picker" />
				</p>
			</div>
	</div>
  ';
}


add_action('save_post','wpc_save_metabox');
function wpc_save_metabox($post_id){
	if ( 'wpc-weather' === get_post_type($post_id)) {
        update_post_meta($post_id, '_wpcloudy_version', WPCLOUDY_VERSION);

		if(isset($_POST['wpcloudy_city'])){
		  update_post_meta($post_id, '_wpcloudy_city', esc_html($_POST['wpcloudy_city']));
		}
		if(isset($_POST['wpcloudy_city_name'])){
		  update_post_meta($post_id, '_wpcloudy_city_name', esc_html($_POST['wpcloudy_city_name']));
		}
		if(isset($_POST['wpcloudy_id_owm'])){
		  update_post_meta($post_id, '_wpcloudy_id_owm', esc_html($_POST['wpcloudy_id_owm']));
		}
		if(isset($_POST['wpcloudy_longitude'])){
		  update_post_meta($post_id, '_wpcloudy_longitude', esc_html($_POST['wpcloudy_longitude']));
		}
		if(isset($_POST['wpcloudy_latitude'])){
		  update_post_meta($post_id, '_wpcloudy_latitude', esc_html($_POST['wpcloudy_latitude']));
		}
		if(isset($_POST['wpcloudy_country_code'])){
		  update_post_meta($post_id, '_wpcloudy_country_code', esc_html($_POST['wpcloudy_country_code']));
		}
		if(isset($_POST['wpcloudy_unit'])) {
		  update_post_meta($post_id, '_wpcloudy_unit', $_POST['wpcloudy_unit']);
		}
		if(isset($_POST['wpcloudy_time_format'])) {
		  update_post_meta($post_id, '_wpcloudy_time_format', $_POST['wpcloudy_time_format']);
		}
		if(isset($_POST['wpcloudy_custom_timezone'])) {
		  update_post_meta($post_id, '_wpcloudy_custom_timezone', $_POST['wpcloudy_custom_timezone']);
		}
		if( isset( $_POST[ 'wpcloudy_current_city_name' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_current_city_name', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_current_city_name' );
		}
		if( isset( $_POST[ 'wpcloudy_current_weather_symbol' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_current_weather_symbol', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_current_weather_symbol' );
		}
		if( isset( $_POST[ 'wpcloudy_current_weather_description' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_current_weather_description', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_current_weather_description' );
		}
		if( isset( $_POST[ 'wpcloudy_today_date_format' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_today_date_format', $_POST[ 'wpcloudy_today_date_format' ] );
		}
		if( isset( $_POST[ 'wpcloudy_display_temperature_unit' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_display_temperature_unit', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_display_temperature_unit' );
		}
		if( isset( $_POST[ 'wpcloudy_sunrise_sunset' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_sunrise_sunset', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_sunrise_sunset' );
		}
		if( isset( $_POST[ 'wpcloudy_moonrise_moonset' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_moonrise_moonset', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_moonrise_moonset' );
		}
		if( isset( $_POST[ 'wpcloudy_wind' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_wind', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_wind' );
		}
		if(isset($_POST['wpcloudy_wind_unit'])){
		  update_post_meta($post_id, '_wpcloudy_wind_unit', $_POST['wpcloudy_wind_unit']);
		}
		if( isset( $_POST[ 'wpcloudy_humidity' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_humidity', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_humidity' );
		}
		if( isset( $_POST[ 'wpcloudy_pressure' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_pressure', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_pressure' );
		}
		if( isset( $_POST[ 'wpcloudy_cloudiness' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_cloudiness', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_cloudiness' );
		}
		if( isset( $_POST[ 'wpcloudy_precipitation' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_precipitation', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_precipitation' );
		}
		if(isset($_POST['wpcloudy_hours_forecast_no'])){
		  update_post_meta($post_id, '_wpcloudy_hours_forecast_no', $_POST['wpcloudy_hours_forecast_no']);
		}
		if( isset( $_POST[ 'wpcloudy_current_temperature' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_current_temperature', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_current_temperature' );
		}
		if( isset( $_POST[ 'wpcloudy_display_length_days_names' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_display_length_days_names', $_POST[ 'wpcloudy_display_length_days_names' ] );
		}
		if(isset($_POST['wpcloudy_forecast_no'])){
		  update_post_meta($post_id, '_wpcloudy_forecast_no', $_POST['wpcloudy_forecast_no']);
		}
		if( isset( $_POST[ 'wpcloudy_forecast_precipitations' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_forecast_precipitations', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_forecast_precipitations' );
		}
		if( isset( $_POST[ 'wpcloudy_disable_spinner' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_disable_spinner', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_disable_spinner' );
		}
		if( isset( $_POST[ 'wpcloudy_disable_anims' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_disable_anims', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_disable_anims' );
		}
		if( isset( $_POST[ 'wpcloudy_background_color' ] ) ) {
		  update_post_meta( $post_id, '_wpcloudy_background_color', esc_html($_POST[ 'wpcloudy_background_color' ] ));
		}
		if( isset( $_POST[ 'wpcloudy_text_color' ] ) ) {
		  update_post_meta( $post_id, '_wpcloudy_text_color', esc_html($_POST[ 'wpcloudy_text_color' ] ));
		}
		if( isset( $_POST[ 'wpcloudy_border_color' ] ) ) {
		  update_post_meta( $post_id, '_wpcloudy_border_color', esc_html($_POST[ 'wpcloudy_border_color' ] ));
		}
		if(isset($_POST['wpcloudy_custom_css'])){
		  update_post_meta($post_id, '_wpcloudy_custom_css', esc_html($_POST['wpcloudy_custom_css']));
		}
		if(isset($_POST['wpcloudy_size'])) {
		  update_post_meta($post_id, '_wpcloudy_size', esc_html($_POST['wpcloudy_size']));
		}
		if( isset( $_POST[ 'wpcloudy_owm_link' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_owm_link', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_owm_link' );
		}
		if( isset( $_POST[ 'wpcloudy_last_update' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_last_update', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_last_update' );
		}
		if(isset($_POST['wpcloudy_font'])){
		  update_post_meta($post_id, '_wpcloudy_font', $_POST['wpcloudy_font']);
		}
		if(isset($_POST['wpcloudy_template'])){
		  update_post_meta($post_id, '_wpcloudy_template', $_POST['wpcloudy_template']);
		}
		if(isset($_POST['wpcloudy_iconpack'])){
		  update_post_meta($post_id, '_wpcloudy_iconpack', $_POST['wpcloudy_iconpack']);
		}
		if( isset( $_POST[ 'wpcloudy_map' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_map', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_map' );
		}
		if(isset($_POST['wpcloudy_map_height'])){
		  update_post_meta($post_id, '_wpcloudy_map_height', esc_html($_POST['wpcloudy_map_height']));
		}
		if(isset($_POST['wpcloudy_map_opacity'])) {
		  update_post_meta($post_id, '_wpcloudy_map_opacity', $_POST['wpcloudy_map_opacity']);
		}
		if(isset($_POST['wpcloudy_map_zoom'])) {
		  update_post_meta($post_id, '_wpcloudy_map_zoom', $_POST['wpcloudy_map_zoom']);
		}
		if( isset( $_POST[ 'wpcloudy_map_disable_zoom_wheel' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_map_disable_zoom_wheel', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_map_disable_zoom_wheel' );
		}
		if( isset( $_POST[ 'wpcloudy_map_stations' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_map_stations', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_map_stations' );
		}
		if( isset( $_POST[ 'wpcloudy_map_clouds' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_map_clouds', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_map_clouds' );
		}
		if( isset( $_POST[ 'wpcloudy_map_precipitation' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_map_precipitation', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_map_precipitation' );
		}
		if( isset( $_POST[ 'wpcloudy_map_snow' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_map_snow', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_map_snow' );
		}
		if( isset( $_POST[ 'wpcloudy_map_wind' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_map_wind', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_map_wind' );
		}
		if( isset( $_POST[ 'wpcloudy_map_temperature' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_map_temperature', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_map_temperature' );
		}
		if( isset( $_POST[ 'wpcloudy_map_pressure' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_map_pressure', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_map_pressure' );
		}
		if( isset( $_POST[ 'wpcloudy_gtag' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_gtag', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_gtag' );
		}
		if( isset( $_POST[ 'wpcloudy_bypass_exclude' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_bypass_exclude', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_bypass_exclude' );
		}
		if( isset( $_POST[ 'wpcloudy_owm_language' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_owm_language',  $_POST['wpcloudy_owm_language']);
		}
		if( isset( $_POST[ 'wpcloudy_alerts' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_alerts', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_alerts' );
		}
		if( isset( $_POST[ 'wpcloudy_alerts_button_color' ] ) ) {
		  update_post_meta( $post_id, '_wpcloudy_alerts_button_color', esc_html($_POST[ 'wpcloudy_alerts_button_color' ] ));
		}
		if(isset($_POST['wpcloudy_chart_height'])){
		  update_post_meta($post_id, '_wpcloudy_chart_height', esc_html($_POST['wpcloudy_chart_height']));
		}
		if(isset($_POST['wpcloudy_chart_background_color'])){
		  update_post_meta($post_id, '_wpcloudy_chart_background_color', esc_html($_POST['wpcloudy_chart_background_color']));
		}
		if(isset($_POST['wpcloudy_chart_temperature_color'])){
		  update_post_meta($post_id, '_wpcloudy_chart_temperature_color', esc_html($_POST['wpcloudy_chart_temperature_color']));
		}
		if(isset($_POST['wpcloudy_chart_feels_like_color'])){
		  update_post_meta($post_id, '_wpcloudy_chart_feels_like_color', esc_html($_POST['wpcloudy_chart_feels_like_color']));
		}
		if(isset($_POST['wpcloudy_chart_dew_point_color'])){
		  update_post_meta($post_id, '_wpcloudy_chart_dew_point_color', esc_html($_POST['wpcloudy_chart_dew_point_color']));
		}
	}
}

add_action('save_post','wpc_clear_cache_current');
function wpc_clear_cache_current() {
	if ( 'wpc-weather' === get_post_type()) {
        global $wpdb;
		$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_myweather%' ");
		$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_myweather%' ");
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Function CSS/Display/Misc
///////////////////////////////////////////////////////////////////////////////////////////////////

function wpc_css_background_color($color) {
	if( $color ) {
			return 'background-color:'. $color.';';
	}
	return '';
}
function wpc_css_text_color($attribute, $color) {
	if( $color ) {
			return $attribute.':'.$color.';';
	}
	return '';
}
function wpc_css_border($color) {
	if( $color ) {
			return 'border:1px solid '. $color.';';
	}
	return '';
}
function wpc_css_border_color($color) {
	if( $color ) {
			return 'border-color:'. $color.';';
	}
	return '';
}
function wpc_css_background_size($size) {
	if( $size ) {
			return 'background-size:'. $size.';';
	}
	return '';
}
function wpc_css_background_position($horizontal, $vertical) {
	if( $horizontal && $vertical ) {
			return 'background-position: '.$horizontal.'% '.$vertical.'%;';
	}
	return "";
}
function wpc_css_font_family($font) {
	if( $font != 'Default' ) {
			return 'font-family:\'' . $font . '\';';
	}
	return '';
}
function wpc_css_height($height) {
	if( $height ) {
			return 'height:'. $height .'px;';
	}
	return '';
}


function wpcloudy_city_name($custom_city_name, $owm_city_name) {

	if (!empty($custom_city_name)) {
		return $custom_city_name;
	} else if(!empty($owm_city_name)) {
		return $owm_city_name;
	}

	return '';
}

function wpc_display_today_sunrise_sunset($wpcloudy_sunrise_sunset, $sun_rise, $sun_set, $color, $elem) {
	if( $wpcloudy_sunrise_sunset ) {
		return '<div class="sun_hours col">
					<' . $elem . ' class="sunrise" title="'.__('Sunrise','wp-cloudy').'">'. sunrise($color) . '<span class="font-weight-bold">' . $sun_rise .'</span></' . $elem . '><' . $elem . ' class="sunset" title="'.__('Sunset','wp-cloudy').'">'. sunset($color) . '<span class="font-weight-bold">' . $sun_set .'</span></' . $elem . '>
				</div>';
	}

	return '';
}

function wpc_display_today_moonrise_moonset($wpcloudy_moonrise_moonset, $moon_rise, $moon_set, $color, $elem) {
	if( $wpcloudy_moonrise_moonset ) {
		return '<div class="moon_hours col">
					<' . $elem . ' class="moonrise" title="'.__('Moonrise','wp-cloudy').'">'. moonrise($color) . '<span class="font-weight-bold">' . $moon_rise .'</span></' . $elem . '><' . $elem . ' class="moonset" title="'.__('Moonset','wp-cloudy').'">'. moonset($color) . '<span class="font-weight-bold">' . $moon_set .'</span></' . $elem . '>
				</div>';
	}

	return '';
}

function wpc_webfont($bypass, $id) {
	$wpc_webfont_value = wpc_get_bypass($bypass, "font", $id);

    if ($wpc_webfont_value != 'Default') {
        wp_register_style($wpc_webfont_value, '//fonts.googleapis.com/css?family=' . str_replace(' ', '+', $wpc_webfont_value) . ':400&display=swap' );
       	wp_enqueue_style($wpc_webfont_value);
    }

	return $wpc_webfont_value;
}

function wpc_icons_pack($bypass, $id) {
	$iconpack = wpc_get_bypass($bypass, "iconpack", $id);

    if ($iconpack == 'WeatherIcons') {
      	wp_register_style("weathericons-css", plugins_url('css/weather-icons.min.css', __FILE__));
      	wp_enqueue_style("weathericons-css");
      	wp_register_style("weathericons-wind-css", plugins_url('css/weather-icons-wind.min.css', __FILE__)); //bugbug only load if wind is selected
      	wp_enqueue_style("weathericons-css");
    } else if ($iconpack == 'IconVault') {
      	wp_register_style("iconvault-css", plugins_url('css/iconvault.min.css', __FILE__));
      	wp_enqueue_style("iconvault-css");
    } else {
      	wp_register_style("climacons-css", plugins_url('css/climacons-font.min.css', __FILE__));
      	wp_enqueue_style("climacons-css");
    }

    return $iconpack;
}

function wpc_weather_bg_img($attr) {
	if(function_exists('wpcloudy_weather_bg_img')) {
		extract(shortcode_atts(array( 'id' => ''), $attr));
		$wpc_image_bg_value = get_post_meta($id,'_wpcloudy_image_bg',true);

		if ($wpc_image_bg_value == 'yes') {
			wp_enqueue_style('wpcloudy-skin-addon-weather-bg-img');
		}
	}
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Add shortcode Weather
///////////////////////////////////////////////////////////////////////////////////////////////////


add_shortcode("wpc-weather", 'wpc_get_my_weather_id');

function wpc_get_my_weather_id($attr) {
	require_once dirname( __FILE__ ) . '/wpcloudy-options.php';

	extract(shortcode_atts(array( 'id' => ''), $attr));

    $wpc_opt = [];
	$wpc_opt["id"] = $id;
    $wpc_opt["bypass_exclude"]      = get_post_meta($wpc_opt["id"],'_wpcloudy_bypass_exclude',true);
    $bypass = $wpc_opt["bypass_exclude"] != 'yes';
	$wpc_opt["disable_anims"]	    = wpc_get_bypass_yn($bypass, "disable_anims", $wpc_opt["id"]);
	$wpc_opt["map"]           		= wpc_get_bypass_yn($bypass, "map", $wpc_opt["id"]);
	$wpc_opt["template"]  			= wpc_get_bypass($bypass, "template", $wpc_opt["id"]);
	$wpc_opt["disable_spinner"] 	= wpc_get_bypass_yn($bypass, "disable_spinner", $wpc_opt["id"]);

	wpc_webfont($bypass, $wpc_opt["id"]);
	wpc_icons_pack($bypass, $id);
	wpc_weather_bg_img($attr);

	if ($wpc_opt["template"] == 'theme1' || $wpc_opt["template"] == 'theme2' ) {
		wp_enqueue_script('jquery');
		wp_enqueue_script('wpc-flexslider-js');
		wp_enqueue_style('wpc-flexslider-css');
	} else if ($wpc_opt["template"] == 'chart1' || $wpc_opt["template"] == 'chart2' ) {
		wp_enqueue_script('chart-js');
	}

    if (wpc_get_admin_bypass('wpc_advanced_disable_modal_js') != 'yes') {
		wp_enqueue_script('jquery');
		wp_enqueue_style('bootstrap-css');
		wp_enqueue_script('bootstrap-js');
		wp_enqueue_script('popper-js');
    }


	if ($wpc_opt["disable_anims"] == 'yes') {
        echo '<style>
              .wpc-'.$wpc_opt["id"].' {
                /*CSS transitions*/
                -o-transition-property: none !important;
                -moz-transition-property: none !important;
                -ms-transition-property: none !important;
                -webkit-transition-property: none !important;
                transition-property: none !important;
                /*CSS transforms*/
                -o-transform: none !important;
                -moz-transform: none !important;
                -ms-transform: none !important;
                -webkit-transform: none !important;
                transform: none !important;
                /*CSS animations*/
                -webkit-animation: none !important;
                -moz-animation: none !important;
                -o-animation: none !important;
                -ms-animation: none !important;
                animation: none !important;
              }
              </style>';
    } else {
    	wp_enqueue_style('wpcloudy-anim');
    }

    //Map
      if ($wpc_opt["map"] == 'yes') {
    	wp_register_script("leaflet-js", plugins_url('js/leaflet.js', __FILE__), "1.0", false);

    	wp_register_script("leaflet-openweathermap-js", plugins_url('js/leaflet-openweathermap.js', __FILE__), "1.0", false);

      	wp_register_style("leaflet-openweathermap-css", plugins_url('css/leaflet-openweathermap.css', __FILE__));
      	wp_register_style("leaflet-css", plugins_url('css/leaflet.css', __FILE__));

      	wp_enqueue_script("leaflet-js");

      	wp_enqueue_script("leaflet-openweathermap-js");

      	wp_enqueue_style("leaflet-openweathermap-css");
      	wp_enqueue_style("leaflet-css");
    }

	$ret = '<div id="wpc-weather-id-'.$wpc_opt["id"].'" class="wpc-weather-id" data-id="'.$wpc_opt["id"].'">';
	if ($wpc_opt["disable_spinner"] != 'yes') {
	    $ret .= '<div class="wpc-loading-spinner"><img src="'. plugins_url( 'img/owmloading.gif', __FILE__) . '" alt="loader"/></div>';
	}
	$ret .= '</div>';

	return $ret;
}

add_action( 'wp_ajax_wpc_get_my_weather', 'wpc_get_my_weather' );
add_action( 'wp_ajax_nopriv_wpc_get_my_weather', 'wpc_get_my_weather' );

function wpc_get_my_weather($attr) {
	check_ajax_referer( 'wpc_get_weather_nonce', $_POST['_ajax_nonce'], true );

	if ( isset( $_POST['wpc_param'] ) ) {
		$id = $_POST['wpc_param'];

		require_once dirname( __FILE__ ) . '/wpcloudy-options.php';
		require_once dirname( __FILE__ ) . '/wpcloudy-anim.php';
		require_once dirname( __FILE__ ) . '/wpcloudy-icons.php';

        convertSettings($id);

        $wpc_opt                                    = [];
	  	$wpc_opt["id"] 								= $id;
        $wpc_opt["bypass_exclude"]                  = get_post_meta($wpc_opt["id"],'_wpcloudy_bypass_exclude',true);
        $bypass = $wpc_opt["bypass_exclude"] != 'yes';
	  	$wpc_opt["id_owm"]          				= get_post_meta($wpc_opt["id"],'_wpcloudy_id_owm',true);
	  	$wpc_opt["longitude"]          				= get_post_meta($wpc_opt["id"],'_wpcloudy_longitude',true);
	  	$wpc_opt["latitude"]          				= get_post_meta($wpc_opt["id"],'_wpcloudy_latitude',true);
	  	$wpc_opt["city"]                			= str_replace(' ', '+', strtolower(get_post_meta($wpc_opt["id"],'_wpcloudy_city',true)));
		$wpc_opt["custom_city_name"]       			= get_post_meta($wpc_opt["id"],'_wpcloudy_city_name',true);
		$wpc_opt["country_code"]            		= str_replace(' ', '+', get_post_meta($wpc_opt["id"],'_wpcloudy_country_code',true));
		$wpc_opt["temperature_unit"]       			= wpc_get_bypass($bypass, "unit");
    	$wpc_opt["map"]           		            = wpc_get_bypass_yn($bypass, "map");
		$wpc_opt["map_height"]            			= wpc_get_bypass($bypass, "map_height");
		$wpc_opt["map_opacity"]          			= wpc_get_bypass($bypass, "map_opacity");
		$wpc_opt["map_zoom"]              			= wpc_get_bypass($bypass, "map_zoom");
		$wpc_opt["map_disable_zoom_wheel"]     		= wpc_get_bypass_yn($bypass, "map_disable_zoom_wheel");
		$wpc_opt["map_stations"]            		= wpc_get_bypass_yn($bypass, "map_stations");
		$wpc_opt["map_clouds"]            			= wpc_get_bypass_yn($bypass, "map_clouds");
		$wpc_opt["map_precipitation"]         		= wpc_get_bypass_yn($bypass, "map_precipitation");
		$wpc_opt["map_snow"]              			= wpc_get_bypass_yn($bypass, "map_snow");
		$wpc_opt["map_wind"]              			= wpc_get_bypass_yn($bypass, "map_wind");
		$wpc_opt["map_temperature"]         		= wpc_get_bypass_yn($bypass, "map_temperature");
		$wpc_opt["map_pressure"]            		= wpc_get_bypass_yn($bypass, "map_pressure");
		$wpc_opt["border_color"]             		= wpc_get_bypass($bypass, "border_color");
		$wpc_opt["background_color"]   	          	= wpc_get_bypass($bypass, "background_color");
		$wpc_opt["text_color"]         		        = wpc_get_bypass($bypass, "text_color");
		$wpc_opt["time_format"]          			= wpc_get_bypass($bypass, "time_format");
		$wpc_opt["sunrise_sunset"]          		= wpc_get_bypass_yn($bypass, "sunrise_sunset");
		$wpc_opt["moonrise_moonset"]         		= wpc_get_bypass_yn($bypass, "moonrise_moonset");
		$wpc_opt["display_temperature_unit"]   		= wpc_get_bypass_yn($bypass, "display_temperature_unit");
		$wpc_opt["display_length_days_names"]     	= wpc_get_bypass($bypass, "display_length_days_names");
		$wpc_opt["cache_time"]      	            = wpc_get_admin_cache_time();
		$wpc_opt["disable_cache"]       	    	= wpc_get_admin_disable_cache();
		$wpc_opt["api_key"]           			    = wpc_get_admin_api_key();
		$wpc_opt["owm_link"]          	    	    = wpc_get_bypass_yn($bypass, "owm_link");
		$wpc_opt["last_update"]       		        = wpc_get_bypass_yn($bypass, "last_update");
		$wpc_opt["hours_forecast_no"]  				= wpc_get_bypass($bypass, "hours_forecast_no");
		$wpc_opt["days_forecast_no"]     			= wpc_get_bypass($bypass, "forecast_no");
		$wpc_opt["forecast_precipitations"]			= wpc_get_bypass_yn($bypass, "forecast_precipitations");
		$wpc_opt["custom_timezone"]			    	= wpc_get_bypass($bypass, "custom_timezone");
		$wpc_opt["today_date_format"]      			= wpc_get_bypass($bypass, "today_date_format");
		$wpc_opt["alerts"]                          = wpc_get_bypass_yn($bypass, "alerts");
		$wpc_opt["alerts_button_color"]             = wpc_get_bypass($bypass, "alerts_button_color");
		$wpc_opt["owm_language"]                    = wpc_get_bypass($bypass, "owm_language");
    	$wpc_opt["font"]    			            = wpc_get_bypass($bypass, "font");
    	$wpc_opt["iconpack"]  			            = wpc_get_bypass($bypass, "iconpack");
	    $wpc_opt["template"]  			            = wpc_get_bypass($bypass, "template");
        $wpc_opt["gtag"]                            = get_post_meta($wpc_opt["id"],'_wpcloudy_gtag',true);
		$wpc_opt["custom_css"]                      = wpc_get_bypass($bypass, 'custom_css');
		$wpc_opt["current_weather_symbol"]  		= wpc_get_bypass_yn($bypass, "current_weather_symbol");
		$wpc_opt["current_city_name"]        		= wpc_get_bypass_yn($bypass, "current_city_name");
		$wpc_opt["current_weather_description"]     = wpc_get_bypass_yn($bypass, "current_weather_description");
		$wpc_opt["wind"]          					= wpc_get_bypass_yn($bypass, "wind");
        $wpc_opt["wind_unit"]                       = wpc_get_bypass($bypass, "wind_unit");
        $wpc_opt["humidity"]        				= wpc_get_bypass_yn($bypass, "humidity");
		$wpc_opt["pressure"]        				= wpc_get_bypass_yn($bypass, "pressure");
		$wpc_opt["cloudiness"]      				= wpc_get_bypass_yn($bypass, "cloudiness");
		$wpc_opt["precipitation"]     				= wpc_get_bypass_yn($bypass, "precipitation");
		$wpc_opt["current_temperature"] 			= wpc_get_bypass_yn($bypass, "current_temperature");
		$wpc_opt["size"]          					= wpc_get_bypass($bypass, "size");
        $wpc_opt["disable_spinner"]                 = wpc_get_bypass_yn($bypass, "disable_spinner");
		$wpc_opt["bg_img"]            		        = get_post_meta($wpc_opt["id"],'_wpcloudy_weather_bg_img',true); // bugbug
		$wpc_opt["image_bg_cover"]					= get_post_meta($wpc_opt["id"],'_wpcloudy_image_bg_cover',true);
		$wpc_opt["image_bg_cover_e"]				= null;
		$wpc_opt["image_bg_position_horizontal_e"]	= null;
		$wpc_opt["image_bg_position_vertical_e"]	= null;

    	$wpc_opt["chart_height"]	    		    = wpc_get_bypass($bypass, 'chart_height');
    	if ($wpc_opt["chart_height"] == '') {
	        $wpc_opt["chart_height"] = '400';
    	}
    	$wpc_opt["chart_background_color"]		    = wpc_get_bypass($bypass, 'chart_background_color');
    	$wpc_opt["chart_temperature_color"]	        = wpc_get_bypass($bypass, 'chart_temperature_color');
    	$wpc_opt["chart_feels_like_color"]	        = wpc_get_bypass($bypass, 'chart_feels_like_color');
    	$wpc_opt["chart_dew_point_color"]	        = wpc_get_bypass($bypass, 'chart_dew_point_color');

        //JSON : Current weather
    	//No CACHE
		if ($wpc_opt["owm_language"] == 'Default') {
		    $wpc_opt["owm_language"] = 'en';
		}
		
       	if ($wpc_opt["id_owm"] !='') {
       	    $query = "id=".$wpc_opt["id_owm"];
       	} else if ($wpc_opt["longitude"] != '' && $wpc_opt["latitude"] != '') {
       	    $query = "lat=".$wpc_opt["latitude"]."&lon=".$wpc_opt["longitude"];
       	} else {
       	    $query = "q=".$wpc_opt["city"].",".$wpc_opt["country_code"];
       	}

        if ($wpc_opt["disable_cache"] == 'yes') {
			$myweather_current_url = wp_remote_retrieve_body(wp_remote_get("https://api.openweathermap.org/data/2.5/weather?".$query."&mode=json&lang=".$wpc_opt["owm_language"]."&units=".$wpc_opt["temperature_unit"]."&APPID=".$wpc_opt["api_key"]));

          	$myweather_current = json_decode($myweather_current_url);
          	if (!$myweather_current) {
          		_e('Unable to retrieve weather data','wp-cloudy');
			}
        } else {
           	$myweather_current_url = wp_remote_retrieve_body(wp_remote_get("https://api.openweathermap.org/data/2.5/weather?".$query."&mode=json&lang=".$wpc_opt["owm_language"]."&units=".$wpc_opt["temperature_unit"]."&APPID=".$wpc_opt["api_key"]));
        	$myweather_current = json_decode($myweather_current_url);
            if (!$myweather_current) {
            } else {
				set_transient( 'myweather_current_'.$wpc_opt["id"], $myweather_current, $wpc_opt["cache_time"] * MINUTE_IN_SECONDS );
        		$myweather_current = get_transient( 'myweather_current_'.$wpc_opt["id"] );
			}
        }

		$wpcloudy_time_php = $wpc_opt["time_format"] =='12' ? 'h:i A' : 'H:i';
		$wpcloudy_hours_php = $wpc_opt["time_format"] =='12' ? 'h A' : 'H';
        $utc_time_wp = $wpc_opt["custom_timezone"] != 'Default' ? intval($wpc_opt["custom_timezone"]) * 60 : get_option('gmt_offset') * 60;

        $wpc_data = [];
        $wpc_data["name"] = $myweather_current->name ?? null; //bugbug Correct Name for lon/lat searches
        $wpc_data["id"] = $myweather_current->id ?? null;
        $wpc_data["timezone"] = $myweather_current->timezone ?? null;
        $wpc_data["timestamp"] = $myweather_current->dt ? $myweather_current->dt + (60 * $utc_time_wp) : null;
        $wpc_data["last_update"] = __('Last updated: ','wp-cloudy').date($wpcloudy_time_php, $wpc_data["timestamp"]);
        $wpc_data["latitude"] = $myweather_current->coord->lat ?? null;
        $wpc_data["longitude"] = $myweather_current->coord->lon ?? null;
        $wpc_data["condition_id"] = $myweather_current->weather[0]->id ?? null;
        $wpc_data["category"] = $myweather_current->weather[0]->main ?? null;
        $wpc_data["description"] = $myweather_current->weather[0]->description ?? null;
        $wpc_data["wind_speed"] = getConvertedWindSpeed($myweather_current->wind->speed, $wpc_opt["temperature_unit"], $wpc_opt["wind_unit"]);
        $wpc_data["wind_speed_unit"] = getWindSpeedUnit($wpc_opt["temperature_unit"], $wpc_opt["wind_unit"]);
        $wpc_data["wind_degrees"] = $myweather_current->wind->deg ?? null;
        $wpc_data["wind_direction"] = getWindDirection($myweather_current->wind->deg);
        $wpc_data["wind_gust"] = isset($myweather_current->wind->gust) ? getConvertedWindSpeed($myweather_current->wind->gust, $wpc_opt["temperature_unit"], $wpc_opt["wind_unit"]) : null;
        $wpc_data["temperature"] = $myweather_current->main->temp ? ceil($myweather_current->main->temp) : null;
        $wpc_data["temperature_unit_character"] = $wpc_opt["temperature_unit"] == 'metric' ? 'C' : 'F';
        $wpc_data["feels_like"] = $myweather_current->main->feels_like ? ceil($myweather_current->main->feels_like) : null;
        $wpc_data["humidity"] = $myweather_current->main->humidity ?? null;
        $wpc_data["pressure"] = converthp2iom($wpc_opt["temperature_unit"], $myweather_current->main->pressure);
        $wpc_data["pressure_unit"] = $wpc_opt["temperature_unit"] == 'imperial' ? __('in','wp-cloudy') : __('hPa','wp-cloudy');
        $wpc_data["cloudiness"] = $myweather_current->clouds->all ?? null;
        $wpc_data["rain_1h"] = convertPrecipitation($wpc_opt["temperature_unit"], $myweather_current->rain->{"1h"} ?? 0);
        $wpc_data["rain_3h"] = convertPrecipitation($wpc_opt["temperature_unit"], $myweather_current->rain->{"3h"} ?? 0);
        $wpc_data["snow_1h"] = convertPrecipitation($wpc_opt["temperature_unit"], $myweather_current->snow->{"1h"} ?? 0);
        $wpc_data["snow_3h"] = convertPrecipitation($wpc_opt["temperature_unit"], $myweather_current->snow->{"3h"} ?? 0);
        $wpc_data["precipitation_1h"] = $wpc_data["rain_1h"] ?? 0 + $wpc_data["snow_1h"] ?? 0;
        $wpc_data["precipitation_3h"] = $wpc_data["rain_3h"] ?? 0 + $wpc_data["snow_3h"] ?? 0;
        $wpc_data["precipitation_unit"] = $wpc_opt["temperature_unit"] == 'imperial' ? __('in','wp-cloudy') : __('mm','wp-cloudy');
        $wpc_data["owm_link"] = '<a href="https://openweathermap.org/city/'.($myweather_current->id ?? "").'" target="_blank" title="'.__('Full weather on OpenWeatherMap','wp-cloudy').'">'.__('Full weather','wp-cloudy').'</a>';
        $wpc_data["timestamp_sunrise"] = $myweather_current->sys->sunrise ? $myweather_current->sys->sunrise + (60 * $utc_time_wp) : null;
        $wpc_data["timestamp_sunset"] = $myweather_current->sys->sunset ? $myweather_current->sys->sunset + (60 * $utc_time_wp) : null;
        $wpc_data["sunrise"] = (string)date($wpcloudy_time_php, $wpc_data["timestamp_sunrise"]);
        $wpc_data["sunset"] = (string)date($wpcloudy_time_php, $wpc_data["timestamp_sunset"]);
        $wpc_data["moonrise"] = '';
        $wpc_data["moonset"] = '';

		if ($wpc_opt["today_date_format"] == 'date') {
			$today_day =  date_i18n( get_option( 'date_format' ) );
		} else if ($wpc_opt["today_date_format"] == 'day') {
			switch (strftime("%w", $wpc_data["timestamp"])) {
		        case "0":
		          	$today_day      = __('Sunday','wp-cloudy');
		          	break;
		        case "1":
		          	$today_day      = __('Monday','wp-cloudy');
		          	break;
		        case "2":
		        	$today_day      = __('Tuesday','wp-cloudy');
		          	break;
		        case "3":
		        	$today_day      = __('Wednesday','wp-cloudy');
		          	break;
		        case "4":
		        	$today_day      = __('Thursday','wp-cloudy');
		          	break;
		        case "5":
		        	$today_day      = __('Friday','wp-cloudy');
		          	break;
		        case "6":
		        	$today_day      = __('Saturday','wp-cloudy');
		          	break;
		  		}
		} else {
			$today_day ='';
		}

        //JSON : Onecall forecast weather (relies on lat and lon from current weather call)
        if($wpc_opt["hours_forecast_no"] > 0 || $wpc_opt["days_forecast_no"] > 0 || $wpc_opt["alerts"] == 'yes' || $wpc_opt["moonrise_moonset"] == "yes") {
    		if ($wpc_opt["disable_cache"] == 'yes') {
   				$myweather_url = wp_remote_retrieve_body(wp_remote_get("https://api.openweathermap.org/data/2.5/onecall?lon=".$wpc_data["longitude"]."&lat=".$wpc_data["latitude"]."&mode=json&exclude=current,minutely&units=".$wpc_opt["temperature_unit"]."&APPID=".$wpc_opt["api_key"], array( 'timeout' => 10)));
          		$myweather = json_decode($myweather_url);
          		if (!$myweather) {
          			_e('Unable to retrieve weather data','wp-cloudy');
				}
        	} else {
              	if (false === ( $myweather = get_transient( 'myweather_'.$wpc_opt["id"] ) ) ) {
                	$myweather_url = wp_remote_retrieve_body(wp_remote_get("https://api.openweathermap.org/data/2.5/onecall?lon=".$wpc_data["longitude"]."&lat=".$wpc_data["latitude"]."&mode=json&exclude=current,minutely&units=".$wpc_opt["temperature_unit"]."&APPID=".$wpc_opt["api_key"]));
                	$myweather = json_decode($myweather_url);
	                if (!$myweather) {
	                } else {
	                	set_transient( 'myweather_'.$wpc_opt["id"], $myweather, $wpc_opt["cache_time"] * MINUTE_IN_SECONDS );
	                	$myweather = get_transient( 'myweather_'.$wpc_opt["id"] );
                	}
              	} else {
                	$myweather = get_transient( 'myweather_'.$wpc_opt["id"] );
	            }
		    }
		}

		//Days loop
		if ($wpc_opt["days_forecast_no"] > 0 || $wpc_opt["hours_forecast_no"] > 0 || $wpc_opt["moonrise_moonset"] == "yes") {
			foreach ($myweather->daily as $i => $value) {
		    	switch (strftime('%w', $myweather->daily[$i]->dt + (60 * $utc_time_wp))) {
			    	case "0":
			      		$wpc_data["daily"][$i]["day"] = $wpc_opt["display_length_days_names"] == 'short' ? __('Sun','wp-cloudy') : __('Sunday','wp-cloudy');
			      		break;
			    	case "1":
			      		$wpc_data["daily"][$i]["day"] = $wpc_opt["display_length_days_names"] == 'short' ? __('Mon','wp-cloudy') : __('Monday','wp-cloudy');
			      		break;
			    	case "2":
			    		$wpc_data["daily"][$i]["day"] = $wpc_opt["display_length_days_names"] == 'short' ? __('Tue','wp-cloudy') : __('Tuesday','wp-cloudy');
			      		break;
			    	case "3":
			    		$wpc_data["daily"][$i]["day"] = $wpc_opt["display_length_days_names"] == 'short' ? __('Wed','wp-cloudy') : __('Wednesday','wp-cloudy');
			      		break;
			    	case "4":
			    		$wpc_data["daily"][$i]["day"] = $wpc_opt["display_length_days_names"] == 'short' ? __('Thu','wp-cloudy') : __('Thursday','wp-cloudy');
			      		break;
			    	case "5":
			    		$wpc_data["daily"][$i]["day"] = $wpc_opt["display_length_days_names"] == 'short' ? __('Fri','wp-cloudy') : __('Friday','wp-cloudy');
			      		break;
			    	case "6":
			    		$wpc_data["daily"][$i]["day"] = $wpc_opt["display_length_days_names"] == 'short' ? __('Sat','wp-cloudy') : __('Saturday','wp-cloudy');
				      	break;
			    }

                $wpc_data["daily"][$i]["timestamp"] = $value->dt ? $value->dt + (60 * $utc_time_wp) : null;
                $wpc_data["daily"][$i]["timestamp_sunrise"] = $value->sunrise ? $value->sunrise + (60 * $utc_time_wp) : null;
                $wpc_data["daily"][$i]["timestamp_sunset"] = $value->sunset ? $value->sunset + (60 * $utc_time_wp) : null;
                $wpc_data["daily"][$i]["sunrise"] = (string)date($wpcloudy_time_php, $wpc_data["daily"][$i]["timestamp_sunrise"]);
                $wpc_data["daily"][$i]["sunset"] = (string)date($wpcloudy_time_php, $wpc_data["daily"][$i]["timestamp_sunset"]);
                $wpc_data["daily"][$i]["timestamp_moonrise"] = $value->moonrise ? $value->moonrise + (60 * $utc_time_wp) : null;
                $wpc_data["daily"][$i]["timestamp_moonset"] = $value->moonset ? $value->moonset + (60 * $utc_time_wp) : null;
                $wpc_data["daily"][$i]["moonrise"] = (string)date($wpcloudy_time_php, $wpc_data["daily"][$i]["timestamp_moonrise"]);
                $wpc_data["daily"][$i]["moonset"] = (string)date($wpcloudy_time_php, $wpc_data["daily"][$i]["timestamp_moonset"]);
                $wpc_data["daily"][$i]["moon_phase"] = $value->moon_phase ?? null;
                $wpc_data["daily"][$i]["condition_id"] = $value->weather[0]->id ?? null;
                $wpc_data["daily"][$i]["category"] = $value->weather[0]->main ?? null;
                $wpc_data["daily"][$i]["description"] = $value->weather[0]->description ?? null;
                $wpc_data["daily"][$i]["wind_speed"] = getConvertedWindSpeed($value->wind_speed, $wpc_opt["temperature_unit"], $wpc_opt["wind_unit"]);
                $wpc_data["daily"][$i]["wind_degrees"] = $value->wind_deg ?? null;
                $wpc_data["daily"][$i]["wind_direction"] = getWindDirection($value->wind_deg);
                $wpc_data["daily"][$i]["wind_gust"] = isset($value->wind_gust) ? getConvertedWindSpeed($value->wind_gust, $wpc_opt["temperature_unit"], $wpc_opt["wind_unit"]) : null;
                $wpc_data["daily"][$i]["temperature_morning"] = $value->temp->morn ? ceil($value->temp->morn) : null;
                $wpc_data["daily"][$i]["temperature_day"] = $value->temp->day ? ceil($value->temp->day) : null;
                $wpc_data["daily"][$i]["temperature_evening"] = $value->temp->eve ? ceil($value->temp->eve) : null;
                $wpc_data["daily"][$i]["temperature_night"] = $value->temp->eve ? ceil($value->temp->night) : null;
                $wpc_data["daily"][$i]["temperature_minimum"] = $value->temp->min ? ceil($value->temp->min) : null;
                $wpc_data["daily"][$i]["temperature_maximum"] = $value->temp->max ? ceil($value->temp->max) : null;
                $wpc_data["daily"][$i]["feels_like_morning"] = $value->feels_like->morn ? ceil($value->feels_like->morn) : null;
                $wpc_data["daily"][$i]["feels_like_day"] = $value->feels_like->day ? ceil($value->feels_like->day) : null;
                $wpc_data["daily"][$i]["feels_like_evening"] = $value->feels_like->eve ? ceil($value->feels_like->eve) : null;
                $wpc_data["daily"][$i]["feels_like_night"] = $value->feels_like->night ? ceil($value->feels_like->night) : null;
                $wpc_data["daily"][$i]["humidity"] = $value->humidity ?? null;
                $wpc_data["daily"][$i]["pressure"] = converthp2iom($wpc_opt["temperature_unit"], $value->pressure);
                $wpc_data["daily"][$i]["dew_point"] = $value->dew_point ? ceil($value->dew_point) : null;
                $wpc_data["daily"][$i]["cloudiness"] = $value->clouds->all ?? null;
                $wpc_data["daily"][$i]["uv_index"] = $value->uvi ?? null;
                $wpc_data["daily"][$i]["rain_chance"] = $value->pop ? number_format($value->pop * 100, 0) . '%' : null;
                $wpc_data["daily"][$i]["rain"] = convertPrecipitation($wpc_opt["temperature_unit"], $value->rain ?? 0);
                $wpc_data["daily"][$i]["snow"] = convertPrecipitation($wpc_opt["temperature_unit"], $value->snow ?? 0);
                $wpc_data["daily"][$i]["precipitation"] = $wpc_data["daily"][$i]["rain"] ?? 0 + $wpc_data["daily"][$i]["snow"] ?? 0;

			    $date_index = date('Ymd', $wpc_data["daily"][$i]["timestamp"]);
			    $wpc_data[$date_index]["sunrise"] = $wpc_data["daily"][$i]["timestamp_sunrise"];
			    $wpc_data[$date_index]["sunset"] = $wpc_data["daily"][$i]["timestamp_sunset"];
			}
		}//End days loop

		//Hours loop (must be after days loop)
		if($wpc_opt["hours_forecast_no"] > 0) {
            $cnt = 0;
            foreach ($myweather->hourly as $i => $value) {
                if ($value->dt > (time()-3600)) {
                    $wpc_data["hourly"][$cnt]["timestamp"] = $value->dt ? $value->dt + (60 * $utc_time_wp) : null;
                    $wpc_data["hourly"][$cnt]["time"] = $cnt == 0 ? __( 'Now', 'wp-cloudy' ) : (string)date($wpcloudy_hours_php, $value->dt + (60*$utc_time_wp));
                    $wpc_data["hourly"][$cnt]["condition_id"] = $value->weather[0]->id ?? null;
                    $wpc_data["hourly"][$cnt]["category"] = $value->weather[0]->main ?? null;
                    $wpc_data["hourly"][$cnt]["description"] = $value->weather[0]->description ?? null;
                    $wpc_data["hourly"][$cnt]["wind_speed"] = getConvertedWindSpeed($value->wind_speed, $wpc_opt["temperature_unit"], $wpc_opt["wind_unit"]);
                    $wpc_data["hourly"][$cnt]["wind_degrees"] = $value->wind_deg ?? null;
                    $wpc_data["hourly"][$cnt]["wind_direction"] = getWindDirection($value->wind_deg);
                    $wpc_data["hourly"][$cnt]["wind_gust"] = isset($value->wind_gust) ? getConvertedWindSpeed($value->wind_gust, $wpc_opt["temperature_unit"], $wpc_opt["wind_unit"]) : null;
                    $wpc_data["hourly"][$cnt]["temperature"] = $value->temp ? ceil($value->temp) : null;
                    $wpc_data["hourly"][$cnt]["feels_like"] = $value->feels_like ? ceil($value->feels_like) : null;
                    $wpc_data["hourly"][$cnt]["humidity"] = $value->humidity ?? null;
                    $wpc_data["hourly"][$cnt]["pressure"] = converthp2iom($wpc_opt["temperature_unit"], $value->pressure);
                    $wpc_data["hourly"][$cnt]["dew_point"] = $value->dew_point ? ceil($value->dew_point) : null;
                    $wpc_data["hourly"][$cnt]["cloudiness"] = $value->clouds ?? null;
                    $wpc_data["hourly"][$cnt]["uv_index"] = $value->uvi ?? null;
                    $wpc_data["hourly"][$cnt]["rain_chance"] = $value->pop ? number_format($value->pop * 100, 0) .'%': null;
                    $wpc_data["hourly"][$cnt]["visibility"] = $value->visibility  ?? null;
                    $wpc_data["hourly"][$cnt]["rain"] = convertPrecipitation($wpc_opt["temperature_unit"], $value->rain->{"1h"} ?? 0);
                    $wpc_data["hourly"][$cnt]["snow"] = convertPrecipitation($wpc_opt["temperature_unit"], $value->snow->{"1h"} ?? 0);
                    $wpc_data["hourly"][$cnt]["precipitation"] = $wpc_data["hourly"][$cnt]["rain"] ?? 0 + $wpc_data["hourly"][$cnt]["snow"] ?? 0;
       			    $date = date('Ymd', $wpc_data["hourly"][$cnt]["timestamp"]);
       			    if (isset($wpc_data[$date])) {
           			    $wpc_data["hourly"][$cnt]["day_night"] = ($wpc_data["hourly"][$cnt]["timestamp"] > $wpc_data[$date]["sunrise"] && $wpc_data["hourly"][$cnt]["timestamp"] < $wpc_data[$date]["sunset"]) ? 'day' : 'night';
           			} else {
                        $wpc_data["hourly"][$cnt]["day_night"] = 'night';
           			}
       			    ++$cnt;
                }
		  	}
		}

        //Moon rise and set
        if (!empty($wpc_data["daily"])) {
            $wpc_data["timestamp_moonrise"] = $wpc_data["daily"][0]["timestamp_moonrise"] + (60 * $utc_time_wp);
            $wpc_data["timestamp_moonset"] = $wpc_data["daily"][0]["timestamp_moonset"] + (60 * $utc_time_wp);
            $wpc_data["moonrise"] = (string)date($wpcloudy_time_php, $wpc_data["timestamp_moonrise"]);
            $wpc_data["moonset"] = (string)date($wpcloudy_time_php, $wpc_data["timestamp_moonset"]);
        }

		//Alerts loop
		if (isset($myweather->alerts)) {
			foreach ($myweather->alerts as $i => $value) {
			    $wpc_data["alerts"][$i]["sender"] = $value->sender_name;
			    $wpc_data["alerts"][$i]["event"] = $value->event;
			    $wpc_data["alerts"][$i]["start"] = date_i18n( __( 'M j, Y @ G:i' ), $value->start );
			    $wpc_data["alerts"][$i]["end"] = date_i18n( __( 'M j, Y @ G:i' ), $value->end );
			    $wpc_data["alerts"][$i]["text"] = $value->description;
			}
		}


		//variable declarations
        $wpc_html = [];
		$wpc_html["now"]["start"]             	= '';
		$wpc_html["now"]["location_name"]       = '';
		$wpc_html["now"]["time_symbol"]       	= '';
		$wpc_html["now"]["time_temperature"]    = '';
		$wpc_html["now"]["weather_description"] = '';
		$wpc_html["now"]["end"]               	= '';
		$wpc_html["custom_css"]            		= '';
		$wpc_html["today"]["start"]          	= '';
		$wpc_html["today"]["day"]          		= '';
		$wpc_html["today"]["sun"]             	= '';
		$wpc_html["today"]["moon"]             	= '';
		$wpc_html["info"]["start"]             	= '';
		$wpc_html["info"]["wind"]            	= '';
		$wpc_html["info"]["humidity"]          	= '';
		$wpc_html["info"]["pressure"]           = '';
		$wpc_html["info"]["cloudiness"]         = '';
		$wpc_html["info"]["precipitation"]      = '';
		$wpc_html["info"]["end"]             	= '';
		$wpc_html["hour"]["info"]               = '';
		$wpc_html["hour"]["start"]            	= '';
		$wpc_html["hour"]["end"]              	= '';
		$wpc_html["forecast"]["info"]           = '';
		$wpc_html["map"]                 		= '';
		$wpc_html["today"]["end"]          		= '';
		$wpc_html["forecast"]["start"]          = '';
		$wpc_html["forecast"]["end"]            = '';
		$wpc_html["container"]["start"]         = '';
		$wpc_html["container"]["end"]           = '';
		$wpc_html["owm_link"]            		= '';
		$wpc_html["last_update"]         		= '';
        $wpc_html["gtag"]                       = '';
        $wpc_html["alert_button"]               = '';
        $wpc_html["alert_modal"]                = '';

        $wpc_html["weather_id"]                 = 'wpc-weather-container-'.$wpc_opt["id"];
        $wpc_html["map_id"]                     = 'wpc-map-id-'.$wpc_opt["id"];
        $wpc_html["map_container_id"]           = 'wpc-map-container-'.$wpc_opt["id"];

	    $wpc_html["current"]["day_night"] = ($wpc_data["timestamp"] > $wpc_data["timestamp_sunrise"] && $wpc_data["timestamp"] < $wpc_data["timestamp_sunset"]) ? 'day' : 'night';
        $wpc_html["current"]["symbol_svg"] = weatherSVG($wpc_data["condition_id"], $wpc_html["current"]["day_night"]);

		if ($wpc_opt["custom_css"]) {
	    	$wpc_html["custom_css"] = '<style>'. $wpc_opt["custom_css"] . '</style>';
		}

		$display_now_start = '<div class="now">';
		$display_now_location_name = '<div class="location_name">'. wpcloudy_city_name($wpc_opt["custom_city_name"], $wpc_data["name"])  .'</div>';
		$display_now_time_symbol = '<div class="time_symbol climacon" style="'. wpc_css_text_color("fill", $wpc_opt["text_color"]) .'"><span title="'.$wpc_data["description"].'">'. $wpc_html["current"]["symbol_svg"] .'</span></div>';
		$display_now_time_temperature = '<div class="time_temperature">'. $wpc_data["temperature"] .'</div>';
		$display_now_end = '</div>';

		//Hours loop
	    if ($wpc_opt["hours_forecast_no"] > 0) {
	    	$wpcloudy_class_hours = array( 0 => "first", 1 => "second", 2 => "third", 3 => "fourth", 4 => "fifth", 5 => "sixth", 6 => "seventh", 7 => "eighth", 8 => "ninth", 9 => "tenth", 10 => "eleventh", 11 => "twelfth", 12 => "thirteenth", 13 => "fourteenth", 14 => "fifteenth", 15 => "sixteenth", 16 => "seventeenth", 17 => "eighteenth", 18 => "nineteenth", 19 => "twentieth", 20 => "twentyfirst", 21 => "twentysecond", 22 => "twentythird", 23 => "twentyfourth", 24 => "twentyfifth", 25 => "twentysixth", 26 => "twentyseventh", 27 => "twentyeighth", 28 => "twentyninth", 29 => "thirtieth", 30 => "thirtyfirst", 31 => "thirtysecond", 32 => "thirtythird", 33 => "thirtyfourth", 34 => "thirtyfifth", 35 => "thirtysixth", 36 => "thirtyseventh", 37 => "thirtyeighth", 38 => "thirtyninth", 39 => "fortieth", 40 => "fortyfirst", 41 => "fortysecond", 42 => "fortythird", 43 => "fortyfourth", 44 => "fortyfifth", 45 => "fortysixth", 46 => "fortyseventh", 47 => "fortyeighth" );

			foreach ($wpc_data["hourly"] as $i => $value) {
				$display_hours_icon[$i] = weatherIcon($wpc_opt["iconpack"], $value["condition_id"], $value["day_night"], $value["description"]);
				$display_hours_[$i] =
   					'<div class="'. $wpcloudy_class_hours[$i].' card">
   						<div class="card-body">
   						    <div class="hour">'. $value["time"] .'</div>' . $display_hours_icon[$i] . '<div class="temperature">'. $value["temperature"] . '</div>
    					</div>
   					</div>';
			}
		}

		//Daily loop
		if ($wpc_opt["days_forecast_no"] > 0) {
			$wpcloudy_class_days = array(0 => "first", 1 => "second", 2 => "third", 3 => "fourth", 4 => "fifth", 5 => "sixth", 6 => "seventh", 7 => "eighth");

			foreach ($wpc_data["daily"] as $i => $value) {
			    	$display_forecast_icon[$i] = weatherIcon($wpc_opt["iconpack"], $value["condition_id"], "day", $value["description"]);
			    	$display_forecast_[$i] =
			       		'<div class="'. $wpcloudy_class_days[$i].' row">
			          		<div class="day col">'. ($i == 0 ? "Today" : $value["day"]) .'</div>' . $display_forecast_icon[$i];

			          		if ($wpc_opt["forecast_precipitations"] == 'yes') {
		          				$display_forecast_[$i] .= '<div class="rain col">'. $value["precipitation"] .' '.$wpc_data["precipitation_unit"].'</div>';
			          		}

			          		$display_forecast_[$i] .=
			          		'<div class="temp_min col">'. $value["temperature_minimum"] .'</div>
			          		<div class="temp_max col"><span class="font-weight-bold">'. $value["temperature_maximum"] .'</span></div>
			        	</div>';
			}
		}

	    //Map

	      	if ($wpc_opt['map'] == 'yes') {

		    	//Layers opacity
	    		$display_map_layers_opacity = $wpc_opt["map_opacity"];

                $display_map_layers  = '';
                $display_map_options = '';

		    	//Stations
		    	if ( $wpc_opt["map_stations"] ) {
		        	$display_map_options         	.= 'var station = L.OWM.current({type: "station", appId: "'.$wpc_opt["api_key"].'"});';
		        	$display_map_layers             .= '"Stations": station,';
		      	}

		      	//Clouds
		      	if ( $wpc_opt["map_clouds"] ) {
		        	$display_map_options         	.= 'var clouds = L.OWM.clouds({showLegend: false, opacity: '.$display_map_layers_opacity.', appId: "'.$wpc_opt["api_key"].'"});';
		        	$display_map_layers             .= '"Clouds": clouds,';
		      	}

		      	//Precipitations
		      	if ( $wpc_opt["map_precipitation"] ) {
		        	$display_map_options         	.= 'var precipitation = L.OWM.precipitation({opacity: '.$display_map_layers_opacity.', appId: "'.$wpc_opt["api_key"].'"});';
		        	$display_map_layers             .= '"Precipitation": precipitation,';
		      	}

		      	//Snow
		      	if ( $wpc_opt["map_snow"] ) {
		        	$display_map_options         	.= 'var snow = L.OWM.snow({opacity: '.$display_map_layers_opacity.', appId: "'.$wpc_opt["api_key"].'"});';
		        	$display_map_layers             .= '"Snow": snow,';
		      	}

		      	//Wind
		      	if ( $wpc_opt["map_wind"] ) {
		        	$display_map_options         	.= 'var wind = L.OWM.wind({opacity: '.$display_map_layers_opacity.', appId: "'.$wpc_opt["api_key"].'"});';
		        	$display_map_layers             .= '"Wind": wind,';
		      	}

		      	//Temperature
		      	if ( $wpc_opt["map_temperature"] ) {
		        	$display_map_options         	.= 'var temp = L.OWM.temperature({opacity: '.$display_map_layers_opacity.', appId: "'.$wpc_opt["api_key"].'"});';
		        	$display_map_layers             .= '"Temperature": temp,';
		      	}

		      	//Pressure
		      	if ( $wpc_opt["map_pressure"] ) {
		        	$display_map_options         	.= 'var pressure = L.OWM.pressure({opacity: '.$display_map_layers_opacity.', appId: "'.$wpc_opt["api_key"].'"});';
		        	$display_map_layers             .= '"Pressure": pressure,';
		      	}

		      	//Scroll wheel
		      	$display_map_scroll_wheel = ($wpc_opt["map_disable_zoom_wheel"] == 'yes') ? "false" : "true";
		      	
		      	$temperature_unit_character = $wpc_opt["temperature_unit"] == "metric" ? 'C' : 'F';
		      	$temperature_unit_text = $wpc_opt["temperature_unit"] == "metric" ? 'Celsius' : 'Fahrenheit';
                if ($wpc_opt["wind_unit"] == "2") {
    		      	$map_speed = 'ms';
				} else if ($wpc_opt["wind_unit"] == "3") {
    		      	$map_speed = 'kmh';
                } else {
    		      	$map_speed = 'mph';
                }

		      	$wpc_html["map"] =
			        '<div id="' . $wpc_html["map_id"] . '">
			        	<div id="' . $wpc_html["map_container_id"] . '" style="'.wpc_css_height($wpc_opt["map_height"]) .'"></div>
			        </div>
			        <script type="text/javascript">
			        	jQuery(document).ready( function() {

				        	var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
							maxZoom: 18, attribution: "WP Cloudy 2" });

							var city = L.OWM.current({intervall: '.($wpc_opt["cache_time"]??30).', lang: "en", appId: "6c407f412bf644e72fa060adb84c6263",temperatureDigits:0,temperatureUnit:"'.$temperature_unit_character.'",speedUnit:"'.$map_speed.'"});'. //bugbug

							$display_map_options .

							'var map = L.map("' . $wpc_html["map_container_id"] . '", { center: new L.LatLng('. $wpc_data["latitude"] .', '. $wpc_data["longitude"] .'), zoom: '. $wpc_opt["map_zoom"] .', layers: [osm], scrollWheelZoom: '.$display_map_scroll_wheel.' });

							var baseMaps = { "OSM Standard": osm };

							var overlayMaps = {'.$display_map_layers.'"Cities": city};

							var layerControl = L.control.layers(baseMaps, overlayMaps).addTo(map);

                            map.whenReady(() => {
                               	jQuery( "#' . $wpc_html["map_container_id"] . '").on("invalidSize", function() {
                                    map.invalidateSize();
                            	});
                            });

			        	});
			        </script>';
			}


		if ($wpc_opt["image_bg_cover"] == 'yes') {
			$wpc_opt["image_bg_cover_e"] = 'cover';
		}
		$wpcloudy_image_bg_position_horizontal				= 	get_post_meta($wpc_opt["id"],'_wpcloudy_image_bg_position_horizontal',true);
		if ($wpcloudy_image_bg_position_horizontal != 'Default') {
			$wpc_opt["image_bg_position_horizontal_e"]		= 	$wpcloudy_image_bg_position_horizontal;
		}
		$wpcloudy_image_bg_position_vertical				= 	get_post_meta($wpc_opt["id"],'_wpcloudy_image_bg_position_vertical',true);
		if ($wpcloudy_image_bg_position_vertical != 'Default') {
			$wpc_opt["image_bg_position_vertical_e"]		= 	$wpcloudy_image_bg_position_vertical;
		}

		$wpc_html["container"]["start"] = '<!-- WP Cloudy : WordPress weather plugin v'.WPCLOUDY_VERSION.' - https://github.com/uwejacobs/wp-cloudy-2 -->';
		$wpc_html["container"]["start"] .= '<div id="' . $wpc_html["weather_id"] . '" class="wpc-'.$wpc_opt["id"].' wpc-weather-'.$wpc_data["condition_id"].' '. $wpc_opt["size"] .' '. $wpc_opt["template"] .'"';
		$wpc_html["container"]["start"] .= ' style="';
		$wpc_html["container"]["start"] .= wpc_css_background_color($wpc_opt["background_color"]) .
		                                    wpc_css_background_size($wpc_opt["image_bg_cover_e"]).
		                                    wpc_css_background_position($wpc_opt["image_bg_position_horizontal_e"], $wpc_opt["image_bg_position_vertical_e"]).
		                                    wpc_css_text_color("color",$wpc_opt["text_color"]).
		                                    wpc_css_border($wpc_opt["border_color"]).
		                                    wpc_css_font_family($wpc_opt["font"]);
		$wpc_html["container"]["start"] .= '">';

        // Now
        if ($wpc_opt["current_city_name"] == 'yes' || $wpc_opt["current_weather_symbol"] =='yes' || $wpc_opt["current_temperature"] =='yes' || $wpc_opt["current_weather_description"] == 'yes')  {
            $wpc_html["now"]["start"]           	= $display_now_start;
            if ($wpc_opt["current_city_name"] == 'yes') {
                $wpc_html["now"]["location_name"]       = $display_now_location_name;
            }
            if ($wpc_opt["current_weather_symbol"] =='yes') {
            	$wpc_html["now"]["time_symbol"]     = $display_now_time_symbol;
        	}
            if ($wpc_opt["current_temperature"] =='yes') {
       	        $wpc_html["now"]["time_temperature"]    = $display_now_time_temperature;
        	}
    	    if( $wpc_opt["current_weather_description"] == 'yes' ) {
	        	$wpc_html["now"]["weather_description"] = '<div class="short_condition">'. $wpc_data["description"] .'</div>';
	        }
            $wpc_html["now"]["end"]             	= $display_now_end;
        }

	   	$wpc_html["today"]["start"]     = '<div class="today row">';
        if( $wpc_opt["today_date_format"] != "none") {
	        $wpc_html["today"]["day"]       = '<div class="day col"><span class="wpc-highlight">'. $today_day .'</span></div>';
	    }
        $wpc_html["today"]["sun"]       = wpc_display_today_sunrise_sunset($wpc_opt["sunrise_sunset"], $wpc_data["sunrise"], $wpc_data["sunset"], $wpc_opt["text_color"], 'span');
        $wpc_html["today"]["sun_hor"]   = wpc_display_today_sunrise_sunset($wpc_opt["sunrise_sunset"], $wpc_data["sunrise"], $wpc_data["sunset"], $wpc_opt["text_color"], 'div');
        $wpc_html["today"]["moon"]      = wpc_display_today_moonrise_moonset($wpc_opt["moonrise_moonset"], $wpc_data["moonrise"], $wpc_data["moonset"], $wpc_opt["text_color"], 'span');
        $wpc_html["today"]["moon_hor"]  = wpc_display_today_moonrise_moonset($wpc_opt["moonrise_moonset"], $wpc_data["moonrise"], $wpc_data["moonset"], $wpc_opt["text_color"],'div');
        $wpc_html["today"]["end"]       = '</div>';

	    if( $wpc_opt["wind"] || $wpc_opt["humidity"] || $wpc_opt["pressure"] || $wpc_opt["cloudiness"] || $wpc_opt["precipitation"] ) {
	    	$wpc_html["info"]["start"] .= '<div class="infos row">';

	        if( $wpc_opt["wind"] ) {
	        	$wpc_html["info"]["wind"]            = '<div class="wind col">'. __( 'Wind', 'wp-cloudy' ) .'<span class="wpc-highlight">'. $wpc_data["wind_speed"] .' '.$wpc_data["wind_speed_unit"].' - '.$wpc_data["wind_direction"].'</span></div>';
	        }

	        if( $wpc_opt["humidity"] ) {
	        	$wpc_html["info"]["humidity"]        = '<div class="humidity col">'. __( 'Humidity', 'wp-cloudy' ) .'<span class="wpc-highlight">'. $wpc_data["humidity"] .' %</span></div>';
	        }

	        if( $wpc_opt["pressure"] ) {
	        	$wpc_html["info"]["pressure"]        = '<div class="pressure col">'. __( 'Pressure', 'wp-cloudy' ) .'<span class="wpc-highlight">'. $wpc_data["pressure"] .' '.$wpc_data["pressure_unit"].'</span></div>';
	        }

	        if( $wpc_opt["cloudiness"] ) {
	        	$wpc_html["info"]["cloudiness"]      = '<div class="cloudiness col">'. __( 'Cloudiness', 'wp-cloudy' ) .'<span class="wpc-highlight">'. $wpc_data["cloudiness"] .' %</span></div>';
	        }

	        if( $wpc_opt["precipitation"] ) {
	        	$wpc_html["info"]["precipitation"]   = '<div class="precipitation col">'. __( 'Precipitation', 'wp-cloudy' ) .'<span class="wpc-highlight">'. $wpc_data["precipitation_3h"] .' '.$wpc_data["precipitation_unit"].'</span></div>';
	        }

	        $wpc_html["info"]["end"] .= '</div>';
	    };

	    if($wpc_opt["hours_forecast_no"] > 0) {
	    	$wpc_html["hour"]["start"] = '<div class="hours card-group" style="'.wpc_css_border_color($wpc_opt["border_color"]).'">';
	        $wpc_html["hour"]["icon"]  = $display_hours_icon;
	        $wpc_html["hour"]["info"]  = $display_hours_;
	        $wpc_html["hour"]["end"]   = '</div>';
	    }

	    if ($wpc_opt["days_forecast_no"] > 0) {
	    	$wpc_html["forecast"]["start"] = '<div class="forecast d-flex flex-column justify-content-center">';
	        $wpc_html["forecast"]["icon"]  = $display_forecast_icon;
	        $wpc_html["forecast"]["info"]  = $display_forecast_;
	        $wpc_html["forecast"]["end"]   = '</div>';
	    }

        //Google Tag Manager dataLayer
        if ($wpc_opt["gtag"]) {
            $wpc_html["gtag"] = '<script type="text/javascript">
                var dataLayer = window.dataLayer = window.dataLayer || [];
	        	jQuery(document).ready(function() {
                    dataLayer.push({
                      "weatherTemperature": ' . $wpc_data["temperature"] . ',
                      "weatherCloudiness": ' . $wpc_data["cloudiness"] . ',
                      "weatherDescription": "' . $wpc_data["description"] . '",
                      "weatherCategory": "' . $wpc_data["category"] . '"
                  });
                });
            </script>';
        }

	    if ($wpc_opt["alerts"] && !empty($wpc_data["alerts"])) {
    	    require_once dirname( __FILE__ ) . '/wpcloudy-color-css.php';

            if (empty($wpc_opt["alerts_button_color"])) {
                $wpc_opt["alerts_button_color"] = '#000';
    	    }
   	        $wpc_html["alert_button"] .= '<style>' . generateColorCSS($wpc_opt["alerts_button_color"], "wpc-alert-" . $wpc_opt["id"]) . '</style>';
            $wpc_html["alert_button"] .= '<div class="wpc-alert-buttons text-center">';
            foreach($wpc_data["alerts"] as $key => $value) {
                $modal = wp_unique_id('wpc-modal-'.$wpc_opt["id"]);
                $wpc_html["alert_button"] .= '<button class="wpc-alert-button btn btn-outline-wpc-alert-' . $wpc_opt["id"] . ' m-1" data-toggle="modal" data-target="#' . $modal . '">' . $value["event"] . '</button>';
                $wpc_html["alert_modal"] .=
                    '<div class="modal fade" id="' . $modal . '" tabindex="-1" role="dialog" aria-labelledby="' . $modal . '-label" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="' . $modal . '-abel">' . $value["event"] . '</h5>
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
            $wpc_html["alert_button"] .= '</div>';
	    }

    	$wpc_html["temperature_unit"] = temperatureUnitSymbol($wpc_html["weather_id"], $wpc_opt["display_temperature_unit"], $wpc_opt["temperature_unit"], $wpc_opt["iconpack"]);

	    if ($wpc_opt["owm_link"] == 'yes') {
	    	$wpc_html["owm_link"] .= '<div class="wpc-link-owm">'.$wpc_data["owm_link"].'</div>';
	    }
	    if ($wpc_opt["last_update"] == 'yes') {
	    	$wpc_html["last_update"] .= '<div class="wpc-last-update">'.$wpc_data["last_update"].'</div>';
	    }

        //charts
        $wpc_html["chart"]["hourly"] = [];
        $wpc_html["chart"]["hourly"]["container"] = 'wpc-hourly-chart-canvas-'.$wpc_opt["id"];
        $wpc_html["chart"]["hourly"]["labels"] = '';
        $wpc_html["chart"]["hourly"]["dataset_temperature"] = '';
        $wpc_html["chart"]["hourly"]["dataset_feels_like"] = '';
        $wpc_html["chart"]["hourly"]["dataset_dew_point"] = '';
        $wpc_html["chart"]["hourly"]["config"] = '';
        $wpc_html["chart"]["hourly"]["data"] = '';
        $wpc_html["chart"]["hourly"]["options"] = '';
        $wpc_html["chart"]["hourly"]["chart"] = '';
        $wpc_html["chart"]["hourly"]["cmd"] = '';

	    if ($wpc_opt["hours_forecast_no"] > 0) {
            $wpc_html["chart"]["hourly"]["labels"] .= 'const hourly_labels_'.$wpc_opt["id"].' = [';
            $wpc_html["chart"]["hourly"]["dataset_temperature"] .= 'const hourly_temperature_dataset_'.$wpc_opt["id"].' = [';
            $wpc_html["chart"]["hourly"]["dataset_feels_like"] .= 'const hourly_feels_like_dataset_'.$wpc_opt["id"].' = [';
            $wpc_html["chart"]["hourly"]["dataset_dew_point"] .= 'const hourly_dew_point_dataset_'.$wpc_opt["id"].' = [';
			foreach ($wpc_data["hourly"] as $i => $value) {
                $wpc_html["chart"]["hourly"]["labels"] .= '"' . $value["time"] . '",';
                $wpc_html["chart"]["hourly"]["dataset_temperature"] .= '"' . $value["temperature"] . '",';
                $wpc_html["chart"]["hourly"]["dataset_feels_like"] .= '"' . $value["feels_like"] . '",';
                $wpc_html["chart"]["hourly"]["dataset_dew_point"] .= '"' . $value["dew_point"] . '",';
			}
            $wpc_html["chart"]["hourly"]["labels"] .= '];';
            $wpc_html["chart"]["hourly"]["dataset_temperature"] .= '];';
            $wpc_html["chart"]["hourly"]["dataset_feels_like"] .= '];';
            $wpc_html["chart"]["hourly"]["dataset_dew_point"] .= '];';

            $wpc_html["chart"]["hourly"]["config"] .= 'const hourly_config_'.$wpc_opt["id"].' = { type: "line", options: hourly_options_'.$wpc_opt["id"].', data: hourly_data_'.$wpc_opt["id"].',};';
            $wpc_html["chart"]["hourly"]["options"] .= 'const hourly_options_'.$wpc_opt["id"].' = {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: { intersect: false, mode: "index" },
                    plugins: {title: {display: true, text: "Hourly Temperatures" },
                        tooltip: { callbacks: { label: function(context) { var label = context.dataset.label || ""; if (label) { label += ": "; }
if (context.parsed.y !== null) { label += context.parsed.y + " '.$temperature_unit_character.'"; }
return label; } } } },
                    scales: { y: { title: { display: true, text: "'.$temperature_unit_text.'" } } }
                    };';
            $wpc_html["chart"]["hourly"]["data"] .= 'const hourly_data_'.$wpc_opt["id"].' = {
                                                                      labels: hourly_labels_'.$wpc_opt["id"].',
                                                                      datasets: [{
                                                                        label: "Temperature",
                                                                        data: hourly_temperature_dataset_'.$wpc_opt["id"].',
                                                                        fill: false,
                                                                        borderColor: "'.$wpc_opt["chart_temperature_color"].'",
                                                                        borderWidth: 1,
                                                                        tension: 0.3,
                                                                        pointBorderWidth: 0,
                                                                        },{
                                                                        label: "Feels Like",
                                                                        data: hourly_feels_like_dataset_'.$wpc_opt["id"].',
                                                                        fill: false,
                                                                        borderColor: "'.$wpc_opt["chart_feels_like_color"].'",
                                                                        borderWidth: 1,
                                                                        tension: 0.3,
                                                                        pointBorderWidth: 0,
                                                                        },{
                                                                        label: "Dew Point",
                                                                        data: hourly_dew_point_dataset_'.$wpc_opt["id"].',
                                                                        fill: false,
                                                                        borderColor: "'.$wpc_opt["chart_dew_point_color"].'",
                                                                        borderWidth: 1,
                                                                        tension: 0.3,
                                                                        pointBorderWidth: 0,
                                                                      }]
                                                                    };';
            $wpc_html["chart"]["hourly"]["chart"] .= 'jQuery(document).ready(function(){
                                                        var ctx = jQuery("#'.$wpc_html["chart"]["hourly"]["container"].'");
                                                        var hourlyChart = new Chart(ctx, hourly_config_'.$wpc_opt["id"].');
                                                        });';

$wpc_html["chart"]["hourly"]["cmd"] =
        '<div class="chart-container" style="position: relative; height:'.$wpc_opt["chart_height"].'px; width:100%">
        <canvas id="'.$wpc_html["chart"]["hourly"]["container"].'" style="'.wpc_css_background_color($wpc_opt["chart_background_color"]).'" aria-label="Hourly Temperatures" role="img"></canvas></div><script>' .
        $wpc_html["chart"]["hourly"]["labels"] .
        $wpc_html["chart"]["hourly"]["dataset_temperature"] .
        $wpc_html["chart"]["hourly"]["dataset_feels_like"] .
        $wpc_html["chart"]["hourly"]["dataset_dew_point"] .
        $wpc_html["chart"]["hourly"]["data"] .
        $wpc_html["chart"]["hourly"]["options"] .
        $wpc_html["chart"]["hourly"]["config"] .
        $wpc_html["chart"]["hourly"]["chart"] .
        '</script>';
		}


	    $wpc_html["container"]["end"] .= '</div>';
	    deleteWhitespaces($wpc_html);

        // backward compatibility
		$wpcloudy_forecast       				= $wpc_opt["days_forecast_no"] > 0 ? 'yes' : null;
		$wpcloudy_forecast_nd     				= $wpc_opt["days_forecast_no"];
        $wpcloudy_hour_forecast                 = $wpc_opt["hours_forecast_no"] > 0 ? 'yes' : null;
        $wpcloudy_hour_forecast_nd              = $wpc_opt["hours_forecast_no"];
        $wpc_html_container_start               = $wpc_html["container"]["start"];
        $wpc_html_container_end                 = $wpc_html["container"]["end"];
        $wpc_html_now_start                     = $wpc_html["now"]["start"];
        $wpc_html_now_location_name             = $wpc_html["now"]["location_name"];
        $wpc_html_display_now_time_symbol       = $wpc_html["now"]["time_symbol"];
        $wpc_html_display_now_time_temperature  = $wpc_html["now"]["time_temperature"];
        $wpc_html_weather                       = $wpc_html["now"]["weather_description"];
        $wpc_html_now_end                       = $wpc_html["now"]["end"];
        $wpc_html_today_temp_start              = $wpc_html["today"]["start"];
        $wpc_html_today_temp_day                = $wpc_html["today"]["day"];
        $wpc_html_today_sun                     = $wpc_html["today"]["sun"];
        $wpc_html_today_temp_end                = $wpc_html["today"]["end"];
        $wpc_html_infos_start                   = $wpc_html["info"]["start"];
        $wpc_html_infos_wind                    = $wpc_html["info"]["wind"];
        $wpc_html_infos_humidity                = $wpc_html["info"]["humidity"];
        $wpc_html_infos_pressure                = $wpc_html["info"]["pressure"];
        $wpc_html_infos_cloudiness              = $wpc_html["info"]["cloudiness"];
        $wpc_html_infos_precipitation           = $wpc_html["info"]["precipitation"];
        $wpc_html_infos_end                     = $wpc_html["info"]["end"];
        $wpc_html_hour_start                    = $wpc_html["hour"]["start"];
        $wpc_html_hour                          = $wpc_html["hour"]["info"];
        $wpc_html_hour_end                      = $wpc_html["hour"]["end"];
        $wpc_html_forecast_start                = $wpc_html["forecast"]["start"];
        $wpc_html_forecast                      = $wpc_html["forecast"]["info"];
        $wpc_html_forecast_end                  = $wpc_html["forecast"]["end"];
        $wpc_html_map                           = $wpc_html["map"];
        $wpc_html_owm_link                      = $wpc_html["owm_link"];
        $wpc_html_last_update                   = $wpc_html["last_update"];
        $wpc_html_custom_css                    = $wpc_html["custom_css"];
        $wpc_html_css3_anims                    = '';
        $wpc_html_temp_unit_metric              = $wpc_html["temperature_unit"];
        $wpc_html_temp_unit_imperial            = '';

        if ($wpc_opt["template"] == "debug") {
            $wpc_sys_opt = get_option('wpc_option_name');
        }

	    if ( locate_template('wp-cloudy-2/content-wpcloudy.php', false) != '' && $wpc_opt["template"] == 'Default' ) {
	    	ob_start();
	    	include get_stylesheet_directory() . '/wp-cloudy-2/content-wpcloudy.php';
	    	$wpc_html["html"] = ob_get_clean();
	    } elseif ( $wpc_opt["template"] != 'Default' ) {
	    	ob_start();
	    	if ( locate_template('wp-cloudy-2/content-wpcloudy-' . $wpc_opt["template"] . '.php', false) != '' ) {
		    	include get_stylesheet_directory() . '/wp-cloudy-2/content-wpcloudy-' . $wpc_opt["template"] . '.php';
		    	$wpc_html["html"] = ob_get_clean();
	    	} else {
	    		include dirname( __FILE__ ) . '/template/content-wpcloudy-' . $wpc_opt["template"] . '.php';
		    	$wpc_html["html"] = ob_get_clean();
	    	}
	    } else { //Default
	    	ob_start();
	    	include ( dirname( __FILE__ ) . '/template/content-wpcloudy.php');
	    	$wpc_html["html"] = ob_get_clean();
	    }

	  	$response = array();
	  	$response['weather'] = $wpc_opt["id"];
	  	$response['html'] = $wpc_html["html"];
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

add_filter('manage_edit-wpc-weather_columns', 'wpc_set_custom_edit_wpc_weather_columns');
add_action('manage_wpc-weather_posts_custom_column', 'wpc_custom_wpc_weather_column', 10, 2);

function wpc_set_custom_edit_wpc_weather_columns($columns) {
    $columns['wpc-weather'] = __('Shortcode');
    return $columns;
}

function wpc_custom_wpc_weather_column($column, $post_id) {

    $wpc_weather_meta = get_post_meta($post_id, "_wpc-weather_meta", true);
    $wpc_weather_meta = ($wpc_weather_meta != '') ? json_decode($wpc_weather_meta) : array();

    switch ($column) {
        case 'wpc-weather':
            echo "[wpc-weather id=\"$post_id\" /]";
            break;
    }
}

///////////////////////////////////////////////////////////////////////////////////////////////////
//Weather Custom Post Type
///////////////////////////////////////////////////////////////////////////////////////////////////

// Register Custom Post Type
function wpcloudy_weather() {
	$labels = array(
		'name'                => _x( 'Weather', 'Post Type General Name', 'wp-cloudy' ),
		'singular_name'       => _x( 'Weather', 'Post Type Singular Name', 'wp-cloudy' ),
		'menu_name'           => __( 'Weather', 'wp-cloudy' ),
		'parent_item_colon'   => __( 'Parent Weather:', 'wp-cloudy' ),
		'all_items'           => __( 'All Weather', 'wp-cloudy' ),
		'view_item'           => __( 'View Weather', 'wp-cloudy' ),
		'add_new_item'        => __( 'Add New Weather', 'wp-cloudy' ),
		'add_new'             => __( 'New Weather', 'wp-cloudy' ),
		'edit_item'           => __( 'Edit Weather', 'wp-cloudy' ),
		'update_item'         => __( 'Update Weather', 'wp-cloudy' ),
		'search_items'        => __( 'Search Weather', 'wp-cloudy' ),
		'not_found'           => __( 'No weather found', 'wp-cloudy' ),
		'not_found_in_trash'  => __( 'No weather found in Trash', 'wp-cloudy' ),
	);

	$args = array(
		'label'               => __( 'weather', 'wp-cloudy' ),
		'description'         => __( 'Listing weather', 'wp-cloudy' ),
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

	register_post_type( 'wpc-weather', $args );
}

// Hook into the 'init' action
add_action( 'init', 'wpcloudy_weather', 0 );

///////////////////////////////////////////////////////////////////////////////////////////////////
//Weather Custom Post Type Messages
///////////////////////////////////////////////////////////////////////////////////////////////////

function wpc_set_messages($messages) {
	global $post, $post_ID;
	$post_type = 'wpc-weather';

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
		9 => sprintf( __($singular.' scheduled for: <strong>%1$s</strong>. '), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
		10 => sprintf( __($singular.' draft updated.'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
	);
	return $messages;
}

add_filter('post_updated_messages', 'wpc_set_messages' );

///////////////////////////////////////////////////////////////////////////////////////////////////
//WPC Notices
///////////////////////////////////////////////////////////////////////////////////////////////////
function wpcloudy_notice() {
	$wpc_advanced_api_key = get_option('wpc_option_name');
	if ( is_plugin_active( 'wp-cloudy-2/wpcloudy.php' ) && !isset($wpc_advanced_api_key['wpc_advanced_api_key'])) {
	    ?>
	    <div class="error notice">
	        <p><a href="<?php echo admin_url('admin.php?page=wpc-settings-admin#tab_advanced'); ?>"><?php _e( 'WP Cloudy 2: Please enter your own OpenWeatherMap API key to avoid limits requests.', 'wp-cloudy' ); ?></a></p>
	    </div>
	    <?php
	}
}
add_action( 'admin_notices', 'wpcloudy_notice' );

///////////////////////////////////////////////////////////////////////////////////////////////////
//Utility functions
///////////////////////////////////////////////////////////////////////////////////////////////////
function generate_hour_options($current) {
	$str = '<option ' . selected( 0, intval($current), false ) . ' value="0">' . __( "None", 'wp-cloudy' ) . '</option>';

    for ($i=1; $i<=48; $i++) {
        if ($i == 1) {
            $h = 'Now';
        } else if ($i == 2) {
            $h = 'Now + 1 hour';
        } else {
            $h = 'Now + ' . ($i-1) . ' hours';
        }
		$str .= '<option ' . selected( $i, intval($current), false ) . ' value="' . $i . '">' . __( $h, 'wp-cloudy' ) . '</option>';
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

function convertPrecipitation($unit, $p) {
    if ($unit == 'imperial') {
        return number_format($p / 25.4, 1);
    }

    return number_format($p,0);
}

function converthp2iom($unit, $p) {
    if ($unit == 'imperial') {
        return number_format($p / 33.86389, 2);
    }

    return number_format($p,0);
}

function getColor($id, $field, $default) {
	$color = get_post_meta($id, $field, true);
	return !empty($color) ? $color : $default;
}



function deleteWhitespaces(&$arr) {
    if ($arr) {
        foreach ($arr as &$value) {
            if (is_array($value)) {
                deleteWhitespaces($value);
            } else {
                //  Output
                $value = preg_replace('/\s+/', ' ', $value);
            }
        }
    }
}

// wp-cloudy settings conversion
function convertSettings($id) {
    $wpc_version = get_post_meta($id,'_wpcloudy_version',true);
    if (empty($wpc_version)) {
        $old_hour_forecast = get_post_meta($id,'_wpcloudy_hour_forecast',true);
        $old_hour_forecast_nd = get_post_meta($id,'_wpcloudy_hour_forecast_nd',true);
        if ($old_hour_forecast == 'yes') {
            update_post_meta($id, '_wpcloudy_hours_forecast_no', $old_hour_forecast_nd);
        } else {
            update_post_meta($id, '_wpcloudy_hours_forecast_no', '0');
        }
        delete_post_meta($id, '_wpcloudy_hour_forecast', '');

        $old_forecast = get_post_meta($id,'_wpcloudy_forecast',true);
        $old_forecast_nd = get_post_meta($id,'_wpcloudy_forecast_nd',true);
        if ($old_forecast == 'yes') {
            update_post_meta($id, '_wpcloudy_forecast_no', $old_forecast_nd);
        } else {
            update_post_meta($id, '_wpcloudy_forecast_no', '0');
        }
        delete_post_meta($id, '_wpcloudy_forecast', '');

        $old_days_forecast = get_post_meta($id,'_wpcloudy_current_weather',true);
        if ($old_days_forecast == 'yes') {
            update_post_meta($id, '_wpcloudy_current_weather_symbol', 'yes');
        }
        delete_post_meta($id, '_wpcloudy_current_weather', '');

        $old_days_forecast = get_post_meta($id,'_wpcloudy_temperature_min_max',true);
        if ($old_days_forecast == 'yes') {
            update_post_meta($id, '_wpcloudy_current_temperature', 'yes');
        }
        delete_post_meta($id, '_wpcloudy_temperature_min_max', '');

        $old_meta_bg_color = get_post_meta($id,'_wpcloudy_meta_bg_color',true);
        if (!empty($old_meta_bg_color)) {
            update_post_meta($id, '_wpcloudy_background_color', $old_meta_bg_color);
        }
        delete_post_meta($id, '_wpcloudy_meta_bg_color', '');

        $old_meta_txt_color = get_post_meta($id,'_wpcloudy_meta_txt_color',true);
        if (!empty($old_meta_txt_color)) {
            update_post_meta($id, '_wpcloudy_text_color', $old_meta_txt_color);
        }
        delete_post_meta($id, '_wpcloudy_meta_txt_color', '');

        $old_meta_border_color = get_post_meta($id,'_wpcloudy_meta_border_color',true);
        if (!empty($old_meta_border_color)) {
            update_post_meta($id, '_wpcloudy_border_color', $old_meta_border_color);
        }
        delete_post_meta($id, '_wpcloudy_meta_border_color', '');

        $old_date_temp = get_post_meta($id,'_wpcloudy_date_temp',true);
        if (!empty($old_date_temp)) {
            if ($old_date_temp == 'none') {
                update_post_meta($id, '_wpcloudy_today_date_format', 'none');
            } else if ($old_date_temp == 'yes') {
                update_post_meta($id, '_wpcloudy_today_date_format', 'day');
            } else if ($old_date_temp == 'no') {
                update_post_meta($id, '_wpcloudy_today_date_format', 'date');
            }
        } else {
            update_post_meta($id, '_wpcloudy_today_date_format', 'none');
        }
        delete_post_meta($id, '_wpcloudy_date_temp', '');

        $old_weather = get_post_meta($id,'_wpcloudy_weather',true);
        if (!empty($old_weather)) {
            update_post_meta($id, '_wpcloudy_current_weather_description', $old_weather);
        }
        delete_post_meta($id, '_wpcloudy_weather', '');

        $old_date_format = get_post_meta($id,'_wpcloudy_date_format',true);
        if (!empty($old_date_format)) {
            if ($old_date_format == 'yes') {
                update_post_meta($id, '_wpcloudy_time_format', 'short');
            } else {
                update_post_meta($id, '_wpcloudy_time_format', 'normal');
            }
        }
        delete_post_meta($id, '_wpcloudy_date_format', '');

        $old_map_zoom_wheel = get_post_meta($id,'_wpcloudy_map_zoom_wheel',true);
        if (!empty($old_map_zoom_wheel)) {
            update_post_meta($id, '_wpcloudy_map_disable_zoom_wheel', $old_map_zoom_wheel);
        }
        delete_post_meta($id, '_wpcloudy_map_zoom_wheel', '');

        $old_display_temp_unit = get_post_meta($id,'_wpcloudy_display_temp_unit',true);
        if (!empty($old_display_temp_unit)) {
            update_post_meta($id, '_wpcloudy_display_temperature_unit', $old_display_temp_unit);
        }
        delete_post_meta($id, '_wpcloudy_display_temp_unit', '');

        $old_city_name = get_post_meta($id,'_wpcloudy_city_name',true);
        if (!empty($old_city_name)) {
            update_post_meta($id, '_wpcloudy_custom_city_name', $old_city_name);
        }
        delete_post_meta($id, '_wpcloudy_city_name', '');

        update_post_meta($id, '_wpcloudy_current_city_name', 'yes');
        update_post_meta($id, '_wpcloudy_font', 'Default');
        update_post_meta($id, '_wpcloudy_template', 'Default');
        update_post_meta($id, '_wpcloudy_iconpack', 'Default');
        update_post_meta($id, '_wpcloudy_owm_language', 'Default');

        $old_date_timezone_bypass = get_post_meta($id,'_wpcloudy_date_timezone_bypass',true);
        $old_date_timezone = get_post_meta($id,'_wpcloudy_date_timezone',true);
        if (!empty($old_date_timezone_bypass)) {
            update_post_meta($id, '_wpcloudy_custom_timezone', $old_date_timezone);
        } else {
            update_post_meta($id, '_wpcloudy_custom_timezone', 'Default');
        }
        delete_post_meta($id,'_wpcloudy_date_timezone_bypass');

        update_post_meta($id, '_wpcloudy_version', WPCLOUDY_VERSION);
    }
}