<?php

/**
 * @file
 * CTools content type plugin which represents the left pane
 * of an offer content type.
 */

$plugin = array(
  'title' => t('Offer left pane'),
  'no title override' => TRUE,
  'description' => t('The content which is included in the left pane of the offer content type.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => array(t('Offers'), -10),
  'required context' => new ctools_context_required(t('Node'), 'node'),
);

/**
 * Render the content type.
 */
function paddle_holiday_participation_offer_left_pane_content_type_render($subtype, $conf, $args, $context) {
  // Return the output as a block.
  $block = new stdClass();
  $block->title = '';
  $block->content = '';
  $variables = array();

  $node = $context->data;
  $template = 'paddle_holiday_participation_offer_left_pane';
  $wrapper = entity_metadata_wrapper('node', $node);

  $validity_description = $wrapper->field_hp_validity_period_desc->value();

  if (!empty($validity_description)) {
    // This string needs to be cut in parts because it is a accumulation
    // of validity descriptions.
    $description_lines = explode("\n", $validity_description);

    foreach ($description_lines as $description_line) {
      if (!empty($description_line)) {
        $variables['validity']['description'][] = $description_line;
      }
    }
  }

  $opening_hours = $wrapper->field_hp_opening_hours->value();

  if (!empty($opening_hours)) {
    $variables['opening_hours'] = nl2br($opening_hours);
  }

  $residence_description = $wrapper->field_hp_residence_desc->value();

  if (!empty($residence_description)) {
    $variables['residence_description'] = nl2br($residence_description);
  }

  $on_the_spot_description = $wrapper->field_hp_on_the_spot_desc->value();

  if (!empty($on_the_spot_description)) {
    $variables['on_the_spot_description'] = nl2br($on_the_spot_description);
  }

  if (!empty($variables)) {
    $block->content = theme($template, $variables);
  }

  return $block;
}

/**
 * Edit form.
 */
function paddle_holiday_participation_offer_left_pane_content_type_edit_form($form, &$form_state) {
  return $form;
}
