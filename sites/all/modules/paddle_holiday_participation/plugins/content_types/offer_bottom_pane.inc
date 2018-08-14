<?php

/**
 * @file
 * CTools content type plugin which represents the bottom pane
 * of an offer content type.
 */

$plugin = array(
  'title' => t('Offer bottom pane'),
  'no title override' => TRUE,
  'description' => t('The content which is included in the bottom pane of the offer content type.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => array(t('Offers'), -10),
  'required context' => new ctools_context_required(t('Node'), 'node'),
);

/**
 * Render the content type.
 */
function paddle_holiday_participation_offer_bottom_pane_content_type_render($subtype, $conf, $args, $context) {
  // Return the output as a block.
  $block = new stdClass();
  $block->title = '';
  $block->content = '';
  $variables = array();

  $node = $context->data;
  $template = 'paddle_holiday_participation_offer_bottom_pane';
  $wrapper = entity_metadata_wrapper('node', $node);

  $surroundings_description = $wrapper->field_hp_surroundings_desc->value();

  if (!empty($surroundings_description)) {
    // This string needs to be cut in parts because it is a accumulation
    // of surrounding places of interest.
    $description_lines = explode("\n", $surroundings_description);

    foreach ($description_lines as $description_line) {
      if (!empty($description_line)) {
        $variables['surroundings_description'][] = $description_line;
      }
    }
  }

  $private_transport_description = $wrapper->field_hp_private_transport_desc->value();
  $public_transport_description = $wrapper->field_hp_public_transport_desc->value();

  if (!empty($private_transport_description)) {
    // This string needs to be cut in parts because it is a accumulation
    // of private transport directions.
    $private_transport_lines = explode("\n", $private_transport_description);

    foreach ($private_transport_lines as $private_transport_line) {
      if (!empty($private_transport_line)) {
        $variables['practical_info']['private_transport'][] = $private_transport_line;
      }
    }
  }

  if (!empty($public_transport_description)) {
    // This string needs to be cut in parts because it is a accumulation
    // of public transport directions.
    $public_transport_lines = explode("\n", $public_transport_description);

    foreach ($public_transport_lines as $public_transport_line) {
      if (!empty($public_transport_line)) {
        $variables['practical_info']['public_transport'][] = $public_transport_line;
      }
    }
  }

  if (!empty($variables)) {
    $block->content = theme($template, $variables);
  }

  return $block;
}

/**
 * Edit form.
 */
function paddle_holiday_participation_offer_bottom_pane_content_type_edit_form($form, &$form_state) {
  return $form;
}
