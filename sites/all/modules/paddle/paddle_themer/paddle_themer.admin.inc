<?php

/**
 * @file
 * Administration user interface for the Paddle Themer module.
 */

/**
 * Form builder for the 'Create new theme' form.
 */
function paddle_themer_create_new_theme_form($form, &$form_state) {
  ctools_include('export');

  // Build a list of base themes.
  $themes = paddle_themer_available_themes();
  $base_theme_options = array();
  foreach ($themes as $theme_name => $theme) {
    $base_theme_options[$theme_name] = $theme->human_name;
  }

  // Create the form.
  $form = array(
    'name' => array(
      '#type' => 'textfield',
      '#title' => t('Theme name'),
      '#size' => 60,
      '#maxlength' => 128,
      '#required' => TRUE,
      '#default_value' => format_date(REQUEST_TIME),
    ),
    'base_theme' => array(
      '#type' => 'select',
      '#title' => t('Based on'),
      '#options' => $base_theme_options,
      '#required' => TRUE,
    ),
    'submit' => array(
      '#type' => 'submit',
      '#value' => t('Submit'),
    ),
  );
  return $form;
}

/**
 * Submit callback for the 'Create new theme' form.
 */
function paddle_themer_create_new_theme_form_submit($form, &$form_state) {
  $values = $form_state['values'];
  $new_theme = paddle_themer_clone_theme($values['base_theme'], $values['name']);

  drupal_goto("admin/themes/{$new_theme->name}/edit");
}

/**
 * Form builder for the "Edit theme styles" form.
 */
function paddle_themer_theme_edit_form($form, &$form_state, $theme) {
  $form_state['item'] = $theme;

  $form['actions'] = array(
    '#type' => 'container',
    '#weight' => 100,
  );

  if (isset($form_state['save_as']) && $form_state['save_as']) {
    // Create the form.
    $form['name'] = array(
      '#type' => 'textfield',
      '#title' => t('Theme name'),
      '#size' => 60,
      '#maxlength' => 128,
      '#required' => TRUE,
      '#default_value' => format_date(REQUEST_TIME),
    );

    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Submit'),
      '#submit' => array('paddle_themer_theme_save_as_submit'),
    );
  }
  else {
    $form = paddle_themer_styles_edit_wizard_form($form, $form_state);

    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Submit'),
    );

    $form['actions']['submit_as'] = array(
      '#type' => 'submit',
      '#value' => t('Save As'),
      '#submit' => array('paddle_themer_theme_save_as_intention_submit'),
    );
  }

  if (module_exists('paddle_contextual_toolbar')) {
    $form['#after_build'][] = 'paddle_themer_add_contextual_actions_theme_edit';
  }

  return $form;
}

/**
 * Validate handler for the "Edit theme styles" form.
 */
function paddle_themer_theme_edit_form_validate($form, &$form_state) {
  paddle_themer_styles_edit_wizard_form_validate($form, $form_state);
}

/**
 * Submit callback for the "Edit theme styles" form.
 */
function paddle_themer_theme_edit_form_submit($form, &$form_state) {
  paddle_themer_styles_edit_wizard_form_submit($form, $form_state);

  ctools_export_crud_save('paddle_themer_theme', $form_state['item']);

  drupal_goto('admin/themes');
}

/**
 * Wizard form builder for the theme image form.
 *
 * Without any buttons so usable in a CTools powered wizard.
 */
function paddle_themer_image_wizard_form($form, &$form_state) {
  ctools_form_include($form_state, 'export');
  ctools_form_include($form_state, 'export-ui');

  $form['image'] = array(
    '#type' => 'managed_file',
    '#title' => t('Image'),
    '#description' => t('The uploaded image will be displayed as the theme image on the themes overview page.'),
    '#upload_location' => 'public://paddle_themer',
    '#default_value' => !empty($form_state['item']->image_id) ? $form_state['item']->image_id : NULL,
  );

  return $form;
}

/**
 * Submit callback for the theme image form.
 */
function paddle_themer_image_wizard_form_submit($form, &$form_state) {
  $form_state['item']->image_id = $form_state['values']['image'];
}

/**
 * Form builder for the theme image form.
 */
function paddle_themer_theme_image_form($form, &$form_state, $theme) {
  $form_state['item'] = $theme;

  $form = paddle_themer_image_wizard_form($form, $form_state);

  $form['actions'] = array(
    '#type' => 'container',
  );

  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Submit'),
  );

  return $form;
}

/**
 * Submit callback for the theme image form.
 */
function paddle_themer_theme_image_form_submit(&$form, &$form_state) {
  paddle_themer_image_wizard_form_submit($form, $form_state);

  ctools_export_crud_save('paddle_themer_theme', $form_state['item']);

  drupal_goto('admin/themes');
}

/**
 * Form builder for the delete confirmation form.
 */
function paddle_themer_theme_delete_confirm($form, $form_state, $theme) {
  $form['theme'] = array(
    '#type' => 'value',
    '#value' => $theme,
  );

  return confirm_form($form,
    t('Are you sure you want to delete this theme?'),
    'admin/themes',
    t('This action cannot be undone.'),
    t('Delete'),
    t('Cancel')
  );
}

/**
 * Submit handler for the delete confirmation form.
 */
function paddle_themer_theme_delete_confirm_submit($form, &$form_state) {
  if ($form_state['values']['confirm']) {
    $theme = $form_state['values']['theme'];

    ctools_export_crud_delete('paddle_themer_theme', $theme);
    drupal_goto('admin/themes');
  }
}

/**
 * Submit callback for the "Save As" button.
 */
function paddle_themer_theme_save_as_intention_submit($form, &$form_state) {
  $form_state['rebuild'] = TRUE;
  $form_state['save_as'] = TRUE;

  paddle_themer_styles_edit_wizard_form_submit($form, $form_state);
}

/**
 * Submit callback for the "Submit" button in the save as form.
 */
function paddle_themer_theme_save_as_submit($form, &$form_state) {
  $theme = $form_state['item'];

  $theme->human_name = $form_state['values']['name'];
  // Generate a UUID if a machine name was not given.
  $theme->name = uuid_generate();
  // Make sure to generate a new theme.
  $theme->export_type = NULL;
  unset($theme->id);

  // After the theme has been saved go to the Theme Overview page.
  $form_state['redirect'] = 'admin/themes';

  ctools_export_crud_save('paddle_themer_theme', $theme);
  $form_state['redirect'] = 'admin/themes';
}
