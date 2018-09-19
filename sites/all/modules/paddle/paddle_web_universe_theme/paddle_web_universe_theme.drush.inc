<?php
/**
 * @file
 * Drush commands for the web universe theme.
 */

/**
 * Implements hook_drush_command().
 */
function paddle_web_universe_theme_drush_command() {
  $items = array();
  $items['paddle-webuniverse-update-layout'] = array(
    'description' => 'Drush command which updates all the landing pages with a given layout.',
    'aliases' => array('pwu-ul'),
    'callback' => 'paddle_web_universe_theme_update_landing_pages_layout',
    'arguments' => array(
      'layout' => 'The Paddle Layout which all landing pages are desired to be change in. (required)',
    ),
    'examples' => array(
      'drush pwu-ul paddle_2_col_9_3' => 'Converts the layout of all landing pages to the 2 Column layout with widths 9/12 and 3/12.',
    ),
    'required-arguments' => TRUE,
  );

  return $items;
}

/**
 * Updates all layouts of landing pages through drush.
 *
 * @param $layout
 *   The layouts we want our pages in.
 *
 * @return mixed
 */
function paddle_web_universe_theme_update_landing_pages_layout($layout) {
  // Check if the layout exists.
  ctools_include('common', 'panels');
  $allowed_layouts = panels_common_get_allowed_layouts('panelizer_node:landing_page');
  if (!array_key_exists($layout,
      $allowed_layouts) && function_exists('drush_set_error')) {
    return drush_set_error(dt('"@layout" is not a valid Paddle layout.',
      array('@layout' => $layout)));
  }

  $sandbox = array(
    'layout' => $layout,
  );
  $limit = 5;

  // Gets the count of all nodes of the type 'landing_page'.
  $count_callback = function ($sandbox) {
    $q = db_select('node', 'n')
      ->condition('type', 'landing_page')
      ->countQuery();
    return $q->execute()->fetchField();
  };

  // Gets a range of all landing pages.
  $range_callback = function ($limit, $sandbox) {
    $offset = $sandbox['progress'];

    $q = db_select('node', 'n')
      ->fields('n', array());
    $q->range($offset, $limit);

    return $q->execute();
  };

  // Update the layout from all landing pages.
  $item_update_callback = function ($node, $sandbox) {
    try {
      return paddle_web_universe_theme_update_layout($node, $sandbox['layout']);
    }
    catch (\Exception $e) {
      // Nothing we can do.
      return FALSE;
    }
  };

  // Returns a message that can be printed after processing a landing page.
  $progress_msg_callback = function ($sandbox) {
    $item = $sandbox['last_item'];
    $updated = $sandbox['last_item_update_status'];

    $id_string = "Landing page {$item->title} with nid {$item->nid}";
    $action = $updated ? " has been updated" : " could NOT be updated";
    return $id_string . " " . $action . ".";
  };

  paddle_update_batch_helper($sandbox, $limit, $count_callback, $range_callback, $item_update_callback, $progress_msg_callback);
  drupal_flush_all_caches();
}

/**
 * Updates nodes with a new layout.
 *
 * @param $node
 * @param $layout
 *
 * @return bool
 */
function paddle_web_universe_theme_update_layout($entity, $layout) {
  ctools_include('export');
  ctools_include('plugins', 'panels');
  // Default display of the new layout.
  $panelizers = ctools_export_load_object('panelizer_defaults');
  $panelizer = $panelizers["node:$entity->type:$layout"];
  $default_display = $panelizer->display;
  $default_content = $default_display->content;
  $node = node_load($entity->nid);

  if (!empty($node->panelizer['page_manager']->display->did) && $node->panelizer['page_manager']->display->did != 'new') {
    // Current display.
    $display = $node->panelizer['page_manager']->display;

    // For every existing pane, create a new pane and hand it in the display.
    foreach ($default_content as $pid => $pane) {

      $new_pane = panels_new_pane($pane->type, $pane->subtype);

      foreach ($pane as $key => $value) {
        if (!in_array($key, array('type', 'subtype'))) {
          $new_pane->{$key} = $pane->{$key};
        }
      }

      $display->content[$new_pane->pid] = $new_pane;
      $display->panels[$new_pane->panel][] = $new_pane->pid;
    }

    $new_layout = panels_get_layout($layout);
    $new_layout_regions = panels_get_regions($new_layout, panels_new_display());
    // Get the first region ID.
    reset($new_layout_regions);
    $first_region_id = key($new_layout_regions);

    // Add the new regions.
    $new_panels = array();
    foreach ($new_layout_regions as $layout_region_id => $layout_region_label) {
      if (!isset($new_panels[$layout_region_id])) {
        $new_panels[$layout_region_id] = array();
      }
    }

    // Adds all the existing panes to the first region.
    foreach ($display->panels as $region_id => $region) {
      $new_panels[$first_region_id] = array_merge($new_panels[$first_region_id], $region);
    }

    // Go through each pane and make sure its region id is correct.
    foreach ($new_panels as $region_id => $region) {
      foreach ($region as $pid) {
        $display->content[$pid]->panel = $region_id;
      }
    }

    $display->panels = $new_panels;
    $display->layout = $default_display->layout;

    // Save the display.
    $saved_display = panels_save_display($display);
    panels_cache_clear('display', $saved_display->did);
    return TRUE;
  }

  return FALSE;
}
