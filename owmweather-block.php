<?php

add_action('init', 'owmw_init_block_editor_assets', 10, 0);

function owmw_init_block_editor_assets()
{
    $assets = array();

    $assets_file = dirname(__FILE__) .  'owmweather-index-assets.php';

    if (file_exists($assets_file)) {
        $assets = include($assets_file);
    }

    $assets = wp_parse_args($assets, array(
        'src' => plugins_url('js/block-editor-index.js', __FILE__),
        'dependencies' => array(
            'wp-api-fetch',
            'wp-components',
            'wp-compose',
            'wp-blocks',
            'wp-element',
            'wp-i18n',
        ),
        'version' => OWM_WEATHER_VERSION,
    ));

    wp_register_script(
        'owm-weather-block-editor',
        $assets['src'],
        $assets['dependencies'],
        $assets['version']
    );

    wp_set_script_translations(
        'owm-weather'
    );

    register_block_type(
        'owm-weather/weather-selector',
        array(
            'editor_script' => 'owm-weather-block-editor',
        )
    );

    $weather_local = array_map(
        function ($w) {
                return array(
                        'id' => $w->ID,
                        'title' => $w->post_title,
                );
        },
        owmw_block_editor_posts_find()
    );
    
    $weather_shared = array_map(
        function ($w) {
                return array(
                        'id' => "m" . $w->ID,
                        'title' => $w->post_title . " (shared)",
                );
        },
        owmw_block_editor_posts_find_network_share()
    );

    $weather = array_merge($weather_local, $weather_shared);
    usort($weather, "owmw_cmp_by_title");

    wp_add_inline_script(
        'owm-weather-block-editor',
        sprintf(
            'window.owmw_be = {weather:%s};',
            json_encode($weather)
        ),
        'before'
    );
}

function owmw_block_editor_posts_find()
{
    $defaults = array(
        'post_type' => 'owm-weather',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'offset' => 0,
    );

    $args = wp_parse_args($defaults);

    $q = new WP_Query();
    $posts = $q->query($args);

    $objs = array();

    foreach ((array) $posts as $post) {
            $objs[] = $post;
    }

    return $objs;
}

function owmw_block_editor_posts_find_network_share()
{
    $objs = array();
    
    if (owmw_is_global_multisite() && !is_main_site()) {
        switch_to_blog(get_main_site_id());
        
        $defaults = array(
            'post_type' => 'owm-weather',
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'offset' => 0,
            'meta_key' => '_owmweather_network_share',
            'meta_value' => 'yes',
            'meta_compare' => '=',
        );

        $args = wp_parse_args($defaults);

        $q = new WP_Query();
        $posts = $q->query($args);


        foreach ((array) $posts as $post) {
                $objs[] = $post;
        }

        restore_current_blog();
    }

    return $objs;
}

function owmw_cmp_by_title($a, $b)
{
    if ($a["title"] == $b["title"]) {
        return 0;
    }
    return ($a["title"] < $b["title"]) ? -1 : 1;
}
