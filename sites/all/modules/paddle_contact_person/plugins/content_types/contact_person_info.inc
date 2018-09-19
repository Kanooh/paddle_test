<?php

/**
 * @file
 * CTools content type plugin to embed a contact person info.
 */

$plugin = array(
  'title' => t('Contact person info'),
  'no title override' => TRUE,
  'description' => t('Contact person info.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => array(t('Miscellaneous'), -10),
  'edit form' => 'contact_person_info_content_type_edit_form',
  'render callback' => 'contact_person_info_content_type_render',
  'required context' => new ctools_context_required(t('Node'), 'node'),
);

/**
 * Render the content type.
 */
function contact_person_info_content_type_render($subtype, $conf, $args, $context) {
  // Return the output as a block.
  $block = new stdClass();
  $block->title = '';
  $block->content = '';

  $node = $context->data;
  $template = 'paddle_contact_person_page_info';

  $variables = paddle_contact_person_get_additional_field_values($node);

  // Allow other modules to alter the fields.
  drupal_alter('paddle_contact_person_page_info', $variables);

  $block->content = theme($template, $variables);

  return $block;
}

/**
 * Edit form.
 */
function contact_person_info_content_type_edit_form($form, &$form_state) {
  return $form;
}
