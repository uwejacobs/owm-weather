<?php
// To prevent calling the plugin directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Please don&rsquo;t call the plugin directly. Thanks :)';
	exit;
}
///////////////////////////////////////////////////////////////////////////////////////////////////
//WP OWM WEATHER Options Panel
///////////////////////////////////////////////////////////////////////////////////////////////////
function wow_get_admin_bypass($setting) {
    global $wow_params;

	static $options = [];

	if (empty($options)) {
    	$options = get_option("wow_option_name");
    }
    if (isset($wow_params[substr($setting, 4)]) && $wow_params[substr($setting, 4)] !== false) {
        return($wow_params[substr($setting, 4)]);
    } else {
    	return $options[$setting] ?? null;
    }
}

//Get Bypass for Yes/No settings
function wow_get_bypass_yn($bypass, $setting, $id = null) {
    $opt = wow_get_admin_bypass("wow_" . $setting);

	if ($bypass && isset($opt) && $opt != 'nobypass') {
		return $opt == 'yes' ? 'yes' : null;
	} else {
    	return get_post_meta($id ?? $_POST['wow_params']['id'], '_wpowmweather_' . $setting, true);
	}
}

//Get Bypass for settings
function wow_get_bypass($bypass, $setting, $id = null) {
    $opt = wow_get_admin_bypass("wow_" . $setting);

	if ($bypass && isset($opt) && $opt != 'nobypass') {
		return $opt;
	} else {
    	return get_post_meta($id ?? $_POST['wow_params']['id'], '_wpowmweather_' . $setting, true);
	}
}

//Disables weather cache
function wow_get_admin_disable_cache() {
    return $wow_admin_disable_cache_option['wow_advanced_disable_cache'] ?? null;
}

//Time cache refresh
function wow_get_admin_cache_time() {
    return $wow_admin_cache_time_option['wow_advanced_cache_time'] ?? 30;
};

//API Key
function wow_get_admin_api_key() {
    $opt = wow_get_admin_bypass("wow_advanced_api_key");

	if ($opt) {
		return $opt;
	} else {
    	return '46c433f6ba7dd4d29d5718dac3d7f035';
	}
}
