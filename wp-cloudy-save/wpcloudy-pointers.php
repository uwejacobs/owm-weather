<?php
defined( 'ABSPATH' ) or die( 'Please don&rsquo;t call the plugin directly. Thanks :)' );

function wpc_enqueue_pointer_script_style( $hook_suffix ) {
	
	// Assume pointer shouldn't be shown
	$enqueue_pointer_script_style = false;

	// Get array list of dismissed pointers for current user and convert it to array
	$dismissed_pointers = explode( ',', get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );

	// Check if our pointer is not among dismissed ones
	if( !in_array( 'wpc_settings_pointer', $dismissed_pointers ) ) {
		$enqueue_pointer_script_style = true;
		
		// Add footer scripts using callback function
		add_action( 'admin_print_footer_scripts', 'wpc_pointer_print_scripts' );
	}

	// Enqueue pointer CSS and JS files, if needed
	if( $enqueue_pointer_script_style ) {
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );
	}
	
}
add_action( 'admin_enqueue_scripts', 'wpc_enqueue_pointer_script_style' );

function wpc_pointer_print_scripts() {

	$pointer_content  = "<h3>WP Cloudy</h3>";
	$pointer_content .= '<p>'.__('<strong>Before starting:</strong> enter your own OpenWeatherMap API key in','wp-cloudy').' <a href="'.admin_url('options-general.php?page=wpc-settings-admin#tab_advanced').'">'. __('Advanced Settings!','wp-cloudy').'</a></p>';
	?>
	
	<script type="text/javascript">
	//<![CDATA[
	jQuery(document).ready( function($) {
		$('#menu-settings').pointer({
			content:		'<?php echo $pointer_content; ?>',
			position:		{
								edge:	'left', // arrow direction
								align:	'center' // vertical alignment
							},
			pointerWidth:	350,
			close:			function() {
								$.post( ajaxurl, {
										pointer: 'wpc_settings_pointer', // pointer ID
										action: 'dismiss-wp-pointer'
								});
							}
		}).pointer('open');
	});
	//]]>
	</script>

<?php
}