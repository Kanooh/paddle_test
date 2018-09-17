<?php
/**
 * @file
 * Drush commands for the color palettes.
 */

/**
 * Implements hook_drush_command().
 */
function paddle_color_palettes_drush_command() {
  return array(
    'paddle_color_palettes_replace_panes_subpalette' => array(
      'description' => 'Replaces all panes that have a given color subpalette with another color subpalette.',
      'callback' => 'paddle_color_palettes_replace_panes_subpalette',
      'arguments' => array(
        'from' => 'Index number of the original subpalette. (Starting from 0!)',
        'to' => 'Index number of the new subpalette. (Starting from 0!)',
      ),
      'required-arguments' => TRUE,
    ),
  );
}

/**
 * Replaces all panes that have a given color subpalette with another one.
 *
 * @param int $from
 *   Index number of the original color subpalette. (Starting from 0!)
 * @param int $to
 *   Index number of the new subpalette. (Starting from 0!)
 */
function paddle_color_palettes_replace_panes_subpalette($from, $to) {
  $sandbox = array(
    'from' => $from,
    'to' => $to,
  );
  $limit = 5;

  // Gets the count of all panes. We can't query by color subpalette because
  // that data is stored in a serialized array.
  $count_callback = function($sandbox) {
    $q = db_select('panels_pane', 'p')
      ->countQuery();
    return $q->execute()->fetchField();
  };

  // Gets a range of all panes.
  $range_callback = function($limit, $sandbox) {
    $offset = $sandbox['progress'];

    $q = db_select('panels_pane', 'p')
      ->fields('p', array());
    $q->range($offset, $limit);

    return $q->execute();
  };

  // Updates a single pane if it has the correct "from" subpalette.
  $item_update_callback = function($db_item, $sandbox) {
    // Ensure we unserialize all serialized fields with
    // ctools_export_unpack_object(). Fields that are not unserialized,
    // would otherwise become double-serialized when saving them again.
    // See panels_load_displays().
    ctools_include('export');
    $item = ctools_export_unpack_object('panels_pane', $db_item);

    // Some panes do not have their subpalette stored. In that case the
    // subpalette defaults to the one with index number 0.
    if (isset($item->extras['color_subpalettes']['paddle_color_subpalette'])) {
      $item_from = $item->extras['color_subpalettes']['paddle_color_subpalette'];
    }
    else {
      $item_from = 0;
    }

    if ($item_from == $sandbox['from']) {
      $item->extras['color_subpalettes']['paddle_color_subpalette'] = $sandbox['to'];
      drupal_write_record('panels_pane', $item, 'pid');

      return TRUE;
    }
    else {
      return FALSE;
    }
  };

  // Returns a message that can be printed after processing a pane.
  $progress_msg_callback = function($sandbox) {
    $item = $sandbox['last_item'];
    $updated = $sandbox['last_item_update_status'];

    $id_string = "pane {$item->pid}";

    if ($updated) {
      return $id_string;
    }
    else {
      return "{$id_string} SKIPPED";
    }
  };

  paddle_update_batch_helper($sandbox, $limit, $count_callback, $range_callback, $item_update_callback, $progress_msg_callback);
}
