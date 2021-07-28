<?php
// To prevent calling the plugin directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Please don&rsquo;t call the plugin directly. Thanks :)';
    exit;
}

class wpc_options
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page() {
        // This page will be under "Settings"
    $wpc_help_tab = add_options_page(
            'Settings Admin',
            'WP Cloudy',
            'manage_options',
            'wpc-settings-admin',
            array( $this, 'create_admin_page' )
        );
    add_action('load-'.$wpc_help_tab, 'wpc_help_tab');

    }



    /**
     * Options page callback
     */
    public function create_admin_page() {

        // Set class property
        $this->options = get_option( 'wpc_option_name' );
        ?>
        <?php $wpc_info_version = get_plugin_data( plugin_dir_path( __FILE__ ).'/wpcloudy.php'); ?>

        <div id="wpcloudy-header">
			<div id="wpcloudy-clouds">
				<h3>
					<?php _e( 'WP Cloudy 2', 'wp-cloudy' ); ?>
				</h3>
				<span class="wpc-info-version"><?php print_r($wpc_info_version['Version']); ?></span>
				<div id="wpcloudy-notice">
					<div class="small">
						<!--?php _e( 'Do you like WP Cloudy 2? Don\'t forget to rate it 5 stars!', 'wp-cloudy' ); ?-->

                        <!--div class="wporg-ratings rating-stars">
                            <a href="//wordpress.org/support/view/plugin-reviews/wp-cloudy?rate=1#postform" data-rating="1" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#e6b800 !important;"></span></a>
                            <a href="//wordpress.org/support/view/plugin-reviews/wp-cloudy?rate=2#postform" data-rating="2" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#e6b800 !important;"></span></a>
                            <a href="//wordpress.org/support/view/plugin-reviews/wp-cloudy?rate=3#postform" data-rating="3" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#e6b800 !important;"></span></a>
                            <a href="//wordpress.org/support/view/plugin-reviews/wp-cloudy?rate=4#postform" data-rating="4" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#e6b800 !important;"></span></a>
                            <a href="//wordpress.org/support/view/plugin-reviews/wp-cloudy?rate=5#postform" data-rating="5" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#e6b800 !important;"></span></a>
                        </div-->

                        <form action="https://www.paypal.com/donate" method="post" target="_top">
						<span class="dashicons dashicons-wordpress"></span>
						<?php _e( 'Do you like WP Cloudy 2? Consider a donation.', 'wp-cloudy' ); ?>
                        <input type="hidden" name="hosted_button_id" value="PQDNJGKMLHAFU" />
                        <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
                        <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
                        </form>

                        <!--script>
                            jQuery(document).ready( function($) {
                                $('.rating-stars').find('a').hover(
                                    function() {
                                        $(this).nextAll('a').children('span').removeClass('dashicons-star-filled').addClass('dashicons-star-empty');
                                        $(this).prevAll('a').children('span').removeClass('dashicons-star-empty').addClass('dashicons-star-filled');
                                        $(this).children('span').removeClass('dashicons-star-empty').addClass('dashicons-star-filled');
                                    }, function() {
                                        var rating = $('input#rating').val();
                                        if (rating) {
                                            var list = $('.rating-stars a');
                                            list.children('span').removeClass('dashicons-star-filled').addClass('dashicons-star-empty');
                                            list.slice(0, rating).children('span').removeClass('dashicons-star-empty').addClass('dashicons-star-filled');
                                        }
                                    }
                                );
                            });
                        </script-->
					</div>
				</div>
			</div>
		</div>

        <?php
            function wpc_settings_admin_export() {
                ?>
                <div id="wpc_export_form" class="metabox-holder">
                    <div class="postbox">
                        <h3><span><?php _e( 'Export Settings', 'wp-cloudy' ); ?></span></h3>
                        <div class="inside">
                            <p><?php _e( 'Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.', 'wp-cloudy' ); ?></p>
                            <form method="post">
                                <p><input type="hidden" name="wpc_action" value="wpc_export_settings" /></p>
                                <p>
                                    <?php wp_nonce_field( 'wpc_export_nonce', 'wpc_export_nonce' ); ?>
                                    <?php submit_button( __( 'Export', 'wp-cloudy' ), 'secondary', 'submit', false ); ?>
                                </p>
                            </form>
                        </div><!-- .inside -->
                    </div><!-- .postbox -->

                    <div class="postbox">
                        <h3><span><?php _e( 'Import Settings', 'wp-cloudy' ); ?></span></h3>
                        <div class="inside">
                            <p><?php _e( 'Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'wp-cloudy' ); ?></p>
                            <form method="post" enctype="multipart/form-data">
                                <p>
                                    <input type="file" name="wpc_import_file"/>
                                </p>
                                <p>
                                    <input type="hidden" name="wpc_action" value="wpc_import_settings" />
                                    <?php wp_nonce_field( 'wpc_import_nonce', 'wpc_import_nonce' ); ?>
                                    <?php submit_button( __( 'Import', 'wp-cloudy' ), 'secondary', 'submit', false ); ?>
                                </p>
                            </form>
                        </div><!-- .inside -->
                    </div><!-- .postbox -->

                    <div class="postbox">
                        <h3><span><?php _e( 'Reset Settings', 'wp-cloudy' ); ?></span></h3>
                        <div class="inside">
                            <p><?php _e( 'Reset all WP Cloudy global settings. It will not delete your weathers and their indivuals settings.', 'wp-cloudy' ); ?></p>
                            <form method="post" enctype="multipart/form-data">
                                <p>
                                    <input type="hidden" name="wpc_action" value="wpc_reset_settings" />
                                    <?php wp_nonce_field( 'wpc_reset_nonce', 'wpc_reset_nonce' ); ?>
                                    <?php submit_button( __( 'Reset settings', 'wp-cloudy' ), 'secondary', 'submit', false ); ?>
                                </p>
                            </form>
                        </div><!-- .inside -->
                    </div><!-- .postbox -->
                </div><!-- .metabox-holder -->
                <?php

            }
        ?>

        <?php wpc_settings_admin_export(); ?>

        <form method="post" action="options.php" class="wpcloudy-settings">
            <?php settings_fields( 'wpc_cloudy_option_group' ); ?>

            <div id="wpcloudy-tabs">
                <h2 class="nav-tab-wrapper hide-if-no-js">
                	<ul>
						<li><a href="#tab_advanced" class="nav-tab"><?php _e( 'System', 'wp-cloudy' ); ?></a></li>
						<li><a href="#tab_basic" class="nav-tab"><?php _e( 'Basic', 'wp-cloudy' ); ?></a></li>
						<li><a href="#tab_display" class="nav-tab"><?php _e( 'Display', 'wp-cloudy' ); ?></a></li>
						<li><a href="#tab_layout" class="nav-tab"><?php _e( 'Layout', 'wp-cloudy' ); ?></a></li>
						<li><a href="#tab_map" class="nav-tab"><?php _e( 'Map', 'wp-cloudy' ); ?></a></li>
                        <li><a href="#tab_export" class="nav-tab"><?php _e( 'Import/Export/Reset', 'wp-cloudy' ); ?></a></li>
						<!--li><a href="#tab_support" class="nav-tab"><?php _e( 'Support', 'wp-cloudy' ); ?></a></li bugbug-->
                	</ul>
				</h2>

				<div id="wpcloudy-tabs-settings">
					<div class="wpc-tab" id="tab_advanced"><?php do_settings_sections( 'wpc-settings-admin-advanced' ); ?></div>
					<div class="wpc-tab" id="tab_basic"><?php do_settings_sections( 'wpc-settings-admin-basic' ); ?></div>
					<div class="wpc-tab" id="tab_display"><?php do_settings_sections( 'wpc-settings-admin-display' ); ?></div>
					<div class="wpc-tab" id="tab_layout"><?php do_settings_sections( 'wpc-settings-admin-layout' ); ?></div>
					<div class="wpc-tab" id="tab_map"><?php do_settings_sections( 'wpc-settings-admin-map' ); ?></div>
                    <div class="wpc-tab" id="tab_export"></div>
					<!--div class="wpc-tab" id="tab_support"><?php //bugbug do_settings_sections( 'wpc-settings-admin-support' ); ?></div bugbug-->
				</div>
            </div>
            <script>jQuery("#wpc_export_form").detach().appendTo('#tab_export')</script>
             <?php submit_button( __( 'Save changes', 'wp-cloudy' ), 'primary', 'submit', false ); ?>
        </form>

        <div class="wpcloudy-sidebar">
        	<div id="wpcloudy-cache" class="wpcloudy-module wpcloudy-inactive" style="height: 177px;">
				<h3><?php _e('WP Cloudy cache','wp-cloudy'); ?></h3>
				<div class="wpcloudy-module-description">
					<div class="module-image">
						<div class="dashicons dashicons-trash"></div>
						<p><span class="module-image-badge"><?php _e('cache system','wp-cloudy'); ?></span></p>
					</div>

					<p><?php _e('Click this button to refresh weather cache.','wp-cloudy'); ?></p>

	            	<?php
						function wpc_clear_all_cache() {
					    	if (isset($_GET['wpc_clear_all_cache_nonce'])) {
						?>
						<div class="wpcloudy-module-actions">
							<p>
							    <a href="<?php print wp_nonce_url(admin_url('options-general.php?page=wpc-settings-admin'), 'wpc_clear_all_cache_action', 'wpc_clear_all_cache_nonce');?>"
							        class="button button-secondary">
							        <?php esc_html_e('Clear cache!', 'wp-cloudy');?>
								</a>
							</p>
						</div>

						<?php

					    } else {

						?>
						<div class="wpcloudy-module-actions">
						    <a href="<?php print wp_nonce_url(admin_url('options-general.php?page=wpc-settings-admin'), 'wpc_clear_all_cache_action', 'wpc_clear_all_cache');?>"
						        class="button button-secondary">
						        <?php esc_html_e('Clear cache!', 'wp-cloudy');?>
							</a>
						</div>

						<?php
							global $wpdb;
							$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_myweather%' ");
							$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_myweather%' ");
						}
					};
					?>
					<?php echo wpc_clear_all_cache(); ?>
				</div>
			</div>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init() {
        register_setting(
            'wpc_cloudy_option_group', // Option group
            'wpc_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

		//BASIC SECTION============================================================================
		add_settings_section(
            'wpc_setting_section_basic', // ID
            __("Basic settings","wp-cloudy"), // Title
            array( $this, 'print_section_info_basic' ), // Callback
            'wpc-settings-admin-basic' // Page
        );

        add_settings_field(
            'wpc_unit', // ID
           __("Bypass measurement system?","wp-cloudy"), // Title
            array( $this, 'wpc_basic_unit_callback' ), // Callback
            'wpc-settings-admin-basic', // Page
            'wpc_setting_section_basic' // Section
        );

        add_settings_field(
            'wpc_time_format', // ID
           __("Bypass time format?","wp-cloudy"), // Title
            array( $this, 'wpc_basic_time_format_callback' ), // Callback
            'wpc-settings-admin-basic', // Page
            'wpc_setting_section_basic' // Section
        );

        add_settings_field(
            'wpc_custom_timezone', // ID
           __("Bypass custom timezone?","wp-cloudy"), // Title
            array( $this, 'wpc_basic_custom_timezone_callback' ), // Callback
            'wpc-settings-admin-basic', // Page
            'wpc_setting_section_basic' // Section
        );

        add_settings_field(
            'wpc_owm_language', // ID
           __("Bypass OpenWeatherMap language?","wp-cloudy"), // Title
            array( $this, 'wpc_basic_owm_language_callback' ), // Callback
            'wpc-settings-admin-basic', // Page
            'wpc_setting_section_basic' // Section
        );

		//DISPLAY SECTION==========================================================================
        add_settings_section(
            'wpc_setting_section_display', // ID
            __("Display settings","wp-cloudy"), // Title
            array( $this, 'print_section_info_display' ), // Callback
            'wpc-settings-admin-display' // Page
        );

        add_settings_field(
            'wpc_current_city_name', // ID
            __("Current City Name?","wp-cloudy"), // Title
            array( $this, 'wpc_display_current_city_name_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section
        );

        add_settings_field(
            'wpc_current_weather_symbol', // ID
            __("Current weather symbol?","wp-cloudy"), // Title
            array( $this, 'wpc_display_current_weather_symbol_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section
        );

		add_settings_field(
            'wpc_current_temperature', // ID
			__("Current temperature?","wp-cloudy"), // Title
            array( $this, 'wpc_display_current_temperature_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section
        );

		add_settings_field(
            'wpc_current_weather_description', // ID
            __("Current short condition?","wp-cloudy"), // Title
            array( $this, 'wpc_display_weather_description_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section
        );

        add_settings_field(
            'wpc_display_temperature_unit', // ID
            __("Temperature unit (C / F)?","wp-cloudy"), // Title
            array( $this, 'wpc_display_date_temp_unit_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section
        );

		add_settings_field(
            'wpc_today_date_format', // ID
            __("Date?","wp-cloudy"), // Title
            array( $this, 'wpc_display_today_date_format_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section
        );

		add_settings_field(
            'wpc_sunrise_sunset', // ID
            __("Sunrise + sunset?","wp-cloudy"), // Title
            array( $this, 'wpc_display_sunrise_sunset_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section
        );

		add_settings_field(
            'wpc_moonrise_moonset', // ID
            __("Moonrise + moonset?","wp-cloudy"), // Title
            array( $this, 'wpc_display_moonrise_moonset_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section
        );

		add_settings_field(
            'wpc_wind', // ID
            __("Wind?","wp-cloudy"), // Title
            array( $this, 'wpc_display_wind_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section
        );

        add_settings_field(
            'wpc_wind_unit', // ID
            __("Wind unit?","wp-cloudy"), // Title
            array( $this, 'wpc_display_wind_unit_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section
        );

		add_settings_field(
            'wpc_humidity', // ID
            __("Humidity?","wp-cloudy"), // Title
            array( $this, 'wpc_display_humidity_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section
        );

		add_settings_field(
            'wpc_pressure', // ID
           __("Pressure?","wp-cloudy"), // Title
            array( $this, 'wpc_display_pressure_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section
        );

		add_settings_field(
            'wpc_cloudiness', // ID
            __("Cloudiness?","wp-cloudy"), // Title
            array( $this, 'wpc_display_cloudiness_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section
        );

        add_settings_field(
            'wpc_precipitation', // ID
            __("Precipitation?","wp-cloudy"), // Title
            array( $this, 'wpc_display_precipitation_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section
        );

        add_settings_field(
            'wpc_alerts', // ID
            __("Alerts?","wp-cloudy"), // Title
            array( $this, 'wpc_display_alerts_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section
        );

        add_settings_field(
            'wpc_alerts_button_color', // ID
            __("Alerts Button Color?","wp-cloudy"), // Title
            array( $this, 'wpc_display_alerts_button_color_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section
        );

        add_settings_field(
            'wpc_hours_forecast_no', // ID
            __("Number of hours forecast?","wp-cloudy"), // Title
            array( $this, 'wpc_display_hour_forecast_no_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section
        );

        add_settings_field(
            'wpc_forecast_no', // ID
            __("Number of days forecast","wp-cloudy"), // Title
            array( $this, 'wpc_display_forecast_no_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section
        );

        add_settings_field(
            'wpc_forecast_precipitations', // ID
            __("Precipitations forecast?","wp-cloudy"), // Title
            array( $this, 'wpc_display_forecast_precipitations_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section
        );

		add_settings_field(
            'wpc_display_length_days_names', // ID
			__("Length name days:","wp-cloudy"), // Title
            array( $this, 'wpc_display_display_length_days_names_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section
        );

        add_settings_field(
            'wpc_owm_link', // ID
			__("Link to OpenWeatherMap?","wp-cloudy"), // Title
            array( $this, 'wpc_display_owm_link_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section
        );

		add_settings_field(
            'wpc_last_update', // ID
            __("Update date?","wp-cloudy"), // Title
            array( $this, 'wpc_display_last_update_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section
        );

		//LAYOUT SECTION=========================================================================
        add_settings_section(
            'wpc_setting_section_layout', // ID
            __("Layout settings","wp-cloudy"), // Title
            array( $this, 'print_section_info_layout' ), // Callback
            'wpc-settings-admin-layout' // Page
        );

		add_settings_field(
            'wpc_template', // ID
            __("Template?"), // Title
            array( $this, 'wpc_layout_template_callback' ), // Callback
            'wpc-settings-admin-layout', // Page
            'wpc_setting_section_layout' // Section
        );

		add_settings_field(
            'wpc_font', // ID
            __("Font?"), // Title
            array( $this, 'wpc_layout_font_callback' ), // Callback
            'wpc-settings-admin-layout', // Page
            'wpc_setting_section_layout' // Section
        );

		add_settings_field(
            'wpc_iconpack', // ID
            __("Icon Pack?"), // Title
            array( $this, 'wpc_layout_iconpack_callback' ), // Callback
            'wpc-settings-admin-layout', // Page
            'wpc_setting_section_layout' // Section
        );

		add_settings_field(
            'wpc_disable_spinner', // ID
            __("Spinner?","wp-cloudy"), // Title
            array( $this, 'wpc_layout_disable_spinner_callback' ), // Callback
            'wpc-settings-admin-layout', // Page
            'wpc_setting_section_layout' // Section
        );

		add_settings_field(
            'wpc_disable_anims', // ID
            __("Disable animations for main icon?","wp-cloudy"), // Title
            array( $this, 'wpc_layout_disable_anims_callback' ), // Callback
            'wpc-settings-admin-layout', // Page
            'wpc_setting_section_layout' // Section
        );

		add_settings_field(
            'wpc_background_color', // ID
            __("Background color?","wp-cloudy"), // Title
            array( $this, 'wpc_layout_background_color_callback' ), // Callback
            'wpc-settings-admin-layout', // Page
            'wpc_setting_section_layout' // Section
        );

        add_settings_field(
            'wpc_text_color', // ID
            __("Text color?","wp-cloudy"), // Title
            array( $this, 'wpc_layout_text_color_callback' ), // Callback
            'wpc-settings-admin-layout', // Page
            'wpc_setting_section_layout' // Section
        );

		add_settings_field(
            'wpc_border_color', // ID
            __("Border color?","wp-cloudy"), // Title
            array( $this, 'wpc_layout_border_color_callback' ), // Callback
            'wpc-settings-admin-layout', // Page
            'wpc_setting_section_layout' // Section
        );

        add_settings_field(
            'wpc_size', // ID
           __("Weather size?","wp-cloudy"), // Title
            array( $this, 'wpc_layout_size_callback' ), // Callback
            'wpc-settings-admin-layout', // Page
            'wpc_setting_section_layout' // Section
        );


        add_settings_field(
            'wpc_custom_css', // ID
           __("Custom CSS?","wp-cloudy"), // Title
            array( $this, 'wpc_layout_custom_css_callback' ), // Callback
            'wpc-settings-admin-layout', // Page
            'wpc_setting_section_layout' // Section
        );

		//SYSTEM SECTION=========================================================================
        add_settings_section(
            'wpc_setting_section_advanced', // ID
            __("Advanced settings","wp-cloudy"), // Title
            array( $this, 'print_section_info_advanced' ), // Callback
            'wpc-settings-admin-advanced' // Page
        );

        add_settings_field(
            'wpc_advanced_disable_cache', // ID
           __("Disable cache?","wp-cloudy"), // Title
            array( $this, 'wpc_advanced_disable_cache_callback' ), // Callback
            'wpc-settings-admin-advanced', // Page
            'wpc_setting_section_advanced' // Section
        );

        add_settings_field(
            'wpc_advanced_cache_time', // ID
           __("Time cache refresh (in minutes)?","wp-cloudy"), // Title
            array( $this, 'wpc_advanced_cache_time_callback' ), // Callback
            'wpc-settings-admin-advanced', // Page
            'wpc_setting_section_advanced' // Section
        );

        add_settings_field(
            'wpc_advanced_api_key', // ID
           __("Open Weather Map API key?","wp-cloudy"), // Title
            array( $this, 'wpc_advanced_api_key_callback' ), // Callback
            'wpc-settings-admin-advanced', // Page
            'wpc_setting_section_advanced' // Section
        );

        add_settings_field(
            'wpc_advanced_disable_modal_js', // ID
           __("Disable Bootstrap Modal JS?","wp-cloudy"), // Title
            array( $this, 'wpc_advanced_disable_modal_js_callback' ), // Callback
            'wpc-settings-admin-advanced', // Page
            'wpc_setting_section_advanced' // Section
        );

		//MAP SECTION =============================================================================

		add_settings_section(
            'wpc_setting_section_map', // ID
            __("Map settings","wp-cloudy"), // Title
            array( $this, 'print_section_info_map' ), // Callback
            'wpc-settings-admin-map' // Page
        );

        add_settings_field(
            'wpc_map', // ID
            __("Map?","wp-cloudy"), // Title
            array( $this, 'wpc_map_display_callback' ), // Callback
            'wpc-settings-admin-map', // Page
            'wpc_setting_section_map' // Section
        );

        add_settings_field(
            'wpc_map_height', // ID
            __("Map height?","wp-cloudy"), // Title
            array( $this, 'wpc_map_height_callback' ), // Callback
            'wpc-settings-admin-map', // Page
            'wpc_setting_section_map' // Section
        );

       add_settings_field(
            'wpc_map_opacity', // ID
            __("Layers opacity?","wp-cloudy"), // Title
            array( $this, 'wpc_map_opacity_callback' ), // Callback
            'wpc-settings-admin-map', // Page
            'wpc_setting_section_map' // Section
        );

        add_settings_field(
            'wpc_map_zoom', // ID
            __("Zoom?","wp-cloudy"), // Title
            array( $this, 'wpc_map_zoom_callback' ), // Callback
            'wpc-settings-admin-map', // Page
            'wpc_setting_section_map' // Section
        );

        add_settings_field(
            'wpc_map_disable_zoom_wheel', // ID
            __("Disable zoom wheel?","wp-cloudy"), // Title
            array( $this, 'wpc_map_disable_zoom_wheel_callback' ), // Callback
            'wpc-settings-admin-map', // Page
            'wpc_setting_section_map' // Section
        );

        add_settings_field(
            'wpc_map_stations', // ID
            __("Stations Layer?","wp-cloudy"), // Title
            array( $this, 'wpc_map_layers_stations_callback' ), // Callback
            'wpc-settings-admin-map', // Page
            'wpc_setting_section_map' // Section
        );

        add_settings_field(
            'wpc_map_clouds', // ID
            __("Clouds Layer?","wp-cloudy"), // Title
            array( $this, 'wpc_map_layers_clouds_callback' ), // Callback
            'wpc-settings-admin-map', // Page
            'wpc_setting_section_map' // Section
        );

        add_settings_field(
            'wpc_map_precipitation', // ID
            __("Precipitations Layer?","wp-cloudy"), // Title
            array( $this, 'wpc_map_layers_precipitation_callback' ), // Callback
            'wpc-settings-admin-map', // Page
            'wpc_setting_section_map' // Section
        );

        add_settings_field(
            'wpc_map_snow', // ID
            __("Snow Layer?","wp-cloudy"), // Title
            array( $this, 'wpc_map_layers_snow_callback' ), // Callback
            'wpc-settings-admin-map', // Page
            'wpc_setting_section_map' // Section
        );

        add_settings_field(
            'wpc_map_wind', // ID
            __("Wind Layer?","wp-cloudy"), // Title
            array( $this, 'wpc_map_layers_wind_callback' ), // Callback
            'wpc-settings-admin-map', // Page
            'wpc_setting_section_map' // Section
        );

        add_settings_field(
            'wpc_map_temperature', // ID
            __("Temperature Layer?","wp-cloudy"), // Title
            array( $this, 'wpc_map_layers_temperature_callback' ), // Callback
            'wpc-settings-admin-map', // Page
            'wpc_setting_section_map' // Section
        );

        add_settings_field(
            'wpc_map_pressure', // ID
            __("Pressure Layer?","wp-cloudy"), // Title
            array( $this, 'wpc_map_layers_pressure_callback' ), // Callback
            'wpc-settings-admin-map', // Page
            'wpc_setting_section_map' // Section
        );

        //SUPPORT SECTION==========================================================================
		add_settings_section(
            'wpc_setting_section_support', // ID
            '', // Title
            array( $this, 'print_section_info_support' ), // Callback
            'wpc-settings-admin-support' // Page
        );

		add_settings_field(
            'wpc_support_info', // ID
            '', // Title
            array( $this, 'wpc_support_info_callback' ), // Callback
            'wpc-settings-admin-support', // Page
            'wpc_setting_section_support' // Section
        );


		$this->options = get_option('wpc_option_name');
		if ($this->options) {
            foreach($this->options as $key => $value)
            {
                if ($value === '')
                {
                    unset($this->options[$key]);
                }
            }
        }
        update_option('wpc_option_name', $this->options);

        $wpc_version = $this->options['wpc_version'] ?? null;
        if (empty($wpc_version)) {
            $this->renameOptionField("wpc_advanced_bg_color","wpc_background_color");
            $this->renameOptionField("wpc_advanced_border_color","wpc_border_color");
            $this->renameOptionField("wpc_advanced_disable_css3_anims","wpc_disable_anims");
            $this->renameOptionField("wpc_advanced_bypass_size","wpc_size");
            $this->renameOptionField("wpc_advanced_text_color","wpc_text_color");
            $this->renameOptionField("wpc_basic_bypass_date","wpc_time_format");
            $this->renameOptionField("wpc_basic_bypass_unit","wpc_unit");
            $this->renameOptionField("wpc_display_cloudiness","wpc_cloudiness");
            $this->renameOptionField("wpc_display_current_weather","wpc_current_weather_symbol");
            $this->renameOptionField("wpc_display_temperature_min_max","wpc_current_temperature");
            $this->renameOptionField("wpc_display_date_temp_unit","wpc_display_temperature_unit");
            $this->renameOptionField("wpc_display_forecast_precipitations","wpc_forecast_precipitations");
            $this->renameOptionField("wpc_display_humidity","wpc_humidity");
            $this->renameOptionField("wpc_display_last_update","wpc_last_update");
            $this->renameOptionField("wpc_display_owm_link","wpc_owm_link");
            $this->renameOptionField("wpc_display_precipitation","wpc_precipitation");
            $this->renameOptionField("wpc_display_pressure","wpc_pressure");
            $this->renameOptionField("wpc_display_sunrise_sunset","wpc_sunrise_sunset");
            $this->renameOptionField("wpc_display_weather","wpc_current_weather_description");
            $this->renameOptionField("wpc_display_wind","wpc_wind");
            $this->renameOptionField("wpc_display_wind_unit","wpc_wind_unit");
            $this->renameOptionField("wpc_map_display","wpc_map");
            $this->renameOptionField("wpc_map_layers_clouds","wpc_map_clouds");
            $this->renameOptionField("wpc_map_layers_precipitation","wpc_map_precipitation");
            $this->renameOptionField("wpc_map_layers_pressure","wpc_map_pressure");
            $this->renameOptionField("wpc_map_layers_snow","wpc_map_snow");
            $this->renameOptionField("wpc_map_layers_stations","wpc_map_stations");
            $this->renameOptionField("wpc_map_layers_temperature","wpc_map_temperature");
            $this->renameOptionField("wpc_map_layers_wind","wpc_map_wind");
            $this->renameOptionField("wpc_map_mouse_wheel","wpc_map_disable_zoom_wheel");
            $this->renameOptionField("wpc_map_bypass_opacity","wpc_map_opacity");
            $this->renameOptionField("wpc_map_bypass_zoom","wpc_map_zoom");

            if (!empty($this->options["wpc_display_bypass_date_temp"]) && !empty($this->options["wpc_display_date_temp"])) {
                if ($this->options["wpc_display_date_temp"] == 'yes') {
                    $this->options["wpc_today_date_format"] = 'day';
                } else if ($this->options["wpc_display_date_temp"] == 'no') {
                    $this->options["wpc_today_date_format"] = 'date';
                } else  {
                    $this->options["wpc_today_date_format"] = 'none';
                }
            } else {
                $this->options["wpc_today_date_format"] = 'nobypass';
            }

            if (!empty($this->options["wpc_display_bypass_short_days_names"]) && !empty($this->options["wpc_display_short_days_names"])) {
                if ($this->options["wpc_display_short_days_names"] == 'yes') {
                    $this->options["wpc_display_length_days_names"] = 'short';
                } else  {
                    $this->options["wpc_display_length_days_names"] = 'normal';
                }
            } else {
                $this->options["wpc_today_date_format"] = 'nobypass';
            }

            if (!empty($this->options["wpc_display_bypass_hour_forecast_nd"]) && !empty($this->options["wpc_display_hour_forecast"])) {
                $this->options["wpc_hours_forecast_no"] = $this->options["wpc_display_bypass_hour_forecast_nd"];
            }
            unset($this->options["wpc_display_hour_forecast"]);
            unset($this->options["wpc_display_bypass_hour_forecast_nd"]);

            if (!empty($this->options["wpc_display_bypass_forecast_nd"]) && !empty($this->options["wpc_display_forecast"])) {
                $this->options["wpc_forecast_no"] = $this->options["wpc_display_bypass_forecast_nd"];
            }
            unset($this->options["wpc_display_forecast"]);
            unset($this->options["wpc_display_bypass_forecast_nd"]);

            $this->options["wpc_version"] = WPCLOUDY_VERSION;
            update_option('wpc_option_name', $this->options);
        }
    }
    
    private function renameOptionField($old, $new) {
        if (!empty($this->options[$old])) {
            $this->options[$new] = $this->options[$old];
            unset($this->options[$old]);
        }

        update_option('wpc_option_name', $this->options);
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
		if( !empty( $input['wpc_custom_css'] ) )
		$input['wpc_custom_css'] = sanitize_textarea_field( $input['wpc_custom_css'] );

		if( !empty( $input['wpc_background_color'] ) )
		$input['wpc_background_color'] = sanitize_text_field( $input['wpc_background_color'] );

		if( !empty( $input['wpc_text_color'] ) )
		$input['wpc_text_color'] = sanitize_text_field( $input['wpc_text_color'] );

		if( !empty( $input['wpc_border_color'] ) )
		$input['wpc_border_color'] = sanitize_text_field( $input['wpc_border_color'] );

		if( !empty( $input['wpc_advanced_cache_time'] ) )
		$input['wpc_advanced_cache_time'] = sanitize_text_field( $input['wpc_advanced_cache_time'] );

		if( !empty( $input['wpc_advanced_api_key'] ) )
		$input['wpc_advanced_api_key'] = sanitize_text_field( $input['wpc_advanced_api_key'] );

		if( !empty( $input['wpc_map_height'] ) )
        $input['wpc_map_height'] = sanitize_text_field( $input['wpc_map_height'] );

        return $input;
    }

    /**
     * Print the Section text
     */

	public function print_section_info_basic()
    {
        print __('Basic settings to bypass on all weather:', 'wp-cloudy');
        echo '<input type="hidden" name="wpc_option_name[wpc_version]" value="' . WPCLOUDY_VERSION . '" />';
    }

	public function print_section_info_display()
    {
        print __('Display settings to bypass on all weather:', 'wp-cloudy');
    }

    public function print_section_info_layout()
    {
        print __('Layout settings to bypass on all weather:', 'wp-cloudy');
    }

    public function print_section_info_advanced()
    {
        print __('WP Cloudy System settings:', 'wp-cloudy');
    }

	public function print_section_info_map()
    {
        print __('Map settings to bypass on all weather:', 'wp-cloudy');
    }

    public function print_section_info_support()
    {
        print __('&nbsp;', 'wp-cloudy');
    }

    /**
     * Get the settings option array and print one of its values
     */

	public function wpc_basic_unit_callback()
    {
        $selected = $this->options['wpc_unit'] ?? "nobypass";

		echo ' <select id="wpc_unit" name="wpc_option_name[wpc_unit]"> ';
        echo ' <option ';
        if ('nobypass' == $selected) echo 'selected="selected"';
        echo ' value="nobypass">'. __( 'No bypass', 'wp-cloudy' ) .'</option>';
		echo ' <option ';
		if ('imperial' == $selected) echo 'selected="selected"';
		echo ' value="imperial">'. __( 'Imperial', 'wp-cloudy' ) .'</option>';
		echo '<option ';
		if ('metric' == $selected) echo 'selected="selected"';
		echo ' value="metric">'. __( 'Metric', 'wp-cloudy' ) .'</option>';
		echo '</select>';
	}

	public function wpc_basic_time_format_callback()
    {
        $selected = $this->options['wpc_time_format'] ?? "nobypass";

		echo '<select id="wpc_time_format" name="wpc_option_name[wpc_time_format]"> ';
        echo ' <option ';
        if ('nobypass' == $selected) echo 'selected="selected"';
        echo ' value="nobypass">'. __( 'No bypass', 'wp-cloudy' ) .'</option>';
		echo ' <option ';
		echo '<option ';
		if ('12' == $selected) echo 'selected="selected"';
		echo ' value="12">'. __( '12 h', 'wp-cloudy' ) .'</option>';
		echo '<option ';
		if ('24' == $selected) echo 'selected="selected"';
		echo ' value="24">'. __( '24 h', 'wp-cloudy' ) .'</option>';
		echo '</select>';
	}

	public function wpc_basic_custom_timezone_callback()
    {
        $selected = $this->options['wpc_custom_timezone'] ?? "nobypass";

		echo '<select id="wpc_custom_timezone" name="wpc_option_name[wpc_custom_timezone]"> ';
        echo '<option ' . selected( 'nobypass', $selected, false ) . ' value="nobypass">'. __( 'No bypass', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Default', $selected, false ) . ' value="Default">'. __( 'WordPress timezone', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( '-12', $selected, false ) . ' value="-12">'. __( 'UTC -12', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( '-11', $selected, false ) . ' value="-11">'. __( 'UTC -11', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( '-10', $selected, false ) . ' value="-10">'. __( 'UTC -10', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( '-9', $selected, false ) . ' value="-9">'. __( 'UTC -9', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( '-8', $selected, false ) . ' value="-8">'. __( 'UTC -8', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( '-7', $selected, false ) . ' value="-7">'. __( 'UTC -7', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( '-6', $selected, false ) . ' value="-6">'. __( 'UTC -6', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( '-5', $selected, false ) . ' value="-5">'. __( 'UTC -5', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( '-4', $selected, false ) . ' value="-4">'. __( 'UTC -4', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( '-3', $selected, false ) . ' value="-3">'. __( 'UTC -3', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( '-2', $selected, false ) . ' value="-2">'. __( 'UTC -2', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( '-1', $selected, false ) . ' value="-1">'. __( 'UTC -1', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( '0', $selected, false ) . ' value="0">'. __( 'UTC 0', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( '1', $selected, false ) . ' value="1">'. __( 'UTC +1', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( '2', $selected, false ) . ' value="2">'. __( 'UTC +2', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( '3', $selected, false ) . ' value="3">'. __( 'UTC +3', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( '4', $selected, false ) . ' value="4">'. __( 'UTC +4', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( '5', $selected, false ) . ' value="5">'. __( 'UTC +5', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( '6', $selected, false ) . ' value="6">'. __( 'UTC +6', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( '7', $selected, false ) . ' value="7">'. __( 'UTC +7', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( '8', $selected, false ) . ' value="8">'. __( 'UTC +8', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( '9', $selected, false ) . ' value="9">'. __( 'UTC +9', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( '10', $selected, false ) . ' value="10">'. __( 'UTC +10', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( '11', $selected, false ) . ' value="11">'. __( 'UTC +11', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( '12', $selected, false ) . ' value="12">'. __( 'UTC +12', 'wp-cloudy' ) .'</option>';
		echo '</select>';
	}

	public function wpc_basic_owm_language_callback()
    {
        $selected = $this->options['wpc_owm_language'] ?? "nobypass";

		echo '<select id="wpc_owm_language" name="wpc_option_name[wpc_owm_language]"> ';
        echo '<option ' . ('nobypass' == $selected ? 'selected="selected"' : '') . ' value="nobypass">'. __( 'No bypass', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Default', $selected, false ) . ' value="Default">'. __( 'Default', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'af', $selected, false ) . ' value="af">'. __( 'Afrikaans', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'al', $selected, false ) . ' value="al">'. __( 'Albanian', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'ar', $selected, false ) . ' value="ar">'. __( 'Arabic', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'az', $selected, false ) . ' value="az">'. __( 'Azerbaijani', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'eu', $selected, false ) . ' value="eu">'. __( 'Basque', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'bg', $selected, false ) . ' value="bg">'. __( 'Bulgarian', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'ca', $selected, false ) . ' value="ca">'. __( 'Catalan', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'zh_cn', $selected, false ) . ' value="zh_cn">'. __( 'Chinese Simplified', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'zh_tw', $selected, false ) . ' value="zh_tw">'. __( 'Chinese Traditional', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'hr', $selected, false ) . ' value="hr">'. __( 'Croatian', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'cz', $selected, false ) . ' value="cz">'. __( 'Czech', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'da', $selected, false ) . ' value="da">'. __( 'Danish', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'nl', $selected, false ) . ' value="nl">'. __( 'Dutch', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'en', $selected, false ) . ' value="en">'. __( 'English', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'fi', $selected, false ) . ' value="fi">'. __( 'Finnish', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'fr', $selected, false ) . ' value="fr">'. __( 'French', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'gl', $selected, false ) . ' value="gl">'. __( 'Galician', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'de', $selected, false ) . ' value="de">'. __( 'German', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'el', $selected, false ) . ' value="el">'. __( 'Greek', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'he', $selected, false ) . ' value="he">'. __( 'Hebrew', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'hi', $selected, false ) . ' value="hi">'. __( 'Hindi', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'hu', $selected, false ) . ' value="hu">'. __( 'Hungarian', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'id', $selected, false ) . ' value="id">'. __( 'Indonesian', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'it', $selected, false ) . ' value="it">'. __( 'Italian', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'ja', $selected, false ) . ' value="ja">'. __( 'Japanese', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'kr', $selected, false ) . ' value="kr">'. __( 'Korean', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'la', $selected, false ) . ' value="la">'. __( 'Latvian', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'lt', $selected, false ) . ' value="lt">'. __( 'Lithuanian', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'mk', $selected, false ) . ' value="mk">'. __( 'Macedonian', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'no', $selected, false ) . ' value="no">'. __( 'Norwegian', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'fa', $selected, false ) . ' value="fa">'. __( 'Persian (Farsi)', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'pl', $selected, false ) . ' value="pl">'. __( 'Polish', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'pt', $selected, false ) . ' value="pt">'. __( 'Portuguese', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'pt', $selected, false ) . ' value="pt">'. __( 'Português Brasil', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'ro', $selected, false ) . ' value="ro">'. __( 'Romanian', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'ru', $selected, false ) . ' value="ru">'. __( 'Russian', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'sr', $selected, false ) . ' value="sr">'. __( 'Serbian', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'sv', $selected, false ) . ' value="sv">'. __( 'Swedish', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'sk', $selected, false ) . ' value="sk">'. __( 'Slovak', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'sl', $selected, false ) . ' value="sl">'. __( 'Slovenian', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'sp', $selected, false ) . ' value="sp">'. __( 'Spanish', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'th', $selected, false ) . ' value="th">'. __( 'Thai', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'tr', $selected, false ) . ' value="tr">'. __( 'Turkish', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'ua', $selected, false ) . ' value="ua">'. __( 'Ukrainian', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'vi', $selected, false ) . ' value="vi">'. __( 'Vietnamese', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'zu', $selected, false ) . ' value="zu">'. __( 'Zulu', 'wp-cloudy' ) .'</option>';
		echo '</select>';
	}

	public function wpc_layout_font_callback()
    {
        $selected = $this->options['wpc_font'] ?? "nobypass";

		echo '<select id="wpc_font" name="wpc_option_name[wpc_font]"> ';
        echo '<option ' . selected( 'nobypass', $selected, false ) . ' value="nobypass">'. __( 'No bypass', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Default', $selected, false ) . ' value="Default">'. __( 'Default', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Arvo', $selected, false ) . ' value="Arvo">'. __( 'Arvo', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Asap', $selected, false ) . ' value="Asap">'. __( 'Asap', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Bitter', $selected, false ) . ' value="Bitter">'. __( 'Bitter', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Droid Serif', $selected, false ) . ' value="Droid Serif">'. __( 'Droid Serif', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Exo 2', $selected, false ) . ' value="Exo 2">'. __( 'Exo 2', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Francois One', $selected, false ) . ' value="Francois One">'. __( 'Francois One', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Inconsolata', $selected, false ) . ' value="Inconsolata">'. __( 'Inconsolata', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Josefin Sans', $selected, false ) . ' value="Josefin Sans">'. __( 'Josefin Sans', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Lato', $selected, false ) . ' value="Lato">'. __( 'Lato', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Merriweather Sans', $selected, false ) . ' value="Merriweather Sans">'. __( 'Merriweather Sans', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Nunito', $selected, false ) . ' value="Nunito">'. __( 'Nunito', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Open Sans', $selected, false ) . ' value="Open Sans">'. __( 'Open Sans', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Oswald', $selected, false ) . ' value="Oswald">'. __( 'Oswald', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Pacifico', $selected, false ) . ' value="Pacifico">'. __( 'Pacifico', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Roboto', $selected, false ) . ' value="Roboto">'. __( 'Roboto', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Signika', $selected, false ) . ' value="Signika">'. __( 'Signika', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Source Sans Pro', $selected, false ) . ' value="Source Sans Pro">'. __( 'Source Sans Pro', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Tangerine', $selected, false ) . ' value="Tangerine">'. __( 'Tangerine', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Ubuntu', $selected, false ) . ' value="Ubuntu">'. __( 'Ubuntu', 'wp-cloudy' ) .'</option>';
		echo '</select>';
	}

	public function wpc_layout_template_callback()
    {
        $selected = $this->options['wpc_template'] ?? "nobypass";

		echo '<select id="wpc_template" name="wpc_option_name[wpc_template]"> ';
        echo '<option ' . selected( 'nobypass', $selected, false ) . ' value="nobypass">'. __( 'No bypass', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Default', $selected, false ) . ' value="Default">'. __( 'Default', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'theme1', $selected, false ) . ' value="theme1">'. __( 'Theme 1 (with slider)', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'theme2', $selected, false ) . ' value="theme2">'. __( 'Theme 2 (with slider)', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'chart1', $selected, false ) . ' value="chart1" disabled>'. __( 'Chart 1', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'chart2', $selected, false ) . ' value="chart2" disabled>'. __( 'Chart 2', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'table1', $selected, false ) . ' value="table1" disabled>'. __( 'Table 1', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'table2', $selected, false ) . ' value="table2" disabled>'. __( 'Table 2', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'custom1', $selected, false ) . ' value="custom1">'. __( 'Custom 1', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'custom2', $selected, false ) . ' value="custom2">'. __( 'Custom 2', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'debug', $selected, false ) . ' value="debug">'. __( 'Debug', 'wp-cloudy' ) .'</option>';
		echo '</select>';
	}

	public function wpc_layout_iconpack_callback()
    {
        $selected = $this->options['wpc_iconpack'] ?? "nobypass";

		echo '<select id="wpc_iconpack" name="wpc_option_name[wpc_iconpack]"> ';
        echo '<option ' . selected( 'nobypass', $selected, false ) . ' value="nobypass">'. __( 'No bypass', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Climacons', $selected, false ) . ' value="Climacons">'. __( 'Climacons', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'OpenWeatherMap', $selected, false ) . ' value="OpenWeatherMap">'. __( 'Open Weather Map', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'WeatherIcons', $selected, false ) . ' value="WeatherIcons">'. __( 'Weather Icons', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Forecast', $selected, false ) . ' value="Forecast">'. __( 'Forecast', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Dripicons', $selected, false ) . ' value="Dripicons">'. __( 'Dripicons', 'wp-cloudy' ) .'</option>';
        echo '<option ' . selected( 'Pixeden', $selected, false ) . ' value="Pixeden">'. __( 'Pixeden', 'wp-cloudy' ) .'</option>';
		echo '</select>';
	}

	public function wpc_display_current_city_name_callback()
    {
        $this->bypassRadioButtons('wpc_current_city_name');
    }

	public function wpc_display_current_weather_symbol_callback()
    {
        $this->bypassRadioButtons('wpc_current_weather_symbol');
    }

	public function wpc_display_weather_description_callback()
    {
        $this->bypassRadioButtons('wpc_current_weather_description');
    }

    public function wpc_display_today_date_format_callback()
    {
        $check = $this->options['wpc_today_date_format'] ?? "nobypass";

        echo '<input id="wpc_today_date_format_nobypass" name="wpc_option_name[wpc_today_date_format]" type="radio"';
        if ('nobypass' == $check) echo 'checked="checked"';
        echo ' value="nobypass"/>';
        echo '<label for="wpc_today_date_format_nobypass">'. __( 'No bypass', 'wp-cloudy' ) .'</label>';

        echo '<br><br>';
        echo '<input id="wpc_today_date_format_none" name="wpc_option_name[wpc_today_date_format]" type="radio"';
        if ('none' == $check) echo 'checked="checked"';
        echo ' value="none"/>';
        echo '<label for="wpc_today_date_format_none">'. __( 'None', 'wp-cloudy' ) .'</label>';

        echo '<br><br>';

        echo '<input id="wpc_today_date_format_day" name="wpc_option_name[wpc_today_date_format]" type="radio"';
        if ('day' == $check) echo 'checked="checked"';
        echo ' value="day"/>';
        echo '<label for="wpc_today_date_format_day">'. __( 'Day of the week (eg: Sunday)', 'wp-cloudy' ) .'</label>';

        echo '<br><br>';

        echo '<input id="wpc_today_date_format_date" name="wpc_option_name[wpc_today_date_format]" type="radio"';
        if ('date' == $check) echo 'checked="checked"';
        echo ' value="date"/>';
        echo '<label for="wpc_today_date_format_date">'. __( 'Today\'s date', 'wp-cloudy' ) .'</label>';
    }

    public function wpc_display_date_temp_unit_callback()
    {
        $this->bypassRadioButtons('wpc_display_temperature_unit');
    }

	public function wpc_display_sunrise_sunset_callback()
    {
        $this->bypassRadioButtons('wpc_sunrise_sunset');
    }

	public function wpc_display_moonrise_moonset_callback()
    {
        $this->bypassRadioButtons('wpc_moonrise_moonset');
    }

	public function wpc_display_wind_callback()
    {
        $this->bypassRadioButtons('wpc_wind');
    }

    public function wpc_display_wind_unit_callback()
    {
        $selected = $this->options['wpc_wind_unit'] ?? "nobypass";

        echo ' <select id="wpc_wind_unit" name="wpc_option_name[wpc_wind_unit]"> ';
        echo ' <option ';
        if ('nobypass' == $selected) echo 'selected="selected"';
        echo ' value="nobypass">'. __( 'No bypass', 'wp-cloudy' ) .'</option>';
        echo ' <option ';
        if ('1' == $selected) echo 'selected="selected"';
        echo ' value="1">'. __( 'mi/h', 'wp-cloudy' ) .'</option>';
        echo '<option ';
        if ('2' == $selected) echo 'selected="selected"';
        echo ' value="2">'. __( 'm/s', 'wp-cloudy' ) .'</option>';
        echo '<option ';
        if ('3' == $selected) echo 'selected="selected"';
        echo ' value="3">'. __( 'km/h', 'wp-cloudy' ) .'</option>';
        echo '<option ';
        if ('4' == $selected) echo 'selected="selected"';
        echo ' value="4">'. __( 'kt', 'wp-cloudy' ) .'</option>';
        echo '</select>';
    }

	public function wpc_display_humidity_callback()
    {
        $this->bypassRadioButtons('wpc_humidity');
    }

	public function wpc_display_pressure_callback()
    {
        $this->bypassRadioButtons('wpc_pressure');
    }

	public function wpc_display_cloudiness_callback()
    {
        $this->bypassRadioButtons('wpc_cloudiness');
    }

    public function wpc_display_precipitation_callback()
    {
        $this->bypassRadioButtons('wpc_precipitation');
    }

    public function wpc_display_alerts_callback()
    {
        $this->bypassRadioButtons('wpc_alerts');
    }

    public function wpc_display_alerts_button_color_callback()
    {
        $check = $this->options['wpc_alerts_button_color'] ?? null;

        printf('<input name="wpc_option_name[wpc_alerts_button_color]" type="text" value="%s" class="wpcloudy_admin_color_picker" />', esc_attr($check));
    }

    public function wpc_display_hour_forecast_no_callback()
    {
        $selected = $this->options['wpc_hours_forecast_no'] ?? null;

		echo ' <select id="wpc_hours_forecast_no" name="wpc_option_name[wpc_hours_forecast_no]"> ';
		echo $this->wpc_generate_hour_options($selected);
		echo '</select>';
	}

    private function wpc_generate_hour_options($selected) {
   		$str = ' <option ';
   		$str .= selected( "nobypass", $selected, false );
   		$str .= ' value="nobypass">'. __( "No bypass", 'wp-cloudy' ) .'</option>';
   		$str .= ' <option ';
   		$str .= selected( "0", $selected, false );
   		$str .= ' value="0">'. __( "None", 'wp-cloudy' ) .'</option>';

        for ($i=1; $i<=48; $i++) {
            if ($i == 1) {
                $h = 'Now';
            } else if ($i == 2) {
                $h = 'Now + 1 hour';
            } else {
                $h = 'Now + ' . ($i-1) . ' hours';
            }

    		$str .= ' <option ';
    		$str .= selected( $i, intval($selected), false );
    		$str .= ' value="' . $i . '">'. __( $h, 'wp-cloudy' ) .'</option>';
        }

        return $str;
    }

	public function wpc_display_current_temperature_callback()
    {
        $this->bypassRadioButtons('wpc_current_temperature');
    }

	public function wpc_display_forecast_no_callback()
    {
        $selected = $this->options['wpc_forecast_no'] ?? "nobypass";

		echo ' <select id="wpc_forecast_no" name="wpc_option_name[wpc_forecast_no]"> ';
		echo ' <option ';
		if ('nobypass' == $selected) echo 'selected="selected"';
		echo ' value="nobypass">'. __( 'No bypass', 'wp-cloudy' ) .'</option>';
		echo ' <option ';
		if ('0' == $selected) echo 'selected="selected"';
		echo ' value="0">'. __( 'None', 'wp-cloudy' ) .'</option>';
		echo ' <option ';
		if ('1' == $selected) echo 'selected="selected"';
		echo ' value="1">'. __( 'Today', 'wp-cloudy' ) .'</option>';
		echo '<option ';
		if ('2' == $selected) echo 'selected="selected"';
		echo ' value="2">'. __( 'Today + 1 day', 'wp-cloudy' ) .'</option>';
		echo '<option ';
		if ('3' == $selected) echo 'selected="selected"';
		echo ' value="3">'. __( 'Today + 2 days', 'wp-cloudy' ) .'</option>';
		echo '<option ';
		if ('4' == $selected) echo 'selected="selected"';
		echo ' value="4">'. __( 'Today + 3 days', 'wp-cloudy' ) .'</option>';
		echo '<option ';
		if ('5' == $selected) echo 'selected="selected"';
		echo ' value="5">'. __( 'Today + 4 days', 'wp-cloudy' ) .'</option>';
		echo '<option ';
		if ('6' == $selected) echo 'selected="selected"';
		echo ' value="6">'. __( 'Today + 5 days', 'wp-cloudy' ) .'</option>';
		echo '<option ';
		if ('7' == $selected) echo 'selected="selected"';
		echo ' value="7">'. __( 'Today + 6 days', 'wp-cloudy' ) .'</option>';
		echo '<option ';
		if ('8' == $selected) echo 'selected="selected"';
		echo ' value="8">'. __( 'Today + 7 days', 'wp-cloudy' ) .'</option>';
		echo '</select>';
	}

    public function wpc_display_forecast_precipitations_callback()
    {
        $this->bypassRadioButtons('wpc_forecast_precipitations');
    }

	public function wpc_display_display_length_days_names_callback()
    {
   		$check = $this->options['wpc_display_length_days_names'] ?? "nobypass";

        echo '<input id="wpc_display_length_days_names_nobypass" name="wpc_option_name[wpc_display_length_days_names]" type="radio"';
		if ('nobypass' == $check) echo 'checked="yes"';
		echo ' value="nobypass"/>';
		echo '<label for="wpc_display_length_days_names_nobypass">'. __( 'No bypass', 'wp-cloudy' ) .'</label>';

		echo '<br><br>';

        echo '<input id="wpc_display_length_days_names_short" name="wpc_option_name[wpc_display_length_days_names]" type="radio"';
		if ('short' == $check) echo 'checked="yes"';
		echo ' value="short"/>';
		echo '<label for="wpc_display_length_days_names_short">'. __( 'Short days names', 'wp-cloudy' ) .'</label>';

		echo '<br><br>';

		echo '<input id="wpc_display_length_days_names_normal" name="wpc_option_name[wpc_display_length_days_names]" type="radio"';
		if ('normal' == $check) echo 'checked="yes"';
		echo ' value="normal"/>';
		echo '<label for="wpc_display_length_days_names_normal">'. __( 'Normal days names', 'wp-cloudy' ) .'</label>';
    }

    public function wpc_display_owm_link_callback()
    {
        $this->bypassRadioButtons('wpc_owm_link');
    }

    public function wpc_display_last_update_callback()
    {
        $this->bypassRadioButtons('wpc_last_update');
    }

	public function wpc_layout_disable_spinner_callback()
    {
        $this->bypassRadioButtonsDisable('wpc_disable_spinner');
    }

	public function wpc_layout_disable_anims_callback()
    {
        $this->bypassRadioButtonsDisable('wpc_disable_anims');
    }

    public function wpc_layout_background_color_callback()
    {
        $check = $this->options['wpc_background_color'] ?? null;

        printf('<input name="wpc_option_name[wpc_background_color]" type="text" value="%s" class="wpcloudy_admin_color_picker" />', esc_attr($check));
    }

	public function wpc_layout_text_color_callback()
    {
        $check = $this->options['wpc_text_color'] ?? null;

        printf('<input name="wpc_option_name[wpc_text_color]" type="text" value="%s" class="wpcloudy_admin_color_picker" />', esc_attr($check));
    }

	public function wpc_layout_border_color_callback()
    {
        $check = $this->options['wpc_border_color'] ?? null;

		printf('<input name="wpc_option_name[wpc_border_color]" type="text" value="%s" class="wpcloudy_admin_color_picker" />', esc_attr($check));
    }

	public function wpc_layout_size_callback()
    {
        $selected = $this->options['wpc_size'] ?? "nobypass";

		echo ' <select id="wpc_size" name="wpc_option_name[wpc_size]"> ';
		echo ' <option ';
		if ('nobypass' == $selected) echo 'selected="selected"';
		echo ' value="nobypass">'. __( 'No bypass', 'wp-cloudy' ) .'</option>';
		echo ' <option ';
		if ('small' == $selected) echo 'selected="selected"';
		echo ' value="small">'. __( 'Small', 'wp-cloudy' ) .'</option>';
		echo '<option ';
		if ('medium' == $selected) echo 'selected="selected"';
		echo ' value="medium">'. __( 'Medium', 'wp-cloudy' ) .'</option>';
		echo '<option ';
		if ('large' == $selected) echo 'selected="selected"';
		echo ' value="large">'. __( 'Large', 'wp-cloudy' ) .'</option>';
		echo '</select>';
	}


	public function wpc_layout_custom_css_callback()
    {
        $check = $this->options['wpc_custom_css'] ?? '';

        printf('<textarea name="wpc_option_name[wpc_custom_css]" style="width:100%%;height:300px;">%s</textarea>', esc_attr($check));
    }

	public function wpc_advanced_disable_cache_callback()
    {
		$check = $this->options['wpc_advanced_disable_cache'] ?? null;

        echo '<input id="wpc_advanced_disable_cache" name="wpc_option_name[wpc_advanced_disable_cache]" type="checkbox"';
		if ('yes' == $check) echo 'checked="yes"';
		echo ' value="yes"/>';
		echo '<label for="wpc_advanced_disable_cache">'. __( 'Disable weather cache? (Not recommended!)', 'wp-cloudy' ) .'</label>';
    }

	public function wpc_advanced_cache_time_callback()
    {
        $check = $this->options['wpc_advanced_cache_time'] ?? '';

        printf('<input type="text" name="wpc_option_name[wpc_advanced_cache_time]" value="%s" style="width:100%%" />', esc_html( $check ));
        echo '<br /><br /><span class="dashicons dashicons-editor-help"></span>'.__('Default value: 30 minutes','wp-cloudy');
	}

    public function wpc_advanced_api_key_callback()
    {
        $check = $this->options['wpc_advanced_api_key'] ?? '';

        printf('<input type="text" name="wpc_option_name[wpc_advanced_api_key]" value="%s" style="width:100%%" />', esc_html( $check ));
        echo '<br /><br /><span class="dashicons dashicons-editor-help"></span>'.__('Strongly recommended: ','wp-cloudy').'<a href="https://openweathermap.org/appid" target="_blank">'.__('Get your free OWM API key here','wp-cloudy').'</a>';
    }

    public function wpc_advanced_disable_modal_js_callback()
    {
        $check = $this->options['wpc_advanced_disable_modal_js'] ?? null;

        echo '<input id="wpc_advanced_disable_modal_js" name="wpc_option_name[wpc_advanced_disable_modal_js]" type="checkbox"';
        if ('yes' == $check) echo 'checked="yes"';
        echo ' value="yes"/>';
        echo '<label for="wpc_advanced_disable_modal_js">'. __( 'Disable Bootstrap? (Check this if you already include Bootstrap in your theme)', 'wp-cloudy' ) .'</label>';
	}

	public function wpc_map_display_callback()
    {
        $this->bypassRadioButtons('wpc_map');
    }

	public function wpc_map_height_callback()
    {
        $check = $this->options['wpc_map_height'] ?? '';

        printf('<input name="wpc_option_name[wpc_map_height]" type="text" value="%s" />', esc_attr($check));
	}

	public function wpc_map_opacity_callback()
	{
        $selected = $this->options['wpc_map_opacity'] ?? "nobypass";

		echo ' <select id="wpc_map_opacity" name="wpc_option_name[wpc_map_opacity]"> ';
		echo ' <option ';
		if ('nobypass' == $selected) echo 'selected="selected"';
		echo ' value="nobypass">No bypass</option>';
		echo ' <option ';
		if ('0' == $selected) echo 'selected="selected"';
		echo ' value="0">0%</option>';
		echo '<option ';
		if ('0.1' == $selected) echo 'selected="selected"';
		echo ' value="0.1">10%</option>';
		echo '<option ';
		if ('0.2' == $selected) echo 'selected="selected"';
		echo ' value="0.2">20%</option>';
		echo '<option ';
		if ('0.3' == $selected) echo 'selected="selected"';
		echo ' value="0.3">30%</option>';
		echo '<option ';
		if ('0.4' == $selected) echo 'selected="selected"';
		echo ' value="0.4">40%</option>';
		echo '<option ';
		if ('0.5' == $selected) echo 'selected="selected"';
		echo ' value="0.5">50%</option>';
		echo '<option ';
		if ('0.6' == $selected) echo 'selected="selected"';
		echo ' value="0.6">60%</option>';
		echo '<option ';
		if ('0.7' == $selected) echo 'selected="selected"';
		echo ' value="0.7">70%</option>';
		echo '<option ';
		if ('0.8' == $selected) echo 'selected="selected"';
		echo ' value="0.8">80%</option>';
		echo '<option ';
		if ('0.9' == $selected) echo 'selected="selected"';
		echo ' value="0.9">90%</option>';
		echo '<option ';
		if ('1' == $selected) echo 'selected="selected"';
		echo ' value="1">100%</option>';
		echo '</select>';
	}

	public function wpc_map_zoom_callback()
	{
        $selected = $this->options['wpc_map_zoom'] ?? "nobypass";

		echo ' <select id="wpc_map_zoom" name="wpc_option_name[wpc_map_zoom]"> ';
		echo ' <option ';
		if ('nobypass' == $selected) echo 'selected="selected"';
		echo ' value="nobypass">No bypass</option>';
		echo ' <option ';
		if ('1' == $selected) echo 'selected="selected"';
		echo ' value="1">1</option>';
		echo ' <option ';
		if ('2' == $selected) echo 'selected="selected"';
		echo ' value="2">2</option>';
		echo ' <option ';
		if ('3' == $selected) echo 'selected="selected"';
		echo ' value="3">3</option>';
		echo ' <option ';
		if ('4' == $selected) echo 'selected="selected"';
		echo ' value="4">4</option>';
		echo ' <option ';
		if ('5' == $selected) echo 'selected="selected"';
		echo ' value="5">5</option>';
		echo ' <option ';
		if ('6' == $selected) echo 'selected="selected"';
		echo ' value="6">6</option>';
		echo ' <option ';
		if ('7' == $selected) echo 'selected="selected"';
		echo ' value="7">7</option>';
		echo ' <option ';
		if ('8' == $selected) echo 'selected="selected"';
		echo ' value="8">8</option>';
		echo ' <option ';
		if ('9' == $selected) echo 'selected="selected"';
		echo ' value="9">9</option>';
		echo ' <option ';
		if ('10' == $selected) echo 'selected="selected"';
		echo ' value="10">10</option>';
		echo ' <option ';
		if ('11' == $selected) echo 'selected="selected"';
		echo ' value="11">11</option>';
		echo ' <option ';
		if ('12' == $selected) echo 'selected="selected"';
		echo ' value="12">12</option>';
		echo ' <option ';
		if ('13' == $selected) echo 'selected="selected"';
		echo ' value="13">13</option>';
		echo ' <option ';
		if ('14' == $selected) echo 'selected="selected"';
		echo ' value="14">14</option>';
		echo ' <option ';
		if ('15' == $selected) echo 'selected="selected"';
		echo ' value="15">15</option>';
		echo ' <option ';
		if ('16' == $selected) echo 'selected="selected"';
		echo ' value="16">16</option>';
		echo ' <option ';
		if ('17' == $selected) echo 'selected="selected"';
		echo ' value="17">17</option>';
		echo ' <option ';
		if ('18' == $selected) echo 'selected="selected"';
		echo ' value="18">18</option>';
		echo '</select>';
	}

	public function wpc_map_disable_zoom_wheel_callback()
    {
        $this->bypassRadioButtonsDisable('wpc_map_disable_zoom_wheel');
    }

	public function wpc_map_layers_stations_callback()
    {
        $this->bypassRadioButtons('wpc_map_stations');
    }

	public function wpc_map_layers_clouds_callback()
    {
        $this->bypassRadioButtons('wpc_map_clouds');
    }

	public function wpc_map_layers_precipitation_callback()
    {
        $this->bypassRadioButtons('wpc_map_precipitation');
    }

	public function wpc_map_layers_snow_callback()
    {
        $this->bypassRadioButtons('wpc_map_snow');
    }

	public function wpc_map_layers_wind_callback()
    {
        $this->bypassRadioButtons('wpc_map_wind');
    }

	public function wpc_map_layers_temperature_callback()
    {
        $this->bypassRadioButtons('wpc_map_temperature');
    }

	public function wpc_map_layers_pressure_callback()
    {
        $this->bypassRadioButtons('wpc_map_pressure');
    }

    public function wpc_support_info_callback() //bugbug
    {
		echo
			'<h3>'. __("Problem with WP Cloudy?", "wp-cloudy").'</h3>
			<p><a href="https://www.wpcloudy.com/support/faq/" target="_blank" title="'. __("FAQ", "wp-cloudy").'">'. __("Read our FAQ", "wp-cloudy").'</a></p>
			<p><a href="https://www.wpcloudy.com/support/guides/" target="_blank" title="'. __("Guides", "wp-cloudy").'">'.__("Read our Guides", "wp-cloudy").'</a></p>
			<p><a href="https://wordpress.org/plugins/wp-cloudy/" target="_blank" title="'. __("WP Cloudy Forum on WordPress.org", "wp-cloudy").'">'. __("WP Cloudy Forum on WordPress.org", "wp-cloudy").'</a></p>';
    }

    private function bypassRadioButtons($option) {
		$value = $this->options[$option] ?? 'nobypass';

        echo '<input id="' . $option . '_nobypass" name="wpc_option_name[' . $option . ']" type="radio"';
        if ('nobypass' == $value) echo 'checked="checked"';
        echo ' value="nobypass"/>';
        echo '<label for="' . $option . '_nobypass">'. __( 'No bypass', 'wp-cloudy' ) .'</label>';

        echo '<br><br>';
            
        echo '<input id="' . $option . '_on" name="wpc_option_name[' . $option . ']" type="radio"';
        if ('yes' == $value) echo 'checked="checked"';
        echo ' value="yes"/>';
        echo '<label for="' . $option . '_on">'. __( 'Show on all weather', 'wp-cloudy' ) .'</label>';

        echo '<br><br>';

        echo '<input id="' . $option . '_off" name="wpc_option_name[' . $option . ']" type="radio"';
        if ('no' == $value) echo 'checked="checked"';
        echo ' value="no"/>';
        echo '<label for="' . $option . '_off">'. __( 'Suppress on all weather', 'wp-cloudy' ) .'</label>';
    }

    private function bypassRadioButtonsDisable($option) {
		$value = $this->options[$option] ?? 'nobypass';

        echo '<input id="' . $option . '_nobypass" name="wpc_option_name[' . $option . ']" type="radio"';
        if ('nobypass' == $value) echo 'checked="checked"';
        echo ' value="nobypass"/>';
        echo '<label for="' . $option . '_nobypass">'. __( 'No bypass', 'wp-cloudy' ) .'</label>';

        echo '<br><br>';
            
        echo '<input id="' . $option . '_on" name="wpc_option_name[' . $option . ']" type="radio"';
        if ('no' == $value) echo 'checked="checked"';
        echo ' value="no"/>';
        echo '<label for="' . $option . '_on">'. __( 'Show on all weather', 'wp-cloudy' ) .'</label>';

        echo '<br><br>';

        echo '<input id="' . $option . '_off" name="wpc_option_name[' . $option . ']" type="radio"';
        if ('yes' == $value) echo 'checked="checked"';
        echo ' value="yes"/>';
        echo '<label for="' . $option . '_off">'. __( 'Suppress on all weather', 'wp-cloudy' ) .'</label>';
    }

}

if( is_admin() )
    $my_settings_page = new wpc_options();

//Help Tab
function wpc_help_tab() {//bugbug
    global $wpc_help_tab;
    $screen = get_current_screen();

    $screen->add_help_tab( array(
        'id'    => 'wpc_help_tab',
        'title' => __('Setup WP Cloudy'),
        'content'   => '<p>' . __( 'Follow this video to setup WP Cloudy:' ) . '<br>'.wp_oembed_get('https://www.youtube.com/watch?v=mRF_3VOz_OE', array('width'=>560)).'</p>',
    ));
    $screen->add_help_tab( array(
        'id'    => 'wpc_help_tab2',
        'title' => __('Create your first weather'),
        'content'   => '<p>' . __( 'Follow this video to create your first weather with WP Cloudy:' ) . '<br>'.wp_oembed_get('https://www.youtube.com/watch?v=xv4lrgsWkkk', array('width'=>560)).'</p>',
    ));
}
function no_file_selected_action() {
    $message = __( 'Please upload a file to import' );
    add_settings_error('no_file_selected', '', $message, 'error');
}
function no_json_file_selected_action() {
        $message = __( 'Please upload a valid .json file' );
        add_settings_error('no_json_file_selected', '', $message, 'error');
}
?>
