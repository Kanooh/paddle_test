<?php
/**
 * @file
 * Plugin to provide a landing page ID argument.
 */

/**
 * Plugins are described by creating a $plugin array which will be used
 * by the system that includes this file.
 */
$plugin = array(
  'title' => t("Node (current revision): Paddle landing page node ID"),
  'keyword' => 'paddle_landing_page',
  'description' => t('Creates a node context (draft revision) for a landing page from a node ID argument.'),
  'context' => 'paddle_landing_page_node_current_revision_context',
  'placeholder form' => array(
    '#type' => 'textfield',
    '#description' => t('Enter the node ID of a landing page for this argument'),
  ),
);

/**
 * Creates a node context.
 */
function paddle_landing_page_node_current_revision_context($arg = NULL, $conf = NULL, $empty = FALSE) {
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

  if (!$node || 'landing_page' !== $node->type) {
    return NULL;
  }

  // Load current revision of the node, according to workbench moderation.
  $node = workbench_moderation_node_current_load($node);

  return ctools_context_create('node', $node);
}
