<?php

/**
 * @file
 * Contains paddle_content_manager_field_url.
 */

/**
 * A handler to provide a field that displays a node's full url.
 *
 * @ingroup views_field_handlers
 */
class paddle_content_manager_field_url extends views_handler_field {

  /**
   * Overrides views_handler_field::render().
   */
  public function render($values) {
    $nid = $values->node_workbench_moderation_node_history_nid;
    return url('node/' . $nid, array('absolute' => TRUE));
  }

}
