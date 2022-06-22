<?php
/****************************************************************************/
/* DO NOT CHANGE THIS TEMPLATE DIRECTLY. IT WILL BE OVERWRITTEN BY UPDATES. */
/****************************************************************************/
/**
 * The OWM Weather debug template
 *
 */
function owmw_htmlfix(&$item, $key)
{
    $item = trim(htmlentities($item));
}
?>
<style>
#debug_accordion<?php echo esc_attr($owmw_opt["id"]); ?> .card-body,
#debug_accordion<?php echo esc_attr($owmw_opt["id"]); ?> .accordion-body {
  background-color: #fff;
  color: #000;
}
#debug_accordion<?php echo esc_attr($owmw_opt["id"]); ?> svg {
  max-width: 100px;
  max-height: 100px;
}
</style>
<?php if ($owmw_opt["bootstrap_version"] == '5') { ?>
<div class="accordion accordion-flush" id="debug_accordion<?php echo esc_attr($owmw_opt["id"]); ?>">
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingSys<?php echo esc_attr($owmw_opt["id"]); ?>">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSys<?php echo esc_attr($owmw_opt["id"]); ?>" aria-expanded="false" aria-controls="collapseSys<?php echo esc_attr($owmw_opt["id"]); ?>">
        System Options
      </button>
    </h2>
    <div id="collapseSys<?php echo esc_attr($owmw_opt["id"]); ?>" class="accordion-collapse collapse" aria-labelledby="headingSys<?php echo esc_attr($owmw_opt["id"]); ?>" data-bs-parent="#debug_accordion<?php echo esc_attr($owmw_opt["id"]); ?>">
      <div class="accordion-body">
        <pre class="owmw-pre">$owmw_sys_opt[]:<br><?php print_r($owmw_sys_opt); ?></pre>
        <pre class="owmw-pre">$owmw_params[]:<br><?php print_r($owmw_params); ?></pre>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingPage<?php echo esc_attr($owmw_opt["id"]); ?>">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePage<?php echo esc_attr($owmw_opt["id"]); ?>" aria-expanded="false" aria-controls="collapsePage<?php echo esc_attr($owmw_opt["id"]); ?>">
        Page Options
      </button>
    </h2>
    <div id="collapsePage<?php echo esc_attr($owmw_opt["id"]); ?>" class="accordion-collapse collapse" aria-labelledby="headingPage<?php echo esc_attr($owmw_opt["id"]); ?>" data-bs-parent="#debug_accordion<?php echo esc_attr($owmw_opt["id"]); ?>">
      <div class="accordion-body">
        <pre class="owmw-pre">$owmw_opt[]:<br><?php print_r($owmw_opt); ?></pre>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingData<?php echo esc_attr($owmw_opt["id"]); ?>">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseData<?php echo esc_attr($owmw_opt["id"]); ?>" aria-expanded="false" aria-controls="collapseData<?php echo esc_attr($owmw_opt["id"]); ?>">
        Data
      </button>
    </h2>
    <div id="collapseData<?php echo esc_attr($owmw_opt["id"]); ?>" class="accordion-collapse collapse" aria-labelledby="headingData<?php echo esc_attr($owmw_opt["id"]); ?>" data-bs-parent="#debug_accordion<?php echo esc_attr($owmw_opt["id"]); ?>">
      <div class="accordion-body">
        <pre class="owmw-pre">$owmw_data[]:<br><?php print_r($owmw_data); ?></pre>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingHtml<?php echo esc_attr($owmw_opt["id"]); ?>">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHtml<?php echo esc_attr($owmw_opt["id"]); ?>" aria-expanded="false" aria-controls="collapseHtml<?php echo esc_attr($owmw_opt["id"]); ?>">
        Html
      </button>
    </h2>
    <div id="collapseHtml<?php echo esc_attr($owmw_opt["id"]); ?>" class="accordion-collapse collapse" aria-labelledby="headingHtml<?php echo esc_attr($owmw_opt["id"]); ?>" data-bs-parent="#debug_accordion<?php echo esc_attr($owmw_opt["id"]); ?>">
      <div class="accordion-body">
        <pre class="owmw-pre">$owmw_html[]:<br><?php print_r($owmw_html); ?></pre>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header" id="headingRawHtml<?php echo esc_attr($owmw_opt["id"]); ?>">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRawHtml<?php echo esc_attr($owmw_opt["id"]); ?>" aria-expanded="false" aria-controls="collapseRawHtml<?php echo esc_attr($owmw_opt["id"]); ?>">
        RawHtml
      </button>
    </h2>
    <div id="collapseRawHtml<?php echo esc_attr($owmw_opt["id"]); ?>" class="accordion-collapse collapse" aria-labelledby="headingRawHtml<?php echo esc_attr($owmw_opt["id"]); ?>" data-bs-parent="#debug_accordion<?php echo esc_attr($owmw_opt["id"]); ?>">
      <div class="accordion-body">
        <?php array_walk_recursive($owmw_html, 'owmw_htmlfix'); ?>
        <pre class="owmw-pre">$owmw_html[]:<br><?php print_r($owmw_html); ?></pre>
      </div>
    </div>
  </div>
</div>
<?php } else { ?>
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
array_walk_recursive($owmw_html, 'owmw_htmlfix');
?>
        <pre class="owmw-pre">$owmw_html[]:<br><?php print_r($owmw_html); ?></pre>
      </div>
    </div>
  </div>
</div>
<?php } ?>
