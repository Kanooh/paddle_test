<?php

/**
 * @file
 * Plugin to provide a custom content type pane,
 * moderation history section, for the node edit/add panel.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'title' => t('Revision history'),
  'description' => t('Displays the revision history for a particular node.'),
  'category' => t('Paddle Content Manager'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
);

/**
 * Render callback.
 */
function paddle_content_manager_moderation_history_pane_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = t('Revision history');
  $block->content = '';

  $max = 5;

  // Get all of the node revisions, each with its most recent moderation from
  // workbench_moderation_node_history table.
  if (isset($context->data->nid)) {
    $query = db_select('node', 'n');
    $query->innerJoin('node_revision', 'r', 'n.nid = r.nid');
    $query->innerJoin('workbench_moderation_node_history', 'm', 'r.vid = m.vid');
    $query->innerJoin('users', 'u', 'u.uid = m.uid');
    $query->condition('n.nid', $context->data->nid)
      ->orderBy('m.stamp', 'DESC')
      ->orderBy('m.hid', 'DESC')
      ->fields('m')
      ->range(0, $max);

    $items = $query->execute()->fetchAll();

    if ($items) {
      $item_count = count($items);
      if ($item_count == $max) {
        $total_item_count = $query->range()->countQuery()->execute()->fetchField();
      }
      else {
        $total_item_count = $item_count;
      }

      $vars = array(
        'items' => $items,
        'total_item_count' => $total_item_count,
        'node' => $context->data,
      );
      $block->content = theme('paddle_content_manager_moderation_history', $vars);
    }
  }

  return $block;
}

/**
 * Settings form builder callback.
 */
function paddle_content_manager_moderation_history_pane_content_type_edit_form($form, &$form_state) {
  // Just return default form.
  return $form;
}

/**
 * Displays the administrative title for a panel pane in the drag & drop UI.
 */
function paddle_content_manager_moderation_history_pane_content_type_admin_title($subtype, $conf, $context) {
  return t('Revision history');
}
