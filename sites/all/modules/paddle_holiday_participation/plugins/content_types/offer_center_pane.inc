<?php

/**
 * @file
 * CTools content type plugin which represents the center pane
 * of an offer content type.
 */

$plugin = array(
  'title' => t('Offer center pane'),
  'no title override' => TRUE,
  'description' => t('The content which is included in the center pane of the offer content type.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => array(t('Offers'), -10),
  'required context' => new ctools_context_required(t('Node'), 'node'),
);

/**
 * Render the content type.
 */
function paddle_holiday_participation_offer_center_pane_content_type_render($subtype, $conf, $args, $context) {
  // Return the output as a block.
  $block = new stdClass();
  $block->title = '';
  $block->content = '';
  $variables = array();
  $assets_path = drupal_get_path('module', 'paddle_holiday_participation') . '/assets/';

  $node = $context->data;
  $template = 'paddle_holiday_participation_offer_center_pane';
  $wrapper = entity_metadata_wrapper('node', $node);

  $room_and_board_input = $wrapper->field_hp_room_and_board->value();

  if (!empty($room_and_board_input)) {
    foreach ($room_and_board_input as $boarding_item) {
      $variables['room_and_board'][] = array(
        'label' => paddle_holiday_participation_get_multivalue_label($boarding_item, 'field_hp_room_and_board'),
        'image_path' => $assets_path . $boarding_item . '.png',
      );
    }
  }

  $individual_reservation_period = $wrapper->field_hp_resv_period_individual->value();
  $group_reservation_period = $wrapper->field_hp_resv_period_group->value();
  $reservation_comments = $wrapper->field_hp_resv_comments->value();

  if (!empty($individual_reservation_period)) {
    $variables['reservation']['individual'] = $individual_reservation_period;
  }

  if (!empty($group_reservation_period)) {
    $variables['reservation']['group'] = $group_reservation_period;
  }

  if (!empty($reservation_comments)) {
    $variables['reservation']['comments'] = $reservation_comments;
  }

  $label_input = $wrapper->field_hp_labels->value();

  if (!empty($label_input)) {
    foreach ($label_input as $label_item) {
      if ($label_item !== '0') {
        $variables['labels'][] = array(
          'label' => paddle_holiday_participation_get_multivalue_label($label_item, 'field_hp_labels'),
          'image_path' => $assets_path . $label_item . '.png',
        );
      }
    }
  }

  $block->content = theme($template, $variables);

  return $block;
}

/**
 * Edit form.
 */
function paddle_holiday_participation_offer_center_pane_content_type_edit_form($form, &$form_state) {
  return $form;
}
