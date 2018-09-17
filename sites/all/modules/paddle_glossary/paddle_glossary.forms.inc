<?php
/**
 * @file
 * Form callbacks for Glossary paddlet.
 */

/**
 * Form callback to add and edit a glossary definition.
 *
 * @param array $form
 *   The form.
 * @param array $form_state
 *   The form state.
 * @param Entity $definition
 *   The definition object. NULL when creating a new one.
 *
 * @return array
 *   The finished form.
 */
function paddle_glossary_definition_form($form, &$form_state, $definition) {
  // Always include this file.
  form_load_include($form_state, 'inc', 'paddle_glossary', 'paddle_glossary.forms');

  // Save the definition entity for later processing. Use the one in the
  // form_state during a rebuild.
  if (!isset($form_state['definition'])) {
    // Create a new feed instance if needed.
    if (is_null($definition)) {
      $definition = entity_create('paddle_glossary_definition', array());
    }
    $form_state['definition'] = $definition;
  }
  else {
    $definition = $form_state['definition'];
  }

  // Use a wrapper for better styling in modals.
  $form['wrapper'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('form-body')),
    '#tree' => FALSE,
  );

  $form['wrapper']['definition'] = array(
    '#type' => 'textfield',
    '#title' => t('Definition'),
    '#description' => t('The word to show in administrative areas.'),
    '#required' => TRUE,
    '#default_value' => $definition->label() ?: '',
  );

  field_attach_form('paddle_glossary_definition', $definition, $form['wrapper'], $form_state);

  $form['actions'] = array(
    '#type' => 'actions',
    '#attributes' => array('class' => array('form-buttons')),
  );

  $form['actions']['save'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
  );

  return $form;
}

/**
 * Validation callback for paddle_glossary entity form.
 */
function paddle_glossary_definition_form_validate($form, &$form_state) {
  entity_form_field_validate('paddle_glossary', $form, $form_state);

  $definition = $form_state['values']['definition'];
  if (!preg_match('/^[a-zA-Z0-9]/', $definition)) {
    form_set_error('definition', t('A definition should start with a letter or a number.'));
  }

  /* @var Entity $entity */
  $entity = $form_state['definition'];
  // Avoid two definitions for the same word.
  if (!empty($entity->is_new) || empty($entity->gdid)) {
    $query = new EntityFieldQuery();
    $count = $query
      ->entityCondition('entity_type', 'paddle_glossary_definition')
      ->propertyCondition('definition', $definition, 'LIKE')
      ->count()
      ->execute();

    if ($count) {
      form_set_error('definition', t('The definition %definition already exists.', array('%definition' => $definition)));
    }
  }
}

/**
 * Submit callback for the glossary definition form.
 */
function paddle_glossary_definition_form_submit($form, &$form_state) {
  /* @var Entity $definition */
  $definition = $form_state['definition'];
  entity_form_submit_build_entity('paddle_glossary_definition', $definition, $form, $form_state);
  $definition->save();

  drupal_set_message(t('Definition saved.'));
}

/**
 * Form callback for the "Delete glossary definition" form.
 */
function paddle_glossary_definition_delete_form($form, &$form_state) {
  // Always include this file.
  form_load_include($form_state, 'inc', 'paddle_glossary', 'paddle_glossary.forms');

  $definition = entity_load_single('paddle_glossary_definition', $form_state['gdid']);

  $form['body'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('form-body')),
  );

  $form['body']['message'] = array(
    '#type' => 'item',
    '#markup' => t('Are you sure you want to delete the glossary definition for %word?', array('%word' => $definition->definition)),
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
 * Form submit for the "delete definition" form.
 */
function paddle_glossary_definition_delete_form_submit($form, &$form_state) {
  $form_state['completed'] = TRUE;
  $form_state['refresh'] = FALSE;

  if ($form_state['clicked_button']['#value'] == t('Delete')) {
    // Delete the entity,
    entity_delete('paddle_glossary_definition', $form_state['gdid']);
    $form_state['refresh'] = TRUE;
    drupal_set_message(t('Definition deleted.'));
  }
  $form_state['redirect'] = 'admin/paddlet_store/app/paddle_glossary/configure';
}
