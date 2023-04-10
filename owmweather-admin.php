<?php
// To prevent calling the plugin directly
if (!function_exists('add_action')) {
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
    public function __construct()
    {
        $hook = is_multisite() && (is_network_admin() || owmw_is_global_multisite()) ? 'network_' : '';
        add_action("{$hook}admin_menu", array( $this, "owmw_add_plugin_page" ));
        add_action("admin_init", array( $this, "page_init" ));
        if (is_multisite() && is_network_admin()) {
            add_action("admin_init", array( $this, "update_settings" ));
        }
    }

    /**
     * Add options page
     */
    public function owmw_add_plugin_page()
    {
        $parent     = is_multisite() && is_network_admin() ? 'settings.php' : 'options-general.php';
        $capability = is_multisite() && is_network_admin() ? 'manage_network_options' : 'manage_options';

        // This page will be under "Settings"
        $hookname = add_submenu_page(
            $parent,
            esc_html__('OWM Weather Settings', 'owm-weather'),
            esc_html_x('OWM Weather', 'Menu item', 'owm-weather'),
            $capability,
            'owmw-settings-admin',
            array( $this, 'owmw_create_admin_page' )
        );
        add_action('load-'.$hookname, 'owmw_help_tab');
    }

    public function update_settings()
    {
        if ($_SERVER["REQUEST_METHOD"] != "POST" || empty($_POST)) {
            return;
        }

        if ((isset($_POST["action"]) && sanitize_text_field($_POST["action"]) == "update") &&
            (isset($_POST["option_page"]) && sanitize_text_field($_POST["option_page"]) == "owmw_cloudy_option_group")) {
            check_ajax_referer('owmw_cloudy_option_group-options', sanitize_text_field($_POST['_wpnonce']), true);
            
            if (is_multisite() && is_network_admin() && is_network_admin() && current_user_can("manage_network_options") && !empty($_POST["owmw_option_name"])) {
                $this->options = $this->sanitize($_POST["owmw_option_name"]);
                update_site_option('owmw_option_name', $this->options);
                
                new Log_Success(__('Settings saved.', 'owm-weather'));
            }
        }
    }

    /**
     * Options page callback
     */
    public function owmw_create_admin_page()
    {

        wp_enqueue_media();
        add_action('admin_footer', 'owmw_media_selector_print_scripts');

        if (is_multisite() && is_network_admin()) {
            $this->options = get_site_option('owmw_option_name');
        } else {
            $this->options = get_option('owmw_option_name');
        }
        $owmw_info_version = get_plugin_data(plugin_dir_path(__FILE__).'/owmweather.php');
        ?>

        <div id="owmweather-header">
            <div id="owmweather-clouds">
                <h3>
                    <?php esc_html_e('OWM Weather', 'owm-weather'); ?>
                </h3>
                <span class="owmw-info-version"><?php print_r($owmw_info_version['Version']); ?></span>
                <div id="owmweather-notice">
                    <div class="small">

                                <span class="dashicons dashicons-wordpress"></span>
                                <?php esc_html_e('Do you like OWM Weather? Don\'t forget to rate it 5 stars!', 'owm-weather'); ?>

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
                            <?php esc_html_e('Consider a donation', 'owm-weather'); ?>:
                            <input type="hidden" name="hosted_button_id" value="PQDNJGKMLHAFU" />
                            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
                            <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
                        </form>

            </div>
        </div>

        <?php
        function owmw_settings_admin_export_import_reset()
        {
            $action = is_multisite() && is_network_admin() ? network_admin_url('settings.php?page=owmw_export_settings') : admin_url('options.php');
            ?>
                <div id="owmw_export_form" class="metabox-holder">
                    <div class="postbox">
                        <h3><span><?php esc_html_e('Export Settings', 'owm-weather'); ?></span></h3>
                        <div class="inside">
                            <p><?php esc_html_e('Export the plugin settings for this site as a .json file. This allows you to easily import the configuration into another site.', 'owm-weather'); ?></p>
                            <form method="post">
                                <p><input type="hidden" name="owmw_action" value="owmw_export_settings" /></p>
                                <p>
                                    <?php wp_nonce_field('owmw_export_nonce', 'owmw_export_nonce'); ?>
                                    <?php submit_button(esc_html__('Export', 'owm-weather'), 'secondary', 'submit', false); ?>
                                </p>
                            </form>
                        </div><!-- .inside -->
                    </div><!-- .postbox -->

                    <div class="postbox">
                        <h3><span><?php esc_html_e('Import Settings', 'owm-weather'); ?></span></h3>
                        <div class="inside">
                            <p><?php esc_html_e('Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'owm-weather'); ?></p>
                            <form method="post" enctype="multipart/form-data">
                                <p>
                                    <input type="file" name="owmw_import_file"/>
                                </p>
                                <p>
                                    <input type="hidden" name="owmw_action" value="owmw_import_settings" />
                                    <?php wp_nonce_field('owmw_import_nonce', 'owmw_import_nonce'); ?>
                                    <?php submit_button(esc_html__('Import', 'owm-weather'), 'secondary', 'submit', false); ?>
                                </p>
                            </form>
                        </div><!-- .inside -->
                    </div><!-- .postbox -->

                    <div class="postbox">
                        <h3><span><?php esc_html_e('Reset Settings', 'owm-weather'); ?></span></h3>
                        <div class="inside">
                            <p><?php esc_html_e('Reset all OWM Weather global settings. It will not delete your weather pages and their individual settings.', 'owm-weather'); ?></p>
                            <form method="post" enctype="multipart/form-data">
                                <p>
                                    <input type="hidden" name="owmw_action" value="owmw_reset_settings" />
                                    <?php wp_nonce_field('owmw_reset_nonce', 'owmw_reset_nonce'); ?>
                                    <?php submit_button(esc_html__('Reset settings', 'owm-weather'), 'secondary', 'submit', false); ?>
                                </p>
                            </form>
                        </div><!-- .inside -->
                    </div><!-- .postbox -->
                </div><!-- .metabox-holder -->
                <?php
        }
        ?>

        <?php owmw_settings_admin_export_import_reset();

        $action = is_multisite() && is_network_admin() ? network_admin_url('settings.php?page=owmw-settings-admin') : admin_url('options.php');?>

        <form method="post" action="<?php echo esc_attr($action); ?>" class="owmweather-settings">
            <?php settings_fields('owmw_cloudy_option_group'); ?>

            <div id="owmweather-tabs">
                <h2 class="nav-tab-wrapper hide-if-no-js">
                    <ul>
                    <?php if (is_multisite() && is_network_admin()) { ?>
                        <li><a href="#tab_network" class="nav-tab"><?php esc_html_e('Network', 'owm-weather'); ?></a></li>
                    <?php } ?>
                        <li class="only-multisite"><a href="#tab_advanced" class="nav-tab"><?php esc_html_e('System', 'owm-weather'); ?></a></li>
                        <li class="only-multisite"><a href="#tab_basic" class="nav-tab"><?php esc_html_e('Basic', 'owm-weather'); ?></a></li>
                        <li class="only-multisite"><a href="#tab_display" class="nav-tab"><?php esc_html_e('Display', 'owm-weather'); ?></a></li>
                        <li class="only-multisite"><a href="#tab_layout" class="nav-tab"><?php esc_html_e('Layout', 'owm-weather'); ?></a></li>
                        <li class="only-multisite"><a href="#tab_weather_based" class="nav-tab"><?php esc_html_e('Weather-based', 'owm-weather'); ?></a></li>
                        <li class="only-multisite"><a href="#tab_map" class="nav-tab"><?php esc_html_e('Map', 'owm-weather'); ?></a></li>
                        <li><a href="#tab_export" class="nav-tab"><?php esc_html_e('Import/Export/Reset', 'owm-weather'); ?></a></li>
                        <li><a href="#tab_support" class="nav-tab"><?php esc_html_e('Support', 'owm-weather'); ?></a></li>
                    </ul>
                </h2>

                <div id="owmweather-tabs-settings">
                    <?php if (is_multisite() && is_network_admin()) { ?>
                        <div class="owmw-tab" id="tab_network"><?php do_settings_sections('owmw-settings-admin-network'); ?></div>
                    <?php } ?>
                    <div class="owmw-tab" id="tab_advanced"><?php do_settings_sections('owmw-settings-admin-advanced'); ?></div>
                    <div class="owmw-tab" id="tab_basic"><?php do_settings_sections('owmw-settings-admin-basic'); ?></div>
                    <div class="owmw-tab" id="tab_display"><?php do_settings_sections('owmw-settings-admin-display'); ?></div>
                    <div class="owmw-tab" id="tab_layout"><?php do_settings_sections('owmw-settings-admin-layout'); ?></div>
                    <div class="owmw-tab" id="tab_weather_based"><?php do_settings_sections('owmw-settings-admin-weather-based'); ?></div>
                    <div class="owmw-tab" id="tab_map"><?php do_settings_sections('owmw-settings-admin-map'); ?></div>
                    <div class="owmw-tab" id="tab_export"></div>
                    <div class="owmw-tab" id="tab_support"><?php do_settings_sections('owmw-settings-admin-support'); ?></div>
                </div>
            </div>
        <script>jQuery("#owmw_export_form").detach().appendTo('#tab_export')</script>
        <div style="padding-top: 15px;">
             <?php submit_button(esc_html__('Save changes', 'owm-weather'), 'primary', 'submit', false); ?>
        </div>
        </form>

        <div class="owmweather-sidebar">
            <div id="owmweather-cache" class="owmweather-module owmweather-inactive" style="height: 177px;">
                <h3><?php esc_html_e('OWM Weather cache', 'owm-weather'); ?></h3>
                <div class="owmweather-module-description">
                    <div class="module-image">
                        <div class="dashicons dashicons-trash"></div>
                        <p><span class="module-image-badge"><?php esc_html_e('cache system', 'owm-weather'); ?></span></p>
                    </div>

                    <p><?php esc_html_e('Click this button to refresh the weather cache.', 'owm-weather'); ?></p>

                    <?php
                    function owmw_clear_all_cache()
                    {
                        $url = (is_multisite() && is_network_admin() ? network_admin_url('settings.php') : admin_url('options-general.php')) . '?page=owmw-settings-admin';
                        ?>
                        <div class="owmweather-module-actions">
                            <a href="<?php print add_query_arg('owmw_clear_all_cache_nonce', wp_create_nonce('owmw_clear_all_cache'), $url);?>"
                                class="button">
                                <?php esc_html_e('Clear cache!', 'owm-weather');?>
                            </a>
                        </div>

                        <?php
                        if (isset($_GET['owmw_clear_all_cache_nonce'])) {
                            if (wp_verify_nonce(sanitize_text_field($_GET['owmw_clear_all_cache_nonce']), 'owmw_clear_all_cache')) {
                                global $wpdb;
                                $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_owmw%' ");
                                $wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_timeout_owmw%' ");
                                if (is_multisite() && is_network_admin() && is_network_admin() && current_user_can("manage_network_options")) {
                                    $wpdb->query("DELETE FROM $wpdb->sitemeta WHERE meta_key LIKE '_site_transient_owmw%' ");
                                    $wpdb->query("DELETE FROM $wpdb->sitemeta WHERE meta_key LIKE 'site__transient_timeout_owmw%' ");
                                }
                            }
                        }
                    }
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
    public function page_init()
    {
        register_setting(
            'owmw_cloudy_option_group', // Option group
            'owmw_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

    //BASIC SECTION============================================================================
        add_settings_section(
            'owmw_setting_section_basic', // ID
            esc_html__("Basic Settings", 'owm-weather'), // Title
            array( $this, 'owmw_print_section_info_basic' ), // Callback
            'owmw-settings-admin-basic' // Page
        );

        add_settings_field(
            'owmw_unit', // ID
            esc_html__("Bypass Measurement System", 'owm-weather'), // Title
            array( $this, 'owmw_basic_unit_callback' ), // Callback
            'owmw-settings-admin-basic', // Page
            'owmw_setting_section_basic' // Section
        );

        add_settings_field(
            'owmw_time_format', // ID
            esc_html__("Bypass Time Format", 'owm-weather'), // Title
            array( $this, 'owmw_basic_time_format_callback' ), // Callback
            'owmw-settings-admin-basic', // Page
            'owmw_setting_section_basic' // Section
        );

        add_settings_field(
            'owmw_custom_timezone', // ID
            esc_html__("Bypass Timezone", 'owm-weather'), // Title
            array( $this, 'owmw_basic_custom_timezone_callback' ), // Callback
            'owmw-settings-admin-basic', // Page
            'owmw_setting_section_basic' // Section
        );

        add_settings_field(
            'owmw_owm_language', // ID
            esc_html__("Bypass OpenWeatherMap Language", 'owm-weather'), // Title
            array( $this, 'owmw_basic_owm_language_callback' ), // Callback
            'owmw-settings-admin-basic', // Page
            'owmw_setting_section_basic' // Section
        );

        //DISPLAY SECTION==========================================================================
        add_settings_section(
            'owmw_setting_section_display', // ID
            esc_html__("Display Settings", 'owm-weather'), // Title
            array( $this, 'owmw_print_section_info_display' ), // Callback
            'owmw-settings-admin-display' // Page
        );

        add_settings_field(
            'owmw_current_city_name', // ID
            esc_html__("Current City Name", 'owm-weather'), // Title
            array( $this, 'owmw_display_current_city_name_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_current_weather_symbol', // ID
            esc_html__("Current Weather Symbol", 'owm-weather'), // Title
            array( $this, 'owmw_display_current_weather_symbol_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_current_temperature', // ID
            esc_html__("Current Temperature", 'owm-weather'), // Title
            array( $this, 'owmw_display_current_temperature_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_current_feels_like', // ID
            esc_html__("Current Feels-Like Temperature", 'owm-weather'), // Title
            array( $this, 'owmw_display_current_feels_like_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_current_weather_description', // ID
            esc_html__("Current Short Condition", 'owm-weather'), // Title
            array( $this, 'owmw_display_weather_description_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_display_temperature_unit', // ID
            esc_html__("Temperature Unit (C / F)", 'owm-weather'), // Title
            array( $this, 'owmw_display_date_temp_unit_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_today_date_format', // ID
            esc_html__("Date", 'owm-weather'), // Title
            array( $this, 'owmw_display_today_date_format_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_sunrise_sunset', // ID
            esc_html__("Sunrise + Sunset", 'owm-weather'), // Title
            array( $this, 'owmw_display_sunrise_sunset_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_moonrise_moonset', // ID
            esc_html__("Moonrise + Moonset", 'owm-weather'), // Title
            array( $this, 'owmw_display_moonrise_moonset_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_wind', // ID
            esc_html__("Wind", 'owm-weather'), // Title
            array( $this, 'owmw_display_wind_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_wind_unit', // ID
            esc_html__("Wind Unit", 'owm-weather'), // Title
            array( $this, 'owmw_display_wind_unit_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_wind_icon_direction', // ID
            esc_html__("Wind Icon Pointer Direction", 'owm-weather'), // Title
            array( $this, 'owmw_display_wind_icon_direction_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_humidity', // ID
            esc_html__("Humidity", 'owm-weather'), // Title
            array( $this, 'owmw_display_humidity_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_dew_point', // ID
            esc_html__("Dew Point", 'owm-weather'), // Title
            array( $this, 'owmw_display_dew_point_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_pressure', // ID
            esc_html__("Pressure", 'owm-weather'), // Title
            array( $this, 'owmw_display_pressure_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_pressure_unit', // ID
            esc_html__("Pressure Unit", 'owm-weather'), // Title
            array( $this, 'owmw_display_pressure_unit_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_cloudiness', // ID
            esc_html__("Cloudiness", 'owm-weather'), // Title
            array( $this, 'owmw_display_cloudiness_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_precipitation', // ID
            esc_html__("Precipitation", 'owm-weather'), // Title
            array( $this, 'owmw_display_precipitation_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_visibility', // ID
            esc_html__("Visibility", 'owm-weather'), // Title
            array( $this, 'owmw_display_visibility_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_uv_index', // ID
            esc_html__("UV Index", 'owm-weather'), // Title
            array( $this, 'owmw_display_uv_index_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_alerts', // ID
            esc_html__("Alerts", 'owm-weather'), // Title
            array( $this, 'owmw_display_alerts_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_alerts_popup', // ID
            esc_html__("Alerts Popup:", 'owm-weather'), // Title
            array( $this, 'owmw_display_alerts_popup_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_text_labels', // ID
            esc_html__("Show only Icon Labels", 'owm-weather'), // Title
            array( $this, 'owmw_display_text_labels_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_hours_forecast_no', // ID
            esc_html__("Number of Forecast Hours", 'owm-weather'), // Title
            array( $this, 'owmw_display_hour_forecast_no_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_hours_time_icons', // ID
            esc_html__("Display Time Icons", 'owm-weather'), // Title
            array( $this, 'owmw_display_hour_time_icons_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_forecast_no', // ID
            esc_html__("Number of Forecast Days", 'owm-weather'), // Title
            array( $this, 'owmw_display_forecast_no_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_display_length_days_names', // ID
            esc_html__("Length Day Names:", 'owm-weather'), // Title
            array( $this, 'owmw_display_display_length_days_names_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_owm_link', // ID
            esc_html__("Link to OpenWeatherMap", 'owm-weather'), // Title
            array( $this, 'owmw_display_owm_link_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        add_settings_field(
            'owmw_last_update', // ID
            esc_html__("Data Update Time", 'owm-weather'), // Title
            array( $this, 'owmw_display_last_update_callback' ), // Callback
            'owmw-settings-admin-display', // Page
            'owmw_setting_section_display' // Section
        );

        //LAYOUT SECTION=========================================================================
        add_settings_section(
            'owmw_setting_section_layout', // ID
            esc_html__("Layout Settings", 'owm-weather'), // Title
            array( $this, 'owmw_print_section_info_layout' ), // Callback
            'owmw-settings-admin-layout' // Page
        );

        add_settings_field(
            'owmw_template', // ID
            esc_html__("Template"), // Title
            array( $this, 'owmw_layout_template_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_font', // ID
            esc_html__("Font"), // Title
            array( $this, 'owmw_layout_font_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_iconpack', // ID
            esc_html__("Icon Pack"), // Title
            array( $this, 'owmw_layout_iconpack_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_disable_spinner', // ID
            esc_html__("Spinner", 'owm-weather'), // Title
            array( $this, 'owmw_layout_disable_spinner_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_disable_anims', // ID
            esc_html__("Disable Animations for Main Icon", 'owm-weather'), // Title
            array( $this, 'owmw_layout_disable_anims_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_background_color', // ID
            esc_html__("Background Color", 'owm-weather'), // Title
            array( $this, 'owmw_layout_background_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_background_image', // ID
            esc_html__("Background Image", 'owm-weather'), // Title
            array( $this, 'owmw_layout_background_image_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_background_yt_video', // ID
            esc_html__("Background YouTube Video", 'owm-weather'), // Title
            array( $this, 'owmw_layout_background_yt_video_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_text_color', // ID
            esc_html__("Text Color", 'owm-weather'), // Title
            array( $this, 'owmw_layout_text_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_border_color', // ID
            esc_html__("Border Color", 'owm-weather'), // Title
            array( $this, 'owmw_layout_border_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_border_width', // ID
            esc_html__("Border Width (in px)", 'owm-weather'), // Title
            array( $this, 'owmw_layout_border_width_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_border_style', // ID
            esc_html__("Border Style", 'owm-weather'), // Title
            array( $this, 'owmw_layout_border_style_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_border_radius', // ID
            esc_html__("Border Radius", 'owm-weather'), // Title
            array( $this, 'owmw_layout_border_radius_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_size', // ID
            esc_html__("Weather Size", 'owm-weather'), // Title
            array( $this, 'owmw_layout_size_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );


        add_settings_field(
            'owmw_custom_css', // ID
            esc_html__("Custom CSS", 'owm-weather'), // Title
            array( $this, 'owmw_layout_custom_css_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_tabbed_btn_text_color', // ID
            esc_html__("Tabbed Button Text Color", 'owm-weather'), // Title
            array( $this, 'owmw_layout_tabbed_btn_text_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_tabbed_btn_background_color', // ID
            esc_html__("Tabbed Button Background Color", 'owm-weather'), // Title
            array( $this, 'owmw_layout_tabbed_btn_background_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );
        add_settings_field(
            'owmw_tabbed_btn_active_color', // ID
            esc_html__("Tabbed Button Active Color", 'owm-weather'), // Title
            array( $this, 'owmw_layout_tabbed_btn_active_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );
        add_settings_field(
            'owmw_tabbed_btn_hover_color', // ID
            esc_html__("Tabbed Button Hover Color", 'owm-weather'), // Title
            array( $this, 'owmw_layout_tabbed_btn_hover_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );
        
        add_settings_field(
            'owmw_table_background_color', // ID
            esc_html__("Table Background Color", 'owm-weather'), // Title
            array( $this, 'owmw_layout_table_background_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_table_text_color', // ID
            esc_html__("Table Text Color", 'owm-weather'), // Title
            array( $this, 'owmw_layout_table_text_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_table_border_color', // ID
            esc_html__("Table Border Color", 'owm-weather'), // Title
            array( $this, 'owmw_layout_table_border_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_table_border_width', // ID
            esc_html__("Table Border Width (in px)", 'owm-weather'), // Title
            array( $this, 'owmw_layout_table_border_width_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_table_border_style', // ID
            esc_html__("Table Border Style", 'owm-weather'), // Title
            array( $this, 'owmw_layout_table_border_style_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_table_border_radius', // ID
            esc_html__("Table Border Radius", 'owm-weather'), // Title
            array( $this, 'owmw_layout_table_border_radius_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );


        add_settings_field(
            'owmw_chart_height', // ID
            esc_html__("Chart Height", 'owm-weather'), // Title
            array( $this, 'owmw_layout_chart_height_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_chart_text_color', // ID
            esc_html__("Chart Text Color", 'owm-weather'), // Title
            array( $this, 'owmw_layout_chart_text_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_chart_night_color', // ID
            esc_html__("Chart Night Highlight Color", 'owm-weather'), // Title
            array( $this, 'owmw_layout_chart_night_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_chart_background_color', // ID
            esc_html__("Chart Background Color", 'owm-weather'), // Title
            array( $this, 'owmw_layout_chart_background_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_chart_border_color', // ID
            esc_html__("Chart Border Color", 'owm-weather'), // Title
            array( $this, 'owmw_layout_chart_border_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_chart_border_width', // ID
            esc_html__("Chart Border Width", 'owm-weather'), // Title
            array( $this, 'owmw_layout_chart_border_width_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_chart_border_style', // ID
            esc_html__("Chart Border Style", 'owm-weather'), // Title
            array( $this, 'owmw_layout_chart_border_style_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_chart_border_radius', // ID
            esc_html__("Chart Border Radius", 'owm-weather'), // Title
            array( $this, 'owmw_layout_chart_border_radius_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_chart_temperature_color', // ID
            esc_html__("Chart Temperature Color", 'owm-weather'), // Title
            array( $this, 'owmw_layout_chart_temperature_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_chart_feels_like_color', // ID
            esc_html__("Chart Feels-Like Color", 'owm-weather'), // Title
            array( $this, 'owmw_layout_chart_feels_like_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_chart_dew_point_color', // ID
            esc_html__("Chart Dew Point Color", 'owm-weather'), // Title
            array( $this, 'owmw_layout_chart_dew_point_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_chart_cloudiness_color', // ID
            esc_html__("Chart Cloudiness Color", 'owm-weather'), // Title
            array( $this, 'owmw_layout_chart_cloudiness_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_chart_rain_chance_color', // ID
            esc_html__("Chart Rain Chance Color", 'owm-weather'), // Title
            array( $this, 'owmw_layout_chart_rain_chance_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_chart_humidity_color', // ID
            esc_html__("Chart Humidity Color", 'owm-weather'), // Title
            array( $this, 'owmw_layout_chart_humidity_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_chart_pressure_color', // ID
            esc_html__("Chart Pressure Color", 'owm-weather'), // Title
            array( $this, 'owmw_layout_chart_pressure_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_chart_rain_amt_color', // ID
            esc_html__("Chart Rain Amount Color", 'owm-weather'), // Title
            array( $this, 'owmw_layout_chart_rain_amt_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_chart_snow_amt_color', // ID
            esc_html__("Chart Snow Amount Color", 'owm-weather'), // Title
            array( $this, 'owmw_layout_chart_snow_amt_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_chart_wind_speed_color', // ID
            esc_html__("Chart Wind Speed Color", 'owm-weather'), // Title
            array( $this, 'owmw_layout_chart_wind_speed_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        add_settings_field(
            'owmw_chart_wind_gust_color', // ID
            esc_html__("Chart Wind Gust Color", 'owm-weather'), // Title
            array( $this, 'owmw_layout_chart_wind_gust_color_callback' ), // Callback
            'owmw-settings-admin-layout', // Page
            'owmw_setting_section_layout' // Section
        );

        //WEATHER-BASED SECTION=========================================================================
        add_settings_section(
            'owmw_setting_section_weather_based', // ID
            esc_html__("Weather-based Settings", 'owm-weather'), // Title
            array( $this, 'owmw_print_section_info_weather_based' ), // Callback
            'owmw-settings-admin-weather-based' // Page
        );

        add_settings_field(
            'owmw_sunny_text_color', // ID
            esc_html__("Sunny Text Color", 'owm-weather'), // Title
            array( $this, 'owmw_weather_based_sunny_text_color_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_sunny_background_color', // ID
            esc_html__("Sunny Background Color", 'owm-weather'), // Title
            array( $this, 'owmw_weather_based_sunny_background_color_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_sunny_background_image', // ID
            esc_html__("Sunny Background Image", 'owm-weather'), // Title
            array( $this, 'owmw_weather_based_sunny_background_image_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_sunny_background_yt_video', // ID
            esc_html__("Sunny Background YouTube Video", 'owm-weather'), // Title
            array( $this, 'owmw_layout_sunny_background_yt_video_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_cloudy_text_color', // ID
            esc_html__("Cloudy Text Color", 'owm-weather'), // Title
            array( $this, 'owmw_weather_based_cloudy_text_color_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_cloudy_background_color', // ID
            esc_html__("Cloudy Background Color", 'owm-weather'), // Title
            array( $this, 'owmw_weather_based_cloudy_background_color_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_cloudy_background_image', // ID
            esc_html__("Cloudy Background Image", 'owm-weather'), // Title
            array( $this, 'owmw_weather_based_cloudy_background_image_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_cloudy_background_yt_video', // ID
            esc_html__("Cloudy Background YouTube Video", 'owm-weather'), // Title
            array( $this, 'owmw_layout_cloudy_background_yt_video_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_drizzly_text_color', // ID
            esc_html__("Drizzly Text Color", 'owm-weather'), // Title
            array( $this, 'owmw_weather_based_drizzly_text_color_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_drizzly_background_color', // ID
            esc_html__("Drizzly Background Color", 'owm-weather'), // Title
            array( $this, 'owmw_weather_based_drizzly_background_color_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_drizzly_background_image', // ID
            esc_html__("Drizzly Background Image", 'owm-weather'), // Title
            array( $this, 'owmw_weather_based_drizzly_background_image_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_drizzly_background_yt_video', // ID
            esc_html__("Drizzly Background YouTube Video", 'owm-weather'), // Title
            array( $this, 'owmw_layout_drizzly_background_yt_video_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_rainy_text_color', // ID
            esc_html__("Rainy Text Color", 'owm-weather'), // Title
            array( $this, 'owmw_weather_based_rainy_text_color_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_rainy_background_color', // ID
            esc_html__("Rainy Background Color", 'owm-weather'), // Title
            array( $this, 'owmw_weather_based_rainy_background_color_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_rainy_background_image', // ID
            esc_html__("Rainy Background Image", 'owm-weather'), // Title
            array( $this, 'owmw_weather_based_rainy_background_image_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_rainy_background_yt_video', // ID
            esc_html__("Rainy Background YouTube Video", 'owm-weather'), // Title
            array( $this, 'owmw_layout_rainy_background_yt_video_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_snowy_text_color', // ID
            esc_html__("Snowy Text Color", 'owm-weather'), // Title
            array( $this, 'owmw_weather_based_snowy_text_color_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_snowy_background_color', // ID
            esc_html__("Snowy Background Color", 'owm-weather'), // Title
            array( $this, 'owmw_weather_based_snowy_background_color_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_snowy_background_image', // ID
            esc_html__("Snowy Background Image", 'owm-weather'), // Title
            array( $this, 'owmw_weather_based_snowy_background_image_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_snowy_background_yt_video', // ID
            esc_html__("Snowy Background YouTube Video", 'owm-weather'), // Title
            array( $this, 'owmw_layout_snowy_background_yt_video_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_stormy_text_color', // ID
            esc_html__("Stormy Text Color", 'owm-weather'), // Title
            array( $this, 'owmw_weather_based_stormy_text_color_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_stormy_background_color', // ID
            esc_html__("Stormy Background Color", 'owm-weather'), // Title
            array( $this, 'owmw_weather_based_stormy_background_color_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_stormy_background_image', // ID
            esc_html__("Stormy Background Image", 'owm-weather'), // Title
            array( $this, 'owmw_weather_based_stormy_background_image_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_stormy_background_yt_video', // ID
            esc_html__("Stormy Background YouTube Video", 'owm-weather'), // Title
            array( $this, 'owmw_layout_stormy_background_yt_video_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_foggy_text_color', // ID
            esc_html__("Foggy Text Color", 'owm-weather'), // Title
            array( $this, 'owmw_weather_based_foggy_text_color_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_foggy_background_color', // ID
            esc_html__("Foggy Background Color", 'owm-weather'), // Title
            array( $this, 'owmw_weather_based_foggy_background_color_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_foggy_background_image', // ID
            esc_html__("Foggy Background Image", 'owm-weather'), // Title
            array( $this, 'owmw_weather_based_foggy_background_image_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        add_settings_field(
            'owmw_foggy_background_yt_video', // ID
            esc_html__("Foggy Background YouTube Video", 'owm-weather'), // Title
            array( $this, 'owmw_layout_foggy_background_yt_video_callback' ), // Callback
            'owmw-settings-admin-weather-based', // Page
            'owmw_setting_section_weather_based' // Section
        );

        //NETWORK SECTION=========================================================================
        if (is_multisite() && is_network_admin()) {
            add_settings_section(
                'owmw_setting_section_network', // ID
                esc_html__("Network Settings", 'owm-weather'), // Title
                array( $this, 'owmw_print_section_info_network' ), // Callback
                'owmw-settings-admin-network' // Page
            );

            add_settings_field(
                'owmw_network_multisite', // ID
                esc_html__("Global Multisite Setup", 'owm-weather'), // Title
                array( $this, 'owmw_network_multisite_callback' ), // Callback
                'owmw-settings-admin-network', // Page
                'owmw_setting_section_network' // Section
            );
        }

        //SYSTEM SECTION=========================================================================
        add_settings_section(
            'owmw_setting_section_advanced', // ID
            esc_html__("Advanced Settings", 'owm-weather'), // Title
            array( $this, 'owmw_print_section_info_advanced' ), // Callback
            'owmw-settings-admin-advanced' // Page
        );

        add_settings_field(
            'owmw_advanced_disable_cache', // ID
            esc_html__("Disable Cache", 'owm-weather'), // Title
            array( $this, 'owmw_advanced_disable_cache_callback' ), // Callback
            'owmw-settings-admin-advanced', // Page
            'owmw_setting_section_advanced' // Section
        );

        add_settings_field(
            'owmw_advanced_cache_time', // ID
            esc_html__("Time Until Cache Refresh (in Minutes)", 'owm-weather'), // Title
            array( $this, 'owmw_advanced_cache_time_callback' ), // Callback
            'owmw-settings-admin-advanced', // Page
            'owmw_setting_section_advanced' // Section
        );

        add_settings_field(
            'owmw_advanced_api_key', // ID
            esc_html__("Open Weather Map API Key", 'owm-weather'), // Title
            array( $this, 'owmw_advanced_api_key_callback' ), // Callback
            'owmw-settings-admin-advanced', // Page
            'owmw_setting_section_advanced' // Section
        );

        add_settings_field(
            'owmw_advanced_disable_modal_js', // ID
            esc_html__("Disable Bootstrap", 'owm-weather'), // Title
            array( $this, 'owmw_advanced_disable_bootstrap_callback' ), // Callback
            'owmw-settings-admin-advanced', // Page
            'owmw_setting_section_advanced' // Section
        );

        add_settings_field(
            'owmw_advanced_bootstrap_version', // ID
            esc_html__("Bootstrap Version", 'owm-weather'), // Title
            array( $this, 'owmw_advanced_bootstrap_version_callback' ), // Callback
            'owmw-settings-admin-advanced', // Page
            'owmw_setting_section_advanced' // Section
        );

        //MAP SECTION =============================================================================

        add_settings_section(
            'owmw_setting_section_map', // ID
            esc_html__("Map Settings", 'owm-weather'), // Title
            array( $this, 'owmw_print_section_info_map' ), // Callback
            'owmw-settings-admin-map' // Page
        );

        add_settings_field(
            'owmw_map', // ID
            esc_html__("Map", 'owm-weather'), // Title
            array( $this, 'owmw_map_display_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_height', // ID
            esc_html__("Map Height", 'owm-weather'), // Title
            array( $this, 'owmw_map_height_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_opacity', // ID
            esc_html__("Layers Opacity", 'owm-weather'), // Title
            array( $this, 'owmw_map_opacity_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_zoom', // ID
            esc_html__("Zoom", 'owm-weather'), // Title
            array( $this, 'owmw_map_zoom_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_disable_zoom_wheel', // ID
            esc_html__("Disable Zoom Wheel", 'owm-weather'), // Title
            array( $this, 'owmw_map_disable_zoom_wheel_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_cities', // ID
            esc_html__("Cities Layer", 'owm-weather'), // Title
            array( $this, 'owmw_map_layers_cities_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_cities_legend', // ID
            esc_html__("Cities Layer Legend", 'owm-weather'), // Title
            array( $this, 'owmw_map_layers_cities_legend_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_cities_on', // ID
            esc_html__("Cities Layer On", 'owm-weather'), // Title
            array( $this, 'owmw_map_layers_cities_on_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_clouds', // ID
            esc_html__("Cloud Layer", 'owm-weather'), // Title
            array( $this, 'owmw_map_layers_clouds_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_clouds_legend', // ID
            esc_html__("Cloud Layer Legend", 'owm-weather'), // Title
            array( $this, 'owmw_map_layers_clouds_legend_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_clouds_on', // ID
            esc_html__("Cloud Layer On", 'owm-weather'), // Title
            array( $this, 'owmw_map_layers_clouds_on_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_precipitation', // ID
            esc_html__("Precipitation Layer", 'owm-weather'), // Title
            array( $this, 'owmw_map_layers_precipitation_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_precipitation_legend', // ID
            esc_html__("Precipitation Layer Legend", 'owm-weather'), // Title
            array( $this, 'owmw_map_layers_precipitation_legend_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_precipitation_on', // ID
            esc_html__("Precipitation Layer On", 'owm-weather'), // Title
            array( $this, 'owmw_map_layers_precipitation_on_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_rain', // ID
            esc_html__("Rain Layer", 'owm-weather'), // Title
            array( $this, 'owmw_map_layers_rain_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_rain_legend', // ID
            esc_html__("Rain Layer Legend", 'owm-weather'), // Title
            array( $this, 'owmw_map_layers_rain_legend_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_rain_on', // ID
            esc_html__("Rain Layer On", 'owm-weather'), // Title
            array( $this, 'owmw_map_layers_rain_on_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_snow', // ID
            esc_html__("Snow Layer", 'owm-weather'), // Title
            array( $this, 'owmw_map_layers_snow_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_snow_legend', // ID
            esc_html__("Snow Layer Legend", 'owm-weather'), // Title
            array( $this, 'owmw_map_layers_snow_legend_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_snow_on', // ID
            esc_html__("Snow Layer On", 'owm-weather'), // Title
            array( $this, 'owmw_map_layers_snow_on_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_wind', // ID
            esc_html__("Wind Layer", 'owm-weather'), // Title
            array( $this, 'owmw_map_layers_wind_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_wind_legend', // ID
            esc_html__("Wind Layer Legend", 'owm-weather'), // Title
            array( $this, 'owmw_map_layers_wind_legend_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_wind_on', // ID
            esc_html__("Wind Layer On", 'owm-weather'), // Title
            array( $this, 'owmw_map_layers_wind_on_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_temperature', // ID
            esc_html__("Temperature Layer", 'owm-weather'), // Title
            array( $this, 'owmw_map_layers_temperature_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_temperature_legend', // ID
            esc_html__("Temperature Layer Legend", 'owm-weather'), // Title
            array( $this, 'owmw_map_layers_temperature_legend_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_temperature_on', // ID
            esc_html__("Temperature Layer On", 'owm-weather'), // Title
            array( $this, 'owmw_map_layers_temperature_on_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_pressure', // ID
            esc_html__("Pressure Layer", 'owm-weather'), // Title
            array( $this, 'owmw_map_layers_pressure_callback' ), // Callback
            'owmw-settings-admin-map', // Page
            'owmw_setting_section_map' // Section
        );

        add_settings_field(
            'owmw_map_windrose', // ID
            esc_html__("Wind Rose Layer", 'owm-weather'), // Title
            array( $this, 'owmw_map_layers_windrose_callback' ), // Callback
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


        if (is_multisite() && is_network_admin()) {
            $this->options = get_site_option('owmw_option_name');
        } else {
            $this->options = get_option('owmw_option_name');
        }

        if ($this->options) {
            foreach ($this->options as $key => $value) {
                if ($value === '') {
                    unset($this->options[$key]);
                }
            }
        }
        
        if (is_multisite() && is_network_admin()) {
            update_site_option('owmw_option_name', $this->options);
        } else {
            update_option('owmw_option_name', $this->options);
        }
    }
    
    /**
     * Sanitize each setting field
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize($input)
    {
        if (!empty($input)) {
            foreach ($input as $k => &$v) {
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

    public function owmw_print_section_info_weather_based()
    {
        esc_html_e('Weather-based settings to bypass on all weather:', 'owm-weather');
    }

    public function owmw_print_section_info_network()
    {
        esc_html_e('OWM Weather Multisite settings:', 'owm-weather');
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
        if ('nobypass' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="nobypass">'. esc_html__('No bypass', 'owm-weather') .'</option>';
        echo ' <option ';
        if ('imperial' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="imperial">'. esc_html__('Imperial', 'owm-weather') .'</option>';
        echo '<option ';
        if ('metric' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="metric">'. esc_html__('Metric', 'owm-weather') .'</option>';
        echo '</select>';
    }

    public function owmw_basic_time_format_callback()
    {
        $selected = $this->options['owmw_time_format'] ?? "nobypass";

        echo '<select id="owmw_time_format" name="owmw_option_name[owmw_time_format]"> ';
        echo ' <option ';
        if ('nobypass' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="nobypass">'. esc_html__('No bypass', 'owm-weather') .'</option>';
        echo ' <option ';
        echo '<option ';
        if ('12' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="12">'. esc_html__('12 h', 'owm-weather') .'</option>';
        echo '<option ';
        if ('24' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="24">'. esc_html__('24 h', 'owm-weather') .'</option>';
        echo '</select>';
    }

    public function owmw_basic_custom_timezone_callback()
    {
        $selected = $this->options['owmw_custom_timezone'] ?? "nobypass";

        echo '<select id="owmw_custom_timezone" name="owmw_option_name[owmw_custom_timezone]"> ';
        echo '<option ' . selected('nobypass', $selected, false) . ' value="nobypass">'. esc_html__('No bypass', 'owm-weather') .'</option>';
        echo '<option ' . selected('Default', $selected, false) . ' value="Default">'. esc_html__('WordPress timezone', 'owm-weather') .'</option>';
        echo '<option ' . selected('local', $selected, false) . ' value="local">'. esc_html__('Local timezone', 'owm-weather') .'</option>';
        echo '<option ' . selected('-12', $selected, false) . ' value="-12">'. esc_html__('UTC -12', 'owm-weather') .'</option>';
        echo '<option ' . selected('-11', $selected, false) . ' value="-11">'. esc_html__('UTC -11', 'owm-weather') .'</option>';
        echo '<option ' . selected('-10', $selected, false) . ' value="-10">'. esc_html__('UTC -10', 'owm-weather') .'</option>';
        echo '<option ' . selected('-9', $selected, false) . ' value="-9">'. esc_html__('UTC -9', 'owm-weather') .'</option>';
        echo '<option ' . selected('-8', $selected, false) . ' value="-8">'. esc_html__('UTC -8', 'owm-weather') .'</option>';
        echo '<option ' . selected('-7', $selected, false) . ' value="-7">'. esc_html__('UTC -7', 'owm-weather') .'</option>';
        echo '<option ' . selected('-6', $selected, false) . ' value="-6">'. esc_html__('UTC -6', 'owm-weather') .'</option>';
        echo '<option ' . selected('-5', $selected, false) . ' value="-5">'. esc_html__('UTC -5', 'owm-weather') .'</option>';
        echo '<option ' . selected('-4', $selected, false) . ' value="-4">'. esc_html__('UTC -4', 'owm-weather') .'</option>';
        echo '<option ' . selected('-3', $selected, false) . ' value="-3">'. esc_html__('UTC -3', 'owm-weather') .'</option>';
        echo '<option ' . selected('-2', $selected, false) . ' value="-2">'. esc_html__('UTC -2', 'owm-weather') .'</option>';
        echo '<option ' . selected('-1', $selected, false) . ' value="-1">'. esc_html__('UTC -1', 'owm-weather') .'</option>';
        echo '<option ' . selected('0', $selected, false) . ' value="0">'. esc_html__('UTC 0', 'owm-weather') .'</option>';
        echo '<option ' . selected('1', $selected, false) . ' value="1">'. esc_html__('UTC +1', 'owm-weather') .'</option>';
        echo '<option ' . selected('2', $selected, false) . ' value="2">'. esc_html__('UTC +2', 'owm-weather') .'</option>';
        echo '<option ' . selected('3', $selected, false) . ' value="3">'. esc_html__('UTC +3', 'owm-weather') .'</option>';
        echo '<option ' . selected('4', $selected, false) . ' value="4">'. esc_html__('UTC +4', 'owm-weather') .'</option>';
        echo '<option ' . selected('5', $selected, false) . ' value="5">'. esc_html__('UTC +5', 'owm-weather') .'</option>';
        echo '<option ' . selected('6', $selected, false) . ' value="6">'. esc_html__('UTC +6', 'owm-weather') .'</option>';
        echo '<option ' . selected('7', $selected, false) . ' value="7">'. esc_html__('UTC +7', 'owm-weather') .'</option>';
        echo '<option ' . selected('8', $selected, false) . ' value="8">'. esc_html__('UTC +8', 'owm-weather') .'</option>';
        echo '<option ' . selected('9', $selected, false) . ' value="9">'. esc_html__('UTC +9', 'owm-weather') .'</option>';
        echo '<option ' . selected('10', $selected, false) . ' value="10">'. esc_html__('UTC +10', 'owm-weather') .'</option>';
        echo '<option ' . selected('11', $selected, false) . ' value="11">'. esc_html__('UTC +11', 'owm-weather') .'</option>';
        echo '<option ' . selected('12', $selected, false) . ' value="12">'. esc_html__('UTC +12', 'owm-weather') .'</option>';
        echo '</select>';
    }

    public function owmw_basic_owm_language_callback()
    {
        $selected = $this->options['owmw_owm_language'] ?? "nobypass";

        echo '<p><i>' . esc_html_e('This is the language for the data from OpenWeatherMap, not for this plugin. If set to Default, it will try to use the WordPress site language first with fallback to English.', 'owm-weather') . '</i></p>';
        echo '<select id="owmw_owm_language" name="owmw_option_name[owmw_owm_language]"> ';
        echo '<option ' . ('nobypass' == $selected ? 'selected="selected"' : '') . ' value="nobypass">'. esc_html__('No bypass', 'owm-weather') .'</option>';
        echo '<option ' . selected('Default', $selected, false) . ' value="Default">'. esc_html__('Default', 'owm-weather') .'</option>';
        echo '<option ' . selected('af', $selected, false) . ' value="af">'. esc_html__('Afrikaans', 'owm-weather') .'</option>';
        echo '<option ' . selected('al', $selected, false) . ' value="al">'. esc_html__('Albanian', 'owm-weather') .'</option>';
        echo '<option ' . selected('ar', $selected, false) . ' value="ar">'. esc_html__('Arabic', 'owm-weather') .'</option>';
        echo '<option ' . selected('az', $selected, false) . ' value="az">'. esc_html__('Azerbaijani', 'owm-weather') .'</option>';
        echo '<option ' . selected('eu', $selected, false) . ' value="eu">'. esc_html__('Basque', 'owm-weather') .'</option>';
        echo '<option ' . selected('bg', $selected, false) . ' value="bg">'. esc_html__('Bulgarian', 'owm-weather') .'</option>';
        echo '<option ' . selected('ca', $selected, false) . ' value="ca">'. esc_html__('Catalan', 'owm-weather') .'</option>';
        echo '<option ' . selected('zh_cn', $selected, false) . ' value="zh_cn">'. esc_html__('Chinese Simplified', 'owm-weather') .'</option>';
        echo '<option ' . selected('zh_tw', $selected, false) . ' value="zh_tw">'. esc_html__('Chinese Traditional', 'owm-weather') .'</option>';
        echo '<option ' . selected('hr', $selected, false) . ' value="hr">'. esc_html__('Croatian', 'owm-weather') .'</option>';
        echo '<option ' . selected('cz', $selected, false) . ' value="cz">'. esc_html__('Czech', 'owm-weather') .'</option>';
        echo '<option ' . selected('da', $selected, false) . ' value="da">'. esc_html__('Danish', 'owm-weather') .'</option>';
        echo '<option ' . selected('nl', $selected, false) . ' value="nl">'. esc_html__('Dutch', 'owm-weather') .'</option>';
        echo '<option ' . selected('en', $selected, false) . ' value="en">'. esc_html__('English', 'owm-weather') .'</option>';
        echo '<option ' . selected('fi', $selected, false) . ' value="fi">'. esc_html__('Finnish', 'owm-weather') .'</option>';
        echo '<option ' . selected('fr', $selected, false) . ' value="fr">'. esc_html__('French', 'owm-weather') .'</option>';
        echo '<option ' . selected('gl', $selected, false) . ' value="gl">'. esc_html__('Galician', 'owm-weather') .'</option>';
        echo '<option ' . selected('de', $selected, false) . ' value="de">'. esc_html__('German', 'owm-weather') .'</option>';
        echo '<option ' . selected('el', $selected, false) . ' value="el">'. esc_html__('Greek', 'owm-weather') .'</option>';
        echo '<option ' . selected('he', $selected, false) . ' value="he">'. esc_html__('Hebrew', 'owm-weather') .'</option>';
        echo '<option ' . selected('hi', $selected, false) . ' value="hi">'. esc_html__('Hindi', 'owm-weather') .'</option>';
        echo '<option ' . selected('hu', $selected, false) . ' value="hu">'. esc_html__('Hungarian', 'owm-weather') .'</option>';
        echo '<option ' . selected('id', $selected, false) . ' value="id">'. esc_html__('Indonesian', 'owm-weather') .'</option>';
        echo '<option ' . selected('it', $selected, false) . ' value="it">'. esc_html__('Italian', 'owm-weather') .'</option>';
        echo '<option ' . selected('ja', $selected, false) . ' value="ja">'. esc_html__('Japanese', 'owm-weather') .'</option>';
        echo '<option ' . selected('kr', $selected, false) . ' value="kr">'. esc_html__('Korean', 'owm-weather') .'</option>';
        echo '<option ' . selected('la', $selected, false) . ' value="la">'. esc_html__('Latvian', 'owm-weather') .'</option>';
        echo '<option ' . selected('lt', $selected, false) . ' value="lt">'. esc_html__('Lithuanian', 'owm-weather') .'</option>';
        echo '<option ' . selected('mk', $selected, false) . ' value="mk">'. esc_html__('Macedonian', 'owm-weather') .'</option>';
        echo '<option ' . selected('no', $selected, false) . ' value="no">'. esc_html__('Norwegian', 'owm-weather') .'</option>';
        echo '<option ' . selected('fa', $selected, false) . ' value="fa">'. esc_html__('Persian (Farsi)', 'owm-weather') .'</option>';
        echo '<option ' . selected('pl', $selected, false) . ' value="pl">'. esc_html__('Polish', 'owm-weather') .'</option>';
        echo '<option ' . selected('pt', $selected, false) . ' value="pt">'. esc_html__('Portuguese', 'owm-weather') .'</option>';
        echo '<option ' . selected('pt', $selected, false) . ' value="pt">'. esc_html__('Português Brasil', 'owm-weather') .'</option>';
        echo '<option ' . selected('ro', $selected, false) . ' value="ro">'. esc_html__('Romanian', 'owm-weather') .'</option>';
        echo '<option ' . selected('ru', $selected, false) . ' value="ru">'. esc_html__('Russian', 'owm-weather') .'</option>';
        echo '<option ' . selected('sr', $selected, false) . ' value="sr">'. esc_html__('Serbian', 'owm-weather') .'</option>';
        echo '<option ' . selected('sv', $selected, false) . ' value="se">'. esc_html__('Swedish', 'owm-weather') .'</option>';
        echo '<option ' . selected('sk', $selected, false) . ' value="sk">'. esc_html__('Slovak', 'owm-weather') .'</option>';
        echo '<option ' . selected('sl', $selected, false) . ' value="sl">'. esc_html__('Slovenian', 'owm-weather') .'</option>';
        echo '<option ' . selected('sp', $selected, false) . ' value="es">'. esc_html__('Spanish', 'owm-weather') .'</option>';
        echo '<option ' . selected('th', $selected, false) . ' value="th">'. esc_html__('Thai', 'owm-weather') .'</option>';
        echo '<option ' . selected('tr', $selected, false) . ' value="tr">'. esc_html__('Turkish', 'owm-weather') .'</option>';
        echo '<option ' . selected('ua', $selected, false) . ' value="uk">'. esc_html__('Ukrainian', 'owm-weather') .'</option>';
        echo '<option ' . selected('vi', $selected, false) . ' value="vi">'. esc_html__('Vietnamese', 'owm-weather') .'</option>';
        echo '<option ' . selected('zu', $selected, false) . ' value="zu">'. esc_html__('Zulu', 'owm-weather') .'</option>';
        echo '</select>';
    }

    public function owmw_layout_font_callback()
    {
        $selected = $this->options['owmw_font'] ?? "nobypass";

        echo '<select id="owmw_font" name="owmw_option_name[owmw_font]"> ';
        echo '<option ' . selected('nobypass', $selected, false) . ' value="nobypass">'. esc_html__('No bypass', 'owm-weather') .'</option>';
        echo '<option ' . selected('Default', $selected, false) . ' value="Default">'. esc_html__('Default', 'owm-weather') .'</option>';
        echo '<option ' . selected('Arvo', $selected, false) . ' value="Arvo">'. esc_html__('Arvo', 'owm-weather') .'</option>';
        echo '<option ' . selected('Asap', $selected, false) . ' value="Asap">'. esc_html__('Asap', 'owm-weather') .'</option>';
        echo '<option ' . selected('Bitter', $selected, false) . ' value="Bitter">'. esc_html__('Bitter', 'owm-weather') .'</option>';
        echo '<option ' . selected('Droid Serif', $selected, false) . ' value="Droid Serif">'. esc_html__('Droid Serif', 'owm-weather') .'</option>';
        echo '<option ' . selected('Exo 2', $selected, false) . ' value="Exo 2">'. esc_html__('Exo 2', 'owm-weather') .'</option>';
        echo '<option ' . selected('Francois One', $selected, false) . ' value="Francois One">'. esc_html__('Francois One', 'owm-weather') .'</option>';
        echo '<option ' . selected('Inconsolata', $selected, false) . ' value="Inconsolata">'. esc_html__('Inconsolata', 'owm-weather') .'</option>';
        echo '<option ' . selected('Josefin Sans', $selected, false) . ' value="Josefin Sans">'. esc_html__('Josefin Sans', 'owm-weather') .'</option>';
        echo '<option ' . selected('Lato', $selected, false) . ' value="Lato">'. esc_html__('Lato', 'owm-weather') .'</option>';
        echo '<option ' . selected('Merriweather Sans', $selected, false) . ' value="Merriweather Sans">'. esc_html__('Merriweather Sans', 'owm-weather') .'</option>';
        echo '<option ' . selected('Nunito', $selected, false) . ' value="Nunito">'. esc_html__('Nunito', 'owm-weather') .'</option>';
        echo '<option ' . selected('Open Sans', $selected, false) . ' value="Open Sans">'. esc_html__('Open Sans', 'owm-weather') .'</option>';
        echo '<option ' . selected('Oswald', $selected, false) . ' value="Oswald">'. esc_html__('Oswald', 'owm-weather') .'</option>';
        echo '<option ' . selected('Pacifico', $selected, false) . ' value="Pacifico">'. esc_html__('Pacifico', 'owm-weather') .'</option>';
        echo '<option ' . selected('Roboto', $selected, false) . ' value="Roboto">'. esc_html__('Roboto', 'owm-weather') .'</option>';
        echo '<option ' . selected('Signika', $selected, false) . ' value="Signika">'. esc_html__('Signika', 'owm-weather') .'</option>';
        echo '<option ' . selected('Source Sans Pro', $selected, false) . ' value="Source Sans Pro">'. esc_html__('Source Sans Pro', 'owm-weather') .'</option>';
        echo '<option ' . selected('Tangerine', $selected, false) . ' value="Tangerine">'. esc_html__('Tangerine', 'owm-weather') .'</option>';
        echo '<option ' . selected('Ubuntu', $selected, false) . ' value="Ubuntu">'. esc_html__('Ubuntu', 'owm-weather') .'</option>';
        echo '</select>';
    }

    public function owmw_layout_template_callback()
    {
        $selected = $this->options['owmw_template'] ?? "nobypass";

        echo '<select id="owmw_template" name="owmw_option_name[owmw_template]"> ';
        echo '<option ' . selected('nobypass', $selected, false) . ' value="nobypass">'. esc_html__('No bypass', 'owm-weather') .'</option>';
        echo '<option ' . selected('Default', $selected, false) . ' value="Default">'. esc_html__('Default', 'owm-weather') .'</option>';
        echo '<option ' . selected('card1', $selected, false) . ' value="card1">'. esc_html__('Card 1', 'owm-weather') .'</option>';
        echo '<option ' . selected('card2', $selected, false) . ' value="card2">'. esc_html__('Card 2', 'owm-weather') .'</option>';
        echo '<option ' . selected('tabbed1', $selected, false) . ' value="tabbed1">'. esc_html__('Tabbed 1', 'owm-weather') .'</option>';
        echo '<option ' . selected('tabbed2', $selected, false) . ' value="tabbed2">'. esc_html__('Tabbed 2', 'owm-weather') .'</option>';
        echo '<option ' . selected('chart1', $selected, false) . ' value="chart1">'. esc_html__('Chart 1', 'owm-weather') .'</option>';
        echo '<option ' . selected('chart2', $selected, false) . ' value="chart2">'. esc_html__('Chart 2', 'owm-weather') .'</option>';
        echo '<option ' . selected('slider1', $selected, false) . ' value="slider1">'. esc_html__('Slider 1', 'owm-weather') .'</option>';
        echo '<option ' . selected('slider2', $selected, false) . ' value="slider2">'. esc_html__('Slider 2', 'owm-weather') .'</option>';
        echo '<option ' . selected('table1', $selected, false) . ' value="table1">'. esc_html__('Table 1', 'owm-weather') .'</option>';
        echo '<option ' . selected('table2', $selected, false) . ' value="table2">'. esc_html__('Table 2', 'owm-weather') .'</option>';
        echo '<option ' . selected('table3', $selected, false) . ' value="table3">'. esc_html__('Table 3', 'owm-weather') .'</option>';
        echo '<option ' . selected('custom1', $selected, false) . ' value="custom1">'. esc_html__('Custom 1', 'owm-weather') .'</option>';
        echo '<option ' . selected('custom2', $selected, false) . ' value="custom2">'. esc_html__('Custom 2', 'owm-weather') .'</option>';
        echo '<option ' . selected('debug', $selected, false) . ' value="debug">'. esc_html__('Debug', 'owm-weather') .'</option>';
        echo '</select>';
    }

    public function owmw_layout_iconpack_callback()
    {
        $selected = $this->options['owmw_iconpack'] ?? "nobypass";

        echo '<select id="owmw_iconpack" name="owmw_option_name[owmw_iconpack]"> ';
        echo '<option ' . selected('nobypass', $selected, false) . ' value="nobypass">'. esc_html__('No bypass', 'owm-weather') .'</option>';
        echo '<option ' . selected('Climacons', $selected, false) . ' value="Climacons">'. esc_html__('Climacons', 'owm-weather') .'</option>';
        echo '<option ' . selected('OpenWeatherMap', $selected, false) . ' value="OpenWeatherMap">'. esc_html__('Open Weather Map', 'owm-weather') .'</option>';
        echo '<option ' . selected('WeatherIcons', $selected, false) . ' value="WeatherIcons">'. esc_html__('Weather Icons', 'owm-weather') .'</option>';
        echo '<option ' . selected('Forecast', $selected, false) . ' value="Forecast">'. esc_html__('Forecast', 'owm-weather') .'</option>';
        echo '<option ' . selected('Dripicons', $selected, false) . ' value="Dripicons">'. esc_html__('Dripicons', 'owm-weather') .'</option>';
        echo '<option ' . selected('Pixeden', $selected, false) . ' value="Pixeden">'. esc_html__('Pixeden', 'owm-weather') .'</option>';
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
        if ('nobypass' == $check) {
            echo 'checked="checked"';
        }
        echo ' value="nobypass"/>';
        echo '<label for="owmw_today_date_format_nobypass">'. esc_html__('No bypass', 'owm-weather') .'</label>';

        echo '<br><br>';
        echo '<input id="owmw_today_date_format_none" name="owmw_option_name[owmw_today_date_format]" type="radio"';
        if ('none' == $check) {
            echo 'checked="checked"';
        }
        echo ' value="none"/>';
        echo '<label for="owmw_today_date_format_none">'. esc_html__('None', 'owm-weather') .'</label>';

        echo '<br><br>';

        echo '<input id="owmw_today_date_format_day" name="owmw_option_name[owmw_today_date_format]" type="radio"';
        if ('day' == $check) {
            echo 'checked="checked"';
        }
        echo ' value="day"/>';
        echo '<label for="owmw_today_date_format_day">'. esc_html__('Day of the week (eg: Sunday)', 'owm-weather') .'</label>';

        echo '<br><br>';

        echo '<input id="owmw_today_date_format_date" name="owmw_option_name[owmw_today_date_format]" type="radio"';
        if ('date' == $check) {
            echo 'checked="checked"';
        }
        echo ' value="date"/>';
        echo '<label for="owmw_today_date_format_date">'. esc_html__('Today\'s date', 'owm-weather') .'</label>';

        echo '<br><br>';

        echo '<input id="owmw_today_datetime_format_date" name="owmw_option_name[owmw_today_date_format]" type="radio"';
        if ('datetime' == $check) {
            echo 'checked="checked"';
        }
        echo ' value="datetime"/>';
        echo '<label for="owmw_today_date_format_date">'. esc_html__('Today\'s date and time', 'owm-weather') .'</label>';
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
        if ('nobypass' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="nobypass">'. esc_html__('No bypass', 'owm-weather') .'</option>';
        echo ' <option ';
        if ('mi/h' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="mi/h">'. esc_html__('mi/h', 'owm-weather') .'</option>';
        echo '<option ';
        if ('m/s' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="m/s">'. esc_html__('m/s', 'owm-weather') .'</option>';
        echo '<option ';
        if ('km/h' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="km/h">'. esc_html__('km/h', 'owm-weather') .'</option>';
        echo '<option ';
        if ('kt' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="kt">'. esc_html__('kt', 'owm-weather') .'</option>';
        echo '</select>';
    }

    public function owmw_display_wind_icon_direction_callback()
    {
        $selected = $this->options['owmw_wind_icon_direction'] ?? "nobypass";

        echo ' <select id="owmw_wind_icon_direction" name="owmw_option_name[owmw_wind_icon_direction]"> ';
        echo ' <option ';
        if ('nobypass' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="nobypass">'. esc_html__('No bypass', 'owm-weather') .'</option>';
        echo ' <option ';
        if ('to' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="to">'. esc_html__('direction of the wind', 'owm-weather') .'</option>';
        echo '<option ';
        if ('from' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="from">'. esc_html__('source of the wind flow', 'owm-weather') .'</option>';
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

    public function owmw_display_pressure_unit_callback()
    {
        $selected = $this->options['owmw_pressure_unit'] ?? "nobypass";

        echo ' <select id="owmw_pressure_unit" name="owmw_option_name[owmw_pressure_unit]"> ';
        echo ' <option ';
        if ('nobypass' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="nobypass">'. esc_html__('No bypass', 'owm-weather') .'</option>';
        echo ' <option ';
        if ('inHg' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="inHg">'. esc_html__('Inches of Mercury', 'owm-weather') .'</option>';
        echo '<option ';
        if ('mmHg' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="mmHg">'. esc_html__('Millimeter of Mercury', 'owm-weather') .'</option>';
        echo '<option ';
        if ('hPa' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="hPa">'. esc_html__('Hectopascal', 'owm-weather') .'</option>';
        echo '<option ';
        if ('mb' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="mb">'. esc_html__('Millibar', 'owm-weather') .'</option>';
        echo '</select>';
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

    public function owmw_display_text_labels_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_text_labels');
    }

    public function owmw_display_alerts_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_alerts');
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

    private function owmw_generate_hour_options($selected)
    {
           $str = ' <option ';
           $str .= selected("nobypass", $selected, false);
           $str .= ' value="nobypass">'. esc_html__("No bypass", 'owm-weather') .'</option>';
           $str .= ' <option ';
           $str .= selected("0", $selected, false);
           $str .= ' value="0">'. esc_html__("None", 'owm-weather') .'</option>';

        for ($i=1; $i<=48; $i++) {
            if ($i == 1) {
                $h = 'Now';
            } elseif ($i == 2) {
                $h = 'Now + 1 hour';
            } else {
                $h = 'Now + ' . ($i-1) . ' hours';
            }

            $str .= ' <option ';
            $str .= selected($i, intval($selected), false);
            $str .= ' value="' . $i . '">'. esc_html__($h, 'owm-weather') .'</option>';
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
        if ('nobypass' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="nobypass">'. esc_html__('No bypass', 'owm-weather') .'</option>';
        echo ' <option ';
        if ('0' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="0">'. esc_html__('None', 'owm-weather') .'</option>';
        echo ' <option ';
        if ('1' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="1">'. esc_html__('Today', 'owm-weather') .'</option>';
        echo '<option ';
        if ('2' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="2">'. esc_html__('Today + 1 day', 'owm-weather') .'</option>';
        echo '<option ';
        if ('3' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="3">'. esc_html__('Today + 2 days', 'owm-weather') .'</option>';
        echo '<option ';
        if ('4' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="4">'. esc_html__('Today + 3 days', 'owm-weather') .'</option>';
        echo '<option ';
        if ('5' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="5">'. esc_html__('Today + 4 days', 'owm-weather') .'</option>';
        echo '<option ';
        if ('6' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="6">'. esc_html__('Today + 5 days', 'owm-weather') .'</option>';
        echo '<option ';
        if ('7' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="7">'. esc_html__('Today + 6 days', 'owm-weather') .'</option>';
        echo '<option ';
        if ('8' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="8">'. esc_html__('Today + 7 days', 'owm-weather') .'</option>';
        echo '</select>';
    }

    public function owmw_display_alerts_popup_callback()
    {
           $check = $this->options['owmw_alerts_popup'] ?? "nobypass";

        echo '<input id="owmw_alerts_popup_nobypass" name="owmw_option_name[owmw_alerts_popup]" type="radio"';
        if ('nobypass' == $check) {
            echo 'checked="yes"';
        }
        echo ' value="nobypass"/>';
        echo '<label for="owmw_alerts_popup_nobypass">'. esc_html__('No bypass', 'owm-weather') .'</label>';

        echo '<br><br>';

        echo '<input id="owmw_alerts_popup_modal" name="owmw_option_name[owmw_alerts_popup]" type="radio"';
        if ('modal' == $check) {
            echo 'checked="yes"';
        }
        echo ' value="modal"/>';
        echo '<label for="owmw_alerts_popup_modal">'. esc_html__('Modal', 'owm-weather') .'</label>';

        echo '<br><br>';

        echo '<input id="owmw_alerts_popup_inline" name="owmw_option_name[owmw_alerts_popup]" type="radio"';
        if ('inline' == $check) {
            echo 'checked="yes"';
        }
        echo ' value="inline"/>';
        echo '<label for="owmw_alerts_popup_inline">'. esc_html__('Inline', 'owm-weather') .'</label>';
    }

    public function owmw_display_display_length_days_names_callback()
    {
           $check = $this->options['owmw_display_length_days_names'] ?? "nobypass";

        echo '<input id="owmw_display_length_days_names_nobypass" name="owmw_option_name[owmw_display_length_days_names]" type="radio"';
        if ('nobypass' == $check) {
            echo 'checked="yes"';
        }
        echo ' value="nobypass"/>';
        echo '<label for="owmw_display_length_days_names_nobypass">'. esc_html__('No bypass', 'owm-weather') .'</label>';

        echo '<br><br>';

        echo '<input id="owmw_display_length_days_names_short" name="owmw_option_name[owmw_display_length_days_names]" type="radio"';
        if ('short' == $check) {
            echo 'checked="yes"';
        }
        echo ' value="short"/>';
        echo '<label for="owmw_display_length_days_names_short">'. esc_html__('Short days names', 'owm-weather') .'</label>';

        echo '<br><br>';

        echo '<input id="owmw_display_length_days_names_normal" name="owmw_option_name[owmw_display_length_days_names]" type="radio"';
        if ('normal' == $check) {
            echo 'checked="yes"';
        }
        echo ' value="normal"/>';
        echo '<label for="owmw_display_length_days_names_normal">'. esc_html__('Normal days names', 'owm-weather') .'</label>';
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

    public function owmw_layout_background_image_callback()
    {
        echo '<div class="background_image_preview_wrapper">';
        echo '    <img id="background_image_preview" src="' . wp_get_attachment_url(($this->options['owmw_background_image'] ?? '' )) . '" height="100px"' . (isset($this->options['owmw_background_image']) ? '' : ' style="display: none;"') . '>';
        echo '<div class="background_image_preview_wrapper">';
        echo '<input id="select_background_image_button" type="button" class="button select_background_image_button" value="' . esc_attr__('Select image', 'owm-weather') . '" data-name="background" />';
        echo '<input type="hidden" name="owmw_option_name[owmw_background_image]" id="background_image_attachment_id" value="' . esc_attr($this->options['owmw_background_image'] ?? '') . '">';
        echo '<input id="clear_background_image_button" type="button" class="button clear_background_image" value="Clear" data-name="background" />';
        echo '</div>';
        echo '</div>';
    }

    public function owmw_weather_based_sunny_text_color_callback()
    {
        $check = $this->options['owmw_sunny_text_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_sunny_text_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    public function owmw_weather_based_sunny_background_color_callback()
    {
        $check = $this->options['owmw_sunny_background_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_sunny_background_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    function owmw_weather_based_sunny_background_image_callback()
    {
        echo '    <div class="background_image_preview_wrapper">';
        echo '        <img id="sunny_background_image_preview" src="' . wp_get_attachment_url(($this->options['owmw_sunny_background_image'] ?? '' )) . '" height="100px"' . (isset($this->options['owmw_sunny_background_image']) ? '' : ' style="display: none;"') . '>';
        echo '    </div>';
        echo '    <input id="select_sunny_background_image_button" type="button" class="button select_background_image_button" value="' . esc_attr__('Select image', 'owm-weather') . '" data-name="sunny_background" />';
        echo '    <input type="hidden" name="owmw_option_name[owmw_sunny_background_image]" id="sunny_background_image_attachment_id" value="' . esc_attr($this->options['owmw_sunny_background_image'] ?? '') . '">';
        echo '    <input id="clear_sunny_background_image_button" type="button" class="button clear_background_image" value="Clear" data-name="sunny_background" />';
    }
    
    public function owmw_weather_based_cloudy_text_color_callback()
    {
        $check = $this->options['owmw_cloudy_text_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_cloudy_text_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    public function owmw_weather_based_cloudy_background_color_callback()
    {
        $check = $this->options['owmw_cloudy_background_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_cloudy_background_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    function owmw_weather_based_cloudy_background_image_callback()
    {
        echo '    <div class="background_image_preview_wrapper">';
        echo '        <img id="cloudy_background_image_preview" src="' . wp_get_attachment_url(($this->options['owmw_cloudy_background_image'] ?? '' )) . '" height="100px"' . (isset($this->options['owmw_cloudy_background_image']) ? '' : ' style="display: none;"') . '>';
        echo '    </div>';
        echo '    <input id="select_cloudy_background_image_button" type="button" class="button select_background_image_button" value="' . esc_attr__('Select image', 'owm-weather') . '" data-name="cloudy_background" />';
        echo '    <input type="hidden" name="owmw_option_name[owmw_cloudy_background_image]" id="cloudy_background_image_attachment_id" value="' . esc_attr($this->options['owmw_cloudy_background_image'] ?? '') . '">';
        echo '    <input id="clear_cloudy_background_image_button" type="button" class="button clear_background_image" value="Clear" data-name="cloudy_background" />';
    }

    public function owmw_weather_based_drizzly_text_color_callback()
    {
        $check = $this->options['owmw_drizzly_text_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_drizzly_text_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    public function owmw_weather_based_drizzly_background_color_callback()
    {
        $check = $this->options['owmw_drizzly_background_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_drizzly_background_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    function owmw_weather_based_drizzly_background_image_callback()
    {
        echo '    <div class="background_image_preview_wrapper">';
        echo '        <img id="drizzly_background_image_preview" src="' . wp_get_attachment_url(($this->options['owmw_drizzly_background_image'] ?? '' )) . '" height="100px"' . (isset($this->options['owmw_drizzly_background_image']) ? '' : ' style="display: none;"') . '>';
        echo '    </div>';
        echo '    <input id="select_drizzly_background_image_button" type="button" class="button select_background_image_button" value="' . esc_attr__('Select image', 'owm-weather') . '" data-name="drizzly_background" />';
        echo '    <input type="hidden" name="owmw_option_name[owmw_drizzly_background_image]" id="drizzly_background_image_attachment_id" value="' . esc_attr($this->options['owmw_drizzly_background_image'] ?? '') . '">';
        echo '    <input id="clear_drizzly_background_image_button" type="button" class="button clear_background_image" value="Clear" data-name="drizzly_background" />';
    }

    public function owmw_weather_based_rainy_text_color_callback()
    {
        $check = $this->options['owmw_rainy_text_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_rainy_text_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    public function owmw_weather_based_rainy_background_color_callback()
    {
        $check = $this->options['owmw_rainy_background_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_rainy_background_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    function owmw_weather_based_rainy_background_image_callback()
    {
        echo '    <div class="background_image_preview_wrapper">';
        echo '        <img id="rainy_background_image_preview" src="' . wp_get_attachment_url(($this->options['owmw_rainy_background_image'] ?? '' )) . '" height="100px"' . (isset($this->options['owmw_rainy_background_image']) ? '' : ' style="display: none;"') . '>';
        echo '    </div>';
        echo '    <input id="select_rainy_background_image_button" type="button" class="button select_background_image_button" value="' . esc_attr__('Select image', 'owm-weather') . '" data-name="rainy_background" />';
        echo '    <input type="hidden" name="owmw_option_name[owmw_rainy_background_image]" id="rainy_background_image_attachment_id" value="' . esc_attr($this->options['owmw_rainy_background_image'] ?? '') . '">';
        echo '    <input id="clear_rainy_background_image_button" type="button" class="button clear_background_image" value="Clear" data-name="rainy_background" />';
    }

    public function owmw_weather_based_snowy_text_color_callback()
    {
        $check = $this->options['owmw_snowy_text_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_snowy_text_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    public function owmw_weather_based_snowy_background_color_callback()
    {
        $check = $this->options['owmw_snowy_background_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_snowy_background_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    function owmw_weather_based_snowy_background_image_callback()
    {
        echo '    <div class="background_image_preview_wrapper">';
        echo '        <img id="snowy_background_image_preview" src="' . wp_get_attachment_url(($this->options['owmw_snowy_background_image'] ?? '' )) . '" height="100px"' . (isset($this->options['owmw_snowy_background_image']) ? '' : ' style="display: none;"') . '>';
        echo '    </div>';
        echo '    <input id="select_snowy_background_image_button" type="button" class="button select_background_image_button" value="' . esc_attr__('Select image', 'owm-weather') . '" data-name="snowy_background" />';
        echo '    <input type="hidden" name="owmw_option_name[owmw_snowy_background_image]" id="snowy_background_image_attachment_id" value="' . esc_attr($this->options['owmw_snowy_background_image'] ?? '') . '">';
        echo '    <input id="clear_snowy_background_image_button" type="button" class="button clear_background_image" value="Clear" data-name="snowy_background" />';
    }

    public function owmw_weather_based_stormy_text_color_callback()
    {
        $check = $this->options['owmw_stormy_text_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_stormy_text_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    public function owmw_weather_based_stormy_background_color_callback()
    {
        $check = $this->options['owmw_stormy_background_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_stormy_background_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    function owmw_weather_based_stormy_background_image_callback()
    {
        echo '    <div class="background_image_preview_wrapper">';
        echo '        <img id="stormy_background_image_preview" src="' . wp_get_attachment_url(($this->options['owmw_stormy_background_image'] ?? '' )) . '" height="100px"' . (isset($this->options['owmw_stormy_background_image']) ? '' : ' style="display: none;"') . '>';
        echo '    </div>';
        echo '    <input id="select_stormy_background_image_button" type="button" class="button select_background_image_button" value="' . esc_attr__('Select image', 'owm-weather') . '" data-name="stormy_background" />';
        echo '    <input type="hidden" name="owmw_option_name[owmw_stormy_background_image]" id="stormy_background_image_attachment_id" value="' . esc_attr($this->options['owmw_stormy_background_image'] ?? '') . '">';
        echo '    <input id="clear_stormy_background_image_button" type="button" class="button clear_background_image" value="Clear" data-name="stormy_background" />';
    }

    public function owmw_weather_based_foggy_text_color_callback()
    {
        $check = $this->options['owmw_foggy_text_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_foggy_text_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    public function owmw_weather_based_foggy_background_color_callback()
    {
        $check = $this->options['owmw_foggy_background_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_foggy_background_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    function owmw_weather_based_foggy_background_image_callback()
    {
        echo '    <div class="background_image_preview_wrapper">';
        echo '        <img id="foggy_background_image_preview" src="' . wp_get_attachment_url(($this->options['owmw_foggy_background_image'] ?? '' )) . '" height="100px"' . (isset($this->options['owmw_foggy_background_image']) ? '' : ' style="display: none;"') . '>';
        echo '    </div>';
        echo '    <input id="select_foggy_background_image_button" type="button" class="button select_background_image_button" value="' . esc_attr__('Select image', 'owm-weather') . '" data-name="foggy_background" />';
        echo '    <input type="hidden" name="owmw_option_name[owmw_foggy_background_image]" id="foggy_background_image_attachment_id" value="' . esc_attr($this->options['owmw_foggy_background_image'] ?? '') . '">';
        echo '    <input id="clear_foggy_background_image_button" type="button" class="button clear_background_image" value="Clear" data-name="foggy_background" />';
    }

    public function owmw_layout_background_yt_video_callback()
    {
        echo '<div class="background_yt_video_preview_wrapper">';
        echo '	<input id="owmweather_background_yt_video_meta" type="text" name="owmw_option_name[owmw_background_yt_video]" value="' . esc_attr($this->options["owmw_background_yt_video"] ?? "") . '" />';
        echo '<div class="background_yt_video_preview_wrapper">';
        echo '<p>';
        echo '    <img id="owmweather_background_yt_video_tn" src="https://img.youtube.com/vi/' . esc_attr($this->options["owmw_background_yt_video"] ?? "") . '/default.jpg"' . (empty($this->options["owmw_background_yt_video"]) ? ' style="display: none;"' : "") . '>';
        echo '</p>';
        echo printYTvideoTN("background");
        echo '</div>';
        echo '</div>';
    }

    public function owmw_layout_sunny_background_yt_video_callback()
    {
        echo '<div class="sunny_background_yt_video_preview_wrapper">';
        echo '	<input id="owmweather_sunny_background_yt_video_meta" type="text" name="owmw_option_name[owmw_sunny_background_yt_video]" value="' . esc_attr($this->options["owmw_sunny_background_yt_video"] ?? "") . '" />';
        echo '<div class="sunny_background_yt_video_preview_wrapper">';
        echo '<p>';
        echo '    <img id="owmweather_sunny_background_yt_video_tn" src="https://img.youtube.com/vi/' . esc_attr($this->options["owmw_sunny_background_yt_video"] ?? "") . '/default.jpg"' . (empty($this->options["owmw_sunny_background_yt_video"]) ? ' style="display: none;"' : "") . '>';
        echo '</p>';
        echo printYTvideoTN("sunny_background");
        echo '</div>';
        echo '</div>';
    }

    public function owmw_layout_cloudy_background_yt_video_callback()
    {
        echo '<div class="cloudy_background_yt_video_preview_wrapper">';
        echo '	<input id="owmweather_cloudy_background_yt_video_meta" type="text" name="owmw_option_name[owmw_cloudy_background_yt_video]" value="' . esc_attr($this->options["owmw_cloudy_background_yt_video"] ?? "") . '" />';
        echo '<div class="cloudy_background_yt_video_preview_wrapper">';
        echo '<p>';
        echo '    <img id="owmweather_cloudy_background_yt_video_tn" src="https://img.youtube.com/vi/' . esc_attr($this->options["owmw_cloudy_background_yt_video"] ?? "") . '/default.jpg"' . (empty($this->options["owmw_cloudy_background_yt_video"]) ? ' style="display: none;"' : "") . '>';
        echo '</p>';
        echo printYTvideoTN("cloudy_background");
        echo '</div>';
        echo '</div>';
    }

    public function owmw_layout_drizzly_background_yt_video_callback()
    {
        echo '<div class="drizzly_background_yt_video_preview_wrapper">';
        echo '	<input id="owmweather_drizzly_background_yt_video_meta" type="text" name="owmw_option_name[owmw_drizzly_background_yt_video]" value="' . esc_attr($this->options["owmw_drizzly_background_yt_video"] ?? "") . '" />';
        echo '<div class="drizzly_background_yt_video_preview_wrapper">';
        echo '<p>';
        echo '    <img id="owmweather_drizzly_background_yt_video_tn" src="https://img.youtube.com/vi/' . esc_attr($this->options["owmw_drizzly_background_yt_video"] ?? "") . '/default.jpg"' . (empty($this->options["owmw_drizzly_background_yt_video"]) ? ' style="display: none;"' : "") . '>';
        echo '</p>';
        echo printYTvideoTN("drizzly_background");
        echo '</div>';
        echo '</div>';
    }

    public function owmw_layout_rainy_background_yt_video_callback()
    {
        echo '<div class="rainy_background_yt_video_preview_wrapper">';
        echo '	<input id="owmweather_rainy_background_yt_video_meta" type="text" name="owmw_option_name[owmw_rainy_background_yt_video]" value="' . esc_attr($this->options["owmw_rainy_background_yt_video"] ?? "") . '" />';
        echo '<div class="rainy_background_yt_video_preview_wrapper">';
        echo '<p>';
        echo '    <img id="owmweather_rainy_background_yt_video_tn" src="https://img.youtube.com/vi/' . esc_attr($this->options["owmw_rainy_background_yt_video"] ?? "") . '/default.jpg"' . (empty($this->options["owmw_rainy_background_yt_video"]) ? ' style="display: none;"' : "") . '>';
        echo '</p>';
        echo printYTvideoTN("rainy_background");
        echo '</div>';
        echo '</div>';
    }

    public function owmw_layout_snowy_background_yt_video_callback()
    {
        echo '<div class="snowy_background_yt_video_preview_wrapper">';
        echo '	<input id="owmweather_snowy_background_yt_video_meta" type="text" name="owmw_option_name[owmw_snowy_background_yt_video]" value="' . esc_attr($this->options["owmw_snowy_background_yt_video"] ?? "") . '" />';
        echo '<div class="snowy_background_yt_video_preview_wrapper">';
        echo '<p>';
        echo '    <img id="owmweather_snowy_background_yt_video_tn" src="https://img.youtube.com/vi/' . esc_attr($this->options["owmw_snowy_background_yt_video"] ?? "") . '/default.jpg"' . (empty($this->options["owmw_snowy_background_yt_video"]) ? ' style="display: none;"' : "") . '>';
        echo '</p>';
        echo printYTvideoTN("snowy_background");
        echo '</div>';
        echo '</div>';
    }

    public function owmw_layout_stormy_background_yt_video_callback()
    {
        echo '<div class="stormy_background_yt_video_preview_wrapper">';
        echo '	<input id="owmweather_stormy_background_yt_video_meta" type="text" name="owmw_option_name[owmw_stormy_background_yt_video]" value="' . esc_attr($this->options["owmw_stormy_background_yt_video"] ?? "") . '" />';
        echo '<div class="stormy_background_yt_video_preview_wrapper">';
        echo '<p>';
        echo '    <img id="owmweather_stormy_background_yt_video_tn" src="https://img.youtube.com/vi/' . esc_attr($this->options["owmw_stormy_background_yt_video"] ?? "") . '/default.jpg"' . (empty($this->options["owmw_stormy_background_yt_video"]) ? ' style="display: none;"' : "") . '>';
        echo '</p>';
        echo printYTvideoTN("stormy_background");
        echo '</div>';
        echo '</div>';
    }

    public function owmw_layout_foggy_background_yt_video_callback()
    {
        echo '<div class="foggy_background_yt_video_preview_wrapper">';
        echo '	<input id="owmweather_foggy_background_yt_video_meta" type="text" name="owmw_option_name[owmw_foggy_background_yt_video]" value="' . esc_attr($this->options["owmw_foggy_background_yt_video"] ?? "") . '" />';
        echo '<div class="foggy_background_yt_video_preview_wrapper">';
        echo '<p>';
        echo '    <img id="owmweather_foggy_background_yt_video_tn" src="https://img.youtube.com/vi/' . esc_attr($this->options["owmw_foggy_background_yt_video"] ?? "") . '/default.jpg"' . (empty($this->options["owmw_foggy_background_yt_video"]) ? ' style="display: none;"' : "") . '>';
        echo '</p>';
        echo printYTvideoTN("foggy_background");
        echo '</div>';
        echo '</div>';
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

    private function owmw_borderStyleOptions($selected)
    {
        echo '<option ' . selected('nobypass', $selected, false) . ' value="nobypass">'. esc_html__('No bypass', 'owm-weather') .'</option>';
        echo '<option ' . selected('solid', $selected, false) . ' value="solid">'. esc_html__('Solid', 'owm-weather') .'</option>';
        echo '<option ' . selected('dotted', $selected, false) . ' value="dotted">'. esc_html__('Dotted', 'owm-weather') .'</option>';
        echo '<option ' . selected('dashed', $selected, false) . ' value="dashed">'. esc_html__('Dashed', 'owm-weather') .'</option>';
        echo '<option ' . selected('double', $selected, false) . ' value="double">'. esc_html__('Double', 'owm-weather') .'</option>';
        echo '<option ' . selected('groove', $selected, false) . ' value="groove">'. esc_html__('Groove', 'owm-weather') .'</option>';
        echo '<option ' . selected('inset', $selected, false) . ' value="inset">'. esc_html__('Inset', 'owm-weather') .'</option>';
        echo '<option ' . selected('outset', $selected, false) . ' value="outset">'. esc_html__('Outset', 'owm-weather') .'</option>';
        echo '<option ' . selected('ridge', $selected, false) . ' value="ridge">'. esc_html__('Ridge', 'owm-weather') .'</option>';
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
        if ('nobypass' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="nobypass">'. esc_html__('No bypass', 'owm-weather') .'</option>';
        echo ' <option ';
        if ('small' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="small">'. esc_html__('Small', 'owm-weather') .'</option>';
        echo '<option ';
        if ('medium' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="medium">'. esc_html__('Medium', 'owm-weather') .'</option>';
        echo '<option ';
        if ('large' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="large">'. esc_html__('Large', 'owm-weather') .'</option>';
        echo '</select>';
    }


    public function owmw_layout_custom_css_callback()
    {
        $check = $this->options['owmw_custom_css'] ?? '';

        printf('<textarea name="owmw_option_name[owmw_custom_css]" style="width:100%%;height:300px;">%s</textarea>', esc_textarea($check));
    }

    public function owmw_layout_tabbed_btn_text_color_callback()
    {
        $check = $this->options['owmw_tabbed_btn_text_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_tabbed_btn_text_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    public function owmw_layout_tabbed_btn_background_color_callback()
    {
        $check = $this->options['owmw_tabbed_btn_background_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_tabbed_btn_background_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    public function owmw_layout_tabbed_btn_active_color_callback()
    {
        $check = $this->options['owmw_tabbed_btn_active_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_tabbed_btn_active_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    public function owmw_layout_tabbed_btn_hover_color_callback()
    {
        $check = $this->options['owmw_tabbed_btn_hover_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_tabbed_btn_hover_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
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

    public function owmw_layout_chart_height_callback()
    {
        $check = $this->options['owmw_chart_height'] ?? '';

        printf('<input name="owmw_option_name[owmw_chart_height]" type="number" min="300" value="%s" />', esc_attr($check));
    }

    public function owmw_layout_chart_text_color_callback()
    {
        $check = $this->options['owmw_chart_text_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_chart_text_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    public function owmw_layout_chart_night_color_callback()
    {
        $check = $this->options['owmw_chart_night_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_chart_night_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    public function owmw_layout_chart_background_color_callback()
    {
        $check = $this->options['owmw_chart_background_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_chart_background_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    public function owmw_layout_chart_border_color_callback()
    {
        $check = $this->options['owmw_chart_border_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_chart_border_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    public function owmw_layout_chart_border_width_callback()
    {
        $check = $this->options['owmw_chart_border_width'] ?? null;

        printf('<input name="owmw_option_name[owmw_chart_border_width]" type="number" min="0" value="%s" />', esc_attr($check));
    }

    public function owmw_layout_chart_border_style_callback()
    {
        $selected = $this->options['owmw_chart_border_style'] ?? "nobypass";

        echo '<select id="owmw_border_style" name="owmw_option_name[owmw_chart_border_style]">';
        $this->owmw_borderStyleOptions($selected);
        echo '</select>';
    }

    public function owmw_layout_chart_border_radius_callback()
    {
        $check = $this->options['owmw_chart_border_radius'] ?? null;

        printf('<input name="owmw_option_name[owmw_chart_border_radius]" type="number" min="0" value="%s" />', esc_attr($check));
    }

    public function owmw_layout_chart_temperature_color_callback()
    {
        $check = $this->options['owmw_chart_temperature_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_chart_temperature_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    public function owmw_layout_chart_feels_like_color_callback()
    {
        $check = $this->options['owmw_chart_feels_like_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_chart_feels_like_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    public function owmw_layout_chart_dew_point_color_callback()
    {
        $check = $this->options['owmw_chart_dew_point_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_chart_dew_point_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    public function owmw_layout_chart_cloudiness_color_callback()
    {
        $check = $this->options['owmw_chart_cloudiness_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_chart_cloudiness_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    public function owmw_layout_chart_rain_chance_color_callback()
    {
        $check = $this->options['owmw_chart_rain_chance_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_chart_rain_chance_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    public function owmw_layout_chart_humidity_color_callback()
    {
        $check = $this->options['owmw_chart_humidity_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_chart_humidity_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    public function owmw_layout_chart_pressure_color_callback()
    {
        $check = $this->options['owmw_chart_pressure_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_chart_pressure_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    public function owmw_layout_chart_rain_amt_color_callback()
    {
        $check = $this->options['owmw_chart_rain_amt_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_chart_rain_amt_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    public function owmw_layout_chart_snow_amt_color_callback()
    {
        $check = $this->options['owmw_chart_snow_amt_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_chart_snow_amt_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    public function owmw_layout_chart_wind_speed_color_callback()
    {
        $check = $this->options['owmw_chart_wind_speed_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_chart_wind_speed_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    public function owmw_layout_chart_wind_gust_color_callback()
    {
        $check = $this->options['owmw_chart_wind_gust_color'] ?? null;

        printf('<input name="owmw_option_name[owmw_chart_wind_gust_color]" type="text" value="%s" class="owmweather_admin_color_picker" />', esc_attr($check));
    }

    public function owmw_network_multisite_callback()
    {
        $check = $this->options['owmw_network_multisite'] ?? null;
        echo '<label class="toggle-switchy" for="owmw_network_multisite" data-size="sm" data-text="false" data-color="green">
              <input '.(!empty($check) ? ' checked="checked"' : '').' type="checkbox" id="owmw_network_multisite" name="owmw_option_name[owmw_network_multisite]" value="yes" />
              <span class="toggle">
                <span class="switch"></span>
              </span>
              <span class="label"><strong>'. esc_html__('(You need to configure your “OWM Weather” settings individually on every website in the network if this is turned off.)', 'owm-weather') .'</strong></span>
              </label>';
    }

    public function owmw_advanced_disable_cache_callback()
    {
        $check = $this->options['owmw_advanced_disable_cache'] ?? null;
        echo '<label class="toggle-switchy" for="owmw_advanced_disable_cache" data-size="sm" data-text="false" data-color="red">
              <input '.(!empty($check) ? ' checked="checked"' : '').' type="checkbox" id="owmw_advanced_disable_cache" name="owmw_option_name[owmw_advanced_disable_cache]" value="yes" />
              <span class="toggle">
                <span class="switch"></span>
              </span>
              <span class="label"><strong>'. esc_html__('(Not recommended!)', 'owm-weather') .'</strong></span>
              </label>';
    }

    public function owmw_advanced_cache_time_callback()
    {
        $check = $this->options['owmw_advanced_cache_time'] ?? '';

        printf('<input type="number" min="10" name="owmw_option_name[owmw_advanced_cache_time]" value="%s" style="width:100%%" />', esc_attr($check));
        echo '<br /><br /><span class="dashicons dashicons-editor-help"></span>'.esc_html__('Default value: 30 minutes', 'owm-weather');
    }

    public function owmw_advanced_api_key_callback()
    {
        $check = $this->options['owmw_advanced_api_key'] ?? '';

        printf('<input type="text" name="owmw_option_name[owmw_advanced_api_key]" value="%s" style="width:100%%" />', esc_attr($check));
        echo '<br /><br /><span class="dashicons dashicons-editor-help"></span>'.esc_html__('Strongly recommended: ', 'owm-weather').'<a href="https://openweathermap.org/appid" target="_blank">'.esc_html__('Get your free OWM API key here', 'owm-weather').'</a>';
    }

    public function owmw_advanced_disable_bootstrap_callback()
    {
        $check = $this->options['owmw_advanced_disable_modal_js'] ?? null;
        echo '<label class="toggle-switchy" for="owmw_advanced_disable_modal_js" data-size="sm" data-text="false" data-color="red">
              <input '.(!empty($check) ? ' checked="checked"' : '').' type="checkbox" id="owmw_advanced_disable_modal_js" name="owmw_option_name[owmw_advanced_disable_modal_js]" value="yes" />
              <span class="toggle">
                <span class="switch"></span>
              </span>
              <span class="label"><strong>'. esc_html__('(Check this if you already include Bootstrap in your theme)', 'owm-weather') .'</strong></span>
              </label>';
    }

    public function owmw_advanced_bootstrap_version_callback()
    {
        $check = $this->options['owmw_advanced_bootstrap_version'] ?? '4';

        echo '<input id="owmw_advanced_bootstrap_version4" name="owmw_option_name[owmw_advanced_bootstrap_version]" type="radio"';
        if ('4' == $check) {
            echo 'checked="yes"';
        }
        echo ' value="4"/>';
        echo '<label for="owmw_advanced_bootstrap_version4" style="margin-right: 30px;">'. esc_html__('4', 'owm-weather') .'</label>';

        echo '<input id="owmw_advanced_bootstrap_version5" name="owmw_option_name[owmw_advanced_bootstrap_version]" type="radio"';
        if ('5' == $check) {
            echo 'checked="yes"';
        }
        echo ' value="5"/>';
        echo '<label for="owmw_advanced_bootstrap_version5">'. esc_html__('5', 'owm-weather') . " " . esc_html__('(Ignored when Disable Bootstrap is unchecked)') .'</label>';
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
        if ('nobypass' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="nobypass">No bypass</option>';
        echo ' <option ';
        if ('0' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="0">0%</option>';
        echo '<option ';
        if ('0.1' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="0.1">10%</option>';
        echo '<option ';
        if ('0.2' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="0.2">20%</option>';
        echo '<option ';
        if ('0.3' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="0.3">30%</option>';
        echo '<option ';
        if ('0.4' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="0.4">40%</option>';
        echo '<option ';
        if ('0.5' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="0.5">50%</option>';
        echo '<option ';
        if ('0.6' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="0.6">60%</option>';
        echo '<option ';
        if ('0.7' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="0.7">70%</option>';
        echo '<option ';
        if ('0.8' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="0.8">80%</option>';
        echo '<option ';
        if ('0.9' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="0.9">90%</option>';
        echo '<option ';
        if ('1' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="1">100%</option>';
        echo '</select>';
    }

    public function owmw_map_zoom_callback()
    {
        $selected = $this->options['owmw_map_zoom'] ?? "nobypass";

        echo ' <select id="owmw_map_zoom" name="owmw_option_name[owmw_map_zoom]"> ';
        echo ' <option ';
        if ('nobypass' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="nobypass">No bypass</option>';
        echo ' <option ';
        if ('1' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="1">1</option>';
        echo ' <option ';
        if ('2' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="2">2</option>';
        echo ' <option ';
        if ('3' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="3">3</option>';
        echo ' <option ';
        if ('4' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="4">4</option>';
        echo ' <option ';
        if ('5' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="5">5</option>';
        echo ' <option ';
        if ('6' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="6">6</option>';
        echo ' <option ';
        if ('7' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="7">7</option>';
        echo ' <option ';
        if ('8' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="8">8</option>';
        echo ' <option ';
        if ('9' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="9">9</option>';
        echo ' <option ';
        if ('10' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="10">10</option>';
        echo ' <option ';
        if ('11' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="11">11</option>';
        echo ' <option ';
        if ('12' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="12">12</option>';
        echo ' <option ';
        if ('13' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="13">13</option>';
        echo ' <option ';
        if ('14' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="14">14</option>';
        echo ' <option ';
        if ('15' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="15">15</option>';
        echo ' <option ';
        if ('16' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="16">16</option>';
        echo ' <option ';
        if ('17' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="17">17</option>';
        echo ' <option ';
        if ('18' == $selected) {
            echo 'selected="selected"';
        }
        echo ' value="18">18</option>';
        echo '</select>';
    }

    public function owmw_map_disable_zoom_wheel_callback()
    {
        $this->owmw_bypassRadioButtonsDisable('owmw_map_disable_zoom_wheel');
    }

    public function owmw_map_layers_cities_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_cities');
    }

    public function owmw_map_layers_cities_legend_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_cities_legend');
    }

    public function owmw_map_layers_cities_on_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_cities_on');
    }

    public function owmw_map_layers_clouds_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_clouds');
    }

    public function owmw_map_layers_clouds_legend_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_clouds_legend');
    }

    public function owmw_map_layers_clouds_on_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_clouds_on');
    }

    public function owmw_map_layers_precipitation_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_precipitation');
    }

    public function owmw_map_layers_precipitation_legend_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_precipitation_legend');
    }

    public function owmw_map_layers_precipitation_on_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_precipitation_on');
    }

    public function owmw_map_layers_rain_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_rain');
    }

    public function owmw_map_layers_rain_legend_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_rain_legend');
    }

    public function owmw_map_layers_rain_on_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_rain_on');
    }

    public function owmw_map_layers_snow_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_snow');
    }

    public function owmw_map_layers_snow_legend_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_snow_legend');
    }

    public function owmw_map_layers_snow_on_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_snow_on');
    }

    public function owmw_map_layers_wind_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_wind');
    }

    public function owmw_map_layers_wind_legend_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_wind_legend');
    }

    public function owmw_map_layers_wind_on_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_wind_legend');
    }

    public function owmw_map_layers_temperature_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_temperature');
    }

    public function owmw_map_layers_temperature_legend_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_temperature_legend');
    }

    public function owmw_map_layers_temperature_on_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_temperature_on');
    }

    public function owmw_map_layers_pressure_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_pressure');
    }

    public function owmw_map_layers_pressure_legend_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_pressure_legend');
    }

    public function owmw_map_layers_pressure_on_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_pressure_on');
    }

    public function owmw_map_layers_windrose_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_windrose');
    }

    public function owmw_map_layers_windrose_legend_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_windrose_legend');
    }

    public function owmw_map_layers_windrose_on_callback()
    {
        $this->owmw_bypassRadioButtons('owmw_map_windrose_on');
    }

    public function owmw_support_info_callback()
    {
        echo
            '<h3>'. esc_html__("Get started with OWM Weather", 'owm-weather').'</h3>
            <p><a href="https://ujsoftware.com/owm-weather-blog/owm-weather-create-your-first-weather/" target="_blank" title="'. esc_attr__("Create your first weather", 'owm-weather').'">'. esc_html__("Create your first weather", 'owm-weather').'</a></p><br>
            <h3>'. esc_html__("Having a problem with OWM Weather?", 'owm-weather').'</h3>
            <p><a href="https://ujsoftware.com/owm-weather-blog/" target="_blank" title="'. esc_attr__("OWM Weather Blog", 'owm-weather').'">'. esc_html__("OWM Weather Blog", 'owm-weather').'</a></p><br>
            <p><a href="https://wordpress.org/plugins/owm-weather/" target="_blank" title="'. esc_attr__("OWM Weather Forum on WordPress.org", 'owm-weather').'">'. esc_html__("OWM Weather Forum on WordPress.org", 'owm-weather').'</a></p>';
    }

    private function owmw_bypassRadioButtons($option)
    {
        $value = $this->options[$option] ?? 'nobypass';

        echo '<input id="' . esc_attr($option) . '_nobypass" name="owmw_option_name[' . esc_attr($option) . ']" type="radio"';
        if ('nobypass' == $value) {
            echo 'checked="checked"';
        }
        echo ' value="nobypass"/>';
        echo '<label for="' . esc_attr($option) . '_nobypass">'. esc_html__('No bypass', 'owm-weather') .'</label>';

        echo '<br><br>';
            
        echo '<input id="' . esc_attr($option) . '_on" name="owmw_option_name[' . esc_attr($option) . ']" type="radio"';
        if ('yes' == $value) {
            echo 'checked="checked"';
        }
        echo ' value="yes"/>';
        echo '<label for="' . esc_attr($option) . '_on">'. esc_html__('Show on all weather', 'owm-weather') .'</label>';

        echo '<br><br>';

        echo '<input id="' . esc_attr($option) . '_off" name="owmw_option_name[' . esc_attr($option) . ']" type="radio"';
        if ('no' == $value) {
            echo 'checked="checked"';
        }
        echo ' value="no"/>';
        echo '<label for="' . esc_attr($option) . '_off">'. esc_html__('Suppress on all weather', 'owm-weather') .'</label>';
    }

    private function owmw_bypassRadioButtonsDisable($option)
    {
        $value = $this->options[$option] ?? 'nobypass';

        echo '<input id="' . esc_attr($option) . '_nobypass" name="owmw_option_name[' . esc_attr($option) . ']" type="radio"';
        if ('nobypass' == $value) {
            echo 'checked="checked"';
        }
        echo ' value="nobypass"/>';
        echo '<label for="' . esc_attr($option) . '_nobypass">'. esc_html__('No bypass', 'owm-weather') .'</label>';

        echo '<br><br>';
            
        echo '<input id="' . esc_attr($option) . '_on" name="owmw_option_name[' . esc_attr($option) . ']" type="radio"';
        if ('no' == $value) {
            echo 'checked="checked"';
        }
        echo ' value="no"/>';
        echo '<label for="' . esc_attr($option) . '_on">'. esc_html__('Show on all weather', 'owm-weather') .'</label>';

        echo '<br><br>';

        echo '<input id="' . esc_attr($option) . '_off" name="owmw_option_name[' . esc_attr($option) . ']" type="radio"';
        if ('yes' == $value) {
            echo 'checked="checked"';
        }
        echo ' value="yes"/>';
        echo '<label for="' . esc_attr($option) . '_off">'. esc_html__('Suppress on all weather', 'owm-weather') .'</label>';
    }
}

if (is_network_admin() || is_admin()) {
    $my_settings_page = new owmw_options();
}

function owmw_help_tab()
{
    global $owmw_help_tab;
    $screen = get_current_screen();

    $screen->add_help_tab(array(
        'id'    => 'owmw_help_tab',
        'title' => esc_html__('Setup OWM Weather', 'owm-weather'),
        'content'   => '<ol>
<li>' . esc_html__('Goto Settings / OWM Weather.').'</li>
<li>' . esc_html__('Enter your API key, if you have one. You may use the built-in key for testing until yours is activated.').'</li>
<li>' . esc_html__('Check "Disable Bootstrap" if you already include Bootstrap in your theme.').'</li>
<li>' . esc_html__('Leave all other settings as is for now and hit "Save changes".', 'owm-weather') . '</li>
</ol>',
    ));
    $screen->add_help_tab(array(
        'id'    => 'owmw_help_tab2',
        'title' => esc_html__('Create your first weather', 'owm-weather'),
        'content'   => '<ol>
<li>' . esc_html__('Click on the new custom post type called "Weather" and create a "New Weather".') . '</li>
<li>' . esc_html__('Fill one of the tabs under "Get weather by..." or leave empty for user\'s location.') . '</li>
<li>' . esc_html__('Choose "Measurement System" Imperial for Fahrenheit and miles, or "Metric" for Celsius and kilometers.') . '</li>
<li>' . esc_html__('Choose "12" or "24" hour time format.') . '</li>
<li>' . esc_html__('Under the "Display" tab, select the fields you would like to display.') . '</li>
<li>' . esc_html__('"Publish" your weather.') . '</li>
<li>' . esc_html__('Put the shortcode "[owm-weather id="###"/]" on a page or post, and look at the page.') . '</li>
<li>' . esc_html__('You just created your first weather! Now you can add additional fields under "Display", change the look-and-feel under "Layout", or add a map with layers under "Map".') . '</li>
<ol>',
    ));
}

function owmw_media_selector_print_scripts($id = null)
{
    if (!empty($id)) {
        $image_id = [];
        $image_id["background"]         = get_post_meta($id, '_owmweather_background_image', true);
        $image_id["sunny_background"]   = get_post_meta($id, '_owmweather_sunny_background_image', true);
        $image_id["cloudy_background"]  = get_post_meta($id, '_owmweather_cloudy_background_image', true);
        $image_id["drizzly_background"] = get_post_meta($id, '_owmweather_drizzly_background_image', true);
        $image_id["rainy_background"]   = get_post_meta($id, '_owmweather_rainy_background_image', true);
        $image_id["snowy_background"]   = get_post_meta($id, '_owmweather_snowy_background_image', true);
        $image_id["stormy_background"]  = get_post_meta($id, '_owmweather_stormy_background_image', true);
        $image_id["foggy_background"]   = get_post_meta($id, '_owmweather_foggy_background_image', true);
    } else {
        if (is_multisite() && is_network_admin()) {
            $options = get_site_option('owmw_option_name');
        } else {
            $options = get_option('owmw_option_name');
        }
        $image_id = [];
        $image_id["background"]         = $options['owmw_background_image'] ?? null;
        $image_id["sunny_background"]   = $options['owmw_sunny_background_image'] ?? null;
        $image_id["cloudy_background"]  = $options['owmw_cloudy_background_image'] ?? null;
        $image_id["drizzly_background"] = $options['owmw_drizzly_background_image'] ?? null;
        $image_id["rainy_background"]   = $options['owmw_rainy_background_image'] ?? null;
        $image_id["snowy_background"]   = $options['owmw_snowy_background_image'] ?? null;
        $image_id["stormy_background"]  = $options['owmw_stormy_background_image'] ?? null;
        $image_id["foggy_background"]   = $options['owmw_foggy_background_image'] ?? null;
    }

    foreach ($image_id as &$id) {
        $id = !empty($id) ? $id : 0;
    }

    ?><script type='text/javascript'>

        jQuery( document ).ready( function( $ ) {

            // Uploading files
            var image_frame;
            var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
            const set_to_post_id = { background:"<?php echo intval($image_id["background"]); ?>",
                                     sunny_background:"<?php echo intval($image_id["sunny_background"]); ?>",
                                     cloudy_background:"<?php echo intval($image_id["cloudy_background"]); ?>",
                                     drizzly_background:"<?php echo intval($image_id["drizzly_background"]); ?>",
                                     rainy_background:"<?php echo intval($image_id["rainy_background"]); ?>",
                                     snowy_background:"<?php echo intval($image_id["snowy_background"]); ?>",
                                     stormy_background:"<?php echo intval($image_id["stormy_background"]); ?>",
                                     foggy_sunny_background:"<?php echo intval($image_id["foggy_background"]); ?>"
                                    };

            $('.clear_background_image').on('click', function( event ){

                event.preventDefault();
                name = $( this ).data('name');

                $( '#'+name+'_image_attachment_id' ).val('');
                $( '#'+name+'_image_preview' ).attr('src', '');
                $( '#'+name+'_image_preview' ).hide();
            });

            $('.select_background_image_button').on('click', function( event ){

                event.preventDefault();
                name = $( this ).data('name');

                // If the media frame already exists, reopen it.
                if ( image_frame ) {
                    // Set the post ID to what we want
                    image_frame.uploader.uploader.param( 'post_id', set_to_post_id );
                    // Open frame
                    image_frame.open();
                    return;
                } else {
                    // Set the wp.media post id so the uploader grabs the ID we want when initialised
                    wp.media.model.settings.post.id = set_to_post_id[name];
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
                    const attachment = wp.media.attachment(set_to_post_id[name]);
                    attachment.fetch();
                    selection.add( attachment ? [ attachment ] : [] );
                });
            
                // When an image is selected, run a callback.
                image_frame.on( 'select', function() {
                    // We set multiple to false so only get one image from the uploader
                    attachment = image_frame.state().get('selection').first().toJSON();

                    // Do something with attachment.id and/or attachment.url here
                    $( '#'+name+'_image_preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
                    $( '#'+name+'_image_attachment_id' ).val( attachment.id );
                    $( '#'+name+'_image_preview' ).show();

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

/**
 * Class to log success.
 */
class Log_Success
{
    /**
     * Message to be displayed in a warning.
     *
     * @var string
     */
    private $message;
 
    /**
     * Initialize class.
     *
     * @param string $message Message to be displayed in a warning.
     */
    public function __construct(string $message)
    {
        $this->message = $message;
 
        add_action('network_admin_notices', array( $this, 'render' ));
    }
 
    /**
     * Displays warning on the admin screen.
     *
     * @return void
     */
    public function render()
    {
        printf('<div class="notice notice-success is-dismissible"><p>Success: %s</p></div>', esc_html($this->message));
    }
}

/**
 * Class to log warnings.
 */
class Log_Warning
{
    /**
     * Message to be displayed in a warning.
     *
     * @var string
     */
    private $message;
 
    /**
     * Initialize class.
     *
     * @param string $message Message to be displayed in a warning.
     */
    public function __construct(string $message)
    {
        $this->message = $message;
 
        add_action('network_admin_notices', array( $this, 'render' ));
    }
 
    /**
     * Displays warning on the admin screen.
     *
     * @return void
     */
    public function render()
    {
        printf('<div class="notice notice-warning is-dismissible"><p>Warning: %s</p></div>', esc_html($this->message));
    }
}

/**
 * Class to log errors.
 */
class Log_Error
{
    /**
     * Message to be displayed in a warning.
     *
     * @var string
     */
    private $message;
 
    /**
     * Initialize class.
     *
     * @param string $message Message to be displayed in a warning.
     */
    public function __construct(string $message)
    {
        $this->message = $message;
 
        add_action('network_admin_notices', array( $this, 'render' ));
    }
 
    /**
     * Displays warning on the admin screen.
     *
     * @return void
     */
    public function render()
    {
        printf('<div class="notice notice-error is-dismissible"><p>Error: %s</p></div>', esc_html($this->message));
    }
}
?>
