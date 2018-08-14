<?php

/**
 * @file
 * Contains paddle_content_manager_field_creation_uid.
 */

/**
 * A handler to provide a field that displays the creation author.
 *
 * @ingroup views_field_handlers
 */
class paddle_content_manager_field_creation_uid extends views_handler_field {

  /**
   * Overrides views_handler_field::render().
   */
  public function render($values) {
    $uid = isset($values->node_workbench_moderation_node_history_uid) ? $values->node_workbench_moderation_node_history_uid : $values->node_uid;
    $user = user_load($uid);

    return $this->sanitize_value($user->name);
  }

}
