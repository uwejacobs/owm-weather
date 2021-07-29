<?php
// To prevent calling the plugin directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Please don&rsquo;t call the plugin directly. Thanks :)';
	exit;
}
///////////////////////////////////////////////////////////////////////////////////////////////////
//WPC Options Panel
///////////////////////////////////////////////////////////////////////////////////////////////////
function wpc_get_admin_bypass($setting) {
    global $wpc_params;

	static $options = [];

	if (empty($options)) {
    	$options = get_option("wpc_option_name");
    }

    if (!empty($wpc_params[substr($setting, 4)])) {
        return($wpc_params[substr($setting, 4)]);
    } else {
    	return $options[$setting] ?? null;
    }
}

//Get Bypass for Yes/No settings
function wpc_get_bypass_yn($bypass, $setting, $id = null) {
    $opt = wpc_get_admin_bypass("wpc_" . $setting);

	if ($bypass && isset($opt) && $opt != 'nobypass') {
		return $opt == 'yes' ? 'yes' : null;
	} else {
    	return get_post_meta($id ?? $_POST['wpc_params']['id'], '_wpcloudy_' . $setting, true);
	}
}

//Get Bypass for settings
function wpc_get_bypass($bypass, $setting, $id = null) {
    $opt = wpc_get_admin_bypass("wpc_" . $setting);

	if ($bypass && isset($opt) && $opt != 'nobypass') {
		return $opt;
	} else {
    	return get_post_meta($id ?? $_POST['wpc_params']['id'], '_wpcloudy_' . $setting, true);
	}
}

//Disables weather cache
function wpc_get_admin_disable_cache() {
    return $wpc_admin_disable_cache_option['wpc_advanced_disable_cache'] ?? null;
}

//Time cache refresh
function wpc_get_admin_cache_time() {
    return $wpc_admin_cache_time_option['wpc_advanced_cache_time'] ?? 30;
};

//API Key
function wpc_get_admin_api_key() {
    $opt = wpc_get_admin_bypass("wpc_advanced_api_key");

	if ($opt) {
		return $opt;
	} else {
    	return '46c433f6ba7dd4d29d5718dac3d7f035';
	}
}
