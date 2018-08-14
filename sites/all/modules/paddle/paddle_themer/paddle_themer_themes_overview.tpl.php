<?php
/**
 * @file
 * This template handles the overview of the themes.
 */
?>
<div id="paddle-themer-active-theme">
  <h2><?php print t('Active theme'); ?></h2>
  <?php if (empty($active_theme)): ?>
    <p class="paddle-themer-none-available">
      <?php print t('There is not active theme yet.'); ?>
    </p>
  <?php else: ?>
    <div class="paddle-themer-theme-list">
      <?php print theme('paddle_themer_theme_detail', array('theme' => $active_theme)); ?>
    </div>
  <?php endif; ?>
</div>
<div id="paddle-themer-my-themes">
  <h2><?php print t('My themes'); ?></h2>
  <?php if (empty($my_themes)): ?>
    <p class="paddle-themer-none-available">
      <?php print t('You did not create any themes yet.'); ?>
    </p>
  <?php else: ?>
    <div class="paddle-themer-theme-list">
      <?php foreach ($my_themes as $theme): ?>
        <?php print theme('paddle_themer_theme_detail', array('theme' => $theme)); ?>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
<div id="paddle-themer-standard-themes">
  <h2><?php print t('Standard themes'); ?></h2>
  <?php if (empty($standard_themes)): ?>
    <p class="paddle-themer-none-available">
      <?php print t('No standard themes are available.'); ?>
    </p>
  <?php else: ?>
    <div class="paddle-themer-theme-list">
      <?php foreach ($standard_themes as $theme): ?>
        <?php print theme('paddle_themer_theme_detail', array('theme' => $theme)); ?>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>
