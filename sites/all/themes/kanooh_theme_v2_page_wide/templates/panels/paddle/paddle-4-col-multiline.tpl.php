<?php

/**
 * @file
 * Template for a four column layout
 *
 * Variables:
 * - $content: An array of content, each item in the array is keyed to one
 *   panel of the layout. This layout supports the following sections:
 *   - $content['nested_top']: The top nested column.
 *   - $content['nested_left']: The left nested column.
 *   - $content['nested_right']: The right nested column.
 *   - $content['nested_bottom']: The bottom nested column.
 *   - $content['column_two']: The second column.
 *   - $content['column_three']: The third column.
 *   - $content['full_bottom']: The full width bottom row.
 */

?>
<div class="row paddle-layout-paddle_4_col_multiline <?php print $custom_styles['nested_6_a']; ?>">
  <div class="row">
    <div class="col-md-12 col-lg-6">
      <?php print $content['nested_6_a']; ?>
    </div>
    <div class="col-md-6 col-md-nomarginleft col-lg-3">
      <?php print $content['nested_3_a']; ?>
    </div>
    <div class="col-md-6 col-lg-3">
      <?php print $content['nested_3_b']; ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6 col-lg-3">
      <?php print $content['nested_3_c']; ?>
    </div>
    <div class="col-md-6 col-lg-3">
      <?php print $content['nested_3_d']; ?>
    </div>
    <div class="col-md-6 col-lg-3 col-md-nomarginleft">
      <?php print $content['nested_3_e']; ?>
    </div>
    <div class="col-md-6 col-lg-3">
      <?php print $content['nested_3_f']; ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 col-lg-6">
      <?php print $content['nested_6_b']; ?>
    </div>
    <div class="col-md-6 col-md-nomarginleft col-lg-3">
      <?php print $content['nested_3_g']; ?>
    </div>
    <div class="col-md-6 col-lg-3">
      <?php print $content['nested_3_h']; ?>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <?php print $content['full_bottom']; ?>
    </div>
  </div>
</div>
