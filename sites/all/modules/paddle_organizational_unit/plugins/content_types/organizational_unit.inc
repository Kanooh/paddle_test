<?php

/**
 * @file
 * CTools content type plugin to embed an organizational unit.
 */

$plugin = array(
  'title' => t('Add organizational unit'),
  'no title override' => TRUE,
  'description' => t('Add organizational unit.'),
  'single' => TRUE,
  'defaults' => array(
    'view_mode' => 'short',
  ),
  'category' => array(t('Paddle Landing Page'), -10),
  'edit form' => 'paddle_organizational_unit_content_type_edit_form',
  'render callback' => 'paddle_organizational_unit_content_type_render',
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
function paddle_organizational_unit_content_type_render($subtype, $conf, $args, $context) {
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

  $view_mode = isset($conf['view_mode']) ? $conf['view_mode'] : 'short';
  $template = 'paddle_organizational_unit_' . $view_mode;

  $wrapper = entity_metadata_wrapper('node', $node);
  $email = $wrapper->field_paddle_ou_email->value();
  $fax = $wrapper->field_paddle_ou_fax->value();
  $website = !empty($node->field_paddle_ou_website['und'][0]['value']) ? check_plain($node->field_paddle_ou_website['und'][0]['value']) : '';
  $website_simple = preg_replace('#^https?://#', '', $website);
  $phone = $wrapper->field_paddle_ou_phone->value();
  $address = $wrapper->field_paddle_ou_address->value();
  $empty_address = paddle_core_is_address_empty($address);

  $variables = array(
    'name' => check_plain($node->title),
    'email' => $email,
    'website_simple' => $website_simple,
    'website' => $website,
    'phone' => $phone,
    'fax' => $fax,
    'parents' => array(
      $wrapper->field_paddle_ou_parent_1->value(),
      $wrapper->field_paddle_ou_parent_2->value(),
    ),
  );

  $formatted_address = '';
  if (!$empty_address) {
    $addresses = field_get_items('node', $node, 'field_paddle_ou_address');
    $formatted_address = field_view_value('node', $node, 'field_paddle_ou_address', $addresses[0]);
    $formatted_address = render($formatted_address);

    $variables['address'] = $address;
    $variables['address_formatted'] = render($formatted_address);
  }

  // Retrieve the opening hours if there are some.
  if (module_exists('paddle_opening_hours')) {
    $field_name = opening_hours_status_get_field_name($node->type);
    if ($field_name) {
      $item = field_get_items('node', $node, $field_name);

      if ($item !== FALSE) {
        $wrapper = entity_metadata_wrapper('node', $node);
        $opening_hours_set = $wrapper->$field_name->value();

        $ohs_wrapper = entity_metadata_wrapper('opening_hours_set', $opening_hours_set);
        $current_week = opening_hours_sets_get_current_week($ohs_wrapper);
        $exceptional_opening_hours = opening_hours_sets_get_exceptional_opening_hours($ohs_wrapper);
        $closing_days = opening_hours_sets_get_closing_days($ohs_wrapper);
        drupal_add_js(drupal_get_path('module', 'opening_hours_sets') . '/js/opening_hours.js', 'file');

        $opening_hours_variables = array(
          'current_week' => $current_week,
          'closing_days' => $closing_days,
          'exceptional_opening_hours' => $exceptional_opening_hours,
        );
        $variables = array_merge($variables, $opening_hours_variables);
      }
    }
  }

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
function paddle_organizational_unit_content_type_edit_form($form, &$form_state) {
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

  // Add an autocomplete field for selecting an organizational unit.
  $form['section_body']['node'] = array(
    '#type' => 'textfield',
    '#title' => t('Organizational unit'),
    '#required' => TRUE,
    '#default_value' => isset($conf['node']) ? $conf['node'] : '',
    '#size' => 60,
    // The maximum length of the field is 1 title of max 255 characters + 20
    // characters for the (node/xxx) suffix.
    '#maxlength' => 255 + 20,
    '#autocomplete_path' => 'admin/organizational_unit/node_autocomplete_callback/' . $form_state['contexts']['panelizer']->data->language,
    '#attributes' => array('placeholder' => t('Search for page titles, or enter a node number directly: "node/123".')),
  );

  $t_options = array('context' => 'ou_view');
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

  // Only add this option if opening hours is enabled.
  if (module_exists('paddle_opening_hours')) {
    $form['section_body']['view_mode']['#options']['longOpeningHours'] = t('Long with opening hours', array(), $t_options);
  }

  // Include the form elements for the pane sections in the form.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_build_edit_form($form, $form_state);
  }

  form_load_include($form_state, 'inc', 'paddle_organizational_unit', 'plugins/content_types/organizational_unit');

  return $form;
}

/**
 * Validate handler for the edit form.
 */
function paddle_organizational_unit_content_type_edit_form_validate($form, &$form_state) {
  // Only nodes which exist are allowed to be selected.
  $node = FALSE;
  if (!empty($form_state['values']['node'])) {
    if (preg_match('/node\/(\d+)/', $form_state['values']['node'], $matches)) {
      $nid = $matches[1];
      $node = node_load($nid);
    }
  }

  if (empty($node) || $node->type != 'organizational_unit') {
    form_set_error('node', t('Please enter a valid organizational unit ID.'));
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
function paddle_organizational_unit_content_type_edit_form_submit($form, &$form_state) {
  foreach (element_children($form['pane_settings']['section_body']) as $key) {
    $form_state['conf'][$key] = $form_state['values'][$key];
  }

  // Allow the form elements provided by pane sections to be saved.
  if (module_exists('paddle_panes')) {
    module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
    paddle_panes_pane_sections_submit($form, $form_state);
  }
}
