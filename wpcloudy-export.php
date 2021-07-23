<?php
///////////////////////////////////////////////////////////////////////////////////////////////////
//Import / Exports settings
///////////////////////////////////////////////////////////////////////////////////////////////////

//Export WP Cloudy 2 Settings in JSON
function wpc_export_settings() {
    if( empty( $_POST['wpc_action'] ) || 'wpc_export_settings' != $_POST['wpc_action'] )
        return;
    if( ! wp_verify_nonce( $_POST['wpc_export_nonce'], 'wpc_export_nonce' ) )
        return;
    if( ! current_user_can( 'manage_options' ) )
        return;

    $wpc_settings["wpc_option_name"]                = get_option( 'wpc_option_name' );
    $wpc_settings["wpc_dashboard_widget_option"]    = get_option( 'wpc_dashboard_widget_option' );
    $data = json_encode($wpc_settings);
    
    ignore_user_abort( true );
    nocache_headers();
    //header( 'Content-Type: application/json; charset=utf-8' );
    header("Content-type: application/octet-stream");
    header( 'Content-Disposition: attachment; filename=wpc-settings-export-' . date( 'Y-m-d' ) . '.json' );
    header( "Expires: 0" );
    header('Content-Length: ' . strlen($data));
    echo $data;
    exit;
}
add_action( 'admin_init', 'wpc_export_settings' );

//Import WP Cloudy 2 Settings from JSON
function wpc_import_settings() {
    if( empty( $_POST['wpc_action'] ) || 'wpc_import_settings' != $_POST['wpc_action'] )
        return;
    if( ! wp_verify_nonce( $_POST['wpc_import_nonce'], 'wpc_import_nonce' ) )
        return;
    if( ! current_user_can( 'manage_options' ) )
        return;

    $wpc_import_file = $_FILES['wpc_import_file']['tmp_name'];
    if( empty( $wpc_import_file ) ) {
        add_action( 'admin_notices', 'no_file_selected_action' );
        return;
    }
    if( substr_compare($_FILES['wpc_import_file']['name'], ".json", -5, 5, true) != 0 ) {
        add_action( 'admin_notices', 'no_json_file_selected_action' );
        return;
    }

    $wpc_settings = (array) json_decode( file_get_contents( $wpc_import_file ), true );

    update_option( 'wpc_option_name', $wpc_settings["wpc_option_name"] ); 
    update_option( 'wpc_dashboard_widget_option', $wpc_settings["wpc_dashboard_widget_option"] ); 
     
    wp_safe_redirect( admin_url( 'options-general.php?page=wpc-settings-admin#tab_export' ) );
    exit;
}
add_action( 'admin_init', 'wpc_import_settings' );

//Reset WP Cloudy 2 Settings
function wpc_reset_settings() {
    if( empty( $_POST['wpc_action'] ) || 'wpc_reset_settings' != $_POST['wpc_action'] )
        return;
    if( ! wp_verify_nonce( $_POST['wpc_reset_nonce'], 'wpc_reset_nonce' ) )
        return;
    if( ! current_user_can( 'manage_options' ) )
        return;

    ob_start();

    $wpc_settings = null;

    update_option( 'wpc_option_name', $wpc_settings["wpc_option_name"] ); 
    update_option( 'wpc_dashboard_widget_option', $wpc_settings["wpc_dashboard_widget_option"] ); 
     
    wp_safe_redirect( admin_url( 'options-general.php?page=wpc-settings-admin#tab_export' ) ); exit;

    ob_end_flush();
}
add_action( 'admin_init', 'wpc_reset_settings' );
?>
