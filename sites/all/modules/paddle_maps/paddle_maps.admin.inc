<?php

/**
 * @file
 * Administration page callbacks for the Paddle Advanced Search module.
 */

/**
 * Autocomplete callback for maps nodes by title or real path.
 *
 * Searches for a node by title or real path, but then identifies it by nid,
 * so the actual returned value can be used later by the form.
 *
 * The returned $matches array has
 * - key: The title, with the identifying node path in brackets, like
 *     "Some node title (node/444)"
 * - value: the title which will be displayed in the autocompleted dropdown.
 *
 * @param string $parent_lang
 *   The language of the parent node where the autocomplete is being called.
 * @param string $string
 *   The string that will be searched.
 * @param int $nid
 *   When the user searches for "node/<nid>" this will hold the nid.
 */
function paddle_maps_node_autocomplete_callback($parent_lang = '', $string = "", $nid = 0) {
  global $language_content;

  $results = array();
  if ($string) {
    $language = !empty($parent_lang) ? $parent_lang : $language_content->language;
    $query = db_select('node', 'n');
    $query->fields('n', array('nid', 'title'))
      ->condition('n.language', $language, '=')
      ->condition('n.type', 'paddle_maps_page')
      ->addTag('node_access');

    // We need a subquery to exclude archived nodes.
    // @todo might be improved joining with {node} table to filter on type?
    $archived = db_select('workbench_moderation_node_history', 'h');
    $archived->fields('h', array('nid'))
      ->condition('h.state', 'archived')
      ->condition('h.is_current', 1);
    $query->condition('n.nid', $archived, 'NOT IN');

    // We need to show only maps page nodes that have the search
    // field enabled. That field is enabled when the pane is present for that
    // revision. But a display is created only after editing the node at least
    // once. Since the default display for this node type contains the search
    // field (making that setting enabled by default), we need also to retrieve
    // all the nodes without any display available yet.
    // First, left join with the panelizer table, to retrieve the display id, if
    // available.
    $query->leftJoin('panelizer_entity', 'pe', 'pe.entity_id = n.nid AND pe.revision_id = n.vid AND pe.entity_type = :type', array(
      ':type' => 'node',
    ));
    // Then, left join with the panels table, joining on the display id.
    $query->leftJoin('panels_pane', 'pp', 'pp.did = pe.did');
    // Now filter all the entries that have the maps form pane, or
    // all those that don't have any display yet, or where the display id is set
    // to 0. In Paddle this happens when a node gets published without any
    // further edit of the node.
    $condition = db_or()
      ->condition('pp.type', 'maps_search_form')
      ->condition('pe.did', 0)
      ->isNull('pe.did');
    $query->condition($condition);

    if ($string == 'node' && is_numeric($nid) && $nid !== 0) {
      // The search string is a node path.
      $query->condition('n.nid', $nid, '=');
    }
    elseif ($string != 'node') {
      // The search string is a title or something else.
      $query->condition('title', '%' . db_like($string) . '%', 'LIKE');
    }
    // If neither is true we basically show the first 10 nodes we can find.
    $query->range(0, 10);
    $result = $query->execute();
    foreach ($result as $node) {
      $results[$node->title . " (node/$node->nid)"] = check_plain($node->title) . " (node/$node->nid)";
    }
  }

  drupal_json_output($results);
}
