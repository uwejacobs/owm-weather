<?php
///////////////////////////////////////////////////////////////////////////////////////////////////
//Import / Exports settings
///////////////////////////////////////////////////////////////////////////////////////////////////

//Export OWM Weather Settings in JSON
function wow_export_settings() {
    if( empty( $_POST['wow_action'] ) || 'wow_export_settings' != $_POST['wow_action'] )
        return;
    if( ! wp_verify_nonce( $_POST['wow_export_nonce'], 'wow_export_nonce' ) )
        return;
    if( ! current_user_can( 'manage_options' ) )
        return;

    $wow_settings["wow_option_name"]                = get_option( 'wow_option_name' );
    $wow_settings["wow_dashboard_widget_option"]    = get_option( 'wow_dashboard_widget_option' );
    $data = json_encode($wow_settings);
    
    ignore_user_abort( true );
    nocache_headers();
    //header( 'Content-Type: application/json; charset=utf-8' );
    header("Content-type: application/octet-stream");
    header( 'Content-Disposition: attachment; filename=wow-settings-export-' . date( 'Y-m-d' ) . '.json' );
    header( "Expires: 0" );
    header('Content-Length: ' . strlen($data));
    echo $data;
    exit;
}
add_action( 'admin_init', 'wow_export_settings' );

//Import OWM Weather Settings from JSON
function wow_import_settings() {
    if( empty( $_POST['wow_action'] ) || 'wow_import_settings' != $_POST['wow_action'] )
        return;
    if( ! wp_verify_nonce( $_POST['wow_import_nonce'], 'wow_import_nonce' ) )
        return;
    if( ! current_user_can( 'manage_options' ) )
        return;

    $wow_import_file = $_FILES['wow_import_file']['tmp_name'];
    if( empty( $wow_import_file ) ) {
        add_action( 'admin_notices', 'no_file_selected_action' );
        return;
    }
    if( substr_compare($_FILES['wow_import_file']['name'], ".json", -5, 5, true) != 0 ) {
        add_action( 'admin_notices', 'no_json_file_selected_action' );
        return;
    }

    $wow_settings = (array) json_decode( file_get_contents( $wow_import_file ), true );

    update_option( 'wow_option_name', $wow_settings["wow_option_name"] ); 
    update_option( 'wow_dashboard_widget_option', $wow_settings["wow_dashboard_widget_option"] ); 
     
    wp_safe_redirect( admin_url( 'options-general.php?page=wow-settings-admin#tab_export' ) );
    exit;
}
add_action( 'admin_init', 'wow_import_settings' );

//Reset OWM Weather Settings
function wow_reset_settings() {
    if( empty( $_POST['wow_action'] ) || 'wow_reset_settings' != $_POST['wow_action'] )
        return;
    if( ! wp_verify_nonce( $_POST['wow_reset_nonce'], 'wow_reset_nonce' ) )
        return;
    if( ! current_user_can( 'manage_options' ) )
        return;

    ob_start();

    $wow_settings = null;

    update_option( 'wow_option_name', $wow_settings["wow_option_name"] ); 
    update_option( 'wow_dashboard_widget_option', $wow_settings["wow_dashboard_widget_option"] ); 
     
    wp_safe_redirect( admin_url( 'options-general.php?page=wow-settings-admin#tab_export' ) ); exit;

    ob_end_flush();
}
add_action( 'admin_init', 'wow_reset_settings' );
?>
