<?php

  /**
   * @file
   * Template for a single column layout.
   *
   * This template provides a very simple "one column" panel display layout.
   *
   * Variables:
   * - $content: An array of content, each item in the array is keyed to one
   *   panel of the layout. This layout supports the following sections:
   *   - $content['middle']: The only panel in the layout.
   */
?>

<div class="region">
  <div class="layout">
    <div><?php print $content['middle']; ?></div>
  </div>
</div>
