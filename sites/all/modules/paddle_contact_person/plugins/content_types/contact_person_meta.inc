<?php

/**
 * @file
 * CTools content type plugin to embed a contact person contact information.
 */

$plugin = array(
  'title' => t('Contact person contact information'),
  'no title override' => TRUE,
  'description' => t('Contact person contact information.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => array(t('Miscellaneous'), -10),
  'edit form' => 'contact_person_meta_content_type_edit_form',
  'render callback' => 'contact_person_meta_content_type_render',
  'required context' => new ctools_context_required(t('Node'), 'node'),
);

/**
 * Render the content type.
 */
function contact_person_meta_content_type_render($subtype, $conf, $args, $context) {
  // Return the output as a block.
  $block = new stdClass();
  $block->title = '';
  $block->content = '';

  $node = $context->data;

  $template = 'paddle_contact_person_page_meta';

  $variables = paddle_contact_person_get_additional_field_values($node);

  // We do not need the full name, first name and last name in the meta data.
  unset($variables['full_name']);
  unset($variables['first_name']);
  unset($variables['last_name']);

  if (!empty($variables)) {
    $block->content = theme($template, $variables);
  }

  return $block;
}

/**
 * Edit form.
 */
function contact_person_meta_content_type_edit_form($form, &$form_state) {
  return $form;
}
