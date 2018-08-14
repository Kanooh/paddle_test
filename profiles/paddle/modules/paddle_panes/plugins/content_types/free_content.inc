<?php

/**
 * @file
 * CTools content type plugin to embed custom content.
 *
 * This is similar to the CTools custom plugin, but with limited functionality
 * and a simpler user interface.
 */

$plugin = array(
  'title' => t('Custom content'),
  'no title override' => TRUE,
  'description' => t('Add free content.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => array(t('Paddle Landing Page'), -10),
  'paddle_panes sections' => TRUE,
  'reference tracker' => array(
    'free_content][value' => array(
      'value_type' => 'text',
    ),
  ),
  'configuration info' => 'paddle_panes_free_content_configuration_info',
);

/**
 * Render the content type.
 */
function paddle_panes_free_content_content_type_render($subtype, $conf, $args, $context) {
  // Return the output as a block.
  $block = new stdClass();
  $block->title = '';
  $block->content = '';

  if (!empty($conf['free_content'])) {
    $output = check_markup($conf['free_content']['value'], $conf['free_content']['format']);
    // Allow the pane sections to contribute output.
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_render($output, $conf, $context);

    $block->content = $output;
  }

  return $block;
}

/**
 * Edit form callback for the content type.
 */
function paddle_panes_free_content_content_type_edit_form($form, &$form_state) {
  $form['section_body'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#weight' => -50,
    '#attributes' => array(
      'class' => array(drupal_html_class('pane-section-body')),
    ),
  );

  $form['section_body']['free_content'] = array(
    '#type' => 'text_format',
    '#default_value' => isset($form_state['conf']['free_content']['value']) ? $form_state['conf']['free_content']['value'] : '',
  );

  // Include the form elements for the pane sections in the form.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_build_edit_form($form, $form_state);

  form_load_include($form_state, 'inc', 'paddle_panes', 'plugins/content_types/free_content');

  return $form;
}

/**
 * Validate handler for the edit form.
 */
function paddle_panes_free_content_content_type_edit_form_validate($form, &$form_state) {
  // Allow the form elements provided by pane sections to be validated.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_validate($form, $form_state);
}

/**
 * Submit callback for the configuration form.
 */
function paddle_panes_free_content_content_type_edit_form_submit($form, &$form_state) {
  foreach (element_children($form['pane_settings']['section_body']) as $key) {
    $form_state['conf'][$key] = $form_state['values'][$key];
  }

  // Allow the form elements provided by pane sections to be saved.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_submit($form, $form_state);
}

/**
 * Return the configuration options which are needed for later parsing.
 *
 * @param array $conf
 *   The configuration of the pane.
 *
 * @return array
 *   An array containing the values which will be used.
 */
function paddle_panes_free_content_configuration_info($conf) {
  return array(
    $conf['free_content']['value'],
  );
}
