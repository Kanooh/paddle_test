<?php

/**
 * @file
 * Definition of paddle_protected_content_handler_field_role_delete.
 */

/**
 * Field handler to render the 'delete' action link of roles.
 *
 * @ingroup views_field_handlers
 */
class paddle_protected_content_handler_field_role_delete extends views_handler_field_url {

  /**
   * {@inheritdoc}
   */
  function render($values) {
    if (isset($values->rid)) {
      $roles = paddle_user_paddle_user_roles();
      if (!array_key_exists($values->rid, $roles)) {
        $this->options['alter']['make_link'] = TRUE;
        $this->options['alter']['path'] = 'admin/users/roles/nojs/delete/' . $values->rid;
        return !empty($this->options['text']) ? $this->options['text'] : t('Delete');
      }
    }
  }
}
