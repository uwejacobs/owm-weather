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
#debug_accordion<?php echo $owmw_opt["id"]; ?> .card-body {
  background-color: #fff;
  color: #000;
}
</style>
<h2>OWM Weather Debug Output (Weather Id <?php echo $owmw_opt["id"]; ?>)</h2>
<div class="accordion" id="debug_accordion<?php echo $owmw_opt["id"]; ?>">
  <div class="card">
    <div class="card-header" id="headingSys<?php echo $owmw_opt["id"]; ?>">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSys<?php echo $owmw_opt["id"]; ?>" aria-expanded="false" aria-controls="collapseSys">
          System Options
        </button>
      </h2>
    </div>

    <div id="collapseSys<?php echo $owmw_opt["id"]; ?>" class="collapse" aria-labelledby="headingSys<?php echo $owmw_opt["id"]; ?>" data-parent="#debug_accordion<?php echo $owmw_opt["id"]; ?>">
      <div class="card-body">
        <pre class="owmw-pre">$owmw_sys_opt[]:<br><?php print_r($owmw_sys_opt); ?></pre>
        <pre class="owmw-pre">$owmw_params[]:<br><?php print_r($owmw_params); ?></pre>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingPage<?php echo $owmw_opt["id"]; ?>">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapsePage<?php echo $owmw_opt["id"]; ?>" aria-expanded="false" aria-controls="collapsePage">
          Page Options
        </button>
      </h2>
    </div>

    <div id="collapsePage<?php echo $owmw_opt["id"]; ?>" class="collapse" aria-labelledby="headingPage<?php echo $owmw_opt["id"]; ?>" data-parent="#debug_accordion<?php echo $owmw_opt["id"]; ?>">
      <div class="card-body">
        <pre class="owmw-pre">$owmw_opt[]:<br><?php print_r($owmw_opt); ?></pre>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingData<?php echo $owmw_opt["id"]; ?>">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseData<?php echo $owmw_opt["id"]; ?>" aria-expanded="false" aria-controls="collapseData">
          Data
        </button>
      </h2>
    </div>

    <div id="collapseData<?php echo $owmw_opt["id"]; ?>" class="collapse" aria-labelledby="headingData<?php echo $owmw_opt["id"]; ?>" data-parent="#debug_accordion<?php echo $owmw_opt["id"]; ?>">
      <div class="card-body">
        <pre class="owmw-pre">$owmw_data[]:<br><?php print_r($owmw_data); ?></pre>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingHtml<?php echo $owmw_opt["id"]; ?>">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseHtml<?php echo $owmw_opt["id"]; ?>" aria-expanded="false" aria-controls="collapseHtml">
          HTML
        </button>
      </h2>
    </div>

    <div id="collapseHtml<?php echo $owmw_opt["id"]; ?>" class="collapse" aria-labelledby="headingHtml<?php echo $owmw_opt["id"]; ?>" data-parent="#debug_accordion<?php echo $owmw_opt["id"]; ?>">
      <div class="card-body">
        <pre class="owmw-pre">$owmw_html[]:<br><?php print_r($owmw_html); ?></pre>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingRawHtml<?php echo $owmw_opt["id"]; ?>">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseRawHtml<?php echo $owmw_opt["id"]; ?>" aria-expanded="false" aria-controls="collapseRawHtml">
          Raw HTML
        </button>
      </h2>
    </div>

    <div id="collapseRawHtml<?php echo $owmw_opt["id"]; ?>" class="collapse" aria-labelledby="headingRawHtml<?php echo $owmw_opt["id"]; ?>" data-parent="#debug_accordion<?php echo $owmw_opt["id"]; ?>">
      <div class="card-body">
<?php
function htmlfix(&$item, $key)
{
    $item = trim(htmlentities($item));
}

array_walk_recursive($owmw_html, 'htmlfix');
?>
        <pre class="owmw-pre">$owmw_html[]:<br><?php print_r($owmw_html); ?></pre>
      </div>
    </div>
  </div>
</div>
