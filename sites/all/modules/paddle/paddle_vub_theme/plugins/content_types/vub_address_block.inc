<?php

/**
 * @file
 * CTools content type plugin to embed custom content.
 *
 * This is similar to the CTools custom plugin, but with limited functionality
 * and a simpler user interface.
 */

$plugin = array(
  'title' => t('VUB address block'),
  'no title override' => TRUE,
  'description' => t('Adds the VUB address block.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => array(t('Paddle VUB Theme'), -10),
);

/**
 * Render the content type.
 */
function paddle_vub_theme_vub_address_block_content_type_render($subtype, $conf, $args, $context) {
  // Return the output as a block.
  $block = new stdClass();
  $block->title = '';
  $block->content = '';

  $conf['logo'] = theme('image', array('path' => base_path() . drupal_get_path('module', 'paddle_vub_theme') . '/logo/vub_logo-white.svg'));
  $output = theme('paddle_vub_address_block', $conf);
  $block->content = $output;

  return $block;
}

/**
 * Edit form callback for the content type.
 */
function paddle_vub_theme_vub_address_block_content_type_edit_form($form, &$form_state) {
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

  $form['section_body']['address'] = array(
    '#type' => 'container',
    '#attributes' => array('id' => array('vub_address')),
  );

  $form['section_body']['address']['thoroughfare'] = array(
    '#type' => 'textfield',
    '#title' => t('Thoroughfare'),
    '#default_value' => isset($conf['thoroughfare']) ? $conf['thoroughfare'] : 'Pleinlaan 2',
    '#size' => 60,
  );

  $form['section_body']['address']['postal_code'] = array(
    '#type' => 'textfield',
    '#title' => t('Postal Code'),
    '#default_value' => isset($conf['postal_code']) ? $conf['postal_code'] : '1050',
    '#size' => 10,
  );

  $form['section_body']['address']['city'] = array(
    '#type' => 'textfield',
    '#title' => t('City'),
    '#default_value' => isset($conf['city']) ? $conf['city'] : 'Brussel',
    '#size' => 10,
  );

  $form['section_body']['address']['phone_number'] = array(
    '#type' => 'textfield',
    '#title' => t('Phone Number'),
    '#default_value' => isset($conf['phone_number']) ? $conf['phone_number'] : '02/629.20.10',
    '#size' => 10,
  );

  $form['section_body']['address']['mail'] = array(
    '#type' => 'textfield',
    '#title' => t('E-mail'),
    '#default_value' => isset($conf['mail']) ? $conf['mail'] : 'info@vub.ac.be',
    '#size' => 10,
  );

  $form['section_body']['address']['website'] = array(
    '#type' => 'textfield',
    '#title' => t('Website'),
    '#default_value' => isset($conf['website']) ? $conf['website'] : 'http://www.vub.ac.be/',
    '#size' => 60,
  );

  return $form;
}

/**
 * Validate handler for the edit form.
 */
function paddle_vub_theme_vub_address_block_content_type_edit_form_validate($form, &$form_state) {
  if (!empty($form_state['values']['mail']) && $error = user_validate_mail($form_state['values']['mail'])) {
    form_set_error('site_mail', $error);
  }

  if ($form_state['values']['website'] !== '' && !valid_url($form_state['values']['website'], TRUE)) {
    form_error($element, t('The URL %url is not valid.', array('%url' => $form_state['values']['mail'])));
  }
}

/**
 * Submit callback for the configuration form.
 */
function paddle_vub_theme_vub_address_block_content_type_edit_form_submit($form, &$form_state) {
  foreach (element_children($form['section_body']['address']) as $key) {
    if (!empty($form_state['values'][$key])) {
      $form_state['conf'][$key] = $form_state['values'][$key];
    }
  }
}
