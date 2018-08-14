<?php

/**
 * @file
 * CTools content type plugin to embed an maps search field.
 */

$plugin = array(
  'title' => t('Add an maps search field'),
  'no title override' => TRUE,
  'description' => t('Add an maps search field.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => t('Paddle Maps Search'),
  'paddle_panes sections' => TRUE,
  'reference tracker' => array(
    'node' => array(
      'value_type' => 'node_autocomplete',
    ),
  ),
);

/**
 * Render the content type.
 */
function paddle_maps_maps_search_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';

  if (!empty($conf['node']) && preg_match('/node\/(\d+)/', $conf['node'], $matches)) {
    $nid = $matches[1];
    $node = node_load($nid);
  }

  // Do not output anything if the node is invalid or the user has no access.
  if (empty($node) || !node_access('view', $node)) {
    return $block;
  }

  // Retrieve the search form pane configuration from the node, if available.
  $search_form_pane = _paddle_maps_get_maps_search_form_pane($node);

  // Double check if the search form pane is still enabled, as configuration for
  // the published maps search page might have been changed since
  // when this content type was edited.
  if (!$search_form_pane) {
    return $block;
  }

  // Retrieve the seach form.
  module_load_include('inc', 'paddle_maps', 'paddle_maps.forms');
  $form = drupal_get_form('paddle_maps_form', $node, $search_form_pane->configuration);

  // Render the form.
  $output = render($form);

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
function paddle_maps_maps_search_content_type_edit_form($form, &$form_state) {
  $conf = $form_state['conf'];

  $context_language = '';
  if (isset($form_state['contexts']['panelizer']->data->language)) {
    $context_language = $form_state['contexts']['panelizer']->data->language;
  }

  $form['section_body'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#weight' => -50,
    '#attributes' => array(
      'class' => array(drupal_html_class('pane-section-body')),
    ),
  );

  // Add an autocomplete field for selecting an maps search page node.
  $form['section_body']['node'] = array(
    '#type' => 'textfield',
    '#title' => t('Maps search page'),
    '#required' => TRUE,
    '#default_value' => isset($conf['node']) ? $conf['node'] : '',
    '#size' => 60,
    '#autocomplete_path' => 'admin/maps/node_autocomplete_callback/' . $context_language,
    '#attributes' => array('placeholder' => t('Search for page titles, or enter a node number directly: "node/123".')),
  );

  // Include the form elements for the pane sections in the form.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_build_edit_form($form, $form_state);
  }

  form_load_include($form_state, 'inc', 'paddle_maps', 'plugins/content_types/maps_search');

  return $form;
}

/**
 * Validate handler for the edit form.
 */
function paddle_maps_maps_search_content_type_edit_form_validate($form, &$form_state) {
  // Only nodes which exist are allowed to be selected.
  $node = FALSE;
  if (!empty($form_state['values']['node'])) {
    if (preg_match('/node\/(\d+)/', $form_state['values']['node'], $matches)) {
      $nid = $matches[1];
      $node = node_load($nid);
    }
  }

  if (empty($node) || $node->type != 'paddle_maps_page') {
    form_set_error('node', t('Please enter a valid Maps search page ID.'));
  }

  // Allow the form elements provided by pane sections to be validated.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_validate($form, $form_state);
  }
}

/**
 * Submit callback for the configuration form.
 */
function paddle_maps_maps_search_content_type_edit_form_submit($form, &$form_state) {
  foreach (element_children($form['pane_settings']['section_body']) as $key) {
    $form_state['conf'][$key] = $form_state['values'][$key];
  }

  // Allow the form elements provided by pane sections to be saved.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_submit($form, $form_state);
  }
}
