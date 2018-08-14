<?php

/**
 * @file
 * This file handles views api definitions.
 */

/**
 * Implements hook_views_data().
 */
function paddle_mailchimp_views_data() {
  $data = array();

  $data['mailchimp_signup']['signup_lists'] = array(
    'group' => t('MailChimp Signup'),
    'real field' => 'mc_lists',
    'title' => t('Mailchimp Lists'),
    'help' => t('The lists to which the users can subscribe with this signup form.'),
    'field' => array(
      'handler' => 'paddle_mailchimp_handler_field_signup_lists',
    ),
  );

  $data['mailchimp_signup']['edit_link'] = array(
    'group' => t('MailChimp Signup'),
    'title' => t('Edit link'),
    'help' => t('A link to edit this signup form.'),
    'field' => array(
      'handler' => 'paddle_mailchimp_handler_field_edit_link',
    ),
  );

  $data['mailchimp_signup']['delete_link'] = array(
    'group' => t('MailChimp Signup'),
    'title' => t('Delete link'),
    'help' => t('A link to delete this signup form.'),
    'field' => array(
      'handler' => 'paddle_mailchimp_handler_field_delete_link',
    ),
  );

  return $data;
}
