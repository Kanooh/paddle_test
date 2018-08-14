<?php

/**
 * @file
 * Template of the description pane of the Cirro content type.
 */
?>

<div class="pane-description">
  <?php if (isset($is_methodology)) : ?>
    <h3 class="subtitle">
        <?php print ($is_methodology) ? t('Methodology') : t('Assistance tool'); ?>
    </h3>
  <?php endif; ?>
  <?php if (!empty($description)) : ?>
      <div class="cirro-resource-description"><?php print $description; ?></div>
  <?php endif; ?>
</div>
