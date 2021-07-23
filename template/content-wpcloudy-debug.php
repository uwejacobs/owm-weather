<style>
#debug_accordion<?php echo $wpc_opt["id"]; ?> .card-body {
  background-color: #fff;
  color: #000;
}
</style>
<h1>WP Cloudy 2 Debug Output (Weather Id <?php echo $wpc_opt["id"]; ?>)</h1>
<div class="accordion" id="debug_accordion<?php echo $wpc_opt["id"]; ?>">
  <div class="card">
    <div class="card-header" id="headingSys<?php echo $wpc_opt["id"]; ?>">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseSys<?php echo $wpc_opt["id"]; ?>" aria-expanded="false" aria-controls="collapseSys">
          System Options
        </button>
      </h2>
    </div>

    <div id="collapseSys<?php echo $wpc_opt["id"]; ?>" class="collapse" aria-labelledby="headingSys<?php echo $wpc_opt["id"]; ?>" data-parent="#debug_accordion<?php echo $wpc_opt["id"]; ?>">
      <div class="card-body">
        <pre class="wpc-pre"><?php print_r($wpc_sys_opt); ?></pre>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingPage<?php echo $wpc_opt["id"]; ?>">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapsePage<?php echo $wpc_opt["id"]; ?>" aria-expanded="false" aria-controls="collapsePage">
          Page Options
        </button>
      </h2>
    </div>

    <div id="collapsePage<?php echo $wpc_opt["id"]; ?>" class="collapse" aria-labelledby="headingPage<?php echo $wpc_opt["id"]; ?>" data-parent="#debug_accordion<?php echo $wpc_opt["id"]; ?>">
      <div class="card-body">
        <pre class="wpc-pre"><?php print_r($wpc_opt); ?></pre>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingData<?php echo $wpc_opt["id"]; ?>">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseData<?php echo $wpc_opt["id"]; ?>" aria-expanded="false" aria-controls="collapseData">
          Data
        </button>
      </h2>
    </div>

    <div id="collapseData<?php echo $wpc_opt["id"]; ?>" class="collapse" aria-labelledby="headingData<?php echo $wpc_opt["id"]; ?>" data-parent="#debug_accordion<?php echo $wpc_opt["id"]; ?>">
      <div class="card-body">
        <pre class="wpc-pre"><?php print_r($wpc_data); ?></pre>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingHtml<?php echo $wpc_opt["id"]; ?>">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseHtml<?php echo $wpc_opt["id"]; ?>" aria-expanded="false" aria-controls="collapseHtml">
          HTML
        </button>
      </h2>
    </div>

    <div id="collapseHtml<?php echo $wpc_opt["id"]; ?>" class="collapse" aria-labelledby="headingHtml<?php echo $wpc_opt["id"]; ?>" data-parent="#debug_accordion<?php echo $wpc_opt["id"]; ?>">
      <div class="card-body">
        <pre class="wpc-pre"><?php print_r($wpc_html); ?></pre>
      </div>
    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingRawHtml<?php echo $wpc_opt["id"]; ?>">
      <h2 class="mb-0">
        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseRawHtml<?php echo $wpc_opt["id"]; ?>" aria-expanded="false" aria-controls="collapseRawHtml">
          Raw HTML
        </button>
      </h2>
    </div>

    <div id="collapseRawHtml<?php echo $wpc_opt["id"]; ?>" class="collapse" aria-labelledby="headingRawHtml<?php echo $wpc_opt["id"]; ?>" data-parent="#debug_accordion<?php echo $wpc_opt["id"]; ?>">
      <div class="card-body">
<?php
function htmlfix(&$item, $key)
{
    $item = trim(htmlentities($item));
}

array_walk_recursive($wpc_html, 'htmlfix');
?>
        <pre class="wpc-pre"><?php print_r($wpc_html); ?></pre>
      </div>
    </div>
  </div>
</div>
