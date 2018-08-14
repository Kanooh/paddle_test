<?php

/**
 * @file
 * Definition of paddle_mailchimp_handler_field_signup_lists.
 */

/**
 * Field handler to render the lists for a MailChimp Signup entity.
 *
 * @ingroup views_field_handlers
 */
class paddle_mailchimp_handler_field_signup_lists extends views_handler_field_entity {

  /**
   * Renders the lists for this signup form.
   */
  public function render($values) {
    $entity = entity_load('mailchimp_signup', array($values->mcs_id));
    $entity = $entity[$values->mcs_id];

    $list_names = array();
    $lists = mailchimp_get_lists();

    foreach ($entity->mc_lists as $list_id) {
      if (!empty($lists[$list_id])) {
        $list_names[] = $lists[$list_id]['name'];
      }
    }

    if (!count($list_names)) {
      return t('Cannot retrieve the list name(s)');
    }
    return implode(', ', $list_names);
  }
}
