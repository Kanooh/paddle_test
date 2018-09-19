<?php

/**
 * @file
 * CTools content type plugin to embed the related terms.
 */

$plugin = array(
  'title' => t('CIRRO terms'),
  'description' => t('Taxonomy terms related to the CIRRO page.'),
  'category' => t('Paddle CIRRO'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'paddle_panes sections' => TRUE,
);

/**
 * Render the content type.
 */
function paddle_cirro_cirro_terms_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $block->content = '';
  $variables = array();

  $wrapper = entity_metadata_wrapper('node', $context->data);
  $vocabularies_fields = array(
    'paddle_cirro_action_strategies' => 'field_paddle_cirro_action_strats',
    'paddle_cirro_policy_themes' => 'field_paddle_cirro_policy_themes',
    'paddle_cirro_settings' => 'field_paddle_cirro_settings',
  );

  foreach ($vocabularies_fields as $vocabulary => $field) {
    if (!empty($wrapper->$field->value())) {
      $vocabulary_terms = array();
      foreach ($wrapper->$field->value() as $term) {
        // We want to filter out some terms.
        // We also only want terms which are the first hierarchical level.
        if (!in_array($term->name, paddle_cirro_get_disallowed_taxonomy_terms()) && empty(taxonomy_get_parents($term->tid))) {
          $vocabulary_terms[] = $term->name;
        }
      }
      // Order the terms alphabetically.
      sort($vocabulary_terms);
      $variables[$vocabulary] = implode(", ", $vocabulary_terms);
    }
  }

  if (!empty($variables)) {
    $block->content = theme('paddle_cirro_terms', $variables);
  }

  return $block;
}
