<?php
/**
 * @file
 * This template handles the overview of the themes.
 */
?>
<?php $counter = 0; ?>
<div class="row">
<?php if (empty($active_theme)): ?>
  <p class="paddle-themer-none-available">
    <?php print t('There is not active theme yet.'); ?>
  </p>
<?php else: ?>
  <?php print theme('paddle_themer_theme_detail', array('theme' => $active_theme, 'class' => 'active')); ?>
  <?php $counter++; ?>
<?php endif; ?>

<?php foreach ($my_themes as $theme): ?>
  <?php if ($counter == 3): ?>
    </div>
    <div class="row">
    <?php $counter = 0; ?>
  <?php endif; ?>

  <?php print theme('paddle_themer_theme_detail', array('theme' => $theme)); ?>
  <?php $counter++; ?>
<?php endforeach; ?>

<?php foreach ($standard_themes as $theme): ?>
  <?php if ($counter == 3): ?>
    </div>
    <div class="row">
    <?php $counter = 0; ?>
  <?php endif; ?>

  <?php print theme('paddle_themer_theme_detail', array('theme' => $theme)); ?>
  <?php $counter++; ?>
<?php endforeach; ?>
</div>
