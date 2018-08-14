<?php

/**
 * @file
 * CTools content type plugin to embed an opening hours set.
 */

$plugin = array(
  'title' => t('Opening hours info'),
  'no title override' => TRUE,
  'description' => t('The opening hours of this content type.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => array(t('Opening hours sets'), -10),
  'required context' => new ctools_context_required(t('Node'), 'node'),
);

/**
 * Render the content type.
 */
function opening_hours_sets_opening_hours_content_type_render($subtype, $conf, $args, $context) {
  $node = $context->data;
  $field_name = opening_hours_status_get_field_name($node->type);

  if ($field_name) {
    $item = field_get_items('node', $node, $field_name);

    if ($item) {
      // Return the output as a block.
      $block = new stdClass();
      $block->title = '';
      $block->block = '';
      $template = 'opening_hours_info';

      $wrapper = entity_metadata_wrapper('node', $node);
      $opening_hours_set = $wrapper->$field_name->value();
      if (isset($opening_hours_set)) {
        $ohs_wrapper = entity_metadata_wrapper('opening_hours_set', $opening_hours_set);
        $weekdays = opening_hours_sets_get_weekdays($ohs_wrapper);
        $closing_days = opening_hours_sets_get_closing_days($ohs_wrapper);
        $exceptional_opening_hours = opening_hours_sets_get_exceptional_opening_hours($ohs_wrapper);
        $current_week = opening_hours_sets_get_current_week($ohs_wrapper);

        $variables = array(
          'closing_days' => $closing_days,
          'exceptional_opening_hours' => $exceptional_opening_hours,
          'weekdays' => $weekdays,
          'title' => t("Opening Hours"),
        );
        $variables['fieldsets'] = theme('opening_hours_sets_fieldsets', $variables);
        $variables['current_week'] = $current_week;

        drupal_add_js(drupal_get_path('module', 'opening_hours_sets') . '/js/opening_hours.js', 'file');
        // Allow other modules to alter the fields.
        drupal_alter('opening_hours_info', $variables);
        $block->content = theme($template, $variables);
        return $block;
      }
    }
  }
  return FALSE;
}

/**
 * Edit form.
 */
function opening_hours_sets_opening_hours_content_type_edit_form($form, &$form_state) {
  return $form;
}
