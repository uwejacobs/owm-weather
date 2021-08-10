<?php
/****************************************************************************/
/* DO NOT CHANGE THIS TEMPLATE DIRECTLY. IT WILL BE OVERWRITTEN BY UPDATES. */
/****************************************************************************/
/**
 * The WP OWM Weather debug template
 *
 */
?>
<style>
#debug_accordion<?php echo $wow_opt["id"]; ?> .card-body {
  background-color: #fff;
  color: #000;
}
</style>
<h2>WP OWM Weather Debug Output (Weather Id <?php echo $wow_opt["id"]; ?>)</h2>
<div class="accordion" id="debug_accordion<?php echo $wow_opt["id"]; ?>">
  <div class="card">
    <div class="card-header" id="headingSys<?php echo $wow_opt["id"]; ?>">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSys<?php echo $wow_opt["id"]; ?>" aria-expanded="false" aria-controls="collapseSys">
          System Options
        </button>
      </h2>
    </div>

    <div id="collapseSys<?php echo $wow_opt["id"]; ?>" class="collapse" aria-labelledby="headingSys<?php echo $wow_opt["id"]; ?>" data-parent="#debug_accordion<?php echo $wow_opt["id"]; ?>">
      <div class="card-body">
        <pre class="wow-pre"><?php print_r($wow_sys_opt); ?></pre>
        <pre class="wow-pre"><?php print_r($wow_params); ?></pre>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingPage<?php echo $wow_opt["id"]; ?>">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapsePage<?php echo $wow_opt["id"]; ?>" aria-expanded="false" aria-controls="collapsePage">
          Page Options
        </button>
      </h2>
    </div>

    <div id="collapsePage<?php echo $wow_opt["id"]; ?>" class="collapse" aria-labelledby="headingPage<?php echo $wow_opt["id"]; ?>" data-parent="#debug_accordion<?php echo $wow_opt["id"]; ?>">
      <div class="card-body">
        <pre class="wow-pre"><?php print_r($wow_opt); ?></pre>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingData<?php echo $wow_opt["id"]; ?>">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseData<?php echo $wow_opt["id"]; ?>" aria-expanded="false" aria-controls="collapseData">
          Data
        </button>
      </h2>
    </div>

    <div id="collapseData<?php echo $wow_opt["id"]; ?>" class="collapse" aria-labelledby="headingData<?php echo $wow_opt["id"]; ?>" data-parent="#debug_accordion<?php echo $wow_opt["id"]; ?>">
      <div class="card-body">
        <pre class="wow-pre"><?php print_r($wow_data); ?></pre>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingHtml<?php echo $wow_opt["id"]; ?>">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseHtml<?php echo $wow_opt["id"]; ?>" aria-expanded="false" aria-controls="collapseHtml">
          HTML
        </button>
      </h2>
    </div>

    <div id="collapseHtml<?php echo $wow_opt["id"]; ?>" class="collapse" aria-labelledby="headingHtml<?php echo $wow_opt["id"]; ?>" data-parent="#debug_accordion<?php echo $wow_opt["id"]; ?>">
      <div class="card-body">
        <pre class="wow-pre"><?php print_r($wow_html); ?></pre>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingRawHtml<?php echo $wow_opt["id"]; ?>">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseRawHtml<?php echo $wow_opt["id"]; ?>" aria-expanded="false" aria-controls="collapseRawHtml">
          Raw HTML
        </button>
      </h2>
    </div>

    <div id="collapseRawHtml<?php echo $wow_opt["id"]; ?>" class="collapse" aria-labelledby="headingRawHtml<?php echo $wow_opt["id"]; ?>" data-parent="#debug_accordion<?php echo $wow_opt["id"]; ?>">
      <div class="card-body">
<?php
function htmlfix(&$item, $key)
{
    $item = trim(htmlentities($item));
}

array_walk_recursive($wow_html, 'htmlfix');
?>
        <pre class="wow-pre"><?php print_r($wow_html); ?></pre>
      </div>
    </div>
  </div>
</div>
