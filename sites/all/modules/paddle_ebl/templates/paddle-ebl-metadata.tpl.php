<?php

/**
 * @file
 * Template of the metadata pane of the EBL content type.
 */
?>

<div class="pane-ebl-metadata-group">
  <?php if (!empty($publisher)) : ?>
    <div class="pane-ebl-metadata-item">
      <div class="pane-ebl-metadata-label"><?php print t('Publisher'); ?></div>
      <div class="pane-ebl-metadata-value"><?php print $publisher; ?></div>
    </div>
  <?php endif; ?>
  <?php if (!empty($publication_date)) : ?>
    <div class="pane-ebl-metadata-item">
      <div class="pane-ebl-metadata-label"><?php print t('Publication date'); ?></div>
      <div class="pane-ebl-metadata-value"><?php print $publication_date; ?></div>
    </div>
  <?php endif; ?>
  <?php if (!empty($document_type)) : ?>
    <div class="pane-ebl-metadata-item">
      <div class="pane-ebl-metadata-label"><?php print t('Document type'); ?></div>
      <div class="pane-ebl-metadata-value"><?php print $document_type; ?></div>
    </div>
  <?php endif; ?>
  <?php if (!empty($languages)) : ?>
    <div class="pane-ebl-metadata-item">
      <div class="pane-ebl-metadata-label"><?php print t('Language(s)'); ?></div>
      <div class="pane-ebl-metadata-value"><?php print $languages; ?></div>
    </div>
  <?php endif; ?>
  <?php if (!empty($themes)) : ?>
    <div class="pane-ebl-metadata-item">
      <div class="pane-ebl-metadata-label"><?php print t('Themes', array(), array('context' => 'EBL theme label')); ?></div>
      <div class="pane-ebl-metadata-value"><?php print $themes; ?></div>
    </div>
  <?php endif; ?>
  <?php if (!empty($authors)) : ?>
    <div class="pane-ebl-metadata-item">
      <div class="pane-ebl-metadata-label"><?php print t('Author(s)'); ?></div>
      <div class="pane-ebl-metadata-value"><?php print $authors; ?></div>
    </div>
  <?php endif; ?>
  <?php if (!empty($ebl_series)) : ?>
    <div class="pane-ebl-metadata-item">
      <div class="pane-ebl-metadata-label"><?php print t('Series'); ?></div>
      <div class="pane-ebl-metadata-value"><?php print $ebl_series; ?></div>
    </div>
  <?php endif; ?>
</div>
