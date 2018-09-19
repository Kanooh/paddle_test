<?php

/**
 * @file
 * CTools content type plugin to embed a contact person.
 */

$plugin = array(
  'title' => t('Contact person'),
  'no title override' => TRUE,
  'description' => t('Contact person.'),
  'single' => TRUE,
  'defaults' => array(
    'view_mode' => 'short',
  ),
  'category' => array(t('Paddle Landing Page'), -10),
  'edit form' => 'paddle_contact_person_content_type_edit_form',
  'render callback' => 'paddle_contact_person_content_type_render',
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
function paddle_contact_person_content_type_render($subtype, $conf, $args, $context) {
  // Return the output as a block.
  $block = new stdClass();
  $block->title = '';
  $block->block = '';

  if (!empty($conf['node']) && preg_match('/node\/(\d+)/', $conf['node'], $matches)) {
    $nid = $matches[1];
    $node = node_load($nid);
  }

  // Do not output anything if the node is invalid or the user has no access.
  if (empty($node) || !node_access('view', $node)) {
    return $block;
  }

  $variables = paddle_contact_person_get_additional_field_values($node);

  if (!empty($variables['organizations'])) {
    $collection = reset($variables['organizations']);

    $variables['function'] = $collection['function'];
    $variables['address'] = $collection['address'];
    $variables['email'] = $collection['email'];
    $variables['field_cp_office'] = $collection['office'];
    $variables['phone_office'] = $collection['phone'];
    $variables['mobile_office'] = $collection['mobile'];
  }

  $view_mode = isset($conf['view_mode']) ? $conf['view_mode'] : 'short';
  $template = 'paddle_contact_person_' . $view_mode;
  $output = theme($template, $variables);

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
function paddle_contact_person_content_type_edit_form($form, &$form_state) {
  $conf = $form_state['conf'];

  $form['section_body'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#weight' => -50,
    '#attributes' => array(
      'class' => array(drupal_html_class('pane-section-body')),
    ),
  );

  // Add an autocomplete field for selecting an contact person.
  $form['section_body']['node'] = array(
    '#type' => 'textfield',
    '#title' => t('Contact person'),
    '#required' => TRUE,
    '#default_value' => isset($conf['node']) ? $conf['node'] : '',
    '#size' => 60,
    // The maximum length of the field is 1 full name + 3 OU levels of 255
    // characters + 9 characters for the ' > ' separators + 20 characters for
    // the (node/xxx) suffix.
    '#maxlength' => 4 * 255 + 9 + 20,
    '#autocomplete_path' => 'admin/contact_person/node_autocomplete_callback/' . $form_state['contexts']['panelizer']->data->language,
    '#attributes' => array('placeholder' => t('Search for page titles, or enter a node number directly: "node/123".')),
  );

  $t_options = array('context' => 'cp_view');
  $form['section_body']['view_mode'] = array(
    '#type' => 'radios',
    '#title' => t('View'),
    '#required' => TRUE,
    '#default_value' => isset($conf['view_mode']) ? $conf['view_mode'] : 'short',
    '#options' => array(
      'short' => t('Short', array(), $t_options),
      'medium' => t('Medium', array(), $t_options),
      'long' => t('Long', array(), $t_options),
    ),
  );

  // Include the form elements for the pane sections in the form.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_build_edit_form($form, $form_state);
  }

  form_load_include($form_state, 'inc', 'paddle_contact_person', 'plugins/content_types/contact_person');

  return $form;
}

/**
 * Implements hook_form_FORM_ID_alter().
 */
function paddle_contact_person_form_paddle_contact_person_content_type_edit_form_alter(&$form, &$form_state, $form_id) {
  $tips = array(
    'short' => t('name, function and e-mail address'),
    'medium' => t('name, function, e-mail address, telephone number and mobile number'),
    'long' => t('name, function, e-mail address, telephone number, mobile number, office and address'),
  );
  foreach ($tips as $radio => $tip) {
    $form['pane_settings']['section_body']['view_mode']['#options'][$radio] .= theme('html_tag', array(
      'element' => array(
        '#tag' => 'span',
        '#attributes' => array(
          'class' => 'inline-tip',
        ),
        '#value' => ' (' . $tip . ')',
      ),
    ));
  }
}

/**
 * Validate handler for the edit form.
 */
function paddle_contact_person_content_type_edit_form_validate($form, &$form_state) {
  // Only nodes which exist are allowed to be selected.
  $node = FALSE;
  if (!empty($form_state['values']['node'])) {
    if (preg_match('/node\/(\d+)/', $form_state['values']['node'], $matches)) {
      $nid = $matches[1];
      $node = node_load($nid);
    }
  }

  if (empty($node) || $node->type != 'contact_person') {
    form_set_error('node', t('Please enter a valid contact person ID.'));
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
function paddle_contact_person_content_type_edit_form_submit($form, &$form_state) {
  foreach (element_children($form['pane_settings']['section_body']) as $key) {
    $form_state['conf'][$key] = $form_state['values'][$key];
  }

  // Allow the form elements provided by pane sections to be saved.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_submit($form, $form_state);
  }
}
