<?php

/**
 * @file
 * Template for the "In the Spotlight" view.
 */
?>

<?php if (!empty($variables['events']['spotlight'])) : ?>
  <?php foreach ($variables['events']['spotlight'] as $event) : ?>
    <?php print theme_render_template(drupal_get_path('module', 'paddle_cultuurnet') . '/templates/paddle-cultuurnet-spotlight.tpl.php', $event); ?>
  <?php endforeach; ?>
<?php endif; ?>
