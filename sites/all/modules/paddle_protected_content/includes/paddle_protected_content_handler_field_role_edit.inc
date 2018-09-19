<?php

/**
 * @file
 * Definition of paddle_protected_content_handler_field_role_edit.
 */

/**
 * Field handler to render the 'edit' action link of roles.
 *
 * @ingroup views_field_handlers
 */
class paddle_protected_content_handler_field_role_edit extends views_handler_field_url {

  /**
   * {@inheritdoc}
   */
  function render($values) {
    if (isset($values->rid)) {
      $roles = paddle_user_paddle_user_roles();
      if (!array_key_exists($values->rid, $roles)) {
        $this->options['alter']['make_link'] = TRUE;
        $this->options['alter']['path'] = 'admin/users/roles/nojs/edit/' . $values->rid;
        return !empty($this->options['text']) ? $this->options['text'] : t('Edit');
      }
    }
  }
}
