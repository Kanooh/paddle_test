<?php

/**
 * @file
 * CTools content type plugin to embed news.
 */

$plugin = array(
  'title' => t('Add news content'),
  'description' => t('Add news content.'),
  'single' => TRUE,
  'no title override' => TRUE,
  'defaults' => array(),
  'category' => array(t('Paddle Landing Page'), -9),
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
function paddle_news_paddle_news_content_type_render($subtype, $conf, $args, $context) {
  // Return the output as a block.
  $block = new stdClass();
  $block->title = '';
  $block->content = '';

  if (!empty($conf['node']) && preg_match('/node\/(\d+)/', $conf['node'], $matches)) {
    $nid = $matches[1];
    $node = node_load($nid);
  }

  // Do not output anything if the node is invalid or the user has no access.
  if (empty($node) || !node_access('view', $node)) {
    return $block;
  }

  $view_mode = $conf['view_mode'];
  $node_view = node_view($node, $view_mode);
  $block->content .= render($node_view);

  // Allow the pane sections to contribute output.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_render($block->content, $conf, $context);

  return $block;
}

/**
 * Edit form callback for the content type.
 */
function paddle_news_paddle_news_content_type_edit_form($form, &$form_state) {
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

  // Add an autocomplete field for selecting a node.
  $form['section_body']['node'] = array(
    '#type' => 'textfield',
    '#title' => t('Page'),
    '#required' => TRUE,
    '#default_value' => isset($conf['node']) ? $conf['node'] : '',
    '#size' => 60,
    '#maxlength' => 300,
    '#autocomplete_path' => 'admin/paddle_panes/node_autocomplete_callback/' . $context_language . '/news_item',
    '#attributes' => array('placeholder' => t('Search for page titles, or enter a node number directly: "node/123".')),
  );

  // Add the view mode.
  $form['section_body']['view_mode'] = array(
    '#type' => 'radios',
    '#title' => t('Display'),
    '#required' => TRUE,
    '#default_value' => isset($conf['view_mode']) ? $conf['view_mode'] : 'spotlight',
    '#options' => array(
      'spotlight' => t('In the spotlight'),
    ),
  );

  // Include the form elements for the pane sections in the form.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_build_edit_form($form, $form_state);

  return $form;
}

/**
 * Validate handler for the edit form.
 */
function paddle_news_paddle_news_content_type_edit_form_validate($form, &$form_state) {
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');

  // Only nodes which exist and have the two custom node content pane view modes
  // are allowed to be selected.
  $node = FALSE;
  if (!empty($form_state['values']['node'])) {
    if (preg_match('/node\/(\d+)/', $form_state['values']['node'], $matches)) {
      $nid = $matches[1];
      $node = node_load($nid);
    }
  }

  if (empty($node)) {
    form_set_error('node', t('Please enter a valid page.'));
  }

  // Allow the form elements provided by pane sections to be validated.
  paddle_panes_pane_sections_validate($form, $form_state);
}

/**
 * Submit callback for the configuration form.
 */
function paddle_news_paddle_news_content_type_edit_form_submit($form, &$form_state) {
  foreach (element_children($form['pane_settings']['section_body']) as $key) {
    $form_state['conf'][$key] = $form_state['values'][$key];
  }

  // Allow the form elements provided by pane sections to be saved.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_submit($form, $form_state);
}
