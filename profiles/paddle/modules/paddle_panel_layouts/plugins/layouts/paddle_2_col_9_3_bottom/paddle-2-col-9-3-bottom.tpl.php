<?php
/**
 * @file
 * Template for a 9/3 two column layout, with a bottom.
 *
 * Variables:
 * - $content: An array of content, each item in the array is keyed to one
 *   panel of the layout. This layout supports the following sections:
 *   - $content['left']: The left panel in the layout.
 *   - $content['right']: The right panel in the layout.
 *   - $content['bottom']: The bottom panel in the layout.
 */

?>

<div class="row paddle-layout-paddle_2_col_9_3_bottom <?php print $classes; ?>">
  <div class="col-md-9 root-column">
    <div class="panel-panel panel-col panel-region-left">
      <div><?php print $content['left']; ?></div>
    </div>
    <div class="row panel-region-bottom">
      <div class="col-md-12">
        <div><?php print $content['bottom']; ?></div>
      </div>
    </div>
  </div>
  <div class="col-md-3 root-column">
    <div class="panel-panel panel-col panel-region-right">
      <div><?php print $content['right']; ?></div>
    </div>
  </div>
</div>
