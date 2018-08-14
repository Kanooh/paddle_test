<?php
/**
 * @file
 * Template for a 6/6 two column layout
 *
 * Variables:
 * - $content: An array of content, each item in the array is keyed to one
 *   panel of the layout. This layout supports the following sections:
 *   - $content['left']: The left panel in the layout.
 *   - $content['right']: The right panel in the layout.
 */

?>

<div class="row">
  <div class="col-md-9">
    <div class="panel-panel panel-col">
      <div><?php print $content['left']; ?></div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="panel-panel panel-col">
      <div><?php print $content['right']; ?></div>
    </div>
  </div>
</div>
