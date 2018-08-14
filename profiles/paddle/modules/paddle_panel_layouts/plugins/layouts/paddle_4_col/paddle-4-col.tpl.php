<?php

/**
 * @file
 * Template for a 6/6 two column layout with two nested columns
 *
 * Variables:
 * - $content: An array of content, each item in the array is keyed to one
 *   panel of the layout. This layout supports the following sections:
 *   - $content['top']: The top panel in the layout.
 *   - $content['nested_top']: The top panel in the left column of the layout.
 *   - $content['nested_left']: The left panel in the left column of the layout.
 *   - $content['nested_middle']: The middle panel in the left column of the
 *     layout.
 *   - $content['nested_right']: The right panel in the left column of the
 *     layout.
 *   - $content['nested_bottom']: The bottom panel in the left column of the
 *     layout.
 *   - $content['right']: The panel for the right column of the layout.
 */

?>
<div class="row paddle-layout-paddle_4_col <?php print $classes; ?>">
  <div class="col-md-12 col-lg-6 root-column panel-region-nested-top">
    <?php print $content['nested_top']; ?>
      <div class="row">
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-6 panel-region-nested-left"><?php print $content['nested_left']; ?></div>
            <div class="col-md-6 panel-region-nested-right"><?php print $content['nested_right']; ?></div>
          </div>
        </div>
      </div>
  </div>
  <div class="col-md-nomarginleft col-md-6 col-lg-3 root-column panel-region-second-column">
    <?php print $content['second_column']; ?>
  </div>
  <div class="col-md-6 col-lg-3 root-column panel-region-third-column">
    <?php print $content['third_column']; ?>
  </div>
</div>
