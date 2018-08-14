<?php
/**
 * @file
 * Template for the two rows psi layout.
 *
 * First row has one full size column.
 * Second row has 3 columns in a 4-4-4 division based on 12-grid.
 * Rows are repeated 4 times.
 *
 * Variables:
 * - $content: An array of content, each item in the array is keyed to one
 *   panel of the layout. This layout supports the following sections:
 * - $content['row_1_full']: The full size panel in the layout of the first row.
 * - $content['row_2_left']: The left panel in the layout of the second row.
 * - $content['row_2_center']: The center panel in the layout of the second row.
 * - $content['row_2_right']: The right panel in the layout of the second row.
 * - $content['row_3_full']: The full size panel in the layout of the third row.
 * - $content['row_4_left']: The left panel in the layout of the fourth row.
 * - $content['row_4_center']: The center panel in the layout of the fourth row.
 * - $content['row_4_right']: The right panel in the layout of the fourth row.
 * - $content['row_5_full']: The full size panel in the layout of the fifth row.
 * - $content['row_6_left']: The left panel in the layout of the sixth row.
 * - $content['row_6_center']: The center panel in the layout of the sixth row.
 * - $content['row_6_right']: The right panel in the layout of the sixth row.
 * - $content['row_7_full']: The full size panel in the layout of the seventh
 *   row.
 * - $content['row_8_left']: The left panel in the layout of the last row.
 * - $content['row_8_center']: The center panel in the layout of the last row.
 * - $content['row_8_right']: The right panel in the layout of the last row.
 */
?>

<div class="row paddle-layout-paddle_1_col_3_cols <?php print $classes; ?>">
  <div class="col-md-12">
    <div class="panel-panel panel-col panel-region-row-1-full">
      <?php print $content['row_1_full']; ?>
    </div>
  </div>
</div>
<div class ="row paddle-layout-paddle_1_col_3_cols">
  <div class="col-md-4">
    <div class="panel-panel panel-col panel-region-row-2-left">
      <?php print $content['row_2_left']; ?>
    </div>
  </div>
  <div class="col-md-4">
    <div class="panel-panel panel-col panel-region-row-2-center">
      <?php print $content['row_2_center']; ?>
    </div>
  </div>
  <div class="col-md-4">
    <div class="panel-panel panel-col panel-region-row-2-right">
      <?php print $content['row_2_right']; ?>
    </div>
  </div>
</div>
<div class="row paddle-layout-paddle_1_col_3_cols">
  <div class="col-md-12">
    <div class="panel-panel panel-col panel-region-row-3-full">
      <?php print $content['row_3_full']; ?>
    </div>
  </div>
</div>
<div class ="row paddle-layout-paddle_1_col_3_cols">
  <div class="col-md-4">
    <div class="panel-panel panel-col panel-region-row-4-left">
      <?php print $content['row_4_left']; ?>
    </div>
  </div>
  <div class="col-md-4">
    <div class="panel-panel panel-col panel-region-row-4-center">
      <?php print $content['row_4_center']; ?>
    </div>
  </div>
  <div class="col-md-4">
    <div class="panel-panel panel-col panel-region-row-4-right">
      <?php print $content['row_4_right']; ?>
    </div>
  </div>
</div>
<div class="row paddle-layout-paddle_1_col_3_cols">
  <div class="col-md-12">
    <div class="panel-panel panel-col panel-region-row-5-full">
      <?php print $content['row_5_full']; ?>
    </div>
  </div>
</div>
<div class ="row paddle-layout-paddle_1_col_3_cols">
  <div class="col-md-4">
    <div class="panel-panel panel-col panel-region-row-6-left">
      <?php print $content['row_6_left']; ?>
    </div>
  </div>
  <div class="col-md-4">
    <div class="panel-panel panel-col panel-region-row-6-center">
      <?php print $content['row_6_center']; ?>
    </div>
  </div>
  <div class="col-md-4">
    <div class="panel-panel panel-col panel-region-row-6-right">
      <?php print $content['row_6_right']; ?>
    </div>
  </div>
</div>
<div class="row paddle-layout-paddle_1_col_3_cols">
  <div class="col-md-12">
    <div class="panel-panel panel-col panel-region-row-7-full">
      <?php print $content['row_7_full']; ?>
    </div>
  </div>
</div>
<div class ="row paddle-layout-paddle_1_col_3_cols">
  <div class="col-md-4">
    <div class="panel-panel panel-col panel-region-row-8-left">
      <?php print $content['row_8_left']; ?>
    </div>
  </div>
  <div class="col-md-4">
    <div class="panel-panel panel-col panel-region-row-8-center">
      <?php print $content['row_8_center']; ?>
    </div>
  </div>
  <div class="col-md-4">
    <div class="panel-panel panel-col panel-region-row-8-right">
      <?php print $content['row_8_right']; ?>
    </div>
  </div>
</div>
