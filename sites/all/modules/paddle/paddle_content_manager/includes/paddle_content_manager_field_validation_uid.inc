<?php

/**
 * @file
 * Contains paddle_content_manager_field_validation_uid.
 */

/**
 * A handler to provide a field that displays the validation author.
 *
 * @ingroup views_field_handlers
 */
class paddle_content_manager_field_validation_uid extends views_handler_field {

  /**
   * Overrides views_handler_field::render().
   */
  public function render($values) {
    if (empty($values->paddle_content_manager_validate_user_uid)) {
      return '';
    }

    $user = user_load($values->paddle_content_manager_validate_user_uid);
    return $this->sanitize_value($user->name);
  }

}
