<?php
/**
 * @file
 * Plugin to provide access control based upon node comment status.
 */

$plugin = array(
  'title' => t('Node: comments are hidden for a certain content type'),
  'description' => t('Control access by the nodes comment status of a content type.'),
  'callback' => 'paddle_comment_node_type_comment_status_hidden_ctools_access_check',
  'summary' => 'paddle_comment_node_type_comment_status_hidden_ctools_access_summary',
  'required context' => new ctools_context_required(t('Node'), 'node'),
);

/**
 * Checks for access.
 */
function paddle_comment_node_type_comment_status_hidden_ctools_access_check($conf, $context) {
  return (!empty($context->data) && variable_get('comment_' . $context->data->type, COMMENT_NODE_HIDDEN) != COMMENT_NODE_HIDDEN);
}

/**
 * Provides a summary description based upon the checked node_status.
 */
function paddle_comment_node_type_comment_status_hidden_ctools_access_summary($conf, $context) {
  return t('Returns true if the content type comment status is not "hidden".');
}
