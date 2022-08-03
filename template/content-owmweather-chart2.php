<?php
/****************************************************************************/
/* DO NOT CHANGE THIS TEMPLATE DIRECTLY. IT WILL BE OVERWRITTEN BY UPDATES. */
/****************************************************************************/
/**
 * The OWM Weather chart2 template
 *
 */
?>
<?php

echo wp_kses($owmw_html["container"]["start"], $owmw_opt['allowed_html']);
echo wp_kses($owmw_html["chart"]["hourly"]["temperatures"]["container"], $owmw_opt['allowed_html']);
echo '<script type="text/javascript">' . wp_kses($owmw_html["chart"]["hourly"]["temperatures"]["script"], $owmw_opt['allowed_html']) . '</script>';
echo wp_kses($owmw_html["chart"]["hourly"]["precipitation"]["container"], $owmw_opt['allowed_html']);
echo '<script type="text/javascript">' . wp_kses($owmw_html["chart"]["hourly"]["precipitation"]["script"], $owmw_opt['allowed_html']) . '</script>';
echo wp_kses($owmw_html["chart"]["hourly"]["precip_amt"]["container"], $owmw_opt['allowed_html']);
echo '<script type="text/javascript">' . wp_kses($owmw_html["chart"]["hourly"]["precip_amt"]["script"], $owmw_opt['allowed_html']) . '</script>';
echo wp_kses($owmw_html["chart"]["hourly"]["wind"]["container"], $owmw_opt['allowed_html']);
echo '<script type="text/javascript">' . wp_kses($owmw_html["chart"]["hourly"]["wind"]["script"], $owmw_opt['allowed_html']) . '</script>';
echo wp_kses($owmw_html["container"]["end"], $owmw_opt['allowed_html']);
echo '<style type="text/css">' . wp_kses($owmw_html["temperature_unit"], $owmw_opt['allowed_html']) . '</style>';
