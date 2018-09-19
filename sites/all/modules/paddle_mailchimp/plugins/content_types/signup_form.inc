<?php

/**
 * @file
 * Plugin to show a Signup form.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'title' => t('Signup form'),
  'no title override' => TRUE,
  'description' => t('Add a signup form.'),
  'category' => t('Paddle Mailchimp'),
  'editor render callback' => 'paddle_mailchimp_signup_form_content_type_editor_render',
  'defaults' => array(),
  'paddle_panes sections' => TRUE,
);

/**
 * Render callback for the pane to display in the panels editor.
 *
 * Note that this is supposed to return a string, as it overrides the content
 * property of the content of the block rendered by the default callback.
 */
function paddle_mailchimp_signup_form_content_type_editor_render($subtype, $conf, $args, $context) {
  return _paddle_mailchimp_signup_form_render_pane_content($subtype, $conf, $args, $context, TRUE);
}

/**
 * Render callback.
 */
function paddle_mailchimp_signup_form_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $block->content = _paddle_mailchimp_signup_form_render_pane_content($subtype, $conf, $args, $context, FALSE);

  return $block;
}

/**
 * Helper function to render a pane content, for both editor and frontend.
 */
function _paddle_mailchimp_signup_form_render_pane_content($subtype, $conf, $args, $context, $editor_render = FALSE) {
  $output = '';

  $entity = mailchimp_signup_load($conf['signup_form']);
  if ($entity) {
    $form = drupal_get_form('mailchimp_signup_subscribe_block_' . $entity->name . '_form', $entity, 'mailchimp_signup_block');

    // Disable fields when in editor render mode.
    if ($editor_render) {
      // Disable all merge fields and direct children (eg. submit).
      _paddle_mailchimp_signup_form_disable_elements($form);
      _paddle_mailchimp_signup_form_disable_elements($form['mergevars']);
      // The list checkbox is nested under an additional wrapper.
      foreach (element_children($form['mailchimp_lists']) as $list) {
        _paddle_mailchimp_signup_form_disable_elements($form['mailchimp_lists'][$list]);
      }
    }

    $output = drupal_render($form);
  }

  // Allow the pane sections to contribute output.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_render($output, $conf, $context);
  }

  return $output;
}

/**
 * Helper to set an input element attribute to disabled.
 *
 * drupal_get_form() already process the $form array, so we cannot set
 * #disabled to TRUE as the form_builder() function that handles that has
 * already been run.
 *
 * @param array $elements
 *   The element array whose children must be disabled.
 */
function _paddle_mailchimp_signup_form_disable_elements(&$elements) {
  foreach (element_children($elements) as $key) {
    if (!empty($elements[$key]['#input'])) {
      $elements[$key]['#attributes']['disabled'] = 'disabled';
    }
  }
}

/**
 * Settings form builder callback.
 */
function paddle_mailchimp_signup_form_content_type_edit_form($form, &$form_state) {
  form_load_include($form_state, 'inc', 'paddle_mailchimp', 'plugins/content_types/signup_form.inc');

  $form['section_body'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#weight' => -50,
    '#attributes' => array(
      'class' => array(drupal_html_class('pane-section-body')),
    ),
  );

  $signup_forms = mailchimp_signup_load_multiple();
  if (!count($signup_forms)) {
    $form['section_body']['signup_form'] = array(
      '#markup' => t('No signup forms added yet.'),
    );
  }
  else {
    $options = array();
    foreach ($signup_forms as $id => $entity) {
      $options[$id] = $entity->title;
    }
    $form['section_body']['signup_form'] = array(
      '#type' => 'radios',
      '#default_value' => $form_state['conf']['signup_form'],
      '#required' => TRUE,
      '#title' => t('Select a signup form'),
      '#options' => $options,
    );
  }

  // Include the form elements for the pane sections in the form.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_build_edit_form($form, $form_state);
  }

  return $form;
}

/**
 * Validate handler for the edit form.
 */
function paddle_mailchimp_signup_form_content_type_edit_form_validate($form, &$form_state) {
  // Allow the form elements provided by pane sections to be validated.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_validate($form, $form_state);
  }
}

/**
 * Submit callback of the settings form.
 */
function paddle_mailchimp_signup_form_content_type_edit_form_submit($form, &$form_state) {
  $form_state['conf']['signup_form'] = $form_state['values']['signup_form'];

  // Allow the form elements provided by pane sections to be saved.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_submit($form, $form_state);
  }
}
