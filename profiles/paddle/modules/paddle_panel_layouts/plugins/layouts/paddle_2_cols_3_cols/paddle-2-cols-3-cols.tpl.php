<?php
/**
 * @file
 * Template for the two rows layout.
 *
 * First row has 2 columns in a 8-4 division based on 12-grid.
 * Second row has 3 columns in a 4-4-4 division based on 12-grid.
 *
 * Variables:
 * - $content: An array of content, each item in the array is keyed to one
 *   panel of the layout. This layout supports the following sections:
 * - $content['row_1_left']: The left panel in the layout of the first row.
 * - $content['row_1_right']: The right panel in the layout of the first row.
 * - $content['row_2_left']: The left panel in the layout of the second row.
 * - $content['row_2_middle']: The middle panel in the layout of the second
 *   row.
 * - $content['row_2_right']: The right panel in the layout of the second row.
 */
?>

<div class ="row paddle-layout-paddle_2_cols_3_cols <?php print $classes; ?>">
  <div class ="col-md-12">
    <div class="col-md-4">
      <div class="panel-panel panel-col panel-region-row-1-left">
        <div>
          <?php print $content['row_1_left']; ?>
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <div class="panel-panel panel-col panel-region-row-1-right">
        <div>
          <?php print $content['row_1_right']; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row paddle-layout-paddle_2_cols_3_cols">
  <div class="col-md-12">
    <div class="col-md-6 col-lg-4">
      <div class="panel-panel panel-col panel-region-row-2-left">
        <div>
          <?php print $content['row_2_left']; ?>
        </div>
      </div>
    </div>
    <div class="col-md-6 col-lg-4">
      <div class="panel-panel panel-col panel-region-row-2-middle">
        <div>
          <?php print $content['row_2_middle']; ?>
        </div>
      </div>
    </div>
    <div class="col-md-12 col-lg-4">
      <div class="panel-panel panel-col panel-region-row-2-right">
        <div>
          <?php print $content['row_2_right']; ?>
        </div>
      </div>
    </div>
  </div>
</div>
