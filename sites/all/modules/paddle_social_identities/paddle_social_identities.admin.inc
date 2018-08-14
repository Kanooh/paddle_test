<?php

/**
 * @file
 * Administration forms for the Paddle Social Identities module.
 */

/**
 * Page callback for the "create/edit social identity" form.
 */
function paddle_social_identities_settings($js, $identity = NULL) {
  $form_state = array(
    'ajax' => $js,
    'identity' => $identity,
  );

  if ($js) {
    ctools_include('ajax');
    ctools_include('modal');

    // Open the settings form in a modal.
    $commands = ctools_modal_form_wrapper('paddle_social_identities_settings_form', $form_state);

    // If the settings form has been saved, refresh the list of identities.
    if ($form_state['identity_saved']) {
      // Flush the status messages so they don't appear when we open a new page
      // or modal.
      drupal_get_messages(NULL, TRUE);

      $commands = array();
      $commands[] = ctools_modal_command_dismiss();
      $commands[] = paddle_social_identities_refresh_identities_list_command();
    }

    // Render all the commands and exit.
    print ajax_render($commands);
    ajax_footer();
    drupal_exit();
  }
  else {
    return drupal_build_form('paddle_social_identities_settings_form', $form_state);
  }
}

/**
 * Form callback to add/edit a social identity.
 */
function paddle_social_identities_settings_form($form, &$form_state) {
  $identity = $form_state['identity'];
  $form_state['entity_type'] = 'paddle_social_identity';

  $form['body'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('form-body')),
  );

  $form['body']['name'] = array(
    '#type' => 'textfield',
    '#title' => t('Identity name'),
    '#default_value' => !empty($identity) ? $identity->name : '',
    '#required' => TRUE,
  );

  field_attach_form('paddle_social_identity', $identity, $form['body'], $form_state);
  // Adjust the theme function for the urls field.
  $form['body']['field_social_identity_urls'][LANGUAGE_NONE]['#theme'] = 'paddle_social_identities_urls';

  $form['buttons'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('form-buttons')),
  );

  $form['buttons']['save'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
  );

  return $form;
}

/**
 * Submit callback for the social identity form.
 */
function paddle_social_identities_settings_form_submit($form, &$form_state) {
  module_load_include('inc', 'entity', 'includes/entity.ui');

  $identity = $form_state['identity'];

  if (empty($identity)) {
    $identity = entity_create('paddle_social_identity', array(
      'name' => $form_state['values']['name'],
    ));
  }
  else {
    $identity->name = $form_state['values']['name'];
  }

  $form_state['paddle_social_identity'] = $identity;
  entity_ui_form_submit_build_entity($form, $form_state);
  entity_save('paddle_social_identity', $identity);

  $form_state['identity'] = $identity;
  $form_state['identity_saved'] = TRUE;

  drupal_set_message(t('Social identity saved.'));
}

/**
 * Page callback for the "delete social identity" form.
 */
function paddle_social_identities_identity_delete($js, $identity) {
  $form_state = array(
    'ajax' => $js,
    'identity' => $identity,
  );

  if ($js) {
    ctools_include('ajax');
    ctools_include('modal');

    // Open the settings form in a modal.
    $commands = ctools_modal_form_wrapper('paddle_social_identities_identity_delete_form', $form_state);

    // If the settings form has been saved, refresh the list of social
    // identities.
    if ($form_state['completed']) {
      // Flush the status messages so they don't appear when we open a new page
      // or modal.
      drupal_get_messages(NULL, TRUE);

      $commands = array();
      $commands[] = ctools_modal_command_dismiss();

      if ($form_state['refresh']) {
        $commands[] = paddle_social_identities_refresh_identities_list_command();
      }
    }

    print ajax_render($commands);
    ajax_footer();
    drupal_exit();
  }
  else {
    return drupal_build_form('paddle_social_identities_identity_delete_form', $form_state);
  }
}

/**
 * Form callback for the "delete social identity" form.
 */
function paddle_social_identities_identity_delete_form($form, &$form_state) {
  $identity = $form_state['identity'];

  $form['body'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('form-body')),
  );

  $form['body']['message'] = array(
    '#type' => 'item',
    '#markup' => t('Are you sure you want to delete the identity %name?', array('%name' => $identity->name)),
  );

  $form['buttons'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('form-buttons')),
  );
  $form['buttons']['confirm'] = array(
    '#type' => 'submit',
    '#value' => t('Delete'),
  );

  // Can't use "cancel" as a key here, as that would cause the button to be
  // hidden by CSS...
  $form['buttons']['cancelling'] = array(
    '#type' => 'submit',
    '#value' => t('Cancel'),
  );

  return $form;
}

/**
 * Form submit for the "delete social identity" form.
 */
function paddle_social_identities_identity_delete_form_submit($form, &$form_state) {
  $form_state['completed'] = TRUE;
  $form_state['refresh'] = FALSE;

  if ($form_state['clicked_button']['#value'] == t('Delete')) {
    entity_delete('paddle_social_identity', $form_state['identity']->psiid);
    $form_state['refresh'] = TRUE;
    drupal_set_message(t('Social identity deleted.'));
  }
}
