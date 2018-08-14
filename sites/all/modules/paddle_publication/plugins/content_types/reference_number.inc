<?php

/**
 * @file
 * CTools content type plugin to embed the Reference number.
 */

$plugin = array(
  'title' => t('Reference number'),
  'description' => t('Reference number of the publication.'),
  'category' => t('Paddle Publication'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'paddle_panes sections' => TRUE,
);

/**
 * Render the content type.
 */
function paddle_publication_reference_number_content_type_render($subtype, $conf, $args, $context) {
  $wrapper = entity_metadata_wrapper('node', $context->data);

  $block = new stdClass();
  $block->title = '';
  $block->content = '';
  $block->content = theme(
    'paddle_publication_reference_number',
    array(
      'publication_txt_export' => l(
        t('Export the bibliographical reference (.RIS)'),
        '/risexport.txt/' . $wrapper->nid->value()
      )
    )
  );

  return $block;
}
