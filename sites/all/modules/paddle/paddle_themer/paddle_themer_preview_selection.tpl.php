<?php
/**
 * @file
 * This template handles the preview navigation.
 */
?>
<div class="paddle-themer-preview-nav">
  <span class="previous">
    <?php print l(t('Previous'), paddle_themer_preview_url($previous), array('external' => TRUE)); ?>
  </span>

  <span class="next">
    <?php print l(t('Next'), paddle_themer_preview_url($next), array('external' => TRUE)); ?>
  </span>
</div>

<div class="current">
  <p><?php print t('You are currently previewing the theme "@theme".', array('@theme' => $current->human_name)); ?></p>
  <?php print theme('paddle_themer_theme_image', array('theme' => $current, 'style_name' => 'thumbnail')); ?>
</div>

<div><?php print l(t('Exit preview'), 'admin/themes', array('paddle_themer_disable_url_outbound_alter' => TRUE)); ?></div>
