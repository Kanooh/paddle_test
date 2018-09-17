<?php

/**
 * @file
 * CTools content type plugin to embed the Contact Information.
 */

$plugin = array(
  'title' => t('CIRRO Contact Information'),
  'description' => t('Who to contact when having questions about the CIRRO resource.'),
  'category' => t('Paddle CIRRO'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'paddle_panes sections' => TRUE,
);

/**
 * Render the content type.
 */
function paddle_cirro_cirro_contact_info_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $block->content = '';
  $variables = array();
  $contact_info = field_view_field('node', $context->data, 'field_paddle_cirro_contact_info');

  // The body text value is stored in ['#items'][0]['value']. It is the only
  // empty check we can perform at this point without computing new stuff.
  if (!empty($contact_info['#items'][0]['value'])) {
    $variables['contact_info'] = $contact_info;
    // Hide the label.
    $variables['contact_info']['#label_display'] = 'hidden';
  }

  // @TODO: Once the Logo logic has been added we can uncomment this check.
  /* if (!empty($variables)) { */
  $output = theme('paddle_cirro_contact_information', $variables);

  // Allow the pane sections to contribute output.
  if (module_exists('paddle_panes')) {
    $conf['top'] = array(
      'enable_section' => 1,
      'section_text' => t('More information'),
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
  /* } */

  return $block;
}
