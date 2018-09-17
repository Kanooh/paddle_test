<?php
/**
 * @file
 * Form callbacks for Pane Collection app.
 */

/**
 * Form callback to add and edit a pane collections.
 *
 * @param array $form
 *   The form.
 * @param array $form_state
 *   The form state.
 * @param Entity $pane_collection
 *   The pane collection object. NULL when creating a new one.
 *
 * @return array
 *   The finished form.
 */
function paddle_pane_collection_form(array $form, array &$form_state, Entity $pane_collection = NULL) {
  // Always include this file.
  form_load_include($form_state, 'inc', 'paddle_pane_collection', 'paddle_pane_collection.forms');

  // Save the pane collection entity for later processing. Use the one in the
  // form_state during a rebuild.
  if (!isset($form_state['pane_collection'])) {
    // Create a new feed instance if needed.
    if (is_null($pane_collection)) {
      $pane_collection = entity_create('paddle_pane_collection', array());
    }
    $form_state['pane_collection'] = $pane_collection;
  }
  else {
    $pane_collection = $form_state['pane_collection'];
  }

  // Use a wrapper for better styling in modals.
  $form['wrapper'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('form-body')),
    '#tree' => FALSE,
  );

  $form['wrapper']['title'] = array(
    '#type' => 'textfield',
    '#title' => t('Title'),
    '#description' => t('The title of the pane collection.'),
    '#required' => TRUE,
    '#default_value' => $pane_collection->label() ?: '',
  );

  field_attach_form('paddle_pane_collection', $pane_collection, $form['wrapper'], $form_state);

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
 * Validation callback for pane_collection entity form.
 */
function paddle_pane_collection_form_validate($form, &$form_state) {
  entity_form_field_validate('paddle_pane_collection', $form, $form_state);

  $title = $form_state['values']['title'];
  if (!preg_match('/^[a-zA-Z0-9]/', $title)) {
    form_set_error('definition', t('A title should start with a letter or a number.'));
  }

  /* @var Entity $entity */
  $entity = $form_state['pane_collection'];
  // Avoid two definitions for the same word.
  if (!empty($entity->is_new) || empty($entity->ppcid)) {
    $query = new EntityFieldQuery();
    $count = $query
      ->entityCondition('entity_type', 'paddle_pane_collection')
      ->propertyCondition('title', $title, 'LIKE')
      ->count()
      ->execute();

    if ($count) {
      form_set_error('title', t('The pane collection with title %title already exists.', array('%title' => $title)));
    }
  }
}

/**
 * Submit callback for the pane_collection entity form.
 */
function paddle_pane_collection_form_submit($form, &$form_state) {
  /* @var Entity $pane_collection */
  $pane_collection = $form_state['pane_collection'];
  entity_form_submit_build_entity('paddle_pane_collection', $pane_collection, $form, $form_state);
  $pane_collection->save();

  drupal_set_message(t('Pane collection saved.'));
}

/**
 * Form callback for the pane_collection delete form.
 */
function paddle_pane_collection_delete_form($form, &$form_state) {
  // Always include this file.
  form_load_include($form_state, 'inc', 'paddle_pane_collection', 'paddle_pane_collection.forms');

  $pane_collection = $form_state['build_info']['args'][0];
  $form_state['ppcid'] = $pane_collection->ppcid;

  $form['body'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('form-body')),
  );

  $form['body']['message'] = array(
    '#type' => 'item',
    '#markup' => t('Are you sure you want to delete the pane collection %title', array('%title' => $pane_collection->title)),
  );

  $form['buttons'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('form-buttons')),
  );

  $form['buttons']['confirm'] = array(
    '#type' => 'submit',
    '#value' => t('Delete'),
  );

  $form['buttons']['cancelling'] = array(
    '#type' => 'submit',
    '#value' => t('Cancel'),
  );

  return $form;
}

/**
 * Form submit for the "Delete pane collection" form.
 */
function paddle_pane_collection_delete_form_submit($form, &$form_state) {
  $form_state['completed'] = TRUE;
  $form_state['refresh'] = FALSE;

  if ($form_state['clicked_button']['#value'] == t('Delete')) {
    // Delete the entity.
    entity_delete('paddle_pane_collection', $form_state['ppcid']);
    $form_state['refresh'] = TRUE;
    drupal_set_message(t('Pane collection deleted.'));
  }
}
