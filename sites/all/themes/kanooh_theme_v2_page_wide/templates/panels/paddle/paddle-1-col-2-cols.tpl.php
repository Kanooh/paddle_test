<?php
/**
 * @file
 * Template for the two rows omega layout.
 *
 * First row has 2 columns in a 6-6 division based on 12-grid.
 * Second row has one full size column.
 * Rows are repeated 4 times.
 *
 * Variables:
 * - $content: An array of content, each item in the array is keyed to one
 *   panel of the layout. This layout supports the following sections:
 * - $content['row_1_left']: The left panel in the layout of the first row.
 * - $content['row_1_right']: The right panel in the layout of the first row.
 * - $content['row_2_full']: The full size panel in the layout of the second
 *   row.
 * - $content['row_3_left']: The left panel in the layout of the third
 *   row.
 * - $content['row_3_right']: The right panel in the layout of the third
 *   row.
 * - $content['row_4_full']: The full size panel in the layout of the fourth
 *   row.
 * - $content['row_5_left']: The left panel in the layout of the fifth
 *   row.
 * - $content['row_5_right']: The right panel in the layout of the fifth
 *   row.
 * - $content['row_6_full']: The full size panel in the layout of the sixth
 *   row.
 * - $content['row_7_left']: The left panel in the layout of the seventh
 *   row.
 * - $content['row_7_right']: The right panel in the layout of the seventh
 *   row.
 * - $content['row_8_full']: The full size panel in the layout of the last
 *   row.
 */
?>

<div class ="row paddle-layout-paddle_1_col_2_cols <?php print $custom_styles['row_1_left']; ?>">
  <div class="col-md-6">
    <div class="panel-panel panel-col panel-region-row-1-left">
      <?php print $content['row_1_left']; ?>
    </div>
  </div>
  <div class="col-md-6">
    <div class="panel-panel panel-col panel-region-row-1-right">
      <?php print $content['row_1_right']; ?>
    </div>
  </div>
</div>
<div class="row paddle-layout-paddle_1_col_2_cols <?php print $custom_styles['row_2_full']; ?>">
  <div class="col-md-12">
    <?php print $content['row_2_full']; ?>
  </div>
</div>
<div class ="row paddle-layout-paddle_1_col_2_cols <?php print $custom_styles['row_3_left']; ?>">
  <div class="col-md-6">
    <div class="panel-panel panel-col panel-region-row-1-left">
      <?php print $content['row_3_left']; ?>
    </div>
  </div>
  <div class="col-md-6">
    <div class="panel-panel panel-col panel-region-row-1-right">
      <?php print $content['row_3_right']; ?>
    </div>
  </div>
</div>
<div class="row paddle-layout-paddle_1_col_2_cols <?php print $custom_styles['row_4_full']; ?>">
  <div class="col-md-12">
    <?php print $content['row_4_full']; ?>
  </div>
</div>
<div class ="row paddle-layout-paddle_1_col_2_cols <?php print $custom_styles['row_5_left']; ?>">
  <div class="col-md-6">
    <div class="panel-panel panel-col panel-region-row-1-left">
      <?php print $content['row_5_left']; ?>
    </div>
  </div>
  <div class="col-md-6">
    <div class="panel-panel panel-col panel-region-row-1-right">
      <?php print $content['row_5_right']; ?>
    </div>
  </div>
</div>
<div class="row paddle-layout-paddle_1_col_2_cols <?php print $custom_styles['row_6_full']; ?>">
  <div class="col-md-12">
    <?php print $content['row_6_full']; ?>
  </div>
</div>
<div class ="row paddle-layout-paddle_1_col_2_cols <?php print $custom_styles['row_7_left']; ?>">
  <div class="col-md-6">
    <div class="panel-panel panel-col panel-region-row-1-left">
      <?php print $content['row_7_left']; ?>
    </div>
  </div>
  <div class="col-md-6">
    <div class="panel-panel panel-col panel-region-row-1-right">
      <?php print $content['row_7_right']; ?>
    </div>
  </div>
</div>
<div class="row paddle-layout-paddle_1_col_2_cols <?php print $custom_styles['row_8_full']; ?>">
  <div class="col-md-12">
    <?php print $content['row_8_full']; ?>
  </div>
</div>
