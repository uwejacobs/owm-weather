<?php
// To prevent calling the plugin directly
if ( !function_exists( 'add_action' ) ) {
    echo 'Please don&rsquo;t call the plugin directly. Thanks :)';
    exit;
}

class wow_options
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
    $wow_help_tab = add_options_page(
            'Settings Admin',
            'OWM Weather',
            'manage_options',
            'wow-settings-admin',
            array( $this, 'create_admin_page' )
        );
    add_action('load-'.$wow_help_tab, 'wow_help_tab');

    }



    /**
     * Options page callback
     */
    public function create_admin_page() {

    	wp_enqueue_media();
        add_action( 'admin_footer', 'media_selector_print_scripts' );

        // Set class property
        $this->options = get_option( 'wow_option_name' );
        ?>
        <?php $wow_info_version = get_plugin_data( plugin_dir_path( __FILE__ ).'/wpowmweather.php'); ?>

        <div id="wpowmweather-header">
			<div id="wpowmweather-clouds">
				<h3>
					<?php _e( 'OWM Weather', 'owm-weather' ); ?>
				</h3>
				<span class="wow-info-version"><?php print_r($wow_info_version['Version']); ?></span>
				<div id="wpowmweather-notice">
					<div class="small">
                         <div id="wpcloudy-notice">
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

                        <form action="https://www.paypal.com/donate" method="post" target="_top">
	    					<?php _e( 'Consider a donation', 'owm-weather' ); ?>:
                            <input type="hidden" name="hosted_button_id" value="PQDNJGKMLHAFU" />
                            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
                            <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
                        </form>

					</div>
				</div>
			</div>
		</div>

        <?php
            function wow_settings_admin_export() {
                ?>
                <div id="wow_export_form" class="metabox-holder">
                    <div class="postbox">
                        <h3><span><?php _e( 'Export Settings', 'owm-weather' ); ?></span></h3>
                        <div class="inside">
                            <p><?php _e( 'Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.', 'owm-weather' ); ?></p>
                            <form method="post">
                                <p><input type="hidden" name="wow_action" value="wow_export_settings" /></p>
                                <p>
                                    <?php wp_nonce_field( 'wow_export_nonce', 'wow_export_nonce' ); ?>
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
                                    <input type="file" name="wow_import_file"/>
                                </p>
                                <p>
                                    <input type="hidden" name="wow_action" value="wow_import_settings" />
                                    <?php wp_nonce_field( 'wow_import_nonce', 'wow_import_nonce' ); ?>
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
                                    <input type="hidden" name="wow_action" value="wow_reset_settings" />
                                    <?php wp_nonce_field( 'wow_reset_nonce', 'wow_reset_nonce' ); ?>
                                    <?php submit_button( __( 'Reset settings', 'owm-weather' ), 'secondary', 'submit', false ); ?>
                                </p>
                            </form>
                        </div><!-- .inside -->
                    </div><!-- .postbox -->
                </div><!-- .metabox-holder -->
                <?php

            }
        ?>

        <?php wow_settings_admin_export(); ?>

        <form method="post" action="options.php" class="wpowmweather-settings">
            <?php settings_fields( 'wow_cloudy_option_group' ); ?>

            <div id="wpowmweather-tabs">
                <h2 class="nav-tab-wrapper hide-if-no-js">
                	<ul>
						<li><a href="#tab_advanced" class="nav-tab"><?php _e( 'System', 'owm-weather' ); ?></a></li>
						<li><a href="#tab_basic" class="nav-tab"><?php _e( 'Basic', 'owm-weather' ); ?></a></li>
						<li><a href="#tab_display" class="nav-tab"><?php _e( 'Display', 'owm-weather' ); ?></a></li>
						<li><a href="#tab_layout" class="nav-tab"><?php _e( 'Layout', 'owm-weather' ); ?></a></li>
						<li><a href="#tab_map" class="nav-tab"><?php _e( 'Map', 'owm-weather' ); ?></a></li>
                        <li><a href="#tab_export" class="nav-tab"><?php _e( 'Import/Export/Reset', 'owm-weather' ); ?></a></li>
						<!--li><a href="#tab_support" class="nav-tab"><?php _e( 'Support', 'owm-weather' ); ?></a></li bugbug-->
                	</ul>
				</h2>

				<div id="wpowmweather-tabs-settings">
					<div class="wow-tab" id="tab_advanced"><?php do_settings_sections( 'wow-settings-admin-advanced' ); ?></div>
					<div class="wow-tab" id="tab_basic"><?php do_settings_sections( 'wow-settings-admin-basic' ); ?></div>
					<div class="wow-tab" id="tab_display"><?php do_settings_sections( 'wow-settings-admin-display' ); ?></div>
					<div class="wow-tab" id="tab_layout"><?php do_settings_sections( 'wow-settings-admin-layout' ); ?></div>
					<div class="wow-tab" id="tab_map"><?php do_settings_sections( 'wow-settings-admin-map' ); ?></div>
                    <div class="wow-tab" id="tab_export"></div>
					<!--div class="wow-tab" id="tab_support"><?php //bugbug do_settings_sections( 'wow-settings-admin-support' ); ?></div bugbug-->
				</div>
            </div>
            <script>jQuery("#wow_export_form").detach().appendTo('#tab_export')</script>
             <?php submit_button( __( 'Save changes', 'owm-weather' ), 'primary', 'submit', false ); ?>
        </form>

        <div class="wpowmweather-sidebar">
        	<div id="wpowmweather-cache" class="wpowmweather-module wpowmweather-inactive" style="height: 177px;">
				<h3><?php _e('OWM Weather cache','owm-weather'); ?></h3>
				<div class="wpowmweather-module-description">
					<div class="module-image">
						<div class="dashicons dashicons-trash"></div>
						<p><span class="module-image-badge"><?php _e('cache system','owm-weather'); ?></span></p>
					</div>

					<p><?php _e('Click this button to refresh weather cache.','owm-weather'); ?></p>

	            	<?php
						function wow_clear_all_cache() {
					    	if (isset($_GET['wow_clear_all_cache_nonce'])) {
						?>
						<div class="wpowmweather-module-actions">
							<p>
							    <a href="<?php print wp_nonce_url(admin_url('options-general.php?page=wow-settings-admin'), 'wow_clear_all_cache_action', 'wow_clear_all_cache_nonce');?>"
							        class="button button-secondary">
							        <?php esc_html_e('Clear cache!', 'owm-weather');?>
								</a>
							</p>
						</div>

						<?php

					    } else {

						?>
						<div class="wpowmweather-module-actions">
						    <a href="<?php print wp_nonce_url(admin_url('options-general.php?page=wow-settings-admin'), 'wow_clear_all_cache_action', 'wow_clear_all_cache');?>"
						        class="button button-secondary">
						        <?php esc_html_e('Clear cache!', 'owm-weather');?>
							</a>
						</div>

						<?php
							global $wpdb;
							$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_myweather%' ");
							$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_myweather%' ");
						}
					};
					?>
					<?php echo wow_clear_all_cache(); ?>
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
            'wow_cloudy_option_group', // Option group
            'wow_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

		//BASIC SECTION============================================================================
		add_settings_section(
            'wow_setting_section_basic', // ID
            __("Basic settings",'owm-weather'), // Title
            array( $this, 'print_section_info_basic' ), // Callback
            'wow-settings-admin-basic' // Page
        );

        add_settings_field(
            'wow_unit', // ID
           __("Bypass measurement system?",'owm-weather'), // Title
            array( $this, 'wow_basic_unit_callback' ), // Callback
            'wow-settings-admin-basic', // Page
            'wow_setting_section_basic' // Section
        );

        add_settings_field(
            'wow_time_format', // ID
           __("Bypass time format?",'owm-weather'), // Title
            array( $this, 'wow_basic_time_format_callback' ), // Callback
            'wow-settings-admin-basic', // Page
            'wow_setting_section_basic' // Section
        );

        add_settings_field(
            'wow_custom_timezone', // ID
           __("Bypass custom timezone?",'owm-weather'), // Title
            array( $this, 'wow_basic_custom_timezone_callback' ), // Callback
            'wow-settings-admin-basic', // Page
            'wow_setting_section_basic' // Section
        );

        add_settings_field(
            'wow_owm_language', // ID
           __("Bypass OpenWeatherMap language?",'owm-weather'), // Title
            array( $this, 'wow_basic_owm_language_callback' ), // Callback
            'wow-settings-admin-basic', // Page
            'wow_setting_section_basic' // Section
        );

		//DISPLAY SECTION==========================================================================
        add_settings_section(
            'wow_setting_section_display', // ID
            __("Display settings",'owm-weather'), // Title
            array( $this, 'print_section_info_display' ), // Callback
            'wow-settings-admin-display' // Page
        );

        add_settings_field(
            'wow_current_city_name', // ID
            __("Current City Name?",'owm-weather'), // Title
            array( $this, 'wow_display_current_city_name_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

        add_settings_field(
            'wow_current_weather_symbol', // ID
            __("Current weather symbol?",'owm-weather'), // Title
            array( $this, 'wow_display_current_weather_symbol_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

		add_settings_field(
            'wow_current_temperature', // ID
			__("Current temperature?",'owm-weather'), // Title
            array( $this, 'wow_display_current_temperature_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

		add_settings_field(
            'wow_current_feels_like', // ID
			__("Current feels like temperature?",'owm-weather'), // Title
            array( $this, 'wow_display_current_feels_like_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

		add_settings_field(
            'wow_current_weather_description', // ID
            __("Current short condition?",'owm-weather'), // Title
            array( $this, 'wow_display_weather_description_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

        add_settings_field(
            'wow_display_temperature_unit', // ID
            __("Temperature unit (C / F)?",'owm-weather'), // Title
            array( $this, 'wow_display_date_temp_unit_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

		add_settings_field(
            'wow_today_date_format', // ID
            __("Date?",'owm-weather'), // Title
            array( $this, 'wow_display_today_date_format_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

		add_settings_field(
            'wow_sunrise_sunset', // ID
            __("Sunrise + sunset?",'owm-weather'), // Title
            array( $this, 'wow_display_sunrise_sunset_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

		add_settings_field(
            'wow_moonrise_moonset', // ID
            __("Moonrise + moonset?",'owm-weather'), // Title
            array( $this, 'wow_display_moonrise_moonset_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

		add_settings_field(
            'wow_wind', // ID
            __("Wind?",'owm-weather'), // Title
            array( $this, 'wow_display_wind_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

        add_settings_field(
            'wow_wind_unit', // ID
            __("Wind unit?",'owm-weather'), // Title
            array( $this, 'wow_display_wind_unit_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

		add_settings_field(
            'wow_humidity', // ID
            __("Humidity?",'owm-weather'), // Title
            array( $this, 'wow_display_humidity_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

		add_settings_field(
            'wow_dew_point', // ID
            __("Dew Point?",'owm-weather'), // Title
            array( $this, 'wow_display_dew_point_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

		add_settings_field(
            'wow_pressure', // ID
           __("Pressure?",'owm-weather'), // Title
            array( $this, 'wow_display_pressure_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

		add_settings_field(
            'wow_cloudiness', // ID
            __("Cloudiness?",'owm-weather'), // Title
            array( $this, 'wow_display_cloudiness_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

        add_settings_field(
            'wow_precipitation', // ID
            __("Precipitation?",'owm-weather'), // Title
            array( $this, 'wow_display_precipitation_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

        add_settings_field(
            'wow_visibility', // ID
            __("Visibility?",'owm-weather'), // Title
            array( $this, 'wow_display_visibility_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

        add_settings_field(
            'wow_uv_index', // ID
            __("UV Index?",'owm-weather'), // Title
            array( $this, 'wow_display_uv_index_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

        add_settings_field(
            'wow_alerts', // ID
            __("Alerts?",'owm-weather'), // Title
            array( $this, 'wow_display_alerts_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

        add_settings_field(
            'wow_alerts_button_color', // ID
            __("Alerts Button Color?",'owm-weather'), // Title
            array( $this, 'wow_display_alerts_button_color_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

        add_settings_field(
            'wow_hours_forecast_no', // ID
            __("Number of hours forecast?",'owm-weather'), // Title
            array( $this, 'wow_display_hour_forecast_no_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

        add_settings_field(
            'wow_hours_time_icons', // ID
            __("Display time icons?",'owm-weather'), // Title
            array( $this, 'wow_display_hour_time_icons_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

        add_settings_field(
            'wow_forecast_no', // ID
            __("Number of days forecast",'owm-weather'), // Title
            array( $this, 'wow_display_forecast_no_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

        add_settings_field(
            'wow_forecast_precipitations', // ID
            __("Precipitations forecast?",'owm-weather'), // Title
            array( $this, 'wow_display_forecast_precipitations_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

		add_settings_field(
            'wow_display_length_days_names', // ID
			__("Length name days:",'owm-weather'), // Title
            array( $this, 'wow_display_display_length_days_names_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

        add_settings_field(
            'wow_owm_link', // ID
			__("Link to OpenWeatherMap?",'owm-weather'), // Title
            array( $this, 'wow_display_owm_link_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

		add_settings_field(
            'wow_last_update', // ID
            __("Update date?",'owm-weather'), // Title
            array( $this, 'wow_display_last_update_callback' ), // Callback
            'wow-settings-admin-display', // Page
            'wow_setting_section_display' // Section
        );

		//LAYOUT SECTION=========================================================================
        add_settings_section(
            'wow_setting_section_layout', // ID
            __("Layout settings",'owm-weather'), // Title
            array( $this, 'print_section_info_layout' ), // Callback
            'wow-settings-admin-layout' // Page
        );

		add_settings_field(
            'wow_template', // ID
            __("Template?"), // Title
            array( $this, 'wow_layout_template_callback' ), // Callback
            'wow-settings-admin-layout', // Page
            'wow_setting_section_layout' // Section
        );

		add_settings_field(
            'wow_font', // ID
            __("Font?"), // Title
            array( $this, 'wow_layout_font_callback' ), // Callback
            'wow-settings-admin-layout', // Page
            'wow_setting_section_layout' // Section
        );

		add_settings_field(
            'wow_iconpack', // ID
            __("Icon Pack?"), // Title
            array( $this, 'wow_layout_iconpack_callback' ), // Callback
            'wow-settings-admin-layout', // Page
            'wow_setting_section_layout' // Section
        );

		add_settings_field(
            'wow_disable_spinner', // ID
            __("Spinner?",'owm-weather'), // Title
            array( $this, 'wow_layout_disable_spinner_callback' ), // Callback
            'wow-settings-admin-layout', // Page
            'wow_setting_section_layout' // Section
        );

		add_settings_field(
            'wow_disable_anims', // ID
            __("Disable animations for main icon?",'owm-weather'), // Title
            array( $this, 'wow_layout_disable_anims_callback' ), // Callback
            'wow-settings-admin-layout', // Page
            'wow_setting_section_layout' // Section
        );

		add_settings_field(
            'wow_background_color', // ID
            __("Background color?",'owm-weather'), // Title
            array( $this, 'wow_layout_background_color_callback' ), // Callback
            'wow-settings-admin-layout', // Page
            'wow_setting_section_layout' // Section
        );

        add_settings_field(
            'wow_background_image', // ID
            __("Background Image?",'owm-weather'), // Title
            array( $this, 'media_selector_settings_page_callback' ), // Callback
            'wow-settings-admin-layout', // Page
            'wow_setting_section_layout' // Section
        );

        add_settings_field(
            'wow_text_color', // ID
            __("Text color?",'owm-weather'), // Title
            array( $this, 'wow_layout_text_color_callback' ), // Callback
            'wow-settings-admin-layout', // Page
            'wow_setting_section_layout' // Section
        );

		add_settings_field(
            'wow_border_color', // ID
            __("Border color?",'owm-weather'), // Title
            array( $this, 'wow_layout_border_color_callback' ), // Callback
            'wow-settings-admin-layout', // Page
            'wow_setting_section_layout' // Section
        );

		add_settings_field(
            'wow_border_width', // ID
            __("Border width (in px)?",'owm-weather'), // Title
            array( $this, 'wow_layout_border_width_callback' ), // Callback
            'wow-settings-admin-layout', // Page
            'wow_setting_section_layout' // Section
        );

		add_settings_field(
            'wow_border_style', // ID
            __("Border style?",'owm-weather'), // Title
            array( $this, 'wow_layout_border_style_callback' ), // Callback
            'wow-settings-admin-layout', // Page
            'wow_setting_section_layout' // Section
        );

		add_settings_field(
            'wow_border_radius', // ID
            __("Border Radius?",'owm-weather'), // Title
            array( $this, 'wow_layout_border_radius_callback' ), // Callback
            'wow-settings-admin-layout', // Page
            'wow_setting_section_layout' // Section
        );

        add_settings_field(
            'wow_size', // ID
           __("Weather size?",'owm-weather'), // Title
            array( $this, 'wow_layout_size_callback' ), // Callback
            'wow-settings-admin-layout', // Page
            'wow_setting_section_layout' // Section
        );


        add_settings_field(
            'wow_custom_css', // ID
           __("Custom CSS?",'owm-weather'), // Title
            array( $this, 'wow_layout_custom_css_callback' ), // Callback
            'wow-settings-admin-layout', // Page
            'wow_setting_section_layout' // Section
        );

		add_settings_field(
            'wow_table_background_color', // ID
            __("Table background color?",'owm-weather'), // Title
            array( $this, 'wow_layout_table_background_color_callback' ), // Callback
            'wow-settings-admin-layout', // Page
            'wow_setting_section_layout' // Section
        );

        add_settings_field(
            'wow_table_text_color', // ID
            __("Table text color?",'owm-weather'), // Title
            array( $this, 'wow_layout_table_text_color_callback' ), // Callback
            'wow-settings-admin-layout', // Page
            'wow_setting_section_layout' // Section
        );

		add_settings_field(
            'wow_table_border_color', // ID
            __("Table border color?",'owm-weather'), // Title
            array( $this, 'wow_layout_table_border_color_callback' ), // Callback
            'wow-settings-admin-layout', // Page
            'wow_setting_section_layout' // Section
        );

		add_settings_field(
            'wow_table_border_width', // ID
            __("Table border width (in px)?",'owm-weather'), // Title
            array( $this, 'wow_layout_table_border_width_callback' ), // Callback
            'wow-settings-admin-layout', // Page
            'wow_setting_section_layout' // Section
        );

		add_settings_field(
            'wow_table_border_style', // ID
            __("Table border style?",'owm-weather'), // Title
            array( $this, 'wow_layout_table_border_style_callback' ), // Callback
            'wow-settings-admin-layout', // Page
            'wow_setting_section_layout' // Section
        );

		add_settings_field(
            'wow_table_border_radius', // ID
            __("Table border Radius?",'owm-weather'), // Title
            array( $this, 'wow_layout_table_border_radius_callback' ), // Callback
            'wow-settings-admin-layout', // Page
            'wow_setting_section_layout' // Section
        );

		//SYSTEM SECTION=========================================================================
        add_settings_section(
            'wow_setting_section_advanced', // ID
            __("Advanced settings",'owm-weather'), // Title
            array( $this, 'print_section_info_advanced' ), // Callback
            'wow-settings-admin-advanced' // Page
        );

        add_settings_field(
            'wow_advanced_disable_cache', // ID
           __("Disable cache?",'owm-weather'), // Title
            array( $this, 'wow_advanced_disable_cache_callback' ), // Callback
            'wow-settings-admin-advanced', // Page
            'wow_setting_section_advanced' // Section
        );

        add_settings_field(
            'wow_advanced_cache_time', // ID
           __("Time cache refresh (in minutes)?",'owm-weather'), // Title
            array( $this, 'wow_advanced_cache_time_callback' ), // Callback
            'wow-settings-admin-advanced', // Page
            'wow_setting_section_advanced' // Section
        );

        add_settings_field(
            'wow_advanced_api_key', // ID
           __("Open Weather Map API key?",'owm-weather'), // Title
            array( $this, 'wow_advanced_api_key_callback' ), // Callback
            'wow-settings-admin-advanced', // Page
            'wow_setting_section_advanced' // Section
        );

        add_settings_field(
            'wow_advanced_disable_modal_js', // ID
           __("Disable Bootstrap Modal JS?",'owm-weather'), // Title
            array( $this, 'wow_advanced_disable_modal_js_callback' ), // Callback
            'wow-settings-admin-advanced', // Page
            'wow_setting_section_advanced' // Section
        );

		//MAP SECTION =============================================================================

		add_settings_section(
            'wow_setting_section_map', // ID
            __("Map settings",'owm-weather'), // Title
            array( $this, 'print_section_info_map' ), // Callback
            'wow-settings-admin-map' // Page
        );

        add_settings_field(
            'wow_map', // ID
            __("Map?",'owm-weather'), // Title
            array( $this, 'wow_map_display_callback' ), // Callback
            'wow-settings-admin-map', // Page
            'wow_setting_section_map' // Section
        );

        add_settings_field(
            'wow_map_height', // ID
            __("Map height?",'owm-weather'), // Title
            array( $this, 'wow_map_height_callback' ), // Callback
            'wow-settings-admin-map', // Page
            'wow_setting_section_map' // Section
        );

       add_settings_field(
            'wow_map_opacity', // ID
            __("Layers opacity?",'owm-weather'), // Title
            array( $this, 'wow_map_opacity_callback' ), // Callback
            'wow-settings-admin-map', // Page
            'wow_setting_section_map' // Section
        );

        add_settings_field(
            'wow_map_zoom', // ID
            __("Zoom?",'owm-weather'), // Title
            array( $this, 'wow_map_zoom_callback' ), // Callback
            'wow-settings-admin-map', // Page
            'wow_setting_section_map' // Section
        );

        add_settings_field(
            'wow_map_disable_zoom_wheel', // ID
            __("Disable zoom wheel?",'owm-weather'), // Title
            array( $this, 'wow_map_disable_zoom_wheel_callback' ), // Callback
            'wow-settings-admin-map', // Page
            'wow_setting_section_map' // Section
        );

        add_settings_field(
            'wow_map_stations', // ID
            __("Stations Layer?",'owm-weather'), // Title
            array( $this, 'wow_map_layers_stations_callback' ), // Callback
            'wow-settings-admin-map', // Page
            'wow_setting_section_map' // Section
        );

        add_settings_field(
            'wow_map_clouds', // ID
            __("Clouds Layer?",'owm-weather'), // Title
            array( $this, 'wow_map_layers_clouds_callback' ), // Callback
            'wow-settings-admin-map', // Page
            'wow_setting_section_map' // Section
        );

        add_settings_field(
            'wow_map_precipitation', // ID
            __("Precipitations Layer?",'owm-weather'), // Title
            array( $this, 'wow_map_layers_precipitation_callback' ), // Callback
            'wow-settings-admin-map', // Page
            'wow_setting_section_map' // Section
        );

        add_settings_field(
            'wow_map_snow', // ID
            __("Snow Layer?",'owm-weather'), // Title
            array( $this, 'wow_map_layers_snow_callback' ), // Callback
            'wow-settings-admin-map', // Page
            'wow_setting_section_map' // Section
        );

        add_settings_field(
            'wow_map_wind', // ID
            __("Wind Layer?",'owm-weather'), // Title
            array( $this, 'wow_map_layers_wind_callback' ), // Callback
            'wow-settings-admin-map', // Page
            'wow_setting_section_map' // Section
        );

        add_settings_field(
            'wow_map_temperature', // ID
            __("Temperature Layer?",'owm-weather'), // Title
            array( $this, 'wow_map_layers_temperature_callback' ), // Callback
            'wow-settings-admin-map', // Page
            'wow_setting_section_map' // Section
        );

        add_settings_field(
            'wow_map_pressure', // ID
            __("Pressure Layer?",'owm-weather'), // Title
            array( $this, 'wow_map_layers_pressure_callback' ), // Callback
            'wow-settings-admin-map', // Page
            'wow_setting_section_map' // Section
        );

        //SUPPORT SECTION==========================================================================
		add_settings_section(
            'wow_setting_section_support', // ID
            '', // Title
            array( $this, 'print_section_info_support' ), // Callback
            'wow-settings-admin-support' // Page
        );

		add_settings_field(
            'wow_support_info', // ID
            '', // Title
            array( $this, 'wow_support_info_callback' ), // Callback
            'wow-settings-admin-support', // Page
            'wow_setting_section_support' // Section
        );


		$this->options = get_option('wow_option_name');
		if ($this->options) {
            foreach($this->options as $key => $value)
            {
                if ($value === '')
                {
                    unset($this->options[$key]);
                }
            }
        }
        update_option('wow_option_name', $this->options);

    }
    
    private function renameOptionField($old, $new) {
        if (!empty($this->options[$old])) {
            $this->options[$new] = $this->options[$old];
            unset($this->options[$old]);
        }

        update_option('wow_option_name', $this->options);
    }

    /**
     * Sanitize each setting field
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize($input) {
        foreach($input as $k => &$v) {
    		if ($k == 'wow_custom_css') {
	        	$v = sanitize_textarea_field(trim($v));
        	} else {
	        	$v = sanitize_text_field(trim($v));
        	}
        }

        return $input;
    }

    /**
     * Print the Section text
     */

	public function print_section_info_basic()
    {
        print __('Basic settings to bypass on all weather:', 'owm-weather');
        echo '<input type="hidden" name="wow_option_name[wow_version]" value="' . WP_OWM_WEATHER_VERSION . '" />';
    }

	public function print_section_info_display()
    {
        print __('Display settings to bypass on all weather:', 'owm-weather');
    }

    public function print_section_info_layout()
    {
        print __('Layout settings to bypass on all weather:', 'owm-weather');
    }

    public function print_section_info_advanced()
    {
        print __('OWM Weather System settings:', 'owm-weather');
    }

	public function print_section_info_map()
    {
        print __('Map settings to bypass on all weather:', 'owm-weather');
    }

    public function print_section_info_support()
    {
        print __('&nbsp;', 'owm-weather');
    }

    /**
     * Get the settings option array and print one of its values
     */

	public function wow_basic_unit_callback()
    {
        $selected = $this->options['wow_unit'] ?? "nobypass";

		echo ' <select id="wow_unit" name="wow_option_name[wow_unit]"> ';
        echo ' <option ';
        if ('nobypass' == $selected) echo 'selected="selected"';
        echo ' value="nobypass">'. __( 'No bypass', 'owm-weather' ) .'</option>';
		echo ' <option ';
		if ('imperial' == $selected) echo 'selected="selected"';
		echo ' value="imperial">'. __( 'Imperial', 'owm-weather' ) .'</option>';
		echo '<option ';
		if ('metric' == $selected) echo 'selected="selected"';
		echo ' value="metric">'. __( 'Metric', 'owm-weather' ) .'</option>';
		echo '</select>';
	}

	public function wow_basic_time_format_callback()
    {
        $selected = $this->options['wow_time_format'] ?? "nobypass";

		echo '<select id="wow_time_format" name="wow_option_name[wow_time_format]"> ';
        echo ' <option ';
        if ('nobypass' == $selected) echo 'selected="selected"';
        echo ' value="nobypass">'. __( 'No bypass', 'owm-weather' ) .'</option>';
		echo ' <option ';
		echo '<option ';
		if ('12' == $selected) echo 'selected="selected"';
		echo ' value="12">'. __( '12 h', 'owm-weather' ) .'</option>';
		echo '<option ';
		if ('24' == $selected) echo 'selected="selected"';
		echo ' value="24">'. __( '24 h', 'owm-weather' ) .'</option>';
		echo '</select>';
	}

	public function wow_basic_custom_timezone_callback()
    {
        $selected = $this->options['wow_custom_timezone'] ?? "nobypass";

		echo '<select id="wow_custom_timezone" name="wow_option_name[wow_custom_timezone]"> ';
        echo '<option ' . selected( 'nobypass', $selected, false ) . ' value="nobypass">'. __( 'No bypass', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Default', $selected, false ) . ' value="Default">'. __( 'WordPress timezone', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '-12', $selected, false ) . ' value="-12">'. __( 'UTC -12', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '-11', $selected, false ) . ' value="-11">'. __( 'UTC -11', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '-10', $selected, false ) . ' value="-10">'. __( 'UTC -10', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '-9', $selected, false ) . ' value="-9">'. __( 'UTC -9', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '-8', $selected, false ) . ' value="-8">'. __( 'UTC -8', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '-7', $selected, false ) . ' value="-7">'. __( 'UTC -7', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '-6', $selected, false ) . ' value="-6">'. __( 'UTC -6', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '-5', $selected, false ) . ' value="-5">'. __( 'UTC -5', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '-4', $selected, false ) . ' value="-4">'. __( 'UTC -4', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '-3', $selected, false ) . ' value="-3">'. __( 'UTC -3', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '-2', $selected, false ) . ' value="-2">'. __( 'UTC -2', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '-1', $selected, false ) . ' value="-1">'. __( 'UTC -1', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '0', $selected, false ) . ' value="0">'. __( 'UTC 0', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '1', $selected, false ) . ' value="1">'. __( 'UTC +1', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '2', $selected, false ) . ' value="2">'. __( 'UTC +2', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '3', $selected, false ) . ' value="3">'. __( 'UTC +3', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '4', $selected, false ) . ' value="4">'. __( 'UTC +4', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '5', $selected, false ) . ' value="5">'. __( 'UTC +5', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '6', $selected, false ) . ' value="6">'. __( 'UTC +6', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '7', $selected, false ) . ' value="7">'. __( 'UTC +7', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '8', $selected, false ) . ' value="8">'. __( 'UTC +8', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '9', $selected, false ) . ' value="9">'. __( 'UTC +9', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '10', $selected, false ) . ' value="10">'. __( 'UTC +10', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '11', $selected, false ) . ' value="11">'. __( 'UTC +11', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( '12', $selected, false ) . ' value="12">'. __( 'UTC +12', 'owm-weather' ) .'</option>';
		echo '</select>';
	}

	public function wow_basic_owm_language_callback()
    {
        $selected = $this->options['wow_owm_language'] ?? "nobypass";

		echo '<select id="wow_owm_language" name="wow_option_name[wow_owm_language]"> ';
        echo '<option ' . ('nobypass' == $selected ? 'selected="selected"' : '') . ' value="nobypass">'. __( 'No bypass', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Default', $selected, false ) . ' value="Default">'. __( 'Default', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'af', $selected, false ) . ' value="af">'. __( 'Afrikaans', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'al', $selected, false ) . ' value="al">'. __( 'Albanian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'ar', $selected, false ) . ' value="ar">'. __( 'Arabic', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'az', $selected, false ) . ' value="az">'. __( 'Azerbaijani', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'eu', $selected, false ) . ' value="eu">'. __( 'Basque', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'bg', $selected, false ) . ' value="bg">'. __( 'Bulgarian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'ca', $selected, false ) . ' value="ca">'. __( 'Catalan', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'zh_cn', $selected, false ) . ' value="zh_cn">'. __( 'Chinese Simplified', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'zh_tw', $selected, false ) . ' value="zh_tw">'. __( 'Chinese Traditional', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'hr', $selected, false ) . ' value="hr">'. __( 'Croatian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'cz', $selected, false ) . ' value="cz">'. __( 'Czech', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'da', $selected, false ) . ' value="da">'. __( 'Danish', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'nl', $selected, false ) . ' value="nl">'. __( 'Dutch', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'en', $selected, false ) . ' value="en">'. __( 'English', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'fi', $selected, false ) . ' value="fi">'. __( 'Finnish', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'fr', $selected, false ) . ' value="fr">'. __( 'French', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'gl', $selected, false ) . ' value="gl">'. __( 'Galician', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'de', $selected, false ) . ' value="de">'. __( 'German', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'el', $selected, false ) . ' value="el">'. __( 'Greek', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'he', $selected, false ) . ' value="he">'. __( 'Hebrew', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'hi', $selected, false ) . ' value="hi">'. __( 'Hindi', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'hu', $selected, false ) . ' value="hu">'. __( 'Hungarian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'id', $selected, false ) . ' value="id">'. __( 'Indonesian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'it', $selected, false ) . ' value="it">'. __( 'Italian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'ja', $selected, false ) . ' value="ja">'. __( 'Japanese', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'kr', $selected, false ) . ' value="kr">'. __( 'Korean', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'la', $selected, false ) . ' value="la">'. __( 'Latvian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'lt', $selected, false ) . ' value="lt">'. __( 'Lithuanian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'mk', $selected, false ) . ' value="mk">'. __( 'Macedonian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'no', $selected, false ) . ' value="no">'. __( 'Norwegian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'fa', $selected, false ) . ' value="fa">'. __( 'Persian (Farsi)', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'pl', $selected, false ) . ' value="pl">'. __( 'Polish', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'pt', $selected, false ) . ' value="pt">'. __( 'Portuguese', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'pt', $selected, false ) . ' value="pt">'. __( 'Português Brasil', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'ro', $selected, false ) . ' value="ro">'. __( 'Romanian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'ru', $selected, false ) . ' value="ru">'. __( 'Russian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'sr', $selected, false ) . ' value="sr">'. __( 'Serbian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'sv', $selected, false ) . ' value="sv">'. __( 'Swedish', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'sk', $selected, false ) . ' value="sk">'. __( 'Slovak', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'sl', $selected, false ) . ' value="sl">'. __( 'Slovenian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'sp', $selected, false ) . ' value="sp">'. __( 'Spanish', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'th', $selected, false ) . ' value="th">'. __( 'Thai', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'tr', $selected, false ) . ' value="tr">'. __( 'Turkish', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'ua', $selected, false ) . ' value="ua">'. __( 'Ukrainian', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'vi', $selected, false ) . ' value="vi">'. __( 'Vietnamese', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'zu', $selected, false ) . ' value="zu">'. __( 'Zulu', 'owm-weather' ) .'</option>';
		echo '</select>';
	}

	public function wow_layout_font_callback()
    {
        $selected = $this->options['wow_font'] ?? "nobypass";

		echo '<select id="wow_font" name="wow_option_name[wow_font]"> ';
        echo '<option ' . selected( 'nobypass', $selected, false ) . ' value="nobypass">'. __( 'No bypass', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Default', $selected, false ) . ' value="Default">'. __( 'Default', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Arvo', $selected, false ) . ' value="Arvo">'. __( 'Arvo', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Asap', $selected, false ) . ' value="Asap">'. __( 'Asap', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Bitter', $selected, false ) . ' value="Bitter">'. __( 'Bitter', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Droid Serif', $selected, false ) . ' value="Droid Serif">'. __( 'Droid Serif', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Exo 2', $selected, false ) . ' value="Exo 2">'. __( 'Exo 2', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Francois One', $selected, false ) . ' value="Francois One">'. __( 'Francois One', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Inconsolata', $selected, false ) . ' value="Inconsolata">'. __( 'Inconsolata', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Josefin Sans', $selected, false ) . ' value="Josefin Sans">'. __( 'Josefin Sans', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Lato', $selected, false ) . ' value="Lato">'. __( 'Lato', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Merriweather Sans', $selected, false ) . ' value="Merriweather Sans">'. __( 'Merriweather Sans', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Nunito', $selected, false ) . ' value="Nunito">'. __( 'Nunito', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Open Sans', $selected, false ) . ' value="Open Sans">'. __( 'Open Sans', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Oswald', $selected, false ) . ' value="Oswald">'. __( 'Oswald', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Pacifico', $selected, false ) . ' value="Pacifico">'. __( 'Pacifico', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Roboto', $selected, false ) . ' value="Roboto">'. __( 'Roboto', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Signika', $selected, false ) . ' value="Signika">'. __( 'Signika', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Source Sans Pro', $selected, false ) . ' value="Source Sans Pro">'. __( 'Source Sans Pro', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Tangerine', $selected, false ) . ' value="Tangerine">'. __( 'Tangerine', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Ubuntu', $selected, false ) . ' value="Ubuntu">'. __( 'Ubuntu', 'owm-weather' ) .'</option>';
		echo '</select>';
	}

	public function wow_layout_template_callback()
    {
        $selected = $this->options['wow_template'] ?? "nobypass";

		echo '<select id="wow_template" name="wow_option_name[wow_template]"> ';
        echo '<option ' . selected( 'nobypass', $selected, false ) . ' value="nobypass">'. __( 'No bypass', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Default', $selected, false ) . ' value="Default">'. __( 'Default', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'card1', $selected, false ) . ' value="card1">'. __( 'Card 1', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'card2', $selected, false ) . ' value="card2">'. __( 'Card 2', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'chart1', $selected, false ) . ' value="chart1">'. __( 'Chart 1', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'chart2', $selected, false ) . ' value="chart2">'. __( 'Chart 2', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'slider1', $selected, false ) . ' value="slider1">'. __( 'Slider 1', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'slider2', $selected, false ) . ' value="slider2">'. __( 'Slider 2', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'table1', $selected, false ) . ' value="table1">'. __( 'Table 1', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'table2', $selected, false ) . ' value="table2">'. __( 'Table 2', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'custom1', $selected, false ) . ' value="custom1">'. __( 'Custom 1', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'custom2', $selected, false ) . ' value="custom2">'. __( 'Custom 2', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'debug', $selected, false ) . ' value="debug">'. __( 'Debug', 'owm-weather' ) .'</option>';
		echo '</select>';
	}

	public function wow_layout_iconpack_callback()
    {
        $selected = $this->options['wow_iconpack'] ?? "nobypass";

		echo '<select id="wow_iconpack" name="wow_option_name[wow_iconpack]"> ';
        echo '<option ' . selected( 'nobypass', $selected, false ) . ' value="nobypass">'. __( 'No bypass', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Climacons', $selected, false ) . ' value="Climacons">'. __( 'Climacons', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'OpenWeatherMap', $selected, false ) . ' value="OpenWeatherMap">'. __( 'Open Weather Map', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'WeatherIcons', $selected, false ) . ' value="WeatherIcons">'. __( 'Weather Icons', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Forecast', $selected, false ) . ' value="Forecast">'. __( 'Forecast', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Dripicons', $selected, false ) . ' value="Dripicons">'. __( 'Dripicons', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'Pixeden', $selected, false ) . ' value="Pixeden">'. __( 'Pixeden', 'owm-weather' ) .'</option>';
		echo '</select>';
	}

	public function wow_display_current_city_name_callback()
    {
        $this->bypassRadioButtons('wow_current_city_name');
    }

	public function wow_display_current_weather_symbol_callback()
    {
        $this->bypassRadioButtons('wow_current_weather_symbol');
    }

	public function wow_display_weather_description_callback()
    {
        $this->bypassRadioButtons('wow_current_weather_description');
    }

    public function wow_display_today_date_format_callback()
    {
        $check = $this->options['wow_today_date_format'] ?? "nobypass";

        echo '<input id="wow_today_date_format_nobypass" name="wow_option_name[wow_today_date_format]" type="radio"';
        if ('nobypass' == $check) echo 'checked="checked"';
        echo ' value="nobypass"/>';
        echo '<label for="wow_today_date_format_nobypass">'. __( 'No bypass', 'owm-weather' ) .'</label>';

        echo '<br><br>';
        echo '<input id="wow_today_date_format_none" name="wow_option_name[wow_today_date_format]" type="radio"';
        if ('none' == $check) echo 'checked="checked"';
        echo ' value="none"/>';
        echo '<label for="wow_today_date_format_none">'. __( 'None', 'owm-weather' ) .'</label>';

        echo '<br><br>';

        echo '<input id="wow_today_date_format_day" name="wow_option_name[wow_today_date_format]" type="radio"';
        if ('day' == $check) echo 'checked="checked"';
        echo ' value="day"/>';
        echo '<label for="wow_today_date_format_day">'. __( 'Day of the week (eg: Sunday)', 'owm-weather' ) .'</label>';

        echo '<br><br>';

        echo '<input id="wow_today_date_format_date" name="wow_option_name[wow_today_date_format]" type="radio"';
        if ('date' == $check) echo 'checked="checked"';
        echo ' value="date"/>';
        echo '<label for="wow_today_date_format_date">'. __( 'Today\'s date', 'owm-weather' ) .'</label>';
    }

    public function wow_display_date_temp_unit_callback()
    {
        $this->bypassRadioButtons('wow_display_temperature_unit');
    }

	public function wow_display_sunrise_sunset_callback()
    {
        $this->bypassRadioButtons('wow_sunrise_sunset');
    }

	public function wow_display_moonrise_moonset_callback()
    {
        $this->bypassRadioButtons('wow_moonrise_moonset');
    }

	public function wow_display_wind_callback()
    {
        $this->bypassRadioButtons('wow_wind');
    }

    public function wow_display_wind_unit_callback()
    {
        $selected = $this->options['wow_wind_unit'] ?? "nobypass";

        echo ' <select id="wow_wind_unit" name="wow_option_name[wow_wind_unit]"> ';
        echo ' <option ';
        if ('nobypass' == $selected) echo 'selected="selected"';
        echo ' value="nobypass">'. __( 'No bypass', 'owm-weather' ) .'</option>';
        echo ' <option ';
        if ('1' == $selected) echo 'selected="selected"';
        echo ' value="1">'. __( 'mi/h', 'owm-weather' ) .'</option>';
        echo '<option ';
        if ('2' == $selected) echo 'selected="selected"';
        echo ' value="2">'. __( 'm/s', 'owm-weather' ) .'</option>';
        echo '<option ';
        if ('3' == $selected) echo 'selected="selected"';
        echo ' value="3">'. __( 'km/h', 'owm-weather' ) .'</option>';
        echo '<option ';
        if ('4' == $selected) echo 'selected="selected"';
        echo ' value="4">'. __( 'kt', 'owm-weather' ) .'</option>';
        echo '</select>';
    }

	public function wow_display_humidity_callback()
    {
        $this->bypassRadioButtons('wow_humidity');
    }

	public function wow_display_dew_point_callback()
    {
        $this->bypassRadioButtons('wow_dew_point');
    }

	public function wow_display_pressure_callback()
    {
        $this->bypassRadioButtons('wow_pressure');
    }

	public function wow_display_cloudiness_callback()
    {
        $this->bypassRadioButtons('wow_cloudiness');
    }

    public function wow_display_precipitation_callback()
    {
        $this->bypassRadioButtons('wow_precipitation');
    }

    public function wow_display_visibility_callback()
    {
        $this->bypassRadioButtons('wow_visibility');
    }

    public function wow_display_uv_index_callback()
    {
        $this->bypassRadioButtons('wow_uv_index');
    }

    public function wow_display_alerts_callback()
    {
        $this->bypassRadioButtons('wow_alerts');
    }

    public function wow_display_alerts_button_color_callback()
    {
        $check = $this->options['wow_alerts_button_color'] ?? null;

        printf('<input name="wow_option_name[wow_alerts_button_color]" type="text" value="%s" class="wpowmweather_admin_color_picker" />', esc_attr($check));
    }

    public function wow_display_hour_forecast_no_callback()
    {
        $selected = $this->options['wow_hours_forecast_no'] ?? null;

		echo ' <select id="wow_hours_forecast_no" name="wow_option_name[wow_hours_forecast_no]"> ';
		echo $this->wow_generate_hour_options($selected);
		echo '</select>';
	}

    public function wow_display_hour_time_icons_callback()
    {
        $this->bypassRadioButtons('wow_hours_time_icons');
    }

    private function wow_generate_hour_options($selected) {
   		$str = ' <option ';
   		$str .= selected( "nobypass", $selected, false );
   		$str .= ' value="nobypass">'. __( "No bypass", 'owm-weather' ) .'</option>';
   		$str .= ' <option ';
   		$str .= selected( "0", $selected, false );
   		$str .= ' value="0">'. __( "None", 'owm-weather' ) .'</option>';

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
    		$str .= ' value="' . $i . '">'. __( $h, 'owm-weather' ) .'</option>';
        }

        return $str;
    }

	public function wow_display_current_temperature_callback()
    {
        $this->bypassRadioButtons('wow_current_temperature');
    }

	public function wow_display_current_feels_like_callback()
    {
        $this->bypassRadioButtons('wow_current_feels_like');
    }

	public function wow_display_forecast_no_callback()
    {
        $selected = $this->options['wow_forecast_no'] ?? "nobypass";

		echo ' <select id="wow_forecast_no" name="wow_option_name[wow_forecast_no]"> ';
		echo ' <option ';
		if ('nobypass' == $selected) echo 'selected="selected"';
		echo ' value="nobypass">'. __( 'No bypass', 'owm-weather' ) .'</option>';
		echo ' <option ';
		if ('0' == $selected) echo 'selected="selected"';
		echo ' value="0">'. __( 'None', 'owm-weather' ) .'</option>';
		echo ' <option ';
		if ('1' == $selected) echo 'selected="selected"';
		echo ' value="1">'. __( 'Today', 'owm-weather' ) .'</option>';
		echo '<option ';
		if ('2' == $selected) echo 'selected="selected"';
		echo ' value="2">'. __( 'Today + 1 day', 'owm-weather' ) .'</option>';
		echo '<option ';
		if ('3' == $selected) echo 'selected="selected"';
		echo ' value="3">'. __( 'Today + 2 days', 'owm-weather' ) .'</option>';
		echo '<option ';
		if ('4' == $selected) echo 'selected="selected"';
		echo ' value="4">'. __( 'Today + 3 days', 'owm-weather' ) .'</option>';
		echo '<option ';
		if ('5' == $selected) echo 'selected="selected"';
		echo ' value="5">'. __( 'Today + 4 days', 'owm-weather' ) .'</option>';
		echo '<option ';
		if ('6' == $selected) echo 'selected="selected"';
		echo ' value="6">'. __( 'Today + 5 days', 'owm-weather' ) .'</option>';
		echo '<option ';
		if ('7' == $selected) echo 'selected="selected"';
		echo ' value="7">'. __( 'Today + 6 days', 'owm-weather' ) .'</option>';
		echo '<option ';
		if ('8' == $selected) echo 'selected="selected"';
		echo ' value="8">'. __( 'Today + 7 days', 'owm-weather' ) .'</option>';
		echo '</select>';
	}

    public function wow_display_forecast_precipitations_callback()
    {
        $this->bypassRadioButtons('wow_forecast_precipitations');
    }

	public function wow_display_display_length_days_names_callback()
    {
   		$check = $this->options['wow_display_length_days_names'] ?? "nobypass";

        echo '<input id="wow_display_length_days_names_nobypass" name="wow_option_name[wow_display_length_days_names]" type="radio"';
		if ('nobypass' == $check) echo 'checked="yes"';
		echo ' value="nobypass"/>';
		echo '<label for="wow_display_length_days_names_nobypass">'. __( 'No bypass', 'owm-weather' ) .'</label>';

		echo '<br><br>';

        echo '<input id="wow_display_length_days_names_short" name="wow_option_name[wow_display_length_days_names]" type="radio"';
		if ('short' == $check) echo 'checked="yes"';
		echo ' value="short"/>';
		echo '<label for="wow_display_length_days_names_short">'. __( 'Short days names', 'owm-weather' ) .'</label>';

		echo '<br><br>';

		echo '<input id="wow_display_length_days_names_normal" name="wow_option_name[wow_display_length_days_names]" type="radio"';
		if ('normal' == $check) echo 'checked="yes"';
		echo ' value="normal"/>';
		echo '<label for="wow_display_length_days_names_normal">'. __( 'Normal days names', 'owm-weather' ) .'</label>';
    }

    public function wow_display_owm_link_callback()
    {
        $this->bypassRadioButtons('wow_owm_link');
    }

    public function wow_display_last_update_callback()
    {
        $this->bypassRadioButtons('wow_last_update');
    }

	public function wow_layout_disable_spinner_callback()
    {
        $this->bypassRadioButtonsDisable('wow_disable_spinner');
    }

	public function wow_layout_disable_anims_callback()
    {
        $this->bypassRadioButtonsDisable('wow_disable_anims');
    }

    public function wow_layout_background_color_callback()
    {
        $check = $this->options['wow_background_color'] ?? null;

        printf('<input name="wow_option_name[wow_background_color]" type="text" value="%s" class="wpowmweather_admin_color_picker" />', esc_attr($check));
    }

    function media_selector_settings_page_callback() {

    	// Save attachment ID
    	if ( isset( $_POST['submit_image_selector'] ) && isset( $_POST['background_image_attachment_id'] ) ) {
    		update_option( 'wow_background_image', absint( $_POST['background_image_attachment_id'] ) );
    	}

    	echo '	<div class="background_image_preview_wrapper">';
    	echo '		<img id="background_image_preview" src="' . wp_get_attachment_url( ($this->options['wow_background_image'] ?? '' ) ) . '" height="100px"' . (isset($this->options['wow_background_image']) ? '' : ' style="display: none;"') . '>';
    	echo '	</div>';
    	echo '	<input id="select_background_image_button" type="button" class="button" value="' . __( 'Select image', 'owm-weather' ) . '" />';
    	echo '	<input type="hidden" name="wow_option_name[wow_background_image]" id="background_image_attachment_id" value="' . ( $this->options['wow_background_image'] ?? '' ) . '">';
    	echo '	<input id="clear_background_image_button" type="button" class="button" value="Clear" />';
    }

	public function wow_layout_text_color_callback()
    {
        $check = $this->options['wow_text_color'] ?? null;

        printf('<input name="wow_option_name[wow_text_color]" type="text" value="%s" class="wpowmweather_admin_color_picker" />', esc_attr($check));
    }

	public function wow_layout_border_color_callback()
    {
        $check = $this->options['wow_border_color'] ?? null;

		printf('<input name="wow_option_name[wow_border_color]" type="text" value="%s" class="wpowmweather_admin_color_picker" />', esc_attr($check));
    }

	public function wow_layout_border_width_callback()
    {
        $check = $this->options['wow_border_width'] ?? null;

        printf('<input name="wow_option_name[wow_border_width]" type="number" min="0" value="%s" />', esc_attr($check));
    }

	public function wow_layout_border_style_callback()
    {
        $selected = $this->options['wow_border_style'] ?? "nobypass";

		echo '<select id="wow_border_style" name="wow_option_name[wow_border_style]">';
		$this->borderStyleOptions($selected);
		echo '</select>';
	}

    private function borderStyleOptions($selected) {
        echo '<option ' . selected( 'nobypass', $selected, false ) . ' value="nobypass">'. __( 'No bypass', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'solid', $selected, false ) . ' value="solid">'. __( 'Solid', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'dotted', $selected, false ) . ' value="dotted">'. __( 'Dotted', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'dashed', $selected, false ) . ' value="dashed">'. __( 'Dashed', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'double', $selected, false ) . ' value="double">'. __( 'Double', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'groove', $selected, false ) . ' value="groove">'. __( 'Groove', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'inset', $selected, false ) . ' value="inset">'. __( 'Inset', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'outset', $selected, false ) . ' value="outset">'. __( 'Outset', 'owm-weather' ) .'</option>';
        echo '<option ' . selected( 'ridge', $selected, false ) . ' value="ridge">'. __( 'Ridge', 'owm-weather' ) .'</option>';
    }

	public function wow_layout_border_radius_callback()
    {
        $check = $this->options['wow_border_radius'] ?? null;

        printf('<input name="wow_option_name[wow_border_radius]" type="number" min="0" value="%s" />', esc_attr($check));
    }

	public function wow_layout_size_callback()
    {
        $selected = $this->options['wow_size'] ?? "nobypass";

		echo ' <select id="wow_size" name="wow_option_name[wow_size]"> ';
		echo ' <option ';
		if ('nobypass' == $selected) echo 'selected="selected"';
		echo ' value="nobypass">'. __( 'No bypass', 'owm-weather' ) .'</option>';
		echo ' <option ';
		if ('small' == $selected) echo 'selected="selected"';
		echo ' value="small">'. __( 'Small', 'owm-weather' ) .'</option>';
		echo '<option ';
		if ('medium' == $selected) echo 'selected="selected"';
		echo ' value="medium">'. __( 'Medium', 'owm-weather' ) .'</option>';
		echo '<option ';
		if ('large' == $selected) echo 'selected="selected"';
		echo ' value="large">'. __( 'Large', 'owm-weather' ) .'</option>';
		echo '</select>';
	}


	public function wow_layout_custom_css_callback()
    {
        $check = $this->options['wow_custom_css'] ?? '';

        printf('<textarea name="wow_option_name[wow_custom_css]" style="width:100%%;height:300px;">%s</textarea>', esc_attr($check));
    }

    public function wow_layout_table_background_color_callback()
    {
        $check = $this->options['wow_table_background_color'] ?? null;

        printf('<input name="wow_option_name[wow_table_background_color]" type="text" value="%s" class="wpowmweather_admin_color_picker" />', esc_attr($check));
    }

	public function wow_layout_table_text_color_callback()
    {
        $check = $this->options['wow_table_text_color'] ?? null;

        printf('<input name="wow_option_name[wow_table_text_color]" type="text" value="%s" class="wpowmweather_admin_color_picker" />', esc_attr($check));
    }

	public function wow_layout_table_border_color_callback()
    {
        $check = $this->options['wow_table_border_color'] ?? null;

		printf('<input name="wow_option_name[wow_table_border_color]" type="text" value="%s" class="wpowmweather_admin_color_picker" />', esc_attr($check));
    }

	public function wow_layout_table_border_width_callback()
    {
        $check = $this->options['wow_table_border_width'] ?? null;

        printf('<input name="wow_option_name[wow_table_border_width]" type="number" min="0" value="%s" />', esc_attr($check));
    }

	public function wow_layout_table_border_style_callback()
    {
        $selected = $this->options['wow_table_border_style'] ?? "nobypass";

		echo '<select id="wow_border_style" name="wow_option_name[wow_table_border_style]">';
		$this->borderStyleOptions($selected);
		echo '</select>';
	}

	public function wow_layout_table_border_radius_callback()
    {
        $check = $this->options['wow_table_border_radius'] ?? null;

        printf('<input name="wow_option_name[wow_table_border_radius]" type="number" min="0" value="%s" />', esc_attr($check));
    }

	public function wow_advanced_disable_cache_callback()
    {
		$check = $this->options['wow_advanced_disable_cache'] ?? null;

        echo '<input id="wow_advanced_disable_cache" name="wow_option_name[wow_advanced_disable_cache]" type="checkbox"';
		if ('yes' == $check) echo 'checked="yes"';
		echo ' value="yes"/>';
		echo '<label for="wow_advanced_disable_cache">'. __( 'Disable weather cache? (Not recommended!)', 'owm-weather' ) .'</label>';
    }

	public function wow_advanced_cache_time_callback()
    {
        $check = $this->options['wow_advanced_cache_time'] ?? '';

        printf('<input type="number" min="10" name="wow_option_name[wow_advanced_cache_time]" value="%s" style="width:100%%" />', esc_html( $check ));
        echo '<br /><br /><span class="dashicons dashicons-editor-help"></span>'.__('Default value: 30 minutes','owm-weather');
	}

    public function wow_advanced_api_key_callback()
    {
        $check = $this->options['wow_advanced_api_key'] ?? '';

        printf('<input type="text" name="wow_option_name[wow_advanced_api_key]" value="%s" style="width:100%%" />', esc_html( $check ));
        echo '<br /><br /><span class="dashicons dashicons-editor-help"></span>'.__('Strongly recommended: ','owm-weather').'<a href="https://openweathermap.org/appid" target="_blank">'.__('Get your free OWM API key here','owm-weather').'</a>';
    }

    public function wow_advanced_disable_modal_js_callback()
    {
        $check = $this->options['wow_advanced_disable_modal_js'] ?? null;

        echo '<input id="wow_advanced_disable_modal_js" name="wow_option_name[wow_advanced_disable_modal_js]" type="checkbox"';
        if ('yes' == $check) echo 'checked="yes"';
        echo ' value="yes"/>';
        echo '<label for="wow_advanced_disable_modal_js">'. __( 'Disable Bootstrap? (Check this if you already include Bootstrap in your theme)', 'owm-weather' ) .'</label>';
	}

	public function wow_map_display_callback()
    {
        $this->bypassRadioButtons('wow_map');
    }

	public function wow_map_height_callback()
    {
        $check = $this->options['wow_map_height'] ?? '';

        printf('<input name="wow_option_name[wow_map_height]" type="number" min="300" value="%s" />', esc_attr($check));
	}

	public function wow_map_opacity_callback()
	{
        $selected = $this->options['wow_map_opacity'] ?? "nobypass";

		echo ' <select id="wow_map_opacity" name="wow_option_name[wow_map_opacity]"> ';
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

	public function wow_map_zoom_callback()
	{
        $selected = $this->options['wow_map_zoom'] ?? "nobypass";

		echo ' <select id="wow_map_zoom" name="wow_option_name[wow_map_zoom]"> ';
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

	public function wow_map_disable_zoom_wheel_callback()
    {
        $this->bypassRadioButtonsDisable('wow_map_disable_zoom_wheel');
    }

	public function wow_map_layers_stations_callback()
    {
        $this->bypassRadioButtons('wow_map_stations');
    }

	public function wow_map_layers_clouds_callback()
    {
        $this->bypassRadioButtons('wow_map_clouds');
    }

	public function wow_map_layers_precipitation_callback()
    {
        $this->bypassRadioButtons('wow_map_precipitation');
    }

	public function wow_map_layers_snow_callback()
    {
        $this->bypassRadioButtons('wow_map_snow');
    }

	public function wow_map_layers_wind_callback()
    {
        $this->bypassRadioButtons('wow_map_wind');
    }

	public function wow_map_layers_temperature_callback()
    {
        $this->bypassRadioButtons('wow_map_temperature');
    }

    public function wow_map_layers_pressure_callback()
    {
        $this->bypassRadioButtons('wow_map_pressure');
    }

    public function wow_support_info_callback() //bugbug
    {
		echo
			'<h3>'. __("Problem with OWM Weather?", 'owm-weather').'</h3>
			<p><a href="https://www.wpcloudy.com/support/faq/" target="_blank" title="'. __("FAQ", 'owm-weather').'">'. __("Read our FAQ", 'owm-weather').'</a></p>
			<p><a href="https://www.wpcloudy.com/support/guides/" target="_blank" title="'. __("Guides", 'owm-weather').'">'.__("Read our Guides", 'owm-weather').'</a></p>
			<p><a href="https://wordpress.org/plugins/owm-weather/" target="_blank" title="'. __("OWM Weather Forum on WordPress.org", 'owm-weather').'">'. __("OWM Weather Forum on WordPress.org", 'owm-weather').'</a></p>';
    }

    private function bypassRadioButtons($option) {
		$value = $this->options[$option] ?? 'nobypass';

        echo '<input id="' . $option . '_nobypass" name="wow_option_name[' . $option . ']" type="radio"';
        if ('nobypass' == $value) echo 'checked="checked"';
        echo ' value="nobypass"/>';
        echo '<label for="' . $option . '_nobypass">'. __( 'No bypass', 'owm-weather' ) .'</label>';

        echo '<br><br>';
            
        echo '<input id="' . $option . '_on" name="wow_option_name[' . $option . ']" type="radio"';
        if ('yes' == $value) echo 'checked="checked"';
        echo ' value="yes"/>';
        echo '<label for="' . $option . '_on">'. __( 'Show on all weather', 'owm-weather' ) .'</label>';

        echo '<br><br>';

        echo '<input id="' . $option . '_off" name="wow_option_name[' . $option . ']" type="radio"';
        if ('no' == $value) echo 'checked="checked"';
        echo ' value="no"/>';
        echo '<label for="' . $option . '_off">'. __( 'Suppress on all weather', 'owm-weather' ) .'</label>';
    }

    private function bypassRadioButtonsDisable($option) {
		$value = $this->options[$option] ?? 'nobypass';

        echo '<input id="' . $option . '_nobypass" name="wow_option_name[' . $option . ']" type="radio"';
        if ('nobypass' == $value) echo 'checked="checked"';
        echo ' value="nobypass"/>';
        echo '<label for="' . $option . '_nobypass">'. __( 'No bypass', 'owm-weather' ) .'</label>';

        echo '<br><br>';
            
        echo '<input id="' . $option . '_on" name="wow_option_name[' . $option . ']" type="radio"';
        if ('no' == $value) echo 'checked="checked"';
        echo ' value="no"/>';
        echo '<label for="' . $option . '_on">'. __( 'Show on all weather', 'owm-weather' ) .'</label>';

        echo '<br><br>';

        echo '<input id="' . $option . '_off" name="wow_option_name[' . $option . ']" type="radio"';
        if ('yes' == $value) echo 'checked="checked"';
        echo ' value="yes"/>';
        echo '<label for="' . $option . '_off">'. __( 'Suppress on all weather', 'owm-weather' ) .'</label>';
    }

}

if( is_admin() )
    $my_settings_page = new wow_options();

//Help Tab
function wow_help_tab() {//bugbug
    global $wow_help_tab;
    $screen = get_current_screen();

    $screen->add_help_tab( array(
        'id'    => 'wow_help_tab',
        'title' => __('Setup OWM Weather', 'owm-weather'),
        'content'   => '<p>' . __( 'Follow this video to setup OWM Weather:', 'owm-weather' ) . '<br>'.wp_oembed_get('https://www.youtube.com/watch?v=mRF_3VOz_OE', array('width'=>560)).'</p>',
    ));
    $screen->add_help_tab( array(
        'id'    => 'wow_help_tab2',
        'title' => __('Create your first weather', 'owm-weather'),
        'content'   => '<p>' . __( 'Follow this video to create your first weather with OWM Weather:', 'owm-weather' ) . '<br>'.wp_oembed_get('https://www.youtube.com/watch?v=xv4lrgsWkkk', array('width'=>560)).'</p>',
    ));
}
function no_file_selected_action() {
    $message = __( 'Please upload a file to import', 'owm-weather' );
    add_settings_error('no_file_selected', '', $message, 'error');
}
function no_json_file_selected_action() {
        $message = __( 'Please upload a valid .json file', 'owm-weather' );
        add_settings_error('no_json_file_selected', '', $message, 'error');
}

function media_selector_print_scripts($id = null) {
    if (!empty($id)) {
        $image_id = get_post_meta($id,'_wpowmweather_background_image',true);
    } else {
        $options = get_option('wow_option_name');
        $image_id = $options['wow_background_image'] ?? null;
    }

   	$my_saved_attachment_post_id = !empty($image_id) ? $image_id : 0;

	?><script type='text/javascript'>

		jQuery( document ).ready( function( $ ) {

			// Uploading files
			var image_frame;
			var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
			var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this

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
