<?php

/**
 * @file
 * Definition of paddle_mailchimp_handler_field_edit_link.
 */

/**
 * Field handler to display an edit MailChimp Signup entity link.
 *
 * @ingroup views_field_handlers
 */
class paddle_mailchimp_handler_field_edit_link extends views_handler_field_entity {

  /**
   * Renders the lists for this signup form.
   */
  public function render($values) {
    $entity = entity_load('mailchimp_signup', array($values->mcs_id));
    $entity = $entity[$values->mcs_id];
    $attributes = array(
      'class' => array(
        'ui-icon',
        'ui-icon-hiddentext',
        'ui-icon-edit',
        'signup-form-edit-link',
      ),
    );
    return l(t('Edit'), 'admin/config/services/mailchimp/signup/manage/' . $entity->name, array('attributes' => $attributes));
  }
}
