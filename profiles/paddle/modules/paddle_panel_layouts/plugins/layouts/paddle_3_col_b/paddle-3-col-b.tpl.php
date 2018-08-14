<?php

/**
 * @file
 * Template for a 3 column layout with one top full width row
 *
 * Variables:
 * - $content: An array of content, each item in the array is keyed to one
 *   panel of the layout. This layout supports the following sections:
 *   - $content['top']: The top panel in the layout.
 *   - $content['first_left']: The top panel in the left column.
 *   - $content['second_middle']: The left panel in the left column.
 *   - $content['third_right']: The middle panel in the left column.
 */

?>
<div class="row paddle-layout-paddle_3_col_b <?php print $classes; ?>">
  <div class="row">
    <div class="col-xs-12 root-column panel-region-top">
      <?php print $content['top']; ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6 col-lg-4 root-column panel-region-first-left">
      <?php print $content['first_left']; ?>
    </div>
    <div class="col-md-6 col-lg-4 root-column panel-region-second-middle">
      <?php print $content['second_middle']; ?>
    </div>
    <div class="col-md-12 col-lg-4 root-column panel-region-third-right">
      <?php print $content['third_right']; ?>
    </div>
  </div>
</div>
