<?php

/**
 * @file
 * CTools content type plugin to embed the google custom search.
 */

$plugin = array(
  'title' => t('Google custom search'),
  'no title override' => TRUE,
  'description' => t('Google custom search.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => array(t('Paddle Landing Page'), -10),
  'edit form' => 'paddle_google_custom_search_content_type_edit_form',
  'render callback' => 'paddle_google_custom_search_content_type_render',
  'editor render callback' => 'paddle_google_custom_search_content_type_editor_render',
  'paddle_panes sections' => TRUE,
);

/**
 * Render callback for the pane to display in the panels editor.
 *
 * Note that this is supposed to return a string, as it overrides the content
 * property of the content of the block rendered by the default callback.
 */
function paddle_google_custom_search_content_type_editor_render($subtype, $conf, $args, $context) {
  $form = drupal_get_form('paddle_google_custom_search_form', TRUE);
  $output = drupal_render($form);

  // Allow the pane sections to contribute output.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_render($output, $conf, $context);
  }

  return $output;
}

/**
 * Render the content type.
 */
function paddle_google_custom_search_content_type_render($subtype, $conf, $args, $context) {
  // Return the output as a block.
  $block = new stdClass();
  $block->title = '';

  $disabled = FALSE;
  if (path_is_admin(current_path())) {
    $disabled = TRUE;
  }

  $form = drupal_get_form('paddle_google_custom_search_form', $disabled);
  $output = drupal_render($form);

  // Allow the pane sections to contribute output.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_render($output, $conf, $context);
  }

  $block->content = $output;
  return $block;
}

/**
 * Edit form callback for the content type.
 */
function paddle_google_custom_search_content_type_edit_form($form, &$form_state) {
  // Include the form elements for the pane sections in the form.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_build_edit_form($form, $form_state);
  }

  form_load_include($form_state, 'inc', 'paddle_google_custom_search', 'plugins/content_types/google_custom_search');

  return $form;
}

/**
 * Validate handler for the edit form.
 */
function paddle_google_custom_search_content_type_validate($form, $form_state) {
  // Allow the form elements provided by pane sections to be validated.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_validate($form, $form_state);
  }
}

/**
 * Submit callback for the configuration form.
 */
function paddle_google_custom_search_content_type_edit_form_submit($form, &$form_state) {
  // Allow the form elements provided by pane sections to be saved.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_submit($form, $form_state);
  }
}

/**
 * Form for the google custom search pane.
 */
function paddle_google_custom_search_form($form, &$form_state, $disabled = FALSE) {
  $form['search_field'] = array(
    '#type' => 'textfield',
    '#title' => '',
    '#default_value' => '',
    '#attributes' => array(
      'placeholder' => variable_get('search_placeholder_text', t('Looking for what?')),
    ),
    '#size' => 30,
  );

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => variable_get('search_placeholder_button_label', t('Search')),
  );

  if ($disabled) {
    $form['search_field']['#disabled'] = TRUE;
    $form['submit']['#disabled'] = TRUE;
  }

  return $form;
}

/**
 * Submit handler for the google custom search form in the pane.
 */
function paddle_google_custom_search_form_submit($form, &$form_state) {
  $keys = trim($form_state['values']['search_field']);
  $form_state['redirect'] = 'google-custom-search/' . $keys;
}
