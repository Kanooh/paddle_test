<?php

/**
 * @file
 * Template of the related info pane of the Publication content type.
 */
?>

<div class="pane-related-info">
  <div class="pane-content">
    <div class="pane-section-body">
      <?php if (!empty($publication_year)) : ?>
        <p><span class="publication-info-label"><?php print t('Publication year'); ?></span><?php print ': ' . $publication_year; ?></p>
      <?php endif; ?>
      <?php if (!empty($reference_number)) : ?>
        <p><span class="publication-info-label"><?php print t('Report number'); ?></span><?php print ': ' . $reference_number; ?></p>
      <?php endif; ?>
      <?php if (!empty($type)) : ?>
        <p><span class="publication-info-label"><?php print t('Type'); ?></span><?php print ': ' . $type; ?></p>
      <?php endif; ?>
      <?php if (!empty($authors)) : ?>
        <p><span class="publication-info-label"><?php print t('Authors'); ?></span><?php print ': ' . $authors; ?></p>
      <?php endif; ?>
      <?php if (!empty($language)) : ?>
        <p><span class="publication-info-label"><?php print t('Language'); ?></span><?php print ': ' . $language; ?></p>
      <?php endif; ?>
      <?php if (!empty($keywords)) : ?>
        <p><span class="publication-info-label"><?php print t('Keywords'); ?></span><?php print ': ' . $keywords; ?></p>
      <?php endif; ?>
    </div>
  </div>
</div>
