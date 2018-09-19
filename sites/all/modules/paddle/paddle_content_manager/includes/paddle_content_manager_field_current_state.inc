<?php

/**
 * @file
 * Contains paddle_content_manager_field_current_state.
 */

/**
 * A handler to provide a field that displays either the latest
 * node workbench moderation state or the regular status.
 *
 * @ingroup views_field_handlers
 */
class paddle_content_manager_field_current_state extends workbench_moderation_handler_field_state {

  /**
   * Overrides workbench_moderation_handler_field_state::render_name().
   */
  public function render_name($data, $values) {
    // Get the node.
    $nid = isset($values->node_node_revision_nid) ? $values->node_node_revision_nid :
      $values->node_workbench_moderation_node_history_nid;
    $node = node_load($nid);

    // Set the adjusted status.
    if ($data == 'published' && !empty($node->unpublish_on) && $node->unpublish_on != '0') {
      return t('Scheduled for depublication');
    }
    elseif ($data == 'scheduled') {
      return t('Scheduled for publication');
    }
    elseif ($this->options['machine_name'] != 1 && $data !== NULL && $data !== '') {
      return t($this->sanitize_value(workbench_moderation_state_label($data)));
    }

    return $this->sanitize_value($data);
  }
}
