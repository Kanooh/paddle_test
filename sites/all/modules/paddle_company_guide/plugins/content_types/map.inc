<?php

/**
 * @file
 * CTools content type plugin to embed the map of Company pages.
 */

$plugin = array(
  'title' => t('Company Location'),
  'description' => t('Displays the company on a map.'),
  'category' => t('Paddle Company Guide'),
  'required context' => new ctools_context_required(t('Node'), 'node'),
  'paddle_panes sections' => FALSE,
);

/**
 * Render the content type.
 */
function paddle_company_guide_map_content_type_render($subtype, $conf, $args, $context) {
  $block = new stdClass();
  $block->title = '';
  $block->content = '';
  $element = array();

  $node = $context->data;
  $wrapper = entity_metadata_wrapper('node', $node);

  if (!empty($wrapper->field_paddle_address_geocode) && !empty($wrapper->field_paddle_address_geocode->value())) {
    $geocodefield = $wrapper->field_paddle_address_geocode->value();


    $latitude = $geocodefield['lat'];
    $longitude = $geocodefield['lon'];

    $markers[] = array(
      'latitude' => $latitude,
      'longitude' => $longitude,
      'text' => $node->title,
    );

    $settings = array(
      'latitude' => $latitude,
      'longitude' => $longitude,
      'zoom' => 10,
      'markers' => $markers,
      'width' => '100%',
      'height' => '215px',
      'type' => 'Satellite',
    );

    $element = array(
      '#type' => 'gmap',
      '#gmap_settings' => $settings,
    );
  }

  if (!empty($element)) {
    $block->content = drupal_render($element);
  }

  return $block;
}
