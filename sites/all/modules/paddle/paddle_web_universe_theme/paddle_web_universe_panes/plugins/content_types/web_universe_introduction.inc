<?php

/**
 * @file
 * CTools content type plugin to add an introduction component.
 */

$plugin = array(
  'title' => t('Add Introduction component'),
  'no title override' => TRUE,
  'description' => t('Add Introduction component.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => array(t('Paddle Web Universe'), -20),
  'paddle_panes sections' => FALSE,
);

/**
 * Render the content type.
 */
function paddle_web_universe_panes_web_universe_introduction_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $output = '';

  if (!empty($conf['title'])) {
    $variables['title'] = $conf['title'];
  }

  if (!empty($conf['body']['value'])) {
    $variables['body'] = $conf['body']['value'];
  }

  if (!empty($variables)) {
    $output = theme('paddle_web_universe_theme_introduction', $variables);
  }

  $block->content = $output;
  return $block;
}

/**
 * Edit form callback for the content type.
 */
function paddle_web_universe_panes_web_universe_introduction_content_type_edit_form($form, &$form_state) {
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

  $form['section_body']['title'] = array(
    '#title' => t('Introduction Title'),
    '#type' => 'textfield',
    '#weight' => 0,
    '#attributes' => array(
      'placeholder' => t('Enter a title.'),
    ),
    '#default_value' => isset($conf['title']) ? $conf['title'] : '',
  );

  $form['section_body']['body'] = array(
    '#title' => t('Introduction Body'),
    '#required' => TRUE,
    '#type' => 'text_format',
    '#default_value' => isset($conf['body']['value']) ? $conf['body']['value'] : '',
    '#format' => 'full_html'
  );
  // No need to show 'plain text' when 'full html' is allowed. Hide text format
  // selection, and related tips.
  $form['section_body']['body']['better_formats'] = array(
    '#show_selection' => FALSE,
    '#show_tips' => FALSE,
    '#show_tips_link' => FALSE,
  );

  return $form;
}

/**
 * Submit callback for the configuration form.
 */
function paddle_web_universe_panes_web_universe_introduction_content_type_edit_form_submit($form, &$form_state) {
  // Store all values in the pane configuration.
  foreach (element_children($form['section_body']) as $key) {
    $form_state['conf'][$key] = $form_state['values'][$key];
  }
}
