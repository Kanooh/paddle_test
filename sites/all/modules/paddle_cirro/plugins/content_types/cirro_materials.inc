<?php

/**
 * @file
 * CTools content type plugin to embed the Materials.
 */

$plugin = array(
  'title' => t('CIRRO Materials'),
  'description' => t('Links and Files which are available in CIRRO resources.'),
  'category' => t('Paddle CIRRO'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'paddle_panes sections' => TRUE,
);

/**
 * Render the content type.
 */
function paddle_cirro_cirro_materials_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $block->content = '';
  $variables = array();

  $wrapper = entity_metadata_wrapper('node', $context->data);

  // Retrieve the CIRRO external links.
  if (!empty($wrapper->field_paddle_cirro_links) && !empty($wrapper->field_paddle_cirro_links->value())) {
    $variables['links_list'] = $wrapper->field_paddle_cirro_links->value();
  }
  // Retrieve the CIRRO file download links.
  if (!empty($wrapper->field_paddle_cirro_files) && !empty($wrapper->field_paddle_cirro_files->value())) {
    $cirro_files = $wrapper->field_paddle_cirro_files->value();
    $list_items = array();

    foreach ($cirro_files as $cirro_file) {
      $path_info = pathinfo($cirro_file['title']);

      $file_extension = "";
      if (!empty($path_info['extension'])) {
        $file_extension = $path_info['extension'];
      }

      // Render the icon based on the file extension.
      if (!empty($file_extension)) {
        $icon = theme('paddle_core_icon', array(
          'type' => 'file',
          'name' => $file_extension,
          'attributes' => array('class' => 'file-' . drupal_html_class($file_extension)),
        ));
      }
      else {
        $icon = theme('paddle_core_icon', array(
          'name' => 'file',
          'type' => 'file',
        ));
      }

      // Get the HTML for the list item.
      $item = theme('paddle_core_listing_table_item', array(
        'content' => $cirro_file['title'],
        'icon' => $icon,
        'extension' => $file_extension,
      ));

      $link_options['attributes']['target'] = '_blank';
      $link_options['html'] = TRUE;

      $list_item = l($item, $cirro_file['value'], $link_options);
      $list_items[] = $list_item;
    }
    $variables['files_list'] = theme('paddle_core_listing_table', array('items' => $list_items));
  }

  // Check if the resource is a Methodology.
  if (!empty($wrapper->field_paddle_cirro_methodology) && !empty($wrapper->field_paddle_cirro_methodology->value())) {
    // The pane title differs on the CIRRO resource type.
    $pane_title = t('Included in this methodology');
    // Methodologies also contain other CIRRO resources.
    if (!empty($wrapper->field_paddle_cirro_resources) && !empty($wrapper->field_paddle_cirro_resources->value())) {
      $cirro_resources = $wrapper->field_paddle_cirro_resources->value();
      // Add the children as links to their own page.
      foreach ($cirro_resources as $cirro_resource) {
        if (!empty($cirro_resource)) {
          $variables['children'][] = l($cirro_resource->title, 'node/' . $cirro_resource->nid);
        }
      }
    }
  }
  else {
    $pane_title = t('Get started with these materials and services');
  }

  if (!empty($variables)) {
    $output = theme('paddle_cirro_materials', $variables);

    // Allow the pane sections to contribute output.
    if (module_exists('paddle_panes')) {
      $conf['top'] = array(
        'enable_section' => 1,
        'section_text' => $pane_title,
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
