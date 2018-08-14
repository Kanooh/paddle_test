<?php

/**
 * @file
 * CTools content type plugin to embed images.
 */

$plugin = array(
  'title' => t('Add image'),
  'no title override' => TRUE,
  'description' => t('Add image.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => array(t('Paddle Landing Page'), -10),
  'paddle_panes sections' => TRUE,
  'edit form' => 'paddle_panes_add_image_content_type_edit_form',
  'configuration info' => 'paddle_panes_add_image_configuration_info',
  'reference tracker' => array(
    'image_scald' => array(
      'value_type' => 'paddle_scald_atom',
    ),
    'internal_url' => array(
      'value_type' => 'conditional_node_autocomplete',
      'conditional element' => 'url_type',
      'conditional value' => 'internal',
    ),
  ),
);

/**
 * Render the content type.
 */
function paddle_panes_add_image_content_type_render($subtype, $conf, $args, $context) {
  $library = libraries_load('colorbox');

  // Return the output as a block.
  $block = new stdClass();
  $block->title = isset($conf['pane_title']) ? check_plain($conf['pane_title']) : '';

  // Take into account old configurations.
  if (!is_array($conf['image_scald'])) {
    $conf['image_scald'] = array('sid' => (array) $conf['image_scald'], 'style' => '');
  }

  // Add the image - this is required so it cannot be absent.
  $atom = scald_fetch($conf['image_scald']['sid'][0]);
  $output = theme('paddle_scald_render_atom', array('atom' => $atom, 'image_style' => $conf['image_scald']['style']));

  // Check of there is a URL to put a link on the image.
  if (($conf['url_type'] == 'internal' && $conf['internal_url'] != '')) {
    $matches = array();
    $result = preg_match('/node\/(\d+)/', $conf['internal_url'], $matches);
    if ($result && strpos($matches[0], 'node/') === 0) {
      $output = l($output, $matches[0], array('html' => TRUE));
    }
  }
  elseif ($conf['url_type'] == 'external' && $conf['external_url'] != '') {
    $output = l($output, $conf['external_url'], array('html' => TRUE, 'external' => TRUE));
  }
  elseif ($conf['url_type'] == 'colorbox' && $library['installed']) {
    // Add the colorbox JS file.
    drupal_add_js(drupal_get_path('module', 'paddle_scald_image') . '/js/paddle_scald_image_colorbox.js', 'file');

    // Specify the attributes.
    $attributes = array('class' => array('colorbox-link'));

    $output = l($output, file_create_url($atom->file_source), array('attributes' => $attributes, 'html' => TRUE));
  }

  // Render the caption.
  if (!empty($conf['caption_checkbox']) && $conf['caption_checkbox'] && !empty($conf['caption_textarea'])) {
    $output .= theme('paddle_panes_image_caption', array('caption_text' => $conf['caption_textarea']));
  }

  // Render the pane content inside a figure element.
  $output = theme('paddle_panes_image_pane', array('pane_content' => $output));

  // Allow the pane sections to contribute output.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_render($output, $conf, $context);

  $block->content = $output;
  return $block;
}

/**
 * Edit form callback for the content type.
 */
function paddle_panes_add_image_content_type_edit_form($form, &$form_state) {
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

  // Take in account old values saved before the crop ratio was introduced.
  $image_value = isset($conf['image_scald']) ? $conf['image_scald'] : array();
  $image_value = is_array($image_value) ? $image_value : array('sid' => $image_value, 'style' => '');

  $form['section_body']['image_scald'] = array(
    '#title' => t('Image file'),
    '#type' => 'paddle_scald_image_atom',
    '#required' => TRUE,
    '#default_value' => $image_value,
  );

  $form['section_body']['url_wrapper'] = array(
    '#type' => 'fieldset',
    '#title' => t('Image link'),
  );

  $form['section_body']['url_wrapper']['url_type'] = array(
    '#type' => 'radios',
    '#title' => t('Link'),
    '#options' => array(
      'no_link' => t('No link'),
      'internal' => t('Internal link'),
      'external' => t('External link'),
    ),
    '#default_value' => isset($conf['url_type']) ? $conf['url_type'] : 'no_link',
  );

  $library = libraries_load('colorbox');
  if ($library['installed']) {
    $form['section_body']['url_wrapper']['url_type']['#options']['colorbox'] = t('Use Lightbox');
  }

  $form['section_body']['url_wrapper']['internal_url'] = array(
    '#type' => 'textfield',
    '#title' => t('Node'),
    '#default_value' => isset($conf['internal_url']) ? $conf['internal_url'] : '',
    '#size' => 60,
    '#autocomplete_path' => 'admin/paddle_panes/node_autocomplete_callback/' . $context_language . '/all',
    '#states' => array(
      'visible' => array(':input[name="url_type"]' => array('value' => 'internal')),
    ),
  );

  $form['section_body']['url_wrapper']['external_url'] = array(
    '#type' => 'textfield',
    '#title' => t('External URL'),
    '#default_value' => isset($conf['external_url']) && url_is_external($conf['external_url']) !== FALSE ? $conf['external_url'] : 'http://',
    '#size' => 60,
    '#maxlength' => defined('PADDLE_CORE_EXTERNAL_URL_MAX_LENGTH') ? PADDLE_CORE_EXTERNAL_URL_MAX_LENGTH : 255,
    '#states' => array(
      'visible' => array(':input[name="url_type"]' => array('value' => 'external')),
    ),
  );

  // Create the caption form elements.
  $form['section_body']['caption'] = array(
    '#type' => 'fieldset',
    '#title' => t('Image caption'),
  );

  $form['section_body']['caption']['caption_checkbox'] = array(
    '#type' => 'checkbox',
    '#title' => t('Activate'),
    '#default_value' => isset($conf['caption_checkbox']) ? $conf['caption_checkbox'] : FALSE,
  );

  $form['section_body']['caption']['caption_textarea'] = array(
    '#type' => 'textarea',
    '#maxlength' => 210,
    '#attributes' => array('maxlength' => 210),
    '#title' => t('Caption text'),
    '#default_value' => isset($conf['caption_textarea']) ? $conf['caption_textarea'] : '',
    '#states' => array(
      'visible' => array(':input[name="caption_checkbox"]' => array('checked' => TRUE)),
    ),
  );

  // Include the form elements for the pane sections in the form.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_build_edit_form($form, $form_state);

  form_load_include($form_state, 'inc', 'paddle_panes', 'plugins/content_types/add_image');

  return $form;
}

/**
 * Validate handler for the edit form.
 */
function paddle_panes_add_image_content_type_edit_form_validate($form, &$form_state) {
  // Allow the form elements provided by pane sections to be validated.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_validate($form, $form_state);
}

/**
 * Submit callback for the configuration form.
 */
function paddle_panes_add_image_content_type_edit_form_submit($form, &$form_state) {
  $values = $form_state['values'];
  $form_state['conf']['image_scald'] = $values['image_scald'];
  $form_state['conf']['url_type'] = $values['url_type'];
  $form_state['conf']['internal_url'] = $values['internal_url'];
  $form_state['conf']['external_url'] = $values['external_url'];
  $form_state['conf']['caption_checkbox'] = $values['caption_checkbox'];
  // No HTML is allowed in the caption text area.
  $form_state['conf']['caption_textarea'] = strip_tags($values['caption_textarea']);

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
function paddle_panes_add_image_configuration_info($conf) {
  // Take into account old configurations.
  if (!is_array($conf['image_scald'])) {
    $conf['image_scald'] = array('sid' => (array) $conf['image_scald']);
  }

  $atom = scald_fetch($conf['image_scald']['sid'][0]);

  // Add the space at the end. Otherwise the thumbnail of the image is rendered.
  // We don't want this because it is being rendered as faulty HTML.
  return array($atom->title . ' ');
}
