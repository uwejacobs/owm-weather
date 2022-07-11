<?php

add_action( 'init', 'owmw_init_block_editor_assets', 10, 0 );

function owmw_init_block_editor_assets() {
	$assets = array();

	$assets_file = dirname( __FILE__ ) .  'owmweather-index-assets.php';

	if ( file_exists( $assets_file ) ) {
		$assets = include( $assets_file );
	}

	$assets = wp_parse_args( $assets, array(
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
	) );

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

    $weather = array_map(
            function ( $w ) {
                    return array(
                            'id' => $w->ID,
                            'title' => $w->post_title,
                    );
            },
            owmw_block_editor_posts_find( array(
                    'posts_per_page' => 20,
            ) )
    );

	wp_add_inline_script(
		'owm-weather-block-editor',
		sprintf(
			'window.owmw_be = {weather:%s};',
			json_encode( $weather )
		),
		'before'
	);

}

function owmw_block_editor_posts_find( $args = '' ) {
	$defaults = array(
		'post_status' => 'any',
		'posts_per_page' => -1,
		'offset' => 0,
		'orderby' => 'ID',
		'order' => 'ASC',
	);

	$args = wp_parse_args( $args, $defaults );

	$args['post_type'] = 'owm-weather';
	$q = new WP_Query();
    $posts = $q->query( $args );

	$objs = array();

	foreach ( (array) $posts as $post ) {
			$objs[] = $post;
	}

	return $objs;
}

