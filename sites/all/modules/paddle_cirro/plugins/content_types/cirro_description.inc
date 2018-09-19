<?php

/**
 * @file
 * CTools content type plugin to embed the basic description.
 */

$plugin = array(
  'title' => t('CIRRO Basic Description'),
  'description' => t('Show the basic information of the CIRRO resource.'),
  'category' => t('Paddle CIRRO'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'paddle_panes sections' => FALSE,
);

/**
 * Render the content type.
 */
function paddle_cirro_cirro_description_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $block->content = '';
  $variables = array();

  $wrapper = entity_metadata_wrapper('node', $context->data);

  if (!empty($wrapper->body) && !empty($wrapper->body->value())) {
    $variables['description'] = $wrapper->body->value()['value'];
  }

  // Can't check on !empty() values since a Boolean can either be TRUE or FALSE
  // and FALSE is seen as empty.
  if (!empty($wrapper->field_paddle_cirro_methodology) && ($wrapper->field_paddle_cirro_methodology->value() === FALSE || $wrapper->field_paddle_cirro_methodology->value() === TRUE)) {
    $variables['is_methodology'] = $wrapper->field_paddle_cirro_methodology->value();
  }

  if (!empty($variables)) {
    $block->content = theme('paddle_cirro_description', $variables);
  }

  return $block;
}
