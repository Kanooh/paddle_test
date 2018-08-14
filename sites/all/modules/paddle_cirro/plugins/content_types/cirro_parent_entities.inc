<?php

/**
 * @file
 * CTools content type plugin to embed the parent entities.
 */

$plugin = array(
  'title' => t('CIRRO Parent Entities'),
  'description' => t('Displays the parent entities of CIRRO Resources.'),
  'category' => t('Paddle CIRRO'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'paddle_panes sections' => TRUE,
);

/**
 * Render the content type.
 */
function paddle_cirro_cirro_parent_entities_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $block->content = '';
  $variables = array();

  $parent_entity_ids = paddle_cirro_get_parent_entity_ids($context->data->nid);

  if (!empty($parent_entity_ids)) {
    foreach ($parent_entity_ids as $parent_entity_id) {
      $parent_entity = node_load($parent_entity_id);

      if (!empty($parent_entity->title)) {
        $variables['resources'][$parent_entity_id]['title'] = l($parent_entity->title, 'node/' . $parent_entity_id);
      }

      // Retrieve a trimmed version of the body.
      $parent_view = node_view($parent_entity, 'listing_teaser');
      if (!empty($parent_view['body'])) {
        $variables['resources'][$parent_entity_id]['summary'] = $parent_view['body'];
      }
    }
  }

  if (!empty($variables)) {
    $output = theme('paddle_cirro_resources', $variables);

    // Allow the pane sections to contribute output.
    if (module_exists('paddle_panes')) {
      $conf['top'] = array(
        'enable_section' => 1,
        'section_text' => t('Parent methodologies'),
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
