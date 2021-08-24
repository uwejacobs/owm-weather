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

echo wp_kses_post($owmw_html["container"]["start"]);
echo wp_kses($owmw_html["chart"]["hourly"]["container"], $owmw_opt['allowed_html']);
echo '<script type="text/javascript">' . wp_kses_post($owmw_html["chart"]["hourly"]["script"]) . '</script>';
echo wp_kses($owmw_html["chart"]["daily"]["container"], $owmw_opt['allowed_html']);
echo '<script type="text/javascript">' . wp_kses_post($owmw_html["chart"]["daily"]["script"]) . '</script>';
echo wp_kses_post($owmw_html["container"]["end"]);
echo '<style type="text/css">' . wp_kses_post($owmw_html["temperature_unit"]) . '</style>';
