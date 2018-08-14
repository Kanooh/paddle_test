<?php

/**
 * @file
 * Template for the two columns tau layout.
 *
 * First column has a full width region, then 2 half-width ones. These regions
 * are repeated 4 times.
 * Left column extends for the whole height of the page.
 *
 * Variables:
 * - $content: An array of content, each item in the array is keyed to one
 *   panel of the layout. This layout supports the following sections:
 * - $content['left']: The left column panel region.
 * - $content['row_1_full']: The first full size region.
 * - $content['row_2_left']: The first half-width left panel region.
 * - $content['row_2_right']: The first half-width left panel region.
 * - $content['row_3_full']: The second full size region.
 * - $content['row_4_left']: The second half-width left panel region.
 * - $content['row_4_right']: The second half-width left panel region.
 * - $content['row_5_full']: The third full size region.
 * - $content['row_6_left']: The third half-width left panel region.
 * - $content['row_6_right']: The third half-width left panel region.
 * - $content['row_7_full']: The fourth full size region.
 * - $content['row_8_left']: The fourth half-width left panel region.
 * - $content['row_8_right']: The fourth half-width left panel region.
 */

?>
<div class="row paddle-layout-paddle_2_col_4_8 <?php print $custom_styles['left']; ?>">
  <div class="col-md-4 root-column panel-region-left">
    <?php print $content['left']; ?>
  </div>
  <div class="col-md-8 root-column">
    <div class="row">
      <div class="col-md-12 panel-region-row-1-full"><?php print $content['row_1_full']; ?></div>
    </div>

    <div class="row">
      <div class="col-md-6 panel-region-row-2-left"><?php print $content['row_2_left']; ?></div>
      <div class="col-md-6 panel-region-row-2-right"><?php print $content['row_2_right']; ?></div>
    </div>

    <div class="row">
      <div class="col-md-12 panel-region-row-3-full"><?php print $content['row_3_full']; ?></div>
    </div>

    <div class="row">
      <div class="col-md-6 panel-region-row-4-left"><?php print $content['row_4_left']; ?></div>
      <div class="col-md-6 panel-region-row-4-right"><?php print $content['row_4_right']; ?></div>
    </div>

    <div class="row">
      <div class="col-md-12 panel-region-row-5-full"><?php print $content['row_5_full']; ?></div>
    </div>

    <div class="row">
      <div class="col-md-6 panel-region-row-6-left"><?php print $content['row_6_left']; ?></div>
      <div class="col-md-6 panel-region-row-6-right"><?php print $content['row_6_right']; ?></div>
    </div>

    <div class="row">
      <div class="col-md-12 panel-region-row-7-full"><?php print $content['row_7_full']; ?></div>
    </div>

    <div class="row">
      <div class="col-md-6 panel-region-row-8-left"><?php print $content['row_8_left']; ?></div>
      <div class="col-md-6 panel-region-row-8-right"><?php print $content['row_8_right']; ?></div>
    </div>
  </div>
</div>
