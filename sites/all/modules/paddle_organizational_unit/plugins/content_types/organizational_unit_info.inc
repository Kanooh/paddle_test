<?php

/**
 * @file
 * CTools content type plugin to embed an organizational unit info.
 */

$plugin = array(
  'title' => t('Organizational unit info'),
  'no title override' => TRUE,
  'description' => t('Organizational unit info.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => array(t('Miscellaneous'), -10),
  'edit form' => 'organizational_unit_info_content_type_edit_form',
  'render callback' => 'organizational_unit_info_content_type_render',
  'required context' => new ctools_context_required(t('Node'), 'node'),
);

/**
 * Render the content type.
 */
function organizational_unit_info_content_type_render($subtype, $conf, $args, $context) {
  // Return the output as a block.
  $block = new stdClass();
  $block->title = '';
  $block->block = '';

  /** @var EntityDrupalWrapper $wrapper */
  $wrapper = entity_metadata_wrapper('node', $context->data);
  $template = 'paddle_organizational_unit_page_info';

  $title = $wrapper->title->value();
  $variables['title'] = check_plain($title);

  if (!empty($wrapper->field_paddle_ou_email->value())) {
    $variables['email'] = check_plain($wrapper->field_paddle_ou_email->value());
  }

  if (!empty($wrapper->field_paddle_ou_phone->value())) {
    $variables['phone'] = check_plain($wrapper->field_paddle_ou_phone->value());
  }

  if (!empty($wrapper->field_paddle_ou_fax->value())) {
    $variables['fax'] = check_plain($wrapper->field_paddle_ou_fax->value());
  }

  // Allow other modules to alter the fields.
  drupal_alter('paddle_organizational_unit_page_info', $variables);

  $block->content = theme($template, $variables);

  return $block;
}

/**
 * Edit form.
 */
function organizational_unit_info_content_type_edit_form($form, &$form_state) {
  return $form;
}
