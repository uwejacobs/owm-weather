<?php
///////////////////////////////////////////////////////////////////////////////////////////////////
//Import / Exports settings
///////////////////////////////////////////////////////////////////////////////////////////////////

//Export OWM Weather Settings
function owmw_export_settings() {
    if( empty( $_POST['owmw_action'] ) || 'owmw_export_settings' != $_POST['owmw_action'] )
        return;
    if( ! wp_verify_nonce( $_POST['owmw_export_nonce'], 'owmw_export_nonce' ) )
        return;
    if( ! current_user_can( 'manage_options' ) )
        return;

    $data["owmw_option_name"]                = get_option( 'owmw_option_name' );
    $data["owmw_dashboard_widget_option"]    = get_option( 'owmw_dashboard_widget_option' );
    $charset = get_option( 'blog_charset' );
    
    ignore_user_abort( true );
    nocache_headers();
    header('Content-Type: application/octet-stream; charset=' . $charset);
    header('Content-Disposition: attachment; filename=owmw-settings-export-' . date( 'Y-m-d' ) . '.dat' );
    header('Expires: 0');
    echo serialize($data);
    die();
}
add_action( 'admin_init', 'owmw_export_settings' );

//Import OWM Weather Settings
function owmw_import_settings() {
    if( empty( $_POST['owmw_action'] ) || 'owmw_import_settings' != $_POST['owmw_action'] )
        return;
    if( ! wp_verify_nonce( $_POST['owmw_import_nonce'], 'owmw_import_nonce' ) )
        return;
    if( ! current_user_can( 'manage_options' ) )
        return;

    // Make sure WordPress upload support is loaded.
    if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
    }
    
    $overrides   = array( 'test_form' => false, 'test_type' => false, 'mimes' => array('dat' => 'text/plain') );
    $owmw_import_file = wp_handle_upload($_FILES['owmw_import_file'], $overrides);
    if (isset($owmw_import_file['error'])) {
        add_action( 'admin_notices', 'owmw_no_dat_file_selected_action' );
        return;
    }
    if (!file_exists($owmw_import_file['file'])) {
        add_action( 'admin_notices', 'owmw_no_file_selected_action' );
        return;
    }

    $raw = file_get_contents($owmw_import_file['file']);
    $owmw_settings = @unserialize($raw);

   	if (!empty($owmw_settings["owmw_option_name"])) {
        foreach($owmw_settings["owmw_option_name"] as $k => &$v) {
       		if (!in_array($v, array('yes', 'nobypass'))) {
   	        	$v = owmw_sanitize_validate_field(substr($k, 5), $v);
           	}
        }
   	}
    update_option( 'owmw_option_name', $owmw_settings["owmw_option_name"] ); 

   	if (!empty($owmw_settings["owmw_dashboard_widget_option"])) {
        foreach($owmw_settings["owmw_dashboard_widget_option"] as $k => &$v) {
        	$v = owmw_sanitize_validate_field($k, $v);
        }
   	}
    update_option( 'owmw_dashboard_widget_option', $owmw_settings["owmw_dashboard_widget_option"] ); 
     
    wp_safe_redirect( admin_url( 'options-general.php?page=owmw-settings-admin#tab_export' ) );
    exit;
}
add_action( 'admin_init', 'owmw_import_settings' );

//Reset OWM Weather Settings
function owmw_reset_settings() {
    if( empty( $_POST['owmw_action'] ) || 'owmw_reset_settings' != $_POST['owmw_action'] )
        return;
    if( ! wp_verify_nonce( $_POST['owmw_reset_nonce'], 'owmw_reset_nonce' ) )
        return;
    if( ! current_user_can( 'manage_options' ) )
        return;

    ob_start();

    update_option( 'owmw_option_name', null); 
    update_option( 'owmw_dashboard_widget_option', null); 
     
    wp_safe_redirect( admin_url( 'options-general.php?page=owmw-settings-admin#tab_export' ) ); exit;

    ob_end_flush();
}
add_action( 'admin_init', 'owmw_reset_settings' );

function owmw_no_file_selected_action() {
    $message = esc_html__( 'Please upload a file to import', 'owm-weather' );
    add_settings_error('no_file_selected', '', $message, 'error');
}

function owmw_no_dat_file_selected_action() {
        $message = esc_html__( 'Please upload a valid .dat file', 'owm-weather' );
        add_settings_error('no_dat_file_selected', '', $message, 'error');
}
?>
