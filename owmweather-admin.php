<?php
// To prevent calling the plugin directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Please don&rsquo;t call the plugin directly. Thanks :)';
    exit;
}

class owmw_options
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct() {
        add_action( 'admin_menu', array( $this, 'owmw_add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function owmw_add_plugin_page() {
        // This page will be under "Settings"

    $owmw_help_tab = add_options_page(
            'Settings Admin',
            'OWM Weather',
            'manage_options',
            'owmw-settings-admin',
            array( $this, 'owmw_create_admin_page' )
        );
    add_action('load-'.$owmw_help_tab, 'owmw_help_tab');
    }


    /**
     * Options page callback
     */
    public function owmw_create_admin_page() {

    	wp_enqueue_media();
        add_action( 'admin_footer', 'owmw_media_selector_print_scripts' );

        // Set class property
        $this->options = get_option( 'owmw_option_name' );
        ?>
        <?php $owmw_info_version = get_plugin_data( plugin_dir_path( __FILE__ ).'/owmweather.php'); ?>

        <div id="owmweather-header">
			<div id="owmweather-clouds">
				<h3>
					<?php _e( 'OWM Weather', 'owm-weather' ); ?>
				</h3>
				<span class="owmw-info-version"><?php print_r($owmw_info_version['Version']); ?></span>
				<div id="owmweather-notice">
					<div class="small">

                                <span class="dashicons dashicons-wordpress"></span>
                                <?php _e( 'Do you like WP OPM Weather? Don\'t forget to rate it 5 stars!', 'owm-weather' ); ?>

                                <div class="wporg-ratings rating-stars">
                                    <a href="//wordpress.org/support/view/plugin-reviews/owm-weather?rate=1#postform" data-rating="1" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#e6b800 !important;"></span></a>
                                    <a href="//wordpress.org/support/view/plugin-reviews/owm-weather?rate=2#postform" data-rating="2" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#e6b800 !important;"></span></a>
                                    <a href="//wordpress.org/support/view/plugin-reviews/owm-weather?rate=3#postform" data-rating="3" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#e6b800 !important;"></span></a>
                                    <a href="//wordpress.org/support/view/plugin-reviews/owm-weather?rate=4#postform" data-rating="4" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#e6b800 !important;"></span></a>
                                    <a href="//wordpress.org/support/view/plugin-reviews/owm-weather?rate=5#postform" data-rating="5" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#e6b800 !important;"></span></a>
                                </div>
                                <script>
                                jQuery(document).ready( function($) {
                                    $('.rating-stars').find('a').hover(function() {
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
                                </script>
                            </div>
                        </div>

                        <form action="https://www.paypal.com/donate" method="post" target="_blank">
	    					<?php _e( 'Consider a donation', 'owm-weather' ); ?>:
                            <input type="hidden" name="hosted_button_id" value="PQDNJGKMLHAFU" />
                            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
                            <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
                        </form>

			</div>
		</div>

        <?php
            function owmw_settings_admin_export() {
                ?>
                <div id="owmw_export_form" class="metabox-holder">
                    <div class="postbox">
                        <h3><span><?php _e( 'Export Settings', 'owm-weather' ); ?></span></h3>
                        <div class="inside">
                            <p><?php _e( 'Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.', 'owm-weather' ); ?></p>
                            <form method="post">
                                <p><input type="hidden" name="owmw_action" value="owmw_export_settings" /></p>
                                <p>
                                    <?php wp_nonce_field( 'owmw_export_nonce', 'owmw_export_nonce' ); ?>
                                    <?php submit_button( __( 'Export', 'owm-weather' ), 'secondary', 'submit', false ); ?>
                                </p>
                            </form>
                        </div><!-- .inside -->
                    </div><!-- .postbox -->

                    <div class="postbox">
                        <h3><span><?php _e( 'Import Settings', 'owm-weather' ); ?></span></h3>
                        <div class="inside">
                            <p><?php _e( 'Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'owm-weather' ); ?></p>
                            <form method="post" enctype="multipart/form-data">
                                <p>
                                    <input type="file" name="owmw_import_file"/>
                                </p>
                                <p>
                                    <input type="hidden" name="owmw_action" value="owmw_import_settings" />
                                    <?php wp_nonce_field( 'owmw_import_nonce', 'owmw_import_nonce' ); ?>
                                    <?php submit_button( __( 'Import', 'owm-weather' ), 'secondary', 'submit', false ); ?>
                                </p>
                            </form>
                        </div><!-- .inside -->
                    </div><!-- .postbox -->

                    <div class="postbox">
                        <h3><span><?php _e( 'Reset Settings', 'owm-weather' ); ?></span></h3>
                        <div class="inside">
                            <p><?php _e( 'Reset all OWM Weather global settings. It will not delete your weathers and their indivuals settings.', 'owm-weather' ); ?></p>
                            <form method="post" enctype="multipart/form-data">
                                <p>
                                    <input type="hidden" name="owmw_action" value="owmw_reset_settings" />
                                    <?php wp_nonce_field( 'owmw_reset_nonce', 'owmw_reset_nonce' ); ?>
                                    <?php submit_button( __( 'Reset settings', 'owm-weather' ), 'secondary', 'submit', false ); ?>
                                </p>
                            </form>
                        </div><!-- .inside -->
                    </div><!-- .postbox -->
                </div><!-- .metabox-holder -->
                <?php

            }
        ?>

        <?php owmw_settings_admin_export(); ?>

        <form method="post" action="options.php" class="owmweather-settings">
            <?php settings_fields( 'owmw_cloudy_option_group' ); ?>

            <div id="owmweather-tabs">
                <h2 class="nav-tab-wrapper hide-if-no-js">
                	<ul>
						<li><a href="#tab_advanced" class="nav-tab"><?php _e( 'System', 'owm-weather' ); ?></a></li>
						<li><a href="#tab_basic" class="nav-tab"><?php _e( 'Basic', 'owm-weather' ); ?></a></li>
						<li><a href="#tab_display" class="nav-tab"><?php _e( 'Display', 'owm-weather' ); ?></a></li>
						<li><a href="#tab_layout" class="nav-tab"><?php _e( 'Layout', 'owm-weather' ); ?></a></li>
						<li><a href="#tab_map" class="nav-tab"><?php _e( 'Map', 'owm-weather' ); ?></a></li>
                        <li><a href="#tab_export" class="nav-tab"><?php _e( 'Import/Export/Reset', 'owm-weather' ); ?></a></li>
						<li><a href="#tab_support" class="nav-tab"><?php _e( 'Support', 'owm-weather' ); ?></a></li>
                	</ul>
				</h2>

				<div id="owmweather-tabs-settings">
					<div class="owmw-tab" id="tab_advanced"><?php do_settings_sections( 'owmw-settings-admin-advanced' ); ?></div>
					<div class="owmw-tab" id="tab_basic"><?php do_settings_sections( 'owmw-settings-admin-basic' ); ?></div>
					<div class="owmw-tab" id="tab_display"><?php do_settings_sections( 'owmw-settings-admin-display' ); ?></div>
					<div class="owmw-tab" id="tab_layout"><?php do_settings_sections( 'owmw-settings-admin-layout' ); ?></div>
					<div class="owmw-tab" id="tab_map"><?php do_settings_sections( 'owmw-settings-admin-map' ); ?></div>
                    <div class="owmw-tab" id="tab_export"></div>
					<div class="owmw-tab" id="tab_support"><?php do_settings_sections( 'owmw-settings-admin-support' ); ?></div>
				</div>
            </div>
            <script>jQuery("#owmw_export_form").detach().appendTo('#tab_export')</script>
             <?php submit_button( __( 'Save changes', 'owm-weather' ), 'primary', 'submit', false ); ?>
        </form>

        <div class="owmweather-sidebar">
        	<div id="owmweather-cache" class="owmweather-module owmweather-inactive" style="height: 177px;">
				<h3><?php _e('OWM Weather cache','owm-weather'); ?></h3>
				<div class="owmweather-module-description">
					<div class="module-image">
						<div class="dashicons dashicons-trash"></div>
						<p><span class="module-image-badge"><?php _e('cache system','owm-weather'); ?></span></p>
					</div>

					<p><?php _e('Click this button to refresh weather cache.','owm-weather'); ?></p>

	            	<?php
						function owmw_clear_all_cache() {
					    	if (isset($_GET['owmw_clear_all_cache_nonce'])) {
						?>
						<div class="owmweather-module-actions">
							<p>
							    <a href="<?php print wp_nonce_url(admin_url('options-general.php?page=owmw-settings-admin'), 'owmw_clear_all_cache_action', 'owmw_clear_all_cache_nonce');?>"
							        class="button button-secondary">
							        <?php esc_html_e('Clear cache!', 'owm-weather');?>
								</a>
							</p>
						</div>

						<?php

					    } else {

						?>
						<div class="owmweather-module-actions">
						    <a href="<?php print wp_nonce_url(admin_url('options-general.php?page=owmw-settings-admin'), 'owmw_clear_all_cache_action', 'owmw_clear_all_cache');?>"
						        class="button button-secondary">
						        <?php esc_html_e('Clear cache!', 'owm-weather');?>
							</a>
						</div>

						<?php
							global $wpdb;
							$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_owmweather%' ");
							$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_owmweather%' ");
						}
					};
					?>
					<?php echo owmw_clear_all_cache(); ?>
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
            'owmw_cloudy_option_group', // Option group
            'owmw_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

		//BASIC SECTION============================================================================
		add_settings_section(
            'owmw_setting_section_basic', // ID
            __("Basic settings",'owm-weather'), // Title
            array( $this, 'owmw_print_section_info_basic' ), // Callback
            'owmw-settings-admin-basic' // Page
        );

        add_settings_field(
            'owmw_unit', // ID
           __("Bypass measurement system?",'owm-weather'), // Title
            array( $this, 'owmw_basic_unit_callback' ), // Callback
            'owmw-settings-admin-basic', // Page
            'owmw_setting_section_basic' // Section
        );

        add_settings_field(
            'owmw_time_format', // ID
           __("Bypass time format?",'owm-weather'), // Title
            array( $this, 'owmw_basic_time_format_callback' ), // Callback
            'owmw-settings-admin-basic', // Page
            'owmw_setting_section_basic' // Section
        );

        add_settings_field(
            'owmw_custom_timezone', // ID
           __("Bypass timezone?",'owm-weather'), // Title
            array( $this, 'owmw_basic_custom_timezone_callback' ), // Callback
            'owmw-settings-admin-basic', // Page
            'owmw_setting_section_basic' // Section
        );

        add_settings_field(
            'owmw_owm_language', // ID
           __("Bypass OpenWeatherMap language?",'owm-weather'), // Title
            array( $this, 'owmw_basic_owm_language_callback' ), // Callback
            'owmw-settings-admin-basic', // Page
            'owmw_setting_section_basic' // Section
        );

		//DISPLAY SECTION==========================================================================
        add_settings_section(
            'owmw_setting_section_display', // ID
            __("Display settings",'owm-weather'), // Title
            array( $this, 'owmw_print_section_info_display' ), // Callback
            'owmw-settings-admin-display' // Page
        );

        add_settings_field(
            'owmw_current_city_name', // ID
            __("Current City Name?",'owm-weather'), // Title
            array( $this, 'owmw_display_current_city_name_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_current_weather_symbol', // ID
            __("Current weather symbol?",'owm-weather'), // Title
            array( $this, 'owmw_display_current_weather_symbol_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

		add_settings_field(
            'owmw_current_temperature', // ID
			__("Current temperature?",'owm-weather'), // Title
            array( $this, 'owmw_display_current_temperature_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

		add_settings_field(
            'owmw_current_feels_like', // ID
			__("Current feels like temperature?",'owm-weather'), // Title
            array( $this, 'owmw_display_current_feels_like_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

		add_settings_field(
            'owmw_current_weather_description', // ID
            __("Current short condition?",'owm-weather'), // Title
            array( $this, 'owmw_display_weather_description_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_display_temperature_unit', // ID
            __("Temperature unit (C / F)?",'owm-weather'), // Title
            array( $this, 'owmw_display_date_temp_unit_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

		add_settings_field(
            'owmw_today_date_format', // ID
            __("Date?",'owm-weather'), // Title
            array( $this, 'owmw_display_today_date_format_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

		add_settings_field(
            'owmw_sunrise_sunset', // ID
            __("Sunrise + sunset?",'owm-weather'), // Title
            array( $this, 'owmw_display_sunrise_sunset_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

		add_settings_field(
            'owmw_moonrise_moonset', // ID
            __("Moonrise + moonset?",'owm-weather'), // Title
            array( $this, 'owmw_display_moonrise_moonset_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

		add_settings_field(
            'owmw_wind', // ID
            __("Wind?",'owm-weather'), // Title
            array( $this, 'owmw_display_wind_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_wind_unit', // ID
            __("Wind unit?",'owm-weather'), // Title
            array( $this, 'owmw_display_wind_unit_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

		add_settings_field(
            'owmw_humidity', // ID
            __("Humidity?",'owm-weather'), // Title
            array( $this, 'owmw_display_humidity_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

		add_settings_field(
            'owmw_dew_point', // ID
            __("Dew Point?",'owm-weather'), // Title
            array( $this, 'owmw_display_dew_point_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

		add_settings_field(
            'owmw_pressure', // ID
           __("Pressure?",'owm-weather'), // Title
            array( $this, 'owmw_display_pressure_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

		add_settings_field(
            'owmw_cloudiness', // ID
            __("Cloudiness?",'owm-weather'), // Title
            array( $this, 'owmw_display_cloudiness_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_precipitation', // ID
            __("Precipitation?",'owm-weather'), // Title
            array( $this, 'owmw_display_precipitation_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_visibility', // ID
            __("Visibility?",'owm-weather'), // Title
            array( $this, 'owmw_display_visibility_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_uv_index', // ID
            __("UV Index?",'owm-weather'), // Title
            array( $this, 'owmw_display_uv_index_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_alerts', // ID
            __("Alerts?",'owm-weather'), // Title
            array( $this, 'owmw_display_alerts_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_alerts_button_color', // ID
            __("Alerts Button Color?",'owm-weather'), // Title
            array( $this, 'owmw_display_alerts_button_color_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_hours_forecast_no', // ID
            __("Number of hours forecast?",'owm-weather'), // Title
            array( $this, 'owmw_display_hour_forecast_no_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_hours_time_icons', // ID
            __("Display time icons?",'owm-weather'), // Title
            array( $this, 'owmw_display_hour_time_icons_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_forecast_no', // ID
            __("Number of days forecast",'owm-weather'), // Title
            array( $this, 'owmw_display_forecast_no_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_forecast_precipitations', // ID
            __("Precipitations forecast?",'owm-weather'), // Title
            array( $this, 'owmw_display_forecast_precipitations_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

		add_settings_field(
            'owmw_display_length_days_names', // ID
			__("Length name days:",'owm-weather'), // Title
            array( $this, 'owmw_display_display_length_days_names_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_owm_link', // ID
			__("Link to OpenWeatherMap?",'owm-weather'), // Title
            array( $this, 'owmw_display_owm_link_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

		add_settings_field(
            'owmw_last_update', // ID
            __("Update date?",'owm-weather'), // Title
            array( $this, 'owmw_display_last_update_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

		//LAYOUT SECTION=========================================================================
        add_settings_section(
            'owmw_setting_section_layout', // ID
            __("Layout settings",'owm-weather'), // Title
            array( $this, 'owmw_print_section_info_layout' ), // Callback
            'owmw-settings-admin-layout' // Page
        );

		add_settings_field(
            'owmw_template', // ID
            __("Template?"), // Title
            array( $this, 'owmw_layout_template_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

		add_settings_field(
            'owmw_font', // ID
            __("Font?"), // Title
            array( $this, 'owmw_layout_font_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

		add_settings_field(
            'owmw_iconpack', // ID
            __("Icon Pack?"), // Title
            array( $this, 'owmw_layout_iconpack_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

		add_settings_field(
            'owmw_disable_spinner', // ID
            __("Spinner?",'owm-weather'), // Title
            array( $this, 'owmw_layout_disable_spinner_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

		add_settings_field(
            'owmw_disable_anims', // ID
            __("Disable animations for main icon?",'owm-weather'), // Title
            array( $this, 'owmw_layout_disable_anims_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

		add_settings_field(
            'owmw_background_color', // ID
            __("Background color?",'owm-weather'), // Title
            array( $this, 'owmw_layout_background_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_background_image', // ID
            __("Background Image?",'owm-weather'), // Title
            array( $this, 'owmw_media_selector_settings_page_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_text_color', // ID
            __("Text color?",'owm-weather'), // Title
            array( $this, 'owmw_layout_text_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

		add_settings_field(
            'owmw_border_color', // ID
            __("Border color?",'owm-weather'), // Title
            array( $this, 'owmw_layout_border_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

		add_settings_field(
            'owmw_border_width', // ID
            __("Border width (in px)?",'owm-weather'), // Title
            array( $this, 'owmw_layout_border_width_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

		add_settings_field(
            'owmw_border_style', // ID
            __("Border style?",'owm-weather'), // Title
            array( $this, 'owmw_layout_border_style_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

		add_settings_field(
            'owmw_border_radius', // ID
            __("Border Radius?",'owm-weather'), // Title
            array( $this, 'owmw_layout_border_radius_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_size', // ID
           __("Weather size?",'owm-weather'), // Title
            array( $this, 'owmw_layout_size_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );


        add_settings_field(
            'owmw_custom_css', // ID
           __("Custom CSS?",'owm-weather'), // Title
            array( $this, 'owmw_layout_custom_css_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

		add_settings_field(
            'owmw_table_background_color', // ID
            __("Table background color?",'owm-weather'), // Title
            array( $this, 'owmw_layout_table_background_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_table_text_color', // ID
            __("Table text color?",'owm-weather'), // Title
            array( $this, 'owmw_layout_table_text_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

		add_settings_field(
            'owmw_table_border_color', // ID
            __("Table border color?",'owm-weather'), // Title
            array( $this, 'owmw_layout_table_border_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

		add_settings_field(
            'owmw_table_border_width', // ID
            __("Table border width (in px)?",'owm-weather'), // Title
            array( $this, 'owmw_layout_table_border_width_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

		add_settings_field(
            'owmw_table_border_style', // ID
            __("Table border style?",'owm-weather'), // Title
            array( $this, 'owmw_layout_table_border_style_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

		add_settings_field(
            'owmw_table_border_radius', // ID
            __("Table border Radius?",'owm-weather'), // Title
            array( $this, 'owmw_layout_table_border_radius_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

		//SYSTEM SECTION=========================================================================
        add_settings_section(
            'owmw_setting_section_advanced', // ID
            __("Advanced settings",'owm-weather'), // Title
            array( $this, 'owmw_print_section_info_advanced' ), // Callback
            'owmw-settings-admin-advanced' // Page
        );

        add_settings_field(
            'owmw_advanced_disable_cache', // ID
           __("Disable cache?",'owm-weather'), // Title
            array( $this, 'owmw_advanced_disable_cache_callback' ), // Callback
            'owmw-settings-admin-advanced', // Page
            'owmw_setting_section_advanced' // Section
        );

        add_settings_field(
            'owmw_advanced_cache_time', // ID
           __("Time cache refresh (in minutes)?",'owm-weather'), // Title
            array( $this, 'owmw_advanced_cache_time_callback' ), // Callback
            'owmw-settings-admin-advanced', // Page
            'owmw_setting_section_advanced' // Section
        );

        add_settings_field(
            'owmw_advanced_api_key', // ID
           __("Open Weather Map API key?",'owm-weather'), // Title
            array( $this, 'owmw_advanced_api_key_callback' ), // Callback
            'owmw-settings-admin-advanced', // Page
            'owmw_setting_section_advanced' // Section
        );

        add_settings_field(
            'owmw_advanced_disable_modal_js', // ID
           __("Disable Bootstrap Modal JS?",'owm-weather'), // Title
            array( $this, 'owmw_advanced_disable_modal_js_callback' ), // Callback
            'owmw-settings-admin-advanced', // Page
            'owmw_setting_section_advanced' // Section
        );

		//MAP SECTION =============================================================================

		add_settings_section(
            'owmw_setting_section_map', // ID
            __("Map settings",'owm-weather'), // Title
            array( $this, 'owmw_print_section_info_map' ), // Callback
            'owmw-settings-admin-map' // Page
        );

        add_settings_field(
            'owmw_map', // ID
            __("Map?",'owm-weather'), // Title
            array( $this, 'owmw_map_display_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_height', // ID
            __("Map height?",'owm-weather'), // Title
            array( $this, 'owmw_map_height_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

       add_settings_field(
            'owmw_map_opacity', // ID
            __("Layers opacity?",'owm-weather'), // Title
            array( $this, 'owmw_map_opacity_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_zoom', // ID
            __("Zoom?",'owm-weather'), // Title
            array( $this, 'owmw_map_zoom_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_disable_zoom_wheel', // ID
            __("Disable zoom wheel?",'owm-weather'), // Title
            array( $this, 'owmw_map_disable_zoom_wheel_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_stations', // ID
            __("Stations Layer?",'owm-weather'), // Title
            array( $this, 'owmw_map_layers_stations_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_clouds', // ID
            __("Clouds Layer?",'owm-weather'), // Title
            array( $this, 'owmw_map_layers_clouds_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_precipitation', // ID
            __("Precipitations Layer?",'owm-weather'), // Title
            array( $this, 'owmw_map_layers_precipitation_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_snow', // ID
            __("Snow Layer?",'owm-weather'), // Title
            array( $this, 'owmw_map_layers_snow_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_wind', // ID
            __("Wind Layer?",'owm-weather'), // Title
            array( $this, 'owmw_map_layers_wind_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_temperature', // ID
            __("Temperature Layer?",'owm-weather'), // Title
            array( $this, 'owmw_map_layers_temperature_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_pressure', // ID
            __("Pressure Layer?",'owm-weather'), // Title
            array( $this, 'owmw_map_layers_pressure_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        //SUPPORT SECTION==========================================================================
		add_settings_section(
            'owmw_setting_section_support', // ID
            '', // Title
            array( $this, 'print_section_info_support' ), // Callback
            'owmw-settings-admin-support' // Page
        );

		add_settings_field(
            'owmw_support_info', // ID
            '', // Title
            array( $this, 'owmw_support_info_callback' ), // Callback
            'owmw-settings-admin-support', // Page
            'owmw_setting_section_support' // Section
        );


		$this->options = get_option('owmw_option_name');
		if ($this->options) {
            foreach($this->options as $key => $value)
            {
                if ($value === '')
                {
                    unset($this->options[$key]);
                }
            }
        }
        update_option('owmw_option_name', $this->options);

    }
    
    /**
     * Sanitize each setting field
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize($input) {
    	if (!empty($input)) {
            foreach($input as $k => &$v) {
        		if (!in_array($v, array('yes', 'nobypass'))) {
    	        	$v = owmw_sanitize_validate_field(substr($k, 5), $v);
            	}
	        }
    	}

        return $input;
    }

    /**
     * Print the Section text
     */

	public function owmw_print_section_info_basic()
    {
        esc_html_e('Basic settings to bypass on all weather:', 'owm-weather');
        echo '<input type="hidden" name="owmw_option_name[owmw_version]" value="' . OWM_WEATHER_VERSION . '" />';
    }

	public function owmw_print_section_info_display()
    {
        esc_html_e('Display settings to bypass on all weather:', 'owm-weather');
    }

    public function owmw_print_section_info_layout()
    {
        esc_html_e('Layout settings to bypass on all weather:', 'owm-weather');
    }

    public function owmw_print_section_info_advanced()
    {
        esc_html_e('OWM Weather System settings:', 'owm-weather');
    }

	public function owmw_print_section_info_map()
    {
        esc_html_e('Map settings to bypass on all weather:', 'owm-weather');
    }

    public function print_section_info_support()
    {
        esc_html_e('&nbsp;', 'owm-weather');
    }

    /**
     * Get the settings option array and print one of its values
     */

	public function owmw_basic_unit_callback()
    {
        $selected = $this->options['owmw_unit'] ?? "nobypass";

		echo ' <select id="owmw_unit" name="owmw_option_name[owmw_unit]"> ';
        echo ' <option ';
        if ('nobypass' == $selected) echo 'selected="selected"';
        echo ' value="nobypass">'. esc_html__( 'No bypass', 'owm-weather' ) .'</option>';
		echo ' <option ';
		if ('imperial' == $selected) echo 'selected="selected"';
		echo ' value="imperial">'. esc_html__( 'Imperial', 'owm-weather' ) .'</option>';
		echo '<option ';
		if ('metric' == $selected) echo 'selected="selected"';
		echo ' value="metric">'. esc_html__( 'Metric', 'owm-weather' ) .'</option>';
		echo '</select>';
	}

	public function owmw_basic_time_format_callback()
    {
        $selected = $this->options['owmw_time_format'] ?? "nobypass";

		echo '<select id="owmw_time_format" name="owmw_option_name[owmw_time_format]"> ';
        echo ' <option ';
        if ('nobypass' == $selected) echo 'selected="selected"';
        echo ' value="nobypass">'. esc_html__( 'No bypass', 'owm-weather' ) .'</option>';
		echo ' <option ';
		echo '<option ';
		if ('12' == $selected) echo 'selected="selected"';
		echo ' value="12">'. esc_html__( '12 h', 'owm-weather' ) .'</option>';
		echo '<option ';
		if ('24' == $selected) echo 'selected="selected"';
		echo ' value="24">'. esc_html__( '24 h', 'owm-weather' ) .'</option>';
		echo '</select>';
	}

	public function owmw_basic_custom_timezone_callback()
    {
        $selected = $this->options['owmw_custom_timezone'] ?? "nobypass";

		echo '<select id="owmw_custom_timezone" name="owmw_option_name[owmw_custom_timezone]"> ';
        echo '<option ' . selected( 'nobypass', $selected, false ) . ' value="nobypass">'. esc_html__( 'No bypass', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Default', $selected, false ) . ' value="Default">'. esc_html__( 'WordPress timezone', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'local', $selected, false ) . ' value="local">'. esc_html__( 'Local timezone', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '-12', $selected, false ) . ' value="-12">'. esc_html__( 'UTC -12', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '-11', $selected, false ) . ' value="-11">'. esc_html__( 'UTC -11', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '-10', $selected, false ) . ' value="-10">'. esc_html__( 'UTC -10', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '-9', $selected, false ) . ' value="-9">'. esc_html__( 'UTC -9', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '-8', $selected, false ) . ' value="-8">'. esc_html__( 'UTC -8', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '-7', $selected, false ) . ' value="-7">'. esc_html__( 'UTC -7', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '-6', $selected, false ) . ' value="-6">'. esc_html__( 'UTC -6', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '-5', $selected, false ) . ' value="-5">'. esc_html__( 'UTC -5', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '-4', $selected, false ) . ' value="-4">'. esc_html__( 'UTC -4', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '-3', $selected, false ) . ' value="-3">'. esc_html__( 'UTC -3', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '-2', $selected, false ) . ' value="-2">'. esc_html__( 'UTC -2', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '-1', $selected, false ) . ' value="-1">'. esc_html__( 'UTC -1', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '0', $selected, false ) . ' value="0">'. esc_html__( 'UTC 0', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '1', $selected, false ) . ' value="1">'. esc_html__( 'UTC +1', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '2', $selected, false ) . ' value="2">'. esc_html__( 'UTC +2', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '3', $selected, false ) . ' value="3">'. esc_html__( 'UTC +3', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '4', $selected, false ) . ' value="4">'. esc_html__( 'UTC +4', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '5', $selected, false ) . ' value="5">'. esc_html__( 'UTC +5', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '6', $selected, false ) . ' value="6">'. esc_html__( 'UTC +6', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '7', $selected, false ) . ' value="7">'. esc_html__( 'UTC +7', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '8', $selected, false ) . ' value="8">'. esc_html__( 'UTC +8', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '9', $selected, false ) . ' value="9">'. esc_html__( 'UTC +9', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '10', $selected, false ) . ' value="10">'. esc_html__( 'UTC +10', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '11', $selected, false ) . ' value="11">'. esc_html__( 'UTC +11', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '12', $selected, false ) . ' value="12">'. esc_html__( 'UTC +12', 'owm-weather' ) .'</option>';
		echo '</select>';
	}

	public function owmw_basic_owm_language_callback()
    {
        $selected = $this->options['owmw_owm_language'] ?? "nobypass";

		echo '<select id="owmw_owm_language" name="owmw_option_name[owmw_owm_language]"> ';
        echo '<option ' . ('nobypass' == $selected ? 'selected="selected"' : '') . ' value="nobypass">'. esc_html__( 'No bypass', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Default', $selected, false ) . ' value="Default">'. esc_html__( 'Default', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'af', $selected, false ) . ' value="af">'. esc_html__( 'Afrikaans', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'al', $selected, false ) . ' value="al">'. esc_html__( 'Albanian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'ar', $selected, false ) . ' value="ar">'. esc_html__( 'Arabic', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'az', $selected, false ) . ' value="az">'. esc_html__( 'Azerbaijani', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'eu', $selected, false ) . ' value="eu">'. esc_html__( 'Basque', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'bg', $selected, false ) . ' value="bg">'. esc_html__( 'Bulgarian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'ca', $selected, false ) . ' value="ca">'. esc_html__( 'Catalan', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'zh_cn', $selected, false ) . ' value="zh_cn">'. esc_html__( 'Chinese Simplified', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'zh_tw', $selected, false ) . ' value="zh_tw">'. esc_html__( 'Chinese Traditional', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'hr', $selected, false ) . ' value="hr">'. esc_html__( 'Croatian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'cz', $selected, false ) . ' value="cz">'. esc_html__( 'Czech', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'da', $selected, false ) . ' value="da">'. esc_html__( 'Danish', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'nl', $selected, false ) . ' value="nl">'. esc_html__( 'Dutch', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'en', $selected, false ) . ' value="en">'. esc_html__( 'English', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'fi', $selected, false ) . ' value="fi">'. esc_html__( 'Finnish', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'fr', $selected, false ) . ' value="fr">'. esc_html__( 'French', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'gl', $selected, false ) . ' value="gl">'. esc_html__( 'Galician', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'de', $selected, false ) . ' value="de">'. esc_html__( 'German', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'el', $selected, false ) . ' value="el">'. esc_html__( 'Greek', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'he', $selected, false ) . ' value="he">'. esc_html__( 'Hebrew', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'hi', $selected, false ) . ' value="hi">'. esc_html__( 'Hindi', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'hu', $selected, false ) . ' value="hu">'. esc_html__( 'Hungarian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'id', $selected, false ) . ' value="id">'. esc_html__( 'Indonesian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'it', $selected, false ) . ' value="it">'. esc_html__( 'Italian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'ja', $selected, false ) . ' value="ja">'. esc_html__( 'Japanese', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'kr', $selected, false ) . ' value="kr">'. esc_html__( 'Korean', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'la', $selected, false ) . ' value="la">'. esc_html__( 'Latvian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'lt', $selected, false ) . ' value="lt">'. esc_html__( 'Lithuanian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'mk', $selected, false ) . ' value="mk">'. esc_html__( 'Macedonian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'no', $selected, false ) . ' value="no">'. esc_html__( 'Norwegian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'fa', $selected, false ) . ' value="fa">'. esc_html__( 'Persian (Farsi)', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'pl', $selected, false ) . ' value="pl">'. esc_html__( 'Polish', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'pt', $selected, false ) . ' value="pt">'. esc_html__( 'Portuguese', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'pt', $selected, false ) . ' value="pt">'. esc_html__( 'Português Brasil', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'ro', $selected, false ) . ' value="ro">'. esc_html__( 'Romanian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'ru', $selected, false ) . ' value="ru">'. esc_html__( 'Russian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'sr', $selected, false ) . ' value="sr">'. esc_html__( 'Serbian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'sv', $selected, false ) . ' value="sv">'. esc_html__( 'Swedish', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'sk', $selected, false ) . ' value="sk">'. esc_html__( 'Slovak', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'sl', $selected, false ) . ' value="sl">'. esc_html__( 'Slovenian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'sp', $selected, false ) . ' value="sp">'. esc_html__( 'Spanish', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'th', $selected, false ) . ' value="th">'. esc_html__( 'Thai', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'tr', $selected, false ) . ' value="tr">'. esc_html__( 'Turkish', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'ua', $selected, false ) . ' value="ua">'. esc_html__( 'Ukrainian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'vi', $selected, false ) . ' value="vi">'. esc_html__( 'Vietnamese', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'zu', $selected, false ) . ' value="zu">'. esc_html__( 'Zulu', 'owm-weather' ) .'</option>';
		echo '</select>';
	}

	public function owmw_layout_font_callback()
    {
        $selected = $this->options['owmw_font'] ?? "nobypass";

		echo '<select id="owmw_font" name="owmw_option_name[owmw_font]"> ';
        echo '<option ' . selected( 'nobypass', $selected, false ) . ' value="nobypass">'. esc_html__( 'No bypass', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Default', $selected, false ) . ' value="Default">'. esc_html__( 'Default', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Arvo', $selected, false ) . ' value="Arvo">'. esc_html__( 'Arvo', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Asap', $selected, false ) . ' value="Asap">'. esc_html__( 'Asap', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Bitter', $selected, false ) . ' value="Bitter">'. esc_html__( 'Bitter', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Droid Serif', $selected, false ) . ' value="Droid Serif">'. esc_html__( 'Droid Serif', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Exo 2', $selected, false ) . ' value="Exo 2">'. esc_html__( 'Exo 2', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Francois One', $selected, false ) . ' value="Francois One">'. esc_html__( 'Francois One', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Inconsolata', $selected, false ) . ' value="Inconsolata">'. esc_html__( 'Inconsolata', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Josefin Sans', $selected, false ) . ' value="Josefin Sans">'. esc_html__( 'Josefin Sans', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Lato', $selected, false ) . ' value="Lato">'. esc_html__( 'Lato', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Merriweather Sans', $selected, false ) . ' value="Merriweather Sans">'. esc_html__( 'Merriweather Sans', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Nunito', $selected, false ) . ' value="Nunito">'. esc_html__( 'Nunito', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Open Sans', $selected, false ) . ' value="Open Sans">'. esc_html__( 'Open Sans', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Oswald', $selected, false ) . ' value="Oswald">'. esc_html__( 'Oswald', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Pacifico', $selected, false ) . ' value="Pacifico">'. esc_html__( 'Pacifico', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Roboto', $selected, false ) . ' value="Roboto">'. esc_html__( 'Roboto', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Signika', $selected, false ) . ' value="Signika">'. esc_html__( 'Signika', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Source Sans Pro', $selected, false ) . ' value="Source Sans Pro">'. esc_html__( 'Source Sans Pro', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Tangerine', $selected, false ) . ' value="Tangerine">'. esc_html__( 'Tangerine', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Ubuntu', $selected, false ) . ' value="Ubuntu">'. esc_html__( 'Ubuntu', 'owm-weather' ) .'</option>';
		echo '</select>';
	}

	public function owmw_layout_template_callback()
    {
        $selected = $this->options['owmw_template'] ?? "nobypass";

		echo '<select id="owmw_template" name="owmw_option_name[owmw_template]"> ';
        echo '<option ' . selected( 'nobypass', $selected, false ) . ' value="nobypass">'. esc_html__( 'No bypass', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Default', $selected, false ) . ' value="Default">'. esc_html__( 'Default', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'card1', $selected, false ) . ' value="card1">'. esc_html__( 'Card 1', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'card2', $selected, false ) . ' value="card2">'. esc_html__( 'Card 2', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'chart1', $selected, false ) . ' value="chart1">'. esc_html__( 'Chart 1', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'chart2', $selected, false ) . ' value="chart2">'. esc_html__( 'Chart 2', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'slider1', $selected, false ) . ' value="slider1">'. esc_html__( 'Slider 1', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'slider2', $selected, false ) . ' value="slider2">'. esc_html__( 'Slider 2', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'table1', $selected, false ) . ' value="table1">'. esc_html__( 'Table 1', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'table2', $selected, false ) . ' value="table2">'. esc_html__( 'Table 2', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'custom1', $selected, false ) . ' value="custom1">'. esc_html__( 'Custom 1', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'custom2', $selected, false ) . ' value="custom2">'. esc_html__( 'Custom 2', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'debug', $selected, false ) . ' value="debug">'. esc_html__( 'Debug', 'owm-weather' ) .'</option>';
		echo '</select>';
	}

	public function owmw_layout_iconpack_callback()
    {
        $selected = $this->options['owmw_iconpack'] ?? "nobypass";

		echo '<select id="owmw_iconpack" name="owmw_option_name[owmw_iconpack]"> ';
        echo '<option ' . selected( 'nobypass', $selected, false ) . ' value="nobypass">'. esc_html__( 'No bypass', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Climacons', $selected, false ) . ' value="Climacons">'. esc_html__( 'Climacons', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'OpenWeatherMap', $selected, false ) . ' value="OpenWeatherMap">'. esc_html__( 'Open Weather Map', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'WeatherIcons', $selected, false ) . ' value="WeatherIcons">'. esc_html__( 'Weather Icons', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Forecast', $selected, false ) . ' value="Forecast">'. esc_html__( 'Forecast', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Dripicons', $selected, false ) . ' value="Dripicons">'. esc_html__( 'Dripicons', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Pixeden', $selected, false ) . ' value="Pixeden">'. esc_html__( 'Pixeden', 'owm-weather' ) .'</option>';
		echo '</select>';
	}

	public function owmw_display_current_city_name_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_current_city_name');
    }

	public function owmw_display_current_weather_symbol_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_current_weather_symbol');
    }

	public function owmw_display_weather_description_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_current_weather_description');
    }

    public function owmw_display_today_date_format_callback()
    {
        $check = $this->options['owmw_today_date_format'] ?? "nobypass";

        echo '<input id="owmw_today_date_format_nobypass" name="owmw_option_name[owmw_today_date_format]" type="radio"';
        if ('nobypass' == $check) echo 'checked="checked"';
        echo ' value="nobypass"/>';
        echo '<label for="owmw_today_date_format_nobypass">'. esc_html__( 'No bypass', 'owm-weather' ) .'</label>';

        echo '<br><br>';
        echo '<input id="owmw_today_date_format_none" name="owmw_option_name[owmw_today_date_format]" type="radio"';
        if ('none' == $check) echo 'checked="checked"';
        echo ' value="none"/>';
        echo '<label for="owmw_today_date_format_none">'. esc_html__( 'None', 'owm-weather' ) .'</label>';

        echo '<br><br>';

        echo '<input id="owmw_today_date_format_day" name="owmw_option_name[owmw_today_date_format]" type="radio"';
        if ('day' == $check) echo 'checked="checked"';
        echo ' value="day"/>';
        echo '<label for="owmw_today_date_format_day">'. esc_html__( 'Day of the week (eg: Sunday)', 'owm-weather' ) .'</label>';

        echo '<br><br>';

        echo '<input id="owmw_today_date_format_date" name="owmw_option_name[owmw_today_date_format]" type="radio"';
        if ('date' == $check) echo 'checked="checked"';
        echo ' value="date"/>';
        echo '<label for="owmw_today_date_format_date">'. esc_html__( 'Today\'s date', 'owm-weather' ) .'</label>';
    }

    public function owmw_display_date_temp_unit_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_display_temperature_unit');
    }

	public function owmw_display_sunrise_sunset_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_sunrise_sunset');
    }

	public function owmw_display_moonrise_moonset_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_moonrise_moonset');
    }

	public function owmw_display_wind_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_wind');
    }

    public function owmw_display_wind_unit_callback()
    {
        $selected = $this->options['owmw_wind_unit'] ?? "nobypass";

        echo ' <select id="owmw_wind_unit" name="owmw_option_name[owmw_wind_unit]"> ';
        echo ' <option ';
        if ('nobypass' == $selected) echo 'selected="selected"';
        echo ' value="nobypass">'. esc_html__( 'No bypass', 'owm-weather' ) .'</option>';
        echo ' <option ';
        if ('mi/h' == $selected) echo 'selected="selected"';
        echo ' value="mi/h">'. esc_html__( 'mi/h', 'owm-weather' ) .'</option>';
        echo '<option ';
        if ('m/s' == $selected) echo 'selected="selected"';
        echo ' value="m/s">'. esc_html__( 'm/s', 'owm-weather' ) .'</option>';
        echo '<option ';
        if ('km/h' == $selected) echo 'selected="selected"';
        echo ' value="km/h">'. esc_html__( 'km/h', 'owm-weather' ) .'</option>';
        echo '<option ';
        if ('kt' == $selected) echo 'selected="selected"';
        echo ' value="kt">'. esc_html__( 'kt', 'owm-weather' ) .'</option>';
        echo '</select>';
    }

	public function owmw_display_humidity_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_humidity');
    }

	public function owmw_display_dew_point_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_dew_point');
    }

	public function owmw_display_pressure_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_pressure');
    }

	public function owmw_display_cloudiness_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_cloudiness');
    }

    public function owmw_display_precipitation_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_precipitation');
    }

    public function owmw_display_visibility_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_visibility');
    }

    public function owmw_display_uv_index_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_uv_index');
    }

    public function owmw_display_alerts_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_alerts');
    }

    public function owmw_display_alerts_button_color_callback()
    {
        $check = $this->options['owmw_alerts_button_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_alerts_button_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    public function owmw_display_hour_forecast_no_callback()
    {
        $selected = $this->options['owmw_hours_forecast_no'] ?? null;

		echo ' <select id="owmw_hours_forecast_no" name="owmw_option_name[owmw_hours_forecast_no]"> ';
		echo $this->owmw_generate_hour_options($selected);
		echo '</select>';
	}

    public function owmw_display_hour_time_icons_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_hours_time_icons');
    }

    private function owmw_generate_hour_options($selected) {
   		$str = ' <option ';
   		$str .= selected( "nobypass", $selected, false );
   		$str .= ' value="nobypass">'. esc_html__( "No bypass", 'owm-weather' ) .'</option>';
   		$str .= ' <option ';
   		$str .= selected( "0", $selected, false );
   		$str .= ' value="0">'. esc_html__( "None", 'owm-weather' ) .'</option>';

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
    		$str .= ' value="' . $i . '">'. esc_html__( $h, 'owm-weather' ) .'</option>';
        }

        return $str;
    }

	public function owmw_display_current_temperature_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_current_temperature');
    }

	public function owmw_display_current_feels_like_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_current_feels_like');
    }

	public function owmw_display_forecast_no_callback()
    {
        $selected = $this->options['owmw_forecast_no'] ?? "nobypass";

		echo ' <select id="owmw_forecast_no" name="owmw_option_name[owmw_forecast_no]"> ';
		echo ' <option ';
		if ('nobypass' == $selected) echo 'selected="selected"';
		echo ' value="nobypass">'. esc_html__( 'No bypass', 'owm-weather' ) .'</option>';
		echo ' <option ';
		if ('0' == $selected) echo 'selected="selected"';
		echo ' value="0">'. esc_html__( 'None', 'owm-weather' ) .'</option>';
		echo ' <option ';
		if ('1' == $selected) echo 'selected="selected"';
		echo ' value="1">'. esc_html__( 'Today', 'owm-weather' ) .'</option>';
		echo '<option ';
		if ('2' == $selected) echo 'selected="selected"';
		echo ' value="2">'. esc_html__( 'Today + 1 day', 'owm-weather' ) .'</option>';
		echo '<option ';
		if ('3' == $selected) echo 'selected="selected"';
		echo ' value="3">'. esc_html__( 'Today + 2 days', 'owm-weather' ) .'</option>';
		echo '<option ';
		if ('4' == $selected) echo 'selected="selected"';
		echo ' value="4">'. esc_html__( 'Today + 3 days', 'owm-weather' ) .'</option>';
		echo '<option ';
		if ('5' == $selected) echo 'selected="selected"';
		echo ' value="5">'. esc_html__( 'Today + 4 days', 'owm-weather' ) .'</option>';
		echo '<option ';
		if ('6' == $selected) echo 'selected="selected"';
		echo ' value="6">'. esc_html__( 'Today + 5 days', 'owm-weather' ) .'</option>';
		echo '<option ';
		if ('7' == $selected) echo 'selected="selected"';
		echo ' value="7">'. esc_html__( 'Today + 6 days', 'owm-weather' ) .'</option>';
		echo '<option ';
		if ('8' == $selected) echo 'selected="selected"';
		echo ' value="8">'. esc_html__( 'Today + 7 days', 'owm-weather' ) .'</option>';
		echo '</select>';
	}

    public function owmw_display_forecast_precipitations_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_forecast_precipitations');
    }

	public function owmw_display_display_length_days_names_callback()
    {
   		$check = $this->options['owmw_display_length_days_names'] ?? "nobypass";

        echo '<input id="owmw_display_length_days_names_nobypass" name="owmw_option_name[owmw_display_length_days_names]" type="radio"';
		if ('nobypass' == $check) echo 'checked="yes"';
		echo ' value="nobypass"/>';
		echo '<label for="owmw_display_length_days_names_nobypass">'. esc_html__( 'No bypass', 'owm-weather' ) .'</label>';

		echo '<br><br>';

        echo '<input id="owmw_display_length_days_names_short" name="owmw_option_name[owmw_display_length_days_names]" type="radio"';
		if ('short' == $check) echo 'checked="yes"';
		echo ' value="short"/>';
		echo '<label for="owmw_display_length_days_names_short">'. esc_html__( 'Short days names', 'owm-weather' ) .'</label>';

		echo '<br><br>';

		echo '<input id="owmw_display_length_days_names_normal" name="owmw_option_name[owmw_display_length_days_names]" type="radio"';
		if ('normal' == $check) echo 'checked="yes"';
		echo ' value="normal"/>';
		echo '<label for="owmw_display_length_days_names_normal">'. esc_html__( 'Normal days names', 'owm-weather' ) .'</label>';
    }

    public function owmw_display_owm_link_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_owm_link');
    }

    public function owmw_display_last_update_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_last_update');
    }

	public function owmw_layout_disable_spinner_callback()
    {
        $this->owmw_bypassRadioButtonsDisable('owmw_disable_spinner');
    }

	public function owmw_layout_disable_anims_callback()
    {
        $this->owmw_bypassRadioButtonsDisable('owmw_disable_anims');
    }

    public function owmw_layout_background_color_callback()
    {
        $check = $this->options['owmw_background_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_background_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    function owmw_media_selector_settings_page_callback() {

    	// Save attachment ID
    	if ( isset( $_POST['submit_image_selector'] ) && isset( $_POST['background_image_attachment_id'] ) ) {
    		update_option( 'owmw_background_image', absint( $_POST['background_image_attachment_id'] ) );
    	}

    	echo '	<div class="background_image_preview_wrapper">';
    	echo '		<img id="background_image_preview" src="' . wp_get_attachment_url( ($this->options['owmw_background_image'] ?? '' ) ) . '" height="100px"' . (isset($this->options['owmw_background_image']) ? '' : ' style="display: none;"') . '>';
    	echo '	</div>';
    	echo '	<input id="select_background_image_button" type="button" class="button" value="' . esc_attr__( 'Select image', 'owm-weather' ) . '" />';
    	echo '	<input type="hidden" name="owmw_option_name[owmw_background_image]" id="background_image_attachment_id" value="' . esc_attr( $this->options['owmw_background_image'] ?? '' ) . '">';
    	echo '	<input id="clear_background_image_button" type="button" class="button" value="Clear" />';
    }

	public function owmw_layout_text_color_callback()
    {
        $check = $this->options['owmw_text_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_text_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

	public function owmw_layout_border_color_callback()
    {
        $check = $this->options['owmw_border_color'] ?? null;

		printf('<input name="owmw_option_name[owmw_border_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

	public function owmw_layout_border_width_callback()
    {
        $check = $this->options['owmw_border_width'] ?? null;

        printf('<input name="owmw_option_name[owmw_border_width]" type="number" min="0" value="%s" />', esc_attr($check));
    }

	public function owmw_layout_border_style_callback()
    {
        $selected = $this->options['owmw_border_style'] ?? "nobypass";

		echo '<select id="owmw_border_style" name="owmw_option_name[owmw_border_style]">';
		$this->owmw_borderStyleOptions($selected);
		echo '</select>';
	}

    private function owmw_borderStyleOptions($selected) {
        echo '<option ' . selected( 'nobypass', $selected, false ) . ' value="nobypass">'. esc_html__( 'No bypass', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'solid', $selected, false ) . ' value="solid">'. esc_html__( 'Solid', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'dotted', $selected, false ) . ' value="dotted">'. esc_html__( 'Dotted', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'dashed', $selected, false ) . ' value="dashed">'. esc_html__( 'Dashed', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'double', $selected, false ) . ' value="double">'. esc_html__( 'Double', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'groove', $selected, false ) . ' value="groove">'. esc_html__( 'Groove', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'inset', $selected, false ) . ' value="inset">'. esc_html__( 'Inset', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'outset', $selected, false ) . ' value="outset">'. esc_html__( 'Outset', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'ridge', $selected, false ) . ' value="ridge">'. esc_html__( 'Ridge', 'owm-weather' ) .'</option>';
    }

	public function owmw_layout_border_radius_callback()
    {
        $check = $this->options['owmw_border_radius'] ?? null;

        printf('<input name="owmw_option_name[owmw_border_radius]" type="number" min="0" value="%s" />', esc_attr($check));
    }

	public function owmw_layout_size_callback()
    {
        $selected = $this->options['owmw_size'] ?? "nobypass";

		echo ' <select id="owmw_size" name="owmw_option_name[owmw_size]"> ';
		echo ' <option ';
		if ('nobypass' == $selected) echo 'selected="selected"';
		echo ' value="nobypass">'. esc_html__( 'No bypass', 'owm-weather' ) .'</option>';
		echo ' <option ';
		if ('small' == $selected) echo 'selected="selected"';
		echo ' value="small">'. esc_html__( 'Small', 'owm-weather' ) .'</option>';
		echo '<option ';
		if ('medium' == $selected) echo 'selected="selected"';
		echo ' value="medium">'. esc_html__( 'Medium', 'owm-weather' ) .'</option>';
		echo '<option ';
		if ('large' == $selected) echo 'selected="selected"';
		echo ' value="large">'. esc_html__( 'Large', 'owm-weather' ) .'</option>';
		echo '</select>';
	}


	public function owmw_layout_custom_css_callback()
    {
        $check = $this->options['owmw_custom_css'] ?? '';

        printf('<textarea name="owmw_option_name[owmw_custom_css]" style="width:100%%;height:300px;">%s</textarea>', esc_textarea($check));
    }

    public function owmw_layout_table_background_color_callback()
    {
        $check = $this->options['owmw_table_background_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_table_background_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

	public function owmw_layout_table_text_color_callback()
    {
        $check = $this->options['owmw_table_text_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_table_text_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

	public function owmw_layout_table_border_color_callback()
    {
        $check = $this->options['owmw_table_border_color'] ?? null;

		printf('<input name="owmw_option_name[owmw_table_border_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

	public function owmw_layout_table_border_width_callback()
    {
        $check = $this->options['owmw_table_border_width'] ?? null;

        printf('<input name="owmw_option_name[owmw_table_border_width]" type="number" min="0" value="%s" />', esc_attr($check));
    }

	public function owmw_layout_table_border_style_callback()
    {
        $selected = $this->options['owmw_table_border_style'] ?? "nobypass";

		echo '<select id="owmw_border_style" name="owmw_option_name[owmw_table_border_style]">';
		$this->owmw_borderStyleOptions($selected);
		echo '</select>';
	}

	public function owmw_layout_table_border_radius_callback()
    {
        $check = $this->options['owmw_table_border_radius'] ?? null;

        printf('<input name="owmw_option_name[owmw_table_border_radius]" type="number" min="0" value="%s" />', esc_attr($check));
    }

	public function owmw_advanced_disable_cache_callback()
    {
		$check = $this->options['owmw_advanced_disable_cache'] ?? null;

        echo '<input id="owmw_advanced_disable_cache" name="owmw_option_name[owmw_advanced_disable_cache]" type="checkbox"';
		if ('yes' == $check) echo 'checked="yes"';
		echo ' value="yes"/>';
		echo '<label for="owmw_advanced_disable_cache">'. esc_html__( 'Disable weather cache? (Not recommended!)', 'owm-weather' ) .'</label>';
    }

	public function owmw_advanced_cache_time_callback()
    {
        $check = $this->options['owmw_advanced_cache_time'] ?? '';

        printf('<input type="number" min="10" name="owmw_option_name[owmw_advanced_cache_time]" value="%s" style="width:100%%" />', esc_attr( $check ));
        echo '<br /><br /><span class="dashicons dashicons-editor-help"></span>'.esc_html__('Default value: 30 minutes','owm-weather');
	}

    public function owmw_advanced_api_key_callback()
    {
        $check = $this->options['owmw_advanced_api_key'] ?? '';

        printf('<input type="text" name="owmw_option_name[owmw_advanced_api_key]" value="%s" style="width:100%%" />', esc_attr( $check ));
        echo '<br /><br /><span class="dashicons dashicons-editor-help"></span>'.esc_html__('Strongly recommended: ','owm-weather').'<a href="https://openweathermap.org/appid" target="_blank">'.esc_html__('Get your free OWM API key here','owm-weather').'</a>';
    }

    public function owmw_advanced_disable_modal_js_callback()
    {
        $check = $this->options['owmw_advanced_disable_modal_js'] ?? null;

        echo '<input id="owmw_advanced_disable_modal_js" name="owmw_option_name[owmw_advanced_disable_modal_js]" type="checkbox"';
        if ('yes' == $check) echo 'checked="yes"';
        echo ' value="yes"/>';
        echo '<label for="owmw_advanced_disable_modal_js">'. esc_html__( 'Disable Bootstrap? (Check this if you already include Bootstrap in your theme)', 'owm-weather' ) .'</label>';
	}

	public function owmw_map_display_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map');
    }

	public function owmw_map_height_callback()
    {
        $check = $this->options['owmw_map_height'] ?? '';

        printf('<input name="owmw_option_name[owmw_map_height]" type="number" min="300" value="%s" />', esc_attr($check));
	}

	public function owmw_map_opacity_callback()
	{
        $selected = $this->options['owmw_map_opacity'] ?? "nobypass";

		echo ' <select id="owmw_map_opacity" name="owmw_option_name[owmw_map_opacity]"> ';
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

	public function owmw_map_zoom_callback()
	{
        $selected = $this->options['owmw_map_zoom'] ?? "nobypass";

		echo ' <select id="owmw_map_zoom" name="owmw_option_name[owmw_map_zoom]"> ';
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

	public function owmw_map_disable_zoom_wheel_callback()
    {
        $this->owmw_bypassRadioButtonsDisable('owmw_map_disable_zoom_wheel');
    }

	public function owmw_map_layers_stations_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_stations');
    }

	public function owmw_map_layers_clouds_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_clouds');
    }

	public function owmw_map_layers_precipitation_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_precipitation');
    }

	public function owmw_map_layers_snow_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_snow');
    }

	public function owmw_map_layers_wind_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_wind');
    }

	public function owmw_map_layers_temperature_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_temperature');
    }

    public function owmw_map_layers_pressure_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_pressure');
    }

    public function owmw_support_info_callback()
    {
		echo
			'<h3>'. esc_html__("Having a problem with OWM Weather?", 'owm-weather').'</h3>
			<p><a href="https://wordpress.org/plugins/owm-weather/" target="_blank" title="'. esc_attr__("OWM Weather Forum on WordPress.org", 'owm-weather').'">'. esc_html__("OWM Weather Forum on WordPress.org", 'owm-weather').'</a></p>';
    }

    private function owmw_bypassRadioButtons($option) {
		$value = $this->options[$option] ?? 'nobypass';

        echo '<input id="' . esc_attr($option) . '_nobypass" name="owmw_option_name[' . esc_attr($option) . ']" type="radio"';
        if ('nobypass' == $value) echo 'checked="checked"';
        echo ' value="nobypass"/>';
        echo '<label for="' . esc_attr($option) . '_nobypass">'. esc_html__( 'No bypass', 'owm-weather' ) .'</label>';

        echo '<br><br>';
            
        echo '<input id="' . esc_attr($option) . '_on" name="owmw_option_name[' . esc_attr($option) . ']" type="radio"';
        if ('yes' == $value) echo 'checked="checked"';
        echo ' value="yes"/>';
        echo '<label for="' . esc_attr($option) . '_on">'. esc_html__( 'Show on all weather', 'owm-weather' ) .'</label>';

        echo '<br><br>';

        echo '<input id="' . esc_attr($option) . '_off" name="owmw_option_name[' . esc_attr($option) . ']" type="radio"';
        if ('no' == $value) echo 'checked="checked"';
        echo ' value="no"/>';
        echo '<label for="' . esc_attr($option) . '_off">'. esc_html__( 'Suppress on all weather', 'owm-weather' ) .'</label>';
    }

    private function owmw_bypassRadioButtonsDisable($option) {
		$value = $this->options[$option] ?? 'nobypass';

        echo '<input id="' . esc_attr($option) . '_nobypass" name="owmw_option_name[' . esc_attr($option) . ']" type="radio"';
        if ('nobypass' == $value) echo 'checked="checked"';
        echo ' value="nobypass"/>';
        echo '<label for="' . esc_attr($option) . '_nobypass">'. esc_html__( 'No bypass', 'owm-weather' ) .'</label>';

        echo '<br><br>';
            
        echo '<input id="' . esc_attr($option) . '_on" name="owmw_option_name[' . esc_attr($option) . ']" type="radio"';
        if ('no' == $value) echo 'checked="checked"';
        echo ' value="no"/>';
        echo '<label for="' . esc_attr($option) . '_on">'. esc_html__( 'Show on all weather', 'owm-weather' ) .'</label>';

        echo '<br><br>';

        echo '<input id="' . esc_attr($option) . '_off" name="owmw_option_name[' . esc_attr($option) . ']" type="radio"';
        if ('yes' == $value) echo 'checked="checked"';
        echo ' value="yes"/>';
        echo '<label for="' . esc_attr($option) . '_off">'. esc_html__( 'Suppress on all weather', 'owm-weather' ) .'</label>';
    }

}

if( is_admin() )
    $my_settings_page = new owmw_options();

function owmw_help_tab() {
    global $owmw_help_tab;
    $screen = get_current_screen();

    $screen->add_help_tab( array(
        'id'    => 'owmw_help_tab',
        'title' => esc_html__('Setup OWM Weather', 'owm-weather'),
        'content'   => '<ol>
<li>' . esc_html__('Goto Settings / OWM Weather.').'</li>
<li>' . esc_html__('Enter your API key, if you have one.').'</li>
<li>' . esc_html__('Check "Disable Bootstrap" if you already include Bootstrap in your theme.').'</li>
<li>' . esc_html__('Leave all other settings as is for now and hit "Save changes".', 'owm-weather' ) . '</li>
</ol>',
    ));
    $screen->add_help_tab( array(
        'id'    => 'owmw_help_tab2',
        'title' => esc_html__('Create your first weather', 'owm-weather'),
        'content'   => '<ol>
<li>' . esc_html__('Click on the new custom post type called "Weather" and create a "New Weather"') . '</li>
<li>' . esc_html__('Fill one of the tabs under "Get weather by..." or leave empty for user\'s location by ip address') . '</li>
<li>' . esc_html__('Choose "Measurement System" Imperial for Fahrenheit and miles, or "Metric" for Celsius and kilometers.') . '</li>
<li>' . esc_html__('Choose "12" or "24" hour time format.') . '</li>
<li>' . esc_html__('Under the "Display" tab, select the fields you would like to display.') . '</li>
<li>' . esc_html__('"Publish" your weather.') . '</li>
<li>' . esc_html__('Put the shortcode "[owm-weather id="XXX"/]" on a page or post, and look at the page.') . '</li>
<li>' . esc_html__('You just created your first weather! Now you can add additional fields under "Display", change the look-and-feel under "Layout" or add a map with layers under "Map".') . '</li>
<ol>',
    ));
}

function owmw_media_selector_print_scripts($id = null) {
    if (!empty($id)) {
        $image_id = get_post_meta($id,'_owmweather_background_image',true);
    } else {
        $options = get_option('owmw_option_name');
        $image_id = $options['owmw_background_image'] ?? null;
    }

   	$my_saved_attachment_post_id = !empty($image_id) ? $image_id : 0;

	?><script type='text/javascript'>

		jQuery( document ).ready( function( $ ) {

			// Uploading files
			var image_frame;
			var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
			var set_to_post_id = <?php echo wp_json_encode($my_saved_attachment_post_id); ?>; // Set this

			$('#clear_background_image_button').on('click', function( event ){

				event.preventDefault();

                $( '#background_image_attachment_id' ).val('');
                $( '#background_image_preview' ).attr('src', '');
                $( '#background_image_preview' ).hide();
			});

			$('#select_background_image_button').on('click', function( event ){

				event.preventDefault();

				// If the media frame already exists, reopen it.
				if ( image_frame ) {
					// Set the post ID to what we want
					image_frame.uploader.uploader.param( 'post_id', set_to_post_id );
					// Open frame
					image_frame.open();
					return;
				} else {
					// Set the wp.media post id so the uploader grabs the ID we want when initialised
					wp.media.model.settings.post.id = set_to_post_id;
				}

				// Create the media frame.
				image_frame = wp.media.frames.image_frame = wp.media({
					title: 'Select a background image',
					button: { text: 'Use this image' },
					library : { type : 'image' },
					multiple: false,
					currentValue: set_to_post_id
				});

                image_frame.on('open',function() {
                    const selection =  image_frame.state().get('selection');
                    const attachment = wp.media.attachment(set_to_post_id);
                    attachment.fetch();
                    selection.add( attachment ? [ attachment ] : [] );
                });
            
				// When an image is selected, run a callback.
				image_frame.on( 'select', function() {
					// We set multiple to false so only get one image from the uploader
					attachment = image_frame.state().get('selection').first().toJSON();

					// Do something with attachment.id and/or attachment.url here
					$( '#background_image_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
					$( '#background_image_attachment_id' ).val( attachment.id );
                    $( '#background_image_preview' ).show();

					// Restore the main post ID
					wp.media.model.settings.post.id = wp_media_post_id;
				});

					// Finally, open the modal
					image_frame.open();
			});

			// Restore the main ID when the add media button is pressed
			$( 'a.add_media' ).on( 'click', function() {
				wp.media.model.settings.post.id = wp_media_post_id;
			});
		});

	</script><?php
}
?>
