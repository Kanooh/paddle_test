<?php

/**
 * @file
 * CTools content type plugin to embed the status of an opening hours set.
 */

$plugin = array(
  'title' => t('Opening hours status'),
  'no title override' => TRUE,
  'description' => t('Whether the object belonging to the opening hours set is currently open or closed.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => array(t('Opening hours sets'), -10),
  'required context' => new ctools_context_required(t('Node'), 'node'),
);

/**
 * Render the content type.
 */
function opening_hours_sets_opening_hours_status_content_type_render($subtype, $conf, $args, $context) {

  $node = $context->data;
  $field_name = opening_hours_status_get_field_name($node->type);

  if ($field_name) {
    $item = field_get_items('node', $node, $field_name);

    if ($item) {
      // Return the output as a block.
      $block = new stdClass();
      $block->title = '';
      $block->block = '';
      $variables = array();
      $template = 'opening_hours_status';

      $wrapper = entity_metadata_wrapper('node', $node);
      $opening_hours_set = $wrapper->$field_name->value();

      if (isset($opening_hours_set)) {
        $ohs_wrapper = entity_metadata_wrapper('opening_hours_set', $opening_hours_set);
        $current_opening_hour = opening_hours_sets_get_current_opening_hour($ohs_wrapper);

        if (!$current_opening_hour || empty($current_opening_hour)) {
          $current_opening_status = t("Now closed");
        }
        else {
          $current_opening_status = t("Now open");
          $variables['current_opening_hour'] = $current_opening_hour['time'];
          $variables['description'] = $current_opening_hour['description'];
        }
        $variables['opening_hour_status'] = $current_opening_status;
      }

      // Allow other modules to alter the fields.
      drupal_alter('opening_hours_status', $variables);
      $block->content = theme($template, $variables);
      return $block;
    }
  }

  return FALSE;
}

/**
 * Edit form.
 */
function opening_hours_sets_opening_hours_status_content_type_edit_form($form, &$form_state) {
  return $form;
}
