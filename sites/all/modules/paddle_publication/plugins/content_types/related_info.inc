<?php

/**
 * @file
 * CTools content type plugin to embed the Related info.
 */

$plugin = array(
  'title' => t('Related info'),
  'description' => t('Related information of the publication.'),
  'category' => t('Paddle Publication'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'paddle_panes sections' => TRUE,
);

/**
 * Render the content type.
 */
function paddle_publication_related_info_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $block->content = '';

  $wrapper = entity_metadata_wrapper('node', $context->data);
  $template = 'paddle_publication_related_info';

  if (!empty($wrapper->field_paddle_kce_collections->value())) {
    $variables['reference_collection'] = $wrapper->field_paddle_kce_collections->value()->name;
  }

  if (!empty($wrapper->field_paddle_kce_number) && !empty($wrapper->field_paddle_kce_number->value())) {
    $variables['reference_number'] = check_plain($wrapper->field_paddle_kce_number->value());
  }

  if (!empty($wrapper->field_paddle_kce_publication_y) && !empty($wrapper->field_paddle_kce_publication_y->value())) {
    $publication_year_vocabulary_term = $wrapper->field_paddle_kce_publication_y->value();
    $variables['publication_year'] = theme('paddle_publication_terms', array('category' => 'publication_year', 'terms' => array($publication_year_vocabulary_term->tid => $publication_year_vocabulary_term)));
  }

  if (!empty($wrapper->field_paddle_kce_type) && !empty($wrapper->field_paddle_kce_type->value())) {
    $variables['type'] = check_plain($wrapper->field_paddle_kce_type->value());
  }

  if (!empty($wrapper->field_paddle_kce_doc_lang) && !empty($wrapper->field_paddle_kce_doc_lang->value())) {
    $variables['language'] = $wrapper->field_paddle_kce_doc_lang->value();
  }

  if (!empty($wrapper->field_paddle_kce_authors) && !empty($wrapper->field_paddle_kce_authors->value())) {
    $authors_vocabulary_terms = $wrapper->field_paddle_kce_authors->value();
    $variables['authors'] = theme('paddle_publication_terms', array('category' => 'authors', 'terms' => $authors_vocabulary_terms));
  }

  if (!empty($wrapper->field_paddle_kce_keywords) && !empty($wrapper->field_paddle_kce_keywords->value())) {
    $keywords_vocabulary_terms = $wrapper->field_paddle_kce_keywords->value();
    $variables['keywords'] = theme('paddle_publication_terms', array('category' => 'keywords', 'terms' => $keywords_vocabulary_terms));
  }

  // Allow other modules to alter the fields.
  drupal_alter('paddle_publication_related_info', $variables);

  // Only render the pane if there is content in it.
  if (!empty($variables)) {
    $block->content = theme($template, $variables);
  }

  return $block;
}
