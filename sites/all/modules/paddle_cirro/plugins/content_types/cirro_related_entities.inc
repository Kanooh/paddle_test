<?php

/**
 * @file
 * CTools content type plugin to embed the related entities.
 */

$plugin = array(
  'title' => t('CIRRO Related Entities'),
  'description' => t('Displays the related entities of CIRRO Resources.'),
  'category' => t('Paddle CIRRO'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'paddle_panes sections' => TRUE,
);

/**
 * Render the content type.
 */
function paddle_cirro_cirro_related_entities_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $block->content = '';
  $variables = array();

  $related_entities = paddle_cirro_get_related_entities($context->data->nid);

  if (!empty($related_entities)) {
    foreach ($related_entities as $id => $related_entity) {

      if (!empty($related_entity->title)) {
        $variables['resources'][$id]['title'] = l($related_entity->title, 'node/' . $id);
      }

      // Retrieve a trimmed version of the body.
      $related_view = node_view($related_entity, 'listing_teaser');
      if (!empty($related_view['body'])) {
        $variables['resources'][$id]['summary'] = $related_view['body'];
      }
    }
  }

  if (!empty($variables)) {
    $output = theme('paddle_cirro_resources', $variables);

    // Allow the pane sections to contribute output.
    if (module_exists('paddle_panes')) {
      $conf['top'] = array(
        'enable_section' => 1,
        'section_text' => t('Related support packages'),
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
