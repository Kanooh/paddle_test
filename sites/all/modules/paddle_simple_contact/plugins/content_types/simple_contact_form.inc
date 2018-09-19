<?php

/**
 * @file
 * Plugin to show a simple contact form of a specific node.
 */

/**
 * Plugin definition.
 */
$plugin = array(
  'title' => t('Simple contact form'),
  'no title override' => TRUE,
  'description' => t('Add a simple contact form.'),
  'category' => t('Paddle Content Manager'),
  'editor render callback' => 'paddle_simple_contact_simple_contact_form_content_type_editor_render',
  'defaults' => array(
    'node' => 0,
  ),
  'paddle_panes sections' => TRUE,
  'reference tracker' => array(
    'node' => array(
      'value_type' => 'entity_id',
      'reference_entity_type' => 'node',
    ),
  ),
);

/**
 * Render callback for the pane to display in the panels editor.
 *
 * Note that this is supposed to return a string, as it overrides the content
 * property of the content of the block rendered by the default callback.
 */
function paddle_simple_contact_simple_contact_form_content_type_editor_render($subtype, $conf, $args, $context) {
  $output = '';

  if (!empty($conf['node']) && $node = node_load($conf['node'], NULL, TRUE)) {
    $output .= _paddle_simple_contact_render_pane_content($node, TRUE);
  }

  // Allow the pane sections to contribute output.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_render($output, $conf, $context);
  }

  return $output;
}

/**
 * Render callback.
 */
function paddle_simple_contact_simple_contact_form_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';

  $output = '';

  if (!empty($conf['node']) && $node = node_load($conf['node'])) {
    $output .= _paddle_simple_contact_render_pane_content($node);
  }

  // Allow the pane sections to contribute output.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_render($output, $conf, $context);
  }

  $block->content = $output;
  return $block;
}

/**
 * Renders the node for the pane.
 *
 * @param object $node
 *   Node object to render.
 * @param bool $disabled
 *   If set to TRUE, this will disable all fields in the form.
 *
 * @return string
 *   Rendered node in HTML.
 */
function _paddle_simple_contact_render_pane_content($node, $disabled = FALSE) {
  if (!node_access('view', $node)) {
    return '';
  }

  $node_view = node_view($node, 'node_content_pane_full');
  if ($disabled && isset($node_view['field_paddle_contact_form'])) {
    // @todo Find out why using #disabled doesn't work here.
    $node_view['field_paddle_contact_form'][0]['name']['#attributes']['disabled'] = TRUE;
    $node_view['field_paddle_contact_form'][0]['email']['#attributes']['disabled'] = TRUE;
    $node_view['field_paddle_contact_form'][0]['subject']['#attributes']['disabled'] = TRUE;
    $node_view['field_paddle_contact_form'][0]['body']['#attributes']['disabled'] = TRUE;
    $node_view['field_paddle_contact_form'][0]['actions']['submit']['#attributes']['disabled'] = TRUE;
  }

  return render($node_view);
}

/**
 * Settings form builder callback.
 */
function paddle_simple_contact_simple_contact_form_content_type_edit_form($form, &$form_state) {
  $form['section_body'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#weight' => -50,
    '#attributes' => array(
      'class' => array(drupal_html_class('pane-section-body')),
    ),
  );

  $language = $form_state['contexts']['panelizer']->data->language;
  $node_options = array();
  $query = new EntityFieldQuery();
  $query->entityCondition('entity_type', 'node')
    ->entityCondition('bundle', 'simple_contact_page')
    ->propertyCondition('language', $language, '=')
    ->propertyOrderBy('title');
  $results = $query->execute();

  if (isset($results['node'])) {
    $nids = array_keys($results['node']);
    $nodes = node_load_multiple($nids);
    foreach ($nodes as $node) {
      // Filter the archived node out.
      if (!empty($node->workbench_moderation['current']->state) && $node->workbench_moderation['current']->state != 'archived') {
        $node_options[$node->nid] = $node->title;
      }
    }
  }

  $form['section_body']['node'] = array(
    '#title' => t('Select a simple contact page'),
    '#type' => 'select',
    '#options' => $node_options,
    '#default_value' => $form_state['conf']['node'],
    '#empty_value' => 0,
    '#empty_option' => '',
  );

  // Include the form elements for the pane sections in the form.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_build_edit_form($form, $form_state);
  }

  form_load_include($form_state, 'inc', 'paddle_simple_contact', 'plugins/content_types/simple_contact_form');

  return $form;
}

/**
 * Validate handler for the edit form.
 */
function paddle_simple_contact_simple_contact_form_content_type_edit_form_validate($form, &$form_state) {
  // Allow the form elements provided by pane sections to be validated.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_validate($form, $form_state);
  }
}

/**
 * Submit callback of the settings form.
 */
function paddle_simple_contact_simple_contact_form_content_type_edit_form_submit($form, &$form_state) {
  $form_state['conf']['node'] = $form_state['values']['node'];

  // Allow the form elements provided by pane sections to be saved.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_submit($form, $form_state);
  }
}
