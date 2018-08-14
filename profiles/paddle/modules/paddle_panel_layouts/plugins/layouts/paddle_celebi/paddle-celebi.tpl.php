<?php

/**
 * @file
 * Template for the celebi layout.
 *
 * Variables:
 * - $content: An array of content, each item in the array is keyed to one
 *   panel of the layout. This layout supports the following:
 * - A top row in which we provide two sections, being 3/4 and 1/4 of
 * the width of the row.
 * - 4 rows in the middle which each provide two sections, all being equal
 * width (1/4 of the width of the row).
 * - A bottom row which only provides one section, being the full width
 * of the row.
 */
?>
<div class="row paddle-layout-paddle_celebi <?php print $classes; ?>">
  <div class="col-md-9 root-column">
    <div class="row">
      <div class="col-md-12 panel-region-nested-top"><?php print $content['full_a']; ?></div>
    </div>
    <div class="row">
      <div class="col-md-12 col-lg-7 panel-region-nested-left"><?php print $content['nested_7_b']; ?></div>
      <div class="col-md-12 col-lg-5 panel-region-nested-right"><?php print $content['nested_5_c']; ?></div>
    </div>
    <div class="row">
      <div class="col-md-12 panel-region-nested-bottom">
        <?php print $content['bottom']; ?>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 col-lg-6 panel-region-nested-left"><?php print $content['nested_6_e']; ?></div>
      <div class="col-md-12 col-lg-6 panel-region-nested-right"><?php print $content['nested_6_f']; ?></div>
    </div>
    <div class="row">
      <div class="col-md-12 col-lg-4 panel-region-nested-left"><?php print $content['nested_4_g']; ?></div>
      <div class="col-md-12 col-lg-4 panel-region-nested-right"><?php print $content['nested_4_h']; ?></div>
      <div class="col-md-12 col-lg-4 panel-region-nested-right"><?php print $content['nested_4_i']; ?></div>
    </div>
  </div>
  <div class="col-md-3 root-column panel-region-right">
    <div class="panel-region-right"><?php print $content['right']; ?></div>
  </div>
</div>
