<?php

/**
 * @file
 * Template for a 3 column layout with one top full width row
 * and one two column row.
 *
 * Variables:
 * - $content: An array of content, each item in the array is keyed to one
 *   panel of the layout. This layout supports the following sections:
 *   - $content['top']: The top panel in the layout.
 *   - $content['1_a']: The first column of the second row
 *   - $content['2_a']: The second column of the second row
 *   - $content['3_b']: The first column of the third row
 *   - $content['4_b']: The second column of the third row
 *   - $content['5_b']: The third column of the third row
 */

?>
<div class="row paddle-layout-paddle_3_col_c <?php print $classes; ?>">
  <div class="row">
    <div class="col-xs-12 root-column panel-region-top">
      <?php print $content['top']; ?>
    </div>
  </div>
    <div class="row">
    <div class="col-md-6 col-lg-8 root-column panel-region-first-left">
      <?php print $content['1_a']; ?>
    </div>
    <div class="col-md-6 col-lg-4 root-column panel-region-second-middle">
      <?php print $content['2_a']; ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6 col-lg-4 root-column panel-region-first-left">
      <?php print $content['3_b']; ?>
    </div>
    <div class="col-md-6 col-lg-4 root-column panel-region-second-middle">
      <?php print $content['4_b']; ?>
    </div>
    <div class="col-md-12 col-lg-4 root-column panel-region-third-right">
      <?php print $content['5_b']; ?>
    </div>
  </div>
</div>
