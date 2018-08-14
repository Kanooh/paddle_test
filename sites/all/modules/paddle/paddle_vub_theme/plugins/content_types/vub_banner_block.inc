<?php

/**
 * @file
 * CTools content type plugin to embed custom content.
 *
 * This is similar to the CTools custom plugin, but with limited functionality
 * and a simpler user interface.
 */

$plugin = array(
  'title' => t('VUB banner block'),
  'no title override' => TRUE,
  'description' => t('Adds the VUB banner block.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => array(t('Paddle VUB Theme'), -10),
  'reference tracker' => array(
    'image_scald' => array(
      'value_type' => 'paddle_scald_atom',
    ),
  ),
);

/**
 * Render the content type.
 */
function paddle_vub_theme_vub_banner_block_content_type_render($subtype, $conf, $args, $context) {
  // Return the output as a block.
  $block = new stdClass();
  $block->title = '';
  $block->content = '';

  if (!empty($conf['image_scald']['sid'][0])) {
    $scald_atom = scald_atom_load($conf['image_scald']['sid'][0]);
    $image_path = $scald_atom->thumbnail_source;
    $styled_path = image_style_path($conf['image_scald']['style'], $image_path);

    if (!file_exists($styled_path)) {
      image_style_create_derivative(image_style_load($conf['image_scald']['style']), $image_path, $styled_path);
    }

    $variables['image'] = file_create_url($styled_path);
  }

  if (!empty($conf['title'])) {
    $variables['title'] = $conf['title'];
  }

  if (!empty($conf['subtitle'])) {
    $variables['subtitle'] = $conf['subtitle'];
  }

  if (!empty($conf['link_text'])) {
    $variables['link_text'] = $conf['link_text'];
  }

  if (!empty($conf['body'])) {
    $variables['body'] = $conf['body'];
  }

  // Check of there is a URL to put a link on the image.
  if (($conf['url_type'] == 'internal' && $conf['internal_url'] != '')) {
    $matches = array();
    $result = preg_match('/node\/(\d+)/', $conf['internal_url'], $matches);
    if ($result && strpos($matches[0], 'node/') === 0) {
      $variables['link_url'] = url($matches[0]);
    }
  }
  elseif ($conf['url_type'] == 'external' && $conf['external_url'] != '') {
    $variables['link_url'] = $conf['external_url'];
  }

  if (!empty($variables)) {
    $block->content = theme('paddle_vub_banner_block', $variables);
  }

  return $block;
}

/**
 * Edit form callback for the content type.
 */
function paddle_vub_theme_vub_banner_block_content_type_edit_form($form, &$form_state) {
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

  $form['section_body']['image_scald'] = array(
    '#title' => t('Background image'),
    '#type' => 'paddle_scald_image_atom',
    '#required' => TRUE,
    '#default_value' => isset($conf['image_scald']) ? $conf['image_scald'] : array(),
  );

  $form['section_body']['title'] = array(
    '#title' => t('Title'),
    '#type' => 'textfield',
    '#default_value' => isset($conf['title']) ? $conf['title'] : '',
  );

  $form['section_body']['subtitle'] = array(
    '#title' => t('Subtitle'),
    '#type' => 'textfield',
    '#default_value' => isset($conf['subtitle']) ? $conf['subtitle'] : '',
  );

  $form['section_body']['body'] = array(
    '#title' => t('Body'),
    '#type' => 'textarea',
    '#rows' => 3,
    '#default_value' => isset($conf['body']) ? $conf['body'] : '',
  );

  $form['section_body']['url_type'] = array(
    '#type' => 'radios',
    '#title' => t('Button link'),
    '#options' => array(
      'no_link' => t('No link'),
      'internal' => t('Internal link'),
      'external' => t('External link'),
    ),
    '#default_value' => isset($conf['url_type']) ? $conf['url_type'] : 'no_link',
  );

  $form['section_body']['internal_url'] = array(
    '#type' => 'textfield',
    '#title' => t('Node'),
    '#default_value' => isset($conf['internal_url']) ? $conf['internal_url'] : '',
    '#size' => 60,
    '#autocomplete_path' => 'admin/paddle_panes/node_autocomplete_callback/' . $context_language . '/all',
    '#states' => array(
      'visible' => array(':input[name="url_type"]' => array('value' => 'internal')),
    ),
  );

  $form['section_body']['external_url'] = array(
    '#type' => 'textfield',
    '#title' => t('External URL'),
    '#default_value' => isset($conf['external_url']) && url_is_external($conf['external_url']) !== FALSE ? $conf['external_url'] : 'http://',
    '#size' => 60,
    '#maxlength' => defined('PADDLE_CORE_EXTERNAL_URL_MAX_LENGTH') ? PADDLE_CORE_EXTERNAL_URL_MAX_LENGTH : 255,
    '#states' => array(
      'visible' => array(':input[name="url_type"]' => array('value' => 'external')),
    ),
  );

  $form['section_body']['link_text'] = array(
    '#title' => t('Button text'),
    '#type' => 'textfield',
    '#default_value' => isset($conf['link_text']) ? $conf['link_text'] : '',
    '#states' => array(
      'invisible' => array(':input[name="url_type"]' => array('value' => 'no_link')),
    ),
  );

  return $form;
}

/**
 * Submit callback for the configuration form.
 */
function paddle_vub_theme_vub_banner_block_content_type_edit_form_submit($form, &$form_state) {
  foreach (element_children($form['section_body']) as $key) {
    if (!empty($form_state['values'][$key])) {
      $form_state['conf'][$key] = $form_state['values'][$key];
    }
  }
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
function paddle_vub_theme_vub_banner_block_configuration_info($conf) {
  $atom = scald_fetch($conf['image_scald']['sid'][0]);

  // Add the space at the end. Otherwise the thumbnail of the image is rendered.
  // We don't want this because it is being rendered as faulty HTML.
  return array($atom->title . ' ');
}
