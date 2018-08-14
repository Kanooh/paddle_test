<?php

/**
 * @file
 * Contains paddle_content_manager_field_assigned_uid.
 */

/**
 * A handler to provide a field that displays the assigned author.
 *
 * @ingroup views_field_handlers
 */
class paddle_content_manager_field_assigned_uid extends views_handler_field {

  /**
   * Overrides views_handler_field::render().
   */
  public function render($values) {
    if (empty($values->paddle_content_manager_revision_assignee_uid)) {
      return '';
    }

    $user = user_load($values->paddle_content_manager_revision_assignee_uid);
    return $this->sanitize_value($user->name);
  }

}
