<?php
/**
 * @file
 * Drush commands for paddle_panes module.
 */

/**
 * Implements hook_drush_command().
 */
function paddle_panes_drush_command() {
  $items = array();

  $items['paddle-panes-image-pane-lightbox'] = array(
    'description' => 'Enable or disable lightbox options for all image panes',
    'arguments' => array(
      'value' => '1 for enabled, 0 for disabled',
    ),
    'required-arguments' => TRUE,
    'aliases' => array('plp-lightbox'),
  );

  return $items;
}

/**
 * Command callback. Changes lightbox options for image panes.
 */
function drush_paddle_panes_image_pane_lightbox($value) {
  // Double check that a wanted value has been entered.
  if ('0' !== $value && '1' !== $value) {
    drush_log(dt('Please enter 1 or 0 as value.'), 'error');
    return;
  }

  $results = db_query('SELECT * FROM {panels_pane} WHERE type = :type', array(':type' => 'add_image'))->fetchAllAssoc('pid');

  // Exit if no panes found.
  if (!count($results)) {
    drush_log(dt('No image panes found.'), 'error');
    return;
  }

  // Use ctools library to unpack serialize properties.
  ctools_include('export');

  // Prepare the correct configuration value.
  $url_type = $value ? 'colorbox' : 'no_link';

  // Loop all the panes and change the option accordingly.
  $updated = 0;
  $skipped = 0;
  foreach ($results as $raw_pane) {
    $pane = ctools_export_unpack_object('panels_pane', $raw_pane);

    // Update the pane only if it has a different url type, so we save
    // database updates.
    if (empty($pane->configuration['url_type']) || $url_type !== $pane->configuration['url_type']) {
      $pane->configuration['url_type'] = $url_type;
      drupal_write_record('panels_pane', $pane, array('pid'));
      module_invoke_all('panels_pane_update', $pane);
      $updated++;
    }
    else {
      $skipped++;
    }
  }

  drush_log(dt('Checked @count panes: @updated were updated, @skipped were skipped as no update needed.', array(
    '@count' => count($results),
    '@updated' => $updated,
    '@skipped' => $skipped,
  )), 'completed');
}
