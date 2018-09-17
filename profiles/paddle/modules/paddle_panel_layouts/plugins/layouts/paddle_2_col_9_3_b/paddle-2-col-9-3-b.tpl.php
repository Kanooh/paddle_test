<?php
/**
 * @file
 * Template for a 9/3 two column layout
 *
 * Variables:
 * - $content: An array of content, each item in the array is keyed to one
 *   panel of the layout. This layout supports the following sections:
 *   - $content['nested_top']: The top panel in the left column of the layout.
 *   - $content['nested_left']: The left panel in the left column of the layout.
 *   - $content['nested_right']: The right panel in the left column of the
 *     layout.
 *   - $content['nested_bottom']: The bottom panel in the left column of the
 *     layout.
 *   - $content['right']: The panel for the right column of the layout.
 */

?>
<div class="row paddle-layout-paddle_2_col_9_3_b <?php print $classes; ?>">
  <div class="col-md-9 root-column">
    <div class="row">
      <div class="col-md-12 panel-region-nested-top"><?php print $content['nested_top']; ?></div>
    </div>
    <div class="row">
      <div class="col-lg-3 panel-region-nested-left"><?php print $content['nested_left']; ?></div>
      <div class="col-lg-9 panel-region-nested-right"><?php print $content['nested_right']; ?></div>
    </div>
    <div class="row">
      <div class="col-md-12 panel-region-nested-bottom">
        <?php print $content['nested_bottom']; ?>
      </div>
    </div>
  </div>
  <div class="col-md-3 root-column panel-region-right">
    <div class="panel-region-right"><?php print $content['right']; ?></div>
  </div>
</div>
