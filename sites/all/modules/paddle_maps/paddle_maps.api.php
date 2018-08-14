<?php

/**
 * @file
 * API documentation for the maps implementation of Paddle.
 *
 * Through this API we allow a custom content type to index its address
 * through the Paddle Maps paddlet. Three steps need to be undertaken to
 * be able to use the Paddle Maps paddlet for your content type.
 *
 * 1) Implement hook_paddle_maps_supported_content_types_alter to add
 * your content type.
 *
 * 2) Implement hook_paddle_maps_add_index_fields_alter to add the fields which
 * you want to be indexed (and be searched on) on the Paddle Maps Search page.
 *
 * 3) Implement hook_views_default_views_alter to add those fields above to the
 * Maps view itself.
 *
 * 4) Implement hook_modules_enabled to add your content type to
 * paddle_maps_index_contenttype_fields and reinitialize your search index.
 *
 * @see paddle_contact_person_paddle_maps_supported_content_types_alter
 * @see paddle_contact_person_paddle_maps_add_index_fields_alter
 * @see paddle_contact_person_views_default_views_alter
 * @see paddle_contact_person_modules_enabled
 */

/**
 * Allow modules to alter the search index fields of Maps.
 *
 * @param object $index
 *   The search index of the Paddle Maps content type.
 * @param bool $paddle_maps_index_contenttype_fields
 *   Whether the content type needs to be indexed.
 * @param bool $something_changed
 *   Whether the index has changed.
 */
function hook_paddle_maps_add_index_fields_alter(&$index, &$paddle_maps_index_contenttype_fields, &$something_changed) {
  $index->options['fields']['field_paddle_address_geocode:lat']['type'] = 'decimal';
  $index->options['fields']['field_paddle_address_geocode:lon']['type'] = 'decimal';
  $index->options['fields']['field_paddle_address_geocode:left']['type'] = 'decimal';
  $index->options['fields']['field_paddle_address_geocode:top']['type'] = 'decimal';
  $index->options['fields']['field_paddle_address_geocode:right']['type'] = 'decimal';
  $index->options['fields']['field_paddle_address_geocode:bottom']['type'] = 'decimal';
  $index->options['fields']['field_paddle_address_geocode:srid']['type'] = 'integer';
  $index->options['fields']['field_paddle_address_geocode:latlon']['type'] = 'string';
  $index->options['fields']['field_paddle_address_geocode:schemaorg_shape']['type'] = 'string';
  $paddle_maps_index_contenttype_fields['paddle_test'] = TRUE;
  $something_changed = TRUE;
}

/**
 * Allow modules to add more supported content types.
 *
 * @param string $supported_types
 *   The machine name of content types.
 */
function hook_paddle_maps_supported_content_types_alter(&$supported_types) {
  $supported_types[] = 'paddle_test_page';
}
