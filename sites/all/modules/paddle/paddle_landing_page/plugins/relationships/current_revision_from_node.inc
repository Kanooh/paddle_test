<?php
/**
 * @file
 * Plugin to provide a relationship with the current revision of a node.
 */

$plugin = array(
  'title' => t('Node current revision from node'),
  'keyword' => 'current_revision',
  'description' => t('Creates the current revision of a node as a node context.'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'context' => 'paddle_landing_page_current_revision_from_node_context',
);

/**
 * Return a new context based on an existing context.
 */
function paddle_landing_page_current_revision_from_node_context($context, $conf) {
  // If unset it wants a generic, unfilled context, which is just NULL.
  if (empty($context->data)) {
    return ctools_context_create_empty('node');
  }

  $current = workbench_moderation_node_current_load($context->data);

  return ctools_context_create('node', $current);
}
