<?php
///////////////////////////////////////////////////////////////////////////////////////////////////
//Import / Exports settings
///////////////////////////////////////////////////////////////////////////////////////////////////

//Export OWM Weather Settings in JSON
function owmw_export_settings() {
    if( empty( $_POST['owmw_action'] ) || 'owmw_export_settings' != $_POST['owmw_action'] )
        return;
    if( ! wp_verify_nonce( $_POST['owmw_export_nonce'], 'owmw_export_nonce' ) )
        return;
    if( ! current_user_can( 'manage_options' ) )
        return;

    $owmw_settings["owmw_option_name"]                = get_option( 'owmw_option_name' );
    $owmw_settings["owmw_dashboard_widget_option"]    = get_option( 'owmw_dashboard_widget_option' );
    $data = json_encode($owmw_settings);
    
    ignore_user_abort( true );
    nocache_headers();
    //header( 'Content-Type: application/json; charset=utf-8' );
    header("Content-type: application/octet-stream");
    header( 'Content-Disposition: attachment; filename=owmw-settings-export-' . date( 'Y-m-d' ) . '.json' );
    header( "Expires: 0" );
    header('Content-Length: ' . strlen($data));
    echo $data;
    exit;
}
add_action( 'admin_init', 'owmw_export_settings' );

//Import OWM Weather Settings from JSON
function owmw_import_settings() {
    if( empty( $_POST['owmw_action'] ) || 'owmw_import_settings' != $_POST['owmw_action'] )
        return;
    if( ! wp_verify_nonce( $_POST['owmw_import_nonce'], 'owmw_import_nonce' ) )
        return;
    if( ! current_user_can( 'manage_options' ) )
        return;

    $owmw_import_file = $_FILES['owmw_import_file']['tmp_name'];
    if( empty( $owmw_import_file ) ) {
        add_action( 'admin_notices', 'owmw_no_file_selected_action' );
        return;
    }
    if( substr_compare($_FILES['owmw_import_file']['name'], ".json", -5, 5, true) != 0 ) {
        add_action( 'admin_notices', 'owmw_no_json_file_selected_action' );
        return;
    }

    $owmw_settings = (array) json_decode( file_get_contents( $owmw_import_file ), true );

    update_option( 'owmw_option_name', $owmw_settings["owmw_option_name"] ); 
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

    $owmw_settings = null;

    update_option( 'owmw_option_name', $owmw_settings["owmw_option_name"] ); 
    update_option( 'owmw_dashboard_widget_option', $owmw_settings["owmw_dashboard_widget_option"] ); 
     
    wp_safe_redirect( admin_url( 'options-general.php?page=owmw-settings-admin#tab_export' ) ); exit;

    ob_end_flush();
}
add_action( 'admin_init', 'owmw_reset_settings' );

function owmw_no_file_selected_action() {
    $message = __( 'Please upload a file to import', 'owm-weather' );
    add_settings_error('no_file_selected', '', $message, 'error');
}

function owmw_no_json_file_selected_action() {
        $message = __( 'Please upload a valid .json file', 'owm-weather' );
        add_settings_error('no_json_file_selected', '', $message, 'error');
}

?>
