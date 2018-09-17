<?php

/**
 * @file
 * Definition of paddle_reference_tracker_handler_field_entity_bundle.
 */

/**
 * Field handler to render the bundle of the entity.
 *
 * @ingroup views_field_handlers
 */
class paddle_reference_tracker_handler_field_entity_bundle extends views_handler_field {

  /**
   * Renders the type.
   */
  public function render($values) {
    // Get the bundle of the entity and print it.
    $entity_id = $this->get_value($values);
    $entity_type = $values->{$this->aliases['entity_type']};
    $info = entity_get_info($entity_type);

    $wrapper = entity_metadata_wrapper($entity_type, $entity_id);
    $bundle = $wrapper->getBundle();

    return !empty($info['bundles'][$bundle]) ? $info['bundles'][$bundle]['label'] : '';
  }

}
