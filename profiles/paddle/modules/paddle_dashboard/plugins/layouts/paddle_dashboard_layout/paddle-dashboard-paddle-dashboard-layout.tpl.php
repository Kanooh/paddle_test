<?php

/**
 * @file
 * Template file for the three column Dashboard layout.
 */
?>
<div class="panel-display dashboard-page" <?php if (!empty($css_id)): print "id=\"$css_id\""; endif; ?>>
  <div class="row">
    <div class="col-md-4">
      <div class="inside"><?php print $content['left']; ?></div>
    </div>

    <div class="col-md-4">
      <div class="inside"><?php print $content['middle']; ?></div>
    </div>

    <div class="col-md-4">
      <div class="inside"><?php print $content['right']; ?></div>
    </div>
  </div>
</div>
