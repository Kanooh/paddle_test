<?php

/**
 * @file
 * Template for a four column layout
 *
 * Variables:
 * - $content: An array of content, each item in the array is keyed to one
 *   panel of the layout. This layout supports the following sections:
 *   - $content['column_one']: The first column.
 *   - $content['column_two']: The second column.
 *   - $content['column_three']: The third column.
 *   - $content['column_four']: The forth column.
 */

?>
<div class="row paddle-layout-paddle_4_col_full <?php print $custom_styles['column_one']; ?>">
  <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 root-column panel-region-column-one">
    <?php print $content['column_one']; ?>
  </div>
  <div class="col-xs-12 col-sm-12 col-md-4 col-lg-3 root-column panel-region-column-two">
    <?php print $content['column_two']; ?>
  </div>
  <div class="col-xs-12 col-md-4 col-lg-3 root-column panel-region-column-three">
    <?php print $content['column_three']; ?>
  </div>
  <div class="col-xs-12 col-md-12 col-lg-3 root-column panel-region-column-four">
    <?php print $content['column_four']; ?>
  </div>
</div>
