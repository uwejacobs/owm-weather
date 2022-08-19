<?php
/****************************************************************************/
/* DO NOT CHANGE THIS TEMPLATE DIRECTLY. IT WILL BE OVERWRITTEN BY UPDATES. */
/****************************************************************************/
/**
 * The OWM Weather Table3 template
 * 5 days / 3 hours forecast
 */
?>
<!-- Start #owm-weather-container -->
<?php echo wp_kses($owmw_html["container"]["start"], $owmw_opt['allowed_html']); ?>

    <!-- 3-Hour Table -->
    <?php echo owmw_colorAnimatedConfig(); ?>
    <?php echo wp_kses($owmw_html["table"]["forecast"], $owmw_opt['allowed_html']); ?>

    <!-- OWM Link -->
    <?php echo wp_kses($owmw_html["owm_link_last_update_start"], $owmw_opt['allowed_html']); ?>
    <?php echo wp_kses($owmw_html["owm_link"], $owmw_opt['allowed_html']); ?>
    <!-- OWM Last Update -->
    <?php echo wp_kses($owmw_html["last_update"], $owmw_opt['allowed_html']); ?>
    <?php echo wp_kses($owmw_html["owm_link_last_update_end"], $owmw_opt['allowed_html']); ?>

    <!-- CSS/Scripts -->
    <?php echo '<style type="text/css">' . wp_kses($owmw_html["custom_css"], $owmw_opt['allowed_html']) . '</style>'; ?>
    <?php echo '<style type="text/css">' . wp_kses($owmw_html["temperature_unit"], $owmw_opt['allowed_html']) . '</style>'; ?>

    <!-- Google Tag Manager -->
    <?php echo '<script type="text/javascript">' . wp_kses($owmw_html["gtag"], $owmw_opt['allowed_html']) . '</script>'; ?>

<!-- End #owm-weather-container -->
<?php echo wp_kses($owmw_html["container"]["end"], $owmw_opt['allowed_html']); ?>
