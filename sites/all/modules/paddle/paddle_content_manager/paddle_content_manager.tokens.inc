<?php

/**
 * @file
 * Builds placeholder replacement tokens for Paddle content manager data.
 */

/**
 * Implements hook_token_info().
 */
function paddle_content_manager_token_info() {
  $node['diff-url'] = array(
    'name' => t('Diff URL'),
    'description' => t('The URL of the page listing the differences between revisions.'),
  );
  $node['diff-comparison-url'] = array(
    'name' => t('Latest differences comparison URL'),
    'description' => t('The URL to the differences comparison page between the current revision and the previous revision of this node.'),
  );

  return array(
    'tokens' => array('node' => $node),
  );
}

/**
 * Implements hook_tokens().
 */
function paddle_content_manager_tokens($type, $tokens, array $data = array(), array $options = array()) {
  $url_options = array('absolute' => TRUE);
  $replacements = array();

  if ($type == 'node' && !empty($data['node'])) {
    $node = $data['node'];

    foreach ($tokens as $name => $original) {
      switch ($name) {
        case 'diff-url':
          $replacements[$original] = url("node/{$node->nid}/moderation/diff", $url_options);
          break;

        case 'diff-comparison-url':
          $revisions = node_revision_list($node);
          if (count($revisions) == 1) {
            $replacements[$original] = t('(No previous revision available.)');
          }
          else {
            module_load_include('inc', 'diff', 'diff.pages');
            $old_vid = _diff_get_previous_vid($revisions, $node->vid);
            $replacements[$original] = url("node/{$node->nid}/moderation/diff/view/{$old_vid}/{$node->vid}", $url_options);
          }
          break;
      }
    }
  }

  return $replacements;
}
