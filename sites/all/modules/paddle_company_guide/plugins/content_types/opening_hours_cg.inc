<?php

/**
 * @file
 * CTools content type plugin to embed the opening hours of Company pages.
 */

$plugin = array(
  'title' => t('Company opening hours'),
  'description' => t('Displays the opening hours of a company.'),
  'category' => t('Paddle Company Guide'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'paddle_panes sections' => FALSE,
);

/**
 * Render the content type.
 */
function paddle_company_guide_opening_hours_cg_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $block->content = '';
  $variables = array();

  $node = $context->data;
  $wrapper = entity_metadata_wrapper('node', $node);

  $variables['current_week'][] = opening_hours_sets_get_weekday($wrapper, 'field_ous_monday', t('Monday'));
  $variables['current_week'][] = opening_hours_sets_get_weekday($wrapper, 'field_ous_tuesday', t('Tuesday'));
  $variables['current_week'][] = opening_hours_sets_get_weekday($wrapper, 'field_ous_wednesday', t('Wednesday'));
  $variables['current_week'][] = opening_hours_sets_get_weekday($wrapper, 'field_ous_thursday', t('Thursday'));
  $variables['current_week'][] = opening_hours_sets_get_weekday($wrapper, 'field_ous_friday', t('Friday'));
  $variables['current_week'][] = opening_hours_sets_get_weekday($wrapper, 'field_ous_saturday', t('Saturday'));
  $variables['current_week'][] = opening_hours_sets_get_weekday($wrapper, 'field_ous_sunday', t('Sunday'));

  if (!empty($variables) && !paddle_company_guide_opening_hours_week_empty($variables['current_week'])) {
    drupal_add_js(drupal_get_path('module', 'opening_hours_sets') . '/js/opening_hours.js', 'file');
    $block->content = theme('opening_hours_info', $variables);
  }

  return $block;
}
