<?php
// To prevent calling the plugin directly
if (!function_exists('add_action')) {
    echo 'Please don&rsquo;t call the plugin directly. Thanks :)';
    exit;
}
///////////////////////////////////////////////////////////////////////////////////////////////////
//OWM WEATHER Options Panel
///////////////////////////////////////////////////////////////////////////////////////////////////
function owmw_get_admin_bypass($setting)
{
    global $owmw_params;

    static $options = [];

    if (empty($options)) {
        if (is_multisite() && owmw_is_global_multisite()) {
            $options = get_site_option("owmw_option_name");
        } else {
            $options = get_option("owmw_option_name");
        }
    }
    if (isset($owmw_params[substr($setting, 5)]) && $owmw_params[substr($setting, 5)] !== false) {
        return($owmw_params[substr($setting, 5)]);
    } else {
        return $options[$setting] ?? null;
    }
}

//Get Bypass for Yes/No settings
function owmw_get_bypass_yn($bypass, $setting, $id = null)
{
    global $owmw_params;
    $opt = owmw_get_admin_bypass("owmw_" . $setting);

    if ($bypass && isset($opt) && $opt != 'nobypass') {
        return $opt == 'yes' ? 'yes' : null;
    } else {
        return get_post_meta($id ?? $owmw_params['id'], '_owmweather_' . $setting, true);
    }
}

//Get Bypass for settings
function owmw_get_bypass($bypass, $setting, $id = null)
{
    global $owmw_params;
    $opt = owmw_get_admin_bypass("owmw_" . $setting);

    if ($bypass && isset($opt) && $opt != 'nobypass') {
        return $opt;
    } else {
        return get_post_meta($id ?? $owmw_params['id'], '_owmweather_' . $setting, true);
    }
}

//Disable weather cache
function owmw_get_admin_disable_cache()
{
    return $owmw_admin_disable_cache_option['owmw_advanced_disable_cache'] ?? null;
}

//Time cache refresh
function owmw_get_admin_cache_time()
{
    return $owmw_admin_cache_time_option['owmw_advanced_cache_time'] ?? 30;
};

//API Key
function owmw_get_admin_api_key()
{
    $opt = owmw_get_admin_bypass("owmw_advanced_api_key");

    if ($opt) {
        return $opt;
    } else {
        return '46c433f6ba7dd4d29d5718dac3d7f035';
    }
}
