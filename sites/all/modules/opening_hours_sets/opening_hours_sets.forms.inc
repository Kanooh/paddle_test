<?php
/**
 * @file
 * Form callbacks for opening hours sets module.
 */

/**
 * Form callback to add and edit an opening hour set.
 *
 * @param array $form
 *   The form.
 * @param array $form_state
 *   The form state.
 * @param Entity $opening_hours_set
 *   The opening hour set object. NULL when creating a new one.
 *
 * @return array
 *   The finished form.
 */
function opening_hours_set_form($form, &$form_state, $opening_hours_set = NULL) {
  // Always include this file.
  form_load_include($form_state, 'inc', 'opening_hours_sets', 'opening_hours_sets.forms');

  // Save the opening hour entity for later processing. Use the one in the
  // form_state during a rebuild.
    // Create a new feed instance if needed.
  if (is_null($opening_hours_set)) {
    $opening_hours_set = entity_create('opening_hours_set', array());
  }

  $form_state['opening_hours_set'] = $opening_hours_set;

  // Use a wrapper for better styling in modals.
  $form['wrapper'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('form-body')),
    '#tree' => FALSE,
  );

  $form['wrapper']['title'] = array(
    '#type' => 'textfield',
    '#title' => t('Opening hours set title'),
    '#description' => t('Give the opening hours set a title, which you can add to one or more organizational units.'),
    '#required' => TRUE,
    '#default_value' => !empty($opening_hours_set->title) ? $opening_hours_set->title : '',
  );

  field_attach_form('opening_hours_set', $opening_hours_set, $form['wrapper'], $form_state);

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
 * Submit callback for the opening hour form.
 */
function opening_hours_set_form_submit($form, &$form_state) {
  /* @var Entity $opening_hour */
  $opening_hours_set = $form_state['opening_hours_set'];
  entity_form_submit_build_entity('opening_hours_set', $opening_hours_set, $form, $form_state);
  $opening_hours_set->save();

  drupal_set_message(t('Opening hours set saved.'));
}

/**
 * Form callback for the "Delete opening hours set" form.
 */
function opening_hours_sets_delete_form($form, &$form_state, $opening_hours_set) {
  // Always include this file.
  form_load_include($form_state, 'inc', 'opening_hours_sets', 'opening_hours_sets.forms');

  $opening_hours_set = entity_load_single('opening_hours_set', $opening_hours_set->ohsid);
  $form_state['ohsid'] = $opening_hours_set->ohsid;

  $form['body'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('form-body')),
  );

  $form['body']['message'] = array(
    '#type' => 'item',
    '#markup' => t('Are you sure you want to delete the opening hours set %title', array('%title' => $opening_hours_set->title)),
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
 * Form submit for the "Delete opening hours set" form.
 */
function opening_hours_sets_delete_form_submit($form, &$form_state) {
  $form_state['completed'] = TRUE;
  $form_state['refresh'] = FALSE;

  if ($form_state['clicked_button']['#value'] == t('Delete')) {
    // Delete the entity.
    entity_delete('opening_hours_set', $form_state['ohsid']);
    $form_state['refresh'] = TRUE;
    drupal_set_message(t('Opening hours set deleted.'));
  }
}
