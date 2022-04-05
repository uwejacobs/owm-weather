<?php
/****************************************************************************/
/* DO NOT CHANGE THIS TEMPLATE DIRECTLY. IT WILL BE OVERWRITTEN BY UPDATES. */
/****************************************************************************/
/**
 * The OWM Weather debug template
 *
 */
?>
<style>
#debug_accordion<?php echo esc_attr($owmw_opt["id"]); ?> .card-body {
  background-color: #fff;
  color: #000;
}
</style>
<h2>OWM Weather Debug Output (Weather Id <?php echo esc_attr($owmw_opt["id"]); ?>)</h2>
<div class="accordion" id="debug_accordion<?php echo esc_attr($owmw_opt["id"]); ?>">
  <div class="card">
    <div class="card-header" id="headingSys<?php echo esc_attr($owmw_opt["id"]); ?>">
      <h2 class="mb-0">
	<button class="btn btn-link btn-block text-left" type="button" data-<?php echo $owmw_opt["bootstrap_data"] ?>toggle="collapse" data-<?php echo $owmw_opt["bootstrap_data"] ?>target="#collapseSys<?php echo esc_attr($owmw_opt["id"]); ?>" aria-expanded="false" aria-controls="collapseSys<?php echo esc_attr($owmw_opt["id"]); ?>">
          System Options
        </button>
      </h2>
    </div>

    <div id="collapseSys<?php echo esc_attr($owmw_opt["id"]); ?>" class="collapse" aria-labelledby="headingSys<?php echo esc_attr($owmw_opt["id"]); ?>" data-<?php echo $owmw_opt["bootstrap_data"] ?>parent="#debug_accordion<?php echo esc_attr($owmw_opt["id"]); ?>">
      <div class="card-body">
        <pre class="owmw-pre">$owmw_sys_opt[]:<br><?php print_r($owmw_sys_opt); ?></pre>
        <pre class="owmw-pre">$owmw_params[]:<br><?php print_r($owmw_params); ?></pre>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingPage<?php echo esc_attr($owmw_opt["id"]); ?>">
      <h2 class="mb-0">
	<button class="btn btn-link btn-block text-left" type="button" data-<?php echo $owmw_opt["bootstrap_data"] ?>toggle="collapse" data-<?php echo $owmw_opt["bootstrap_data"] ?>target="#collapsePage<?php echo esc_attr($owmw_opt["id"]); ?>" aria-expanded="false" aria-controls="collapsePage<?php echo esc_attr($owmw_opt["id"]); ?>">
          Page Options
        </button>
      </h2>
    </div>

    <div id="collapsePage<?php echo esc_attr($owmw_opt["id"]); ?>" class="collapse" aria-labelledby="headingPage<?php echo esc_attr($owmw_opt["id"]); ?>" data-<?php echo $owmw_opt["bootstrap_data"] ?>parent="#debug_accordion<?php echo esc_attr($owmw_opt["id"]); ?>">
      <div class="card-body">
        <pre class="owmw-pre">$owmw_opt[]:<br><?php print_r($owmw_opt); ?></pre>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingData<?php echo esc_attr($owmw_opt["id"]); ?>">
      <h2 class="mb-0">
	<button class="btn btn-link btn-block text-left" type="button" data-<?php echo $owmw_opt["bootstrap_data"] ?>toggle="collapse" data-<?php echo $owmw_opt["bootstrap_data"] ?>target="#collapseData<?php echo esc_attr($owmw_opt["id"]); ?>" aria-expanded="false" aria-controls="collapseData<?php echo esc_attr($owmw_opt["id"]); ?>">
          Data
        </button>
      </h2>
    </div>

    <div id="collapseData<?php echo esc_attr($owmw_opt["id"]); ?>" class="collapse" aria-labelledby="headingData<?php echo esc_attr($owmw_opt["id"]); ?>" data-<?php echo $owmw_opt["bootstrap_data"] ?>parent="#debug_accordion<?php echo esc_attr($owmw_opt["id"]); ?>">
      <div class="card-body">
        <pre class="owmw-pre">$owmw_data[]:<br><?php print_r($owmw_data); ?></pre>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingHtml<?php echo esc_attr($owmw_opt["id"]); ?>">
      <h2 class="mb-0">
	<button class="btn btn-link btn-block text-left" type="button" data-<?php echo $owmw_opt["bootstrap_data"] ?>toggle="collapse" data-<?php echo $owmw_opt["bootstrap_data"] ?>target="#collapseHtml<?php echo esc_attr($owmw_opt["id"]); ?>" aria-expanded="false" aria-controls="collapseHtml<?php echo esc_attr($owmw_opt["id"]); ?>">
          HTML
        </button>
      </h2>
    </div>

    <div id="collapseHtml<?php echo esc_attr($owmw_opt["id"]); ?>" class="collapse" aria-labelledby="headingHtml<?php echo esc_attr($owmw_opt["id"]); ?>" data-<?php echo $owmw_opt["bootstrap_data"] ?>parent="#debug_accordion<?php echo esc_attr($owmw_opt["id"]); ?>">
      <div class="card-body">
        <pre class="owmw-pre">$owmw_html[]:<br><?php print_r($owmw_html); ?></pre>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingRawHtml<?php echo esc_attr($owmw_opt["id"]); ?>">
      <h2 class="mb-0">
	<button class="btn btn-link btn-block text-left" type="button" data-<?php echo $owmw_opt["bootstrap_data"] ?>toggle="collapse" data-<?php echo $owmw_opt["bootstrap_data"] ?>target="#collapseRawHtml<?php echo esc_attr($owmw_opt["id"]); ?>" aria-expanded="false" aria-controls="collapseRawHtml<?php echo esc_attr($owmw_opt["id"]); ?>">
          Raw HTML
        </button>
      </h2>
    </div>

    <div id="collapseRawHtml<?php echo esc_attr($owmw_opt["id"]); ?>" class="collapse" aria-labelledby="headingRawHtml<?php echo esc_attr($owmw_opt["id"]); ?>" data-<?php echo $owmw_opt["bootstrap_data"] ?>parent="#debug_accordion<?php echo esc_attr($owmw_opt["id"]); ?>">
      <div class="card-body">
<?php
function owmw_htmlfix(&$item, $key)
{
    $item = trim(htmlentities($item));
}

array_walk_recursive($owmw_html, 'owmw_htmlfix');
?>
        <pre class="owmw-pre">$owmw_html[]:<br><?php print_r($owmw_html); ?></pre>
      </div>
    </div>
  </div>
</div>
