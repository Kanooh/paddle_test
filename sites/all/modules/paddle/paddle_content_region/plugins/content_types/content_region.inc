<?php

/**
 * @file
 * CTools content type plugin to display content from a content region.
 */

$plugin = array(
  'title' => t('Region content'),
  'description' => t('Display content from a content region.'),
  'single' => TRUE,
  'render callback' => 'content_region_render',
  'no title override' => TRUE,
  'defaults' => array(),
  'edit form' => 'content_region_edit_form',
  'category' => array(t('Paddle Landing Page'), -9),
);

/**
 * Render the content type.
 */
function content_region_render($subtype, $conf, $args, $context) {
  if ($subtype == 'inherit') {
    global $language;

    if (paddle_content_region_get_inherit($conf['type'], $language->language)) {
      // Respect the inherit setting if wanted and needed.
      $conf['type'] = 'all_pages';
    }
  }

  // Return the output as a block.
  $block = new stdClass();
  $block->title = '';

  // Get the output of the panes with the correct parameters and render it in
  // the body.
  $output = paddle_content_region_get_panes_html($conf['type'], $conf['region']);

  $block->content = $output;

  return $block;
}

/**
 * Edit form callback for the content type.
 */
function content_region_edit_form($form, &$form_state) {
  $conf = $form_state['conf'];
  $form['pane_settings'] = array(
    '#type' => 'container',
    '#attributes' => array('class' => array('form-body')),
  );
  $form['pane_settings']['section_body'] = array(
    '#type' => 'fieldset',
    '#collapsible' => FALSE,
    '#collapsed' => FALSE,
    '#weight' => -50,
    '#attributes' => array(
      'class' => array(drupal_html_class('pane-section-body')),
    ),
  );

  $form['pane_settings']['section_body']['region'] = array(
    '#type' => 'radios',
    '#title' => t('Use:'),
    '#options' => paddle_content_region_get_regions(),
    '#default_value' => isset($conf['region']) ? $conf['region'] : 'right',
  );

  $options = array();
  foreach (paddle_content_region_get_allowed_node_types() as $key => $name) {
    $options[$key] = t('@type layout', array('@type' => $name));
  }

  $form['pane_settings']['section_body']['type'] = array(
    '#type' => 'radios',
    '#title' => t('From:'),
    '#options' => $options,
    '#default_value' => isset($conf['type']) ? $conf['type'] : 'all_pages',
  );

  $form['#attributes'] = array('class' => array('paddle-add-pane-form'));
  $form['buttons']['#attributes'] = array('class' => array('form-buttons'));

  return $form;
}

/**
 * Submit callback for the configuration form.
 */
function content_region_edit_form_submit($form, &$form_state) {
  foreach (array('region', 'type') as $key) {
    if (!empty($form_state['values'][$key])) {
      $form_state['conf'][$key] = $form_state['values'][$key];
    }
    else {
      $form_state['conf'][$key] = NULL;
    }
  }
}
