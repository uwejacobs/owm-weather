<?php
/*
Plugin Name: WP Cloudy
Plugin URI: https://wpcloudy.com/
Description: WP Cloudy is a powerful weather plugin for WordPress, based on Open Weather Map API, using Custom Post Types and shortcodes, bundled with a ton of features.
Version: 4.4.7
Author: Benjamin DENIS
Author URI: https://wpcloudy.com/
License: GPLv2
Text Domain: wp-cloudy
Domain Path: /lang
*/

/*  Copyright 2013 - 2018  Benjamin DENIS  (email : contact@wpcloudy.com)

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
}
register_activation_hook(__FILE__, 'weather_activation');
function weather_deactivation() {
}
register_deactivation_hook(__FILE__, 'weather_deactivation');

define( 'WPCLOUDY_VERSION', '4.4.7' );

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
// 		plugins_url( 'dist/blocks.style.build.css', dirname( __FILE__ ) ),
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
	$wpcloudy_city 						= get_post_meta($post->ID,'_wpcloudy_city',true);
	$wpcloudy_city_name					= get_post_meta($post->ID,'_wpcloudy_city_name',true);
	$wpcloudy_id_owm					= get_post_meta($post->ID,'_wpcloudy_id_owm',true);
	$wpcloudy_country_code 				= get_post_meta($post->ID,'_wpcloudy_country_code',true);
	$wpcloudy_unit 						= get_post_meta($post->ID,'_wpcloudy_unit',true);
	$wpcloudy_date_format				= get_post_meta($post->ID,'_wpcloudy_date_format',true);
	$wpcloudy_date_timezone_bypass		= get_post_meta($post->ID,'_wpcloudy_date_timezone_bypass',true);
	$wpcloudy_date_timezone				= get_post_meta($post->ID,'_wpcloudy_date_timezone',true);
	$wpcloudy_current_weather			= get_post_meta($post->ID,'_wpcloudy_current_weather',true);
	$wpcloudy_date_temp					= get_post_meta($post->ID,'_wpcloudy_date_temp',true);
	$wpcloudy_weather					= get_post_meta($post->ID,'_wpcloudy_weather',true);
	$wpcloudy_sunrise_sunset 			= get_post_meta($post->ID,'_wpcloudy_sunrise_sunset',true);
	$wpcloudy_wind 						= get_post_meta($post->ID,'_wpcloudy_wind',true);
	$wpcloudy_wind_unit 				= get_post_meta($post->ID,'_wpcloudy_wind_unit',true);
	$wpcloudy_humidity 					= get_post_meta($post->ID,'_wpcloudy_humidity',true);
	$wpcloudy_pressure					= get_post_meta($post->ID,'_wpcloudy_pressure',true);
	$wpcloudy_cloudiness				= get_post_meta($post->ID,'_wpcloudy_cloudiness',true);
	$wpcloudy_precipitation				= get_post_meta($post->ID,'_wpcloudy_precipitation',true);
	$wpcloudy_hour_forecast				= get_post_meta($post->ID,'_wpcloudy_hour_forecast',true);
	$wpcloudy_hour_forecast_nd			= get_post_meta($post->ID,'_wpcloudy_hour_forecast_nd',true);
	$wpcloudy_temperature_min_max		= get_post_meta($post->ID,'_wpcloudy_temperature_min_max',true);
	$wpcloudy_display_temp_unit			= get_post_meta($post->ID,'_wpcloudy_display_temp_unit',true);
	$wpcloudy_forecast					= get_post_meta($post->ID,'_wpcloudy_forecast',true);
	$wpcloudy_forecast_nd				= get_post_meta($post->ID,'_wpcloudy_forecast_nd',true);
	$wpcloudy_forecast_precipitations   = get_post_meta($post->ID,'_wpcloudy_forecast_precipitations',true);
	$wpcloudy_short_days_names			= get_post_meta($post->ID,'_wpcloudy_short_days_names',true);
	$wpcloudy_disable_anims				= get_post_meta($post->ID,'_wpcloudy_disable_anims',true);
	$wpcloudy_meta_bg_color				= get_post_meta($post->ID,'_wpcloudy_meta_bg_color',true);
	$wpcloudy_meta_txt_color			= get_post_meta($post->ID,'_wpcloudy_meta_txt_color',true);
	$wpcloudy_meta_border_color			= get_post_meta($post->ID,'_wpcloudy_meta_border_color',true);
	$wpcloudy_custom_css				= get_post_meta($post->ID,'_wpcloudy_custom_css',true);
	$wpcloudy_size 						= get_post_meta($post->ID,'_wpcloudy_size',true);
	$wpcloudy_owm_link					= get_post_meta($post->ID,'_wpcloudy_owm_link',true);
	$wpcloudy_last_update				= get_post_meta($post->ID,'_wpcloudy_last_update',true);
	$wpcloudy_fluid						= get_post_meta($post->ID,'_wpcloudy_fluid',true);
	$wpcloudy_fluid_width				= get_post_meta($post->ID,'_wpcloudy_fluid_width',true);
	$wpcloudy_map 						= get_post_meta($post->ID,'_wpcloudy_map',true);
	$wpcloudy_map_height				= get_post_meta($post->ID,'_wpcloudy_map_height',true);
	$wpcloudy_map_opacity				= get_post_meta($post->ID,'_wpcloudy_map_opacity',true);
	$wpcloudy_map_zoom					= get_post_meta($post->ID,'_wpcloudy_map_zoom',true);
	$wpcloudy_map_zoom_wheel			= get_post_meta($post->ID,'_wpcloudy_map_zoom_wheel',true);
	$wpcloudy_map_stations				= get_post_meta($post->ID,'_wpcloudy_map_stations',true);
	$wpcloudy_map_clouds				= get_post_meta($post->ID,'_wpcloudy_map_clouds',true);
	$wpcloudy_map_precipitation			= get_post_meta($post->ID,'_wpcloudy_map_precipitation',true);
	$wpcloudy_map_snow					= get_post_meta($post->ID,'_wpcloudy_map_snow',true);
	$wpcloudy_map_wind					= get_post_meta($post->ID,'_wpcloudy_map_wind',true);
	$wpcloudy_map_temperature			= get_post_meta($post->ID,'_wpcloudy_map_temperature',true);
	$wpcloudy_map_pressure				= get_post_meta($post->ID,'_wpcloudy_map_pressure',true);

	function wpc_get_admin_api_key2() {
		$wpc_admin_api_key_option = get_option("wpc_option_name");
		if ( ! empty ( $wpc_admin_api_key_option ) ) {
			foreach ($wpc_admin_api_key_option as $key => $wpc_admin_api_key_value)
				$options[$key] = $wpc_admin_api_key_value;
			if (isset($wpc_admin_api_key_option['wpc_advanced_api_key'])) {
				return $wpc_admin_api_key_option['wpc_advanced_api_key'];
			}
		} else {
			return '46c433f6ba7dd4d29d5718dac3d7f035';
		}
	};
	  
	echo '<div id="wpcloudy-tabs">
			<ul>
				<li><a href="#tabs-1">'. __( 'Basic settings', 'wp-cloudy' ) .'</a></li>
				<li><a href="#tabs-2">'. __( 'Display', 'wp-cloudy' ) .'</a></li>
				<li><a href="#tabs-3">'. __( 'Advanced', 'wp-cloudy' ) .'</a></li>
				<li><a href="#tabs-4">'. __( 'Map', 'wp-cloudy' ) .'</a></li>
			</ul>
			
			<div id="tabs-1">
				<p>
					<label for="wpcloudy_id_owm_meta">'. __( 'OpenWeatherMap city ID', 'wp-cloudy' ) .'<span class="mandatory">*</span> <a href="http://openweathermap.org/find?q=" target="_blank"> '.__('Find my city ID','wp-cloudy').'</a><span class="dashicons dashicons-external"></span></label>
					<input id="wpcloudy_id_owm" type="text" name="wpcloudy_id_owm" value="'.$wpcloudy_id_owm.'" />
				</p>
				~<strong>'.__('OR','wp-cloudy').'~</strong>
				<p>
					<label for="wpcloudy_city_meta">'. __( 'City', 'wp-cloudy' ) .' <span class="mandatory">*</span></label>
					<input id="wpcloudy_city_meta" data_appid="'.wpc_get_admin_api_key2().'" class="cities typeahead" type="text" name="wpcloudy_city" placeholder="'.__('Enter your city','wp-cloudy').'" value="'.$wpcloudy_city.'" />
				</p>
				<p>
					<label for="wpcloudy_country_meta">'. __( 'Country?', 'wp-cloudy' ) .' <span class="mandatory">*</span></label>
					<input id="wpcloudy_country_meta" class="countries typeahead" type="text" name="wpcloudy_country_code" value="'.$wpcloudy_country_code.'" />
				</p>
				<p><em>'.__('If you enter an OpenWeatherMap ID, it will automatically bypassed City and Country fields.','wp-cloudy').'</em></p>
				<hr>
				<p>
					<label for="wpcloudy_city_name_meta">'. __( 'Custom city title', 'wp-cloudy' ) .'</label>
					<input id="wpcloudy_city_name_meta" type="text" name="wpcloudy_city_name" value="'.$wpcloudy_city_name.'" />
				</p>
				<p>
					<label for="unit_meta">'. __( 'Imperial or metric units?', 'wp-cloudy' ) .'</label>
					<select name="wpcloudy_unit">
						<option ' . selected( 'imperial', $wpcloudy_unit, false ) . ' value="imperial">'. __( 'Imperial', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'metric', $wpcloudy_unit, false ) . ' value="metric">'. __( 'Metric', 'wp-cloudy' ) .'</option>
					</select>
				</p>
				<p>
					<label for="wpcloudy_date_format_meta">'. __( '12h / 24h date format?', 'wp-cloudy' ) .'</label>
					<select name="wpcloudy_date_format">
						<option ' . selected( '12', $wpcloudy_date_format, false ) . ' value="12">'. __( '12 h', 'wp-cloudy' ) .'</option>
						<option ' . selected( '24', $wpcloudy_date_format, false ) . ' value="24">'. __( '24 h', 'wp-cloudy' ) .'</option>
					</select>
				</p>				
				<p>				
					<label for="wpcloudy_date_timezone_bypass_meta">
						<input type="checkbox" name="wpcloudy_date_timezone_bypass" id="wpcloudy_date_timezone_bypass_meta" value="yes" '. checked( $wpcloudy_date_timezone_bypass, 'yes', false ) .' />
							'. __( 'Bypass default WordPress timezone setting?', 'wp-cloudy' ) .'
					</label>			
				</p>
				<p>
					<label for="wpcloudy_date_timezone_meta">'. __( 'Custom timezone? (default: WordPress general settings)', 'wp-cloudy' ) .'</label>
					<select name="wpcloudy_date_timezone">
						<option ' . selected( '-12', $wpcloudy_date_timezone, false ) . ' value="-12">'. __( 'UTC -12', 'wp-cloudy' ) .'</option>
						<option ' . selected( '-11', $wpcloudy_date_timezone, false ) . ' value="-11">'. __( 'UTC -11', 'wp-cloudy' ) .'</option>
						<option ' . selected( '-10', $wpcloudy_date_timezone, false ) . ' value="-10">'. __( 'UTC -10', 'wp-cloudy' ) .'</option>
						<option ' . selected( '-9', $wpcloudy_date_timezone, false ) . ' value="-9">'. __( 'UTC -9', 'wp-cloudy' ) .'</option>
						<option ' . selected( '-8', $wpcloudy_date_timezone, false ) . ' value="-8">'. __( 'UTC -8', 'wp-cloudy' ) .'</option>
						<option ' . selected( '-7', $wpcloudy_date_timezone, false ) . ' value="-7">'. __( 'UTC -7', 'wp-cloudy' ) .'</option>
						<option ' . selected( '-6', $wpcloudy_date_timezone, false ) . ' value="-6">'. __( 'UTC -6', 'wp-cloudy' ) .'</option>
						<option ' . selected( '-5', $wpcloudy_date_timezone, false ) . ' value="-5">'. __( 'UTC -5', 'wp-cloudy' ) .'</option>
						<option ' . selected( '-4', $wpcloudy_date_timezone, false ) . ' value="-4">'. __( 'UTC -4', 'wp-cloudy' ) .'</option>
						<option ' . selected( '-3', $wpcloudy_date_timezone, false ) . ' value="-3">'. __( 'UTC -3', 'wp-cloudy' ) .'</option>
						<option ' . selected( '-2', $wpcloudy_date_timezone, false ) . ' value="-2">'. __( 'UTC -2', 'wp-cloudy' ) .'</option>
						<option ' . selected( '-1', $wpcloudy_date_timezone, false ) . ' value="-1">'. __( 'UTC -1', 'wp-cloudy' ) .'</option>
						<option ' . selected( '0', $wpcloudy_date_timezone, false ) . ' value="0">'. __( 'UTC 0', 'wp-cloudy' ) .'</option>
						<option ' . selected( '1', $wpcloudy_date_timezone, false ) . ' value="1">'. __( 'UTC +1', 'wp-cloudy' ) .'</option>
						<option ' . selected( '2', $wpcloudy_date_timezone, false ) . ' value="2">'. __( 'UTC +2', 'wp-cloudy' ) .'</option>
						<option ' . selected( '3', $wpcloudy_date_timezone, false ) . ' value="3">'. __( 'UTC +3', 'wp-cloudy' ) .'</option>
						<option ' . selected( '4', $wpcloudy_date_timezone, false ) . ' value="4">'. __( 'UTC +4', 'wp-cloudy' ) .'</option>
						<option ' . selected( '5', $wpcloudy_date_timezone, false ) . ' value="5">'. __( 'UTC +5', 'wp-cloudy' ) .'</option>
						<option ' . selected( '6', $wpcloudy_date_timezone, false ) . ' value="6">'. __( 'UTC +6', 'wp-cloudy' ) .'</option>
						<option ' . selected( '7', $wpcloudy_date_timezone, false ) . ' value="7">'. __( 'UTC +7', 'wp-cloudy' ) .'</option>
						<option ' . selected( '8', $wpcloudy_date_timezone, false ) . ' value="8">'. __( 'UTC +8', 'wp-cloudy' ) .'</option>
						<option ' . selected( '9', $wpcloudy_date_timezone, false ) . ' value="9">'. __( 'UTC +9', 'wp-cloudy' ) .'</option>
						<option ' . selected( '10', $wpcloudy_date_timezone, false ) . ' value="10">'. __( 'UTC +10', 'wp-cloudy' ) .'</option>
						<option ' . selected( '11', $wpcloudy_date_timezone, false ) . ' value="11">'. __( 'UTC +11', 'wp-cloudy' ) .'</option>
						<option ' . selected( '12', $wpcloudy_date_timezone, false ) . ' value="12">'. __( 'UTC +12', 'wp-cloudy' ) .'</option>
					</select>
				</p>		
			</div>
			<div id="tabs-2">
				<p>				
					<label for="wpcloudy_current_weather_meta">
						<input type="checkbox" name="wpcloudy_current_weather" id="wpcloudy_current_weather_meta" value="yes" '. checked( $wpcloudy_current_weather, 'yes', false ) .' />
							'. __( 'Current weather?', 'wp-cloudy' ) .'
					</label>			
				</p>
				<p>				
					<label for="wpcloudy_weather_meta">
						<input type="checkbox" name="wpcloudy_weather" id="wpcloudy_weather_meta" value="yes" '. checked( $wpcloudy_weather, 'yes', false ) .' />
							'. __( 'Short condition?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p class="wpc-dates">
					'. __( 'Dates', 'wp-cloudy' ) .'
				</p>
				<p>
					<label for="wpcloudy_date_temp_none_meta">
						<input type="radio" name="wpcloudy_date_temp" id="wpcloudy_date_temp_none_meta" value="none" '. checked( $wpcloudy_date_temp, 'none', false ) .' />
							'. __( 'No date (default)?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_date_temp_week_meta">
						<input type="radio" name="wpcloudy_date_temp" id="wpcloudy_date_temp_week_meta" value="yes" '. checked( $wpcloudy_date_temp, 'yes', false ) .' />
							'. __( 'Day of the week (eg: Sunday)?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_date_temp_calendar_meta">
						<input type="radio" name="wpcloudy_date_temp" id="wpcloudy_date_temp_calendar_meta" value="no" '. checked( $wpcloudy_date_temp, 'no', false ) .' />
							'. __( 'Todays date (based on your WordPress General Settings)?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p class="wpc-misc">
					'. __( 'Misc', 'wp-cloudy' ) .'
				</p>
				<p>
					<label for="wpcloudy_sunrise_sunset_meta">
						<input type="checkbox" name="wpcloudy_sunrise_sunset" id="wpcloudy_sunrise_sunset_meta" value="yes" '. checked( $wpcloudy_sunrise_sunset, 'yes', false ) .' />
							'. __( 'Sunrise + sunset? appears only if today date is checked', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>				
					<label for="wpcloudy_wind_meta">
						<input type="checkbox" name="wpcloudy_wind" id="wpcloudy_wind_meta" value="yes" '. checked( $wpcloudy_wind, 'yes', false ) .' />
							'. __( 'Wind?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_wind_unit_meta">'. __( 'Wind unit: ', 'wp-cloudy' ) .'</label>
					<select name="wpcloudy_wind_unit">
						<option ' . selected( '1', $wpcloudy_wind_unit, false ) . ' value="1">'. __( 'mi/h', 'wp-cloudy' ) .'</option>
						<option ' . selected( '2', $wpcloudy_wind_unit, false ) . ' value="2">'. __( 'm/s', 'wp-cloudy' ) .'</option>
						<option ' . selected( '3', $wpcloudy_wind_unit, false ) . ' value="3">'. __( 'km/h', 'wp-cloudy' ) .'</option>
						<option ' . selected( '4', $wpcloudy_wind_unit, false ) . ' value="4">'. __( 'kt', 'wp-cloudy' ) .'</option>
					</select>
				</p>
				<p>
					<label for="wpcloudy_humidity_meta">
						<input type="checkbox" name="wpcloudy_humidity" id="wpcloudy_humidity_meta" value="yes" '. checked( $wpcloudy_humidity, 'yes', false ) .' />
							'. __( 'Humidity?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_pressure_meta">
						<input type="checkbox" name="wpcloudy_pressure" id="wpcloudy_pressure_meta" value="yes" '. checked( $wpcloudy_pressure, 'yes', false ) .' />
							'. __( 'Pressure?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_cloudiness_meta">
						<input type="checkbox" name="wpcloudy_cloudiness" id="wpcloudy_cloudiness_meta" value="yes" '. checked( $wpcloudy_cloudiness, 'yes', false ) .' />
							'. __( 'Cloudiness?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_precipitation_meta">
						<input type="checkbox" name="wpcloudy_precipitation" id="wpcloudy_precipitation_meta" value="yes" '. checked( $wpcloudy_precipitation, 'yes', false ) .' />
							'. __( 'Precipitation?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p class="temperatures">
					'. __( 'Temperatures', 'wp-cloudy' ) .'
				</p>
				<p>
					<label for="wpcloudy_display_temp_unit_meta">
						<input type="checkbox" name="wpcloudy_display_temp_unit" id="wpcloudy_display_temp_unit_meta" value="yes" '. checked( $wpcloudy_display_temp_unit, 'yes', false ) .' />
							'. __( 'Temperatures unit (C / F)?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_temperature_min_max_meta">
						<input type="checkbox" name="wpcloudy_temperature_min_max" id="wpcloudy_temperature_min_max_meta" value="yes" '. checked( $wpcloudy_temperature_min_max, 'yes', false ) .' />
							'. __( 'Today temperature?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p class="hour">
					'. __( 'Hourly Forecast', 'wp-cloudy' ) .'
				</p>
				<p>
					<label for="wpcloudy_hour_forecast_meta">
						<input type="checkbox" name="wpcloudy_hour_forecast" id="wpcloudy_hour_forecast_meta" value="yes" '. checked( $wpcloudy_hour_forecast, 'yes', false ) .' />
							'. __( 'Hour Forecast?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_hour_forecast_nd_meta">'. __( 'How hours ranges ?', 'wp-cloudy' ) .'</label>
					<select name="wpcloudy_hour_forecast_nd">
						<option ' . selected( '1', $wpcloudy_hour_forecast_nd, false ) . ' value="1">'. __( '1', 'wp-cloudy' ) .'</option>
						<option ' . selected( '2', $wpcloudy_hour_forecast_nd, false ) . ' value="2">'. __( '2', 'wp-cloudy' ) .'</option>
						<option ' . selected( '3', $wpcloudy_hour_forecast_nd, false ) . ' value="3">'. __( '3', 'wp-cloudy' ) .'</option>
						<option ' . selected( '4', $wpcloudy_hour_forecast_nd, false ) . ' value="4">'. __( '4', 'wp-cloudy' ) .'</option>
						<option ' . selected( '5', $wpcloudy_hour_forecast_nd, false ) . ' value="5">'. __( '5', 'wp-cloudy' ) .'</option>
						<option ' . selected( '6', $wpcloudy_hour_forecast_nd, false ) . ' value="6">'. __( '6', 'wp-cloudy' ) .'</option>
					</select>
					<br />
					<span class="dashicons dashicons-editor-help"></span><a href="'.admin_url('options-general.php').'" target="_blank">'.__('Make sure you have properly set the date of your site in WordPress settings.','wp-cloudy').'</a>
				</p>
				<p class="forecast">
					'. __( '5-Day Forecast', 'wp-cloudy' ) .'
				</p>
				<p>
					<label for="wpcloudy_forecast_meta">
						<input type="checkbox" name="wpcloudy_forecast" id="wpcloudy_forecast_meta" value="yes" '. checked( $wpcloudy_forecast, 'yes', false ) .' />
							'. __( '5-Day Forecast? (today + 5 days max)', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_forecast_nd_meta">'. __( 'How many days?', 'wp-cloudy' ) .'</label>
					<select name="wpcloudy_forecast_nd">
						<option ' . selected( '1', $wpcloudy_forecast_nd, false ) . ' value="1">'. __( '1 day', 'wp-cloudy' ) .'</option>
						<option ' . selected( '2', $wpcloudy_forecast_nd, false ) . ' value="2">'. __( '2 days', 'wp-cloudy' ) .'</option>
						<option ' . selected( '3', $wpcloudy_forecast_nd, false ) . ' value="3">'. __( '3 days', 'wp-cloudy' ) .'</option>
						<option ' . selected( '4', $wpcloudy_forecast_nd, false ) . ' value="4">'. __( '4 days', 'wp-cloudy' ) .'</option>
						<option ' . selected( '5', $wpcloudy_forecast_nd, false ) . ' value="5">'. __( '5 days', 'wp-cloudy' ) .'</option>
					</select>
				</p>
				<p>
					<label for="wpcloudy_forecast_precipitations_meta">
						<input type="checkbox" name="wpcloudy_forecast_precipitations" id="wpcloudy_forecast_precipitations_meta" value="yes" '. checked( $wpcloudy_forecast_precipitations, 'yes', false ) .' />
							'. __( 'Forecast Precipitations?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_short_days_names_yes_meta">
						<input type="radio" name="wpcloudy_short_days_names" id="wpcloudy_short_days_names_yes_meta" value="yes" '. checked( $wpcloudy_short_days_names, 'yes', false ) .' />
							'. __( 'Short days names?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_short_days_names_no_meta">
						<input type="radio" name="wpcloudy_short_days_names" id="wpcloudy_short_days_names_no_meta" value="no" '. checked( $wpcloudy_short_days_names, 'no', false ) .' />
							'. __( 'Normal days names?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_owm_link_meta">
						<input type="checkbox" name="wpcloudy_owm_link" id="wpcloudy_owm_link_meta" value="yes" '. checked( $wpcloudy_owm_link, 'yes', false ) .' />
						'. __( 'Link to OpenWeatherMap?', 'wp-cloudy' ) .'
					</label>
				</p>
				
				<p>
					<label for="wpcloudy_last_update_meta">
						<input type="checkbox" name="wpcloudy_last_update" id="wpcloudy_last_update_meta" value="yes" '. checked( $wpcloudy_last_update, 'yes', false ) .' />

						'. __( 'Update date?', 'wp-cloudy' ) .'
					</label>
				</p>	
				<p>
					<label for="wpcloudy_fluid_meta">
						<input type="checkbox" name="wpcloudy_fluid" id="wpcloudy_fluid_meta" value="yes" '. checked( $wpcloudy_fluid, 'yes', false ) .' />

						'. __( 'Fluid design? (useful with small container)', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_fluid_width_meta">'. __( 'Max width parent container to enable fluid design (pixels):', 'wp-cloudy' ) .'</label>
					<input id="wpcloudy_fluid_width_meta" name="wpcloudy_fluid_width" value="'.$wpcloudy_fluid_width.'" type="number" min="0" max="1000" />
				</p>	
			</div>
			<div id="tabs-3">
				<p>				
					<label for="wpcloudy_disable_anims_meta">
						<input type="checkbox" name="wpcloudy_disable_anims" id="wpcloudy_disable_anims_meta" value="yes" '. checked( $wpcloudy_disable_anims, 'yes', false ) .' />
							'. __( 'Disable CSS3 animations?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_meta_bg_color2">'. __( 'Background color', 'wp-cloudy' ) .'</label>
					<input name="wpcloudy_meta_bg_color" type="text" value="'. $wpcloudy_meta_bg_color .'" class="wpcloudy_meta_bg_color_picker" />
				</p>
				<p>
					<label for="wpcloudy_meta_txt_color2">'. __( 'Text color', 'wp-cloudy' ) .'</label>
					<input name="wpcloudy_meta_txt_color" type="text" value="'. $wpcloudy_meta_txt_color .'" class="wpcloudy_meta_txt_color_picker" />
				</p>
				<p>
					<label for="wpcloudy_meta_border_color2">'. __( 'Border color', 'wp-cloudy' ) .'</label>
					<input name="wpcloudy_meta_border_color" type="text" value="'. $wpcloudy_meta_border_color .'" class="wpcloudy_meta_border_color_picker" />
				</p>
				<p>
					<label for="wpcloudy_custom_css_meta">'. __( 'Custom CSS', 'wp-cloudy' ) .'</label>
					<textarea id="wpcloudy_custom_css_meta" name="wpcloudy_custom_css">'.$wpcloudy_custom_css.'</textarea>
				</p>
				<p>
					<label for="size_meta">'. __( 'Weather size?', 'wp-cloudy' ) .'</label>
					<select name="wpcloudy_size">
						<option ' . selected( 'small', $wpcloudy_size, false ) . ' value="small">'. __( 'Small', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'medium', $wpcloudy_size, false ) . ' value="medium">'. __( 'Medium', 'wp-cloudy' ) .'</option>
						<option ' . selected( 'large', $wpcloudy_size, false ) . ' value="large">'. __( 'Large', 'wp-cloudy' ) .'</option>
					</select>
				</p>
			</div>
			<div id="tabs-4">
				<p>				
					<label for="wpcloudy_map_meta">
						<input type="checkbox" name="wpcloudy_map" id="wpcloudy_map_meta" value="yes" '. checked( $wpcloudy_map, 'yes', false ) .' />
							'. __( 'Display map?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>
					<label for="wpcloudy_map_height_meta">'. __( 'Map height (in px)', 'wp-cloudy' ) .'</label>
					<input id="wpcloudy_map_height_meta" type="text" name="wpcloudy_map_height" value="'.$wpcloudy_map_height.'" />
				</p>
				<p>
					<label for="wpcloudy_map_opacity_meta">'. __( 'Layers opacity', 'wp-cloudy' ) .'</label>
					<select name="wpcloudy_map_opacity">
						<option ' . selected( '0', $wpcloudy_map_opacity, false ) . ' value="0">0%</option>
						<option ' . selected( '0.1', $wpcloudy_map_opacity, false ) . ' value="0.1">10%</option>
						<option ' . selected( '0.2', $wpcloudy_map_opacity, false ) . ' value="0.2">20%</option>
						<option ' . selected( '0.3', $wpcloudy_map_opacity, false ) . ' value="0.3">30%</option>
						<option ' . selected( '0.4', $wpcloudy_map_opacity, false ) . ' value="0.4">40%</option>
						<option ' . selected( '0.5', $wpcloudy_map_opacity, false ) . ' value="0.5">50%</option>
						<option ' . selected( '0.6', $wpcloudy_map_opacity, false ) . ' value="0.6">60%</option>
						<option ' . selected( '0.7', $wpcloudy_map_opacity, false ) . ' value="0.7">70%</option>
						<option ' . selected( '0.8', $wpcloudy_map_opacity, false ) . ' value="0.8">80%</option>
						<option ' . selected( '0.9', $wpcloudy_map_opacity, false ) . ' value="0.9">90%</option>
						<option ' . selected( '1', $wpcloudy_map_opacity, false ) . ' value="1">100%</option>
					</select>
				</p>
				<p>
					<label for="wpcloudy_map_zoom_meta">'. __( 'Zoom', 'wp-cloudy' ) .'</label>
					<select name="wpcloudy_map_zoom">
						<option ' . selected( '1', $wpcloudy_map_zoom, false ) . ' value="1">1</option>
						<option ' . selected( '2', $wpcloudy_map_zoom, false ) . ' value="2">2</option>
						<option ' . selected( '3', $wpcloudy_map_zoom, false ) . ' value="3">3</option>
						<option ' . selected( '4', $wpcloudy_map_zoom, false ) . ' value="4">4</option>
						<option ' . selected( '5', $wpcloudy_map_zoom, false ) . ' value="5">5</option>
						<option ' . selected( '6', $wpcloudy_map_zoom, false ) . ' value="6">6</option>
						<option ' . selected( '7', $wpcloudy_map_zoom, false ) . ' value="7">7</option>
						<option ' . selected( '8', $wpcloudy_map_zoom, false ) . ' value="8">8</option>
						<option ' . selected( '9', $wpcloudy_map_zoom, false ) . ' value="9">9</option>
						<option ' . selected( '10', $wpcloudy_map_zoom, false ) . ' value="10">10</option>
						<option ' . selected( '11', $wpcloudy_map_zoom, false ) . ' value="11">11</option>
						<option ' . selected( '12', $wpcloudy_map_zoom, false ) . ' value="12">12</option>
						<option ' . selected( '13', $wpcloudy_map_zoom, false ) . ' value="13">13</option>
						<option ' . selected( '14', $wpcloudy_map_zoom, false ) . ' value="14">14</option>
						<option ' . selected( '15', $wpcloudy_map_zoom, false ) . ' value="15">15</option>
						<option ' . selected( '16', $wpcloudy_map_zoom, false ) . ' value="16">16</option>
						<option ' . selected( '17', $wpcloudy_map_zoom, false ) . ' value="17">17</option>
						<option ' . selected( '18', $wpcloudy_map_zoom, false ) . ' value="18">18</option>
					</select>
				</p>
				<p>				
					<label for="wpcloudy_map_zoom_wheel_meta">
						<input type="checkbox" name="wpcloudy_map_zoom_wheel" id="wpcloudy_map_zoom_wheel_meta" value="yes" '. checked( $wpcloudy_map_zoom_wheel, 'yes', false ) .' />
							'. __( 'Disable zoom wheel on map?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p class="subsection-title">
					'. __( 'Layers', 'wp-cloudy' ) .'
				</p>
				<p>				
					<label for="wpcloudy_map_stations_meta">
						<input type="checkbox" name="wpcloudy_map_stations" id="wpcloudy_map_stations_meta" value="yes" '. checked( $wpcloudy_map_stations, 'yes', false ) .' />
							'. __( 'Display stations?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>				
					<label for="wpcloudy_map_clouds_meta">
						<input type="checkbox" name="wpcloudy_map_clouds" id="wpcloudy_map_clouds_meta" value="yes" '. checked( $wpcloudy_map_clouds, 'yes', false ) .' />
							'. __( 'Display clouds?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>				
					<label for="wpcloudy_map_precipitation_meta">
						<input type="checkbox" name="wpcloudy_map_precipitation" id="wpcloudy_map_precipitation_meta" value="yes" '. checked( $wpcloudy_map_precipitation, 'yes', false ) .' />
							'. __( 'Display precipitation?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>				
					<label for="wpcloudy_map_snow_meta">
						<input type="checkbox" name="wpcloudy_map_snow" id="wpcloudy_map_snow_meta" value="yes" '. checked( $wpcloudy_map_snow, 'yes', false ) .' />
							'. __( 'Display snow?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>				
					<label for="wpcloudy_map_wind_meta">
						<input type="checkbox" name="wpcloudy_map_wind" id="wpcloudy_map_wind_meta" value="yes" '. checked( $wpcloudy_map_wind, 'yes', false ) .' />
							'. __( 'Display wind?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>				
					<label for="wpcloudy_map_temperature_meta">
						<input type="checkbox" name="wpcloudy_map_temperature" id="wpcloudy_map_temperature_meta" value="yes" '. checked( $wpcloudy_map_temperature, 'yes', false ) .' />
							'. __( 'Display temperature?', 'wp-cloudy' ) .'
					</label>
				</p>
				<p>				
					<label for="wpcloudy_map_pressure_meta">
						<input type="checkbox" name="wpcloudy_map_pressure" id="wpcloudy_map_pressure_meta" value="yes" '. checked( $wpcloudy_map_pressure, 'yes', false ) .' />
							'. __( 'Display pressure?', 'wp-cloudy' ) .'
					</label>
				</p>
			</div>
	</div>
  ';  
}

add_action('save_post','wpc_save_metabox');
function wpc_save_metabox($post_id){
	if ( 'wpc-weather' === get_post_type($post_id)) {
		if(isset($_POST['wpcloudy_city'])){
		  update_post_meta($post_id, '_wpcloudy_city', esc_html($_POST['wpcloudy_city']));
		}
		if(isset($_POST['wpcloudy_city_name'])){
		  update_post_meta($post_id, '_wpcloudy_city_name', esc_html($_POST['wpcloudy_city_name']));
		}
		if(isset($_POST['wpcloudy_id_owm'])){
		  update_post_meta($post_id, '_wpcloudy_id_owm', esc_html($_POST['wpcloudy_id_owm']));
		}
		if(isset($_POST['wpcloudy_country_code'])){
		  update_post_meta($post_id, '_wpcloudy_country_code', esc_html($_POST['wpcloudy_country_code']));
		}
		if(isset($_POST['wpcloudy_unit'])) {
		  update_post_meta($post_id, '_wpcloudy_unit', $_POST['wpcloudy_unit']);
		}
		if(isset($_POST['wpcloudy_date_format'])) {
		  update_post_meta($post_id, '_wpcloudy_date_format', $_POST['wpcloudy_date_format']);
		}		
		if( isset( $_POST[ 'wpcloudy_date_timezone_bypass' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_date_timezone_bypass', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_date_timezone_bypass', '' );
		}
		if(isset($_POST['wpcloudy_date_timezone'])) {
		  update_post_meta($post_id, '_wpcloudy_date_timezone', $_POST['wpcloudy_date_timezone']);
		}
		if( isset( $_POST[ 'wpcloudy_current_weather' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_current_weather', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_current_weather', '' );
		}
		if( isset( $_POST[ 'wpcloudy_weather' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_weather', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_weather', '' );
		}
		if( isset( $_POST[ 'wpcloudy_date_temp' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_date_temp', $_POST[ 'wpcloudy_date_temp' ] );
		}
		if( isset( $_POST[ 'wpcloudy_display_temp_unit' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_display_temp_unit', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_display_temp_unit', '' );
		}
		if( isset( $_POST[ 'wpcloudy_sunrise_sunset' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_sunrise_sunset', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_sunrise_sunset', '' );
		}
		if( isset( $_POST[ 'wpcloudy_wind' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_wind', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_wind', '' );
		}
		if(isset($_POST['wpcloudy_wind_unit'])){
		  update_post_meta($post_id, '_wpcloudy_wind_unit', esc_html($_POST['wpcloudy_wind_unit']));
		}
		if( isset( $_POST[ 'wpcloudy_humidity' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_humidity', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_humidity', '' );
		}
		if( isset( $_POST[ 'wpcloudy_pressure' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_pressure', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_pressure', '' );
		}
		if( isset( $_POST[ 'wpcloudy_cloudiness' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_cloudiness', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_cloudiness', '' );
		}
		if( isset( $_POST[ 'wpcloudy_precipitation' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_precipitation', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_precipitation', '' );
		}
		if( isset( $_POST[ 'wpcloudy_hour_forecast' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_hour_forecast', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_hour_forecast', '' );
		}
		if(isset($_POST['wpcloudy_hour_forecast_nd'])){
		  update_post_meta($post_id, '_wpcloudy_hour_forecast_nd', esc_html($_POST['wpcloudy_hour_forecast_nd']));
		}
		if( isset( $_POST[ 'wpcloudy_temperature_min_max' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_temperature_min_max', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_temperature_min_max', '' );
		}
		if( isset( $_POST[ 'wpcloudy_short_days_names' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_short_days_names', $_POST[ 'wpcloudy_short_days_names' ] );
		}
		if( isset( $_POST[ 'wpcloudy_forecast' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_forecast', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_forecast', '' );
		}		
		if(isset($_POST['wpcloudy_forecast_nd'])){
		  update_post_meta($post_id, '_wpcloudy_forecast_nd', esc_html($_POST['wpcloudy_forecast_nd']));
		}
		if( isset( $_POST[ 'wpcloudy_forecast_precipitations' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_forecast_precipitations', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_forecast_precipitations', '' );
		}
		if( isset( $_POST[ 'wpcloudy_disable_anims' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_disable_anims', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_disable_anims', '' );
		}
		if( isset( $_POST[ 'wpcloudy_meta_bg_color' ] ) ) {
		  update_post_meta( $post_id, '_wpcloudy_meta_bg_color', $_POST[ 'wpcloudy_meta_bg_color' ] );
		}
		if( isset( $_POST[ 'wpcloudy_meta_txt_color' ] ) ) {
		  update_post_meta( $post_id, '_wpcloudy_meta_txt_color', $_POST[ 'wpcloudy_meta_txt_color' ] );
		}
		if( isset( $_POST[ 'wpcloudy_meta_border_color' ] ) ) {
		  update_post_meta( $post_id, '_wpcloudy_meta_border_color', $_POST[ 'wpcloudy_meta_border_color' ] );
		}
		if(isset($_POST['wpcloudy_custom_css'])){
		  update_post_meta($post_id, '_wpcloudy_custom_css', esc_html($_POST['wpcloudy_custom_css']));
		}
		if(isset($_POST['wpcloudy_size'])) {
		  update_post_meta($post_id, '_wpcloudy_size', $_POST['wpcloudy_size']);
		}
		if( isset( $_POST[ 'wpcloudy_owm_link' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_owm_link', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_owm_link', '' );
		}
		if( isset( $_POST[ 'wpcloudy_last_update' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_last_update', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_last_update', '' );
		}
		if( isset( $_POST[ 'wpcloudy_fluid' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_fluid', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_fluid', '' );
		}
		if(isset($_POST['wpcloudy_fluid_width'])){
		  update_post_meta($post_id, '_wpcloudy_fluid_width', esc_html($_POST['wpcloudy_fluid_width']));
		}
		if( isset( $_POST[ 'wpcloudy_map' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_map', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_map', '' );
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
		if( isset( $_POST[ 'wpcloudy_map_zoom_wheel' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_map_zoom_wheel', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_map_zoom_wheel', '' );
		}
		if( isset( $_POST[ 'wpcloudy_map_stations' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_map_stations', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_map_stations', '' );
		}
		if( isset( $_POST[ 'wpcloudy_map_clouds' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_map_clouds', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_map_clouds', '' );
		}
		if( isset( $_POST[ 'wpcloudy_map_precipitation' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_map_precipitation', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_map_precipitation', '' );
		}
		if( isset( $_POST[ 'wpcloudy_map_snow' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_map_snow', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_map_snow', '' );
		}
		if( isset( $_POST[ 'wpcloudy_map_wind' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_map_wind', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_map_wind', '' );
		}
		if( isset( $_POST[ 'wpcloudy_map_temperature' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_map_temperature', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_map_temperature', '' );
		}
		if( isset( $_POST[ 'wpcloudy_map_pressure' ] ) ) {
			update_post_meta( $post_id, '_wpcloudy_map_pressure', 'yes' );
		} else {
			delete_post_meta( $post_id, '_wpcloudy_map_pressure', '' );
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

function wpc_css_background($wpcloudy_meta_bg_color) {
	if( $wpcloudy_meta_bg_color ) {
			return 'background-color:'. $wpcloudy_meta_bg_color;
	}
};
function wpc_css_text_color($wpcloudy_meta_text_color) {
	if( $wpcloudy_meta_text_color ) {
			return $wpcloudy_meta_text_color;
	}
};
function wpc_css_border($wpcloudy_meta_border_color) {
	if( $wpcloudy_meta_border_color ) {
			return 'border:1px solid '. $wpcloudy_meta_border_color;
	}
};

function wpcloudy_city_name($wpcloudy_city_name, $wpcloudy_city_proper, $location_name, $wpcloudy_select_city_name, $wpcloudy_enable_geolocation, $wpcloudy_enable_geolocation_custom_field, $wpcloudy_custom_field_city_value, $wpcloudy_custom_field_country_value ) {

	if ($wpcloudy_enable_geolocation == 'yes' && $_COOKIE['wpc-manualGeolocation']=='1' && $wpcloudy_enable_geolocation_custom_field != 'yes') {	
		return $wpcloudy_select_city_name;
	}
	if ($wpcloudy_enable_geolocation == 'yes' && $_COOKIE['wpc-detectGeolocation']=='1' && $wpcloudy_enable_geolocation_custom_field != 'yes') {
		return $location_name;
	}
	if ($wpcloudy_enable_geolocation == 'yes' && $wpcloudy_enable_geolocation_custom_field != 'yes') {
		return $wpcloudy_city_name;
	}
	if ($wpcloudy_enable_geolocation == 'yes' && $wpcloudy_enable_geolocation_custom_field == 'yes') {
		return $location_name;
	}
	if( $wpcloudy_city_name && $wpcloudy_enable_geolocation == '') {
		return $wpcloudy_city_name;
	}
	if( $wpcloudy_city_proper && $wpcloudy_enable_geolocation == '') {
		return $wpcloudy_city_proper;
	}
};

function wpc_display_today_sunrise_sunset($wpcloudy_sunrise_sunset, $sun_rise, $sun_set) {
	if( $wpcloudy_sunrise_sunset ) {
		return '<div class="sun_hours">
					<span class="sunrise">'. $sun_rise .'</span> - <span class="sunset">'. $sun_set .'</span>
				</div>';
	}
}

function wpc_css_webfont($attr) {
	if(function_exists('wpcloudy_google_fonts')) {
		extract(shortcode_atts(array( 'id' => ''), $attr));
		$wpc_css_webfont_value = get_post_meta($id,'_wpcloudy_fonts',true);
			/***Open Sans***/
			if ($wpc_css_webfont_value == 'Open Sans' ) {
				wp_enqueue_style('Open Sans');
			}
			/***Ubuntu***/
			if ($wpc_css_webfont_value == 'Ubuntu' ) {
				wp_enqueue_style('Ubuntu');
			}
			/***Lato***/
			if ($wpc_css_webfont_value == 'Lato' ) {
				wp_enqueue_style('Lato');
			}
			/***Asap***/
			if ($wpc_css_webfont_value == 'Asap' ) {
				wp_enqueue_style('Asap');
			}
			/***Oswald***/
			if ($wpc_css_webfont_value == 'Oswald') { 
				wp_enqueue_style('Oswald');
			}
			/***Exo***/
			if ($wpc_css_webfont_value == '\'Exo 2\'' ) {
				wp_enqueue_style('Exo 2');
			}
			/***Roboto***/
			if ($wpc_css_webfont_value == 'Roboto' ) {
				wp_enqueue_style('Roboto');
			}
			/***Source Sans Pro***/
			if ($wpc_css_webfont_value == 'Source Sans Pro' ) {
				wp_enqueue_style('Source Sans Pro');
			}
			/***Droid Serif***/
			if ($wpc_css_webfont_value == 'Droid Serif' ) {
				wp_enqueue_style('Droid Serif');
			}
			/***Arvo***/
			if ($wpc_css_webfont_value == 'Arvo') { 
				wp_enqueue_style('Arvo');
			}
			/***Bitter***/
			if ($wpc_css_webfont_value == 'Bitter' ) {
				wp_enqueue_style('Bitter');
			}
			/***Francois One***/
			if ($wpc_css_webfont_value == 'Francois One' ) {
				wp_enqueue_style('Francois One');
			}
			/***Nunito***/
			if ($wpc_css_webfont_value == 'Nunito' ) {
				wp_enqueue_style('Nunito');
			}
			/***Josefin***/
			if ($wpc_css_webfont_value == 'Josefin Sans' ) {
				wp_enqueue_style('Josefin Sans');
			}
			/***Signika***/
			if ($wpc_css_webfont_value == 'Signika') { 
				wp_enqueue_style('Signika');
			}
			/***Merriweather Sans***/
			if ($wpc_css_webfont_value == 'Merriweather Sans') { 
				wp_enqueue_style('Merriweather Sans');
			}
			/***Tangerine***/
			if ($wpc_css_webfont_value == 'Tangerine') { 
				wp_enqueue_style('Tangerine');
			}
			/***Pacifico***/
			if ($wpc_css_webfont_value == 'Pacifico') { 
				wp_enqueue_style('Pacifico');
			}
			/***Inconsolata***/
			if ($wpc_css_webfont_value == 'Inconsolata') { 
				wp_enqueue_style('Inconsolata');
			}
		return $wpc_css_webfont_value;
	}
}

function wpc_icons_pack($attr) {
	if(function_exists('wpcloudy_icons_pack')) {
		extract(shortcode_atts(array( 'id' => ''), $attr));
		$wpc_icons_pack_value = get_post_meta($id,'_wpcloudy_icons',true);
		$wpc_skin_value = get_post_meta($id,'_wpcloudy_skin',true);

		if ($wpc_icons_pack_value == 'default' && $wpc_skin_value !='default') {
			wp_enqueue_style('wpcloudy-skin-addon');
		}

		if ($wpc_icons_pack_value == 'forecast_font') {
			wp_enqueue_style('wpcloudy-skin-addon');
			wp_enqueue_style('wpcloudy-forecast-icons');
		}
	}
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
//Is plugin active
///////////////////////////////////////////////////////////////////////////////////////////////////

function wpc_check_active_plugin() {
	if ( function_exists( 'wpcloudy_icons_pack' ) ) {
		return $wpc_skins_active = '1';
	}
	else {
		return $wpc_skins_active = '2';
	}
}
add_action( 'admin_init', 'wpc_check_active_plugin' );


///////////////////////////////////////////////////////////////////////////////////////////////////
//Add shortcode Weather
///////////////////////////////////////////////////////////////////////////////////////////////////


add_shortcode("wpc-weather", 'wpc_get_my_weather_id');

function wpc_get_my_weather_id($attr) {
	extract(shortcode_atts(array( 'id' => ''), $attr));
	$wpc_id 										= $id;

	wpc_css_webfont($attr);
	wpc_icons_pack($attr);
	wpc_weather_bg_img($attr);

	require_once dirname( __FILE__ ) . '/wpcloudy-options.php';
	  
	$wpcloudy_css3_anims      					= wpc_get_bypass_disable_css3_anims($wpc_id);
	$wpcloudy_map           					= wpc_get_bypass_map($wpc_id); 
	$wpcloudy_map_js        					= wpc_get_admin_map_js(); 
	$wpcloudy_skin 								= get_post_meta($wpc_id,'_wpcloudy_skin',true); 
	$wpc_html_css3_anims            			= null;

	if ($wpcloudy_skin == 'theme1' || $wpcloudy_skin == 'theme2' ) {
		wp_enqueue_script('jquery');
		wp_enqueue_script('wpc-flexslider-js');	
		wp_enqueue_style('wpc-flexslider-css');
	}
	
	if ($wpcloudy_css3_anims == 'yes') {
        $wpc_html_css3_anims .= '<style>
              .wpc-'.$wpc_id.' {
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
              </style>
            ';
          echo $wpc_html_css3_anims;
      }
      
     if ($wpcloudy_css3_anims != 'yes') {
    	wp_enqueue_style('wpcloudy-anim');
    }

    //Map
      if ($wpcloudy_map == 'yes') {
    	wp_register_script("leaflet-js", plugins_url('js/leaflet.js', __FILE__), "1.0", false);
    	
    	wp_register_script("leaflet-openweathermap-js", plugins_url('js/leaflet-openweathermap.js', __FILE__), "1.0", false);
      	
      	wp_register_style("leaflet-openweathermap-css", plugins_url('css/leaflet-openweathermap.css', __FILE__));
      	wp_register_style("leaflet-css", plugins_url('css/leaflet.css', __FILE__));
      	
      	wp_enqueue_script("leaflet-js");

      	wp_enqueue_script("leaflet-openweathermap-js");          
      	
      	wp_enqueue_style("leaflet-openweathermap-css");
      	wp_enqueue_style("leaflet-css");
    }

    $wpc_detectGeolocation 		= !empty($_COOKIE['wpc-detectGeolocation'])? $_COOKIE['wpc-detectGeolocation'] : "" ;
	$wpc_manualGeolocation 		= !empty($_COOKIE['wpc-manualGeolocation'])? $_COOKIE['wpc-manualGeolocation'] : "" ;
	$wpc_posLat 				= !empty($_COOKIE['wpc-posLat'])? $_COOKIE['wpc-posLat'] : "" ;
	$wpc_posLon 				= !empty($_COOKIE['wpc-posLon'])? $_COOKIE['wpc-posLon'] : "" ;
	$wpc_posCityId 				= !empty($_COOKIE['wpc-posCityId'])? $_COOKIE['wpc-posCityId'] : "" ;
	$wpc_posCityName 			= !empty($_COOKIE['wpc-posCityName'])? $_COOKIE['wpc-posCityName'] : "" ;

	return '<div id="wpc-weather-id-'.$wpc_id.'" class="wpc-weather-id" data-id="'.$wpc_id.'" data-post-id="'.get_the_ID().'" data-map="'.$wpcloudy_map.'" data-detect-geolocation="'.$wpc_detectGeolocation.'" data-manual-geolocation="'.$wpc_manualGeolocation.'" data-wpc-lat="'.$wpc_posLat.'" data-wpc-lon="'.$wpc_posLon.'" data-wpc-city-id="'.$wpc_posCityId.'" data-wpc-city-name="'.$wpc_posCityName.'" data-custom-font="'.wpc_css_webfont($attr).'"></div>';
}

add_action( 'wp_ajax_wpc_get_my_weather', 'wpc_get_my_weather' );
add_action( 'wp_ajax_nopriv_wpc_get_my_weather', 'wpc_get_my_weather' );

function wpc_get_my_weather($attr) {
	check_ajax_referer( 'wpc_get_weather_nonce', $_POST['_ajax_nonce'], true );

	if ( isset( $_POST['wpc_param'] ) ) {
		$id = $_POST['wpc_param'];

		require_once dirname( __FILE__ ) . '/wpcloudy-options.php';
		require_once dirname( __FILE__ ) . '/wpcloudy-anim.php';
		
		
		if ( isset( $_POST['wpc_param3']) || isset($_POST['wpc_param4']) || isset($_POST['wpc_param5']) || isset($_POST['wpc_param6']) || isset($_POST['wpc_param7']) || isset($_POST['wpc_param8']) || isset($_POST['wpc_param9']) || isset($_POST['wpc_param10']) ) {
			$_COOKIE['wpc-detectGeolocation'] 		= $_POST['wpc_param3'];
			$_COOKIE['wpc-manualGeolocation'] 		= $_POST['wpc_param4'];
			$_COOKIE['wpc-posLat']					= $_POST['wpc_param5'];
			$_COOKIE['wpc-posLon'] 					= $_POST['wpc_param6'];
			$_COOKIE['wpc-posCityId'] 				= $_POST['wpc_param7'];
			$_COOKIE['wpc-posCityName'] 			= $_POST['wpc_param8'];
			$wpc_css_webfont 						= $_POST['wpc_param9'];
			$wpc_get_the_ID 						= $_POST['wpc_param10'];
		}

	  	$wpc_id 									= $id;
	  	$wpcloudy_id_owm          					= get_post_meta($id,'_wpcloudy_id_owm',true);
	  	$wpcloudy_city                				= str_replace(' ', '+', strtolower(get_post_meta($id,'_wpcloudy_city',true)));
	  	$wpcloudy_city_proper          				= get_post_meta($id,'_wpcloudy_city',true);
		$wpcloudy_city_name             			= get_post_meta($id,'_wpcloudy_city_name',true);
		$wpcloudy_country_code            			= str_replace(' ', '+', get_post_meta($id,'_wpcloudy_country_code',true));
		$wpcloudy_custom_field_city_name      		= get_post_meta($id,'_wpcloudy_custom_field_city_name',true);
		$wpcloudy_custom_field_country_name    		= strtolower(get_post_meta($id,'_wpcloudy_custom_field_country_name',true));
		$wpcloudy_custom_field_city_value    		= get_post_meta( $wpc_get_the_ID, $wpcloudy_custom_field_city_name, true );
		$wpcloudy_custom_field_country_value    	= get_post_meta( $wpc_get_the_ID, $wpcloudy_custom_field_country_name, true );
		$wpcloudy_custom_field_lat_name       		= get_post_meta($id,'_wpcloudy_custom_field_lat_name',true);
		$wpcloudy_custom_field_lon_name       		= get_post_meta($id,'_wpcloudy_custom_field_lon_name',true);
		$wpcloudy_custom_field_lat_value      		= get_post_meta( $wpc_get_the_ID, $wpcloudy_custom_field_lat_name, true );
		$wpcloudy_custom_field_lon_value      		= get_post_meta( $wpc_get_the_ID, $wpcloudy_custom_field_lon_name, true );
		$wpcloudy_lat_lon_cf_value          		= get_post_meta( $id, '_wpcloudy_lat_lon_cf', true );
		$wpcloudy_unit                				= wpc_get_bypass_unit($attr);
		$wpcloudy_map_height            			= wpc_get_bypass_map_height($attr);
		$wpcloudy_map_opacity          				= wpc_get_bypass_map_opacity($attr);
		$wpcloudy_map_zoom              			= wpc_get_bypass_map_zoom($attr);
		$wpcloudy_map_zoom_wheel          			= wpc_get_bypass_map_zoom_wheel($attr);
		$wpcloudy_map_stations            			= wpc_get_bypass_map_layers_stations($attr);
		$wpcloudy_map_clouds            			= wpc_get_bypass_map_layers_clouds($attr);
		$wpcloudy_map_precipitation         		= wpc_get_bypass_map_layers_precipitation($attr);
		$wpcloudy_map_snow              			= wpc_get_bypass_map_layers_snow($attr);
		$wpcloudy_map_wind              			= wpc_get_bypass_map_layers_wind($attr);
		$wpcloudy_map_temperature         			= wpc_get_bypass_map_layers_temperature($attr);
		$wpcloudy_map_pressure            			= wpc_get_bypass_map_layers_pressure($attr);
		$wpcloudy_meta_border_color         		= wpc_get_bypass_color_border($attr);
		$wpcloudy_meta_bg_color           			= wpc_get_bypass_color_background($attr);
		$wpcloudy_meta_text_color         			= wpc_get_bypass_color_text($attr);
		$wpcloudy_date_format          				= wpc_get_bypass_date($attr);
		$wpcloudy_sunrise_sunset          			= wpc_get_bypass_display_sunrise_sunset($attr);
		$wpcloudy_display_temp_unit         		= wpc_get_bypass_display_temp_unit($attr);
		$wpcloudy_display_length_days_names     	= wpc_get_bypass_length_days_names($attr);
		$wpcloudy_enable_geolocation        		= get_post_meta($id,'_wpcloudy_enable_geolocation',true);
		$wpcloudy_enable_geolocation_custom_field   = get_post_meta($id,'_wpcloudy_enable_geolocation_custom_field',true);
		$wpc_advanced_set_cache_time        		= wpc_get_admin_cache_time();
		$wpc_advanced_set_disable_cache       		= wpc_get_admin_disable_cache();
		$wpc_advanced_api_key           			= wpc_get_api_key();
		$wpcloudy_display_owm_link          		= wpc_get_bypass_owm_link($attr);
		$wpcloudy_display_last_update       		= wpc_get_bypass_last_update($attr);
		$wpcloudy_icons_pack            			= get_post_meta($id,'_wpcloudy_icons',true);
		$wpcloudy_weather_bg_img            		= get_post_meta($id,'_wpcloudy_weather_bg_img',true);
		$wpcloudy_hour_forecast     				= wpc_get_bypass_display_hour_forecast($attr);
		$wpcloudy_hour_forecast_nd    				= wpc_get_bypass_display_hour_forecast_nd($attr);
		$wpcloudy_forecast        					= wpc_get_bypass_display_forecast($attr);
		$wpcloudy_forecast_nd     					= wpc_get_bypass_forecast_nd($attr); 
		$wpcloudy_forecast_precipitation			= wpc_get_bypass_forecast_precipitation($attr); 
		$wpcloudy_force_geolocation_js 				= get_post_meta($id,'_wpcloudy_force_geolocation',true);
		$wpcloudy_basic_date_timezone_bypass 		= get_post_meta($id,'_wpcloudy_date_timezone_bypass',true);
		$wpcloudy_basic_date_timezone				= get_post_meta($id,'_wpcloudy_date_timezone',true);
		$wpcloudy_date_temp       					= wpc_get_bypass_display_date_temp($attr);
		$wpcloudy_display_fluid       				= wpc_get_bypass_display_fluid($attr);
		$wpcloudy_display_fluid_width  				= wpc_get_bypass_display_fluid_width($attr);

		//variable declarations
		$wpcloudy_select_city_name          		= null;
		$display_today_min_max_day          		= null;
		$display_today_min_max_start        		= null;
		$display_today_min_max_end          		= null;      
		$wpc_html_now_start             			= null;
		$wpc_html_now_location_name         		= null;
		$wpc_html_display_now_time_symbol       	= null;
		$wpc_html_display_now_time_temperature    	= null;
		$wpc_html_now_end               			= null;
		$wpc_html_custom_css            			= null;
		$wpc_html_weather               			= null;
		$wpc_html_today_temp_start          		= null;
		$wpc_html_today_temp_day          			= null;
		$wpc_html_today_time_temp_min         		= null;
		$wpc_html_today_time_temp_max         		= null;
		$wpc_html_today_sun             			= null;
		$wpc_html_infos_start             			= null;
		$wpc_html_infos_wind            			= null;
		$wpc_html_infos_humidity          			= null;
		$wpc_html_infos_pressure          			= null;
		$wpc_html_infos_cloudiness          		= null;
		$wpc_html_infos_precipitation         		= null;
		$wpc_html_infos_end             			= null;
		$wpc_html_hour                				= null;
		$wpc_html_hour_start            			= null;
		$wpc_html_hour_end              			= null;
		$wpc_html_forecast              			= null;
		$wpc_html_map                 				= null;
		$wpc_html_temp_unit_imperial        		= null;
		$wpc_html_css3_anims            			= null;
		$display_today_sun              			= null;
		$wpc_html_today_temp_end          			= null;
		$wpc_html_forecast_start          			= null;
		$wpc_html_forecast_end            			= null;
		$wpc_html_temp_unit_metric          		= null;
		$wpc_html_container_end           			= null;
		$wpc_html_geolocation           			= null;
		$wpcloudy_owm_link              			= null;
		$wpcloudy_last_update           			= null;
		$wpc_html_owm_link              			= null;
		$wpc_html_last_update           			= null;
		
		//Set a default value for cache
		if ($wpc_advanced_set_cache_time =='') {
			$wpc_advanced_set_cache_time = '30';
		}

		if (isset($_COOKIE['wpc-posLat'])) {
			$wpcloudy_lat         					= $_COOKIE['wpc-posLat'];
		}
		if (isset($_COOKIE['wpc-posLon'])) {
			$wpcloudy_lon         					= $_COOKIE['wpc-posLon'];
		}
		if (isset($_COOKIE['wpc-posCityId'])) {
			$wpcloudy_select_city_id  				= $_COOKIE['wpc-posCityId'];
		}
		if (isset($_COOKIE['wpc-posCityName'])) {
			$wpcloudy_select_city_name  			= $_COOKIE['wpc-posCityName'];
		}

        //JSON : Current weather   
        //Geolocation ON / Automatic Detection
        if( $wpcloudy_enable_geolocation == 'yes' && $_COOKIE['wpc-detectGeolocation']=='1' && $wpcloudy_enable_geolocation_custom_field != 'yes' ) { 
        	$myweather_current_url = wp_remote_retrieve_body(wp_remote_get("https://api.openweathermap.org/data/2.5/weather?lat=".$wpcloudy_lat."&lon=".$wpcloudy_lon."&mode=json&units=".$wpcloudy_unit."&APPID=".$wpc_advanced_api_key));
        	$myweather_current = json_decode($myweather_current_url);
        	if (!$myweather_current) {
          		_e('Unable to retrieve weather data','wp-cloudy');
			}
        }
        //Geolocation ON / Manual Detection
        elseif( $wpcloudy_enable_geolocation == 'yes' && $_COOKIE['wpc-manualGeolocation']=='1' && $wpcloudy_enable_geolocation_custom_field != 'yes' )  {
        	$myweather_current_url = wp_remote_retrieve_body(wp_remote_get("https://api.openweathermap.org/data/2.5/weather?id=".$wpcloudy_select_city_id."&mode=json&units=".$wpcloudy_unit."&APPID=".$wpc_advanced_api_key));
        	$myweather_current = json_decode($myweather_current_url);
        	if (!$myweather_current) {
          		_e('Unable to retrieve weather data','wp-cloudy');
			}
        }

        else {
        	//No CACHE
	        if ($wpc_advanced_set_disable_cache == '1') {
	        	if ($wpcloudy_id_owm !='') {
					$myweather_current_url = wp_remote_retrieve_body(wp_remote_get("https://api.openweathermap.org/data/2.5/weather?id=".$wpcloudy_id_owm."&mode=json&units=".$wpcloudy_unit."&APPID=".$wpc_advanced_api_key));
	        	} else {
	        		$myweather_current_url = wp_remote_retrieve_body(wp_remote_get("https://api.openweathermap.org/data/2.5/weather?q=".$wpcloudy_city.",".$wpcloudy_country_code."&mode=json&units=".$wpcloudy_unit."&APPID=".$wpc_advanced_api_key));
	        	}
	          	
	          	$myweather_current = json_decode($myweather_current_url);
	          	if (!$myweather_current) {
	          		_e('Unable to retrieve weather data','wp-cloudy');
				}
	        }
	        else {    
	        	//Geolocation ON / Custom Field ON / LAT-LON fields ON
		      	if( $wpcloudy_enable_geolocation == 'yes' 
		        && $wpcloudy_enable_geolocation_custom_field == 'yes' 
		        && $wpcloudy_lat_lon_cf_value == 'no' 
		        && false === ( $myweather_current = get_transient( 'myweather_current_'.$wpc_id.'_'.$wpc_get_the_ID ) ) )  {
			        $myweather_current_url = wp_remote_retrieve_body(wp_remote_get("https://api.openweathermap.org/data/2.5/weather?q=".$wpcloudy_custom_field_city_value.",".$wpcloudy_custom_field_country_value."&mode=json&units=".$wpcloudy_unit."&APPID=".$wpc_advanced_api_key));
			    	$myweather_current = json_decode($myweather_current_url);
			    	if ($myweather_current) {
						set_transient( 'myweather_current_'.$wpc_id.'_'.$wpc_get_the_ID, $myweather_current, $wpc_advanced_set_cache_time * MINUTE_IN_SECONDS );
						$myweather_current = get_transient( 'myweather_current_'.$wpc_id.'_'.$wpc_get_the_ID );
					}
	        	}
	        	//Geolocation ON / Custom Field ON / CITY-COUNTRY fields ON
	        	elseif( $wpcloudy_enable_geolocation == 'yes' 
				&& $wpcloudy_enable_geolocation_custom_field == 'yes' 
				&& $wpcloudy_lat_lon_cf_value == 'yes'
				&& false === ( $myweather_current = get_transient( 'myweather_current_'.$wpc_id.'_'.$wpc_get_the_ID ) ) ) {
			        $myweather_current_url = wp_remote_retrieve_body(wp_remote_get("https://api.openweathermap.org/data/2.5/weather?lat=".$wpcloudy_custom_field_lat_value."&lon=".$wpcloudy_custom_field_lon_value."&mode=json&units=".$wpcloudy_unit."&APPID=".$wpc_advanced_api_key)); 
			    	$myweather_current = json_decode($myweather_current_url);
			        if ($myweather_current) {
						set_transient( 'myweather_current_'.$wpc_id.'_'.$wpc_get_the_ID, $myweather_current, $wpc_advanced_set_cache_time * MINUTE_IN_SECONDS );
			        	$myweather_current = get_transient( 'myweather_current_'.$wpc_id.'_'.$wpc_get_the_ID );
					}
				}
				//Geolocation OFF / Custom Field OFF - default
				//Geolocation OFF / Custom Field ON
				//Geolocation ON / Custom Field OFF
	          	elseif ( (($wpcloudy_enable_geolocation != 'yes' && $wpcloudy_enable_geolocation_custom_field != 'yes')
				|| ($wpcloudy_enable_geolocation != 'yes' && $wpcloudy_enable_geolocation_custom_field == 'yes')
				|| ($wpcloudy_enable_geolocation == 'yes' && $wpcloudy_enable_geolocation_custom_field != 'yes'))
				&& false === ( $myweather_current = get_transient( 'myweather_current_'.$wpc_id ) ) ) {  
		            if ($wpcloudy_id_owm !='') {
		            	$myweather_current_url = wp_remote_retrieve_body(wp_remote_get("https://api.openweathermap.org/data/2.5/weather?id=".$wpcloudy_id_owm."&mode=json&units=".$wpcloudy_unit."&APPID=".$wpc_advanced_api_key));
		            } else {
		            	$myweather_current_url = wp_remote_retrieve_body(wp_remote_get("https://api.openweathermap.org/data/2.5/weather?q=".$wpcloudy_city.",".$wpcloudy_country_code."&mode=json&units=".$wpcloudy_unit."&APPID=".$wpc_advanced_api_key));
		            }
		        	$myweather_current = json_decode($myweather_current_url);
		            if (!$myweather_current) {
		            } else {
						set_transient( 'myweather_current_'.$wpc_id, $myweather_current, $wpc_advanced_set_cache_time * MINUTE_IN_SECONDS );
	            		$myweather_current = get_transient( 'myweather_current_'.$wpc_id );
					}
	      		}
	      		//Geolocation ON / Custom Field ON
	      		else {
		            if ($wpcloudy_enable_geolocation == 'yes' && $wpcloudy_enable_geolocation_custom_field == 'yes') {
		              	$myweather_current = get_transient( 'myweather_current_'.$wpc_id.'_'.$wpc_get_the_ID );
		            }
		            else {
		              	$myweather_current = get_transient( 'myweather_current_'.$wpc_id );
		            }
	          	}
	        }
	    }

        //JSON : Hourly - 5 days forecast weather      
        if( ($wpcloudy_hour_forecast && !$wpcloudy_hour_forecast_nd =='') || ($wpcloudy_forecast && !$wpcloudy_forecast_nd == '' ) ) {
          	if( $wpcloudy_enable_geolocation == 'yes' && $_COOKIE['wpc-detectGeolocation']=='1' && $wpcloudy_enable_geolocation_custom_field != 'yes' ) {
            	$myweather_url = wp_remote_retrieve_body(wp_remote_get("https://api.openweathermap.org/data/2.5/forecast/?lat=".$wpcloudy_lat."&lon=".$wpcloudy_lon."&mode=json&units=".$wpcloudy_unit."&APPID=".$wpc_advanced_api_key));
            	$myweather = json_decode($myweather_url);
            	if (!$myweather) {
          			_e('Unable to retrieve weather data','wp-cloudy');
				}
          	}
          	elseif( $wpcloudy_enable_geolocation == 'yes' && $_COOKIE['wpc-manualGeolocation']=='1' && $wpcloudy_enable_geolocation_custom_field != 'yes' )  {
            	$myweather_url = wp_remote_retrieve_body(wp_remote_get("https://api.openweathermap.org/data/2.5/forecast/?id=".$wpcloudy_select_city_id."&mode=json&units=".$wpcloudy_unit."&APPID=".$wpc_advanced_api_key));
            	$myweather = json_decode($myweather_url);
            	if (!$myweather) {
          			_e('Unable to retrieve weather data','wp-cloudy');
				}
          	}
          	else {
        		if ($wpc_advanced_set_disable_cache == '1') {
        			if ($wpcloudy_id_owm !='') {
    					$myweather_url = wp_remote_retrieve_body(wp_remote_get("https://api.openweathermap.org/data/2.5/forecast/?id=".$wpcloudy_id_owm."&mode=json&units=".$wpcloudy_unit."&APPID=".$wpc_advanced_api_key, array( 'timeout' => 10)));
        			} else {
        				$myweather_url = wp_remote_retrieve_body(wp_remote_get("https://api.openweathermap.org/data/2.5/forecast/?q=".$wpcloudy_city.",".$wpcloudy_country_code."&mode=json&units=".$wpcloudy_unit."&APPID=".$wpc_advanced_api_key, array( 'timeout' => 10)));
        			}              		
              		$myweather = json_decode($myweather_url);
              		if (!$myweather) {
	          			_e('Unable to retrieve weather data','wp-cloudy');
					}
            	}
            	else {  
	              	if( $wpcloudy_enable_geolocation == 'yes' && $wpcloudy_enable_geolocation_custom_field == 'yes' && $wpcloudy_custom_field_city_value !='' && $wpcloudy_custom_field_country_value !='' && false === ( $myweather = (get_transient( 'myweather_'.$wpc_id.'_'.$wpc_get_the_ID ) ) ) )  { 
                		$myweather_url = wp_remote_retrieve_body(wp_remote_get("https://api.openweathermap.org/data/2.5/forecast/?q=".$wpcloudy_custom_field_city_value.",".$wpcloudy_custom_field_country_value."&mode=json&units=".$wpcloudy_unit."&APPID=".$wpc_advanced_api_key));
                		$myweather = json_decode($myweather_url);
	                	if ($myweather) {
							set_transient( 'myweather_'.$wpc_id.'_'.$wpc_get_the_ID, $myweather, $wpc_advanced_set_cache_time * MINUTE_IN_SECONDS );		                	
	                		$myweather = get_transient( 'myweather_'.$wpc_id.'_'.$wpc_get_the_ID );
						}
	              	}
	              	elseif( $wpcloudy_enable_geolocation == 'yes' && $wpcloudy_enable_geolocation_custom_field == 'yes' && $wpcloudy_custom_field_lat_value !='' && $wpcloudy_custom_field_lon_value !='' && false === ( $myweather = get_transient( 'myweather_'.$wpc_id.'_'.$wpc_get_the_ID ) ) )  {  
	                	$myweather_url = wp_remote_retrieve_body(wp_remote_get("https://api.openweathermap.org/data/2.5/forecast/?lat=".$wpcloudy_custom_field_lat_value."&lon=".$wpcloudy_custom_field_lon_value."&mode=json&units=".$wpcloudy_unit."&APPID=".$wpc_advanced_api_key));
	                	$myweather = json_decode($myweather_url);
		                if ($myweather) {
		                	set_transient( 'myweather_'.$wpc_id.'_'.$wpc_get_the_ID, $myweather, $wpc_advanced_set_cache_time * MINUTE_IN_SECONDS );
		                	$myweather = get_transient( 'myweather_'.$wpc_id.'_'.$wpc_get_the_ID );
	                	}
	              	}
	              	elseif ( (($wpcloudy_enable_geolocation != 'yes' && $wpcloudy_enable_geolocation_custom_field != 'yes')
	                || ($wpcloudy_enable_geolocation != 'yes' && $wpcloudy_enable_geolocation_custom_field == 'yes')
	                || ($wpcloudy_enable_geolocation == 'yes' && $wpcloudy_enable_geolocation_custom_field != 'yes'))
	                && false === ( $myweather = get_transient( 'myweather_'.$wpc_id ) ) ) {
	                	if ($wpcloudy_id_owm !='') {
	                		$myweather_url = wp_remote_retrieve_body(wp_remote_get("https://api.openweathermap.org/data/2.5/forecast/?id=".$wpcloudy_id_owm."&mode=json&units=".$wpcloudy_unit."&APPID=".$wpc_advanced_api_key));
	                	} else {
	                		$myweather_url = wp_remote_retrieve_body(wp_remote_get("https://api.openweathermap.org/data/2.5/forecast/?q=".$wpcloudy_city.",".$wpcloudy_country_code."&mode=json&units=".$wpcloudy_unit."&APPID=".$wpc_advanced_api_key));
	                	}	                	
	                	$myweather = json_decode($myweather_url);
		                if (!$myweather) {
		                }else {
		                	set_transient( 'myweather_'.$wpc_id, $myweather, $wpc_advanced_set_cache_time * MINUTE_IN_SECONDS );		                	
		                	$myweather = get_transient( 'myweather_'.$wpc_id );
	                	}
	              	}
	              	else {
	            		if ($wpcloudy_enable_geolocation == 'yes' && $wpcloudy_enable_geolocation_custom_field == 'yes') {
	                  		$myweather = get_transient( 'myweather_'.$wpc_id.'_'.$wpc_get_the_ID );
	                	}
		            	else {
		                	$myweather = get_transient( 'myweather_'.$wpc_id );         
		                }
		            }
		        }
		    }
		}   

		$location_name = null;
		if (isset($myweather_current->name)) {
			$location_name      					= $myweather_current->name;
		}
		$location_latitude = null;
		if (isset($myweather_current->coord->lat)) {
			$location_latitude    					= $myweather_current->coord->lat;
		}
		$location_longitude = null;
		if (isset($myweather_current->coord->lon)) {
			$location_longitude   					= $myweather_current->coord->lon;
		}
		$time_symbol = null;
		if (isset($myweather_current->weather{0}->description)) {
			$time_symbol      						= $myweather_current->weather{0}->description;
		}
		$time_symbol_number = null;
		if (isset($myweather_current->weather{0}->id)) {
			$time_symbol_number   					= $myweather_current->weather{0}->id;
		}
		$time_wind_direction = null;
		if (isset($myweather_current->wind->deg)) {
			$time_wind_direction  					= $myweather_current->wind->deg.'°';
		}
		$time_wind_speed = null;
		if (isset($myweather_current->wind->speed)) {
			$time_wind_speed    					= $myweather_current->wind->speed;
		}
		$time_humidity = null;
		if (isset($myweather_current->main->humidity)) {
			$time_humidity      					= $myweather_current->main->humidity;
		}
		$time_pressure = null;
		if (isset($myweather_current->main->pressure)) {
			$time_pressure      					= $myweather_current->main->pressure;
		}
		$time_cloudiness = null;
		if (isset($myweather_current->clouds->all)) {
			$time_cloudiness    					= $myweather_current->clouds->all;
		}
		$time_precipitation = null;
		if (isset($myweather_current->rain->{"3h"})) {
			$time_precipitation   					= $myweather_current->rain->{"3h"};
		}
		$time_temperature = null;
		if (isset($myweather_current->main->temp)) {
			$time_temperature   					= (ceil($myweather_current->main->temp));
		}
		$owm_link = null;
		if (isset($myweather_current->id)) {
			$owm_link         						= '<a href="https://openweathermap.org/city/'.$myweather_current->id.'" target="_blank" title="'.__('Full weather on OpenWeatherMap','wp-cloudy').'">'.__('Full weather','wp-cloudy').'</a>';
		}
		$last_update = null;
		if (isset($myweather_current->dt)) {
			$last_update      						= __('Last update: ','wp-cloudy').date("M-j-Y, H:i", $myweather_current->dt); 
		}

		if ($wpcloudy_date_format =='12') {
			$wpcloudy_date_php_sun    = 'h:i A';
			$wpcloudy_date_php_hours = 'h A';
		}

		if ($wpcloudy_date_format =='24') { 
			$wpcloudy_date_php_sun    = 'H:i';
			$wpcloudy_date_php_hours  = 'H';    
		}

		if ($wpcloudy_basic_date_timezone_bypass == 'yes') {
			$utc_time_wp      					= $wpcloudy_basic_date_timezone * 60; 
		} else {
			$utc_time_wp      					= get_option('gmt_offset') * 60; 
		}

		$sun_rise = null;
		if(isset($myweather_current->sys->sunrise)) {
			$sun_rise       						= (string)date($wpcloudy_date_php_sun, $myweather_current->sys->sunrise+60*$utc_time_wp);
		}
		$sun_set = null;
		if(isset($myweather_current->sys->sunset)) {
			$sun_set        						= (string)date($wpcloudy_date_php_sun, $myweather_current->sys->sunset+60*$utc_time_wp);
		}

		if ($wpcloudy_date_temp == 'no') {
			$today_month_feed = date('m', $myweather_current->dt);

			$today_day =  date_i18n( get_option( 'date_format' ), strtotime( '11/15-1976' ) );

		} elseif ($wpcloudy_date_temp == 'yes') {
			$today_day_feed	= strftime("%w", $myweather_current->dt);

			switch ($today_day_feed) {
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
  
		//Hours loop
		if( $wpcloudy_hour_forecast && !$wpcloudy_hour_forecast_nd =='' ) {  
			$hour_temp_0      			= (ceil($myweather->list{0}->main->temp));
		  	$hour_symbol_0      		= $myweather->list{0}->weather{0}->description;
		  	$hour_symbol_number_0 		= $myweather->list{0}->weather{0}->id;
		  
		  	$i=1;

		  	while ($i<=5) {
		    	$hour_time_[$i]       		= (string)date($wpcloudy_date_php_hours, $myweather->list{$i}->dt+60*$utc_time_wp);
		    	$hour_temp_[$i]       		= (ceil($myweather->list{$i}->main->temp));
		    	$hour_symbol_[$i]     		= $myweather->list{$i}->weather{0}->description;
		    	$hour_symbol_number_[$i]  	= $myweather->list{$i}->weather{0}->id;
		    	$i++;
		  	} 
		}
		
		//Forecast loop
		if ($wpcloudy_forecast && !$wpcloudy_forecast_nd == '' ) { 
			      
			$i=0;
			while ($i<=39) {
				
				$forecast_day_hours_feed      = strftime('%H', $myweather->list[$i]->dt);
				
				if ($forecast_day_hours_feed =='12') {
				    	$forecast_day_id[$i] = $forecast_day_hours_feed;
				}
				
				$i++;
			}

			foreach ($forecast_day_id as $i => $value) {
				$forecast_day_feed      = strftime('%w', $myweather->list[$i]->dt);

		  		if ($wpcloudy_display_length_days_names == 'yes') {
		        	switch ($forecast_day_feed) {
			        	case "0":
			          		$wpcloudy_display_length_days_names_php      = __('Sun','wp-cloudy');
			          		break;
			        	case "1":
			          		$wpcloudy_display_length_days_names_php      = __('Mon','wp-cloudy');
			          		break;
			        	case "2":
			        		$wpcloudy_display_length_days_names_php      = __('Tue','wp-cloudy');
			          		break;
			        	case "3":
			        		$wpcloudy_display_length_days_names_php      = __('Wed','wp-cloudy');
			          		break;
			        	case "4":
			        		$wpcloudy_display_length_days_names_php      = __('Thu','wp-cloudy');
			          		break;
			        	case "5":
			        		$wpcloudy_display_length_days_names_php      = __('Fri','wp-cloudy');
			          		break;
			        	case "6":
			        		$wpcloudy_display_length_days_names_php      = __('Sat','wp-cloudy');
			    	      	break;
			  		}
		     	}
		      	elseif ($wpcloudy_display_length_days_names == 'no') {
		        	switch ($forecast_day_feed) {
			        	case "0":
			          		$wpcloudy_display_length_days_names_php      = __('Sunday','wp-cloudy');
			          		break;
			        	case "1":
			          		$wpcloudy_display_length_days_names_php      = __('Monday','wp-cloudy');
			          		break;
			        	case "2":
			        		$wpcloudy_display_length_days_names_php      = __('Tuesday','wp-cloudy');
			          		break;
			        	case "3":
			        		$wpcloudy_display_length_days_names_php      = __('Wednesday','wp-cloudy');
			          		break;
			        	case "4":
			        		$wpcloudy_display_length_days_names_php      = __('Thursday','wp-cloudy');
			          		break;
			        	case "5":
			        		$wpcloudy_display_length_days_names_php      = __('Friday','wp-cloudy');
			          		break;
			        	case "6":
			        		$wpcloudy_display_length_days_names_php      = __('Saturday','wp-cloudy');
			          		break;
			  		}
		      	}
		      	else {
		        	switch ($forecast_day_feed) {
			        	case "0":
			          		$wpcloudy_display_length_days_names_php      = __('Sunday','wp-cloudy');
			          		break;
			        	case "1":
			          		$wpcloudy_display_length_days_names_php      = __('Monday','wp-cloudy');
			          		break;
			        	case "2":
			        		$wpcloudy_display_length_days_names_php      = __('Tuesday','wp-cloudy');
			          		break;
			        	case "3":
			        		$wpcloudy_display_length_days_names_php      = __('Wednesday','wp-cloudy');
			          		break;
			        	case "4":
			        		$wpcloudy_display_length_days_names_php      = __('Thursday','wp-cloudy');
			          		break;
			        	case "5":
			        		$wpcloudy_display_length_days_names_php      = __('Friday','wp-cloudy');
			          		break;
			        	case "6":
			        		$wpcloudy_display_length_days_names_php      = __('Saturday','wp-cloudy');
			          		break;
			  		}
		    	}
		    	
		    	if (isset($wpcloudy_display_length_days_names_php)) {
			    	$forecast_day_[$i]      	= $wpcloudy_display_length_days_names_php;
			    }
			    if (isset($myweather->list[$i]->weather[0]->id)) {
			    	$forecast_number_[$i]   	= $myweather->list[$i]->weather[0]->id;
			    }
			    if (isset($myweather->list[$i]->rain->{"3h"})) {
			    	$forecast_rain_[$i]   		= $myweather->list[$i]->rain->{"3h"};
				}
				if (isset($myweather->list[$i]->main->temp_min)) {
			    	$forecast_temp_min_[$i]   	= (round($myweather->list[$i]->main->temp_min));
			    }
			    if (isset($myweather->list[$i]->main->temp_max)) {
			    	$forecast_temp_max_[$i]   	= (round($myweather->list[$i]->main->temp_max));
			    }
			}
		}//End Forecast loop

			$time_symbol_svg = null;

			switch ($time_symbol_number) {

			//sun
			case "800":
			  $time_symbol_svg = sun();
			  $time_symbol_alt = '<span class="icon-sun"></span>';
			  $time_symbol 	   = __("clear sky","wp-cloudy");
			  break;
			case "801":
			  $time_symbol_svg = cloudSun();
			  $time_symbol_alt = '<span class="icon-basecloud"></span><span class="icon-sun"></span>';
			  $time_symbol 	   = __("few clouds","wp-cloudy");
			  break;
			case "802":
			  $time_symbol_svg = cloud();
			  $time_symbol_alt = '<span class="icon-cloud"></span>';
			  $time_symbol 	   = __("scattered clouds","wp-cloudy");
			  break;
			case "803":
			  $time_symbol_svg = cloudFill();
			  $time_symbol_alt = '<span class="icon-cloud icon-fill"></span>';
			  $time_symbol 	   = __("broken clouds","wp-cloudy");
			  break;
			case "804":
			  $time_symbol_svg = cloudFill();
			  $time_symbol_alt = '<span class="icon-cloud icon-fill"></span>';
			  $time_symbol 	   = __("overcast clouds","wp-cloudy");
			  break;
			  
			//rain
			case "500":
			  $time_symbol_svg = cloudDrizzleSun();
			  $time_symbol_alt = '<span class="icon-basecloud"></span><span class="icon-drizzle icon-sunny"></span>';
			  $time_symbol 	   = __("light rain","wp-cloudy");
			  break;
			case "501":
			  $time_symbol_svg = cloudDrizzleSun();
			  $time_symbol_alt = '<span class="icon-basecloud"></span><span class="icon-drizzle icon-sunny"></span>';
			  $time_symbol 	   = __("moderate rain","wp-cloudy");
			  break;
			case "502":
			  $time_symbol_svg = cloudDrizzle();
			  $time_symbol_alt = '<span class="icon-basecloud"></span><span class="icon-drizzle"></span>';
			  $time_symbol 	   = __("heavy intensity rain","wp-cloudy");
			  break;
			case "503":
			  $time_symbol_svg = cloudDrizzleSunAlt();
			  $time_symbol_alt = '<span class="icon-basecloud"></span><span class="icon-drizzle icon-sunny"></span>';
			  $time_symbol 	   = __("very heavy rain","wp-cloudy");
			  break;
			case "504":
			  $time_symbol_svg = cloudDrizzleAlt();
			  $time_symbol_alt = '<span class="icon-drizzle"></span>';
			  $time_symbol 	   = __("extreme rain","wp-cloudy");
			  break;
			case "511":
			  $time_symbol_svg = cloudRainSun();
			  $time_symbol_alt = '<span class="icon-basecloud"></span><span class="icon-rainy icon-sunny"></span>';
			  $time_symbol 	   = __("freezing rain","wp-cloudy");
			  break;
			case "520":
			  $time_symbol_svg = cloudRain();
			  $time_symbol_alt = '<span class="icon-basecloud"></span><span class="icon-rainy"></span>';
			  $time_symbol 	   = __("light intensity shower rain","wp-cloudy");
			  break;
			case "521":
			  $time_symbol_svg = cloudSunRainAlt();
			  $time_symbol_alt = '<span class="icon-basecloud"></span><span class="icon-showers icon-sunny"></span>';
			  $time_symbol 	   = __("shower rain","wp-cloudy");
			  break;
			case "522":
			  $time_symbol_svg = cloudRainAlt();
			  $time_symbol_alt = '<span class="icon-basecloud"></span><span class="icon-showers"></span>';
			  $time_symbol 	   = __("heavy intensity shower rain","wp-cloudy");
			  break;
			case "531":
			  $time_symbol_svg = cloudRainAlt();
			  $time_symbol_alt = '<span class="icon-basecloud"></span><span class="icon-showers"></span>';
			  $time_symbol 	   = __("ragged shower rain","wp-cloudy");
			  break;
			  
			//drizzle
			case "300":
			  $time_symbol_svg = cloudRainAlt();
			  $time_symbol_alt = '<span class="icon-drizzle"></span>';
			  $time_symbol 	   = __("light intensity drizzle","wp-cloudy");
			  break;
			case "301":
			  $time_symbol_svg = cloudRainAlt();
			  $time_symbol_alt = '<span class="icon-drizzle"></span>';
			  $time_symbol 	   = __("drizzle","wp-cloudy");
			  break;
			case "302":
			  $time_symbol_svg = cloudRainAlt();
			  $time_symbol_alt = '<span class="icon-drizzle"></span>';
			  $time_symbol 	   = __("heavy intensity drizzle","wp-cloudy");
			  break;
			case "310":
			  $time_symbol_svg = cloudRainAlt();
			  $time_symbol_alt = '<span class="icon-drizzle"></span>';
			  $time_symbol 	   = __("light intensity drizzle rain","wp-cloudy");
			  break;
			case "311":
			  $time_symbol_svg = cloudRainAlt();
			  $time_symbol_alt = '<span class="icon-drizzle"></span>';
			  $time_symbol 	   = __("drizzle rain","wp-cloudy");
			  break;
			case "312":
			  $time_symbol_svg = cloudRainAlt();
			  $time_symbol_alt = '<span class="icon-drizzle"></span>';
			  $time_symbol 	   = __("heavy intensity drizzle rain","wp-cloudy");
			  break;
			case "313":
			  $time_symbol_svg = cloudRainAlt();
			  $time_symbol_alt = '<span class="icon-drizzle"></span>';
			  $time_symbol 	   = __("shower rain and drizzle","wp-cloudy");
			  break;
			case "314":
			  $time_symbol_svg = cloudRainAlt();
			  $time_symbol_alt = '<span class="icon-drizzle"></span>';
			  $time_symbol 	   = __("heavy shower rain and drizzle","wp-cloudy");
			  break;
			case "321":
			  $time_symbol_svg = cloudRainAlt();
			  $time_symbol_alt = '<span class="icon-drizzle"></span>';
			  $time_symbol 	   = __("shower drizzle","wp-cloudy");
			  break;
			  
			//snow
			case "600":
			  $time_symbol_svg = cloudSnowSun();
			  $time_symbol_alt = '<span class="icon-snowy icon-sunny"></span>';
			  $time_symbol 	   = __("light snow","wp-cloudy");
			  break;
			case "601":
			  $time_symbol_svg = cloudSnow();
			  $time_symbol_alt = '<span class="icon-snowy"></span>';
			  $time_symbol 	   = __("snow","wp-cloudy");
			  break;
			case "602":
			  $time_symbol_svg = cloudSnowSunAlt();
			  $time_symbol_alt = '<span class="icon-snowy icon-sunny"></span>';
			  $time_symbol 	   = __("heavy snow","wp-cloudy");
			  break;
			case "611":
			  $time_symbol_svg = cloudSnow();
			  $time_symbol_alt = '<span class="icon-basecloud"></span><span class="icon-sleet"></span>';
			  $time_symbol 	   = __("sleet","wp-cloudy");
			  break;
			case "612":
			  $time_symbol_svg = cloudSnow();
			  $time_symbol_alt = '<span class="icon-basecloud"></span><span class="icon-sleet"></span>';
			  $time_symbol 	   = __("shower sleet","wp-cloudy");
			  break;
			case "615":
			  $time_symbol_svg = cloudSnow();
			  $time_symbol_alt = '<span class="icon-basecloud"></span><span class="icon-sleet"></span>';
			  $time_symbol 	   = __("light rain and snow","wp-cloudy");
			  break;
			case "616":
			  $time_symbol_svg = cloudSnow();
			  $time_symbol_alt = '<span class="icon-basecloud"></span><span class="icon-sleet"></span>';
			  $time_symbol 	   = __("rain and snow","wp-cloudy");
			  break;
			case "620":
			  $time_symbol_svg = cloudSnow();
			  $time_symbol_alt = '<span class="icon-basecloud"></span><span class="icon-sleet"></span>';
			  $time_symbol 	   = __("light shower snow","wp-cloudy");
			  break;
			case "621":
			  $time_symbol_svg = cloudSnowAlt();
			  $time_symbol_alt = '<span class="icon-snowy"></span>';
			  $time_symbol 	   = __("shower snow","wp-cloudy");
			  break;
			case "622":
			  $time_symbol_svg = cloudSnowAlt();
			  $time_symbol_alt = '<span class="icon-snowy"></span>';
			  $time_symbol 	   = __("heavy shower snow","wp-cloudy");
			  break;
			  
			//atmosphere
			case "701":
			  $time_symbol_svg = cloudFogSunAlt();
			  $time_symbol_alt = '<span class="icon-mist icon-sunny"></span>';
			  $time_symbol 	   = __("mist","wp-cloudy");
			  break;
			case "711":
			  $time_symbol_svg = cloudFogAlt();
			  $time_symbol_alt = '<span class="icon-mist"></span>';
			  $time_symbol 	   = __("smoke","wp-cloudy");
			  break;
			case "721":
			  $time_symbol_svg = cloudFogAlt();
			  $time_symbol_alt = '<span class="icon-mist"></span>';
			  $time_symbol 	   = __("haze","wp-cloudy");
			  break;
			case "731":
			  $time_symbol_svg = cloudFogSun();
			  $time_symbol_alt = '<span class="icon-mist icon-sunny"></span>';
			  $time_symbol 	   = __("sand, dust whirls","wp-cloudy");
			  break;
			case "741":
			  $time_symbol_svg = cloudFog();
			  $time_symbol_alt = '<span class="icon-mist"></span>';
			  $time_symbol 	   = __("fog","wp-cloudy");
			  break;
			  
			//extreme
			case "900":
			  $time_symbol_svg = tornado();
			  $time_symbol_alt = '<span class="icon-basecloud"></span><span class="icon-windy"></span>';
			  $time_symbol 	   = __("tornado","wp-cloudy");
			  break;
			case "901":
			  $time_symbol_svg = wind();
			  $time_symbol_alt = '<span class="icon-basecloud"></span><span class="icon-windy"></span>';
			  $time_symbol 	   = __("tropical storm","wp-cloudy");
			  break;
			case "902":
			  $time_symbol_svg = wind();
			  $time_symbol_alt = '<span class="icon-basecloud"></span><span class="icon-windy"></span>';
			  $time_symbol 	   = __("hurricane","wp-cloudy");
			  break;
			case "905":
			  $time_symbol_svg = wind();
			  $time_symbol_alt = '<span class="icon-basecloud"></span><span class="icon-windy"></span>';
			  $time_symbol 	   = __("windy","wp-cloudy");
			  break;
			case "906":
			  $time_symbol_svg = cloudHailAlt();
			  $time_symbol_alt = '<span class="icon-basecloud"></span><span class="icon-hail"></span>';
			  $time_symbol 	   = __("hail","wp-cloudy");
			  break;
			  
			//thunderstorm
			case "200":
			  $time_symbol_svg = cloudLightning();
			  $time_symbol_alt = '<span class="icon-thunder"></span>';
			  $time_symbol 	   = __("thunderstorm with light rain","wp-cloudy");
			  break;
			
			case "201":
			  $time_symbol_svg = cloudLightning();
			  $time_symbol_alt = '<span class="icon-thunder"></span>';
			  $time_symbol 	   = __("thunderstorm with rain","wp-cloudy");
			  break;
						
			case "202":
			  $time_symbol_svg = cloudLightning();
			  $time_symbol_alt = '<span class="icon-thunder"></span>';
			  $time_symbol 	   = __("thunderstorm with heavy rain","wp-cloudy");
			  break;
						
			case "210":
			  $time_symbol_svg = cloudLightning();
			  $time_symbol_alt = '<span class="icon-thunder"></span>';
			  $time_symbol 	   = __("light thunderstorm","wp-cloudy");
			  break;
						
			case "211":
			  $time_symbol_svg = cloudLightning();
			  $time_symbol_alt = '<span class="icon-thunder"></span>';
			  $time_symbol 	   = __("thunderstorm","wp-cloudy");
			  break;
			
			case "212":
			  $time_symbol_svg = cloudLightning();
			  $time_symbol_alt = '<span class="icon-thunder"></span>';
			  $time_symbol 	   = __("heavy thunderstorm","wp-cloudy");
			  break;
			
			case "221":
			  $time_symbol_svg = cloudLightning();
			  $time_symbol_alt = '<span class="icon-thunder"></span>';
			  $time_symbol 	   = __("ragged thunderstorm","wp-cloudy");
			  break;
			
			case "230":
			  $time_symbol_svg = cloudLightning();
			  $time_symbol_alt = '<span class="icon-thunder"></span>';
			  $time_symbol 	   = __("thunderstorm with light drizzle","wp-cloudy");
			  break;
			
			case "231":
			  $time_symbol_svg = cloudLightning();
			  $time_symbol_alt = '<span class="icon-thunder"></span>';
			  $time_symbol 	   = __("thunderstorm with drizzle","wp-cloudy");
			  break;
			
			case "232":
			  $time_symbol_svg = cloudLightning();
			  $time_symbol_alt = '<span class="icon-thunder"></span>';
			  $time_symbol 	   = __("thunderstorm with heavy drizzle","wp-cloudy");
			  break;
			}

			$wpcloudy_custom_css  = get_post_meta($id,'_wpcloudy_custom_css',true);

			if ($wpcloudy_custom_css) {
			$display_custom_css   = '
			  <style>
			    '. $wpcloudy_custom_css .'
			  </style>
			';
			}

			if ($wpcloudy_icons_pack == 'default' || wpc_check_active_plugin() == '2' ) {
				$display_now_start = '<div class="now">';
				$display_now_location_name = '<div class="location_name">'. wpcloudy_city_name($wpcloudy_city_name, $wpcloudy_city_proper, $location_name, $wpcloudy_select_city_name, $wpcloudy_enable_geolocation, $wpcloudy_enable_geolocation_custom_field, $wpcloudy_custom_field_city_value, $wpcloudy_custom_field_country_value, $wpcloudy_enable_geolocation_custom_field)  .'</div>';
				$display_now_time_symbol = '<div class="time_symbol climacon" style="fill:'. wpc_css_text_color($wpcloudy_meta_text_color) .'">'. $time_symbol_svg .'</div>';
				$display_now_time_temperature = '<div class="time_temperature">'. $time_temperature .'</div>';
				$display_now_end = '</div>';
			} elseif ($wpcloudy_icons_pack == 'forecast_font' && wpc_check_active_plugin() == '1') {
				$display_now_start = '<div class="now">';
				$display_now_location_name = '<div class="location_name">'. wpcloudy_city_name($wpcloudy_city_name, $wpcloudy_city_proper, $location_name, $wpcloudy_select_city_name, $wpcloudy_enable_geolocation, $wpcloudy_enable_geolocation_custom_field, $wpcloudy_custom_field_city_value, $wpcloudy_custom_field_country_value, $wpcloudy_enable_geolocation_custom_field)  .'</div>';
				$display_now_time_symbol = '<div class="time_symbol iconvault">'. $time_symbol_alt .'</div>';
				$display_now_time_temperature = '<div class="time_temperature">'. $time_temperature .'</div>';
				$display_now_end = '</div>';
			}
			$display_weather = '
			<div class="short_condition">'. $time_symbol .'</div>
			';

			$display_today_min_max_start  .=  '<div class="today">';
			$display_today_min_max_day    .=  '<div class="day"><span class="wpc-highlight">'. $today_day .'</span> '. __( 'Today', 'wp-cloudy' ) .'</div>';
			$display_today_sun        .=  wpc_display_today_sunrise_sunset($wpcloudy_sunrise_sunset, $sun_rise, $sun_set);
			$display_today_min_max_end    .=  '</div>';

			$wpcloudy_wind_unit        							=   wpc_get_bypass_display_wind_unit($attr);

			$time_wind_speed_convert = null;
			switch ($wpcloudy_wind_unit) {
		        case "1": //MI/H
		        	ini_set('precision', 3);
		        	if ($wpcloudy_unit =='metric') {
				        $time_wind_speed_convert = ($time_wind_speed)*2.24;
						$time_wind_speed_unit = 'mi/h';
					} else {
						$time_wind_speed_convert = $time_wind_speed;
						$time_wind_speed_unit = 'mi/h';
					}
		          	break;
		        case "2": //M/S
		        	ini_set('precision', 3);
		        	if ($wpcloudy_unit =='metric') {
			        	$time_wind_speed_convert = $time_wind_speed;
						$time_wind_speed_unit = 'm/s';
					} else {
						$time_wind_speed_convert = ($time_wind_speed)/2.24;
						$time_wind_speed_unit = 'm/s';
					}
		          	break;
		        case "3": //KM/H
		        	ini_set('precision', 3);
		        	if ($wpcloudy_unit =='metric') {
						$time_wind_speed_convert = ($time_wind_speed)*3.6;
						$time_wind_speed_unit = 'km/h';
					} else {
						$time_wind_speed_convert = ($time_wind_speed)*1.61;
						$time_wind_speed_unit = 'km/h';
					}
		          	break;
		        case "4": //KNOTS
		        	ini_set('precision', 3);
		        	if ($wpcloudy_unit =='metric') {
				        $time_wind_speed_convert = ($time_wind_speed)*1.94;
						$time_wind_speed_unit = 'kt';
					} else {
						$time_wind_speed_convert = $time_wind_speed*0.87;
						$time_wind_speed_unit = 'kt';
					}
		          	break;
		  		}

		  	if ($time_wind_speed_convert !='') {
				$display_wind = '
				<div class="wind">'. __( 'Wind', 'wp-cloudy' ) .'<span class="wpc-highlight">'. $time_wind_speed_convert .' '.$time_wind_speed_unit.' - '.$time_wind_direction.'</span></div>
				';
			}
			$display_humidity = '
			<div class="humidity">'. __( 'Humidity', 'wp-cloudy' ) .'<span class="wpc-highlight">'. $time_humidity .' %</span></div>
			';
			$display_pressure = '
			<div class="pressure">'. __( 'Pressure', 'wp-cloudy' ) .'<span class="wpc-highlight">'. $time_pressure .' hPa</span></div>
			';
			$display_cloudiness = '
			<div class="cloudiness">'. __( 'Cloudiness', 'wp-cloudy' ) .'<span class="wpc-highlight">'. $time_cloudiness .' %</span></div>
			';
			if ($time_precipitation != '') {
				$display_precipitation = '
				<div class="precipitation">'. __( 'Precipitation', 'wp-cloudy' ) .'<span class="wpc-highlight">'. $time_precipitation .' mm</span></div>
				';
			}
			elseif ($time_precipitation == '') {
				$display_precipitation = '
				<div class="precipitation">'. __( 'Precipitation', 'wp-cloudy' ) .'<span class="wpc-highlight">0 mm</span></div>
				';
			}

			//Hours loop
			if ($wpcloudy_hour_forecast && !$wpcloudy_hour_forecast_nd == '' ) {
				if ($wpcloudy_icons_pack == 'default' || wpc_check_active_plugin() == '2') {
			    	$display_hours_0 = '
				        <div class="first">
					        <div class="hour"><span class="wpc-highlight">'. __( 'Now', 'wp-cloudy' ) .'</span></div>
					        <div class="symbol climacon w'. $hour_symbol_number_0 .'"><span>'. $hour_symbol_0 .'</span></div>
					        <div class="temperature"><span class="wpc-highlight">'. $hour_temp_0 .'</span></div>
				        </div>
				    ';
			} elseif ($wpcloudy_icons_pack == 'forecast_font' && wpc_check_active_plugin() == '1') {
			    $display_hours_0 = '
			        <div class="first">
						<div class="hour"><span class="wpc-highlight">'. __( 'Now', 'wp-cloudy' ) .'</span></div>
						<div class="symbol">
							<div class="iconvault w'. $hour_symbol_number_0 .'"><span>'. $hour_symbol_0 .'</span></div>
							<div class="iconvault2 w'. $hour_symbol_number_0 .'"></div>
							<div class="iconvault3 w'. $hour_symbol_number_0 .'"></div>
						</div>
						<div class="temperature"><span class="wpc-highlight">'. $hour_temp_0 .'</span></div>
			        </div>
			    ';
			}
			  
			$wpcloudy_class_hours = array(1 => "second", 2 => "third", 3 => "fourth", 4 => "fifth", 5 => "sixth");
			  
			$i=1;
			while ($i<=5) { 
			    if ($wpcloudy_icons_pack == 'default' || wpc_check_active_plugin() == '2') {
					$display_hours_[$i] = '
					<div class="'. $wpcloudy_class_hours[$i].'">
						<div class="hour">'. $hour_time_[$i] .'</div>
						<div class="symbol climacon w'. $hour_symbol_number_[$i] .'"><span>'. $hour_symbol_[$i] .'</span></div>
						<div class="temperature">'. $hour_temp_[$i]. '</div>
					</div>
				';
			    } elseif ($wpcloudy_icons_pack == 'forecast_font' && wpc_check_active_plugin() == '1') {
			    	$display_hours_[$i] = '
			        	<div class="'. $wpcloudy_class_hours[$i].'">
							<div class="hour">'. $hour_time_[$i] .'</div>
								<div class="symbol">
									<div class="iconvault w'. $hour_symbol_number_[$i] .'"><span>'. $hour_symbol_[$i] .'</span></div>
									<div class="iconvault2 w'. $hour_symbol_number_[$i] .'"></div>
									<div class="iconvault3 w'. $hour_symbol_number_[$i] .'"></div>
								</div>
							<div class="temperature">'. $hour_temp_[$i]. '</div>
						</div>
			      	';
			    }
			$i++;
			} 
		}

		//Forecast loop
		if ($wpcloudy_forecast && !$wpcloudy_forecast_nd == '' ) {
			$wpcloudy_class_days = array(1 => "first", 2 => "second", 3 => "third", 4 => "fourth", 5 => "fifth");

			$forecast_day_id_array = array_keys($forecast_day_id);
			array_unshift($forecast_day_id_array,"none");
			unset($forecast_day_id_array[0]);

			foreach ($forecast_day_id_array as $i => $value) {
				$display_forecast_[$i] ='';
				$forecast_rain_[$value] ='';
				if ($wpcloudy_icons_pack == 'default' || wpc_check_active_plugin() == '2') {
			    	$display_forecast_[$i] .= '
			       		<div class="'. $wpcloudy_class_days[$i].'">
			          		<div class="day">'. $forecast_day_[$value] .'</div>
			          		<div class="symbol climacon w'. $forecast_number_[$value] .'"></div>';
			          		if ($wpcloudy_forecast_precipitation ) {
			          			if ($forecast_rain_[$value]) {
			          				$display_forecast_[$i] .= '<div class="rain">'. $forecast_rain_[$value] .' mm</div>';
			          			} else {
			          				$display_forecast_[$i] .= '<div class="rain">'.__('0 mm','wp-cloudy').'</div>';
			          			}
			          		}
			          		$display_forecast_[$i] .='         
			          		<div class="temp_min">'. $forecast_temp_min_[$value] .'</div>
			          		<div class="temp_max"><span class="wpc-highlight">'. $forecast_temp_max_[$value] .'</span></div>
			        	</div>
			      	';
			    }
			    elseif ($wpcloudy_icons_pack == 'forecast_font' && wpc_check_active_plugin() == '1'){
			    	$display_forecast_[$i] .= '  
			        	<div class="'. $wpcloudy_class_days[$i].'">
			          		<div class="day">'. $forecast_day_[$value] .'</div>
			          		<div class="symbol">
				            	<div class="iconvault w'. $forecast_number_[$value] .'"></div>
				            	<div class="iconvault2 w'. $forecast_number_[$value] .'"></div>
				            	<div class="iconvault3 w'. $forecast_number_[$value] .'"></div>
			          		</div>';
			          		if ($wpcloudy_forecast_precipitation ) {
			          			if ($forecast_rain_[$value]) {
			          				$display_forecast_[$i] .= '<div class="rain">'. $forecast_rain_[$value] .' mm</div>';
			          			} else {
			          				$display_forecast_[$i] .= '<div class="rain">'.__('0 mm','wp-cloudy').'</div>';
			          			}
			          		}
			          		$display_forecast_[$i] .='
			          		<div class="temp_min">'. $forecast_temp_min_[$value] .'</div>
			          		<div class="temp_max"><span class="wpc-highlight">'. $forecast_temp_max_[$value] .'</span></div>
			        	</div>
			      	';
			    }
			}
		}

	    //Map
		if ( isset( $_POST['wpc_param2'] ) ) {
			$wpcloudy_map = $_POST['wpc_param2'];
	      	if ($wpcloudy_map == 'yes') {
		    	
		    	//Layers opacity
		    	if ($wpcloudy_map_opacity !='') {
		    		$display_map_layers_opacity = $wpcloudy_map_opacity;
		    	} else {
		    		$display_map_layers_opacity = "0.5";
		    	}

		    	//Stations
		    	if ( $wpcloudy_map_stations ) {
		        	$display_map_stations         	= 'var station = L.OWM.current({type: "station", appId: "'.$wpc_advanced_api_key.'"});';
		        	$display_map_stations_layers    = '"Stations": station,';
		      	} else {
		        	$display_map_stations         	= '';
		        	$display_map_stations_layers    = '';
		      	}

		      	//Clouds
		      	if ( $wpcloudy_map_clouds ) {
		        	$display_map_clouds 			= 'var clouds = L.OWM.clouds({showLegend: false, opacity: '.$display_map_layers_opacity.', appId: "'.$wpc_advanced_api_key.'"});';
		        	$display_map_clouds_layers    	= '"Clouds": clouds,';
		      	} else {
		        	$display_map_clouds           	= '';
		        	$display_map_clouds_layers    	= '';
		      	}

		      	//Precipitations
		      	if ( $wpcloudy_map_precipitation ) {
		        	$display_map_precipitation        	= 'var precipitation = L.OWM.precipitation({opacity: '.$display_map_layers_opacity.', appId: "'.$wpc_advanced_api_key.'"});';
		        	$display_map_precipitation_layers   = '"Precipitation": precipitation,';
		      	} else {
		        	$display_map_precipitation        	= '';
		        	$display_map_precipitation_layers   = '';
		      	}

		      	//Snow
		      	if ( $wpcloudy_map_snow ) {
		        	$display_map_snow           = 'var snow = L.OWM.snow({opacity: '.$display_map_layers_opacity.', appId: "'.$wpc_advanced_api_key.'"});';
		        	$display_map_snow_layers    = '"Snow": snow,';
		      	} else {
		        	$display_map_snow           = '';
		        	$display_map_snow_layers    = '';
		      	}

		      	//Wind
		      	if ( $wpcloudy_map_wind ) {
		        	$display_map_wind             	= 'var wind = L.OWM.wind({opacity: '.$display_map_layers_opacity.', appId: "'.$wpc_advanced_api_key.'"});';
		        	$display_map_wind_layers        = '"Wind": wind,';
		      	} else {
		        	$display_map_wind           	= '';
		        	$display_map_wind_layers        = '';
		      	}

		      	//Temperature
		      	if ( $wpcloudy_map_temperature ) {
		        	$display_map_temperature        	= 'var temp = L.OWM.temperature({opacity: '.$display_map_layers_opacity.', appId: "'.$wpc_advanced_api_key.'"});';
		        	$display_map_temperature_layers     = '"Temperature": temp,';
		      	} else {
		        	$display_map_temperature        	= '';
		        	$display_map_temperature_layers     = '';
		      	}

		      	//Pressure
		      	if ( $wpcloudy_map_pressure ) {
		        	$display_map_pressure         		= 'var pressure = L.OWM.pressure({opacity: '.$display_map_layers_opacity.', appId: "'.$wpc_advanced_api_key.'"});';
		        	$display_map_pressure_layers      	= '"Pressure": pressure,';
		      	} else {
		        	$display_map_pressure           	= '';
		        	$display_map_pressure_layers      	= '';
		      	}

		      	//Scroll wheel
		      	if ($wpcloudy_map_zoom_wheel == 'yes') {
		      		$display_map_scroll_wheel = "false";
		      	} else {
					$display_map_scroll_wheel = "true";
		      	}

		      	//Geolocation
		      	if ( $wpcloudy_enable_geolocation == 'yes' && $_COOKIE['wpc-detectGeolocation']=='1' ) { 
		        	$wpcloudy_map_lat = $_COOKIE['wpc-posLat'];
		        	$wpcloudy_map_lon = $_COOKIE['wpc-posLon'];		      
		      	}		      
		      	if ( $wpcloudy_enable_geolocation == 'yes' && $_COOKIE['wpc-manualGeolocation']=='1' ) { 
		        	$wpcloudy_map_lat = $_COOKIE['wpc-posLat'];
		        	$wpcloudy_map_lon = $_COOKIE['wpc-posLon'];
		      	} else {
		        	$wpcloudy_map_lat = $location_latitude;
		        	$wpcloudy_map_lon = $location_longitude;
		      	}
		      	$display_map = '        
			        <div id="wpc-map-container">  
			        	<div id="wpc-map" style="height: '. $wpcloudy_map_height .'px"></div>
			        </div>
			        <script type="text/javascript">
			        	jQuery(document).ready( function() {

				        	var osm = L.tileLayer("http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
							maxZoom: 18, attribution: "WP Cloudy" });

							//Cities
							var city = L.OWM.current({intervall: 15, lang: "en", appId: "6c407f412bf644e72fa060adb84c6263"});

							//Stations
							'. $display_map_stations .'

							//Clouds
							'. $display_map_clouds .'

							//Precipitation
				            '. $display_map_precipitation .'
				            
				            //Snow
				            '. $display_map_snow .'
				            
				            //Wind
				            '. $display_map_wind .'
				            
				            //Temperature
				            '. $display_map_temperature .'
				            
				            //Pressure
				            '. $display_map_pressure .'

							var map = L.map("wpc-map", { center: new L.LatLng('. $wpcloudy_map_lat .', '. $wpcloudy_map_lon .'), zoom: '. $wpcloudy_map_zoom .', layers: [osm], scrollWheelZoom: '.$display_map_scroll_wheel.' });
							
							var baseMaps = { "OSM Standard": osm };
							
							var overlayMaps = {
								'.$display_map_stations_layers.' 
								'.$display_map_clouds_layers.' 
								'.$display_map_precipitation_layers.'
								'.$display_map_snow_layers.'
								'.$display_map_wind_layers.'
								'.$display_map_temperature_layers.'
								'.$display_map_pressure_layers.'
								"Cities": city
							};
							
							var layerControl = L.control.layers(baseMaps, overlayMaps).addTo(map);    
			        	});
			        </script>
			    ';
			}
		}
		$wpcloudy_current_weather   						=   wpc_get_bypass_display_current_weather($attr);
		$wpcloudy_weather       							=   wpc_get_bypass_display_weather($attr);
		$wpcloudy_wind          							=   wpc_get_bypass_display_wind($attr);
		$wpcloudy_humidity        							=   wpc_get_bypass_display_humidity($attr);
		$wpcloudy_pressure        							=   wpc_get_bypass_display_pressure($attr);
		$wpcloudy_cloudiness      							=   wpc_get_bypass_display_cloudiness($attr);
		$wpcloudy_precipitation     						=   wpc_get_bypass_display_precipitation($attr);
		$wpcloudy_temperature_min_max 						= 	wpc_get_bypass_temp($attr);
		$wpcloudy_size          							= 	wpc_get_bypass_size($attr);
		$wpcloudy_skin            							=   get_post_meta($id,'_wpcloudy_skin',true);
		$wpcloudy_image_bg_cover							= 	get_post_meta($id,'_wpcloudy_image_bg_cover',true);
		$wpcloudy_image_bg_cover_e							= 	null;
		$wpcloudy_image_bg_position_horizontal_e			= 	null;
		$wpcloudy_image_bg_position_vertical_e				= 	null;

		if ($wpcloudy_image_bg_cover == 'yes') {
			$wpcloudy_image_bg_cover_e = 'cover';
		}
		$wpcloudy_image_bg_position_horizontal				= 	get_post_meta($id,'_wpcloudy_image_bg_position_horizontal',true);
		if ($wpcloudy_image_bg_position_horizontal != 'default') {
			$wpcloudy_image_bg_position_horizontal_e		= 	$wpcloudy_image_bg_position_horizontal;
		}
		$wpcloudy_image_bg_position_vertical				= 	get_post_meta($id,'_wpcloudy_image_bg_position_vertical',true);
		if ($wpcloudy_image_bg_position_vertical != 'default') {
			$wpcloudy_image_bg_position_vertical_e		= 	$wpcloudy_image_bg_position_vertical;
		}
		$wpc_html_container_start = '
		<!-- WP Cloudy : WordPress weather plugin v'.WPCLOUDY_VERSION.' - https://wpcloudy.com/ -->
		<div id="wpc-weather" class="wpc-'.$id.' wpc-weather-'.$time_symbol_number.' '. $wpcloudy_size .' '. $wpcloudy_skin .'" style="'. wpc_css_background($wpcloudy_meta_bg_color) .'; background-size:'.$wpcloudy_image_bg_cover_e.'; background-position: '.$wpcloudy_image_bg_position_horizontal_e.'% '.$wpcloudy_image_bg_position_vertical_e.'%; color:'. wpc_css_text_color($wpcloudy_meta_text_color) .';'. wpc_css_border($wpcloudy_meta_border_color) .'; font-family:'. $wpc_css_webfont .'">
		';
    	
		if ($wpcloudy_display_fluid =='yes' && $wpcloudy_display_fluid_width !='') {
			$wpc_html_container_start .= '
				<script>
					jQuery(document).ready( function($) {
						function wpc_responsive_weather() {
							var size = $("#wpc-weather").parent().width();
							if (size <= "'.$wpcloudy_display_fluid_width.'") {
								$("#wpc-weather").addClass("wpc-xs");	
							}
						}
						wpc_responsive_weather();
						$(window).resize(wpc_responsive_weather);
					});
				</script>
			';
		} elseif ($wpcloudy_display_fluid =='yes') {
			$wpc_html_container_start .= '
				<script>
					jQuery(document).ready( function($) {
						function wpc_responsive_weather() {
							$("#wpc-weather").addClass("wpc-xs");
						}
						wpc_responsive_weather();
						$(window).resize(wpc_responsive_weather);
					});
				</script>
			';
		}

    	if ( wpc_check_active_plugin() == '1' ) {
	    	wpc_icons_pack($attr);
	    }

	    if(function_exists('wpc_geolocation_form')) {
	    	if ( $wpcloudy_enable_geolocation == 'yes' && $wpcloudy_enable_geolocation_custom_field != 'yes') { 
	        	$wpc_html_geolocation .=  wpc_geolocation_form($attr);
	        	if ($wpcloudy_force_geolocation_js == 'yes') {
	        		$wpc_html_geolocation .=  '<script>
					    jQuery( document ).ready(function() {
					        wpc_load_location();
					    });				 
					</script>';
	        	}
	    	}
	    }
	      
	    if( ($wpcloudy_current_weather =='yes' || $wpcloudy_current_weather =='1') && ($wpcloudy_temperature_min_max == 'yes' || $wpcloudy_temperature_min_max == '1' || $wpcloudy_temperature_min_max == '1' )) {
	        $wpc_html_now_start           			.= $display_now_start;
	        $wpc_html_now_location_name       		.= $display_now_location_name;
	        $wpc_html_display_now_time_symbol    	.= $display_now_time_symbol;
	        $wpc_html_display_now_time_temperature  .= $display_now_time_temperature;
	        $wpc_html_now_end             			.= $display_now_end;
	    } 

	    else {
	        $wpc_html_now_start           			.= $display_now_start;
	        $wpc_html_now_location_name       		.= $display_now_location_name;
	        if ($wpcloudy_current_weather =='yes') {
	        	$wpc_html_display_now_time_symbol    	.= $display_now_time_symbol;
	    	}
	        $wpc_html_now_end             			.= $display_now_end;
	    }
	     
	    if( $wpcloudy_weather ) {
	    	$wpc_html_weather .= $display_weather;
	    }
	  
	    if( $wpcloudy_date_temp !="none") {
	    	$wpc_html_today_temp_start  .= $display_today_min_max_start;
	        $wpc_html_today_temp_day  .= $display_today_min_max_day;
	        $wpc_html_today_sun       .= $display_today_sun;
	        $wpc_html_today_temp_end  .= $display_today_min_max_end;
	    }      
	      
	    if( $wpcloudy_wind || $wpcloudy_humidity || $wpcloudy_pressure || $wpcloudy_cloudiness || $wpcloudy_precipitation ) {    
	    	$wpc_html_infos_start .= '<div class="infos">';

	        if( $wpcloudy_wind ) {
	        	$wpc_html_infos_wind      .= $display_wind;
	        }
	        
	        if( $wpcloudy_humidity ) {
	        	$wpc_html_infos_humidity    .= $display_humidity;
	        } 
	        
	        if( $wpcloudy_pressure ) {
	        	$wpc_html_infos_pressure    .= $display_pressure;
	        } 
	        
	        if( $wpcloudy_cloudiness ) {
	        	$wpc_html_infos_cloudiness    .= $display_cloudiness;
	        }
	        
	        if( $wpcloudy_precipitation ) {
	        	$wpc_html_infos_precipitation   .= $display_precipitation;
	        }  
	        
	        $wpc_html_infos_end .= '</div>';  
	    };
	      
	    if( $wpcloudy_hour_forecast && !$wpcloudy_hour_forecast_nd =='' ) {    
	    	$wpc_html_hour_start .= '<div class="hours" style="border-color:'. $wpcloudy_meta_border_color .';">';      
	        $wpc_html_hour = array( $display_hours_[1], $display_hours_[2], $display_hours_[3], $display_hours_[4], $display_hours_[5] );	        
	        $wpc_html_hour_end .= '</div>';
	    } 

	    if ($wpcloudy_forecast && !$wpcloudy_forecast_nd == '' ) {  
	    	$wpc_html_forecast_start .= '<div class="forecast">';
	        $wpc_html_forecast = array( $display_forecast_[1], $display_forecast_[2], $display_forecast_[3], $display_forecast_[4], $display_forecast_[5]);
	        $wpc_html_forecast_end .= '</div>';
	    }

	    if ( isset( $_POST['wpc_param2'] ) ) {
			$wpcloudy_map = $_POST['wpc_param2'];
		    if ($wpcloudy_map =='yes') {
		        $wpc_html_map .= $display_map;
		    }
		}

	    if (isset($display_custom_css)) {
	    	$wpc_html_custom_css .= $display_custom_css;
	    }
	      
	    if (($wpcloudy_display_temp_unit == 'yes' || $wpcloudy_display_temp_unit == '1') && $wpcloudy_unit == 'metric') {
	    	$wpc_html_temp_unit_metric .= '
	    		<style>
	            	#wpc-weather.small .now .time_temperature:after,
	              	#wpc-weather .forecast .temp_max:after,
	              	#wpc-weather .forecast .temp_min:after,
	              	#wpc-weather .hours .temperature:after,
	              	#wpc-weather .today .time_temperature_max:after,
	              	#wpc-weather .today .time_temperature_min:after,
	              	#wpc-weather .now .time_temperature:after,
	              	#wpc-weather .today .time_temperature_ave:after {
		                content:"\e03e";
		                font-family: "Climacons-Font";
		                font-size: 24px;
		                margin-left: 2px;
		                vertical-align: top;
	              	}
	            </style>
	        ';
	    }
	      
	    if (($wpcloudy_display_temp_unit == 'yes' || $wpcloudy_display_temp_unit == '1') && $wpcloudy_unit == 'imperial') {
	        $wpc_html_temp_unit_imperial .= '
	        	<style>
	            	#wpc-weather.small .now .time_temperature:after,
	              	#wpc-weather .forecast .temp_max:after,
	              	#wpc-weather .forecast .temp_min:after,
	              	#wpc-weather .hours .temperature:after,
	              	#wpc-weather .today .time_temperature_max:after,
	              	#wpc-weather .today .time_temperature_min:after,
	              	#wpc-weather .now .time_temperature:after,
	              	#wpc-weather .today .time_temperature_ave:after {
		                content: "\e03f";
		                font-family: "Climacons-Font";
		                font-size: 24px;
		                margin-left: 2px;
		                vertical-align: top;
	              	}
	            </style>
	        ';
	    }
	      
	    if ($wpcloudy_display_owm_link == 'yes') {
	    	$wpc_html_owm_link .= '<div class="wpc-link-owm">'.$owm_link.'</div>';
	    }
	    if ($wpcloudy_display_last_update == 'yes') {
	    	$wpc_html_last_update .= '<div class="wpc-last-update">'.$last_update.'</div>';
	    }

	    $wpc_html_container_end .= '</div>';

		$wpc_theme_files 			= array('wp-cloudy/content-wpcloudy.php');
		$wpc_theme_files_theme1 	= array('wp-cloudy/content-wpcloudy-theme1.php');
		$wpc_theme_files_theme2 	= array('wp-cloudy/content-wpcloudy-theme2.php');
	    $wpc_exists_in_theme 		= locate_template($wpc_theme_files, false);
	    $wpc_exists_in_theme1 		= locate_template($wpc_theme_files_theme1, false);
	    $wpc_exists_in_theme2		= locate_template($wpc_theme_files_theme2, false);
	    
	    if ( $wpc_exists_in_theme != '' && $wpcloudy_skin != 'theme1' && $wpcloudy_skin != 'theme2' ) {//Bypass dans theme actif
	    	ob_start();
	    	include get_stylesheet_directory() . '/wp-cloudy/content-wpcloudy.php';
	    	$wpc_html = ob_get_clean();
	    }
	    elseif ( $wpcloudy_skin == 'theme1' ) {//Theme1 actif
	    	ob_start();
	    	if ( $wpc_exists_in_theme1 != '' ) {
		    	include get_stylesheet_directory() . '/wp-cloudy/content-wpcloudy-theme1.php';
		    	$wpc_html = ob_get_clean();
	    	} else {
	    		include dirname( __FILE__ ) . '/template/content-wpcloudy-theme1.php';
		    	$wpc_html = ob_get_clean();
	    	}
	    }
	   	elseif ( $wpcloudy_skin == 'theme2' ) {//Theme2 actif
	    	ob_start();
	    	if ( $wpc_exists_in_theme2 != '' ) {
		    	include get_stylesheet_directory() . '/wp-cloudy/content-wpcloudy-theme2.php';
		    	$wpc_html = ob_get_clean();
	    	} else {
	    		include dirname( __FILE__ ) . '/template/content-wpcloudy-theme2.php';
		    	$wpc_html = ob_get_clean();
	    	}
	    }
	    else { //Default
	    	ob_start();
	    	include ( dirname( __FILE__ ) . '/template/content-wpcloudy.php');
	    	$wpc_html = ob_get_clean();
	    }
	    
	  	$response = array();
	  	$response['weather'] = $id;
	  	$response['html'] = $wpc_html;
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
	if ( is_plugin_active( 'wp-cloudy/wpcloudy.php' ) && !isset($wpc_advanced_api_key['wpc_advanced_api_key'])) {
	    ?>
	    <div class="error notice">
	        <p><a href="<?php echo admin_url('admin.php?page=wpc-settings-admin#tab_advanced'); ?>"><?php _e( 'WP Cloudy: Please enter your own OpenWeatherMap API key to avoid limits requests.', 'wp-cloudy' ); ?></a></p>
	    </div>
	    <?php
	}
}
add_action( 'admin_notices', 'wpcloudy_notice' );
