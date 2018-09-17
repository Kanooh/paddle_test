<?php

/**
 * @file
 * CTools content type plugin to embed the metadata of EBL publications.
 */

$plugin = array(
  'title' => t('EBL publication metadata'),
  'description' => t('Show the metadata of the EBL publication.'),
  'category' => t('Paddle EBL'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'paddle_panes sections' => FALSE,
);

/**
 * Render the content type.
 */
function paddle_ebl_ebl_metadata_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $block->content = '';
  $variables = array();

  $wrapper = entity_metadata_wrapper('node', $context->data);

  if (!empty($wrapper->field_paddle_ebl_publisher) && !empty($wrapper->field_paddle_ebl_publisher->value())) {
    $variables['publisher'] = $wrapper->field_paddle_ebl_publisher->value();
  }

  if (!empty($wrapper->field_paddle_ebl_pub_date) && !empty($wrapper->field_paddle_ebl_pub_date->value())) {
    $variables['publication_date'] = date('F Y', $wrapper->field_paddle_ebl_pub_date->value());
  }

  if (!empty($wrapper->field_paddle_ebl_themes) && !empty($wrapper->field_paddle_ebl_themes->value()) && !variable_get('paddle_ebl_hide_themes', FALSE)) {
    $variables['themes'] = $wrapper->field_paddle_ebl_themes->value();
  }

  if (!empty($wrapper->field_paddle_ebl_authors) && !empty($wrapper->field_paddle_ebl_authors->value())) {
    $variables['authors'] = $wrapper->field_paddle_ebl_authors->value();
  }

  if (!empty($wrapper->field_paddle_ebl_series) && !empty($wrapper->field_paddle_ebl_series->value())) {
    $variables['ebl_series'] = $wrapper->field_paddle_ebl_series->value();
  }

  if (!empty($wrapper->field_paddle_ebl_language) && !empty($wrapper->field_paddle_ebl_language->value())) {
    $language_objects = $wrapper->field_paddle_ebl_language->value();

    foreach ($language_objects as $language_object) {
      $languages[] = $language_object->name;
    }

    if (!empty($languages)) {
      $variables['languages'] = implode(', ', $languages);
    }
  }

  if (!empty($wrapper->field_paddle_ebl_document_type) && !empty($wrapper->field_paddle_ebl_document_type->value())) {
    $document_type = $wrapper->field_paddle_ebl_document_type->value();
    $variables['document_type'] = $document_type->name;
  }

  if (!empty($variables)) {
    $block->content = theme('paddle_ebl_metadata', $variables);
  }

  return $block;
}
