<?php
/**
 * @file
 * Template for a three column layout
 *
 * Variables:
 * - $content: An array of content, each item in the array is keyed to one
 *   panel of the layout. This layout supports the following sections:
 *   - $content['left']: The left panel in the layout.
 *   - $content['middle']: The middle panel in the layout.
 *   - $content['right']: The right panel in the layout.
 */
?>

<div class="row paddle-layout-paddle_three_column <?php print $classes; ?>">
  <div class="col-md-12">
    <div class="col-md-6 col-lg-4 root-column">
      <div class="panel-panel panel-col panel-region-left">
        <div><?php print $content['left']; ?></div>
      </div>
    </div>
    <div class="col-md-6 col-lg-4 root-column">
      <div class="panel-panel panel-col panel-region-middle">
        <div><?php print $content['middle']; ?></div>
      </div>
    </div>
     <div class="col-md-12 col-lg-4 root-column">
      <div class="panel-panel panel-col panel-region-right">
        <div><?php print $content['right']; ?></div>
      </div>
    </div>
  </div>
</div>
