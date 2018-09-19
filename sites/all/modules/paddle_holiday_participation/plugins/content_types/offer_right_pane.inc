<?php

/**
 * @file
 * CTools content type plugin which represents the right pane
 * of an offer content type.
 */

$plugin = array(
  'title' => t('Offer right pane'),
  'no title override' => TRUE,
  'description' => t('The content which is included in the right pane of the offer content type.'),
  'single' => TRUE,
  'defaults' => array(),
  'category' => array(t('Offers'), -10),
  'required context' => new ctools_context_required(t('Node'), 'node'),
);

/**
 * Render the content type.
 */
function paddle_holiday_participation_offer_right_pane_content_type_render($subtype, $conf, $args, $context) {
  // Return the output as a block.
  $block = new stdClass();
  $block->title = '';
  $block->content = '';
  $name = '';
  $variables = array();
  $assets_path = base_path() . drupal_get_path('module', 'paddle_holiday_participation') . '/assets/';

  $node = $context->data;
  $template = 'paddle_holiday_participation_offer_right_pane';
  $wrapper = entity_metadata_wrapper('node', $node);

  $unformatted_address = $wrapper->field_hp_address->value();

  if (!empty($unformatted_address['name_line'])) {
    $name = check_plain($unformatted_address['name_line']);
  }
  else {
    if (!empty($unformatted_address['first_name'])) {
      $name .= check_plain($unformatted_address['first_name']) . ' ';
    }
    if (!empty($unformatted_address['last_name'])) {
      $name .= check_plain($unformatted_address['last_name']);
    }
  }

  $variables['address']['image_path'] = $assets_path . 'addressmarker.png';

  $variables['address']['name'] = $name;

  if (!empty($unformatted_address['thoroughfare'])) {
    $variables['address']['thoroughfare'] = check_plain($unformatted_address['thoroughfare']);
  }

  if (!empty($unformatted_address['premise'])) {
    $variables['address']['premise'] = check_plain($unformatted_address['premise']);
  }

  if (!empty($unformatted_address['locality'])) {
    $municipality = '';

    if (!empty($unformatted_address['postal_code'])) {
      $municipality .= check_plain($unformatted_address['postal_code']) . ' ';
    }

    $municipality .= check_plain($unformatted_address['locality']);
    $variables['address']['municipality'] = $municipality;
  }

  $website = $wrapper->field_hp_website->value();

  if (!empty($website['value'])) {
    $variables['website']['url'] = check_plain($website['value']);
    $variables['website']['image_path'] = $assets_path . 'website.png';
  }

  $geofield = $wrapper->field_paddle_geofield->value();

  if (!empty($geofield)) {
    $display = array(
      'type' => 'geofield_map_map',
      'label' => 'hidden',
      'settings' => array(
        'geofield_map_maptype' => 'map',
        'geofield_map_height' => '200px',
        'geofield_map_zoom' => 10,
        'geofield_map_center' => array(
          'geocode' => 'Find my location',
          'lat' => 50.8503,
          'lon' => 4.3517,
        ),
      ),
    );
    $variables['geofield'] = field_view_field('node', $node, 'field_paddle_geofield', $display);
  }

  $facilities_input = $wrapper->field_hp_facilities->value();

  if (!empty($facilities_input)) {
    foreach ($facilities_input as $facility) {
      if ($facility !== '0') {
        $variables['facilities'][] = array(
          'label' => paddle_holiday_participation_get_multivalue_label($facility, 'field_hp_facilities'),
          'image_path' => $assets_path . $facility . '.png',
        );
      }
    }
  }

  $youtube_link = $wrapper->field_hp_youtube->value();

  if (!empty($youtube_link['value'])) {
    $variables['social_media']['youtube'] = array(
      'url' => check_plain($youtube_link['value']),
      'image_path' => $assets_path . 'youtube.png',
    );
  }

  $facebook_link = $wrapper->field_hp_facebook->value();

  if (!empty($facebook_link['value'])) {
    $variables['social_media']['facebook'] = array(
      'url' => check_plain($facebook_link['value']),
      'image_path' => $assets_path . 'facebook.png',
    );
  }

  $twitter_link = $wrapper->field_hp_twitter->value();

  if (!empty($twitter_link['value'])) {
    $variables['social_media']['twitter'] = array(
      'url' => check_plain($twitter_link['value']),
      'image_path' => $assets_path . 'twitter.png',
    );
  }

  $block->content = theme($template, $variables);

  return $block;
}

/**
 * Edit form.
 */
function paddle_holiday_participation_offer_right_pane_content_type_edit_form($form, &$form_state) {
  return $form;
}
