<?php

/**
 * @file
 * Template of the reference number pane of the Publication content type.
 */
?>

<div class="pane-reference-number">
  <div class="pane-content">
    <div class="pane-section-body">
      <?php if (!empty($publication_txt_export)) : ?>
        <p>
           <?php print $publication_txt_export; ?>
        </p>
      <?php endif; ?>
    </div>
  </div>
</div>
