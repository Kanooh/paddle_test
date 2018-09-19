<?php

/**
 * @file
 * CTools content type plugin to embed videos.
 */

$plugin = array(
  'title' => t('Add video'),
  'no title override' => TRUE,
  'description' => t('Add video.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => array(t('Paddle Landing Page'), -10),
  'paddle_panes sections' => TRUE,
  'edit form' => 'paddle_scald_video_add_video_content_type_edit_form',
  'configuration info' => 'paddle_scald_video_add_video_configuration_info',
  'paddle_panes sections' => TRUE,
  'reference tracker' => array(
    'video' => array(
      'value_type' => 'paddle_scald_atom',
    ),
  ),
);

/**
 * Render the content type.
 */
function paddle_scald_video_add_video_content_type_render($subtype, $conf, $args, $context) {
  // Return the output as a block.
  $block = new stdClass();
  $block->title = isset($conf['pane_title']) ? check_plain($conf['pane_title']) : '';

  // Add the video - this is required so it cannot be absent.
  $output = paddle_scald_video_ckeditor_html(scald_fetch($conf['video']));

  // Allow the pane sections to contribute output.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_render($output, $conf, $context);

  $block->content = $output;
  return $block;
}

/**
 * Edit form callback for the content type.
 */
function paddle_scald_video_add_video_content_type_edit_form($form, &$form_state) {
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

  $form['section_body']['video'] = array(
    '#title' => t('Video'),
    '#type' => 'paddle_scald_atom',
    '#required' => TRUE,
    '#button_text' => t('Choose a video...'),
    '#default_value' => isset($conf['video']) ? $conf['video'] : NULL,
    '#allowed_types' => array(
      'video',
    ),
  );

  // Include the form elements for the pane sections in the form.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_build_edit_form($form, $form_state);

  form_load_include($form_state, 'inc', 'paddle_scald_video', 'plugins/content_types/add_video');

  return $form;
}

/**
 * Validate handler for the edit form.
 */
function paddle_scald_video_add_video_content_type_edit_form_validate($form, &$form_state) {
  // Allow the form elements provided by pane sections to be validated.
  module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
  paddle_panes_pane_sections_validate($form, $form_state);
}

/**
 * Submit callback for the configuration form.
 */
function paddle_scald_video_add_video_content_type_edit_form_submit($form, &$form_state) {
  $form_state['conf']['video'] = $form_state['values']['video'][0];

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
function paddle_scald_video_add_video_configuration_info($conf) {
  $atom = scald_fetch($conf['video']);

  // Add the space at the end. Otherwise the thumbnail of the image is rendered.
  // We don't want this because it is being rendered as faulty HTML.
  return array($atom->title . ' ');
}
