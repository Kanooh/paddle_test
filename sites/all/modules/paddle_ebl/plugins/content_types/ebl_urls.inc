<?php

/**
 * @file
 * CTools content type plugin to embed the external URLs of EBL publications.
 */

$plugin = array(
  'title' => t('EBL external URLs'),
  'description' => t('Show the external URLs of the EBL publication.'),
  'category' => t('Paddle EBL'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'paddle_panes sections' => FALSE,
);

/**
 * Render the content type.
 */
function paddle_ebl_ebl_urls_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $block->content = '';
  $variables = array();

  $wrapper = entity_metadata_wrapper('node', $context->data);

  // Retrieve the CIRRO external links.
  if (!empty($wrapper->field_paddle_ebl_external_urls) && !empty($wrapper->field_paddle_ebl_external_urls->value())) {
    $variables['links_list'] = $wrapper->field_paddle_ebl_external_urls->value();
  }

  if (!empty($variables)) {
    $output = theme('paddle_ebl_urls', $variables);

    // Allow the pane sections to contribute output.
    if (module_exists('paddle_panes')) {
      $conf['top'] = array(
        'enable_section' => 1,
        'section_text' => t('Related links'),
        'section_content_type' => 'text',
        'section_image' => array(
          'sid' => array(),
          'style' => '',
        ),
        'icon_image' => array(),
        'section_url_type' => 'no_link',
        'section_internal_url' => '',
        'section_external_url' => 'http://',
      );

      module_load_include('inc', 'paddle_panes', 'paddle_panes_pane_sections');
      paddle_panes_pane_sections_render($output, $conf, $context);
    }

    $block->content = $output;
  }

  return $block;
}
