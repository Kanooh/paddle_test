<?php
/**
 * @file
 * Template for a 3/9 two column layout
 *
 * Variables:
 * - $content: An array of content, each item in the array is keyed to one
 *   panel of the layout. This layout supports the following sections:
 *   - $content['left']: The left panel in the layout.
 *   - $content['right']: The right panel in the layout.
 */

?>

<div class="row paddle-layout-paddle_2_col_3_9_flexible <?php print $custom_styles['left']; ?>">
  <?php if (empty($content['left'])): ?>
    <div class="col-md-12 root-column">
  <?php endif; ?>
  <?php if (!empty($content['left'])): ?>
    <div class="col-md-3 root-column">
      <div class="panel-panel panel-col panel-region-left">
        <div><?php print $content['left']; ?></div>
      </div>
    </div>
    <div class="col-md-9 root-column">
  <?php endif; ?>
      <div class="panel-panel panel-col panel-region-right">
        <div>
          <?php print $content['right']; ?>
        </div>
      </div>
    </div>
</div>
