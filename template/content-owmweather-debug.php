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
.accordion-button:not(.collapsed) {
    background: none;
    color: #ff9800;
    box-shadow: none;
    border-bottom: none;
}
.accordion-button::after {
    width: auto;
    height: auto;
    content: "+";
    font-size: 40px;
    background-image: none;
    font-weight: 100;
    color: #1b6ce5;
    transform: translateY(-4px);
}
.accordion-button:not(.collapsed)::after {
    width: auto;
    height: auto;
    background-image: none;
    content: "-";
    font-size: 48px;
    transform: translate(-5px, -4px);
    transform: rotate(0deg);
}
</style>
<?php if ($owmw_opt["bootstrap_version"] == '5') { ?>
<h2>OWM Weather Debug Output (Weather Id <?php echo esc_attr($owmw_opt["id"]); ?>)</h2>
<div class="accordion accordion-flush" id="debug_accordion<?php echo esc_attr($owmw_opt["id"]); ?>">
  <div class="accordion-item">
    <h3 class="accordion-header h2" id="headingSys<?php echo esc_attr($owmw_opt["id"]); ?>">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSys<?php echo esc_attr($owmw_opt["id"]); ?>" aria-expanded="false" aria-controls="collapseSys<?php echo esc_attr($owmw_opt["id"]); ?>">
        System Options
      </button>
    </h3>
    <div id="collapseSys<?php echo esc_attr($owmw_opt["id"]); ?>" class="accordion-collapse collapse" aria-labelledby="headingSys<?php echo esc_attr($owmw_opt["id"]); ?>" data-bs-parent="#debug_accordion<?php echo esc_attr($owmw_opt["id"]); ?>">
      <div class="accordion-body">
        <pre class="owmw-pre">$owmw_sys_opt[]:<br><?php print_r($owmw_sys_opt); ?></pre>
        <pre class="owmw-pre">$owmw_params[]:<br><?php print_r($owmw_params); ?></pre>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h3 class="accordion-heade h2" id="headingPage<?php echo esc_attr($owmw_opt["id"]); ?>">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePage<?php echo esc_attr($owmw_opt["id"]); ?>" aria-expanded="false" aria-controls="collapsePage<?php echo esc_attr($owmw_opt["id"]); ?>">
        Page Options
      </button>
    </h3>
    <div id="collapsePage<?php echo esc_attr($owmw_opt["id"]); ?>" class="accordion-collapse collapse" aria-labelledby="headingPage<?php echo esc_attr($owmw_opt["id"]); ?>" data-bs-parent="#debug_accordion<?php echo esc_attr($owmw_opt["id"]); ?>">
      <div class="accordion-body">
        <pre class="owmw-pre">$owmw_opt[]:<br><?php print_r($owmw_opt); ?></pre>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h3 class="accordion-heade h2" id="headingData<?php echo esc_attr($owmw_opt["id"]); ?>">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseData<?php echo esc_attr($owmw_opt["id"]); ?>" aria-expanded="false" aria-controls="collapseData<?php echo esc_attr($owmw_opt["id"]); ?>">
        Data
      </button>
    </h3>
    <div id="collapseData<?php echo esc_attr($owmw_opt["id"]); ?>" class="accordion-collapse collapse" aria-labelledby="headingData<?php echo esc_attr($owmw_opt["id"]); ?>" data-bs-parent="#debug_accordion<?php echo esc_attr($owmw_opt["id"]); ?>">
      <div class="accordion-body">
        <pre class="owmw-pre">$owmw_data[]:<br><?php print_r($owmw_data); ?></pre>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h3 class="accordion-heade h2" id="headingHtml<?php echo esc_attr($owmw_opt["id"]); ?>">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHtml<?php echo esc_attr($owmw_opt["id"]); ?>" aria-expanded="false" aria-controls="collapseHtml<?php echo esc_attr($owmw_opt["id"]); ?>">
        Html
      </button>
    </h3>
    <div id="collapseHtml<?php echo esc_attr($owmw_opt["id"]); ?>" class="accordion-collapse collapse" aria-labelledby="headingHtml<?php echo esc_attr($owmw_opt["id"]); ?>" data-bs-parent="#debug_accordion<?php echo esc_attr($owmw_opt["id"]); ?>">
      <div class="accordion-body">
        <pre class="owmw-pre">$owmw_html[]:<br><?php print_r($owmw_html); ?></pre>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h3 class="accordion-heade h2" id="headingRawHtml<?php echo esc_attr($owmw_opt["id"]); ?>">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRawHtml<?php echo esc_attr($owmw_opt["id"]); ?>" aria-expanded="false" aria-controls="collapseRawHtml<?php echo esc_attr($owmw_opt["id"]); ?>">
        RawHtml
      </button>
    </h3>
    <div id="collapseRawHtml<?php echo esc_attr($owmw_opt["id"]); ?>" class="accordion-collapse collapse" aria-labelledby="headingRawHtml<?php echo esc_attr($owmw_opt["id"]); ?>" data-bs-parent="#debug_accordion<?php echo esc_attr($owmw_opt["id"]); ?>">
      <div class="accordion-body">
        <?php array_walk_recursive($owmw_html, 'owmw_htmlfix'); ?>
        <pre class="owmw-pre">$owmw_html[]:<br><?php print_r($owmw_html); ?></pre>
      </div>
    </div>
  </div>
</div>
<?php } else { ?>
<div class="accordion" id="debug_accordion<?php echo esc_attr($owmw_opt["id"]); ?>">
  <div class="card">
    <div class="card-header" id="headingSys<?php echo esc_attr($owmw_opt["id"]); ?>">
      <h3 class="mb-0">
    <button class="btn btn-link btn-block text-left" type="button" data-<?php echo $owmw_opt["bootstrap_data"] ?>toggle="collapse" data-<?php echo $owmw_opt["bootstrap_data"] ?>target="#collapseSys<?php echo esc_attr($owmw_opt["id"]); ?>" aria-expanded="false" aria-controls="collapseSys<?php echo esc_attr($owmw_opt["id"]); ?>">
          System Options
        </button>
      </h3>
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
      <h3 class="mb-0">
    <button class="btn btn-link btn-block text-left" type="button" data-<?php echo $owmw_opt["bootstrap_data"] ?>toggle="collapse" data-<?php echo $owmw_opt["bootstrap_data"] ?>target="#collapsePage<?php echo esc_attr($owmw_opt["id"]); ?>" aria-expanded="false" aria-controls="collapsePage<?php echo esc_attr($owmw_opt["id"]); ?>">
          Page Options
        </button>
      </h3>
    </div>

    <div id="collapsePage<?php echo esc_attr($owmw_opt["id"]); ?>" class="collapse" aria-labelledby="headingPage<?php echo esc_attr($owmw_opt["id"]); ?>" data-<?php echo $owmw_opt["bootstrap_data"] ?>parent="#debug_accordion<?php echo esc_attr($owmw_opt["id"]); ?>">
      <div class="card-body">
        <pre class="owmw-pre">$owmw_opt[]:<br><?php print_r($owmw_opt); ?></pre>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingData<?php echo esc_attr($owmw_opt["id"]); ?>">
      <h3 class="mb-0">
    <button class="btn btn-link btn-block text-left" type="button" data-<?php echo $owmw_opt["bootstrap_data"] ?>toggle="collapse" data-<?php echo $owmw_opt["bootstrap_data"] ?>target="#collapseData<?php echo esc_attr($owmw_opt["id"]); ?>" aria-expanded="false" aria-controls="collapseData<?php echo esc_attr($owmw_opt["id"]); ?>">
          Data
        </button>
      </h3>
    </div>

    <div id="collapseData<?php echo esc_attr($owmw_opt["id"]); ?>" class="collapse" aria-labelledby="headingData<?php echo esc_attr($owmw_opt["id"]); ?>" data-<?php echo $owmw_opt["bootstrap_data"] ?>parent="#debug_accordion<?php echo esc_attr($owmw_opt["id"]); ?>">
      <div class="card-body">
        <pre class="owmw-pre">$owmw_data[]:<br><?php print_r($owmw_data); ?></pre>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingHtml<?php echo esc_attr($owmw_opt["id"]); ?>">
      <h3 class="mb-0">
    <button class="btn btn-link btn-block text-left" type="button" data-<?php echo $owmw_opt["bootstrap_data"] ?>toggle="collapse" data-<?php echo $owmw_opt["bootstrap_data"] ?>target="#collapseHtml<?php echo esc_attr($owmw_opt["id"]); ?>" aria-expanded="false" aria-controls="collapseHtml<?php echo esc_attr($owmw_opt["id"]); ?>">
          HTML
        </button>
      </h3>
    </div>

    <div id="collapseHtml<?php echo esc_attr($owmw_opt["id"]); ?>" class="collapse" aria-labelledby="headingHtml<?php echo esc_attr($owmw_opt["id"]); ?>" data-<?php echo $owmw_opt["bootstrap_data"] ?>parent="#debug_accordion<?php echo esc_attr($owmw_opt["id"]); ?>">
      <div class="card-body">
        <pre class="owmw-pre">$owmw_html[]:<br><?php print_r($owmw_html); ?></pre>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingRawHtml<?php echo esc_attr($owmw_opt["id"]); ?>">
      <h3 class="mb-0">
    <button class="btn btn-link btn-block text-left" type="button" data-<?php echo $owmw_opt["bootstrap_data"] ?>toggle="collapse" data-<?php echo $owmw_opt["bootstrap_data"] ?>target="#collapseRawHtml<?php echo esc_attr($owmw_opt["id"]); ?>" aria-expanded="false" aria-controls="collapseRawHtml<?php echo esc_attr($owmw_opt["id"]); ?>">
          Raw HTML
        </button>
      </h3>
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
