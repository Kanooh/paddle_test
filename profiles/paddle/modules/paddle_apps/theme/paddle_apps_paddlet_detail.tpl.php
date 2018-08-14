<?php
/**
 * @file
 * This template renders the details of 1 app.
 *
 * Available variables:
 * - app_class: The main class for the app.
 * - name: The name of the app.
 * - description: The app's description.
 * - image: A link to the app's image thumbnail.
 * - controls: Links to administrative pages.
 * - level: The app's type. Mostly 'free' or 'extra'.
 */
?>

<div data-machine-name="<?php print $machine_name; ?>" data-status="<?php print $status; ?>" class="paddle-apps-paddlet detail paddlet-selector clearfix col-md-4 <?php print $app_class; ?>">
<?php if ($name): ?>
  <h3 class="paddle-apps-paddlet-name">
    <?php print $name; ?>
  </h3>
<?php endif; ?>

<?php if ($level == 'extra'): ?>
  <span class="paddle-apps-paddlet-level">
    <?php print t('paying'); ?>
  </span>
<?php endif; ?>

<?php if ($vendor): ?>
  <h4 class="paddle-apps-paddlet-vendor">
    <?php print $vendor; ?>
  </h4>
<?php endif; ?>

<?php if ($image): ?>
  <div class="paddle-apps-paddlet-image">
    <?php print render($image); ?>
  </div>
<?php endif; ?>
<?php if ($controls): ?>
  <div class="paddle-apps-paddlet-controls">
    <div class="paddle-apps-paddlet-info"><?php print $controls['info']; ?></div>
    <?php if ($controls['configure']): ?>
      <div class="paddle-apps-paddlet-configure"><?php print $controls['configure']; ?></div>
    <?php endif; ?>
    <div class="paddle-apps-paddlet-status"><?php print $controls['status']; ?></div>
  </div>
<?php endif; ?>

<?php if ($description): ?>
  <p class="paddle-apps-paddlet-description">
    <?php print $description; ?>
  </p>
<?php endif; ?>
</div>
