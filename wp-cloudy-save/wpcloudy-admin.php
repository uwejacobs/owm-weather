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
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }
	
	public function activate() {
        update_option($this->wpc_options, $this->data);
    }

    public function deactivate() {
        delete_option($this->wpc_options);
    }
	
    /**
     * Add options page
     */
    public function add_plugin_page()
    {
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
    public function create_admin_page()
    {
	
        // Set class property
        $this->options = get_option( 'wpc_option_name' );
        ?>      
        <?php $wpc_info_version = get_plugin_data( plugin_dir_path( __FILE__ ).'/wpcloudy.php'); ?>
        
        <div id="wpcloudy-header">
            <div id="wp-admin-ui-banner">
                <span class="wp-admin-ui-txt"><?php _e('The best freemium plugin to boost your SEO with WordPress.','wp-cloudy'); ?></span>
                <a target="_blank" href="https://www.seopress.org/?utm_source=wpcloudy&utm_medium=banner&utm_campaign=WordPress" class="button-primary more-info-link"><?php _e('Download for free','wp-cloudy'); ?></a>
            </div>
			<div id="wpcloudy-clouds">
				<h3>
					<?php _e( 'WP Cloudy', 'wp-cloudy' ); ?>
				</h3>
				<span class="wpc-info-version"><?php print_r($wpc_info_version['Version']); ?></span>
				<div id="wpcloudy-notice">
					<div class="small">
						<span class="dashicons dashicons-wordpress"></span>
						<?php _e( 'You like WP Cloudy? Don\'t forget to rate it 5 stars!', 'wp-cloudy' ); ?>

                        <div class="wporg-ratings rating-stars">
                            <a href="//wordpress.org/support/view/plugin-reviews/wp-cloudy?rate=1#postform" data-rating="1" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#e6b800 !important;"></span></a>
                            <a href="//wordpress.org/support/view/plugin-reviews/wp-cloudy?rate=2#postform" data-rating="2" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#e6b800 !important;"></span></a>
                            <a href="//wordpress.org/support/view/plugin-reviews/wp-cloudy?rate=3#postform" data-rating="3" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#e6b800 !important;"></span></a>
                            <a href="//wordpress.org/support/view/plugin-reviews/wp-cloudy?rate=4#postform" data-rating="4" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#e6b800 !important;"></span></a>
                            <a href="//wordpress.org/support/view/plugin-reviews/wp-cloudy?rate=5#postform" data-rating="5" title="" target="_blank"><span class="dashicons dashicons-star-filled" style="color:#e6b800 !important;"></span></a>
                        </div>
                        <script>
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
                        </script>
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
						<li><a href="#tab_basic" class="nav-tab"><?php _e( 'Basic', 'wp-cloudy' ); ?></a></li>
						<li><a href="#tab_display" class="nav-tab"><?php _e( 'Display', 'wp-cloudy' ); ?></a></li>
						<li><a href="#tab_advanced" class="nav-tab"><?php _e( 'Advanced', 'wp-cloudy' ); ?></a></li>
						<li><a href="#tab_map" class="nav-tab"><?php _e( 'Map', 'wp-cloudy' ); ?></a></li>
                        <li><a href="#tab_export" class="nav-tab"><?php _e( 'Import/Export/Reset', 'wp-cloudy' ); ?></a></li>
						<li><a href="#tab_support" class="nav-tab"><?php _e( 'Support', 'wp-cloudy' ); ?></a></li>
                	</ul>
				</h2>
               
				<div id="wpcloudy-tabs-settings">
					<div class="wpc-tab" id="tab_basic"><?php do_settings_sections( 'wpc-settings-admin-basic' ); ?></div>
					<div class="wpc-tab" id="tab_display"><?php do_settings_sections( 'wpc-settings-admin-display' ); ?></div>
					<div class="wpc-tab" id="tab_advanced"><?php do_settings_sections( 'wpc-settings-admin-advanced' ); ?></div>
					<div class="wpc-tab" id="tab_map"><?php do_settings_sections( 'wpc-settings-admin-map' ); ?></div>
                    <div class="wpc-tab" id="tab_export"></div>
					<div class="wpc-tab" id="tab_support"><?php do_settings_sections( 'wpc-settings-admin-support' ); ?></div>
				</div>
            </div>
            <script>jQuery("#wpc_export_form").detach().appendTo('#tab_export')</script>    
             <?php submit_button( __( 'Save changes', 'wp-cloudy' ), 'primary', 'submit', false ); ?>
        </form>

        <div class="wpcloudy-sidebar">	
            <div id="wpcloudy-premium" class="wpcloudy-module wpcloudy-inactive" style="height: 177px;">
                <h3><?php _e('WP Cloudy Premium','wp-cloudy'); ?></h3>
                <div class="wpcloudy-module-description">
                    <div class="module-image">
                        <div class="dashicons dashicons-location-alt"></div>
                        <p><span class="module-image-badge"><?php _e('$ 19 / year','wp-cloudy'); ?></span></p>
                        <?php if ( is_plugin_active( 'wpcloudy-geolocation-addon/wpcloudy-geolocation-addon.php' ) || is_plugin_active( 'wpcloudy-skin-addon/wpcloudy-skin-addon.php' ) ) {
                            echo '<div class="enabled"><div class="dashicons dashicons-yes"></div>'.__('Enabled','').'</div>';
                        }; ?>
                    </div>

                    <p><?php _e('Join now to get Skins & Geolocation Add-ons, and premium support by email.','wp-cloudy'); ?></p>
                </div>

                <div class="wpcloudy-module-actions">
                    <a target="_blank" href="https://wpcloudy.com/pro?utm_source=WP%20Cloudy%20Admin&utm_medium=global-settings-sidebar&utm_term=join-now&utm_campaign=WP%20Cloudy%20Premium" class="button-primary more-info-link"><?php _e('Join now','wp-cloudy'); ?></a>
                </div>
            </div>
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
	 
	

    public function page_init()
    {        
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
            'wpc_basic_bypass_unit', // ID
           __("Bypass unit?","wp-cloudy"), // Title
            array( $this, 'wpc_basic_bypass_unit_callback' ), // Callback
            'wpc-settings-admin-basic', // Page
            'wpc_setting_section_basic' // Section           
        );
				
        add_settings_field(
            'wpc_basic_unit', // ID
            __("Unit","wp-cloudy"), // Title 
            array( $this, 'wpc_basic_unit_callback' ), // Callback
            'wpc-settings-admin-basic', // Page
            'wpc_setting_section_basic' // Section           
        );
        
		add_settings_field(
            'wpc_basic_bypass_date', // ID
           __("Bypass date format?","wp-cloudy"), // Title
            array( $this, 'wpc_basic_bypass_date_callback' ), // Callback
            'wpc-settings-admin-basic', // Page
            'wpc_setting_section_basic' // Section           
        );
				
        add_settings_field(
            'wpc_basic_date', // ID
            __("Date","wp-cloudy"), // Title 
            array( $this, 'wpc_basic_date_callback' ), // Callback
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
            'wpc_display_current_weather', // ID
            __("Current weather?","wp-cloudy"), // Title
            array( $this, 'wpc_display_current_weather_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );
		
		add_settings_field(
            'wpc_display_weather', // ID
            __("Short condition?","wp-cloudy"), // Title
            array( $this, 'wpc_display_weather_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );

        add_settings_field(
            'wpc_display_bypass_date_temp', // ID
            __("Bypass the todays date format?","wp-cloudy"), // Title 
            array( $this, 'wpc_display_bypass_date_temp_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );
		
		add_settings_field(
            'wpc_display_date_temp', // ID
            __("Todays date or day of the week?","wp-cloudy"), // Title 
            array( $this, 'wpc_display_date_temp_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );
        
        add_settings_field(
            'wpc_display_date_temp_unit', // ID
            __("Temperatures unit (C / F)?","wp-cloudy"), // Title 
            array( $this, 'wpc_display_date_temp_unit_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );
		
		add_settings_field(
            'wpc_display_sunrise_sunset', // ID
            __("Sunrise + sunset?","wp-cloudy"), // Title 
            array( $this, 'wpc_display_sunrise_sunset_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );
		
		add_settings_field(
            'wpc_display_wind', // ID
            __("Wind?","wp-cloudy"), // Title 
            array( $this, 'wpc_display_wind_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );

        add_settings_field(
            'wpc_display_wind_unit', // ID
            __("Wind unit?","wp-cloudy"), // Title 
            array( $this, 'wpc_display_wind_unit_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );
		
		add_settings_field(
            'wpc_display_humidity', // ID
            __("Humidity?","wp-cloudy"), // Title
            array( $this, 'wpc_display_humidity_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );
		
		add_settings_field(
            'wpc_display_pressure', // ID
           __("Pressure?","wp-cloudy"), // Title
            array( $this, 'wpc_display_pressure_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );
		
		add_settings_field(
            'wpc_display_cloudiness', // ID
            __("Cloudiness?","wp-cloudy"), // Title
            array( $this, 'wpc_display_cloudiness_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );
        
        add_settings_field(
            'wpc_display_precipitation', // ID
            __("Precipitation?","wp-cloudy"), // Title
            array( $this, 'wpc_display_precipitation_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );
		
		add_settings_field(
            'wpc_display_hour_forecast', // ID
            __("Hour forecast?","wp-cloudy"), // Title 
            array( $this, 'wpc_display_hour_forecast_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );
        
        add_settings_field(
            'wpc_display_bypass_hour_forecast_nd', // ID
            __("Bypass number of hours forecast settings?","wp-cloudy"), // Title 
            array( $this, 'wpc_display_bypass_hour_forecast_nd_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );
        
        add_settings_field(
            'wpc_display_hour_forecast_nd', // ID
            __("Number of range hours forecast?","wp-cloudy"), // Title 
            array( $this, 'wpc_display_hour_forecast_nd_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );
		
		add_settings_field(
            'wpc_display_bypass_temperature', // ID
            __("Bypass individual temperatures settings?","wp-cloudy"), // Title 
            array( $this, 'wpc_display_bypass_temperature_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );
		
		add_settings_field(
            'wpc_display_temperature_min_max', // ID
			__("Today temperature?","wp-cloudy"), // Title
            array( $this, 'wpc_display_temperature_min_max_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );
		
		add_settings_field(
            'wpc_display_forecast', // ID
            __("5-Days Forecast","wp-cloudy"), // Title 
            array( $this, 'wpc_display_forecast_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );
		
		add_settings_field(
            'wpc_display_bypass_forecast_nd', // ID
            __("Bypass number of days forecast settings?","wp-cloudy"), // Title 
            array( $this, 'wpc_display_bypass_forecast_nd_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );
		
        add_settings_field(
            'wpc_display_forecast_nd', // ID
            __("Number of days forecast","wp-cloudy"), // Title 
            array( $this, 'wpc_display_forecast_nd_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );		

        add_settings_field(
            'wpc_display_forecast_precipitations', // ID
            __("Precipitations forecast?","wp-cloudy"), // Title 
            array( $this, 'wpc_display_forecast_precipitations_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );
        
        add_settings_field(
            'wpc_display_bypass_short_days_names', // ID
            __("Bypass the length of name days?","wp-cloudy"), // Title 
            array( $this, 'wpc_display_bypass_short_days_names_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );
		
		add_settings_field(
            'wpc_display_short_days_names', // ID
			__("Length name days:","wp-cloudy"), // Title
            array( $this, 'wpc_display_short_days_names_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );
        
        add_settings_field(
            'wpc_display_owm_link', // ID
			__("Link to OpenWeatherMap?","wp-cloudy"), // Title
            array( $this, 'wpc_display_owm_link_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );
		
		add_settings_field(
            'wpc_display_last_update', // ID
            __("Update date?","wp-cloudy"), // Title
            array( $this, 'wpc_display_last_update_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );

        add_settings_field(
            'wpc_display_fluid', // ID
			__("Fluid design?","wp-cloudy"), // Title
            array( $this, 'wpc_display_fluid_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );

        add_settings_field(
            'wpc_display_fluid_width', // ID
            __("Default Max width parent container (in pixels)?","wp-cloudy"), // Title
            array( $this, 'wpc_display_fluid_width_callback' ), // Callback
            'wpc-settings-admin-display', // Page
            'wpc_setting_section_display' // Section           
        );
		
		//ADVANCED SECTION=========================================================================
        add_settings_section( 
            'wpc_setting_section_advanced', // ID
            __("Advanced settings","wp-cloudy"), // Title
            array( $this, 'print_section_info_advanced' ), // Callback
            'wpc-settings-admin-advanced' // Page
        );
        
		add_settings_field(
            'wpc_advanced_disable_css3_anims', // ID
            __("CSS 3 Animations","wp-cloudy"), // Title 
            array( $this, 'wpc_advanced_disable_css3_anims_callback' ), // Callback
            'wpc-settings-admin-advanced', // Page
            'wpc_setting_section_advanced' // Section           
        ); 
        
		add_settings_field(
            'wpc_advanced_bg_color', // ID
            __("Background color","wp-cloudy"), // Title 
            array( $this, 'wpc_advanced_bg_color_callback' ), // Callback
            'wpc-settings-admin-advanced', // Page
            'wpc_setting_section_advanced' // Section           
        );     
		
        add_settings_field(
            'wpc_advanced_text_color', // ID
            __("Text color","wp-cloudy"), // Title
            array( $this, 'wpc_advanced_text_color_callback' ), // Callback
            'wpc-settings-admin-advanced', // Page
            'wpc_setting_section_advanced' // Section   
        ); 	
		
		add_settings_field(
            'wpc_advanced_border_color', // ID
            __("Border color","wp-cloudy"), // Title 
            array( $this, 'wpc_advanced_border_color_callback' ), // Callback
            'wpc-settings-admin-advanced', // Page
            'wpc_setting_section_advanced' // Section   
        ); 

		add_settings_field(
            'wpc_advanced_bypass_size', // ID
            __("Bypass size?","wp-cloudy"), // Title
            array( $this, 'wpc_advanced_bypass_size_callback' ), // Callback
            'wpc-settings-admin-advanced', // Page
            'wpc_setting_section_advanced' // Section           
        );
				
        add_settings_field(
            'wpc_advanced_size', // ID
           __("Size","wp-cloudy"), // Title
            array( $this, 'wpc_advanced_size_callback' ), // Callback
            'wpc-settings-admin-advanced', // Page
            'wpc_setting_section_advanced' // Section           
        );
        
        add_settings_field(
            'wpc_advanced_disable_cache', // ID
           __("Disable cache","wp-cloudy"), // Title
            array( $this, 'wpc_advanced_disable_cache_callback' ), // Callback
            'wpc-settings-admin-advanced', // Page
            'wpc_setting_section_advanced' // Section           
        );
        
        add_settings_field(
            'wpc_advanced_cache_time', // ID
           __("Time cache refresh (in minutes)","wp-cloudy"), // Title
            array( $this, 'wpc_advanced_cache_time_callback' ), // Callback
            'wpc-settings-admin-advanced', // Page
            'wpc_setting_section_advanced' // Section           
        );
        
        add_settings_field(
            'wpc_advanced_api_key', // ID
           __("Open Weather Map API key","wp-cloudy"), // Title
            array( $this, 'wpc_advanced_api_key_callback' ), // Callback
            'wpc-settings-admin-advanced', // Page
            'wpc_setting_section_advanced' // Section           
        );

        add_settings_field(
            'wpc_advanced_modal_js', // ID
           __("Disable Bootstrap Modal JS?","wp-cloudy"), // Title
            array( $this, 'wpc_advanced_modal_js_callback' ), // Callback
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
            'wpc_map_display', // ID
            __("Map?","wp-cloudy"), // Title
            array( $this, 'wpc_map_display_callback' ), // Callback
            'wpc-settings-admin-map', // Page
            'wpc_setting_section_map' // Section           
        );	

        add_settings_field(
            'wpc_map_height', // ID
            __("Map height","wp-cloudy"), // Title 
            array( $this, 'wpc_map_height_callback' ), // Callback
            'wpc-settings-admin-map', // Page
            'wpc_setting_section_map' // Section           
        );	
		
		add_settings_field(
            'wpc_map_bypass_opacity', // ID
            __("Bypass layers opacity?","wp-cloudy"), // Title 
            array( $this, 'wpc_map_bypass_opacity_callback' ), // Callback
            'wpc-settings-admin-map', // Page
            'wpc_setting_section_map' // Section           
        );

        add_settings_field(
            'wpc_map_opacity', // ID
            __("Layers opacity","wp-cloudy"), // Title 
            array( $this, 'wpc_map_opacity_callback' ), // Callback
            'wpc-settings-admin-map', // Page
            'wpc_setting_section_map' // Section           
        );	
		
        add_settings_field(
            'wpc_map_bypass_zoom', // ID
            __("Bypass zoom?","wp-cloudy"), // Title
            array( $this, 'wpc_map_bypass_zoom_callback' ), // Callback
            'wpc-settings-admin-map', // Page
            'wpc_setting_section_map' // Section           
        );	

        add_settings_field(
            'wpc_map_zoom', // ID
            __("Zoom","wp-cloudy"), // Title 
            array( $this, 'wpc_map_zoom_callback' ), // Callback
            'wpc-settings-admin-map', // Page
            'wpc_setting_section_map' // Section           
        );	
        
        add_settings_field(
            'wpc_map_mouse_wheel', // ID
            __("Disable zoom wheel?","wp-cloudy"), // Title 
            array( $this, 'wpc_map_mouse_wheel_callback' ), // Callback
            'wpc-settings-admin-map', // Page
            'wpc_setting_section_map' // Section           
        );

        add_settings_field(
            'wpc_map_layers_stations', // ID
            __("Stations?","wp-cloudy"), // Title
            array( $this, 'wpc_map_layers_stations_callback' ), // Callback
            'wpc-settings-admin-map', // Page
            'wpc_setting_section_map' // Section           
        );	

        add_settings_field(
            'wpc_map_layers_clouds', // ID
            __("Clouds?","wp-cloudy"), // Title 
            array( $this, 'wpc_map_layers_clouds_callback' ), // Callback
            'wpc-settings-admin-map', // Page
            'wpc_setting_section_map' // Section           
        );	

        add_settings_field(
            'wpc_map_layers_precipitation', // ID
            __("Precipitations?","wp-cloudy"), // Title
            array( $this, 'wpc_map_layers_precipitation_callback' ), // Callback
            'wpc-settings-admin-map', // Page
            'wpc_setting_section_map' // Section           
        );	

        add_settings_field(
            'wpc_map_layers_snow', // ID
            __("Snow?","wp-cloudy"), // Title
            array( $this, 'wpc_map_layers_snow_callback' ), // Callback
            'wpc-settings-admin-map', // Page
            'wpc_setting_section_map' // Section           
        );	

        add_settings_field(
            'wpc_map_layers_wind', // ID
            __("Wind?","wp-cloudy"), // Title 
            array( $this, 'wpc_map_layers_wind_callback' ), // Callback
            'wpc-settings-admin-map', // Page
            'wpc_setting_section_map' // Section           
        );	

        add_settings_field(
            'wpc_map_layers_temperature', // ID
            __("Temperatures?","wp-cloudy"), // Title
            array( $this, 'wpc_map_layers_temperature_callback' ), // Callback
            'wpc-settings-admin-map', // Page
            'wpc_setting_section_map' // Section           
        );	

        add_settings_field(
            'wpc_map_layers_pressure', // ID
            __("Pressure?","wp-cloudy"), // Title
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
	
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {	
		if( !empty( $input['wpc_advanced_bg_color'] ) )
		$input['wpc_advanced_bg_color'] = sanitize_text_field( $input['wpc_advanced_bg_color'] );
		
		if( !empty( $input['wpc_advanced_text_color'] ) )
		$input['wpc_advanced_text_color'] = sanitize_text_field( $input['wpc_advanced_text_color'] );
		
		if( !empty( $input['wpc_advanced_border_color'] ) )
		$input['wpc_advanced_border_color'] = sanitize_text_field( $input['wpc_advanced_border_color'] );
		
		if( !empty( $input['wpc_advanced_cache_time'] ) )
		$input['wpc_advanced_cache_time'] = sanitize_text_field( $input['wpc_advanced_cache_time'] );
		
		if( !empty( $input['wpc_advanced_api'] ) )
		$input['wpc_advanced_api'] = sanitize_text_field( $input['wpc_advanced_api'] );
		
		if( !empty( $input['wpc_map_height'] ) )
        $input['wpc_map_height'] = sanitize_text_field( $input['wpc_map_height'] );

        if( !empty( $input['wpc_display_fluid_width'] ) )
        $input['wpc_display_fluid_width'] = sanitize_text_field( $input['wpc_display_fluid_width'] );
		
        return $input;
    }

    /** 
     * Print the Section text
     */
	 
	public function print_section_info_basic()
    {
        print __('Basic settings to bypass:', 'wp-cloudy');
    }
	
	public function print_section_info_display()
    {
        print __('Display settings to bypass:', 'wp-cloudy');
    }
	
    public function print_section_info_advanced()
    {
        print __('Advanced settings to bypass:', 'wp-cloudy');
    }
	
	public function print_section_info_map()
    {
        print __('Map settings to bypass:', 'wp-cloudy');
    }
    
    public function print_section_info_support()
    {
        print __('&nbsp;', 'wp-cloudy');
    }

    /** 
     * Get the settings option array and print one of its values
     */
	
	public function wpc_basic_bypass_unit_callback()
    {
		$options = get_option( 'wpc_option_name' );  
		  
		$check = isset($options['wpc_basic_bypass_unit']);
		
        echo '<input id="wpc_basic_bypass_unit" name="wpc_option_name[wpc_basic_bypass_unit]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_basic_bypass_unit">'. __( 'Enable bypass unit on all weather?', 'wp-cloudy' ) .'</label>';
		
		if (isset($this->options['wpc_basic_bypass_unit'])) {
			esc_attr( $this->options['wpc_basic_bypass_unit']);
		}
		
    } 
	 
	public function wpc_basic_unit_callback()
    {
		$options = get_option( 'wpc_option_name' );    
        $selected = isset($options['wpc_basic_unit']) ? $options['wpc_basic_unit'] : NULL;
		
		echo ' <select id="wpc_basic_unit" name="wpc_option_name[wpc_basic_unit]"> ';
		echo ' <option '; 
		if ('imperial' == $selected) echo 'selected="selected"'; 
		echo ' value="imperial">'. __( 'Imperial', 'wp-cloudy' ) .'</option>';
		echo '<option '; 
		if ('metric' == $selected) echo 'selected="selected"'; 
		echo ' value="metric">'. __( 'Metric', 'wp-cloudy' ) .'</option>';
		echo '</select>';

		if (isset($this->options['wpc_basic_unit'])) {
			esc_attr( $this->options['wpc_basic_unit']);
		}
	}
	
	public function wpc_basic_bypass_date_callback()
    {
		$options = get_option( 'wpc_option_name' );  
		  
		$check = isset($options['wpc_basic_bypass_date']);
		
        echo '<input id="wpc_basic_bypass_date" name="wpc_option_name[wpc_basic_bypass_date]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_basic_bypass_date">'. __( 'Enable bypass date format on all weather?', 'wp-cloudy' ) .'</label>';
		
		if (isset($this->options['wpc_basic_bypass_date'])) {
			esc_attr( $this->options['wpc_basic_bypass_date']);
		}
		
    } 
	 
	public function wpc_basic_date_callback()
    {
		$options = get_option( 'wpc_option_name' );    
        $selected = isset($options['wpc_basic_date']) ? $options['wpc_basic_date'] : NULL;
		
		echo '<select id="wpc_basic_date" name="wpc_option_name[wpc_basic_date]"> ';
		echo '<option '; 
		if ('12' == $selected) echo 'selected="selected"'; 
		echo ' value="12">'. __( '12 h', 'wp-cloudy' ) .'</option>';
		echo '<option '; 
		if ('24' == $selected) echo 'selected="selected"'; 
		echo ' value="24">'. __( '24 h', 'wp-cloudy' ) .'</option>';
		echo '</select>';

		if (isset($this->options['wpc_basic_date'])) {
			esc_attr( $this->options['wpc_basic_date']);
		}
	}

	public function wpc_display_current_weather_callback()
    {
		$options = get_option( 'wpc_option_name' );    
	
		$check = isset($options['wpc_display_current_weather']);
		
        echo '<input id="wpc_display_current_weather" name="wpc_option_name[wpc_display_current_weather]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_display_current_weather">'. __( 'Display current weather on all weather?', 'wp-cloudy' ) .'</label>';
		if (isset($this->options['wpc_display_current_weather'])) {
			esc_attr( $this->options['wpc_display_current_weather']);
		}
    }
	
	public function wpc_display_weather_callback()
    {
		$options = get_option( 'wpc_option_name' );    

		$check = isset($options['wpc_display_weather']);
		
        echo '<input id="wpc_display_weather" name="wpc_option_name[wpc_display_weather]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_display_weather">'. __( 'Display short condition on all weather?', 'wp-cloudy' ) .'</label>';
		
		if (isset($this->options['wpc_display_weather'])) { 
			esc_attr( $this->options['wpc_display_weather']);
		}
    }
    public function wpc_display_bypass_date_temp_callback()
    {
        $options = get_option( 'wpc_option_name' );
          
        $check = isset($options['wpc_display_bypass_date_temp']);
        
        echo '<input id="wpc_display_bypass_date_temp" name="wpc_option_name[wpc_display_bypass_date_temp]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpc_display_bypass_date_temp">'. __( 'Bypass todays date format?', 'wp-cloudy' ) .'</label>';
         
        if (isset($this->options['wpc_display_bypass_date_temp'])) {  
            esc_attr( $this->options['wpc_display_bypass_date_temp']);
        }
    }
    public function wpc_display_date_temp_callback()
    {
        $options = get_option( 'wpc_option_name' );    
         
        $check = isset($options['wpc_display_date_temp']);

        echo '<input id="wpc_display_date_temp_none" name="wpc_option_name[wpc_display_date_temp]" type="radio"';
        if ('none' == $check) echo 'checked="none"'; 
        echo ' value="none"/>';
        
        echo '<label for="wpc_display_date_temp_none">'. __( 'None (default)?', 'wp-cloudy' ) .'</label>';
        
        echo '<br><br>';
        
        echo '<input id="wpc_display_date_temp_yes" name="wpc_option_name[wpc_display_date_temp]" type="radio"';
        if ('yes' == $check) echo 'checked="yes"'; 
        echo ' value="yes"/>';
        
        echo '<label for="wpc_display_date_temp_yes">'. __( 'Day of the week (eg: Sunday)?', 'wp-cloudy' ) .'</label>';
        
        echo '<br><br>';
        
        echo '<input id="wpc_display_date_temp_no" name="wpc_option_name[wpc_display_date_temp]" type="radio"';
        if ('no' == $check) echo 'checked="yes"'; 
        echo ' value="no"/>';
        
        echo '<label for="wpc_display_date_temp_no">'. __( 'Todays date?', 'wp-cloudy' ) .'</label>';
        
        if (isset($this->options['wpc_display_date_temp'])) {
            esc_attr( $this->options['wpc_display_date_temp']);
        }
    }

    public function wpc_display_date_temp_unit_callback()
    {
		$options = get_option( 'wpc_option_name' ); 
   
		$check = isset($options['wpc_display_date_temp_unit']);
		
        echo '<input id="wpc_display_date_temp_unit" name="wpc_option_name[wpc_display_date_temp_unit]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_display_date_temp_unit">'. __( 'Display temperatures unit (C / F)?', 'wp-cloudy' ) .'</label>';
		
		if (isset($this->options['wpc_display_date_temp_unit'])) { 
			esc_attr( $this->options['wpc_display_date_temp_unit']);
		}
    }
	
	public function wpc_display_sunrise_sunset_callback()
    {
		$options = get_option( 'wpc_option_name' );
		    
		$check = isset($options['wpc_display_sunrise_sunset']);
		
        echo '<input id="wpc_display_sunrise_sunset" name="wpc_option_name[wpc_display_sunrise_sunset]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_display_sunrise_sunset">'. __( 'Display sunrise - sunset on all weather?', 'wp-cloudy' ) .'</label>';
		
		if (isset($this->options['wpc_display_sunrise_sunset'])) { 
			esc_attr( $this->options['wpc_display_sunrise_sunset']);
		}
    }
	
	public function wpc_display_wind_callback()
    {
		$options = get_option( 'wpc_option_name' );
  
		$check = isset($options['wpc_display_wind']);
		
        echo '<input id="wpc_display_wind" name="wpc_option_name[wpc_display_wind]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_display_wind">'. __( 'Display wind on all weather?', 'wp-cloudy' ) .'</label>';
		
		if (isset($this->options['wpc_display_wind'])) {   
			esc_attr( $this->options['wpc_display_wind']);
		}
    }

    public function wpc_display_wind_unit_callback()
    {
        $options = get_option( 'wpc_option_name' ); 
         
        $selected = isset($options['wpc_display_wind_unit']);
        
        echo ' <select id="wpc_display_wind_unit" name="wpc_option_name[wpc_display_wind_unit]"> ';
        echo ' <option '; 
        if ('0' == $selected) echo 'selected="selected"'; 
        echo ' value="0">'. __( 'No bypass', 'wp-cloudy' ) .'</option>';
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
        
        if (isset($this->options['wpc_display_wind_unit'])) { 
            esc_attr( $this->options['wpc_display_wind_unit']);
        }
    }
	
	public function wpc_display_humidity_callback()
    {
		$options = get_option( 'wpc_option_name' ); 
		    
		$check = isset($options['wpc_display_humidity']);
		
        echo '<input id="wpc_display_humidity" name="wpc_option_name[wpc_display_humidity]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_display_humidity">'. __( 'Display humidity on all weather?', 'wp-cloudy' ) .'</label>';
		
		if (isset($this->options['wpc_display_humidity'])) {  
			esc_attr( $this->options['wpc_display_humidity']);
		}
    }
	
	public function wpc_display_pressure_callback()
    {
		$options = get_option( 'wpc_option_name' ); 
		  
		$check = isset($options['wpc_display_pressure']);
		
        echo '<input id="wpc_display_pressure" name="wpc_option_name[wpc_display_pressure]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_display_pressure">'. __( 'Display pressure on all weather?', 'wp-cloudy' ) .'</label>';
		
		if (isset($this->options['wpc_display_pressure'])) {
			esc_attr( $this->options['wpc_display_pressure']);
		}
    }
	
	public function wpc_display_cloudiness_callback()
    {
		$options = get_option( 'wpc_option_name' );  
		  
		$check = isset($options['wpc_display_cloudiness']);
		
        echo '<input id="wpc_display_cloudiness" name="wpc_option_name[wpc_display_cloudiness]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_display_cloudiness">'. __( 'Display cloudiness on all weather?', 'wp-cloudy' ) .'</label>';
		
		if (isset($this->options['wpc_display_cloudiness'])) { 
			esc_attr( $this->options['wpc_display_cloudiness']);
		}
    }
    
    public function wpc_display_precipitation_callback()
    {
		$options = get_option( 'wpc_option_name' );  
		  
		$check = isset($options['wpc_display_precipitation']);
		
        echo '<input id="wpc_display_precipitation" name="wpc_option_name[wpc_display_precipitation]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_display_precipitation">'. __( 'Display precipitation on all weather?', 'wp-cloudy' ) .'</label>';
		
		if (isset($this->options['wpc_display_precipitation'])) { 
			esc_attr( $this->options['wpc_display_precipitation']);
		}
    }
	
	public function wpc_display_hour_forecast_callback()
    {
		$options = get_option( 'wpc_option_name' );   
		
		$check = isset($options['wpc_display_hour_forecast']);
	
        echo '<input id="wpc_display_hour_forecast" name="wpc_option_name[wpc_display_hour_forecast]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_display_hour_forecast">'. __( 'Display hour forecast on all weather?', 'wp-cloudy' ) .'</label>';
		
		if (isset($this->options['wpc_display_hour_forecast'])) { 
			esc_attr( $this->options['wpc_display_hour_forecast']);
		}
    }
    
    public function wpc_display_bypass_hour_forecast_nd_callback()
    {
		$options = get_option( 'wpc_option_name' );   
		 
		$check = isset($options['wpc_display_bypass_hour_forecast_nd']);
		
        echo '<input id="wpc_display_bypass_hour_forecast_nd" name="wpc_option_name[wpc_display_bypass_hour_forecast_nd]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_display_bypass_hour_forecast_nd">'. __( 'Enable bypass number of hours forecast on all weather?', 'wp-cloudy' ) .'</label>';

		if (isset($this->options['wpc_display_bypass_hour_forecast_nd'])) { 
			esc_attr( $this->options['wpc_display_bypass_hour_forecast_nd']);
		}
    }
    
    public function wpc_display_hour_forecast_nd_callback()
    {
		$options = get_option( 'wpc_option_name' ); 
		 
		$selected = isset($options['wpc_display_hour_forecast_nd']);
		
		echo ' <select id="wpc_display_hour_forecast_nd" name="wpc_option_name[wpc_display_hour_forecast_nd]"> ';
		echo ' <option '; 
		if ('1' == $selected) echo 'selected="selected"'; 
		echo ' value="1">'. __( '1', 'wp-cloudy' ) .'</option>';
		echo '<option '; 
		if ('2' == $selected) echo 'selected="selected"'; 
		echo ' value="2">'. __( '2', 'wp-cloudy' ) .'</option>';
		echo '<option '; 
		if ('3' == $selected) echo 'selected="selected"'; 
		echo ' value="3">'. __( '3', 'wp-cloudy' ) .'</option>';
		echo '<option '; 
		if ('4' == $selected) echo 'selected="selected"'; 
		echo ' value="4">'. __( '4', 'wp-cloudy' ) .'</option>';
		echo '<option '; 
		if ('5' == $selected) echo 'selected="selected"'; 
		echo ' value="5">'. __( '5', 'wp-cloudy' ) .'</option>';
		echo '<option '; 
		if ('6' == $selected) echo 'selected="selected"'; 
		echo ' value="6">'. __( '6', 'wp-cloudy' ) .'</option>';
		echo '</select>';
		
		if (isset($this->options['wpc_display_hour_forecast_nd'])) { 
			esc_attr( $this->options['wpc_display_hour_forecast_nd']);
		}
	}

	public function wpc_display_bypass_temperature_callback()
    {
		$options = get_option( 'wpc_option_name' );
		  
		$check = isset($options['wpc_display_bypass_temperature']);
		
        echo '<input id="wpc_display_bypass_temperature" name="wpc_option_name[wpc_display_bypass_temperature]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_display_bypass_temperature">'. __( 'Bypass individual temperatures settings?', 'wp-cloudy' ) .'</label>';
		 
		if (isset($this->options['wpc_display_bypass_temperature'])) {  
			esc_attr( $this->options['wpc_display_bypass_temperature']);
		}
    }
	
	public function wpc_display_temperature_min_max_callback()
    {
        $options = get_option( 'wpc_option_name' );   
        
        $check = isset($options['wpc_display_temperature_min_max']);
        
        echo '<input id="wpc_display_temperature_min_max" name="wpc_option_name[wpc_display_temperature_min_max]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpc_display_temperature_min_max">'. __( 'Display today temperature on all weather?', 'wp-cloudy' ) .'</label>';
        
        if (isset($this->options['wpc_display_temperature_min_max'])) {
            esc_attr( $this->options['wpc_display_temperature_min_max']);
        }
    }
	
	public function wpc_display_forecast_callback()
    {
		$options = get_option( 'wpc_option_name' );   
		
		$check = isset($options['wpc_display_forecast']);
		
        echo '<input id="wpc_display_forecast" name="wpc_option_name[wpc_display_forecast]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_display_forecast">'. __( 'Display 5-days Forecast on all weather?', 'wp-cloudy' ) .'</label>';
		
		if (isset($this->options['wpc_display_forecast'])) {
			esc_attr( $this->options['wpc_display_forecast']);
		}
    }

	public function wpc_display_bypass_forecast_nd_callback()
    {
		$options = get_option( 'wpc_option_name' );   
		 
		$check = isset($options['wpc_display_bypass_forecast_nd']);
		
        echo '<input id="wpc_display_bypass_forecast_nd" name="wpc_option_name[wpc_display_bypass_forecast_nd]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_display_bypass_forecast_nd">'. __( 'Enable bypass number of days forecast on all weather?', 'wp-cloudy' ) .'</label>';

		if (isset($this->options['wpc_display_bypass_forecast_nd'])) { 
			esc_attr( $this->options['wpc_display_bypass_forecast_nd']);
		}
    } 
	 
	public function wpc_display_forecast_nd_callback()
    {
		$options = get_option( 'wpc_option_name' ); 
		 
        $selected = isset($options['wpc_display_forecast_nd']) ? $options['wpc_display_forecast_nd'] : NULL;
		
		echo ' <select id="wpc_display_forecast_nd" name="wpc_option_name[wpc_display_forecast_nd]"> ';
		echo ' <option '; 
		if ('1' == $selected) echo 'selected="selected"'; 
		echo ' value="1">'. __( '1 day', 'wp-cloudy' ) .'</option>';
		echo '<option '; 
		if ('2' == $selected) echo 'selected="selected"'; 
		echo ' value="2">'. __( '2 days', 'wp-cloudy' ) .'</option>';
		echo '<option '; 
		if ('3' == $selected) echo 'selected="selected"'; 
		echo ' value="3">'. __( '3 days', 'wp-cloudy' ) .'</option>';
		echo '<option '; 
		if ('4' == $selected) echo 'selected="selected"'; 
		echo ' value="4">'. __( '4 days', 'wp-cloudy' ) .'</option>';
		echo '<option '; 
		if ('5' == $selected) echo 'selected="selected"'; 
		echo ' value="5">'. __( '5 days', 'wp-cloudy' ) .'</option>';
		echo '</select>';
		
		if (isset($this->options['wpc_display_forecast_nd'])) { 
			esc_attr( $this->options['wpc_display_forecast_nd']);
		}
	}
	
    public function wpc_display_forecast_precipitations_callback()
    {
        $options = get_option( 'wpc_option_name' );
          
        $check = isset($options['wpc_display_forecast_precipitations']);
        
        echo '<input id="wpc_display_forecast_precipitations" name="wpc_option_name[wpc_display_forecast_precipitations]" type="checkbox"';
        if ('1' == $check) echo 'checked="yes"'; 
        echo ' value="1"/>';
        echo '<label for="wpc_display_forecast_precipitations">'. __( 'Display Forecast Precipitations on all Weather?', 'wp-cloudy' ) .'</label>';
         
        if (isset($this->options['wpc_display_forecast_precipitations'])) {  
            esc_attr( $this->options['wpc_display_forecast_precipitations']);
        }
    }	

    public function wpc_display_bypass_short_days_names_callback()
    {
		$options = get_option( 'wpc_option_name' );
		  
		$check = isset($options['wpc_display_bypass_short_days_names']);
		
        echo '<input id="wpc_display_bypass_short_days_names" name="wpc_option_name[wpc_display_bypass_short_days_names]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_display_bypass_short_days_names">'. __( 'Bypass the length of name days?', 'wp-cloudy' ) .'</label>';
		 
		if (isset($this->options['wpc_display_bypass_short_days_names'])) {  
			esc_attr( $this->options['wpc_display_bypass_short_days_names']);
		}
    }
	
	public function wpc_display_short_days_names_callback()
    {
		$options = get_option( 'wpc_option_name' );    
		 
		$check = isset($options['wpc_display_short_days_names']);
		
        echo '<input id="wpc_display_short_days_names_yes" name="wpc_option_name[wpc_display_short_days_names]" type="radio"';
		if ('yes' == $check) echo 'checked="yes"'; 
		echo ' value="yes"/>';
		
		echo '<label for="wpc_display_short_days_names_yes">'. __( 'Short days names', 'wp-cloudy' ) .'</label>';
		
		echo '<br><br>';
		
		echo '<input id="wpc_display_short_days_names_no" name="wpc_option_name[wpc_display_short_days_names]" type="radio"';
		if ('no' == $check) echo 'checked="yes"'; 
		echo ' value="no"/>';
		
		echo '<label for="wpc_display_short_days_names_no">'. __( 'Normal days names', 'wp-cloudy' ) .'</label>';
		
		if (isset($this->options['wpc_display_short_days_names'])) {
			esc_attr( $this->options['wpc_display_short_days_names']);
		}
    }
    
    public function wpc_display_owm_link_callback()
    {
		$options = get_option( 'wpc_option_name' );   
		
		$check = isset($options['wpc_display_owm_link']);
		
        echo '<input id="wpc_display_owm_link" name="wpc_option_name[wpc_display_owm_link]" type="checkbox"';
		if ('yes' == $check) echo 'checked="yes"'; 
		echo ' value="yes"/>';
		echo '<label for="wpc_display_owm_link">'. __( 'Display link to full weather on OpenWeatherMap?', 'wp-cloudy' ) .'</label>';
		 
		if (isset($this->options['wpc_display_owm_link'])) {  
			esc_attr( $this->options['wpc_display_owm_link']);
		}
		
    } 
    
    public function wpc_display_last_update_callback()
    {
        $options = get_option( 'wpc_option_name' );   
        
        $check = isset($options['wpc_display_last_update']);
        
        echo '<input id="wpc_display_last_update" name="wpc_option_name[wpc_display_last_update]" type="checkbox"';
        if ('yes' == $check) echo 'checked="yes"'; 
        echo ' value="yes"/>';
        echo '<label for="wpc_display_last_update">'. __( 'Display update date on all weather?', 'wp-cloudy' ) .'</label>';
         
        if (isset($this->options['wpc_display_last_update'])) {  
            esc_attr( $this->options['wpc_display_last_update']);
        }
        
    }

    public function wpc_display_fluid_callback()
    {
        $options = get_option( 'wpc_option_name' );   
        
        $check = isset($options['wpc_display_fluid']);
        
        echo '<input id="wpc_display_fluid" name="wpc_option_name[wpc_display_fluid]" type="checkbox"';
        if ('yes' == $check) echo 'checked="yes"'; 
        echo ' value="yes"/>';
        echo '<label for="wpc_display_fluid">'. __( 'Enable fluid design on all weather?', 'wp-cloudy' ) .'</label>';
         
        if (isset($this->options['wpc_display_fluid'])) {  
            esc_attr( $this->options['wpc_display_fluid']);
        }
        
    }     

    public function wpc_display_fluid_width_callback()
    {
        $check = isset($this->options['wpc_display_fluid_width']) ? $this->options['wpc_display_fluid_width'] : NULL;
        
        printf(
        '<input name="wpc_option_name[wpc_display_fluid_width]" value="%s" type="number" min="0" max="1000" />',
        esc_attr($check)
        );
    } 
	
	public function wpc_advanced_disable_css3_anims_callback()
    {
		$options = get_option( 'wpc_option_name' );   
		
		$check = isset($options['wpc_advanced_disable_css3_anims']);
		
        echo '<input id="wpc_advanced_disable_css3_anims" name="wpc_option_name[wpc_advanced_disable_css3_anims]" type="checkbox"';
		if ('yes' == $check) echo 'checked="yes"'; 
		echo ' value="yes"/>';
		echo '<label for="wpc_advanced_disable_css3_anims">'. __( 'Disable CSS3 animations, transformations and transitions?', 'wp-cloudy' ) .'</label>';
		 
		if (isset($this->options['wpc_advanced_disable_css3_anims'])) {  
			esc_attr( $this->options['wpc_advanced_disable_css3_anims']);
		}
		
    } 
    
    public function wpc_advanced_bg_color_callback()
    {
        $check = isset($this->options['wpc_advanced_bg_color']) ? $this->options['wpc_advanced_bg_color'] : NULL;

        printf(
		'<input name="wpc_option_name[wpc_advanced_bg_color]" type="text" value="%s" class="wpcloudy_admin_color_picker" />',
		esc_attr($check)
		
        );
		
    }
	
	public function wpc_advanced_text_color_callback()
    {

        $check = isset($this->options['wpc_advanced_text_color']) ? $this->options['wpc_advanced_text_color'] : NULL;

        printf(
        '<input name="wpc_option_name[wpc_advanced_text_color]" type="text" value="%s" class="wpcloudy_admin_color_picker" />',
        esc_attr($check)

        );
    }
	
	public function wpc_advanced_border_color_callback()
    {
        $check = isset($this->options['wpc_advanced_border_color']) ? $this->options['wpc_advanced_border_color'] : NULL;

		printf(
		'<input name="wpc_option_name[wpc_advanced_border_color]" type="text" value="%s" class="wpcloudy_admin_color_picker" />',
		esc_attr($check)
		
		);
    }
	
	public function wpc_advanced_bypass_size_callback()
    {

		$options = get_option( 'wpc_option_name' );   
		$check = isset($this->options['wpc_advanced_bypass_size']) ? $this->options['wpc_advanced_bypass_size'] : NULL;
		
        echo '<input id="wpc_advanced_bypass_size" name="wpc_option_name[wpc_advanced_bypass_size]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_advanced_bypass_size">'. __( 'Enable bypass size on all weather?', 'wp-cloudy' ) .'</label>';
		
		if (isset($this->options['wpc_advanced_bypass_size'])) {
			esc_attr( $this->options['wpc_advanced_bypass_size']);
		}
    } 
	 
	public function wpc_advanced_size_callback()
    {
		$options = get_option( 'wpc_option_name' );
		
        $selected = isset($options['wpc_advanced_size']) ? $options['wpc_advanced_size'] : NULL;
		
		echo ' <select id="wpc_advanced_size" name="wpc_option_name[wpc_advanced_size]"> ';
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
		
		if (isset($this->options['wpc_advanced_size'])) {
			esc_attr( $this->options['wpc_advanced_size']);
		}
	}
	
	public function wpc_advanced_disable_cache_callback()
    {
		$options = get_option( 'wpc_option_name' );  
		
		$check = isset($options['wpc_advanced_disable_cache']);
		
        echo '<input id="wpc_advanced_disable_cache" name="wpc_option_name[wpc_advanced_disable_cache]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_advanced_disable_cache">'. __( 'Disable weather cache? (not recommended)', 'wp-cloudy' ) .'</label>';
		
		if (isset($this->options['wpc_advanced_disable_cache'])) {
			esc_attr( $this->options['wpc_advanced_disable_cache']);
		}
    }
    
	public function wpc_advanced_cache_time_callback()
    {
        $check = isset($this->options['wpc_advanced_cache_time']) ? $this->options['wpc_advanced_cache_time'] : NULL;

        printf(
        '<input type="text" name="wpc_option_name[wpc_advanced_cache_time]" type="text" value="%s" style="width:100%%" />',
        esc_html( $check )
        
        );

        echo '<br /><br /><span class="dashicons dashicons-editor-help"></span>'.__('Default value: 30 minutes','wp-cloudy');
	}
	
    public function wpc_advanced_api_key_callback()
    {
        $check = isset($this->options['wpc_advanced_api_key']) ? $this->options['wpc_advanced_api_key'] : NULL;

        printf(
        '<input type="text" name="wpc_option_name[wpc_advanced_api_key]" type="text" value="%s" style="width:100%%" />',
        esc_html( $check )
        
        );

        echo '<br /><br /><span class="dashicons dashicons-editor-help"></span>'.__('Strongly recommended: ','wp-cloudy').'<a href="https://openweathermap.org/appid" target="_blank">'.__('Strongly recommended: get your free OWM API key here','wp-cloudy').'</a>';
    }	

    public function wpc_advanced_modal_js_callback()
    {

        $options = get_option( 'wpc_option_name' );  
        
        $check = isset($options['wpc_advanced_modal_js']);
        
        echo '<input id="wpc_advanced_modal_js" name="wpc_option_name[wpc_advanced_modal_js]" type="checkbox"';
        if ('yes' == $check) echo 'checked="yes"'; 
        echo ' value="yes"/>';
        echo '<label for="wpc_advanced_modal_js">'. __( 'Disable Bootstrap Modal JS? (disable this if you already include Bootstrap JS in your theme)', 'wp-cloudy' ) .'</label>';
        
        if (isset($this->options['wpc_advanced_modal_js'])) {
            esc_attr( $this->options['wpc_advanced_modal_js']);
        }
	}
	
	public function wpc_map_display_callback()
    {
		$options = get_option( 'wpc_option_name' );  
		
		$check = isset($options['wpc_map_display']);
		
        echo '<input id="wpc_map_display" name="wpc_option_name[wpc_map_display]" type="checkbox"';
		if ('yes' == $check) echo 'checked="yes"'; 
		echo ' value="yes"/>';
		echo '<label for="wpc_map_display">'. __( 'Enable map on all weather?', 'wp-cloudy' ) .'</label>';
		
		if (isset($this->options['wpc_map_display'])) {
			esc_attr( $this->options['wpc_map_display']);
		}
    }
	
	public function wpc_map_height_callback()
    {
        $check = isset($this->options['wpc_map_height']) ? $this->options['wpc_map_height'] : NULL;
		
        printf(
		'<input name="wpc_option_name[wpc_map_height]" type="text" value="%s" />',
		esc_attr($check)
		
		);
	}

	public function wpc_map_bypass_opacity_callback()
    {
		$options = get_option( 'wpc_option_name' );    
		
		$check = isset($options['wpc_map_bypass_opacity']);
		
        echo '<input id="wpc_map_bypass_opacity" name="wpc_option_name[wpc_map_bypass_opacity]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_map_bypass_opacity">'. __( 'Enable bypass map opacity on all weather?', 'wp-cloudy' ) .'</label>';
		
		if (isset($this->options['wpc_map_bypass_opacity'])) {
			esc_attr( $this->options['wpc_map_bypass_opacity']);
		}
    }
	
	public function wpc_map_opacity_callback()
	{
		$options = get_option( 'wpc_option_name' ); 
		  
        $selected = isset($options['wpc_map_opacity']) ? $options['wpc_map_opacity'] : NULL;
		
		echo ' <select id="wpc_map_opacity" name="wpc_option_name[wpc_map_opacity]"> ';
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
	
		if (isset($this->options['wpc_map_opacity'])) {
			esc_attr( $this->options['wpc_map_opacity']);
		}
	} 
	
	public function wpc_map_bypass_zoom_callback()
    {
		$options = get_option( 'wpc_option_name' ); 
		  
		$check = isset($options['wpc_map_bypass_zoom']);
		
        echo '<input id="wpc_map_bypass_zoom" name="wpc_option_name[wpc_map_bypass_zoom]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_map_bypass_zoom">'. __( 'Enable bypass map zoom on all weather?', 'wp-cloudy' ) .'</label>';
		
		if (isset($this->options['wpc_map_bypass_zoom'])) {
			esc_attr( $this->options['wpc_map_bypass_zoom']);
		}
    }
	
	public function wpc_map_zoom_callback()
	{
		$options = get_option( 'wpc_option_name' );    
        $selected = isset($options['wpc_map_zoom']) ? $options['wpc_map_zoom'] : NULL;
		
		echo ' <select id="wpc_map_zoom" name="wpc_option_name[wpc_map_zoom]"> ';
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
		
		if (isset($this->options['wpc_map_zoom'])) {
			esc_attr( $this->options['wpc_map_zoom']);
		}
	} 
	
	public function wpc_map_mouse_wheel_callback()
    {
		$options = get_option( 'wpc_option_name' ); 
		  
		$check = isset($options['wpc_map_mouse_wheel']);
		
        echo '<input id="wpc_map_mouse_wheel" name="wpc_option_name[wpc_map_mouse_wheel]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_map_mouse_wheel">'. __( 'Disable zoom wheel on all weather?', 'wp-cloudy' ) .'</label>';
		
		if (isset($this->options['wpc_map_mouse_wheel'])) {
			esc_attr( $this->options['wpc_map_mouse_wheel']);
		}
    }
	
	public function wpc_map_layers_stations_callback()
    {
		$options = get_option( 'wpc_option_name' );    
		
		$check = isset($options['wpc_map_layers_stations']);
		
        echo '<input id="wpc_map_layers_stations" name="wpc_option_name[wpc_map_layers_stations]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_map_layers_stations">'. __( 'Display stations on all weather maps?', 'wp-cloudy' ) .'</label>';
		
		if (isset($this->options['wpc_map_layers_stations'])) {
			esc_attr( $this->options['wpc_map_layers_stations']);
		}
    } 
	
	public function wpc_map_layers_clouds_callback()
    {
		$options = get_option( 'wpc_option_name' );
    
		$check = isset($options['wpc_map_layers_clouds']);
		
        echo '<input id="wpc_map_layers_clouds" name="wpc_option_name[wpc_map_layers_clouds]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_map_layers_clouds">'. __( 'Display clouds on all weather maps?', 'wp-cloudy' ) .'</label>';
		
		if (isset($this->options['wpc_map_layers_clouds'])) {
			esc_attr( $this->options['wpc_map_layers_clouds']);
		}
    }
	
	public function wpc_map_layers_precipitation_callback()
    {
		$options = get_option( 'wpc_option_name' );  
		  
		$check = isset($options['wpc_map_layers_precipitation']);
		
        echo '<input id="wpc_map_layers_precipitation" name="wpc_option_name[wpc_map_layers_precipitation]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_map_layers_precipitation">'. __( 'Display precipitations on all weather maps?', 'wp-cloudy' ) .'</label>';
		
		if (isset($this->options['wpc_map_layers_precipitation'])) {
			esc_attr( $this->options['wpc_map_layers_precipitation']);
		}

    }
	
	public function wpc_map_layers_snow_callback()
    {
		$options = get_option( 'wpc_option_name' );
		
		$check = isset($options['wpc_map_layers_snow']);
		
        echo '<input id="wpc_map_layers_snow" name="wpc_option_name[wpc_map_layers_snow]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_map_layers_snow">'. __( 'Display snow on all weather maps?', 'wp-cloudy' ) .'</label>';
		
		if (isset($this->options['wpc_map_layers_snow'])) {
			esc_attr( $this->options['wpc_map_layers_snow']);
		}
    }
	
	public function wpc_map_layers_wind_callback()
    {
		$options = get_option( 'wpc_option_name' );
		
		$check = isset($options['wpc_map_layers_wind']);
		
        echo '<input id="wpc_map_layers_wind" name="wpc_option_name[wpc_map_layers_wind]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_map_layers_wind">'. __( 'Display wind on all weather maps?', 'wp-cloudy' ) .'</label>';
		
		if (isset($this->options['wpc_map_layers_wind'])) {
			esc_attr( $this->options['wpc_map_layers_wind']);
		}
    }
	
	public function wpc_map_layers_temperature_callback()
    {
		$options = get_option( 'wpc_option_name' ); 
		  
		$check = isset($options['wpc_map_layers_temperature']);
		
        echo '<input id="wpc_map_layers_temperature" name="wpc_option_name[wpc_map_layers_temperature]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_map_layers_temperature">'. __( 'Display temperatures on all weather maps?', 'wp-cloudy' ) .'</label>';
		
		if (isset($this->options['wpc_map_layers_temperature'])) {
			esc_attr( $this->options['wpc_map_layers_temperature']);
		}
    }
    
	public function wpc_map_layers_pressure_callback()
    {
		$options = get_option( 'wpc_option_name' );    
		
		$check = isset($options['wpc_map_layers_pressure']);
		
        echo '<input id="wpc_map_layers_pressure" name="wpc_option_name[wpc_map_layers_pressure]" type="checkbox"';
		if ('1' == $check) echo 'checked="yes"'; 
		echo ' value="1"/>';
		echo '<label for="wpc_map_layers_pressure">'. __( 'Display pressure on all weather maps?', 'wp-cloudy' ) .'</label>';
		
		if (isset($this->options['wpc_map_layers_pressure'])) {
			esc_attr( $this->options['wpc_map_layers_pressure']);
		}
    }

    public function wpc_support_info_callback()
    {
		echo '
			<h3>'. __("Problem with WP Cloudy?", "wp-cloudy").'</h3>
			<p><a href="https://www.wpcloudy.com/support/faq/" target="_blank" title="'. __("FAQ", "wp-cloudy").'">'. __("Read our FAQ", "wp-cloudy").'</a></p>
			<p><a href="https://www.wpcloudy.com/support/guides/" target="_blank" title="'. __("Guides", "wp-cloudy").'">'.__("Read our Guides", "wp-cloudy").'</a></p>
			<p><a href="https://wordpress.org/plugins/wp-cloudy/" target="_blank" title="'. __("WP Cloudy Forum on WordPress.org", "wp-cloudy").'">'. __("WP Cloudy Forum on WordPress.org", "wp-cloudy").'</a></p>
			';
		
    } 
	
}
	
if( is_admin() )
    $my_settings_page = new wpc_options();
	
//Help Tab
function wpc_help_tab() {
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
?>