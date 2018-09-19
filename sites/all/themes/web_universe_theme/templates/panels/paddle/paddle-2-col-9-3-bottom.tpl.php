<?php

/**
 * @file
 * Template for a 9/3 two column layout, with a bottom.
 *
 * Variables:
 * - $content: An array of content, each item in the array is keyed to one
 *   panel of the layout. This layout supports the following sections:
 *   - $content['left']: The left panel in the layout.
 *   - $content['right']: The right panel in the layout.
 *   - $content['bottom']: The bottom panel in the layout.
 */
?>

<div class="grid">
  <div class="col--9-12 col--12-12--s col--8-12--m">
    <div class="layout">
      <div><?php print $content['left']; ?></div>
    </div>
    <div class="layout">
      <div><?php print $content['bottom']; ?></div>
    </div>
  </div>
  <div class="col--3-12 col--12-12--s col--4-12--m">
      <div><?php print $content['right']; ?></div>
  </div>
</div>
