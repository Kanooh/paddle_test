<?php

/**
 * @file
 * Definition of paddle_mailchimp_handler_field_delete_link.
 */

/**
 * Field handler to display a delete MailChimp Signup entity link.
 *
 * @ingroup views_field_handlers
 */
class paddle_mailchimp_handler_field_delete_link extends views_handler_field_entity {

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
        'ui-icon-delete',
        'ctools-use-modal',
        'signup-form-delete-link',
      ),
    );
    return l(t('Delete'), 'admin/paddlet_store/app/paddle_mailchimp/nojs/delete/' . $entity->name, array('attributes' => $attributes));
  }
}
