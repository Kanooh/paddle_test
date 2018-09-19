<?php
/**
 * @file
 * Template for a single column layout
 *
 * This template provides a very simple "one column" panel display layout.
 *
 * Variables:
 * - $content: An array of content, each item in the array is keyed to one
 *   panel of the layout. This layout supports the following sections:
 *   - $content['middle']: The only panel in the layout.
 */

?>

<div class="row paddle-layout-paddle_no_column <?php print $classes; ?>">
  <div class="col-md-12">
    <div class="panel-panel panel-col panel-region-middle">
      <div><?php print $content['middle']; ?></div>
    </div>
  </div>
</div>
