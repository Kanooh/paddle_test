<?php
/**
 * @file
 * Plugin to provide a node ID argument.
 */

/**
 * Plugins are described by creating a $plugin array which will be used
 * by the system that includes this file.
 */
$plugin = array(
  'title' => t("Node (current revision): ID"),
  'keyword' => 'paddle_node_revision_id',
  'description' => t('Creates a node context (draft revision) for a node from a node ID argument.'),
  'context' => 'paddle_node_current_revision_context',
  'placeholder form' => array(
    '#type' => 'textfield',
    '#description' => t('Enter the node ID for this argument'),
  ),
);

/**
 * Creates a node context.
 */
function paddle_node_current_revision_context($arg = NULL, $conf = NULL, $empty = FALSE) {
  // If unset it wants a generic, unfilled context.
  if ($empty) {
    return ctools_context_create_empty('node');
  }

  // We can accept either a node object or a pure nid.
  if (is_object($arg)) {
    $nid = $arg->nid;
  }
  else {
    if (!is_numeric($arg)) {
      return FALSE;
    }

    $nid = $arg;
  }

  $node = node_load($nid, NULL, TRUE);

  if (!$node) {
    return NULL;
  }

  // Load current revision of the node, according to workbench moderation.
  $node = workbench_moderation_node_current_load($node);

  return ctools_context_create('node', $node);
}
